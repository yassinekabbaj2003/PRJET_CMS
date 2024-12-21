<?php
/**
 * Description
 *
 * @package    init.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

/*
* Cart Checkout
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/cart-checkout.php') );

/*
* Progress Bar (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/progress-bar.php') );

/*
* Estimated Delivery (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/estimated-delivery.php') );

/*
* Safe & Secure Checkout (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/safe-checkout.php') );

/*
* Reviews Images
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/reviews-images.php') );

/*
* Reviews Advanced
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/reviews-advanced.php') );

/*
* Order email sku
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/emails.php') );

/*
* Quantity select type
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/quantity-select.php') );

/*
* Quantity discounts
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/quantity-discounts.php') );

/*
* Account (sales booster)
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/account.php') );

/*
* Checkout fields
* ******************************************************************* */
require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/checkout-fields.php') );


if( get_theme_mod( 'linked_variations', false ) ) {
	/*
	* Linked variations
	* ******************************************************************* */
	require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'features/woocommerce/linked-variations.php') );
}


