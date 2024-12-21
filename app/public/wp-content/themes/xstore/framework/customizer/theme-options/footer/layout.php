<?php
/**
 * The template created for displaying footer layout options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'footer-layout' => array(
			'name'        => 'footer-layout',
			'title'       => esc_html__( 'Footer layout', 'xstore' ),
			'description' => esc_html__( 'Remember that you can create a footer using static blocks.', 'xstore' ) . ' <a href="https://www.youtube.com/watch?v=gY-x4m47Duo" rel="nofollow" target="_blank">' . esc_html__( 'Watch the tutorial', 'xstore' ) . '</a>.',
			'panel'       => 'footer',
			'icon'        => 'dashicons-schedule',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/footer-layout' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'footer_columns' => array(
			'name'        => 'footer_columns',
			'type'        => 'select',
			'settings'    => 'footer_columns',
			'label'       => esc_html__( 'Columns', 'xstore' ),
			'tooltip' => sprintf(esc_html__( 'This controls the number of columns in the footer area. You can add footer content by going to Appearance > %1s. You can use static block widget in the footer to create a custom layout.', 'xstore' ),
                '<a href="'.admin_url('widgets.php').'" target="_blank">'.esc_html__('Widgets', 'xstore').'</a>'),
			'section'     => 'footer-layout',
			'default'     => 4,
			'choices'     => array(
				1 => esc_html__( '1 Column', 'xstore' ),
				2 => esc_html__( '2 Columns', 'xstore' ),
				3 => esc_html__( '3 Columns', 'xstore' ),
				4 => esc_html__( '4 Columns', 'xstore' ),
			),
		),
		
		'footer_demo' => array(
			'name'        => 'footer_demo',
			'type'        => 'toggle',
			'settings'    => 'footer_demo',
			'label'       => esc_html__( 'Demo blocks', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to display the default demo content in the footer area.', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 1,
		),
		
		'footer_fixed' => array(
			'name'        => 'footer_fixed',
			'type'        => 'toggle',
			'settings'    => 'footer_fixed',
			'label'       => esc_html__( 'Sliding effect', 'xstore' ),
			'tooltip' => esc_html__( 'Turn on the sliding effect for the footer so that it appears under the content when scrolling.', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 0,
		),
		
		'footer_widgets_open_close' => array(
			'name'        => 'footer_widgets_open_close',
			'type'        => 'toggle',
			'settings'    => 'footer_widgets_open_close',
			'label'       => esc_html__( 'Widgets toggle on mobile', 'xstore' ),
			'tooltip' => esc_html__( 'Enable this option to add the open/close toggles for widgets in the footer area on mobile devices.', 'xstore' ) . '<br/>' .
            esc_html__('Note: If you created your footer content using a static block, it may not work correctly.', 'xstore'),
			'section'     => 'footer-layout',
			'default'     => 1,
		),
		
		'footer_widgets_open_close_type' => array(
			'name'            => 'footer_widgets_open_close_type',
			'type'            => 'select',
			'settings'        => 'footer_widgets_open_close_type',
			'label'           => esc_html__( 'Widgets toggle action on mobile', 'xstore' ),
			'tooltip'     => esc_html__( 'Choose the default type for widgets\' content on mobile devices.', 'xstore' ),
			'section'         => 'footer-layout',
			'default'         => 'closed_mobile',
			'choices'         => array(
				'open_mobile'   => esc_html__( 'Open always', 'xstore' ),
				// 'closed' => esc_html__( 'Collapsed always', 'xstore' ),
				'closed_mobile' => esc_html__( 'Collapsed', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_widgets_open_close',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );