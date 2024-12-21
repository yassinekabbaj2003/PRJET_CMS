<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image-builder.php.
 * (in case you have Single Product builder option enabled)
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     7.8.0
 * @xstore-version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $etheme_global, $post_id, $product, $is_IE, $main_slider_on, $attachment_ids, $main_attachment_id;

$post_id = get_the_ID(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case

if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! isset( $etheme_global['quick_view'] ) && !apply_filters('etheme_elementor_edit_mode', false) ) {
	if ( ! empty( $_REQUEST['product_id'] ) ) {
		$post_id = (int) $_REQUEST['product_id']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited valid use case
		setup_postdata( $post_id );
		$main_attachment_id = get_post_thumbnail_id( $post_id );
		$product            = wc_get_product( $post_id );
		if ( ! empty( $_REQUEST['option'] ) ) {
			$option         = esc_attr( $_REQUEST['option'] );
			$attributes     = $product->get_attributes();
			$variations     = $product->get_available_variations();
			$images         = '';
			$thumb          = '';
			$attachment_ids = array();
			
			foreach ( $variations as $variation ) {
				if ( isset( $variation['attributes'][ 'attribute_' . $swatch ] ) && $variation['attributes'][ 'attribute_' . $swatch ] == $option && has_post_thumbnail( $variation['variation_id'] ) ) {
					$main_attachment_id = get_post_thumbnail_id( $variation['variation_id'] );
				}
			}
			
		}
	}
	
} else {
	$attachment_ids     = $product->get_gallery_image_ids();
	$main_attachment_id = get_post_thumbnail_id( $post_id );
}

$gallery_type = apply_filters('etheme_product_gallery_type', etheme_get_option( 'product_gallery_type_et-desktop', 'thumbnails_bottom' ));
$lightbox     = apply_filters('etheme_product_gallery_lightbox', etheme_get_option( 'product_gallery_lightbox_et-desktop', 0 ));
$space        = etheme_get_option( 'product_gallery_spacing_et-desktop', 10 );
$space        = apply_filters('etheme_product_gallery_spacing', $gallery_type == 'full_width' || !$space ? 0 : $space);

$has_video = false;

$gallery_slider  = $etheme_global['gallery_slider'] = ( ! in_array( $gallery_type, array( 'one_image', 'double_image' ) ) );
$vertical_slider = $etheme_global['vertical_slider'] = $gallery_type == 'thumbnails_left';
$show_thumbs     = apply_filters('etheme_product_thumbnails', ( in_array( $gallery_type, array(
	'thumbnails_bottom',
	'thumbnails_bottom_inside',
	'thumbnails_left'
) ) ) );
$thumbs_slider   = apply_filters('etheme_product_gallery_thumbnails_slider', etheme_get_option( 'product_gallery_thumbnails_et-desktop', 1 ));
$video_position = etheme_get_option('product_video_position', 'last');

if ( defined( 'DOING_AJAX' ) && DOING_AJAX && !apply_filters('etheme_elementor_edit_mode', false) ) {
	$gallery_slider = true;
}

$video_attachments = array();
$videos            = etheme_get_attach_video( $product->get_id() );
if ( isset( $videos[0] ) && $videos[0] != '' ) {
	$video_attachments = get_posts( array(
		'post_type' => 'attachment',
		'include'   => $videos[0]
	) );
}

$external_video = etheme_get_external_video( $product->get_id() );

if ( count( $video_attachments ) > 0 || $external_video != '' ) {
    $has_video = true;
}

$force_check = false;

if ( ! $attachment_ids && ! $has_video && ( apply_filters( 'etheme_single_product_variation_gallery', get_query_var( 'etheme_single_product_variation_gallery', false ) ) && $product->get_type() == 'variable' ) ) {
	$force_check    = true;
	$attachment_ids = array( 0 );
}

$attachment_ids = !is_array($attachment_ids) ? array() : $attachment_ids;
$main_slider_on = ( count( $attachment_ids ) > 0 || $has_video || $force_check );

$class = '';
if ( $main_slider_on ) {
	$class .= ' main-slider-on';
}

$class .= ( $gallery_slider ) ? ' gallery-slider-on' : ' gallery-slider-off';

if ( $is_IE ) {
	$class .= ' ie';
}

