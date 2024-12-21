<?php

/**
 *
 * @package     XStore theme
 * @author      8theme
 * @version     1.0.2
 * @since       3.2.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EthemeAdmin' ) ) {
	return;
}

if ( ! method_exists( 'EthemeAdmin', 'get_instance' ) ) {
	return;
}

// Don't duplicate me!
if ( ! class_exists( 'Etheme_Sales_Booster_Backend' ) ) {
	
	
	/**
	 * Main Etheme_Sales_Booster_Backend class
	 *
	 * @since       3.2.2
	 */
	class Etheme_Sales_Booster_Backend {
		
		/**
		 * Projects.
		 *
		 * @var array
		 * @since 3.2.2
		 */
		private $dir_url,
			$icons;
		
		public $global_admin_class;
		
		/**
		 * Class Constructor. Defines the args for the actions class
		 *
		 * @return      void
		 * @version     1.0.1
		 * @since       3.2.2
		 * @access      public
		 */
		public function __construct() {
			$this->global_admin_class = EthemeAdmin::get_instance();
			
			$this->global_admin_class->init_vars();
			
			add_action( 'wp_ajax_xstore_panel_settings_save', array(
				$this->global_admin_class,
				'xstore_panel_settings_save'
			) );
		}
		
		public function sales_booster_page_init_scripts() {
			
			$this->global_admin_class->settings_name = 'xstore_sales_booster_settings';
			
			$this->global_admin_class->xstore_panel_section_settings = get_option( $this->global_admin_class->settings_name, array() );
			
			$this->dir_url = ET_CORE_URL . 'app/models/sales-booster';
		}
		
		/**
		 * Section content html.
		 *
		 * @return void
		 * @version 1.0.0
		 * @since   3.2.2
		 *
		 */
		public function sales_booster_page() {

            $global_admin_class = EthemeAdmin::get_instance();
			$this->sales_booster_page_init_scripts();
            $scripts_2_load = array();
			
			ob_start();
			
//			$active_tab = get_transient( 'xstore_sales_booster_settings_active_tab' );
//			if ( ! $active_tab ) {
//				$active_tab = 'fake_sale_popup';
//			}
//
//			if ( isset( $_GET['etheme-sales-booster-tab'] ) ) {
//				$active_tab = $_GET['etheme-sales-booster-tab'];
//			}

            $_sale_booster_feature = ( isset($_GET['etheme-sales-booster-tab']) ) ? $_GET['etheme-sales-booster-tab'] : false;
			
			?>

            <h2 class="etheme-page-title etheme-page-title-type-2"><?php echo '🚀&nbsp;&nbsp;' . esc_html__( 'Sales Booster', 'xstore-core' ); ?></h2>
            <p>
				<?php echo '<strong>' . esc_html__( 'We are delighted to introduce you to the Sales Booster panel!', 'xstore-core' ) . '</strong> &#127881'; ?>
                <br/>
                <?php echo sprintf(esc_html__('The %s Sales Booster offers a variety of features to assist you in reaching your goals and propelling your store towards success.', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')); ?>
            </p>
            <?php if(!$_sale_booster_feature): ?>
                <div class="xstore-panel-grid-header">
                    <?php
                    $global_admin_class->get_filters_form(array(
                        'all' => esc_html__( 'All', 'xstore-core' ),
                        'active' => esc_html__( 'Active', 'xstore-core' ),
                        'disabled' => esc_html__( 'Inactive', 'xstore-core' ),
                        'site' => esc_html__( 'Across the Web-site', 'xstore-core' ),
                        'account' => esc_html__( 'Account Page', 'xstore-core' ),
                        'single-product' => esc_html__( 'Single Product Page', 'xstore-core' ),
                        'off-canvas' => esc_html__( 'Off-Canvas', 'xstore-core' ),
                        'product-quick-view' => esc_html__( 'Product Quick View', 'xstore-core' ),
                        'cart' => esc_html__( 'Cart Page', 'xstore-core' ),
                        'checkout' => esc_html__( 'Checkout Page', 'xstore-core' ),
                    ) );
                ?>

                <?php
                    $global_admin_class->get_search_form('sale-booster', esc_html__( 'Search for feature', 'xstore-core' ));
                ?>
            </div>
            <?php endif; ?>
            <div class="xstore-panel-grid-wrapper">
            <?php
                $sales_booster_features = array(
                    'fake_sale_popup' => array(
                        'title' => esc_html__('Fake Sale Popup', 'xstore-core'),
                        'details_url' => etheme_documentation_url('133-fake-sale-popup', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/product/product-classic/',
                        'script_depends' => array(
                            'switch',
                            'media',
                            'callbacks',
                            'slider'
                        ),
                        'filters' => array(
                            'site'
                        )
                    ),
                    'progress_bar' =>  array(
                        'title' => esc_html__( 'Progress Bar', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('134-progress-bar', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/product/product-classic/',
                        'script_depends' => array(
                            'icons_select'
                        ),
                        'filters' => array(
                            'site'
                        )
                    ),
                    'request_quote' => array(
                        'title' => esc_html__( 'Request A Quote', 'xstore-core' ),
                        'description' => esc_html__( 'Show Request a quote on single product page or all pages as a floating bulb.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('135-request-a-quote', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/product/product-classic/',
                        'script_depends' => array(
                            'switch',
                            'media',
                            'colorpicker'
                        ),
                        'filters' => array(
                            'site',
                            'single-product'
                        )
                    ),
                    'cart_checkout_countdown' => array(
                        'title' => esc_html__( 'Cart / Checkout Countdown', 'xstore-core' ),
                        'description' => esc_html__( 'Show countdown timer as soon as any product has been added to the cart. This can help your store make those products sales quicker.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('136-cart-checkout-countdown', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/cart/',
                        'script_depends' => array(
                            'switch',
                            'slider'
                        ),
                        'filters' => array(
                            'cart',
                            'checkout'
                        )
                    ),
                    'cart_checkout_progress_bar' => array(
                        'title' => esc_html__( 'Cart / Checkout Progress Bar', 'xstore-core' ),
                        'description' => esc_html__( 'Show progress bar as soon as any product has been added to the cart. This can help your store make those products sales quicker.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('137-cart-checkout-progress-bar', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/cart/',
                        'script_depends' => array(
                            'icons_select'
                        ),
                        'filters' => array(
                            'cart',
                            'checkout'
                        )
                    ),
                    'fake_live_viewing' => array(
                        'title' => esc_html__('Fake Live Viewing', 'xstore-core'),
                        'description' => esc_html__( 'Show live viewing message on single products and quick view. This can help your store make that product to sell quicker.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('138-fake-live-viewing', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/product/product-classic/',
                        'script_depends' => array(
                            'slider'
                        ),
                        'filters' => array(
                            'single-product',
                            'product-quick-view',
                        )
                    ),
                    'fake_product_sales' => array(
                        'title' => esc_html__('Item Sold Fake Indicator', 'xstore-core'),
                        'description' => esc_html__( 'Show total sales message on single products, quick view and products archives. This can help your store make that product to sell quicker.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('155-boost-your-sales-with-xstores-fake-sold-counter-feature', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/demos/beauty-and-cosmetics/product/balancing-capsule/',
                        'script_depends' => array(
                            'switch',
                            'slider',
                            'callbacks'
                        ),
                        'filters' => array(
                            'single-product',
                            'product-quick-view',
                        )
                    ),
                    'estimated_delivery' => array(
                        'title' => esc_html__('Estimated Delivery', 'xstore-core'),
                        'description' => esc_html__( 'Show estimated delivery on your single products.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('153-xstore-setup-booster-sales-estimate-delivery', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/industrial-power-tools/product/professionals-engineers-hammer-5g/',
                        'script_depends' => array(
                            'slider',
                            'callbacks',
                            'switch'
                        ),
                        'filters' => array(
                            'single-product',
                            'product-quick-view',
                        )
                    ),
                    'customer_reviews_images' => array(
                        'title' => esc_html__('Customer Image Reviews', 'xstore-core'),
                        'description' => esc_html__( 'This feature provides the ability to allow customers to upload images in their review.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('154-learn-how-to-set-up-the-photo-reviews-feature-in-the-booster-sales-section-of-xstore', false),
                        'script_depends' => array(
                            'slider',
                            'switch'
                        ),
                        'filters' => array(
                            'single-product',
                        )
                    ),
                    'customer_reviews_advanced' => array(
                        'title' => esc_html__('Advanced Product Reviews', 'xstore-core'),
                        'description' => esc_html__( 'With this feature you will have new-fashion style for reviews on single product pages.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('185-advanced-reviews-elevate-your-customer-feedback-game-with-xstore', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/sport/product/nike-white-lightweight/',
                        'script_depends' => array(
                            'icons_select',
                            'colorpicker',
                            'callbacks',
                            'switch',
                            'sortable',
                            'repeater',
                        ),
                        'filters' => array(
                            'single-product',
                        )
                    ),
                    'quantity_discounts' => array(
                        'title' => esc_html__('Quantity Discounts', 'xstore-core'),
                        'description' => esc_html__( 'Show quantity discounts on your single products.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('156-how-to-setup-booster-sales-quantity-discounts', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/furniture3/product/achille-sideboard-unit/',
                        'script_depends' => array(
                            'switch',
                            'icons_select',
                            'sortable',
                            'repeater',
                            'callbacks'
                        ),
                        'filters' => array(
                            'single-product',
                            'product-quick-view',
                        )
                    ),
                    'safe_checkout' => array(
                        'title' => esc_html__('Safe & Secure Checkout', 'xstore-core'),
                        'description' => esc_html__( 'XStore makes it easy to include trust badges below the primary call to action button on the single product page, cart and checkout.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('161-xstore-sales-booster-feature-safe-secure-checkout', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/furniture3/product/achille-sideboard-unit/',
                        'script_depends' => array(
                            'colorpicker',
                            'sortable',
                            'repeater',
                            'media',
                            'switch'
                        ),
                        'filters' => array(
                            'single-product',
                            'product-quick-view',
                            'cart',
                            'checkout',
                        )
                    ),
                    'account_loyalty_program' => array(
                        'title' => esc_html__('Account Loyalty Program', 'xstore-core'),
                        'description' => esc_html__( 'Register your account benefits items shown for your customers on My account page and account off-canvas area. This can motivate them to shopping again on your store.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('187-account-loyalty-program-info', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/sport/my-account/',
                        'script_depends' => array(
                            'sortable',
                            'repeater',
                            'icons_select'
                        ),
                        'filters' => array(
                            'account',
                            'off-canvas'
                        )
                    ),
                    'account_tabs' => array(
                        'title' => esc_html__('Account Login/Register Tabs', 'xstore-core'),
                        'description' => esc_html__( 'Make your basic login/register forms with switcher so it will be more iteractive for customers.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('188-account-login-register-tabs', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor2/sport/my-account/',
                        'filters' => array(
                            'account'
                        )
                    ),
                    'floating_menu' => array(
                        'title' => esc_html__('Floating Navigation Panel', 'xstore-core'),
                        'description' => esc_html__( 'Show floating menu that could add extra links you want to make shown.', 'xstore-core' ),
                        'details_url' => etheme_documentation_url('186-floating-menu', false),
                        'preview_url' => 'https://xstore.8theme.com/elementor/demos/beauty-and-cosmetics/',
                        'script_depends' => array(
                            'sortable',
                            'repeater',
                            'media',
                            'switch',
                            'tab',
                            'slider',
                            'colorpicker'
                        ),
                        'filters' => array(
                            'site'
                        )
                    ),
                    'xstore_waitlist' => array(
                        'title' => esc_html__('Waitlist', 'xstore-core'),
                        'description' => __('By enabling this option, your customers can easily sign up for a mailing list of out-of-stock or unavailable items they are interested in. Don\'t miss out on the opportunity to offer a personalized shopping experience that keeps your customers coming back for more. Enable the waitlist feature today!', 'xstore-core'),
//                        'details_url' => etheme_documentation_url('188-account-login-register-tabs', false),
//                        'preview_url' => 'https://xstore.8theme.com/elementor2/sport/my-account/',
                        'filters' => array(
                            'site', 'account', 'single-product', 'off-canvas'
                        ),
                        'theme_mod' => true,
                        'theme_mod_url' => admin_url( 'customize.php?autofocus[section]=xstore-waitlist' ),
                    ),
                    'linked_variations' => array(
                        'title' => esc_html__('Linked Variations Products', 'xstore-core'),
                        'description' => __('This feature allows you to create a seamless shopping experience by linking related product variations. Customers can easily switch between different versions of a product, like colors or sizes, without leaving the product page. This not only enhances user experience but also boosts SEO by creating more internal links and helping search engines better understand the relationship between your products.', 'xstore-core'),
//                        'details_url' => etheme_documentation_url('188-account-login-register-tabs', false),
//                        'preview_url' => 'https://xstore.8theme.com/elementor2/sport/my-account/',
                        'filters' => array(
                            'product-quick-view', 'single-product'
                        ),
                        'theme_mod' => true,
//                        'theme_mod_url' => admin_url( 'edit.php?post_type=etheme_linked_var' ), // uncomment to redirect to settings
                    ),
                );

                foreach ( $sales_booster_features as $slug => $sale_booster_feature ) {
                    if ($_sale_booster_feature && $_sale_booster_feature != $slug)
                        continue;
                    
                    if ( isset($sale_booster_feature['script_depends']) ) {
                        $scripts_2_load = array_merge($scripts_2_load, $sale_booster_feature['script_depends']);
                    }
                    $sale_booster_feature['image_url'] = $this->dir_url . '/images/'.$slug.'.jpg';
                    $new_is_plugin_active = isset($sale_booster_feature['theme_mod']) ? get_theme_mod($slug, false) : get_option( $this->global_admin_class->settings_name . '_' . $slug, false );
                    $sale_booster_feature_classes = array();
                    $sale_booster_feature_classes[] = 'xstore-panel-grid-item';
                    $sale_booster_feature_classes[] = ($new_is_plugin_active) ? 'xstore-panel-grid-item-active' : '';

                    $filters = array();
                    $filters[] = 'all';

                    $filters[] = $new_is_plugin_active ? 'active' : 'disabled';

                    foreach ($sale_booster_feature['filters'] as $sale_booster_feature_filter) {
                        $filters[] = $sale_booster_feature_filter;
                    }
                ?>
                    <div class="<?php echo trim( esc_attr( implode( ' ', $sale_booster_feature_classes ) ) ); ?>"
                 data-slug="<?php echo esc_attr( $slug ); ?>"
                 data-filter="<?php echo trim( esc_attr( implode( ' ', $filters ) ) ); ?>">
                    <div class="xstore-panel-grid-item-content">
                    <span
                            class="xstore-panel-grid-item-action-text"
                            data-activate="<?php echo esc_html__('Activating', 'xstore-core') . ' ...'; ?>"
                            data-deactivate="<?php echo esc_html__('Deactivating', 'xstore-core') . ' ...'; ?>"
                    ></span>
                        <div class="xstore-panel-grid-item-image">
                            <span class="xstore-panel-grid-item-checkbox">
                                <span class="mtips mtips-left inverse no-arrow">
                                    <span class="dashicons dashicons-yes"></span>
                                    <span class="mt-mes"><?php echo esc_html__('Activated', 'xstore-core'); ?></span>
                                </span>
                            </span>
                            <?php if ( isset( $sale_booster_feature['image_url'] ) ) :
                                $details_url = false;
                                if ( isset($sale_booster_feature['preview_url']) )
                                    $details_url = apply_filters('etheme_documentation_url', $sale_booster_feature['preview_url']);
                                elseif (isset($sale_booster_feature['details_url']) )
                                    $details_url = $sale_booster_feature['details_url'];

                                $details_tag = !!$details_url ? 'a' : 'span'; ?>
                                <<?php echo esc_attr($details_tag); ?><?php echo !!$details_url ? ' href="'.$details_url.'" target="_blank"' : ''; ?>>
                                    <img
                                            class="lazyload lazyload-simple et-lazyload-fadeIn"
                                            src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                            data-src="<?php echo apply_filters('etheme_protocol_url', $sale_booster_feature['image_url'] ); ?>"
                                            alt="<?php echo esc_attr( $slug ); ?>">
                                </<?php echo esc_attr($details_tag); ?>>
                            <?php else: ?>
                                <span><?php esc_html_e( 'No image set', 'xstore-core' ); ?></span>
                            <?php endif; ?>
                            <?php if ( isset($sale_booster_feature['details_url']) ) : ?>
                                <a class="xstore-panel-grid-item-info" target="_blank" href="<?php echo esc_url( apply_filters('etheme_documentation_url', $sale_booster_feature['details_url']) ); ?>">
                                    <span class="mtips mtips-left inverse no-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" class="svg-inline--fa fa-info fa-w-6" role="img" viewBox="0 0 192 512" style="width: 1;width: 1em;height: 1em;">
                                            <path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path>
                                        </svg>
                                        <span class="mt-mes"><?php echo esc_html__('More information', 'xstore-core'); ?></span>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="xstore-panel-grid-item-info">
                            <span class="xstore-panel-grid-item-name">
                                 <?php echo esc_html( $sale_booster_feature['title'] ); ?>
                            </span>
                            <div class="xstore-panel-grid-item-control-wrapper">

                                <?php  if ( isset($sale_booster_feature['theme_mod_url']) ) : ?>
                                    <a
                                            href="<?php echo esc_url( $sale_booster_feature['theme_mod_url'] ); ?>"
                                            target="_blank"
                                            class="xstore-panel-grid-item-control et-button et-button-sm no-loader<?php echo !$new_is_plugin_active ? ' hidden' : ''; ?>"
                                            data-action="customizer_settings">
                                        <span class="dashicons dashicons-admin-generic"></span>
                                        <span><?php esc_html_e('Settings', 'xstore-core'); ?></span>
                                    </a>
                                <?php else: ?>
                                    <span
                                            class="xstore-panel-grid-item-control et-button et-button-sm no-loader<?php echo !$new_is_plugin_active ? ' hidden' : ''; ?>"
                                            data-slug="<?php echo esc_attr( $slug ); ?>"
                                            data-action="settings"
                                            data-info="<?php echo esc_attr(wp_json_encode(array(
                                                'title' => $sale_booster_feature['title'],
                                                'description' => isset($sale_booster_feature['description']) ? $sale_booster_feature['description'] : '',
                                            ))); ?>">
                                    <span class="dashicons dashicons-admin-generic"></span>
                                    <span><?php echo esc_html__( 'Settings', 'xstore-core' ); ?></span>
                                </span>
                    <?php endif; ?>

                                <?php if ( isset($sale_booster_feature['preview_url']) ) : ?>
                                    <a
                                            href="<?php echo esc_url( apply_filters('etheme_documentation_url', $sale_booster_feature['preview_url']) ); ?>"
                                            target="_blank"
                                            class="xstore-panel-grid-item-control et-button et-button-sm no-loader<?php echo $new_is_plugin_active ? ' hidden' : ''; ?>"
                                            data-action="preview">
                                        <span class="dashicons dashicons-visibility"></span>
                                        <span><?php esc_html_e('Preview', 'xstore-core'); ?></span>
                                    </a>
                                <?php endif; ?>

                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-active et-button-sm no-loader<?php echo !$new_is_plugin_active ? ' hidden' : ''; ?>"
                                        data-slug="<?php echo esc_attr( $slug ); ?>"
                                        <?php if ( isset($sale_booster_feature['theme_mod']) ) : ?>data-theme-mod="yes"<?php endif; ?>
                                        <?php if ( isset($sale_booster_feature['theme_mod_url']) ) : ?>data-theme-mod_url="yes"<?php endif; ?>
                                        data-action="deactivate">
                                    <span class="dashicons dashicons-lock"></span>
                                    <span><?php esc_html_e( 'Deactivate', 'xstore-core' ); ?></span>
                                </span>

                                <span
                                        class="xstore-panel-grid-item-control et-button et-button-green et-button-sm no-loader<?php echo $new_is_plugin_active ? ' hidden' : ''; ?>"
                                        data-slug="<?php echo esc_attr( $slug ); ?>"
                                        <?php if ( isset($sale_booster_feature['theme_mod']) ) : ?>data-theme-mod="yes"<?php endif; ?>
                                        <?php if ( isset($sale_booster_feature['theme_mod_url']) ) : ?>data-theme-mod_url="yes"<?php endif; ?>
                                        data-action="activate">
                                    <span class="dashicons dashicons-unlock"></span>
                                    <span><?php echo esc_html__( 'Activate', 'xstore-core' ); ?></span>
                                </span>

                            </div>
                        </div>
                    </div>
                    </div>
                <?php } ?>
                <span class="hidden xstore-panel-grid-item-nonce"
                      data-plugin-nonce="<?php echo wp_create_nonce( 'sales_booster_nonce' ); ?>"></span>
            </div>

        <?php
            $global_admin_class->get_search_no_found();

            $global_admin_class->get_additional_panel_blocks();

        echo ob_get_clean();

            foreach (array_unique($scripts_2_load) as $script_2_load) {
                switch ($script_2_load) {
                    case 'repeater':
                    case 'sortable':
                        wp_enqueue_script('jquery-ui-sortable');
                        wp_enqueue_script('jquery-ui-draggable');
                        break;
                    case 'media':
                        wp_enqueue_media();
                        break;
                    case 'colorpicker':
                        wp_enqueue_script( 'jquery-color' );
                        wp_enqueue_style( 'wp-color-picker' );
                        wp_enqueue_script( 'wp-color-picker' );
                        break;
                }
                $this->global_admin_class->enqueue_settings_scripts($script_2_load);
        }
	    }

    }
	$Etheme_Sales_Booster_Backend = new Etheme_Sales_Booster_Backend();
}
