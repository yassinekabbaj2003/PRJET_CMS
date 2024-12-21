<?php
/**
 * The template created for displaying waitlist options
 *
 * @version 1.0.0
 * @since   5.1.9
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'xstore-waitlist' => array(
			'name'       => 'xstore-waitlist',
			'title'      => esc_html__( 'Waitlist', 'xstore-core' ),
			'description' => sprintf(esc_html__('With our Waitlist feature, customers can sign up for a mailing list of out-of-stock or unavailable items they are interested in and will automatically receive notifications when these items become available in your store, and share them with friends and family. This feature not only enhances the shopping experience but also increases the likelihood of customers returning to your site to make a purchase. For proper setup of this functionality, we recommend that you check the %1s.', 'xstore-core'),
            ' <a href="'.etheme_documentation_url('215-waitlist-feature', false).'" rel="nofollow" target="_blank">' . esc_html__( 'Waitlist documentation', 'xstore-core' ) . '</a>'),
			'panel'      => 'woocommerce',
			'icon'       => 'dashicons-bell',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/xstore-waitlist', function ( $fields ) use ( $separators, $sep_style, $strings, $choices, $box_models ) {
    
    $select_pages = et_b_get_posts(
        array(
            'post_per_page' => -1,
            'nopaging'      => true,
            'post_type'     => 'page',
            'with_select_page' => true
        )
    );

    $select_pages[0] = esc_html__('Dynamic page', 'xstore-core');

    $sections = et_b_get_posts(
        array(
            'post_per_page' => -1,
            'nopaging'      => true,
            'post_type'     => 'staticblocks',
            'with_none' => true
        )
    );

    $is_spb = get_option( 'etheme_single_product_builder', false );

	$args = array();
	
	// Array of fields
	$args = array(

        // setting was moved to Sales Booster but can be activated here too
//		'xstore_waitlist' => array(
//			'name'     => 'xstore_waitlist',
//			'type'     => 'toggle',
//			'settings' => 'xstore_waitlist',
//			'label'    => __( 'Enable Waitlist', 'xstore-core' ),
//			'tooltip' => __('By enabling this option, your customers can easily sign up for a mailing list of out-of-stock or unavailable items they are interested in. Don\'t miss out on the opportunity to offer a personalized shopping experience that keeps your customers coming back for more. Enable the waitlist feature today!', 'xstore-core'),
//			'section'  => 'xstore-waitlist',
//			'default'  => '0',
//		),

        // product_waitlist_icon
        'xstore_waitlist_icon'                    => array(
            'name'     => 'xstore_waitlist_icon',
            'type'     => 'radio-image',
            'settings' => 'xstore_waitlist_icon',
            'label'    => $strings['label']['icon'],
            'tooltip'     => $strings['description']['icon'],
            'section'  => 'xstore-waitlist',
            'default'  => 'type1',
            'choices'  => array(
                'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-1.svg',
                'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/waitlist/Waitlist-2.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
                'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
            ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // cart_icon_custom_svg
        'xstore_waitlist_icon_custom_svg' => array(
            'name'            => 'xstore_waitlist_icon_custom_svg',
            'type'            => 'image',
            'settings'        => 'xstore_waitlist_icon_custom_svg',
            'label'           => $strings['label']['custom_image_svg'],
            'tooltip'     => $strings['description']['custom_image_svg'],
            'section'  => 'xstore-waitlist',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
                array(
                    'setting'  => 'xstore_waitlist_icon',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // xstore_waitlist_label_add_to_waitlist
        'xstore_waitlist_label_add_to_waitlist'              => array(
            'name'     => 'xstore_waitlist_label_add_to_waitlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_waitlist_label_add_to_waitlist',
            'label'    => esc_html__( '"Add to waitlist" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the waitlist action, with the default value being "Add to waitlist".', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'  => esc_html__( 'Notify when available', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_label_browse_waitlist
//        'xstore_waitlist_label_browse_waitlist'              => array(
//            'name'     => 'xstore_waitlist_label_browse_waitlist',
//            'type'     => 'etheme-text',
//            'settings' => 'xstore_waitlist_label_browse_waitlist',
//            'label'    => esc_html__( '"Remove from waitlist" text', 'xstore-core' ),
//            'tooltip'  => esc_html__( 'Customize the title text for removing a product from the waitlist action, with the default value being "Remove from waitlist".', 'xstore-core'),
//            'section'  => 'xstore-waitlist',
//            'default'  => esc_html__( 'Remove from Waitlist', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // xstore_waitlist_popup_label_add_to_waitlist
        'xstore_waitlist_popup_intro_add_to_waitlist'              => array(
            'name'     => 'xstore_waitlist_popup_intro_add_to_waitlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_waitlist_popup_intro_add_to_waitlist',
            'label'    => esc_html__( '"Product unavailable" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text in popup for adding a product to the waitlist action, with the default value being "This product is currently unavailable".', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'  => esc_html__( 'This product is currently unavailable', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_popup_checkbox_label_add_to_waitlist
        'xstore_waitlist_popup_checkbox_label_add_to_waitlist'              => array(
            'name'     => 'xstore_waitlist_popup_checkbox_label_add_to_waitlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_waitlist_popup_checkbox_label_add_to_waitlist',
            'label'    => esc_html__( 'Consent text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the consent title text in popup for adding a product to the waitlist action, with the default value being "I consent to being contacted by the store owner".', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'  => esc_html__( 'I consent to being contacted by the store owner', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_popup_label_add_to_waitlist
        'xstore_waitlist_popup_label_add_to_waitlist'              => array(
            'name'     => 'xstore_waitlist_popup_label_add_to_waitlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_waitlist_popup_label_add_to_waitlist',
            'label'    => esc_html__( '"Join waiting list" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text in popup for adding a product to the waitlist action, with the default value being "Join waiting list".', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'  => esc_html__( 'Join waiting list', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_popup_intro_remove_waitlist
        'xstore_waitlist_popup_intro_remove_waitlist'              => array(
            'name'     => 'xstore_waitlist_popup_intro_remove_waitlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_waitlist_popup_intro_remove_waitlist',
            'label'    => esc_html__( '"Email removal" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text in popup for removing a product from the waitlist action, with the default value being "Leaving the Waitlist? Confirm Email Removal".', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'  => esc_html__( 'Leaving the Waitlist? Confirm Email Removal', 'xstore-core' ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        'xstore_waitlist_notify_type' => array(
            'name'            => 'xstore_waitlist_notify_type',
            'type'            => 'select',
            'settings'        => 'xstore_waitlist_notify_type',
            'label'           => esc_html__( 'Product added notification', 'xstore-core' ),
            'tooltip' => esc_html__( 'Choose the type of notification that will be displayed when the product is added to the waitlist.', 'xstore-core' ),
            'section'  => 'xstore-waitlist',
            'default'         => 'alert_advanced',
            'choices'         => array(
                'none'      => esc_html__( 'None', 'xstore-core' ),
                'alert'     => esc_html__( 'Alert', 'xstore-core' ),
                'alert_advanced'     => esc_html__( 'Alert advanced', 'xstore-core' ),
//                'mini_waitlist' => esc_html__( 'Open waitlist Off-canvas/dropdown content', 'xstore-core' ), // uncomment when header element will be added
            ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

//        uncomment when waitlist page will be added to show
//        'xstore_waitlist_cache_time' => array(
//            'name'            => 'xstore_waitlist_cache_time',
//            'type'            => 'select',
//            'settings'        => 'xstore_waitlist_cache_time',
//            'label'           => esc_html__( 'Cache lifespan', 'xstore-core' ),
//            'tooltip' => esc_html__( 'Specify the time after which the customer waitlist items cache will be cleared. Note: the customer waitlist items will be kept in the cache for the time you set in this option or until the browser cookies are cleared. This will add an additional cookie to the customer\'s browser with the following parameters: name: "xstore_waitlist_ids_0", purpose: "To store Waitlist product information", expiry: "7 days by default".', 'xstore-core' ) . '<br/>' .
//                esc_html__('Note: Please remember to include this in the security policy (GDPR).', 'xstore-core'),
//            'section'  => 'xstore-waitlist',
//            'default'         => 'week',
//            'choices'         => array(
//                'day' => esc_html__('One day', 'xstore-core'),
//                'week' => esc_html__('One week', 'xstore-core'),
//                'month' => esc_html__('One month', 'xstore-core'),
//                '3months' => esc_html__('Three months', 'xstore-core'),
//                'year' => esc_html__('One year', 'xstore-core'),
//            ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // go to product header waitlist
//        'go_to_section_header_waitlist'                 => array(
//            'name'     => 'go_to_section_header_waitlist',
//            'type'     => 'custom',
//            'settings' => 'go_to_section_header_waitlist',
//            'section'  => 'xstore-waitlist',
//            'default'  => '<span class="et_edit" data-parent="waitlist" data-section="waitlist_content_separator" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Header Waitlist', 'xstore-core' ) . '</span>',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // go to product single product waitlist
        'go_to_section_product_waitlist'                 => array(
            'name'     => 'go_to_section_product_waitlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_product_waitlist',
            'section'  => 'xstore-waitlist',
            'default'  => '<span class="et_edit" data-parent="'.($is_spb ? 'product_waitlist' : 'single-product-page-waitlist').'" data-section="'.($is_spb ? 'product_waitlist_content_separator' : 'xstore_waitlist_single_product_position').'" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Single Product Waitlist', 'xstore-core' ) . '</span>',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // content separator
//        'xstore_waitlist_page_content_separator' => array(
//            'name'     => 'xstore_waitlist_page_content_separator',
//            'type'     => 'custom',
//            'settings' => 'xstore_waitlist_page_content_separator',
//            'section'  => 'xstore-waitlist',
//            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-heart"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Waitlist page settings', 'xstore-core') . '</span></div>',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),
//
//        'xstore_waitlist_page' => array(
//            'name'            => 'xstore_waitlist_page',
//            'type'            => 'select',
//            'settings'        => 'xstore_waitlist_page',
//            'label'           => esc_html__( 'Waitlist page', 'xstore-core' ),
//            'tooltip'     => esc_html__( 'Choose a page to be the main Waitlist page. Make sure to add the [xstore_waitlist_page] shortcode to the page content.', 'xstore-core') . '<br/>' .
//            esc_html__('Choose the "Dynamic page" option to create a waitlist page based on the "Account" page link, with a few extra query parameters added to the URL.', 'xstore-core'),
//            'section'  => 'xstore-waitlist',
//            'default'         => '',
//            'choices'         => $select_pages,
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),
//
//        'xstore_waitlist_page_content' => array(
//            'name'            => 'xstore_waitlist_page_content',
//            'type'            => 'sortable',
//            'settings'        => 'xstore_waitlist_page_content',
//            'label'           => esc_html__( 'Table content', 'xstore-core' ),
//            'tooltip'     => esc_html__( 'Revamp the contents of the waitlist page easily by turning on or off the necessary elements.', 'xstore-core' ),
//            'section'  => 'xstore-waitlist',
//            'default'         => array(
//                'product',
//                'email',
//                'price',
//                'action'
//            ),
//            'choices'         => array(
//                'product' => esc_html__('Product', 'xstore-core'),
//                'email' => esc_html__('Email', 'xstore-core'),
//                'price' => esc_html__('Price', 'xstore-core'),
//                'action' => esc_html__('Actions', 'xstore-core'),
//            ),
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),
//
//        'xstore_waitlist_empty_page_content' => array(
//            'name'        => 'xstore_waitlist_empty_page_content',
//            'type'        => 'editor',
//            'settings'    => 'xstore_waitlist_empty_page_content',
//            'label'       => esc_html__( 'Empty waitlist content', 'xstore-core' ),
//            'tooltip'     => $strings['description']['editor_control'] . '<br/>' .
//                esc_html__('Leave the content blank to use the default content.', 'xstore-core'),
//            'section'  => 'xstore-waitlist',
//            'default'     => '<h1 style="text-align: center;">Your waitlist is empty</h1><p style="text-align: center;">We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!</p> ',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
//        ),

        // advanced separator
        'xstore_waitlist_advanced_separator'                 => array(
            'name'     => 'xstore_waitlist_advanced_separator',
            'type'     => 'custom',
            'settings' => 'xstore_waitlist_advanced_separator',
            'section'  => 'xstore-waitlist',
            'default'  => $separators['advanced'],
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_customer_email
        'xstore_waitlist_customer_email'                  => array(
            'name'            => 'xstore_waitlist_customer_email',
            'type'            => 'toggle',
            'settings'        => 'xstore_waitlist_customer_email',
            'label'           => esc_html__('Email for customer', 'xstore-core'),
            'tooltip'         => esc_html__('Send email for customer once he left own email for "Product Availability" notification.', 'xstore-core'),
            'section'         => 'xstore-waitlist',
            'default'         => 1,
            'transport'       => 'postMessage',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_product_in_stock_customer_email
        'xstore_waitlist_product_in_stock_customer_email'                  => array(
            'name'            => 'xstore_waitlist_product_in_stock_customer_email',
            'type'            => 'toggle',
            'settings'        => 'xstore_waitlist_product_in_stock_customer_email',
            'label'           => esc_html__('Auto-Email for customer when product is "in stock"', 'xstore-core'),
            'tooltip'         => esc_html__('Send email for all customers who left their email for "product in stock" notification.', 'xstore-core'),
            'section'         => 'xstore-waitlist',
            'default'         => 1,
            'transport'       => 'postMessage',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

        // xstore_waitlist_admin_email
        'xstore_waitlist_admin_email'                  => array(
            'name'            => 'xstore_waitlist_admin_email',
            'type'            => 'toggle',
            'settings'        => 'xstore_waitlist_admin_email',
            'label'           => esc_html__('Email for admin', 'xstore-core'),
            'tooltip'         => esc_html__('Send email to admin once customer left email for "product in stock" notification.', 'xstore-core'),
            'section'         => 'xstore-waitlist',
            'default'         => 1,
            'transport'       => 'postMessage',
//            'active_callback' => array(
//                array(
//                    'setting'  => 'xstore_waitlist',
//                    'operator' => '==',
//                    'value'    => true,
//                ),
//            )
        ),

    );
	
	return array_merge( $fields, $args );
	
} );