<?php
/**
 * The template created for displaying product sale label options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_sale_label' => array(
			'name'       => 'product_sale_label',
			'title'      => esc_html__( '"Sale" label', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-awards',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_sale_label', function ( $fields ) use ( $separators, $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator 
		'product_sale_label_content_separator'            => array(
			'name'     => 'product_sale_label_content_separator',
			'type'     => 'custom',
			'settings' => 'product_sale_label_content_separator',
			'section'  => 'product_sale_label',
			'default'  => $separators['content'],
		),
		
		// product_sale_label_sale_type
		'product_sale_label_type_et-desktop'              => array(
			'name'      => 'product_sale_label_type_et-desktop',
			'type'      => 'radio-image',
			'settings'  => 'product_sale_label_type_et-desktop',
			'label'     => $strings['label']['type'],
            'tooltip'   => esc_html__('With this option, you can select a design type for the "Sale" label displayed on the main gallery on individual product pages.', 'xstore-core'),
			'section'   => 'product_sale_label',
			'default'   => 'square',
			'choices'   => array(
				'circle' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/Style-sale-label-1.svg',
				'square' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/Style-sale-label-2.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/None.svg',
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => 'none'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => 'none'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'type-circle',
					'value'    => 'circle'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'type-square',
					'value'    => 'square'
				),
			),
		),
		
		// product_sale_label_percentage
		'product_sale_label_percentage_et-desktop'        => array(
			'name'     => 'product_sale_label_percentage_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_sale_label_percentage_et-desktop',
            'label'       => esc_html__( 'Percentage', 'xstore-core' ),
            'tooltip'     => esc_html__( 'With this option, the "sale" label will be calculated as a percentage discount for the product based on its regular price and sale price.', 'xstore-core' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore-core'),
            )),
			'section'  => 'product_sale_label',
			'default'  => 0,
		),
		
		// product_sale_label_sale_text 
		'product_sale_label_text_et-desktop'              => array(
			'name'      => 'product_sale_label_text_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'product_sale_label_text_et-desktop',
			'label'     => esc_html__( 'Label text', 'xstore-core' ),
            'tooltip' => esc_html__( 'You can customize the text on your "Sale" label.', 'xstore-core' ),
            'data-tags' => implode('| ', array(
                esc_html__('"Sale" product label', 'xstore-core'),
            )),
			'section'   => 'product_sale_label',
			'default'   => esc_html__( 'Sale', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale:not(.with-percentage)',
					'function' => 'html',
				),
			),
		),
		
		// style separator 
		'product_sale_label_style_separator'              => array(
			'name'     => 'product_sale_label_style_separator',
			'type'     => 'custom',
			'settings' => 'product_sale_label_style_separator',
			'section'  => 'product_sale_label',
			'default'  => $separators['style'],
		),
		
		// product_sale_label_sale_position
		'product_sale_label_position_et-desktop'          => array(
			'name'      => 'product_sale_label_position_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_sale_label_position_et-desktop',
			'label'     => esc_html__( 'Position', 'xstore-core' ),
            'tooltip'  => esc_html__('Choose the placement of the "Sale" label on the product gallery.', 'xstore-core'),
			'section'   => 'product_sale_label',
			'default'   => 'right',
			'priority'  => 10,
			'choices'   => array(
				'left'  => esc_html__( 'Left', 'xstore-core' ),
				'right' => esc_html__( 'Right', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'right',
					'value'    => 'right'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'left',
					'value'    => 'left'
				),
			),
		),
		
		// product_sale_label_sale_position_asix
		'product_sale_label_position_asix_et-desktop'     => array(
			'name'      => 'product_sale_label_position_asix_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_position_asix_et-desktop',
			'label'     => esc_html__( 'Axis (px)', 'xstore-core' ),
            'tooltip' => esc_html__('This controls the X-axis and Y-axis positions of this element.', 'xstore-core'),
			'section'   => 'product_sale_label',
			'default'   => 12,
			'choices'   => array(
				'min'  => '-50',
				'max'  => '50',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale',
					'property' => 'top',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale.left',
					'property' => 'left',
					'units'    => 'px'
				),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.left',
                    'property' => 'right',
                    'units'    => 'px'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.left',
                    'property' => 'left',
                    'value_pattern'    => 'auto'
                ),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale.right',
					'property' => 'right',
					'units'    => 'px'
				),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.right',
                    'property' => 'left',
                    'units'    => 'px'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.right',
                    'property' => 'right',
                    'value_pattern'    => 'auto'
                ),
			),
		),
		
		// product_sale_label_sale_zoom
		'product_sale_label_content_zoom_et-desktop'      => array(
			'name'      => 'product_sale_label_content_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_content_zoom_et-desktop',
			'label' => $strings['label']['size_proportion'],
            'tooltip' => $strings['description']['size_proportion'],
			'section'   => 'product_sale_label',
			'default'   => 1,
			'choices'   => array(
				'min'  => '.7',
				'max'  => '5',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-sale-zoom-proportion'
				),
			),
		),
		
		// product_sale_label_sale_zoom
		'product_sale_label_zoom_et-desktop'              => array(
			'name'      => 'product_sale_label_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_zoom_et-desktop',
            'label' => esc_html__( 'Dimensions', 'xstore-core' ),
            'tooltip'     => esc_html__( 'This sets the minimum size for the "Sale" label', 'xstore-core'). '<br/>' .
                sprintf(esc_html__('Note: it works only for the "%1s" variant of the "Sale" label type.', 'xstore-core' ),
                '<span class="et_edit" data-parent="product_sale_label" data-section="product_sale_label_type_et-desktop" style="text-decoration: underline;">' . esc_html__( 'Breadcrumb settings', 'xstore-core' ) . '</span>', '<span class="et_edit" data-parent="header_overlap" data-section="header_overlap_content_separator" style="text-decoration: underline;">' . esc_html__('Circle', 'xstore-core') . '</span>'),
			'section'   => 'product_sale_label',
			'default'   => 50,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_sale_label_type_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'circle',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'min-width',
					'units'    => 'px'
				),
			),
		),
		
		// product_sale_label_sale_border_radius
		'product_sale_label_border_radius_et-desktop'     => array(
			'name'      => 'product_sale_label_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
            'tooltip'   => $strings['description']['border_radius'] . '<br/>' .
                sprintf(esc_html__('Note: it works only for the "%1s" variant of the "Sale" label type.', 'xstore-core' ),
        '<span class="et_edit" data-parent="product_sale_label" data-section="product_sale_label_type_et-desktop" style="text-decoration: underline;">' . esc_html__( 'Breadcrumb settings', 'xstore-core' ) . '</span>', '<span class="et_edit" data-parent="header_overlap" data-section="header_overlap_content_separator" style="text-decoration: underline;">' . esc_html__('Circle', 'xstore-core') . '</span>'),
			'section'   => 'product_sale_label',
			'default'   => 50,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_sale_label_type_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'circle',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		
		// product_sale_label_sale_background_custom
		'product_sale_label_background_custom_et-desktop' => array(
			'name'        => 'product_sale_label_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_sale_label_background_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_sale_label',
			'default'     => '#c62828',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale',
					'property' => 'background-color'
				),
			),
		),
		
		// product_sale_label_sale_color
		'product_sale_label_color_et-desktop'             => array(
			'name'        => 'product_sale_label_color_et-desktop',
			'settings'    => 'product_sale_label_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'tooltip' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_sale_label',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_sale_label)(product_sale_label_background_custom_et-desktop)',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale',
					'property' => 'color'
				)
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
