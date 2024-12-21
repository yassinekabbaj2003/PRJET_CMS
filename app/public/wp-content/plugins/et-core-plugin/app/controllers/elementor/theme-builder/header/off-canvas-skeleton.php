<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Off-Canvas skeleton for multipurposes of Canvas using cases (account/wishlist/cart).
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Off_Canvas_Skeleton extends \Elementor\Widget_Base {

    public static $instance = null;

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_off_canvas_skeleton';
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
		return __( 'Off-Canvas', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-header-builder-widget-icon-only';
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
        return [ 'woocommerce', 'shop', 'store', 'button', 'canvas', 'aside' ];
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
	 * Get widget dependency.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-off-canvas' ];
	}

    /**
     * Get widget dependency.
     *
     * @since 4.1.4
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_script_depends() {
        return [ 'etheme_elementor_off_canvas' ];
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

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        $content_types = Elementor::get_saved_content_list(array('global_widget' => false));
        $saved_templates = Elementor::get_saved_content();
        $static_blocks = Elementor::get_static_blocks();

        $is_rtl = is_rtl();

        $this->start_controls_section(
            'section_general',
            [
                'label' => __( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label' => __( 'Content Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'off_canvas',
                'options' => [
                    'off_canvas' => __( 'Off-Canvas', 'xstore-core' ),
                    'dropdown' => __( 'Dropdown', 'xstore-core' ),
                    'none' => __( 'None', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'redirect',
            [
                'label' => __( 'Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'account',
                'options' => [
                    'account' => __( 'Account', 'xstore-core' ),
                    'cart' => __( 'Cart', 'xstore-core' ),
                    'checkout' => __( 'Checkout', 'xstore-core' ),
                    'wishlist' => __( 'Wishlist', 'xstore-core' ),
                    'waitlist' => __( 'Waitlist', 'xstore-core' ),
                    'compare' => __( 'Compare', 'xstore-core' ),
                    'none' => __( 'Without', 'xstore-core' ),
                    'custom' => __( 'Custom', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'redirect_link',
            [
                'label' => __( 'Custom Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
                'condition' => [
                    'redirect' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'dropdown_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'dropdown',
                'condition' => [
                    'content_type' => 'dropdown'
                ],
                'prefix_class' => 'etheme-elementor-',
            ]
        );

        $this->add_control(
            'off_canvas_toggle_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'content',
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
                    'content_type' => 'off_canvas',
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
                    '{{WRAPPER}}' => '--toggle-button-alignment: {{VALUE}}; --toggle-wrapper-display: inline-block;',
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
                    'content_type' => 'off_canvas',
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
                    'content_type' => 'off_canvas',
                    'off_canvas_toggle_position!' => 'content'
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Click here', 'xstore-core' ),
                'placeholder' => __( 'Click here', 'xstore-core' ),
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

        $this->add_responsive_control(
            'button_text_hidden',
            [
                'label' => __('Hide button text', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' 	=> [
                        [
                            'name' 		=> 'button_text',
                            'operator'  => '!=',
                            'value' 	=> ''
                        ],
                    ],
                ]
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

//        $this->add_responsive_control(
//            'button_min_height',
//            [
//                'label' => __( 'Min Height', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', '%' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 0,
//                        'max'  => 500,
//                        'step' => 1
//                    ],
//                    '%' => [
//                        'min'  => 0,
//                        'max'  => 100,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button-content-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'separator' => 'before',
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

        $icon_conditions = [
            'relation' => 'and',
            'terms' 	=> [
                [
                    'name' 		=> 'button_text',
                    'operator'  => '!=',
                    'value' 	=> ''
                ],
                [
                    'name' 		=> 'selected_icon[value]',
                    'operator'  => '!=',
                    'value' 	=> ''
                ],
            ],
        ];

        $this->add_control(
            'icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                    'above' => __( 'Above', 'xstore-core' ),
                ],
                'conditions' 	=> $icon_conditions
            ]
        );

        $this->add_responsive_control(
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
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle_wrapper > .etheme-elementor-off-canvas__toggle .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle_wrapper > .etheme-elementor-off-canvas__toggle .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle_wrapper > .etheme-elementor-off-canvas__toggle .flex-wrap .button-text:last-child' => 'margin: {{SIZE}}{{UNIT}} 0 0;',
                ],
                'conditions' 	=> $icon_conditions
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label' 		=> __( 'Show Quantity', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' 		=> 'yes',
            ]
        );

        $this->add_control(
            'show_quantity_zero',
            [
                'label' 		=> __( 'Show Zero Quantity', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' 		=> 'yes',
                'prefix_class' => 'etheme-elementor-off-canvas-zero-quantity-',
                'condition' => [
                    'show_quantity!' => ''
                ]
            ]
        );

        $this->add_control(
            'quantity_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __( 'Top', 'xstore-core' ),
                    'middle' => __( 'Middle', 'xstore-core' ),
                    'bottom' => __( 'Bottom', 'xstore-core' ),
                ],
                'prefix_class' => 'etheme-elementor-off-canvas__toggle-qty-',
                'condition' => [
                    'show_quantity!' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_toggle_style',
            [
                'label' => __( 'General', 'xstore-core' ),
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
                        'default' => '#ffffff'
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
                'default' => '#555',
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover svg, {{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover:focus svg' => 'fill: {{VALUE}};',
                ],
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
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 1,
                            'left' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'unit' => 'px'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
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
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'selected_icon_proportion',
            [
                'label' => esc_html__( 'Icon Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'separator' => 'before',
                'size_units' => [ 'em', 'px', '%', 'custom' ],
                'range' => [
                    'em' => [
                        'max' => 5,
                        'min' => 0,
                        'step' => .1
                    ],
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 70,
                        'min' => 0
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button' => '--toggle-icon-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas',
            [
                'label' => __( 'Off-Canvas/Dropdown', 'xstore-core' ),
                'condition' => [
                    'content_type!' => 'none'
                ],
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
                'condition' => [
                    'content_type!' => 'none'
                ],
                'default' => 'click',
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'off_canvas_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => !$is_rtl ? 'right' : 'left',
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
                'default' => 'outside',
                'options' => [
                    'inside' => __( 'Inside', 'xstore-core' ),
                    'outside' => __( 'Outside', 'xstore-core' ),
                    '' => __('Hidden', 'xstore-core')
                ],
            ]
        );

        $this->add_control(
            'separated_design',
            [
                'label' 		=>	__( 'Separated Design', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'separated',
                'prefix_class' => 'etheme-elementor-off-canvas-design-',
            ]
        );

        $this->add_control(
            'off_canvas_head',
            [
                'label' => __( 'Head', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'off_canvas_head_icon',
            [
                'label' 		=> __( 'Show Icon', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'off_canvas_head_inline_design',
            [
                'label' 		=> __( 'Inline Style', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'off_canvas_head_icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_advanced',
            [
                'label' => __( 'Advanced', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'items_count',
            [
                'label' => __( 'Products Amount', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description'         => esc_html__( 'Set the maximum number of products to be displayed in the mini-content of this element.', 'xstore-core' ),
                'default' => 3,
//                'range' => [
//                    'px' => [
//                        'max' => 30,
//                    ],
//                ],
//                'default' => [
//                    'size' => 3
//                ]
            ]
        );

        $this->add_control(
            'automatically_open_canvas',
            [
                'label' => esc_html__( 'Automatically Open Canvas', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Open the content every time an item is added.', 'xstore-core' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'show_view_page',
            [
                'label' 		=> __( 'Show View Page', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_view_page_extra',
            [
                'label' 		=> __( 'Show View Page Extra', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional',
            [
                'label' => esc_html__( 'Empty Content', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'additional_empty_content_template_switch',
            [
                'label' => esc_html__( 'Customize empty content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'additional_template_description',
            [
                'raw' => sprintf(
                /* translators: 1: Saved templates link opening tag, 2: Link closing tag. */
                    esc_html__( 'Replaces the default content with a custom template. (Don’t have one? Head over to %1$sSaved Templates%2$s)', 'xstore-core' ),
                    sprintf( '<a href="%s" target="_blank">', admin_url( 'edit.php?post_type=elementor_library&tabs_group=library#add_new' ) ),
                    '</a>'
                ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor elementor-descriptor-subtle',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_content_template_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Choose template', 'xstore-core' ),
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_content_content_type',
            [
                'label' 		=>	__( 'Content Type', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' => $content_types,
                'default'	=> 'custom',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                ],
            ]
        );

        $this->add_control(
            'additional_empty_content_save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                    'additional_empty_content_content_type' => 'saved_template'
                ]
            ]
        );

        $this->add_control(
            'additional_empty_content_static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                    'additional_empty_content_content_type' => 'static_block'
                ]
            ]
        );

        $this->add_control(
            'additional_empty_content_template_content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'condition'   => [
                    'additional_empty_content_template_switch!' => '',
                    'additional_empty_content_content_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_empty_content_saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $saved_templates,
                'default' => 'select',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                    'additional_empty_content_content_type' => 'saved_template'
                ],
            ]
        );

        $this->add_control(
            'additional_empty_content_static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $static_blocks,
                'default' => 'select',
                'condition' => [
                    'additional_empty_content_template_switch!' => '',
                    'additional_empty_content_content_type' => 'static_block'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_quantity_style',
            [
                'label' => __( 'Quantity Count', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_quantity!' => ''
                ]
            ]
        );

        $this->get_quantity_style();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_style',
            [
                'label' => __( 'Off-Canvas/Dropdown', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_type!' => 'none'
                ],
            ]
        );

