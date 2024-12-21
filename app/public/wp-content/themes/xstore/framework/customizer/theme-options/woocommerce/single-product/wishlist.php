<?php
/**
 * The template created for displaying single product wishlist options
 *
 * @version 0.0.1
 * @since   8.3.8
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-wishlist' => array(
			'name'       => 'single-product-page-wishlist',
			'title'      => esc_html__( 'Wishlist', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-heart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-wishlist' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(

        'xstore_wishlist_single_product_position' => array(
            'name'            => 'xstore_wishlist_single_product_position',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_single_product_position',
            'label'           => esc_html__( 'Position', 'xstore' ),
            'tooltip' => esc_html__('Choose the best placement for the "Wishlist" feature to be displayed on individual product pages.', 'xstore'),
            'section'  => 'single-product-page-wishlist',
            'default'         => 'after_cart_form',
            'choices'         => array(
                'none'      => esc_html__( 'Nowhere', 'xstore' ),
                'on_image'      => esc_html__( 'On product image', 'xstore' ),
                'after_atc'     => esc_html__( 'After "Add to cart"', 'xstore' ),
                'before_cart_form' => esc_html__( 'Before cart form', 'xstore' ),
                'after_cart_form' => esc_html__( 'After cart form', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_wishlist_label_add_to_wishlist
        'product_wishlist_label_add_to_wishlist'              => array(
            'name'     => 'product_wishlist_label_add_to_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'product_wishlist_label_add_to_wishlist',
            'label'    => esc_html__( '"Add to wishlist" text', 'xstore' ),
            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the wishlist action, with the default value being "Add to Wishlist".', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => esc_html__( 'Add to wishlist', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_wishlist_label_browse_wishlist
        'product_wishlist_label_browse_wishlist'              => array(
            'name'     => 'product_wishlist_label_browse_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'product_wishlist_label_browse_wishlist',
            'label'    => esc_html__( '"Browse wishlist" text', 'xstore' ),
            'tooltip'  => esc_html__( 'Customize the title text for browsing the wishlist or removing a product from the wishlist action, with the default value being "Browse wishlist".', 'xstore') . '<br/>' .
                sprintf(esc_html__('Note: This value may be written differently depending on the value of the "%1s" option you set. Tip: if the "Redirect on remove" option is active, it is better to write something similar to "Browse wishlist" text, but if that option is inactive, it is better to write "Remove from wishlist" text.', 'xstore' ),
                    '<span class="et_edit" data-parent="single-product-page-wishlist" data-section="product_wishlist_redirect_on_remove" style="text-decoration: underline;">'.esc_html__('Redirect on remove', 'xstore').'</span>'),
            'section'  => 'single-product-page-wishlist',
            'default'  => esc_html__( 'Browse wishlist', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_tooltip' => array(
            'name'     => 'product_wishlist_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_tooltip',
            'label'    => __( 'Tooltips', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to add tooltips to the "Wishlist" element in single product content. Tip: tooltips will look better if the "%1s" option below is enabled.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-wishlist" data-section="product_wishlist_only_icon" style="text-decoration: underline;">'.esc_html__('Only icon', 'xstore').'</span>'),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_only_icon' => array(
            'name'     => 'product_wishlist_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_only_icon',
            'label'    => __( 'Only icon', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to make the "Wishlist" element icon styled only. Tip: Enable the "%1s" option above which will make the wishlist icon look better and more informative.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-wishlist" data-section="product_wishlist_tooltip" style="text-decoration: underline;">'.esc_html__('Tooltips', 'xstore').'</span>'),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_redirect_on_remove' => array(
            'name'     => 'product_wishlist_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_redirect_on_remove',
            'label'    => __( 'Redirect on remove', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to automatically redirect customers to the wishlist page when they remove a product from their wishlist. Note: The wishlist page can be set in the "%1s" setting.', 'xstore'),
                '<span class="et_edit" data-parent="xstore_wishlist" data-section="xstore_wishlist_page" style="text-decoration: underline;">'.esc_html__('Wishlist page', 'xstore').'</span>'),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product wishlist
        'go_to_section_xstore_wishlist'                 => array(
            'name'     => 'go_to_section_xstore_wishlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_wishlist',
            'section'  => 'single-product-page-wishlist',
            'default'  => '<span class="et_edit" data-parent="xstore-wishlist" data-section="xstore_wishlist" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Global wishlist settings', 'xstore' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );