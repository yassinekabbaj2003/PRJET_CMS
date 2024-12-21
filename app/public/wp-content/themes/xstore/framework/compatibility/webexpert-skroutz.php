<?php
// Compatibility with WooCommerce Skroutz & BestPrice XML Feed
// Adds variation gallery images for xml file on creation
add_filter('webexpert_skroutz_xml_custom_gallery','etheme_compatibility_webexpert_skroutz_xml_variation_gallery_images',20,2);

/**
 * Returns variation gallery images ids.
 *
 * @param $gallery_image_ids
 * @param $product
 * @return array
 *
 * @since 8.1.5
 *
 */
function etheme_compatibility_webexpert_skroutz_xml_variation_gallery_images($gallery_image_ids,$product) {
    if ( $product->is_type( 'variation' ) ) {
        $has_variation_gallery_images = get_post_meta( $product->get_id(), 'et_variation_gallery_images', true );
        if ( (bool)$has_variation_gallery_images ) {
            $gallery_images = (array) $has_variation_gallery_images;
            return $gallery_images;
        }

        // Compatibility with WooCommerce Additional Variation Images plugin
        $has_variation_gallery_images_wc_additional_variation_images = get_post_meta( $product->get_id(), '_wc_additional_variation_images', true );
        if ( (bool)$has_variation_gallery_images_wc_additional_variation_images ) {
            $gallery_images = (array) array_filter( explode( ',', $has_variation_gallery_images_wc_additional_variation_images));
            return $gallery_images;
        }
        return $gallery_image_ids;
    }
    return $gallery_image_ids;
}