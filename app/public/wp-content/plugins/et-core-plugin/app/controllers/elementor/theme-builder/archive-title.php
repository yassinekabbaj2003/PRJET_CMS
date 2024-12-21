<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder;

/**
 * Archive Title widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Archive_Title extends \ElementorPro\Modules\ThemeBuilder\Widgets\Archive_Title {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-archive-etheme_title';
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
		return 'eight_theme-elementor-icon et-elementor-archive-title';
	}

    public function get_categories() {
        return array_merge([
            'woocommerce-elements-archive',
        ], parent::get_categories());
    }
	
	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
}
