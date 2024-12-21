<?php
namespace ETC\App\Controllers\Admin;

use ETC\App\Controllers\Admin\Base_Controller;
use ETC\App\Controllers\Customizer;

/**
 * Remove controller.
 *
 * @since
 * @package    ETC
 * @subpackage ETC/Controller
 */
class Remove extends Base_Controller {

	// ! Declare default variables
	private $allow_types = array(
		'page',
		'product',
		'post',
		'project',
		'etheme_portfolio',
		'staticblocks',
        'etheme_slides',
		'wpcf7_contact_form',
		'mc4wp-form',
		'attachment',
		'options',
		'widgets',
		'widget_areas',
		'menu',
		'elementor_templates-footer',
		'elementor_templates-header',
		'elementor_templates-product-archive',
		'elementor_templates-product',
		'elementor_templates-archive',
		'elementor_templates-single-post',
		'elementor_xstore-builders',
		'etheme_mega_menus',
		'elementor_templates-section',
		'etheme_terms',
		'vc_grid_item',
	);
	private $imported_data = array();
	private $type = '';
	private $option = 'et_imported_data';
	// ! Main construct/ setup variables
	public function hooks() {

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Add import init actions.
	 *
	 * Require files/add ajax actions callback.
	 *
	 * @since   1.1.0
	 * @version 1.1.2
	 */
	public function init() {
		add_action('wp_ajax_etheme_remove_ajax', array($this, 'remove_data'));
	}

	public function remove_data(){

		check_ajax_referer('etheme_remove_content-nonce', 'security');

		if (!isset($_POST['type']) || empty($_POST['type'])) wp_send_json_error(array('msg'=>'ERROR: remove error 1'));

		if (!in_array($_POST['type'], $this->allow_types)) wp_send_json_error(array('msg'=>'ERROR: remove error 2'));

		$this->imported_data = get_option($this->option, array());
		$this->type = $_POST['type'];

        switch ($this->type) {
            case 'attachment':
                $this->remove_attachment();
                break;
            case 'revslider':
                $this->remove_revslider();
                break;
            case 'options':
                $this->remove_options();
                break;
            case 'menu':
                $this->remove_menu();
                break;
            case 'widgets':
                $this->remove_widget();
                 break;
            case 'widget_areas':
                $this->remove_widget_areas();
                break;
            case 'etheme_portfolio':
            case 'project':
                $this->remove_project();
                break;
	        case 'elementor_xstore-builders':
		        $this->remove_builders();
		        break;
	        case 'etheme_terms':
		        $this->remove_terms();
		        break;
            default:
                $this->remove_post();
                break;
        }

		wp_send_json_error(array('msg' => 'ERROR: remove error 4!'));
	}


	private function remove_builders(){
		$return = array();
		foreach (array('elementor_templates-footer',
			'elementor_templates-header',
			'elementor_templates-product-archive',
			'elementor_templates-product', 'elementor_templates-section', 'elementor_templates-archive', 'elementor_templates-single-post') as $value){
			if (isset($this->imported_data[$value])){
				$posts = $this->imported_data[$value];
				foreach ($posts as $kay => $post){
					if (is_wp_error(wp_delete_post($post, true))){
						$return['not-removed'][$value][] = 'ERROR: Can not remove '. $value . ' ' .$post. ' !';
					} else {
						unset($this->imported_data[$value][$kay]);
					}
				}
				update_option($this->option, $this->imported_data);
			}
		}

		wp_send_json_success($return);
	}

	private function remove_post(){
		$return = array();
		if (isset($this->imported_data[$this->type])){
			$posts = $this->imported_data[$this->type];
			foreach ($posts as $kay => $post){
				if (is_wp_error(wp_delete_post($post, true))){
					$return['not-removed'][$this->type][] = 'ERROR: Can not remove '. $this->type . ' ' .$post. ' !';
				} else {
					unset($this->imported_data[$this->type][$kay]);
				}
			}
			update_option($this->option, $this->imported_data);
			wp_send_json_success($return);
		}
		wp_send_json_success($return);
		//wp_send_json_error(array('msg' => 'ERROR: thea no ' .$this->type. ' imported!'));
	}
	private function remove_project(){
		$return = array();
		if (isset($this->imported_data['etheme_portfolio'])){
			$posts = $this->imported_data['etheme_portfolio'];
			foreach ($posts as $kay => $post){
				if (is_wp_error(wp_delete_post($post, true))){
					$return['not-removed']['etheme_portfolio'][] = 'ERROR: Can not remove '. $this->type . ' ' .$post. ' !';
				} else {
					unset($this->imported_data['etheme_portfolio'][$kay]);
				}
			}
			update_option($this->option, $this->imported_data);
			wp_send_json_success($return);
		}
		wp_send_json_success($return);
	}
	private function remove_attachment(){
		$return = array();
		if (isset($this->imported_data[$this->type])){
			$posts = $this->imported_data[$this->type];
			foreach ($posts as $kay => $post){
				if (is_wp_error(wp_delete_attachment($post, true))){
					$return['not-removed'][$this->type][] = 'ERROR: Can not remove '. $this->type . ' ' .$post. ' !';
				} else {
					unset($this->imported_data[$this->type][$kay]);
				}
			}
			update_option($this->option, $this->imported_data);
			wp_send_json_success($return);
		}
		wp_send_json_success($return);
		//wp_send_json_error(array('msg' => 'ERROR: thea no image imported!'));
	}
	private function remove_options(){
		// remove theme options
		$theme = get_option( 'stylesheet' );
		delete_option('theme_mods_'.$theme);

		delete_option('et_multiple_headers');
		delete_option('et_multiple_single_product');
		delete_option('etheme_single_product_builder');
		delete_option('etheme_disable_customizer_header_builder');
		get_option('versions_imported');
		// regenerete css
		$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
		$Customizer->customizer_style('kirki-styles');

		$Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
		$Etheme_Customize_header_Builder->generate_header_builder_style('all');

		$Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
		$Etheme_Customize_header_Builder->generate_single_product_style('all');

		wp_send_json_success(array());
	}
	private function remove_menu(){
		$menus = $this->imported_data[$this->type];
		foreach ($menus as $kay => $menu){
			wp_delete_nav_menu($menu);
			unset($this->imported_data[$this->type][$kay]);
		}

		wp_delete_nav_menu('currencies');
		wp_delete_nav_menu('languages');

		update_option($this->option, $this->imported_data);
		wp_send_json_success(array());
	}
	private function remove_widget(){
		$widgets = $this->imported_data[$this->type];
		$active_widgets = get_option( 'sidebars_widgets' );
		foreach ($widgets as $kay => $widget){
			delete_option(  'widget_' . $widget );
			unset($this->imported_data[$this->type][$kay]);
		}
		update_option($this->option, $this->imported_data);

//		widgets-ids
		$active_widgets = get_option( 'sidebars_widgets' );
		foreach ($active_widgets as $area => $widgets_2){
			if (is_array($widgets_2)){
				foreach ($widgets_2 as $key => $widget_2){
					if (in_array($widget_2, $this->imported_data['widgets-ids'])){
						unset($active_widgets[$area][$key]);
					}
				}
			}
		}
		$this->imported_data['widgets-ids']=array();
		$this->imported_data['multiples']=array();
		update_option( 'sidebars_widgets', $active_widgets );
		update_option($this->option, $this->imported_data);

		wp_send_json_success(array());
	}
	private function remove_widget_areas(){
		delete_option(  'etheme_custom_sidebars' );
		$this->imported_data['widget_areas']=array();

		update_option($this->option, $this->imported_data);
		wp_send_json_success(array());
	}

	private function remove_terms(){
		$return = array();
		foreach (array('brand', 'product_cat') as $value){
			if (isset($this->imported_data[$value])){
				foreach ($this->imported_data[$value] as $kay => $term){
					if (is_wp_error(wp_delete_term($term, $value))){
						$return['not-removed'][$value][] = 'ERROR: Can not remove '. $value . ' ' .$term. ' !';
					} else {
						unset($this->imported_data[$value][$kay]);
					}
				}
				update_option($this->option, $this->imported_data);
			}
		}

		wp_send_json_success($return);
	}

	private function remove_revslider(){
	}
}