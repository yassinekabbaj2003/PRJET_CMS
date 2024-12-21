<?php

namespace ETC\App\Models\Search\Analytics;

use ETC\App\Models\Search\Multilingual;

if (!defined('ABSPATH')) {
    exit;
}

class Data
{

    /**
     * @var string
     */
    private $format = 'Y-m-d H:i:s';

    /**
     * Date start in format Y-m-d H:i:s
     * @var string
     */
    private $dateFrom;

    /**
     * Date end in format Y-m-d H:i:s
     * @var string
     */
    private $dateTo;

    /**
     * Available values: 'autocomplete', 'search-results-page'
     * @var string
     */
    private $context;

    /**
     * Language
     * @var string
     */
    private $lang = '';

    /**
     * Minimum number of phrase repetitions which must occur to be recognized as critical
     * @var int
     */
    private $minCriticalRep = 3;

    /**
     * Percentage limit of searches returning results.
     * Above this limit searches will be marked as satisfying
     * Below this limit searches will be marked as not satisfying
     * @var int
     */
    private $searchesReturningResutlsGoodPercent = 70;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->set_default_date_range();
        $this->set_default_min_critical_rep();
    }

    public function get_default_date_range($formated = true)
    {
        $all_values = User_Interface::get_date_range_values();
        $date_from_default = 'month';
        $date_from = 'today - 30 days';
        if (isset($_GET['date-from'])) {
            $date_from_default = $_GET['date-from'];
            switch ($_GET['date-from']) {
                case 'today':
                    $date_from = 'today - 1 day';
                    break;
                case 'week':
                    $date_from = 'today - 1 week';
                    break;
                case 'month':
                    $date_from = 'today - 1 month';
                    break;
                case 'quarter':
                    $date_from = 'today - 3 month';
                    break;
                case 'year':
                    $date_from = 'today - 1 year';
                    break;
            }
        }
        return (!$formated && isset($all_values[$date_from_default])) ? array('key' => $date_from_default, 'value' => $all_values[$date_from_default]) : $date_from;
    }

    /**
     * Set default data range - last 30 days
     *
     * @return void
     */
    public function set_default_date_range()
    {
        $this->dateFrom = date($this->format, strtotime($this->get_default_date_range()));
        $this->dateTo = date($this->format);
    }

    public function get_default_min_critical_rep($custom_count = false)
    {
        if ($custom_count !== false)
            return $custom_count;

        $minCriticalRep = $this->minCriticalRep;
        return isset($_GET['min-critical-rep']) ? $_GET['min-critical-rep'] : $minCriticalRep;
    }

    public function set_default_min_critical_rep($custom_count = false)
    {
        $this->minCriticalRep = $this->get_default_min_critical_rep($custom_count);
    }

    /**
     * Set language
     *
     * @param string $lang
     *
     * @return void
     */
    public function set_language($lang)
    {
        if (Multilingual::is_multilingual() && !empty($lang)) {
            $this->lang = Multilingual::get_default_language();
        }

        if (Multilingual::check_language_code($lang)) {
            $this->lang = $lang;
        }
    }

    /**
     * Set context
     *
     * @param string $context | Available values: 'autocomplete', 'search-results-page'
     *
     * @return void
     */
    public function set_context($context)
    {
        if (!in_array($context, array('autocomplete', 'search-results-page'))) {
            $context = 'autocomplete';
        }

        $this->context = $context;
    }

    /**
     * Get phrases with search results
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function get_phrases_with_results($limit = 10, $offset = 0)
    {
        return $this->get_phrases($this->dateFrom, $this->dateTo, $this->context, true, null, $limit, $offset);
    }

    /**
     * Get total searches
     *
     * @param bool $hasResults
     * @param bool $unique - count only unique values
     *
     * @return int
     */
    public function get_total_searches($hasResults, $unique = false)
    {
        global $wpdb;
        $total = 0;
        $select = 'COUNT(id)';
        $where = '';

        if ($unique) {
            $select = 'COUNT(DISTINCT phrase)';
        }

        // Context
        if ($this->context === 'autocomplete') {
            $where .= " AND autocomplete = 1";
        } else {
            $where .= " AND autocomplete = 0";
        }

        //With results or with no results
        if ($hasResults) {
            $where .= " AND hits > 0";
        } else {
            $where .= " AND hits = 0";
        }

        // Language
        $where .= $this->get_language_sql();

        $sql = $wpdb->prepare("SELECT $select
                                     FROM $wpdb->et_ajax_search_stats
                                     WHERE 1=1
                                     $where
                                     AND created_at > %s AND created_at < %s", $this->dateFrom, $this->dateTo);

        $res = $wpdb->get_var($sql);
        if (!empty($res) && is_numeric($res)) {
            $total = absint($res);
        }

        return $total;
    }

    /**
     * Get SQL where clause related to language
     *
     * @param string $lang
     *
     * @return string
     */
    public function get_language_sql($lang = '')
    {
        global $wpdb;

        $where = '';

        if (Multilingual::is_multilingual()) {
            if (empty($lang)) {
                $lang = $this->lang;
            }

            if (Multilingual::get_default_language() === $lang) {
                $where .= $wpdb->prepare(" AND (lang = %s OR lang IS NULL)", $lang);
            } else {
                $where .= $wpdb->prepare(" AND lang = %s", $lang);
            }
        }

        return $where;
    }

    /**
     * Get total critical searches
     *
     * @return int
     */
    public function get_total_critical_searches()
    {
        global $wpdb;

        $total = 0;
        $where = '';

        // Language
        $where .= $this->get_language_sql();

        $sql = $wpdb->prepare("SELECT COUNT(*) AS total FROM (
                                     SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->et_ajax_search_stats
                                     WHERE 1=1
                                     AND created_at > %s AND created_at < %s
                                     AND autocomplete = 1
                                     AND solved = 0
                                     AND hits = 0
                                     $where
                                     GROUP BY phrase
                                     HAVING qty >= %d) AS total", $this->dateFrom, $this->dateTo, $this->minCriticalRep);

        $res = $wpdb->get_var($sql);
        if (!empty($res) && is_numeric($res)) {
            $total = absint($res);
        }

        return $total;
    }

    /**
     * Get critical searches
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function get_critical_searches($limit = 10, $offset = 0)
    {
        global $wpdb;
        $phrases = array();
        $where = '';

        // Language
        $where .= $this->get_language_sql();

        $sql = $wpdb->prepare("SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->et_ajax_search_stats
                                     WHERE hits = 0
                                     AND created_at > %s AND created_at < %s
                                     AND autocomplete = 1
                                     AND solved = 0
                                     $where
                                     GROUP BY phrase
                                     HAVING qty >= %d
                                     ORDER BY qty DESC, phrase ASC LIMIT %d,%d", $this->dateFrom, $this->dateTo, $this->minCriticalRep, $offset, $limit);

        $res = $wpdb->get_results($sql, ARRAY_A);
        if (!empty($res) && is_array($res)) {
            $phrases = $res;

            foreach ($res as $key => $search) {
                $phrases[$key] = $search;
            }
        }

        return $phrases;
    }


    /**
     *
     * Get search phrases with the frequency of occurrences
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $context
     * @param bool $hasResults
     * @param bool $solved
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function get_phrases($dateFrom, $dateTo, $context, $hasResults, $solved = null, $limit = 10, $offset = 0)
    {
        global $wpdb;

        $output = array();
        $where = '';

        // Context
        if ($context === 'autocomplete') {
            $where .= " AND autocomplete = 1";
        } else {
            $where .= " AND autocomplete = 0";
        }

        //With results or with no results
        if ($hasResults) {
            $where .= " AND hits > 0";
        } else {
            $where .= " AND hits = 0";
        }

        if (is_bool($solved)) {
            if ($solved) {
                $where .= " AND solved = 1";
            } else {
                $where .= " AND solved = 0";
            }
        }

        // Language
        $where .= $this->get_language_sql();

        $sql = $wpdb->prepare("SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->et_ajax_search_stats
                                     WHERE 1=1
                                     AND created_at > %s AND created_at < %s
                                     $where
                                     GROUP BY phrase
                                     ORDER BY qty DESC, phrase ASC LIMIT %d,%d", $dateFrom, $dateTo, $offset, $limit);

        $res = $wpdb->get_results($sql, ARRAY_A);
        if (!empty($res) && is_array($res)) {
            $output = $res;
        }

        return $output;
    }

    /**
     * Mark as solved. Exclude the phrase from critical phrases module.
     *
     * @return bool
     */
    function mark_search_as_solved($phrase)
    {
        global $wpdb;
        $success = false;

        $data = array(
            'solved' => 1
        );

        $where = array(
            'phrase' => $phrase
        );

        $format = array('%s');

        if (!empty($this->lang)) {
            $where['lang'] = $this->lang;
            $format[] = '%s';
        }

        if ($wpdb->update($wpdb->et_ajax_search_stats, $data, $where, $format)) {
            $success = true;
        }

        return $success;
    }

    /**
     * Check if the date has properly format
     *
     * @return bool
     */
    function validate_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /**
     * Check if the date has properly format
     *
     * @param int $percentage
     *
     * @return bool
     */
    function is_searches_returning_results_satisfying($percentage)
    {
        return $percentage >= $this->searchesReturningResutlsGoodPercent;
    }
}
