<?php
/**
 * The template created for displaying shop brands options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-brands' => array(
			'name'       => 'shop-brands',
			'title'      => esc_html__( 'Brands', 'xstore' ),
            'description' => esc_html__('With this powerful feature, your customers can easily find their favorite products from their preferred brands with just a few clicks. Create and assign brands to your products, and display them in a sleek and organized manner on your website.', 'xstore'),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-tickets',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-brands' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'enable_brands' => array(
			'name'        => 'enable_brands',
			'type'        => 'toggle',
			'settings'    => 'enable_brands',
			'label'       => esc_html__( 'Enable brands', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to give you the ability to assign brands to your products and display them in an orderly and attractive way on your website.', 'xstore' ),
			'section'     => 'shop-brands',
			'default'     => 1,
		),
		
		'product_page_brands' => array(
			'name'            => 'product_page_brands',
			'type'            => 'toggle',
			'settings'        => 'product_page_brands',
			'label'           => esc_html__( 'Show on archives', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to display the brands in your product content on product archive pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'product_view',
					'operator' => '!=',
					'value'    => 'custom',
				),
			)
		),
		
		'show_brand' => array(
			'name'            => 'show_brand',
			'type'            => 'toggle',
			'settings'        => 'show_brand',
			'label'           => esc_html__( 'Show on single product', 'xstore' ),
			'tooltip'     => esc_html__( 'Enable this option to display the brands on individual product pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		'brands_location' => array(
			'name'            => 'brands_location',
			'type'            => 'select',
			'settings'        => 'brands_location',
			'label'           => esc_html__( 'Position', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the location for the brand names on the individual product pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 'sidebar',
			'choices'         => array(
				'sidebar'       => esc_html__( 'Sidebar', 'xstore' ),
				'content'       => esc_html__( 'Above short description', 'xstore' ),
				'under_content' => esc_html__( 'In product meta', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_brand',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		'show_brand_image' => array(
			'name'            => 'show_brand_image',
			'type'            => 'toggle',
			'settings'        => 'show_brand_image',
			'label'           => esc_html__( 'Show image on single product', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Enable this option to display the brand\'s image (if the brand has its own image assigned) on the individual product pages. Note: you can select the brand image by uploading thumbnails for the brand when %1s the brand.', 'xstore' ),
                '<a href="'.admin_url('edit-tags.php?taxonomy=brand&post_type=product').'" target="_blank" rel="nofollow">'.esc_html__('creating/editing', 'xstore').'</a>'),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_brand',
					'operator' => '==',
					'value'    => true,
				),
//				array(
//					'setting'  => 'brands_location',
//					'operator' => '==',
//					'value'    => 'sidebar',
//				),
			)
		),
		
		'show_brand_title' => array(
			'name'            => 'show_brand_title',
			'type'            => 'toggle',
			'settings'        => 'show_brand_title',
			'label'           => esc_html__( 'Show title', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to display the brand\'s title on the individual product pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_brand',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'brands_location',
					'operator' => '==',
					'value'    => 'sidebar',
				),
			)
		),
		
		'show_brand_desc' => array(
			'name'            => 'show_brand_desc',
			'type'            => 'toggle',
			'settings'        => 'show_brand_desc',
			'label'           => esc_html__( 'Show description', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to display the brand\'s description on the individual product pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_brand',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'brands_location',
					'operator' => '==',
					'value'    => 'sidebar',
				),
			),
		),
		
		'brand_title' => array(
			'name'            => 'brand_title',
			'type'            => 'toggle',
			'settings'        => 'brand_title',
			'label'           => esc_html__( '"Brand" word', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to display the word "Brand" before the brand image on the individual product pages.', 'xstore' ),
			'section'         => 'shop-brands',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'enable_brands',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_brand',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'brands_location',
					'operator' => '!=',
					'value'    => 'sidebar',
				),
			)
		),
	);
	
	return array_merge( $fields, $args );
	
} );