<?php
/**
 * The template created for displaying product upsels product options
 *
 * @version 1.0.2
 * @since   3.3.4
 * @log     added mobile products per view
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'products_cross_sell' => array(
			'name'       => 'products_cross_sell',
			'title'      => esc_html__( 'Cross-sell products', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-tag',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/products_cross_sell', function ( $fields ) use ( $separators, $product_settings, $strings, $sep_style, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'products_cross_sell_content_separator'                => array(
			'name'     => 'products_cross_sell_content_separator',
			'type'     => 'custom',
			'settings' => 'products_cross_sell_content_separator',
			'section'  => 'products_cross_sell',
			'default'  => $separators['content'],
		),
		
		// products_cross_sell_type
		'products_cross_sell_type_et-desktop'                  => array(
			'name'     => 'products_cross_sell_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'products_cross_sell_type_et-desktop',
            'label'    => $strings['label']['type'],
            'tooltip'  => esc_html__('Choose the design type for the cross-sell products.', 'xstore-core'),
			'section'  => 'products_cross_sell',
			'default'  => 'slider',
			'multiple' => 1,
			'choices'  => $choices['product_types']
		),
		
		// products_cross_sell_per_view
		'products_cross_sell_per_view_et-desktop'              => array(
			'name'     => 'products_cross_sell_per_view_et-desktop',
			'type'     => 'slider',
			'settings' => 'products_cross_sell_per_view_et-desktop',
            'label'    => esc_html__( 'Products per view', 'xstore-core' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed for cross-sell products.', 'xstore-core' ),
			'section'  => 'products_cross_sell',
			'default'  => 4,
			'choices'  => array(
				'min'  => '1',
				'max'  => '6',
				'step' => '1',
			),
		),
		
		// products_cross_sell_per_view
		'products_cross_sell_per_view_et-mobile'               => array(
			'name'     => 'products_cross_sell_per_view_et-mobile',
			'type'     => 'slider',
			'settings' => 'products_cross_sell_per_view_et-mobile',
            'label'    => esc_html__( 'Products per view (carousel)', 'xstore-core' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed for cross-sell products carousel.', 'xstore-core' ),
			'section'  => 'products_cross_sell',
			'default'  => 2,
			'choices'  => array(
				'min'  => '1',
				'max'  => '6',
				'step' => '1',
			),
		),
		
		// products_cross_sell_limit
		'products_cross_sell_limit_et-desktop'                 => array(
			'name'     => 'products_cross_sell_limit_et-desktop',
			'type'     => 'slider',
			'settings' => 'products_cross_sell_limit_et-desktop',
            'label'    => esc_html__( 'Products limit', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Use this option to limit the maximum number of products displayed for cross-sell products.', 'xstore-core' ),
			'section'  => 'products_cross_sell',
			'default'  => 7,
			'choices'  => array(
				'min'  => '1',
				'max'  => '30',
				'step' => '1',
			),
		),
		
		// products_cross_sell_out_of_stock
		'products_cross_sell_out_of_stock_et-desktop'          => array(
			'name'     => 'products_cross_sell_out_of_stock_et-desktop',
			'type'     => 'toggle',
			'settings' => 'products_cross_sell_out_of_stock_et-desktop',
            'label'    => esc_html__( 'Hide out of stock products', 'xstore-core' ),
            'tooltip' => esc_html__('Enable this option to hide products that are out of stock in cross-sell products.', 'xstore-core'),
			'section'  => 'products_cross_sell',
			'default'  => 0,
		),

        // products_cross_sells_sale_label
        'products_cross_sells_sale_label_et-desktop'           => array(
            'name'     => 'products_cross_sells_sale_label_et-desktop',
            'type'     => 'toggle',
            'settings' => 'products_cross_sells_sale_label_et-desktop',
            'label'    => esc_html__( 'Hide "Sale" label', 'xstore-core' ),
            'tooltip' => esc_html__('Enable this option to hide "Sale" label for products in cross-sell products.', 'xstore-core'),
            'section'  => 'products_cross_sell',
            'default'  => 0,
        ),

        'products_cross_sells_title_style_separator' => array(
            'name' => 'products_cross_sells_title_style_separator',
            'type' => 'custom',
            'settings' => 'products_cross_sells_title_style_separator',
            'section' => 'products_cross_sell',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Title styles', 'xstore-core') . '</span></div>',
        ),

        // products_cross_sell_title_align
        'products_cross_sell_title_align_et-desktop'           => array(
            'name'            => 'products_cross_sell_title_align_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'products_cross_sell_title_align_et-desktop',
            'label'           => $strings['label']['alignment'],
            'tooltip'         => $strings['description']['alignment_with_inherit'],
            'section'         => 'products_cross_sell',
            'default'         => 'start',
            'choices'         => $choices['alignment_with_inherit'],
            'active_callback' => array(
                array(
                    'setting'  => 'products_cross_sell_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'widget',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.single-product .cross-sell-products-title',
                    'property' => 'text-align',
                ),
            ),
        ),

        // products_cross_sell_title_sizes
        'products_cross_sell_title_sizes_et-desktop'           => array(
            'name'            => 'products_cross_sell_title_sizes_et-desktop',
            'type'            => 'radio-buttonset',
            'settings'        => 'products_cross_sell_title_sizes_et-desktop',
            'label'           => $strings['label']['title_sizes'],
            'tooltip'   => esc_html__( 'Choose which size should be applied for the element\'s main title. Select "Custom" to configure custom size proportions in the form below.', 'xstore-core' ),
            'section'         => 'products_cross_sell',
            'default'         => 'inherit',
            'choices'         => array(
                'inherit' => esc_html__( 'Inherit', 'xstore-core' ),
                'custom'  => esc_html__( 'Custom', 'xstore-core' )
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'products_cross_sell_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'widget',
                ),
            ),
        ),

        // products_cross_sells_title_size_proportion
        'products_cross_sell_title_size_proportion_et-desktop' => array(
            'name'            => 'products_cross_sell_title_size_proportion_et-desktop',
            'type'            => 'slider',
            'settings'        => 'products_cross_sell_title_size_proportion_et-desktop',
            'label'           => $strings['label']['title_size_proportion'],
            'tooltip'   => esc_html__( 'This option allows you to increase or decrease the size proportion of the element\'s main title.', 'xstore-core' ),
            'section'         => 'products_cross_sell',
            'default'         => 1.71,
            'choices'         => array(
                'min'  => '0',
                'max'  => '5',
                'step' => '.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'products_cross_sell_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'widget',
                ),
                array(
                    'setting'  => 'products_cross_sell_title_sizes_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.single-product .cross-sell-products-title',
                    'property' => '--h2-size-proportion',
                ),
            ),
        ),

        // products_cross_sells_title_line_height
        'products_cross_sell_title_line_height_et-desktop'     => array(
            'name'            => 'products_cross_sell_title_line_height_et-desktop',
            'type'            => 'slider',
            'settings'        => 'products_cross_sell_title_line_height_et-desktop',
            'label'           => esc_html__( 'Line height', 'xstore-core' ),
            'tooltip'   => esc_html__( 'This option allows you to increase or decrease the line-height proportion of the element\'s main title.', 'xstore-core' ),
            'section'         => 'products_cross_sell',
            'default'         => 1.5,
            'choices'         => array(
                'min'  => '0',
                'max'  => '3',
                'step' => '.1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'products_cross_sell_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'widget',
                ),
                array(
                    'setting'  => 'products_cross_sell_title_sizes_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.single-product .cross-sell-products-title',
                    'property' => '--h2-line-height',
                ),
            ),
        ),

        // products_cross_sell_title_margin_bottom
        'products_cross_sell_title_margin_bottom_et-desktop'   => array(
            'name'            => 'products_cross_sell_title_margin_bottom_et-desktop',
            'type'            => 'slider',
            'settings'        => 'products_cross_sell_title_margin_bottom_et-desktop',
            'label'           => esc_html__( 'Bottom space', 'xstore-core' ),
            'tooltip'   => esc_html__( 'This option controls the bottom margin of the element\'s main title.', 'xstore-core' ),
            'section'         => 'products_cross_sell',
            'default'         => 20,
            'choices'         => array(
                'min'  => '0',
                'max'  => '50',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'products_cross_sell_type_et-desktop',
                    'operator' => '!=',
                    'value'    => 'widget',
                ),
                array(
                    'setting'  => 'products_cross_sell_title_sizes_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport'       => 'auto',
            'output'          => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.single-product .cross-sell-products-title',
                    'property' => 'margin-bottom',
                    'units'    => 'px'
                ),
            ),
        ),
		
		// style separator
		'products_cross_sell_style_separator'                  => array(
			'name'     => 'products_cross_sell_style_separator',
			'type'     => 'custom',
			'settings' => 'products_cross_sell_style_separator',
			'section'  => 'products_cross_sell',
			'default'  => $separators['style'],
		),
		
		// products_cross_sell_zoom
		// 'products_cross_sell_zoom_et-desktop'	=>' array(
		// 'name'		  => 'products_cross_sell_zoom_et-desktop',
		// 	'type'        => 'slider',
		// 	'settings'    => 'products_cross_sell_zoom_et-desktop',
		// 	'label'       => $strings['label']['content_zoom']
		// 	'section'     => 'products_cross_sell',
		// 	'default'     => 100,
		// 	'choices'     => array(
		// 		'min'  => '0',
		// 		'max'  => '200',
		// 		'step' => '1',
		// 	),
		// 	'transport' => 'auto',
		// 	'output' => array(
		// 		array(
		// 'context'   => array('editor', 'front'),
		// 			'element' => '.cross_sells ul.products li',
		// 			'property' => '--content-zoom',
		// 			'value_pattern' => 'calc($em * .01)'
		// 		),
		// 	),
		// ),
		
		// products_cross_sell_cols_gap
		'products_cross_sell_cols_gap_et-desktop'              => array(
			'name'     => 'products_cross_sell_cols_gap_et-desktop',
			'type'     => 'slider',
			'settings' => 'products_cross_sell_cols_gap_et-desktop',
            'label'    => $strings['label']['cols_gap'],
            'tooltip'  => $strings['description']['cols_gap'],
			'section'  => 'products_cross_sell',
			'default'  => 15,
			'choices'  => array(
				'min'  => '0',
				'max'  => '80',
				'step' => '1',
			),
			'output'   => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cross-sell-products',
					'property' => '--cols-gap',
					'units'    => 'px'
				),
			),
		),
		
		// products_cross_sell_rows_gap
		'products_cross_sell_rows_gap_et-desktop'              => array(
			'name'            => 'products_cross_sell_rows_gap_et-desktop',
			'type'            => 'slider',
			'settings'        => 'products_cross_sell_rows_gap_et-desktop',
            'label'    => $strings['label']['rows_gap'],
            'tooltip'  => $strings['description']['rows_gap'],
			'section'         => 'products_cross_sell',
			'default'         => 15,
			'choices'         => array(
				'min'  => '0',
				'max'  => '80',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '!=',
					'value'    => 'slider',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cross-sell-products',
					'property' => '--rows-gap',
					'units'    => 'px'
				),
			),
		),
		
		// product_gallery_arrow_size
		'products_cross_sell_arrow_size'                       => array(
			'name'            => 'products_cross_sell_arrow_size',
			'type'            => 'slider',
			'settings'        => 'products_cross_sell_arrow_size',
			'label'           => esc_html__( 'Arrows size (px)', 'xstore-core' ),
            'tooltip'         => esc_html__('This controls the size of the arrows for this element\'s slider.', 'xstore-core'),
			'section'         => 'products_cross_sell',
			'default'         => 50,
			'choices'         => array(
				'min'  => '20',
				'max'  => '90',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '==',
					'value'    => 'slider',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cross-sell-products',
					'property' => '--arrow-size',
					'units'    => 'px'
				),
			)
		),
		
		// products_cross_sell_content_align
		// 'products_cross_sell_content_align_et-desktop'	=>' array(
		// 'name'		  => 'products_cross_sell_content_align_et-desktop',
		//     'type'        => 'radio-buttonset',
		//     'settings'    => 'products_cross_sell_content_align_et-desktop',
		//     'label'       => $strings['label']['alignment'],
		//     'section'     => 'products_cross_sell',
		//     'default'     => 'inherit',
		//     'choices'     => $choices['alignment_with_inherit'],
		// ),
		
		// products_cross_sell_content_align_custom
		// 'products_cross_sell_content_align_custom_et-desktop'	=>' array(
		// 'name'		  => 'products_cross_sell_content_align_custom_et-desktop',
		//     'type'        => 'slider',
		//     'settings'    => 'products_cross_sell_content_align_custom_et-desktop',
		//     'label'       => esc_html__( 'Offset align content', 'xstore-core' ),
		//     'section'     => 'products_cross_sell',
		//     'default'     => 0,
		//     'choices'     => array(
		//         'min'  => 0,
		//         'max'  => 70,
		//         'step' => 1,
		//     ),
		//     'active_callback' => array(
		//         array(
		//             'setting'  => 'products_cross_sell_content_align_et-desktop',
		//             'operator' => '!=',
		//             'value'    => 'center',
		//         ),
		//     ),
		//     'transport' => 'auto',
		//     'output'      => array(
		//         array(
		// 'context'   => array('editor', 'front'),
		//             'element' => '.cross_sells ul.products .product.align-start .product-content',
		//             'property' => 'padding-left',
		//             'value_pattern' => 'calc(var(--separate-elements-space-inside) + $px)'
		//         ),
		//         array(
		// 'context'   => array('editor', 'front'),
		//             'element' => '.cross_sells ul.products  .product.align-start .product-image-wrap',
		//             'property' => 'margin-left',
		//             'value_pattern' => '-$px'
		//         ),
		//         array(
		// 'context'   => array('editor', 'front'),
		//             'element' => '.cross_sells ul.products  .product.align-end .product-content',
		//             'property' => 'padding-right',
		//             'value_pattern' => 'calc(var(--separate-elements-space-inside) + $px)'
		//         ),
		//         array(
		// 'context'   => array('editor', 'front'),
		//             'element' => '.cross_sells ul.products  .product.align-end .product-image-wrap',
		//             'property' => 'margin-right',
		//             'value_pattern' => '-$px'
		//         ),
		//     ),
		// ),

		'products_cross_sell_box_model_et-desktop'             => array(
			'name'        => 'products_cross_sell_box_model_et-desktop',
			'settings'    => 'products_cross_sell_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'tooltip' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'products_cross_sell',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '30px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cross-sell-products-wrapper',
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.cross-sell-products-wrapper' )
		),
		
		// products_cross_sell_border
		'products_cross_sell_border_et-desktop'                => array(
			'name'      => 'products_cross_sell_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'products_cross_sell_border_et-desktop',
			'label'     => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
			'section'   => 'products_cross_sell',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cross-sell-products-wrapper',
					'property' => 'border-style'
				)
			)
		),
		
		// products_cross_sell_border_color_custom
		'products_cross_sell_border_color_custom_et-desktop'   => array(
			'name'      => 'products_cross_sell_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'products_cross_sell_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
			'section'   => 'products_cross_sell',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cross-sell-products-wrapper',
					'property' => 'border-color',
				),
			),
		),
		
		// advanced separator
		'products_cross_sell_advanced_separator'               => array(
			'name'            => 'products_cross_sell_advanced_separator',
			'type'            => 'custom',
			'settings'        => 'products_cross_sell_advanced_separator',
			'section'         => 'products_cross_sell',
			'default'         => $separators['advanced'],
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '!=',
					'value'    => 'widget',
				),
			),
		),
		
		// products_cross_sell_product_view
		'products_cross_sells_product_view_et-desktop'         => array(
			'name'            => 'products_cross_sells_product_view_et-desktop',
			'type'            => 'select',
			'settings'        => 'products_cross_sells_product_view_et-desktop',
			'label'           => $strings['label']['product_view'],
			'tooltip'     => $strings['description']['product_view'],
			'section'         => 'products_cross_sell',
			'default'         => 'inherit',
			'choices'         => $product_settings['view'],
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '!=',
					'value'    => 'widget',
				),
			),
		),
		
		// products_cross_sell_product_view_color
		'products_cross_sells_product_view_color_et-desktop'   => array(
			'name'            => 'products_cross_sells_product_view_color_et-desktop',
			'type'            => 'select',
			'settings'        => 'products_cross_sells_product_view_color_et-desktop',
			'label'           => $strings['label']['product_view_color'],
			'tooltip'     => $strings['description']['product_view_color'],
			'section'         => 'products_cross_sell',
			'default'         => 'inherit',
			'choices'         => $product_settings['view_color'],
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '!=',
					'value'    => 'widget',
				),
				array(
					'setting'  => 'products_cross_sells_product_view_et-desktop',
					'operator' => 'in',
					'value'    => array( 'default', 'info', 'mask', 'mask2', 'mask3' ),
				),
			)
		),
		
		// products_cross_sell_product_img_hover
		'products_cross_sells_product_img_hover_et-desktop'    => array(
			'name'            => 'products_cross_sells_product_img_hover_et-desktop',
			'type'            => 'select',
			'settings'        => 'products_cross_sells_product_img_hover_et-desktop',
			'label'           => $strings['label']['product_img_hover'],
			'tooltip'     => $strings['description']['product_img_hover'],
			'section'         => 'products_cross_sell',
			'default'         => 'inherit',
			'choices'         => $product_settings['img_hover'],
			'active_callback' => array(
				array(
					'setting'  => 'products_cross_sell_type_et-desktop',
					'operator' => '!=',
					'value'    => 'widget',
				),
				array(
					'setting'  => 'products_cross_sells_product_view_et-desktop',
					'operator' => '!=',
					'value'    => 'custom',
				),
			)
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
