<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Estimated delivery widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Estimated_Delivery extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_sales_booster_estimated_delivery';
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
		return __( 'Estimated Delivery (Sales Booster)', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-sales-booster et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'sale', 'day', 'month', 'week', 'booster', 'product' ];
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
//	public function get_style_depends() {
//		return [ 'etheme-off-canvas', 'etheme-cart-widget' ];
//	}

    /**
     * Get widget dependency.
     *
     * @since 4.1.4
     * @access public
     *
     * @return array Widget dependency.
     */
//    public function get_script_depends() {
//        return [ 'etheme_et_wishlist' ];
//    }
	
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

        $activated_option = get_option('xstore_sales_booster_settings_estimated_delivery');

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
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'estimated_delivery', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Estimated delivery', 'xstore-core') . '</a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'estimated_delivery', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
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
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'estimated_delivery', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
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
            'section_style',
            [
                'label' => esc_html__( 'Text Editor', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
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
                ],
                'selectors_dictionary'  => [
                    'left'          => 'flex-start',
                    'right' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-estimated-delivery' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'vertical_align',
            [
                'label' => esc_html__( 'Vertical Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'xstore-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__( 'Middle', 'xstore-core' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'xstore-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary'  => [
                    'top'          => 'flex-start',
                    'middle'          => 'center',
                    'bottom' => 'flex-end'
                ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-estimated-delivery' => 'display: flex; align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-estimated-delivery' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .sales-booster-estimated-delivery',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-estimated-delivery',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_date',
            [
                'label' => esc_html__( 'Delivery date', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'date_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-estimated-delivery .delivery-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .sales-booster-estimated-delivery .delivery-date',
            ]
        );

        $this->add_responsive_control(
            'date_spacing',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-estimated-delivery .delivery-date' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
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

        if ( !get_option('xstore_sales_booster_settings_estimated_delivery') ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' . sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                    '<a href="' . add_query_arg('etheme-sales-booster-tab', 'estimated_delivery', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Estimated delivery', 'xstore-core') . '</strong></a>',
                    '<a href="' . add_query_arg('etheme-sales-booster-tab', 'estimated_delivery', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Sales Booster', 'xstore-core') . '</strong></a>'));
            }
            return;
        }

		if (!class_exists('Etheme_Sales_Booster_Estimated_Delivery')) return;

        $settings = $this->get_settings_for_display();

        add_filter('etheme_sales_booster_estimated_delivery', '__return_true');
        $estimated_delivery = \Etheme_Sales_Booster_Estimated_Delivery::get_instance();
        $estimated_delivery->init($product);
        $estimated_delivery->args['in_quick_view'] = true;
        $estimated_delivery->add_actions();
        $original_tag = $estimated_delivery->args['tag'];
        $estimated_delivery->args['tag'] = $settings['html_wrapper_tag'];
        $estimated_delivery->output();
        $estimated_delivery->args['tag'] = $original_tag;
        remove_filter('etheme_sales_booster_estimated_delivery', '__return_true');
	}

}
