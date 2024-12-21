<?php
/**
 * The template created for displaying catalog mode options
 *
 * @version 0.0.2
 * @since   6.0.0
 */

// section catalog-mode
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'catalog-mode' => array(
			'name'       => 'catalog-mode',
			'title'      => esc_html__( 'Catalog Mode', 'xstore' ),
			'panel'      => 'shop',
			'icon'       => 'dashicons-hidden', // dashicons-tickets-alt
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/catalog-mode' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'just_catalog' => array(
			'name'        => 'just_catalog',
			'type'        => 'toggle',
			'settings'    => 'just_catalog',
			'label'       => esc_html__( 'Catalog mode', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to prevent customers from purchasing products on your website. Note: most "Add to Cart" buttons will be removed or hidden.', 'xstore' ),
			'section'     => 'catalog-mode',
			'default'     => 0,
		),
		
		'just_catalog_type' => array(
			'name'            => 'just_catalog_type',
			'type'            => 'select',
			'settings'        => 'just_catalog_type',
			'label'           => esc_html__( 'Permission restrictions rules', 'xstore' ),
            'tooltip'         => esc_html__( 'Choose which type of customers will see your website in the catalog view version.', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => 'all',
			'choices'         => array(
				'all'          => esc_html__( 'All', 'xstore' ),
				'unregistered' => esc_html__( 'Not logged in', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
		
		'just_catalog_price' => array(
			'name'            => 'just_catalog_price',
			'type'            => 'toggle',
			'settings'        => 'just_catalog_price',
			'label'           => esc_html__( 'Hide price', 'xstore' ),
            'tooltip' => esc_html__( 'Use this option to prevent customers from viewing the prices of products on your website.', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
		
		'ltv_price' => array(
			'name'            => 'ltv_price',
			'type'            => 'etheme-text',
			'settings'        => 'ltv_price',
			'label'           => esc_html__( 'Price text', 'xstore' ),
			'tooltip'     => esc_html__( 'Customize the text that will be displayed instead of the product price on the product archive pages and simple product pages. Note: Leave the field empty to hide the text and prices completely.', 'xstore' ),
			'section'         => 'catalog-mode',
			'default'         => esc_html__( 'Login to view price', 'xstore' ),
			'active_callback' => array(
				array(
					'setting'  => 'just_catalog',
					'operator' => '=',
					'value'    => 1,
				),
				array(
					'setting'  => 'just_catalog_price',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),

        'ltv_price_button' => array(
            'name'            => 'ltv_price_button',
            'type'            => 'toggle',
            'settings'        => 'ltv_price_button',
            'label'           => esc_html__( 'Loginizer price', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to display your price text (you set above) as button with link to login.', 'xstore' ),
            'section'         => 'catalog-mode',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'just_catalog',
                    'operator' => '=',
                    'value'    => 1,
                ),
                array(
                    'setting'  => 'just_catalog_price',
                    'operator' => '=',
                    'value'    => 1,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );