<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Mega Menu widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Mega_Menu extends Menu_Skeleton {

    protected $start_depth = 1;

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_mega_menu';
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
		return __( 'Mega Menu', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-mega-menu et-elementor-header-builder-widget-icon-only';
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
        return array_merge(parent::get_keywords(), [ 'mega', 'staticblock', 'template' ]);
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
    public function get_script_depends() {
        return array_merge(parent::get_script_depends(), ['etheme_elementor_mega_menu']);
    }

    /**
     * Register widget controls.
     *
     * @since 5.2
     * @access protected
     */
    protected function register_controls() {

        parent::register_controls();

        $this->update_control('layout',[
            'default' => 'horizontal'
        ]);

        $this->update_control('hover_overlay', [
            'default' => 'yes'
        ]);

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__( 'Items', 'xstore-core' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $data_sources = $this->get_saved_content_list();
        $default_data_source = array_key_first($data_sources);

        $repeater->add_control(
            'item_text',
            [
                'label' 		=>	__( 'Title', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'separator' => 'after',
                'default' => __( 'Menu item', 'xstore-core' ),
            ]
        );

        $repeater->add_control(
            'content_type',
            [
                'label' 		=>	__( 'Content Type', 'xstore-core' ),
                'type' 			=>	(count($data_sources) > 1 ? \Elementor\Controls_Manager::SELECT : \Elementor\Controls_Manager::HIDDEN),
                'options' => $data_sources,
                'default'	=> $default_data_source
            ]
        );

        $repeater->add_control(
            'etheme_mega_menu_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(esc_html__('Generate captivating Mega Menus effortlessly by navigating to Dashboard -> %s -> Add New. Each new Mega Menu mirrors a post, offering the flexibility to craft compelling content seamlessly through the website\'s page builder.'), '<a href="'.admin_url( 'edit.php?post_type=etheme_mega_menus' ).'" target="_blank">'.esc_html__('Mega Menus', 'xstore-core').'</a>'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'content_type' => 'etheme_mega_menu'
                ]
            ]
        );

        $repeater->add_control(
            'save_template_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => Elementor::get_saved_template_description(),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'content_type' => 'saved_template'
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
                    'content_type' => 'static_block'
                ]
            ]
        );

        $mega_menus = $this->get_saved_content('etheme_mega_menus');

        $repeater->add_control(
            'etheme_mega_menu',
            [
                'label' => __( 'Mega Menu', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $mega_menus,
                'default' => array_keys( $mega_menus )[0],
                'save_default' => true,
                'condition' => [
                    'content_type' => 'etheme_mega_menu'
                ],
            ]
        );

        $menus = Elementor::get_available_menus();

        if ( ! empty( $menus ) ) {
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
                    'default' => array_keys( $menus )[0],
                    'save_default' => true,
                    'condition' => [
                        'content_type' => 'wp_menu'
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
                    'condition' => [
                        'content_type' => 'wp_menu'
                    ],
                ]
            );
        }

        $saved_templates = $this->get_saved_content();

        $repeater->add_control(
            'saved_template',
            [
                'label' => __( 'Saved Template', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $saved_templates,
                'default' => 'select',
                'condition' => [
                    'content_type' => 'saved_template'
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
                    'content_type' => 'static_block'
                ],
            ]
        );

        $this->register_item_control($repeater);

        $stretch_submenus = apply_filters('etheme_elementor_menu_item_dropdown_stretch_options', true);

            $repeater->add_control(
                'stretch_dropdown',
                [
                    'label' => __('Stretch Dropdown', 'xstore-core'),
                    'type' => $stretch_submenus ? \Elementor\Controls_Manager::SWITCHER : \Elementor\Controls_Manager::HIDDEN,
                    'default' => $stretch_submenus ? 'yes' : '',
                    'separator' => 'before',
                    'condition' => [
                        'content_type!' => ['without', 'wp_menu']
                    ],
                ]
            );

            $repeater->add_control(
                'dropdown_content_width',
                [
                    'label' => __('Content width', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => array(
                        'boxed' => esc_html__('Boxed', 'xstore-core'),
                        'full-width' => esc_html__('Full-width', 'xstore-core'),
                    ),
                    'default' => 'boxed',
                    'condition' => [
                        'content_type!' => ['without', 'wp_menu'],
                        'stretch_dropdown!' => ''
                    ],
                ]
            );
//        }

//        if ( apply_filters('etheme_elementor_menu_item_dropdown_custom_width_options', false) ) {
            $repeater->add_responsive_control(
                'dropdown_content_width_custom',
                [
                    'label' => __( 'Width', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'vw', 'vh', 'em', 'rem', 'custom' ],
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => '--submenu-mega-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'content_type!' => ['without', 'wp_menu'],
                        'stretch_dropdown' => ''
                    ],
                ]
            );
//        }

        $this->add_control(
            'items',
            [
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'content_type'  => $default_data_source,
                    ],
                    [
                        'content_type'  => $default_data_source,
                    ],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_item_control($repeater) {
        $repeater->add_control(
            'item_element',
            [
                'label' 		=>	__( 'Extra element', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::SELECT,
                'separator' => 'before',
                'options' 		=>	[
                    'none' => esc_html__('None', 'xstore-core'),
                    'icon' => esc_html__('Icon', 'xstore-core'),
                    'image' => esc_html__('Image', 'xstore-core'),
                ],
                'default'		=> 'none'
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => ET_CORE_URL . 'app/assets/img/widgets/icon-list/house-icon.png'
                ],
                'condition' => [
                    'item_element' => 'image'
                ]
            ]
        );

        $repeater->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'thumbnail',
                'separator' => 'none',
                'condition' => [
                    'item_element' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'library' => 'xstore-icons',
                    'value' => 'et-icon et-star',
                ],
                'condition' => [
                    'item_element' => 'icon'
                ]
            ]
        );

        $repeater->add_control(
            'add_link',
            [
                'label' => __( 'Add link', 'xstore-core' ),
                'separator' => 'before',
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'xstore-core' ),
                'condition' => [
                    'add_link!' => ''
                ]
            ]
        );

        $repeater->add_control(
            'show_label',
            [
                'label' => __( 'Show Label', 'xstore-core' ),
                'separator' => 'before',
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'label_text',
            [
                'label' 		=>	__( 'Label Text', 'xstore-core' ),
                'type' 			=>	\Elementor\Controls_Manager::TEXT,
                'default' => __( 'HOT', 'xstore-core' ),
                'condition' => [
                    'show_label!' => ''
                ]
            ]
        );

        $repeater->add_control(
            'label_color',
            [
                'label' 	=> __( 'Label Color', 'xstore-core' ),
                'type' 		=> \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .etheme-elementor-nav-menu-item-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label!' => ''
                ]
            ]
        );

        $repeater->add_control(
            'label_bg_color',
            [
                'label' 	=> __( 'Label Background Color', 'xstore-core' ),
                'type' 		=> \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .etheme-elementor-nav-menu-item-label' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label!' => ''
                ]
            ]
        );
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {
        parent::init_attributes();

        parent::menu_wrapper_start();

            $this->render_inner_content();

        parent::menu_wrapper_end();
    }

    protected function render_inner_content($args = array())
    {

        $args = wp_parse_args($args, array(
            'item_classes' => array(),
            'item_limits' => 0
        ));

        $settings = $this->get_settings_for_display();

        $local_link = get_permalink( get_the_ID() );
        if ( is_tax() ) {
            global $wp_query;
            $obj        = $wp_query->get_queried_object();
            $local_link = get_term_link( $obj );
        }

        // to disable theme scripts and styles
        add_filter('menu_item_mega_menu', '__return_false');

        $parent_link_classes = array(
            // 'elementor-item',
            'etheme-elementor-nav-menu-item',
        );

        if ( $this->start_depth <= 1 ) {
            $parent_link_classes[] = 'etheme-elementor-nav-menu-item-parent';
        }

        $parent_link_classes = array_merge($parent_link_classes, $this->get_link_animation_classes());

                foreach ($settings['items'] as $item_index => $item) {
                    $repeater_setting_key = $this->get_repeater_setting_key( 'items', 'menu-items', $item_index );
                    $this->add_render_attribute( $repeater_setting_key.'main-menu-dropdown', 'class', [
                        'nav-sublist-dropdown',
                    ] );

//                    if ( $this->start_depth < 1 ) {
                        $this->add_render_attribute( $repeater_setting_key.'main-menu-dropdown', 'class', [
                            'etheme-elementor-nav-menu--dropdown',
                        ] );
//                    }

                    if ( isset($item['stretch_dropdown']) && !!$item['stretch_dropdown'] ) {
                        $this->add_render_attribute( $repeater_setting_key.'main-menu-dropdown', 'class', [
                            'etheme-elementor-nav-menu--dropdown-stretched',
                        ] );
                    }

                    $this->add_render_attribute( $repeater_setting_key, [
                        'class' => array_merge([
                            'elementor-repeater-item-' . $item['_id']
                        ], $args['item_classes'])
                    ]);

                    if ( $args['item_limits'] > 0 && $item_index >= $args['item_limits'] ) {
                        $this->add_render_attribute( $repeater_setting_key, 'class', 'hidden');
                    }

                    $this->add_render_attribute( $repeater_setting_key.'item-text', [
                        'class' => $parent_link_classes
                    ]);

                    $this->add_render_attribute( $repeater_setting_key.'item-text-inner', [
                        'class' => ['elementor-item']
                    ]);

                    $tag = 'span';

                    if ( $item['add_link'] && ! empty( $item['link']['url'] ) ) {
                        $tag = 'a';
                        $this->add_link_attributes( $repeater_setting_key.'item-text', $item['link'] );
                        $link = $item['link']['url'];

                        $current_item_class = $this->get_current_menu_item_class( $item['link']['url'] );
                        if ( $current_item_class )
                            $this->add_render_attribute( $repeater_setting_key.'item-text-inner', 'class', $current_item_class);
//                        if ( $local_link && $link != '#' && strpos( $local_link, substr( $link, 0, - 2 ) ) !== false ) {
//                            $this->add_render_attribute( $repeater_setting_key.'item-text-inner', 'class', 'elementor-item-active');
//                        }
                    }

                    $simple_list = true;
                    $menu_item_extra_class = false;

                    $menu_item_dropdown_html = '';

                        switch ($item['content_type']) {
                            case 'wp_menu':

                                $menu_html = $this->render_wp_menu($item[$item['content_type']]);

                                if ( !empty( $menu_html ) ) {
                                    // PHPCS - escaped by WordPress with "wp_nav_menu"
                                    $menu_item_dropdown_html = $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    $menu_item_extra_class = 'item-design-dropdown';
                                }

                                break;
                            case 'global_widget':
                            case 'saved_template':
                            case 'etheme_mega_menu':
                                if (!empty($item[$item['content_type']]) && $item[$item['content_type']] != 'select'):
                                    //								echo \Elementor\Plugin::$instance->frontend->get_builder_content( $item[$item['content_type']], true );
                                    if ( $item['content_type'] == 'etheme_mega_menu' ) {
                                        $posts = get_posts(
                                            [
                                                'name' => $item[$item['content_type']],
                                                'post_type' => 'etheme_mega_menus',
                                                'posts_per_page' => '1',
                                                'fields' => 'ids'
                                            ]
                                        );
                                    }
                                    else {
                                        $posts = get_posts(
                                            [
                                                'name' => $item[$item['content_type']],
                                                'post_type' => 'elementor_library',
                                                'posts_per_page' => '1',
                                                'tax_query' => [
                                                    [
                                                        'taxonomy' => 'elementor_library_type',
                                                        'field' => 'slug',
                                                        'terms' => str_replace(array('global_widget', 'saved_template'), array('widget', 'section'), $item['content_type']),
                                                    ],
                                                ],
                                                'fields' => 'ids'
                                            ]
                                        );
                                    }
                                    if (!isset($posts[0]) || !$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($posts[0])) {
                                        $menu_item_dropdown_html = '<div class="col-md-12">'.esc_html__('We have imported template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core').'</div>';
                                    } else {
                                        $simple_list = false;
                                        $menu_item_dropdown_html = $content;
                                        $menu_item_extra_class = 'item-design-mega-menu';
                                    }
                                endif;
                                break;
                            case 'static_block':
                                ob_start();
                                Elementor::print_static_block($item[$item['content_type']]);
                                $menu_item_dropdown_html = ob_get_clean();
                                if ( !empty($menu_item_dropdown_html) ) {
                                    $simple_list = false;
                                    $menu_item_extra_class = 'item-design-mega-menu';
                                }
                                break;
                        }

                        if ( $menu_item_extra_class ) {
                            $this->add_render_attribute($repeater_setting_key, [
                                'class' => [
                                    $menu_item_extra_class,
                                ]
                            ]);
                            if (!$simple_list && !!$settings['hover_overlay']) {
                                $this->add_render_attribute($repeater_setting_key, [
                                    'class' => 'add-overlay-body-on-hover'
                                ]);
                            }
                        }
                        if ( !$simple_list ) {
                            $this->add_render_attribute( $repeater_setting_key.'main-menu-dropdown', 'class', [
                                'etheme-elementor-nav-menu--dropdown-mega',
                            ] );
                        }

                    ?>
                    <li <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>

                        <?php $this->render_item_inner($item, $settings, $tag, !empty($menu_item_dropdown_html), $repeater_setting_key); ?>

                        <?php if ( !empty($menu_item_dropdown_html) ) { ?>

                            <div <?php $this->print_render_attribute_string( $repeater_setting_key.'main-menu-dropdown' ); ?>>
                                <div class="container<?php echo (isset($item['dropdown_content_width']) && $item['dropdown_content_width'] == 'full-width') ? ' full-width' : ''; ?>">
                                    <?php echo $menu_item_dropdown_html; ?>
                                </div>
                            </div>

                        <?php } ?>

                    </li>
                    <?php
                    $this->render_separator($settings);
                }

        remove_filter('menu_item_mega_menu', '__return_false');
    }

    public function add_attributes_to_item_dropdown( $key, $classes, $item_dropdown_id, $display_index, $has_dropdown_content = false, $title = '' ) {
        $this->add_render_attribute( $key, [
            'id' => $item_dropdown_id,
            'class' => $classes,
            'role' => 'button',
            'data-tab-index' => $display_index,
            'tabindex' => 1 === $display_index ? '0' : '-1',
            'aria-haspopup' => $has_dropdown_content ? 'true' : 'false',
            'aria-expanded' => 'false',
            'aria-controls' => 'e-n-menu-content-' . $this->widget_number() . $display_index,
            'aria-label' => esc_html__( 'Expand: ', 'xstore-core' ) . $title,
        ] );
    }

    public function render_item_inner($item, $settings, $tag, $show_arrow, $repeater_setting_key = '', $extra_args = array()) {
        $extra_args = wp_parse_args($extra_args, array(
            'item_text_classes' => array(),
        ));
        ?>
        <<?php echo $tag; ?> <?php $this->print_render_attribute_string( $repeater_setting_key.'item-text' ); ?>>
        <span <?php $this->print_render_attribute_string( $repeater_setting_key.'item-text-inner' ); ?>>
                <?php
                if ( $item['item_element'] != 'none' ) { ?>

                    <span <?php $this->print_render_attribute_string( 'item-icon' ); ?>>
                            <?php
                            switch ($item['item_element']) {
                                case 'icon':
                                    $this->render_icon($item);
                                    break;
                                case 'image':
                                    echo \Elementor\Group_Control_Image_Size::print_attachment_image_html( $item );
                                    break;
                            }
                            ?>
                            </span>

                <?php }

                echo $item['item_text'] ? '<span class="'.implode(' ', $extra_args['item_text_classes']).'">'.$item['item_text'].'</span>' : '';

                if ( !empty($show_arrow) ) {
                    $this->render_menu_item_icon($settings, ($this->start_depth - 1), $item['item_text']);
                }

                if ( !!$item['show_label'] ) { ?>
                    <span <?php $this->print_render_attribute_string( 'item-label' ); ?>>
                            <?php echo $item['label_text']; ?>
                        </span>
                <?php } ?>
                </span>
        </<?php echo $tag; ?>>
        <?php
    }

    protected function get_saved_content( $term = 'section' ) {
        $saved_contents = $term == 'etheme_mega_menus' ? $this->get_post_template( 'mega_menu', 'etheme_mega_menus' ) : $this->get_post_template( $term );

        if ( count( $saved_contents ) > 0 ) {
            foreach ( $saved_contents as $saved_content ) {
//                $content_id             = $saved_content['id'];
//                $options[ $content_id ] = $saved_content['name'];
                $content_slug             = $saved_content['slug'];
                $options[ $content_slug ] = $saved_content['name'];
            }
        } else {
            $options['no_template'] = __( 'Nothing Found', 'xstore-core' );
        }
        return $options;
    }

    protected function get_post_template( $term = 'page', $type = 'elementor_library' ) {
        switch ($type) {
            case 'elementor_library':
                $posts = get_posts(
                    [
                        'post_type'      => $type,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                        'posts_per_page' => '-1',
                        'tax_query'      => [
                            [
                                'taxonomy' => 'elementor_library_type',
                                'field'    => 'slug',
                                'terms'    => $term,
                            ],
                        ],
                    ]
                );
                break;
            case 'etheme_mega_menus':
                $posts = get_posts(
                    array(
                        'post_type' => $type,
                        'order'          => 'ASC',
                        'posts_per_page' => '-1'
                    )
                );
                break;
        }

        $templates = [];
        foreach ( $posts as $post ) {
            $templates[] = [
                'id'   => $post->ID,
                'name' => $post->post_title,
                'slug' => $post->post_name
            ];
        }
        return $templates;
    }

    /**
     * Get all elementor page templates
     * Keep inheritance from Elementor class because we rewrite this function in itemshow Widget (extended from this one)
     *
     * @return array
     */
    protected function get_saved_content_list() {
        return array_merge(array(
                'without' => esc_html__('Without', 'xstore-core'),
            'etheme_mega_menu' => __( ' Mega Menus (Presets)', 'xstore-core' ),
            'wp_menu' => __( 'WordPress Menu', 'xstore-core' )
        ), Elementor::get_saved_content_list(array(
            'custom' => false,
            'global_widget' => false
        )));
    }

    protected function get_current_menu_item_class( $menu_link_url ) {
        $menu_link_url = trim( $menu_link_url );

        if ( str_contains( $menu_link_url, '#' ) ) {
            return 'e-anchor';
        }

        $permalink_url = get_query_var($this->get_id().'_cp', 'undefined');
        if ( $permalink_url == 'undefined' ) {
            $permalink_url = $this->get_permalink_for_current_page();
            set_query_var($this->get_id().'_cp', $permalink_url);
        }

        if ( empty( $menu_link_url ) || empty( $permalink_url ) ) {
            return '';
        }

        $permalink_array = $this->parse_url( $permalink_url );
        $menu_item_url_array = $this->parse_url( $menu_link_url );
        $has_equal_urls = $permalink_array === $menu_item_url_array;

        return $has_equal_urls ? 'elementor-item-active' : '';
    }

    public function parse_url( $url ) {
        $link_array = wp_parse_url( $url );

        return [
            'host'  => ! empty( $link_array['host'] ) ? str_replace( 'www.', '', $link_array['host'] ) : '',
            'path'  => ! empty( $link_array['path'] ) ? trim( $link_array['path'], '/' ) : '',
            'query' => ! empty( $link_array['query'] ) ? $link_array['query'] : '',
        ];
    }

    public function get_permalink_for_current_page() {
        if ( ! is_front_page() && is_home() ) {
            return get_post_type_archive_link( 'post' );
        } elseif ( is_front_page() && is_home() ) {
            return home_url();
        } elseif ( is_year() ) {
            return get_year_link( get_query_var( 'year' ) );
        } elseif ( is_month() ) {
            return get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
        } elseif ( is_day() ) {
            return get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $queried_object = get_queried_object();
            return get_term_link( $queried_object->term_id, $queried_object->taxonomy );
        } elseif ( is_author() ) {
            return get_author_posts_url( get_the_author_meta( 'ID' ) );
        } elseif ( is_search() ) {
            return get_search_link();
        } elseif ( is_archive() ) {
            return get_post_type_archive_link( get_post_type() );
        }

        return ! ( empty( get_the_permalink() ) ) ? get_the_permalink() : '';
    }
}
