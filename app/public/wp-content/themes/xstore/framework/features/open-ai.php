<?php
/**
 * Built-in AI base functionality
 *
 * @package    open-ai.php
 * @since      9.1
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

class XStore_AI {

    public static $instance = null;

    public static $enabled = false;

    public function __construct() {}

    public function init() {

    	if (!is_admin()) return;

        self::$enabled = true;
        $activated = get_theme_mod('open_ai', '');

        $lang_list = $this->lang_code_list();

        add_filter('etheme_custom_metaboxes_tabs', function ($tabs) use ($activated, $lang_list) {
            $brand_label = apply_filters('etheme_theme_label', 'XStore');
            $options = array(
                array(
	                'name' => sprintf(
	                	esc_html__( 'To work with the %1$s AI assistant, please navigate to the  %2$s control panel %3$s and activate it using an API key.', 'xstore' ),
                        $brand_label,
		                '<a href="' . admin_url( 'admin.php?page=et-panel-open-ai' ) . '" target="_blank">',
		                '</a>'
	                ),
	                'id' => ETHEME_PREFIX . 'ai_information',
                    'type' => 'title',
                ),
            );
            if ( $activated ) {

                $content_types = array(
                    'content'    => esc_html__( 'Content', 'xstore' ),
                    'excerpt'    => esc_html__( 'Excerpt', 'xstore' ),
                    'outline'    => esc_html__( 'Outline', 'xstore' ),
                    'meta_title' => esc_html__( 'Meta Title', 'xstore' ),
                    'meta_desc'  => esc_html__( 'Meta Description', 'xstore' ),
                    'meta_key'   => esc_html__( 'Meta Keywords', 'xstore' ),
	                'custom'     => esc_html__( 'Custom', 'xstore' ),
                );
                $prompt_text_default = esc_html__( 'You can improve the AI generated answer with this additional prompt.', 'xstore' );
                $prompt_texts = array();
                foreach ($content_types as $content_type_key => $content_type_string) {
                    $prompt_texts[$content_type_key] = $prompt_text_default;
                    switch ($content_type_key) {
                        case 'content':
                            $prompt_texts[$content_type_key] = esc_html__('Write at least 5 paragraphs', 'xstore');
                            break;
                        case 'excerpt':
                            $prompt_texts[$content_type_key] = esc_html__('Excerpt must be between 55 and 75 characters.', 'xstore');
                            break;
                        case 'meta_title':
                            $prompt_texts[$content_type_key] = esc_html__('Title must be between 40 and 60 characters.', 'xstore');
                            break;
                        case 'meta_desc':
                            $prompt_texts[$content_type_key] = esc_html__('Description must be between 105 and 140 characters.', 'xstore');
                            break;
                        case 'meta_key':
                            $prompt_texts[$content_type_key] = esc_html__('Write at least 10 words.', 'xstore');
                            break;
                    }
                }
                $options = array(
	                array(
		                'name' => esc_html__( 'AI model', 'xstore' ),
		                'id' => ETHEME_PREFIX . 'ai_model_type',
		                'description' => sprintf(esc_html__('Choose the preferred AI model for enhanced customization and functionality within the %s theme.', 'xstore'), $brand_label),
		                'type' => 'select',
		                'options'  => array(
//			                'text-davinci-003' => esc_html__( 'Davinci 003 (deprecated)', 'xstore' ),
			                'gpt-4-turbo' => esc_html__( 'GPT-4 turbo', 'xstore'),
			                'gpt-4-1106-preview'    => esc_html__( 'GPT-4 turbo(1106-preview)', 'xstore'),
			                'gpt-4'    => esc_html__( 'GPT-4', 'xstore'),
			                'gpt-3.5-turbo'    => esc_html__( 'GPT-3.5 turbo', 'xstore'),
			                'ada' => esc_html__( 'Ada', 'xstore'),
			                'curie' => esc_html__( 'Curie', 'xstore'),
			                'babbage' => esc_html__( 'Babbage', 'xstore'),
		                ),
		                'save_field' => false
	                ),
                    array(
                        'name' => esc_html__( 'Content Type', 'xstore' ),
                        'id' => ETHEME_PREFIX . 'ai_content_type',
                        'description' => esc_html__('Simply choose the type of content you want to create, and let the AI do the heavy lifting for you.', 'xstore'),
                        'type' => 'select',
                        'options'  => $content_types,
                        'save_field' => false
                    ),
                    array(
                        'name' => esc_html__( 'Writing Style', 'xstore' ),
                        'id' => ETHEME_PREFIX . 'ai_write_style',
                        'desc' => esc_html__('The AI can analyze your existing content and learn your brand\'s unique style.', 'xstore'),
                        'type' => 'select',
                        'options'  => array(
                            ''              => esc_html__( 'Normal', 'xstore' ),
                            'persuasive'    => esc_html__( 'Persuasive', 'xstore'),
                            'informative'   => esc_html__( 'Informative', 'xstore'),
                            'descriptive'   => esc_html__( 'Descriptive', 'xstore'),
                            'creative'      => esc_html__( 'Creative', 'xstore'),
                            'narrative'     => esc_html__( 'Narrative', 'xstore'),
                            'argumentative' => esc_html__( 'Argumentative', 'xstore'),
                            'analytical'    => esc_html__( 'Analytical', 'xstore'),
                            'evaluative'    => esc_html__( 'Evaluative', 'xstore'),
                        ),
                        'save_field' => false
                    ),
	                array(
		                'name' => esc_html__( 'Writing Language', 'xstore' ),
		                'id' => ETHEME_PREFIX . 'ai_write_lang',
		                'desc' => esc_html__('Choose your preferred language, and let AI-powered content generator do the rest.', 'xstore'),
		                'type' => 'select',
		                'options'  => $lang_list,
		                'save_field' => false
	                ),
                    array(
                        'name'       => esc_html__( 'Prompt', 'xstore'),
                        'description'        => esc_html__('Simply enter few keywords or topic, and let the power of OpenAI technology generate a list of relevant prompts and ideas for you to choose from.', 'xstore'),
                        'attributes' => array(
                            'data-texts' => json_encode($prompt_texts)
                        ),
                        'id' => ETHEME_PREFIX . 'ai_prompt',
                        'type' => 'textarea',
                        'save_field' => false
                    ),
                    array(
                        'name' => esc_html__('Action', 'xstore'),
                        'id' => ETHEME_PREFIX . 'ai_',
                        'et_button_text' => esc_html__('Generate', 'xstore'),
                        'attributes' => array(
                            'data-texts' => json_encode($prompt_texts)
                        ),
                        'type' => 'et_ai_button',
                        'save_field' => false
                    ),
                    array(
                        'name'       => esc_html__( 'Result Message', 'xstore'),
                        'description'        => esc_html__( 'Here is the result of your request', 'xstore'),
                        'id' => ETHEME_PREFIX . 'ai_answer',
                        'type' => 'et_ai_result',
                        'save_field' => false
                    ),
                );
            }
            $tabs['et_open_ai'] = array(
                'id' => 'et_open_ai',
                'title' => sprintf(esc_html__('%1s AI Assistant', 'xstore'), $brand_label),
                'fields' => $options
            );
            return $tabs;
        });

    }

	/**
	 * Returns the instance.
	 *
	 * @return array
	 * @since  9.1.0
	 * @version 1.0.0
	 */
	public function lang_code_list(){
		return array(
			''  => 'Default',
			'aa' => 'Afar',
			'ab' => 'Abkhazian',
			'af' => 'Afrikaans',
			'ak' => 'Akan',
			'sq' => 'Albanian',
			'am' => 'Amharic',
			'ar' => 'Arabic',
			'an' => 'Aragonese',
			'hy' => 'Armenian',
			'as' => 'Assamese',
			'av' => 'Avaric',
			'ae' => 'Avestan',
			'ay' => 'Aymara',
			'az' => 'Azerbaijani',
			'ba' => 'Bashkir',
			'bm' => 'Bambara',
			'eu' => 'Basque',
			'be' => 'Belarusian',
			'bn' => 'Bengali',
			'bh' => 'Bihari',
			'bi' => 'Bislama',
			'bs' => 'Bosnian',
			'br' => 'Breton',
			'bg' => 'Bulgarian',
			'my' => 'Burmese',
			'ca' => 'Catalan; Valencian',
			'ch' => 'Chamorro',
			'ce' => 'Chechen',
			'zh' => 'Chinese',
			'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
			'cv' => 'Chuvash',
			'kw' => 'Cornish',
			'co' => 'Corsican',
			'cr' => 'Cree',
			'cs' => 'Czech',
			'da' => 'Danish',
			'dv' => 'Divehi; Dhivehi; Maldivian',
			'nl' => 'Dutch; Flemish',
			'dz' => 'Dzongkha',
			'en' => 'English',
			'eo' => 'Esperanto',
			'et' => 'Estonian',
			'ee' => 'Ewe',
			'fo' => 'Faroese',
			'fj' => 'Fijjian',
			'fi' => 'Finnish',
			'fr' => 'French',
			'fy' => 'Western Frisian',
			'ff' => 'Fulah',
			'ka' => 'Georgian',
			'de' => 'German',
			'gd' => 'Gaelic; Scottish Gaelic',
			'ga' => 'Irish',
			'gl' => 'Galician',
			'gv' => 'Manx',
			'el' => 'Greek, Modern',
			'gn' => 'Guarani',
			'gu' => 'Gujarati',
			'ht' => 'Haitian; Haitian Creole',
			'ha' => 'Hausa',
			'he' => 'Hebrew',
			'hz' => 'Herero',
			'hi' => 'Hindi',
			'ho' => 'Hiri Motu',
			'hu' => 'Hungarian',
			'ig' => 'Igbo',
			'is' => 'Icelandic',
			'io' => 'Ido',
			'ii' => 'Sichuan Yi',
			'iu' => 'Inuktitut',
			'ie' => 'Interlingue',
			'ia' => 'Interlingua (International Auxiliary Language Association)',
			'id' => 'Indonesian',
			'ik' => 'Inupiaq',
			'it' => 'Italian',
			'jv' => 'Javanese',
			'ja' => 'Japanese',
			'kl' => 'Kalaallisut; Greenlandic',
			'kn' => 'Kannada',
			'ks' => 'Kashmiri',
			'kr' => 'Kanuri',
			'kk' => 'Kazakh',
			'km' => 'Central Khmer',
			'ki' => 'Kikuyu; Gikuyu',
			'rw' => 'Kinyarwanda',
			'ky' => 'Kirghiz; Kyrgyz',
			'kv' => 'Komi',
			'kg' => 'Kongo',
			'ko' => 'Korean',
			'kj' => 'Kuanyama; Kwanyama',
			'ku' => 'Kurdish',
			'lo' => 'Lao',
			'la' => 'Latin',
			'lv' => 'Latvian',
			'li' => 'Limburgan; Limburger; Limburgish',
			'ln' => 'Lingala',
			'lt' => 'Lithuanian',
			'lb' => 'Luxembourgish; Letzeburgesch',
			'lu' => 'Luba-Katanga',
			'lg' => 'Ganda',
			'mk' => 'Macedonian',
			'mh' => 'Marshallese',
			'ml' => 'Malayalam',
			'mi' => 'Maori',
			'mr' => 'Marathi',
			'ms' => 'Malay',
			'mg' => 'Malagasy',
			'mt' => 'Maltese',
			'mo' => 'Moldavian',
			'mn' => 'Mongolian',
			'na' => 'Nauru',
			'nv' => 'Navajo; Navaho',
			'nr' => 'Ndebele, South; South Ndebele',
			'nd' => 'Ndebele, North; North Ndebele',
			'ng' => 'Ndonga',
			'ne' => 'Nepali',
			'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian',
			'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
			'no' => 'Norwegian',
			'ny' => 'Chichewa; Chewa; Nyanja',
			'oc' => 'Occitan, Provençal',
			'oj' => 'Ojibwa',
			'or' => 'Oriya',
			'om' => 'Oromo',
			'os' => 'Ossetian; Ossetic',
			'pa' => 'Panjabi; Punjabi',
			'fa' => 'Persian',
			'pi' => 'Pali',
			'pl' => 'Polish',
			'pt' => 'Portuguese',
			'ps' => 'Pushto',
			'qu' => 'Quechua',
			'rm' => 'Romansh',
			'ro' => 'Romanian',
			'rn' => 'Rundi',
			'ru' => 'Russian',
			'sg' => 'Sango',
			'sa' => 'Sanskrit',
			'sr' => 'Serbian',
			'hr' => 'Croatian',
			'si' => 'Sinhala; Sinhalese',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'se' => 'Northern Sami',
			'sm' => 'Samoan',
			'sn' => 'Shona',
			'sd' => 'Sindhi',
			'so' => 'Somali',
			'st' => 'Sotho, Southern',
			'es' => 'Spanish; Castilian',
			'sc' => 'Sardinian',
			'ss' => 'Swati',
			'su' => 'Sundanese',
			'sw' => 'Swahili',
			'sv' => 'Swedish',
			'ty' => 'Tahitian',
			'ta' => 'Tamil',
			'tt' => 'Tatar',
			'te' => 'Telugu',
			'tg' => 'Tajik',
			'tl' => 'Tagalog',
			'th' => 'Thai',
			'bo' => 'Tibetan',
			'ti' => 'Tigrinya',
			'to' => 'Tonga (Tonga Islands)',
			'tn' => 'Tswana',
			'ts' => 'Tsonga',
			'tk' => 'Turkmen',
			'tr' => 'Turkish',
			'tw' => 'Twi',
			'ug' => 'Uighur; Uyghur',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			've' => 'Venda',
			'vi' => 'Vietnamese',
			'vo' => 'Volapük',
			'cy' => 'Welsh',
			'wa' => 'Walloon',
			'wo' => 'Wolof',
			'xh' => 'Xhosa',
			'yi' => 'Yiddish',
			'yo' => 'Yoruba',
			'za' => 'Zhuang; Chuang',
			'zu' => 'Zulu',
		);
	}

    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.0.3
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}
$seo = new XStore_AI();
$seo->init();