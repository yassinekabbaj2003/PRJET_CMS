<?php
/**
 * The template created for displaying single product compare options
 *
 * @version 0.0.1
 * @since   8.3.9
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-compare' => array(
			'name'       => 'single-product-page-compare',
			'title'      => esc_html__( 'Compare', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-update-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-compare' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(

        'xstore_compare_single_product_position' => array(
            'name'            => 'xstore_compare_single_product_position',
            'type'            => 'select',
            'settings'        => 'xstore_compare_single_product_position',
            'label'           => esc_html__( 'Position', 'xstore' ),
            'tooltip' => esc_html__('Choose the best placement for the "Compare" feature to be displayed on individual product pages.', 'xstore'),
            'section'  => 'single-product-page-compare',
            'default'         => 'after_cart_form',
            'choices'         => array(
                'none'      => esc_html__( 'Nowhere', 'xstore' ),
//                'on_image'      => esc_html__( 'On product image', 'xstore' ),
                'after_atc'     => esc_html__( 'After add to cart', 'xstore' ),
                'before_cart_form' => esc_html__( 'Before cart form', 'xstore' ),
                'after_cart_form' => esc_html__( 'After cart form', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_compare_label_add_to_compare
        'product_compare_label_add_to_compare'              => array(
            'name'     => 'product_compare_label_add_to_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_add_to_compare',
            'label'    => esc_html__( '"Add to compare" text', 'xstore' ),
            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the compare action, with the default value being "Add to compare".', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => esc_html__( 'Add to compare', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_compare_label_browse_compare
        'product_compare_label_browse_compare'              => array(
            'name'     => 'product_compare_label_browse_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_browse_compare',
            'label'    => esc_html__( '"Browse compare" text', 'xstore' ),
            'tooltip'  => esc_html__( 'Customize the title text for browsing the compare or removing a product from the compare action, with the default value being "Browse compare".', 'xstore') . '<br/>' .
                sprintf(esc_html__('Note: This value may be written differently depending on the value of the "%1s" option you set. Tip: if the "Redirect on remove" option is active, it is better to write something similar to "Browse compare" text, but if that option is inactive, it is better to write "Remove from compare" text.', 'xstore' ),
                    '<span class="et_edit" data-parent="single-product-page-compare" data-section="product_compare_redirect_on_remove" style="text-decoration: underline;">'.esc_html__('Redirect on remove', 'xstore').'</span>'),
            'section'  => 'single-product-page-compare',
            'default'  => esc_html__( 'Delete from compare', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_tooltip' => array(
            'name'     => 'product_compare_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_compare_tooltip',
            'label'    => __( 'Tooltip', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to add tooltips to the "Compare" element in single product content. Tip: tooltips will look better if the "%1s" option below is enabled.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-compare" data-section="product_compare_only_icon" style="text-decoration: underline;">'.esc_html__('Only icon', 'xstore').'</span>'),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_only_icon' => array(
            'name'     => 'product_compare_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_compare_only_icon',
            'label'    => __( 'Only icon', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to make the "Compare" element icon styled only. Tip: Enable the "%1s" option above which will make the compare icon look better and more informative.', 'xstore'),
                '<span class="et_edit" data-parent="single-product-page-compare" data-section="product_compare_tooltip" style="text-decoration: underline;">'.esc_html__('Tooltips', 'xstore').'</span>'),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_redirect_on_remove' => array(
            'name'     => 'product_compare_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_compare_redirect_on_remove',
            'label'    => __( 'Redirect on remove', 'xstore' ),
            'tooltip' => sprintf(esc_html__('Enable this option to automatically redirect customers to the compare page when they remove a product from their compare list. Note: The compare page can be set in the "%1s" setting.', 'xstore'),
                '<span class="et_edit" data-parent="xstore_compare" data-section="xstore_compare_page" style="text-decoration: underline;">'.esc_html__('Compare page', 'xstore').'</span>'),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product compare
        'go_to_section_xstore_compare'                 => array(
            'name'     => 'go_to_section_xstore_compare',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_compare',
            'section'  => 'single-product-page-compare',
            'default'  => '<span class="et_edit" data-parent="xstore-compare" data-section="xstore_compare" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Global compare settings', 'xstore' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );