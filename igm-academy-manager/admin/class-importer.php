<?php
/**
 * Data import functionality
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin
 */

class IGM_Academy_Importer {

    /**
     * Display the import page
     *
     * @since    1.0.0
     */
    public static function display_import_page() {
        // Handle file upload
        if ( isset( $_POST['igm_import_submit'] ) && check_admin_referer( 'igm_import_action', 'igm_import_nonce' ) ) {
            if ( isset( $_FILES['import_file'] ) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK ) {
                $result = self::process_import();
            }
        }

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/import-page.php';
    }

    /**
     * Process file import
     *
     * @since    1.0.0
     * @return   array    Import results
     */
    private static function process_import() {
        $file = $_FILES['import_file'];
        $import_type = sanitize_text_field( $_POST['import_type'] );

        // Verify file type
        $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

        if ( $import_type === 'students' && $file_ext === 'csv' ) {
            return self::import_students_csv( $file['tmp_name'] );
        } elseif ( in_array( $import_type, array( 'exercises_tennis', 'exercises_padel' ) ) && $file_ext === 'xlsx' ) {
            return self::import_exercises_xlsx( $file['tmp_name'], $import_type );
        }

        return array(
            'success' => false,
            'message' => __( 'Invalid file type.', 'igm-academy-manager' ),
        );
    }

    /**
     * Import students from CSV
     *
     * @since    1.0.0
     * @param    string    $file_path    Path to CSV file
     * @return   array     Import results
     */
    private static function import_students_csv( $file_path ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_students';

        $imported = 0;
        $errors = array();

        // Open file
        if ( ( $handle = fopen( $file_path, 'r' ) ) !== false ) {
            // Skip header row
            $header = fgetcsv( $handle, 0, ';' );

            // Process each row
            while ( ( $row = fgetcsv( $handle, 0, ';' ) ) !== false ) {
                // Skip empty rows
                if ( empty( $row[0] ) && empty( $row[1] ) ) {
                    continue;
                }

                // Parse data
                $first_name = isset( $row[0] ) ? trim( str_replace( '"', '', $row[0] ) ) : '';
                $last_name = isset( $row[1] ) ? trim( str_replace( '"', '', $row[1] ) ) : '';
                $email = isset( $row[2] ) ? trim( $row[2] ) : '';
                $phone = isset( $row[3] ) ? trim( $row[3] ) : '';
                $gender = isset( $row[4] ) ? trim( $row[4] ) : '';
                $birth_date_str = isset( $row[5] ) ? trim( str_replace( '"', '', $row[5] ) ) : '';
                $notes = isset( $row[6] ) ? trim( str_replace( '"', '', $row[6] ) ) : '';
                $last_class = isset( $row[7] ) ? trim( str_replace( '"', '', $row[7] ) ) : '';
                $total_classes = isset( $row[8] ) ? intval( $row[8] ) : 0;
                $pending_classes = isset( $row[9] ) ? intval( $row[9] ) : 0;

                // Parse Spanish date format (e.g., "10 de July de 1976")
                $birth_date = self::parse_spanish_date( $birth_date_str );

                // Validate email
                if ( empty( $email ) || ! is_email( $email ) ) {
                    $errors[] = sprintf(
                        __( 'Invalid email for: %s %s', 'igm-academy-manager' ),
                        $first_name,
                        $last_name
                    );
                    continue;
                }

                // Check if student already exists
                $existing_student = $wpdb->get_row( $wpdb->prepare(
                    "SELECT id, user_id FROM $table_name WHERE email = %s",
                    $email
                ) );

                if ( $existing_student ) {
                    // Update existing student
                    $user_id = $existing_student->user_id;

                    // Update WordPress user
                    wp_update_user( array(
                        'ID'           => $user_id,
                        'display_name' => $first_name . ' ' . $last_name,
                        'first_name'   => $first_name,
                        'last_name'    => $last_name,
                    ) );

                    // Update student record
                    $wpdb->update(
                        $table_name,
                        array(
                            'first_name'      => $first_name,
                            'last_name'       => $last_name,
                            'phone'           => $phone,
                            'gender'          => $gender,
                            'birth_date'      => $birth_date,
                            'notes'           => $notes,
                            'total_classes'   => $total_classes,
                            'pending_classes' => $pending_classes,
                        ),
                        array( 'id' => $existing_student->id ),
                        array( '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' ),
                        array( '%d' )
                    );
                    $imported++;
                } else {
                    // Create new student with WordPress user
                    $username = 'student_' . sanitize_user( $email );
                    $password = wp_generate_password( 12, true, true );

                    // Check if username exists, add number if needed
                    $base_username = $username;
                    $counter = 1;
                    while ( username_exists( $username ) ) {
                        $username = $base_username . '_' . $counter;
                        $counter++;
                    }

                    // Create WordPress user
                    $user_id = wp_create_user( $username, $password, $email );

                    if ( is_wp_error( $user_id ) ) {
                        $errors[] = sprintf(
                            __( 'Failed to create user for: %s %s (%s) - %s', 'igm-academy-manager' ),
                            $first_name,
                            $last_name,
                            $email,
                            $user_id->get_error_message()
                        );
                        continue;
                    }

                    // Set user role
                    $user = new WP_User( $user_id );
                    $user->set_role( 'igm_student' );

                    // Set user meta
                    wp_update_user( array(
                        'ID'           => $user_id,
                        'display_name' => $first_name . ' ' . $last_name,
                        'first_name'   => $first_name,
                        'last_name'    => $last_name,
                    ) );

                    // Insert student record
                    $result = $wpdb->insert(
                        $table_name,
                        array(
                            'user_id'         => $user_id,
                            'first_name'      => $first_name,
                            'last_name'       => $last_name,
                            'email'           => $email,
                            'phone'           => $phone,
                            'gender'          => $gender,
                            'birth_date'      => $birth_date,
                            'notes'           => $notes,
                            'total_classes'   => $total_classes,
                            'pending_classes' => $pending_classes,
                        ),
                        array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' )
                    );

                    if ( $result ) {
                        $imported++;
                        // Send notification email with login credentials
                        wp_new_user_notification( $user_id, null, 'both' );
                    } else {
                        $errors[] = sprintf(
                            __( 'Failed to insert student record: %s %s (%s)', 'igm-academy-manager' ),
                            $first_name,
                            $last_name,
                            $email
                        );
                        // Cleanup: delete the WordPress user if student record insertion failed
                        wp_delete_user( $user_id );
                    }
                }
            }

            fclose( $handle );
        }

        return array(
            'success' => true,
            'imported' => $imported,
            'errors' => $errors,
        );
    }

