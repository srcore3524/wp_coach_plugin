<?php
/**
 * Group Students Management
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 * @version    1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <!-- Page Header -->
    <div class="igm-page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-people-fill"></i>
                    <?php _e( 'Manage Group Students', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <strong><?php echo esc_html( $group->name ); ?></strong>
                    <?php if ( $group->coach_first_name ) : ?>
                        - <?php printf( __( 'Coach: %s', 'igm-academy-manager' ), esc_html( $group->coach_first_name . ' ' . $group->coach_last_name ) ); ?>
                    <?php endif; ?>
                </p>
            </div>
            <a href="<?php echo admin_url( 'admin.php?page=igm-groups' ); ?>"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                <?php _e( 'Back to Groups', 'igm-academy-manager' ); ?>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Current Students -->
        <div class="col-md-7">
            <div class="card mw-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle"></i>
                        <?php _e( 'Students in this Group', 'igm-academy-manager' ); ?>
                        <span class="badge bg-primary ms-2"><?php echo count( $group_students ); ?>/<?php echo esc_html( $group->max_students ); ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ( ! empty( $group_students ) ) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Name', 'igm-academy-manager' ); ?></th>
                                        <th><?php _e( 'Email', 'igm-academy-manager' ); ?></th>
                                        <th><?php _e( 'Joined Date', 'igm-academy-manager' ); ?></th>
                                        <th><?php _e( 'Action', 'igm-academy-manager' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $group_students as $student ) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                                                         style="width: 28px; height: 28px; font-size: 12px;">
                                                        <?php echo esc_html( strtoupper( substr( $student->first_name, 0, 1 ) ) ); ?>
                                                    </div>
                                                    <?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?>
                                                </div>
                                            </td>
                                            <td><?php echo esc_html( $student->email ); ?></td>
                                            <td>
                                                <?php if ( $student->joined_date ) : ?>
                                                    <?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $student->joined_date ) ) ); ?>
                                                <?php else : ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="post" style="display: inline;">
                                                    <?php wp_nonce_field( 'igm_group_action', 'igm_group_nonce' ); ?>
                                                    <input type="hidden" name="igm_action" value="remove_student">
                                                    <input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">
                                                    <input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to remove this student from the group?', 'igm-academy-manager' ); ?>');">
                                                        <i class="bi bi-x-circle"></i>
                                                        <?php _e( 'Remove', 'igm-academy-manager' ); ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3"><?php _e( 'No students in this group yet', 'igm-academy-manager' ); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Add Students -->
        <div class="col-md-5">
            <div class="card mw-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i>
                        <?php _e( 'Add Students', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ( ! empty( $available_students ) ) : ?>
                        <form method="post">
                            <?php wp_nonce_field( 'igm_group_action', 'igm_group_nonce' ); ?>
                            <input type="hidden" name="igm_action" value="add_students">
                            <input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">

                            <div class="mb-3">
                                <label class="form-label"><?php _e( 'Select students to add', 'igm-academy-manager' ); ?></label>
                                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 0.5rem;">
                                    <?php foreach ( $available_students as $student ) : ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="student_ids[]"
                                                   value="<?php echo esc_attr( $student->id ); ?>"
                                                   id="student_<?php echo esc_attr( $student->id ); ?>">
                                            <label class="form-check-label" for="student_<?php echo esc_attr( $student->id ); ?>">
                                                <?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?>
                                                <small class="text-muted d-block"><?php echo esc_html( $student->email ); ?></small>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle"></i>
                                <?php _e( 'Add Selected Students', 'igm-academy-manager' ); ?>
                            </button>
                        </form>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i>
                            <?php _e( 'All active students are already in this group or other groups.', 'igm-academy-manager' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
