<?php
/**
 * Coaches List - Modern Bootstrap 5 Design
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
                    <i class="bi bi-person-badge-fill"></i>
                    <?php _e( 'Coaches Management', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php printf( _n( '%d coach registered', '%d coaches registered', $total_coaches, 'igm-academy-manager' ), $total_coaches ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus-fill"></i>
                    <?php _e( 'Add New Coach', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ( isset( $_GET['message'] ) ) : ?>
        <?php if ( in_array( $_GET['message'], [ 'created', 'updated', 'success', 'deleted', 'restored' ] ) ) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong><?php _e( 'Success!', 'igm-academy-manager' ); ?></strong>
                <?php
                if ( $_GET['message'] === 'created' ) {
                    _e( 'Coach created successfully.', 'igm-academy-manager' );
                } elseif ( $_GET['message'] === 'updated' ) {
                    _e( 'Coach updated successfully.', 'igm-academy-manager' );
                } elseif ( $_GET['message'] === 'deleted' ) {
                    _e( 'Coach deleted successfully.', 'igm-academy-manager' );
                } elseif ( $_GET['message'] === 'restored' ) {
                    _e( 'Coach restored successfully.', 'igm-academy-manager' );
                } else {
                    _e( 'Coach saved successfully.', 'igm-academy-manager' );
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( isset( $_GET['error'] ) || ( isset( $_GET['message'] ) && $_GET['message'] === 'error' ) ) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong><?php _e( 'Error!', 'igm-academy-manager' ); ?></strong>
            <?php
            if ( isset( $_GET['error'] ) && $_GET['error'] === 'has_groups' ) {
                _e( 'Cannot delete coach. This coach has assigned groups.', 'igm-academy-manager' );
            } elseif ( isset( $_GET['error'] ) && $_GET['error'] === 'coach_not_found' ) {
                _e( 'Coach not found.', 'igm-academy-manager' );
            } else {
                _e( 'An error occurred.', 'igm-academy-manager' );
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Status Tabs -->
    <?php
    $current_status = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'active';
    ?>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_status === 'active' ? 'active' : ''; ?>"
               href="<?php echo admin_url( 'admin.php?page=igm-coaches&status=active' ); ?>">
                <i class="bi bi-check-circle"></i>
                <?php _e( 'Active Coaches', 'igm-academy-manager' ); ?>
                <span class="badge bg-primary ms-1"><?php echo esc_html( $active_count ); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_status === 'deleted' ? 'active' : ''; ?>"
               href="<?php echo admin_url( 'admin.php?page=igm-coaches&status=deleted' ); ?>">
                <i class="bi bi-trash"></i>
                <?php _e( 'Deleted Coaches', 'igm-academy-manager' ); ?>
                <span class="badge bg-secondary ms-1"><?php echo esc_html( $deleted_count ); ?></span>
            </a>
        </li>
    </ul>

    <!-- Coaches Table Card -->
    <div class="mw-100 card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                <?php echo $current_status === 'deleted' ? _e( 'Deleted Coaches', 'igm-academy-manager' ) : _e( 'Active Coaches', 'igm-academy-manager' ); ?>
            </h5>
            <?php if ( ! empty( $coaches ) ) : ?>
                <span class="badge bg-success"><?php echo esc_html( $total_coaches ); ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if ( ! empty( $coaches ) ) : ?>
                <div class="table-responsive">
                    <table id="coaches-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Name', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Email', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Phone', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'Specialty', 'igm-academy-manager' ); ?></th>
                                <th><?php _e( 'WP User', 'igm-academy-manager' ); ?></th>
                                <th class="text-center"><?php _e( 'Actions', 'igm-academy-manager' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $coaches as $coach ) : ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo esc_html( $coach->id ); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px; font-size: 14px;">
                                                <?php echo esc_html( strtoupper( substr( $coach->first_name, 0, 1 ) ) ); ?>
                                            </div>
                                            <strong><?php echo esc_html( $coach->first_name . ' ' . $coach->last_name ); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope"></i>
                                        <?php echo esc_html( $coach->email ); ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-telephone"></i>
                                        <?php echo esc_html( $coach->phone ? $coach->phone : '-' ); ?>
                                    </td>
                                    <td>
                                        <?php if ( $coach->specialty === 'tennis' ) : ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-trophy"></i>
                                                <?php _e( 'Tennis', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php elseif ( $coach->specialty === 'padel' ) : ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star"></i>
                                                <?php _e( 'Padel', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php elseif ( $coach->specialty === 'both' ) : ?>
                                            <span class="badge bg-primary">
                                                <i class="bi bi-award"></i>
                                                <?php _e( 'Both', 'igm-academy-manager' ); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ( $coach->user_id ) {
                                            $user = get_user_by( 'id', $coach->user_id );
                                            if ( $user ) {
                                                echo '<span class="badge bg-success">';
                                                echo '<i class="bi bi-check-circle"></i> ';
                                                echo esc_html( $user->user_login );
                                                echo '</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">';
                                                echo '<i class="bi bi-x-circle"></i> ';
                                                _e( 'Invalid', 'igm-academy-manager' );
                                                echo '</span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">';
                                            echo '<i class="bi bi-dash-circle"></i> ';
                                            _e( 'No account', 'igm-academy-manager' );
                                            echo '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="table-actions text-center">
                                        <?php if ( $current_status === 'active' ) : ?>
                                            <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=edit&coach_id=' . $coach->id ); ?>"
                                               class="btn btn-sm btn-primary"
                                               data-bs-toggle="tooltip"
                                               title="<?php esc_attr_e( 'Edit Coach', 'igm-academy-manager' ); ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                                <?php _e( 'Edit', 'igm-academy-manager' ); ?>
                                            </a>
                                            <form method="post" style="display: inline;">
                                                <?php wp_nonce_field( 'igm_coach_action', 'igm_coach_nonce' ); ?>
                                                <input type="hidden" name="igm_action" value="delete">
                                                <input type="hidden" name="coach_id" value="<?php echo esc_attr( $coach->id ); ?>">
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger delete-confirm"
                                                        data-bs-toggle="tooltip"
                                                        title="<?php esc_attr_e( 'Delete Coach', 'igm-academy-manager' ); ?>">
                                                    <i class="bi bi-trash-fill"></i>
                                                    <?php _e( 'Delete', 'igm-academy-manager' ); ?>
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <form method="post" style="display: inline;">
                                                <?php wp_nonce_field( 'igm_coach_action', 'igm_coach_nonce' ); ?>
                                                <input type="hidden" name="igm_action" value="restore">
                                                <input type="hidden" name="coach_id" value="<?php echo esc_attr( $coach->id ); ?>">
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        data-bs-toggle="tooltip"
                                                        title="<?php esc_attr_e( 'Restore Coach', 'igm-academy-manager' ); ?>">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                    <?php _e( 'Restore', 'igm-academy-manager' ); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="bi bi-person-badge" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-4 mb-2"><?php _e( 'No coaches found', 'igm-academy-manager' ); ?></h4>
                    <p class="text-muted mb-4">
                        <?php _e( 'Start by adding your first coach to the academy', 'igm-academy-manager' ); ?>
                    </p>
                    <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>"
                       class="btn btn-success">
                        <i class="bi bi-person-plus-fill"></i>
                        <?php _e( 'Add First Coach', 'igm-academy-manager' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
