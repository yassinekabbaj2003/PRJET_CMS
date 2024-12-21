<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Checkout;

use ETC\App\Classes\Elementor;

/**
 * Order Review widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Order_Review extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-checkout-etheme_order_review';
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
		return __( 'Checkout Order Review', 'xstore-core' );
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
        return [ 'woocommerce', 'checkout', 'subtotal', 'total', 'price', 'product' ];
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
        return ['etheme-cart-page', 'etheme-checkout-page', 'etheme-elementor-checkout-page'];
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
            'order_review_section_heading',
            [
                'label' => esc_html__( 'Section Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Order Review', 'xstore-core'),
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
            'section_advanced',
            [
                'label' => esc_html__('Advanced', 'xstore-core'),
            ]
        );

        $this->add_control(
            'order_review_product_images',
            [
                'label'           => esc_html__( 'Show images', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'     => esc_html__( 'Enable this option to display product images in the order details information on the checkout and thank you pages.', 'xstore-core' ),
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'order_review_product_quantity',
            [
                'label'           => esc_html__( 'Show quantity', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'     => esc_html__( 'Enable this option to add the ability to change the quantity of product displayed in the order details information on the checkout page.', 'xstore-core' ),
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'order_review_product_quantity_style',
            [
                'label' => __( 'Quantity Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'xstore-core'),
                    'simple' => esc_html__('Simple', 'xstore-core'),
                    'circle' => esc_html__('Circle', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                ],
                'condition' => [
                    'order_review_product_quantity!' => ''
                ]
            ]
        );

        $this->add_control(
            'order_review_product_remove',
            [
                'label'           => esc_html__( 'Show "remove" button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'     => esc_html__( 'Enable this option to display a "Remove" button for products displayed in the order details information on the checkout page.', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'order_review_product_link',
            [
                'label'           => esc_html__( 'Product link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'     => esc_html__( 'Enable this option to give your customers the ability to access the product page by clicking on either the product title or product image for products displayed in the order details information on the checkout page.', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'order_review_product_subtotal',
            [
                'label'           => esc_html__( 'Product subtotal', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'     => esc_html__( 'Enable this option to display a subtotal for each product displayed in the order details information on the checkout page.', 'xstore-core' ),
                'default' => 'yes'
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
            'section_order_review_table_style',
            [
                'label' => esc_html__( 'Table', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'order_review_thead_heading',
            [
                'label' => __( 'Head', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'order_review_thead_typography',
                'selector' => '{{WRAPPER}} table thead th',
            ]
        );

        $this->add_control(
            'order_review_thead_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table thead th' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_review_tbody_heading',
            [
                'label' => __( 'Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'order_review_table_typography',
                'selector' => '{{WRAPPER}} table tbody',
            ]
        );

        $this->add_control(
            'order_review_table_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table tbody' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_review_table_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table tbody .amount' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_review_tfoot_heading',
            [
                'label' => __( 'Footer', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'order_review_tfoot_typography',
                'selector' => '{{WRAPPER}} table tfoot th, {{WRAPPER}} table tfoot td',
            ]
        );

        $this->add_control(
            'order_review_tfoot_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table tfoot th, {{WRAPPER}} table tfoot td' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_review_tfoot_price_color',
            [
                'label' => __( 'Price Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} table tfoot .amount' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_review_table_space',
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

        if ( $settings['design_type'] == 'multistep' )
            wp_enqueue_script( 'etheme_elementor_checkout_page' );

        add_filter('etheme_checkout_order_review_product_details_one_column', '__return_true');
        add_filter('etheme_woocommerce_checkout_order_review_title', array($this, 'modify_section_title'));

        if ( !!$settings['show_heading'] ) {
            add_filter('etheme_checkout_order_review_title', '__return_true');
            add_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            add_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            add_filter('etheme_checkout_order_review_title', '__return_false');
        }

        $order_review_features = array(
            'etheme_checkout_order_review_product_images',
            'etheme_checkout_order_review_product_quantity',
            'etheme_checkout_order_review_product_remove',
            'etheme_checkout_order_review_product_link',
            'etheme_checkout_order_review_product_subtotal'
        );
        foreach ($order_review_features as $order_review_feature) {
            $order_review_feature_key = str_replace('etheme_checkout_', '', $order_review_feature);
            if ( !!$settings[$order_review_feature_key] ) {
                add_filter($order_review_feature, '__return_true');
                if ( $order_review_feature_key == 'order_review_product_quantity' ) {
                    wp_enqueue_script('checkout_product_quantity');
                    add_filter($order_review_feature.'_style', array($this, 'product_quantity_style'));
                    $quantity_input = !in_array($settings['order_review_product_quantity_style'], array('', 'select'));
                    if ( $quantity_input )
                        add_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                }
            }
            else
                add_filter($order_review_feature, '__return_false');
        }

        remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
        add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
        add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );

        ?>
        <div class="etheme-elementor-checkout-widgets-contain">
            <?php
                woocommerce_order_review();
            ?>
        </div>
        <?php

        foreach ($order_review_features as $order_review_feature) {
            $order_review_feature_key = str_replace('etheme_checkout_', '', $order_review_feature);
            if ( !!$settings[$order_review_feature_key] ) {
                remove_filter($order_review_feature, '__return_true');
                if ( $order_review_feature_key == 'order_review_product_quantity' ) {
                    add_filter($order_review_feature.'_style', array($this, 'product_quantity_style'));
                    add_filter($order_review_feature.'_size', array($this, 'product_quantity_size'));
                    $quantity_input = !in_array($settings['order_review_product_quantity_style'], array('', 'select'));
                    if ( $quantity_input )
                        remove_filter('theme_mod_shop_quantity_type', array($this, 'return_input_value'));
                }
            }
            else
                remove_filter($order_review_feature, '__return_false');
        }

        if ( !!$settings['show_heading'] ) {
            remove_filter('etheme_checkout_order_review_title', '__return_true');
            remove_filter('etheme_woocommerce_checkout_title_tag', array($this, 'title_tag'));
            remove_filter('etheme_woocommerce_checkout_title_class', array($this, 'title_class'));
        }
        else {
            remove_filter('etheme_checkout_order_review_title', '__return_false');
        }

        remove_filter('etheme_woocommerce_checkout_order_review_title', array($this, 'modify_section_title'));

        remove_filter('etheme_checkout_order_review_product_details_one_column', '__return_true');
	}


    public function modify_section_title($title) {
        $new_title = $this->get_settings_for_display('order_review_section_heading');
        return !empty($new_title) ? $new_title : $title;
    }

    public function return_input_value() {
        return 'input';
    }

    public function product_quantity_style() {
        $quantity_style = $this->get_settings_for_display('order_review_product_quantity_style');
        if ( !$quantity_style )
            $quantity_style = 'square';
        return $quantity_style;
    }

    public function product_quantity_size() {
        return 'size-sm';
    }

    public function title_tag($html_tag) {
        return $this->get_settings_for_display('heading_html_tag');
    }

    public function title_class($class) {
        return $class . ' style-'. $this->get_settings_for_display('heading_type');
    }

}
