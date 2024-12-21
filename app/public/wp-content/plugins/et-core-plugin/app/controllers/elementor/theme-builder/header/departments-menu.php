<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Departments Menu widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Departments_Menu extends Mega_Menu {

    protected $subitem_classes;

    protected $hidden_subitem_counter = 0;
	private $subitem_counter = 0;

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_departments_menu';
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
		return __( 'Departments Menu', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-department-menu et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'mega', 'all departments', 'categories', 'staticblock', 'template' ]);
	}

    /**
     * Get widget dependency.
     *
     * @since 4.0.11
     * @access public
     *
     * @return array Widget dependency.
     */
//    public function get_style_depends() {
//        return array_merge(parent::get_style_depends(), [ 'etheme-mega-menu' ]);
//    }

    /**
     * Get widget dependency.
     *
     * @since 5.2
     * @access public
     *
     * @return array Widget dependency.
     */
//    public function get_script_depends() {
//        return array_merge(parent::get_script_depends(), ['etheme_elementor_mega_menu']);
//    }

//    protected function get_nav_menu_index() {
//        return $this->nav_menu_index++;
//    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        add_filter('etheme_elementor_menu_item_dropdown_stretch_options', '__return_false');
//        add_filter('etheme_elementor_menu_item_dropdown_custom_width_options', '__return_true');
        add_filter('etheme_elementor_menu_item_active_options', '__return_false');
        add_action('etheme_elementor_menu_before_style', [$this, 'register_popup_button_controls']);

        parent::register_controls();

        remove_filter('etheme_elementor_menu_item_dropdown_stretch_options', '__return_false');
