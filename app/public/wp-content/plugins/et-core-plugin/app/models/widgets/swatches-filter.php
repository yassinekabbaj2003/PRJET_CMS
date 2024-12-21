<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Swatches Filter Widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
if( ! class_exists( 'WC_Widget' ) ) return;

class Swatches_Filter extends WC_Widget {

	public function __construct() {
		// ! Get the taxonomies
		$attribute_array      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
			}
		}

		$this->widget_cssclass    = 'etheme_swatches_filter';
		$this->widget_description = esc_html__( 'Widget to filtering products by swatches attributes', 'xstore-core' );
		$this->widget_id          = 'etheme_swatches_filter';
		$this->widget_name        = '8theme - &nbsp;&#160;&nbsp;' . esc_html__( 'Swatches filter', 'xstore-core' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Filter by', 'xstore-core' ),
				'label' => esc_html__( 'Title', 'xstore-core' ),
			),
			'attribute' => array(
				'type'    => 'select',
				'std'     => '',
				'label'   => esc_html__( 'Attribute', 'xstore-core' ),
				'options' => $attribute_array,
			),
			'query_type' => array(
				'type'    => 'select',
				'std'     => '',
				'label'   => esc_html__( 'Query type', 'xstore-core' ),
				'options' => array(
					'and' => esc_html__( 'AND', 'xstore-core' ),
					'or'  => esc_html__( 'OR', 'xstore-core' ),
					'one_select' => esc_html__( 'One select', 'xstore-core' ),
				),
			),
			'search' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Show search', 'xstore-core' )
			),
			'ajax' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
			),
		);
		parent::__construct();
	}

	public function widget( $args, $instance ) {
		if (parent::admin_widget_preview(esc_html__('Swatches filter', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		if (is_admin()){
			return;
		}

		$search             = isset( $instance['search'] ) ? $instance['search'] : $this->settings['search']['std'];
		$ajax               = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

		$unique = $instance["attribute"] . '-' . $instance["query_type"];

		if (apply_filters('et_ajax_widgets', $ajax)){
			$instance['selector'] = '.etheme_swatches_filter.' . $unique;
			echo et_ajax_element_holder( 'Swatches_Filter', $instance, '', '', 'widget_filter', $args );
			return;
		}

		if ( ! is_shop() && ! is_product_taxonomy() ) return;

		global $wpdb;
		// ! Set main variables
		$html               = '';
//        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
		$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];

		$is_one_select = ($query_type == 'one_select') ? true : false;

		if ($is_one_select) {
			$query_type = 'AND';
		}

//	    $orderby            = wc_attribute_orderby( $taxonomy );

		// ! Set get_terms args
		$terms = get_terms( $taxonomy );

		// ! Set class
		$class = '';
		$class .= 'st-swatch-size-large';

		// ! Get the taxonomies attribute
		$origin_attr = substr( $taxonomy, 3 );
        $attribute_type = get_query_var('et_swatch_tax-'.$origin_attr, false);
        if ( !$attribute_type ) {
            $attr = $wpdb->get_row( $wpdb->prepare( "SELECT attribute_type FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $origin_attr ) );
            if ($attr && $attr->attribute_type) {
                $attribute_type = $attr->attribute_type;
                set_query_var('et_swatch_tax-' . $origin_attr, $attribute_type);
            }
        }

		$subtype      = '';
		$sw_shape = get_theme_mod('swatch_shape', 'default');
		$sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;

		$subtype = apply_filters('et_'.$attribute_type.'_swatch_filter_subtype', $subtype);
		$sw_custom_shape = apply_filters('et_'.$attribute_type.'_swatch_filter_shape', $sw_custom_shape);

		if ( strpos( $attribute_type, '-sq') !== false ) {
			$et_attribute_type = str_replace( '-sq', '', $attribute_type );
			if ( !$sw_custom_shape || $sw_custom_shape == 'square' ) {
				$class .= ' st-swatch-shape-square';
				$subtype      = 'subtype-square';
			}
			else if ( $sw_custom_shape == 'circle' ) {
				$class .= ' st-swatch-shape-circle';
			}
		} else {
			$et_attribute_type = $attribute_type;
			if ( !$sw_custom_shape || $sw_custom_shape == 'circle' ) {
				$class .= ' st-swatch-shape-circle';
			}
		}

		// ! Get current filter
		$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', urldecode($taxonomy) ) );
		$current_filter = isset( $_GET[ urldecode($filter_name) ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ urldecode($filter_name) ] ) ) ) : array();
		$is_tax_or_search = false;

		if ( ! is_rtl() ) {
			$current_filter = array_map( 'sanitize_title', $current_filter );
		}

		if ( is_product_category() || is_tax( 'brand' ) || is_product_tag() || is_search() ) {
			$is_tax_or_search = true;
			$term_counts  = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );

			if ( ! count( $term_counts ) ) {
				return;
			}
		}

		$current_page_url = false;

		foreach( $terms as $taxonomy ) {

			if ( $is_tax_or_search ) {
				if ( ! array_key_exists( $taxonomy->term_id, $term_counts) ) {
					continue;
				}
			}

			if (! is_object($taxonomy) || ! isset($taxonomy->term_id)){
				continue;
			}

			$all_filters = $current_filter;
			$metadata    = get_term_meta( $taxonomy->term_id, '', true );
			$current_page_url = $current_page_url ? $current_page_url : $this->get_current_page_url();
			$link        = remove_query_arg( urldecode($filter_name), $current_page_url );

			$data_tooltip = $taxonomy->name;
			$li_class  = '';

			// ! Generate link
			$decoded_slug = urldecode($taxonomy->slug);
			if ( ! in_array( $decoded_slug, $current_filter, true ) ) {
				$all_filters[] = $decoded_slug;
			} else {
				$key = array_search( $decoded_slug, $all_filters );
				unset( $all_filters[$key] );
				$li_class .= ' selected';
			}

			if ( ! empty( $all_filters ) ) {
				asort( $all_filters );

				if ($is_one_select && count($all_filters) && count($current_filter)) {
					$one_select_attr = (end($all_filters) == $current_filter[0]) ? $all_filters[1] : end($all_filters) ;
					$link = add_query_arg( $filter_name, $one_select_attr, $link );
				} else {
					$link = add_query_arg( $filter_name, implode( ',', $all_filters ), $link );
				}

				$decoded_taxonomy = sanitize_title( str_replace( 'pa_', '', urldecode($taxonomy->taxonomy) ) );
				if ( 'or' === $query_type && ! strpos($link, 'query_type_' . $decoded_taxonomy) && ! ( 1 === count( $all_filters ) ) ) {
					$link = add_query_arg( 'query_type_' . $decoded_taxonomy, 'or', $link );
				}
				$link = str_replace( '%2C', ',', $link );
			}

			// ! Generate html
			switch ( $et_attribute_type ) {
				case 'st-color-swatch':
					$value = ( isset( $metadata['st-color-swatch'] ) && isset( $metadata['st-color-swatch'][0] ) ) ? $metadata['st-color-swatch'][0] : '#fff';
					$html .= '<li class="type-color ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                    <a href="' . $link . '">
	                    <span class="st-custom-attribute" style="'. esc_attr( $this->generate_gradient_color_css($value) ) .'">
	                        <span class="screen-reader-text hidden">'.$data_tooltip.'</span>
	                    </span>
                    </a></li>';
					break;

				case 'st-image-swatch':
					$value = ( isset( $metadata['st-image-swatch'] ) && isset( $metadata['st-image-swatch'][0] ) ) ? $metadata['st-image-swatch'][0] : false;
					$image = ( $value ) ? wp_get_attachment_image( $value, apply_filters('sten_wc_filter_image_swatch_size', 'thumbnail') ) : wc_placeholder_img();
					$html .= '<li class="type-image ' . $subtype . $li_class . '"  data-tooltip="'.$data_tooltip.'">
                    <a href="' . $link . '">
						<span class="st-custom-attribute">'
					         . $image .
					         '<span class="screen-reader-text hidden">'.$data_tooltip.'</span>' .
					         '</span>
					</a>
					</li>';
					break;

				case 'st-label-swatch':
					$value = ( isset( $metadata['st-label-swatch'] ) && $metadata['st-label-swatch'][0] ) ? $metadata['st-label-swatch'][0] : false;

					if ( ! $value ) {
						$value = $taxonomy->name;
					}

					$html .= '<li class="type-label ' . $subtype . $li_class . '"><a href="' . $link . '"><span class="st-custom-attribute">' . $value . '</span></a></li>';
					break;

				default:
					$html .= '<li class="type-select ' . $li_class . '"><a href="' . $link . '"><span class="st-custom-attribute">' . $taxonomy->name . '</span></a></li>';
					break;
			}
		}

		if ( $html == '' ) return;

		$out = '';
		$out .= (isset($args['before_widget'])) ? str_replace('class="', ' class="type-'.$et_attribute_type.' '.$unique . ' ', $args['before_widget']) : '';
		$out .= parent::etheme_widget_title( $args, $instance );
		if ( $search ) {
			$out .= parent::render_widget_local_search_form(esc_html__('Search product attribute', 'xstore-core'));
		}
		$out .= '<ul class="st-swatch-preview st-color-swatch ' . esc_attr( $class ) . '">';
		$out .= $html;
		$out .= '</ul>';
		$out .= (isset($args['after_widget'])) ? $args['after_widget'] : '';

		echo $out;
//        echo '
//            <div class="sidebar-widget etheme_swatches_filter '.$unique.'">
//                ' . parent::etheme_widget_title($args, $instance) . '
//                <ul class="st-swatch-preview st-color-swatch ' . esc_attr( $class ) . '">
//                    ' . $html . '
//                </ul>
//            </div>
//        ';

	}

	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;
		$tax_query  = \WC_Query::get_main_tax_query();
		$meta_query = \WC_Query::get_main_meta_query();
		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}
		$meta_query     = new \WP_Meta_Query( $meta_query );
		$tax_query      = new \WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$term_ids_sql   = '(' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		if (strlen($term_ids_sql) < 3) {
			return array();
		}

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) AS term_count, terms.term_id AS term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND terms.term_id IN $term_ids_sql";

		$search = \WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query_sql         = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query_sql );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$results                      = $wpdb->get_results( $query_sql, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
			}
		}
		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}

	/**
	 * Generate color style
	 */
	public function generate_gradient_color_css($color) {
		$style = '';

		if (is_serialized($color)){
			$color = unserialize($color);
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
}