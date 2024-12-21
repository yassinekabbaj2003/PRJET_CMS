<?php

namespace ETC\App\Models\Search;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Multilingual {

	public static $currentCurrency = '';
	public static $langs = null;

	/**
	 * Check if the website is multilingual
	 *
	 * @return bool
	 */
	public static function is_multilingual() {

		$is_multilingual = false;

		if ( defined( 'ET_AJAX_SEARCH_DISABLE_MULTILINGUAL' ) && ET_AJAX_SEARCH_DISABLE_MULTILINGUAL ) {
			return false;
		}

		if ( count( self::get_languages() ) > 0 && self::get_multilingual_provider() !== 'not set' ) {
			$is_multilingual = true;
		}

		return $is_multilingual;
	}

	/**
	 * Check if WPMl is active
	 *
	 * @return bool
	 */
	public static function is_WPML_activated() {
		return class_exists( 'SitePress' );
	}

	/**
	 * Check if Polylang is active
	 *
	 * @return bool
	 */
	public static function is_Polylang_activated() {
		return did_action( 'pll_init' );
	}

	/**
	 * Get Provider
	 *
	 * @return string
	 */
	public static function get_multilingual_provider() {
		$provider = 'not set';

		if ( self::is_WPML_activated() ) {
			$provider = 'WPML';
		}

		if ( self::is_Polylang_activated() ) {
			$provider = 'Polylang';
		}

		$provider = apply_filters( 'etheme_ajax_search_stats/multilingual/provider', $provider );

		return $provider;
	}

	/**
	 * Check if language code has one of the following format:
	 * aa, aaa, aa-aa
	 *
	 * @param $lang
	 *
	 * @return bool
	 */
	public static function check_language_code( $lang ) {
		return ! empty( $lang ) && is_string( $lang ) && (bool) preg_match( '/^([a-zA-Z]{2,15})$|^([a-zA-Z]{2,15}[-_][a-zA-Z]{2,15})$/', $lang );
	}

	/**
	 * Get default language
	 *
	 * @return string
	 */
	public static function get_default_language() {
		$defaultLang = 'en';

		if ( self::is_WPML_activated() ) {
			$defaultLang = apply_filters( 'wpml_default_language', NULL );
		}

		if ( self::is_Polylang_activated() ) {
			$defaultLang = pll_default_language( 'slug' );
		}

		if ( empty( $defaultLang ) ) {
			$locale      = get_locale();
			$defaultLang = substr( $locale, 0, 2 );
		}

		return apply_filters( 'etheme_ajax_search_stats/multilingual/default-language', $defaultLang );
	}

	/**
	 * Current language
	 *
	 * @return string
	 */
	public static function get_current_language() {
		$currentLang = self::get_default_language();

		if ( self::is_WPML_activated() ) {
			$currentLang = apply_filters( 'wpml_current_language', null );
		}

		if ( self::is_Polylang_activated() ) {
			$lang = pll_current_language( 'slug' );

			if ( $lang ) {
				$currentLang = $lang;
			} else {
				$currentLang = pll_default_language( 'slug' );
			}
		}

		if ( empty( $currentLang ) && ! empty( $_GET['lang'] ) && self::check_language_code( $_GET['lang'] ) ) {
			$currentLang = $_GET['lang'];
		}

		return apply_filters( 'etheme_ajax_search_stats/multilingual/current-language', $currentLang );
	}

