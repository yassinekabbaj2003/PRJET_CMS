<?php
namespace ETC\App\Controllers\Elementor\Theme_Builder\WooCommerce\Archive;

/**
 * Archive Description widget.
 *
 * @since      5.2
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Archive_Description extends \ElementorPro\Modules\Woocommerce\Widgets\Archive_Description {
    
	/**
	 * Get widget name.
	 *
	 * @since 5.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'woocommerce-archive-etheme_description';
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

    protected function render()
    {
        if (isset($_GET['et_ajax'])) {
            add_filter('wp_doing_ajax', '__return_true'); // tweak for inline loading Elementor CSS
            add_action('etheme_output_shortcodes_inline_css', '__return_true');
        }
        if ( isset($_GET['filter_cat']) ) {
            $this->render_description_by_filter($_GET['filter_cat']);
            return;
        }

        else {
            global $wp_query;
            $maybe_category = $wp_query->get_queried_object();
            $shop_banner = get_theme_mod('product_bage_banner', '');

            if ( ! property_exists( $maybe_category, 'term_id' ) && ! is_search() && $shop_banner != '' ) {
                echo '<div class="term-description">';
                echo do_shortcode( $shop_banner );
                echo '</div>';
                return;
            }
        }

        ob_start();
        parent::render();
        $description_content = ob_get_clean();
        if ( !empty($description_content) )
            echo $description_content;
        else
            echo $this->render_placeholder_content();
    }

    public function render_placeholder_content() {
        return apply_filters('elementor-woocommerce-archive-etheme_description_placeholder', '');
    }

    public function render_description_by_filter($cat_slug) {
        $cats = explode(',', $cat_slug);
        $category_slug = end($cats );
        $category = get_term_by('slug', $category_slug, 'product_cat');
        if ( $category ) {
            /**
             * Filters the archive's raw description on taxonomy archives.
             *
             * @since 6.7.0
             *
             * @param string  $term_description Raw description text.
             * @param WP_Term $term             Term object for this taxonomy archive.
             */
            $term_description = apply_filters( 'woocommerce_taxonomy_archive_description_raw', $category->description, $category );

            if ( ! empty( $term_description ) ) {
                echo '<div class="term-description">' . wc_format_content( wp_kses_post( $term_description ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            else {
                echo $this->render_placeholder_content();
            }
        }
        else {
            global $wp_query;
            $cat = $wp_query->get_queried_object();
            $shop_banner = get_theme_mod('product_bage_banner', '');

            if ( ! property_exists( $cat, 'term_id' ) && ! is_search() && $shop_banner != '' ) {
                echo '<div class="term-description">';
                echo do_shortcode( $shop_banner );
                echo '</div>';
            }
            else {
                echo $this->render_placeholder_content();
            }
        }
    }
}
