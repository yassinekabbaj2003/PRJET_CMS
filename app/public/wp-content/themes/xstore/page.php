<?php
/**
 * Template Name: Page
 * @xstore-version 9.4.0
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

global $post;

if ( get_query_var('et_is_portfolio-archive', false) ) {
    get_template_part('portfolio');
    return;
}

?>

<?php do_action( 'etheme_page_heading' ); ?>

<div class="container content-page sidebar-mobile-<?php echo esc_attr( get_query_var('et_sidebar-mobile', 'bottom') ); ?>">
    <div class="sidebar-position-<?php echo esc_attr( get_query_var('et_sidebar', 'left') ); ?>">
        <div class="row">

            <div class="content <?php echo esc_attr( get_query_var('et_content-class', 'col-md-9') ); ?>">
                <?php if(have_posts()): while(have_posts()) : the_post(); ?>

                    <?php
                    $mobile_content = etheme_get_custom_field('mobile_content', $post->ID);
                    if ( get_query_var('is_mobile', false) && !empty( $mobile_content ) && $mobile_content != 'inherit')
                        etheme_static_block($mobile_content, true);
                    else
                        the_content();
                    ?>

                    <div class="post-navigation"><?php wp_link_pages(); ?></div>

                    <?php if ($post->ID != 0 && current_user_can('edit_post', $post->ID)): ?>
                        <?php edit_post_link( esc_html__('Edit this', 'xstore'), '<p class="edit-link">', '</p>' ); ?>
                    <?php endif ?>

                <?php endwhile; else: ?>

                    <h3><?php esc_html_e('No pages were found!', 'xstore') ?></h3>

                <?php endif; ?>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() )
                    comments_template('', true);
                ?>

            </div>

            <?php get_sidebar(); ?>

        </div><!-- end row-fluid -->

    </div>
</div><!-- end container -->

<?php
get_footer();
