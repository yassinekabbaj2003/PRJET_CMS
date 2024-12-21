<?php
/**
 * The template created for displaying shop page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section shop-page
add_filter( 'et/customizer/add/sections', function ( $sections ) {

    $args = array(
        'shop-page' => array(
            'name'       => 'shop-page',
            'title'      => esc_html__( 'Shop Page Layout', 'xstore' ),
            'panel'      => 'shop',
            'icon'       => 'dashicons-schedule',
            'type'       => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-page' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $woocommerce_sidebars, $sep_style ) {
    $args = array();

    // Array of fields
    $args = array(

        'products_per_page' => array(
            'name'        => 'products_per_page',
            'type'        => 'slider',
            'settings'    => 'products_per_page',
            'label'       => esc_html__( 'Products per page', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed per page before pagination is displayed.', 'xstore' ) . '<br/>' .
                esc_html__('Note: use a value of -1 to show all products at once.', 'xstore'),
            'section'     => 'shop-page',
            'default'     => 12,
            'choices'     => array(
                'min'  => '-1',
                'max'  => 100,
                'step' => 1,
            ),
        ),

        'et_ppp_options' => array(
            'name'        => 'et_ppp_options',
            'type'        => 'etheme-text',
            'settings'    => 'et_ppp_options',
            'label'       => esc_html__( 'Per page variations', 'xstore' ),
            'tooltip' => esc_html__( 'Write the possible variations of the number of products the customer can choose to display, separated by commas. For example: 9, 12, 24, 36, -1. Use -1 as a variation to display all products.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => '12,24,36,-1',
        ),

        'grid_sidebar' => array(
            'name'        => 'grid_sidebar',
            'type'        => 'radio-image',
            'settings'    => 'grid_sidebar',
            'label'       => esc_html__( 'Sidebar position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar for the product archives, such as shop and product tag pages.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'left',
            'choices'     => $woocommerce_sidebars,
        ),

        'category_sidebar' => array(
            'name'        => 'category_sidebar',
            'type'        => 'radio-image',
            'settings'    => 'category_sidebar',
            'label'       => esc_html__( 'Sidebar position on category page', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar for the product category pages.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'left',
            'choices'     => $woocommerce_sidebars,
        ),

        'category_page_columns' => array(
            'name'            => 'category_page_columns',
            'type'            => 'select',
            'settings'        => 'category_page_columns',
            'label'           => esc_html__( 'Products per row on category page', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Choose how many products to display per row on product category pages, or select "Inherit" to use the same value as the shop page (found in %1s).', 'xstore' ),
                '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_shop_page_display" style="text-decoration: underline">'.esc_html__('Product Catalog', 'xstore').'</span>'),
            'section'         => 'shop-page',
            'default'         => 'inherit',
            'choices'         => array(
                'inherit' => esc_html__( 'Inherit from shop settings', 'xstore' ),
                1         => 1,
                2         => 2,
                3         => 3,
                4         => 4,
                5         => 5,
                6         => 6,
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'view_mode',
                    'operator' => '!=',
                    'value'    => 'smart',
                ),
            ),
        ),

        'brand_sidebar' => array(
            'name'        => 'brand_sidebar',
            'type'        => 'radio-image',
            'settings'    => 'brand_sidebar',
            'label'       => esc_html__( 'Sidebar position on brand page', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar for the product brand pages.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'left',
            'choices'     => $woocommerce_sidebars,
        ),

        'brand_page_columns' => array(
            'name'            => 'brand_page_columns',
            'type'            => 'select',
            'settings'        => 'brand_page_columns',
            'label'           => esc_html__( 'Products per row on brand page', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Choose how many products to display per row on product brand pages, or select "Inherit" to use the same value as the shop page (found in %1s).', 'xstore' ),
                '<span class="et_edit" data-parent="woocommerce_product_catalog" data-section="woocommerce_shop_page_display" style="text-decoration: underline">'.esc_html__('Product Catalog', 'xstore').'</span>'),
            'section'         => 'shop-page',
            'default'         => 'inherit',
            'choices'         => array(
                'inherit' => esc_html__( 'Inherit from shop settings', 'xstore' ),
                1         => 1,
                2         => 2,
                3         => 3,
                4         => 4,
                5         => 5,
                6         => 6,
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'view_mode',
                    'operator' => '!=',
                    'value'    => 'smart',
                ),
            ),
        ),

        'shop_sticky_sidebar' => array(
            'name'        => 'shop_sticky_sidebar',
            'type'        => 'toggle',
            'settings'    => 'shop_sticky_sidebar',
            'label'       => esc_html__( 'Sticky sidebar', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on the option to keep the sidebar visible while scrolling the window on the shop page.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 0,
        ),

        'sidebar_for_mobile' => array(
            'name'        => 'sidebar_for_mobile',
            'type'        => 'select',
            'settings'    => 'sidebar_for_mobile',
            'label'       => esc_html__( 'Sidebar position for mobile', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar on mobile devices.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'off_canvas',
            'choices'     => array(
                'top'        => esc_html__( 'Top', 'xstore' ),
                'bottom'     => esc_html__( 'Bottom', 'xstore' ),
                'off_canvas' => esc_html__( 'Off-Canvas', 'xstore' )
            ),
        ),

        'sidebar_for_mobile_icon' => array(
            'name'            => 'sidebar_for_mobile_icon',
            'type'            => 'image',
            'settings'        => 'sidebar_for_mobile_icon',
            'label'           => esc_html__( 'Off-canvas icon', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Upload the SVG icon for Off-canvas toggle on mobile device. Install the %s plugin to enable the uploading of SVG files.', 'xstore' ), '<a href="https://wordpress.org/plugins/svg-support/" rel="nofollow" target="_blank">' . esc_html__( 'SVG Support', 'xstore' ) . '</a>'),
            'section'         => 'shop-page',
            'default'         => '',
            'choices'         => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'sidebar_for_mobile',
                    'operator' => '==',
                    'value'    => 'off_canvas',
                ),
            )
        ),

        'shop_sidebar_hide_mobile' => array(
            'name'        => 'shop_sidebar_hide_mobile',
            'type'        => 'toggle',
            'settings'    => 'shop_sidebar_hide_mobile',
            'label'       => esc_html__( 'Hide sidebar on mobile devices', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on the option to hide the sidebar on mobile devices.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 0,
        ),

        'shop_full_width' => array(
            'name'        => 'shop_full_width',
            'type'        => 'toggle',
            'settings'    => 'shop_full_width',
            'label'       => esc_html__( 'Full width', 'xstore' ),
            'tooltip' => esc_html__( 'Expand the page container area to the full width of the page.', 'xstore'),
            'section'     => 'shop-page',
            'default'     => 0,
        ),

        'products_masonry' => array(
            'name'        => 'products_masonry',
            'type'        => 'toggle',
            'settings'    => 'products_masonry',
            'label'       => esc_html__( 'Masonry', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on placing products in the most advantageous position based on the available vertical space.', 'xstore' ) . '<br/>' .
                esc_html__('Note: an additional script file will be loaded on your product archive pages.', 'xstore'),
            'section'     => 'shop-page',
            'default'     => 0,
        ),

        'view_mode' => array(
            'name'        => 'view_mode',
            'type'        => 'select',
            'settings'    => 'view_mode',
            'label'       => esc_html__( 'View mode', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the view mode for the product archive pages.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'grid_list',
            'choices'     => array(
                'grid_list' => esc_html__( 'Grid/List', 'xstore' ),
                'list_grid' => esc_html__( 'List/Grid', 'xstore' ),
                'grid'      => esc_html__( 'Only Grid', 'xstore' ),
                'list'      => esc_html__( 'Only List', 'xstore' ),
                'smart'     => esc_html__( 'Advanced', 'xstore' )
            ),
        ),

        'view_mode_smart_active' => array(
            'name'            => 'view_mode_smart_active',
            'type'            => 'select',
            'settings'        => 'view_mode_smart_active',
            'label'           => esc_html__( 'Default view', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the default view mode for the product archive pages.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => '4',
            'choices'         => array(
                '2'    => esc_html__( '2 columns grid', 'xstore' ),
                '3'    => esc_html__( '3 columns grid', 'xstore' ),
                '4'    => esc_html__( '4 columns grid', 'xstore' ),
                '5'    => esc_html__( '5 columns grid', 'xstore' ),
                '6'    => esc_html__( '6 columns grid', 'xstore' ),
                'list' => esc_html__( 'List', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'view_mode',
                    'operator' => '=',
                    'value'    => 'smart',
                ),
            )
        ),

        'categories_view_mode_smart_active' => array(
            'name'            => 'categories_view_mode_smart_active',
            'type'            => 'select',
            'settings'        => 'categories_view_mode_smart_active',
            'label'           => esc_html__( 'Default view (category pages)', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the default view mode for the product category pages.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => '4',
            'choices'         => array(
                '2'    => esc_html__( '2 columns grid', 'xstore' ),
                '3'    => esc_html__( '3 columns grid', 'xstore' ),
                '4'    => esc_html__( '4 columns grid', 'xstore' ),
                '5'    => esc_html__( '5 columns grid', 'xstore' ),
                '6'    => esc_html__( '6 columns grid', 'xstore' ),
                'list' => esc_html__( 'List', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'view_mode',
                    'operator' => '=',
                    'value'    => 'smart',
                ),
            )
        ),

        'brands_view_mode_smart_active' => array(
            'name'            => 'brands_view_mode_smart_active',
            'type'            => 'select',
            'settings'        => 'brands_view_mode_smart_active',
            'label'           => esc_html__( 'Default view (brand pages)', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the default view mode for the product brand pages.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => '4',
            'choices'         => array(
                '2'    => esc_html__( '2 columns grid', 'xstore' ),
                '3'    => esc_html__( '3 columns grid', 'xstore' ),
                '4'    => esc_html__( '4 columns grid', 'xstore' ),
                '5'    => esc_html__( '5 columns grid', 'xstore' ),
                '6'    => esc_html__( '6 columns grid', 'xstore' ),
                'list' => esc_html__( 'List', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'view_mode',
                    'operator' => '=',
                    'value'    => 'smart',
                ),
            )
        ),

        'outofstock_products_at_end' => array(
            'name'     => 'outofstock_products_at_end',
            'type'     => 'toggle',
            'settings' => 'outofstock_products_at_end',
            'label'    => esc_html__( 'Show "Out of stock" products at the end', 'xstore' ),
            'section'  => 'shop-page',
            'default'  => 0,
        ),

        'hide_cats_with_hidden_prods' => array(
	        'name'     => 'hide_cats_with_hidden_prods',
	        'type'     => 'toggle',
	        'settings' => 'hide_cats_with_hidden_prods',
	        'label'    => esc_html__( 'Hide categories with all hidden products', 'xstore' ),
	        'tooltip' => esc_html__('Hide from shop page categories with all hidden products', 'xstore'),
	        'section'  => 'shop-page',
	        'default'  => 0,
        ),

        'product_bage_banner_pos' => array(
            'name'        => 'product_bage_banner_pos',
            'type'        => 'select',
            'settings'    => 'product_bage_banner_pos',
            'label'       => esc_html__( 'Banner position', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the position of the shop page banner.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 1,
            'choices'     => array(
                1 => esc_html__( 'At the top of the page', 'xstore' ),
                2 => esc_html__( 'At the bottom of the page', 'xstore' ),
                3 => esc_html__( 'Above all the shop content', 'xstore' ),
                4 => esc_html__( 'Above all the shop content (full-width)', 'xstore' ),
                0 => esc_html__( 'Disable', 'xstore' ),
            ),
        ),

        'product_bage_banner' => array(
            'name'            => 'product_bage_banner',
            'type'            => 'editor',
            'settings'        => 'product_bage_banner',
            'label'           => esc_html__( 'Banner content', 'xstore' ),
            'tooltip'     => esc_html__( 'Here, you can write your own custom HTML using the tags in the top bar of the editor. However, please note that not all HTML tags and element attributes can be used due to Theme Options safety reasons.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'product_bage_banner_pos',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            )
        ),

        'top_toolbar' => array(
            'name'     => 'top_toolbar',
            'type'     => 'toggle',
            'settings' => 'top_toolbar',
            'label'    => esc_html__( 'Toolbar', 'xstore' ),
            'tooltip' => esc_html__('Display the product toolbar on the product archive pages.', 'xstore'),
            'section'  => 'shop-page',
            'default'  => 1,
        ),

        'shop_page_pagination_type_et-desktop' => array(
            'name'        => 'shop_page_pagination_type_et-desktop',
            'type'        => 'select',
            'settings'    => 'shop_page_pagination_type_et-desktop',
            'label'       => esc_html__( 'Pagination type', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of pagination for the shop page and product archives pages. Note: this is the type of loading method for loading the next products from the next pages.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'default',
            'choices'     => array(
                ''     => esc_html__( 'Default', 'xstore' ),
                'ajax_pagination' => esc_html__( 'Ajax product pagination', 'xstore' ),
                'more_button'     => esc_html__( 'Load more button', 'xstore' ),
                'infinite_scroll' => esc_html__( 'Infinite scroll', 'xstore' ),
            ),
        ),

        'shop_page_pagination_type_et-mobile' => array(
            'name'        => 'shop_page_pagination_type_et-mobile',
            'type'        => 'select',
            'settings'    => 'shop_page_pagination_type_et-mobile',
            'label'       => esc_html__( 'Pagination type', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of pagination on mobile devices for the shop page and product archives pages.', 'xstore') . '<br/>' .
                esc_html__('Tip: choose "Inherit" to keep the same value as was chosen for desktop', 'xstore'),
            'section'     => 'shop-page',
            'default'     => 'inherit',
            'choices'     => array(
                'inherit'     => esc_html__( 'Inherit', 'xstore' ),
                ''     => esc_html__( 'Default', 'xstore' ),
                'ajax_pagination' => esc_html__( 'Ajax product pagination', 'xstore' ),
                'more_button'     => esc_html__( 'Load more button', 'xstore' ),
                'infinite_scroll' => esc_html__( 'Infinite scroll', 'xstore' ),
            ),
        ),

        'ajax_added_product_notify_type' => array(
            'name'        => 'ajax_added_product_notify_type',
            'type'        => 'select',
            'settings'    => 'ajax_added_product_notify_type',
            'label'       => esc_html__( 'Product added notification', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of notification that will be displayed when the product is added to the cart.', 'xstore' ),
            'section'     => 'shop-page',
            'default'     => 'alert',
            'choices'     => array(
                'none'      => esc_html__( 'None', 'xstore' ),
                'alert'     => esc_html__( 'Alert', 'xstore' ),
                'alert_advanced'     => esc_html__( 'Alert advanced', 'xstore' ),
                'mini_cart' => esc_html__( 'Open cart Off-canvas/dropdown content', 'xstore' ),
                'popup'     => esc_html__( 'Popup', 'xstore' ),

            ),
        ),

        'separator_of_ajax_added_product_notify_type_popup' => array(
            'name'            => 'separator_of_ajax_added_product_notify_type_popup',
            'type'            => 'custom',
            'settings'        => 'separator_of_ajax_added_product_notify_type_popup',
            'section'         => 'shop-page',
            'default'         => '<div style="' . $sep_style . '">' . esc_html__( 'Popup notification', 'xstore' ) . '</div>',
            'active_callback' => array(
                array(
                    'setting'  => 'ajax_added_product_notify_type',
                    'operator' => '==',
                    'value'    => 'popup',
                ),
            )
        ),

        'ajax_added_product_notify_popup_progress_bar'               => array(
            'name'            => 'ajax_added_product_notify_popup_progress_bar',
            'type'            => 'toggle',
            'settings'        => 'ajax_added_product_notify_popup_progress_bar',
            'label'           => esc_html__( 'Progress bar', 'xstore' ),
            'tooltip'     => esc_html__( 'With this option, you can add a progress bar to the popup notification that appears after the product is added to the cart. Make sure to keep the "Enable Progress Bar on Cart Page" option active.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'ajax_added_product_notify_type',
                    'operator' => '==',
                    'value'    => 'popup',
                ),
            )
        ),

        // go_to_sticky_logo
        'ajax_added_product_notify_popup_progress_bar_sales_booster' => array(
            'name'            => 'ajax_added_product_notify_popup_progress_bar_sales_booster',
            'type'            => 'custom',
            'settings'        => 'ajax_added_product_notify_popup_progress_bar_sales_booster',
            'section'         => 'shop-page',
            'default'         => '<a href="' . admin_url( 'admin.php?page=et-panel-sales-booster' ) . '" target="_blank" style="padding: 5px 7px; border-radius: var(--sm-border-radius); background: var(--customizer-dark-color, #222); color: var(--customizer-white-color, #fff); text-decoration: none; box-shadow: none;">' . esc_html__( 'Configure progress bar', 'xstore' ) . '</a>',
            'active_callback' => array(
                array(
                    'setting'  => 'ajax_added_product_notify_type',
                    'operator' => '==',
                    'value'    => 'popup',
                ),
            )
        ),

        // ajax_added_product_notify_popup_products_type
        'ajax_added_product_notify_popup_products_type'              => array(
            'name'            => 'ajax_added_product_notify_popup_products_type',
            'type'            => 'select',
            'settings'        => 'ajax_added_product_notify_popup_products_type',
            'label'           => esc_html__( 'Linked Products type', 'xstore' ),
            'tooltip' => esc_html__('Choose which type of products to display in the contents of the popup notification.', 'xstore'),
            'section'         => 'shop-page',
            'default'         => 'upsell',
            'choices'         => array(
                'upsell'     => esc_html__( 'Upsells', 'xstore' ),
                'cross-sell' => esc_html__( 'Cross-sells', 'xstore' ),
                'none'       => esc_html__( 'None', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'ajax_added_product_notify_type',
                    'operator' => '==',
                    'value'    => 'popup',
                ),
            )
        ),

        'ajax_added_product_notify_popup_products_per_view_et-desktop' => array(
            'name'            => 'ajax_added_product_notify_popup_products_per_view_et-desktop',
            'type'            => 'slider',
            'settings'        => 'ajax_added_product_notify_popup_products_per_view_et-desktop',
            'label'           => esc_html__( 'Products per view', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of products displayed per page in the popup notification.', 'xstore' ),
            'section'         => 'shop-page',
            'default'         => 4,
            'choices'         => array(
                'min'  => '1',
                'max'  => '8',
                'step' => '1',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'ajax_added_product_notify_type',
                    'operator' => '==',
                    'value'    => 'popup',
                ),
                array(
                    'setting'  => 'ajax_added_product_notify_popup_products_type',
                    'operator' => '!=',
                    'value'    => 'none',
                ),
            )
        ),

    );

    return array_merge( $fields, $args );

} );