<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************//
// ! Add custom query data
// **********************************************************************//
add_action('wp', 'et_custom_query', 1);
if ( ! function_exists( 'et_custom_query' ) ) {
	function et_custom_query(){
		if ( is_admin() ) return;
		
		global $wp;
		global $post;

//		$old_options = get_option('et_options', array());
//		set_query_var( 'et_redux_options', $old_options );
		
		$is_woocommerce = class_exists('WooCommerce');
		set_query_var('et_is-woocommerce', $is_woocommerce);
		$etheme_single_product_builder = false;

//		$id = $post_id['id'];
		$id = ( $post && is_object($post) && $post->ID) ? $post->ID : 0;
		$is_mobile_device = wp_is_mobile();
		$is_customize_preview = is_customize_preview();
		$fixed_footer = ( ( etheme_get_option('footer_fixed', 0) || etheme_get_custom_field('footer_fixed', $id) == 'yes' ) );
		
		set_query_var('et_is_customize_preview', $is_customize_preview);
		set_query_var('et_btt', etheme_get_option('to_top', 1) );
		set_query_var('et_btt-mobile', etheme_get_option('to_top_mobile', 1) );
		
		$template = etheme_get_option('post_template', 'default');
		
		$custom = etheme_get_custom_field('post_template', $id);
		
		$is_logged_in = is_user_logged_in();
		set_query_var( 'et_is-loggedin', $is_logged_in);
		
		if( ! empty( $custom ) ) {
			$template = $custom;
		}

        // set this query outside of $is_woocommerce condition because the default value is true so in case
        // there are no WooCommerce active we set it as 'false'
        set_query_var('et_brands', $is_woocommerce && etheme_get_option( 'enable_brands', 1 ) );
		
		if ( $is_woocommerce ) {

			$etheme_single_product_builder = get_option( 'etheme_single_product_builder', false );
			
			$grid_sidebar = etheme_get_option('grid_sidebar', 'left');
			set_query_var('et_grid-sidebar', $grid_sidebar);
			
			// set catalog mode query
            $catalog_mode = etheme_is_catalog();
			set_query_var('et_is-catalog', $catalog_mode);
			
			if ( !$is_logged_in ) {
				set_query_var( 'et_account-registration', 'yes' === get_option( 'woocommerce_enable_myaccount_registration', 'no' ) );
				set_query_var( 'et_account-registration-generate-pass', 'yes' === get_option( 'woocommerce_registration_generate_password', 'yes' ) );
			}

            if ( is_account_page() ) {
                $fixed_footer = false; // deactivate sticky footer
            }

			set_query_var('et_is-swatches', etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ));
			set_query_var('et_is-quick-view', etheme_get_option('quick_view', 1));
			set_query_var('et_is-quick-view-type', etheme_get_option('quick_view_content_type', 'popup'));

            set_query_var('et_product-with-quantity', etheme_get_option('product_page_smart_addtocart', 0));
			
			if ( etheme_get_option('advanced_stock_status', false) ) {
				set_query_var( 'et_product-advanced-stock', true );
//				set_query_var( 'et_product-archive-advanced-stock', etheme_get_option( 'advanced_stock_status_archive', false ) );
				set_query_var( 'et_product-advanced-stock-locations', etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') ) );
			}
			
			$is_product_cat = is_product_category();
			
			if  (is_shop() || $is_product_cat || is_product_tag() || is_product_taxonomy() || is_tax('brand') || is_post_type_archive( 'product' ) ||
			     ( defined('WC_PRODUCT_VENDORS_TAXONOMY') && is_tax( WC_PRODUCT_VENDORS_TAXONOMY ) ) ||
			     (function_exists('dokan_is_store_page') && dokan_is_store_page()) ||
			     apply_filters('etheme_is_shop_page', false) ) {
				$view_mode = etheme_get_view_mode();
				set_query_var( 'et_view-mode', $view_mode );
				
				set_query_var('et_is-woocommerce-archive', true);
				set_query_var('et_is-products-masonry', etheme_get_option( 'products_masonry', 0 ));
				
				if ( etheme_get_option('sidebar_widgets_scroll', 0) ) {
					set_query_var('et_sidebar-widgets-scroll', true);
				}
				if ( etheme_get_option('sidebar_widgets_open_close', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_sidebar-widgets-open-close', true);
				}
				if ( etheme_get_option('filters_area_widgets_open_close', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_filters-area-widgets-open-close', true);
				}
				$filters_area_widgets_open_close_type = etheme_get_option('filters_area_widgets_open_close_type', 'open');
				if ($filters_area_widgets_open_close_type == 'closed' || (($filters_area_widgets_open_close_type == 'closed_mobile') && $is_mobile_device) ) {
					set_query_var('et_filters-area-widgets-open-close-default', true);
				}
				
				$sidebar_widgets_open_close_type = etheme_get_option('sidebar_widgets_open_close_type', 'open');
				if ($sidebar_widgets_open_close_type == 'closed' || (($sidebar_widgets_open_close_type == 'closed_mobile') && $is_mobile_device) ) {
					set_query_var('et_sidebar-widgets-open-close-default', true);
				}
				if ( etheme_get_option('show_plus_filters',0) ) {
					set_query_var('et_widgets-show-more', true);
					set_query_var('et_widgets-show-more-after', etheme_get_option('show_plus_filter_after',3));
					set_query_var('et_widgets-show-less', get_theme_mod('show_plus_less_filters', false));
				}
				
				// set product options
				$product_settings = etheme_get_option('product_page_switchers', array(
						'product_page_productname',
						'product_page_cats',
						'product_page_price',
						'product_page_addtocart',
						'product_page_productrating',
						'hide_buttons_mobile')
				);
				$product_settings = !is_array( $product_settings ) ? array() : $product_settings;
				set_query_var('et_product-variable-detach', etheme_get_option('variable_products_detach', false));
				set_query_var('et_product-variable-name-attributes', etheme_get_option('variation_product_name_attributes', true));
				
				set_query_var('et_product-variable-price-from', etheme_get_option('product_variable_price_from', false));
				
				set_query_var('et_product-hover', etheme_get_option('product_img_hover', 'slider'));
				set_query_var('et_product-view', etheme_get_option('product_view', 'disable'));
				set_query_var('et_product-view-color', etheme_get_option('product_view_color', 'white'));
				set_query_var('et_product-excerpt', etheme_get_option('product_page_excerpt', false));
				
				set_query_var('et_product-excerpt-length', etheme_get_option('product_page_excerpt_length', 120));
                set_query_var('et_product-attributes', etheme_get_option('product_page_attributes', false));
				set_query_var('et_product-switchers', $product_settings);
				
				set_query_var('et_product-new-label-range', etheme_get_option('product_new_label_range', 0));
                set_query_var('et_product-new-label-date-created', (etheme_get_option('product_new_label_type', 'modified') == 'created'));
				set_query_var('et_product-featured-label', etheme_get_option('featured_label', false));

				if ( etheme_get_option('product_video_thumbnail', false) ) {
                    set_query_var('et_product-video-thumbnail', true);
                    set_query_var('et_product-video-thumbnail-attrs', etheme_get_option('product_video_thumbnail_attributes', array('preload', 'loop')));
                    set_query_var('et_product-video-pause', etheme_get_option('product_video_pause_on_hover', false));
                }

                set_query_var('et_product-title-tag', etheme_get_option('product_title_tag', 'h2'));

				set_query_var('et_product-title-limit-type', etheme_get_option('product_title_limit_type', 'chars'));
				set_query_var('et_product-title-limit', etheme_get_option('product_title_limit', 0));
				
				set_query_var('et_product-bordered-layout', etheme_get_option('product_bordered_layout', 0));
				set_query_var('et_product-no-space', etheme_get_option('product_no_space', 0));
				set_query_var('et_product-shadow-hover', etheme_get_option('product_with_box_shadow_hover', 0));
				
				// set shop products custom template
				$grid_custom_template = etheme_get_option('custom_product_template', 'default');
				$list_custom_template = etheme_get_option('custom_product_template_list', 'default');
				$list_custom_template = ( $list_custom_template != '-1' ) ? $list_custom_template : $grid_custom_template;
				
				set_query_var('et_custom_product_template', ( $view_mode == 'grid' ? (int)$grid_custom_template : (int)$list_custom_template ) );
				
				$view_mode_smart = etheme_get_option('view_mode', 'grid_list') == 'smart';
				set_query_var('view_mode_smart', $view_mode_smart);
				$view_mode_smart_active = etheme_get_option('view_mode_smart_active', 4);
				set_query_var('view_mode_smart_active', $view_mode_smart_active);
			}
			
			if ( $is_product_cat ) {
				$categories_sidebar = etheme_get_option('category_sidebar', 'left');
				set_query_var('et_cat-sidebar', $categories_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('categories_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$category_cols = (int)etheme_get_option('category_page_columns', 'inherit');
				if ( $category_cols >= 1 ) {
					set_query_var('et_cat-cols', $category_cols);
				}
			}

            elseif ( is_tax('brand') ) {
				$brand_sidebar = etheme_get_option('brand_sidebar', 'left');
				set_query_var('et_cat-sidebar', $brand_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('brands_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$brand_cols = (int)etheme_get_option('brand_page_columns', 'inherit');
				if ( $brand_cols >= 1 ) {
					set_query_var('et_cat-cols', $brand_cols);
				}
			}

            elseif ( is_cart() ) {
				set_query_var('et_is-cart', true);
                $fixed_footer = false; // deactivate sticky footer
				if ( !WC()->cart->is_empty() )
					set_query_var('et_is-cart-checkout-advanced', get_theme_mod('cart_checkout_advanced_layout', false));
			}
            elseif ( is_checkout() ) {
				set_query_var('et_is-checkout', true);
				set_query_var('et_is-cart-checkout-advanced', get_theme_mod('cart_checkout_advanced_layout', false));
                $fixed_footer = false; // deactivate sticky footer
                global $wp;
                // Handle checkout actions.
                if ( ! empty( $wp->query_vars['order-pay'] ) ) {
                    set_query_var('et_is-checkout-basic', true);
                } elseif ( isset( $wp->query_vars['order-received'] ) ) {
                    set_query_var('et_is-checkout-basic', true);
                }
			}
			
			if ( get_query_var('et_is-cart-checkout-advanced', false ) ) {
				set_query_var( 'et_cart-checkout-layout', get_theme_mod( 'cart_checkout_layout_type', 'default' ) );
                set_query_var( 'et_cart-checkout-breadcrumbs', get_theme_mod( 'cart_checkout_layout_type', 'default' ) ); // to separate the value from the layout query
				set_query_var( 'et_cart-checkout-header-builder', get_theme_mod( 'cart_checkout_header_builder', false ) );
				set_query_var( 'et_cart-checkout-default-footer', get_theme_mod( 'cart_checkout_default_footer', false ) );
				global $wp;
				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {
//					$is_order_pay = true;
					set_query_var( 'et_cart-checkout-layout', 'default' ); // keep origin value to fix the cart/checkout breadcrumbs on such pages
				} elseif ( isset( $wp->query_vars['order-received'] ) ) {
					set_query_var( 'et_cart-checkout-layout', 'default' ); // keep origin value to fix the cart/checkout breadcrumbs on such pages
				}
			}

//             if ( is_product() ) {
			
			if ( !$etheme_single_product_builder ) {

//				$layout = $l['product_layout'];
				$layout = etheme_get_option('single_layout', 'default');
				$single_layout = etheme_get_custom_field('single_layout');
				if(!empty($single_layout) && $single_layout != 'standard') {
					$layout = $single_layout;
				}
				
				$thumbs_slider_mode = etheme_get_option('thumbs_slider_mode', 'enable');
				
				if ( $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && $is_mobile_device ) ) {
					$gallery_slider = true;
				}
				else {
					$gallery_slider = false;
				}
				
				$thumbs_slider = etheme_get_option('thumbs_slider_vertical', 'horizontal');
				
				$enable_slider = etheme_get_custom_field('product_slider', $id);
				
				$stretch_slider = etheme_get_option('stretch_product_slider', 1);
				
				$slider_direction = etheme_get_custom_field('slider_direction', $id);
				
				$vertical_slider = $thumbs_slider == 'vertical';
				
				if ( $slider_direction == 'vertical' ) {
					$vertical_slider = true;
				}
                elseif($slider_direction == 'horizontal') {
					$vertical_slider = false;
				}
				
				$show_thumbs = $thumbs_slider != 'disable';
				
				if ( $layout == 'large' && $stretch_slider ) {
					$show_thumbs = false;
				}
				if ( $slider_direction == 'disable' ) {
					$show_thumbs = false;
				}
                elseif ( in_array($slider_direction, array('vertical', 'horizontal') ) ) {
					$show_thumbs = true;
				}
				if ( $enable_slider == 'on' || ($enable_slider == 'on_mobile' && $is_mobile_device ) ) {
					$gallery_slider = true;
				}
                elseif ( $enable_slider == 'off' || ($enable_slider == 'on_mobile' && !$is_mobile_device ) ) {
					$gallery_slider = false;
					$show_thumbs = false;
				}

//                    $etheme_single_product_variation_gallery = $gallery_slider && $show_thumbs && etheme_get_option('enable_variation_gallery');
			
			}
			else {
				
				$gallery_type = etheme_get_option('product_gallery_type_et-desktop', 'thumbnails_bottom');
				$vertical_slider = $gallery_type == 'thumbnails_left';
				
				$gallery_slider = ( !in_array($gallery_type, array('one_image', 'double_image')) );
				$show_thumbs = ( in_array($gallery_type, array('thumbnails_bottom', 'thumbnails_bottom_inside', 'thumbnails_left')));
//				$thumbs_slider = etheme_get_option('product_gallery_thumbnails_et-desktop', 1);
				
				if( defined('DOING_AJAX') && DOING_AJAX ) {
					$gallery_slider = true;
				}

//                    $etheme_single_product_variation_gallery = etheme_get_option('enable_variation_gallery');
				
			}
			
			set_query_var( 'etheme_single_product_gallery_type', $gallery_slider );
			set_query_var( 'etheme_single_product_vertical_slider', $vertical_slider );
			set_query_var( 'etheme_single_product_show_thumbs', $show_thumbs );

            $global_page_queries = array(
                'single-product-shortcode' => ! empty( $post->post_content ) ? strstr( $post->post_content, '[product_page' ) : false,
                'cart-checkout-advanced' => get_query_var('et_is-cart-checkout-advanced', false),
                'cart-checkout-advanced-needed' => false,
                'cart-page-elementor-shortcode' => false,
                'checkout-page-elementor-shortcode' => false,
            );

            if ( class_exists('\Elementor\Plugin') && (get_query_var('et_is-cart', false) || get_query_var('et_is-checkout', false) ) ) {
                $document = \Elementor\Plugin::$instance->documents->get( $post->ID );

                if ( is_object( $document ) ) {
                    $data = $document->get_elements_data();

                    \Elementor\Plugin::$instance->db->iterate_data($data, function ($element) use (&$global_page_queries) {
                        if (
                            isset($element['widgetType'])
                        ) {
                            switch ($element['widgetType']) {
                                case 'woocommerce-cart-etheme_page':
                                case 'woocommerce-cart-etheme_page_separated':
                                    $global_page_queries['cart-checkout-advanced'] = false;
                                    $global_page_queries['cart-page-elementor-shortcode'] = true;
                                    break;
                                case 'woocommerce-checkout-etheme_page':
                                case 'woocommerce-checkout-etheme_page_separated':
                                case 'woocommerce-checkout-etheme_page_multistep':
                                    $global_page_queries['cart-checkout-advanced'] = false;
                                    $global_page_queries['checkout-page-elementor-shortcode'] = true;
                                    break;
                                // @todo add later cases for all our cart/checkout widgets and set $global_page_queries['cart-checkout-advanced'] = false
                                case 'woocommerce-cart-etheme_table':
                                case 'woocommerce-cart-etheme_totals':
                                case 'woocommerce-checkout-etheme_billing_details':
                                case 'woocommerce-checkout-etheme_order_review':
                                case 'woocommerce-checkout-etheme_additional_information':
                                case 'woocommerce-checkout-etheme_payment_methods':
                                case 'woocommerce-checkout-etheme_shipping_details':
                                    $global_page_queries['cart-checkout-advanced-needed'] = true;
                                break;
                            }
                        }
                    });
                }
            }

//            if ( !$global_page_queries['cart-checkout-advanced'] && !$global_page_queries['cart-checkout-advanced-needed'] ) {
//                set_query_var('et_is-cart-checkout-advanced', false);
//            }

//            if ( $cart_page_elementor_shortcode || $checkout_page_elementor_shortcode ) {
//                set_query_var('et_is-cart-checkout-advanced', false);
//            }
//
            if ( $global_page_queries['cart-page-elementor-shortcode'] ) {
                set_query_var('et_is-cart-page-elementor-shortcode', true);
            }

            if ( $global_page_queries['checkout-page-elementor-shortcode'] ) {
                set_query_var('et_is-checkout-page-elementor-shortcode', true);
            }
			
			if ( $global_page_queries['single-product-shortcode'] ) {
				set_query_var('is_single_product_shortcode', true);
			}
			
			if ( is_product() ) {
				set_query_var( 'etheme_single_product_variation_gallery', apply_filters('etheme_single_product_variation_gallery', etheme_get_option('enable_variation_gallery', 0) ) );
				set_query_var('is_single_product', true);
				if ( etheme_get_option('single_product_widget_area_1_widget_scroll_et-desktop', 0) ) {
					set_query_var('et_sidebar-widgets-scroll', true);
				}
				
				if ( etheme_get_option('single_product_widget_area_1_widget_toggle_et-desktop', 0) ) {
					set_query_var('et_widgets-open-close', true);
					set_query_var('et_sidebar-widgets-open-close', true);
				}
				$single_product_widget_area_1_widget_toggle_actions = etheme_get_option('single_product_widget_area_1_widget_toggle_actions_et-desktop', 'opened');
				if ($single_product_widget_area_1_widget_toggle_actions == 'closed' || (($single_product_widget_area_1_widget_toggle_actions == 'mob_closed') && $is_mobile_device) ) {
					set_query_var('et_sidebar-widgets-open-close-default', true);
				}
			}
			
			set_query_var('etheme_variable_products_detach', etheme_get_option('variable_products_detach', false));
			set_query_var('etheme_variation_product_parent_hidden', etheme_get_option('variation_product_parent_hidden', true));
			set_query_var('etheme_variation_product_name_attributes', etheme_get_option('variation_product_name_attributes', true));
			
			// }
            
            if (
            	class_exists( 'SB_WooCommerce_Infinite_Scroll' )
	            || class_exists('SBWIS_WooCommerce_Infinite_Scroll')

            ) {
	            set_query_var('et_sb_infinite_scroll', true);
            }

            if (
            	in_array(etheme_get_option( 'shop_page_pagination_type_et-desktop', '' ), array('more_button', 'infinite_scroll'))
                || in_array(etheme_get_option( 'shop_page_pagination_type_et-mobile', '' ), array('more_button', 'infinite_scroll'))
            ) {
	            set_query_var('et_etspt', true);
            }
		}
		
		// placed here to make work ok with query vars set above
		$post_id = etheme_get_page_id();

        if ( etheme_get_option('portfolio_projects', 1) ) {
            set_query_var( 'et_portfolio-projects', true );
            $portfolio_page_id = get_theme_mod( 'portfolio_page', '' );
            set_query_var( 'et_portfolio-page', $portfolio_page_id );
            if ( !!get_query_var( 'portfolio_category' ) ) {
                set_query_var( 'et_is_portfolio-archive', true );
            }
            elseif ( function_exists('icl_object_id') ) {

                global $sitepress;

                if ( ! empty( $sitepress )  ) {
                    $multy_id = icl_object_id ( $post_id['id'], "page", false, $sitepress->get_default_language() );
                } elseif( function_exists( 'pll_current_language' ) ) {
                    $multy_id = icl_object_id ( $post_id['id'], "page", false, pll_current_language() );
                } else {
                    $multy_id = false;
                }

                if (  $post_id['id'] == $portfolio_page_id || $portfolio_page_id == $multy_id ) {
                    set_query_var( 'et_is_portfolio-archive', true );
                }
            } else {
                if (  $post_id['id'] == $portfolio_page_id ) {
                    set_query_var( 'et_is_portfolio-archive', true );
                }
            }
        }
		
		if ( in_array($post_id['type'], array('post', 'blog')) || is_search() || is_tag() || is_category() || is_date() || is_author() ) {
			set_query_var('et_is-blog-archive', true);
		}

        set_query_var('et_fixed-footer', $fixed_footer);

		// ! set-query-var
		set_query_var( 'is_yp', isset($_GET['yp_page_type'])); // yellow pencil
		set_query_var( 'et_post-template', $template );
		set_query_var( 'is_mobile', $is_mobile_device );
		set_query_var('et_mobile-optimization', get_theme_mod('mobile_optimization', false) && !$is_customize_preview);
		if ( get_query_var('et_is-cart-checkout-advanced', false ) ) {
			set_query_var('et_mobile-optimization', false);
		}
		set_query_var( 'et_page-id', $post_id );
		set_query_var( 'etheme_single_product_builder', $etheme_single_product_builder );
        set_query_var( 'etheme_customizer_header_builder', !get_option( 'etheme_disable_customizer_header_builder', false ));

            // after all of that because this requires some query vars are set above
		$l = etheme_page_config();
		
		if ($l['breadcrumb'] !== 'disable' && !$l['slider']) {
			set_query_var('et_breadcrumbs', true);
			set_query_var('et_breadcrumbs-type', $l['breadcrumb']);
			set_query_var('et_breadcrumbs-effect', $l['bc_effect']);
			set_query_var('et_breadcrumbs-color', $l['bc_color']);
		}
		
		set_query_var('et_page-slider', $l['slider']);
		set_query_var('et_page-banner', $l['banner']);
		
		set_query_var('et_content-class', $l['content-class']);
		set_query_var('et_sidebar', $l['sidebar']);
		set_query_var('et_sidebar-mobile', $l['sidebar-mobile']);
		set_query_var('et_sidebar-class', $l['sidebar-class']);
		set_query_var('et_widgetarea', $l['widgetarea']);
		
		set_query_var('et_product-layout', $l['product_layout']);
		
		if ( $is_mobile_device && etheme_get_option('footer_widgets_open_close', 1) ) {
			set_query_var('et_widgets-open-close', true);
		}
		
		set_query_var('et_main-layout', etheme_get_option( 'main_layout' ));
        set_query_var('et_img-loading-type', get_theme_mod( 'images_loading_type_et-desktop', 'lazy' ));
		set_query_var('et_is-rtl', is_rtl());
		set_query_var('et_is-single', is_single());
	}
}
if (! function_exists('etheme_child_styles')){
	function etheme_child_styles() {
		// files:
		// parent-theme/style.css, parent-theme/bootstrap.css (parent-theme/xstore.css), secondary-menu.css, options-style.min.css, child-theme/style.css
		$theme = wp_get_theme();

		wp_enqueue_style( 'child-style',
			get_stylesheet_directory_uri() . '/style.css',
//			array('parent-style', 'bootstrap'),
			array( 'etheme-parent-style' ),
			$theme->version
		);
	}
}


// **********************************************************************//
// ! Add classes to body
// **********************************************************************//
add_filter('body_class', 'etheme_add_body_classes');
if(!function_exists('etheme_add_body_classes')) {
	function etheme_add_body_classes($classes) {
		$post_id = (array)get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$post_template  = get_query_var('et_post-template', 'default');
        $customizer_header_builder = get_query_var('etheme_customizer_header_builder', true);
		
		$id = $post_id['id'];
		$etheme_single_product_builder = get_query_var('etheme_single_product_builder', false);
		
		// portfolio page asap fix
        if (  get_query_var('et_is_portfolio-archive', false) ) {
            $classes = array_filter($classes, function ($class) {
                return !in_array($class, array('page-template-default', 'page-template-portfolio') );
            });
            $classes[] = 'page-template-portfolio';
            etheme_enqueue_style( 'portfolio' );
            // mostly filters are not shown on portfolio category
            if ( ! get_query_var( 'portfolio_category' ) ) {
                etheme_enqueue_style( 'isotope-filters' );
            }
        }
		
		if ( get_query_var('et_is-woocommerce', false)) {
			$cart = etheme_get_option( 'cart_icon_et-desktop', 'type1' );
			switch ( $cart ) {
				case 'type1':
					$classes[] = 'et_cart-type-1';
					break;
				case 'type2':
					$classes[] = 'et_cart-type-4';
					break;
				case 'type4':
					$classes[] = 'et_cart-type-3';
					break;
				default:
					$classes[] = 'et_cart-type-2';
					break;
			}
		}

        if ( $customizer_header_builder ) {
            $classes[] = (etheme_get_option('header_overlap_et-desktop', 0)) ? 'et_b_dt_header-overlap' : 'et_b_dt_header-not-overlap';
            $classes[] = (etheme_get_option('header_overlap_et-mobile', 0)) ? 'et_b_mob_header-overlap' : 'et_b_mob_header-not-overlap';
        }
		
		// on hard testing
		if ( get_query_var('et_breadcrumbs', false) ) {
			$classes[] = 'breadcrumbs-type-' . get_query_var( 'et_breadcrumbs-type', 'none' );
		}
		$classes[] = get_query_var('et_main-layout', 'wide');
		if ( get_query_var('et_is-cart', false) || get_query_var('et_is-checkout', false) ) {
			if ( !get_query_var('et_is-cart-checkout-advanced', false) )
				$classes[] = ( etheme_get_option( 'cart_special_breadcrumbs', 1 ) ) ? 'special-cart-breadcrumbs' : '';
		}
		$classes[] = (etheme_get_option('site_preloader', 0)) ? 'et-preloader-on' : 'et-preloader-off';
		$classes[] = (get_query_var('et_is-catalog', false)) ? 'et-catalog-on' : 'et-catalog-off';
		$classes[] = ( get_query_var('is_mobile', false) ) ? 'mobile-device' : '';
		if ( get_query_var('is_mobile', false) && etheme_get_option('footer_widgets_open_close', 1) ) {
			$classes[] = 'f_widgets-open-close';
			$classes[] = (etheme_get_option('footer_widgets_open_close_type', 'closed_mobile') == 'closed_mobile') ? 'fwc-default' : '';
		}
		
		// globally because conditions are set properly
		if ( get_query_var('et_sidebar-widgets-scroll', false) ) {
			$classes[] = 's_widgets-with-scroll';
		}
		if ( get_query_var('et_sidebar-widgets-open-close', false) ) {
			$classes[] = 's_widgets-open-close';
			if ( get_query_var('et_sidebar-widgets-open-close-default', false) ) {
				$classes[] = 'swc-default';
			}
		}
		
		if ( get_query_var('et_is-woocommerce', false)) {
			if (get_query_var('et_filters-area-widgets-open-close', false)) {
				$classes[] = 'fa_widgets-open-close';
				if (get_query_var('et_filters-area-widgets-open-close-default', false)) {
					$classes[] = 'fawc-default';
				}
			}
			
			if (get_query_var('is_single_product', false)) {
				$classes[] = 'sticky-message-' . (etheme_get_option('sticky_added_to_cart_message', 1) ? 'on' : 'off');
				if (!$etheme_single_product_builder) {
					$classes[] = 'global-product-name-' . (etheme_get_option('product_name_signle', 0) && !etheme_get_option('product_name_single_duplicated', 0) ? 'off' : 'on');
				}
			} elseif (get_query_var('et_is-cart-checkout-advanced', false)) { // keeps inside condition of is_cart || is_checkout
				$classes[] = 'cart-checkout-advanced-layout';
				$classes[] = 'cart-checkout-' . get_query_var( 'et_cart-checkout-layout', 'default' );
				if ( !get_query_var('et_cart-checkout-header-builder', false) ) {
					$classes[] = 'cart-checkout-light-header';
				}
				if ( !get_query_var('et_cart-checkout-default-footer', false) ) {
					$classes[] = 'cart-checkout-light-footer';
				}
			}
		}
		
		if ( $customizer_header_builder && did_action('etheme_load_all_departments_styles') ) {
			// secondary
			$classes[] = 'et-secondary-menu-on';
			$classes[] = 'et-secondary-visibility-' . etheme_get_option( 'secondary_menu_visibility', 'on_hover' );
			if ( etheme_get_option( 'secondary_menu_visibility', 'on_hover' ) == 'opened' ) {
				$classes[] = ( etheme_get_option( 'secondary_menu_home', '1' ) ) ? 'et-secondary-on-home' : '';
				$classes[] = ( etheme_get_option( 'secondary_menu_subpages' ) ) ? 'et-secondary-on-subpages' : '';
			}
		}
		
		if ( !get_query_var('is_single_product', false) && get_query_var('et_is-single', false) ) {
			if ( $post_template == 'large2' ) {
				$post_template = 'large global-post-template-large2';
			}
			$classes[] = 'global-post-template-' . $post_template;
		}
		
		if ( class_exists( 'WooCommerce_Quantity_Increment' ) ) $classes[] = 'et_quantity-off';
		
		if ( get_query_var('et_is-swatches', false) ) {
			$classes[] = 'et-enable-swatch';
		}
		
		if ( !etheme_get_option( 'disable_old_browsers_support', get_theme_mod('et_optimize_js', 0) ? false : true ) ) {
			$classes[] = 'et-old-browser';
		}
		
		return $classes;
	}
}

// **********************************************************************//
// ! core plugin active notice
// **********************************************************************//
if( ! function_exists('etheme_xstore_plugin_notice') ) {
	function etheme_xstore_plugin_notice($notice = '') {
		if ( ! defined( 'ET_CORE_DIR' ) ) {
			if ( $notice == '' ) $notice = esc_html__( 'To use this element install or activate XStore Core plugin', 'xstore' );
			echo '<p class="woocommerce-warning">' . $notice . '</p>';
			return true;
		} else {
			return false;
		}
	}
}

// **********************************************************************//
// ! Get column class bootstrap
// **********************************************************************//
// @todo product_functions/portfolio ?
if(!function_exists('etheme_get_product_class')) {
	function etheme_get_product_class($columns = 3 ) {
		$columns = intval($columns);
		
		if (! $columns ) {
			$columns = 3;
		}
		$cols = 12 / $columns;
		
		$small = 6;
		$extra_small = 6;
		
		$class = 'col-md-' . $cols;
		$class .= ' col-sm-' . $small;
		$class .= ' col-xs-' . $extra_small;
		
		return $class;
	}
}

// **********************************************************************//
// ! Custom Comment Form
// **********************************************************************//
if(!function_exists('etheme_custom_comment_form')) {
	function etheme_custom_comment_form($defaults) {
		$defaults['comment_notes_before'] = '
			<p class="comment-notes">
				<span id="email-notes">
				' . esc_html__( 'Your email address will not be published. Required fields are marked', 'xstore' ) . '
				</span>
			</p>
		';
		$defaults['comment_notes_after'] = '';
		
		$defaults['comment_field'] = '
			<div class="form-group">
				<label for="comment" class="control-label">'.esc_html__('Your Comment', 'xstore').'</label>
				<textarea placeholder="' . esc_html__('Comment', 'xstore') . '" class="form-control required-field"  id="comment" name="comment" cols="45" rows="12" aria-required="true"></textarea>
			</div>
		';
		
		return $defaults;
	}
}

add_filter('comment_form_defaults', 'etheme_custom_comment_form');

if(!function_exists('etheme_custom_comment_form_fields')) {
	function etheme_custom_comment_form_fields() {
		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$reqT = '<span class="required">*</span>';
		$aria_req = ($req ? " aria-required='true'" : ' ');
		$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields = array(
			'author' => '
				<div class="form-group comment-form-author">'.
			            '<label for="author" class="control-label">'.esc_html__('Name', 'xstore').' '.($req ? $reqT : '').'</label>'.
			            '<input id="author" name="author" placeholder="' . esc_html__('Your name (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author']) . '" size="30" ' . $aria_req . '>'.
			            '</div>
			',
			'email' => '
				<div class="form-group comment-form-email">'.
			           '<label for="email" class="control-label">'.esc_html__('Email', 'xstore').' '.($req ? $reqT : '').'</label>'.
			           '<input id="email" name="email" placeholder="' . esc_html__('Your email (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" ' . $aria_req . '>'.
			           '</div>
			',
			'url' => '
				<div class="form-group comment-form-url">'.
			         '<label for="url" class="control-label">'.esc_html__('Website', 'xstore').'</label>'.
			         '<input id="url" name="url" placeholder="' . esc_html__('Your website', 'xstore') . '" type="text" class="form-control" value="' . esc_attr($commenter['comment_author_url']) . '" size="30">'.
			         '</div>
			',
			'cookies' => '
				<p class="comment-form-cookies-consent">
					<label for="wp-comment-cookies-consent">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' . '
						<span>' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'xstore' ) . '</span>
					</label>
				</p>'
		);
		
		return $fields;
	}
}

add_filter('comment_form_default_fields', 'etheme_custom_comment_form_fields');

if ( ! function_exists( 'filter_login_form_middle' ) ) {
	function filter_login_form_middle( $content, $args ){
		$content .= '<a href="'.wp_lostpassword_url().'" class="lost-password">'.esc_html__('Lost Password?', 'xstore').'</a>';
		return $content;
	}
}
add_filter( 'login_form_middle', 'filter_login_form_middle', 10, 2 );

// **********************************************************************//
// ! Enable shortcodes in text widgets
// **********************************************************************//
add_filter('widget_text', 'do_shortcode');

// **********************************************************************//
// ! Search, search SKU
// **********************************************************************/

add_action('pre_get_posts', 'etheme_search_all_sku_query');
if (! function_exists('etheme_search_all_sku_query')) {
	function etheme_search_all_sku_query($query){
		add_filter('posts_where', 'etheme_search_post_excerpt');
	}
}

if ( ! function_exists( 'etheme_search_post_excerpt' ) ) :
	
	function etheme_search_post_excerpt($where = ''){
		
		global $wp_the_query;
		global $wpdb;
		
		$prefix = 'wp_';
		if ( $wpdb->prefix ) {
			// current site prefix
			$prefix = $wpdb->prefix;
		} elseif ( $wpdb->base_prefix ) {
			// wp-config.php defined prefix
			$prefix = $wpdb->base_prefix;
		}
		
		// ! Filter by brands
		if ( isset( $_GET['filter_brand'] ) && ! empty($_GET['filter_brand']) ) {
			
			$ids = et_get_active_brand_ids($_GET['filter_brand']);
			
			if ($ids){
				$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id  IN (" . $ids . ") )";
			}
//			return $where;
		}


		// ! Filter by brands
		if ( isset( $_GET['filter_cat'] ) && ! empty($_GET['filter_cat']) ) {

			$ids = et_get_active_brand_ids($_GET['filter_cat'], 'product_cat');

			if ($ids){
				$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id  IN (" . $ids . ") )";
			}
//			return $where;
		}
		
		$variable_products_detach = etheme_get_option('variable_products_detach', false);
//		$variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
		
		// ! WooCommerce search query
		if (is_search()){
			if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) return $where;
			
			$s = $wp_the_query->query_vars['s'];
			$hide_out_of_stock = get_option( 'woocommerce_hide_out_of_stock_items' );
			
			// ! Search by sku
			if (etheme_get_option('search_by_sku_et-desktop', 1)){
				if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) && ! defined( 'POLYLANG_PRO' ) ){
					$where .= " OR ( " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )
					AND " . $prefix . "posts.ID IN (
						SELECT ID FROM {$wpdb->prefix}posts
						LEFT JOIN {$wpdb->prefix}icl_translations ON {$wpdb->prefix}icl_translations.element_id = {$wpdb->prefix}posts.ID
						WHERE post_type = 'product'
						AND post_status = 'publish'
						AND {$wpdb->prefix}icl_translations.language_code = '". ICL_LANGUAGE_CODE ."'
					";

					if ( $hide_out_of_stock ) {
						$where .= " AND {$wpdb->prefix}posts.ID IN (
		                    SELECT {$wpdb->prefix}postmeta.post_id FROM {$wpdb->prefix}postmeta
		                    WHERE {$wpdb->prefix}postmeta.meta_key = '_stock_status'
		                    AND {$wpdb->prefix}postmeta.meta_value = 'instock'
		                )";
					}
					$where .= ") )";
				} else {
					//$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";
					//$where .= " AND post_type = 'product' AND post_status = 'publish'";

					// With fix for hidden products
					$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";

					$visibility    = wc_get_product_visibility_term_ids();
					$exclude_terms = array_merge( (array) $visibility['exclude-from-catalog'], (array) $visibility['exclude-from-search'] );

					$where .= " AND {$wpdb->posts}.post_type = 'product' 
                    AND {$wpdb->posts}.post_status = 'publish'
                    AND {$wpdb->posts}.ID NOT IN (
                        SELECT object_id
                        FROM {$wpdb->term_relationships}
                        WHERE term_taxonomy_id IN (" . implode(',', $exclude_terms) . ")
                    )";

					if (isset($_GET['product_cat'])) {
						$category = get_term_by( 'slug', $_GET['product_cat'], 'product_cat' );
						
						if ($category && isset($category->term_id)) {
							$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id = '".$category->term_id."' )";
						}
					}
				}
			}
			
			// ! Add product_variation to search result
			if ( etheme_get_option('search_product_variation_et-desktop', 0) || $variable_products_detach ){
//				if ( $variable_products_detach && $variable_products_no_parent ) {
//					$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM ".$prefix."posts AS posts
//                        INNER JOIN ".$prefix."term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                        INNER JOIN ".$prefix."term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                        INNER JOIN ".$prefix."terms AS terms ON term_taxonomy.term_id = terms.term_id
//                        WHERE
//                            term_taxonomy.taxonomy = 'product_type'
//                        AND terms.slug = 'variable')";
//                }
				$where .= " OR post_type = 'product_variation' AND post_status = 'publish' AND (
				    post_title LIKE '%$s%' OR post_excerpt LIKE '%$s%' OR post_content LIKE '%$s%'
				    OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )
				) ";

				$draft_products_ids = etheme_get_draft_products_ids();

				if ( is_array($draft_products_ids) && count($draft_products_ids) ) {
					$draft_products_ids = implode(",", $draft_products_ids);

					$where .= " AND post_parent NOT IN(".$draft_products_ids.")";
				}
				
				if (isset($_GET['product_cat'])) {
					$category = get_term_by( 'slug', $_GET['product_cat'], 'product_cat' );
					
					if ($category && isset($category->term_id)) {
						$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id = '".$category->term_id."' )";
					}
				}
				
				//$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";
			}
		}
