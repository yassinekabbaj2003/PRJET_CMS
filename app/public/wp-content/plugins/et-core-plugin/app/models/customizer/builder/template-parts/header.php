<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying header builter of Wordpress customizer
 *
 * @since   1.0.0
 * @version 1.0.2
 * @log
 * 1.0.1
 * Added panel resizer
 * 1.0.2
 * Added count condition
 */

$Etheme_Customize_Builder = new Etheme_Customize_header_Builder();

$ajax_local     = false;
$ajax_local     = apply_filters( 'Etheme_Customize_Builder_ajax', $ajax_local );
$elements = $Etheme_Customize_Builder->elements;
$conditions = $Etheme_Customize_Builder->get_json_option('et_multiple_headers');
$mtips_class = 'mtips-left';


if (isset($this->post['et_multiple']) && $this->post['et_multiple']){
    $headers = get_option('et_multiple_headers', false);
    $headers = json_decode($headers, true);


    $ajax_local = array();
    $ajax_local['header_top_elements'] = '';


    $ajax_local = $this->decode_special_options($headers[$this->post['et_multiple']]['options']);

}

?>

<div class="et_customizer-builder et_customizer-builder-header hidden <?php echo (count( $conditions )) ? 'et_multiple-exist' : ''; echo ($Etheme_Customize_Builder->et_multiple)? ' current-multiple': ''; ?>">
    <span class="et_button et_collapse-builder" data-panel="header">
        <span class="dashicons dashicons-arrow-down-alt2"></span>
    </span>
	<div class="et_panel-resizer"><span class="et_panel-resizer-inner"></span></div>
	<div class="et_header-head align-center flex valign-center equal-columns">
		<div class="flex-left align-left nowrap">
            <span class="et_button desktop active" data-device="desktop">
                <span class="dashicons dashicons-desktop"></span>
                <span><?php esc_html_e( 'Desktop', 'xstore-core' ); ?></span>
            </span>
			<span class="et_button mobile" data-device="mobile">
                <span class="dashicons dashicons-smartphone"></span>
                <span><?php esc_html_e( 'Mobile', 'xstore-core' ); ?></span>
            </span>
            <?php if(! $Etheme_Customize_Builder->et_multiple): ?>
                <span class="et_button et_call-multiple-headers">
                    <span class="dashicons dashicons-rest-api"></span>
                    <span><?php esc_html_e( 'Multiple Headers', 'xstore-core' ); ?>
                        (<span class="multiple-count"><?php echo (count( $conditions )) ? count( $conditions ) : 0 ?></span>)
                    </span>
                </span>
            <?php endif; ?>
		</div>

        <?php if( $Etheme_Customize_Builder->et_multiple): ?>
            <div data-name="<?php esc_html_e('Multiple Header builder', 'xstore-core'); ?>" data-mobile-name="<?php echo esc_attr__('Header builder (mobile)', 'xstore-core'); ?>"></div>
        <?php else: ?>
            <div data-name="<?php esc_html_e('Header builder', 'xstore-core'); ?>" data-mobile-name="<?php echo esc_attr__('Header builder (mobile)', 'xstore-core'); ?>"></div>
        <?php endif;?>


        <?php if ($Etheme_Customize_Builder->et_multiple): ?>
            <?php //var_dump($Etheme_Customize_Builder->condition_path('header', $Etheme_Customize_Builder->et_multiple, '->')); ?>
            <div class="multiple-header-name" data-name="" data-name-prefix="<?php echo sprintf(esc_attr__('You are editing multiple header for %s', 'xstore-core'),  $Etheme_Customize_Builder->condition_path('header', $Etheme_Customize_Builder->et_multiple, '->')); ?>"></div>
        <?php elseif (count( $conditions )): ?>
            <div class="multiple-header-name" data-name="" data-name-prefix="<?php echo esc_attr__('You are editing Default header -> All Site', 'xstore-core'); ?>"></div>
        <?php endif; ?>

