<?php
/**
 * Coaches list view
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Coaches', 'igm-academy-manager' ); ?></h1>
    <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>" class="page-title-action">
        <?php _e( 'Add New', 'igm-academy-manager' ); ?>
    </a>
    <hr class="wp-header-end">

    <?php if ( isset( $_GET['message'] ) ) : ?>
        <?php if ( $_GET['message'] === 'success' ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Coach saved successfully.', 'igm-academy-manager' ); ?></p>
            </div>
        <?php elseif ( $_GET['message'] === 'deleted' ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Coach deleted successfully.', 'igm-academy-manager' ); ?></p>
            </div>
        <?php elseif ( $_GET['message'] === 'error' ) : ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <?php
                    if ( isset( $_GET['error'] ) && $_GET['error'] === 'has_groups' ) {
                        _e( 'Cannot delete coach. This coach has assigned groups.', 'igm-academy-manager' );
                    } else {
                        _e( 'An error occurred.', 'igm-academy-manager' );
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="get" action="">
        <input type="hidden" name="page" value="igm-coaches">
        <p class="search-box">
            <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search coaches...', 'igm-academy-manager' ); ?>">
            <input type="submit" class="button" value="<?php esc_attr_e( 'Search', 'igm-academy-manager' ); ?>">
        </p>
    </form>

    <p class="igm-total-count">
        <?php printf( _n( '%d coach', '%d coaches', $total_coaches, 'igm-academy-manager' ), $total_coaches ); ?>
    </p>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e( 'ID', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'Name', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'Email', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'Phone', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'Specialty', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'WP User', 'igm-academy-manager' ); ?></th>
                <th><?php _e( 'Actions', 'igm-academy-manager' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $coaches ) ) : ?>
                <?php foreach ( $coaches as $coach ) : ?>
                    <tr>
                        <td><?php echo esc_html( $coach->id ); ?></td>
                        <td><strong><?php echo esc_html( $coach->first_name . ' ' . $coach->last_name ); ?></strong></td>
                        <td><?php echo esc_html( $coach->email ); ?></td>
                        <td><?php echo esc_html( $coach->phone ); ?></td>
                        <td>
                            <?php
                            if ( $coach->specialty === 'tennis' ) {
                                echo '🎾 ' . __( 'Tennis', 'igm-academy-manager' );
                            } elseif ( $coach->specialty === 'padel' ) {
                                echo '🏸 ' . __( 'Padel', 'igm-academy-manager' );
                            } elseif ( $coach->specialty === 'both' ) {
                                echo '🎾🏸 ' . __( 'Both', 'igm-academy-manager' );
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ( $coach->user_id ) {
                                $user = get_user_by( 'id', $coach->user_id );
                                if ( $user ) {
                                    echo '<span class="dashicons dashicons-yes" style="color: green;"></span> ';
                                    echo esc_html( $user->user_login );
                                }
                            } else {
                                echo '<span class="dashicons dashicons-no" style="color: #ccc;"></span> ';
                                _e( 'No account', 'igm-academy-manager' );
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=edit&coach_id=' . $coach->id ); ?>" class="button button-small">
                                <?php _e( 'Edit', 'igm-academy-manager' ); ?>
                            </a>
                            <form method="post" style="display: inline;" onsubmit="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this coach?', 'igm-academy-manager' ); ?>');">
                                <?php wp_nonce_field( 'igm_coach_action', 'igm_coach_nonce' ); ?>
                                <input type="hidden" name="igm_action" value="delete">
                                <input type="hidden" name="coach_id" value="<?php echo esc_attr( $coach->id ); ?>">
                                <button type="submit" class="button button-small button-link-delete">
                                    <?php _e( 'Delete', 'igm-academy-manager' ); ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" style="text-align: center;">
                        <?php _e( 'No coaches found.', 'igm-academy-manager' ); ?>
                        <br><br>
                        <a href="<?php echo admin_url( 'admin.php?page=igm-coaches&action=add' ); ?>" class="button button-primary">
                            <?php _e( 'Add First Coach', 'igm-academy-manager' ); ?>
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
