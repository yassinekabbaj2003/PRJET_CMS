<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Etheme_Studio {

	private $source = null;
	public function __construct() {
		require_once( ET_CORE_DIR . 'app/models/studio/connector.php' );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
		add_action( 'elementor/editor/footer', array( $this, 'print_template_views' ) );
	}


	public function instance_elementor() {
		return \Elementor\Plugin::instance();
	}

	public function print_template_views(){
		$this->get_source();
		require_once( ET_CORE_DIR . 'app/models/studio/templates.php' );
	}

	public function get_source() {
		if ( is_null( $this->source ) ) {
			$this->source = new Studio_Source();
		}
		return $this->source;
	}

	public function enqueue_assets() {
		wp_enqueue_style('etheme-studio-css', ET_CORE_URL.'app/models/studio/css/style.css');
		wp_enqueue_script(
			'etheme-studio-isotope', ET_CORE_URL . 'app/models/studio/js/scripts.js',
			array(
				'jquery',
				'elementor-editor',
			),
			'',
			true
		);

		if ( defined( 'ETHEME_BASE_URI' ) ) {
			wp_enqueue_script(
				'etheme-studio-js', ETHEME_BASE_URI . 'js/libs/isotope.js',
				array(
					'jquery',
					'elementor-editor',
					'etheme-studio-isotope'
				),
				'',
				true
			);
		}

		$localize_data = array(
			'Texts' => array(
				'EmptyTitle' => esc_html__( 'No Templates Found', 'xstore-core' ),
				'EmptyMessage' => esc_html__( 'Try different category or sync for new templates.', 'xstore-core' ),
				'NoResultsTitle' => esc_html__( 'No Results Found', 'xstore-core' ),
				'NoResultsMessage' => esc_html__( 'Please make sure your search is spelled correctly or try a different words.', 'xstore-core' ),
			),
			'Btns' => array(
				'studio'=> array(
					'BodySelector' => '',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('studio')
				),
//                'Header' => array(
//                    'BodySelector' => 'elementor-editor-header',
//                    'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
//                    'Html' => $this->get_btn('header')
//                ),
				'singleProduct' => array(
					'BodySelector' => 'elementor-editor-product',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('single-product')
				),
				'productArchive' => array(
					'BodySelector' => 'elementor-editor-product-archive',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('product-archive')
				),
				'Page404' => array(
					'BodySelector' => 'elementor-editor-error-404',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('404')
				),
                'Footer' => array(
                    'BodySelector' => 'elementor-editor-footer',
                    'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
                    'Html' => $this->get_btn('footer')
                ),
				'Header' => array(
					'BodySelector' => 'elementor-editor-header',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('header')
				),
				'Cart' => array(
					'BodySelector' => '',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('cart')
				),
				'Checkout' => array(
					'BodySelector' => '',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('checkout')
				),
				'Slides' => array(
					'BodySelector' => '',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('slide')
				),
				'MegaMenu' => array(
					'BodySelector' => '',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('mega_menu')
				),
				'SinglePost' => array(
					'BodySelector' => 'elementor-editor-single-post',
					'Selector' => ".elementor-add-new-section .elementor-add-section-drag-title",
					'Html' => $this->get_btn('single-post')
				),
			),
			'Error' => array()
		);
		wp_localize_script(
			'elementor-editor',
			'EthemeStudioJsData',
			$localize_data
		);
	}

	public function get_btn($template){
		ob_start();
		require_once( ET_CORE_DIR . 'app/models/studio/btns/'.$template.'.php' );
		return ob_get_clean();
	}


	public function register_ajax_actions( $ajax ) {
		$ajax->register_ajax_action( 'get_et_library_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found.', 'xstore-core' ) );
				}

				$this->instance_elementor()->db->switch_to_post( $editor_post_id );
			}

			$source = $this->get_source();
			return $source->get_library_data($data);
		} );

		$ajax->register_ajax_action( 'get_et_filters_data', function( $data ) {
			$option = 'et_studio_data';
			if (isset($data['type'])){
				$option .= '_' . $data['type'];
			}
			$data = get_option($option,true);
			if (! is_array($data)) {
				$source = $this->get_source();
				$data = $source->get_library_data(array('init'=>true));
			}
			return $data['filters'];
		} );

        $ajax->register_ajax_action( 'et_studio_dark_light_switch_default', function ($data) {
            if ( !isset($data['dark_light_mode']) || ! current_user_can( 'edit_posts' ) ) {
                throw new \Exception( 'Access Denied' );
            }

            update_option( 'et_studio_dark_light_default', $data['dark_light_mode']);
            die();
        });

		$ajax->register_ajax_action( 'get_et_template_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found', 'xstore-core' ) );
				}

				$this->instance_elementor()->db->switch_to_post( $editor_post_id );

			}

			if ( empty( $data['template_id'] ) ) {
				throw new \Exception( __( 'Template id missing', 'xstore-core' ) );
			}
			$source = $this->get_source();
			return $source->get_data( $data );
		} );
	}
} // class


new Etheme_Studio();