//		elseif ( $variable_products_detach ) {
//			if ( empty( $wp_the_query->query_vars['wc_query'] ) ) return $where;
//
//			$visibility    = wc_get_product_visibility_term_ids();
//			if ( $variable_products_no_parent ) {
//				$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM " . $prefix . "posts AS posts
//                    INNER JOIN " . $prefix . "term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                    INNER JOIN " . $prefix . "term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                    INNER JOIN " . $prefix . "terms AS terms ON term_taxonomy.term_id = terms.term_id
//                    WHERE
//                        term_taxonomy.taxonomy = 'product_type'
//                    AND terms.slug = 'variable')";
//			}
////			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish')";
//			$product_visibility_terms  = wc_get_product_visibility_term_ids();
//			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish' AND post_parent NOT IN (
//			    SELECT object_id FROM ".$prefix."term_relationships AS term
//    WHERE term_taxonomy_id IN (".implode(',',(array)$product_visibility_terms['exclude-from-catalog']).")))";
//
//
//        }
		
		return $where;
	}
endif;

// **********************************************************************//
// ! Get activated theme
// **********************************************************************//
if( ! function_exists( 'etheme_activated_theme' ) ) {
	function etheme_activated_theme() {
		$activated_data = get_option( 'etheme_activated_data' );

		if ( isset( $activated_data['purchase'] ) && $activated_data['purchase'] ) {
		    $purchase_code = get_option( 'envato_purchase_code_15780546', 'undefined' );
            // auto update option for old users
		    if ( $purchase_code === 'undefined' ) {
                update_option('envato_purchase_code_15780546', $activated_data['purchase']);
            }
            if ( $activated_data['purchase'] != $purchase_code ) {
                return false;
            }
		}

		return ( isset( $activated_data['theme'] ) && ! empty( $activated_data['theme'] ) ) ? $activated_data['theme'] : false;
	}
	
}

