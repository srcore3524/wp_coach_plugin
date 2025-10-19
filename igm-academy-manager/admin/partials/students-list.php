<?php
/**
 * Students list view
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
    <h1 class="wp-heading-inline"><?php _e( 'Students', 'igm-academy-manager' ); ?></h1>
    <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=add' ); ?>" class="page-title-action">
        <?php _e( 'Add New', 'igm-academy-manager' ); ?>
    </a>
    <hr class="wp-header-end">

    <?php if ( isset( $_GET['message'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                if ( $_GET['message'] === 'success' ) {
                    _e( 'Student saved successfully.', 'igm-academy-manager' );
                } elseif ( $_GET['message'] === 'deleted' ) {
                    _e( 'Student deleted successfully.', 'igm-academy-manager' );
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <form method="get" action="">
        <input type="hidden" name="page" value="igm-students">
        <p class="search-box">
            <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search students...', 'igm-academy-manager' ); ?>">
            <input type="submit" class="button" value="<?php esc_attr_e( 'Search', 'igm-academy-manager' ); ?>">
        </p>
    </form>

    <p class="igm-total-count">
        <?php printf( _n( '%d student', '%d students', $total_students, 'igm-academy-manager' ), $total_students ); ?>
    </p>

    <table class="wp-list-table widefat fixed striped">
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
                <th><?php _e( 'Actions', 'igm-academy-manager' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $students ) ) : ?>
                <?php foreach ( $students as $student ) : ?>
                    <tr>
                        <td><?php echo esc_html( $student->id ); ?></td>
                        <td><strong><?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?></strong></td>
                        <td><?php echo esc_html( $student->email ); ?></td>
                        <td><?php echo esc_html( $student->phone ); ?></td>
                        <td>
                            <?php
                            if ( $student->gender === 'male' ) {
                                _e( 'Male', 'igm-academy-manager' );
                            } elseif ( $student->gender === 'female' ) {
                                _e( 'Female', 'igm-academy-manager' );
                            }
                            ?>
                        </td>
                        <td><?php echo $student->birth_date ? esc_html( date_i18n( 'd/m/Y', strtotime( $student->birth_date ) ) ) : '-'; ?></td>
                        <td><?php echo esc_html( $student->total_classes ); ?></td>
                        <td><?php echo esc_html( $student->pending_classes ); ?></td>
                        <td>
                            <a href="<?php echo admin_url( 'admin.php?page=igm-students&action=edit&student_id=' . $student->id ); ?>" class="button button-small">
                                <?php _e( 'Edit', 'igm-academy-manager' ); ?>
                            </a>
                            <form method="post" style="display: inline;" onsubmit="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this student?', 'igm-academy-manager' ); ?>');">
                                <?php wp_nonce_field( 'igm_student_action', 'igm_student_nonce' ); ?>
                                <input type="hidden" name="igm_action" value="delete">
                                <input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
                                <button type="submit" class="button button-small button-link-delete">
                                    <?php _e( 'Delete', 'igm-academy-manager' ); ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" style="text-align: center;">
                        <?php _e( 'No students found.', 'igm-academy-manager' ); ?>
                        <br><br>
                        <a href="<?php echo admin_url( 'admin.php?page=igm-import' ); ?>" class="button button-primary">
                            <?php _e( 'Import Students from CSV', 'igm-academy-manager' ); ?>
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
