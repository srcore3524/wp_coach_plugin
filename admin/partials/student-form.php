<?php
/**
 * Student Add/Edit Form - Modern Bootstrap 5 Design
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit = isset( $student ) && $student;
$page_title = $is_edit ? __( 'Edit Student', 'igm-academy-manager' ) : __( 'Add New Student', 'igm-academy-manager' );
$submit_text = $is_edit ? __( 'Update Student', 'igm-academy-manager' ) : __( 'Add Student', 'igm-academy-manager' );
?>

<div class="wrap">
    <!-- Modern Page Header -->
    <div class="igm-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-<?php echo $is_edit ? 'pencil-square' : 'person-plus'; ?>"></i>
                    <?php echo esc_html( $page_title ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php echo $is_edit ? __( 'Update student information', 'igm-academy-manager' ) : __( 'Add a new student to the academy', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>"
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
                <?php _e( 'Student Information', 'igm-academy-manager' ); ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="" class="igm-form needs-validation" novalidate>
                <?php wp_nonce_field( 'igm_student_action', 'igm_student_nonce' ); ?>
                <input type="hidden" name="igm_action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
                <?php if ( $is_edit ) : ?>
                    <input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
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
                                   value="<?php echo $is_edit ? esc_attr( $student->first_name ) : ''; ?>"
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
                                   value="<?php echo $is_edit ? esc_attr( $student->last_name ) : ''; ?>"
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
                                   value="<?php echo $is_edit ? esc_attr( $student->email ) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">
                                <?php _e( 'Please enter a valid email', 'igm-academy-manager' ); ?>
                            </div>
                        </div>
                        <?php if ( ! $is_edit ) : ?>
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
                                   value="<?php echo $is_edit ? esc_attr( $student->phone ) : ''; ?>">
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">
                            <?php _e( 'Gender', 'igm-academy-manager' ); ?>
                        </label>
                        <select id="gender" name="gender" class="form-select">
                            <option value=""><?php _e( 'Select...', 'igm-academy-manager' ); ?></option>
                            <option value="male" <?php echo ( $is_edit && $student->gender === 'male' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Male', 'igm-academy-manager' ); ?>
                            </option>
                            <option value="female" <?php echo ( $is_edit && $student->gender === 'female' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Female', 'igm-academy-manager' ); ?>
                            </option>
                            <option value="other" <?php echo ( $is_edit && $student->gender === 'other' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Other', 'igm-academy-manager' ); ?>
                            </option>
                        </select>
                    </div>

                    <!-- Birth Date -->
                    <div class="col-md-6 mb-3">
                        <label for="birth_date" class="form-label">
                            <?php _e( 'Birth Date', 'igm-academy-manager' ); ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                            <input type="date"
                                   id="birth_date"
                                   name="birth_date"
                                   class="form-control"
                                   value="<?php echo $is_edit ? esc_attr( $student->birth_date ) : ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Classes Information Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase mb-3">
                            <i class="bi bi-calendar-check"></i>
                            <?php _e( 'Classes Information', 'igm-academy-manager' ); ?>
                        </h6>
                    </div>

                    <!-- Total Classes -->
                    <div class="col-md-6 mb-3">
                        <label for="total_classes" class="form-label">
                            <?php _e( 'Total Classes', 'igm-academy-manager' ); ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-list-ol"></i>
                            </span>
                            <input type="number"
                                   id="total_classes"
                                   name="total_classes"
                                   class="form-control"
                                   min="0"
                                   value="<?php echo $is_edit ? esc_attr( $student->total_classes ) : '0'; ?>">
                        </div>
                        <small class="form-text text-muted">
                            <?php _e( 'Total number of classes purchased', 'igm-academy-manager' ); ?>
                        </small>
                    </div>

                    <!-- Pending Classes -->
                    <div class="col-md-6 mb-3">
                        <label for="pending_classes" class="form-label">
                            <?php _e( 'Pending Classes', 'igm-academy-manager' ); ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-hourglass-split"></i>
                            </span>
                            <input type="number"
                                   id="pending_classes"
                                   name="pending_classes"
                                   class="form-control"
                                   min="0"
                                   value="<?php echo $is_edit ? esc_attr( $student->pending_classes ) : '0'; ?>">
                        </div>
                        <small class="form-text text-muted">
                            <?php _e( 'Classes remaining to be taken', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase mb-3">
                            <i class="bi bi-file-text"></i>
                            <?php _e( 'Additional Information', 'igm-academy-manager' ); ?>
                        </h6>
                    </div>

                    <!-- Notes -->
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">
                            <?php _e( 'Notes', 'igm-academy-manager' ); ?>
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="5"
                                  class="form-control"
                                  placeholder="<?php esc_attr_e( 'Add any additional notes about the student...', 'igm-academy-manager' ); ?>"><?php echo $is_edit ? esc_textarea( $student->notes ) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end border-top pt-3">
                    <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>"
                       class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-x-circle"></i>
                        <?php _e( 'Cancel', 'igm-academy-manager' ); ?>
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-<?php echo $is_edit ? 'check-circle' : 'plus-circle'; ?>"></i>
                        <?php echo esc_html( $submit_text ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                        <?php _e( 'The student will receive a notification email with login credentials', 'igm-academy-manager' ); ?>
                    </li>
                <?php endif; ?>
                <li>
                    <?php _e( 'Total classes should be greater than or equal to pending classes', 'igm-academy-manager' ); ?>
                </li>
                <li>
                    <?php _e( 'Use the notes field to record any special considerations or information', 'igm-academy-manager' ); ?>
                </li>
            </ul>
        </div>
    </div>
</div>
