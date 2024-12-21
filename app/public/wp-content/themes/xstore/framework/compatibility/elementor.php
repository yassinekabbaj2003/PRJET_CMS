<?php
/**
 * Description
 *
 * @package    elementor.php
 * @since      8.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

// compatibility with elementor header/footer builders
// rewritten due to a single post template error
function etheme_register_elementor_locations( $elementor_theme_manager ) {
	
	// the default locations
	$core_locations = $elementor_theme_manager->get_core_locations();
	
	// do not rewrite this locations
	unset($core_locations['archive']);
	unset($core_locations['single']);
	
	foreach ( $core_locations as $location => $settings ) {
		// rewrite locations to default
		$elementor_theme_manager->register_location( $location, $settings );
	}
	
	// previse rewritten all locations
	//$elementor_theme_manager->register_all_core_location();
}

add_action( 'elementor/theme/register_locations', 'etheme_register_elementor_locations' );

add_action( "elementor/theme/before_do_header", function() {
	ob_start();
	
	do_action( 'et_after_body', true )
	
	?>
	<div class="template-container">
	
	<?php
	/**
	 * Hook: etheme_header_before_template_content.
	 *
	 * @hooked etheme_top_panel_content - 10
	 * @hooked etheme_mobile_menu_content - 20
	 *
	 * @version 6.0.0 +
	 * @since 6.0.0 +
	 *
	 */
	do_action( 'etheme_header_before_template_content' );
	?>
	<div class="template-content">
	<div class="page-wrapper">
	<?php
	echo ob_get_clean();
} );

add_action( "elementor/theme/after_do_header", function() {

});

add_action( "elementor/theme/before_do_footer", function() {
	ob_start(); ?>
	</div> <!-- page wrapper -->
	
	</div> <!-- template-content -->
	
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- template-container -->
	<?php echo ob_get_clean();
});

add_action('wp', function () {
    $is_preview = Elementor\Plugin::$instance->preview->is_preview_mode();
    $is_edit = Elementor\Plugin::$instance->editor->is_edit_mode();
    if ( $is_preview || $is_edit ) {
        add_filter('etheme_cart_redirect_after_add', '__return_false');
        add_filter('etheme_elementor_edit_mode', '__return_true');
        add_filter('etheme_site_preloader', '__return_false');
    }
    $is_static_block = is_singular('staticblocks');
    $is_etheme_slides = is_singular('etheme_slides');
    $is_etheme_mega_menus = is_singular('etheme_mega_menus');
    if ( $is_preview || $is_edit ) :
        if ($is_etheme_slides && false)  : // temp hide
            add_action('wp_body_open', function () {
                foreach (array(
                             'media',
                             'colorpicker'
                         ) as $script_2_load) {
                    switch ($script_2_load) {
                        case 'repeater':
                        case 'sortable':
                            wp_enqueue_script('jquery-ui-sortable');
                            wp_enqueue_script('jquery-ui-draggable');
                            break;
                        case 'media':
                            wp_enqueue_media();
                            break;
                        case 'colorpicker':
                            // works only for backend @todo need to load for frontend
                            wp_enqueue_script( 'jquery-color' );
                            wp_enqueue_style( 'wp-color-picker' );
                            wp_enqueue_script( 'wp-color-picker' );
                            break;
                    }
                }
                $post_type = 'etheme_slides';
                $has_post_thumbnail = has_post_thumbnail();
                $button_atts = array(
                    'class="et-elementor-editor-thumbnail-action"',
                    'data-selector=".swiper-slide-contents"',
                    'data-post_id="'.get_the_ID().'"',
                    'data-post_type="'.$post_type.'"'
                ); ?>
                <div class="et-elementor-editor-thumbnail-actions-wrapper">
                    <span <?php echo implode(' ', $button_atts); ?> data-action="upload">
                        <span class="dashicons dashicons-upload"></span>
                        <?php echo esc_html__('Upload BG Image', 'xstore'); ?>
                    </span>
                    <span <?php echo implode(' ', $button_atts); ?> data-action="settings">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php echo esc_html__('BG Settings', 'xstore'); ?>
                    </span>
                    <span <?php echo implode(' ', $button_atts); ?> data-action="remove" <?php if ( !$has_post_thumbnail ) echo ' style="display: none;"'; ?>>
                        <span class="dashicons dashicons-trash"></span>
                        <?php echo esc_html__('Remove BG Image', 'xstore'); ?>
                    </span>
                    <input type="hidden" name="etheme_<?php echo esc_attr($post_type) . '_nonce' ?>" value="<?php echo wp_create_nonce( 'etheme_'.$post_type.'_nonce' ); ?>">
                </div>
        <?php });
        endif;
        if ( $is_static_block || $is_etheme_slides || $is_etheme_mega_menus ) :
            add_action('wp_body_open', function () {
               ?>
                <div class="et-elementor-editor-dark-light-switcher">
            <span class="switcher light-mode" data-light-color="#fff" data-dark-color="#1a1a1a">
                <span class="on"><?php echo esc_html__('Light', 'xstore'); ?></span>
                <span class="off"><?php echo esc_html__('Dark', 'xstore'); ?></span>
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" xml:space="preserve" class="light" width="1em" height="1em" fill="currentColor">
                      <g id="Sun">
                          <g>
                              <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M6,17.5C6,16.672,5.328,16,4.5,16h-3C0.672,16,0,16.672,0,17.5    S0.672,19,1.5,19h3C5.328,19,6,18.328,6,17.5z M7.5,26c-0.414,0-0.789,0.168-1.061,0.439l-2,2C4.168,28.711,4,29.086,4,29.5    C4,30.328,4.671,31,5.5,31c0.414,0,0.789-0.168,1.06-0.44l2-2C8.832,28.289,9,27.914,9,27.5C9,26.672,8.329,26,7.5,26z M17.5,6    C18.329,6,19,5.328,19,4.5v-3C19,0.672,18.329,0,17.5,0S16,0.672,16,1.5v3C16,5.328,16.671,6,17.5,6z M27.5,9    c0.414,0,0.789-0.168,1.06-0.439l2-2C30.832,6.289,31,5.914,31,5.5C31,4.672,30.329,4,29.5,4c-0.414,0-0.789,0.168-1.061,0.44    l-2,2C26.168,6.711,26,7.086,26,7.5C26,8.328,26.671,9,27.5,9z M6.439,8.561C6.711,8.832,7.086,9,7.5,9C8.328,9,9,8.328,9,7.5    c0-0.414-0.168-0.789-0.439-1.061l-2-2C6.289,4.168,5.914,4,5.5,4C4.672,4,4,4.672,4,5.5c0,0.414,0.168,0.789,0.439,1.06    L6.439,8.561z M33.5,16h-3c-0.828,0-1.5,0.672-1.5,1.5s0.672,1.5,1.5,1.5h3c0.828,0,1.5-0.672,1.5-1.5S34.328,16,33.5,16z     M28.561,26.439C28.289,26.168,27.914,26,27.5,26c-0.828,0-1.5,0.672-1.5,1.5c0,0.414,0.168,0.789,0.439,1.06l2,2    C28.711,30.832,29.086,31,29.5,31c0.828,0,1.5-0.672,1.5-1.5c0-0.414-0.168-0.789-0.439-1.061L28.561,26.439z M17.5,29    c-0.829,0-1.5,0.672-1.5,1.5v3c0,0.828,0.671,1.5,1.5,1.5s1.5-0.672,1.5-1.5v-3C19,29.672,18.329,29,17.5,29z M17.5,7    C11.71,7,7,11.71,7,17.5S11.71,28,17.5,28S28,23.29,28,17.5S23.29,7,17.5,7z M17.5,25c-4.136,0-7.5-3.364-7.5-7.5    c0-4.136,3.364-7.5,7.5-7.5c4.136,0,7.5,3.364,7.5,7.5C25,21.636,21.636,25,17.5,25z"></path>
                          </g>
                      </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" class="dark" width="1em" height="1em" fill="currentColor">
                      <path d="M96.76,66.458c-0.853-0.852-2.15-1.064-3.23-0.534c-6.063,2.991-12.858,4.571-19.655,4.571  C62.022,70.495,50.88,65.88,42.5,57.5C29.043,44.043,25.658,23.536,34.076,6.47c0.532-1.08,0.318-2.379-0.534-3.23  c-0.851-0.852-2.15-1.064-3.23-0.534c-4.918,2.427-9.375,5.619-13.246,9.491c-9.447,9.447-14.65,22.008-14.65,35.369  c0,13.36,5.203,25.921,14.65,35.368s22.008,14.65,35.368,14.65c13.361,0,25.921-5.203,35.369-14.65  c3.872-3.871,7.064-8.328,9.491-13.246C97.826,68.608,97.611,67.309,96.76,66.458z"></path>
                    </svg>
                </i>
            </span></div>
                <?php
            });
        endif;
    endif;
    if ( $is_preview ) {
        // disable mega menu lazy load if in Elementor edit mode
        add_filter( 'menu_dropdown_ajax', '__return_false' );
        // disable mobile optimization in editor/preview mode
        // to make Elementor resize work normally
        set_query_var('et_mobile-optimization', false);
    }
    if ( defined('ELEMENTOR_PRO_VERSION') && ( get_query_var('et_is-cart', false) || get_query_var('et_is-checkout', false) ) ) {

        if ( $is_preview ) {
            add_filter('etheme_elementor_cart_page', '__return_true');
            add_filter('etheme_elementor_checkout_page', '__return_true');
        }
//        else {
//
//            $document = \Elementor\Plugin::$instance->documents->get( get_query_var('et_page-id', array('id' => 0))['id'] );
//
//            if ( is_object( $document ) ) {
//                $data = $document->get_elements_data();
//                \Elementor\Plugin::$instance->db->iterate_data( $data, function( $element ) {
//                    if (
//                        isset( $element['widgetType'] )
//                    )  {
//                        switch($element['widgetType']) {
//                            case 'woocommerce-cart':
//                                add_filter('etheme_elementor_cart_page', '__return_true');
//                                break;
//                            case 'woocommerce-checkout-page':
//                                add_filter('etheme_elementor_checkout_page', '__return_true');
//                                break;
//                        }
//                    }
//                });
//            }
//        }
    }
});

