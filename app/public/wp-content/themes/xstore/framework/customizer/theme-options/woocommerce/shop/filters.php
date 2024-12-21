<?php
/**
 * The template created for displaying shop page filters options
 *
 * @version 1.0.0
 * @since   7.1.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-page-filters' => array(
			'name'       => 'shop-page-filters',
			'title'      => esc_html__( 'Shop page Filters', 'xstore' ),
			'panel'      => 'shop',
			'icon'       => 'dashicons-filter',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-page-filters' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'filter_opened' => array(
			'name'        => 'filter_opened',
			'type'        => 'toggle',
			'settings'    => 'filter_opened',
			'label'       => esc_html__( 'Opened state', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'If you want the "Filters" section in the shop toolbar to be opened by default, enable this option. Note: The "Filters" section will only be displayed if there are any widgets added to the "%1s" widget area.', 'xstore' ),
                '<a href="'.admin_url('widgets.php').'" target="_blank">'.esc_html__('Shop filters', 'xstore').'</a>'),
			'section'     => 'shop-page-filters',
			'default'     => 0,
		),
		
		'filters_columns' => array(
			'name'        => 'filters_columns',
			'type'        => 'slider',
			'settings'    => 'filters_columns',
			'label'       => esc_html__( '"Filters" columns', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'Control the number of columns for the "Filters" section in the shop topbar widgets area. Note: The "Filters" section will only be displayed if there are any widgets added to the "%1s" widget area.', 'xstore' ),
            '<a href="'.admin_url('widgets.php').'" target="_blank">'.esc_html__('Shop filters', 'xstore').'</a>'),
			'section'     => 'shop-page-filters',
			'default'     => 3,
			'choices'     => array(
				'min'  => 1,
				'max'  => 5,
				'step' => 1,
			),
		),
		
		'sidebar_widgets_scroll' => array(
			'name'        => 'sidebar_widgets_scroll',
			'type'        => 'toggle',
			'settings'    => 'sidebar_widgets_scroll',
			'label'       => esc_html__( 'Scrollable widgets', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to set the maximum height of the sidebar widgets.', 'xstore' ) . '<br/>' .
            esc_html__('Note: if any widget of the sidebar has content that is higher than what you set in the next option, then that widget will have a scrollbar.', 'xstore'),
			'section'     => 'shop-page-filters',
			'default'     => 0,
		),
		
		'sidebar_widgets_height' => array(
			'name'            => 'sidebar_widgets_height',
			'type'            => 'slider',
			'settings'        => 'sidebar_widgets_height',
			'label'           => esc_html__( 'Max height', 'xstore' ),
			'tooltip'     => esc_html__( 'Set the maximum height for the sidebar widgets.', 'xstore' ) . '<br/>' .
                esc_html__('Note: if any widget of the sidebar has content that is higher than what you set in this option, then that widget will have a scrollbar.', 'xstore'),
			'section'         => 'shop-page-filters',
			'default'         => 250,
			'choices'         => array(
				'min'  => 50,
				'max'  => 800,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'sidebar_widgets_scroll',
					'operator' => '==',
					'value'    => true,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.archive.woocommerce-page.s_widgets-with-scroll .sidebar .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > ul, .archive.woocommerce-page.s_widgets-with-scroll .shop-filters .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > ul, .archive.woocommerce-page.s_widgets-with-scroll .sidebar .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > div, .archive.woocommerce-page.s_widgets-with-scroll .shop-filters .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > div',
					'property' => 'max-height',
					'units'    => 'px'
				)
			)
		),
		
		'sidebar_widgets_open_close' => array(
			'name'        => 'sidebar_widgets_open_close',
			'type'        => 'toggle',
			'settings'    => 'sidebar_widgets_open_close',
			'label'       => esc_html__( 'Widget toggles', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the toggle for the sidebar widget titles to open and close the widget content. Tip: this could be useful if you have many widgets added to the sidebar, as it allows customers to collapse widgets that are not needed and quickly find and use the ones they need.', 'xstore' ),
			'section'     => 'shop-page-filters',
			'default'     => 0,
		),
		
		'sidebar_widgets_open_close_type' => array(
			'name'            => 'sidebar_widgets_open_close_type',
			'type'            => 'select',
			'settings'        => 'sidebar_widgets_open_close_type',
			'label'           => esc_html__( 'Widget toggle action', 'xstore' ),
			'tooltip'     => sprintf(esc_html__( 'Choose the default action for sidebar widget toggles. The "Collapsed on mobile" variation will use the WordPress function "%1s" to identify mobile devices. However, the "wp_is_mobile()" function may conflict with cache plugins.', 'xstore' ),
                '<a href="https://developer.wordpress.org/reference/functions/wp_is_mobile/" target="_blank" rel="nofollow">wp_is_mobile()</a>'),
			'section'         => 'shop-page-filters',
			'default'         => 'open',
			'choices'         => array(
				'open'          => esc_html__( 'Opened', 'xstore' ),
				'closed'        => esc_html__( 'Collapsed always', 'xstore' ),
				'closed_mobile' => esc_html__( 'Collapsed on mobile', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'sidebar_widgets_open_close',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		'show_plus_filters' => array(
			'name'        => 'show_plus_filters',
			'type'        => 'toggle',
			'settings'    => 'show_plus_filters',
			'label'       => esc_html__( 'Show more link', 'xstore' ),
			'tooltip' => esc_html__( 'If a widget has more items than the number set in the option below, only a limited number of items will be shown initially, and the total number of additional items will be indicated by "+X more". Clicking the "+X more" link will reveal the hidden items.', 'xstore' ),
			'section'     => 'shop-page-filters',
			'default'     => 0,
		),
		
		'show_plus_filter_after' => array(
			'name'            => 'show_plus_filter_after',
			'type'            => 'slider',
			'settings'        => 'show_plus_filter_after',
			'label'           => esc_html__( 'Initially shown items', 'xstore' ),
            'tooltip' => esc_html__( 'Set the maximum number of list items to be shown initially, with the total number of additional items indicated by "+X more". Clicking the "+X more" link will reveal the hidden items.', 'xstore' ),
            'section'         => 'shop-page-filters',
			'choices'         => array(
				'min'  => 1,
				'max'  => 10,
				'step' => 1,
			),
			'default'         => 3,
			'active_callback' => array(
				array(
					'setting'  => 'show_plus_filters',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'show_plus_less_filters' => array(
			'name'            => 'show_plus_less_filters',
			'type'            => 'toggle',
			'settings'        => 'show_plus_less_filters',
			'label'           => esc_html__( 'Show less link', 'xstore' ),
			'tooltip'     => esc_html__( 'With this option, the customer will have the option to collapse the widget once the "+X items" link has been clicked.', 'xstore' ),
			'section'         => 'shop-page-filters',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'show_plus_filters',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'ajax_product_filter' => array(
			'name'        => 'ajax_product_filter',
			'type'        => 'toggle',
			'settings'    => 'ajax_product_filter',
			'label'       => esc_html__( 'Ajax filters', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to use the Ajax technology for filters on product archive pages.', 'xstore' ),
			'section'     => 'shop-page-filters',
			'default'     => 0,
		),
		
		'ajax_categories' => array(
			'name'            => 'ajax_categories',
			'type'            => 'toggle',
			'settings'        => 'ajax_categories',
			'label'           => esc_html__( 'Ajax for the product categories widget', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to use the Ajax technology for product categories widget on product archive pages.', 'xstore' ),
			'section'         => 'shop-page-filters',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'ajax_product_filter',
					'operator' => '=',
					'value'    => 1,
				),
			)
		),
		
		'ajax_product_filter_scroll_top' => array(
			'name'            => 'ajax_product_filter_scroll_top',
			'type'            => 'toggle',
			'settings'        => 'ajax_product_filter_scroll_top',
			'label'           => esc_html__( 'Scroll to Top after Ajax filter action', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to automatically scroll the window to the top of product archive pages after the filter has been clicked. Note: this will only work if the "Ajax filters" option is enabled.', 'xstore' ),
			'section'         => 'shop-page-filters',
			'default'         => 1,
			'active_callback' => array(
				array(
					array(
						'setting'  => 'ajax_product_filter',
						'operator' => '=',
						'value'    => 1,
					),
					array(
						'setting'  => 'shop_page_pagination_type_et-desktop',
						'operator' => '=',
						'value'    => 'ajax_pagination',
					)
				)
			)
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );