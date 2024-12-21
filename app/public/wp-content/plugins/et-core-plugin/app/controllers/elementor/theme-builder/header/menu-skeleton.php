<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Menu skeleton for multi-purposes of menus using cases (mega-menu, menu).
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Menu_Skeleton extends \Elementor\Widget_Base {

    protected $start_depth = 0;
    protected $nav_menu_index = 1;

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
		return 'theme-etheme_menu_skeleton';
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
		return __( 'Menu Skeleton', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-menu';
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
        return [ 'woocommerce', 'header', 'menu', 'nav', 'navigation', 'dropdown', 'item', 'link', 'shop', 'store', 'button', 'canvas', 'aside' ];
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
        $styles = ['etheme-elementor-menu'];
//        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
//            $styles[] = 'etheme-mega-menu'; // for correct visual in editor
//        }
        return $styles;
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

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'menu_class',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'menu',
                'prefix_class' => 'etheme-elementor-',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'align_items',
            [
                'label' => esc_html__( 'Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'xstore-core' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'xstore-core' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'xstore-core' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Stretch', 'xstore-core' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'prefix_class' => 'etheme-elementor-nav-menu__align-',
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        );

        $this->get_hover_effects('main');

        $this->add_control(
            'separator_type',
            [
                'label' => __( 'Separator Type', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'none',
                'options' => [
                    'none' => __( 'Without', 'xstore-core' ),
                    'icon' => __( 'Icon', 'xstore-core' ),
                    'symbol' => __( 'HTML special symbol', 'xstore-core' ),
                    'image' => __( 'Image', 'xstore-core' ),
                    'text' => __( 'Custom Text', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'separator_selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'separator_icon',
                'skin' => 'inline',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-star',
                ],
                'label_block' => false,
                'condition' => ['separator_type' => 'icon'],
            ]
        );

        $this->add_control(
            'separator_symbol',
            [
                'label' => __( 'Symbol', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'star',
                'options' => [
                    'star' => sprintf(__( 'Star - %s', 'xstore-core' ), '&#9734;'),
                    'star-filled' => sprintf(__( 'Star Filled - %s', 'xstore-core' ), '&#9733;'),
                    'snowflake' => sprintf(__( 'Snowflake - %s', 'xstore-core' ), '&#10052;'),
                    'diamond' => sprintf(__( 'Diamond - %s', 'xstore-core' ), '&#10070;'),
                    'circle-dot' => sprintf(__( 'Circle - %s', 'xstore-core' ), '&#9737;'),
                    'v-separator' => sprintf(__('Vertical separator - %s', 'xstore-core'), '&#10072;'),
                ],
                'condition' => ['separator_type' => 'symbol'],
            ]
        );

        $this->add_control(
            'separator_image',
            [
                'label'   => esc_html__( 'Image', 'xstore-core' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['separator_type' => 'image'],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'         => 'separator_image_size',
                'label'        => __( 'Image Size', 'xstore-core' ),
                'default'      => 'full',
                'condition' => [
                    'separator_type' => 'image',
                    'separator_image[url]!' => ''
                ],
            ]
        );

        $this->add_control(
            'separator_text',
            [
                'label' => esc_html__('Custom Text', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('AB', 'xstore-core'),
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );

        $is_rtl = is_rtl();

        $this->add_control(
            'dropdown_indicator',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Dropdown Indicator', 'xstore-core' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'menu_item_icon',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-down-arrow',
                ],
                'recommended' => [
                    'xstore-icons' => [
                        'down-arrow',
                    ],
                    'fa-solid' => [
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                        'caret-down',
                        'caret-square-down',
                    ],
                    'fa-regular' => [
                        'caret-square-down',
                    ],
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'menu_item_icon_active',
            [
                'label' => esc_html__( 'Active Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_active',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-up-arrow',
                ],
                'recommended' => [
                    'xstore-icons' => [
                        'up-arrow',
                    ],
                    'fa-solid' => [
                        'chevron-up',
                        'angle-up',
                        'angle-double-up',
                        'caret-up',
                        'caret-square-up',
                    ],
                    'fa-regular' => [
                        'caret-square-up',
                    ],
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown',
            [
                'label' => esc_html__( 'Dropdown', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'dropdown_animation',
            [
                'label' => esc_html__( 'Dropdown Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fadeInUp',
                'options' => array_merge(
                    array('' => esc_html__( 'Default', 'xstore-core' )),
                    $this->get_dropdown_animations()
                ),
                'selectors' => [
                    '{{WRAPPER}}' => '--dropdown-animation-name: etheme-elementor-menu-dropdown-{{VALUE}};',
                ],
            ]
        );

//        $this->add_control(
//            'mega_dropdown_animation',
//            [
//                'label' => esc_html__( 'Mega Menus Animation', 'xstore-core' ),
//                'type' => \Elementor\Controls_Manager::SELECT,
//                'default' => 'fadeInUp',
//                'options' => array_merge(
//                    array('' => esc_html__( 'Default', 'xstore-core' )),
//                    $this->get_dropdown_animations()
//                ),
//                'selectors' => [
//                    '{{WRAPPER}} .item-design-mega-menu' => '--dropdown-animation-name: etheme-elementor-menu-dropdown-{{VALUE}};',
//                ],
//            ]
//        );

        $this->add_control(
            'dropdown_align',
            [
                'label' => esc_html__( 'Dropdown Alignment', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => esc_html__( 'Left', 'xstore-core' ),
                    'right' => esc_html__( 'Right', 'xstore-core' ),
                ],
            ]
        );

        $this->add_control(
            'subdmenu_indicator_heading',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Dropdown Indicator', 'xstore-core' ),
                'separator' => 'before',
            ]
        );

        $open_sublist_arrow_side = $is_rtl ? 'left' : 'right';
        $close_sublist_arrow_side = $is_rtl ? 'right' : 'left';

        $subitem_icons_recommended = [
            'xstore-icons' => [
                $open_sublist_arrow_side.'-arrow',
                $open_sublist_arrow_side.'-arrow-2',
            ],
            'fa-solid' => [
                'chevron-'.$open_sublist_arrow_side,
                'angle-'.$open_sublist_arrow_side,
                'angle-double-'.$open_sublist_arrow_side,
                'caret-'.$open_sublist_arrow_side,
                'caret-square-'.$open_sublist_arrow_side,
            ],
            'fa-regular' => [
                'caret-square-'.$open_sublist_arrow_side,
            ],
        ];
        $this->add_control(
            'menu_subitem_icon',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-'.$open_sublist_arrow_side.'-arrow',
                ],
                'recommended' => $subitem_icons_recommended,
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $subitem_icons_recommended['xstore-icons'] = array_merge($subitem_icons_recommended['xstore-icons'], array(
            $close_sublist_arrow_side.'-arrow',
            $close_sublist_arrow_side.'-arrow-2',
        ));

        $subitem_icons_recommended['fa-solid'] = array_merge($subitem_icons_recommended['fa-solid'], array(
            'chevron-'.$close_sublist_arrow_side,
            'angle-'.$close_sublist_arrow_side,
            'angle-double-'.$close_sublist_arrow_side,
            'caret-'.$close_sublist_arrow_side,
            'caret-square-'.$close_sublist_arrow_side,
        ));
        $subitem_icons_recommended['fa-regular'] = array_merge($subitem_icons_recommended['fa-regular'], array(
            'caret-square-'.$close_sublist_arrow_side,
        ));
        $this->add_control(
            'menu_subitem_icon_active',
            [
                'label' => esc_html__( 'Active Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_active',
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-'.$open_sublist_arrow_side.'-arrow',
                ],
                'recommended' => $subitem_icons_recommended,
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

//        $this->get_hover_effects('dropdown');

        $this->add_control(
            'hover_overlay',
            [
                'label' => __( 'Overlay on hover', 'xstore-core' ),
                'description' => __('Activate this option to add content overlay on menu items with Mega Menu content.', 'xstore-core'),
                'separator' => 'before',
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hover_overlay_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.elementor-location-header:has(.elementor-element.elementor-element-{{ID}} .add-overlay-body-on-hover:hover)' => '--hover-overlay-color: {{VALUE}};',
                ],
                'condition' => [
                    'hover_overlay!' => ''
                ]
            ]
        );

        $this->end_controls_section();

        do_action('etheme_elementor_menu_before_style');

//        $this->start_controls_section(
//            'section_general_style',
//            [
//                'label' => __( 'General', 'xstore-core' ),
//                'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
//            ]
//        );
//
//
//
//        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => __( 'Menu Items', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label' => __( 'Space Between Items', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--menu-item-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent',
            ]
        );

        $this->start_controls_tabs( 'tabs_item_style' );

        $this->start_controls_tab(
            'tab_item_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'item_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            [
                'label' => __('Hover', 'xstore-core'),
            ]
        );

        $this->add_control(
            'item_color_hover',
            [
                'label' => __('Text Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent):hover > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_color_hover',
            [
                'label' => __('Background Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item:hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent):hover > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_pointer_hover',
            [
                'label' => __('Pointer Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent:not(.e--pointer-framed) .elementor-item:before,
                {{WRAPPER}} .etheme-elementor-nav-menu-item-parent:not(.e--pointer-framed) .elementor-item:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent.e--pointer-framed .elementor-item:before,
                {{WRAPPER}} .etheme-elementor-nav-menu-item-parent.e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'main_pointer!' => ['none', 'text'],
                ],
            ]
        );

        $this->add_control(
            'item_border_color_hover',
            [
                'label' => __('Border Color', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent):hover > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'item_border_border!' => ''
                ]
            ]
        );

        $this->end_controls_tab();

        if ( apply_filters('etheme_elementor_menu_item_active_options', true) ) {

            $this->start_controls_tab(
                'tab_item_active',
                [
                    'label' => __('Active', 'xstore-core'),
                ]
            );

            $this->add_control(
                'item_color_active',
                [
                    'label' => __('Text Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active' => 'fill: {{VALUE}}; color: {{VALUE}};',
                        '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active) > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'item_background_color_active',
                [
                    'label' => __('Background Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active' => 'background: {{VALUE}};',
                        '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active) > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'item_pointer_active',
                [
                    'label' => __('Pointer Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent:not(.e--pointer-framed) .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .etheme-elementor-nav-menu-item-parent:not(.e--pointer-framed) .elementor-item.elementor-item-active:after' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent.e--pointer-framed .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .etheme-elementor-nav-menu-item-parent.e--pointer-framed .elementor-item.elementor-item-active:after' => 'border-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'main_pointer!' => ['none', 'text'],
                    ],
                ]
            );

            $this->add_control(
                'item_border_color_active',
                [
                    'label' => __('Border Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} li:has(> .etheme-elementor-nav-menu-item-parent .elementor-item.elementor-item-active) > .etheme-elementor-nav-menu-item-parent .elementor-item' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'item_border_border!' => ''
                    ]
                ]
            );

            $this->end_controls_tab();

        }

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-parent .elementor-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator_style',
            [
                'label' => __( 'Separator', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'separator_type!' => 'none'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'separator_typography',
                'selector' => '{{WRAPPER}} .etheme-elementor-nav-menu-item-separator',
                'condition' => [
                    'separator_type' => ['icon', 'symbol', 'text']
                ]
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-separator' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'separator_type' => ['icon', 'symbol', 'text']
                ]
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label' => __( 'Size proportion', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    'separator_type' => ['image']
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-separator' => '--menu-item-icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'separator_type' => ['image']
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu-item-separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown_item_style',
            [
                'label' => __( 'Dropdown Items', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_item_typography',
                'selector' => '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>li>.etheme-elementor-nav-menu-item, {{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown',
            ]
        );

        $this->start_controls_tabs( 'tabs_dropdown_item_style' );

        $this->start_controls_tab(
            'tab_dropdown_item_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'dropdown_item_color',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>li>.etheme-elementor-nav-menu-item' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_item_bg_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>li>.etheme-elementor-nav-menu-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
//
        $this->start_controls_tab(
            'tab_dropdown_item_hover',
            [
                'label' => __( 'Hover', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'dropdown_item_color_hover',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555',
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>li>.etheme-elementor-nav-menu-item:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_item_bg_color_hover',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>li>.etheme-elementor-nav-menu-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dropdown_item_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'dropdown_item_color_active',
            [
                'label' => __( 'Text Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555',
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>.current-menu-item>a' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_item_bg_color_active',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-design-dropdown .nav-sublist-dropdown ul>.current-menu-item>a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => __( 'Padding', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu--dropdown, {{WRAPPER}} .nav-sublist-dropdown' => '--menu-sublist-padding-top: {{TOP}}{{UNIT}}; --menu-sublist-item-padding-top: {{TOP}}{{UNIT}}; --menu-sublist-padding-right: {{RIGHT}}{{UNIT}}; --menu-sublist-padding-bottom: {{BOTTOM}}{{UNIT}}; --menu-sublist-item-padding-bottom: {{BOTTOM}}{{UNIT}}; --menu-sublist-padding-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_item_min_height',
            [
                'label' => __( 'Min-Height', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-elementor-nav-menu--dropdown, {{WRAPPER}} .nav-sublist-dropdown' => '--menu-sublist-item-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dropdown_style',
            [
                'label' => __( 'Dropdown', 'xstore-core' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dropdown_width',
            [
                'label' => __( 'Width', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1600,
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
                    'unit' => 'em'
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--menu-sublist-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_bg_color',
            [
                'label' => __( 'Background Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--et-sublist-background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .etheme-elementor-nav-menu--dropdown, {{WRAPPER}} .nav-sublist-dropdown, {{WRAPPER}} .nav-sublist ul',
                'separator' => 'before',
                'fields_options' => [
                    'width' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}}' => '--menu-sublist-top-border: {{TOP}}{{UNIT}};'
                        ],
                    ],
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'border-color: {{VALUE}}',
                            '{{WRAPPER}}' => '--et-sublist-border-color: {{VALUE}}',
                        ],
                    ]
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'selector' => '{{WRAPPER}} .etheme-elementor-nav-menu--dropdown, {{WRAPPER}} .nav-sublist-dropdown, {{WRAPPER}} .nav-sublist ul',
            ]
        );

        $this->add_control(
            'dropdown_border_radius',
            [
                'label' => __( 'Border Radius', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--et-sublist-border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public function get_hover_effects($prefix = '') {
        $first_level = $prefix == 'main';
        $this->add_control(
            $prefix . '_pointer',
            [
                'label' => esc_html__( 'Hover Effect', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $first_level ? 'underline' : 'right',
                'options' => array_merge(
                        array(
                            '' => esc_html__( 'None', 'xstore-core' )
                        ),
                        ($first_level ? array(
                                'underline' => esc_html__( 'Underline', 'xstore-core' ),
                                'overline' => esc_html__( 'Overline', 'xstore-core' )
                            ) : array(
                                'right' => esc_html__( 'Right', 'xstore-core' ),
                                'left' => esc_html__( 'Left', 'xstore-core' )
                            )
                        ),
                        array(
                            'double-line' => esc_html__( 'Double Line', 'xstore-core' ),
                            'framed' => esc_html__( 'Framed', 'xstore-core' ),
                            'background' => esc_html__( 'Background', 'xstore-core' ),
                            'text' => esc_html__( 'Text', 'xstore-core' )
                        )
                    ),
                'style_transfer' => true,
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_animation_line',
            [
                'label' => esc_html__( 'Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'fade' => 'Fade',
                    'slide' => 'Slide',
                    'grow' => 'Grow',
                    'drop-in' => 'Drop In',
                    'drop-out' => 'Drop Out',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    $prefix . '_pointer' => [ 'underline', 'right', 'overline', 'left', 'double-line' ],
                ],
            ]
        );

        $this->add_control(
            $prefix . '_animation_framed',
            [
                'label' => esc_html__( 'Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'draw' => 'Draw',
                    'corners' => 'Corners',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    $prefix . '_pointer' => 'framed',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_animation_background',
            [
                'label' => esc_html__( 'Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => 'Fade',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'sweep-left' => 'Sweep Left',
                    'sweep-right' => 'Sweep Right',
                    'sweep-up' => 'Sweep Up',
                    'sweep-down' => 'Sweep Down',
                    'shutter-in-vertical' => 'Shutter In Vertical',
                    'shutter-out-vertical' => 'Shutter Out Vertical',
                    'shutter-in-horizontal' => 'Shutter In Horizontal',
                    'shutter-out-horizontal' => 'Shutter Out Horizontal',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    $prefix . '_pointer' => 'background',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_animation_text',
            [
                'label' => esc_html__( 'Animation', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grow',
                'options' => [
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'sink' => 'Sink',
                    'float' => 'Float',
                    'skew' => 'Skew',
                    'rotate' => 'Rotate',
                    'none' => 'None',
                ],
                'condition' => [
                    'layout!' => 'dropdown',
                    $prefix . '_pointer' => 'text',
                ],
            ]
        );
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {}

    public function init_attributes() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'item-separator', [
            'class' => 'etheme-elementor-nav-menu-item-separator-wrapper'
        ]);

        $this->add_render_attribute( 'menu-item-separator', [
            'class' => [
                'etheme-elementor-nav-menu-item-separator',
                'etheme-elementor-nav-menu-item',
                'etheme-elementor-nav-menu-item-parent',
                'etheme-elementor-nav-menu-item-icon',
            ]
        ]);

        $this->add_render_attribute( 'main-menu', 'class', [
            'etheme-elementor-nav-menu--main',
        ] );

        $this->add_render_attribute( 'main-menu-inner', 'class', [
            'etheme-elementor-nav-menu',
            $settings['layout']
        ] );

//        if ( $settings['dropdown_align'] != 'left' ) {
        $this->add_render_attribute( 'main-menu-inner', 'class', 'dropdowns-'.$settings['dropdown_align']);
//        }

        $this->add_render_attribute( 'main-menu-inner', 'id', $this->get_id() );

        $this->add_render_attribute( 'item-icon', 'class', [
            'etheme-elementor-nav-menu-item-element',
            'etheme-elementor-nav-menu-item-icon'
        ] );

        $this->add_render_attribute( 'item-label', 'class', [
            'etheme-elementor-nav-menu-item-label',
        ] );
    }

    public function menu_wrapper_start($with_ul = true) {
        ?>
        <nav <?php $this->print_render_attribute_string( 'main-menu' ); ?>>

        <?php if ( $with_ul ) : ?>
            <ul <?php $this->print_render_attribute_string( 'main-menu-inner' ); ?>>
        <?php endif;
    }

    public function menu_wrapper_end($with_ul = true) {
        if ( $with_ul ) : ?>
            </ul>
        <?php endif; ?>

        </nav>
        <?php
    }

    public function render_separator($settings) {
        if ( $settings['separator_type'] == 'none' ) return;

        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && $migration_allowed;

        ?>
        <li <?php $this->print_render_attribute_string( 'item-separator' ); ?>>
            <span <?php $this->print_render_attribute_string( 'menu-item-separator' ); ?>>
            <?php
                switch ($settings['separator_type']) {
                    case 'icon':
                        ?>
                        <span class="etheme-elementor-nav-menu-item-icon">
                            <?php
                                if ( $is_new || $migrated ) {
                                    \Elementor\Icons_Manager::render_icon( $settings['separator_selected_icon'], [ 'aria-hidden' => 'true' ] );
                                } elseif ( ! empty( $settings['separator_icon'] ) ) {
                                    ?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
                                }
                            ?>
                        </span>
                        <?php
                        break;
                    case 'image':
                        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'separator_image_size', 'separator_image' );
                        break;
                    case 'text': ?>
                        <span> <?php echo $settings['separator_text']; ?> </span>
                        <?php
                        break;
                    case 'symbol':
                        switch ($settings['separator_symbol']) {
                            case 'star':
                                echo '&#9734;';
                            break;
                            case 'star-filled':
                                echo '&#9733;';
                            break;
                            case 'snowflake':
                                echo '&#10052;';
                                break;
                            case 'diamond':
                                echo '&#10070;';
                                break;
                            case 'circle-dot':
                                echo '&#9737;';
                                break;
                            case 'v-separator':
                                echo '&#10072;';
                                break;
                        }
                        break;
                }
            ?>
        </span>
    </li>
        <?php
    }

    public function render_wp_menu($menu, $args = array()) {
        $args = wp_parse_args( $args, array(
            'type' => 'vertical',
            'class' => array(),
            'handle_classes' => true,
            'handle_overlay_classes' => false,
            'handle_animation_classes' => true,
            'handle_arrows' => true,
        ) );
        $available_menus = Elementor::get_available_menus();

        if ( ! $available_menus ) {
            return;
        }

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && !defined('DOING_ETHEME_AJAXIFY')) {
            define('DOING_ETHEME_AJAXIFY', true); // needs for preventing double menu item images
        }

        $args['class'][] = $args['type'];

        $menu_args = [
            'echo' => false,
            'menu' => $menu,
            'menu_class' => 'etheme-elementor-nav-menu '.implode(' ', $args['class']),
            'menu_id' => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
            'fallback_cb' => '__return_empty_string',
            'container' => '',
        ];

        if ( class_exists('ETheme_Navigation')) {
            $menu_args['walker'] = new \ETheme_Navigation;
        }

        // Add custom filter to handle Nav Menu HTML output.
        if ( $args['handle_classes'] )
            add_filter('etheme_menu_link_classes', [$this, 'handle_link_classes'], 10, 3);

        if ( $args['handle_overlay_classes'] )
            add_filter('nav_menu_css_class', [$this, 'handle_link_overlay_classes'], 10, 3);

        if ( $args['handle_animation_classes'] )
            add_filter('etheme_menu_link_classes', [$this, 'handle_link_animation_classes'], 10, 3);
//                                add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_tabindex' ], 10, 4 );
//                                add_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
        if ( $args['handle_arrows'] )
            add_filter('etheme_nav_menu_item_inner', [$this, 'handle_sub_menu_arrows'], 10, 3);

        add_filter('etheme_nav_menu_item_inner', [$this, 'handle_sub_menu_item'], 15, 3);
        add_filter( 'nav_menu_item_id', '__return_empty_string' );

        // General Menu.
        $menu_html = wp_nav_menu( $menu_args );

        // Remove all our custom filters.
        if ( $args['handle_classes'] )
            remove_filter('etheme_menu_link_classes', [$this, 'handle_link_classes']);

        if ( $args['handle_overlay_classes'] )
            remove_filter('nav_menu_css_class', [$this, 'handle_link_overlay_classes']);

        if ( $args['handle_animation_classes'] )
            remove_filter('etheme_menu_link_classes', [$this, 'handle_link_animation_classes'], 10, 3);
//                                remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_tabindex' ] );
//                                remove_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );

        if ( $args['handle_arrows'] )
            remove_filter('etheme_nav_menu_item_inner', [$this, 'handle_sub_menu_arrows'], 10, 3);

        remove_filter('etheme_nav_menu_item_inner', [$this, 'handle_sub_menu_item'], 15, 3);
        remove_filter( 'nav_menu_item_id', '__return_empty_string' );

        return $menu_html;
    }

    protected function get_nav_menu_index() {
        return $this->nav_menu_index++;
    }

    public function get_dropdown_animations() {
        return [
            'none' => esc_html__('Without', 'xstore-core'),
            'fadeIn' => 'fadeIn',
            'fadeInUp' => 'fadeInUp',
            'zoomIn' => 'zoomIn',
//            'dropdown' => 'Dropdown', // -
//            'dropdown-top' => 'Dropdown 2', // -
            'fadeInLeft' => 'fadeInLeft',
            'fadeInRight' => 'fadeInRight',
        ];
    }


    public function get_link_animation_classes($level = 0) {
        $settings = $this->get_settings_for_display();
        $prefix = $level < 1 ? 'main' : 'dropdown_item';
        $classes = array();
        if ( !!$settings[$prefix.'_pointer'] ) :
            $classes[] = 'e--pointer-' . $settings[$prefix.'_pointer'];
            foreach ( $settings as $key => $value ) :
                if ( 0 === strpos( $key, $prefix.'_animation' ) && $value ) :
                    $classes[] = 'e--animation-' . $value;
                    break;
                endif;
            endforeach;
        endif;
        return $classes;
    }

    public function handle_link_classes( $classes, $li_classes, $depth ) {
        $classes[] = 'etheme-elementor-nav-menu-item';
        if ( ($this->start_depth + $depth) < 1 ) {
            $classes[] = 'etheme-elementor-nav-menu-item-parent';
        }
        return $classes;
    }

    public function handle_link_on_click_classes( $classes, $item, $args ) {
        if (in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'dropdown-click';
            wp_enqueue_script('etheme_elementor_mega_menu');
        }
        return $classes;
    }

    public function handle_link_overlay_classes( $classes, $item, $args ) {
        if ( in_array('item-design-mega-menu', $classes) ) {
//        if (!!$this->get_settings_for_display('hover_overlay')) {
            $classes[] = 'add-overlay-body-on-hover';
//        }
        }
        return $classes;
    }

    public function handle_link_animation_classes( $classes, $li_classes, $depth ) {
        if ( ($this->start_depth + $depth) < 1 )
            $classes = array_merge($classes, $this->get_link_animation_classes());
        return $classes;
    }

    public function handle_link_tabindex( $atts, $item, $args ) {
        $settings = $this->get_settings_for_display();

        // Add `tabindex = -1` to the links if it's a dropdown, for A11y.
        $is_dropdown = 'dropdown' === $settings['layout'];
        $is_dropdown = $is_dropdown || ( isset( $args->menu_type ) && 'dropdown' === $args->menu_type );

        if ( $is_dropdown ) {
            $atts['tabindex'] = '-1';
        }

        return $atts;
    }

    public function handle_sub_menu_classes( $classes ) {
        $classes[] = 'etheme-elementor-nav-menu';
        $classes[] = 'etheme-elementor-nav-menu--dropdown';
        $classes[] = 'vertical';

        return $classes;
    }

    public function handle_sub_menu_arrows($title, $nav_menu_css_classes, $depth) {
        if (in_array( 'menu-item-has-children', $nav_menu_css_classes ) || in_array('item-design-posts-subcategories', $nav_menu_css_classes) ) {
            ob_start();
            $this->render_menu_item_icon($this->get_settings_for_display(), ($this->start_depth + $depth), $title );
            $icon = ob_get_clean();
            if ( !empty($icon) )
                $title .= $icon;
        }
        return $title;
    }

    public function handle_sub_menu_item($title, $nav_menu_css_classes, $depth) {
        $classes = array('elementor-item');
        if ( in_array('current-menu-item', $nav_menu_css_classes) ) {
            $classes[] = 'elementor-item-active';
        }
        return '<span class="'.implode(' ', $classes).'">'.$title.'</span>';
    }

    public function render_menu_item_icon($settings, $level = 0, $title = '') {
        $icon_html = \Elementor\Icons_Manager::try_get_icon_html( ($level == 0 ? $settings['menu_item_icon'] : $settings['menu_subitem_icon']), [ 'aria-hidden' => 'true' ] );
        $icon_active_html = \Elementor\Icons_Manager::try_get_icon_html( ($level == 0 ? $settings['menu_item_icon_active'] : $settings['menu_subitem_icon_active']), [ 'aria-hidden' => 'true' ] );
        if ( empty($icon_html) && empty($icon_active_html) ) return;
        ?>
        <span class="etheme-elementor-nav-menu-item-arrow<?php echo (empty($title) ? ' only-child' : '') ?>">
            <span class="etheme-elementor-nav-menu-item-icon etheme-elementor-nav-menu-item-icon-opened"><?php echo $icon_active_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <span class="etheme-elementor-nav-menu-item-icon etheme-elementor-nav-menu-item-icon-closed"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
        </span>
        <?php
    }

    /**
     * Render Icon HTML.
     *
     * @param $settings
     * @return void
     *
     * @since 5.9
     *
     */
    protected function render_icon($settings, $prefix = '') {
        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings[$prefix.'icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
        if ( ! empty( $settings[$prefix.'icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
            <?php if ( $is_new || $migrated ) :
                \Elementor\Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
            else : ?>
                <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
            <?php endif;
        endif;
    }

    // This widget extends the woocommerce core widget and therefore needs to overwrite the widget-base core CSS config.
//    public function get_css_config() {
//        $widget_name = 'nav-menu';
//
//        $direction = is_rtl() ? '-rtl' : '';
//
//        $css_file_path = 'css/widget-' . $widget_name . $direction . '.min.css';
//
//        /*
//         * Currently this widget does not support custom-breakpoints in its CSS file.
//         * In order to support it, this widget needs to get the CSS config from the base-widget-trait.php.
//         * But to make sure that it implements the Pro assets-path due to the fact that it extends a Core widget.
//        */
//        return [
//            'key' => $widget_name,
//            'version' => ELEMENTOR_PRO_VERSION,
//            'file_path' => ELEMENTOR_PRO_ASSETS_PATH . $css_file_path,
//            'data' => [
//                'file_url' => ELEMENTOR_PRO_ASSETS_URL . $css_file_path,
//            ],
//        ];
//    }

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
