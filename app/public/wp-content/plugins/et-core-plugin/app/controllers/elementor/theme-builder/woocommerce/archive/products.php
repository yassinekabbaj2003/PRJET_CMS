<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

/**
 * Archive Products widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Products extends \ETC\App\Controllers\Elementor\General\Product_Grid {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-etheme_archive_products';
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
        return __( 'Archive Products', 'xstore-core' );
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
        return 'eight_theme-elementor-icon et-elementor-products-grid et-elementor-product-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'masonry', 'isotope' ]);
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 5.2
     * @access public
     *
     */
    public function get_categories()
    {
        return ['woocommerce-elements-archive'];
    }

	/**
	 * Help link.
	 *
	 * @since 5.2
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
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
            'at' => 'start',
            'of' => 'section_general',
        ] );

        $this->add_control(
            'type',
            [
                'label' 		=>	__( 'Type', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' 		=>	[
                    'grid' => esc_html__( 'Grid', 'xstore-core' ),
                    'list' => esc_html__( 'List', 'xstore-core' ),
                ],
                'default'	=> 'grid',
            ]
        );

        $this->end_injection();

        $this->update_control('equal_height_layout', [
            'condition' => [
                'type' => 'grid',
                'masonry' => ''
            ]
        ]);

        $this->update_control('masonry', [
            'type'         => \Elementor\Controls_Manager::SWITCHER,
        ]);

        $this->update_control('animated_items', [
            'default' => 'yes'
        ]);

        $this->update_control('query_type', [
            'type' 			=>	\Elementor\Controls_Manager::HIDDEN,
            'default' => 'current_query'
        ]);

//        $this->remove_control('current_query_note');

        $this->update_control('navigation', [
            'options' => [
                'button'		=>	esc_html__('Load More', 'xstore-core'),
                'scroll'	=>	esc_html__('Infinite Scroll', 'xstore-core'),
                'pagination'		=>	esc_html__('Pagination', 'xstore-core'),
            ],
            'default' => 'pagination'
        ]);

        $this->update_control('section_product_hover_settings', [
            'condition' => [
                'product_image!' => '',
                'type' => 'grid'
            ]
        ]);

        $this->update_control('hover_effect_overlay_color', [
            'condition' => [
                'product_image!' => '',
                'type' => 'grid',
                'hover_effect' => 'overlay'
            ]
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at' => 'after',
            'of' => 'alignment',
        ] );

        // hidden
        $this->add_control(
            'alignment_list',
            [
                'label' 		=>	__( 'Alignment List', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::HIDDEN,
//                'options' => [
//                    'left'    => [
//                        'title' => __( 'Left', 'xstore-core' ),
//                        'icon' => 'eicon-text-align-left',
//                    ],
//                    'center' => [
//                        'title' => __( 'Center', 'xstore-core' ),
//                        'icon' => 'eicon-text-align-center',
//                    ],
//                    'right' => [
//                        'title' => __( 'Right', 'xstore-core' ),
//                        'icon' => 'eicon-text-align-right',
//                    ],
//                ],
                'default' => (is_rtl() ? 'right' : 'left'),
                'selectors' => [
                    '{{WRAPPER}} .etheme-product-grid-item.type-list' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'type' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'vertical_alignment',
            [
                'label' => __( 'Vertical Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'xstore-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'xstore-core' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'xstore-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'middle',
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-product-grid-item.type-list .etheme-product-grid-content' => 'align-self: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'image_column_width',
            [
                'label' => __( 'Columns Proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'default' => [
                    'unit' => '%'
                ],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 70,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'type' => 'list',
                    'product_image!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-width-proportion: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();
    }

}
