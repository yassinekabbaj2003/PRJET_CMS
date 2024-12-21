<?php
/**
 * Template "Demos" for 8theme dashboard.
 *
 * @since   
 * @version 1.0.0
 */

$versions = etheme_get_demo_versions();

$version = $versions[$_POST['version']];
?>

<div class="popup-import-head">
	<img
		class="version-img"
        data-lazy-src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
        src="<?php echo apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/' . esc_attr( $_POST['version'] ) . '/screenshot.jpg'); ?>"
        data-old-src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
		alt="<?php echo esc_attr( $_POST['version'] ); ?>">
</div>