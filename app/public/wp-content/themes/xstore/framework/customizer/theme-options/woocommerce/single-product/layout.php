<?php
/**
 * The template created for displaying single product layout options
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-layout' => array(
			'name'       => 'single-product-page-layout',
			'title'      => esc_html__( 'Layout', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-schedule',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-layout' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $sep_style, $single_product_layout, $sidebars ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'single_layout' => array(
			'name'        => 'single_layout',
			'type'        => 'radio-image',
			'settings'    => 'single_layout',
			'label'       => esc_html__( 'Layout', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the layout type for the individual product pages.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'default',
			'choices'     => $single_product_layout,
		),
		
		'single_sidebar' => array(
			'name'        => 'single_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'single_sidebar',
            'label'           => esc_html__( 'Sidebar position', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the position of the sidebar for the single product pages.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'without',
			'choices'     => $sidebars,
		),
		
		'single_product_hide_sidebar' => array(
			'name'        => 'single_product_hide_sidebar',
			'type'        => 'toggle',
			'settings'    => 'single_product_hide_sidebar',
            'label'       => esc_html__( 'Hide sidebar on mobile devices', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on the option to hide the sidebar on mobile devices.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 0,
		),
		
		'fixed_images' => array(
			'name'            => 'fixed_images',
			'type'            => 'toggle',
			'settings'        => 'fixed_images',
			'label'           => esc_html__( 'Sticky gallery', 'xstore' ),
			'tooltip'     => sprintf(esc_html__( 'Enable the option to keep the product gallery visible while scrolling the window on the single product page. Note: If the "%1s" option is enabled, then keep this option disabled.', 'xstore' ),
                '<span class="et_edit" data-parent="single-product-page-layout" data-section="fixed_content" style="text-decoration: underline">'.esc_html__('Sticky content', 'xstore').'</span>'),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => 'in',
					'value'    => array( 'small', 'default', 'xsmall', 'wide', 'right' ),
				),
			)
		),
		
		'fixed_content' => array(
			'name'            => 'fixed_content',
			'type'            => 'toggle',
			'settings'        => 'fixed_content',
			'label'           => esc_html__( 'Sticky content', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Enable the option to keep the product content visible while scrolling the window on the single product page. Note: If the "%1s" option is enabled, then keep this option disabled.', 'xstore' ),
                '<span class="et_edit" data-parent="single-product-page-layout" data-section="fixed_images" style="text-decoration: underline">'.esc_html__('Sticky gallery', 'xstore').'</span>'),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => 'in',
					'value'    => array( 'small', 'default', 'xsmall', 'wide', 'right' ),
				),
			)
		),
		
		'product_name_signle'            => array(
			'name'        => 'product_name_signle',
			'type'        => 'toggle',
			'settings'    => 'product_name_signle',
            'label'       => esc_html__( 'Show product name', 'xstore' ),
            'tooltip'     => esc_html__( 'Turn on the option to display the product title in the breadcrumb trail.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 0,
		),
		
		// product_breadcrumbs_product_title_duplicated
		'product_name_single_duplicated' => array(
			'name'            => 'product_name_single_duplicated',
			'type'            => 'toggle',
			'settings'        => 'product_name_single_duplicated',
            'label'           => esc_html__( 'Leave product name in content', 'xstore' ),
            'tooltip'     => esc_html__( 'Enable the option to display the product title in the product content as well.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_name_signle',
					'operator' => '!=',
					'value'    => 0
				)
			),
		),
		
		'share_icons' => array(
			'name'        => 'share_icons',
			'type'        => 'toggle',
			'settings'    => 'share_icons',
			'label'       => esc_html__( 'Share buttons', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display the share buttons in the content on each individual product page.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),

        'attributes_after_description' => array(
            'name'        => 'attributes_after_description',
            'type'        => 'toggle',
            'settings'    => 'attributes_after_description',
            'label'       => esc_html__( 'Show attributes after excerpt', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to display the product attributes (e.g. color, size, material) after the product short description on each individual product page.', 'xstore' ) . '<br/>' .
                esc_html__('Note: The "Additional Information" tab will be removed as it contains the same product attributes information.', 'xstore'),
            'section'     => 'single-product-page-layout',
            'default'     => 0,
        ),
		
		'ajax_add_to_cart' => array(
			'name'        => 'ajax_add_to_cart',
			'type'        => 'toggle',
			'settings'    => 'ajax_add_to_cart',
			'label'       => esc_html__( 'Ajax add to cart', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to use Ajax technology when adding products to the cart from the individual product page. Note: this option only works for simple and variable products, and adding these products to the cart will be done without refreshing the page. It is important to note that third-party plugins may conflict with this option, so it may be necessary to keep it disabled for better compatibility with some plugins.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'product_zoom' => array(
			'name'        => 'product_zoom',
			'type'        => 'toggle',
			'settings'    => 'product_zoom',
            'label'           => esc_html__( 'Zoom', 'xstore' ),
            'tooltip'         => esc_html__('Increase your sales by providing customers with a clear view of your products. Enable the option to add a zoom feature to product images so that customers can quickly and easily zoom in on the product image.', 'xstore'),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'thumbs_slider_mode' => array(
			'name'        => 'thumbs_slider_mode',
			'type'        => 'select',
			'settings'    => 'thumbs_slider_mode',
			'label'       => esc_html__( 'Gallery slider', 'xstore' ),
            'tooltip' => sprintf(esc_html__('If you would like to display the product gallery in a slider format, you should enable this feature. Note: if you would like to keep the gallery items in a grid format, you should keep this option inactive. Note: The "Enable on mobile" variation will use the WordPress function "%1s" to identify mobile devices. However, the "wp_is_mobile()" function may conflict with cache plugins.', 'xstore'),
                '<a href="https://developer.wordpress.org/reference/functions/wp_is_mobile/" target="_blank" rel="nofollow">wp_is_mobile()</a>'),
			'section'     => 'single-product-page-layout',
			'default'     => 'enable',
			'choices'     => array(
				'enable'     => esc_html__( 'Enable', 'xstore' ),
				'enable_mob' => esc_html__( 'Enable on mobile', 'xstore' ),
				'disable'    => esc_html__( 'Disable', 'xstore' )
			),
		),
		
		'thumbs_autoheight' => array(
			'name'            => 'thumbs_autoheight',
			'type'            => 'toggle',
			'settings'        => 'thumbs_autoheight',
			'label'           => esc_html__( 'Slider auto height', 'xstore' ),
			'tooltip'     => esc_html__( 'Turn on the auto height feature for the product gallery image slider.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
			)
		),
		
		'product_video_position' => array(
			'name'        => 'product_video_position',
			'type'        => 'select',
			'settings'    => 'product_video_position',
            'label'       => esc_html__( 'Video position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the product video in the product gallery. Note: the position of the video will be applied to products which have at least one video added in their settings.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'last',
			'choices'     => array(
				'first' => esc_html__( 'First', 'xstore' ),
				'last'  => esc_html__( 'Last', 'xstore' ),
			),
		),

		'product_video_placeholder' => array(
			'name'            => 'product_video_placeholder',
			'type'            => 'toggle',
			'settings'        => 'product_video_placeholder',
			'label'           => esc_html__( 'Show video placeholder', 'xstore' ),
			'tooltip'     => esc_html__( 'Will show placeholders for YouTube and Vimeo Videos.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
			)
		),
		
		'stretch_product_slider' => array(
			'name'            => 'stretch_product_slider',
			'type'            => 'toggle',
			'settings'        => 'stretch_product_slider',
			'label'           => esc_html__( 'Stretch slider', 'xstore' ),
			'tooltip'     => esc_html__( 'Enable this option to stretch the main gallery slider. The main gallery will be displayed in full with the slider and parts of the previous and next gallery images on either side of the carousel. Note: if this option is activated, the thumbnails will not be shown below the main gallery.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'single_layout',
					'operator' => '==',
					'value'    => 'large',
				),
			)
		),
		
		'thumbs_slider_vertical' => array(
			'name'            => 'thumbs_slider_vertical',
			'type'            => 'select',
			'settings'        => 'thumbs_slider_vertical',
			'label'           => esc_html__( 'Thumbnails', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the direction of the gallery thumbnails or disable them at all.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 'horizontal',
			'choices'         => array(
				'horizontal' => esc_html__( 'Horizontal', 'xstore' ),
				'vertical'   => esc_html__( 'Vertical', 'xstore' ),
				'disable'    => esc_html__( 'Disable', 'xstore' )
			),
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
			)
		),
		
		'count_slides' => array(
			'name'            => 'count_slides',
			'type'            => 'slider',
			'settings'        => 'count_slides',
			'label'           => esc_html__( 'Thumbnails slides', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of slides displayed in the thumbnail slider.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => 4,
			'choices'         => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'thumbs_slider_mode',
					'operator' => '==',
					'value'    => 'enable',
				),
				array(
					'setting'  => 'thumbs_slider_vertical',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		// 'single_wishlist_type'	=> array(
		// 'name'		  => 'single_wishlist_type',
		// 	'type'        => 'select',
		// 	'settings'    => 'single_wishlist_type',
		// 	'label'       => esc_html__( 'Wishlist type', 'xstore' ),
		// 	'tooltip' => esc_html__( 'Only for "Use shortcode" wislist position', 'xstore'),
		// 	'section'     => 'single-product-page-layout',
		// 	'default'     => 'icon',
		// 	'choices'     => array(
		// 		'icon' => esc_html__( 'Icon', 'xstore' ),
		//               'icon-text' => esc_html__( 'Icon + text', 'xstore' ),
		// 	),
		// ),
		
		// 'single_wishlist_position'	=> array(
		// 'name'		  => 'single_wishlist_position',
		// 	'type'        => 'select',
		// 	'settings'    => 'single_wishlist_position',
		// 	'label'       => esc_html__( 'Wishlist position', 'xstore' ),
		// 	'tooltip' => esc_html__( 'Only for "Use shortcode" wislist position', 'xstore'),
		// 	'section'     => 'single-product-page-layout',
		// 	'default'     => 'after',
		// 	'choices'     => array(
		// 		'after' => esc_html__( 'After "add to cart" button', 'xstore' ),
		//               'under' => esc_html__( 'Under "add to cart" button', 'xstore' ),
		// 	),
		// ),
		
		'upsell_location' => array(
			'name'        => 'upsell_location',
			'type'        => 'select',
			'settings'    => 'upsell_location',
			'label'       => esc_html__( 'Upsell products position', 'xstore' ),
			'tooltip' => esc_html__( 'This determines the placement of the upsell products. If you select the "Sidebar" option, make sure that the sidebar is enabled on the individual product pages.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'sidebar',
			'choices'     => array(
				'sidebar'       => esc_html__( 'Sidebar', 'xstore' ),
				'after_content' => esc_html__( 'After content', 'xstore' ),
				'none'          => esc_html__( 'None', 'xstore' ),
			),
		),
		
		'cross_sell_location' => array(
			'name'        => 'cross_sell_location',
			'type'        => 'select',
			'settings'    => 'cross_sell_location',
			'label'       => esc_html__( 'Cross-sell products position', 'xstore' ),
            'tooltip' => esc_html__( 'This determines the placement of the cross-sell products. If you select the "Sidebar" option, make sure that the sidebar is enabled on the individual product pages.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 'none',
			'choices'     => array(
				'sidebar'       => esc_html__( 'Sidebar', 'xstore' ),
				'after_content' => esc_html__( 'After content', 'xstore' ),
				'none'          => esc_html__( 'None', 'xstore' ),
			),
		),
		
		'product_posts_links' => array(
			'name'        => 'product_posts_links',
			'type'        => 'toggle',
			'settings'    => 'product_posts_links',
			'label'       => esc_html__( 'Prev/Next navigation', 'xstore' ),
            'tooltip'  => esc_html__( 'Enable this option to display the previous and next navigation arrows on your single product pages. Note: the previous and next product links are based on the products which are in the same primary category as the current product.', 'xstore' ),
			'section'     => 'single-product-page-layout',
			'default'     => 1,
		),
		
		'size_guide_type' => array(
			'name'     => 'size_guide_type',
			'type'     => 'radio-buttonset',
			'settings' => 'size_guide_type',
			'label'    => esc_html__( 'Size guide type', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 'popup',
			'multiple' => 1,
			'choices'  => array(
				'popup'           => esc_html__( 'Lightbox', 'xstore' ),
				'download_button' => esc_html__( 'Download Button', 'xstore' ),
			),
		),
		
		'size_guide_img' => array(
			'name'            => 'size_guide_img',
			'type'            => 'image',
			'settings'        => 'size_guide_img',
			'label'           => esc_html__( 'Size guide image', 'xstore' ),
			'tooltip'     => esc_html__( 'Upload size guide image to show size guide link and size guide image in lightbox after click.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'size_guide_type',
					'operator' => '==',
					'value'    => 'popup',
				),
			),
		),
		
		'size_guide_file' => array(
			'name'            => 'size_guide_file',
			'type'            => 'upload',
			'settings'        => 'size_guide_file',
			'label'           => esc_html__( 'File', 'xstore' ),
			'tooltip'     => esc_html__( 'Upload size guide file.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'active_callback' => array(
				array(
					'setting'  => 'size_guide_type',
					'operator' => '==',
					'value'    => 'download_button',
				),
			),
		),
		
		'sticky_added_to_cart_message'                           => array(
			'name'      => 'sticky_added_to_cart_message',
			'type'      => 'toggle',
			'settings'  => 'sticky_added_to_cart_message',
			'label'     => esc_html__( 'Fixed added to cart message', 'xstore' ),
			'section'   => 'single-product-page-layout',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-on',
					'value'    => true
				),
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-off',
					'value'    => false
				),
			),
		),
		
		// stretch_add_to_cart
		'stretch_add_to_cart_et-desktop'                         => array(
			'name'     => 'stretch_add_to_cart_et-desktop',
			'type'     => 'toggle',
			'settings' => 'stretch_add_to_cart_et-desktop',
            'label' => esc_html__('Stretch "Add to Cart"', 'xstore'),
            'tooltip' => esc_html__('Enable this option to make the "Add to Cart" button expand to the full width of its parent.', 'xstore'),
			'section'  => 'single-product-page-layout',
			'default'  => 0,
		),
		
		// sticky_add_to_cart
		'sticky_add_to_cart_et-desktop'                          => array(
			'name'      => 'sticky_add_to_cart_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'sticky_add_to_cart_et-desktop',
			'label'     => esc_html__( 'Sticky cart', 'xstore' ),
			'tooltip' => esc_html__('If the product content is lengthy, users often find it difficult to click the "Add to Cart" button, as it is located at the top of the page. Enable this option if you wish to give your visitors the opportunity to purchase even when the page has been scrolled a lot, thus increasing your store\'s sales.', 'xstore'),
			'section'   => 'single-product-page-layout',
			'default'   => 0,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),

		// show single stock
		'show_single_stock'                                      => array(
			'name'     => 'show_single_stock',
			'type'     => 'toggle',
			'settings' => 'show_single_stock',
			'label'    => esc_html__( 'Stock status', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to force the display of the stock status of the product on its individual page.', 'xstore' ),
			'section'  => 'single-product-page-layout',
			'default'  => 0,
		),
		
		// style separator
		'buy_now_btn_style_separator'                            => array(
			'name'            => 'buy_now_btn_style_separator',
			'type'            => 'custom',
			'settings'        => 'buy_now_btn_style_separator',
			'section'         => 'single-product-page-layout',
			'default'         => '<div style="'.$sep_style.'"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-inline-start: 5px;">' . esc_html__( '"Buy now" button', 'xstore' ) . '</span></div>',
//			'active_callback' => array(
//				array(
//					'setting'  => 'buy_now_btn',
//					'operator' => '==',
//					'value'    => 1,
//				),
//			),
		),

        // buy now btn
        'buy_now_btn'                                            => array(
            'name'     => 'buy_now_btn',
            'type'     => 'toggle',
            'settings' => 'buy_now_btn',
            'label' => esc_html__('"Buy Now" button', 'xstore'),
            'tooltip' => esc_html__( 'With this option, your customers will be able to purchase the product from individual product pages, which will automatically redirect them to the checkout page. Enable this option to add this functionality to your website.', 'xstore' ),
            'section'  => 'single-product-page-layout',
            'default'  => 0,
        ),
		
		// product_cart_buy_now_color
		'product_cart_buy_now_color_et-desktop'                  => array(
			'name'            => 'product_cart_buy_now_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_et-desktop',
			'label'           => esc_html__( 'Color', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the color for the "Buy Now" button.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color'
				),
			),
		),
		
		// product_cart_buy_now_background_color
		'product_cart_buy_now_background_color_et-desktop'       => array(
			'name'            => 'product_cart_buy_now_background_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_et-desktop',
			'label'           => esc_html__( 'Background color', 'xstore' ),
			'tooltip'         => esc_html__( 'Choose the background color for the "Buy Now" button.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#339438',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color'
				),
			),
		),
		
		// product_cart_buy_now_color_hover
		'product_cart_buy_now_color_hover_et-desktop'            => array(
			'name'            => 'product_cart_buy_now_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_hover_et-desktop',
			'label'           => esc_html__( 'Color (hover)', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the color for the "Buy Now" button when hovering over it.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color-hover',
				),
			),
		),
		
		// product_cart_buy_now_background_color_hover
		'product_cart_buy_now_background_color_hover_et-desktop' => array(
			'name'            => 'product_cart_buy_now_background_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_hover_et-desktop',
			'label'           => esc_html__( 'Background color (hover)', 'xstore' ),
            'tooltip'         => esc_html__( 'Choose the background color for the "Buy Now" button.', 'xstore' ),
			'section'         => 'single-product-page-layout',
			'default'         => '#2e7d32',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color-hover'
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );