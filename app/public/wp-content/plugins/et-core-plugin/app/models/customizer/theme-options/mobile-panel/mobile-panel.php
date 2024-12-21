<?php
/**
 * The template created for displaying mobile panel options
 *
 * @version 0.0.2
 * @since   2.3.1
 * last changes in 3.2.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'mobile_panel' => array(
			'name'       => 'mobile_panel',
			'title'      => esc_html__( 'Mobile panel', 'xstore-core' ),
            'description' => esc_html__(' Our mobile panel includes many useful features to improve navigation and usability, ensuring a seamless mobile shopping experience for your customers. Try it out today and see how it can help increase engagement and sales on your online store.', 'xstore-core'),
			'icon'       => 'dashicons-download',
			'priority'   => 5,
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/mobile_panel', function ( $fields ) use ( $separators, $strings, $menu_settings, $choices, $icons, $mobile_panel_elements ) {
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	$menus = et_b_get_terms( 'nav_menu' );
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'mobile_panel_et-mobile'         => array(
			'name'      => 'mobile_panel_et-mobile',
			'type'      => 'toggle',
			'settings'  => 'mobile_panel_et-mobile',
			'label'     => esc_html__( 'Enable mobile panel', 'xstore-core' ),
            'tooltip' => esc_html__('Enable the mobile panel option and add essential icons such as cart, shop, home, and more to the mobile panel for seamless mobile browsing.', 'xstore-core'),
			'section'   => 'mobile_panel',
			'default'   => '0',
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et-mobile-panel-wrapper',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
			'priority'  => 8,
		),
		
		// content separator
		'mobile_panel_content_separator' => array(
			'name'     => 'mobile_panel_content_separator',
			'type'     => 'custom',
			'settings' => 'mobile_panel_content_separator',
			'section'  => 'mobile_panel',
			'default'  => $separators['content'],
			'priority' => 9,
		),
		
		'mobile_panel_elements_labels_et-mobile' => array(
			'name'            => 'mobile_panel_elements_labels_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'mobile_panel_elements_labels_et-mobile',
			'label'           => esc_html__( 'Show labels', 'xstore-core' ),
            'tooltip' => esc_html__( 'Enable this option to display the counter labels above cart/wishlist/compare elements of the mobile panel.', 'xstore-core' ),
			'section'         => 'mobile_panel',
			'default'         => 1,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_elements_labels_et-mobile' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
		
		'mobile_panel_elements_texts_et-mobile' => array(
			'name'            => 'mobile_panel_elements_texts_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'mobile_panel_elements_texts_et-mobile',
			'label'           => esc_html__( 'Show texts', 'xstore-core' ),
            'tooltip' => esc_html__( 'Enable this option to display the text below each element of the mobile panel.', 'xstore-core' ),
			'section'         => 'mobile_panel',
			'default'         => 1,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_elements_texts_et-mobile' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
		
		'mobile_panel_more_toggle_content' => array(
			'name'     => 'mobile_panel_more_toggle_content',
			'type'     => 'select',
			'settings' => 'mobile_panel_more_toggle_content',
			'label'    => esc_html__( 'More toggle 01 content type', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Choose the content to be displayed in the "More toggle 01" element of the mobile panel.', 'xstore-core' ),
			'section'  => 'mobile_panel',
			'choices'  => array(
				'menu'        => esc_html__( 'Menu', 'xstore-core' ),
				'staticblock' => esc_html__( 'Static block', 'xstore-core' ),
			),
			'default'  => 'menu'
		),
		
		// mobile_panel_more_toggle_section
		'mobile_panel_more_toggle_section' => array(
			'name'            => 'mobile_panel_more_toggle_section',
			'type'            => 'select',
			'settings'        => 'mobile_panel_more_toggle_section',
//			'label'           => sprintf( esc_html__( 'Choose %1s ', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
			'section'         => 'mobile_panel',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_more_toggle_content',
					'operator' => '==',
					'value'    => 'staticblock',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_more_toggle_section' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
		
		'mobile_panel_more_toggle_menu_term'  => array(
			'name'            => 'mobile_panel_more_toggle_menu_term',
			'type'            => 'select',
			'settings'        => 'mobile_panel_more_toggle_menu_term',
            'label'    => esc_html__( 'More toggle 01 menu', 'xstore-core' ),
            'tooltip'         => $menu_settings['strings']['description']['select_menu'],
			'section'         => 'mobile_panel',
			'choices'         => $menus,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_more_toggle_menu_term' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_more_toggle_content',
					'operator' => '==',
					'value'    => 'menu',
				),
			),
		),
		
		//
		'mobile_panel_more_toggle_02_content' => array(
			'name'     => 'mobile_panel_more_toggle_02_content',
			'type'     => 'select',
			'settings' => 'mobile_panel_more_toggle_02_content',
            'label'    => esc_html__( 'More toggle 02 content type', 'xstore-core' ),
            'tooltip'         => esc_html__( 'Choose the content to be displayed in the "More toggle 02" element of the mobile panel.', 'xstore-core' ),
			'section'  => 'mobile_panel',
			'choices'  => array(
				'menu'        => esc_html__( 'Menu', 'xstore-core' ),
				'staticblock' => esc_html__( 'Static block', 'xstore-core' ),
			),
			'default'  => 'menu'
		),
		
		// mobile_panel_more_toggle_02_section
		'mobile_panel_more_toggle_02_section' => array(
			'name'            => 'mobile_panel_more_toggle_02_section',
			'type'            => 'select',
			'settings'        => 'mobile_panel_more_toggle_02_section',
//			'label'           => sprintf( esc_html__( 'Choose %1s ', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
			'section'         => 'mobile_panel',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_more_toggle_02_content',
					'operator' => '==',
					'value'    => 'staticblock',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_more_toggle_02_section' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
		
		'mobile_panel_more_toggle_02_menu_term' => array(
			'name'            => 'mobile_panel_more_toggle_02_menu_term',
			'type'            => 'select',
			'settings'        => 'mobile_panel_more_toggle_02_menu_term',
            'label'    => esc_html__( 'More toggle 02 menu', 'xstore-core' ),
            'tooltip'         => $menu_settings['strings']['description']['select_menu'],
			'section'         => 'mobile_panel',
			'choices'         => $menus,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_more_toggle_02_menu_term' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_more_toggle_02_content',
					'operator' => '==',
					'value'    => 'menu',
				),
			),
		),
		
		// style separator
		'mobile_panel_style_separator'          => array(
			'name'     => 'mobile_panel_style_separator',
			'type'     => 'custom',
			'settings' => 'mobile_panel_style_separator',
			'section'  => 'mobile_panel',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		'mobile_panel_height_et-mobile'        => array(
			'name'      => 'mobile_panel_height_et-mobile',
			'type'      => 'slider',
			'settings'  => 'mobile_panel_height_et-mobile',
			'label'     => esc_html__( 'Height (px)', 'xstore-core' ),
            'tooltip' => esc_html__( 'This controls the height of the mobile panel.', 'xstore-core' ),
			'section'   => 'mobile_panel',
			'default'   => 60,
			'choices'   => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'property' => 'height',
					'units'    => 'px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel-wrapper',
					'property'      => '--mobile-panel-height',
					'value_pattern' => '$px'
				),
				array(
					'media_query'   => '@media only screen and (max-width: 992px)',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel-wrapper:not(.mob-hide):not(.outside) ~ .back-top,
					.et-mobile-panel-wrapper:not(.mob-hide):not(.outside) ~ .et-request-quote,
					.et-mobile-panel-wrapper:not(.mob-hide):not(.outside) ~ #sales-booster-popup,
					body:has(.etheme-sticky-cart:not(.outside)) #sales-booster-popup',
					'property'      => 'bottom',
					'value_pattern' => 'calc($px + 15px)',
				),
				array(
					'media_query'   => '@media only screen and (max-width: 992px)',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel-wrapper:not(.mob-hide):not(.outside) ~ .back-top.backIn ~ .et-request-quote',
					'property'      => 'bottom',
					'value_pattern' => 'calc($px + 70px)',
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel-wrapper',
					'property'      => '--max-elements-mini-content-height',
					'value_pattern' => 'calc(100% - $px + 1px)'
				),
			
			),
		),
		
		// mobile_panel_elements_zoom 
		'mobile_panel_elements_zoom_et-mobile' => array(
			'name'      => 'mobile_panel_elements_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'mobile_panel_elements_zoom_et-mobile',
            'label'     => $strings['label']['content_zoom'],
            'tooltip'   => $strings['description']['content_zoom'],
			'section'   => 'mobile_panel',
			'default'   => 100,
			'choices'   => array(
				'min'  => '30',
				'max'  => '250',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel-wrapper',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				)
			)
		),
		
		// mobile_panel_background
		'mobile_panel_background_et-mobile'    => array(
			'name'        => 'mobile_panel_background_et-mobile',
			'type'        => 'background',
			'settings'    => 'mobile_panel_background_et-mobile',
            'label'       => $strings['label']['wcag_bg_color'],
            'tooltip' => $strings['description']['wcag_bg_color'],
			'section'     => 'mobile_panel',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et-mobile-panel-wrapper, .et_b_mobile-panel-more_toggle .et-mini-content, .et_b_mobile-panel-more_toggle_02 .et-mini-content, .et-mobile-panel .et_column',
				),
			),
		),
		
		'mobile_panel_color_et-mobile' => array(
			'name'        => 'mobile_panel_color_et-mobile',
			'type'        => 'color',
			'settings'    => 'mobile_panel_color_et-mobile',
            'label'       => $strings['label']['wcag_color'],
            'tooltip' => $strings['description']['wcag_color'],
			'section'     => 'mobile_panel',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(mobile_panel)(mobile_panel_background_et-mobile)[background-color]',
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
					'element'  => '.et-mobile-panel-wrapper, .et_b_mobile-panel-more_toggle .et-mini-content, .et_b_mobile-panel-more_toggle_02 .et-mini-content',
					'property' => 'color'
				)
			)
		),
		
		'mobile_panel_active_colors_et-mobile'            => array(
			'name'            => 'mobile_panel_active_colors_et-mobile',
			'type'            => 'select',
			'settings'        => 'mobile_panel_active_colors_et-mobile',
			'label'           => esc_html__( 'Active colors', 'xstore-core' ),
            'tooltip'  => $strings['description']['colors'],
			'section'         => 'mobile_panel',
			'default'         => 'current',
			'choices'         => $choices['colors'],
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et-mobile-panel .et_column.active',
					'property'      => 'color',
					'value_pattern' => 'var(--$-color)'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_active_colors_et-mobile' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
		
		// mobile_panel_background
		'mobile_panel_active_background_custom_et-mobile' => array(
			'name'            => 'mobile_panel_active_background_custom_et-mobile',
			'type'            => 'background',
			'settings'        => 'mobile_panel_active_background_custom_et-mobile',
			'label'           => $strings['label']['wcag_bg_color_active'],
			'tooltip'     => $strings['description']['wcag_bg_color'],
			'section'         => 'mobile_panel',
			'default'         => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et-mobile-panel .et_column.active',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_active_colors_et-mobile',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		
		'mobile_panel_active_color_custom_et-mobile' => array(
			'name'            => 'mobile_panel_active_color_custom_et-mobile',
			'type'            => 'color',
			'settings'        => 'mobile_panel_active_color_custom_et-mobile',
			'label'           => $strings['label']['wcag_color_active'],
			'tooltip'     => $strings['description']['wcag_color'],
			'section'         => 'mobile_panel',
			'default'         => '#000000',
			'choices'         => array(
				'setting' => 'setting(mobile_panel)(mobile_panel_active_background_custom_et-mobile)[background-color]',
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
			'active_callback' => array(
				array(
					'setting'  => 'mobile_panel_active_colors_et-mobile',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et-mobile-panel .et_column.active',
					'property' => 'color'
				)
			)
		),
	
	);
	
	unset($sections);
	unset($menus);
	
	return array_merge( $fields, $args );
	
} );


add_filter( 'et/customizer/add/fields', function ( $fields ) use ( $separators, $is_customize_preview, $strings, $choices, $icons, $mobile_panel_elements ) {
	
	$pages = $is_customize_preview ? et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'page',
			'with_select_page' => true
		)
	) : array();
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'mobile_panel_package_et-mobile' => array(
			'name'            => 'mobile_panel_package_et-mobile',
			'type'            => 'repeater',
			'settings'        => 'mobile_panel_package_et-mobile',
			'label'           => esc_html__( 'Sections', 'xstore-core' ),
            'tooltip' => esc_html__('Easily reorder, enable, and disable elements to create a truly unique and customized mobile experience for your customers.', 'xstore-core'),
			'section'         => 'mobile_panel',
			'priority'        => 9,
			'dynamic'         => false,
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_html__( 'Item', 'xstore-core' ),
				'field' => 'element',
			),
			'button_label'    => esc_html__( 'Add new item', 'xstore-core' ),
			'default'         => array(
				array(
					'element'     => 'home',
					'icon'        => 'et_icon-home',
					'icon_custom' => '',
					'link'        => 0,
					'custom_link' => '',
					'text'        => '',
					'is_active'   => false
				),
				array(
					'element'     => 'shop',
					'icon'        => 'et_icon-shop',
					'icon_custom' => '',
					'link'        => 0,
					'custom_link' => '',
					'text'        => '',
					'is_active'   => false
				),
				array(
					'element'     => 'cart',
					'icon'        => 'et_icon-shopping-bag',
					'icon_custom' => '',
					'link'        => 0,
					'custom_link' => '',
					'text'        => '',
					'is_active'   => false
				),
			),
			'fields'          => array(
				'element'     => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Element', 'xstore-core' ),
                    'description' => esc_html__( 'Select the element you would like to display in the mobile panel area.', 'xstore-core' ) . '<br/>' .
                        esc_html__('Tip: You can select the "Custom" variant if you want to create your own element with a unique icon, style, and link.', 'xstore-core'),
					'default' => 'shop',
					'choices' => $mobile_panel_elements
				),
				'icon'        => array(
					'type'        => 'select',
					'label'       => $strings['label']['icon'],
					'description' => esc_html__( 'With this option, you can select an available icon for your element, deactivate it, or upload a custom SVG icon using the form below.', 'xstore-core' ) . '<br/>' .
                        $strings['description']['icons_style'],
					'default'     => 'et_icon-coupon',
					'choices'     => array_merge( $icons['simple'], $icons['socials'] ),
				),
				'icon_custom' => array(
					'type'        => 'image',
					'label'       => esc_html__( 'Custom icon', 'xstore-core' ),
					'description' => $strings['description']['custom_image_svg'] . '<br/>' . esc_html__('If none of the options listed above meet your needs, you may upload a custom SVG icon or image.', 'xstore-core'),
					'default'     => '',
					'choices'     => array(
						'save_as' => 'array',
					),
				),
				'text'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Custom text', 'xstore-core' ),
                    'description' => esc_html__('Customize the text on the title of this element.', 'xstore-core'),
					'default' => '',
				),
				'is_active'   => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Animation dot', 'xstore-core' ),
					'default' => false,
				),
				'link'        => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Page link', 'xstore-core' ),
                    'description' => $strings['description']['page_links'],
					'choices' => $pages
				),
				'custom_link' => array(
					'type'    => 'link',
                    'label'           => $strings['label']['custom_link'],
                    'description'         => $strings['description']['custom_link'],
					'default' => ''
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'mobile_panel_package_et-mobile' => array(
					'selector'        => '.et-mobile-panel-wrapper .et-mobile-panel .et-wrap-columns',
					'render_callback' => 'etheme_mobile_panel_callback'
				),
			),
		),
	
	);
	
	unset($pages);
	
	return array_merge( $fields, $args );
	
} );
