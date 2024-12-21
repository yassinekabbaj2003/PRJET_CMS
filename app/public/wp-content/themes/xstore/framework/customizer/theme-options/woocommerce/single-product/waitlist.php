<?php
/**
 * The template created for displaying single product waitlist options
 *
 * @version 0.0.1
 * @since   8.3.8
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-waitlist' => array(
			'name'       => 'single-product-page-waitlist',
			'title'      => esc_html__( 'Waitlist', 'xstore' ),
            'description' => sprintf(esc_html__('Once you have %1s option activated products in your store that are Out of stock will have "Availability notify" button placed instead of add to cart form.', 'xstore'),
                '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="text-decoration: underline;">' . esc_html__( 'Waitlist', 'xstore' ) . '</span>'),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-bell',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-waitlist' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(

//        'xstore_waitlist_single_product_position' => array(
//            'name'            => 'xstore_waitlist_single_product_position',
//            'type'            => 'select',
//            'settings'        => 'xstore_waitlist_single_product_position',
//            'label'           => esc_html__( 'Position', 'xstore' ),
//            'tooltip' => esc_html__('Choose the best placement for the "Waitlist" feature to be displayed on individual product pages.', 'xstore'),
//            'section'  => 'single-product-page-waitlist',
//            'default'         => 'stock_message',
//            'choices'         => array(
//                'none'      => esc_html__( 'Nowhere', 'xstore' ),
////                'on_image'      => esc_html__( 'On product image', 'xstore' ),
//                'after_atc'     => esc_html__( 'After "Add to cart"', 'xstore' ),
//                'before_cart_form' => esc_html__( 'Before cart form', 'xstore' ),
//                'after_cart_form' => esc_html__( 'After cart form', 'xstore' ),
//                'stock_message' => esc_html__( 'Replace "Out of stock" message', 'xstore' )
//            ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // product_waitlist_label_add_to_waitlist
//        'product_waitlist_label_add_to_waitlist'              => array(
//            'name'     => 'product_waitlist_label_add_to_waitlist',
//            'type'     => 'etheme-text',
//            'settings' => 'product_waitlist_label_add_to_waitlist',
//            'label'    => esc_html__( '"Add to waitlist" text', 'xstore' ),
//            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the waitlist action, with the default value being "Add to Waitlist".', 'xstore' ),
//            'section'  => 'single-product-page-waitlist',
//            'default'  => esc_html__( 'Notify when available', 'xstore' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // product_waitlist_label_browse_waitlist
//        'product_waitlist_label_browse_waitlist'              => array(
//            'name'     => 'product_waitlist_label_browse_waitlist',
//            'type'     => 'etheme-text',
//            'settings' => 'product_waitlist_label_browse_waitlist',
//            'label'    => esc_html__( '"Browse waitlist" text', 'xstore' ),
//            'tooltip'  => esc_html__( 'Customize the title text for browsing the waitlist or removing a product from the waitlist action, with the default value being "Browse waitlist".', 'xstore') . '<br/>' .
//                sprintf(esc_html__('Note: This value may be written differently depending on the value of the "%1s" option you set. Tip: if the "Redirect on remove" option is active, it is better to write something similar to "Browse waitlist" text, but if that option is inactive, it is better to write "Remove from waitlist" text.', 'xstore' ),
//                    '<span class="et_edit" data-parent="single-product-page-waitlist" data-section="product_waitlist_redirect_on_remove" style="text-decoration: underline;">'.esc_html__('Redirect on remove', 'xstore').'</span>'),
//            'section'  => 'single-product-page-waitlist',
//            'default'  => esc_html__( 'Browse waitlist', 'xstore' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        'product_waitlist_tooltip' => array(
            'name'     => 'product_waitlist_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_waitlist_tooltip',
            'label'    => __( 'Tooltips', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to add tooltips to the "Waitlist" element in single product content. Tip: tooltips will look better if the "%1s" option below is enabled.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-waitlist" data-section="product_waitlist_only_icon" style="text-decoration: underline;">'.esc_html__('Only icon', 'xstore').'</span>'),
            'section'  => 'single-product-page-waitlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_waitlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_waitlist_only_icon' => array(
            'name'     => 'product_waitlist_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_waitlist_only_icon',
            'label'    => __( 'Only icon', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to make the "Waitlist" element icon styled only. Tip: Enable the "%1s" option above which will make the waitlist icon look better and more informative.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-waitlist" data-section="product_waitlist_tooltip" style="text-decoration: underline;">'.esc_html__('Tooltips', 'xstore').'</span>'),
            'section'  => 'single-product-page-waitlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_waitlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

//        'product_waitlist_redirect_on_remove' => array(
//            'name'     => 'product_waitlist_redirect_on_remove',
//            'type'     => 'toggle',
//            'settings' => 'product_waitlist_redirect_on_remove',
//            'label'    => __( 'Redirect on remove', 'xstore' ),
//            'tooltip' => sprintf(esc_html__('Enable this option to automatically redirect customers to the waitlist page when they remove a product from their waitlist. Note: The waitlist page can be set in the "%1s" setting.', 'xstore'),
//                '<span class="et_edit" data-parent="xstore_waitlist" data-section="xstore_waitlist_page" style="text-decoration: underline;">'.esc_html__('Waitlist page', 'xstore').'</span>'),
//            'section'  => 'single-product-page-waitlist',
//            'default'  => false,
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // go to product single product waitlist
        'go_to_section_xstore_waitlist'                 => array(
            'name'     => 'go_to_section_xstore_waitlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_waitlist',
            'section'  => 'single-product-page-waitlist',
            'default'  => '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Global waitlist settings', 'xstore' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_waitlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );