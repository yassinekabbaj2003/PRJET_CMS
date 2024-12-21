 <?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 9.4.0
 * @xstore-version 9.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

 // Ensure visibility
 if ( ! is_a( $product, WC_Product::class ) ) {
	 return;
 }
 
$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

 if ( $doing_ajax || !get_query_var('et_is-woocommerce-archive', false) ) {
    $variable_products_detach = etheme_get_option('variable_products_detach', false);
//    $show_attributes_name = etheme_get_option('variation_product_name_attributes', true);
	
    $variable_price_from = etheme_get_option('product_variable_price_from', false);

    $enabled_brands = etheme_get_option( 'enable_brands', 1 );
    $hover = etheme_get_option('product_img_hover', 'slider');
    $view = etheme_get_option('product_view', 'disable');
    $view_color = etheme_get_option('product_view_color', 'white');
    $just_catalog = etheme_is_catalog();
    $custom_template = etheme_get_option('custom_product_template', 'default');
    $show_excerpt = etheme_get_option('product_page_excerpt', 0);
    $excerpt_length = etheme_get_option('product_page_excerpt_length', 120);
    $show_attributes_on_hover = etheme_get_option('product_page_attributes', 0);
    $product_settings = etheme_get_option('product_page_switchers', array('product_page_productname', 'product_page_cats', 'product_page_price','product_page_addtocart', 'product_page_productrating', 'hide_buttons_mobile'));
    $product_settings = !is_array( $product_settings ) ? array() : $product_settings;
    $product_new_label_range = etheme_get_option('product_new_label_range', 0);
    $product_new_label_date_created = etheme_get_option('product_new_label_type', 'modified') == 'created';
    $product_featured_label = etheme_get_option('featured_label', false);
    $product_video_thumbnail = etheme_get_option('product_video_thumbnail', false);
    $product_video_attrs = etheme_get_option('product_video_thumbnail_attributes', array('preload', 'loop'));
    $product_video_pause_on_hover = etheme_get_option('product_video_pause_on_hover', false);
    $product_title_tag = etheme_get_option('product_title_tag', 'h2');
    $product_title_limit_type = etheme_get_option('product_title_limit_type', 'chars');
    $product_title_limit = etheme_get_option('product_title_limit', 0);
    $product_has_quantity = etheme_get_option('product_page_smart_addtocart', 0);
    $view_mode = etheme_get_view_mode();
    $is_archive = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_tax('brand') || is_post_type_archive( 'product' ) ||
                  ( defined('WC_PRODUCT_VENDORS_TAXONOMY') && is_tax( WC_PRODUCT_VENDORS_TAXONOMY ) ) || apply_filters('dokan_archive_page', false) || apply_filters('etheme_is_shop_page', false);
    $is_masonry = etheme_get_option( 'products_masonry', 0 );
    $show_quick_view = etheme_get_option('quick_view', 1);
    $advanced_stock = etheme_get_option('advanced_stock_status', false) && in_array('product_archives', (array)etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') )) && 'yes' === get_option( 'woocommerce_manage_stock' );

     // force filter built-in wishlist on ajax actions
     if ( get_theme_mod('xstore_wishlist', false) && class_exists('\XStoreCore\Modules\WooCommerce\XStore_Wishlist')) {
         $xstore_wishlist_instance = \XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
         if ( !$xstore_wishlist_instance::$inited || $doing_ajax ) {
             $xstore_wishlist_instance->init_added_products();
             $xstore_wishlist_instance->define_settings();
         }
         add_filter('etheme_wishlist_btn_output', array($xstore_wishlist_instance, 'old_wishlist_btn_filter'), 10, 2);
     }

     // force filter built-in compare on ajax actions
     if ( !isset($woocommerce_loop['popup-added-to-cart']) && get_theme_mod('xstore_compare', false) && class_exists('\XStoreCore\Modules\WooCommerce\XStore_Compare')) {
         $xstore_compare_instance = \XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance();
         if ( !$xstore_compare_instance::$inited || $doing_ajax ) {
             $xstore_compare_instance->init_added_products();
             $xstore_compare_instance->define_settings();
             add_action('woocommerce_after_shop_loop_item', array($xstore_compare_instance, 'old_compare_btn_filter'), 20);
         }
     }
}
 else {
    $variable_products_detach = get_query_var('et_product-variable-detach', false);
//    $show_attributes_name = get_query_var('et_product-variable-name-attributes', true);
	
    $variable_price_from = get_query_var('et_product-variable-price-from', false);

    $enabled_brands = get_query_var('et_brands', true);
    $hover = get_query_var('et_product-hover', 'slider');
    $view = get_query_var('et_product-view', 'disable');
    $view_color = get_query_var('et_product-view-color', 'white');
    $just_catalog = get_query_var('et_is-catalog', false);
    $custom_template = get_query_var( 'et_custom_product_template' );
    $show_excerpt = get_query_var('et_product-excerpt', false);
    $show_attributes_on_hover = get_query_var('et_product-attributes', false);
    $excerpt_length = get_query_var('et_product-excerpt-length', 120);
    $product_settings = get_query_var('et_product-switchers', array());
    $product_new_label_range = get_query_var('et_product-new-label-range', 0);
    $product_new_label_date_created = get_query_var('et_product-new-label-date-created', false);
    $product_featured_label = get_query_var('et_product-featured-label', false);
    $product_video_thumbnail = get_query_var('et_product-video-thumbnail', false);
    $product_video_attrs = get_query_var('et_product-video-thumbnail-attrs', array('preload', 'loop'));
    $product_video_pause_on_hover = get_query_var('et_product-video-pause', false);
    $product_title_tag = get_query_var('et_product-title-tag', 'h2');
    $product_title_limit_type = get_query_var('et_product-title-limit-type', 'chars');
    $product_title_limit = get_query_var('et_product-title-limit', 0);
    $product_has_quantity = get_query_var('et_product-with-quantity', 0);
    $view_mode = get_query_var('et_view-mode', 'grid');
	
    $is_archive = get_query_var('et_is-woocommerce-archive', false);
    $is_masonry = get_query_var('et_is-products-masonry', false);
    $show_quick_view = get_query_var('et_is-quick-view', false);
    $advanced_stock = get_query_var('et_product-advanced-stock', false) && in_array('product_archives', (array) get_query_var('et_product-advanced-stock-locations', array('single_product'))) && 'yes' === get_option( 'woocommerce_manage_stock' );
}

if ( $variable_price_from ) {
 add_filter( 'woocommerce_format_price_range', function ( $price, $from, $to ) {
     return sprintf( '%s %s', esc_html__( 'From:', 'xstore' ), wc_price( $from ) );
 }, 10, 3 );
}

$size            = apply_filters( 'single_product_small_thumbnail_size', 'woocommerce_thumbnail' );
$show_stock      = !!$advanced_stock;
$show_counter = false;
$with_new_label = false;
$with_hot_label = false;
$product_type = $product->get_type();
$variation_product_detached = $variable_products_detach && $product_type == 'variation';
$atts = array();
$product_id = $product->get_ID();

if ( $product_new_label_range > 0 ) {
    $postdate        = apply_filters('product_new_label_on_date_created', $product_new_label_date_created) ?
            get_the_date( 'Y-m-d', $product_id ) :
            get_the_modified_date( 'Y-m-d', $product_id );
    $post_date_stamp = strtotime( $postdate );

    $with_new_label = ( time() - ( 60 * 60 * 24 * $product_new_label_range ) ) < $post_date_stamp;
}

if ( $product_featured_label ) {
	$with_hot_label = $product->is_featured();
}

$video_attachments = array();
$product_video = false;
$has_video = false;
$show_featured_as_video = false;
$video_slides_array = array();
$video_slides = '';
if ( etheme_get_custom_field('product_video_thumbnail') == 'disable' )
    $product_video_thumbnail = false;

if ( $product_video_thumbnail ) {
    $videos = etheme_get_attach_video($product_id);
        if (isset($videos[0]) && $videos[0] != '') {
            $video_attachments = get_posts(array(
                'post_type' => 'attachment',
                'include' => $videos[0]
            ));
            foreach ($video_attachments as $video) {
                if ( !$video || !in_array($video->post_mime_type, array('video/mp4', 'video/webm', 'video/ogg'))) continue;
                $video_slides_array[] = array(
                    'item_type' => 'video',
                    'src' => $video->guid,
                    'type' => str_replace(
                        array('video/mp4', 'video/webm', 'video/ogg'),
                        array(
                            'video/mp4; codecs="avc1.42E01E, mp4a.40.2"',
                            'video/webm; codecs="vp8, vorbis"',
                            'video/ogg; codecs="theora, vorbis"'
                        ),
                        $video->post_mime_type)
                );
            }
        }
    $has_video = count($video_attachments) > 0;

    $show_featured_as_video = $has_video || false;
}

if ( etheme_get_option( 'product_page_brands', 1 ) )
	$product_settings[] = 'product_page_brands';
$product_settings = apply_filters('etheme_product_content_settings', $product_settings);

 if ( isset( $woocommerce_loop['product_content_elements'] ) ) {
	 $product_settings = $woocommerce_loop['product_content_elements'];
	 if ( !in_array('product_page_swatches', $product_settings)) {
		 add_action('etheme_product_content_disable_swatches', '__return_true');
     }
 }
 
 if ( isset($woocommerce_loop['product_add_to_cart_quantity']) ) {
	 $product_has_quantity = $woocommerce_loop['product_add_to_cart_quantity'];
 }
 
// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( get_query_var('et_cat-cols') ) {
	$woocommerce_loop['columns'] = apply_filters('loop_shop_columns', get_query_var('et_cat-cols'));
}
elseif (empty( $woocommerce_loop['columns'] )) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

