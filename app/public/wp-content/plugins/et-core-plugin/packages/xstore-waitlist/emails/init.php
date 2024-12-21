<?php

namespace XStoreCore\Modules\WooCommerce;

/**
 * Class XStore_Waitlist_Emails
 */
class XStore_Waitlist_Emails {
    /**
     * Custom_WC_Email constructor.
     */
    public function __construct() {

        // Filtering the emails and adding our own email.
        add_filter( 'woocommerce_email_classes', array( $this, 'register_email' ), 90, 1 );
        add_filter( 'woocommerce_email_actions', array( $this, 'email_actions' ) );

        add_filter( 'woocommerce_email_styles', array($this, 'email_styles'), 10, 2 );

        // Absolute path to the plugin folder.
        define( 'XSTORE_WAITLIST_WC_EMAIL_PATH', plugin_dir_path( __FILE__ ) );

        add_filter( 'viwec_register_replace_shortcode', array ($this, 'replace_shortcode' ), 10, 3 );
        add_filter( 'viwec_register_email_type', array( $this, 'register_email_type_for_builder' ) );
//        add_filter('viwec_accept_email_type', array($this, 'register_email_type_for_builder'));
        add_filter('viwec_admin_email_types', array( $this, 'register_admin_email_type_for_builder' ) );
        add_filter( 'viwec_live_edit_shortcodes', array( $this, 'register_render_preview_shortcode' ), 20 );
        add_filter( 'viwec_register_preview_shortcode', array( $this, 'register_render_preview_shortcode' ), 20 );
    }

    /**
     * @param array $emails
     *
     * @return array
     */
    public function register_email( $emails ) {

        require_once 'class-customer-notify.php';

        $emails['XStore_Waitlist_Customer_Notify'] = new XStore_Waitlist_Customer_Notify();

        require_once 'class-customer-notify-new-request.php';

        $emails['XStore_Waitlist_Customer_Notify_New_Request'] = new XStore_Waitlist_Customer_Notify_New_Request();

        require_once 'class-admin-notify.php';

        $emails['XStore_Waitlist_Admin_Notify'] = new XStore_Waitlist_Admin_Notify();

        return $emails;
    }

    /**
     * Registers custom emails actions.
     *
     * @param  array  $actions
     * @return array
     */
    public function email_actions( $actions ) {

        $actions[] = 'xstore_waitlist_new_customer_request';
        $actions[] = 'woocommerce_variation_set_stock_status';
        $actions[] = 'woocommerce_product_set_stock_status';

        return $actions;
    }

    public function email_styles($styles, $email) {
        if ( in_array($email->id, array('xstore_waitlist_customer_notify', 'xstore_waitlist_customer_notify_new_request', 'xstore_waitlist_admin_notify'))) {
//            $bg = get_option( 'woocommerce_email_background_color' );
//            $styles .= '#template_header {
//                background-color: '.esc_attr( $bg ).';
//                text-align: center;
//            }';
            $styles .= '#template_header h1 {
                text-align: center;
            }';
//            $styles .= '#template_footer {
//                display: none;
//            }';
            $styles .= 'hr {
                outline: none;
                box-shadow: none;
                text-shadow: none;
                border-style: solid;
                border-top: none;
                border-color: #e1e1e1;
            }';
        }
        return $styles;
    }

    public function register_email_type_for_builder( $emails ) {
        $emails['xstore_waitlist_customer_notify'] = [
            'name' => __( 'Waitlist "Product in stock"', 'xstore-core' ),
            'hide_rules' => [ 'min_order', 'max_order' ]
        ];
        $emails['xstore_waitlist_customer_notify_new_request'] = [
            'name' => __( 'Waitlist request', 'xstore-core' ),
            'hide_rules' => [ 'min_order', 'max_order' ]
        ];
        $emails['xstore_waitlist_admin_notify'] = [
            'name' => __( 'Waitlist request', 'xstore-core' ),
            'hide_rules' => [ 'min_order', 'max_order' ]
        ];
        return $emails;
    }

    public function register_admin_email_type_for_builder($emails) {
        $emails[] = 'xstore_waitlist_admin_notify';
        return $emails;
    }

    public function register_render_preview_shortcode( $sc ) {

        $sc['xstore_waitlist_admin_notify'] = [
            '{waitlist_customer_email_address}' => 'Waitlist customer E-mail address',
            '{waitlist_product_name}' => esc_html__('Product name', 'xstore-core'),
            '{waitlist_product_image}' => '<img width="100%" src='. esc_url( VIWEC_IMAGES . 'product.png' ) . ' style="vertical-align: middle;">',
            '{waitlist_product_link}' => home_url('/shop/product/product-title'),
            '{waitlist_product_price}' => wc_price( 25 ),
            '{waitlist_product_sku}' => 'SKU: N/A',
        ];

        return $sc;
    }

    public function replace_shortcode( $shortcodes, $object, $args )
    {
        if (empty($args)) {
            return $shortcodes;
        }

        if (isset($args['email']) && (
            is_a($args['email'], 'XStoreCore\Modules\WooCommerce\XStore_Waitlist_Customer_Notify') ||
            is_a($args['email'], 'XStoreCore\Modules\WooCommerce\XStore_Waitlist_Customer_Notify_New_Request') ||
            is_a($args['email'], 'XStoreCore\Modules\WooCommerce\XStore_Waitlist_Admin_Notify')
            )) {

            $instance = \XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();
            $shortcodes['waitlist_customer_email_address'] = ['{waitlist_customer_email_address}' => (isset($args['customer_email_address']) ? $args['customer_email_address'] : $args['email']->recipient)];
            $shortcodes['waitlist_product_name'] = ['{waitlist_product_name}' => $instance->create_link_tag($args['product_info']['permalink'], $args['product_info']['name'])];
            $shortcodes['waitlist_product_link'] = ['{waitlist_product_link}' => $instance->create_link_tag($args['product_info']['permalink'], $args['product_info']['permalink'])];
            $shortcodes['waitlist_product_image'] = ['{waitlist_product_image}' => $instance->create_link_tag($args['product_info']['permalink'], $args['product_info']['image'])];
            $shortcodes['waitlist_product_sku'] = ['{waitlist_product_sku}' => $args['product_info']['sku']];
            $shortcodes['waitlist_product_price'] = ['{waitlist_product_price}' => $args['product_info']['price']];
        }

        return $shortcodes;
    }
}

new XStore_Waitlist_Emails();