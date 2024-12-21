<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

/**
 * Product title widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Title extends \ElementorPro\Modules\Woocommerce\Widgets\Product_Title {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_title';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-product-title et-elementor-product-widget-icon-only';
	}
	
	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
}