if ( (get_query_var('view_mode_smart', false) || isset($_GET['view_mode_smart'])) && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	if ( isset( $_GET['et_columns-count'] ) ) {
		$woocommerce_loop['columns'] = $_GET['et_columns-count'];
	}
	else {
		$view_mode_smart_active = get_query_var('view_mode_smart_active', 4);
		$woocommerce_loop['columns'] = $view_mode_smart_active != 'list' ? $view_mode_smart_active : 4;
		$view_mode = $view_mode_smart_active == 'list' ? 'list' : $view_mode;
	}
}

if ( ! empty( $woocommerce_loop['show_counter'] ) ) {
	$show_counter = $woocommerce_loop['show_counter'];
}

if ( ! empty( $woocommerce_loop['hover'] ) )
	$hover = $woocommerce_loop['hover'];

if ( ! empty( $woocommerce_loop['product_view'] ) )
	$view = $woocommerce_loop['product_view'];

// it could help to fix issue for product elements with list mode and overlay hover but break some settings
// so if use product element with list view then select another hover type
// if ( ! empty( $woocommerce_loop['view_mode'] ) )
//     $view_mode = $woocommerce_loop['view_mode'];
 
if ( ! empty( $woocommerce_loop['product_view_color'] ) )
	$view_color = $woocommerce_loop['product_view_color'];