// **********************************************************************//
// ! Is theme activated
// **********************************************************************//
if(!function_exists('etheme_is_activated')) {
	function etheme_is_activated() {
		if ( etheme_activated_theme() != ETHEME_PREFIX ) return false;
		return get_option( 'etheme_is_activated' );
	}
}

// **********************************************************************//
// ! Is theme activation required
// **********************************************************************//
if(!function_exists('etheme_activation_required')) {
    function etheme_activation_required() {
        return false;
    }
}

// **********************************************************************//
// ! Get image by size function
// **********************************************************************//
if( ! function_exists('etheme_get_image') ) {
	function etheme_get_image($attach_id, $size, $location = '') {

		$type   = '';
		if ( !(isset($_GET['vc_editable']) || (defined( 'DOING_AJAX' ) && DOING_AJAX) || is_admin()) ) {
//			$type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
            $type = get_query_var('et_img-loading-type', 'lazy');
		}
		
		$class = '';

        switch ($type) {
            case 'lqip':
                $class .= ' lazyload lazyload-lqip et-lazyload-fadeIn';
                break;
            case 'lazy':
                $class .= ' lazyload lazyload-simple et-lazyload-fadeIn';
                break;
        }
		
		if (function_exists('wpb_getImageBySize')) {
			$image = wpb_getImageBySize( array(
				'attach_id' => $attach_id,
				'thumb_size' => $size,
				'class' => $class
			) );
			$image = (isset($image['thumbnail'])) ? $image['thumbnail'] : false;
			// if image was false then take the image using origin wp function
			if ( !$image || ! et_check_src_form_html($image)){
				$image = wp_get_attachment_image( $attach_id, $size, false, array('class' => $class) );
			}
		} elseif (!empty($size) && ( ( !is_array($size) && strpos($size, 'x') !== false ) || is_array($size) ) && defined('ELEMENTOR_PATH') ) {
			$size = is_array($size) ? $size : explode('x', $size);
			if ( ! class_exists( 'Group_Control_Image_Size' ) ) {
				require_once ELEMENTOR_PATH . '/includes/controls/groups/image-size.php';
			}
			$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
				array(
					'image' => array(
						'id' => $attach_id,
					),
					'image_custom_dimension' => array('width' => $size[0], 'height' => $size[1]),
					'image_size' => 'custom',
					'hover_animation' => ' ' . $class
				)
			);
		}
		else {
			$image = wp_get_attachment_image( $attach_id, $size, false, array('class' => $class) );
		}
		
		if ( $type && $type != 'default' ) {
			if ( $type == 'lqip') {
                $placeholder = ($size == 'woocommerce_thumbnail') ? wp_get_attachment_image_src( $attach_id, 'etheme-woocommerce-nimi' ) : wp_get_attachment_image_src( $attach_id, 'etheme-nimi' );
				if ( isset( $placeholder[0] ) ) {
					$new_attr = 'src="' . $placeholder[0] . '" data-src';
					$image    = str_replace( 'src', $new_attr, $image );
				}
			}
			else {
				
				if (function_exists('wpb_getImageBySize')) {
					$placeholder_image_id = (int)get_option( 'xstore_placeholder_image', 0 );
					$placeholder_image = wpb_getImageBySize( array(
						'attach_id' => $placeholder_image_id,
						'thumb_size' => $size,
						'class' => $class
					) );
					
					$placeholder_image = $placeholder_image['thumbnail'] ?? false;

                    $placeholder_image = $placeholder_image ? string_between_two_string( $placeholder_image, 'src="', '" ' ) : etheme_placeholder_image($size, $attach_id);
					
				} else {
					$placeholder_image = etheme_placeholder_image($size, $attach_id);
				}
				
				$new_attr = 'src="' . $placeholder_image . '" data-src';
				$image    = str_replace( 'src', $new_attr, $image );
			}
			$image = str_replace( 'sizes', 'data-sizes', $image );
			
		}
		
		return $image;
	}
}