$et_zoom       = apply_filters('etheme_product_gallery_zoom', etheme_get_option( 'product_gallery_zoom_et-desktop', 1 ));
$et_zoom_class = 'woocommerce-product-gallery__image';

if ( $et_zoom ) {
	$class .= ' zoom-on';
}

if ( ! $gallery_slider ) {
	$wrapper_classes = array();
}

?>


<?php if ( (! defined( 'DOING_AJAX' ) || apply_filters('etheme_elementor_edit_mode', false)) && ! isset( $etheme_global['quick_view'] ) ):
	$wrap_class = $gallery_type;
	if ( $lightbox ) {
		$wrap_class .= ' with-pswp';
	}
	if ( $vertical_slider && $gallery_slider && $attachment_ids ) {
		$wrap_class .= ' swiper-vertical-images';
	}
	if ( ! $thumbs_slider ) {
		$wrap_class .= ' product-thumbnails-as-grid';
	}
	if ( apply_filters('etheme_product_gallery_arrows_always', false) )
	    $wrap_class .= ' arrows-hovered-static';
	?>
    <div class="swiper-entry swipers-couple-wrapper images images-wrapper woocommerce-product-gallery arrows-hovered mob-full-width <?php
echo esc_attr( $wrap_class ); ?>">
	<?php
   if ( apply_filters('etheme_product_gallery_sale_flash', true) )
        woocommerce_show_product_sale_flash();
    ?>
<?php endif;

$swiper_container = '';
$swiper_wrapper   = '';
if ( $gallery_slider && ( count( $attachment_ids ) > 0 || $has_video || $force_check ) ) {
	$swiper_container = 'swiper-container';
	$swiper_wrapper   = 'swiper-wrapper';
}

$gallery_slider_class = ( $gallery_slider ) ? 'swiper-slide' : '';

$slides = apply_filters('etheme_product_gallery_slides', array(
    'large'           => 1,
    'notebook'        => 1,
    'tablet_land'     => 1,
    'mobile' => 1,
));

$container_atts = array(
	'class="swiper-control-top ' . esc_attr( $swiper_container ) . ' ' . esc_attr( $class ) . '"',
	'data-effect="' . apply_filters( 'single_product_main_gallery_effect', 'slide' ) . '"',
	'data-xs-slides="'.$slides['mobile'].'" data-sm-slides="'.$slides['tablet_land'].'" data-lt-slides="'.$slides['large'].'"'
);
if ( apply_filters( 'single_product_main_gallery_autoheight', true ) ) {
	$container_atts[] = 'data-autoheight="true"';
}

if ( ! isset( $etheme_global['quick_view_gallery_grid'] ) ) {
	$container_atts[] = "data-space='" . $space . "'";
} else {
	$container_atts[] = "data-space='0'";
	$container_atts[] = "data-free-mode='true'";
}

if ( $gallery_type == 'full_width' ) {
	$container_atts[] = "data-loop='true'";
	$container_atts[] = "data-slides-per-view='1'";
}
elseif (apply_filters('etheme_product_gallery_loop', false)) {
    $container_atts[] = "data-loop='true'";
}

if ( $force_check ) {
	$container_atts[] = "data-default-empty='true'";
}

$slides_speed = apply_filters('single_product_main_gallery_speed', false);
if ( $slides_speed ) {
	$container_atts[] = "data-speed='".$slides_speed."'";
}

$is_yith_wcbm_frontend = class_exists('YITH_WCBM_Frontend');