$disable_options_edit_mode = array(
    'cssjs_ver',
    'flying_pages'
);

foreach ($disable_options_edit_mode as $disable_option_edit_mode) {
	add_filter('theme_mod_'.$disable_option_edit_mode, function ($origin_value) {
		if ( is_customize_preview() || (class_exists('\Elementor\Plugin') &&
		     \Elementor\Plugin::$instance->editor &&
		     ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) ) {
			return false;
		}
		return $origin_value;
	}, 10);
}


add_action( 'elementor/frontend/after_enqueue_scripts', function () {
	// is elementor preview load
	if ( Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		wp_enqueue_script( 'etheme_parallax_scroll_effect' ); // works always
		wp_enqueue_script( 'etheme_parallax_3d_hover_effect' ); // works always
		wp_enqueue_script( 'etheme_parallax_hover_effect' ); // works always
		wp_enqueue_script( 'etheme_parallax_floating_effect' ); // works always
        wp_enqueue_script( 'et_product_hover_slider' ); // for product carousel effect
//        if ( \Elementor\Plugin::$instance->documents->get_current() instanceof \ElementorPro\Modules\ThemeBuilder\Documents\Header ) {
            wp_enqueue_script('etheme_elementor_header_sticky'); // works always
            wp_enqueue_style('etheme-elementor-header-sticky'); // works always
//        }
	}
}, 50 );

add_action( 'elementor/frontend/before_register_scripts', function() {
//    $scripts_2_register = array(
//        'etheme_countdown',
//        'etheme_animated_headline',
//	    'etheme_progress_bar',
//	    'etheme_timeline',
//	    'etheme_product_filters',
//    );
//	foreach ($scripts_2_register as $script){
//		wp_register_script(
//			$scripts[$script]['name'],
//			get_template_directory_uri() . $scripts[$script]['file'],
//			(isset($scripts[$script]['deps']) ? $scripts[$script]['deps'] : array('jquery', 'etheme')),
//			(isset($scripts[$script]['version']) ? $scripts[$script]['version'] : ''),
//			$scripts[$script]['in_footer']
//		);
//	}
	$theme = wp_get_theme();
	foreach (etheme_config_js_files() as $script){
		wp_register_script(
			$script['name'],
			get_template_directory_uri() . $script['file'],
			(isset($script['deps']) ? $script['deps'] : array('jquery', 'etheme')),
			(isset($script['version']) ? $script['version'] : $theme->version),
			$script['in_footer']
		);
	}
	
}, 99);

add_action( 'elementor/frontend/before_register_styles', function() {
 
	$is_rtl = get_query_var('et_is-rtl', false);
	$theme = wp_get_theme();
	
	foreach (etheme_config_css_files() as $script){
		if ( !isset($script['deps'])) $script['deps'] = array("etheme-parent-style");
		
		if ( $is_rtl ) {
			$rtl_file = get_template_directory() . esc_attr( $script['file'] ) . '-rtl'.ETHEME_MIN_CSS.'.css';
			if (file_exists($rtl_file)) {
				$script['file'] .= '-rtl';
			}
		}
		
		wp_register_style(  'etheme-'.$script['name'], get_template_directory_uri() . $script['file'] . ETHEME_MIN_CSS .'.css', $script['deps'], $theme->version );
	}
}, 99);

// filters/action for product grid Elementor widget
add_filter('etheme_product_filters_taxonomies', function ($elements) {
	if ( etheme_get_option( 'enable_brands', 1 ) ) {
		$elements['brand'] = esc_html__( 'Brand', 'xstore' );
	}
    return $elements;
});

add_filter( 'etheme_product_grid_list_product_hover_elements', function ( $elements ) {
	if ( get_theme_mod( 'quick_view', 1 ) )
		$elements['quick_view'] = esc_html__( 'Show Quick View', 'xstore' );
	return $elements;
} );

