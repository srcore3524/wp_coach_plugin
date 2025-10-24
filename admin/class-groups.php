<?php
/**
 * Group Management Class
 *
 * Handles all group-related operations including CRUD operations,
 * student assignments, and coach assignments.
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin
 * @version    1.0.0
 */

class IGM_Academy_Groups {

    /**
     * Display the groups management page
     *
     * @since    1.0.0
     * @return   void
     */
    public static function display_groups_page() {
        // Handle POST requests (form submissions)
        if ( self::handle_post_request() ) {
            return; // Already redirected
        }

        // Handle GET requests (display views)
        self::handle_get_request();
    }

    /**
     * Handle POST request for group operations
     *
     * @since    1.0.0
     * @return   bool    True if request was handled and redirected
     */
    private static function handle_post_request() {
        if ( ! isset( $_POST['igm_action'] ) || ! isset( $_POST['igm_group_nonce'] ) ) {
            return false;
        }

        if ( ! check_admin_referer( 'igm_group_action', 'igm_group_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'igm-academy-manager' ) );
        }

        $action = sanitize_text_field( $_POST['igm_action'] );

        switch ( $action ) {
            case 'add':
            case 'edit':
                self::save_group();
                return true;

            case 'delete':
                if ( isset( $_POST['group_id'] ) ) {
                    self::delete_group( intval( $_POST['group_id'] ) );
                    return true;
                }
                break;

            case 'add_students':
                if ( isset( $_POST['group_id'] ) ) {
                    self::add_students_to_group();
                    return true;
                }
                break;

            case 'remove_student':
                if ( isset( $_POST['group_id'] ) && isset( $_POST['student_id'] ) ) {
                    self::remove_student_from_group( intval( $_POST['group_id'] ), intval( $_POST['student_id'] ) );
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
        $group_id = isset( $_GET['group_id'] ) ? intval( $_GET['group_id'] ) : 0;

        switch ( $action ) {
            case 'edit':
                if ( $group_id > 0 ) {
                    self::display_edit_form( $group_id );
                } else {
                    self::redirect_with_error( 'invalid_id' );
                }
                break;

            case 'add':
                self::display_add_form();
                break;

            case 'manage_students':
                if ( $group_id > 0 ) {
                    self::display_student_management( $group_id );
                } else {
                    self::redirect_with_error( 'invalid_id' );
                }
                break;

            default:
                self::display_groups_list();
                break;
        }
    }

    /**
     * Display the groups list view
     *
     * @since    1.0.0
     * @return   void
     */
    private static function display_groups_list() {
        global $wpdb;

        $groups = self::get_groups_with_search();
        $total_groups = count( $groups );

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/groups-list.php';
    }

    /**
     * Get groups with optional search filtering
     *
     * @since    1.0.0
     * @return   array    Array of group objects
     */
    private static function get_groups_with_search() {
        global $wpdb;

        $search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
        $table = $wpdb->prefix . 'igm_groups';

        if ( ! empty( $search ) ) {
            $search_term = '%' . $wpdb->esc_like( $search ) . '%';

            return $wpdb->get_results( $wpdb->prepare(
                "SELECT g.*, c.first_name as coach_first_name, c.last_name as coach_last_name,
                        (SELECT COUNT(*) FROM {$wpdb->prefix}igm_group_students gs WHERE gs.group_id = g.id AND gs.status = 'active') as student_count
                 FROM {$table} g
                 LEFT JOIN {$wpdb->prefix}igm_coaches c ON g.coach_id = c.id
                 WHERE g.name LIKE %s
                 ORDER BY g.name",
                $search_term
            ) );
        }

        return $wpdb->get_results(
            "SELECT g.*, c.first_name as coach_first_name, c.last_name as coach_last_name,
                    (SELECT COUNT(*) FROM {$wpdb->prefix}igm_group_students gs WHERE gs.group_id = g.id AND gs.status = 'active') as student_count
             FROM {$table} g
             LEFT JOIN {$wpdb->prefix}igm_coaches c ON g.coach_id = c.id
             ORDER BY g.name"
        );
    }

    /**
     * Display the add group form
     *
     * @since    1.0.0
     * @return   void
     */
    private static function display_add_form() {
        $group = null; // No group data for add form
        $coaches = self::get_active_coaches();
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/group-form.php';
    }

    /**
     * Display the edit group form
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID to edit
     * @return   void
     */
    private static function display_edit_form( $group_id ) {
        $group = self::get_group_by_id( $group_id );

        if ( ! $group ) {
            wp_die( __( 'Group not found.', 'igm-academy-manager' ) );
        }

        $coaches = self::get_active_coaches();
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/group-form.php';
    }

    /**
     * Display student management for a group
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID
     * @return   void
     */
    private static function display_student_management( $group_id ) {
        $group = self::get_group_by_id( $group_id );

        if ( ! $group ) {
            wp_die( __( 'Group not found.', 'igm-academy-manager' ) );
        }

        $group_students = self::get_group_students( $group_id );
        $available_students = self::get_available_students( $group_id );

        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/group-students.php';
    }

    /**
     * Get group by ID
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID
     * @return   object|null    Group object or null if not found
     */
    private static function get_group_by_id( $group_id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}igm_groups WHERE id = %d",
            $group_id
        ) );
    }

