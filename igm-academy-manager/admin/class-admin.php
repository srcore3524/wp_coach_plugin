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
        // Bootstrap 5 CSS from CDN
        wp_enqueue_style(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
            array(),
            '5.3.2',
            'all'
        );

        // Bootstrap Icons
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
            array(),
            '1.11.3',
            'all'
        );

        // DataTables CSS for advanced table features
        wp_enqueue_style(
            'datatables',
            'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css',
            array( 'bootstrap' ),
            '1.13.7',
            'all'
        );

        // Custom admin styles (for IGM-specific overrides)
        wp_enqueue_style(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'admin/css/admin-style.css',
            array( 'bootstrap', 'bootstrap-icons', 'datatables' ),
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
        // Bootstrap 5 JS Bundle (includes Popper.js)
        wp_enqueue_script(
            'bootstrap-bundle',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
            array( 'jquery' ),
            '5.3.2',
            true
        );

        // DataTables JS for advanced table features
        wp_enqueue_script(
            'datatables',
            'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js',
            array( 'jquery' ),
            '1.13.7',
            true
        );

        wp_enqueue_script(
            'datatables-bootstrap',
            'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js',
            array( 'jquery', 'datatables', 'bootstrap-bundle' ),
            '1.13.7',
            true
        );

        // Custom admin scripts
        wp_enqueue_script(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'admin/js/admin-script.js',
            array( 'jquery', 'bootstrap-bundle', 'datatables', 'datatables-bootstrap' ),
            $this->version,
            true
        );

        // Localize script for AJAX
        wp_localize_script(
            $this->plugin_name,
            'igmAcademy',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'igm_academy_nonce' ),
            )
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
