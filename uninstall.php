<?php
/**
 * Fired when the plugin is uninstalled
 *
 * @package    IGM_Academy
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Drop custom tables (optional - uncomment if you want to delete all data on uninstall)
 */
/*
global $wpdb;

$tables = array(
    $wpdb->prefix . 'igm_students',
    $wpdb->prefix . 'igm_coaches',
    $wpdb->prefix . 'igm_groups',
    $wpdb->prefix . 'igm_group_students',
    $wpdb->prefix . 'igm_exercises',
    $wpdb->prefix . 'igm_sessions',
    $wpdb->prefix . 'igm_session_exercises',
    $wpdb->prefix . 'igm_attendance',
    $wpdb->prefix . 'igm_payments',
);

foreach ( $tables as $table ) {
    $wpdb->query( "DROP TABLE IF EXISTS $table" );
}
*/

/**
 * Remove custom roles
 */
remove_role( 'igm_coach' );
remove_role( 'igm_student' );

/**
 * Remove custom capabilities from administrator
 */
$admin_role = get_role( 'administrator' );
if ( $admin_role ) {
    $admin_role->remove_cap( 'manage_igm_students' );
    $admin_role->remove_cap( 'manage_igm_coaches' );
    $admin_role->remove_cap( 'manage_igm_groups' );
    $admin_role->remove_cap( 'manage_igm_sessions' );
    $admin_role->remove_cap( 'manage_igm_attendance' );
    $admin_role->remove_cap( 'manage_igm_payments' );
    $admin_role->remove_cap( 'manage_igm_exercises' );
    $admin_role->remove_cap( 'import_igm_data' );
}

/**
 * Delete plugin options
 */
delete_option( 'igm_academy_version' );
delete_transient( 'igm_academy_cache' );
