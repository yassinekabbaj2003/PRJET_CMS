<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Single_Post;

/**
 * Post info widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Post_Info extends \ElementorPro\Modules\ThemeElements\Widgets\Post_Info {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'theme-post-etheme_info';
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
        return 'eight_theme-elementor-icon et-elementor-additional-information et-elementor-post-widget-icon-only';
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

        $this->update_control('icon_list', [
            'default' => [
                [
                    'type' => 'author',
                    'show_icon' => 'custom',
                    'selected_icon' => [
                        'value' => 'et-icon et-user-2',
                        'library' => 'xstore-icons',
                    ],
                ],
                [
                    'type' => 'date',
                    'show_icon' => 'custom',
                    'selected_icon' => [
                        'value' => 'et-icon et-calendar',
                        'library' => 'xstore-icons',
                    ],
                ],
                [
                    'type' => 'time',
                    'show_icon' => 'custom',
                    'selected_icon' => [
                        'value' => 'et-icon et-time',
                        'library' => 'xstore-icons',
                    ],
                ],
                [
                    'type' => 'comments',
                    'show_icon' => 'custom',
                    'selected_icon' => [
                        'value' => 'et-icon et-chat',
                        'library' => 'xstore-icons',
                    ],
                ],
            ],
        ]);

        $is_dark_mode = get_theme_mod( 'dark_styles', false );

        $this->update_control('divider_color', [
            'default' => $is_dark_mode ? '#2f2f2f' : '#e1e1e1'
        ]);

        $this->update_control('icon_color', [
            'default' => '#888'
        ]);

        $this->update_control('icon_size', [
            'default' => [
                'size' => 0.8,
                'unit' => 'em'
            ],
            'range' => [
                'em' => [
                    'max' => 1,
                ],
                'rem' => [
                    'max' => 1,
                ],
            ],
        ]);

        $this->update_control('text_color', [
            'default' => $is_dark_mode ? '#ffffff' : '#222222'
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'text_color',
        ] );

        $this->add_control(
            'link_color_hover',
            [
                'label' => esc_html__( 'Link Hover Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text a:hover, {{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_injection();

    }
}
