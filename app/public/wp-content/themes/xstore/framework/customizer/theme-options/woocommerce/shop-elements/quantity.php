<?php
/**
 * The template created for displaying shop icons options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-quantity' => array(
			'name'       => 'shop-quantity',
			'title'      => esc_html__( 'Quantity', 'xstore' ),
            'description' => esc_html__('This feature allows customers to select the quantity of the product they want to purchase with ease, making their shopping experience quick and hassle-free. Whether you\'re running an online store with a large inventory or just a few products, the product quantity option will help you manage your stock levels efficiently.', 'xstore'),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-chart-pie',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-quantity' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {

    $is_spb = get_option( 'etheme_single_product_builder', false );

	$args = array();
	
	// Array of fields
	$args = array(
        'shop_quantity_type' => array(
            'name' => 'shop_quantity_type',
            'type' => 'select',
            'settings' => 'shop_quantity_type',
            'label' => esc_html__('Quantity type', 'xstore'),
            'tooltip' => ($is_spb ?
                sprintf(esc_html__('Choose the quantity type to be displayed on your product archive pages if you have "%1s" option enabled in the content of the product. Note: to set the quantity type for your single product, please go to the %2s.', 'xstore'),
                    '<span class="et_edit" data-parent="products-style" data-section="product_page_smart_addtocart" style="text-decoration: underline;">' . esc_html__('Add to cart with quantity', 'xstore') . '</span>',
                    '<span class="et_edit" data-parent="product_cart_form" data-section="product_cart_form_direction_et-desktop" style="text-decoration: underline;">' . esc_html__('corresponding options', 'xstore') . '</span>'):
                sprintf(esc_html__('Choose the type of quantity to be displayed on your product archive pages and individual product pages. Make sure that the "%1s" option is enabled in order for the quantity type to be displayed correctly on your product archive pages.', 'xstore'),
                    '<span class="et_edit" data-parent="products-style" data-section="product_page_smart_addtocart" style="text-decoration: underline;">' . esc_html__('Add to cart with quantity', 'xstore') . '</span>')).
                '<br/>' . esc_html__('Tip: It is always possible to configure specific settings for quantity types and quantity select range on the single product page settings page from the dashboard.', 'xstore'),
            'section' => 'shop-quantity',
            'default' => 'input',
            'choices' => array(
                'input' => esc_html__('Input', 'xstore'),
                'select' => esc_html__('Select', 'xstore'),
            ),
        ),

        'shop_quantity_select_ranges' => array(
            'name'        => 'shop_quantity_select_ranges',
            'type'        => 'etheme-textarea',
            'settings'    => 'shop_quantity_select_ranges',
            'label'       => esc_html__( 'Ranges', 'xstore' ),
            'tooltip' => esc_html__( 'Add variants and allow the customer to select the quantity of products shown in the selection. Enter each value on one line and use a range, e.g. "1-5".', 'xstore' ) .
                '<br/>' . esc_html__('Tip: It is always possible to configure specific settings for quantity types and quantity select range on the single product page settings page from the dashboard.', 'xstore'),
            'section'     => 'shop-quantity',
            'default'     => '1-5',
            'active_callback' => array(
                array(
                    'setting' => 'shop_quantity_type',
                    'operator' => '==',
                    'value' => 'select',
                ),
            ),
        ),
	);

    if ( $is_spb ) {
        $args['go_to_section_spb_product_cart_form_direction'] = array(
            'name' => 'go_to_section_spb_product_cart_form_direction',
            'type' => 'custom',
            'settings' => 'go_to_section_spb_product_cart_form_direction',
            'section' => 'shop-quantity',
            'default' => '<span class="et_edit" data-parent="product_cart_form" data-section="product_cart_form_direction_et-desktop" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__('Single product quantity', 'xstore') . '</span>',
        );
    }
	
	return array_merge( $fields, $args );
	
} );