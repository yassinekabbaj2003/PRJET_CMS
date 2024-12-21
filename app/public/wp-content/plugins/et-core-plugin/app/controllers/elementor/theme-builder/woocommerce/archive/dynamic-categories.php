<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

use ETC\App\Classes\Elementor;

/**
 * Dynamic Categories widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Dynamic_Categories extends \Elementor\Widget_Base {

    private static $is_archive_shop = null;
    private static $base_url;
    private static $shop_url;

    private static $page_url;

    private static $queried_object = null;
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-archive-etheme_dynamic_categories';
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
		return __( 'Spotlight Categories', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-product-categories';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'shop', 'store', 'categories', 'query', 'term', 'product', 'slider', 'carousel' ];
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
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_style_depends() {
        return [ 'etheme-elementor-dynamic-product-categories' ];
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
	    return ['etheme_elementor_slider'];
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
	 * Register controls.
	 *
	 * @since 5.2
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

        $this->add_control(
            'query_type',
            [
                'label' 		=>	__( 'Data Source', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'options' 		=>	self::get_data_source_list(),
                'default'	=> 'all'
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'      => esc_html__( 'Categories Limit', 'xstore-core' ),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'default' => 12,
//                'condition'  => [
//                    'query_type!' => [ 'product_ids' ],
//                ],
            ]
        );

//        $items_per_view = range( 1, 10 );
//        $items_per_view = array_combine( $items_per_view, $items_per_view );
//
//        $this->add_responsive_control(
//            'slides_per_view',
//            [
//                'type' => \Elementor\Controls_Manager::SELECT,
//                'label' => __( 'Slides Per View', 'xstore-core' ),
//                'options' => [ '' => __( 'Default', 'xstore-core' ) ] + $items_per_view,
//                'frontend_available' => true,
//                'default' => 6,
//                'render_type' => 'template',
//                'selectors' => [
//                    '{{WRAPPER}}' => '--slides-per-view: {{VALUE}};', // for init slides width
//                ],
//            ]
//        );
//
//        $this->add_responsive_control(
//            'space_between',
//            [
//                'label' => esc_html__( 'Space Between', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'default' => [
//                    'size' => 20
//                ],
//                'range' => [
//                    'px' => [
//                        'max' => 100,
//                        'min' => 0
//                    ],
//                ],
//                'frontend_available' => true,
//            ]
//        );
//
//        $this->add_control(
//            'free_mode',
//            [
//                'label' => esc_html__('Free mode', 'xstore-core'),
//                'type'  => \Elementor\Controls_Manager::HIDDEN,
//                'frontend_available' => true,
//                'default' => 'yes'
//            ]
//        );

        $this->add_control(
            'exclude_ids',
            [
                'label'       => __( 'Exclude Categories', 'xstore-core' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'description' => esc_html__( 'Enter categories.', 'xstore-core' ),
                'label_block' => 'true',
                'multiple'    => true,
                'options'     => Elementor::get_terms( 'product_cat' ),
                'condition' => [
                    'query_type' => 'excluded'
                ]
            ]
        );

        $this->add_control(
            'include_ids',
            [
                'label'       => __( 'Include Categories', 'xstore-core' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'description' => esc_html__( 'Enter categories.', 'xstore-core' ),
                'label_block' => 'true',
                'multiple'    => true,
                'options'     => Elementor::get_terms( 'product_cat' ),
                'condition' => [
                    'query_type' => 'included'
                ]
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order By', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'order',
                'options'   => array(
                    'order' => esc_html__( 'Category Order', 'xstore-core' ),
                    'name'  => esc_html__( 'Category Name', 'xstore-core' ),
                    'id'    => esc_html__( 'Category ID', 'xstore-core' ),
                    'count' => esc_html__( 'Product Counts', 'xstore-core' ),
                ),
//                'condition' => [
//                    'query_type!' => 'product_ids',
//                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'     => esc_html__( 'Sort Order', 'xstore-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'ASC',
                'options'   => array(
                    'DESC' => esc_html__( 'Descending', 'xstore-core' ),
                    'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
                ),
//                'condition' => [
//                    'query_type!' => 'product_ids',
//                ],
            ]
        );

        $this->add_control(
            'show_all',
            [
                'label'        => esc_html__( 'Show All', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'show_new',
            [
                'label'        => esc_html__( 'Show New', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'select_date_new_products',
            [
                'label' => __( 'Date', 'xstore-core' ),
                'description' => esc_html__('Select date range for getting count of new products', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'anytime' => __( 'All', 'xstore-core' ),
                    'today' => __( 'Past Day', 'xstore-core' ),
                    'week' => __( 'Past Week', 'xstore-core' ),
                    'month'  => __( 'Past Month', 'xstore-core' ),
                    'quarter' => __( 'Past Quarter', 'xstore-core' ),
                    'year' => __( 'Past Year', 'xstore-core' ),
                    'exact' => __( 'Custom', 'xstore-core' ),
                ],
                'default' => 'month',
                'condition' => [
                    'show_new!' => '',
                    'category_count!' => '',
                ],
            ]
        );

        $this->add_control(
            'date_before',
            [
                'label' => __( 'Before', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'multiple' => false,
                'placeholder' => __( 'Choose', 'xstore-core' ),
                'condition' => [
                    'show_new!' => '',
                    'category_count!' => '',
                    'select_date_new_products' => 'exact',
                ],
                'description' => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'xstore-core' ),
            ]);

        $this->add_control(
            'date_after',
            [
                'label' => __( 'After', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'placeholder' => __( 'Choose', 'xstore-core' ),
                'condition' => [
                    'show_new!' => '',
                    'category_count!' => '',
                    'select_date_new_products' => 'exact',
                ],
                'description' => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'xstore-core' ),
            ]);

        $this->add_control(
            'show_sale',
            [
                'label'        => esc_html__( 'Show Sale', 'xstore-core' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        if ( array_key_exists('count', self::get_category_elements()) ) {
            $count_title = self::get_category_elements()['count'];
            $this->add_control(
                'category_count',
                [
                    'label' => $count_title,
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                ]
            );

        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_settings',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'heading_html_wrapper_tag',
            [
                'label' => esc_html__('HTML tag', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_control(
            'heading_limit_type',
            [
                'label' => esc_html__('Limit By', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'chars' => esc_html__('Chars', 'xstore-core'),
                    'words' => esc_html__('Words', 'xstore-core'),
                    'lines' => esc_html__('Lines', 'xstore-core'),
                    'none' => esc_html__('None', 'xstore-core'),
                ],
                'default' => 'none',
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_control(
            'heading_limit',
            [
                'label' => esc_html__('Limit', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'condition' => [
                    'show_heading!' => '',
                    'heading_limit_type' => ['chars', 'words']
                ]
            ]
        );

        $this->add_control(
            'heading_lines_limit',
            [
                'label' => esc_html__('Lines Limit', 'xstore-core'),
                'description' => esc_html__('Line-height will not work with this option. Don\'t set it up in typography settings.', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 2,
                'condition' => [
                    'show_heading!' => '',
                    'heading_limit_type' => 'lines'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--etheme-heading-lines: {{VALUE}};',
                    '{{WRAPPER}} .etheme-category-grid-heading-title' => 'display: block; height: calc(var(--etheme-heading-lines) * 3ex); line-height: 3ex; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'return_to_previous',
            [
                'label' => esc_html__( '"Return to previous page" link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_settings',
            [
                'label' => esc_html__( 'Category', 'xstore-core' ),
            ]
        );

        $category_elements = self::get_category_elements();

        foreach ($category_elements as $key => $value) {

            if ( $key == 'count' ) continue; // because this option is added in section above

            $this->add_control(
                'category_' . $key,
                [
                    'label' => $value,
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => in_array($key, array('image', 'title')) ? 'yes' : ''
                ]
            );

            // injection of some options for specific keys
            switch ($key) {
                case 'image':
                    // make as filter for image
                    $this->add_group_control(
                        \Elementor\Group_Control_Image_Size::get_type(),
                        [
                            'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                            'default' => 'woocommerce_thumbnail',
                            'separator' => 'none',
                            'condition' => [
                                'category_image!' => ''
                            ]
                        ]
                    );

                    $this->add_control(
                        'img_size_custom',
                        [
                            'label' => esc_html__('Image Dimension', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
                            'description' => esc_html__('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'xstore-core'),
                            'condition' => [
                                'category_image!' => '',
                                'images_size' => 'custom',
                            ],
                        ]
                    );

                    $this->add_control(
                        'img_rounded',
                        [
                            'label' => esc_html__('Rounded', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'yes'
                        ]
                    );

                    $this->add_control(
                        'img_size_divider',
                        [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                            'condition' => [
                                'category_image!' => ''
                            ]
                        ]
                    );
                    break;
                case 'title':
                case 'excerpt':
                    if ($key == 'title') {
                        $this->add_control(
                            'category_' . $key . '_tag',
                            [
                                'label' => esc_html__('HTML tag', 'xstore-core'),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'h1' => 'H1',
                                    'h2' => 'H2',
                                    'h3' => 'H3',
                                    'h4' => 'H4',
                                    'h5' => 'H5',
                                    'h6' => 'H6',
                                    'div' => 'div',
                                    'span' => 'span',
                                    'p' => 'p',
                                ],
                                'default' => 'h2',
                                'condition' => [
                                    'category_' . $key . '!' => ''
                                ]
                            ]
                        );
                    }
                    $this->add_control(
                        'category_' . $key . '_limit_type',
                        [
                            'label' => esc_html__('Limit By', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'chars' => esc_html__('Chars', 'xstore-core'),
                                'words' => esc_html__('Words', 'xstore-core'),
                                'lines' => esc_html__('Lines', 'xstore-core'),
                                'none' => esc_html__('None', 'xstore-core'),
                            ],
                            'default' => 'none',
                            'condition' => [
                                'category_' . $key . '!' => ''
                            ]
                        ]
                    );

                    $this->add_control(
                        'category_' . $key . '_limit',
                        [
                            'label' => esc_html__('Limit', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                            'condition' => [
                                'category_' . $key . '!' => '',
                                'category_' . $key . '_limit_type' => ['chars', 'words']
                            ]
                        ]
                    );

                    $selector = '{{WRAPPER}} .etheme-category-grid-content .etheme-category-grid-title a';
                    if ($key == 'excerpt')
                        $selector = '{{WRAPPER}} .etheme-category-grid-content .woocommerce-category-details__short-description';

                    $this->add_control(
                        'category_' . $key . '_lines_limit',
                        [
                            'label' => esc_html__('Lines Limit', 'xstore-core'),
                            'description' => esc_html__('Line-height will not work with this option. Don\'t set it up in typography settings.', 'xstore-core'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'max' => 20,
                            'step' => 1,
                            'default' => 2,
                            'condition' => [
                                'category_' . $key . '!' => '',
                                'category_' . $key . '_limit_type' => 'lines'
                            ],
                            'selectors' => [
                                '{{WRAPPER}}' => '--category-' . $key . '-lines: {{VALUE}};',
                                $selector => 'display: block; height: calc(var(--category-' . $key . '-lines) * 3ex); line-height: 3ex; overflow: hidden;',
                            ],
                        ]
                    );

//                    $this->add_control(
//                        'category_' . $key . '_divider',
//                        [
//                            'type' => \Elementor\Controls_Manager::DIVIDER,
//                            'condition' => [
//                                'category_' . $key . '!' => '',
//                            ]
//                        ]
//                    );
                    break;
            }

        }

        $this->end_controls_section();

        // before added only few same options from Slider so no need to create separated section yet
        // slider global settings
        Elementor::get_slider_general_settings($this);

        $this->update_control( 'section_slider', [
            'label' => esc_html__('Carousel', 'xstore-core'),
        ] );

        $this->update_control( 'section_slider_navigation', [
            'label' => esc_html__('Carousel Navigation', 'xstore-core'),
        ] );

        $this->update_control( 'slides_per_view', [
            'default' => 6,
            'tablet_default' => 3,
            'mobile_default' => 2,
            'description' => esc_html__('You have additional options to set correct image proportions in Style section -> Image tab', 'xstore-core')
        ] );

        $slider_options_2_remove = array(
                'slider_vertical_align',
                'loop',
                'autoheight',
        );

        foreach ($slider_options_2_remove as $slider_option_2_remove) {
            $this->remove_control($slider_option_2_remove);
        }

        $this->update_control( 'navigation', [
            'default' => 'none'
        ]);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'space_between',
        ] );

        $this->add_control(
            'free_mode',
            [
                'label' => esc_html__('Free mode', 'xstore-core'),
                'type'  => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->end_injection();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__( 'General', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .etheme-dynamic-categories-wrapper',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => get_theme_mod('dark_styles', false) ? '#1e1e1e' : '#f7f7f7'
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'vw', 'vh' ],
                'default' => [
                    'unit' => 'vw',
                    'top' => '3',
                    'right' => '0',
                    'bottom' => '3',
                    'left' => '0',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-dynamic-categories-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_heading!' => ''
                ]
            ]
        );

        $this->add_responsive_control(
            'heading_align',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'xstore-core' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-heading-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .etheme-category-grid-heading-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .etheme-category-grid-heading-title',
            ]
        );

        $this->add_control(
            'heading_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
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
                    '{{WRAPPER}} .etheme-category-grid-heading-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_return_to_previous_style',
            [
                'label' => esc_html__( 'Return to previous page', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'return_to_previous!' => ''
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'return_to_previous_typography',
                'selector' => '{{WRAPPER}} .return-to-previous',
            ]
        );

        $this->start_controls_tabs('tabs_return_to_previous_colors');

        $this->start_controls_tab(
            'tab_return_to_previous_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core'),
            ]
        );

        $this->add_control(
            'return_to_previous_color',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .return-to-previous' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_return_to_previous_color_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'return_to_previous_color_hover',
            [
                'label' => esc_html__( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .return-to-previous:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'return_to_previous_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
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
                    '{{WRAPPER}} .return-to-previous' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_style',
            [
                'label' => __( 'Category', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' 		=>	__( 'Alignment', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-item' => 'text-align: {{VALUE}};',
                ]
            ]
        );

//		$this->add_control(
//			'image_column_width',
//			[
//				'label' => __( 'Columns Proportion', 'xstore-core' ),
//				'type' => \Elementor\Controls_Manager::SLIDER,
//				'size_units' => [ '%', 'px' ],
//				'default' => [
//                    'unit' => '%'
//                ],
//				'range' => [
//					'%' => [
//						'min' => 10,
//						'max' => 70,
//						'step' => 1,
//					],
//					'px' => [
//						'min' => 10,
//						'max' => 100,
//						'step' => 1,
//					],
//				],
//				'condition' => [
//                    'category_image!' => ''
//                ],
//				'selectors' => [
//					'{{WRAPPER}}' => '--image-width-proportion: {{SIZE}}{{UNIT}};',
//				],
//			]
//		);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .etheme-category-grid-item'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'xstore-core'),
                'selector' => '{{WRAPPER}} .etheme-category-grid-item',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .etheme-category-grid-item',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Padding', 'xstore-core'),
                'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // image
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => __( 'Image', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'category_image!' => ''
                ],
            ]
        );

        $this->add_responsive_control(
            'image_proportion',
            [
                'label' => __( 'Width/Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-proportion: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_scale',
            [
                'label' => __( 'Image Scale', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-scale: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position_x',
            [
                'label' => __( 'Image Position X', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-position-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position_y',
            [
                'label' => __( 'Image Position Y', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-position-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .etheme-category-grid-image img',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .etheme-category-grid-image-ghost.rounded, {{WRAPPER}} .etheme-category-grid-image:not(.etheme-category-grid-image-ghost).rounded img',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_space',
            [
                'label' => __( 'Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 70,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--image-space: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'category_title!' => ''
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .etheme-category-grid-title',
            ]
        );

        $this->start_controls_tabs('tabs_title_colors');

        $this->start_controls_tab( 'tabs_title_color_normal',
            [
                'label' => esc_html__('Normal', 'xstore-core')
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'tabs_title_color_hover',
            [
                'label' => esc_html__('Hover', 'xstore-core')
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'title_space',
            [
                'label' => __( 'Bottom Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // title
        $this->start_controls_section(
            'section_count_style',
            [
                'label' => __( 'Count', 'xstore-core' ),
                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'category_count!' => ''
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .etheme-category-grid-count',
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-category-grid-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // slider style settings
        Elementor::get_slider_style_settings($this);
        $this->update_control( 'section_style_slider', [
            'label' => esc_html__('Carousel', 'xstore-core'),
        ] );
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 5.2
	 * @access protected
	 */
	public function render()
    {
        $settings = $this->get_settings_for_display();
        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || is_preview();
        $swiper_latest = \Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest');

        self::$shop_url = wc_get_page_permalink( 'shop' );
        self::$page_url = false;
        $heading_title = esc_html__('All categories', 'xstore-core');
        $heading_link = false;
        $return_link = !!$edit_mode;
        $query_args = array(
            'edit_mode' => $edit_mode,
            'all_categories' => $edit_mode
        );
        if ( !$edit_mode ) {
            if ( !$this->is_archive_shop() ) {
                if (null == self::$queried_object)
                    self::$queried_object = get_queried_object();

                if (self::$queried_object) {
                    if (!empty (self::$queried_object->term_id)) {
                        $term_link = get_term_link(self::$queried_object->term_id, 'product_cat');
                        if ($term_link && !is_wp_error($term_link)) {
                            $heading_title = self::$queried_object->name;
                            $heading_link = $term_link;
                            $return_link = true;
                        }
                    } else {
                        $query_args['all_categories'] = true; // to include all categories
                    }
                }
            }
            else {
                if ( function_exists('etheme_get_current_page_url') )
                    self::$page_url = etheme_get_current_page_url();
            }
        }

        $this->add_render_attribute('main_wrapper', [
            'class' => 'etheme-dynamic-categories-wrapper'
        ]);

        if ($swiper_latest && in_array($settings['arrows_position'], array('middle', 'middle-inside')))
            $settings['arrows_position'] = 'middle-inbox';

        $this->add_render_attribute('wrapper', [
            'class' => [
                'etheme-elementor-swiper-entry',
                'swiper-entry',
                $settings['arrows_position'],
                $settings['arrows_position_style']
            ]
        ]);


        $this->add_render_attribute('wrapper-inner',
            [
                'class' =>
                    [
                        $swiper_latest ? 'swiper' : 'swiper-container',
                        'etheme-elementor-slider',
                    ],
                'dir' => is_rtl() ? 'rtl' : 'ltr'
            ]
        );

        $this->add_render_attribute('slides-wrapper', 'class', 'swiper-wrapper');

        $categories = self::get_query( $settings, $query_args );

        global $local_settings;
        $local_settings = $settings;

        $this->add_render_attribute( 'heading_wrapper', 'class', 'etheme-category-grid-heading-wrapper' );

        ?>

        <div <?php $this->print_render_attribute_string( 'main_wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'heading_wrapper' ); ?>>
                <?php

                if (!!$settings['show_heading']) {

                    $this->add_render_attribute( 'heading', 'class', 'etheme-category-grid-heading-title' );

                    if ( $local_settings['heading_limit_type'] != 'none' )
                        $heading_title = $this->limit_heading_string($heading_title);

                    if ( $heading_link ) {
                        $heading_title = sprintf( '<a %1$s>%2$s</a>', 'href="'.$heading_link.'"', $heading_title );
                    }
                    echo sprintf('<%1$s %2$s>%3$s</%1$s>', \Elementor\Utils::validate_html_tag($settings['heading_html_wrapper_tag']), $this->get_render_attribute_string('heading'), $heading_title);
                }

                if ( $settings['return_to_previous'] && $return_link ) {
                    $is_rtl = is_rtl();
                    $return_icon = $is_rtl ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="0.85em" height="0.85em" viewBox="0 0 32 32" fill="currentColor">
    <path d="M23.84 15.328l-14.304-15.072c-0.352-0.352-0.896-0.352-1.28-0.032-0.192 0.16-0.288 0.416-0.288 0.672 0 0.224 0.096 0.448 0.256 0.64l13.696 14.4-13.696 14.4c-0.16 0.16-0.256 0.416-0.256 0.672s0.096 0.48 0.256 0.672c0.16 0.192 0.416 0.288 0.64 0.288 0.192 0 0.416-0.064 0.608-0.256l0.032-0.032 14.336-15.104c0.352-0.352 0.32-0.896 0-1.248z"></path>
    </svg>' : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="0.85em" height="0.85em" viewBox="0 0 32 32" fill="currentColor">
                        <path d="M23.968 30.4l-13.92-14.4 13.952-14.464c0.32-0.384 0.32-0.96-0.032-1.28-0.32-0.32-0.96-0.352-1.312 0l-14.56 15.104c-0.352 0.352-0.352 0.896 0 1.312l14.56 15.040c0.192 0.192 0.416 0.288 0.672 0.288 0.224 0 0.448-0.096 0.608-0.256 0.192-0.16 0.288-0.384 0.32-0.64 0-0.288-0.096-0.512-0.288-0.704z"></path>
                    </svg>';
                    $return_icon = '<span class="et_b-icon">'.$return_icon.'</span>';
                    echo '<a class="return-to-previous" href="javascript: history.go(-1)">' . ( !$is_rtl ? $return_icon : '' ) . '<span>'.esc_html__( 'Return to previous page', 'xstore-core' ).'</span>' . ( $is_rtl ? $return_icon : '' ) . '</a>';
                }

                ?>
            </div>

            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
                <div <?php $this->print_render_attribute_string( 'slides-wrapper' ); ?>>
                    <?php

                    if( $settings['show_all'] ) {
                        echo '<div class="swiper-slide">';
                        $category_args = [
                            'url' => self::$shop_url,
                            'count' => count($this->count_all_products($settings, $edit_mode)),
                            'is_active' => (!isset($_GET['orderby']) && !isset($_GET['sale_status']) && is_shop())
                        ];
                        $this->get_content_virtual_category('all', $settings, $category_args);
                        echo '</div>';
                    }

                    if( $settings['show_new'] ) {
                        echo '<div class="swiper-slide">';
                            $category_args = [
                                'title' => esc_html__('New Arrivals', 'xstore-core'),
                                'image_title' => esc_html__('New', 'xstore-core'),
                                'count' => count($this->count_new_products($settings, $edit_mode)),
                                'url' => add_query_arg('orderby', 'date', self::$shop_url ),
                                'is_active' => isset($_GET['orderby'])
                            ];
                            $this->get_content_virtual_category('new', $settings, $category_args);
                        echo '</div>';
                    }

                    if( $settings['show_sale'] ) {
                        echo '<div class="swiper-slide">';
                            $category_args = [
                                'title' => esc_html__('Sale', 'xstore-core'),
                                'image_title' => esc_html__('Sale', 'xstore-core'),
                                'count' => $this->count_on_sale_products($settings, $edit_mode),
                                'url' => add_query_arg('sale_status', '1', self::$shop_url ),
                                'is_active' => isset($_GET['sale_status'])
                            ];
                            $this->get_content_virtual_category('sale', $settings, $category_args);
                        echo '</div>';
                    }

                    foreach ($categories as $category) {
                        echo '<div class="swiper-slide">';
                            $this->get_content_category($category, $settings);
                        echo '</div>';
                    }
                    ?>
                </div>

                <?php
                    if ( $swiper_latest ) {
                        if (in_array($settings['navigation'], array('both', 'arrows')))
                            Elementor::get_slider_navigation($settings, $edit_mode);
                    }
                    //                                    if ( 1 < count($categories) ) {
                    if ( in_array($settings['navigation'], array('both', 'dots')) ) {
                        Elementor::get_slider_pagination($this, $settings, $edit_mode);
                    }

                    //                                    }
                    ?>
            </div>
            <?php
                if ( !$swiper_latest ) {
                    if (in_array($settings['navigation'], array('both', 'arrows')))
                        Elementor::get_slider_navigation($settings, $edit_mode);
                }
                ?>
        </div>

        </div>
		<?php

	}

    public static function is_archive_shop() {
        if ( null != self::$is_archive_shop )
            return self::$is_archive_shop;

        self::$is_archive_shop = is_shop() && ! is_product_taxonomy() && ! is_product_category() && ! is_product_tag() && ! is_tax('brand');
        return self::$is_archive_shop;
    }
    /**
     * Get query for render categories.
     *
     * @param $settings
     * @return \WP_Query
     *
     * @since 5.2
     *
     */
    public static function get_query($settings, $extra_params = array()) {
        $query_args = array(
           'orderby' => $settings['orderby'],
            'order' => $settings['order']
        ); // WPCS: slow query ok.

        if ( $query_args['orderby'] == 'order' ) {
            $query_args['menu_order'] = 'asc';
        } elseif ( $query_args['orderby'] == 'count' ) {
            $query_args['order'] = 'desc';
        }

        if( ! empty ( $settings['limit'] ) && $settings['limit'] !== '0' ) {
            $query_args['number'] = $settings['limit'];
        }

        if ( null == self::$queried_object )
            self::$queried_object = get_queried_object();

        $source = $settings['query_type'];

        if( $extra_params['edit_mode'] || $extra_params['all_categories'] || ($source == 'included') || !is_object(self::$queried_object) || self::is_archive_shop() ) {
            $query_args['taxonomy'] = 'product_cat';
            $query_args['parent'] = 0;;
            self::$base_url = self::$shop_url;
        }
        else {
            $queried = self::$queried_object;
            $current_term   = ! empty ( $queried->term_id ) ? $queried->term_id : '';
            self::$base_url = get_term_link( $current_term, 'product_cat' );
            $termchildren  = (array)get_term_children( $queried->term_id, $queried->taxonomy );

            $query_args['taxonomy'] = $queried->taxonomy;

            if( ! empty( $termchildren ) ) {
                $query_args['parent'] = $queried->term_id;

                if( count( $termchildren ) == 1 ) {
                    $term = get_term_by( 'id', $termchildren[0], $queried->taxonomy );

                    if( $term->count == 0 ) {
                        $query_args['parent'] = $queried->parent;
                    }
                }

            } else {
                $query_args['parent'] = $queried->parent;
            }
        }

        switch ($source) {
            case 'excluded':
                if ($settings['exclude_ids'] && count($settings['exclude_ids'])) {
                    $query_args['exclude'] = $settings['exclude_ids'];
                }
                break;
            case 'included':
                if ($settings['include_ids'] && count($settings['include_ids'])) {
                    $query_args['include'] = $settings['include_ids'];
                }
                break;
        }

        $terms = get_terms( $query_args );

        return ( is_wp_error( $terms ) || ! $terms ) ? array() : $terms;
    }

    public function get_content_category($category, $settings) {
        global $local_settings;
        $local_settings = $settings;

        add_filter('etheme_static_block_prevent_setup_post', '__return_true');

        $classes = array(
            'etheme-category-grid-item'
        );

        if ( $local_settings['type'] == 'list' ) {
            $classes[] = 'type-list';
        }

        if ( self::$queried_object ) {
            if ( !empty( self::$queried_object->term_id ) && self::$queried_object->term_id == $category->term_id ) {
                $classes[] = 'etheme-category-grid-item-current';
            }
        }

        $url_class = $this->generate_category_url_class(self::$page_url, $category);

        ?>

        <div <?php wc_product_cat_class( $classes, $category ); ?>>
            <?php
                $local_content = array();
                foreach (self::get_category_elements() as $key => $string_text) {
                    if ( !isset($local_settings['category_'.$key]) || !$local_settings['category_'.$key]) continue;
                    switch ($key) {
                        case 'image':
                            ob_start();
                            // filter image size
                            if ( $local_settings['image_size'] != 'custom') {
                                add_filter('subcategory_archive_thumbnail_size', array($this, 'image_prerendered_size_filter'), 10);
                                woocommerce_subcategory_thumbnail($category);
                                remove_filter('subcategory_archive_thumbnail_size', array($this, 'image_prerendered_size_filter'), 10);
                            }
                            else {
                                echo $this->get_category_thumbnail($category);
                            }
                            $local_content[$key] = ob_get_clean();
                            break;
                        case 'title':
                            ob_start();
                                $this->get_category_title($category->name, $category, (!empty($url_class['class']) ? $url_class['url'] : false), (!empty($url_class['class']) ? $url_class['class'] : false));
                            $local_content[$key] = ob_get_clean();
                            break;
                        case 'count':
                            ob_start();
                                $this->get_category_count($category->count, $category);
                            $local_content[$key] = ob_get_clean();
                            break;
                    }
                }

        if ( isset($local_content['image'])) {
            echo '<div class="etheme-category-grid-image'. (!!$local_settings['img_rounded'] ? ' rounded' : '') .'">';
                echo '<a href="' . esc_url( $url_class['url'] ) . '" class="'.$url_class['class'].'">';
                    echo $local_content['image'];
                echo '</a>';
            echo '</div>';
        }

        $list_content = $local_content;
        unset($list_content['image']);

        if ( count($list_content) ) {
            echo '<div class="etheme-category-grid-content">' .
                implode('', $list_content) .
                '</div>';
        }
        ?>
        </div>
        <?php

        remove_filter('etheme_static_block_prevent_setup_post', '__return_true');
    }

    public function generate_category_url_class($page_url, $category) {
        $category_class = '';
        if ( !$page_url || !get_theme_mod('ajax_categories', 1) || is_tax('dc_vendor_shop') || (function_exists('wcfm_is_store_page') && wcfm_is_store_page()) || ( function_exists('wcfmmp_is_stores_list_page') && wcfmmp_is_stores_list_page()) ) {
            $category_url = get_term_link($category, 'product_cat');
            if ( !apply_filters('etheme_dynamic_categories_filter_cat_merge', true) && get_theme_mod('ajax_categories', 1) )
                $category_class = 'etheme-category-ajax';
        }
        else {
            $link = remove_query_arg( 'filter_cat', $page_url );

            $current_filter = isset( $_GET['filter_cat'] ) ? explode( ',', wc_clean( wp_unslash( $_GET['filter_cat'] ) ) ) : array();
            $current_filter = array_map( 'sanitize_title', $current_filter );

            $all_filters = $current_filter;

            if ( ! in_array( $category->slug, $current_filter, true ) ) {
                $all_filters[] = $category->slug;
            } else {
                $key = array_search( $category->slug, $all_filters );
                unset( $all_filters[ $key ] );
            }

            $ajax_filters_link = false;
            if ( ! empty( $all_filters ) ) {
                if ( apply_filters('etheme_dynamic_categories_filter_cat_merge', true) ) {
                    $link = add_query_arg('filter_cat', implode(',', $all_filters), $link);
                    $ajax_filters_link = true;
                }
            }
            if ( !$ajax_filters_link )
                $link = get_term_link( $category, 'product_cat' );

            $category_url = $link;
            $category_class = 'etheme-category-ajax';
        }
        return [
            'url' => $category_url,
            'class' => $category_class
        ];
    }

    public function get_content_virtual_category($type, $settings, $category_args = array()) {
        global $local_settings;
        $local_settings = $settings;

        $category_args = shortcode_atts(
            array(
                'title' => esc_html__('Shop All', 'xstore-core'),
                'image_title' => esc_html__('All', 'xstore-core'),
                'count' => false,
                'url' => false,
                'is_active' => false
            ), $category_args);

        $classes = array(
            'etheme-category-grid-item'
        );

        if ( $local_settings['type'] == 'list' ) {
            $classes[] = 'type-list';
        }

        if ( $category_args['is_active'] ) {
            $classes[] = 'is-active';
            $classes[] = 'etheme-category-grid-item-current';
        }

        ?>

        <div <?php wc_product_cat_class( $classes, null ); ?>>
            <?php
            $local_content = array();
            foreach (self::get_category_elements() as $key => $string_text) {
                if ( !isset($local_settings['category_'.$key]) || !$local_settings['category_'.$key]) continue;
                switch ($key) {
                    case 'image':
                        ob_start();
                        // filter image size
                        echo wc_placeholder_img($local_settings['image_size'] != 'custom' ? $local_settings['image_size'] : apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' ) );
                        $local_content[$key] = ob_get_clean();
                        break;
                    case 'title':
                        ob_start();
                        $this->get_category_title($category_args['title'], false, $category_args['url']);
                        $local_content[$key] = ob_get_clean();
                        break;
                    case 'count':
                        if ( isset($category_args['count']) ) {
                            ob_start();
                            $this->get_category_count($category_args['count']);
                            $local_content[$key] = ob_get_clean();
                        }
                        else {
                            $local_content[$key] = '';
                        }
                        break;
                }
            }

            if ( isset($local_content['image'])) {
                echo '<div class="etheme-category-grid-image etheme-category-grid-image-ghost'. (!!$local_settings['img_rounded'] ? ' rounded' : '') .'" data-type="'.$type.'">';
                    echo '<a href="' . esc_url( $category_args['url'] ) . '">';
                        echo $local_content['image'];
                        echo '<span>'.$category_args['image_title'].'</span>';
                    echo '</a>';
                echo '</div>';
            }

            $list_content = $local_content;
            unset($list_content['image']);

            if ( count($list_content) ) {
                echo '<div class="etheme-category-grid-content">' .
                    implode('', $list_content) .
                    '</div>';
            }
            ?>
        </div>
        <?php
    }
    public function get_category_count($count, $category = false) {
//        if ( $count > 0 ) {
            ?>
            <div class="etheme-category-grid-count woocommerce-loop-category__count">
            <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo apply_filters('woocommerce_subcategory_count_html', ' <span class="count">' . sprintf(_n('%s product', '%s products', $count, 'xstore-core'), $count) . '</span>', $category);
            ?>
            </div>
            <?php
//        }
    }
    public function get_category_title($title, $category = false, $custom_url = false, $custom_class = false) {
        global $local_settings;
        $html_tag = $local_settings['category_title_tag'];

        if ( $local_settings['category_title_limit_type'] != 'none' )
            $title = $this->limit_title_string($title);

        ?>
        <<?php echo $html_tag; ?> class="etheme-category-grid-title woocommerce-loop-category__title">
            <?php
                if ( $category && !$custom_url )
                    woocommerce_template_loop_category_link_open($category);
                else {
                    /* translators: %s: Category name */
                    echo '<a aria-label="' . sprintf(esc_attr__('Visit product category %1$s', 'xstore-core'), esc_attr($title)) . '" class="'.($custom_class?$custom_class:'').'" href="' . ($custom_url ? esc_url($custom_url) : esc_url(self::$shop_url)) . '">';
                }
                        echo '<span>'.esc_html( $title ).'</span>';
                if ( $category && !$custom_url )
                    woocommerce_template_loop_category_link_close();
                else
                    echo '</a>';
            ?>
        </<?php echo $html_tag; ?>>
        <?php
    }
    public function get_category_thumbnail($category) {
//	    $settings = $this->get_settings_for_display();
        global $local_settings;
        $small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
        $product_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );

        if ( $product_id ) {
            $custom_size = $local_settings['image_custom_dimension'];
            $image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
                array(
                    'image' => array(
                        'id' => $product_id,
                    ),
                    'image_custom_dimension' =>
                        array(
                            'width' => $custom_size['width'],
                            'height' => $custom_size['width']
                        ),
                    'image_size' => 'custom',
                )
            );
            $image = str_replace(
                '<img ',
                sprintf('<img width="%1s" height="%2s"',
                    $custom_size['width'],
                    $custom_size['height']
                ),
                $image
            );
        }
        else
            $image = wc_placeholder_img( $small_thumbnail_size );

        return $image;
    }

    public function count_all_products($settings, $edit_mode)
    {
        if ( !$settings['category_count'] ) return [];
        $ids = !$edit_mode ? get_transient('etheme_get_publish_products_ids', false) : false;
        if ($ids === false) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids'
            );
            $ids = get_posts($args);
            set_transient('etheme_get_publish_products_ids', $ids, DAY_IN_SECONDS);
        }
        return $ids;
    }

    public function count_new_products($settings, $edit_mode) {
        if ( !$settings['category_count'] ) return [];
        $select_date_new_products = $settings['select_date_new_products'];
        if ( ! empty( $select_date_new_products ) && $select_date_new_products != 'anytime' ) {
            $date_query = [];
            switch ($select_date_new_products) {
                case 'today':
                    $date_query['after'] = '-1 day';
                    break;
                case 'week':
                    $date_query['after'] = '-1 week';
                    break;
                case 'month':
                    $date_query['after'] = '-1 month';
                    break;
                case 'quarter':
                    $date_query['after'] = '-3 month';
                    break;
                case 'year':
                    $date_query['after'] = '-1 year';
                    break;
                case 'exact':
                    $after_date = $settings['date_after'];
                    if (!empty($after_date)) {
                        $date_query['after'] = $after_date;
                    }
                    $before_date = $settings['date_before'];
                    if (!empty($before_date)) {
                        $date_query['before'] = $before_date;
                    }
                    $date_query['inclusive'] = true;
                    break;
            }
            $transient_key = sanitize_text_field(json_encode($date_query));
            $ids = !$edit_mode ? get_transient('etheme_get_new_products_ids_'.$transient_key, false) : false;
            if ($ids === false) {
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'fields' => 'ids'
                );
                $args['date_query'] = $date_query;
                $ids = get_posts($args);

                set_transient('etheme_get_new_products_ids_'.$transient_key, $ids, DAY_IN_SECONDS);
            }
        }
        else {
            $ids = $this->count_all_products($settings, $edit_mode);
        }
        return $ids;
    }

    public function count_on_sale_products($settings, $edit_mode) {
        if ( !$settings['category_count'] ) return [];
        $all_sale_ids = wc_get_product_ids_on_sale();
        $new_ids = [];
        $variable_products = [];
        foreach ($all_sale_ids as $all_sale_id) {
            $local_id = $all_sale_id;
            $local_product = wc_get_product( $all_sale_id );
            if ( !$local_product ) continue;
            if ( $local_product->get_type() == 'variation' ) {
                $local_id = $local_product->get_parent_id();
                $variable_products[] = $local_id;
            }

            $new_ids[] = $local_id;
        }

        if ( !get_theme_mod('variable_products_detach', false) )
            return count(array_unique($new_ids));
        else {
            if ( get_theme_mod('variation_product_parent_hidden', true) ) {
                return count($new_ids) - count(array_unique($variable_products));
            }
            else {
                return count($new_ids);
            }
        }
    }
    /**
     * Filter image by default (wp) size.
     *
     * @param $old_size
     * @return mixed
     *
     * @since 5.2
     *
     */
    public function image_prerendered_size_filter($old_size) {
        global $local_settings;
//	    $settings = $this->get_settings_for_display();
        return $local_settings['image_size'];
    }

    /**
     * Function that returns rendered title by chars/words limit.
     *
     * @param $title
     * @return mixed|string
     *
     * @since 5.2
     *
     */
    public function limit_title_string($title) {
//		$settings = $this->get_settings_for_display();
        global $local_settings;
        if ( $local_settings['category_title_limit'] > 0) {
            if ( $local_settings['category_title_limit_type'] == 'chars' ) {
                return Elementor::limit_string_by_chars($title, $local_settings['category_title_limit']);
            }
            elseif ( $local_settings['category_title_limit_type'] == 'words' ) {
                return Elementor::limit_string_by_words($title, $local_settings['category_title_limit']);
            }
        }
        return $title;
    }

    /**
     * Function that returns rendered heading by chars/words limit.
     *
     * @param $title
     * @return mixed|string
     *
     * @since 5.2
     *
     */
    public function limit_heading_string($title) {
//		$settings = $this->get_settings_for_display();
        global $local_settings;
        if ( $local_settings['heading_limit'] > 0) {
            if ( $local_settings['heading_limit_type'] == 'chars' ) {
                return Elementor::limit_string_by_chars($title, $local_settings['heading_limit']);
            }
            elseif ( $local_settings['heading_limit_type'] == 'words' ) {
                return Elementor::limit_string_by_words($title, $local_settings['heading_limit']);
            }
        }
        return $title;
    }

    /**
     * All product element that could be shown.
     *
     * @since 5.2
     *
     * @return mixed
     */
    public static function get_category_elements() {
        $elements = array(
            'image' => esc_html__('Show Image', 'xstore-core'),
            'title' => esc_html__('Show Title', 'xstore-core'),
            'count' => esc_html__('Show Products Count', 'xstore-core'),
        );
        return apply_filters('etheme_dynamic_categories_elements', $elements);
    }

    /**
     * Return filtered product data sources
     *
     * @since 5.2
     *
     * @return mixed
     */
    public static function get_data_source_list() {
        return array(
            'all' => esc_html__( 'All categories', 'xstore-core' ),
            'excluded' => esc_html__( 'Exclude IDs', 'xstore-core' ),
            'included' => esc_html__( 'Include IDs', 'xstore-core' ),
        );
    }
}
