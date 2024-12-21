<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

/**
 * Archive Description Second widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Archive_Description_Second extends Archive_Description {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-archive-etheme_description_second';
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
		return 'eight_theme-elementor-icon et-elementor-archive-description et-elementor-product-builder-widget-icon-only';
	}

    public function get_title() {
        return esc_html__( 'Archive Second Description', 'xstore-core' );
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

        $this->remove_control('wc_style_warning');
    }

    protected function render() {
        if ( isset($_GET['et_ajax'] ) ) {
            add_filter('wp_doing_ajax', '__return_true'); // tweak for inline loading Elementor CSS
            add_action('etheme_output_shortcodes_inline_css', '__return_true');
        }
        global $wp_query;
        $category = $wp_query->get_queried_object();
        if ( isset($_GET['filter_cat']) ) {
            $cats = explode(',', $_GET['filter_cat']);
            $category_slug = end($cats );
            $category = get_term_by('slug', $category_slug, 'product_cat');
        }

        if ( property_exists( $category, 'term_id' ) && ! is_search() ) {
            $desc = get_term_meta( $category->term_id, '_et_second_description', true );
        } else {
            echo $this->render_placeholder_content();
            return;
        }

        if ( ! empty( $desc ) ) {
            echo '<div class="term-description et_second-description">' . do_shortcode( $desc ) . '</div>';
        }
        else {
            echo $this->render_placeholder_content();
        }
    }
}
