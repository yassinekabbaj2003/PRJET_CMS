<?php

namespace XStoreCore\Modules\WooCommerce;

if (!class_exists('\WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Class XStore_Wishlist_WP_List_Table
 */
class XStore_Wishlist_WP_List_Table extends \WP_List_Table {

    public static $key = 'et-wishlists';
    public static $global_key = 'xstore-wishlist';

    /**
     * Site ID to generate the Users list table for.
     *
     * @since 3.1.0
     * @var int
     */
    public $site_id;

    /**
     * Whether or not the current Users list table is for Multisite.
     *
     * @since 3.1.0
     * @var bool
     */
    public $is_site_users;

    /**
     * XStore_Wishlist_WP_List_Table constructor.
     */
    public function __construct()
    {
        parent::__construct( array(
            'singular'  => 'et_wishlist',     //singular name of the listed records
            'plural'    => 'et_wishlists',    //plural name of the listed records
            'ajax'      => false
        ) );
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'count_added':
            case 'product_id':
            case 'created':
                return $item[$column_name];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Get a list of columns for the list table.
     *
     * @since 3.1.0
     *
     * @return string[] Array of column titles keyed by their column name.
     */
    public function get_columns() {
        $columns = array(
//            'cb' => '<input type="checkbox" />',
            'product'     => __( 'Product', 'xstore-core' ),
            'count_added' => __( 'Times added', 'xstore-core' ),
            'product_stock_status'     => __( 'Stock status', 'xstore-core' ),
            'users_list' => __( 'Users list', 'xstore-core' ),
            'created'    => __( 'Date added', 'xstore-core' ),
        );

        return $columns;
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'count_added' => array('count_added', true),
            'product'     => array('product_id',true),
//            'created' => array('created',true), // as there are array
        );
        return $sortable_columns;
    }

    public function get_hidden_columns()
    {
        // Setup Hidden columns and return them
        return array();
    }

    /**
     * Handles the checkbox column output.
     *
     * @since 4.3.0
     * @since 5.9.0 Renamed `$post` to `$item` to match parent class for PHP 8 named parameter support.
     *
     * @param WP_Post $item The current WP_Post object.
     */
    public function column_cb( $item ) {
        // Restores the more descriptive, specific name for use within this method.
        $post = $item;

            ?>
            <label class="screen-reader-text" for="cb-select-<?php echo $post['id']; ?>">
                <?php
                /* translators: %s: Post title. */
                printf( __( 'Select %s' ), $post['count_added'] );
                ?>
            </label>
            <input id="cb-select-<?php echo $post['id']; ?>" type="checkbox" name="user_details[]" value="<?php echo implode('|',
                array($post['count_added'], $post['product_id'], ($post['product']->is_on_backorder() || $post['product']->is_in_stock() ? 1 : 0) )
            ); ?>" />
            <div class="locked-indicator">
                <span class="locked-indicator-icon" aria-hidden="true"></span>
                <span class="screen-reader-text">
				<?php
                printf(
                /* translators: Hidden accessibility text. %s: Post title. */
                    __( '&#8220;%s&#8221; is locked' ),
                    $post['count_added']
                );
                ?>
				</span>
            </div>
        <?php
    }

    /**
     * Handles the checkbox column output.
     *
     * @since 4.3.0
     * @since 5.9.0 Renamed `$post` to `$item` to match parent class for PHP 8 named parameter support.
     *
     * @param WP_Post $item The current WP_Post object.
     */
    public function column_created( $item ) {
        // Restores the more descriptive, specific name for use within this method.
        $post = $item;

        $all_dates = array();
        foreach ($post['created'] as $created_date) {
            $all_dates[] = sprintf(
            /* translators: 1: Post date, 2: Post time. */
                __( '%1$s at %2$s' ),
                /* translators: Post date format. See https://www.php.net/manual/datetime.format.php */
                date(__( 'Y/m/d' ), $created_date),
                /* translators: Post time format. See https://www.php.net/manual/datetime.format.php */
                date(__( 'g:i a' ), $created_date),
            );
        }

        echo implode('<br/>', $all_dates);

//        echo date(get_option('date_format'), $post['created']);
    }

    public function column_users_list($item) {
        $users_emails_ready = array();
        $instance = XStore_Wishlist::get_instance();
        $wishlist_page_id = absint( get_theme_mod('xstore_wishlist_page', '') );
        if ( !$wishlist_page_id ) {
            $wishlist_page_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $wishlist_page_ghost_id )
                $wishlist_page = add_query_arg('et-wishlist-page', '', get_permalink($wishlist_page_ghost_id));
            else
                $wishlist_page = home_url();
        }
        else {
            $wishlist_page = get_permalink($wishlist_page_id);
        }
        foreach ($item['users'] as $user_info) {
            $user_key = $instance->get_user_key($user_info['id']);
            $user_wishlist_url = add_query_arg( 'wid', $user_key, $wishlist_page );
            $users_emails_ready[] = '<a class="mtips mtips-top" href="'.$user_wishlist_url.'" target="_blank">'.$user_info['email'].
                '<span class="mt-mes">'.sprintf(esc_html__('View the wishlist of %s', 'xstore-core'), '<strong>'.$user_info['email'].'</strong>').'</span>'.'</a>';
        }
        echo implode('<br/>', $users_emails_ready);
    }

    public function column_product_stock_status( $item ) {
        // Restores the more descriptive, specific name for use within this method.
        $post = $item;

        if ( $post['product']->is_on_backorder() ) {
            $stock_html = '<span class="onbackorder">' . __( 'On backorder', 'xstore-core' ) . '</span>';
        } elseif ( $post['product']->is_in_stock() ) {
            $stock_html = '<span class="instock">' . __( 'In stock', 'xstore-core' ) . '</span>';
        } else {
            $stock_html = '<span class="outofstock">' . __( 'Out of stock', 'xstore-core' ) . '</span>';
        }

        if ( $post['product']->managing_stock() ) {
            $stock_html .= ' (' . wc_stock_amount( $post['product']->get_stock_quantity() ) . ')';
        }

        echo wp_kses_post( apply_filters( 'woocommerce_admin_stock_html', $stock_html, $post['product'] ) );
    }


    public function column_product( $item ) {
        // Restores the more descriptive, specific name for use within this method.
        $post = $item;

        echo '<a href="'.$post['product']->get_permalink().'" target="_blank">'.$post['product']->get_name().'</a>';
    }

    /**
     * Output 'no wishlist' message.
     *
     * @since 3.1.0
     */
    public function no_items() {
        echo __( 'No wishlist found.', 'xstore-core' );
    }

    function get_bulk_actions()
    {
        return array();
//        $actions = array(
//            'notify'    => esc_html__('Notify', 'xstore-core'),
//            'delete'    => esc_html__('Delete', 'xstore-core')
//        );
//
//        return $actions;
    }

    /**
     * Generates and displays row action links.
     *
     * @since 4.3.0
     * @since 5.9.0 Renamed `$post` to `$item` to match parent class for PHP 8 named parameter support.
     *
     * @param WP_Post $item        Post being acted upon.
     * @param string  $column_name Current column name.
     * @param string  $primary     Primary column name.
     * @return string Row actions output for posts, or an empty string
     *                if the current column is not the primary column.
     */
    protected function handle_row_actions( $item, $column_name, $primary ) {
        return '';
        if ( $primary !== $column_name ) {
            return '';
        }

        // Restores the more descriptive, specific name for use within this method.
        $post             = $item;
//        $can_edit_post    = current_user_can( 'edit_post', $post->ID );
        $actions          = array();
        $title            = $post['count_added'];

//        $actions['delete'] = sprintf(
//            '<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
//            $this->get_action_user_wishlist( 'delete', $post['email'], $post['product_id'] ),
//            /* translators: %s: Post title. */
//            esc_attr( sprintf( __( 'Delete &#8220;%s&#8221; wishlist request' ), $title ) ),
//            _x( 'Delete', 'verb' )
//        );
        $actions['delete'] = sprintf(
            '<a href="%s" aria-label="%s" data-email="%s" data-id="%s" data-texts="%s" class="et-wishlist-delete">%s</a>',
            $this->get_action_user_wishlist('delete', $post['count_added'], $post['product_id']),
            /* translators: %s: Post title. */
            esc_attr(sprintf(__('Delete &#8220;%s&#8221; wishlist request'), $title)),
            $post['count_added'],
            $post['product_id'],
            esc_attr(wp_json_encode(
                array(
                    'default' => esc_html__('Delete', 'xstore-core'),
                    'success' => esc_html__('Deleted', 'xstore-core'),
                    'process' => esc_html__('Deleting', 'xstore-core'),
                    'error' => esc_html__('Error', 'xstore-core'),
                )
            )),
            __('Delete', 'xstore-core')
        );

        if ( $post['product']->is_on_backorder() || $post['product']->is_in_stock() ) {
            $actions['notify'] = sprintf(
                '<a href="%s" aria-label="%s" data-email="%s" data-id="%s" data-texts="%s" class="et-wishlist-notify">%s</a>',
                $this->get_action_user_wishlist('notify', $post['count_added'], $post['product_id']),
                /* translators: %s: Post title. */
                esc_attr(sprintf(__('Notify &#8220;%s&#8221;'), $title)),
                $post['count_added'],
                $post['product_id'],
                esc_attr(wp_json_encode(
                    array(
                        'default' => esc_html__('Notify', 'xstore-core'),
                        'success' => esc_html__('Notified', 'xstore-core'),
                        'process' => esc_html__('Sending email...', 'xstore-core'),
                        'error' => esc_html__('Error', 'xstore-core'),
                        'notify_yes' => esc_html__('Yes', 'xstore-core')
                    )
                )),
                __('Notify', 'xstore-core')
            );
        }

        return $this->row_actions( $actions );
    }

    public function get_action_user_wishlist($action, $email, $product_id) {
        return wp_nonce_url(
                add_query_arg( array('wishlist-action' => "{$action}-wishlist", 'email' => $email, 'product_id' => $product_id), admin_url( 'admin.php?page=et-wishlists' ) ),
            "{$action}-wishlist_{$email}_{$product_id}");
    }

    private function table_data()
    {
        global $wpdb;

        $table = str_replace('-', '_', self::$global_key);

        $data=array();

        $cookie_key = XStore_Wishlist::get_instance()->get_cookie_key();

        if(isset($_GET['s']))
        {

            $search=$_GET['s'];

            $search = trim($search);

            // search by emails and products names
//            $data_store                   = \WC_Data_Store::load( 'product' );
//            $ids                          = $data_store->search_products( wc_clean( wp_unslash( $search ) ), '', true, true );
            $wk_users = $wpdb->get_results( $wpdb->prepare( "SELECT user_email, ID FROM " . $wpdb->prefix . 'users' . " WHERE user_email LIKE '%$search%'") );
        }

        else{
            $wk_users = get_users(array(
                'fields' => ['ID', 'user_email'], // improved
                'meta_key'     => $cookie_key,
                'meta_compare' => 'EXISTS' // doesn't do anything, just a reminder
            ));
        }

        $field_name_one = array();

        $field_name_two = array();

        $field_name_three = array();

        $sitepress_exists = class_exists('SitePress');
        $polylang_exists = function_exists( 'pll_current_language' );
        if ( class_exists('SitePress') ) {
            global $sitepress;
            $sitepress_lang = $sitepress->get_current_language();
        }
        if ( $polylang_exists ) {
            $polylang_lang = pll_current_language();
        }

        $i=0;

        $products_package = array();

        foreach ($wk_users as $wk_user) {

//            $field_name_one[]=$wk_user->email;
            $saved_products = get_user_meta($wk_user->ID, $cookie_key, true);
            if (!$saved_products) {
                $saved_products = [];
            } else {
                $saved_products_local = explode('|', $saved_products);
                $saved_products = [];
                foreach ($saved_products_local as $local_product_info) {
                    $product_info = (array)json_decode($local_product_info);
                    $saved_products[$product_info['id']] = $product_info;
                    if (!array_key_exists($product_info['id'], $products_package))
                        $products_package[$product_info['id']] = array();

                    $products_package[$product_info['id']][] = array(
                        'user_id' => $wk_user->ID,
                        'user_email' => $wk_user->user_email,
                        'details' => $product_info
                    );
                }

            }
        }

        foreach ($products_package as $product_id => $product_info ) {
            $field_name_one[]=$product_id;
            $origin_product_id = $product_id;
            if ( $sitepress_exists ) {
                $product_id = apply_filters('wpml_object_id', $product_id, get_post_type($product_id), false, $sitepress_lang);
                // if product does not have ready translations then use original id of product
                if ( !$product_id )
                    $product_id = $origin_product_id;
            }
            elseif ( $polylang_exists ) {
                $product_id = PLL()->model->post->get_translation( $product_id, $polylang_lang );
                // if product does not have ready translations then use original id of product
                if ( !$product_id )
                    $product_id = $origin_product_id;
            }

            add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
            $post_object = get_post($product_id);
            setup_postdata($GLOBALS['post'] =& $post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
            // if product was permanently removed then prevent it from count and showing
            $product = class_exists('WooCommerce') ? wc_get_product($product_id) : false;
            if (!$product || $product->get_status() == 'trash') {
                continue;
            }

            $field_name_two[]= $product_id;

            $added_on = array();
            $user_emails = array();
            foreach ($product_info as $product_info_item) {
                if ( isset($product_info_item['details']['time']) ) {
                    $added_on[] = $product_info_item['details']['time'];
                }
                $user_emails[] = array(
                    'id' => $product_info_item['user_id'],
                    'email' => $product_info_item['user_email']
                );
            }
            $field_name_three[] = $added_on;

            $data[] = array(

//                'cb' => '<input type="checkbox"/>',

                'id' => $product_id,

                'count_added'  => count($product_info),

                'product'  => $product,

                'users' => $user_emails,

                'product_id'  => $field_name_two[$i],

                'created' =>  $field_name_three[$i],

            );

            $i++;

        }

        return $data;

    }

    public function prepare_items($search_value = '')
    {

        global $wpdb;

        $_SERVER['REQUEST_URI'] = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'error', 'message', 'paged' ), $_SERVER['REQUEST_URI'] );

        $columns = $this->get_columns();

        $sortable = $this->get_sortable_columns();

        $hidden=$this->get_hidden_columns();

        $data = $this->table_data();

        $totalitems = count($data);

//        $user = get_current_user_id();
//
//        $screen = get_current_screen();

        $perpage = $this->get_items_per_page('edit_et_wishlists_per_page');

        $this->_column_headers = array($columns,$hidden,$sortable);

        usort($data, function ($a,$b){

            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'count_added'; //If no sort, default to email

            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc

            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order

            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort

        });

        $totalpages = ceil($totalitems/$perpage);

        $currentPage = $this->get_pagenum();

        $data = array_slice($data,(($currentPage-1)*$perpage),$perpage);

        $this->set_pagination_args( array(

            "total_items" => $totalitems,

            "total_pages" => $totalpages,

            "per_page" => $perpage,
        ) );

        $this->items =$data;
    }

}