<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Product meta widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Meta extends \ElementorPro\Modules\Woocommerce\Widgets\Product_Meta {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_meta';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-product-meta et-elementor-product-widget-icon-only';
	}
	
	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}

	/**
	 * Register widget controls.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function register_controls() {

        $this->start_controls_section(
            'section_product_meta_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'type',
            [
                'label'   => __('Type', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_product_metas(),
                'default' => 'sku',
            ]
        );

        $this->add_control(
            'elements',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'type'  => 'sku',
                    ],
                    [
                        'type'  => 'categories',
                    ],
                    [
                        'type'  => 'tags',
                    ],
                ],
                'title_field' => '{{{ type }}}',
            ]
        );

        $this->end_controls_section();

		parent::register_controls();

        $this->remove_control('wc_style_warning');

        $this->start_injection([
            'type' => 'control',
            'at' => 'after',
            'of' => 'link_color',
        ]);

        $this->add_control(
            'link_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_injection();
        foreach ($this->get_product_metas() as $meta_element_key => $meta_element_title) {
            if ( in_array($meta_element_key, array('sku', 'categories', 'tags'))) continue;

            $this->start_injection([
                'type' => 'control',
                'at' => 'after',
                'of' => 'sku_missing_caption',
            ]);

            $this->add_control(
                'heading_'.$meta_element_key.'_caption',
                [
                    'label' => $meta_element_title,
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if ( $meta_element_key == 'et_brand' ) {
                $this->add_control(
                    $meta_element_key . '_caption_single',
                    [
                        'label' => $meta_element_title,
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => $meta_element_title,
                        'dynamic' => [
                            'active' => true,
                        ],
                    ]
                );
                $this->add_control(
                    $meta_element_key . '_caption_plural',
                    [
                        'label' => esc_html__('Brands', 'xstore-core'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => esc_html__('Brands', 'xstore-core'),
                        'dynamic' => [
                            'active' => true,
                        ],
                    ]
                );
            }
            else {
                $this->add_control(
                    $meta_element_key . '_caption',
                    [
                        'label' => $meta_element_title,
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => $meta_element_title,
                        'dynamic' => [
                            'active' => true,
                        ],
                    ]
                );

                $this->add_control(
                    $meta_element_key . '_missing_caption',
                    [
                        'label' => esc_html__('Missing', 'xstore-core'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => esc_html__('N/A', 'xstore-core'),
                        'dynamic' => [
                            'active' => true,
                        ],
                    ]
                );
            }

            $this->end_injection();
        }
	}

    // full duplicate because it is private function and need to be called directly
    private function get_plural_or_single( $single, $plural, $count ) {
        return 1 === $count ? $single : $plural;
    }

    protected function render() {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( !count($settings['elements'])) return;

        $elements_list = $this->get_product_metas();

        ?>
        <div class="product_meta">

            <?php do_action( 'woocommerce_product_meta_start' ); ?>

            <?php
                foreach ($settings['elements'] as $meta_element) {

                    $meta_element_key = $meta_element['type'];

	                if (!isset($elements_list[$meta_element_key])) return;


	                $meta_element_title = $elements_list[$meta_element_key];
                    switch ($meta_element_key) {
                        case 'sku':
                            $sku_caption = ! empty( $settings['sku_caption'] ) ? esc_html( $settings['sku_caption'] ) : esc_html__( 'SKU:', 'xstore-core' );
                            $sku_missing = ! empty( $settings['sku_missing_caption'] ) ? esc_html( $settings['sku_missing_caption'] ) : esc_html__( 'N/A', 'xstore-core' );
                            $sku = esc_html( $product->get_sku() );
                            if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
                                <span class="sku_wrapper detail-container">
                                    <span class="detail-label"><?php // PHPCS - the $sku_caption variable is safe. ?><?php echo $sku_caption; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </span>
                                    <span class="sku"><?php // PHPCS - the $sku && $sku_missing variables are safe. ?><?php echo $sku ? $sku : $sku_missing; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </span>
                                </span>
                            <?php endif;
                            break;
                        case 'categories':
                            if ( count( $product->get_category_ids() ) ) :
                                $category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : esc_html__( 'Category:', 'xstore-core' );
                                $category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : esc_html__( 'Categories:', 'xstore-core' );
                                $terms_list = '';
                                if ( function_exists('etheme_get_custom_field')) {
                                    $primary_cat = etheme_get_custom_field('primary_category', $product->get_id());
                                    if (!empty($primary_cat) && $primary_cat != 'auto') {
                                        $primary_cat = get_term_by( 'slug', $primary_cat, 'product_cat' );
                                        if ( ! is_wp_error( $primary_cat ) ) {
                                            $primary_cat_link = get_term_link( $primary_cat );
                                            if ( ! is_wp_error( $primary_cat_link ) ) {
                                                $terms_list = '<a href="' . esc_url( $primary_cat_link ) . '" rel="tag">' . $primary_cat->name . '</a></span>';
                                                $categories_count = 1;
                                            }
                                        }
                                    }
                                }
                                if ( empty($terms_list) ) {
                                    $categories_count = $product->get_category_ids();
                                    $terms_list = get_the_term_list($product->get_id(), 'product_cat', '', ', ');
                                }
                            ?>
                                <span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, $categories_count ) ); ?></span> <span class="detail-content"><?php echo $terms_list; ?></span></span>
                            <?php endif;
                            break;
                        case 'tags':
                            if ( count( $product->get_tag_ids() ) ) :
                                $tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : esc_html__( 'Tag:', 'xstore-core' );
                                $tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : esc_html__( 'Tags:', 'xstore-core' ); ?>
                                <span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
                            <?php endif;
                            break;
                        case 'et_gtin':
                            $product_id = $product->get_id();
                            $origin_id = $product_id;
                            $product_type = $product->get_type();

                            $gtin_caption = ! empty( $settings['et_gtin_caption'] ) ? esc_html( $settings['et_gtin_caption'] ) : esc_html__( 'GTIN:', 'xstore-core' );
                            $gtin_missing = ! empty( $settings['et_gtin_missing_caption'] ) ? esc_html( $settings['et_gtin_missing_caption'] ) : esc_html__( 'N/A', 'xstore-core' );

                            if ( $product_type == 'variation' )
                                $product_id = $product->get_parent_id();

                            $gtin = get_post_meta( $origin_id, '_et_gtin', true );
                            $gtin_ghost = false;

                            if ( !$gtin && $product_type == 'variable' ) {
                                $children_have_gtin = array_filter( $product->get_children(), function ($localProdId) {
                                    return !empty(get_post_meta( $localProdId, '_et_gtin', true ));
                                } );
                                if ( $children_have_gtin )
                                    $gtin_ghost = true;
                            }
                            // in case it is product variation gtin field
                            if ( !$gtin && $origin_id != $product_id ) {
                                $gtin = get_post_meta($product_id, '_et_gtin', true);
                                $gtin_ghost = false;
                            }

                            if ( $gtin || $gtin_ghost ) : ?>
                                <span class="gtin_wrapper detail-container">
                                    <span class="detail-label"><?php // PHPCS - the $sku_caption variable is safe. ?><?php echo $gtin_caption; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </span>
                                    <span class="gtin"><?php // PHPCS - the $sku && $sku_missing variables are safe. ?><?php echo $gtin ? $gtin : $gtin_missing; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </span>
                                </span>
                                <?php endif;
                            break;
                        case 'et_brand':
                            $brands = (array) wp_get_post_terms( $product->get_id(), 'brand' );
                            if ( count( $brands ) ) :
                                $brand_caption_single = ! empty( $settings['et_brand_caption_single'] ) ? $settings['et_brand_caption_single'] : esc_html__( 'Brand:', 'xstore-core' );
                                $brand_caption_plural = ! empty( $settings['et_brand_caption_plural'] ) ? $settings['et_brand_caption_plural'] : esc_html__( 'Brands:', 'xstore-core' ); ?>
                                <span class="brands_wrapper detail-container">
                                    <span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $brand_caption_single, $brand_caption_plural, count( $brands ) ) ); ?></span>
                                    <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'brand', '', ', ' ); ?></span>
                                </span>
                            <?php endif;
                            break;
                        default:
                            do_action('etheme_product_meta_element_render', $meta_element_key, $product, $edit_mode, $this);
                        break;
                    }
                }
            ?>

            <?php do_action( 'woocommerce_product_meta_end' ); ?>

        </div>
    <?php }

    public function get_product_metas() {
        return apply_filters('etheme_product_meta_elements', array(
            'sku' => esc_html__('SKU', 'xstore-core'),
            'categories' => esc_html__('Categories', 'xstore-core'),
            'tags' => esc_html__('Tags', 'xstore-core'),
        ));
    }
}
