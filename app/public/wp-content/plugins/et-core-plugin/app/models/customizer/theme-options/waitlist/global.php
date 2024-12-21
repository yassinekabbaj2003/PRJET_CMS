<?php
/**
 * The template created for enqueueing all files for waitlist options
 *
 * @version 0.0.1
 * @since   5.1.9
 */

// added condition because we keep Waitlist in Sales booster
if ( !get_theme_mod('xstore_waitlist', false) ) return;

$elements = array(
	'waitlist'
);

foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/waitlist/' . $key . '.php' );
}