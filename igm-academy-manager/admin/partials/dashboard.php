<?php
/**
 * Admin dashboard
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 */

// Get statistics
global $wpdb;
$total_students = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_students" );
$total_coaches = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_coaches" );
$total_groups = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_groups" );
$total_payments = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_payments" );
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div class="igm-dashboard">
        <div class="igm-stats-grid">
            <div class="igm-stat-card">
                <div class="igm-stat-icon dashicons dashicons-groups"></div>
                <div class="igm-stat-content">
                    <h3><?php echo esc_html( $total_students ); ?></h3>
                    <p><?php _e( 'Total Students', 'igm-academy-manager' ); ?></p>
                </div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>" class="igm-stat-link">
                    <?php _e( 'View Students', 'igm-academy-manager' ); ?> &rarr;
                </a>
            </div>

            <div class="igm-stat-card">
                <div class="igm-stat-icon dashicons dashicons-businessperson"></div>
                <div class="igm-stat-content">
                    <h3><?php echo esc_html( $total_coaches ); ?></h3>
                    <p><?php _e( 'Total Coaches', 'igm-academy-manager' ); ?></p>
                </div>
            </div>

            <div class="igm-stat-card">
                <div class="igm-stat-icon dashicons dashicons-welcome-learn-more"></div>
                <div class="igm-stat-content">
                    <h3><?php echo esc_html( $total_groups ); ?></h3>
                    <p><?php _e( 'Active Groups', 'igm-academy-manager' ); ?></p>
                </div>
            </div>

            <div class="igm-stat-card">
                <div class="igm-stat-icon dashicons dashicons-money-alt"></div>
                <div class="igm-stat-content">
                    <h3><?php echo esc_html( $total_payments ); ?></h3>
                    <p><?php _e( 'Total Payments', 'igm-academy-manager' ); ?></p>
                </div>
            </div>
        </div>

        <div class="igm-quick-links">
            <h2><?php _e( 'Quick Actions', 'igm-academy-manager' ); ?></h2>
            <div class="igm-links-grid">
                <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>" class="button button-primary">
                    <?php _e( 'Add New Student', 'igm-academy-manager' ); ?>
                </a>
                <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>" class="button button-primary">
                    <?php _e( 'Add New Coach', 'igm-academy-manager' ); ?>
                </a>
                <a href="<?php echo admin_url( 'admin.php?page=igm-import' ); ?>" class="button button-secondary">
                    <?php _e( 'Import Data', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>

        <div class="igm-info-box">
            <h3><?php _e( 'Welcome to IGM Academy Manager', 'igm-academy-manager' ); ?></h3>
            <p><?php _e( 'This plugin helps you manage your tennis and padel academy efficiently.', 'igm-academy-manager' ); ?></p>
            <ul>
                <li><?php _e( 'Manage students and coaches', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'Organize groups and sessions', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'Track attendance and payments', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'Plan training sessions with exercises', 'igm-academy-manager' ); ?></li>
            </ul>
        </div>
    </div>
</div>