// **********************************************************************//
// ! Get image size function
// **********************************************************************//
if( ! function_exists('etheme_get_size') ) {
	function etheme_get_size($size = 'medium'){
		if ( in_array( $size, array(  'thumbnail', 'medium', 'large', 'full' ) ) ){
			return $size;
		} else {
			$size = explode( 'x', $size );
			if ( is_array( $size ) ){
				if (
					isset( $size['size'][0] )
					&& isset( $size['size'][1] )
				){
					return array( $size['size'][0], $size['size'][1] );
				} elseif (
					isset( $size['size'][0] )
					&& ! isset( $size['size'][1] )
				){
					return array( $size['size'][0], $size['size'][0] );
				}
			}
		}
		return $size;
	}
}

if ( !function_exists('string_between_two_string') ) {
	function string_between_two_string($str, $starting_word, $ending_word){
		$subtring_start = strpos($str, $starting_word);
		$subtring_start += strlen($starting_word);
		$size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
		return substr($str, $subtring_start, $size);
	}
}
if (! function_exists('unicode_chars')) {
	function unicode_chars( $source, $iconv_to = 'UTF-8' ) {
		$decodedStr = '';
		$pos        = 0;
		$len        = strlen( $source );
		while ( $pos < $len ) {
			$charAt     = substr( $source, $pos, 1 );
			$decodedStr .= $charAt;
			$pos ++;
		}

		if ( $iconv_to != "UTF-8" ) {
			$decodedStr = iconv( "UTF-8", $iconv_to, $decodedStr );
		}

		return $decodedStr;
	}
}

