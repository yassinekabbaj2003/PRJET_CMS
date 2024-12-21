<?php
/**
 * Custom attributes feature for Elementor widgets
 *
 * @package    custom-attributes.php
 * @since      5.1.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Controls_Manager;

class Custom_Attributes {
    public function __construct() {
        add_action( 'elementor/element/column/section_advanced/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/section/section_advanced/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/container/section_effects/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/common/_section_style/before_section_start', array( $this, 'add_controls_section' ) );

        if ( !defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            add_action('elementor/element/after_section_end', array($this, 'remove_go_pro_section'), 25, 3);
        }

        add_action( 'elementor/element/after_add_attributes', [ $this, 'render_attributes' ] );
    }

    public function remove_go_pro_section($widget, $section_id, $args) {

        $this->remove_go_pro_custom_attributes_controls( $widget );
    }

    public function add_controls_section( $widget ) {

        $widget->start_controls_section(
            'section_etheme_custom_attributes',
            [
                'label' => sprintf(__( '%s Attributes', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'etheme_custom_attributes',
            [
                'type' => 'textarea',
                'label' => __( 'Custom Attributes', 'xstore-core' ),
                'placeholder' => 'key|value',
                'render_type' => 'ui',
                'show_label' => true,
                'separator' => 'none',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $widget->add_control(
            'etheme_custom_attributes_how_to_use',
            [
                'type' => 'raw_html',
                'raw' => __( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using | character.', 'xstore-core' ),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $widget->end_controls_section();
    }

    public function remove_go_pro_custom_attributes_controls( $controls_stack ) {
        \Elementor\Plugin::$instance->controls_manager->remove_control_from_stack($controls_stack->get_unique_name(), ['section_custom_attributes_pro', 'custom_attributes_pro']);
    }

    private function get_black_list_attributes() {
        return [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid' ];
    }

    public function render_attributes( $element ) {
        $settings = $element->get_settings_for_display();

        if ( empty( $settings['etheme_custom_attributes'] ) ) {
            return;
        }

        $attributes = \Elementor\Utils::parse_custom_attributes( $settings['etheme_custom_attributes'], "\n" );
        $black_list = $this->get_black_list_attributes();

        foreach ( $attributes as $attribute => $value ) {
            if ( ! in_array( $attribute, $black_list, true ) ) {
                $element->add_render_attribute( '_wrapper', $attribute, $value );
            }
        }
    }
}