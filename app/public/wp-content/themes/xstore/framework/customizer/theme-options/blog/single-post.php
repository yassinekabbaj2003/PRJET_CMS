<?php
/**
 * The template created for displaying blog page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'blog-single-post' => array(
			'name'       => 'blog-single-post',
			'title'      => esc_html__( 'Single post', 'xstore' ),
			'panel'      => 'blog',
			'icon'       => 'dashicons-format-aside',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/blog-single-post' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $post_template, $sidebars ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'post_template' => array(
			'name'        => 'post_template',
			'type'        => 'radio-image',
			'settings'    => 'post_template',
			'label'       => esc_html__( 'Layout', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the layout type for the individual post pages.', 'xstore' ) . '<br/>' .
                esc_html__('The "Backstretch" feature will be applied to the "Large", "Full Width", and "Full Width Centered" layouts. It will stretch any image to fit the page or block-level element and will automatically resize as the window or element size changes.', 'xstore') . '<br/>' .
                esc_html__('Note: an additional script file will be loaded on your individual post pages for correct work of "Backstretch" feature.', 'xstore'),
			'section'     => 'blog-single-post',
			'default'     => 'default',
			'choices'     => $post_template,
		),
		
		'post_sidebar' => array(
			'name'        => 'post_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'post_sidebar',
			'label'       => esc_html__( 'Sidebar position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar for the individual post pages.', 'xstore' ),
			'section'     => 'blog-single-post',
			'default'     => 'right',
			'choices'     => $sidebars,
		),
		
		'blog_featured_image' => array(
			'name'        => 'blog_featured_image',
			'type'        => 'toggle',
			'settings'    => 'blog_featured_image',
			'label'       => esc_html__( 'Featured image', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display the featured image of a post at the top of individual post pages.', 'xstore' ) . '<br/>' .
                            esc_html__('Note: if the post does not have a set featured image and you are using one of the "Large", "Full Width", and "Full Width Centered" layouts, then you will see design issues. To fix this issue, you should set the featured image for all posts to ensure the correct functioning of the "Backstretch" feature, or change the layout type.', 'xstore'),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'single_post_title' => array(
			'name'        => 'single_post_title',
			'type'        => 'toggle',
			'settings'    => 'single_post_title',
            'label'       => esc_html__( 'Show "byline"', 'xstore' ),
            'tooltip' => esc_html__( 'Use this option to display detailed information about the post, including the date of creation, author, number of comments, and number of views.', 'xstore' ) . '<br/>' .
                    sprintf(esc_html__('It is always possible to use the shortcode %1s in post content as an alternative to this option.', 'xstore'), '[etheme_post_meta time="true" time_details="true" author="true" comments="true" count="true" class=""]'),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'post_share' => array(
			'name'        => 'post_share',
			'type'        => 'toggle',
			'settings'    => 'post_share',
			'label'       => esc_html__( 'Share buttons', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to display the share buttons in the content on each individual post page.', 'xstore' ),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'about_author' => array(
			'name'        => 'about_author',
			'type'        => 'toggle',
			'settings'    => 'about_author',
			'label'       => esc_html__( 'About author', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display author information on individual post pages, such as the name of the writer, avatar, a description of the author, and a link to the author\'s posts.', 'xstore' ),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'posts_links' => array(
			'name'        => 'posts_links',
			'type'        => 'toggle',
			'settings'    => 'posts_links',
            'label'       => esc_html__( 'Prev/Next navigation', 'xstore' ),
            'tooltip'  => esc_html__( 'Enable this option to display the previous and next navigation arrows on your single post pages. Note: the previous and next post links are based on the posts which are in the same primary category as the current post.', 'xstore' ),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'post_related' => array(
			'name'        => 'post_related',
			'type'        => 'toggle',
			'settings'    => 'post_related',
			'label'       => esc_html__( 'Display related posts', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to show related posts on the individual post pages.', 'xstore' ),
			'section'     => 'blog-single-post',
			'default'     => 1,
		),
		
		'related_query' => array(
			'name'            => 'related_query',
			'type'            => 'select',
			'settings'        => 'related_query',
			'label'           => esc_html__( 'Related posts query', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the taxonomy type by which the related posts should be displayed.', 'xstore' ),
			'section'         => 'blog-single-post',
			'default'         => 'categories',
			'choices'         => array(
				'categories' => esc_html__( 'Categories', 'xstore' ),
				'tags'       => esc_html__( 'Tags', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'post_related',
					'operator' => '==',
					'value'    => true,
				),
			)
		)
	);
	
	return array_merge( $fields, $args );
	
} );