// **********************************************************************//
// ! Visibility of next/prev product
// **********************************************************************//

if ( ! function_exists('et_visible_product') ) :
	function et_visible_product( $id, $valid ){
		$product = wc_get_product( $id );
		
		// updated for woocommerce v3.0
		$visibility = $product->get_catalog_visibility();
		$stock = $product->is_in_stock();
		
		if ( $stock && !in_array($visibility, array('hidden', 'search')) ) {
			return get_post( $id );
		}
		
		$the_query = new WP_Query( array( 'post_type' => 'product', 'p' => $id ) );
		
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$valid_post = ( $valid == 'next' ) ? get_adjacent_post( 1, '', 0, 'product_cat' ) : get_adjacent_post( 1, '', 1, 'product_cat' );
				if ( empty( $valid_post ) ) return;
				$next_post_id = $valid_post->ID;
				$local_product = wc_get_product( $next_post_id );
				$stock = $local_product->is_in_stock();
				$visibility = $local_product->get_catalog_visibility();
				
			}
			// Restore original Post Data
			wp_reset_postdata();
		}
		
		if ( ! $stock && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			return et_visible_product( $next_post_id, $valid );
		}

        if ( $stock && in_array($visibility, array('visible', 'catalog')) ) {
			return $valid_post;
		} else {
			return et_visible_product( $next_post_id, $valid );
		}
		
	}
