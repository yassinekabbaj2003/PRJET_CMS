<?php
/**
 * The template created for displaying product waitlist options
 *
 * @since   1.5.0
 * @version 1.0.1
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_waitlist' => array(
			'name'       => 'product_waitlist',
			'title'      => esc_html__( 'Waitlist', 'xstore-core' ),
            'description' => sprintf(esc_html__('Once you have %1s option activated products in your store that are Out of stock will have "Availability notify" button placed instead of add to cart form.', 'xstore-core'),
                '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="text-decoration: underline;">' . esc_html__( 'Waitlist', 'xstore-core' ) . '</span>'),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-bell',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_waitlist', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_waitlist_content_separator'                  => array(
			'name'     => 'product_waitlist_content_separator',
			'type'     => 'custom',
			'settings' => 'product_waitlist_content_separator',
			'section'  => 'product_waitlist',
			'default'  => $separators['content'],
		),
		
		// product_waitlist_icon
		'product_waitlist_icon_et-desktop'                    => array(
			'name'     => 'product_waitlist_icon_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'product_waitlist_icon_et-desktop',
			'label'    => $strings['label']['icon'],
            'tooltip'     => $strings['description']['icon'] . '<br/>' .
                sprintf(esc_html__('Note: The "Inherit" and "Custom SVG" options will only work if the "%1s" option is enabled.', 'xstore-core'),
                    '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="text-decoration: underline;">'.esc_html__('Waitlist', 'xstore-core').'</span>'),
			'section'  => 'product_waitlist',
			'default'  => 'inherit',
			'choices'  => array(
				'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-1.svg',
				'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-2.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
                'inherit'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-inherit.svg',
				'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg',
			),
			'js_vars'  => array(
				array(
					'element'  => '.et_product-block .single-waitlist .et_b-icon',
					'function' => 'toggleClass',
					'class'    => 'none',
					'value'    => 'none'
				),
			),
		),

        // waitlist_icon_custom_svg
        'product_waitlist_icon_custom_svg_et-desktop'                   => array(
            'name'            => 'product_waitlist_icon_custom_svg_et-desktop',
            'type'            => 'image',
            'settings'        => 'product_waitlist_icon_custom_svg_et-desktop',
            'label'           => $strings['label']['custom_image_svg'],
            'tooltip'     => $strings['description']['custom_image_svg'] . '<br/>' .
                sprintf(esc_html__('Note: This option will only work if the "%1s" option is enabled.', 'xstore-core'),
                    '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="text-decoration: underline;">'.esc_html__('Waitlist', 'xstore-core').'</span>'),
            'section'         => 'product_waitlist',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'product_waitlist_icon_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),
		
		// product_waitlist_label_add_to_waitlist
//		'product_waitlist_label_add_to_waitlist'              => array(
//			'name'     => 'product_waitlist_label_add_to_waitlist',
//			'type'     => 'etheme-text',
//			'settings' => 'product_waitlist_label_add_to_waitlist',
//			'label'    => esc_html__( '"Add to waitlist" text', 'xstore-core' ),
//            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the waitlist action, with the default value being "Add to Waitlist".', 'xstore-core' ),
//			'section'  => 'product_waitlist',
//			'default'  => esc_html__( 'Add to waitlist', 'xstore-core' ),
//			// 'transport' => 'postMessage',
//			// 'js_vars' => array(
//			// 	array(
//			// 		'element'  => '.et_product-block .single-waitlist .yith-wcwl-add-button a',
//			// 		'attr' => 'data-hover',
//			// 		'function' => 'html',
//			// 	),
//			// 	array(
//			// 		'element'  => '.et_product-block .single-waitlist .yith-wcwl-add-button a .et_b-icon + span',
//			// 		'function' => 'html',
//			// 	),
//			// ),
//		),
		
		// product_waitlist_label_browse_waitlist
//		'product_waitlist_label_browse_waitlist'              => array(
//			'name'     => 'product_waitlist_label_browse_waitlist',
//			'type'     => 'etheme-text',
//			'settings' => 'product_waitlist_label_browse_waitlist',
//			'label'    => esc_html__( '"Browse waitlist" text', 'xstore-core' ),
//            'tooltip'  => esc_html__( 'Customize the title text for browsing the waitlist or removing a product from the waitlist action, with the default value being "Browse waitlist".', 'xstore-core') . '<br/>' .
//                sprintf(esc_html__('Note: This value may be written differently depending on the value of the "%1s" option you set. Tip: if the "Redirect on remove" option is active, it is better to write something similar to "Browse waitlist" text, but if that option is inactive, it is better to write "Remove from waitlist" text.', 'xstore-core' ),
//                    '<span class="et_edit" data-parent="product_waitlist" data-section="product_waitlist_redirect_on_remove" style="text-decoration: underline;">'.esc_html__('Redirect on remove', 'xstore-core').'</span>'),
//			'section'  => 'product_waitlist',
//			'default'  => esc_html__( 'Browse waitlist', 'xstore-core' ),
//			// 'transport' => 'postMessage',
//			// 'js_vars' => array(
//			// 	array(
//			// 		'element'  => '.et_product-block .single-waitlist .yith-wcwl-waitlistaddedbrowse a .et_b-icon + span, .et_product-block .single-waitlist .yith-wcwl-waitlistexistsbrowse a .et_b-icon + span',
//			// 		'function' => 'html',
//			// 	),
//			// ),
//		),

        'product_waitlist_tooltip' => array(
            'name'     => 'product_waitlist_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_waitlist_tooltip',
            'label'    => __( 'Tooltips', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('Enable this option to add tooltips to the "Waitlist" element in single product content. Tip: tooltips will look better if the "%1s" option below is enabled.', 'xstore-core'),
                '<span class="et_edit" data-parent="product_waitlist" data-section="product_waitlist_only_icon" style="text-decoration: underline;">'.esc_html__('Only icon', 'xstore-core').'</span>'),
            'section'  => 'product_waitlist',
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
            'label'    => __( 'Only icon', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('Enable this option to make the "Waitlist" element icon styled only. Tip: Enable the "%1s" option above which will make the waitlist icon look better and more informative.', 'xstore-core'),
                '<span class="et_edit" data-parent="product_waitlist" data-section="product_waitlist_tooltip" style="text-decoration: underline;">'.esc_html__('Tooltips', 'xstore-core').'</span>'),
            'section'  => 'product_waitlist',
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
//            'label'    => __( 'Redirect on remove', 'xstore-core' ),
//            'tooltip' => sprintf(esc_html__('Enable this option to automatically redirect customers to the waitlist page when they remove a product from their waitlist. Note: The waitlist page can be set in the "%1s" setting.', 'xstore-core'),
//                '<span class="et_edit" data-parent="xstore_waitlist" data-section="xstore_waitlist_page" style="text-decoration: underline;">'.esc_html__('Waitlist page', 'xstore-core').'</span>'),
//            'section'  => 'product_waitlist',
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
            'section'  => 'product_waitlist',
            'default'  => '<span class="et_edit" data-parent="xstore-waitlist" data-section="xstore_waitlist" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Global waitlist settings', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_waitlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
		
		// style separator
		'product_waitlist_style_separator'                    => array(
			'name'     => 'product_waitlist_style_separator',
			'type'     => 'custom',
			'settings' => 'product_waitlist_style_separator',
			'section'  => 'product_waitlist',
			'default'  => $separators['style'],
		),
		
		// product_waitlist_align
		'product_waitlist_align_et-desktop'                   => array(
			'name'        => 'product_waitlist_align_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'product_waitlist_align_et-desktop',
			'label'       => $strings['label']['alignment'],
			'tooltip' => $strings['description']['alignment'] . '<br/>'. $strings['description']['size_bigger_attention'],
			'section'     => 'product_waitlist',
			'default'     => 'start',
			'choices'     => $choices['alignment_with_inherit'],
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-waitlist',
					'property' => 'text-align'
				)
			)
		),
		
		// product_waitlist_proportion
		'product_waitlist_proportion_et-desktop'              => array(
			'name'      => 'product_waitlist_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_waitlist_proportion_et-desktop',
			'label'     => $strings['label']['size_proportion'],
            'tooltip'     => $strings['description']['size_proportion'],
			'section'   => 'product_waitlist',
			'default'   => 1,
			'choices'   => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-product-waitlist-proportion',
				),
			),
		),
		
		// product_waitlist_background
		// 'product_waitlist_background_et-desktop'	=>	 array(
// 'name'		  => 'product_waitlist_background_et-desktop',
		// 	'type'        => 'select',
		// 	'settings'    => 'product_waitlist_background_et-desktop',
		// 	'label'       => esc_html__( 'Background', 'xstore-core' ),
		// 	'section'     => 'product_waitlist',
		// 	'default'     => 'transparent',
		// 	'choices'     => $choices['colors'],
		// 	'transport' => 'auto',
		// 	'output'      => array(
		// 		array(
		// 'context'   => array('editor', 'front'),
		// 			'element' => '.et_product-block .tinvwl-shortcode-add-to-cart > a',
		// 			'property' => 'background-color',
		// 			'value_pattern' => 'var(--$-color)'
		// 		),
		// 	),
		// ),
		
		// product_waitlist_background_custom
		'product_waitlist_background_custom_et-desktop'       => array(
			'name'        => 'product_waitlist_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_waitlist_background_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_waitlist',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#000000',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .xstore-waitlist-single',
					'property' => 'background-color',
				),
			),
		),
		'product_waitlist_color_et-desktop'                   => array(
			'name'        => 'product_waitlist_color_et-desktop',
			'settings'    => 'product_waitlist_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'tooltip' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_waitlist',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_waitlist)(product_waitlist_background_custom_et-desktop)',
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
					'element'  => '.et_product-block .xstore-waitlist-single, .et_product-block .xstore-waitlist-single .et-icon',
					'property' => 'color'
				)
			),
		),
		
		// product_waitlist_background_hover_custom
		'product_waitlist_background_hover_custom_et-desktop' => array(
			'name'        => 'product_waitlist_background_hover_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_waitlist_background_hover_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color_hover'],
			'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_waitlist',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#444444',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .xstore-waitlist-single:hover',
					'property' => 'background-color',
				),
			),
		),
		'product_waitlist_hover_color_et-desktop'             => array(
			'name'        => 'product_waitlist_hover_color_et-desktop',
			'settings'    => 'product_waitlist_hover_color_et-desktop',
			'label'       => $strings['label']['wcag_color_hover'],
			'tooltip' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_waitlist',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_waitlist)(product_waitlist_background_hover_custom_et-desktop)',
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
					'element'  => '.et_product-block .xstore-waitlist-single:hover, .et_product-block .xstore-waitlist-single:hover .et-icon',
					'property' => 'color'
				)
			),
		),
		
		// product_waitlist_border_radius
		'product_waitlist_border_radius_et-desktop'           => array(
			'name'      => 'product_waitlist_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_waitlist_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
            'tooltip' => $strings['description']['border_radius'],
			'section'   => 'product_waitlist',
			'default'   => 0,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => ' .et_product-block .xstore-waitlist-single',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		'product_waitlist_box_model_et-desktop'               => array(
			'name'        => 'product_waitlist_box_model_et-desktop',
			'settings'    => 'product_waitlist_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'tooltip' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_waitlist',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '10px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '11px',
				'padding-right'       => '30px',
				'padding-bottom'      => '11px',
				'padding-left'        => '30px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => ' .et_product-block .xstore-waitlist-single'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( ' .et_product-block .xstore-waitlist-single' )
		),
		
		// product_waitlist_border
		'product_waitlist_border_et-desktop'                  => array(
			'name'      => 'product_waitlist_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_waitlist_border_et-desktop',
			'label'     => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
			'section'   => 'product_waitlist',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => ' .et_product-block .xstore-waitlist-single',
					'property' => 'border-style',
				),
			),
		),
		
		// product_waitlist_border_color_custom
		'product_waitlist_border_color_custom_et-desktop'     => array(
			'name'      => 'product_waitlist_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_waitlist_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
			'section'   => 'product_waitlist',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => ' .et_product-block .xstore-waitlist-single',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
