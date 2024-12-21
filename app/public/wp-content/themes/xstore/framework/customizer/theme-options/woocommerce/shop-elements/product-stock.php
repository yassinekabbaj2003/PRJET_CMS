<?php
/**
 * The template created for displaying product stock options
 *
 * @version 0.0.1
 * @since   6.2.2
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product-stock' => array(
			'name'       => 'product-stock',
			'title'      => esc_html__( 'Advanced product stock', 'xstore' ),
            'description' => esc_html__('The "Advanced Stock" will show customers how many units of the product have been sold and how many are still available. The "Advanced Stock" has an attractive design and the information it provides about product stock is easily understandable for any customer at first glance.', 'xstore'),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-chart-line',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/product-stock' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'advanced_stock_status' => array(
			'name'        => 'advanced_stock_status',
			'type'        => 'toggle',
			'settings'    => 'advanced_stock_status',
			'label'       => esc_html__( 'Enable advanced stock', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'Enable this option to enable an advanced stock line for products with the %1s option enabled.', 'xstore' ),
                '<a href="https://woocommerce.com/document/managing-products/#product-data" target="_blank" rel="nofollow">'.esc_html__('managed stock', 'xstore').'</a>'),
			'section'     => 'product-stock',
			'default'     => 0,
		),
		
		// advanced_stock_locations
		'advanced_stock_locations'  => array(
			'name'        => 'advanced_stock_locations',
			'type'        => 'select',
			'settings'    => 'advanced_stock_locations',
			'label'       => esc_html__( 'Advanced stock locations', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the locations for the advanced stock line of product.', 'xstore' ),
			'section'     => 'product-stock',
			'placeholder' => esc_html__( 'Choose locations', 'xstore' ),
			'multiple'    => 3,
			'default'     => array(
				'single_product',
				'quick_view'
			),
			'choices'     => array(
				'single_product'                => esc_html__( 'Single product', 'xstore' ),
				'quick_view'                => esc_html__( 'Quick view', 'xstore' ),
				'product_archives' => esc_html__( 'Product archives', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'advanced_stock_status',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		'product_stock_colors' => array(
			'name'        => 'product_stock_colors',
			'type'        => 'multicolor',
			'settings'    => 'product_stock_colors',
			'label'       => esc_html__( 'Stock colors', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Choose the colors for the different product stock levels. Note: Here you can check the "%1s" of your products configured on your website.', 'xstore'),
			'<a href="' . admin_url( "admin.php?page=wc-settings&tab=products&section=inventory" ) . '" target="_blank">' . esc_html__( 'Low stock threshold values', 'xstore' ) . '</a>'),
			'section'     => 'product-stock',
			'choices'     => array(
				'step1' => esc_html__( 'Full stock', 'xstore' ),
				'step2' => esc_html__( 'Middle stock (sold 50%+)', 'xstore' ),
				'step3' => esc_html__( 'Low stock', 'xstore' ),
			),
			'default'     => array(
				'step1' => '#2e7d32',
				'step2' => '#f57f17',
				'step3' => '#c62828',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'step1',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-1-active-color',
				),
				array(
					'choice'   => 'step2',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-2-active-color',
				),
				array(
					'choice'   => 'step3',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--product-stock-step-3-active-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );