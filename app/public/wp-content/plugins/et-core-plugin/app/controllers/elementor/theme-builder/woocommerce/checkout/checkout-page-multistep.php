<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Checkout Page Multistep widget.
 *
 * @since      5.2.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Checkout_Page_Multistep extends Checkout_Page {
    protected static $needs_shipping;

    protected static $need_shipping_methods;
    protected static $has_order_notes;

    protected static $can_create_account;

    protected static $has_payments_separated;
    /**
     * Get widget name.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'woocommerce-checkout-etheme_page_multistep';
    }

    /**
     * Get widget title.
     *
     * @since 5.2.4
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Checkout Page (Multistep)', 'xstore-core' );
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        return array_unique(array_merge(parent::get_script_depends(), ['etheme_elementor_checkout_page']));
    }

    /**
     * Register widget controls.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function register_controls() {

        add_action('etheme_elementor_checkout_page_before_section_general_style', [$this, 'add_sections_steps_settings']);

        add_action('etheme_elementor_checkout_page_before_section_heading_style', [$this, 'add_sections_style']);

        add_filter('etheme_checkout_fields_section_style', '__return_false');

        add_filter('etheme_checkout_page_heading_color', '__return_false');

        parent::register_controls();

        $this->update_control(
            'show_heading',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'yes',
            ]
        );

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'design_type_multistep',
            [
                'label' => esc_html__('Accordant Steps', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'accordant',
                'frontend_available' => true,
                'render_type' => 'template',
                'prefix_class' => 'etheme-elementor-checkout-multistep-',
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'design_type',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'multistep',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'checkout_page_design_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'multistep',
                'prefix_class' => 'etheme-elementor-checkout-',
            ]
        );

        $this->end_injection();

        // remove this control because we need to add it in tabs with active color
//        $this->remove_control('heading_color');

        foreach (['payment_methods', 'shipping_methods' ] as $default_separated_section) {
            $this->update_control(
                $default_separated_section.'_position',
                [
                    'default' => 'separated',
                ]
            );
        }

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'heading_border_width',
        ] );

        $this->start_controls_tabs( 'tabs_heading_colors' );

        $this->start_controls_tab(
            'tabs_heading_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
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

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_heading_color_active',
            [
                'label' => esc_html__( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'heading_color_active',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-active-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_counter_color_active',
            [
                'label' => esc_html__( 'Counter Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-counter-active-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_injection();

        remove_action('etheme_elementor_checkout_page_before_section_general_style', [$this, 'add_sections_steps_settings']);

        remove_action('etheme_elementor_checkout_page_before_section_heading_style', [$this, 'add_sections_style']);

        remove_filter('etheme_checkout_fields_section_style', '__return_false');
    }

    // @todo 
    public function add_sections_steps_settings($class) {
        $class->start_controls_section(
            'section_sections_footer_steps',
            [
                'label' => esc_html__( 'Footer Steps', 'xstore-core' ),
            ]
        );

        $class->add_control(
            'sections_footer_steps_type',
            [
                'label' => esc_html__( 'Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __( 'Classic', 'xstore-core' ),
                    'stretch' => __( 'Stretch', 'xstore-core' ),
                ),
            ]
        );

        $class->add_control(
            'sections_footer_previous_step',
            [
                'label' => __( 'Show Previous Step', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $class->end_controls_section();
    }
    public function add_sections_style($class) {
        $class->start_controls_section(
            'section_sections_style',
            [
                'label' => esc_html__( 'Section', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $class->start_controls_tabs('tabs_sections_bg');

        $class->start_controls_tab( 'tabs_tabs_sections_bg_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core')
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'section_background',
                'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
                'selector'    => '{{WRAPPER}} .design-type-multistep',
            ]
        );

        $class->end_controls_tab();

        $class->start_controls_tab( 'tabs_tabs_sections_bg_active',
            [
                'label' => esc_html__('Active', 'xstore-core')
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'section_background_active',
                'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
                'selector'    => '{{WRAPPER}} .design-type-multistep:has(.step-title.opened)',
            ]
        );

        $class->add_control(
            'section_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'section_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .design-type-multistep:has(.step-title.opened)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $class->end_controls_tab();
        $class->end_controls_tabs();

        $class->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'section_border',
                'selector' => '{{WRAPPER}} .design-type-multistep',
                'separator' => 'before',
            ]
        );

        $class->add_responsive_control(
            'section_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .design-type-multistep' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'section_box_shadow',
                'selector' => '{{WRAPPER}} .design-type-multistep',
            ]
        );

        $class->add_responsive_control(
            'section_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .design-type-multistep' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $class->end_controls_section();

        $class->start_controls_section(
            'section_next_step_button_style',
            [
                'label' => __( 'Next Step Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'next_step_button_typography',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-next-step',
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'next_step_button_text_shadow',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-next-step',
            ]
        );

        $class->start_controls_tabs( 'tabs_next_step_button_style' );

        $class->start_controls_tab(
            'tab_next_step_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $class->add_control(
            'next_step_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'next_step_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $class->end_controls_tab();

        $class->start_controls_tab(
            'tab_next_step_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $class->add_control(
            'next_step_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step:hover, {{WRAPPER}} .etheme-checkout-page-next-step:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-checkout-page-next-step:hover svg, {{WRAPPER}} .etheme-checkout-page-next-step:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'next_step_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step:hover, {{WRAPPER}} .etheme-checkout-page-next-step:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'next_step_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'next_step_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step:hover, {{WRAPPER}} .etheme-checkout-page-next-step:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $class->end_controls_tab();

        $class->end_controls_tabs();

        $class->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'next_step_button_border',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-next-step, {{WRAPPER}} .etheme-checkout-page-next-step.button',
                'separator' => 'before',
            ]
        );

        $class->add_control(
            'next_step_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'next_step_button_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-next-step',
            ]
        );

        $class->add_responsive_control(
            'next_step_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-next-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $class->end_controls_section();

        $class->start_controls_section(
            'section_prev_step_button_style',
            [
                'label' => __( 'Prev Step Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'prev_step_button_typography',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-previous-step',
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'prev_step_button_text_shadow',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-previous-step',
            ]
        );

        $class->start_controls_tabs( 'tabs_prev_step_button_style' );

        $class->start_controls_tab(
            'tab_prev_step_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $class->add_control(
            'prev_step_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'prev_step_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $class->end_controls_tab();

        $class->start_controls_tab(
            'tab_prev_step_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $class->add_control(
            'prev_step_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step:hover, {{WRAPPER}} .etheme-checkout-page-previous-step:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-checkout-page-previous-step:hover svg, {{WRAPPER}} .etheme-checkout-page-previous-step:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'prev_step_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step:hover, {{WRAPPER}} .etheme-checkout-page-previous-step:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $class->add_control(
            'prev_step_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'prev_step_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step:hover, {{WRAPPER}} .etheme-checkout-page-previous-step:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $class->end_controls_tab();

        $class->end_controls_tabs();

        $class->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'prev_step_button_border',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-previous-step, {{WRAPPER}} .etheme-checkout-page-previous-step.button',
                'separator' => 'before',
            ]
        );

        $class->add_control(
            'prev_step_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $class->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'prev_step_button_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-checkout-page-previous-step',
            ]
        );

        $class->add_responsive_control(
            'prev_step_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-checkout-page-previous-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $class->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2.4
     * @access protected
     */
    protected function render() {
        if ( !$this->is_woocommerce() ) {
            parent::render();
            return;
        }

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $edit_mode )
            add_filter('etheme_checkout_form_shipping_address', '__return_true');

        self::$needs_shipping = apply_filters('etheme_checkout_form_shipping_address', (WC()->cart->needs_shipping_address() === true));
        self::$need_shipping_methods = $edit_mode || (WC()->cart->needs_shipping() && WC()->cart->show_shipping());
        self::$can_create_account = ($edit_mode || ! get_query_var( 'et_is-loggedin', false)) && WC()->checkout()->is_registration_enabled();
        self::$has_order_notes = apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) && !!$this->get_settings_for_display('additional_information_switcher');
        self::$has_payments_separated = $this->get_settings_for_display('payment_methods_position') == 'separated';
        $footer_steps = array(
            'billing' => array(
                'hook' => 'etheme_after_checkout_billing_form_fields_wrapper',
            ),
            'payment_methods' => array(
                'hook' => 'etheme_after_checkout_payment_methods_form_fields_wrapper'
            ),
            'account' => array(
                'hook' => 'etheme_after_checkout_account_form_fields_wrapper',
            ),
            'additional_information' => array(
                'hook' => 'etheme_after_checkout_additional_information_form_fields_wrapper',
            ),
        );
        if ( self::$need_shipping_methods ) {
            $footer_steps['shipping_methods'] = array(
                'hook' => 'etheme_after_checkout_shipping_methods_form_fields_wrapper',
            );
        }
        if ( self::$needs_shipping ) {
            $footer_steps['shipping'] = array(
                'hook' => 'etheme_after_checkout_shipping_form_fields_wrapper',
            );
        }

        foreach ($footer_steps as $footer_step_key => $footer_step_details) {
            add_filter('etheme_checkout_form_'.$footer_step_key.'_wrapper', '__return_true');
            add_filter($footer_step_details['hook'], array($this, $footer_step_key.'_footer'));
        }
        $multistep_filters_steps = array(
            'etheme_checkout_form_billing_wrapper_classes',
            'etheme_checkout_form_payment_methods_wrapper_classes',
            'etheme_checkout_form_account_wrapper_classes',
            'etheme_checkout_form_additional_information_wrapper_classes'
        );
        if ( self::$needs_shipping ) {
            $multistep_filters_steps[] = 'etheme_checkout_form_shipping_wrapper_classes';
        }
        if ( self::$need_shipping_methods ) {
            $multistep_filters_steps[] = 'etheme_checkout_form_shipping_methods_wrapper_classes';
        }
        foreach ($multistep_filters_steps as $multistep_filters_step) {
            add_filter($multistep_filters_step, array($this, 'add_multistep_class'));
        }

        add_filter('etheme_checkout_form_additional_information_separated', '__return_true');
        add_filter('etheme_checkout_form_additional_information_title_force_display', '__return_true');
        parent::render();
        foreach ($multistep_filters_steps as $multistep_filters_step) {
            remove_filter($multistep_filters_step, array($this, 'add_multistep_class'));
        }
        foreach ($footer_steps as $footer_step_key => $footer_step_details) {
            remove_filter($footer_step_details['hook'], array($this, $footer_step_key.'_footer'));
            remove_filter('etheme_checkout_form_'.$footer_step_key.'_wrapper', '__return_true');
        }
    }

    public function billing_footer() {
        $next_step_title = $this->get_steps_heading();
        if ( self::$has_payments_separated )
            $next_step_title = $this->get_steps_heading('payment_methods');

        if ( self::$need_shipping_methods )
            $next_step_title = $this->get_steps_heading('shipping_methods');

        if ( self::$has_order_notes )
            $next_step_title = $this->get_steps_heading('additional_information');

        if ( self::$needs_shipping )
            $next_step_title = $this->get_steps_heading('shipping');

        if ( self::$can_create_account )
            $next_step_title = $this->get_steps_heading('new_account');

        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'billing',
            'previous_text' => $this->get_steps_heading('shop', false),
            'previous_link' => wc_get_page_id('shop') > 0 ? get_permalink(wc_get_page_id('shop')) : home_url(),
            'next_text' => $next_step_title,
        ));
    }

    public function account_footer() {
        $next_step_title = $this->get_steps_heading();
        if ( self::$has_payments_separated )
            $next_step_title = $this->get_steps_heading('payment_methods');
        if ( self::$need_shipping_methods )
            $next_step_title = $this->get_steps_heading('shipping_methods');
        if ( self::$has_order_notes )
            $next_step_title = $this->get_steps_heading('additional_information');
        if ( self::$needs_shipping )
            $next_step_title = $this->get_steps_heading('shipping');

        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'account',
            'previous_text' => $this->get_steps_heading('billing', false),
            'next_text' => $next_step_title,
        ));
    }

    public function shipping_methods_footer() {
        $next_step_title = $this->get_steps_heading();
        if ( self::$has_payments_separated )
            $next_step_title = $this->get_steps_heading('payment_methods');

        $prev_step_title = $this->get_steps_heading('billing', false);

        if ( self::$can_create_account ) {
            $prev_step_title = $this->get_steps_heading('new_account', false);
        }
        if ( self::$needs_shipping ) {
            $prev_step_title = $this->get_steps_heading('shipping', false);
        }
        if ( self::$has_order_notes ) {
            $prev_step_title = $this->get_steps_heading('additional_information', false);
        }

        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'shipping_methods',
            'previous_text' => $prev_step_title,
            'next_text' => $next_step_title,
        ));
    }

    public function shipping_footer() {
        $next_step_title = $this->get_steps_heading();
        if ( self::$has_payments_separated )
            $next_step_title = $this->get_steps_heading('payment_methods');
        if ( self::$need_shipping_methods )
            $next_step_title = $this->get_steps_heading('shipping_methods');
        if ( self::$has_order_notes ) {
            $next_step_title = $this->get_steps_heading('additional_information');
        }

        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'shipping',
            'previous_text' => self::$can_create_account ? $this->get_steps_heading('new_account', false) : $this->get_steps_heading('billing', false),
            'next_text' => $next_step_title,
        ));
    }

    public function additional_information_footer() {
        $next_step_title = $this->get_steps_heading();
        if ( self::$has_payments_separated )
            $next_step_title = $this->get_steps_heading('payment_methods');
        if ( self::$need_shipping_methods )
            $next_step_title = $this->get_steps_heading('shipping_methods');

        $prev_step_title = $this->get_steps_heading('billing', false);
        if ( self::$can_create_account )
            $prev_step_title = $this->get_steps_heading('new_account', false);
        if ( self::$needs_shipping ) {
            $prev_step_title = $this->get_steps_heading('shipping', false);
        }
        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'additional_information',
            'previous_text' => $prev_step_title,
            'next_text' => $next_step_title,
            'force_next_step' => self::$has_payments_separated
        ));
    }

    public function payment_methods_footer() {
//        $prev_step_title = esc_html__('Return to billing', 'xstore-core');
//        if ( self::$can_create_account )
//            $prev_step_title = esc_html__('Return to account', 'xstore-core');
//
//        if ( self::$needs_shipping )
//            $prev_step_title = esc_html__( 'Return to Shipping', 'xstore-core' );
//
//        if ( self::$has_order_notes )
//            $prev_step_title = esc_html__('Return to Order Notes', 'xstore-core');

        Elementor::get_checkout_multistep_footer_steps($this, array(
            'step' => 'payment_methods',
            'previous_text' => false,
            'next_text' => false,
        ));
    }

    public function get_steps_heading($step = '', $next_step = true) {
        switch ($step) {
            case 'shop':
                $heading = esc_html__('Shop', 'xstore-core');
                if ( !$next_step )
                    $heading = esc_html__('Return to shop', 'xstore-core');
                break;
            case 'billing':
                $heading = esc_html__('Billing', 'xstore-core');
                if ( !$next_step )
                    $heading = esc_html__('Return to billing', 'xstore-core');
                break;
            case 'shipping':
                $heading = apply_filters('etheme_woocommerce_checkout_shipping_title', esc_html__( 'Shipping', 'xstore-core' ));
                if ( !$next_step )
                    $heading = esc_html__('Return to shipping', 'xstore-core');
                break;
            case 'payment_methods':
                $heading = esc_html__('Payments', 'xstore-core');
                if ( !$next_step )
                    $heading = esc_html__('Return to payments', 'xstore-core');
                break;
            case 'shipping_methods':
                $heading = esc_html__('Shipping methods', 'xstore-core');
                if ( !$next_step )
                    $heading = esc_html__('Return to shipping methods', 'xstore-core');
                break;
            case 'new_account':
                $heading = apply_filters('etheme_woocommerce_checkout_new_account_title', esc_html__( 'New Customer', 'xstore-core' ));
                if ( !$next_step )
                    $heading = esc_html__('Return to account', 'xstore-core');
                break;
            case 'additional_information':
                $heading = apply_filters('etheme_woocommerce_checkout_additional_information_title', esc_html__( 'Order notes', 'xstore-core' ));
                if ( !$next_step )
                    $heading = esc_html__('Return to order notes', 'xstore-core');
                break;
            default:
                $heading = esc_html__('Place order', 'xstore-core');
                if ( !$next_step )
                    $heading = esc_html__('Return to place order', 'xstore-core');
                break;
        }
        return $heading;
    }

    public function add_multistep_class($classes) {
        $classes[] = 'design-type-multistep';
        return $classes;
    }

}
