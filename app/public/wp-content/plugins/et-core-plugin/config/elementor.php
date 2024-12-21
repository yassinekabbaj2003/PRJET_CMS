<?php
/**
 *	Register routes 
 */
add_filter( 'etc/add/elementor/widgets', 'etc_elementor_widgets_routes' );
function etc_elementor_widgets_routes( $routes ) {
	
	$check_function = function_exists( 'etheme_get_option' );
    $is_pro_version = defined( 'ELEMENTOR_PRO_VERSION' );
    $is_woocommerce = class_exists('WooCommerce');
    $is_portfolio = get_theme_mod( 'portfolio_projects', true );

    if ( $is_pro_version ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Logo',
        );
    }

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Account',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Cart',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Wishlist',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Waitlist',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Compare',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Newsletter'
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Search',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Popup_Search'
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Mega_Menu',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Departments_Menu',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Nav_Menu',
            'ETC\App\Controllers\Elementor\Theme_Builder\Header\Mobile_Menu',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Images',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Size_Guide',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Wishlist',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Waitlist',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Compare',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Additional_Custom_Block'
        );

    if ( $is_woocommerce && $is_pro_version ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Cross_Sells', // depends on Elementor Pro plugin css
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Upsells', // depends on Elementor Pro plugin css
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Related', // depends on Elementor Pro plugin css
        );
        // these widgets are based on Elementor Pro classes namespaces
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Price',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Meta',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Short_Description',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Title',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Content',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Additional_Information',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Stock',
        );
    }

    if ( $is_woocommerce ) {

        // sales booster
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Fake_Sales',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Fake_Live_Viewing',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Estimated_Delivery',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Quantity_Discounts',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Price_Filter',
        );

        // woocommerce global features
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart_Checkout_Progress_Bar',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart_Checkout_Countdown',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Safe_Checkout',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Breadcrumb',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Frequently_Bought_Together',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Advanced_Stock_Status',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Add_To_Cart',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Request_Quote',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Product_Attributes',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Tabs',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Reviews',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product\Sticky_Cart',
        );

        if ($is_pro_version) {
            // these widgets are based on Elementor Pro classes namespaces
            $routes[] = array(
                'ETC\App\Controllers\Elementor\Theme_Builder\Archive_Title',
            );

            // these widgets are based on Elementor Pro classes namespaces
            $routes[] = array(
                'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Archive_Description',
                'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Archive_Description_Second',
            );
        }

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\WooCommerce_Hook',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Products',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Dynamic_Categories',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Result_Count',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Product_Sorting',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Product_Per_Page',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive\Grid_List',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart_Checkout_Breadcrumbs',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart\Cart_Page',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart\Cart_Page_Separated',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart\Cart_Totals',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart\Cart_Table',
        );

        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Checkout_Page',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Checkout_Page_Multistep', // temporary
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Checkout_Page_Separated',
        );
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Payment_Methods',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Billing_Details',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Shipping_Details',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Additional_Information',
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Order_Review',
        );

    }

    $routes[] = array(
        'ETC\App\Controllers\Elementor\Theme_Builder\Archive\Posts',
        'ETC\App\Controllers\Elementor\Theme_Builder\Archive\Posts_Chess',
        'ETC\App\Controllers\Elementor\Theme_Builder\Archive\Posts_Timeline'
    );

    if ( $is_pro_version ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Title',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Excerpt',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Content',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Featured_Image',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Info',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Author_Box',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Post_Comments',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Related_Posts',
            'ETC\App\Controllers\Elementor\Theme_Builder\Single_Post\Related_Posts_Carousel'
        );
    }

    if ( $is_pro_version ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Post_Navigation',
        );
    }
    $routes[] = array(
        'ETC\App\Controllers\Elementor\Theme_Builder\Post_Sticky_Navigation',
    );

    $routes[] = array(
        'ETC\App\Controllers\Elementor\Theme_Builder\Search_Results\Posts',
    );

    if ( $is_woocommerce ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Search_Results\Products',
        );
    }

    if ( $is_portfolio ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\Search_Results\Projects',
        );
    }

    if ( $is_woocommerce ) {
        // Account
        $routes[] = array(
            'ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Account\Account_Page',
        );
    }

    // sorted new
    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Advanced_Headline',
        'ETC\App\Controllers\Elementor\General\Animated_Headline',
        'ETC\App\Controllers\Elementor\General\Advanced_Calculator',
        'ETC\App\Controllers\Elementor\General\Banner',
        'ETC\App\Controllers\Elementor\General\Static_Block',
        'ETC\App\Controllers\Elementor\General\Banner_Carousel',
        'ETC\App\Controllers\Elementor\General\FlipBox',
        'ETC\App\Controllers\Elementor\General\Team_Member',
        'ETC\App\Controllers\Elementor\General\Testimonials',
        'ETC\App\Controllers\Elementor\General\Blockquote',
        'ETC\App\Controllers\Elementor\General\Marquee',
        'ETC\App\Controllers\Elementor\General\Carousel_Anything', // new
        'ETC\App\Controllers\Elementor\General\Slideshow', // new
        'ETC\App\Controllers\Elementor\General\Countdown',
        'ETC\App\Controllers\Elementor\General\Circle_Progress_Bar',
        'ETC\App\Controllers\Elementor\General\Linear_Progress_Bar',
    );

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Categories',
        'ETC\App\Controllers\Elementor\General\Categories_lists',
        'ETC\App\Controllers\Elementor\General\Custom_Product_Categories_Masonry',
    );

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Products',
        'ETC\App\Controllers\Elementor\General\Product_List',
        'ETC\App\Controllers\Elementor\General\Product_Grid',
        'ETC\App\Controllers\Elementor\General\Product_Carousel',
        'ETC\App\Controllers\Elementor\General\Custom_Products_Masonry',
        'ETC\App\Controllers\Elementor\General\Product_Menu_Layout',
        'ETC\App\Controllers\Elementor\General\Product_Filters',
        'ETC\App\Controllers\Elementor\General\Add_To_Cart',
        'ETC\App\Controllers\Elementor\General\Text_Button',
        'ETC\App\Controllers\Elementor\General\PayPal',
        'ETC\App\Controllers\Elementor\General\Advanced_Tabs',
        'ETC\App\Controllers\Elementor\General\Search',
        'ETC\App\Controllers\Elementor\General\Price_Table',
        'ETC\App\Controllers\Elementor\General\HotSpot',
        'ETC\App\Controllers\Elementor\General\Horizontal_Scroll', // new
    );

    if ( $is_woocommerce && $check_function && get_theme_mod( 'enable_brands', 1 ) ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\General\Brands',
            // 'ETC\App\Controllers\Elementor\General\Brands_List',
        );
    }

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Blog_Carousel',
        // 'ETC\App\Controllers\Elementor\General\Blog',
        // 'ETC\App\Controllers\Elementor\General\Blog_List',
        // 'ETC\App\Controllers\Elementor\General\Blog_Timeline',
        'ETC\App\Controllers\Elementor\General\Custom_Posts_Masonry',
        'ETC\App\Controllers\Elementor\General\Posts',
        'ETC\App\Controllers\Elementor\General\Posts_Chess',
        'ETC\App\Controllers\Elementor\General\Posts_Tabs',
        'ETC\App\Controllers\Elementor\General\Posts_Carousel',
        'ETC\App\Controllers\Elementor\General\Posts_Timeline',
    );

    if ( $is_portfolio ) {
        $routes[] = array(
            'ETC\App\Controllers\Elementor\General\Projects',
            'ETC\App\Controllers\Elementor\General\Projects_Chess',
            'ETC\App\Controllers\Elementor\General\Projects_Timeline',
//            'ETC\App\Controllers\Elementor\General\Projects_Tabs',
            'ETC\App\Controllers\Elementor\General\Projects_Carousel',
            'ETC\App\Controllers\Elementor\General\Portfolio',
        );
    }

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Vertical_Timeline',
        'ETC\App\Controllers\Elementor\General\Horizontal_Timeline',
        'ETC\App\Controllers\Elementor\General\Tabs',
        'ETC\App\Controllers\Elementor\General\Slider',
        'ETC\App\Controllers\Elementor\General\Slides',
        'ETC\App\Controllers\Elementor\General\Gallery',
        'ETC\App\Controllers\Elementor\General\Media_Carousel',
        'ETC\App\Controllers\Elementor\General\Menu_List',
        'ETC\App\Controllers\Elementor\General\Follow',
        'ETC\App\Controllers\Elementor\General\Instagram',
        'ETC\App\Controllers\Elementor\General\Google_Map',
        'ETC\App\Controllers\Elementor\General\Contact_Form_7',
        'ETC\App\Controllers\Elementor\General\Image_Comparison',
        'ETC\App\Controllers\Elementor\General\Icon_list',
        'ETC\App\Controllers\Elementor\General\Lottie_Animation',
        'ETC\App\Controllers\Elementor\General\Icon_Box',
        'ETC\App\Controllers\Elementor\General\Icon_Box_Carousel',
        'ETC\App\Controllers\Elementor\General\Scroll_Progress',
        'ETC\App\Controllers\Elementor\General\Three_Sixty_Product_Viewer',

        'ETC\App\Controllers\Elementor\General\Modal_Popup',
        'ETC\App\Controllers\Elementor\General\Content_Switcher',
        'ETC\App\Controllers\Elementor\General\Toggle_Text',
    );

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Facebook_Comments',
        'ETC\App\Controllers\Elementor\General\Facebook_Embed',
        'ETC\App\Controllers\Elementor\General\Twitter_Feed',
        'ETC\App\Controllers\Elementor\General\Twitter_Feed_Slider',
    );

    $routes[] = array(
        'ETC\App\Controllers\Elementor\General\Tag_Cloud',
        'ETC\App\Controllers\Elementor\General\Sidebar',
        'ETC\App\Controllers\Elementor\General\Sidebar_Off_Canvas',
        'ETC\App\Controllers\Elementor\General\Horizontal_Filters',
        'ETC\App\Controllers\Elementor\General\Horizontal_Filters_Toggle',
    );

	return $routes;
}