if ( ! empty( $woocommerce_loop['size'] ) )
	$size = $woocommerce_loop['size'];

if ( isset( $woocommerce_loop['show_excerpt'] ) )
	$show_excerpt = $woocommerce_loop['show_excerpt'];

if ( isset( $woocommerce_loop['excerpt_length'] ) )
	$excerpt_length = $woocommerce_loop['excerpt_length'];

if ( ! empty( $woocommerce_loop['custom_template'] ) )
	$custom_template = $woocommerce_loop['custom_template'];

if ( ! empty( $woocommerce_loop['show_stock'] ) )
	$show_stock = true;

$sale_counter = etheme_get_custom_field('sale_counter');
 if ( in_array($sale_counter, array('all', 'list', 'single_list')) ) {
  
     // include scripts/style in case we are on grid but could switch to list with ajax and make countdown work too
	 wp_enqueue_script( 'et_countdown');
//	 if ( function_exists('etheme_enqueue_style')) {
//		 if ( class_exists( 'WPBMap' ) ) {
//			 etheme_enqueue_style( 'wpb-et-timer' );
//		 } else {
//			 etheme_enqueue_style( 'et-timer' );
//		 }
//	 }
	 
     if ( $view_mode == 'list' || $sale_counter == 'all' )
	    $show_counter = true;
 }

// Use single product option
$single = etheme_get_custom_field('single_thumbnail_hover');
if ( $single && $single != 'inherit' ) $hover = $single;

$product_view = etheme_get_custom_field('product_view_hover');
if ( $product_view && $product_view != 'inherit' ) $view = $product_view;

$product_view_color = etheme_get_custom_field('product_view_color');
if ( $product_view_color && $product_view_color != 'inherit' ) $view_color = $product_view_color;

if ( $variation_product_detached ) {
}
elseif ( ! $product->is_visible() ) {
    return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if (!$woocommerce_loop['columns'] || $woocommerce_loop['columns'] == 'inherit') {
	$woocommerce_loop['columns'] = 4;
}
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % intval($woocommerce_loop['columns']) || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
	$classes[] = 'grid-sizer';
}
if ( 0 === $woocommerce_loop['loop'] % intval($woocommerce_loop['columns']) ) {
	$classes[] = 'last';
}

if(!class_exists('YITH_WCWL')) {
	$classes[] = 'wishlist-disabled';
}

if ( !wc_get_loop_prop( 'etheme_default_elementor_products_widget', false ) )
    $classes[] = etheme_get_product_class( $woocommerce_loop['columns'] );

if ( ! empty( $woocommerce_loop['isotope'] ) && $woocommerce_loop['isotope'] || $is_masonry && $is_archive ) {
	$classes[] = 'et-isotope-item';
}

if ( !in_array($view, array('mask3', 'mask', 'mask2', 'info', 'default', 'overlay') ) ) {
	$view_color = 'dark';
}

// if ( in_array($view, array('mask3', 'mask2', 'info') ) ) {
//     $hover = 'disabled';
// }

if ( in_array($view, array('overlay'))) {
	$hover = 'disabled';
}

if ( ! empty( $woocommerce_loop['loading_class']) ) {
	$classes[] = $woocommerce_loop['loading_class'];
}

if ( $view != 'custom' || !$custom_template ) {
	$classes[] = 'product-hover-' . $hover;
	$classes[] = 'product-view-' . $view;
	$classes[] = 'view-color-' . $view_color;
	if ( in_array($hover, array('slider', 'carousel')) ) $classes[] = 'arrows-hovered';
}

if ($hover == 'carousel') {
    // include scripts/style in case we are on grid but could switch to list with ajax and make automatic hover work too
    wp_enqueue_script( 'et_product_hover_slider');
}

