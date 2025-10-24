<?php
/**
 * Student Management Class
 *
 * Handles all student-related operations including CRUD operations,
 * WordPress user integration, and list display.
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin
 * @version    1.0.0
 */

class IGM_Academy_Students {

    /**
     * Display the students management page
     *
     * Routes to appropriate view based on action parameter
     *
     * @since    1.0.0
     * @return   void
     */
    public static function display_students_page() {
        // Handle POST requests (form submissions)
        if ( self::handle_post_request() ) {
            return; // Already redirected
        }

        // Handle GET requests (display views)
        self::handle_get_request();
    }

    /**
     * Handle POST request for student operations
     *
     * @since    1.0.0
     * @return   bool    True if request was handled and redirected
     */
    private static function handle_post_request() {
        if ( ! isset( $_POST['igm_action'] ) || ! isset( $_POST['igm_student_nonce'] ) ) {
            return false;
        }

        if ( ! check_admin_referer( 'igm_student_action', 'igm_student_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'igm-academy-manager' ) );
        }

        $action = sanitize_text_field( $_POST['igm_action'] );

        switch ( $action ) {
            case 'add':
            case 'edit':
                self::save_student();
                return true;

            case 'delete':
                if ( isset( $_POST['student_id'] ) ) {
                    self::delete_student( intval( $_POST['student_id'] ) );
                    return true;
                }
                break;

            case 'restore':
                if ( isset( $_POST['student_id'] ) ) {
                    self::restore_student( intval( $_POST['student_id'] ) );
                    self::redirect_with_success( 'restored' );
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * Handle GET request to display appropriate view
     *
     * @since    1.0.0
     * @return   void
     */
    private static function handle_get_request() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list';
        $student_id = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;

        switch ( $action ) {
            case 'edit':
                if ( $student_id > 0 ) {
                    self::display_edit_form( $student_id );
                } else {
                    self::redirect_with_error( 'invalid_id' );
                }
                break;

            case 'add':
                self::display_add_form();
                break;

            default:
                self::display_students_list();
                break;
        }
    }

    /**
     * Display the students list view
     *
     * @since    1.0.0
     * @return   void
     */
    private static function display_students_list() {
        global $wpdb;

        $status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'active';
        $students = self::get_students_with_search( $status_filter );
        $total_students = count( $students );

        // Get counts for tabs
        $active_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_students WHERE status = 'active'" );
        $deleted_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_students WHERE status = 'deleted'" );

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/students-list.php';
    }

    /**
     * Get students with optional search filtering
     *
     * @since    1.0.0
     * @param    string    $status    Status filter ('active' or 'deleted')
     * @return   array     Array of student objects
     */
    private static function get_students_with_search( $status = 'active' ) {
        global $wpdb;

        $search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
        $table = $wpdb->prefix . 'igm_students';

        if ( ! empty( $search ) ) {
            $search_term = '%' . $wpdb->esc_like( $search ) . '%';

            return $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM {$table}
                WHERE status = %s
                  AND (first_name LIKE %s
                   OR last_name LIKE %s
                   OR email LIKE %s)
                ORDER BY last_name, first_name",
                $status,
                $search_term,
                $search_term,
                $search_term
            ) );
        }

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$table}
             WHERE status = %s
             ORDER BY last_name, first_name",
            $status
        ) );
    }

    /**
     * Display the add student form
     *
     * @since    1.0.0
     * @return   void
     */
    private static function display_add_form() {
        $student = null; // No student data for add form
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/student-form.php';
    }

    /**
     * Display the edit student form
     *
     * @since    1.0.0
     * @param    int    $student_id    Student ID to edit
     * @return   void
     */
    private static function display_edit_form( $student_id ) {
        $student = self::get_student_by_id( $student_id );

        if ( ! $student ) {
            wp_die( __( 'Student not found.', 'igm-academy-manager' ) );
        }

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/student-form.php';
    }

    /**
     * Get student by ID
     *
     * @since    1.0.0
     * @param    int    $student_id    Student ID
     * @return   object|null    Student object or null if not found
     */
    private static function get_student_by_id( $student_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_students WHERE id = %d",
            $student_id
        ) );
    }

    /**
     * Redirect with error message
     *
     * @since    1.0.0
     * @param    string    $error_code    Error code
     * @return   void
     */
    private static function redirect_with_error( $error_code ) {
        wp_redirect( add_query_arg(
            array(
                'page'  => 'igm-students',
                'error' => $error_code
            ),
            admin_url( 'admin.php' )
        ) );
        exit;
    }

    /**
     * Redirect with success message
     *
     * @since    1.0.0
     * @param    string    $message    Success message code
     * @return   void
     */
    private static function redirect_with_success( $message = 'success' ) {
        wp_redirect( add_query_arg(
            array(
                'page'    => 'igm-students',
                'message' => $message
            ),
            admin_url( 'admin.php' )
        ) );
        exit;
    }

    /**
     * Save student (create or update)
     *
     * Creates WordPress user account and syncs student data
     *
     * @since    1.0.0
     * @return   void
     */
    private static function save_student() {
        $student_id = isset( $_POST['student_id'] ) ? intval( $_POST['student_id'] ) : 0;

        // Validate and sanitize input
        $student_data = self::sanitize_student_input();

        // Check for duplicate email
        if ( self::email_exists_for_other_student( $student_data['email'], $student_id ) ) {
            wp_die( __( 'Error: A student with this email already exists.', 'igm-academy-manager' ) );
        }

        // Handle WordPress user (create or update)
        $user_id = self::handle_wordpress_user( $student_id, $student_data );

        if ( is_wp_error( $user_id ) ) {
            wp_die( __( 'Error with WordPress user: ', 'igm-academy-manager' ) . $user_id->get_error_message() );
        }

        // Save student record
        $result = self::save_student_record( $student_id, $user_id, $student_data );

        if ( $result ) {
            self::redirect_with_success( $student_id > 0 ? 'updated' : 'created' );
        } else {
            wp_die( __( 'Error saving student record.', 'igm-academy-manager' ) );
        }
    }

    /**
     * Sanitize student input data
     *
     * @since    1.0.0
     * @return   array    Sanitized student data
     */
    private static function sanitize_student_input() {
        return [
            'first_name'      => sanitize_text_field( $_POST['first_name'] ?? '' ),
            'last_name'       => sanitize_text_field( $_POST['last_name'] ?? '' ),
            'email'           => sanitize_email( $_POST['email'] ?? '' ),
            'phone'           => sanitize_text_field( $_POST['phone'] ?? '' ),
            'gender'          => sanitize_text_field( $_POST['gender'] ?? '' ),
            'birth_date'      => ! empty( $_POST['birth_date'] ) ? sanitize_text_field( $_POST['birth_date'] ) : null,
            'notes'           => sanitize_textarea_field( $_POST['notes'] ?? '' ),
            'total_classes'   => intval( $_POST['total_classes'] ?? 0 ),
            'pending_classes' => intval( $_POST['pending_classes'] ?? 0 ),
        ];
    }

    /**
     * Check if email exists for another student
     *
     * @since    1.0.0
     * @param    string    $email         Email to check
     * @param    int       $student_id    Current student ID (0 for new)
     * @return   bool      True if email exists for another student
     */
    private static function email_exists_for_other_student( $email, $student_id = 0 ) {
        global $wpdb;

        if ( $student_id > 0 ) {
            $existing = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}igm_students
                 WHERE email = %s AND id != %d AND status != 'deleted'",
                $email,
                $student_id
            ) );
        } else {
            $existing = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}igm_students
                 WHERE email = %s AND status != 'deleted'",
                $email
            ) );
        }

        return (bool) $existing;
    }

    /**
     * Handle WordPress user creation or update
     *
     * @since    1.0.0
     * @param    int      $student_id      Student ID (0 for new)
     * @param    array    $student_data    Student data
     * @return   int|WP_Error    User ID or WP_Error on failure
     */
    private static function handle_wordpress_user( $student_id, $student_data ) {
        if ( $student_id > 0 ) {
            // Update existing WordPress user
            return self::update_wordpress_user( $student_id, $student_data );
        } else {
            // Create new WordPress user
            return self::create_wordpress_user( $student_data );
        }
    }

    /**
     * Update existing WordPress user
     *
     * @since    1.0.0
     * @param    int      $student_id      Student ID
     * @param    array    $student_data    Student data
     * @return   int      User ID
     */
    private static function update_wordpress_user( $student_id, $student_data ) {
        global $wpdb;

        $current_student = $wpdb->get_row( $wpdb->prepare(
            "SELECT user_id FROM {$wpdb->prefix}igm_students WHERE id = %d",
            $student_id
        ) );

        $user_id = $current_student->user_id;

        wp_update_user( [
            'ID'           => $user_id,
            'user_email'   => $student_data['email'],
            'display_name' => $student_data['first_name'] . ' ' . $student_data['last_name'],
            'first_name'   => $student_data['first_name'],
            'last_name'    => $student_data['last_name'],
        ] );

        return $user_id;
    }

    /**
     * Create new WordPress user
     *
     * @since    1.0.0
     * @param    array    $student_data    Student data
     * @return   int|WP_Error    User ID or WP_Error on failure
     */
    private static function create_wordpress_user( $student_data ) {
        $username = self::generate_unique_username( $student_data['email'] );
        $password = wp_generate_password( 12, true, true );

        $user_id = wp_create_user( $username, $password, $student_data['email'] );

        if ( is_wp_error( $user_id ) ) {
            return $user_id;
        }

        // Set user role and meta
        $user = new WP_User( $user_id );
        $user->set_role( 'igm_student' );

        wp_update_user( [
            'ID'           => $user_id,
            'display_name' => $student_data['first_name'] . ' ' . $student_data['last_name'],
            'first_name'   => $student_data['first_name'],
            'last_name'    => $student_data['last_name'],
        ] );

        // Send notification email
        wp_new_user_notification( $user_id, null, 'both' );

        return $user_id;
    }

    /**
     * Generate unique username from email
     *
     * @since    1.0.0
     * @param    string    $email    Email address
     * @return   string    Unique username
     */
    private static function generate_unique_username( $email ) {
        $username = 'student_' . sanitize_user( $email );
        $base_username = $username;
        $counter = 1;

        while ( username_exists( $username ) ) {
            $username = $base_username . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Save student record to database
     *
     * @since    1.0.0
     * @param    int      $student_id      Student ID (0 for new)
     * @param    int      $user_id         WordPress user ID
     * @param    array    $student_data    Student data
     * @return   bool     True on success, false on failure
     */
    private static function save_student_record( $student_id, $user_id, $student_data ) {
        global $wpdb;

        $data = [
            'user_id'         => $user_id,
            'first_name'      => $student_data['first_name'],
            'last_name'       => $student_data['last_name'],
            'email'           => $student_data['email'],
            'phone'           => $student_data['phone'],
            'gender'          => $student_data['gender'],
            'birth_date'      => $student_data['birth_date'],
            'notes'           => $student_data['notes'],
            'total_classes'   => $student_data['total_classes'],
            'pending_classes' => $student_data['pending_classes'],
        ];

        $format = [ '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' ];

        if ( $student_id > 0 ) {
            return $wpdb->update(
                "{$wpdb->prefix}igm_students",
                $data,
                [ 'id' => $student_id ],
                $format,
                [ '%d' ]
            );
        } else {
            return $wpdb->insert(
                "{$wpdb->prefix}igm_students",
                $data,
                $format
            );
        }
    }

    /**
     * Soft delete student (mark as deleted, keep data for returning students)
     *
     * @since    1.0.0
     * @param    int    $student_id    Student ID to delete
     * @return   void
     */
    private static function delete_student( $student_id ) {
        $student = self::get_student_by_id( $student_id );

        if ( ! $student ) {
            self::redirect_with_error( 'student_not_found' );
            return;
        }

        // Soft delete: Update status instead of physical deletion
        self::soft_delete_student( $student_id );

        // Disable WordPress user (remove role but keep account)
        if ( $student->user_id ) {
            self::disable_wordpress_user( $student->user_id );
        }

        self::redirect_with_success( 'deleted' );
    }

    /**
     * Soft delete student record (mark as deleted)
     *
     * @since    1.0.0
     * @param    int    $student_id    Student ID
     * @return   bool   True on success, false on failure
     */
    private static function soft_delete_student( $student_id ) {
        global $wpdb;

        $result = $wpdb->update(
            $wpdb->prefix . 'igm_students',
            [
                'status'     => 'deleted',
                'deleted_at' => current_time( 'mysql' )
            ],
            [ 'id' => $student_id ],
            [ '%s', '%s' ],
            [ '%d' ]
        );

        return $result !== false;
    }

    /**
     * Disable WordPress user (remove role, keep account for potential return)
     *
     * @since    1.0.0
     * @param    int    $user_id    WordPress user ID
     * @return   void
     */
    private static function disable_wordpress_user( $user_id ) {
        $user = new WP_User( $user_id );

        // Remove igm_student role but keep the user account
        $user->remove_role( 'igm_student' );

        // Optionally add a "suspended" role or metadata
        update_user_meta( $user_id, 'igm_account_suspended', true );
        update_user_meta( $user_id, 'igm_suspended_at', current_time( 'mysql' ) );
    }

    /**
     * Restore deleted student (reactivate)
     *
     * @since    1.0.0
     * @param    int    $student_id    Student ID to restore
     * @return   void
     */
    private static function restore_student( $student_id ) {
        global $wpdb;

        // Get student info
        $student = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_students WHERE id = %d",
            $student_id
        ) );

        if ( ! $student ) {
            return;
        }

        // Restore student status
        $wpdb->update(
            $wpdb->prefix . 'igm_students',
            [
                'status'     => 'active',
                'deleted_at' => null
            ],
            [ 'id' => $student_id ],
            [ '%s', '%s' ],
            [ '%d' ]
        );

        // Restore WordPress user role
        if ( $student->user_id ) {
            $user = new WP_User( $student->user_id );
            $user->add_role( 'igm_student' );

            delete_user_meta( $student->user_id, 'igm_account_suspended' );
            delete_user_meta( $student->user_id, 'igm_suspended_at' );
        }
    }
}

