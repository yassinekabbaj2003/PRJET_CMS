<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Sticky Cart widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Sticky_Cart extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_sticky_cart';
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
		return __( 'Sticky Cart', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-sticky-cart et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'price', 'swatches', 'cart', 'product', 'fixed', 'sticky', 'cart' ];
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
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [
            'etheme-single-product-elements',
            'etheme-quantity-types-style',
            'etheme-single-product-sticky-cart'
        ];
	}

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        return [ 'et_single_product_sticky_cart' ];
    }
	
	/**
	 * Help link.
	 *
	 * @since 5.2
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

        $activated_swatch_option = get_theme_mod('enable_swatch', 1);

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Content Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'boxed',
                'options' => [
                    'boxed' => esc_html__( 'Boxed', 'xstore-core' ),
                    'full_width' => esc_html__( 'Full Width', 'xstore-core' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 1600,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-sticky-cart > .et-container' => 'max-width: {{SIZE}}{{UNIT}}; padding-left: 0; padding-right: 0;',
                ],
                'condition' => [
                    'layout' => [ 'boxed' ],
                ],
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'always_shown',
            [
                'label' 		=> esc_html__( 'Always Shown', 'xstore-core' ),
                'description' => esc_html__('Note: this option will work on real frontend only, because we keep Sticky cart always shown while you are in edit mode.', 'xstore-core'),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Elements', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' 		=> esc_html__( 'Show Image', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'woocommerce_thumbnail',
                'exclude' => ['custom'],
                'separator' => 'none',
                'condition' => [
                    'show_image!' => ''
                ]
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' 		=> esc_html__( 'Show Title', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' 		=> esc_html__( 'Show Price', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        if ( get_theme_mod('xstore_wishlist', false) ) {
            $this->add_control(
                'show_wishlist',
                [
                    'label' 		=> esc_html__( 'Show Wishlist', 'xstore-core' ),
                    'type'			=> \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
            );
        }

        if ( get_theme_mod('xstore_compare', false) ) {
            $this->add_control(
                'show_compare',
                [
                    'label' 		=> esc_html__( 'Show Compare', 'xstore-core' ),
                    'type'			=> \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
            );
        }

        $this->add_control(
            'show_button',
            [
                'label' 		=> esc_html__( 'Show Button', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'show_buy_now_button',
            [
                'label' 		=> esc_html__( 'Show Buy Now Button', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_popup',
            [
                'label' => esc_html__( 'Variations Popup', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'quantity_style',
            [
                'label' => __( 'Quantity Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'square',
                'options' => [
                    'simple' => esc_html__('Simple', 'xstore-core'),
                    'circle' => esc_html__('Circle', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                    'select' => esc_html__('Select', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
                ],
            ]
        );

        $this->end_controls_section();

        if ( $activated_swatch_option ) {
            $this->start_controls_section(
                'section_swatches',
                [
                    'label' => esc_html__( 'Swatches', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'swatch_size',
                [
                    'label' => __( 'Size', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'normal' => esc_html__( 'Normal', 'xstore-core' ),
                        'large'  => esc_html__( 'Large', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_design',
                [
                    'label' => __( 'Design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default'   => esc_html__( 'Default', 'xstore-core' ),
                        'underline' => esc_html__( 'Underline', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_disabled_design',
                [
                    'label' => __( '"Out of Stock" design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default'      => esc_html__( 'Default', 'xstore-core' ),
                        'line-thought' => esc_html__( 'Line-thought', 'xstore-core' ),
                        'cross-line'   => esc_html__( 'Cross line', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_multicolor_design',
                [
                    'label' => __( 'Multicolor design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'right' => esc_html__( 'Vertical', 'xstore-core' ),
                        'bottom'   => esc_html__( 'Horizontal', 'xstore-core' ),
                        'diagonal_1'   => esc_html__( 'Diagonal 1', 'xstore-core' ),
//                        'diagonal_2'   => esc_html__( 'Diagonal 2', 'xstore-core' ),
                    ],
                ]
            );
            $this->add_control(
                'swatch_shape',
                [
                    'label' => __( '"Out of Stock" design', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inherit',
                    'options' => [
                        'inherit'   => esc_html__( 'Inherit', 'xstore-core' ),
                        'default' => esc_html__( 'Default', 'xstore-core' ),
                        'square'  => esc_html__( 'Square', 'xstore-core' ),
                        'circle'  => esc_html__( 'Circle', 'xstore-core' ),
                    ],
                ]
            );

            $this->end_controls_section();

        }

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'min_height',
            [
                'label' => __( 'Min Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'vw' ],
                'range' => [
                    'px' 		=> [
                        'min' 	=> 50,
                        'max' 	=> 300,
                        'step' 	=> 1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Padding', 'xstore-core'),
                'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title!' => ''
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '.woocommerce {{WRAPPER}} .sticky_product_title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .sticky_product_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // image
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => __( 'Image', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'product_image!' => ''
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart img',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            [
                'label' => __( 'Price', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_price!' => ''
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart .price' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart .price',
            ]
        );

        $this->add_control(
            'sale_heading',
            [
                'label' => esc_html__( 'Sale Price', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'sale_price_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .etheme-sticky-cart .price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sale_price_typography',
                'selector' => '.woocommerce {{WRAPPER}} .etheme-sticky-cart .price ins',
            ]
        );

        $this->add_responsive_control(
            'sale_price_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .etheme-sticky-cart del' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .etheme-sticky-cart del' => 'margin-left: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_variations',
            [
                'label' => __( 'Variations Popup', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'variations_popup_border',
                'selector' => '{{WRAPPER}} form.grouped_form, {{WRAPPER}} form.variations_form',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_popup_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} form.grouped_form, {{WRAPPER}} form.variations_form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'variations_popup_box_shadow',
                'selector' => '{{WRAPPER}} form.grouped_form, {{WRAPPER}} form.variations_form',
            ]
        );

        $this->add_responsive_control(
            'variations_popup_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} form.grouped_form, {{WRAPPER}} form.variations_form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_variations_label_style',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variation_label_typography',
                'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations label',
            ]
        );

        $this->add_control(
            'variation_label_color',
            [
                'label' => __( 'Label Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label'     => esc_html__('Spacing', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_variations_select_style',
            [
                'label' => esc_html__( 'Select field', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_select_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variations_select_typography',
                'selector' => '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce div.product.elementor{{WRAPPER}} form.cart table.variations td.value:before',
                'fields_options' => [
                    'font_size' => [
                        'selector_value' => 'font-size: {{SIZE}}{{UNIT}}; height: auto',
                    ]
                ]
            ]
        );

        $this->add_control(
            'variations_select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} form.cart table.variations td.value select, .woocommerce {{WRAPPER}} form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_quantity',
            [
                'label' => __( 'Quantity', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quantity_full_width',
            [
                'label'         =>  esc_html__( 'Full width', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SWITCHER,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity' => 'width: 100%;margin: 0 0 15px;',
                    '.woocommerce {{WRAPPER}} .quantity select' => 'margin: 0 auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_size',
            [
                'label' => __( 'Font Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity-wrapper' => 'font-size: {{SIZE}}{{UNIT}}; max-width: unset;',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'font-size: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper select' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity' => 'height: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'width: {{SIZE}}{{UNIT}};',
                    '.woocommerce {{WRAPPER}} .quantity-wrapper select' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'quantity_input_width',
            [
                'label' => __( 'Input Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    'em' => [
                        'min'  => 0.1,
                        'max'  => 10,
                        'step' => 0.1
                    ],
                ],
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .quantity-wrapper input' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();

        if ( $activated_swatch_option ) {

            $this->start_controls_section(
                'section_style_swatch',
                [
                    'label' => __( 'Swatches', 'xstore-core' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->start_controls_tabs('swatch_border_style_tabs');

            $this->start_controls_tab( 'swatch_border_style_normal',
                [
                    'label' => esc_html__('Normal', 'xstore-core')
                ]
            );

            $this->add_control(
                'swatch_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart table.variations ul.st-swatch-preview li' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab( 'swatch_border_style_active',
                [
                    'label' => esc_html__('Hover/Active', 'xstore-core')
                ]
            );

            $this->add_control(
                'swatch_active_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} form.cart table.variations .st-swatch-preview li.selected' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_control(
                'heading_reset_variations_button_style',
                [
                    'label' => esc_html__( 'Clear variations button', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'reset_variations_button_typography',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'reset_variations_button_text_shadow',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->start_controls_tabs( 'tabs_reset_variations_button_style' );

            $this->start_controls_tab(
                'tab_reset_variations_button_normal',
                [
                    'label' => __( 'Normal', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'reset_variations_button_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_background_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_reset_variations_button_hover',
                [
                    'label' => __( 'Hover', 'xstore-core' ),
                ]
            );

            $this->add_control(
                'reset_variations_button_hover_color',
                [
                    'label' => __( 'Text Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                        '{{WRAPPER}} .reset_variations:hover svg, {{WRAPPER}} .reset_variations:focus svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_background_hover_color',
                [
                    'label' => __( 'Background Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'reset_variations_button_hover_border_color',
                [
                    'label' => __( 'Border Color', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'reset_variations_button_border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations:hover, {{WRAPPER}} .reset_variations:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'reset_variations_button_border',
                    'selector' => '{{WRAPPER}} .reset_variations',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'reset_variations_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'reset_variations_button_box_shadow',
                    'selector' => '{{WRAPPER}} .reset_variations',
                ]
            );

            $this->add_responsive_control(
                'reset_variations_button_padding',
                [
                    'label' => __( 'Padding', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'reset_variations_button_spacing',
                [
                    'label'     => esc_html__('Spacing', 'xstore-core'),
                    'type'      => \Elementor\Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .reset_variations' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

        }

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .single_add_to_cart_button:hover svg, {{WRAPPER}} .single_add_to_cart_button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_buy_now_button_style',
            [
                'label' => __( 'Buy now button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_buy_now_button!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'buy_now_button_typography',
                'selector' => '{{WRAPPER}} .et-single-buy-now',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'buy_now_button_text_shadow',
                'selector' => '{{WRAPPER}} .et-single-buy-now',
            ]
        );

        $this->start_controls_tabs( 'tabs_buy_now_button_style' );

        $this->start_controls_tab(
            'tab_buy_now_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'buy_now_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--single-buy-now-button-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buy_now_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--single-buy-now-button-background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_buy_now_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'buy_now_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .et-single-buy-now:hover svg, {{WRAPPER}} .et-single-buy-now:focus svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}}' => '--single-buy-now-button-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buy_now_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--single-buy-now-button-background-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buy_now_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'buy_now_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now:hover, {{WRAPPER}} .et-single-buy-now:focus' => 'border-color: {{VALUE}}; --single-buy-now-button-border-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'buy_now_button_border',
                'selector' => '{{WRAPPER}} .et-single-buy-now, {{WRAPPER}} .et-single-buy-now.button',
                'separator' => 'before',
                'fields_options' => [
                    'border' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-style: {{VALUE}}; --single-buy-now-button-border-style: {{VALUE}};',
                        ],
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-color: {{VALUE}}; --single-buy-now-button-border-color: {{VALUE}};',
                        ],
                    ]
                ]
            ]
        );

        $this->add_control(
            'buy_now_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'buy_now_button_box_shadow',
                'selector' => '{{WRAPPER}} .et-single-buy-now',
            ]
        );

        $this->add_responsive_control(
            'buy_now_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .et-single-buy-now' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
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

        if ( !defined('ETHEME_THEME_VERSION') ) {
            echo Elementor::elementor_frontend_alert_message(esc_html__('To use this widget, please, activate XStore Theme', 'xstore-core'));
            return;
        }

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( $edit_mode ) {
            echo Elementor::elementor_frontend_alert_message(
                sprintf(esc_html__('Placeholder for %s widget to quick find and edit from clicking here. Shown only in Elementor Editor.', 'xstore-core'),
                    '<strong>'.esc_html__('Sticky Cart', 'xstore-core').'</strong>')
            );

            // wishlist force init in Sticky Cart
            $wishlist = new \XStoreCore\Modules\WooCommerce\XStore_Wishlist();
            $wishlist->define_settings();

            add_action('woocommerce_before_add_to_cart_form', array($wishlist, 'print_button_single'), 5);
            // before etheme_sticky_add_to_cart()
            add_action( 'etheme_sticky_add_to_cart_before', function () use ($wishlist) {
                add_filter('xstore_wishlist_single_product_settings', array($wishlist, 'wishlist_btn_only_icon'), 999, 2);
            }, 1 );
            add_action( 'etheme_sticky_add_to_cart_after', function () use ($wishlist) {
                remove_filter('xstore_wishlist_single_product_settings', array($wishlist, 'wishlist_btn_only_icon'), 999, 2);
            }, 10 );

            // compare force init in Sticky Cart
            $compare = new \XStoreCore\Modules\WooCommerce\XStore_Compare();
            $compare->define_settings();

            add_action('woocommerce_before_add_to_cart_form', array($compare, 'print_button_single'), 5);
            // before etheme_sticky_add_to_cart()
            add_action( 'etheme_sticky_add_to_cart_before', function () use ($compare) {
                add_filter('xstore_compare_single_product_settings', array($compare, 'compare_btn_only_icon'), 999, 2);
            }, 1 );
            add_action( 'etheme_sticky_add_to_cart_after', function () use ($compare) {
                remove_filter('xstore_compare_single_product_settings', array($compare, 'compare_btn_only_icon'), 999, 2);
            }, 10 );
        }

        $quantity_type_o = get_theme_mod('shop_quantity_type', 'input');
        $quantity_type = $settings['quantity_style'];

        add_filter('etheme_elementor_theme_builder', '__return_true');
        add_filter('etheme_sticky_cart_enabled', '__return_true');
        add_filter('etheme_woocommerce_template_single_add_to_cart_hooks', '__return_false');

        $swatch_options_2_filter = array(
            'swatch_size',
            'swatch_design',
            'swatch_disabled_design',
            'swatch_multicolor_design',
            'swatch_shape'
        );
        foreach ($swatch_options_2_filter as $swatch_option_2_filter) {
            add_filter('sten_wc_single_'.$swatch_option_2_filter, array($this, 'filter_'.$swatch_option_2_filter));
        }

        remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
        add_action( 'woocommerce_before_quantity_input_field', array($this, 'before_add_to_cart_quantity'), 10 );
        add_action( 'woocommerce_after_quantity_input_field', array($this, 'after_add_to_cart_quantity'), 10 );
        add_filter( 'woocommerce_cart_item_quantity', array($this, 'cart_item_quantity'), 3, 20 );
        if ( in_array('select', array($quantity_type, $quantity_type_o) ) ) {
            $select_type_ranges_o = get_theme_mod('shop_quantity_select_ranges', '1-5');
            $select_type_ranges = get_theme_mod('product_quantity_select_ranges', $select_type_ranges_o);
            add_filter('theme_mod_shop_quantity_type', function ($value) use ($quantity_type) {
                return $quantity_type == 'select' ? 'select' : 'input';
            });
            add_filter('theme_mod_shop_quantity_select_ranges', function ($value) use ($select_type_ranges) {
                return $select_type_ranges;
            });
        }

        add_filter('etheme_buy_now_or_separator', '__return_false');
        add_filter('etheme_buy_now_button_classes', array($this, 'add_buy_now_button_classes'));

        $show_buy_now_desktop = isset($settings['show_buy_now_button']) && $settings['show_buy_now_button'];
        $show_buy_now_tablet = isset($settings['show_buy_now_button_tablet']) && $settings['show_buy_now_button_tablet'];
        $show_buy_now_mobile = isset($settings['show_buy_now_button_mobile']) && $settings['show_buy_now_button_mobile'];
        $show_buy_now = $show_buy_now_desktop || $show_buy_now_tablet || $show_buy_now_mobile;
        etheme_sticky_add_to_cart(array(
            'show_image' => $settings['show_image'],
            'image_size' => $settings['image_size'],
            'show_title' => $settings['show_title'],
            'show_price' => $settings['show_price'],
            'show_wishlist' => get_theme_mod('xstore_wishlist', false) && $settings['show_wishlist'],
            'show_compare' => get_theme_mod('xstore_compare', false) && $settings['show_compare'],
            'show_button' => $settings['show_button'] || $show_buy_now,
            'show_buy_now_button' => $show_buy_now,
            'always_shown' => $settings['always_shown'],
            'boxed' => $settings['layout'] == 'boxed'
        ));
        remove_filter('etheme_buy_now_button_classes', array($this, 'add_buy_now_button_classes'));
        remove_filter('etheme_buy_now_or_separator', '__return_false');

        remove_filter( 'woocommerce_cart_item_quantity', array($this, 'cart_item_quantity'), 3, 20 );
        remove_action( 'woocommerce_before_quantity_input_field', array($this, 'before_add_to_cart_quantity'), 10 );
        remove_action( 'woocommerce_after_quantity_input_field', array($this, 'after_add_to_cart_quantity'), 10 );

        add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );

        foreach ($swatch_options_2_filter as $swatch_option_2_filter) {
            remove_filter('sten_wc_single_'.$swatch_option_2_filter, array($this, 'filter_'.$swatch_option_2_filter));
        }

        remove_filter('etheme_woocommerce_template_single_add_to_cart_hooks', '__return_false');
        remove_filter('etheme_sticky_cart_enabled', '__return_true');
        remove_filter('etheme_elementor_theme_builder', '__return_true');

        if (!$settings['show_button'] ?? $show_buy_now) { ?>
            <style>
                [data-id="<?php echo $this->get_id(); ?>"] .single_add_to_cart_button:not(.et-single-buy-now, .etheme_custom_add_to_cart_toggle) {
                    display: none;
                }
            </style>
        <?php }
        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) { ?>
            <style>
                [data-id="<?php echo $this->get_id(); ?>"] .etheme-sticky-cart.outside {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0);
                }
            </style>
            <script>
                jQuery(document).ready(function($) {
                    if ( etTheme.et_woocommerce !== undefined ) {
                        etTheme.et_woocommerce['is_single_product'] = true;
                    }
                    $('[data-id="<?php echo $this->get_id(); ?>"] .etheme-sticky-cart').find('.variations_form, .grouped_form').addClass('hidden');
                    etTheme.sticky_cart();
                    jQuery(document).ready(function () {
                        if ( etTheme.reinitSwatches !== undefined )
                            etTheme.reinitSwatches();
                    });
                });
            </script>
            <?php
        }

	}

    public function add_buy_now_button_classes($classes) {
        $settings = $this->get_settings_for_display();
        if ( !isset($settings['show_buy_now_button']) || !$settings['show_buy_now_button'] )
            $classes[] = 'elementor-hidden-desktop';
        if ( !isset($settings['show_buy_now_button_tablet']) || !$settings['show_buy_now_button_tablet'])
            $classes[] = 'elementor-hidden-tablet';
        if ( !isset($settings['show_buy_now_button_mobile']) || !$settings['show_buy_now_button_mobile'] )
            $classes[] = 'elementor-hidden-mobile';
        return $classes;
    }


    function cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
        ob_start();
        $this->before_add_to_cart_quantity();
        echo $product_quantity;
        $this->after_add_to_cart_quantity();

        return ob_get_clean();
    }

    public function before_add_to_cart_quantity() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="quantity-wrapper type-<?php echo $settings['quantity_style']; ?>" data-label="<?php echo esc_html__( 'Quantity:', 'xstore-core' ); ?>">
            <?php if ( !in_array($settings['quantity_style'], array('none', 'select')) ) : ?>
                <span class="minus et-icon et_b-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                        <path d="M23.52 11.4h-23.040c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h23.040c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                    </svg>
                </span>
            <?php endif;
    }

    public function after_add_to_cart_quantity() {
        $settings = $this->get_settings_for_display();
        if ( !in_array($settings['quantity_style'], array('none', 'select')) ) : ?>
            <span class="plus et-icon et_b-icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24">
                    <path d="M23.52 11.4h-10.92v-10.92c0-0.264-0.216-0.48-0.48-0.48h-0.24c-0.264 0-0.48 0.216-0.48 0.48v10.92h-10.92c-0.264 0-0.48 0.216-0.48 0.48v0.24c0 0.264 0.216 0.48 0.48 0.48h10.92v10.92c0 0.264 0.216 0.48 0.48 0.48h0.24c0.264 0 0.48-0.216 0.48-0.48v-10.92h10.92c0.264 0 0.48-0.216 0.48-0.48v-0.24c0-0.264-0.216-0.48-0.48-0.48z"></path>
                    </svg>
                </span>
            <?php endif; ?>
        </div>
        <?php
    }

    public function filter_swatch_size($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_size'] != 'inherit' ? $settings['swatch_size'] : $value;
    }
    public function filter_swatch_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_design'] != 'inherit' ? $settings['swatch_design'] : $value;
    }
    public function filter_swatch_disabled_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_disabled_design'] != 'inherit' ? $settings['swatch_disabled_design'] : $value;
    }
    public function filter_swatch_multicolor_design($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_multicolor_design'] != 'inherit' ? $settings['swatch_multicolor_design'] : $value;
    }
    public function filter_swatch_shape($value) {
        $settings = $this->get_settings_for_display();
        return $settings['swatch_shape'] != 'inherit' ? $settings['swatch_shape'] : $value;
    }

}