if ( in_array('product_page_addtocart', $product_settings) && ! $just_catalog ) {
	$classes[] = 'et_cart-on';
}
else {
	$classes[] = 'et_cart-off';
}
if ( ! in_array('hide_buttons_mobile', $product_settings) ) $classes[] = 'hide-hover-on-mobile';

$product_title = unicode_chars(get_the_title());
if ( $variation_product_detached ) {
	$product_title = unicode_chars( $product->get_name() );
}
$product_link = $product->get_permalink();

if ( $product_title_limit_type == 'chars' ) {
	if ( $product_title_limit && strlen( $product_title ) > $product_title_limit ) {
		$split         = preg_split( '/(?<!^)(?!$)/u', $product_title );
		$product_title = ( $product_title_limit != '' && $product_title_limit > 0 && ( count( $split ) >= $product_title_limit ) ) ? '' : $product_title;
		if ( $product_title == '' ) {
			for ( $i = 0; $i < $product_title_limit; $i ++ ) {
				$product_title .= $split[ $i ];
			}
			$product_title .= '...';
		}
	}
}

$excerpt = get_the_excerpt();
$excerpt_2 = '';
if ( $view_mode == 'grid' && $show_excerpt ) {
    if ( $variation_product_detached ) {
	    $custom_excerpt = $product->get_description();
	    if ( !empty($custom_excerpt)) {
		    $excerpt_2 = $custom_excerpt;
		    if ( $excerpt_length > 0 && strlen($excerpt_2) > 0 && ( strlen($excerpt_2) > $excerpt_length)) {
			    $excerpt_2         = substr($excerpt_2,0,$excerpt_length) . '...';
		    }
	    }
    }
	if ( $excerpt_length > 0 && strlen($excerpt) > 0 && ( strlen($excerpt) > $excerpt_length)) {
		$excerpt         = substr($excerpt,0,$excerpt_length) . '...';
	}
}

$product_type_quantity_types = apply_filters('etheme_product_type_show_quantity', array('simple', 'variable', 'variation'));

$with_quantity = false;

if (
	$product_has_quantity
	&& in_array('product_page_addtocart', $product_settings)
	&& in_array( $view, array('default', 'mask3', 'mask', 'mask2', 'overlay') )
	&& in_array($product_type, $product_type_quantity_types)
	&& !$just_catalog
	&& $product->is_in_stock()
){
	$with_quantity = true;
}


if ( $just_catalog ) {
	etheme_before_fix_just_catalog_link();
}

if ( !in_array('product_page_productrating', $product_settings ) ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
}

add_filter( 'single_product_archive_thumbnail_size', function($old_size) use ($size){
	return $size;
} );

$hover = (in_array($hover, array('swap', 'back-zoom-in', 'back-zoom-out', 'zoom-in')) && (get_query_var('is_mobile', false) || $show_featured_as_video)) ? 'none' : $hover;
if ( $just_catalog && etheme_get_option( 'just_catalog_price', 0 ) && etheme_get_option( 'ltv_price', esc_html__( 'Login to view price', 'xstore' ) ) == '' ){
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
}

if ( $show_featured_as_video && in_array($hover, array('slider', 'carousel'))) {
    $hover = 'slider'; // force carousel => slider hover
    $video_slides = wp_json_encode(
        array(
            'items' => array_merge($video_slides_array, array_map(
                function ($item_value) {
                    return array(
                        'item_type' => 'image',
                        'src' => $item_value
                    );
                }, explode(';', etheme_get_image_list($size))))
        ));
}


$html_wrapper_tag = 'div';
if ( wc_get_loop_prop( 'etheme_default_elementor_products_widget', false ) ) {
    $html_wrapper_tag = 'li';
}

$product_attributes = false;
if ( $show_attributes_on_hover ) {
    ob_start();
    wc_display_product_attributes($product);
    $product_attributes = ob_get_clean();
}
if ( $product_attributes ) {
    $classes[] = 'product-has-fadeIn-content';
}

