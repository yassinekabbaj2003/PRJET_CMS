<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Single_Product;

use ETC\App\Classes\Elementor;

/**
 * Additional Custom Block widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Additional_Custom_Block extends \Elementor\Widget_Base {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-product-etheme_additional_custom_block';
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
		return __( 'Additional Custom Block', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-additional-custom-block et-elementor-product-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'static block', 'section', 'product' ];
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
    	return ['woocommerce-elements-single'];
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

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'raw'             =>
                    esc_html__('This element has no settings to configure. This block is unique for each product and could be selected from product page settings.', 'xstore-core') .
                    sprintf(esc_html__('Tip: The "%1s Options" can be found by going to Dashboard -> Products -> Edit Product and scrolling down.', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')),
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 5.2
	 * @access protected
	 */
	protected function render() {
        global $product;

        $product = Elementor::get_product();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( ! $product ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message();
            }
            return;
        }

        if ( !function_exists('etheme_get_custom_field') ) return;

        $content = etheme_get_custom_field( 'additional_block', $product->get_ID());

        if ( $content != '' && $content > 0 ) {
            etheme_static_block($content, true);
        }
        else {
            if ( $edit_mode ) {
                echo '<div class="elementor-panel-alert elementor-panel-alert-info">'.
                    esc_html__('This message is displayed on edit mode only.', 'xstore-core') . '<br/>' .
                    esc_html__('This product has no Additional custom block selected.', 'xstore-core') .
                    '</div>';
            }
        }

	}
}
