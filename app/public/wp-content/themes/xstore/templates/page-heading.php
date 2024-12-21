<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme page heading
 *
 * Override this template by copying it to yourtheme/templates/page-heading.php
 * @author 	   8theme
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 * @since   6.4.5
 * @xstore-version 9.4.0
 */
//$l = etheme_page_config();

if ( get_query_var('et_page-banner', false) ) {
	echo '<div class="container">';
		etheme_static_block(get_query_var('et_page-banner', false), true);
	echo '</div>';
}

if (get_query_var('et_breadcrumbs', false)): ?>

    <?php
	$styles = '';
	if( is_category() || is_tag() ) {
		$term_id = get_queried_object()->term_id;
		if( $term_id && $image = get_term_meta( $term_id, '_et_page_heading', true ) ) {
			$styles = 'style="background-image:url('. $image .'); margin-bottom: 25px;"';
		}
	}

    ?>

	<div class="page-heading bc-type-<?php echo esc_attr( get_query_var('et_breadcrumbs-type', false) ); ?> bc-effect-<?php echo esc_attr( get_query_var('et_breadcrumbs-effect', false) ); ?> bc-color-<?php echo esc_attr( get_query_var('et_breadcrumbs-color', false) ); ?>" <?php echo $styles; ?>>
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
					<?php etheme_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>

<?php endif;

if(get_query_var('et_page-slider', false)): ?>
	<div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider alias="'.get_query_var('et_page-slider', false).'"][/rev_slider]'); ?>
	</div>
<?php endif;