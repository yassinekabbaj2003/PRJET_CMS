<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Single_Post;

/**
 * Post content widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Post_Comments extends \Elementor\Widget_Base {
    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'theme-post-etheme_comments';
    }

    public function get_title() {
        return esc_html__( 'Post Comments', 'xstore-core' );
    }

    public function get_categories() {
        return [ 'theme-elements-single' ];
    }

    public function get_keywords() {
        return [ 'comments', 'post', 'response', 'form'  ];
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
        return 'eight_theme-elementor-icon et-elementor-product-content et-elementor-post-widget-icon-only';
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

    /**
     * Register widget controls.
     *
     * @since 5.4
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
            'design',
            [
                'label' => __( 'Design', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'xstore-core' ),
                    'quotes' => __( 'Quotes', 'xstore-core' ),
                ],
                'default' => '',
                'prefix_class'          => 'etheme-comments-design-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_comment',
            [
                'label' => esc_html__( 'Comment', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_avatars',
            [
                'label' => __( 'Show Avatars', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'author_badge',
            [
                'label' => __( 'Author Badge', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'comment_reply_selected_icon',
            [
                'label' => __( 'Reply Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'comment_reply_icon',
                'label_block' => false,
                'default' => [
                    'value' => 'et-icon et-compare-3',
                    'library' => 'xstore-icons',
                ],
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
            'replies_offset',
            [
                'label' => __( 'Replies offset', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comments-h-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => __( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comments-v-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_avatar',
            [
                'label' => __( 'Avatar', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_spacing',
            [
                'label' => __( 'Avatar space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comment-avatar-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comment-avatar-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_divider',
            [
                'label' => __( 'Divider', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'divider_weight',
            [
                'label' => __( 'Weight', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--comment-border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => __( 'Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'solid' => __( 'Solid', 'xstore-core' ),
                    'double' => __( 'Double', 'xstore-core' ),
                    'dotted' => __( 'Dotted', 'xstore-core' ),
                    'dashed' => __( 'Dashed', 'xstore-core' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}}' => '--comment-border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--comment-border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .media-heading',
            ]
        );

        $this->start_controls_tabs('title_colors_tabs');
        $this->start_controls_tab( 'title_colors_tab_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core')
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .media-heading a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab( 'title_colors_tab_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core')
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .media-heading a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->add_responsive_control(
            'title_space',
            [
                'label' => __( 'Bottom Space (px)', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_time_style',
            [
                'label' => __( 'Time', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'time_typography',
                'selector' => '{{WRAPPER}} time',
            ]
        );

        $this->add_control(
            'time_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} time' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'time_space',
            [
                'label' => __( 'Bottom Space (px)', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __( 'Content', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .media-body > p',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .media-body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_comment_reply_style',
            [
                'label' => __( 'Reply Link', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comment_reply_typography',
                'selector' => '{{WRAPPER}} .comment-reply-link',
            ]
        );

        $this->start_controls_tabs('comment_reply_colors_tabs');
        $this->start_controls_tab( 'comment_reply_colors_tab_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core')
            ]
        );

        $this->add_control(
            'comment_reply_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-reply-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab( 'comment_reply_colors_tab_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core')
            ]
        );

        $this->add_control(
            'comment_reply_color_hover',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-reply-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'comment_reply_space',
            [
                'label' => __( 'Top Space (px)', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-reply-link' => '--comment-reply-link-v-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_comment_respond_style',
            [
                'label' => __( 'Comment Respond', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'comment_respond_align',
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'text-align: start; justify-content: flex-start;',
                    'center' => 'text-align: center; justify-content: center;',
                    'right' => 'text-align: end; justify-content: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-respond' => '{{VALUE}};',
                    '{{WRAPPER}} #reply-title' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comment_respond_typography',
                'selector' => '{{WRAPPER}} .comment-respond',
            ]
        );

        $this->start_controls_tabs('comment_respond_colors');

        $this->start_controls_tab( 'comment_respond_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core')
            ]
        );

        $this->add_control(
            'comment_respond_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_respond_links_color',
            [
                'label' => __( 'Links Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'comment_respond_color_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core')
            ]
        );

        $this->add_control(
            'comment_respond_links_color_hover',
            [
                'label' => __( 'Link Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-respond a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'comment_respond_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .comment-respond',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'comment_respond_border',
                'label' => esc_html__('Items Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} .comment-respond',
            ]
        );

        $this->add_responsive_control(
            'comment_respond_paddings',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .comment-respond' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'comment_respond_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .comment-respond' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_respond_spacing',
            [
                'label' => __( 'Spacing before', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-respond' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function render() {
        if ( ! comments_open() && ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) :
            ?>
            <div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'elementor-pro' ); ?>
				</span>
                <span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'elementor-pro' ); ?>
				</span>
            </div>
        <?php
        else :
            $settings = $this->get_settings_for_display();
            add_filter('etheme_comment_avatar', (!!$settings['show_avatars'] ? '__return_true' : '__return_false'));
            add_filter('etheme_comment_author_badge', (!!$settings['author_badge'] ? '__return_true' : '__return_false'));
            add_filter('etheme_comment_reply_link_icon', array($this, 'modify_reply_link_icon'));
            comments_template();
            remove_filter('etheme_comment_reply_link_icon', array($this, 'modify_reply_link_icon'));
            remove_filter('etheme_comment_author_badge', (!!$settings['author_badge'] ? '__return_true' : '__return_false'));
            remove_filter('etheme_comment_avatar', (!!$settings['show_avatars'] ? '__return_true' : '__return_false'));
        endif;
    }

    public function modify_reply_link_icon() {
        $settings = $this->get_settings_for_display();
        ob_start();
        $this->render_icon($settings, 'comment_reply_');
        return ob_get_clean();
    }
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
