<?php


namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;

/**
 * Categories filter.
 *
 * @since      5.1.6
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
if ( ! class_exists( 'WC_Widget' ) ) {
	return;
}

class Categories_Filter extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		//$this->widget_cssclass    = 'sidebar-widget etheme-brands-filter';
		$this->widget_cssclass = 'etheme_widget_cats_filter etheme_widget_cats';
		$this->widget_description = esc_html__( 'Widget to filtering products by categories', 'xstore-core' );
		$this->widget_id          = 'etheme_product_categories_filter';
		$this->widget_name        = '8theme - &nbsp;&#160;&nbsp;' . esc_html__( 'Filter Products by Categories', 'xstore-core' );
		$this->settings           = array(
			'title'        => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Filter by categories', 'xstore-core' ),
				'label' => esc_html__( 'Title', 'xstore-core' ),
			),
			'display_type' => array(
				'type'    => 'select',
				'std'     => 'list',
				'label'   => esc_html__( 'Display type', 'xstore-core' ),
				'options' => array(
					'list'     => esc_html__( 'List', 'xstore-core' ),
					'dropdown' => esc_html__( 'Dropdown', 'xstore-core' ),
					'select2'  => esc_html__( 'Dropdown Advanced', 'xstore-core' ),
				),
			),
            'search' => array(
                'type'  => 'checkbox',
                'std'   => 0,
                'label' => esc_html__( 'Show search', 'xstore-core' )
            ),
			'count'        => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Show product counts', 'xstore-core' )
			),
			'ajax'         => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 *
	 * @see WP_Widget
	 *
	 */
	public function widget( $args, $instance ) {
		if ( xstore_notice() ) {
			return;
		}

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

//		$_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
		$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
        $search             = isset( $instance['search'] ) ? $instance['search'] : $this->settings['search']['std'];
		$taxonomy           = 'product_cat';
		$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
		$display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];
		$ajax               = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

		if ( in_array( $display_type, array( 'dropdown', 'select2' ) ) ) {
			if ( $display_type == 'select2' ) {
				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_style( 'select2' );
				wc_enqueue_js( "
		            jQuery( '.etheme_widget_cats_filter .dropdown_product_cats' ).select2();
		            jQuery(document).on('et_ajax_content_loaded et_ajax_element_loaded', function() {
				        jQuery( '.etheme_widget_cats_filter .dropdown_product_cats' ).select2();
				    });
		        " );
			}

			if ( ! etheme_get_option( 'ajax_product_filter', 0 ) ) {
				wc_enqueue_js( "
                    jQuery( '.dropdown_product_cats' ).change( function() {
                        var url = jQuery(this).find( 'option:selected' ).data( 'url' );
                        if ( url != '' ) location.href = url;
                    });
                " );
			}
		}

		if ( apply_filters( 'et_ajax_widgets', $ajax ) ) {
			$extra                = ( $display_type == 'select2' ) ? 'select2' : '';
			$instance['selector'] = '.etheme_widget_cats_filter';
			echo et_ajax_element_holder( 'Categories_Filter', $instance, '', '', 'widget_filter', $args );

			return;
		}

		$hide_empty = get_option( 'woocommerce_hide_out_of_stock_items' ) === 'yes';

		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty'       => $hide_empty,
			'operator'         => 'IN',
			'include_children' => false,
		));

		if ( is_wp_error( $categories ) || 0 === count( $categories ) ) {
			return;
		}


        $widget_class    = '';
		$shop_url = '';
        $is_product_cat = false;

		if ( is_tax( $taxonomy ) ) {
            $widget_class    = 'on_cat ';
			$shop_url = 'data-shop-url="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"';
            $is_product_cat = true;
		}

		$items = '';
		$cached_counts = array();
		$write_cache = false;
		$is_cache_enabled = apply_filters( 'etheme_widget_product_categories_cache', true);

		if ( $is_cache_enabled ){
			$cached_counts = (array) get_transient( 'etheme_product_categories_filter_counts' );
		}

		// Next one write random string to $cached_counts in any case
		// $cached_counts = (array) (apply_filters( 'etheme_widget_product_categories_cache', true)) ? get_transient( 'etheme_product_categories_filter_counts' ) : array();

		if ( count( $categories ) > 0 ) {

            $term_counts = $this->get_filtered_term_product_counts( wp_list_pluck( $categories, 'term_id' ), $taxonomy, $query_type );
            $current_page_url = false;

			foreach ( $categories as $category ) {

				$class = 'cat-item';

				if ( ! array_key_exists( $category->term_id, $term_counts ) ) {
					continue;
				}

				if ( ! $cached_counts || !is_array($cached_counts) || count($cached_counts) < 2 || !isset($cached_counts[$category->term_id]) ) {
					$write_cache = true;

					if ($is_cache_enabled) {
						if ( $hide_empty ) {
							$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat' );
							$cached_counts[$category->term_id] = $stock;
						} else {
							$cached_counts[$category->term_id] = absint($category->count);
						}
					} else {
						if ( $hide_empty && $is_product_cat ) {
							$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat', $category->slug );
							$cached_counts[$category->term_id] = $stock;
						} elseif ( $hide_empty ) {
							$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat' );
							$cached_counts[$category->term_id] = $stock;
						} elseif ( $is_product_cat ) {
							$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat', $category->slug, false );
							$cached_counts[$category->term_id] = $stock;
						} else {
							$cached_counts[$category->term_id] = absint($category->count);
						}
					}

//					if ( $hide_empty && $is_product_cat ) {
//						$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat', $category->slug );
//						$cached_counts[$category->term_id] = $stock;
//					} elseif ( $hide_empty ) {
//						$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat' );
//						$cached_counts[$category->term_id] = $stock;
//					} elseif ( $is_product_cat ) {
//						$stock = parent::etheme_stock_taxonomy( $category->term_id, 'product_cat', $category->slug, false );
//						$cached_counts[$category->term_id] = $stock;
//					} else {
//						$cached_counts[$category->term_id] = absint($category->count);
//					}
				}

				// Do it if new cat added during the cache.
				$stock = isset($cached_counts[$category->term_id]) ? $cached_counts[$category->term_id] : $category->count;

				if ( ! $stock || $stock == '' ) {
					continue;
				}

				//$thumbnail_id = absint( get_term_meta( $category->term_id, 'thumbnail_id', true ) );
                $current_page_url = $current_page_url ? $current_page_url : $this->get_current_page_url();
                // fix for Elementor editor
                if ( is_wp_error($current_page_url)){
                    $current_page_url = get_permalink( wc_get_page_id( 'shop' ) );
                }
				$link = remove_query_arg( 'filter_cat', $current_page_url );

				$current_filter = isset( $_GET['filter_cat'] ) ? explode( ',', wc_clean( wp_unslash( $_GET['filter_cat'] ) ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				$all_filters = $current_filter;

				if ( ! in_array( $category->slug, $current_filter, true ) ) {
					$all_filters[] = $category->slug;
				} else {
					$key = array_search( $category->slug, $all_filters );
					unset( $all_filters[ $key ] );
					$class .= ' current-item';
				}

				if ( ! empty( $all_filters ) ) {
					$link = add_query_arg( 'filter_cat', implode( ',', $all_filters ), $link );
				}

				// Render widget items
				if ( in_array( $display_type, array( 'dropdown', 'select2' ) ) ) {
					$link = remove_query_arg( 'filter_cat', $current_page_url );
					$link = add_query_arg( 'filter_cat', $category->slug, $link );

					$selected = ( is_tax( $taxonomy, $category->term_id ) ) ? ' selected' : '';
					$items    .= '<option class="level-0" value="' . esc_html( $category->name ) . '" data-url="' . $link . '"' . $selected . '>' . esc_html( $category->name . ( $count == 1 ? ' (' . $stock . ')' : '' ) ) . '</option>';
				} else {
					$items .= '<li class="' . $class . '">';
					$items .= '<a href="' . $link . '">';
					$items .= esc_html( $category->name );
					if ( $count == 1 ) {
						$items .= apply_filters( 'etheme_cats_widget_count', '<span class="count">(' . esc_html( $stock ) . ')</span>', $stock, $category );
					}
					$items .= '</a>';
					$items .= '</li>';
				}
			}
		}

		if ($cached_counts && $write_cache && $is_cache_enabled) {
			set_transient( 'etheme_product_categories_filter_counts', $cached_counts, DAY_IN_SECONDS );
		}

		// Render widget
		$out = '';
        $out .= (isset($args['before_widget'])) ? str_replace('class="', $shop_url . ' class="'.$widget_class, $args['before_widget']) : '';
//		$out .= '<div class="sidebar-widget etheme_widget_cats_filter etheme_widget_cats ' . $class . '" ' . $shop_url . '>';
		$out .= parent::etheme_widget_title( $args, $instance );
        if ( $search && $display_type == 'list' ) {
            $out .= parent::render_widget_local_search_form(esc_html__('Find a category', 'xstore-core'));
        }
            if ( in_array( $display_type, array( 'dropdown', 'select2' ) ) ) {
                $out .= '<select name="product_cats" class="dropdown_product_cats">';
                    $out .= '<option value="" selected="selected" data-url="">' . esc_html__( 'Select a category', 'xstore-core' ) . '</option>';
                    $out .= $items;
                $out .= '</select>';
            } else {

                $out .= '<ul>';

                    $out .= $items;

                $out .= '</ul>';
            }
        $out .= (isset($args['after_widget'])) ? $args['after_widget'] : '';
//		$out .= '</div>';

		echo $out;
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
		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
            INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
            INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
            INNER JOIN {$wpdb->terms} AS terms USING( term_id )
            " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$query['where']  = "
            WHERE {$wpdb->posts}.post_type IN ( 'product' )
            AND {$wpdb->posts}.post_status = 'publish'"
		                   . $tax_query_sql['where'] . $meta_query_sql['where'] .
		                   'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';
		$search          = \WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}
		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );
		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query );
		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
//		$cache = false;
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}
		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
			}
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
}
