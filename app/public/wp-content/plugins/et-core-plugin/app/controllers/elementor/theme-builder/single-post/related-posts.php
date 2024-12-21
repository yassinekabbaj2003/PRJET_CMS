<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Single_Post;

/**
 * Related Posts widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Related_Posts extends \ETC\App\Controllers\Elementor\General\Posts {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'post-related_'.parent::get_name();
    }

    /**
     * Get widget title.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Related Posts', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-related-posts et-elementor-post-widget-icon-only';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.4
     * @access public
     *
     */
    public function get_categories() {
        return [ 'theme-elements-single' ];
    }

    /**
     * Get widget keywords.
     *
     * @since 5.4
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return array_merge(parent::get_categories(), [ 'related', 'post', 'query'  ]);
    }

    /**
     * Register widget controls.
     *
     * @since 5.4
     * @access protected
     */
    protected function register_controls() {
        parent::register_controls();

        $this->update_control('cols', [
            'default' => '4'
        ]);

        $this->update_control('related_posts_note', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('query_type', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default'	=> 'related_posts'
        ]);
    }

}
