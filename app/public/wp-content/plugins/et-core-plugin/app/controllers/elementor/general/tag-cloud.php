<?php

namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;

/**
 * Tag Cloud widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Tag_Cloud extends \Elementor\Widget_Base
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
        return 'etheme_tag_cloud';
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
        return __('Tag/Category Cloud', 'xstore-core');
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
        return 'eight_theme-elementor-icon et-elementor-social-links';
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
        return ['woocommerce', 'wordpress', 'shop', 'blog', 'single', 'post', 'categories', 'tags', 'taxonomy', 'store', 'filter', 'action', 'tag', 'category', 'product', 'archive'];
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
        return ['eight_theme_general', 'woocommerce-elements-single', 'woocommerce-elements-archive', 'theme-elements-single'];
    }

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
    public function get_style_depends() {
        $styles = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
            $styles[] = 'etheme-elementor-sidebar';
        return $styles;
    }

    /**
     * Get widget dependency.
     *
     * @return array Widget dependency.
     * @since 5.2
     * @access public
     *
     */
    public function get_script_depends() {
        $scripts = [ 'accounting', 'wc-jquery-ui-touchpunch', 'wc-price-slider' ];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
            $scripts[] = 'etheme_elementor_sidebar';
        return $scripts;
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
            'section_general',
            [
                'label' => esc_html__('General', 'xstore-core'),
            ]
        );

        $filtered_taxonomies = $this->get_taxonomies_to_filter();

        $this->add_control(
            'taxonomy',
            [
                'label'       => __( 'Taxonomy', 'xstore-core' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options' => $filtered_taxonomies,
                'default' => array_key_exists('product_tag', $filtered_taxonomies) ? 'product_tag' : array_key_first($filtered_taxonomies)
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'      => esc_html__( 'Limit', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'format',
            [
                'label'     => esc_html__( 'Format', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'flat',
                'options'   => array(
                    'flat'  => esc_html__( 'Flat', 'xstore-core' ),
                    'list'  => esc_html__( 'List', 'xstore-core' ),
                ),
                'selectors_dictionary'  => [
                    'flat' => '',
                    'list'          => '--cols-gap: 0;',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'list_style_type',
            [
                'label'     => esc_html__( 'List Style', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT2,
                'default'   => 'none',
                'options'   => array(
                    'disc' => 	esc_html__('Default value. The marker is a filled circle', 'xstore-core'),
                    'armenian' => 	esc_html__('The marker is traditional Armenian numbering', 'xstore-core'),
                    'circle' => 	esc_html__('The marker is a circle', 'xstore-core'),
                    'cjk-ideographic' => 	esc_html__('The marker is plain ideographic numbers', 'xstore-core'),
                    'decimal' => 	esc_html__('The marker is a number', 'xstore-core'),
                    'decimal-leading-zero' => 	esc_html__('The marker is a number with leading zeros (01, 02, 03, etc.)', 'xstore-core'),
                    'georgian' => 	esc_html__('The marker is traditional Georgian numbering', 'xstore-core'),
                    'hebrew' => 	esc_html__('The marker is traditional Hebrew numbering', 'xstore-core'),
                    'hiragana' => 	esc_html__('The marker is traditional Hiragana numbering', 'xstore-core'),
                    'hiragana-iroha' => 	esc_html__('The marker is traditional Hiragana iroha numbering', 'xstore-core'),
                    'katakana' => 	esc_html__('The marker is traditional Katakana numbering', 'xstore-core'),
                    'katakana-iroha' => 	esc_html__('The marker is traditional Katakana iroha numbering', 'xstore-core'),
                    'lower-alpha' => 	esc_html__('The marker is lower-alpha (a, b, c, d, e, etc.)', 'xstore-core'),
                    'lower-greek' => 	esc_html__('The marker is lower-greek', 'xstore-core'),
                    'lower-latin' => 	esc_html__('The marker is lower-latin (a, b, c, d, e, etc.)', 'xstore-core'),
                    'lower-roman' => 	esc_html__('The marker is lower-roman (i, ii, iii, iv, v, etc.)', 'xstore-core'),
                    'none' => 	esc_html__('No marker is shown', 'xstore-core'),
                    'square' => 	esc_html__('The marker is a square', 'xstore-core'),
                    'custom' => 	esc_html__('Custom marker is shown', 'xstore-core'),
                    'upper-alpha' => 	esc_html__('The marker is upper-alpha (A, B, C, D, E, etc.)', 'xstore-core'),
                    'upper-greek' => 	esc_html__('The marker is upper-greek', 'xstore-core'),
                    'upper-latin' => 	esc_html__('The marker is upper-latin (A, B, C, D, E, etc.)', 'xstore-core'),
                    'upper-roman' => 	esc_html__('The marker is upper-roman (I, II, III, IV, V, etc.)', 'xstore-core'),
                ),
                'selectors' => [
                    '{{WRAPPER}} ul' => 'list-style-type: {{VALUE}};',
                ],
                'condition' => [
                    'format' => 'list'
                ]
            ]
        );

        $this->add_control(
            'list_style_type_custom',
            [
                'label' => esc_html__('Custom Marker', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '»',
                'condition' => [
                    'format' => 'list',
                    'list_style_type' => 'custom'
                ],
                'selectors' => [
                    '{{WRAPPER}} ul li::marker' => 'content: "{{VALUE}}";',
                ],
            ]
        );

        $this->add_control(
            'list_style_position',
            [
                'label'     => esc_html__( 'List Style Position', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'inside',
                'options'   => array(
                    'outside' => esc_html__('Outside', 'xstore-core'),
                    'inside' => esc_html__('Inside', 'xstore-core'),
                ),
                'selectors' => [
                    '{{WRAPPER}} ul' => 'list-style-position: {{VALUE}};',
                ],
                'condition' => [
                    'format' => 'list',
                    'list_style_type!' => 'none'
                ]
        ]);

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order By', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'name',
                'options'   => array(
                    'name'  => esc_html__( 'Name', 'xstore-core' ),
                    'count'  => esc_html__( 'Count', 'xstore-core' ),
                ),
            ]
        );

        $this->add_control(
            'order',
            [
                'label'     => esc_html__( 'Sort Order', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'ASC',
                'options'   => array(
                    'DESC' => esc_html__( 'Descending', 'xstore-core' ),
                    'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
                ),
            ]
        );

        $this->add_control(
            'count',
            [
                'label'        => esc_html__( 'Show count', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => __( 'Separator Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'xstore-core'),
                    'icon' => __( 'Icon', 'xstore-core' ),
                    'image' => __( 'Image', 'xstore-core' ),
                    'text' => __( 'Custom Text', 'xstore-core' ),
                ],
                'default' => 'none',
                'condition' => [
                    'format' => 'flat'
                ]
            ]
        );

        $this->add_control(
            'separator_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'separator_icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'format' => 'flat',
                    'separator_type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'separator_image',
            [
                'label' => esc_html__( 'Choose File', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'format' => 'flat',
                    'separator_type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'         => 'separator_image_size',
                'label'        => __( 'Image Size', 'xstore-core' ),
                'default'      => 'full',
                'condition' => [
                    'format' => 'flat',
                    'separator_type' => 'image',
                    'separator_image[url]!' => ''
                ],
            ]
        );

        $this->add_control(
            'separator_text',
            [
                'label' => esc_html__('Custom Text', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '|',
                'condition' => [
                    'format' => 'flat',
                    'separator_type' => 'text',
                ],
            ]
        );

        $this->end_controls_section();

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
            'heading_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Tag cloud', 'xstore-core'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_type',
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
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'html_heading_tag',
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
                'condition' => [
                    'show_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'widgets_toggle',
            [
                'label' => __( 'Widget Toggle', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Turn on the toggle for the widget title to open and close the widget content.', 'xstore-core' ),
                'frontend_available' => true,
                'condition' => [
                    'show_heading!' => '',
                ]
            ]
        );

        $this->add_control(
            'widgets_toggle_action_opened',
            [
                'label'    => __( 'Widget Opened On', 'xstore-core' ),
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
                    'show_heading!' => '',
                    'widgets_toggle!' => ''
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => __( 'Columns Gap', 'xstore-core' ),
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
                    '{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

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
                'selectors' => [
                    '{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_border_width',
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
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_control(
            'heading_border_color',
            [
                'label'     => __( 'Border Color', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-border-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_inner_spacing',
            [
                'label' => esc_html__( 'Inner Bottom Spacing', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-inner-space-bottom: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'heading_type!' => ['classic']
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_spacing',
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
            'heading_element_heading',
            [
                'label' => __( 'Design element', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

//        $this->add_responsive_control(
//            'heading_element_width',
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
//                    'heading_type' => ['line-aside']
//                ]
//            ]
//        );

        $this->add_control(
            'heading_element_color',
            [
                'label'     => __( 'Color Active', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--widget-title-element-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_type' => ['line-aside', 'square-aside', 'circle-aside', 'colored-underline']
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tag_style',
            [
                'label' => esc_html__( 'Tag Item', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tag_typography',
                'selector' => '{{WRAPPER}} .tagcloud',
                'fields_options' => [
                    'font_size' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}}; --tagcloud-font-size: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'tag_text_shadow',
                'selector' => '{{WRAPPER}} .tag-cloud-link',
            ]
        );

        $this->start_controls_tabs( 'tabs_tag_style' );

        $this->start_controls_tab(
            'tab_tag_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'tag_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_tag_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'tag_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link:hover, {{WRAPPER}} .tag-cloud-link:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tag-cloud-link:hover svg, {{WRAPPER}} .tag-cloud-link:focus svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}}' => '--tagcloud-color-hover: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'tag_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link:hover, {{WRAPPER}} .tag-cloud-link:focus' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--tagcloud-bg-color-hover: {{VALUE}};'
                ],
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

        $this->add_control(
            'tag_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'format' => 'flat',
                    'tag_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link:hover, {{WRAPPER}} .tag-cloud-link:focus' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}' => '--tagcloud-br-color-hover: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tag_border',
                'selector' => '{{WRAPPER}} .tag-cloud-link, {{WRAPPER}} .tag-cloud-link.button',
                'separator' => 'before',
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

        $this->add_control(
            'tag_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tag_box_shadow',
                'selector' => '{{WRAPPER}} .tag-cloud-link',
            ]
        );

        $this->add_responsive_control(
            'tag_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tag-cloud-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'format' => 'flat',
                ],
            ]
        );

        $this->add_control(
            'tag_market_color',
            [
                'label' => __( 'Marker Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'separator' => 'before',
                'condition' => [
                    'format' => 'list',
                    'list_style_type!' => 'none'
                ],
                'selectors' => [
                    '{{WRAPPER}} ul li::marker' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_market_indent',
            [
                'label' => __( 'Marker Spacing', 'xstore-core' ),
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
                    '{{WRAPPER}} .tag-cloud-link' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'format' => 'list',
                    'list_style_type!' => 'none'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator_style',
            [
                'label' => esc_html__( 'Items Separator', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'separator_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tagcloud' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_size',
            [
                'label' => __( 'Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'max' => 70,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--tagcloud-separator-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_type!' => 'none'
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

        if ( !!$settings['widgets_toggle'] ) {
            wp_enqueue_script('etheme_elementor_sidebar');
            wp_enqueue_style('etheme-elementor-sidebar');
        }

        $widget_classes = array(
            'etheme-elementor-tag-cloud-widget'
        );
        if ( !!$settings['widgets_toggle'] ) {
            $widget_classes[] = 'widget-has-toggle';
        }
        $title_classes = array(
            'widget-title',
        );
        if ( $settings['heading_type'] != 'underline' )
            $title_classes[] = 'style-' . $settings['heading_type'];
        if ( $edit_mode )
            $title_classes[] = 'elementor-clickable';

        $args = array(
            'before_widget' => '<div id="'.$this->get_id().'" class="'.implode(' ', $widget_classes).'">',
            'after_widget' => '</div><!-- //sidebar-widget -->',
            'before_title' => apply_filters('etheme_sidebar_before_title', '<'.$settings['html_heading_tag'].' class="'.implode(' ', $title_classes).'"><span>' ),
            'after_title' => apply_filters('etheme_sidebar_after_title', '</span></'.$settings['html_heading_tag'].'>'),
        );

        $instance = array(
            'title' => !!$settings['show_heading'] ? $settings['heading_text'] : false,
            'show_count' => !!$settings['count'],
        );

        if ( !!!$settings['show_heading'] )
            add_filter('widget_title', '__return_false');

        add_filter('widget_tag_cloud_args', array($this, 'filter_args'));

        $widget = new \WP_Widget_Tag_Cloud();
        $widget->widget($args, $instance);

        if ( !!!$settings['show_heading'] )
            remove_filter('widget_title', '__return_false');

        remove_filter('widget_tag_cloud_args', array($this, 'filter_args'));

    }

    public function filter_args($global_args) {
        $settings = $this->get_settings_for_display();

        $args = array(
            'taxonomy'                  => $settings['taxonomy'],
            'topic_count_text_callback' => array( $this, 'topic_count_text' ),
            'format' => $settings['format'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'unit' => '',
            'separator' => '',
            'show_count' => !!$settings['count'],
        );

        if ( $settings['limit'] )
            $args['number'] = $settings['limit'];
        if ( $settings['format'] == 'flat' ) {
            switch ($settings['separator_type']) {
                case 'icon':
                    $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();

                    $migrated = isset( $settings['__fa4_migrated']['separator_selected_icon'] );
                    $is_new = empty( $settings['separator_icon'] ) && $migration_allowed;
                    $has_icon = ! empty( $settings['separator_icon'] );
                    if ( $has_icon ) {
                        $this->add_render_attribute( 'i', 'class', $settings['separator_icon'] );
                        $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
                    }
                    if ( ! $has_icon && ! empty( $settings['separator_selected_icon']['value'] ) ) {
                        $has_icon = true;
                    }
                    if ( $has_icon ) :
                        ob_start();
                        if ( $is_new || $migrated ) {
                            \Elementor\Icons_Manager::render_icon( $settings['separator_selected_icon'], [ 'aria-hidden' => 'true' ] );
                        } elseif ( ! empty( $settings['separator_icon'] ) ) {
                            ?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
                        }
                        $args['separator'] = ob_get_clean();
                    endif;
                    break;
                case 'image':
                    $args['separator'] = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'separator_image_size', 'separator_image' );
                    break;
                case 'text':
                    $args['separator'] = $settings['separator_text'];
                    break;
            }
            if ( !empty($args['separator']) )
                $args['separator'] = '<span class="tag-cloud-link-separator">'.$args['separator'].'</span>';
        }
        return array_merge($global_args, $args);
    }

    /**
     * Return filtered taxonomies for filters.
     *
     * @since 5.2
     *
     * @return mixed
     */
    public function get_taxonomies_to_filter() {
        $taxonomies = array(
            'post_tag' => esc_html__('Post tags', 'xstore-core'),
            'category' => esc_html__('Post categories', 'xstore-core')
        );
        if ( class_exists('WooCommerce') ) {
            $taxonomies = array_merge($taxonomies, array(
                'product_cat' => esc_html__('Product categories', 'xstore-core'),
                'product_tag' => esc_html__('Product tags', 'xstore-core'),
            ));
        }
        return apply_filters('etheme_product_tag_cloud_taxonomies', $taxonomies);
    }

    /**
     * Returns topic count text.
     *
     * @since 5.2
     * @param int $count Count text.
     * @return string
     */
    public function topic_count_text( $count ) {
        $settings = $this->get_settings_for_display();
        switch ($settings['taxonomy']) {
            case 'product_cat':
            case 'product_tag':
            /* translators: %s: product count */
            return sprintf( _n( '%s product', '%s products', $count, 'xstore-core' ), number_format_i18n( $count ) );
                break;
            case 'category':
            case 'post_tag':
            /* translators: %s: post count */
            return sprintf( _n( '%s post', '%s posts', $count, 'xstore-core' ), number_format_i18n( $count ) );
                break;
            default:
                return apply_filters('etheme_product_tag_cloud_topic_count_text_callback', $count);
            break;
        }
    }

}