?>
	<<?php echo esc_html($html_wrapper_tag); ?> <?php wc_product_class( $classes, $product ); ?>>
        <?php
            // in case if custom product view specified for this product // load in this place to prevent css nth-child on grid
            if ( !in_array($view, array('disable', 'custom')) ) {
                etheme_enqueue_style('product-view-'.$view );
            }
        if ( in_array($sale_counter, array('all', 'list', 'single_list')) ) {

	        // include scripts/style in case we are on grid but could switch to list with ajax and make countdown work too
//            wp_enqueue_script( 'et_countdown');
	        if ( function_exists( 'etheme_enqueue_style' ) ) {
		        if ( class_exists( 'WPBMap' ) ) {
			        etheme_enqueue_style( 'wpb-et-timer' );
		        } else {
			        etheme_enqueue_style( 'et-timer' );
		        }
	        }
        }
        ?>
		<div class="content-product <?php if ($view == 'custom' && $custom_template != '') echo 'custom-template clearfix et-product-template-'.$custom_template; ?>">
			<?php // etheme_loader(); ?>

			<?php if ( $view == 'custom' && $custom_template != '' ) {

				$args = array( 'include' => $custom_template,'post_type' => 'vc_grid_item', 'posts_per_page' => 1);
				$myposts = get_posts( $args );

				if ( is_array($myposts) && isset($myposts[0]) && class_exists('ET_product_templates')) {
					$block = $myposts[0];

					$templates = new ET_product_templates();

					$content = $block->post_content;
					$templates->setTemplateById($custom_template);
					// $templates->setTemplateById($content, $custom_template);
					$shortcodes = $templates->shortcodes();
					$templates->mapShortcodes();
					WPBMap::addAllMappedShortcodes();

					$attr = ' width="' . $templates->gridAttribute( 'element_width', 12 ) . '"'
					        . ' is_end="' . ( 'true' === $templates->isEnd() ? 'true' : '' ) . '"';
					$content = preg_replace( '/(\[(\[?)vc_gitem\b)/', '$1' . $attr, $content );
					echo ( 1 === ( $woocommerce_loop['loop'] - 1 ) ) ? $templates->addShortcodesCustomCss($custom_template) : '';

					echo $templates->renderItem( get_post( (int) $product_id ), $content); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				else {
					echo '<div class="woocommerce-info">' . sprintf( esc_html__('To use this element you have to create custom product template via %1$1s. For more details %2$2s.', 'xstore'), '<a href="https://kb.wpbakery.com/docs/learning-more/grid-builder/" rel="nofollow" target="_blank">' . esc_html__('WPBakery page builder', 'xstore') . '</a>', '<a href="https://youtu.be/ER2njPVmsnk" rel="nofollow" target="_blank">' . esc_html__('Watch the tutorial', 'xstore') . '</a>' ) . '</div>';
				}
			}
			else {

				/**
				 * woocommerce_before_shop_loop_item hook.
				 *
				 * @hooked woocommerce_template_loop_product_link_open - 10
				 */
				if ( $view_mode == 'grid' && $view != 'booking' ) {
					remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
					do_action( 'woocommerce_before_shop_loop_item' );
					add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
				}

                if ( $with_new_label ) {
                    ?>
                    <div class="sale-wrapper">
                        <span class="onsale left new-label"><?php echo esc_html__('New', 'xstore'); ?></span>
                    </div>
                    <?php
                }

				if ( $with_hot_label ) {
					?>
                    <div class="sale-wrapper">
                        <span class="onsale left hot-label"><?php echo esc_html__('Hot', 'xstore'); ?></span>
                    </div>
					<?php
				}

				?>

				<div class="product-image-wrapper hover-effect-<?php echo esc_attr( $hover ); ?><?php if ( $show_featured_as_video ) : ?> product-video-as-image<?php endif; ?>"
                    <?php if ($show_featured_as_video && in_array('autoplay', $product_video_attrs) && $product_video_pause_on_hover ) echo ' data-video-hover-state="pause"';
                    else if ($show_featured_as_video && !in_array('autoplay', $product_video_attrs)) echo ' data-video-hover-state="play"'; ?>>

					<?php if ( $view == 'overlay' ): ?>
                        <div class="quick-buttons">
							<?php
							if ( in_array('product_page_addtocart', $product_settings) && ! $just_catalog ) {
								add_filter( 'woocommerce_product_add_to_cart_text', '__return_false' );
								do_action( 'woocommerce_after_shop_loop_item' );
								remove_filter( 'woocommerce_product_add_to_cart_text', '__return_false' );
							}
							echo etheme_wishlist_btn(); ?>
                        </div>
                        <?php if ($show_quick_view) { ?>
                            <span class="show-quickly" data-prodid="<?php echo esc_attr($product_id);?>"><?php esc_html_e('Quick View', 'xstore') ?></span>
                        <?php }
						endif; ?>
					<?php if ( in_array($view, array('default')) ) echo etheme_wishlist_btn();

					if ( $view != 'booking' ) etheme_product_availability();
					if ( in_array($hover, array('slider', 'carousel')) ) echo '<div class="images-slider-wrapper">'; ?>
                        <a class="product-content-image woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo esc_url($product_link); ?>"
                           data-images="<?php echo ( 'slider' == $hover && !$show_featured_as_video ) ? etheme_get_image_list( $size ) : ''; ?>"
                        <?php if ( 'slider' == $hover && $show_featured_as_video ) : ?>data-videos="<?php echo esc_attr($video_slides); ?>"<?php endif; ?>>
                            <?php if ( $view_mode == 'list' || $view == 'booking' ) {
                                if ($view == 'booking') {
                                    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
                                }
                                do_action( 'woocommerce_before_shop_loop_item' );
                            }
                            if ( $view == 'booking' ) etheme_product_availability();
                            if( in_array($hover, array('swap', 'back-zoom-in', 'back-zoom-out')) ) etheme_get_second_image( $size );
                            // echo woocommerce_get_product_thumbnail( $size );

                            /**
                             * woocommerce_before_shop_loop_item_title hook.
                             *
                             * @hooked woocommerce_show_product_loop_sale_flash - 10
                             * @hooked woocommerce_template_loop_product_thumbnail - 10
                             */
                            // set required attributes for hover effect
                            if ('carousel' == $hover) {
                                $carousel_images = etheme_get_image_list($size, false);
                                add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) use ($carousel_images) {
                                    if ($carousel_images) {
                                        $attr['data-hover-slides'] = str_replace(';', ',', $carousel_images);
                                        $attr['data-options'] = '{"touch": "end", "preloadImages": true }';
                                        if ( isset($attr['class']))
                                            $attr['class'] .= ' main-hover-slider-img';
                                        else
                                            $attr['class'] = 'main-hover-slider-img';
                                    }
                                    return $attr;
                                }, 5, 3);
                            }
                            if ( $show_featured_as_video ) {
                                $product_video_params = array(
                                    'muted="muted"'
                                );
                                if ( in_array('preload', $product_video_attrs))
                                    $product_video_params[] = 'preload="auto"';
                                if ( in_array('loop', $product_video_attrs))
                                    $product_video_params[] = 'loop="true"';
                                if ( in_array('autoplay', $product_video_attrs)) {
                                    $product_video_params[] = 'autoplay="true"';
                                    if ($product_video_pause_on_hover)
                                        $product_video_params[] = 'data-pause-on-hover="true"';
                                }
                                $video_poster = '';
                                if ( in_array('poster', $product_video_attrs) ) {
                                    if ($product->get_image_id()) {
                                        $video_poster = wp_get_attachment_image_src($product->get_image_id(), $size, false, array())[0];
                                    } elseif ($product->get_parent_id()) {
                                        $video_poster = wp_get_attachment_image_src($product->get_parent_id(), $size, false, array())[0];
                                    }

                                    if (!$video_poster) {
                                        $video_poster = wc_placeholder_img_src($size, array());
                                    }

                                    if ( $video_poster )
                                        $product_video_params[] = 'poster="'.$video_poster.'"';

                                }
                                if ( $hover == 'none' && $product_type != 'variable' ) {
                                    remove_filter('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
                                }
                                else {
//                                if ('slider' == $hover) {
                                    add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
                                        $attr['class'] = isset($attr['class']) ? $attr['class'] . ' hidden' : 'hidden';
                                        return $attr;
                                    }, 5, 3);
//                                }
                                }
                                if ( count( $video_attachments ) > 0 ): $video_sources_shown = 0;?>
                                    <video <?php echo implode(' ', $product_video_params); ?>>
                                        <?php foreach ( $video_attachments as $video ):
                                            if ( $video_sources_shown > 0 ) break; ?>
                                            <?php $video_ogg = $video_mp4 = $video_webm = false; ?>
                                            <?php if ( $video->post_mime_type == 'video/mp4' && ! $video_mp4 ): $video_mp4 = true; ?>
                                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                                            <?php
                                            $video_sources_shown++;
                                            endif; ?>
                                            <?php if ( $video->post_mime_type == 'video/webm' && ! $video_webm ): $video_webm = true; ?>
                                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                                        type='video/webm; codecs="vp8, vorbis"'>
                                            <?php
                                            $video_sources_shown++;
                                            endif; ?>
                                            <?php if ( $video->post_mime_type == 'video/ogg' && ! $video_ogg ): $video_ogg = true; ?>
                                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                                        type='video/ogg; codecs="theora, vorbis"'>
                                                <?php esc_html_e( 'Video is not supporting by your browser', 'xstore' ); ?>
                                            <?php
                                            $video_sources_shown++;
                                            endif; ?>
                                        <?php endforeach; ?>
                                    </video>
                                <?php endif;
                            }

                            do_action( 'woocommerce_before_shop_loop_item_title' );
                            if ($show_featured_as_video) {
                                if ( $hover == 'none' && $product_type != 'variable' ) {
                                    add_filter('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
                                }
                                else {
//                                if ('slider' == $hover) {
                                    add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
                                        $attr['class'] = str_replace('hidden', '', $attr['class']);
                                        return $attr;
                                    }, 5, 3);
//                                }
                                }
                            }
                            // reset required attributes for hover effect
                            if ('carousel' == $hover) {
                                add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
                                    if (isset($attr['data-hover-slides']))
                                        unset($attr['data-hover-slides']);
                                    if (isset($attr['data-options']))
                                        unset($attr['data-options']);
                                    $attr['class'] = str_replace('main-hover-slider-img', '', $attr['class']);
                                    return $attr;
                                }, 5, 3);
                            }
                            ?>
                        </a>
					<?php if ( in_array($hover, array('slider', 'carousel')) ) echo '</div>';

					if ( $view == 'booking' && $view_mode != 'list' && in_array('product_page_productname', $product_settings) ): ?>
						<<?php echo esc_attr($product_title_tag); ?> class="product-title">
							<a href="<?php echo esc_url($product_link); ?>"><?php echo wp_specialchars_decode($product_title); ?></a>
						</<?php echo esc_attr($product_title_tag); ?>>
					<?php endif;

					if ($view == 'info'): ?>
						<div class="product-mask">

							<?php if (in_array('product_page_productname', $product_settings)): ?>
                                <<?php echo esc_attr($product_title_tag); ?> class="product-title">
									<a href="<?php echo esc_url($product_link); ?>"><?php echo wp_specialchars_decode($product_title); ?></a>
                                </<?php echo esc_attr($product_title_tag); ?>>
							<?php endif;


							/**
							 * woocommerce_after_shop_loop_item_title hook
							 *
							 * @hooked woocommerce_template_loop_rating - 5
							 * @hooked woocommerce_template_loop_price - 10
							 */
							if (in_array('product_page_price', $product_settings)) {
								do_action( 'woocommerce_after_shop_loop_item_title' );
							}
							?>
						</div>
					<?php endif;

					if ( $view == 'booking' && $view_mode != 'list' ):
                        if ( in_array('product_page_price', $product_settings) ) do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
						<div class="product-excerpt">
							<?php echo do_shortcode($excerpt); ?>
						</div>
                        <?php if ( $excerpt_2 != '') { ?>
                            <div class="product-excerpt">
                                <?php echo do_shortcode($excerpt_2); ?>
                            </div>
                        <?php } ?>
						<div class="product-excerpt">
							<?php do_action( 'woocommerce_product_additional_information', $product ); ?>
						</div>
						<?php
						if (in_array('product_page_addtocart', $product_settings) && $view != 'booking' && !$just_catalog ) {
							do_action( 'woocommerce_after_shop_loop_item' );
						}
                        endif;

					if ( in_array($view, array('mask', 'mask2', 'mask3', 'default', 'info') ) ):
                        $built_in_compare = get_theme_mod('xstore_compare', false) && class_exists('\XStoreCore\Modules\WooCommerce\XStore_Compare');
                        $built_in_compare_instance = $built_in_compare ? XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance() : ''; ?>
						<footer class="footer-product">
                            <?php if ($built_in_compare)
                                add_filter('xstore_compare_product_settings', array($built_in_compare_instance, 'compare_btn_only_icon'), 999, 2);
							if ( $view == 'mask3' ):
								echo etheme_wishlist_btn();
							else:
								if ($show_quick_view): ?>
									<span class="show-quickly" data-prodid="<?php echo esc_attr($product_id);?>"><?php esc_html_e('Quick View', 'xstore') ?></span>
								<?php endif;
							endif;

							if ( !in_array($view, array('default', 'overlay')) ) {
								//if (in_array('product_page_addtocart', $product_settings)) {
								remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
								add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
								do_action( 'woocommerce_after_shop_loop_item' );
								//}
							}
							if ( $view == 'mask3' ):
                                if ($show_quick_view): ?>
									<span class="show-quickly" data-prodid="<?php echo esc_attr($product_id);?>"><?php esc_html_e('Quick View', 'xstore') ?></span>
                                <?php endif;

                            elseif (!in_array($view, array('default'))):
                                echo etheme_wishlist_btn();

							endif; ?>
                            <?php if ($built_in_compare)
                                remove_filter('xstore_compare_product_settings', array($built_in_compare_instance, 'compare_btn_only_icon'), 999, 2);

                            unset($built_in_compare);
                            unset($built_in_compare_instance);
                            ?>
						</footer>
					<?php endif ?>
				</div>

				<?php if ( ( !in_array($view, array('info', 'booking')) || $view_mode == 'list' ) ):

					if ( $with_quantity ) {
						remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
						add_filter('woocommerce_product_add_to_cart_text', '__return_false');
						remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
						remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
						add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
						add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
					} ?>

					<div class="<?php if ( $view != 'light' ) : ?>text-center <?php endif; ?>product-details"><?php do_action( 'et_before_shop_loop_title' );

						if ( $view == 'light' ) echo '<div class="light-left-side">';

						if (in_array('product_page_cats', $product_settings)):
							etheme_product_cats();
						endif;

						if (in_array('product_page_productname', $product_settings)): ?>
							<<?php echo esc_attr($product_title_tag); ?> class="product-title">
								<a href="<?php echo esc_url($product_link); ?>"><?php echo wp_specialchars_decode($product_title); ?></a>
                            </<?php echo esc_attr($product_title_tag); ?>>
						<?php endif;

						etheme_dokan_seller();

						if ( $enabled_brands && in_array('product_page_brands', $product_settings) ) :
							etheme_product_brands();
						endif;

						if (in_array('product_page_product_sku', $product_settings) &&
						          wc_product_sku_enabled() && ( $product->get_sku() || $product_type == 'variable' )): ?>
							<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore' ); ?>
                        <span class="sku"><?php echo esc_html( ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore' ) ); ?></span>
                    </span>
						<?php endif;

						/**
						 * woocommerce_after_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_template_loop_rating - 5
						 * @hooked woocommerce_template_loop_price - 10
						 */

						if ( in_array('product_page_price', $product_settings) ) :
							if ( $view != 'light' ) :
								do_action( 'woocommerce_after_shop_loop_item_title' );
								do_action( 'et_after_shop_loop_title' );

                            else :
								if ( in_array('product_page_productrating', $product_settings ) ) {
                                    woocommerce_template_loop_rating();
								}
								do_action( 'et_after_shop_loop_title' );
								if ( $view_mode != 'list'  ): ?>
								<div class="switcher-wrapper">
									<div class="price-switcher">
										<div class="price-switch">
											<?php
											remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
											do_action( 'woocommerce_after_shop_loop_item_title' );
											add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
											?>
										</div>
										<div class="button-switch">
											<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
										</div>
									</div>
								</div>
							<?php endif;
							endif;
						endif;
						if ( $view_mode == 'list' ): ?>
							<div class="product-excerpt">
								<?php echo do_shortcode($excerpt); ?>
							</div>
							<?php if ( $excerpt_2 != '') { ?>
                                <div class="product-excerpt">
									<?php echo do_shortcode($excerpt_2); ?>
                                </div>
							<?php } ?>
						<?php endif ?>

						<?php if ( $view == 'light' && $view_mode == 'list' ) : ?>
							<div class="switcher-wrapper">
								<div class="price-switcher">
									<div class="price-switch">
										<?php woocommerce_template_loop_price(); ?>
									</div>
									<div class="button-switch">
										<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
									</div>
								</div>
							</div>
						<?php endif;


						if ( $show_stock && 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
//							$id = $product->get_ID(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
//							$product = wc_get_product($product_id);
							echo et_product_stock_line($product);
						}

						if( $show_counter ) etheme_product_countdown('type2', false);

                        if ( $view_mode == 'grid' && $show_excerpt ): ?>
                            <div class="product-excerpt">
                                <?php echo do_shortcode($excerpt); ?>
                            </div>
                            <?php if ( $excerpt_2 != '') { ?>
                                <div class="product-excerpt">
                                    <?php echo do_shortcode($excerpt_2); ?>
                                </div>
                            <?php }

                        endif;

						if ( in_array('product_page_addtocart', $product_settings) && ! $just_catalog) {
							if ( ! in_array( $view, array( 'mask', 'mask3', 'light', 'overlay' ) ) ) {
								do_action( 'woocommerce_after_shop_loop_item' );
							}
							if ( $with_quantity && ! ($product_type == 'variable' && etheme_get_option( 'swatch_layout_shop', 'default' ) == 'popup') ) {
                                $q_args = array(
                                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                );

                                if (
                                    $product->managing_stock()
                                    // $product->get_stock_quantity()
                                ) {
                                    $q_args['max_value'] = $product->get_stock_quantity();
                                }

                                echo '<div class="quantity-wrapper">';
                                woocommerce_quantity_input( $q_args, $product, true );
                                woocommerce_template_loop_add_to_cart();
                                echo '</div>';
							}
						}

						if ( $view == 'light' ) echo '</div><!-- .light-left-side -->'; ?>

                        <?php if ( $view == 'light' ) : ?>
                            <div class="light-right-side">
                                <?php if ($show_quick_view): ?>
                                    <span class="show-quickly" data-prodid="<?php echo esc_attr($product_id);?>"><?php esc_html_e('Quick View', 'xstore') ?></span>
                                <?php endif;

                                echo etheme_wishlist_btn(); ?>
                            </div><!-- .light-right-side -->
                        <?php endif; ?>

                    <?php
                        if ( $product_attributes ) {?>
                            <div class="product-fadeIn-area">
                                <div class="product-more-info">
                                    <?php echo '<div class="product-more-info-inner limited-height">'.
                                        $product_attributes.
                                    '</div>'; ?>
                                    <a href="#" rel="nofollow" class="product-more-info-btn text-left block hidden">
                                        <span><?php echo esc_html__( 'Read more', 'xstore' ); ?></span>
                                        <span class="et-icon et_b-icon et-down-arrow" style="font-size: .85em"></span>
                                    </a>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                    </div><?php // .product-details ?>

					<?php
					if ( $with_quantity ) {
						add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
						remove_filter('woocommerce_product_add_to_cart_text', '__return_false');
					} ?>
				<?php endif;
			} // end if not custom template ?>
         <?php
            if ( $product_attributes )
                echo '<div class="product-spacer"></div>';
         ?>
		</div><!-- .content-product -->
	</<?php echo esc_html($html_wrapper_tag); ?>>

<?php if ( $just_catalog ) {
	etheme_after_fix_just_catalog_link();
}

if ( !in_array('product_page_productrating', $product_settings ) ) {
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
}

remove_action('etheme_product_content_disable_swatches', '__return_true');