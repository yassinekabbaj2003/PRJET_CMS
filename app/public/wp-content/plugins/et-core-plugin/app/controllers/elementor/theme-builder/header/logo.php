<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

/**
 * Logo widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Logo extends \ElementorPro\Modules\ThemeBuilder\Widgets\Site_Logo {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_site-logo';
	}

	/**
	 * Get widget title.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Site Logo', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-logo';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
        return array_merge(parent::get_keywords(), array('header', 'image'));
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}

//    protected function register_controls() {
//        parent::register_controls();
//
//        $this->update_control(
//            'image',
//            [
//                'src' => $this->get_site_logo_updated(),
//                'dynamic' => [
//                    'default' => '',
//                ],
//            ],
//        );
//    }
//
//    private function get_default_site_logo() {
//        return !in_array()
//    }
//    // Get the site logo from the dynamic tag
//    private function get_site_logo_updated() {
//        $site_logo = \Elementor\Plugin::$instance->dynamic_tags->get_tag_data_content( null, 'site-logo' );
//        $ready_logo = '';
//        if ( $site_logo['url'] && $site_logo['url'] != ELEMENTOR_PRO_ASSETS_URL . 'images/logo-placeholder.png' ) {
//            $ready_logo = $site_logo['url'];
//        }
//        else {
//            $customizer_logo = get_theme_mod( 'logo_img_et-desktop', 'logo' );
//            if ( is_array($customizer_logo) ) {
//                if ( isset($customizer_logo['id']) && $customizer_logo['id'] != '' ) {
//                    $ready_logo = wp_get_attachment_image_src( $customizer_logo['id'], 'full' );
//                }
//            }
//            if ( empty($ready_logo) ) {
//                $ready_logo = Utils::get_placeholder_image_src();
//            }
//        }
//        return $ready_logo;
//    }
}