/**
 *	Register modules 
 */
add_filter( 'etc/add/elementor/modules', 'etc_elementor_modules' );
function etc_elementor_modules( $modules ) {
	
	$modules['general'] = array(
		'ETC\App\Controllers\Elementor\Modules\General'
	);
	$modules['parallax'] = array(
		'ETC\App\Controllers\Elementor\Modules\Parallax'
	);
	$modules['tooltip'] = array(
		'ETC\App\Controllers\Elementor\Modules\Tooltip'
	);
	$modules['conditional_display'] = array(
		'ETC\App\Controllers\Elementor\Modules\Conditional_Display'
	);
    $modules['wrapper_link'] = array(
        'ETC\App\Controllers\Elementor\Modules\Wrapper_Link'
    );
    $modules['custom_attributes'] = array(
        'ETC\App\Controllers\Elementor\Modules\Custom_Attributes'
    );
	$modules['sticky_column'] = array(
		'ETC\App\Controllers\Elementor\Modules\Sticky_Column'
	);
    $modules['lazy_background_image'] = array(
        'ETC\App\Controllers\Elementor\Modules\Lazy_Background_Image'
    );
	$modules['grid_layer'] = array(
		'ETC\App\Controllers\Elementor\Modules\Grid_Layer'
	);
    $modules['header_sticky'] = array(
        'ETC\App\Controllers\Elementor\Modules\Header_Sticky'
    );
	$modules['css'] = array(
		'ETC\App\Controllers\Elementor\Modules\CSS'
	);

	return $modules;
}

