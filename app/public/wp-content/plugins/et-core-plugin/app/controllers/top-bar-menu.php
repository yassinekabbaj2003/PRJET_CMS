<?php

namespace ETC\App\Controllers;
/**
 * Top Bar Menu.
 *
 * @since      4.0.12
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/Models
 */
class Top_Bar_Menu extends Base_Controller{
    public $is_theme_active = false;
    public $is_admin = false;
    public $is_woocommerce = false;
    public $is_elementor = false;
    public $is_system_requirements = true;
    public $is_update_available = false;
    private $settings;
    private $notices;

    public $is_subscription = false;

    public function hooks(){
        add_action( 'admin_bar_menu', array( $this, 'top_bar_menu' ), 110 );
    }

    public function top_bar_menu( $wp_admin_bar ){
        if ( ! defined( 'ETHEME_CODE_IMAGES' ) || ! current_user_can('manage_options') ) {
            return;
        }

        $allow_full_access = !$this->is_theme_activation_required();
        $this->is_theme_active = $this->is_theme_active();
        $this->is_admin = is_admin();
        $this->is_woocommerce = class_exists('WooCommerce');
        $this->is_elementor = defined( 'ELEMENTOR_VERSION' );
        $this->is_system_requirements = $this->is_system_requirements();
        $this->is_update_available = $this->is_update_available();
        $this->set_settings();
        $this->get_notices();
        $menu_labels = $this->label();
        $menu_icons = $this->get_menu_icons();

        // usage in other places where the top bar menu items are added in top-bar menu created below
        add_filter('etheme_top_bar_menu_icons', function ($icons) use ($menu_icons) {
            return $menu_icons;
        });

        add_filter('etheme_top_bar_menu_labels', function ($labels) use ($menu_labels) {
            return $menu_labels;
        });

        $notices = array();

        if($this->is_theme_active){
            if ($this->is_system_requirements){
                $notices['system_requirements'] = $this->is_system_requirements;
            }

            if (
                ! $this->get_support_status()
                && ! $this->settings->hide_updates
                && ! $this->is_subscription
            ){
                $notices['support'] = 1;
            }
        } else {
//          $notices['activate'] = 1;
        }

        if ( !$this->is_theme_active && $allow_full_access )
            $notices['licence'] = 1;

        if ( $this->is_theme_active || $allow_full_access ) {
            if(
                $this->is_update_available
                && in_array('changelog', $this->settings->show_pages)
                && ! $this->settings->hide_updates
            ){
                $notices['update'] = 1;
            }

            $available_patches = get_site_transient( 'xstore_patches_available', array());

            if ( is_array($available_patches) && count( $available_patches ) ){
                $notices['patches'] = count($available_patches);
            }
        }


        $main_notices_icon = '';
        if (count($notices)){
            $count = count($notices);
            if (isset($notices['patches'])){
                $count = $count - 1 + $notices['patches'];
            }
            if (isset($notices['system_requirements'])){
            	if  (is_array($notices['system_requirements'])){
		            $notices['system_requirements'] = count($notices['system_requirements']);
	            }
                $count = $count - 1 + $notices['system_requirements'];
            }
            $main_notices_icon .= '<span class="et-title-label">'.$count.'</span>';
        }


        $args = array(
            'id'    => 'et-top-bar-general-menu',
            'title' => '<span class="ab-label"><img class="et-logo" style="vertical-align: -4px; margin-inline-end: 5px; max-width: 18px;" src="' . $this->settings->title_logo . '" alt="xstore"><span>' . $this->settings->title_text . '</span>' . $main_notices_icon . $this->notices->main,
            'href'  => admin_url( 'admin.php?page=et-panel-welcome' ),
            'meta' => array(
                'html' => $this->style(),
            )
        );

        $wp_admin_bar->add_node( $args );

        if ( in_array('welcome', $this->settings->show_pages) ) {
            $notice_icon = isset($notices['activate']) ? '<span class="et-title-label">'.$notices['activate'].'</span>' : '';
            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu',
                'id'     => 'et-panel-welcome',
                'title'  => $menu_icons['welcome'] . esc_html__( 'Dashboard', 'xstore-core' ). $this->notices->main . $notice_icon,
                'href'   => admin_url( 'admin.php?page=et-panel-welcome' ),
            ) );
        }

        if ( $this->is_theme_active || $allow_full_access ) {
            if ( in_array('customize', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-theme-settings',
                    'title' => $menu_icons['customize'] . esc_html__('Theme Options', 'xstore-core'),
                    'href' => wp_customize_url(),
                ));

                //Child pages of Theme Settings (et-top-bar-general-menu)
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-general',
                    'title' => '<span class="dashicons dashicons-before dashicons-schedule"></span>' . __('General', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-breadcrumbs',
                    'title' => '<span class="dashicons dashicons-before dashicons-carrot"></span>' . __('Breadcrumbs', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=breadcrumbs'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-footer',
                    'title' => '<span class="dashicons dashicons-before dashicons-arrow-down-alt"></span>' . __('Footer', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=footer'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-mobile_panel',
                    'title' => '<span class="dashicons dashicons-before dashicons-download"></span>' . __('Mobile panel', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=mobile_panel'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-style',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Styling/Colors', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=style'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-typography-content',
                    'title' => '<span class="dashicons dashicons-before dashicons-media-document"></span>' . __('Typography', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=typography-content'),
                ));
//                if (in_array('custom_fonts', $this->settings->show_pages)) {
//                    $wp_admin_bar->add_node(array(
//                        'parent' => 'et-theme-settings',
//                        'id' => 'et-panel-custom-fonts',
//                        'title' => '<span class="dashicons dashicons-before dashicons-editor-spellcheck"></span>' . esc_html__('Custom Fonts', 'xstore-core'),
//                        'href' => admin_url('admin.php?page=et-panel-custom-fonts'),
//                    ));
//                }
                if ($this->is_woocommerce) {
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart',
                        'title' => '<span class="dashicons dashicons-before dashicons-cart"></span>' . __('WooCommerce(Shop)', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=woocommerce'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-built-in-wishlist',
                        'title' => '<span class="dashicons dashicons-before dashicons-heart"></span>' . esc_html__('Wishlist', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=xstore-wishlist'),
                    ));

                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-built-in-compare',
                        'title' => '<span class="dashicons dashicons-before dashicons-image-rotate"></span>' . esc_html__('Compare', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=xstore-compare'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-elements',
                        'title' => '<span class="dashicons dashicons-before dashicons-forms"></span>' . __('Shop Elements', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=shop-elements'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart-page',
                        'title' => '<span class="dashicons dashicons-before dashicons-cart"></span>' . __('Cart Page', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[panel]=cart-page'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-woocommerce_checkout',
                        'title' => '<span class="dashicons dashicons-before dashicons-clipboard"></span>' . __('Checkout', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=woocommerce_checkout'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-cart-checkout-layout',
                        'title' => '<span class="dashicons dashicons-before dashicons-schedule"></span>' . __('Advanced Cart/Checkout', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=cart-checkout-layout'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-color-swatches',
                        'title' => '<span class="dashicons dashicons-before dashicons-image-filter"></span>' . __('Variation Swatches', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-color-swatches'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-quick-view',
                        'title' => '<span class="dashicons dashicons-before dashicons-external"></span>' . __('Quick View', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-quick-view'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-section-shop-brands',
                        'title' => '<span class="dashicons dashicons-before dashicons-tickets"></span>' . __('Brands', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-brands'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-catalog-mode',
                        'title' => '<span class="dashicons dashicons-before dashicons-hidden"></span>' . __('Catalog Mode', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=catalog-mode'),
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-settings-shop-page-filters',
                        'title' => '<span class="dashicons dashicons-before dashicons-filter"></span>' . __('Shop Page Filters', 'xstore-core'),
                        'href' => admin_url('/customize.php?autofocus[section]=shop-page-filters'),
                    ));
                }
                if (in_array('social', $this->settings->show_pages)) {
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-theme-settings',
                        'id' => 'et-panel-social',
                        'title' => '<span class="dashicons dashicons-before dashicons-admin-users"></span>' . esc_html__('Authorization APIs', 'xstore-core'),
                        'href' => admin_url('admin.php?page=et-panel-social'),
                    ));
                }
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-blog',
                    'title' => '<span class="dashicons dashicons-before dashicons-editor-table"></span>' . __('Blog', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=blog'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-portfolio',
                    'title' => '<span class="dashicons dashicons-before dashicons-images-alt2"></span>' . __('Portfolio', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=portfolio'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-social-sharing',
                    'title' => '<span class="dashicons dashicons-before dashicons-share-alt"></span>' . __('Social Sharing', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=social-sharing'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-general-page-not-found',
                    'title' => '<span class="dashicons dashicons-before dashicons-warning"></span>' . __('404 Page', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general-page-not-found'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-style-custom_css',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Theme Custom CSS', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=style-custom_css'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-title_tagline',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-settings"></span>' . __('Site Identity', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=title_tagline'),
                ));
//                $wp_admin_bar->add_node(array(
//                    'parent' => 'et-theme-settings',
//                    'id' => 'et-settings-general-optimization',
//                    'title' => '<span class="dashicons dashicons-before dashicons-dashboard"></span>' . __('Speed Optimization', 'xstore-core'),
//                    'href' => admin_url('/customize.php?autofocus[section]=general-optimization'),
//                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-nav_menus',
                    'title' => '<span class="dashicons dashicons-before dashicons-menu"></span>' . __('Menus', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=nav_menus'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-widgets',
                    'title' => '<span class="dashicons dashicons-before dashicons-wordpress-alt"></span>' . __('Widgets', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[panel]=widgets'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-static_front_page',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-home"></span>' . __('Home Settings', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=static_front_page'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-custom_css',
                    'title' => '<span class="dashicons dashicons-before dashicons-admin-customizer"></span>' . __('Additional CSS', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=custom_css'),
                ));
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-theme-settings',
                    'id' => 'et-settings-cei-section',
                    'title' => '<span class="dashicons dashicons-before dashicons-controls-repeat"></span>' . __('Export/Import', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=cei-section'),
                ));
                // End of Child pages of Theme Settings (et-top-bar-general-menu)

            }

            if (in_array('demos', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-demos',
                    'title' => $menu_icons['demos'] . esc_html__('Import Demos 130+', 'xstore-core'),
                    'href' => admin_url('admin.php?page=et-panel-demos'),
                ));
            }

            if (get_theme_mod('static_blocks', true)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-staticblocks',
                    'title' => $menu_icons['static_blocks'] . esc_html__('Static Blocks', 'xstore-core'),
                    'href' => admin_url('edit.php?post_type=staticblocks'),
                ));
            }

            if (get_theme_mod('etheme_slides', true) && $this->is_elementor) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-etheme_slides',
                    'title' => $menu_icons['etheme_slides'] . esc_html__('Slides', 'xstore-core') . $menu_labels->new,
                    'href' => admin_url('edit.php?post_type=etheme_slides'),
                ));
            }

            if (get_theme_mod( 'etheme_mega_menus', true ) && $this->is_elementor) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-etheme_mega_menus',
                    'title' => $menu_icons['etheme_mega_menus'] . esc_html__('Mega Menus', 'xstore-core') . $menu_labels->new,
                    'href' => admin_url('edit.php?post_type=etheme_mega_menus'),
                ));
            }

            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu',
                'id'     => 'et-panel-global-widgets',
                'title'  => $menu_icons['widgets'] . esc_html__( 'Widgets', 'xstore-core' ),
                'href'   => admin_url( 'widgets.php' ),
            ) );

            if ($this->is_woocommerce && in_array('email_builder', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-general-menu',
                    'id' => 'et-panel-email-builder',
                    'title' => $menu_icons['email_builder'] . esc_html__('Email Builder', 'xstore-core'),
                    'href' => admin_url('admin.php?page=et-panel-email-builder'),
                ));
            }

            $wp_admin_bar->add_group(
                array(
                    'parent' => 'et-top-bar-general-menu',
                    'id'     => 'et-top-bar-general-menu-extra',
                    'meta'   => array(
                        'class' => 'ab-sub-secondary',
                    ),
                )
            );

            if ( in_array('plugins', $this->settings->show_pages) ) {
                $wp_admin_bar->add_node( array(
                    'parent' => 'et-top-bar-general-menu-extra',
                    'id'     => 'et-panel-plugins',
                    'title'  => $menu_icons['plugins'] . esc_html__( 'Plugin Installer', 'xstore-core' ),
                    'href'   => admin_url( 'admin.php?page=et-panel-plugins' ),
                ) );
            }

            if ( in_array('patcher', $this->settings->show_pages) ) {
                $notice_icon = isset($notices['patches']) ? '<span class="et-title-label">'.$notices['patches'].'</span>' : '';
                $wp_admin_bar->add_node( array(
                    'parent' => 'et-top-bar-general-menu-extra',
                    'id'     => 'et-panel-patcher',
                    'title'  => $menu_icons['patcher'] . esc_html__( 'Patcher', 'xstore-core' ) . $notice_icon,
                    'href'   => admin_url( 'admin.php?page=et-panel-patcher' ),
                ) );
            }
        }

        if ( in_array('system_requirements', $this->settings->show_pages) ) {
            $notice_icon = isset($notices['system_requirements']) ? '<span class="et-title-label">'.$notices['system_requirements'].'</span>' : '';
            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu-extra',
                'id'     => 'et-panel-system-requirements',
                'title'  => $menu_icons['system_requirements'] . esc_html__( 'System Status', 'xstore-core' ) . $notice_icon,
                'href'   => admin_url( 'admin.php?page=et-panel-system-requirements' ),
            ) );
        }

        // white label check already done
        if ( isset( $notices['update'] ) ) {
            $notice_icon = isset( $notices['update'] ) ? '<span class="et-title-label">' . $notices['update'] . '</span>' : '';
            $wp_admin_bar->add_node( array(
                'parent' => 'et-top-bar-general-menu-extra',
                'id'     => 'et-panel-changelog',
                'title'  => $menu_icons['changelog'] . (!$this->is_theme_active ? esc_html__( 'Auto-Updates', 'xstore-core' ) : esc_html__( 'Updates', 'xstore-core' )) . $notice_icon,
                'href'   => !$this->is_theme_active ? admin_url('admin.php?page=et-panel-welcome') : admin_url( 'update-core.php?force-check=1' ),
            ) );
        }
        if ( isset( $notices['support'] ) ) {
            $notice_icon = isset($notices['support']) ? '<span class="et-title-label">' . $notices['support'] . '</span>' : '';
            $wp_admin_bar->add_node(array(
                'parent' => 'et-top-bar-general-menu-extra',
                'id' => 'et-panel-renew-support',
                'title' => $menu_icons['support'] . esc_html__('Renew Support', 'xstore-core') . $notice_icon,
                'href' => 'https://1.envato.market/2rXmmA',
                'meta' => array(
                    'target' => '_blank'
                )
            ));
            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-renew-support',
                'id' => 'et-panel-renew-support-origin',
                'title' => $menu_icons['update'] . esc_html__('Renew Now', 'xstore-core'),
                'href' => 'https://1.envato.market/2rXmmA',
                'meta' => array(
                    'target' => '_blank'
                )
            ));
            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-renew-support',
                'id' => 'et-panel-how-to-renew-support',
                'title' => $menu_icons['question_mark'] . esc_html__('How to Extend / Renew', 'xstore-core'),
                'href' => "https://help.market.envato.com/hc/en-us/articles/207886473-Extend-or-renew-Item-Support#:~:text=on%20that%20item%3A-,Log%20in%20to%20your%20account,on%20'Renew%20support%20now",
                'meta' => array(
                    'target' => '_blank'
                )
            ));
        }
        elseif ( !$this->is_theme_active && $allow_full_access ) {
            $notice_icon = isset($notices['licence']) ? '<span class="et-title-label">' . $notices['licence'] . '</span>' : '';
            $wp_admin_bar->add_node(array(
                'parent' => 'et-top-bar-general-menu-extra',
                'id' => 'et-panel-register-license',
                'title' => $menu_icons['support'] . esc_html__('Register License', 'xstore-core') . $notice_icon,
                'href' => admin_url('admin.php?page=et-panel-welcome'),
            ));

            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-register-license',
                'id' => 'et-panel-how-to-register-license',
                'title' => $menu_icons['question_mark'] . sprintf(esc_html__('How to Register %s', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')),
                'href' => etheme_documentation_url('163-how-to-registerderegister-xstore-theme-livestaging-websites', false),
                'meta' => array(
                    'target' => '_blank'
                )
            ));
            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-register-license',
                'id' => 'et-panel-how-to-find-license-code',
                'title' => $menu_icons['question_mark'] . esc_html__('Where is my Purchase Code?', 'xstore-core'),
                'href' => 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code',
                'meta' => array(
                    'target' => '_blank'
                )
            ));

	        $wp_admin_bar->add_node(array(
		        'parent' => 'et-panel-register-license',
		        'id' => 'et-panel-buy-license',
		        'title' => '<span class="dashicons dashicons-money-alt" style="
    font-family: \'dashicons\';
    margin-right: 4px;
    line-height: 1;
    transform: scale(1.2);
"></span>' . sprintf(esc_html__('Buy %s License', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')),
		        'href' => 'https://1.envato.market/oePdkE',
		        'meta' => array(
			        'target' => '_blank'
		        )
	        ));
        }

            $wp_admin_bar->add_node(array(
                'parent' => 'et-top-bar-general-menu-extra',
                'id' => 'et-panel-more',
                'title' => $menu_icons['more'] . esc_html__('More', 'xstore-core'),
                'href' => admin_url('admin.php?page=et-panel-welcome'),
            ));
