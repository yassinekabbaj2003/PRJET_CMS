<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

class Sales_Booster_Estimated_Delivery extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'etheme_sales_booster_estimated_delivery-tag';
    }

    public function get_title() {
        return __( 'Estimated Delivery (Sales Booster)', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    public function render() {
        global $product;

        $product = Elementor::get_product();

        if ( ! $product ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return '';
        }

        if ( !class_exists('\Etheme_Sales_Booster_Estimated_Delivery') ) return '';

        $estimated_delivery = \Etheme_Sales_Booster_Estimated_Delivery::get_instance();
        $estimated_delivery->init($product);
        $estimated_delivery->args['in_quick_view'] = true;
        $estimated_delivery->add_actions();
        $estimated_delivery->output();
    }

}