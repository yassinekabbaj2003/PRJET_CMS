<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

class Product_Category_Heading_Image extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'etheme_product_category_heading_image-tag';
    }

    public function get_title() {
        return __( 'Product Category Heading Image', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
    }

    protected function register_controls() {
        $this->add_control(
            'fallback_image',
            [
                'label' => __( 'Fallback Image', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );
    }

    public function get_value( array $options = [] ) {
        $settings = $this->get_settings_for_display();
        $category_id = 0;

        $is_category = is_tax('product_cat');
        $is_tag = is_tax('product_tag');

        if ( $is_category || $is_tag ) {
            $category_id = get_queried_object_id();
        }
//
        if ( $category_id ) {
            $image_id = get_term_meta( $category_id, '_et_page_heading_id', true );
            if ( empty($image_id) ) {
                $image = $settings['fallback_image'];
                if ( ! empty( $image['id'] ) ) {
                    $image_id = $image['id'];
                }
            }
        }

        if ( empty( $image_id ) ) {
            return [];
        }

        $src = wp_get_attachment_image_src( $image_id, 'full' );

        return [
            'id' => $image_id,
            'url' => $src[0],
        ];
    }

}