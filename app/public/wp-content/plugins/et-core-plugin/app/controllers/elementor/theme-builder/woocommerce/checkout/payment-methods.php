<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Cart Totals widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Payment_Methods extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-checkout-etheme_payment_methods';
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
		return __( 'Checkout Payment Methods', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-checkout-page et-elementor-checkout-builder-new-widget-icon';
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
        return [ 'woocommerce', 'checkout' ];
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
        $scripts = [ 'wc-checkout', 'wc-add-payment-method' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'etheme_elementor_checkout_page';
        }
        return $scripts;
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
            'design_type',
            [
                'label' => esc_html__( 'Design type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Classic', 'xstore-core' ),
                    'multistep' => esc_html__( 'Multistep', 'xstore-core' ),
//                    'separated' => esc_html__( 'Separated', 'xstore-core' ),
                ],
                'render_type' => 'template',
                'prefix_class' => 'design-type-',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'design_type_description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' =>
                    esc_html__('Classic', 'xstore-core') . ' - ' . sprintf( __( 'Use this type for making <a href="%s" target="_blank">classic design</a> of this widget.', 'xstore-core' ), 'https://prnt.sc/2YEEjt4tV8JK' ) . '<br/>' .
                esc_html__('Multistep', 'xstore-core') . ' - ' . sprintf( __( 'Use this type for making <a href="%s" target="_blank">accordion steps</a> on this widget.', 'xstore-core' ), 'https://prnt.sc/0Cj3eJWxleU5' ) . '<br/>',
//                esc_html__('Separated', 'xstore-core') . ' - ' . sprintf( __( 'Use this type for making next column filled with <a href="%s" target="_blank">full-height background</a>, we recommend you to add aside Order Total widget', 'xstore-core' ), 'https://prnt.sc/CGeFeSWiBLr-' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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
            'payment_methods_section_heading',
            [
                'label' => esc_html__( 'Section Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Payment', 'xstore-core' ),
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_heading!' => '',
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
                'condition' => [
                    'show_heading!' => '',
                ],
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
                    '{{WRAPPER}} .step-title' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
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
                    '{{WRAPPER}}' => '--payment-methods-rows-gap: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .step-title',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .step-title' => 'color: {{VALUE}}',
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
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_label_colors' );

        $this->start_controls_tab(
            'tab_label_color_normal',
            [
                'label'                 => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_label_color_active',
            [
                'label'                 => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color_active',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} #payment .payment_methods input[type=radio]:checked+label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'label_typography',
                'selector'              => '{{WRAPPER}} #payment .payment_methods label',
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} #payment .payment_methods label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

//        $this->start_controls_section(
//            'section_radio_checkbox_style',
//            [
//                'label' => esc_html__( 'Radio & Checkbox', 'xstore-core' ),
//                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//            ]
//        );
//
//        $this->add_responsive_control(
//            'radio_checkbox_size',
//            [
//                'label'                 => __( 'Size', 'xstore-core' ),
//                'type'                  => \Elementor\Controls_Manager::SLIDER,
//                'range'                 => [
//                    'px'        => [
//                        'min'   => 0,
//                        'max'   => 80,
//                        'step'  => 1,
//                    ],
//                ],
//                'size_units'            => [ 'px', 'em', '%' ],
//                'selectors'             => [
//                    '{{WRAPPER}}' => '--et_inputs-radio-size: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            [
                'label'                 => __( 'Payment Box', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .payment_box' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'box_typography',
                'selector'              => '{{WRAPPER}} .payment_box',
            ]
        );

        $this->add_control(
            'box_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .payment_box' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_terms_style',
            [
                'label'                 => __( 'Terms & Conditions', 'xstore-core' ),
                'tab'                   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'terms_color',
            [
                'label'                 => __( 'Text Color', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_terms_links_colors');

        $this->start_controls_tab(
            'tab_terms_link_color_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color',
            [
                'label' => esc_html__( 'Link Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_terms_link_color_color_hover',
            [
                'label' => esc_html__('Links Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'terms_link_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'                  => 'terms_typography',
                'selector'              => '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper',
            ]
        );

        $this->add_control(
            'terms_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_place_order_button_style',
            [
                'label' => __( 'Place Order Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'place_order_button_typography',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'place_order_button_text_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->start_controls_tabs( 'tabs_place_order_button_style' );

        $this->start_controls_tab(
            'tab_place_order_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_place_order_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'place_order_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} #place_order:hover svg, {{WRAPPER}} #place_order:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'place_order_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'place_order_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} #place_order:hover, {{WRAPPER}} #place_order:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'place_order_button_border',
                'selector' => '{{WRAPPER}} #place_order, {{WRAPPER}} #place_order.button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'place_order_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'place_order_button_box_shadow',
                'selector' => '{{WRAPPER}} #place_order',
            ]
        );

        $this->add_responsive_control(
            'place_order_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        if ( !class_exists('WooCommerce') ) {
            echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
            return;
        }

        if ( ! is_object( WC()->cart ) || 0 === WC()->cart->get_cart_contents_count() ) {
            if ( get_query_var('et_is-checkout-basic', false) ) {
                echo '<span class="etheme-elementor-checkout-widgets-contain screen-reader-text hidden elementor-etheme_checkout_placeholder">'.esc_html__('Placeholder for replacement with default shortcode', 'xstore-core').'</span>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        $multistep_design = $settings['design_type'] == 'multistep';
        if ( $multistep_design ) {
            wp_enqueue_script('etheme_elementor_checkout_page');
            add_action('etheme_after_checkout_payment_methods_form_fields_wrapper', array($this, 'multistep_footer_steps'));
        }

        if ( $edit_mode )
            add_filter('wp_doing_ajax', '__return_false');

        add_action('woocommerce_review_order_before_payment', array($this, 'add_heading'));
        add_action('woocommerce_review_order_before_payment', array($this, 'add_wrapper_start'));
        add_action('woocommerce_review_order_after_payment', array($this, 'add_wrapper_end'));

        add_filter('etheme_woocommerce_checkout_payment_methods_title', array($this, 'modify_section_title'));

        if ( !!$settings['show_heading'] ) {
            add_filter('etheme_checkout_form_payment_methods_title', '__return_true');
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            add_filter('etheme_checkout_form_payment_methods_title', '__return_false');
        }

        WC()->cart->calculate_fees();
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        ?>
        <div class="etheme-elementor-checkout-widgets-contain">
            <?php
                woocommerce_checkout_payment();
            ?>
        </div>
        <?php

        remove_filter('etheme_woocommerce_checkout_payment_methods_title', array($this, 'modify_section_title'));

        if ( !!$settings['show_heading'] ) {
            remove_filter('etheme_checkout_form_payment_methods_title', '__return_true');
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            remove_filter('etheme_checkout_form_payment_methods_title', '__return_false');
        }

        remove_action('woocommerce_review_order_before_payment', array($this, 'add_wrapper_start'));
        remove_action('woocommerce_review_order_after_payment', array($this, 'add_wrapper_end'));
        remove_action('woocommerce_review_order_before_payment', array($this, 'add_heading'));

        if ( $multistep_design )
            remove_action('etheme_after_checkout_payment_methods_form_fields_wrapper', array($this, 'multistep_footer_steps'));

        if ( $edit_mode )
            remove_filter('wp_doing_ajax', '__return_false');
	}

    public function add_heading() {
        if ( apply_filters('etheme_checkout_form_payment_methods_title', true) ) { ?>
            <<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?> class="<?php echo apply_filters('etheme_woocommerce_checkout_title_class', 'step-title'); ?>">
                <span><?php echo apply_filters('etheme_woocommerce_checkout_payment_methods_title', esc_html__( 'Payment', 'xstore-core' )); ?></span>
            </<?php echo apply_filters('etheme_woocommerce_checkout_title_tag', 'h3'); ?>>
        <?php }
    }

    public function add_wrapper_start() {
        ?>
        <div class="woocommerce-checkout-payment-wrapper">
        <?php
    }
    public function add_wrapper_end() {
        do_action('etheme_after_checkout_payment_methods_form_fields_wrapper' ) ?>
        </div>
        <?php
    }

    public function multistep_footer_steps() {
        $is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $prev_title = $is_edit ? esc_html__('Previous step', 'xstore-core') : '{{step_title}}';
        $next_title = $is_edit ? esc_html__('Next step', 'xstore-core') : '{{step_title}}';
        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'payment_methods',
            'previous_text' => $prev_title,
            'next_text' => $next_title,
            'loading' => !$is_edit ? 'yes' : ''
        ));
    }

    public function modify_section_title($title) {
        $new_title = $this->get_settings_for_display('payment_methods_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function title_tag($html_tag) {
        return $this->get_settings_for_display('heading_html_tag');
    }

    public function title_class($class) {
        return $class . ' style-'. $this->get_settings_for_display('heading_type');
    }

}
