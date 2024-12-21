<?php
namespace ETC\App\Controllers\Elementor\Dynamic_Tags;

use ETC\App\Classes\Elementor;

//class Product_Countdown extends \Elementor\Core\DynamicTags\Tag {
class Product_Countdown extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'etheme-countdown-tag';
    }

    public function get_title() {
        return __( 'Product countdown', 'xstore-core' );
    }

    public function get_group() {
        return \ElementorPro\Modules\Woocommerce\Module::WOOCOMMERCE_GROUP;// 'woocommerce'; // group key is taken from Elementor Pro code
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::DATETIME_CATEGORY ];
    }

    public function get_value( array $options = [] ) {
        global $product;

        $product = Elementor::get_product();

        if ( ! $product ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo Elementor::elementor_frontend_alert_message();
            }

            return '';
        }

        $countdown = $this->get_countdown($product, $product->get_ID());
        add_filter('etheme_countdown_dynamic_extra_params', function ($value) use ($countdown, $product) {
            return array_merge(
                $countdown,
                array(
                    'id' => $product->get_ID(),
                    'should_display' => !empty($countdown['date'])
                ));
        });
        // universal value for built-in Countdown and basic one
        return $countdown['date'];
//        json_encode(array_merge(
//            $countdown,
//            array(
//                'id' => $product->get_ID(),
//                'should_display' => !empty($countdown['date'])
//            )
//        ));
    }

    public function get_countdown($product, $product_id) {
        $return = array(
            'date' => '',
            'wrapper_class' => 'etheme-countdown-wrapper_product',
            'wrapper_attr' => array(),
        );
        $date       = get_post_meta( $product_id, '_sale_price_dates_to', true );
        $date_from  = get_post_meta( $product_id, '_sale_price_dates_from', true );
        $time_start = get_post_meta( $product_id, '_sale_price_time_start', true );
        $time_start = explode( ':', $time_start );
        $time_end   = get_post_meta( $product_id, '_sale_price_time_end', true );
        $time_end   = explode( ':', $time_end );

        $start_hour = ( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00';
        $start_minute = isset( $time_start[1] ) ? $time_start[1] : '00';

        $end_hour = ( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00';
        $end_minute = isset( $time_end[1] ) ? $time_end[1] : '00';

        $has_variation_on_sale = false;

        if( $product && is_object($product) && $product->is_type('variable') ) {
            $variation_ids = $product->get_visible_children();
            foreach( $variation_ids as $variation_id ) {
                if ( $has_variation_on_sale ) break;
                $variation = wc_get_product( $variation_id );

                if ( $variation->is_on_sale() ) {
                    $has_variation_on_sale = true;
                    $date       = get_post_meta( $variation_id, '_sale_price_dates_to', true );
                    $date_from  = get_post_meta( $variation_id, '_sale_price_dates_from', true );
                    $time_start = get_post_meta( $variation_id, '_sale_price_time_start', true );
                    $time_start = explode( ':', $time_start );
                    $time_end   = get_post_meta( $variation_id, '_sale_price_time_end', true );
                    $time_end   = explode( ':', $time_end );

                    $start_hour = ( isset( $time_start[0] ) && $time_start[0] != 'Array' && $time_start[0] > 0 ) ? $time_start[0] : '00';
                    $start_minute = isset( $time_start[1] ) ? $time_start[1] : '00';

                    $end_hour = ( isset( $time_end[0] ) && $time_end[0] != 'Array' && $time_end[0] > 0 ) ? $time_end[0] : '00';
                    $end_minute = isset( $time_end[1] ) ? $time_end[1] : '00';
                }
            }
            if ( $has_variation_on_sale && !\Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                $return['wrapper_class'] .= ' hidden';
                $return['wrapper_attr'] = array(
                    'data-has-reinit' => 'yes'
                );
            }
        }

        if ( !$date ) return $return;

        $now = strtotime('now');

        if ( $date_from ) {
            // place condition here because we have time start/end post_meta in XStore Theme so
            // origin time is 23:59 but user could set another and time could be already out
            $date_from = strtotime( get_gmt_from_date( date( 'Y-m-d', $date_from ) . ' ' . $start_hour . ':' . $start_minute . ':00' ) );
        }

        if ( ($date_from && $now < $date_from) ) return $return;

        // for frontend
//        wp_enqueue_script('etheme_countdown');
//        wp_enqueue_style('etheme-elementor-countdown');

        $date = strtotime( get_gmt_from_date(date('Y-m-d', $date) . ' '. $end_hour.':'.$end_minute.':00') );

        // place condition here because we have time start/end post_meta in XStore Theme so
        // origin time is 23:59 but user could set another and time could be already out
        if ( ($date && $now > $date) ) return $return;

        $return['date'] = date('Y-m-d', $date) . ' '. $end_hour.':'.$end_minute;
        return $return;
    }
}