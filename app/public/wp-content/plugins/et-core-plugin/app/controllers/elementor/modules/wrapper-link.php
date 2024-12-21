<?php
/**
 * Wrapper link feature for Elementor widgets
 *
 * @package    wrapper-link.php
 * @since      5.1.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Controls_Manager;


class Wrapper_Link {

    function __construct() {
        add_action( 'elementor/element/column/section_advanced/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/section/section_advanced/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/container/section_effects/before_section_start', array( $this, 'add_controls_section' ) );
        add_action( 'elementor/element/common/_section_style/before_section_start', array( $this, 'add_controls_section' ) );

        add_action( 'elementor/frontend/before_render', array( $this, 'before_section_render' ) );
    }

    public function add_controls_section( $element ) {
        $element->start_controls_section(
            '_section_etheme_wrapper_link',
            [
                'label' => sprintf(esc_html__( '%s Wrapper Link', 'xstore-core' ), apply_filters('etheme_theme_label', 'XSTORE')),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'etheme_element_link',
            [
                'label'       => esc_html__( 'Link', 'xstore-core' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => 'https://example.com',
            ]
        );

        $element->end_controls_section();
    }

    public static function before_section_render( $element ) {
        $link_settings = $element->get_settings_for_display( 'etheme_element_link' );

        if ( $link_settings && ! empty( $link_settings['url'] ) ) {
            $allowed_protocols = array_merge( wp_allowed_protocols(), [ 'skype', 'viber' ] );
            $link_settings['url'] = esc_url( $link_settings['url'], $allowed_protocols );

            $element->add_render_attribute(
                '_wrapper',
                [
                    'data-etheme-element-link' => wp_json_encode( $link_settings ),
                    'style' => 'cursor: pointer',
                ]
            );
            $element->add_script_depends( 'etheme_elementor_wrapper_link' );
            wp_enqueue_script('etheme_elementor_wrapper_link'); // works always
        }
    }

}