	/**
	 * Get Language of post or product
	 *
	 * @param int $postID
	 *
	 * @return string
	 */
	public static function get_post_language( $postID, $postType = 'product' ) {
		$lang = self::get_default_language();

		if ( self::is_WPML_activated() ) {
			global $wpdb;

			$postType = 'post_' . $postType;

			$tranlationsTable = $wpdb->prefix . 'icl_translations';
			$sql              = $wpdb->prepare( "SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type=%s
                                          AND element_id=%d", sanitize_key( $postType ), $postID );
			$result           = $wpdb->get_var( $sql );

			if ( self::check_language_code( $result ) ) {
				$lang = $result;
			}
		}

		if ( self::is_Polylang_activated() ) {
			$lang = pll_get_post_language( $postID, 'slug' );
		}

		$lang = apply_filters( 'etheme_ajax_search_stats/multilingual/post-language', $lang, $postID, $postType );

		return $lang;
	}

	/**
	 * Get term lang
	 *
	 * @param int $term ID
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	public static function get_term_language( $termID, $taxonomy ) {
		$lang = self::get_default_language();

		if ( self::is_WPML_activated() ) {
			global $wpdb;

			$elementType      = 'tax_' . sanitize_key( $taxonomy );
			$tranlationsTable = $wpdb->prefix . 'icl_translations';

			$term = \WP_Term::get_instance( $termID, $taxonomy );
			if ( is_a( $term, 'WP_Term' ) ) {
				$sql = $wpdb->prepare( "SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type = %s
                                          AND element_id=%d",
					$elementType, $term->term_taxonomy_id );

				$result = $wpdb->get_var( $sql );

				if ( self::check_language_code( $result ) ) {
					$lang = $result;
				}
			}
		}

		if ( self::is_Polylang_activated() ) {
			$lang = pll_get_term_language( $termID, 'slug' );
		}

		// TranslatePress/qTranslate-XT has no language relationship with the post, so we always return the default
		$lang = apply_filters( 'etheme_ajax_search_stats/multilingual/term-language', $lang, $termID, $taxonomy );

		return $lang;
	}

	/**
	 * Get permalink
	 *
	 * @param string $postID
	 * @param string $url
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function get_permalink( $postID, $url = '', $lang = '' ) {
		$permalink = $url;

		if ( self::is_WPML_activated() && self::get_default_language() !== $lang ) {
			/**
			 *  1 if the option is *Different languages in directories*
			 *  2 if the option is *A different domain per language*
			 *  3 if the option is *Language name added as a parameter*.
			 */
			$urlType = apply_filters( 'wpml_setting', 0, 'language_negotiation_type' );

			if ( $urlType == 3 ) {
				$permalink = apply_filters( 'wpml_permalink', $url, $lang );
			} else {
				$permalink = apply_filters( 'wpml_permalink', $url, $lang, true );
			}

		}

		$permalink = apply_filters( 'etheme_ajax_search_stats/multilingual/post-permalink', $permalink, $lang, $postID );

