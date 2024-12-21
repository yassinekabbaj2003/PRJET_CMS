<?php
/**
 * The template created for displaying single product rating options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_rating' => array(
			'name'       => 'product_rating',
			'title'      => esc_html__( 'Rating', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-star-filled',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/product_rating', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_rating_content_separator'              => array(
			'name'     => 'product_rating_content_separator',
			'type'     => 'custom',
			'settings' => 'product_rating_content_separator',
			'section'  => 'product_rating',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// product_rating_align
		'product_rating_align_et-desktop'               => array(
			'name'      => 'product_rating_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_rating_align_et-desktop',
			'label'     => $strings['label']['alignment'],
            'tooltip'   => $strings['description']['alignment_with_inherit'],
			'section'   => 'product_rating',
			'default'   => 'inherit',
			'choices'   => $choices['alignment_with_inherit'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-rating',
					'property' => 'text-align',
				),
			),
		),

        // go to product images sizes
        'go_to_section_star-rating-color'                 => array(
            'name'     => 'go_to_section_star-rating-color',
            'type'     => 'custom',
            'label'       => $strings['label']['color'],
            'tooltip' => esc_html__( 'The color of the product rating stars is set globally for all cases.', 'xstore-core' ),
            'settings' => 'go_to_section_star-rating-color',
            'section'  => 'product_rating',
            'default'  => '<span class="et_edit" data-parent="products-style" data-section="star-rating-color" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Rating stars color', 'xstore-core' ) . '</span>',
        ),

		'product_rating_box_model_et-desktop'           => array(
			'name'        => 'product_rating_box_model_et-desktop',
			'settings'    => 'product_rating_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_rating',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '10px',
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
					'element' => '.single-product .et_product-block .woocommerce-product-rating',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product .et_product-block .woocommerce-product-rating' )
		),
		
		// product_rating_border
		'product_rating_border_et-desktop'              => array(
			'name'      => 'product_rating_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_rating_border_et-desktop',
			'label'     => $strings['label']['border_style'],
            'tooltip'   => $strings['description']['border_style'],
			'section'   => 'product_rating',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-rating',
					'property' => 'border-style'
				),
			),
		),
		
		// product_rating_border_color_custom
		'product_rating_border_color_custom_et-desktop' => array(
			'name'      => 'product_rating_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_rating_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
            'tooltip'   => $strings['description']['border_color'],
			'section'   => 'product_rating',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .et_product-block .woocommerce-product-rating',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
