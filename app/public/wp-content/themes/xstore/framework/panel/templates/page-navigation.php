<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Navigation" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.2
 */

$mtips_notify = esc_html__('Register your theme and activate XStore Core plugin, please.', 'xstore');
$allow_full_access = !etheme_activation_required();
$allow_force_access_woocommerce_features = true;
$popup_requirements_trigger_class = 'trigger-xstore-control-plugins-popup';
$popup_requirements = array('heading' => '', 'plugins' => array(), 'trigger_class' => $popup_requirements_trigger_class);
$active_dot = '<span class="feature-active-mark"></span>';
$theme_active = etheme_is_activated();
$core_active = class_exists('ETC\App\Controllers\Admin\Import');
$amp_active = class_exists('XStore_AMP');
$is_woocommerce = class_exists('WooCommerce');
$is_elementor = defined( 'ELEMENTOR_VERSION' );
$kirki_exists = class_exists( 'Kirki' );

$system_requirements = $plugins = $theme_options = '';

$system_requirements_active = false;
$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

$branding_label = 'XStore';
$custom_plugins_label = 'XStore';
$show_pages = array(
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
    'maintenance_mode',
    'social_authentication',
    'social',
    'support',
    'changelog',
);

$hide_theme_builders = false;

if ( count($xstore_branding_settings) ) {
    if ( isset($xstore_branding_settings['control_panel']) ) {
        if ($xstore_branding_settings['control_panel']['label'])
            $branding_label = $xstore_branding_settings['control_panel']['label'];
        if (isset($xstore_branding_settings['control_panel']['hide_theme_builders']) && $xstore_branding_settings['control_panel']['hide_theme_builders'] == 'on')
            $hide_theme_builders = true;
        $show_pages_parsed = array();
        foreach ($show_pages as $show_page) {
            if (isset($xstore_branding_settings['control_panel']['page_' . $show_page]))
                $show_pages_parsed[] = $show_page;
        };
        $show_pages = $show_pages_parsed;
    }
    if ( isset($xstore_branding_settings['plugins_data'] ) ) {
        if (isset($xstore_branding_settings['plugins_data']['label']) && !empty($xstore_branding_settings['plugins_data']['label']))
            $custom_plugins_label = $xstore_branding_settings['plugins_data']['label'];
    }
}

$system = class_exists('Etheme_System_Requirements') ? Etheme_System_Requirements::get_instance() : new Etheme_System_Requirements();
$system->system_test();
$result = $system->result();

$new_label = '<span style="margin-left: 5px; background: var(--et_admin_green-color, #489c33); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.esc_html__('new', 'xstore').'</span>';
$hot_label = '<span style="margin-left: 5px; background: var(--et_admin_main-color, #A4004F); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.esc_html__('hot', 'xstore').'</span>';
$beta_label = '<span style="margin-left: 5px; background: var(--et_admin_orange-color, #f57f17); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.esc_html__('beta', 'xstore').'</span>';

$info_label = '<span class="dashicons dashicons-warning" style="color: var(--et_admin_orange-color);"></span>';
//$info_label = '';

$locked_icon = !$theme_active || !$core_active ? '<span class="dashicons dashicons-lock" style="width: 1rem;height: 1rem;font-size: 1rem;"></span>' : '';

$changelog_icon = '';
$welcome_icon = '';
$check_update = new ETheme_Version_Check();

