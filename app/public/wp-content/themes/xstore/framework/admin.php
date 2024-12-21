<?php  if ( ! defined('ETHEME_FW')) {exit('No direct script access allowed');}
// **********************************************************************//
// ! Add select2 styles and scripts admin widgets page
// **********************************************************************//
add_action( 'widgets_admin_page', 'etheme_load_selec2' );
function etheme_load_selec2(){
	wp_register_style( 'select2css', ETHEME_CODE_CSS . 'select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', ETHEME_CODE_JS . 'select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}

// **********************************************************************//
// ! Add admin styles and scripts
// **********************************************************************//
if(!function_exists('etheme_load_admin_styles')) {
	add_action( 'admin_enqueue_scripts', 'etheme_load_admin_styles', 150 );
	function etheme_load_admin_styles() {
		global $pagenow;

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

        wp_register_style('etheme_admin_panel_css', ETHEME_CODE_CSS.'et_admin-panel-styles.css');
        wp_register_style('etheme_admin_panel_options_css', ETHEME_CODE_CSS.'et_admin-panel-options.css');
        if ( defined('ET_CORE_VERSION'))
            wp_register_style('et-core-eight_theme-elementor-icon', ET_CORE_URL . 'app/assets/css/eight_theme-elementor-icon.css');

        wp_register_style('etheme_admin_panel_theme_builders_css', ETHEME_CODE_CSS.'et_admin-panel-theme-builders.css');

		if ( strpos($screen_id, 'et-panel') ) {
            wp_enqueue_script('etheme_panel_global');
		    wp_enqueue_style('etheme_admin_panel_css');
		    if ( strpos($screen_id, 'et-panel-sales-booster') ||
		    strpos($screen_id, 'et-panel-white-label-branding') ||
		    strpos($screen_id, 'et-panel-xstore-amp') ) {
		        wp_enqueue_style('etheme_admin_panel_options_css');
		    }
		    if ( strpos($screen_id, 'et-panel-theme-builders') ) {
		        wp_enqueue_style('etheme_admin_panel_theme_builders_css');
		    }
            else {
                // xstore icons needed only for menu in non-theme-builder page
                wp_enqueue_style('xstore-panel-nav-icons', ETHEME_CODE_CSS.'xstore-panel-nav-icons.css');
            }
		    if ( count($xstore_branding_settings) ) {
		        if ( isset($xstore_branding_settings['control_panel']) ) {
			        $colors = array();
			        $colors_output = array();
			        foreach ($xstore_branding_settings['control_panel'] as $color => $color_val) {
			            if ( strpos($color, '_color') !== false && $color_val ) {
			                $colors['--et_admin_' . str_replace('_', '-', $color)] = $color_val;
			            }
			        }
			        $styles = array();
			        foreach ($colors as $color_key => $color_val) {
			            $colors_output[] = $color_key . ':' . $color_val . ' !important';
			            if ( $color_key == '--et_admin_main-color' ) {
			                $styles[] = '.etheme-page-header {background-color: '.$color_val.';}';
			            }
			        }
			        if ( count($colors_output))
			            $styles[] = ':root {'.implode(';', $colors_output) . '}';

			        if ( count($styles) ) {
	                    wp_add_inline_style('etheme_admin_panel_css', implode(' ', $styles));
			        }
		        }
		    }
		}

	    wp_enqueue_style('farbtastic');
	    wp_enqueue_style('etheme_admin_css', ETHEME_CODE_CSS.'etheme_admin_backend-styles.css');
	    if ( is_rtl() ) {
	    	wp_enqueue_style('etheme_admin_rtl_css', ETHEME_CODE_CSS.'etheme_admin_backend-styles-rtl.css');
	    }

	    wp_register_style("etheme_font-awesome", get_template_directory_uri().'/css/fontawesome/4.7.0/font-awesome.min.css');

        if ( count($xstore_branding_settings) ) {

            if ( isset($xstore_branding_settings['control_panel']) && $xstore_branding_settings['control_panel']['icon'] ) {
                wp_add_inline_style('etheme_admin_css', ':root {--et-shortcodes-icon: url("'. $xstore_branding_settings['control_panel']['icon'] . '");}');
            }

            if ( isset($xstore_branding_settings['advanced']) ) {
	            wp_add_inline_style('etheme_admin_css', $xstore_branding_settings['advanced']['admin_css']);
	        }
		}

        if ( $pagenow == 'widgets.php' || strpos($screen_id, 'widgets' ) ) {
            $widgets_label_styles_selectors = [
                'div[id*=_et_]:before',
                'div[id*=_etheme]:before',
                'div[id*=null-instagram-feed]:before'
            ];
                $widgets_label_styles = implode(', ', $widgets_label_styles_selectors) . ' {
                    content: "'.apply_filters('etheme_theme_label', 'XStore').'";
                    position: absolute;
                    bottom: calc(100% - 10px);
                    background: var(--et_admin_dark-color);
                    color: #fff;
                    font-size: 0.65em;
                    line-height: 1;
                    padding: 4px 5px;
                    border-radius: 3px;
                }';
//                $widgets_label_styles .= implode(', ', str_replace(':before', ':hover:before', $widgets_label_styles_selectors)) . '{
//                    opacity: 0;
//                    visibility: hidden;
//                }';
            $widgets_all_label_styles_selectors = [];
            foreach ($widgets_label_styles_selectors as $widgets_label_styles_selector) {
                $widgets_all_label_styles_selectors[] = '.widgets-holder-wrap ' . $widgets_label_styles_selector . ' .widget-title';
            }
            $widgets_label_styles .= implode(', ', str_replace(':before', '', $widgets_all_label_styles_selectors)) . '{
                text-indent: -73px;
            }';
                wp_add_inline_style('etheme_admin_css', $widgets_label_styles);
            }

	    // Variations Gallery Images script
	    switch ($screen_id) {
            case 'elementor_page_elementor-element-manager':
                wp_enqueue_style('et-core-eight_theme-elementor-icon');
                break;
            case 'product':
            case 'edit-product':
                if (etheme_get_option('enable_variation_gallery', 0) ) {
                    wp_enqueue_script('etheme_admin_product_variations_js', ETHEME_CODE_JS.'product-variations.js', array('etheme_admin_js', 'wc-admin-product-meta-boxes', 'wc-admin-variation-meta-boxes'), false,true);
                }
                break;
            case 'product_page_product_attributes':
                // add more description for product_attributes slug
                $product_attributes_notice = esc_html__('Please use only Latin characters, numbers, and symbols such as "-" or "_".', 'xstore');
                wp_add_inline_script( 'etheme_admin_js', "
                    jQuery(document).ready(function($) {
                        $('#attribute_name').after('<p>".$product_attributes_notice."</p>');
                });", 'after' );
                break;
	    }
	}
}

if(!function_exists('etheme_add_admin_script')) {
	add_action('admin_init','etheme_add_admin_script', 1130);
	function etheme_add_admin_script(){
		global $pagenow;

	    add_thickbox();

	    wp_enqueue_script('theme-preview');
	    wp_enqueue_script('common');
	    wp_enqueue_script('wp-lists');
	    // wp_enqueue_script('postbox');
	    wp_enqueue_script('farbtastic');
//	    wp_enqueue_script('et_masonry', get_template_directory_uri().'/js/jquery.masonry.min.js',array(),false,true);

        wp_register_script( 'jquery_lazyload', ETHEME_BASE_URI . '/js/libs/jquery.lazyload.js', array('jquery') );
	    wp_enqueue_script('etheme_admin_js', ETHEME_CODE_JS.'admin-scripts.min.js', array(), false,true);

	    $localize_admin_script = array(
			'choose_image' => esc_html__( 'Choose Image', 'xstore' ),
            'theme_label' => apply_filters('etheme_theme_label', 'XStore'),
			'add_image'    => esc_html__( 'Add Images', 'xstore' ),
			'menu_enabled' => etheme_get_option('et_menu_options', 1),
            'plugins' => array(
                'et_core_plugin' => class_exists('ETC\App\Controllers\Admin\Import'),
                'woocommerce' => class_exists('WooCommerce'),
                'elementor' => defined( 'ELEMENTOR_VERSION' ),
            ),
            'icons' => array(
                'loader' => '<svg class="loader-circular" viewBox="25 25 50 50">
                    <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                </svg>',
              'close' => '<svg version="1.1" class="origin-size-svg" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M17.408 16l14.304-14.272c0.192-0.192 0.288-0.448 0.288-0.736 0-0.256-0.096-0.512-0.288-0.704-0.192-0.16-0.448-0.288-0.704-0.288 0 0 0 0 0 0-0.256 0-0.544 0.096-0.704 0.288l-14.304 14.304-14.272-14.304c-0.384-0.384-1.024-0.384-1.408 0-0.224 0.192-0.32 0.448-0.32 0.704 0 0.288 0.096 0.544 0.288 0.736l14.304 14.272-14.304 14.272c-0.192 0.192-0.288 0.448-0.288 0.736s0.096 0.512 0.288 0.704c0.384 0.384 1.024 0.384 1.408 0l14.304-14.304 14.272 14.272c0.192 0.192 0.448 0.288 0.704 0.288s0.512-0.096 0.704-0.288c0.192-0.192 0.288-0.448 0.288-0.704s-0.096-0.512-0.288-0.704l-14.272-14.272z"></path>
                </svg>'
            ),
            'messages' => array(
                'skip_import' => esc_html__('Your import process will be lost if you navigate away from this page.', 'xstore'),
                'skip_process' => esc_html__('Are you sure? Your delete process will be lost if you leave this page.', 'xstore')
            ),
			'wp_customize_url' => wp_customize_url()
		);

	    if ( class_exists('\Elementor\Plugin') && defined( 'ELEMENTOR_PRO_VERSION' ) ) {
	        $localize_admin_script['elementor_theme_builder_url'] = \Elementor\Plugin::$instance->app->get_settings('menu_url');
	    }

    	wp_localize_script( 'etheme_admin_js', 'et_variation_gallery_admin', apply_filters('etheme_admin_js_localize', $localize_admin_script) );
	}
}

add_action('init', 'etheme_white_label_filters', -999);

function etheme_white_label_filters () {
    $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

    if ( count($xstore_branding_settings) ) {
        $theme = wp_get_theme();

        $xstore_branding_settings['theme_template'] = $theme->template;

        if ( isset($xstore_branding_settings['advanced']) ) {

             add_filter( 'wp_prepare_themes_for_js', function($themes) use ($xstore_branding_settings){
                 if ( isset($xstore_branding_settings['advanced']['screenshot']) && $xstore_branding_settings['advanced']['screenshot'] )
                    $themes[$xstore_branding_settings['theme_template']]['screenshot'][0] = $xstore_branding_settings['advanced']['screenshot'];
                 if ( isset($xstore_branding_settings['advanced']['theme_name']) && $xstore_branding_settings['advanced']['theme_name'] )
                     $themes[$xstore_branding_settings['theme_template']]['name'] = $xstore_branding_settings['advanced']['theme_name'];
                 if ( isset($xstore_branding_settings['advanced']['theme_description']) && $xstore_branding_settings['advanced']['theme_description'] )
                     $themes[$xstore_branding_settings['theme_template']]['description'] = $xstore_branding_settings['advanced']['theme_description'];
                return $themes;
            } );

        }
        if ( isset($xstore_branding_settings['control_panel']) ) {
            if ( $xstore_branding_settings['control_panel']['label'] ) {
                $theme_label = $xstore_branding_settings['control_panel']['label'];
                add_filter('etheme_theme_label', function ($label) use ($theme_label) { return $theme_label;});
            }
            if ( isset($xstore_branding_settings['control_panel']['hide_updates']) && $xstore_branding_settings['control_panel']['hide_updates'] == 'on' ) {
                add_filter('etheme_hide_updates', '__return_true');
            }
            add_filter( 'wp_prepare_themes_for_js', function($themes) use ($xstore_branding_settings){
                if ( $xstore_branding_settings['control_panel']['theme_version'] ) {
                    $themes[$xstore_branding_settings['theme_template']]['version'] = $xstore_branding_settings['control_panel']['theme_version'];
                }
                return $themes;
            });
        }
        if ( isset($xstore_branding_settings['plugins_data'])) {
            add_filter( 'wp_prepare_themes_for_js', function($themes) use ($xstore_branding_settings){
                if ( isset($xstore_branding_settings['plugins_data']['author']) && !empty($xstore_branding_settings['plugins_data']['author'] ) ) {
                    $themes[$xstore_branding_settings['theme_template']]['author'] = $xstore_branding_settings['plugins_data']['author'];
                    // replace author name placed inside link
                    $themes[$xstore_branding_settings['theme_template']]['authorAndUri'] = str_replace('>8theme<', '>'.$xstore_branding_settings['plugins_data']['author'].'<', $themes[$xstore_branding_settings['theme_template']]['authorAndUri']);
                }
                if ( isset($xstore_branding_settings['plugins_data']['author_uri']) && !empty($xstore_branding_settings['plugins_data']['author_uri'] ) )
                    $themes[$xstore_branding_settings['theme_template']]['authorAndUri'] = '<a href="'.$xstore_branding_settings['plugins_data']['author_uri'].'">'.$themes[$xstore_branding_settings['theme_template']]['author'].'</a>';
                return $themes;
            } );
             if ( isset($xstore_branding_settings['plugins_data']['author']) && !empty($xstore_branding_settings['plugins_data']['author'] ) ) {
                $author_name = $xstore_branding_settings['plugins_data']['author'];
                add_filter('etheme_theme_author_name', function ($author) use ($author_name) { return $author_name;});
            }
             if ( isset($xstore_branding_settings['plugins_data']['documentation_url']) && !empty($xstore_branding_settings['plugins_data']['documentation_url'] ) ) {
                $documentation_url = $xstore_branding_settings['plugins_data']['documentation_url'];
                add_filter('etheme_documentation_url', function ($url) use ($documentation_url) { return $documentation_url; });
             }
             if ( isset($xstore_branding_settings['plugins_data']['support_url']) && !empty($xstore_branding_settings['plugins_data']['support_url'] ) ) {
                $support_url = $xstore_branding_settings['plugins_data']['support_url'];
                add_filter('etheme_support_forum_url', function ($url) use ($support_url) { return $support_url; });
             }

         }
    }
}

add_action('wp_ajax_et_ajax_required_plugins_popup', 'et_ajax_required_plugins_popup');
if ( !function_exists('et_ajax_required_plugins_popup') ) {
    function et_ajax_required_plugins_popup() {
         $response = array();
        ob_start();
        get_template_part( 'framework/panel/templates/popup-theme', $_POST['type']);
        $response['content'] = ob_get_clean();
        wp_send_json($response);
    }
}
add_action( 'wp_ajax_et_update_menu_ajax', 'et_update_menu_ajax' );
if ( ! function_exists('et_update_menu_ajax')) {

	function et_update_menu_ajax () {
        check_ajax_referer('etheme_update-menu-item', 'security');

        if (!current_user_can( 'manage_options' )){
            wp_send_json_error('Unauthorized access');
        }

		$post = $_POST['item_menu'];

		// update_post_meta( $post['db_id'], '_menu-item-disable_titles', $post['dis_titles']);
		update_post_meta( $post['db_id'], '_menu-item-anchor', sanitize_post($post['anchor']));
		update_post_meta( $post['db_id'], '_menu-item-design', sanitize_post($post['design']));
		update_post_meta( $post['db_id'], '_menu-item-design2', sanitize_post($post['design2']));
		update_post_meta( $post['db_id'], '_menu-item-column_width', $post['column_width']);
		update_post_meta( $post['db_id'], '_menu-item-column_height', $post['column_height']);

		update_post_meta( $post['db_id'], '_menu-item-sublist_width', $post['sublist_width']);

		update_post_meta( $post['db_id'], '_menu-item-columns', $post['columns']);
		update_post_meta( $post['db_id'], '_menu-item-icon_type', sanitize_post($post['icon_type']));
		update_post_meta( $post['db_id'], '_menu-item-icon', $post['icon']);
		update_post_meta( $post['db_id'], '_menu-item-label', sanitize_post($post['item_label']));
		update_post_meta( $post['db_id'], '_menu-item-background_repeat', sanitize_post($post['background_repeat']));
		update_post_meta( $post['db_id'], '_menu-item-background_position', $post['background_position']);
		update_post_meta( $post['db_id'], '_menu-item-use_img', sanitize_post($post['use_img']));
		update_post_meta( $post['db_id'], '_menu-item-widget_area', sanitize_post($post['widget_area']));
		update_post_meta( $post['db_id'], '_menu-item-static_block', sanitize_post($post['static_block']));

		echo json_encode($post);
		die();
	}
}

add_action( 'admin_footer', 'admin_template_js' );
function admin_template_js() {
	if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
	ob_start();
	?>
		<script type="text/html" id="tmpl-et-variation-gallery-image">
		    <li class="image">
		        <input type="hidden" name="et_variation_gallery[{{data.product_variation_id}}][]" value="{{data.id}}">
		        <img src="{{data.url}}">
		        <a href="#" class="delete remove-et-variation-gallery-image"></a>
		    </li>
		</script>
	<?php
	$data = ob_get_clean();
	echo apply_filters( 'et_variation_gallery_admin_template_js', $data );
}

add_action( 'woocommerce_save_product_variation', 'et_save_variation_gallery', 10, 2 );

add_action( 'woocommerce_product_after_variable_attributes', 'et_gallery_admin_html', 10, 3 );

if ( ! function_exists( 'et_gallery_admin_html' ) ):
		function et_gallery_admin_html( $loop, $variation_data, $variation ) {
			if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
			$variation_id   = absint( $variation->ID );
			$gallery_images = get_post_meta( $variation_id, 'et_variation_gallery_images', true );
			if ( !(bool)$gallery_images) {
			    // Compatibility with WooCommerce Additional Variation Images plugin
			    $gallery_images = get_post_meta($variation_id, '_wc_additional_variation_images', true);
                if ( (bool)$gallery_images )
                    $gallery_images = array_filter( explode( ',', $gallery_images ) );
            }
			?>
            <div class="form-row form-row-full et-variation-gallery-wrapper">
                <h4><?php esc_html_e( 'Variation Image Gallery', 'xstore' ) ?></h4>
                <div class="et-variation-gallery-image-container">
                    <ul class="et-variation-gallery-images">
						<?php
							if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {

								foreach ( $gallery_images as $image_id ):

									$image = wp_get_attachment_image_src( $image_id );

									?>
							        <li class="image">
							            <input type="hidden" name="et_variation_gallery[<?php echo esc_attr( $variation_id ); ?>][]" value="<?php echo esc_attr( $image_id ); ?>">
							            <img src="<?php echo esc_url( $image[ 0 ] ) ?>">
							            <a href="#" class="delete remove-et-variation-gallery-image"></a>
							        </li>

								<?php endforeach;
							}
						?>
                    </ul>
                </div>
                <p class="add-et-variation-gallery-image-wrapper hide-if-no-js">
                    <a href="#" data-product_variation_loop="<?php echo esc_attr($loop); ?>" data-product_variation_id="<?php echo absint( $variation->ID ) ?>" class="button add-et-variation-gallery-image"><?php esc_html_e( 'Add Gallery Images', 'xstore' ) ?></a>
                </p>
            </div>
			<?php
		}
	endif;

//-------------------------------------------------------------------------------
// Save Gallery
//-------------------------------------------------------------------------------
if ( ! function_exists( 'et_save_variation_gallery' ) ):
    function et_save_variation_gallery( $variation_id, $loop ) {
        if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}

        if ( isset( $_POST[ 'et_variation_gallery' ] ) ) {
            if ( isset( $_POST[ 'et_variation_gallery' ][ $variation_id ] ) ) {

                $gallery_image_ids = (array) array_map( 'absint', $_POST[ 'et_variation_gallery' ][ $variation_id ] );
                update_post_meta( $variation_id, 'et_variation_gallery_images', $gallery_image_ids );
            } else {
                delete_post_meta( $variation_id, 'et_variation_gallery_images' );
            }
        } else {
            delete_post_meta( $variation_id, 'et_variation_gallery_images' );
        }
    }
