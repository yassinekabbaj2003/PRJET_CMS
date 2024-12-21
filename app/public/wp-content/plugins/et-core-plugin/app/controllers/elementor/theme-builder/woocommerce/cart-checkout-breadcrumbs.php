<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce;

use ETC\App\Classes\Elementor;

/**
 * Cart/Checkout breadcrumbs steps widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Checkout_Breadcrumbs extends \Elementor\Widget_Base {

    private static $cart_url;
    private static $checkout_url;

    public static $needs_shipping = null;
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-etheme_cart_checkout_breadcrumbs';
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
		return __( 'Cart/Checkout Breadcrumbs (steps)', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-breadcrumb';
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
        return [ 'woocommerce-elements', 'shop', 'store', 'breadcrumbs', 'internal links', 'product', 'steps', 'checkout', 'order' ];
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
        return ['woocommerce-elements-archive', 'woocommerce-elements-single'];
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
        return [ 'etheme-elementor-breadcrumbs-steps' ];
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
//        $scripts = [];
//        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
//            $scripts = [ 'etheme_elementor_breadcrumbs_steps' ];
        $scripts = ['etheme_elementor_breadcrumbs_steps'];
        return $scripts;
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
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('To set up breadcrumbs for Cart/Checkout pages, you can use a %s or employ the "WooCommerce Breadcrumbs" widget.', 'xstore-core'),
                    '<a href="'.admin_url('/customize.php?autofocus[section]=breadcrumbs').'" target="_blank">'.esc_html__('Special breadcrumb on the cart, checkout, and order pages.', 'xstore-core') . '</a>'). '<br/>' .
                        esc_html__('If you encounter duplicated breadcrumbs, refer to the provided options for resolution.', 'xstore-core'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'description_2',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => esc_html__('If you would like to configure this widget once and use it in all next cases by default then save it as default with right mouse click.', 'xstore-core') .
                        ' <a href="https://prnt.sc/lKZQ1OfzcGol" target="_blank">'.esc_html__('Example', 'xstore-core') . '</a>',
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        // @todo fully remove if not needed after 20.02.2023
        $this->add_control(
            'type',
            [
                'label' => __( 'Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN, // could be SELECT if anyone ask us for it
                'options' => [
                    'classic' => esc_html__('Classic', 'xstore-core'),
                    'fractional' => esc_html__('Advanced', 'xstore-core'),
                ],
                'default' => 'classic',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'numbered',
            [
                'label' => __( 'Numbered', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => __( 'Separator Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'line' => esc_html__('Line', 'xstore-core'),
                    'icon' => esc_html__('Icon', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
                ],
                'default' => 'icon',
            ]
        );

        $this->add_control(
            'separator_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-right-arrow',
                    'library' => 'xstore-icons',
                ],
                'condition' => [
                    'separator_type' => 'icon'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_zoom',
            [
                'label' => __( 'Content Zoom', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--steps-content-zoom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_steps_customize',
            [
                'label' => esc_html__( 'Customize', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'customize_steps',
            [
                'label' => __( 'Customize Steps', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        foreach ($this->get_steps_titles() as $step_key => $step_title ) {
            $is_active = true;
            $condition = [
                'customize_steps!' => ''
            ];
            switch ($step_key) {
                case 'checkout':
                    $condition += [
                        'type!' => 'fractional'
                    ];
                break;
                case 'billing_details':
                case 'new_account':
                case 'shipping_details':
                case 'shipping_methods':
                case 'payment_methods':
                case 'additional_information':
                    $condition += [
                        'type' => 'fractional'
                    ];
                    if ( in_array($step_key, array('new_account', 'additional_information') ) )
                        $is_active = false;
                    break;
            }
                $this->add_control(
                    $step_key.'_heading',
                    [
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                        'label' => $step_title,
                        'condition' => $condition
                    ]
                );

            $this->add_control(
                $step_key.'_step_active',
                [
                    'label' => __( 'Active Status', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => $is_active ? 'yes' : '',
                    'condition' => $condition
                ]
            );
            $this->add_control(
                $step_key.'_step_title',
                [
                    'label' => esc_html__('Title', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => array_merge($condition, [$step_key.'_step_active!' => ''])
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'steps_gap',
            [
                'label' => __( 'Steps Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'em', 'px', 'rem', 'vw' ],
                'default' => [
                    'unit' => 'em'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--steps-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-breadcrumbs-steps-inner' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'margin-right: auto; margin-left: calc(var(--steps-gap,.3em) * -1);',
                    'center' => 'margin: 0 auto',
                    'right' => 'margin-left: auto; margin-right: calc(var(--steps-gap,.3em) * -1)',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-breadcrumbs-steps',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .etheme-elementor-breadcrumbs-steps',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-breadcrumbs-steps' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __( 'Border Radius', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .etheme-elementor-breadcrumbs-steps' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_step_number_style',
            [
                'label' => __( 'Numbers', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'numbered!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'step_number_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-breadcrumbs-step:before',
            ]
        );

        $this->start_controls_tabs( 'step_number_colors_style' );

        $this->start_controls_tab(
            'step_number_colors_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'step_number_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'step_number_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-bg-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'step_number_colors_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'step_number_color_active',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'step_number_background_color_active',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-bg-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'step_number_proportion',
            [
                'label' => __( 'Width/Height Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'em', 'px', 'rem', 'vw' ],
                'default' => [
                    'unit' => 'em'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-proportion: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_number_border_style',
            [
                'label' => __( 'Border Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'xstore-core' ),
                    'solid' => __( 'Solid', 'xstore-core' ),
                    'double' => __( 'Double', 'xstore-core' ),
                    'dotted' => __( 'Dotted', 'xstore-core' ),
                    'dashed' => __( 'Dashed', 'xstore-core' ),
                ],
                'default' => '',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-border-width: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_number_border_width',
            [
                'label' => __( 'Border Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'em', 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'step_number_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'step_number_spacing',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--step-number-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_step_style',
            [
                'label' => __( 'Step', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'step_typography',
                'exclude' => ['font_size'],
                'selector' => '{{WRAPPER}} .etheme-elementor-breadcrumbs-step-text',
            ]
        );

        $this->start_controls_tabs( 'tabs_step_colors_style' );

        $this->start_controls_tab(
            'tab_step_color_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'step_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_step_color_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'step_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_step_color_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'step_color_active',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_step_separator_style',
            [
                'label' => __( 'Separator', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'separator_type!' => 'none'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'step_separator_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-breadcrumbs-step-separator',
                'condition' => [
                    'separator_type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'step_separator_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--step-separator-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'step_separator_line_style',
            [
                'label' => __( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'xstore-core' ),
                    'solid' => __( 'Solid', 'xstore-core' ),
                    'double' => __( 'Double', 'xstore-core' ),
                    'dotted' => __( 'Dotted', 'xstore-core' ),
                    'dashed' => __( 'Dashed', 'xstore-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--step-separator-line-style: {{VALUE}};',
                ],
                'condition' => [
                    'separator_type' => 'line'
                ]
            ]
        );

        $this->add_control(
            'step_separator_line_width',
            [
                'label' => __( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--step-separator-line-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_type' => 'line'
                ]
            ]
        );

        $this->add_responsive_control(
            'step_separator_line_min_width',
            [
                'label' => __( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--step-separator-line-min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_type' => 'line'
                ]
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

        if (!class_exists('WooCommerce')) return;

        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        self::$cart_url = wc_get_cart_url();
        self::$checkout_url = wc_get_checkout_url();
        self::$needs_shipping = !$edit_mode ? apply_filters('etheme_checkout_form_shipping_address', (WC()->cart->needs_shipping_address() === true)) : false;

        global $wp;
        $page_id = get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
        $page_id = $page_id['id'];
        $is_checkout = $page_id == wc_get_page_id( 'checkout' ) || get_query_var( 'et_is-checkout', false );
        $is_cart = $page_id == wc_get_page_id( 'cart' ) || get_query_var( 'et_is-cart', false );
        $is_order = false;

        // Handle checkout actions.
        if ( ! empty( $wp->query_vars['order-pay'] ) ) {
        } elseif ( isset( $wp->query_vars['order-received'] ) ) {
            $is_order = true;
        }

        // keep only cart step active in edit mode
        if ( $edit_mode ) {
            $is_checkout = false;
            $is_cart = true;
            $is_order = false;
        }

        if ( !$edit_mode ) {
            if (!($is_checkout || $is_cart || $is_order)) return;
        }

//        if ( $settings['type'] == 'fractional' )
            wp_enqueue_script('etheme_elementor_breadcrumbs_steps');

        $this->add_render_attribute( 'wrapper', 'class', 'etheme-elementor-breadcrumbs-steps');

        $this->add_render_attribute( 'inner', 'class', 'etheme-elementor-breadcrumbs-steps-inner' );

        $this->add_render_attribute( 'separator', [
            'class' => 'etheme-elementor-breadcrumbs-step-separator',
        ] );

        if ( $settings['separator_type'] == 'line') {
            $this->add_render_attribute( 'separator', [
                'class' => 'etheme-elementor-breadcrumbs-step-separator-line',
            ] );
        }

        $this->add_render_attribute( 'step-text', [
            'class' => 'etheme-elementor-breadcrumbs-step-text'
        ]);

        $default_titles = $this->get_steps_titles();
        $steps = array(
            'cart' => [
                'class' => [],
                'title' => $default_titles['cart']
            ],
            'checkout' => [
                'class' => [],
                'title' => $default_titles['checkout']
            ],
            'order' => [
                'class' => ['no-click'],
                'title' => $default_titles['order']
            ]
        );

        if ( $is_order ) {
            $steps['cart']['class'] = $steps['checkout']['class'] = $steps['order']['class'] = ['is-active'];
        }
        elseif ( $is_checkout ) {
            $steps['cart']['class'] = $steps['checkout']['class'] = ['is-active'];
            $steps['checkout']['class'][] = 'no-click';
        }
        elseif ( $is_cart ) {
            $steps['cart']['class'][] = 'is-active';
            $steps['cart']['class'][] = 'no-click';
        }

        if ( $settings['type'] == 'fractional' ) {
            $default_checkout = $steps['checkout'];
            if ( count($default_checkout['class']) ) {
                $default_checkout_active_class = array_search('no-click', $default_checkout['class']);
                if ($default_checkout_active_class !== false) {
                    unset($default_checkout['class'][$default_checkout_active_class]);
                }
            }
            $billing_step = $default_checkout;
            $billing_step['title'] = $default_titles['billing_details'];
            $default_checkout_inactive = $default_checkout;
            if ( count($default_checkout_inactive['class']) ) {
                $default_checkout_inactive_class = array_search('is-active', $default_checkout_inactive['class']);
                if ($default_checkout_inactive_class !== false) {
                    unset($default_checkout_inactive['class'][$default_checkout_inactive_class]);
                }
            }
            $new_account_step = $default_checkout_inactive;
            $new_account_step['title'] = $default_titles['new_account'];
            $shipping_step = $default_checkout_inactive;
            $shipping_step['title'] = $default_titles['shipping_details'];
            $additional_information_step = $default_checkout_inactive;
            $additional_information_step['title'] = $default_titles['additional_information'];
            $shipping_methods_step = $default_checkout_inactive;
            $shipping_methods_step['title'] = $default_titles['shipping_methods'];
            $payment_methods_step = $default_checkout_inactive;
            $payment_methods_step['title'] = $default_titles['payment_methods'];

            $pushed_steps = [
                'billing_details' => $billing_step,
            ];

            if ( $edit_mode ||
                (! get_query_var( 'et_is-loggedin', false) && WC()->checkout()->is_registration_enabled() &&
                    ! WC()->checkout()->is_registration_required() || WC()->checkout()->get_checkout_fields( 'account' ) || has_action('etheme_before_checkout_createaccount_checkbox') || has_action('woocommerce_before_checkout_registration_form') || has_action('woocommerce_after_checkout_registration_form') )) {
                $pushed_steps += [
                    'new_account' => $new_account_step
                ];
            }

            if ( self::$needs_shipping ) {
                $pushed_steps += [
                    'shipping_details' => $shipping_step
                ];
            }

            $pushed_steps += [
                'additional_information' => $additional_information_step,
            ];

            $pushed_steps += [
                'shipping_methods' => $shipping_methods_step
            ];

            $pushed_steps += [
                'payment_methods' => $payment_methods_step
            ];

            $order_position = array_search('order', array_keys($steps));
            if ( $order_position > 1 ) {
                $steps = array_slice( $steps, 0, $order_position, true ) +
                    $pushed_steps +
                    array_slice( $steps, $order_position, count( $steps ) - $order_position, true );
            }
            else {
                $steps += $pushed_steps;
            }
            $steps += $pushed_steps;

            unset($steps['checkout']);
        }

        if ( !!$settings['customize_steps'] ) {
            foreach ($steps as $step => $step_info) {
                if ( !!!$settings[$step.'_step_active']) {
                    unset($steps[$step]);
                    continue;
                }
                $new_title = $settings[$step.'_step_title'];
                if ( !array_key_exists($step, $steps) || !$new_title ) continue;
                $steps[$step]['title'] = $new_title;
            }
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'inner' ); ?>>
                <?php
                    $i = 1;
                    foreach ($steps as $step => $step_info) {
                        $need_separator = $settings['separator_type'] != 'none' && $i<count($steps);
                        $i++;
                        $step_href = '#';
                        switch ($step) {
                            case 'cart':
                                $step_href = self::$cart_url;
                                break;
                            case 'checkout':
                            case 'billing_details':
                            case 'new_account':
                            case 'shipping_details':
                            case 'additional_information':
                            case 'shipping_methods':
                            case 'payment_methods':
                                $step_href = self::$checkout_url;
                                if ( $step != 'checkout' ) {
//                                    $step_href = add_query_arg('step', $step, $step_href);
                                    // anyway we should start from the first step on checkout page
                                    $step_href = add_query_arg('step', 'billing_details', $step_href);
                                }
                                break;
                        }
                        $this->add_render_attribute( $step.'-step', [
                            'class' => 'etheme-elementor-breadcrumbs-step',
                            'href' => $step_href,
                            'data-step' => $step
                        ] );
                        if ( !!$settings['numbered'] )
                            $this->add_render_attribute( $step.'-step', 'class', 'etheme-elementor-breadcrumbs-step-numbered' );
                        if ( count($step_info['class']) )
                            $this->add_render_attribute( $step.'-step', [
                                'class' => $step_info['class'],
                            ] );
                        ?>
                        <a <?php echo $this->get_render_attribute_string( $step.'-step' ); ?>>
                            <span <?php echo $this->get_render_attribute_string( 'step-text' ); ?>>
                                <?php echo $step_info['title']; ?>
                            </span>
                            <?php
                                if ( $need_separator ) {
                                    if ( $settings['separator_type'] == 'line') {
                                    ?>
                                        <span <?php echo $this->get_render_attribute_string( 'separator' ); ?>></span>
                                    <?php
                                    } else
                                        $this->render_icon($settings);
                                }
                            ?>
                        </a>
                        <?php
                    }
                ?>
            </div>
        </div>
        <?php
    }

    public function get_steps_titles() {
        return [
            'cart' => esc_html__('Shopping cart', 'xstore-core'),
            'checkout' => esc_html__('Checkout', 'xstore-core'),
            'billing_details' => esc_html__('Billing details', 'xstore-core'),
            'new_account' => esc_html__('New customer', 'xstore-core'),
            'shipping_details' => esc_html__('Shipping details', 'xstore-core'),
            'additional_information' => esc_html__('Additional Information', 'xstore-core'),
            'shipping_methods' => esc_html__('Shipping methods', 'xstore-core'),
            'payment_methods' => esc_html__('Payments', 'xstore-core'),
            'order' => esc_html__('Order status', 'xstore-core')
        ];
    }

    protected function render_icon($settings) {
        $migrated = isset( $settings['__fa4_migrated']['separator_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['icon'] ) || ! empty( $settings['separator_icon']['value'] ) ) : ?>
            <span <?php echo $this->get_render_attribute_string( 'separator' ); ?>>
                <?php if ( $is_new || $migrated ) :
                    \Elementor\Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
            </span>
        <?php endif;
    }

}