/**
 *	Register controls 
 */
add_filter( 'etc/add/elementor/controls', 'etc_elementor_controls' );
function etc_elementor_controls( $controls ) {

	$controls['etheme-ajax-product'] = array(
		'ETC\App\Controllers\Elementor\Controls\Ajax_Product',
	);

	return $controls;
}

add_filter( 'etc/add/elementor/dynamic_tags', 'etc_elementor_dynamic_tags' );
function etc_elementor_dynamic_tags( $dynamic_tags ) {
    $dynamic_tags[] = array(
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Countdown',
    );
    if ( get_option('xstore_sales_booster_settings_fake_product_sales') ) {
        $dynamic_tags[] = array(
            'ETC\App\Controllers\Elementor\Dynamic_Tags\Sales_Booster_Fake_Sales',
        );
    }
    if ( get_option('xstore_sales_booster_settings_fake_live_viewing') ) {
        $dynamic_tags[] = array(
            'ETC\App\Controllers\Elementor\Dynamic_Tags\Sales_Booster_Fake_Live_Viewing',
        );
    }
    if ( get_option('xstore_sales_booster_settings_estimated_delivery') ) {
        $dynamic_tags[] = array(
            'ETC\App\Controllers\Elementor\Dynamic_Tags\Sales_Booster_Estimated_Delivery',
        );
    }
    $dynamic_tags[] = array(
//        'ETC\App\Controllers\Elementor\Dynamic_Tags\Products_Related', // made via select
//        'ETC\App\Controllers\Elementor\Dynamic_Tags\Products_Upsells', // made via select
//        'ETC\App\Controllers\Elementor\Dynamic_Tags\Products_Cross_Sells', // made via select
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Brand_Name',
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Brand_Image',
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Brand_URL',
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Brand_Description',
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Brands_Images',
        'ETC\App\Controllers\Elementor\Dynamic_Tags\Product_Category_Heading_Image',
    );
    return $dynamic_tags;
}