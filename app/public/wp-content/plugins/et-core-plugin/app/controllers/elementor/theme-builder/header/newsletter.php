<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

/**
 * Newsletter widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Newsletter extends \ETC\App\Controllers\Elementor\General\Modal_Popup {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
        return 'theme-etheme_newsletter';
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
		return __( 'Newsletter', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-newsletter et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'header', 'woocommerce', 'shop', 'store', 'static block', 'section', 'product' ]);
	}

    /**
     * Get widget categories.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['theme-elements'];
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

	/**
	 * Register widget controls.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

        $this->update_control('button_text', [
            'default' => esc_html__('Newsletter', 'xstore-core'),
            'placeholder' => esc_html__( 'Newsletter', 'xstore-core' ),
        ]);

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-message'
                ],
            ]
        );

        $this->update_control('button_color', [
            'default' => '#000',
        ]);

        $this->update_control('button_background_background', [
            'default' => 'classic',
        ]);

        $this->update_control('button_background_color', [
            'default' => '#fff',
        ]);

        $this->update_control('button_border_border', [
            'default' => 'solid'
        ]);

        $this->update_control('button_border_width', [
            'default' => [
                'unit' => 'px',
                'top' => 1,
                'left' => 1,
                'right' => 1,
                'bottom' => 1
            ]
        ]);

	}
}
