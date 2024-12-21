<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Reviews widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Reviews extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_reviews';
	}

	/**
	 * Get widget title.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Reviews', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-comments et-elementor-product-widget-icon-only';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'comment', 'reply', 'form', 'star', 'rating', 'product' ];
	}

    /**
     * Get widget categories.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['woocommerce-elements-single'];
    }

    /**
     * Get widget dependency.
     *
     * @since 4.3
     * @access public
     *
     * @return array Widget dependency.
     */

    public function get_script_depends() {
        $scripts = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            if ( get_option('xstore_sales_booster_settings_customer_reviews_advanced') && class_exists('\Etheme_WooCommerce_Product_Reviews_Advanced')) {
                $reviews_advanced = new \Etheme_WooCommerce_Product_Reviews_Advanced();
                if ( !!$reviews_advanced->settings['likes'] )
                    $scripts[] = 'et_reviews_likes';
                if ( !!$reviews_advanced->settings['rating_criteria'] && count($reviews_advanced->settings['criteria_ready']) )
                    $scripts[] = 'et_reviews_criteria';
            }
        }
        return $scripts;
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_style_depends() {
        $styles = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            if ( get_option('xstore_sales_booster_settings_customer_reviews_advanced') ) {
                $styles[] = 'etheme-sale-booster-reviews-advanced';
            }
        }
        return $styles;
    }
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('110-sales-booster', false);
	}

	/**
	 * Register widget controls.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function register_controls() {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'raw' => sprintf(esc_html__('We recommend you to configure %s Sales Booster settings to increase conversion rates on your eCommerce store.', 'xstore-core'),
                    '<a href="' . add_query_arg('etheme-sales-booster-tab', 'customer_reviews', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Customer reviews', 'xstore-core') . '</a>'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'reviews_layout',
            [
                'label'     => esc_html__( 'Layout', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default' => 'two',
                'options' => [
                    'two' => esc_html__('Two columns', 'xstore-core'),
                    'one' => esc_html__('One column', 'xstore-core'),
                ],
                'prefix_class' => 'etheme-product-review-columns-',
            ]
        );

        $this->add_responsive_control(
            'spacing',
            [
                'label' => __( 'Space between', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comments-columns-space: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke',
                'selector' => '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title',
            ]
        );

        $this->add_control(
            'title_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'xstore-core' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'xstore-core'),
                'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_space',
            [
                'label' => __( 'Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #reviews #comments>h2, {{WRAPPER}}  span.comment-reply-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
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

        // Reviews tab - shows comments.
        if ( comments_open() ) {
            comments_template();
        }
        else {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('Comments are closed for this product', 'xstore-core'));
            }
        }

        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <style>
                [data-id="<?php echo $this->get_id(); ?>"] .comment-respond .comment-form-rating .stars + .stars {
                    display: none;
                }
            </style>
            <script>
                jQuery(document).ready(function($) {
                    // Init Tabs and Star Ratings
                    ['[data-id="<?php echo $this->get_id(); ?>"] .wc-tabs-wrapper', '[data-id="<?php echo $this->get_id(); ?>"] .woocommerce-tabs', '[data-id="<?php echo $this->get_id(); ?>"] #rating'].forEach(function (el) {
                        if ( !$(el).hasClass('et-force-reinited') ) {
                            jQuery(el).trigger('init').addClass('et-force-reinited');
                        }
                    });
                    <?php if ( get_option('xstore_sales_booster_settings_customer_reviews_advanced') && class_exists('\Etheme_WooCommerce_Product_Reviews_Advanced')) :
                        $reviews_advanced = new \Etheme_WooCommerce_Product_Reviews_Advanced(); ?>
                        if ( etTheme.reviewsCriteria !== undefined ) {
                            etConfig['sales_booster_reviews_advanced'] = [];
                            etConfig['sales_booster_reviews_advanced']['criteria_list'] = '<?php echo wp_json_encode($reviews_advanced->settings['criteria_ready']); ?>';
                            etConfig['sales_booster_reviews_advanced']['criteria_required'] = <?php echo !!$reviews_advanced->settings['rating_criteria_required'] ? 'yes' : '""'; ?>;
                            etTheme.reviewsCriteria();
                        }
                    <?php endif; ?>
                });
            </script>
            <?php
        }

	}
}
