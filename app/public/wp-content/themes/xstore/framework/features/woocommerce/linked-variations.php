<?php
/**
 * Single Product Linked Variations
 *
 * @package    linked-variations.php
 * @since      9.4.0
 * @author     8theme
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Etheme_WooCommerce_Linked_Variations {

	/**
	 * Data.
	 *
	 * @var array
	 */
	private $linked_data = [];

	public static $option_name = 'linked_variations';

	public static $should_display = false;

	public $settings = array();

	/**
	 * Construct.
	 */
	public function init() {
		if ( !class_exists('WooCommerce')) return;
		if ( !get_theme_mod(self::$option_name, false) ) return; // because it is set as theme mod setting

		self::$should_display = true;

		$this->hooks();
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		$this->set_settings();
		add_filter('etheme_single_product_cart_class', function ($class){
			if ( did_action('etheme_sticky_add_to_cart_before') )
				$class .= ' variations_form';
			return $class;
		}, 10, 1);
		add_action('etheme_sticky_add_to_cart_before', [$this, 'modify_sticky_cart_button']);
		add_action('etheme_sticky_add_to_cart_after', [$this, 'unmodify_sticky_cart_button']);
		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'output' ], 125 );

		// Add variation for out_of_stock products
		add_action( 'etheme_single_product_out_of_stock_after_add_to_cart', function(){echo '<form class="cart">';}, 124 );
		add_action( 'etheme_single_product_out_of_stock_after_add_to_cart', [ $this, 'output' ], 125 );
		add_action( 'etheme_single_product_out_of_stock_after_add_to_cart', function(){echo '</form>';}, 126 );
		//add_action( 'woocommerce_after_add_to_cart_form', 'etheme_custom_variable_add_to_cart', 30 );
	}

	public function modify_sticky_cart_button() {
		add_action( 'woocommerce_simple_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 );
	}

	public function unmodify_sticky_cart_button() {
		remove_action( 'woocommerce_simple_add_to_cart', 'etheme_custom_variable_add_to_cart', 30 );
	}

	/**
	 * @param array $custom_settings
	 * @since 9.2.8
	 */
	public function set_settings($custom_settings = array()) {
		$settings = (array)get_option('xstore_sales_booster_settings', array());

		$default = array(
			'target_blank'          => false,
		);

		$local_settings = $default;

		if (count($settings) && isset($settings[self::$option_name])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
		}

		$this->settings = wp_parse_args( $custom_settings, $local_settings );
		$this->settings = wp_parse_args( $custom_settings, $this->settings );
	}

	public function output(){

		if ( !self::$should_display ) return;

		global $product;

		$product_id = $product->get_id();

		$this->set_linked_data( $product_id );

		if ( empty( $this->linked_data ) || ! $this->linked_data['attrs'] || 1 === count( $this->linked_data['attrs'] ) && empty( reset( $this->linked_data['attrs'] ) ) ) {
			return;
		}

//		$current_attributes     = $this->get_product_attributes( $product->get_id() );
		$linked_variations_data = $this->get_linked_variations( $product_id );

		if ( ! get_theme_mod('enable_swatch', 1) ) {
			echo '<p class="woocommerce-message woocommerce-info">' . sprintf(esc_html__( 'To use this element please activate "%s" option', 'xstore' ), '<a href="'.admin_url('/customize.php?autofocus[section]=shop-color-swatches').'">'.esc_html__('Variation swatches', 'xstore') . '</a>') . '</p>';
			return;
		}

		?>

        <table class="variations" cellspacing="0" role="presentation">
			<?php foreach ($linked_variations_data as $attr => $attr_data) : ?>
				<?php
				$attribute_taxonomy = wc_get_attribute(wc_attribute_taxonomy_id_by_name($attr));
				$attribute_type = $attribute_taxonomy->type;
				?>
                <tr>
                    <th class="label"><label for="<?php esc_html_e($attr); ?>"><?php esc_html_e($attribute_taxonomy->name);?></label></th>
                    <td class="value">
						<?php
						echo $this->swatch_html($attribute_type, $attr, $attr_data, array(), false, false);
						?>
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
		<?php
	}

	/**
	 * Print HTML of swatches
	 */
	public function swatch_html( $attribute_type, $taxonomy, $terms, $variations, $selected, $variations_by_attr ) {
		$html = '';
		$custom_class = '';
		$custom_class .= 'st-swatch-size-'.apply_filters('sten_wc_single_swatch_size', 'large');
		$subtype      = '';

		$sw_shape = apply_filters('sten_wc_single_swatch_shape', get_theme_mod('swatch_shape', 'default'));
		$sw_custom_shape = $sw_shape != 'default' ? $sw_shape : false;

		$target = ( !!$this->settings['target_blank'] ) ? 'target="_blank"' : '';

		$is_use_image = false;
		if ( $this->linked_data['use_image'] && in_array( $taxonomy, $this->linked_data['use_image'], true ) ) {
			$attribute_type = 'st-image-swatch-sq';
			$is_use_image = true;
		}

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

		$sw_design = apply_filters('sten_wc_single_swatch_design', get_theme_mod('swatch_design', 'default'));
		$sw_disabled_design = apply_filters('sten_wc_single_swatch_disabled_design', get_theme_mod('swatch_disabled_design', 'line-thought'));

		if ( $sw_design != 'default' )
			$custom_class .= ' st-swatch-'.$sw_design;

		if ( $sw_disabled_design != 'default' )
			$custom_class .= ' st-swatch-disabled-'.$sw_disabled_design;

		$custom_class .= ' et_linked-swatches';

		switch ( $et_attribute_type ) {

			case 'st-color-swatch':
				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-color-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);

					foreach( $terms as $term ) {

						$color = get_term_meta( $term['term_id'], 'st-color-swatch', true );

						$class = ( $selected == $term['attributes']['slugs'][$taxonomy] ) ? 'selected' : '';
						$class .= ( $color == '#ffffff' || $color == '#fcfcfc' || $color == '#f7f7f7' || $color == '#f4f4f4'  ) ?  ' st-swatch-white' : '';

						if (!$term['is_purchasable']){
							$class .= ' sten-li-disabled';
						}
						if ($term['is_selected']){
							$class .= ' selected';
						}

						$html .= sprintf(
							'<li class="type-color %5$s %1$s" data-tooltip="%3$s"> <a href="%6$s" '.$target.'"><span class="st-custom-attribute" data-value="%2$s" data-name="%3$s" 
                            style="%4$s"></span></a></li>',
							esc_attr( $class ),
							esc_attr( $term['attributes']['slugs'][$taxonomy] ),
							esc_attr( $term['attributes']['labels'][$taxonomy] ),
							esc_attr( $this->generate_gradient_color_css($color) ),
							esc_attr( $subtype ),
							esc_attr( $term['permalink'] )
						);
					}
					$html .= sprintf('</ul>');
				}
				break;

			case 'st-label-swatch':

				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-label-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);

					foreach( $terms as $term ) {

						$label = get_term_meta( $term['term_id'], 'st-label-swatch', true );
						$label = (!empty($label)) ? $label : $term['attributes']['labels'][$taxonomy];
						$class = ( $selected == $term['attributes']['slugs'][$taxonomy] ) ? 'selected' : '';

						if (!$term['is_purchasable']){
							$class .= ' sten-li-disabled';
						}
						if ($term['is_selected']){
							$class .= ' selected';
						}

						$html .= sprintf(
							'<li class="type-label %5$s %1$s"><a href="%6$s" '.$target.'> <span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span></a></li>',
							esc_attr( $class ),
							esc_attr( $term['attributes']['slugs'][$taxonomy] ),
							esc_attr( $term['attributes']['labels'][$taxonomy] ),
							esc_attr( $label ),
							esc_attr( $subtype ),
							esc_attr( $term['permalink'] )
						);
					}
					$html .= sprintf('</ul>');
				}
				break;

			case 'st-image-swatch':
				if( $terms ) {

					$html .= sprintf(
						'<ul class="st-swatch-preview st-swatch-preview-single-product st-image-swatch %1$s" data-attribute="%2$s">',
						esc_attr( $custom_class ),
						sanitize_title( $taxonomy )
					);

					foreach( $terms as $term ) {

						$class = ( $selected == $term['attributes']['slugs'][$taxonomy] ) ? 'selected' : '';

						if (!$term['is_purchasable']){
							$class .= ' sten-li-disabled';
						}
						if ($term['is_selected']){
							$class .= ' selected';
						}

						if ($is_use_image){
							$image   = wp_get_attachment_image( get_post_thumbnail_id( $term['id'] ), 'woocommerce_thumbnail' );
						} else {
							if (
								is_array($variations_by_attr)
								&& count($variations_by_attr)
								&& isset($variations_by_attr[$term['attributes']['slugs'][$taxonomy]])
								&& ! empty($variations_by_attr[$term['attributes']['slugs'][$taxonomy]])
							){
								$image = get_post_thumbnail_id( $variations_by_attr[$term['attributes']['slugs'][$taxonomy]] );
							} else {
								$image = get_term_meta( $term['term_id'], 'st-image-swatch', true );
							}

							if ($image){
								$image = wp_get_attachment_image( $image, apply_filters('sten_wc_single_image_swatch_size', 'thumbnail') );
							}
						}

						$html .= sprintf(
							'<li class="type-image %5$s %1$s" data-tooltip="%3$s"><a href="%6$s" '.$target.'"><span class="st-custom-attribute" data-value="%2$s" data-name="%3$s"> %4$s </span></a></li>',
							esc_attr( $class ),
							esc_attr( $term['attributes']['slugs'][$taxonomy] ),
							esc_attr( $term['attributes']['labels'][$taxonomy] ),
							$image,
							esc_attr( $subtype ),
							esc_attr( $term['permalink'] )
						);

					}
					$html .= sprintf('</ul>');
				}
				break;
		}
		return $html;
	}

	/**
	 * Set data.
	 *
	 * @param int $product_id Product id.
	 */
	private function set_linked_data( $product_id ) {
		$post = new WP_Query(
			[
				'post_type'   => 'etheme_linked_var',
				'numberposts' => 1,
				'meta_query'  => [ // phpcs:ignore
					[
						'key'     => ETHEME_PREFIX . 'linked_var_products',
						'value'   =>  $product_id,
						'compare' => 'LIKE',
					],
				],
			]
		);

		if ( ! $post->posts ) {
			return;
		}

		$this->linked_data = [
			'products'  => get_post_meta( $post->posts[0]->ID, ETHEME_PREFIX . 'linked_var_products', false ),
			'attrs'     => get_post_meta( $post->posts[0]->ID, ETHEME_PREFIX . 'linked_var_attributes', false ),
			'use_image' => get_post_meta( $post->posts[0]->ID, ETHEME_PREFIX . 'linked_var_attributes_image', false ),
		];


		foreach ($this->linked_data['use_image'] as $k => $taxonomy){
			$this->linked_data['use_image'][$k] = $this->prepare_tax_slug($taxonomy);
		}
	}

	/**
	 * Get product attributes.
	 *
	 * @param int $product_id Product id.
	 *
	 * @return array
	 */
	private function get_product_attributes( $product_id ) {
		$attributes = [];


		foreach ( $this->linked_data['attrs'] as $attribute ) {

			// Use strtolower - important, for some DB settings
			$attribute = $this->prepare_tax_slug($attribute);

			$terms = get_the_terms( $product_id, $attribute );

			if ( ! $terms || is_wp_error( $terms ) ) {
				continue;
			}

			$first_term = array_pop( $terms );

			$attributes[ $product_id ]['slugs'][ $attribute ]    = $first_term->slug;
			$attributes[ $product_id ]['labels'][ $attribute ]   = $first_term->name;
			$attributes[ $product_id ]['taxonomy'][ $attribute ] = get_taxonomy( $this->prepare_tax_slug($attribute) )->labels->singular_name;
			$attributes[ $product_id ]['meta'][ $attribute ]     = [
				'color' => get_term_meta( $first_term->term_id, 'color', true ),
				'image' => get_term_meta( $first_term->term_id, 'image', true ),
			];
		}

		return array_key_exists( $product_id, $attributes ) ? $attributes[ $product_id ] : array();
	}

	/**
	 * Get linked variations data.
	 *
	 * @param int $product_id Product id.
	 *
	 * @return array
	 */
	public function get_linked_variations( $product_id ) {
		$attributes = $this->get_product_attributes( $product_id );
		$output     = array();

		if ( empty( $attributes['slugs'] ) ) {
			return $output;
		}

		foreach ( $attributes['slugs'] as $taxonomy => $attribute ) {
			$taxonomy_ids = array();

			foreach ( $this->linked_data['products'] as $current_product_id ) {
				$current_product = wc_get_product( $current_product_id );

				if ( ! $current_product || $current_product->get_status() !== 'publish' ) {
					continue;
				}

				$current_product_attrs = $current_product->get_attributes();

				if ( is_wp_error( $current_product_attrs ) || empty( $current_product_attrs[ $taxonomy ] ) || ! $current_product_attrs[ $taxonomy ]->get_options() ) {
					continue;
				}

				$taxonomy_ids = array_merge( $taxonomy_ids, $current_product_attrs[ $taxonomy ]->get_options() );
			}

			$terms = get_terms(
				[
					'taxonomy' => $taxonomy,
					'include'  => array_unique( $taxonomy_ids ),
				]
			);

			foreach ( $terms as $term ) {
				$data = $this->get_linked_variation_data_for_attribute( $product_id, $taxonomy, $term->slug );

				if ( ! $data ) {
					continue;
				}

				$output[ $taxonomy ][ $term->slug ] = $data;
				$output[ $taxonomy ][ $term->slug ]['name'] = $term->name;
				$output[ $taxonomy ][ $term->slug ]['term_id'] = $term->term_id;
			}
		}

		return $output;
	}

	/**
	 * Get linked variation data for attribute.
	 *
	 * @param int    $product_id Product id.
	 * @param string $taxonomy Taxonomy.
	 * @param string $term_slug Term slug.
	 *
	 * @return array
	 */
	public function get_linked_variation_data_for_attribute( $product_id, $taxonomy, $term_slug ) {
		$current_attributes = $this->get_product_attributes( $product_id );
		$linked_variations  = $this->get_linked_variations_data( $product_id );

		$current_attributes['slugs'][ $taxonomy ] = $term_slug;

		$output = [];

		foreach ( $linked_variations as $linked_variation ) {
			if ( ! empty( $linked_variation['attributes'] ) && ! array_diff_assoc( $current_attributes['slugs'], $linked_variation['attributes']['slugs'] ) ) {
				$output = $linked_variation;
			}
		}

		return $output;
	}

	/**
	 * Get product attributes.
	 *
	 * @param int $product_id Product id.
	 *
	 * @return array
	 */
	private function get_linked_variations_data( $product_id ) {
		$linked_products = [];

		foreach ( $this->linked_data['products'] as $linked_variation_id ) {
			$linked_variation = wc_get_product( $linked_variation_id );

			if ( ! $linked_variation || $linked_variation->get_status() !== 'publish' ) {
				continue;
			}

			$linked_products[ $product_id ][ $linked_variation_id ] = [
				'id'             => $linked_variation_id,
				'permalink'      => $linked_variation->get_permalink(),
				'title'          => $linked_variation->get_title(),
				'stock_status'   => $linked_variation->get_stock_status(),
				'is_purchasable' => $linked_variation->is_purchasable(),
				'attributes'     => $this->get_product_attributes( $linked_variation_id ),
				'is_selected'    => ( $linked_variation_id == $product_id)
			];
		}
		return $linked_products[ $product_id ];
	}

	/**
	 * Generate color style
	 */
	public function generate_gradient_color_css($color) {
		$style = '';
		if (is_array($color)){
			$gradient_direction = apply_filters('sten_wc_single_swatch_multicolor_design', get_theme_mod('swatch_multicolor_design', 'right'));
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
	 * Prepare taxonomy slug
	 */
	public function prepare_tax_slug($taxonomy_slug){
		return str_replace(' ', '-', strtolower($taxonomy_slug));
    }
}

$gdpr = new Etheme_WooCommerce_Linked_Variations();
$gdpr->init();