endif;

add_action( 'woocommerce_product_after_variable_attributes', 'et_extra_variation_options', 10, 3 );
if ( !function_exists('et_extra_variation_options')) {
    function et_extra_variation_options($loop, $variation_data, $variation) {
        if ( !etheme_get_option('variable_products_detach', false) ) {return;}
        ?>
        <div>
            <?php
                woocommerce_wp_text_input( array(
                    'id'    => "_et_product_variation_title[$loop]",
                    'label' => __( 'Custom variation title', 'xstore' ),
                    'type'  => 'text',
                    'value' => get_post_meta( $variation->ID, '_et_product_variation_title', true )
                    )
                );
            ?>
        </div>
        <?php
    }
}

add_action( 'woocommerce_save_product_variation', 'et_save_extra_variation_options', 100, 2 );
//add_action( 'woocommerce_new_product_variation', 'et_save_extra_variation_options', 10 );
//add_action( 'woocommerce_update_product_variation', 'et_save_extra_variation_options', 10 );
function et_save_extra_variation_options($variation_id, $i) {
//    if ( etheme_get_option( 'variable_products_detach', false ) ) {

        $custom_title = (isset($_POST['_et_product_variation_title']) && isset($_POST['_et_product_variation_title'][$i])) ? $_POST['_et_product_variation_title'][$i] : false;

        //$custom_title = $_POST['_et_product_variation_title'][$i];
        if ( ! empty( $custom_title ) ) {
            update_post_meta( $variation_id, '_et_product_variation_title', esc_attr( $custom_title ) );
        } else {
            delete_post_meta( $variation_id, '_et_product_variation_title' );
        }
//    }

    // sale price time start/end
    $_sale_price_time_start = $_POST['_sale_price_time_start'][$i];
    if ( ! empty( $_sale_price_time_start ) ) {
        update_post_meta( $variation_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_start' );
    }

    $_sale_price_time_end = $_POST['_sale_price_time_end'][$i];
    if ( ! empty( $_sale_price_time_end ) ) {
        update_post_meta( $variation_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_end' );
    }

    if ( apply_filters('etheme_product_option_gtin', true) ) {
        $_et_gtin = $_POST['_et_gtin'][$i];
        if ( ! empty( $_et_gtin ) ) {
            update_post_meta( $variation_id, '_et_gtin', esc_attr( $_et_gtin ) );
        } else {
            delete_post_meta( $variation_id, '_et_gtin' );
        }
    }
}

add_action( 'woocommerce_product_options_pricing', 'et_general_product_data_time_fields' );
function et_general_product_data_time_fields() {
	?>
	</div>
	<div class="options_group pricing show_if_simple show_if_external hidden">
	<?php

	woocommerce_wp_text_input(
	        array(
	                'id' => '_sale_price_time_start',
	                'label' => esc_html('Sale price time start', 'xstore'),
	                'placeholder' => esc_html( 'From&hellip; 12:00', 'xstore'),
                    'desc_tip' => 'true',
                    'description' => __( 'Only when sale price schedule is enabled', 'xstore' ),
                )
            );
	woocommerce_wp_text_input(
	        array(
	                'id' => '_sale_price_time_end',
	                'label' => esc_html('Sale price time end', 'xstore'),
	                'placeholder' => esc_html( 'To&hellip; 12:00', 'xstore' ),
                    'desc_tip' => 'true',
                    'description' => __( 'Only when sale price schedule is enabled', 'xstore' ),
                )
            );

}

if ( apply_filters('etheme_product_option_gtin', true) ) {
    add_action('woocommerce_product_options_sku', function() {
    //    global $product_object;
       woocommerce_wp_text_input(
                array(
                    'id'          => '_et_gtin',
    //                'value'       => get_post_meta( $product_object->ID, '_et_gtin', true ),
                    'placeholder'   => esc_html__('GTIN code', 'xstore'),
                    'label'         => '<abbr title="' . esc_attr__( 'Global Trade Item Number', 'xstore' ) . '">' . esc_html__( 'GTIN', 'xstore' ) . '</abbr>',
                    'desc_tip'      => true,
                    'description'   => __( 'Such identifiers are used to look up product information in a database (often by entering the number through a barcode scanner pointed at an actual product) which may belong to a retailer, manufacturer, collector, researcher, or other entity.', 'xstore' ),
                )
            );
    });
    add_action('woocommerce_variation_options', function($loop, $variation_data, $variation) {
        woocommerce_wp_text_input(
            array(
                'id'            => "_et_gtin{$loop}",
                'name'          => "_et_gtin[{$loop}]",
                'value'         => get_post_meta( $variation->ID, '_et_gtin', true ),
                'placeholder'   => esc_html__('GTIN code', 'xstore'),
                'label'         => '<abbr title="' . esc_attr__( 'Global Trade Item Number', 'xstore' ) . '">' . esc_html__( 'GTIN', 'xstore' ) . '</abbr>',
                'desc_tip'      => true,
                'description'   => __( 'Such identifiers are used to look up product information in a database (often by entering the number through a barcode scanner pointed at an actual product) which may belong to a retailer, manufacturer, collector, researcher, or other entity.', 'xstore' ),
                'wrapper_class' => 'form-row',
            )
        );
    }, 10, 3);
}

// -----------------------------------------
// 1. Add custom field input @ Product Data > Variations > Single Variation

add_action( 'woocommerce_variation_options_pricing', 'et_add_custom_field_to_variations', 10, 3 );

function et_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	?>

	<div class="form-field sale_price_time_fields">

		<?php
		$time_start = get_post_meta( $variation->ID, '_sale_price_time_start', true );
		$time_end = get_post_meta( $variation->ID, '_sale_price_time_end', true );

			woocommerce_wp_text_input(
				array(
					'id' => '_sale_price_time_start[' . $loop . ']',
					'wrapper_class' => 'form-row form-row-first',
					'label' => __( 'Sale price time start', 'xstore' ),
					'placeholder' => esc_html__( 'From&hellip; 12:00', 'xstore'),
					'value' => $time_start == 'Array' ? '' : $time_start
				)
			);
			woocommerce_wp_text_input(
				array(
					'id' => '_sale_price_time_end[' . $loop . ']',
					'wrapper_class' => 'form-row form-row-last',
					'label' => __( 'Sale price time end', 'xstore' ),
					'placeholder' => esc_html__( 'To&hellip; 12:00', 'xstore' ),
					'value' => $time_end == 'Array' ? '' : $time_end
				)
			);
		?>

	</div>

<?php }

// Hook to save the data value from the custom fields
add_action( 'woocommerce_process_product_meta', 'et_save_general_product_data_time_fields' );
function et_save_general_product_data_time_fields( $post_id ) {

	if (isset($_POST['_sale_price_time_start'])) {
		$_sale_price_time_start = $_POST['_sale_price_time_start'];
		update_post_meta( $post_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) );
	}

	if (isset($_POST['_sale_price_time_end'])) {
		$_sale_price_time_end = $_POST['_sale_price_time_end'];
		update_post_meta( $post_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) );
	}

	if ( isset($_POST['_et_gtin']) && apply_filters('etheme_product_option_gtin', true) ) {
        $_et_gtin = $_POST['_et_gtin'];
        if ( !is_array($_et_gtin) ){
            update_post_meta( $post_id, '_et_gtin', esc_attr( $_et_gtin ) );
        }
	}
}

