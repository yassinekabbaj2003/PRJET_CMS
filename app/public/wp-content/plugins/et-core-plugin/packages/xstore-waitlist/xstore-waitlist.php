<?php
/**
 * Own built-in waitlist
 *
 * @package    XStore_Waitlist.php
 * @since      9.2.2
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

namespace XStoreCore\Modules\WooCommerce;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class XStore_Waitlist {

    public static $instance = null;

    public static $is_enabled = false;
    public static $page_id = 0;
    public static $is_multilingual = null;
    public static $show_in_catalog_mode = true;
    public static $should_load_single_assets = false;
    public static $inited = false;
    public static $db_version = '1.0';

    public static $db_version_created = false;
    public static $database_table_exists = false;
    public static $products_ids = [];
    public static $products = [];

    public static $templates_path;
    public static $single_product_builder;

    public static $key = 'xstore-waitlist';
    public static $waitlist_page_id = null;
    public static $waitlist_page = '';

    public static $user_email = '';

    public static $settings = array();

    public static $waitlist_options = array();

    public $admin_notices = array();

    /**
     * Holds product ids stored in cookie
     */
    const COOKIE_KEY = 'xstore_waitlist_ids';

    /**
     * Holds user unique key
     */
    const USER_KEY = 'xstore_waitlist_u';

    /**
     * Initialize all
     */
    public function init() {

        if ( !get_theme_mod('xstore_waitlist', false) ) return;

        $this->create_database_table();

        if ( (bool) self::$db_version_created ) {
            self::$database_table_exists = true;
        }

        require_once dirname( __FILE__ ) . '/emails/init.php';
        
        self::$waitlist_options = array(
            'subscribed_text' => esc_html__('This email was already added for notify when this product be in stock', 'xstore-core', 'xstore-core'),
//            'unsubscribe_error_text'       => esc_html__( 'Your email has not been found in the waiting list for this product. Please, check it and try again or {{join_waitlist_button}}.', 'xstore-core' ),
            'unsubscribe_error_text'       => esc_html__( 'Your email has not been found in the waiting list for this product. Please, check it and try again.', 'xstore-core' ),
            'unsubscribe_success_text'       => esc_html__( 'You have been removed from the waiting list for this product!', 'xstore-core' ),
            'from_email'         => get_option( 'admin_email' ),
//            'from_name'          => get_option( 'blogname' ),
            'recipient'          => get_option( 'admin_email' ),
            'auto_mail'     => get_theme_mod('xstore_waitlist_customer_email', true),
            'auto_subject'       => esc_html__( 'A product you are waiting for is back in stock!', 'xstore-core' ),

            'auto_content'  =>  sprintf(
        "Hi,%s{product_title} is now back in stock on {site_name}.%sYou have been sent this email because your email address was registered in a waiting list for this product.%sIf you would like to purchase {product_title}, please visit the following link:%s{product_link}",
                   '\r\n',
                   '\r\n',
                   '\r\n',
                   '\r\n'
            ),


            'auto_footer'        => esc_html__( 'This email does not guarantee the availability of stock. If the item is out of stock again, you will need to re-add yourself to the waitlist.', 'xstore-core' ),
            'waitlist_admin_mail'    => true,
            'admin_subject'      => esc_html__( 'You have a new waiting list request on {site_name}', 'xstore-core' ),


            'admin_content'      => sprintf(
           "Hi,%sYou got a waiting list request from {site_name} ({site_url}) for the following:%sCustomer email: {customer_email}%sProduct Name: {product_title}, SKU: {product_sku}%sProduct link: {product_link}",
               '\r\n',
               '\r\n',
               '\r\n',
               '\r\n'
            ),

            'waitlist_user_mail'     => true,
            'user_subject'       => esc_html__( 'We have received your waiting list request', 'xstore-core' ),

            'user_content'      => sprintf(
           "Hi,%sWe have received your waiting list request from {site_name} for the following:%sProduct Name: {product_title}, SKU: {product_sku}%sProduct link: {product_link}%s%sWe will send you an email once this item is back in stock.",
               '\r\n',
               '\r\n',
               '\r\n',
               '\r\n',
               '\r\n'
            ),

            'add_intro' => get_theme_mod('xstore_waitlist_popup_intro_add_to_waitlist', esc_html__( 'This product is currently unavailable', 'xstore-core' )),
            'add_placeholder' => esc_html__( 'Your email address...', 'xstore-core' ),
            'add_button_text' => get_theme_mod('xstore_waitlist_popup_label_add_to_waitlist', esc_html__( 'Join waiting list', 'xstore-core' )),
            'add_checkbox_text' => get_theme_mod('xstore_waitlist_popup_checkbox_label_add_to_waitlist', esc_html__( 'I consent to being contacted by the store owner', 'xstore-core' )),

            'remove_intro' => get_theme_mod('xstore_waitlist_popup_intro_remove_waitlist', esc_html__( 'Leaving the Waitlist? Confirm Email Removal', 'xstore-core' )),
            'remove_placeholder' => esc_html__( 'Your email address...', 'xstore-core' ),
            'remove_button_text' => esc_html__( 'Confirm', 'xstore-core' ),
            'remove_checkbox_text' => esc_html__( 'I consent to being contacted by the store owner', 'xstore-core' ),
        );

//		add_action( 'wp_ajax_xstore_add_to_waitlist', array( $this, 'add_to_waitlist_action' ) );
//		add_action( 'wp_ajax_nopriv_xstore_add_to_waitlist', array( $this, 'add_to_waitlist_action' ) );

        add_action( 'wp_ajax_xstore_update_waitlist', array( $this, 'update_waitlist' ) );
        add_action( 'wp_ajax_nopriv_xstore_update_waitlist', array( $this, 'update_waitlist' ) );

        add_action( 'wp_ajax_xstore_update_user_waitlist', array( $this, 'update_user_waitlist' ) );
        add_action( 'wp_ajax_nopriv_xstore_update_user_waitlist', array( $this, 'update_user_waitlist' ) );

        add_action( 'wp_ajax_xstore_get_user_waitlist', array( $this, 'get_user_waitlist' ) );
        add_action( 'wp_ajax_nopriv_xstore_get_user_waitlist', array( $this, 'get_user_waitlist' ) );

//        add_action( 'wp_ajax_xstore_waitlist_page_action', array( $this, 'global_page_actions' ) );
//        add_action( 'wp_ajax_nopriv_xstore_waitlist_page_action', array( $this, 'global_page_actions' ) );

        add_action( 'wp_ajax_xstore_waitlist_fragments', array( $this, 'fragments' ) );
        add_action( 'wp_ajax_nopriv_xstore_waitlist_fragments', array( $this, 'fragments' ) );

        add_action( 'wp_ajax_xstore_get_waitlist_product_info', array( $this, 'get_waitlist_product_info' ) );
        add_action( 'wp_ajax_nopriv_xstore_get_waitlist_product_info', array( $this, 'get_waitlist_product_info' ) );

        add_action( 'wp_ajax_xstore_get_origin_waitlist_product_variation_id', array( $this, 'get_origin_waitlist_product_variation_id' ) );
        add_action( 'wp_ajax_nopriv_xstore_get_origin_waitlist_product_variation_id', array( $this, 'get_origin_waitlist_product_variation_id' ) );

        add_action( 'wp_ajax_xstore_empty_waitlist_page', array( $this, 'get_empty_page_content' ) );
        add_action( 'wp_ajax_nopriv_xstore_empty_waitlist_page', array( $this, 'get_empty_page_content' ) );

//        add_filter('woocommerce_create_pages', array($this, 'add_create_waitlist_page'));
//        add_action('woocommerce_system_status_tool_executed', array ($this, 'set_waitlist_page'), 10, 1);

        // Add a post display state for special WC pages.
        add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

        add_filter('woocommerce_account_menu_items', array($this, 'add_link_to_account_menu'), 10, 2);
        add_filter('woocommerce_get_endpoint_url', array($this, 'add_endpoint_link_to_account_menu'), 10, 4);

//		if ( !class_exists('WooCommerce')) return;
        $this->define_constants();
        $this->is_multilingual();
//		$this->define_settings();
        $this->assets();
        if ( !is_admin() ) {
            $this->actions();
        }
        else {
            add_action( 'admin_menu', array($this, 'register_admin_page'));
//            add_action('init', array($this, 'delete_request_from_db'));
//            add_action('init', array($this, 'manual_notify_customer'));

            add_action( 'admin_notices', array( $this, 'notices' ) );

            add_action('admin_enqueue_scripts', array($this, 'admin_assets'), 1130);

            add_action( 'wp_ajax_xstore_waitlist_manual_notify', array( $this, 'notify_customer' ) );
            add_action( 'wp_ajax_nopriv_xstore_waitlist_manual_notify', array( $this, 'notify_customer' ) );

            add_action( 'wp_ajax_xstore_waitlist_delete_request', array( $this, 'delete_request' ) );
            add_action( 'wp_ajax_nopriv_xstore_waitlist_delete_request', array( $this, 'delete_request' ) );

            add_action( 'woocommerce_product_write_panel_tabs', array($this, 'panel_tab') );
            add_action( 'woocommerce_product_data_panels', array($this, 'panel_data') );
//            add_action( 'woocommerce_process_product_meta', array($this, 'save_panel_data') );
        }
    }

    public function notices() {
        foreach ($this->admin_notices as $admin_notice) { ?>
            <div class="et-message et-error"><?php echo $admin_notice; ?></div>
        <?php }
    }

    public function admin_assets() {
        if ( (isset($_GET['page']) && $_GET['page'] == 'et-waitlists') || get_current_screen()->post_type == 'product' ) {
            $script_key = str_replace('-', '_', self::$key);
            wp_enqueue_script($script_key.'_admin_js', ET_CORE_URL . 'packages/'.self::$key.'/admin/assets/js/script.min.js', array(), false,true);
            $localize_admin_script = array(
                'remove_text' => esc_html__('Are you certain you want to delete this customer from the support notification?', 'xstore-core'),
                'nonce' => wp_create_nonce( $script_key.'_nonce' ),
            );
            wp_localize_script( $script_key.'_admin_js', $script_key.'_config', $localize_admin_script );
        }
    }

    /**
     * Define namespace constants
     */
    public function define_constants() {
        define('XStore_Waitlist_Version', '1.0');
    }

    /**
     * Define default settings of waitlist button
     */
    public function define_settings() {
        self::$settings = array(
            'show_icon' => true,
            'custom_icon' => false,
            'add_action' => true,
            'icon_position' => 'left',
            'add_icon_class' => 'et-bell',
            'remove_icon_class' => 'et-bell-o',
            'add_text' => esc_html(get_theme_mod('xstore_waitlist_label_add_to_waitlist', esc_html__('Notify when available', 'xstore-core'))),
            'remove_text' => esc_html(get_theme_mod('xstore_waitlist_label_browse_waitlist', esc_html__('Remove from Waitlist', 'xstore-core'))),
            'redirect_on_remove' => false,
            'is_single' => false,
            'is_spb' => false, // single product builder
            'only_icon' => false,
            'has_tooltip' => false,
            'force_display' => false
        );
        switch (get_theme_mod('xstore_waitlist_icon', 'type1')) {
            case 'type2':
                self::$settings['add_icon_class'] = 'et-cart-unavailable';
                self::$settings['remove_icon_class'] = 'et-trash'; // false
                break;
            case 'none':
                self::$settings['show_icon'] = false;
                self::$settings['only_icon'] = false;
                break;
            case 'custom':
                $icon_custom = get_theme_mod( 'xstore_waitlist_icon_custom_svg', '' );
                $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
                if ( $icon_custom != '' ) {
                    self::$settings['custom_icon'] = str_replace(array('fill="black"', 'stroke="black"'), array('fill="currentColor"', 'stroke="currentColor"'), etheme_get_svg_icon( $icon_custom ));
                    add_filter('etheme_mobile_panel_element_waitlist_icon', function ($icon_svg, $icon_key) {
                        return 'et_icon-heart' == $icon_key ? self::$settings['custom_icon'] : $icon_svg;
                    }, 10, 2);
                }
                break;
        }
        self::$waitlist_page_id = absint( get_theme_mod('xstore_waitlist_page', '') );
        if ( !self::$waitlist_page_id ) {
            $waitlist_page_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $waitlist_page_ghost_id )
                self::$waitlist_page = add_query_arg('et-waitlist-page', '', get_permalink($waitlist_page_ghost_id));
            else
                self::$waitlist_page = home_url();
        }
        else {
            self::$waitlist_page = get_permalink(self::$waitlist_page_id);
        }
    }

    /**
     * Enqueue style/scripts action
     */
    public function assets() {
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 30 );
    }

    /**
     * Register and enqueue scripts and styles
     */
    public function enqueue_scripts(){
        // Enqueue the script.
        wp_register_script(
            self::$key,
            ET_CORE_URL . 'packages/'.self::$key.'/assets/js/script.min.js',
            array(
                'jquery',
                'etheme',
                'js-cookie'
            ),
            XStore_Waitlist_Version,
            false
        );

        wp_localize_script(
            self::$key,
            str_replace('-', '_', self::$key).'_params',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'confirmQuestion' => esc_html__('Are you sure?', 'xstore-core'),
                'no_active_checkbox' => esc_html__('Please, choose any product by clicking checkbox', 'xstore-core'),
                'no_products_available' => esc_html__('Sorry, there are no products available for this action', 'xstore-core'),
                'is_loggedin' => is_user_logged_in(),
                'waitlist_id' => $this->get_cookie_key(),
                'waitlist_page_url' => self::$waitlist_page,
                'ajax_fragments' => apply_filters('xstore_waitlist_mini_content_ajax', true),
                'is_multilingual' => self::$is_multilingual,
                'view_waitlist' => esc_html__('View waitlist', 'xstore-core'),
                'days_cache' => $this->get_days_cache(),
                'notify_type' => get_theme_mod('xstore_waitlist_notify_type', 'alert_advanced')
            ]
        );

