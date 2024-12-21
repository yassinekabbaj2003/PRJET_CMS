<?php if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Init elements for builder
 *
 * @since   1.0.0
 * @version 1.0.0
 */

return array(
    'connect_block' => array(
        'title'   => esc_html__('Connection block', 'xstore-core'),
        'parent'  => 'header_builder_elements',
        'section' => 'connect_block_package',
        'element_info' => esc_html__('Connection block allows you to place elements one next to another in the horizontal or vertical position, align elements within connection block width and manage space between the elements. Use for example to place search, wishlist and cart without additional space that could appear if you place them in 3 separate columns.', 'xstore-core'),
        'icon'    => 'dashicons-share-alt',
        'class'   => 'et-stuck-block',
        'location' => array( 'header', 'product-single' )
    ),
    'logo' => array(
        'title'   => esc_html__('Logo', 'xstore-core'),
        'parent'  => 'logo',
        'section' => 'logo_content_separator',
        'icon'    => 'dashicons-format-image',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'main_menu' => array(
        'title'   => esc_html__('Main Menu', 'xstore-core'),
        'parent'  => 'main_menu',
        'section' => 'menu_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'secondary_menu' => array(
        'title'   => esc_html__('Secondary menu', 'xstore-core'),
        'parent'  => 'main_menu_2',
        'section' => 'menu_2_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'all_departments' => array(
        'title'   => esc_html__('All Departments', 'xstore-core'),
        'parent'  => 'secondary_menu',
        'section' => 'secondary_menu_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'mobile_menu' => array(
        'title'   => esc_html__('Mobile Menu', 'xstore-core'),
        'parent'  => 'mobile_menu',
        'section' => 'mobile_menu_content_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'cart' => array(
        'title'   => esc_html__('Cart', 'xstore-core'),
        'parent'  => ( class_exists('WooCommerce') ) ? 'cart' : 'cart_off',
        'section' => ( class_exists('WooCommerce') ) ? 'cart_content_separator' : 'cart_off_text',
        'icon'    => 'dashicons-cart',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'wishlist' => array(
        'title'   => esc_html__('Wishlist', 'xstore-core'),
        'parent'  => 'wishlist',
        'section' => 'wishlist_content_separator',
        'icon'    => 'dashicons-heart',
        'class'   => '',
        'location' => array( 'header' )
    ),
//    'waitlist' => array(
//        'title'   => esc_html__('Waitlist', 'xstore-core'),
//        'parent'  => 'waitlist',
//        'section' => 'waitlist_content_separator',
//        'icon'    => 'dashicons-bell',
//        'class'   => '',
//        'location' => array( 'header' )
//    ),
    'compare' => array(
	    'title'   => esc_html__('Compare', 'xstore-core'),
        'parent'  => 'compare',
	    'section' => 'compare_content_separator',
	    'icon'    => 'dashicons-update-alt',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'account' => array(
        'title'   => esc_html__('Account', 'xstore-core'),
        'parent'  => 'account',
        'section' => 'account_content_separator',
        'icon'    => 'dashicons-admin-users',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'search' => array(
        'title'   => esc_html__('Search', 'xstore-core'),
        'parent'  => 'search',
        'section' => 'search_content_separator',
        'icon'    => 'dashicons-search',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'header_socials' => array(
        'title'   => esc_html__('Socials', 'xstore-core'),
        'parent'  => 'header_socials',
        'section' => 'header_socials_content_separator',
        'icon'    => 'dashicons-facebook',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'contacts' => array(
        'title'   => esc_html__('Contacts', 'xstore-core'),
        'parent'  => 'contacts',
        'section' => 'contacts_content_separator',
        'icon'    => 'dashicons-phone',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'newsletter' => array(
        'title'   => esc_html__('Newsletter', 'xstore-core'),
        'parent'  => 'newsletter',
        'section' => 'newsletter_content_separator',
        'icon'    => 'dashicons-email-alt',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'button' => array(
        'title'   => esc_html__('Button', 'xstore-core'),
        'parent'  => 'button',
        'section' => 'button_content_separator',
        'icon'    => 'dashicons-editor-removeformatting',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'promo_text' => array(
        'title'   => esc_html__('Promo text', 'xstore-core'),
        'parent'  => 'promo_text',
        'section' => 'promo_text_content_separator',
        'icon'    => 'dashicons-megaphone',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block1' => array(
        'title'   => esc_html__('Html Block 1', 'xstore-core'),
        'parent'  => 'html_blocks',
        'section' => 'html_block1',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block2' => array(
        'title'   => esc_html__('Html Block 2', 'xstore-core'),
        'parent'  => 'html_blocks',
        'section' => 'html_block2',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block3' => array(
        'title'   => esc_html__('Html Block 3', 'xstore-core'),
        'parent'  => 'html_blocks',
        'section' => 'html_block3',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block4' => array(
	    'title'   => esc_html__('Html Block 4', 'xstore-core'),
	    'parent'  => 'html_blocks',
	    'section' => 'html_block4',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'html_block5' => array(
	    'title'   => esc_html__('Html Block 5', 'xstore-core'),
	    'parent'  => 'html_blocks',
	    'section' => 'html_block5',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'header_widget1' => array(
        'title'   => esc_html__('Widget 1', 'xstore-core'),
        'parent'  => 'header_widgets',
        'section' => 'header_widget1',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'header_widget2' => array(
        'title'   => esc_html__('Widget 2', 'xstore-core'),
        'parent'  => 'header_widgets',
        'section' => 'header_widget2',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'header' )
    ),











    'etheme_woocommerce_template_woocommerce_breadcrumb' => array(
        'title'   => esc_html__('Breadcrumbs', 'xstore-core'),
        'parent'  => 'product_breadcrumbs',
        'section' => 'product_breadcrumbs_content_separator',
        'icon'    => 'dashicons-carrot',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_show_product_images' => array(
        'title'   => esc_html__('Gallery', 'xstore-core'),
        'parent'  => 'product_gallery',
        'section' => 'product_gallery_content_separator',
        'icon'    => 'dashicons-format-gallery',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_title' => array(
        'title'   => esc_html__('Title', 'xstore-core'),
        'parent'  => 'product_title',
        'section' => 'product_title_style_separator',
        'icon'    => 'dashicons-welcome-write-blog',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_price' => array(
        'title'   => esc_html__('Price', 'xstore-core'),
        'parent'  => 'product_price',
        'section' => 'product_price_style_separator',
        'icon'    => 'dashicons-tag',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_rating' => array(
        'title'   => esc_html__('Product rating', 'xstore-core'),
        'parent'  => 'product_rating',
        'section' => 'product_rating_content_separator',
        'icon'    => 'dashicons-star-filled', 
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_excerpt' => array(
        'title'   => esc_html__('Short description', 'xstore-core'),
        'parent'  => 'product_short_description',
        'section' => 'product_short_description_style_separator',
        'icon'    => 'dashicons-clipboard',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_attributes' => array(
        'title'   => esc_html__('Attributes', 'xstore-core'),
        'parent'  => 'product_attributes',
        'section' => 'product_attributes_style_separator',
        'icon'    => 'dashicons-image-filter',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_product_single_size_guide' => array(
        'title'   => esc_html__('Sizing guide', 'xstore-core'),
        'parent'  => 'product_size_guide',
        'section' => 'product_size_guide_content_separator',
        'icon'    => 'dashicons-image-crop',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_button' => array(
        'title'   => esc_html__('Button', 'xstore-core'),
        'parent'  => 'single-button',
        'section' => 'single_product_button_content_separator',
        'icon'    => 'dashicons-editor-removeformatting',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_request_quote' => array(
	    'title'   => esc_html__('Request quote', 'xstore-core'),
        'parent'  => 'single-request-quote',
	    'section' => 'single_product_request_quote_button_content_separator',
	    'icon'    => 'dashicons-editor-help',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_bought_together' => array(
        'title'   => esc_html__('Bought together', 'xstore-core'),
        'parent'  => 'single-bought-together',
        'section' => 'single_product_bought_together_content_separator',
        'icon'    => 'dashicons-products',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_add_to_cart' => array(
        'title'   => esc_html__('Add to cart', 'xstore-core'),
        'parent'  => 'product_cart_form',
        'section' => 'product_cart_style_separator',
        'icon'    => 'dashicons-cart',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_wishlist' => array(
        'title'   => esc_html__('Wishlist', 'xstore-core'),
        'parent'  => 'product_wishlist',
        'section' => 'product_wishlist_content_separator',
        'icon'    => 'dashicons-heart',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
//    'etheme_product_single_waitlist' => array(
//        'title'   => esc_html__('Waitlist', 'xstore-core'),
//        'parent'  => 'product_waitlist',
//        'section' => 'product_waitlist_content_separator',
//        'icon'    => 'dashicons-bell',
//        'class'   => '',
//        'location' => array( 'product-single' )
//    ),
    'etheme_product_single_compare' => array(
        'title'   => esc_html__('Compare', 'xstore-core'),
        'parent'  => 'product_compare',
        'section' => 'product_compare_style_separator',
        'icon'    => 'dashicons-update-alt',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_meta' => array(
        'title'   => esc_html__('Product meta', 'xstore-core'),
        'parent'  => 'product_meta',
        'section' => 'product_meta_content_separator',
        'icon'    => 'dashicons-format-aside', 
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_sharing' => array(
        'title'   => esc_html__('Sharing', 'xstore-core'),
        'parent'  => 'product_sharing',
        'section' => 'product_sharing_content_separator',
        'icon'    => 'dashicons-share',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_product_data_tabs' => array(
        'title'   => esc_html__('Tabs', 'xstore-core'),
        'parent'  => 'product_tabs',
        'section' => 'product_tabs_content_separator',
        'icon'    => 'dashicons-index-card',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_related_products' => array(
        'title'   => esc_html__('Related products', 'xstore-core'),
        'parent'  => 'products_related',
        'section' => 'products_related_content_separator',
        'icon'    => 'dashicons-networking',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_upsell_products' => array(
        'title'   => esc_html__('Upsell products', 'xstore-core'),
        'parent'  => 'products_upsell',
        'section' => 'products_upsell_content_separator',
        'icon'    => 'dashicons-products',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_cross_sells_products' => array(
	    'title'   => esc_html__('Cross-sells products', 'xstore-core'),
        'parent'  => 'products_cross_sell',
	    'section' => 'products_cross_sell_content_separator',
	    'icon'    => 'dashicons-products',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_widget_area_01' => array(
        'title'   => esc_html__('Sidebar', 'xstore-core'),
        'parent'  => 'single_product_layout',
        'section' => 'single_product_layout_content_separator',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_01' => array(
        'title'   => esc_html__('Custom HTML 01', 'xstore-core'),
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_02' => array(
        'title'   => esc_html__('Custom HTML 02', 'xstore-core'),
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_03' => array(
        'title'   => esc_html__('Custom HTML 03', 'xstore-core'),
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_04' => array(
	    'title'   => esc_html__('Custom HTML 04', 'xstore-core'),
	    'parent'  => 'single_product_html_blocks',
	    'section' => 'single_product_html_block1_content_separator',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_05' => array(
	    'title'   => esc_html__('Custom HTML 05', 'xstore-core'),
	    'parent'  => 'single_product_html_blocks',
	    'section' => 'single_product_html_block1_content_separator',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_additional_custom_block' => array(
        'title'   => esc_html__('Additional Custom Block', 'xstore-core'),
        'section' => '',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_product_description' => array(
        'title'   => esc_html__('Full Description', 'xstore-core'),
        'section' => '',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
);