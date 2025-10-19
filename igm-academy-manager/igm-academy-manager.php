<?php
/**
 * Plugin Name: IGM Academy Manager
 * Plugin URI: https://igmacademy.es
 * Description: Complete management system for IGM Tennis and Padel Academy - students, groups, sessions, and payments
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://igmacademy.es
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: igm-academy-manager
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'IGM_ACADEMY_VERSION', '1.0.0' );
define( 'IGM_ACADEMY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IGM_ACADEMY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IGM_ACADEMY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

function activate_igm_academy() {
    require_once IGM_ACADEMY_PLUGIN_DIR . 'includes/class-activator.php';
    IGM_Academy_Activator::activate();
}

function deactivate_igm_academy() {
    require_once IGM_ACADEMY_PLUGIN_DIR . 'includes/class-deactivator.php';
    IGM_Academy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_igm_academy' );
register_deactivation_hook( __FILE__, 'deactivate_igm_academy' );

require IGM_ACADEMY_PLUGIN_DIR . 'includes/class-igm-academy.php';

function run_igm_academy() {
    $plugin = new IGM_Academy();
    $plugin->run();
}
run_igm_academy();
