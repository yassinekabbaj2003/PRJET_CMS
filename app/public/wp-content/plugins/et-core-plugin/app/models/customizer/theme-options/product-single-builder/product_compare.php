<?php
/**
 * The template created for displaying product compare options
 *
 * @since   2.2.4
 * @version 1.0.1
 * last changes in 4.3.9 built-in compare options added
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_compare' => array(
			'name'       => 'product_compare',
			'title'      => esc_html__( 'Compare', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-update-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_compare', function ( $fields ) use ( $separators, $strings, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(

        // content separator
        'product_compare_content_separator'                  => array(
            'name'     => 'product_compare_content_separator',
            'type'     => 'custom',
            'settings' => 'product_compare_content_separator',
            'section'  => 'product_compare',
            'default'  => $separators['content'],
        ),

        // product_compare_icon
        'product_compare_icon_et-desktop'                    => array(
            'name'     => 'product_compare_icon_et-desktop',
            'type'     => 'radio-image',
            'settings' => 'product_compare_icon_et-desktop',
            'label'    => $strings['label']['icon'],
            'tooltip'     => $strings['description']['icon'] . '<br/>' .
                sprintf(esc_html__('Note: The "Inherit" and "Custom SVG" options will only work if the "%1s" option is enabled.', 'xstore-core'),
                    '<span class="et_edit" data-parent="xstore-compare" data-section="xstore_compare" style="text-decoration: underline;">'.esc_html__('Compare', 'xstore-core').'</span>'),
            'section'  => 'product_compare',
            'default'  => 'type1',
            'choices'  => array(
                'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/compare/Compare-1.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
                'inherit'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-inherit.svg',
                'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
            'js_vars'  => array(
                array(
                    'element'  => '.et_product-block .single-compare .et_b-icon',
                    'function' => 'toggleClass',
                    'class'    => 'none',
                    'value'    => 'none'
                ),
            ),
        ),

        // compare_icon_custom_svg
        'product_compare_icon_custom_svg_et-desktop'                   => array(
            'name'            => 'product_compare_icon_custom_svg_et-desktop',
            'type'            => 'image',
            'settings'        => 'product_compare_icon_custom_svg_et-desktop',
            'label'           => $strings['label']['custom_image_svg'],
            sprintf(esc_html__('Note: This option will only work if the "%1s" option is enabled.', 'xstore-core'),
                '<span class="et_edit" data-parent="xstore-compare" data-section="xstore_compare" style="text-decoration: underline;">'.esc_html__('Compare', 'xstore-core').'</span>'),
            'section'         => 'product_compare',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
                array(
                    'setting'  => 'product_compare_icon_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // product_compare_label_add_to_compare
        'product_compare_label_add_to_compare'              => array(
            'name'     => 'product_compare_label_add_to_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_add_to_compare',
            'label'    => esc_html__( '"Add to compare" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the compare action, with the default value being "Add to compare".', 'xstore-core' ),
            'section'  => 'product_compare',
            'default'  => esc_html__( 'Add to compare', 'xstore-core' ),
            // 'transport' => 'postMessage',
            // 'js_vars' => array(
            // 	array(
            // 		'element'  => '.et_product-block .single-compare .yith-wcwl-add-button a',
            // 		'attr' => 'data-hover',
            // 		'function' => 'html',
            // 	),
            // 	array(
            // 		'element'  => '.et_product-block .single-compare .yith-wcwl-add-button a .et_b-icon + span',
            // 		'function' => 'html',
            // 	),
            // ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        // product_compare_label_browse_compare
        'product_compare_label_browse_compare'              => array(
            'name'     => 'product_compare_label_browse_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_browse_compare',
            'label'    => esc_html__( '"Browse compare" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text for browsing the compare or removing a product from the compare action, with the default value being "Browse compare".', 'xstore-core') . '<br/>' .
                sprintf(esc_html__('Note: This value may be written differently depending on the value of the "%1s" option you set. Tip: if the "Redirect on remove" option is active, it is better to write something similar to "Browse compare" text, but if that option is inactive, it is better to write "Remove from compare" text.', 'xstore-core' ),
                    '<span class="et_edit" data-parent="product_compare" data-section="product_compare_redirect_on_remove" style="text-decoration: underline;">'.esc_html__('Redirect on remove', 'xstore-core').'</span>'),
            'section'  => 'product_compare',
            'default'  => esc_html__( 'Delete from compare', 'xstore-core' ),
            // 'transport' => 'postMessage',
            // 'js_vars' => array(
            // 	array(
            // 		'element'  => '.et_product-block .single-compare .yith-wcwl-compareaddedbrowse a .et_b-icon + span, .et_product-block .single-compare .yith-wcwl-compareexistsbrowse a .et_b-icon + span',
            // 		'function' => 'html',
            // 	),
            // ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'product_compare_tooltip' => array(
            'name'     => 'product_compare_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_compare_tooltip',
            'label'    => __( 'Tooltip', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('Enable this option to add tooltips to the "Compare" element in single product content. Tip: tooltips will look better if the "%1s" option below is enabled.', 'xstore-core'),
                '<span class="et_edit" data-parent="product_compare" data-section="product_compare_only_icon" style="text-decoration: underline;">'.esc_html__('Only icon', 'xstore-core').'</span>'),
            'section'  => 'product_compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'product_compare_only_icon' => array(
            'name'     => 'product_compare_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_compare_only_icon',
            'label'    => __( 'Only icon', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('Enable this option to make the "Compare" element icon styled only. Tip: Enable the "%1s" option above which will make the compare icon look better and more informative.', 'xstore-core'),
                '<span class="et_edit" data-parent="product_compare" data-section="product_compare_tooltip" style="text-decoration: underline;">'.esc_html__('Tooltips', 'xstore-core').'</span>'),
            'section'  => 'product_compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'product_compare_redirect_on_remove' => array(
            'name'     => 'product_compare_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_compare_redirect_on_remove',
            'label'    => __( 'Redirect on remove', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('Enable this option to automatically redirect customers to the compare page when they remove a product from their compare list. Note: The compare page can be set in the "%1s" setting.', 'xstore-core'),
                '<span class="et_edit" data-parent="xstore_compare" data-section="xstore_compare_page" style="text-decoration: underline;">'.esc_html__('Compare page', 'xstore-core').'</span>'),
            'section'  => 'product_compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        // go to product single product compare
        'go_to_section_xstore_compare'                 => array(
            'name'     => 'go_to_section_xstore_compare',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_compare',
            'section'  => 'product_compare',
            'default'  => '<span class="et_edit" data-parent="xstore-compare" data-section="xstore_compare" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Global compare settings', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        // style separator
        'product_compare_style_separator'              => array(
            'name'     => 'product_compare_style_separator',
            'type'     => 'custom',
            'settings' => 'product_compare_style_separator',
            'section'  => 'product_compare',
            'default'  => $separators['style'],
        ),

		// product_compare_align
		'product_compare_align_et-desktop'             => array(
			'name'        => 'product_compare_align_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'product_compare_align_et-desktop',
			'label'       => $strings['label']['alignment'],
			'tooltip' => $strings['description']['alignment'] . '<br/>'. $strings['description']['size_bigger_attention'],
			'section'     => 'product_compare',
			'default'     => 'start',
			'choices'     => $choices['alignment_with_inherit'],
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-compare',
					'property' => 'text-align'
				)
			)
		),
		
		// product_compare_proportion
		'product_compare_proportion_et-desktop'        => array(
			'name'      => 'product_compare_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_compare_proportion_et-desktop',
			'label'     => $strings['label']['size_proportion'],
            'tooltip'     => $strings['description']['size_proportion'],
			'section'   => 'product_compare',
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
					'property' => '--single-product-compare-proportion',
				),
			),
		),
		
		// product_compare_background
		// 'product_compare_background_et-desktop'	=>	 array(
		// 'name'		  => 'product_compare_background_et-desktop',
		// 	'type'        => 'select',
		// 	'settings'    => 'product_compare_background_et-desktop',
		// 	'label'       => esc_html__( 'Background', 'xstore-core' ),
		// 	'section'     => 'product_compare',
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
		
		// product_compare_background_custom
		'product_compare_background_custom_et-desktop' => array(
			'name'        => 'product_compare_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_compare_background_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_compare',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#ffffff',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-compare > a',
					'property' => 'background-color',
				),
			),
		),
		
		'product_compare_color_et-desktop'                   => array(
			'name'        => 'product_compare_color_et-desktop',
			'settings'    => 'product_compare_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'tooltip' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_compare',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(product_compare)(product_compare_background_custom_et-desktop)',
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
					'element'  => '.et_product-block .single-compare > a, .et_product-block .xstore-compare-single .et-icon',
					'property' => 'color'
				)
			),
		),
		
		// product_compare_background_hover_custom
		'product_compare_background_hover_custom_et-desktop' => array(
			'name'        => 'product_compare_background_hover_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_compare_background_hover_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color_hover'],
			'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_compare',
			'choices'     => array(
				'alpha' => true
			),
			'default'     => '#ffffff',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-compare a:hover',
					'property' => 'background-color',
				),
			),
		),
		
		'product_compare_hover_color_et-desktop'   => array(
			'name'        => 'product_compare_hover_color_et-desktop',
			'settings'    => 'product_compare_hover_color_et-desktop',
			'label'       => $strings['label']['wcag_color_hover'],
			'tooltip' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_compare',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(product_compare)(product_compare_background_hover_custom_et-desktop)',
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
					'element'  => '.et_product-block .single-compare a:hover',
					'property' => 'color'
				)
			),
		),
		
		// product_compare_border_radius
		'product_compare_border_radius_et-desktop' => array(
			'name'      => 'product_compare_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_compare_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
            'tooltip' => $strings['description']['border_radius'],
			'section'   => 'product_compare',
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
					'element'  => '.et_product-block .single-compare > a',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		
		'product_compare_box_model_et-desktop'           => array(
			'name'        => 'product_compare_box_model_et-desktop',
			'settings'    => 'product_compare_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'tooltip' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_compare',
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
					'element' => '.et_product-block .single-compare > a, .et_product-block .single-compare > .xstore-compare-single'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_product-block .single-compare > a, .et_product-block .single-compare > .xstore-compare-single' )
		),
		
		// product_compare_border
		'product_compare_border_et-desktop'              => array(
			'name'      => 'product_compare_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_compare_border_et-desktop',
			'label'     => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
			'section'   => 'product_compare',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-compare > a',
					'property' => 'border-style',
				),
			),
		),
		
		// product_compare_border_color_custom
		'product_compare_border_color_custom_et-desktop' => array(
			'name'      => 'product_compare_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_compare_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
			'section'   => 'product_compare',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_product-block .single-compare > a',
					'property' => 'border-color',
				),
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
