<?php
/**
 * The template created for displaying general optimization options
 *
 * @version 0.0.4
 * @since 6.0.0
 * @log
 * 0.0.2
 * ADDED: Disable Gutenberg CSS option
 * ADDED: Wishlist for variation products
 * 0.0.3
 * ADDED: Always load wc-cart-fragments
 */
add_filter( 'et/customizer/add/sections', function($sections)  use($priorities){
	
	$args = array(
		'general-optimization'	 => array(
			'name'        => 'general-optimization',
			'title'          => esc_html__( 'Speed Optimization', 'xstore' ),
			'description' => esc_html__('Our speed optimization options are easy to use and require no coding knowledge, so you can improve your website\'s speed with just a few clicks. Whether you\'re running an online store, a blog, or any other type of website, our speed optimization options can help you get the most out of your site.', 'xstore'),
			'icon' => 'dashicons-dashboard',
			'priority' => $priorities['speed-optimization'],
			'type'		=> 'kirki-lazy',
			'dependency'    => array()
		)
	);
	return array_merge( $sections, $args );
});

$hook = class_exists('ETC_Initial') ? 'et/customizer/add/fields/general-optimization' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'images_loading_type_et-desktop'	=> array(
			'name'		  => 'images_loading_type_et-desktop',
			'type'        => 'select',
			'settings'    => 'images_loading_type_et-desktop',
			'label'       => esc_html__( 'Image loading type', 'xstore' ),
			'tooltip' => esc_html__('To improve the site loading speed, there is a beneficial technique that delays the loading of offscreen images, which are considered non-critical resources, and keeps them "off-screen" until the user needs them by scrolling the page to the locations of those images. Here you can choose the type of "off-screen" images until they are displayed "on-screen". Select the "Default" value to always show the images, but keep in mind that it may increase the site loading time.', 'xstore') . '<br/>' .
                        esc_html__('The "Lazy" option means that images will be replaced by a 1x1 pixel placeholder. "LQIP" (Low-Quality Image Placeholders) initially loads a low-quality, small-sized version of the final image and fills in the container.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 'lazy',
			'choices'     => array(
				'lazy' => esc_html__( 'Lazy', 'xstore' ),
				'lqip' => esc_html__( 'LQIP', 'xstore' ),
				'default' => esc_html__( 'Default', 'xstore' ),
			),
//			'priority'	  => 1,
		),

		'images_loading_offset_et-desktop'	=> array(
			'name'		  => 'images_loading_offset_et-desktop',
			'type'        => 'slider',
			'settings'    => 'images_loading_offset_et-desktop',
			'label'       => esc_html__('Image loading offset', 'xstore'),
			'tooltip' => esc_html__('Setting this option correctly will help to prevent content from jumping while scrolling the page, as the images will begin to load when they are "X" pixels off-screen. Note: the highest value of this option will cause all images that are "X" pixels off-screen to autoload, so we recommend setting the value between 100 and 500 pixels.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 200,
			'choices'     => array(
				'min'  => '0',
				'max'  => '1000',
				'step' => '10',
			),
//			'priority'	  => 2,
			'active_callback' => array(
				array(
					'setting'  => 'images_loading_type_et-desktop',
					'operator' => '!=',
					'value'    => 'default',
				),
			),
		),

        'disable_wordpress_lazy_loading'	=> array(
            'name'		  => 'disable_wordpress_lazy_loading',
            'type'        => 'toggle',
            'settings'    => 'disable_wordpress_lazy_loading',
            'label'       => esc_html__( 'Disable native WordPress lazy loading', 'xstore' ),
            'tooltip' => esc_html__( 'This option will remove attribute loading=“lazy” from all images on your website.', 'xstore' ),
            'section'     => 'general-optimization',
            'default'     => false,
            'active_callback' => array(
                array(
                    'setting'  => 'images_loading_type_et-desktop',
                    'operator' => '==',
                    'value'    => 'default',
                ),
            ),
//			'priority'	  => 3,
        ),

		'disable_old_browsers_support'	=> array(
			'name'		  => 'disable_old_browsers_support',
			'type'        => 'toggle',
			'settings'    => 'disable_old_browsers_support',
			'label'       => esc_html__( 'Disable old browser support', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to prevent the loading of the additional JavaScript library to support older browsers.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => !get_theme_mod('et_optimize_js', 0),
//			'priority'	  => 3,
		),

//		'et_optimize_css'	=> array(
//			'name'		  => 'et_optimize_css',
//			'type'        => 'toggle',
//			'settings'    => 'et_optimize_css',
//			'label'       => esc_html__( 'Optimize frontend CSS', 'xstore' ),
//			'tooltip' => esc_html__( 'Turn on to load optimized CSS. Read our documentation to do it in a properly way if you are using child theme installed before 5.0 theme release.', 'xstore' ),
//			'section'     => 'general-optimization',
//			'default'     => 0,
//			'priority'	  => 3,
//		),

//		'global_masonry'	=> array(
//			'name'		  => 'global_masonry',
//			'type'        => 'toggle',
//			'settings'    => 'global_masonry',
//			'label'       => esc_html__( 'Masonry scripts', 'xstore' ),
//			'tooltip' => esc_html__( 'Turn on to load masonry scripts to all pages. Enable this option if you plan to use WPBakery Brands list, 8theme Product Looks elements.', 'xstore' ),
//			'tooltip' => esc_html__( 'Loads masonry scripts needed to work for masonry elements (115kb of page size)', 'xstore' ),
//			'section'     => 'general-optimization',
//			'default'     => 0,
//			'priority'	  => 4,
//		),

		// fa_icons_library
		'fa_icons_library'	=> array(
			'name'		  => 'fa_icons_library',
			'type'        => 'select',
			'settings'    => 'fa_icons_library',
			'label'       => esc_html__( 'FontAwesome support', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to load the "FontAwesome" icons font and scripts.', 'xstore' ) . '<br/>' .
                    esc_html__('Notes: running the "FontAwesome" scripts and styles is necessary for some elements that use those icons, e.g. menu subitem item icons; the FontAwesome files weigh approximately 51kb and will be loaded onto your website.', 'xstore'),
			'section'     => 'general-optimization',
			'multiple'    => 1,
			'choices'     => array(
				'disable' => esc_html__('Disable', 'xstore'),
				'4.7.0' => esc_html__('4.7.0 version', 'xstore'),
				'5.15.3' => esc_html__('5.15.3 version', 'xstore'),
				'6.4.0' => esc_html__('6.4.0 version', 'xstore'),
			),
			'default' => 'disable',
//			'priority'	  => 4,
		),

		'menu_dropdown_ajax'	=> array(
			'name'		  => 'menu_dropdown_ajax',
			'type'        => 'toggle',
			'settings'    => 'menu_dropdown_ajax',
			'label'       => esc_html__( 'Ajax menu dropdown', 'xstore' ),
			'tooltip' => esc_html__( 'To improve the site loading speed and increase your website\'s grades on website testers, enable the Ajax lazy load technique for menu dropdowns. Note: the content of the menu item dropdown will begin to load when the mouse is hovered over the parent menu item.', 'xstore' ) . '<br/>' .
                    esc_html__('Note: If you are still in developer mode, please keep this option disabled in order to view changes immediately.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 1,
//			'priority'	  => 5,
		),

		'menu_dropdown_ajax_cache'	=> array(
			'name'		  => 'menu_dropdown_ajax_cache',
			'type'        => 'toggle',
			'settings'    => 'menu_dropdown_ajax_cache',
			'label'       => esc_html__( 'Menu dropdown cache', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'To improve the site loading speed, enable the \'%1s\' cache for menu dropdowns.', 'xstore' ), '<a href="https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage" target="_blank" rel="nofollow">localStorage</a>') . '<br/>' .
                esc_html__('Note: If you are still in developer mode, please keep this option disabled in order to view changes immediately.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 1,
//			'priority'	  => 6,
			'active_callback' => array(
				array(
					'setting'  => 'menu_dropdown_ajax',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'menu_cache'	=> array(
			'name'		  => 'menu_cache',
			'type'        => 'toggle',
			'settings'    => 'menu_cache',
			'label'       => esc_html__( 'Menu cache', 'xstore' ),
			'tooltip' => sprintf(esc_html__('To improve the site loading speed, enable the \'%1s\' for menus', 'xstore'), '<a href="https://developer.wordpress.org/reference/classes/wp_object_cache/" target="_blank" rel="nofollow">object_cache</a>') . '<br/>' .
                esc_html__('Note: If you are still in developer mode, please keep this option disabled in order to view changes immediately.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 7,
		),

		'ajax_search_cache'	=> array(
			'name'		  => 'ajax_search_cache',
			'type'        => 'toggle',
			'settings'    => 'ajax_search_cache',
			'label'       => esc_html__( 'Ajax search results cache', 'xstore' ),
            'tooltip' => esc_html__( 'If you would like to save most popular search results in cache, this option should be used.', 'xstore' ) . '<br/>' .
                sprintf(esc_html__('Note: This option only works for the search results of the %1s element in the Header Builder.', 'xstore'),
                    '<span class="et_edit" data-parent="search" data-section="search_ajax_et-desktop" style="text-decoration: underline;">' . esc_html__('Search', 'xstore') . '</span>'),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 8,
            'active_callback' => array( // depends on header search element's ajax option
                array(
                    'setting'  => 'search_ajax_et-desktop',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
		),

		'static_block_cache'	=> array(
			'name'		  => 'static_block_cache',
			'type'        => 'toggle',
			'settings'    => 'static_block_cache',
			'label'       => esc_html__( 'Static blocks cache', 'xstore' ),
			'tooltip' => sprintf(esc_html__('To improve the site loading speed, enable the \'%1s\' for %1s', 'xstore'), '<a href="https://developer.wordpress.org/reference/classes/wp_object_cache/" target="_blank" rel="nofollow">object_cache</a>', '<a href="'.admin_url('edit.php?post_type=staticblocks').'" target="_blank" rel="nofollow">'.esc_html__('Static blocks', 'xstore').'</a>') . '<br/>' .
                esc_html__('Note: If you are still in developer mode, please keep this option disabled in order to view changes immediately.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 1,
//			'priority'	  => 9,
		),

		'flying_pages'	=> array(
			'name'		  => 'flying_pages',
			'type'        => 'toggle',
			'settings'    => 'flying_pages',
			'label'       => esc_html__( 'Flying Pages', 'xstore' ),
			'tooltip' => esc_html__( 'The "Flying Pages" feature will pre-fetch pages before the user clicks on links, making them load instantly.', 'xstore' ) . '<br/>' .
                    esc_html__('Note: Before activating this option, please, ensure that your caching plugin does not already have a built-in link pre-loader function, because it may create conflicts between this option and the cache plugin\'s analogical option.', 'xstore'),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 10,
		),

		'wishlist_for_variations_new'	=> array(
			'name'		  => 'wishlist_for_variations_new',
			'type'        => 'toggle',
			'settings'    => 'wishlist_for_variations_new',
			'label'       => esc_html__( 'Wishlist for product variations', 'xstore' ),
			'tooltip' => esc_html__( 'By default, the "wishlist" functionality works for all main products without a separate code to make the wishlist work for each product variation separately. Enable this option so that the "wishlist" code will recognize each product variation as a separate product, allowing customers to add any product variation to their wishlist instead of adding the main parent product.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 12,
		),

		'load_wc_cart_fragments'	=> array(
			'name'		  => 'load_wc_cart_fragments',
			'type'        => 'toggle',
			'settings'    => 'load_wc_cart_fragments',
			'label'       => esc_html__( 'Always load "wc-cart-fragments"', 'xstore' ),
			'tooltip' => esc_html__( 'WooCommerce\'s "Cart Fragments" is a JavaScript script that uses the admin ajax to update the cart without refreshing the page. This functionality can slow down the speed of your site or break caching on pages that do not require cart information. Disable this option to prevent the loading of this JavaScript script if there are no products added to the customer\'s cart.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 13,
		),

		'et_load_css_minify'	=> array(
			'name'		  => 'et_load_css_minify',
			'type'        => 'toggle',
			'settings'    => 'et_load_css_minify',
			'label'       => esc_html__( 'CSS minification', 'xstore' ),
			'tooltip' => esc_html__( 'CSS minification means minimizing CSS from the original size to its smallest size without affecting its primary functionality and breaking the page’s design.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 1,
//			'priority'	  => 14,
		),

		'et_force_cache'	=> array(
			'name'		  => 'et_force_cache',
			'type'        => 'toggle',
			'settings'    => 'et_force_cache',
			'label'       => esc_html__( 'Forced cache', 'xstore' ),
			'tooltip' => esc_html__( 'Most caching plugins for WordPress use a de facto standard to exclude pages from being cached: the DONOTCACHEPAGE WordPress Core constant which allows a regular database request to WordPress. Enable this option to forcibly enable caching even if it was set somewhere to "do not cache".', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
//			'priority'	  => 15,
		),

	);

	if ( defined( 'ET_CORE_VERSION' ) ) {

		$ajaxify_widgets = et_b_get_widgets();
		// prevent some widgets from ajaxify because they contain conditions of is_shop || is_product_taxonomy()
		// so that return false in ajax actions
		$prevent_widgets_ajaxify = array(
			'WC_Widget_Layered_Nav_Filters',
			'WC_Widget_Layered_Nav',
			'WC_Widget_Price_Filter',
			'WC_Widget_Rating_Filter',
			'ETC\App\Models\Widgets\Price_Filter',
			'ETC\App\Models\Widgets\Apply_All_Filters',
			'ETC\App\Models\Widgets\Brands_Filter',
			'ETC\App\Models\Widgets\Layered_Nav_Filters',
			'ETC\App\Models\Widgets\Product_Status_Filter',
			'ETC\App\Models\Widgets\Swatches_Filter',
			'ETC\App\Models\Widgets\Categories_Filter',
		);

		foreach ( $prevent_widgets_ajaxify as $prevent_widget_ajaxify_key => $prevent_widget_ajaxify_name ) {

			if( isset( $ajaxify_widgets[ $prevent_widget_ajaxify_name ] ) ){
				$prevent_widgets_ajaxify[ $prevent_widget_ajaxify_name ] = $ajaxify_widgets[ $prevent_widget_ajaxify_name ];
			}

			unset( $prevent_widgets_ajaxify[ $prevent_widget_ajaxify_key ] );
		}

		$ajaxify_widgets = array_diff_key( $ajaxify_widgets, $prevent_widgets_ajaxify );

		$ajaxify_menus = et_b_get_terms('nav_menu');

		$args = array_merge( $args, array(

			// widgets_ajaxify
			'widgets_ajaxify'	=> array(
				'name'		  => 'widgets_ajaxify',
				'type'        => 'select',
				'multiple'    => count($ajaxify_widgets), // 0 as infinite does not work
				'settings'    => 'widgets_ajaxify',
				'label'       => esc_html__('Ajaxify widgets', 'xstore'),
				'tooltip' => esc_html__( 'Select the names of the widgets you would like to lazy-load across the website. These widgets will only be loaded if the customer scrolls the window to the point where the widget is displayed. Note: it is not recommended to select widgets that are shown in the header or first viewport, as there is no sense in making them lazy-load and loading them at once, as they are already in the first viewport.', 'xstore' ),
				'placeholder' => esc_html__( 'Click to add widgets', 'xstore' ),
				'section'     => 'general-optimization',
				'choices'     => $ajaxify_widgets,
			),

			// menus_ajaxify
			'menus_ajaxify'	=> array(
				'name'		  => 'menus_ajaxify',
				'type'        => 'select',
				'multiple'    => count($ajaxify_menus), // 0 as infinite does not work
				'settings'    => 'menus_ajaxify',
				'label'       => esc_html__('Ajaxify menus', 'xstore'),
				'tooltip' => esc_html__( 'Select the names of the menus you would like to lazy-load across the website. These menus will only be loaded if the customer scrolls the window to the point where the menu is displayed. Note: it is not recommended to select menus that are shown in the header or first viewport, as there is no sense in making them lazy-load and loading them at once, as they are already in the first viewport.', 'xstore' ),
				'placeholder' => esc_html__( 'Click to add menus', 'xstore' ),
				'section'     => 'general-optimization',
				'choices'     => $ajaxify_menus,
			),

			'cssjs_ver'	=> array(
				'name'		  => 'cssjs_ver',
				'type'        => 'toggle',
				'settings'    => 'cssjs_ver',
				'label'       => esc_html__( 'Remove query strings from static resources', 'xstore' ),
				'tooltip' => esc_html__( 'Enable this option to turn off version numbers on CSS and JS files. Before activating this option, please make sure that your caching plugin does not already have a built-in "Remove query strings" option, as this may cause conflicts between this option and the cache plugin\'s similar option.', 'xstore' ) . '<br/>' .
                    esc_html__('Note: sometimes these version numbers can cause caching problems when developing the website.', 'xstore'),
				'section'     => 'general-optimization',
				'default'     => 0,
//				'priority'	  => 8,
			),

			'disable_emoji'	=> array(
				'name'		  => 'disable_emoji',
				'type'        => 'toggle',
				'settings'    => 'disable_emoji',
				'label'       => esc_html__( 'Disable emoji', 'xstore' ),
				'tooltip' => esc_html__( 'Many people do not use emojis on their web pages. If you are one of them, then you can easily disable the CSS styles and JS scripts required for displaying the emojis simply by enabling this option.', 'xstore' ),
				'section'     => 'general-optimization',
				'default'     => 0,
//				'priority'	  => 9,
			),

			'disable_embeds'	=> array(
				'name'		  => 'disable_embeds',
				'type'        => 'toggle',
				'settings'    => 'disable_embeds',
				'label'       => esc_html__( 'Disable embeds', 'xstore' ),
				'tooltip' => esc_html__( 'If you believe that you do not require the oEmbed feature, you can disable it by activating this option. This will improve the overall loading time of your website, as every time a page is loaded, an additional HTTP request is generated on your WordPress site to load the wp-embed.min.js file. This request can sometimes be more significant than the content download size, as these types of requests accumulate over time.', 'xstore' ) . '<br/>' .
                    esc_html__('The oEmbed feature allows users to embed content from another third-party site simply by entering the source URL into the content area. WordPress will then automatically convert it into an embed and display a live preview of the source on the page.', 'xstore'),
				'section'     => 'general-optimization',
				'default'     => 1,
//				'priority'	  => 10,
			),

			'disable_rest_api'	=> array(
				'name'		  => 'disable_rest_api',
				'type'        => 'toggle',
				'settings'    => 'disable_rest_api',
				'label'       => esc_html__( 'Disable REST API endpoint', 'xstore' ),
				'tooltip' => sprintf(esc_html__( 'With this option, you can disable the %1s functionality on your website.', 'xstore' ),
                    '<a href="https://developer.wordpress.org/rest-api/" target="_blank" rel="nofollow">WP REST API</a>'),
				'section'     => 'general-optimization',
				'default'     => 0,
//				'priority'	  => 11,
			),

            'disable_jquery_migrate'	=> array(
                'name'		  => 'disable_jquery_migrate',
                'type'        => 'toggle',
                'settings'    => 'disable_jquery_migrate',
                'label'       => esc_html__( 'Disable "jQuery migrate" script', 'xstore' ),
                'tooltip' => esc_html__( 'Most recent front-end code and plugins do not require jquery-migrate.min.js (JavaScript script). In most cases, this simply adds unnecessary load to your website..', 'xstore' ),
                'section'     => 'general-optimization',
                'default'     => 0,
//                'priority'	  => 11,
            ),

			'disable_block_css'	=> array(
				'name'		  => 'disable_block_css',
				'type'        => 'toggle',
				'settings'    => 'disable_block_css',
				'label'       => esc_html__( 'Disable Gutenberg styles', 'xstore' ),
				'tooltip' => esc_html__('Every website owner wants their WordPress site to load as quickly as possible. Every request on your site will slightly slow down your pages, so if you are not using Gutenberg but are using a page builder such as Elementor, WPBakery, or the classic editor, enable this option to prevent the loading of Gutenberg CSS files with your pages.', 'xstore'),
				'section'     => 'general-optimization',
				'default'     => 0,
//				'priority'	  => 13,
			)), (defined('ELEMENTOR_VERSION') ? array(
			'disable_elementor_dialog_js'	=> array(
				'name'		  => 'disable_elementor_dialog_js',
				'type'        => 'toggle',
				'settings'    => 'disable_elementor_dialog_js',
				'label'       => esc_html__( 'Disable Elementor dialog JS', 'xstore' ),
				'tooltip' => esc_html__('Use this option to prevent the "elementor-dialog" JS script file from loading.', 'xstore') . '<br/>' .
                    esc_html__('Note: if you have any videos with modal popups in your page content or the global lightbox option is enabled in Elementor settings, then keep this option disabled to avoid any problems.', 'xstore'),
				'section'     => 'general-optimization',
				'default'     => 1,
//				'priority'	  => 14,
			),
			'disable_theme_swiper_js'	=> array(
				'name'		  => 'disable_theme_swiper_js',
				'type'        => 'toggle',
				'settings'    => 'disable_theme_swiper_js',
				'label'       => esc_html__( 'Disable theme Swiper JS', 'xstore' ),
				'tooltip' => esc_html__( 'Our theme and Elementor page builder use the Swiper.js library to create sliders. There is no sense in loading two similar "Swiper.js" JavaScript scripts which do the same thing and could cause conflicts between each other, so to prevent this situation you can enable this option to prevent loading the second "Swiper.js" library on your website.', 'xstore' ),
				'section'     => 'general-optimization',
				'default'     => 0,
//				'priority'	  => 15,
			)) : array()), array(

			// pw_jpeg_quality
			'pw_jpeg_quality'	=> array(
				'name'		  => 'pw_jpeg_quality',
				'type'        => 'slider',
				'settings'    => 'pw_jpeg_quality',
				'label'       => esc_html__('WordPress Image Quality', 'xstore'),
				'tooltip' => esc_html__( 'This option determines the quality of the generated image sizes for each uploaded image, ranging from 0 to 100 percent. Higher values result in better image quality but also larger file sizes.', 'xstore' ) . '<br/>' .
                    sprintf(esc_html__('After changing this value, please install the "%1s" plugin and run the regenerate images command.', 'xstore'), '<a href="' . admin_url( 'admin.php?page=et-panel-plugins&et_clear_plugins_transient=true&plugin=regenerate-thumbnails' ) . '" target="_blank" title="' . esc_html__( 'Regenerate Thumbnails', 'xstore' ) . '">' . esc_html__( 'Regenerate Thumbnails', 'xstore' ) . '</a>' ),
				'section'     => 'general-optimization',
				'default'     => 82,
				'choices'     => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),

			'wp_big_image_size_threshold'	=> array(
				'name'		  => 'wp_big_image_size_threshold',
				'type'        => 'slider',
				'settings'    => 'wp_big_image_size_threshold',
				'label'       => esc_html__('WordPress Big Image Size Threshold', 'xstore'),
				'tooltip' => esc_html__( 'This option sets the threshold value for the maximum height and width of newly uploaded images. If the image is larger than the threshold, WordPress will scale it down to the specified values. Tip: Set the value to "0" to disable the threshold completely.', 'xstore' ),
				'section'     => 'general-optimization',
				'default'     => 2560,
				'choices'     => array(
					'min'  => '0',
					'max'  => '5000',
					'step' => '1',
				),
			),

		));
		
	}
	
	return array_merge( $fields, $args );
	
});