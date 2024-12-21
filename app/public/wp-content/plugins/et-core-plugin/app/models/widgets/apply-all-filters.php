<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Apply_All_Filters Widget.
 *
 * @version    1.0.0
 * @since      8.0.7
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Apply_All_Filters extends Widgets {

	function __construct() {
		$widget_ops = array(
			'classname' => 'etheme_apply_all_filters hidden',
			'description' => esc_html__( 'Apply filters button', 'xstore-core')
		);
		parent::__construct('etheme-apply-all-filters', '8theme - &nbsp;&#160;&nbsp;'.esc_html__('Apply All Filters', 'xstore-core'), $widget_ops);
	}

	function widget($args, $instance) {

		wp_register_script( 'et_apply_filters', get_template_directory_uri() . '/js/modules/apply-filters.min.js',array('etheme'), '1.0.0', true );
		wp_enqueue_script( 'et_apply_filters' );

		wp_localize_script( 'et_apply_filters', 'EthemeApplyFilters', array('is_loaded' => true) );

		if (parent::admin_widget_preview(esc_html__('Apply All Filters', 'xstore-core')) !== false) return;

		if ( xstore_notice() ) return;

		if (!is_null($args)) {
			extract($args);
		}
		$text = isset($instance['text']) ? $instance['text'] : '';
        $button_stretched = ( !empty($instance['button_stretched'] ) ) ? $instance['button_stretched'] : '';

		echo (isset($before_widget)) ? $before_widget : '';

		echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		$obj_id = get_queried_object_id();
		$url = get_term_link( $obj_id );

		if ( is_wp_error($url)){
			$url = get_permalink( wc_get_page_id( 'shop' ) );
		}

		if (!$url){
			$url = get_home_url();
		}

		echo '<a class="etheme-all-filter btn btn-black button'.($button_stretched ? ' full-width' : '').'" href="' . $url . '" style="height: auto;"><span>' . $text . '</span></a>';

		echo (isset($after_widget)) ? $after_widget : '';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['text']    = strip_tags( $new_instance['text'] );
        $instance['button_stretched'] = isset( $new_instance['button_stretched'] ) ? (bool) $new_instance['button_stretched'] : false;

		if ( function_exists ( 'icl_register_string' ) ){
			icl_register_string( 'Widgets', 'ETheme_Apply_All_Filters - title', $instance['title'] );
			icl_register_string( 'Widgets', 'ETheme_Clear_All_Filters - text', $instance['text'] );
		}
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$text = isset( $instance['text'] ) ? $instance['text'] : esc_html__('Apply Filters', 'xstore-core');
        $button_full_width = isset( $instance['button_stretched'] ) ? (bool) $instance['button_stretched'] : false;
		parent::widget_input_text( esc_html__( 'Widget title:', 'xstore-core' ), $this->get_field_id( 'title' ),$this->get_field_name( 'title' ), $title );
		parent::widget_input_text( esc_html__( 'Button text:', 'xstore-core' ), $this->get_field_id( 'text' ),$this->get_field_name( 'text' ), $text );
        parent::widget_input_checkbox( esc_html__( 'Full-width button', 'xstore-core' ), $this->get_field_id( 'button_stretched' ), $this->get_field_name( 'button_stretched' ), checked( $button_full_width, true, false ), 1 );
	}
}