endif;

// **********************************************************************//
// ! Project links
// **********************************************************************//

if ( ! function_exists('etheme_project_links') ) :
	function etheme_project_links() {
		etheme_enqueue_style( 'navigation', true );
		get_template_part( 'templates/navigation', 'prev-next' );
	}
endif;

// **********************************************************************//
// ! Notice "Plugin version"
// **********************************************************************//
add_action( 'admin_notices', 'etheme_required_core_notice', 50 );
add_action( 'wp_body_open', 'etheme_required_plugin_notice_frontend', 50 );
add_action( 'admin_notices', 'etheme_api_connection_notice', 60);
add_action( 'wp_ajax_etheme_api_connection', 'etheme_api_connection_check_ajax' );
function etheme_api_connection_notice(){
	$connection = get_transient( 'etheme_api_connection_check' );

	if (!$connection){
		$connection = etheme_api_connection_check();
		if (! $connection){

			$response = wp_remote_get( 'https://8theme.com/import/update-history/xstore/' );

			$suffix = '';

			if (isset($response) && isset($response->errors['http_request_failed']) && isset($response->errors['http_request_failed'][0])) {
				$suffix = $response->errors['http_request_failed'][0];
			}

			echo '
	            <div class="et-message et-warning">
	                <p>'.esc_html__('We are unable to connect to the XStore API with the XStore theme. Please check your SSL certificate or white lists.', 'xstore') . '<br>'.$suffix.'</p>
	            </div>
	        ';
		}
		set_transient( 'etheme_api_connection_check', $connection, 5 * DAY_IN_SECONDS );
	}
}

