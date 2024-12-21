<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Plugins Class.
 *
 *
 * @since   7.2.0
 * @version 1.0.0
 *
 */
class MaintenanceMode{
	
	// ! Main construct/ add actions
	function __construct(){
        add_action( 'wp_ajax_et_maintenance_access_key', array($this, 'et_maintenance_access_key') );
	}
	
	public function et_maintenance_mode_switch_default(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;
		update_option( 'etheme_maintenance_mode', $_POST['value']);
		die();
	}

    public function et_maintenance_access_key(){
        check_ajax_referer('etheme_update_maintenance-settings', 'security');

        if (!current_user_can( 'manage_options' )){
            wp_send_json_error('Unauthorized access');
        }

        $response = array(
            'status' =>'error',
            'msg' => esc_html__( 'Settings saving error!', 'xstore' ),
            'icon' => ''
        );

        if (! isset($_POST['form']) || ! count($_POST['form'])){
            $response['msg'] = esc_html__('Wrong data','xstore');
            return wp_send_json($response);
        }

        foreach ( $_POST['form'] as $key => $value ) {
//            set_theme_mod($value['name'],$value['value']);

            if ($value['name'] == 'maintenance_access_key'){
                update_option( 'etheme_maintenance_mode_access_key', $value['value']);
            }
        }

        $response['status'] = 'success';
        $response['msg']  = '<h4 style="margin-bottom: 15px;">' . esc_html__( 'Settings successfully saved!', 'xstore' ) . '</h4>';
        $response['icon'] = '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/success-icon.png" alt="installed icon" style="margin-top: 15px;"><br/><br/>';
        return wp_send_json($response);
    }
}

new MaintenanceMode();