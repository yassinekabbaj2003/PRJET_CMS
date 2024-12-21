<?php
/**
 * Template Name: Portfolio page
 * @xstore-version 9.4.0
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

$full_width = get_query_var('et_project-fullwidth', 'unset');
if ( $full_width == 'unset' ) {
    $full_width = etheme_get_option('portfolio_fullwidth', 0);
    set_query_var('et_project-fullwidth', $full_width);
}

$class = ( $full_width ) ? 'port-full-width' : 'container';

?>

<?php do_action( 'etheme_page_heading' ); ?>

	<div class="<?php echo esc_attr($class); ?>">
		<div class="page-content sidebar-position-without">
			<div class="content">
				<?php if ( ! etheme_xstore_plugin_notice() ):
					if( have_posts() && get_query_var( 'portfolio_category' ) == '' ): while( have_posts() ) : the_post();
	                    the_content();
	                endwhile; endif;
	                
					 if ( get_query_var( 'portfolio_category' ) && $term_desc = term_description()):
						 echo '<div class="portfolio-category-description">' . $term_desc . '</div>';
					 endif;

					 if ( get_query_var( 'et_portfolio-projects', false ) ) {
                        etheme_portfolio();
                     }
					?>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
get_footer();
?>