<?php

namespace ETC\App\Models\Search\Analytics;

use ETC\App\Models\Search\Helpers;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Database
{
    const DB_NAME = 'et_ajax_search_stats';
    const DB_VERSION = 1;
    const DB_VERSION_OPTION = 'et_ajax_search_stats_db_version';

    /**
     * Add table names to the $wpdb object
     *
     * @return void
     */
    public static function register_db_tables()
    {
        global $wpdb;

        $wpdb->et_ajax_search_stats = $wpdb->prefix . self::DB_NAME;
        $wpdb->tables[] = self::DB_NAME;
    }

    /**
     * Install DB if necessary
     *
     * @return void
     */
    public static function maybe_install()
    {
        if (!self::exist()) {
            self::install();
        } else {
            $dbVersion = get_option(self::DB_VERSION_OPTION);

            if (absint($dbVersion) !== self::DB_VERSION) {
                self::install();
            }
        }
    }

    /**
     * Install DB table
     *
     * @return void
     */
    private static function install()
    {
        global $wpdb;

        $wpdb->hide_errors();
        $freshInstall = !self::exist();

        $upFile = ABSPATH . 'wp-admin/includes/upgrade.php';

        if (file_exists($upFile)) {

            require_once($upFile);

            $collate = Helpers::get_collate('stats/main');

            $table = "CREATE TABLE $wpdb->et_ajax_search_stats (
				id              BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				phrase          VARCHAR(255) NOT NULL,
				hits            INT NOT NULL,
				created_at      DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                post_type       VARCHAR(20) NOT NULL,
				autocomplete    TINYINT(1) NULL DEFAULT 1,
				solved          TINYINT(1) NULL DEFAULT 0,
				lang            VARCHAR(10) NULL,
				PRIMARY KEY    (id)
			) ENGINE=InnoDB $collate;";

            dbDelta($table);

            if ($freshInstall) {
                //@TODO mount index for columns if necessary
            }

            update_option(self::DB_VERSION_OPTION, self::DB_VERSION);
        }
    }

    /**
     * Check if the table exists
     *
     * @return bool
     */
    public static function exist()
    {
        global $wpdb;

        return Helpers::db_table_exists($wpdb->et_ajax_search_stats);
    }

    /**
     * Remove DB table
     *
     * @return void
     */
    public static function remove()
    {
        global $wpdb;

        $wpdb->hide_errors();

        $wpdb->query("DROP TABLE IF EXISTS $wpdb->et_ajax_search_stats");
        delete_option(self::DB_VERSION_OPTION);
    }

    /**
     * Wipe old analytics records
     *
     * @param int $daysAgo Minimum age of records to be deleted.
     *
     * @return void
     */
    public static function wipe_old_records($daysAgo = 0)
    {
        global $wpdb;

        if (!self::exist()) {
            return;
        }

        if (intval($daysAgo) <= 0) {
            $daysAgo = Maintenance::ANALYTICS_EXPIRATION_IN_DAYS;
        }

        $wpdb->hide_errors();

        // Delete expired records.
        $daysAgo = date('Y-m-d H:i:s', strtotime("today - $daysAgo days"));
        $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->et_ajax_search_stats WHERE DATE(created_at) <= %s", $daysAgo));

        // Delete non-critical records.
        if (
            defined('ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL') &&
            ET_AJAX_SEARCH_ANALYTICS_ONLY_CRITICAL
        ) {
            $wpdb->query("DELETE FROM $wpdb->et_ajax_search_stats WHERE hits > 0");
        }
    }

    /**
     * Wipe all analytics records
     *
     * @return void
     */
    public static function wipe_all_records()
    {
        global $wpdb;

        if (!self::exist()) {
            return;
        }

        $wpdb->hide_errors();

        $wpdb->query("DELETE FROM $wpdb->et_ajax_search_stats");
    }

    /**
     * Get the number of records
     *
     * @return int
     */
    public static function get_records_count()
    {
        global $wpdb;

        if (!self::exist()) {
            return 0;
        }

        $wpdb->hide_errors();

        return intval($wpdb->get_var("SELECT COUNT(*) FROM $wpdb->et_ajax_search_stats"));
    }

    /**
     * @return string
     */
    public static function get_table_name()
    {
        global $wpdb;

        return $wpdb->prefix . Database::DB_NAME;
    }
}
