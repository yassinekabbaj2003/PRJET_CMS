<?php

namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

/**
 * WooCommerce hook widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class WooCommerce_Hook extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 5.2
     * @access public
     *
     */
    public function get_name()
    {
        return 'woocommerce-etheme_woocommerce_hook';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     * @since 5.2
     * @access public
     *
     */
    public function get_title()
    {
        return __('WooCommerce Hook', 'xstore-core');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 5.2
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eight_theme-elementor-icon et-elementor-wc-hook';
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     * @since 5.2
     * @access public
     *
     */
    public function get_keywords()
    {
        return ['woocommerce', 'shop', 'store', 'php', 'code', 'action', 'category', 'product', 'archive'];
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.2
     * @access public
     *
     */
    public function get_categories()
    {
        return ['woocommerce-elements-archive'];
    }

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
//	public function get_style_depends() {
//		return [ 'etheme-off-canvas', 'etheme-cart-widget' ];
//	}

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
//    public function get_script_depends() {
//        return [ 'etheme_et_wishlist' ];
//    }

    /**
     * Help link.
     *
     * @return string
     * @since 5.2
     *
     */
    public function get_custom_help_url()
    {
        return etheme_documentation_url('110-sales-booster', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'xstore-core'),
            ]
        );

        $hooks_list = array(
            '' => esc_html__('Select', 'xstore-core')
        );
        $wc_hooks_list = array(
            'woocommerce_before_shop_loop',
            'woocommerce_after_shop_loop',
            'woocommerce_before_cart',
            'woocommerce_after_cart_table',
            'woocommerce_cart_collaterals',
            'woocommerce_after_cart',
            'woocommerce_before_checkout_form',
            'woocommerce_checkout_before_customer_details',
            'woocommerce_checkout_after_customer_details',
            'woocommerce_checkout_billing',
            'woocommerce_checkout_shipping',
            'woocommerce_checkout_before_order_review_heading',
            'woocommerce_checkout_before_order_review',
            'woocommerce_checkout_order_review',
            'woocommerce_checkout_after_order_review',
            'woocommerce_after_checkout_form'
        );
        $hooks_list = array_merge($hooks_list, array_combine($wc_hooks_list, $wc_hooks_list));

        $this->add_control(
            'hook', [
                'label' => esc_html__('Hook', 'xstore-core'),
                'description' => esc_html__('Select which PHP hook do you want to display here.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'options' => $hooks_list,
                'default' => '',
            ]
        );

        $this->add_control(
            'clean_actions', [
                'label' => esc_html__('Clean actions', 'xstore-core'),
                'description' => esc_html__('You can clean all default WooCommerce PHP functions hooked to this action.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'condition' => [
                    'hook!' => ''
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
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        if ('yes' === $settings['clean_actions']) {
            switch ($settings['hook']) {
                case 'woocommerce_checkout_billing':
                    remove_action('woocommerce_checkout_billing', array(WC()->checkout(), 'checkout_form_billing'));
                    break;
                case 'woocommerce_checkout_shipping':
                    remove_action('woocommerce_checkout_shipping', array(WC()->checkout(), 'checkout_form_shipping'));
                    break;
                case 'woocommerce_checkout_before_customer_details':
                    remove_action('woocommerce_checkout_before_customer_details', 'wc_get_pay_buttons', 30);
                    break;
                case 'woocommerce_before_checkout_form':
                    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
                    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
                    remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                    break;
                case 'woocommerce_cart_collaterals':
                    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
                    remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
                    break;
                case 'woocommerce_before_cart':
                    remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                    if (class_exists('\Etheme_WooCommerce_Cart_Checkout')) {
                        remove_action('woocommerce_before_cart', array(
                            \Etheme_WooCommerce_Cart_Checkout::get_instance(),
                            'header_steps'
                        ), -10);
                    }
                    break;
                case 'woocommerce_checkout_order_review':
                    remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
                    remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 20);
                    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
                    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 10);
                    break;
            }
        }

        if (in_array($settings['hook'], array('woocommerce_before_checkout_form', 'woocommerce_after_checkout_form')))
            do_action($settings['hook'], WC()->checkout());
        else
            do_action($settings['hook']);

    }
}