// Add Bought Together
add_action( 'woocommerce_product_write_panel_tabs', 'et_add_product_bought_together_panel_tab' );
add_action( 'woocommerce_product_data_panels', 'et_add_product_bought_together_panel_data' );

//if ( function_exists('wc_get_product_types') ) {
//    foreach ( wc_get_product_types() as $value => $label ) {
//        add_action( 'woocommerce_process_product_meta_' . $value, 'et_save_product_bought_together_panel_data' );
//    }
//}

add_action( 'woocommerce_process_product_meta', 'et_save_product_bought_together_panel_data' );

function et_add_product_bought_together_panel_tab() {
        ?>
        <li class="et_bought_together_options et_bought_together_tab show_if_simple show_if_external">
            <a href="#et_bought_together_product_data"><span>
            <?php echo esc_html__( 'Bought together', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="background: var(--et_admin_dark-color, #222); color: #fff; font-size: 0.65em; line-height: 1; padding: 2px 5px; border-radius: 3px; margin: 0; margin-inline-start: 3px;">'.apply_filters('etheme_theme_label', 'XStore').'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

function et_add_product_bought_together_panel_data() {
        global $post;
        $exclude_types = wc_get_product_types();
        unset($exclude_types['simple']);
        $exclude_types = array_keys($exclude_types);
        ?>
        <div id="et_bought_together_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <label for="et_bought_together_ids"><?php _e( 'Products', 'xstore' ); ?></label>

                        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="et_bought_together_ids" name="et_bought_together_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'xstore' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="<?php echo implode(',', $exclude_types); ?>">

                            <?php
                                $product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_et_bought_together_ids', true ) ) );

                                foreach ( $product_ids as $product_id ) {
                                    $product = wc_get_product( $product_id );
                                    if ( is_object( $product ) ) {
                                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
                                    }
                                }
                            ?>
                        </select>

                    <?php echo wc_help_tip( __( 'Bought Together are products which you recommend to be bought along with this product. Only simple products and product variations can be added as accessories.', 'xstore' ) ); ?>
                </p>
            </div>
        </div>
        <?php
    }

function et_save_product_bought_together_panel_data( $post_id ) {
    $et_bought_together = isset( $_POST['et_bought_together_ids'] ) ? array_map( 'intval', (array) $_POST['et_bought_together_ids'] ) : array();
    update_post_meta( $post_id, '_et_bought_together_ids', $et_bought_together );
}

// WooCommerce settings
add_filter('woocommerce_account_settings', function($settings) {
    $updated_settings = array();

      foreach ( $settings as $section ) {

          $updated_settings[] = $section;

        // at the bottom of the General Options section
        if ( isset( $section['id'] ) && 'account_registration_options' == $section['id'] &&
           isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

            $updated_settings[] = array(
                'type'  => 'et_custom_section_start',
                'id' => 'et_custom_section_start'
            );

            $updated_settings[] = array(
                'title' => __( 'XStore "My account" page settings', 'xstore' ),
                'type'  => 'title',
                'id'    => 'et_wc_account_options',
            );

              $updated_settings[] = array(
                'title'    => __( 'Account page type', 'xstore' ),
                'id'       => 'et_wc_account_page_type',
                'default'  => 'new',
                'type'     => 'select',
                'options'  => array(
                        'default'     => esc_html__( 'Default', 'xstore' ),
                        'new'         => esc_html__( 'New', 'xstore' ),
                    ),
                'autoload'      => false
                );

              $updated_settings[] = array(
                'name'     => __( 'Account banner', 'xstore' ),
                'id'       => 'et_wc_account_banner',
                'type'     => 'textarea',
                'css'      => 'min-width:300px;',
                'desc_tip'     => __( 'You can add simple html or staticblock shortcode', 'xstore' ),
                'autoload'      => false
              );

              $updated_settings[] = array(
                'title'    => __( 'Products type', 'xstore' ),
                'id'       => 'et_wc_account_products_type',
                'default'  => 'random',
                'type'     => 'select',
                'options'  => array(
                        'featured'     => esc_html__( 'Featured', 'xstore' ),
                        'sale'         => esc_html__( 'On sale', 'xstore' ),
                        'bestsellings' => esc_html__( 'Bestsellings', 'xstore' ),
                        'recently_viewed'         => esc_html__( 'Recently viewed', 'xstore' ),
                        'none' => esc_html__( 'None', 'xstore' ),
                        'random'       => esc_html__( 'Random', 'xstore' ),
                    ),
                'autoload'      => false
                );
                $updated_settings[] = array(
                    'title'    => __( 'Navigation icons', 'xstore' ),
                    'desc'          => __( 'Show icons on the "My account" page for the account navigation', 'xstore' ),
                    'id'            => 'et_wc_account_nav_icons',
                    'default'       => 'yes',
                    'type'          => 'checkbox',
                    'autoload'      => false
                );

              $updated_settings[] = array(
                    'type' => 'sectionend',
                    'id'   => 'et_wc_account_options',
                );

              $updated_settings[] = array(
                'type'  => 'et_custom_section_end',
                'id'  => 'et_custom_section_end',
            );
        }

      }

      return $updated_settings;
});

add_action('woocommerce_admin_field_et_custom_section_start', function() {
    echo '<div class="et-wc-section-wrapper">';
});
add_action('woocommerce_admin_field_et_custom_section_end', function() {
    echo '</div>';
});

// WooCommerce status
add_filter('woocommerce_debug_tools', function($settings) {
   $settings['clear_et_brands_transients'] = array(
        'name'   => __( 'Brands transients', 'xstore' ),
        'button' => __( 'Clear transients', 'xstore' ),
        'desc'   => __( 'This tool will clear the brands transients cache.', 'xstore' ),
        'callback' => 'etheme_clear_brands_transients'
    );
    return $settings;
});

function etheme_clear_brands_transients() {
    delete_transient('wc_layered_nav_counts_brand');
}

add_filter('et_ajax_widgets', '__return_false');
add_filter('etheme_ajaxify_lazyload_widget', '__return_false');
add_filter('etheme_ajaxify_elementor_widget', '__return_false');

if (etheme_get_option('old_widgets_panel_type', 0)){
    add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
	add_filter( 'use_widgets_block_editor', '__return_false' );
}

add_action( 'admin_enqueue_scripts', function ($hook){
    if ( 'nav-menus.php' == $hook ) {
        wp_enqueue_script('etheme_admin_menus_js', ETHEME_CODE_JS.'admin-menus.js', array(), false,true);
    }
    if ( 'themes.php' == $hook ) {
        wp_enqueue_script('etheme_admin_major_update_js', ETHEME_CODE_JS.'admin-major-version.js', array(), false,true);
    }
} );

add_action('vc_backend_editor_enqueue_js_css', function () {
    wp_enqueue_script('etheme_admin_vc_js', ETHEME_CODE_JS.'admin-vc.js', array('vc-backend-actions-js'), false,true);
});

// remove fake sales transients for products in order on it's status change action
if ( get_option('xstore_sales_booster_settings_fake_product_sales', false) ) {
    add_action( 'woocommerce_order_status_changed', function ($order_id) {

        $orders = get_posts( array(
            'numberposts' => -1,
            'post_type'   => array( 'shop_order' ),
            'post_status' => array_keys(wc_get_order_statuses()),
            'post__in' => array($order_id)
        ));

        // reset fake sale popup transient products from orders
        delete_transient('etheme_fake_sale_popup_orders_rendered');
        foreach ( $orders as $order_id ) {
            $order = wc_get_order( $order_id );
            foreach ( $order->get_items() as $item_id => $item_values ) {
                delete_transient( 'etheme_fake_product_sales_' . $item_values->get_product_id() );
            }
        }

     }, 30, 1 );
}

function et_ai_msg(){
    $woo_msg = '';
    if (class_exists( 'WooCommerce' )){

        $products = get_posts( array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'posts_per_page'      => 1,
            'orderby'             => 'date',
            'order'               => 'ASC',
        ) );

        $url = admin_url( 'edit.php?post_type=product' );

        if ($products && isset($products[0]) && isset($products[0]->ID)){
            $url = admin_url( 'post.php?post='.$products[0]->ID.'&action=edit&et_autofocus=et_open_ai' );
        }

        $woo_msg = '
            <p>'.sprintf(esc_html__('Additionally, you can generate content for your, products by going to %s WooCommerce -> Products -> Edit %s and scrolling down to the bottom of the page.', 'xstore'), '<a href="'. $url .'" target="_blank" style="display: inline">', '</a>') . '</p>
        ';
    }

    $pages = get_posts( array(
        'post_type'           => 'page',
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'orderby'             => 'date',
        'order'               => 'ASC',
    ) );

    $url = admin_url( 'edit.php?post_type=page' );

    if ($pages && isset($pages[0]) && isset($pages[0]->ID)){
        $url = admin_url( 'post.php?post='.$pages[0]->ID.'&action=edit&et_autofocus=et_open_ai' );
    }

    return '
        <div class="et_ai-saved-options">
            <br>
            <p>'.sprintf(esc_html__('To use ChatGPT (OpenAI) functionality, please visit your %s WordPress Dashboard -> Pages -> Choose Page and Edit it %s -> Scroll down to the bottom of the page, and you will find the %s AI Assistant tab.', 'xstore'),
            '<a href="'. $url .'" target="_blank" style="display: inline">', '</a>', apply_filters('etheme_theme_label', 'XStore')).'</p>'.$woo_msg.'
        </div>
    ';
}