?>
    <div <?php echo implode( ' ', $container_atts ); ?>>
        <div class="<?php echo esc_attr( $swiper_wrapper ); ?> main-images clearfix">
			
			<?php
			
			if ( $video_position == 'first') :
                if ( $external_video ) :
                    echo '<div class="' . esc_attr( $gallery_slider_class ) . ' images woocommerce-product-gallery">'.
                        $external_video .
                        '</div>';
                endif;
				
				if ( count( $video_attachments ) > 0 ): ?>
                <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
					<?php
                    $video_attributes = array('controls="controls"');
                    if ( get_post_meta( $post->ID, '_product_video_autoplay', true ) ) {
                        $video_attributes[] = 'autoplay="true"';
                        $video_attributes[] = 'muted="muted"';
                    }
                    ?>
                    <video <?php echo implode(' ', apply_filters('single_product_main_gallery_video_attributes', $video_attributes)); ?>>
						<?php foreach ( $video_attachments as $video ): ?>
							<?php $video_ogg = $video_mp4 = $video_webm = false; ?>
							<?php if ( $video->post_mime_type == 'video/mp4' && ! $video_mp4 ): $video_mp4 = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/webm' && ! $video_webm ): $video_webm = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/webm; codecs="vp8, vorbis"'>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/ogg' && ! $video_ogg ): $video_ogg = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/ogg; codecs="theora, vorbis"'>
								<?php esc_html_e( 'Video is not supporting by your browser', 'xstore' ); ?>
                                <a href="<?php echo esc_url( $video->guid ); ?>"><?php esc_html_e( 'Download Video', 'xstore' ); ?></a>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/videopress' && !$video_ogg && ! $video_mp4 && ! $video_webm ): ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
							<?php endif; ?>
						<?php endforeach; ?>
                    </video>
                </div>
			<?php endif;
			endif; ?>
			
			<?php if ( has_post_thumbnail( $post_id ) ) {
				
				$index             = 0;
				$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
				$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
				$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
				
				if (!$full_size_image){
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src( 'woocommerce_single' ), esc_html__( 'Placeholder', 'xstore' ) ), $post_id );
				} else {
					// **********************************************************************************************
					// ! Main product image
					// **********************************************************************************************
					
					$attributes = array(
						'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
						'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2]
					);

                    $image_link_attr = array(
                        'href' => esc_url( $full_size_image[0] ),
                        'data-index' => $index,
                        'class' => array("woocommerce-main-image", "pswp-main-image", "zoom")
                    );

                    $image_link_attr_ready = array_map(function ($key, $value) {
                        return $key.'="'.(is_array($value) ? implode(' ', $value) : $value) .'"';
                    }, array_keys($image_link_attr), array_values($image_link_attr));

                    do_action('etheme_before_single_product_image');

                    $html                  = '<a '.implode(' ', apply_filters('etheme_single_product_image_link_attributes_rendered', $image_link_attr_ready, $post_thumbnail_id)).'>';
					$html                 .= get_the_post_thumbnail( $post->ID, 'woocommerce_single', $attributes );
					$html                 .= '</a>';
					
					echo '<div class="' . $gallery_slider_class . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'woocommerce_thumbnail' ) . '" class="' . $et_zoom_class . '">';
					
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
					
					echo '</div></div>';

                    do_action('etheme_after_single_product_image');
					
					// **********************************************************************************************
					// ! Product slider
					// **********************************************************************************************
					if ( $main_slider_on ) {
						
						if ( count( $attachment_ids ) > 0 ) {
							foreach ( $attachment_ids as $key => $attachment_id ) {
								$index ++;
								$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
								if ( !$full_size_image) continue;

								$attributes = array(
									'title'                   => esc_attr( get_the_title( $attachment_id ) ),
									// Remove has_excerpt check after WPB will fix their error
									'data-caption'            => (has_excerpt($attachment_id)) ? esc_attr( get_the_excerpt( $attachment_id ) ) : '',
									//'data-caption'            => get_the_excerpt( $attachment_id ),
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

								$image_link_attr = array(
                                    'href' => esc_url( $full_size_image[0] ),
                                    'data-large' => esc_url( $full_size_image[0] ),
                                    'data-width' => esc_attr( $full_size_image[1] ),
                                    'data-height' => esc_attr( $full_size_image[2] ),
                                    'data-index' => $index,
                                    'itemprop' => "image",
                                    'class' => array("woocommerce-main-image", "zoom")
                                );

                                $image_link_attr_ready = array_map(function ($key, $value) {
								    return $key.'="'.(is_array($value) ? implode(' ', $value) : $value) .'"';
                                }, array_keys($image_link_attr), array_values($image_link_attr));

                                do_action('etheme_before_single_product_image');

								$html = '<a '.implode(' ', apply_filters('etheme_single_product_image_link_attributes_rendered', $image_link_attr_ready, $attachment_id)).'>';

								$html .= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), false, $attributes );

								$html .= '</a>';

								echo '<div class="' . esc_attr( $gallery_slider_class ) . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url( $attachment_id, 'woocommerce_thumbnail' ) . '" class="' . $et_zoom_class . '">';

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $attachment_id ) );

								echo '</div></div>';

                                do_action('etheme_after_single_product_image');
							}
						}
						
					}
					
				}
			} else {
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src( 'woocommerce_single' ), esc_html__( 'Placeholder', 'xstore' ) ), $post_id );
			}
			
			if ($is_yith_wcbm_frontend) {
				remove_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product_thumbnail' ), 99 );
			}
			
			if ( $video_position == 'last') :
                if ( $external_video ) :
                    echo '<div class="' . esc_attr( $gallery_slider_class ) . ' images woocommerce-product-gallery">'.
                        $external_video .
                        '</div>';
                endif;
				
				if ( count( $video_attachments ) > 0 ): ?>
                <div class="<?php echo esc_attr( $gallery_slider_class ); ?> images woocommerce-product-gallery">
					<?php
                    $video_attributes = array('controls="controls"');
                    if ( get_post_meta( $post->ID, '_product_video_autoplay', true ) ) {
                        $video_attributes[] = 'autoplay="true"';
                        $video_attributes[] = 'muted="muted"';
                    }
                    ?>
                    <video <?php echo implode(' ', apply_filters('single_product_main_gallery_video_attributes', $video_attributes)); ?>>
						<?php foreach ( $video_attachments as $video ): ?>
							<?php $video_ogg = $video_mp4 = $video_webm = false; ?>
							<?php if ( $video->post_mime_type == 'video/mp4' && ! $video_mp4 ): $video_mp4 = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/webm' && ! $video_webm ): $video_webm = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/webm; codecs="vp8, vorbis"'>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/ogg' && ! $video_ogg ): $video_ogg = true; ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/ogg; codecs="theora, vorbis"'>
								<?php esc_html_e( 'Video is not supporting by your browser', 'xstore' ); ?>
                                <a href="<?php echo esc_url( $video->guid ); ?>"><?php esc_html_e( 'Download Video', 'xstore' ); ?></a>
							<?php endif; ?>
							<?php if ( $video->post_mime_type == 'video/videopress' && !$video_ogg && ! $video_mp4 && ! $video_webm ): ?>
                                <source src="<?php echo esc_url( $video->guid ); ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
							<?php endif; ?>
						<?php endforeach; ?>
                    </video>
                </div>
			<?php endif;
			endif; ?>

        </div>
		<?php if ( apply_filters('etheme_product_gallery_pagination', false) && (count( $attachment_ids ) > 0 || $force_check) ) {
		 echo '<div class="swiper-pagination"></div>';
		} ?>
		
		<?php etheme_360_view_block(); ?>
		
		<?php
		if ( apply_filters('etheme_product_gallery_arrows', true) && $gallery_slider && ( count( $attachment_ids ) > 0 || $has_video || $force_check ) && ! isset( $etheme_global['quick_view_gallery_grid'] ) ) {
			$class = ( $force_check ) ? 'dt-hide mob-hide' : '';
			$arrows_type = apply_filters('etheme_product_gallery_arrows_type', 'arrow');
			if ( $arrows_type != 'arrow' )
                $class .= ' type-'.$arrows_type;
            $arrows_style = apply_filters('etheme_product_gallery_arrows_style', 'default');
            if ( $arrows_style != 'default' )
                $class .= ' '.$arrows_style;
            if ( apply_filters('etheme_elementor_theme_builder', false) )
                $class .= ' et-swiper-elementor-nav';
			echo '<div class="swiper-custom-left ' . $class . '"></div>
                  <div class="swiper-custom-right ' . $class . '"></div>
            ';
		} ?>

    </div>

    <div class="empty-space col-xs-b15 col-sm-b30"></div>
