<?php
/**
 * The shop public facing functionality.
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/public/partials
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Shop extends St_Woo_Swatches_Base {
	/**
	 * Just define $plugin_public_dir_path to avoid php 8.2.+ notices.
	 *
	 * @static
	 * @access protected
	 * @var string
	 */
	public $plugin_public_dir_path = '';

    public function __construct( $args ) {
        parent::__construct();

        if (isset($args['plugin_public_dir_path'])){
	        $this->plugin_public_dir_path = $args['plugin_public_dir_path'];
        }

//        if (!empty($args)) {
//            foreach ($args as $property => $arg) {
//            	var_dump($property);
//                $this->{$property} = $arg;
//            }
//        }

        // @todo check if it's ok because actions are different and it adds open/close link in diff places
//		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
//		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );

        add_action( 'init', array( $this, 'etheme_shop_swatches_position' ) );

        add_shortcode( 'st-swatch-shortcode', array( &$this, 'loop_swatch' ) );
        add_action( 'loop_swatch', array( &$this, 'loop_swatch' ), 10, 2 );

        add_filter( 'sten_wc_archive_loop_available_variations', array( &$this, 'available_variations' ) );

        add_filter( 'sten_wc_archive_loop_swatch_html', array( &$this, 'swatch_html' ), 10, 10 );

        add_action( 'wp_ajax_nopriv_sten_wc_product_loop_add_to_cart', array( &$this, 'add_to_cart' ) );
        add_action( 'wp_ajax_sten_wc_product_loop_add_to_cart', array( &$this, 'add_to_cart' ) );
    }

    public function etheme_shop_swatches_position(){
        $view_mode 	 = get_query_var('et_view-mode');
        $swatch_hook = function_exists('etheme_get_option') ? etheme_get_option( 'swatch_position_shop', 'before' ) : 'before';
        if ( $view_mode == 'list' ) {
            return;
        } elseif( $swatch_hook == 'before' ){
            add_action( 'et_before_shop_loop_title', function(){ do_action( 'loop_swatch', 'normal' ); }, 1 );
        } elseif( $swatch_hook == 'after' ){
            add_action( 'et_after_shop_loop_title', function(){ do_action( 'loop_swatch', 'normal' ); }, 9 );
        }

        add_action( 'et_quick_view_swatch', function(){ do_action( 'loop_swatch', 'large', true ); }, 10 );

    }

    public function loop_swatch( $size = 'normal', $is_quick_view = false ) {

        // disable swatches if loaded popup after added to cart @see woocommerce/content-product.php
        if (did_action('etheme_product_added_popup') || has_action('etheme_product_content_disable_swatches', '__return_true') ) {
            return;
        }

        global $product;

        if( ! $product->is_type( 'variable' ) ) return;

        $available_variations = apply_filters( 'sten_wc_archive_loop_available_variations', $product->get_available_variations() );

        $sw_popup = get_theme_mod( 'swatch_layout_shop', 'default' ) == 'popup';
        if ( $is_quick_view )
            $sw_popup = false;
        $sw_design = get_theme_mod('swatch_design', 'default');
        $sw_disabled_design = get_theme_mod('swatch_disabled_design', 'line-thought');
        $loop_class = '';

        if ( $sw_popup ) {
            $loop_class .= ' st-swatch-popup';
        }
        if ( $sw_design != 'default' )
            $loop_class .= ' st-swatch-'.$sw_design;

        if ( $sw_disabled_design != 'default' )
            $loop_class .= ' st-swatch-disabled-'.$sw_disabled_design;

        if( empty( $available_variations ) )
            return;

        $html = '';

        $html .= sprintf('<div class="st-swatch-in-loop%3$s" data-product_id="%1$s" data-product_variations="%2$s">',
            esc_attr( absint( $product->get_id() ) ),
            htmlspecialchars( wp_json_encode( $available_variations ) ),
            esc_attr($loop_class)
        );

        $attributes = $product->get_variation_attributes();

        $is_only_select = true;
        $show_select_swatch = get_theme_mod('swatch_select_type_shop', false);

        $variations_images = get_theme_mod('images_from_variations', 0);
        $variations = ($variations_images || $show_select_swatch) ? $product->get_available_variations() : false;

        $attributes_types_list = $this->attribute_types;
        if ( $show_select_swatch ) {
            $attributes_types_list['select'] = esc_html__('Select', 'xstore-core');
        }
        $attributes_count = array();

        foreach( array_keys( $attributes ) as $taxonomy ) {

            $attribute = $this->get_tax_attribute( $taxonomy, true );
            $attribute_type = $attribute['attribute_type'];
            $attribute_label = $attribute['attribute_label'];

            if ( ! $attribute_type ) {
                continue;
            }

            if ($attribute_type !== 'select') {
                $is_only_select = false;
            }

            if( array_key_exists( $attribute_type,  $attributes_types_list ) ) {

                $available_options = $attributes[$taxonomy];

                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms    = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'all' ) );

                // Generate request variable name.
                $key      = 'attribute_' . sanitize_title( $taxonomy );
                $selected = $product->get_variation_default_attribute( $taxonomy );

                // show it for quick view only where size of swatches is large
                if ( $is_quick_view ) {
                    $html .= '<span class="et_attribute-name" data-for="'.sanitize_title( $taxonomy ).'">' . $attribute_label . '</span>';
                }

                $variations_by_attr = array();
                if ($variations_images){
                    if ( in_array($attribute_type, array('st-image-swatch', 'st-image-swatch-sq')) ) {
                        $variations_by_attr = $this->variations_by_attr($available_options, $key, $variations);
                    }
                }
                if ($show_select_swatch && $attribute_type == 'select') {
                    $variations_by_attr = $this->variations_by_attr($available_options, $key, $variations);
                }
                if ( !isset($attributes_count[$attribute_type]) )
                    $attributes_count[$attribute_type] = 0;
                $attributes_count[$attribute_type]++;

                $html .= apply_filters( 'sten_wc_archive_loop_swatch_html', $attribute_type, $taxonomy, $terms, $available_options, $variations, $selected, $attributes_count, $size, $is_quick_view, $variations_by_attr );
            }
        }

        if ($is_only_select && !$show_select_swatch) {
            return;
        }

        $html .= sprintf( '<a href="javascript:void(0);" class="sten-reset-loop-variation" style="display:none;"> %1$s </a>', esc_html__( 'Clear', 'xstore-core' )  );
        if ( $sw_popup ) {
            $html .= '<div class="st-swatch-preview-wrap"><div class="et_st-popup-holder"></div>';
            ob_start();

            if (function_exists('etheme_get_option') && etheme_get_option('product_page_smart_addtocart', 0) && !etheme_is_catalog()){
                echo '<div class="quantity-wrapper">';
                remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
                remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
                add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
                add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
                woocommerce_quantity_input( array(
                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product )
                ), $product, true );
                woocommerce_template_loop_add_to_cart();
                echo '</div>';
            } else {
                do_action('woocommerce_after_shop_loop_item_title' );
                do_action( 'woocommerce_after_shop_loop_item' );
            }

            $html .= ob_get_clean();
            $html .= '<i class="et-icon et-delete"></i>';
            $html .= '</div>';
        }
        $html .= '</div>';
        print ( $html );
    }

    public function available_variations( $variations ) {

        $new_variations = array();

        foreach ( $variations as $variation ) {

            if ( $variation['variation_id'] != '' ) {

                $id     = get_post_thumbnail_id( $variation['variation_id'] );
                $src    = wp_get_attachment_image_src( $id, 'woocommerce_thumbnail' );
                $srcset = wp_get_attachment_image_srcset( $id, 'woocommerce_thumbnail' );
                $sizes  = wp_get_attachment_image_sizes( $id, 'woocommerce_thumbnail' );

                $variation = apply_filters( 'sten_wc_archive_loop_available_variation', array_merge( $variation, array(
                    'st_image_src'    => $src,
                    'st_image_srcset' => $srcset,
                    'st_image_sizes'  => $sizes
                ) ), $this, wc_get_product( $variation['variation_id'] ) );

                $new_variations[] = $variation;
            }
        }

        return $new_variations;
    }

    /**
     * Print HTML of swatches
     */
    public function swatch_html( $attribute_type, $taxonomy, $terms, $available_options, $variations, $selected, $attributes_count, $size, $is_quick_view = false, $available_options_by_attr=array() ) {
        $html = '';
        $layout = function_exists('etheme_get_option') ? etheme_get_option( 'swatch_layout_shop', 'default' ) : 'default';
        $show_plus = function_exists('etheme_get_option') ? etheme_get_option( 'show_plus_variations', 0 ) : 0;
        $show_plus_after = function_exists('etheme_get_option') ? etheme_get_option( 'show_plus_variations_after', 3 ) : 3;
        $sw_shape = get_theme_mod('swatch_shape', 'default');
        $sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;
        $custom_class = '';
        $custom_class .= 'st-swatch-size-' . $size;
        if ( !$is_quick_view )
            $custom_class .= ( $layout == 'popup' ) ? ' st-swatch-et-disabled' : '' ;
        $subtype      = '';

        if ( strpos( $attribute_type, '-sq') !== false ) {
            $et_attribute_type = str_replace( '-sq', '', $attribute_type );
            if ( !$sw_custom_shape || $sw_custom_shape == 'square' ) {
                $custom_class .= ' st-swatch-shape-square';
                $subtype      = 'subtype-square';
            }
            else if ( $sw_custom_shape == 'circle' ) {
                $custom_class .= ' st-swatch-shape-circle';
            }
        } else {
            $et_attribute_type = $attribute_type;
            if ( !$sw_custom_shape || $sw_custom_shape == 'circle' ) {
                $custom_class .= ' st-swatch-shape-circle';
            }
        }

        switch ( $et_attribute_type ) {

            case 'st-color-swatch':
                if( $terms ) {

                    $out = '';

                    $count = count($terms);

                    $_i=0;

                    foreach( $terms as $term ) {

                        if ( in_array( $term->slug, $available_options, true ) ) {

                            $_i++;

                            $color = get_term_meta( $term->term_id, 'st-color-swatch', true );

                            $class = ( $selected == $term->slug ) ? 'selected' : '';
                            $class .= ( $color == '#ffffff' || $color == '#fcfcfc' || $color == '#f7f7f7' || $color == '#f4f4f4'  ) ?  ' st-swatch-white' : '';

                            if ( $show_plus && $count>$show_plus_after && $_i>$show_plus_after){
                                $class .= ' hidden';
                            }

                            $out .= sprintf(
                                '<li class="type-color %5$s %1$s" data-tooltip="%3$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s" 
								style="%4$s"></span> </li>',
                                esc_attr( $class ),
                                esc_attr( $term->slug ),
                                esc_attr( $term->name ),
                                esc_attr( $this->generate_gradient_color_css($color) ),
                                esc_attr( $subtype )
                            );
                        }
                    }

                    if ( $show_plus && $count>$show_plus_after){
                        $out .= '<li class="et_show-more-attr" data-tooltip="'.esc_html__('Show more', 'xstore-core').'">+'.($count - $show_plus_after). '</li>';
                    }

                    $html .= '<div class="et_st-default-holder" data-et-holder="' . sanitize_title( $taxonomy ) . '">';
                    $html .= sprintf(
                        '<ul class="st-swatch-preview %1$s %3$s" data-attribute="%2$s" data-default-attribute="%4$s">',
                        esc_attr( $custom_class ),
                        sanitize_title( $taxonomy ),
                        !empty( $selected ) ? 'has-default-attribute' : '',
                        !empty( $selected ) ? $selected : 'none'
                    );
                    $html .= $out;
                    $html .= '</ul></div>';
                }

                break;

            case 'st-image-swatch':
                if( $terms ) {

                    $count = count($terms);
                    $_i=0;

                    $out = '';

                    foreach( $terms as $term ) {

                        if ( in_array( $term->slug, $available_options, true ) ) {
                            $_i++;
                            $image = get_term_meta( $term->term_id, 'st-image-swatch', true );
                            $class = ( $selected == $term->slug ) ? 'selected' : '';

                            if (
                                count($available_options_by_attr)
                                && isset($available_options_by_attr[$term->slug])
                                && ! empty($available_options_by_attr[$term->slug])
                            ){
                                $image = get_post_thumbnail_id( $available_options_by_attr[$term->slug] );
                            } else {
                                $image = get_term_meta( $term->term_id, 'st-image-swatch', true );
                            }

                            if ($image){
                                $image = wp_get_attachment_image( $image, apply_filters('sten_wc_archive_loop_image_swatch_size', 'thumbnail') );
                            }

                            if (!$image){
                                $image = '<img src="'.esc_url( ET_CORE_URL . 'packages/st-woo-swatches/public/images/placeholder.png' ) . '"/>';
                            }

                            if ( $show_plus && $count>$show_plus_after && $_i>$show_plus_after){
                                $class .= ' hidden';
                            }

                            $out .= sprintf(
                                '<li class="type-image %5$s %1$s" data-tooltip="%3$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span> </li>',
                                esc_attr( $class ),
                                esc_attr( $term->slug ),
                                esc_attr( $term->name ),
                                $image,
                                esc_attr( $subtype )
                            );
                        }
                    }

                    if ( $show_plus && $count>$show_plus_after){
                        $out .= '<li class="et_show-more-attr" data-tooltip="'.esc_html__('Show more', 'xstore-core').'">+'.($count - $show_plus_after). '</li>';
                    }

                    $html .= '<div class="et_st-default-holder" data-et-holder="' . sanitize_title( $taxonomy ) . '">';
                    $html .= sprintf(
                        '<ul class="st-swatch-preview %1$s %3$s" data-attribute="%2$s" data-default-attribute="%4$s">',
                        esc_attr( $custom_class ),
                        sanitize_title( $taxonomy ),
                        !empty( $selected ) ? 'has-default-attribute' : '',
                        !empty( $selected ) ? $selected : 'none'
                    );
                    $html .= $out;
                    $html .= '</ul></div>';
                }
                break;

            case 'st-label-swatch':
                if( $terms ) {

                    $count = count($terms);
                    $_i=0;

                    $out = '';

                    foreach( $terms as $term ) {

                        if ( in_array( $term->slug, $available_options, true ) ) {
                            $_i++;
                            $label = get_term_meta( $term->term_id, 'st-label-swatch', true );
                            $label = (!empty($label)) ? $label : $term->name;
                            $class = ( $selected == $term->slug ) ? 'selected' : '';

                            if ( $show_plus && $count>$show_plus_after && $_i>$show_plus_after ){
                                $class .= ' hidden';
                            }

                            $out .= sprintf(
                                '<li class="type-label %5$s %1$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span> </li>',
                                esc_attr( $class ),
                                esc_attr( $term->slug ),
                                esc_attr( $term->name ),
                                esc_attr( $label ),
                                esc_attr( $subtype )
                            );
                        }
                    }

                    if ( $show_plus && $count>$show_plus_after){
                        $out .= '<li class="et_show-more-attr" data-tooltip="'.esc_html__('Show more', 'xstore-core').'">+'.($count - $show_plus_after). '</li>';
                    }

                    $html .= '<div class="et_st-default-holder" data-et-holder="' . sanitize_title( $taxonomy ) . '">';
                    $html .= sprintf(
                        '<ul class="st-swatch-preview %1$s %3$s" data-attribute="%2$s" data-default-attribute="%4$s">',
                        esc_attr( $custom_class ),
                        sanitize_title( $taxonomy ),
                        !empty( $selected ) ? 'has-default-attribute' : '',
                        !empty( $selected ) ? $selected : 'none'
                    );
                    $html .= $out;
                    $html .= '</ul></div>';
                }
                break;

            case 'select':
                if ( get_theme_mod('swatch_select_type_shop', false) && is_array($variations)) {
                    $swatch_select_price = get_theme_mod('swatch_select_type_price_shop', true);
                    if( $terms ) {
                        $terms_prices = array();
                        $_i=0;

                        $out = '';
                        $selected_title = '';

                        foreach( $terms as $term ) {

                            if ( in_array( $term->slug, $available_options, true ) ) {
                                $_i++;
                                if ( !isset($terms_prices[$term->slug]) ) {
                                    $terms_prices[$term->slug] = array_map(function ($variation) {
                                        return $variation['display_price'];
                                    }, array_filter($variations, function ($key) use ($taxonomy, $term) {
                                        return $key['attributes']['attribute_' . sanitize_title($taxonomy)] == $term->slug ||
                                            $key['attributes']['attribute_' . sanitize_title($taxonomy)] == '';
                                    }));
                                }

                                $label = get_term_meta( $term->term_id, 'select', true );
                                $label = (!empty($label)) ? $label : $term->name;
                                $price = '';
                                if ( $swatch_select_price && $terms_prices[$term->slug] ) {
                                    $price = wc_price(floatval(min($terms_prices[$term->slug])));
                                    $price = '<span class="st-attribute-price">' . sprintf(esc_html__('From: %s', 'xstore-core'), $price) . '</span>';
                                }
                                $class = '';
                                if ( $selected == $term->slug ) {
                                    $selected_title = $label . $price;
                                    $class .= 'selected';
                                }

                                $out .= sprintf(
                                    '<li class="type-select %5$s %1$s"> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span> </li>',
                                    esc_attr( $class ),
                                    esc_attr( $term->slug ),
                                    esc_attr( $term->name ),
                                    esc_attr( $label ) . $price,
                                    esc_attr( $subtype )
                                );
                            }
                        }

                        $html .= '<div class="et_st-default-holder et_st-select-holder'.(!empty( $selected ) ? '' : ' hidden').'" data-et-holder="' . sanitize_title( $taxonomy ) . '">';
                        $html .= '<div class="et_st-swatch-select-wrapper">';
                        $html .= '<div class="et_st-swatch-select-title">'.
                            (!empty($selected_title) ? $selected_title : esc_html__('Select options', 'xstore-core')).
                            '</div>';
                        $html .= '<div class="et_st-swatch-select-list" data-taxonomy="' . sanitize_title( $taxonomy ) . '">';
                        $html .= sprintf(
                            '<ul class="st-swatch-preview %1$s %3$s" data-attribute="%2$s" data-default-attribute="%4$s">',
                            '',
                            sanitize_title( $taxonomy ),
                            !empty( $selected ) ? 'has-default-attribute' : '',
                            !empty( $selected ) ? $selected : 'none'
                        );
                        $html .= $out;
                        $html .= '</ul>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                }
                break;
        }

        return $html;
    }

    public function add_to_cart() {

        $product_id   = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity     = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
        $variation_id = $_POST['variation_id'];
        $variation    = array();
        $data         = array();

        if ( is_array( $_POST['variation'] ) ) {

            foreach ( $_POST['variation'] as $key => $value ) {

                $variation[ $key ] = $this->utf8_urldecode( $value );
            }
        }

        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10);
            add_action('woocommerce_widget_shopping_cart_total', 'etheme_woocommerce_widget_shopping_cart_subtotal', 10);

            if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
                wc_add_to_cart_message( $product_id );
            }

            $data = WC_AJAX::get_refreshed_fragments();
        } else {

            if (class_exists('WC_AJAX')&& method_exists(WC_AJAX,'json_headers')){
                WC_AJAX::json_headers();
            }

            $data = array(
                'error'       => true,
                'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
            );
        }

        wp_send_json( $data );
        wp_die();
    }

    /**
     * Generate color style
     */
    public function generate_gradient_color_css($color) {
        $style = '';
        if (is_array($color)){
            $gradient_direction = get_theme_mod('swatch_multicolor_design', 'right');
            if ( in_array($gradient_direction, array('diagonal_1', 'diagonal_2'))) {
                $gradient_direction = str_replace(array('diagonal_1', 'diagonal_2'), array('bottom right', 'bottom left'), $gradient_direction);
            }
            $style .= 'background: linear-gradient( to ';
            $style .= $gradient_direction . ',';
            $percent = 100/count($color);

            foreach($color as $color_key => $color_value){
                $style .= $color_value . ' ' . $percent .'% '. ( $percent+$percent*$color_key ) . '%';
                if ($color_key != count($color)-1){
                    $style .= ',';
                }
            }

            $style .= ');';
        } else {
            $style .= 'background-color:' . $color . ';';
        }
        return $style;
    }

    /**
     *  Form variations id array based on attribute value, use attribute slug as array kay
     */
    public function variations_by_attr($variations_values, $attribute_key, $variations){
        $variations_by_attr = array();
        foreach ($variations as $variation ) {
            if ( ! isset( $variation['attributes'][ $attribute_key ] ) ) {
                continue;
            }
            $slug = $variation['attributes'][ $attribute_key ];
            if (in_array($slug,$variations_values)){
                $variations_by_attr[$slug] = $variation['variation_id'];
            }
        }
        return $variations_by_attr;
    }
}