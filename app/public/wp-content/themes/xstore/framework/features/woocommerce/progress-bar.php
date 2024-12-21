<?php
/**
 * Progress Bar feature
 *
 * @package    progress_bar.php
 * @since      9.2.8
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_Sales_Booster_Progress_Bar {
	
	public static $instance = null;
	
	public static $option_name = 'progress_bar';

    public static $should_display = false;

	public $settings = array();

	public function __construct() {
	}

    /**
     * @param null $product
     */
	public function init() {
		if ( !class_exists('WooCommerce')) return;
		if ( !get_option('xstore_sales_booster_settings_'.self::$option_name) ) return;

        self::$should_display = true;

//        add_action('wp', array($this, 'add_actions'));
        $this->set_settings();
        add_action('etheme_after_mini_cart_footer', array($this, 'output'));
        add_action('etheme_elementor_header_off_canvas_footer_after', function ($widget_name) {
            if ( $widget_name == 'theme-etheme_cart')
                echo do_shortcode('[etheme_sales_booster_progress_bar force_load_assets="yes"]');
        });
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));
	}

    public function load_assets() {
        wp_enqueue_script( 'cart_progress_bar');
    }

    /**
     * @param array $custom_settings
     * @since 9.2.8
     */
	public function set_settings($custom_settings = array()) {
		$settings = (array)get_option('xstore_sales_booster_settings', array());

        $default = array(
            'message_text'          => get_theme_mod( 'booster_progress_content_et-desktop', esc_html__( 'Spend {{et_price}} to get free shipping', 'xstore' ) ),
            'process_icon'          => get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ),
            'process_icon_position' => get_theme_mod( 'booster_progress_icon_position_et-desktop', 'before' ) != 'after' ? 'before' : 'after',
            'price'                 => get_theme_mod( 'booster_progress_price_et-desktop', 350 ),
            'message_success_text'  => get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__( 'Congratulations! You\'ve got free shipping.', 'xstore' ) ),
            'success_icon'          => get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ),
            'success_icon_position' => get_theme_mod( 'booster_progress_success_icon_position_et-desktop', 'before' ),
            'progress_bar_cart_ignore_discount' => 'yes',

            'wrapper_classes' => array('woocommerce-mini-cart__footer'),
            'force_load_assets' => false
		);
		
		$local_settings = $default;
		
		if (count($settings) && isset($settings[self::$option_name])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
		}

        $this->settings = wp_parse_args( $custom_settings, $local_settings );
        $this->settings = wp_parse_args( $custom_settings, $this->settings );
	}

    /**
     * Output the content of payments.
     * @since 8.3.9
     */
    public function output() {
        if ( !self::$should_display ) return;
        if ( !apply_filters('etheme_sales_booster_progress_bar', true) ) return;
        $settings = apply_filters('et_'.self::$option_name.'_settings', $this->settings);
        // in case it was on different hook added where not refreshing by woocomerce ajax
        // wp_enqueue_script( 'cart_progress_bar');

        if ( $settings['force_load_assets'] )
            etheme_enqueue_style( 'sale-booster-cart-checkout-progress-bar', true );

        $amount = '';
        if ( ! wc_tax_enabled() ) {
            $amount = WC()->cart->cart_contents_total;
        } else {
//			$amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
            $amount = WC()->cart->get_displayed_subtotal();
        }

        if ($settings['progress_bar_cart_ignore_discount'] == 'yes'){
            $amount += WC()->cart->get_discount_total();
        }

        $amount = apply_filters('et_progress_bar_amount', $amount);

        if ( class_exists('WCPay\MultiCurrency\MultiCurrency') ) {
//            $amount = WCPay\MultiCurrency\MultiCurrency::instance()->get_price($amount, 'exchange_rate');
            $settings['price'] = WCPay\MultiCurrency\MultiCurrency::instance()->get_price($settings['price'], 'exchange_rate');
        }

        $settings['price_diff'] = $settings['price'] - $amount;
        $settings['price_diff'] = $settings['price_diff'] > 0 ? $settings['price_diff'] : 0;
        $settings['cart_progress_bar_content'] = '<span class="et-cart-progress-amount" data-amount="'.$settings['price'].'" data-currency="' . get_woocommerce_currency_symbol() . '">'.wc_price($settings['price_diff']).'</span>';

        $percent_sold = ($amount/$settings['price'])*100;
        $finished = false;
        if ( $amount >= $settings['price'] )
            $finished = true;
        ?>
        <div class="<?php echo implode(' ', $settings['wrapper_classes']); ?> et-cart-progress flex justify-content-start align-items-center" data-percent-sold="<?php if ( $finished ) : echo '100'; else: echo (int)number_format($percent_sold, 3); endif; ?>">
            <?php
            $settings['process_content'] = '<span>' . str_replace(array('{{et_price}}'), array($settings['cart_progress_bar_content']), $settings['message_text']) . '</span>';
            if ( $settings['process_icon'] != 'none') {
                if ( $settings['process_icon_position'] == 'before')
                    $settings['process_content'] = '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $settings['process_icon']).'"></span>'. $settings['process_content'];
                else
                    $settings['process_content'] .= '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $settings['process_icon']).'"></span>';
            }
            echo '<span class="et-cart-in-progress">' . $settings['process_content'] . '</span>';
            ?>

            <?php
            $settings['process_content_success'] = '<span>'.$settings['message_success_text'].'</span>';
            if ( $settings['success_icon'] != 'none') {
                if ( $settings['success_icon_position'] == 'before')
                    $settings['process_content_success'] = '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $settings['success_icon']).'"></span>'. $settings['process_content_success'];
                else
                    $settings['process_content_success'] .= '<span class="et_b-icon et-icon '.str_replace('et_icon-', 'et-', $settings['success_icon']).'"></span>';
            }
            echo '<span class="et-cart-progress-success">' . $settings['process_content_success'] . '</span>';
            ?>

            <progress class="et_cart-progress-bar" max="100" value="<?php if ( $finished ) : echo '100'; else: echo (int)number_format($percent_sold, 3); endif; ?>"></progress>
        </div>
        <?php
    }

    /**
     * Outputs the content of payments with parsing for custom params from shortcode attributes.
     * @param array $atts
     * @return false|string
     * @since 8.3.9
     */
	public function shortcode_output($atts=array()) {
        $atts = is_array($atts) ? $atts : array();

	    if ( count($this->settings) < 1)
            $this->set_settings();

        $this->settings = wp_parse_args($atts, $this->settings);
        ob_start();
            echo '<div class="etheme_sales_booster_progress_bar_shortcode">';
                $this->output();
            echo '</div>';
        return ob_get_clean();
	}
	
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  8.3.9
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
	
}