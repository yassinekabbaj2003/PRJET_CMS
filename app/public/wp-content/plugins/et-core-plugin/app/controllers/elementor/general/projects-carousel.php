<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Projects Carousel widget.
 *
 * @since      5.4
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Projects_Carousel extends Posts_Carousel {

    /**
     * Get widget name.
     *
     * @since 5.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'etheme_projects_carousel';
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
        return __( 'Projects Carousel', 'xstore-core' );
    }

    /**
     * Get widget keywords.
     *
     * @since 4.1.3
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return array_merge(parent::get_keywords(), ['project', 'portfolio']);
    }

    public static function get_post_meta_data_elements() {
        $options = parent::get_post_meta_data_elements();
        if (array_key_exists( 'views', $options) )
            unset($options['views']);

        return $options;
    }

    /**
     * Return filtered product data sources
     *
     * @since 5.4
     *
     * @return mixed
     */
    public function get_data_source_list() {
        return apply_filters('etheme_projects_grid_list_post_data_source', array(
            'all' => esc_html__( 'All Projects', 'xstore-core' ),
            'posts_ids' => esc_html__( 'List of IDs', 'xstore-core' ),
            'categories' => esc_html__('By Categories', 'xstore-core'),
        ));
    }

    public function get_post_type_details() {
        return [
            'post_type' => 'etheme_portfolio',
            'post_type_name' => esc_html__('Project', 'xstore-core'),
            'post_type_names' => esc_html__('Projects', 'xstore-core'),
            'post_terms' => array(
                'category' => 'portfolio_category',
                'tag' => 'post_tag'
            ),
        ];
    }

    public function get_post_elements() {
        $options = parent::get_post_elements();
        if (array_key_exists( 'tags', $options) )
            unset($options['tags']);

        return $options;
    }

}
