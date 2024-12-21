<?php
namespace ETC\App\Traits;

/**
 * Widget Trait
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Core/Registry
 */
trait Widget {

	public static function widget_label( $label, $id ) {
		echo "<label for='{$id}'>{$label}</label>";
	}

	public static function widget_input_checkbox( $label, $id, $name, $checked, $value = 1 ) {
		echo "\n\t\t\t<p>";
		echo "<label for='{$id}'>";
		echo "<input type='checkbox' id='{$id}' value='{$value}' name='{$name}' {$checked} /> ";
		echo "{$label}</label>";
		echo '</p>';
	}

	public static function widget_textarea( $label, $id, $name, $value ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<textarea id='{$id}' name='{$name}' rows='3' cols='10' class='widefat'>" . ( $value ) . "</textarea>";
		echo '</p>';
	}

	public static function widget_input_text( $label, $id, $name, $value ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<input type='text' id='{$id}' name='{$name}' value='" . strip_tags( $value ) . "' class='widefat' />";
		echo '</p>';
	}

	public static function widget_input_image( $label, $id, $name, $value ) {
		$out = "\n\t\t\t<p>";
		self::widget_label( $label, $id );

		$class = ( $value ) ? 'selected' : '' ;

		$out .= '<div class="media-widget-control ' . $class . '">';
		$out .= '<div class="media-widget-preview etheme_media-image">';
		if ( $value ) {
			$out .= '<img class="attachment-thumb etheme_upload-image" src="' . $value . '">';
		} else {
			$out .= '<div class="attachment-media-view">';
			$out .= '<div class="placeholder etheme_upload-image">' . esc_html__( 'No image selected', 'xstore-core' ) . '</div>';
			$out .= '</div>';
		}
		$out .= '</div>';
		$out .= '<p class="media-widget-buttons">';
		if ( $value ) {
						//$out .= '<button type="button" class="button edit-media selected">Edit Image</button>';
			$out .= '<button type="button" class="button change-media select-media etheme_upload-image selected">' . esc_html__( 'Replace Image', 'xstore-core' ) . '</button>';
		} else {
			$out .= '<button type="button" class="button etheme_upload-image not-selected">' . esc_html__( 'Add Image', 'xstore-core' ) . '</button>';
		}
		$out .= '</p>';
		$out .= '<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . strip_tags( $value ) . '" class="widefat" />';
		$out .= '</div>';
		$out .= '</p>';
		echo $out;
	}

	public static function widget_input_dropdown( $label, $id, $name, $value, $options ) {
		echo "\n\t\t\t<p>";
		self::widget_label( $label, $id );
		echo "<select id='{$id}' name='{$name}' class='widefat'>";
		$val = current( array_flip( $options ) );
		if( ! empty( $val ) ) echo '<option value=""></option>';
		foreach ($options as $key => $option) {
			echo '<option value="' . $key . '" ' . selected( strip_tags( $value ), $key ) . '>' . $option . '</option>';
		}
		echo "</select>";
		echo '</p>';
	}

	public static function etheme_stock_taxonomy( $term_id = false, $taxonomy = 'product_cat', $category = false, $stock = true ) {

		if ( $term_id === false ) return false;

		$type = apply_filters('etheme_stock_taxonomy_type', 'new');

		$args = array(
			'post_type'         => 'product',
			'posts_per_page'    => -1,
			'tax_query'         => array(
				array(
					'taxonomy'  => $taxonomy,
					'field'     => 'term_id',
					'terms'     => $term_id
				),
			),
		);

		// ! new meta
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ){
			$args['meta_query'][] = array(
				'key' => '_stock_status',
				'value' => 'instock'
			);
		}


		if ( $category ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug',
				'terms'            => $category,
				'include_children' => true,
				'operator'         => 'IN'
			);
		}


		if ($type == 'new') {
			$query = new \WP_Query( $args );
			return (int)$query->post_count;
		} else {
			// ! old meta
			$cat_prods = get_posts( $args );
			$i = 0;
			foreach ( $cat_prods as $single_prod ) {
				$product = wc_get_product( $single_prod->ID );
				if ( ! $stock ) {
					$i++;
				} elseif( $product->is_in_stock() === true ){
					$i++;
				}

			}
			return $i;
		}
		return 0;
	}

	// @todo Add pretty custom preview (title, image, description ...)
	public static function admin_widget_preview($name) {
		if (isset($_GET['legacy-widget-preview']) || defined( 'REST_REQUEST' ) && REST_REQUEST){
			echo '<div class="et-no-preview"><h3>8theme - '. $name .'</h3><p>No preview available.</p></div>';
			echo '<style>
                .et-no-preview{
                    font-size: 13px;
                    background: #f0f0f0;
                    padding: 8px 12px;
                    color: #000;
                    font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif;
                }
                .et-no-preview h3 {
                    font-size: 14px;
                    font-family: inherit;
                    font-weight: 600;
                    margin: 4px 0;
                }
                .et-no-preview p {
                    margin: 4px 0;
                    font-size: 13px;
                }
            </style>';
		} else {
			return false;
		}
	}

	public static function etheme_widget_title($args, $instance){
		$title = apply_filters( 'widget_title', ( $instance['title'] ?? '' ) );
		$args = apply_filters( 'etheme_widget_args', $args );
		if ( $title ) {
			return $args['before_title'] . $title . $args['after_title'];
		}
		return '';
	}

    public static function render_widget_local_search_form($placeholder_text = '') {
        $placeholder_text = $placeholder_text ? $placeholder_text : esc_html__('Search', 'xstore-core');
        return '<div class="etheme-widget_local_search-wrapper">'.
            '<input type="text" class="etheme-widget_local_search" placeholder="'.$placeholder_text.'">'.
            '<span class="buttons-wrapper flex flex-nowrap pos-relative">
                <span class="clear flex-inline justify-content-center align-items-center pointer">
                    <span class="et_b-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                        </svg>
                    </span>
                </span>'.
                '<button type="submit" class="search-button flex justify-content-center align-items-center pointer" aria-label="'.$placeholder_text.'">
                    <span class="et_b-loader"></span>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path></svg>'.
                    '<span class="screen-reader-text">'.$placeholder_text.'</span></button>'.
            '</span>'.
        '</div>'.
            '<div class="etheme-widget_local_search-message text-center animated animated-fast fadeIn hidden">'.esc_html__('Sorry, we couldn\'t find what you\'re looking for', 'xstore-core').'</div>';
    }


// only if there are Search locations created for Search results page builder then we should redirect the customer
// to the search results built page
    public static function etheme_should_redirect_to_archive() {
        if ( !defined('ELEMENTOR_VERSION')) return true;

        $created_templates = get_posts(
            [
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'tax_query' => [
                    [
                        'taxonomy' => 'elementor_library_type',
                        'field' => 'slug',
                        'terms' => 'search-results',
                    ],
                ],
                'meta_query'     => array(
                    array(
                        'key'     => '_elementor_conditions',
                        'value'   => 'include/archive/search',
                        'compare' => 'LIKE'
                    )
                ),
                'fields' => 'ids'
            ]
        );

        // originally we should display
        if ( count($created_templates) ) {
            $should_redirect_to_shop = false;
//            foreach ($created_templates as $created_template) {
//                if ( $should_redirect_to_shop ) break;
//                $should_redirect_to_shop = in_array('include/archive', (array)get_post_meta($created_template, '_elementor_conditions', true));
//            }
            return $should_redirect_to_shop;
        }
        return true;
    }
}
