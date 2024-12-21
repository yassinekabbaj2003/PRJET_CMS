<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder;

use ETC\App\Classes\Elementor;

/**
 * Post Sticky Navigation widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Post_Sticky_Navigation extends \Elementor\Widget_Base
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
        return 'etheme_post_sticky_navigation';
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
        return __('Posts Sticky Navigation', 'xstore-core');
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
        return 'eight_theme-elementor-icon et-elementor-posts-sticky-navigation et-elementor-post-widget-icon-only';
    }

    public function get_categories()
    {
        return ['theme-elements-single'];
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
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'xstore-core'),
            ]
        );

        $this->add_control(
            'description',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('The widget displays previous and next posts %s and currently does not have settings to configure.', 'xstore-core'),
                '<a href="https://prnt.sc/NxaO4ZgZ_5Ru" target="_blank" rel="nofollow">https://prnt.sc/NxaO4ZgZ_5Ru</a>'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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
        if (function_exists('etheme_project_links')) {
            $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            if ($edit_mode) {
                echo Elementor::elementor_frontend_alert_message(
                    sprintf(esc_html__('Placeholder for %s widget to quick find and edit from clicking here. Shown only in Elementor Editor.', 'xstore-core'),
                        '<strong>'.$this->get_title().'</strong>'), 'info '
                );
                add_filter('etheme_elementor_edit_mode', '__return_true');
                ?>
                <style>.elementor-element-<?php echo $this->get_id(); ?> .posts-navigation {
                        display: block
                    }</style>
                <?php
            }
            etheme_project_links();
        }
    }
}
