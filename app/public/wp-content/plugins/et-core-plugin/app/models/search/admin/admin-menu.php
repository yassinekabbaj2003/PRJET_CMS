<?php

namespace ETC\App\Models\Search\Admin;

use ETC\App\Models\Search\Helpers;
use ETC\App\Models\Search\Analytics\Analytics;
use ETC\App\Models\Search\Analytics\User_Interface;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Admin_Menu
{

    public $global_admin_class;
    /**
     * Projects.
     *
     * @var array
     * @since 5.4.0
     */
    private $settings = [];

    public function __construct()
    {
        add_action('admin_init', array($this, 'init_admin'));

        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_action('admin_menu', array($this, 'xstore_panel_search_stats_link'), 99999); // keep the last position
        add_action( 'admin_bar_menu', array( $this, 'top_bar_menu' ), 999 );

        add_filter('etheme_dashboard_navigation', array($this, 'filter_navigation'), 10, 7);
    }

    public function init_admin()
    {
        if (!class_exists('\EthemeAdmin')) {
            return;
        }

        if (!method_exists('\EthemeAdmin', 'get_instance')) {
            return;
        }
        $this->global_admin_class = \EthemeAdmin::get_instance();

        $this->global_admin_class->init_vars();

//        $this->global_admin_class->settings_name = 'xstore_search_stats_settings';
//
//        $this->global_admin_class->xstore_panel_section_settings = get_option($this->global_admin_class->settings_name, array());
//
//        $this->settings = $this->global_admin_class->xstore_panel_section_settings;

    }

    public function filter_navigation($categories, $theme_active, $core_active, $allow_full_access, $locked_icon, $mtips_notify, $labels)
    {
        $category = 'performance';
        $is_active = ($_GET['page'] == 'et-panel-search-stats');
        $categories[$category]['items'][] = sprintf(
            (!$theme_active && !$allow_full_access ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>'),
            (($theme_active && $core_active) || $allow_full_access) ? admin_url('admin.php?page=et-panel-search-stats') : admin_url('admin.php?page=et-panel-welcome'),
            $is_active ? ' active' : '',
            '<span class="et-panel-nav-icon et-panel-nav-sales-booster"></span>',
            esc_html__('Search Analytics', 'xstore-core') . (!$theme_active && !$allow_full_access ? $locked_icon : '') . $labels['new']
        );
        if ($is_active)
            $categories[$category]['active_item'] = $is_active;
        return $categories;
    }

    /**
     * Add link to xstore submenu page.
     *
     * @return void
     * @since 5.4.0
     *
     */
    public function xstore_panel_search_stats_link()
    {
        add_submenu_page(
            'et-panel-welcome',
            esc_html__('Search Analytics', 'xstore-white-label-branding'),
            esc_html__('Search Analytics', 'xstore-white-label-branding'),
            Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options',
            'et-panel-search-stats',
            array($this, 'xstore_panel_search_stats_page')
        );
    }

    /**
     * top_bar_menu.
     *
     * @return void
     * @since 5.4.0
     *
     */
    function top_bar_menu($wp_admin_bar)
    {
        if (!defined('ETHEME_CODE_IMAGES') || !current_user_can((Helpers::shop_manager_has_access() ? 'manage_woocommerce' : 'manage_options'))) {
            return;
        }

        $menu_icons = apply_filters('etheme_top_bar_menu_icons', array());
        $menu_labels = (object)apply_filters('etheme_top_bar_menu_labels', array());

        $icon = (isset($menu_icons['sales_booster']) ? $menu_icons['sales_booster'] : '');
        $label = (isset($menu_labels->new) ? $menu_labels->new : '');

        $wp_admin_bar->add_node(array(
            'parent' => 'et-top-bar-general-menu',
            'id' => 'et-panel-search-stats',
            'title' => $icon . esc_html__('Search Analytics', 'xstore-white-label-branding') . $label,
            'href' => admin_url('admin.php?page=et-panel-search-stats'),
        ));
    }

    /**
     * Section content html.
     *
     * @return void
     * @since 5.4.0
     *
     */
    public function xstore_panel_search_stats_page()
    {

        wp_enqueue_script('et_ajax_search_stats-admin-js');
        wp_enqueue_style('et_ajax_search_stats-admin-css');

//        $theme   = wp_get_theme();
//        $version = $theme->get( 'Version' );

//        if ( is_child_theme() ) {
//            $parent  = wp_get_theme( 'xstore' );
//            $version = $parent->version;
//        }

//        $version = 'v.' . $version;

        $instance = Analytics::get_instance();
        $module_enabled = $instance->is_module_active();

        ob_start();
        get_template_part('framework/panel/templates/page', 'header');
        get_template_part('framework/panel/templates/page', 'navigation');
        ?>

        <div class="et-row etheme-page-content">
            <h2 class="etheme-page-title etheme-page-title-type-2">
                <?php echo esc_html__('Search Analytics', 'xstore-white-label-branding'); ?>
                <label class="et-panel-option-switcher<?php if ($module_enabled) { ?> switched<?php } ?>"
                       for="etheme_ajax_search_analytics">
                    <input type="checkbox" id="etheme_ajax_search_analytics" name="etheme_ajax_search_analytics"
                           <?php if ($module_enabled) { ?>checked<?php } ?>>
                    <span></span>
                </label>
            </h2>

            <p><?php echo sprintf(esc_html__('%s Search Analytics is our exclusive tool designed to give you detailed insights into how your customers search for products on your website. This is a powerful tool that provides deep insights into your customers\' search behavior. With this feature, you can now understand which products are in high demand, enabling you to focus more on what your customers need most. Moreover, you can identify products that customers are searching for but not finding—giving you the opportunity to add these products to your website and increase sales.', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')); ?></p>

            <?php

            if ($module_enabled) :
                $instance->maybe_init_user_interface();
                $instance::$ui->analytics_page();
            else: ?>
                <p class="et-message et-info"><?php echo esc_html__('Your search analytics is currently disabled. You can activate it by turning on the switcher above.', 'xstore-core'); ?></p>
            <?php endif;

            ?>
        </div>

        <?php get_template_part('framework/panel/templates/page', 'footer');
        echo ob_get_clean();
    }

    /**
     * Enqueue Script
     *
     * @since 5.4.0
     * @version 1.0
     */
    public function admin_assets()
    {
        wp_register_script(
            'et_ajax_search_stats-admin-js',
            plugin_dir_url(__FILE__) . 'assets/script.js',
            array('jquery', 'etheme_admin_js'),
            false,
            true
        );
        wp_localize_script('et_ajax_search_stats-admin-js', 'XStorePanelAjaxSearchStatsConfig', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'confirmQuestion' => esc_html__('Are you sure?', 'xstore-core'),
            'nonce' => array(
                'reset_stats' => wp_create_nonce(User_Interface::RESET_STATS_NONCE),
                'export_stats_csv' => wp_create_nonce(User_Interface::EXPORT_STATS_CSV_NONCE),
                'load_more_critical_searches' => wp_create_nonce(User_Interface::LOAD_MORE_CRITICAL_SEARCHES_NONCE),
                'load_more_autocomplete' => wp_create_nonce(User_Interface::LOAD_MORE_AUTOCOMPLETE_NONCE),
            )
        ));
        wp_register_style(
            'et_ajax_search_stats-admin-css',
            plugin_dir_url(__FILE__) . 'assets/style.css',
            array('etheme_admin_panel_css'),
            false,
            false
        );
    }

}

$admin_menu = new Admin_Menu();
