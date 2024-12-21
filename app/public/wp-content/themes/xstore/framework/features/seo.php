<?php
/**
 * Built-in seo base functionality
 *
 * @package    seo.php
 * @since      9.0.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

class XStore_SEO {

    public static $instance = null;

    public static $enabled = false;
    public function init() {
        self::$enabled = get_theme_mod('et_seo_switcher', 0);
        if ( self::$enabled ) {
            add_filter('etheme_custom_metaboxes_tabs', function ($tabs) {
                $tabs['et_seo'] = array(
                    'id' => 'et_seo',
                    'title' => __('SEO', 'xstore'),
                    'fields' => array(
                        array(
                            'name' => esc_html__( 'Meta description', 'xstore' ),
                            'id' => ETHEME_PREFIX . 'meta_description',
                            'type' => 'textarea',
                        ),
                        array(
                            'name' => esc_html__( 'Meta keywords', 'xstore' ),
                            'id' => ETHEME_PREFIX . 'meta_keywords',
                            'type' => 'textarea',
                        ),
                        array(
                            'id'          => ETHEME_PREFIX .'og-image',
                            'name'        => esc_html__('Open Graph image', 'xstore'),
                            'desc' => esc_html__('Upload an image or enter an URL.', 'xstore'),
                            'type' => 'file',
                            'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
                        ),
                    )
                );
                return $tabs;
            });
        }
        add_action( 'wp_head', array($this, 'print_opengraph_tags'), 1);
	    add_action( 'wp', array($this, 'nofollow_pagination_urls'), 101);
    }

    /**
     * Output all possible meta tags based on global settings or local post/page/product settings
     * @return void
     */
    public function print_opengraph_tags() {
        global $post;
        if ( !self::$enabled )
            return;
        if ( ! $post || defined( 'WPSEO_VERSION' ) || is_home() || is_archive() || is_search() || is_paged() ) {
            return;
        } elseif (get_query_var('et_is-woocommerce-archive', false) && get_theme_mod( 'et_seo_noindex', 0 )){
        	echo esc_html($this->noidex_product_archives());
	        return;
        }

        // Fix warnings in php 7.2.x.
        setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

        $post_id = $post->ID;
	    printf(
	    	'%s %s %s %s %s %s %s %s %s %s',
		    $this->site_name(),
		    $this->title(),
		    $this->description(),
		    $this->tagline($post_id),
		    $this->keywords($post_id),
		    (get_query_var('et_is-woocommerce-archive', false) && get_theme_mod( 'et_seo_noindex', 0 )) ? $this->noidex_product_archives() : '',
		    $this->image($post_id),
		    $this->url($post_id),
		    $this->type(),
		    $this->fb_app_id()
	    );

        wp_reset_postdata();
    }

    /**
     * No-index meta tag
     *
     * @return string
     */
    public function noidex_product_archives() {
        $url = parse_url($_SERVER['REQUEST_URI']);
	    $html = '';
        if (isset($url['query'])){
	        $html .= "\n\t\t<!-- 8theme SEO v1.0.0 -->";
	        $html .= '<meta name="robots" content="noindex, nofollow">';
	        $html .= "\t\t<!-- 8theme SEO -->\n\n";
        }
	    return $html;
    }

    /**
     * Output the site name straight from the blog info.
     *
     * @return string
     */
    public function site_name() {
        return $this->og_tag( 'og:site_name', get_bloginfo( 'name' ) );
    }

    /**
     * Output post title.
     *
     * @link https://developers.facebook.com/docs/reference/opengraph/object-type/article/
     *
     * @return string
     */
    public function title() {
        return $this->og_tag( 'og:title', get_the_title() );
    }

    /**
     * Output post excerpt as description.
     *
     * @return string
     */
    public function description() {
        return $this->og_tag( 'og:description', get_the_excerpt() );
    }

    /**
     * Output site tagline as description.
     *
     * @return string
     */
    public function tagline($post_id) {
        $tagline = etheme_get_custom_field('meta_description', $post_id);
        if ( !$tagline )
            $tagline = get_theme_mod('et_seo_meta_description');
        return $this->og_tag( 'description', $tagline?$tagline:get_bloginfo( 'description' ), 'name' );
    }

    /**
     * Output keywords.
     *
     * @return string
     */
    public function keywords($post_id) {
        $keywords = etheme_get_custom_field('meta_keywords', $post_id);
        if ( !$keywords )
            $keywords = get_theme_mod('et_seo_meta_keywords');
        return $keywords ? $this->og_tag( 'keywords', $keywords ) : '';
    }

    /**
     * Output post thumbnail if any as image.
     *
     * @return string
     */
    public function image($post_id) {
        $post_thumbnail = etheme_get_custom_field('og-image', $post_id);
        if ( $post_thumbnail )
            return $this->og_tag( 'og:image', esc_url_raw( $post_thumbnail ) );

        $post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full' );
        if ( isset( $post_thumbnail[0] ) ) {
            return $this->og_tag( 'og:image', esc_url_raw( $post_thumbnail[0] ) );
        }

        return '';
    }

    /**
     * Output url.
     *
     * @link https://developers.facebook.com/docs/reference/opengraph/object-type/article/
     *
     * @return string
     */
    public function url($post_id) {
        return $this->og_tag( 'og:url', esc_url_raw( get_the_permalink($post_id) ) );
    }

    /**
     * Output the OpenGraph type.
     *
     * @link https://developers.facebook.com/docs/reference/opengraph/object-type/object/
     *
     * @return string
     */
    public function type() {
        if ( is_front_page() || is_home() ) {
            $type = 'website';
        } elseif ( is_singular() ) {
            $type = 'article';
        } else {
            // We use "object" for archives etc. as article doesn't apply there.
            $type = 'object';
        }

        return $this->og_tag( 'og:type', $type );
    }

    /**
     * Output facebook app id
     * @return string
     */
    public function fb_app_id() {
        $app_id = get_option( 'etheme_facebook_app_id', get_theme_mod( 'facebook_app_id', '' ) );

        return $app_id ? $this->og_tag( 'fb:app_id', $app_id ) : '';
    }
    /**
     * Output the OpenGraph meta tag.
     *
     * @param string $property OG property.
     * @param string $content Property content.
     *
     * @return string
     */
    public function og_tag( $property, $content, $type = 'property' ) {
        $property = (string) $property;
        $content  = (string) $content;
        if ( ! $content ) {
            return '';
        }

        return '<meta '.$type.'="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.0.3
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }

	/**
	 * Add nofollow for pagination urls
	 *
	 * @return void
	 */
	public function nofollow_pagination_urls() {
		if (get_theme_mod( 'et_seo_nofollow_pagination', 0 )){
			// add nofollow to pagination
			add_filter('paginate_links_output', function($r, $args){
				$r = str_replace('<a', '<a rel="nofollow"', $r);
				return $r;
			}, 10, 2);
		}
	}
}
$seo = new XStore_SEO();
$seo->init();