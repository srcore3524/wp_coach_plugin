<?php
/**
 * Coach management functionality
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin
 */

class IGM_Academy_Coaches {

    /**
     * Display the coaches page
     *
     * @since    1.0.0
     */
    public static function display_coaches_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        // Handle form submissions BEFORE any output
        if ( isset( $_POST['igm_action'] ) && isset( $_POST['igm_coach_nonce'] ) &&
             check_admin_referer( 'igm_coach_action', 'igm_coach_nonce' ) ) {
            if ( $_POST['igm_action'] === 'add' || $_POST['igm_action'] === 'edit' ) {
                self::save_coach();
                return; // Exit after redirect
            } elseif ( $_POST['igm_action'] === 'delete' && isset( $_POST['coach_id'] ) ) {
                self::delete_coach( intval( $_POST['coach_id'] ) );
                return; // Exit after redirect
            }
        }

        // Get action
        $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list';
        $coach_id = isset( $_GET['coach_id'] ) ? intval( $_GET['coach_id'] ) : 0;

        if ( $action === 'edit' && $coach_id > 0 ) {
            self::display_edit_form( $coach_id );
        } elseif ( $action === 'add' ) {
            self::display_add_form();
        } else {
            self::display_coaches_list();
        }
    }

    /**
     * Display coaches list
     *
     * @since    1.0.0
     */
    private static function display_coaches_list() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        // Get search query
        $search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

        // Build query
        $where = '';
        if ( ! empty( $search ) ) {
            $where = $wpdb->prepare(
                " WHERE first_name LIKE %s OR last_name LIKE %s OR email LIKE %s",
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%'
            );
        }

        $coaches = $wpdb->get_results( "SELECT * FROM $table_name $where ORDER BY last_name, first_name" );
        $total_coaches = count( $coaches );

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/coaches-list.php';
    }

    /**
     * Display add coach form
     *
     * @since    1.0.0
     */
    private static function display_add_form() {
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/coach-form.php';
    }

    /**
     * Display edit coach form
     *
     * @since    1.0.0
     * @param    int    $coach_id    Coach ID
     */
    private static function display_edit_form( $coach_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        $coach = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $coach_id ) );

        if ( ! $coach ) {
            wp_die( __( 'Coach not found.', 'igm-academy-manager' ) );
        }

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/coach-form.php';
    }

    /**
     * Save coach (add or edit)
     *
     * @since    1.0.0
     */
    private static function save_coach() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        $coach_id = isset( $_POST['coach_id'] ) ? intval( $_POST['coach_id'] ) : 0;
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name = sanitize_text_field( $_POST['last_name'] );
        $email = sanitize_email( $_POST['email'] );
        $phone = sanitize_text_field( $_POST['phone'] );
        $specialty = sanitize_text_field( $_POST['specialty'] );
        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : null;

        $data = array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'phone'      => $phone,
            'specialty'  => $specialty,
        );

        $format = array( '%s', '%s', '%s', '%s', '%s' );

        if ( $coach_id > 0 ) {
            // Update existing coach
            if ( $user_id ) {
                $data['user_id'] = $user_id;
                $format[] = '%d';
            }
            $wpdb->update( $table_name, $data, array( 'id' => $coach_id ), $format, array( '%d' ) );
            $message = __( 'Coach updated successfully.', 'igm-academy-manager' );
        } else {
            // Insert new coach
            // Optionally create WordPress user if requested
            if ( isset( $_POST['create_wp_user'] ) && $_POST['create_wp_user'] === 'yes' ) {
                $user_id = self::create_wordpress_user( $email, $first_name, $last_name );
                if ( $user_id ) {
                    $data['user_id'] = $user_id;
                    $format[] = '%d';
                }
            }

            $wpdb->insert( $table_name, $data, $format );
            $message = __( 'Coach added successfully.', 'igm-academy-manager' );
        }

        // Redirect with success message
        wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'success' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Create WordPress user for coach
     *
     * @since    1.0.0
     * @param    string    $email       Email address
     * @param    string    $first_name  First name
     * @param    string    $last_name   Last name
     * @return   int|null  User ID or null on failure
     */
    private static function create_wordpress_user( $email, $first_name, $last_name ) {
        // Check if user already exists
        if ( email_exists( $email ) ) {
            return get_user_by( 'email', $email )->ID;
        }

        // Generate random password
        $password = wp_generate_password( 12, true, true );

        // Create user
        $user_id = wp_create_user( $email, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            return null;
        }

        // Update user meta
        wp_update_user( array(
            'ID'           => $user_id,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'display_name' => $first_name . ' ' . $last_name,
        ) );

        // Set user role to coach
        $user = new WP_User( $user_id );
        $user->set_role( 'igm_coach' );

        // Send password reset email
        wp_send_new_user_notifications( $user_id, 'both' );

        return $user_id;
    }

    /**
     * Delete coach
     *
     * @since    1.0.0
     * @param    int    $coach_id    Coach ID
     */
    private static function delete_coach( $coach_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        // Check if coach has assigned groups
        $groups_table = $wpdb->prefix . 'igm_groups';
        $has_groups = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $groups_table WHERE coach_id = %d",
            $coach_id
        ) );

        if ( $has_groups > 0 ) {
            wp_redirect( add_query_arg( array(
                'page'    => 'igm-coaches',
                'message' => 'error',
                'error'   => 'has_groups'
            ), admin_url( 'admin.php' ) ) );
            exit;
        }

        $wpdb->delete( $table_name, array( 'id' => $coach_id ), array( '%d' ) );

        wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'deleted' ), admin_url( 'admin.php' ) ) );
        exit;
    }
}
