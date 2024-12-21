<?php

namespace ETC\App\Models\Search;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Helpers
{
    /**
     * Logger instance
     *
     * @var \WC_Logger
     */
    public static $log = false;

    /**
     * Get icon (SVG)
     *
     * @return string
     */
    public static function get_icon_svg($name, $class = '', $color = '')
    {
        $svg = '';
        ob_start();
        switch ($name) {
            case 'face-smile':
                ?>
                <svg class="<?php
                echo $class;
                ?>" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                    <path d="M23.689 19.5c-0.294 0.366-3.003 3.566-7.689 3.566-4.784 0-7.572-3.421-7.688-3.566-0.044-0.056-0.064-0.126-0.057-0.197s0.044-0.135 0.1-0.18l0.64-0.506c0.049-0.038 0.107-0.057 0.165-0.057 0.074 0 0.148 0.031 0.201 0.092 1.705 1.958 4.125 3.081 6.639 3.081s4.932-1.122 6.639-3.082c0.094-0.108 0.254-0.12 0.366-0.034l0.641 0.506c0.056 0.044 0.091 0.109 0.1 0.18 0.008 0.070-0.013 0.142-0.057 0.197zM27.314 4.686c-3.022-3.022-7.040-4.686-11.314-4.686s-8.292 1.664-11.314 4.686c-3.022 3.022-4.686 7.040-4.686 11.314s1.664 8.292 4.686 11.314c3.022 3.022 7.040 4.686 11.314 4.686s8.292-1.664 11.314-4.686c3.022-3.022 4.686-7.040 4.686-11.314s-1.664-8.292-4.686-11.314zM16 30.583c-8.041 0-14.583-6.542-14.583-14.583s6.542-14.583 14.583-14.583 14.583 6.542 14.583 14.583-6.542 14.583-14.583 14.583zM11.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2zM22.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2z"></path>
                </svg>
                <?php
                break;
            case 'face-neutral':
                ?>
                <svg class="<?php
                echo $class;
                ?>" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                    <path d="M27.314 4.686c-3.022-3.022-7.040-4.686-11.314-4.686s-8.292 1.664-11.314 4.686c-3.022 3.022-4.686 7.040-4.686 11.314s1.664 8.292 4.686 11.314c3.022 3.022 7.040 4.686 11.314 4.686s8.292-1.664 11.314-4.686c3.022-3.022 4.686-7.040 4.686-11.314s-1.664-8.292-4.686-11.314zM16 30.583c-8.041 0-14.583-6.542-14.583-14.583s6.542-14.583 14.583-14.583 14.583 6.542 14.583 14.583-6.542 14.583-14.583 14.583zM11.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2zM22.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2zM23.204 21.927h-14.409c-0.147 0-0.267-0.119-0.267-0.267v-0.8c0-0.147 0.119-0.267 0.267-0.267h14.409c0.147 0 0.267 0.119 0.267 0.267v0.8c0 0.147-0.119 0.267-0.267 0.267z"></path>
                </svg>
                <?php
                break;
            case 'face-sad':
                ?>
                <svg class="<?php
                echo $class;
                ?>" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                    <path d="M23.689 22.126c-0.294-0.366-3.003-3.566-7.689-3.566-4.784 0-7.572 3.421-7.688 3.566-0.044 0.056-0.064 0.126-0.057 0.197s0.044 0.135 0.1 0.18l0.64 0.506c0.049 0.038 0.107 0.057 0.165 0.057 0.074 0 0.148-0.031 0.201-0.092 1.705-1.958 4.125-3.081 6.639-3.081s4.932 1.122 6.639 3.082c0.094 0.108 0.254 0.12 0.366 0.034l0.641-0.506c0.056-0.044 0.091-0.109 0.1-0.18 0.008-0.070-0.013-0.142-0.057-0.197zM27.314 4.686c-3.022-3.022-7.040-4.686-11.314-4.686s-8.292 1.664-11.314 4.686c-3.022 3.022-4.686 7.040-4.686 11.314s1.664 8.292 4.686 11.314c3.022 3.022 7.040 4.686 11.314 4.686s8.292-1.664 11.314-4.686c3.022-3.022 4.686-7.040 4.686-11.314s-1.664-8.292-4.686-11.314zM16 30.583c-8.041 0-14.583-6.542-14.583-14.583s6.542-14.583 14.583-14.583 14.583 6.542 14.583 14.583-6.542 14.583-14.583 14.583zM11.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2zM22.7 11.258c0 0.663-0.537 1.2-1.2 1.2s-1.2-0.537-1.2-1.2c0-0.663 0.537-1.2 1.2-1.2s1.2 0.537 1.2 1.2z"></path>
                </svg>
                <?php
                break;
        }
        $svg .= ob_get_clean();
        return apply_filters(
            'etheme_ajax_search_stats/icon',
            $svg,
            $name,
            $class,
            $color
        );
    }

