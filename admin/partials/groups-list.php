<?php
/**
 * Groups List - Modern Bootstrap 5 Design
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
    <!-- Modern Page Header -->
    <div class="igm-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-people-fill"></i>
                    <?php _e( 'Groups Management', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php printf( _n( '%d group configured', '%d groups configured', $total_groups, 'igm-academy-manager' ), $total_groups ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-groups&action=add' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-plus-circle-fill"></i>
                    <?php _e( 'Add New Group', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ( isset( $_GET['message'] ) ) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong><?php _e( 'Success!', 'igm-academy-manager' ); ?></strong>
            <?php
            if ( $_GET['message'] === 'created' ) {
                _e( 'Group created successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'updated' ) {
                _e( 'Group updated successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'deleted' ) {
                _e( 'Group deleted successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'students_added' ) {
                _e( 'Students added to group successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'student_removed' ) {
                _e( 'Student removed from group successfully.', 'igm-academy-manager' );
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['error'] ) ) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong><?php _e( 'Error!', 'igm-academy-manager' ); ?></strong>
            <?php
            if ( $_GET['error'] === 'invalid_id' ) {
                _e( 'Invalid group ID.', 'igm-academy-manager' );
            } elseif ( $_GET['error'] === 'has_students' ) {
                _e( 'Cannot delete group. This group has assigned students. Please remove all students first.', 'igm-academy-manager' );
            } elseif ( $_GET['error'] === 'no_students_selected' ) {
                _e( 'Please select at least one student to add.', 'igm-academy-manager' );
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Groups Table Card -->
    <div class="mw-100 card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                <?php _e( 'All Groups', 'igm-academy-manager' ); ?>
            </h5>
            <?php if ( ! empty( $groups ) ) : ?>
                <span class="badge bg-primary"><?php echo esc_html( $total_groups ); ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if ( ! empty( $groups ) ) : ?>
                <div class="table-responsive">
                    <table id="groups-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Name', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Coach', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Sport', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Level', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Students', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Max', 'igm-academy-manager' ); ?></th>
                                <th class="text-center"><?php _e( 'Actions', 'igm-academy-manager' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $groups as $group ) : ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo esc_html( $group->id ); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 14px;">
                                                <?php echo esc_html( strtoupper( substr( $group->name, 0, 1 ) ) ); ?>
                                            </div>
                                            <strong><?php echo esc_html( $group->name ); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ( $group->coach_id && $group->coach_first_name ) : ?>
                                            <i class="bi bi-person-badge"></i>
                                            <?php echo esc_html( $group->coach_first_name . ' ' . $group->coach_last_name ); ?>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ( $group->sport_type === 'tennis' ) : ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-trophy"></i>
                                                <?php _e( 'Tennis', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php elseif ( $group->sport_type === 'padel' ) : ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star"></i>
                                                <?php _e( 'Padel', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo esc_html( $group->level ? $group->level : '-' ); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo esc_html( $group->student_count ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $group->student_count >= $group->max_students ? 'bg-danger' : 'bg-secondary'; ?>">
                                            <?php echo esc_html( $group->max_students ); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions text-center">
                                        <a href="<?php echo admin_url( 'admin.php?page=igm-groups&action=manage_students&group_id=' . $group->id ); ?>"
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip"
                                           title="<?php esc_attr_e( 'Manage Students', 'igm-academy-manager' ); ?>">
                                            <i class="bi bi-people-fill"></i>
                                            <?php _e( 'Students', 'igm-academy-manager' ); ?>
                                        </a>
                                        <a href="<?php echo admin_url( 'admin.php?page=igm-groups&action=edit&group_id=' . $group->id ); ?>"
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="<?php esc_attr_e( 'Edit Group', 'igm-academy-manager' ); ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                            <?php _e( 'Edit', 'igm-academy-manager' ); ?>
                                        </a>
                                        <form method="post" style="display: inline;">
                                            <?php wp_nonce_field( 'igm_group_action', 'igm_group_nonce' ); ?>
                                            <input type="hidden" name="igm_action" value="delete">
                                            <input type="hidden" name="group_id" value="<?php echo esc_attr( $group->id ); ?>">
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger delete-confirm"
                                                    data-bs-toggle="tooltip"
                                                    title="<?php esc_attr_e( 'Delete Group', 'igm-academy-manager' ); ?>">
                                                <i class="bi bi-trash-fill"></i>
                                                <?php _e( 'Delete', 'igm-academy-manager' ); ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-4 mb-2"><?php _e( 'No groups found', 'igm-academy-manager' ); ?></h4>
                    <p class="text-muted mb-4">
                        <?php _e( 'Get started by creating your first group', 'igm-academy-manager' ); ?>
                    </p>
                    <a href="<?php echo admin_url( 'admin.php?page=igm-groups&action=add' ); ?>"
                       class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i>
                        <?php _e( 'Add First Group', 'igm-academy-manager' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
