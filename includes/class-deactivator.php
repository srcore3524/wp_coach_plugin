<?php
/**
 * Fired during plugin deactivation
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/includes
 */

class IGM_Academy_Deactivator {

    /**
     * Plugin deactivation handler
     *
     * Flush rewrite rules and clean up temporary data.
     * Note: Database tables are NOT dropped on deactivation (only on uninstall).
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Clean up any transients
        delete_transient( 'igm_academy_cache' );
    }
}
