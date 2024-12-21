<?php
/**
 * The template created for displaying tabs options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'tabs-settings' => array(
			'name'       => 'tabs-settings',
			'title'      => esc_html__( 'Tabs', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-index-card',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/tabs-settings' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'tabs_type' => array(
			'name'        => 'tabs_type',
			'type'        => 'select',
			'settings'    => 'tabs_type',
			'label'       => esc_html__( 'Type', 'xstore' ),
            'tooltip' => esc_html__('Using this option, you can choose a design type for this element.', 'xstore'),
			'section'     => 'tabs-settings',
			'default'     => 'tabs-default',
			'choices'     => array(
				'tabs-default' => esc_html__( 'Default', 'xstore' ),
				'left-bar'     => esc_html__( 'Left Bar', 'xstore' ),
				'accordion'    => esc_html__( 'Accordion', 'xstore' ),
				'disable'      => esc_html__( 'Disable', 'xstore' ),
			),
		),
		
		'first_tab_closed' => array(
			'name'            => 'first_tab_closed',
			'type'            => 'toggle',
			'settings'        => 'first_tab_closed',
			'label'           => esc_html__( 'First tab closed', 'xstore' ),
            'tooltip' => esc_html__('Enable this option to make the first tab closed by default when customers open the page.', 'xstore'),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'tabs_scroll' => array(
			'name'            => 'tabs_scroll',
			'type'            => 'toggle',
			'settings'        => 'tabs_scroll',
            'label'     => esc_html__( 'Scrollable content', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to set the maximum height for the content of the tabs.', 'xstore' ) . '<br/>' .
                esc_html__('Info: If any content in your tabs is higher than the height you set in the option below, then a scrollbar will appear.', 'xstore'),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '==',
					'value'    => 'accordion',
				),
			)
		),
		
		'tab_height' => array(
			'name'            => 'tab_height',
			'type'            => 'slider',
			'settings'        => 'tab_height',
            'label'       => esc_html__( 'Content max height', 'xstore' ),
            'tooltip'     => esc_html__( 'Set the maximum height for the content of the tabs.', 'xstore' ) . '<br/>' .
                esc_html__('Info: If any content in your tabs is higher than the height you set in the option below, then a scrollbar will appear.', 'xstore'),
			'section'         => 'tabs-settings',
			'default'         => 250,
			'choices'         => array(
				'min'  => 50,
				'max'  => 800,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '==',
					'value'    => 'accordion',
				),
				array(
					'setting'  => 'tabs_scroll',
					'operator' => '==',
					'value'    => true,
				),
			),
			'output'          => array(
				array(
					'etheme-context' => array( 'editor', 'front' ),
					'element'        => '.tabs-with-scroll.accordion .tab-content .tab-content-inner',
					'property'       => 'max-height',
					'units'          => 'px'
				)
			)
		),
		
		'tabs_location' => array(
			'name'            => 'tabs_location',
			'type'            => 'select',
			'settings'        => 'tabs_location',
			'label'           => esc_html__( 'Position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the placement of the tabs on individual product pages.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 'after_content',
			'choices'         => array(
				'after_image'   => esc_html__( 'Next to image', 'xstore' ),
				'after_content' => esc_html__( 'Under content', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'reviews_position' => array(
			'name'            => 'reviews_position',
			'type'            => 'select',
			'settings'        => 'reviews_position',
			'label'           => esc_html__( 'Reviews position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the placement of the reviews on individual product pages.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 'tabs',
			'choices'         => array(
				'tabs'    => esc_html__( 'Tabs', 'xstore' ),
				'outside' => esc_html__( 'Next to tabs', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),

        'product_reviews_collapsed_et-desktop' => array(
            'name'            => 'product_reviews_collapsed_et-desktop',
            'type'            => 'toggle',
            'settings'        => 'product_reviews_collapsed_et-desktop',
            'label'           => esc_html__( 'Collapsed reviews', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to make the reviews only appear when the button is clicked on mobile devices.', 'xstore' ),
            'section'         => 'tabs-settings',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'reviews_position',
                    'operator' => '==',
                    'value'    => 'outside',
                ),
            )
        ),

		'product_reviews_collapsed_et-mobile' => array(
			'name'            => 'product_reviews_collapsed_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'product_reviews_collapsed_et-mobile',
			'label'           => esc_html__( 'Collapsed reviews', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable this option to make the reviews only appear when the button is clicked on mobile devices.', 'xstore' ) . '<br/>' .
                sprintf(esc_html__('To properly identify mobile devices, we use the WordPress function "%1s". However, this function may conflict with cache plugins.', 'xstore' ),
                    '<a href="https://developer.wordpress.org/reference/functions/wp_is_mobile/" target="_blank" rel="nofollow">wp_is_mobile()</a>'),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'reviews_position',
					'operator' => '==',
					'value'    => 'outside',
				),
			)
		),
		
		'custom_tab_title' => array(
			'name'            => 'custom_tab_title',
			'type'            => 'etheme-text',
			'settings'        => 'custom_tab_title',
			'label'           => esc_html__( 'Custom tab title', 'xstore' ),
            'tooltip' => esc_html__('Customize the title text displayed for "Custom tab".', 'xstore'),
			'section'         => 'tabs-settings',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'custom_tab' => array(
			'name'            => 'custom_tab',
			'type'            => 'editor',
			'settings'        => 'custom_tab',
			'label'           => esc_html__( 'Custom tab content', 'xstore' ),
			'tooltip'     => esc_html__( 'Here, you can write your own custom HTML using the tags in the top bar of the editor. However, please note that not all HTML tags and element attributes can be used due to Theme Options safety reasons.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
	);
	
	return array_merge( $fields, $args );
	
} );