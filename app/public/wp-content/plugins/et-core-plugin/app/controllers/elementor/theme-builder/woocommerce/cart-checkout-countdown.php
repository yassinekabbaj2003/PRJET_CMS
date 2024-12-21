<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce;

use ETC\App\Classes\Elementor;

/**
 * Cart/Checkout countdown widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart_Checkout_Countdown extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-etheme_sales_cart_checkout_countdown';
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
		return __( 'Cart/Checkout Countdown (SB)', 'xstore-core' );
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
        return [ 'count', 'percent', 'number', 'date', 'time', 'sale', 'evergreen', 'woocommerce', 'booster' ];
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
        return [ 'etheme-sale-booster-cart-checkout-countdown' ];
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
        return [ 'cart_checkout_countdown' ];
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
	    $activated_option = get_option('xstore_sales_booster_settings_cart_checkout_countdown');

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
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_countdown', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Cart / Checkout Countdown', 'xstore-core') . '</a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_countdown', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
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
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_countdown', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank">' . esc_html__('Sales Booster', 'xstore-core') . '</a>'),
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
                    'right'          => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sales-booster-cart-countdown' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .sales-booster-cart-countdown' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .sales-booster-cart-countdown',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .sales-booster-cart-countdown',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_time',
            [
                'label' => esc_html__( 'Time', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'time_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cart-countdown-time' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'time_typography',
                'selector' => '{{WRAPPER}} .cart-countdown-time',
            ]
        );

        $this->add_responsive_control(
            'time_spacing',
            [
                'label' => __( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cart-countdown-time' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
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

        if ( !get_option( 'xstore_sales_booster_settings_cart_checkout_countdown' ) ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' . sprintf(esc_html__('To use this widget, please, activate %1s within the %2s section.', 'xstore-core'),
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_countdown', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Cart / Checkout Countdown', 'xstore-core') . '</strong></a>',
                        '<a href="' . add_query_arg('etheme-sales-booster-tab', 'cart_checkout_countdown', admin_url('admin.php?page=et-panel-sales-booster')) . '" target="_blank"><strong>' . esc_html__('Sales Booster', 'xstore-core') . '</strong></a>'));
            }
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( $edit_mode )
            WC()->session->set( 'etheme_last_added_cart_time', strtotime( 'now', current_time( 'timestamp' ) ) );

        $countdown = \Etheme_WooCommerce_Cart_Checkout::get_instance();
        add_filter('et_sales_booster_cart_checkout_countdown_enabled', '__return_true');
        $countdown->sales_booster_countdown_output(array(
            'tag' => $settings['html_wrapper_tag'],
            'force_load_assets' => false
        ));
        remove_filter('et_sales_booster_cart_checkout_countdown_enabled', '__return_true');

        // On render widget from Editor - trigger the init manually.
        if ( $edit_mode ) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    if ( etTheme.cart_checkout_countdown !== undefined )
                        etTheme.cart_checkout_countdown();
                });
            </script>
            <?php
        }

    }

}
