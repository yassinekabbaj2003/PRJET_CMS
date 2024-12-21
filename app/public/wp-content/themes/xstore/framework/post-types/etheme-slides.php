<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');
// **********************************************************************// 
// ! Etheme Slides Post Type
// **********************************************************************// 
if ( !function_exists('etheme_slides_slide_wrapper') ) {
    function etheme_slides_slide_wrapper($slide, $edit_mode = false) {
        $attributes = array(
            'data-etheme_slide_template_id' => $slide->ID,
            'class' => 'swiper-slide-contents'
        );
        $attributes_ready = array();
        foreach ($attributes as $attribute_key => $attribute_value) {
            $attributes_ready[] = $attribute_key . '=' . (is_array($attribute_value) ? implode(' ', $attribute_value) : $attribute_value);
        }
        echo '<style type="text/css">'.etheme_slides_slide_style($slide->ID, $edit_mode).'</style>';
        echo '<div '.implode(' ', $attributes_ready).'>';
            the_content();
        echo '</div>';
    }
}

if ( !function_exists('etheme_slides_slide_style') ) {
    function etheme_slides_slide_style($slide_id, $edit_mode = false) {
        $post_type = 'etheme_slides';
        $all_possible_properties = array(
            'background-color',
            'background-repeat',
            'background-position',
            'background-size'
        );

        $css = array();

        $css_selectors = array(
//            'slide' => !$edit_mode ? "[data-etheme_slide_template_id='{$slide_id}'] .swiper-slide-contents" : '.swiper-slide-contents',
            'slide' => "[data-etheme_slide_template_id='{$slide_id}']",
        );
        $css_selectors['body'] = 'body:has('.$css_selectors['slide'].')';
        $css_selectors['page-wrapper'] = '.page-wrapper:has('.$css_selectors['slide'].')';
        $css_selectors['slide-inner'] = $css_selectors['slide'] . ' > [data-elementor-id]';
        $css_selectors['editor-area'] = $css_selectors['slide'] . ' .elementor-edit-area-active';

        foreach ($all_possible_properties as $possible_property) {
            $possible_property_value = get_post_meta( $slide_id, $post_type . '_' . str_replace('-', '_', $possible_property), true );
            if ( !$possible_property_value ) {
                switch ($possible_property) {
                    case 'background-color':
                        $possible_property_value = '';
                        break;
                    case 'background-repeat':
                        $possible_property_value = 'no-repeat';
                        break;
                    case  'background-position':
                        $possible_property_value = 'center center';
                        break;
                    case 'background-size':
                        $possible_property_value = 'cover';
                        break;
                }
            }
            if ( $possible_property_value )
                $css['slide'][] = $possible_property . ':' . $possible_property_value;
        }

//        $bg_color           = get_post_meta( $slide_id, 'bg_color', true );
        $bg_image_desktop      = has_post_thumbnail( $slide_id ) ? wp_get_attachment_url( get_post_thumbnail_id( $slide_id ) ) : '';
//        $meta_bg_image_desktop = get_post_meta( $slide_id, 'bg_image_desktop', true );
//        if ( is_array( $meta_bg_image_desktop ) && isset( $meta_bg_image_desktop['url'] ) ) {
//            $meta_bg_image_desktop = $meta_bg_image_desktop['url'];
//        }
//        if ( $meta_bg_image_desktop ) {
//            $bg_image_desktop = $meta_bg_image_desktop;
//        }
//        $bg_image_size_desktop       = get_post_meta( $slide_id, 'bg_image_size_desktop', true );
//        $bg_image_position_desktop   = get_post_meta( $slide_id, 'bg_image_position_desktop', true );
//        $bg_image_position_x_desktop = get_post_meta( $slide_id, 'bg_image_position_x_desktop', true );
//        $bg_image_position_y_desktop = get_post_meta( $slide_id, 'bg_image_position_y_desktop', true );

        $css['slide'][] = 'display: flex';
        $css['slide'][] = 'align-items: center';
        $css['slide'][] = 'justify-content: center';
        if ( $bg_image_desktop ) {
            $css['slide'][] = 'background-image: url(' . $bg_image_desktop . ')';
//        if ( $edit_mode )
            $css['slide'][] = 'min-height: 420px';
        }

        $css['slide-inner'][] = 'flex: 1;';
        if ( $edit_mode ) {
            $css['editor-area'][] = 'flex: 1';
            $css['slide'][] = 'flex: 1';
            $css['body'] = array(
                'display: flex',
                'flex-direction: column',
                'justify-content: center',
                'margin-bottom: 0',
                'padding-top: 20px',
                'padding-bottom: 10px',
                'min-height: 100vh',
            );
        }

        $output_css = '';
        foreach ($css as $css_selector => $selector_style) {
            $output_css .= $css_selectors[$css_selector] . '{' . implode(';', $selector_style) . ' }';
        }
        return $output_css;
    }
}