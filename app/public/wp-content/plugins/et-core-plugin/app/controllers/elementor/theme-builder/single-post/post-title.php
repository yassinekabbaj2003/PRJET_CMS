<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Single_Post;

/**
 * Post title widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Post_Title extends \ElementorPro\Modules\ThemeBuilder\Widgets\Post_Title {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'theme-post-etheme_title';
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
        return 'eight_theme-elementor-icon et-elementor-archive-title et-elementor-post-widget-icon-only';
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
}
