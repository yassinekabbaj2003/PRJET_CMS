<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;

/**
 * Sidebar widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Sidebar extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'etheme_sidebar';
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
        return __( 'Sidebar/Filters', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-sidebar';
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
        return [ 'sidebar', 'widget', 'filter', 'list', 'menu', 'checkbox' ];
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
        return ['eight_theme_general', 'theme-elements-archive', 'woocommerce-elements-single', 'woocommerce-elements-archive', 'theme-elements-single'];
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
          $styles = ['etheme-elementor-sidebar'];
          if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
              $styles[] = 'etheme-elementor-off-canvas';
              $styles[] = 'etheme-elementor-off-canvas-devices';
          }
          return $styles;
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
        $scripts = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'etheme_elementor_sidebar';
            $scripts[] = 'etheme_elementor_off_canvas';
        }
        return $scripts;
    }

    /**
     * Help link.
     *
     * @since 5.2
     *
     * @return string
     */
    public function get_custom_help_url() {
        return etheme_documentation_url('110-sales-booster', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        global $wp_registered_sidebars;

        $sidebars = [];

        if ( ! $wp_registered_sidebars ) {
            $sidebars[''] = esc_html__( 'No sidebars were found', 'xstore-core' );
        } else {
            $sidebars[''] = esc_html__( 'Select', 'xstore-core' );

            foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
                $sidebars[ $sidebar_id ] = $sidebar['name'];
            }
        }

        $sidebars_default_key = array_keys( $sidebars );
        $sidebars_default_key = array_shift( $sidebars_default_key );

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'Sidebar', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'sidebar',
            [
                'label' => esc_html__( 'Choose Sidebar', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $sidebars_default_key,
                'options' => $sidebars,
            ]
        );

        $this->add_control(
            'widgets_toggle',
            [
                'label' => __( 'Widgets Toggles', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Turn on the toggle for the sidebar widget titles to open and close the widget content.', 'xstore-core' ),
                'frontend_available' => true,
                'condition' => [
                    'sidebar!' => '',
                ]
            ]
        );

        $this->add_control(
            'widgets_toggle_action_opened',
            [
                'label'    => __( 'Widgets Opened On', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => array(
                    'desktop',
                    'tablet',
                ),
                'frontend_available' => true,
                'options' => Elementor::get_breakpoints_list(),
                'condition' => [
                    'sidebar!' => '',
                    'widgets_toggle!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_scrollable',
            [
                'label' => esc_html__( 'Scrollable Widgets', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Enable this option to set the maximum height of the sidebar widgets.', 'xstore-core' ),
                'condition' => [
                    'sidebar!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'widgets_scrollable_max_height',
            [
                'label' => esc_html__( 'Max Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 700,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widgets-max-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'sidebar!' => '',
                    'widgets_scrollable!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_separator',
            [
                'label' => __( 'Widgets Separators', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'with-separators',
                'prefix_class' => 'etheme_sidebar-widgets-',
                'condition' => [
                    'sidebar!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'widgets_separator_width',
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
                    '{{WRAPPER}}' => '--widgets-sep-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'widgets_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_separator_style',
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
                    '{{WRAPPER}}' => '--widgets-sep-style: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_separator_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widgets-sep-color: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_list_limited',
            [
                'label' => esc_html__( 'Show more link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'If a widget has more items than the number set in the option below, only a limited number of items will be shown initially, and the total number of additional items will be indicated by "+X more". Clicking the "+X more" link will reveal the hidden items.', 'xstore-core' ),
                'condition' => [
                    'sidebar!' => '',
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'widgets_list_limited_more_text',
            [
                'label' 		=>	__( 'Show more text', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' 		=>	__('+{{count}} more', 'xstore-core'),
                'condition' => [
                    'sidebar!' => '',
                    'widgets_list_limited!' => ''
                ],
                'frontend_available' => true
            ]
        );

//        $show_limited_after_selectors =
//                '{{WRAPPER}} .widget-has-list-limited ul:not(.children):not(.sub-menu) > li:nth-child({{widgets_list_limited_after.VALUE}})
//							~ li:not(.et_widget-open, .etheme_sidebar-widget-list-expand, .current-cat, .current-item, .selected),
//							 .widget-has-list-limited ul.menu > li:nth-child({{this.VALUE}}) {{self.VALUE}}
//							~ li:not(.et_widget-open, .etheme_sidebar-widget-list-expand)';

        $this->add_control(
            'widgets_list_limited_after',
            [
                'label' 		=>	__( 'Limit', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::NUMBER,
                'default'	 	=>	'12',
                'min' 	=> '1',
                'max' 	=> '',
                'condition' => [
                    'sidebar!' => '',
                    'widgets_list_limited!' => ''
                ],
//                'selectors' => [
//                    $show_limited_after_selectors => '{display: none}',
//                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'widgets_list_limited_less',
            [
                'label' => esc_html__( 'Show less link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'With this option, the customer will have the option to collapse the widget once the "+X items" link has been clicked.', 'xstore-core' ),
                'condition' => [
                    'sidebar!' => '',
                    'widgets_list_limited!' => ''
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'widgets_list_limited_less_text',
            [
                'label' 		=>	__( 'Show less text', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' 		=>	__('Show less', 'xstore-core'),
                'frontend_available' => true,
                'condition' => [
                    'sidebar!' => '',
                    'widgets_list_limited!' => '',
                    'widgets_list_limited_less!' => ''
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_widgets_title',
            [
                'label' => esc_html__( 'Widgets Title', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'widgets_title_type',
            [
                'label' => esc_html__( 'Design Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => array(
                    'classic' => __( 'Classic', 'xstore-core' ),
                    'line-aside' => __( 'Line aside', 'xstore-core' ),
                    'square-aside' => __( 'Square aside', 'xstore-core' ),
                    'circle-aside' => __( 'Circle aside', 'xstore-core' ),
                    'underline' => __( 'With Underline', 'xstore-core' ),
                    'colored-underline' => __( 'With Colored Underline', 'xstore-core' ),
                ),
            ]
        );

        $this->add_control(
            'widgets_title_html_wrapper_tag',
            [
                'label' => esc_html__('HTML tag', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h4',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_count',
            [
                'label'     => __( 'Widgets Count', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'widgets_count_position',
            [
                'label'                 => __( 'Position', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SELECT,
                'default'               => 'aside',
                'options'               => [
                    'next'    => __( 'Next to link', 'xstore-core' ),
                    'aside'        => __( 'Full aside', 'xstore-core' ),
                ],
                'prefix_class'          => 'etheme_sidebar-widgets-count-',
            ]
        );

        $this->add_control(
            'widgets_count_brackets',
            [
                'label'                 => __( 'Wrap in Brackets', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SELECT,
                'default'               => 'without',
                'options'               => [
                    'wrap'    => __( 'In brackets', 'xstore-core' ),
                    'without'        => __( 'Without', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'widgets_count_background',
            [
                'label' => __( 'With Background', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'with-background',
                'return_value' => 'with-background',
                'prefix_class'          => 'etheme_sidebar-widgets-count-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_advanced',
            [
                'label'     => __( 'Advanced', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'widgets_rating_heading',
            [
                'label' => __( 'Product Rating', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'widgets_rating_type',
            [
                'label'                 => __( 'Display Type', 'xstore-core' ),
                'type'                  => \Elementor\Controls_Manager::SELECT,
                'default'               => 'advanced',
                'options'               => [
                    'default'    => __( 'Default', 'xstore-core' ),
                    'advanced'        => __( 'Advanced', 'xstore-core' ),
                ],
            ]
        );

        $this->end_controls_section();

        $is_rtl = is_rtl();

        $breakpoints_list = Elementor::get_breakpoints_list();

        $off_canvas_conditions = $this->nav_conditions('sidebar_off_canvas_on', $breakpoints_list);

        $this->start_controls_section(
            'section_off_canvas',
            [
                'label' => __( 'Off-Canvas', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'sidebar_off_canvas_on',
            array(
                'label'    => __( 'Activate on devices:', 'xstore-core' ),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => 'true',
                'default' => array(),
                'options' => $breakpoints_list,
            )
        );

        $this->add_control(
            'off_canvas_position',
            [
                'label' => __( 'Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_rtl ? 'right' : 'left',
                'separator' => 'before',
                'options' => [
                    'left' => __( 'Left', 'xstore-core' ),
                    'right' => __( 'Right', 'xstore-core' ),
                ],
                'prefix_class' => 'etheme-elementor-off-canvas-',
                'conditions' => $off_canvas_conditions
            ]
        );

        $this->add_control(
            'off_canvas_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'off-canvas',
                'prefix_class' => 'etheme-elementor-',
                'conditions' => $off_canvas_conditions
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
                'conditions' => $off_canvas_conditions
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_toggle',
            [
                'label' => __( 'Off-Canvas Toggle', 'xstore-core' ),
                'conditions' => $off_canvas_conditions
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
                'conditions' => $off_canvas_conditions
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
            'widgets_style',
            [
                'label'     => __( 'General', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'active_color',
            [
                'label'     => __( 'Active Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--et_active-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widgets_spacing',
            [
                'label' => esc_html__( 'Widgets Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--space-between-widgets: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'off_canvas_style',
            [
                'label'     => __( 'Off-canvas', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => $off_canvas_conditions
            ]
        );

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
                ]
            ]
        );

        $this->add_control(
            'off_canvas_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'off_canvas_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'off_canvas_overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-overlay-color: {{VALUE}};',
                ]
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
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'off_canvas_close_icon_style',
            [
                'label'     => __( 'Close Icon', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => $off_canvas_conditions
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

        $this->start_controls_tabs( 'tabs_close_icon_style', ['conditions' => $off_canvas_conditions] );

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

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_title_style',
            [
                'label'     => __( 'Widgets Title', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'widgets_title_typography',
                'selector' => '{{WRAPPER}} .widget-title, {{WRAPPER}} .widgettitle',
            ]
        );

        $this->add_control(
            'widgets_title_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-title, {{WRAPPER}} .widgettitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widgets_title_border_width',
            [
                'label' => esc_html__( 'Border Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 5,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'widgets_title_type!' => ['classic']
                ]
            ]
        );

        $this->add_control(
            'widgets_title_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-color: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_title_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'widgets_title_inner_spacing',
            [
                'label' => esc_html__( 'Inner Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'widgets_title_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'widgets_title_spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-space-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'widgets_title_element_heading',
            [
                'label' => __( 'Design element', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'widgets_title_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

//        $this->add_responsive_control(
//            'widgets_title_element_width',
//            [
//                'label' => esc_html__( 'Element Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px', 'rem' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 1,
//                        'max'  => 20,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}}' => '--widget-title-element-width: {{SIZE}}{{UNIT}}',
//                ],
//                'condition' => [
//                    'widgets_title_type' => ['line-aside']
//                ]
//            ]
//        );

        $this->add_control(
            'widgets_title_element_color',
            [
                'label'     => __( 'Color Active', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_title_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_list_item_style',
            [
                'label'     => __( 'Widgets List Item', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'widgets_list_item_typography',
                'selector' => '{{WRAPPER}} ul li a',
            ]
        );

        $this->start_controls_tabs( 'widgets_list_item_colors' );

        $this->start_controls_tab( 'widgets_list_item_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_list_item_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'widgets_list_item_colors_active',
            [
                'label' => esc_html__( 'Active/Hover', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_list_item_color_active',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul li a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul li.chosen > a, {{WRAPPER}} ul li.current > a, {{WRAPPER}} ul .current-cat-parent > a, {{WRAPPER}} ul .current-cat > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'widgets_list_item_spacing',
            [
                'label' => esc_html__( 'Items Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-list-item-space: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_checkbox_style',
            [
                'label'     => __( 'Widgets Checkbox', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widgets_checkbox_icon_code',
            [
                'label' => esc_html__( 'Checkbox Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'tick',
                'options' => array(
                    'tick' => esc_html__('Tick', 'xstore-core'),
                    'circle' => esc_html__('Circle', 'xstore-core'),
                    'square' => esc_html__('Square', 'xstore-core'),
                ),
                'selectors_dictionary'  => [
                    'tick'          => '"\e918"',
                    'circle'          => '"\e94e"',
                    'square'          => '"\e95c"',
                ],
                'selectors'             => [
                    '{{WRAPPER}}' => '--widget-checkbox-icon-code: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widgets_checkbox_size',
            [
                'label' => esc_html__( 'Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 10,
                        'max'  => 30,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'widgets_checkbox_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 5,
                        'max'  => 30,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-icon-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'widgets_checkbox_colors' );

        $this->start_controls_tab( 'widgets_checkbox_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_checkbox_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_checkbox_background_color',
            [
                'label'     => __( 'Background Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-bg-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_checkbox_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-br-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'widgets_checkbox_colors_active',
            [
                'label' => esc_html__( 'Active', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_checkbox_color_active',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-color-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_checkbox_background_color_active',
            [
                'label'     => __( 'Background Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-bg-color-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_checkbox_border_color_active',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-checkbox-br-color-active: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'widgets_checkbox_border_radius',
            [
                'label'      => __( 'Border Radius', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--widget-checkbox-br-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widgets_count_style',
            [
                'label'     => __( 'Widgets Count', 'xstore-core' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'widgets_count_typography',
                'selector' => '{{WRAPPER}} ul li .count, {{WRAPPER}} li.wc-layered-nav-rating .star-rating ~ span',
            ]
        );

        $this->add_responsive_control(
            'widgets_count_min_width',
            [
                'label' => esc_html__( 'Min Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-min-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->start_controls_tabs( 'widgets_count_colors' );

        $this->start_controls_tab( 'widgets_count_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_count_color',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_count_background_color',
            [
                'label'     => __( 'Background Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-bg-color: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'widgets_count_colors_active',
            [
                'label' => esc_html__( 'Active', 'xstore-core' )
            ]
        );

        $this->add_control(
            'widgets_count_color_active',
            [
                'label'     => __( 'Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-color-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_count_background_color_active',
            [
                'label'     => __( 'Background Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-bg-color-active: {{VALUE}};',
                ],
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'widgets_count_border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} ul li .count, {{WRAPPER}} li.wc-layered-nav-rating .star-rating ~ span',
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'widgets_count_padding',
            [
                'label' => esc_html__( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-count-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->add_control(
            'widgets_count_border_radius',
            [
                'label'      => __( 'Border Radius', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--widget-count-br-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'widgets_count_background!' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $input_selectors = '{{WRAPPER}} .dokan-form-control, {{WRAPPER}} input[type=email], {{WRAPPER}} input[type=number], {{WRAPPER}} input[type=password], {{WRAPPER}} input[type=search], {{WRAPPER}} input[type=tel], {{WRAPPER}} input[type=text], {{WRAPPER}} input[type=url], {{WRAPPER}} textarea, {{WRAPPER}} textarea.form-control';
        $select_selectors = '{{WRAPPER}} .select2.select2-container--default .select2-selection--single, {{WRAPPER}} select';
        $input_selectors .= $select_selectors;

        $input_button_selectors = '{{WRAPPER}} .widget_product_search button, {{WRAPPER}} .widget_search button, {{WRAPPER}} .woocommerce-product-search button';
        $input_button_hover_selectors = str_replace(',', ':hover,', $input_button_selectors);

        $this->start_controls_section(
            'widgets_input_section',
            [
                'label' => esc_html__( 'Widgets Input/Select', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'widgets_input_typography',
                'selector' => $input_selectors,
            ]
        );

        $this->add_responsive_control(
            'widgets_input_height',
            [
                'label' => __( 'Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'separator' => 'before',
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

        $this->add_control(
            'widgets_input_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $input_selectors => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_input_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $input_selectors => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widgets_input_placeholder_color',
            [
                'label' => esc_html__( 'Placeholder Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    str_replace(',', '::-webkit-input-placeholder,', $input_selectors) => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'widgets_input_border',
                'label'     => esc_html__( 'Border', 'xstore-core' ),
                'selector'  => $input_selectors,
            ]
        );

        $this->add_control(
            'widgets_input_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--et_inputs-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    str_replace('{{WRAPPER}}', 'body:not(.rtl) {{WRAPPER}}', $input_button_selectors) => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0 !important;',
                    str_replace('{{WRAPPER}}', 'body.rtl {{WRAPPER}}', $input_button_selectors) => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'widgets_input_button_heading',
            [
                'label' => __( 'Search Button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

//        $this->add_group_control(
//            \Elementor\Group_Control_Typography::get_type(),
//            [
//                'name' => 'widgets_input_button_typography',
//                'selector' => $input_button_selectors,
//            ]
//        );

        $this->add_control(
            'widgets_input_button_size',
            [
                'label'      => esc_html__( 'Size', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}}' => '--et_inputs-btn-icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_widgets_input_button_style' );

        $this->start_controls_tab(
            'tab_widgets_input_button_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'widgets_input_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $input_button_selectors => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'widgets_input_button_background',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $input_button_selectors,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_widgets_input_button_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'widgets_input_button_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    $input_button_hover_selectors => 'color: {{VALUE}};',
                    str_replace(',', ':hover svg,', $input_button_selectors) => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'widgets_input_button_background_hover',
                'label' => esc_html__( 'Background', 'xstore-core' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => $input_button_hover_selectors,
            ]
        );

        $this->add_control(
            'widgets_input_button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'widgets_input_button_border_border!' => '',
                ],
                'selectors' => [
                    $input_button_hover_selectors => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'widgets_input_button_border',
                'selector' => $input_button_selectors,
            ]
        );

//        $this->add_responsive_control(
//            'widgets_input_button_min_width',
//            [
//                'label' => __( 'Min Width', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'size_units' => [ 'px' ],
//                'range' => [
//                    'px' => [
//                        'min' => 0,
//                        'max' => 300,
//                        'step' => 1,
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}}' => '--s-button-min-width: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_filter_widget_section',
            [
                'label' => esc_html__( 'Filter by Price widget', 'xstore-core' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_slider_line_styles_controls($this);
//
            $this->add_price_filter_label_controls($this);
//
            $this->add_price_filter_button_controls($this);

        $this->end_controls_section();

    }

    /**
     * Add slider line style controls.
     */
    protected function add_slider_line_styles_controls($widget) {

        $widget->add_control(
            'price_filter_slider_line_heading',
            [
                'label' => __( 'Slider line', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $widget->add_control(
            'price_filter_slider_line_height',
            [
                'label'      => esc_html__( 'Thickness', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .price_slider_wrapper' => '--price-slider-line-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->start_controls_tabs( 'price_filter_slider_line_colors' );

        $widget->start_controls_tab(
            'price_filter_slider_line_color_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $widget->add_control(
            'price_filter_slider_color',
            [
                'label'     => esc_html__( 'Line Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ui-slider-horizontal' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'price_filter_slider_line_color_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $widget->add_control(
            'price_filter_slider_color_active',
            [
                'label'     => esc_html__( 'Line Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ui-slider-range' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();
        $widget->end_controls_tabs();

        $widget->add_responsive_control(
            'price_filter_slider_space',
            [
                'label'     => esc_html__( 'Space below', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .price_slider_wrapper .ui-slider-horizontal' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_slider_handles_heading',
            [
                'label' => __( 'Slider Handles', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $widget->add_control(
            'price_filter_slider_handle_size',
            [
                'label'      => esc_html__( 'Size', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .price_slider_wrapper' => '--price-slider-handle-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_slider_handle_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .price_slider_wrapper' => '--price-slider-handle-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_slider_handle_scale_hover',
            [
                'label'      => esc_html__( 'Scale on hover', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0.1,
                        'step' => 0.1,
                        'max' => 3,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .price_slider_wrapper' => '--price-slider-handle-scale-hover: {{SIZE}};',
                ],
            ]
        );
    }

    protected function add_price_filter_label_controls($widget) {
        $widget->add_control(
            'price_filter_label_heading',
            [
                'label' => __( 'Price Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $widget->add_control(
            'price_filter_label_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price_slider_wrapper .price_label, {{WRAPPER}} .price_slider_amount .price_label span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_filter_label_typography',
                'selector' => '{{WRAPPER}} .price_slider_wrapper .price_label, {{WRAPPER}} .price_slider_amount .price_label span',
            ]
        );

    }

    protected function add_price_filter_button_controls($widget) {
        $widget->add_control(
            'price_filter_button_heading',
            [
                'label' => __( 'Button', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $widget->add_responsive_control(
            'price_filter_button_min_width',
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
                    '{{WRAPPER}} .widget_price_filter .button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_filter_button_typography',
                'selector' => '{{WRAPPER}} .widget_price_filter .button',
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'price_filter_button_text_shadow',
                'selector' => '{{WRAPPER}} .widget_price_filter .button',
            ]
        );

        $widget->start_controls_tabs( 'tabs_price_filter_button_style' );

        $widget->start_controls_tab(
            'tab_price_filter_button_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $widget->add_control(
            'price_filter_button_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_button_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'tab_price_filter_button_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $widget->add_control(
            'price_filter_button_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button:hover, {{WRAPPER}} .widget_price_filter .button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
                    '{{WRAPPER}} .widget_price_filter .button:hover svg, {{WRAPPER}} .widget_price_filter .button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_button_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button:hover, {{WRAPPER}} .widget_price_filter .button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'price_filter_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'price_filter_button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button:hover, {{WRAPPER}} .widget_price_filter .button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();
        $widget->end_controls_tabs();

        $widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'price_filter_button_border',
                'selector' => '{{WRAPPER}} .widget_price_filter .button, {{WRAPPER}} .widget_price_filter .button.button',
                'separator' => 'before',
            ]
        );

        $widget->add_control(
            'price_filter_button_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            'price_filter_button_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget_price_filter .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

    }

    public function nav_conditions($key, $values, $relation = 'or'){

        $conditions = array('terms' => array());
        foreach ($values as $value => $label) {
            $conditions['terms'][] = [
                'name' => $key, // control name
                'operator' => 'contains',
                'value' => $value, // value
            ];
        }
        $conditions['relation'] = $relation;

        return $conditions;
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
//        $sidebar = $this->get_settings_for_display( 'sidebar' );
        $should_render_canvas = !!$settings['sidebar_off_canvas_on'];
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( $should_render_canvas ) {

            wp_enqueue_style('etheme-elementor-off-canvas');
            wp_enqueue_style('etheme-elementor-off-canvas-devices');
            wp_enqueue_script('etheme_elementor_off_canvas');

            $canvas_classes = array();
            $edit_mode_hidden_classes = array();

            $this->add_render_attribute( 'button_wrapper', 'class', 'elementor-button-wrapper' );

            foreach (array_diff(array_keys(Elementor::get_breakpoints_list()), $settings['sidebar_off_canvas_on']) as $canvas_inactive_device) {
                $canvas_classes['toggle'][] = 'elementor-hidden-' . $canvas_inactive_device;
                $canvas_classes['alert'][] = 'elementor-hidden-' . $canvas_inactive_device;
                $edit_mode_hidden_classes[] = '[data-elementor-device-mode="'.$canvas_inactive_device.'"] [data-id="'.$id.'"] .elementor-hidden-'.$canvas_inactive_device;
            }

            if ( $edit_mode ) {
                ?>
                <style>
                    <?php echo implode(',', $edit_mode_hidden_classes) . '{ display: none !important; }'; ?>
                </style>
                <?php
            }

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
            $this->render_sidebar($settings, $edit_mode);
            $content = ob_get_clean();
            Elementor::elementor_off_canvas($this, $settings, $button, array('main' => $content), $canvas_classes);
        }
        else {
            $this->render_sidebar($settings, $edit_mode);
        }
    }

    public function render_sidebar($settings, $edit_mode) {
        $sidebar = $settings['sidebar'];
        if ( empty( $sidebar ) ) {
            if ( $edit_mode ) {
                echo Elementor::elementor_frontend_alert_message(esc_html__('This message is shown only in edit mode.', 'xstore-core') . '<br/>' .
                    sprintf(esc_html__('To use this widget, please, choose the %s to display.', 'xstore-core'), '<strong>'.esc_html__('Sidebar Area', 'xstore-core').'</strong>'));
            }
            return;
        }

        $this->add_render_attribute( 'sidebar-wrapper', [
            'class' => 'etheme-elementor-sidebar'
        ]);

        if ( !!$settings['widgets_toggle'] || !!$settings['widgets_list_limited'] ) {
            wp_enqueue_script('etheme_elementor_sidebar');
        }

        if ( $edit_mode ) {
            set_query_var('et_is-woocommerce-archive', true);
            add_filter('etheme_elementor_edit_mode', '__return_true');
        }

//        add_filter('theme_mod_ajax_product_filter', '__return_false');
//        add_filter('et_ajax_widgets', '__return_false');
        add_filter('etheme_elementor_theme_builder', '__return_true');

        $layered_count_filters = array(
            'etheme_cats_widget_count',
            'etheme_brands_widget_count',
            'etheme_product_status_filter_widget_count'
        );
        foreach ($layered_count_filters as $layered_count_filter) {
            add_filter($layered_count_filter, array($this, 'filter_layered_nav_count'), 10, 2);
        }

        add_filter('woocommerce_layered_nav_term_html', array($this, 'filter_layered_nav_term_html'), 10, 4);

        add_filter('woocommerce_rating_filter_count', array($this, 'filter_rating_filter_count'), 10, 3);

        if ( $settings['widgets_rating_type'] == 'advanced' )
            add_filter('woocommerce_rating_filter_count', array($this, 'filter_rating_filter_text'), 20, 3);

        add_filter('woocommerce_layered_nav_count', '__return_empty_string');

        add_filter('widget_title', array($this, 'filter_widget_title_tag'));

        add_filter( 'wp_list_categories', array($this, 'filter_wp_list_categories_count'), 10, 2 );

        add_filter('get_archives_link', array($this, 'filter_get_archives_link'), 10);

        Elementor::add_fake_woocommerce_query();

        add_filter('dynamic_sidebar_params', array($this, 'filter_dynamic_sidebar_params'), 999);

        ?>
        <div <?php $this->print_render_attribute_string( 'sidebar-wrapper' ); ?>>
            <?php
            dynamic_sidebar( $sidebar );
            ?>
        </div>
        <?php

        remove_filter('dynamic_sidebar_params', array($this, 'filter_dynamic_sidebar_params'), 999);

        remove_filter('get_archives_link', array($this, 'filter_get_archives_link'), 10);

        remove_filter( 'wp_list_categories', array($this, 'filter_wp_list_categories_count'), 10, 2 );

        remove_filter('widget_title', array($this, 'filter_widget_title_tag'));

        remove_filter('woocommerce_layered_nav_count', '__return_empty_string');

        if ( $settings['widgets_rating_type'] == 'advanced' )
            remove_filter('woocommerce_rating_filter_count', array($this, 'filter_rating_filter_text'), 20, 3);

        remove_filter('woocommerce_rating_filter_count', array($this, 'filter_rating_filter_count'), 10, 3);

        remove_filter('woocommerce_layered_nav_term_html', array($this, 'filter_layered_nav_term_html'), 10, 4);

        foreach ($layered_count_filters as $layered_count_filter) {
            remove_filter($layered_count_filter, array($this, 'filter_layered_nav_count'), 10, 2);
        }

        remove_filter('etheme_elementor_theme_builder', '__return_true');
//        remove_filter('et_ajax_widgets', '__return_false');
//        remove_filter('theme_mod_ajax_product_filter', '__return_false');

        if ( $edit_mode )
            remove_filter('etheme_elementor_edit_mode', '__return_true');

        if ( $edit_mode ) : ?>
            <script>jQuery(document).ready(function($){
                    etTheme.swiperFunc();
                    etTheme.secondInitSwipers();
                    if ( etTheme.sliderVertical !== undefined )
                        etTheme.sliderVertical();
                    etTheme.global_image_lazy();
                    if ( etTheme.contentProdImages !== undefined )
                        etTheme.contentProdImages();
                    if ( window.hoverSlider !== undefined ) {
                        window.hoverSlider.init({});
                        window.hoverSlider.prepareMarkup();
                    }
                    if ( etTheme.countdown !== undefined )
                        etTheme.countdown();
                    etTheme.customCss();
                    etTheme.customCssOne();
                    if ( etTheme.reinitSwatches !== undefined )
                        etTheme.reinitSwatches();

                    $( document.body ).trigger( 'init_price_filter' );
                });</script>
        <?php endif;
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

    protected function render_icon($settings) {
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

    function filter_get_archives_link($output) {
        $has_brackets = $this->get_settings_for_display('widgets_count_brackets') == 'wrap';
        $open_bracket = $has_brackets ? '(' : '';
        $close_bracket = $has_brackets ? ')' : '';
        $output = str_replace('</a>&nbsp;(', ' <span class="count">'.$open_bracket, $output);
        $output = str_replace(')', $close_bracket.'</span></a>', $output);
        return $output;
    }

    function filter_wp_list_categories_count( $output, $args ) {
        $has_brackets = $this->get_settings_for_display('widgets_count_brackets') == 'wrap';
        $open_bracket = $has_brackets ? '(' : '';
        $close_bracket = $has_brackets ? ')' : '';
        $output = str_replace( array('</a> (', '<span class="count">('), array('</a><span class="count">'.$open_bracket, '<span class="count">'.$open_bracket), $output );
        $output = str_replace( array(')', ')</span>'), $close_bracket.'</span>', $output );
        return $output;
    }

    public function filter_dynamic_sidebar_params($params) {
        $settings = $this->get_settings_for_display();
        $title_tag = $settings['widgets_title_html_wrapper_tag'];
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ( isset($params[0]['before_widget']) ) {
            $params[0]['before_widget'] = str_replace(
                'class="', 'class="etheme_sidebar-widget-item ', $params[0]['before_widget']);
        }

        if ( isset($params[0]['before_title']) ) {
            $params[0]['before_title'] = str_replace(
                array('<h4', '</h4>', '<p', '</p>'),
                array('<' . $title_tag, '</' . $title_tag . '>',
                    '<' . $title_tag, '</' . $title_tag . '>'),
                $params[0]['before_title']);
            if ( $settings['widgets_title_type'] != 'underline' ) {
                $params[0]['before_title'] = str_replace(
                    'class="', 'class="style-' . $settings['widgets_title_type'] . ' ', $params[0]['before_title']);
            }
            if ( $edit_mode ) {
                $params[0]['before_title'] = str_replace(
                        'class="', 'class="elementor-clickable ', $params[0]['before_title']);
            }
        }
        if ( isset($params[0]['after_title']) ) {
            $params[0]['after_title'] = str_replace(
                array('<h4', '</h4>', '<p', '</p>'),
                array('<' . $title_tag, '</' . $title_tag . '>', '<' . $title_tag, '</' . $title_tag . '>'),
                $params[0]['after_title']);
        }
        if ( !!$settings['widgets_toggle'] ) {
            // faster then strpos php function and more flexible
            $locked_toggle_classes = apply_filters('etheme_sidebar_widgets_toggle_locked_classes', array('sidebar-slider', 'etheme_widget_satick_block'));
            if ( str_replace($locked_toggle_classes, '', $params[0]['before_widget']) == $params[0]['before_widget'] ) {
                if ( isset($params[0]['before_widget']) ) {
                    $params[0]['before_widget'] = str_replace(
                        'class="', 'class="widget-has-toggle ', $params[0]['before_widget']);

                    // toggle widget by default from PHP + CSS
    //                $breakpoints_list = Elementor::get_breakpoints_list();
    //                $opened_on_devices = $settings['widgets_toggle_action_opened'];
    //                $closed_on_devices = array_diff(array_keys($breakpoints_list), $opened_on_devices);
    //
    //                $params[0]['before_widget'] = str_replace(
    //                    'class=', 'data-toggled-on="'.implode(',', $closed_on_devices).'" class=', $params[0]['before_widget']);
                }
            }
        }
        if ( !!$settings['widgets_scrollable'] ) {
            // faster then strpos php function and more flexible
            $locked_scrollable_classes = apply_filters('etheme_sidebar_widgets_scrollable_locked_classes', array('sidebar-slider', 'etheme_widget_satick_block', 'widget_product_tag_cloud', 'widget_tag_cloud'));
            if ( str_replace($locked_scrollable_classes, '', $params[0]['before_widget']) == $params[0]['before_widget'] ) {
//            if (false !== strpos($params[0]['before_widget'], 'sidebar-slider')) {}
//            else {
                if (isset($params[0]['before_widget'])) {
                    $params[0]['before_widget'] = str_replace(
                        'class="', 'class="widget-scrollable ', $params[0]['before_widget']);
                }
            }
        }
        if ( !!$settings['widgets_list_limited'] && $settings['widgets_list_limited_after'] ) {
            // faster then strpos php function and more flexible
            $locked_list_limited_classes = apply_filters('etheme_sidebar_widgets_list_limited_locked_classes', array('sidebar-slider', 'etheme_widget_satick_block', 'type-st-image-swatch', 'type-st-color-swatch', 'type-st-label-swatch', 'null-instagram-feed'));
            if ( str_replace($locked_list_limited_classes, '', $params[0]['before_widget']) == $params[0]['before_widget'] ) {
//            if (false !== strpos($params[0]['before_widget'], 'sidebar-slider')) {}
//            else {
                if ( isset($params[0]['before_widget']) ) {
                    $params[0]['before_widget'] = str_replace(
                        'class="', 'class="widget-has-list-limited calculation-process ', $params[0]['before_widget']);
                }
            }
        }
        return $params;
    }

    public function filter_widget_title_tag($title_html) {
        $settings = $this->get_settings_for_display();
        $title_tag = $settings['widgets_title_html_wrapper_tag'];
        return str_replace(
            array('<h4', '</h4>', '<p', '</p>'),
            array('<'.$title_tag, '</'.$title_tag.'>', '<'.$title_tag, '</'.$title_tag.'>'),
            $title_html);
    }

    public function filter_rating_filter_count($count_html, $count, $rating) {
        $has_brackets = $this->get_settings_for_display('widgets_count_brackets') == 'wrap';
        $open_bracket = $has_brackets ? '(' : '';
        $close_bracket = $has_brackets ? ')' : '';
        return '<span class="count">'.$open_bracket.$count.$close_bracket.'</span>';
    }

    public function filter_rating_filter_text($count_in_brackets, $count, $rating) {
        return '<em>' . ($rating >= 5 ? $rating : sprintf(esc_html__('%s & Up', 'xstore-core'), $rating) ) . '</em>' . $count_in_brackets;
    }

    public function filter_layered_nav_count($count_html, $count) {
        $has_brackets = $this->get_settings_for_display('widgets_count_brackets') == 'wrap';
        $open_bracket = $has_brackets ? '(' : '';
        $close_bracket = $has_brackets ? ')' : '';
        if ( !empty($count_html) ) // means we have stock value
            return '<span class="count">'.$open_bracket.$count.$close_bracket.'</span>';
        return $count_html;
    }

    public function filter_layered_nav_term_html($term_html, $term, $link, $count) {
        $has_brackets = $this->get_settings_for_display('widgets_count_brackets') == 'wrap';
        $open_bracket = $has_brackets ? '(' : '';
        $close_bracket = $has_brackets ? ')' : '';
        $count_html = '<span class="count">'.$open_bracket.$count.$close_bracket.'</span>';
        return str_replace(array('</span>', '</a>'), array($count_html.'</span>',$count_html.'</a>'), $term_html);
    }

    /**
     * Render sidebar widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template() {}

    /**
     * Render sidebar widget as plain content.
     *
     * Override the default render behavior, don't render sidebar content.
     *
     * @since 1.0.0
     * @access public
     */
    public function render_plain_content() {}
}
