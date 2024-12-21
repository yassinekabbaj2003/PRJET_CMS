<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Mobile Menu widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Mobile_Menu extends Off_Canvas_Skeleton {

    public static $instance = null;

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_mobile_menu';
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
		return __( 'Mobile Menu', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-mobile-menu et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'mobile', 'menu', 'navigation', 'links' ]);
	}

    /**
     * Get widget dependency.
     *
     * @since 4.0.11
     * @access public
     *
     * @return array Widget dependency.
     */
    public function get_style_depends() {
        return array_merge(parent::get_style_depends(), [ 'etheme-elementor-menu' ]);
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
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() )
            return array_merge(parent::get_script_depends(), ['etheme_elementor_mega_menu']);
        return parent::get_script_depends();
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        $search = \ETC\App\Controllers\Elementor\General\Search::get_instance();

        parent::register_controls();

        $this->remove_control('automatically_open_canvas');
        $this->remove_control('items_count');
        $this->remove_control('section_off_canvas_quantity_style');
        $this->remove_control('section_additional');
        $this->remove_control('off_canvas_advanced');
        $this->remove_control('off_canvas_head');
        $this->remove_control('off_canvas_head_icon');
        $this->remove_control('off_canvas_head_inline_design');
        $this->remove_control('content_align_top');
        $this->remove_control('product_title_full');

        $this->update_control(
            'content_type',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'off_canvas',
            ]
        );

        $this->update_control(
                'redirect',
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => 'none',
                ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-burger'
                ],
            ]
        );

        $this->update_control(
            'button_text',
            [
                'default' => '',
                'placeholder' => __( 'Menu', 'xstore-core' ),
            ]
        );

        $disable_options = array(
            'show_view_page',
            'show_view_page_extra'
        );

        foreach ($disable_options as $disable_option) {
            $this->update_control($disable_option,
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => '',
                ]
            );
        }

        $hidden_options = array(
            'show_quantity',
            'show_quantity_zero',
            'quantity_position'
        );

        foreach ($hidden_options as $hidden_option) {
            $this->update_control($hidden_option,
                [
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                ]
            );
        }

        $this->update_control('off_canvas_position', [
            'default' => is_rtl() ? 'right' : 'left',
        ]);

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Items', 'xstore-core' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $data_sources = $this->get_content_elements();
        $default_data_source = array_key_first($data_sources);

        $repeater->add_control(
            'element',
            [
                'label' 		=>	__( 'Element', 'xstore-core' ),
                'type' 			=>	(count($data_sources) > 1 ? \Elementor\Controls_Manager::SELECT : \Elementor\Controls_Manager::HIDDEN),
                'options' => $data_sources,
                'default'	=> $default_data_source
            ]
        );

        $menus = Elementor::get_available_menus();

        if ( ! empty( $menus ) ) {

	        $default_menu = array_keys( $menus );
	        $default_menu = (isset($default_menu[0]) && $default_menu[0]) ? $default_menu[0] : '';

            $repeater->add_control(
                'wp_menu',
                [
                    'label' => __( 'Menu', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'description' => sprintf(
                    /* translators: 1: Link opening tag, 2: Link closing tag. */
                        esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'xstore-core' ),
                        sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php' ) ),
                        '</a>'
                    ),
                    'options' => $menus,
                    'default' => $default_menu,
                    'save_default' => true,
                    'separator' => 'after',
                    'condition' => [
                        'element' => 'wp_menu'
                    ],
                ]
            );
        } else {
            $repeater->add_control(
                'wp_menu',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<strong>' . esc_html__( 'There are no menus in your site.', 'xstore-core' ) . '</strong><br>' .
                        sprintf(
                        /* translators: 1: Link opening tag, 2: Link closing tag. */
                            esc_html__( 'Go to the %1$sMenus screen%2$s to create one.', 'xstore-core' ),
                            sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                            '</a>'
                        ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'separator' => 'after',
                    'condition' => [
                        'element' => 'wp_menu'
                    ],
                ]
            );
        }

        $repeater->add_control(
            'wp_menu_extra',
            [
                'label' => __( 'Extra Content', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'none'       => esc_html__( 'Without', 'xstore-core' ),
                    'categories' => esc_html__( 'Categories', 'xstore-core' ),
                    'menu'       => esc_html__( 'Menu', 'xstore-core' ),
                ),
                'default' => 'none',
                'condition' => [
                    'element' => 'wp_menu'
                ],
            ]
        );

        $default_menu = array_keys( $menus );
	    $default_menu = (isset($default_menu[0]) && $default_menu[0]) ? $default_menu[0] : '';

        $repeater->add_control(
            'wp_menu_2',
            [
                'label' => __( 'Menu', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'description' => sprintf(
                /* translators: 1: Link opening tag, 2: Link closing tag. */
                    esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'xstore-core' ),
                    sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php' ) ),
                    '</a>'
                ),
                'options' => $menus,
                'default' => $default_menu,
                'save_default' => true,
                'condition' => [
                    'element' => 'wp_menu',
                    'wp_menu_extra' => 'menu'
                ],
            ]
        );

        $repeater->add_control(
            'menu_tab_01_title',
            [
                'label' => __( 'Tab 01 text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'element' => 'wp_menu',
                    'wp_menu_extra!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'menu_tab_02_title',
            [
                'label' => __( 'Tab 02 text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'separator' => 'after',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'element' => 'wp_menu',
                    'wp_menu_extra!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'wp_menu_categories_hide_empty',
            [
                'label' => __( 'Hide Empty Categories', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'element' => 'wp_menu',
                    'wp_menu_extra' => 'categories'
                ],
            ]
        );

        $repeater->add_control(
            'wp_menu_reverse',
            [
                'label' => __( 'Elements Reverse', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'condition' => [
                    'element' => 'wp_menu',
                    'wp_menu_extra!' => 'none'
                ],
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Title', 'xstore-core' ),
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'element' => ['account', 'cart', 'wishlist', 'compare']
                ],
            ]
        );

//        $repeater->add_control(
//            'show_quantity',
//            [
//                'label' => __( 'Show quantity', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//                'default' => 'yes',
//                'condition' => [
//                    'element' => ['account', 'cart', 'wishlist', 'compare']
//                ],
//            ]
//        );

        $repeater->add_control(
            'show_total',
            [
                'label' => __( 'Show total', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'element' => 'cart'
                ],
            ]
        );

        $repeater->add_control(
            'ajax_search',
            [
                'label' => __( 'Ajax search', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'element' => 'search'
                ],
            ]
        );

        $search_types = $search->search_post_types();
        $default_post_types = [];
        if ( array_key_exists('product', $search_types) ) {
            $default_post_types[] = 'product';
        }
        if ( array_key_exists('post', $search_types) ) {
            $default_post_types[] = 'post';
        }

        $repeater->add_control(
            'search_post_types',
            [
                'label'   => esc_html__( 'Post Types', 'xstore-core' ),
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $search_types,
                'default' => $default_post_types,
                'condition' => [
                    'element' => 'search'
                ],
            ]
        );

        $repeater->add_control(
            'search_placeholder',
            [
                'label' => __( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Search for products...', 'xstore-core' ),
                'condition' => [
                    'element' => 'search'
                ],
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-star'
                ],
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'element' => ['account', 'cart', 'wishlist', 'compare']
                ],
            ]
        );

        $repeater->add_control(
            'icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' => [
                    'element' => ['account', 'cart', 'wishlist', 'compare'],
                    'selected_icon[value]!' => ''
                ],
            ]
        );

        $repeater->add_responsive_control(
            'logo_max_width',
            [
                'label' => __( 'Max Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 20,
                        'max'  => 400,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'element' => 'logo'
                ],
            ]
        );

//        $repeater->add_control(
//            'element_styles',
//            [
//                'label' => __( 'Styles', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::HEADING,
//                'condition' => [
//                    'element' => 'search'
//                ],
//            ]
//        );
//
//        $repeater->add_group_control(
//            \Elementor\Group_Control_Border::get_type(),
//            [
//                'name'      => 'search_border',
//                'label'     => esc_html__( 'Border', 'xstore-core' ),
//                'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}}',
//                'fields_options' => [
//                    'border' => [
//                        'default' => 'solid',
//                        'selectors' => [
//                            '{{SELECTOR}}' => '--s-border-style: {{VALUE}};',
//                        ],
//                    ],
//                    'width' => [
//                        'type' => \Elementor\Controls_Manager::SLIDER,
//                        'selectors' => [
//                            '{{SELECTOR}}' => '--s-border-width: {{SIZE}}{{UNIT}};',
//                        ],
//                    ],
//                    'color' => [
//                        'default' => '#e1e1e1',
//                        'selectors' => [
//                            '{{SELECTOR}}' => '--s-border-color: {{VALUE}};',
//                        ],
//                    ]
//                ],
//                'condition' => [
//                    'element' => 'search'
//                ],
//            ]
//        );
//
//        $repeater->add_responsive_control(
//            'search_min_height',
//            [
//                'label' => __( 'Min Height', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SLIDER,
//                'separator' => 'before',
//                'size_units' => [ 'px', 'em', 'rem' ],
//                'range' => [
//                    'px' => [
//                        'min'  => 20,
//                        'max'  => 100,
//                        'step' => 1
//                    ],
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--s-min-height: {{SIZE}}{{UNIT}};',
//                ],
//                'condition' => [
//                    'element' => 'search'
//                ],
//            ]
//        );
//
//        $repeater->add_control(
//            'search_border_radius',
//            [
//                'label' => esc_html__( 'Border Radius', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::DIMENSIONS,
//                'size_units' => [ 'px', '%' ],
//                'selectors' => [
//                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--s-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
//                'condition' => [
//                    'element' => 'search'
//                ],
//            ]
//        );

        $repeater->add_control(
            'save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'element' => 'saved_template'
                ]
            ]
        );

        $repeater->add_control(
            'static_block_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_static_block_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'element' => 'static_block'
                ]
            ]
        );

        $saved_templates = Elementor::get_saved_content();

        $repeater->add_control(
            'saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $saved_templates,
                'default' => 'select',
                'condition' => [
                    'element' => 'saved_template'
                ],
            ]
        );

        $static_blocks = Elementor::get_static_blocks();

        $repeater->add_control(
            'static_block',
            [
                'label' => __( 'Static Block', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $static_blocks,
                'default' => 'select',
                'condition' => [
                    'element' => 'static_block'
                ],
            ]
        );

        $default_items = [
            [
                'element'  => 'logo',
            ],
            [
                'element'  => 'search',
            ],
            [
                'element'  => 'wp_menu',
            ],
        ];
        if ( class_exists('WooCommerce') ) {
            foreach (array('account', 'cart', 'wishlist', 'compare') as $default_item) {
                $default_items[] = [
                    'element'  => array_key_exists($default_item, $data_sources) ? $default_item : $default_data_source,
                    'button_text' => str_replace(
                            array('account', 'cart', 'wishlist', 'compare'),
                            array(esc_html__('Account', 'xstore-core'), esc_html__('Cart', 'xstore-core'), esc_html__('Wishlist', 'xstore-core'), esc_html__('Compare', 'xstore-core')),
                            $default_item
                    ),
                    'selected_icon' => [
                        'value' => 'et-icon '.str_replace(array('account', 'cart', 'wishlist', 'compare'), array('et-user', 'et-shopping-cart', 'et-heart', 'et-compare'), $default_item),
                        'library' => 'xstore-icons',
                    ],
                ];
            }
        }
        $this->add_control(
            'items',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_items,
                // replacement to make first letter capitalize + '_' symbol with empty space
                'title_field' => '<# var item = element.charAt(0).toUpperCase() + element.slice(1); item = item.replace(/_/g, " ") #> {{item}}',
//                'title_field' => '{{{ element }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_off_canvas_elements_style',
            [
                'label' => __( 'Off-Canvas Elements', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_type!' => 'none'
                ],
            ]
        );

        $this->add_responsive_control(
            'off_canvas_elements_gap',
            [
                'label' => __( 'Elements Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--off-canvas-elements-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'off_canvas_elements_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-off-canvas__main',
            ]
        );

        $this->add_control(
            'off_canvas_elements_menu',
            [
                'label' => __( 'Menu', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'off_canvas_elements_menu_gap',
            [
                'label' => __( 'Items Space', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-mobile-menu-wp_menu' => '--off-canvas-elements-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

//        $this->add_control(
//            'off_canvas_elements_quantity',
//            [
//                'label' => __( 'Quantity', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::HEADING,
//            ]
//        );
//
//        $this->get_quantity_style(
//                'off_canvas_elements_',
//            '{{WRAPPER}} .etheme-elementor-off-canvas__main .etheme-elementor-off-canvas__toggle .elementor-button-icon-qty',
//            '{{WRAPPER}} .etheme-elementor-off-canvas__main .etheme-elementor-off-canvas__toggle:hover .elementor-button-icon-qty',
//            ['exclude_typography' => true]
//        );
//
//        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
//    protected function render()
//    {
//        parent::render();
//    }

    protected function render_main_content_head($settings, $should_make_link, $default_url_params) {

    }

    protected function render_main_content($settings, $element_page_url, $extra_args = array()) {
        $settings = $this->get_settings_for_display();
        $is_woocommerce = class_exists('WooCommerce');
        $main_unique_id = $this->get_id();

        foreach ($settings['items'] as $item_index => $item) {
            $unique_id = $main_unique_id. '_' . $item['_id'];
            if ( in_array($item['element'], array('account', 'cart', 'wishlist', 'compare'))) {
                if ( !$is_woocommerce )
                    continue;
                $this->add_render_attribute( 'element-'.$unique_id, [
                    'class' => [
                        'etheme-elementor-off-canvas__toggle'
                    ]
                ] );
            }
            $this->add_render_attribute( 'element-'.$unique_id, [
                'class' => [
                    'etheme-elementor-mobile-menu-element',
                    'etheme-elementor-mobile-menu-'.$item['element'],
                    'elementor-repeater-item-' . $item['_id'],
                ]
            ] );
            if ( $item['element'] == 'logo' ) {
                $this->add_render_attribute( 'element-'.$unique_id, [
                    'class' => [
                        'text-center',
                    ]
                ] );
            }
            $this->render_element_start($unique_id);
            switch ($item['element']) {
                case 'logo':
                    $this->get_site_logo();
                    break;
                case 'search':
                    $search = \ETC\App\Controllers\Elementor\General\Search::get_instance();
                    $search->enqueue_scripts();
                    $search->enqueue_styles();
                    $search_settings = array_merge($item, array(
                        'type' => 'inline',
                        'ajax_search' => 'yes',
                        'focus_overlay' => '',
                        'animated_placeholder' => '',
                        'placeholder' => $item['search_placeholder'],
                        'categories' => '',
                        'button_icon_align' => 'left',
                        'button_text' => '',
                        'button_icon' => '',
                        'button_selected_icon' => [
                            'value' => 'et-icon et-zoom',
                            'library' => 'xstore-icons',
                        ]
//                        'post_types' => implode(',' )
                    ));
                    $should_redirect_to_archive = $this->should_redirect_to_archive();
                    add_filter('etheme_elementor_search_should_redirect_to_archive', ($should_redirect_to_archive?'__return_true':'__return_false'));
                    $search->render_inner_content($search_settings, $unique_id, array(
                        'form_attributes' => [
                            'data-ajax-search' => 'yes',
                            'data-post-types' => implode(',', $item['search_post_types'])
                        ]
                    ));
                    remove_filter('etheme_elementor_search_should_redirect_to_archive', ($should_redirect_to_archive?'__return_true':'__return_false'));
                    break;
                case 'wp_menu':
                    // to disable theme scripts and styles
                    add_filter('menu_item_mega_menu', '__return_false');
                    add_filter('etheme_nav_menu_item_inner', [$this, 'handle_mobile_menu_arrows'], 10, 3);
                    $menu = Menu_Skeleton::get_instance();
                    $wp_menu_params = array(
                        'type' => 'vertical',
                        'handle_arrows' => false,
                        'handle_animation_classes'=> false,
                    );
                    $has_additional_content = $item['wp_menu_extra'] != 'none';
                    if ( $has_additional_content ) {
                        $tab_titles_keys = [
                            'wp_menu',
                            'wp_menu_extra'
                        ];
                        $tab_titles = [
                                !!$item['menu_tab_01_title'] ? $item['menu_tab_01_title'] : esc_html__( 'Menu', 'xstore-core' ),
                            str_replace(
                                array('menu', 'categories'),
                                array(
                                    !!$item['menu_tab_02_title'] ? $item['menu_tab_02_title'] : esc_html__('Menu 2', 'xstore-core'),
                                    !!$item['menu_tab_02_title'] ? $item['menu_tab_02_title'] : esc_html__('Categories', 'xstore-core')),
                                $item['wp_menu_extra'])
                            ];
                        if ( !!$item['wp_menu_reverse'] ) {
                            $tab_titles_keys = array_reverse($tab_titles_keys);
                            $tab_titles = array_reverse($tab_titles);
                        }
                        ?>
                        <div class="et_b-tabs-wrapper">
                            <div class="et_b-tabs">
                                <span class="et-tab active" data-tab="<?php echo $tab_titles_keys[0]; ?>">
                                    <?php echo $tab_titles[0]; ?>
                                </span>
                                <span class="et-tab" data-tab="<?php echo $tab_titles_keys[1]; ?>">
                                    <?php echo $tab_titles[1]; ?>
                                </span>
                            </div>
                        <?php
                    }
                    add_filter('nav_menu_css_class', [$menu, 'handle_link_on_click_classes'], 10, 3);
                    if ( $has_additional_content ) {
                        ?>
                        <div class="et_b-tab-content<?php echo !!!$item['wp_menu_reverse'] ? ' active' : ''; ?>" data-tab-name="wp_menu">
                        <?php
                    }
                        echo $menu->render_wp_menu($item['wp_menu'], $wp_menu_params);
                    if ( $has_additional_content ) {
                        ?>
                        </div>
                        <div class="et_b-tab-content<?php echo !!$item['wp_menu_reverse'] ? ' active' : ''; ?>" data-tab-name="wp_menu_extra">
                            <?php
                            switch ($item['wp_menu_extra']) {
                                case 'menu':
                                    echo $menu->render_wp_menu($item['wp_menu_2'], $wp_menu_params);
                                break;
                                case 'categories':
                                    add_filter('list_product_cats', [$this, 'handle_categories_arrows'], 10, 2);
                                    the_widget( 'WC_Widget_Product_Categories', apply_filters('etheme_elementor_mobile_menu_categories_args', array(
                                        'title'   => '',
                                        'orderby' => 'order',
                                        'hide_empty' => !!$item['wp_menu_categories_hide_empty'] ? 1 : 0
                                    ) ) );
                                    remove_filter('list_product_cats', [$this, 'handle_categories_arrows'], 10, 2);
                                    break;
                            }
                            ?>
                            </div>
                        </div>
                        <?php
                    }
                    remove_filter('nav_menu_css_class', [$menu, 'handle_link_on_click_classes'], 10, 3);
                    remove_filter('etheme_nav_menu_item_inner', [$this, 'handle_mobile_menu_arrows'], 10, 3);
                    remove_filter('menu_item_mega_menu', '__return_false');
                    break;
                case 'account':
                case 'cart':
                case 'wishlist':
                case 'compare':
                    $this->render_woocommerce_element($item);
                    break;
                case 'global_widget':
                case 'saved_template':
                    if (!empty($item[$item['element']]) && $item[$item['element']] != 'select'):
                        //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $item[$item['content_type']], true );

                        $posts = get_posts(
                            [
                                'name' => $item[$item['element']],
                                'post_type' => 'elementor_library',
                                'posts_per_page' => '1',
                                'tax_query' => [
                                    [
                                        'taxonomy' => 'elementor_library_type',
                                        'field' => 'slug',
                                        'terms' => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $item['element']),
                                    ],
                                ],
                                'fields' => 'ids'
                            ]
                        );
                        if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) {
                            echo esc_html__('We have imported template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                        } else {
                            echo $content;
                        }
                    endif;
                    break;
                case 'static_block':
                    Elementor::print_static_block($item[$item['element']]);
                    break;
                default;
            }
            $this->render_element_end();
        }
    }

    public function handle_categories_arrows($cat_name, $cat) {
        $has_subcategories = count(get_term_children($cat->term_id, $cat->taxonomy)) > 0;
        if ( $has_subcategories ) {
            wp_enqueue_script('etheme_elementor_mega_menu');
            ob_start();
            $this->render_menu_item_icon();
            $icon = ob_get_clean();
            if ( !empty($icon) )
                $cat_name .= $icon;
        }
        return '<span class="elementor-item">' . $cat_name . '</span>';
    }

    public function handle_mobile_menu_arrows($title, $nav_menu_css_classes, $depth) {
        if (in_array( 'menu-item-has-children', $nav_menu_css_classes ) ) {
            wp_enqueue_script('etheme_elementor_mega_menu');
            ob_start();
            $this->render_menu_item_icon();
            $icon = ob_get_clean();
            if ( !empty($icon) )
                $title .= $icon;
        }
        return $title;
    }

    public function render_menu_item_icon() {
        $icon_html = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" fill="currentColor">
<path d="M31.712 8.096c-0.352-0.352-0.896-0.352-1.312 0l-14.4 13.888-14.4-13.888c-0.352-0.352-0.896-0.352-1.312 0-0.192 0.16-0.288 0.416-0.288 0.64 0 0.256 0.096 0.48 0.256 0.672l15.040 14.528c0.128 0.128 0.32 0.256 0.64 0.256 0.192 0 0.384-0.064 0.576-0.192l0.032-0.032 15.072-14.56c0.192-0.16 0.32-0.416 0.32-0.672 0.032-0.224-0.064-0.48-0.224-0.64z"></path>
</svg>';
        $icon_active_html = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32" fill="currentColor">
<path d="M31.584 22.592l-14.944-14.496c-0.352-0.352-0.928-0.32-1.28 0l-15.008 14.496c-0.16 0.16-0.256 0.384-0.288 0.64 0 0.256 0.096 0.48 0.288 0.672s0.416 0.288 0.64 0.288c0.224 0 0.48-0.096 0.64-0.288l14.368-13.856 14.336 13.824c0.288 0.288 0.768 0.352 1.248 0l0.032-0.032c0.16-0.16 0.256-0.416 0.256-0.64 0.032-0.224-0.096-0.448-0.288-0.608z"></path>
</svg>';
        ?>
        <span class="etheme-elementor-nav-menu-item-arrow">
            <span class="etheme-elementor-nav-menu-item-icon etheme-elementor-nav-menu-item-icon-opened"><?php echo $icon_active_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <span class="etheme-elementor-nav-menu-item-icon etheme-elementor-nav-menu-item-icon-closed"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
        </span>
        <?php
    }

    protected function get_site_logo() {
        $link = $this->get_site_logo_link_url();
        $logo_url = $this->get_site_logo_url();

        if ( $link ) {
            $this->add_link_attributes( 'link', $link );

            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                $this->add_render_attribute( 'link', 'class', 'elementor-clickable' );
            }
        } ?>
                <?php if ( $link ) : ?>
                <a <?php $this->print_render_attribute_string( 'link' ); ?>>
                    <?php endif;
                    if ( !empty($logo_url) ) {
                        $logo_id = attachment_url_to_postid($logo_url);
                        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html(
                            array(
                                'image' => array(
                                    'id' => $logo_id > 0 ? $logo_id : '',
                                    'url' => $logo_url
                                ),
                            )
                        );
                    }
                    else {
                        echo \Elementor\Utils::get_placeholder_image();
                    }
                    if ( $link ) : ?>
                </a>
            <?php endif;
    }

    protected function get_site_logo_link_url() {
        return [ 'url' => \Elementor\Plugin::$instance->dynamic_tags->get_tag_data_content( null, 'site-url' ) ?? '' ];
    }

    // Get the site logo from the dynamic tag
    private function get_site_logo_url() {
        $site_logo = \Elementor\Plugin::$instance->dynamic_tags->get_tag_data_content( null, 'site-logo' );
        return $site_logo['url'] ? $site_logo['url'] : \Elementor\Utils::get_placeholder_image_src();
    }

    public function render_element_start($unique_id) {
        ?>
        <div <?php $this->print_render_attribute_string( 'element-'.$unique_id ); ?>>
        <?php
    }
    public function render_element_end() {
        ?>
        </div>
        <?php
    }
    public function render_woocommerce_element($item) {
        $element = $item['element'];
        $unique_id = $item['_id'];

        $default_url_params = array(
            'url' => '',
            'is_external' => '',
            'nofollow' => '',
            'custom_attributes' => ''
        );

        $button_text = '';
        $element_text_after = '';

        $should_make_link = false;

        $qty_count = '';

        switch ($element) {
            case 'account':
                $default_url_params['url'] = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;
                break;
            case 'cart':
                $default_url_params['url'] = wc_get_cart_url();
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;
                if ( !!$item['show_total'])
                    $element_text_after = $this->render_cart_subtotal();
                $qty_count = $this->get_cart_qty();
                break;
            case 'wishlist':
            case 'compare':
                $local_options = array();
                $local_options['built_in_'.$element] = get_theme_mod('xstore_'.$element, false);
                if ( $local_options['built_in_'.$element] ) {
                    $local_options['built_in_'.$element.'_page_id'] = get_theme_mod('xstore_'.$element.'_page', '');
                    switch ($element) {
                        case 'wishlist':
                            $extra_args['built_in_'.$element.'_instance'] = Wishlist::get_instance();
                            $qty_count = $extra_args['built_in_'.$element.'_instance']->get_icon_qty_count();
                            break;
                        case 'compare':
                            $extra_args['built_in_'.$element.'_instance'] = Compare::get_instance();
                            $qty_count = $extra_args['built_in_'.$element.'_instance']->get_icon_qty_count();
                            break;
                    }
                    if ( $local_options['built_in_'.$element.'_page_id'] ) {
                        $default_url_params['url'] = get_permalink($local_options['built_in_'.$element.'_page_id']);
                    }
                    else {
                        $local_options['built_in_'.$element.'_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
                        if ( $local_options['built_in_'.$element.'_ghost_page_id'] )
                            $default_url_params['url'] = add_query_arg('et-'.$element.'-page', '', get_permalink($local_options['built_in_'.$element.'_ghost_page_id']));
                    }
                }
                if ( !empty($default_url_params['url']) )
                    $should_make_link = true;

                unset($local_options);
                break;
        }

        if ( !!$item['button_text'] )
            $button_text = $item['button_text'];

//        $this->add_render_attribute( 'button_text_wrapper-'.$unique_id, [
//            'class' => 'elementor-button-content-wrapper',
//        ] );

        if ( $should_make_link ) {
            $this->add_render_attribute( 'button-'.$unique_id, 'class', 'elementor-button-link' );
            $this->add_link_attributes('button-'.$unique_id, $default_url_params);
        }

        $this->add_render_attribute( 'button_text-'.$unique_id, [
            'class' => 'button-text',
        ] );

        ?>
            <a <?php $this->print_render_attribute_string( 'button-'.$unique_id ); ?>>
                <span <?php echo $this->get_render_attribute_string( 'button_text_wrapper-'.$unique_id ); ?>>
                    <?php
                    if ( !$button_text || in_array($item['icon_align'], array('left', 'above') ) )
                        $this->render_element_icon( $item, $qty_count );
                    if ( $button_text ) : ?>
                        <span <?php echo $this->get_render_attribute_string( 'button_text-'.$unique_id ); ?>>
                            <?php
                                echo $button_text;
                                echo $element_text_after;
                            ?>
                        </span>
                    <?php else:
                        echo $element_text_after;
                    endif;
                    if ( $button_text && $item['icon_align'] == 'right')
                        $this->render_element_icon( $item, $qty_count );
                    ?>
                </span>
            </a>
        <?php
    }

    protected function render_element_icon($settings, $qty_count = false) {
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
            <span class="elementor-button-icon">
                <?php if ( $is_new || $migrated ) :
                    \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <?php
//                if ( !!$settings['show_quantity'] )
//                    $this->render_icon_qty($qty_count); ?>
            </span>
        <?php endif;
    }

    public function render_cart_subtotal() {
        ob_start();
        ?>
            <span class="etheme-elementor-off-canvas-total-inner">
                <?php echo wp_specialchars_decode( WC()->cart->get_cart_subtotal() ); ?>
            </span>
        <?php
        return ob_get_clean();
    }

    public function get_cart_qty() {
        return WC()->cart->get_cart_contents_count();
    }
    public function get_content_elements() {
        return array_merge([
            'logo' => esc_html__('Logo', 'xstore-core'),
            'search' => esc_html__('Search', 'xstore-core'),
            'wp_menu' => esc_html__('WP Menu', 'xstore-core'),
            'account' => esc_html__('Account', 'xstore-core'),
            'cart' => esc_html__('Cart', 'xstore-core'),
            'wishlist' => esc_html__('Wishlist', 'xstore-core'),
            'compare' => esc_html__('Compare', 'xstore-core'),
        ], Elementor::get_saved_content_list(array(
            'custom' => false,
            'global_widget' => false
        )));
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
        return true;
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  4.1
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}
