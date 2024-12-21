<?php
/**
 * The template created for displaying style options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'style' => array(
			'name'       => 'style',
			'title'      => esc_html__( 'Styling/Colors', 'xstore' ),
			'icon'       => 'dashicons-admin-customizer',
			'priority'   => $priorities['styling'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/style' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $sep_style, $light_buttons, $borders_empty, $border_radius, $border_radius_labels, $border_radius_descriptions, $border_styles, $bordered_buttons, $border_labels, $border_descriptions, $dark_buttons, $active_buttons ) {
    $is_elementor = defined('ELEMENTOR_VERSION');
	$args = array();
	
	// Array of fields
	$args = array(
		'dark_styles' => array(
			'name'        => 'dark_styles',
			'type'        => 'toggle',
			'settings'    => 'dark_styles',
			'label'       => esc_html__( 'Dark version', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the switch to change your website to the dark style version.', 'xstore' ),
			'section'     => 'style',
			'default'     => 0,
		),
		
		'activecol' => array(
			'name'        => 'activecol',
			'type'        => 'color',
			'settings'    => 'activecol',
			'label'       => esc_html__( 'Main Color', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the main color for the site (color of links, active buttons and elements like pagination, sale price, portfolio project mask, blog image mask etc).', 'xstore' ),
			'section'     => 'style',
			'default'     => '#a4004f',
			'choices'     => array(
				'alpha' => false
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_active-color',
				),
			)
		),

        'browser_bar_color' => array(
            'name'        => 'browser_bar_color',
            'type'        => 'color',
            'settings'    => 'browser_bar_color',
            'label'       => esc_html__( 'Mobile browser bar color', 'xstore' ),
            'tooltip' => __( 'Set the color of the browser top bar on mobile devices. <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color" target="_blank">Details</a>', 'xstore' ),
            'section'     => 'style',
            'default'     => '',
            'choices'     => array(
                'alpha' => false
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body',
                    'property' => '--et_browser-bar-color',
                ),
            )
        ),
		
		'background_img' => array(
			'name'        => 'background_img',
			'type'        => 'background',
			'settings'    => 'background_img',
			'label'       => esc_html__( 'Site Background', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the background for the site. Note: it will only be visible if the boxed layout is enabled.', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => 'body',
				),
				array(
					'choice'   => 'background-color',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.etheme-sticky-cart',
					'property' => 'background-color'
				),
			),
		),
		
		'container_bg'              => array(
			'name'        => 'container_bg',
			'type'        => 'color',
			'settings'    => 'container_bg',
			'label'       => esc_html__( 'Container Background Color', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the background color of the template container, which covers the entire visible area when the wide layout is enabled.', 'xstore' ),
			'section'     => 'style',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_container-bg-color'
				),
			),
		),
		
		'slider_arrows_colors' => array(
			'name'     => 'slider_arrows_colors',
			'type'     => 'select',
			'settings' => 'slider_arrows_colors',
            'label' => esc_html__('Site\'s slider arrows colors', 'xstore'),
			'tooltip'    => esc_html__( 'Choose whether you want to make all of the site\'s slider arrows without a background, or with a custom background color which you can set in the options below.', 'xstore' ) . ($is_elementor ? '<br/>' .
        esc_html__('This will not be applied to Elementor widgets sliders, as they have their own settings for this purpose.', 'xstore') : ''),
			'section'  => 'style',
			'default'  => 'transparent',
			'choices'  => array(
				'transparent' => esc_html__( 'Transparent', 'xstore' ),
				'custom'      => esc_html__( 'Custom', 'xstore' ),
			),
		),
		
		'slider_arrows_bg_color' => array(
			'name'            => 'slider_arrows_bg_color',
			'type'            => 'color',
			'settings'        => 'slider_arrows_bg_color',
			'label'           => esc_html__( 'Slider arrows background color', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the background color for the site\'s slider arrows.', 'xstore' ) . ($is_elementor ? '<br/>' .
                esc_html__('This will not be applied to Elementor widgets sliders, as they have their own settings for this purpose.', 'xstore') : ''),
			'section'         => 'style',
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_arrows-bg-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'slider_arrows_colors',
					'operator' => '==',
					'value'    => 'custom',
				),
			)
		),
		
		'slider_arrows_color' => array(
			'name'      => 'slider_arrows_color',
			'type'      => 'color',
			'settings'  => 'slider_arrows_color',
			'label'     => esc_html__( 'Slider arrows color', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the color for the site\'s slider arrows.', 'xstore' ) . '<br/>' .
                esc_html__('This will not be applied to Elementor widgets sliders, as they have their own settings for this purpose.', 'xstore'),
			'section'   => 'style',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_arrows-color',
				),
			),
		),
		
		'bold_icons' => array(
			'name'        => 'bold_icons',
			'type'        => 'toggle',
			'settings'    => 'bold_icons',
			'label'       => esc_html__( 'Bold icons', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the option to make all the default icons across your website (e.g. cart, search, wishlist, arrows, etc.) bold..', 'xstore' ),
			'section'     => 'style',
			'default'     => 0,
		),

        'separator_of_inputs_colors' => array(
            'name'     => 'separator_of_inputs_colors',
            'type'     => 'custom',
            'settings' => 'separator_of_inputs_colors',
            'section'  => 'style',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-button"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Input', 'xstore' ) . '</span></div>',
        ),

        // search_zoom
        'form_inputs_border_radius' => array(
            'name'      => 'form_inputs_border_radius',
            'type'      => 'slider',
            'settings'  => 'form_inputs_border_radius',
            'label'     => esc_html__( 'Border radius', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border radius of the input, textarea, select, and fieldset fields throughout the entire site.', 'xstore'),
            'default'   => 0,
            'choices'   => array(
                'min'  => '0',
                'max'  => '50',
                'step' => '1',
            ),
            'section'   => 'style',
            'transport' => 'auto',
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_inputs-border-radius',
                    'units'    => 'px'
                ),
            ),
        ),

        'forms_inputs_bg' => array(
            'name'        => 'forms_inputs_bg',
            'type'        => 'color',
            'settings'    => 'forms_inputs_bg',
            'label'       => esc_html__( 'Background color', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the background color of the input, textarea, select, and fieldset fields throughout the entire site.', 'xstore' ),
            'section'     => 'style',
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_inputs-bg-color'
                ),
            ),
        ),

        'forms_inputs_br' => array(
            'name'        => 'forms_inputs_br',
            'type'        => 'color',
            'settings'    => 'forms_inputs_br',
            'label'       => esc_html__( 'Border color', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border color of the input, textarea, select, and fieldset fields throughout the entire site.', 'xstore' ),
            'section'     => 'style',
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_inputs-border-color',
                ),
            ),
        ),

        'separator_of_alerts_colors' => array(
            'name'     => 'separator_of_alerts_colors',
            'type'     => 'custom',
            'settings' => 'separator_of_alerts_colors',
            'section'  => 'style',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-info-outline"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Alerts', 'xstore' ) . '</span></div>',
        ),

//        'notice_bg_color' => array(
//            'name'        => 'notice_bg_color',
//            'type'        => 'color',
//            'settings'    => 'notice_bg_color',
//            'label'       => esc_html__( 'Notice Background color', 'xstore' ),
//            'tooltip' => esc_html__( 'Choose the background color for the site notices.', 'xstore' ),
//            'section'     => 'style',
//            'default'     => '#2e7d32',
//            'choices'     => array(
//                'alpha' => false
//            ),
//            'transport'   => 'auto',
//            'output'      => array(
//                array(
//                    'context'  => array( 'editor', 'front' ),
//                    'element'  => 'body',
//                    'property' => '--et_notice-bg-color',
//                ),
//            )
//        ),
//
//        'notice_color' => array(
//            'name'        => 'notice_color',
//            'type'        => 'color',
//            'settings'    => 'notice_color',
//            'label'       => esc_html__( 'Notice color', 'xstore' ),
//            'tooltip' => esc_html__( 'Choose the color for the site notices.', 'xstore' ),
//            'section'     => 'style',
//            'default'     => '#fff',
//            'choices'     => array(
//                'alpha' => false
//            ),
//            'transport'   => 'auto',
//            'output'      => array(
//                array(
//                    'context'  => array( 'editor', 'front' ),
//                    'element'  => 'body',
//                    'property' => '--et_notice-color',
//                ),
//            )
//        ),

        'notices_bg' => array(
            'name'      => 'notices_bg',
            'type'      => 'multicolor',
            'settings'  => 'notices_bg',
            'label'     => esc_html__( 'Background color', 'xstore' ),
            'tooltip' => esc_html__('Choose the background colors for the notifications and alerts displayed on your website.', 'xstore'),
            'section'   => 'style',
            'choices'   => array(
                'notice' => esc_html__( 'Success', 'xstore' ),
                'info'   => esc_html__( 'Info', 'xstore' ),
                'error'   => esc_html__( 'Error', 'xstore' ),
            ),
            'default'   => array(
                'notice' => '',
                'info'   => '',
                'error'   => '',
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'choice'   => 'notice',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_notice-bg-color',
                ),
                array(
                    'choice'   => 'info',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_info-bg-color',
                ),
                array(
                    'choice'   => 'error',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_error-bg-color',
                ),
            ),
        ),

        'notices_color' => array(
            'name'      => 'notices_color',
            'type'      => 'multicolor',
            'settings'  => 'notices_color',
            'label'     => esc_html__( 'Text color', 'xstore' ),
            'tooltip' => esc_html__('Choose the text colors for the notifications and alerts displayed on your website.', 'xstore'),
            'section'   => 'style',
            'choices'   => array(
                'notice' => esc_html__( 'Success', 'xstore' ),
                'info'   => esc_html__( 'Info', 'xstore' ),
                'error'   => esc_html__( 'Error', 'xstore' ),
            ),
            'default'   => array(
                'notice' => '',
                'info'   => '',
                'error'   => '',
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'choice'   => 'notice',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_notice-color',
                ),
                array(
                    'choice'   => 'info',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_info-color',
                ),
                array(
                    'choice'   => 'error',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body, [data-mode="dark"]',
                    'property' => '--et_error-color',
                ),
            ),
        ),

		'separator_of_light_btn' => array(
			'name'     => 'separator_of_light_btn',
			'type'     => 'custom',
			'settings' => 'separator_of_light_btn',
			'section'  => 'style',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons" style="background-color: #f2f2f2;border-radius: 50%;"></span><span style="padding-inline-start: 5px;">'.esc_html__( 'Light buttons', 'xstore' ) . '</span></div>',
		),
		
		'light_buttons_fonts' => array(
			'name'      => 'light_buttons_fonts',
			'type'      => 'typography',
            'label' => esc_html__( 'Typeface', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the typeface settings of all light-styled buttons displayed on your website..', 'xstore'),
			'settings'  => 'light_buttons_fonts',
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $light_buttons['regular'],
				),
			),
		),
		
		'light_buttons_bg' => array(
			'name'      => 'light_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'light_buttons_bg',
//			'label'     => esc_html__( 'Light buttons background', 'xstore' ),
            'label' => esc_html__('Background color', 'xstore'),
            'tooltip' => esc_html__('Choose the background colors for the light-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bg-color-hover',
				),
			),
		),
		
		'light_buttons_color' => array(
			'name'      => 'light_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'light_buttons_color',
			'label'     => esc_html__( 'Text color', 'xstore' ),
            'tooltip' => esc_html__('Choose the text colors for the light-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-color-hover',
				),
			),
		),
		
		'light_buttons_border_color' => array(
			'name'        => 'light_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'light_buttons_border_color',
//			'label'       => esc_html__( 'Light buttons border color', 'xstore' ),
            'label'       => esc_html__( 'Border color', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the border color to be applied to the light-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-br-color-hover',
				),
			),
		),
		
		'light_buttons_border_width' => array(
			'name'        => 'light_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'light_buttons_border_width',
//			'label'       => esc_html__( 'Light buttons border width', 'xstore' ),
            'label'       => esc_html__( 'Border width', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to the light-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'light_buttons_border_radius' => array(
			'name'      => 'light_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'light_buttons_border_radius',
//			'label'     => esc_html__( 'Light buttons border radius', 'xstore' ),
            'label'       => esc_html__( 'Border radius', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border radius to be applied to the light-styled buttons displayed on your website.', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
				'labels' => $border_radius_labels,
                'descriptions' => $border_radius_descriptions,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-left-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-left-radius',
					// 'suffix' => '!important'
				),
			),
		),
		
		'light_buttons_border_width_hover' => array(
			'name'        => 'light_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'light_buttons_border_width_hover',
//			'label'       => esc_html__( 'Light buttons border width (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border width (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to all light-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'light_buttons_border_style' => array(
			'name'        => 'light_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'light_buttons_border_style',
//			'label'       => esc_html__( 'Light buttons border style', 'xstore' ),
            'label'       => esc_html__( 'Border style', 'xstore' ),
			'tooltip' => esc_html__( 'Controls the light buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'light_buttons_border_style_hover' => array(
			'name'        => 'light_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'light_buttons_border_style_hover',
//			'label'       => esc_html__( 'Light buttons border style (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border style (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border style to be applied to all light-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_sep_bordered' => array(
			'name'     => 'separator_sep_bordered',
			'type'     => 'custom',
			'settings' => 'separator_sep_bordered',
			'section'  => 'style',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons" style="border: 1px solid var(--customizer-border-color, #e1e1e1);border-radius: 50%;width: calc(var(--customizer-ui-content-zoom, 1) * 16px - 1px); height: calc(var(--customizer-ui-content-zoom, 1) * 16px - 1px);"></span><span style="padding-inline-start: 5px;">'.esc_html__( 'Bordered buttons', 'xstore' ) . '</span></div>',
		
		),
		
		'bordered_buttons_fonts' => array(
			'name'      => 'bordered_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'bordered_buttons_fonts',
            'label' => esc_html__( 'Typeface', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the typeface settings of all bordered-styled buttons displayed on your website..', 'xstore'),
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $bordered_buttons['regular'],
				),
			),
		),
		
		'bordered_buttons_bg' => array(
			'name'      => 'bordered_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'bordered_buttons_bg',
//			'label'     => esc_html__( 'Bordered buttons background', 'xstore' ),
            'label' => esc_html__('Background color', 'xstore'),
            'tooltip' => esc_html__('Choose the background colors for the bordered-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-bg-color-hover',
				),
			),
		),
		
		'bordered_buttons_color' => array(
			'name'      => 'bordered_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'bordered_buttons_color',
//			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
            'label'     => esc_html__( 'Text color', 'xstore' ),
            'tooltip' => esc_html__('Choose the text colors for the light-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-color-hover',
				),
			),
		),
		
		'bordered_buttons_border_color' => array(
			'name'        => 'bordered_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'bordered_buttons_border_color',
//			'label'       => esc_html__( 'Bordered buttons border color', 'xstore' ),
            'label'       => esc_html__( 'Border color', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the border color to be applied to the bordered-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-br-color-hover',
				),
			),
		),
		
		'bordered_buttons_border_width' => array(
			'name'        => 'bordered_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'bordered_buttons_border_width',
//			'label'       => esc_html__( 'Bordered buttons border width', 'xstore' ),
            'label'       => esc_html__( 'Border width', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to the bordered-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'border-top'    => '1px',
				'border-right'  => '1px',
				'border-bottom' => '1px',
				'border-left'   => '1px',
			),
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'bordered_buttons_border_radius' => array(
			'name'      => 'bordered_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'bordered_buttons_border_radius',
//			'label'     => esc_html__( 'Bordered buttons border radius', 'xstore' ),
            'label'       => esc_html__( 'Border radius', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border radius to be applied to the bordered-styled buttons displayed on your website.', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
                'labels' => $border_radius_labels,
                'descriptions' => $border_radius_descriptions,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-left-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-left-radius',
					// 'suffix' => '!important'
				),
			),
		),
		
		'bordered_buttons_border_width_hover' => array(
			'name'        => 'bordered_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'bordered_buttons_border_width_hover',
//			'label'       => esc_html__( 'Bordered buttons border width (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border width (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to all bordered-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'border-top'    => '1px',
				'border-right'  => '1px',
				'border-bottom' => '1px',
				'border-left'   => '1px',
			),
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'bordered_buttons_border_style' => array(
			'name'        => 'bordered_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'bordered_buttons_border_style',
//			'label'       => esc_html__( 'Bordered buttons border style', 'xstore' ),
            'label'       => esc_html__( 'Border style', 'xstore' ),
			'tooltip' => esc_html__( 'Controls the bordered buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'solid',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_dark_sep' => array(
			'name'     => 'separator_dark_sep',
			'type'     => 'custom',
			'settings' => 'separator_dark_sep',
			'section'  => 'style',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons" style="background-color: #000;border-radius: 50%;"></span><span style="padding-inline-start: 5px;">'.esc_html__( 'Dark buttons', 'xstore' ) . '</span></div>',
		),
		
		'dark_buttons_fonts' => array(
			'name'      => 'dark_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'dark_buttons_fonts',
            'label' => esc_html__( 'Typeface', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the typeface settings of all dark-styled buttons displayed on your website..', 'xstore'),
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $dark_buttons['regular'],
				),
			),
		),
		
		'dark_buttons_bg' => array(
			'name'      => 'dark_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'dark_buttons_bg',
//			'label'     => esc_html__( 'Dark buttons background', 'xstore' ),
            'label' => esc_html__('Background color', 'xstore'),
            'tooltip' => esc_html__('Choose the background colors for the dark-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-bg-color-hover',
				),
			),
		),
		
		'dark_buttons_color' => array(
			'name'      => 'dark_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'dark_buttons_color',
//			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
            'label'     => esc_html__( 'Text color', 'xstore' ),
            'tooltip' => esc_html__('Choose the text colors for the light-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-color-hover',
				),
			),
		
		),
		
		'dark_buttons_border_color' => array(
			'name'        => 'dark_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'dark_buttons_border_color',
//			'label'       => esc_html__( 'Dark buttons border color', 'xstore' ),
            'label'       => esc_html__( 'Border color', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the border color to be applied to the dark-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-br-color-hover',
				),
			),
		),
		
		'dark_buttons_border_width' => array(
			'name'        => 'dark_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'dark_buttons_border_width',
//			'label'       => esc_html__( 'Dark buttons border width', 'xstore' ),
            'label'       => esc_html__( 'Border width', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to the dark-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'dark_buttons_border_radius' => array(
			'name'      => 'dark_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'dark_buttons_border_radius',
//			'label'     => esc_html__( 'Dark buttons border radius', 'xstore' ),
            'label'       => esc_html__( 'Border radius', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border radius to be applied to the dark-styled buttons displayed on your website.', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
                'labels' => $border_radius_labels,
                'descriptions' => $border_radius_descriptions,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-left-radius',
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-right-radius',
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-right-radius',
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-left-radius',
				),
			),
		),
		
		'dark_buttons_border_width_hover' => array(
			'name'        => 'dark_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'dark_buttons_border_width_hover',
//			'label'       => esc_html__( 'Dark buttons border width (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border width (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to all dark-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'dark_buttons_border_style' => array(
			'name'        => 'dark_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'dark_buttons_border_style',
//			'label'       => esc_html__( 'Dark buttons border style', 'xstore' ),
            'label'       => esc_html__( 'Border style', 'xstore' ),
			'tooltip' => esc_html__( 'Controls the dark buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'dark_buttons_border_style_hover' => array(
			'name'        => 'dark_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'dark_buttons_border_style_hover',
//			'label'       => esc_html__( 'Dark buttons border style (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border style (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border style to be applied to all dark-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_active_sep' => array(
			'name'     => 'separator_active_sep',
			'type'     => 'custom',
			'settings' => 'separator_active_sep',
			'section'  => 'style',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons" style="background-color: #c62828;border-radius: 50%;"></span><span style="padding-inline-start: 5px;">'.esc_html__( 'Active buttons', 'xstore' ) . '</span></div>',
		),
		
		'active_buttons_fonts' => array(
			'name'      => 'active_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'active_buttons_fonts',
            'label' => esc_html__( 'Typeface', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the typeface settings of all active-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $active_buttons['regular'],
				),
			),
		),
		
		'active_buttons_bg' => array(
			'name'      => 'active_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'active_buttons_bg',
//			'label'     => esc_html__( 'Active buttons background', 'xstore' ),
            'label' => esc_html__('Background color', 'xstore'),
            'tooltip' => esc_html__('Choose the background colors for the active-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-bg-color-hover',
				),
			),
		),
		
		'active_buttons_color' => array(
			'name'      => 'active_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'active_buttons_color',
//			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
            'label'     => esc_html__( 'Text color', 'xstore' ),
            'tooltip' => esc_html__('Choose the text colors for the light-styled buttons displayed on your website.', 'xstore'),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-color-hover',
				),
			),
		),
		
		'active_buttons_border_color' => array(
			'name'        => 'active_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'active_buttons_border_color',
//			'label'       => esc_html__( 'Active buttons border color', 'xstore' ),
            'label'       => esc_html__( 'Border color', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the border color to be applied to the active-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-br-color-hover',
				),
			)
		),
		
		'active_buttons_border_width' => array(
			'name'        => 'active_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'active_buttons_border_width',
//			'label'       => esc_html__( 'Active buttons border width', 'xstore' ),
            'label'       => esc_html__( 'Border width', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to the active-styled buttons displayed on your website.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'postMessage',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'active_buttons_border_radius' => array(
			'name'      => 'active_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'active_buttons_border_radius',
//			'label'     => esc_html__( 'Active buttons border radius', 'xstore' ),
            'label'       => esc_html__( 'Border radius', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border radius to be applied to the active-styled buttons displayed on your website.', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
                'labels' => $border_radius_labels,
                'descriptions' => $border_radius_descriptions,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-left-radius',
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-right-radius',
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-right-radius',
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-left-radius',
				),
			),
		),
		
		'active_buttons_border_width_hover' => array(
			'name'        => 'active_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'active_buttons_border_width_hover',
//			'label'       => esc_html__( 'Active buttons border width (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border width (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border width to be applied to all active-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
                'labels' => $border_labels,
                'descriptions' => $border_descriptions,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'active_buttons_border_style' => array(
			'name'        => 'active_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'active_buttons_border_style',
//			'label'       => esc_html__( 'Active buttons border style', 'xstore' ),
            'label'       => esc_html__( 'Border style', 'xstore' ),
			'tooltip' => esc_html__( 'Controls the Active buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'active_buttons_border_style_hover' => array(
			'name'        => 'active_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'active_buttons_border_style_hover',
//			'label'       => esc_html__( 'Active buttons border style (hover)', 'xstore' ),
            'label'       => esc_html__( 'Border style (hover)', 'xstore' ),
            'tooltip' => esc_html__( 'This sets the border style to be applied to all active-styled buttons throughout the entire site when they are hovered over.', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
	
	);
	
	
	return array_merge( $fields, $args );
	
} );