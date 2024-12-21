<?php

namespace ETC\App\Models\Search\Analytics;

if (!defined('ABSPATH')) {
    exit;
}

class Maintenance
{
    const HOOK = 'et_ajax_search_analytics_maintenance';
    const ANALYTICS_EXPIRATION_IN_DAYS = 30;

    public function init()
    {
        $this->schedule();
        $this->listen_wp_cron();
    }

    /**
     * Listen to cron action
     *
     * @return void
     */
    public function listen_wp_cron()
    {
        add_action(self::HOOK, [$this, 'handle_maintenance']);
    }

    /**
     * Schedule maintenance task
     *
     * @return void
     */
    public function schedule()
    {
        if (!wp_next_scheduled(self::HOOK)) {
            wp_schedule_event(strtotime('tomorrow') + 2 * HOUR_IN_SECONDS, 'daily', self::HOOK);
        }
    }

    /**
     * Unschedule maintenance task
     *
     * @return void
     */
    public function unschedule()
    {
        $timestamp = wp_next_scheduled(self::HOOK);
        if ($timestamp) {
            wp_unschedule_event($timestamp, self::HOOK);
        }
    }

    /**
     * Handle maintenance task
     *
     * @return void
     */
    public function handle_maintenance()
    {
        $expiration = self::ANALYTICS_EXPIRATION_IN_DAYS;

        if (
            defined('ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS') &&
            intval(ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS) > 0
        ) {
            $expiration = intval(ET_AJAX_SEARCH_ANALYTICS_EXPIRATION_IN_DAYS);
        }

        Database::wipe_old_records($expiration);
    }
}
