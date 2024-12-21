<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "API Integrations" for 8theme dashboard.
 *
 * @since   9.2.+
 * @version 1.0.0
 */

$instagram = new Instagram();
$api_data     = $instagram->get_api_data();
$api_settings = $instagram->get_api_settings();
$no_users_class = ' hidden';

$active_tab = 'facebook';
if ( isset( $_GET['etheme-panel-social-tab'] ) ) {
	$active_tab = $_GET['etheme-panel-social-tab'];
}
?>
<div class="etheme-div">
	<h2 class="etheme-page-title etheme-page-title-type-2"><?php esc_html_e( 'Social Authentication', 'xstore' ) ?></h2>
</div>
<div class="etheme-div etheme-social-tabs">
	<ul class="et-filters et-tabs-filters">
	        <li data-network="facebook" class="<?php echo 'facebook' == $active_tab ? 'active' : ''; ?>">
	            <span class="dashicons dashicons-facebook-alt"></span>
	            <span><?php esc_html_e( 'Facebook Loginization', 'xstore' ); ?></span>
	        </li>
	        <li data-network="google" class="<?php echo 'google' == $active_tab ? 'active' : ''; ?>">
	            <span class="dashicons dashicons-google"></span>
	            <span><?php esc_html_e( 'Google Loginization', 'xstore' ); ?></span>
	        </li>
	</ul>
</div>

<div class="etheme-div etheme-social-tab etheme-social-facebook <?php echo 'facebook' != $active_tab ? 'hidden' : ''; ?>">
	<form>
		<p><?php esc_html_e('FaceBook loginization would provide a way for the users to register and login to your WordPress site with a FaceBook account.', 'xstore'); ?></p>
		<p class="et-message et-info">
			<?php echo sprintf(esc_html__('To create FaceBook APP ID follow the %s instructions %s', 'xstore'),
				'<a href="https://developers.facebook.com/docs/apps/register" target="blank">',
				'</a>'); ?>
			<br>
			<?php echo sprintf(esc_html__('Check %s theme documentation %s if it does not work for you or watch the %s video tutorial %s', 'xstore'),
				'<a href="'. etheme_documentation_url('87-facebook-login', false).'" target="blank">',
				'</a>',
				'<a href="https://www.youtube.com/watch?v=mBp33hPMgnA&ab_channel=8theme" target="blank">',
				'</a>'); ?>
		</p>
		<p>
			<label for="facebook_app_id"><?php echo esc_html__('Facebook APP ID', 'xstore'); ?></label>
		</p>
		<p>
			<input id="facebook_app_id" placeholder="<?php echo esc_attr('Enter your App id', 'xstore'); ?>" name="facebook_app_id" type="text" value="<?php echo get_theme_mod('facebook_app_id'); ?>">
		</p>
		<p>
			<label for="facebook_app_secret"><?php echo esc_html__('Facebook APP SECRET', 'xstore'); ?></label>
		</p>
		<p>
			<input id="facebook_app_secret" placeholder="<?php echo esc_attr('Enter your App secret', 'xstore'); ?>" name="facebook_app_secret" type="text" value="<?php echo get_theme_mod('facebook_app_secret'); ?>">
		</p>
		<p>
			<input id="load_social_avatar" type="checkbox" value="<?php echo get_theme_mod( 'load_social_avatar_value', 'off' ); ?>" <?php echo (get_theme_mod( 'load_social_avatar_value', 'off' ) === 'on') ? ' checked' : ''; ?>>
			<label for="load_social_avatar"><?php esc_attr_e('Load user avatar', 'xstore'); ?></label>
			<input type="hidden" id="load_social_avatar_value" name="load_social_avatar_value" value="<?php echo get_theme_mod( 'load_social_avatar_value', 'off' ); ?>">
		</p>

		<p>
			<input id="social_login_on_checkout" type="checkbox" value="<?php echo get_theme_mod( 'social_login_on_checkout_value', 'off' ); ?>" <?php echo (get_theme_mod( 'social_login_on_checkout_value', 'off' ) === 'on') ? ' checked' : ''; ?>>
			<label for="social_login_on_checkout"><?php esc_attr_e('Use social login on checkout', 'xstore'); ?></label>
			<input type="hidden" id="social_login_on_checkout_value" name="social_login_on_checkout_value" value="<?php echo get_theme_mod( 'social_login_on_checkout_value', 'off' ); ?>">
		</p>

        <p>
            <input id="social_login_on_registration" type="checkbox" value="<?php echo get_theme_mod( 'social_login_on_registration_value', 'off' ); ?>" <?php echo (get_theme_mod( 'social_login_on_registration_value', 'off' ) === 'on') ? ' checked' : ''; ?>>
            <label for="social_login_on_registration"><?php esc_attr_e('Use social login for registration form', 'xstore'); ?></label>
            <input type="hidden" id="social_login_on_registration_value" name="social_login_on_registration_value" value="<?php echo get_theme_mod( 'social_login_on_registration_value', 'off' ); ?>">
        </p>

		<p>
			<input class="etheme-network-save et-button et-button-green no-loader" data-network="facebook" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
		</p>
		<p class="etheme-network-save-info info-success hidden">
			<?php esc_html_e('Saved', 'xstore');?>
		</p>
		<p class="etheme-network-save-info info-error hidden">
			<?php esc_html_e('Error while saving', 'xstore');?>
		</p>
	</form>
</div>

<div class="etheme-div etheme-social-tab etheme-social-google <?php echo 'google' != $active_tab ? 'hidden' : ''; ?>">
	<form>
		<p><?php esc_html_e('Google loginization would provide a way for the users to register and login to your WordPress site with a Google account.', 'xstore'); ?></p>
		<p class="et-message et-info">
			<?php echo sprintf(esc_html__('To create Google APP ID follow the %s instructions %s', 'xstore'),
				'<a href="https://developers.google.com/adwords/api/docs/guides/authentication" target="blank">',
				'</a>'); ?>
			<br>
			<?php echo sprintf(esc_html__('Check %s theme documentation %s if it does not work for you or watch the %s video tutorial %s', 'xstore'),
				'<a href="'. etheme_documentation_url('87-google-login', false).'" target="blank">',
				'</a>',
				'<a href="https://www.youtube.com/watch?v=eDclashcCwo" target="blank">',
				'</a>'); ?>
		</p>
		<p>
			<label for="google_app_id"><?php echo esc_html__('Google client id', 'xstore'); ?></label>
		</p>
		<p>
			<input id="google_app_id" placeholder="<?php echo esc_attr('Enter your App id', 'xstore'); ?>" name="google_app_id" type="text" value="<?php echo get_theme_mod('google_app_id'); ?>">
		</p>
		<p>
			<label for="google_app_secret"><?php echo esc_html__('Google client secret', 'xstore'); ?></label>
		</p>
		<p>
			<input id="google_app_secret" placeholder="<?php echo esc_attr('Enter your App secret', 'xstore'); ?>" name="google_app_secret" type="text" value="<?php echo get_theme_mod('google_app_secret'); ?>">
		</p>
		<p>
			<input class="etheme-network-save et-button et-button-green no-loader" data-network="google" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
		</p>
		<p class="etheme-network-save-info info-success hidden">
			<?php esc_html_e('Saved', 'xstore');?>
		</p>
		<p class="etheme-network-save-info info-error hidden">
			<?php esc_html_e('Error while saving', 'xstore');?>
		</p>
	</form>
</div>

<input type="hidden" name="nonce_update_network-settings" value="<?php echo wp_create_nonce( 'etheme_update_network-settings' ); ?>">