		return $permalink;
	}

	/**
	 * Active languages
	 *
	 * @param bool $includeInvalid Also return invalid languages
	 *
	 * @return array
	 */
	public static function get_languages( $includeInvalid = false ) {
		$includeHidden = apply_filters( 'etheme_ajax_search_stats/multilingual/languages/include-hidden', false );

		if ( self::$langs !== null && ! $includeInvalid && ! $includeHidden ) {
			return self::$langs;
		}

		$langs = array();

		if ( self::is_WPML_activated() ) {
			$wpmlLangs = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );

			if ( is_array( $wpmlLangs ) ) {
				foreach ( $wpmlLangs as $langCode => $details ) {
					if ( self::check_language_code( $langCode ) || $includeInvalid ) {
						$langs[] = $langCode;
					}
				}
			}

			if ( ! $includeHidden ) {
				$hiddenLangs = apply_filters( 'wpml_setting', array(), 'hidden_languages' );
				if ( ! empty( $hiddenLangs ) && is_array( $hiddenLangs ) ) {
					foreach ( $hiddenLangs as $hiddenLang ) {
						if ( ! self::check_language_code( $hiddenLang ) && $includeInvalid ) {
							continue;
						}
						$langs = array_diff( $langs, [ $hiddenLang ] );
					}
				}
			}
		}

		if ( self::is_Polylang_activated() ) {
			$langs = pll_languages_list( array(
				'hide_empty' => false,
				'fields'     => ''
			) );

			// Filter not-active languages
			$langs = array_filter( $langs, function ( $lang ) {
				// By default, 'active' prop isn't available; It is set the first time the administrator deactivates the language
				if ( isset( $lang->active ) && ! $lang->active ) {
					return false;
				}

				return true;
			} );

			$langs = wp_list_pluck( $langs, 'slug' );
		}

		if ( empty( $langs ) ) {
			$langs[] = self::get_default_language();
		}

		$langs = apply_filters( 'etheme_ajax_search_stats/multilingual/languages', $langs, $includeInvalid, $includeHidden );

		if ( ! $includeInvalid && ! $includeHidden ) {
			self::$langs = $langs;
		}

		return $langs;
	}

	/**
	 * Get language details by language code
	 *
	 * @param string $lang
	 * @param string $field | name | locale |
	 *
	 * @return string
	 */
	public static function get_language_field( $lang, $field ) {
		$value = $lang;

		if ( self::is_WPML_activated() ) {
			global $sitepress;
			$details = $sitepress->get_language_details( $lang );

			if ( $field === 'name' && ! empty( $details['display_name'] ) ) {
				$value = $details['display_name'];
			}

			if ( $field === 'locale' && ! empty( $details['default_locale'] ) ) {
				$value = $details['default_locale'];
			}
		}

		if ( self::is_Polylang_activated() ) {
			$langs = pll_languages_list( array(
				'hide_empty' => false,
				'fields'     => ''
			) );

			if ( ! empty( $langs ) && is_array( $langs ) ) {
				foreach ( $langs as $object ) {
					if ( ! empty( $object->slug ) && $object->slug === $lang ) {

						if ( $field === 'name' ) {
							$value = $object->name;
						}

						if ( $field === 'locale' ) {
							$value = $object->locale;
						}
					}
				}
			}
		}

		return $value;
	}

	/**
	 * Get all terms in one taxonomy for all languages
	 *
	 * @param string $taxonomy
	 *
	 * @return array of WP_Term objects
	 */
	public static function get_terms_in_all_languages( $taxonomy ) {
		$terms = array();

		if ( self::is_WPML_activated() ) {
			$currentLang = self::get_current_language();
			$usedIds     = array();

			foreach ( self::get_languages() as $lang ) {
				do_action( 'wpml_switch_language', $lang );
				$args        = array(
					'taxonomy'         => $taxonomy,
					'hide_empty'       => true,
					'suppress_filters' => false
				);
				$termsInLang = get_terms( apply_filters( 'etheme_ajax_search_stats/search/' . $taxonomy . '/args', $args ) );

				if ( ! empty( $termsInLang ) && is_array( $termsInLang ) ) {
					foreach ( $termsInLang as $termInLang ) {
						if ( ! in_array( $termInLang->term_id, $usedIds ) ) {
							$terms[]   = $termInLang;
							$usedIds[] = $termInLang->term_id;
						}
					}
				}

			}

			do_action( 'wpml_switch_language', $currentLang );
		}

		if ( self::is_Polylang_activated() ) {

			$terms = get_terms( array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'lang'       => '', // query terms in all languages
			) );

		}

		$terms = apply_filters( 'etheme_ajax_search_stats/multilingual/terms-in-all-languages', $terms, $taxonomy );

		return $terms;
	}

	/**
	 * Get terms in specific language
	 *
	 * @param array $args
	 * @param string $lang
	 *
	 * @return \WP_Term[]
	 */
	public static function get_terms_in_language( $args = array(), $lang = '' ) {
		$terms = array();

		if ( empty( $lang ) ) {
			$lang = self::get_default_language();
		}

		if ( self::is_WPML_activated() ) {
			$currentLang = self::get_current_language();
			$usedIds     = array();

			do_action( 'wpml_switch_language', $lang );
			$args        = wp_parse_args( $args, array(
				'taxonomy'         => '',
				'hide_empty'       => true,
				'suppress_filters' => false
			) );
			$termsInLang = get_terms( apply_filters( 'etheme_ajax_search_stats/search/' . $args['taxonomy'] . '/args', $args ) );

			if ( ! empty( $termsInLang ) && is_array( $termsInLang ) ) {
				foreach ( $termsInLang as $termInLang ) {

					if ( ! in_array( $termInLang->term_id, $usedIds ) ) {
						$terms[]   = $termInLang;
						$usedIds[] = $termInLang->term_id;
					}
				}
			}

			do_action( 'wpml_switch_language', $currentLang );
		}

		if ( self::is_Polylang_activated() ) {
			$args = wp_parse_args( $args, array(
				'taxonomy'   => '',
				'hide_empty' => true,
				'lang'       => $lang,
			) );

			$terms = get_terms( $args );
		}

		$terms = apply_filters( 'etheme_ajax_search_stats/multilingual/terms-in-language', $terms, $args, $lang );

		return $terms;
	}

	public static function search_terms( $taxonomy, $query, $lang = '' ) {
		$terms = array();

		if ( empty( $lang ) ) {
			$lang = self::get_default_language();
		}

		$args  = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'search'     => $query,
		);
		$terms = get_terms( $args );
	}

	/**
	 * Get term in specific language
	 *
	 * @param int $termID
	 * @param string $taxonomy
	 * @param string $lang
	 *
	 * @return object WP_Term
	 */
	public static function get_term( $termID, $taxonomy, $lang ) {
		$term = null;

		if ( self::is_WPML_activated() ) {
			$currentLang = self::get_current_language();
			do_action( 'wpml_switch_language', $lang );

			$term = get_term( $termID, $taxonomy );

			do_action( 'wpml_switch_language', $currentLang );
		}

		if ( self::is_Polylang_activated() ) {

			$termID = pll_get_term( $termID, $lang );

			if ( $termID ) {
				$term = get_term( $termID, $taxonomy );
			}
		}

		$term = apply_filters( 'etheme_ajax_search_stats/multilingual/term', $term, $termID, $taxonomy, $lang );

		return $term;
	}

	/**
	 * Check if multicurrency module is enabled
	 *
	 * @return bool
	 */
	public static function is_multi_currency_activated() {

		$multiCurrency = false;

		if ( self::is_WPML_activated() && function_exists( 'wcml_is_multi_currency_on' ) && wcml_is_multi_currency_on() ) {
			$multiCurrency = true;
		}


		return $multiCurrency;
	}

	/**
	 * Get currency code assigned to language
	 *
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function get_currency_for_language( $lang ) {
		$currencyCode = '';

		if ( self::is_WPML_activated() ) {
			global $woocommerce_wpml;
			if ( ! empty( $woocommerce_wpml ) && is_object( $woocommerce_wpml ) && ! empty( $lang ) ) {

				if ( ! empty( $woocommerce_wpml->settings['default_currencies'][ $lang ] ) ) {
					$currencyCode = $woocommerce_wpml->settings['default_currencies'][ $lang ];
				}
			}

		}

		return $currencyCode;
	}

	/**
	 * Set currenct currency
	 *
	 * @return void
	 */
	public static function setCurrentCurrency( $currency ) {
		self::$currentCurrency = $currency;
	}

	/**
	 * Get currenct currency
	 *
	 * @return string
	 */
	public static function getCurrentCurrency() {
		return self::$currentCurrency;
	}

	/**
	 * Switch language
	 *
	 * @param $lang
	 */
	public static function switchLanguage( $lang ) {
		if ( self::is_WPML_activated() && ! empty( $lang ) ) {
			do_action( 'wpml_switch_language', $lang );
		}

		/**
		 * Some plugins (e.g. Permalink Manager for WooCommerce) use the get_the_terms() function,
		 * which caches terms related to the product, and we need to clear this cache when changing the language.
		 */
		if ( function_exists( 'wp_cache_flush_group' ) ) {
			wp_cache_flush_group( 'product_cat_relationships' );
		} else {
			wp_cache_flush();
		}

		do_action( 'etheme_ajax_search_stats/multilingual/switch-language', $lang );
	}

}
