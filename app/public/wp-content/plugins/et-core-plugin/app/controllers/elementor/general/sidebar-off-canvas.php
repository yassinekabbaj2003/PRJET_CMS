<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;

/**
 * Sidebar Off-Canvas widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Sidebar_Off_Canvas extends Sidebar {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'etheme_sidebar_off_canvas';
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
        return __( 'Off-Canvas Sidebar/Filters', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-off-canvas-filter';
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_style_depends() {
        return array_merge(parent::get_style_depends(), ['etheme-elementor-off-canvas']);
    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        return array_merge(parent::get_style_depends(), ['etheme_elementor_off_canvas']);
    }

    protected function register_controls() {

        parent::register_controls();

        $this->update_control('sidebar_off_canvas_on', [
            'type'     => \Elementor\Controls_Manager::HIDDEN,
            'default' => array_keys(Elementor::get_breakpoints_list())
        ]);

        $this->update_control('off_canvas_position', [
            'separator' => 'none'
        ]);
    }
    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls_old() {

        $is_rtl = is_rtl();

        $this->start_controls_section(
            'section_off_canvas_toggle',
            [
                'label' => __( 'Off-Canvas Toggle', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'off_canvas_toggle_type',
            [
                'label' => esc_html__( 'Action', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'click' => esc_html__( 'On Click', 'xstore-core' ),
                    'mouseover' => esc_html__( 'On Hover', 'xstore-core' ),
                ],
                'default' => 'click',
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'off_canvas_toggle_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_rtl ? 'right' : 'left',
                'options' => [
                    'left' => esc_html__( 'Fixed to Left', 'xstore-core' ),
                    'content'   => esc_html__( 'Content Flow', 'xstore-core' ),
                    'right' => esc_html__( 'Fixed to Right', 'xstore-core' ),
                ],
                'render_type' => 'template',
                'prefix_class' => 'etheme-elementor-off-canvas-toggle-',
            ]
        );

        $this->add_control(
            'off_canvas_toggle_position_fixed',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'fixed',
                'condition' => [
                    'off_canvas_toggle_position!' => 'content'
                ],
                'prefix_class' => 'etheme-elementor-off-canvas-toggle-',
            ]
        );

        $this->add_responsive_control(
            'off_canvas_toggle_alignment',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'off_canvas_toggle_position' => 'content'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--toggle-button-alignment: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'off_canvas_toggle_position_axis_x',
            [
                'label' => esc_html__( 'Axis X', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 70,
                        'min' => 0
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--toggle-button-position-axis-x: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'off_canvas_toggle_position!' => 'content'
                ],
            ]
        );

        $this->add_responsive_control(
            'off_canvas_toggle_position_axis_y',
            [
                'label' => esc_html__( 'Axis Y', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'default' => [
                    'unit' => '%'
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 70,
                        'min' => 0
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--toggle-button-position-axis-y: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'off_canvas_toggle_position!' => 'content'
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'placeholder' => __( 'Toggle', 'xstore-core' ),
            ]
        );

//        $this->add_control(
//            'link',
//            [
//                'label' => __( 'Link', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::URL,
//                'dynamic' => [
//                    'active' => true,
//                ],
//                'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
//                'default' => [
//                    'url' => '#',
//                ],
//            ]
//        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-filter'
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

//        $this->add_control(
//            'icon_animation',
//            [
//                'label' => __( 'Icon Animation', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SELECT,
//                'default' => 'background_ltr',
//                'options' => [
//                    'switch_side' => __( 'Switch position', 'xstore-core' ),
//                    'background_ltr' => __( 'Background LTR', 'xstore-core' ),
//                    'background_rtl' => __( 'Background RTL', 'xstore-core' ),
//                    'background_to_top' => __( 'Background to top', 'xstore-core' ),
//                    'background_to_bottom' => __( 'Background to bottom', 'xstore-core' ),
//                    'none' => __( 'None', 'xstore-core' ),
//                ],
//                'condition' => [
//                    'selected_icon[value]!' => '',
//                ],
//            ]
//        );

        $this->add_control(
            'icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'above',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                    'above' => __( 'Above', 'xstore-core' ),
                ],
                'condition' => [
                    'button_text!' => '',
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => __( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 7
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .flex-wrap .button-text:last-child' => 'margin: {{SIZE}}{{UNIT}} 0 0;',
                ],
                'condition' => [
                    'button_text!' => '',
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_toggle_style',
            [
                'label' => __( 'Off-Canvas Toggle', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#e1e1e1'
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover svg, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover:focus svg' => 'fill: {{VALUE}};',
                ],
                'default'=> '#ffffff'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => __( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#222222'
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_min_width',
            [
                'label' => __( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button-content-wrapper' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_min_height',
            [
                'label' => __( 'Min Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 1
                    ],
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button-content-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas',
            [
                'label' => __( 'Off-Canvas', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'off_canvas_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_rtl ? 'right' : 'left',
                'options' => [
                    'left' => __( 'Left', 'xstore-core' ),
                    'right' => __( 'Right', 'xstore-core' ),
                ],
                'prefix_class' => 'etheme-elementor-off-canvas-',
            ]
        );

        $this->add_control(
            'off_canvas_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'off-canvas',
                'prefix_class' => 'etheme-elementor-',
            ]
        );

        $this->add_control(
            'off_canvas_close_icon',
            [
                'label' => __( 'Close Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __( 'Inside', 'xstore-core' ),
                    'outside' => __( 'Outside', 'xstore-core' ),
                    '' => __('Hidden', 'xstore-core')
                ],
            ]
        );

        $this->end_controls_section();

        parent::register_controls();

        $this->update_control('section_general', [
            'label' => __( 'Off-Canvas Content', 'xstore-core' ),
        ]);

        $this->update_control('widgets_style', [
            'label' => __( 'Off-Canvas', 'xstore-core' ),
        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'widgets_style',
        ] );

        $this->add_responsive_control(
            'off_canvas_width',
            [
                'label' => __( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 700,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'active_color',
        ] );

        $this->add_control(
            'off_canvas_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-overlay-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'off_canvas_border',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'off_canvas_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'off_canvas_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
            ]
        );

        $this->add_responsive_control(
            'off_canvas_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'widgets_style',
        ] );

        $this->add_control(
            'off_canvas_close_icon_heading_style',
            [
                'label' => __( 'Close Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'off_canvas_close_icon_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__close-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_close_icon_style' );

        $this->start_controls_tab(
            'tab_close_icon_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-close-button-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-close-button-background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_close_icon_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-close-button-hover-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-close-button-hover-background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'off_canvas_close_icon' => 'outside',
                    'off_canvas_close_icon_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__close-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'widgets_style',
        ] );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'off_canvas_close_icon_border',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__close-button',
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
            ]
        );

        $this->add_control(
            'off_canvas_close_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__close-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'off_canvas_close_icon_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__close-button',
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
            ]
        );

        $this->add_responsive_control(
            'off_canvas_close_icon_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__close-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'off_canvas_close_icon' => 'outside'
                ],
                'separator' => 'before',
            ]
        );

        $this->end_injection();

    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render_old() {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'button_wrapper', 'class', 'elementor-button-wrapper' );

//		if ( ! empty( $settings['link']['url'] ) ) {
//			$this->add_link_attributes( 'button', $settings['link'] );
//			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
//		}

        $this->add_render_attribute( 'button', [
            'class' => ['elementor-button', 'etheme-elementor-off-canvas__toggle_button'],
            'role' => 'button',
            'aria-expanded' => 'false',
        ] );

        $this->add_render_attribute( 'button_text_wrapper', [
            'class' => 'elementor-button-content-wrapper',
        ] );

        $this->add_render_attribute( 'button_text', [
            'class' => 'button-text',
        ] );

        if ( $settings['icon_align'] == 'above' ) {
            $this->add_render_attribute( 'button_text_wrapper', [
                'class' => 'flex-wrap',
            ] );
            $this->add_render_attribute( 'button_text', [
                'class' => 'full-width',
            ] );
        }

//		if ( ! empty( $settings['button_css_id'] ) ) {
//			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
//		}

//		if ( ! empty( $settings['size'] ) ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
//		}

//		if ( $settings['hover_animation'] ) {
//			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
//		}

        ob_start();
        ?>
        <div <?php $this->print_render_attribute_string( 'button_wrapper' ); ?>>
            <a <?php $this->print_render_attribute_string( 'button' ); ?>>
                <span <?php $this->print_render_attribute_string( 'button_text_wrapper' ); ?>>
                    <?php $this->render_text(); ?>
                </span>
            </a>
        </div>
        <?php
        $button = ob_get_clean();

        ob_start();
            parent::render();
        $content = ob_get_clean();
        Elementor::elementor_off_canvas($this, $settings, $button, array('main' => $content));

    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text_old() {
        $settings = $this->get_settings_for_display();

        if ( !$settings['button_text'] || in_array($settings['icon_align'], array('left', 'above') ) )
            $this->render_icon( $settings );

        if ( $settings['button_text'] ) : ?>
            <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                <?php echo $settings['button_text']; ?>
            </span>
        <?php endif; ?>

        <?php
        if ( $settings['button_text'] && $settings['icon_align'] == 'right')
            $this->render_icon( $settings );
    }

    protected function render_icon_old($settings) {
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
            <span class="elementor-button-icon">
                <?php if ( $is_new || $migrated ) :
                    \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
            </span>
        <?php endif;
    }
}
