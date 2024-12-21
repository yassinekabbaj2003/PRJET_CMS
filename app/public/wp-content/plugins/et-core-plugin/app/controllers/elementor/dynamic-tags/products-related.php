<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

class Products_Related extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'etheme-products-related-tag';
    }

    public function get_title() {
        return __( 'Products related', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ 'etheme_ajax_product' ];
    }

    public function get_value( array $options = [] ) {
        global $product;

        $product = Elementor::get_product();

        if ( ! $product ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo Elementor::elementor_frontend_alert_message();
            }

            return '';
        }

        return implode(',', wc_get_related_products( $product->get_id() ));
    }

}