//        remove_filter('etheme_elementor_menu_item_dropdown_custom_width_options', '__return_true');
        remove_filter('etheme_elementor_menu_item_active_options', '__return_false');
        remove_action('etheme_elementor_menu_before_style', [$this, 'register_popup_button_controls']);

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'align_items',
        ] );

        $this->add_control(
            'item_text',
            [
                'label' 		=>	__( 'Title', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' => __( 'All Departments', 'xstore-core' ),
            ]
        );

//        $this->add_responsive_control(
//            'item_text_hidden',
//            [
//                'label' => __('Hide button text', 'xstore-core'),
//                'type' => \Elementor\Controls_Manager::SWITCHER,
//                'conditions' => [
//                    'relation' => 'or',
//                    'terms' 	=> [
//                        [
//                            'name' 		=> 'item_text',
//                            'operator'  => '!=',
//                            'value' 	=> ''
//                        ],
//                    ],
//                ]
//            ]
//        );

        $this->register_item_control($this);

        $this->end_injection();

        $this->update_control('item_element', [
                'separator' => 'none'
            ]
        );

        $this->update_control('item_background_color', [
                'default' => '#ffffff',
            ]
        );

        $this->update_control('item_element', [
            'default' => 'icon',
        ]);

        $this->update_control('selected_icon', [
            'default' => [
                'library' => 'xstore-icons',
                'value' => 'et-icon et-dev-menu'
            ]
        ]);

        $this->update_control('add_link', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('show_label', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
        ]);

        $this->update_control('separator_type', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => 'none'
        ]);

        $this->update_control('section_items', [
            'condition' => [
                'menu_type' => 'advanced'
            ]
        ]);

        $this->update_control('main_pointer', [
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => ''
        ]);

        $this->update_control('item_border_border', [
            'default' => 'solid'
        ]);

        $this->update_control('item_border_width', [
            'default' => [
                'top' => 1,
                'left' => 1,
                'right' => 1,
                'bottom' => 1,
                'unit' => 'px'
            ]
        ]);

        $this->update_control('item_padding', [
            'default' => [
                'top' => '10',
                'right' => '20',
                'bottom' => '10',
                'left' => '20',
                'isLinked' => false,
                'unit' => 'px',
            ]
        ]);

        $this->update_control('dropdown_item_padding', [
            'default' => [
                'top' => '11',
                'right' => '20',
                'bottom' => '11',
                'left' => '20',
                'isLinked' => false,
                'unit' => 'px',
            ],
        ]);

        $this->update_control('item_border_radius', [
            'default' => [
                'top' => '3',
                'right' => '3',
                'bottom' => '3',
                'left' => '3',
                'unit' => 'px',
            ]
        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_item_style',
        ] );

        $this->add_responsive_control(
            'item_width',
            [
                'label' => __( 'Min-Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => .1,
                    ],
                    'rem' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 18
                ],
                'condition' => [
                    'item_text!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu.horizontal > li' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_injection();

        $this->update_control('dropdown_width', [
            'default' => [
                'unit' => 'rem',
                'size' => 18
             ]
        ]);

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'start',
            'of'   => 'section_dropdown',
        ] );

        $this->add_control(
            'menu_type',
            [
                'label' => esc_html__( 'Source Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'wp_menu',
                'options' => [
                    'wp_menu' => esc_html__( 'WP Menu', 'xstore-core' ),
                    'advanced' => esc_html__( 'Advanced', 'xstore-core' ),
                ],
            ]
        );

        $menus = Elementor::get_available_menus();

        if ( ! empty( $menus ) ) {
            $this->add_control(
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
                    'default' => array_keys( $menus )[0],
                    'save_default' => true,
                    'separator' => 'after',
                    'condition' => [
                        'menu_type' => 'wp_menu'
                    ],
                ]
            );
        } else {
            $this->add_control(
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
                        'menu_type' => 'wp_menu'
                    ],
                ]
            );
        }

        $this->add_control(
            'menu_state',
            [
                'label' => __( 'Menu Action', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'On Hover', 'xstore-core' ),
                    'opened' => __( 'Opened', 'xstore-core' ),
                    'click' => __( 'On Click', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'menu_state_only',
            [
                'label' 		=>	__( 'Opened only on', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT2,
                'label_block'	=> 'true',
                'description' 	=>	__( 'Choose pages', 'xstore-core' ),
                'multiple' 		=>	true,
                'options' 		=>	Elementor::get_post_pages(array( 'page' )),
                'condition' 	=>	['menu_state' => 'opened'],
            ]
        );

        $this->end_injection();

        $this->start_injection( [
            'type' => 'section',
            'at'   => 'end',
            'of'   => 'section_dropdown',
        ] );

        $this->add_control(
            'dropdown_arrow',
            [
                'label' => __( 'Menu arrow', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
//                'condition' => [
//                    'menu_state!' => ''
//                ]
            ]
        );

        $this->add_responsive_control(
            'dropdown_arrow_size',
            [
                'label' => __( 'Arrow Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'condition' => [
//                    'menu_state!' => '',
                    'dropdown_arrow!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--menu-sublist-arrow-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_offset',
            [
                'label' => __( 'Offset', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
//                    'menu_state!' => '',
                    'dropdown_arrow!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--menu-sublist-offset-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_clean_design',
            [
                'label' => __( 'Clean design', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'separator' => 'before',
                'return_value' => 'clean',
                'prefix_class' => 'dropdown-design-',
                'render_type' => 'template',
            ]
        );

        $this->end_injection();

//        $this->start_injection( [
//            'type' => 'section',
//            'at'   => 'end',
//            'of'   => 'section_dropdown',
//        ] );
//
//        $this->get_hover_effects('dropdown_item');
//
//        $this->end_injection();

        $this->update_control('dropdown_animation', [
            'default' => 'none',
            'separator' => 'before',
        ]);

        $this->update_control('section_item_style', [
            'label' => __( 'Title', 'xstore-core' ),
        ]);

        $this->remove_control('cols_gap');
        $this->remove_control('item_pointer_hover');

    }

    public function register_popup_button_controls() {

        $this->start_controls_section(
            'dropdown_items_limits_section',
            [
                'label' => __( 'Dropdown Limits', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'dropdown_items_limited',
            [
                'label' => esc_html__( 'Show more link', 'xstore-core' ),
                'description' => esc_html__( 'If menu has more items than the number set in the option below, only a limited number of items will be shown initially, and the total number of additional items will be indicated by "+X more". Clicking the "+X more" link will reveal the hidden items.', 'xstore-core' ),
                'separator' => 'before',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'dropdown_items_limited_more_text',
            [
                'label' 		=>	__( 'Show more text', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' 		=>	__('Show more ({{count}})', 'xstore-core'),
                'condition' => [
                    'dropdown_items_limited!' => ''
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'dropdown_items_limited_more_text_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'dropdown_items_limited_more_text_icon',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-more'
                ],
                'skin' => 'inline',
                'label_block' => false,
                'condition' 	=> [
                    'dropdown_items_limited!' => '',
                ]
            ]
        );

        $this->add_control(
            'dropdown_items_limited_more_text_icon_align',
            [
                'label' => __( 'Icon Position', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'xstore-core' ),
                    'right' => __( 'After', 'xstore-core' ),
                ],
                'condition' 	=> [
                    'dropdown_items_limited!' => '',
                    'dropdown_items_limited_more_text_selected_icon[value]!' => '',
                    'dropdown_items_limited_more_text!' => ''
                ]
            ]
        );

        $this->add_control(
            'dropdown_items_limited_after',
            [
                'label' 		=>	__( 'Limit', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::NUMBER,
                'separator' => 'before',
                'default'	 	=>	'5',
                'min' 	=> '1',
                'max' 	=> '',
                'condition' => [
                    'dropdown_items_limited!' => ''
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'dropdown_items_limited_less',
            [
                'label' => esc_html__( 'Show less link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__( 'With this option, the customer will have the option to collapse the menu once the "+X items" link has been clicked.', 'xstore-core' ),
                'condition' => [
                    'dropdown_items_limited!' => ''
                ],
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'dropdown_items_limited_less_text',
            [
                'label' 		=>	__( 'Show less text', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' 		=>	__('Show less', 'xstore-core'),
                'frontend_available' => true,
                'condition' => [
                    'dropdown_items_limited!' => '',
                    'dropdown_items_limited_less!' => ''
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        parent::init_attributes();

        parent::menu_wrapper_start();

        $this->add_render_attribute( 'departments_title', [
            'class' => [
                'item-design-dropdown'
            ]
        ]);

        $parent_link_classes = array();

        // $parent_link_classes = array_merge($parent_link_classes, $this->get_link_animation_classes());

        if ( !!$settings['hover_overlay'] ) {
            $this->add_render_attribute( 'departments_title', [
                'class' => 'add-overlay-body-on-hover'
            ]);
        }

        if ( !!$settings['dropdown_arrow'] ) {
            $this->add_render_attribute('departments_title', [
                'class' => 'with-arrow'
            ]);
        }

        if ( $settings['menu_state'] != '' ) {
            $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            $should_add_opened_classes = true;
            if ( !$edit_mode && $settings['menu_state'] == 'opened' && !empty($settings['menu_state_only'])) {
                $should_add_opened_classes = in_array(get_the_ID(), $settings['menu_state_only']);
            }
            if ( $should_add_opened_classes ) {
                $this->add_render_attribute('departments_title', [
                    'class' => 'dropdown-' . $settings['menu_state']
                ]);
                if ($settings['menu_state'] == 'opened' && !$edit_mode)
                    $this->add_render_attribute('departments_title', [
                        'class' => 'dropdown-click'
                    ]);
            }
        }

        $tag = 'span';

//        if ( $settings['add_link'] && ! empty( $settings['link']['url'] ) ) {
//            $tag = 'a';
//            $this->add_link_attributes( 'item-text', $settings['link'] );
//            $local_link = get_permalink( get_the_ID() );
//            $link = $settings['link']['url'];
//            if ( is_tax() ) {
//                global $wp_query;
//                $obj        = $wp_query->get_queried_object();
//                $local_link = get_term_link( $obj );
//            }
//
//            if ( $local_link && strpos( $local_link, substr( $link, 0, - 2 ) ) !== false ) {
//                $this->add_render_attribute( 'item-text-inner', 'class', 'elementor-item-active');
//            }
//        }

        $this->add_render_attribute( 'item-text', [
            'class' => array_merge($parent_link_classes, [
//                            'elementor-item',
                'etheme-elementor-nav-menu-item',
                'etheme-elementor-nav-menu-item-parent',
            ])
        ]);

        $this->add_render_attribute( 'item-text-inner', [
            'class' => ['elementor-item']
        ]);

        $this->subitem_classes = !!!$settings['dropdown_clean_design'] ? array(
            'e--pointer-overline',
            'e--pointer-overline-left',
            'e--animation-fade'
        ) : array();

        $this->start_depth = 1;
        $limited_items = !!$settings['dropdown_items_limited'];

        $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

        $button_extra_classes = array();
        if ( false ) {
        foreach ( $settings as $key => $value ) :
            if ( 0 === strpos($key, 'item_text_hidden') && $value ) :
                $hidden_on_device = str_replace('item_text_hidden', '', $key);
                if ( empty($hidden_on_device) )
                    $hidden_on_device = 'desktop';
                else {
                    $hidden_on_device = str_replace('_', '', $hidden_on_device);
                }
                $button_extra_classes[] = 'elementor-hidden-' . $hidden_on_device;
                // hide force in editor
                if ( $edit_mode ) {
                    ?>
                    <style>
                        [data-elementor-device-mode="<?php echo $hidden_on_device ?>"] [data-id="<?php echo $this->get_id(); ?>"] .elementor-hidden-<?php echo $hidden_on_device; ?> {
                            display: none !important;
                        }
                    </style>
                    <?php
                }
            endif;
        endforeach;
        }

        ?>
        <li <?php $this->print_render_attribute_string( 'departments_title' ); ?>>
            <?php $this->render_item_inner($settings, $settings, $tag, true, '', array('item_text_classes' => $button_extra_classes)); ?>
                <div class="etheme-elementor-nav-menu--dropdown nav-sublist-dropdown<?php echo !!$settings['dropdown_arrow'] ? ' with-arrow' : ''; ?>">
                            <?php
                                $this->start_depth = 2;
                                if ( $settings['menu_type'] == 'wp_menu' ) {
                                    if ( $limited_items ) {
                                        add_filter('wp_nav_menu_objects', [$this, 'etheme_all_departments_limit_objects'], 10, 2);
                                        add_filter('wp_nav_menu_items', [$this, 'etheme_all_departments_limit_items'], 10, 2);
                                    }
                                    echo $this->render_wp_menu($settings['wp_menu']);

                                    if ( $limited_items ) {
                                        remove_filter('wp_nav_menu_objects', [$this, 'etheme_all_departments_limit_objects'], 10, 2);
                                        remove_filter('wp_nav_menu_items', [$this, 'etheme_all_departments_limit_items'], 10, 2);
                                    }
                                } else {
                                    ?>
                                    <ul class="etheme-elementor-nav-menu vertical">
                                        <?php
                                        $mega_menu_args = array(
                                            'item_classes' => ['item-level-0']
                                        );
                                        if ( $limited_items )
                                            $mega_menu_args['item_limits'] = $settings['dropdown_items_limited_after'];

                                        $this->render_inner_content($mega_menu_args);

                                        if ( $limited_items && count($settings['items']) > $mega_menu_args['item_limits'] )
                                            echo $this->render_more_less_item((count($settings['items']) - $mega_menu_args['item_limits']));
                                    ?>
                                    </ul>
                                    <?php
                                }
                            ?>
                </div>
        </li>
         <?php
        $this->subitem_counter = 0;
        parent::menu_wrapper_end();

    }

    /**
     * Set animations for 0 level of dropdown items only
     *
     * @param $classes
     * @param $li_classes
     * @param $depth
     * @return mixed
     *
     */
    public function handle_link_animation_classes( $classes, $li_classes, $depth ) {
        if ( $depth == 0 ) {
            $classes = array_merge($classes, $this->subitem_classes);
        }
        return $classes;
    }

    public function get_link_animation_classes($level = 0) {
        $classes = array();
        if ( $level == 0 ) {
            $classes = array_merge($classes, $this->subitem_classes);
        }
        return $classes;
    }

    public function etheme_all_departments_limit_objects( $items, $args ) {
        $limit = $this->get_settings_for_display('dropdown_items_limited_after');
        $limit = empty($limit) ? 3 : $limit;
        $toplinks  = 0;
        $max_count = count( $items );
        foreach ( $items as $k => $v ) {
            if ( $v->menu_item_parent == 0 ) {
                $toplinks ++;
                if ( $toplinks > $limit ) {
//			    unset($items[$k]);
                    $items[ $k ]->classes[] = 'hidden';
                    $this->hidden_subitem_counter++;
                }
            }
        }

        return $items;
    }

    public function render_more_less_item($counter) {
        $settings = $this->get_settings_for_display();
        $show_less = !!$settings['dropdown_items_limited_less'];
        $unique_id = $this->get_id();

        $more_less_items = array();

        $this->remove_render_attribute( 'items_more_less_wrapper'.$unique_id, 'class');
        $this->remove_render_attribute( 'items_more_less_wrapper'.$unique_id, 'data-reverse');
        $this->remove_render_attribute( 'items_more_less'.$unique_id, 'class');

        $this->add_render_attribute( 'items_more_less_wrapper'.$unique_id, [
            'class' => [
                'menu-item',
                'etheme-elementor-nav-menu-item-more-less',
                'item-level-0',
            ]
        ]);

        $this->add_render_attribute( 'items_more_less'.$unique_id, [
            'class' => [
                'etheme-elementor-nav-menu-item',
            ]
        ]);

        if ( !!!$settings['dropdown_clean_design'] ) {
            $this->add_render_attribute( 'items_more_less'.$unique_id, [
                'class' => [
                    'e--pointer-overline e--pointer-overline-left e--animation-fade'
                ]
            ]);
        }

        ob_start();
        $this->render_icon($settings, 'dropdown_items_limited_more_text_');
        $icon = ob_get_clean();
        if ( !empty($icon) )
            $icon = '<span '.$this->get_render_attribute_string( 'item-icon' ) . '>'.$icon.'</span>';

        $icon_position = $settings['dropdown_items_limited_more_text_icon_align'];

        $more_text = str_replace('{{count}}', $counter, $settings['dropdown_items_limited_more_text']);
        if ( !empty($more_text) )
            $more_text = '<span>'.$more_text.'</span>';

        if ( $icon )
            $more_text = ($icon_position == 'left' ? $icon . $more_text : $more_text . $icon);

        $more_less_items[] = '<span '.$this->get_render_attribute_string('items_more_less'.$unique_id).'>'.'<span class="elementor-item">'.$more_text.'</span>'.'</span>';

        if ( $show_less ) {
            $less_text = str_replace('{{count}}', $counter, $settings['dropdown_items_limited_less_text']);
            if ( !empty($less_text) )
                $less_text = '<span>'.$less_text.'</span>';
            $this->add_render_attribute('items_more_less_wrapper'.$unique_id, 'data-reverse', 'true');
            $more_less_items[] = '<span '.$this->get_render_attribute_string('items_more_less'.$unique_id).'>'.'<span class="elementor-item">'.$less_text.'</span>'.'</span>';
        }

        return '<li '.$this->get_render_attribute_string('items_more_less_wrapper'.$unique_id).'>'.implode('', $more_less_items).'</li>';

    }
    public function etheme_all_departments_limit_items( $items, $args ) {
        if ( $this->hidden_subitem_counter < 1) return $items;

        return $items . $this->render_more_less_item($this->hidden_subitem_counter);

//        return $items . '<li class="menu-item show-more"' . ( $show_less ? ' data-reverse="true"' : '' ) . '><a>' . esc_html__( 'Show more', 'xstore-core' ) . '<i class="et-icon et-down-arrow"></i></a>' .
//            ( $show_less ? '<a>' . esc_html__( 'Show less', 'xstore-core' ) . '<i class="et-icon et-up-arrow"></i></a>' : '' ) . '</li>';
    }

}
