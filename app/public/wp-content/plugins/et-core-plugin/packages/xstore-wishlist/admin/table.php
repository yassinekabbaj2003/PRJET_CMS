<?php

namespace XStoreCore\Modules\WooCommerce;
/**
 * Edit Comments Administration Screen.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once ABSPATH . 'wp-admin/admin.php';
if ( ! current_user_can( 'edit_posts' ) ) {
    wp_die(
        '<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
        '<p>' . __( 'Sorry, you are not allowed to see wishlists.' ) . '</p>',
        403
    );
}

$wp_list_table = new XStore_Wishlist_WP_List_Table();
$pagenum       = $wp_list_table->get_pagenum();

$doaction = $wp_list_table->current_action();
$message = '';

if ( $doaction ) {
    if (isset($_GET['user_details'])) {
        $user_actioned = array();
        if (is_array($_GET['user_details'])) {
            foreach ($_GET['user_details'] as $id) {

                list($user_id, $product_id, $product_status) = explode('|', $id);

                if ( $doaction == 'notify' ) {
                    if ( $product_status > 0 )
                        $user_actioned[] = $user_id;
                }
                else
                    $user_actioned[] = $user_id;

            }
        } else {
            if (!empty($_GET['user_details'])) {
                list($user_id, $product_id, $product_status) = explode('|', $_GET['user_details']);
                if ( $doaction == 'notify' ) {
                    if ( $product_status > 0 )
                        $user_actioned[] = $user_id;
                }
                else
                    $user_actioned[] = $user_id;
            }
        }
        switch ($doaction) {
            case 'notify':
                $message = _n( 'The user below was notified:', 'The users below were notified:', count( $user_actioned ), 'xstore-core' ) . '<br/>' . implode( "<br />\n", $user_actioned);
            break;
            case 'delete':
                $message = _n( 'The user below was deleted from wailist:', 'The users below were deleted from wailists:', count( $user_actioned ), 'xstore-core' ) . '<br/>' . implode( "<br />\n", $user_actioned);
                break;
        }
    }
}

if ( ! empty( $_GET['_wp_http_referer'] ) ) {
    wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
    exit;
}

$wp_list_table->prepare_items();

require_once ABSPATH . 'wp-admin/admin-header.php';
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php
        echo esc_html__( 'Wishlists', 'xstore-core');
        ?>
    </h1>

    <?php

    if ( $message ) {
        echo '<div id="moderated" class="updated notice is-dismissible"><p>' . $message . '</p></div>';
    }

    if ( isset( $_REQUEST['s'] ) && strlen( $_REQUEST['s'] ) ) {
        echo '<span class="subtitle">';
        printf(
        /* translators: %s: Search query. */
            __( 'Search results for: %s' ),
            '<strong>' . esc_html( wp_unslash( $_REQUEST['s'] ) ) . '</strong>'
        );
        echo '</span>';
    }
    ?>

    <hr class="wp-header-end">

    <?php
    if ( isset( $_REQUEST['error'] ) ) {
        $error     = (int) $_REQUEST['error'];
        $error_msg = '';
        switch ( $error ) {
            case 1:
                $error_msg = __( 'Invalid ID.' );
                break;
            case 2:
                $error_msg = __( 'Sorry, you are not allowed to edit on this wishlist.' );
                break;
        }
        if ( $error_msg ) {
            echo '<div id="moderated" class="error"><p>' . $error_msg . '</p></div>';
        }
    }

    ?>

    <?php $wp_list_table->views(); ?>

    <form id="wishlist-filter" method="get">

        <input type="hidden" name="page" value="et-wishlists">

        <?php $wp_list_table->search_box( __( 'Search' ), 'email' ); ?>
        
        <?php if ( isset( $_REQUEST['paged'] ) ) { ?>
            <input type="hidden" name="paged" value="<?php echo esc_attr( absint( $_REQUEST['paged'] ) ); ?>" />
        <?php } ?>

        <?php $wp_list_table->display(); ?>
    </form>
</div>

<div id="ajax-response"></div>

<?php

require_once ABSPATH . 'wp-admin/admin-footer.php';