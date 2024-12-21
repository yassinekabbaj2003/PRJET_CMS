<?php
namespace ETC\App\Controllers\Admin;

use ETC\App\Controllers\Admin\Base_Controller;

/**
 * Admin controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Admin extends Base_Controller{

	/**
	 * Construct the class.
	 *
	 * @since   1.4.4
     * @version 1.0.1
	 */
	public function hooks() {
		add_action( 'admin_head', array( $this, 'add_mce_button' ) );
        add_action( 'admin_init', array( $this, 'pages_import' ) );
        add_action( 'admin_init', array( $this, 'fonts_uploader' ) );
		add_action( 'admin_init', array( $this, 'sales_booster' ) );
        add_action( 'admin_init', array( $this, 'slides' ) );
        add_action( 'admin_init', array( $this, 'ai_assistant' ) );
        add_action( 'admin_init', array( $this, 'overview_dashboard_widget' ) );
	}

    /**
     * Require pages import.
     *
     * @since   2.0.1
     * @version 1.0.0
     */
    function pages_import() {
        if ( !defined('WPB_VC_VERSION') ) return;
        require_once( ET_CORE_DIR . 'app/models/pages-import/import.php' );
    }

    /**
     * Require fonts uploader.
     *
     * @since   2.2.3
     * @version 1.0.0
     */
    function fonts_uploader() {
        require_once( ET_CORE_DIR . 'app/models/fonts-uploader/etheme_fonts_uploader.php' );
    }

    /**
     * Require AI assistant.
     *
     * @since   5.1.0
     * @version 1.0.0
     */
    function ai_assistant() {
        require_once( ET_CORE_DIR . 'app/models/ai/ai.php' );
    }

    /**
     * Require Overview widget.
     *
     * @since   5.1.3
     * @version 1.0.0
     */
    function overview_dashboard_widget() {
        require_once( ET_CORE_DIR . 'app/models/overview/overview.php' );
    }
	
	/**
	 * Require sales booster.
	 *
	 * @since   2.2.3
	 * @version 1.0.0
	 */
	function sales_booster() {
		require_once( ET_CORE_DIR . 'app/models/sales-booster/etheme_sales_booster.php' );
	}

    /**
     * Require slides.
     *
     * @since   5.2.6
     * @version 1.0.0
     */
    function slides() {
        if (get_theme_mod('etheme_slides', true) && defined( 'ELEMENTOR_VERSION' )) {
            require_once(ET_CORE_DIR . 'app/models/slides/slides.php');
        }
    }

    function add_mce_button() {

        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) || ! defined( 'ETHEME_CODE_JS' ) ) {
            return;
        }

        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this, 'etheme_add_tinymce_plugin' ) );
            add_filter( 'mce_buttons', array( $this, 'etheme_register_mce_button' ) );
        }
    }

    // Declare script for new button
    function etheme_add_tinymce_plugin($plugin_array ) {
        $plugin_array['et_mce_button'] = ETHEME_CODE_JS . 'mce.js';
        return $plugin_array;
    }

    // Register new button in the editor
    function etheme_register_mce_button( $buttons ) {
    	array_push( $buttons, 'et_mce_button' );
    	return $buttons;
    }
}