$categories = array(
    'main' => array(
        'title' => esc_html__('Main', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
    'content' => array(
        'title' => esc_html__('Content Management', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
    'performance' => array(
        'title' => esc_html__('Performance & Optimization', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
    'api' => array(
        'title' => esc_html__('API & Interaction', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
    'maintenance' => array(
        'title' => esc_html__('Maintenance & Help', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
    'customization' => array(
        'title' => esc_html__('Branding Customization', 'xstore'),
        'title_postfix_count' => 0,
        'title_postfix_html' => false,
        'href' => admin_url( 'admin.php?page=et-panel-welcome' ),
        'active_item' => false,
        'items' => array()
    ),
);

if( $check_update->is_update_available() )
    $changelog_icon = '
    <span style="       
            display: inline-block;
            position: relative;
            min-width: 12px;
            height: 12px;
            margin: 0px 0px -2px 8px;
            background: #fff;">
        <span
            style="
                width: auto;
                height: auto;
                vertical-align: middle;
                position: absolute;
                left: -8px;
                top: -5px;
                font-size: 22px;"
            class="dashicons dashicons-warning dashicons-warning et_admin_bullet-green-color"></span>
    </span>';
$is_update_support = 'active'; //$check_update->get_support_status();
if( $is_update_support !='active' ) {
    if ( $is_update_support == 'expire-soon' ) {
        $welcome_icon = '
            <span style="       
                    display: inline-block;
                    position: relative;
                    min-width: 12px;
                    height: 12px;
                    margin: 0px 0px -2px 8px;
                    color: var(--et_admin_orange-color);
                    background: #fff;">
                <span
                    style="
                        width: auto;
                        height: auto;
                        vertical-align: middle;
                        position: absolute;
                        left: -8px;
                        top: -5px;
                        font-size: 22px;"
                    class="dashicons dashicons-warning dashicons-warning et_admin_bullet-orange-color"></span>
            </span>';
    } else {
        $welcome_icon = '
            <span style="       
                    display: inline-block;
                    position: relative;
                    min-width: 12px;
                    height: 12px;
                    margin: 0px 0px -2px 8px;
                    color: var(--et_admin_red-color);
                    background: #fff;">
                <span
                    style="
                        width: auto;
                        height: auto;
                        vertical-align: middle;
                        position: absolute;
                        left: -8px;
                        top: -5px;
                        font-size: 22px;"
                    class="dashicons dashicons-warning dashicons-warning et_admin_bullet-red-color"></span>
            </span>';
    }
}

$category = 'main';
if ( in_array('welcome', $show_pages) ) {
    $item_title_affix = '';
    $expired_support = $check_update->get_support_status() == 'expired';
    $tooltips = array();
    if ( !$theme_active || $expired_support ) {
        $categories[$category]['title_postfix_count']++;
        $item_title_affix = ' <span class="et-title-label">1</span>';
        if (!$theme_active)
            $tooltips[] = '<li>' . esc_html__('Complete your theme registration', 'xstore') . '</li>';
        elseif ($expired_support)
            $tooltips[] = '<li>' . esc_html__('Support expired. Renew today!', 'xstore') . '</li>';
    }

    $is_active = ( ! isset( $_GET['page'] ) || $_GET['page'] == 'et-panel-welcome' );
    $categories[$category]['items'][] = sprintf(
        '<li class="%s"><a href="%s" class="%s">%s %s '.$welcome_icon.'</a>%s</li>',
        count($tooltips) ? 'mtips mtips-lg' : '',
        admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-welcome"></span>',
        esc_html__( 'Welcome', 'xstore' ) . $item_title_affix,
        (count($tooltips) ? '<span class="mt-mes"><ol>' . implode('<br/>', $tooltips) . '</ol></span>' : '')
    );
    if ( $is_active )
        $categories[$category]['active_item'] = $is_active;
}

if ( in_array('system_requirements', $show_pages) ) {
    $system_requirements_active = ( $_GET['page'] == 'et-panel-system-requirements' );
    $system_requirements = sprintf(
        '<li><a href="%s" class="%s">%s %s</a></li>',
        admin_url( 'admin.php?page=et-panel-system-requirements' ),
        $system_requirements_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-system-status"></span>',
        esc_html__( 'System Status', 'xstore' ) . ( ( ! $result && ($theme_active || $allow_full_access) ) ? $info_label : '' )
    );
}
if ( (! $theme_active || ! $core_active) && !$allow_full_access ) {
    $categories[$category]['items'][] = $system_requirements;
    if ( $system_requirements_active )
        $categories[$category]['active_item'] = $system_requirements_active;
    if ( ! $result ) {
        $categories[$category]['title_postfix_html'] = $info_label;
    }
}

if ( in_array('customize', $show_pages) ) {
    $categories[$category]['items'][] = sprintf(
        ( ! $core_active && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ! $core_active && !$allow_full_access ) ? admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ) : wp_customize_url(),
        '',
        '<span class="et-panel-nav-icon et-panel-nav-theme-options"></span>',
        esc_html__( 'Theme Options', 'xstore' ) . (!$core_active && !$allow_full_access ? $locked_icon : '')
    );
}

if ( ($is_elementor || $allow_full_access) && !$hide_theme_builders ) {
    $popup_requirements['heading'] = sprintf(esc_html__( '%s Builders', 'xstore' ), $branding_label);
    $popup_requirements['feature'] = 'theme_builders';
    $item_link_class = array();
//    if ( !$is_elementor || !$core_active ) {
    if ( !$core_active ) {
        $item_link_class[] = $popup_requirements_trigger_class;
    }
//    if ( !$is_elementor )
//        $popup_requirements['plugins'][] = 'elementor';
    if ( !$core_active )
        $popup_requirements['plugins'][] = 'et-core-plugin';
    $categories[$category]['items'][] = sprintf(
        (((!$core_active || !$theme_active) && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>'),
        ((!$core_active || !$theme_active) && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url( 'admin.php?page=et-panel-theme-builders' ),
        implode(' ', $item_link_class),
        count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
        '<span class="et-panel-nav-icon et-panel-nav-xstore-builders"></span>',
        $popup_requirements['heading'] . (((!$core_active || !$theme_active) && !$allow_full_access) ? $locked_icon : '')
    );
    $popup_requirements['plugins'] = array();
}

if ( in_array('demos', $show_pages) ) {
    $is_active = ($_GET['page'] == 'et-panel-demos');
    $categories[$category]['items'][] = sprintf(
        (! $theme_active && !$allow_full_access ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>'),
        admin_url('admin.php?page=et-panel-demos'),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-import-demos"></span>',
        esc_html__('Import Demos 130+', 'xstore') . (! $theme_active && !$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}

// @todo change links and page conditions for this item then show it
//if ( in_array('demos', $show_pages) ) {
//    $is_active = ($_GET['page'] == 'et-panel-demos');
//    $categories[$category]['items'][] = sprintf(
//        (! $theme_active ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>'),
//        admin_url('admin.php?page=et-panel-demos'),
//        $is_active ? ' active' : '',
//        '<span class="et-panel-nav-icon et-panel-nav-import-demos"></span>',
//        esc_html__('Additional Pages', 'xstore') . (! $theme_active ? $locked_icon : '')
//    );
//    if ($is_active)
//        $categories[$category]['active_item'] = $is_active;
//}

if ( $allow_force_access_woocommerce_features || $is_woocommerce ) {

    if ( !$is_woocommerce )
        $popup_requirements['plugins'][] = 'woocommerce';
    if ( !$core_active )
        $popup_requirements['plugins'][] = 'et-core-plugin';

    if (in_array('sales_booster', $show_pages)) {
        $is_active = ( $_GET['page'] == 'et-panel-sales-booster' );
        $popup_requirements['heading'] = esc_html__('Sales Booster', 'xstore');
        $popup_requirements['feature'] = 'sales_booster';
        $item_link_class = array();
        if ( $is_active )
            $item_link_class[] = 'active';
        if ( !$is_woocommerce || !$core_active ) {
            $item_link_class[] = $popup_requirements_trigger_class;
        }
        $categories[$category]['items'][] = sprintf(
            ((!$theme_active || !$core_active) && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
            ((!$theme_active || !$core_active) && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url('admin.php?page=et-panel-sales-booster'),
            implode(' ', $item_link_class),
            count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
            '<span class="et-panel-nav-icon et-panel-nav-sales-booster"></span>',
//              '🚀&nbsp;&nbsp;' . esc_html__( 'Sales Booster', 'xstore' ) . $new_label
            $popup_requirements['heading'] . $hot_label . (((!$core_active || !$theme_active) && !$allow_full_access) ? $locked_icon : '')
        );
        if ( $is_active )
            $categories[$category]['active_item'] = $is_active;
    }

    if (in_array('email_builder', $show_pages)) {
        $is_active = ( $_GET['page'] == 'et-panel-email-builder' );
        $popup_requirements['heading'] = esc_html__('Built-in Email Builder', 'xstore');
        $popup_requirements['feature'] = 'email_builder';
        $item_link_class = array();
        if ( $is_active )
            $item_link_class[] = 'active';
        if ( !$is_woocommerce || !$core_active ) {
            $item_link_class[] = $popup_requirements_trigger_class;
        }

        $categories[$category]['items'][] = sprintf(
            ((!$core_active || !$theme_active) && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
            (($theme_active && $core_active) || $allow_full_access) ? admin_url('admin.php?page=et-panel-email-builder') : admin_url('admin.php?page=et-panel-welcome'),
            implode(' ', $item_link_class),
            count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
            (get_option('etheme_built_in_email_builder', false) ? $active_dot : '').
            '<span class="et-panel-nav-icon et-panel-nav-email-builder"></span>',
            $popup_requirements['heading'] . (((!$core_active || !$theme_active) && !$allow_full_access) ? $locked_icon : '')
        );
        if ( $is_active )
            $categories[$category]['active_item'] = $is_active;
    }
    $popup_requirements['plugins'] = array();
}

if ( in_array('plugins', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-plugins' );
    $categories[$category]['items'][] = sprintf(
        ( ! $theme_active && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( $theme_active || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-plugins' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-plugins-installer"></span>',
        esc_html__( 'Plugins Installer', 'xstore' ) . ((!$theme_active && !$allow_full_access) ? $locked_icon : '')
    );
    if ( $is_active )
        $categories[$category]['active_item'] = $is_active;
}
if ( in_array('patcher', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-patcher' );
    $item_title_affix = '';
    if ( class_exists('Etheme_Patcher') && ($theme_active || $allow_full_access) ) {
        $patcher = Etheme_Patcher::get_instance();
        $available_patches = count($patcher->get_available_patches(ETHEME_THEME_VERSION));
        if ( $available_patches ) {
            $item_title_affix = ' <span class="et-title-label">'.
                $available_patches.
                '</span>';
            $categories[$category]['title_postfix_count'] += $available_patches;
        }
    }
    $categories[$category]['items'][] = sprintf(
        ( ! $theme_active && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( $theme_active || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-patcher' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-patcher"></span>',
        esc_html__( 'Patcher', 'xstore' ) . $item_title_affix . ((!$theme_active && !$allow_full_access) ? $locked_icon : '')
    );
    if ( $is_active )
        $categories[$category]['active_item'] = $is_active;
}

$category = 'content';
if ( $theme_active || $allow_full_access ) {
    if (in_array('custom_fonts', $show_pages)) {
        $is_active = ($_GET['page'] == 'et-panel-custom-fonts');
        $popup_requirements['heading'] = esc_html__('Custom Fonts', 'xstore');
        $popup_requirements['feature'] = 'custom_fonts';
        $item_link_class = array();
        if ( $is_active )
            $item_link_class[] = 'active';
        if ( !$core_active ) {
            $popup_requirements['plugins'][] = 'et-core-plugin';
            $item_link_class[] = $popup_requirements_trigger_class;
        }
        $categories[$category]['items'][] = sprintf(
            (!$core_active && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
            (!$core_active && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url('admin.php?page=et-panel-custom-fonts'),
            implode(' ', $item_link_class),
            count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
            '<span class="et-panel-nav-icon et-panel-nav-custom-fonts"></span>',
            $popup_requirements['heading']
        );
        if ($is_active)
            $categories[$category]['active_item'] = $is_active;
    }
    if ( $core_active || $allow_full_access ) {
        $popup_requirements['plugins'] = array();
        if ( !$core_active )
            $popup_requirements['plugins'][] = 'et-core-plugin';
        if ( !$is_elementor )
            $popup_requirements['plugins'][] = 'elementor';
        $item_link_class = array();
        if ( !$is_elementor || !$core_active ) {
            $item_link_class[] = $popup_requirements_trigger_class;
        }
        if (get_theme_mod('etheme_slides', true)) {
            $popup_requirements['heading'] = esc_html__('Slides', 'xstore');
            $popup_requirements['feature'] = 'etheme_slides';
            $categories[$category]['items'][] = sprintf(
                (!$core_active && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
                (!$core_active && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url('edit.php?post_type=etheme_slides'),
                implode(' ', $item_link_class),
                count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
                '<span class="et-panel-nav-icon et-panel-nav-import-demos"></span>',
                $popup_requirements['heading'] . $new_label
            );
        }
        if (get_theme_mod('etheme_mega_menus', true)) {
            $popup_requirements['heading'] = esc_html__('Mega Menus', 'xstore');
            $popup_requirements['feature'] = 'etheme_mega_menus';
            $categories[$category]['items'][] = sprintf(
                (!$core_active && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
                (!$core_active && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url('edit.php?post_type=etheme_mega_menus'),
                implode(' ', $item_link_class),
                count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
                '<span class="et-panel-nav-icon et-panel-nav-widgets"></span>',
                $popup_requirements['heading'] . $new_label
            );
        }
        if (get_theme_mod('static_blocks', true)) {
            $popup_requirements['heading'] = esc_html__('Static Blocks', 'xstore');
            $popup_requirements['feature'] = 'static_blocks';
            $item_link_class = array();
            $popup_requirements['plugins'] = array();
            if ( !$core_active ) {
                $popup_requirements['plugins'][] = 'et-core-plugin';
                $item_link_class[] = $popup_requirements_trigger_class;
            }
            $categories[$category]['items'][] = sprintf(
                (!$core_active && !$allow_full_access) ? '<li class="mtips inactive"><a href="%s" class="%s" %s>%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s" %s>%s %s</a></li>',
                (!$core_active && !$allow_full_access) ? admin_url('admin.php?page=et-panel-welcome') : admin_url('edit.php?post_type=staticblocks'),
                implode(' ', $item_link_class),
                count($popup_requirements['plugins']) ? 'data-details="'.esc_attr(wp_json_encode($popup_requirements)).'"' : '',
                '<span class="et-panel-nav-icon et-panel-nav-static-blocks"></span>',
                $popup_requirements['heading']
            );
        }
        $popup_requirements['plugins'] = array();
    }
}

if ( ($theme_active && $core_active) || $allow_full_access ) {
    $categories[$category]['items'][] = sprintf(
        '<li><a href="%s" class="%s">%s %s</a></li>',
        admin_url('widgets.php'),
        '',
        '<span class="et-panel-nav-icon et-panel-nav-widgets"></span>',
        esc_html__('Widgets', 'xstore')
    );
}

if ( $theme_active ) {
    if( get_theme_mod( 'portfolio_projects', true ) ) {
        $categories[$category]['items'][] = sprintf(
            ( ! $core_active ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
            ( ! $core_active ) ? admin_url( 'admin.php?page=et-panel-welcome' ) : admin_url( 'edit.php?post_type=etheme_portfolio' ),
            '',
            '<span class="et-panel-nav-icon et-panel-nav-portfolio"></span>',
            esc_html__('Portfolio', 'xstore')
        );
    }
}

$category = 'performance';
if ( ($core_active && $allow_full_access) || (!$core_active && !$allow_full_access) ) {
    if (in_array('customize', $show_pages)) {
        $categories[$category]['items'][] = sprintf(
            (!$core_active) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
            (!$core_active) ? admin_url('themes.php?page=install-required-plugins&plugin_status=all') : admin_url('/customize.php?autofocus[section]=general-optimization'),
            '',
            '<span class="et-panel-nav-icon et-panel-nav-speed-optimization"></span>',
            esc_html__('Speed Optimization', 'xstore') . (!$core_active ? $locked_icon : '')
        );
    }
}

$amp_tips = $mtips_notify;
if ( !$amp_active ) {
    $amp_url = admin_url( 'admin.php?page=et-panel-plugins&plugin=xstore-amp' );
    if ( $allow_full_access || $theme_active && $core_active )
        $amp_tips = sprintf(esc_html__( 'Install and Activate %s AMP plugin to use amp settings', 'xstore' ), $custom_plugins_label);
} else {
    $amp_url = admin_url('admin.php?page=et-panel-xstore-amp');
}

$is_active = ( $_GET['page'] == 'et-panel-xstore-amp' );
$categories[$category]['items'][] = sprintf(
    ((!$core_active || !$theme_active || !$amp_active) && !$allow_full_access ) ? '<li class="mtips'.((!$core_active || !$theme_active) ? ' inactive' : '').'"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $amp_tips . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
    ( ($theme_active && $core_active) || $allow_full_access ) ? $amp_url : admin_url( 'admin.php?page=et-panel-welcome' ),
    $is_active ? ' active' : '',
    ($amp_active ? $active_dot : '').
    '<span class="et-panel-nav-icon et-panel-nav-amp-xstore"></span>',
    sprintf(esc_html__( 'AMP %s', 'xstore' ), $custom_plugins_label) . (!$allow_full_access ? $locked_icon : '')
);
if ($is_active)
    $categories[$category]['active_item'] = $is_active;

// api category
$category = 'api';
if ( in_array('open_ai', $show_pages) ) {
    $is_active = ($_GET['page'] == 'et-panel-open-ai');
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? admin_url('admin.php?page=et-panel-open-ai') : admin_url('admin.php?page=et-panel-welcome'),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-open-ai"></span>',
        esc_html__('ChatGPT (OpenAI)', 'xstore') . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}

if ( in_array('social_authentication', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-social-authentication' );
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-social-authentication' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-social-authentication"></span>',
        esc_html__( 'Social Authentication', 'xstore' ) . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}

if ( in_array('social', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-social' );
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-social' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-api-integrations"></span>',
        esc_html__( 'API Integrations', 'xstore' ) . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}

// maintenance category
$category = 'maintenance';
if ( in_array( 'maintenance_mode', $show_pages ) ) {
    $is_active = ( $_GET['page'] == 'et-panel-maintenance-mode' );
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-maintenance-mode' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        (get_option('etheme_maintenance_mode', false) ? $active_dot : '').
        '<span class="et-panel-nav-icon et-panel-nav-maintenance-mode"></span>',
        esc_html__( 'Maintenance Mode', 'xstore' ) . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}
if ( in_array('support', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-support' );
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? admin_url( 'admin.php?page=et-panel-support' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-tutorials-support"></span>',
        esc_html__( 'Tutorials & Support', 'xstore' ) . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}
if ( $theme_active && $core_active && $system_requirements ) {
    $categories[$category]['items'][] = $system_requirements;
    if ( $system_requirements_active )
        $categories[$category]['active_item'] = $system_requirements_active;
    if ( ! $result ) {
        $categories[$category]['title_postfix_html'] = $info_label;
    }
}

if ( in_array('changelog', $show_pages) ) {
    $is_active = ( $_GET['page'] == 'et-panel-changelog' );
    $categories[$category]['items'][] = sprintf(
        ( (! $core_active || ! $theme_active) && !$allow_full_access ) ? '<li class="mtips inactive"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        admin_url( 'admin.php?page=et-panel-changelog' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-changelog"></span>',
        esc_html__( 'Changelog', 'xstore' ) . $changelog_icon . (!$allow_full_access ? $locked_icon : '')
    );
    if ($is_active)
        $categories[$category]['active_item'] = $is_active;
}

// based on plugins because it that page is shown for customer then he can install White Label Plugin too
if ( !$allow_full_access && in_array('plugins', $show_pages) && !class_exists('XStore_White_Label_Branding') ) {
    $branding_tips = $mtips_notify;
    $is_active = false;
    $branding_url = admin_url( 'admin.php?page=et-panel-plugins&plugin=xstore-white-label-branding' );
    if ( ($theme_active && $core_active) || $allow_full_access )
        $branding_tips = sprintf(esc_html__( 'Install and Activate %s White Label plugin to use White Label Branding settings', 'xstore' ), $custom_plugins_label);
    $categories[$category]['items'][] = sprintf(
        ((!$core_active || !$theme_active) && !$allow_full_access ) ? '<li class="mtips'.((!$core_active || !$theme_active) ? ' inactive' : '').'"><a href="%s" class="%s">%s %s</a><span class="mt-mes">' . $branding_tips . '</span></li>' : '<li><a href="%s" class="%s">%s %s</a></li>',
        ( ($theme_active && $core_active) || $allow_full_access ) ? $branding_url : admin_url( 'admin.php?page=et-panel-welcome' ),
        $is_active ? ' active' : '',
        '<span class="et-panel-nav-icon et-panel-nav-white-label"></span>',
        esc_html__( 'White Label Branding', 'xstore' ) . (!$allow_full_access ? $locked_icon : '')
    );
}


// additional category for some items added with hook action
if ( has_action('etheme_last_dashboard_nav_item') ) {
    $category = 'customization';
    ob_start();
    do_action('etheme_last_dashboard_nav_item');
    $customization = ob_get_clean();
    foreach (explode('<li', $customization) as $customization_item) {
        if ( !$customization_item ) continue;
        $categories[$category]['items'][] = '<li' . ((!$core_active || !$theme_active) ? ' class="mtips inactive" ' . str_replace(array('</a>', '</li>'), array( $locked_icon.'</a>', '<span class="mt-mes">' . $mtips_notify . '</span>' . '</li>'), $customization_item) : $customization_item);
//        $categories[$category]['items'][] = '<li' . $customization_item;
        // $categories[$category]['items'][] = '<li'.$customization_item;
        if ( strpos('active', $customization) !== false )
            $categories[$category]['active_item'] = true;
    }
}

$categories = apply_filters('etheme_dashboard_navigation', $categories, $theme_active, $core_active, $allow_full_access, $locked_icon, $mtips_notify, array('new' => $new_label,
'hot' => $hot_label,
'beta' => $beta_label,
'info' => $info_label));

$combined_list = array();
foreach ($categories as $category_key => $category_details) {
    $combined_list_local = '';
    if ( !count($category_details['items']) ) continue;
    $category_details['active_item'] = true; // temporary to show all opened @todo
    $combined_list_items = '<ul style="'.(!$category_details['active_item'] ? ' display: none;' : '').'">';
    $combined_list_items .= implode('', $category_details['items']);
    $combined_list_items .= '</ul>';
//        $combined_list_local .= '<li><span class="dashicons dashicons-arrow-down-alt2"></span><span>'.$category_details['title'].'</span>';
    $combined_list_local .= sprintf(
        '<li><a href="%s" class="%s et-nav-category">%s %s</a> %s</li>',
        $category_details['href'],
        $category_details['active_item'] ? ' opened' : '',
        '<span class="dashicons dashicons-arrow-'.($category_details['active_item'] ? 'down' : 'right').'"></span>',
        '<span>'.$category_details['title'].'</span>' . $category_details['title_postfix_html'] .
        ($category_details['title_postfix_count'] > 0 ? '<span class="et-title-label">'.$category_details['title_postfix_count'].'</span>' : ''),
        $combined_list_items
    );

    $combined_list[] = $combined_list_local;
}

$nav_collapser = '<span class="etheme-page-nav-collapser"><span class="dashicons dashicons-arrow-left"></span></span>';
echo '<div class="etheme-page-nav"><ul>' . implode('', $combined_list) . '</ul>'.$nav_collapser.'</div>';