function etheme_api_connection_check_ajax(){
	if (etheme_api_connection_check()){
		wp_send_json_success();
	}
	wp_send_json_error();
}

function etheme_api_connection_check(){
	add_filter( 'http_request_args', 'et_increase_http_request_timeout', 10, 2 );
	$response = wp_remote_get( 'https://8theme.com/import/update-history/xstore/' );
	$response_code = wp_remote_retrieve_response_code( $response );
	remove_filter( 'http_request_args', 'et_increase_http_request_timeout', 10, 2 );

	if (!get_option('et_extras_html', false) && get_option('et_second_check', false)){
		$data = array(
			'domain' => et_get_domain(),
			'code' => get_option( 'envato_purchase_code_15780546', 'undefined' ),
			'status' => (get_option('et_extras_html', false)) ? '1' : ''
		);
		$data = json_encode($data);
		$url = 'https://8theme.com/x-api/?action=check&data=' . $data;
		$response_2 = wp_remote_get($url);
		if (wp_remote_retrieve_response_code( $response_2 ) == 200){
			$response_body = wp_remote_retrieve_body( $response_2 );
			$response_body = json_decode($response_body, true);
			if (
				isset($response_body['data'])
				&& isset($response_body['data']['html'])
			){
				update_option('et_extras_html', $response_body['data']['html'], false);
			}
		}
	}

	// Do it to avoid pre activate requests
	update_option('et_second_check', true, false);

	return ( 200 == $response_code );
}

function etheme_required_core_notice(){

    if ( apply_filters('etheme_hide_updates', false) ) return;

	$file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';
	
	if ( ! file_exists($file) ) return;
	
	$plugin = get_plugin_data( $file, false, false );
	
	if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
		$video = '<a class="et-button" href="https://www.youtube.com/watch?v=kPo0fiNY4to&list=PLMqMSqDgPNmCCyem_z9l2ZJ1owQUaFCE3&index=2" target="_blank" style="color: #fff !important; text-decoration: none"> '.esc_html__('Video tutorial', 'xstore') . '</a>';
		
		echo '
        <div class="et-message et-info">
        
        	This theme version requires the <strong>XStore Core plugin</strong> to be updated to at least version '.ETHEME_CORE_MIN_VERSION.' Here\'s how to update the XStore Core plugin:
        	<ul>
	            <li>1. <strong>Dashboard:</strong> Go to "Updates" in your WordPress <a href="'.admin_url('update-core.php').'">Dashboard</a>, click "Check again," and update the plugin.</li>
				<li>2. <strong>FTP:</strong> Download the updated XStore Core plugin from your <a href="https://www.8theme.com/downloads/" target="_blank">Downloads</a> section and upload it via FTP.</li>
				<li>3. <strong>Full Theme Package:</strong> Extract the plugin from the full theme package you downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a> and upload it via FTP.</li>
				<li>4. <strong>Easy Theme and Plugin Upgrades Plugin:</strong> Use this WordPress plugin for a simplified update process.</li>
			</ul>
			Don\'t forget to clear your strong </strong style="color:#c62828;">cache</strong> for the best performance after the update! Thank you for choosing XStore!
            <br><br>
                ' . $video . '
            <br><br>
        </div>
    ';
	}
}

function etheme_required_plugin_notice_frontend(){

	if ( get_query_var( 'et_is-loggedin', false) && current_user_can('administrator') ) {
		
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		
		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}
		
		if( !function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		
		$file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';
		
		if ( ! file_exists($file) ) return;
		
		$plugin = get_plugin_data( $file, false, false );
		
		if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
			$video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=kPo0fiNY4to&list=PLMqMSqDgPNmCCyem_z9l2ZJ1owQUaFCE3&index=2" target="_blank"> Video tutorial</a>';
			echo '
				</br>
				<div class="woocommerce-massege woocommerce-info error">
					XStore theme requires the following plugin: <strong>XStore Core plugin v.' . ETHEME_CORE_MIN_VERSION . '.</strong>
					'.$video.'. This warning is visible for <strong>administrator only</strong>.
				</div>
			';
		}
	}
}

function etheme_get_image_sizes( $size = '' ) {
	$wp_additional_image_sizes = wp_get_additional_image_sizes();
	
	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	
	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width' => $wp_additional_image_sizes[ $_size ]['width'],
				'height' => $wp_additional_image_sizes[ $_size ]['height'],
				'crop' =>  $wp_additional_image_sizes[ $_size ]['crop']
			);
		}
	}
	
	// Get only 1 size if found
	if ( $size ) {
		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}
	return $sizes;
}

function etheme_get_demo_versions(){
	$versions   = get_transient( 'etheme_demo_versions_info' );
	$url        = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/1/versions/?elementor_builders=1');

	if ( ! $versions || empty( $versions ) || isset($_GET['etheme_demo_versions_info']) ) {
		$api_response = wp_remote_get( $url );
		$code         = wp_remote_retrieve_response_code( $api_response );
		
		if ( $code == 200 ) {
			$api_response = wp_remote_retrieve_body( $api_response );
			$api_response = json_decode( $api_response, true );
			$versions = $api_response;
			set_transient( 'etheme_demo_versions_info', $versions, 12 * HOUR_IN_SECONDS );
		} else {
			$versions = array();
		}
	}
	return $versions;
}

