<?php
/**
 * The template created for displaying shop color swatches options
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: show_all_variations
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-color-swatches' => array(
			'name'       => 'shop-color-swatches',
			'title'      => esc_html__( 'Variation Swatches', 'xstore' ),
            'description' => esc_html__('Shoppers appreciate seeing visual representations of product attributes when using your store, which can help create a professional and tidy look. Our built-in color, image, and label swatches are the perfect solution if you are looking to gain an advantage over your competitors.', 'xstore'),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-image-filter',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-color-swatches' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ($sep_style) {
	$args = array();
	
	$attributes = wc_get_attribute_taxonomies();
	
	$attributes_to_show = array(
		'et_none' => esc_html__( 'None', 'xstore' ),
	);
	
	if ( is_array( $attributes ) ) {
		foreach ( $attributes as $attribute ) {
			$attributes_to_show[ $attribute->attribute_name ] = $attribute->attribute_label;
		}
	}
	
	// Array of fields
	$args = array(
		'enable_swatch' => array(
			'name'        => 'enable_swatch',
			'type'        => 'toggle',
			'settings'    => 'enable_swatch',
			'label'       => esc_html__( 'Enable variation swatches', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'Enable this option to replace the old-fashioned dropdown fields on your variable products with our built-in color, image, and label %s.', 'xstore' ),
                '<a href="'.etheme_documentation_url('94-variation-swatches', false).'" target="_blank" rel="nofollow">'.esc_html__('variation swatches', 'xstore').'</a>'),
			'section'     => 'shop-color-swatches',
			'default'     => 1,
		),
		
		'show_plus_variations' => array(
			'name'            => 'show_plus_variations',
			'type'            => 'toggle',
			'settings'        => 'show_plus_variations',
			'label'           => esc_html__( 'Show more link', 'xstore' ),
            'tooltip' => esc_html__( 'If a product has more variation combinations than the number set in the option below, only a limited number of variations will be shown initially, and the total number of additional variations will be indicated by "+X". By clicking the "+X" link, the hidden variations will be revealed.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'show_plus_variations_after' => array(
			'name'            => 'show_plus_variations_after',
			'type'            => 'slider',
			'settings'        => 'show_plus_variations_after',
			'label'           => esc_html__( 'Initially shown items', 'xstore' ),
            'tooltip' => esc_html__( 'Set the maximum number of variations to be displayed initially, with the total number of additional variations indicated by "+X". Clicking the "+X" link will reveal the hidden product variations.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'choices'         => array(
				'min'  => 1,
				'max'  => 10,
				'step' => 1,
			),
			'default'         => 3,
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'show_plus_variations',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_position_shop' => array(
			'name'            => 'swatch_position_shop',
			'type'            => 'select',
			'settings'        => 'swatch_position_shop',
			'label'           => esc_html__( 'Position', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the position of the variation swatches to be displayed on the product archive pages.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'before',
			'choices'         => array(
				'before'  => esc_html__( 'Before product details', 'xstore' ),
				'after'   => esc_html__( 'After product details', 'xstore' ),
				'disable' => esc_html__( 'Disable', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'primary_attribute' => array(
			'name'            => 'primary_attribute',
			'type'            => 'select',
			'settings'        => 'primary_attribute',
			'label'           => esc_html__( 'Primary attribute', 'xstore' ),
			'tooltip'     => esc_html__( 'When you click on a primary attribute, the product image will change even if you have not selected the full combination of current variations. To keep the default variation swatches, please select the "None" variant in the list.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'et_none',
			'choices'         => $attributes_to_show,
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_item_title' => array(
			'name'            => 'swatch_item_title',
			'type'            => 'select',
			'settings'        => 'swatch_item_title',
			'label'           => esc_html__( 'Show name postfix', 'xstore' ),
			'tooltip'     => esc_html__( 'Add the selected swatch item name next to its label on the single product page.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'both',
			'choices'         => array(
				'desktop' => esc_html__( 'On desktop', 'xstore' ),
				'mobile'  => esc_html__( 'On mobile', 'xstore' ),
				'both'    => esc_html__( 'On desktop and mobile', 'xstore' ),
				'none'    => esc_html__( 'Don\'t show', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_layout_shop' => array(
			'name'            => 'swatch_layout_shop',
			'type'            => 'select',
			'settings'        => 'swatch_layout_shop',
			'label'           => esc_html__( 'Type', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the type of the swatches to be displayed on the product archive pages.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'default',
			'choices'         => array(
				'default' => esc_html__( 'Default', 'xstore' ),
				'popup'   => esc_html__( 'Popup', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_design' => array(
			'name'            => 'swatch_design',
			'type'            => 'select',
			'settings'        => 'swatch_design',
			'label'           => esc_html__( 'Design', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the design of the swatches to be displayed on the product archive pages.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'default',
			'choices'         => array(
				'default'   => esc_html__( 'Default', 'xstore' ),
				'underline' => esc_html__( 'Underline', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_disabled_design' => array(
			'name'            => 'swatch_disabled_design',
			'type'            => 'select',
			'settings'        => 'swatch_disabled_design',
			'label'           => esc_html__( '"Out of Stock" design', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the design of the swatches which are out of stock to be displayed on the product archive pages.', 'xstore' ),
			'section'         => 'shop-color-swatches',
			'default'         => 'line-thought',
			'choices'         => array(
				'default'      => esc_html__( 'Default', 'xstore' ),
				'line-thought' => esc_html__( 'Line-thought', 'xstore' ),
				'cross-line'   => esc_html__( 'Cross line', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

        'swatch_multicolor_design' => array(
            'name'            => 'swatch_multicolor_design',
            'type'            => 'select',
            'settings'        => 'swatch_multicolor_design',
            'label'           => esc_html__( 'Multicolor design', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the design of the multicolor swatches to be displayed on the product archive pages.', 'xstore' ),
            'section'         => 'shop-color-swatches',
            'default'         => 'right',
            'choices'         => array(
                'right' => esc_html__( 'Vertical', 'xstore' ),
                'bottom'   => esc_html__( 'Horizontal', 'xstore' ),
                'diagonal_1'   => esc_html__( 'Diagonal 1', 'xstore' ),
//                'diagonal_2'   => esc_html__( 'Diagonal 2', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
		
		'swatch_shape' => array(
			'name'            => 'swatch_shape',
			'type'            => 'select',
			'settings'        => 'swatch_shape',
			'label'           => esc_html__( 'Shape', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the shape of the swatches to be displayed on the product archive pages.', 'xstore' ) . '<br/>' .
                esc_html__('Tip: setting this option is useful if you need to make all swatches one style, regardless of the global settings you have made for your attributes.', 'xstore'),
			'section'         => 'shop-color-swatches',
			'default'         => 'default',
			'choices'         => array(
				'default' => esc_html__( 'Default', 'xstore' ),
				'square'  => esc_html__( 'Square', 'xstore' ),
				'circle'  => esc_html__( 'Circle', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'swatch_border' => array(
			'name'            => 'swatch_border',
			'type'            => 'multicolor',
			'settings'        => 'swatch_border',
			'label'           => esc_html__( 'Border color', 'xstore' ),
            'tooltip' => esc_html__('Choose the colors of the borders for the swatches displayed on the product archive pages and single product pages.', 'xstore'),
			'section'         => 'shop-color-swatches',
			'choices'         => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover/Active', 'xstore' ),
			),
			'default'         => array(
				'regular' => '',
				'hover'   => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_swatch',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'ul.st-swatch-preview li, .st-swatch-preview li.selected,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-color,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-color.st-swatch-white,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-image,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-image.st-swatch-white',
					'property' => 'border-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'ul.st-swatch-preview li:hover, .products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-color:hover,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-image:hover,
					.st-swatch-preview li.selected, .products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-color.selected,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-image.selected,
					.products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-color:hover, .products-grid .content-product .st-swatch-in-loop > .et_st-default-holder .type-image:hover',
					'property' => 'border-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'front', 'front' ),
					'element'  => 'body',
					'property' => '--et_swatch-active-color',
				),
			)
		),

        // advanced separator
        'swatch_advanced' => array(
            'name'     => 'swatch_advanced',
            'type'     => 'custom',
            'settings' => 'swatch_advanced',
            'section'  => 'shop-color-swatches',
            'default'  => '<div style="' . $sep_style . '">' . esc_html__( 'Advanced settings', 'xstore' ) . '</div>',
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'images_from_variations' => array(
            'name'            => 'images_from_variations',
            'type'            => 'toggle',
            'settings'        => 'images_from_variations',
            'label'           => esc_html__( 'Use images from product variations', 'xstore' ),
            'tooltip'         => esc_html__( 'Image swatches buttons will be filled with images chosen for product variations and not with images uploaded to attribute terms.', 'xstore' ) . '<br/>' .
                esc_html__('Note: It will not change images in filter widgets!', 'xstore'),
            'section'         => 'shop-color-swatches',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'swatch_select_type_shop' => array(
            'name'            => 'swatch_select_type_shop',
            'type'            => 'toggle',
            'settings'        => 'swatch_select_type_shop',
            'label'           => esc_html__( 'Show select type', 'xstore' ),
            'tooltip'         => esc_html__( 'Enable this option to display the swatches with the "select" type on the product archive pages.', 'xstore' ),
            'section'         => 'shop-color-swatches',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'swatch_select_type_price_shop' => array(
            'name'            => 'swatch_select_type_price_shop',
            'type'            => 'toggle',
            'settings'        => 'swatch_select_type_price_shop',
            'label'           => esc_html__( 'Show price in select', 'xstore' ),
            'tooltip'         => esc_html__( 'Enable this option to display the minimum price of the variation next to the attribute name in the selection.', 'xstore' ),
            'section'         => 'shop-color-swatches',
            'default'         => 1,
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'swatch_select_type_shop',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'swatch_js_logic_type' => array(
            'name'            => 'swatch_js_logic_type',
            'type'            => 'toggle',
            'settings'        => 'swatch_js_logic_type',
            'label'           => esc_html__( 'Use alternative stock logic', 'xstore' ),
            'tooltip'         => esc_html__( 'Enable this option to use alternative single product logic for out of stock product variations.', 'xstore' ),
            'section'         => 'shop-color-swatches',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'enable_swatch',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	);
	
	return array_merge( $fields, $args );
	
} );