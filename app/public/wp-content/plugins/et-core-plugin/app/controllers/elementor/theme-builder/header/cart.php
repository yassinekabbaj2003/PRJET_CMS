<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Account widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Cart extends Off_Canvas_Skeleton {

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
		return 'theme-etheme_cart';
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
		return __( 'Shopping Cart', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-cart et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'mini-cart', 'product', 'list' ]);
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

        $mini_cart_template = get_option( 'elementor_use_mini_cart_template', 'no' );
        if ( empty($mini_cart_template) || in_array($mini_cart_template, array('initial', 'yes') ) ) {
            $this->start_injection( [
                'type' => 'section',
                'at'   => 'start',
                'of'   => 'section_general',
            ] );
            $this->add_control(
                'elementor_mini_cart_template_info',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => sprintf(esc_html__('You have enabled "%s" in Elementor settings so please, set it to disable for correct layout of this widget', 'xstore-core'), '<a href="' . admin_url('admin.php?page=elementor#tab-integrations') . '" target="_blank">' . esc_html__('Mini Cart Template', 'xstore-core') . '</a>'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );
            $this->end_injection();
        }

        // for getting account url in needed places
        $this->update_control(
                'redirect',
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => 'cart',
                ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-shopping-cart'
                ],
            ]
        );

        $this->update_control(
            'button_text',
            [
                'default' => __( 'Cart', 'xstore-core' ),
                'placeholder' => __( 'Cart', 'xstore-core' ),
            ]
        );

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'show_total',
            [
                'label' 		=> __( 'Show Total', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
            $active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
            $breakpoints_list   = array();

            foreach ($active_breakpoints as $key => $value) {
                $breakpoints_list[$key] = $value->get_label();
            }

            $breakpoints_list['desktop'] = 'Desktop';
            $breakpoints_list            = array_reverse($breakpoints_list);
        } else {
            $breakpoints_list = array(
                'desktop' => 'Desktop',
                'tablet'  => 'Tablet',
                'mobile'  => 'Mobile'
            );
        }

        $this->add_control(
            'show_total_hidden',
            array(
                'label'    => __( 'Total Hidden On', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => 'true',
                'default' => array(),
                'options' => $breakpoints_list,
                'condition' => array(
                    'show_total!' => '',
                ),
            )
        );

        $this->end_injection();

        $this->update_control('button_text_hidden', [
            'conditions' 	=> [
                'relation' => 'or',
                'terms' 	=> [
                    [
                        'name' 		=> 'button_text',
                        'operator'  => '!=',
                        'value' 	=> ''
                    ],
                    [
                        'name' 		=> 'show_total',
                        'operator'  => '!=',
                        'value' 	=> ''
                    ],
                ],
            ]
        ]);

        $icon_conditions = [
            'relation' => 'and',
            'terms' 	=> [
                [
                    'name' 		=> 'selected_icon[value]',
                    'operator'  => '!=',
                    'value' 	=> ''
                ],
                [
                    'relation' => 'or',
                    'terms' 	=> [
                        [
                            'name' 		=> 'button_text',
                            'operator'  => '!=',
                            'value' 	=> ''
                        ],
                        [
                            'name' 		=> 'show_total',
                            'operator'  => '!=',
                            'value' 	=> ''
                        ],
                    ]
                ]
            ],
        ];

        $this->update_control('icon_align', [
            'conditions' 	=> $icon_conditions
        ]);

        $this->update_control('icon_indent', [
            'conditions' 	=> $icon_conditions
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'show_view_page',
        ] );

        $this->add_control(
            'linked_products',
            [
                'label'    => esc_html__( 'Linked Products', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'description'  => esc_html__('Enable this option to display upsells or cross-sells products based on the items added to the cart.', 'xstore-core'),
                'options' => array(
                    ''     => esc_html__( 'Disable', 'xstore-core' ),
                    'upsell'     => esc_html__( 'Upsells', 'xstore-core' ),
                    'cross-sell' => esc_html__( 'Cross-sells', 'xstore-core' ),
                ),
                'frontend_available' => true,
                'default' => '',
            ]
        );

        $this->end_injection();

        $this->update_control('show_view_page', [
            'label' 		=> __( 'View Checkout Button', 'xstore-core' ),
        ]);

        $this->update_control('show_view_page_extra', [
            'label' 		=> __( 'View Cart Button', 'xstore-core' ),
        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_off_canvas',
        ] );

        $this->add_control(
            'show_product_quantity_input',
            [
                'label' 		=> __( 'Quantity input for products', 'xstore-core' ),
                'label_block' => true,
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'has-quantity-input',
                'return_value' => 'has-quantity-input',
                'prefix_class' => 'etheme-elementor-off-canvas-products-',
                'render_type' => 'template',
            ]
        );

        $this->add_control(
			'footer_content',
			[
				'label' => esc_html__( 'Footer Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '',
                'separator' => 'before'
			]
		);

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_off_canvas_style',
        ] );

        $this->add_control(
            'after_footer_style',
            [
                'label' => __( 'After Footer', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'after_footer_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas_content-after-footer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'after_footer_background',
				'types' => [ 'classic', 'gradient' ], // classic, gradient, video, slideshow
				'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas_content-after-footer'
			]
		);

        $this->end_injection();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render()
    {
//        $OPTION_NAME_USE_MINI_CART = 'use_mini_cart_template';
//        $elementor_use_mini_cart_template = 'yes' === get_option( 'elementor_' . $OPTION_NAME_USE_MINI_CART, 'no' );
//
//        add_filter('pre_option_elementor_'.$OPTION_NAME_USE_MINI_CART, array($this, 'filter_elementor_use_mini_cart_template'));

        if ( ! wp_script_is( 'wc-cart-fragments' ) ) {
            wp_enqueue_script( 'wc-cart-fragments' );
        }

        parent::render();

        // remove_filter('pre_option_elementor_'.$OPTION_NAME_USE_MINI_CART, array($this, 'filter_elementor_use_mini_cart_template'));
    }

    public function is_woocommerce_depended() {
        return true;
    }

    public function canvas_should_display($settings) {
        $cart_checkout_page = is_cart() || is_checkout();
        if ($cart_checkout_page && !apply_filters('etheme_cart_content_shown_cart_checkout_pages', false))
            return false;

        return parent::canvas_should_display($settings);
    }

    protected function render_main_content($settings, $element_page_url, $extra_args = array()) {
        if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) ) return;

        if ( WC()->cart->is_empty() ) {
            echo '<div class="widget_shopping_cart_content">';
                $this->render_empty_content($settings);
            echo '</div>';
        }
        else {
            $is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            $this->render_main_content_inner($settings, $is_edit_mode);
            // preload for cases when ajax empty cart be displayed correctly with templates built for empty cart content
            $this->render_empty_content($settings, true);
        }
    }

    public function render_main_content_inner($settings, $is_edit_mode) {
        $show_quantity = !!$settings['show_product_quantity_input'];
        $linked_products = !!$settings['linked_products'];
        $filters = array(
            'etheme_mini_cart_quantity_input' => ($show_quantity ? '__return_true' : '__return_false'),
            'etheme_mini_cart_linked_products_force_display' => ($linked_products ? '__return_true' : '__return_false')
        );
        if ( $linked_products ) {
            switch ($linked_products) {
                case 'upsell':
                    $filters['etheme_mini_cart_linked_products_type'] = array($this, 'filter_linked_products_type_upsell');
                    break;
                case 'cross-sell':
                    $filters['etheme_mini_cart_linked_products_type'] = array($this, 'filter_linked_products_type_cross_sell');
                    break;
            }
            $filters['etheme_mini_cart_linked_products_force_display'] = '__return_true';
        }
        foreach ($filters as $filter_key => $filter_value) {
            add_filter($filter_key, $filter_value);
        }

        if ( !$is_edit_mode ) {
            the_widget('WC_Widget_Cart', 'title=');
        }
        else {
            ?>
            <div class="widget_shopping_cart_content">
                <?php
                woocommerce_mini_cart();
                ?>
            </div>
            <?php
        }
        foreach ($filters as $filter_key => $filter_value) {
            remove_filter($filter_key, $filter_value);
        }
    }

    protected function render_main_prefooter($settings, $extra_args = array()) {
        $mini_cart_template = get_option( 'elementor_use_mini_cart_template', 'no' );
        if ( empty($mini_cart_template) || in_array($mini_cart_template, array('initial', 'yes') ) ) return; // because it is yes then
        $this->render_main_content_prefooter_content();
    }

    public function render_main_content_prefooter_content() {
        if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) ) return;

        $settings = $this->get_settings_for_display();

        $element_options = array();
        $element_options['xstore_sales_booster_settings'] = (array)get_option( 'xstore_sales_booster_settings', array() );

        if (
            ! isset($element_options['xstore_sales_booster_settings'])
            || ! isset($element_options['xstore_sales_booster_settings']['progress_bar_ignore_discount'])
        ) {
            $element_options['xstore_sales_booster_settings']['progress_bar_ignore_discount'] = 'yes';
        }

        $element_options['xstore_sales_booster_settings_default'] = array(
            'progress_bar_ignore_discount' => (isset($element_options['xstore_sales_booster_settings']['progress_bar_cart_ignore_discount'])? $element_options['xstore_sales_booster_settings']['progress_bar_cart_ignore_discount'] : 'yes'),
        );

        $element_options['xstore_sales_booster_settings_cart_checkout'] = $element_options['xstore_sales_booster_settings_default'];

        if ( count($element_options['xstore_sales_booster_settings']) && isset($element_options['xstore_sales_booster_settings']['cart_checkout'])) {
            $element_options['xstore_sales_booster_settings'] = wp_parse_args( $element_options['xstore_sales_booster_settings']['cart_checkout'],
                $element_options['xstore_sales_booster_settings_default'] );
            $element_options['xstore_sales_booster_settings_cart_checkout'] = $element_options['xstore_sales_booster_settings'];
        }

        $has_cart_button = has_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart');
        if ( !!$has_cart_button)
            remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', $has_cart_button );

        $has_checkout_button = has_action( 'woocommerce_widget_shopping_cart_buttons', 'etheme_woocommerce_widget_shopping_cart_proceed_to_checkout' );
        if ( !!$has_checkout_button ) {
            remove_action( 'woocommerce_widget_shopping_cart_buttons', 'etheme_woocommerce_widget_shopping_cart_proceed_to_checkout', $has_checkout_button );
            if ( !!$settings['show_view_page'] ) {
                add_action( 'woocommerce_widget_shopping_cart_buttons', 'etheme_woocommerce_widget_shopping_cart_proceed_to_checkout', $has_checkout_button );
            }
        }
        else {
            $has_checkout_button = has_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout' );
            remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', $has_checkout_button );
            if ( !!$settings['show_view_page'] ) {
                add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', $has_checkout_button );
            }
        }

        if ( !!$settings['show_view_page_extra'] ) {
            add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
        }

        $count = get_query_var('et_woo_products_count', false);
        if ( !$count ) {
            $count = WC()->cart->get_cart_contents_count();
            set_query_var('et_woo_products_count', $count);
        }

        // if ( ! WC()->cart->is_empty() ) : ?>

        <div class="etheme-elementor-off-canvas-content-prefooter-inner">

            <?php $this->render_main_content_prefooter_total_inner_area($count); ?>

            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

            <?php if ( has_action('woocommerce_widget_shopping_cart_buttons') ) : ?>
                <p class="buttons mini-cart-buttons">
                    <?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
                </p>
            <?php endif; ?>

            <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

        </div>

        <?php
            if ( !!$settings['footer_content'] ) { ?>
            <div class="etheme-elementor-off-canvas_content-after-footer woocommerce-mini-cart__footer">
                <?php
                    echo do_shortcode($settings['footer_content']);
                ?>
            </div>
            <?php }
        ?>

        <?php // endif;
    }

    public function render_main_content_prefooter_total_inner_area($count = 0) {
        if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) ) return;
        $amount = '';
        if ( ! wc_tax_enabled() ) {
            $amount = WC()->cart->cart_contents_total;
        } else {
//			$amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
            $amount = WC()->cart->get_displayed_subtotal();
        }

        $progress_bar_ignore_discount = (array)get_option( 'xstore_sales_booster_settings', array() );
        if (
            isset($progress_bar_ignore_discount['progress_bar'])
            && isset($progress_bar_ignore_discount['progress_bar']['progress_bar_ignore_discount'])
        ) {
            $progress_bar_ignore_discount = $progress_bar_ignore_discount['progress_bar']['progress_bar_ignore_discount'];
        } else {
            $progress_bar_ignore_discount = 'yes';
        }

        if ($progress_bar_ignore_discount == 'yes'){
            $amount += WC()->cart->get_discount_total();
        }

        $amount = apply_filters('et_progress_bar_amount', $amount);

