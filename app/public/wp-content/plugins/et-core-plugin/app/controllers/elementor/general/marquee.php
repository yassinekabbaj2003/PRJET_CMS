<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Classes\Elementor;
/**
 * Marquee widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Marquee extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_marquee';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Marquee', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-best-offer';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'marquee', 'animation', 'carousel', 'slide', 'scroll' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.12
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
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
	    $scripts = [];
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            $scripts[] = 'etheme_waypoints';
            $scripts[] = 'etheme_marquee';
        }
	    return $scripts;
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
//	public function get_style_depends() {
//		return ['etheme-elementor-icon-box'];
//	}
	
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
	 * Register controls.
	 *
	 * @since 4.0.12
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
            'stretch_items',
            [
                'label' => __('Stretch items', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'   => __('Direction', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'ltr'     => __('Left to right', 'xstore-core'),
                    'rtl'   => __('Right to left', 'xstore-core'),
                ),
                'default' => 'rtl',
            ]
        );

        $this->add_control(
            'separator',
            [
                'label'   => __('Separator', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'none'     => __('None', 'xstore-core'),
                    'icon'   => __('Icon', 'xstore-core'),
                    'custom'   => __('Custom', 'xstore-core'),
                ),
                'default' => 'icon',
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
                'default' => [
                    'value' => 'fas fa-asterisk',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'separator' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'separator_custom',
            [
                'label' 		=>	__( 'Separator', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' => '*',
                'condition' => [
                    'separator' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'animation_type',
            [
                'label' => __('Animation type', 'xstore-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'auto'     => __('Autoplay', 'xstore-core'),
                    'scroll'   => __('Window scroll', 'xstore-core'),
                ),
                'frontend_available' => true,
                'default' => 'auto',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __('Pause on hover', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'animation_type' => 'auto',
                ],
            ]
        );

        $this->add_control(
            'slide_animation_duration',
            [
                'label' 		=>	__( 'Animation duration', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'    => [
                    '{{WRAPPER}} .etheme-marquee-wrapper' => '--animation-duration: {{SIZE}}s;',
                ],
                'condition' => [
                    'animation_type' => 'auto',
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'content',
            [
                'type'        => \Elementor\Controls_Manager::WYSIWYG,
                'label'       => __( 'Content', 'xstore-core' ),
                'default' => esc_html__('You can add any HTML here', 'xstore-core')
            ]
        );

        $this->add_control(
            'items',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'content' => __( 'Leo purus egestas venenatis vivamus sem mauris sed sit. Dignissim mattis non eget enim.', 'xstore-core' ),
                    ],
                    [
                        'content' => __( 'Leo purus egestas venenatis vivamus sem mauris sed sit. Dignissim mattis non eget enim.', 'xstore-core' ),
                    ],
                    [
                        'content' => __( 'Leo purus egestas venenatis vivamus sem mauris sed sit. Dignissim mattis non eget enim.', 'xstore-core' ),
                    ],
                ],
                'title_field' => '{{{ content }}}',
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

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => __( 'Gap', 'xstore-core' ),
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
                    '{{WRAPPER}} .etheme-marquee-wrapper' => '--cols-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'border',
                'label'     => esc_html__( 'Border', 'xstore-core' ),
                'selector'  => '{{WRAPPER}} .etheme-marquee-wrapper',
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => __( 'Item', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .etheme-marquee-wrapper',
            ]
        );

        $this->start_controls_tabs( 'tabs_items_style' );

        $this->start_controls_tab(
            'tab_item_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'item_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'item_hover_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item:hover, {{WRAPPER}} .etheme-marquee-item:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_hover_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item:hover, {{WRAPPER}} .etheme-marquee-item:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_border_color',
            [
                'label' => __( 'Border Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'item_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item:hover, {{WRAPPER}} .etheme-marquee-item:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .etheme-marquee-item',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-marquee-item',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-marquee-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

        if ( $settings['animation_type'] == 'scroll' ) {
            wp_enqueue_script('etheme_waypoints');
            wp_enqueue_script('etheme_marquee');
            $this->add_render_attribute( 'wrapper', [
                'class' => 'etheme-marquee-on-scroll',
                'data-dir' => $settings['direction']
            ]);
        }

        $this->add_render_attribute( 'main_wrapper', [
            'class' => [
                'etheme-marquee-wrapper',
            ]
        ]);

		// marquee
		$this->add_render_attribute( 'wrapper', [
			'class' => [
                'etheme-marquee',
                'etheme-marquee-pos-absolute'
            ]
		]);

		if ( !$settings['stretch_items'] ) {
            $this->add_render_attribute( 'wrapper', [
                'class' => 'etheme-marquee-fit-content',
            ]);
        }

        if ( $settings['direction'] == 'ltr' ) {
            $this->add_render_attribute( 'wrapper', [
                'class' => 'etheme-marquee-reverse',
            ]);
        }

        if ( $settings['pause_on_hover'] ) {
            $this->add_render_attribute( 'wrapper', [
                'class' => 'etheme-marquee-hover-pause',
            ]);
        }

		// inner
        $this->add_render_attribute( 'inner', [
            'class' => 'etheme-marquee-content',
        ]);

		// item
        $this->add_render_attribute( 'item', [
            'class' => 'etheme-marquee-item',
        ]);

        // separator
        $this->add_render_attribute( 'item_sep', [
            'class' => ['etheme-marquee-item', 'etheme-marquee-item_sep'],
        ]);
		
		?>
        <div <?php $this->print_render_attribute_string( 'main_wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                <?php if ( $settings['animation_type'] == 'scroll' ) { ?>
                    <div <?php $this->print_render_attribute_string( 'inner' ); ?> aria-hidden="true">
                        <?php
                        foreach ( $settings['items'] as $index => $item ) {
                            echo '<span ' . $this->get_render_attribute_string('item') . '>' . do_shortcode($item['content']) . '</span>';
                            if ( $settings['separator'] != 'none') {
                                if ( $settings['separator'] == 'custom' )
                                    echo !empty($settings['separator_custom']) ? '<span '. $this->get_render_attribute_string( 'item_sep' ) . '>' . $settings['separator_custom'] . '</span>' : '';
                                else
                                    $this->render_icon($settings);
                            }
                        }
                        ?>
                    </div>
                <?php } ?>

                <div <?php $this->print_render_attribute_string( 'inner' ); ?>>
                    <?php
                        foreach ( $settings['items'] as $index => $item ) {
                            echo '<span '. $this->get_render_attribute_string( 'item' ) . '>'. do_shortcode($item['content']) . '</span>';
                            if ( $settings['separator'] != 'none') {
                                if ( $settings['separator'] == 'custom' )
                                    echo !empty($settings['separator_custom']) ? '<span '. $this->get_render_attribute_string( 'item_sep' ) . '>' . $settings['separator_custom'] . '</span>' : '';
                                else {
                                    $this->render_icon($settings);
                                }
                            }
                        }
                    ?>
                </div>
                    <div <?php $this->print_render_attribute_string( 'inner' ); ?> aria-hidden="true">
                        <?php
                        foreach ( $settings['items'] as $index => $item ) {
                            echo '<span ' . $this->get_render_attribute_string('item') . '>' . do_shortcode($item['content']) . '</span>';
                            if ( $settings['separator'] != 'none') {
                                if ( $settings['separator'] == 'custom' )
                                    echo !empty($settings['separator_custom']) ? '<span '. $this->get_render_attribute_string( 'item_sep' ) . '>' . $settings['separator_custom'] . '</span>' : '';
                                else
                                    $this->render_icon($settings);
                            }
                        }
                        ?>
                    </div>
            </div>
        </div>
		<?php
		
	}

    protected function render_icon($settings) {
        $migrated = isset( $settings['__fa4_migrated']['separator_selected_icon'] );
        $is_new = empty( $settings['separator_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['separator_icon'] ) || ! empty( $settings['separator_selected_icon']['value'] ) ) :
            echo '<span '. $this->get_render_attribute_string( 'item_sep' ) . '>';
                if ( $is_new || $migrated ) :
                    \Elementor\Icons_Manager::render_icon( $settings['separator_selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['separator_icon'] ); ?>" aria-hidden="true"></i>
                <?php endif;
            echo '</span>';
        endif;
    }
	
}
