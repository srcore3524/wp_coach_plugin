<?php
/**
 * Student add/edit form
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/partials
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit = isset( $student ) && $student;
$page_title = $is_edit ? __( 'Edit Student', 'igm-academy-manager' ) : __( 'Add New Student', 'igm-academy-manager' );
?>

<div class="wrap">
    <h1><?php echo esc_html( $page_title ); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field( 'igm_student_action', 'igm_student_nonce' ); ?>
        <input type="hidden" name="igm_action" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
        <?php if ( $is_edit ) : ?>
            <input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
        <?php endif; ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="first_name"><?php _e( 'First Name', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="text" id="first_name" name="first_name" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $student->first_name ) : ''; ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="last_name"><?php _e( 'Last Name', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="text" id="last_name" name="last_name" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $student->last_name ) : ''; ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="email"><?php _e( 'Email', 'igm-academy-manager' ); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="email" id="email" name="email" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $student->email ) : ''; ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="phone"><?php _e( 'Phone', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <input type="text" id="phone" name="phone" class="regular-text"
                           value="<?php echo $is_edit ? esc_attr( $student->phone ) : ''; ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="gender"><?php _e( 'Gender', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <select id="gender" name="gender">
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
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="birth_date"><?php _e( 'Birth Date', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <input type="date" id="birth_date" name="birth_date"
                           value="<?php echo $is_edit ? esc_attr( $student->birth_date ) : ''; ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="total_classes"><?php _e( 'Total Classes', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <input type="number" id="total_classes" name="total_classes" min="0"
                           value="<?php echo $is_edit ? esc_attr( $student->total_classes ) : '0'; ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="pending_classes"><?php _e( 'Pending Classes', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <input type="number" id="pending_classes" name="pending_classes" min="0"
                           value="<?php echo $is_edit ? esc_attr( $student->pending_classes ) : '0'; ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="notes"><?php _e( 'Notes', 'igm-academy-manager' ); ?></label>
                </th>
                <td>
                    <textarea id="notes" name="notes" rows="5" class="large-text"><?php echo $is_edit ? esc_textarea( $student->notes ) : ''; ?></textarea>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button button-primary" value="<?php echo $is_edit ? esc_attr__( 'Update Student', 'igm-academy-manager' ) : esc_attr__( 'Add Student', 'igm-academy-manager' ); ?>">
            <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>" class="button button-secondary">
                <?php _e( 'Cancel', 'igm-academy-manager' ); ?>
            </a>
        </p>
    </form>
</div>
