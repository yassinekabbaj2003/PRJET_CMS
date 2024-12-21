<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

class Product_Brand_Image extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'etheme_product_brand_image-tag';
    }

    public function get_title() {
        return __( 'Product Brand Image', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
    }

    public function get_value( array $options = [] ) {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // force get origin option value in editor but in other cases as query
        $enabled_brands = $edit_mode ? get_theme_mod('enable_brands', true) : get_query_var('et_brands', true);

        if ( !$enabled_brands ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(
                    sprintf(esc_html__('To use %s dynamic tag you should enable brands in Theme options -> Shop elements -> Brands. Shown only in Elementor Editor.', 'xstore-core'),
                        '<strong>'.$this->get_title().'</strong>')
                );
            }
            return [];
        }
        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return [];
        }

        $terms = wp_get_post_terms( $product->get_ID(), 'brand' );

        if ( count( $terms ) < 1 || !isset($terms[0]) || is_wp_error($terms[0]) ) {
            return [];
        }

        $image_id = absint( get_term_meta( $terms[0]->term_id, 'thumbnail_id', true ) );
        if ( !$image_id )
            return [];
        $src = wp_get_attachment_image_src( $image_id, 'full' );

        return [
            'id' => $image_id,
            'url' => $src[0],
        ];

    }

}