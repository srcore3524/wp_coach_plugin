<?php
/**
 * Define the internationalization functionality
 *
 * @package    IGM_Academy
 * @subpackage IGM_Academy/includes
 */

class IGM_Academy_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'igm-academy-manager',
            false,
            dirname( IGM_ACADEMY_PLUGIN_BASENAME ) . '/languages/'
        );
    }
}
