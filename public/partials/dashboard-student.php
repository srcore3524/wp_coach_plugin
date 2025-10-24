<?php
/**
 * Student dashboard view
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/public/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_user = wp_get_current_user();
?>

<div class="igm-student-dashboard">
    <h2><?php printf( __( 'Welcome, %s', 'igm-academy-manager' ), esc_html( $current_user->display_name ) ); ?></h2>

    <div class="igm-dashboard-section">
        <h3><?php _e( 'My Classes', 'igm-academy-manager' ); ?></h3>
        <p><?php _e( 'This feature will be available in Phase 2-3.', 'igm-academy-manager' ); ?></p>
    </div>

    <div class="igm-dashboard-section">
        <h3><?php _e( 'My Payments', 'igm-academy-manager' ); ?></h3>
        <p><?php _e( 'This feature will be available in Phase 4.', 'igm-academy-manager' ); ?></p>
    </div>

    <div class="igm-dashboard-section">
        <h3><?php _e( 'My Attendance', 'igm-academy-manager' ); ?></h3>
        <p><?php _e( 'This feature will be available in Phase 2.', 'igm-academy-manager' ); ?></p>
    </div>
</div>
