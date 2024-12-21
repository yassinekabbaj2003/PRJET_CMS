<?php
/**
 * The template created for displaying shop related products options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-related-products' => array(
			'name'       => 'shop-related-products',
			'title'      => esc_html__( 'Related products', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-networking',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-related-products' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'show_related' => array(
			'name'        => 'show_related',
			'type'        => 'toggle',
			'settings'    => 'show_related',
			'label'       => esc_html__( 'Display related products', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to show related products on the individual product pages.', 'xstore' ),
			'section'     => 'shop-related-products',
			'default'     => 1,
		),
		
		'related_type' => array(
			'name'            => 'related_type',
			'type'            => 'select',
			'settings'        => 'related_type',
			'label'           => esc_html__( 'Type', 'xstore' ),
            'tooltip'  => esc_html__('Choose the design type for the related products.', 'xstore'),
			'section'         => 'shop-related-products',
			'default'         => 'slider',
			'choices'         => array(
				'slider' => esc_html__( 'Slider', 'xstore' ),
				'grid'   => esc_html__( 'Grid', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'related_slides' => array(
			'name'            => 'related_slides',
			'type'            => 'dimensions',
			'settings'        => 'related_slides',
            'label'    => esc_html__( 'Products per view', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed for related products.', 'xstore' ),
			'section'         => 'shop-related-products',
			'default'         => array(
				'padding-top'    => '',
				'padding-right'  => '',
				'padding-bottom' => '',
				'padding-left'   => '',
			),
			'choices'         => array(
				'labels' => array(
					'padding-top'    => esc_html__( 'Large', 'xstore' ),
					'padding-right'  => esc_html__( 'Notebook', 'xstore' ),
					'padding-bottom' => esc_html__( 'Tablet landscape', 'xstore' ),
					'padding-left'   => esc_html__( 'Tablet portrait', 'xstore' ),
				),
                'descriptions' => array(
                    'padding-top'    => '<span class="dashicons dashicons-desktop" style="margin-inline-end: 7px;width: 1em;height: 1em;font-size: 1.5em;"></span>' . esc_html__( 'Large', 'xstore' ),
                    'padding-right'  => '<span class="dashicons dashicons-laptop" style="margin-inline-end: 7px;width: 1em;height: 1em;font-size: 1.5em;"></span>' . esc_html__( 'Notebook', 'xstore' ),
                    'padding-bottom' => '<span class="dashicons dashicons-tablet" style="width: calc(1em + 7px);height: 0.9em;font-size: 1.5em;transform: rotate(-90deg);"></span>' . esc_html__( 'Tablet', 'xstore' ),
                    'padding-left'   => '<span class="dashicons dashicons-tablet" style="margin-inline-end: 7px;width: 1em;height: 1em;font-size: 1.5em;"></span>' . esc_html__( 'Tablet', 'xstore' ),
                ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'related_type',
					'operator' => '==',
					'value'    => 'slider',
				),
			)
		),
		
		'related_columns' => array(
			'name'            => 'related_columns',
			'type'            => 'select',
			'settings'        => 'related_columns',
            'label'    => esc_html__( 'Products per view', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed for related products.', 'xstore' ),
			'section'         => 'shop-related-products',
			'default'         => 4,
			'choices'         => array(
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'related_type',
					'operator' => '==',
					'value'    => 'grid',
				),
			)
		),
		
		'related_limit' => array(
			'name'            => 'related_limit',
			'type'            => 'slider',
			'settings'        => 'related_limit',
            'label'    => esc_html__( 'Products limit', 'xstore' ),
            'tooltip'     => esc_html__( 'Use this option to limit the maximum number of products displayed for related products.', 'xstore' ),
			'section'         => 'shop-related-products',
			'default'         => 10,
			'choices'         => array(
				'min'  => 1,
				'max'  => 30,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_related',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
	);
	
	return array_merge( $fields, $args );
	
} );