//        if ( class_exists('WCPay\MultiCurrency\MultiCurrency') ) {
//            $amount = WCPay\MultiCurrency\MultiCurrency::instance()->get_price($amount, 'exchange_rate');
//        }
        ?>
        <div class="cart-popup-footer">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
               class="btn-view-cart wc-forward"><?php esc_html_e( 'Shopping cart ', 'xstore-core' ); ?>
                (<?php echo $count; ?>)</a>
            <div class="cart-widget-subtotal woocommerce-mini-cart__total total flex justify-content-between align-items-center"
                 data-amount="<?php echo $amount; ?>">
                <?php
                /**
                 * Woocommerce_widget_shopping_cart_total hook.
                 *
                 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
                 */
                do_action( 'woocommerce_widget_shopping_cart_total' );
                ?>
            </div>
        </div>
        <?php
    }

    public function render_empty_content_basic() {
        ?>
        <p class="text-center"><?php esc_html_e( 'No products in the cart.', 'xstore-core' ); ?></p>
        <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
            <div class="text-center">
                <a class="btn medium" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore-core') ?></span></a>
            </div>
        <?php endif;
    }

    public function get_icon_qty_count() {
        if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) || ! method_exists( WC()->cart, 'get_cart_contents_count' ) ) {
            return parent::get_icon_qty_count();
        }
        return WC()->cart->get_cart_contents_count();
    }

    protected function should_wrap_button_text($settings, $has_hidden_text = false) {
        if ( $settings['show_total'] && $has_hidden_text ) return true;
        return parent::should_wrap_button_text($settings);
    }
    protected function render_text_after($settings, $edit_mode = false, $button_wrapper = false) {
        if ( !$settings['show_total'] ) return;
        if ( ! function_exists( 'WC' ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) || ! method_exists( WC()->cart, 'get_cart_subtotal' ) ) {
            return;
        }
        $show_total_class = array();
        foreach ($settings['show_total_hidden'] as $hidden_on_device) {
            $show_total_class[] = 'elementor-hidden-' . $hidden_on_device;
            if ( $edit_mode ) {
                    ?>
                    <style>
                        [data-elementor-device-mode="<?php echo $hidden_on_device ?>"] [data-id="<?php echo $this->get_id(); ?>"] .elementor-hidden-<?php echo $hidden_on_device; ?> {
                            display: none !important;
                        }
                    </style>
                    <?php
                }
        }
        if ( $button_wrapper ) {
            $this->add_render_attribute( 'button_text', [
                'class' => $show_total_class,
            ] );?>
            <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
        <?php }
        else { ?>
            <span class="<?php echo implode(' ', $show_total_class); ?>">
        <?php }
            $this->render_subtotal();
        ?>
            </span>
        <?php
    }

    public function render_subtotal() {
        ?>
            <span class="etheme-elementor-off-canvas-total-inner">
              <?php echo wp_specialchars_decode( WC()->cart->get_cart_subtotal() ); ?>
            </span>
        <?php
    }
    public function filter_linked_products_type_upsell() {
        return 'upsell';
    }
    public function filter_linked_products_type_cross_sell() {
        return 'cross-sell';
    }

//    public function filter_elementor_use_mini_cart_template($value) {
//        return 'no';
//    }

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
