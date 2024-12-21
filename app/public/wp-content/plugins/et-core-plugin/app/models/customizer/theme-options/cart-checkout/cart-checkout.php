<?php
/**
 * The template created for displaying cart/checkout options
 *
 * @version 1.0.1
 * @since   4.3
 * @log     last changes in 4.3.2
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'cart-checkout-layout' => array(
			'name'       => 'cart-checkout-layout',
			'title'      => esc_html__( 'Cart/Checkout layout', 'xstore-core' ),
			'description' => esc_html__('With easy-to-use customization tools, you can create a seamless checkout experience that is sure to impress your customers. Try it out today and elevate your online shopping experience!', 'xstore-core'),
			'panel'      => 'woocommerce',
			'icon'       => 'dashicons-cart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/cart-checkout-layout', function ( $fields ) use ( $sep_style, $strings, $box_models, $choices ) {
	$args = array();
	
	$header_box_model                        = $box_models['empty'];
	$header_box_model['border-bottom-width'] = '1px';
	$header_box_model['padding-top']         = '10px';
	$header_box_model['padding-bottom']      = '10px';
	
	$dark_mode = get_theme_mod( 'dark_styles', false );
	
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	// Array of fields
	$args = array(
		
		// main_header_wide
		'cart_checkout_advanced_layout' => array(
			'name'     => 'cart_checkout_advanced_layout',
			'type'     => 'toggle',
			'settings' => 'cart_checkout_advanced_layout',
			'label'    => __( 'Advanced layout', 'xstore-core' ),
			'tooltip' => __('Our advanced layout option offers even more flexibility and customization to ensure your customers have a seamless checkout experience.', 'xstore-core'),
			'section'  => 'cart-checkout-layout',
			'default'  => '0',
		),
		
		'cart_checkout_layout_type'                => array(
			'name'            => 'cart_checkout_layout_type',
			'type'            => 'select',
			'settings'        => 'cart_checkout_layout_type',
			'label'           => esc_html__( 'Layout', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__( 'Choose the layout type for the cart, checkout, and order pages. Note: the "%1s" option will not be available if you are using the "Advanced layout" for those pages.', 'xstore-core' ),
                '<span class="et_edit" data-parent="breadcrumbs" data-section="cart_special_breadcrumbs" style="text-decoration: underline">'.esc_html__('Special breadcrumb on the cart, checkout, and order pages', 'xstore-core').'</span>'),
			'section'         => 'cart-checkout-layout',
			'default'         => 'default',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
			'choices'         => array(
				'default'   => __( 'Classic', 'xstore-core' ),
				'multistep' => __( 'Multistep', 'xstore-core' ),
				'separated' => __( 'Separated', 'xstore-core' ),
			)
		),
		
		
		// go_to_sticky_logo
		'cart_checkout_layout_type_separated_info' => array(
			'name'            => 'cart_checkout_layout_type_separated_info',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_type_separated_info',
			'section'         => 'cart-checkout-layout',
			'default'         => '<div><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0.024c-6.6 0-11.976 5.376-11.976 11.976s5.376 11.976 11.976 11.976 11.976-5.376 11.976-11.976-5.376-11.976-11.976-11.976zM12 22.056c-5.544 0-10.056-4.512-10.056-10.056s4.512-10.056 10.056-10.056 10.056 4.512 10.056 10.056-4.512 10.056-10.056 10.056zM12.24 4.656h-0.48c-0.48 0-0.84 0.264-0.84 0.624v8.808c0 0.336 0.36 0.624 0.84 0.624h0.48c0.48 0 0.84-0.264 0.84-0.624v-8.808c0-0.336-0.36-0.624-0.84-0.624zM12.24 16.248h-0.48c-0.456 0-0.84 0.384-0.84 0.84v1.44c0 0.456 0.384 0.84 0.84 0.84h0.48c0.456 0 0.84-0.384 0.84-0.84v-1.44c0-0.456-0.384-0.84-0.84-0.84z"></path></svg>'.
                '<em style="margin-inline-start: 5px;">' . esc_html__( 'This layout may have some problems with payment plugins.', 'xstore-core' ) . '</em><br/><br/></div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '==',
					'value'    => 'separated',
				),
			)
		),

        'cart_checkout_advanced_email_first' => array(
            'name'            => 'cart_checkout_advanced_email_first',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_advanced_email_first',
            'label'           => esc_html__( 'Email field prioritized', 'xstore-core' ),
            'tooltip' => esc_html__('Enable this option to move the email field to the first position of the billing details form so that it will become the highest priority for filling in among the other fields.', 'xstore-core'),
            'section'         => 'cart-checkout-layout',
            'default'         => '0',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),
		
		'cart_checkout_advanced_form_label' => array(
			'name'            => 'cart_checkout_advanced_form_label',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_advanced_form_label',
			'label'           => esc_html__( 'Advanced labels', 'xstore-core' ),
			'tooltip'     => esc_html__( 'Enable this option to have aesthetically pleasing animated labels when filling out forms on the checkout page.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),

        'cart_checkout_advanced_sticky_sidebar' => array(
            'name'            => 'cart_checkout_advanced_sticky_sidebar',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_advanced_sticky_sidebar',
            'label'           => esc_html__( 'Sticky sidebar', 'xstore-core' ),
            'tooltip' => esc_html__( 'Turn on the option to keep the sidebar visible while scrolling the window on the cart and checkout pages.', 'xstore-core' ),
            'section'         => 'cart-checkout-layout',
            'default'         => '1',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),
		
		// go_to_sticky_logo
		'cart_checkout_sales_booster'       => array(
			'name'            => 'cart_checkout_sales_booster',
			'type'            => 'custom',
			'label'           => esc_html__( 'Sales booster features', 'xstore-core' ),
			'tooltip'     => sprintf(esc_html__( 'Enable our built-in sales booster features which will help your store make product sales faster. You can find more details in our %1s.', 'xstore-core' ),
            '<a href="'.etheme_documentation_url('110-sales-booster', false).'" target="_blank" rel="nofollow">'.esc_html__('documentation', 'xstore-core').'</a>'),
			'settings'        => 'cart_checkout_sales_booster',
			'section'         => 'cart-checkout-layout',
			'default'         => '<a href="' . admin_url( 'admin.php?page=et-panel-sales-booster&s=cart' ) . '" target="_blank" style="padding: 5px 7px; border-radius: var(--sm-border-radius); background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); text-decoration: none; box-shadow: none;">' . esc_html__( 'Sales Booster features', 'xstore-core' ) . '</a>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),

        'cart_checkout_layout_product_separator'                    => array(
            'name'            => 'cart_checkout_layout_product_separator',
            'type'            => 'custom',
            'settings'        => 'cart_checkout_layout_product_separator',
            'section'         => 'cart-checkout-layout',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-format-image"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Product settings', 'xstore-core') . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'cart_checkout_order_product_images' => array(
            'name'            => 'cart_checkout_order_product_images',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_order_product_images',
            'label'           => esc_html__( 'Show images', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Enable this option to display product images in the order details information on the checkout and thank you pages.', 'xstore-core' ),
            'section'         => 'cart-checkout-layout',
            'default'         => '1',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'cart_checkout_order_product_quantity' => array(
            'name'            => 'cart_checkout_order_product_quantity',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_order_product_quantity',
            'label'           => esc_html__( 'Show quantity', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Enable this option to add the ability to change the quantity of product displayed in the order details information on the checkout page.', 'xstore-core' ),
            'section'         => 'cart-checkout-layout',
            'default'         => '0',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'cart_checkout_order_product_remove' => array(
            'name'            => 'cart_checkout_order_product_remove',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_order_product_remove',
            'label'           => esc_html__( 'Show "remove" button', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Enable this option to display a "Remove" button for products displayed in the order details information on the checkout page.', 'xstore-core' ),
            'section'         => 'cart-checkout-layout',
            'default'         => '0',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),

        'cart_checkout_order_product_link' => array(
            'name'            => 'cart_checkout_order_product_link',
            'type'            => 'toggle',
            'settings'        => 'cart_checkout_order_product_link',
            'label'           => esc_html__( 'Product link', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Enable this option to give your customers the ability to access the product page by clicking on either the product title or product image for products displayed in the order details information on the checkout page.', 'xstore-core' ),
            'section'         => 'cart-checkout-layout',
            'default'         => '0',
            'active_callback' => array(
                array(
                    'setting'  => 'cart_checkout_advanced_layout',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            )
        ),
		
		'cart_checkout_layout_header_separator'                    => array(
			'name'            => 'cart_checkout_layout_header_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_header_separator',
			'section'         => 'cart-checkout-layout',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-arrow-up-alt"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Header', 'xstore-core') . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		// cart_checkout_header_builder
		'cart_checkout_header_builder'                             => array(
			'name'            => 'cart_checkout_header_builder',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_header_builder',
			'label'           => __( 'Use header builder', 'xstore-core' ),
			'tooltip'     => sprintf(__( 'Create multiple headers and set conditions to display them on the cart and checkout pages, or show the default header that is displayed globally across your website. More information about the multiple headers feature can be found %1s.', 'xstore-core' ),
                '<a href="https://www.youtube.com/watch?v=RbdKjQrFnO4&list=PLMqMSqDgPNmDu3kYqh-SAsfUqutW3ohlG&index=3" target="_blank" rel="nofollow">'.esc_html__('here', 'xstore-core').'</a>'),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		// logo_img
		'cart_checkout_logo_img_et-desktop'                        => array(
			'name'            => 'cart_checkout_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'cart_checkout_logo_img_et-desktop',
			'label'           => esc_html__( 'Site logo', 'xstore-core' ),
            'tooltip' => esc_html__( 'Upload an image of the logo for the header section.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => get_theme_mod( 'logo_img_et-desktop', '' ),
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			)
		),
		
		// retina_logo_img
		'cart_checkout_retina_logo_img_et-desktop'                 => array(
			'name'            => 'cart_checkout_retina_logo_img_et-desktop',
			'type'            => 'image',
			'settings'        => 'cart_checkout_retina_logo_img_et-desktop',
			'label'           => esc_html__( 'Retina logo', 'xstore-core' ),
            'tooltip' => esc_html__( 'Upload the retina image of the logo for the header section.', 'xstore-core' ) . '<br/>' .
                esc_html__('Tip: Most of the newest devices have Retina displays. To ensure a positive user experience, it is essential to include Retina images when designing a website.', 'xstore-core'),
			'section'         => 'cart-checkout-layout',
			'default'         => get_theme_mod( 'retina_logo_img_et-desktop', '' ),
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			)
		),
		
		// logo_width
		'cart_checkout_logo_width_et-desktop'                      => array(
			'name'            => 'cart_checkout_logo_width_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_checkout_logo_width_et-desktop',
			'label'           => esc_html__( 'Logo width (px)', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__('This setting controls the width of the logo image in pixels, with the default being %s.', 'xstore-core'), '140px'),
			'section'         => 'cart-checkout-layout',
			'default'         => 140,
			'choices'         => array(
				'min'  => '20',
				'max'  => '1000',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .et_b_header-logo.et_element-top-level img',
					'property' => 'width',
					'units'    => 'px'
				)
			)
		),
		
		
		// main_header_wide
		'cart_checkout_main_header_wide_et-desktop'                => array(
			'name'            => 'cart_checkout_main_header_wide_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_main_header_wide_et-desktop',
            'label'     => $strings['label']['wide_header'],
            'tooltip' => $strings['description']['wide_header'],
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '!=',
					'value'    => 'separated',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.cart-checkout-light-header .header-main-wrapper .header-main > .et-row-container',
					'function' => 'toggleClass',
					'class'    => 'et-container',
					'value'    => true
				),
				array(
					'element'  => '.cart-checkout-light-header .header-main-wrapper .header-main > .et-row-container',
					'function' => 'toggleClass',
					'class'    => 'et-container',
					'value'    => false
				),
			),
		),
		
		// main_header_sticky
		'cart_checkout_main_header_sticky_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_sticky_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_main_header_sticky_et-desktop',
//			'label'           => esc_html__( 'Main header sticky', 'xstore-core' ),
            'label'           => esc_html__( 'Header sticky', 'xstore-core' ),
            'tooltip' => esc_html__('Turn on the option to make the header area sticky when the page is scrolled.', 'xstore-core'),
			'section'         => 'cart-checkout-layout',
			'default'         => '1',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_layout_type',
					'operator' => '!=',
					'value'    => 'separated',
				),
			)
		),
		
		// header height
		// main_header_height
		'cart_checkout_main_header_height_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'cart_checkout_main_header_height_et-desktop',
            'label'     => $strings['label']['min_height'],
            'tooltip' => $strings['description']['min_height'],
			'section'         => 'cart-checkout-layout',
			'default'         => 90,
			'choices'         => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main .et-wrap-columns, .header-main .widget_nav_menu .menu > li > a',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main .widget_nav_menu .menu > li > a, .header-main #lang_sel a.lang_sel_sel, .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
				
				// sticky header same min-height
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .sticky-on .header-main .et-wrap-columns, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .et-wrap-columns',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header #header.sticky-on .header-main .widget_nav_menu .menu > li > a, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .widget_nav_menu .menu > li > a,
									.cart-checkout-light-header #header.sticky-on .header-main #lang_sel a.lang_sel_sel, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main #lang_sel a.lang_sel_sel,
									.cart-checkout-light-header #header.sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle, .cart-checkout-light-header #header[data-type="smart"].sticky-on .header-main .wcml-dropdown a.wcml-cs-item-toggle',
					'property' => 'line-height',
					'units'    => 'px'
				),
			),
		),
		
		// main_header_background
		'cart_checkout_main_header_background_et-desktop'          => array(
			'name'            => 'cart_checkout_main_header_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'cart_checkout_main_header_background_et-desktop',
			'label'           => $strings['label']['wcag_bg_color'],
			'tooltip'     => $strings['description']['wcag_bg_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => array(
				'background-color'      => $dark_mode ? '#1f1f1f' : '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-header .header-main, .cart-checkout-light-header .sticky-on .header-main',
				),
			),
		),
		
		// main_header_box_model
		'cart_checkout_main_header_box_model_et-desktop'           => array(
			'name'            => 'cart_checkout_main_header_box_model_et-desktop',
			'settings'        => 'cart_checkout_main_header_box_model_et-desktop',
			'label'           => $strings['label']['computed_box'],
			'tooltip'     => $strings['description']['computed_box'],
			'type'            => 'kirki-box-model',
			'section'         => 'cart-checkout-layout',
			'default'         => $header_box_model,
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-header .header-main',
				),
				array(
					'choice'        => 'margin-left',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.cart-checkout-light-header .sticky-on .header-main',
					'property'      => '--sticky-on-space-fix',
					'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
				),
				array(
					'choice'        => 'margin-right',
					'context'       => array( 'editor', 'front' ),
					'element'       => '.cart-checkout-light-header .sticky-on .header-main',
					'property'      => 'max-width',
					'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
				)
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array_merge(
				box_model_output( '.cart-checkout-light-header .header-main' ),
				array(
					array(
						'choice'        => 'margin-left',
						'element'       => '.cart-checkout-light-header .sticky-on .header-main',
						'property'      => '--sticky-on-space-fix',
						'type'          => 'css',
						'value_pattern' => 'calc(var(--sticky-on-space-fix2, 0px) + $)',
					),
					array(
						'choice'        => 'margin-right',
						'element'       => '.cart-checkout-light-header .sticky-on .header-main',
						'property'      => 'max-width',
						'type'          => 'css',
						'value_pattern' => 'calc(100% - var(--sticky-on-space-fix, 0px) - $)'
					)
				)
			),
		),
		
		// main_header_border
		'cart_checkout_main_header_border_et-desktop'              => array(
			'name'            => 'cart_checkout_main_header_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'cart_checkout_main_header_border_et-desktop',
			'label'           => $strings['label']['border_style'],
            'tooltip'         => $strings['description']['border_style'],
			'section'         => 'cart-checkout-layout',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main',
					'property' => 'border-style'
				)
			)
		),
		
		// main_header_border_color_custom
		'cart_checkout_main_header_border_color_custom_et-desktop' => array(
			'name'            => 'cart_checkout_main_header_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'cart_checkout_main_header_border_color_custom_et-desktop',
			'label'           => $strings['label']['border_color'],
			'tooltip'     => $strings['description']['border_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => $dark_mode ? '#2f2f2f' : '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_header_builder',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-header .header-main',
					'property' => 'border-color',
				),
			),
		),
		
		'cart_checkout_layout_footer_separator'      => array(
			'name'            => 'cart_checkout_layout_footer_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_footer_separator',
			'section'         => 'cart-checkout-layout',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-arrow-down-alt"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Footer', 'xstore-core') . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			)
		),
		
		// cart_checkout_header_builder
		'cart_checkout_default_footer'               => array(
			'name'            => 'cart_checkout_default_footer',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_default_footer',
			'label'           => __( 'Use default footer', 'xstore-core' ),
            'tooltip'     => __( 'Enable this option to show the default footer that is displayed globally across your website.', 'xstore-core' ),
			'section'         => 'cart-checkout-layout',
			'default'         => '0',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
			),
		),
		
		// main_header_background
		'cart_checkout_footer_background_et-desktop' => array(
			'name'            => 'cart_checkout_footer_background_et-desktop',
			'type'            => 'background',
			'settings'        => 'cart_checkout_footer_background_et-desktop',
			'label'           => $strings['label']['wcag_bg_color'],
			'tooltip'     => $strings['description']['wcag_bg_color'],
			'section'         => 'cart-checkout-layout',
			'default'         => array(
				'background-color'      => $dark_mode ? '#1f1f1f' : '#222222',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.cart-checkout-light-footer .footer',
				),
			),
		),
		
		// cart_checkout_footer_color
		'cart_checkout_footer_color_et-desktop'      => array(
			'name'            => 'cart_checkout_footer_color_et-desktop',
			'settings'        => 'cart_checkout_footer_color_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'tooltip'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'cart-checkout-layout',
			'default'         => '#ffffff',
			'choices'         => array(
				'setting' => 'setting(cart-checkout-layout)(cart_checkout_footer_background_et-desktop)[background-color]',
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
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.cart-checkout-light-footer .footer',
					'property' => 'color'
				)
			)
		),
		
		//
		// cart_checkout_footer_content
		'cart_checkout_footer_content'               => array(
			'name'            => 'cart_checkout_footer_content',
			'type'            => 'editor',
			'settings'        => 'cart_checkout_footer_content',
			'label'           => esc_html__( 'Content', 'xstore-core' ),
			'tooltip'     => $strings['description']['editor_control'],
			'section'         => 'cart-checkout-layout',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_footer_content_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		// html_block1_sections
		'cart_checkout_footer_content_sections'      => array(
			'name'            => 'cart_checkout_footer_content_sections',
			'type'            => 'toggle',
			'settings'        => 'cart_checkout_footer_content_sections',
			'label'           => $strings['label']['use_static_block'],
            'tooltip'         => $strings['description']['use_static_block'],
			'section'         => 'cart-checkout-layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		// html_block1_section
		'cart_checkout_footer_content_section'       => array(
			'name'            => 'cart_checkout_footer_content_section',
			'type'            => 'select',
			'settings'        => 'cart_checkout_footer_content_section',
//			'label'           => sprintf( esc_html__( 'Choose %1s for Footer content', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
			'section'         => 'cart-checkout-layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
				array(
					'setting'  => 'cart_checkout_footer_content_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		'cart_checkout_layout_copyrights_separator' => array(
			'name'            => 'cart_checkout_layout_copyrights_separator',
			'type'            => 'custom',
			'settings'        => 'cart_checkout_layout_copyrights_separator',
			'section'         => 'cart-checkout-layout',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-nametag"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Copyrights', 'xstore-core') . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
		),
		
		'cart_checkout_copyrights_content' => array(
			'name'            => 'cart_checkout_copyrights_content',
			'type'            => 'editor',
			'settings'        => 'cart_checkout_copyrights_content',
			'label'           => esc_html__( 'Content', 'xstore-core' ),
			'tooltip'     => $strings['description']['editor_control'],
			'section'         => 'cart-checkout-layout',
			'default'         => esc_html__( 'Ⓒ Created by 8theme - Power Elite ThemeForest Author.', 'xstore-core' ),
			'active_callback' => array(
				array(
					'setting'  => 'cart_checkout_advanced_layout',
					'operator' => '!=',
					'value'    => '0',
				),
				array(
					'setting'  => 'cart_checkout_default_footer',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'cart_checkout_copyrights_content' => array(
					'selector'        => '.cart-checkout-light-footer .footer .copyrights',
					'render_callback' => function () {
						return do_shortcode( get_theme_mod( 'cart_checkout_copyrights_content', esc_html__( 'Ⓒ Created by 8theme - Power Elite ThemeForest Author.', 'xstore-core' ) ) );
					},
				),
			),
		),
	
	);
	
	unset($header_box_model);
	unset($dark_mode);
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );