<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Patcher" for 8theme dashboard.
 *
 * @since   9.0.4
 * @version 1.0.0
 */


$patcher = new \Etheme_Patcher();
$patcher->get_patches_list(ETHEME_THEME_VERSION); // set current version as minimum version for patch list

