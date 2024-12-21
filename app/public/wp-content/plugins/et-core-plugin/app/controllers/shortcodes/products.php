<?php

namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Products shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Products extends Shortcodes {
	
	function hooks() {
	}
	
	function products_shortcode( $atts, $content ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		global $wpdb, $woocommerce_loop;
		
		$atts = shortcode_atts( array(
			'ids'                  => '',
			'columns'              => 4,
			'shop_link'            => 1,
			'limit'                => 20,
			'taxonomies'           => '',
			'brands' => '',
			'product_tags' => '',
			'type'                 => 'slider',
			'navigation'           => 'off',
			'per_iteration'        => '',
			// 'first_loaded' => 4,
			'style'                => 'default',
			'show_counter'         => '',
			'show_stock'           => '',
			'show_category'        => true,
			'products'             => '', //featured new sale bestsellings recently_viewed
			'title'                => '',
			'hide_out_stock'       => '',
			'large'                => 4,
			'notebook'             => 3,
			'tablet_land'          => 2,
			'tablet_portrait'      => 2,
			'mobile'               => 1,
			'slider_autoplay'      => false,
			'slider_interval'      => 3000,
			'slider_speed'         => 300,
			'slider_loop'          => false,
			'slider_stop_on_hover' => false,
			'pagination_type'      => 'hide',
			'nav_color'            => '',
			'arrows_bg_color'      => '',
			'default_color'        => '#e1e1e1',
			'active_color'         => '#222',
			'hide_fo'              => '',
			'hide_buttons'         => false,
			'navigation_type'      => 'arrow',
			'navigation_position_style' => 'arrows-hover',
			'navigation_style'     => '',
			'navigation_position'  => 'middle',
			'hide_buttons_for'     => '',
			'orderby'              => 'date',
			'no_spacing'           => '',
			'show_image'           => true,
			'image_position'       => 'left',
			'order'                => 'ASC',
			'product_view'         => '',
			'product_view_color'   => '',
			'product_img_hover'    => '',
			'product_img_size'     => '',
			'show_excerpt'         => false,
			'excerpt_length'       => 120,
			'custom_template'      => '',
			'custom_template_list' => '',
			'per_move'             => 1,
			'autoheight'           => false,
			'ajax'                 => false,
			'ajax_loaded'          => false,
			'no_spacing_grid'      => false,
			'bordered_layout'      => false,
			'hover_shadow'         => false,
			'class'                => '',
			'css'                  => '',
			'is_preview'           => isset( $_GET['vc_editable'] ),
			
			'product_content_custom_elements' => false,
			'product_content_elements' => '',
			'product_add_to_cart_quantity' => false,
			'elementor'            => false,
		), $atts );
		
		// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
		if ( $atts['product_img_size'] ) {
			$atts['product_img_size'] =
				str_replace(
					array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ),
					array( 'woocommerce_gallery_thumbnail', 'woocommerce_thumbnail', 'woocommerce_single' ),
					$atts['product_img_size']
				);
		}
		
		if ( $atts['is_preview'] ) {
			add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			
			add_filter( 'etheme_output_shortcodes_inline_css', function () {
				return true;
			} );
		}
		
		$options = array();
		
		$woocommerce_loop['doing_ajax'] = defined( 'DOING_AJAX' ) && DOING_AJAX;
		
		$woocommerce_loop['product_view']       = $atts['product_view'];
		$woocommerce_loop['product_view_color'] = $atts['product_view_color'];
		$woocommerce_loop['hover']              = $atts['product_img_hover'];
		$woocommerce_loop['size']               = $atts['product_img_size'];
		$woocommerce_loop['show_image']         = $atts['show_image'];
		$woocommerce_loop['show_counter']       = $atts['show_counter'];
		$woocommerce_loop['show_stock']         = $atts['show_stock'];
		$woocommerce_loop['show_category']      = $atts['show_category'];
		$woocommerce_loop['show_excerpt']       = $atts['show_excerpt'];
		$woocommerce_loop['excerpt_length']     = $atts['excerpt_length'];
		
		if ( $atts['product_content_custom_elements'] && count($atts['product_content_elements'])) {
			$woocommerce_loop['product_content_elements'] = $atts['product_content_elements'];
			if ( in_array('product_page_product_excerpt', $atts['product_content_elements']) ) {
				$woocommerce_loop['show_excerpt']       = true;
			}
			$woocommerce_loop['product_add_to_cart_quantity'] = $atts['product_add_to_cart_quantity'];
		}
		
		if ( in_array( $atts['type'], array( 'grid', 'list' ) ) ) {
			if ( $atts['bordered_layout'] ) {
//				$atts['class'] .= ' products-bordered-layout';
				$woocommerce_loop['bordered_layout'] = true;
			}
			if ( $atts['no_spacing_grid'] ) {
//				$atts['class'] .= ' products-no-space';
				$woocommerce_loop['no_spacing_grid'] = true;
			}
		}
		
		if ( $atts['hover_shadow'] ) {
//				$atts['class'] .= ' products-no-space';
			$woocommerce_loop['hover_shadow'] = true;
		}

        switch ($atts['type']) {
            case 'grid':
            case 'slider':
                if ( ! empty( $atts['custom_template'] ) )
                    $woocommerce_loop['custom_template'] = $atts['custom_template'];
                break;
            case 'list':
                if ( ! empty( $atts['custom_template_list'] ) ) {
                    $woocommerce_loop['custom_template'] = $atts['custom_template_list'];
                } elseif ( ! empty( $atts['custom_template'] ) ) {
                    $woocommerce_loop['custom_template'] = $atts['custom_template'];
                }
                break;
        }
		
		$options['lazy_load_element'] = ! $atts['is_preview'] && $atts['ajax'];

//        $options['lazy_load_element'] = ! $atts['is_preview'] && $atts['ajax'] && $atts['navigation'] != 'lazy';
		
		if ( $atts['show_counter'] ) {
			wp_enqueue_script( 'et_countdown');
		}
		
		if ( $options['lazy_load_element'] ) {
			
			if ( function_exists('etheme_enqueue_style')) {
				if ( $atts['show_counter'] ) {
					if ( class_exists( 'WPBMap' ) ) {
						etheme_enqueue_style( 'wpb-et-timer' );
					} else {
						etheme_enqueue_style( 'et-timer' );
					}
				}
				etheme_enqueue_style( 'woocommerce' );
				etheme_enqueue_style( 'woocommerce-archive' );
				if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
					etheme_enqueue_style( "swatches-style" );
				}
				if ( $woocommerce_loop['product_view'] && ! in_array( $woocommerce_loop['product_view'], array( 'disable', 'custom' ) ) ) {
					etheme_enqueue_style( 'product-view-' . $woocommerce_loop['product_view'] );
				}
				else {
					$options['local_product_view'] = etheme_get_option('product_view', 'disable');
					if ( !in_array($options['local_product_view'], array('custom', 'disable')) )
						etheme_enqueue_style( 'product-view-' . $options['local_product_view'] );
					
					if ( $woocommerce_loop['product_view'] == 'custom' || $options['local_product_view'] == 'custom' ) {
						etheme_enqueue_style( 'content-product-custom' );
					}
				}
				if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
					etheme_enqueue_style( 'content-product-custom' );
				}
	
				if ( etheme_get_option('quick_view', 1) ) {
					etheme_enqueue_style( "quick-view" );
					if ( etheme_get_option('quick_view_content_type', 'popup') == 'off_canvas' ) {
						etheme_enqueue_style( "off-canvas" );
					}
				}
			}
			$options['extra'] = ( $atts['type'] == 'slider' ) ? 'slider' : '';
			return et_ajax_element_holder( 'etheme_products', $atts, $options['extra'] );
		}
		
		$options['product_visibility_term_ids'] = wc_get_product_visibility_term_ids();
		
		$options['wp_query_args'] = array(
			'post_type'           => array('product'),
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'posts_per_page'      => $atts['limit'],
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);
		
		if ( $atts['hide_out_stock'] ) {
			$options['wp_query_args']['meta_query'] = array(
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '='
				),
			);
		}
		
		$options['wp_query_args']['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => is_search() ? $options['product_visibility_term_ids']['exclude-from-search'] : $options['product_visibility_term_ids']['exclude-from-catalog'],
			'operator' => 'NOT IN',
		);
		
		switch ( $atts['products'] ) {
			
			case 'featured':
				$options['featured_product_ids']      = wc_get_featured_product_ids();
				$options['wp_query_args']['post__in'] = array_merge( array( 0 ), $options['featured_product_ids'] );
				break;
			
			case 'sale':
				$options['product_ids_on_sale']       = wc_get_product_ids_on_sale();
				$options['wp_query_args']['post__in'] = array_merge( array( 0 ), $options['product_ids_on_sale'] );
				break;
			
			case 'bestsellings':
				$options['wp_query_args']['meta_key'] = 'total_sales';
				$options['wp_query_args']['order']    = 'DESC';
				$options['wp_query_args']['orderby']  = 'meta_value_num';
				break;
			
			case 'recently_viewed':
				$options['viewed_products'] = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
				$options['viewed_products'] = array_filter( array_map( 'absint', $options['viewed_products'] ) );
				
				set_query_var( 'recently_viewed', true );
				
				if ( empty( $options['viewed_products'] ) ) {
					return;
				}
				
				$options['wp_query_args']['post__in'] = $options['viewed_products'];
				$options['wp_query_args']['orderby']  = 'rand';
				
				break;
			
		}
		
		// WCMp vendor plugin compatibility
		if ( function_exists( 'get_wcmp_vendor_settings' ) && get_transient( 'wcmp_spmv_exclude_products_data' ) ) {
			$options['wcmp_vendor_settings']                   = array();
			$options['wcmp_vendor_settings']['spmv_excludes']  = get_transient( 'wcmp_spmv_exclude_products_data' );
			$options['wcmp_vendor_settings']['excluded_order'] = ( get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) ) ? get_wcmp_vendor_settings( 'singleproductmultiseller_show_order', 'general' ) : 'min-price';
			$options['wcmp_vendor_settings']['post__not_in']   = ( isset( $options['wcmp_vendor_settings']['spmv_excludes'][ $options['wcmp_vendor_settings']['excluded_order'] ] ) ) ? $options['wcmp_vendor_settings']['spmv_excludes'][ $options['wcmp_vendor_settings']['excluded_order'] ] : array();
			$options['wp_query_args']['post__not_in']          = ( isset( $options['wp_query_args']['post__not_in'] ) ) ? array_merge( $options['wp_query_args']['post__not_in'], $options['wcmp_vendor_settings']['post__not_in'] ) : $options['wcmp_vendor_settings']['post__not_in'];
		}
		
		if ( $atts['type'] == 'slider' ) {
			if ( $atts['slider_stop_on_hover'] ) {
				$atts['class'] .= ' stop-on-hover';
			}
		}
		
		if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		
		if ( $atts['orderby'] == 'price' ) {
			$options['wp_query_args']['meta_key'] = '_price';
			$options['wp_query_args']['orderby']  = 'meta_value_num';
		}
		
		if ( $atts['ids'] != '' ) {
			if ( ! is_array( $atts['ids'] ) ) {
				$atts['ids'] = explode( ',', $atts['ids'] );
			}
			$atts['ids']                          = array_map( 'trim', $atts['ids'] );
			$options['wp_query_args']['post_type'][] = 'product_variation';
			$options['wp_query_args']['post__in'] = $atts['ids'];
		}
		
		// Narrow by categories
		if ( ! empty( $atts['taxonomies'] ) ) {
			if ( $atts['elementor']) {
				$categories = array_map( 'sanitize_title', explode( ',', $atts['taxonomies'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $categories[0] ) ) {
					$field      = 'term_id';
					$categories = array_map( 'absint', $categories );
					// Check numeric slugs.
					foreach ( $categories as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'product_cat' );
						if ( false !== $the_cat ) {
							$categories[] = $the_cat->term_id;
						}
					}
				}
				
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'product_cat',
					'terms'            => $categories,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['taxonomies']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		if ( ! empty( $atts['product_tags'] ) ) {
			if ( $atts['elementor']) {
				$categories = array_map( 'sanitize_title', explode( ',', $atts['product_tags'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $categories[0] ) ) {
					$field      = 'term_id';
					$categories = array_map( 'absint', $categories );
					// Check numeric slugs.
					foreach ( $categories as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'product_tag' );
						if ( false !== $the_cat ) {
							$categories[] = $the_cat->term_id;
						}
					}
				}
				
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'product_tag',
					'terms'            => $categories,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['taxonomies']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		// Narrow by brands
		if ( ! empty( $atts['brands'] ) ) {
			if ( $atts['elementor']) {
				$brands = array_map( 'sanitize_title', explode( ',', $atts['brands'] ) );
				$field      = 'slug';
				
				if ( is_numeric( $brands[0] ) ) {
					$field      = 'term_id';
					$brands = array_map( 'absint', $brands );
					// Check numeric slugs.
					foreach ( $brands as $cat ) {
						$the_cat = get_term_by( 'slug', $cat, 'brand' );
						if ( false !== $the_cat ) {
							$brands[] = $the_cat->term_id;
						}
					}
				}
				$options['wp_query_args']['tax_query'][] = array(
					'taxonomy'         => 'brand',
					'terms'            => $brands,
					'field'            => $field,
					'operator'         => 'IN',
					'include_children' => true,
				);
			}
			else {
				$options['taxonomy_names'] = get_object_taxonomies( 'product' );
				$options['terms']          = get_terms( $options['taxonomy_names'], array(
					'orderby' => 'name',
					'include' => $atts['brands']
				) );
				
				if ( ! is_wp_error( $options['terms'] ) && ! empty( $options['terms'] ) ) {
					$options['wp_query_args']['tax_query'] = array( 'relation' => 'OR' );
					foreach ( $options['terms'] as $key => $term ) {
						$options['wp_query_args']['tax_query'][] = array(
							'taxonomy'         => $term->taxonomy,
							'field'            => 'slug',
							'terms'            => array( $term->slug ),
							'include_children' => true,
							'operator'         => 'IN'
						);
					}
				}
			}
		}
		
		ob_start();
		
		if ( !$options['lazy_load_element'] && !$atts['ajax_loaded'] ) {
			if ( function_exists( 'etheme_enqueue_style' ) ) {
				if ( $atts['show_counter'] ) {
					if ( class_exists( 'WPBMap' ) ) {
						etheme_enqueue_style( 'wpb-et-timer', true );
					} else {
						etheme_enqueue_style( 'et-timer', true );
					}
				}
				etheme_enqueue_style( 'woocommerce', true );
				etheme_enqueue_style( 'woocommerce-archive', true );
				if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
					etheme_enqueue_style( "swatches-style", true );
				}
				if ( $woocommerce_loop['product_view'] && ! in_array( $woocommerce_loop['product_view'], array( 'disable', 'custom' ) ) ) {
					etheme_enqueue_style( 'product-view-' . $woocommerce_loop['product_view'], true );
				}
				else {
					$options['local_product_view'] = etheme_get_option('product_view', 'disable');
					if ( !in_array($options['local_product_view'], array('custom', 'disable')) )
						etheme_enqueue_style( 'product-view-' . $options['local_product_view'], true );
					
					if ( $woocommerce_loop['product_view'] == 'custom' || $options['local_product_view'] == 'custom' ) {
						etheme_enqueue_style( 'content-product-custom', true );
						$options['content-product-custom-loaded'] = true;
					}
				}
				if ( !isset($options['content-product-custom-loaded']) || ! empty( $woocommerce_loop['custom_template'] ) ) {
					etheme_enqueue_style( 'content-product-custom', true );
				}
			}
		}
		
		switch ( $atts['type'] ) {
			case 'slider':
				$options['slider_args'] = array(
					'title'               => $atts['title'],
					'shop_link'           => $atts['shop_link'],
					'slider_type'         => false,
					'style'               => $atts['style'],
					'no_spacing'          => $atts['no_spacing'],
					'large'               => (int) $atts['large'],
					'notebook'            => (int) $atts['notebook'],
					'tablet_land'         => (int) $atts['tablet_land'],
					'tablet_portrait'     => (int) $atts['tablet_portrait'],
					'mobile'              => (int) $atts['mobile'],
					'slider_autoplay'     => $atts['slider_autoplay'],
					'slider_interval'     => $atts['slider_interval'],
					'slider_speed'        => $atts['slider_speed'],
					'slider_loop'         => $atts['slider_loop'],
					'pagination_type'     => $atts['pagination_type'],
					'nav_color'           => $atts['nav_color'],
					'arrows_bg_color'     => $atts['arrows_bg_color'],
					'default_color'       => $atts['default_color'],
					'active_color'        => $atts['active_color'],
					'hide_buttons'        => $atts['hide_buttons'],
					'navigation_type'     => $atts['navigation_type'],
					'navigation_position_style' => $atts['navigation_position_style'],
					'navigation_style'    => $atts['navigation_style'],
					'navigation_position' => $atts['navigation_position'],
					'hide_buttons_for'    => $atts['hide_buttons_for'],
					'hide_fo'             => $atts['hide_fo'],
					'per_move'            => $atts['per_move'],
					'autoheight'          => $atts['autoheight'],
					'class'               => ( ! empty( $atts['custom_template'] ) ) ? 'products-with-custom-template products-template-' . $atts['custom_template'] . $atts['class'] : $atts['class'],
					'attr'                => ( ! empty( $atts['custom_template'] ) ) ? 'data-post-id="' . $atts['custom_template'] . '"' : '',
					'echo'                => true,
					'elementor'           => $atts['elementor'],
					'is_preview'          => $atts['is_preview'],
				);
				etheme_slider( $options['wp_query_args'], 'product', $options['slider_args'] );
				break;
			case 'full-screen':
				$options['slider_args'] = array(
					'title' => $atts['title'],
					'class' => $atts['class']
				);
				echo $this->products_fullscreen_layout( $options['wp_query_args'], $options['slider_args'] );
				break;
			default:
				// ! Add attr for lazy loading
				$options['atts']                = $options['extra'] = array();
				$options['extra']['navigation'] = $atts['navigation'];
				$options['extra']['ajax_loaded'] = (bool)$atts['ajax_loaded'];
				
				if ( $atts['navigation'] != 'off' ) {
					if ( isset( $options['wp_query_args']['post__in'] ) ) {
						$options['atts'][] = 'data-ids="' . implode( ',', $options['wp_query_args']['post__in'] ) . '"';
					}
					
					if ( isset( $options['wp_query_args']['orderby'] ) ) {
						$options['atts'][] = 'data-orderby="' . $options['wp_query_args']['orderby'] . '"';
					}
					
					if ( isset( $options['wp_query_args']['order'] ) ) {
						$options['atts'][] = 'data-order="' . $options['wp_query_args']['order'] . '"';
					}
					
					if ( $atts['hide_out_stock'] ) {
						$options['atts'][] = 'data-stock="true"';
					}
					
					if ( $atts['products'] ) {
						$options['atts'][] = 'data-type="' . $atts['products'] . '"';
					}
					
					if ( ! empty( $atts['taxonomies'] ) ) {
						$options['atts'][] = 'data-taxonomies="' . $atts['taxonomies'] . '"';
					}
					
					if ( ! empty( $atts['brands'] ) ) {
						$options['atts'][] = 'data-brands="' . $atts['brands'] . '"';
					}
					
					if ( ! empty( $atts['product_tags'] ) ) {
						$options['atts'][] = 'data-product_tags="' . $atts['product_tags'] . '"';
					}
					
					if ( ! empty( $atts['product_view'] ) ) {
						$options['atts'][] = 'data-product_view="' . $atts['product_view'] . '"';
					}
					
					if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
						$options['atts'][] = 'data-custom_template="' . $woocommerce_loop['custom_template'] . '"';
					}
					
					if ( ! empty( $atts['product_view_color'] ) ) {
						$options['atts'][] = 'data-product_view_color="' . $atts['product_view_color'] . '"';
					}
					
					if ( ! empty( $atts['product_img_hover'] ) ) {
						$options['atts'][] = 'data-hover="' . $atts['product_img_hover'] . '"';
					}
					
					if ( ! empty( $atts['product_img_size'] ) ) {
						$options['atts'][] = 'data-size="' . $atts['product_img_size'] . '"';
					}
					
					if ( $atts['show_counter'] ) {
						$options['atts'][] = 'data-show_counter="true"';
					}
					
					if ( $atts['show_stock'] ) {
						$options['atts'][] = 'data-show_stock="true"';
					}

                    if ( $atts['show_excerpt'] ) {
                        $options['atts'][] = 'data-show_excerpt="true"';
                    }
					
					// if ( $atts['first_loaded'] > $atts['limit'] )
					//     $atts['first_loaded'] = $atts['limit'];
					
					$options['extra']['per-page'] = ( $atts['limit'] != -1 && $atts['limit'] < $atts['columns'] ) ? $atts['limit'] : $atts['columns'];
					
					if ( $atts['per_iteration'] && ( $atts['limit'] == -1 || $atts['limit'] >= $atts['per_iteration'] ) ) {
						$options['extra']['per-page'] = $atts['per_iteration'];
					}
					
					$options['extra']['limit']   = $atts['limit'];
					$options['extra']['columns'] = $atts['columns'];
					
					$options['atts'][] = 'data-columns="' . $atts['columns'] . '"';
					$options['atts'][] = 'data-per-page="' . $options['extra']['per-page'] . '"';
					
					if ( $atts['product_content_custom_elements'] && count($atts['product_content_elements'])) {
						$options['atts'][] = 'data-product_content_elements="'. implode(',', $atts['product_content_elements']) . '"';
//						if ( in_array('product_page_product_excerpt', $atts['product_content_elements']) ) {
//							$woocommerce_loop['show_excerpt']       = true;
//						}
					}
				}
				
				if ( $atts['type'] == 'menu' ) {
					$atts['class'] .= ' products-layout-menu';
				}
				
				// ! Add attr for lazy loading end.
				$woocommerce_loop['view_mode'] = $atts['type'];

				echo '<div class="etheme_products etheme_products-r' . rand(1,999) . ' ' . $atts['class'] . '" ' . implode( ' ', $options['atts'] ) . '>';
				if ( $atts['type'] == 'menu' ) {
					$this->products_menu_layout( $options['wp_query_args'], $atts['title'], $atts['columns'], $atts['image_position'], $options['extra'], $atts['is_preview']);
				} else {
					echo etheme_products( $options['wp_query_args'], $atts['title'], $atts['columns'], $options['extra'] );
				}
				echo '</div>';
				unset( $woocommerce_loop['view_mode'] );
				break;
		}
		
		unset( $woocommerce_loop['doing_ajax'] );
		unset( $woocommerce_loop['product_view'] );
		unset( $woocommerce_loop['product_view_color'] );
		unset( $woocommerce_loop['hover'] );
		unset( $woocommerce_loop['size'] );
		unset( $woocommerce_loop['show_image'] );
		unset( $woocommerce_loop['show_category'] );
		unset( $woocommerce_loop['show_excerpt'] );
		unset( $woocommerce_loop['excerpt_length'] );
		unset( $woocommerce_loop['show_counter'] );
		unset( $woocommerce_loop['show_stock'] );
		if ( ! empty( $atts['custom_template'] ) ) {
			unset( $woocommerce_loop['custom_template'] );
		}
		
		unset($woocommerce_loop['bordered_layout']);
		unset($woocommerce_loop['product_content_elements']);
		unset($woocommerce_loop['product_add_to_cart_quantity']);
		unset($woocommerce_loop['no_spacing_grid']);
		unset($woocommerce_loop['hover_shadow']);
		
		unset( $options );
		unset( $atts );
		
		return ob_get_clean();
	}

    public function products_fullscreen_layout( $args, $slider_args = array() ) {
        global $woocommerce_loop;

        extract($slider_args);

        ob_start();

        $products = new \WP_Query( $args );

        $images_slider_items = array();

        if ( $products->have_posts() ) :

            if ( function_exists('etheme_enqueue_style')) {
                etheme_enqueue_style('products-full-screen', true);
                etheme_enqueue_style('single-product', true);
                etheme_enqueue_style('single-product-elements', true);
                etheme_enqueue_style('single-post-meta');
                // if comments allowed
                etheme_enqueue_style('star-rating');
                if (etheme_get_option('enable_swatch', 1) && class_exists('St_Woo_Swatches_Base')) {
                    etheme_enqueue_style("swatches-style");
                }
            }

            ?>

            <div class="et-full-screen-products <?php echo esc_attr( $class ); ?>">
                <div class="et-self-init-slider et-products-info-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <div class="et-product-info-slide swiper-slide swiper-no-swiping">
                                <div class="product-info-wrapper">
                                    <p class="product-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </p>

                                    <?php

                                    woocommerce_template_single_rating();

                                    if ( !get_query_var('et_is-catalog', false) || ! etheme_get_option( 'just_catalog_price', 0 ) ){
                                        woocommerce_template_single_price();
                                    }

                                    woocommerce_template_single_excerpt();

                                    if( isset($woocommerce_loop['show_counter']) && $woocommerce_loop['show_counter'] ) etheme_product_countdown('type2', false);

                                    woocommerce_template_loop_add_to_cart();

                                    if( get_option('yith_wcwl_button_position') == 'shortcode' ) {
                                        etheme_wishlist_btn();
                                    }

                                    if ( isset($woocommerce_loop['show_stock']) && $woocommerce_loop['show_stock'] && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
                                        $id = get_the_ID();
                                        $product = wc_get_product($id);
                                        echo et_product_stock_line($product);
                                    }

                                    woocommerce_template_single_meta();

                                    if(etheme_get_option('share_icons', 1)): ?>
                                        <div class="product-share">
                                            <?php echo do_shortcode('[share title="'.__('Share: ', 'xstore-core').'" text="'.get_the_title().'"]'); ?>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>

                            <?php
                            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                            $images_slider_items[] = '<div class="et-product-image-slide swiper-slide swiper-no-swiping" style="background-image: url(' . $image[0] . ');"></div>';
                            ?>

                        <?php endwhile; // end of the loop. ?>
                    </div>
                </div>
                <div class="et-self-init-slider et-products-images-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php echo implode( '', array_reverse( $images_slider_items) ); ?>
                    </div>
                    <div class="et-products-navigation">
                        <div class="et-swiper-next">
                            <span class="swiper-nav-title"></span>
                            <span class="swiper-nav-price"></span>
                            <span class="swiper-nav-arrow et-icon et-up-arrow"></span>
                        </div>
                        <div class="et-swiper-prev">
                            <span class="swiper-nav-arrow et-icon et-down-arrow"></span>
                            <span class="swiper-nav-title"></span>
                            <span class="swiper-nav-price"></span>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var slidesCount = $('.et-product-info-slide').length;

                    var infoSwiper = new Swiper('.et-products-info-slider', {
                        pagination: {
                            clickable : true
                        },
                        direction: 'vertical',
                        slidesPerView: 1,
                        initialSlide: slidesCount,
                        // simulateTouch: false,
                        noSwiping: true,
                        loop: true,
                        on : {
                            init: function(swiper) {
                                updateNavigation();
                            }
                        }
                    });

                    var imagesSwiper = new Swiper('.et-products-images-slider', {
                        direction: 'vertical',
                        slidesPerView: 1,
                        loop: true,
                        // simulateTouch: false,
                        noSwiping: true,
                        navigation: {
                            nextEl: '.et-products-navigation .et-swiper-next',
                            prevEl: '.et-products-navigation .et-swiper-prev',
                        },
                        pagination: {
                            clickable : true
                        },
                        on : {
                            slideNextTransitionStart: function(swiper) {
                                infoSwiper.slidePrev();
                                updateNavigation();
                            },
                            slidePrevTransitionStart: function(swiper) {
                                infoSwiper.slideNext();
                                updateNavigation();
                            }
                        }
                    });

                    function updateNavigation() {
                        var $nextBtn = $('.et-products-navigation .et-swiper-next'),
                            $prevBtn = $('.et-products-navigation .et-swiper-prev'),
                            currentIndex = $('.et-product-info-slide.swiper-slide-active').data('swiper-slide-index'),
                            prevIndex = ( currentIndex >= slidesCount - 1 ) ? 0 : currentIndex + 1,
                            nextIndex = ( currentIndex <= 0 ) ? slidesCount - 1 : currentIndex - 1,
                            $nextProduct = $('.et-product-info-slide[data-swiper-slide-index="' + nextIndex + '"]'),
                            nextTitle = $nextProduct.find('.product-title a').first().text(),
                            nextPrice = $nextProduct.find('.price').html(),
                            $prevProduct = $('.et-product-info-slide[data-swiper-slide-index="' + prevIndex + '"]'),
                            prevTitle = $prevProduct.find('.product-title a').first().text(),
                            prevPrice = $prevProduct.find('.price').html();

                        $nextBtn.find('.swiper-nav-title').text(nextTitle);
                        $nextBtn.find('.swiper-nav-price').html(nextPrice);

                        $prevBtn.find('.swiper-nav-title').text(prevTitle);
                        $prevBtn.find('.swiper-nav-price').html(prevPrice);
                    }
                    <?php if( isset($woocommerce_loop['show_counter']) ) : ?>
                    if ( etTheme.countdown !== undefined )
                        etTheme.countdown(); // refresh product timers
                    <?php endif; ?>
                });
            </script>

        <?php endif;
        wp_reset_postdata();
        return ob_get_clean();
    }

    public function products_menu_layout($args,$title = false, $columns = 4, $img_pos = 'left', $extra = array(), $is_preview = false ){
        global $wpdb, $woocommerce_loop;
        $output = '';

        if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ){
            $args['no_found_rows'] = false;
            $args['posts_per_page'] = $extra['per-page'];
        }

