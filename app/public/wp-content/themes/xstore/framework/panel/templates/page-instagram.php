<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Instagram" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.8
 * @log 1.0.6
 * ADDED etheme-social-tabs
 * @log 1.0.7
 * ADDED: escape_albums
 * @log 1.0.8
 * ADDED: Show number of posts in album option
 */

$instagram = new Instagram();
$api_data     = $instagram->get_api_data();
$api_settings = $instagram->get_api_settings();
$no_users_class = ' hidden';

$is_grap = false;

$active_tab = 'instagram';
if ( isset( $_GET['etheme-panel-social-tab'] ) ) {
    $active_tab = $_GET['etheme-panel-social-tab'];
}
$global_admin_class = EthemeAdmin::get_instance();
?>
<div class="etheme-div">
    <h2 class="etheme-page-title etheme-page-title-type-2"><?php esc_html_e( 'API Integrations', 'xstore' ) ?></h2>
</div>
<div class="etheme-div etheme-social-tabs">
    <ul class="et-filters et-tabs-filters">
        <li data-network="instagram" class="<?php echo 'instagram' == $active_tab ? 'active' : ''; ?>">
            <span class="dashicons dashicons-instagram"></span>
            <span><?php esc_html_e( 'Instagram Accounts', 'xstore' ); ?></span>
        </li>
        <li data-network="google-map" class="<?php echo 'google-map' == $active_tab ? 'active' : ''; ?>">
            <span class="dashicons dashicons-location-alt"></span>
            <span><?php esc_html_e( 'Google Map', 'xstore' ); ?></span>
        </li>
        <li data-network="adobe-fonts" class="<?php echo 'adobe-fonts' == $active_tab ? 'active' : ''; ?>">
            <span class="dashicons dashicons-editor-spellcheck"></span>
            <span><?php esc_html_e( 'Adobe Fonts', 'xstore' ); ?></span>
        </li>
    </ul>
</div>

