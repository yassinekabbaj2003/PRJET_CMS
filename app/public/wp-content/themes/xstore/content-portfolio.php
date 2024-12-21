<?php
/**
 * Template Name: Content-portfolio
 * @xstore-version 9.4.0
 */

if ( etheme_xstore_plugin_notice() ) {
	return;
}
global $et_portfolio_loop;

if( empty($et_portfolio_loop['loop']))
	$et_portfolio_loop['loop'] = 0;

if( empty($et_portfolio_loop['one_project']))
	$et_portfolio_loop['one_project'] = false;

$et_portfolio_loop['loop']++;

$postId = get_the_ID();

$portfolio_style = get_query_var('et_project-style', false);
if ( !$portfolio_style ) {
    $portfolio_style = etheme_get_option('portfolio_style', 'default');
    set_query_var('et_project-style', $portfolio_style);
}
$port_first_wide = get_query_var('et_project-first-wide', 'unset');
if ( $port_first_wide == 'unset' ) {
    $port_first_wide = etheme_get_option('port_first_wide', 0);
    set_query_var('et_project-first-wide', $port_first_wide);
}
$portfolio_fullwidth = get_query_var('et_project-fullwidth', 'unset');
if ( $portfolio_fullwidth == 'unset' ) {
    $portfolio_fullwidth = etheme_get_option('portfolio_fullwidth', 0);
    set_query_var('et_project-fullwidth', $portfolio_fullwidth);
}
$size = get_query_var('et_project-img-size', false);
if ( !$size ) {
    $size = etheme_get_option( 'portfolio_images_size', 'large' );
    set_query_var('et_project-img-size', $size);
}

$classes = array('portfolio-item');

$columns = ( isset( $et_portfolio_loop['columns'] ) && $et_portfolio_loop['columns'] ) ? $et_portfolio_loop['columns'] : etheme_get_option('portfolio_columns', 3);

if( $et_portfolio_loop['loop'] == 1 && $port_first_wide ) {
	$columns /= 2;
}

$classes[] = etheme_get_product_class($columns);

$classes[] = 'columns-count-'.$columns;
$classes[] = 'port-style-'.$portfolio_style;
$classes[] = ($portfolio_fullwidth) ? 'item-full-width' : 'no-full-width';


if( ($et_portfolio_loop['loop'] == 2 && $port_first_wide) || $et_portfolio_loop['one_project'] ) {
	$classes[] = 'grid-sizer';
} else if($et_portfolio_loop['loop'] == 1 && ! $port_first_wide) {
	$classes[] = 'grid-sizer';
}
?>
<div <?php post_class( $classes ); ?>>
	<div class="portfolio-item-inner">
		<?php if (has_post_thumbnail( $postId ) ): ?>
			<div class="portfolio-image">
				<a href="<?php the_permalink(); ?>">
        			<?php echo etheme_get_image( get_post_thumbnail_id(), $size ); ?>
				</a>
				<div class="zoom">
					<a href="<?php $portfolio_link = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'full'); echo esc_url($portfolio_link[0]); ?>" class="btn-lightbox" rel="lightbox"><span><?php esc_html_e('View large', 'xstore'); ?></span></a>
					<a href="<?php the_permalink(); ?>" class="btn btn-view-more"><span><?php esc_html_e('More details', 'xstore'); ?></span></a>
				</div>
			</div>
		<?php endif; ?>
		<div class="portfolio-descr">
			<span class="posted-in"><?php etheme_project_categories($postId); ?></span>
			<p class="project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
		</div>
	</div>
</div>