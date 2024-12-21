<?php

namespace ETC\App\Models\Search\Analytics;

use ETC\App\Models\Search;
use ETC\App\Models\Search\Helpers;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Analytics
{
    /**
     * A reference to an instance of this class.
     *
     * @since 5.4.0
     */
    private static $instance = null;

    public static $ui = null;
    public $maintenance = null;

    public function maybe_init_user_interface()
    {
        if (null == self::$ui) {
            // Load user interface
            self::$ui = new User_Interface($this);
        }
    }


    public function init()
    {

        add_action('wp_ajax_et_ajax_search_analytics_switch', array($this, 'module_switcher'));
        add_action('wp_ajax_nopriv_et_ajax_search_analytics_switch', array($this, 'module_switcher'));

        if (!$this->is_module_active()) {
            return;
        }

        if (is_admin()) {

            $this->maybe_init_user_interface();
            self::$ui->init();
        }

        // Database
        Database::register_db_tables();
        $this->maybe_install_db();

        // Maintenance.
        $this->maintenance = new Maintenance();
        if ($this->is_module_active()) {
            $this->maintenance->init();
        } else {
            $this->maintenance->unschedule();
        }
    }

    public function module_switcher()
    {
        $_POST['value'] = $_POST['value'] == 'false' ? false : true;
        update_option('etheme_ajax_search_analytics', $_POST['value']);
        die();
    }

    /**
     * Check if the Analytics module is enabled
     *
     * @return bool
     */
    public function is_module_active()
    {
        return get_option('etheme_ajax_search_analytics', false);
    }

    /**
     * Create the database table if necessary
     *
     * @return void
     */
    public function maybe_install_db()
    {

        // Try to create tables when Search Analytics module is created, but from some reasons the table wasn't created
        if (Helpers::is_analytics_page()
            && $this->is_module_active()
            && !Database::exist()
        ) {
            Database::maybe_install();
        }
    }

    public function get_default_date_range()
    {
        $data = new Data();
        return $data->get_default_date_range(false);
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  5.4.0
     */
    public static function get_instance($shortcodes = array())
    {

        if (null == self::$instance) {
            self::$instance = new self($shortcodes);
        }

        return self::$instance;
    }

}
