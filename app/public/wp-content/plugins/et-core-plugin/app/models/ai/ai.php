<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Etheme AI class
 *
 * @since 5.1
 * @version 1.2
 * 1.2
 * ADDED: AI model select
 */
class Etheme_AI {
    /**
     * Main settings array
     *
     * @since 5.1
     * @version 1.0
     */
    private $settings = array(
        'token' => '',
        'url' => 'https://api.openai.com/v1/completions',
        'request_type' => 'meta_title',
        'post_type' => 'post',
        'api_model' => 'gpt-3.5-turbo',
        'content' => '',
        'style' => 'persuasive',
        'lang' => '',
    );

    /**
     * Main models array
     *
     * @since 5.1
     * @version 1.0
     */
    private $models = array(
        'meta_title' => array(
            'max_tokens' => 64,
            'temperature' => 0.6,
            'prompt' => 'Please write a SEO friendly meta title for the %1$s "%2$s". %3$s %4$s %5$s',
            'details' => 'The title must be between 40 and 60 characters.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
        'meta_key'=> array(
            'max_tokens' => 265,
            'temperature' => 0.6,
            'prompt' => 'Please write a SEO friendly meta keywords for the %1$s "%2$s". %3$s %4$s %5$s',
            'details' => 'Write at least 10 words.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
        'meta_desc'=> array(
            'max_tokens' => 265,
            'temperature' => 0.3,
            'prompt' => 'Please write a SEO friendly meta description for the %1$s "%2$s". %3$s %4$s %5$s',
            'details' => 'The description must be between 105 and 140 characters.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
        'content'=> array(
            'max_tokens' => 2048,
            'temperature' => 0.9,
            'prompt' => 'Please write a %1$s description about the "%2$s". %3$s %4$s %5$s',
            'details' => 'Write at least 5 paragraphs.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
        'excerpt'=> array(
            'max_tokens' => 64,
            'temperature' => 0.1,
            'prompt' => 'Please write a %1$s short excerpt about the "%2$s". %3$s %4$s %5$s',
            'details' => 'The excerpt must be between 55 and 75 characters.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
        'outline'=> array(
            'max_tokens' => 2048,
            'temperature' => 0.9,
            'prompt' => 'Please write a %1$s outline about the "%2$s". %3$s %4$s %5$s',
            'details' => 'Outline type is a alphanumeric outline.',
            'stop_sequences' => '',
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1.0,
            'show_probabilities' => '',
        ),
	    'custom' => array(
		    'max_tokens' => 2048,
		    'temperature' => 0.9,
		    'prompt' => '%1$s %2$s %3$s %4$s %5$s',
		    'details' => '',
		    'stop_sequences' => '',
		    'top_p' => 1.0,
		    'frequency_penalty' => 0.0,
		    'presence_penalty' => 0.0,
		    'best_of' => 1.0,
		    'show_probabilities' => '',
	    ),
    );

    /**
     * Construct just leave it empty
     *
     * @since 5.1
     * @version 1.0
     */
    public function __construct(){
    }

    /**
     * Init
     *
     * @since 5.1
     * @version 1.0
     */
    public function init(){
        $this->settings['token'] = get_theme_mod('open_ai', '');
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'wp_ajax_et_ajax_ai', array( $this, 'et_ajax_ai' ) );
        add_action( 'wp_ajax_nopriv_et_ajax_ai', array( $this, 'et_ajax_ai' ) );
        add_action( 'wp_ajax_et_ajax_ai_save_options', array( $this, 'et_ajax_ai_save_options' ) );
        add_action( 'wp_ajax_et_ajax_ai_load_template', array( $this, 'et_ajax_ai_load_template' ) );
    }

    /**
     * Enqueue Script
     *
     * @since 5.1
     * @version 1.0
     */
    public function admin_scripts(){
        wp_register_script(
            'etheme_ai-admin-js',
            plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
            array('jquery'),
            false,
            false
        );
    }

    /**
     * Prepare post data for request
     *
     * @since 5.1
     * @version 1.1
     */
    public function prepare_post_data(){
        if (isset($_POST['action']) && $_POST['action'] == 'et_ajax_ai'){
            if ( isset($_POST['data']) && !empty($_POST['data']) ){
                if (
                	isset($_POST['data']['type'])
	                && ! empty($_POST['data']['type'])
                ){
                    $this->settings['request_type'] = $_POST['data']['type'];
                }
                if (
                	isset($_POST['data']['content'])
	                && ! empty($_POST['data']['content'])
                ){
                    $this->settings['content'] = stripslashes($_POST['data']['content']);
                }

                if (
                	isset($_POST['data']['model'])
                    && ! empty($_POST['data']['model'])
                ){
	                $this->settings['api_model'] = $_POST['data']['model'];

	                if (in_array($this->settings['api_model'], array('gpt-4', 'gpt-3.5-turbo', 'gpt-4-1106-preview', 'gpt-4-turbo'))){
		                $this->settings['url'] = 'https://api.openai.com/v1/chat/completions';
	                }
                }

                if ($this->settings['request_type'] !='custom'){
	                if (
		                isset($_POST['data']['post_type'])
		                && ! empty($_POST['data']['post_type'])
	                ){
		                $this->settings['post_type'] = stripslashes($_POST['data']['post_type']);
	                }

	                if (
		                isset($_POST['data']['style']) && ! empty($_POST['data']['style'])
	                ){
		                $this->settings['style'] = $_POST['data']['style'];
	                }

	                $this->settings['style'] = 'Writing Style: ' . $this->settings['style'] . '.';

	                if (
		                isset($_POST['data']['lang'])
		                && ! empty($_POST['data']['lang']) && $_POST['data']['lang'] != $this->settings['lang']
	                ){
		                $this->settings['lang'] = 'Write in: ' . $this->format_code_lang($_POST['data']['lang']) . ' language';
	                }
                } else {
	                $this->settings['style'] = '';
	                $this->settings['post_type'] = '';
                }

            } else {
                $this->return_error();
            }
        }
    }

    /**
     * Form data and do AI request
     *
     * @since 5.1
     * @version 1.0
     */
    public function et_ajax_ai(){
        if ($this->settings['token']){
            $this->prepare_post_data();
            $this->return_success( nl2br( $this->rewrite_data()) );
        }
        $this->return_error('You use wrong OpenAI API Key');
    }

    /**
     * Save options for IA modules
     *
     * @since 5.1
     * @version 1.0
     */
    public function et_ajax_ai_save_options(){
	    check_ajax_referer('etheme_ai-settings', 'security');

        if (
	        current_user_can( 'manage_options' )
            && isset($_POST['data'])
            && isset($_POST['data']['model'])
            && in_array($_POST['data']['model'], array_keys($this->models) )
        ) {
            $data = $_POST['data'];
            $options = $options_default = get_option('et_ai_models_settings', array());
            $options[$data['model']] = $this->prepare_options_array($data);

            if (
            	update_option('et_ai_models_settings', $options, false)
	            || $options_default == $options
            ){
                $this->return_success(array('msg' => esc_html__('Options saved.', 'xstore-core') ));
            }
            $this->return_error(esc_html__('Error found, Can not save the options.', 'xstore-core'));
        }
        $this->return_error( esc_html__('Error found, Unregistered model detected.', 'xstore-core') );
    }

    /**
     * Prepare options array
     *
     * @since 5.1
     * @version 1.0
     */
    public function prepare_options_array($data){
        $array = array();
        foreach ($data as $kay => $value){
            if (in_array($kay, array('temperature', 'max_tokens', 'stop_sequences', 'top_p', 'frequency_penalty', 'presence_penalty', 'best_of', 'show_probabilities'))){
                $array[$kay] = $value;
            }
        }
        return $array;
    }

    /**
     * Prepare options array
     *
     * @since 5.1
     * @version 1.0
     */
    public function et_ajax_ai_load_template(){
        if (isset($_POST['template']) && $_POST['template'] == 'settings_popup'){
            $this->return_success(array('html' => $this->load_template('popup')));
        }
        $this->return_error(esc_html__('Error found, Can not load requested template.', 'xstore-core'));
    }

    public function load_template($template){
        if (!$template || empty($template) || ! file_exists(ET_CORE_DIR . 'app/models/ai/template-parts/'.$template.'.php')){
            $this->return_error(esc_html__('Error found, Can not load requested template.', 'xstore-core'));
        }
        ob_start();
            require_once(ET_CORE_DIR . 'app/models/ai/template-parts/'.$template.'.php');
        return ob_get_clean();
    }


    public function is_settings_available(){
        return isset($_POST['model']) && !empty($_POST['model']) && in_array($_POST['model'], array_keys($this->models) );
    }

    public function get_model_settings($model){
        $options = $this->models[$model];
        $settings = get_option('et_ai_models_settings', array());

        if (isset($settings[$model])){
            foreach ($settings[$model] as $kay => $value){
            	if (
            		in_array(
            			$kay,
			            array(
				            'max_tokens',
							'temperature',
							'top_p',
							'frequency_penalty',
							'presence_penalty',
							'best_of'
			            )
		            )
	            ){
		            $options[$kay] = floatval($value);
	            } else {
		            $options[$kay] = $value;
	            }
            }
        }
        return $options;
    }

    public function get_model_defaults($model){
    	return $this->models[$model];
    }


    /**
     * Rewrite AI response
     *
     * @since 5.1
     * @version 1.1
     */
    private function rewrite_data(){
        $response = $this->send_request();
        if ($response) {
            if (
                isset($response['choices'])
                && isset($response['choices'][0])
                && isset($response['choices'][0]['text'])
                && ! empty($response['choices'][0]['text'])
            ) {
                return $response['choices'][0]['text'];
            } elseif (
	            in_array($this->settings['api_model'], array('gpt-4', 'gpt-3.5-turbo', 'gpt-4-1106-preview', 'gpt-4-turbo'))
	            && isset($response['choices'])
	            && isset($response['choices'][0])
	            && isset($response['choices'][0]['message']['content'])
	            && ! empty($response['choices'][0]['message']['content'])
            ){
	            return $response['choices'][0]['message']['content'];
            } elseif (
            	isset($response['error'])
	            && isset($response['error']['message'])
            ){
	            $this->return_error($response['error']['message']);
            }
        }
        $this->return_error('Assistant is unable to provide an answer at this time. Please try again later or contact support team for assistance.');
    }

    /**
     * Form and return - success ajax response
     *
     * @since 5.1
     * @version 1.0
     */
    public function return_success($data = ''){
        wp_send_json(
            array(
                'status' => 'success',
                'data' => $data
            )
        );
    }

    /**
     * Form and return - error ajax response
     *
     * @since 5.1
     * @version 1.0
     */
    public function return_error($msg = ''){
        $msg = empty($msg) ? esc_html__('Error found, please contact our support team for assistance.', 'xstore-core') : $msg;
        wp_send_json(
            array(
                'status' => 'error',
                'msg' => $msg
            )
        );
    }

    /**
     * Send request to AI
     *
     * @since 5.1
     * @version 1.2
     */
    private function send_request(){
        if ( ! in_array($this->settings['request_type'], array_keys($this->models) ) ) {
            $this->return_error('Error found, the "' . $this->settings['request_type'] . '" is unregistered.');
        } else {
           $model = $this->models[$this->settings['request_type']];

           if ($model){
	           $model = $this->get_model_settings($this->settings['request_type']);
           }
        }

        $model['prompt'] = sprintf(
            $model['prompt'],
            $this->settings['post_type'],
            $this->settings['content'],
            $model['details'],
            $this->settings['style'],
            $this->settings['lang']
        );

        if ($this->settings['lang'] && $this->settings['request_type'] = 'content'){
        	$delimiter = apply_filters('etheme_ai_assistance_lang_content_delimiter', 2);
	        $model['max_tokens'] = ceil($model['max_tokens']/$delimiter);
        }

        $arr = array(
            "model" => $this->settings['api_model'],
            "prompt"=> $model['prompt'],
            "temperature"=> $model['temperature'],
            "max_tokens"=> $model['max_tokens'],
            "top_p"=> $model['top_p'],
            "frequency_penalty"=> $model['frequency_penalty'],
            "presence_penalty"=> $model['presence_penalty'],
        );

        if (in_array($this->settings['api_model'], array('gpt-4', 'gpt-3.5-turbo', 'gpt-4-1106-preview', 'gpt-4-turbo'))){
        	unset($arr['prompt']);
	        $arr['messages'] = array( array( "role" => "user", "content" => $model['prompt'] ) );
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);

        curl_setopt($ch, CURLOPT_URL, $this->settings['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->settings['token'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * Copy of the WP original format_code_lang function
     *
     * @since 5.1
     * @version 1.0
     */
    public function format_code_lang( $code = '' ) {
        $code       = strtolower( substr( $code, 0, 2 ) );
        $lang_codes = array(
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

        /**
         * Filters the language codes.
         *
         * @since MU (3.0.0)
         *
         * @param string[] $lang_codes Array of key/value pairs of language codes where key is the short version.
         * @param string   $code       A two-letter designation of the language.
         */
        $lang_codes = apply_filters( 'lang_codes', $lang_codes, $code );
        return strtr( $code, $lang_codes );
    }
}

$Etheme_AI = new Etheme_AI();
$Etheme_AI->init();