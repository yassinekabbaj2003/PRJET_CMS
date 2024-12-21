<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Wishlist widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Wishlist extends Off_Canvas_Skeleton {

    public static $instance = null;

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'theme-etheme_wishlist';
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
        return __( 'Wishlist', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-wishlist et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'mini-wishlist', 'product', 'list' ]);
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
        return array_merge(parent::get_style_depends(), [ 'etheme-cart-widget' ]);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        parent::register_controls();

        // for getting account url in needed places
        $this->update_control(
            'redirect',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'wishlist',
            ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-'.(get_theme_mod('xstore_wishlist_icon', 'type1') == 'type1' ? 'heart' : 'star')
                ],
            ]
        );

        $this->update_control(
            'button_text',
            [
                'default' => __( 'Wishlist', 'xstore-core' ),
                'placeholder' => __( 'Wishlist', 'xstore-core' ),
            ]
        );

        $this->update_control('show_view_page', [
            'label' 		=> __( 'View Wishlist Button', 'xstore-core' ),
        ]);

        $this->update_control('show_view_page_extra', [
            'label' 		=> __( 'Add All To Cart Button', 'xstore-core' ),
        ]);
    }

    public function is_woocommerce_depended() {
        return true;
    }

    public function canvas_should_display($settings) {
        $wishlist_page = false;
        if ( get_theme_mod('xstore_wishlist', false) ) {
            $wishlist_page_id = get_theme_mod('xstore_wishlist_page', '');
            $wishlist_page = ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) || (isset($_GET['et-wishlist-page']) && is_account_page());
        }
        if ($wishlist_page && !apply_filters('etheme_wishlist_content_shown_wishlist_pages', false))
            return false;

        return parent::canvas_should_display($settings);
    }

    protected function render_main_content($settings, $element_page_url, $extra_args = array()) {
        if ( $extra_args['wishlist_type'] ) {
            $is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            switch ($extra_args['wishlist_type']) {
                case 'xstore':
                    if ( $is_edit_mode ) {
                        echo '<div class="et_b_wishlist-dropdown product_list_widget cart_list"></div>';
                    }
                    else {
                        if ($is_edit_mode) {
                            add_filter('xstore_wishlist_mini_content_ajax', '__return_false');
                        }
                        if (!count($extra_args['built_in_wishlist_instance']::$products)) {
                            echo '<div class="et_b_wishlist-dropdown product_list_widget cart_list">';
                            $this->render_empty_content($settings);
                            echo '</div>';
                        } else {
                            $extra_args['built_in_wishlist_instance']->header_mini_wishlist(array('display_footer_buttons' => false));
                            $this->render_empty_content($settings, true);
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        else {
            // wrap in empty content html to make content centered
            echo '<div class="etheme-elementor-off-canvas_content-empty-message text-center">';
                echo Elementor::elementor_frontend_alert_message(
                    current_user_can( 'edit_theme_options' ) ? sprintf(
                    /* translators: %s: URL to header image configuration in Customizer. */
                        __( 'Please, enable <a style="text-decoration: underline" class="elementor-clickable" href="%s" target="_blank">Wishlist</a>.', 'xstore-core'),
                        admin_url( 'customize.php?autofocus[section]=xstore-wishlist' )) :
                        __( 'Please, enable Wishlist.', 'xstore-core'),
                    'warning'
                );
            echo '</div>';
        }
    }

    protected function render_main_prefooter($settings, $extra_args = array()) {
        if ( $extra_args['wishlist_type'] ) {
            switch ($extra_args['wishlist_type']) {
                case 'xstore':
                    $extra_args['built_in_wishlist_instance']->header_mini_wishlist_footer(
                            array_merge($extra_args, array(
                                'display_footer_buttons' => (!!$settings['show_view_page'] || !!$settings['show_view_page_extra']),
                                'show_view_page' => !!$settings['show_view_page'],
                                'show_add_all_products' => !!$settings['show_view_page_extra'],
                            )));
                    break;
                default:
                    break;
            }
        }
    }

    public function get_icon_qty_count() {
        $extra_args = array();
        if ( get_theme_mod('xstore_wishlist', false) ) {
            ob_start();
            $extra_args['built_in_wishlist_instance'] = \XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
            $extra_args['built_in_wishlist_instance']->header_wishlist_quantity(false, true);
            unset($extra_args);
            $qty = ob_get_clean();
            return '' != $qty ? $qty : false;
        }
        return parent::get_icon_qty_count();
    }

    public function render_empty_content_basic() {
        ?>
        <p class="text-center"><?php esc_html_e( 'No products in the wishlist.', 'xstore-core' ); ?></p>
        <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
            <div class="text-center">
                <a class="btn medium" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore-core') ?></span></a>
            </div>
        <?php endif;
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  4.1
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}