<?php
if ( $gallery_slider && $show_thumbs ) {
	global $post_id, $product, $woocommerce, $main_slider_on, $attachment_ids, $main_attachment_id;
	
	$zoom_plugin = etheme_is_zoom_activated();
	
	$thums_size = apply_filters( 'single_product_small_thumbnail_size', 'woocommerce_thumbnail' );
	
	$ul_class = '';
	if ( ! $vertical_slider ) {
		$ul_class = 'swiper-wrapper ';
	}
	
	$ul_class .= 'right thumbnails-list';
	
	if ( $zoom_plugin ) {
		$ul_class .= ' yith_magnifier_gallery';
	}
	
	if ( $vertical_slider && get_query_var('et_is-rtl', false) ) {
		$ul_class .= ' slick-rtl';
	}
	
	if ( $vertical_slider ) {
		$ul_class .= ' vertical-thumbnails';
	} else {
		$ul_class .= ' thumbnails';
	}
	
	if ( empty( $attachment_ids ) ) {
		$attachment_ids = $product->get_gallery_image_ids();
	}
	
	if ( $attachment_ids || $has_video || $force_check ) {
		$loop       = 0;
		$res_slides = etheme_get_option( 'product_thumbnails_columns_et-desktop', 4 ) ? etheme_get_option( 'product_thumbnails_columns_et-desktop', 4 ) : 4;
        $res_slides = apply_filters('etheme_product_gallery_thumb_slides', array(
            'large'           => $res_slides,
            'notebook'        => $res_slides,
            'tablet_land'     => $res_slides,
            'mobile' => 3,
        ));
		$columns    = apply_filters( 'woocommerce_product_thumbnails_columns', $res_slides['large'] );
		$ul_p_class = '';
		if ( ! $vertical_slider ) {
			$ul_p_class .= 'swiper-container swiper-control-bottom';
			if ( ! $thumbs_slider ) {
				$ul_p_class .= ' swiper-container-grid';
			}
		} else {
			$ul_p_class .= 'vertical-thumbnails-wrapper';
			if ( ! $thumbs_slider ) {
				$ul_class .= ' slick-vertical-slider-grid';
			}
		}
		
		$ul_p_class .= ' columns-' . $res_slides['large'];
		if ( count( $attachment_ids ) + 1 <= $res_slides['large'] ) {
			$ul_p_class .= ' no-arrows';
		}
		$ul_p_class .= ( ! $gallery_slider ) ? ' noslider' : ' slider';
		
		$ul_p_class .= ( $force_check && ! $vertical_slider ) ? ' dt-hide mob-hide' : '';
		
		$type_slider = ( $vertical_slider ) ? 'slick-slide' : 'swiper-slide';
		
		// force space 10 for thumbnails
		$space = apply_filters('etheme_product_gallery_thumb_spacing', 10);
		
		?>
        <div class="<?php echo esc_attr( $ul_p_class ); ?>" <?php if ( ! $vertical_slider ) : ?>data-breakpoints="1"
             data-xs-slides="<?php echo esc_attr( $res_slides['mobile'] ); ?>" data-sm-slides="<?php echo esc_attr( $res_slides['tablet_land'] ); ?>"
             data-md-slides="<?php echo esc_attr( $res_slides['tablet_land'] ); ?>"
             data-lt-slides="<?php echo esc_attr( $res_slides['large'] ); ?>"
             data-slides-per-view="<?php echo esc_attr( $res_slides['large'] ); ?>" data-clickedslide="1"
             data-space="<?php echo esc_attr( $space ); ?>" <?php endif; ?>>
			<?php etheme_loader(); ?>
            <ul
                    class="<?php echo esc_attr( $ul_class ); ?>"
				<?php if ( $vertical_slider ) { ?>
                    data-slick-slides-per-view="<?php echo esc_attr( $res_slides['large'] ); ?>"
				<?php } ?>>
				<?php
				
				if ( $video_position == 'first') :
                    if ( $external_video ): ?>
                        <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                            <a href="#product-video-popup" class="open-video-popup">
                                <span class="et-icon et-play-button"></span>
                                <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                            </a>
                        </li>
                    <?php endif; ?>
					
					<?php if ( count( $video_attachments ) > 0 ): ?>
                    <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                        <a href="#product-video-popup" class="open-video-popup">
                            <span class="et-icon et-play-button"></span>
                            <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                        </a>
                    </li>
				<?php endif;
				endif;
				
				if ( count( $attachment_ids ) > 0 || $has_video || $force_check ) {
					$classes     = array( 'zoom' );
					
					$image       = wp_get_attachment_image( $main_attachment_id, $thums_size );
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $main_attachment_id ) );
					
					
					$image_link = wp_get_attachment_image_src( $main_attachment_id, 'full' );
					
					list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $main_attachment_id, "woocommerce_single" );