<!--        current-->
<!--		<div class="multiple-header-name" data-default-path="Entire Site"  data-default-name="Default header" data-name="Default header -> Entire Site" data-name-prefix="You are editing "><span class="tip">(IF YOU WANT TO EDIT ANOTHER HEADER SELECT IT IN MULTIPLE HEADERS, PLEASE)</span></div>-->

		<div class="flex-right align-right nowrap">
			<?php do_action( 'etheme-builder-before-header-right-buttons' ); ?>
			<span class="et_button et_edit" data-parent="header_presets" data-section="header_presets_content_separator">
                <span class="dashicons dashicons-schedule"></span>
                <span><?php esc_html_e( 'Templates', 'xstore-core' ); ?></span>
            </span>
			<span class="et_button et_edit" data-parent="headers_sticky" data-section="header_sticky_content_separator">
                <span class="dashicons dashicons-paperclip"></span>
                <span><?php esc_html_e('Header Sticky', 'xstore-core'); ?></span>
            </span>
		</div>
	</div>
	<div class="et_device et_desktop-mod">
		<?php

		$connect_block_package = '{"element-oCMF7":{"title":"Section1","width":"100","index":1,"align":"start","sticky":"false","data":{"element-lpYyv":{"element":"etheme_woocommerce_template_woocommerce_breadcrumb","index":0}}},"element-raHwF":{"title":"Section2","width":"30","index":2,"align":"start","sticky":"false","data":{"sA6vX":{"element":"etheme_woocommerce_show_product_images","index":0}}},"element-TFML4":{"title":"Section3","width":"35","index":3,"align":"start","sticky":"false","data":{"su2ri":{"element":"etheme_woocommerce_template_single_title","index":0},"pcrn2":{"element":"etheme_woocommerce_template_single_price","index":1},"ZhZAb":{"element":"etheme_woocommerce_template_single_rating","index":2},"DBsjn":{"element":"etheme_woocommerce_template_single_excerpt","index":3},"oXjuP":{"element":"etheme_woocommerce_template_single_add_to_cart","index":4},"element-Zwwrj":{"element":"etheme_product_single_wishlist","index":5},"4XneW":{"element":"etheme_woocommerce_template_single_meta","index":6},"WP7Ne":{"element":"etheme_woocommerce_template_single_sharing","index":7}}},"element-fgcNP":{"title":"Section4","width":"25","index":4,"align":"start","sticky":"element-TFML4","data":{"HK48p":{"element":"etheme_product_single_widget_area_01","index":0}}},"element-nnrkj":{"title":"Section5","width":"100","index":5,"align":"start","sticky":"false","data":{"BJZsk":{"element":"etheme_woocommerce_output_product_data_tabs","index":0}}},"element-aKxrL":{"title":"Section6","width":"100","index":6,"align":"start","sticky":"false","data":{"qyJz2":{"element":"etheme_woocommerce_output_related_products","index":0}}},"element-a8Rd9":{"title":"Section7","width":"100","index":7,"align":"start","sticky":"false","data":{"sbu5J":{"element":"etheme_woocommerce_output_upsell_products","index":0}}}}';
		$blocks = ( $ajax_local ) ? json_decode($ajax_local['connect_block_package'], true) : get_theme_mod( 'connect_block_package', $connect_block_package );
		if ( $blocks && $blocks != '[]' && is_array($blocks) && count( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['data'] ) ) {
					$inside = json_decode( $block['data'] );
					if ( !is_null( $inside ) ){
						foreach ( $inside as $key => $value ) {
							unset( $elements[$key] );
						}
                    }
				}
			}
		}
		?>

		<div class="et_header-part et_header-top" data-name="Top Header" data-section="header_top_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode($ajax_local['header_top_elements'], true) : json_decode( get_theme_mod( 'header_top_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'    => $value['index'],
						'offset'   => $value['offset'],
						'size'     => $value['size'],
						'element'  => $value['element'],
						'id'       => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__('Dropdown settings', 'xstore-core').'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html( $args, 'column' );

					if ( $_i == $count) {
						for ( $i = 1; $i <= 12 - $value['index']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ($i=1; $i < $value['size']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ($i=0; $i < 12; $i++) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="top_header" data-section="top_header_style_separator">
            <span class="mt-mes"><?php esc_html_e( 'Top header settings', 'xstore-core' ); ?></span>
        </span>

		<div class="et_header-part et_header-main" data-name="Main Header" data-section="header_main_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode($ajax_local['header_main_elements'], true) : json_decode( get_theme_mod( 'header_main_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'    => $value['index'],
						'offset'   => $value['offset'],
						'size'     => $value['size'],
						'element'  => $value['element'],
						'id'       => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html( $args, 'column' );

					if ( $_i == $count) {
						for ($i = 1; $i <= 12 - $value['index']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ($i=1; $i < $value['size']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ($i=0; $i < 12; $i++) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="main_header" data-section="main_header_style_separator">
            <span class="mt-mes"><?php esc_html_e( 'Main header settings', 'xstore-core' ); ?></span>
        </span>

		<div class="et_header-part et_header-bottom" data-name="Bottom Header" data-section="header_bottom_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode($ajax_local['header_bottom_elements'], true) : json_decode( get_theme_mod( 'header_bottom_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'    => $value['index'],
						'offset'   => $value['offset'],
						'size'     => $value['size'],
						'element'  => $value['element'],
						'id'       => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html($args, 'column');

					if ( $_i == $count) {
						for ($i = 1; $i <= 12 - $value['index']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ($i=1; $i < $value['size']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ( $i=0; $i < 12; $i++ ) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="bottom_header" data-section="bottom_header_style_separator">
            <span class="mt-mes"><?php esc_html_e( 'Bottom header settings', 'xstore-core' ); ?></span>
        </span>
		<div class="et_header-part et_elements">
			<div class="et_column et_col-sm-12 align-center">
				<div class="et_column-inner">
					<?php foreach ( $elements as $key => $value ): ?>
						<?php if ( ! in_array( 'header', $value['location'] ) ) continue; ?>
						<div class="et_customizer-element <?php echo $value['class']; ?> ui-state-default" data-id="element-<?php echo $Etheme_Customize_Builder->generate_random( 5 ); ?>" data-size="1" data-element="<?php echo $key; ?>">
                            <span class="et_name">
                                <span class="dashicons <?php echo $value['icon']; ?>"></span>
                                <?php echo $value['title']; ?>
                            </span>
							<span class="et_actions">
                                <?php if ( isset($value['element_info']) ) { ?>
	                                <span class="dashicons dashicons-lightbulb mtips mtips-lg">
                                        <span class="mt-mes"><?php echo $value['element_info']; ?></span>
                                    </span>
                                <?php } ?>
                                <span class="dashicons dashicons-admin-generic et_edit mtips" data-parent="<?php echo esc_attr( $value['parent'] ); ?>" data-section="<?php echo $value['section']; ?>"><span class="mt-mes"><?php esc_html_e( 'Settings', 'xstore-core' ); ?></span></span>
                                <?php if ( isset( $value['section2'] ) ) { ?>
	                                <span class="dashicons dashicons-networking et_edit mtips" data-parent="<?php echo esc_attr( $value['parent'] ); ?>" data-section="<?php echo $value['section2']; ?>">
                                        <span class="mt-mes"><?php echo esc_html__( 'Dropdown settings', 'xstore-core' ); ?></span>
                                    </span>
                                <?php } ?>
                                <span class="dashicons dashicons-trash et_remove mtips"><span class="mt-mes"><?php esc_html_e( 'Remove', 'xstore-core' ); ?></span></span>
                            </span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>

	<div class="et_device et_mobile-mod hidden">
		<?php
		$elements = $Etheme_Customize_Builder->elements;
		// remove some elements on mobile header
		unset($elements['all_departments']);
		unset($elements['main_menu']);
		unset($elements['secondary_menu']);
		unset($elements['newsletter']);
		unset($elements['contacts']);
		$blocks   = ( $ajax_local ) ? json_decode($ajax_local['connect_block_mobile_package'], true) : get_theme_mod( 'connect_block_mobile_package' );

		if ( $blocks && count( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['data'] ) ) {
					$inside = json_decode( $block['data'] );
					foreach ( $inside as $key => $value ) {
						unset( $elements[$key] );
					}
				}
			}
		}
		?>


		<div class="et_header-part et_header-top" data-name="Top Header" data-section="header_mobile_top_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode( $ajax_local['header_mobile_top_elements'], true ) : json_decode( get_theme_mod( 'header_mobile_top_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'    => $value['index'],
						'offset'   => $value['offset'],
						'size'     => $value['size'],
						'element'  => $value['element'],
						'id'       => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html( $args, 'column' );

					if ( $_i == $count) {
						for ($i = 1; $i <= 12 - $value['index']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ($i=1; $i < $value['size']; $i++) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ($i=0; $i < 12; $i++) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="top_header" data-section="top_header_style_separator">
            <span class="mt-mes"><?php esc_html_e( 'Top header settings', 'xstore-core' ); ?></span>
        </span>
		<div class="et_header-part et_header-main" data-name="Main Header" data-section="header_mobile_main_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode( $ajax_local['header_mobile_main_elements'], true ) : json_decode( get_theme_mod( 'header_mobile_main_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'    => $value['index'],
						'offset'   => $value['offset'],
						'size'     => $value['size'],
						'element'  => $value['element'],
						'id'       => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html( $args, 'column' );

					if ( $_i == $count) {
						for ( $i = 1; $i <= 12 - $value['index']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ( $i=1; $i < $value['size']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ( $i=0; $i < 12; $i++ ) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="main_header" data-section="main_header_style_separator">
            <span class="mt-mes"><?php esc_html_e('Main header settings', 'xstore-core'); ?></span>
        </span>

		<div class="et_header-part et_header-bottom" data-name="Bottom Header" data-section="header_mobile_bottom_elements">
			<?php
			$data      = ( $ajax_local ) ? json_decode( $ajax_local['header_mobile_bottom_elements'], true ) : json_decode( get_theme_mod( 'header_mobile_bottom_elements', '' ), true );
			$cols_html = '';

			if ( $data ) {
				if ( ! is_array( $data ) ) {
					$data = array();
				}

				uasort( $data, function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				});

				$_i = 0;

				$count = count( $data );

				foreach ( $data as $key => $value ) {
					$_i++;

					if ( $value['offset'] ) {
						for ( $i=0; $i < $value['offset']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index'] - $value['offset']  + $i ) . '"></div>';
						}
					}

					$element = $elements[$value['element']];

					if ( $value['element'] != 'connect_block') {
						unset( $elements[$value['element']] );
					}

					$args = array(
						'index'   => $value['index'],
						'offset'  => $value['offset'],
						'size'    => $value['size'],
						'element' => $value['element'],
						'id'      => $key,
						'icon'     => ( isset($element['icon']) ) ? $element['icon'] : '',
						'title'    => $element['title'],
						'parent'   => $element['parent'],
						'section'  => $element['section'],
						'section2' => ( isset( $element['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-parent="'.$element['parent'].'" data-section="'.$element['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$cols_html .= $Etheme_Customize_Builder->generate_html( $args, 'column' );

					if ( $_i == $count ) {
						for ( $i = 1; $i <= 12 - $value['index']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					} else {
						for ( $i=1; $i < $value['size']; $i++ ) {
							$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $value['index']+$i ) . '"></div>';
						}
					}
				}
			} else {
				for ( $i=0; $i < 12; $i++ ) {
					$cols_html .= '<div class="et_sortable et_column et_col-sm-1" data-index="' . ( $i+1 ) . '"></div>';
				}
			}
			echo $cols_html;
			?>
		</div>
		<span class="et_header-settings et_edit dashicons dashicons-admin-generic mtips <?php echo esc_attr($mtips_class); ?>" data-parent="bottom_header" data-section="bottom_header_style_separator">
            <span class="mt-mes"><?php esc_html_e( 'Bottom header settings', 'xstore-core' ); ?></span>
        </span>

		<div class="et_header-part et_elements">
			<div class="et_column et_col-sm-12 align-center">
				<div class="et_column-inner">
					<?php foreach ( $elements as $key => $value ): ?>
						<?php if ( ! in_array( 'header', $value['location'] ) ) continue; ?>
						<div class="et_customizer-element <?php echo $value['class']; ?> ui-state-default" data-id="element-<?php echo $Etheme_Customize_Builder->generate_random( 5 ); ?>" data-size="1" data-element="<?php echo $key; ?>">
                            <span class="et_name">
                                <span class="dashicons <?php echo $value['icon']; ?>"></span>
                                <?php echo $value['title']; ?>
                            </span>
							<span class="et_actions">
                                <?php if ( isset($value['element_info']) ) { ?>
	                                <span class="dashicons dashicons-lightbulb mtips mtips-lg">
                                        <span class="mt-mes"><?php echo $value['element_info']; ?></span>
                                    </span>
                                <?php } ?>
                                <span class="dashicons dashicons-admin-generic et_edit mtips" data-parent="<?php echo esc_attr( $value['parent'] ); ?>" data-section="<?php echo $value['section']; ?>"><span class="mt-mes"><?php esc_html_e( 'Settings', 'xstore-core' ); ?></span></span>
                                <?php if ( isset( $value['section2'] ) ) { ?>
	                                <span class="dashicons dashicons-networking et_edit mtips" data-parent="<?php echo esc_attr( $value['parent'] ); ?>" data-section="<?php echo $value['section2']; ?>">
                                        <span class="mt-mes"><?php echo esc_html__( 'Dropdown settings', 'xstore-core' ); ?></span>
                                    </span>
                                <?php } ?>
                                <span class="dashicons dashicons-trash et_remove mtips"><span class="mt-mes"><?php esc_html_e( 'Remove', 'xstore-core' ); ?></span></span>
                            </span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<span class="hidden et_options-data et_header-options-data">
        <?php echo json_encode( require( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/options-data.php' ) ); ?>
    </span>
</div>