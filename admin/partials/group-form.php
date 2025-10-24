<?php
/**
 * Group Form - Add/Edit Group
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit = ! empty( $group );
$page_title = $is_edit ? __( 'Edit Group', 'igm-academy-manager' ) : __( 'Add New Group', 'igm-academy-manager' );
?>

<div class="wrap">
    <!-- Page Header -->
    <div class="igm-page-header">
        <h1>
            <i class="bi bi-<?php echo $is_edit ? 'pencil-square' : 'plus-circle'; ?>"></i>
            <?php echo esc_html( $page_title ); ?>
        </h1>
    </div>

    <!-- Form Card -->
    <div class="card mw-100">
        <div class="card-body">
            <form method="post" action="">
                <?php wp_nonce_field( 'igm_group_action', 'igm_group_nonce' ); ?>
                <input type="hidden" name="igm_action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
                <?php if ( $is_edit ) : ?>
                    <input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">
                <?php endif; ?>

                <div class="row">
                    <!-- Group Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            <?php _e( 'Group Name', 'igm-academy-manager' ); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               value="<?php echo $is_edit ? esc_attr( $group->name ) : ''; ?>"
                               required>
                    </div>

                    <!-- Coach -->
                    <div class="col-md-6 mb-3">
                        <label for="coach_id" class="form-label">
                            <?php _e( 'Assigned Coach', 'igm-academy-manager' ); ?>
                        </label>
                        <select class="form-select" id="coach_id" name="coach_id">
                            <option value=""><?php _e( 'No coach assigned', 'igm-academy-manager' ); ?></option>
                            <?php foreach ( $coaches as $coach ) : ?>
                                <option value="<?php echo esc_attr( $coach->id ); ?>"
                                    <?php echo ( $is_edit && $group->coach_id == $coach->id ) ? 'selected' : ''; ?>>
                                    <?php echo esc_html( $coach->first_name . ' ' . $coach->last_name ); ?>
                                    <?php if ( $coach->specialty ) : ?>
                                        (<?php echo esc_html( ucfirst( $coach->specialty ) ); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Sport Type -->
                    <div class="col-md-6 mb-3">
                        <label for="sport_type" class="form-label">
                            <?php _e( 'Sport Type', 'igm-academy-manager' ); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="sport_type" name="sport_type" required>
                            <option value="tennis" <?php echo ( $is_edit && $group->sport_type === 'tennis' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Tennis', 'igm-academy-manager' ); ?>
                            </option>
                            <option value="padel" <?php echo ( $is_edit && $group->sport_type === 'padel' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Padel', 'igm-academy-manager' ); ?>
                            </option>
                        </select>
                    </div>

                    <!-- Level -->
                    <div class="col-md-6 mb-3">
                        <label for="level" class="form-label">
                            <?php _e( 'Level', 'igm-academy-manager' ); ?>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="level"
                               name="level"
                               value="<?php echo $is_edit ? esc_attr( $group->level ) : ''; ?>"
                               placeholder="<?php esc_attr_e( 'e.g., Beginner, Intermediate, Advanced', 'igm-academy-manager' ); ?>">
                    </div>

                    <!-- Max Students -->
                    <div class="col-md-6 mb-3">
                        <label for="max_students" class="form-label">
                            <?php _e( 'Maximum Students', 'igm-academy-manager' ); ?>
                        </label>
                        <input type="number"
                               class="form-control"
                               id="max_students"
                               name="max_students"
                               value="<?php echo $is_edit ? esc_attr( $group->max_students ) : '10'; ?>"
                               min="1"
                               max="50">
                    </div>

                    <!-- Schedule -->
                    <div class="col-md-12 mb-3">
                        <label for="schedule" class="form-label">
                            <?php _e( 'Schedule', 'igm-academy-manager' ); ?>
                        </label>
                        <textarea class="form-control"
                                  id="schedule"
                                  name="schedule"
                                  rows="3"
                                  placeholder="<?php esc_attr_e( 'e.g., Monday & Wednesday 4:00 PM - 5:30 PM', 'igm-academy-manager' ); ?>"><?php echo $is_edit ? esc_textarea( $group->schedule ) : ''; ?></textarea>
                        <small class="form-text text-muted">
                            <?php _e( 'Describe when this group meets (days, times, etc.)', 'igm-academy-manager' ); ?>
                        </small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="<?php echo admin_url( 'admin.php?page=igm-groups' ); ?>"
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <?php _e( 'Back to Groups', 'igm-academy-manager' ); ?>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i>
                        <?php echo $is_edit ? _e( 'Update Group', 'igm-academy-manager' ) : _e( 'Create Group', 'igm-academy-manager' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
