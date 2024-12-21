<?php
/**
 * Reviews advanced functionality for products
 *
 * @package    reviews-advanced.php
 * @since      9.1.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_WooCommerce_Product_Reviews_Advanced {
 
    public static $option_name = 'customer_reviews';
    
    public $settings = array();
	
	/**
	 * constructor method.
	 * @since 9.1.3
	 */
	public function __construct() {
		if ( !class_exists('WooCommerce')) return;
		if ( !get_option('xstore_sales_booster_settings_'.self::$option_name.'_advanced') ) return;

        add_action( 'wp_ajax_xstore_comments_likes', array( $this, 'ajax_comments_likes' ) );

        $this->init_vars();
        $this->hooks();
	}
	
	/**
	 * Initialize main settings values.
	 *
	 * @since 9.1.3
	 *
	 * @return void
	 */
	public function init_vars() {

        $postfix = '_advanced';
		$settings = (array)get_option('xstore_sales_booster_settings', array());
		
		$default = array(
            'rating_summary_position' => 'comments_start',
			'rating_summary' => 'on',
			'pros_cons' => false,
			'likes' => 'on',
            'reset_likes' => false,
            'verified_owner_badge' => 'on',
            'rating_criteria' => false,
            'rating_criteria_required' => false,
            'criteria' => '',
            'criteria_ready' => [],
            'circle_avatars' => false,
            'date_format' => 'ago',
            'date_format_custom' => wc_date_format()
		);
		
		$local_settings = $default;
		
		if (count($settings) && isset($settings[self::$option_name . $postfix])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name . $postfix ], $default );
		}

		$force_check_switchers = array(
			'rating_summary',
            'pros_cons',
            'likes',
            'reset_likes',
            'verified_owner_badge',
            'rating_criteria',
            'rating_criteria_required',
            'circle_avatars'
        );
		foreach ($force_check_switchers as $switcher) {
		    $local_settings[$switcher] = $local_settings[$switcher] == 'on';
        }

		if ($local_settings['criteria'] ) {
		    $criteria_unique_slugs = array();
            $criteria = explode(',', $local_settings['criteria']);
            if (count($criteria)) {
                foreach ($criteria as $criteria_key) {
                    if ('' == $criteria_key || '' == $local_settings[$criteria_key . '_slug']) continue;
                    if ( in_array($local_settings[$criteria_key . '_slug'], $criteria_unique_slugs)) continue;
                    $criteria_ready = array(
                        'slug' => $local_settings[$criteria_key . '_slug'],
                        'name' => $local_settings[$criteria_key . '_name'],
                    );

                    if (array_filter($criteria_ready)) {
                        $local_settings['criteria_ready'][] = $criteria_ready;
                        $criteria_unique_slugs[] = $criteria_ready['slug'];
                    }
                }

                // empty all values
                if (!array_filter($local_settings['criteria_ready']))
                    $local_settings['criteria_ready'] = array();
            }
        }
		
		$this->settings = $local_settings;
    }
    
	/**
	 * Hooks for creating/saving/removing data in comment form.
	 *
	 * @since 9.1.3
	 *
	 * @return void
	 */
	public function hooks() {

	    add_action('wp_enqueue_scripts', array($this, 'load_styles'), 40);

	    // change the position of star rating in review comment
	    remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
        add_action('woocommerce_review_before_comment_text', 'woocommerce_review_display_rating', 5);

        if (in_array($this->settings['date_format'], array('ago', 'custom'))) {
            add_action('woocommerce_review_meta', array($this, 'set_comment_date_format_custom'), 1);
            add_action('woocommerce_review_meta', array($this, 'reset_comment_date_format_custom'), 100);
        }

	    // pros and cons
        if ( $this->settings['pros_cons'] ) {
            add_action('comment_post', array($this, 'save_comment_pros_cons'));
            add_filter('comment_text', array($this, 'render_pros_cons'), 30, 2);
            add_filter('woocommerce_product_review_comment_form_args', array($this, 'add_pros_cons_fields'));
        }
        if ( $this->settings['rating_summary'] ) {
            add_filter('comment_post_redirect', array($this, 'redirect_after_comment'));
            $summary_hook = str_replace(
                array('above_all', 'comments_start', 'review_start'),
                array('etheme_before_product_comments_wrapper', 'etheme_before_product_comments', 'etheme_before_product_review_form'),
                $this->settings['rating_summary_position']
            );
            add_action($summary_hook, array($this, 'render_rating_summary'));
        }
        if ( $this->settings['verified_owner_badge'] ) {
            add_filter( 'comment_author', array( $this, 'add_author_icon' ), 10, 2 );
        }

        if ( $this->settings['rating_criteria'] && count($this->settings['criteria_ready']) ) {
            remove_action( 'woocommerce_review_before_comment_text', 'woocommerce_review_display_rating', 5 );
            add_action( 'woocommerce_review_before_comment_text', array( $this, 'render_criteria_star_rating' ), 7 );
            add_filter('etheme_product_review_rating_html', array($this, 'add_criteria_ratings'), 10);
            add_filter('cr_review_form_before_comment', array($this, 'add_criteria_ratings'), 10);
            add_action( 'comment_post', array( $this, 'save_comment_rating_criteria' ) );
            add_filter('etheme_et_js_config', function ($config) {
                if ( get_query_var('is_single_product', false) || get_query_var('is_single_product_shortcode', false) ) {
                    if ( !array_key_exists('sales_booster_reviews_advanced', $config) )
                        $config['sales_booster_reviews_advanced'] = array();
                    $config['sales_booster_reviews_advanced'] = array_merge(array(
                        'criteria_list' => wp_json_encode($this->settings['criteria_ready']),
                        'criteria_required' => $this->settings['rating_criteria_required']
                    ), $config['sales_booster_reviews_advanced']);
                }
                return $config;
            });
        }

        if ( $this->settings['circle_avatars'] ) {
            add_action('woocommerce_review_before', array($this, 'set_avatar_circle'), 1);
            add_action('woocommerce_review_before', array($this, 'reset_avatar_circle'), 100);
        }

        if ( $this->settings['likes'] ) {
            add_action( 'comment_post', array( $this, 'save_comment_likes' ) );

            add_action( 'woocommerce_review_after_comment_text', array( $this, 'render_likes' ) );

            add_filter('etheme_et_js_config', function ($config) {
                if ( get_query_var('is_single_product', false) || get_query_var('is_single_product_shortcode', false) ) {
                    $config['is_loggedIn'] = get_query_var( 'et_is-loggedin', false);
                    $config['woocommerceSettings'] = array_merge($config['woocommerceSettings'], array(
                        'myaccount_url' => $config['woocommerceSettings']['is_woocommerce'] ? esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) : '',
                        'voted_text' => esc_html__('Voted', 'xstore')
                    ));

                    if ( !array_key_exists('sales_booster_reviews_advanced', $config) )
                        $config['sales_booster_reviews_advanced'] = array();

                    $config['sales_booster_reviews_advanced'] = array_merge(array(
                        'cheating_likes' => esc_html__('Cheating huh?', 'xstore'),
                        'reset_likes' => $this->settings['reset_likes']
                    ), $config['sales_booster_reviews_advanced']);
                }
                return $config;
            });
        }

	}

	public function load_styles() {
	    if ( get_query_var('is_single_product', false) || get_query_var('is_single_product_shortcode', false) ) {
            wp_enqueue_style('etheme-sale-booster-reviews-advanced');
            if ( $this->settings['likes'] )
                wp_enqueue_script( 'et_reviews_likes' );
            if ( $this->settings['rating_criteria'] && count($this->settings['criteria_ready']) )
                wp_enqueue_script( 'et_reviews_criteria' );
        }
    }
    /**
     * Converting data to human-readable version (X days ago)
     * @param $date
     * @param $format
     * @param $comment
     * @return string
     */
    public function human_time_ago($date, $format, $comment) {
        return sprintf(_x( '%s ago', '%s = human-readable time difference', 'xstore' ),
            human_time_diff(
                    get_comment_time( 'U', false, true, $comment->comment_ID ),
                    current_time( 'timestamp' )
            ) );
    }

    /**
     * Set the date format to custom one if the options are set.
     * @param $format
     * @return mixed
     */
    public function custom_date_format($format) {
        if ( empty($this->settings['date_format_custom']) )
            return $format;

        return $this->settings['date_format_custom'];
    }

    /**
     * Setter of custom date format
     */
    public function set_comment_date_format_custom() {
	    if ( $this->settings['date_format'] == 'ago' )
            add_filter('get_comment_date', array($this, 'human_time_ago'), 10, 3);
	    else
            add_filter('woocommerce_date_format', array($this, 'custom_date_format'), 10, 1);
    }

    /**
     * Resetter of custom date format
     */
    public function reset_comment_date_format_custom() {
        if ( $this->settings['date_format'] == 'ago' )
            remove_filter('get_comment_date', array($this, 'human_time_ago'), 10, 3);
        else
            remove_filter('woocommerce_date_format', array($this, 'custom_date_format'), 10, 1);
    }

    /**
     * Render rating summary.
     *
     * @return string|void
     */
    public function render_rating_summary() {

        if ( ! function_exists( 'wc_review_ratings_enabled' ) || ! wc_review_ratings_enabled() ) {
            return '';
        }

        global $product;

        $rating_count = $product->get_rating_count();

        if ( $rating_count < 1 ) return;

        $rating_item = array(
            5 => $product->get_rating_count(5),
            4 => $product->get_rating_count(4),
            3 => $product->get_rating_count(3),
            2 => $product->get_rating_count(2),
            1 => $product->get_rating_count(1)
        );

        $average_rating         = $product->get_average_rating();

        ?>

        <div class="et-product-rating-summary">
            <h2>
                <?php echo esc_html($average_rating); ?>
            </h2>
            <div class="et-product-avg-rating">
                <?php echo wc_get_rating_html( $average_rating, $rating_count ); // WPCS: XSS ok. ?>
                <div class="et-product-avg-rating-number">
                    <?php echo sprintf(esc_html__('Based on %s reviews', 'xstore'), $rating_count); ?>
                </div>
            </div>

            <div class="et-product-ratings">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <div class="et-product-rating">
                        <span class="et-product-rating-stars">
                            <?php echo wc_get_rating_html( $i, $rating_item[$i] ); // WPCS: XSS ok. ?>
                        </span>
                        <span class="et-product-rating-progress">
                            <progress max="<?php echo esc_html($rating_count); ?>" value="<?php echo esc_html($rating_item[$i]); ?>"></progress>
                        </span>
                        <span class="et-product-rating-count">
                            <?php echo esc_html($rating_item[$i]); ?>
                        </span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Return same product url for refreshing rating summary and other comment data.
     * @param $location
     * @return mixed
     */
    public function redirect_after_comment( $location ) { // phpcs:ignore.
        return wp_unslash( $_SERVER['HTTP_REFERER'] ); // phpcs:ignore.
    }

    /**
     * Adds comment data (pros, cons)
     *
     * @param $comment_id
     */
    public function save_comment_pros_cons( $comment_id ) {
        if ( isset( $_POST['pros'], $_POST['cons'], $_POST['comment_post_ID'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) {
            add_comment_meta( $comment_id, 'et_pros', trim( $_POST['pros'] ), true );
            add_comment_meta( $comment_id, 'et_cons', trim( $_POST['cons'] ), true );
        }
    }

    /**
     * Renders pros & cons fields for comment form.
     * @param $comment_form
     * @return mixed
     */
    public function add_pros_cons_fields( $comment_form ) {
        if ( ! $this->settings['pros_cons'] ) {
            return $comment_form;
        }

        $comment_form['comment_field'] .= '<p class="comment-form-pros"><label for="pros">' . esc_html__( 'Pros', 'xstore' ) . '</label><input id="pros" name="pros" type="text" value="" size="30"/></p>';

        $comment_form['comment_field'] .= '<p class="comment-form-cons"><label for="cons">' . esc_html__( 'Cons', 'xstore' ) . '</label><input id="cons" name="cons" type="text" value="" size="30"/></p>';

        return $comment_form;
    }

    /**
     * Render pros & cons for each comment if one's has such data set on posting.
     * @param $comment_content
     * @param $comment
     * @return string
     */
    public function render_pros_cons( $comment_content, $comment ) {
        if ( ! $this->settings['pros_cons'] || ( ! wp_doing_ajax() && ( is_admin() || ! is_singular( 'product' ) ) ) ) {
            return $comment_content;
        }

        $pros_list = array_filter( get_comment_meta( $comment->comment_ID, 'et_pros' ) );
        $cons_list = array_filter( get_comment_meta( $comment->comment_ID, 'et_cons' ) );

        if ( empty( $pros_list ) && empty( $cons_list ) )
            return $comment_content;

        ob_start();
        ?>
        <div class="et-review-arguments">
            <?php foreach ( $pros_list as $pros ) :
                if ( empty( $pros ) ) {
                    continue;
                }
                ?>

                <div class="et-pros">
                    <div class="et-argument-label">
                        <?php $this->get_review_icon('pros', true); ?>
                        <?php echo esc_html__( 'Pros:', 'xstore' ); ?>
                    </div>
                    <p>
                        <?php echo esc_html( $pros ); ?>
                    </p>
                </div>
            <?php endforeach;

            foreach ( $cons_list as $cons ) :
                if ( empty( $cons ) )
                    continue;
                ?>

                <div class="et-cons">
                    <div class="et-argument-label">
                        <?php $this->get_review_icon('cons', true); ?>
                        <?php echo esc_html__( 'Cons:', 'xstore' ); ?>
                    </div>
                    <p>
                        <?php echo esc_html( $cons ); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php

        return $comment_content . ob_get_clean();
    }

    /**
     * Set "verified-owner" icon next to author
     *
     * @param $author
     * @param $comment_ID
     * @return string
     */
    public function add_author_icon( $author, $comment_ID ) {
        if ( !function_exists('wc_review_is_from_verified_owner') || !wc_review_is_from_verified_owner( $comment_ID ) ) {
//        if ( ( $this->is_parent_comment( $comment_ID ) && ! $this->is_user_purchased_product( $comment_ID ) ) || ( ! wp_doing_ajax() && is_admin() ) ) {
            return $author;
        }

        return $this->get_review_icon('verified-owner') . $author;
    }

    /**
     * Saving of comment likes/dislikes
     * @param $comment_id
     */
    public function save_comment_likes( $comment_id ) {
        if ( 'product' !== get_post_type( absint( $_POST['comment_post_ID'] ) ) ) {
            return;
        }

        update_comment_meta( $comment_id, 'et_likes', 0 );
        update_comment_meta( $comment_id, 'et_dislikes', 0 );
        update_comment_meta( $comment_id, 'et_total_votes', 0 );
    }

    /**
     * Ajax technique for recounting like/dislikes on clicking the relative icons
     * @return int
     */
    public function ajax_comments_likes() {
        if ( ! isset( $_POST['comment_id'], $_POST['vote'] ) ) {
            return 0;
        }

        $comment_id       = $_POST['comment_id'];
        $vote             = $_POST['vote'];
        $reset_vote       = $_POST['reset_vote'];
        $current_user_id  = get_current_user_id();

        if ( metadata_exists( 'comment', $comment_id, 'et_votes' ) ) {
            $meta_votes = get_comment_meta($comment_id, 'et_votes', true);
            $meta_votes[$current_user_id] = $vote;
        }
        else
            $meta_votes[$current_user_id] = $vote;

        foreach ( $meta_votes as $user_id => $meta_vote ) {
            if ( $user_id !== $current_user_id )
                $meta_votes[$current_user_id] = $vote;
            else
                $meta_votes[$user_id] = $vote;
        }

        if ( in_array($reset_vote, array('like', 'dislike')) && isset($meta_votes[$current_user_id])) {
            unset($meta_votes[$current_user_id]);
        }

        $votes_counted = array_count_values( $meta_votes );

        if ( ! isset( $votes_counted['like'] ) ) {
            $votes_counted['like'] = 0;
        }

        if ( ! isset( $votes_counted['dislike'] ) ) {
            $votes_counted['dislike'] = 0;
        }

        $likes   = $votes_counted['like'];
        $dislike = $votes_counted['dislike'];
        $total   = $votes_counted['like'] + $votes_counted['dislike'];

        update_comment_meta( $comment_id, 'et_votes', $meta_votes );
        update_comment_meta( $comment_id, 'et_likes', $likes );
        update_comment_meta( $comment_id, 'et_dislikes', $dislike );
        update_comment_meta( $comment_id, 'et_total_votes', $total );

        wp_send_json(
            array(
                'likes'    => $likes,
                'dislikes' => $dislike,
            )
        );
    }

    /**
     * Render likes/dislikes html for each comment with count and tooltip if customer is not logged in.
     * @param $data_object
     */
    public function render_likes( $data_object ) {
        if ( '0' !== $data_object->comment_parent || ( ! wp_doing_ajax() && ( is_admin() || ! is_singular( 'product' ) ) ) ) {
            return;
        }

        $likes    = metadata_exists( 'comment', $data_object->comment_ID, 'et_likes' ) ? get_comment_meta( $data_object->comment_ID, 'et_likes', true ) : 0;
        $dislikes = metadata_exists( 'comment', $data_object->comment_ID, 'et_dislikes' ) ? get_comment_meta( $data_object->comment_ID, 'et_dislikes', true ) : 0;

        $current_user_id  = get_current_user_id();
        $voters = get_comment_meta($data_object->comment_ID, 'et_votes', true);
        $like_left = isset($voters[$current_user_id]) && $voters[$current_user_id] == 'like';
        $dislike_left = isset($voters[$current_user_id]) && $voters[$current_user_id] == 'dislike';

        $like_text = $dislike_text = esc_attr__('Log in to leave your voice', 'xstore');
        if ( get_query_var( 'et_is-loggedin', false) ) {
            $like_text = $dislike_text = false;
            if ( $like_left )
                $like_text = esc_html__('Voted', 'xstore');
            elseif ( $dislike_left )
                $dislike_text = esc_html__('Voted', 'xstore');

        }

        ?>
        <div class="et-review-votes">
            <span><?php echo esc_html__('Is it helpful?', 'xstore'); ?></span>
            <div class="et-review-vote<?php if ($like_left) echo ' et-review-voted'; ?>" data-vote-type="like" data-votes="<?php echo esc_attr( $likes ); ?>"<?php if ($like_text) echo ' data-text="' . $like_text . '"'; ?>>
                <?php $this->get_review_icon('like', true); ?>
            </div>
            <div class="et-review-vote<?php if ($dislike_left) echo ' et-review-voted'; ?>" data-vote-type="dislike" data-votes="<?php echo esc_attr( $dislikes ); ?>"<?php if ($dislike_text) echo ' data-text="' . $dislike_text . '"'; ?>>
                <?php $this->get_review_icon('dislike', true); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Getter of icon from the list by name
     * @param string $icon_key
     * @param bool $echo
     * @return string
     */
    public function get_review_icon($icon_key = '', $echo = false) {

        if ( !get_theme_mod('bold_icons', 0) ) {

            $icons_package = array(
                    'verified-owner' => '<svg version="1.1" width="0.85em" height="0.85em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 24 24" style="margin-inline-end: 5px;" xml:space="preserve" fill="#2e7d32">
                    <path d="M8.5700073,17.7399902c-1.4700317,0-2.5900269,1.1199951-2.5900269,2.5900269
                        c0,1.4599609,1.1199951,2.5899658,2.5900269,2.5899658c1.460022,0,2.5899658-1.1300049,2.5899658-2.5899658
                        C11.1599731,18.8599854,10.0300293,17.7399902,8.5700073,17.7399902z M8.5700073,21.6500244
                        c-0.7000122,0-1.3200073-0.6300049-1.3200073-1.3200073c0-0.7000122,0.6199951-1.3200073,1.3200073-1.3200073
                        c0.6900024,0,1.3200073,0.6199951,1.3200073,1.3200073C9.8900146,21.0200195,9.2600098,21.6500244,8.5700073,21.6500244z"/>
                    <path d="M23.7600098,4.25c-0.0900269-0.0900269-0.2200317-0.2199707-0.4500122-0.2299805
                        c-0.0200195-0.0100098-0.0299683-0.0100098-0.0499878-0.0100098h-3.7399902c-0.3000488,0-0.5400391,0.2399902-0.5400391,0.539978
                        v0.0900269c0,0.2999878,0.2399902,0.539978,0.5400391,0.539978h2.8699951l-1.6099854,6.7000122L6.7199707,13.7000122
                        L5.0200195,5.1799927h3.0499878c0.2999878,0,0.5499878-0.2399902,0.5499878-0.539978V4.5499878
                        c0-0.2600098-0.1900024-0.4799805-0.4400024-0.5299683c-0.039978-0.0100098-0.0700073-0.0100098-0.1099854-0.0100098h-3.289978
                        L4.2999878,1.6099854c-0.0299683-0.289978-0.3900146-0.5299683-0.6300049-0.5299683H0.7199707
                        c-0.3799438,0-0.6199951,0.2600098-0.6199951,0.6199951s0.2600098,0.6499634,0.6199951,0.6499634h2.4200439l2.3299561,11.8300171
                        c0.3100586,1.6099854,1.8500366,2.8599854,3.4800415,2.8599854h11.2799683c0.3900146,0,0.6300049-0.2599487,0.6300049-0.6199951
                        c0-0.3599854-0.2699585-0.6300049-0.6300049-0.6300049H9.0700073c-0.6900024,0-1.3400269-0.3099976-1.7000122-0.8099976
                        l14.0599976-1.9199829c0.2700195,0,0.5300293-0.2399902,0.5300293-0.5299683l1.9699707-7.8300171V4.6799927
                        C23.9000244,4.6300049,23.9000244,4.3900146,23.7600098,4.25z"/>
                    <path d="M18.3599854,17.7399902c-1.4599609,0-2.5899658,1.1199951-2.5899658,2.5900269
                        c0,1.4599609,1.1300049,2.5899658,2.5899658,2.5899658c1.460022,0,2.5900269-1.1300049,2.5900269-2.5899658
                        C20.9500122,18.8599854,19.8200073,17.7399902,18.3599854,17.7399902z M18.3800049,21.6500244
                        c-0.6900024,0-1.3400269-0.6000366-1.3400269-1.3200073c0-0.7200317,0.6199951-1.3200073,1.3200073-1.3200073
                        s1.3400269,0.6199951,1.3400269,1.3200073C19.7000122,21.0200195,19.0800171,21.6500244,18.3800049,21.6500244z"/>
                    <path d="M12.7885742,7.9296875c0.121582,0.1210938,0.2875977,0.190918,0.456543,0.190918
                        c0.1621094,0,0.3134766-0.065918,0.4375-0.1889648L19.234375,2.527832
                        c0.1201172-0.1191406,0.1923828-0.3022461,0.1923828-0.4892578c0-0.1611328-0.0664062-0.3105469-0.1826172-0.4160156
                        c-0.0849609-0.1176758-0.2314453-0.1938477-0.3837891-0.1982422C18.6865234,1.3999023,18.4980469,1.472168,18.375,1.5966797
                        l-5.109375,4.9477539l-2.6625977-2.7421875c-0.2421875-0.2407227-0.7709961-0.2294922-0.940918,0.0297852
                        c-0.125,0.1499023-0.1865234,0.3408203-0.1679688,0.5234375C9.5092773,4.5078125,9.5791016,4.640625,9.671875,4.7128906
                        L12.7885742,7.9296875z"/>
                </svg>',
                'pros' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                    <path d="M12,0C5.3759766,0,0,5.3759766,0,12s5.3759766,12,12,12s12-5.3759766,12-12S18.6240234,0,12,0z M12,22.7280273
                        C6.0720215,22.7280273,1.2719727,17.9279785,1.2719727,12S6.0720215,1.2719727,12,1.2719727S22.7280273,6.0960083,22.7280273,12
                        C22.7280273,17.9279785,17.9279785,22.7280273,12,22.7280273z"/>
                    <path d="M16.7012329,11.269043l-3.9713745,0.0007935l0.0004272-3.9717407c0-0.402771-0.3265991-0.7293701-0.7293701-0.7293701
                        c-0.40271,0-0.7299805,0.3272705-0.7299805,0.7300415l-0.0007935,3.9713745l-3.9713745,0.0007935
                        c-0.402771,0-0.7300415,0.3272705-0.7300415,0.7299805c0,0.402771,0.3265991,0.7293701,0.7293701,0.7293701l3.9717407-0.0004272
                        l-0.0007935,3.9713745c0,0.402771,0.3272705,0.7300415,0.7300415,0.7300415c0.201355,0,0.3838501-0.0818481,0.5159912-0.2139893
                        s0.2139893-0.3146362,0.2139893-0.5160522l0.0005493-3.9716187l3.9716187-0.0005493
                        c0.201416,0,0.3839111-0.0818481,0.5160522-0.2139893s0.2139893-0.3146362,0.2139893-0.5159912
                        C17.4312744,11.5963135,17.1040039,11.269043,16.7012329,11.269043z"/>
                </svg>',
                'cons' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                        <path d="M12,0C5.3759766,0,0,5.3759766,0,12s5.3759766,12,12,12s12-5.3759766,12-12S18.6240234,0,12,0z M12,22.7280273
                            C6.0720215,22.7280273,1.2719727,17.9279785,1.2719727,12S6.0720215,1.2719727,12,1.2719727S22.7280273,6.0960083,22.7280273,12
                            C22.7280273,17.9279785,17.9279785,22.7280273,12,22.7280273z"/>
                        <path d="M11.2701416,11.2701416l-3.9713745,0.0007935c-0.402771,0-0.7300415,0.3272705-0.7300415,0.7299805
                            c0,0.402771,0.3265991,0.7293701,0.7293701,0.7293701l3.9717407-0.0004272l-0.0007935,0.0004272h1.460022l0,0l0,0
                            l0.0005493-0.0006714l3.9716187-0.0005493c0.201416,0,0.3839111-0.0818481,0.5160522-0.2139893
                            s0.2139893-0.3146362,0.2139893-0.5159912c0-0.402771-0.3272705-0.7300415-0.7300415-0.7300415l-3.9713745,0.0007935"/>
                    </svg>',
                'like' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width="1em" height="1em">
                    <path d="M23.4828033,14.5867081c0-0.4765177-0.1510906-0.9244642-0.4377766-1.3017073
                        C23.6368008,12.8840275,24,12.2036343,24,11.4825611c0-1.195653-0.9724064-2.1685438-2.1675758-2.1685438h-7.4315491
                        c0.3176794-1.0179272,0.8038826-2.7065639,0.8038826-3.5225525c0-1.9888811-1.4750748-4.7554941-2.9278736-4.7554941h-1.3094559
                        c-0.3399553,0-0.6164713,0.2765161-0.6164713,0.6164708v3.986479l-2.3884006,4.7768021V9.9304886
                        c0-0.3399553-0.276516-0.6164713-0.6164708-0.6164713H0.6201124c-0.1748201,0-0.3433447,0.0769987-0.4619899,0.2106562
                        C0.0389929,9.6583309-0.017182,9.8341198,0.00461,10.0070028l1.552073,12.4170694
                        c0.0377728,0.3079929,0.3026663,0.539957,0.6155021,0.539957h5.1739006c0.3399549,0,2.5864658,0,2.5864658,0l0,0l0,0l0,0h9.8301191
                        c1.1961384,0,2.1685448-0.9728909,2.1685448-2.1690292c0-0.3830547-0.1055698-0.7602978-0.3070259-1.0983162
                        c0.8077564-0.3355961,1.3414173-1.1264019,1.3414173-2.0058289c0-0.4770031-0.1510906-0.9244652-0.4377766-1.3012238
                        C23.1196041,15.9886589,23.4828033,15.3082647,23.4828033,14.5867081z M21.8324242,12.4181643h-0.5171967
                        c-0.3399544,0-0.6169548,0.276516-0.6169548,0.6164703c0,0.3399553,0.2770004,0.6164713,0.6169548,0.6164713
                        c0.5152607,0,0.9356022,0.4198589,0.9356022,0.9356022s-0.4203415,0.9356022-0.9356022,0.9356022h-0.5181637
                        c-0.3399544,0-0.6159878,0.276516-0.6159878,0.6164703s0.2760334,0.6169548,0.6159878,0.6169548
                        c0.5162277,0,0.9365711,0.4193745,0.9365711,0.9351196c0,0.5157433-0.4203434,0.9356022-0.9365711,0.9356022h-1.0343933
                        c-0.3399544,0-0.6159859,0.276516-0.6159859,0.6164703s0.2760315,0.6169548,0.6159859,0.6169548
                        c0.5152588,0,0.9356022,0.4193745,0.9356022,0.9351177s-0.4203434,0.9356022-0.9356022,0.9356022H9.9325514l0,0
                        c-0.2738123,0,0.3399544,0,0,0l-1.969995-0.0348759v-9.596694h0.1593232c0.2329321,0,0.4513369-0.1355944,0.5573912-0.3457661
                        L11.524334,6.063138c0.040679-0.0794191,0.0595646-0.1656189,0.0595646-0.2716732V2.269397h0.6929855
                        c0.3796644,0,1.6959,1.7060697,1.6959,3.5220678c0,0.797102-0.7389898,3.175334-0.9975882,3.9448328
                        c-0.0653763,0.1908007-0.0363197,0.3995199,0.0784512,0.5588436c0.1123495,0.1578703,0.2992764,0.2518177,0.5007315,0.2518177
                        h8.2780457c0.5152588,0,0.9356022,0.4198589,0.9356022,0.9356022
                        C22.7680264,11.9983053,22.347683,12.4181643,21.8324242,12.4181643z M6.7291303,10.5469589v11.1836433H2.7126267
                        L1.31455,10.5469589H6.7291303z"/>
                    <path d="M5.0177336,20.8942757c0.4828134,0,0.8755536-0.3927402,0.8755536-0.8750687
                        c0-0.4828148-0.3927402-0.875555-0.8755536-0.875555s-0.8755531,0.3927402-0.8755531,0.875555
                        C4.1421804,20.5015354,4.5349202,20.8942757,5.0177336,20.8942757z"/>
                        
                    <path class="vote-active-svg-path" d="M10.9549475,1.0452101c-0.2873192,0-0.521657,0.234338-0.521657,0.5216566v4.0428391l-2.0866261,5.346981v10.9547892
	c0.2159977,0.6072407,0.8843708,1.043314,1.56497,1.043314h9.9114761c1.1513119,0,2.0866261-0.9353142,2.0866261-2.0866261
	c0-0.4299603-0.1365261-0.8252773-0.3586388-1.1574268c0.8130512-0.2852802,1.4019527-1.0636902,1.4019527-1.9725132
	c0-0.5114689-0.1833954-0.9740314-0.4890537-1.3367462c0.6052036-0.3647518,1.0107098-1.035162,1.0107098-1.7931948
	c0-0.5114679-0.1833935-0.9740305-0.4890518-1.3367453c0.6052017-0.3647518,1.0107098-1.035162,1.0107098-1.7931948
	c0-1.1513119-0.9353142-2.0866261-2.086628-2.0866261H14.280509c0.3178844-1.0066347,0.8476915-2.8079796,0.8476915-3.651597
	c0-1.9643633-1.4386311-4.69491-2.8528099-4.69491H10.9549475z M0.5218142,9.391717
	c-0.1487536,0-0.2913943,0.0672445-0.3912425,0.1793194s-0.1487535,0.25879-0.1304142,0.4075441l1.56497,12.5197601
	c0.0326035,0.260828,0.2587909,0.4564495,0.5216566,0.4564495h4.69491c0.2873187,0,0.521657-0.2322998,0.521657-0.521656V9.913373
	c0-0.2873182-0.2343383-0.521656-0.521657-0.521656H0.5218142z M4.9558959,19.303194
	c0.4319968,0,0.782485,0.3504868,0.782485,0.7824841s-0.3504882,0.782486-0.782485,0.782486s-0.782485-0.3504887-0.782485-0.782486
	S4.5238991,19.303194,4.9558959,19.303194z"/>
                </svg>',
                'dislike' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width="1em" height="1em">
                    <path d="M22.5278301,7.6103692c0.2866859-0.3767591,0.4377766-0.8242211,0.4377766-1.3012233
                        c0-0.8794274-0.5336609-1.6702342-1.3414173-2.0058308c0.2014561-0.3380177,0.3070259-0.7152612,0.3070259-1.0983157
                        c0-1.1961374-0.9724064-2.1690283-2.1685448-2.1690283H9.9325514c-0.6774883,0-2.5864658,0-2.5864658,0l0,0l0,0l0,0H2.1721854
                        c-0.3128359,0-0.5777295,0.2319635-0.6155021,0.5399568L0.00461,13.9929972
                        c-0.021792,0.172883,0.0343829,0.3486719,0.1535124,0.4823294c0.1186452,0.1336575,0.2871699,0.2106562,0.4619899,0.2106562
                        h6.7259731c0.3399549,0,0.6164708-0.276516,0.6164708-0.6164713v-0.4852343l2.3884006,4.7768011v3.9864788
                        c0,0.3399563,0.276516,0.6164722,0.6164713,0.6164722h1.3094559c1.4527988,0,2.9278736-2.766613,2.9278736-4.7554951
                        c0-0.8159885-0.4862032-2.5046244-0.8038826-3.5225515h7.4315491C23.0275936,14.6859827,24,13.7130919,24,12.5174389
                        c0-0.7210732-0.3631992-1.4014664-0.9549732-1.8024397c0.2866859-0.377243,0.4377766-0.8251896,0.4377766-1.3017073
                        C23.4828033,8.6917353,23.1196041,8.0113411,22.5278301,7.6103692z M22.7680264,12.5174389
                        c0,0.5157433-0.4203434,0.9356022-0.9356022,0.9356022h-8.2780457c-0.2014551,0-0.388382,0.0939474-0.5007315,0.2518177
                        c-0.1147709,0.1593237-0.1438274,0.3680429-0.0784512,0.5588436c0.2585983,0.7694988,0.9975882,3.1477299,0.9975882,3.9448318
                        c0,1.8159981-1.3162355,3.522068-1.6959,3.522068h-0.6929855v-3.522068c0-0.1060543-0.0188856-0.1922531-0.0595646-0.2716732
                        l-2.8450632-5.6901274c-0.1060543-0.2101717-0.3244591-0.3457661-0.5573912-0.3457661H7.9625564v-8.596693V2.2693973
                        l1.969995,0.0000005l0,0h9.8301191c0.5152588,0,0.9356022,0.4198587,0.9356022,0.9356022s-0.4203434,0.9351182-0.9356022,0.9351182
                        c-0.3399544,0-0.6159859,0.277-0.6159859,0.6169548s0.2760315,0.6164713,0.6159859,0.6164713h1.0343933
                        c0.5162277,0,0.9365711,0.4198585,0.9365711,0.9356022s-0.4203434,0.9351177-0.9365711,0.9351177
                        c-0.3399544,0-0.6159878,0.2770004-0.6159878,0.6169553c0,0.3399544,0.2760334,0.6164703,0.6159878,0.6164703h0.5181637
                        c0.5152607,0,0.9356022,0.4198589,0.9356022,0.9356022s-0.4203415,0.9356022-0.9356022,0.9356022
                        c-0.3399544,0-0.6169548,0.276516-0.6169548,0.6164713c0,0.3399544,0.2770004,0.6164703,0.6169548,0.6164703h0.5171967
                        C22.347683,11.5818357,22.7680264,12.0016947,22.7680264,12.5174389z M1.31455,13.4530411L2.7126267,2.2693973h4.0165033
                        v11.1836433H1.31455z"/>
                    <path d="M5.0177336,3.1057248c0.4828134,0,0.8755536,0.39274,0.8755536,0.8750691
                        c0,0.4828134-0.3927402,0.8755531-0.8755536,0.8755531S4.1421804,4.4636073,4.1421804,3.980794
                        C4.1421804,3.4984648,4.5349202,3.1057248,5.0177336,3.1057248z"/>
                        
                        <path class="vote-active-svg-path" d="M12.2753906,22.9547901c1.4141788,0,2.8528099-2.730547,2.8528099-4.69491
	c0-0.8436165-0.5298071-2.6449623-0.8476915-3.651597h7.6292276c1.1513138,0,2.086628-0.9353142,2.086628-2.0866261
	c0-0.7580328-0.405508-1.428443-1.0107098-1.7931948c0.3056583-0.3627148,0.4890518-0.8252773,0.4890518-1.3367453
	c0-0.7580328-0.4055061-1.428443-1.0107098-1.7931952c0.3056583-0.3627143,0.4890537-0.8252769,0.4890537-1.3367448
	c0-0.908824-0.5889015-1.6872334-1.4019527-1.9725146c0.2221127-0.3321483,0.3586388-0.7274663,0.3586388-1.1574256
	c0-1.1513125-0.9353142-2.0866265-2.0866261-2.0866265H9.9116344c-0.6805992,0-1.3489723,0.4360723-1.56497,1.0433133V13.043313
	l2.0866261,5.346982v4.0428391c0,0.2873173,0.2343378,0.521656,0.521657,0.521656H12.2753906z M6.7816939,14.608283
	c0.2873187,0,0.521657-0.2343378,0.521657-0.521656V1.5668668c0-0.2893564-0.2343383-0.5216566-0.521657-0.5216566h-4.69491
	c-0.2628658,0-0.4890531,0.1956213-0.5216566,0.4564496l-1.56497,12.5197601
	c-0.0183394,0.1487541,0.0305659,0.2954693,0.1304142,0.4075441s0.242489,0.1793194,0.3912425,0.1793194H6.7816939z
	 M4.1734109,3.9143217c0-0.4319968,0.3504882-0.782485,0.782485-0.782485s0.782485,0.3504882,0.782485,0.782485
	s-0.3504882,0.7824852-0.782485,0.7824852S4.1734109,4.3463187,4.1734109,3.9143217z"/>
                </svg>'
            );
        }
        else {
            $icons_package = array(
                'verified-owner' => '<svg version="1.1" width="0.85em" height="0.85em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 24 24" style="margin-inline-end: 5px;" xml:space="preserve" fill="#2e7d32">
                                <path d="M8.7919312,17.4049072c-1.6976318-0.0966797-3.0534058,1.2468262-2.9570923,2.9450073
                                    c0.0782471,1.3793335,1.2357788,2.5369263,2.6151123,2.6152344c1.6981812,0.0963745,3.0418091-1.2593994,2.9451904-2.9569702
                                    C11.3169556,18.6350708,10.1650391,17.4831543,8.7919312,17.4049072z M8.6199951,21.2399902
                                    c-0.5599976,0-1.0599976-0.5-1.0599976-1.0599976c0-0.5499878,0.5-1.0499878,1.0599976-1.0499878
                                    c0.5700073,0,1.0499878,0.5,1.0499878,1.0499878C9.6699829,20.7399902,9.1699829,21.2399902,8.6199951,21.2399902z"/>
                                <path d="M23.7600098,4.25l-0.0700073-0.0499878c-0.1199951-0.1000366-0.289978-0.2399902-0.5999756-0.2399902H19.56604
                                    c-0.3292236,0-0.5960693,0.2668457-0.5960693,0.5960693v0.3843994c0,0.3310547,0.2684326,0.5994873,0.5994873,0.5994873h2.3405151
                                    l-1.4400024,6.1199951L7.0100098,13.4199829L5.4199829,5.539978h2.6000366c0.3313599,0,0.5999756-0.2686157,0.5999756-0.5999756
                                    V4.5599976c0-0.3313599-0.2686157-0.5999756-0.5999756-0.5999756h-2.960022L4.6599731,1.7999878
                                    C4.6300049,1.3699951,4.1500244,1.0599976,3.8200073,1.0599976H0.9099731
                                    C0.4299927,1.0300293,0.0499878,1.3900146,0.0499878,1.8699951c0,0.5100098,0.3599854,0.8400269,0.8400269,0.8400269h2.1799927
                                    l2.2799683,11.4499512c0.3400269,1.7000122,1.9000244,3,3.6500244,3h11.0900269
                                    c0.4799805,0,0.8399658-0.3599854,0.8399658-0.8399658c0-0.5-0.3599854-0.8400269-0.8399658-0.8400269H9.0999756
                                    c-0.4400024,0-0.8399658-0.1399536-1.1799927-0.3800049l13.3400269-1.8299561c0.3599854,0,0.7199707-0.3600464,0.75-0.7200317
                                    l1.9400024-7.7000122V4.7999878C23.9500122,4.7000122,23.9299927,4.4199829,23.7600098,4.25z"/>
                                <path d="M18.4359131,17.4051514c-1.7022095-0.0990601-3.0498047,1.2484131-2.9508057,2.9506226
                                    c0.079895,1.3737183,1.2270508,2.5292358,2.600769,2.6091309c1.7000122,0.098877,3.0578613-1.2589111,2.9590454-2.9589844
                                    C20.9650269,18.6322632,19.8095703,17.4851074,18.4359131,17.4051514z M18.2399902,21.2399902
                                    c-0.5499878,0-1.0599976-0.5-1.0599976-1.0599976c0-0.5499878,0.5100098-1.0499878,1.0599976-1.0499878
                                    c0.5800171,0,1.0599976,0.5,1.0599976,1.0499878C19.2999878,20.7399902,18.789978,21.2399902,18.2399902,21.2399902z"/>
                            <path d="M12.6972656,8.1918945c0.1401367,0.140625,0.3320312,0.2207031,0.5263672,0.2207031
                                c0.1894531,0,0.3662109-0.0761719,0.5078125-0.2177734l5.5537109-5.4042969
                                c0.1376953-0.137207,0.2197266-0.3466797,0.2197266-0.5600586c0-0.1865234-0.0742188-0.359375-0.2060547-0.4829102
                                c-0.1044922-0.1381836-0.2724609-0.2246094-0.453125-0.230957c-0.203125-0.0244141-0.4169922,0.0556641-0.5615234,0.2006836
                                l-5.0371094,4.8779297l-2.5947266-2.6708984C10.3686523,3.640625,9.7548828,3.6674805,9.5639648,3.9604492
                                C9.421875,4.1308594,9.3525391,4.3486328,9.3735352,4.5585938C9.3916016,4.737793,9.4746094,4.8945312,9.5791016,4.9746094
                                L12.6972656,8.1918945z"/>
                    </svg>',
                'pros' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                    <path d="M12,0.0720215C5.4240112,0.0720215,0.0720215,5.4240112,0.0720215,12S5.4240112,23.9279785,12,23.9279785
                        S23.9279785,18.5759888,23.9279785,12S18.5759888,0.0720215,12,0.0720215z M12,22.0079956
                        C6.4799805,22.0079956,1.9920044,17.5200195,1.9920044,12S6.4799805,1.9920044,12,1.9920044S22.0079956,6.4799805,22.0079956,12
                        S17.5200195,22.0079956,12,22.0079956z"/>
                    <path d="M16.7011719,11.019043l-3.7211914,0.0009766l0.0004883-3.722168c0-0.5400391-0.4394531-0.9790039-0.9794922-0.9790039
                        c-0.5405273,0-0.9799805,0.4394531-0.9799805,0.9799805l-0.0009766,3.7211914l-3.7211914,0.0009766
                        c-0.5405273,0-0.9799805,0.4394531-0.9799805,0.9799805c0,0.5400391,0.4389648,0.9794922,0.9790039,0.9794922l3.722168-0.0004883
                        l-0.0009766,3.7211914c0,0.5410156,0.4394531,0.9804688,0.9799805,0.9804688c0.2617188,0,0.5078125-0.1025391,0.6923828-0.2880859
                        c0.1855469-0.1845703,0.2875977-0.4306641,0.2875977-0.6923828l0.0004883-3.7216797l3.7216797-0.0004883
                        c0.2617188,0,0.5078125-0.1020508,0.6923828-0.2866211c0.1855469-0.1855469,0.2880859-0.4316406,0.2880859-0.6933594
                        C17.6816406,11.4584961,17.2421875,11.019043,16.7011719,11.019043z"/>
                </svg>',
                'cons' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                    <path d="M12,0.0720215C5.4240112,0.0720215,0.0720215,5.4240112,0.0720215,12S5.4240112,23.9279785,12,23.9279785
                        S23.9279785,18.5759888,23.9279785,12S18.5759888,0.0720215,12,0.0720215z M12,22.0079956
                        C6.4799805,22.0079956,1.9920044,17.5200195,1.9920044,12S6.4799805,1.9920044,12,1.9920044S22.0079956,6.4799805,22.0079956,12
                        S17.5200195,22.0079956,12,22.0079956z"/>
                    <path d="M11.0200195,11.0200195l-3.7211914,0.0009766c-0.5405273,0-0.9799805,0.4394531-0.9799805,0.9799805
                        c0,0.5400391,0.4389648,0.9794922,0.9790039,0.9794922l3.722168-0.0004883l-0.0009766-0.0004883l0,0h1.9599609l0,0h0.0004883
                        l3.7216797-0.0004883c0.2617188,0,0.5078125-0.1020508,0.6923828-0.2866211
                        c0.1855469-0.1855469,0.2880859-0.4316406,0.2880859-0.6933594c0-0.5405273-0.4394531-0.9799805-0.9804688-0.9799805
                        l-3.7211914,0.0009766"/>
                </svg>',
                'like' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                    <path d="M23.1877441,13.322998C23.7011108,12.8613892,24,12.1999512,24,11.4973145
                        c0-1.3493042-1.0969238-2.4472046-2.4462891-2.4472046h-6.7608643c0.3273315-1.090271,0.661377-2.3755493,0.661377-3.0825195
                        c0-2.1203003-1.5742188-4.9608154-3.1849976-4.9608154h-1.2724609c-0.5176392,0-0.9389648,0.4212646-0.9389648,0.9389038v3.7937012
                        L8.2197876,9.4144897C8.0480347,9.1929321,7.77948,9.0501099,7.4782104,9.0501099H0.942688
                        c-0.2671509,0-0.5233154,0.1166992-0.7022095,0.3183594c-0.1817017,0.203064-0.2666016,0.4716187-0.2333984,0.7373047
                        l1.5078125,12.0638428c0.057373,0.4696655,0.4597168,0.8236084,0.9360352,0.8236084h5.0272827l0,0c0,0,1.8731689,0,2.5131836,0
                        h9.5516357c1.3493042,0,2.4472046-1.0978394,2.4472046-2.4472046c0-0.3121948-0.0626221-0.6234131-0.184082-0.916626
                        c0.7325439-0.4384155,1.1889648-1.2288208,1.1889648-2.0999146c0-0.4232178-0.1062622-0.8283691-0.3103027-1.1899414
                        c0.5143433-0.4620972,0.8122559-1.1234741,0.8122559-1.8256836C23.4970703,14.0906372,23.3908081,13.6845093,23.1877441,13.322998z
                         M6.5383301,21.1153564H3.276001L2.0026245,10.9284668h4.5357056V21.1153564z M21.5537109,12.0661621h-0.5019531
                        c-0.5180664,0-0.9393921,0.4213257-0.9393921,0.9389648c0,0.5180664,0.4213257,0.9393921,0.9393921,0.9393921
                        c0.3140869,0,0.5693359,0.255249,0.5693359,0.5693359s-0.255249,0.5693359-0.5693359,0.5693359h-0.5038452
                        c-0.5181274,0-0.9384766,0.4204102-0.9384766,0.9384766c0,0.5181274,0.4203491,0.9393921,0.9384766,0.9393921
                        c0.3140869,0,0.5702515,0.2553101,0.5702515,0.5684204c0,0.3140869-0.2561646,0.5693359-0.5702515,0.5693359h-1.0048828
                        c-0.5181274,0-0.9384766,0.4213257-0.9384766,0.9393921c0,0.5181274,0.4203491,0.9394531,0.9384766,0.9394531
                        c0.3140869,0,0.5693359,0.255249,0.5693359,0.5683594c0,0.3140869-0.255249,0.5693359-0.5693359,0.5693359H9.991394
                        c-0.0365601,0,0,0.0360718,0,0l0,0H8.4171753v-8.6976318c0.2803955-0.057373,0.5280762-0.2429199,0.6604004-0.5047607
                        l2.7632446-5.5264282c0.0640259-0.1252441,0.0953369-0.2623901,0.0953369-0.4189453V2.8812866l0.27948-0.0037842
                        c0.2817993,0.1166992,1.3616333,1.5125732,1.3616333,3.0900879c0,0.6723022-0.6414185,2.7997437-0.9512329,3.7230225
                        c-0.0991821,0.2894287-0.0536499,0.6077881,0.1209717,0.8497314c0.1721802,0.2429199,0.458313,0.3881226,0.763855,0.3881226
                        h8.0428467c0.3140869,0,0.5693359,0.255249,0.5693359,0.5688477S21.8677979,12.0661621,21.5537109,12.0661621z"/>
                    <path d="M4.8318563,20.5988235c0.6566162,0,1.1908569-0.5333252,1.1908569-1.1899414s-0.5342407-1.1908569-1.1908569-1.1908569
                        s-1.1908572,0.5342407-1.1908572,1.1908569S4.17524,20.5988235,4.8318563,20.5988235z"/>
                        
                        <path class="vote-active-svg-path" d="M10.9549475,1.0452101c-0.2873192,0-0.521657,0.234338-0.521657,0.5216566v4.0428391l-2.0866261,5.346981v10.9547892
	c0.2159977,0.6072407,0.8843708,1.043314,1.56497,1.043314h9.9114761c1.1513119,0,2.0866261-0.9353142,2.0866261-2.0866261
	c0-0.4299603-0.1365261-0.8252773-0.3586388-1.1574268c0.8130512-0.2852802,1.4019527-1.0636902,1.4019527-1.9725132
	c0-0.5114689-0.1833954-0.9740314-0.4890537-1.3367462c0.6052036-0.3647518,1.0107098-1.035162,1.0107098-1.7931948
	c0-0.5114679-0.1833935-0.9740305-0.4890518-1.3367453c0.6052017-0.3647518,1.0107098-1.035162,1.0107098-1.7931948
	c0-1.1513119-0.9353142-2.0866261-2.086628-2.0866261H14.280509c0.3178844-1.0066347,0.8476915-2.8079796,0.8476915-3.651597
	c0-1.9643633-1.4386311-4.69491-2.8528099-4.69491H10.9549475z M0.5218142,9.391717
	c-0.1487536,0-0.2913943,0.0672445-0.3912425,0.1793194s-0.1487535,0.25879-0.1304142,0.4075441l1.56497,12.5197601
	c0.0326035,0.260828,0.2587909,0.4564495,0.5216566,0.4564495h4.69491c0.2873187,0,0.521657-0.2322998,0.521657-0.521656V9.913373
	c0-0.2873182-0.2343383-0.521656-0.521657-0.521656H0.5218142z M4.9558959,19.303194
	c0.4319968,0,0.782485,0.3504868,0.782485,0.7824841s-0.3504882,0.782486-0.782485,0.782486s-0.782485-0.3504887-0.782485-0.782486
	S4.5238991,19.303194,4.9558959,19.303194z"/>
                </svg>',
                'dislike' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 24 24" xml:space="preserve" fill="currentColor" width=".85em" height=".85em">
                    <path d="M23.1877575,10.6767855c0.2030602-0.3601007,0.3093357-0.7700176,0.3093357-1.1903715
                        c0-0.7002745-0.2951031-1.3611698-0.8122425-1.8261218c0.20401-0.3605752,0.3102837-0.7700171,0.3102837-1.1898971
                        c0-0.8615842-0.4526157-1.6581697-1.1889477-2.0993996c0.122406-0.2946277,0.1840839-0.6025395,0.1840839-0.9166193
                        c0-1.3497834-1.0978565-2.447639-2.4471645-2.447639H9.9911928l0,0c0,0-2.0405688,0-2.5131116,0H2.4509089
                        c-0.4763384,0-0.8786639,0.3539326-0.9355968,0.82268L0.0070655,13.8944416
                        c-0.0332108,0.2652121,0.051714,0.5337448,0.2324759,0.7368059c0.1793385,0.2021112,0.4355364,0.3183498,0.7031209,0.3183498
                        h6.535419c0.2951021,0,0.5664821-0.1361647,0.7420249-0.3638964l1.8375082,3.6754913v3.7936268
                        c0,0.5180893,0.4213037,0.9384441,0.9389181,0.9384441h1.2724495c0.7553101,0,1.5618591-0.6186714,2.2127914-1.6984978
                        c0.6091814-1.0096092,0.9726028-2.2289219,0.9726028-3.2613049c0-0.5835609-0.2220383-1.6206894-0.6613703-3.0838633h6.7607794
                        C22.9030933,14.9495974,24,13.8517418,24,12.5029068C24,11.8031073,23.7048988,11.142211,23.1877575,10.6767855z
                         M12.748167,13.4574814c-0.1760168,0.2448111-0.2215633,0.5626869-0.12288,0.8497229
                        c0.2979488,0.8872042,0.9522018,3.0572958,0.9522018,3.7262564c0,1.6273308-1.1158838,2.9794865-1.3265352,3.0819664h-0.3150291
                        v-3.0819664c0-0.1565647-0.030838-0.2922554-0.0939388-0.4165592l-2.7650394-5.5305529
                        c-0.1304712-0.2590446-0.3786039-0.4459743-0.6599474-0.5038567v-8.69697l0,0h1.574194l0,0h9.5519133
                        c0.3140793,0,0.5693283,0.255249,0.5693283,0.5688541s-0.255249,0.5683799-0.5693283,0.5683799
                        c-0.5180893,0-0.9384441,0.4213033-0.9384441,0.9393926s0.4203548,0.9393921,0.9384441,0.9393921h1.0048637
                        c0.3140793,0,0.5702782,0.255249,0.5702782,0.5688543S20.8620491,7.038775,20.5479698,7.038775
                        c-0.5180893,0-0.9384422,0.4217777-0.9384422,0.9398665c0,0.5176144,0.4203529,0.9389181,0.9384422,0.9389181h0.5038567
                        c0.3140793,0,0.5693283,0.255249,0.5693283,0.5688543c0,0.3140793-0.255249,0.5693283-0.5693283,0.5693283
                        c-0.5180893,0-0.9393921,0.4213037-0.9393921,0.9389181c0,0.5180893,0.4213028,0.9393921,0.9393921,0.9393921h0.5019588
                        c0.3140793,0,0.5693283,0.255249,0.5693283,0.5688543s-0.255249,0.5688543-0.5693283,0.5688543h-8.0427179
                        C13.2055283,13.0717611,12.9203892,13.215991,12.748167,13.4574814z M6.5382147,2.8855221v10.1862392H2.0025625
                        L3.2759612,2.8855221H6.5382147z"/>
                    <path d="M4.8346267,5.7799554c0.6566257,0,1.190846-0.5342197,1.190846-1.1908455
                        c0-0.6561515-0.5342202-1.1903715-1.190846-1.1903715s-1.1908457,0.53422-1.1908457,1.1903715
                        C3.6437809,5.2457356,4.1780009,5.7799554,4.8346267,5.7799554z"/>
                        
                        <path class="vote-active-svg-path" d="M12.2753906,22.9547901c1.4141788,0,2.8528099-2.730547,2.8528099-4.69491
	c0-0.8436165-0.5298071-2.6449623-0.8476915-3.651597h7.6292276c1.1513138,0,2.086628-0.9353142,2.086628-2.0866261
	c0-0.7580328-0.405508-1.428443-1.0107098-1.7931948c0.3056583-0.3627148,0.4890518-0.8252773,0.4890518-1.3367453
	c0-0.7580328-0.4055061-1.428443-1.0107098-1.7931952c0.3056583-0.3627143,0.4890537-0.8252769,0.4890537-1.3367448
	c0-0.908824-0.5889015-1.6872334-1.4019527-1.9725146c0.2221127-0.3321483,0.3586388-0.7274663,0.3586388-1.1574256
	c0-1.1513125-0.9353142-2.0866265-2.0866261-2.0866265H9.9116344c-0.6805992,0-1.3489723,0.4360723-1.56497,1.0433133V13.043313
	l2.0866261,5.346982v4.0428391c0,0.2873173,0.2343378,0.521656,0.521657,0.521656H12.2753906z M6.7816939,14.608283
	c0.2873187,0,0.521657-0.2343378,0.521657-0.521656V1.5668668c0-0.2893564-0.2343383-0.5216566-0.521657-0.5216566h-4.69491
	c-0.2628658,0-0.4890531,0.1956213-0.5216566,0.4564496l-1.56497,12.5197601
	c-0.0183394,0.1487541,0.0305659,0.2954693,0.1304142,0.4075441s0.242489,0.1793194,0.3912425,0.1793194H6.7816939z
	 M4.1734109,3.9143217c0-0.4319968,0.3504882-0.782485,0.782485-0.782485s0.782485,0.3504882,0.782485,0.782485
	s-0.3504882,0.7824852-0.782485,0.7824852S4.1734109,4.3463187,4.1734109,3.9143217z"/>
                </svg>'
            );
        }
        if ( $echo )
            echo array_key_exists($icon_key, $icons_package) ? $icons_package[$icon_key] : '';
        else
            return array_key_exists($icon_key, $icons_package) ? $icons_package[$icon_key] : '';
    }

    /**
     * Setter filter for adding class for circle avatar
     */
    public function set_avatar_circle() {
        add_filter('get_avatar', array($this, 'avatar_circle_classes'), 10);
    }

    /**
     * Resetter filter for adding class for circle avatar
     */
    public function reset_avatar_circle() {
        remove_filter('get_avatar', array($this, 'avatar_circle_classes'), 10);
    }

    /**
     * Adds class for know the avatar should be styled in circle style
     * @param $avatar
     * @return string|string[]
     */
    public function avatar_circle_classes($avatar) {
        return str_replace( "class='","class='avatar-circle ",$avatar );
    }

    /**
     * Modify default ratings with new additions of ratings by criteria
     * @param $html
     * @return string
     */
    public function add_criteria_ratings($html) {
        $stars_options = '<option value="">' . esc_html__( 'Rate&hellip;', 'xstore' ) . '</option>
                    <option value="5">' . esc_html__( 'Perfect', 'xstore' ) . '</option>
                    <option value="4">' . esc_html__( 'Good', 'xstore' ) . '</option>
                    <option value="3">' . esc_html__( 'Average', 'xstore' ) . '</option>
                    <option value="2">' . esc_html__( 'Not that bad', 'xstore' ) . '</option>
                    <option value="1">' . esc_html__( 'Very poor', 'xstore' ) . '</option>';
        // replace html so text in label should be another
        $html = '<label for="rating">' . esc_html__( 'Your total rating', 'xstore' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>'.
            $stars_options.
        '</select>';
        foreach ($this->settings['criteria_ready'] as $criteria) {
            $html .= '<label for="'.$criteria['slug'] .'">' . $criteria['name'] . ( $this->settings['rating_criteria_required'] ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="'.$criteria['slug'].'" id="'.$criteria['slug'].'"'.($this->settings['rating_criteria_required'] ? ' required': '').'>'.
                $stars_options.
            '</select>';
        }
        $html .= '<input type="hidden" name="et_reviews_criteria_ids" value="' . implode( ',', array_column($this->settings['criteria_ready'], 'slug') ) . '">';
        return '<div class="et-reviews-criteria">'.$html.'</div>';
    }

    /**
     * Saving criteria ratings
     * @param $comment_id
     */
    public function save_comment_rating_criteria( $comment_id ) {
        $summary_criteria_list = $this->get_rating_criteria_slugs();

        if ( 0 === count( $summary_criteria_list ) ) {
            return;
        }

        foreach ( $summary_criteria_list as $criteria_key ) {
            if ( isset( $_POST[ $criteria_key ], $_POST['comment_post_ID'] ) && in_array( $_POST[ $criteria_key ], array( '1', '2', '3', '4', '5' ), true ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) {
                add_comment_meta( $comment_id, $criteria_key, $_POST[ $criteria_key ], true );
            }
        }
    }

    /**
     * Getter of rating criteria slugs for saving it as comment_meta for comment data.
     * @return array|false|string[]
     */
    public function get_rating_criteria_slugs() {
        return isset( $_REQUEST['et_reviews_criteria_ids'] ) ? explode( ',', $_REQUEST['et_reviews_criteria_ids'] ) : array();
    }

    /**
     * Showing ratings by criteria or only total if no criteria ratings are left
     * @param $comment
     */
    public function render_criteria_star_rating($comment) {
        $comment_id            = $comment->comment_ID;
        $reviews_enabled = function_exists('wc_review_ratings_enabled') && wc_review_ratings_enabled();
        $ratings = '';

        foreach ($this->settings['criteria_ready'] as $criteria) {
            $rating                = intval( get_comment_meta( $comment_id, $criteria['slug'], true ) );
            if ( $rating && $reviews_enabled ) {
                $ratings .= '<div class="et-review-criteria-rating">' . wc_get_rating_html( $rating ) . '<span class="et-review-criteria-label">'.$criteria['name'].'</span>' . '</div>'; // WPCS: XSS ok.
            }
        }

        if ( $ratings ) {
            echo '<div class="et-review-criteria-rating">';
        }
        woocommerce_review_display_rating();
        if ( $ratings ) {
            echo '<span class="et-review-criteria-label">' . esc_html__('Total rating', 'xstore') . '</span>';
            echo '</div>';
            echo '<div class="et-review-criteria-ratings">' . $ratings . '</div>';
        }
    }

    /**
     * Check if user is verified (bought this product before)
     * It is not used yet but could be used in the near future after some testing time
     * @param $comment_id
     * @return bool
     */
    protected function is_user_purchased_product( $comment_id ) {
        $comment = get_comment( $comment_id );

        if ( empty( $comment ) || ! class_exists('WooCommerce') || ! is_singular( 'product' ) || ! function_exists( 'wc_customer_bought_product' ) || ! function_exists( 'wc_review_is_from_verified_owner' ) ) {
            return false;
        }

        global $product;

        if ( $product && $product->get_ID() && wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $product->get_ID() ) && wc_review_is_from_verified_owner( $comment_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check for parents comment
     * It is not used yet but could be used in the near future after some testing time
     * @param $comment_id
     * @return bool
     */
    protected function is_parent_comment( $comment_id ) {
        global $wpdb;

        $comment_info = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT $wpdb->comments.comment_parent
					FROM $wpdb->comments
					WHERE $wpdb->comments.comment_ID = %d;",
                $comment_id
            ),
            ARRAY_A
        );

        return '0' === $comment_info[0]['comment_parent'];
    }

}

new Etheme_WooCommerce_Product_Reviews_Advanced();