<?php
/**
 * The template created for displaying blog portfolio options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'portfolio' => array(
			'name'       => 'portfolio',
			'title'      => esc_html__( 'Portfolio', 'xstore' ),
			'description' => esc_html__('Whether you\'re a designer, photographer, artist, or freelancer, Portfolio has everything you need to create a stunning portfolio website that will impress your clients and showcase your work in the best possible light.', 'xstore'),
			'icon'       => 'dashicons-images-alt2',
			'priority'   => $priorities['portfolio'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/portfolio' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $sep_style ) {

    $image_sizes = implode(', ', array_unique(array_merge(array("thumbnail", "medium", "large", "full"), array_keys(etheme_get_image_sizes()))));

	$select_pages = et_customizer_get_posts(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'page'
		)
	);
	
	$select_pages[0] = esc_html__( 'Select page', 'xstore' );
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'portfolio_projects' => array(
			'name'        => 'portfolio_projects',
			'type'        => 'toggle',
			'settings'    => 'portfolio_projects',
			'label'       => esc_html__( 'Enable portfolio projects', 'xstore' ),
			'tooltip' => esc_html__( 'With this option enabled, you can quickly and easily add images, videos, and other multimedia to your portfolio, along with descriptions and other metadata. Plus, you can customize the layout and appearance of your portfolio with ease.', 'xstore' ),
			'section'     => 'portfolio',
			'default'     => 1,
		),

		'portfolio_page' => array(
			'name'            => 'portfolio_page',
			'type'            => 'select',
			'settings'        => 'portfolio_page',
			'label'           => esc_html__( 'Portfolio page', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose a page to be the main Portfolio page for displaying your projects.', 'xstore') . '<br/>' .
                            esc_html__('Note: using a static page will help us know the exact URL of the Portfolio page, as we will use this link in different areas of your project\'s content.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => '',
			'choices'         => $select_pages,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_filters_type' => array(
			'name'            => 'portfolio_filters_type',
			'type'            => 'select',
			'settings'        => 'portfolio_filters_type',
			'label'           => esc_html__( 'Filter categories type', 'xstore' ),
			'tooltip'     => esc_html__( 'If you want to show all portfolio categories for filtering projects, choose the "All" option. The "Only parent" value will prevent the display of subcategories for filtering.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'all',
			'choices'         => array(
				'all'    => esc_html__( 'All', 'xstore' ),
				'parent' => esc_html__( 'Only parent', 'xstore' ),
				// 'child'  => esc_html__( 'Only child', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_fullwidth' => array(
			'name'            => 'portfolio_fullwidth',
			'type'            => 'toggle',
			'settings'        => 'portfolio_fullwidth',
            'label'           => esc_html__( 'Full width', 'xstore' ),
            'tooltip' => esc_html__( 'Expand the page container area to the full width of the page.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'port_first_wide' => array(
			'name'            => 'port_first_wide',
			'type'            => 'toggle',
			'settings'        => 'port_first_wide',
			'label'           => esc_html__( 'Stretched 1st project', 'xstore' ),
			'tooltip'     => esc_html__( 'If you want to expand the first portfolio project, enable this option.', 'xstore' ) . '<br/>' .
                    esc_html__('Note: if the column count value is 4 or higher, the first project will take up the full width of the parent container; otherwise, it will be double the width of the normal projects.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_masonry' => array(
			'name'            => 'portfolio_masonry',
			'type'            => 'toggle',
			'settings'        => 'portfolio_masonry',
			'label'           => esc_html__( 'Masonry', 'xstore' ),
            'tooltip' => esc_html__( 'Turn on placing project in the most advantageous position based on the available vertical space.', 'xstore' ) . '<br/>' .
                esc_html__('Note: an additional script file will be loaded on your post archive pages.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_columns' => array(
			'name'            => 'portfolio_columns',
			'type'            => 'select',
			'settings'        => 'portfolio_columns',
            'label'    => esc_html__( 'Projects per row', 'xstore' ),
            'tooltip' => esc_html__( 'Choose how many projects to display per row on the portfolio page and portfolio category pages.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 3,
			'choices'         => array(
				2 => '2',
				3 => '3',
				4 => '4',
				5 => '5',
				6 => '6',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_margin' => array(
			'name'            => 'portfolio_margin',
			'type'            => 'select',
			'settings'        => 'portfolio_margin',
			'label'           => esc_html__( 'Columns gap (px)', 'xstore' ),
			'tooltip'     => esc_html__('Choose the distance value between the projects on the portfolio page and the portfolio category pages.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => 15,
			'choices'         => array(
				1  => '0',
				5  => '5',
				10 => '10',
				15 => '15',
				20 => '20',
				30 => '30',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

		'portfolio_count' => array(
			'name'            => 'portfolio_count',
			'type'            => 'etheme-text',
			'settings'        => 'portfolio_count',
            'label'       => esc_html__( 'Projects per page', 'xstore' ),
            'tooltip' => esc_html__( 'This controls the number of projects displayed per page before pagination is displayed.', 'xstore' ) . '<br/>' .
                esc_html__('Note: use a value of -1 to show all projects at once.', 'xstore'),
			'section'         => 'portfolio',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

        'portfolio_order' => array(
            'name'            => 'portfolio_order',
            'type'            => 'select',
            'settings'        => 'portfolio_order',
            'label'           => esc_html__( 'Order way', 'xstore' ),
            'tooltip'     => sprintf(esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter for %1s of getting projects. Defaults to ‘Descending’.', 'xstore' ),
                    '<a href="https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters" target="_blank" rel="nofollow">'.esc_html__('main query', 'xstore').'</a>') . '<br/>' .
                esc_html__('Info: Simply put, this option defines how you want to view projects: \'Ascending\' means that the newest projects will be displayed at the top of the page, while \'Descending\' means that the oldest projects will be displayed first.', 'xstore'),
            'section'         => 'portfolio',
            'default'         => 'DESC',
            'choices'         => array(
                'DESC' => 'Descending',
                'ASC'  => 'Ascending',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'portfolio_orderby' => array(
            'name'            => 'portfolio_orderby',
            'type'            => 'select',
            'settings'        => 'portfolio_orderby',
            'label'           => esc_html__( 'Order by', 'xstore' ),
            'tooltip'     => esc_html__( 'Choose the way you want to sort retrieved projects by the value you set. Defaults to \'Title\'.', 'xstore' ),
            'section'         => 'portfolio',
            'default'         => 'title',
            'choices'         => array(
                'title' => 'Title',
                'date'  => 'Date',
                'ID'    => 'ID',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'portfolio_project_style_separator'                        => array(
            'name'     => 'portfolio_project_style_separator',
            'type'     => 'custom',
            'settings' => 'portfolio_project_style_separator',
            'section'  => 'portfolio',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-align-left"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Project design', 'xstore' ) . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'portfolio_style' => array(
            'name'            => 'portfolio_style',
            'type'            => 'select',
            'settings'        => 'portfolio_style',
            'label'           => esc_html__( 'Project design', 'xstore' ),
            'tooltip' => esc_html__( 'Choose the type of content that will be displayed for projects on the portfolio page, portfolio category pages, and in the "Portfolio" elements of the page builder you are using.', 'xstore' ),
            'section'         => 'portfolio',
            'default'         => 'default',
            'choices'         => array(
                'default' => esc_html__( 'Static content', 'xstore' ),
                'classic' => esc_html__( 'Content on hover', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

		'portfolio_images_size' => array(
			'name'            => 'portfolio_images_size',
			'type'            => 'etheme-text',
			'settings'        => 'portfolio_images_size',
			'label'           => esc_html__( 'Project image size', 'xstore' ),
            'tooltip' => sprintf(esc_html__( 'Use this option to configure the size of the project image that is displayed on the portfolio page and portfolio category pages. Possible values are: %1s.', 'xstore' ),
                    $image_sizes) . '<br/>' .
                esc_html__('Alternatively, you can enter the size in pixels, for example 200x100 (width by height).', 'xstore'),
			'section'         => 'portfolio',
			'default'         => 'large',
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),

        'portfolio_single_project_separator'                        => array(
            'name'     => 'portfolio_single_project_separator',
            'type'     => 'custom',
            'settings' => 'portfolio_single_project_separator',
            'section'  => 'portfolio',
            'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-format-aside"></span> <span style="padding-inline-start: 5px;">' . esc_html__( 'Single project settings', 'xstore' ) . '</span></div>',
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
        'port_single_nav' => array(
            'name'            => 'port_single_nav',
            'type'            => 'toggle',
            'settings'        => 'port_single_nav',
            'label'       => esc_html__( 'Prev/Next navigation', 'xstore' ),
            'tooltip'  => esc_html__( 'Enable this option to display the previous and next navigation arrows on your single project pages. Note: the previous and next project links are based on the projects which are in the same primary category as the current project.', 'xstore' ),
            'section'         => 'portfolio',
            'default'         => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'portfolio_projects',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	);
	
	return array_merge( $fields, $args );
	
} );