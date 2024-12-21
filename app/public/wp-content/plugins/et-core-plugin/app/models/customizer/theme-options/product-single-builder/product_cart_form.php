<?php
/**
 * The template created for displaying single product cart form options
 *
 * @since   1.5.0
 * @version 1.0.2
 * last changes in 1.5.5
 * @log     1.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 *
 */
add_filter('et/customizer/add/sections', function ($sections) {

    $args = array(
        'product_cart_form' => array(
            'name' => 'product_cart_form',
            'title' => esc_html__('Add to cart & quantity', 'xstore-core'),
            'panel' => 'single_product_builder',
            'icon' => 'dashicons-cart',
            'type' => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge($sections, $args);
});

add_filter('et/customizer/add/fields/product_cart_form', function ($fields) use ($separators, $strings, $choices, $sep_style) {
    $sections = et_b_get_posts(
        array(
            'post_per_page' => -1,
            'nopaging'      => true,
            'post_type'     => 'staticblocks',
            'with_none' => true
        )
    );
    
    $args = array();

    // Array of fields
    $args = array(
        // content separator
        'product_quantity_content_separator' => array(
            'name' => 'product_quantity_content_separator',
            'type' => 'custom',
            'settings' => 'product_quantity_content_separator',
            'section' => 'product_cart_form',
            'default' => $separators['content'],
        ),

        // product_quantity_style
        'product_quantity_style_et-desktop' => array(
            'name' => 'product_quantity_style_et-desktop',
            'type' => 'radio-image',
            'settings' => 'product_quantity_style_et-desktop',
            'label' => $strings['label']['style'],
            'tooltip' => $strings['description']['style'],
            'section' => 'product_cart_form',
            'default' => 'simple',
            'priority' => 10,
            'choices' => array(
                'simple' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/1.svg',
                'circle' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/2.svg',
                'square' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/3.svg',
                'select' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/5.svg',
                'none' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/4.svg',
            ),
//			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-simple',
//					'value'    => 'simple'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-circle',
//					'value'    => 'circle'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-square',
//					'value'    => 'square'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-none',
//					'value'    => 'none'
//				),
//				array(
//					'element'  => '.quantity-wrapper span.et-icon',
//					'function' => 'toggleClass',
//					'class'    => 'none',
//					'value'    => 'none'
//				),
//			),
        ),

        // product_cart_form_direction
        'product_cart_form_direction_et-desktop' => array(
            'name' => 'product_cart_form_direction_et-desktop',
            'type' => 'radio-buttonset',
            'settings' => 'product_cart_form_direction_et-desktop',
            'label' => $strings['label']['direction'],
            'tooltip' => esc_html__('Choose the content direction for this element.', 'xstore-core'),
            'section' => 'product_cart_form',
            'default' => 'row',
            'choices' => $choices['direction']['type2'],
            'transport' => 'postMessage',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder form.cart, .single-product-builder form.cart .woocommerce-variation-add-to-cart',
                    'property' => 'flex-direction',
                ),
            ),
            'js_vars' => array(
                array(
                    'element' => '.single-product-builder form.cart span.hidden',
                    'function' => 'toggleClass',
                    'class' => 'dir-column',
                    'value' => 'column'
                ),
                array(
                    'element' => '.single-product-builder form.cart, .single-product-builder form.cart .woocommerce-variation-add-to-cart',
                    'function' => 'css',
                    'property' => 'flex-direction',
                ),
            ),
        ),

        'product_quantity_select_ranges' => array(
            'name' => 'product_quantity_select_ranges',
            'type' => 'etheme-textarea',
            'settings' => 'product_quantity_select_ranges',
            'label' => esc_html__('Ranges', 'xstore-core'),
            'tooltip' => esc_html__( 'Add variants and allow the customer to select the quantity of products shown in the selection. Enter each value on one line and use a range, e.g. "1-5".', 'xstore-core' ) .
                '<br/>' . esc_html__('Tip: It is always possible to configure specific settings for quantity types and quantity select range on the single product page settings page from the dashboard.', 'xstore-core') . '<br/>' .
        sprintf(esc_html__('To configure global quantity types and ranges, please go to the "%1s" settings.', 'xstore-core'),
            '<span class="et_edit" data-parent="shop-quantity" data-section="shop_quantity_type" style="text-decoration: underline">'.esc_html__('Quantity', 'xstore-core').'</span>'),
            'section' => 'product_cart_form',
            'default' => get_theme_mod('shop_quantity_select_ranges', '1-5'),
            'active_callback' => array(
                array(
                    'setting' => 'product_quantity_style_et-desktop',
                    'operator' => '==',
                    'value' => 'select',
                ),
            ),
        ),

        'go_to_section_product_waitlist_2'                 => array(
            'name'     => 'go_to_section_product_waitlist_2',
            'type'     => 'custom',
            'settings' => 'go_to_section_product_waitlist_2',
            'section'  => 'product_cart_form',
            'default'  => '<span class="et_edit" data-parent="product_waitlist" data-section="product_waitlist_content_separator" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Waitlist settings', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_waitlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // content separator
        'product_cart_style_separator' => array(
            'name' => 'product_cart_style_separator',
            'type' => 'custom',
            'settings' => 'product_cart_style_separator',
            'section' => 'product_cart_form',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-button"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Button styles', 'xstore-core') . '</span></div>',
        ),

        // product_add_to_cart_fonts
        'product_add_to_cart_fonts_et-desktop' => array(
            'name' => 'product_add_to_cart_fonts_et-desktop',
            'type' => 'typography',
            'label' => $strings['label']['fonts'],
            'tooltip' => $strings['description']['fonts'],
            'settings' => 'product_add_to_cart_fonts_et-desktop',
            'section' => 'product_cart_form',
            'default' => array(
                // 'font-family'    => '',
                // 'variant'        => 'regular',
                // 'font-size'      => '14px',
                // 'line-height'    => '1.5',
                // 'letter-spacing' => '0',
                // 'color'          => '#555',
                'text-transform' => 'none',
                // 'text-align'     => 'left',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button',
                ),
            ),
        ),

        // product_add_to_cart_size
        'product_add_to_cart_size_et-desktop' => array(
            'name' => 'product_add_to_cart_size_et-desktop',
            'type' => 'slider',
            'settings' => 'product_add_to_cart_size_et-desktop',
            'label' => $strings['label']['button_size_proportion'],
            'tooltip' => $strings['description']['size_proportion'],
            'section' => 'product_cart_form',
            'default' => 1,
            'choices' => array(
                'min' => '0',
                'max' => '5',
                'step' => '.1',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-button-proportion',
                )
            )
        ),

        // product_add_to_cart_min_width
        'product_add_to_cart_min_width_et-desktop' => array(
            'name' => 'product_add_to_cart_min_width_et-desktop',
            'type' => 'slider',
            'settings' => 'product_add_to_cart_min_width_et-desktop',
            'label' => esc_html__('Min width (px)', 'xstore-core'),
            'tooltip' => esc_html__( 'This sets the minimum width of this element.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => 120,
            'choices' => array(
                'min' => '90',
                'max' => '400',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button',
                    'property' => 'min-width',
                    'units' => 'px'
                )
            )
        ),

        // product_add_to_cart_min_height
        'product_add_to_cart_min_height_et-desktop' => array(
            'name' => 'product_add_to_cart_min_height_et-desktop',
            'type' => 'slider',
            'settings' => 'product_add_to_cart_min_height_et-desktop',
            'label' => $strings['label']['min_height'],
            'tooltip' => esc_html__( 'This sets the minimum height for this element and quantity input/select.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => 40,
            'choices' => array(
                'min' => '30',
                'max' => '150',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button',
                    'property' => 'min-height',
                    'units' => 'px'
                ),
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder form.cart select[name=quantity]',
                    'property' => 'min-height',
                    'units' => 'px'
                )
            )
        ),

        // product_add_to_cart_radius
        'product_add_to_cart_radius_et-desktop' => array(
            'name' => 'product_add_to_cart_radius_et-desktop',
            'type' => 'slider',
            'settings' => 'product_add_to_cart_radius_et-desktop',
            'label' => $strings['label']['border_radius'],
            'tooltip' => $strings['description']['border_radius'],
            'section' => 'product_cart_form',
            'default' => 0,
            'choices' => array(
                'min' => '0',
                'max' => '100',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button, .single-product-builder .single_add_to_cart_button.button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button,
					.single-product-builder .single_add_to_cart_button:hover, .single-product-builder .single_add_to_cart_button.button:hover, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:hover,
					.single-product-builder .single_add_to_cart_button:focus, .single-product-builder .single_add_to_cart_button.button:focus, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:focus',
                    'property' => 'border-radius',
                    'units' => 'px'
                )
            )
        ),

        // product_cart_background_custom
        'product_cart_background_custom_et-desktop' => array(
            'name' => 'product_cart_background_custom_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_background_custom_et-desktop',
            'label' => $strings['label']['bg_color'],
            'tooltip' => $strings['description']['wcag_bg_color'],
            'section' => 'product_cart_form',
            'default' => '#222222',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-background-color'
                ),
            ),
        ),

        'product_cart_color_et-desktop' => array(
            'name' => 'product_cart_color_et-desktop',
            'settings' => 'product_cart_color_et-desktop',
            'label'       => $strings['label']['wcag_color'],
            'tooltip' => $strings['description']['wcag_color'],
            'type' => 'kirki-wcag-tc',
            'section' => 'product_cart_form',
            'default' => '#ffffff',
            'choices' => array(
                'setting' => 'setting(product_cart_form)(product_cart_background_custom_et-desktop)',
                // 'maxHueDiff'          => 60,   // Optional.
                // 'stepHue'             => 15,   // Optional.
                // 'maxSaturation'       => 0.5,  // Optional.
                // 'stepSaturation'      => 0.1,  // Optional.
                // 'stepLightness'       => 0.05, // Optional.
                // 'precissionThreshold' => 6,    // Optional.
                // 'contrastThreshold'   => 4.5   // Optional.
                'show' => array(
                    // 'auto'        => false,
                    // 'custom'      => false,
                    'recommended' => false,
                ),
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-color',
                ),
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button',
                    'property' => '--loader-side-color'
                ),
            ),
        ),

        // product_cart_background_custom_hover
        'product_cart_background_custom_hover_et-desktop' => array(
            'name' => 'product_cart_background_custom_hover_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_background_custom_hover_et-desktop',
            'label' => esc_html__('Background color (hover)', 'xstore-core'),
            'tooltip' => $strings['description']['wcag_bg_color'],
            'section' => 'product_cart_form',
            'default' => '#555555',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-hover-background-color',
                ),
            ),
        ),
        'product_cart_color_hover_et-desktop' => array(
            'name' => 'product_cart_color_hover_et-desktop',
            'settings' => 'product_cart_color_hover_et-desktop',
            'label' => esc_html__('Color (hover)', 'xstore-core'),
            'tooltip' => $strings['description']['wcag_color'],
            'type' => 'kirki-wcag-tc',
            'section' => 'product_cart_form',
            'default' => '#ffffff',
            'choices' => array(
                'setting' => 'setting(product_cart_form)(product_cart_background_custom_hover_et-desktop)',
                // 'maxHueDiff'          => 60,   // Optional.
                // 'stepHue'             => 15,   // Optional.
                // 'maxSaturation'       => 0.5,  // Optional.
                // 'stepSaturation'      => 0.1,  // Optional.
                // 'stepLightness'       => 0.05, // Optional.
                // 'precissionThreshold' => 6,    // Optional.
                // 'contrastThreshold'   => 4.5   // Optional.
                'show' => array(
                    // 'auto'        => false,
                    // 'custom'      => false,
                    'recommended' => false,
                ),
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-hover-color',
                ),
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder .single_add_to_cart_button:hover, .single-product-builder .single_add_to_cart_button:focus, .single-product-builder .single_add_to_cart_button:hover:focus, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:hover',
                    'property' => '--loader-side-color'
                ),
            ),
        ),

        // style separator
        'product_cart_buy_now_style_separator' => array(
            'name' => 'product_cart_buy_now_style_separator',
            'type' => 'custom',
            'settings' => 'product_cart_buy_now_style_separator',
            'section' => 'product_cart_form',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-button"></span> <span style="padding-inline-start: 5px;">' . esc_html__('"Buy now" button', 'xstore-core') . '</span></div>',
//            'active_callback' => array(
//                array(
//                    'setting' => 'buy_now_btn',
//                    'operator' => '==',
//                    'value' => 1,
//                ),
//            )
        ),

        // buy now btn
        'buy_now_btn' => array(
            'name' => 'buy_now_btn',
            'type' => 'toggle',
            'settings' => 'buy_now_btn',
            'label' => esc_html__('"Buy Now" button', 'xstore-core'),
            'tooltip' => esc_html__( 'With this option, your customers will be able to purchase the product from individual product pages, which will automatically redirect them to the checkout page. Enable this option to add this functionality to your website.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => 0,
        ),

        // product_cart_buy_now_color
        'product_cart_buy_now_color_et-desktop' => array(
            'name' => 'product_cart_buy_now_color_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_buy_now_color_et-desktop',
            'label' => esc_html__('Color', 'xstore-core'),
            'tooltip'     => esc_html__( 'Choose the color for the "Buy Now" button.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => '#ffffff',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting' => 'buy_now_btn',
                    'operator' => '==',
                    'value' => 1,
                ),
            ),
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-buy-now-button-color'
                ),
            ),
        ),

        // product_cart_buy_now_background_color
        'product_cart_buy_now_background_color_et-desktop' => array(
            'name' => 'product_cart_buy_now_background_color_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_buy_now_background_color_et-desktop',
            'label' => esc_html__('Background color', 'xstore-core'),
            'tooltip' => $strings['description']['bg_color'],
            'section' => 'product_cart_form',
            'default' => '#339438',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting' => 'buy_now_btn',
                    'operator' => '==',
                    'value' => 1,
                ),
            ),
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-buy-now-button-background-color'
                ),
            ),
        ),

        // product_cart_buy_now_color_hover
        'product_cart_buy_now_color_hover_et-desktop' => array(
            'name' => 'product_cart_buy_now_color_hover_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_buy_now_color_hover_et-desktop',
            'label' => esc_html__('Color (hover)', 'xstore-core'),
            'tooltip'     => esc_html__( 'Choose the color for the "Buy Now" button when hovering over it.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => '#ffffff',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting' => 'buy_now_btn',
                    'operator' => '==',
                    'value' => 1,
                ),
            ),
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-buy-now-button-color-hover',
                ),
            ),
        ),

        // product_cart_buy_now_background_color_hover
        'product_cart_buy_now_background_color_hover_et-desktop' => array(
            'name' => 'product_cart_buy_now_background_color_hover_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_buy_now_background_color_hover_et-desktop',
            'label' => esc_html__('Background color (hover)', 'xstore-core'),
            'tooltip' => $strings['description']['bg_color'],
            'section' => 'product_cart_form',
            'default' => '#2e7d32',
            'choices' => array(
                'alpha' => true,
            ),
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting' => 'buy_now_btn',
                    'operator' => '==',
                    'value' => 1,
                ),
            ),
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-buy-now-button-background-color-hover'
                ),
            ),
        ),

        // content separator
        'product_form_content_separator' => array(
            'name' => 'product_form_content_separator',
            'type' => 'custom',
            'settings' => 'product_form_content_separator',
            'section' => 'product_cart_form',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-feedback"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Cart form', 'xstore-core') . '</span></div>',
        ),

        // product_add_to_cart_spacing
        'product_add_to_cart_spacing_et-desktop' => array(
            'name' => 'product_add_to_cart_spacing_et-desktop',
            'type' => 'slider',
            'settings' => 'product_add_to_cart_spacing_et-desktop',
            'label' => $strings['label']['elements_spacing'],
            'tooltip'   => esc_html__('With this option, you can set the distance value between items (such as buttons, quantities, etc.) displayed in the shopping cart form.', 'xstore-core'),
            'section' => 'product_cart_form',
            'default' => 15,
            'choices' => array(
                'min' => '0',
                'max' => '30',
                'step' => '1',
            ),
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => 'body',
                    'property' => '--single-add-to-cart-button-spacing',
                    'units' => 'px'
                )
            )
        ),

        'product_cart_form_box_model_et-desktop' => array(
            'name' => 'product_cart_form_box_model_et-desktop',
            'settings' => 'product_cart_form_box_model_et-desktop',
            'label' => $strings['label']['computed_box'],
            'tooltip' => $strings['description']['computed_box'],
            'type' => 'kirki-box-model',
            'section' => 'product_cart_form',
            'default' => array(
                'margin-top' => '0px',
                'margin-right' => '0px',
                'margin-bottom' => '15px',
                'margin-left' => '0px',
                'border-top-width' => '0px',
                'border-right-width' => '0px',
                'border-bottom-width' => '0px',
                'border-left-width' => '0px',
                'padding-top' => '0px',
                'padding-right' => '0px',
                'padding-bottom' => '0px',
                'padding-left' => '0px',
            ),
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder form.cart'
                )
            ),
            'transport' => 'postMessage',
            'js_vars' => box_model_output('.single-product-builder form.cart')
        ),

        // product_cart_form_border
        'product_cart_form_border_et-desktop' => array(
            'name' => 'product_cart_form_border_et-desktop',
            'type' => 'select',
            'settings' => 'product_cart_form_border_et-desktop',
            'label' => $strings['label']['border_style'],
            'tooltip' => $strings['description']['border_style'],
            'section' => 'product_cart_form',
            'default' => 'solid',
            'choices' => $choices['border_style'],
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder form.cart',
                    'property' => 'border-style'
                )
            )
        ),

        // product_cart_form_border_color_custom
        'product_cart_form_border_color_custom_et-desktop' => array(
            'name' => 'product_cart_form_border_color_custom_et-desktop',
            'type' => 'color',
            'settings' => 'product_cart_form_border_color_custom_et-desktop',
            'label'       => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
            'section' => 'product_cart_form',
            'choices' => array(
                'alpha' => true
            ),
            'default' => '#e1e1e1',
            'transport' => 'auto',
            'output' => array(
                array(
                    'context' => array('editor', 'front'),
                    'element' => '.single-product-builder form.cart',
                    'property' => 'border-color',
                ),
            ),
        ),

        // advanced separator
        'product_cart_advanced_separator' => array(
            'name' => 'product_cart_advanced_separator',
            'type' => 'custom',
            'settings' => 'product_cart_advanced_separator',
            'section' => 'product_cart_form',
            'default' => $separators['advanced'],
        ),

        // stretch_add_to_cart
        'stretch_add_to_cart_et-desktop' => array(
            'name' => 'stretch_add_to_cart_et-desktop',
            'type' => 'toggle',
            'settings' => 'stretch_add_to_cart_et-desktop',
            'label' => esc_html__('Stretch "Add to Cart"', 'xstore-core'),
            'tooltip' => esc_html__('Enable this option to make the "Add to Cart" button expand to the full width of its parent.', 'xstore-core'),
            'section' => 'product_cart_form',
            'default' => 0,
        ),

        'ajax_add_to_cart' => array(
            'name' => 'ajax_add_to_cart',
            'type' => 'toggle',
            'settings' => 'ajax_add_to_cart',
            'label'       => esc_html__( 'Ajax add to cart', 'xstore-core' ),
            'tooltip' => esc_html__( 'Enable this option to use Ajax technology when adding products to the cart from the individual product page. Note: this option only works for simple and variable products, and adding these products to the cart will be done without refreshing the page. It is important to note that third-party plugins may conflict with this option, so it may be necessary to keep it disabled for better compatibility with some plugins.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => 1,
        ),

        // sticky_add_to_cart
        'sticky_add_to_cart_et-desktop' => array(
            'name' => 'sticky_add_to_cart_et-desktop',
            'type' => 'toggle',
            'settings' => 'sticky_add_to_cart_et-desktop',
            'label'     => esc_html__( 'Sticky cart', 'xstore-core' ),
            'tooltip' => esc_html__('If the product content is lengthy, users often find it difficult to click the "Add to Cart" button, as it is located at the top of the page. Enable this option if you wish to give your visitors the opportunity to purchase even when the page has been scrolled a lot, thus increasing your store\'s sales.', 'xstore-core'),
            'section' => 'product_cart_form',
            'default' => 0,
            'transport' => 'postMessage',
            'js_vars' => array(
                array(
                    'element' => '.etheme-sticky-cart',
                    'function' => 'toggleClass',
                    'class' => 'dt-hide',
                    'value' => false
                ),
                array(
                    'element' => '.etheme-sticky-cart',
                    'function' => 'toggleClass',
                    'class' => 'mob-hide',
                    'value' => false
                ),
            ),
        ),

        'sticky_added_to_cart_message' => array(
            'name' => 'sticky_added_to_cart_message',
            'type' => 'toggle',
            'settings' => 'sticky_added_to_cart_message',
            'label' => esc_html__('Fixed added to cart message', 'xstore-core'),
            'section' => 'product_cart_form',
            'default' => 1,
            'transport' => 'postMessage',
            'js_vars' => array(
                array(
                    'element' => 'body.single-product',
                    'function' => 'toggleClass',
                    'class' => 'sticky-message-on',
                    'value' => true
                ),
                array(
                    'element' => 'body.single-product',
                    'function' => 'toggleClass',
                    'class' => 'sticky-message-off',
                    'value' => false
                ),
            ),
        ),

        // show single stock
        'show_single_stock' => array(
            'name' => 'show_single_stock',
            'type' => 'toggle',
            'settings' => 'show_single_stock',
            'label'    => esc_html__( 'Stock status', 'xstore-core' ),
            'tooltip' => esc_html__( 'Enable this option to force the display of the stock status of the product on its individual page.', 'xstore-core' ),
            'section' => 'product_cart_form',
            'default' => 0,
        ),

        // before_adc_separator separator
        'before_adc_separator' => array(
            'name' => 'before_adc_separator',
            'type' => 'custom',
            'settings' => 'before_adc_separator',
            'section' => 'product_cart_form',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-insert-before"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Before cart form', 'xstore-core') . '</span></div>',
        ),

        // before_adc_html_block
        'before_adc_html_block'                   => array(
            'name'            => 'before_adc_html_block',
            'type'            => 'editor',
            'settings'        => 'before_adc_html_block',
            'label'           => esc_html__( 'Content', 'xstore-core' ),
            'tooltip'     => $strings['description']['editor_control'],
            'section'         => 'product_cart_form',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'before_adc_html_block_sections',
                    'operator' => '!=',
                    'value'    => '1',
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'before_adc_html_block' => array(
                    'selector'        => '.etheme-before-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'html_backup' => 'before_adc_html_block',
                        ) );
                    },
                ),
            ),
        ),

        // before_adc_html_block_sections
        'before_adc_html_block_sections'          => array(
            'name'            => 'before_adc_html_block_sections',
            'type'            => 'toggle',
            'settings'        => 'before_adc_html_block_sections',
            'label'           => $strings['label']['use_static_block'],
            'tooltip'         => $strings['description']['use_static_block'],
            'section'         => 'product_cart_form',
            'default'         => 0,
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'before_adc_html_block_sections' => array(
                    'selector'        => '.etheme-before-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'section'         => 'before_adc_html_block_section',
                            'sections'        => 'before_adc_html_block_sections',
                            'html_backup'     => 'before_adc_html_block',
                            'section_content' => true
                        ) );
                    },
                ),
            ),
        ),

        // before_adc_html_block_section
        'before_adc_html_block_section'           => array(
            'name'            => 'before_adc_html_block_section',
            'type'            => 'select',
            'settings'        => 'before_adc_html_block_section',
//			'label'           => sprintf( esc_html__( 'Choose %1s for Html Block 1', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
            'section'         => 'product_cart_form',
            'default'         => '',
            'choices'         => $sections,
            'active_callback' => array(
                array(
                    'setting'  => 'before_adc_html_block_sections',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'before_adc_html_block_section' => array(
                    'selector'        => '.etheme-before-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'section'         => 'before_adc_html_block_section',
                            'sections'        => 'before_adc_html_block_sections',
                            'html_backup'     => 'before_adc_html_block',
                            'section_content' => true
                        ) );
                    },
                ),
            ),
        ),

        'after_adc_separator' => array(
            'name' => 'after_adc_separator',
            'type' => 'custom',
            'settings' => 'after_adc_separator',
            'section' => 'product_cart_form',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-insert-after"></span> <span style="padding-inline-start: 5px;">' . esc_html__('After cart form', 'xstore-core') . '</span></div>',
        ),
        // after_adc_html_block
        'after_adc_html_block'                   => array(
            'name'            => 'after_adc_html_block',
            'type'            => 'editor',
            'settings'        => 'after_adc_html_block',
            'label'           => esc_html__( 'Content', 'xstore-core' ),
            'tooltip'     => $strings['description']['editor_control'],
            'section'         => 'product_cart_form',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'after_adc_html_block_sections',
                    'operator' => '!=',
                    'value'    => '1',
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'after_adc_html_block' => array(
                    'selector'        => '.etheme-after-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'html_backup' => 'after_adc_html_block',
                        ) );
                    },
                ),
            ),
        ),

        // after_adc_html_block_sections
        'after_adc_html_block_sections'          => array(
            'name'            => 'after_adc_html_block_sections',
            'type'            => 'toggle',
            'settings'        => 'after_adc_html_block_sections',
            'label'           => $strings['label']['use_static_block'],
            'tooltip'         => $strings['description']['use_static_block'],
            'section'         => 'product_cart_form',
            'default'         => 0,
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'after_adc_html_block_sections' => array(
                    'selector'        => '.etheme-after-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'section'         => 'after_adc_html_block_section',
                            'sections'        => 'after_adc_html_block_sections',
                            'html_backup'     => 'after_adc_html_block',
                            'section_content' => true
                        ) );
                    },
                ),
            ),
        ),

        // after_adc_html_block_section
        'after_adc_html_block_section'           => array(
            'name'            => 'after_adc_html_block_section',
            'type'            => 'select',
            'settings'        => 'after_adc_html_block_section',
//			'label'           => sprintf( esc_html__( 'Choose %1s for Html Block 1', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
            'section'         => 'product_cart_form',
            'default'         => '',
            'choices'         => $sections,
            'active_callback' => array(
                array(
                    'setting'  => 'after_adc_html_block_sections',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'after_adc_html_block_section' => array(
                    'selector'        => '.etheme-after-adc-content',
                    'render_callback' => function () {
                        return html_blocks_callback( array(
                            'section'         => 'after_adc_html_block_section',
                            'sections'        => 'after_adc_html_block_sections',
                            'html_backup'     => 'after_adc_html_block',
                            'section_content' => true
                        ) );
                    },
                ),
            ),
        ),

    );

    return array_merge($fields, $args);

});