//        $this->add_group_control(
//            \Elementor\Group_Control_Typography::get_type(),
//            [
//                'name' => 'off_canvas_typography',
//                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
//            ]
//        );

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
                'condition' => [
                    'content_type' => 'off_canvas'
                ],
            ]
        );

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
                'condition' => [
                    'content_type' => 'off_canvas'
                ],
            ]
        );

//        $this->add_group_control(
//            \Elementor\Group_Control_Border::get_type(),
//            [
//                'name' => 'off_canvas_border',
//                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
//                'separator' => 'before',
//            ]
//        );
//
//        $this->add_responsive_control(
//            'off_canvas_border_radius',
//            [
//                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::DIMENSIONS,
//                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
//                'selectors' => [
//                    '{{WRAPPER}} .etheme-elementor-off-canvas__main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->add_group_control(
//            \Elementor\Group_Control_Box_Shadow::get_type(),
//            [
//                'name' => 'off_canvas_box_shadow',
//                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
//            ]
//        );

        $this->add_responsive_control(
            'off_canvas_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-off-canvas__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --cart-widget-footer-margin: 15px -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}}; --cart-widget-footer-padding: 15px {{RIGHT}}{{UNIT}} 15px {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'off_canvas_head_style',
            [
                'label' => __( 'Head', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'off_canvas_head_alignment',
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
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'off_canvas_head_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas_content-head',
            ]
        );

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

        $this->add_control(
            'content_align_top',
            [
                'label' 		=> __( 'Content Top Aligned', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-content-height: auto;',
                ],
            ]
        );

        $this->add_control(
            'product_title_full',
            [
                'label' 		=> __( 'Wrap Product Title', 'xstore-core' ),
                'type'			=> \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}}' => '--item-title-white-space: wrap;',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function get_quantity_style($prefix = '', $selectors = false, $selectors_hover = false, $settings = array()) {
        if ( !$selectors )
            $selectors = '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button .elementor-button-icon-qty';

        if ( !$selectors_hover )
            $selectors_hover = '{{WRAPPER}} .etheme-elementor-off-canvas__toggle .elementor-button:hover .elementor-button-icon-qty';

        if ( !isset($settings['exclude_typography']) || !$settings['exclude_typography'] ) {
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => $prefix . 'quantity_typography',
                    'selector' => $selectors,
                ]
            );
        }

        $this->start_controls_tabs( 'tabs_'.$prefix.'quantity_style' );

        $this->start_controls_tab(
            'tab_'.$prefix.'quantity_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $prefix.'quantity_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selectors => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $prefix.'quantity_background',
                'label' => __( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $selectors,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_'.$prefix.'quantity_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            $prefix.'quantity_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $selectors_hover => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => $prefix.'quantity_hover_background',
                'label' => __( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $selectors_hover,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            $prefix.'quantity_proportion',
            [
                'label' => esc_html__( 'Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'em', 'px', '%', 'custom' ],
                'range' => [
                    'em' => [
                        'max' => 5,
                        'min' => 0,
                        'step' => .1
                    ],
                    'px' => [
                        'max' => 100,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 70,
                        'min' => 0
                    ],
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'selectors' => [
                    $selectors => '--toggle-button-qty-proportion: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $is_woocommerce = class_exists('WooCommerce');

        if ( $this->is_woocommerce_depended() && !$is_woocommerce) {
            echo Elementor::elementor_frontend_alert_message(esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core'));
            return;
        }

        $has_content = $this->canvas_should_display($settings);
        $off_canvas_content = isset($settings['content_type']) && $settings['content_type'] == 'off_canvas';

        $this->add_render_attribute( 'button_wrapper', 'class', 'elementor-button-wrapper' );

        $this->add_render_attribute( 'button', [
            'class' => ['elementor-button'],
        ] );

        $is_woocommerce = class_exists('WooCommerce');

        $default_url_params = array(
            'url' => '',
            'is_external' => '',
            'nofollow' => '',
            'custom_attributes' => ''
        );

        $should_make_link = false;

        $extra_args = array();

        switch ($settings['redirect']) {
            case 'account':
                $should_make_link = true;
                $default_url_params['url'] = $is_woocommerce ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : wp_login_url();
                break;
            case 'cart':
                $default_url_params['url'] = wc_get_cart_url();
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;
                break;
            case 'wishlist':
                $local_options = array();
                $local_options['built_in_wishlist'] = get_theme_mod('xstore_wishlist', false);
                $extra_args['wishlist_type'] = false;
                if ( $local_options['built_in_wishlist'] ) {
                    $extra_args['wishlist_type'] = 'xstore';
                    $local_options['built_in_wishlist_page_id'] = get_theme_mod('xstore_wishlist_page', '');
                    $extra_args['built_in_wishlist_instance'] = \XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
                    if ( $local_options['built_in_wishlist_page_id'] ) {
                        $default_url_params['url'] = get_permalink($local_options['built_in_wishlist_page_id']);
                    }
                    else {
                        $local_options['built_in_wishlist_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
                        if ( $local_options['built_in_wishlist_ghost_page_id'] )
                            $default_url_params['url'] = add_query_arg('et-wishlist-page', '', get_permalink($local_options['built_in_wishlist_ghost_page_id']));
                    }
                }
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;

                unset($local_options);
                break;
            case 'waitlist':
                $local_options = array();
                $local_options['built_in_waitlist'] = get_theme_mod('xstore_waitlist', false);
                $extra_args['waitlist_type'] = false;
                if ( $local_options['built_in_waitlist'] ) {
                    $extra_args['waitlist_type'] = 'xstore';
                    $local_options['built_in_waitlist_page_id'] = get_theme_mod('xstore_waitlist_page', '');
                    $extra_args['built_in_waitlist_instance'] = \XStoreCore\Modules\WooCommerce\XStore_Waitlist::get_instance();
                    if ( $local_options['built_in_waitlist_page_id'] ) {
                        $default_url_params['url'] = get_permalink($local_options['built_in_waitlist_page_id']);
                    }
                    else {
                        $local_options['built_in_waitlist_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
                        if ( $local_options['built_in_waitlist_ghost_page_id'] )
                            $default_url_params['url'] = add_query_arg('et-waitlist-page', '', get_permalink($local_options['built_in_waitlist_ghost_page_id']));
                    }
                }
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;

                unset($local_options);
                break;
            case 'compare':
                $local_options['built_in_compare'] = get_theme_mod('xstore_compare', false);
                $extra_args['compare_type'] = false;
                if ( $local_options['built_in_compare'] ) {
                    $extra_args['compare_type'] = 'xstore';
                    $local_options['built_in_compare_page_id'] = get_theme_mod('xstore_compare_page', '');
                    $extra_args['built_in_compare_instance'] = \XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance();
                    if ( $local_options['built_in_compare_page_id'] ) {
                        $default_url_params['url'] = get_permalink($local_options['built_in_compare_page_id']);
                    }
                    else {
                        $local_options['built_in_compare_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
                        if ( $local_options['built_in_compare_ghost_page_id'] )
                            $default_url_params['url'] = add_query_arg('et-compare-page', '', get_permalink($local_options['built_in_compare_ghost_page_id']));
                    }
                }
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;

                unset($local_options);
                break;
            case 'checkout':
                $default_url_params['url'] = wc_get_checkout_url();
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;
                break;
            case 'custom':
                $default_url_params = wp_parse_args($settings['redirect_link'], $default_url_params);
                $should_make_link = ! empty( $default_url_params['url'] );
                break;
            default:
                break;
        }

        if ( $should_make_link ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
            $this->add_link_attributes('button', $default_url_params);
        }

        if ( $has_content ) {
            $this->add_render_attribute( 'button', [
                'class' => ['etheme-elementor-off-canvas__toggle_button'],
                'role' => 'button',
                'aria-expanded' => 'false',
            ] );
            $button_text = apply_filters('etheme_elementor_header_off_canvas_button_text', $settings['button_text']);
            $this->add_render_attribute( 'button', 'aria-label', ($button_text ? $button_text : $this->get_title()));
        }

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

        $content = array(
            'head' => '',
            'main' => '',
            'footer' => ''
        );

        if ( $has_content ) {
            ob_start();
            if ( $off_canvas_content ) {
                $this->render_main_content_head($settings, $should_make_link, $default_url_params);
            }
            $content['head'] = ob_get_clean();
            ob_start();
            $this->render_main_content($settings, $default_url_params['url'], $extra_args);
            $content['main'] = ob_get_clean();
            ob_start();
            $this->render_main_prefooter($settings, $extra_args);
            $content['footer'] = ob_get_clean();
        }

        Elementor::elementor_off_canvas($this, $settings, $button, $content);

    }

    public function is_woocommerce_depended() {
        return false;
    }
    /**
     * Condition for prevent displaying off-canvas/dropdown account content in some cases
     *
     * @param $settings
     * @return bool
     */
    public function canvas_should_display($settings) {
        return !isset($settings['content_type']) || $settings['content_type'] != 'none';
    }

    protected function render_main_content_head($settings, $should_make_link, $default_url_params) {
        $text_alignment = isset($settings['off_canvas_head_alignment']) && !!$settings['off_canvas_head_alignment'] ? $settings['off_canvas_head_alignment'] : 'center';
        $text_alignment = str_replace(array('left', 'center', 'right'), array('content-start', 'content-center', 'content-end'), $text_alignment);
        $button_text = apply_filters('etheme_elementor_header_off_canvas_button_text', $settings['button_text']);
        ?>
        <a <?php echo $should_make_link ? 'href="'.$default_url_params['url'].'"' : ''; ?> class="flex justify-<?php echo $text_alignment; ?> flex-wrap">
            <?php if ( !!$settings['off_canvas_head_icon'] ) $this->render_icon($settings, true); ?>

            <?php if ( !empty($button_text) ) : ?>
                <span class="etheme-elementor-off-canvas_content-head-label">
                    <?php echo $button_text; ?>
                </span>
            <?php endif; ?>
        </a>
        <?php
    }
    protected function render_main_content($settings, $element_page_url, $extra_args = array()) {
    }

    public function render_empty_content($settings = array(), $preload_assets = false) {
        // parse setting for making sure default values are set correctly
        $settings = wp_parse_args($settings, array(
            'additional_empty_content_template_switch' => '',
            'additional_empty_content_template_content' => '',
            'additional_empty_content_content_type' => 'custom'
        ) );
        $rendered_content = false;
        if ( !$preload_assets ) : ?>
<!--            <div class="woocommerce-mini-cart__empty-message empty">-->
            <div class="etheme-elementor-off-canvas_content-empty-message">
        <?php endif;
        if ( !!$settings['additional_empty_content_template_switch'] ) {
            switch ($settings['additional_empty_content_content_type']) {
                case 'custom':
                    if (!$preload_assets && !empty($settings['additional_empty_content_template_content'])) {

                        //                        $this->print_unescaped_setting('additional_empty_content_template_content');
                        echo $settings['additional_empty_content_template_content'];
                        $rendered_content = true;
                    }
                    break;
                case 'global_widget':
                case 'saved_template':
                    $prefix = 'additional_empty_content_';
                    if (!empty($settings[$prefix . $settings[$prefix . 'content_type']])):
                        //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                        $posts = get_posts(
                            [
                                'name' => $settings[$prefix . $settings[$prefix . 'content_type']],
                                'post_type' => 'elementor_library',
                                'posts_per_page' => '1',
                                'tax_query' => [
                                    [
                                        'taxonomy' => 'elementor_library_type',
                                        'field' => 'slug',
                                        'terms' => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $settings[$prefix . 'content_type']),
                                    ],
                                ],
                                'fields' => 'ids'
                            ]
                        );

                        if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) { // @todo maybe try to enchance TRUE value with on ajax only

                        } else {
                            if ( !$preload_assets )
                                echo $content;
                            $rendered_content = true;
                        }
                    endif;
                    break;
                case 'static_block':
                    if ( !$preload_assets ) {
                        $prefix = 'additional_empty_content_';
                        Elementor::print_static_block($settings[$prefix . $settings[$prefix . 'content_type']]);
                    }
                    $rendered_content = true;
                    break;
            }
        }
        if ( !$preload_assets && !$rendered_content ) :
            $this->render_empty_content_basic();
        endif;
        if ( !$preload_assets ) : ?>
            </div>
        <?php endif;
    }

    public function render_empty_content_basic() {
    }

    // to prevent overlapping multiple default fragments and own ones
    public function render_processing_state() {
        ?>
        <div class="etheme-elementor-off-canvas_content-process">
            <span class="elementor-screen-only"><?php esc_html_e( 'Please, wait white we update content.', 'xstore-core' ); ?></span>
            <?php if ( function_exists('etheme_loader') ) etheme_loader( true, 'product-ajax' ); ?>
        </div>
        <?php
    }

    protected function render_main_prefooter($settings, $extra_args = array()) {
    }
    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();
        $button_text = apply_filters('etheme_elementor_header_off_canvas_button_text', $settings['button_text']);
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        $button_extra_classes = array();
        foreach ( $settings as $key => $value ) :
            if ( 0 === strpos($key, 'button_text_hidden') && $value ) :
                    $hidden_on_device = str_replace('button_text_hidden', '', $key);
                if ( empty($hidden_on_device) )
                    $hidden_on_device = 'desktop';
                else {
                    $hidden_on_device = str_replace('_', '', $hidden_on_device);
                }
                    $button_extra_classes[] = 'elementor-hidden-' . $hidden_on_device;
                    // hide force in editor
                if ( $edit_mode ) {
                    ?>
                    <style>
                        [data-elementor-device-mode="<?php echo $hidden_on_device ?>"] [data-id="<?php echo $this->get_id(); ?>"] .elementor-hidden-<?php echo $hidden_on_device; ?> {
                            display: none !important;
                        }
                    </style>
                    <?php
                }
            endif;
        endforeach;

        $should_wrap_button_text = $this->should_wrap_button_text($settings, count($button_extra_classes) );
        if ( count($button_extra_classes) ) {
            $this->add_render_attribute( ($should_wrap_button_text ? 'button_text_inner' : 'button_text'), [
                'class' => $button_extra_classes,
            ] );
        }

        if ( !$button_text || in_array($settings['icon_align'], array('left', 'above') ) )
            $this->render_icon( $settings );

        if ( $button_text ) : ?>
            <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                <?php if ( $should_wrap_button_text ) : ?>
                    <span <?php echo $this->get_render_attribute_string( 'button_text_inner' ); ?>>
                <?php endif;
                        echo $button_text;
                if ( $should_wrap_button_text ) : ?>
                    </span>
                <?php
                endif;
                    $this->render_text_after($settings, $edit_mode);
                ?>
            </span>
        <?php else:
            $this->render_text_after($settings, $edit_mode, true);
        endif; ?>

        <?php
        if ( $button_text && $settings['icon_align'] == 'right')
            $this->render_icon( $settings );
    }

    /**
     * Check if the Button text should be wrapped in separated span
     * Used for showing cart totals but hide the button text for this time
     *
     * @param $settings
     * @param $has_hidden_text
     * @return false
     */
    protected function should_wrap_button_text($settings, $has_hidden_text = false) {
        return false;
    }

    protected function render_text_after($settings, $edit_mode = false, $button_wrapper = false) {}

    protected function render_icon($settings, $canvas_header = false) {
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        $icon_alignment = false;
        if ( $canvas_header ) {
            $icon_alignment = isset($settings['off_canvas_head_alignment']) && !!$settings['off_canvas_head_alignment'] ? $settings['off_canvas_head_alignment'] : 'center';
            $icon_alignment = ' text-'.$icon_alignment;
        }
        if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
            <span class="<?php if ( !$canvas_header ) : ?>elementor-button-icon<?php else: ?>etheme-elementor-off-canvas_content-head-icon<?php echo !!!$settings['off_canvas_head_inline_design'] ? ' full-width' : ''; echo $icon_alignment; ?><?php endif; ?>">
                <?php if ( $is_new || $migrated ) :
                    \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <?php if ( !!$settings['show_quantity'] && !$canvas_header)
                    $this->render_icon_qty(); ?>
            </span>
        <?php endif;
    }

    // Skip by default to display quantity without based on anything
    public function get_icon_qty_count() {
        return false;
    }
    public function render_icon_qty($ajax_count = false) {
        $count = $ajax_count !== false ? $ajax_count : $this->get_icon_qty_count();
        if ( $count === false ) return;
        ?>
        <span class="elementor-button-icon-qty" data-counter="<?php echo esc_attr( $count ); ?>">
            <?php echo $count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </span>
        <?php
    }

    // This widget extends the woocommerce core widget and therefore needs to overwrite the widget-base core CSS config.
//    public function get_css_config() {
//        $theme_config = etheme_config_css_files();
//        $theme_config_file = $theme_config['elementor-off-canvas'];
//        $theme_config_file_path = get_template_directory_uri();
//        $widget_name = $this->get_name();
//
//        $direction = is_rtl() ? '-rtl' : '';
//
//        $css_file_path = $theme_config_file['file'] . $direction . '.min.css';
//
//        /*
//         * Currently this widget does not support custom-breakpoints in its CSS file.
//         * In order to support it, this widget needs to get the CSS config from the base-widget-trait.php.
//         * But to make sure that it implements the Pro assets-path due to the fact that it extends a Core widget.
//        */
//        return [
//            'key' => $widget_name,
//            'version' => ELEMENTOR_PRO_VERSION,
//            'file_path' => $theme_config_file_path . $css_file_path,
//            'data' => [
//                'file_url' => $theme_config_file_path . $css_file_path,
//            ],
//        ];
//    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  4.1
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }

}
