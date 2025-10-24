<?php
/**
 * The public-facing functionality of the plugin
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/public
 */

class IGM_Academy_Public {

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
     * @param    string    $plugin_name    The name of the plugin.
     * @param    string    $version        The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Register shortcodes
        add_shortcode( 'igm_student_dashboard', array( $this, 'student_dashboard_shortcode' ) );
        add_shortcode( 'igm_coach_dashboard', array( $this, 'coach_dashboard_shortcode' ) );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Bootstrap 5 CSS
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

        // Custom public styles
        wp_enqueue_style(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'public/css/public-style.css',
            array( 'bootstrap', 'bootstrap-icons' ),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Bootstrap 5 JS Bundle
        wp_enqueue_script(
            'bootstrap-bundle',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
            array( 'jquery' ),
            '5.3.2',
            true
        );

        // Custom public scripts
        wp_enqueue_script(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'public/js/public-script.js',
            array( 'jquery', 'bootstrap-bundle' ),
            $this->version,
            true
        );
    }

    /**
     * Student dashboard shortcode
     *
     * @since    1.0.0
     * @return   string    Dashboard HTML
     */
    public function student_dashboard_shortcode() {
        // Check if user is logged in
        if ( ! is_user_logged_in() ) {
            return $this->render_login_message();
        }

        // Load capabilities class
        require_once IGM_ACADEMY_PLUGIN_DIR . 'includes/class-capabilities.php';

        // Check if user is a student
        if ( ! IGM_Academy_Capabilities::is_student() ) {
            return $this->render_access_denied();
        }

        // Get student data
        $student_id = IGM_Academy_Capabilities::get_current_student_id();

        if ( ! $student_id ) {
            return '<div class="alert alert-warning">' .
                   __( 'Student profile not found. Please contact the administrator.', 'igm-academy-manager' ) .
                   '</div>';
        }

        ob_start();
        include IGM_ACADEMY_PLUGIN_DIR . 'public/partials/student-dashboard.php';
        return ob_get_clean();
    }

    /**
     * Coach dashboard shortcode
     *
     * @since    1.0.0
     * @return   string    Dashboard HTML
     */
    public function coach_dashboard_shortcode() {
        // Check if user is logged in
        if ( ! is_user_logged_in() ) {
            return $this->render_login_message();
        }

        // Load capabilities class
        require_once IGM_ACADEMY_PLUGIN_DIR . 'includes/class-capabilities.php';

        // Check if user is a coach
        if ( ! IGM_Academy_Capabilities::is_coach() ) {
            return $this->render_access_denied();
        }

        // Get coach data
        $coach_id = IGM_Academy_Capabilities::get_current_coach_id();

        if ( ! $coach_id ) {
            return '<div class="alert alert-warning">' .
                   __( 'Coach profile not found. Please contact the administrator.', 'igm-academy-manager' ) .
                   '</div>';
        }

        ob_start();
        include IGM_ACADEMY_PLUGIN_DIR . 'public/partials/coach-dashboard.php';
        return ob_get_clean();
    }

    /**
     * Render login message
     *
     * @since    1.0.0
     * @return   string
     */
    private function render_login_message() {
        return '<div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> ' .
                    __( 'Please log in to view your dashboard.', 'igm-academy-manager' ) . '
                    <a href="' . wp_login_url( get_permalink() ) . '" class="alert-link">' .
                    __( 'Log in here', 'igm-academy-manager' ) .
                    '</a>
                </div>';
    }

    /**
     * Render access denied message
     *
     * @since    1.0.0
     * @return   string
     */
    private function render_access_denied() {
        return '<div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> ' .
                    __( 'Access denied. You do not have permission to view this page.', 'igm-academy-manager' ) .
                '</div>';
    }
}
