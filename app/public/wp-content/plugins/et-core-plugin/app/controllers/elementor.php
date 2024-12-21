<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;
use ETC\App\Controllers\Shortcodes\Products as Products_Shortcode;
use ETC\Views\Elementor as View;
use const Grpc\WRITE_BUFFER_HINT;

/**
 * Elementor initial class.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controller
 */
final class Elementor extends Base_Controller {

    /**
     * Minimum Elementor Version Supp
     *
     * @since 2.0.0
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

    /**
     * Registered modules.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $modules = NULL;

    /**
     * Registered widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $widgets = NULL;

    /**
     * Registered controls.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $controls = NULL;

    /**
     * Registered dynamic tags.
     *
     * @since 5.2
     *
     * @var array
     */
    public static $dynamic_tags = NULL;

    /**
     * Registered google_map_api.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $google_map_api = NULL;

    const MENU_CART_FRAGMENTS_ACTION = 'elementor-menu-cart-fragments';
    const MENU_WISHLIST_FRAGMENTS_ACTION = 'elementor-etheme-wishlist-fragments';

    const MENU_WAITLIST_FRAGMENTS_ACTION = 'elementor-etheme-waitlist-fragments';
    const MENU_COMPARE_FRAGMENTS_ACTION = 'elementor-etheme-compare-fragments';

    const WC_STATUS_PAGES_MAPPED_TO_WIDGETS = [
        'Cart' => 'woocommerce-cart-etheme_page',
        'Checkout' => 'woocommerce-checkout-etheme_page',
        'My account' => 'woocommerce-account-etheme_page',
    ];

    /**
     * Constructor
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function __construct() {
        // Register ajax

        add_action( 'wp_ajax_select2_control', array( $this, '_maybe_post_terms' ) );
        add_action( 'wp_ajax_nopriv_select2_control', array( $this, '_maybe_post_terms' ) );

        add_action( 'wp_ajax_et_advanced_tab', array( $this, 'et_advanced_tab' ) );
        add_action( 'wp_ajax_nopriv_et_advanced_tab', array( $this, 'et_advanced_tab' ) );

	    add_action( 'wp_ajax_etheme_elementor_lazy_load', array( $this, 'etheme_elementor_lazy_load' ) );
	    add_action( 'wp_ajax_nopriv_etheme_elementor_lazy_load', array( $this, 'etheme_elementor_lazy_load' ) );

        add_action( 'wp_ajax_etheme_elementor_dynamic_thumbnail', array( $this, 'etheme_elementor_dynamic_thumbnail' ) );
        add_action( 'wp_ajax_nopriv_etheme_elementor_dynamic_thumbnail', array( $this, 'etheme_elementor_dynamic_thumbnail' ) );

        add_action( 'wp_ajax_etheme_elementor_post_screenshot', array( $this, 'etheme_elementor_post_screenshot' ) );
        add_action( 'wp_ajax_nopriv_etheme_elementor_post_screenshot', array( $this, 'etheme_elementor_post_screenshot' ) );

        add_action( 'before_delete_post', array($this, 'etheme_elementor_screenshot_remover'), 10, 2 );

        add_action( 'plugins_loaded', array( $this, 'hooks' ) );

    }

    /**
     * Fired elementor options by `plugins_loaded` action hook.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function hooks() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Check for elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_version' ) );
            return;
        }

        $this->register_modules();

        // Register categories, widgets, controls
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
	    // before 3.5.0
		// add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	    // after 3.5.0 because 'elementor/widgets/widgets_registered' action became deprecated
	    add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	    add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );

//	    add_action('init', function () {
        if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            add_action('elementor/dynamic_tags/register', array($this, 'register_dynamic_tags'));
        }
//        });

        // Elementor editor

		// studio
        add_action( 'init', array( $this, 'enqueue_studio' ) );

	    // cross copy paste
	    add_action( 'init', array( $this, 'enqueue_cross_domain_cp' ) );

        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_styles' ) );
        // Enqueue front end js
        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'enqueue_scripts' ) );

        add_filter( 'elementor/icons_manager/native', array( $this, 'etc_elementor_icons' ) );
        add_filter( 'elementor/fonts/groups', array( $this, 'add_custom_font_group' ) );
        add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_custom_font' ) );


        add_filter('elementor_pro/frontend/localize_settings', [$this, 'localized_settings_frontend']);

        add_action( 'wp_ajax_etheme_elementor_menu_cart_fragments', [ $this, 'menu_cart_fragments' ] );
        add_action( 'wp_ajax_nopriv_etheme_elementor_menu_cart_fragments', [ $this, 'menu_cart_fragments' ] );

        add_action('woocommerce_add_to_cart_fragments', array($this, 'woocommerce_fragments'));

        add_filter('xstore_wishlist_refresh_fragments', array($this, 'wishlist_fragments'), 10, 3);

        add_filter('xstore_waitlist_refresh_fragments', array($this, 'waitlist_fragments'), 10, 3);

        add_filter('xstore_compare_refresh_fragments', array($this, 'compare_fragments'), 10, 3);

        // set WooCommerce pages as validated if built with XStore Elementor widgets
        add_filter( 'woocommerce_rest_prepare_system_status', array($this, 'prepare_wc_system_status'), 10, 3);

    }

    public function prepare_wc_system_status($response, $system_status, $request) {
        return $this->add_system_status_data( $response, $system_status, $request );
    }

    public function add_system_status_data( $response, $system_status, $request ) {
        foreach ( $response->data['pages'] as $index => $wc_page ) {
            $this->modify_response_if_widget_exists_in_page( $wc_page, $response, $index );
        }

        return $response;
    }

    private function modify_response_if_widget_exists_in_page( $wc_page, &$response, $index ) {
        if ( empty( $wc_page['page_name'] ) || empty( $wc_page['page_id'] ) || ! array_key_exists( $wc_page['page_name'], self::WC_STATUS_PAGES_MAPPED_TO_WIDGETS ) ) {
            return;
        }

        if ( isset( $wc_page['shortcode_present'] ) && false !== $wc_page['shortcode_present'] ) {
            return;
        }

        $document = \Elementor\Plugin::$instance->documents->get( $wc_page['page_id'] );

        if ( ! $document || ! $document->is_built_with_elementor() ) {
            return;
        }

        $elementor_data = get_post_meta( $wc_page['page_id'], '_elementor_data', true );
        $widget_name = self::WC_STATUS_PAGES_MAPPED_TO_WIDGETS[ $wc_page['page_name'] ];
        $widget_exists_in_page = false !== strpos( $elementor_data, $widget_name );

        if ( $widget_exists_in_page ) {
            $response->data['pages'][ $index ]['shortcode_present'] = true;
        }
    }

    //
    public function localized_settings_frontend( $settings ) {
        if ( !class_exists('WooCommerce') ) return $settings;
//        $has_cart = is_a( WC()->cart, 'WC_Cart' );
//
//        if ( $has_cart ) {
            $settings['woocommerce']['etheme_wishlist'] = [
                'fragments_nonce' => wp_create_nonce( self::MENU_WISHLIST_FRAGMENTS_ACTION ),
            ];
            $settings['woocommerce']['etheme_waitlist'] = [
                'fragments_nonce' => wp_create_nonce( self::MENU_WAITLIST_FRAGMENTS_ACTION ),
            ];
            $settings['woocommerce']['etheme_compare'] = [
                'fragments_nonce' => wp_create_nonce( self::MENU_COMPARE_FRAGMENTS_ACTION ),
            ];
//        }
        return $settings;
    }

    /**
     * Menu cart fragments.
     *
     * Ajax action to create fragments for the menu carts in a page.
     *
     * @return void
     */
    public function menu_cart_fragments() {
        $all_fragments = [];

        if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), str_replace(array('cart', 'wishlist', 'waitlist', 'compare'), array(self::MENU_CART_FRAGMENTS_ACTION, self::MENU_WISHLIST_FRAGMENTS_ACTION, self::MENU_WAITLIST_FRAGMENTS_ACTION, self::MENU_COMPARE_FRAGMENTS_ACTION), $_POST['nonce_type']) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            wp_send_json( [] );
        }

        $templates = \ElementorPro\Core\Utils::_unstable_get_super_global_value( $_POST, 'templates' );

        if ( ! is_array( $templates ) ) {
            wp_send_json( [] );
        }

        if ( 'true' === \ElementorPro\Core\Utils::_unstable_get_super_global_value( $_POST, 'is_editor' ) ) {
            \Elementor\Plugin::$instance->editor->set_edit_mode( true );
        }

        foreach ( $templates as $id ) {
            $this->get_all_fragments( $id, $all_fragments );
        }

        wp_send_json( [ 'fragments' => $all_fragments ] );
    }

    /**
     * Get All Fragments.
     *
     * @since 3.7.0
     *
     * @param $id
     * @param $all_fragments
     * @return void
     */
    public function get_all_fragments( $id, &$all_fragments ) {
        $fragments_in_document = $this->get_fragments_in_document( $id );

        if ( $fragments_in_document ) {
            $all_fragments += $fragments_in_document;
        }
    }

    /**
     * Get Fragments In Document.
     *
     * A general function that will return any needed fragments for a Post.
     *
     * @since 3.7.0
     * @access public
     *
     * @param int $id
     *
     * @return mixed $fragments
     */
    public function get_fragments_in_document( $id ) {
        $document = \Elementor\Plugin::$instance->documents->get( $id );

        if ( ! is_object( $document ) ) {
            return false;
        }

        $fragments = [];

        $data = $document->get_elements_data();

        \Elementor\Plugin::$instance->db->iterate_data(
            $data,
            $this->get_fragments_handler( $fragments )
        );

        return ! empty( $fragments ) ? $fragments : false;
    }

    /**
     * Get Fragments Handler.
     *
     * @since 3.7.0
     *
     * @param array $fragments
     * @return void
     */
    public function get_fragments_handler( array &$fragments ) {
        return function ( $element ) use ( &$fragments ) {
            if ( ! isset( $element['widgetType'] ) ) {
                return;
            }

            $fragment_data = $this->get_fragment_data( $element );
            $total_fragments = count( $fragment_data ) / 2;

            for ( $i = 0; $i < $total_fragments; $i++ ) {
                foreach ($fragment_data['selector'] as $selector_index => $selector) {
                    $fragments[ $fragment_data['selector'][ $selector_index ] ] = $fragment_data['html'][ $selector_index ];
                }
            }
        };
    }

    /**
     * Get Fragment Data.
     *
     * A function that will return the selector and HTML for WC fragments.
     *
     * @since 3.7.0
     * @access private
     *
     * @param array $element
     *
     * @return array $fragment_data
     */
    private function get_fragment_data( $element ) {
        $fragment_data = [];

        switch ($element['widgetType']) {
            case 'theme-etheme_compare':
                if ( isset($_POST['nonce_type']) && $_POST['nonce_type'] == 'compare' ) {
                    $items_count = 3;
                    $compare_instance = \XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance();
                    $compare = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Compare::get_instance();
                    ob_start();
                    if (isset($_POST['products']) && count($_POST['products']) > 0) {
                        if (isset($element['settings']['items_count']) && !!$element['settings']['items_count'])
                            $items_count = $element['settings']['items_count'];
                        add_filter('etheme_mini_compare_items_count', function ($value) use ($items_count) {
                            return $items_count !== false ? $items_count : $value;
                        });
                        $compare_instance->header_mini_compare_products(
                            array_map(
                                function ($_product) {
                                    return (array)json_decode(stripcslashes($_product));
                                },
                                $_POST['products']));
                    } else {
                        $compare->render_empty_content($element['settings']);
                    }
                    $fragment_data['html'][] = '<div class="et_b_compare-dropdown product_list_widget cart_list">' . ob_get_clean() . '</div>';

                    $fragment_data['selector'][] = 'div.elementor-element-' . $element['id'] . ' .product_list_widget';
                    unset($compare);
                }
                break;
            case 'theme-etheme_wishlist':
                if ( isset($_POST['nonce_type']) && $_POST['nonce_type'] == 'wishlist' ) {
                    $items_count = 3;
                    $wishlist_instance = \XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
                    $wishlist = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Wishlist::get_instance();
                    ob_start();
                    if (isset($_POST['products']) && count($_POST['products']) > 0) {
                        if (isset($element['settings']['items_count']) && !!$element['settings']['items_count'])
                            $items_count = $element['settings']['items_count'];
                        add_filter('etheme_mini_wishlist_items_count', function ($value) use ($items_count) {
                            return $items_count !== false ? $items_count : $value;
                        });
                        $wishlist_instance->header_mini_wishlist_products(
                            array_map(
                                function ($_product) {
                                    return (array)json_decode(stripcslashes($_product));
                                },
                                $_POST['products']));
                    } else {
                        $wishlist->render_empty_content($element['settings']);
                    }
                    $fragment_data['html'][] = '<div class="et_b_wishlist-dropdown product_list_widget cart_list">' . ob_get_clean() . '</div>';

                    $fragment_data['selector'][] = 'div.elementor-element-' . $element['id'] . ' .product_list_widget';
                    unset($wishlist);
                }
                break;
            case 'theme-etheme_waitlist':
                if ( isset($_POST['nonce_type']) && $_POST['nonce_type'] == 'waitlist' ) {
                    $items_count = 3;
                    $waitlist_instance = \XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();
                    $waitlist = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Waitlist::get_instance();
                    ob_start();
                    if (isset($_POST['products']) && count($_POST['products']) > 0) {
                        if (isset($element['settings']['items_count']) && !!$element['settings']['items_count'])
                            $items_count = $element['settings']['items_count'];
                        add_filter('etheme_mini_waitlist_items_count', function ($value) use ($items_count) {
                            return $items_count !== false ? $items_count : $value;
                        });
                        $waitlist_instance->header_mini_waitlist_products(
                            array_map(
                                function ($_product) {
                                    return (array)json_decode(stripcslashes($_product));
                                },
                                $_POST['products']));
                    } else {
                        $waitlist->render_empty_content($element['settings']);
                    }
                    $fragment_data['html'][] = '<div class="et_b_waitlist-dropdown product_list_widget cart_list">' . ob_get_clean() . '</div>';

                    $fragment_data['selector'][] = 'div.elementor-element-' . $element['id'] . ' .product_list_widget';
                    unset($waitlist);
                }
                break;
            case 'theme-etheme_cart':
                if ( isset($_POST['nonce_type']) && $_POST['nonce_type'] == 'cart' ) {
                    $cart = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Cart::get_instance();
                    $items_count = 3;
                    $show_quantity = true; // default value in widget settings
                    if (isset($element['settings']['show_product_quantity_input']))
                        $show_quantity = !!$element['settings']['show_product_quantity_input'];
                    $linked_products = false;
                    if (isset($element['settings']['linked_products']))
                        $linked_products = $element['settings']['linked_products'];
                    if (isset($element['settings']['items_count']) && !!$element['settings']['items_count'])
                        $items_count = $element['settings']['items_count'];
                    add_filter('etheme_mini_cart_items_count', function ($value) use ($items_count) {
                        return $items_count !== false ? $items_count : $value;
                    });
                    $filters = array(
                        'etheme_mini_cart_quantity_input' => ($show_quantity ? '__return_true' : '__return_false'),
                        'etheme_mini_cart_linked_products_force_display' => ($linked_products ? '__return_true' : '__return_false')
                    );
                    if ($linked_products) {
                        add_filter('etheme_mini_cart_linked_products_type', function ($old_value) use ($linked_products) {
                            return $linked_products;
                        });
                    }
                    foreach ($filters as $filter_key => $filter_value) {
                        add_filter($filter_key, $filter_value);
                    }

                    ob_start();
                    if (WC()->cart->is_empty()) {
                        $cart->render_empty_content($element['settings']);
                    } else {
                        woocommerce_mini_cart();
                    }
                    $fragment_data['html'][] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';
                    foreach ($filters as $filter_key => $filter_value) {
                        remove_filter($filter_key, $filter_value);
                    }
                    $fragment_data['selector'][] = 'div.elementor-element-' . $element['id'] . ' div.widget_shopping_cart_content';
                    unset($cart);
                }
                break;
            default:
                break;
        }

//        if ( 'woocommerce-menu-cart' === $element['widgetType'] ) {
//            ob_start();
//            self::render_menu_cart_toggle_button( $element['settings'] );
//            $fragment_data['html'][] = ob_get_clean();
//
//            $fragment_data['selector'][] = 'div.elementor-element-' . $element['id'] . ' div.elementor-menu-cart__toggle';
//        }

        return $fragment_data;
    }
    //

    public function woocommerce_fragments($cart_fragments) {
        $cart_widget_selector = '.elementor-widget-theme-etheme_cart';
        $mobile_menu_cart_widget_selector = '.elementor-widget-theme-etheme_mobile_menu .etheme-elementor-mobile-menu-cart';
        $product_count = WC()->cart->get_cart_contents_count();
        $instance = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Cart::get_instance();
        ob_start();
        $instance->render_icon_qty($product_count);
        $product_count_html = ob_get_clean();
        $cart_fragments[$cart_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;
        $cart_fragments[$mobile_menu_cart_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;
        ob_start();
        $instance->render_subtotal();
        $cart_fragments[$cart_widget_selector . ' .etheme-elementor-off-canvas-total-inner'] = ob_get_clean();
        if ( ! WC()->cart->is_empty() ) :
            ob_start();
            $instance->render_main_content_prefooter_total_inner_area($product_count);
            $cart_fragments[$cart_widget_selector . ' .cart-popup-footer'] = ob_get_clean();

            // to prevent overlapping default WC fragments and own one
//            ob_start();
//            $instance->render_processing_state();
//            $cart_fragments[$cart_widget_selector . ' div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';

//            // tweak for making separated refresh for product quantity input included/excluded widgets (made with prefix-class)
//            ob_start();
//            add_filter('etheme_mini_cart_quantity_input', '__return_true');
//                woocommerce_mini_cart();
//            remove_filter('etheme_mini_cart_quantity_input', '__return_true');
//            $cart_fragments[$cart_widget_selector . '.etheme-elementor-off-canvas-products-has-quantity-input div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';
//            ob_start();
//            add_filter('etheme_mini_cart_quantity_input', '__return_false');
//                woocommerce_mini_cart();
//            remove_filter('etheme_mini_cart_quantity_input', '__return_false');
//            $cart_fragments[$cart_widget_selector . ':not(.etheme-elementor-off-canvas-products-has-quantity-input) div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';
        else:
//            // output loader while we check for correct empty cart content for each cart widget and replace it then
//            ob_start();
//            $instance->render_processing_state(); // show empty content because we need time for parsing each cart-widget with options and replace with own empty content
//            $cart_fragments[$cart_widget_selector . ' div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';
        endif;
        return $cart_fragments;
    }

    public function compare_fragments($compare_fragments, $product_count, $products_html_list) {
        $compare_widget_selector = '.elementor-widget-theme-etheme_compare';
        $mobile_menu_compare_widget_selector = '.elementor-widget-theme-etheme_mobile_menu .etheme-elementor-mobile-menu-compare';
        $instance = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Compare::get_instance();
        ob_start();
        $instance->render_icon_qty($product_count);
        $product_count_html = ob_get_clean();
        $compare_fragments[$compare_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;
        $compare_fragments[$mobile_menu_compare_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;

        if ( $product_count > 0 )
            $compare_fragments[$compare_widget_selector . ' .product_list_widget'] = $compare_fragments['.et_b_compare-dropdown'];
        else
            $compare_fragments[$compare_widget_selector . ' .product_list_widget'] = '<div class="et_b_compare-dropdown product_list_widget cart_list"><div class="etheme-elementor-off-canvas_content-empty-message empty">'.$compare_fragments['.et_b_compare-dropdown'].'</div></div>';
        return $compare_fragments;
    }
    public function wishlist_fragments($wishlist_fragments, $product_count, $products_html_list) {
        $wishlist_widget_selector = '.elementor-widget-theme-etheme_wishlist';
        $mobile_menu_wishlist_widget_selector = '.elementor-widget-theme-etheme_mobile_menu .etheme-elementor-mobile-menu-wishlist';
        $instance = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Wishlist::get_instance();
        ob_start();
        $instance->render_icon_qty($product_count);
        $product_count_html = ob_get_clean();
        $wishlist_fragments[$wishlist_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;
        $wishlist_fragments[$mobile_menu_wishlist_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;

        // keep default products list but later we will replace it according to the settings of Elementor wishlist element
        if ( $product_count > 0 )
            $wishlist_fragments[$wishlist_widget_selector . ' .product_list_widget'] = $wishlist_fragments['.et_b_wishlist-dropdown'];
        else
            $wishlist_fragments[$wishlist_widget_selector . ' .product_list_widget'] = '<div class="et_b_wishlist-dropdown product_list_widget cart_list"><div class="etheme-elementor-off-canvas_content-empty-message empty">'.$wishlist_fragments['.et_b_wishlist-dropdown'].'</div></div>';
        return $wishlist_fragments;
    }

    public function waitlist_fragments($waitlist_fragments, $product_count, $products_html_list) {
        $waitlist_widget_selector = '.elementor-widget-theme-etheme_waitlist';
        $mobile_menu_waitlist_widget_selector = '.elementor-widget-theme-etheme_mobile_menu .etheme-elementor-mobile-menu-waitlist';
        $instance = \ETC\App\Controllers\Elementor\Theme_Builder\Header\Wishlist::get_instance();
        ob_start();
        $instance->render_icon_qty($product_count);
        $product_count_html = ob_get_clean();
        $waitlist_fragments[$waitlist_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;
        $waitlist_fragments[$mobile_menu_waitlist_widget_selector . ' .elementor-button-icon-qty'] = $product_count_html;

        // keep default products list but later we will replace it according to the settings of Elementor waitlist element
        if ( $product_count > 0 )
            $waitlist_fragments[$waitlist_widget_selector . ' .product_list_widget'] = $waitlist_fragments['.et_b_waitlist-dropdown'];
        else
            $waitlist_fragments[$waitlist_widget_selector . ' .product_list_widget'] = '<div class="et_b_waitlist-dropdown product_list_widget cart_list"><div class="etheme-elementor-off-canvas_content-empty-message empty">'.$waitlist_fragments['.et_b_waitlist-dropdown'].'</div></div>';
        return $waitlist_fragments;
    }

    public function enqueue_studio(){
        if ( get_theme_mod( 'etheme_studio_on', 1) ){
            require_once( ET_CORE_DIR . 'app/models/studio/studio.php' );
        }
    }

	/**
	 * Cross Domain Copy Paste.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_cross_domain_cp(){
        // based on XStudio option because in most cases it is used in common
        if ( get_theme_mod( 'etheme_studio_on', 1) ) {
            require_once(ET_CORE_DIR . 'app/models/cross-domain-cp/cross-domain-cp.php');
        }
	}

    /**
     * Register widget args
     *
     * @return mixed|null|void
     */
    public static function module_args() {

        if ( ! is_null( self::$modules ) ) {
            return self::$modules;
        }

        return self::$modules = apply_filters( 'etc/add/elementor/modules', array() );
    }

    /**
     * Register widget args
     *
     * @return mixed|null|void
     */
    public static function widgets_args() {

        if ( ! is_null( self::$widgets ) ) {
            return self::$widgets;
        }

        return self::$widgets = apply_filters( 'etc/add/elementor/widgets', array() );
    }

    /**
     * Register controls args
     *
     * @return mixed|null|void
     */
    public static function controls_args() {

        if ( ! is_null( self::$controls ) ) {
            return self::$controls;
        }

        return self::$controls = apply_filters( 'etc/add/elementor/controls', array() );
    }

    /**
     * Register dynamic tags args
     *
     * @return mixed|null|void
     */
    public static function dynamic_tags_args() {

        if ( ! is_null( self::$dynamic_tags ) ) {
            return self::$dynamic_tags;
        }

        return self::$dynamic_tags = apply_filters( 'etc/add/elementor/dynamic_tags', array() );
    }

    /**
     * Admin notice when minimum required Elementor version not activating.
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function admin_notice_version() {

	    $view = new View;
	    $view->elementor_version_requirement(
            array(
                'error_message' => sprintf(esc_html__( 'Your Elementor version is too old, Please update your Elementor plugin to at least %s Version', 'xstore-core' ), self::MINIMUM_ELEMENTOR_VERSION ),
            )
        );

    }

    /**
     * Add eight theme Widgets Category
     *
     * @since 2.0.0
     */
    function register_categories( $categories_manager ) {
	    $categories_manager->add_category(
            'eight_theme_general',
            array(
                'title' => sprintf(__( '%s Widgets', 'xstore-core' ), apply_filters('etheme_theme_label', 'XStore')) . ' (60)',
                'icon' => 'fa fa-plug',
            )
        );

	    $categories_manager->add_category(
		    'eight_theme_deprecated',
		    array(
			    'title' => sprintf(__( '%s Widgets (deprecated)', 'xstore-core' ), apply_filters('etheme_theme_label', 'XStore')),
			    'icon' => 'fa fa-plug',
		    )
	    );

    }

    /**
     * Ajax select2 control handler
     *
     * @since 2.3.9
     * @return object
     */
    function _maybe_post_terms() {

        check_ajax_referer( 'select2_ajax_control', 'security' );

        if ( isset( $_POST['options']['post_type'] ) ) {
            $return = $this->process_post_ajax_select_control();
        }

        wp_send_json( $return );
    }

    /**
     * Get post type for ajax select2 control
     *
     * @since 2.3.9
     * @return return posttype
     */
    function process_post_ajax_select_control() {

        $return = array();

        $args = array(
            'post_status'           => 'publish',
            'post_type'             => $_POST['options']['post_type'],
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => apply_filters('etheme_elementor_post_ajax_results_per_page', 20, $_POST['options']['post_type'])
        );

        // Search
        if ( isset( $_POST['search'] ) && '' != $_POST['search'] ) {
            $args['s'] =  sanitize_text_field( $_POST['search'] );

            $search = $this->_post_get_data_select_control( $args );

            unset( $args['s'] );
        }

        // Get selected id
        if ( isset( $_POST['id'] ) ) {
            $args['post__in'] =  $_POST['id'];

            $selected = $this->_post_get_data_select_control( $args );
        }

        // Get old options again
        if ( isset( $_POST['old_option'] ) && '' != $_POST['old_option'] ) {
            $args['post__in'] =  $_POST['old_option'] ;

            $old_option = $this->_post_get_data_select_control( $args );
        }

        if ( isset( $selected ) ) {
            return $selected;
        }

        if ( isset( $old_option ) && is_array( $old_option ) && isset( $search ) && is_array( $search ) ) {
            return $old_option + $search;
        } elseif ( isset( $search ) && is_array( $search ) ) {
            return $search;
        } elseif ( isset( $old_option ) && is_array( $old_option ) ) {
            return $old_option;
        }

    }

    protected function _post_get_data_select_control( $args ) {
        $return = array();

        $search_results = new \WP_Query( $args );

        if( $search_results->have_posts() ) :

            while( $search_results->have_posts() ) : $search_results->the_post();

                $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;

                $return[$search_results->post->ID] = $title . ' (id - ' . $search_results->post->ID . ')' .
                     (( $search_results->post->post_type == 'product_variation' ) ? ' ' . esc_html__('variation', 'xstore-core') : '' );

            endwhile;

            wp_reset_postdata();

        endif;

        return $return;
    }

    /**
     * Advanced tabs widget
     *
     * @since 2.3.9
     * @return return tab content
     */
    function et_advanced_tab() {
        // check nonce
    	check_ajax_referer( 'etheme_advancedtabnonce', 'security' );
        // sanitizing
    	$tab_id    = isset( $_POST['tabid'] )    ? sanitize_key( $_POST['tabid'] )    : null;
    	$data_json = isset( $_POST['tabjson'] )  ? $_POST['tabjson']   : null;

        // simple check
    	if ( null === $tab_id ) {
    		wp_send_json_error( array( 'Do not change html via inspect element :)' ) );
    	}

        // Json data
    	if ( is_string( $data_json ) && ! empty( $data_json ) ) {
    		$data_json = json_decode( wp_unslash( $data_json ) , true );
    	}

    	$view = new View;
    	$Products_Shortcode = Products_Shortcode::get_instance();

    	$out = $view->advanced_tabs_ajax(
    		array(
    			'tabs'  				=> $data_json,
    			'Products_Shortcode'  	=> $Products_Shortcode,
    			'is_preview'  			=> ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ),
    		)
    	);

        echo $out;

        exit();

    }

    function etheme_elementor_lazy_load() {
	    check_ajax_referer( 'etheme_'.$_POST['widgetType'].'_nonce', 'security' );

	    $settings = json_decode( wp_unslash( $_POST['query'] ) , true );
	    $params_extra = array();

	    if ( isset($_POST['attr']['offset']) )
		    $params_extra['offset'] = $_POST['attr']['offset'] + ( ( $_POST['attr']['paged'] - 1 ) * $_POST['attr']['posts-per-page'] );

	    $query_args = array_merge(array(
		    'ignore_sticky_posts' => 1,
		    'no_found_rows' => 1,
		    'paged' => $_POST['attr']['paged'],
		    'posts_per_page' => $_POST['attr']['posts-per-page'],
	    ), $params_extra);

	    $instance = false;
	    switch ($_POST['widgetType']) {
            case 'product-list':
	            $instance = \ETC\App\Controllers\Elementor\General\Product_List::get_instance();
	            $posts = \ETC\App\Controllers\Elementor\General\Product_List::get_query($settings, $query_args);
                break;
		    case 'product-grid':
			    $instance = \ETC\App\Controllers\Elementor\General\Product_Grid::get_instance();
			    $posts = \ETC\App\Controllers\Elementor\General\Product_Grid::get_query( $settings, $query_args);
			    break;
		    case 'posts':
		    case 'posts-tabs':
			    $instance = \ETC\App\Controllers\Elementor\General\Posts::get_instance();

			    $posts = \ETC\App\Controllers\Elementor\General\Posts::get_query( $settings, $query_args);
			    break;
		    case 'posts-chess':
			    $instance = \ETC\App\Controllers\Elementor\General\Posts_Chess::get_instance();
			    $posts = \ETC\App\Controllers\Elementor\General\Posts_Chess::get_query( $settings, $query_args);
			    break;
            case 'posts-timeline':
                $instance = \ETC\App\Controllers\Elementor\General\Posts_Timeline::get_instance();
                $posts = \ETC\App\Controllers\Elementor\General\Posts_Timeline::get_query( $settings, $query_args);
                break;
        }

        if ( !$instance ) return;

        if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
	        wc_set_loop_prop( 'columns', 4 );
	        wc_set_loop_prop( 'etheme_elementor_product_widget', true );
	        wc_set_loop_prop( 'is_shortcode', true );
        }

        $data = [];
        $_i=0;

	    $new_limit = isset($_POST['attr']['limit']) ? $_POST['attr']['limit'] : 0;
	    if ( $_POST['attr']['paged'] > 1 ) {
		    $loaded_posts = ($_POST['attr']['paged'] - 1) * $_POST['attr']['posts-per-page'];
		    if ( $new_limit > $loaded_posts ) {
			    $new_limit = $new_limit - $loaded_posts;
		    }
	    }

        ob_start();
	    if ( $posts && $posts->have_posts() ) {

		    while ( $posts->have_posts() ) {
			    $posts->the_post();

//			    if ( isset( $_POST['attr']['limit'] ) ) {
//				    if ( $_i >= $_POST['attr']['limit'] ) {
//					    break;
//				    }
//				    $_i++;
//			    }
			    if ( $new_limit > 0 ) {
				    if ( $_i >= $new_limit ) {
					    break;
				    }
				    $_i++;
			    }
                $local_settings = json_decode( wp_unslash( $_POST['postSettings'] ), true );
			    if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
				    $instance->get_content_product($local_settings);
			    }
			    else {
                    if ( $_POST['widgetType'] == 'posts-timeline' )
                        $instance->get_content_post_wrapper($local_settings, \Elementor\Icons_Manager::is_migration_allowed() );
                    else
                        $instance->get_content_post($local_settings );
                }
		    }
	    }

	    else {
		    echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
		         esc_html__('No items were found matching your selection.', 'xstore-core') .
		         '</div>';
	    }

	    if ( in_array($_POST['widgetType'], array('product-list', 'product-grid')) ) {
		    wc_reset_loop();
	    }

	    $data['content'] = json_encode(ob_get_clean());

	    if ( $_POST['loading_type'] == 'pagination' ) {
	    	ob_start();
		    $is_rtl = is_rtl();
		    $left_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
		                  '<path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>' .
		                  '</svg>';
		    $right_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
		                   '<path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>' .
		                   '</svg>';

		    echo paginate_links( array(
                 'base'      => esc_url_raw( add_query_arg( 'etheme-'.$_POST['widgetType'].'-'.$_POST['widgetId'].'-page', '%#%', $_POST['permalink'] ) ),
			    'format'    => '?etheme-'.$_POST['widgetType'].'-' . $_POST['widgetId'] . '-page=%#%',
			    'add_args'  => false,
			    'current'   => $_POST['attr']['paged'],
			    'total'     => $_POST['totalPages'],
			    'prev_text' => $is_rtl ? $right_arrow : $left_arrow,
			    'next_text' => $is_rtl ? $left_arrow : $right_arrow,
			    'type'      => 'list',
			    'end_size'  => 2,
			    'mid_size'  => 2,
		    ) );
		    $data['pagination'] = json_encode(ob_get_clean());
	    }

	    wp_reset_postdata();

	    wp_send_json($data);
	    // die();
    }

    function etheme_elementor_dynamic_thumbnail() {
        check_ajax_referer( 'etheme_'.$_POST['postType'].'_nonce', 'security' );

        $response = array('status' => 'error');
        if ( post_type_supports( $_POST['postType'], 'thumbnail' ) ) {
            if ( $_POST['setThumbnail'] == 'yes' ) {
                set_post_thumbnail( $_POST['postId'], $_POST['thumbnailId'] );
            }
            else {
                delete_post_thumbnail( $_POST['postId'] );
            }
            $response['status'] = 'success';
        }
        wp_send_json($response);
    }

    function etheme_elementor_post_screenshot() {
        $response = array('status' => 'error');
        if (isset($_POST['image'])) {
            $uploads = wp_get_upload_dir();
            $screenshot = $uploads['basedir']. '/xstore/'.sanitize_file_name($_POST['postType']).'-'.sanitize_file_name($_POST['postId']).'screenshot.json';
            // should have read and write permission to the disk to write the JSON file
            if ( $screenshotJson = fopen($screenshot, "a") ) {
                $screenshotImage = array(
                    'imageURL' => $_POST['image']
                );
                $contentArray = $screenshotImage;
                $fullData = json_encode($contentArray);
                file_put_contents($screenshot, $fullData);
                fclose($screenshotJson);
                $response = array('status' => 'success');
            }
        }
        wp_send_json($response);
    }

    /**
     * Unlick screenshot file attached to Slide {id} of etheme_slides post-type
     * @param $postID
     * @return void
     */
    function etheme_elementor_screenshot_remover($postID, $post) {
        $postType = $post->post_type;
        if (!in_array($postType, array('etheme_slides', 'etheme_mega_menus'))) return;

        $uploads = wp_get_upload_dir();
        $upload_dir = $uploads['basedir'];

        $screenshot = $upload_dir. '/xstore/'.$postType.'-'.$postID.'screenshot.json';
        if ( file_exists($screenshot) )
            unlink($screenshot);
    }

    /**
     * Include modules
     *
     * @since 2.0.0
     *
     * @access public
     */
    public function register_modules() {

        $modules = self::module_args();
        foreach ( $modules as $module ) {
            foreach ( $module as $class ) {
                new $class();
            }
        }

    }

    /**
     * Include widgets files and register them
     *
     * @since 2.0.0
     * @log last changes in 4.3.1 - since Elementor 3.5.0 uses register instead of register_widget_type
     *
     * @access public
     */
    public function register_widgets( $widgets_manager ) {

        $args = self::widgets_args();
        foreach ( $args as $widget_classes ) {
            foreach ( $widget_classes as $class ) {
	            $widgets_manager->register( new $class() );
            }
        }

    }

    /**
     * Register controls
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls( $controls_manager ) {

        $args = self::controls_args();
        foreach ( $args as $arg ) {
            foreach ( $arg as $control ) {
                $controls_manager->register( new $control() );
            }
        }
    }

    public function register_dynamic_tags( $dynamic_tags ) {

        $args = self::dynamic_tags_args();

        /** @var \Elementor\Core\DynamicTags\Manager $module */
        $module = \Elementor\Plugin::$instance->dynamic_tags;

        foreach ( $args as $arg ) {
            foreach ( $arg as $dynamic_tag ) {
                $module->register(new $dynamic_tag() );
            }
        }

    }

    public function etc_elementor_icons( $icons_library ) {

        $icons_library['xstore-icons'] = [
            'name' => 'xstore-icons',
            'label' => __( 'XStore Icons', 'xstore-core' ),
            'url' => self::get_et_asset_url( ( get_theme_mod('bold_icons', 0) ? 'bold' : 'light') ),
            'enqueue' => [ self::get_et_asset_url( 'xstore-icons' ) ],
            'prefix' => 'et-',
            'displayPrefix' => 'et-icon',
            'labelIcon' => 'et-icon et-star-o',
            'ver' => '1.4.2',
            'fetchJson' => self::get_et_asset_url( 'light', 'js', false ),
            'native' => true,
        ];

        return $icons_library;
    }

    private static function get_et_asset_url( $filename, $ext_type = 'css', $add_suffix = true ) {
        // static $is_test_mode = null;
        // if ( null === $is_test_mode ) {
        //     $is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS;
        // }
        $url = ET_CORE_URL . 'app/assets/lib/xstore-icons/' . $ext_type . '/' . $filename;
        // if ( ! $is_test_mode && $add_suffix ) {
        //     $url .= '.min';
        // }
        return $url . '.' . $ext_type;
    }

    /**
     * Add xstore custom fonts group
     *
     * @since    2.5.0
     * @param new fonts
     */
    public function add_custom_font_group( $font_groups ) {
        // Get available fonts
        $uploaded_fonts = get_option( 'etheme-fonts', false );

        if ( $uploaded_fonts ) {
            $new_group =  array(
                'xstore' => 'XStore Fonts',
            );
            $font_groups = array_merge( $font_groups, $new_group );
        }

        $uploaded_fonts = get_theme_mod( 'etheme_typekit_fonts', '' );

        if ( $uploaded_fonts ) {
            $new_group =  array(
                'xstore-adobe' => 'Adobe Fonts',
            );
            $font_groups = array_merge( $font_groups, $new_group );
        }

        return $font_groups;
    }

    /**
     * add xstore custom font
     *
     * @since    2.5.0
     */
    public function add_custom_font( $additional_fonts ) {
        $uploaded_fonts = get_option( 'etheme-fonts', false );
        $has_etheme_fonts = false;
        $new_fonts = array();

        if ( false == $uploaded_fonts || is_null( $uploaded_fonts ) ) {

        }
        else {
            $has_etheme_fonts = true;
        }

        if ( $has_etheme_fonts ) {
            foreach ($uploaded_fonts as $font) {
                $new_fonts[$font['name']] = 'xstore';
            }
        }

        $etheme_typekit_fonts = get_theme_mod( 'etheme_typekit_fonts', '' );
        if ( $etheme_typekit_fonts ) {
            $typekit = explode( ',', $etheme_typekit_fonts );
            foreach ( $typekit as $font ) {
                $new_fonts[ trim( $font ) ] = 'xstore-adobe';
            }
        }

        return array_merge( $additional_fonts ,$new_fonts );
    }

    /**
     * Register the stylesheets for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_editor_styles() {

        wp_enqueue_style(
            'et-core-elementor-style',
            ET_CORE_URL . 'app/assets/css/elementor-editor.css',
            array(),
            ET_CORE_VERSION,
            'all'
        );

        wp_add_inline_style( 'et-core-elementor-style', '
            .elementor-panel .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after {
                content: "'.apply_filters('etheme_theme_label', 'XStore').'";
                position: absolute;
                top: 5px;
                left: 5px;
                background: var(--et-elementor-widget-icon-bg-color, #000);
                color: var(--et-elementor-widget-icon-color, #fff);
                font-size: 8.5px;
                line-height: 1;
                font-family: var(--e-a-font-family);
                padding: 4px 6px;
                border-radius: 3px;
                opacity: 0;
                visibility: hidden;
                transition: all .3s linear;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .elementor-panel .elementor-element:hover .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after {
                opacity: 1;
                visibility: visible;
            }
            .elementor-editor-active:not(.elementor-editor-product-archive) .elementor-panel .elementor-element:has(.et-elementor-product-builder-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after,
            .elementor-editor-active:not(.elementor-editor-archive) .elementor-panel .elementor-element:has(.et-elementor-archive-builder-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after,
            .elementor-editor-active:not(.elementor-editor-header) .elementor-panel .elementor-element:has(.et-elementor-header-builder-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after,
            .elementor-editor-active:not(.elementor-editor-single-post) .elementor-panel .elementor-element:has(.et-elementor-post-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after,
            .elementor-editor-active:not(.elementor-editor-product) .elementor-panel .elementor-element:has(.et-elementor-product-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after,
             .elementor-editor-active:not(.elementor-editor-search-results) .elementor-panel .elementor-element:has(.et-elementor-search-results-widget-icon-only) .eight_theme-elementor-icon:not(.eight_theme-elementor-deprecated):after {
                content: "'.esc_html__('Locked', 'xstore-core').'";
                opacity: 1;
                visibility: visible;
            }');

        if ( !apply_filters('etheme_elementor_cart_new_widgets', false) ) {
            wp_add_inline_style( 'et-core-elementor-style', '.elementor-panel .elementor-element-wrapper:has(.et-elementor-cart-builder-new-widget-icon) {display: none}');
        }
        if ( !apply_filters('etheme_elementor_checkout_new_widgets', false) ) {
            wp_add_inline_style( 'et-core-elementor-style', '.elementor-panel .elementor-element-wrapper:has(.et-elementor-checkout-builder-new-widget-icon) {display: none}');
        }

        // hide Slideshow Elementor widget in editor of Slides post type
        $post_type = get_post_type();
        if ( $post_type == 'etheme_slides' ) {
            wp_add_inline_style( 'et-core-elementor-style', '.elementor-panel .elementor-element-wrapper:has(.et-elementor-carousel-anything) {display: none}');
        }

        $ui_theme_selected = \Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

        $ui_themes = [
            'light',
            'dark',
        ];

        if ( 'auto' === $ui_theme_selected || ! in_array( $ui_theme_selected, $ui_themes, true ) ) {
            $ui_light_theme_media_queries = '(prefers-color-scheme: light)';
            $ui_dark_theme_media_queries = '(prefers-color-scheme: dark)';
            wp_add_inline_style( 'et-core-elementor-style', '@media ' . $ui_light_theme_media_queries . ' { 
                .elementor-panel {
                    --et-elementor-widget-icon-bg-color: #000;
                    --et-elementor-widget-icon-color: #fff;
                }
                body #et-studio-modal .et_popup-notice{
                    background: #fff;
                }
            }');
            wp_add_inline_style( 'et-core-elementor-style', '@media ' . $ui_dark_theme_media_queries . ' { 
                .elementor-panel {
                    --et-elementor-widget-icon-bg-color: #fff;
                    --et-elementor-widget-icon-color: #000;
                }
               body #et-studio-modal .et_popup-notice, body #et-studio-modal .et_popup-notice a.a-black{
                    color: #fff!important;
                    background: #000;
                }
            }');
        } else {

            if ( 'light' === $ui_theme_selected ) {
                wp_add_inline_style('et-core-elementor-style', ' 
                    .elementor-panel {
                        --et-elementor-widget-icon-bg-color: #000;
                        --et-elementor-widget-icon-color: #fff;
                    }
                    body #et-studio-modal .et_popup-notice{
	                    background: #fff;
	                }
                ');
            }
            elseif ('dark' === $ui_theme_selected) {
                wp_add_inline_style('et-core-elementor-style', ' 
                    .elementor-panel {
                        --et-elementor-widget-icon-bg-color: #fff;
                        --et-elementor-widget-icon-color: #000;
                    }
                    body #et-studio-modal .et_popup-notice, body #et-studio-modal .et_popup-notice a.a-black{
	                    color: #fff!important;
	                    background: #000;
	                }
                ');
            }
        }

        wp_enqueue_style(
            'et-core-eight_theme-elementor-icon',
            ET_CORE_URL . 'app/assets/css/eight_theme-elementor-icon.css',
            array(),
            ET_CORE_VERSION,
            'all'
        );

//        if ( get_theme_mod( 'google_map_api', '' ) ) {
            $this->google_map_api = get_theme_mod( 'google_map_api', '' );
//        }

        if( $this->google_map_api != '' ) {
            $url = 'https://maps.googleapis.com/maps/api/js?key=' . $this->google_map_api . '&language=' . get_locale().'&libraries=marker';
//        } else {
//            $url = 'https://maps.googleapis.com/maps/api/js?language='.get_locale().'&libraries=marker';
//        }

            wp_enqueue_script(
                'etheme-google-map-admin-api',
                $url,
                ['elementor-editor'],
                ET_CORE_VERSION,
                true
            );

            wp_enqueue_script(
                'etheme-google-map-admin',
                ET_CORE_URL . 'app/assets/js/google-map-admin.js',
                array('etheme-google-map-admin-api'),
                ET_CORE_VERSION,
                true
            );

        }

        wp_enqueue_script(
            'et-elementor-editor',
            ET_CORE_URL . 'app/assets/js/editor-before.js',
            array(),
            ET_CORE_VERSION
        );

        wp_localize_script(
            'et-elementor-editor',
            'etElementorEditorConfig',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'studioDarkLightMode' => get_option('et_studio_dark_light_default', 'dark')
            )
        );

        if ( in_array($post_type, array('etheme_slides', 'etheme_mega_menus')) ) {
            wp_enqueue_script(
                'et-elementor-html2canvas',
                ET_CORE_URL . 'app/assets/js/libs/html2canvas.min.js',
                array(),
                '1.4.1'
            );
            wp_enqueue_script(
                'et-elementor-editor-'.$post_type,
                ET_CORE_URL . 'app/assets/js/editor-before-etheme_slides.min.js',
                array(),
                ET_CORE_VERSION
            );
            wp_localize_script(
                'et-elementor-editor-'.$post_type,
                'etElementorEditorSlidesConfig',
                array(
                    'screenshot' => array(
                        'postType' => $post_type,
                        'process' => sprintf(esc_html__('Please wait while we generate a screenshot for %s %s panel.', 'xstore-core'),
                            apply_filters('etheme_theme_label', 'XStore'),
                            str_replace(array('etheme_slides', 'etheme_mega_menus'), array(esc_html__('Slides', 'xstore-core'), esc_html__('Mega menus', 'xstore-core')), $post_type)),
                        'success' => esc_html__('Screenshot created.', 'xstore-core'),
                        'error' => esc_html__('The was an error while creating screenshot.', 'xstore-core'),
                    )
                )
            );
        }

        // icons
        // wp_enqueue_script(
        //     'font-awesome-4-shim',
        //     self::get_fa_asset_url( 'v4-shims', 'js' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );
        // wp_enqueue_style(
        //     'font-awesome-5-all',
        //     self::get_fa_asset_url( 'all' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );
        // wp_enqueue_style(
        //     'font-awesome-4-shim',
        //     self::get_fa_asset_url( 'v4-shims' ),
        //     [],
        //     ELEMENTOR_VERSION
        // );

    }

    public function check_location_be_loaded($location = 'header') {
        if ( !defined( 'ELEMENTOR_PRO_VERSION' ) ) return;
        $conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();
        return ! empty( $conditions_manager->get_documents_for_location( $location) );
    }

    public function get_critical_elementor_location_styles($widgets_2_load) {
        $styles = [];
        $need_2_load_elementor_menu_css = array_intersect(array('theme-etheme_mega_menu', 'theme-etheme_nav_menu', 'theme-etheme_departments_menu'), $widgets_2_load);
        if ( count($need_2_load_elementor_menu_css) )
            $styles[] = 'nav-menu'; // default Elementor widget name
        return $styles;
    }

    public function get_location_widgets($elements_data, $key = 'widgetType') {
        $elements = array();
        array_walk_recursive($elements_data, function ($v, $k) use ($key, &$elements) {
            if ($k === $key) array_push($elements, $v);
        });
        return count($elements) > 1 ? array_unique($elements) : array_pop($elements);
    }

    public function get_location_widgets_assets($location = 'header') {
        /** @var Theme_Builder_Module $theme_builder_module */
        $theme_builder_module = \ElementorPro\Modules\ThemeBuilder\Module::instance();

        $documents = $theme_builder_module->get_conditions_manager()->get_documents_for_location( $location );

        $direction = is_rtl() ? '-rtl' : '';

        foreach ($documents as $document) {
            if ( is_object( $document ) ) {
                $data = $document->get_elements_data();
                $widgets_2_load = (array)$this->get_location_widgets($data);
                if ( count(array_intersect(array('theme-etheme_cart', 'theme-etheme_wishlist', 'theme-etheme_waitlist', 'theme-etheme_compare'), $widgets_2_load)) ) {
                    wp_enqueue_style('etheme-cart-widget');
                }

                foreach ($this->get_critical_elementor_location_styles($widgets_2_load) as $elementor_widget_style) {
                    wp_enqueue_style('elementor-'.$elementor_widget_style, ELEMENTOR_PRO_ASSETS_URL . 'css/widget-' . $elementor_widget_style . $direction . '.min.css');
                }

                // add class to make loaded not visible
                add_action( 'elementor/element/after_add_attributes', function ($element) use ($widgets_2_load) {
                    if ( in_array($element->get_name(), $widgets_2_load))
                        $element->add_render_attribute( '_wrapper', 'class', 'etheme-elementor-widget-loaded' );
                } );

                foreach ($widgets_2_load as $widget_2_load) {
                    $local_widget = \Elementor\Plugin::$instance->widgets_manager->get_widget_types($widget_2_load);
                    // check for methods because some widgets don't have such ones and create fatal errors
                     if ( is_object($local_widget) && method_exists($local_widget, 'enqueue_scripts') ) {
                        $local_widget->enqueue_scripts();
                        $local_widget->enqueue_styles();
                     }
                }
            }
        }
    }

    /**
     * Register the stylesheets for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_frontend_styles() {
        $locations_2_check = ['header'];
        foreach ($locations_2_check as $location) {
            if ( $this->check_location_be_loaded($location) ) {
                $this->get_location_widgets_assets($location);
            }
        }
    	if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()){
            if ( defined('ETHEME_CODE_CSS' ) && is_singular('etheme_slides')) {
                wp_enqueue_style('et-elementor-frontend-editor-popup-options-css', ETHEME_CODE_CSS.'et_admin-panel-options.css');
                wp_enqueue_style('et-elementor-frontend-editor-popup', ETHEME_CODE_CSS . 'et_admin-popup.css');
                // add local styles for popup button
                wp_add_inline_style('et-elementor-frontend-editor-popup',
                    '.et_panel-popup .with-scroll {
                        max-height: 50vh;
                        min-height: 100px;
                    }
                    .et_panel-popup .et-button .et-loader {
                        position: static;
                        width: auto;
                        height: auto;
                        opacity: 0;
                        visibility: hidden;
                    }
                    
                    .et-panel-popup .et-button.loading {
                        transition: none;
                        color: transparent !important;
                    }
                    
                    .et-panel-popup .et-button.loading .et-loader {
                        opacity: 1;
                        visibility: visible;
                    }
                ');
            }

		    wp_enqueue_style( 'et-core-elementor-editor-style', ET_CORE_URL . 'app/assets/css/elementor-editor-preview.css', ['editor-preview'], '1.0' );
            wp_enqueue_style( 'et-core-elementor-editor-eight_theme-elementor-icon', ET_CORE_URL . 'app/assets/css/eight_theme-elementor-icon.css', ['editor-preview', 'et-core-elementor-editor-style'], ET_CORE_VERSION);

            wp_enqueue_script(
                'et-elementor-frontend-editor',
                ET_CORE_URL . 'app/assets/js/editor.js',
                array(),
                ET_CORE_VERSION
            );

            wp_localize_script(
                'et-elementor-frontend-editor',
                'etElementorFrontendEditorConfig',
                array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) )
            );
	    }
    }

    /**
     * Register the JavaScript for elementor.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts() {

    	$locate_lang = get_locale();

        if ( get_theme_mod( 'google_map_api', '' ) ) {
            $this->google_map_api = get_theme_mod( 'google_map_api', '' );
        }


        if( $this->google_map_api != '' ) {
            $url = 'https://maps.googleapis.com/maps/api/js?key='. $this->google_map_api .'&language='.$locate_lang.'&libraries=marker&loading=async';
        } else {
            $url = 'https://maps.googleapis.com/maps/api/js?language='.$locate_lang.'&libraries=marker&loading=async';
        }

        if ( apply_filters( 'et_hide_gmaps_api', false ) ){
        	$this->enqueue_scripts_hide_key($url);
        	return;
        }

        wp_register_script(
            'etheme-google-map-api',
            $url,
            array(),
            ET_CORE_VERSION,
            true
        );

        wp_localize_script(
            'etheme-google-map-api',
            'etheme_google_map_loc',
            array( 'plugin_url' => ET_CORE_URL )
        );

        wp_register_script(
            'etheme-google-map',
            ET_CORE_URL . 'app/assets/js/google-map.js',
            array( 'etheme-google-map-api' ),
            ET_CORE_VERSION,
            true
        );

    }

    public function enqueue_scripts_hide_key($url){
	    $data = wp_remote_get($url);
	    $data = wp_remote_retrieve_body($data);

	    wp_add_inline_script('etheme', $data);

	    wp_localize_script(
		    'etheme',
		    'etheme_google_map_loc',
		    array( 'plugin_url' => ET_CORE_URL )
	    );

	    wp_register_script(
		    'etheme-google-map',
		    ET_CORE_URL . 'app/assets/js/google-map.js',
		    array( 'etheme' ),
		    ET_CORE_VERSION,
		    true
	    );
    }

}