<div class="etheme-div etheme-social-tab etheme-social etheme-social-instagram <?php echo 'instagram' != $active_tab ? 'hidden' : ''; ?>">
    <div class="et-col-7 etheme-instagram-connected">
        <p><?php echo sprintf( esc_html__('Instagram widget and Instagram WPBakery element use the special API that requires authentication to show your photos on any theme by 8theme. Authenticated requests need Instagram Access Token. You can get this by clicking the %1s Add account %2s button below.', 'xstore'), '<strong>', '</strong>'); ?></p><p><?php echo sprintf( esc_html__('After clicking, you will be prompted by Instagram to sign in your Instagram account and then you will be asked to authorize %1s 8themeapp %2s to access your Instagram photos.', 'xstore'), '<strong>', '</strong>' ); ?></p>
        <p class="et-message et-info">
			<?php esc_html_e('Generating a token creates a private token for your use only. We will not have access to your feed.', 'xstore'); ?>
        </p>

		<?php if ( isset($_GET['i_error']) && $_GET['i_error'] == 'business_permissions' ): ?>
            <p class="et-message et-error">
				<?php esc_html_e('Seems your user does not have correct permissions to display media of the business account.', 'xstore'); ?>
            </p>
		<?php endif ?>
        <p class="etheme-no-users et-message et-info<?php echo ( is_array($api_data) && count( $api_data ) ) ? esc_attr( $no_users_class ) : ''; ?>"><?php esc_html_e( 'You have not connected any account yet', 'xstore' ) ?></p>

        <p class="et-message et-warning">
		    <?php esc_html_e('We wish to inform you that the feature of connecting Instagram Business accounts is currently deprecated and will be discontinued in a forthcoming update. We kindly request you to input an access token as an alternative. For further guidance, please refer to our
', 'xstore'); ?>
            <a href="<?php etheme_documentation_url('189-instagram-api-token'); ?>" target="_blank"><?php esc_html_e('documentation.', 'xstore'); ?></a>
        </p>

        <?php if ($is_grap) : ?>
            <a
                    class="etheme-instagram-auto et-button et-button-green no-loader last-button etheme-call-popup et-facebook-corporate"
                    href="#"
                    data-personal="<?php echo esc_url($instagram->get_urls()->personal); ?>"
                    data-business="<?php echo esc_url($instagram->get_urls()->business); ?>"
            >
                <span class="dashicons dashicons-instagram"></span>
                <?php esc_html_e('Add account', 'xstore'); ?>
            </a>
        <?php endif; ?>
        <div class="etheme-instagram-manual-wrapper">
	        <?php if ($is_grap) : ?>
                <a class="etheme-instagram-manual et-button et-button-grey no-loader last-button" href="">
                    <span class="dashicons dashicons-instagram"></span>
                    <?php esc_html_e( 'Manually add account', 'xstore' ); ?>
                </a>
	        <?php endif; ?>
			<div class="etheme-instagram-manual-form <?php echo ($is_grap) ? 'hidden' : ''; ?>">
				<input id="etheme-manual-token" name="etheme-manual-token" type="text" placeholder="<?php echo esc_attr__('Enter a valid Instagram access token', 'xstore'); ?>">
				<a class="etheme-manual-btn et-button et-button-blue no-loader et-facebook-corporate" href="">
                    <span class="dashicons dashicons-instagram"></span>
                    <?php esc_html_e( 'Connect', 'xstore' ) ?>
                </a>
				<a href="<?php etheme_documentation_url('189-instagram-api-token'); ?>" target="_blank"><?php esc_html_e( 'Do not have access token ?', 'xstore' ) ?></a>
                <p></p>
				<p class="etheme-form-error hidden et-message et-error"><?php esc_html_e( 'Wrong token', 'xstore' ) ?></p>
				<p class="etheme-form-error-holder et-message et-error hidden"></p>
			</div>
		</div>

		<?php if ( is_array($api_data) && count( $api_data ) ) :
			foreach ( $api_data as $key => $value ) :
				$value = json_decode( $value, true );
				$render_user_data = array();

				if (isset($value['error'])){
					continue;
				}

				if ( isset($value['data']) ) {
					$render_user_data['class'] = 'old-api';
					$render_user_data['username'] = $value['data']['username'];
					$render_user_data['account_type'] = ( $value['data']['is_business'] ) ? 'BUSINESS (Legacy API)' : 'PERSONAL (Legacy API)';

				} else {
					$render_user_data['class'] = 'new-api';
					$render_user_data['username'] = $value['username'];
					$render_user_data['account_type'] = $value['account_type'] . ' (NEW API)';
					if ( isset( $value['connection_type'] ) ) {
						if ( $value['connection_type'] == 'PERSONAL' && $value['account_type'] == 'BUSINESS' ) {
							$render_user_data['account_type'] .= ' connected like personal';
						}
					}
				}
				?>

                <div class="etheme-user <?php echo esc_attr($render_user_data['class']); ?>">
                    <div class="user-info">
                        <div class="user-name"><b><?php esc_html_e( 'Username:', 'xstore' ); ?></b> <?php echo esc_html( $render_user_data['username'] ); ?></div>
                        <div class="user-account-type"><b><?php esc_html_e( 'Account type:', 'xstore' ); ?></b> <?php echo esc_html( $render_user_data['account_type'] ); ?>

                            <div class="et-tooltip">
                                <span class="dashicons dashicons-editor-help et-help tooltip"></span>
                                <span class="et-tooltip-content">
								<?php echo __('Due to future Instagram platform changes (<a href="https://facebook.cmail20.com/t/j-l-ckhkjhy-thhhlitldt-j/">Instagram Graph API</a> and the <a href="https://facebook.cmail20.com/t/j-l-ckhkjhy-thhhlitldt-t/">Instagram Basic Display API</a>, 29 June 2020) Instagram accounts that use Instagram Legacy API will need to be reconnected in order for them to continue updating.', 'xstore'); ?>
							</span>
                            </div>

                        </div>
                        <div class="user-token" style="word-break: break-all;" data-token="<?php echo esc_attr( $key ); ?>"><b><?php esc_html_e( 'Access token:', 'xstore' ) ?></b> <?php echo esc_html( $key ); ?></div>
                        <span class="user-remove red-color"><?php echo esc_html__('Delete', 'xstore'); ?></span>
                    </div>
                </div>
			<?php endforeach; ?>
		<?php else : ?>
			<?php $no_users_class = ''; ?>
		<?php endif; ?>
    </div>

    <div class="et-col-5 etheme-instagram-settings">
        <p>
            <label for="instagram_time"><?php esc_html_e('Check for new posts every', 'xstore'); ?></label>
        </p>
        <p class="etheme-instagram-refresh">
            <input id="instagram_time" name="instagram_time" type="text" value="<?php echo esc_attr( $api_settings['time'] ); ?>">
            <select name="instagram_time_type" id="instagram_time_type">
                <option value="min" <?php selected( $api_settings['time_type'], 'min' ); ?>><?php esc_html_e( 'mins', 'xstore' ); ?></option>
                <option value="hour" <?php selected( $api_settings['time_type'], 'hour' ); ?>><?php esc_html_e( 'hours', 'xstore' ); ?></option>
                <option value="day" <?php selected( $api_settings['time_type'], 'day' ); ?>><?php esc_html_e( 'days', 'xstore' ); ?></option>
            </select>
            <input class="etheme-instagram-save et-button et-button-green no-loader" type="submit" value="save">
            <span class="hidden etheme-instagram-save-info info-success"><?php esc_html_e('Updated', 'xstore'); ?></span>
            <span class="hidden etheme-instagram-save-info info-error"><?php esc_html_e('Error, please try again later', 'xstore'); ?></span>
            <a class="etheme-instagram-reinit et-button et-button-grey" href="<?php echo admin_url('admin.php?page=et-panel-social&et_reinit_instagram=true'); ?>">
                <?php $global_admin_class->get_loader(); ?>
                <span class="dashicons dashicons-image-rotate"></span>
                <?php esc_html_e('Refresh instagram', 'xstore'); ?>
            </a>
            <p>
                <label for="escape_albums">
                    <input id="escape_albums" type="checkbox" name="escape_albums" <?php echo (isset($api_settings['escape_albums']) && $api_settings['escape_albums']) ? 'checked': ''?>>
                    <?php esc_html_e( 'Show everything except album and video posts', 'xstore' ); ?>
                </label>
            </p>
            <p>
                <label for="count_albums_photo">
                    <input id="count_albums_photo" type="checkbox" name="count_albums_photo" <?php echo (isset($api_settings['count_albums_photo']) && $api_settings['count_albums_photo']) ? 'checked': ''?>>
                    <?php esc_html_e( 'Show number of posts in album', 'xstore' ); ?>
                </label>
            </p>
        </p>
    </div>
</div>

<div class="etheme-div etheme-social-tab etheme-social-google-map <?php echo 'google-map' != $active_tab ? 'hidden' : ''; ?>">
    <form>
        <p><?php echo esc_html__('Please, enter your Google API Key to use the Elementor Google Map element', 'xstore'); ?></p>
        <p class="et-message et-info"><?php echo sprintf(esc_html__('If you don\'t have api key, please, click %s here %s to generate one.', 'xstore'),
                '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key">',
                '</a>'); ?></p>
        <p>
            <label for="google_map_api"><?php echo esc_html__('Google Map API Key', 'xstore'); ?></label>
        </p>
        <p>
            <input id="google_map_api" placeholder="<?php echo esc_attr('Enter your API key', 'xstore'); ?>" name="google_map_api" type="text" value="<?php echo get_theme_mod('google_map_api'); ?>">
        </p>
        <p>
            <input class="etheme-network-save et-button et-button-green no-loader" data-network="google-map" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
        </p>
        <p class="etheme-network-save-info info-success hidden">
		    <?php esc_html_e('Saved', 'xstore');?>
        </p>
        <p class="etheme-network-save-info info-error hidden">
		    <?php esc_html_e('Error while saving', 'xstore');?>
        </p>
    </form>
</div>

<div class="etheme-div etheme-social-tab etheme-social-adobe-fonts <?php echo 'adobe-fonts' != $active_tab ? 'hidden' : ''; ?>">
    <form>
        <p class="et-message et-info"><?php echo esc_html__('Adobe Fonts, previously known as Typekit, is a subscription-based font service that provides a vast library of high-quality fonts for creative projects.', 'xstore'); ?></p>
        <?php $adobe_benefits = array(
            esc_html__('Extensive Library: Access to thousands of fonts across various styles and families.', 'xstore'),
            esc_html__('Easy Integration: Seamlessly integrates with Adobe Creative Cloud apps.', 'xstore'),
            esc_html__('Web and Print Ready: Fonts are optimized for both web and print use.', 'xstore'),
            esc_html__('Commercial Licensing: Fonts are licensed for commercial use, ensuring legal compliance.', 'xstore'),
            esc_html__('Consistent Quality: High-quality fonts from renowned type foundries.', 'xstore'),
        ) ?>
        <h4><?php echo esc_html__('Benefits:', 'xstore'); ?></h4>
        <ol>
            <?php foreach ($adobe_benefits as $adobe_benefit) {
                echo '<li>'.$adobe_benefit.'</li>';
            } ?>
        </ol>
        <br/>
        <p>
            <label for="etheme_typekit_id"><?php echo esc_html__('Adobe project IDs', 'xstore'); ?></label>
        </p>
        <p class="et-message et-info">
            <?php
            echo sprintf(
                '%s <a target="_blank" href="https://fonts.adobe.com/my_fonts#web_projects-section">%s</a> %s',
                esc_html__( 'Enter your', 'xstore' ),
                esc_html__( 'Adobe project IDs', 'xstore' ),
                esc_html__( 'separate with coma: tpm3one, qny2aiv. ', 'xstore' )
            )
            ?>
        </p>
        <p>
            <input id="etheme_typekit_id" placeholder="<?php echo esc_attr('tpm3one, qny2aiv', 'xstore'); ?>" name="etheme_typekit_id" type="text" value="<?php echo get_theme_mod('etheme_typekit_id', ''); ?>">
        </p>

        <p>
            <label for="etheme_typekit_fonts"><?php echo esc_html__('Adobe font face', 'xstore'); ?></label>
        </p>
        <p>
            <input id="etheme_typekit_fonts" placeholder="<?php echo esc_attr('futura-pt, lato', 'xstore'); ?>" name="etheme_typekit_fonts" type="text" value="<?php echo get_theme_mod('etheme_typekit_fonts', ''); ?>">
        </p>
        <p>
            <input class="etheme-network-save et-button et-button-green no-loader" data-network="adobe-fonts" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
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