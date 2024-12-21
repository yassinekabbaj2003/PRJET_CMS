<?php
/**
 * Product Loop End
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.0.0
 * @xstore-version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

remove_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );

$tag = 'div';
if ( wc_get_loop_prop( 'etheme_default_elementor_products_widget', false ) ) {
    $tag = 'ul';
}
?>
<?php if ( !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) && (etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'shop_page_pagination_type_et-desktop', 0 )) ): ?>
	</div>
<?php endif; ?>
</<?php echo esc_html($tag); ?>> <!-- .row -->