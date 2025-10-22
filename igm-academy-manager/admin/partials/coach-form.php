<?php
/**
 * Coach Add/Edit Form - Modern Bootstrap 5 Design
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit = isset( $coach ) && $coach;
$page_title = $is_edit ? __( 'Edit Coach', 'igm-academy-manager' ) : __( 'Add New Coach', 'igm-academy-manager' );
$submit_text = $is_edit ? __( 'Update Coach', 'igm-academy-manager' ) : __( 'Add Coach', 'igm-academy-manager' );
?>

<div class="wrap">
    <!-- Modern Page Header -->
    <div class="igm-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-<?php echo $is_edit ? 'pencil-square' : 'person-badge'; ?>"></i>
                    <?php echo esc_html( $page_title ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php echo $is_edit ? __( 'Update coach information', 'igm-academy-manager' ) : __( 'Add a new coach to the academy', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-coaches' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left"></i>
                    <?php _e( 'Back to List', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-person-vcard"></i>
                <?php _e( 'Coach Information', 'igm-academy-manager' ); ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="" class="igm-form needs-validation" novalidate>
                <?php wp_nonce_field( 'igm_coach_action', 'igm_coach_nonce' ); ?>
                <input type="hidden" name="igm_action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
                <?php if ( $is_edit ) : ?>
                    <input type="hidden" name="coach_id" value="<?php echo esc_attr( $coach->id ); ?>">
                    <input type="hidden" name="user_id" value="<?php echo esc_attr( $coach->user_id ); ?>">
                <?php endif; ?>

                <!-- Personal Information Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase mb-3">
                            <i class="bi bi-person-circle"></i>
                            <?php _e( 'Personal Information', 'igm-academy-manager' ); ?>
                        </h6>
                    </div>

                    <!-- First Name -->
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">
                            <?php _e( 'First Name', 'igm-academy-manager' ); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text"
                                   id="first_name"
                                   name="first_name"
                                   class="form-control"
                                   value="<?php echo $is_edit ? esc_attr( $coach->first_name ) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">
                                <?php _e( 'Please enter first name', 'igm-academy-manager' ); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">
                            <?php _e( 'Last Name', 'igm-academy-manager' ); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text"
                                   id="last_name"
                                   name="last_name"
                                   class="form-control"
                                   value="<?php echo $is_edit ? esc_attr( $coach->last_name ) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">
                                <?php _e( 'Please enter last name', 'igm-academy-manager' ); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <?php _e( 'Email', 'igm-academy-manager' ); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   value="<?php echo $is_edit ? esc_attr( $coach->email ) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">
                                <?php _e( 'Please enter a valid email', 'igm-academy-manager' ); ?>
                            </div>
                        </div>
                        <?php if ( $is_edit && $coach->user_id ) : ?>
                            <small class="form-text text-success">
                                <i class="bi bi-check-circle"></i>
                                <?php _e( 'This coach has a WordPress user account', 'igm-academy-manager' ); ?>
                            </small>
                        <?php elseif ( ! $is_edit ) : ?>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i>
                                <?php _e( 'A WordPress user account will be created automatically', 'igm-academy-manager' ); ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">
                            <?php _e( 'Phone', 'igm-academy-manager' ); ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   class="form-control"
                                   value="<?php echo $is_edit ? esc_attr( $coach->phone ) : ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Coaching Information Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase mb-3">
                            <i class="bi bi-trophy"></i>
                            <?php _e( 'Coaching Information', 'igm-academy-manager' ); ?>
                        </h6>
                    </div>

                    <!-- Specialty -->
                    <div class="col-md-6 mb-3">
                        <label for="specialty" class="form-label">
                            <?php _e( 'Specialty', 'igm-academy-manager' ); ?>
                            <span class="required">*</span>
                        </label>
                        <select id="specialty" name="specialty" class="form-select" required>
                            <option value=""><?php _e( 'Select specialty...', 'igm-academy-manager' ); ?></option>
                            <option value="tennis" <?php echo ( $is_edit && $coach->specialty === 'tennis' ) ? 'selected' : ''; ?>>
                                <i class="bi bi-trophy"></i> <?php _e( 'Tennis', 'igm-academy-manager' ); ?>
                            </option>
                            <option value="padel" <?php echo ( $is_edit && $coach->specialty === 'padel' ) ? 'selected' : ''; ?>>
                                <i class="bi bi-star"></i> <?php _e( 'Padel', 'igm-academy-manager' ); ?>
                            </option>
                            <option value="both" <?php echo ( $is_edit && $coach->specialty === 'both' ) ? 'selected' : ''; ?>>
                                <i class="bi bi-award"></i> <?php _e( 'Both (Tennis & Padel)', 'igm-academy-manager' ); ?>
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            <?php _e( 'What sport(s) does this coach teach?', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end border-top pt-3">
                    <a href="<?php echo admin_url( 'admin.php?page=igm-coaches' ); ?>"
                       class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-x-circle"></i>
                        <?php _e( 'Cancel', 'igm-academy-manager' ); ?>
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-<?php echo $is_edit ? 'check-circle' : 'plus-circle'; ?>"></i>
                        <?php echo esc_html( $submit_text ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ( $is_edit && $coach->user_id ) : ?>
        <!-- WordPress User Details Card -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-wordpress text-primary"></i>
                    <?php _e( 'WordPress User Details', 'igm-academy-manager' ); ?>
                </h5>
            </div>
            <div class="card-body">
                <?php
                $user = get_user_by( 'id', $coach->user_id );
                if ( $user ) :
                ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted"><?php _e( 'Username', 'igm-academy-manager' ); ?></label>
                            <div class="form-control-plaintext">
                                <i class="bi bi-person-circle"></i>
                                <?php echo esc_html( $user->user_login ); ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted"><?php _e( 'Role', 'igm-academy-manager' ); ?></label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-success">
                                    <?php echo esc_html( implode( ', ', $user->roles ) ); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted"><?php _e( 'Registered', 'igm-academy-manager' ); ?></label>
                            <div class="form-control-plaintext">
                                <i class="bi bi-calendar-check"></i>
                                <?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $user->user_registered ) ) ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $user->ID ); ?>"
                           class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square"></i>
                            <?php _e( 'Edit WordPress User', 'igm-academy-manager' ); ?>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?php _e( 'WordPress user not found or has been deleted.', 'igm-academy-manager' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Help Card -->
    <div class="card mt-4">
        <div class="card-body bg-light">
            <h6 class="mb-3">
                <i class="bi bi-lightbulb text-warning"></i>
                <?php _e( 'Tips', 'igm-academy-manager' ); ?>
            </h6>
            <ul class="mb-0 small">
                <li>
                    <?php _e( 'Fields marked with * are required', 'igm-academy-manager' ); ?>
                </li>
                <?php if ( ! $is_edit ) : ?>
                    <li>
                        <?php _e( 'A WordPress user account will be created automatically with the email provided', 'igm-academy-manager' ); ?>
                    </li>
                    <li>
                        <?php _e( 'The coach will receive a notification email with login credentials', 'igm-academy-manager' ); ?>
                    </li>
                <?php endif; ?>
                <li>
                    <?php _e( 'Coaches can be assigned to groups in Phase 2 (Groups Management)', 'igm-academy-manager' ); ?>
                </li>
                <li>
                    <?php _e( 'The specialty selection helps in organizing and filtering coaches', 'igm-academy-manager' ); ?>
                </li>
            </ul>
        </div>
    </div>
</div>
