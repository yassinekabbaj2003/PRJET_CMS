<?php
/**
 * Template Name: Content-grid-2
 * @xstore-version 9.4.0
 */

global $et_loop;

if( empty( $et_loop['columns'] ) ) {
    $et_loop['columns'] = etheme_get_option('blog_columns', 3);
}

if( empty( $et_loop['slider'] ) ) {
    $et_loop['slider'] = false;
}

if( empty( $et_loop['loop'] ) ) {
    $et_loop['loop'] = 0;
}

$options = array();

$options['layout'] = get_query_var('et_post-layout', false);
if ( !$options['layout'] ) {
    $options['layout'] = etheme_get_option('blog_layout', 'default');
    set_query_var('et_post-layout', $options['layout']);
}

$options['by_line'] = get_query_var('et_post-byline', 'unset'); // unset value to prevent '', true, false as default and correct set query var
if ( $options['by_line'] == 'unset' ) {
    $options['by_line'] = etheme_get_option('blog_byline', 1);
    set_query_var('et_post-byline', $options['by_line']);
}
$options['size'] = get_query_var('et_post-img-size', false);
if ( !$options['size'] ) {
    $options['size'] = etheme_get_option( 'blog_images_size', 'large' );
    set_query_var('et_post-img-size', $options['size']);
}
$options['hide_img'] = false;
$options['excerpt_length'] = get_query_var('et_post-excerpt-length', false);
if ( !$options['excerpt_length'] ) {
    $options['excerpt_length'] = etheme_get_option('excerpt_length', 25);
    set_query_var('et_post-excerpt-length', $options['excerpt_length']);
}

// get permalink before content because if content has products then link is bloken
$options['the_permalink'] = get_the_permalink();

if ( is_single() && $options['layout'] == 'timeline2' ) {
    $et_loop['slide_view'] = $options['layout'];
}

if( ! empty( $et_loop['blog_layout'] ) ) {
    $options['layout'] = $et_loop['blog_layout'];
}

if( ! empty( $et_loop['size'] ) ) {
    $options['size'] = $et_loop['size'];
}

if( ! empty( $et_loop['hide_img'] ) ) {
    $options['hide_img'] = $et_loop['hide_img'];
}

$options['postClass']      = etheme_post_class( $options['layout'] );

?>

<article <?php post_class( $options['postClass'] ); ?> id="post-<?php the_ID(); ?>" >
    <div>
        <div class="meta-post-timeline">
            <span class="time-day"><?php the_time('d'); ?></span>
            <span class="time-mon"><?php the_time('M'); ?></span>
        </div>

        <?php
            if ( !$options['hide_img'] ) { 
                etheme_post_thumb( array('size' => $options['size'], 'in_slider' => $et_loop['slider'] ) ); 
            }
        ?>

        <div class="grid-post-body">
            <div class="post-heading">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php if($options['by_line']): ?>
                    <?php etheme_byline( array( 'author' => 0, 'in_slider' => $et_loop['slider'] ) );  ?>
                <?php endif; ?>
            </div>

            <div class="content-article">
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
        </div>
    </div>
</article>
<?php

$et_loop['loop']++;

unset($options); ?>