<?php
/**
 * Student Dashboard - Frontend View
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/public/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get student information
global $wpdb;
$student_table = $wpdb->prefix . 'igm_students';
$student = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM $student_table WHERE id = %d",
    $student_id
) );

// Get current user info
$current_user = wp_get_current_user();

// Get statistics (will be populated in later phases)
$enrolled_groups = 0;  // TODO: Phase 2 - count enrolled groups
$upcoming_classes = 0; // TODO: Phase 3 - count upcoming sessions
$total_payments = 0;   // TODO: Phase 4 - sum of payments
?>

<div class="igm-student-dashboard">
    <!-- Welcome Header -->
    <div class="igm-welcome-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-person-fill text-primary"></i>
                    <?php
                    printf(
                        __( 'Welcome, %s!', 'igm-academy-manager' ),
                        esc_html( $student->first_name )
                    );
                    ?>
                </h2>
                <p class="text-muted mb-0">
                    <?php _e( 'Student Dashboard - IGM Academy', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <span class="badge bg-primary">
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
                                <i class="bi bi-people"></i>
                                <?php _e( 'My Groups', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $enrolled_groups ); ?></h3>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-people" style="font-size: 3rem;"></i>
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

        <!-- Total Classes -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar-check"></i>
                                <?php _e( 'Total Classes', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $student->total_classes ); ?></h3>
                        </div>
                        <div class="text-success opacity-25">
                            <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php _e( 'Classes purchased', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Classes -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-hourglass-split"></i>
                                <?php _e( 'Pending Classes', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $student->pending_classes ); ?></h3>
                        </div>
                        <div class="text-warning opacity-25">
                            <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <?php _e( 'Classes remaining', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-clock"></i>
                                <?php _e( 'Upcoming Classes', 'igm-academy-manager' ); ?>
                            </p>
                            <h3 class="mb-0"><?php echo esc_html( $upcoming_classes ); ?></h3>
                        </div>
                        <div class="text-info opacity-25">
                            <i class="bi bi-clock" style="font-size: 3rem;"></i>
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
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Account Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle text-primary"></i>
                        <?php _e( 'Account Information', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Full Name', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0 fw-bold"><?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Email', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $student->email ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Phone', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $student->phone ?: '-' ); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Birth Date', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0">
                                <?php
                                if ( $student->birth_date ) {
                                    echo esc_html( date_i18n( 'd/m/Y', strtotime( $student->birth_date ) ) );
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Gender', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0 text-capitalize">
                                <?php
                                if ( $student->gender === 'male' ) {
                                    _e( 'Male', 'igm-academy-manager' );
                                } elseif ( $student->gender === 'female' ) {
                                    _e( 'Female', 'igm-academy-manager' );
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small"><?php _e( 'Username', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0"><?php echo esc_html( $current_user->user_login ); ?></p>
                        </div>
                    </div>

                    <?php if ( $student->notes ) : ?>
                        <div class="mt-3">
                            <label class="form-label text-muted small"><?php _e( 'Notes', 'igm-academy-manager' ); ?></label>
                            <p class="mb-0 text-muted small"><?php echo esc_html( $student->notes ); ?></p>
                        </div>
                    <?php endif; ?>
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
                            <?php _e( 'View your enrolled groups', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 2</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'Check your attendance history', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 3</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'View upcoming training sessions', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-secondary me-2">Phase 3</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'See session details and exercises', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-0">
                            <span class="badge bg-secondary me-2">Phase 4</span>
                            <i class="bi bi-check2-circle"></i>
                            <?php _e( 'View payment history and balance', 'igm-academy-manager' ); ?>
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
        <?php _e( 'Class schedules, attendance tracking, and payment features will be available in upcoming phases.', 'igm-academy-manager' ); ?>
    </div>
</div>
