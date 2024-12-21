<?php
namespace ETC\App\Models;

use ETC\App\Models\Base_Model;

/**
 * Create Search setup.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Models
 */

class Search extends Base_Model {
    private static $instance;

    private $tnow;

    /**
     * @var \ETC\App\Models\Search\Settings
     */
    public $settings;

    /**
     * @var \ETC\App\Models\Search\Setup
     */
    public $setup;

    public $multilingual;

    /**
     * @var \ETC\App\Models\Search\EnginesWordPressNative\Search
     */
    public $nativeSearch;

    /**
     * @var \ETC\App\Models\Search\Search
     */
    protected $search;


    /**
     * Constructor
     */
    protected function __construct() {
        self::$instance = $this;
    }

    function _run(){

    }

    public static function get_instance($args = array())
    {

        if ( self::$instance === null ) {
                self::$instance = new Search();
            self::$instance->constants();
            self::$instance->settings = array(
                'analytics_critical_searches_widget_enabled' => false
            );

            self::$instance->multilingual = new \ETC\App\Models\Search\Multilingual();
            self::$instance->nativeSearch = new \ETC\App\Models\Search\Engines\Ajax_Search();
            self::$instance->nativeSearch->actions();

            if (is_admin() || wp_doing_cron()) {
                $analytics = new \ETC\App\Models\Search\Analytics\Analytics();
                $analytics->init();
            }
//            ??? interesting thing new \ETC\App\Models\Search\Integrations\Solver();
        }
        self::$instance->tnow = time();
        return self::$instance;
    }

    /**
     * Setup plugin constants
     *
     * @return void
     */
    private function constants()
    {
        $this->define( 'ET_AJAX_SEARCH_DIR', plugin_dir_path( __FILE__ ) );
        $this->define( 'ET_AJAX_SEARCH_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'ET_AJAX_SEARCH_SETTINGS_KEY', 'et_ajax_search_settings' );
        $this->define( 'ET_AJAX_SEARCH_SEARCH_ACTION', 'etheme_ajax_search' );
        $this->define( 'ET_AJAX_SEARCH_RESULT_DETAILS_ACTION', 'et_ajax_search_result_details' );
//        $this->define( 'ET_AJAX_SEARCH_GET_PRICES_ACTION', 'ET_AJAX_SEARCH_get_prices' );
        $this->define( 'ET_AJAX_SEARCH_WC_AJAX_ENDPOINT', true );
//        $this->define( 'ET_AJAX_SEARCH_VOICE_SEARCH_ENABLE', true );
    }

    /**
     * Define constant if not already set
     *
     * @param  string $name
     * @param  string|bool $value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( !defined( $name ) ) {
            define( $name, $value );
        }
    }
}
