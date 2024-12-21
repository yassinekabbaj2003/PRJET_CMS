<?php
/**
 * The template created for displaying blog page options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'blog-blog_page' => array(
			'name'       => 'blog-blog_page',
			'title'      => esc_html__( 'Blog Layout', 'xstore' ),
			'panel'      => 'blog',
			'icon'       => 'dashicons-schedule',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/blog-blog_page' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $blog_layout, $sep_style, $sidebars, $brand_label ) {
    $wpbakery_builder = defined('WPB_VC_VERSION');
    $image_sizes = implode(', ', array_unique(array_merge(array("thumbnail", "medium", "large", "full"), array_keys(etheme_get_image_sizes()))));
	$args = array();
	
	// Array of fields
	$args = array(
		// General layout
		'blog_layout' => array(
			'name'        => 'blog_layout',
			'type'        => 'radio-image',
			'settings'    => 'blog_layout',
			'label'       => esc_html__( 'Layout', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the layout type for the blog page and post archive pages.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 'default',
			'choices'     => $blog_layout,
		),
		
		'blog_columns'              => array(
			'name'            => 'blog_columns',
			'type'            => 'select',
			'settings'        => 'blog_columns',
//			'label'           => esc_html__( 'Columns', 'xstore' ),
//			'tooltip'     => esc_html__( 'Choose the number of columns for the posts at the blog page.', 'xstore' ),
            'label'    => esc_html__( 'Posts per row', 'xstore' ),
            'tooltip' => esc_html__( 'Choose how many posts to display per row on the blog page and post archive pages.', 'xstore' ),
			'section'         => 'blog-blog_page',
			'default'         => 3,
			'choices'         => array(
				2 => '2',
				3 => '3',
				4 => '4',
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout',
					'operator' => 'in',
					'value'    => array( 'grid', 'grid2' ),
				),
			)
		),
		'blog_full_width'           => array(
			'name'            => 'blog_full_width',
			'type'            => 'toggle',
			'settings'        => 'blog_full_width',
			'label'           => esc_html__( 'Full width', 'xstore' ),
            'tooltip' => esc_html__( 'Expand the page container area to the full width of the page.', 'xstore'),
			'section'         => 'blog-blog_page',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout',
					'operator' => 'in',
					'value'    => array( 'grid', 'grid2' ),
				),
			)
		),
		'blog_masonry'              => array(
			'name'            => 'blog_masonry',
			'type'            => 'toggle',
			'settings'        => 'blog_masonry',
			'label'           => esc_html__( 'Masonry', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on placing post in the most advantageous position based on the available vertical space.', 'xstore' ) . '<br/>' .
                esc_html__('Note: an additional script file will be loaded on your post archive pages.', 'xstore'),
			'section'         => 'blog-blog_page',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout',
					'operator' => 'in',
					'value'    => array( 'grid', 'grid2' ),
				),
			)
		),
		'blog_sidebar'              => array(
			'name'        => 'blog_sidebar',
			'type'        => 'radio-image',
			'settings'    => 'blog_sidebar',
			'label'       => esc_html__( 'Sidebar position', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar for the blog page, post archives pages and all simple pages such as "About Us" or "Contact Us" pages.', 'xstore' ) . '<br/>' .
                sprintf(esc_html__('Note: Each page has an individual option in the "%1s Options" section for changing the position of the sidebar and many others.', 'xstore'), $brand_label),
            sprintf(esc_html__('Tip: The "%1s Options" can be found by going to Dashboard > Pages > Edit Page and scrolling down.', 'xstore'), $brand_label),
			'section'     => 'blog-blog_page',
			'default'     => 'right',
			'choices'     => $sidebars,
		),
		'only_blog_sidebar'         => array(
			'name'        => 'only_blog_sidebar',
			'type'        => 'toggle',
			'settings'    => 'only_blog_sidebar',
			'label'       => esc_html__( 'Sidebar only for blog page', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to show the sidebar on the blog page and post archives pages only and keep it disabled for all other simple pages such as "About Us" and "Contact Us" pages.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 0,
		),
		'sticky_sidebar'            => array(
			'name'        => 'sticky_sidebar',
			'type'        => 'toggle',
			'settings'    => 'sticky_sidebar',
			'label'       => esc_html__( 'Sticky sidebar', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on the option to keep the sidebar visible while scrolling the window on the blog page and post archives pages.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 0,
		),
		'blog_sidebar_for_mobile'   => array(
			'name'     => 'blog_sidebar_for_mobile',
			'type'     => 'select',
			'settings' => 'blog_sidebar_for_mobile',
			'label'    => esc_html__( 'Sidebar position for mobile', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the position of the sidebar on mobile devices.', 'xstore' ),
			'section'  => 'blog-blog_page',
			'default'  => 'bottom',
			'choices'  => array(
				'top'    => esc_html__( 'Top', 'xstore' ),
				'bottom' => esc_html__( 'Bottom', 'xstore' ),
			),
		),
        'blog_page_banner_pos'      => array(
            'name'        => 'blog_page_banner_pos',
            'type'        => 'select',
            'settings'    => 'blog_page_banner_pos',
            'label'       => esc_html__( 'Banner position', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the position of the blog page banner.', 'xstore' ),
            'section'     => 'blog-blog_page',
            'default'     => 1,
            'choices'     => array(
                1 => esc_html__( 'At the top of the page', 'xstore' ),
                2 => esc_html__( 'At the bottom of the page', 'xstore' ),
                3 => esc_html__( 'Above all the blog content', 'xstore' ),
                4 => esc_html__( 'Above all the blog content (full-width)', 'xstore' ),
                0 => esc_html__( 'Disable', 'xstore' ),
            ),
        ),
        'blog_page_banner'          => array(
            'name'            => 'blog_page_banner',
            'type'            => 'editor',
            'settings'        => 'blog_page_banner',
            'label'           => esc_html__( 'Banner content', 'xstore' ),
            'tooltip'     => esc_html__( 'Here, you can write your own custom HTML using the tags in the top bar of the editor. However, please note that not all HTML tags and element attributes can be used due to Theme Options safety reasons.', 'xstore' ),
            'section'         => 'blog-blog_page',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'blog_page_banner_pos',
                    'operator' => '!=',
                    'value'    => 0,
                ),
            )
        ),
        'blog_navigation_type'      => array(
            'name'        => 'blog_navigation_type',
            'type'        => 'select',
            'settings'    => 'blog_navigation_type',
            'label'       => esc_html__( 'Pagination type', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of pagination for the blog page and post archives pages. Note: this is the type of loading method for loading the next posts from the next pages.', 'xstore' ),
            'section'     => 'blog-blog_page',
            'default'     => 'pagination',
            'choices'     => array(
                'pagination' => esc_html__( 'Pagination', 'xstore' ),
                'button'     => esc_html__( 'More Button', 'xstore' ),
                'lazy'       => esc_html__( 'Lazy Loading', 'xstore' ),
            ),
        ),
        'blog_pagination_align'     => array(
            'name'            => 'blog_pagination_align',
            'type'            => 'select',
            'settings'        => 'blog_pagination_align',
            'label'           => esc_html__( 'Pagination alignment', 'xstore' ),
            'tooltip'     => esc_html__( 'Using this option, you can choose an alignment value for this element.', 'xstore' ),
            'section'         => 'blog-blog_page',
            'default'         => 'right',
            'choices'         => array(
                'left'   => esc_html__( 'Left', 'xstore' ),
                'center' => esc_html__( 'Center', 'xstore' ),
                'right'  => esc_html__( 'Right', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'blog_navigation_type',
                    'operator' => '==',
                    'value'    => 'pagination',
                ),
            )
        ),
//		'blog_pagination_prev_next' => array(
//			'name'            => 'blog_pagination_prev_next',
//			'type'            => 'toggle',
//			'settings'        => 'blog_pagination_prev_next',
//			'label'           => esc_html__( 'Prev/next pagination links', 'xstore' ),
//			'tooltip'     => esc_html__( 'Turn on to enable the previous and next links.', 'xstore' ),
//			'section'         => 'blog-blog_page',
//			'default'         => 0,
//			'active_callback' => array(
//				array(
//					'setting'  => 'blog_navigation_type',
//					'operator' => '==',
//					'value'    => 'pagination',
//				),
//			)
//		),
        // style separator
        'blog_posts_style_separator'                        => array(
            'name'     => 'blog_posts_style_separator',
            'type'     => 'custom',
            'settings' => 'blog_posts_style_separator',
            'section'  => 'blog-blog_page',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-align-left"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Posts design', 'xstore' ) . '</span></div>',
        ),
		'blog_hover'                => array(
			'name'        => 'blog_hover',
			'type'        => 'select',
			'settings'    => 'blog_hover',
			'label'       => esc_html__( 'Image hover effect', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the hover effect that will be displayed for posts on the blog page, post archives pages, and related posts on the individual post page. There are many attractive effects, so you are sure to find one that you and your customers will like. Alternatively, you can disable the effect if you prefer a static design without any effects.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 'zoom',
			'choices'     => array(
				'zoom'     => esc_html__( 'Default', 'xstore' ),
				'default'  => esc_html__( 'Mask hover', 'xstore' ),
				'animated' => esc_html__( 'Animated', 'xstore' ),
				'none'     => esc_html__( 'None', 'xstore' ),
			),
		),
        'blog_images_size'          => array(
            'name'        => 'blog_images_size',
            'type'        => 'etheme-text',
            'settings'    => 'blog_images_size',
            'label'       => esc_html__( 'Post image size', 'xstore' ),
            'tooltip' => sprintf(esc_html__( 'Use this option to configure the size of the featured image that is displayed on the blog page and post archives pages. Possible values are: %1s.', 'xstore' ),
                    $image_sizes) . '<br/>' .
                esc_html__('Alternatively, you can enter the size in pixels, for example 200x100 (width by height).', 'xstore'),
            'section'     => 'blog-blog_page',
            'default'     => 'large',
        ),
        'blog_related_images_size'  => array(
            'name'        => 'blog_related_images_size',
            'type'        => 'etheme-text',
            'settings'    => 'blog_related_images_size',
            'label'       => esc_html__( 'Related posts image size', 'xstore' ),
            'tooltip' => sprintf(esc_html__( 'Use this option to configure the size of the featured image that is displayed on the individual post pages. Possible values are: %1s.', 'xstore' ),
                    $image_sizes) . '<br/>' .
                esc_html__('Alternatively, you can enter the size in pixels, for example 200x100 (width by height).', 'xstore'),
            'section'     => 'blog-blog_page',
            'default'     => 'medium',
        ),
		'blog_byline'               => array(
			'name'        => 'blog_byline',
			'type'        => 'toggle',
			'settings'    => 'blog_byline',
			'label'       => esc_html__( 'Show "byline"', 'xstore' ),
			'tooltip' => esc_html__( 'Use this option to display detailed information about the post, including the date of creation, author, number of comments, and number of views.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 1,
		),
        'views_counter'             => array(
            'name'        => 'views_counter',
            'type'        => 'toggle',
            'settings'    => 'views_counter',
            'label'       => esc_html__( 'Views counter', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to enable the view counter for each post.', 'xstore' ),
            'section'     => 'blog-blog_page',
            'default'     => 1,
        ),
		'blog_categories'           => array(
			'name'     => 'blog_categories',
			'type'     => 'toggle',
			'settings' => 'blog_categories',
			'label'    => esc_html__( '"Category" label', 'xstore' ),
            'tooltip' => esc_html__( 'Enable this option to display the primary "Category" label for each post on the blog page, post archives pages, and related posts on the individual post page.', 'xstore' ) . '<br/>' .
                        sprintf(esc_html__('Note: Each post has an individual option in the "%1s Options" section for setting the "Primary Category" and many other settings.', 'xstore'), $brand_label) . '<br/>' .
                        sprintf(esc_html__('Tip: The "%1s Options" can be found by going to the Dashboard > Posts > Edit Post and scrolling down.', 'xstore'), $brand_label),
			'section'  => 'blog-blog_page',
			'default'  => 1,
		),
		'excerpt_length'            => array(
			'name'        => 'excerpt_length',
			'type'        => 'slider',
			'settings'    => 'excerpt_length',
			'label'       => esc_html__( 'Excerpt length (words)', 'xstore' ),
            'tooltip'     => esc_html__( 'This controls the words length of each post excerpt.', 'xstore') . ($wpbakery_builder ?
                    ' ' . sprintf(esc_html__('Important: This option does not apply to custom designs for posts created using the %1s.', 'xstore' ),
                        '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>') : ''),
			'section'     => 'blog-blog_page',
			'default'     => 25,
			'choices'     => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
		),
		'excerpt_length_sliders'    => array(
			'name'        => 'excerpt_length_sliders',
			'type'        => 'slider',
			'settings'    => 'excerpt_length_sliders',
			'label'       => esc_html__( 'Excerpt length (words) for posts sliders', 'xstore' ),
            'tooltip'     => esc_html__( 'This controls the length of each excerpt of post for posts displayed in sliders, such as related posts on an individual post page.', 'xstore') . ($wpbakery_builder ?
                    ' ' . sprintf(esc_html__('Important: This option does not apply to custom designs for posts created using the %1s.', 'xstore' ),
                        '<a href="https://wpbakery.com/video-academy/category/grid/" target="_blank">' . esc_html__( 'WPBakery Grid builder', 'xstore' ) . '</a>') : ''),
			'section'     => 'blog-blog_page',
			'default'     => 25,
			'choices'     => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
		),
		'excerpt_words'             => array(
			'name'        => 'excerpt_words',
			'type'        => 'etheme-text',
			'settings'    => 'excerpt_words',
			'label'       => esc_html__( 'Excerpt symbols', 'xstore' ),
			'tooltip' => esc_html__( 'Add symbols that you want to display at the end of the post excerpt, with the default being \'...\'.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => '...',
		),
		'read_more'                 => array(
			'name'        => 'read_more',
			'type'        => 'select',
			'settings'    => 'read_more',
			'label'       => esc_html__( 'Type of "read more" link', 'xstore' ),
			'tooltip' => esc_html__( 'Choose the style of the "Continue Reading" text.', 'xstore') . '<br/>' .
                esc_html__('Note: Select "Disable" to hide the "Continue Reading" button in post content entirely.', 'xstore' ),
			'section'     => 'blog-blog_page',
			'default'     => 'link',
			'choices'     => array(
				'link' => esc_html__( 'Link', 'xstore' ),
				'btn'  => esc_html__( 'Button', 'xstore' ),
				'off'  => esc_html__( 'Disable', 'xstore' ),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
