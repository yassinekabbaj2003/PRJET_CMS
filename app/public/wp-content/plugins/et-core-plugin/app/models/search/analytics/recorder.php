<?php

namespace ETC\App\Models\Search\Analytics;

use ETC\App\Models\Search\Helpers;
use ETC\App\Models\Search\Multilingual;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Recorder {
    public function listen() {
        $instance = Analytics::get_instance();
        $module_enabled = $instance->is_module_active();
        if ( !$module_enabled ) return;

        Database::register_db_tables();
        add_action(
            'etheme_ajax_search_stats/analytics/after_searching',
            array($this, 'listener'),
            10,
            4
        );
    }

    /**
     * Validate input data and save them to the index
     *
     * @param string $phrase
     * @param int $hits
     * @param string $post_type
     * @param string $lang
     *
     * @return void
     */
    public function listener( $phrase, $hits, $post_type, $lang ) {
        $autocomplete = true;
        // Break early the search phrase is empty or has a specific shape
        if ( empty( $phrase ) || !is_string( $phrase ) ) {
            return;
        }
        if ( !in_array($post_type, apply_filters('etheme_ajax_search_stats/analytics/search_in_post_types', array('product')))) {
            return;
        }
        // write all found/non_found searches
        if ( !is_numeric( $hits ) || $hits < 0 ) {
            return;
        }
        // Save only critical searches.
        if ( defined( 'ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL' ) && ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL && $hits > 0 ) {
            return;
        }
        // Allow to exclude critical phrases.
        if ( $hits === 0 ) {
            $excludedPhrases = apply_filters( 'etheme_ajax_search_stats/analytics/excluded_critical_phrases', array() );
            if ( is_array( $excludedPhrases ) && in_array( $phrase, $excludedPhrases, true ) ) {
                return;
            }
        }
        // Break early when a user has specific roles.
        $roles = apply_filters( 'etheme_ajax_search_stats/analytics/exclude_roles', array() );
        if ( !empty( $roles ) ) {
            foreach ( $roles as $role ) {
                if ( current_user_can( $role ) ) {
                    return;
                }
            }
        }
        if ( Helpers::is_product_search_page() ) {
            $autocomplete = false;
        }
        $phrase = strtolower( substr( $phrase, 0, 255 ) );
        $lang = ( !empty( $lang ) && Multilingual::check_language_code( $lang ) ? $lang : '' );
        $this->push(
            $phrase,
            $hits,
            $autocomplete,
            $post_type,
            $lang
        );
    }

    /**
     * Push a record to the index.
     *
     * @param string $phrase
     * @param int $hits
     * @param bool $autocomplete
     * @param string $post_type
     * @param string $lang
     *
     * @return void
     */
    public function push(
        $phrase,
        $hits,
        $autocomplete,
        $post_type,
        $lang
    ) {
        global $wpdb;
        $data = array(
            'phrase'       => $phrase,
            'hits'         => $hits,
            'created_at'   => date( 'Y-m-d H:i:s', current_time( 'timestamp', true ) ),
            'post_type' => $post_type,
            'autocomplete' => $autocomplete,
        );
        $format = array(
            '%s',
            '%d',
            '%s',
            '%s',
            '%d'
        );
        if ( !empty( $lang ) ) {
            $data['lang'] = $lang;
            $format[] = '%s';
        }
        $wpdb->insert( $wpdb->et_ajax_search_stats, $data, $format );
    }

}
