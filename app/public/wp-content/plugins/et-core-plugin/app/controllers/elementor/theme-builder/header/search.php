<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

/**
 * Search widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Search extends \ETC\App\Controllers\Elementor\General\Search {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-'.parent::get_name();
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
		return __( 'Header Ajax Search', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-ajax-search et-elementor-header-builder-widget-icon-only';
	}

    /**
     * Get widget categories.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['theme-elements'];
    }

    protected function register_controls() {
        parent::register_controls();

        $this->update_control('ajax_search_results_heading_type', [
            'default' => 'headings'
        ]);

        $this->update_control('results_max_height', [
            'default' => [
                'unit' => 'px',
                'size' => 500
            ]
        ]);

        $this->update_control(
            'results_new_tab',
            [
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->update_control(
            'focus_overlay',
            [
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

    }

    public function render_animated_placeholder_options() {
        $this->start_controls_section(
            'section_search_animated_placeholder',
            [
                'label' => esc_html__( 'Animated Placeholder', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'animated_placeholder',
            [
                'label' => __( 'Animated Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'animated_placeholder_heading',
            [
                'label' => __( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Search for', 'xstore-core' ),
                'condition' => [
                    'animated_placeholder!' => '',
                ],
            ]
        );

        $this->add_control(
            'animated_placeholder_text',
            [
                'label' => __( 'Animated Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => __( 'Enter each word in a separate line', 'xstore-core' ),
                'default' => "footwear\naccessories\njewelry\nsmartphones\ncosmetics",
                'condition' => [
                    'animated_placeholder!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    public function render_trending_searches_options() {
        $this->start_controls_section(
            'section_search_trending_searches',
            [
                'label' => esc_html__( 'Trending Searches', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'trending_searches',
            [
                'label'     => esc_html__( 'Show Trending searches', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'trending_searches_list',
            [
                'label' => __( 'Trending Searches', 'xstore-core' ),
                'description' => esc_html__('Write your most popular search terms, separated by commas, to enable customers to search for results with one click.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'frontend_available' => true,
                'default' => 'Shirt, Shoes, Cap, Skirt, Dress, Pants, Jacket, Hat, Sweater, Jeans, Blouse, Coat, Scarf, Socks, Sandals, Handbag, T-shirt, Boots, Gloves, Backpack',
                'condition' => [
                    'trending_searches!' => ''
                ]
            ]
        );

        $this->add_control(
            'trending_searches_limit',
            [
                'label' => __( 'Limit', 'xstore-core' ),
                'description' => __( 'Limit searches to show the specific amount per view', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 5
                ],
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 30,
                        'step' => 1
                    ],
                ],
                'frontend_available' => true,
                'condition' => [
                    'trending_searches!' => ''
                ]
            ]
        );

        $this->end_controls_section();
    }

    // only if there are Search locations created for Search results page builder then we should redirect the customer
    // to the search results built page
    public function should_redirect_to_archive() {
        $created_templates = get_posts(
            [
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'tax_query' => [
                    [
                        'taxonomy' => 'elementor_library_type',
                        'field' => 'slug',
                        'terms' => 'search-results',
                    ],
                ],
                'meta_query'     => array(
                    array(
                        'key'     => '_elementor_conditions',
                        'value'   => 'include/archive/search',
                        'compare' => 'LIKE'
                    )
                ),
                'fields' => 'ids'
            ]
        );

        // originally we should display
        if ( count($created_templates) ) {
            $should_redirect_to_shop = false;
//            foreach ($created_templates as $created_template) {
//                if ( $should_redirect_to_shop ) break;
//                $should_redirect_to_shop = in_array('include/archive', (array)get_post_meta($created_template, '_elementor_conditions', true));
//            }
            return $should_redirect_to_shop;
        }
        return parent::should_redirect_to_archive();
    }
}
