<?php
/**
 * Template Name: Content
 * @xstore-version 9.4.0
 */

global $et_loop;

$options = array();

$options['layout'] = get_query_var('et_post-layout', false);
if ( !$options['layout'] ) {
    $options['layout'] = etheme_get_option('blog_layout', 'default');
    set_query_var('et_post-layout', $options['layout']);
}

if( ! empty( $et_loop['blog_layout'] ) ) {
    $options['layout'] = $et_loop['blog_layout'];
}

if( empty( $et_loop['loop'] ) ) {
	$et_loop['loop'] = 0;
}

$options['excerpt_length'] = get_query_var('et_post-excerpt-length', false);
if ( !$options['excerpt_length'] ) {
    $options['excerpt_length'] = etheme_get_option('excerpt_length', 25);
    set_query_var('et_post-excerpt-length', $options['excerpt_length']);
}

$options['postClass'] = etheme_post_class( $options['layout'] );

$options['size'] = get_query_var('et_post-img-size', false);
if ( !$options['size'] ) {
    $options['size'] = etheme_get_option( 'blog_images_size', 'large' );
    set_query_var('et_post-img-size', $options['size']);
}

$options['by_line'] = get_query_var('et_post-byline', 'unset'); // unset value to prevent '', true, false as default and correct set query var
if ( $options['by_line'] == 'unset' ) {
    $options['by_line'] = etheme_get_option('blog_byline', 1);
    set_query_var('et_post-byline', $options['by_line']);
}

$options['hide_img'] = false;

if( ! empty( $et_loop['size'] ) ) {
    $options['size'] = $et_loop['size'];
}

if( ! empty( $et_loop['hide_img'] ) ) {
    $options['hide_img'] = $et_loop['hide_img'];
}

// get permalink before content because if content has products then link is broken
$options['the_permalink'] = get_the_permalink();
$options['show_sticky'] = is_sticky() && is_home() && ! is_paged();

if ( get_post_format() == 'quote' ) {
	etheme_enqueue_style( 'post-quote' );
}

?>

<article <?php post_class($options['postClass']); ?> id="post-<?php the_ID(); ?>" >
    <div>

        <?php if ( $options['layout'] != 'with-author' && !$options['hide_img'] ): ?>
            <?php etheme_post_thumb( array( 'size' => $options['size'] ) ); ?>
        <?php endif ?>
    
        <div class="post-data">
            <div class="post-heading">
                <?php
                    if ( $options['layout'] != 'with-author' && $options['show_sticky'] ) {
                        printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'xstore' ) );
                    }
                ?>
                <?php if( $options['layout'] == 'with-author' ): //etheme_get_option('about_author') && $options['layout'] == 'title-left' ||  ?>
                    <div class="author-info">
                        <?php echo get_avatar( get_the_author_meta('email') , 40 ); ?>
                        <?php the_author_link(); ?>
                    </div>
                <?php endif; ?>
                <div class="post-heading-inner">
                    <?php 
                        if ( $options['layout'] == 'with-author' && $options['show_sticky'] ) {
                            printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'xstore' ) );
                        }
                    ?>
                    <?php if ( $options['layout'] != 'with-author' ): ?>
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                    <?php endif ?>
                    <?php
//                    $author = 1;
//                    $time = 0;
//                    if($options['layout'] == 'small' || $options['layout'] == 'title-left') $author = 0;
//                    if($options['layout'] == 'title-left') $time = 1;
                    if($options['by_line']):
                        etheme_byline( array( 'author' => 0 ) ); 
                    endif; 
                    ?>
                    <?php if ( $options['layout'] == 'with-author' ): ?>
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                    <?php endif ?>
                </div>
            </div>

            <?php if ( $options['layout'] == 'with-author' && !$options['hide_img'] ): ?>
                <?php etheme_post_thumb( array( 'size' => $options['size'] ) ); ?>
            <?php endif ?>

            <div class="content-article entry-content">
                <?php if ( $options['excerpt_length'] > 0 ) {
                    $excerpt = get_the_excerpt();
                    if ( strlen($excerpt) > 0 ) {
                        $options['excerpt_length'] = apply_filters( 'excerpt_length', $options['excerpt_length'] );
                        $options['excerpt_more'] = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
                        $options['text']         = wp_trim_words( $excerpt, $options['excerpt_length'], $options['excerpt_more'] );
                        echo apply_filters( 'wp_trim_excerpt', $options['text'], $options['text'] );
                    }
                    else 
                        the_excerpt();
                }  ?>
                <?php etheme_read_more( $options['the_permalink'], true ) ?>
            </div>

            <?php if($options['layout'] != 'title-left' && etheme_get_option('about_author', 1) ): ?>
                <div class="author-info">
                    <?php echo get_avatar( get_the_author_meta('email') , 40 ); ?>
                    <?php the_author_link(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(in_array($options['layout'], array('timeline', 'timeline2')) ): ?>

        <?php // if ( $options['layout'] == 'timeline2' || $options['layout'] == 'timeline' ): ?>
            <div class="timeline-content">
        <?php // endif; ?>
        <div class="meta-post-timeline">
            <span class="time-day"><?php the_time('d'); ?></span>
            <span class="time-mon"><?php the_time('M'); ?></span>
        </div>
        <?php // if ( $options['layout'] == 'timeline2' || $options['layout'] == 'timeline' ): ?>
            </div><!-- .timeline-content -->
        <?php // endif; ?>
    <?php endif; ?>
</article>
<?php
    $et_loop['loop']++;
    unset($options);