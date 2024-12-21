<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

class ETheme_Version_Check {

	private $current_version = '';
	private $new_version = '';
	private $theme_name = '';
	private $api_url = '';
	private $ignore_key = 'etheme_notice';
	public $information;
	public $api_key;
	public $url = '';
	public $notices;
	private $theme_id = 15780546;
	public $is_subscription = false;
	public $activated_data = array();
	public $is_license = true;
	public $is_new_deactivate = true;


	function __construct($update_transient = true) {
		$theme_data = wp_get_theme('xstore');
		$this->activated_data = $activated_data = get_option( 'etheme_activated_data' );
		$this->current_version = $theme_data->get('Version');
		$this->theme_name = strtolower($theme_data->get('Name'));
		$this->api_url = apply_filters('etheme_protocol_url', ETHEME_API);
		$this->url = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/demo/xstore/change-log.php');
		$this->api_key = ( ! empty( $activated_data['api_key'] ) ) ? $activated_data['api_key'] : false;
		$this->is_subscription = ( isset($activated_data['item']) && isset($activated_data['item']['license']) && $activated_data['item']['license'] == '8theme-subscription' );

		$this->is_new_deactivate = true;

		add_action('admin_init', array($this, 'dismiss_notices'));
		add_action('admin_notices', array($this, 'show_notices'), 50 );

		add_action( 'wp_ajax_et_support_refresh', array($this, 'et_support_refresh') );
		add_action('wp_ajax_etheme_deactivate_theme', array($this, 'deactivate'));

		add_action('wp_ajax_etheme_activate_theme', array($this, 'ajax_etheme_activate_theme'));
		add_action('wp_ajax_etheme_check_activation_data', array($this, 'check_activation_data'));



		if( ! etheme_is_activated() ) {
			#$this->activation_notice();
			return;
		}

		if( $this->is_update_available() ) {
			if ( $this->major_update( 'both' ) ) add_action( 'admin_head', array( $this, 'major_update_holder' ) );
			//$this->update_notice();
		}

		add_action( 'switch_theme', array( $this, 'update_dismiss' ) );

		add_action( 'current_screen', array( $this, 'api_results_init' ) );

		if ($update_transient) {
			add_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_update_transient' ) );
			//add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);
		}

	}

