<?php

namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

use ETC\App\Classes\Elementor;

/**
 * Product Sorting widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Sorting extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 5.2
     * @access public
     *
     */
    public function get_name()
    {
        return 'woocommerce-archive-etheme_product_sorting';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     * @since 5.2
     * @access public
     *
     */
    public function get_title()
    {
        return __('Product Sorting', 'xstore-core');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 5.2
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eight_theme-elementor-icon et-elementor-product-sorting et-elementor-product-builder-widget-icon-only';
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     * @since 5.2
     * @access public
     *
     */
    public function get_keywords()
    {
        return ['woocommerce', 'shop', 'store', 'filter', 'action', 'category', 'product', 'archive'];
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.2
     * @access public
     *
     */
    public function get_categories()
    {
        return ['woocommerce-elements-archive'];
    }

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
//	public function get_style_depends() {
//		return [ 'etheme-off-canvas', 'etheme-cart-widget' ];
//	}

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 4.1.4
     * @access public
     *
     */
//    public function get_script_depends() {
//        return [ 'etheme_et_wishlist' ];
//    }

    /**
     * Help link.
     *
     * @return string
     * @since 4.1.5
     *
     */
    public function get_custom_help_url()
    {
        return etheme_documentation_url('110-sales-booster', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label'                => esc_html__( 'Alignment', 'xstore-core' ),
                'type'                 => \Elementor\Controls_Manager::CHOOSE,
                'options'              => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right' => 'margin-right: 0; margin-left: auto;',
                ],
                'selectors'            => [
                    '{{WRAPPER}} .woocommerce-ordering select' => '{{VALUE}}',
                ],
            ]
        );

//        $this->end_controls_section();
//
//        $this->start_controls_section(
//            'general_section_style',
//            [
//                'label' => esc_html__( 'General', 'xstore-core' ),
//                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
//            ]
//        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'select_typography',
                'selector' => '{{WRAPPER}} select',
            ]
        );
        $this->add_control(
            'select_arrow_size',
            [
                'label' 		=>	__( 'Arrow Size', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::HIDDEN,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}}' => '--et_select-arrow-size: .75em;',
                ],
            ]
        );

        $this->add_responsive_control(
            'select_height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 30,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--et_inputs-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'select_max_width',
            [
                'label' => __( 'Max width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 1000,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} select' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'select_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'select_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'select_border',
                'label'     => esc_html__( 'Border', 'xstore-core' ),
                'selector'  => '{{WRAPPER}} select',
            ]
        );

        $this->add_responsive_control(
            'select_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} select'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_control(
            'select_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--et_inputs-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
    protected function render()
    {
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( $edit_mode ) {
            wc_setup_loop(
                [
                    'total' => 20,
                    'per_page' => 12
                ]
            );
        }

        add_filter('etheme_elementor_theme_builder', '__return_true');

        woocommerce_catalog_ordering();

        remove_filter('etheme_elementor_theme_builder', '__return_true');

        if ( $edit_mode ) {
            wc_reset_loop();
        }

    }

}
