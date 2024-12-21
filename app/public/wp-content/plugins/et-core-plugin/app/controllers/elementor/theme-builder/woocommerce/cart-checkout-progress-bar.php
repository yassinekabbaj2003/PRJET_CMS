<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce;

use ETC\App\Classes\Elementor;

/**
 * Progress bar widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Checkout_Progress_Bar extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-etheme_sales_cart_checkout_progress_bar';
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
		return __( 'Progress Bar (Sales Booster)', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-sales-booster';
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
        return [ 'woocommerce', 'shop', 'store', 'linear', 'order', 'booster', 'product' ];
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
		return [ 'etheme-sale-booster-cart-checkout-progress-bar' ];
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
        return [ 'cart_progress_bar' ];
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
        $activated_option = get_option('xstore_sales_booster_settings_cart_checkout_progress_bar');

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        if ( !$activated_option ) {
            $this->add_control(
                'description',
                [
                    'raw' => sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_progress_bar', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Progress Bar on Cart page', 'xstore-core') . '</a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_progress_bar', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }
        else {

            $this->add_control(
                'description',
                [
                    'raw' => sprintf(esc_html__('This widget inherits global settings set in %s settings.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_progress_bar', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Progress Bar on Cart page', 'xstore-core') . '</a>'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );

            $this->add_control(
                'html_wrapper_tag',
                [
                    'label' => esc_html__('HTML tag', 'xstore-core'),
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
                    'default' => 'div',
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => __( 'Wrapper', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'xstore-core'),
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .etheme_sales_booster_progress_bar_shortcode .et-cart-progress',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme_sales_booster_progress_bar_shortcode .et-cart-progress' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme_sales_booster_progress_bar_shortcode .et-cart-progress' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_progress',
            [
                'label' => __( 'Progress bar', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--progress-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'progress_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--progress-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'progress_background_color',
            [
                'label' => __( 'Default Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme_sales_booster_progress_bar_shortcode .et-cart-progress .et_cart-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'progress_background_active',
                'label' => esc_html__( 'Active Color', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                        'label' => esc_html__( 'Active Color', 'xstore-core' )
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: {{VALUE}}; --progress-active-color: {{VALUE}}',
                        ],
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 90,
                        ],
                    ]
                ],
                'selector' => '{{WRAPPER}} .etheme_sales_booster_progress_bar_shortcode .et-cart-progress .et_cart-progress-bar::-webkit-progress-value',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .et-cart-in-progress, {{WRAPPER}} .et-cart-progress-success',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .et-cart-in-progress, {{WRAPPER}} .et-cart-progress-success' => 'color: {{VALUE}};',
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
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( !get_option( 'xstore_sales_booster_settings_cart_checkout_progress_bar' ) ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' . sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_progress_bar', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Progress Bar on Cart page', 'xstore-core') . '</strong></a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_progress_bar', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Sales Booster', 'xstore-core') . '</strong></a>'));
            }
            return;
        }

        if ( !function_exists('etheme_sales_booster_cart_checkout_progress_bar') ) return;

        echo etheme_sales_booster_cart_checkout_progress_bar(false);

	}

}
