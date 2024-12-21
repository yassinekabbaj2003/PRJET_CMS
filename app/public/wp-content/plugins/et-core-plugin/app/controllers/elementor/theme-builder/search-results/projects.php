<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Search_Results;

/**
 * Archive Posts widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Projects extends \ETC\App\Controllers\Elementor\General\Projects {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'search-results-'.parent::get_name();
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
        return __( 'Searched Projects', 'xstore-core' );
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
        return parent::get_icon() . ' et-elementor-search-results-widget-icon-only';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.4
     * @access public
     *
     */
    public function get_categories()
    {
        return ['theme-elements-archive'];
    }

    /**
     * Register widget controls.
     *
     * @since 5.4
     * @access protected
     */
    protected function register_controls() {
        parent::register_controls();

        $this->update_control('navigation', [
            'default' => 'pagination'
        ]);

        $this->update_control('section_query', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('query_type', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default'	=> 'search_query'
        ]);
    }

    // disable 'None' navigation option for Archive posts
    public function get_navigation_options_list() {
        $options = parent::get_navigation_options_list();
        if (array_key_exists( 'none', $options) )
            unset($options['none']);

        return $options;
    }

}
