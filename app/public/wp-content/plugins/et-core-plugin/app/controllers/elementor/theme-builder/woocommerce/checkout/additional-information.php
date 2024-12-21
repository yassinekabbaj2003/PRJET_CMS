<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Additional Information widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Additional_Information extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-checkout-etheme_additional_information';
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
		return __( 'Checkout Additional Information', 'xstore-core' );
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
        $scripts = [ 'wc-checkout' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'cart_checkout_advanced_labels';
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
//                    esc_html__('Separated', 'xstore-core') . ' - ' . sprintf( __( 'Use this type for making next column filled with <a href="%s" target="_blank">full-height background</a>, we recommend you to add aside Order Total widget', 'xstore-core' ), 'https://prnt.sc/CGeFeSWiBLr-' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'design_type' => 'separated',
                ],
            ]
        );

        $this->add_control(
            'advanced_labels',
            [
                'label' => esc_html__('Advanced Labels/Fields', 'xstore-core'),
                'description' => esc_html__( 'Enable this option to have aesthetically pleasing animated labels when filling out forms on the checkout page.', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'additional_information_section',
            [
                'label' => esc_html__( 'Additional Information', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_information_section_heading',
            [
                'label' => esc_html__( 'Additional Info Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Additional Information', 'xstore-core' ),
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
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
                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-border-width: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-space-bottom: {{SIZE}}{{UNIT}}',
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
//                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-element-width: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .woocommerce-shipping-fields' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields_style',
            [
                'label' => esc_html__( 'Fields', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'fields_cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
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
                    '{{WRAPPER}}' => '--fields-h-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fields_rows_gap',
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
                    '{{WRAPPER}}' => '--fields-v-gap: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .woocommerce-shipping-fields label' => 'color: {{VALUE}}',
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
                'selector'              => '{{WRAPPER}} .woocommerce-shipping-fields label',
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-shipping-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'advanced_labels' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_input_field_style',
            [
                'label' => esc_html__('Input/Textarea Fields', 'xstore-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_input_field_style');

        $this->start_controls_tab(
            'tab_input_field_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_background_color',
            [
                'label'     => esc_html__('Background Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => 'input_field_border',
                'label'       => esc_html__('Border', 'xstore-core'),
                'selector'    => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'input_field_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}}'  => '--et_inputs-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'input_field_box_shadow',
                'selector' => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select',
            ]
        );

        $this->add_responsive_control(
            'input_field_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto; line-height: initial;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => 'input_field_typography',
                'label'     => esc_html__('Typography', 'xstore-core'),
                'selector'  => '{{WRAPPER}} .woocommerce-input-wrapper input, {{WRAPPER}} .woocommerce-input-wrapper textarea, {{WRAPPER}} .woocommerce-input-wrapper select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_field_focus',
            [
                'label' => esc_html__('Focus', 'xstore-core'),
            ]
        );

        $this->add_control(
            'input_field_focus_background',
            [
                'label'     => esc_html__('Background', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_field_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-input-wrapper input:focus, {{WRAPPER}} .woocommerce-input-wrapper textarea:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'input_field_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'additional_information_section_style',
            [
                'label' => esc_html__( 'Additional Information', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'additional_information_section_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .shipping_address_wrapper + .step-title' => 'margin-top: {{SIZE}}{{UNIT}}',
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
            add_filter('etheme_checkout_form_additional_information_wrapper', '__return_true');
            add_action('etheme_after_checkout_additional_information_form_fields_wrapper', array($this, 'multistep_footer_steps'));
        }

        add_filter('etheme_checkout_form_shipping_address', '__return_false');

        add_filter('etheme_woocommerce_checkout_additional_information_title', array($this, 'modify_section_title'));

        if ( !!$settings['show_heading'] ) {
            add_filter('etheme_checkout_form_additional_information_title_force_display', '__return_true');
            add_filter('etheme_checkout_form_additional_information_title', '__return_true');
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            add_filter('etheme_checkout_form_additional_information_title', '__return_false');
        }

        if ( !!$settings['advanced_labels'] ) {
            wp_enqueue_script( 'cart_checkout_advanced_labels' );
            add_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            add_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        // filter hook for include new pages inside the payment method
        $get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );

        ?>
        <div class="woocommerce etheme-elementor-checkout-widgets-contain <?php if ( $settings['design_type'] == 'separated' ) echo ' design-styled-part'; ?>">
            <?php
                WC()->checkout()->checkout_form_shipping();
            ?>
        </div>
        <?php

        if ( !!$settings['advanced_labels'] ) {
            remove_filter('woocommerce_default_address_fields', array($this, 'filter_form_placeholders'));
            remove_filter( 'woocommerce_form_field_args', array($this, 'filter_form_fields'));
        }

        if ( !!$settings['show_heading'] ) {
            remove_filter('etheme_checkout_form_additional_information_title_force_display', '__return_true');
            remove_filter('etheme_checkout_form_additional_information_title', '__return_true');
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'), 999);
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
        }
        else {
            remove_filter('etheme_checkout_form_additional_information_title', '__return_false');
        }

        remove_filter('etheme_woocommerce_checkout_additional_information_title', array($this, 'modify_section_title'));

        remove_filter('etheme_checkout_form_shipping_address', '__return_false');

        if ( $multistep_design ) {
            remove_filter('etheme_checkout_form_additional_information_wrapper', '__return_true');
            remove_action('etheme_after_checkout_additional_information_form_fields_wrapper', array($this, 'multistep_footer_steps'));
        }
        
        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <style>
                .elementor-element-<?php echo $this->get_id(); ?> select,
                .elementor-element-<?php echo $this->get_id(); ?> textarea {
                    width: 100%;
                }
            </style>
            <script>
                jQuery(document).ready(function ($) {
                    if ( etTheme.cart_checkout_advanced_labels !== undefined )
                        etTheme.cart_checkout_advanced_labels();
                });
            </script>
            <?php
        }
	}

    public function modify_section_title($title) {
        $new_title = $this->get_settings_for_display('additional_information_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function title_tag($html_tag) {
        return $this->get_settings_for_display('heading_html_tag');
    }

    public function title_class($class) {
        return $class . ' style-'. $this->get_settings_for_display('heading_type');
    }

    public function filter_form_placeholders($fields) {
        $new_fields = array();
        foreach ($fields as $field_key => $field) {
            if ( isset($field['label']) && $field['label'] != '' ) {
                if ( isset($field['label_class']) ) {
                    if ( !in_array( 'screen-reader-text', $field['label_class'] ) )
                        $field['placeholder'] = '';
                }
                elseif ( isset($field['placeholder']) ) {
                    $field['placeholder'] = '';
                }
            }
            $new_fields[$field_key] = $field;
        }
        return $new_fields;
    }

    public function filter_form_fields ( $args ) {
        if ( $args['label'] != '' && ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-advanced-label';
            $args['placeholder'] = '';
        }

        if ( $args['type'] == 'textarea' ) {
            $args['label_class'][] = 'textarea-label';
        }

        if ( ! in_array( 'screen-reader-text', $args['label_class'] ) ) {
            $args['class'][] = 'et-validated';
        }

        return $args;
    }

    public function multistep_footer_steps() {
        $is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $prev_title = $is_edit ? esc_html__('Previous step', 'xstore-core') : '{{step_title}}';
        $next_title = $is_edit ? esc_html__('Next step', 'xstore-core') : '{{step_title}}';
        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'additional_information',
            'previous_text' => $prev_title,
            'next_text' => $next_title,
            'loading' => !$is_edit ? 'yes' : ''
        ));
    }
}