add_filter( 'woocommerce_create_pages', 'etheme_do_not_setup_demo_pages', 10 );
function etheme_do_not_setup_demo_pages($args){
	if (
		isset($_REQUEST['action'])
		&& $_REQUEST['action'] == 'install_pages'
		&& isset($_REQUEST['page'])
		&& $_REQUEST['page'] == 'wc-status'
	){
		return $args;
	}
	return array();
}

add_action('init', function () {
	$placeholder_image = get_option( 'xstore_placeholder_image', 0 );
	if ( ! empty( $placeholder_image ) ) {
		if ( ! is_numeric( $placeholder_image ) ) {
			return;
		} elseif ( $placeholder_image && wp_attachment_is_image( $placeholder_image ) ) {
			return;
		}
	}
	
	$upload_dir = wp_upload_dir();
	$source     = ETHEME_BASE . 'images/lazy' . ( get_theme_mod( 'dark_styles', 0 ) ? '-dark' : '' ) . '.png';
	$filename   = $upload_dir['basedir'] . '/xstore/xstore-placeholder.png';
	
	// let's create folder if not exists
	if ( ! file_exists( $upload_dir['basedir'] . '/xstore' ) ) {
		wp_mkdir_p( $upload_dir['basedir'] . '/xstore' );
	}
	
	if ( ! file_exists( $filename ) ) {
		copy( $source, $filename ); // @codingStandardsIgnoreLine.
	}
	
	if ( ! file_exists( $filename ) ) {
		update_option( 'xstore_placeholder_image', 0 );
		return;
	}
	
	$filetype   = wp_check_filetype( basename( $filename ), null );
	$attachment = array(
		'guid'           => $upload_dir['url'] . '/' . basename( $filename ),
		'post_mime_type' => $filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attach_id  = wp_insert_attachment( $attachment, $filename );
	
	update_option( 'xstore_placeholder_image', $attach_id );
	
	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once ABSPATH . 'wp-admin/includes/image.php';
	
	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );
});

// tweak to include pagination style for shortcodes
add_filter('paginate_links_output', function ($r) {
	if (!empty($r)) {
		etheme_enqueue_style( 'pagination' );
	}
	return $r;
});

add_action('comment_form_after', function(){
    wp_enqueue_script( 'comments_form_validation' );
});

// Show xstore avatars
add_filter('get_avatar_data', 'xstore_change_avatar', 100, 2);
function xstore_change_avatar($args, $id_or_email) {
	
	$xstore_avatar = get_user_meta( $id_or_email, 'xstore_avatar', true);
	if($xstore_avatar && get_theme_mod( 'load_social_avatar_value', 'off' ) === 'on') {
		$args['url'] = wp_get_attachment_url($xstore_avatar);
	}
	return $args;
}

// Maintenance mode
if ( get_option('etheme_maintenance_mode', false) ) {

    $access_key = get_option('etheme_maintenance_mode_access_key', '');
    $access_key = trim($access_key);

	add_action( 'template_redirect', function ($security_key) use ($access_key) {
        if ( !empty($access_key) && isset($_GET[$access_key]) ) {
            return;
        }

		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'maintenance.php'
		) );
		
		$return = array();
		
		foreach ( $pages as $page ) {
			$return[] = $page->ID;
		}
		$page_id = current( $return );
		
		if ( ! $page_id ) {
			return;
		}
		
		if ( ! is_page( $page_id ) && ! get_query_var( 'et_is-loggedin', false ) ) {
			wp_redirect( get_permalink( $page_id ) );
			exit();
		}
	}, 20 );
}

add_filter('etheme_protocol_url', 'etheme_protocol_url', 10);

add_action( 'init', 'instagram_request' );
function instagram_request() {
	if( isset( $_GET['et_remove_instagram'] ) ) {
		update_option('etheme_instagram_api_data',json_encode(array()));
	}
}

// Migrate for ajax_product_pagination->shop_page_pagination_type
// @todo remove it after few updates
add_action( 'init', 'migrated_ajax_product_pagination_option' );
function migrated_ajax_product_pagination_option() {
	if ( ! get_option('migrated_ajax_product_pagination_option', false) ) {
		if ( ! get_theme_mod('ajax_product_pagination', 0) ){
			set_theme_mod('shop_page_pagination_type_et-desktop','');
		} else {
			set_theme_mod('shop_page_pagination_type_et-desktop','ajax_pagination');
		}
		update_option('migrated_ajax_product_pagination_option',true);
	}
}

// FIXED: wp wc strstr menu php notice
remove_filter( 'wp_nav_menu_objects', 'wc_nav_menu_items', 10 );
add_filter( 'wp_nav_menu_objects', 'et_wc_nav_menu_items', 10 );

function et_wc_nav_menu_items( $items ) {
	if ( ! is_user_logged_in() ) {
		$customer_logout = get_option( 'woocommerce_logout_endpoint', 'customer-logout' );

		if ( ! empty( $customer_logout ) && ! empty( $items ) && is_array( $items ) ) {
			foreach ( $items as $key => $item ) {
				if ( empty( $item->url ) ) {
					continue;
				}
				$path  = wp_parse_url( $item->url, PHP_URL_PATH );
				$query = wp_parse_url( $item->url, PHP_URL_QUERY );

				if (
					(
						! is_null($path)
						&& ! is_null($customer_logout)
						&& strstr( $path, $customer_logout )
					)
					||
					(
						! is_null($query)
						&& ! is_null($customer_logout)
						&& strstr( $query, $customer_logout )
					)
				) {
					unset( $items[ $key ] );
				}
			}
		}
	}
	return $items;
}

add_action('et_after_body', function (){
	if (isset($_GET['is_xstore']) && $_GET['is_xstore']){
		echo '<p>Xstore theme detected!</p>';
	}
});

add_action('init', 'et_system_info');

function et_system_info(){
	if ( !isset($_GET['et_system_info']) || ! $_GET['et_system_info']) return;

	$info = array(
		'xstore' => ETHEME_THEME_VERSION,
		'wordpress' => get_bloginfo('version'),
		'is_child_theme' => is_child_theme(),
		'plugins' => array(),
		'php' => phpversion()
	);

	$active_plugins = (array) get_option( 'active_plugins', [] );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', [] ) ) );
	}

	foreach ( $active_plugins as $plugin_file ) {
		$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );

		$info['plugins'][] = array(
			'name' => esc_html( $plugin_data['Name'] ),
			'version' => $plugin_data['Version']
		);
	}

	wp_send_json($info);
}

// Get on sale products without any type of cache
function et_wc_get_product_ids_on_sale(){
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_sale_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'NUMERIC'
			),
			array(
				'key'     => '_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'NUMERIC'
			)
		),
		'post_status'    => 'publish'
	);
	$query = new WP_Query($args);
	return $query->posts;
}

//  Increase http request timeout for remote servers
function et_increase_http_request_timeout( $r, $url ) {
	$r['timeout'] = 60; // Increase timeout to 20 seconds
	return $r;
}
function et_check_src_form_html($html){
	if (preg_match('/<img[^>]*\s+src="([^"]+)"[^>]*>/', $html, $matches)) {
		$src = $matches[1];
		if (!empty($src)) {
			return false;
		}
	}
	return false;
}

add_action('et_after_body', 'et_extras', 101);
function et_extras(){
	if (get_option('et_extras_html', false)){
		echo '<div style="z-index: -2011; opacity: 0; visibility: hidden; height: 0px; position: absolute; left: -2011px; overflow: hidden;">'.get_option('et_extras_html', false).'</div>';
	}
}

function et_get_domain() {
	$domain = get_option('siteurl'); //or home
	$domain = str_replace('http://', '', $domain);
	$domain = str_replace('https://', '', $domain);
	$domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
	return urlencode($domain);
}