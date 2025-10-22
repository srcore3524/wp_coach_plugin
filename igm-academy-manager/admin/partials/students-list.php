<?php
/**
 * Students List - Modern Bootstrap 5 Design
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
                    <?php _e( 'Students Management', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php printf( _n( '%d student registered', '%d students registered', $total_students, 'igm-academy-manager' ), $total_students ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus-fill"></i>
                    <?php _e( 'Add New Student', 'igm-academy-manager' ); ?>
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
                _e( 'Student created successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'updated' ) {
                _e( 'Student updated successfully.', 'igm-academy-manager' );
            } elseif ( $_GET['message'] === 'deleted' ) {
                _e( 'Student deleted successfully.', 'igm-academy-manager' );
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
            if ( $_GET['error'] === 'student_not_found' ) {
                _e( 'Student not found.', 'igm-academy-manager' );
            } elseif ( $_GET['error'] === 'delete_failed' ) {
                _e( 'Failed to delete student.', 'igm-academy-manager' );
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Students Table Card -->
    <div class="mw-100 card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                <?php _e( 'All Students', 'igm-academy-manager' ); ?>
            </h5>
            <?php if ( ! empty( $students ) ) : ?>
                <span class="badge bg-primary"><?php echo esc_html( $total_students ); ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if ( ! empty( $students ) ) : ?>
                <div class="table-responsive">
                    <table id="students-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Name', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Email', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Phone', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Gender', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Birth Date', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Total Classes', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Pending Classes', 'igm-academy-manager' ); ?></th>
                                <th class="text-center"><?php _e( 'Actions', 'igm-academy-manager' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $students as $student ) : ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo esc_html( $student->id ); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 14px;">
                                                <?php echo esc_html( strtoupper( substr( $student->first_name, 0, 1 ) ) ); ?>
                                            </div>
                                            <strong><?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope"></i>
                                        <?php echo esc_html( $student->email ); ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-telephone"></i>
                                        <?php echo esc_html( $student->phone ? $student->phone : '-' ); ?>
                                    </td>
                                    <td>
                                        <?php if ( $student->gender === 'male' ) : ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-gender-male"></i>
                                                <?php _e( 'Male', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php elseif ( $student->gender === 'female' ) : ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-gender-female"></i>
                                                <?php _e( 'Female', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ( $student->birth_date ) : ?>
                                            <i class="bi bi-calendar-event"></i>
                                            <?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $student->birth_date ) ) ); ?>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo esc_html( $student->total_classes ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $student->pending_classes > 0 ? 'bg-primary' : 'bg-secondary'; ?>">
                                            <?php echo esc_html( $student->pending_classes ); ?>
                                        </span>
                                    </td>
                                    <td class="table-actions text-center">
                                        <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=edit&student_id=' . $student->id ); ?>"
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="<?php esc_attr_e( 'Edit Student', 'igm-academy-manager' ); ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                            <?php _e( 'Edit', 'igm-academy-manager' ); ?>
                                        </a>
                                        <form method="post" style="display: inline;">
                                            <?php wp_nonce_field( 'igm_student_action', 'igm_student_nonce' ); ?>
                                            <input type="hidden" name="igm_action" value="delete">
                                            <input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger delete-confirm"
                                                    data-bs-toggle="tooltip"
                                                    title="<?php esc_attr_e( 'Delete Student', 'igm-academy-manager' ); ?>">
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
                    <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-4 mb-2"><?php _e( 'No students found', 'igm-academy-manager' ); ?></h4>
                    <p class="text-muted mb-4">
                        <?php _e( 'Get started by adding your first student or importing from CSV', 'igm-academy-manager' ); ?>
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>"
                           class="btn btn-primary">
                            <i class="bi bi-person-plus-fill"></i>
                            <?php _e( 'Add First Student', 'igm-academy-manager' ); ?>
                        </a>
                        <a href="<?php echo admin_url( 'admin.php?page=igm-import' ); ?>"
                           class="btn btn-outline-primary">
                            <i class="bi bi-upload"></i>
                            <?php _e( 'Import from CSV', 'igm-academy-manager' ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
