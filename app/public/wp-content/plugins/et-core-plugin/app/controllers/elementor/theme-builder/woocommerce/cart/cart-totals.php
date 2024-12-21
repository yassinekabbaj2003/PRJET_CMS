<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Cart;

use ETC\App\Classes\Elementor;

/**
 * Cart Totals widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Totals extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-cart-etheme_totals';
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
		return __( 'Cart Totals', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-checkout-page et-elementor-cart-builder-new-widget-icon';
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
        return [ 'woocommerce', 'cart' ];
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
    	return ['woocommerce-elements'];
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
            $styles = [ 'etheme-cart-page', 'etheme-no-products-found', 'etheme-checkout-page' ];
        }
		return $styles;
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
        return ['etheme_elementor_checkout_page'];
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

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'hidden_heading_css',
            [
                'label' => __( 'Hide title CSS', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'display: none',
                'condition' => [
                    'show_heading!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals .cart-totals-title' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_type',
            [
                'label' => esc_html__( 'Design Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => array(
                    'classic' => __( 'Classic', 'xstore-core' ),
                    'line-aside' => __( 'Line aside', 'xstore-core' ),
                    'square-aside' => __( 'Square aside', 'xstore-core' ),
                    'circle-aside' => __( 'Circle aside', 'xstore-core' ),
                    'underline' => __( 'With Underline', 'xstore-core' ),
                    'colored-underline' => __( 'With Colored Underline', 'xstore-core' ),
                ),
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'show_return_shop_button',
            [
                'label' => esc_html__( 'Continue shopping button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional_cart_totals',
            [
                'label' => esc_html__( 'Additional Block', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_cart_totals_template_switch',
            [
                'label' => esc_html__( 'Add Custom Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'additional_cart_totals_position',
            [
                'label'         =>  __( 'Position', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'before' => esc_html__('Before', 'xstore-core'),
                    'after' => esc_html__('After', 'xstore-core'),
                ],
                'default'   => 'after',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_cart_totals_template_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Choose template', 'xstore-core' ),
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_cart_totals_content_type',
            [
                'label'         =>  __( 'Content Type', 'xstore-core' ),
                'type'          =>  \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content_list(array('global_widget' => false)),
                'default'   => 'custom',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_cart_totals_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                    'additional_cart_totals_content_type' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'additional_cart_totals_static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                    'additional_cart_totals_content_type' => 'static_block'
                ]
            ]
        );

        $this->add_control(
            'additional_cart_totals_template_content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'condition'   => [
                    'additional_cart_totals_template_switch!' => '',
                    'additional_cart_totals_content_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_cart_totals_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_saved_content(),
                'default' => 'select',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                    'additional_cart_totals_content_type' => 'saved_template'
                ],
            ]
        );

        $this->add_control(
            'additional_cart_totals_static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => Elementor::get_static_blocks(),
                'default' => 'select',
                'condition' => [
                    'additional_cart_totals_template_switch!' => '',
                    'additional_cart_totals_content_type' => 'static_block'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_control(
            'heading_html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'heading_align',
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
                    '{{WRAPPER}} .cart_totals .cart-totals-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_border_width',
            [
                'label' => esc_html__( 'Border Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 5,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_inner_spacing',
            [
                'label' => esc_html__( 'Inner Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-space-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_element_heading',
            [
                'label' => __( 'Design element', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

//        $this->add_responsive_control(
//            'heading_element_width',
//            [
//                'label' => esc_html__( 'Element Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', 'rem' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 1,
//                        'max'  => 20,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}}' => '--widget-title-element-width: {{SIZE}}{{UNIT}}',
//                ],
//                'condition' => [
//                    'heading_type' => ['line-aside']
//                ]
//            ]
//        );

        $this->add_control(
            'heading_element_color',
            [
                'label'     => __( 'Color Active', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_table_style',
            [
                'label' => esc_html__( 'Table', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_table_colors' );

        $this->start_controls_tab(
            'tab_table_color_global',
            [
                'label' => __( 'Global', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'cart_total_table_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_total_table_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table .amount' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_table_color_total',
            [
                'label' => __( 'Total', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'table_total_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .order-total th' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_total_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .order-total .amount' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
                'selector'    => '{{WRAPPER}} .cart_totals',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .cart_totals',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .cart_totals',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_space',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
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
                    '{{WRAPPER}} table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkout_button_style',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'checkout_button_typography',
                'selector' => '{{WRAPPER}} .checkout-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'checkout_button_text_shadow',
                'selector' => '{{WRAPPER}} .checkout-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_checkout_button_style' );

        $this->start_controls_tab(
            'tab_checkout_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'checkout_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .checkout-button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .checkout-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_checkout_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'checkout_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .checkout-button:hover, {{WRAPPER}} .checkout-button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .checkout-button:hover svg, {{WRAPPER}} .checkout-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .checkout-button:hover, {{WRAPPER}} .checkout-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkout_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'checkout_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .checkout-button:hover, {{WRAPPER}} .checkout-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'checkout_button_border',
                'selector' => '{{WRAPPER}} .checkout-button, {{WRAPPER}} .checkout-button.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'checkout_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'checkout_button_box_shadow',
                'selector' => '{{WRAPPER}} .checkout-button',
            ]
        );

        $this->add_responsive_control(
            'checkout_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_return_shop_button_style',
            [
                'label' => __( 'Continue Shopping Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_return_shop_button!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'return_shop_button_typography',
                'selector' => '{{WRAPPER}} .return-shop',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'return_shop_button_text_shadow',
                'selector' => '{{WRAPPER}} .return-shop',
            ]
        );

        $this->start_controls_tabs( 'tabs_return_shop_button_style' );

        $this->start_controls_tab(
            'tab_return_shop_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'return_shop_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .return-shop' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'return_shop_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .return-shop' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_return_shop_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'return_shop_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .return-shop:hover, {{WRAPPER}} .return-shop:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .return-shop:hover svg, {{WRAPPER}} .return-shop:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'return_shop_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .return-shop:hover, {{WRAPPER}} .return-shop:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'return_shop_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'return_shop_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .return-shop:hover, {{WRAPPER}} .return-shop:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'return_shop_button_border',
                'selector' => '{{WRAPPER}} .return-shop, {{WRAPPER}} .return-shop.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'return_shop_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .return-shop' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'return_shop_button_box_shadow',
                'selector' => '{{WRAPPER}} .return-shop',
            ]
        );

        $this->add_responsive_control(
            'return_shop_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .return-shop' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional_cart_totals_style',
            [
                'label' => esc_html__( 'Additional Block', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'additional_cart_totals_template_switch!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'additional_cart_totals_typography',
                'selector' => '{{WRAPPER}} .cart_totals_additional',
                'condition' => [
                    'additional_cart_totals_content_type' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'additional_cart_totals_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cart_totals_additional' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
                'condition' => [
                    'additional_cart_totals_content_type' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'additional_cart_totals_space',
            [
                'label' => __( 'Top Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'vw' ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cart_totals_additional:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cart_totals_additional:last-child' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        if ( !class_exists('WooCommerce') ) {
            echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
            return;
        }

        if ( ! is_object( WC()->cart ) || 0 === WC()->cart->get_cart_contents_count() ) {
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( !!$settings['additional_cart_totals_template_switch'] )
            add_action('etheme_woocommerce_cart_'.$settings['additional_cart_totals_position'].'_collaterals', array($this, 'additional_cart_totals_template'));

        remove_action( 'woocommerce_proceed_to_checkout', 'etheme_woocommerce_continue_shopping', 21 );
        if ( !!$settings['show_return_shop_button'] )
            add_action( 'woocommerce_proceed_to_checkout', 'etheme_woocommerce_continue_shopping', 21 );

//        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        WC()->cart->calculate_fees();
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        ob_start();
        woocommerce_cart_totals();
        $out = ob_get_clean();

        if ( has_action('etheme_woocommerce_cart_before_collaterals') ) {
            ?>
            <div class="cart_totals_additional">
                <?php
                do_action('etheme_woocommerce_cart_before_collaterals');
                ?>
            </div>
            <?php
        }

        echo !!$settings['show_heading'] ? str_replace(array('<h2>', '<h2', '</h2>'), array('<h2 class="cart-totals-title widget-title style-' . $settings['heading_type'].'"><span>', '<'.$settings['heading_html_tag'], '</'.$settings['heading_html_tag'].'>'), $out) :
            str_replace(array('<h2>'), array('<h2 class="cart-totals-title widget-title">'), $out);

        if ( has_action('etheme_woocommerce_cart_after_collaterals') ) {
            ?>
            <div class="cart_totals_additional">
                <?php
                do_action('etheme_woocommerce_cart_after_collaterals');
                ?>
            </div>
            <?php
        }

        if ( !!$settings['additional_cart_totals_template_switch'] )
            remove_action('etheme_woocommerce_cart_'.$settings['additional_cart_totals_position'].'_collaterals', array($this, 'additional_cart_totals_template'));
	}

    public function additional_cart_totals_template() {
        $settings = $this->get_settings_for_display();
        $fallback_text = esc_html__('Custom content below Cart Totals', 'xstore-core');

        switch ($settings['additional_cart_totals_content_type']) {
            case 'custom':
                if (!empty($settings['additional_cart_totals_template_content']))
                    $this->print_unescaped_setting('additional_cart_totals_template_content');
                else
                    echo $fallback_text;
                break;
            case 'global_widget':
            case 'saved_template':
                $prefix = 'additional_cart_totals_';
                if (!empty($settings[$prefix.$settings[$prefix.'content_type']]) && !in_array($settings[$prefix.$settings[$prefix.'content_type']], array('select', 'no_template'))):
                    //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                    $posts = get_posts(
                        [
                            'name' => $settings[$prefix.$settings[$prefix.'content_type']],
                            'post_type'      => 'elementor_library',
                            'posts_per_page' => '1',
                            'tax_query'      => [
                                [
                                    'taxonomy' => 'elementor_library_type',
                                    'field'    => 'slug',
                                    'terms'    => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings[$prefix.'content_type']),
                                ],
                            ],
                            'fields' => 'ids'
                        ]
                    );

                    if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) { // @todo maybe try to enchance TRUE value with on ajax only
                        echo esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                    } else {
                        echo $content;
                    }
                else:
                    echo $fallback_text;
                endif;
                break;
            case 'static_block':
                $prefix = 'additional_cart_totals_';
                Elementor::print_static_block($settings[$prefix.$settings[$prefix.'content_type']]);
                break;
        }
    }

}