//          if (!$this->is_subscription){
//              $wp_admin_bar->add_node( array(
//                  'parent' => 'et-top-bar-general-menu',
//                  'id'     => 'et-panel-unlimited',
//                  'title'  => esc_html__( 'Go Unlimited', 'xstore-core' ),
//                  'href'   => 'https://www.8theme.com/#price-section-anchor',
//              ) );
//          }

            if (in_array('open_ai', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-panel-more',
                    'id' => 'et-panel-open-ai',
                    'title' => $menu_icons['open_ai'] . esc_html__('ChatGPT (OpenAI)', 'xstore-core') . $menu_labels->new,
                    'href' => admin_url('admin.php?page=et-panel-open-ai'),
                ));
            }

            if (!class_exists('XStore_AMP')) {
                $amp_url = admin_url('admin.php?page=et-panel-plugins&plugin=xstore-amp');
            } else {
                $amp_url = admin_url('admin.php?page=et-panel-xstore-amp');
            }

            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-more',
                'id' => 'et-panel-amp',
                'title' => $menu_icons['amp'] . sprintf(esc_html__('AMP %s', 'xstore-core'), $this->settings->plugin_label),
                'href' => (($this->is_theme_active || $allow_full_access) ? $amp_url : admin_url('admin.php?page=et-panel-welcome')),
            ));

            if (in_array('custom_fonts', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-panel-more',
                    'id' => 'et-panel-custom-fonts',
                    'title' => $menu_icons['custom_fonts'] . esc_html__('Custom Fonts', 'xstore-core'),
                    'href' => admin_url('admin.php?page=et-panel-custom-fonts'),
                ));
            }

            if (in_array('customize', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-panel-more',
                    'id' => 'et-panel-speed-optimization',
                    'title' => $menu_icons['speed_optimization'] . esc_html__('Speed Optimization', 'xstore-core'),
                    'href' => admin_url('/customize.php?autofocus[section]=general-optimization'),
                ));
            }

            $wp_admin_bar->add_node(array(
                'parent' => 'et-panel-more',
                'id' => 'et-xstore-documentation',
                'title' => $menu_icons['changelog'] . esc_html__('Documentation', 'xstore-core'),
                'href' => etheme_documentation_url(false, false),
                'meta' => array(
                    'target' => '_blank'
                )
            ));

            if (in_array('support', $this->settings->show_pages)) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-panel-more',
                    'id' => 'et-panel-support',
                    'title' => $menu_icons['support'] . esc_html__('Support & Tutorials', 'xstore-core'),
                    'href' => admin_url('admin.php?page=et-panel-support'),
                ));
            }

        if ( ($this->is_theme_active || $allow_full_access) && !$this->settings->hide_theme_builders ) {
            // theme builder
            $this->add_theme_builders_tab($wp_admin_bar, $this->get_theme_builders_menu_icons(), $this->is_woocommerce);
        }

        if ( ($this->is_theme_active || $allow_full_access) && in_array('sales_booster', $this->settings->show_pages) && $this->is_woocommerce ) {
            $wp_admin_bar->add_node(array(
                'id' => 'et-top-bar-xstore-sales-booster',
                'title' => '<span class="ab-label"><span class="dashicons-before dashicons-chart-bar" style="position: relative; top: 6px;;margin-inline-end: 5px;width: 18px;" aria-hidden="true"></span><span>' . esc_html__('Sales Booster', 'xstore-core') . '</span>',
                'href' => admin_url('admin.php?page=et-panel-sales-booster'),
            ));
            $sales_booster_main_features = array(
                'fake_sale_popup' => esc_html__('Fake Sale Popup', 'xstore-core'),
                'cart_checkout_countdown' => esc_html__( 'Cart Countdown', 'xstore-core' ),
                'cart_checkout_progress_bar' => esc_html__( 'Checkout Progress Bar', 'xstore-core' ),
                'fake_live_viewing' => esc_html__('Fake Live Viewing', 'xstore-core'),
                'fake_product_sales' => esc_html__('Item Sold Fake Indicator', 'xstore-core'),
                'customer_reviews_advanced' => esc_html__('Advanced Product Reviews', 'xstore-core'),
                'quantity_discounts' => esc_html__('Quantity Discounts', 'xstore-core'),
                'safe_checkout' => esc_html__('Safe & Secure Checkout', 'xstore-core'),
            );
            foreach ($sales_booster_main_features as $sales_booster_main_feature_key => $sales_booster_main_feature_title) {
                $wp_admin_bar->add_node(array(
                    'parent' => 'et-top-bar-xstore-sales-booster',
                    'id' => 'et-top-bar-xstore-sales-booster-' . $sales_booster_main_feature_key,
                    'title' => $sales_booster_main_feature_title,
                    'href' => add_query_arg( array( 'etheme-sales-booster-tab' => $sales_booster_main_feature_key ), esc_url( admin_url('admin.php?page=et-panel-sales-booster') ) )
                ));
            }
            $wp_admin_bar->add_node(array(
                'parent' => 'et-top-bar-xstore-sales-booster',
                'id' => 'et-top-bar-xstore-sales-booster-more',
                'title' => esc_html__( 'More Features', 'xstore-core' ) . ' &rarr;',
                'href' => esc_url( admin_url('admin.php?page=et-panel-sales-booster') )
            ));
        }
    }

    public function add_theme_builders_tab($wp_admin_bar, $menu_icons, $is_woocommerce)
    {
        if (!in_array('customize', $this->settings->show_pages)) return;
        $elementor_builders_url = admin_url('admin.php?page=et-panel-theme-builders');
        $args = array(
            'id' => 'et-top-bar-theme-builders-menu',
//            'title' => '<span class="ab-label"><img class="et-logo" style="vertical-align: -4px; margin-inline-end: 5px; max-width: 18px;" src="' . $this->settings->title_logo . '" alt="xstore"><span>' . esc_html__('Theme Builders', 'xstore-core') . '</span>',
            'title' => '<span class="ab-label"><span class="dashicons-before dashicons-schedule" style="position: relative; top: 6px;margin-inline-end: 5px;width: 18px;" aria-hidden="true"></span><span>' . sprintf(esc_html__('%s Builders', 'xstore-core'), $this->settings->title_text) . '</span>',
            'href' => $elementor_builders_url,
            'meta' => array(
                'html' => $this->style(),
            )
        );

        $wp_admin_bar->add_node($args);

        $builder_url = $elementor_builders_url;

        $has_pro = defined('ELEMENTOR_PRO_VERSION');

        $wp_admin_bar->add_node(array(
            'parent' => 'et-top-bar-theme-builders-menu',
            'id' => 'et-panel-theme-builders',
            'title' => $menu_icons['footer'] . esc_html__('Builders Panel', 'xstore-core'),
            'href' => $builder_url,
        ));

        $theme_builders = array(
            'header' => esc_html__('Header', 'xstore-core'),
            'header-old' => esc_html__('Header', 'xstore-core'),
        );

        $theme_builders['footer'] = esc_html__('Footer', 'xstore-core');

//            if ( $is_woocommerce ) {
        $theme_builders = array_merge($theme_builders,
            array(
                'product' => esc_html__('Single Product', 'xstore-core'),
            ));
//                if ( get_option( 'etheme_single_product_builder', false ) ) {
        $theme_builders['product-old'] = esc_html__('Single Product', 'xstore-core');
//                }
        $theme_builders = array_merge($theme_builders, array(
            'product-archive' => esc_html__('Products Archive', 'xstore-core'),
        ));
//            }

        $theme_builders = array_merge($theme_builders, array(
            'search-results' => esc_html__('Search Results', 'xstore-core')
        ));

//            if ( $is_woocommerce ) {
        $theme_builders = array_merge($theme_builders, array(
            'myaccount' => esc_html__('My Account', 'xstore-core'),
            'cart' => esc_html__('Cart Page', 'xstore-core'),
            'checkout' => esc_html__('Checkout Page', 'xstore-core'),
        ));
    // }

        $theme_builders = array_merge($theme_builders, array(
            'archive' => esc_html__('Posts Archive', 'xstore-core'),
            'single-post' => esc_html__('Single Post', 'xstore-core'),
        ));

            $theme_builders = array_merge($theme_builders, array(
                'error-404' => esc_html__('Error-404', 'xstore-core'),
            ));

            $elementor_pro_theme_builder_link = $this->is_elementor ? \Elementor\Plugin::$instance->app->get_settings('menu_url') : false;

            foreach ($theme_builders as $theme_builder_unique_key => $theme_builder_unique_name) {
                if ( !$is_woocommerce && in_array($theme_builder_unique_key, array('product','product-old', 'product-archive', 'myaccount', 'cart', 'checkout') ) )
                    $builder_url = $elementor_builders_url;
                else {
                    if ($this->is_elementor)
                        $builder_url = !$has_pro ? $elementor_builders_url . '&et_trigger_theme_builders_plugins_popup=true' : $elementor_pro_theme_builder_link . '/templates/' . $theme_builder_unique_key;
                    else
                        $builder_url = $elementor_builders_url;
                    switch ($theme_builder_unique_key) {
                        case 'myaccount':
                        case 'cart':
                        case 'checkout':
                            if ($has_pro) {
                                if (
                                	!get_option('et_theme_builder_wc_install_done', false)
	                                && method_exists('\WC_Install', 'create_pages')
	                                && get_option('etheme_current_version')
                                ) {
                                    remove_filter('woocommerce_create_pages', 'etheme_do_not_setup_demo_pages', 10);
                                    \WC_Install::create_pages();
                                    // to start this once only on first load
                                    update_option('et_theme_builder_wc_install_done', 'yes');
                                }
                                $cart_checkout_page_id = get_option('woocommerce_' . $theme_builder_unique_key . '_page_id'); // wc_get_page_id($theme_builder_unique_key) not working correct;
                                if ($cart_checkout_page_id > 0) {
                                    $builder_url = add_query_arg(array('post' => $cart_checkout_page_id, 'action' => 'elementor', 'et_page' => $theme_builder_unique_key), admin_url('post.php'));
                                } else {
                                    // $builder_url = $elementor_pro_theme_builder_link . '/templates/single-page';
                                    $builder_url = add_query_arg(array('post_type' => 'page', 'action' => 'elementor', 'et_page' => $theme_builder_unique_key), admin_url('post-new.php'));
                                }
                            }
                            break;
                        case 'header-old':
                            $builder_url = admin_url('/customize.php?autofocus[' . (!get_option('etheme_disable_customizer_header_builder', false) ? 'panel' : 'section') . ']=header-builder');
                            break;
                        case 'product-old':
                            $builder_url = admin_url('/customize.php?autofocus[' . (get_option('etheme_single_product_builder', false) ? 'panel' : 'section') . ']=single_product_builder');
                            break;
                    }
                }

                if ( in_array($theme_builder_unique_key, array('header-old', 'product-old')) ) {
                    $parent_node = str_replace('-old', '', $theme_builder_unique_key);
                    $local_builder_url = $elementor_builders_url;
                    if ( $this->is_elementor ) {
                        if ( !($theme_builder_unique_key == 'product-old' && !$is_woocommerce) )
                            $local_builder_url = !$has_pro ? $elementor_builders_url . '&et_trigger_theme_builders_plugins_popup=true' : $elementor_pro_theme_builder_link . '/templates/' . $parent_node;
                    }

                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-panel-theme-builder-' . $parent_node,
                        'id' => 'et-panel-theme-builder-' . $theme_builder_unique_key . '-elementor',
                        'title' => esc_html__('Via Elementor', 'xstore-core'),
                        'href' => $local_builder_url,
                    ));
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-panel-theme-builder-' . $parent_node,
                        'id' => 'et-panel-theme-builder-' . $theme_builder_unique_key . '-customizer',
                        'title' => esc_html__('Via Customizer', 'xstore-core'),
                        'href' => $builder_url,
                    ));
                }
                else {
                    $local_builder_url = $builder_url;
                    if ( !$this->is_elementor || in_array($theme_builder_unique_key, array('header')) || ($theme_builder_unique_key == 'product' && array_key_exists('product-old', $theme_builders))) {
                        $local_builder_url = $elementor_builders_url;
                    }
                    $wp_admin_bar->add_node(array(
                        'parent' => 'et-top-bar-theme-builders-menu',
                        'id' => 'et-panel-theme-builder-'.$theme_builder_unique_key,
                        'title' => $menu_icons[$theme_builder_unique_key] . $theme_builder_unique_name,
                        'href' => $local_builder_url,
                    ));
                }
            }
    }

    public function is_theme_active(){
        return function_exists('etheme_is_activated') && etheme_is_activated();
    }

    public function is_theme_activation_required(){
        return function_exists('etheme_activation_required') && etheme_activation_required();
    }

    public function is_system_requirements(){
        if (
            ! defined('ETHEME_CODE')
            || ! is_user_logged_in()
            || ! current_user_can('administrator')
        ){
            return true;
        }

		$log = array();

	    $cache = get_transient('etheme_system_requirements_system_logs', false);

	    if ( $cache ) return $cache;

	    if( ! class_exists('Etheme_System_Requirements') ) {
		    require_once(ABSPATH . 'wp-admin/includes/file.php');
		    require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'system-requirements.php') );
	    }
	    $system = new \Etheme_System_Requirements();
	    $system->system_test();
	    $log = count($system->system_logs());

	    set_transient('etheme_system_requirements_system_logs', $log, WEEK_IN_SECONDS);

	    return $log;
    }

    public function is_update_available(){
        if (! class_exists('ETheme_Version_Check') && defined('ETHEME_CODE') && is_user_logged_in() ){
            require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'version-check.php') );
        }
        $check_update = new \ETheme_Version_Check(false);

        $this->is_subscription = $check_update->is_subscription;

        return $check_update->is_update_available();
    }

    private function set_settings(){
        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

        $settings = array(
            'title_logo' => ETHEME_CODE_IMAGES . 'wp-icon.svg',
            'title_text' => 'XStore',
            'show_pages' => array(
                'welcome',
                'system_requirements',
                'demos',
                'plugins',
                'patcher',
                'open_ai',
                'customize',
                'email_builder',
                'sales_booster',
                'custom_fonts',
                'social',
                'support',
                'changelog',
                'sponsors'
            ),
            'hide_theme_builders' => false,
            'plugin_label' => 'XStore',
	        'hide_updates' => false
        );

        if ( count($xstore_branding_settings) ) {
            if ( isset($xstore_branding_settings['control_panel'])) {
                if ( $xstore_branding_settings['control_panel']['icon'] ) {
                    $settings['title_logo'] = $xstore_branding_settings['control_panel']['icon'];
                }
                if ( $xstore_branding_settings['control_panel']['label'] ) {
                    $settings['title_text'] = $xstore_branding_settings['control_panel']['label'];
                }

                if ( isset($xstore_branding_settings['control_panel']['hide_theme_builders']) && $xstore_branding_settings['control_panel']['hide_theme_builders'] == 'on' )
                    $settings['hide_theme_builders'] = true;

                $show_pages_parsed = array();
                foreach ( $settings['show_pages'] as $show_page ) {
                    if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page])){
                        $show_pages_parsed[] = $show_page;
                    }
                }

                $settings['show_pages'] = $show_pages_parsed;

                if ( isset($xstore_branding_settings['control_panel']['hide_updates']) && $xstore_branding_settings['control_panel']['hide_updates'] == 'on' )
                    $settings['hide_updates'] = true;

            }
            if ( isset($xstore_branding_settings['plugins_data'] ) ) {
                if (isset($xstore_branding_settings['plugins_data']['label']) && !empty($xstore_branding_settings['plugins_data']['label']))
                    $settings['plugin_label'] = $xstore_branding_settings['plugins_data']['label'];
            }
        }
        $this->settings = (object) $settings;
    }

    public function get_menu_icons() {
        $icons = array(
            'welcome' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path d="M0 8.744c0-2.419 0.853-4.481 2.558-6.186s3.767-2.558 6.186-2.558v2.939c-1.596 0-2.963 0.568-4.1 1.705s-1.705 2.503-1.705 4.1h-2.939zM6.277 25.76c-2.201-2.201-3.302-4.849-3.302-7.945s1.101-5.745 3.302-7.946l2.54-2.576 0.435 0.435c0.701 0.701 1.052 1.554 1.052 2.558s-0.351 1.856-1.052 2.558l-0.508 0.508c-0.29 0.29-0.435 0.635-0.435 1.034s0.145 0.744 0.435 1.034l1.306 1.306c0.629 0.629 0.943 1.391 0.943 2.286s-0.314 1.657-0.943 2.286l1.56 1.56c1.064-1.064 1.596-2.34 1.596-3.828s-0.544-2.776-1.633-3.864l-0.798-0.798c0.629-0.629 1.076-1.336 1.342-2.122s0.375-1.59 0.326-2.413l6.494-6.494c0.29-0.29 0.635-0.435 1.034-0.435s0.744 0.145 1.034 0.435c0.29 0.29 0.435 0.635 0.435 1.034s-0.145 0.744-0.435 1.034l-6.785 6.785 1.524 1.524 8.744-8.707c0.29-0.29 0.629-0.435 1.016-0.435s0.726 0.145 1.016 0.435c0.29 0.29 0.435 0.629 0.435 1.016s-0.145 0.726-0.435 1.016l-8.708 8.744 1.524 1.524 7.692-7.692c0.29-0.29 0.635-0.435 1.034-0.435s0.744 0.145 1.034 0.435c0.29 0.29 0.435 0.635 0.435 1.034s-0.145 0.744-0.435 1.034l-7.692 7.692 1.524 1.524 5.878-5.878c0.29-0.29 0.635-0.435 1.034-0.435s0.744 0.145 1.034 0.435c0.29 0.29 0.435 0.635 0.435 1.034s-0.145 0.744-0.435 1.034l-8.707 8.671c-2.201 2.201-4.85 3.302-7.946 3.302s-5.745-1.101-7.946-3.302zM23.256 32v-2.939c1.596 0 2.963-0.568 4.1-1.705s1.705-2.504 1.705-4.1h2.939c0 2.419-0.853 4.481-2.558 6.186s-3.767 2.558-6.186 2.558z"></path>
                </svg>',
            'system_requirements' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M8.421 5.053c-0.702 0-1.298 0.246-1.79 0.737s-0.737 1.088-0.737 1.79 0.246 1.298 0.737 1.789c0.491 0.491 1.088 0.737 1.79 0.737s1.298-0.246 1.789-0.737c0.491-0.491 0.737-1.088 0.737-1.789s-0.246-1.298-0.737-1.79c-0.491-0.491-1.088-0.737-1.789-0.737zM8.421 21.895c-0.702 0-1.298 0.246-1.79 0.737s-0.737 1.088-0.737 1.789 0.246 1.298 0.737 1.79c0.491 0.491 1.088 0.737 1.79 0.737s1.298-0.246 1.789-0.737c0.491-0.491 0.737-1.088 0.737-1.79s-0.246-1.298-0.737-1.789c-0.491-0.491-1.088-0.737-1.789-0.737zM2.526 0h26.947c0.477 0 0.877 0.161 1.2 0.484s0.484 0.723 0.484 1.2v11.789c0 0.477-0.162 0.877-0.484 1.2s-0.723 0.484-1.2 0.484h-26.947c-0.477 0-0.877-0.161-1.2-0.484s-0.484-0.723-0.484-1.2v-11.789c0-0.477 0.161-0.877 0.484-1.2s0.723-0.484 1.2-0.484zM2.526 16.842h26.947c0.477 0 0.877 0.161 1.2 0.484s0.484 0.723 0.484 1.2v11.79c0 0.477-0.162 0.877-0.484 1.2s-0.723 0.484-1.2 0.484h-26.947c-0.477 0-0.877-0.161-1.2-0.484s-0.484-0.723-0.484-1.2v-11.79c0-0.477 0.161-0.877 0.484-1.2s0.723-0.484 1.2-0.484z"></path>
</svg>',
            'demos' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M3.194 27.658l-1.357-0.559c-0.825-0.346-1.377-0.945-1.657-1.797s-0.233-1.69 0.14-2.515l2.874-6.228v11.099zM9.582 31.171c-0.878 0-1.63-0.313-2.256-0.938s-0.938-1.377-0.938-2.256v-9.581l4.232 11.737c0.080 0.186 0.16 0.366 0.24 0.539s0.186 0.339 0.319 0.499h-1.597zM17.806 31.011c-0.852 0.319-1.677 0.28-2.475-0.12s-1.357-1.025-1.677-1.876l-7.106-19.483c-0.319-0.852-0.293-1.683 0.080-2.495s0.985-1.364 1.836-1.657l12.057-4.392c0.852-0.319 1.677-0.279 2.475 0.12s1.357 1.025 1.677 1.876l7.107 19.483c0.319 0.852 0.293 1.683-0.080 2.495s-0.985 1.364-1.836 1.657l-12.057 4.392zM14.372 12.008c0.452 0 0.832-0.153 1.138-0.459s0.459-0.685 0.459-1.138c0-0.452-0.153-0.832-0.459-1.138s-0.685-0.459-1.138-0.459c-0.452 0-0.832 0.153-1.138 0.459s-0.459 0.685-0.459 1.138c0 0.452 0.153 0.832 0.459 1.138s0.685 0.459 1.138 0.459z"></path>
</svg>',
            'plugins' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M9.518 32h-6.236c-0.903 0-1.675-0.321-2.318-0.964s-0.964-1.415-0.964-2.318v-6.236c1.313 0 2.462-0.417 3.446-1.251s1.477-1.894 1.477-3.179-0.492-2.345-1.477-3.18-2.133-1.251-3.446-1.251v-6.236c0-0.903 0.321-1.675 0.964-2.318s1.415-0.964 2.318-0.964h6.564c0-1.149 0.397-2.12 1.19-2.913s1.764-1.19 2.913-1.19c1.149 0 2.12 0.397 2.913 1.19s1.19 1.764 1.19 2.913h6.564c0.903 0 1.675 0.321 2.318 0.964s0.964 1.415 0.964 2.318v6.564c1.149 0 2.12 0.397 2.913 1.19s1.19 1.764 1.19 2.913c0 1.149-0.397 2.12-1.19 2.913s-1.764 1.19-2.913 1.19v6.564c0 0.903-0.321 1.675-0.964 2.318s-1.415 0.964-2.318 0.964h-6.236c0-1.368-0.431-2.53-1.292-3.487s-1.908-1.436-3.138-1.436c-1.231 0-2.277 0.479-3.138 1.436s-1.292 2.12-1.292 3.487z"></path>
</svg>',
            'patcher' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M28.893 3.107c-2.947-2.947-8.658-3.102-8.9-3.107-0.217 0.003-0.372 0.071-0.508 0.204l-19.28 19.283c-0.139 0.139-0.212 0.329-0.204 0.522 0.011 0.236 0.294 5.818 3.232 8.759 2.941 2.939 8.523 3.219 8.759 3.23 0.011 0.003 0.022 0.003 0.033 0.003 0.185 0 0.362-0.073 0.492-0.204l19.279-19.283c0.133-0.133 0.207-0.318 0.204-0.506-0.005-0.242-0.16-5.954-3.107-8.901zM21.064 5.717c0.576 0 1.044 0.465 1.044 1.044 0 0.576-0.468 1.044-1.044 1.044s-1.044-0.468-1.044-1.044c0-0.579 0.468-1.044 1.044-1.044zM7.841 25.204c-0.576 0-1.044-0.468-1.044-1.044s0.468-1.044 1.044-1.044c0.579 0 1.044 0.467 1.044 1.044s-0.465 1.044-1.044 1.044zM7.841 21.028c-0.576 0-1.044-0.468-1.044-1.044s0.468-1.044 1.044-1.044c0.579 0 1.044 0.468 1.044 1.044s-0.465 1.044-1.044 1.044zM12.016 25.204c-0.576 0-1.044-0.468-1.044-1.044s0.468-1.044 1.044-1.044c0.579 0 1.044 0.467 1.044 1.044s-0.465 1.044-1.044 1.044zM17.649 21.493c-0.136 0.136-0.315 0.204-0.492 0.204-0.179 0-0.356-0.068-0.492-0.204l-6.157-6.158c-0.272-0.272-0.272-0.712 0-0.984s0.712-0.272 0.984 0l6.157 6.158c0.272 0.272 0.272 0.712 0 0.984zM22.572 16.567c-0.136 0.136-0.313 0.204-0.492 0.204-0.177 0-0.356-0.068-0.492-0.204l-6.155-6.155c-0.272-0.275-0.272-0.712 0-0.984 0.272-0.275 0.712-0.275 0.984 0l6.155 6.155c0.275 0.272 0.275 0.712 0 0.984zM25.239 11.981c-0.576 0-1.044-0.468-1.044-1.044 0-0.579 0.468-1.044 1.044-1.044s1.044 0.465 1.044 1.044c0 0.576-0.465 1.044-1.044 1.044zM25.239 7.805c-0.576 0-1.044-0.468-1.044-1.044 0-0.579 0.468-1.044 1.044-1.044s1.044 0.465 1.044 1.044c0 0.576-0.465 1.044-1.044 1.044z"></path>
</svg>',
            'open_ai' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M13.494 17.431l2.512 1.428 2.517-1.428v-2.857l-2.508-1.428-2.517 1.428z"></path>
<path d="M12.996 11.429c-0.011-0.006-0.022-0.008-0.034-0.008s-0.024 0.003-0.034 0.008l-4.664 2.654c-0.995 0.569-1.722 1.504-2.019 2.6s-0.143 2.264 0.43 3.248v-0.022c0.513 0.873 1.322 1.538 2.284 1.879v-5.407c-0.002-0.131 0.032-0.259 0.098-0.373s0.161-0.207 0.277-0.272l5.614-3.195-1.95-1.113z"></path>
<path d="M7.51 12.785l4.618-2.627c0.114-0.066 0.244-0.101 0.377-0.101s0.263 0.035 0.377 0.101l5.641 3.208v-2.221c-0.001-0.012-0.004-0.023-0.009-0.033s-0.013-0.019-0.023-0.026l-4.673-2.658c-0.997-0.567-2.182-0.72-3.293-0.426s-2.059 1.011-2.636 1.994c-0.509 0.867-0.692 1.883-0.516 2.87l0.137-0.081z"></path>
<path d="M12.818 22.23c-0.115-0.066-0.21-0.161-0.277-0.275s-0.102-0.243-0.103-0.374l-0.005-6.403-1.95 1.108c-0.010 0.006-0.018 0.014-0.024 0.023s-0.011 0.020-0.012 0.031v5.303c0.001 0.814 0.237 1.61 0.68 2.296s1.075 1.234 1.822 1.579c0.747 0.345 1.578 0.473 2.396 0.369s1.589-0.435 2.223-0.955l-0.137-0.077-4.613-2.627z"></path>
<path d="M19.19 9.762c0.115 0.066 0.21 0.161 0.277 0.275s0.102 0.243 0.103 0.374v6.416l1.95-1.113c0.010-0.005 0.018-0.012 0.024-0.020s0.011-0.019 0.012-0.029v-5.317c-0.002-1.135-0.46-2.222-1.274-3.024s-1.916-1.254-3.066-1.256c-1.015-0.002-1.998 0.349-2.777 0.991l0.137 0.077 4.613 2.627z"></path>
<path d="M27.429 0h-22.857c-2.525 0-4.571 2.047-4.571 4.571v22.857c0 2.525 2.047 4.571 4.571 4.571h22.857c2.525 0 4.571-2.047 4.571-4.571v-22.857c0-2.525-2.047-4.571-4.571-4.571zM27.396 14.823c-0.13 1.21-0.644 2.348-1.469 3.253 0.259 0.77 0.349 1.585 0.263 2.391s-0.345 1.586-0.761 2.285c-0.616 1.058-1.557 1.895-2.686 2.391s-2.39 0.626-3.599 0.37c-0.687 0.754-1.563 1.316-2.54 1.63s-2.020 0.369-3.026 0.16c-1.005-0.21-1.937-0.676-2.702-1.354s-1.335-1.541-1.654-2.504c-0.806-0.163-1.567-0.494-2.232-0.97s-1.221-1.087-1.628-1.792c-0.623-1.056-0.889-2.28-0.76-3.495s0.646-2.358 1.477-3.264c-0.26-0.769-0.351-1.584-0.265-2.391s0.344-1.586 0.759-2.286c0.617-1.058 1.558-1.896 2.689-2.392s2.391-0.626 3.601-0.37c0.546-0.606 1.216-1.091 1.967-1.421s1.564-0.499 2.386-0.494c1.239-0.001 2.447 0.386 3.449 1.106s1.745 1.735 2.124 2.899c0.805 0.163 1.566 0.494 2.232 0.97s1.221 1.087 1.628 1.792c0.615 1.054 0.877 2.274 0.747 3.484z"></path>
<path d="M25.033 11.62c-0.497-0.647-1.171-1.141-1.942-1.425v5.407c-0.004 0.13-0.042 0.258-0.111 0.369s-0.166 0.203-0.282 0.266l-5.632 3.226 1.946 1.109c0.011 0.006 0.022 0.008 0.034 0.008s0.024-0.003 0.034-0.008l4.664-2.658c0.713-0.406 1.294-1.003 1.676-1.723s0.548-1.531 0.48-2.341c-0.069-0.809-0.369-1.583-0.866-2.23z"></path>
<path d="M24.639 19.116l-0.137 0.081-4.609 2.649c-0.115 0.067-0.246 0.102-0.379 0.102s-0.264-0.035-0.379-0.102l-5.636-3.208v2.221c-0.001 0.011 0.001 0.023 0.006 0.033s0.012 0.019 0.022 0.026l4.664 2.654c0.715 0.406 1.532 0.603 2.356 0.568s1.621-0.301 2.298-0.767c0.677-0.465 1.205-1.111 1.523-1.862s0.413-1.575 0.273-2.377l-0-0.018z"></path>
</svg>',
            'customize' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M11.622 31.92l-0.637-5.094c-0.345-0.133-0.67-0.292-0.975-0.478s-0.604-0.385-0.896-0.597l-4.736 1.99-4.378-7.562 4.099-3.104c-0.027-0.186-0.040-0.365-0.040-0.537v-1.075c0-0.172 0.013-0.352 0.040-0.537l-4.099-3.104 4.378-7.562 4.736 1.99c0.292-0.212 0.597-0.411 0.915-0.597s0.637-0.345 0.955-0.478l0.637-5.095h8.756l0.637 5.095c0.345 0.133 0.67 0.292 0.975 0.478s0.604 0.385 0.896 0.597l4.736-1.99 4.378 7.562-4.1 3.104c0.027 0.186 0.040 0.365 0.040 0.537v1.075c0 0.172-0.027 0.352-0.080 0.537l4.1 3.104-4.378 7.562-4.696-1.99c-0.292 0.212-0.597 0.411-0.915 0.597s-0.637 0.345-0.955 0.478l-0.637 5.094h-8.756zM16.080 21.572c1.539 0 2.852-0.544 3.94-1.632s1.632-2.401 1.632-3.94-0.544-2.852-1.632-3.94c-1.088-1.088-2.401-1.632-3.94-1.632-1.566 0-2.886 0.544-3.96 1.632s-1.612 2.401-1.612 3.94 0.537 2.852 1.612 3.94c1.075 1.088 2.395 1.632 3.96 1.632z"></path>
</svg>',
            'email_builder' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M3.2 28.8c-0.88 0-1.633-0.313-2.26-0.94s-0.94-1.38-0.94-2.26v-19.2c0-0.88 0.313-1.633 0.94-2.26s1.38-0.94 2.26-0.94h25.6c0.88 0 1.633 0.313 2.26 0.94s0.94 1.38 0.94 2.26v19.2c0 0.88-0.313 1.633-0.94 2.26s-1.38 0.94-2.26 0.94h-25.6zM16 17.6l12.8-8v-3.2l-12.8 8-12.8-8v3.2l12.8 8z"></path>
</svg>',
            'sales_booster' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M0 32v-4.444l4.571-3.556v8h-4.571zM6.857 32v-10.667l3.81-3.556v14.222h-3.81zM13.714 32v-14.222l4.063 3.6v10.622h-4.064zM20.571 32v-10.622l4.317-3.556v14.178h-4.317zM27.429 32v-17.778l4.571-3.937v21.714h-4.571zM0 24.635v-6.857l12.444-12.444 7.111 7.111 12.444-12.444v6.857l-12.444 12.444-7.111-7.111-12.444 12.444z"></path>
</svg>',
            'custom_fonts' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M0 32v-8.667h2.933v-14.667h-2.933v-8.667h8.667v2.933h14.533v-2.933h8.8v8.667h-2.933v14.533h2.933v8.8h-8.667v-2.933h-14.667v2.933h-8.667zM8.667 26.133h14.533v-2.933h2.933v-14.533h-2.933v-2.8h-14.533v2.933h-2.8v14.533h2.933v2.8h-0.133zM9.867 21.867l4.933-13.067h2.267l4.933 13.067h-2.267l-1.067-3.333h-5.333l-1.2 3.333h-2.267zM14 16.533h3.867l-1.867-5.467h-0.133l-1.867 5.467z"></path>
</svg>',
            'social' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M8.727 24.727c-2.424 0-4.485-0.848-6.182-2.545s-2.545-3.758-2.545-6.182c0-2.424 0.849-4.485 2.545-6.182s3.758-2.545 6.182-2.545c1.964 0 3.679 0.551 5.145 1.655s2.491 2.491 3.073 4.164h15.055v5.818h-2.909v5.818h-5.818v-5.818h-6.327c-0.582 1.673-1.606 3.061-3.073 4.164s-3.182 1.655-5.145 1.655zM8.727 18.909c0.8 0 1.485-0.285 2.055-0.855s0.855-1.255 0.855-2.055c0-0.8-0.285-1.485-0.855-2.055s-1.255-0.855-2.055-0.855-1.485 0.285-2.055 0.855-0.855 1.255-0.855 2.055c0 0.8 0.285 1.485 0.855 2.055s1.255 0.855 2.055 0.855z"></path>
</svg>',
            'support' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M16 32c-2.213 0-4.293-0.42-6.24-1.26s-3.64-1.98-5.080-3.42c-1.44-1.44-2.58-3.133-3.42-5.080s-1.26-4.027-1.26-6.24 0.42-4.293 1.26-6.24 1.98-3.64 3.42-5.080 3.133-2.58 5.080-3.42 4.027-1.26 6.24-1.26 4.293 0.42 6.24 1.26c1.947 0.84 3.64 1.98 5.080 3.42s2.58 3.133 3.42 5.080 1.26 4.027 1.26 6.24-0.42 4.293-1.26 6.24c-0.84 1.947-1.98 3.64-3.42 5.080s-3.133 2.58-5.080 3.42c-1.947 0.84-4.027 1.26-6.24 1.26zM11.36 27.92l1.92-4.4c-1.12-0.4-2.087-1.020-2.9-1.86s-1.447-1.82-1.9-2.94l-4.4 1.84c0.613 1.707 1.56 3.2 2.84 4.48s2.76 2.24 4.44 2.88zM8.48 13.28c0.453-1.12 1.087-2.1 1.9-2.94s1.78-1.46 2.9-1.86l-1.84-4.4c-1.707 0.64-3.2 1.6-4.48 2.88s-2.24 2.773-2.88 4.48l4.4 1.84zM16 20.8c1.333 0 2.467-0.467 3.4-1.4s1.4-2.067 1.4-3.4-0.467-2.467-1.4-3.4-2.067-1.4-3.4-1.4-2.467 0.467-3.4 1.4-1.4 2.067-1.4 3.4 0.467 2.467 1.4 3.4 2.067 1.4 3.4 1.4zM20.64 27.92c1.68-0.64 3.153-1.593 4.42-2.86s2.22-2.74 2.86-4.42l-4.4-1.92c-0.4 1.12-1.013 2.087-1.84 2.9s-1.787 1.447-2.88 1.9l1.84 4.4zM23.52 13.2l4.4-1.84c-0.64-1.68-1.593-3.153-2.86-4.42s-2.74-2.22-4.42-2.86l-1.84 4.48c1.093 0.4 2.040 1.007 2.84 1.82s1.427 1.753 1.88 2.82z"></path>
</svg>',
            'changelog' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M8.889 24.889c0.504 0 0.926-0.17 1.267-0.511s0.511-0.763 0.511-1.267c0-0.504-0.17-0.926-0.511-1.267s-0.763-0.511-1.267-0.511c-0.504 0-0.926 0.17-1.267 0.511s-0.511 0.763-0.511 1.267c0 0.504 0.17 0.926 0.511 1.267s0.763 0.511 1.267 0.511zM8.889 17.778c0.504 0 0.926-0.17 1.267-0.511s0.511-0.763 0.511-1.267-0.17-0.926-0.511-1.267c-0.341-0.341-0.763-0.511-1.267-0.511s-0.926 0.17-1.267 0.511c-0.341 0.341-0.511 0.763-0.511 1.267s0.17 0.926 0.511 1.267c0.341 0.341 0.763 0.511 1.267 0.511zM8.889 10.667c0.504 0 0.926-0.17 1.267-0.511s0.511-0.763 0.511-1.267c0-0.504-0.17-0.926-0.511-1.267s-0.763-0.511-1.267-0.511c-0.504 0-0.926 0.17-1.267 0.511s-0.511 0.763-0.511 1.267c0 0.504 0.17 0.926 0.511 1.267s0.763 0.511 1.267 0.511zM14.222 24.889h10.667v-3.556h-10.667v3.556zM14.222 17.778h10.667v-3.556h-10.667v3.556zM14.222 10.667h10.667v-3.556h-10.667v3.556zM3.556 32c-0.978 0-1.815-0.348-2.511-1.044s-1.044-1.534-1.044-2.511v-24.889c0-0.978 0.348-1.815 1.044-2.511s1.533-1.044 2.511-1.044h24.889c0.978 0 1.815 0.348 2.511 1.044s1.044 1.533 1.044 2.511v24.889c0 0.978-0.348 1.815-1.044 2.511s-1.534 1.044-2.511 1.044h-24.889z"></path>
</svg>',
        );
        $icons['sponsors'] = $icons['support']; // keep same icon
        $icons['update'] = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M12,0C5.3759766,0,0,5.3759766,0,12s5.3759766,12,12,12s12-5.3759766,12-12S18.6240234,0,12,0z M12,19.2000008
	c-3.9719973,0-7.1999998-3.2280035-7.1999998-7.2000008h2.3999996c0,2.6520262,2.147974,4.7999992,4.8000002,4.7999992
	S16.7999992,14.6520262,16.7999992,12S14.6520262,7.1999998,12,7.1999998v3.6000004L7.1999998,6L12,1.2v3.6000001
	c3.9719973,0,7.2000008,3.2280025,7.2000008,7.1999998S15.9719973,19.2000008,12,19.2000008z"/>
</svg>';
        $icons['question_mark'] = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M12,0C5.3759766,0,0,5.3759766,0,12s5.3759766,12,12,12s12-5.3759766,12-12S18.6240234,0,12,0z M13.1999998,20.3999996
	h-2.3999996V18h2.3999996V20.3999996z M15.6840086,11.1000004l-1.0800295,1.1039791
	c-0.8639641,0.87605-1.4039793,1.5960207-1.4039793,3.3960209h-2.3999996V15c0-1.3199711,0.5400143-2.5199709,1.4039793-3.3960209
	l1.4879885-1.5120115c0.444067-0.431982,0.7080317-1.0319824,0.7080317-1.691968C14.3999996,7.0800295,13.3199711,6,12,6
	S9.6000004,7.0800295,9.6000004,8.3999996H7.1999998c0-2.6520262,2.147974-4.7999997,4.8000002-4.7999997
	s4.7999992,2.1479735,4.7999992,4.7999997C16.7999992,9.4560061,16.3680172,10.4159908,15.6840086,11.1000004z"/>
</svg>';
        $icons['static_blocks'] = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M14.3999996,0c-0.8824997,0-1.5999994,0.7175-1.5999994,1.6v8c0,0.8824997,0.7174997,1.5999994,1.5999994,1.5999994h8
	C23.2824993,11.1999998,24,10.4825001,24,9.6000004v-8C24,0.7175,23.2824993,0,22.3999996,0H14.3999996z M18.3999996,2.8
	c1.5438995,0,2.8000011,1.2560999,2.8000011,2.8s-1.2561016,2.7999997-2.8000011,2.7999997
	s-2.7999992-1.2560992-2.7999992-2.7999997S16.8561001,2.8,18.3999996,2.8z M18.3999996,3.5999999c-1.1045704,0-2,0.8954306-2,2
	s0.8954296,2,2,2s2-0.8954306,2-2S19.50457,3.5999999,18.3999996,3.5999999z M1.6,12.8000002c-0.8825,0-1.6,0.7174997-1.6,1.5999994
	v8C0,23.2824993,0.7175,24,1.6,24h8c0.8824997,0,1.5999994-0.7175007,1.5999994-1.6000004v-8
	c0-0.8824997-0.7174997-1.5999994-1.5999994-1.5999994H1.6z M14.3999996,12.8000002
	c-0.8824997,0-1.5999994,0.7174997-1.5999994,1.5999994v8C12.8000002,23.2824993,13.5174999,24,14.3999996,24h8
	C23.2824993,24,24,23.2824993,24,22.3999996v-8c0-0.8824997-0.7175007-1.5999994-1.6000004-1.5999994H14.3999996z
	 M18.4000969,15.4502926c0.3666134,0.0000448,0.7332077,0.1909809,0.9215832,0.5726566l0.2798824,0.5671883
	c0.0331993,0.0671997,0.0973816,0.1138077,0.1715813,0.124609l0.6258793,0.0909176
	c0.842701,0.1224499,1.1791306,1.1589241,0.5695324,1.7531242l-0.4528332,0.4415054
	c-0.053751,0.0523491-0.0781765,0.1276627-0.065527,0.201561l0.1069336,0.6234379
	c0.144001,0.8393993-0.7377586,1.4794979-1.4913082,1.0833988l-0.559864-0.2943363
	c-0.0664005-0.0348988-0.1455631-0.0348988-0.2119141,0l-0.5598621,0.2944336
	c-0.7538509,0.3963509-1.6352596-0.2444458-1.4913101-1.0834961l0.1069336-0.6234379
	c0.0126495-0.0738983-0.0117283-0.1492119-0.0654297-0.201561l-0.4529285-0.4415054
	c-0.6097994-0.5943985-0.272872-1.6306229,0.5696278-1.7531242l0.6258793-0.0909176
	c0.0742016-0.0107498,0.1384335-0.0573597,0.1715832-0.124609l0.2798824-0.5671883
	C17.666893,15.6410999,18.0334854,15.4502487,18.4000969,15.4502926z M5.5998049,15.5597658
	c0.4041119-0.0001135,0.8082304,0.1998453,1.0393553,0.6001959l1.8706055,3.2400379
	c0.4618502,0.7998009-0.1146603,1.8000011-1.0391603,1.8000011H3.7293944c-0.9235499,0-1.5015578-0.9993515-1.0392578-1.8000011
	l1.8706057-3.2400379C4.7916174,15.7600613,5.1956921,15.5598783,5.5998049,15.5597658z M18.4000969,16.2498055
	c-0.0812054-0.000082-0.1624451,0.0421982-0.2043934,0.1271477l-0.2798824,0.5672855
	c-0.1497498,0.3034-0.4390297,0.5135059-0.7738285,0.5622063l-0.6259766,0.0909176
	c-0.1868,0.0271511-0.2619686,0.2563248-0.1262703,0.3885746l0.4529305,0.4415035
	c0.2422504,0.2361012,0.3527546,0.57617,0.2956047,0.909668l-0.1069336,0.6234379
	c-0.0319996,0.1863995,0.1630192,0.3281384,0.3304691,0.2401371l0.559864-0.2943363
	c0.299448-0.1574497,0.6570911-0.1574497,0.956543,0l0.5598621,0.2943363
	c0.1670513,0.0878487,0.3625183-0.0533867,0.3304691-0.2401371l-0.1069336-0.6234379
	c-0.0572014-0.3335495,0.0533047-0.6735172,0.2956047-0.909668l0.4528332-0.4415035
	c0.1357994-0.1322994,0.0607758-0.3614235-0.1260738-0.3885746l-0.6259766-0.0909176
	c-0.3348007-0.0486488-0.6240788-0.2588558-0.7738285-0.5622063l-0.2798824-0.5672855
	C18.562521,16.2923279,18.4813042,16.2498856,18.4000969,16.2498055z M5.6000977,16.3598633
	c-0.1346879-0.0000744-0.2693839,0.0665226-0.3464842,0.200098l-1.8706057,3.2400379
	c-0.1541998,0.2670517,0.0385368,0.6000004,0.3463867,0.6000004h3.7412109c0.3083506,0,0.5003366-0.3333988,0.3463869-0.6000004
	l-1.8706059-3.2400379C5.8694367,16.4266853,5.7347851,16.3599377,5.6000977,16.3598633z"/>
</svg>';
        $icons['widgets'] = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M22.632 18.736l-9.368-9.368 9.368-9.368 9.368 9.368-9.368 9.368zM0 15.42v-13.264h13.264v13.264h-13.264zM16.58 32v-13.264h13.264v13.264h-13.264zM0 32v-13.264h13.264v13.264h-13.264z"></path>
</svg>';
        $icons['speed_optimization'] = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M13.536 21.607c0.667 0.667 1.508 0.981 2.523 0.941s1.749-0.407 2.203-1.101l8.97-13.456-13.456 8.97c-0.694 0.481-1.075 1.208-1.141 2.183s0.234 1.795 0.901 2.463zM4.966 28.815c-0.587 0-1.128-0.127-1.622-0.38s-0.888-0.634-1.181-1.141c-0.694-1.255-1.228-2.556-1.602-3.905s-0.561-2.743-0.561-4.185c0-2.216 0.42-4.298 1.261-6.247s1.982-3.644 3.424-5.086c1.442-1.442 3.137-2.583 5.086-3.424s4.031-1.261 6.247-1.261c2.189 0 4.245 0.414 6.167 1.241s3.604 1.956 5.046 3.384c1.442 1.428 2.59 3.097 3.444 5.006s1.295 3.958 1.322 6.147c0.027 1.468-0.14 2.903-0.501 4.305s-0.915 2.743-1.662 4.025c-0.294 0.507-0.688 0.888-1.181 1.141s-1.035 0.38-1.622 0.38h-22.066z"></path>
</svg>';
        $icons['more'] = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M3,9c-1.6499999,0-3,1.3499994-3,3s1.3500001,3,3,3s3-1.3499994,3-3S4.6499996,9,3,9z M21,9c-1.6500015,0-3,1.3499994-3,3
	s1.3499985,3,3,3s3-1.3499994,3-3S22.6500015,9,21,9z M12,9c-1.6500006,0-3,1.3499994-3,3s1.3499994,3,3,3s3-1.3499994,3-3
	S13.6500006,9,12,9z"/>
</svg>';
        $icons['amp'] = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<path d="M16 0c-8.84 0-16 7.16-16 16s7.16 16 16 16 16-7.16 16-16-7.16-16-16-16zM21.333 15.267l-5.387 9.080c-0.147 0.24-0.373 0.387-0.613 0.387-0.427 0-0.773-0.413-0.773-0.92v-0.093l0.56-6.267h-2.72c-0.413 0-0.76-0.4-0.76-0.907 0-0.2 0.053-0.387 0.147-0.547l4.76-8.347c0.16-0.24 0.387-0.387 0.627-0.387 0.427 0 0.773 0.413 0.773 0.92v0.107l-0.56 5.52h3.333c0.413 0 0.76 0.413 0.76 0.92 0 0.187-0.053 0.387-0.147 0.533z"></path>
</svg>';
        $icons['etheme_slides'] = $icons['demos'];
        $icons['etheme_mega_menus'] = $icons['widgets'];
        return $icons;
    }

    public function get_theme_builders_menu_icons() {
        $icons = array(
            'header' => '<svg width="1.3em" height="1.3em" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V1.45455H0.727273V14.5455H0V16H5.81818V14.5455H5.09091V10.1818H9.45455V14.5455H8.72727V16H14.5455V14.5455H13.8182V1.45455H14.5455V0H8.72727V1.45455H9.45455V5.81818H5.09091V1.45455H5.81818V0H0ZM2.18182 1.45455H3.63636V7.27273H10.9091V1.45455H12.3636V14.5455H10.9091V8.72727H3.63636V14.5455H2.18182V1.45455Z" fill="currentColor"/>
                    </svg>',
            'footer' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0V16H16V0H14.5455V14.5455H1.45455V0H0ZM2.90909 0V1.45455H4.36364V0H2.90909ZM5.81818 0V1.45455H7.27273V0H5.81818ZM8.72727 0V1.45455H10.1818V0H8.72727ZM11.6364 0V1.45455H13.0909V0H11.6364ZM2.90909 2.90909V4.36364H13.0909V2.90909H2.90909ZM2.90909 6.54545V7.27273V13.0909H13.0909V6.54545H2.90909ZM4.36364 8H11.6364V11.6364H4.36364V8Z" fill="currentColor"/>
                    </svg>',
            'product' => '<svg width="1.3em" height="1.3em" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.80769 0C5.11779 0 3.73077 1.38702 3.73077 3.07692V3.69231H0.692308L0.653846 4.26923L0.0384615 15.3462L0 16H13.6154L13.5769 15.3462L12.9615 4.26923L12.9231 3.69231H9.88462V3.07692C9.88462 1.38702 8.4976 0 6.80769 0ZM6.80769 1.23077C7.82692 1.23077 8.65385 2.05769 8.65385 3.07692V3.69231H4.96154V3.07692C4.96154 2.05769 5.78846 1.23077 6.80769 1.23077ZM1.84615 4.92308H3.73077V6.76923H4.96154V4.92308H8.65385V6.76923H9.88462V4.92308H11.7692L12.3077 14.7692H1.30769L1.84615 4.92308Z" fill="currentColor"/>
                    </svg>',
            'product-archive' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.6 0V1.95L0 4.08333V4.26667C0 5.14375 0.722917 5.86667 1.6 5.86667V12.8H10.1333V8H11.2V12.8H14.4V5.86667C15.2771 5.86667 16 5.14375 16 4.26667V4.08333L14.4 1.95V0H1.6ZM2.66667 1.06667H13.3333V1.6H2.66667V1.06667ZM2.4 2.66667H13.6L14.8833 4.38333C14.825 4.61458 14.65 4.8 14.4 4.8C14.1042 4.8 13.8667 4.5625 13.8667 4.26667H12.8C12.8 4.5625 12.5625 4.8 12.2667 4.8C11.9708 4.8 11.7333 4.5625 11.7333 4.26667H10.6667C10.6667 4.5625 10.4292 4.8 10.1333 4.8C9.8375 4.8 9.6 4.5625 9.6 4.26667H8.53333C8.53333 4.5625 8.29583 4.8 8 4.8C7.70417 4.8 7.46667 4.5625 7.46667 4.26667H6.4C6.4 4.5625 6.1625 4.8 5.86667 4.8C5.57083 4.8 5.33333 4.5625 5.33333 4.26667H4.26667C4.26667 4.5625 4.02917 4.8 3.73333 4.8C3.4375 4.8 3.2 4.5625 3.2 4.26667H2.13333C2.13333 4.5625 1.89583 4.8 1.6 4.8C1.35 4.8 1.175 4.61458 1.11667 4.38333L2.4 2.66667ZM2.66667 5.45C2.95 5.70625 3.325 5.86667 3.73333 5.86667C4.14167 5.86667 4.51667 5.70625 4.8 5.45C5.08333 5.70625 5.45833 5.86667 5.86667 5.86667C6.275 5.86667 6.65 5.70625 6.93333 5.45C7.21667 5.70625 7.59167 5.86667 8 5.86667C8.40833 5.86667 8.78333 5.70625 9.06667 5.45C9.35 5.70625 9.725 5.86667 10.1333 5.86667C10.5417 5.86667 10.9167 5.70625 11.2 5.45C11.4833 5.70625 11.8583 5.86667 12.2667 5.86667C12.675 5.86667 13.05 5.70625 13.3333 5.45V11.7333H12.2667V6.93333H9.06667V11.7333H2.66667V5.45ZM3.73333 6.93333V10.6667H8V6.93333H3.73333ZM4.8 8H6.93333V9.6H4.8V8Z" fill="currentColor"/>
                    </svg>',
            'myaccount' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.96674 12.4566C3.68253 11.9255 4.46232 11.5061 5.30611 11.1983C6.14975 10.8907 7.04772 10.7368 8 10.7368C8.95228 10.7368 9.85025 10.8907 10.6939 11.1983C11.5377 11.5061 12.3175 11.9255 13.0333 12.4566C13.5568 11.8812 13.9716 11.2151 14.2777 10.4583C14.5838 9.70154 14.7368 8.88211 14.7368 8C14.7368 6.13333 14.0807 4.54386 12.7684 3.23158C11.4561 1.9193 9.86667 1.26316 8 1.26316C6.13333 1.26316 4.54386 1.9193 3.23158 3.23158C1.9193 4.54386 1.26316 6.13333 1.26316 8C1.26316 8.88211 1.41621 9.70154 1.72232 10.4583C2.02842 11.2151 2.44323 11.8812 2.96674 12.4566ZM8.00021 8.63158C7.23137 8.63158 6.58302 8.36772 6.05516 7.84C5.52716 7.31214 5.26316 6.66379 5.26316 5.89495C5.26316 5.12611 5.52702 4.47775 6.05474 3.94989C6.5826 3.42189 7.23095 3.15789 7.99979 3.15789C8.76863 3.15789 9.41698 3.42175 9.94484 3.94947C10.4728 4.47733 10.7368 5.12568 10.7368 5.89453C10.7368 6.66337 10.473 7.31172 9.94526 7.83958C9.4174 8.36758 8.76905 8.63158 8.00021 8.63158ZM8 16C6.88912 16 5.84702 15.7911 4.87368 15.3733C3.90035 14.9554 3.05368 14.3865 2.33368 13.6663C1.61354 12.9463 1.04456 12.0996 0.626737 11.1263C0.208912 10.153 0 9.11088 0 8C0 6.88912 0.208912 5.84702 0.626737 4.87368C1.04456 3.90035 1.61354 3.05368 2.33368 2.33368C3.05368 1.61354 3.90035 1.04456 4.87368 0.626737C5.84702 0.208913 6.88912 0 8 0C9.11088 0 10.153 0.208913 11.1263 0.626737C12.0996 1.04456 12.9463 1.61354 13.6663 2.33368C14.3865 3.05368 14.9554 3.90035 15.3733 4.87368C15.7911 5.84702 16 6.88912 16 8C16 9.11088 15.7911 10.153 15.3733 11.1263C14.9554 12.0996 14.3865 12.9463 13.6663 13.6663C12.9463 14.3865 12.0996 14.9554 11.1263 15.3733C10.153 15.7911 9.11088 16 8 16ZM8 14.7368C8.76 14.7368 9.49277 14.6146 10.1983 14.3701C10.9039 14.1255 11.5303 13.7835 12.0777 13.3442C11.5303 12.9209 10.912 12.5911 10.2227 12.3547C9.53333 12.1182 8.79242 12 8 12C7.20758 12 6.46533 12.1168 5.77326 12.3505C5.08119 12.5844 4.46421 12.9156 3.92232 13.3442C4.46968 13.7835 5.09614 14.1255 5.80168 14.3701C6.50723 14.6146 7.24 14.7368 8 14.7368ZM8 7.36842C8.41895 7.36842 8.76926 7.22751 9.05095 6.94568C9.33277 6.664 9.47368 6.31368 9.47368 5.89474C9.47368 5.47579 9.33277 5.12547 9.05095 4.84379C8.76926 4.56197 8.41895 4.42105 8 4.42105C7.58105 4.42105 7.23074 4.56197 6.94905 4.84379C6.66723 5.12547 6.52632 5.47579 6.52632 5.89474C6.52632 6.31368 6.66723 6.664 6.94905 6.94568C7.23074 7.22751 7.58105 7.36842 8 7.36842Z" fill="currentColor"/>
                    </svg>',
            'cart' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.08421 0L4.73684 3.34737L4.75789 3.36842H4.71579L5.05263 4.71579H14.2526L12.9684 9.43158H5.24211L3.47368 2.35789C3.32368 1.75789 2.78684 1.34737 2.16842 1.34737H0.673684C0.302632 1.34737 0 1.65 0 2.02105C0 2.39211 0.302632 2.69474 0.673684 2.69474H2.16842L3.93684 9.76842C4.08684 10.3684 4.62368 10.7789 5.24211 10.7789H12.9684C13.5763 10.7789 14.0921 10.3763 14.2526 9.78947L16 3.36842H14.8211L11.4526 0L9.76842 1.68421L8.08421 0ZM12.1263 10.7789C11.0184 10.7789 10.1053 11.6921 10.1053 12.8C10.1053 13.9079 11.0184 14.8211 12.1263 14.8211C13.2342 14.8211 14.1474 13.9079 14.1474 12.8C14.1474 11.6921 13.2342 10.7789 12.1263 10.7789ZM6.06316 10.7789C4.95526 10.7789 4.04211 11.6921 4.04211 12.8C4.04211 13.9079 4.95526 14.8211 6.06316 14.8211C7.17105 14.8211 8.08421 13.9079 8.08421 12.8C8.08421 11.6921 7.17105 10.7789 6.06316 10.7789ZM8.08421 1.91579L9.55789 3.36842H6.63158L8.08421 1.91579ZM11.4526 1.91579L12.9053 3.36842H11.4526L10.7368 2.65263L11.4526 1.91579ZM6.06316 12.1263C6.44211 12.1263 6.73684 12.4211 6.73684 12.8C6.73684 13.1789 6.44211 13.4737 6.06316 13.4737C5.68421 13.4737 5.38947 13.1789 5.38947 12.8C5.38947 12.4211 5.68421 12.1263 6.06316 12.1263ZM12.1263 12.1263C12.5053 12.1263 12.8 12.4211 12.8 12.8C12.8 13.1789 12.5053 13.4737 12.1263 13.4737C11.7474 13.4737 11.4526 13.1789 11.4526 12.8C11.4526 12.4211 11.7474 12.1263 12.1263 12.1263Z" fill="currentColor"/>
                    </svg>',
            'checkout' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.646465 0C0.290404 0 0 0.290404 0 0.646465C0 1.00253 0.290404 1.29293 0.646465 1.29293H2.08081L3.77778 8.08081C3.92172 8.65657 4.43687 9.0505 5.0303 9.0505H13.0909C13.6742 9.0505 14.1692 8.66414 14.3232 8.10101L16 1.93939H14.6465L13.0909 7.75758H5.0303L3.33333 0.969697C3.18939 0.393939 2.67424 0 2.08081 0H0.646465ZM12.2828 9.0505C11.2197 9.0505 10.3434 9.92677 10.3434 10.9899C10.3434 12.053 11.2197 12.9293 12.2828 12.9293C13.346 12.9293 14.2222 12.053 14.2222 10.9899C14.2222 9.92677 13.346 9.0505 12.2828 9.0505ZM6.46465 9.0505C5.40152 9.0505 4.52525 9.92677 4.52525 10.9899C4.52525 12.053 5.40152 12.9293 6.46465 12.9293C7.52778 12.9293 8.40404 12.053 8.40404 10.9899C8.40404 9.92677 7.52778 9.0505 6.46465 9.0505ZM9.69697 0.646465V2.58586H6.46465V3.87879H9.69697V5.81818L12.2828 3.23232L9.69697 0.646465ZM6.46465 10.3434C6.82828 10.3434 7.11111 10.6263 7.11111 10.9899C7.11111 11.3535 6.82828 11.6364 6.46465 11.6364C6.10101 11.6364 5.81818 11.3535 5.81818 10.9899C5.81818 10.6263 6.10101 10.3434 6.46465 10.3434ZM12.2828 10.3434C12.6465 10.3434 12.9293 10.6263 12.9293 10.9899C12.9293 11.3535 12.6465 11.6364 12.2828 11.6364C11.9192 11.6364 11.6364 11.3535 11.6364 10.9899C11.6364 10.6263 11.9192 10.3434 12.2828 10.3434Z" fill="currentColor"/>
                    </svg>',
            'archive' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.66667 0V4.66667H4V1.33333H12V4.66667H13.3333V0H2.66667ZM5.33333 2.66667V4H10.6667V2.66667H5.33333ZM0.666667 5.33333V6.66667H0V8H0.666667V14C0.666667 15.0964 1.57031 16 2.66667 16H13.3333C14.4297 16 15.3333 15.0964 15.3333 14V8H16V6.66667H15.3333V5.33333H8.66667V6.66667H10V7.33333H6V6.66667H7.33333V5.33333H0.666667ZM2 6.66667H4.66667V8.66667H11.3333V6.66667H14V14C14 14.375 13.7083 14.6667 13.3333 14.6667H2.66667C2.29167 14.6667 2 14.375 2 14V6.66667ZM4 10C3.63281 10 3.33333 10.2995 3.33333 10.6667C3.33333 11.0339 3.63281 11.3333 4 11.3333C4.36719 11.3333 4.66667 11.0339 4.66667 10.6667C4.66667 10.2995 4.36719 10 4 10ZM6.66667 10C6.29948 10 6 10.2995 6 10.6667C6 11.0339 6.29948 11.3333 6.66667 11.3333C7.03385 11.3333 7.33333 11.0339 7.33333 10.6667C7.33333 10.2995 7.03385 10 6.66667 10ZM9.33333 10C8.96615 10 8.66667 10.2995 8.66667 10.6667C8.66667 11.0339 8.96615 11.3333 9.33333 11.3333C9.70052 11.3333 10 11.0339 10 10.6667C10 10.2995 9.70052 10 9.33333 10ZM12 10C11.6328 10 11.3333 10.2995 11.3333 10.6667C11.3333 11.0339 11.6328 11.3333 12 11.3333C12.3672 11.3333 12.6667 11.0339 12.6667 10.6667C12.6667 10.2995 12.3672 10 12 10ZM5.33333 12C4.96615 12 4.66667 12.2995 4.66667 12.6667C4.66667 13.0339 4.96615 13.3333 5.33333 13.3333C5.70052 13.3333 6 13.0339 6 12.6667C6 12.2995 5.70052 12 5.33333 12ZM8 12C7.63281 12 7.33333 12.2995 7.33333 12.6667C7.33333 13.0339 7.63281 13.3333 8 13.3333C8.36719 13.3333 8.66667 13.0339 8.66667 12.6667C8.66667 12.2995 8.36719 12 8 12ZM10.6667 12C10.2995 12 10 12.2995 10 12.6667C10 13.0339 10.2995 13.3333 10.6667 13.3333C11.0339 13.3333 11.3333 13.0339 11.3333 12.6667C11.3333 12.2995 11.0339 12 10.6667 12Z" fill="currentColor"/>
                    </svg>',
            'single-post' => '<svg width="1.3em" height="1.3em" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.46154 0V6.76923L2.55769 6.92308L3.32692 8.19231H3.30769L4.30769 9.84615L5.30769 8.19231H5.28846L6.05769 6.92308L6.15385 6.76923V3.07692H11.6923C12.0313 3.07692 12.3077 3.35337 12.3077 3.69231V9.84615H4.30769L5.51923 11.8654L5.36538 11.9808L3.69231 13.3654V11.6923H1.84615C1.50721 11.6923 1.23077 11.4159 1.23077 11.0769V3.69231C1.23077 3.35337 1.50721 3.07692 1.84615 3.07692V1.84615C0.826923 1.84615 0 2.67308 0 3.69231V11.0769C0 12.0962 0.826923 12.9231 1.84615 12.9231H2.46154V16L6.15385 12.9231H11.6923C12.7115 12.9231 13.5385 12.0962 13.5385 11.0769V3.69231C13.5385 2.67308 12.7115 1.84615 11.6923 1.84615H6.15385V0H2.46154ZM3.69231 1.23077H4.92308V5.09615L4.5 4.96154L4.30769 4.88462L4.11538 4.96154L3.69231 5.09615V1.23077ZM4.30769 6.17308L4.92308 6.38462V6.44231L4.30769 7.46154L3.69231 6.44231V6.38462L4.30769 6.17308Z" fill="currentColor"/>
                    </svg>',
            'error-404' => '<svg width="1.3em" height="1.3em" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 0L0 13.8564H1.00924H16L8 0ZM3.20751 0.670175L2.30636 1.40976L3.76276 3.18248L4.66392 2.44176L3.20751 0.670175ZM12.7925 0.670175L11.3361 2.44176L12.2372 3.18248L13.6936 1.40976L12.7925 0.670175ZM8 2.32911L13.9826 12.6912H2.01735L8 2.32911ZM1.29256 2.86048L0.725928 3.87996L2.71825 4.98478L3.28374 3.96643L1.29256 2.86048ZM14.7074 2.86048L12.7163 3.96643L13.2818 4.98478L15.2741 3.87996L14.7074 2.86048ZM0.426682 5.70047V6.8656H2.75693V5.70047H0.426682ZM13.2431 5.70047V6.8656H15.5733V5.70047H13.2431ZM7.41744 6.28303V9.77841H8.58256V6.28303H7.41744ZM7.41744 10.361V11.5261H8.58256V10.361H7.41744Z" fill="currentColor"/>
                </svg>',
        );
        $icons['header-old'] = $icons['header'];
        $icons['product-old'] = $icons['product'];
        $icons['search-results'] = '<svg width="1.3em" height="1.3em" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6.19074 12.2571C6.45418 12.2571 6.71004 12.2201 6.95832 12.1461C7.2066 12.0721 7.43874 11.9666 7.65474 11.8295L9.83116 14.0061L10.7185 13.1185L8.54211 10.9421C8.69004 10.7316 8.79825 10.5008 8.86674 10.2497C8.93537 9.99874 8.96968 9.74154 8.96968 9.47811C8.96968 8.72344 8.70351 8.08568 8.17116 7.56484C7.63895 7.04386 6.99284 6.78337 6.23284 6.78337C5.4727 6.78337 4.82653 7.04947 4.29432 7.58168C3.76211 8.11404 3.496 8.76021 3.496 9.52021C3.496 10.2802 3.75642 10.9264 4.27726 11.4587C4.79825 11.9909 5.43607 12.2571 6.19074 12.2571ZM6.23284 10.9939C5.82793 10.9939 5.48105 10.8495 5.19221 10.5606C4.90351 10.2719 4.75916 9.92512 4.75916 9.52021C4.75916 9.1153 4.90351 8.76849 5.19221 8.47979C5.48105 8.19095 5.82793 8.04653 6.23284 8.04653C6.63761 8.04653 6.98442 8.19095 7.27326 8.47979C7.56211 8.76849 7.70653 9.1153 7.70653 9.52021C7.70653 9.92512 7.56211 10.2719 7.27326 10.5606C6.98442 10.8495 6.63761 10.9939 6.23284 10.9939ZM2.02232 16.5C1.59691 16.5 1.23684 16.3526 0.942105 16.0579C0.647368 15.7632 0.5 15.4031 0.5 14.9777V2.02232C0.5 1.59691 0.647368 1.23684 0.942105 0.942105C1.23684 0.647368 1.59691 0.5 2.02232 0.5H8.71053L13.1316 4.92105V14.9777C13.1316 15.4031 12.9842 15.7632 12.6895 16.0579C12.3947 16.3526 12.0347 16.5 11.6093 16.5H2.02232ZM8.07895 5.55263V1.76316H2.02232C1.95747 1.76316 1.89811 1.79017 1.84421 1.84421C1.79018 1.8981 1.76316 1.95747 1.76316 2.02232V14.9777C1.76316 15.0425 1.79018 15.1019 1.84421 15.1558C1.89811 15.2098 1.95747 15.2368 2.02232 15.2368H11.6093C11.6741 15.2368 11.7335 15.2098 11.7874 15.1558C11.8414 15.1019 11.8684 15.0425 11.8684 14.9777V5.55263H8.07895Z" fill="currentColor"></path>
</svg>';
        return $icons;
    }

    public function get_notices(){

        $this->notices = (object) array(
            'main' => false,
            'system_requirements' => false,
            'theme_update' => false,
            'theme_activate' => false,
        );

        // temporary disable setter for notices
        // @todo remove when alerts will be fully redesigned
        return;

        if (! $this->is_system_requirements){
            $this->notices->main = $this->notices->system_requirements = $this->notice('warning', __( 'Upgrade Your System Requirements', 'xstore-core' ) );
        }

        if ($this->is_update_available){
            $this->notices->main = $this->notices->theme_update = $this->notice('warning-light', __( 'Update Available', 'xstore-core' ) );
        }

        if (!$this->is_theme_active ){
            $this->notices->main = $this->notices->theme_activate = $this->notice('warning', __( 'Theme Isn\'t Registered', 'xstore-core' ) );
        }

    }

    public function notice($type = 'warning', $tooltip = false){
        if (! $this->is_admin){
            $tooltip = false;
        }
        return $this->icon($type, $tooltip );
    }

    public function get_support_status(){
	    $activated_data = get_option( 'etheme_activated_data' );
	    $supported_until = ( isset( $activated_data['item'] ) && isset( $activated_data['item']['supported_until'] ) && ! empty( $activated_data['item']['supported_until'] ) ) ? $activated_data['item']['supported_until'] : '';
	    $daysleft = round(((( strtotime($supported_until) - time() )/24)/60)/60);

	    return ( $daysleft > 0 );
    }

    public function icon($type = 'warning', $tooltip = false){
        $class = ($tooltip) ? 'mtips mtips-right': '';

        if ($type == 'warning'){
            $color = 'var(--et_admin_orange-color, #f57f17)';
        } else {
            $color = 'var(--et_admin_green-color, #f57f17)';
        }

        $text = '<span class="awaiting-mod '.$class.'" style="position: relative;min-width: 16px;height: 16px;margin-inline-start: 7px;background: #fff;line-height: 1;display: inline-block;width: 10px;height: 10px;min-width: unset;">';
        $text .= '<span class="dashicons dashicons-warning" style="width: auto;height: auto;font-size: 20px;font-family: dashicons;line-height: 1;border-radius: 50%;color: '.$color.';position: absolute;top: -5px;left: -5px;"></span>';
        if ( $tooltip ) {
            $text .= $this->tooltip($tooltip);
        }
        $text .= '</span>';

        return $text;
    }

    public function tooltip($text = 'Warning: Empty tooltip!'){
        return'<span class="mt-mes" style="line-height: 1; margin-top: -13px; border-radius: 3px;">' . $text . '</span>';
    }

    public function label(){
        return (object) [
            'new' => '<span class="et-tbm-label et-tbm-label-new">'.esc_html__('new', 'xstore-core').'</span>',
            'hot' => '<span class="et-tbm-label et-tbm-label-hot">'.esc_html__('hot', 'xstore-core').'</span>',
            'beta' => '<span class="et-tbm-label et-tbm-label-beta">'.esc_html__('beta', 'xstore-core').'</span>',
            'deprecated' => '<span class="et-tbm-label et-tbm-label-deprecated">'.esc_html__('deprecated', 'xstore-core').'</span>',
        ];
    }

    private function style(){
        return '<style id="et-tbm-styles">
        #wp-admin-bar-et-top-bar-general-menu li#wp-admin-bar-et-theme-settings .ab-sub-wrapper .ab-submenu, .js #adminmenu #toplevel_page_et-panel-theme-options .et_top-bar-mega-menu-copy {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            width: 840px;
            align-content: space-between;
            height: 220px;
        }
        #wp-admin-bar-et-top-bar-general-menu li .ab-sub-wrapper .ab-submenu{
            padding: 10px;
        }
        .et_adm-mega-menu-holder{
            position: relative;
        }
        
        .et_adm-mega-menu-holder > a:after {
            content: "\f139";
            font: normal 20px/1 dashicons;
            position: absolute;
            top: 0;
            right: 0;
            speak: never;
            padding: 5px 12px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-image: none!important;
        }
        
        #wp-admin-bar-et-top-bar-general-menu li .ab-sub-wrapper .ab-submenu .dashicons:before {
            font-size: 14px;
            line-height: 27px;
        }
        .js #adminmenu #toplevel_page_et-panel-theme-options .et_top-bar-mega-menu-copy .dashicons:before {
            font-size: 14px;
            line-height: 18px;
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label,
        #toplevel_page_et-panel-theme-options .et-tbm-label {
            margin-inline-start: 3px;
            letter-spacing: 1px;
            display: inline-block;
            border-radius: 3px;
            color: #fff;
            padding: 3px 2px 2px 3px;
            text-transform: uppercase;
            font-size: 8px;
            line-height: 1;
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-beta,
        #toplevel_page_et-panel-theme-options .et-tbm-label-beta {
            background: var(--et_admin_orange-color, #f57f17);
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-deprecated,
        #toplevel_page_et-panel-theme-options .et-tbm-label-deprecated {
            background: var(--et_admin_red-color, #c62828);
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-new,
        #toplevel_page_et-panel-theme-options .et-tbm-label-new {
            background: var(--et_admin_green-color, #489c33);
        }
        #wp-admin-bar-et-top-bar-general-menu .et-tbm-label-hot,
        #toplevel_page_et-panel-theme-options .et-tbm-label-hot {
            background: var(--et_admin_main-color, #A4004F);
        }
        #wp-admin-bar-et-top-bar-general-menu ul li a > svg,
        #wp-admin-bar-et-top-bar-theme-builders-menu ul li a > svg,
        #wp-admin-bar-et-top-bar-xstore-sales-booster ul li a > svg {
            width: 1.2em;
            height: 1.2em;
            font-size: .85em;;
            fill: currentColor;
            margin-right: 5px;
            position: relative;
            top: 2px;
        }
     	#wp-admin-bar-et-top-bar-general-menu .et-title-label{
         	position: relative;
			white-space: nowrap;
			vertical-align: middle;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			line-height: 15px;
			border-radius: 30px;
			color: #fff;
			background: var(--et_admin_green-color, #489C33);
			font-size: 9px;
			min-width: 6px;
			padding: 4px;
			height: 6px;
			margin-inline-start: 5px;
     	}
         
        </style>';
    }
}