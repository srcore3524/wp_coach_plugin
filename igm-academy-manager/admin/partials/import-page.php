<?php
/**
 * Import Data Page - Modern Bootstrap 5 Design
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
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-upload"></i>
                    <?php _e( 'Import Data', 'igm-academy-manager' ); ?>
                </h1>
                <p class="mb-0 mt-2">
                    <?php _e( 'Import students and exercises from CSV/Excel files', 'igm-academy-manager' ); ?>
                </p>
            </div>
            <div>
                <a href="<?php echo admin_url( 'admin.php?page=igm-students' ); ?>"
                   class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left"></i>
                    <?php _e( 'Back to Students', 'igm-academy-manager' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ( isset( $result ) ) : ?>
        <?php if ( $result['success'] ) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong><?php _e( 'Import Successful!', 'igm-academy-manager' ); ?></strong>
                <?php
                printf(
                    __( 'Successfully imported %d records.', 'igm-academy-manager' ),
                    $result['imported']
                );
                ?>
                <?php if ( ! empty( $result['errors'] ) ) : ?>
                    <hr>
                    <p class="mb-2"><strong><?php _e( 'Some errors occurred:', 'igm-academy-manager' ); ?></strong></p>
                    <ul class="mb-0">
                        <?php foreach ( $result['errors'] as $error ) : ?>
                            <li><?php echo esc_html( $error ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php else : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong><?php _e( 'Import Failed!', 'igm-academy-manager' ); ?></strong>
                <?php echo esc_html( $result['message'] ); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Import Students Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-spreadsheet text-primary"></i>
                        <?php _e( 'Import Students from CSV', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" class="igm-form">
                        <?php wp_nonce_field( 'igm_import_action', 'igm_import_nonce' ); ?>
                        <input type="hidden" name="import_type" value="students">

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="import_file" class="form-label">
                                <?php _e( 'CSV File', 'igm-academy-manager' ); ?>
                                <span class="required">*</span>
                            </label>
                            <input type="file"
                                   id="import_file"
                                   name="import_file"
                                   class="form-control"
                                   accept=".csv"
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i>
                                <?php _e( 'Select a CSV file to import. File should use semicolon (;) as separator.', 'igm-academy-manager' ); ?>
                            </div>
                        </div>

                        <!-- CSV Format Info -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-table"></i>
                                <?php _e( 'Required CSV Columns', 'igm-academy-manager' ); ?>
                            </h6>
                            <p class="mb-2"><?php _e( 'Your CSV file should contain the following columns in order:', 'igm-academy-manager' ); ?></p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th><?php _e( 'Column Name', 'igm-academy-manager' ); ?></th>
                                            <th><?php _e( 'Required', 'igm-academy-manager' ); ?></th>
                                            <th><?php _e( 'Example', 'igm-academy-manager' ); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>First Name</td>
                                            <td><span class="badge bg-danger">Yes</span></td>
                                            <td>Juan</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Last Name</td>
                                            <td><span class="badge bg-danger">Yes</span></td>
                                            <td>García</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Email</td>
                                            <td><span class="badge bg-danger">Yes</span></td>
                                            <td>juan@example.com</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Phone</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>+34 123 456 789</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Gender</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>male / female</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Birth Date</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>10 de July de 1976</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Notes</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>Any notes...</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Last Class</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>2025-01-15</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Total Classes</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>20</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>Pending Classes</td>
                                            <td><span class="badge bg-secondary">No</span></td>
                                            <td>5</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" name="igm_import_submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i>
                                <?php _e( 'Import Students', 'igm-academy-manager' ); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Info -->
        <div class="col-lg-4">
            <!-- Import Tips Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <?php _e( 'Import Tips', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <?php _e( 'Existing students (matched by email) will be updated', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <?php _e( 'New students will be added to the database', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <?php _e( 'WordPress user accounts are created automatically', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <?php _e( 'Spanish date formats are supported', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                            <?php _e( 'Make sure your CSV is properly formatted', 'igm-academy-manager' ); ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                            <?php _e( 'Use semicolon (;) as column separator', 'igm-academy-manager' ); ?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Exercise Import (Phase 3) Card -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-excel text-success"></i>
                        <?php _e( 'Import Exercises', 'igm-academy-manager' ); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3;"></i>
                        <h6 class="mt-3 mb-2"><?php _e( 'Coming in Phase 3', 'igm-academy-manager' ); ?></h6>
                        <p class="text-muted small mb-0">
                            <?php _e( 'You will be able to import tennis and padel exercises from Excel files', 'igm-academy-manager' ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Example CSV Section -->
    <div class="card mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-file-code"></i>
                <?php _e( 'Example CSV Format', 'igm-academy-manager' ); ?>
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <?php _e( 'Here\'s an example of how your CSV file should look:', 'igm-academy-manager' ); ?>
            </p>
            <pre class="bg-light p-3 rounded"><code>First Name;Last Name;Email;Phone;Gender;Birth Date;Notes;Last Class;Total Classes;Pending Classes
Juan;García;juan@example.com;+34123456789;male;10 de July de 1976;Notes here;2025-01-15;20;5
María;López;maria@example.com;+34987654321;female;15 de March de 1985;More notes;2025-01-10;15;3</code></pre>
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle"></i>
                <strong><?php _e( 'Important:', 'igm-academy-manager' ); ?></strong>
                <?php _e( 'The first row should contain the column headers exactly as shown above.', 'igm-academy-manager' ); ?>
            </div>
        </div>
    </div>
</div>
