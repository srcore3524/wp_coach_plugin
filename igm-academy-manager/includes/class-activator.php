<?php
/**
 * Fired during plugin activation
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/includes
 */

class IGM_Academy_Activator {

    /**
     * Plugin activation handler
     *
     * Creates database tables, sets up user roles, and initializes default settings.
     *
     * @since    1.0.0
     */
    public static function activate() {
        self::create_tables();
        self::create_roles();

        // Set plugin version
        update_option( 'igm_academy_version', IGM_ACADEMY_VERSION );

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create custom database tables
     *
     * @since    1.0.0
     */
    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // Students table
        $table_students = $wpdb->prefix . 'igm_students';
        $sql_students = "CREATE TABLE $table_students (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) DEFAULT NULL,
            gender enum('male','female','other') DEFAULT NULL,
            birth_date date DEFAULT NULL,
            notes text DEFAULT NULL,
            total_classes int(11) DEFAULT 0,
            pending_classes int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY user_id (user_id),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        dbDelta( $sql_students );

        // Coaches table
        $table_coaches = $wpdb->prefix . 'igm_coaches';
        $sql_coaches = "CREATE TABLE $table_coaches (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) DEFAULT NULL,
            specialty enum('tennis','padel','both') DEFAULT 'both',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY user_id (user_id),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        dbDelta( $sql_coaches );

        // Groups table
        $table_groups = $wpdb->prefix . 'igm_groups';
        $sql_groups = "CREATE TABLE $table_groups (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            coach_id bigint(20) DEFAULT NULL,
            sport_type enum('tennis','padel') NOT NULL,
            level varchar(50) DEFAULT NULL,
            max_students int(11) DEFAULT 10,
            schedule text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY coach_id (coach_id)
        ) $charset_collate;";
        dbDelta( $sql_groups );

        // Group Students (many-to-many)
        $table_group_students = $wpdb->prefix . 'igm_group_students';
        $sql_group_students = "CREATE TABLE $table_group_students (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            group_id bigint(20) NOT NULL,
            student_id bigint(20) NOT NULL,
            joined_date date DEFAULT NULL,
            status enum('active','inactive','withdrawn') DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY group_id (group_id),
            KEY student_id (student_id)
        ) $charset_collate;";
        dbDelta( $sql_group_students );

        // Exercises table
        $table_exercises = $wpdb->prefix . 'igm_exercises';
        $sql_exercises = "CREATE TABLE $table_exercises (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(200) NOT NULL,
            sport_type enum('tennis','padel') NOT NULL,
            category varchar(100) DEFAULT NULL,
            description text DEFAULT NULL,
            duration int(11) DEFAULT NULL,
            difficulty enum('beginner','intermediate','advanced') DEFAULT 'intermediate',
            technical_objective text DEFAULT NULL,
            tactical_objective text DEFAULT NULL,
            physical_objective text DEFAULT NULL,
            mental_objective text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY sport_type (sport_type),
            KEY difficulty (difficulty)
        ) $charset_collate;";
        dbDelta( $sql_exercises );

        // Sessions table
        $table_sessions = $wpdb->prefix . 'igm_sessions';
        $sql_sessions = "CREATE TABLE $table_sessions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            group_id bigint(20) DEFAULT NULL,
            coach_id bigint(20) NOT NULL,
            session_date datetime NOT NULL,
            duration int(11) DEFAULT 60,
            location varchar(100) DEFAULT NULL,
            notes text DEFAULT NULL,
            status enum('planned','completed','cancelled') DEFAULT 'planned',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY group_id (group_id),
            KEY coach_id (coach_id),
            KEY session_date (session_date)
        ) $charset_collate;";
        dbDelta( $sql_sessions );

        // Session Exercises (many-to-many)
        $table_session_exercises = $wpdb->prefix . 'igm_session_exercises';
        $sql_session_exercises = "CREATE TABLE $table_session_exercises (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            session_id bigint(20) NOT NULL,
            exercise_id bigint(20) NOT NULL,
            duration int(11) DEFAULT NULL,
            order_number int(11) DEFAULT 0,
            notes text DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY session_id (session_id),
            KEY exercise_id (exercise_id)
        ) $charset_collate;";
        dbDelta( $sql_session_exercises );

        // Attendance table
        $table_attendance = $wpdb->prefix . 'igm_attendance';
        $sql_attendance = "CREATE TABLE $table_attendance (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            session_id bigint(20) NOT NULL,
            student_id bigint(20) NOT NULL,
            status enum('present','absent','recovery') DEFAULT 'present',
            notes text DEFAULT NULL,
            recorded_by bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY session_id (session_id),
            KEY student_id (student_id)
        ) $charset_collate;";
        dbDelta( $sql_attendance );

        // Payments table
        $table_payments = $wpdb->prefix . 'igm_payments';
        $sql_payments = "CREATE TABLE $table_payments (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            student_id bigint(20) NOT NULL,
            amount decimal(10,2) NOT NULL,
            payment_date datetime NOT NULL,
            payment_method enum('cash','transfer','card') DEFAULT 'cash',
            period_start date DEFAULT NULL,
            period_end date DEFAULT NULL,
            notes text DEFAULT NULL,
            recorded_by bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY student_id (student_id),
            KEY payment_date (payment_date)
        ) $charset_collate;";
        dbDelta( $sql_payments );
    }

    /**
     * Create custom user roles
     *
     * @since    1.0.0
     */
    private static function create_roles() {
        // Coach role
        add_role(
            'igm_coach',
            __( 'Coach', 'igm-academy-manager' ),
            array(
                'read'                   => true,
                'edit_posts'             => false,
                'delete_posts'           => false,
                'manage_igm_groups'      => true,
                'manage_igm_sessions'    => true,
                'manage_igm_attendance'  => true,
                'view_igm_students'      => true,
                'manage_igm_payments'    => true,
            )
        );

        // Student role
        add_role(
            'igm_student',
            __( 'Student', 'igm-academy-manager' ),
            array(
                'read'                   => true,
                'edit_posts'             => false,
                'delete_posts'           => false,
                'view_own_schedule'      => true,
                'view_own_payments'      => true,
                'view_own_attendance'    => true,
            )
        );

        // Add capabilities to administrator
        $admin_role = get_role( 'administrator' );
        if ( $admin_role ) {
            $admin_role->add_cap( 'manage_igm_students' );
            $admin_role->add_cap( 'manage_igm_coaches' );
            $admin_role->add_cap( 'manage_igm_groups' );
            $admin_role->add_cap( 'manage_igm_sessions' );
            $admin_role->add_cap( 'manage_igm_attendance' );
            $admin_role->add_cap( 'manage_igm_payments' );
            $admin_role->add_cap( 'manage_igm_exercises' );
            $admin_role->add_cap( 'import_igm_data' );
        }
    }
}