	public function api_results_init( $current_screen ) {
		if ( $current_screen->base !== 'woocommerce_page_wc-status' ) {
			add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);
		}

	}

	public function activation_page() {
		?>

		<?php if ( etheme_is_activated() ): ?>
			<?php
			$activated_data = get_option( 'etheme_activated_data' );
			$purchase = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
			$supported_until = ( isset( $activated_data['item'] ) && isset( $activated_data['item']['supported_until'] ) && ! empty( $activated_data['item']['supported_until'] ) ) ? $activated_data['item']['supported_until'] : '';

			?>

            <p>
                <?php echo esc_html__('Your theme is now registered! You can enjoy lifetime updates through','xstore'); ?>
                <a href="<?php echo admin_url( 'update-core.php' ); ?>" target="_blank"><?php echo esc_html__('Dashboard -> Updates', 'xstore'); ?></a>
                <?php echo esc_html__('receive high-quality 24/7 customer support, and access many other fantastic features. Enjoy!', 'xstore')?>
            </p>
			<?php $this->process_form(); ?>
            <p class="etheme-purchase">
                <span class="etheme-purchase-inner">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" class="licence-key" fill="currentColor">
                        <path d="M20.864 0c-6.016 0-10.88 4.896-10.88 10.88 0 1.28 0.224 2.528 0.672 3.744l-10.112 10.112c-0.192 0.192-0.288 0.448-0.288 0.704v5.6c0 0.544 0.448 0.992 0.992 0.992h4.608c0.256 0 0.512-0.096 0.704-0.288l1.536-1.536c0.192-0.192 0.288-0.448 0.288-0.704v-1.696h1.696c0.544 0 0.992-0.448 0.992-0.992v-0.576h0.544c0.544 0 0.992-0.448 0.992-0.992v-1.472h1.472c0.256 0 0.512-0.096 0.704-0.288l2.368-2.368c1.184 0.448 2.464 0.672 3.744 0.672 6.016 0 10.88-4.896 10.88-10.88-0.032-6.016-4.896-10.912-10.912-10.912zM2.208 30.048v-0.8l9.44-9.44c0.16-0.16 0.256-0.384 0.256-0.64s-0.096-0.48-0.256-0.64c-0.352-0.352-0.928-0.352-1.28 0l-8.16 8.128v-0.832l10.304-10.304c0.288-0.288 0.352-0.704 0.192-1.088-0.48-1.12-0.736-2.336-0.736-3.584 0-4.928 4-8.928 8.928-8.928s8.928 4 8.928 8.928c0 4.928-4 8.928-8.928 8.928-1.248 0-2.464-0.256-3.584-0.736-0.384-0.16-0.8-0.096-1.088 0.192l-2.56 2.56h-2.048c-0.544 0-0.992 0.448-0.992 0.992v1.472h-0.544c-0.544 0-0.992 0.448-0.992 0.992v0.544h-1.696c-0.544 0-0.992 0.448-0.992 0.992v2.272l-0.96 0.96h-3.232zM24.128 10.752c1.728 0 3.136-1.408 3.136-3.104 0-1.728-1.408-3.104-3.136-3.104s-3.104 1.408-3.104 3.104c-0.032 1.696 1.376 3.104 3.104 3.104zM22.976 7.648c0-0.64 0.512-1.152 1.152-1.152s1.152 0.512 1.152 1.152-0.512 1.152-1.152 1.152-1.152-0.544-1.152-1.152z"></path>
                    </svg>
                    <span><?php echo $this->maskHalfOfStringFromEnd($purchase); ?></span>
                </span>
                <?php //if (!$this->is_subscription): ?>
                <span class="et-button et-button-active et_theme-deactivator <?php echo ($this->is_new_deactivate) ? 'new-theme-deactivate': ''; ?>">
                            <?php esc_html_e( 'Unregister', 'xstore' ); ?>
                            <span class="et-loader">
                                <svg class="loader-circular" viewBox="25 25 50 50">
                                    <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                                </svg>
                            </span>
                        </span>
                <?php //endif; ?>
            </p>

			<?php $this->support_status($supported_until); ?>
			<?php if (!$this->is_subscription): ?>
<!--                <p class="et-message et-info">-->
<!--					Due to the Envato's <a href="https://themeforest.net/licenses/terms/regular">license policy</a> one standard license is valid only for 1 project.-->
<!--                    Running multiple projects on a single license is a copyright violation. If you want to view all your active domains, please visit our website and check the activation list in your <a href="https://www.8theme.com/account/">account</a>.-->
<!--                </p>-->
			<?php endif; ?>
		<?php else: ?>

            <p class="">
                <?php echo esc_html__('It is important to activate XStore using your purchase code to access premium plugins and lifetime auto updates.', 'xstore'); ?>
            </p>
			<?php $this->process_form(); ?>

            <form class="xstore-form" method="post">
                <span class="etheme-purchase-inner">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" class="licence-key" fill="currentColor">
                        <path d="M20.864 0c-6.016 0-10.88 4.896-10.88 10.88 0 1.28 0.224 2.528 0.672 3.744l-10.112 10.112c-0.192 0.192-0.288 0.448-0.288 0.704v5.6c0 0.544 0.448 0.992 0.992 0.992h4.608c0.256 0 0.512-0.096 0.704-0.288l1.536-1.536c0.192-0.192 0.288-0.448 0.288-0.704v-1.696h1.696c0.544 0 0.992-0.448 0.992-0.992v-0.576h0.544c0.544 0 0.992-0.448 0.992-0.992v-1.472h1.472c0.256 0 0.512-0.096 0.704-0.288l2.368-2.368c1.184 0.448 2.464 0.672 3.744 0.672 6.016 0 10.88-4.896 10.88-10.88-0.032-6.016-4.896-10.912-10.912-10.912zM2.208 30.048v-0.8l9.44-9.44c0.16-0.16 0.256-0.384 0.256-0.64s-0.096-0.48-0.256-0.64c-0.352-0.352-0.928-0.352-1.28 0l-8.16 8.128v-0.832l10.304-10.304c0.288-0.288 0.352-0.704 0.192-1.088-0.48-1.12-0.736-2.336-0.736-3.584 0-4.928 4-8.928 8.928-8.928s8.928 4 8.928 8.928c0 4.928-4 8.928-8.928 8.928-1.248 0-2.464-0.256-3.584-0.736-0.384-0.16-0.8-0.096-1.088 0.192l-2.56 2.56h-2.048c-0.544 0-0.992 0.448-0.992 0.992v1.472h-0.544c-0.544 0-0.992 0.448-0.992 0.992v0.544h-1.696c-0.544 0-0.992 0.448-0.992 0.992v2.272l-0.96 0.96h-3.232zM24.128 10.752c1.728 0 3.136-1.408 3.136-3.104 0-1.728-1.408-3.104-3.136-3.104s-3.104 1.408-3.104 3.104c-0.032 1.696 1.376 3.104 3.104 3.104zM22.976 7.648c0-0.64 0.512-1.152 1.152-1.152s1.152 0.512 1.152 1.152-0.512 1.152-1.152 1.152-1.152-0.544-1.152-1.152z"></path>
                    </svg>
                    <input type="text" name="purchase-code" placeholder="Example: f20b1cdd-ee2a-1c32-a146-66eafe" id="purchase-code" />
                </span>
                <span class="et-button et-button-green no-transform active activate-license-btn-holder popup-caller" onclick="">
                            <span class="et-loader">
                                <svg class="loader-circular" viewBox="25 25 50 50">
                                    <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                                </svg>
                            </span>
                            <?php esc_attr_e( 'Register', 'xstore' ); ?>
                        </span>


                <!--                    <span class="et-button et-button-green no-transform active activate-license-btn-holder" onclick="jQuery('.xstore-form .activate-license-btn').trigger('click')">-->
                <!--                        <span class="et-loader">-->
                <!--                            <svg class="loader-circular" viewBox="25 25 50 50">-->
                <!--                                <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>-->
                <!--                            </svg>-->
                <!--                        </span>-->
                <!--                        --><?php //esc_attr_e( 'Register', 'xstore' ); ?>
                <!--                    </span>-->

                <input class="et-button et-button-green no-transform no-loader active hidden activate-license-btn" name="xstore-purchase-code" type="submit" value="<?php esc_attr_e( 'Register', 'xstore' ); ?>" />

				<?php if(false) :?>
                    <p <?php if(!$this->is_license ) echo 'style="color: #ff0000;"'; ?> >
                        <input type="checkbox" id="form-license" name="form-license">
                        <label for="form-license">
                            Please confirm that, according to Envato's license policy, a single standard license is only valid for one project. Using a single license for multiple projects is a violation of copyright. For more information, please refer to
                        </label>

                        <a style="color: #2271b1;" href="https://themeforest.net/licenses/terms/regular" target="_blank">Envato's license policy.
                        </a>
                    </p>
				<?php endif; ?>
                <!--                    <p class="et-message et-info">-->
                <!--                        By clicking "Register," you agree to allow 8theme.com to store your purchase code and user data.-->
                <!--                    </p>-->

            </form>

		<?php endif; ?>
		<?php
	}

	public function show_notices() {
		global $current_user;
		$user_id = $current_user->ID;
		if( ! empty( $this->notices ) ) {
			foreach ($this->notices as $key => $notice) {
				if ( ! get_user_meta($user_id, $this->ignore_key . $key) ) {
					echo '<div class="et-message et-info">' . $notice['message'] . '</div>';
				}
			}
		}
	}

	public function dismiss_notices() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( isset( $_GET['et-hide-notice'] ) && isset( $_GET['_et_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_et_notice_nonce'], 'etheme_hide_notices_nonce' ) ) {
				return;
			}

			add_user_meta($user_id, $this->ignore_key . '_' . $_GET['et-hide-notice'], 'true', true);
		}
	}

	public function setup_notice() {
		$this->notices['_setup'] = array(
			'message' => '
                <p><strong>Welcome to XStore</strong> – You‘re almost ready to start selling :)</p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Run the Setup Wizard</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'setup' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Skip Setup</a></p>
            '
		);
	}

	public function activation_notice() {
		$this->notices['_activation'] = array(
			'message' => '
                <p><strong>You need to activate XStore</strong></p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Activate theme</a></p>
            '
		);
	}

	public function update_notice() {
		if( isset( $_GET['_wpnonce'] )) return;

		$this->notices['_update'] = array(
			'message' => '
                    <p>There is a new version of ' . ETHEME_THEME_NAME . ' Theme available.</p>' . $this->major_update( 'msg-b' ) . '
                    <p class="submit"><a href="' . admin_url( 'update-core.php?force-check=1&theme_force_check=1' ) . '" class="button-primary">Update now</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'update' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Dismiss</a></p>
                ',
		);
	}

	private function api_get_version() {

		$raw_response = wp_remote_get($this->api_url . '?theme=' . ETHEME_THEME_SLUG);
		if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
			$response = json_decode($raw_response['body'], true);
			if(!empty($response['version'])) $this->new_version = $response['version'];
		}
	}

	public function update_dismiss() {
		global $current_user;
		#$user_id = $current_user->ID;
		#delete_user_meta($user_id, $this->ignore_key);
	}


	public function update_transient($value, $transient) {
		// if(isset($_GET['theme_force_check']) && $_GET['theme_force_check'] == '1') return false;
		if(isset($_GET['force-check']) && $_GET['force-check'] == '1') return false;
		return $value;
	}


	public function set_update_transient($transient) {
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return $transient;
		}

		$this->check_for_update();

		if (is_null($transient)) {
			$transient = new stdClass();
		}

		if( isset( $transient ) && ! isset( $transient->response ) ) {
			$transient->response = array();
		}

		if( ! empty( $this->information ) && is_object( $this->information ) ) {
			if( $this->is_update_available() ) {
				$transient->response[ $this->theme_name ] = json_decode( json_encode( $this->information ), true );
			}
		}

		remove_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );

		return $transient;
	}


	public function api_results($result, $action, $args) {

		$this->check_for_update();

		if( isset( $args->slug ) && $args->slug == $this->theme_name && $action == 'theme_information') {
			if( is_object( $this->information ) && ! empty( $this->information ) ) {
				$result = $this->information;
			}
		}

		return $result;
	}


	protected function check_for_update() {

		$force = false;

		// if( isset( $_GET['theme_force_check'] ) && $_GET['theme_force_check'] == '1') $force = true;

		if( isset( $_GET['force-check'] ) && $_GET['force-check'] == '1') $force = true;

		// Get data
		if( empty( $this->information ) ) {
			$version_information = get_option( 'xstore-update-info', false );
			$version_information = $version_information ? $version_information : new stdClass;

			$this->information = is_object( $version_information ) ? $version_information : maybe_unserialize( $version_information );

		}

		$last_check = get_option( 'xstore-update-time' );
		if( $last_check == false ){
			update_option( 'xstore-update-time', time(), 'no' );
		}

		if( time() - $last_check > 172800 || $force || $last_check == false ){

			$version_information = $this->api_info();

			if( $version_information ) {
				update_option( 'xstore-update-time', time(), 'no' );

				$this->information          = $version_information;
				$this->information->checked = time();
				$this->information->url     = $this->url;
				$this->information->package = $this->download_url();

			}

		}

		// Save results
		update_option( 'xstore-update-info', $this->information );
	}

	public function api_info() {
		$version_information = new stdClass;

		$response = wp_remote_get( $this->api_url . 'info/' . $this->theme_name . '?plugin=et-core' );
		$response_code = wp_remote_retrieve_response_code( $response );

		if( $response_code != '200' ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );
		if( ! isset( $response ) || ! isset( $response->new_version ) || empty( $response->new_version ) ) {
			return $version_information;
		}

		$version_information = $response;

		return $version_information;
	}

	public function is_update_available() {
		return version_compare( $this->current_version, $this->release_version(), '<' );
	}

	public function download_url() {
		$activated_data = get_option( 'etheme_activated_data' );
		$purchase = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
		$url = $this->api_url . 'files/get/' . $this->theme_name . '.zip?token=' . $this->api_key . '&code=' . $purchase;
		return apply_filters( 'etheme_theme_url', $url );
	}
	public function release_version() {
		$this->check_for_update();

		if ( isset( $this->information ) && isset( $this->information->new_version ) ) {
			return $this->information->new_version;
		}
	}


	public function activate( $purchase, $args ) {

		$data = array(
			'api_key' => $args['token'],
			'theme' => ETHEME_PREFIX,
			'purchase' => $purchase,
		);

		foreach ( $args as $key => $value ) {
			$data['item'][$key] = $value;
		}

		update_option( 'envato_purchase_code_15780546', $purchase );
		update_option( 'etheme_activated_data', maybe_unserialize( $data ) );
		update_option( 'etheme_is_activated', true );
	}

	public function process_form() {

		if (
			isset( $_POST['xstore-purchase-code'] )
			&& ! empty( $_POST['xstore-purchase-code'] )
			&& ! isset($_POST['form-license'])
			&& empty( $_POST['form-license'] )
		){
			$this->is_license = false;
		}

		if(
			isset( $_POST['xstore-purchase-code'] )
			&& ! empty( $_POST['xstore-purchase-code'] )
			&& isset($_POST['form-license'])
			&& ! empty( $_POST['form-license'] )
		) {
			$code = trim( $_POST['purchase-code'] );

			if( empty( $code ) ) {
				echo  '<p class="et-message et-error">The code is missing, <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">here</a> you have more information where you can find it</p>';
				return;
			}

			$response = wp_remote_get( $this->api_url . 'activate/' . $code . '?envato_id='. $this->theme_id .'&domain=' .$this->domain() );
			if ( ! $response ) {
				echo  '<p class="et-message et-error">There was an error with the API request call; it was unable to connect to 8theme.com.</p>';
				return;
			}
			$response_code = wp_remote_retrieve_response_code( $response );

			if( $response_code != '200' ) {

				if( is_wp_error( $response ) ) {
					echo '<p class="et-message et-error">' . $response->get_error_message() . '</p>';
				}

				if (!$response_code){
					echo  '<p class="et-message et-error">There was an error with the API request call. This is a common problem caused by an SSL certificate. Please check it  <a href="https://www.sslshopper.com/ssl-checker.html" target="_blank" rel="nofollow">here</a>. If your certificate does not exist or has errors, please contact your server provider.</p>';
					return;
				}
				echo  '<p class="et-message et-error">API request call error. Response code - <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank" rel="nofollow">' . $response_code . '</a></p>';
				return;
			}

			$data = json_decode( wp_remote_retrieve_body($response), true );

			if( isset( $data['error'] ) ) {
				echo  '<p class="et-message et-error">' . $data['error'] . '</p>';
				return;
			}

			if ( ! isset($data['verified']) ){
				echo  '<p class="et-message et-error">Sorry, I cannot get the API response..</p>';
				return;
			}

			if( ! $data['verified'] ) {
				echo  '<p class="et-message et-error">Sorry, the code is incorrect. Please try again.</p>';
				return;
			}

			$this->activate( $code, $data );

            $svg_key_icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" class="licence-key" fill="currentColor">
<path d="M20.864 0c-6.016 0-10.88 4.896-10.88 10.88 0 1.28 0.224 2.528 0.672 3.744l-10.112 10.112c-0.192 0.192-0.288 0.448-0.288 0.704v5.6c0 0.544 0.448 0.992 0.992 0.992h4.608c0.256 0 0.512-0.096 0.704-0.288l1.536-1.536c0.192-0.192 0.288-0.448 0.288-0.704v-1.696h1.696c0.544 0 0.992-0.448 0.992-0.992v-0.576h0.544c0.544 0 0.992-0.448 0.992-0.992v-1.472h1.472c0.256 0 0.512-0.096 0.704-0.288l2.368-2.368c1.184 0.448 2.464 0.672 3.744 0.672 6.016 0 10.88-4.896 10.88-10.88-0.032-6.016-4.896-10.912-10.912-10.912zM2.208 30.048v-0.8l9.44-9.44c0.16-0.16 0.256-0.384 0.256-0.64s-0.096-0.48-0.256-0.64c-0.352-0.352-0.928-0.352-1.28 0l-8.16 8.128v-0.832l10.304-10.304c0.288-0.288 0.352-0.704 0.192-1.088-0.48-1.12-0.736-2.336-0.736-3.584 0-4.928 4-8.928 8.928-8.928s8.928 4 8.928 8.928c0 4.928-4 8.928-8.928 8.928-1.248 0-2.464-0.256-3.584-0.736-0.384-0.16-0.8-0.096-1.088 0.192l-2.56 2.56h-2.048c-0.544 0-0.992 0.448-0.992 0.992v1.472h-0.544c-0.544 0-0.992 0.448-0.992 0.992v0.544h-1.696c-0.544 0-0.992 0.448-0.992 0.992v2.272l-0.96 0.96h-3.232zM24.128 10.752c1.728 0 3.136-1.408 3.136-3.104 0-1.728-1.408-3.104-3.136-3.104s-3.104 1.408-3.104 3.104c-0.032 1.696 1.376 3.104 3.104 3.104zM22.976 7.648c0-0.64 0.512-1.152 1.152-1.152s1.152 0.512 1.152 1.152-0.512 1.152-1.152 1.152-1.152-0.544-1.152-1.152z"></path>
</svg>';

			echo '<div class="purchase-default"><p class="etheme-purchase"><span class="etheme-purchase-inner">'.$svg_key_icon.' <span>' . $this->maskHalfOfStringFromEnd($code) . '</span></span></p>
                <span class="et-button et-button-active et_theme-deactivator last-button">
                ' . esc_html__( 'Unregister', 'xstore' ) . '
                    <span class="et-loader">
                        <svg class="loader-circular" viewBox="25 25 50 50">
                            <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                        </svg>
                    </span>
                </span>
                    <p class="et-message et-error">
                    ' . esc_html__('One standard license is valid only for 1 website. Running multiple websites on a single license is a copyright violation. When moving a site from one domain to another please deactivate the theme first.', 'xstore') . '
                </p></div>';
			sleep(2);
			// if ( !class_exists('ETheme_Import') )
			//     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-welcome' ));
			// else
			//     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos' ));
			wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos&after_activate=true&et_clear_plugins_transient' ));
		}
	}

    public function maskHalfOfStringFromEnd($str) {
		$halfLength = floor(strlen($str) / 2);
		$startUnmaskedIndex = strlen($str) - $halfLength;
		$maskedPart = str_repeat('*', $halfLength);
		$unmaskedPart = substr($str, 0, $startUnmaskedIndex);
		return $unmaskedPart . $maskedPart;
	}

	public function domain() {
		$domain = get_option('siteurl'); //or home
		$domain = str_replace('http://', '', $domain);
		$domain = str_replace('https://', '', $domain);
		$domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
		return urlencode($domain);
	}

	public function major_update( $type = 'msg' ) {

		// ! major update versions
		$versions = array( '4.0', '4.18', '5.0', '6.0', '7.0', '8.0', '9.0', '10.0', '11.0', '12.0', '13.0', '14.0', '15.0' );

		// ! current release version
		$release = $this->release_version();

		if ( ! in_array( $release , $versions ) ){
			return;
		}

		$message = esc_html__( 'This is major theme update! Please, do the backup of your files and database before proceed to update. If you use WooCommerce plugin make sure that its latest version.', 'xstore' );

		switch ( $type ) {
			case 'msg':
				$return = $message;
				break;
			case 'msg-b':
				$return = '<p class="et_major-update">' . $message . '</p>';
				break;
			case 'ver':
				$return = $release;
				break;
			case 'both':
				$return['msg'] = $message;
				$return['ver'] = $release;
				break;
			default:
				$return = $release;
				break;
		}
		return $return;
	}

	public function major_update_holder() {
		$major_update = $this->major_update( 'both' );
		echo '<span class="hidden et_major-version" data-version="' . $major_update['ver'] . '" data-message="' . $major_update['msg'] . '"></span>';
	}

	public function et_support_refresh(){
		$activated_data = get_option( 'etheme_activated_data' );
		$purchase = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';

		if (!$purchase){
			wp_send_json( array( 'status' => 'error', 'msg' => __('Invalid purchase code', 'xstore') ) );
		}

		$remote_response = wp_remote_get( $this->api_url . 'support/' . $purchase . '?envato_id='. $this->theme_id );
		$response_code = wp_remote_retrieve_response_code( $remote_response );

		if( $response_code != '200' ) {
			wp_send_json( array( 'status' => 'error', 'msg' => __('API request call error. Can not connect to 8theme.com', 'xstore') ) );
		}

		$remote_response = json_decode( wp_remote_retrieve_body( $remote_response ) );

		if (isset($remote_response->error)){
			wp_send_json( array( 'status' => 'error', 'msg' => $remote_response->error ) );
		}

		$activated_data['item']['supported_until'] = $remote_response->supported_until;
		update_option('etheme_activated_data', $activated_data);

		wp_send_json( array('status' => 'success', 'msg' => __('Successful updated', 'xstore'), 'html' => $this->support_status($remote_response->supported_until, false) ) );
	}

	public function get_support_day_left(){
		$activated_data = get_option( 'etheme_activated_data' );
		$supported_until = ( isset( $activated_data['item'] ) && isset( $activated_data['item']['supported_until'] ) && ! empty( $activated_data['item']['supported_until'] ) ) ? $activated_data['item']['supported_until'] : '';
		$daysleft = round(((( strtotime($supported_until) - time() )/24)/60)/60);
		return $daysleft;
	}

	public function get_support_status(){

		if (
			$this->is_subscription
			&& isset($this->activated_data['item'])
			&& isset($this->activated_data['item']['subscription_type'])
			&& $this->activated_data['item']['subscription_type'] == 'lifetime'){
			return 'lifetime';
		}

		$daysleft = $this->get_support_day_left();

		if ($daysleft <= 30 && $daysleft > 0) {
			$status = 'expire-soon';
		}else if ($daysleft <= 0) {
			$status = 'expired';
		} else {
			$status = 'active';
		}
		return $status;
	}

	public function support_status($supported_until, $echo = true) {
		$support  = $this->get_support_status();
		$daysleft = $this->get_support_day_left();

		$left = $daysleft . ' ' . _nx( 'day left', 'days left', $daysleft, 'Support day/days left', 'xstore' );

		$icon = '<span style="cursor: pointer; font-size: 14px; width: 14px; height: 14px; padding-left: 5px; vertical-align: -1px;" class="et_support-refresh dashicons dashicons-image-rotate"></span>';
		$renew = __('You can renew your support by clicking ', 'xstore') . '<a href="https://1.envato.market/2rXmmA" target="_blank">' . __('here', 'xstore') . '</a>';
        $renew .= '<br/>' . sprintf(__('To learn how to renew your support, follow this %s', 'xstore'), '<a href="https://help.market.envato.com/hc/en-us/articles/207886473-Extend-or-renew-Item-Support#:~:text=on%20that%20item%3A-,Log%20in%20to%20your%20account,on%20\'Renew%20support%20now" target="_blank">' . __('link', 'xstore') . '</a>');

		if ($support == 'expire-soon') {
			$status = 'et-notice';
			$left .= $icon . '</br>' . $renew;
		}else if ($support == 'expired') {
			$status = 'et-warning';
			$left = __('Expired', 'xstore');
			$left .= $icon . '</br>' . $renew;
		} else if($support == 'lifetime'){
			$status = 'et-notice';
			$left = 'lifetime' . $icon;
		} else {
			$status = 'et-notice';
			$left .= $icon;
		}

		if ($echo){
			printf(
				'<div class="et_support-block"><p class="temp-msg"></p><p class="et_support-status et-message %s">%s %s </p></div>',
				$status,
				__('Support status:', 'xstore'),
				$left
			);
		} else {
			return sprintf(
				'<div class="et_support-block"><p class="temp-msg"></p><p class="et_support-status et-message %s">%s %s </p></div>',
				$status,
				__('Support status:', 'xstore'),
				$left
			);
		}
	}

	public function deactivate_action(){
		if ( $this->api_url ) {

			if ($this->is_new_deactivate){

				$remote_response = wp_remote_get( $this->api_url . 'xstore/deactivate/' . $this->api_key . '?is_test=1/&domain=' . $this->domain() );

//	            write_log($this->api_url . 'deactivate/' . $this->api_key . 'is_test=1/?domain=' . $this->domain());

				$code = wp_remote_retrieve_response_code( $remote_response );

				$response = wp_remote_retrieve_body( $remote_response );

				if ($code == 200 && $response){
					$response = json_decode($response, true);

					if ($response['status'] == 'deleted' || $response['status'] == 'not_found'){
						$this->update_options();
						wp_send_json( array('status' => 'deleted') );
					}

					wp_send_json( array('status' => $response['status']) );
				}
				wp_send_json( array('status' => 'active') );
			}

			$remote_response = wp_remote_get( $this->api_url . 'xstore/deactivate/' . $this->api_key . '?domain=' . $this->domain() );

			// old deactivation type
//			$remote_response = wp_remote_get( $this->api_url . 'deactivate/' . $this->api_key . '?domain=' . $this->domain() );


			//wp_remote_retrieve_response_code( $remote_response );
			//wp_remote_retrieve_body( $remote_response );
		}
	}

	public function update_options(){
		update_option( 'etheme_activated_data',
			maybe_unserialize( array(
				'api_key' => 0,
				'theme' => 0,
				'purchase' => 0,
			) )
		);
		update_option( 'envato_purchase_code_15780546', '' );
	}

	public function deactivate() {
		check_ajax_referer('etheme_theme-actions', 'security');
		if (! current_user_can( 'manage_options' )){
			wp_send_json( array('status' => 'error') );
		}

		$this->deactivate_action();
		$this->update_options();
		delete_transient('etheme_plugins_info');
		wp_send_json( array('status' => 'deleted') );
	}

	public function ajax_etheme_activate_theme(){
		check_ajax_referer('etheme_theme-actions', 'security');
		if (! current_user_can( 'manage_options' )){
			wp_send_json( array('status' => 'error') );
		}

		$code = trim( $_POST['purchase_code'] );
		$this->activate_theme($code);
	}

	public function check_activation_data(){
		check_ajax_referer('etheme_theme-actions', 'security');
		if (! current_user_can( 'manage_options' )){
			wp_send_json( array('status' => 'error') );
		}

		$code = trim( $_POST['purchase_code'] );

		$this->activate_theme($code, 'check');
	}


	public function activate_theme($code, $type='activate'){
		$_response = array(
			'status' => 'error',
			'msg' => 'code 10'
		);

		if( empty( $code ) ) {
			$_response['msg'] = '<p class="et-message et-error">The code is missing, <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">here</a> you have more information where you can find it</p>';
			wp_send_json( $_response );
		}

		$url = $this->api_url . 'xstore/activate/' . $code . '?envato_id='. $this->theme_id .'&domain=' .$this->domain();

		if ($type=='check'){
			$url .=  '&is_check=1';
		}

		add_filter( 'http_request_args', 'et_increase_http_request_timeout', 10, 2 );
		$response = wp_remote_get( $url );
		remove_filter( 'http_request_args', 'et_increase_http_request_timeout', 10, 2 );

		if ( ! $response ) {
			$_response['msg'] =  '<p class="et-message et-error">There was an error with the API request call; it was unable to connect to 8theme.com.</p>';
			wp_send_json( $_response );
		}
		$response_code = wp_remote_retrieve_response_code( $response );

		if( $response_code != '200' ) {

			if( is_wp_error( $response ) ) {
				$_response['msg'] = '<p class="et-message et-error">' . $response->get_error_message() . '</p>';
			}

			if (!$response_code){
				$_response['msg'] =  '<p class="et-message et-error">There was an error with the API request call. This is a common problem caused by an SSL certificate. Please check it  <a href="https://www.sslshopper.com/ssl-checker.html" target="_blank" rel="nofollow">here</a>. If your certificate does not exist or has errors, please contact your server provider.</p>';
				wp_send_json( $_response );
			}
			$_response['msg'] =  '<p class="et-message et-error">API request call error. Response code - <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank" rel="nofollow">' . $response_code . '</a></p>';
			wp_send_json( $_response );
		}

		$data = json_decode( wp_remote_retrieve_body($response), true );

		if( isset( $data['error'] ) ) {
			$_response['msg'] =  '<p class="et-message et-error">' . $data['error'] . '</p>';
			wp_send_json( $_response );
		}

		if ( ! isset($data['verified']) ){
			$_response['msg'] =  '<p class="et-message et-error">Sorry, I cannot get the API response..</p>';
			wp_send_json( $_response );
		}

		if( ! $data['verified'] ) {
			$_response['msg'] =  '<p class="et-message et-error">Sorry, the code is incorrect. Please try again.</p>';
			wp_send_json( $_response );
		}

		if ($type=='activate'){
			$this->activate( $code, $data );
		}

		$_response = array(
			'status' => 'success',
			'msg' => 'code 11',
            'type' => (isset($data['type']))?$data['type']:''
		);

//		if (isset($data['type'])){
//			$_response['type'] =>
//		}

		if (isset($data['notice'])){
			$_response['notice'] = array(
				'notice' => $data['notice'],
				'type' => $data['type'],
				'max_domains' => $data['max_domains'],
				'actve_domains' => $data['actve_domains']
			);
		}

		wp_send_json( $_response );
	}

}

if(!function_exists('etheme_check_theme_update')) {
	add_action('init', 'etheme_check_theme_update');
	function etheme_check_theme_update() {
		new ETheme_Version_Check();
	}
}