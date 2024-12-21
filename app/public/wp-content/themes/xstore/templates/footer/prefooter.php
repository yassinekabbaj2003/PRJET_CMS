<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme prefooter
 *
 * Override this template by copying it to yourtheme/templates/footer/prefooter.php
 * @author 	   8theme
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 * @since   6.2.12
 * @xstore-version 9.4.0
 */

$custom_prefooter = etheme_get_query_custom_field('custom_prefooter');
?>

<?php if( $custom_prefooter != 'without' || ( $custom_prefooter == '' && is_active_sidebar('prefooter') ) ): ?>
	<footer class="prefooter">
		<div class="container">
			<?php if(empty($custom_prefooter) && is_active_sidebar('prefooter')): ?>
				<?php dynamic_sidebar('prefooter'); ?>
			<?php elseif(!empty($custom_prefooter)): ?>
				<?php etheme_static_block($custom_prefooter, true); ?>
			<?php endif; ?>
		</div>
	</footer>
<?php endif; ?>