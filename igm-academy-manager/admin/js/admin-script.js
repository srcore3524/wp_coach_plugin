/**
 * IGM Academy Manager - Admin JavaScript
 * Uses Bootstrap 5 + DataTables + jQuery
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/js
 * @version    1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize DataTables
     */
    function initDataTables() {
        // Check if DataTable is available
        if (typeof $.fn.DataTable !== 'function') {
            return;
        }

        // Students table
        if ($('#students-table').length) {
            $('#students-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'asc']], // Sort by last name
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ estudiantes",
                    infoEmpty: "No hay estudiantes",
                    infoFiltered: "(filtrado de _MAX_ total)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    emptyTable: "No hay estudiantes registrados"
                },
                columnDefs: [
                    { orderable: false, targets: -1 } // Disable sorting on actions column
                ]
            });
        }

        // Coaches table
        if ($('#coaches-table').length) {
            $('#coaches-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'asc']], // Sort by last name
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entrenadores",
                    infoEmpty: "No hay entrenadores",
                    infoFiltered: "(filtrado de _MAX_ total)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    emptyTable: "No hay entrenadores registrados"
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            });
        }

        // Groups table
        if ($('#groups-table').length) {
            $('#groups-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ grupos",
                    infoEmpty: "No hay grupos",
                    infoFiltered: "(filtrado de _MAX_ total)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    emptyTable: "No hay grupos registrados"
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            });
        }
    }

    /**
     * Initialize Bootstrap Tooltips
     */
    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    /**
     * Initialize Bootstrap Popovers
     */
    function initPopovers() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    /**
     * Confirm delete actions
     */
    function initDeleteConfirmation() {
        $('.delete-confirm').on('click', function(e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este registro? Esta acción no se puede deshacer.')) {
                e.preventDefault();
                return false;
            }
        });
    }

    /**
     * Form validation
     */
    function initFormValidation() {
        // Add Bootstrap validation classes
        $('.igm-form').on('submit', function(e) {
            var form = $(this)[0];

            if (form.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(this).addClass('was-validated');
        });
    }

    /**
     * File upload preview
     */
    function initFileUpload() {
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    }

    /**
     * Auto-hide alerts/notices after 5 seconds
     */
    function initAutoHideNotices() {
        setTimeout(function() {
            $('.alert-dismissible').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Initialize all on document ready
     */
    $(document).ready(function() {
        initDataTables();
        initTooltips();
        initPopovers();
        initDeleteConfirmation();
        initFormValidation();
        initFileUpload();
        initAutoHideNotices();

        // Log initialization
        console.log('IGM Academy Manager: Admin scripts loaded successfully');
    });

    /**
     * AJAX Helper Functions
     */
    window.igmAjax = {
        /**
         * Generic AJAX request
         */
        request: function(action, data, successCallback, errorCallback) {
            $.ajax({
                url: igmAcademy.ajaxUrl,
                type: 'POST',
                data: $.extend({
                    action: action,
                    nonce: igmAcademy.nonce
                }, data),
                success: function(response) {
                    if (response.success && typeof successCallback === 'function') {
                        successCallback(response.data);
                    } else if (!response.success && typeof errorCallback === 'function') {
                        errorCallback(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof errorCallback === 'function') {
                        errorCallback({
                            message: 'Error: ' + error,
                            status: status
                        });
                    }
                }
            });
        }
    };

})(jQuery);
