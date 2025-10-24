<?php
/**
 * Coach Dashboard - Frontend View
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/public/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get coach information
global $wpdb;
$coach_table = $wpdb->prefix . 'igm_coaches';
$coach = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM $coach_table WHERE id = %d",
    $coach_id
) );

// Get current user info
$current_user = wp_get_current_user();

// Get statistics (will be populated in Phase 2)
$total_groups = 0;  // TODO: Phase 2 - count assigned groups
$total_students = 0; // TODO: Phase 2 - count students in assigned groups
$upcoming_sessions = 0; // TODO: Phase 3 - count upcoming sessions
?>

<div class="igm-coach-dashboard">
    <!-- Welcome Header -->
    <div class="igm-welcome-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-person-badge-fill text-success"></i>
                    <?php
                    printf(
                        __( 'Welcome, %s!', 'igm-academy-manager' ),
                        esc_html( $coach->first_name )
                    );
                    ?>
                </h2>
                <p class="text-muted mb-0">
                    <?php _e( 'Coach Dashboard - IGM Academy', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <span class="badge bg-success">
                    <i class="bi bi-circle-fill"></i>
                    <?php _e( 'Active', 'igm-academy-manager' ); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- My Groups -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-people-fill"></i>
                                <?php _e( 'My Groups', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $total_groups ); ?></h3>
                        </div>
                        <div class="text-success opacity-25">
                            <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php _e( 'Available in Phase 2', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-person"></i>
                                <?php _e( 'Total Students', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $total_students ); ?></h3>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-person" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php _e( 'In assigned groups', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar-check"></i>
                                <?php _e( 'Upcoming Sessions', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $upcoming_sessions ); ?></h3>
                        </div>
                        <div class="text-info opacity-25">
                            <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php _e( 'Available in Phase 3', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Specialty -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-trophy"></i>
                                <?php _e( 'Specialty', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0 text-capitalize">
                                <?php
                                if ( $coach->specialty === 'both' ) {
                                    _e( 'Tennis & Padel', 'igm-academy-manager' );
                                } elseif ( $coach->specialty === 'tennis' ) {
                                    _e( 'Tennis', 'igm-academy-manager' );
                                } else {
                                    _e( 'Padel', 'igm-academy-manager' );
                                }
                                ?>
                            </h3>
                        </div>
                        <div class="text-warning opacity-25">
                            <i class="bi bi-trophy" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phase Information -->
    <div class="row g-4">
        <!-- Account Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle text-success"></i>
                        <?php _e( 'Account Information', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Full Name', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0 fw-bold"><?php echo esc_html( $coach->first_name . ' ' . $coach->last_name ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Email', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $coach->email ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Phone', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $coach->phone ?: '-' ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Username', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $current_user->user_login ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coming Features -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-primary"></i>
                        <?php _e( 'Coming Soon', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 2</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'View and manage assigned groups', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 2</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'View students in your groups', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 2</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'Record attendance', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 3</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'Plan and manage training sessions', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-0">
                            <span class="badge bg-secondary me-2">Phase 4</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'Record student payments', 'igm-academy-manager' ); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info mt-4" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <strong><?php _e( 'Phase 1 Complete!', 'igm-academy-manager' ); ?></strong>
        <?php _e( 'Group management, session planning, and payment features will be available in upcoming phases.', 'igm-academy-manager' ); ?>
    </div>
</div>
