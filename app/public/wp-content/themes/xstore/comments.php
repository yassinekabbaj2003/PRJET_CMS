<?php
/**
 * Template Name: Comments
 * @xstore-version 9.4.0
 */

	// Prevent the direct loading

	defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

	// Check if post is pwd protected
	if(post_password_required()){
		?>
		<p><?php esc_html_e('This post is password protected. Enter the password to view the comments.', 'xstore'); ?></p>
		<?php
		return;
	}

	if(have_comments()) :?>
		<div class="comments" id="comments">
			<h4 class="title-alt">
			<span>
				<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
                    echo esc_html__( 'One comment', 'xstore' );
				} else {
					printf( 
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s comment', '%1$s comments', $comments_number, 'comments title', 'xstore' ) ),
						number_format_i18n( $comments_number ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);
				}
				?>
			</span>
			</h4>

			<ul class="comments-list">
				<?php wp_list_comments('callback=etheme_comments'); ?>
			</ul>

			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>

				<div class="comments-nav">
					<div class="pull-left"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'xstore')); ?></div>
					<div class="pull-right"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'xstore')); ?></div>
					<div class="clear"></div>
				</div>

			<?php endif ?>

		</div>

	<?php elseif(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>

		<p class="no-comments"><?php esc_html_e('Comments are closed', 'xstore') ?></p>

		<?php
	endif;

	// Display Comment Form
	comment_form(array('title_reply' => '<span>' . esc_html__('Add comment', 'xstore') . '</span>'));
