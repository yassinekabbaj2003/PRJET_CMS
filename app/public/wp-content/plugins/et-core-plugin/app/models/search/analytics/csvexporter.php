<?php

namespace ETC\App\Models\Search\Analytics;

use WC_CSV_Exporter;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CSVExporter extends WC_CSV_Exporter {

	private $context = '';
	private $lang = '';

	public function set_context( $context = '' ) {
		$this->context = $context;
	}

	public function set_lang( $lang = '' ) {
		$this->lang = $lang;
	}

	public function prepare_data_to_export() {
		$data = new Data();
		if ( ! empty( $this->lang ) ) {
			$data->set_language( $this->lang );
		}

		$dateSuffix = date( 'Ymd-His', time() );

		if ( in_array( $this->context, array( 'autocomplete', 'search-results-page' ) ) ) {
			$data->set_context( $this->context );
			$this->set_filename( 'etheme_ajax_search-analytics_' . $this->context . '_' . ( empty( $this->lang ) ? '' : $this->lang . '_' ) . $dateSuffix );
		} else {
			$this->set_filename( 'etheme_ajax_search-analytics_critical' . '_' . ( empty( $this->lang ) ? '' : $this->lang . '_' ) . $dateSuffix );
		}

		$this->set_column_names(
			[
				"phrase" => "Phrase",
				"qty"    => "Repetitions",
			]
		);

		if ( empty( $this->context ) ) {
			$this->row_data = $data->get_critical_searches( PHP_INT_MAX );
		} else {
			$this->row_data = $data->get_phrases_with_results( PHP_INT_MAX );
		}
	}
}
