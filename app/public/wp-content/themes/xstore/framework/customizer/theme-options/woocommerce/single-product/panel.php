<?php
/**
 * The template created for displaying single product page panel
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/panels', function ( $panels ) {

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
                        'terms' => 'product',
                    ],
                ],
                'fields' => 'ids'
            ]
        );
        $elementor_templates_count = count($created_templates);
    }
	
	$args = array(
		'single-product-page' => array(
			'id'    => 'single-product-page',
			'title' => esc_html__( 'Single Product Page', 'xstore' ),
			'icon'  => 'dashicons-align-left',
			'panel' => 'woocommerce'
		)
	);

    if ( $elementor_templates_count > 0 ) {
        $args['single-product-page']['description'] = sprintf(esc_html__('It looks like you\'re using Elementor Single Product templates [%s] on your site. This might cause issues with your customizer Single Product. To avoid problems and make sure everything runs smoothly, we suggest %schoosing%s one Single Product Builder option and disabling the others.', 'xstore'), $elementor_templates_count, '<a href="'.admin_url( 'admin.php?page=et-panel-theme-builders' ).'" target="_blank">', '</a>');
    }
	
	return array_merge( $panels, $args );
} );