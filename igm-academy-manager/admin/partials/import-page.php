<?php
/**
 * Import page view
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
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <?php if ( isset( $result ) ) : ?>
        <?php if ( $result['success'] ) : ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <?php
                    printf(
                        __( 'Successfully imported %d records.', 'igm-academy-manager' ),
                        $result['imported']
                    );
                    ?>
                </p>
                <?php if ( ! empty( $result['errors'] ) ) : ?>
                    <p><strong><?php _e( 'Errors:', 'igm-academy-manager' ); ?></strong></p>
                    <ul>
                        <?php foreach ( $result['errors'] as $error ) : ?>
                            <li><?php echo esc_html( $error ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html( $result['message'] ); ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="igm-import-container">
        <div class="igm-import-section">
            <h2><?php _e( 'Import Students from CSV', 'igm-academy-manager' ); ?></h2>
            <p><?php _e( 'Upload a CSV file with student data. The file should have the following columns:', 'igm-academy-manager' ); ?></p>
            <ul>
                <li><?php _e( 'First Name, Last Name, Email, Phone, Gender, Birth Date, Notes, Last Class, Total Classes, Pending Classes', 'igm-academy-manager' ); ?></li>
            </ul>

            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field( 'igm_import_action', 'igm_import_nonce' ); ?>
                <input type="hidden" name="import_type" value="students">

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="import_file"><?php _e( 'CSV File', 'igm-academy-manager' ); ?></label>
                        </th>
                        <td>
                            <input type="file" id="import_file" name="import_file" accept=".csv" required>
                            <p class="description">
                                <?php _e( 'Select a CSV file to import. File should use semicolon (;) as separator.', 'igm-academy-manager' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="igm_import_submit" class="button button-primary" value="<?php esc_attr_e( 'Import Students', 'igm-academy-manager' ); ?>">
                </p>
            </form>
        </div>

        <div class="igm-import-section">
            <h2><?php _e( 'Import Exercises from Excel', 'igm-academy-manager' ); ?></h2>
            <p><?php _e( 'This feature will be available in Phase 3.', 'igm-academy-manager' ); ?></p>
            <p class="description"><?php _e( 'You will be able to import tennis and padel exercises from Excel files.', 'igm-academy-manager' ); ?></p>
        </div>

        <div class="igm-import-info">
            <h3><?php _e( 'Import Notes', 'igm-academy-manager' ); ?></h3>
            <ul>
                <li><?php _e( 'Existing students (matched by email) will be updated with new data.', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'New students will be added to the database.', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'Make sure your CSV file is properly formatted to avoid errors.', 'igm-academy-manager' ); ?></li>
                <li><?php _e( 'Spanish date formats (e.g., "10 de July de 1976") are supported.', 'igm-academy-manager' ); ?></li>
            </ul>
        </div>
    </div>
</div>
