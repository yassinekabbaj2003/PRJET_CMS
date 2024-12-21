<?php
/**
 * The template created for displaying product attributes options
 *
 * @version 1.0.0
 * @since   5.1.1
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_attributes' => array(
			'name'       => 'product_attributes',
			'title'      => esc_html__( 'Attributes', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-image-filter',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_attributes', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_attributes_style_separator' => array(
			'name'     => 'product_attributes_style_separator',
			'type'     => 'custom',
			'settings' => 'product_attributes_style_separator',
			'section'  => 'product_attributes',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		'product_attributes_zoom_et-desktop'  => array(
			'name'      => 'product_attributes_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_attributes_zoom_et-desktop',
			'label'     => $strings['label']['content_zoom'],
            'tooltip'   => $strings['description']['content_zoom'],
			'section'   => 'product_attributes',
			'default'   => 100,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// product_attributes_align
//		'product_attributes_align_et-desktop' => array(
//			'name'      => 'product_attributes_align_et-desktop',
//			'type'      => 'radio-buttonset',
//			'settings'  => 'product_attributes_align_et-desktop',
//			'label'     => $strings['label']['alignment'],
//            'tooltip'   => $strings['description']['alignment_with_inherit'],
//			'section'   => 'product_attributes',
//			'default'   => 'inherit',
//			'choices'   => $choices['alignment_with_inherit'],
//			'transport' => 'auto',
//			'output'    => array(
//				array(
//					'context'  => array( 'editor', 'front' ),
//					'element'  => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
//					'property' => 'text-align',
//				),
//			),
//		),
		
//		'product_short_color_custom_et-desktop' => array(
//			'name'      => 'product_short_color_custom_et-desktop',
//			'type'      => 'color',
//			'settings'  => 'product_short_color_custom_et-desktop',
//			'label'     => $strings['label']['color'],
//            'tooltip' => esc_html__( 'Choose the text color for this element.', 'xstore-core' ),
//			'section'   => 'product_attributes',
//			'default'   => '#555555',
//			'choices'   => array(
//				'alpha' => true
//			),
//			'transport' => 'auto',
//			'output'    => array(
//				array(
//					'context'  => array( 'editor', 'front' ),
//					'element'  => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
//					'property' => 'color',
//				),
//			),
//		),
		
//		'product_attributes_box_model_et-desktop'           => array(
//			'name'        => 'product_attributes_box_model_et-desktop',
//			'settings'    => 'product_attributes_box_model_et-desktop',
//			'label'       => $strings['label']['computed_box'],
//			'tooltip' => $strings['description']['computed_box'],
//			'type'        => 'kirki-box-model',
//			'section'     => 'product_attributes',
//			'default'     => array(
//				'margin-top'          => '0px',
//				'margin-right'        => '0px',
//				'margin-bottom'       => '15px',
//				'margin-left'         => '0px',
//				'border-top-width'    => '0px',
//				'border-right-width'  => '0px',
//				'border-bottom-width' => '0px',
//				'border-left-width'   => '0px',
//				'padding-top'         => '0px',
//				'padding-right'       => '0px',
//				'padding-bottom'      => '0px',
//				'padding-left'        => '0px',
//			),
//			'output'      => array(
//				array(
//					'context' => array( 'editor', 'front' ),
//					'element' => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
//				),
//			),
//			'transport'   => 'postMessage',
//			'js_vars'     => box_model_output( '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes' )
//		),
		
		// product_attributes_border
//		'product_attributes_border_et-desktop'              => array(
//			'name'      => 'product_attributes_border_et-desktop',
//			'type'      => 'select',
//			'settings'  => 'product_attributes_border_et-desktop',
//			'label'     => $strings['label']['border_style'],
//            'tooltip'   => $strings['description']['border_style'],
//			'section'   => 'product_attributes',
//			'default'   => 'solid',
//			'choices'   => $choices['border_style'],
//			'transport' => 'auto',
//			'output'    => array(
//				array(
//					'context'  => array( 'editor', 'front' ),
//					'element'  => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
//					'property' => 'border-style'
//				),
//			),
//		),
		
		// product_attributes_border_color_custom
//		'product_attributes_border_color_custom_et-desktop' => array(
//			'name'      => 'product_attributes_border_color_custom_et-desktop',
//			'type'      => 'color',
//			'settings'  => 'product_attributes_border_color_custom_et-desktop',
//			'label'     => $strings['label']['border_color'],
//            'tooltip'   => $strings['description']['border_color'],
//			'section'   => 'product_attributes',
//			'default'   => '#e1e1e1',
//			'choices'   => array(
//				'alpha' => true
//			),
//			'transport' => 'auto',
//			'output'    => array(
//				array(
//					'context'  => array( 'editor', 'front' ),
//					'element'  => '.single-product .et_connect-block > .shop_attributes, .single-product .et_product-block > .shop_attributes',
//					'property' => 'border-color',
//				),
//			),
//		),

        // advanced separator
        'product_attributes_advanced_separator' => array(
            'name' => 'product_attributes_advanced_separator',
            'type' => 'custom',
            'settings' => 'product_attributes_advanced_separator',
            'section' => 'product_attributes',
            'default' => $separators['advanced'],
        ),

        'attributes_after_description' => array(
            'name'        => 'attributes_after_description',
            'type'        => 'toggle',
            'settings'    => 'attributes_after_description',
            'label'       => esc_html__( 'Show in quick view', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__( 'Enable this option to display the product attributes (e.g. color, size, material) after the product short description in %1s.', 'xstore-core' ), '<span class="et_edit" data-parent="shop-quick-view" data-section="quick_view" style="text-decoration: underline;">' . esc_html__('quick view', 'xstore-core') . '</span>'),
            'section'     => 'product_attributes',
            'default'     => 0,
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );
