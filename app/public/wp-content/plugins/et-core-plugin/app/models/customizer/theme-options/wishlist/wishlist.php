<?php
/**
 * The template created for displaying built-in wishlist options
 *
 * @version 1.0.0
 * @since   4.3.8
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'xstore-wishlist' => array(
			'name'       => 'xstore-wishlist',
			'title'      => esc_html__( 'Wishlist', 'xstore-core' ),
			'description' => sprintf(esc_html__('With our Wishlist feature, customers can create lists of items they love, save them for later, and share them with friends and family. This feature not only enhances the shopping experience but also increases the likelihood of customers returning to your site to make a purchase. For proper setup of this functionality, we recommend that you watch the %1s.', 'xstore-core'),
            ' <a href="https://www.youtube.com/watch?v=ZyjZ-vCx0pE" rel="nofollow" target="_blank">' . esc_html__( 'video tutorial', 'xstore-core' ) . '</a>'),
			'panel'      => 'woocommerce',
			'icon'       => 'dashicons-heart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/xstore-wishlist', function ( $fields ) use ( $separators, $sep_style, $strings, $choices, $box_models ) {
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

    $estimate_popup_content_default =
        esc_html__( 'You may add any content here from Customizer -> WooCommerce -> Wishlist -> Popup content.', 'xstore-core');

	$args = array();
	
	// Array of fields
	$args = array(

		'xstore_wishlist' => array(
			'name'     => 'xstore_wishlist',
			'type'     => 'toggle',
			'settings' => 'xstore_wishlist',
			'label'    => __( 'Enable Wishlist', 'xstore-core' ),
			'tooltip' => __('By enabling this option, your customers can easily save and keep track of their favorite products, creating a seamless shopping experience. Don\'t miss out on the opportunity to offer a personalized shopping experience that keeps your customers coming back for more. Enable the built-in wishlist feature today!', 'xstore-core'),
			'section'  => 'xstore-wishlist',
			'default'  => '0',
		),

        // product_wishlist_icon
        'xstore_wishlist_icon'                    => array(
            'name'     => 'xstore_wishlist_icon',
            'type'     => 'radio-image',
            'settings' => 'xstore_wishlist_icon',
            'label'    => $strings['label']['icon'],
            'tooltip'     => $strings['description']['icon'],
            'section'  => 'xstore-wishlist',
            'default'  => 'type1',
            'choices'  => array(
                'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/wishlist/Wishlist-1.svg',
                'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/wishlist/Wishlist-2.svg',
                'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
//                'none'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg'
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // cart_icon_custom_svg
        'xstore_wishlist_icon_custom_svg' => array(
            'name'            => 'xstore_wishlist_icon_custom_svg',
            'type'            => 'image',
            'settings'        => 'xstore_wishlist_icon_custom_svg',
            'label'           => $strings['label']['custom_image_svg'],
            'tooltip'     => $strings['description']['custom_image_svg'],
            'section'  => 'xstore-wishlist',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_icon',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
        ),

        // xstore_wishlist_label_add_to_wishlist
        'xstore_wishlist_label_add_to_wishlist'              => array(
            'name'     => 'xstore_wishlist_label_add_to_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_wishlist_label_add_to_wishlist',
            'label'    => esc_html__( '"Add to wishlist" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text for adding a product to the wishlist action, with the default value being "Add to wishlist".', 'xstore-core' ),
            'section'  => 'xstore-wishlist',
            'default'  => esc_html__( 'Add to wishlist', 'xstore-core' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // xstore_wishlist_label_browse_wishlist
        'xstore_wishlist_label_browse_wishlist'              => array(
            'name'     => 'xstore_wishlist_label_browse_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'xstore_wishlist_label_browse_wishlist',
            'label'    => esc_html__( '"Remove from wishlist" text', 'xstore-core' ),
            'tooltip'  => esc_html__( 'Customize the title text for removing a product from the wishlist action, with the default value being "Remove from wishlist".', 'xstore-core'),
            'section'  => 'xstore-wishlist',
            'default'  => esc_html__( 'Remove from wishlist', 'xstore-core' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_notify_type' => array(
            'name'            => 'xstore_wishlist_notify_type',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_notify_type',
            'label'           => esc_html__( 'Product added notification', 'xstore-core' ),
            'tooltip' => esc_html__( 'Choose the type of notification that will be displayed when the product is added to the wishlist.', 'xstore-core' ),
            'section'  => 'xstore-wishlist',
            'default'         => 'alert',
            'choices'         => array(
                'none'      => esc_html__( 'None', 'xstore-core' ),
                'alert'     => esc_html__( 'Alert', 'xstore-core' ),
                'alert_advanced'     => esc_html__( 'Alert advanced', 'xstore-core' ),
                'mini_wishlist' => esc_html__( 'Open wishlist Off-canvas/dropdown content', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_animated_hearts' => array(
            'name'     => 'xstore_wishlist_animated_hearts',
            'type'     => 'toggle',
            'settings' => 'xstore_wishlist_animated_hearts',
            'label'    => __( 'Animated hearts', 'xstore-core' ),
            'tooltip' => esc_html__('Enable this option to display a nice heart animation on each product wishlist element when adding a product to the wishlist. Give this option a try and see the animation effects for yourself; we are sure you will enjoy it.', 'xstore-core'),
            'section'  => 'xstore-wishlist',
            'default'  => 1,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_cache_time' => array(
            'name'            => 'xstore_wishlist_cache_time',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_cache_time',
            'label'           => esc_html__( 'Cache lifespan', 'xstore-core' ),
            'tooltip' => esc_html__( 'Specify the time after which the customer wishlist items cache will be cleared. Note: the customer wishlist items will be kept in the cache for the time you set in this option or until the browser cookies are cleared. This will add an additional cookie to the customer\'s browser with the following parameters: name: "xstore_wishlist_ids_0", purpose: "To store Wishlist product information", expiry: "7 days by default".', 'xstore-core' ) . '<br/>' .
                esc_html__('Note: Please remember to include this in the security policy (GDPR).', 'xstore-core'),
            'section'  => 'xstore-wishlist',
            'default'         => 'week',
            'choices'         => array(
                'day' => esc_html__('One day', 'xstore-core'),
                'week' => esc_html__('One week', 'xstore-core'),
                'month' => esc_html__('One month', 'xstore-core'),
                '3months' => esc_html__('Three months', 'xstore-core'),
                'year' => esc_html__('One year', 'xstore-core'),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product header wishlist
        'go_to_section_header_wishlist'                 => array(
            'name'     => 'go_to_section_header_wishlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_header_wishlist',
            'section'  => 'xstore-wishlist',
            'default'  => '<span class="et_edit" data-parent="wishlist" data-section="wishlist_content_separator" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Header Wishlist', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product wishlist
        'go_to_section_product_wishlist'                 => array(
            'name'     => 'go_to_section_product_wishlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_product_wishlist',
            'section'  => 'xstore-wishlist',
            'default'  => '<span class="et_edit" data-parent="'.($is_spb ? 'product_wishlist' : 'single-product-page-wishlist').'" data-section="'.($is_spb ? 'product_wishlist_content_separator' : 'xstore_wishlist_single_product_position').'" style="padding: 6px 7px; border-radius: 5px; background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); font-size: calc(var(--customizer-ui-content-zoom, 1) * 12px); text-align: center;">' . esc_html__( 'Single Product Wishlist', 'xstore-core' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // content separator
        'xstore_wishlist_page_content_separator' => array(
            'name'     => 'xstore_wishlist_page_content_separator',
            'type'     => 'custom',
            'settings' => 'xstore_wishlist_page_content_separator',
            'section'  => 'xstore-wishlist',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-heart"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Wishlist page settings', 'xstore-core') . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_page' => array(
            'name'            => 'xstore_wishlist_page',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_page',
            'label'           => esc_html__( 'Wishlist page', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Choose a page to be the main Wishlist page. Make sure to add the [xstore_wishlist_page] shortcode to the page content.', 'xstore-core') . '<br/>' .
            esc_html__('Choose the "Dynamic page" option to create a wishlist page based on the "Account" page link, with a few extra query parameters added to the URL.', 'xstore-core'),
            'section'  => 'xstore-wishlist',
            'default'         => '',
            'choices'         => $select_pages,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_page_content' => array(
            'name'            => 'xstore_wishlist_page_content',
            'type'            => 'sortable',
            'settings'        => 'xstore_wishlist_page_content',
            'label'           => esc_html__( 'Table content', 'xstore-core' ),
            'tooltip'     => esc_html__( 'Revamp the contents of the wishlist page easily by turning on or off the necessary elements.', 'xstore-core' ),
            'section'  => 'xstore-wishlist',
            'default'         => array(
                'product',
                'quantity',
                'price',
                'stock_status',
                'action'
            ),
            'choices'         => array(
                'product' => esc_html__('Product', 'xstore-core'),
                'quantity' => esc_html__('Quantity', 'xstore-core'),
                'price' => esc_html__('Price', 'xstore-core'),
                'stock_status' => esc_html__('Stock status', 'xstore-core'),
                'action' => esc_html__('Actions', 'xstore-core'),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'xstore_wishlist_empty_page_content' => array(
            'name'        => 'xstore_wishlist_empty_page_content',
            'type'        => 'editor',
            'settings'    => 'xstore_wishlist_empty_page_content',
            'label'       => esc_html__( 'Empty wishlist content', 'xstore-core' ),
            'tooltip'     => $strings['description']['editor_control'] . '<br/>' .
                esc_html__('Leave the content blank to use the default content.', 'xstore-core'),
            'section'  => 'xstore-wishlist',
            'default'     => '<h1 style="text-align: center;">Your wishlist is empty</h1><p style="text-align: center;">We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!</p> ',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // content separator
        'xstore_wishlist_ask_estimate_content_separator' => array(
            'name'     => 'xstore_wishlist_ask_estimate_content_separator',
            'type'     => 'custom',
            'settings' => 'xstore_wishlist_ask_estimate_content_separator',
            'section'  => 'xstore-wishlist',
            'default' => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-help"></span> <span style="padding-inline-start: 5px;">' . esc_html__('Ask estimate settings', 'xstore-core') . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // request_quote_button_text
        'xstore_wishlist_ask_estimate_button_text'                  => array(
            'name'      => 'xstore_wishlist_ask_estimate_button_text',
            'type'      => 'etheme-text',
            'settings'  => 'xstore_wishlist_ask_estimate_button_text',
            'label'     => $strings['label']['button_text'],
            'tooltip'   => $strings['description']['button_text'],
            'section'   => 'xstore-wishlist',
            'default'   => esc_html__( 'Ask for an estimate', 'xstore-core' ),
            'transport' => 'postMessage',
            'js_vars'   => array(
                array(
                    'element'  => '.et-call-popup[data-type="ask-wishlist-estimate"]',
                    'function' => 'html',
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // xstore_wishlist_ask_estimate_popup_content
        'xstore_wishlist_ask_estimate_popup_content'                     => array(
            'name'            => 'xstore_wishlist_ask_estimate_popup_content',
            'type'            => 'editor',
            'settings'        => 'xstore_wishlist_ask_estimate_popup_content',
            'label'           => esc_html__( 'Content', 'xstore-core' ),
            'tooltip'     => $strings['description']['editor_control'],
            'section'         => 'xstore-wishlist',
            'default' => $estimate_popup_content_default,
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'xstore_wishlist_ask_estimate_popup_content' => array(
                    'selector'        => '.ask-wishlist-estimate-popup .et-popup-content',
                    'render_callback' => function($default_content) use ($estimate_popup_content_default) {
                        $popup_content = get_theme_mod( 'xstore_wishlist_ask_estimate_popup_content', $estimate_popup_content_default );
                        ob_start(); ?>
                        <span class="et-close-popup et-toggle pos-fixed full-left top"
                              style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;">
                              <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                                <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                              </svg>
                            </span>
                        <?php if ( $popup_content != '' ) {
                            echo do_shortcode( $popup_content );
                        } else { ?>
                            <h2><?php echo $estimate_popup_content_default; ?></h2>
                            <p>At sem a enim eu vulputate nullam convallis Iaculis vitae odio faucibus adipiscing urna.</p>
                        <?php } ?>
                        <?php return ob_get_clean();
                    },
                ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            )
        ),

        // xstore_wishlist_ask_estimate_popup_content_sections
        'xstore_wishlist_ask_estimate_popup_content_sections'          => array(
            'name'            => 'xstore_wishlist_ask_estimate_popup_content_sections',
            'type'            => 'toggle',
            'settings'        => 'xstore_wishlist_ask_estimate_popup_content_sections',
            'label'           => $strings['label']['use_static_block'],
            'tooltip'         => $strings['description']['use_static_block'],
            'section'  => 'xstore-wishlist',
            'default'         => 0,
            'transport'       => 'postMessage',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            ),
            'partial_refresh' => array(
                'xstore_wishlist_ask_estimate_popup_content_sections' => array(
                    'selector'        => '.ask-wishlist-estimate-popup .et-popup-content',
                    'render_callback' => function($default_content) use ($estimate_popup_content_default) {
                        $arrow_margin = 'margin-' . (is_rtl() ? 'right' : 'left');
                        $close_arrow = '<span class="et-close-popup et-toggle pos-fixed full-left top"
                              style="'.$arrow_margin.': 5px;">
                              <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                                <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                              </svg>
                            </span>';
                        return $close_arrow . html_blocks_callback( array(
                            'section'         => 'xstore_wishlist_ask_estimate_popup_content_section',
                            'sections'        => 'xstore_wishlist_ask_estimate_popup_content_sections',
                            'html_backup'     => 'xstore_wishlist_ask_estimate_popup_content',
                            'html_backup_default' => $estimate_popup_content_default,
                            'section_content' => true
                        ) );
                    },
                ),
            ),
        ),

        // xstore_wishlist_ask_estimate_popup_content_section
        'xstore_wishlist_ask_estimate_popup_content_section'           => array(
            'name'            => 'xstore_wishlist_ask_estimate_popup_content_section',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_ask_estimate_popup_content_section',
//            'label'           => sprintf( esc_html__( 'Choose %1s for Popup content', 'xstore-core' ), '<a href="'.etheme_documentation_url('47-static-blocks', false).'" target="_blank" style="color: var(--customizer-dark-color, #555)">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
            'label'           => $strings['label']['choose_static_block'],
            'tooltip'         => $strings['description']['choose_static_block'],
            'section'  => 'xstore-wishlist',
            'default'         => '',
            'priority'        => 10,
            'choices'         => $sections,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_popup_content_sections',
                    'operator' => '==',
                    'value'    => 1,
                ),
            ),
            'transport'       => 'postMessage',
            'partial_refresh' => array(
                'xstore_wishlist_ask_estimate_popup_content_section' => array(
                    'selector'        => '.ask-wishlist-estimate-popup .et-popup-content',
                    'render_callback' => function($default_content) use ($estimate_popup_content_default) {
                        $arrow_margin = 'margin-' . (is_rtl() ? 'right' : 'left');
                        $close_arrow = '<span class="et-close-popup et-toggle pos-fixed full-left top"
                              style="'.$arrow_margin.': 5px;">
                              <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                                <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                              </svg>
                            </span>';
                        return $close_arrow . html_blocks_callback( array(
                                'section'         => 'xstore_wishlist_ask_estimate_popup_content_section',
                                'sections'        => 'xstore_wishlist_ask_estimate_popup_content_sections',
                                'html_backup'     => 'xstore_wishlist_ask_estimate_popup_content',
                                'html_backup_default' => $estimate_popup_content_default,
                                'section_content' => true
                            ) );
                    },
                ),
            ),
        ),
        
        // style separator
        'xstore_wishlist_ask_estimate_popup_style_separator'                        => array(
            'name'     => 'xstore_wishlist_ask_estimate_popup_style_separator',
            'type'     => 'custom',
            'settings' => 'xstore_wishlist_ask_estimate_popup_style_separator',
            'section'  => 'xstore-wishlist',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-external"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Popup style', 'xstore-core' ) . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            )
        ),

        // xstore_wishlist_ask_estimate_popup_content_width_height
        'xstore_wishlist_ask_estimate_popup_content_width_height_et-desktop'        => array(
            'name'      => 'xstore_wishlist_ask_estimate_popup_content_width_height_et-desktop',
            'type'      => 'radio-buttonset',
            'settings'  => 'xstore_wishlist_ask_estimate_popup_content_width_height_et-desktop',
            'label'     => esc_html__( 'Dimensions', 'xstore-core' ),
            'tooltip'   => esc_html__( 'Choose which dimensions should be applied for the "Ask estimate" popup. Select "Custom" to configure the custom dimensions in the form below.', 'xstore-core' ),
            'section'   => 'xstore-wishlist',
            'default'   => 'auto',
            'multiple'  => 1,
            'choices'   => array(
                'auto'   => esc_html__( 'Auto', 'xstore-core' ),
                'custom' => esc_html__( 'Custom', 'xstore-core' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            )
        ),

        // xstore_wishlist_ask_estimate_popup_content_width_height_custom
        'xstore_wishlist_ask_estimate_popup_content_width_height_custom_et-desktop' => array(
            'name'      => 'xstore_wishlist_ask_estimate_popup_content_width_height_custom_et-desktop',
            'type'      => 'dimensions',
            'settings'  => 'xstore_wishlist_ask_estimate_popup_content_width_height_custom_et-desktop',
            'label'     => esc_html__( 'Custom dimensions', 'xstore-core' ),
            'tooltip'   => esc_html__( 'Configure the dimensions for the "Ask estimate" popup.', 'xstore-core' ),
            'section'   => 'xstore-wishlist',
            'default'   => array(
                'width'  => '550px',
                'height' => '250px',
            ),
            'choices'   => array(
                'labels' => $strings['label']['popup_dimensions'],
                'descriptions' => $strings['description']['popup_dimensions'],
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_popup_content_width_height_et-desktop',
                    'operator' => '==',
                    'value'    => 'custom',
                ),
            ),
            'transport' => 'auto',
            'output'    => array(
                array(
                    'choice'   => 'width',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.ask-wishlist-estimate-popup .et-popup-content-custom-dimenstions',
                    'property' => 'width',
                ),
                array(
                    'choice'   => 'height',
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.ask-wishlist-estimate-popup .et-popup-content-custom-dimenstions',
                    'property' => 'height',
                )
            ),
        ),

        // xstore_wishlist_ask_estimate_popup_background
        'xstore_wishlist_ask_estimate_popup_background_et-desktop'                  => array(
            'name'        => 'xstore_wishlist_ask_estimate_popup_background_et-desktop',
            'type'        => 'background',
            'settings'    => 'xstore_wishlist_ask_estimate_popup_background_et-desktop',
            'label'       => $strings['label']['wcag_bg_color'],
            'tooltip' => $strings['description']['wcag_bg_color'],
            'section'     => 'xstore-wishlist',
            'default'     => array(
                'background-color'      => '#ffffff',
                'background-image'      => '',
                'background-repeat'     => 'no-repeat',
                'background-position'   => 'center center',
                'background-size'       => '',
                'background-attachment' => '',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.ask-wishlist-estimate-popup .et-popup-content',
                ),
            ),
        ),

        'xstore_wishlist_ask_estimate_popup_color_et-desktop' => array(
            'name'        => 'xstore_wishlist_ask_estimate_popup_color_et-desktop',
            'settings'    => 'xstore_wishlist_ask_estimate_popup_color_et-desktop',
            'label'       => $strings['label']['wcag_color'],
            'tooltip' => $strings['description']['wcag_color'],
            'type'        => 'kirki-wcag-tc',
            'section'     => 'xstore-wishlist',
            'default'     => '#000000',
            'choices'     => array(
                'setting' => 'setting(xstore-wishlist)(xstore_wishlist_ask_estimate_popup_background_et-desktop)[background-color]',
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
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            ),
            'transport'   => 'auto',
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.ask-wishlist-estimate-popup .et-popup-content, .ask-wishlist-estimate-popup .et-close-popup',
                    'property' => 'color'
                )
            ),
        ),

        'xstore_wishlist_ask_estimate_popup_box_model_et-desktop'           => array(
            'name'        => 'xstore_wishlist_ask_estimate_popup_box_model_et-desktop',
            'settings'    => 'xstore_wishlist_ask_estimate_popup_box_model_et-desktop',
            'label'       => $strings['label']['computed_box'],
            'tooltip' => $strings['description']['computed_box'],
            'type'        => 'kirki-box-model',
            'section'     => 'xstore-wishlist',
            'default'     => array(
                'margin-top'          => '0px',
                'margin-right'        => '0px',
                'margin-bottom'       => '0px',
                'margin-left'         => '0px',
                'border-top-width'    => '0px',
                'border-right-width'  => '0px',
                'border-bottom-width' => '0px',
                'border-left-width'   => '0px',
                'padding-top'         => '15px',
                'padding-right'       => '15px',
                'padding-bottom'      => '15px',
                'padding-left'        => '15px',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            ),
            'output'      => array(
                array(
                    'context' => array( 'editor', 'front' ),
                    'element' => '.ask-wishlist-estimate-popup .et-popup-content',
                ),
            ),
            'transport'   => 'postMessage',
            'js_vars'     => box_model_output( '.ask-wishlist-estimate-popup .et-popup-content' )
        ),

        // xstore_wishlist_ask_estimate_popup_border
        'xstore_wishlist_ask_estimate_popup_border_et-desktop'              => array(
            'name'      => 'xstore_wishlist_ask_estimate_popup_border_et-desktop',
            'type'      => 'select',
            'settings'  => 'xstore_wishlist_ask_estimate_popup_border_et-desktop',
            'label'     => $strings['label']['border_style'],
            'tooltip'   => $strings['description']['border_style'],
            'section'   => 'xstore-wishlist',
            'default'   => 'solid',
            'choices'   => $choices['border_style'],
            'transport' => 'auto',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            ),
            'output'    => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.ask-wishlist-estimate-popup .et-popup-content',
                    'property' => 'border-style'
                ),
            ),
        ),

        // xstore_wishlist_ask_estimate_popup_border_color_custom
        'xstore_wishlist_ask_estimate_popup_border_color_custom_et-desktop' => array(
            'name'        => 'xstore_wishlist_ask_estimate_popup_border_color_custom_et-desktop',
            'type'        => 'color',
            'settings'    => 'xstore_wishlist_ask_estimate_popup_border_color_custom_et-desktop',
            'label'       => $strings['label']['border_color'],
            'tooltip' => $strings['description']['border_color'],
            'section'     => 'xstore-wishlist',
            'default'     => '#e1e1e1',
            'choices'     => array(
                'alpha' => true
            ),
            'transport'   => 'auto',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
                array(
                    'setting'  => 'xstore_wishlist_ask_estimate_button_text',
                    'operator' => '!=',
                    'value'    => '',
                ),
            ),
            'output'      => array(
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => '.ask-wishlist-estimate-popup .et-popup-content',
                    'property' => 'border-color',
                ),
            ),
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );