<?php
/**
 * The admin-specific functionality of the plugin
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin
 */

class IGM_Academy_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name    The name of this plugin.
     * @param    string    $version        The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Load admin classes
        $this->load_admin_classes();
    }

    /**
     * Load admin classes
     *
     * @since    1.0.0
     */
    private function load_admin_classes() {
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/class-students.php';
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/class-coaches.php';
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/class-importer.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'admin/css/admin-style.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'admin/js/admin-script.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }

    /**
     * Register the admin menu.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __( 'IGM Academy', 'igm-academy-manager' ),
            __( 'IGM Academy', 'igm-academy-manager' ),
            'manage_options',
            'igm-academy',
            array( $this, 'display_dashboard' ),
            'dashicons-groups',
            30
        );

        // Dashboard submenu
        add_submenu_page(
            'igm-academy',
            __( 'Dashboard', 'igm-academy-manager' ),
            __( 'Dashboard', 'igm-academy-manager' ),
            'manage_options',
            'igm-academy',
            array( $this, 'display_dashboard' )
        );

        // Students submenu
        add_submenu_page(
            'igm-academy',
            __( 'Students', 'igm-academy-manager' ),
            __( 'Students', 'igm-academy-manager' ),
            'manage_igm_students',
            'igm-students',
            array( 'IGM_Academy_Students', 'display_students_page' )
        );

        // Coaches submenu
        add_submenu_page(
            'igm-academy',
            __( 'Coaches', 'igm-academy-manager' ),
            __( 'Coaches', 'igm-academy-manager' ),
            'manage_igm_coaches',
            'igm-coaches',
            array( 'IGM_Academy_Coaches', 'display_coaches_page' )
        );

        // Import submenu
        add_submenu_page(
            'igm-academy',
            __( 'Import Data', 'igm-academy-manager' ),
            __( 'Import Data', 'igm-academy-manager' ),
            'import_igm_data',
            'igm-import',
            array( 'IGM_Academy_Importer', 'display_import_page' )
        );
    }

    /**
     * Display the dashboard page.
     *
     * @since    1.0.0
     */
    public function display_dashboard() {
        require_once IGM_ACADEMY_PLUGIN_DIR . 'admin/partials/dashboard.php';
    }
}
