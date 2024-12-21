<?php

/**
 *
 * @package     XStore Core plugin
 * @author      8theme
 * @version     1.0.0
 * @since       3.2.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'EthemeAdmin' ) ) {
    return;
}

if ( ! method_exists( 'EthemeAdmin', 'get_instance' ) ) {
    return;
}
