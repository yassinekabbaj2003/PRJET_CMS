<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Sidebar Horizontal Toggle widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Horizontal_Filters_Toggle extends Text_Button {

    /**
     * Get widget name.
     *
     * @since 5.2
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'etheme_sidebar_horizontal_toggle';
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
        return __( 'Horizontal Filters Toggle', 'xstore-core' );
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

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.2
     * @access public
     *
     */
    public function get_categories() {
        return ['eight_theme_general', 'theme-elements-archive', 'woocommerce-elements-archive'];
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
        return [ 'etheme_elementor_horizontal_sidebar_toggle' ];
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {
        parent::register_controls();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'general_section',
        ] );

        $this->add_control(
            'description',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('This widget can be used with %s widget as toggle for opening/closing filter area.'), esc_html__('Horizontal Filters', 'xstore-core')),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ]
        );

        $this->end_injection();

        $this->update_control('button_animation', [
            'type' => \Elementor\Controls_Manager::HIDDEN
        ]);

        $this->update_control('text', [
            'default' => esc_html__('Filters', 'xstore-core'),
            'placeholder' => esc_html__('Filters', 'xstore-core'),
        ]);

        $this->remove_control('link');

        $this->update_control('selected_icon', [
            'default' => [
                'value' => 'et-icon et-controls',
                'library' => 'xstore-icons',
            ],
        ]);

        $this->update_control('icon_animation', [
            'default' => 'none',
        ]);
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {

//        $settings = $this->get_settings_for_display();
//        $this->add_render_attribute( 'text_button', [
//            'class' => 'etheme-horizontal-filters-toggle'
//        ]);
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
