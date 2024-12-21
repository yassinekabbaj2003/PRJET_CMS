<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     9.2.0
 * @xstore-version 9.4.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( get_query_var( 'et_is-loggedin', false) )
	return;

$woo_new_7_0_1_version = etheme_woo_version_check();
$button_class = '';
if ( $woo_new_7_0_1_version ) {
    $button_class = wc_wp_theme_get_element_class_name( 'button' );
}

$layout_new = apply_filters('etheme_account_login_form_new_layout', false);

?>
<form method="post" class="woocommerce-form woocommerce-form-login login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="username"><?php esc_html_e( 'Username or email', 'xstore' ); ?> <span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'xstore' ); ?></span></label>
		<input type="text" class="input-text" name="username" required aria-required="true" id="username"<?php if ($layout_new) : ?> value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"<?php endif; ?>/>
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="password"><?php esc_html_e( 'Password', 'xstore' ); ?> <span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'xstore' ); ?></span></label>
		<input class="input-text woocommerce-Input" type="password" name="password" required aria-required="true" id="password" <?php if (!$layout_new) : ?> autocomplete="current-password"<?php endif; ?>/>
	</p>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<p class="form-row form-row-wide flex justify-content-between align-center lost_password flex-wrap">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
			<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'xstore' ); ?></span>
		</label>
        <?php if ( !$layout_new ) : ?>
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'xstore' ); ?></a>
        <?php endif; ?>
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide<?php if ( !$layout_new ) echo ' m0'; ?>">
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
		<button type="submit" class="button<?php echo esc_attr( $button_class ? ' ' . $button_class : '' ); ?>" name="login" value="<?php esc_attr_e( 'Login', 'xstore' ); ?>"><?php esc_html_e( 'Login', 'xstore' ); ?></button>
	</p>
    <?php if ( $layout_new ) : ?>
        <p class="text-center m0">
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'xstore' ); ?></a>
        </p>
    <?php endif; ?>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>