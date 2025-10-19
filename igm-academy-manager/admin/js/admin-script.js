/**
 * Admin JavaScript
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/admin/js
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // Auto-dismiss notices after 5 seconds
        setTimeout(function() {
            $('.notice.is-dismissible').fadeOut();
        }, 5000);

        // Confirm delete actions
        $('.button-link-delete').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });

        // Search box focus
        $('input[type="search"]').on('focus', function() {
            $(this).select();
        });

    });

})(jQuery);
