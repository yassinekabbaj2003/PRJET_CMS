<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Single_Post;

/**
 * Author box widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Author_Box extends \ElementorPro\Modules\ThemeElements\Widgets\Author_Box {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'theme-post-etheme_author-box';
    }

    /**
     * Get widget icon.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eight_theme-elementor-icon et-elementor-account et-elementor-post-widget-icon-only';
    }

    /**
     * Help link.
     *
     * @since 5.4
     *
     * @return string
     */
    public function get_custom_help_url() {
        return etheme_documentation_url('122-elementor-live-copy-option', false);
    }

    protected function register_controls() {
        parent::register_controls();

        $this->update_control('source', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('show_avatar', [
            'separator' => 'none'
        ]);

        $this->update_control('layout', [
            'default' => !is_rtl() ? 'left' : 'right',
            'toggle'      => false,
        ]);

        $this->update_control('link_to', [
            'default' => 'posts_archive'
        ]);

        $this->update_control('show_link', [
            'default' => 'yes'
        ]);

        $this->update_control('link_text', [
            'default' => esc_html__('Author Posts', 'xstore-core')
        ]);

        $this->update_control('image_vertical_align', [
            'default' => 'middle',
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'show_avatar',
        ] );

        $this->add_control(
            'image_outside',
            [
                'label' => esc_html__( 'Image Outside', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'outside',
                'prefix_class' => 'elementor-author-box--layout-image-',
                'condition' => [
                    'show_avatar!' => ''
                ]
            ]
        );

        $this->end_injection();

        $this->update_control('image_size', [
            'selectors' => [
                '{{WRAPPER}} .elementor-author-box__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .elementor-author-box__avatar' => '--img-size: {{SIZE}}{{UNIT}};'
            ],
        ]);

        $is_dark_mode = get_theme_mod( 'dark_styles', false );

//        $this->remove_control('name_color');

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'name_color',
        ] );

//        $this->start_controls_tabs('name_colors_tabs');
//        $this->start_controls_tab( 'name_colors_tab_normal',
//            [
//                'label' => esc_html__('Normal', 'xstore-core')
//            ]
//        );

//        $this->add_control(
//            'name_color',
//            [
//                'label' => esc_html__( 'Color', 'elementor-pro' ),
//                'type' => \Elementor\Controls_Manager::COLOR,
//                'default' => $is_dark_mode ? '#ffffff' : '#222222',
//                'selectors' => [
//                    '{{WRAPPER}} .elementor-author-box__name' => 'color: {{VALUE}}',
//                ],
//            ]
//        );

//        $this->end_controls_tab();
//        $this->start_controls_tab( 'name_colors_tab_hover',
//            [
//                'label' => esc_html__('Hover', 'xstore-core')
//            ]
//        );

        $this->add_control(
            'name_color_hover',
            [
                'label' => esc_html__( 'Color (hover)', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} a:hover > .elementor-author-box__name' => 'color: {{VALUE}}',
                ],
//                'condition' => [
//                    'link_to!' => ''
//                ]
            ]
        );

//        $this->end_controls_tab();
//        $this->end_controls_tabs();

        $this->end_injection();

        $this->update_control('bio_color', [
            'default' => '#888'
        ]);

        $typography = get_theme_mod('headings', array());
        if ( isset($typography['font-family']) && !empty($typography['font-family'])) {
            $fonts_list = \Elementor\Fonts::get_fonts();
            if (array_key_exists($typography['font-family'], $fonts_list)) {
                $this->update_control('name_typography_typography', [
                    'default' => 'custom'
                ]);
                $this->update_control('name_typography_font_family', [
                    'default' => $typography['font-family']
                ]);
            }
        }

        $body_typography = get_theme_mod('sfont', array());
        if ( isset($body_typography['font-family']) && !empty($body_typography['font-family'])) {
            $fonts_list = \Elementor\Fonts::get_fonts();
            if (array_key_exists($body_typography['font-family'], $fonts_list)) {
                foreach (array('bio', 'button') as $s_font_option) {
                    $this->update_control($s_font_option.'_typography_typography', [
                        'default' => 'custom'
                    ]);
                    $this->update_control($s_font_option.'_typography_font_family', [
                        'default' => $body_typography['font-family']
                    ]);
                }
            }
        }

        $this->update_control('button_text_color', [
            'default' => $is_dark_mode ? '#ffffff' : '#222222',
            'selectors' => [
                '{{WRAPPER}} .elementor-author-box__button' => 'color: {{VALUE}};',
            ],
        ]);

        $this->update_control('button_hover_color', [
            'default' => !$is_dark_mode ? '#ffffff' : '#222222',
            'selectors' => [
                '{{WRAPPER}} .elementor-author-box__button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->update_control('button_background_color', [
            'default' => !$is_dark_mode ? '#ffffff' : '#222222'
        ]);

        $this->update_control('button_background_hover_color', [
            'default' => $is_dark_mode ? '#ffffff' : '#222222'
        ]);

        $this->remove_control('button_border_width');

        $this->remove_control('button_border_radius');

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'before',
            'of'   => 'button_text_padding',
        ] );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .elementor-author-box__button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_text_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-author-box__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'button_background_hover_color',
        ] );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-author-box__button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_injection();
    }
}