//        wp_enqueue_script( self::$key );

        wp_register_style(
            self::$key.'-page',
            ET_CORE_URL . 'packages/'.self::$key.'/assets/css/waitlist-page'.(defined('ETHEME_MIN_CSS') ? ETHEME_MIN_CSS : '').'.css',
            false,
            XStore_Waitlist_Version,
            'all' );

        if ( (get_query_var('is_single_product', false) || get_query_var('is_single_product_shortcode', false) ) || get_query_var('et_is-quick-view', false) ) {
            wp_enqueue_script( self::$key );
            wp_enqueue_script( 'call_popup' );
        }
        if ( self::$settings['show_icon'] ) {
            $waitlist_icon_code = false;
            $waitlist_icon_deps_styles = '.woocommerce-waitlist .page-heading .title:before, .empty-waitlist-block:before';
            if ( self::$settings['custom_icon'] ) {
                $icon_custom = get_theme_mod( 'xstore_waitlist_icon_custom_svg', '' );
                $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
                if ( $icon_custom ) {
                    $waitlist_icon_code = 'content: \'\'; color: transparent; background: center no-repeat url('.wp_get_attachment_url($icon_custom).'); background-size: contain;';
                    $waitlist_icon_code .= 'display: inline-block;';
                    $waitlist_icon_code .= 'width: 1em; height: 1em;';
                }
            }
            elseif ( self::$settings['add_icon_class'] == 'et-star')
                $waitlist_icon_code = 'content: \'\e90e\';';

            if ( $waitlist_icon_code )
                wp_add_inline_style(self::$key.'-page', $waitlist_icon_deps_styles . '{'. $waitlist_icon_code . '}');
        }
    }

    /**
     * Waitlist output form script
     */
    function output_form_script() {
        if ( !self::$should_load_single_assets ) return;
        wp_enqueue_script( self::$key.'-single' );
        wp_enqueue_script( 'call_popup' );
    }

    /**
     * Check if any plugins for translations are installed
     * @return bool|null
     */
    public function is_multilingual() {
        if ( self::$is_multilingual != null )
            return self::$is_multilingual;

        if ( class_exists('SitePress') ) {
            self::$is_multilingual = true;
            return true;
        }
        if ( function_exists( 'pll_current_language' ) ) {
            self::$is_multilingual = true;
            return true;
        }
        self::$is_multilingual = false;
        return self::$is_multilingual;
    }

    /**
     * Getter of products count already added in waitlist
     * @return int
     */
    public function get_products_count() {
        return count(self::$products_ids);
    }

    /**
     * Get html of waitlist quantity for header builder waitlist/mobile panel waitlist elements
     * @param bool $updated_count
     */
    function header_waitlist_quantity($updated_count = false, $only_count = false) {

        if ( $updated_count ) {
            $count = $updated_count;
        }
        else {
            $count = get_query_var(self::$key.'_products_count', false);

            if (!$count) {
                $count = $this->get_products_count();
                set_query_var(self::$key.'_products_count', $count);
            }
        }
        if ( $only_count ) {
            echo $count;
            return;
        }
        ?>
        <span class="et-waitlist-quantity et-quantity count-<?php echo $count; ?>">
          <?php echo wp_specialchars_decode( $count ); ?>
        </span>
        <?php
    }

    /**
     * Header mini-waitlist content
     */
    public function header_mini_waitlist($args = array()) {
        $args = wp_parse_args($args, array(
            'display_footer_buttons' => true,
            'show_view_page' => true,
            'show_add_all_products' => true
        ));

        if ( apply_filters('xstore_waitlist_mini_content_ajax', true) ) {
            $waitlist_class  = 'et_b_waitlist-dropdown product_list_widget cart_list';
            ?>
            <div class="<?php esc_attr_e( $waitlist_class ); ?>"></div>
            <?php
        }
        else
            $this->header_mini_waitlist_products();

        $this->header_mini_waitlist_footer($args);
    }

    public function header_mini_waitlist_footer($args = array()) {
        if ( !$args['display_footer_buttons'] ) return;
        ?>
        <div class="woocommerce-mini-cart__footer-wrapper">
            <div class="product_list-popup-footer-wrapper" <?php if ( $this->get_products_count() < 1 ) : ?>style="display: none"<?php endif; ?>>
                <p class="buttons mini-cart-buttons">
                <?php if ( $args['show_view_page'] ) : ?>
                    <a href="<?php echo esc_url( self::$waitlist_page ); ?>"
                       class="button btn-view-waitlist wc-forward"><?php _e( 'View Waitlist', 'xstore-core' ); ?></a>
                   <?php endif; ?>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Header mini-waitlist products
     * @param bool $updated_products
     */
    public function header_mini_waitlist_products($updated_products = false) {

        $products = $updated_products ? $updated_products : self::$products;
        $products = array_reverse( $products );

        $limit = get_theme_mod( 'mini-waitlist-items-count', 3 );
        $limit = apply_filters('etheme_mini_waitlist_items_count', $limit);
        $limit = is_numeric( $limit ) ? $limit : 3;

        $add_remove_ajax = true;
        $waitlist_class  = 'et_b_waitlist-dropdown product_list_widget cart_list';
        $sitepress_exists = class_exists('SitePress');
        $polylang_exists = function_exists( 'pll_current_language' );
        if ( class_exists('SitePress') ) {
            global $sitepress;
            $sitepress_lang = $sitepress->get_current_language();
        }
        if ( $polylang_exists ) {
            $polylang_lang = pll_current_language();
        }
        ?>
        <div class="<?php esc_attr_e( $waitlist_class ); ?>">
            <?php if ( ! empty( $products ) ) : ?>

                <?php $is_yith_wcbm_frontend = class_exists('YITH_WCBM_Frontend'); ?>

                <?php
                if ($is_yith_wcbm_frontend) {
                    remove_filter( 'woocommerce_product_get_image', array( \YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
                }
                ?>

                <ul class="cart-widget-products">
                    <?php
                    $i = 0;
                    $trash_bin = defined( 'ETHEME_BASE_URI' ) ? ETHEME_BASE_URI . 'theme/assets/images/trash-bin.gif' : '';
                    $sku_enabled = in_array('mini-waitlist', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart'))) && wc_product_sku_enabled();
                    foreach ( $products as $product_info ) {
                        $i++;
                        if ( $i > $limit ) {
                            break;
                        }

                        $origin_product_id = $product_info['id'];
                        if ( $sitepress_exists ) {
                            $product_info['id'] = apply_filters('wpml_object_id', $product_info['id'], get_post_type($product_info['id']), false, $sitepress_lang);
                            // if product does not have ready translations then use original id of product
                            if ( !$product_info['id'] )
                                $product_info['id'] = $origin_product_id;
                        }
                        elseif ( $polylang_exists ) {
                            $product_info['id'] = PLL()->model->post->get_translation( $product_info['id'], $polylang_lang );
                            // if product does not have ready translations then use original id of product
                            if ( !$product_info['id'] )
                                $product_info['id'] = $origin_product_id;
                        }

                        $post_object = get_post( $product_info['id'] );
                        setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                        $_product = wc_get_product($product_info['id']);

                        if ( ! $_product ) {
                            wp_reset_postdata();
                            continue;
                        }

                        $product_name = $_product->get_title();
                        $thumbnail    = $_product->get_image();
                        ?>
                        <li class="woocommerce-mini-waitlist-item">
                            <?php if ( ! $_product->is_visible() ) : ?>
                                <a class="product-mini-image">
                                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"
                                   class="product-mini-image">
                                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
                            <?php endif; ?>

                            <div class="product-item-right" data-row-id="<?php esc_attr_e( $origin_product_id ); ?>">

                                <<?php echo apply_filters('etheme_woocommerce_mini_waitlist_product_title_tag', 'h4'); ?> class="product-title">
                                    <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo wp_specialchars_decode( $product_name ); ?></a>
                                </<?php echo apply_filters('etheme_woocommerce_mini_waitlist_product_title_tag', 'h4'); ?>>

                                <?php if ( $add_remove_ajax ) : ?>
                                    <a href="<?php echo add_query_arg( 'remove_from_waitlist', $origin_product_id, esc_url( self::$waitlist_page ) ); ?>"
                                       data-id="<?php echo esc_attr($origin_product_id); ?>"
                                       data-email="<?php echo esc_attr($product_info['email']); ?>"
                                       class="remove xstore-miniwaitlist-remove remove_from_waitlist"
                                       title="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"><i
                                                class="et-icon et-delete et-remove-type1"></i><i
                                                class="et-trash-wrap et-remove-type2"><img
                                                    src="<?php echo $trash_bin; ?>"
                                                    alt="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"></i></a>
                                <?php endif; ?>

                                <div class="descr-box">
                                        <div class="product_meta">
                                            <span class="user-email-wrapper">
                                                <?php esc_html_e( 'Email:', 'xstore-core' ); ?>
                                                <span class="user-email"><?php echo esc_html($product_info['email']); ?></span>
                                            </span>
                                            <?php if ( $sku_enabled && $_product->get_sku() ) : ?>
                                                <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore-core' ); ?>
                                                    <span class="sku"><?php echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) ); ?></span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                </div>

                                <?php
                                //                                add_filter('woocommerce_loop_add_to_cart_args', array($this, 'filter_miniwaitlist_add_to_cart_button') );
                                //                                woocommerce_template_loop_add_to_cart();
                                //                                remove_filter('woocommerce_loop_add_to_cart_args', array($this, 'filter_miniwaitlist_add_to_cart_button') );
                                ?>

                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <div class="hidden keeper-all-products-buttons">
                    <span class="screen-reader-text"><?php echo esc_html__('The keeper of all add to cart buttons of products', 'xstore-core'); ?></span>
                    <?php
                    foreach ( $products as $product_info ) {

                        $post_object = get_post($product_info['id']);
                        setup_postdata($GLOBALS['post'] =& $post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                        $_product = wc_get_product($product_info['id']);

                        if (!$_product) {
                            continue;
                        }
                        woocommerce_template_loop_add_to_cart();
                    }
                    ?>
                </div>
                <?php

                if ($is_yith_wcbm_frontend) {
                    add_filter( 'woocommerce_product_get_image', array( \YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
                }

                wp_reset_postdata();
                ?>
            <?php else : ?>
                <p class="empty"><?php esc_html_e( 'No products in the waitlist.', 'xstore-core' ); ?></p>
            <?php endif; ?>
        </div><!-- end product list -->
    <?php }

    /**
     * Fragments for ajax-refresh on adding/removing product in waitlist
     */
    public function fragments() {

        $data = array(
            'fragments' => array()
        );

        ob_start();
        $this->header_waitlist_quantity(isset($_POST['products_count']) ? $_POST['products_count'] : false, true);
        $product_count = ob_get_clean();

        ob_start();
        if ( isset($_POST['products_count'])) {
            $this->header_waitlist_quantity($_POST['products_count']);
        }
        else {
            $this->header_waitlist_quantity(false);
        }
        $data['fragments']['span.et-waitlist-quantity'] = ob_get_clean();

        ob_start();
        if ( isset($_POST['products'])) {
            $this->header_mini_waitlist_products(
                array_map(
                    function ($_product) {
                        return (array)json_decode(stripcslashes($_product));
                    },
                    $_POST['products']));
        }
        else {
            $this->header_mini_waitlist_products(false);
        }
        $data['fragments']['.et_b_waitlist-dropdown'] = ob_get_clean();

        $data = array(
			'fragments' => apply_filters(
				'xstore_waitlist_refresh_fragments',
				$data['fragments'],
				$product_count,
				$data['fragments']['.et_b_waitlist-dropdown']
			),
		);

        wp_send_json( $data );
    }

    /**
     * Getter of product info by product_id param set from ajax params in js
     */
    public function get_waitlist_product_info() {
        if ( !isset($_POST['product_id']) || empty($_POST['product_id']) ) die();
        add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
        $product_id = $_POST['product_id'];
        if ( class_exists('SitePress')) {
            global $sitepress;
            $translated_product_id = apply_filters('wpml_object_id', $product_id, get_post_type($product_id), false, $sitepress->get_current_language());
            // if product has ready translations then use translated id of product
            if ( $translated_product_id )
                $product_id = $translated_product_id;
        }
        elseif ( function_exists( 'pll_current_language' ) ) {
            // get default post id
            $translated_product_id = PLL()->model->post->get_translation( $product_id, pll_current_language() );
            // if product has ready translations then use translated id of product
            if ( $translated_product_id )
                $product_id = $translated_product_id;
        }

        $post_object = get_post($product_id);
        setup_postdata($GLOBALS['post'] =& $post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
        $_product = wc_get_product($product_id);
        if ( !$_product ) {
            wp_reset_postdata();
            die();
        }

        $image = wp_get_attachment_image($_product->get_image_id(), 'woocommerce_thumbnail');
        $product_link = get_permalink($product_id);
        $product_name = $_product->get_name();
        unset($product_id);

        remove_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );

        wp_reset_postdata();
        wp_send_json( array(
            'product_link' => $product_link,
            'product_image' => $image ? $image : wc_placeholder_img(),
            'product_title' => sprintf(__('<a href="%s">%s</a> has been added to the waitlist', 'xstore-core'), $product_link, $product_name ),
        ) );
    }

    /**
     * Getter of product variation original id
     * @return mixed
     */
    public function get_origin_waitlist_product_variation_id() {
        if ( !isset($_POST['variation_id']) || empty($_POST['variation_id']) ) die();
        if ( class_exists('SitePress')) {
            global $sitepress;
            $productIdTranslated = $sitepress->get_original_element_id($_POST['variation_id'], 'post_product_variation');
            wp_send_json( array(
                'variation_id' => $productIdTranslated ? $productIdTranslated : $_POST['variation_id']
            ));
        }
        elseif ( function_exists( 'pll_current_language' ) ) {
            $productIdTranslated = PLL()->model->post->get_translation( $_POST['variation_id'], pll_default_language() );
            wp_send_json( array(
                'variation_id' => $productIdTranslated ? $productIdTranslated : $_POST['variation_id']
            ));
        }
        else die();
    }

    /**
     * Getter of empty page. Used for ajax setters after all products were removed on waitlist page.
     */
    public function get_empty_page_content() {
        ob_start();
        $this->empty_page_template();
        $ob_clean = ob_get_clean();
        wp_send_json( array('page_content' => $ob_clean) );
    }

    /**
     * Create waitlist page based on woocommerce tools.
     * @param $pages
     * @return mixed
     */
    public function add_create_waitlist_page($pages) {
        $waitlist_shortcode = 'xstore_waitlist_page';
        $pages['xstore_waitlist'] = array(
            'name'    => _x( 'xstore-waitlist', 'Page slug', 'xstore-core' ),
            'title'   => _x( 'XStore Waitlist', 'Page title', 'xstore-core' ),
            'content' => '<!-- wp:shortcode -->[' . $waitlist_shortcode . ']<!-- /wp:shortcode -->',
        );
        return $pages;
    }

    /**
     * Set theme mod if not set yet of waitlist id once we are in woocommerce tools and install-page action.
     * @param $tool
     */
//    public function set_waitlist_page($tool) {
//	    if ( $tool['id'] == 'install_pages') {
//            $waitlist_page_id = get_option('woocommerce_xstore_waitlist_page_id');
//            if ( $waitlist_page_id && get_theme_mod('xstore_waitlist_page', '') == '' ) {
//                set_theme_mod('xstore_waitlist_page', $waitlist_page_id);
//            }
//        }
//    }

    /**
     * Add a post display state for special waitlist page in the page list table.
     *
     * @param array   $post_states An array of post display states.
     * @param WP_Post $post        The current post object.
     */
    public function add_display_post_states($post_states, $post) {
        if ( (int)get_theme_mod('xstore_waitlist_page', '') === $post->ID ) {
            $post_states['xstore_page_for_waitlist'] = __( 'Waitlist page', 'xstore-core' );
        }

        return $post_states;
    }

    /**
     * Add waitlist link to account menu.
     * @param $items
     * @param $endpoints
     * @return array|false|int|string
     */
    public function add_link_to_account_menu($items, $endpoints) {
        $add_anywhere = true;
        if ( array_key_exists('customer-logout', $items)) {
            $logout_position = array_search('customer-logout', array_keys($items));
            if ( $logout_position > 1 ) {
                $items = array_slice( $items, 0, $logout_position, true ) +
                    array( 'xstore-waitlist' => esc_html__( 'Waitlist', 'xstore-core' ) ) +
                    array_slice( $items, $logout_position, count( $items ) - $logout_position, true );
                $add_anywhere = false;
            }
        }

        if ( $add_anywhere )
            $items['xstore-waitlist'] = __( 'Waitlist', 'xstore-core' );

        return $items;
    }

    /**
     * Filter xstore-waitlist link in account menu items.
     * @param $url
     * @param $endpoint
     * @param $value
     * @param $permalink
     * @return bool|false|string|void
     */
    public function add_endpoint_link_to_account_menu($url, $endpoint, $value, $permalink) {
        if ( $endpoint == 'xstore-waitlist')
            return self::$waitlist_page;
        return $url;
    }

    public function compatibility_contact_forms() {
        // contact form7 plugin
        add_filter( 'wpcf7_special_mail_tags', function ($output, $name, $html, $mail_tag = null) {
            if ( function_exists('is_user_logged_in') && function_exists('get_current_user_id') ) {
                $waitlist_page_id = absint( get_theme_mod('xstore_waitlist_page', '') );
                if ( !$waitlist_page_id ) {
                    $waitlist_page_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
                    if ( $waitlist_page_ghost_id )
                        $waitlist_page = add_query_arg('et-waitlist-page', '', get_permalink($waitlist_page_ghost_id));
                    else
                        $waitlist_page = home_url();
                }
                else {
                    $waitlist_page = get_permalink($waitlist_page_id);
                }
                // don't remove this because it is used for initialization of current user
                $user_id = apply_filters( 'determine_current_user', false );
                $user = wp_set_current_user( $user_id );

                if ( is_user_logged_in() ) {
                    $user_waitlist_page = add_query_arg('wid', $this->get_user_key(), $waitlist_page);
                    if ('_waitlist_page_url' == $name) {
                        if ($url = $user_waitlist_page) {
                            return $url;
                        } else {
                            return '';
                        }
                    }
                }
                else {
                    $products_ids = $this->get_products()['ids'];
                    $user_waitlist_page = add_query_arg('waitlist_product_ids', implode(',', $products_ids), $waitlist_page);
                    if ('_waitlist_page_url' == $name) {
                        if ($url = $user_waitlist_page) {
                            return $url;
                        } else {
                            return '';
                        }
                    }
                }
            }
            return $output;
        }, 20, 4 );
    }
    /**
     * Main actions of waitlist functionality.
     */
    public function actions() {

        self::$templates_path = plugin_dir_path( __FILE__ ) . '/templates/';
        self::$single_product_builder = get_option( 'etheme_single_product_builder', false );
//        $this->reset_products();
        $this->compatibility_contact_forms();
        // on testing state
//        add_action( 'wp_login', array($this, 'update_ids_after_login'), 10, 2);
        add_action('wp', array($this, 'check_waitlist_items'));
        add_action('wp', function () {

            if ( apply_filters( 'xstore_theme_amp', false ) ) return;


            if ( get_query_var( 'et_is-loggedin', false) ) {
                $current_user = wp_get_current_user();
                self::$user_email = $current_user->user_email;
            }

            if ( !self::$inited ) {
                $this->init_added_products();
            }

            $this->define_settings();

            add_shortcode('xstore_waitlist_page', array($this, 'page_template'));
//            add_shortcode('xstore_waitlist_button', array($this, 'print_button'));
//            delete_user_meta(get_current_user_id(), $this->get_cookie_key());
//            $this->reset_products();
//            $this->test_save_user_waitlist();

//            $single_product_action = false;
//            $single_product_priority = 0;
//            if ( !self::$single_product_builder ) {
//                $position = get_theme_mod('xstore_waitlist_single_product_position', 'stock_message');
//                switch ($position) {
//                    case 'before_cart_form':
//                        $single_product_action = 'woocommerce_before_add_to_cart_form';
//                        $single_product_priority = 5;
//                        break;
//                    case 'after_cart_form':
//                        $single_product_action = 'woocommerce_after_add_to_cart_form';
//                        $single_product_priority = 15;
//                        break;
//                    case 'after_atc':
//                        $single_product_action = 'woocommerce_after_add_to_cart_button';
//                        $single_product_priority = 7; // 10 in for buy now button
//                        break;
//                    case 'stock_message':
//                        $single_product_action = 'replace_stock_message';
//                        break;
//                }
                // if ( $position != 'on_image' && get_query_var('is_mobile', false) ) {
                //     $single_product_action = 'woocommerce_after_add_to_cart_form';
                //     $single_product_priority = 15;
                //     add_action($single_product_action, function () {
                //         add_filter('xstore_waitlist_single_product_settings', array($this, 'waitlist_btn_required_text'), 20, 2);
                //     }, 5);
                //     add_action($single_product_action, function () {
                //         remove_filter('xstore_waitlist_single_product_settings', array($this, 'waitlist_btn_required_text'), 20, 2);
                //     }, 25);
                // }
//            }
//            if ($single_product_action) {
//                if ($position == 'stock_message') {
                    add_filter('woocommerce_get_stock_html', array($this, 'print_button_single_stock_replace'), 100, 2);
                    add_action('woocommerce_after_single_product', array($this, 'output_form_script'));

                    // change template only if it is variable.php file and empty variations (all out of stock)
                    add_filter( 'wc_get_template', function ( $template, $template_name, $args, $template_path, $default_path ) {
                        if ( isset($args['available_variations']) && empty($args['available_variations']) && basename($template) == 'variable.php' ) {
                            $template = plugin_dir_path( __FILE__ ) . 'templates/variable.php';
                        }
                        return $template;
                    }, 20, 5);
//                    add_action('woocommerce_after_shop_loop_item_title', function () {
//                        global $product;
//                        if ( 'outofstock' === $product->get_stock_status() || ( $product->managing_stock() && 0 === (int) $product->get_stock_quantity() && 'no' === $product->get_backorders() ) ) {
//                            $this->print_button($product->get_ID());
//                        }
//                    }, 5);
//                } else
//                    add_action($single_product_action, array($this, 'print_button_single'), $single_product_priority);

                // before etheme_sticky_add_to_cart()
                add_action('etheme_sticky_add_to_cart_before', function () {
//                    if ($position == 'stock_message') {
                        remove_filter('woocommerce_get_stock_html', array($this, 'print_button_single_stock_replace'), 100, 2);
                        remove_action('woocommerce_after_single_product', array($this, 'output_form_script'));
//                    } else
//                        remove_action($single_product_action, array($this, 'print_button_single'), $single_product_priority);
                }, 1);
                add_action('etheme_sticky_add_to_cart_after', function () {
//                    if ($position == 'stock_message') {
                        add_filter('woocommerce_get_stock_html', array($this, 'print_button_single_stock_replace'), 100, 2);
                        add_action('woocommerce_after_single_product', array($this, 'output_form_script'));
//                    } else
//                        add_action($single_product_action, array($this, 'print_button_single'), $single_product_priority);
                }, 10);
//            }

            if ( $this->is_waitlist_page() ) {
                // prevent load account page styles
                add_filter('etheme_enqueue_account_page_style', '__return_false');

                wp_enqueue_style( self::$key . '-page' );
                add_filter('body_class', array($this, 'add_body_classes'));

                if ( isset($_REQUEST['wid']) ) {
//                    var_dump(self::USER_KEY);
//                    var_dump($_REQUEST['wid']);
                    $users = get_users(array(
                        'meta_key' => self::USER_KEY,
                        'meta_value' => $_REQUEST['wid']
                    ));
//                    var_dump($users);
                    $shared_from = $users[0];
                    add_filter('pre_get_document_title', function ($empty_title) use ($shared_from) {
                        return sprintf(__('Shared waitlist by %s', 'xstore-core'), $shared_from->display_name);
                    });
                }
                add_filter('theme_mod_ajax_added_product_notify_type', function ($old_value) {
                    return in_array($old_value, array('mini_cart', 'popup')) ? 'alert' : $old_value;
                });
            }
        });

        add_action( 'wp', array($this, 'ghost_waitlist_page'), 7 );

        add_action( 'wp_loaded', array( $this, 'no_script_add_to_waitlist' ), 20 );
        add_action( 'wp_loaded', array( $this, 'no_script_remove_waitlist_product' ), 20 );

//		if ( wp_doing_ajax() ) {
//            $this->init_added_products();
//            $this->define_settings();
//            add_filter('etheme_waitlist_btn_output', array($this, 'old_waitlist_btn_filter'), 10, 2);
//        }

//		$cookie_key = $this->get_cookie_key();
//		var_dump( $_COOKIE);
//		if ( isset( $_COOKIE[$cookie_key] ) ) { // @codingStandardsIgnoreLine.
//		    $products = explode( '|', $_COOKIE[$cookie_key]);
//		    $product_ids = array();
//			foreach ( $products as $key => $value ) {
//                $products[$key] = wp_unslash( (array)json_decode( $value) );
//				$product_ids[] = $products[$key]['id'];
//		    }
//			var_dump( $products);
//			var_dump( $product_ids);
//		    var_dump( json_decode($_COOKIE[$cookie_key]) );
//		    var_dump( wp_unslash( json_decode($_COOKIE[$cookie_key]) ));
//			$products = wp_parse_id_list( (array) explode( '|', wp_unslash( json_decode($_COOKIE[$cookie_key]) ) ) ); // @codingStandardsIgnoreLine.
//            var_dump( $products);
//		}
    }

    /**
     * Filter for body classes.
     * @param $classes
     * @return mixed
     */
    public function add_body_classes($classes) {
        $classes[] = 'woocommerce-waitlist';
        $classes[] = 'xstore-waitlist-page';
        if ( !isset($_REQUEST['wid'])) {
            $classes[] = 'xstore-waitlist-owner';
        }
        return $classes;
    }

    public function init_added_products() {
        $added_products = $this->get_products();
        self::$products_ids = $added_products['ids'];
        self::$products = $added_products['products'];
        self::$inited = true;
    }
    /**
     * Checker if it is waitlist page or has [xstore_waitlist_page] shortcode on this page.
     * @return bool
     */
    public function is_waitlist_page() {
        return ( self::$waitlist_page_id && is_page( self::$waitlist_page_id ) ) || (isset($_GET['et-waitlist-page']) && is_account_page()) || (class_exists('WooCommerce') && wc_post_content_has_shortcode( 'xstore_waitlist_page' ));
    }

    public function test_save_user_waitlist() {
        $products = $this->get_products();
        $cookie_key = $this->get_cookie_key();
        $saved_products = get_user_meta(get_current_user_id(), $cookie_key, true);
        if ( !$saved_products ) {
            $saved_products = [];
        }
        else {
            $saved_products_local = explode('|', $saved_products);
            $saved_products = [];
            foreach ($saved_products_local as $local_product_info ) {
                $product_info = (array)json_decode($local_product_info);
                $saved_products[$product_info['id']] = $product_info;
            }

        }

        $merge = array_merge($saved_products, $products['products']);
        $filtered = array();
        $filtered_json = array();
        foreach ($merge as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

        self::$products_ids = array_keys($filtered);

        self::$products = array_values($filtered);

    }

    /**
     * Updates user waitlist with the items set from unlogged state and merged with the ones set before.
     * @param $user_login
     * @param $user
     */
    public function update_ids_after_login( $user_login, $user){
        if ( !class_exists( 'WooCommerce') ) return;
        $products = $this->get_products();
        $cookie_key = $this->get_cookie_key();
        $saved_products = get_user_meta($user->ID, $cookie_key, true);
        if ( !$saved_products ) {
            $saved_products = [];
        }
        else {
            $saved_products_local = explode('|', $saved_products);
            $saved_products = [];
            foreach ($saved_products_local as $local_product_info ) {
                $product_info = (array)json_decode($local_product_info);
                $saved_products[$product_info['id']] = $product_info;
            }

        }

        $merge = array_merge($saved_products, $products['products']);
        $filtered = array();
        $filtered_json = array();
        foreach ($merge as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

        $ready_products = array_values($filtered_json);
        update_user_meta($user->ID, $cookie_key, implode('|', $ready_products) );

        unset( $_COOKIE[$cookie_key] );
        setcookie($cookie_key, implode('|', $ready_products), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
        $_COOKIE[ $cookie_key ] = implode('|', $ready_products);
        self::$products_ids = array_keys($filtered);
        self::$products = array_values($filtered);
    }

    public function update_waitlist() {
        global $wpdb;

        $ajax            = array(
            'type' => 'error',
            'success' => false,
            'message' => esc_html__( 'Error on submitting for waiting list.', 'xstore-core' )
        );
        $table = str_replace('-', '_', self::$key);
        $email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $pid    = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
        $reset = isset( $_POST['reset'] ) && $_POST['reset'] == 'yes';

        $data   = array(
            'email'      => $email,
            'product_id' => $pid,
            'created'    => time(),
        );
        
        $format = array( '%s', '%d', '%d' );
        if ( $email && $pid ) {
            $found = (int)$wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . $table . ' WHERE product_id = %d AND email = %s', $pid, $email)); // db call ok; no-cache ok.
            if (!$found) {
                if ( !$reset ) {
                    $action = $wpdb->insert($wpdb->prefix . $table, $data, $format); // db call ok; no-cache ok.
                    if (false === $action) {
                        write_log(new \WP_Error($wpdb->last_error));
                    }
                }
                else {
//                    $ajax['status']  = 1;
                    $ajax['message'] = self::$waitlist_options['unsubscribe_error_text'];
//                    $ajax['message'] = str_replace(
//                            '{{join_waitlist_button}}',
//                        '<button class="xstore-waitlist button" data-action="add" data-id="'.$pid.'" data-settings='.json_encode(array('force_action' => true)).'>'.self::$waitlist_options['button_text'].'</button>',
//                            self::$waitlist_options['unsubscribe_error_text']);
                    wp_send_json($ajax);
                }
            }
            else {
                if ($reset) {
//                $action = $wpdb->delete($wpdb->prefix . $table, $data, $format); // db call ok; no-cache ok.
                    $action = $wpdb->delete($wpdb->prefix . $table, array('product_id' => $pid, 'email' => $email), array('%d', '%s'));
                    $ajax['success'] = true;
                    $ajax['message'] = self::$waitlist_options['unsubscribe_success_text'];
//                    $ajax['message'] = (false === $action ?
//                        str_replace(
//                            '{{join_waitlist_button}}',
//                            '<button class="xstore-waitlist button" data-action="add" data-id="'.$pid.'" data-settings='.json_encode(array('force_action' => true)).'>'.self::$waitlist_options['button_text'].'</button>',
//                            self::$waitlist_options['unsubscribe_error_text']) :
//                            self::$waitlist_options['unsubscribe_success_text']);
                    wp_send_json($ajax);
                    return;
                }
                else {
                    $ajax['type'] = 'info';
                    $ajax['message'] = self::$waitlist_options['subscribed_text'];
                    wp_send_json($ajax);
                }
            }

            $ajax['success']  = true;
            $ajax['message'] = 'Success message text';

            $product = wc_get_product( $pid );
            if ( $product )
                do_action('xstore_waitlist_new_customer_request', $product, $email, self::$waitlist_options);
        }
        wp_send_json( $ajax );
    }

    public function create_link_tag($link, $text = '') {
        return '<a href="'.$link.'">'.($text ? $text : $link).'</a>';
    }

    /**
     * Update waitlist products set for specific user id.
     */
    public function update_user_waitlist() {
        $user_id = get_current_user_id();

        update_user_meta($user_id, $this->get_cookie_key(), (isset($_POST['products']) ? $_POST['products'] : '') );

        echo wp_json_encode(array('success' => true));
        exit;
    }

    /**
     * Getter of waitlist products from specific user.
     */
    public function get_user_waitlist() {
        echo wp_json_encode( array('success' => true, 'products' => $this->get_products($_POST['cookie_key'])) );

        exit;
    }

    /**
     * Getter of created user unique key.
     * @return mixed|string
     */
    public function get_user_key() {

        $user_id = get_current_user_id();

        if ( ! ($user_key = get_user_meta( $user_id, self::USER_KEY, true )) ) {

            $user_key = strtoupper( substr( base_convert( md5( self::USER_KEY . $user_id ), 16, 32), 0, 12) );

            update_user_meta( $user_id, self::USER_KEY, $user_key );
        }

        return $user_key;
    }

//	public function global_page_actions() {
//        $products = $_POST['products'];
//        if ( $products ) {
//            foreach ($products as $value) {
//                $product_info = (array)json_decode($value);
//                $products_ids[] = $product_info['id'];
//                $products[] = $product_info;
//            }
//        }
//        echo wp_json_encode( array('success' => true) );
//
//        exit;
//    }

//	public function add_to_waitlist_action() {
//
//		check_ajax_referer( 'xstore-waitlist-add', 'security' );
//
//	    $data = array(
//	        'group' => '',
//            'product_id' => isset($_POST['product_id']) ? $_POST['product_id'] : null,
//            'security' => isset($_POST['security']) ? $_POST['security'] : ''
//        );
//	    if ( !$data['product_id'] ) return false;
//
//	    $group = '';
//	    if (isset($_POST['group'])) {
//	        $group = $_POST['group'];
//	    }
//
//	    if ( !$this->is_product_in_waitlist($data['product_id'])) {
//		    $all_products = $this->get_products();
//		    $all_products[$data['product_id']] = array(
//		        'id' => $data['product_id'],
//		        'time' => time()
//            );
//
////		    setcookie( $this->get_cookie_key(), json_encode( $all_products ), (time() + intval(WEEK_IN_SECONDS)) );
//
//	    }
//		echo wp_json_encode( array('success' => true) );
//
//		exit;
//    }

    /**
     * Getter of cookie key
     * @return string
     */
    public static function get_cookie_key(){
        return self::COOKIE_KEY . '_' . (is_multisite() ? get_current_blog_id() : 0);
    }

    public static function get_days_cache() {
        $cache_days = 7;
        switch (get_theme_mod('xstore_waitlist_cache_time', 'week')) {
            case 'week':
                $cache_days = 7;
                break;
            case 'month':
                $cache_days = 31;
                break;
            case '3months':
                $cache_days = 31*3;
                break;
            case 'year':
                $cache_days = 365;
                break;
        }
        return $cache_days;
    }

    /**
     * Reset of waitlist products
     */
    public function reset_products() {
        $cookie_key = $this->get_cookie_key();
        unset( $_COOKIE[$cookie_key] );
        setcookie($cookie_key, null, 0);
    }

    /**
     * Getter of waitlist products
     * @param null $cookie_key
     * @param null $user_id
     * @return array[]
     */
    public function get_products($cookie_key = null, $user_id = null) {
        $products = [];
        $products_ids = [];

        $cached_products = '';
        $user_cached_products = false;

        $cookie_key = $cookie_key ? $cookie_key : $this->get_cookie_key();

        // if user is logged in take products info from usermeta
        if ( $user_id ) {
            $cached_products = get_user_meta($user_id, $cookie_key, true );
            $user_cached_products = true;
        }
        elseif ( is_user_logged_in() ) {
            $cached_products = get_user_meta(get_current_user_id(), $cookie_key, true );
            $user_cached_products = true;
        }

        // if user is not logged in or does not have any products set to his usermeta take it from cookies
        if ( !$user_cached_products && isset( $_COOKIE[$cookie_key] ) && !empty( $_COOKIE[$cookie_key]) ) { // @codingStandardsIgnoreLine.
            $cached_products = $_COOKIE[$cookie_key];
        }

        if ( $cached_products != '' && $cached_products = explode( '|', $cached_products) ) {
            $sitepress_exists = class_exists('SitePress');
            if ( $sitepress_exists ) {
                global $sitepress;
            }
            foreach ($cached_products as $value) {
                $product_info = (array)json_decode(stripcslashes($value));

                if (!isset($product_info['id'])) {
                    continue;
                }

                // if product was permanently removed then prevent it from count and showing
                $product = class_exists('WooCommerce') ? wc_get_product($product_info['id']) : false;
                if (!$product || $product->get_status() == 'trash')
                    continue;

                if ( $sitepress_exists ) {
                    $productIdTranslated = $sitepress->get_original_element_id($product_info['id'], 'post_product');
                    $products_ids[] = $productIdTranslated ? $productIdTranslated : $product_info['id'];
                }
                elseif ( function_exists( 'pll_current_language' ) ) {
                    $productIdTranslated = PLL()->model->post->get_translation( $product_info['id'], pll_default_language() );
                    $products_ids[] = $productIdTranslated ? $productIdTranslated : $product_info['id'];
                }
                else
                    $products_ids[] = $product_info['id'];
                $products[] = $product_info;
            }
        }

        return ['ids' => $products_ids, 'products' => $products];
    }

    /**
     * Checker if product id is in the list of already added products
     * @param $product_id
     * @return bool
     */
    public function is_product_in_waitlist($product_id) {
        return in_array($product_id, self::$products_ids);
    }

    /**
     * Filter mini-waitlist add to cart buttons classes (used for add all to cart button action)
     * @param $args
     * @return mixed
     */
    public function filter_miniwaitlist_add_to_cart_button($args) {
        $args['class'] .= ' hidden';
        return $args;
    }

    public function waitlist_btn_required_text($args) {
        $args['only_icon'] = false;
        $args['has_tooltip'] = false;
        return $args;
    }

    public function waitlist_btn_only_icon($args) {
        $args['only_icon'] = true;
        $args['has_tooltip'] = true;
        return $args;
    }

    /**
     * Print single product waitlist button. Based on origin print_button() but with custom options set for button.
     * @param null $productId
     * @param array $custom_settings
     */
    public function print_button_single($productId = null, $custom_settings = array()) {

        if ( !apply_filters('xstore_waitlist_print_single_product_button', true) ) return;

        $custom_settings['is_single'] = true;
        $custom_settings['has_tooltip'] = get_theme_mod('product_waitlist_tooltip', false);
        // $custom_settings['redirect_on_remove'] = get_theme_mod('product_waitlist_redirect_on_remove', false);
        // keep inheritance from global options yet
//        $custom_settings['add_text'] = get_theme_mod('product_waitlist_label_add_to_waitlist', esc_html__('Notify when available', 'xstore-core'));
//        $custom_settings['remove_text'] = get_theme_mod('product_waitlist_label_browse_waitlist', esc_html__('Browse waitlist', 'xstore-core'));
        $custom_settings['only_icon'] = get_theme_mod('product_waitlist_only_icon', false);
//        $custom_settings['class'] = array('single-waitlist');
        $custom_settings = apply_filters('xstore_waitlist_single_product_settings', $custom_settings);

        $this->print_button($productId, $custom_settings);
    }

    /**
     * Print waitlist button
     * @param null $productId
     * @param array $custom_settings
     */
    public function print_button($productId = null, $custom_settings = array()) {
        global $product;

        // if it is doing_ajax and settings are not defined then init it again
//		if ( count(self::$settings) < 1) {
        if ( wp_doing_ajax() ) {
            $this->init_added_products();
            $this->define_settings();
        }
        $settings = wp_parse_args( $custom_settings, self::$settings );

        $productId = $productId ? $productId : $product->get_ID();

        if ( class_exists('SitePress')) {
            global $sitepress;
            $productIdTranslated = $sitepress->get_original_element_id($productId, 'post_product');
            if ( $productIdTranslated )
                $productId = $productIdTranslated;
        }
        elseif ( function_exists( 'pll_current_language' ) ) {
            // get default post id
            $productIdTranslated = PLL()->model->post->get_translation( $productId, pll_default_language() );
            if ( $productIdTranslated )
                $productId = $productIdTranslated;
        }

        $local_product = $productId ? wc_get_product($productId) : $product;

        $add_action = !$this->is_product_in_waitlist($productId);
//        $add_action = $settings['add_action']; // force always add action only - remove to make browse waitlist work

        $attributes = array(
            'class' => array(
                self::$key,
//                'btn',
//                'black'
            ),
            'data-action'=> $add_action?'add':'remove',
            'data-id' => $productId,
            'data-settings'=> array(),
        );

        if ( $settings['custom_icon'] ) {
            $attributes['data-settings']['iconAdd'] = false;
            $attributes['data-settings']['iconRemove'] = false;
        }
        else {
            if (!empty($settings['add_icon_class'])) {
                $attributes['data-settings']['iconAdd'] = $settings['add_icon_class'];
            }

            if (!empty($settings['remove_icon_class'])) {
                $attributes['data-settings']['iconRemove'] = $settings['remove_icon_class'];
            }
        }

        if ( !empty($settings['add_text']) ) {
            $attributes['data-settings']['addText'] = $settings['add_text'];
        }

        if ( !empty($settings['remove_text']) ) {
            $attributes['data-settings']['removeText'] = $settings['remove_text'];
        }

        if ( $settings['has_tooltip'] ) {
            $attributes['class'][] = 'mtips';
            $attributes['class'][] = 'mtips-top';
        }

        if ( isset($settings['class']) ) {
            $attributes['class'] = array_merge($attributes['class'], (array)$settings['class']);
        }
        if ( $settings['is_single'] ) {
            $attributes['class'][] = self::$key . '-single';
            $attributes['class'][] = 'pos-relative';
        }

        if ( $settings['only_icon'] ) {
            $attributes['class'][] = 'xstore-waitlist-icon';
        }

        if ( $settings['redirect_on_remove'] ) {
            $attributes['class'][] = 'xstore-waitlist-redirect';
            if ( !$add_action )
                $attributes['class'][] = 'xstore-waitlist-redirect-ready';
        }

        $remove_button_only = isset($custom_settings['email']);
        if ( $remove_button_only )
            $attributes['data-settings']['email'] = $custom_settings['email'];
        $attributes['data-settings'] = json_encode($attributes['data-settings']);
        $attributes['class'] = implode(' ', array_unique($attributes['class']));

        $href = apply_filters('xstore_waitlist_product_query_args', true) ? add_query_arg(($add_action?'add_to_waitlist':'remove_waitlist'), $productId, self::$waitlist_page) : self::$waitlist_page;
        if ( $settings['is_single']) echo '<div class="single-waitlist">';

        if ( $remove_button_only && !isset($custom_settings['force_display']) ) {
            $attributes_rendered = array();
            foreach ($attributes as $attribute_key => $attribute_value) {
                $attributes_rendered[] = $attribute_key."='".$attribute_value."'";
            } ?>
            <a href="<?php echo esc_url($href); ?>" <?php echo implode(' ', $attributes_rendered); ?>>
                <?php if ( $settings['icon_position'] == 'left' && ($settings['show_icon'] || $settings['only_icon'])) :

                    if ($settings['custom_icon']) { ?>
                        <span class="et-icon"><?php echo $settings['custom_icon']; ?></span>
                    <?php }

                    else if ( ($add_action && !empty($settings['add_icon_class'])) || (!$add_action && !empty($settings['remove_icon_class'])) ) { ?>
                        <span class="et-icon <?php echo $add_action ? $settings['add_icon_class'] : $settings['remove_icon_class']; ?>"></span>
                    <?php }

                endif;

                if ( !$settings['only_icon'] ) {
                    if ($add_action) :
                        echo '<span class="button-text et-element-label">' . $settings['add_text'] . '</span>';
                    else:
                        echo '<span class="button-text et-element-label">' . $settings['remove_text'] . '</span>';
                    endif;
                }

                if ( $settings['icon_position'] == 'right' && ($settings['show_icon'] || $settings['only_icon'])) :

                    if ($settings['custom_icon']) { ?>
                        <span class="et-icon"><?php echo $settings['custom_icon']; ?></span>
                    <?php }

                    else if ( ($add_action && !empty($settings['add_icon_class'])) || (!$add_action && !empty($settings['remove_icon_class'])) ) { ?>
                        <span class="et-icon <?php echo $add_action ? $settings['add_icon_class'] : $settings['remove_icon_class']; ?>"></span>
                    <?php }

                endif;

                if ( $settings['has_tooltip'] ) { ?>
                    <span class="mt-mes"><?php echo $add_action ? $settings['add_text'] : $settings['remove_text']; ?></span>
                <?php } ?>
            </a>
            <?php
        }
        if ( !$remove_button_only || isset($custom_settings['force_display']) ) {
            echo $this->waitlist_output_form('', $local_product, array_merge(
                $settings,
                array(
                    'add_action' => $add_action,
                    'button_attributes' => $attributes
                )
            ));
        }
        if ( $settings['is_single']) echo '</div>';
    }


    public function print_button_single_stock_replace($html, $product, $atts = array()) {
        if ( $product->get_type() == 'variation' ) return $html;
        wp_enqueue_script( self::$key );
        wp_enqueue_script( 'call_popup' );
        ob_start();
        $this->print_button_single();
        return $html . ob_get_clean();
    }
    /**
     * Check all waitlist items for errors.
     */
    public function check_waitlist_items() {
        if ( is_admin() ) return;
        $return = true;
        $result = $this->check_waitlist_item_validity();
        $woo_exists = function_exists('wc_add_notice');
        if ( count($result['errors']) ) {
            foreach ($result['errors'] as $error) {
                if (is_wp_error($error) && $woo_exists) {
                    wc_add_notice($error->get_error_message(), 'error');
                    $return = false;
                }
            }
        }

//        $result = $this->check_cart_item_stock();
//
//        if ( is_wp_error( $result ) ) {
//            wc_add_notice( $result->get_error_message(), 'error' );
//            $return = false;
//        }

        return $return;

    }

    /**
     * Looks through waitlist items and checks the posts are not trashed or deleted.
     *
     * @return bool|WP_Error
     */
    public function check_waitlist_item_validity() {
        $return = [
            'success' => true,
            'errors' => []
        ];
        $products_ids_to_reset = [];

        foreach ( self::$products as $product_info) {
            $post_object = get_post( $product_info['id'] );
            setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
            $product = wc_get_product($product_info['id']);
            if ( ! $product || ! $product->exists() || 'trash' === $product->get_status() ) {
                $return['errors'][] = new \WP_Error( 'invalid', sprintf( __( 'Sorry, %s is no longer available and was removed from your waitlist.', 'xstore-core' ), is_object($product ? '"'.$product->get_name().'"' : esc_html__('One of your products', 'xstore-core')) ) );
                $products_ids_to_reset[] = $product_info['id'];
            }
            wp_reset_postdata();
        }
        $return['success'] = count($return['errors']) < 1;

        if ( count($products_ids_to_reset) > 0) {
            $products_to_leave = [];
            $products_filtered = array_filter(self::$products, function ($val) use ($products_ids_to_reset) {
                return !in_array($val['id'], $products_ids_to_reset);
            });

            foreach ($products_filtered as $product_info) {
                $products_to_leave[] = json_encode($product_info);
            }
            $cookie_key = $this->get_cookie_key();
            unset( $_COOKIE[$cookie_key] );
            setcookie($cookie_key, implode('|', $products_to_leave), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
            $_COOKIE[ $cookie_key ] = implode('|', $products_to_leave);
            self::$products_ids = array_diff(self::$products_ids, $products_ids_to_reset);
            self::$products = $products_filtered;
        }

        return $return;
    }

    public function waitlist_output_form($html, $product, $atts = array()) {
        $atts = shortcode_atts(
            array_merge(
                self::$settings,
                array(
                    'add_action' => true,
                    'button_attributes' => array()
                )
            ), $atts );

        if ( !isset($atts['button_attributes']['data-id']) || !$atts['button_attributes']['data-id'] ) return;

        if ( $atts['force_display'] || 'outofstock' === $product->get_stock_status() || ( $product->managing_stock() && 0 === (int) $product->get_stock_quantity() && 'no' === $product->get_backorders() ) ) {
            self::$should_load_single_assets = true;
            if ($atts['add_action']) {
                $html .= $this->add_to_waitlist_output_form($html, $product, $atts);
            } else {
                $html .= $this->remove_waitlist_output_form($html, $product, $atts);
            }
        }

        return $html;
    }

    public function add_to_waitlist_output_form($html, $product, $atts = array()) {
        $popup_button_attributes = array();
        foreach ($atts['button_attributes'] as $attribute_key => $attribute_value) {
            if ( $attribute_key == 'class' ) {
                $attribute_value = str_replace(array(self::$key . '-single', 'elementor-button'), array('', ''), $attribute_value);
                $attribute_value .= ' btn black';
            }
            $popup_button_attributes[] = $attribute_key."='".$attribute_value."'";
        }

            ob_start(); ?>
            <div class="single-product-availability-notify-wrapper flex justify-content-start mob-justify-content-start">
            <div class="et-availability-notify-popup et-called-popup" data-type="single-product-availability-notify-<?php echo esc_attr($atts['button_attributes']['data-id']); ?>">
                <div class="et-popup">
                    <div class="et-popup-content with-static-block">
                        <span class="et-close-popup et-toggle pos-fixed full-left top" style="margin-left: 5px;">
                          <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                          </svg>
                        </span>
                        <div class="<?php echo self::$key; ?>-info">
                            <h4 data-remove-text="<?php echo esc_attr(self::$waitlist_options['remove_intro']); ?>" class="et-popup-heading text-center"><?php echo esc_html(self::$waitlist_options['add_intro']); ?></h4>
                            <input type="email" class="<?php echo self::$key; ?>-email" name="<?php echo self::$key; ?>_email" placeholder="<?php echo esc_attr(self::$waitlist_options['add_placeholder']); ?>" value="<?php echo esc_attr(self::$user_email); ?>"/>
                            <label><input type="checkbox" class="<?php echo self::$key; ?>-consent" name="<?php echo self::$key; ?>_consent" value="1" data-remove-text="<?php echo esc_attr(self::$waitlist_options['remove_checkbox_text']); ?>"/>&nbsp;&nbsp;<?php echo esc_attr(self::$waitlist_options['add_checkbox_text']); ?></label>
                            <div class="<?php echo self::$key; ?>-buttons">
                                <button data-remove-text="<?php echo esc_attr(self::$waitlist_options['remove_button_text']); ?>" <?php echo implode(' ', $popup_button_attributes); ?>><?php echo esc_html(self::$waitlist_options['add_button_text']); ?></button>
                            </div>
                            <p class="<?php echo self::$key; ?>-error-message woocommerce-error"></p>
                        </div>
                    </div>
                </div>
            </div>

<!--            <span class="inline-block pos-relative et-call-popup btn medium--><?php //echo $atts['has_tooltip'] ? ' mtips mtips-top': ''; echo $atts['is_single'] ? ' '.self::$key . '-single' : ''; ?><!--"-->
            <span class="<?php echo implode(' ', array_unique(array_merge(array_filter(explode(' ', $atts['button_attributes']['class']), function($value) {return $value != self::$key; }), array_merge($atts['only_icon'] ? array() : array('btn', 'black', 'medium',), array('pos-relative', 'et-call-popup'))))); ?>"
                data-type="single-product-availability-notify-<?php echo esc_attr($atts['button_attributes']['data-id']); ?>">
                    <?php if ( $atts['icon_position'] == 'left' && ($atts['show_icon'] || $atts['only_icon'])) :

                        if ($atts['custom_icon']) { ?>
                            <span class="et-icon"><?php echo $atts['custom_icon']; ?></span>
                        <?php }

                        else if ( !empty($atts['add_icon_class']) ) { ?>
                            <span class="et-icon <?php echo $atts['add_icon_class']; ?>"></span>
                        <?php }

                    endif;

                    if ( !$atts['only_icon'] ) {
                        echo '<span class="button-text et-element-label">' . $atts['add_text'] . '</span>';
                    }

                    if ( $atts['icon_position'] == 'right' && ($atts['show_icon'] || $atts['only_icon'])) :

                        if ($atts['custom_icon']) { ?>
                            <span class="et-icon"><?php echo $atts['custom_icon']; ?></span>
                        <?php }

                        else if ( !empty($atts['add_icon_class']) ) { ?>
                            <span class="et-icon <?php echo $atts['add_icon_class']; ?>"></span>
                        <?php }

                    endif;

                    if ( $atts['has_tooltip'] ) { ?>
                        <span class="mt-mes"><?php echo $atts['add_text']; ?></span>
                    <?php } ?>
                </span>

            </div>

            <?php

        return ob_get_clean();
    }

    public function remove_waitlist_output_form($html, $product, $atts = array()) {
        $popup_button_attributes = array();
        foreach ($atts['button_attributes'] as $attribute_key => $attribute_value) {
            if ( $attribute_key == 'class' ) {
                $attribute_value = str_replace(array(self::$key . '-single', 'elementor-button'), array('', ''), $attribute_value);
                $attribute_value .= ' btn black';
            }
            $popup_button_attributes[] = $attribute_key."='".$attribute_value."'";
            switch ($attribute_key) {
                case 'data-action':
                    $attribute_value = 'add';
                    break;
            }
        }

        ob_start(); ?>
        <div class="single-product-availability-notify-wrapper flex justify-content-start mob-justify-content-start">
            <div class="et-availability-notify-popup et-called-popup" data-type="single-product-availability-notify-<?php echo esc_attr($atts['button_attributes']['data-id']); ?>">
                <div class="et-popup">
                    <div class="et-popup-content with-static-block">
                        <span class="et-close-popup et-toggle pos-fixed full-left top" style="margin-left: 5px;">
                          <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                          </svg>
                        </span>
                        <div class="<?php echo self::$key; ?>-info">
                            <h4 data-add-text="<?php echo esc_attr(self::$waitlist_options['add_intro']); ?>" class="text-center"><?php echo esc_html(self::$waitlist_options['remove_intro']); ?></h4>
                            <input type="email" class="<?php echo self::$key; ?>-email" name="<?php echo self::$key; ?>_email" placeholder="<?php echo esc_attr(self::$waitlist_options['remove_placeholder']); ?>" value="<?php echo esc_attr(self::$user_email) ?>"/>
                            <label><input type="checkbox" class="<?php echo self::$key; ?>-consent" name="<?php echo self::$key; ?>_consent" value="1" data-add-text="<?php echo esc_attr(self::$waitlist_options['add_checkbox_text']); ?>"/>&nbsp;&nbsp;<?php echo esc_attr(self::$waitlist_options['remove_checkbox_text']); ?></label>
                            <div class="<?php echo self::$key; ?>-buttons">
                                <button data-add-text="<?php echo esc_attr(self::$waitlist_options['add_button_text']); ?>" <?php echo implode(' ', $popup_button_attributes); ?>><?php echo esc_html(self::$waitlist_options['remove_button_text']); ?></button>
                            </div>
                            <p class="<?php echo self::$key; ?>-error-message woocommerce-error"></p>
                        </div>
                    </div>
                </div>
            </div>

            <span class="<?php echo implode(' ', array_unique(array_merge(array_filter(explode(' ', $atts['button_attributes']['class']), function($value) {return $value != self::$key; }), array_merge($atts['only_icon'] ? array() : array('btn', 'black', 'medium',), array('pos-relative', 'et-call-popup'))))); ?>"
                  data-type="single-product-availability-notify-<?php echo esc_attr($atts['button_attributes']['data-id']); ?>">
                    <?php if ( $atts['icon_position'] == 'left' && ($atts['show_icon'] || $atts['only_icon'])) :

                        if ($atts['custom_icon']) { ?>
                            <span class="et-icon"><?php echo $atts['custom_icon']; ?></span>
                        <?php }

                        else if ( !empty($atts['remove_icon_class']) ) { ?>
                            <span class="et-icon <?php echo $atts['remove_icon_class']; ?>"></span>
                        <?php }

                    endif;

                    if ( !$atts['only_icon'] ) {
                        echo '<span class="button-text et-element-label">' . $atts['remove_text'] . '</span>';
                    }

                    if ( $atts['icon_position'] == 'right' && ($atts['show_icon'] || $atts['only_icon'])) :

                        if ($atts['custom_icon']) { ?>
                            <span class="et-icon"><?php echo $atts['custom_icon']; ?></span>
                        <?php }

                        else if ( !empty($atts['remove_icon_class']) ) { ?>
                            <span class="et-icon <?php echo $atts['remove_icon_class']; ?>"></span>
                        <?php }

                    endif;

                    if ( $atts['has_tooltip'] ) { ?>
                        <span class="mt-mes"><?php echo $atts['remove_text']; ?></span>
                    <?php } ?>
                </span>

        </div>

        <?php

        return ob_get_clean();
    }

    /**
     * Create ghost waitlist page for customers who didn't set origin waitlist page in settings
     * modifying myaccount page with custom get params to filter old content with new one
     */
    public function ghost_waitlist_page() {
        if ( !(isset($_GET['et-waitlist-page']) && is_account_page() ) ) return;

        add_filter('pre_get_document_title', function ($empty_title) {
            return __('Waitlist', 'xstore-core');
        });

        // seo nofollow/noindex this page content
        add_action('wp_head', function () {
            echo "\n\t\t<!-- 8theme SEO v1.0.0 -->";
            echo '<meta name="robots" content="noindex, nofollow">';
            echo "\t\t<!-- 8theme SEO -->\n\n";
        });

        // filter page title in breadcrumbs only and remove it after breadcrumbs are shown
        add_action('etheme_page_heading', function () {
            add_filter('the_title', array($this, 'filter_ghost_waitlist_page_title'), 10, 2);
        }, 5);

        add_action('etheme_page_heading', function () {
            remove_filter('the_title', array($this, 'filter_ghost_waitlist_page_title'), 10, 2);
        }, 20);

        // load styles
        wp_enqueue_style( self::$key . '-page' );

        // add body classes
        add_filter('body_class', array($this, 'add_body_classes'));

        // modify [woocommerce_my_account] shortcode with the content of waitlist page
        add_filter('do_shortcode_tag', function ($content, $shortcode, $atts) {
            if ( $shortcode == 'woocommerce_my_account') {
                $content = $this->page_template($atts);
            }
            return $content;
        },10,3);
    }

    public function filter_ghost_waitlist_page_title($post_title, $post_id) {
        return $post_id == absint(get_option( 'woocommerce_myaccount_page_id' )) ? esc_html__('Waitlist', 'xstore-core') : $post_title;
    }

    public function escape_text($safe_text, $text) {
        return $text;
    }

    public function add_to_cart_icon($text) {
        global $et_cart_icons;
//		$settings = $this->get_settings_for_display();
        $cart_type = get_theme_mod( 'cart_icon_et-desktop', 'type1' );
        $cart_type = apply_filters('cart_icon', $cart_type);

        $icon_custom = get_theme_mod('cart_icon_custom_svg_et-desktop', '');
        $icon_custom = apply_filters('cart_icon_custom', $icon_custom);
        $icon_custom = isset($icon_custom['id']) ? $icon_custom['id'] : '';

        $cart_icons = !get_theme_mod('bold_icons', 0) ? $et_cart_icons['light'] : $et_cart_icons['bold'];

        if ( $cart_type == 'custom' ) {
            if ( $icon_custom != '' ) {
                $cart_icons['custom'] = str_replace(array('fill="black"', 'stroke="black"'), array('fill="currentColor"', 'stroke="currentColor"'), etheme_get_svg_icon($icon_custom));
            }
            else {
                $cart_icons['custom'] = $cart_icons['type1'];
            }
        }

        $cart_icon = $cart_icons[$cart_type];

        return $cart_icon ? $cart_icon . '<span class="button-text">'.$text.'</span>' : $text;
    }
    /**
     * Waitlist page shortcode content
     * @param $atts
     * @param null $content
     * @return false|string
     */
    public function page_template($atts, $content=null) {

        $atts = shortcode_atts( array(
            'share' => true,
            'design' => 'table'
        ), $atts );

//        $this->check_waitlist_items();

        $own_waitlist = true;
        $unlogged_shared_waitlist = false;
        if ( isset($_REQUEST['wid']) && !empty($_REQUEST['wid'])) {
            $users = get_users(array(
                'meta_key' => self::USER_KEY,
                'meta_value' => $_REQUEST['wid']
            ));
            $products = $this->get_products(null, $users[0]->ID);
            $products = $products['products'];
            $own_waitlist = false;
        }
        elseif ( isset($_GET['waitlist_product_ids'])) {
            $products = array();
            $products_ids = explode(',', $_GET['waitlist_product_ids']);
            foreach ($products_ids as $products_id) {
                $products[] = array('id' => $products_id);
            }
            $own_waitlist = false;
            $unlogged_shared_waitlist = true;
        }
        else {
            $products = self::$products;
        }

        if ( count($products) < 1) {
            ob_start();
            $this->empty_page_template();
            $return = ob_get_clean();
        }
        else {
            add_filter('pre_option_woocommerce_cart_redirect_after_add', '__return_false');
            $share_socials = array();
            $waitlist_page_args = array(
                'own_waitlist' => $own_waitlist,
                'products' => array_reverse($products),
                'waitlist_url' => self::$waitlist_page,
                'global_actions' => array(
                    'add' => esc_html__('Add to cart', 'xstore-core'),
                    'remove' => $own_waitlist ? esc_html__('Remove', 'xstore-core') : esc_html__('Remove from my waitlist', 'xstore-core')
                )
            );
            if ( !$own_waitlist ) {
                $waitlist_page_args['global_actions']['add_waitlist'] = esc_html__('Add to my waitlist', 'xstore-core');
            }
            if ( !$unlogged_shared_waitlist && is_user_logged_in() ) {
                $share_socials = get_theme_mod('socials', array( 'share_twitter', 'share_facebook', 'share_vk', 'share_pinterest', 'share_mail', 'share_linkedin', 'share_whatsapp', 'share_skype'));
                $waitlist_page_args['user_key'] = $this->get_user_key();
                $waitlist_page_args['share_url'] = add_query_arg( 'wid', $waitlist_page_args['user_key'], $waitlist_page_args['waitlist_url'] );
            }
            $waitlist_page_args['share_socials'] = $share_socials;
            ob_start();
//			$old_socials = get_theme_mod('socials', array( 'share_twitter', 'share_facebook', 'share_vk', 'share_pinterest', 'share_mail', 'share_linkedin', 'share_whatsapp', 'share_skype'));
//			add_filter('theme_mod_socials', function ($socials) use ($share_socials) {
//			    return $share_socials;
//            });
            wc_print_notices();
            switch ($atts['design']) {
                case 'table':
                    $this->render_table_products(self::get_instance(), $waitlist_page_args);
                    break;
            }
//            add_filter('theme_mod_socials', function ($socials) use ($old_socials) {
//                return $old_socials;
//            });
            $return = ob_get_clean();
        }
        return $return;
    }

    /**
     * Load empty waitlist page template
     */
    public function empty_page_template() {
        // load direct path because this function used in ajax
        include_once plugin_dir_path( __FILE__ ) . '/templates/empty-waitlist.php';
    }

    /**
     * Load waitlist page template
     * @param $instance
     * @param $waitlist_page_args
     */
    public function render_table_products($instance, $waitlist_page_args) {
        include_once self::$templates_path . '/waitlist.php';
    }

    public function no_script_add_to_waitlist() {
        if ( ! isset( $_REQUEST['add_to_waitlist'] ) || ! is_numeric( wp_unslash( $_REQUEST['add_to_waitlist'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return;
        }

        $product_id = (int)$_REQUEST['add_to_waitlist'];
        $products = $this->get_products();

        if ( in_array($product_id, $products['ids'])) return;

        $products['ids'][] = $product_id;
        $products['products'][] = array(
            'id' => $product_id,
            'time' => strtotime( 'now' )
        );

        $this->no_script_update_waitlist($products['products']);
    }

    public function no_script_remove_waitlist_product() {
        if ( ! isset( $_REQUEST['remove_waitlist'] ) || ! is_numeric( wp_unslash( $_REQUEST['remove_waitlist'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return;
        }

        $product_id_to_reset = (int)$_REQUEST['remove_waitlist'];
        $products = $this->get_products();

        if ( !in_array($product_id_to_reset, $products['ids'])) return;

        $products_filtered = array_filter($products['products'], function ($val) use ($product_id_to_reset) {
            return $val['id'] != $product_id_to_reset;
        });

        $this->no_script_update_waitlist($products_filtered);
    }

    public function no_script_update_waitlist($products) {
        $cookie_key = $this->get_cookie_key();

        $filtered = array();
        $filtered_json = array();
        foreach ($products as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

        $ready_products = array_values($filtered_json);

        unset( $_COOKIE[$cookie_key] );
//
        setcookie($cookie_key, implode('|', $ready_products), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
        $_COOKIE[ $cookie_key ] = implode('|', $ready_products);
        self::$products_ids = array_keys($filtered);
        self::$products = array_values($filtered);
        self::$inited = true;

        if ( is_user_logged_in() ) {
            update_user_meta(get_current_user_id(), $this->get_cookie_key(), $_COOKIE[ $cookie_key ]);
        }
    }

    /**
     * Commercekit create plugin tables
     */
    function create_database_table() {
        global $wpdb;
        self::$db_version_created = get_option( self::$key.'_db_version' );
        $installed_version = (string) self::$db_version_created;
        if ( $installed_version === self::$db_version ) {
            return true;
        }

        $table_name = str_replace('-', '_', self::$key);
        $waitlist_table  = $wpdb->prefix . $table_name;
        $get_table  = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $waitlist_table ) ); // db call ok; no-cache ok.
        if ( $waitlist_table !== $get_table ) {
            $sql  = 'CREATE TABLE IF NOT EXISTS `' . $waitlist_table . '` ( ';
            $sql .= '`id` INT(11) NOT NULL AUTO_INCREMENT, ';
            $sql .= '`email` VARCHAR(255) NOT NULL, ';
            $sql .= '`product_id` BIGINT(20) NOT NULL, ';
            $sql .= '`mail_sent` TINYINT(1) NOT NULL DEFAULT \'0\', ';
            $sql .= '`created` BIGINT(20) NOT NULL, ';
            $sql .= 'PRIMARY KEY (`id`) ';
            $sql .= '); ';
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta( $sql ); // db call ok; no-cache ok.
        } else {
            $field_cols = $wpdb->get_col( 'SHOW COLUMNS FROM `' . $waitlist_table . '`' ); // phpcs:ignore
            if ( ! in_array( 'mail_sent', $field_cols, true ) ) {
                $sql = 'ALTER TABLE `' . $waitlist_table . '` ADD `mail_sent` TINYINT(1) NOT NULL DEFAULT \'0\' AFTER `product_id`';
                $wpdb->query( $sql ); // phpcs:ignore
            }

            $field_rows = $wpdb->get_results( 'SHOW COLUMNS FROM `' . $waitlist_table . '`' ); // phpcs:ignore
            if ( count( $field_rows ) ) {
                foreach ( $field_rows as $field_row ) {
                    if ( 'product_id' === $field_row->Field && 'int(11)' === strtolower( $field_row->Type ) ) { // phpcs:ignore
                        $sql = 'ALTER TABLE `' . $waitlist_table . '` MODIFY `product_id` BIGINT(20) NOT NULL';
                        $wpdb->query( $sql ); // phpcs:ignore
                    }
                }
            }
        }

        self::$db_version_created = self::$db_version;

        update_option( self::$key.'_db_version', self::$db_version_created, false );
    }

    public function panel_tab() {
        ?>
        <li class="<?php echo esc_attr(self::$key); ?>_options <?php echo esc_attr(self::$key); ?>_tab hide_if_virtual hide_if_external">
            <a href="#<?php echo esc_attr(self::$key); ?>_data"><span>
            <?php echo esc_html__( 'Waitlist', 'xstore-core' ); ?>
            <?php echo '<span class="et-brand-label" style="background: var(--et_admin_dark-color, #222); color: #fff; font-size: 0.65em; line-height: 1; padding: 2px 5px; border-radius: 3px; margin: 0; margin-inline-start: 3px;">'.apply_filters('etheme_theme_label', 'XStore').'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

    public function panel_data() {
        global $post;
        global $wpdb;
        $table = str_replace('-', '_', self::$key);
        $rows    = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT email, created, mail_sent FROM ' . $wpdb->prefix . $table . ' WHERE product_id = %d ORDER BY created ASC', $post->ID ), ARRAY_A ); // db call ok; no-cache ok.

        $product = wc_get_product( $post->ID );
        $product_name = $product->get_name();
        $notify_column = false;
        if ( $product->is_on_backorder() || $product->is_in_stock() ) {
            $notify_column = true;
        }

        ?>
        <div id="<?php echo esc_attr(self::$key); ?>_data" class="panel wc-metaboxes-wrapper">
            <?php
            if ( is_array( $rows ) && count( $rows ) ) { ?>
                <div class="wc-metaboxes">
                    <div class="wc-metabox">
                        <table>
                            <thead>
                            <tr>
                                <td><strong><?php echo esc_html__('Customer email', 'xstore-core'); ?></strong></td>
                                <td><strong><?php echo esc_html__('Date added', 'xstore-core'); ?></strong></td>
                                <td><strong><?php echo esc_html__('Notified', 'xstore-core'); ?></strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $row) {
                                $actions = array(); ?>
                                <tr>
                                    <td>
                                        <?php echo $row['email']; ?>
                                        <?php if ( $notify_column ) : ?>
                                            <?php
                                            $actions[] = sprintf(
                                                '<a href="%s" aria-label="%s" data-email="%s" data-id="%s" data-texts="%s" class="et-waitlist-notify">%s</a>',
                                                $this->get_action_user_waitlist('notify', $row['email'], $post->ID),
                                                /* translators: %s: Post title. */
                                                esc_attr(sprintf(__('Notify &#8220;%s&#8221;'), $product_name)),
                                                $row['email'],
                                                $post->ID,
                                                esc_attr(wp_json_encode(
                                                    array(
                                                        'default' => esc_html__('Notify', 'xstore-core'),
                                                        'success' => esc_html__('Notified', 'xstore-core'),
                                                        'process' => esc_html__('Sending email...', 'xstore-core'),
                                                        'error' => esc_html__('Error', 'xstore-core'),
                                                        'notify_yes' => esc_html__('Yes', 'xstore-core')
                                                    )
                                                )),
                                                __('Notify', 'xstore-core')
                                            );

                                        endif;
                                        $actions[] = sprintf(
                                            '<span class="delete"><a href="%s" aria-label="%s" data-email="%s" data-id="%s" data-texts="%s" class="et-waitlist-delete">%s</a></span>',
                                            $this->get_action_user_waitlist('delete', $row['email'], $post->ID),
                                            /* translators: %s: Post title. */
                                            esc_attr(sprintf(__('Delete &#8220;%s&#8221; waitlist request'), $product_name)),
                                            $row['email'],
                                            $post->ID,
                                            esc_attr(wp_json_encode(
                                                array(
                                                    'default' => esc_html__('Delete', 'xstore-core'),
                                                    'success' => esc_html__('Deleted', 'xstore-core'),
                                                    'process' => esc_html__('Deleting', 'xstore-core'),
                                                    'error' => esc_html__('Error', 'xstore-core'),
                                                )
                                            )),
                                            __('Delete', 'xstore-core')
                                        );

                                        echo '<div class="row-actions">' . implode('&nbsp;|&nbsp;', $actions) . '</div>';
                                        ?>
                                    </td>
                                    <td><?php echo date(get_option('date_format'), $row['created']); ?></td>
                                    <td>
                                        <span class="notified-state"><?php echo $row['mail_sent'] ? esc_html__('Yes', 'xstore-core') : esc_html__('No', 'xstore-core'); ?></span>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                            </tbody>
                        </table>
                        <br/>
                        <div class="toolbar toolbar-buttons">
                            <a href="<?php echo admin_url( 'admin.php?page=et-waitlists' ); ?>" target="_blank" class="button button-primary"><?php echo esc_html__('Browse Full Waitlist', 'xstore-core'); ?></a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
            <div class="options_group">
                <p class="form-field"></p>
                <div class="form-field" style="padding: 0 10px;">
                    <p class="et-message et-info">
                        <?php echo esc_html__('There are no customers who have added this product to their waitlist yet!', 'xstore-core'); ?>
                    </p>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
    }

    public function get_action_user_waitlist($action, $email, $product_id) {
        return wp_nonce_url(
            add_query_arg( array('waitlist-action' => "{$action}-waitlist", 'email' => $email, 'product_id' => $product_id, 'is_single-product-waitlist' => true), get_edit_post_link() ),
            "{$action}-waitlist_{$email}_{$product_id}");
    }

    public function notify_customer($customer_email = null, $product_id = null, $ajax = true) {
        global $wpdb;
        $table = str_replace('-', '_', self::$key);
        if ( !current_user_can( 'manage_options' ) || ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), $table . '_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            wp_send_json(
                array(
                    'success' => false,
                    'error_text' => __( 'You are not allowed to complete this task due to invalid nonce validation.', 'xstore-core' )
                )
            );
            exit;
        }
        $email = isset($_POST['email']) ? $_POST['email'] : $customer_email;
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : $product_id;
        $action = false;
        if ( $email && $product_id ) {
            $_product = wc_get_product($product_id );

            if ( $_product ) {
                \WC()->mailer()->emails['XStore_Waitlist_Customer_Notify']->unique_customer_trigger( $email, $product_id, $_product );
                $data    = array(
                    'mail_sent' => 1,
                );
                $where   = array(
                    'email'      => $email,
                    'product_id' => $product_id,
                );

                $data_format  = array( '%d' );
                $where_format = array( '%s', '%d' );
                $wpdb->update( $wpdb->prefix . $table, $data, $where, $data_format, $where_format ); // db call ok; no-cache ok.
                $action = true;
            }
        }
        if ( $ajax ) {
            wp_send_json(
                array(
                    'success' => !!$action,
                    'error_text' => esc_html__('Error: your request could not be processed at this time. please try again later or contact to site administrator.', 'xstore-core')
                )
            );
        }
    }

    public function delete_request($customer_email = null, $product_id = null, $ajax = true) {
        global $wpdb;
        $table = str_replace('-', '_', self::$key);
        if ( !current_user_can( 'manage_options' ) || ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), $table . '_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            wp_send_json(
                array(
                    'success' => false,
                    'error_text' => __( 'You are not allowed to complete this task due to invalid nonce validation.', 'xstore-core' )
                )
            );
            exit;
        }
        $email = isset($_POST['email']) ? $_POST['email'] : $customer_email;
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : $product_id;
        $action = false;
        if ( $email && $product_id ) {
            $action = $wpdb->delete($wpdb->prefix . $table, array('product_id' => $product_id, 'email' => $email), array('%d', '%s'));
        }
        if ( $ajax ) {
            wp_send_json(
                array(
                    'success' => !!$action,
                    'error_text' => esc_html__('Error: your request could not be processed at this time. please try again later or contact to site administrator.', 'xstore-core')
                )
            );
        }
    }

    public function register_admin_page(){
        if ( !current_user_can('edit_posts') ) return;

        $hook = add_menu_page(
            esc_html__('Waitlist', 'xstore-core'),
            esc_html__('Waitlist', 'xstore-core'),
            'edit_posts',
            'et-waitlists',
            array( $this, 'load_waitlists_wp_list_table' ),
            'dashicons-bell',
            52.35
        );

//        add_action( "load-".$hook, array($this, 'add_options') );
        // Creating help tab
//        add_action( 'current_screen',array($this, 'add_help_tab'));

    }
    public function add_help_tab() {
        $screen = get_current_screen();
        $screen->add_help_tab( array(
            'id'    => 'et_waitlist_help_tab',
            'title' => esc_html__('Information', 'xstore-core'),
            'content'   => '<p>' . __( 'Here you can find all list of waitlist request from customers.', 'xstore-core' ) . '</p>',
        ) );
    }
    public function add_options() {
        $option = 'per_page';
        $args = array(
            'label' => 'Results',
            'default' => 10,
            'option' => 'et_waitlists'
        );
        add_screen_option( $option, $args );
    }
    public function load_waitlists_wp_list_table() {
        require_once dirname(__FILE__) . '/admin/init.php';

        require_once dirname(__FILE__) . '/admin/table.php';
    }
    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.2.2
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}

$waitlist = new XStore_Waitlist;
$waitlist->init();