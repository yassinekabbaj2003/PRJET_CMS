<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;
/**
 * Advanced Calculator widget.
 *
 * @since      5.1.7
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Advanced_Calculator extends \Elementor\Widget_Base {

    public $calculated_sum = 0;
	/**
	 * Get widget name.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_advanced_calculator';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Calculator', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-calculator';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'custom', 'advanced', 'calculator', 'math', 'formula', 'plus', 'minus' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
	    return ['etheme_advanced_calculator'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 5.1.7
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return ['etheme-elementor-advanced-calculator'];
	}
	
	/**
	 * Help link.
	 *
	 * @since 5.1.7
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register controls.
	 *
	 * @since 5.1.7
	 * @access protected
	 */
	protected function register_controls() {

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Items', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label' => __( 'Show labels', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label'   => __('Type', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'text'     => __('Text', 'xstore-core'),
                    'number'   => __('Number', 'xstore-core'),
                    'hidden'   => __('Hidden', 'xstore-core'),
                    'disabled' => __('Disabled', 'xstore-core'),
                    'select'   => __('Select', 'xstore-core'),
                    'radio' => __('Radio', 'xstore-core'),
                    'checkbox' => __('Checkbox', 'xstore-core'),
                ),
                'default' => 'number',
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label'   => __('Label', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'placeholder',
            [
                'label'      => __('Placeholder', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::TEXT,
                'default'    => '',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => [
                        'text',
                        'number'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'field_value',
            [
                'label'      => __('Default Value', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::TEXT,
                'default'    => '',
                'dynamic' => [
                    'active' => true,
                ],
//                'condition' => [
//                    'field_type' => [
//                        'text',
//                        'number',
//                        'hidden',
//                        'disabled'
//                    ]
//                ],
            ]
        );

        $this->add_control(
            'field_value_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Enter default option value. Example: if you need to make default option - Option label 20$|20, set 20 in this field.', 'xstore-core'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => [
                    'field_type' => [
                        'select',
                        'checkbox',
                        'radio'
                    ]
                ],
            ]
        );

        $repeater->add_control(
            'min',
            [
                'label'      => __('Min', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 0,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'max',
            [
                'label'      => __('Max', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 0.1,
                'default' => 10,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'step',
            [
                'label'      => __('Step', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 1,
                'step' => 0.1,
                'default' => 1,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'field_options',
            [
                'label'       => __('Options', 'xstore-core'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__('Enter each option in a separate line. Separate attribute label from the value using "|" character. Example: Option label 20$|20', 'xstore-core'),
                'default' => implode("\n", array(
                        esc_html__('Choose an option|0', 'xstore-core'),
                        esc_html__('Option one 20$|20', 'xstore-core'),
                        esc_html__('Option two 30$|30', 'xstore-core'),
                        esc_html__('Option three 40$|40', 'xstore-core')
                    )
                ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => [
                        'select',
                        'checkbox',
                        'radio'
                    ]
                ],
            ]
        );

        $this->add_control(
            'fields',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        // 'custom_id' => 'value1',
                        'field_type'  => 'number',
                        'field_label' => __('First Value', 'xstore-core'),
                        'placeholder' => __('Enter your value', 'xstore-core'),
                    ],
                    [
                        // 'custom_id' => 'value2',
                        'field_type'  => 'number',
                        'field_label' => __('Second Value', 'xstore-core'),
                        'placeholder' => __('Enter your value', 'xstore-core'),
                    ],
                ],
                'title_field' => '{{{ field_label }}}',
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            'section_result',
            [
                'label' => esc_html__('Result', 'xstore-core'),
            ]
        );

        if ( class_exists('WooCommerce') ) {
            $this->add_control(
                'convert_price',
                [
                    'label' => __('Convert to price format', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true
                ]
            );
        }

        $this->add_control(
            'form_result_show',
            [
                'label'   => __('Result Show', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'submit',
                'options' => [
                    'submit'    => __('On Submit', 'xstore-core'),
                    'change' => __('On Change', 'xstore-core'),
                ],
                'frontend_available' => true
            ]
        );

        $this->add_responsive_control(
            'result_alignment',
            [
                'label'       => __('Alignment', 'xstore-core'),
                'type'        => \Elementor\Controls_Manager::CHOOSE,
                'options'     => [
                    'left'    => [
                        'title' => __('Left', 'xstore-core'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'xstore-core'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'xstore-core'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justify', 'xstore-core'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'     => '',
                'toggle'      => false,
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .etheme-ac-wrapper .etheme-ac-result' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_result_text',
            [
                'label'       => __('Result Text', 'xstore-core'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__('{{result}} string will be replaced with result value after calculations.', 'xstore-core'),
                'dynamic'     => ['active' => true],
                'default'     => __('Total = {{result}}', 'xstore-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'button_section',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'condition' => [
                    'form_result_show' => 'submit'
                ]
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Submit', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_selected_icon',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'button_icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'button_icon_align',
            [
                'label' => esc_html__( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Before', 'xstore-core' ),
                    'right' => esc_html__( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'button_selected_icon[value]!' => '',
                    'button_text!' => '',
                ],
            ]
        );

        $this->add_control(
            'button_icon_indent',
            [
                'label' => esc_html__( 'Icon Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-ac-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button_text!' => '',
                    'button_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'General', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

//        $this->add_responsive_control(
//            'cols_gap',
//            [
//                'label' => __( 'Columns Gap', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px' ],
//                'range' => [
//                    'px' => [
//                        'min' => 0,
//                        'max' => 100,
//                        'step' => 1,
//                    ],
//                ],
//                'frontend_available' => true,
//                'selectors' => [
//                    '{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_labels',
            [
                'label'     => esc_html__('Label', 'xstore-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label'     => esc_html__('Spacing', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .etheme-ac-label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_field_style',
            [
                'label' => esc_html__('Fields', 'xstore-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_field_style');

        $this->start_controls_tab(
            'tab_field_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select'  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-field input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_background_color',
            [
                'label'     => esc_html__('Background Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select'  => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'        => 'field_border',
                'label'       => esc_html__('Border', 'xstore-core'),
                'selector'    => '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'field_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'field_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select',
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label'      => esc_html__('Padding', 'xstore-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'      => 'field_typography',
                'label'     => esc_html__('Typography', 'xstore-core'),
                'selector'  => '{{WRAPPER}} .etheme-ac-field input, {{WRAPPER}} .etheme-ac-field select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_field_focus',
            [
                'label' => esc_html__('Focus', 'xstore-core'),
            ]
        );

        $this->add_control(
            'field_focus_background',
            [
                'label'     => esc_html__('Background', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-field input:focus, {{WRAPPER}} .etheme-ac-field textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_focus_border_color',
            [
                'label'     => esc_html__('Border Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-field input:focus, {{WRAPPER}} .etheme-ac-field textarea:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'field_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'form_result_show' => 'submit'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .etheme-ac-button .elementor-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-ac-button .elementor-button',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#000000',
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-button:hover, {{WRAPPER}} .etheme-ac-button .elementor-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etheme-ac-button .elementor-button:hover svg, {{WRAPPER}} .etheme-ac-button .elementor-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .etheme-ac-button .elementor-button:hover, {{WRAPPER}} .etheme-ac-button .elementor-button:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#3f3f3f'
                    ]
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-button:hover, {{WRAPPER}} .etheme-ac-button .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .etheme-ac-button .elementor-button',
                'separator' => 'before',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 0,
                            'left' => 0,
                            'right' => 0,
                            'bottom' => 0
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-button .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_result',
            [
                'label'     => esc_html__('Result', 'xstore-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'result_color',
            [
                'label'     => esc_html__('Text Color', 'xstore-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-ac-result' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'result_typography',
                'selector' => '{{WRAPPER}} .etheme-ac-result',
            ]
        );

        $this->end_controls_section();
		
	}

    protected function make_select_field($field)
    {
        $this->add_render_attribute(
            [
                'select-wrapper' . $field['_id'] => [
                    'class' => [
                        'etheme-ac-form-controls'
                    ],
                ],
                'select_' . $field['_id']         => [
                    'name'  => $field['custom_id'],
                    'id'    => $field['custom_id'],
                ],
            ]
        );

        $options = preg_split("/\\r\\n|\\r|\\n/", $field['field_options']);

        if (!$options)
            return;

        $has_default_value = $field['field_value'] ? array_filter(array_values($options), function ($option) use ($field) {
            $option_value = '';
            if (false !== strpos($option, '|')) {
                list($option_label, $option_value) = explode('|', $option);
            }
            return $option_value == $field['field_value'];
        }) : false;

        if ($this->get_settings_for_display('show_labels')) : ?>
            <label <?php echo $this->get_render_attribute_string('field_label_' . $field['_id']); ?>>
                <?php echo $field['field_label']; ?>
            </label>
        <?php endif; ?>
        <div <?php echo $this->get_render_attribute_string('select-wrapper' . $field['_id']); ?>>
            <select <?php echo $this->get_render_attribute_string('select_' . $field['_id']); ?>>
                <?php
                foreach ($options as $key => $option) {
                    $option_id         = $field['custom_id'] . $key;
                    $option_value      = esc_attr($option);
                    $option_label      = esc_html($option);

                    if (false !== strpos($option, '|')) {
                        list($option_label, $option_value) = explode('|', $option);
                    }

                    if ( $has_default_value ) {
                        if ($field['field_value'] == $option_value) {
                            $this->add_render_attribute($option_id, 'selected', '');
                            $this->calculated_sum += (float)$option_value;
                        }
                    }
                    elseif ( array_key_first($options) == $key) {
                        $this->add_render_attribute($option_id, 'selected', '');
                        $this->calculated_sum += (float)$option_value;
                    }

                    $this->add_render_attribute($option_id, 'value', $option_value);

                    echo '<option ' . $this->get_render_attribute_string($option_id) . '>' . $option_label . '</option>';
                }
                ?>
            </select>
        </div>
        <?php

    }

    protected function make_radio_checkbox_field($field)
    {
        $options = preg_split("/\\r\\n|\\r|\\n/", $field['field_options']);
        if ($this->get_settings_for_display('show_labels')) : ?>
            <label <?php echo $this->get_render_attribute_string('field_label_' . $field['_id']); ?>>
                <?php echo $field['field_label']; ?>
            </label>
        <?php endif;
        $has_default_value = $field['field_value'] ? array_filter(array_values($options), function ($option) use ($field) {
            $option_value = '';
            if (false !== strpos($option, '|')) {
                list($option_label, $option_value) = explode('|', $option);
            }
            return $option_value == $field['field_value'];
        }) : false;
        if ($options) { ?>
            <div class="etheme-ac-form-controls elementor-field-subgroup">
                <?php
                    foreach ($options as $key => $option) {
                        $option_id         = $field['custom_id'] . $key;
                        $option_label      = $option;
                        $option_value      = $option;
                        if (false !== strpos($option, '|')) {
                            list($option_label, $option_value) = explode('|', $option);
                        }

                        $this->add_render_attribute(
                            $option_id,
                            [
                                'type'  => 'radio',
                                'id'  => $option_id,
                                'value' => $option_value,
                                'name'  => $field['custom_id'],
                            ]
                        );

                        if ( $has_default_value ) {
                            if ($field['field_value'] == $option_value) {
                                $this->add_render_attribute($option_id, 'checked', '');
                                $this->calculated_sum += (float)$option_value;
                            }
                        }
                        elseif ( array_key_first($options) == $key) {
                            $this->add_render_attribute($option_id, 'checked', '');
                            $this->calculated_sum += (float)$option_value;
                        }

                        echo '<label for="' . $option_id . '" class="elementor-field-option"><input ' . $this->get_render_attribute_string($option_id) . '>' . $option_label . '</label>';
                    }
                ?>
            </div>
            <?php
        }
    }

    protected function make_checkbox_checkbox_field($field)
    {
        $options = preg_split("/\\r\\n|\\r|\\n/", $field['field_options']);
        if ($this->get_settings_for_display('show_labels')) : ?>
            <label <?php echo $this->get_render_attribute_string('field_label_' . $field['_id']); ?>>
                <?php echo $field['field_label']; ?>
            </label>
        <?php endif;
        $field_values = array($field['field_value']);
        if (false !== strpos($field['field_value'], '|')) {
            $field_values = explode('|', $field['field_value']);
        }
        $has_default_value = $field['field_value'] ? array_filter(array_values($options), function ($option) use ($field_values) {
            $option_value = '';
            if (false !== strpos($option, '|')) {
                list($option_label, $option_value) = explode('|', $option);
            }
            return in_array($option_value, $field_values);
        }) : false;
        if ($options) { ?>
            <div class="etheme-ac-form-controls elementor-field-subgroup">
                <?php
                foreach ($options as $key => $option) {
                    $option_id = $field['custom_id'] . $key;
                    $option_label = $option;
                    $option_value = $option;
                    if (false !== strpos($option, '|')) {
                        list($option_label, $option_value) = explode('|', $option);
                    }

                    $this->add_render_attribute(
                        $option_id,
                        [
                            'type' => 'checkbox',
                            'id' => $option_id,
                            'value' => $option_value,
                            'name' => $field['custom_id'],
                        ]
                    );

                    if ( $has_default_value ) {
                        if (in_array($option_value, $field_values)) {
                            $this->add_render_attribute($option_id, 'checked', '');
                            $this->calculated_sum += (float)$option_value;
                        }
                    }

                    echo '<label for="' . $option_id . '" class="elementor-field-option"><input ' . $this->get_render_attribute_string($option_id) . '>' . $option_label . '</label>';
                }
                ?>
            </div>
            <?php
        }
    }

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 5.1.7
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $migrated = isset( $settings['__fa4_migrated']['button_selected_icon'] );
        $is_new = empty( $settings['button_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'wrapper', [
				'class' =>
					[
						'etheme-ac-wrapper',
					]
			]
		);

        $this->add_render_attribute( 'result', [
                'class' =>
                    [
                        'etheme-ac-result',
                    ]
            ]
        );

        $this->add_render_attribute( 'result-inner', [
                'class' =>
                    [
                        'etheme-ac-result-inner',
                    ]
            ]
        );
		
		?>
        
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

            <form action="" class="etheme-ac-form">
                
                <?php foreach ($settings['fields'] as $key => $field) {
                $field_repeater_setting_key = $this->get_repeater_setting_key( 'field', 'fields', $key );
                $field['custom_id'] = 'et-ac-field-'.$field['_id'];
                $this->add_render_attribute(
                    'field_label_' . $field['_id'], [
                        'for'   => $field['custom_id'],
                        'class' => 'etheme-ac-label'
                    ]
                );

                // field-wrapper
                $this->add_render_attribute( 'field-wrapper'.$field_repeater_setting_key, [
                    'class' =>
                        [
                            'etheme-ac-field',
                            'elementor-repeater-item-' . $field['_id']
                        ]
                ]);

                ?>
                <div <?php $this->print_render_attribute_string( 'field-wrapper'.$field_repeater_setting_key ); ?>>
                    <?php
                        switch ($field['field_type']) {
                        case 'text':
                        case 'number':
                        case 'hidden':
                        case 'disabled':
                        $this->add_render_attribute(
                            'field_input_' . $field['_id'], [
                                'type'        => $field['field_type'] == 'disabled' ? 'text' : $field['field_type'],
                                'value'       => $field['field_value'],
                                'id'          => $field['custom_id'],
                                'placeholder' => ($field['placeholder']) ? $field['placeholder'] : '',
                            ]
                        );
                        if ($field['field_value'] && is_numeric($field['field_value'])) {
                            $this->calculated_sum += (float)$field['field_value'];
                        }
                        switch ($field['field_type']) {
                            case 'number':
                                $this->add_render_attribute(
                                    'field_input_' . $field['_id'], [
                                        'min' => $field['min'],
                                        'max' => $field['max'],
                                        'step' => $field['step']
                                    ]
                                );
                                if ( $field['max'] && $field['field_value'] && $field['max'] < $field['field_value'] ) {
                                    $this->calculated_sum -= (float)$field['field_value'];
                                    $this->calculated_sum += (float)$field['max'];
                                    $this->remove_render_attribute( 'field_input_' . $field['_id'], 'value' );
                                    $this->add_render_attribute('field_input_' . $field['_id'], [
                                        'value'       => $field['max']
                                    ]);
                                }
                                break;
                            case 'disabled':
                                $this->add_render_attribute('field_input_' . $field['_id'], [
                                    'disabled' => '1'
                                ]);
                                if ($field['field_value'] && is_numeric($field['field_value'])) {
                                    $this->remove_render_attribute( 'field_input_' . $field['_id'], 'type' );
                                    $this->add_render_attribute(
                                        'field_input_' . $field['_id'], [
                                            'type'        => 'number'
                                        ]
                                    );
                                }
                                break;
                            case 'hidden':
                                $this->add_render_attribute('field_label_' . $field['_id'], [
                                    'class' => 'screen-reader-text'
                                ]);
                                break;
                        }

                            if ($settings['show_labels']) {
                                echo '<label ' . $this->get_render_attribute_string('field_label_' . $field['_id']) . '>' . $field['field_label'] . '</label>';
                            }
                            echo '<div class="etheme-ac-form-controls">';
                            echo '<input ' . $this->get_render_attribute_string('field_input_' . $field['_id']) . '>';
                            echo '</div>';
                            break;
                        case 'select':
                            $this->make_select_field($field);
                            break;
                        case 'radio':
                            $this->make_radio_checkbox_field($field);
                            break;
                        case 'checkbox':
                            $this->make_checkbox_checkbox_field($field);
                            break;
                    }
                    ?>
                </div>

                <?php } ?>
            </form>

            <?php
            if ($settings['form_result_show'] == 'submit') {
                $this->add_render_attribute( [
                    'button-wrapper' => [
                        'class' => [
                            'etheme-ac-button',
                        ]
                    ],
                    'button' => [
                        'class' => [
                            'elementor-button'
                        ],
                        'role' => 'button',
                        'type' => 'submit'
                    ],
                    'button-icon-align' => [
                        'class' => [
                            'elementor-button-icon',
                            'elementor-align-icon-' . $settings['button_icon_align'],
                        ],
                    ],
                    'content-wrapper' => [
                        'class' => 'elementor-button-content-wrapper',
                    ],
                    'text' => [
                        'class' => 'elementor-button-text',
                    ],
                ] );

                ?>

                <div <?php $this->print_render_attribute_string( 'button-wrapper' ); ?>>
                    <button <?php $this->print_render_attribute_string( 'button' ); ?>>
                        <span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
                            <?php if ( ! empty( $settings['button_icon'] ) || ! empty( $settings['button_selected_icon']['value'] ) ) : ?>
                                <span <?php $this->print_render_attribute_string( 'button-icon-align' ); ?>>
                                <?php if ( $is_new || $migrated ) :
                                    \Elementor\Icons_Manager::render_icon( $settings['button_selected_icon'], [ 'aria-hidden' => 'true' ] );
                                else : ?>
                                    <i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
                                <?php endif; ?>
                            </span>
                            <?php endif; ?>
                            <span <?php $this->print_render_attribute_string( 'text' ); ?>>
                                <?php echo $settings['button_text'] ?? esc_html__( 'Submit', 'xstore-core' ); ?>
                            </span>
                        </span>
                    </button>
                </div>

            <?php }

            if ( $this->calculated_sum == 0) {
                $this->add_render_attribute( 'result', [
                        'class' => [ 'hidden' ]
                    ]
                );
            }

            if ( $settings['convert_price'] && function_exists('wc_price') )
                $result_sum = wc_price($this->calculated_sum);
            else
                $result_sum = $this->calculated_sum;
            ?>

            <div <?php $this->print_render_attribute_string( 'result' ); ?>>
                <?php echo str_replace(
                        '{{result}}',
                        '<span ' . $this->get_render_attribute_string( 'result-inner' ) . '>' . $result_sum . '</span>',
                        $settings['form_result_text']);
                ?>
            </div>

        </div>
		<?php
		
	}
}
