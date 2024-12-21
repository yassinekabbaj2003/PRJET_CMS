<?php
/**
 * The template created for displaying general optimization options
 *
 * @version 0.0.1
 * @since 9.0.3
 */
add_filter( 'et/customizer/add/sections', function($sections) {

    $args = array(
        'general-seo'	 => array(
            'name'        => 'general-seo',
            'title'          => esc_html__( 'SEO', 'xstore-core' ),
            'description' => esc_html__('With Built-in SEO options, your website will be more visible, more effective, and more successful than ever before. Improve your website\'s search engine rankings with our Built-in powerful SEO options.', 'xstore-core'),
            'icon' => 'dashicons-megaphone',
            'priority' => 17,
            'type'		=> 'kirki-lazy',
            'dependency'    => array()
        )
    );
    return array_merge( $sections, $args );
});

add_filter( 'et/customizer/add/fields/general-seo', function ( $fields ) use ( $separators, $strings, $choices, $sep_style, $brand_label ) {

    $args = array();

    // Array of fields
    $args = array(
        'et_seo_switcher'	=> array(
            'name'		  => 'et_seo_switcher',
            'type'        => 'toggle',
            'settings'    => 'et_seo_switcher',
            'label'       => esc_html__( 'Enable SEO settings', 'xstore-core' ),
            'tooltip' => esc_html__('With just a few clicks, you can enable SEO features to optimize your website\'s title tags, meta descriptions, and URLs, making it easier for search engines to crawl and index your site.', 'xstore-core') . '<br/>' .
                    esc_html__('Tip: It can be safely disabled if any SEO plugin is used.', 'xstore-core'),
            'section'     => 'general-seo',
            'default'     => 0,
        ),

        // et_seo_meta_description
        'et_seo_meta_description'             => array(
            'name'            => 'et_seo_meta_description',
            'type'            => 'etheme-textarea',
            'settings'        => 'et_seo_meta_description',
            'label'           => esc_html__( 'Custom meta description', 'xstore-core' ),
            'tooltip' => esc_html__('The meta description summarizes a page\'s content and presents that to users in the search results. It’s one of the first things people will likely see when searching for something, so optimizing it is crucial for SEO. It’s your chance to persuade users to click on your result!', 'xstore-core') . '<br/>' .
                sprintf(esc_html__('Leave empty to use site tagline set in %s', 'xstore-core'),
                    '<a href="'.admin_url('options-general.php').'" target="_blank">'.esc_html__('General settings', 'xstore-core').'</a>') . '<br/>' .
                sprintf(esc_html__('Note: Each page, post, and product has an individual option in the "%1s Options" section for setting the "Meta description" and many other SEO settings.', 'xstore-core'), $brand_label) . '<br/>' .
                sprintf(esc_html__(' Tip: The "%1s Options" can be found by going to the Dashboard > Posts (Pages/Products) > Edit Post (Page/Product) and scrolling down to the SEO tab.', 'xstore-core'), $brand_label),
            'section'         => 'general-seo',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'et_seo_switcher',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        // et_seo_meta_description
        'et_seo_meta_keywords'             => array(
            'name'            => 'et_seo_meta_keywords',
            'type'            => 'etheme-textarea',
            'settings'        => 'et_seo_meta_keywords',
            'label'           => esc_html__( 'Meta keywords', 'xstore-core' ),
            'tooltip' => esc_html__('Search engine optimization (SEO) keywords are terms added to online content to improve the rankings of those terms in search engine results. Keywords are essential for all other SEO efforts, so it is worth investing time and resources to ensure that your SEO keywords are highly relevant to your audience and properly organized for action. Writing the best comma separated keywords will drive more targeted traffic to your website in the future.', 'xstore-core') . '<br/>' .
                sprintf(esc_html__('Note: Each page, post, and product has an individual option in the "%1s Options" section for setting the "Meta keywords" and many other SEO settings.', 'xstore-core'), $brand_label) . '<br/>' .
                sprintf(esc_html__(' Tip: The "%1s Options" can be found by going to the Dashboard > Posts (Pages/Products) > Edit Post (Page/Product) and scrolling down to the SEO tab.', 'xstore-core'), $brand_label),
            'section'         => 'general-seo',
            'default'         => '',
            'active_callback' => array(
                array(
                    'setting'  => 'et_seo_switcher',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        'et_seo_og-image'          => array(
            'name'        => 'et_seo_og-image',
            'type'        => 'image',
            'settings'    => 'et_seo_og-image',
            'label'       => esc_html__( 'Open Graph image', 'xstore-core' ),
            'tooltip' => esc_html__('Open Graph meta tags are pieces of code that control how URLs are displayed when shared on social media. Here you can upload the image for the social snippet. Note that this is probably the most important Open Graph tag because it takes up the most space in a social media feed.', 'xstore-core') . '<br/>' .
                sprintf(esc_html__('Note: Each page, post, and product has an individual option in the "%1s Options" section for setting the "Open Graph image" and many other SEO settings.', 'xstore-core'), $brand_label) . '<br/>' .
                sprintf(esc_html__(' Tip: The "%1s Options" can be found by going to the Dashboard > Posts (Pages/Products) > Edit Post (Page/Product) and scrolling down to the SEO tab.', 'xstore-core'), $brand_label),
            'section'     => 'general-seo',
            'default'     => '',
            'choices'     => array(
                'save_as' => 'array',
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'et_seo_switcher',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        'et_seo_noindex' => array(
            'name'        => 'et_seo_noindex',
            'type'        => 'toggle',
            'settings'    => 'et_seo_noindex',
            'label'       => esc_html__( '"Noindex" tag for url', 'xstore-core' ),
            'tooltip' => sprintf(esc_html__( 'If you want to add a "noindex" tag to the shop page meta when the shop page URL has additional parameters, such as %1s then enable this option.', 'xstore-core' ),
                '?min_price=120, ?page=2, or ?filter_pa_color=blue, etc.'). '<br/>' .
        esc_html__('Info: The \'noindex\' directive instructs search engines to exclude a page from their index, making it ineligible to appear in search results.', 'xstore-core'),
            'section'     => 'general-seo',
            'default'     => 0,
            'active_callback' => array(
                array(
                    'setting'  => 'et_seo_switcher',
                    'operator' => '!=',
                    'value'    => '0',
                ),
            ),
        ),

        'et_seo_nofollow_pagination' => array(
	        'name'        => 'et_seo_nofollow_pagination',
	        'type'        => 'toggle',
	        'settings'    => 'et_seo_nofollow_pagination',
	        'label'       => esc_html__( '"nofollow" tag for pagination url', 'xstore-core' ),
			'tooltip'     => esc_html__( 'If you want to add a "nofollow" tag to the shop or blog page pagination URL, then enable this option.', 'xstore-core' ),
	        'section'     => 'general-seo',
	        'default'     => 0,
	        'active_callback' => array(
		        array(
			        'setting'  => 'et_seo_switcher',
			        'operator' => '!=',
			        'value'    => '0',
		        ),
	        ),
        ),

    );

    return array_merge( $fields, $args );

});