    /**
     * Check if table exist
     *
     * @return bool
     */
    public static function db_table_exists($tableName)
    {
        global $wpdb;
        $exist = false;
        $wpdb->hide_errors();
        if (empty($tableName)) {
            return false;
        }
        $sql = $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->prefix . 'et_ajax_search_%');
        $result = $wpdb->get_col($sql);
        if (is_array($result) && in_array($tableName, $result)) {
            $exist = true;
        }
        return $exist;
    }

    /**
     * Check if this is a product search page
     *
     * @return bool
     */
    public static function is_product_search_page()
    {
        if (isset($_GET['et_search']) && isset($_GET['post_type']) && $_GET['post_type'] === 'product' && isset($_GET['s'])) {
            return true;
        }
        return false;
    }

    /**
     * Get default collate
     *
     * @param string $context
     *
     * @return string
     */
    public static function get_collate($context = '')
    {
        global $wpdb;
        $sql = '';
        $collate = '';
        $charset = '';
        if ($wpdb->has_cap('collation')) {
            if (!empty($wpdb->charset)) {
                $charset = $wpdb->charset;
            }
            if (!empty($wpdb->collate)) {
                $collate = $wpdb->collate;
            }
        }
        $charset = apply_filters('etheme_ajax_search_stats/db/charset', $charset, $context);
        $collate = apply_filters('etheme_ajax_search_stats/db/collation', $collate, $context);
        if (!empty($charset)) {
            $sql .= " DEFAULT CHARACTER SET " . $charset;
        }
        if (!empty($collate)) {
            $sql .= " COLLATE " . $collate;
        }
        return apply_filters('etheme_ajax_search_stats/db/collation/sql', $sql, $context);
    }

    /**
     * Check if is settings page
     * @return bool
     */
    public static function is_analytics_page() {
        if ( is_admin() && !empty( $_GET['page'] ) && $_GET['page'] === 'et-panel-search-stats' ) {
            return true;
        }
        return false;
    }

    /**
     * Get table info
     *
     * @return float[]
     */
    public static function get_table_info($table = '')
    {
        global $wpdb;
        if (!defined('DB_NAME') || empty($table)) {
            return array(
                'data' => 0.0,
                'index' => 0.0,
            );
        }
        $info = $wpdb->get_row($wpdb->prepare("SELECT\n\t\t\t\t\t    round( ( data_length / 1024 / 1024 ), 2 ) 'data',\n\t\t\t\t\t    round( ( index_length / 1024 / 1024 ), 2 ) 'index'\n\t\t\t\t\tFROM information_schema.TABLES\n\t\t\t\t\tWHERE table_schema = %s\n\t\t\t\t\tAND table_name = %s;", DB_NAME, $table), ARRAY_A);
        if (!isset($info['data']) || !isset($info['index'])) {
            return array(
                'data' => 0.0,
                'index' => 0.0,
            );
        }
        $info['data'] = floatval($info['data']);
        $info['index'] = floatval($info['index']);
        return $info;
    }

    /**
     * Does the "Shop manager" role have access to the analytics settings
     *
     * @return bool
     */
    public static function shop_manager_has_access()
    {
        return defined('ET_AJAX_SEARCH_ALLOW_SHOP_MANAGER_ACCESS') && ET_AJAX_SEARCH_ALLOW_SHOP_MANAGER_ACCESS;
    }

    public static function allow_export_cvv()
    {
        return get_option('etheme_ajax_search_analytics_export_cvv', false);
    }

}
