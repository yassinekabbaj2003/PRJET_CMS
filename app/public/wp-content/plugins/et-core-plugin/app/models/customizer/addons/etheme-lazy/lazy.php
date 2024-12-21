<?php
/**
 * Create customizer lazy section.
 *
 * @since      3.2.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Etheme_Lazy_Section extends WP_Customize_Section {
	const TYPE = 'kirki-lazy';

	/**
	 * Type of control, used by JS.
	 *
	 * @access public
	 * @var string
	 */
	public $type = self::TYPE;

	public $ajax_call;

	public $dependency;

	/**
	 * Render the panel's JS templates.
	 *
	 * This function is only run for panel types that have been registered with
	 * WP_Customize_Manager::register_panel_type().
	 *
	 * @see WP_Customize_Manager::register_panel_type()
	 */
	public function print_template() {
		?>
		<script type="text/html" id="tmpl-et-lazy-section-loader">
			<li class="lazy-section-loading">
				<div class="loader">{{ data.loading }}</div>
			</li>

		</script>
		<?php
		parent::print_template();
	}

	/**
	 * Export data to JS.
	 *
	 * @return array
	 */
	public function json() {
		$data               = parent::json();
		$data['ajax_call']  = $this->ajax_call;
		$data['dependency'] = $this->dependency;

		return $data;
	}
}

add_filter( 'kirki_section_types', 'etheme_customizer_lazy_section' ); 
function etheme_customizer_lazy_section( $section_types ) {
	$section_types['kirki-lazy'] = 'Etheme_Lazy_Section';
	return $section_types;
}