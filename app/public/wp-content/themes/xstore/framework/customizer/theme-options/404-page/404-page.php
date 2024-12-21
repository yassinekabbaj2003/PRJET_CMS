<?php
/**
 * The template created for displaying 404 page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section general-page-not-found
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'general-page-not-found' => array(
			'name'       => 'general-page-not-found',
			'title'      => esc_html__( '404 Page', 'xstore' ),
			'description' => esc_html__('A 404 page is a landing page that informs visitors of a website that the requested page is either unavailable or does not exist. Ideally, visitors would never land on a 404 page, however, this is not always the case, even for well-maintained sites. Having a visually appealing and user-friendly 404 error page demonstrates to customers that the website is concerned about their experience and is interested in keeping them on the website. When done correctly, a good 404 page can help users forgive the error (even if it was their own fault) and keep them on the website.', 'xstore'),
			'icon'       => 'dashicons-warning',
			'priority'   => $priorities['404'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/general-page-not-found' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'404_text' => array(
			'name'            => '404_text',
			'type'            => 'editor',
			'settings'        => '404_text',
			'label'           => esc_html__( 'Content', 'xstore' ),
			'tooltip'     => esc_html__( 'Here, you can write your own custom HTML using the tags in the top bar of the editor. However, please note that not all HTML tags and element attributes can be used due to Theme Options safety reasons.', 'xstore' ),
			'section'         => 'general-page-not-found',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'404_text' => array(
					'selector'        => '.page-404 > .row > .col-md-12',
					'render_callback' => function () {
						$content = get_theme_mod( '404_text', '' );
						if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
							WPBMap::addAllMappedShortcodes();
						}
						ob_start();
						
						if ( ! empty( $content ) ):
							echo do_shortcode( $content );
						else:
							echo '<h2 class="largest">404</h2>';
							echo '<h1>' . esc_html__( 'That Page Can\'t Be Found', 'xstore' ) . '</h1>';
							echo '<p>' . esc_html__( 'It looks like nothing was found at this location. Try searching.', 'xstore' ) . '</p>';
							get_search_form( true );
						endif;
						
						return ob_get_clean();
					},
				),
			),
		)
	
	);
	
	
	return array_merge( $fields, $args );
} );