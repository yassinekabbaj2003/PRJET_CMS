<?php  
	/**
	 * The template created for displaying footer panel
	 *
	 * @version 0.0.1
	 * @since 6.0.0
	 */

	add_filter( 'et/customizer/add/panels', function($panels) use($priorities){

		$args = array(
			'footer'	 => array(
				'id'          => 'footer',
				'title'       => esc_html__( 'Footer', 'xstore' ),
				'icon'		  => 'dashicons-arrow-down-alt',
				'priority' => $priorities['footer']
			)
		);

        $is_elementor = defined('ELEMENTOR_VERSION') && defined('ELEMENTOR_PRO_VERSION');

        $elementor_templates_count = 0;

        if ( $is_elementor ) {
            $created_templates = get_posts(
                [
                    'post_type' => 'elementor_library',
                    'post_status' => 'publish',
                    'posts_per_page' => '-1',
                    'tax_query' => [
                        [
                            'taxonomy' => 'elementor_library_type',
                            'field' => 'slug',
                            'terms' => 'footer',
                        ],
                    ],
                    'fields' => 'ids'
                ]
            );
            $elementor_templates_count = count($created_templates);
        }

        if ( $elementor_templates_count > 0 ) {
            $args['footer']['description'] = sprintf(esc_html__('It looks like you\'re using Elementor %sFooter templates%s [%s] on your site. Settings of Elementor builder have higher priority and settings from this section in the customizer are ignored.', 'xstore'), '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>', $elementor_templates_count);
        }

		return array_merge( $panels, $args );
	});