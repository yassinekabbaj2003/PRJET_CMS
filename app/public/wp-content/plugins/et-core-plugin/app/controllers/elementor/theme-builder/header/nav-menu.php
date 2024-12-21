<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\Header;

use ETC\App\Classes\Elementor;

/**
 * Navigation Menu widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Nav_Menu extends Menu_Skeleton {

    protected $start_depth = 0;

	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'theme-etheme_nav_menu';
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
		return __( 'Nav Menu', 'xstore-core' );
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

        parent::register_controls();

        $this->start_injection( [
            'type' => 'control',
            'at'   => 'after',
            'of'   => 'align_items',
        ] );

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
                ]
            );
        }

        $this->end_injection();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 5.2
     * @access protected
     */
    protected function render() {
        parent::init_attributes();

        parent::menu_wrapper_start(false);

        // to disable theme scripts and styles
        add_filter('menu_item_mega_menu', '__return_false');

        add_action('etheme_after_menu_item', array($this, 'render_separator_items'));
            $this->render_inner_content();
        remove_action('etheme_after_menu_item', array($this, 'render_separator_items'));

        remove_filter('menu_item_mega_menu', '__return_false');

        parent::menu_wrapper_end(false);

        if ( did_action('menu_item_mega_menu_assets_load') ) {
            wp_enqueue_script('etheme_elementor_mega_menu');
        }
    }

    protected function render_inner_content()
    {
        $settings = $this->get_settings_for_display();
        echo $this->render_wp_menu($settings['wp_menu'], array(
            'type' => 'horizontal',
            'handle_overlay_classes' => $settings['hover_overlay'],
            'class' => array('dropdowns-'.$settings['dropdown_align'])
        ));
    }

    public function render_separator_items($depth) {
        if ( $depth == 0 ) {
            $settings = $this->get_settings_for_display();
            $this->render_separator($settings);
        }
    }
}
