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
* Ajax functions
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/ajax-functions.php') );

/*
* Lazyload functions
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/lazyload-functions.php') );

/*
* XStore_LazyLoad Class
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/lazyload.php') );

/*
* XStore_GDPR Class
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/gdpr.php') );

/*
* Optimization
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/optimization.php') );

/*
* SEO
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/seo.php') );

/*
* OpenAI
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/open-ai.php') );

/*
* WooCommerce
* ******************************************************************* */
if ( class_exists('WooCommerce') ) {
	require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'features/woocommerce/init.php' ) );
}

/*
* Search page results
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/search-page.php') );