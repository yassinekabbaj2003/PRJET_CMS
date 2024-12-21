<?php

namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Create customizer controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
final class Search extends Base_Controller {
    /**
     * Construct the class.
     *
     * @since 1.4.4
     */
    public function hooks() {
        $this->search_init();

        $this->search_admin_init();
    }

    /**
     * Require admin part.
     *
     * @since   2.2.3
     * @version 1.0.0
     */
    function search_admin_init() {
        require_once( ET_AJAX_SEARCH_DIR . 'search/admin/admin-menu.php' );
    }

    /**
     * Initiate customizer options.
     *
     * @since 3.2.5
     */
    public function search_init() {
        // Run builder
        $this->get_model()->_run();
    }

    public function getOption($key = '', $default_value = false) {
        $settings = $this->get_model()->settings;
        return isset($settings[$key]) ? $settings[$key] : $default_value;
    }
}