add_action( 'etheme_product_grid_list_product_hover_element_render', function ( $key, $product, $edit_mode ) {
	if ( $key == 'quick_view' ) {
		if ( !$edit_mode && !wp_doing_ajax() ) {
			etheme_enqueue_style( "quick-view" );
			if ( get_theme_mod( 'quick_view_content_type', 'popup' ) == 'off_canvas' ) {
				etheme_enqueue_style( "off-canvas" );
			}
		}
		echo '<span class="show-quickly" data-prodid="' . esc_attr( $product->get_ID() ) . '" data-text="'.esc_attr__('Quick View', 'xstore').'">' .
            (get_theme_mod('bold_icons', 0) ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.52 8.592v0c-4.728-4.704-12.384-4.704-17.088 0l-3.384 3.36 3.456 3.456c2.28 2.28 5.328 3.552 8.568 3.552s6.288-1.248 8.568-3.552l3.312-3.36-3.432-3.456zM12 8.376c1.992 0 3.624 1.632 3.624 3.624s-1.632 3.624-3.624 3.624-3.624-1.608-3.624-3.624 1.632-3.624 3.624-3.624zM6.528 12c0 2.040 1.128 3.816 2.784 4.752-1.68-0.456-3.264-1.32-4.56-2.64l-2.16-2.184 2.136-2.136c1.392-1.392 3.072-2.28 4.848-2.712-1.8 0.912-3.048 2.784-3.048 4.92zM17.472 12c0-2.136-1.248-4.008-3.048-4.896 1.776 0.432 3.456 1.344 4.848 2.712l2.16 2.184-2.136 2.136c-1.344 1.32-2.952 2.208-4.656 2.664 1.68-0.936 2.832-2.736 2.832-4.8z"></path>
            </svg>' :
		     '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.664 8.688v0c-4.8-4.776-12.6-4.776-17.376 0l-3.288 3.264 3.36 3.36c2.328 2.328 5.4 3.6 8.712 3.6 3.288 0 6.384-1.272 8.712-3.6l3.216-3.264-3.336-3.36zM4.152 14.496l-2.52-2.544 2.496-2.496c4.344-4.344 11.4-4.344 15.744 0l2.52 2.544-2.496 2.496c-4.344 4.32-11.4 4.32-15.744 0zM12 6.648c-2.952 0-5.352 2.4-5.352 5.352s2.4 5.352 5.352 5.352c2.952 0 5.352-2.4 5.352-5.352s-2.4-5.352-5.352-5.352zM12 16.176c-2.304 0-4.176-1.872-4.176-4.176s1.872-4.176 4.176-4.176 4.176 1.872 4.176 4.176-1.872 4.176-4.176 4.176z"></path>
                    </svg>') .
		     '</span>';
	}
}, 10, 3 );

add_filter('etheme_product_grid_list_product_elements', 'etheme_product_grid_list_product_and_hover_elements_filter');
add_filter('etheme_product_grid_list_product_hover_elements', 'etheme_product_grid_list_product_and_hover_elements_filter');

function etheme_product_grid_list_product_and_hover_elements_filter($elements) {
	if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
		$excerpt_position = array_search('excerpt', array_keys($elements));
		if ( $excerpt_position > 1 ) {
			$elements = array_slice( $elements, 0, $excerpt_position, true ) +
			            array( 'stock_line' => esc_html__( 'Show Stock Line', 'xstore' ) ) +
			            array_slice( $elements, $excerpt_position, count( $elements ) - $excerpt_position, true );
		}
		else {
			$elements['stock_line'] = esc_html__( 'Show Stock Line', 'xstore' );
		}
	}

    $enabled_brands = get_query_var('et_brands', 'undefined');
    if ( $enabled_brands === 'undefined' ) {
        $enabled_brands = etheme_get_option( 'enable_brands', 1 );
        set_query_var('et_brands', $enabled_brands);
    }
	if ( $enabled_brands ) {
		$rating_position = array_search('rating', array_keys($elements));
		if ( $rating_position > 1 ) {
			$elements = array_slice( $elements, 0, $rating_position, true ) +
			            array( 'brands' => esc_html__( 'Show Brands', 'xstore' ) ) +
			            array_slice( $elements, $rating_position, count( $elements ) - $rating_position, true );
		}
		else {
			$elements['brands'] = esc_html__( 'Show Brands', 'xstore' );
		}
	}

    $enabled_swatch = get_query_var('et_is-swatches', 'undefined');
    if ( $enabled_swatch === 'undefined' ) {
        $enabled_swatch = etheme_get_option( 'enable_swatch', 1 );
        set_query_var('et_is-swatches', $enabled_brands);
    }

	if ( $enabled_swatch ) {
        $swatch_position = get_query_var('et_swatches-pos', 'undefined');
        if ( $swatch_position === 'undefined' ) {
            $swatch_position = etheme_get_option('swatch_position_shop', 'before');
            set_query_var('et_swatches-pos', $swatch_position);
        }
		if ( $swatch_position != 'disable' ) {
			$categories_position = $swatch_position == 'before' ? array_search('categories', array_keys($elements)) : array_search('button', array_keys($elements));
//			$categories_position_after = $swatch_position == 'after' ? 1 : 0;
			$categories_position_after = 0;
			if ( $categories_position > 0 ) {
				$elements = array_slice( $elements, 0, $categories_position + $categories_position_after, true ) +
				            array( 'swatches' => esc_html__( 'Show Swatches', 'xstore' ) ) +
				            array_slice( $elements, $categories_position, count( $elements ) - $categories_position + $categories_position_after, true );
			}
			else {
				$elements['swatches'] = esc_html__( 'Show Swatches', 'xstore' );
			}
        }
    }
	return $elements;
}

add_filter('etheme_product_grid_list_product_hover_info_elements', function ($elements) {
	$elements[] = 'brands';
	$elements[] = 'stock_line';
	$elements[] = 'swatches';
	return $elements;
});

add_action( 'etheme_product_grid_list_product_element_render', 'etheme_product_grid_list_product_and_hover_element_render', 10, 4 );
add_action( 'etheme_product_grid_list_product_hover_element_render', 'etheme_product_grid_list_product_and_hover_element_render', 10, 4 );

function etheme_product_grid_list_product_and_hover_element_render($key, $product, $edit_mode, $main_class) {
	switch ($key) {
		case 'stock_line':
			echo et_product_stock_line($product);
			break;
		case 'brands':
			etheme_product_brands();
			break;
		case 'swatches':
			wp_enqueue_style('etheme-swatches-style');
			global $local_settings;
			$specific_hover = isset($local_settings['hover_effect']) && in_array($local_settings['hover_effect'], array('info', 'overlay', 'default'));
			$product_type_quantity_types = apply_filters('etheme_product_type_show_quantity', array('simple', 'variable', 'variation'));
			$has_quantity = !!$local_settings['product_button_quantity'] && in_array($product->get_type(), $product_type_quantity_types);
			if ( $specific_hover ) {
			    add_filter('theme_mod_swatch_layout_shop', '__return_false');
            }
			if ( $has_quantity ) {
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				add_filter('woocommerce_product_add_to_cart_text', '__return_false');
				remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
				remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
				add_action( 'woocommerce_before_quantity_input_field', array($main_class, 'quantity_minus_icon') );
				add_action( 'woocommerce_after_quantity_input_field', array($main_class, 'quantity_plus_icon') );
				add_filter('esc_html', array($main_class, 'escape_text'), 10, 2);
				add_filter('woocommerce_loop_add_to_cart_args', array($main_class, 'add_class_for_button'), 10, 1);
				add_filter('woocommerce_product_add_to_cart_text', array($main_class, 'add_to_cart_icon'), 10);
            }
			else {
			    add_filter('theme_mod_product_page_smart_addtocart', '__return_false');
            }
			do_action('loop_swatch', 'normal');
			if ( $specific_hover ) {
				remove_filter('theme_mod_swatch_layout_shop', '__return_false');
			}
			if ( $has_quantity ) {
				remove_filter('woocommerce_product_add_to_cart_text', array($main_class, 'add_to_cart_icon'), 10);
				remove_filter('woocommerce_loop_add_to_cart_args', array($main_class, 'add_class_for_button'), 10, 1);
				remove_filter('esc_html', array($main_class, 'escape_text'), 10, 2);
				remove_action( 'woocommerce_before_quantity_input_field', array($main_class, 'quantity_minus_icon') );
				remove_action( 'woocommerce_after_quantity_input_field', array($main_class, 'quantity_plus_icon') );
				add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				remove_filter('woocommerce_product_add_to_cart_text', '__return_false');
            }
			else {
				remove_filter('theme_mod_product_page_smart_addtocart', '__return_false');
			}
			break;
	}
}

add_action('etheme_product_grid_list_product_elements_style', 'etheme_product_grid_list_product_and_hover_elements_style');

