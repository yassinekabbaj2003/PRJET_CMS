<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Sales booster" for 8theme dashboard.
 *
 * @since   7.0.0
 * @version 1.0.0
 */

if ( class_exists('Etheme_Sales_Booster_Backend') ) {
	$Etheme_Sales_Booster_Backend = new Etheme_Sales_Booster_Backend();
	$Etheme_Sales_Booster_Backend->sales_booster_page();
}