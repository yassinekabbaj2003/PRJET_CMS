<?php

namespace XStoreCore\Modules\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( '\WC_Email' ) ) {
    return;
}

/**
 * Class XStore_Waitlist_Admin_Notify
 */
class XStore_Waitlist_Admin_Notify extends \WC_Email {

    /**
     * Strings to find/replace in subjects/headings.
     *
     * @var array
     */
    protected $placeholders = array();

    /**
     * User email.
     *
     * @var string
     */
    public $customer_email_address;

    public $introduction;

    public $product_info = [];

    /**
     * Create an instance of the class.
     *
     * @access public
     * @return void
     */
    function __construct() {
        // Find/replace.
        $this->placeholders = array_merge(
            array(
                '{site_title}'   => $this->get_blogname(),
                '{site_url}'     => '<a href="'.wp_parse_url( home_url(), PHP_URL_HOST ).'">'.wp_parse_url( home_url(), PHP_URL_HOST ).'</a>',
            ),
            $this->placeholders
        );

        // Email slug we can use to filter other data.
        $this->id          = 'xstore_waitlist_admin_notify';
        $this->title       = $this->format_string(__( 'You have a new waiting list request on {site_title}', 'xstore-core' ));

        $this->description = $this->format_string( sprintf(
	        "Hi,%sYou got a waiting list request from {site_url} for the following:%s",
	        '\r\n',
	        ''
        ));

        // For admin area to let the user know we are sending this email to customers.
        $this->heading     = __( 'New Waitlist Request!', 'xstore-core' );
        // translators: placeholder is {blogname}, a variable that will be substituted when email is sent out
        $this->subject     = __( 'You have a new waiting list request', 'xstore-core' );

        // Template paths.
        $this->template_html  = 'admin-notify.php';
        $this->template_plain = 'plain/admin-notify.php';
        $this->template_base  = XSTORE_WAITLIST_WC_EMAIL_PATH . 'templates/';

        // Other settings.
        $this->recipient = get_option( 'admin_email' );

        if ( get_theme_mod('xstore_waitlist_admin_email', true) ) {
            // Action to which we hook onto to send the email.
            add_action('xstore_waitlist_new_customer_request_notification', array($this, 'trigger'), 10, 3);
        }

        parent::__construct();
    }

    /**
     * Trigger Function that will send this email to the customer.
     *
     * @access public
     * @return void
     */
    function trigger( $product, $customer_email_address, $waitlist_options ) {

        $instance = XStore_Waitlist::get_instance();
        $this->setup_locale();

        $this->customer_email_address = $customer_email_address;
        $this->product_info = array(
            'permalink' => $product->get_permalink(),
            'name' => $product->get_name(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price_html(),
            'image' => $product->get_image()
        );

//        $finds   = array( '{site_url}', '{product_title}', '{product_image}', '{product_sku}', '{product_link}', '{customer_email_address}' );
//        $replace = array( $instance->create_link_tag(home_url( '/' )),
//            $instance->create_link_tag($this->product_info['permalink'], $this->product_info['name']),
//            $instance->create_link_tag($this->product_info['permalink'], $this->product_info['image']),
//            $this->product_info['sku'],
//            $instance->create_link_tag($this->product_info['permalink']),
//            $customer_email_address );
//        $this->introduction    = str_replace( $finds, $replace, $this->description );
//        $this->introduction    = html_entity_decode( $this->introduction );
//        $this->introduction    = str_replace( "\r\n", '<br />', $this->introduction );
        $this->introduction = str_replace(array(
            '{customer_email_address}',
            '{site_url}'
        ),
            array(
                $this->customer_email_address,
                $instance->create_link_tag(home_url( '/' ))
            ), $this->description);

        $this->recipient  = get_option( 'admin_email' );
        $this->object = $product;

        if ( $this->is_enabled() && $this->get_recipient() ) {
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }

        $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @access public
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'email_heading'      => $this->get_heading(),
                'customer_email_address' => $this->customer_email_address,
                'product_info' => $this->product_info,
                'introduction' => $this->introduction,
                'blogname'           => $this->get_blogname(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => true,
                'plain_text'         => false,
                'email'              => $this,
            ), '', $this->template_base
        );
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'email_heading'      => $this->get_heading(),
                'customer_email_address' => $this->customer_email_address,
                'product_info' => $this->product_info,
                'introduction' => $this->introduction,
                'blogname'           => $this->get_blogname(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => true,
                'plain_text'         => true,
                'email'              => $this,
            ), '', $this->template_base
        );
    }

    /**
     * Default content to show below main email content.
     *
     * @since 3.7.0
     * @return string
     */
    public function get_default_additional_content() {
        return __( 'This is an informative email about new waitlist request on your web-site', 'xstore-core' );
    }

}
