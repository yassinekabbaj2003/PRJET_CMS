<?php

namespace ETC\App\Models\Search\Analytics;

use ETC\App\Models\Search\Helpers;
use ETC\App\Models\Search\Multilingual;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class User_Interface
{

    const LOAD_MORE_CRITICAL_SEARCHES_NONCE = 'analytics-load-more-critical-searches';

    const LOAD_MORE_AUTOCOMPLETE_NONCE = 'analytics-load-more-autocomplete';

    const RESET_STATS_NONCE = 'analytics-reset-stats';

    const EXPORT_STATS_CSV_NONCE = 'analytics-export-stats-csv';

    const CRITICAL_SEARCHES_LOAD_LIMIT = 10;

    const SEARCHES_LOAD_LIMIT = 10;

    /**
     * @var Analytics
     */
    private $analytics;

    /**
     * Constructor
     *
     * @param Analytics $analytics
     */
    public function __construct(Analytics $analytics)
    {
        $this->analytics = $analytics;
    }

    public static function get_date_range_values()
    {
        return array(
            'today' => __('Past Day', 'xstore-core'),
            'week' => __('Past Week', 'xstore-core'),
            'month' => __('Past Month', 'xstore-core'),
//            'quarter' => __( 'Past Quarter', 'xstore-core' ),
//            'year' => __( 'Past Year', 'xstore-core' ),
        );
    }

    /**
     * Init the class
     *
     * @return void
     */
    public function init()
    {

        add_action('wp_ajax_et_ajax_search_load_more_critical_searches', array($this, 'load_more_critical_searches'));
        add_action('wp_ajax_et_ajax_search_load_more_autocomplete', array($this, 'load_more_autocomplete_searches'));

        add_action('wp_ajax_et_ajax_search_check_critical_phrase', array($this, 'checkCriticalPhrase'));
        add_action('wp_ajax_et_ajax_search_exclude_critical_phrase', array($this, 'excludeCriticalPhrase'));

        add_action('wp_ajax_et_ajax_search_reset_stats', array($this, 'reset_stats'));
        add_action('wp_ajax_et_ajax_search_export_stats_csv', array($this, 'export_stats'));
    }

    /**
     * Get HTML of language switcher
     *
     * @return string
     */
    private function get_language_select()
    {
        $vars = array(
            'multilingual' => array(
                'is-multilingual' => true,
                'current-lang' => Multilingual::get_current_language(),
                'langs' => array(),
            ),
        );
        foreach (Multilingual::get_languages() as $lang) {
            $vars['multilingual']['langs'][$lang] = Multilingual::get_language_field($lang, 'name');
        }
        ob_start();
        require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/langs.php';
        return ob_get_clean();
    }

    /**
     * Get HTML of date range select
     *
     * @return string
     */
    private function get_date_range_select()
    {
        $vars = array(
            'date-range' => array(
                'current-range' => isset($_GET['date-from']) ? $_GET['date-from'] : 'month',
                'ranges' => self::get_date_range_values()
            ),
        );
        ob_start();
        require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/date-range.php';
        return ob_get_clean();
    }

    /**
     * Load the analytics page
     *
     * @return void
     */
    public function analytics_page()
    {
        $vars = $this->get_stats_vars(isset($_GET['lang']) ? $_GET['lang'] : Multilingual::get_current_language());

        if (Multilingual::is_multilingual()) {
            $vars['lang-html'] = $this->get_language_select();
        }
        $vars['date-range-html'] = $this->get_date_range_select();
        require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/stats.php';
    }

    /**
     * Load more critical searches
     *
     * @return void
     */
    public function load_more_critical_searches()
    {
        if (!current_user_can((Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options'))) {
            wp_die(-1, 403);
        }
        check_ajax_referer(self::LOAD_MORE_CRITICAL_SEARCHES_NONCE);
        $lang = (!empty($_REQUEST['lang']) && Multilingual::check_language_code(sanitize_key($_REQUEST['lang'])) ? sanitize_key($_REQUEST['lang']) : '');
        $offset = (!empty($_REQUEST['loaded']) ? absint($_REQUEST['loaded']) : 0);
        $minCriticalRep = (isset($_REQUEST['minCriticalRep']) ? absint($_REQUEST['minCriticalRep']) : false);
        $html = '';
        $data = new Data();
        if (!empty($lang)) {
            $data->set_language($lang);
        }
        if ($minCriticalRep !== false)
            $data->set_default_min_critical_rep($minCriticalRep);
        $total = $data->get_total_critical_searches();
        $critical = $data->get_critical_searches(100, $offset);
        if (!empty($critical)) {
            ob_start();
            $i = $offset + 1;
            foreach ($critical as $row) {
                require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/critical-searches-row.php';
                $i++;
            }
            $html = ob_get_clean();
        }
        $toLoad = $total - $offset - count($critical);
        $more = min(100, $toLoad);
        $data = array(
            'html' => $html,
            'more' => $more,
        );
        wp_send_json_success($data);
    }

    /**
     * Load more autocomplete searches with results
     *
     * @return void
     */
    public function load_more_autocomplete_searches()
    {
        if (!current_user_can((Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options'))) {
            wp_die(-1, 403);
        }
        check_ajax_referer(self::LOAD_MORE_AUTOCOMPLETE_NONCE);
        $lang = (!empty($_REQUEST['lang']) && Multilingual::check_language_code(sanitize_key($_REQUEST['lang'])) ? sanitize_key($_REQUEST['lang']) : '');
        // Autocomplete
        $data = new Data();
        if (!empty($lang)) {
            $data->set_language($lang);
        }
        $data->set_context('autocomplete');
        $phrases = $data->get_phrases_with_results(100);
        ob_start();
        $i = 1;
        foreach ($phrases as $row) {
            require ET_AJAX_SEARCH_DIR . 'search/partials/admin/stats/ac-searches-row.php';
            $i++;
        }
        $html = ob_get_clean();
        $data = array(
            'html' => $html,
        );
        wp_send_json_success($data);
    }

    /**
     * Reset stats. AJAX callback
     *
     * @return void
     */
    public function reset_stats()
    {
        $response = array(
            'status' => 'error',
            'msg' => '<h4 style="margin-bottom: 15px;">' . esc_html__('Reset error!', 'xstore-core') . '</h4>',
            'icon' => '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/error-icon.png" alt="error icon" style="margin-top: 15px;"><br/><br/>',
        );

        check_ajax_referer(self::RESET_STATS_NONCE);

        if (!current_user_can((Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options'))) {
            $response['msg'] = '<h4 style="margin-bottom: 15px;">' . esc_html__('Unauthorized access', 'xstore-core') . '</h4>';
            return wp_send_json($response);
        }
        Database::wipe_all_records();
        $response['status'] = 'success';
        $response['msg'] = '<h4 style="margin-bottom: 15px;">' . esc_html__('Data was successfully reset!', 'xstore-core') . '</h4>';
        $response['icon'] = '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/success-icon.png" alt="installed icon" style="margin-top: 15px;"><br/><br/>';
        return wp_send_json($response);
    }

    /**
     * Export stats. AJAX callback
     *
     * @return void
     */
    public function export_stats()
    {
        if (!current_user_can((Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options'))) {
            wp_die(-1, 403);
        }
        check_ajax_referer(self::EXPORT_STATS_CSV_NONCE);
        if (!class_exists('WC_CSV_Exporter', false)) {
            require_once WC_ABSPATH . 'includes/export/abstract-wc-csv-exporter.php';
        }
        $exporter = new CSVExporter();
        $context = (isset($_GET['context']) ? sanitize_key($_GET['context']) : '');
        $exporter->set_context($context);
        $lang = (!empty($_REQUEST['lang']) && Multilingual::check_language_code(sanitize_key($_REQUEST['lang'])) ? sanitize_key($_REQUEST['lang']) : '');
        if (!empty($lang)) {
            $exporter->set_lang($lang);
        }
        $exporter->export();
    }

    /**
     * Prepare vars for the view
     *
     * @param string $lang
     *
     * @return array
     */
    private function get_stats_vars($lang = '')
    {
        $data = new Data();
        if (Multilingual::is_multilingual()) {
            $data->set_language($lang);
        }
        $vars = array(
            'days' => $this->get_expiration_in_days(),
            'date-range' => $data->get_default_date_range(false),
            'autocomplete' => array(),
            'critical-searches-min-rep' => $data->get_default_min_critical_rep(),
            'critical-searches' => array(),
            'critical-searches-total' => 0,
            'critical-searches-more' => 0,
            'returning-results-percent' => 0,
            'returning-results-percent-poorly' => false,
            'allow-export-cvv' => Helpers::allow_export_cvv(),
            'table-info' => Helpers::get_table_info(Database::get_table_name()),
        );

        // Ajax Autocomplete
        $data->set_context('autocomplete');
        $vars['autocomplete'] = array(
            'with-results' => $data->get_phrases_with_results(self::SEARCHES_LOAD_LIMIT),
            'total-unique-results' => $data->get_total_searches(true, true),
            'total-unique-no-results' => $data->get_total_searches(false, true),
            'total-with-results' => $data->get_total_searches(true),
            'total-no-results' => $data->get_total_searches(false),
            'total-results' => 0,
        );
        $vars['autocomplete']['total-results-uniq'] = $vars['autocomplete']['total-unique-results'] + $vars['autocomplete']['total-unique-no-results'];
        $vars['autocomplete']['total-results'] = $vars['autocomplete']['total-with-results'] + $vars['autocomplete']['total-no-results'];

        // Common
        $vars['total'] = $vars['autocomplete']['total-results'];
        if ($vars['total'] > 0) {
            $vars['returning-results-percent'] = round($vars['autocomplete']['total-with-results'] * 100 / $vars['total']);
            $vars['returning-results-percent-satisfying'] = $data->is_searches_returning_results_satisfying($vars['returning-results-percent']);
        }

        // Searches with no results
        $critical = $data->get_critical_searches(self::CRITICAL_SEARCHES_LOAD_LIMIT);
        if (!empty($critical)) {
            $vars['critical-searches'] = $critical;
            $vars['critical-searches-total'] = $data->get_total_critical_searches();
            $toLoad = $vars['critical-searches-total'] - count($critical);
            $vars['critical-searches-more'] = min(self::CRITICAL_SEARCHES_LOAD_LIMIT, $toLoad);
            if ($vars['critical-searches-total'] < self::CRITICAL_SEARCHES_LOAD_LIMIT) {
                $vars['critical-searches-more'] = 0;
            }
        }
        return $vars;
    }

    /**
     * The records will be removed from the database after passing X days
     *
     * @return int
     */
    public function get_expiration_in_days()
    {
        $days = Maintenance::ANALYTICS_EXPIRATION_IN_DAYS;
        if (defined('ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS') && intval(ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS) > 0) {
            $days = intval(ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS);
        }
        return $days;
    }

}