//					list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $main_attachment_id, "shop_magnifier" );
					
					if ( $image_link && has_post_thumbnail( $post_id ) ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="' . $type_slider . ' thumbnail-item %s"><span class="pswp-additional pointer %s" title="%s" data-small="%s" data-large="%s" data-width="%s" data-height="%s">%s</span></li>', $image_class, $image_class, $image_title, $thumbnail_url, $image_link[0], $image_link[1], $image_link[2], $image ), $post_id, $post_id, $image_class );
					}
				}
				
				if ( ! $force_check ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$classes = array( 'zoom' );
						
						$image_link = wp_get_attachment_image_src( $attachment_id );
						
						if ( ! $image_link ) {
							continue;
						}
						
						$image       = wp_get_attachment_image( $attachment_id, $thums_size );
						$image_class = esc_attr( implode( ' ', $classes ) );
						$image_title = esc_attr( get_the_title( $attachment_id ) );
						$image_link  = wp_get_attachment_image_src( $attachment_id, 'full' );
						
						list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $attachment_id, "woocommerce_single" );
//						list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $attachment_id, "shop_magnifier" );
						
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li class="' . $type_slider . ' thumbnail-item %s"><span data-large="%s" data-width="%s" data-height="%s" class="pswp-additional pointer %s" title="%s" data-small="%s">%s</span></li>', $image_class, $image_link[0], $image_link[1], $image_link[2], $image_class, $image_title, $thumbnail_url, $image ), $attachment_id, $post_id, $image_class );
						$loop ++;
					}
				}
				
				if ( $video_position == 'last') :
                    if ( $external_video ):
                        echo '<li class="video-thumbnail thumbnail-item ' . esc_attr( $type_slider ) . '">
                            <a href="#product-video-popup" class="open-video-popup">
                                <span class="et-icon et-play-button"></span>
                                <p>' . esc_html__( 'video', 'xstore' ) . '</p>
                            </a>
                        </li>';
                    endif; ?>
					
					<?php if ( count( $video_attachments ) > 0 ): ?>
                    <li class="video-thumbnail thumbnail-item <?php echo esc_attr( $type_slider ); ?>">
                        <a href="#product-video-popup" class="open-video-popup">
                            <span class="et-icon et-play-button"></span>
                            <p><?php esc_html_e( 'video', 'xstore' ); ?></p>
                        </a>
                    </li>
				<?php endif;
				endif; ?>

            </ul>
			<?php if ( $vertical_slider && $thumbs_slider ) { ?>
                <div class="swiper-vertical-navig"></div> <?php }
			if ( apply_filters('etheme_product_gallery_thumb_arrows', true) && ( count( $attachment_ids ) > 0 || $has_video || $force_check ) && ! $vertical_slider && $thumbs_slider ) {
                $class = 'thumbnails-bottom';
			    $arrows_type = apply_filters('etheme_product_gallery_arrows_type', 'arrow');
                if ( $arrows_type != 'arrow' )
                    $class .= ' type-'.$arrows_type;
                $arrows_style = apply_filters('etheme_product_gallery_arrows_style', 'default');
                if ( $arrows_style != 'default' )
                    $class .= ' '.$arrows_style;
                if ( apply_filters('etheme_elementor_theme_builder', false) )
                    $class .= ' et-swiper-elementor-nav';
                echo '<div class="swiper-custom-left ' . $class . '"></div>
                  <div class="swiper-custom-right ' . $class . '"></div>';
			} ?>

        </div>
		<?php
	}
}

if  ($is_yith_wcbm_frontend){
	add_filter( 'woocommerce_single_product_image_thumbnail_html', array( YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product_thumbnail' ), 99 );
}

if ( (! defined( 'DOING_AJAX' ) || apply_filters('etheme_elementor_edit_mode', false)) && ! isset( $etheme_global['quick_view'] ) ): ?>
    </div>
<?php endif;

if ( apply_filters('etheme_should_reinit_swiper_script', false) ) : ?>
    <script>jQuery(document).ready(function(){
            etTheme.swiperFunc();
            etTheme.secondInitSwipers();
            if ( etTheme.sliderVertical !== undefined )
                etTheme.sliderVertical();
            etTheme.global_image_lazy();
            if ( etTheme.contentProdImages !== undefined )
                etTheme.contentProdImages();
            if ( window.hoverSlider !== undefined ) {
                window.hoverSlider.init({});
                window.hoverSlider.prepareMarkup();
            }
            if ( etTheme.countdown !== undefined )
                etTheme.countdown();
            etTheme.customCss();
            etTheme.customCssOne();
            if ( etTheme.reinitSwatches !== undefined )
                etTheme.reinitSwatches();
        });</script>
<?php endif;