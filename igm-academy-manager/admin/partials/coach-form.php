<?php
/**
 * Coach add/edit form
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit = isset( $coach ) && $coach;
$page_title = $is_edit ? __( 'Edit Coach', 'igm-academy-manager' ) : __( 'Add New Coach', 'igm-academy-manager' );
?>

<div class="wrap">
    <h1><?php echo esc_html( $page_title ); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field( 'igm_coach_action', 'igm_coach_nonce' ); ?>
        <input type="hidden" name="igm_action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
        <?php if ( $is_edit ) : ?>
            <input type="hidden" name="coach_id" value="<?php echo esc_attr( $coach->id ); ?>">
            <input type="hidden" name="user_id" value="<?php echo esc_attr( $coach->user_id ); ?>">
        <?php endif; ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="first_name"><?php _e( 'First Name', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="text" id="first_name" name="first_name" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $coach->first_name ) : ''; ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="last_name"><?php _e( 'Last Name', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="text" id="last_name" name="last_name" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $coach->last_name ) : ''; ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="email"><?php _e( 'Email', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="email" id="email" name="email" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $coach->email ) : ''; ?>" required>
                    <?php if ( $is_edit && $coach->user_id ) : ?>
                        <p class="description">
                            <span class="dashicons dashicons-yes" style="color: green;"></span>
                            <?php _e( 'This coach has a WordPress user account.', 'igm-academy-manager' ); ?>
                        </p>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="phone"><?php _e( 'Phone', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <input type="text" id="phone" name="phone" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $coach->phone ) : ''; ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="specialty"><?php _e( 'Specialty', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <select id="specialty" name="specialty" required>
                        <option value=""><?php _e( 'Select specialty...', 'igm-academy-manager' ); ?></option>
                        <option value="tennis" <?php echo ( $is_edit && $coach->specialty === 'tennis' ) ? 'selected' : ''; ?>>
                            🎾 <?php _e( 'Tennis', 'igm-academy-manager' ); ?>
                        </option>
                        <option value="padel" <?php echo ( $is_edit && $coach->specialty === 'padel' ) ? 'selected' : ''; ?>>
                            🏸 <?php _e( 'Padel', 'igm-academy-manager' ); ?>
                        </option>
                        <option value="both" <?php echo ( $is_edit && $coach->specialty === 'both' ) ? 'selected' : ''; ?>>
                            🎾🏸 <?php _e( 'Both (Tennis & Padel)', 'igm-academy-manager' ); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e( 'What sport(s) does this coach teach?', 'igm-academy-manager' ); ?>
                    </p>
                </td>
            </tr>

            <?php if ( ! $is_edit ) : ?>
            <tr>
                <th scope="row">
                    <label for="create_wp_user"><?php _e( 'Create WordPress Account', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" id="create_wp_user" name="create_wp_user" value="yes" checked>
                        <?php _e( 'Create a WordPress user account for this coach', 'igm-academy-manager' ); ?>
                    </label>
                    <p class="description">
                        <?php _e( 'If checked, an account will be created and the coach will receive login credentials via email.', 'igm-academy-manager' ); ?>
                    </p>
                </td>
            </tr>
            <?php endif; ?>
        </table>

        <p class="submit">
            <input type="submit" class="button button-primary" value="<?php echo $is_edit ? esc_attr__( 'Update Coach', 'igm-academy-manager' ) : esc_attr__( 'Add Coach', 'igm-academy-manager' ); ?>">
            <a href="<?php echo admin_url( 'admin.php?page=igm-coaches' ); ?>" class="button button-secondary">
                <?php _e( 'Cancel', 'igm-academy-manager' ); ?>
            </a>
        </p>
    </form>

    <?php if ( $is_edit && $coach->user_id ) : ?>
    <div class="igm-info-box" style="margin-top: 30px;">
        <h3><?php _e( 'WordPress User Details', 'igm-academy-manager' ); ?></h3>
        <?php
        $user = get_user_by( 'id', $coach->user_id );
        if ( $user ) :
        ?>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Username', 'igm-academy-manager' ); ?>:</th>
                <td><?php echo esc_html( $user->user_login ); ?></td>
            </tr>
            <tr>
                <th><?php _e( 'Role', 'igm-academy-manager' ); ?>:</th>
                <td><?php echo esc_html( implode( ', ', $user->roles ) ); ?></td>
            </tr>
            <tr>
                <th><?php _e( 'Registered', 'igm-academy-manager' ); ?>:</th>
                <td><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $user->user_registered ) ) ); ?></td>
            </tr>
        </table>
        <p>
            <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $user->ID ); ?>" class="button">
                <?php _e( 'Edit WordPress User', 'igm-academy-manager' ); ?>
            </a>
        </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
