<?php
/**
 * Sales booster quantity discounts feature
 *
 * @package    sales_booster_quantity_discounts.php
 * @since      9.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_Sales_Booster_Quantity_Discounts {

    public static $instance = null;

    public static $option_name = 'quantity_discounts';

    public $args = array(
        'is_local_product' => false,
        'hidden_default' => false,
        'product_id' => null,
        'should_render' => true
    );

    public $settings = array();

    public $product_settings = array();

    public function __construct() {
    }

    public function init($product = null) {
        if ( !class_exists('WooCommerce')) return;
        if ( !get_option('xstore_sales_booster_settings_'.self::$option_name) ) return;
        $this->args['tab_prefix'] = 'et_'.self::$option_name;
        $this->args['product'] = $product;
        if ( $this->args['product'] )
            $this->args['product_id'] = $this->args['product']->get_ID();

        $this->args['is_admin'] = is_admin();

        $this->args['spb'] = !!get_option( 'etheme_single_product_builder', false );
        add_action('wp', array($this, 'add_actions'));

        /**
         * Fix quantity discount not applied on ajax request.
         */
        add_filter( 'woocommerce_cart_subtotal', array( $this, 'get_cart_subtotal_filter' ), 100, 1 );
        add_filter('woocommerce_get_cart_contents', array($this, 'get_cart_contents'), 20, 1);

        add_filter('woocommerce_cart_get_total', array($this, 'get_cart_total_filter'), 20, 1);
        add_filter('woocommerce_cart_item_price', array($this, 'calculate_price_discounts_filter'), 20, 3);
        add_filter('woocommerce_cart_item_subtotal', array($this, 'calculate_price_discounts_subtotal_filter'), 20, 3);

        add_filter('woocommerce_get_cart_item_from_session', array($this, 'filter_cart_item_from_session'), 20, 3);

        if ( $this->args['is_admin'] ) {
            add_action( 'woocommerce_product_write_panel_tabs', array($this, 'panel_tab') );
            add_action( 'woocommerce_product_data_panels', array($this, 'panel_data') );
            add_action( 'woocommerce_process_product_meta', array($this, 'save_panel_data') );
        }
    }

    /**
     * Add global actions/filters.
     */
    public function add_actions() {
        if( $this->args['product'] ){
            $this->args['is_local_product'] = true;
        }
        elseif ( is_singular('product') ) {
            $this->args['product'] = wc_get_product();
            $this->args['product_id'] = $this->args['product']->get_ID();
        }
        else {
            return;
        }

        if ( $this->args['product']->is_sold_individually() || !$this->args['product']->is_purchasable() || !$this->args['product']->is_in_stock() ) {
            $this->args['should_render'] = false;
            return;
        }

        if ( in_array($this->args['product']->get_type(), array('variable'))) {
            $this->args['hidden_default'] = true;
//            if ( !get_theme_mod( 'enable_swatch', 1 ) ) {
//                $this->args['should_render'] = false;
//                return;
//            }
        }
        // prevent showing quantity discounts on product which does not support ajax add to cart (it means the product is
        // external/grouped/etc)
        elseif ( !$this->args['product']->supports( 'ajax_add_to_cart' ) ) {
            $this->args['should_render'] = false;
            return;
        }

        if ( etheme_is_catalog() ) return;

        $this->set_settings(array(), $this->args['product_id']);

        if ( !$this->args['is_local_product']) {
            $action       = 'woocommerce_after_add_to_cart_button';
            $priority     = 15;
            $apply_action = true;
            if ( $this->args['spb'] ) {
                $action   = 'etheme_woocommerce_template_single_excerpt';
                $priority = 5;
            }
            switch ( $this->settings['position'] ) {
                case 'before_cart_form':
                    $action   = 'woocommerce_before_add_to_cart_form';
                    $priority = 5;
                    break;
                case 'after_cart_form':
                    $action   = 'woocommerce_after_add_to_cart_form';
                    $priority = 15;
                    break;
                case 'before_woocommerce_share':
                    $action   = 'woocommerce_share';
                    $priority = - 999;
                    break;
                case 'after_woocommerce_share':
                    $action   = 'woocommerce_share';
                    $priority = 999;
                    break;
                case 'before_product_meta':
                    $this->args['tag'] = 'span';
                    $action            = 'woocommerce_product_meta_start';
                    $priority          = 5;
                    break;
                case 'after_product_meta':
                    $this->args['tag'] = 'span';
                    $action            = 'woocommerce_product_meta_end';
                    $priority          = 15;
                    break;
                case 'shortcode':
                    $apply_action = false;
                    break;
                default:
                    if ( $this->args['spb'] ) {
                        switch ( $this->settings['position'] ) {
                            case 'before_atc':
                                $action   = 'etheme_woocommerce_template_single_add_to_cart';
                                $priority = 5;
                                break;
                            case 'after_atc':
                                $action   = 'etheme_woocommerce_template_single_add_to_cart';
                                $priority = 15;
                                break;
                            case 'before_excerpt':
                                $action   = 'etheme_woocommerce_template_single_excerpt';
                                $priority = 5;
                                break;
                            case 'after_excerpt':
                                $action   = 'etheme_woocommerce_template_single_excerpt';
                                $priority = 15;
                                break;
                        }
                    }
                    break;
            }

            if ( $apply_action ) {
                add_action( $action, array( $this, 'output' ), $priority );

                if ( has_action('after_page_wrapper', 'etheme_sticky_add_to_cart') ) {
                    add_action( 'after_page_wrapper', function () use ($action, $priority) {
                        remove_action( $action, array( $this, 'output' ), $priority );
                    }, -1 );
                    add_action( 'after_page_wrapper', function () use ($action, $priority) {
                        add_action( $action, array( $this, 'output' ), $priority );
                    }, 2 );
                }
            }
        }
    }

    /**
     * Filter product price which are loaded from session (cache)
     * @param $session_data
     * @param $values
     * @param $key
     * @return mixed
     */
    public function filter_cart_item_from_session($session_data, $values, $key) {
        $product = $session_data['data'];
        if ( isset($session_data['et_discount_price']) ) {
            unset($session_data['et_discount_price']);
        }
        if ( isset($session_data['et_discount_price_done']) ) {
            unset($session_data['et_discount_price_done']);
        }
//        $origin_settings = $this->set_settings();
//        $local_settings = $origin_settings;
//        if ( get_post_meta( $session_data['product_id'], $this->args['tab_prefix'].'_settings', true ) != '' ) {
        $local_settings = $this->set_settings(array(), $session_data['product_id']);
//        }
        if (!count($local_settings))
            return $session_data;

        $type = $local_settings['type']; // percentage/fixed price
        $steps_intervals = $local_settings[$local_settings['rules'].'_ready'];
        $simple_price = $product->get_price();
        switch ($local_settings['rules']) {
            case 'intervals':
                foreach ($steps_intervals as $step_interval) {
                    if ( $session_data['quantity'] >= $step_interval['min'] && (empty($step_interval['max']) || $session_data['quantity'] <= $step_interval['max']) ) {
                        if ( $type == 'percentage')
                            $price = $simple_price - ($simple_price / 100 * $step_interval['percentage']);
                        else
                            $price = $simple_price >= $step_interval['percentage'] ? $simple_price - $step_interval['percentage'] : $simple_price;
                        // in case product price is less than discount - leave origin price for product
                        $session_data['et_discount_price'] = $price;
                        $session_data['et_origin_price'] = $simple_price;
                        $product->set_price($price);
                    }
                }
                break;
            case 'steps':
                foreach ($steps_intervals as $step_interval) {
                    if ( $session_data['quantity'] == $step_interval['every'] ) {
                        if ( $type == 'percentage')
                            $price = $simple_price - ($simple_price / 100 * $step_interval['percentage']);
                        else
                            $price = $simple_price >= $step_interval['percentage'] ? $simple_price - $step_interval['percentage'] : $simple_price;
                        $session_data['et_discount_price'] = $price;
                        $session_data['et_origin_price'] = $simple_price;
                        $product->set_price($price);
                    }
                }
                break;
        }
        $session_data['data'] = $product;
        return $session_data;
    }

    /**
     * Filter global total with products have discounts
     * @param $total
     * @return float|int
     */
    public function get_cart_total_filter($total) {
        $new_total = $total;
        $items        = WC()->cart->get_cart_contents();

        foreach ( $items as $item => $values ) {
            /**
             * @var \WC_Product $product_obj
             */
            $quantity     = $values['quantity'];
            if ( isset($values['et_discount_price']))
                $new_total -= ($values['data']->get_price() - $values['et_discount_price']) * $quantity;
//            else
//                $new_total += $values['data']->get_price() * $quantity;
        }

        return $new_total;
    }

    /**
     * Filter global subtotal with products have discounts
     * @param $subtotal
     * @return mixed
     */
    public function get_cart_subtotal_filter( $subtotal ) {
        $new_subtotal = 0;
        $items        = WC()->cart->get_cart_contents();
        $had_taxable = false;

        foreach ( $items as $item => $values ) {
            /**
             * @var \WC_Product $product_obj
             */
            $quantity     = $values['quantity'];
            $local_price = $values['data']->get_price();
            if ( isset($values['et_discount_price'])) {
                if ( $local_price > $values['et_discount_price'])
                    $local_price = $values['et_discount_price'];
            }

            if ( $values['data']->is_taxable() ) {
                $had_taxable = true;

                if ( WC()->cart->display_prices_including_tax() ) {
                    $row_price        = wc_get_price_including_tax( $values['data'], array( 'qty' => $values['quantity'], 'price' => $local_price ) );
                    $new_subtotal += $row_price;

                    // if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    //     $new_price .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
                    // }
                    // return $new_price;
                } else {
                    $row_price        = wc_get_price_excluding_tax( $values['data'], array( 'qty' => $values['quantity'], 'price' => $local_price ) );
                    $new_subtotal += $row_price;

                    // if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    //     $new_price .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
                    // }
                    // return $new_price;
                }
            } else {
                $new_subtotal += $local_price * $quantity;
            }
        }
        $subtotal = wc_price( $new_subtotal );
        if ( $had_taxable ) {
            if ( WC()->cart->display_prices_including_tax() ) {
                if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
                }
            }
            else {
                if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
                }
            }
        }

        return $subtotal;
    }

    /**
     * Set product price with discount if all conditions are met
     * @param $contents
     * @return mixed
     */
    public function get_cart_contents($contents) {
//        return $contents;
        $new_contents = $contents;
//        $origin_settings = $this->set_settings();
        foreach ( $contents as $item => $values ) {
            if ( isset($values['et_discount_price_done'])) continue;
//            if ( isset($values['et_discount_price']) ) {
//                unset($values['et_discount_price']);
//            }
//            if ( isset($values['et_discount_price_done']) ) {
//                unset($values['et_discount_price_done']);
//            }
//            $local_settings = $origin_settings;
//            if ( get_post_meta( $values['product_id'], $this->args['tab_prefix'].'_settings', true ) == 'custom' ) {
            $local_settings = $this->set_settings(array(), $values['product_id']);
//            }
            if (!count($local_settings)) continue;

            $type = $local_settings['type']; // percentage/fixed price
            $steps_intervals = $local_settings[$local_settings['rules'].'_ready'];

            if (!count($steps_intervals)) continue;

            // Use it to prevent server error "The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later."
            // Method "get_price" causes server error in "WC_Product_Subscription" object
            // Can be removed after solution from "WooCommerce Memberships" plugin - By SkyVerge
            if ( class_exists('WC_Product_Subscription') && $values['data'] instanceof WC_Product_Subscription ) {
                $values['data'] = new WC_product($values['data']->get_ID());
            }

            $simple_price = isset($values['et_origin_price']) ? $values['et_origin_price'] : $values['data']->get_price();
            switch ($local_settings['rules']) {
                case 'intervals':
                    foreach ($steps_intervals as $step_interval) {
                        if ( $values['quantity'] >= $step_interval['min'] && (empty($step_interval['max']) || $values['quantity'] <= $step_interval['max']) ) {
                            if ( $type == 'percentage') {
                                $price = $simple_price - ($simple_price / 100 * $step_interval['percentage']);
                            }
                            else
                                $price = $simple_price >= $step_interval['percentage'] ? $simple_price - $step_interval['percentage'] : $simple_price;
                            $values['et_discount_price'] = $price;
                            $values['et_origin_price'] = $simple_price;
                            $values['et_discount_price_done'] = true;
                        }
                    }
                    break;
                case 'steps':
                    foreach ($steps_intervals as $step_interval) {
                        if ( $values['quantity'] == $step_interval['every'] ) {
                            if ( $type == 'percentage')
                                $price = $simple_price - ($simple_price / 100 * $step_interval['percentage']);
                            else
                                $price = $simple_price >= $step_interval['percentage'] ? $simple_price - $step_interval['percentage'] : $simple_price;
                            $values['et_discount_price'] = $price;
                            $values['et_origin_price'] = $simple_price;
                            $values['et_discount_price_done'] = true;
                        }
                    }
                    break;
            }

            $new_contents[$item] = $values;
        }
        return $new_contents;
    }

    /**
     * Filter product price from discounted if such one exists
     * @param $price
     * @param $cart_item
     * @param $cart_item_key
     * @return mixed
     */
    public function calculate_price_discounts_filter($price, $cart_item, $cart_item_key) {
        $local_price = $cart_item['data']->get_price();
        if ( isset($cart_item['et_discount_price']) ) {
            $local_price = $cart_item['et_discount_price'];
        }
        if ( $cart_item['data']->is_taxable() ) {

            if ( WC()->cart->display_prices_including_tax() ) {
                $row_price        = wc_get_price_including_tax( $cart_item['data'], array( 'price' => $local_price ) );
                $new_price = wc_price( $row_price );

                if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $new_price .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
                }
                return $new_price;
            } else {
                $row_price        = wc_get_price_excluding_tax( $cart_item['data'], array( 'price' => $local_price ) );
                $new_price = wc_price( $row_price );

                if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $new_price .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
                }
                return $new_price;
            }
        } else {
            return wc_price($local_price);
        }
    }

    /**
     * Filter product price with discount if all conditions are passed and quantities are in the ones set in options
     * @param $product_subtotal
     * @param $cart_item
     * @param $cart_item_key
     * @return string
     */
    public function calculate_price_discounts_subtotal_filter($product_subtotal, $cart_item, $cart_item_key) {
        $local_price = $cart_item['data']->get_price();
        if ( isset($cart_item['et_discount_price']) )
            $local_price = $cart_item['et_discount_price'];

        if ( $cart_item['data']->is_taxable() ) {

            if ( WC()->cart->display_prices_including_tax() ) {
                $row_price        = wc_get_price_including_tax( $cart_item['data'], array( 'qty' => $cart_item['quantity'], 'price' => $local_price ) );
                $product_subtotal = wc_price( $row_price );

                if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $product_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
                }
            } else {
                $row_price        = wc_get_price_excluding_tax( $cart_item['data'], array( 'qty' => $cart_item['quantity'], 'price' => $local_price ) );
                $product_subtotal = wc_price( $row_price );

                if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
                    $product_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
                }
            }
        }
        else {
            $row_price        = (float) $local_price * (float) $cart_item['quantity'];
            $product_subtotal = wc_price( $row_price );
        }
        // }
        return $product_subtotal;
    }

    /**
     * Setting settings globally or for specific product if $product_id param is set
     * @param array $custom_settings
     * @param null $product_id
     * @return array|mixed
     */
    public function set_settings($custom_settings = array(), $product_id = null) {

        if ( !$product_id && count($this->settings)) return $this->settings;

        if ( $product_id && isset($this->product_settings[$product_id]) && count($this->product_settings[$product_id])) return $this->product_settings[$product_id];

        $this->settings = array(
            'type' => 'percentage', // fixed
            'rules' => 'intervals', // steps
            'intervals' => [],
            'intervals_ready' => [],
            'steps' => [],
            'steps_ready' => [],
            'title' => esc_html__('Buy more save more!', 'xstore'),
            'title_tag' => 'h4',
            'discount_title_tag' => 'h5',
            'button_icon' => 'et_icon-shopping-bag',
            'custom_icon' => false,
            'icon_position' => 'left',
            'button_text' => esc_html__('Add', 'xstore'),
            'add_quantity' => false,
            'shown_on_quick_view' => false,
            'position' => $this->args['spb'] ? 'after_excerpt' : 'after_summary',
            'force_load_assets' => true
        );

        $local_settings = $this->settings;
        $settings = (array)get_option('xstore_sales_booster_settings', array());

        if (count($settings) && isset($settings[self::$option_name])) {
            $local_settings = wp_parse_args( $settings[ self::$option_name ], $this->settings );
        }

        // single product page backend custom options
        $product_custom_options = array();
        $local_product_id = $product_id ? $product_id : $this->args['product_id'];
        $product_discount_type = get_post_meta( $local_product_id, $this->args['tab_prefix'].'_settings', true );
        if ( $product_discount_type == 'none' ) return $this->settings;

        if ( $product_discount_type == 'custom' ) {
            $rules = get_post_meta( $local_product_id, $this->args['tab_prefix'].'_rules', true );
            $option_value = get_post_meta($local_product_id, $this->args['tab_prefix'] . '_' . $rules, true);
            if ($option_value) {
                $local_settings['rules'] = $rules;
                $local_settings['type'] = get_post_meta( $local_product_id, $this->args['tab_prefix'].'_type', true );
                $steps_intervals_ready = array();
                $local_settings[$rules.'_ready'] = array();
                foreach ($option_value as $option) {
                    $prefixed_rules = $this->args['tab_prefix'] . '_' . $rules;
                    $steps_intervals_ready['percentage'] = $option[$prefixed_rules . '_percentage'];
                    if ( !$steps_intervals_ready['percentage'] ) continue;
                    if ( $rules == 'intervals' ) {
                        $steps_intervals_ready['min'] = $option[$prefixed_rules . '_min'];
                        $steps_intervals_ready['max'] = $option[$prefixed_rules . '_max'];
                    }
                    else {
                        $steps_intervals_ready['every'] = $option[$prefixed_rules . '_every'];
                    }
                    if ( array_filter($steps_intervals_ready ) )
                        $local_settings[$rules.'_ready'][] = $steps_intervals_ready;
                }
            }
        }
        else {

            if ( !isset($local_settings[$local_settings['rules']]) || !$local_settings[$local_settings['rules']] ) return $this->settings;
            $steps_intervals = explode(',', $local_settings[$local_settings['rules']]);
            if ( count($steps_intervals) < 1) return $this->settings;

            $local_settings[$local_settings['rules'].'_ready'] = array();

            foreach ($steps_intervals as $steps_interval) {
                $steps_intervals_ready = array();
                $steps_intervals_ready['percentage'] = $local_settings[$steps_interval . '_percentage'];
                if ( !$steps_intervals_ready['percentage'] ) continue;
                if ( $local_settings['rules'] == 'intervals' ) {
                    $steps_intervals_ready['min'] = $local_settings[$steps_interval . '_min'];
                    $steps_intervals_ready['max'] = $local_settings[$steps_interval . '_max'];
                }
                else {
                    $steps_intervals_ready['every'] = $local_settings[$steps_interval . '_every'];
                }
                if ( array_filter($steps_intervals_ready ) )
                    $local_settings[$local_settings['rules'].'_ready'][] = $steps_intervals_ready;
            }

            // empty all values
            if ( !array_filter($local_settings[$local_settings['rules'].'_ready']))
                return $this->settings;
        }

        $this->settings = wp_parse_args( $product_custom_options, $local_settings );
        $this->settings = wp_parse_args( $custom_settings, $this->settings );

        if ( $product_id )
            $this->product_settings[$product_id] = $this->settings;

        return $this->settings;

    }

    /**
     * Output quantity discounts block if all conditions are passed
     */
    public function output() {
        if ( !apply_filters('etheme_sales_booster_quantity_discounts', true) ) return;
        if ( !$this->args['should_render']) return;
        $settings = apply_filters($this->args['tab_prefix'].'_settings',
            isset($this->product_settings[$this->args['product_id']]) ? $this->product_settings[$this->args['product_id']] : $this->settings,
            $this->args);
        if ( !isset($settings[$settings['rules'].'_ready']) || !count($settings[$settings['rules'].'_ready'])) return;
        if ( !isset($this->args['product_id']) || !$this->args['product_id']) return;

        ob_start();
            $this->calculated_discounts($settings);
        $discounts = ob_get_clean();

        if ( !$discounts) {
            $this->args['hidden_default'] = true;
//            return;
        }

        if ( $settings['force_load_assets'] )
            etheme_enqueue_style( 'sale-booster-quantity-discounts', true ); ?>
        <div class="sales-booster-quantity-discounts-wrapper<?php echo esc_attr($this->args['hidden_default'] ? ' hidden': ''); ?>">
            <?php echo !empty($settings['title']) ? '<'.$settings['title_tag'].' class="quantity-discounts-title">'.$settings['title'].'</'.$settings['title_tag'].'>' : ''; ?>
            <?php echo '<div class="sales-booster-quantity-discounts">' . $discounts . '</div>'; ?>
        </div>
        <?php
    }

    /**
     * Calculated all discount for specific product and output with specific structure
     * @param $settings
     */
    public function calculated_discounts($settings) {
        if ( !count($settings[$settings['rules'].'_ready']) ) return;
        if ( get_query_var('is_mobile', false) ) {
            $settings['button_text'] = '';
            if ( !$settings['custom_icon'] && $settings['button_icon'] == 'none') {
                switch (get_theme_mod('cart_icon_et-desktop', 'type1')) {
                    case 'type1':
                        $settings['button_icon'] = 'et_icon-shopping-bag';
                        break;
                    case 'type2':
                        $settings['button_icon'] = 'et_icon-shopping-basket';
                        break;
                    case 'type4':
                        $settings['button_icon'] = 'et_icon-shopping-cart-2';
                        break;
                    default:
                        $settings['button_icon'] = 'et_icon-shopping-cart';
                        break;
                }
            }
        }

        $stock_quantity = $this->args['product']->get_stock_quantity();

        foreach ($settings[$settings['rules'].'_ready'] as $discount) {
            $intervals = $settings['rules'] == 'intervals';
            if ( ($intervals && !$this->args['product']->has_enough_stock( $discount['min'] )) ||
                ( !$intervals && !$this->args['product']->has_enough_stock( $discount['every'] ) ) ) continue;
            $max_quantity = '';
            if ( $intervals ) {
                if ($discount['max']) {
                    $max_quantity = $discount['max'];
                    if ($stock_quantity && $stock_quantity <= $max_quantity) {
                        $max_quantity = $stock_quantity;
                    }
                }
            }
            else {
                if ($discount['every']) {
                    $max_quantity = $discount['every'];
                    if ($stock_quantity && $stock_quantity <= $max_quantity) {
                        $max_quantity = $stock_quantity;
                    }
                }
            }

            $should_display_qty = $intervals && !($max_quantity && $max_quantity == $discount['min']);

            ?>
            <div class="quantity-discount-item">
                <div class="quantity-discount-info">
                    <?php
                    if ( $settings['rules'] == 'intervals') {
                        if ( $max_quantity && $max_quantity == $discount['min'] ) {
                            echo sprintf(__('%1s Buy %2s items and get %3s OFF %4s %5s on each product %6s', 'xstore'), '<' . $settings['discount_title_tag'] . ' class="quantity-discount-name">', $discount['min'],
                                ($settings['type'] == 'percentage' ? $discount['percentage'] . '%' : wc_price($discount['percentage'])),
                                '</' . $settings['discount_title_tag'] . '>', '<span class="quantity-discount-suggest">', '</span>');
                        }
                        else {
                            echo sprintf(__('%1s Buy from %2s to %3s items and get %4s OFF %5s %6s on each product %7s', 'xstore'), '<' . $settings['discount_title_tag'] . ' class="quantity-discount-name">', $discount['min'], ($max_quantity ? $max_quantity : '&#8734;'),
                                ($settings['type'] == 'percentage' ? $discount['percentage'] . '%' : wc_price($discount['percentage'])),
                                '</' . $settings['discount_title_tag'] . '>', '<span class="quantity-discount-suggest">', '</span>');
                        }
                    }
                    else
                        echo sprintf(__('%1s Buy %2s items get %3s OFF %4s %5s on each product %6s', 'xstore'), '<'.$settings['discount_title_tag'].' class="quantity-discount-name">', $discount['every'],
                            ($settings['type'] == 'percentage' ? $discount['percentage'].'%' : wc_price($discount['percentage']) ), '</'.$settings['discount_title_tag'].'>', '<span class="quantity-discount-suggest">', '</span>');
                    ?>
                </div>
                <div class="quantity-discount-add">
                    <?php
                    if ( $should_display_qty && $settings['rules'] == 'intervals' && $settings['add_quantity'] == 'on') :
                        add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
                        add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
                        add_filter('woocommerce_quantity_input_classes', array($this, 'quantity_input_classes'), 10, 2);
                        woocommerce_quantity_input( array(
                            'input_value' => $discount['min'],
                            'max_value' => $max_quantity,
                            'min_value' => $discount['min'],
                            'quantity_type' => 'input',
                        ) );
                        remove_filter('woocommerce_quantity_input_classes', array($this, 'quantity_input_classes'), 10, 2);
                        remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
                        remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
                    endif;
                    ?>
                    <button type="submit" class="add_to_cart_button ajax_add_to_cart button btn bordered"
                            data-product_id="<?php echo esc_attr($this->args['product_id']); ?>"
                            data-qty="<?php echo 'intervals' == $settings['rules'] ? $discount['min'] : $discount['every']; ?>">
                        <?php
                        if ( $settings['icon_position'] == 'left' && $settings['button_icon'] != 'none' ) {
                            echo '<span class="et_b-icon et-icon' . (!$settings['custom_icon'] ? ' ' . str_replace('et_icon-', 'et-', $settings['button_icon']) : '') . '">'.
                                ($settings['custom_icon'] ? $settings['custom_icon'] : '') .
                                '</span>';
                        }

                        if ( !empty($settings['button_text'])  )
                            echo '<span class="button-text">' . $settings['button_text'] . '</span>';

                        if ( $settings['icon_position'] == 'right' && $settings['button_icon'] != 'none' ) {
                            echo '<span class="et_b-icon et-icon' . (!$settings['custom_icon'] ? ' ' . str_replace('et_icon-', 'et-', $settings['button_icon']) : '') . '">'.
                                ($settings['custom_icon'] ? $settings['custom_icon'] : '') .
                                '</span>';
                        }
                        ?>
                    </button>
                </div>
            </div>
        <?php }
    }

    public function quantity_input_classes($classes, $product) {
        $classes[] = 'quantity-discount-qty-input';
        return $classes;
    }
    /**
     * Shortcode output functions with custom params (if needed)
     * @param array $atts
     * @return false|string
     */
    public function shortcode_output($atts=array()) {
        $atts = is_array($atts) ? $atts : array();

        if ( isset($atts['product_id']) ) {
            if ( wc_get_product($atts['product_id']) ) {
                $this->args['product_id'] = $atts['product_id'];
                if ( count($this->settings) < 1)
                    $this->set_settings(array(), $this->args['product_id']);
            }
            else {
                wc_print_notice( wp_kses_post( sprintf(esc_html__('Product with id "%s" does not exists or was permanently removed.', 'xstore'), $atts['product_id']) ), 'error');
                return;
            }
        }
        else {
            if (count($this->settings) < 1)
                $this->set_settings();
        }

        $this->settings = wp_parse_args($atts, $this->settings);
        ob_start();
        $this->output();
        return ob_get_clean();
    }

    /**
     * Add settings panel list item on Single product editor
     */
    public function panel_tab() {
        ?>
        <li class="<?php echo esc_attr($this->args['tab_prefix']); ?>_options <?php echo esc_attr($this->args['tab_prefix']); ?>_tab hide_if_virtual hide_if_external">
            <a href="#<?php echo esc_attr($this->args['tab_prefix']); ?>_data"><span>
            <?php echo esc_html__( 'Quantity discounts', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="background: var(--et_admin_dark-color, #222); color: #fff; font-size: 0.65em; line-height: 1; padding: 2px 5px; border-radius: 3px; margin: 0; margin-inline-start: 3px;">'.apply_filters('etheme_theme_label', 'XStore').'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

    /**
     * Add settings panel on Single product editor
     */
    public function panel_data() {
        ?>
        <div id="<?php echo esc_attr($this->args['tab_prefix']); ?>_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <?php

                    woocommerce_wp_select([
                            'id'      => $this->args['tab_prefix'].'_settings',
                            'label'   => __( 'Quantity discounts', 'xstore' ),
                            'options' => [
                                'inherit' => __('Inherit', 'xstore'),
                                'none' => __('None', 'xstore'),
                                'custom' => __('Custom', 'xstore'),
                            ],
                        ]
                    );

                    woocommerce_wp_radio([
                            'id'      => $this->args['tab_prefix'].'_type',
                            'label'   => __( 'Discount Type', 'xstore' ),
                            'options' => [
                                'percentage' => esc_html__('By percentage (example: 10% OFF)', 'xstore'),
                                'fixed' => str_replace('{{currency_symbol}}', get_woocommerce_currency_symbol( get_woocommerce_currency() ), esc_html__( 'By fixed price (example: 10{{currency_symbol}} OFF)', 'xstore' )),
                            ],
                        ]
                    );

                    woocommerce_wp_radio([
                        'id'      => $this->args['tab_prefix'].'_rules',
                        'label'   => __( 'Discount Rules', 'xstore' ),
                        'options' => [
                            'intervals' => __('Intervals', 'xstore'),
                            'steps' => __('Steps', 'xstore'),
                        ],
                    ]);

                    $this->sortable_options([
                        'id'      => $this->args['tab_prefix'].'_intervals',
                        'label'   => __( 'Intervals', 'xstore' ),
                        'option_key' => 'intervals'
                    ]);

                    $this->sortable_options([
                        'id'      => $this->args['tab_prefix'].'_steps',
                        'label'   => __( 'Steps', 'xstore' ),
                        'option_key' => 'steps'
                    ]);

                    ?>
                </p>
            </div>
        </div>
        <?php
        wp_add_inline_script( 'etheme_admin_js', "
            jQuery(document).ready(function($) {
                let prod_quantity_discounts_options = $('#woocommerce-product-data').find('#et_quantity_discounts_settings');

                if ( prod_quantity_discounts_options.length ) {
                    setTimeout(function () {
                        prod_quantity_discounts_options.trigger('change');
                        $('input[name=et_quantity_discounts_type]:checked, input[name=et_quantity_discounts_rules]:checked').trigger('change');
                    }, 500);
            
                    $('#woocommerce-product-data')
                        .on(
                            'change',
                            '#et_quantity_discounts_settings',
                            function () {
                                var wrap = $(this).closest('.panel');
                                switch (this.value) {
                                    case 'none':
                                    case 'inherit':
                                        wrap
                                            .find('.et_quantity_discounts_type_field, .et_quantity_discounts_rules_field, .et_quantity_discounts_intervals_field, .et_quantity_discounts_steps_field')
                                            .hide();
                                        setTimeout(function () {
                                            $('.et_quantity_discounts_intervals_field, .et_quantity_discounts_steps_field').hide();
                                        }, 300);
                                        break;
                                    case 'custom':
                                        wrap
                                            .find('.et_quantity_discounts_type_field, .et_quantity_discounts_rules_field, .et_quantity_discounts_intervals_field, .et_quantity_discounts_steps_field')
                                            .show();
                                        $('input[name=et_quantity_discounts_type]:checked, input[name=et_quantity_discounts_rules]:checked').trigger('change');
                                        break;
                                }
                                return false;
                            }
                        )
                        .on(
                            'change',
                            'input[name=et_quantity_discounts_type]',
                            function () {
                                $('.et_quantity_discounts_intervals_field, .et_quantity_discounts_steps_field')
                                    .find('.switch-texts-table-col').text($(this).parent().text());
                                return false;
                            })
                        .on(
                            'change',
                            'input[name=et_quantity_discounts_rules]',
                            function () {
                                switch (this.value) {
                                    case 'steps':
                                        $('.et_quantity_discounts_intervals_field').hide();
                                        break;
                                    case 'intervals':
                                        $('.et_quantity_discounts_steps_field').hide();
                                        break;
                                }
                                $('.et_quantity_discounts_'+this.value+'_field').show();
                                return false;
                            });
                    // $(document).on(
                    //     'change',
                    //     '#woocommerce-product-data .percentage-switcher-input',
                    //     function () {
                    //         if ($('input[name=et_quantity_discounts_type]:checked').val() == 'fixed') return false;
                    //         if ( this.value > 100)
                    //             $(this).val(100);
                    //         return false;
                    //     }
                    // )
                }
            });", 'after' );
    }

    /**
     * Save settings from quantity discount panel on Single product editor
     * @param $post_id
     */
    public function save_panel_data( $post_id ) {
        $quantity_discount_settings = isset( $_POST[$this->args['tab_prefix'].'_settings'] ) ? $_POST[$this->args['tab_prefix'].'_settings'] : '';
        if ( $quantity_discount_settings )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_settings', $quantity_discount_settings );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_settings' );

        $quantity_discount_type = isset( $_POST[$this->args['tab_prefix'].'_type'] ) ? $_POST[$this->args['tab_prefix'].'_type'] : '';
        if ( $quantity_discount_type )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_type', $quantity_discount_type );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_type' );

        $quantity_discount_rules = isset( $_POST[$this->args['tab_prefix'].'_rules'] ) ? $_POST[$this->args['tab_prefix'].'_rules'] : '';
        if ( $quantity_discount_rules )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_rules', $quantity_discount_rules );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_rules' );

        $this->sortable_options_save($post_id, 'intervals');

        $this->sortable_options_save($post_id, 'steps');
    }

    /**
     * New type of field - sortable (adding/removing rows)
     * @param $field
     * @param WC_Data|null $data
     */
    public function sortable_options($field, WC_Data $data = null) {
        global $post;
        $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
        $options = get_post_meta($post->ID, $field['id'], true);
        echo '<fieldset class="form-field et-repeater ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><legend>' . wp_kses_post( $field['label'] ) . '</legend>';
        if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
            echo wc_help_tip( $field['description'] );
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#add-row-<?php echo esc_js($field['id']); ?>').on('click', function() {
                    var row = $('.empty-row.screen-reader-text[data-type="<?php echo esc_js($field['id']); ?>"]').clone(true);
                    row.removeClass('empty-row screen-reader-text');
                    row.attr('data-type', null);
                    row.insertBefore('#repeatable-fieldset-<?php echo esc_js($field['id']); ?> tbody>tr:last');
                    $('#repeatable-fieldset-<?php echo esc_js($field['id']); ?> tbody tr .button').attr('disabled', null);
                    return false;
                });

                $('.remove-row-<?php echo esc_js($field['id']); ?>').on('click', function(e) {
                    e.preventDefault();
                    if ( $(this).attr('disabled') ) {
                        alert('You cannot remove last item');
                        return;
                    }
                    var items = $(this).parents('tbody').find('tr:not(.empty-row)').length;
                    if ( items <= 2 ) {
                        $(this).parents('tbody').find('tr:not(.empty-row) td:last-child .button').attr('disabled', '1');
                    }
                    $(this).parents('tr').remove();
                    return false;
                });
            });
        </script>
        <table id="repeatable-fieldset-<?php echo esc_attr($field['id']); ?>" width="100%">
            <thead>
            <tr>
                <?php if ($field['option_key'] == 'intervals') : ?>
                    <td><?php echo esc_html__('Min', 'xstore'); ?></td>
                    <td><?php echo esc_html__('Max', 'xstore'); ?></td>
                <?php else: ?>
                    <td><?php echo esc_html__('Every X items', 'xstore'); ?></td>
                <?php endif; ?>
                <td class="switch-texts-table-col"><?php echo sprintf(esc_html__('By percentage (example: 10%1s OFF)', 'xstore'), '%'); ?></td>
                <td><?php echo esc_html__('Action', 'xstore'); ?></td>
            </tr>
            </thead>
            <tbody>
            <?php
            $empty_table_columns =  (($field['option_key'] == 'intervals') ? '<td>
                            <input type="number" placeholder="Enter min value" min="1" name="'.$this->args['tab_prefix'].'_'.$field['option_key'].'_min[]" />
                        </td>
                        <td>
                            <input type="number" placeholder="Enter max value" min="1" name="'.$this->args['tab_prefix'].'_'.$field['option_key'].'_max[]" />
                        </td>' : '<td>
                            <input type="number" min="1" name="'.$this->args['tab_prefix'].'_'.$field['option_key'].'_every[]" />
                        </td>') .
                '<td>
                            <input type="number" class="percentage-switcher-input" placeholder="Enter percentage value" min="0" max="" name="'.$this->args['tab_prefix'].'_'.$field['option_key'].'_percentage[]" />
                        </td>
                        <td>
                            <a class="button remove-row-'.$field['id'].'" href="#">'.esc_html__('Remove', 'xstore').'</a>
                        </td>';
            if ($options) :
                foreach ($options as $option) {
                    ?>
                    <tr>
                        <?php if ($field['option_key'] == 'intervals') : ?>
                            <td>
                                <input type="number" placeholder="Enter min value" min="1" name="<?php echo esc_attr($this->args['tab_prefix'] . '_' . $field['option_key']); ?>_min[]" value="<?php if (isset($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_min'])) echo esc_attr($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_min']); ?>" />
                            </td>
                            <td>
                                <input type="number" placeholder="Enter max value" min="1" name="<?php echo esc_attr($this->args['tab_prefix'] . '_' . $field['option_key']); ?>_max[]" value="<?php if (isset($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_max'])) echo esc_attr($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_max']); ?>" />
                            </td>
                        <?php else: ?>
                            <td>
                                <input type="number" min="1" name="<?php echo esc_attr($this->args['tab_prefix'] . '_' . $field['option_key']); ?>_every[]" value="<?php if (isset($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_every'])) echo esc_attr($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_every']); ?>" />
                            </td>
                        <?php endif; ?>
                        <td>
                            <input type="number" class="percentage-switcher-input" placeholder="Enter percentage value" min="0" max="" name="<?php echo esc_attr($this->args['tab_prefix'].'_'.$field['option_key']); ?>_percentage[]" value="<?php if (isset($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_percentage'])) echo esc_attr($option[$this->args['tab_prefix'].'_'.$field['option_key'].'_percentage']); ?>" />
                        </td>
                        <td>
                            <a class="button remove-row-<?php echo esc_attr($field['id']); ?>" href="#"><?php echo esc_html__('Remove', 'xstore'); ?></a>
                        </td>
                    </tr>
                    <?php
                }
            else :
                // show a blank one
                echo '<tr>'.$empty_table_columns.'</tr>';
            endif; ?>

            <!-- empty hidden one for jQuery -->
            <?php echo '<tr class="empty-row screen-reader-text" data-type="'. esc_attr($field['id']). '">'.$empty_table_columns.'</tr>'; ?>

            </tbody>
        </table>
        <p><a id="add-row-<?php echo esc_attr($field['id']); ?>" class="button" href="#"><?php echo esc_html__('Add new', 'xstore'); ?></a></p>
        <?php
        echo '</fieldset>';
    }

    /**
     * Saving action for new sortable field option type
     * @param $post_id
     * @param string $type
     */
    public function sortable_options_save($post_id, $type='intervals') {
        $previous_value = get_post_meta($post_id, $this->args['tab_prefix'].'_'.$type, true);
        $prefix = $this->args['tab_prefix'] . '_' . $type;
        if ( $type == 'intervals' ) {
            $intervals_new = array();
            $interval_min = isset($_POST[$prefix . '_min']) ? $_POST[$prefix . '_min'] : 0;
            $interval_max = isset($_POST[$prefix . '_max']) ? $_POST[$prefix . '_max'] : 0;
            $interval_percentage = isset($_POST[$prefix . '_percentage']) ? $_POST[$prefix . '_percentage'] : 0;
            for ($i = 0; $i < count(max($interval_min, $interval_max, $interval_percentage)); $i++) {
                $_min = stripslashes($interval_min[$i]); // and however you want to sanitize
                $_max = stripslashes($interval_max[$i]); // and however you want to sanitize
                $_percentage = stripslashes($interval_percentage[$i]); // and however you want to sanitize
                if ( $_min || $_max || $_percentage) {
                    $intervals_new[$i][$prefix . '_min'] = $_min;
                    $intervals_new[$i][$prefix . '_max'] = $_max;
                    $intervals_new[$i][$prefix . '_percentage'] = $_percentage;
                }
            }
            if (!empty($intervals_new) && $intervals_new != $previous_value)
                update_post_meta($post_id, $prefix, $intervals_new);
            elseif (empty($intervals_new) && $previous_value)
                delete_post_meta($post_id, $prefix);
        } else {
            $steps_new = array();
            $steps_every = isset($_POST[$prefix . '_every']) ? $_POST[$prefix . '_every'] : 0;
            $interval_percentage = isset($_POST[$prefix . '_percentage']) ? $_POST[$prefix . '_percentage'] : 0;
            for ($i = 0; $i < count(max($steps_every, $interval_percentage)); $i++) {
                $_every = stripslashes($steps_every[$i]); // and however you want to sanitize
                $_percentage = stripslashes($interval_percentage[$i]); // and however you want to sanitize
                if ( $_every || $_percentage ) {
                    $steps_new[$i][$prefix . '_every'] = $_every;
                    $steps_new[$i][$prefix . '_percentage'] = $_percentage;
                }
            }
            if (!empty($steps_new) && $steps_new != $previous_value)
                update_post_meta($post_id, $prefix, $steps_new);
            elseif (empty($steps_new) && $previous_value)
                delete_post_meta($post_id, $prefix);
        }
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.0
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }

}