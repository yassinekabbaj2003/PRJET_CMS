<?php

namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

use ETC\App\Classes\Elementor;

/**
 * Product Per Page widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Product_Per_Page extends \Elementor\Widget_Base
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
        return 'woocommerce-archive-etheme_product_per_page';
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
        return __('Products Per Page', 'xstore-core');
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
        return 'eight_theme-elementor-icon et-elementor-product-per-page et-elementor-product-builder-widget-icon-only';
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
        return ['woocommerce', 'shop', 'store', 'filter', 'select', 'count', 'category', 'product', 'archive'];
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
            'section_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'posts_per_page_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('Note: You can set variations globally by setting value in Theme Options -> WooCommerce -> Shop -> %s', 'xstore-core'),
                    '<a href="'.admin_url( '/customize.php?autofocus[section]=shop-page' ).'" target="_blank">'.esc_html__('Per page variations', 'xstore-core').'</a>'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ]
        );

        $this->end_controls_section();

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
                    'left' => 'flex-start',
                    'right' => 'flex-end',
                ],
                'selectors'            => [
                    '{{WRAPPER}} .products-per-page' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .products-per-page > span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .products-per-page > span',
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
            [
                'label' => esc_html__( 'Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .products-per-page > span' => 'margin-inline-end: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_select',
            [
                'label' => esc_html__( 'Select', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
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

        $hide_heading = !!!$this->get_settings_for_display('show_heading');
        if ( $hide_heading )
            add_filter('etheme_products_per_page_select_heading', '__return_false');
        add_filter('etheme_products_per_page_select_enabled', '__return_true');
        add_filter('etheme_elementor_theme_builder', '__return_true');

        if ( function_exists('etheme_products_per_page_select') )
            etheme_products_per_page_select();

        remove_filter('etheme_elementor_theme_builder', '__return_true');
        remove_filter('etheme_products_per_page_select_enabled', '__return_true');
        if ( $hide_heading )
            remove_filter('etheme_products_per_page_select_heading', '__return_false');

        if ( $edit_mode ) {
            wc_reset_loop();
        }

    }

}