function et_get_current_domain() {
    $domain = get_option('siteurl'); //or home
    $domain = str_replace('http://', '', $domain);
    $domain = str_replace('https://', '', $domain);
    $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
    return urlencode($domain);
}

function et_check_domain_pattern($domain){
    $patterns = et_get_patterns();

    // Ignore some local domains by user requests
    foreach ($patterns['ignore_local'] as $local) {
        if (fnmatch($local, $domain)) {
            return false;
        }
    }

    // Check if the domain matches any of the patterns
    foreach ($patterns['patterns'] as $pattern) {
        if (fnmatch($pattern, $domain)) {
            return true;
        }
    }

    // Check if the domain matches the "stagingN.*" pattern
    if (preg_match('/^staging\d+\..*$/', $domain)) {
        return true;
    }

    return false;
}

function et_get_patterns(){
    $patterns = get_transient('etheme_domain_patterns');

    if (! $patterns || isset($_GET['etheme_clear_domain_patterns_transient'])){
        $patterns = et_get_remote_patterns();
        if (!$patterns){
            $patterns = et_get_local_patterns();
        }
        set_transient('etheme_domain_patterns', $patterns, WEEK_IN_SECONDS);
    }

    return $patterns;;
}

function et_get_remote_patterns(){
   $responce = wp_remote_get('https://www.8theme.com/import/xstore-demos/1/patterns/');

   if (wp_remote_retrieve_response_code($responce) == 200){
        $responce = wp_remote_retrieve_body($responce);
        if ($responce && ! is_wp_error($responce)){
            $responce = json_decode($responce, true);
            if ($responce){
                return $responce;
            }
        }
   }
   return false;
}

