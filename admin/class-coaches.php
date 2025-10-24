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
            } elseif ( $_POST['igm_action'] === 'restore' && isset( $_POST['coach_id'] ) ) {
                self::restore_coach( intval( $_POST['coach_id'] ) );
                wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'restored' ), admin_url( 'admin.php' ) ) );
                exit;
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

        // Get status filter
        $status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'active';

        // Get search query
        $search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

        // Build query with status filter
        if ( ! empty( $search ) ) {
            $coaches = $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM $table_name
                 WHERE status = %s
                   AND (first_name LIKE %s OR last_name LIKE %s OR email LIKE %s)
                 ORDER BY last_name, first_name",
                $status_filter,
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%'
            ) );
        } else {
            $coaches = $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM $table_name
                 WHERE status = %s
                 ORDER BY last_name, first_name",
                $status_filter
            ) );
        }

        $total_coaches = count( $coaches );

        // Get counts for tabs
        $active_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_coaches WHERE status = 'active'" );
        $deleted_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_coaches WHERE status = 'deleted'" );

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

        // Check if email already exists in another active coach record
        if ( $coach_id > 0 ) {
            $existing = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}igm_coaches
                 WHERE email = %s AND id != %d AND status != 'deleted'",
                $email,
                $coach_id
            ) );
        } else {
            $existing = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}igm_coaches
                 WHERE email = %s AND status != 'deleted'",
                $email
            ) );
        }

        if ( $existing ) {
            wp_die( __( 'Error: A coach with this email already exists.', 'igm-academy-manager' ) );
        }

        // Handle WordPress user creation/update
        if ( $coach_id > 0 ) {
            // Editing existing coach - get current user_id
            $current_coach = $wpdb->get_row( $wpdb->prepare(
                "SELECT user_id FROM {$wpdb->prefix}igm_coaches WHERE id = %d",
                $coach_id
            ) );
            $user_id = $current_coach->user_id;

            // Update WordPress user info
            wp_update_user( array(
                'ID'           => $user_id,
                'user_email'   => $email,
                'display_name' => $first_name . ' ' . $last_name,
                'first_name'   => $first_name,
                'last_name'    => $last_name,
            ) );

        } else {
            // Creating new coach - create WordPress user
            $username = 'coach_' . sanitize_user( $email );
            $password = wp_generate_password( 12, true, true );

            // Check if username exists, add number if needed
            $base_username = $username;
            $counter = 1;
            while ( username_exists( $username ) ) {
                $username = $base_username . '_' . $counter;
                $counter++;
            }

            $user_id = wp_create_user( $username, $password, $email );

            if ( is_wp_error( $user_id ) ) {
                wp_die( __( 'Error creating WordPress user: ', 'igm-academy-manager' ) . $user_id->get_error_message() );
            }

            // Set user role
            $user = new WP_User( $user_id );
            $user->set_role( 'igm_coach' );

            // Set user meta
            wp_update_user( array(
                'ID'           => $user_id,
                'display_name' => $first_name . ' ' . $last_name,
                'first_name'   => $first_name,
                'last_name'    => $last_name,
            ) );

            // Send notification email with login credentials
            wp_new_user_notification( $user_id, null, 'both' );
        }

        // Prepare coach data
        $data = array(
            'user_id'    => $user_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'phone'      => $phone,
            'specialty'  => $specialty,
        );

        $format = array( '%d', '%s', '%s', '%s', '%s', '%s' );

        if ( $coach_id > 0 ) {
            // Update existing coach
            $wpdb->update( $table_name, $data, array( 'id' => $coach_id ), $format, array( '%d' ) );
        } else {
            // Insert new coach
            $wpdb->insert( $table_name, $data, $format );
        }

        // Redirect with success message
        wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'success' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Soft delete coach (mark as deleted, keep data for returning coaches)
     *
     * @since    1.0.0
     * @param    int    $coach_id    Coach ID
     */
    private static function delete_coach( $coach_id ) {
        global $wpdb;

        // Check if coach has assigned groups (Phase 2 feature)
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

        // Get coach data
        $coach = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_coaches WHERE id = %d",
            $coach_id
        ) );

        if ( ! $coach ) {
            wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'error' ), admin_url( 'admin.php' ) ) );
            exit;
        }

        // Soft delete: Update status instead of physical deletion
        self::soft_delete_coach( $coach_id );

        // Disable WordPress user (remove role but keep account)
        if ( $coach->user_id ) {
            self::disable_wordpress_user_coach( $coach->user_id );
        }

        wp_redirect( add_query_arg( array( 'page' => 'igm-coaches', 'message' => 'deleted' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Soft delete coach record (mark as deleted)
     *
     * @since    1.0.0
     * @param    int    $coach_id    Coach ID
     * @return   bool   True on success, false on failure
     */
    private static function soft_delete_coach( $coach_id ) {
        global $wpdb;

        $result = $wpdb->update(
            $wpdb->prefix . 'igm_coaches',
            [
                'status'     => 'deleted',
                'deleted_at' => current_time( 'mysql' )
            ],
            [ 'id' => $coach_id ],
            [ '%s', '%s' ],
            [ '%d' ]
        );

        return $result !== false;
    }

    /**
     * Disable WordPress user for coach (remove role, keep account for potential return)
     *
     * @since    1.0.0
     * @param    int    $user_id    WordPress user ID
     * @return   void
     */
    private static function disable_wordpress_user_coach( $user_id ) {
        $user = new WP_User( $user_id );

        // Remove igm_coach role but keep the user account
        $user->remove_role( 'igm_coach' );

        // Add metadata to mark as suspended
        update_user_meta( $user_id, 'igm_account_suspended', true );
        update_user_meta( $user_id, 'igm_suspended_at', current_time( 'mysql' ) );
    }

    /**
     * Restore deleted coach (reactivate)
     *
     * @since    1.0.0
     * @param    int    $coach_id    Coach ID to restore
     * @return   void
     */
    private static function restore_coach( $coach_id ) {
        global $wpdb;

        // Get coach info
        $coach = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_coaches WHERE id = %d",
            $coach_id
        ) );

        if ( ! $coach ) {
            return;
        }

        // Restore coach status
        $wpdb->update(
            $wpdb->prefix . 'igm_coaches',
            [
                'status'     => 'active',
                'deleted_at' => null
            ],
            [ 'id' => $coach_id ],
            [ '%s', '%s' ],
            [ '%d' ]
        );

        // Restore WordPress user role
        if ( $coach->user_id ) {
            $user = new WP_User( $coach->user_id );
            $user->add_role( 'igm_coach' );

            delete_user_meta( $coach->user_id, 'igm_account_suspended' );
            delete_user_meta( $coach->user_id, 'igm_suspended_at' );
        }
    }
}