//		$variable_products_detach = etheme_get_option('variable_products_detach', false);
//		if ( $variable_products_detach ) {
//			$variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);
//            $args['post_type'][] = 'product_variation';
//			$args['post_type'] = array_unique($args['post_type']);
//			$posts_not_in = etheme_product_variations_excluded();
//			if ( array_key_exists('post__not_in', $args) ) {
//				$args['post__not_in'] = array_unique( array_merge((array)$args['post__not_in'], $posts_not_in) );
//			}
//			else {
//				$args['post__not_in'] = array_unique( $posts_not_in );
//			}
//			// hides all variable products
//			if ( $variable_products_no_parent ) {
//				$args['tax_query'][] = array(
//					array(
//						'taxonomy' => 'product_type',
//						'field'    => 'slug',
//						'terms'    => 'variable',
//						'operator' => 'NOT IN',
//					),
//				);
//			}
//		}

        $products = new \WP_Query( $args );

        wc_setup_loop( array(
            'columns'      => $columns,
            'name'         => 'product',
            'is_shortcode' => true,
            'total'        => $args['posts_per_page'],
        ) );

        if ( $products->have_posts() ) :
            if ( wc_get_loop_prop( 'total' ) ) {
//                etheme_enqueue_style( 'product-view-menu', true );
                if ( $title != '' ) {
                    echo '<h2 class="products-title"><span>' . esc_html( $title ) . '</span></h2>';
                }
                ?>
                <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post();
                    global $product;
                    $product_type = $product->get_type();
                    $local_options = array();
                    $local_options['classes'] = get_post_class();
                    $local_options['classes'][] = 'product';
                    $local_options['classes'][] = 'product-view-menu';
                    $local_options['classes'][] = etheme_get_product_class( $columns );
                    $local_options['thumb_id'] = get_post_thumbnail_id();
                    $local_options['url'] = $product->get_permalink();
                    $local_options['excerpt'] = get_the_excerpt();
//					if ( $variable_products_detach && $product_type == 'variation') {
//						$custom_excerpt = $product->get_description();
//						if ( !empty($custom_excerpt)) {
//							$local_options['excerpt'] = $custom_excerpt;
//						}
//					}
                    if ( $woocommerce_loop['excerpt_length'] > 0 && strlen($local_options['excerpt']) > 0 && ( strlen($local_options['excerpt']) > $woocommerce_loop['excerpt_length'])) {
                        $local_options['excerpt']         = substr($local_options['excerpt'],0,$woocommerce_loop['excerpt_length']) . '...';
                    }

                    ?>
                    <div <?php post_class($local_options['classes']); ?>>
                        <div class="content-product">
                            <?php
                            /**
                             * woocommerce_before_shop_loop_item hook.
                             *
                             * @hooked woocommerce_template_loop_product_link_open - 10
                             */
                            remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
                            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
                            do_action( 'woocommerce_before_shop_loop_item' );
                            add_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10);
                            add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

                            // ! Remove image from title action
                            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

                            if ( $woocommerce_loop['show_image']) :

                                $woocommerce_loop['size'] = empty($woocommerce_loop['size']) ? '120x120' : $woocommerce_loop['size'];

                            endif;

                            if ( $woocommerce_loop['show_image'] && $img_pos != 'right' ) :

                                ?>

                                <a class="product-content-image woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo esc_url($local_options['url']); ?>">
                                    <?php
                                    if ( $local_options['thumb_id'] ) {
                                        $local_options['img'] = etheme_get_image( $local_options['thumb_id'], $woocommerce_loop['size'] );
                                        echo !empty($local_options['img']) ? $local_options['img'] : wc_placeholder_img();
                                    }
                                    else
                                        echo wc_placeholder_img();

                                    do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                                </a>

                            <?php endif; ?>

                            <div class="product-details">

                                <?php

                                if ( $woocommerce_loop['show_category']) :

                                    $cat  = etheme_get_custom_field( 'primary_category', ( $product_type == 'variation') ? $product->get_parent_id() : false );

                                    if ( ! empty( $cat ) && $cat != 'auto' ) {
                                        $primary = get_term_by( 'slug', $cat, 'product_cat' );
                                        if ( ! is_wp_error( $primary ) ) {
                                            $term_link = get_term_link( $primary );
                                            if ( ! is_wp_error( $term_link ) ) {
                                                echo '<span class="category"><a href="' . esc_url( $term_link ) . '">' . $primary->name . '</a></span>';
                                            }
                                        }
                                    } else {
                                        echo '<span class="category">' . wc_get_product_category_list( $product->get_id(), ' ' ) . '</span>';
                                    }

                                endif;
                                ?>

                                <div class="product-main-details">

                                    <p class="product-title">
                                        <a href="<?php echo esc_url($local_options['url']); ?>"><?php
                                            //					                            if ( $variable_products_detach && $product_type == 'variation' ) {
                                            //					                                echo wp_specialchars_decode($product->get_name());
                                            //                                                }
                                            //					                            else {
                                            echo wp_specialchars_decode( get_the_title() );
                                            // } ?>
                                        </a>
                                    </p>

                                    <span class="separator"></span>

                                    <?php woocommerce_template_loop_price(); ?>

                                </div>

                                <?php if ( $woocommerce_loop['show_excerpt']) : ?>

                                    <div class="product-info-details">

                                        <div class="product-excerpt">
                                            <?php echo do_shortcode($local_options['excerpt']); ?>
                                        </div>

                                        <span style="visibility: hidden">
			                                    <?php woocommerce_template_loop_price(); ?>
			                                </span>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <?php if ( $woocommerce_loop['show_image'] && $img_pos == 'right' ) : ?>

                                <a class="product-content-image woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo esc_url($local_options['url']); ?>">
                                    <?php
                                    if ( $local_options['thumb_id'] ) {
                                        $local_options['img'] = etheme_get_image( $local_options['thumb_id'], $woocommerce_loop['size'] );
                                        echo !empty($local_options['img']) ? $local_options['img'] : wc_placeholder_img();
                                    }
                                    else
                                        echo wc_placeholder_img();

                                    do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                                </a>

                            <?php endif; ?>

                            <?php
                            add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
                            ?>

                        </div>
                    </div>
                <?php

                endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>
            <?php } ?>
        <?php endif;

        wp_reset_postdata();
        wc_reset_loop();

        // ! Do it for load more button
        if ( isset( $extra['navigation'] ) && $extra['navigation'] != 'off' ) {
            if ( $products->max_num_pages > 1 && $extra['limit'] > $extra['per-page'] ) {
                $attr = 'data-paged="1"';
                $attr .= ' data-max-paged="' . $products->max_num_pages . '"';

                if ( isset( $extra['limit'] ) && $extra['limit'] != -1 ) {
                    $attr .= ' data-limit="' . $extra['limit'] . '"';
                }

                $ajax_nonce = wp_create_nonce( 'etheme_products' );

                $attr .= ' data-nonce="' . $ajax_nonce . '"';

                $type = ( $extra['navigation'] == 'lazy' ) ? 'lazy-loading' : 'button-loading';

                $output .= '
		        <div class="et-load-block text-center et_load-products ' . $type . '">
		        	' . etheme_loader(false, 'no-lqip') . '
		        	<span class="btn">
		        		<a ' . $attr . '>' . esc_html__( 'Load More', 'xstore-core' ) . '</a>
		        	</span>
		        </div>';
            }
        }
        if ( $is_preview )
            $output .= '<script>jQuery(document).ready(function(){
                    etTheme.swiperFunc();
			        etTheme.secondInitSwipers();
			        etTheme.global_image_lazy();
			        if ( etTheme.contentProdImages !== undefined )
	                    etTheme.contentProdImages();
                    if ( window.hoverSlider !== undefined ) { 
			            window.hoverSlider.init({});
                        window.hoverSlider.prepareMarkup();
                    }
			        if ( etTheme.countdown !== undefined )
	                    etTheme.countdown();
			        etTheme.customCss();
			        etTheme.customCssOne();
			        if ( etTheme.reinitSwatches !== undefined )
	                    etTheme.reinitSwatches();
                });</script>';

        return $output;
    }
}