function et_get_local_patterns(){
    return array(
        'patterns' => array(
            'localhost',
            'localhost*',
            '127.0.0.0',
            '127.0.0.1',
            'test.*',
            'local.*',
            '*local*',
            'dev.*',
            'dev-*',
            '*-dev',
            'staging.*',
            'website.*',
            '*.test',
            '*.local',
            '*.dev',
            '*.staging',
            '*.wpengine.com',
            '*.wpcomstaging.com',
            '*.flywheelstaging.com',
            '*.dreamhosters.com',
            '*.kinsta.cloud',
            '*.pantheonsite.io',
            '*.myftpupload.com',
            '*.cloudwaysapps.com',
            '*.staging.wpmu.host',
            '*.pressdns.com',
            '*WooCommerce*',
            '*Wordpress*',
            '*woocommerce*',
            '*wordpress*',
            '*.demo',
            'demo*',
            'backup.*',
            '*.sg-host.com',
        ),
        'ignore_local' => array(
                'demonlocate.com'
        )
    );
}

add_action( 'admin_notices', function () {

    $system = class_exists('Etheme_System_Requirements') ? Etheme_System_Requirements::get_instance() : new Etheme_System_Requirements();

    $system->admin_notices();

}, 30);

add_action('admin_head', function() {
    $screen = get_current_screen();

    if ( 'etheme_slides' != $screen->post_type )
        return;

    $about_slides = '<p>' . sprintf(__( 'The %s slides feature empowers you to effortlessly craft dynamic slides, comparable to Revolution Slider but with superior speed, simplified configuration, and enhanced website performance. Leveraging the WordPress custom post type named "Slides" this functionality ensures a seamless and efficient experience for your website.', 'xstore'), apply_filters('etheme_theme_label', 'XStore')) . '</p>';

	$screen->add_help_tab(
		array(
			'id'      => 'about-slides',
			'title'   => __( 'About Slides', 'xstore' ),
			'content' => $about_slides,
		)
	);

	$screen->add_help_tab(
		array(
			'id'      => 'managing-slides',
			'title'   => __( 'Managing Slides', 'xstore' ),
			'content' =>
					'<p>' . __( 'Generate captivating slides effortlessly by navigating to Dashboard -> Slides -> Add New. Each new slide mirrors a post, offering the flexibility to craft compelling content seamlessly through the website\'s page builder.', 'xstore' ) . '</p>'
		)
	);

	$screen->set_help_sidebar(
		'<p><strong>' . __( 'For more information:', 'xstore' ) . '</strong></p>' .
		'<p><a href="'.etheme_documentation_url(false, false).'">'.esc_html__('Documentation', 'xstore').'</a></p>' .
		'<p><a href="'.etheme_support_forum_url().'">'.esc_html__('Support forum', 'xstore').'</a></p>'
	);

});

if ( get_option('etheme_maintenance_mode', false) ) {
    global $pagenow;
    if ( $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'page' ) {
        // Add a post display state for special Maintenance page.
        add_filter( 'display_post_states', 'etheme_maintenance_page_state', 10, 2 );
        function etheme_maintenance_page_state($post_states, $post) {
            if ( 'maintenance.php' == basename(get_page_template($post->ID)) ) {
                $post_states['xstore_page_for_maintenance'] = __( 'Maintenance page', 'xstore' );
            }

            return $post_states;
        }
    }
}