<?php

namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

use ETC\App\Classes\Elementor;

/**
 * Grid/List widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Grid_List extends \Elementor\Widget_Base
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
        return 'woocommerce-archive-etheme_grid_list';
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
        return __('Grid/List Switcher', 'xstore-core');
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
        return 'eight_theme-elementor-icon et-elementor-product-list et-elementor-product-builder-widget-icon-only';
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
        return ['woocommerce', 'shop', 'store', 'grid', 'list', 'view', 'mode', 'columns', 'category', 'product', 'archive'];
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
            ]
        );

        $this->add_control(
            'switchers',
            [
                'label'    => __( 'Switchers', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => array(
                    'grid-2',
                    'grid-3',
                    'grid-4',
                    'grid-list',
                ),
                'options' => $this->get_switchers_list(),
            ]
        );

        $switchers_list = $this->get_switchers_list();

        $this->add_control(
            'switchers_more',
            [
                'label'    => __( 'Switchers More', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => array(
                    'grid-5',
                    'grid-6',
                ),
                'options' => $switchers_list,
            ]
        );

        $this->add_control(
            'switcher_default',
            [
                'label'    => __( 'Default Switcher', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid-3',
                'options' => $this->get_switchers_list(),
            ]
        );

        $this->add_control(
            'switchers_type',
            [
                'label' => __( 'Type', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => array(
                    'icon' => esc_html__('Icon', 'xstore-core'),
                    'text' => esc_html__('Text', 'xstore-core'),
                    'icon_text' => esc_html__('Icon + Text', 'xstore-core'),
                ),
            ]
        );

        $this->add_control(
            'switchers_separator',
            [
                'label' => __( 'Separators', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'custom_switchers_labels',
            [
                'label' 		=> esc_html__( 'Custom Labels', 'xstore-core' ),
                'separator' => 'before',
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'condition' => ['switchers_type!' => 'icon'],
            ]
        );

        foreach ($switchers_list as $switcher_key => $switcher_label) {
            $this->add_control(
                'label_'.$switcher_key,
                [
                    'label' 		=>	sprintf(__( 'Label "%s"', 'xstore-core' ), $switcher_label),
                    'label_block' => true,
                    'type' 			=>	\Elementor\Controls_Manager::TEXT,
                    'default' => $switcher_label,
                    'condition' => [
                        'custom_switchers_labels!' => '',
                    ],
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => __( 'Items Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--items-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'switchers_separator_style_heading',
            [
                'label' => esc_html__( 'Separator', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'switchers_separator!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'switchers_separator_width',
            [
                'label' => esc_html__( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--items-sep-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'switchers_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'switchers_separator_style',
            [
                'label' => __( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'xstore-core'),
                    'solid' => __( 'Solid', 'xstore-core' ),
                    'double' => __( 'Double', 'xstore-core' ),
                    'dotted' => __( 'Dotted', 'xstore-core' ),
                    'dashed' => __( 'Dashed', 'xstore-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => '--items-sep-style: {{VALUE}};',
                ],
                'condition' => [
                    'switchers_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'switchers_separator_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--items-sep-color: {{VALUE}};',
                ],
                'condition' => [
                    'switchers_separator!' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_switcher_style',
            [
                'label' => esc_html__( 'Switcher', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'switcher_typography',
                'label'    => esc_html__( 'Typography', 'xstore-core' ),
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a, {{WRAPPER}} .switch-more > .switcher-wrapper a',
            ]
        );

        $this->start_controls_tabs( 'switcher_colors' );

        $this->start_controls_tab( 'switcher_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' )
            ]
        );

        $this->add_control(
            'switcher_color',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .switch-more > .switcher-wrapper a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'switcher_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a, {{WRAPPER}} .switch-more > .switcher-wrapper a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'switcher_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' )
            ]
        );

        $this->add_control(
            'switcher_color_hover',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper:not(.switcher-active):hover a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .switch-more > .switcher-wrapper:not(.switcher-active):hover a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'switcher_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper:not(.switcher-active):hover a, {{WRAPPER}} .switch-more > .switcher-wrapper:not(.switcher-active):hover a',
            ]
        );

        $this->add_control(
            'switcher_border_color_hover',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper:not(.switcher-active):hover a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .switch-more > .switcher-wrapper:not(.switcher-active):hover a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'switcher_border_border!' => ''
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'switcher_colors_active',
            [
                'label' => esc_html__( 'Active', 'xstore-core' )
            ]
        );

        $this->add_control(
            'switcher_color_active',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-active a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .switch-more > .switcher-active a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'switcher_background_active',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-active a, {{WRAPPER}} .switch-more > .switcher-active a',
            ]
        );

        $this->add_control(
            'switcher_border_color_active',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-active a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .switch-more > .switcher-active a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'switcher_border_border!' => ''
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'switcher_border',
                'label'     => esc_html__( 'Border', 'xstore-core' ),
                'selector'  => '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a, {{WRAPPER}} .switch-more > .switcher-wrapper a',
            ]
        );

        $this->add_responsive_control(
            'switcher_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .switch-more > .switcher-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'switcher_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher > .switcher-wrapper a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .switch-more > .switcher-wrapper a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_switcher_more_style',
            [
                'label' => esc_html__( 'Dropdown Switchers', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'switchers_more!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'dropdown_switcher_typography',
                'label'    => esc_html__( 'Typography', 'xstore-core' ),
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher ul a',
            ]
        );

        $this->start_controls_tabs( 'dropdown_switcher_colors' );

        $this->start_controls_tab( 'dropdown_switcher_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' )
            ]
        );

        $this->add_control(
            'dropdown_switcher_item_color',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .switch-more ul .switcher-wrapper a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_switcher_item_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .switch-more ul .switcher-wrapper a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'dropdown_switcher_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' )
            ]
        );

        $this->add_control(
            'dropdown_switcher_item_color_hover',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .switch-more ul .switcher-wrapper:not(.switcher-active):hover a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_switcher_item_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .switch-more ul .switcher-wrapper:not(.switcher-active):hover a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'dropdown_switcher_colors_active',
            [
                'label' => esc_html__( 'Active', 'xstore-core' )
            ]
        );

        $this->add_control(
            'dropdown_switcher_item_color_active',
            [
                'label'     => esc_html__( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .switch-more ul .switcher-active a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_switcher_item_background_active',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .switch-more ul .switcher-active a',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_switcher_dropdown_style',
            [
                'label' => esc_html__( 'Dropdown', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'switchers_more!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'switcher_dropdown_alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher ul' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'switcher_dropdown_items_gap',
            [
                'label' => __( 'Items Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--dropdown-items-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'switcher_dropdown_border',
                'label' => esc_html__('Border', 'xstore-core'),
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .etheme-elementor-grid-list-switcher ul',
            ]
        );

        $this->add_responsive_control(
            'switcher_dropdown_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => '0',
                    'bottom' => '',
                    'left' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher ul' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'switcher_dropdown_border_radius',
            [
                'label'      => __( 'Border Radius', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .etheme-elementor-grid-list-switcher ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( !function_exists('etheme_get_current_page_url') ) return;

        if ( $edit_mode ) {
            wc_setup_loop(
                [
                    'total' => 20,
                    'per_page' => 12
                ]
            );
        }

        if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
            return;
        }

        $current_url = etheme_get_current_page_url();

        $url_grid = add_query_arg( 'view_mode', 'grid', remove_query_arg( 'view_mode', $current_url ) );
        $url_grid = add_query_arg( 'view_mode_smart', 'yes', $url_grid );
        $url_list = add_query_arg( 'view_mode', 'list', remove_query_arg( 'view_mode', $current_url ) );
        $url_list = add_query_arg( 'view_mode_smart', 'yes', $url_list );

        $current = isset($_GET['view_mode']) ? $_GET['view_mode'] : str_replace(
                array('grid-list-2', 'grid-list', 'grid-2',
'grid-3',
'grid-4',
'grid-5',
'grid-6'),
                array('list', 'list', 'grid', 'grid', 'grid', 'grid', 'grid'),
                $settings['switcher_default']
        );
        $current_view = isset($_GET['et_columns-count']) ? $_GET['et_columns-count'] : str_replace(
                array('grid-list-', 'grid-list', 'grid-'),
                array('', 1, ''),
                $settings['switcher_default']
        );
//        $current_view = isset($_GET['et_columns-count']) ? $_GET['et_columns-count'] : get_query_var('view_mode_smart_active', 4);

        $this->add_render_attribute( 'wrapper', 'class', 'etheme-elementor-grid-list-switcher' );
        if ( !!$settings['switchers_separator'] ) {
            $this->add_render_attribute( 'wrapper', 'class', 'with-separators' );
        }

        if ( count( $settings['switchers'] ) ) { ?>
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                <?php
                    $switchers_list = $this->get_switchers_list();
                    if ( !!$settings['custom_switchers_labels'] ) {
                        foreach ($switchers_list as $switcher_key => $switcher_label) {
                            if ( $settings['label_'.$switcher_key] )
                                $switchers_list[$switcher_key] = $settings['label_'.$switcher_key];
                        }
                    }

                    foreach ($settings['switchers'] as $switcher) {
                        $this->render_switcher($switcher, $switchers_list, $settings['switchers_type'], $current, $current_view, $url_grid, $url_list);
                    }

                    if ( count($settings['switchers_more']) ) { ?>
                        <div class="switch-more <?php if ( in_array('more', array($current, $current_view)) ) { echo 'switcher-active'; } ?>">
                            <div class="switcher-wrapper">
                                <a data-type="more">
                                    <i class="et_b-icon et-icon et-down-arrow"></i>
                                </a>
                            </div>
                            <ul>
                                <?php
                                    foreach ( $settings['switchers_more'] as $switcher_more ) {
                                        $this->render_switcher($switcher_more, $switchers_list, 'text', $current, $current_view, $url_grid, $url_list, 'li');
                                    }
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
            </div>
        <?php }
//        add_filter('etheme_elementor_theme_builder', '__return_true');
//
//        add_filter('etheme_filter_area_force_load_assets', '__return_false');
//        if ( function_exists('etheme_grid_list_switcher') )
//            etheme_grid_list_switcher();

//        remove_filter('etheme_filter_area_force_load_assets', '__return_false');
//
//        remove_filter('etheme_elementor_theme_builder', '__return_true');

        if ( $edit_mode ) {
            wc_reset_loop();
        }

    }

    public function render_switcher($switcher, $switchers_list, $switchers_type, $current, $current_view, $url_grid, $url_list, $wrapper_tag = 'div') {
        $type = 'grid';
        if ( in_array($switcher, array('grid-list', 'grid-list-2')) ) {
            $type = 'list';
            $columns = str_replace(array('grid-list-', 'grid-list'), array('', 1), $switcher);
            $is_active = $current == 'list' && $current_view == $columns;
            $columns = 3;
            $icon_class = 'et_b-icon et-icon et-list-grid';
            set_query_var('elementor-etheme-products-list-assets-load', true);
        }
        else {
            $columns = str_replace('grid-', '', $switcher);
            $is_active = $current == 'grid' && $current_view == $columns;
            if ( $columns == 3 )
                $icon_class = 'et_b-icon et-icon et-grid-list';
            else
                $icon_class = 'et_b-icon et-icon et-grid-'.$columns.'-columns';
        }

        $this->remove_render_attribute('switcher-wrapper', 'class');

        $this->add_render_attribute( 'switcher-wrapper', 'class', ['switcher-wrapper', 'switch-grid'] );
        if ( $is_active )
            $this->add_render_attribute( 'switcher-wrapper', 'class', 'switcher-active' );

        $this->remove_render_attribute('switcher', 'data-type');
        $this->remove_render_attribute('switcher', 'data-row-count');
        $this->remove_render_attribute('switcher', 'href');

        $this->add_render_attribute( 'switcher', 'data-type', $type );
        $this->add_render_attribute( 'switcher', 'data-row-count', $columns );
        switch ($type) {
            case 'grid':
                $this->add_render_attribute( 'switcher', 'href',
                        esc_url( add_query_arg( 'et_columns-count', $columns, remove_query_arg( 'et_columns-count', $url_grid ) ) ) );
                break;
            default:
                $this->add_render_attribute( 'switcher', 'href',
                    esc_url( add_query_arg( 'et_columns-count', (( $switcher == 'grid-list-2' ) ? 2 : 1), remove_query_arg( 'et_columns-count', $url_list ) ) ) );
            break;
        }

        ?>
        <<?php echo $wrapper_tag; ?> <?php echo $this->get_render_attribute_string( 'switcher-wrapper' ); ?>>
            <a <?php echo $this->get_render_attribute_string( 'switcher' ); ?>>
                <?php
                if ( $switchers_type != 'text' ) { ?>
                    <i class="<?php echo $icon_class; ?>"></i>
                <?php } ?>
                <span <?php if ($switchers_type == 'icon') echo 'class="screen-reader-text"'; ?>><?php echo $switchers_list[$switcher]; ?></span>
            </a>
        </<?php echo $wrapper_tag; ?>>

    <?php }

    public function get_switchers_list() {
        return array(
            'grid-2' => esc_html__('2 columns grid', 'xstore-core'),
            'grid-3' => esc_html__('3 columns grid', 'xstore-core'),
            'grid-4' => esc_html__('4 columns grid', 'xstore-core'),
            'grid-5' => esc_html__('5 columns grid', 'xstore-core'),
            'grid-6' => esc_html__('6 columns grid', 'xstore-core'),
            'grid-list' => esc_html__('List', 'xstore-core'),
            'grid-list-2' => esc_html__('2 columns list', 'xstore-core'),
        );
    }

}