    /**
     * Get active coaches for dropdown
     *
     * @since    1.0.0
     * @return   array    Array of coach objects
     */
    private static function get_active_coaches() {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT id, first_name, last_name, specialty
             FROM {$wpdb->prefix}igm_coaches
             WHERE status = 'active'
             ORDER BY last_name, first_name"
        );
    }

    /**
     * Get students in a group
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID
     * @return   array    Array of student objects
     */
    private static function get_group_students( $group_id ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT s.*, gs.joined_date, gs.status as membership_status
             FROM {$wpdb->prefix}igm_students s
             INNER JOIN {$wpdb->prefix}igm_group_students gs ON s.id = gs.student_id
             WHERE gs.group_id = %d AND gs.status = 'active'
             ORDER BY s.last_name, s.first_name",
            $group_id
        ) );
    }

    /**
     * Get students not in a group (available for assignment)
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID
     * @return   array    Array of student objects
     */
    private static function get_available_students( $group_id ) {
        global $wpdb;

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT s.*
             FROM {$wpdb->prefix}igm_students s
             WHERE s.status = 'active'
               AND s.id NOT IN (
                   SELECT gs.student_id
                   FROM {$wpdb->prefix}igm_group_students gs
                   WHERE gs.group_id = %d AND gs.status = 'active'
               )
             ORDER BY s.last_name, s.first_name",
            $group_id
        ) );
    }

    /**
     * Save group (create or update)
     *
     * @since    1.0.0
     * @return   void
     */
    private static function save_group() {
        $group_id = isset( $_POST['group_id'] ) ? intval( $_POST['group_id'] ) : 0;

        // Validate and sanitize input
        $group_data = [
            'name'        => sanitize_text_field( $_POST['name'] ?? '' ),
            'coach_id'    => ! empty( $_POST['coach_id'] ) ? intval( $_POST['coach_id'] ) : null,
            'sport_type'  => sanitize_text_field( $_POST['sport_type'] ?? 'tennis' ),
            'level'       => sanitize_text_field( $_POST['level'] ?? '' ),
            'max_students' => intval( $_POST['max_students'] ?? 10 ),
            'schedule'    => sanitize_textarea_field( $_POST['schedule'] ?? '' ),
        ];

        // Save group record
        $result = self::save_group_record( $group_id, $group_data );

        if ( $result ) {
            self::redirect_with_success( $group_id > 0 ? 'updated' : 'created' );
        } else {
            wp_die( __( 'Error saving group record.', 'igm-academy-manager' ) );
        }
    }

    /**
     * Save group record to database
     *
     * @since    1.0.0
     * @param    int      $group_id      Group ID (0 for new)
     * @param    array    $group_data    Group data
     * @return   bool     True on success, false on failure
     */
    private static function save_group_record( $group_id, $group_data ) {
        global $wpdb;

        $format = [ '%s', '%d', '%s', '%s', '%d', '%s' ];

        if ( $group_id > 0 ) {
            return $wpdb->update(
                "{$wpdb->prefix}igm_groups",
                $group_data,
                [ 'id' => $group_id ],
                $format,
                [ '%d' ]
            );
        } else {
            return $wpdb->insert(
                "{$wpdb->prefix}igm_groups",
                $group_data,
                $format
            );
        }
    }

    /**
     * Delete group
     *
     * @since    1.0.0
     * @param    int    $group_id    Group ID
     * @return   void
     */
    private static function delete_group( $group_id ) {
        global $wpdb;

        // Check if group has students
        $has_students = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}igm_group_students WHERE group_id = %d AND status = 'active'",
            $group_id
        ) );

        if ( $has_students > 0 ) {
            self::redirect_with_error( 'has_students' );
            return;
        }

        // Delete group record
        $wpdb->delete(
            "{$wpdb->prefix}igm_groups",
            [ 'id' => $group_id ],
            [ '%d' ]
        );

        self::redirect_with_success( 'deleted' );
    }

    /**
     * Add students to group
     *
     * @since    1.0.0
     * @return   void
     */
    private static function add_students_to_group() {
        global $wpdb;

        $group_id = intval( $_POST['group_id'] );
        $student_ids = isset( $_POST['student_ids'] ) ? array_map( 'intval', $_POST['student_ids'] ) : [];

        if ( empty( $student_ids ) ) {
            wp_redirect( add_query_arg(
                [
                    'page'     => 'igm-groups',
                    'action'   => 'manage_students',
                    'group_id' => $group_id,
                    'error'    => 'no_students_selected'
                ],
                admin_url( 'admin.php' )
            ) );
            exit;
        }

        // Add each student to the group
        foreach ( $student_ids as $student_id ) {
            $wpdb->insert(
                "{$wpdb->prefix}igm_group_students",
                [
                    'group_id'    => $group_id,
                    'student_id'  => $student_id,
                    'joined_date' => current_time( 'Y-m-d' ),
                    'status'      => 'active'
                ],
                [ '%d', '%d', '%s', '%s' ]
            );
        }

        wp_redirect( add_query_arg(
            [
                'page'     => 'igm-groups',
                'action'   => 'manage_students',
                'group_id' => $group_id,
                'message'  => 'students_added'
            ],
            admin_url( 'admin.php' )
        ) );
        exit;
    }

    /**
     * Remove student from group
     *
     * @since    1.0.0
     * @param    int    $group_id     Group ID
     * @param    int    $student_id   Student ID
     * @return   void
     */
    private static function remove_student_from_group( $group_id, $student_id ) {
        global $wpdb;

        // Set status to withdrawn instead of deleting
        $wpdb->update(
            "{$wpdb->prefix}igm_group_students",
            [ 'status' => 'withdrawn' ],
            [
                'group_id'   => $group_id,
                'student_id' => $student_id
            ],
            [ '%s' ],
            [ '%d', '%d' ]
        );

        wp_redirect( add_query_arg(
            [
                'page'     => 'igm-groups',
                'action'   => 'manage_students',
                'group_id' => $group_id,
                'message'  => 'student_removed'
            ],
            admin_url( 'admin.php' )
        ) );
        exit;
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
            [
                'page'  => 'igm-groups',
                'error' => $error_code
            ],
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
            [
                'page'    => 'igm-groups',
                'message' => $message
            ],
            admin_url( 'admin.php' )
        ) );
        exit;
    }
}
