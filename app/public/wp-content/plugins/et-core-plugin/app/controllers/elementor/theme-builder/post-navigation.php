<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder;

use ETC\App\Classes\Elementor;

/**
 * Post Navigation widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Post_Navigation extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 5.4
     * @access public
     *
     */
    public function get_name()
    {
        return 'etheme_post_navigation';
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
        return __('Posts Navigation', 'xstore-core');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 5.4
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eight_theme-elementor-icon et-elementor-posts-navigation et-elementor-post-widget-icon-only';
    }

    public function get_categories()
    {
        return ['theme-elements-single', 'woocommerce-elements-single'];
    }

    public function get_keywords()
    {
        return ['post', 'previous', 'next', 'nav'];
    }

    /**
     * Help link.
     *
     * @return string
     * @since 5.4
     *
     */
    public function get_custom_help_url()
    {
        return etheme_documentation_url('122-elementor-live-copy-option', false);
    }

    /**
     * Register widget controls.
     *
     * @since 5.4
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label' => esc_html__( 'Show Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => esc_html__( 'Previous Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'ai' => [
                    'active' => false,
                ],
                'default' => esc_html__( 'Previous', 'xstore-core' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => esc_html__( 'Next Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Next', 'xstore-core' ),
                'condition' => [
                    'show_label' => 'yes',
                ],
                'ai' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => esc_html__( 'Show Image', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => '',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show Title', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_limit_type',
            [
                'label'       => esc_html__( 'Limit By', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'chars' => esc_html__('Chars', 'xstore-core'),
                    'words' => esc_html__('Words', 'xstore-core'),
                    'lines' => esc_html__('Lines', 'xstore-core'),
                    'width' => esc_html__('Max-width', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
                ],
                'default' => 'words',
                'condition' => [
                    'show_title!' => ''
                ]
            ]
        );

        $this->add_control(
            'title_limit',
            [
                'label'      => esc_html__( 'Limit', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'default' => 7,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'condition' => [
                    'show_title!' => '',
                    'title_limit_type' => ['chars', 'words']
                ]
            ]
        );

        $this->add_control(
            'title_lines_limit',
            [
                'label'      => esc_html__( 'Lines Limit', 'xstore-core' ),
                'description' => esc_html__( 'Line-height will not work with this option. Don\'t set it up in typography settings.', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'title_limit!' => '',
                    'title_limit_type' => 'lines'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--post-nav-title-lines: {{VALUE}};',
                    '{{WRAPPER}} .etheme-post-navigation__link' => 'display: block; height: calc(var(--post-nav-title-lines) * 3ex); line-height: 3ex; overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width_limit',
            [
                'label'      => esc_html__( 'Max-width Limit', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 150
                ],
                'condition' => [
                    'title_limit!' => '',
                    'title_limit_type' => 'width'
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__prev--title, {{WRAPPER}} .post-navigation__next--title' => 'max-width: {{SIZE}}{{UNIT}};display: inline-block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;vertical-align: middle;',
                ],
            ]
        );

        $this->add_control(
            'show_arrow',
            [
                'label' => esc_html__( 'Show Arrows', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrow',
            [
                'label' => esc_html__( 'Arrows Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'arrow' => esc_html__( 'Chevron Arrow', 'xstore-core' ),
                    'arrow-2' => esc_html__( 'Direction Arrow', 'xstore-core' ),
                    'arrow-3' => esc_html__( 'Bordered Arrow', 'xstore-core' ),
                    'custom' => esc_html__( 'Custom', 'xstore-core' ),
                ],
                'default' => 'arrow-2',
                'condition' => [
                    'show_arrow' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'prev_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => false,
                'fa4compatibility' => 'prev_icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-left-arrow'
                ],
                'skin' => 'inline',
                'condition' => [
                    'show_arrow' => 'yes',
                    'arrow' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'next_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => false,
                'fa4compatibility' => 'next_icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-right-arrow'
                ],
                'skin' => 'inline',
                'condition' => [
                    'show_arrow' => 'yes',
                    'arrow' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'show_separator',
            [
                'label' => esc_html__( 'Show Separator', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'separator_button_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'placeholder' => __( 'Archive', 'xstore-core' ),
                'condition' => [
                    'show_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'separator_link_to',
            [
                'label' => esc_html__( 'Link', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'archive',
                'options' => [
                    '' => esc_html__( 'None', 'elementor-pro' ),
                    'author_website' => esc_html__( 'Author Website', 'elementor-pro' ),
                    'archive' => esc_html__( 'Archive', 'elementor-pro' ),
                    'custom' => esc_html__( 'Custom', 'elementor-pro' ),
                ],
                'condition' => [
                    'show_separator!' => ''
                ]
            ]
        );

        $this->add_control(
            'separator_custom_link',
            [
                'label' => __( 'Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_separator!' => '',
                    'separator_link_to' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'separator_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => false,
                'fa4compatibility' => 'separator_icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-grid-2-columns'
                ],
                'skin' => 'inline',
                'condition' => [
                    'show_separator!' => '',
                ]
            ]
        );

        $this->add_control(
            'separator_icon_align',
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
                    'show_separator!' => '',
                    'separator_button_text!' => '',
                    'separator_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'separator_icon_indent',
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
                    '{{WRAPPER}} .etheme-post-navigation__separator .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-post-navigation__separator .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-post-navigation__separator.flex-wrap .button-text:last-child' => 'margin: {{SIZE}}{{UNIT}} 0 0;',
                ],
                'condition' => [
                    'show_separator!' => '',
                    'separator_button_text!' => '',
                    'separator_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'query',
            [
                'label' => esc_html__( 'Query', 'xstore-core' ),
            ]
        );

        // Filter out post type without taxonomies
        $post_type_options = [];
        $post_type_taxonomies = [];
        foreach ( \ElementorPro\Core\Utils::get_public_post_types() as $post_type => $post_type_label ) {
            $taxonomies = \ElementorPro\Core\Utils::get_taxonomies( [ 'object_type' => $post_type ], false );
            if ( empty( $taxonomies ) ) {
                continue;
            }

            $post_type_options[ $post_type ] = $post_type_label;
            $post_type_taxonomies[ $post_type ] = [];
            foreach ( $taxonomies as $taxonomy ) {
                $post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
            }
        }

        $this->add_control(
            'in_same_term',
            [
                'label' => esc_html__( 'In same Term', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $post_type_options,
                'default' => '',
                'multiple' => true,
                'label_block' => true,
                'description' => esc_html__( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'xstore-core' ),
            ]
        );

        foreach ( $post_type_options as $post_type => $post_type_label ) {
            $this->add_control(
                $post_type . '_taxonomy',
                [
                    'label' => $post_type_label . ' ' . esc_html__( 'Taxonomy', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $post_type_taxonomies[ $post_type ],
                    'default' => '',
                    'condition' => [
                        'in_same_term' => $post_type,
                    ],
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'xstore-core' ),
                'tab' =>  \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .etheme-post-navigation'
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Padding', 'xstore-core'),
                'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 25,
                    'right' => 0,
                    'bottom' => 25,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} .etheme-post-navigation',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 1,
                            'left' => 0,
                            'right' => 0,
                            'bottom' => 1,
                        ],
                    ],
                    'color' => [
                        'default' => get_theme_mod( 'dark_styles', false ) ? '#2f2f2' : '#e1e1e1',
                    ]
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-post-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .etheme-post-navigation',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'label_style',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_label_style' );

        $this->start_controls_tab(
            'label_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} span.post-navigation__next--label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'label_color_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--label:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} span.post-navigation__next--label:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} span.post-navigation__prev--label, {{WRAPPER}} span.post-navigation__next--label',
                'exclude' => [ 'line_height' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_post_navigation_style' );

        $this->start_controls_tab(
            'tab_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_color_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.post-navigation__prev--title:hover, {{WRAPPER}} span.post-navigation__next--title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} span.post-navigation__prev--title, {{WRAPPER}} span.post-navigation__next--title',
                'exclude' => [ 'line_height' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrow_style',
            [
                'label' => esc_html__( 'Arrow', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrow' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_post_navigation_arrow_style' );

        $this->start_controls_tab(
            'arrow_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'arrow_color_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => esc_html__( 'Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                    'em' => [
                        'max' => 30,
                    ],
                    'rem' => [
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-navigation__arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_padding',
            [
                'label' => esc_html__( 'Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                    'em' => [
                        'max' => 5,
                    ],
                    'rem' => [
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-right: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} .post-navigation__arrow-next' => 'padding-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .post-navigation__arrow-prev' => 'padding-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} .post-navigation__arrow-next' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'separator_style',
            [
                'label' => esc_html__( 'Separator', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_separator' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_separator_style' );

        $this->start_controls_tab(
            'separator_color_normal',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-post-navigation__separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'separator_color_hover',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'separator_hover_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'description' => esc_html__('This option works if the Separator link is not set to "None" value', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.etheme-post-navigation__separator:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'separator_typography',
                'selector' => '{{WRAPPER}} .etheme-post-navigation__separator',
                'exclude' => [ 'line_height' ],
                'condition' => [
                    'separator_button_text!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'separator_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'separator' => 'before',
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                    'em' => [
                        'max' => 30,
                    ],
                    'rem' => [
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-post-navigation__separator .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_selected_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.4
     * @access protected
     */
    protected function render() {
        global $local_settings;
        $settings = $this->get_settings_for_display();
        $local_settings = $settings;

        $prev_label = '';
        $next_label = '';
        $prev_arrow = '';
        $next_arrow = '';

        if ( 'yes' === $settings['show_label'] ) {
            $prev_label = '<span class="post-navigation__prev--label">' . $settings['prev_label'] . '</span>';
            $next_label = '<span class="post-navigation__next--label">' . $settings['next_label'] . '</span>';
        }

        if ( !!$settings['show_arrow'] ) {
            if ( $settings['arrow'] == 'custom' ) {
                ob_start();
                    $this->render_icon($settings, 'prev_');
                $prev_arrow = ob_get_clean();
                ob_start();
                    $this->render_icon($settings, 'next_');
                $next_arrow = ob_get_clean();
            }
            else {
                if (is_rtl()) {
                    $prev_icon_class = 'et-right-' . $settings['arrow'];
                    $next_icon_class = 'et-left-' . $settings['arrow'];
                } else {
                    $prev_icon_class = 'et-left-' . $settings['arrow'];
                    $next_icon_class = 'et-right-' . $settings['arrow'];
                }
                $prev_arrow = '<i class="et-icon ' . $prev_icon_class . '" aria-hidden="true"></i>';
                $next_arrow = '<i class="et-icon ' . $next_icon_class . '" aria-hidden="true"></i>';
            }

            $prev_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-prev">'.$prev_arrow.'<span class="elementor-screen-only">' . esc_html__( 'Prev', 'xstore-core' ) . '</span></span>';
            $next_arrow = '<span class="post-navigation__arrow-wrapper post-navigation__arrow-next">'.$next_arrow.'<span class="elementor-screen-only">' . esc_html__( 'Next', 'xstore-core' ) . '</span></span>';
        }

        $prev_title = '';
        $next_title = '';

        if ( 'yes' === $settings['show_title'] ) {
            $hidden_title_classes = ' elementor-hidden-tablet elementor-hidden-mobile';
            $prev_title = '<span class="post-navigation__prev--title'.$hidden_title_classes.'">%title</span>';
            $next_title = '<span class="post-navigation__next--title'.$hidden_title_classes.'">%title</span>';
        }

        $in_same_term = false;
        $taxonomy = 'category';
        $post_type = get_post_type( get_queried_object_id() );

        if ( ! empty( $settings['in_same_term'] ) && is_array( $settings['in_same_term'] ) && in_array( $post_type, $settings['in_same_term'] ) ) {
            if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
                $in_same_term = true;
                $taxonomy = $settings[ $post_type . '_taxonomy' ];
            }
        }
        if ( $settings['title_limit_type'] != 'none' )
            add_filter('the_title', array($this, 'limit_title_string'), 10);

        $prev_next_posts = array('next' => '', 'previous' => '');
        if ( $settings['show_image'] ) {
            foreach (array_keys($prev_next_posts) as $prev_next_key) {
                add_filter($prev_next_key . '_post_link', array($this, 'get_post_thumbnail_image_from_link'), 10, 5);
                $prev_next_posts[$prev_next_key] = $prev_next_key == 'previous' ? get_previous_post_link('%link', '', $in_same_term, '', $taxonomy) : get_next_post_link('%link', '', $in_same_term, '', $taxonomy);
                remove_filter($prev_next_key . '_post_link', array($this, 'get_post_thumbnail_image_from_link'), 10, 5);
            }
        }
        ?>
        <div class="etheme-post-navigation">
            <div class="etheme-post-navigation__prev etheme-post-navigation__link">
                <?php previous_post_link( '%link', $prev_next_posts['previous'] . $prev_arrow . '<span class="etheme-post-navigation__link__prev">' . $prev_label . $prev_title . '</span>', $in_same_term, '', $taxonomy ); ?>
            </div>

            <?php if ( !!$settings['show_separator'] ) :
                $separator_tag = 'div';
                $this->add_render_attribute( 'separator_inner', [
                    'class' => ['etheme-post-navigation__separator'],
                    'role' => 'button',
                    'aria-expanded' => 'false',
                ] );
                $this->add_render_attribute( 'separator_button_text', [
                    'class' => 'button-text',
                ] );
                $this->add_render_attribute( 'separator_button_icon', [
                    'class' => 'button-icon',
                ] );
                if ( $settings['separator_icon_align'] == 'above' ) {
                    $this->add_render_attribute( 'separator_inner', [
                        'class' => 'flex-wrap',
                    ] );
                    $this->add_render_attribute( 'separator_button_text', [
                        'class' => 'full-width',
                    ] );
                }
                switch ($settings['separator_link_to']) {
                    case 'author_website':
                        $separator_url = get_the_author_meta( 'user_url' );
                        if ( !empty($separator_url) ) {
                            $separator_tag = 'a';
                            $this->add_render_attribute( 'separator_inner', 'href', esc_url( $separator_url ) );
                            $this->add_render_attribute( 'separator_inner', 'target', '_blank' );
                        }
                        break;
                    case 'archive':
                        $separator_url = get_post_type_archive_link($post_type);
                        if ( !empty($separator_url) ) {
                            $separator_tag = 'a';
                            $this->add_render_attribute( 'separator_inner', 'href', esc_url( $separator_url ) );
                        }
                        break;
                    case 'custom':
                        if ( ! empty( $settings['separator_custom_link']['url'] ) ) {
                            $separator_tag = 'a';
                            $this->add_link_attributes( 'separator_inner', $settings['separator_custom_link'] );
                        }
                        break;
                }
            ?>
                <div class="etheme-post-navigation__separator-wrapper">
                    <<?php echo $separator_tag; ?> <?php echo $this->get_render_attribute_string( 'separator_inner' ); ?>>
                        <?php
                            $this->render_separator_text($settings);
                        ?>
                    </<?php echo $separator_tag; ?>>
                </div>
            <?php endif; ?>

            <div class="etheme-post-navigation__next etheme-post-navigation__link">
                <?php next_post_link( '%link', '<span class="etheme-post-navigation__link__next">' . $next_title . $next_label . '</span>' . $next_arrow . $prev_next_posts['next'], $in_same_term, '', $taxonomy ); ?>
            </div>
        </div>
        <?php
        if ( $settings['title_limit_type'] != 'none' )
            remove_filter('the_title', array($this, 'limit_title_string'), 10);

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <style>
                [data-elementor-device-mode=desktop] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-desktop,
                [data-elementor-device-mode=tablet] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-tablet,
                [data-elementor-device-mode=mobile] .elementor-element-<?php echo $this->get_id(); ?> .elementor-hidden-mobile {
                    display: none !important;
                }
            </style>
        <?php }
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_separator_text($settings) {

        if ( !$settings['separator_button_text'] || in_array($settings['separator_icon_align'], array('left', 'above') ) ) {
            ?>
            <span <?php echo $this->get_render_attribute_string( 'separator_button_icon' ); ?>>
                <?php $this->render_icon($settings, 'separator_'); ?>
            </span>
            <?php
        }

        if ( $settings['separator_button_text'] ) : ?>
            <span <?php echo $this->get_render_attribute_string( 'separator_button_text' ); ?>>
                <?php echo $settings['separator_button_text']; ?>
            </span>
        <?php endif; ?>

        <?php
        if ( $settings['separator_button_text'] && $settings['separator_icon_align'] == 'right') {
            ?>
            <span <?php echo $this->get_render_attribute_string( 'separator_button_icon' ); ?>>
                <?php $this->render_icon($settings, 'separator_'); ?>
            </span>
            <?php
        }

    }

    /**
     * Function that returns rendered title by chars/words limit.
     *
     * @param $title
     * @return mixed|string
     *
     * @since 4.1.3
     *
     */
    public function limit_title_string($title) {
        global $local_settings;
        if ( $local_settings['title_limit'] > 0) {
            if ( $local_settings['title_limit_type'] == 'chars' ) {
                return Elementor::limit_string_by_chars($title, $local_settings['title_limit']);
            }
            elseif ( $local_settings['title_limit_type'] == 'words' ) {
                return Elementor::limit_string_by_words($title, $local_settings['title_limit']);
            }
        }
        return $title;
    }

    public function get_post_thumbnail_image_from_link($output, $format, $link, $post, $adjacent) {
        return get_the_post_thumbnail($post->ID, array(90, 90));
    }

    /**
     * Render Icon HTML.
     *
     * @param $settings
     * @return void
     *
     * @since 4.0.12
     *
     */
    protected function render_icon($settings, $prefix = '') {
        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }
}