function etheme_product_grid_list_product_and_hover_elements_style ($control) {
	$control->start_controls_section(
		'section_brands_style',
		[
			'label' => __( 'Brands', 'xstore' ),
			'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [
				'product_brands!' => ''
			],
		]
	);
	
	$control->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name' => 'brands_typography',
			'selector' => '{{WRAPPER}} .products-page-brands',
		]
	);
	
	$control->start_controls_tabs('tabs_brands_colors');
	
	$control->start_controls_tab( 'tabs_brands_color_normal',
		[
			'label' => esc_html__('Normal', 'xstore')
		]
	);
	
	$control->add_control(
		'brands_color',
		[
			'label' => __( 'Color', 'xstore' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .products-page-brands, {{WRAPPER}} .products-page-brands a' => 'color: {{VALUE}};',
			],
		]
	);
	
	$control->end_controls_tab();
	
	$control->start_controls_tab( 'tabs_brands_color_hover',
		[
			'label' => esc_html__('Hover', 'xstore')
		]
	);
	
	$control->add_control(
		'brands_color_hover',
		[
			'label' => __( 'Color', 'xstore' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .products-page-brands a:hover' => 'color: {{VALUE}};',
			],
		]
	);
	
	$control->end_controls_tab();
	$control->end_controls_tabs();
	
	$control->add_control(
		'brands_space',
		[
			'label' => __( 'Bottom Space', 'xstore' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 50,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .products-page-brands' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		]
	);
	
	$control->end_controls_section();
}

add_filter('etheme_product_grid_list_product_hover_info_elements_render', function ($info_elements, $hover_effect, $all_elements) {
	if ( in_array($hover_effect, array('info', 'overlay', 'default')) ) {
	    if ( array_key_exists('brands', $all_elements) ) {
		    $info_elements['brands'] = $all_elements['brands'];
        }
		if ( array_key_exists('stock_line', $all_elements) ) {
			$info_elements['stock_line'] = $all_elements['stock_line'];
		}
		if ( array_key_exists('swatches', $all_elements) ) {
			$info_elements['swatches'] = $all_elements['swatches'];
		}
    }
	return $info_elements;
}, 10, 3);

add_filter('etheme_product_grid_list_product_hover_elements_render', function ($elements, $hover_effect, $info_elements) {
	if ( in_array($hover_effect, array('info', 'overlay', 'default')) ) {
		if ( array_key_exists('brands', $elements) ) {
			unset($elements['brands']);
		}
		if ( array_key_exists('stock_line', $elements) ) {
			unset($elements['stock_line']);
		}
		if ( array_key_exists('swatches', $elements) ) {
			unset($elements['swatches']);
		}
	}
	return $elements;
}, 10, 3);

add_filter('etheme_product_grid_list_product_taxonomies', function ($taxonomies) {
	if ( etheme_get_option( 'enable_brands', 1 ) ) {
		$taxonomies['brand'] = esc_html__( 'Brands', 'xstore' );
	}
    return $taxonomies;
});
// insert quick view in specific position after cart
//	add_filter('etheme_product_grid_list_product_hover_elements_render', function ($elements) {
//	    if ( array_key_exists('quick_view', $elements) && count($elements) > 1 ) {
//	        $quick_view = $elements['quick_view'];
//	        unset($elements['quick_view']);
//		    array_splice( $elements, 1, 0, $quick_view );
//        }
//	    return $elements;
//    });
//}, 9);

// Posts widget
add_filter('etheme_posts_post_meta_data', function ($meta) {
	$excerpt_position = array_search('comments', array_keys($meta));
	if ( $excerpt_position ) {
		$meta = array_slice( $meta, 0, $excerpt_position, true ) +
		            array( 'views' => esc_html__( 'Views', 'xstore' ) ) +
		            array_slice( $meta, $excerpt_position, count( $meta ) - $excerpt_position, true );
	}
	else {
		$meta['views'] = esc_html__( 'Views', 'xstore' );
	}
    return $meta;
}, 10);

add_action('etheme_posts_post_meta_data_render', function ($key, $meta, $post_id) {
    if ( $key == 'views') {
	    $number = get_post_meta( $post_id, '_et_views_count', true );
	    if( empty($number) ) $number = 0;
	    echo '<span class="etheme-post-views-count">' .
             '<a href="'.get_permalink($post_id).'">'.
            (get_theme_mod('bold_icons', 0) ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.52 8.592v0c-4.728-4.704-12.384-4.704-17.088 0l-3.384 3.36 3.456 3.456c2.28 2.28 5.328 3.552 8.568 3.552s6.288-1.248 8.568-3.552l3.312-3.36-3.432-3.456zM12 8.376c1.992 0 3.624 1.632 3.624 3.624s-1.632 3.624-3.624 3.624-3.624-1.608-3.624-3.624 1.632-3.624 3.624-3.624zM6.528 12c0 2.040 1.128 3.816 2.784 4.752-1.68-0.456-3.264-1.32-4.56-2.64l-2.16-2.184 2.136-2.136c1.392-1.392 3.072-2.28 4.848-2.712-1.8 0.912-3.048 2.784-3.048 4.92zM17.472 12c0-2.136-1.248-4.008-3.048-4.896 1.776 0.432 3.456 1.344 4.848 2.712l2.16 2.184-2.136 2.136c-1.344 1.32-2.952 2.208-4.656 2.664 1.68-0.936 2.832-2.736 2.832-4.8z"></path>
            </svg>' :
                '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.664 8.688v0c-4.8-4.776-12.6-4.776-17.376 0l-3.288 3.264 3.36 3.36c2.328 2.328 5.4 3.6 8.712 3.6 3.288 0 6.384-1.272 8.712-3.6l3.216-3.264-3.336-3.36zM4.152 14.496l-2.52-2.544 2.496-2.496c4.344-4.344 11.4-4.344 15.744 0l2.52 2.544-2.496 2.496c-4.344 4.32-11.4 4.32-15.744 0zM12 6.648c-2.952 0-5.352 2.4-5.352 5.352s2.4 5.352 5.352 5.352c2.952 0 5.352-2.4 5.352-5.352s-2.4-5.352-5.352-5.352zM12 16.176c-2.304 0-4.176-1.872-4.176-4.176s1.872-4.176 4.176-4.176 4.176 1.872 4.176 4.176-1.872 4.176-4.176 4.176z"></path>
                    </svg>') .
             $number .
             '</a>'.
         '</span>';
    }
}, 10, 3);

add_filter('etheme_product_grid_list_product_new_label', '__return_true');
add_filter('etheme_product_grid_list_product_hot_label', '__return_true');

add_filter('etheme_product_meta_elements', function ($elements) {
    $elements['et_gtin'] = esc_html__('GTIN', 'xstore');
    if ( get_theme_mod('enable_brands', true) )
        $elements['et_brand'] = esc_html__('Brand', 'xstore');
    return $elements;
});

// check if core is enabled because it uses functions from core plugin
if ( defined('ET_CORE_VERSION') ) {
    // Lazyload Elementor widgets
	add_filter( 'elementor/widget/render_content', 'etheme_ajaxify_elementor_widgets', PHP_INT_MAX, 2 );
}

add_action( 'elementor/element/common/_section_style/before_section_start', function( $element, $args ) {
    
    $element->start_controls_section(
        'etheme_section_lazy_load',
        array(
            'label'     => __( 'XSTORE Ajaxify', 'xstore' ),
            'tab'       => \Elementor\Controls_Manager::TAB_ADVANCED,
        )
    );
    
    $element->add_control(
        'etheme_ajaxify',
        [
            'label' => __('Lazy Loading', 'xstore'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
        ]
    );
    
    $element->end_controls_section();
    
}, 10, 2);

/**
 * Filter for Elementor render callback to modify html output for lazyloading.
 *
 * @param $widget_content
 * @param $that
 * @return mixed|string
 *
 * @since 8.1.5
 *
 */
function etheme_ajaxify_elementor_widgets($widget_content, $that){
	if (defined('DOING_ETHEME_AJAXIFY') || \Elementor\Plugin::$instance->editor->is_edit_mode() || isset($_GET['et_ajax']) || !apply_filters('etheme_ajaxify_elementor_widget', true, $that)){
		return $widget_content;
	}
	$data = $that->get_data();
	if ( isset($data['settings']['etheme_ajaxify']) && $data['settings']['etheme_ajaxify'] == 'yes' ){
	    add_filter('etheme_ajaxify_script', '__return_true');
		// in case our old ajax option is enabled then make is false to use our new ajax loading action
		if ( isset($data['settings']['ajax']) && in_array($data['settings']['ajax'], array('true', 'yes'))) {
			$data['settings']['ajax'] = false;
		}
		$widget_content = '<span class="etheme-ajaxify-lazy-wrapper etheme-ajaxify-replace" data-type="elementor" data-request="'.etheme_encoding(json_encode(array('elementor', get_the_ID(), etheme_ajaxify_set_lazyload_buffer($data)))).'">' . '</span>';
	}
	return $widget_content;
}


add_action('wp', function () {
    if (is_404() || is_search()) {
        add_filter('etheme_ajaxify_elementor_widget', '__return_false');
    }
});

add_action( 'elementor/frontend/widget/before_render', 'etheme_elementor_before_render', 10 );

// to apply filter for all products widgets which will be loaded with Elementor
function etheme_elementor_before_render($widget) {
	if ( get_theme_mod('product_variable_price_from', false) ) {
		add_filter( 'woocommerce_format_price_range', function ( $price, $from, $to ) {
			return sprintf( '%s %s', esc_html__( 'From:', 'xstore' ), wc_price( $from ) );
		}, 10, 3 );
	}
}

function etheme_elementor_woocommerce_before_shop_loop() {
    if ( !apply_filters('etheme_shop_top_toolbar_enabled', true) ) return;
    if ( get_theme_mod( 'top_toolbar', 1 ) && !wc_get_loop_prop( 'is_shortcode' ) ) {
        etheme_enqueue_style('filter-area', true ); ?>
        <div class="filter-wrap">
        <div class="filter-content">
    <?php }
}

function etheme_elementor_woocommerce_after_shop_loop() {
    if ( !apply_filters('etheme_shop_top_toolbar_enabled', true) ) return;
    if ( get_theme_mod( 'top_toolbar', 1 ) && !wc_get_loop_prop( 'is_shortcode' ) ) { ?>
        </div>
        </div>
    <?php }
}
function etheme_elementor_product_archive_filters_wrapper($remove = false) {
    if ( $remove ) {
        remove_action('woocommerce_before_shop_loop', 'etheme_elementor_woocommerce_before_shop_loop', 0);
        remove_action('woocommerce_before_shop_loop', 'etheme_elementor_woocommerce_after_shop_loop', 45);
    }
    else {
        add_action('woocommerce_before_shop_loop', 'etheme_elementor_woocommerce_before_shop_loop', 0);
        add_action('woocommerce_before_shop_loop', 'etheme_elementor_woocommerce_after_shop_loop', 45);
    }
}

add_action('elementor/theme/before_do_archive', function () {
    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
        // force load css for pagination in editor mode
        etheme_enqueue_style('pagination');
    }
});
// disable Built-in compare/wishlist displayed on Single product templates
add_action('elementor/theme/before_do_single', function () {
    // disable default adding to single product let's use it as separate widget
    $actions_list = array(
        'etheme_sticky_cart_enabled',
        'xstore_compare_print_single_product_button',
        'xstore_wishlist_print_single_product_button',
        'xstore_waitlist_print_single_product_button',
        'etheme_sales_booster_safe_checkout',
        'etheme_sales_booster_estimated_delivery',
        'etheme_sales_booster_quantity_discounts'
    );
    foreach ($actions_list as $action) {
        add_filter($action, '__return_false');
    }
    if ( get_query_var('et_product-advanced-stock', false) ) {
        remove_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10);
    }

    if (! etheme_get_option( 'show_single_stock', 0 )){
        remove_filter( 'woocommerce_get_stock_html', '__return_empty_string', 2, 100);
    }
    remove_action( 'after_page_wrapper', 'etheme_sticky_add_to_cart', 1 );
//    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
//        etheme_enqueue_style('single-product-elements');
//    }
//    add_filter( "pre_option_etheme_single_product_builder", function($option) {
//        return true;
//    } );
});
add_action('elementor/theme/after_do_single', function () {
    $actions_list = array(
        'etheme_sticky_cart_enabled',
        'xstore_compare_print_single_product_button',
        'xstore_wishlist_print_single_product_button',
        'xstore_waitlist_print_single_product_button',
        'etheme_sales_booster_safe_checkout',
        'etheme_sales_booster_estimated_delivery',
        'etheme_sales_booster_quantity_discounts'
    );
    foreach ($actions_list as $action) {
        remove_filter($action, '__return_false');
    }
});

add_action('woocommerce_after_template_part', function ($template_name, $template_path, $located, $args) {
    switch (basename($template_name)) {
        case 'related.php':
        case 'cross-sells.php':
            if ( wc_get_loop_prop( 'etheme_default_elementor_products_widget', false ) ) {
                wc_set_loop_prop('etheme_default_elementor_products_widget', false);
            }
            break;
            default;
    }
}, 10, 4);

add_filter('elementor/widgets/wordpress/widget_args', function ($args, $widget) {
    $widget_instance = $widget->get_widget_instance();
    $args['before_title'] = apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' );
    $args['after_title'] = apply_filters('etheme_sidebar_after_title', '</span></h4>');

    $args['before_widget'] = '<div id="'.$widget_instance->id_base.'" class="sidebar-widget '.$widget_instance->widget_options['classname'].'">';
    $args['after_widget'] = '</div><!-- //sidebar-widget -->';
    return $args;
}, 10, 2);
add_action( 'elementor/widget/before_render_content', function ($widget) {
    switch ($widget->get_name()) {
        case 'wc-archive-products':
        case 'woocommerce-archive-products':
            $settings = $widget->get_settings_for_display();
            if ( !!!$settings['show_filters'] ) {
                add_filter('etheme_shop_filters_enabled', '__return_false');
            }
            if ( !!!$settings['show_grid_list'] ) {
                add_filter('etheme_grid_list_switcher_enabled', '__return_false');
            }
            if ( !!!$settings['show_per_page_select'] ) {
                add_filter('etheme_products_per_page_select_enabled', '__return_false');
            }
            if ( !!!$settings['show_filters'] && !!!$settings['show_grid_list'] && !!!$settings['show_per_page_select'] && !!!$settings['allow_order'] ) {
                add_filter('etheme_shop_top_toolbar_enabled', '__return_false');
            }
//            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                etheme_elementor_product_archive_filters_wrapper(true);
                etheme_elementor_product_archive_filters_wrapper();
                // fix pagination links
//                add_filter('etheme_elementor_theme_builder', '__return_true');
                if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                    // force filter pagination args in editor
                    add_filter('woocommerce_pagination_args', 'et_woocommerce_pagination');
                }
//            }
            break;
        case 'woocommerce-product-add-to-cart':
            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
            add_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            add_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
            if ( get_theme_mod( 'buy_now_btn', 0 ) ) {
//                $settings = $widget->get_settings_for_display();
                remove_action('woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10);
                add_action('woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 4);
            }
            break;
        case 'woocommerce-product-related':
            $settings = $widget->get_settings_for_display();
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                if ( $settings['et_type'] == 'slider' ) {
                    add_filter('related_slides_slider_args', function ($slider_args) {
                        $slider_args['is_preview'] = true;
                        return $slider_args;
                    });
                }
            }
            add_filter('related_limit', function($value) use ($settings) {
                return $settings['posts_per_page'] ? $settings['posts_per_page'] : $value;
            });
            add_filter('related_slides' ,function($value) use ($settings) {
               return wp_parse_args(array(
                   'large' => $settings['columns'],
                   'notebook' => $settings['columns'],
                   'tablet_land'     => $settings['columns_tablet'],
                   'tablet_portrait' => $settings['columns_mobile']
               ), $value);
            });
            add_filter('related_columns', function($value) use ($settings) {
                return $settings['columns'];
            });
            add_filter('related_type', function($value) use ($settings) {
                return $settings['et_type'];
            });
            if ( $settings['show_heading'] != 'yes')
                add_filter('woocommerce_product_related_products_heading', '__return_false');
            if ( $settings['et_type'] == 'grid' ) {
                wc_set_loop_prop( 'etheme_default_elementor_products_widget', true );
            }
            break;
        case 'woocommerce-product-upsell':
            $settings = $widget->get_settings_for_display();
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                if ( $settings['et_type'] == 'slider' ) {
                    add_filter('upsell_slides_slider_args', function ($slider_args) {
                        $slider_args['is_preview'] = true;
                        return $slider_args;
                    });
                }
            }
            add_filter('upsell_limit', function($value) use ($settings) {
                return $settings['posts_per_page'] ? $settings['posts_per_page'] : $value;
            });
            add_filter('upsell_slides' ,function($value) use ($settings) {
                return wp_parse_args(array(
                    'large' => $settings['columns'],
                    'notebook' => $settings['columns'],
                    'tablet_land'     => $settings['columns_tablet'],
                    'tablet_portrait' => $settings['columns_mobile']
                ), $value);
            });
            add_filter('upsell_columns', function($value) use ($settings) {
                return $settings['columns'];
            });
            add_filter('upsell_type', function($value) use ($settings) {
                return $settings['et_type'];
            });
            if ( $settings['show_heading'] != 'yes')
                add_filter('woocommerce_product_upsell_products_heading', '__return_false');

            if ( $settings['et_type'] == 'grid' ) {
                wc_set_loop_prop( 'etheme_default_elementor_products_widget', true );
            }
            break;
        case 'woocommerce-product-images':
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                add_filter('etheme_should_reinit_swiper_script', '__return_true');
                add_filter('etheme_elementor_edit_mode', '__return_true');
//                add_filter( "pre_option_etheme_single_product_builder", function($option) {
//                    return true;
//                } );
            }
            break;
        case 'woocommerce-cart':
            add_filter('etheme_elementor_cart_page', '__return_true');
            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
            add_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
            add_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
            break;
        case 'woocommerce-checkout-page':
            add_filter('etheme_elementor_checkout_page', '__return_true');
            break;
        default:
            break;
    }
}, 10 );

