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
        wp_enqueue_style(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'public/css/public-style.css',
            array(),
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
        wp_enqueue_script(
            $this->plugin_name,
            IGM_ACADEMY_PLUGIN_URL . 'public/js/public-script.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }

    /**
     * Student dashboard shortcode
     *
     * @since    1.0.0
     * @return   string    Dashboard HTML
     */
    public function student_dashboard_shortcode() {
        if ( ! is_user_logged_in() ) {
            return '<p>' . __( 'Please log in to view your dashboard.', 'igm-academy-manager' ) . '</p>';
        }

        ob_start();
        require IGM_ACADEMY_PLUGIN_DIR . 'public/partials/dashboard-student.php';
        return ob_get_clean();
    }

    /**
     * Coach dashboard shortcode
     *
     * @since    1.0.0
     * @return   string    Dashboard HTML
     */
    public function coach_dashboard_shortcode() {
        if ( ! is_user_logged_in() ) {
            return '<p>' . __( 'Please log in to view your dashboard.', 'igm-academy-manager' ) . '</p>';
        }

        ob_start();
        require IGM_ACADEMY_PLUGIN_DIR . 'public/partials/dashboard-coach.php';
        return ob_get_clean();
    }
}
