<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme footer
 *
 * Override this template by copying it to yourtheme/templates/footer/footer.php
 * @author 	   8theme
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 * @since   6.2.12
 * @xstore-version 9.4.0
 */

$custom_footer = etheme_get_query_custom_field('custom_footer');
$fcolor = etheme_get_option('footer_color', 'dark');

?>

<?php if($custom_footer != 'without' && ( ! empty( $custom_footer ) || is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4') )): ?>
	<footer class="footer text-color-<?php echo esc_attr($fcolor); ?>">
		<div class="container">
			<?php if(empty($custom_footer)): ?>
				<div class="row">
					<?php
					$footer_columns = (int) etheme_get_option('footer_columns', 4);
					if( $footer_columns < 1 || $footer_columns > 4) $footer_columns = 4;
                    $footer_widget_class = 'col-md-';
                    switch ($footer_columns) {
                        case 1:
                            $footer_widget_class .= 12;
                            break;
                        case 2:
                            $footer_widget_class .= 6;
                            break;
                        case 3:
                            $footer_widget_class .= 4;
                            break;
                        case 4:
                            $footer_widget_class .= 3;
                            $footer_widget_class .= ' col-sm-6';
                            break;
                        default:
                            $footer_widget_class .= 3;
                            break;
                    }
					for($_i=1; $_i<=$footer_columns; $_i++) {
						echo '<div class="footer-widgets ' . $footer_widget_class .'">';
							if(is_active_sidebar('footer-'.$_i)) dynamic_sidebar( 'footer-'.$_i );
						echo '</div>';
					}
					?>
				</div>
			<?php else: ?>
				<?php etheme_static_block($custom_footer, true); ?>
			<?php endif; ?>
		</div>
	</footer>
<?php endif; ?>