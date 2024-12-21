<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel YouTube.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   7.0.0
 * @version 1.0.1
 */
class YouTube{

	// ! Main construct
	function __construct(){
		$_POST['et_YouTube'] = $this->et_get_YouTube();
	}

	/**
	 * Get YouTube videos
	 *
	 * @version  1.0.1
	 * @since  6.3.6
	 */
	public function et_get_YouTube() {
		$videos = get_transient( 'etheme_YouTube_info' );

		if ( ! $videos || empty( $videos ) || isset($_GET['et_clear_YouTube_transient']) ) {
			$videos = array();
			// Get data from youtube API
			$api_response = wp_remote_get( 'https://8theme.com/youtube-api/');

			// Get response code
			$code = wp_remote_retrieve_response_code( $api_response );

			if ( $code == 200 ) {
				$api_response = wp_remote_retrieve_body( $api_response );
				$api_response = json_decode( $api_response, true );

				if ( $api_response['status'] == 'success'){
					$videos = json_decode($api_response['data'], true);
				}

				set_transient( 'etheme_YouTube_info', $videos, 24 * HOUR_IN_SECONDS );
			} else {
				$videos = array();
			}
		}
		return $videos;
	}

	public function et_documentation_beacon(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;

		$value = ( $_POST['value'] ) ? 'on' : 'off';

		update_option( 'et_documentation_beacon', $value, 'no');
		die();
	}
}