add_action( 'elementor/element/after_section_end', function( $widget, $section_id, $args ) {
    $widget_name = $widget->get_name();
    switch ($widget_name) {
        case 'mega-menu':
            if ( in_array($section_id, array('menu_toggle_section')) ) {
                $widget->start_injection([
                    'type' => 'section',
                    'at' => 'start',
                    'of' => 'menu_toggle_section',
                ]);
                $widget->add_control(
                    'custom_option',
                    [
                        'label' => esc_html__('Breakpoint', 'xstore'),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'description' => esc_html__('Note: Item layout will switch to dropdown on any screen smaller than the selected breakpoint.', 'xstore'),
                        'options' => array(
                            'class1' => 'class 01',
                            'class2' => 'class 02',
                        ),
                        'default' => 'class2',
                        'prefix_class' => 'e-n-menu-class-',
                        'frontend_available' => true,
                    ]
                );
                $widget->end_injection();
            }
            break;
        case 'woocommerce-product-related':
        case 'woocommerce-product-upsell':
        $options_2_update = array(
            'show_heading' => [
                'render_type' => 'template',
            ],
            'heading_color' => [
                'default' => get_theme_mod( 'dark_styles', false ) ? '#ffffff' : '#222222',
                'selectors' => ['.woocommerce {{WRAPPER}}.elementor-wc-products .products-title' => 'color: {{VALUE}}']
            ],
            'heading_typography' => [
                'selector' => '.woocommerce {{WRAPPER}}.elementor-wc-products .products-title'
            ],
            'heading_text_align' => [
                'selectors' => ['.woocommerce {{WRAPPER}}.elementor-wc-products .products-title' => 'text-align: {{VALUE}}']
            ],
            'heading_spacing' => [
                'selectors' => ['.woocommerce {{WRAPPER}}.elementor-wc-products .products-title' => 'margin-bottom: {{SIZE}}{{UNIT}}']
            ],
        );

            $typography = get_theme_mod('headings', array());
            if ( isset($typography['font-family']) && !empty($typography['font-family'])) {
                $fonts_list = \Elementor\Fonts::get_fonts();
                if (array_key_exists($typography['font-family'], $fonts_list)) {
                    $options_2_update['heading_typography_typography'] = [
                        'default' => 'custom'
                    ];
                    $options_2_update['heading_typography_font_family'] = [
                        'default' => $typography['font-family']
                    ];
                    $options_2_update['heading_typography_font_weight'] = [
                        'default' => $typography['font-weight']
                    ];
                }
            }
        foreach ($options_2_update as $option_2_update_key => $option_2_update_value ) {
            $widget->update_control($option_2_update_key, $option_2_update_value);
        }
        foreach ($widget->get_controls() as $widget_control_key => $widget_control_value) {
            if ( array_key_exists('selectors', $widget_control_value) && !array_key_exists('et_updated', $widget_control_value)) {
                $selectors = $widget_control_value['selectors'];
                $selectors_ready = array();
                foreach($selectors as $selector_key => $selector_value) {
                    $selectors_ready[str_replace(array('ul.products li.product', '.products > h2'), array('.product', '.products-title'), $selector_key)] = $selector_value;
                }
                $widget->update_control($widget_control_key, [
                    'selectors' => $selectors_ready
                ]);
            }
        }

        // update all widget with default woocommerce ul.products .product to .product
        foreach ($widget->get_controls() as $widget_control_key => $widget_control_value) {
            if ( array_key_exists('selectors', $widget_control_value)) {
                $selectors = $widget_control_value['selectors'];
                $selectors_ready = array();
                foreach($selectors as $selector_key => $selector_value) {
                    $selectors_ready[str_replace('ul.products li.product', '.product', $selector_key)] = $selector_value;
                }
                $widget->update_control($widget_control_key, [
                    'selectors' => $selectors_ready,
                    'et_updated' => true
                ]);
            }
        }
        $dark_mode = get_theme_mod( 'dark_styles', false );
        $active_color = get_theme_mod('activecol', '#a4004f');
        foreach (array(
                     'columns' => [
                         'render_type' => 'template',
                     ],
                     'title_color' => [
                         'default' => $dark_mode ? '#ffffff' : '#222222',
                         'selectors' => [
                             '{{WRAPPER}}.elementor-wc-products .product .product-title a' => 'color: {{VALUE}}',
                         ]
                     ],
                     'title_typography_typography' => [
                         'default' => 'custom'
                     ],
                     'title_typography' => [
                         'selector' => '{{WRAPPER}}.elementor-wc-products .product .product-title',
                     ],
                     'title_spacing' => [
                         'selectors' => [
                             '{{WRAPPER}}.elementor-wc-products .product .product-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                         ]
                     ],
                     'price_color' => [
                        'default' => '#888888'
                     ],
                     'price_typography_typography' => [
                         'default' => 'custom'
                     ],
                     'price_typography_font_size' => [
                         'default' => [
                             'size' => '',
                             'unit' => 'rem'
                         ]
                     ],
                     'price_typography_font_weight' => [
                         'default' => 'normal'
                     ],
                     'old_price_color' => [
                         'default' => $active_color
                     ],
                     'old_price_typography_typography' => [
                         'default' => 'custom'
                     ],
                     'old_price_typography_font_size' => [
                         'default' => [
                             'size' => '',
                             'unit' => 'rem'
                         ]
                     ],
                     'old_price_typography_font_weight' => [
                         'default' => 'normal'
                     ],
                     'align' => [
                         'selectors' => ['{{WRAPPER}}.elementor-wc-products .product .product-details' => 'text-align: {{VALUE}}']
                     ],
                     'automatically_align_buttons' => [
                         'type' => \Elementor\Controls_Manager::HIDDEN,
                     ],
                     'column_gap' => [
                         'condition' => [
                             'et_type' => 'grid',
                         ],
                     ],
                     'row_gap' => [
                         'condition' => [
                             'et_type' => 'grid',
                         ],
                     ])
                 as $option_2_update_key => $option_2_update_value ) {
            $widget->update_control($option_2_update_key, $option_2_update_value);
        }

        $widget->remove_control('wc_style_warning');

        if ( in_array($section_id, array('section_related_products_content', 'section_upsell_content')) ) {
            $widget->start_injection([
                'type' => 'section',
                'at' => 'start',
                'of' => $widget_name == 'woocommerce-product-related' ? 'section_related_products_content' : 'section_upsell_content',
            ]);

            $widget->add_control(
                'et_type',
                [
                    'label' => __('Type', 'xstore'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'grid',
                    'options' => [
                        'grid' => __('Grid', 'xstore'),
                        'slider' => __('Slider', 'xstore'),
                    ],
                ]
            );

            if ( $widget_name == 'woocommerce-product-upsell' ) {
                $widget->add_control(
                    'posts_per_page',
                    [
                        'label' => esc_html__( 'Products Per Page', 'xstore' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'default' => 4,
                        'range' => [
                            'px' => [
                                'max' => 20,
                            ],
                        ],
                    ]
                );
            }

            $widget->end_injection();
        }
        break;
        case 'heading':
        case 'woocommerce-product-title':
        case 'woocommerce-product-etheme_title': // own one
        case 'theme-site-title':
        case 'theme-page-title':
        case 'theme-archive-title':
        case 'theme-archive-etheme_title':
        case 'theme-post-title':
        case 'theme-post-etheme_title':
            $typography = get_theme_mod('headings', array());
            $widget->update_control('title_color', [
                'default' => get_theme_mod( 'dark_styles', false ) ? '#ffffff' : '#222222'
            ]);
            if ( isset($typography['font-family']) && !empty($typography['font-family'])) {
                $fonts_list = \Elementor\Fonts::get_fonts();
                if (array_key_exists($typography['font-family'], $fonts_list)) {
                    $widget->update_control('typography_typography', [
                        'default' => 'custom'
                    ]);
                    $widget->update_control('typography_font_family', [
                        'default' => $typography['font-family']
                    ]);
                }
            }
            if ( $widget_name == 'woocommerce-product-title' ) {
                $widget->update_control('typography_typography', [
                    'default' => 'custom'
                ]);
                $widget->update_control('typography_font_size', [
                    'default' => [
                        'size' => '24',
                        'unit' => 'px'
                    ]
                ]);
            }
            break;
        case 'wc-archive-products':
        case 'woocommerce-archive-products':
            // use next section after button because we make injections to previous section
            if ( $section_id == 'section_products_title' ) {
                $widget->update_control(
                    'show_result_count',
                    [
                        'type' => \Elementor\Controls_Manager::HIDDEN,
                        'default' => ''
                    ]
                );

                $widget->start_injection([
                    'type' => 'control',
                    'at' => 'after',
                    'of' => 'show_result_count',
                ]);

                $widget->add_control(
                    'show_filters',
                    [
                        'label' => esc_html__('Show Filters', 'xstore'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes'
                    ]
                );

                $widget->add_control(
                    'show_grid_list',
                    [
                        'label' => esc_html__('Show Grid/List Switchers', 'xstore'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes'
                    ]
                );

                $widget->add_control(
                    'show_per_page_select',
                    [
                        'label' => esc_html__('Show Per page', 'xstore'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'default' => 'yes'
                    ]
                );
                $widget->end_injection();
            }
            break;
        case 'woocommerce-product-add-to-cart':
            // use next section after button because we make injections to previous section
            if ( $section_id == 'section_atc_variations_style' ) {
                $widget->update_control(
                    'button_padding',
                    [
                        'selectors' => [
                            '{{WRAPPER}} .cart button, {{WRAPPER}} .cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                        ],
                    ]
                );

//                if ( get_theme_mod( 'buy_now_btn', 0 ) ) {

                    $widget->start_injection([
                        'type' => 'control',
                        'at' => 'before',
                        'of' => 'heading_view_cart_style',
                    ]);
                    $widget->add_control(
                        'heading_button_buy_now_style',
                        [
                            'label' => esc_html__('Buy now button', 'xstore'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $widget->start_controls_tabs('button_buy_now_style_tabs');

                    $widget->start_controls_tab('button_buy_now_style_normal',
                        [
                            'label' => esc_html__('Normal', 'xstore'),
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_text_color',
                        [
                            'label' => esc_html__('Text Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}' => '--single-buy-now-button-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_bg_color',
                        [
                            'label' => esc_html__('Background Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}' => '--single-buy-now-button-background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_border_color',
                        [
                            'label' => esc_html__('Border Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .cart .et-single-buy-now.single_add_to_cart_button.button, {{WRAPPER}} .cart .et-single-buy-now.single_add_to_cart_button.button' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->end_controls_tab();

                    $widget->start_controls_tab('button_buy_now_style_hover',
                        [
                            'label' => esc_html__('Hover', 'xstore'),
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_text_color_hover',
                        [
                            'label' => esc_html__('Text Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}' => '--single-buy-now-button-color-hover: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_bg_color_hover',
                        [
                            'label' => esc_html__('Background Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}' => '--single-buy-now-button-background-color-hover: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->add_control(
                        'button_buy_now_border_color_hover',
                        [
                            'label' => esc_html__('Border Color', 'xstore'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .cart .et-single-buy-now.single_add_to_cart_button.button:hover, {{WRAPPER}} .cart .et-single-buy-now.single_add_to_cart_button.button:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $widget->end_controls_tab();

                    $widget->end_controls_tabs();

                    $widget->end_injection();

//                }

            }
            break;
        case 'woocommerce-product-meta':
        case 'woocommerce-product-etheme_meta': // own one
            $widget->update_control('view', [
                'default' => 'stacked'
            ]);
            $widget->update_control('link_color', [
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .sku' => 'color: {{VALUE}}',
                ],
            ]);
            break;
        case 'woocommerce-product-rating':
            $widget->update_control('star_color',
                [
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating span:before' => 'color: {{VALUE}}',
                ],
            ]);
            $widget->update_control('star_size', [
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .star-rating:before, .woocommerce {{WRAPPER}} .star-rating span:before' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]);
            $widget->update_control('space_between', [
                'size_units' => [ 'px', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
                ],
            ]);
            break;
        case 'woocommerce-product-price':
        case 'woocommerce-product-etheme_price': // own one
            $active_color = get_theme_mod('activecol', '#a4004f');
            $widget->update_control('price_color', [
                'default' => '#888888'
            ]);
            if ( $active_color ) {
                $widget->update_control('sale_price_color', [
                    'default' => $active_color
                ]);
            }
            $widget->update_control('typography_typography', [
                'default' => 'custom'
            ]);
            $widget->update_control('typography_font_size', [
                'default' => [
                    'size' => '1.4',
                    'unit' => 'rem'
                ]
            ]);
            $widget->update_control('typography_font_weight', [
                'default' => 'normal'
            ]);
            break;
        case 'woocommerce-product-stock':
        case 'woocommerce-product-etheme_stock': // own one
            $widget->start_injection([
                'type' => 'control',
                'at' => 'after',
                'of' => 'text_color',
            ]);
            $widget->add_control(
                'outofstock_text_color',
                [
                    'label' => esc_html__( 'Out Of Stock Color', 'xstore' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .out-of-stock' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $widget->end_injection();
            break;
    }
}, 10, 3);

if ( defined('ELEMENTOR_PRO_VERSION') ) {
    add_filter('etheme_product_grid_list_product_data_source', function ($sources) {
        $sources['related_products'] = esc_html__( 'Related Products', 'xstore' );
        $sources['upsells'] = esc_html__( 'Upsells Products', 'xstore' );
        $sources['cross_sells'] = esc_html__( 'Cross-sells Products', 'xstore' );
        $sources['current_query'] = esc_html__( 'Current Query', 'xstore' );
        return $sources;
    });
    add_filter('etheme_posts_grid_list_post_data_source', function ($sources) {
        $sources['related_posts'] = esc_html__( 'Related Posts', 'xstore' );
        $sources['current_query'] = esc_html__( 'Current Query', 'xstore' );
        return $sources;
    });
}

add_filter('elementor/frontend/the_content', function ($content) {
    if ( get_query_var('et_is-cart', false) ) {
        if (false !== strpos($content, 'elementor-etheme_cart_placeholder')) {
            ob_start();
                woocommerce_output_all_notices();
            $wc_forms = ob_get_clean();
            $content = $wc_forms . do_shortcode('[woocommerce_cart]');
        }
    }
    elseif ( get_query_var('et_is-checkout', false) ) {
        if ( false !== strpos($content, 'etheme-elementor-cart-checkout-page-wrapper') ) {
//        if (get_query_var('et_is-checkout-page-elementor-shortcode', false) || false !== strpos($content, 'etheme-elementor-checkout-widgets-contain')) {

            if ( function_exists( 'WC' ) && ! WC()->checkout()->is_registration_enabled() && WC()->checkout()->is_registration_required() && ! get_query_var( 'et_is-loggedin', false) ) :
                $content = wp_kses_post( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'xstore' ) ) );
		    else :
                if ( false !== strpos($content, 'elementor-etheme_checkout_placeholder') ) {
                    $content = do_shortcode('[woocommerce_checkout]');
                }
                else {
//                    if ( get_query_var('et_is-checkout-page-elementor-shortcode', false) ) {
//
//                    }
//                    else {
//                    $container_based = \Elementor\Plugin::$instance->experiments->is_feature_active( 'container' );
                        ob_start();
                        woocommerce_output_all_notices();
//                        woocommerce_checkout_coupon_form();
//                        woocommerce_checkout_login_form();
                        $wc_forms = ob_get_clean();
//                        $wc_forms = '';
//                    if ( $container_based )
//                        $wc_forms = '<div class="e-con e-parent"><div class="e-con-inner"><div class="e-con e-child"><div>'.
//                            $wc_forms.
//                        '</div></div></div></div>';
                        $content = $wc_forms . '<form name="checkout" method="post" class="checkout woocommerce-checkout etheme-elementor-checkout-form" action="' . esc_url(wc_get_checkout_url()) . '" enctype="multipart/form-data">' .
                            $content .
                            '</form>';
//                    }
                }
            endif;
        }
    }
    return $content;
});

add_action('wp', function () {
    if ( is_admin() ) return;
    global $post;
    add_action('woocommerce_before_calculate_totals', function () {
        $document = \Elementor\Plugin::$instance->documents->get( url_to_postid( wp_get_referer() ) );

        if ( is_object( $document ) ) {
            $data = $document->get_elements_data();

            \Elementor\Plugin::$instance->db->iterate_data($data, function ($element) use (&$fragments) {
                if (
                    isset($element['widgetType'])
                ) {
                    switch ($element['widgetType']) {
                        case 'woocommerce-cart-etheme_page':
                        case 'woocommerce-cart-etheme_page_separated':
                            $widgets = \Elementor\Plugin::$instance->widgets_manager->get_widget_types();

                            foreach ( $widgets as $widget ) {
                                if ( $widget instanceof ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart\Cart_Page ) {
                                    $stretch_shipping_totals = false;
                                    $show_return_shop_button = true;

                                    if ( isset( $element['settings']['stretch_shipping_totals'] ) ) {
                                        $stretch_shipping_totals = !!$element['settings']['stretch_shipping_totals'];
                                    }

                                    if ( isset( $element['settings']['show_return_shop_button'] ) ) {
                                        $show_return_shop_button = !!$element['settings']['show_return_shop_button'];
                                    }

                                    remove_action( 'woocommerce_proceed_to_checkout', 'etheme_woocommerce_continue_shopping', 21 );
                                    if ( $show_return_shop_button )
                                        add_action( 'woocommerce_proceed_to_checkout', 'etheme_woocommerce_continue_shopping', 21 );
                                    if ( $stretch_shipping_totals )
                                        add_filter( 'etheme_cart_shipping_full_width', '__return_true' );
                                    }

                                }
                        break;
                    }
                }
            });
        }
    });
    add_filter('woocommerce_update_order_review_fragments', function ($fragments)  {
        $document = \Elementor\Plugin::$instance->documents->get( url_to_postid( wp_get_referer() ) );

        if ( is_object( $document ) ) {
            $data = $document->get_elements_data();

            \Elementor\Plugin::$instance->db->iterate_data($data, function ($element) use (&$fragments) {
                $added_fragment = false;
                if (
                    isset($element['widgetType'])
                ) {
                    switch ($element['widgetType']) {
                        case 'woocommerce-checkout-etheme_page':
                        case 'woocommerce-checkout-etheme_page_separated':
                        case 'woocommerce-checkout-etheme_page_multistep':
//                        case 'woocommerce-checkout-etheme_order_review':
                            $widgets = \Elementor\Plugin::$instance->widgets_manager->get_widget_types();

                            foreach ( $widgets as $widget ) {
                                if ( $widget instanceof ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout\Checkout_Page ) {
                                    $shipping_methods_separated = false;
                                    // in some widgets we have payments separated by default
                                    if ( $element['widgetType'] == 'woocommerce-checkout-etheme_page_multistep') {
                                        $shipping_methods_separated = true;
                                    }
                                    if ( isset( $element['settings']['shipping_methods_position'] ) ) {
                                        $shipping_methods_separated = $element['settings']['shipping_methods_position'] == 'separated';
                                    }
                                    if ( $shipping_methods_separated ) {
                                        if ( !$added_fragment )
                                            $fragments['.etheme-woocommerce-shipping-methods'] = $widget->get_shipping_methods_content();
                                        $added_fragment = true;
                                    }
                                }
                            }
                            break;
                    }
                }
            });
        }
        return $fragments;
    });
    add_action('woocommerce_checkout_update_order_review', function ($data) use ($post) {
        $document = \Elementor\Plugin::$instance->documents->get( url_to_postid( wp_get_referer() ) );

        if ( is_object( $document ) ) {
            $data = $document->get_elements_data();

            \Elementor\Plugin::$instance->db->iterate_data($data, function ($element) {
                if (
                    isset($element['widgetType'])
                ) {
                    switch ($element['widgetType']) {
                        case 'woocommerce-checkout-etheme_page':
                        case 'woocommerce-checkout-etheme_page_separated':
                        case 'woocommerce-checkout-etheme_page_multistep':
                        case 'woocommerce-checkout-etheme_order_review':
                        add_filter('etheme_checkout_order_review_product_details_one_column', '__return_true');
                        add_filter('theme_mod_cart_checkout_advanced_layout', '__return_false');
                        $shipping_methods_separated = false;
                        // in some widgets we have payments separated by default
                        if ( $element['widgetType'] == 'woocommerce-checkout-etheme_page_multistep') {
                            $shipping_methods_separated = true;
                            add_filter('etheme_checkout_form_shipping_methods_wrapper_classes', function ($classes) {
                                $classes[] = 'design-type-multistep';
                                return $classes;
                            });
                        }
                        if ( isset( $element['settings']['shipping_methods_position'] ) ) {
                            $shipping_methods_separated = $element['settings']['shipping_methods_position'] == 'separated';
                        }
                        if ( $shipping_methods_separated ) {
                            add_filter('etheme_checkout_form_shipping_methods', '__return_false');
                        }
                        add_action('woocommerce_review_order_before_submit', function () {
                            ?>
                            <div class="etheme-before-place-order-button">
                            <?php
                        }, 999);
                        add_action('woocommerce_review_order_after_submit', function () {
                            ?>
                            </div>
                            <?php
                        }, 999);
                        $order_review_features = array(
                            'etheme_checkout_order_review_product_images' => 'yes', // default value
                            'etheme_checkout_order_review_product_quantity' => 'yes', // default value
                            'etheme_checkout_order_review_product_remove' => '',
                            'etheme_checkout_order_review_product_link' => '',
                            'etheme_checkout_order_review_product_subtotal' => 'yes', // default value
                        );
                        foreach ($order_review_features as $order_review_feature => $order_review_feature_default_value) {
                            $order_review_feature_key = str_replace('etheme_checkout_', '', $order_review_feature);
                            if ( (!isset( $element['settings'][$order_review_feature_key] ) && !!$order_review_feature_default_value ) ||
                                (isset($element['settings'][$order_review_feature_key]) && !!$element['settings'][$order_review_feature_key] ) ) {
                                add_filter($order_review_feature, '__return_true');
                                if ( $order_review_feature_key == 'order_review_product_quantity' ) {
                                    if ( isset($element['settings']['order_review_product_quantity_style']) && $element['settings']['order_review_product_quantity_style'] != '' ) {
                                        add_filter($order_review_feature . '_style', function ($value) use ($element) {
                                            $quantity_style = $element['settings']['order_review_product_quantity_style'];
                                            if ( !$quantity_style )
                                                $quantity_style = 'square';
                                            return $quantity_style;
                                        });
                                        $quantity_input = !in_array($element['settings']['order_review_product_quantity_style'], array('', 'select'));
                                        if ($quantity_input) {
                                            add_filter('theme_mod_shop_quantity_type', function () {
                                                return 'input';
                                            });
                                        }
                                    }
                                    // could be later as an option
                                    //if ( isset($element['settings']['order_review_product_quantity_size']) && $element['settings']['order_review_product_quantity_size'] != '' ) {
                                        add_filter($order_review_feature . '_size', function ($value) use ($element) {
                                            return 'size-sm';
                                        });
                                    //}
                                }
                            }
                            else
                                add_filter($order_review_feature, '__return_false');
                        }
                            break;
                    }
                }
            });
        }
    });
});

// auto-update elementor support for customers after theme update
if ( !get_option('etheme_elementor_cpt_support_9_2_6', false) ) {
    $elementor_cpt_support = get_option( 'elementor_cpt_support' );
    if (!is_array($elementor_cpt_support)){
        $elementor_cpt_support = array('post', 'page', 'staticblocks', 'etheme_slides', 'testimonials', 'etheme_portfolio', 'product');
    } else {
        $elementor_cpt_support[] = 'etheme_slides';
    }
    update_option('elementor_cpt_support', $elementor_cpt_support);
    update_option('etheme_elementor_cpt_support_9_2_6', true);
}

// auto-update elementor support for customers after theme update
if ( !get_option('etheme_elementor_cpt_support_9_2_7', false) ) {
    $elementor_cpt_support = get_option( 'elementor_cpt_support' );
    if (!is_array($elementor_cpt_support)){
        $elementor_cpt_support = array('post', 'page', 'staticblocks', 'etheme_slides', 'etheme_mega_menus', 'testimonials', 'etheme_portfolio', 'product');
    } else {
        $elementor_cpt_support[] = 'etheme_mega_menus';
    }
    update_option('elementor_cpt_support', $elementor_cpt_support);
    update_option('etheme_elementor_cpt_support_9_2_7', true);
}

// use next placeholder content for AJAX filter replacement of existing description with the new one
add_filter('elementor-woocommerce-archive-etheme_description_placeholder', function ($placeholder_content) {
    if ( get_theme_mod( 'ajax_product_filter', 0 ) )
        return '<div class="screen-reader-text elementor-etheme_woocommerce_archive_placeholder">'.esc_html__('Placeholder for ajax description replacement', 'xstore').'</div>';
    return $placeholder_content;
});

// ADDED: Compatibility Single product builder with YITH WooCommerce Dynamic Pricing & Discounts Premium ( https://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/ )
add_filter('ywdpd_column_product_info_class', function($class) {
    if ( defined('ELEMENTOR_PRO_VERSION') )
        $class .= ', .elementor-location-single';
    return $class;
}, 99);