<?php
/**
 * Admin Dashboard - Modern Bootstrap 5 Design
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 * @version    1.0.0
 */

// Get statistics (active records only)
global $wpdb;
$total_students = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_students WHERE status = 'active'" );
$total_coaches = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_coaches WHERE status = 'active'" );
$total_groups = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_groups" );
$total_payments = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}igm_payments" );

// Get recent students (last 5 active)
$recent_students = $wpdb->get_results(
    "SELECT first_name, last_name, email, created_at
    FROM {$wpdb->prefix}igm_students
    WHERE status = 'active'
    ORDER BY created_at DESC
    LIMIT 5"
);
?>

<div class="wrap">
    <!-- Modern Page Header -->
    <div class="igm-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-speedometer2"></i>
                    <?php _e( 'Dashboard', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php _e( 'Welcome to IGM Academy Manager', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus"></i>
                    <?php _e( 'Add Student', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Students Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stats-card stats-students h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label mb-2">
                                <i class="bi bi-people-fill"></i>
                                <?php _e( 'Total Students', 'igm-academy-manager' ); ?>
                            </p>
                            <h2 class="stats-number mb-0"><?php echo esc_html( $total_students ); ?></h2>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>"
                           class="text-decoration-none small">
                            <?php _e( 'View All', 'igm-academy-manager' ); ?>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coaches Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stats-card stats-coaches h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label mb-2">
                                <i class="bi bi-person-badge"></i>
                                <?php _e( 'Total Coaches', 'igm-academy-manager' ); ?>
                            </p>
                            <h2 class="stats-number mb-0"><?php echo esc_html( $total_coaches ); ?></h2>
                        </div>
                        <div class="text-success opacity-25">
                            <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo admin_url( 'admin.php?page=igm-coaches' ); ?>"
                           class="text-decoration-none small">
                            <?php _e( 'Manage Coaches', 'igm-academy-manager' ); ?>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stats-card stats-groups h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label mb-2">
                                <i class="bi bi-collection"></i>
                                <?php _e( 'Active Groups', 'igm-academy-manager' ); ?>
                            </p>
                            <h2 class="stats-number mb-0"><?php echo esc_html( $total_groups ); ?></h2>
                        </div>
                        <div class="text-warning opacity-25">
                            <i class="bi bi-collection-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="small text-muted">
                            <i class="bi bi-info-circle"></i>
                            <?php _e( 'Coming in Phase 2', 'igm-academy-manager' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stats-card stats-payments h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label mb-2">
                                <i class="bi bi-credit-card"></i>
                                <?php _e( 'Total Payments', 'igm-academy-manager' ); ?>
                            </p>
                            <h2 class="stats-number mb-0"><?php echo esc_html( $total_payments ); ?></h2>
                        </div>
                        <div class="text-info opacity-25">
                            <i class="bi bi-credit-card-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="small text-muted">
                            <i class="bi bi-info-circle"></i>
                            <?php _e( 'Coming in Phase 4', 'igm-academy-manager' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge-fill text-primary"></i>
                        <?php _e( 'Quick Actions', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="d-grid">
                                <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>"
                                   class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <?php _e( 'Add New Student', 'igm-academy-manager' ); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-grid">
                                <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>"
                                   class="btn btn-success btn-lg">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <?php _e( 'Add New Coach', 'igm-academy-manager' ); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-grid">
                                <a href="<?php echo admin_url( 'admin.php?page=igm-import' ); ?>"
                                   class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-upload"></i>
                                    <?php _e( 'Import Data', 'igm-academy-manager' ); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-grid">
                                <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>"
                                   class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-list-ul"></i>
                                    <?php _e( 'View All Students', 'igm-academy-manager' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Feature List -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-muted mb-3"><?php _e( 'What you can do:', 'igm-academy-manager' ); ?></h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <?php _e( 'Manage students and coaches with WordPress integration', 'igm-academy-manager' ); ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <?php _e( 'Import existing student data from CSV files', 'igm-academy-manager' ); ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-fill text-warning"></i>
                                <?php _e( 'Organize groups and sessions (Coming in Phase 2)', 'igm-academy-manager' ); ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-fill text-warning"></i>
                                <?php _e( 'Track attendance and payments (Coming in Phases 2-4)', 'igm-academy-manager' ); ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-fill text-warning"></i>
                                <?php _e( 'Plan training sessions with exercises (Coming in Phase 3)', 'igm-academy-manager' ); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Info -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-primary"></i>
                        <?php _e( 'Recent Students', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ( ! empty( $recent_students ) ) : ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ( $recent_students as $student ) : ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                             style="width: 40px; height: 40px; font-size: 18px;">
                                            <?php echo strtoupper( substr( $student->first_name, 0, 1 ) ); ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                <?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?>
                                            </h6>
                                            <small class="text-muted">
                                                <?php echo esc_html( $student->email ); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-calendar-plus"></i>
                                        <?php echo human_time_diff( strtotime( $student->created_at ), current_time( 'timestamp' ) ); ?> ago
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">
                                <?php _e( 'No students yet', 'igm-academy-manager' ); ?>
                            </p>
                            <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>"
                               class="btn btn-sm btn-primary mt-3">
                                <?php _e( 'Add Your First Student', 'igm-academy-manager' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- System Info (Optional) -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="alert alert-info border-0 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <strong><?php _e( 'Phase 1 Complete!', 'igm-academy-manager' ); ?></strong>
                    <?php _e( 'Student and coach management is ready. Groups, sessions, and payments coming in next phases.', 'igm-academy-manager' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional dashboard-specific styles */
.avatar {
    flex-shrink: 0;
}

.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-5px);
}
</style>
