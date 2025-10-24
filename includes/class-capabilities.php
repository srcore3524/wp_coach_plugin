<?php
/**
 * Role and Capability Management
 *
 * Defines custom roles and capabilities for the IGM Academy plugin.
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/includes
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IGM_Academy_Capabilities {

    /**
     * Register custom roles and capabilities
     *
     * Called on plugin activation
     *
     * @since    1.0.0
     */
    public static function add_roles_and_caps() {
        // Add Administrator capabilities
        self::add_admin_capabilities();

        // Add or update Coach role
        self::add_coach_role();

        // Add or update Student role
        self::add_student_role();
    }

    /**
     * Add capabilities to Administrator role
     *
     * Administrators get all IGM Academy capabilities
     *
     * @since    1.0.0
     */
    private static function add_admin_capabilities() {
        $admin = get_role( 'administrator' );

        if ( ! $admin ) {
            return;
        }

        // Student management
        $admin->add_cap( 'manage_igm_students' );
        $admin->add_cap( 'edit_igm_students' );
        $admin->add_cap( 'delete_igm_students' );

        // Coach management
        $admin->add_cap( 'manage_igm_coaches' );
        $admin->add_cap( 'edit_igm_coaches' );
        $admin->add_cap( 'delete_igm_coaches' );

        // Group management (Phase 2)
        $admin->add_cap( 'manage_igm_groups' );
        $admin->add_cap( 'edit_igm_groups' );
        $admin->add_cap( 'delete_igm_groups' );

        // Session management (Phase 3)
        $admin->add_cap( 'manage_igm_sessions' );
        $admin->add_cap( 'edit_igm_sessions' );
        $admin->add_cap( 'delete_igm_sessions' );

        // Exercise management (Phase 3)
        $admin->add_cap( 'manage_igm_exercises' );
        $admin->add_cap( 'edit_igm_exercises' );
        $admin->add_cap( 'delete_igm_exercises' );

        // Payment management (Phase 4)
        $admin->add_cap( 'manage_igm_payments' );
        $admin->add_cap( 'edit_igm_payments' );
        $admin->add_cap( 'delete_igm_payments' );

        // Import/Export
        $admin->add_cap( 'import_igm_data' );
        $admin->add_cap( 'export_igm_data' );

        // Attendance
        $admin->add_cap( 'manage_igm_attendance' );
    }

    /**
     * Add Coach role with capabilities
     *
     * Coaches can manage their assigned groups, sessions, and attendance
     *
     * @since    1.0.0
     */
    private static function add_coach_role() {
        // Remove role if exists to refresh capabilities
        remove_role( 'igm_coach' );

        // Add role with capabilities
        add_role(
            'igm_coach',
            __( 'IGM Coach', 'igm-academy-manager' ),
            array(
                // WordPress basics
                'read'                      => true,

                // Group access (read only assigned groups - filtered in queries)
                'read_igm_groups'           => true,

                // Session management (create/edit own sessions)
                'edit_igm_sessions'         => true,
                'read_igm_sessions'         => true,

                // Attendance (record for own groups)
                'record_igm_attendance'     => true,
                'read_igm_attendance'       => true,

                // Payment (record for own students)
                'record_igm_payments'       => true,
                'read_igm_payments'         => true,

                // Student info (read only for assigned students)
                'read_igm_students'         => true,

                // Exercise library (read only)
                'read_igm_exercises'        => true,
            )
        );
    }

    /**
     * Add Student role with capabilities
     *
     * Students can only view their own data
     *
     * @since    1.0.0
     */
    private static function add_student_role() {
        // Remove role if exists to refresh capabilities
        remove_role( 'igm_student' );

        // Add role with capabilities
        add_role(
            'igm_student',
            __( 'IGM Student', 'igm-academy-manager' ),
            array(
                // WordPress basics
                'read'                      => true,

                // Own schedule (read only)
                'read_igm_schedule'         => true,

                // Own payments (read only)
                'read_igm_payments'         => true,

                // Own attendance (read only)
                'read_igm_attendance'       => true,

                // Own groups (read only)
                'read_igm_groups'           => true,

                // Own sessions (read only)
                'read_igm_sessions'         => true,
            )
        );
    }

    /**
     * Remove custom roles and capabilities
     *
     * Called on plugin deactivation
     *
     * @since    1.0.0
     */
    public static function remove_roles_and_caps() {
        // Remove capabilities from Administrator
        self::remove_admin_capabilities();

        // Remove custom roles
        remove_role( 'igm_coach' );
        remove_role( 'igm_student' );
    }

    /**
     * Remove capabilities from Administrator role
     *
     * @since    1.0.0
     */
    private static function remove_admin_capabilities() {
        $admin = get_role( 'administrator' );

        if ( ! $admin ) {
            return;
        }

        // Student management
        $admin->remove_cap( 'manage_igm_students' );
        $admin->remove_cap( 'edit_igm_students' );
        $admin->remove_cap( 'delete_igm_students' );

        // Coach management
        $admin->remove_cap( 'manage_igm_coaches' );
        $admin->remove_cap( 'edit_igm_coaches' );
        $admin->remove_cap( 'delete_igm_coaches' );

        // Group management
        $admin->remove_cap( 'manage_igm_groups' );
        $admin->remove_cap( 'edit_igm_groups' );
        $admin->remove_cap( 'delete_igm_groups' );

        // Session management
        $admin->remove_cap( 'manage_igm_sessions' );
        $admin->remove_cap( 'edit_igm_sessions' );
        $admin->remove_cap( 'delete_igm_sessions' );

        // Exercise management
        $admin->remove_cap( 'manage_igm_exercises' );
        $admin->remove_cap( 'edit_igm_exercises' );
        $admin->remove_cap( 'delete_igm_exercises' );

        // Payment management
        $admin->remove_cap( 'manage_igm_payments' );
        $admin->remove_cap( 'edit_igm_payments' );
        $admin->remove_cap( 'delete_igm_payments' );

        // Import/Export
        $admin->remove_cap( 'import_igm_data' );
        $admin->remove_cap( 'export_igm_data' );

        // Attendance
        $admin->remove_cap( 'manage_igm_attendance' );
    }

    /**
     * Get coach ID from WordPress user ID (active coaches only)
     *
     * @since    1.0.0
     * @param    int    $user_id    WordPress user ID
     * @return   int|null           Coach ID or null if not found
     */
    public static function get_coach_id_by_user_id( $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_coaches';

        $coach_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $table_name WHERE user_id = %d AND status = 'active'",
            $user_id
        ) );

        return $coach_id ? (int) $coach_id : null;
    }

    /**
     * Get student ID from WordPress user ID (active students only)
     *
     * @since    1.0.0
     * @param    int    $user_id    WordPress user ID
     * @return   int|null           Student ID or null if not found
     */
    public static function get_student_id_by_user_id( $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'igm_students';

        $student_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $table_name WHERE user_id = %d AND status = 'active'",
            $user_id
        ) );

        return $student_id ? (int) $student_id : null;
    }

    /**
     * Check if current user is a coach
     *
     * @since    1.0.0
     * @return   bool
     */
    public static function is_coach() {
        return current_user_can( 'edit_igm_sessions' );
    }

    /**
     * Check if current user is a student
     *
     * @since    1.0.0
     * @return   bool
     */
    public static function is_student() {
        return current_user_can( 'read_igm_schedule' );
    }

    /**
     * Check if current user is an admin
     *
     * @since    1.0.0
     * @return   bool
     */
    public static function is_admin() {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get current user's coach ID
     *
     * @since    1.0.0
     * @return   int|null
     */
    public static function get_current_coach_id() {
        if ( ! is_user_logged_in() ) {
            return null;
        }

        return self::get_coach_id_by_user_id( get_current_user_id() );
    }

    /**
     * Get current user's student ID
     *
     * @since    1.0.0
     * @return   int|null
     */
    public static function get_current_student_id() {
        if ( ! is_user_logged_in() ) {
            return null;
        }

        return self::get_student_id_by_user_id( get_current_user_id() );
    }

    /**
     * Redirect users to their dashboard after login
     *
     * @since    1.0.0
     * @param    string    $redirect_to    The redirect destination URL
     * @param    string    $request        The requested redirect destination URL passed as a parameter
     * @param    WP_User   $user           WP_User object
     * @return   string    Redirect URL
     */
    public static function login_redirect( $redirect_to, $request, $user ) {
        // Check if user login was successful
        if ( ! isset( $user->roles ) || ! is_array( $user->roles ) ) {
            return $redirect_to;
        }

        // Get the page IDs/URLs for dashboards (you'll need to create these pages)
        // For now, we'll use a filter so you can customize these URLs
        $coach_dashboard_url = apply_filters( 'igm_coach_dashboard_url', home_url( '/coach-dashboard/' ) );
        $student_dashboard_url = apply_filters( 'igm_student_dashboard_url', home_url( '/student-dashboard/' ) );

        // Redirect coaches to coach dashboard
        if ( in_array( 'igm_coach', $user->roles ) ) {
            return $coach_dashboard_url;
        }

        // Redirect students to student dashboard
        if ( in_array( 'igm_student', $user->roles ) ) {
            return $student_dashboard_url;
        }

        // For admins and other roles, keep default redirect
        return $redirect_to;
    }

    /**
     * Register login redirect hook
     *
     * @since    1.0.0
     */
    public static function register_login_redirect() {
        add_filter( 'login_redirect', array( __CLASS__, 'login_redirect' ), 10, 3 );
    }
}
