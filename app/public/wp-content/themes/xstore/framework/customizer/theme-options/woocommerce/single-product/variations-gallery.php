<?php
/**
 * The template created for displaying single product variation gallery options
 *
 * @version 0.0.1
 * @since   6.2.12
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'variations-gallery' => array(
			'name'       => 'variations-gallery',
			'title'      => esc_html__( 'Variation gallery', 'xstore' ),
            'description' => esc_html__('By default, WooCommerce will only change the main variation image when a product variation is selected, not the gallery images below it. Our integrated variation gallery option allows you to add extra gallery images per variation on variable products within WooCommerce.', 'xstore'),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-images-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/variations-gallery' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'enable_variation_gallery' => array(
			'name'        => 'enable_variation_gallery',
			'type'        => 'toggle',
			'settings'    => 'enable_variation_gallery',
			'label'       => esc_html__( 'Variation gallery', 'xstore' ),
            'tooltip' => esc_html__( 'Enabling this feature will allow visitors to view different images of a product variation, all in the same color and style. This option allows visitors to your online store to view different gallery images when they select a product variation. Enable this option to make this feature work, and you can configure separate variation galleries for each variation of a single product.', 'xstore' ),
			'section'     => 'variations-gallery',
			'default'     => 0,
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );