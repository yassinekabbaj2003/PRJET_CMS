<?php
/**
 * Sales booster estimated delivery feature
 *
 * @package    sales_booster_estimated_delivery.php
 * @since      8.3.5
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_Sales_Booster_Estimated_Delivery {

    public static $instance = null;

    public static $option_name = 'estimated_delivery';

    public $args = array(
        'is_local_product' => false,
        'product_id' => null,
        'tab_prefix' => 'et_estimated_delivery',
        'is_admin' => false,
        'in_quick_view' => false,
        'tag' => 'div',
        'spb' => false,
        'should_render' => true
    );

    public $settings = array();

    public function __construct() {
    }

    public function init($product = null) {
        if ( !class_exists('WooCommerce')) return;
        if ( !get_option('xstore_sales_booster_settings_'.self::$option_name) ) return;
//        $this->args['tab_prefix'] = 'et_'.self::$option_name;
        $this->args['product'] = $product;
        if ( $this->args['product'] )
            $this->args['product_id'] = $this->args['product']->get_ID();

        $this->args['is_admin'] = is_admin();

        $this->args['spb'] = !!get_option( 'etheme_single_product_builder', false );
        add_action('wp', array($this, 'add_actions'));

        if ( $this->args['is_admin'] ) {
            add_action( 'woocommerce_product_write_panel_tabs', array($this, 'panel_tab') );
            add_action( 'woocommerce_product_data_panels', array($this, 'panel_data') );
            add_action( 'woocommerce_process_product_meta', array($this, 'save_panel_data') );
        }

        add_filter( 'woocommerce_available_variation', array($this, 'add_custom_field_variation_data') );
    }

    public function add_custom_field_variation_data($variations) {
        if ( count($this->settings)) {
            if ( $this->settings['custom_values_for_statuses'] || $this->settings['custom_values_for_shipping_classes'] ) {
                $product = wc_get_product($variations[ 'variation_id' ]);
                $local_settings = $this->settings;
                $stock_status = $product->get_stock_status();
                if ( $this->settings['custom_values_for_statuses'] && $stock_status && $local_settings['separated_values_for_' . $stock_status]) {
                    $local_settings['days'] = $local_settings['days_' . $stock_status];
                    $local_settings['min_days'] = $local_settings['min_days_' . $stock_status];
                    $local_settings['max_days'] = $local_settings['max_days_' . $stock_status];
                    $variations['_et_estimated_delivery'] = $this->calculated_date($local_settings, false);
                }
                elseif ( $this->settings['custom_values_for_shipping_classes'] ) {
                    $product_shipping_class_id = $product->get_shipping_class_id();
                    $prefix = 'shipping_class_id_';
                    if ($product_shipping_class_id && $local_settings['separated_values_for_' . $prefix . $product_shipping_class_id]) {
                        $local_settings['days'] = $local_settings['days_' . $prefix . $product_shipping_class_id];
                        $local_settings['min_days'] = $local_settings['min_days_' . $prefix . $product_shipping_class_id];
                        $local_settings['max_days'] = $local_settings['max_days_' . $prefix . $product_shipping_class_id];
                    }
                    else {
                        $local_settings['days'] = $local_settings['days_global'];
                        $local_settings['min_days'] = $local_settings['min_days_global'];
                        $local_settings['max_days'] = $local_settings['max_days_global'];
                    }
                    $variations['_et_estimated_delivery'] = $this->calculated_date($local_settings, false);
                }
            }
        }
        return $variations;
    }

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

        $this->set_settings();

        if ( !$this->args['is_local_product']) {
            $action       = 'woocommerce_product_meta_end';
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

    public function set_settings($custom_settings = array()) {
        $settings = (array)get_option('xstore_sales_booster_settings', array());

        $default = array(
            'text_before' => esc_html__('Estimated delivery:', 'xstore'),
            'date_type' => 'days',
            'date_format' => get_option( 'date_format' ),
            'custom_values_for_statuses' => false,
            'custom_values_for_shipping_classes' => false,
            'exclude_dates' => array(),
            'min_days' => 3,
            'max_days' => 5,
            'days' => 3,
            'days_type' => 'number',
            'non_working_days' => array(),
            'only_for' => array(),
            'locale' => false,
            'locale_format' => '%A, %b %d',
            'position' => 'after_summary',
        );
        if ( isset($this->args['spb']) && $this->args['spb'])
            $default['position'] = 'after_excerpt';

        $estimated_delivery_only_for          = function_exists( 'wc_get_product_stock_status_options' ) ? wc_get_product_stock_status_options() : array(
            'instock'     => esc_html__( 'In Stock', 'xstore' ),
            'outofstock'  => esc_html__( 'Out of stock', 'xstore' ),
            'onbackorder' => esc_html__( 'Available on backorder', 'xstore' ),
        );

        $shipping_classes = WC()->shipping()->get_shipping_classes();
        $shipping_classes_rendered = array();
        if ( count($shipping_classes) ) {
            $shipping_classes_url = admin_url('admin.php?page=wc-settings&tab=shipping&section=classes');
            foreach ($shipping_classes as $shipping_class) {
                // save by term_id because WC make is the same way
                $shipping_classes_rendered['shipping_class_id_'.$shipping_class->term_id] = '<a href="'.$shipping_classes_url.'" target="_blank" style="color: currentColor">'.$shipping_class->name.'</a>';
            }
        }

        $estimated_delivery_product_extra_options = array();

        foreach ( array_merge($estimated_delivery_only_for, $shipping_classes_rendered) as $key => $value ) {
            $default['separated_values_for_'.$key] = false;
            $default['min_days_'.$key] = $default['min_days'];
            $default['max_days_'.$key] = $default['max_days'];
            $default['days_'.$key] = $default['days'];
        }

        $local_settings = $default;

        if (count($settings) && isset($settings[self::$option_name])) {
            $local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
        }
        else {
            $local_settings[self::$option_name . '_day_off_saturday'] = 'on';
            $local_settings[self::$option_name . '_day_off_sunday'] = 'on';
        }

        // for saving global values that comes from the general settings
        foreach (array('min_days', 'max_days', 'days') as $key) {
            $local_settings[$key . '_global'] = $local_settings[$key];
        }

        foreach (array(
                     'monday',
                     'tuesday',
                     'wednesday',
                     'thursday',
                     'friday',
                     'saturday',
                     'sunday',
                 ) as $day_off ) {
            if ( array_key_exists(self::$option_name.'_day_off_'.$day_off, $local_settings))
                $local_settings['non_working_days'][] = ucfirst($day_off);
        }

        foreach (array_keys(array_merge($estimated_delivery_only_for, $shipping_classes_rendered)) as $estimated_delivery_only_for_item ) {
            if ( $local_settings['separated_values_for_'.$estimated_delivery_only_for_item] && in_array($estimated_delivery_only_for_item, array_keys($estimated_delivery_only_for) ) ) {
                $local_settings['custom_values_for_statuses'] = true;
            }
            if ( isset($local_settings['separated_values_for_'.$estimated_delivery_only_for_item]) && $local_settings['separated_values_for_'.$estimated_delivery_only_for_item] && in_array($estimated_delivery_only_for_item, array_keys($shipping_classes_rendered) ) )
                $local_settings['custom_values_for_shipping_classes'] = true;
            $estimated_delivery_product_extra_options[] = 'min_days_'.$estimated_delivery_only_for_item;
            $estimated_delivery_product_extra_options[] = 'max_days_'.$estimated_delivery_only_for_item;
            $estimated_delivery_product_extra_options[] = 'days_'.$estimated_delivery_only_for_item;
            if ( array_key_exists(self::$option_name.'_only_for_'.$estimated_delivery_only_for_item, $local_settings)) {
                $local_settings['only_for'][] = $estimated_delivery_only_for_item;
                unset($local_settings[self::$option_name.'_only_for_'.$estimated_delivery_only_for_item]);
            }
        }

        // single product page backend custom options
        $product_custom_options = array();
        foreach ( array_merge(array('text_before', 'min_days', 'max_days', 'days' ), $estimated_delivery_product_extra_options) as $product_custom_option_key ) {
            $option_value = get_post_meta( $this->args['product_id'], $this->args['tab_prefix'].'_'.$product_custom_option_key, true );
            if ( $option_value )
                $product_custom_options[$product_custom_option_key] = $option_value;

            if ( !$local_settings['custom_values_for_statuses'] && !$local_settings['custom_values_for_shipping_classes'] && in_array($product_custom_option_key, $estimated_delivery_product_extra_options) && isset($product_custom_options[$product_custom_option_key])) {
                unset($product_custom_options[$product_custom_option_key]);
            }
        }

        $this->settings = wp_parse_args( $product_custom_options, $local_settings );
        $this->settings = wp_parse_args( $custom_settings, $this->settings );

        if ( isset($this->args['product']) && $this->args['product'] && ($this->args['product']->is_downloadable() || $this->args['product']->is_virtual()) ) {
            $this->args['should_render'] = false;
        }

        if ( (!$this->args['is_admin'] || $this->args['in_quick_view']) && isset($this->args['product']) && $this->args['product'] ) {
            if ( count( $this->settings['only_for'] ) ) {
                $this->args['should_render'] = false;
                $this->args['product_stock_status'] = $this->args['product']->get_stock_status();
                foreach (array_keys($estimated_delivery_only_for) as $estimated_delivery_only_for_item) {
                    if (($this->args['product_stock_status'] == $estimated_delivery_only_for_item) && in_array($estimated_delivery_only_for_item, $this->settings['only_for'])) {
                        $this->args['should_render'] = true;
                    }
                }
            }

            if ( $this->args['should_render'] ) {
                $this->args['product_stock_status'] = isset($this->args['product_stock_status']) ? $this->args['product_stock_status'] : $this->args['product']->get_stock_status();
                $this->args['product_shipping_class_id'] = $this->args['product']->get_shipping_class_id();
                $locally_set_by_status = false; // because by status is prioritized
                foreach (array_keys(array_merge($estimated_delivery_only_for, $shipping_classes_rendered)) as $status) {
                    if ($this->settings['separated_values_for_' . $status] ) {
                        if ( in_array($status, array_keys($estimated_delivery_only_for)) && $this->args['product_stock_status'] == $status) {
                            $locally_set_by_status = true;
                            foreach (array('min_days', 'max_days', 'days') as $key) {
                                $this->settings[$key] = $this->settings[$key . '_' . $status];
                            }
                        }
                        elseif ( !$locally_set_by_status && in_array($status, array_keys($shipping_classes_rendered)) && $this->args['product_shipping_class_id'] == str_replace('shipping_class_id_', '', $status) ) {
                            foreach (array('min_days', 'max_days', 'days') as $key) {
                                $this->settings[$key] = $this->settings[$key . '_' . $status];
                            }
                        }
                    }
                }
            }
        }

        // all days are non-working hmm good shop
        if ( count($local_settings['non_working_days']) >= 7) {
            $this->args['should_render'] = false;
        }
    }

    public function output() {
        if ( !apply_filters('etheme_sales_booster_estimated_delivery', true) ) return;
        if ( !$this->args['should_render'] ) return;

        $settings = apply_filters($this->args['tab_prefix'].'_settings', $this->settings, $this->args);

        ?>
        <<?php echo esc_attr($this->args['tag']) ?> class="sales-booster-estimated-delivery">
        <?php echo (!(empty($settings['text_before'])) ? '<span>'.esc_html($settings['text_before']).'</span>' : '') . $this->calculated_date($settings); ?>
        </<?php echo esc_attr($this->args['tag']) ?>>
        <?php
    }

    public function shortcode_output($atts=array()) {
        $atts = is_array($atts) ? $atts : array();

        if ( !$this->args['product_id'] ) {
            if ( is_singular('product') ) {
                $this->args['product'] = wc_get_product();
                $this->args['product_id'] = $this->args['product']->get_ID();
            }
        }
        if ( count($this->settings) < 1)
            $this->set_settings();

        if ( isset($atts['exclude_dates']))
            $atts['exclude_dates'] = explode(', ', $atts['exclude_dates']);
        if ( isset($atts['non_working_days']))
            $atts['non_working_days'] = explode(', ', $atts['non_working_days']);

        $atts['only_for'] = array();

        $this->settings = wp_parse_args($atts, $this->settings);
        if ( !$this->args['should_render'] ) return;
        ob_start();
        $this->output();
        return ob_get_clean();
    }

    public function calculated_date($calculate_settings, $wrap_in = true) {
        global $wp_locale;
        if(!empty ($calculate_settings['non_working_days']) ){
            $replacements = array(
                'Monday' => $wp_locale->get_weekday( 1 ),
                'Tuesday' =>  $wp_locale->get_weekday( 2 ),
                'Wednesday' =>  $wp_locale->get_weekday( 3 ),
                'Thursday' =>  $wp_locale->get_weekday( 4 ),
                'Friday' =>  $wp_locale->get_weekday( 5 ),
                'Saturday' =>  $wp_locale->get_weekday( 6 ),
                'Sunday' =>  $wp_locale->get_weekday( 0 )
            );
            foreach($calculate_settings['non_working_days'] as $key => $value) {
                if( isset($replacements[$value]) ) {
                    $calculate_settings['non_working_days'][$key] = $replacements[$value];
                }
            }
        } else {
            foreach (array(
                         'sunday',
                         'monday',
                         'tuesday',
                         'wednesday',
                         'thursday',
                         'friday',
                         'saturday',
                     ) as $key => $day_off ) {
                if ( array_key_exists(self::$option_name.'_day_off_'.$day_off, $calculate_settings))
                    $calculate_settings['non_working_days'][] = $wp_locale->get_weekday($key);
            }
        }
        $today = strtotime('today');
//		$today = strtotime(get_gmt_from_date( gmdate( 'Y-m-d H:i', strtotime('today') + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ) . ':00'));
        $html = '';
        if ( $calculate_settings['date_type'] == 'range') {
            if ( $calculate_settings['days_type'] == 'number' ) {
                $min_days = $days = $this->calculate_day($today, absint($calculate_settings['min_days']), $calculate_settings['non_working_days']);
                $max_days = $days = $this->calculate_day($today, absint($calculate_settings['max_days']), $calculate_settings['non_working_days']);
                $html .= sprintf(esc_html__('%1s - %2s days', 'xstore'), $min_days, $max_days);
            }
            else
                $html .= $this->calculate_week_day($today, absint($calculate_settings['min_days']), $calculate_settings['non_working_days'] ) . ' - ' . $this->calculate_week_day($today, absint($calculate_settings['max_days']), $calculate_settings['non_working_days'] );
        }
        else {
            if ( $calculate_settings['days_type'] == 'number' ) {
                $days = $this->calculate_day($today, absint($calculate_settings['days']), $calculate_settings['non_working_days']);
                $html .= sprintf(_n( '%s day', '%s days', $days, 'xstore' ), $days);
            }
            else
                $html .= $this->calculate_week_day($today, absint($calculate_settings['days']), $calculate_settings['non_working_days'] );
        }

        $html = str_replace(
            array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
            array(esc_html__('Monday', 'xstore'), esc_html__('Tuesday', 'xstore'), esc_html__('Wednesday', 'xstore'), esc_html__('Thursday', 'xstore'), esc_html__('Friday', 'xstore'), esc_html__('Saturday', 'xstore'), esc_html__('Sunday', 'xstore')),
            $html
        );
        return $wrap_in ? '<span class="delivery-date">'.$html.'</span>' : $html;
    }

    function calculate_day($timestamp, $days, $skipdays = []) {

        // limit to n days
//		if( $days > $this->settings['days'] ){
//			$days = $this->settings['days'];
//		}

        $i = 1;

        while ($days >= $i) {
            $timestamp = strtotime("+1 day", $timestamp);
            if ( (in_array(wp_date("l", $timestamp), $skipdays)) || (in_array(wp_date("Y-m-d", $timestamp), $this->settings['exclude_dates'])) )
            {
                $days++;
            }
            $i++;
        }

        return $days;
    }

    function calculate_week_day($timestamp, $days, $skipdays = []) {

        // limit to n days
//		if( $days > $this->settings['days'] ){
//			$days = $this->settings['days'];
//		}

        $i = 1;

        while ($days >= $i) {
            $timestamp = strtotime("+1 day", $timestamp);
            if ( (in_array(wp_date("l", $timestamp), $skipdays)) || (in_array(wp_date("Y-m-d", $timestamp), $this->settings['exclude_dates'])) )
            {
                $days++;
            }
            $i++;
        }

        if( $this->settings['locale'] ){

            setlocale(LC_TIME, get_locale());

            if( apply_filters( 'xstore/sales_booster/estimated_delivery/utf8_encode', false) ){
                return utf8_encode( strftime($this->settings['locale_format'], $timestamp) );
            }

            return strftime($this->settings['locale_format'], $timestamp);
        }

        return str_replace(
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            array(esc_html__('January', 'xstore'), esc_html__('February', 'xstore'), esc_html__('March', 'xstore'), esc_html__('April', 'xstore'), esc_html__('May', 'xstore'), esc_html__('June', 'xstore'), esc_html__('July', 'xstore'), esc_html__('August', 'xstore'), esc_html__('September', 'xstore'), esc_html__('October', 'xstore'), esc_html__('November', 'xstore'), esc_html__('December', 'xstore')),
            wp_date($this->settings['date_format'], $timestamp)
        );
    }

    public function panel_tab() {
        ?>
        <li class="<?php echo esc_attr($this->args['tab_prefix']); ?>_options <?php echo esc_attr($this->args['tab_prefix']); ?>_tab hide_if_virtual hide_if_external">
            <a href="#<?php echo esc_attr($this->args['tab_prefix']); ?>_data"><span>
            <?php echo esc_html__( 'Estimated delivery', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="background: var(--et_admin_dark-color, #222); color: #fff; font-size: 0.65em; line-height: 1; padding: 2px 5px; border-radius: 3px; margin: 0; margin-inline-start: 3px;">'.apply_filters('etheme_theme_label', 'XStore').'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

    public function panel_data() {
        global $post, $thepostid, $product_object;
        $this->set_settings();
        $stock_statuses = array();
        if ( $this->settings['custom_values_for_statuses'] ) {
            $stock_statuses = function_exists('wc_get_product_stock_status_options') ? wc_get_product_stock_status_options() : array(
                'instock' => esc_html__('In Stock', 'xstore'),
                'outofstock' => esc_html__('Out of stock', 'xstore'),
                'onbackorder' => esc_html__('Available on backorder', 'xstore'),
            );
        }
        $shipping_classes_rendered = array();
        if ( $this->settings['custom_values_for_shipping_classes'] ) {
            $shipping_classes = WC()->shipping()->get_shipping_classes();
            $product_shipping_class_id = $product_object->get_shipping_class_id( 'edit' );
            if ( count($shipping_classes) ) {
                $shipping_classes_url = admin_url('admin.php?page=wc-settings&tab=shipping&section=classes');
                foreach ($shipping_classes as $shipping_class) {
                    if ( $shipping_class->term_id != $product_shipping_class_id) continue;
                    // save by term_id because WC make is the same way
                    $shipping_classes_rendered['shipping_class_id_'.$shipping_class->term_id] = '<a href="'.$shipping_classes_url.'" target="_blank" style="color: currentColor">'.$shipping_class->name.'</a>';
                }
            }
        }
        ?>
        <div id="<?php echo esc_attr($this->args['tab_prefix']); ?>_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <?php
                    woocommerce_wp_text_input(
                        array(
                            'id'          => $this->args['tab_prefix'].'_text_before',
                            // 'value'       => get_post_meta( $product_object->ID, '_et_gtin', true ),
                            'placeholder'   => $this->settings['text_before'],
                            'label'         => esc_html__( 'Text before', 'xstore' ),
                            'description'   => __( 'Write title for estimated delivery output. By default it inherits from main settings in Sales Booster section', 'xstore' ),
                            'desc_tip'          => true,
                        )
                    );

                    if ( $this->settings['date_type'] == 'range' ) {

                        woocommerce_wp_text_input(
                            array(
                                'id'                => $this->args['tab_prefix'].'_min_days',
                                'label'             => esc_html__( 'Min days', 'xstore' ),
                                'placeholder'       => $this->settings['min_days'],
                                'description'       => esc_html__('Set minimum count of days. In other words: From X days to y days. By default it inherits from main settings in Sales Booster section', 'xstore'),
                                'desc_tip'          => true,
                                'class'             => 'short',
                                'type'              => 'number',
                                'custom_attributes' => array(
                                    'step' => 1,
                                    'min'  => 0,
                                    'max'  => 100,
                                ),
                            )
                        );
                        woocommerce_wp_text_input(
                            array(
                                'id'                => $this->args['tab_prefix'].'_max_days',
                                'label'             => esc_html__( 'Max days', 'xstore' ),
                                'placeholder'       => $this->settings['max_days'],
                                'description'       => esc_html__('Set max count of days. In other words: From x days to Y days. By default it inherits from main settings in Sales Booster section', 'xstore'),
                                'desc_tip'          => true,
                                'class'             => 'short',
                                'type'              => 'number',
                                'custom_attributes' => array(
                                    'step' => 1,
                                    'min'  => 0,
                                    'max'  => 100,
                                ),
                            )
                        );

                        if ( $this->settings['custom_values_for_statuses'] || $this->settings['custom_values_for_shipping_classes'] ) {
                            foreach (array_merge($stock_statuses, $shipping_classes_rendered) as $stock_status_key => $stock_statuses_title) {
                                $is_shipping_class = in_array($stock_status_key, array_keys($shipping_classes_rendered));
                                woocommerce_wp_text_input(
                                    array(
                                        'id' => $this->args['tab_prefix'] . '_min_days_' . $stock_status_key,
                                        'label' => $is_shipping_class ? sprintf(esc_html__('Min days for "%s" [Shipping class]', 'xstore'), $stock_statuses_title) :
                                            sprintf(esc_html__('Min days for "%s" status', 'xstore'), $stock_statuses_title),
                                        'placeholder' => $this->settings['min_days_' . $stock_status_key],
                                        'description' => esc_html__('Set minimum count of days. In other words: From X days to y days. By default it inherits from main settings in Sales Booster section', 'xstore') . '<br/>' .
                                            sprintf(esc_html__('This value will be used only in case this product has %s status', 'xstore'), $stock_statuses_title),
                                        'desc_tip' => true,
                                        'class' => 'short',
                                        'type' => 'number',
                                        'custom_attributes' => array(
                                            'step' => 1,
                                            'min' => 0,
                                            'max'  => 100,
                                        ),
                                    )
                                );
                                woocommerce_wp_text_input(
                                    array(
                                        'id' => $this->args['tab_prefix'] . '_max_days_' . $stock_status_key,
                                        'label' => $is_shipping_class ? sprintf(esc_html__('Max days for "%s" [Shipping class]', 'xstore'), $stock_statuses_title) :
                                            sprintf(esc_html__('Max days for "%s" status', 'xstore'), $stock_statuses_title),
                                        'placeholder' => $this->settings['max_days_' . $stock_status_key],
                                        'description' => esc_html__('Set max count of days. In other words: From x days to Y days. By default it inherits from main settings in Sales Booster section', 'xstore') . '<br/>' .
                                            sprintf(esc_html__('This value will be used only in case this product has %s status', 'xstore'), $stock_statuses_title),
                                        'desc_tip' => true,
                                        'class' => 'short',
                                        'type' => 'number',
                                        'custom_attributes' => array(
                                            'step' => 1,
                                            'min' => 0,
                                            'max'  => 100,
                                        ),
                                    )
                                );
                            }
                        }

                    }

                    else {
                        woocommerce_wp_text_input(
                            array(
                                'id'                => $this->args['tab_prefix'].'_days',
                                'label'             => esc_html__( 'Days', 'xstore' ),
                                'placeholder'       => $this->settings['days'],
                                'description'       => esc_html__('Set count of days. By default it inherits from main settings in Sales Booster section', 'xstore'),
                                'desc_tip'          => true,
                                'class'             => 'short',
                                'type'              => 'number',
                                'custom_attributes' => array(
                                    'step' => 1,
                                    'min'  => 0,
                                    'max'  => 100,
                                ),
                            )
                        );

                        if ( $this->settings['custom_values_for_statuses'] || $this->settings['custom_values_for_shipping_classes'] ) {
                            foreach (array_merge($stock_statuses, $shipping_classes_rendered) as $stock_status_key => $stock_statuses_title) {
                                $is_shipping_class = in_array($stock_status_key, array_keys($shipping_classes_rendered));
                                woocommerce_wp_text_input(
                                    array(
                                        'id' => $this->args['tab_prefix'] . '_days_' . $stock_status_key,
                                        'label' => $is_shipping_class ? sprintf(esc_html__('Days for "%s" [Shipping class]', 'xstore'), $stock_statuses_title) :
                                            sprintf(esc_html__('Days for "%s" status', 'xstore'), $stock_statuses_title),
                                        'placeholder' => $this->settings['days_' . $stock_status_key],
                                        'description' => esc_html__('Set count of days. By default it inherits from main settings in Sales Booster section', 'xstore') . '<br/>' .
                                            sprintf(esc_html__('This value will be used only in case this product has %s status', 'xstore'), $stock_statuses_title),
                                        'desc_tip' => true,
                                        'class' => 'short',
                                        'type' => 'number',
                                        'custom_attributes' => array(
                                            'step' => 1,
                                            'min' => 0,
                                            'max'  => 100,
                                        ),
                                    )
                                );
                            }
                        }
                    }


                    ?>
                </p>
            </div>
        </div>
        <?php
    }

    public function save_panel_data( $post_id ) {
        $text_before = isset( $_POST[$this->args['tab_prefix'].'_text_before'] ) ? $_POST[$this->args['tab_prefix'].'_text_before'] : '';
        if ( $text_before )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_text_before', $text_before );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_text_before' );
        $min_days = isset( $_POST[$this->args['tab_prefix'].'_min_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_min_days'] : '';
        if ( $min_days )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_min_days', $min_days );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_min_days' );
        $max_days = isset( $_POST[$this->args['tab_prefix'].'_max_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_max_days'] : '';
        if ( $max_days )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_max_days', $max_days );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_max_days' );
        $days = isset( $_POST[$this->args['tab_prefix'].'_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_days'] : '';
        if ( $days )
            update_post_meta( $post_id, $this->args['tab_prefix'].'_days', $days );
        else
            delete_post_meta( $post_id, $this->args['tab_prefix'].'_days' );

        $stock_statuses          = function_exists( 'wc_get_product_stock_status_options' ) ? wc_get_product_stock_status_options() : array(
            'instock'     => esc_html__( 'In Stock', 'xstore' ),
            'outofstock'  => esc_html__( 'Out of stock', 'xstore' ),
            'onbackorder' => esc_html__( 'Available on backorder', 'xstore' ),
        );
        $shipping_classes = WC()->shipping()->get_shipping_classes();
        $shipping_classes_rendered = array();
        if ( count($shipping_classes) ) {
            $shipping_classes_url = admin_url('admin.php?page=wc-settings&tab=shipping&section=classes');
            foreach ($shipping_classes as $shipping_class) {
                // save by term_id because WC make is the same way
                $shipping_classes_rendered['shipping_class_id_'.$shipping_class->term_id] = '<a href="'.$shipping_classes_url.'" target="_blank" style="color: currentColor">'.$shipping_class->name.'</a>';
            }
        }
        foreach (array_keys(array_merge($stock_statuses, $shipping_classes_rendered)) as $stock_status_key) {
            $min_days = isset( $_POST[$this->args['tab_prefix'].'_min_days_'.$stock_status_key] ) ? (int)$_POST[$this->args['tab_prefix'].'_min_days_'.$stock_status_key] : '';
            if ( $min_days )
                update_post_meta( $post_id, $this->args['tab_prefix'].'_min_days_'.$stock_status_key, $min_days );
            else
                delete_post_meta( $post_id, $this->args['tab_prefix'].'_min_days_'.$stock_status_key );
            $max_days = isset( $_POST[$this->args['tab_prefix'].'_max_days_'.$stock_status_key] ) ? (int)$_POST[$this->args['tab_prefix'].'_max_days_'.$stock_status_key] : '';
            if ( $max_days )
                update_post_meta( $post_id, $this->args['tab_prefix'].'_max_days_'.$stock_status_key, $max_days );
            else
                delete_post_meta( $post_id, $this->args['tab_prefix'].'_max_days_'.$stock_status_key );
            $days = isset( $_POST[$this->args['tab_prefix'].'_days_'.$stock_status_key] ) ? (int)$_POST[$this->args['tab_prefix'].'_days_'.$stock_status_key] : '';
            if ( $days )
                update_post_meta( $post_id, $this->args['tab_prefix'].'_days_'.$stock_status_key, $days );
            else
                delete_post_meta( $post_id, $this->args['tab_prefix'].'_days_'.$stock_status_key );
        }
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  8.4.5
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }

}