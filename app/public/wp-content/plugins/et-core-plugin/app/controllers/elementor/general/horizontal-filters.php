<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Sidebar Horizontal widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Horizontal_Filters extends Sidebar {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'etheme_sidebar_horizontal';
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
        return __( 'Horizontal Filters', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-product-filter';
    }

    public function get_categories() {
        return ['eight_theme_general', 'theme-elements-archive', 'woocommerce-elements-archive'];
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {
        parent::register_controls();

        $this->update_control('widgets_separator', [
            'type' => \Elementor\Controls_Manager::HIDDEN
        ]);

        $this->remove_control('widgets_spacing');

        $this->update_control('section_off_canvas', [
            'type' => \Elementor\Controls_Manager::HIDDEN
        ]);

        $this->update_control('sidebar_off_canvas_on', [
            'type' => \Elementor\Controls_Manager::HIDDEN
        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('This widget can be used with %s widget as toggle for opening/closing filter area.'), esc_html__('Horizontal Filters Toggle', 'xstore-core')),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_general',
        ] );

        $this->add_control(
            'enabled_default',
            [
                'label' => __( 'Opened state', 'xstore-core' ),
                'description' => __('Make sidebar area shown by default.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'sidebar',
        ] );

        $this->add_responsive_control(
            'cols',
            [
                'label' => __( 'Columns', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'prefix_class' => 'elementor-grid%s-',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '4',
                'selectors' => [
                    '{{WRAPPER}}' => '--cols: {{VALUE}};',
                ],
                'condition' => [
                    'sidebar!' => ''
                ]
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'widgets_style',
        ] );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 20,
                ],
                'tablet_default' => [
                    'size' => 20,
                ],
                'mobile_default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'sidebar!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'rows_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 40,
                ],
                'tablet_default' => [
                    'size' => 40,
                ],
                'mobile_default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'sidebar!' => ''
                ]
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
    protected function render() {

        $sidebar = $this->get_settings_for_display( 'sidebar' );
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if ( !empty($sidebar) ) {
            $this->add_render_attribute( 'sidebar-wrapper', [
                'class' => [
                    'etheme-elementor-sidebar-horizontal',
                    'elementor-grid'
                ]
            ]);
            if ( !$edit_mode ) {
                if ( !!!$this->get_settings_for_display( 'enabled_default' ) ) {
                    $this->add_render_attribute('sidebar-wrapper', [
                        'class' => 'hidden',
                        'style' => 'display: none'
                    ]);
                }
                else {
                    $this->add_render_attribute('sidebar-wrapper', [
                        'class' => 'filters-opened',
                    ]);
                }
            }
        }
        parent::render();
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
