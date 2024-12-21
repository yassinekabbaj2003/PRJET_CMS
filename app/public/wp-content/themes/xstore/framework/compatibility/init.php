<?php
/**
 * Description
 *
 * @package    init.php
 * @since      8.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

/*
* Elementor compatibilities
* ******************************************************************* */
if ( defined('ELEMENTOR_VERSION')) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/elementor.php' ) );
}

/*
* WPBakery compatibilities
* ******************************************************************* */
if ( defined('WPB_VC_VERSION') ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/wpbakery.php' ) );
}

/*
* Dokan compatibilities
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'compatibility/dokan.php') );

/*
* Bbpress compatibilities
* ******************************************************************* */
if ( class_exists( 'bbPress' ) ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/bbpress.php' ) );
}

/*
* WCMarketplace compatibilities (MVX - since 4.0 renamed) 
* ******************************************************************* */
if ( class_exists('WCMp') || class_exists('MVX') ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/wcmp.php' ) );
}

/*
* WCFM Marketplace compatibility
* ******************************************************************* */
//if ( class_exists( 'WCFMmp' ) ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/wcfmmp.php' ) );
//}

/*
* Yoast compatibilities
* ******************************************************************* */
if ( defined('WPSEO_VERSION') ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/wpseo.php' ) );
}

/*
* WPML compatibilities
* ******************************************************************* */
if ( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/wpml.php' ) );
}

require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/yith-compare.php' ) );

/*
* WooCommerce Skroutz & BestPrice XML Feed
* ******************************************************************* */
require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/webexpert-skroutz.php' ) );

require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'compatibility/gutenberg.php' ) );

// ! REFER for woo premium plugins
if ( ! defined( 'YITH_REFER_ID' ) ) {
    define( 'YITH_REFER_ID', '1028760' );
}

// REFER for yellow pencil
if ( ! defined( 'YP_THEME_MODE' ) ) {
    define( 'YP_THEME_MODE', "true" );
}

// **********************************************************************//
// ! Theme 3d plugins
// **********************************************************************//
add_action( 'init', 'etheme_3d_plugins' );
if ( ! function_exists( 'etheme_3d_plugins' ) ) {
    function etheme_3d_plugins() {
        if ( function_exists( 'set_revslider_as_theme' ) ) {
            set_revslider_as_theme();
        }
        if ( function_exists( 'set_ess_grid_as_theme' ) ) {
            set_ess_grid_as_theme();
        }
    }
}