    /**
     * Parse Spanish date format
     *
     * @since    1.0.0
     * @param    string    $date_str    Date string in Spanish format
     * @return   string    Date in Y-m-d format or null
     */
    private static function parse_spanish_date( $date_str ) {
        if ( empty( $date_str ) ) {
            return null;
        }

        // Spanish month names
        $spanish_months = array(
            'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
            'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
            'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
            'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
        );

        // Replace Spanish month names with English
        foreach ( $spanish_months as $english => $spanish ) {
            $date_str = str_ireplace( $spanish, $english, $date_str );
        }

        // Remove "de" (Spanish for "of")
        $date_str = str_replace( ' de ', ' ', $date_str );

        // Try to parse the date
        $timestamp = strtotime( $date_str );

        if ( $timestamp ) {
            return date( 'Y-m-d', $timestamp );
        }

        return null;
    }

    /**
     * Import exercises from Excel
     *
     * @since    1.0.0
     * @param    string    $file_path      Path to Excel file
     * @param    string    $import_type    Type of import (exercises_tennis or exercises_padel)
     * @return   array     Import results
     */
    private static function import_exercises_xlsx( $file_path, $import_type ) {
        // This will be implemented in Phase 3
        return array(
            'success' => false,
            'message' => __( 'Excel import will be implemented in Phase 3.', 'igm-academy-manager' ),
        );
    }
}
