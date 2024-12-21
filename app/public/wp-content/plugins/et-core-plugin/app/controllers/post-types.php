<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Create post type controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Post_Types extends Base_Controller{

	public $domain = 'xstore-core';

	public $upload_dir = null;

	/**
	 * Registered panels.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $post_args = NULL;

	/**
	 * Registered panels.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $tax_args = NULL;

	/**
	 * Register post args
	 *
	 * @return mixed|null|void
	 */
	public static function register_post_args() {

		if ( ! is_null( self::$post_args ) ) {
			return self::$post_args;
		}

        $theme_activated = function_exists('etheme_is_activated') && etheme_is_activated();
        $theme_activation_required = function_exists('etheme_activation_required') && etheme_activation_required();
		return self::$post_args = $theme_activated || !$theme_activation_required ? apply_filters( 'etc/add/post/args', array() ) : array();
	}

	/**
	 * Register taxonomies args
	 *
	 * @return mixed|null|void
	 */
	public static function register_taxonomies_args() {

		if ( ! is_null( self::$tax_args ) ) {
			return self::$tax_args;
		}

		return self::$tax_args = apply_filters( 'etc/add/tax/args', array() );
	}


	public function hooks() {

		add_action( 'init', array( $this, 'create_custom_post_types' ), 1 );
		add_action( 'init', array( $this, 'create_taxonomies' ), 1 );
		add_action('init', array($this, 'remove_frontend_actions'));
		add_filter( 'post_type_link', array( $this, 'portfolio_post_type_link' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'custom_type_settings' ) );
		add_action( 'load-options-permalink.php', array( $this,'seatings_for_permalink') );
		add_filter( 'manage_staticblocks_posts_columns', array( $this, 'et_staticblocks_columns' ) );
		add_action( 'manage_staticblocks_posts_custom_column', array( $this, 'et_staticblocks_columns_val' ), 10, 2 );

		add_filter( 'manage_etheme_slides_posts_columns', array( $this, 'etheme_slides_columns' ) );
		add_action( 'manage_etheme_slides_posts_custom_column', array( $this, 'etheme_slides_columns_val' ), 10, 2 );
		add_filter( 'manage_etheme_mega_menus_posts_columns', array( $this, 'etheme_slides_columns' ) );
		add_action( 'manage_etheme_mega_menus_posts_custom_column', array( $this, 'etheme_slides_columns_val' ), 10, 2 );
		add_action( 'wp_ajax_et_etheme_custom_post_type_create', array( $this, 'create_etheme_custom_post_type' ) );
		add_action('admin_notices', array($this, 'etheme_slides_banner'), 500 );

		add_action( 'brand_add_form_fields', array( $this, 'add_brand_fileds') );
		add_action( 'brand_edit_form_fields', array( $this, 'edit_brand_fields' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_local_scripts' ) );
		add_action( 'created_term', array( $this, 'brands_fields_save' ), 10,3 );
		add_action( 'edit_term', array( $this, 'brands_fields_save' ), 10,3 );
	}

	/**
	 * Create post types
	 * @return null
	 */
	public function create_custom_post_types() {
		$args = self::register_post_args();

		foreach ( $args as $fields ) {
			$this->get_model()->register_single_post_type( $fields );

		}

	}

	/**
	 * Create post types
	 * @return null
	 */
	public function create_taxonomies() {
		$args = self::register_taxonomies_args();

		foreach ( $args as $fields ) {

			$this->get_model()->register_single_post_type_taxnonomy( $fields );

		}

	}

	public function remove_frontend_actions() {
		if (isset($_GET['et_iframe_preview'])) {
			$options = explode('|', $_GET['et_iframe_preview']);

			if ( in_array('elementor_css_print_method_internal', $options) ) {
				add_filter('wp_doing_ajax', '__return_true'); // tweak for inline loading Elementor CSS
				add_action('etheme_output_shortcodes_inline_css', '__return_true');
//                add_filter('elementor/frontend/the_content', function ($content) {
//                    add_filter('wp_doing_ajax', '__return_true'); // tweak for inline loading Elementor CSS
//                    return $content;
//                });
			}
			add_action('wp_head', function () use ($options) {
				?>
                <style>
                    <?php if ( in_array('centered_content', $options) ) : ?>
                    .page-wrapper {height: 100vh;display: flex;align-items: center;}

                    .page-wrapper > * {
                        flex: 1;
                    }
                    <?php endif;
					if ( in_array('disable_animations', $options) ) : ?>
                    * {
                        transition: none !important;
                    }
                    .etheme-headline-text-wrapper svg,
                    .etheme-flipbox-side_b,
                    .etheme-marquee-item ~ .etheme-marquee-item,
                    .etheme-marquee-item_sep ~ .etheme-marquee-item_sep,
                    .etheme-blockquote .quotes {
                        display: none !important;
                    }
                    .animated {
                        animation: none !important;
                    }
                    .etheme-advanced-headline-mask {
                        -webkit-background-clip: unset;
                        -webkit-text-fill-color: currentColor;
                        background: none !important;
                    }
                    .elementor-widget-etheme_horizontal_scroll [data-animation] .swiper-slide-contents:not(.animated) {
                        opacity: 1;
                        visibility: visible;
                    }
                    <?php endif; ?>
                </style>
				<?php if ( in_array('disable_animations', $options) ) : ?>
                    <!-- Scroll to the same bottom for creating full-height page screenshot -->
                    <script>
                        jQuery(document).ready(function ($) {
                            setTimeout(function () {
                                $('html, body').animate({
                                    scrollTop: $('body').height()
                                }, 0);
                            }, 500);
                        })
                    </script>
				<?php endif;
			});
			if ( in_array('disable_lazyload', $options) ) {
				add_filter('et_ajax_widgets', '__return_false');
				add_filter('theme_mod_images_loading_type_et-desktop', '__return_empty_string');
                set_query_var('et_img-loading-type', '');
			}
			// Tell to WP Cache plugins do not cache this request.
			\Elementor\Utils::do_not_cache();
			if ( in_array('admin_bar', $options) ) {
				// Send MIME Type header like WP admin-header.
				@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

				add_filter('show_admin_bar', '__return_false');
			}
			if ( in_array('mobile_panel', $options) ) {
				add_filter('theme_mod_mobile_panel_et-mobile', '__return_false');
			}
			if ( in_array('back_top', $options) ) {
				add_filter('theme_mod_to_top', '__return_false');
				add_filter('theme_mod_to_top_mobile', '__return_false');
			}
			if ( in_array('header', $options) ) {
				remove_all_actions( 'etheme_header' );
				remove_all_actions( 'etheme_header_mobile' );
			}
			if ( in_array('footer', $options) ) {
				remove_all_actions('etheme_prefooter');
				remove_all_actions('etheme_footer');
			}

			remove_action('et_after_body', 'etheme_bordered_layout');
			remove_action('after_page_wrapper', 'etheme_photoswipe_template', 30);
			remove_action('after_page_wrapper', 'et_notify', 40);
			remove_action('after_page_wrapper', 'et_buffer', 40);

			add_filter('et_ajax_widgets', '__return_false');
			add_filter('etheme_ajaxify_lazyload_widget', '__return_false');
			add_filter('etheme_ajaxify_elementor_widget', '__return_false');

			// Handle `wp_enqueue_scripts`
//            remove_all_actions( 'wp_enqueue_scripts' );

			// Also remove all scripts hooked into after_wp_tiny_mce.
			remove_all_actions( 'after_wp_tiny_mce' );
			// Setup default heartbeat options
			add_filter( 'heartbeat_settings', function( $settings ) {
				$settings['interval'] = 15;
				return $settings;
			} );
		}
	}

	public function portfolio_post_type_link( $permalink, $post ) {
		/**
		 *
		 * Add support for portfolio link custom structure.
		 *
		 */
		if ( $post->post_type != 'etheme_portfolio' ) {
			return $permalink;
		}


		if ( false === strpos( $permalink, '%' ) ) {
			return $permalink;
		}

		// Get the custom taxonomy terms of this post.
		$terms = get_the_terms( $post->ID, 'portfolio_category' );

		if ( ! empty( $terms ) ) {
			$terms = wp_list_sort( $terms, 'ID' );  // order by ID

			$category_object = apply_filters( 'portfolio_post_type_link_portfolio_cat', $terms[0], $terms, $post );
			$category_object = get_term( $category_object, 'portfolio_category' );
			$portfolio_category     = $category_object->slug;

			if ( $category_object->parent ) {
				$ancestors = get_ancestors( $category_object->term_id, 'portfolio_category' );
				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, 'portfolio_category' );
					$portfolio_category     = $ancestor_object->slug . '/' . $portfolio_category;
				}
			}
		} else {
			$portfolio_category = esc_html__( 'uncategorized', 'xstore-core' );
		}

		if ( strpos( $permalink, '%author%' ) != false ) {
			$authordata = get_userdata( $post->post_author );
			$author = $authordata->user_nicename;
		} else {
			$author = '';
		}

		$find = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%post_id%',
			'%author%',
			'%category%',
			'%portfolio_category%'
		);

		$replace = array(
			date_i18n( 'Y', strtotime( $post->post_date ) ),
			date_i18n( 'm', strtotime( $post->post_date ) ),
			date_i18n( 'd', strtotime( $post->post_date ) ),
			date_i18n( 'H', strtotime( $post->post_date ) ),
			date_i18n( 'i', strtotime( $post->post_date ) ),
			date_i18n( 's', strtotime( $post->post_date ) ),
			$post->ID,
			$author,
			$portfolio_category,
			$portfolio_category
		);

		$permalink = str_replace( $find, $replace, $permalink );

		return $permalink;
	}

	public function et_staticblocks_columns($defaults) {
		return array(
			'cb'               => '<input type="checkbox" />',
			'title'            => esc_html__( 'Title', 'xstore-core' ),
			'shortcode_column' => esc_html__( 'Shortcode', 'xstore-core' ),
			'date'             => esc_html__( 'Date', 'xstore-core' ),
		);
	}

	public function et_staticblocks_columns_val($column_name, $post_ID) {
		if ($column_name == 'shortcode_column') { ?>
            <div class="staticblock-copy-code">
                <button class="button button-small copy-staticblock-code" type="button" data-text="<?php esc_html_e('Copy shortcode', 'xstore-core') ?>" data-success-text="<?php esc_html_e('Successfully copied!', 'xstore-core') ?>"><?php esc_html_e('Copy shortcode', 'xstore-core') ?></button>
                <pre>[block id="<?php echo $post_ID; ?>"]</pre>
            </div>
		<?php }
	}

	public function etheme_slides_columns($defaults) {
		return array(
			'cb'               => '<input type="checkbox" />',
			'thumbnail' => esc_html__( 'Thumbnail', 'xstore-core' ),
			'title'            => esc_html__( 'Title', 'xstore-core' ),
			'date'             => esc_html__( 'Date', 'xstore-core' ),
		);
	}

	public function etheme_slides_columns_val($column_name, $post_ID) {
		if ($column_name == 'thumbnail') {
			$args = array(
				'admin_bar',
				'mobile_panel',
				'back_top',
				'header',
				'footer');
			$has_content = get_the_content(null, false, $post_ID);
			if ( $has_content )
				$has_content = !isset($_GET['post_status']) || $_GET['post_status'] != 'trash'; // in_array(get_post_status($post_ID), array('publish', 'draft'));

			?>
            <div class="etheme-slides-previewer<?php if ( $has_content ) : ?> mtips mtips-right mtips-img mtips-lg<?php endif; ?>">
                <a href="<?php echo admin_url('post.php?post='.$post_ID.'&action=elementor'); ?>">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					}
                    elseif ( $latest_preview = $this->etheme_slides_previewer(get_post_type(), $post_ID) ) {
						echo '<img src="'.$latest_preview.'">';
					} else { ?>
                        <img src="<?php echo ETHEME_CODE_IMAGES . 'placeholder.jpg'; ?>" alt="<?php echo sprintf(esc_attr__('%s placeholder', 'xstore-core'), get_the_title()); ?>">
					<?php } ?>
                </a>
				<?php if ( $has_content ) : ?>
                    <span class="mt-mes">
                        <iframe class="loading" data-src="<?php echo add_query_arg('et_iframe_preview', implode('|', $args), get_permalink($post_ID)); ?>" frameborder="0"></iframe>
                    </span>
				<?php endif; ?>
            </div>
			<?php // echo '<div class="etheme-slides-thumb">'.get_the_post_thumbnail( $post_ID, 'thumbnail' ).'</div>'; ?>
		<?php }
	}

	public function etheme_slides_previewer($postType, $postID) {
		if ( !$this->upload_dir ) {
			$uploads = wp_get_upload_dir();
			$this->upload_dir = $uploads['basedir'];
		}
		$imageURL = false;
		$screenshot = $this->upload_dir. '/xstore/'.$postType.'-'.$postID.'screenshot.json';
		// should have read and write permission to the disk to write the JSON file
		if ( is_readable($screenshot) ) {
			if ($screenshotJson = fopen($screenshot, "r")) {
				$existingContent = file_get_contents($screenshot);
				$contentArray = json_decode($existingContent, true);
				if (isset($contentArray['imageURL']) && $contentArray['imageURL'])
					$imageURL = $contentArray['imageURL'];
				fclose($screenshotJson);
			}
		}

		return $imageURL;
	}
	public function create_etheme_custom_post_type() {
		check_ajax_referer('etheme-custom_post_type_create', 'security');

		if (!current_user_can( 'manage_options' )){
			wp_send_json_error('Unauthorized access');
		}

		if ( !isset($_POST['postType']) || empty($_POST['postType'])) {
			wp_send_json([]);
			return;
		}
		// get all slides to set new slide item number on creation
		$created_templates = array( 'post_type' => $_POST['postType'], 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' );
		$created_templates = count(get_posts( $created_templates ) );

		$new_post_title = '';
		$new_post_args = array();
		switch ($_POST['postType']) {
			case 'etheme_slides':
				$new_post_title = sprintf(esc_html__('Slide %s', 'xstore-core'), ($created_templates+1));
				$new_post_args['et_open_etheme-slides-import'] = 'yes';
				$new_post_args['et_page'] = 'slide';
				break;
			case 'etheme_mega_menus':
				$new_post_title = sprintf(esc_html__('Mega menu %s', 'xstore-core'), ($created_templates+1));
				$new_post_args['et_open_etheme-mega_menus-import'] = 'yes';
				$new_post_args['et_page'] = 'mega_menus';
				break;
		}

		$post_args = array(
			'post_type'  => $_POST['postType'],
		);

		if ( !!$new_post_title )
			$post_args['post_title'] = $new_post_title;

		$post_id = wp_insert_post( $post_args );

		$url = add_query_arg(
			array_merge(array(
				'post'           => $post_id,
				'action'         => 'elementor',
				'classic-editor' => '',
			), $new_post_args),
			admin_url( 'post.php' )
		);

		wp_send_json(
			array(
				'redirect_url' => $url,
			)
		);
	}

	public function etheme_slides_banner() {
		if ( isset($_GET['post_type']) && in_array($_GET['post_type'], array('etheme_slides', 'etheme_mega_menus', 'etheme_linked_var')) ) {
			$mega_menus = $_GET['post_type'] == 'etheme_mega_menus';
			$video_id = '5ZwO_Cwzy14';
			if ( $mega_menus )
				$video_id = 'Qi0f1DWSH5U';
            if ( $_GET['post_type'] == 'etheme_linked_var') {
                $video_id = false;
            }
			$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() ); ?>
            <div class="wrap">
                <div class="etheme_custom_post_type-banner <?php echo esc_attr($_GET['post_type']); ?>-banner flex">
                    <div class="etheme_custom_post_type-banner-info <?php echo esc_attr($_GET['post_type']); ?>-banner-info">
                        <div class="logo">
                            <?php
                            if ( isset( $xstore_branding_settings['control_panel']['logo'] ) && ! empty( $xstore_branding_settings['control_panel']['logo'] ) ) : ?>
                                <img src="<?php echo esc_url( $xstore_branding_settings['control_panel']['logo'] ); ?>" alt="panel-logo">
                            <?php else:
                                switch ($_GET['post_type']) {
                                    case 'etheme_slides':
                                        ?>
                                        <svg width="237" height="43" viewBox="0 0 237 43" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <path d="M45.344 13.238C45.236 13.418 45.122 13.556 45.002 13.652C44.882 13.736 44.726 13.778 44.534 13.778C44.33 13.778 44.09 13.676 43.814 13.472C43.55 13.268 43.208 13.046 42.788 12.806C42.38 12.554 41.882 12.326 41.294 12.122C40.718 11.918 40.016 11.816 39.188 11.816C38.408 11.816 37.718 11.924 37.118 12.14C36.518 12.344 36.014 12.626 35.606 12.986C35.21 13.346 34.91 13.772 34.706 14.264C34.502 14.744 34.4 15.266 34.4 15.83C34.4 16.55 34.574 17.15 34.922 17.63C35.282 18.098 35.75 18.5 36.326 18.836C36.914 19.172 37.574 19.466 38.306 19.718C39.05 19.958 39.806 20.21 40.574 20.474C41.354 20.738 42.11 21.038 42.842 21.374C43.586 21.698 44.246 22.112 44.822 22.616C45.41 23.12 45.878 23.738 46.226 24.47C46.586 25.202 46.766 26.102 46.766 27.17C46.766 28.298 46.574 29.36 46.19 30.356C45.806 31.34 45.242 32.198 44.498 32.93C43.766 33.662 42.86 34.238 41.78 34.658C40.712 35.078 39.494 35.288 38.126 35.288C36.458 35.288 34.934 34.988 33.554 34.388C32.186 33.776 31.016 32.954 30.044 31.922L31.052 30.266C31.148 30.134 31.262 30.026 31.394 29.942C31.538 29.846 31.694 29.798 31.862 29.798C32.114 29.798 32.402 29.936 32.726 30.212C33.05 30.476 33.452 30.77 33.932 31.094C34.424 31.418 35.012 31.718 35.696 31.994C36.392 32.258 37.238 32.39 38.234 32.39C39.062 32.39 39.8 32.276 40.448 32.048C41.096 31.82 41.642 31.502 42.086 31.094C42.542 30.674 42.89 30.176 43.13 29.6C43.37 29.024 43.49 28.382 43.49 27.674C43.49 26.894 43.31 26.258 42.95 25.766C42.602 25.262 42.14 24.842 41.564 24.506C40.988 24.17 40.328 23.888 39.584 23.66C38.852 23.42 38.096 23.18 37.316 22.94C36.548 22.688 35.792 22.4 35.048 22.076C34.316 21.752 33.662 21.332 33.086 20.816C32.51 20.3 32.042 19.658 31.682 18.89C31.334 18.11 31.16 17.15 31.16 16.01C31.16 15.098 31.334 14.216 31.682 13.364C32.042 12.512 32.558 11.756 33.23 11.096C33.902 10.436 34.73 9.908 35.714 9.512C36.71 9.116 37.85 8.918 39.134 8.918C40.574 8.918 41.888 9.146 43.076 9.602C44.264 10.058 45.302 10.718 46.19 11.582L45.344 13.238ZM68.7898 9.206V12.14H60.4558V35H56.9638V12.14H48.5938V9.206H68.7898ZM94.6564 22.112C94.6564 24.044 94.3504 25.82 93.7384 27.44C93.1264 29.048 92.2624 30.434 91.1464 31.598C90.0304 32.762 88.6864 33.668 87.1144 34.316C85.5544 34.952 83.8264 35.27 81.9304 35.27C80.0344 35.27 78.3064 34.952 76.7464 34.316C75.1864 33.668 73.8484 32.762 72.7324 31.598C71.6164 30.434 70.7524 29.048 70.1404 27.44C69.5284 25.82 69.2224 24.044 69.2224 22.112C69.2224 20.18 69.5284 18.41 70.1404 16.802C70.7524 15.182 71.6164 13.79 72.7324 12.626C73.8484 11.45 75.1864 10.538 76.7464 9.89C78.3064 9.242 80.0344 8.918 81.9304 8.918C83.8264 8.918 85.5544 9.242 87.1144 9.89C88.6864 10.538 90.0304 11.45 91.1464 12.626C92.2624 13.79 93.1264 15.182 93.7384 16.802C94.3504 18.41 94.6564 20.18 94.6564 22.112ZM91.0744 22.112C91.0744 20.528 90.8584 19.106 90.4264 17.846C89.9944 16.586 89.3824 15.524 88.5904 14.66C87.7984 13.784 86.8384 13.112 85.7104 12.644C84.5824 12.176 83.3224 11.942 81.9304 11.942C80.5504 11.942 79.2964 12.176 78.1684 12.644C77.0404 13.112 76.0744 13.784 75.2704 14.66C74.4784 15.524 73.8664 16.586 73.4344 17.846C73.0024 19.106 72.7864 20.528 72.7864 22.112C72.7864 23.696 73.0024 25.118 73.4344 26.378C73.8664 27.626 74.4784 28.688 75.2704 29.564C76.0744 30.428 77.0404 31.094 78.1684 31.562C79.2964 32.018 80.5504 32.246 81.9304 32.246C83.3224 32.246 84.5824 32.018 85.7104 31.562C86.8384 31.094 87.7984 30.428 88.5904 29.564C89.3824 28.688 89.9944 27.626 90.4264 26.378C90.8584 25.118 91.0744 23.696 91.0744 22.112ZM103.255 24.236V35H99.7811V9.206H107.071C108.703 9.206 110.113 9.374 111.301 9.71C112.489 10.034 113.467 10.508 114.235 11.132C115.015 11.756 115.591 12.512 115.963 13.4C116.335 14.276 116.521 15.26 116.521 16.352C116.521 17.264 116.377 18.116 116.089 18.908C115.801 19.7 115.381 20.414 114.829 21.05C114.289 21.674 113.623 22.208 112.831 22.652C112.051 23.096 111.163 23.432 110.167 23.66C110.599 23.912 110.983 24.278 111.319 24.758L118.843 35H115.747C115.111 35 114.643 34.754 114.343 34.262L107.647 25.046C107.443 24.758 107.221 24.554 106.981 24.434C106.741 24.302 106.381 24.236 105.901 24.236H103.255ZM103.255 21.698H106.909C107.929 21.698 108.823 21.578 109.591 21.338C110.371 21.086 111.019 20.738 111.535 20.294C112.063 19.838 112.459 19.298 112.723 18.674C112.987 18.05 113.119 17.36 113.119 16.604C113.119 15.068 112.609 13.91 111.589 13.13C110.581 12.35 109.075 11.96 107.071 11.96H103.255V21.698ZM138.483 9.206V12.05H126.099V20.618H136.125V23.354H126.099V32.156H138.483V35H122.589V9.206H138.483Z" fill="white"/>
                                            <rect y="9" width="28" height="26" fill="url(#pattern0)"/>
                                            <path opacity="0.7" d="M163.092 12.626C162.984 12.83 162.828 12.932 162.624 12.932C162.468 12.932 162.264 12.824 162.012 12.608C161.772 12.38 161.442 12.134 161.022 11.87C160.602 11.594 160.074 11.342 159.438 11.114C158.814 10.886 158.04 10.772 157.116 10.772C156.192 10.772 155.376 10.904 154.668 11.168C153.972 11.432 153.384 11.792 152.904 12.248C152.436 12.704 152.076 13.232 151.824 13.832C151.584 14.432 151.464 15.062 151.464 15.722C151.464 16.586 151.644 17.3 152.004 17.864C152.376 18.428 152.862 18.908 153.462 19.304C154.062 19.7 154.74 20.036 155.496 20.312C156.264 20.576 157.05 20.84 157.854 21.104C158.658 21.368 159.438 21.662 160.194 21.986C160.962 22.298 161.646 22.694 162.246 23.174C162.846 23.654 163.326 24.248 163.686 24.956C164.058 25.652 164.244 26.522 164.244 27.566C164.244 28.634 164.058 29.642 163.686 30.59C163.326 31.526 162.798 32.342 162.102 33.038C161.406 33.734 160.554 34.286 159.546 34.694C158.538 35.09 157.386 35.288 156.09 35.288C154.41 35.288 152.964 34.994 151.752 34.406C150.54 33.806 149.478 32.99 148.566 31.958L149.07 31.166C149.214 30.986 149.382 30.896 149.574 30.896C149.682 30.896 149.82 30.968 149.988 31.112C150.156 31.256 150.36 31.436 150.6 31.652C150.84 31.856 151.128 32.084 151.464 32.336C151.8 32.576 152.19 32.804 152.634 33.02C153.078 33.224 153.588 33.398 154.164 33.542C154.74 33.686 155.394 33.758 156.126 33.758C157.134 33.758 158.034 33.608 158.826 33.308C159.618 32.996 160.284 32.576 160.824 32.048C161.376 31.52 161.796 30.896 162.084 30.176C162.372 29.444 162.516 28.664 162.516 27.836C162.516 26.936 162.33 26.198 161.958 25.622C161.598 25.034 161.118 24.548 160.518 24.164C159.918 23.768 159.234 23.438 158.466 23.174C157.71 22.91 156.93 22.652 156.126 22.4C155.322 22.148 154.536 21.866 153.768 21.554C153.012 21.242 152.334 20.846 151.734 20.366C151.134 19.874 150.648 19.268 150.276 18.548C149.916 17.816 149.736 16.904 149.736 15.812C149.736 14.96 149.898 14.138 150.222 13.346C150.546 12.554 151.02 11.858 151.644 11.258C152.268 10.646 153.036 10.16 153.948 9.8C154.872 9.428 155.922 9.242 157.098 9.242C158.418 9.242 159.6 9.452 160.644 9.872C161.7 10.292 162.66 10.934 163.524 11.798L163.092 12.626ZM170.939 8.81V35H169.229V8.81H170.939ZM179.482 17.09V35H177.772V17.09H179.482ZM180.238 10.916C180.238 11.132 180.19 11.336 180.094 11.528C180.01 11.708 179.896 11.87 179.752 12.014C179.608 12.158 179.44 12.272 179.248 12.356C179.056 12.44 178.852 12.482 178.636 12.482C178.42 12.482 178.216 12.44 178.024 12.356C177.832 12.272 177.664 12.158 177.52 12.014C177.376 11.87 177.262 11.708 177.178 11.528C177.094 11.336 177.052 11.132 177.052 10.916C177.052 10.7 177.094 10.496 177.178 10.304C177.262 10.1 177.376 9.926 177.52 9.782C177.664 9.638 177.832 9.524 178.024 9.44C178.216 9.356 178.42 9.314 178.636 9.314C178.852 9.314 179.056 9.356 179.248 9.44C179.44 9.524 179.608 9.638 179.752 9.782C179.896 9.926 180.01 10.1 180.094 10.304C180.19 10.496 180.238 10.7 180.238 10.916ZM198.501 35C198.201 35 198.027 34.844 197.979 34.532L197.799 31.706C197.007 32.786 196.077 33.644 195.009 34.28C193.953 34.916 192.765 35.234 191.445 35.234C189.249 35.234 187.533 34.472 186.297 32.948C185.073 31.424 184.461 29.138 184.461 26.09C184.461 24.782 184.629 23.564 184.965 22.436C185.313 21.296 185.817 20.312 186.477 19.484C187.149 18.644 187.965 17.984 188.925 17.504C189.897 17.024 191.013 16.784 192.273 16.784C193.485 16.784 194.535 17.012 195.423 17.468C196.311 17.912 197.079 18.566 197.727 19.43V8.81H199.455V35H198.501ZM191.931 33.848C193.119 33.848 194.193 33.542 195.153 32.93C196.113 32.318 196.971 31.466 197.727 30.374V20.924C197.031 19.904 196.263 19.184 195.423 18.764C194.595 18.344 193.653 18.134 192.597 18.134C191.541 18.134 190.617 18.326 189.825 18.71C189.033 19.094 188.367 19.64 187.827 20.348C187.299 21.044 186.897 21.884 186.621 22.868C186.357 23.84 186.225 24.914 186.225 26.09C186.225 28.754 186.717 30.716 187.701 31.976C188.685 33.224 190.095 33.848 191.931 33.848ZM212.213 16.802C213.221 16.802 214.151 16.976 215.003 17.324C215.867 17.672 216.611 18.182 217.235 18.854C217.871 19.514 218.363 20.33 218.711 21.302C219.071 22.274 219.251 23.39 219.251 24.65C219.251 24.914 219.209 25.094 219.125 25.19C219.053 25.286 218.933 25.334 218.765 25.334L205.823 25.334V25.676C205.823 27.02 205.979 28.202 206.291 29.222C206.603 30.242 207.047 31.1 207.623 31.796C208.199 32.48 208.895 32.996 209.711 33.344C210.527 33.692 211.439 33.866 212.447 33.866C213.347 33.866 214.127 33.77 214.787 33.578C215.447 33.374 215.999 33.152 216.443 32.912C216.899 32.66 217.259 32.438 217.523 32.246C217.787 32.042 217.979 31.94 218.099 31.94C218.255 31.94 218.375 32 218.459 32.12L218.927 32.696C218.639 33.056 218.255 33.392 217.775 33.704C217.307 34.016 216.779 34.286 216.191 34.514C215.615 34.73 214.991 34.904 214.319 35.036C213.659 35.168 212.993 35.234 212.321 35.234C211.097 35.234 209.981 35.024 208.973 34.604C207.965 34.172 207.101 33.548 206.381 32.732C205.661 31.916 205.103 30.92 204.707 29.744C204.323 28.556 204.131 27.2 204.131 25.676C204.131 24.392 204.311 23.21 204.671 22.13C205.043 21.038 205.571 20.102 206.255 19.322C206.951 18.53 207.797 17.912 208.793 17.468C209.801 17.024 210.941 16.802 212.213 16.802ZM212.231 18.08C211.307 18.08 210.479 18.224 209.747 18.512C209.015 18.8 208.379 19.214 207.839 19.754C207.311 20.294 206.879 20.942 206.543 21.698C206.219 22.454 206.003 23.3 205.895 24.236L217.703 24.236C217.703 23.276 217.571 22.418 217.307 21.662C217.043 20.894 216.671 20.246 216.191 19.718C215.711 19.19 215.135 18.788 214.463 18.512C213.791 18.224 213.047 18.08 212.231 18.08ZM233.714 19.304C233.63 19.472 233.498 19.556 233.318 19.556C233.186 19.556 233.012 19.484 232.796 19.34C232.592 19.184 232.316 19.016 231.968 18.836C231.632 18.644 231.212 18.476 230.708 18.332C230.216 18.176 229.61 18.098 228.89 18.098C228.242 18.098 227.648 18.194 227.108 18.386C226.58 18.566 226.124 18.812 225.74 19.124C225.368 19.436 225.074 19.802 224.858 20.222C224.654 20.63 224.552 21.062 224.552 21.518C224.552 22.082 224.696 22.55 224.984 22.922C225.272 23.294 225.65 23.612 226.118 23.876C226.586 24.14 227.114 24.368 227.702 24.56C228.302 24.752 228.914 24.944 229.538 25.136C230.162 25.328 230.768 25.544 231.356 25.784C231.956 26.012 232.49 26.3 232.958 26.648C233.426 26.996 233.804 27.422 234.092 27.926C234.38 28.43 234.524 29.042 234.524 29.762C234.524 30.542 234.38 31.268 234.092 31.94C233.816 32.612 233.408 33.194 232.868 33.686C232.34 34.178 231.686 34.568 230.906 34.856C230.126 35.144 229.238 35.288 228.242 35.288C226.982 35.288 225.896 35.09 224.984 34.694C224.072 34.286 223.256 33.758 222.536 33.11L222.95 32.498C223.01 32.402 223.076 32.33 223.148 32.282C223.22 32.234 223.322 32.21 223.454 32.21C223.61 32.21 223.802 32.306 224.03 32.498C224.258 32.69 224.552 32.9 224.912 33.128C225.284 33.344 225.74 33.548 226.28 33.74C226.832 33.932 227.51 34.028 228.314 34.028C229.07 34.028 229.736 33.926 230.312 33.722C230.888 33.506 231.368 33.218 231.752 32.858C232.136 32.498 232.424 32.078 232.616 31.598C232.82 31.106 232.922 30.59 232.922 30.05C232.922 29.45 232.778 28.952 232.49 28.556C232.202 28.16 231.824 27.824 231.356 27.548C230.888 27.272 230.354 27.038 229.754 26.846C229.166 26.654 228.554 26.462 227.918 26.27C227.294 26.078 226.682 25.868 226.082 25.64C225.494 25.412 224.966 25.124 224.498 24.776C224.03 24.428 223.652 24.008 223.364 23.516C223.076 23.012 222.932 22.388 222.932 21.644C222.932 21.008 223.07 20.396 223.346 19.808C223.622 19.22 224.012 18.704 224.516 18.26C225.032 17.816 225.656 17.462 226.388 17.198C227.12 16.934 227.942 16.802 228.854 16.802C229.946 16.802 230.912 16.958 231.752 17.27C232.604 17.582 233.384 18.062 234.092 18.71L233.714 19.304Z" fill="white"/>
                                            <defs>
                                                <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                                    <use xlink:href="#image0_2885_13" transform="matrix(0.00769231 0 0 0.00828402 0 -0.00532544)"/>
                                                </pattern>
                                                <image id="image0_2885_13" width="130" height="122" xlink:href="data:image/png;base64,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"/>
                                            </defs>
                                        </svg>
                                        <?php
                                    break;
                                    case 'etheme_mega_menus':
                                        ?>
                                        <svg width="292" height="30" viewBox="0 0 292 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.9396 11.2769L27.6512 0.109337H24.6481C24.5357 0.109337 24.4362 0.128607 24.3495 0.176793C24.3077 0.192848 24.2692 0.218548 24.2339 0.247444C24.1182 0.34058 24.0154 0.459437 23.9159 0.613599L18.6966 8.36382L18.1859 9.11858C18.0059 9.38784 18.1669 9.15129 17.8004 9.67138L17.4278 10.2372L16.6828 11.2769L24.6481 23.3631C24.6931 23.3695 24.7413 23.3727 24.7927 23.3727H27.9114L19.9396 11.2769Z" fill="white"/>
                                            <path d="M10.6611 11.4244L2.98226 0.108955H6.11539C6.34279 0.108955 6.51043 0.146908 6.61865 0.222499C6.7267 0.298404 6.82424 0.406615 6.91097 0.547134L12.9826 9.86589C13.0581 9.63843 13.1719 9.38972 13.3236 9.11905L19.0545 0.612374C19.1517 0.460878 19.2571 0.339179 19.371 0.246964C19.4845 0.155063 19.6225 0.108955 19.785 0.108955H22.7883L15.0769 11.2782L23.048 23.3729H19.9309C19.6928 23.3729 19.5061 23.3108 19.371 23.1862C19.2355 23.0621 19.1248 22.924 19.0382 22.7722L12.8039 13.0153C12.7282 13.2427 12.6308 13.4592 12.5118 13.6646L6.44017 22.7722C6.34278 22.924 6.23159 23.0621 6.10739 23.1862C5.98271 23.3108 5.80706 23.3729 5.57965 23.3729H2.65747L10.6611 11.4244Z" fill="white"/>
                                            <path d="M42.1201 3.92933C42.0251 4.08826 41.924 4.20736 41.8182 4.28702C41.7118 4.36629 41.5739 4.40613 41.4047 4.40613C41.2239 4.40613 41.0148 4.31613 40.7766 4.13575C40.5384 3.95575 40.2358 3.75698 39.8704 3.53983C39.5043 3.32269 39.0647 3.12393 38.5507 2.94354C38.0367 2.76316 37.4141 2.67316 36.6825 2.67316C35.9932 2.67316 35.3835 2.76623 34.8542 2.95158C34.3234 3.13695 33.8815 3.38856 33.5264 3.70681C33.171 4.02469 32.9037 4.39808 32.7233 4.8274C32.5429 5.25672 32.4534 5.7205 32.4534 6.21875C32.4534 6.85487 32.6096 7.38223 32.9221 7.80082C33.2346 8.21942 33.6482 8.57747 34.1626 8.87394C34.6765 9.17073 35.2594 9.42807 35.9112 9.64526C36.563 9.86236 37.231 10.0879 37.9142 10.3208C38.5982 10.5541 39.2661 10.8163 39.9179 11.1078C40.5697 11.3997 41.1527 11.7677 41.6666 12.2127C42.1813 12.6581 42.5942 13.2042 42.9074 13.8503C43.2199 14.4971 43.3762 15.2922 43.3762 16.2355C43.3762 17.232 43.2061 18.1672 42.8676 19.0415C42.5283 19.9163 42.0327 20.6768 41.3809 21.3233C40.729 21.9697 39.9286 22.4788 38.9797 22.8494C38.0306 23.2206 36.9529 23.4059 35.7443 23.4059C34.2713 23.4059 32.9274 23.1386 31.7138 22.6032C30.5002 22.0678 29.4642 21.3448 28.6056 20.4329L29.496 18.9699C29.5807 18.8535 29.684 18.7554 29.8059 18.6758C29.9276 18.5965 30.0628 18.5567 30.2114 18.5567C30.4339 18.5567 30.6882 18.6758 30.9747 18.9143C31.2607 19.1529 31.6185 19.4153 32.0478 19.7014C32.4771 19.9875 32.9964 20.2498 33.6061 20.4884C34.2154 20.727 34.96 20.8461 35.84 20.8461C36.5707 20.8461 37.2233 20.7458 37.7955 20.544C38.3676 20.3429 38.8524 20.0591 39.25 19.6933C39.6475 19.328 39.9524 18.8907 40.1646 18.3816C40.3767 17.8731 40.4824 17.3062 40.4824 16.6805C40.4824 15.9915 40.3262 15.4274 40.0136 14.9874C39.7004 14.5477 39.2899 14.1793 38.7812 13.8821C38.2727 13.5857 37.6921 13.3337 37.0402 13.1268C36.3884 12.9204 35.7205 12.7082 35.0373 12.4911C34.3533 12.2739 33.6854 12.0219 33.0335 11.7359C32.3817 11.4498 31.8015 11.0787 31.2926 10.6229C30.7839 10.1672 30.373 9.59733 30.0605 8.91371C29.7476 8.23016 29.5913 7.38491 29.5913 6.37768C29.5913 5.57228 29.7476 4.79332 30.0605 4.04039C30.373 3.28784 30.826 2.62031 31.4197 2.03704C32.0133 1.45454 32.7475 0.987692 33.6218 0.638034C34.4965 0.288378 35.4999 0.113358 36.6351 0.113358C37.9065 0.113358 39.0677 0.314803 40.117 0.717693C41.1664 1.12058 42.0825 1.70347 42.8676 2.46636L42.1201 3.92933Z" fill="white"/>
                                            <path d="M62.8205 0.367653V2.95925H55.4598V23.1516H52.3753V2.95925H44.9816V0.367653H62.8205Z" fill="white"/>
                                            <path d="M85.668 11.7677C85.668 13.4742 85.3982 15.0406 84.8578 16.4661C84.3166 17.8914 83.5543 19.1185 82.5683 20.1468C81.5824 21.1747 80.3973 21.9725 79.0145 22.5396C77.631 23.1068 76.1021 23.3902 74.4278 23.3902C72.7526 23.3902 71.2261 23.1068 69.8482 22.5396C68.4703 21.9725 67.2884 21.1747 66.3026 20.1468C65.3169 19.1185 64.5539 17.8914 64.0131 16.4661C63.4732 15.0406 63.2028 13.4742 63.2028 11.7677C63.2028 10.0611 63.4732 8.49519 64.0131 7.06933C64.5539 5.6439 65.3169 4.41417 66.3026 3.38052C67.2884 2.34726 68.4703 1.54415 69.8482 0.97199C71.2261 0.39944 72.7526 0.113358 74.4278 0.113358C76.1021 0.113358 77.631 0.39944 79.0145 0.97199C80.3973 1.54415 81.5824 2.34726 82.5683 3.38052C83.5543 4.41417 84.3166 5.6439 84.8578 7.06933C85.3982 8.49519 85.668 10.0611 85.668 11.7677ZM82.5048 11.7677C82.5048 10.3686 82.3142 9.11254 81.9315 7.99957C81.5503 6.88665 81.0099 5.94607 80.3102 5.17744C79.6106 4.4088 78.762 3.81788 77.7666 3.40465C76.7701 2.99104 75.6571 2.78461 74.4278 2.78461C73.2084 2.78461 72.1009 2.99104 71.1044 3.40465C70.1079 3.81788 69.2577 4.4088 68.5529 5.17744C67.8475 5.94607 67.3044 6.88665 66.923 7.99957C66.5416 9.11254 66.3508 10.3686 66.3508 11.7677C66.3508 13.1667 66.5416 14.4202 66.923 15.5278C67.3044 16.6357 67.8475 17.5736 68.5529 18.3422C69.2577 19.1108 70.1079 19.6991 71.1044 20.1069C72.1009 20.5153 73.2084 20.7189 74.4278 20.7189C75.6571 20.7189 76.7701 20.5153 77.7666 20.1069C78.762 19.6991 79.6106 19.1108 80.3102 18.3422C81.0099 17.5736 81.5503 16.6357 81.9315 15.5278C82.3142 14.4202 82.5048 13.1667 82.5048 11.7677Z" fill="white"/>
                                            <path d="M93.2682 13.6439V23.1516H90.1991V0.367653H96.6385C98.0801 0.367653 99.3256 0.513566 100.375 0.805013C101.424 1.09646 102.29 1.51811 102.975 2.06883C103.658 2.62031 104.164 3.28516 104.493 4.06451C104.821 4.84348 104.986 5.71552 104.986 6.67985C104.986 7.48563 104.859 8.23777 104.605 8.93747C104.349 9.63718 103.981 10.2653 103.499 10.8218C103.017 11.3782 102.428 11.8527 101.734 12.2445C101.04 12.637 100.253 12.9338 99.3734 13.1349C99.7546 13.3574 100.094 13.681 100.392 14.105L107.036 23.1516H104.302C103.74 23.1516 103.327 22.9345 103.062 22.4998L97.1475 14.3593C96.9671 14.105 96.771 13.9219 96.5593 13.8109C96.3467 13.6994 96.029 13.6439 95.6055 13.6439H93.2682ZM93.2682 11.4019H96.4957C97.3961 11.4019 98.1891 11.2935 98.8723 11.076C99.5562 10.8589 100.129 10.5513 100.59 10.1538C101.05 9.75632 101.398 9.28219 101.632 8.73065C101.864 8.17997 101.981 7.57027 101.981 6.90236C101.981 5.54586 101.534 4.52293 100.637 3.83396C99.7413 3.14499 98.4087 2.80031 96.6385 2.80031H93.2682V11.4019Z" fill="white"/>
                                            <path d="M124.399 0.367653V2.87997H113.461V10.4479H122.317V12.8646H113.461V20.6396H124.399V23.1516H110.36V0.367653H124.399Z" fill="white"/>
                                            <path opacity="0.7" d="M145.849 16.7861C146.008 17.0892 146.146 17.414 146.263 17.7604C146.327 17.5872 146.391 17.4194 146.455 17.257C146.529 17.0838 146.609 16.9214 146.694 16.7699L155.565 0.808511C155.65 0.667786 155.735 0.581187 155.82 0.548712C155.905 0.516237 156.022 0.5 156.171 0.5H157.304V23.4759H155.868V3.89362C155.868 3.61217 155.884 3.31448 155.916 3.00056L147.029 19.0593C146.88 19.3408 146.668 19.4815 146.391 19.4815H146.136C145.87 19.4815 145.657 19.3408 145.498 19.0593L136.372 2.98432C136.404 3.29825 136.42 3.60134 136.42 3.89362V23.4759H135V0.5H136.117C136.266 0.5 136.383 0.516237 136.468 0.548712C136.563 0.581187 136.654 0.667786 136.739 0.808511L145.849 16.7861ZM169.227 7.05991C170.12 7.05991 170.944 7.21687 171.699 7.53079C172.465 7.84472 173.125 8.30478 173.678 8.91097C174.241 9.50635 174.677 10.2424 174.986 11.1193C175.305 11.9961 175.464 13.0028 175.464 14.1394C175.464 14.3776 175.427 14.5399 175.353 14.6265C175.289 14.7131 175.183 14.7564 175.034 14.7564H163.563V15.0649C163.563 16.2773 163.701 17.3436 163.978 18.2637C164.254 19.1838 164.648 19.9578 165.158 20.5857C165.669 21.2027 166.286 21.6682 167.009 21.9821C167.732 22.296 168.54 22.453 169.434 22.453C170.232 22.453 170.923 22.3664 171.508 22.1932C172.093 22.0091 172.582 21.8089 172.976 21.5924C173.38 21.3651 173.699 21.1648 173.933 20.9916C174.167 20.8076 174.337 20.7156 174.443 20.7156C174.582 20.7156 174.688 20.7697 174.763 20.8779L175.177 21.3975C174.922 21.7223 174.582 22.0254 174.156 22.3068C173.741 22.5883 173.273 22.8318 172.752 23.0375C172.242 23.2324 171.689 23.3893 171.093 23.5084C170.508 23.6275 169.918 23.687 169.322 23.687C168.237 23.687 167.248 23.4976 166.355 23.1187C165.461 22.729 164.696 22.1661 164.057 21.43C163.419 20.6939 162.925 19.7954 162.574 18.7346C162.233 17.6629 162.063 16.4397 162.063 15.0649C162.063 13.9067 162.223 12.8404 162.542 11.8662C162.872 10.8811 163.34 10.0368 163.946 9.33315C164.563 8.6187 165.312 8.06122 166.195 7.66069C167.089 7.26017 168.099 7.05991 169.227 7.05991ZM169.242 8.21277C168.423 8.21277 167.69 8.34266 167.041 8.60246C166.392 8.86226 165.828 9.23572 165.35 9.72284C164.882 10.21 164.499 10.7945 164.201 11.4765C163.914 12.1585 163.722 12.9216 163.627 13.766H174.092C174.092 12.9 173.975 12.126 173.741 11.444C173.507 10.7512 173.178 10.1667 172.752 9.69037C172.327 9.21407 171.816 8.85144 171.221 8.60246C170.625 8.34266 169.966 8.21277 169.242 8.21277ZM184.774 7.04367C185.465 7.04367 186.103 7.13027 186.688 7.30347C187.284 7.47667 187.815 7.72023 188.283 8.03415H192.352V8.56999C192.352 8.84061 192.203 8.98675 191.905 9.0084L189.496 9.18701C189.794 9.59836 190.022 10.0638 190.182 10.5834C190.342 11.0922 190.421 11.6389 190.421 12.2234C190.421 13.0028 190.283 13.7118 190.007 14.3505C189.73 14.9783 189.342 15.5196 188.842 15.9742C188.353 16.4181 187.762 16.7645 187.071 17.0134C186.38 17.2624 185.614 17.3869 184.774 17.3869C183.859 17.3869 183.029 17.2462 182.285 16.9647C181.838 17.2245 181.487 17.533 181.232 17.8903C180.977 18.2475 180.849 18.5939 180.849 18.9294C180.849 19.3949 181.008 19.7521 181.328 20.0011C181.647 20.2501 182.067 20.4341 182.588 20.5532C183.12 20.6614 183.721 20.7318 184.391 20.7643C185.071 20.7968 185.757 20.8346 186.449 20.8779C187.151 20.9104 187.837 20.9754 188.507 21.0728C189.188 21.1702 189.788 21.3434 190.31 21.5924C190.841 21.8305 191.267 22.1715 191.586 22.6153C191.905 23.0483 192.065 23.6275 192.065 24.3527C192.065 25.0239 191.9 25.668 191.57 26.285C191.24 26.902 190.762 27.4487 190.134 27.925C189.517 28.4013 188.767 28.7801 187.885 29.0616C187.002 29.3539 186.013 29.5 184.917 29.5C183.8 29.5 182.822 29.3809 181.982 29.1428C181.141 28.9155 180.434 28.6015 179.86 28.201C179.296 27.8113 178.871 27.3567 178.583 26.8371C178.296 26.3175 178.153 25.7654 178.153 25.1808C178.153 24.3257 178.429 23.5896 178.982 22.9726C179.535 22.3555 180.296 21.8792 181.264 21.5437C180.732 21.3596 180.307 21.0944 179.987 20.748C179.679 20.4016 179.525 19.9199 179.525 19.3029C179.525 19.0756 179.567 18.8374 179.652 18.5885C179.737 18.3395 179.865 18.0959 180.035 17.8578C180.205 17.6088 180.408 17.3761 180.642 17.1596C180.886 16.9431 181.168 16.7482 181.487 16.575C180.732 16.1312 180.142 15.5412 179.716 14.8052C179.301 14.0691 179.094 13.2085 179.094 12.2234C179.094 11.444 179.227 10.7404 179.493 10.1125C179.769 9.47387 180.152 8.92721 180.642 8.47256C181.141 8.01792 181.742 7.66611 182.444 7.41713C183.146 7.16816 183.923 7.04367 184.774 7.04367ZM190.661 24.5638C190.661 24.0551 190.528 23.6491 190.262 23.346C190.006 23.0321 189.661 22.7885 189.225 22.6153C188.789 22.4421 188.283 22.3231 187.709 22.2581C187.145 22.1823 186.55 22.1282 185.922 22.0957C185.305 22.0633 184.683 22.0308 184.056 21.9983C183.428 21.9658 182.843 21.9009 182.301 21.8035C181.897 21.9658 181.519 22.1553 181.168 22.3718C180.828 22.5775 180.535 22.8156 180.291 23.0862C180.046 23.346 179.854 23.6329 179.716 23.9468C179.578 24.2716 179.509 24.6234 179.509 25.0022C179.509 25.4785 179.626 25.9169 179.86 26.3175C180.104 26.7288 180.455 27.0806 180.913 27.3729C181.381 27.676 181.95 27.9141 182.62 28.0873C183.29 28.2605 184.061 28.3471 184.933 28.3471C185.741 28.3471 186.491 28.2551 187.183 28.0711C187.885 27.8871 188.491 27.6273 189.001 27.2917C189.523 26.9561 189.927 26.5556 190.214 26.0901C190.512 25.6247 190.661 25.1159 190.661 24.5638ZM184.774 16.3315C185.454 16.3315 186.061 16.234 186.592 16.0392C187.124 15.8335 187.571 15.5521 187.932 15.1948C188.294 14.8376 188.565 14.41 188.746 13.9121C188.938 13.4141 189.033 12.8621 189.033 12.2559C189.033 11.6497 188.938 11.0976 188.746 10.5997C188.555 10.0909 188.273 9.65789 187.901 9.30067C187.539 8.94345 187.092 8.66741 186.56 8.47256C186.039 8.27772 185.444 8.18029 184.774 8.18029C184.104 8.18029 183.503 8.27772 182.971 8.47256C182.439 8.66741 181.987 8.94345 181.615 9.30067C181.253 9.65789 180.977 10.0909 180.785 10.5997C180.594 11.0976 180.498 11.6497 180.498 12.2559C180.498 12.8621 180.594 13.4141 180.785 13.9121C180.977 14.41 181.253 14.8376 181.615 15.1948C181.987 15.5521 182.439 15.8335 182.971 16.0392C183.503 16.234 184.104 16.3315 184.774 16.3315ZM205.781 23.4759C205.462 23.4759 205.266 23.3244 205.191 23.0213L204.968 20.9591C204.532 21.3921 204.096 21.7818 203.66 22.1282C203.234 22.4746 202.787 22.7669 202.319 23.005C201.851 23.2432 201.346 23.4218 200.804 23.5409C200.261 23.6708 199.666 23.7357 199.017 23.7357C198.474 23.7357 197.948 23.6545 197.437 23.4922C196.927 23.3298 196.475 23.0808 196.081 22.7452C195.688 22.4097 195.369 21.9821 195.124 21.4625C194.89 20.9321 194.773 20.2988 194.773 19.5627C194.773 18.8807 194.965 18.2475 195.348 17.6629C195.73 17.0784 196.326 16.5696 197.134 16.1366C197.953 15.7036 199.001 15.3572 200.277 15.0974C201.564 14.8376 203.106 14.6861 204.904 14.6428V12.9541C204.904 11.4602 204.585 10.3128 203.947 9.51176C203.319 8.69989 202.383 8.29395 201.139 8.29395C200.373 8.29395 199.719 8.4022 199.176 8.6187C198.645 8.8352 198.193 9.07335 197.82 9.33315C197.448 9.59294 197.145 9.83109 196.911 10.0476C196.677 10.2641 196.48 10.3723 196.321 10.3723C196.108 10.3723 195.948 10.2749 195.842 10.0801L195.571 9.60918C196.4 8.76484 197.273 8.12617 198.187 7.69317C199.102 7.26017 200.139 7.04367 201.298 7.04367C202.149 7.04367 202.894 7.1844 203.532 7.46585C204.17 7.73647 204.697 8.13158 205.111 8.65118C205.537 9.15995 205.856 9.78238 206.069 10.5185C206.281 11.2437 206.388 12.0556 206.388 12.9541V23.4759H205.781ZM199.4 22.6153C200.017 22.6153 200.58 22.5504 201.091 22.4205C201.612 22.2798 202.091 22.0903 202.527 21.8522C202.973 21.6032 203.388 21.3109 203.771 20.9754C204.154 20.6398 204.532 20.2772 204.904 19.8875V15.682C203.394 15.7253 202.091 15.8443 200.995 16.0392C199.91 16.2232 199.012 16.4776 198.299 16.8023C197.597 17.1271 197.076 17.5168 196.736 17.9714C196.406 18.4153 196.241 18.924 196.241 19.4978C196.241 20.039 196.326 20.5099 196.496 20.9104C196.677 21.3001 196.911 21.6249 197.198 21.8847C197.485 22.1336 197.82 22.3177 198.203 22.4367C198.586 22.5558 198.985 22.6153 199.4 22.6153ZM228.953 16.7861C229.112 17.0892 229.251 17.414 229.368 17.7604C229.431 17.5872 229.495 17.4194 229.559 17.257C229.633 17.0838 229.713 16.9214 229.798 16.7699L238.669 0.808511C238.754 0.667786 238.839 0.581187 238.924 0.548712C239.009 0.516237 239.126 0.5 239.275 0.5H240.408V23.4759H238.972V3.89362C238.972 3.61217 238.988 3.31448 239.02 3.00056L230.133 19.0593C229.984 19.3408 229.772 19.4815 229.495 19.4815H229.24C228.974 19.4815 228.761 19.3408 228.602 19.0593L219.476 2.98432C219.508 3.29825 219.524 3.60134 219.524 3.89362V23.4759H218.104V0.5H219.221C219.37 0.5 219.487 0.516237 219.572 0.548712C219.668 0.581187 219.758 0.667786 219.843 0.808511L228.953 16.7861ZM252.331 7.05991C253.224 7.05991 254.048 7.21687 254.803 7.53079C255.569 7.84472 256.229 8.30478 256.782 8.91097C257.345 9.50635 257.781 10.2424 258.09 11.1193C258.409 11.9961 258.569 13.0028 258.569 14.1394C258.569 14.3776 258.531 14.5399 258.457 14.6265C258.393 14.7131 258.287 14.7564 258.138 14.7564H246.667V15.0649C246.667 16.2773 246.805 17.3436 247.082 18.2637C247.358 19.1838 247.752 19.9578 248.262 20.5857C248.773 21.2027 249.39 21.6682 250.113 21.9821C250.836 22.296 251.645 22.453 252.538 22.453C253.336 22.453 254.027 22.3664 254.612 22.1932C255.197 22.0091 255.686 21.8089 256.08 21.5924C256.484 21.3651 256.803 21.1648 257.037 20.9916C257.271 20.8076 257.441 20.7156 257.547 20.7156C257.686 20.7156 257.792 20.7697 257.867 20.8779L258.281 21.3975C258.026 21.7223 257.686 22.0254 257.26 22.3068C256.846 22.5883 256.378 22.8318 255.856 23.0375C255.346 23.2324 254.793 23.3893 254.197 23.5084C253.612 23.6275 253.022 23.687 252.426 23.687C251.341 23.687 250.352 23.4976 249.459 23.1187C248.565 22.729 247.8 22.1661 247.161 21.43C246.523 20.6939 246.029 19.7954 245.678 18.7346C245.337 17.6629 245.167 16.4397 245.167 15.0649C245.167 13.9067 245.327 12.8404 245.646 11.8662C245.976 10.8811 246.444 10.0368 247.05 9.33315C247.667 8.6187 248.417 8.06122 249.299 7.66069C250.193 7.26017 251.203 7.05991 252.331 7.05991ZM252.346 8.21277C251.528 8.21277 250.794 8.34266 250.145 8.60246C249.496 8.86226 248.932 9.23572 248.454 9.72284C247.986 10.21 247.603 10.7945 247.305 11.4765C247.018 12.1585 246.826 12.9216 246.731 13.766H257.196C257.196 12.9 257.08 12.126 256.846 11.444C256.612 10.7512 256.282 10.1667 255.856 9.69037C255.431 9.21407 254.92 8.85144 254.325 8.60246C253.729 8.34266 253.07 8.21277 252.346 8.21277ZM262.645 23.4759V7.31971H263.474C263.751 7.31971 263.91 7.45502 263.953 7.72564L264.097 10.1613C264.82 9.23031 265.66 8.48339 266.617 7.92049C267.585 7.34677 268.659 7.05991 269.84 7.05991C270.712 7.05991 271.478 7.20063 272.137 7.48208C272.807 7.76353 273.36 8.16947 273.797 8.69989C274.233 9.23031 274.562 9.86898 274.786 10.6159C275.009 11.3628 275.121 12.2072 275.121 13.1489V23.4759H273.605V13.1489C273.605 11.6334 273.265 10.4481 272.584 9.59294C271.903 8.72695 270.861 8.29395 269.457 8.29395C268.415 8.29395 267.447 8.56999 266.553 9.12206C265.66 9.66331 264.862 10.4102 264.16 11.3628V23.4759H262.645ZM281.04 7.31971V17.6467C281.04 19.1622 281.38 20.3529 282.061 21.2189C282.741 22.0741 283.778 22.5017 285.172 22.5017C286.203 22.5017 287.166 22.2365 288.059 21.706C288.953 21.1648 289.756 20.4179 290.468 19.4653V7.31971H292V23.4759H291.154C290.857 23.4759 290.702 23.3352 290.692 23.0538L290.548 20.6506C289.814 21.5816 288.963 22.3285 287.996 22.8914C287.038 23.4543 285.969 23.7357 284.789 23.7357C283.906 23.7357 283.135 23.595 282.476 23.3135C281.816 23.0321 281.268 22.6262 280.832 22.0957C280.396 21.5653 280.066 20.9267 279.843 20.1797C279.62 19.4328 279.508 18.5885 279.508 17.6467V7.31971H281.04Z" fill="white"/>
                                        </svg>
                                        <?php
                                        break;
                                    case 'etheme_linked_var':
                                        ?>
                                        <svg width="411" height="43" viewBox="0 0 411 43" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <path d="M45.344 13.238C45.236 13.418 45.122 13.556 45.002 13.652C44.882 13.736 44.726 13.778 44.534 13.778C44.33 13.778 44.09 13.676 43.814 13.472C43.55 13.268 43.208 13.046 42.788 12.806C42.38 12.554 41.882 12.326 41.294 12.122C40.718 11.918 40.016 11.816 39.188 11.816C38.408 11.816 37.718 11.924 37.118 12.14C36.518 12.344 36.014 12.626 35.606 12.986C35.21 13.346 34.91 13.772 34.706 14.264C34.502 14.744 34.4 15.266 34.4 15.83C34.4 16.55 34.574 17.15 34.922 17.63C35.282 18.098 35.75 18.5 36.326 18.836C36.914 19.172 37.574 19.466 38.306 19.718C39.05 19.958 39.806 20.21 40.574 20.474C41.354 20.738 42.11 21.038 42.842 21.374C43.586 21.698 44.246 22.112 44.822 22.616C45.41 23.12 45.878 23.738 46.226 24.47C46.586 25.202 46.766 26.102 46.766 27.17C46.766 28.298 46.574 29.36 46.19 30.356C45.806 31.34 45.242 32.198 44.498 32.93C43.766 33.662 42.86 34.238 41.78 34.658C40.712 35.078 39.494 35.288 38.126 35.288C36.458 35.288 34.934 34.988 33.554 34.388C32.186 33.776 31.016 32.954 30.044 31.922L31.052 30.266C31.148 30.134 31.262 30.026 31.394 29.942C31.538 29.846 31.694 29.798 31.862 29.798C32.114 29.798 32.402 29.936 32.726 30.212C33.05 30.476 33.452 30.77 33.932 31.094C34.424 31.418 35.012 31.718 35.696 31.994C36.392 32.258 37.238 32.39 38.234 32.39C39.062 32.39 39.8 32.276 40.448 32.048C41.096 31.82 41.642 31.502 42.086 31.094C42.542 30.674 42.89 30.176 43.13 29.6C43.37 29.024 43.49 28.382 43.49 27.674C43.49 26.894 43.31 26.258 42.95 25.766C42.602 25.262 42.14 24.842 41.564 24.506C40.988 24.17 40.328 23.888 39.584 23.66C38.852 23.42 38.096 23.18 37.316 22.94C36.548 22.688 35.792 22.4 35.048 22.076C34.316 21.752 33.662 21.332 33.086 20.816C32.51 20.3 32.042 19.658 31.682 18.89C31.334 18.11 31.16 17.15 31.16 16.01C31.16 15.098 31.334 14.216 31.682 13.364C32.042 12.512 32.558 11.756 33.23 11.096C33.902 10.436 34.73 9.908 35.714 9.512C36.71 9.116 37.85 8.918 39.134 8.918C40.574 8.918 41.888 9.146 43.076 9.602C44.264 10.058 45.302 10.718 46.19 11.582L45.344 13.238ZM68.7898 9.206V12.14H60.4558V35H56.9638V12.14H48.5938V9.206H68.7898ZM94.6564 22.112C94.6564 24.044 94.3504 25.82 93.7384 27.44C93.1264 29.048 92.2624 30.434 91.1464 31.598C90.0304 32.762 88.6864 33.668 87.1144 34.316C85.5544 34.952 83.8264 35.27 81.9304 35.27C80.0344 35.27 78.3064 34.952 76.7464 34.316C75.1864 33.668 73.8484 32.762 72.7324 31.598C71.6164 30.434 70.7524 29.048 70.1404 27.44C69.5284 25.82 69.2224 24.044 69.2224 22.112C69.2224 20.18 69.5284 18.41 70.1404 16.802C70.7524 15.182 71.6164 13.79 72.7324 12.626C73.8484 11.45 75.1864 10.538 76.7464 9.89C78.3064 9.242 80.0344 8.918 81.9304 8.918C83.8264 8.918 85.5544 9.242 87.1144 9.89C88.6864 10.538 90.0304 11.45 91.1464 12.626C92.2624 13.79 93.1264 15.182 93.7384 16.802C94.3504 18.41 94.6564 20.18 94.6564 22.112ZM91.0744 22.112C91.0744 20.528 90.8584 19.106 90.4264 17.846C89.9944 16.586 89.3824 15.524 88.5904 14.66C87.7984 13.784 86.8384 13.112 85.7104 12.644C84.5824 12.176 83.3224 11.942 81.9304 11.942C80.5504 11.942 79.2964 12.176 78.1684 12.644C77.0404 13.112 76.0744 13.784 75.2704 14.66C74.4784 15.524 73.8664 16.586 73.4344 17.846C73.0024 19.106 72.7864 20.528 72.7864 22.112C72.7864 23.696 73.0024 25.118 73.4344 26.378C73.8664 27.626 74.4784 28.688 75.2704 29.564C76.0744 30.428 77.0404 31.094 78.1684 31.562C79.2964 32.018 80.5504 32.246 81.9304 32.246C83.3224 32.246 84.5824 32.018 85.7104 31.562C86.8384 31.094 87.7984 30.428 88.5904 29.564C89.3824 28.688 89.9944 27.626 90.4264 26.378C90.8584 25.118 91.0744 23.696 91.0744 22.112ZM103.255 24.236V35H99.7811V9.206H107.071C108.703 9.206 110.113 9.374 111.301 9.71C112.489 10.034 113.467 10.508 114.235 11.132C115.015 11.756 115.591 12.512 115.963 13.4C116.335 14.276 116.521 15.26 116.521 16.352C116.521 17.264 116.377 18.116 116.089 18.908C115.801 19.7 115.381 20.414 114.829 21.05C114.289 21.674 113.623 22.208 112.831 22.652C112.051 23.096 111.163 23.432 110.167 23.66C110.599 23.912 110.983 24.278 111.319 24.758L118.843 35H115.747C115.111 35 114.643 34.754 114.343 34.262L107.647 25.046C107.443 24.758 107.221 24.554 106.981 24.434C106.741 24.302 106.381 24.236 105.901 24.236H103.255ZM103.255 21.698H106.909C107.929 21.698 108.823 21.578 109.591 21.338C110.371 21.086 111.019 20.738 111.535 20.294C112.063 19.838 112.459 19.298 112.723 18.674C112.987 18.05 113.119 17.36 113.119 16.604C113.119 15.068 112.609 13.91 111.589 13.13C110.581 12.35 109.075 11.96 107.071 11.96H103.255V21.698ZM138.483 9.206V12.05H126.099V20.618H136.125V23.354H126.099V32.156H138.483V35H122.589V9.206H138.483Z" fill="white"/>
                                            <rect y="9" width="28" height="26" fill="url(#pattern0_2885_13)"/>
                                            <path opacity="0.7" d="M152.616 33.434H164.694V35H150.78V9.53H152.616V33.434ZM170.552 17.09V35H168.842V17.09H170.552ZM171.308 10.916C171.308 11.132 171.26 11.336 171.164 11.528C171.08 11.708 170.966 11.87 170.822 12.014C170.678 12.158 170.51 12.272 170.318 12.356C170.126 12.44 169.922 12.482 169.706 12.482C169.49 12.482 169.286 12.44 169.094 12.356C168.902 12.272 168.734 12.158 168.59 12.014C168.446 11.87 168.332 11.708 168.248 11.528C168.164 11.336 168.122 11.132 168.122 10.916C168.122 10.7 168.164 10.496 168.248 10.304C168.332 10.1 168.446 9.926 168.59 9.782C168.734 9.638 168.902 9.524 169.094 9.44C169.286 9.356 169.49 9.314 169.706 9.314C169.922 9.314 170.126 9.356 170.318 9.44C170.51 9.524 170.678 9.638 170.822 9.782C170.966 9.926 171.08 10.1 171.164 10.304C171.26 10.496 171.308 10.7 171.308 10.916ZM176.881 35V17.09H177.817C178.129 17.09 178.309 17.24 178.357 17.54L178.519 20.24C179.335 19.208 180.283 18.38 181.363 17.756C182.455 17.12 183.667 16.802 184.999 16.802C185.983 16.802 186.847 16.958 187.591 17.27C188.347 17.582 188.971 18.032 189.463 18.62C189.955 19.208 190.327 19.916 190.579 20.744C190.831 21.572 190.957 22.508 190.957 23.552V35H189.247V23.552C189.247 21.872 188.863 20.558 188.095 19.61C187.327 18.65 186.151 18.17 184.567 18.17C183.391 18.17 182.299 18.476 181.291 19.088C180.283 19.688 179.383 20.516 178.591 21.572V35H176.881ZM198.265 8.81V24.776H199.093C199.261 24.776 199.417 24.752 199.561 24.704C199.717 24.656 199.885 24.542 200.065 24.362L207.247 17.54C207.379 17.408 207.511 17.3 207.643 17.216C207.787 17.132 207.967 17.09 208.183 17.09H209.695L201.793 24.596C201.673 24.716 201.559 24.83 201.451 24.938C201.343 25.034 201.223 25.118 201.091 25.19C201.283 25.286 201.445 25.4 201.577 25.532C201.709 25.664 201.847 25.808 201.991 25.964L210.289 35H208.795C208.627 35 208.477 34.97 208.345 34.91C208.213 34.838 208.081 34.73 207.949 34.586L200.425 26.522C200.221 26.306 200.035 26.162 199.867 26.09C199.699 26.006 199.435 25.964 199.075 25.964H198.265V35H196.537V8.81H198.265ZM219.736 16.802C220.744 16.802 221.674 16.976 222.526 17.324C223.39 17.672 224.134 18.182 224.758 18.854C225.394 19.514 225.886 20.33 226.234 21.302C226.594 22.274 226.774 23.39 226.774 24.65C226.774 24.914 226.732 25.094 226.648 25.19C226.576 25.286 226.456 25.334 226.288 25.334H213.346V25.676C213.346 27.02 213.502 28.202 213.814 29.222C214.126 30.242 214.57 31.1 215.146 31.796C215.722 32.48 216.418 32.996 217.234 33.344C218.05 33.692 218.962 33.866 219.97 33.866C220.87 33.866 221.65 33.77 222.31 33.578C222.97 33.374 223.522 33.152 223.966 32.912C224.422 32.66 224.782 32.438 225.046 32.246C225.31 32.042 225.502 31.94 225.622 31.94C225.778 31.94 225.898 32 225.982 32.12L226.45 32.696C226.162 33.056 225.778 33.392 225.298 33.704C224.83 34.016 224.302 34.286 223.714 34.514C223.138 34.73 222.514 34.904 221.842 35.036C221.182 35.168 220.516 35.234 219.844 35.234C218.62 35.234 217.504 35.024 216.496 34.604C215.488 34.172 214.624 33.548 213.904 32.732C213.184 31.916 212.626 30.92 212.23 29.744C211.846 28.556 211.654 27.2 211.654 25.676C211.654 24.392 211.834 23.21 212.194 22.13C212.566 21.038 213.094 20.102 213.778 19.322C214.474 18.53 215.32 17.912 216.316 17.468C217.324 17.024 218.464 16.802 219.736 16.802ZM219.754 18.08C218.83 18.08 218.002 18.224 217.27 18.512C216.538 18.8 215.902 19.214 215.362 19.754C214.834 20.294 214.402 20.942 214.066 21.698C213.742 22.454 213.526 23.3 213.418 24.236H225.226C225.226 23.276 225.094 22.418 224.83 21.662C224.566 20.894 224.194 20.246 223.714 19.718C223.234 19.19 222.658 18.788 221.986 18.512C221.314 18.224 220.57 18.08 219.754 18.08ZM244.063 35C243.763 35 243.589 34.844 243.541 34.532L243.361 31.706C242.569 32.786 241.639 33.644 240.571 34.28C239.515 34.916 238.327 35.234 237.007 35.234C234.811 35.234 233.095 34.472 231.859 32.948C230.635 31.424 230.023 29.138 230.023 26.09C230.023 24.782 230.191 23.564 230.527 22.436C230.875 21.296 231.379 20.312 232.039 19.484C232.711 18.644 233.527 17.984 234.487 17.504C235.459 17.024 236.575 16.784 237.835 16.784C239.047 16.784 240.097 17.012 240.985 17.468C241.873 17.912 242.641 18.566 243.289 19.43V8.81H245.017V35H244.063ZM237.493 33.848C238.681 33.848 239.755 33.542 240.715 32.93C241.675 32.318 242.533 31.466 243.289 30.374V20.924C242.593 19.904 241.825 19.184 240.985 18.764C240.157 18.344 239.215 18.134 238.159 18.134C237.103 18.134 236.179 18.326 235.387 18.71C234.595 19.094 233.929 19.64 233.389 20.348C232.861 21.044 232.459 21.884 232.183 22.868C231.919 23.84 231.787 24.914 231.787 26.09C231.787 28.754 232.279 30.716 233.263 31.976C234.247 33.224 235.657 33.848 237.493 33.848ZM255.34 9.53H256.798C256.966 9.53 257.104 9.578 257.212 9.674C257.32 9.758 257.404 9.872 257.464 10.016L266.14 31.148C266.368 31.7 266.554 32.288 266.698 32.912C266.83 32.264 267.004 31.676 267.22 31.148L275.878 10.016C275.938 9.884 276.022 9.77 276.13 9.674C276.25 9.578 276.394 9.53 276.562 9.53H278.002L267.49 35H265.852L255.34 9.53ZM290.686 35C290.326 35 290.104 34.832 290.02 34.496L289.768 32.21C289.276 32.69 288.784 33.122 288.292 33.506C287.812 33.89 287.308 34.214 286.78 34.478C286.252 34.742 285.682 34.94 285.07 35.072C284.458 35.216 283.786 35.288 283.054 35.288C282.442 35.288 281.848 35.198 281.272 35.018C280.696 34.838 280.186 34.562 279.742 34.19C279.298 33.818 278.938 33.344 278.662 32.768C278.398 32.18 278.266 31.478 278.266 30.662C278.266 29.906 278.482 29.204 278.914 28.556C279.346 27.908 280.018 27.344 280.93 26.864C281.854 26.384 283.036 26 284.476 25.712C285.928 25.424 287.668 25.256 289.696 25.208V23.336C289.696 21.68 289.336 20.408 288.616 19.52C287.908 18.62 286.852 18.17 285.448 18.17C284.584 18.17 283.846 18.29 283.234 18.53C282.634 18.77 282.124 19.034 281.704 19.322C281.284 19.61 280.942 19.874 280.678 20.114C280.414 20.354 280.192 20.474 280.012 20.474C279.772 20.474 279.592 20.366 279.472 20.15L279.166 19.628C280.102 18.692 281.086 17.984 282.118 17.504C283.15 17.024 284.32 16.784 285.628 16.784C286.588 16.784 287.428 16.94 288.148 17.252C288.868 17.552 289.462 17.99 289.93 18.566C290.41 19.13 290.77 19.82 291.01 20.636C291.25 21.44 291.37 22.34 291.37 23.336V35H290.686ZM283.486 34.046C284.182 34.046 284.818 33.974 285.394 33.83C285.982 33.674 286.522 33.464 287.014 33.2C287.518 32.924 287.986 32.6 288.418 32.228C288.85 31.856 289.276 31.454 289.696 31.022V26.36C287.992 26.408 286.522 26.54 285.286 26.756C284.062 26.96 283.048 27.242 282.244 27.602C281.452 27.962 280.864 28.394 280.48 28.898C280.108 29.39 279.922 29.954 279.922 30.59C279.922 31.19 280.018 31.712 280.21 32.156C280.414 32.588 280.678 32.948 281.002 33.236C281.326 33.512 281.704 33.716 282.136 33.848C282.568 33.98 283.018 34.046 283.486 34.046ZM296.764 35V17.09H297.664C297.868 17.09 298.012 17.132 298.096 17.216C298.192 17.3 298.246 17.444 298.258 17.648L298.402 21.428C298.978 19.976 299.746 18.842 300.706 18.026C301.666 17.198 302.848 16.784 304.252 16.784C304.792 16.784 305.29 16.844 305.746 16.964C306.202 17.072 306.634 17.234 307.042 17.45L306.808 18.638C306.772 18.83 306.652 18.926 306.448 18.926C306.376 18.926 306.274 18.902 306.142 18.854C306.01 18.806 305.842 18.752 305.638 18.692C305.446 18.632 305.206 18.578 304.918 18.53C304.63 18.482 304.3 18.458 303.928 18.458C302.572 18.458 301.456 18.878 300.58 19.718C299.716 20.558 299.014 21.77 298.474 23.354V35H296.764ZM313.286 17.09V35H311.576V17.09H313.286ZM314.042 10.916C314.042 11.132 313.994 11.336 313.898 11.528C313.814 11.708 313.7 11.87 313.556 12.014C313.412 12.158 313.244 12.272 313.052 12.356C312.86 12.44 312.656 12.482 312.44 12.482C312.224 12.482 312.02 12.44 311.828 12.356C311.636 12.272 311.468 12.158 311.324 12.014C311.18 11.87 311.066 11.708 310.982 11.528C310.898 11.336 310.856 11.132 310.856 10.916C310.856 10.7 310.898 10.496 310.982 10.304C311.066 10.1 311.18 9.926 311.324 9.782C311.468 9.638 311.636 9.524 311.828 9.44C312.02 9.356 312.224 9.314 312.44 9.314C312.656 9.314 312.86 9.356 313.052 9.44C313.244 9.524 313.412 9.638 313.556 9.782C313.7 9.926 313.814 10.1 313.898 10.304C313.994 10.496 314.042 10.7 314.042 10.916ZM331.045 35C330.685 35 330.463 34.832 330.379 34.496L330.127 32.21C329.635 32.69 329.143 33.122 328.651 33.506C328.171 33.89 327.667 34.214 327.139 34.478C326.611 34.742 326.041 34.94 325.429 35.072C324.817 35.216 324.145 35.288 323.413 35.288C322.801 35.288 322.207 35.198 321.631 35.018C321.055 34.838 320.545 34.562 320.101 34.19C319.657 33.818 319.297 33.344 319.021 32.768C318.757 32.18 318.625 31.478 318.625 30.662C318.625 29.906 318.841 29.204 319.273 28.556C319.705 27.908 320.377 27.344 321.289 26.864C322.213 26.384 323.395 26 324.835 25.712C326.287 25.424 328.027 25.256 330.055 25.208V23.336C330.055 21.68 329.695 20.408 328.975 19.52C328.267 18.62 327.211 18.17 325.807 18.17C324.943 18.17 324.205 18.29 323.593 18.53C322.993 18.77 322.483 19.034 322.063 19.322C321.643 19.61 321.301 19.874 321.037 20.114C320.773 20.354 320.551 20.474 320.371 20.474C320.131 20.474 319.951 20.366 319.831 20.15L319.525 19.628C320.461 18.692 321.445 17.984 322.477 17.504C323.509 17.024 324.679 16.784 325.987 16.784C326.947 16.784 327.787 16.94 328.507 17.252C329.227 17.552 329.821 17.99 330.289 18.566C330.769 19.13 331.129 19.82 331.369 20.636C331.609 21.44 331.729 22.34 331.729 23.336V35H331.045ZM323.845 34.046C324.541 34.046 325.177 33.974 325.753 33.83C326.341 33.674 326.881 33.464 327.373 33.2C327.877 32.924 328.345 32.6 328.777 32.228C329.209 31.856 329.635 31.454 330.055 31.022V26.36C328.351 26.408 326.881 26.54 325.645 26.756C324.421 26.96 323.407 27.242 322.603 27.602C321.811 27.962 321.223 28.394 320.839 28.898C320.467 29.39 320.281 29.954 320.281 30.59C320.281 31.19 320.377 31.712 320.569 32.156C320.773 32.588 321.037 32.948 321.361 33.236C321.685 33.512 322.063 33.716 322.495 33.848C322.927 33.98 323.377 34.046 323.845 34.046ZM342.019 35.288C340.807 35.288 339.859 34.952 339.175 34.28C338.503 33.608 338.167 32.57 338.167 31.166V18.926H335.593C335.461 18.926 335.353 18.89 335.269 18.818C335.185 18.746 335.143 18.644 335.143 18.512V17.846L338.203 17.63L338.635 11.222C338.647 11.114 338.689 11.018 338.761 10.934C338.845 10.85 338.953 10.808 339.085 10.808H339.895V17.648H345.529V18.926H339.895V31.076C339.895 31.568 339.955 31.994 340.075 32.354C340.207 32.702 340.381 32.99 340.597 33.218C340.825 33.446 341.089 33.614 341.389 33.722C341.689 33.83 342.013 33.884 342.361 33.884C342.793 33.884 343.165 33.824 343.477 33.704C343.789 33.572 344.059 33.434 344.287 33.29C344.515 33.134 344.701 32.996 344.845 32.876C344.989 32.744 345.103 32.678 345.187 32.678C345.283 32.678 345.379 32.738 345.475 32.858L345.943 33.614C345.487 34.118 344.905 34.526 344.197 34.838C343.501 35.138 342.775 35.288 342.019 35.288ZM352.169 17.09V35H350.459V17.09H352.169ZM352.925 10.916C352.925 11.132 352.877 11.336 352.781 11.528C352.697 11.708 352.583 11.87 352.439 12.014C352.295 12.158 352.127 12.272 351.935 12.356C351.743 12.44 351.539 12.482 351.323 12.482C351.107 12.482 350.903 12.44 350.711 12.356C350.519 12.272 350.351 12.158 350.207 12.014C350.063 11.87 349.949 11.708 349.865 11.528C349.781 11.336 349.739 11.132 349.739 10.916C349.739 10.7 349.781 10.496 349.865 10.304C349.949 10.1 350.063 9.926 350.207 9.782C350.351 9.638 350.519 9.524 350.711 9.44C350.903 9.356 351.107 9.314 351.323 9.314C351.539 9.314 351.743 9.356 351.935 9.44C352.127 9.524 352.295 9.638 352.439 9.782C352.583 9.926 352.697 10.1 352.781 10.304C352.877 10.496 352.925 10.7 352.925 10.916ZM365.32 16.802C366.604 16.802 367.75 17.024 368.758 17.468C369.778 17.9 370.636 18.518 371.332 19.322C372.028 20.126 372.556 21.098 372.916 22.238C373.288 23.366 373.474 24.632 373.474 26.036C373.474 27.44 373.288 28.706 372.916 29.834C372.556 30.962 372.028 31.928 371.332 32.732C370.636 33.536 369.778 34.154 368.758 34.586C367.75 35.018 366.604 35.234 365.32 35.234C364.036 35.234 362.884 35.018 361.864 34.586C360.856 34.154 359.998 33.536 359.29 32.732C358.594 31.928 358.06 30.962 357.688 29.834C357.328 28.706 357.148 27.44 357.148 26.036C357.148 24.632 357.328 23.366 357.688 22.238C358.06 21.098 358.594 20.126 359.29 19.322C359.998 18.518 360.856 17.9 361.864 17.468C362.884 17.024 364.036 16.802 365.32 16.802ZM365.32 33.884C366.388 33.884 367.318 33.704 368.11 33.344C368.914 32.972 369.58 32.444 370.108 31.76C370.648 31.076 371.05 30.254 371.314 29.294C371.578 28.322 371.71 27.236 371.71 26.036C371.71 24.848 371.578 23.768 371.314 22.796C371.05 21.824 370.648 20.996 370.108 20.312C369.58 19.616 368.914 19.082 368.11 18.71C367.318 18.338 366.388 18.152 365.32 18.152C364.252 18.152 363.316 18.338 362.512 18.71C361.72 19.082 361.054 19.616 360.514 20.312C359.986 20.996 359.584 21.824 359.308 22.796C359.044 23.768 358.912 24.848 358.912 26.036C358.912 27.236 359.044 28.322 359.308 29.294C359.584 30.254 359.986 31.076 360.514 31.76C361.054 32.444 361.72 32.972 362.512 33.344C363.316 33.704 364.252 33.884 365.32 33.884ZM377.975 35V17.09H378.911C379.223 17.09 379.403 17.24 379.451 17.54L379.613 20.24C380.429 19.208 381.377 18.38 382.457 17.756C383.549 17.12 384.761 16.802 386.093 16.802C387.077 16.802 387.941 16.958 388.685 17.27C389.441 17.582 390.065 18.032 390.557 18.62C391.049 19.208 391.421 19.916 391.673 20.744C391.925 21.572 392.051 22.508 392.051 23.552V35H390.341V23.552C390.341 21.872 389.957 20.558 389.189 19.61C388.421 18.65 387.245 18.17 385.661 18.17C384.485 18.17 383.393 18.476 382.385 19.088C381.377 19.688 380.477 20.516 379.685 21.572V35H377.975ZM407.315 19.304C407.231 19.472 407.099 19.556 406.919 19.556C406.787 19.556 406.613 19.484 406.397 19.34C406.193 19.184 405.917 19.016 405.569 18.836C405.233 18.644 404.813 18.476 404.309 18.332C403.817 18.176 403.211 18.098 402.491 18.098C401.843 18.098 401.249 18.194 400.709 18.386C400.181 18.566 399.725 18.812 399.341 19.124C398.969 19.436 398.675 19.802 398.459 20.222C398.255 20.63 398.153 21.062 398.153 21.518C398.153 22.082 398.297 22.55 398.585 22.922C398.873 23.294 399.251 23.612 399.719 23.876C400.187 24.14 400.715 24.368 401.303 24.56C401.903 24.752 402.515 24.944 403.139 25.136C403.763 25.328 404.369 25.544 404.957 25.784C405.557 26.012 406.091 26.3 406.559 26.648C407.027 26.996 407.405 27.422 407.693 27.926C407.981 28.43 408.125 29.042 408.125 29.762C408.125 30.542 407.981 31.268 407.693 31.94C407.417 32.612 407.009 33.194 406.469 33.686C405.941 34.178 405.287 34.568 404.507 34.856C403.727 35.144 402.839 35.288 401.843 35.288C400.583 35.288 399.497 35.09 398.585 34.694C397.673 34.286 396.857 33.758 396.137 33.11L396.551 32.498C396.611 32.402 396.677 32.33 396.749 32.282C396.821 32.234 396.923 32.21 397.055 32.21C397.211 32.21 397.403 32.306 397.631 32.498C397.859 32.69 398.153 32.9 398.513 33.128C398.885 33.344 399.341 33.548 399.881 33.74C400.433 33.932 401.111 34.028 401.915 34.028C402.671 34.028 403.337 33.926 403.913 33.722C404.489 33.506 404.969 33.218 405.353 32.858C405.737 32.498 406.025 32.078 406.217 31.598C406.421 31.106 406.523 30.59 406.523 30.05C406.523 29.45 406.379 28.952 406.091 28.556C405.803 28.16 405.425 27.824 404.957 27.548C404.489 27.272 403.955 27.038 403.355 26.846C402.767 26.654 402.155 26.462 401.519 26.27C400.895 26.078 400.283 25.868 399.683 25.64C399.095 25.412 398.567 25.124 398.099 24.776C397.631 24.428 397.253 24.008 396.965 23.516C396.677 23.012 396.533 22.388 396.533 21.644C396.533 21.008 396.671 20.396 396.947 19.808C397.223 19.22 397.613 18.704 398.117 18.26C398.633 17.816 399.257 17.462 399.989 17.198C400.721 16.934 401.543 16.802 402.455 16.802C403.547 16.802 404.513 16.958 405.353 17.27C406.205 17.582 406.985 18.062 407.693 18.71L407.315 19.304Z" fill="white"/>
                                            <defs>
                                                <pattern id="pattern0_2885_13" patternContentUnits="objectBoundingBox" width="1" height="1">
                                                    <use xlink:href="#image0_2885_13" transform="matrix(0.00769231 0 0 0.00828402 0 -0.00532544)"/>
                                                </pattern>
                                                <image id="image0_2885_13" width="130" height="122" xlink:href="data:image/png;base64,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"/>
                                            </defs>
                                        </svg>
                                        <?php
                                        break;
                                    default:
                                        break;
                                }
                            endif; ?>
                        </div>
                        <?php
                        $banner_items_limit = false;
                        $banner_items = array();
                        switch ($_GET['post_type']) {
                            case 'etheme_slides':
                                $banner_items = array(
                                    sprintf(esc_html__('Start by creating a %snew slide%s in this section.', 'xstore-core'), '<a href="'.add_query_arg('post_type', $_GET['post_type'], admin_url('post-new.php')).'" target="_blank"">', '</a>'),
                                    sprintf(esc_html__('Go to the %spages%s and edit using the Elementor Website Builder.', 'xstore-core'), '<a href="'.admin_url('edit.php?post_type=page').'" target="_blank"">', '</a>'),
                                    sprintf(esc_html__('Choose the "%s" widget.', 'xstore-core'), '<a href="'.etheme_documentation_url(false, false).'" target="_blank"">' . sprintf(esc_html__('%s Slider', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')) . '</a>'),
                                    sprintf(esc_html__('Select your pre-made slides created in the %s Slides section.', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore'))
                                );
                                break;
                            case 'etheme_mega_menus':
                                $banner_items = array(
                                    sprintf(esc_html__('Start by creating a %snew mega menu%s in this section.', 'xstore-core'), '<a href="'.add_query_arg('post_type', $_GET['post_type'], admin_url('post-new.php')).'" target="_blank"">', '</a>'),
                                    sprintf(esc_html__('Go to the %sheaders%s and edit using the Elementor Website Builder.', 'xstore-core'), '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank"">', '</a>'),
                                    sprintf(esc_html__('Choose the "%s" widget.', 'xstore-core'), '<a href="'.etheme_documentation_url(false, false).'" target="_blank"">' . sprintf(esc_html__('%s Mega Menu', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore')) . '</a>'),
                                    sprintf(esc_html__('Select your pre-made mega menus created in the %s Mega Menus section.', 'xstore-core'), apply_filters('etheme_theme_label', 'XStore'))
                                );
                                break;
                            case 'etheme_linked_var':
                                $banner_items_limit = 3;
$banner_items = array(sprintf(esc_html__('Create Product Attributes: %s', 'xstore-core'),
       '<ul><li> - '.sprintf(esc_html__('Go to "%s" in your dashboard.', 'xstore-core'), '<a href="'.admin_url('edit.php?post_type=product&page=product_attributes').'" target="_blank">'.esc_html__('Product Attributes', 'xstore-core').'</a>').'</li>'.
       '<li> - '.esc_html__('Add the needed attributes (like color, size) for your variations.', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Set Attribute Values: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Define the values for each attribute (e.g., red, blue for color; small, large for size)', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Create or Edit Products: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Add new products or update existing ones.', 'xstore-core').'</li>'.
        '<li> - '.esc_html__('Assign relevant attributes to each product.', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Assign Attributes to Variations: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('For each product, set the attributes that will be used for variations (e.g., Product 01: red, small).', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Cover All Combinations: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Ensure all possible attribute combinations are represented (e.g., Product 01: red, small; Product 02: blue, large).', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Create Linked Variation Group: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Create a group that combines the related products.', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Set Up Linked Variations: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Add products to the linked group.', 'xstore-core').'</li>'.
        '<li> - '.esc_html__('Link the attributes across these products.', 'xstore-core') . '</li></ul>'),
    sprintf(esc_html__('Review and Finalize: %s', 'xstore-core'),
        '<ul><li> - '.esc_html__('Double-check that all variations work smoothly on the product page.', 'xstore-core') . '</li></ul>'),
);
                                break;
                        }
                        if ( count($banner_items) ) {
                            echo '<ol>';
                            $banner_items_count = 0;
                            $hidden_items_class = '';
                            foreach ($banner_items as $banner_item) {
                                if ( !!$banner_items_limit && $banner_items_count >= $banner_items_limit )
                                    $hidden_items_class = 'hidden';
                                echo '<li class="'.$hidden_items_class.'">' . $banner_item . '</li>';
                                $banner_items_count++;
                            }
                            echo '</ol>';
                            if ( !!$banner_items_limit && $banner_items_count >= $banner_items_limit ) {
                                echo '<div class="read-more-toggle"><span class="et-button et-button-sm et-button-white">'.esc_html__('Read more', 'xstore-core').'</span></div>';
                            }
                        }
                        ?>
                    </div>
                    <div class="etheme_custom_post_type-banner-tutorial <?php echo esc_attr($_GET['post_type']); ?>-banner-tutorial" data-video-ready="<?php echo esc_attr(!!$video_id); ?>">
                        <span class="play-icon">
                            <svg width="74" height="56" viewBox="0 0 74 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M72.2414 45.888C71.5042 49.7581 68.3713 52.7067 64.5013 53.2596C58.4197 54.1811 48.2838 55.2868 36.8579 55.2868C25.6162 55.2868 15.4803 54.1811 9.21446 53.2596C5.34439 52.7067 2.21147 49.7581 1.47431 45.888C0.737157 41.6494 0 35.3835 0 27.6434C0 19.9032 0.737157 13.6374 1.47431 9.39875C2.21147 5.52868 5.34439 2.58005 9.21446 2.02718C15.296 1.10574 25.4319 0 36.8579 0C48.2838 0 58.2354 1.10574 64.5013 2.02718C68.3713 2.58005 71.5042 5.52868 72.2414 9.39875C72.9786 13.6374 73.9 19.9032 73.9 27.6434C73.7157 35.3835 72.9786 41.6494 72.2414 45.888Z" fill="#EB3324"/>
                                <path d="M29.4863 40.5437V14.7432L51.601 27.6434L29.4863 40.5437Z" fill="white"/>
                            </svg>
                        </span>
                        <img src="<?php echo !!$video_id ? 'https://img.youtube.com/vi/'.esc_attr($video_id).'/maxresdefault.jpg' : ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/placeholder-350x268.png'; ?>" alt="<?php echo esc_attr__('Video Placeholder', 'xstore-core'); ?>">
                    </div>
                </div>

                <input type="hidden" name="nonce_etheme-custom_post_type_create" value="<?php echo wp_create_nonce( 'etheme-custom_post_type_create' ); ?>">
            </div>
            <script id="<?php echo esc_attr($_GET['post_type']); ?>-banner-js">
                jQuery(document).ready(function ($) {
                    $('#wpwrap').prepend('<div class="et_panel-popup"></div>');
                    //let banner = $(".<?php //echo esc_js($_GET['post_type']) ?>//-banner");
                    let banner_tutorial = $(".<?php echo esc_js($_GET['post_type']) ?>-banner-tutorial");
                    banner_tutorial.on('click', function () {
                        if ( !$(this).data('video-ready') ) return;
                        let popup = $(document).find('.et_panel-popup');
                        $('body').addClass('et_panel-popup-on');

                        popup.addClass('auto-size').html('<iframe width="888" height="500" src="https://www.youtube.com/embed/<?php echo esc_js($video_id); ?>?controls=1&showinfo=0&controls=0&rel=0&autoplay=1&start=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                        popup.prepend('<span class="et_close-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>');
                        $('.et_panel-popup').addClass('active');
                    });
                    $('.etheme_custom_post_type-banner-info .read-more-toggle').on('click', function () {
                        $(this).parent().find('li').removeClass('hidden');
                        $(this).remove();
                    })
                    //    $("#<?php //echo esc_js($_GET['post_type']) ?>//-banner-js").remove();
                });
            </script>
		<?php }
	}

	public function custom_type_settings() {

        $enabled_portfolio = get_theme_mod('portfolio_projects', true);
        $enabled_brands = class_exists('Woocommerce') && get_theme_mod('enable_brands', true);

		/**
		 *
		 * Add Etheme section block to permalink setting page.
		 *
		 */
		if( $enabled_portfolio || $enabled_brands ){
			add_settings_section(
				'et_section',
				sprintf(esc_html__( '%s permalink settings' , 'xstore-core' ), apply_filters('etheme_theme_author_name', '8Theme')),
				array( $this, 'section_callback' ),
				'permalink'
			);
		}

		/**
		 *
		 * Add "Brand base" setting field to Etheme section block.
		 *
		 */
		if ( $enabled_brands ) {
			add_settings_field(
				'brand_base',
				esc_html__( 'Brand base' , 'xstore-core' ),
				array( $this, 'brand_callback' ),
				'permalink',
				'optional'
			);
		}

		if( $enabled_portfolio ){
			/**
			 *
			 * Add "Portfolio base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_base',
				esc_html__( 'Portfolio base' , 'xstore-core' ),
				array( $this, 'portfolio_callback' ),
				'permalink',
				'optional'
			);

			/**
			 *
			 * Add "Portfolio category base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_cat_base',
				esc_html__( 'Portfolio category base' , 'xstore-core' ),
				array( $this, 'portfolio_cat_callback' ),
				'permalink',
				'optional'
			);
		}
	}


	public function section_callback() {
		/**
		 *
		 * Callback function for Etheme section block.
		 *
		 */

        $enabled_portfolio = get_theme_mod('portfolio_projects', true);
        $enabled_brands = class_exists('Woocommerce') && get_theme_mod('enable_brands', true);

		$checked['portfolio_def'] = ( get_option( 'et_permalink' ) == 'portfolio_def' || ! get_option( 'et_permalink' ) ) ? 'checked' : '';
		$checked['portfolio_cat_base'] = ( get_option( 'et_permalink' ) == 'portfolio_cat_base' ) ? 'checked' : '';
		$checked['portfolio_custom_base'] = ( get_option( 'et_permalink' ) == 'portfolio_custom_base' ) ? 'checked' : '';
        $is_woocommerce = class_exists('Woocommerce');
        $shop_url = $is_woocommerce && get_permalink( wc_get_page_id( 'shop' ) ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url() . '/shop/';

		if ( $enabled_brands ) {
			$checked['brand_def'] = ( get_option( 'et_brand_permalink' ) == 'brand_def' || ! get_option( 'et_brand_permalink' ) ) ? 'checked' : '';
			$checked['brand_shop_base'] = ( get_option( 'et_brand_permalink' ) == 'brand_shop_base' || ! get_option( 'et_brand_permalink' ) ) ? 'checked' : '';
			$checked['brand_custom_base'] = ( get_option( 'et_brand_permalink' ) == 'brand_custom_base' ) ? 'checked' : '';

			echo '
				<p>' . sprintf(esc_html__( '%s brand permalink settings.' , 'xstore-core' ), apply_filters('etheme_theme_author_name', '8Theme')) . '</p>
				</tbody></tr></th>
				<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><input class="et-inp-brand" type="radio" name="et_brand_permalink" value="brand_def" ' . $checked['brand_def'] . ' >' . esc_html__( 'Default' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/brand-base/brand-archive/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input class="et-inp-brand" type="radio" name="et_brand_permalink" value="brand_shop_base" ' . $checked['brand_shop_base'] . '>' . esc_html__( 'Shop page base' , 'xstore-core' ) . '</label></th>
								<td><code>' . $shop_url . 'brand-base/brand-archive/</code></td>
								<input type="hidden" id="brand-custom-base" name="brand_custom_base" value="' . get_option( 'brand_custom_base' ) . '">
							</tr>
							
						</tbody>
				</table> 
			';
		}

		if( $enabled_portfolio || $enabled_brands ){
			echo '
				<p>' . sprintf(__( '%s portfolio permalink settings.' , 'xstore-core' ), apply_filters('etheme_theme_author_name', '8Theme')) . '</p>
				</tbody></tr></th>
				<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_def" ' . $checked['portfolio_def'] . ' >' . esc_html__( 'Default' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_cat_base" ' . $checked['portfolio_cat_base'] . '>' . esc_html__( 'Portfolio category base' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/portfolio-category/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input id="portfolio-custom-base-select" type="radio" name="et_permalink" value="portfolio_custom_base" ' . $checked['portfolio_custom_base'] . '>' . esc_html__( 'Portfolio custom Base' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base</code><input id="portfolio-custom-base" name="portfolio_custom_base" type="text" value="' . get_option( 'portfolio_custom_base' ) . '" class="regular-text code" /></td>
							</tr>
						</tbody>
				</table>

				<script type="text/javascript">
					jQuery( function() {
						jQuery("input.et-inp, input.et-inp-brand").change(function() {
							
							var link = "";

							if ( jQuery( this ).val() == "portfolio_cat_base" ) {
								link = "/%portfolio_category%";
							} else if ( jQuery( this ).val() == "brand_shop_base" ) {
								link = "' . basename( $shop_url ) . '";
							}
							
							if ( jQuery( this ).is( ".et-inp-brand" ) ){
								jQuery("#brand-custom-base").val( link );
							} else {
								jQuery("#portfolio-custom-base").val( link );
							}
						});

						jQuery("input:checked").change();
						jQuery("#portfolio-custom-base").focus( function(){
							jQuery("#portfolio-custom-base-select").click();
						} );
					} );
				</script>

				'
			;
		}
	}


	public function portfolio_callback() {
		/**
		 *
		 * Callback function for "portfolio base" setting field.
		 *
		 */

		echo '<input 
			name="portfolio_base"  
			type="text" 
			value="' . get_option( 'portfolio_base' ) . '" 
			class="regular-text code"
			placeholder="project"
		 />';
	}

	public function brand_callback() {
		/**
		 *
		 * Callback function for "brand base" setting field.
		 *
		 */

		echo '<input 
			name="brand_base"  
			type="text" 
			value="' . get_option( 'brand_base' ) . '" 
			class="regular-text code"
			placeholder="brand"
		 />';
	}

	public function portfolio_cat_callback() {
		/**
		 *
		 * Callback function for "portfolio catogory base" setting field.
		 *
		 */

		echo '<input 
			name="portfolio_cat_base"  
			type="text" 
			value="' . get_option( 'portfolio_cat_base' ) . '" 
			class="regular-text code"
			placeholder="portfolio-category"
		 />';
	}


	public function seatings_for_permalink() {
		/**
		 *
		 * Make it work on permalink page.
		 *
		 */
		if ( ! is_admin() ) {
			return;
		}

		if( isset( $_POST['brand_base'] ) ) {
			update_option( 'brand_base', sanitize_title_with_dashes( $_POST['brand_base'] ) );
		}

		if( isset( $_POST['portfolio_base'] ) ) {
			update_option( 'portfolio_base', sanitize_title_with_dashes( $_POST['portfolio_base'] ) );
		}

		if( isset( $_POST['portfolio_cat_base'] ) ) {
			update_option( 'portfolio_cat_base', sanitize_title_with_dashes( $_POST['portfolio_cat_base'] ) );
		}

		if( isset( $_POST['et_permalink'] ) ) {
			update_option( 'et_permalink', sanitize_title_with_dashes( $_POST['et_permalink'] ) );
		}

		if( isset( $_POST['portfolio_custom_base'] ) ) {
			update_option( 'portfolio_custom_base', $_POST['portfolio_custom_base'] );
		}

		if( isset( $_POST['et_brand_permalink'] ) ) {
			update_option( 'et_brand_permalink', sanitize_title_with_dashes( $_POST['et_brand_permalink'] ) );
		}

		if( isset( $_POST['brand_custom_base'] ) ) {
			update_option( 'brand_custom_base', sanitize_title_with_dashes( $_POST['brand_custom_base'] ) );
		}
	}

	/**
	 * Product brands image filed description
	 * @return [type] [description]
	 */
	public function add_brand_fileds() {

		$this->view->add_brand_fileds(
			array(
				'thumbnail'   			  =>	esc_html__( 'Thumbnail', 'xstore-core' ),
				'upload'      			  =>	esc_html__( 'Upload/Add image', 'xstore-core' ),
				'remove'      			  =>	esc_html__( 'Remove image', 'xstore-core' ),
			)
		);

	}

	/**
	 * Product brands edit single tax image filed
	 * @param  [type] $term     [description]
	 * @param  [type] $taxonomy [description]
	 * @return [type]           [description]
	 */
	public function edit_brand_fields($term, $taxonomy ) {
		$thumbnail_id 	= absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );

		$image = ( $thumbnail_id ) ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();


		$this->view->edit_brand_fields(
			array(
				'thumbnail'   		=>	esc_html__( 'Thumbnail', 'xstore-core' ),
				'upload'      		=>	esc_html__( 'Upload/Add image', 'xstore-core' ),
				'remove'      		=>	esc_html__( 'Remove image', 'xstore-core' ),
				'thumbnail_id'      =>	$thumbnail_id,
				'image'      		=>	$image,
			)
		);
	}

	/**
	 * Admin local script parts to enqueue
	 * @return [type] [description]
	 */
	public function admin_local_scripts() {
		$screen = get_current_screen();
        if ( in_array($screen->id, array('edit-etheme_slides', 'edit-etheme_mega_menus')) ) {
            $selector = str_replace('edit-', '', $screen->id);
            $link_affix = str_replace('edit-etheme_', '', $screen->id);
            wp_add_inline_script( 'etheme_admin_js', "
                jQuery(document).ready(function($) {
                    setTimeout(function () {
                        $(document).find('.type-".$selector."').each( function() {
                            let _this = $(this);
                            console.log(_this.find('.edit_with_elementor a').attr('href'));
                            _this.find('.edit_with_elementor a').attr('href', _this.find('.edit_with_elementor a').attr('href') + '&et_page=".$link_affix."');
                        });
                    }, 500);
		    });", 'after' );
        }
        switch ($screen->id) {
            case 'edit-staticblocks':
                wp_add_inline_script( 'etheme_admin_js', "
                jQuery(document).ready(function($) {
                    $(document).on('click', '.copy-staticblock-code', function (e) {
                    e.preventDefault();
                    var n = $(e.currentTarget),
                        i = ($(n).text(), $(n).next('pre').text()),
                        r = $('<input>');
                    $('body').append(r), r.val(i).select(), document.execCommand('copy'), r.remove();
                    let _this = $(this);
                    let text = _this.data('text');
                    let success_text = _this.data('success-text');
                    _this.text(success_text);
                    setTimeout(function () {
                        _this.text(text);
                    }, 1000);
                });
		    });", 'after' );
                break;
            case 'edit-brand':
                wp_enqueue_media();
                break;
            case 'edit-etheme_slides':
                wp_add_inline_script( 'etheme_admin_js', "
                jQuery(document).ready(function($) {
                    $('.etheme-slides-previewer').on('mouseover', function () {
                        if ( $(this).hasClass('loaded')) return;
            
                        let iframe = $(this).find('.mt-mes iframe');
                        iframe.html('').attr('src', iframe.attr('data-src'));
                        iframe.attr('data-src', null);
                        iframe.on('load', function() {
                            iframe.removeClass('loading');
                        });
            
                        $(this).addClass('loaded');
                    });
            
                    $('.etheme-slides-previewer').each(function () {
                        let url = $(this).find('>a').attr('href');
                        let tr = $(this).parents('tr');
                        if ( !!url )
                            tr.find('.row-title').attr('href', url);
                    });
		    });", 'after' );
                break;
        }
	}

	/**
	 * Product brands Save image fields
	 * @param  [type] $term_id  [description]
	 * @param  [type] $tt_id    [description]
	 * @param  [type] $taxonomy [description]
	 * @return [type]           [description]
	 */
	public function brands_fields_save($term_id, $tt_id, $taxonomy ) {
		if ( isset( $_POST['brand_thumbnail_id'] ) ){
			if (function_exists( 'update_term_meta' )){
				update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['brand_thumbnail_id'] ), '' );
			} else {
				update_metadata( 'woocommerce_term', $term_id, 'thumbnail_id', absint( $_POST['brand_thumbnail_id'] ), '' );
			}
		}
		delete_transient( 'wc_term_counts' );
	}
}