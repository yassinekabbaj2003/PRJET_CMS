<?php

/**
 *
 * @package     XStore theme
 * @author      8theme
 * @version     1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Etheme_Custom_Fonts' ) ) {
	
	
	/**
	 * Main Etheme_Custom_Fonts class
	 *
	 * @since       3.1.6
	 */
	class Etheme_Custom_Fonts {
		
		// Protected vars
		public $extension_url;
		public $extension_dir;
		public static $theInstance;
		public $post_data = array();
		public $file_data = array();
		public $errors = array();
		public $successes = array();
		
		/**
		 * Class Constructor. Defines the args for the extions class
		 *
		 * @param array $sections   Panel sections.
		 * @param array $args       Class constructor arguments.
		 * @param array $extra_tabs Extra panel tabs.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function __construct() {
			
			$this->post_data = $_POST;
			$this->file_data = $_FILES;
			$this->errors    = $this->successes = array();
			
			// ! Call upload file function
			if ( isset( $this->post_data['et-upload'] ) ) {
				$this->upload_action();
			}
		}
		
		// ! Add ajax actions
		public function actions() {
			add_action( 'wp_ajax_et_ajax_fonts_remove', array( $this, 'et_ajax_fonts_remove' ) );
			add_action( 'wp_ajax_et_ajax_fonts_export', array( $this, 'et_ajax_fonts_export' ) );
		}
		
		// ! Remove font by ajax
		public function et_ajax_fonts_remove() {
			check_ajax_referer('etheme-fonts-actions', 'security');

			if (!current_user_can( 'manage_options' )){
				wp_send_json_error('Unauthorized access');
			}
			
			$post_data = $_POST;
			$fonts     = get_option( 'etheme-fonts', false );
			
			$out = array(
				'messages' => array(),
				'status'   => 'error'
			);
			
			if ( ! isset( $post_data['id'] ) || empty( $post_data['id'] ) ) {
				$out['messages'][] = esc_html__( 'File ID does not exist', 'xstore-core' );
				echo json_encode( $out );
				die();
			}
			
			if ( ! function_exists( 'wp_delete_file' ) ) {
				require_once ABSPATH . WPINC . '/functions.php';
			}
			
			foreach ( $fonts as $key => $value ) {
				
				if ( $value['id'] == $post_data['id'] ) {
					
					$file = $value['file'];
					
					$upload_dir = wp_upload_dir();
					$upload_dir = $upload_dir['basedir'] . '/custom-fonts';
					$url        = explode( '/custom-fonts', $file['url'] );
					
					wp_delete_file( $upload_dir . $url[1] );
					
					if ( $this->custom_font_exists( $file['url'] ) ) {
						$out['messages'][] = esc_html__( 'File was\'t deleted', 'xstore-core' );
						die();
					} else {
						unset( $fonts[ $key ] );
					}
				}
			}
			
			update_option( 'etheme-fonts', $fonts );
			
			if ( count( $out['messages'] ) < 1 ) {
				$out['status']     = 'success';
				$out['messages'][] = esc_html__( 'File was deleted', 'xstore-core' );
			}
			echo json_encode( $out );
			die();
		}
		
		// ! Field Render Function
		public function render() {

            $this->enqueue();
			
			$out = $style = '';
			
			$out .= '<h2 class="etheme-page-title etheme-page-title-type-2">' . esc_html__( 'Upload Your Custom Font', 'xstore-core' ) . '</h2>';
			
			$out .= '<div class="et-col-7 etheme-fonts-section">';
			
			$out .= '<p>' . esc_html__( 'Upload the custom font to use throughout the site. For full browser support it\'s recommended to upload all formats. You can upload as many custom fonts as you need. The font you upload here will be available in the font-family drop-downs at the Typography options.', 'xstore-core' ) . '</p>';
			
			$out .= '<p class="et-message et-info">' . sprintf( esc_html__( 'Uploaded fonts you may find in your %s -> %s section', 'xstore-core' ), '<a href="' . esc_url( wp_customize_url() ) . '">' . esc_html__( 'Customizer', 'xstore-core' ) . '</a>', '<a href="' . add_query_arg( 'autofocus[section]', 'typography-content', wp_customize_url() ) . '">' . esc_html__( 'Typography', 'xstore-core' ) . '</a>' ) . '</p>';
			
			$out .= '<a class="add-form et-button et-button-green no-loader last-button"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Upload font', 'xstore-core' ) . '</a>';
			
			ob_start();
			do_action( 'etheme_custom_font_export' );
			
			$out .= ob_get_clean();
			
			$out .= '<a id="et_download-export-file" class="hidden" href=""></a>';
			
			if ( count( $this->errors ) > 0 ) {
				foreach ( $this->errors as $value ) {
					$out .= '<p class="et-message et-error">' . $value . '</p>';
				}
			}
			
			if ( count( $this->successes ) > 0 ) {
				foreach ( $this->successes as $value ) {
					$out .= '<p class="et-message">' . $value . '</p>';
				}
			}
			
			$fonts = get_option( 'etheme-fonts', false );
			
			// ! Out font information
			if ( $fonts ) {
				$out .= '<div class="et_fonts-info">';
				$out .= '<h2>' . esc_html__( 'Uploaded fonts', 'xstore-core' ) . '</h2>';
				$out .= '<ul>';
				
				$style .= '<style>';
				
				foreach ( $fonts as $value ) {
					
					// ! Set HTML
					$out .= '<li>';
					$out .= '<p>';
					$out .= '<span class="et_font-name">' . $value['name'] . '</span>';
					$out .= '<i class="et_font-remover dashicons dashicons-no-alt" aria-hidden="true" data-id="' . $value['id'] . '"></i>';
					$out .= '</p>';
					
					if ( ! $this->custom_font_exists( $value['file']['url'] ) ) {
						$out .= '<p class="et_font-error et-message et-info">';
						$out .= esc_html__( 'It looks like font file was removed from the folder directly', 'xstore-core' );
						$out .= '</p>';
						continue;
					}
					
					$out .= '<p class="et_font-preview" style="font-family: &quot;' . $value['name'] . '&quot;;"> 1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z </p>';
					$out .= '<details>';
					$out .= '<summary>' . esc_html__( 'Font details', 'xstore-core' ) . '</summary>';
					$out .= '<ul>';
					$out .= '<li>' . esc_html__( 'Uploaded at', 'xstore-core' ) . ' : ' . $value['file']['time'] . '</li>';
					$out .= '<li>';
					$out .= '</li>';
					$out .= '<li>' . esc_html__( 'File name', 'xstore-core' ) . ' : ' . $value['file']['name'] . '</li>';
					$out .= '<li>' . esc_html__( 'File size', 'xstore-core' ) . ' : ' . $this->file_size( $value['file']['size'] ) . '</li>';
					$out .= '</ul>';
					$out .= '</details>';
					$out .= '</li>';
					
					// ! Validate format
					switch ( $value['file']['extension'] ) {
						case 'ttf':
							$format = 'truetype';
							break;
						case 'otf':
							$format = 'opentype';
							break;
						case 'eot':
							$format = false;
							break;
						case 'eot?#iefix':
							$format = 'embedded-opentype';
							break;
						case 'woff2':
							$format = 'woff2';
							break;
						case 'woff':
							$format = 'woff';
							break;
						default:
							$format = false;
							break;
					}
					
					$format = ( $format ) ? 'format("' . $format . '")' : '';
					
					$font_url = ( is_ssl() && ( strpos( $value['file']['url'], 'https' ) === false ) ) ? str_replace( 'http', 'https', $value['file']['url'] ) : $value['file']['url'];
					
					// ! Set fonts
					$style .= '
                                    @font-face {
                                        font-family: "' . $value['name'] . '";
                                        src: url(' . $font_url . ') ' . $format . ';
                                    }
                                ';
				}
				
				$style .= '</style>';
				
				$out .= '</ul>';
				$out .= '<input type="hidden" name="nonce_etheme-fonts-actions" value="' . wp_create_nonce( 'etheme-fonts-actions' ) . '">';
				$out .= '</div>';
			}
			
			$out .= '</div>';

            $browsers_icons = $this->get_browser_icons();
            
			$out .= '
                <div class="et-col-5 et_fonts-notifications etheme-options-info">
                    <h2 style="margin-top: 40px;">' . esc_html__( 'Browser Support for Font Formats', 'xstore-core' ) . '</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th>' . esc_html__( 'Font format', 'xstore-core' ) . '</th>
                                <th class="et_fonts-br-name et_ie"><span class="mtips mtips-top">'.$browsers_icons['ie'].'<span class="mt-mes">'.esc_html__('Internet explorer', 'xstore-core').'</span></span></th>
                                <th class="et_fonts-br-name et_chrome"><span class="mtips mtips-top">'.$browsers_icons['chrome'].'<span class="mt-mes">'.esc_html__('Chrome', 'xstore-core').'</span></span></th>
                                <th class="et_fonts-br-name et_firefox"><span class="mtips mtips-top">'.$browsers_icons['firefox'].'<span class="mt-mes">'.esc_html__('Firefox', 'xstore-core').'</span></span></th>
                                <th class="et_fonts-br-name et_safari"><span class="mtips mtips-top">'.$browsers_icons['safari'].'<span class="mt-mes">'.esc_html__('Safari', 'xstore-core').'</span></span></th>
                                <th class="et_fonts-br-name et_opera"><span class="mtips mtips-top">'.$browsers_icons['opera'].'<span class="mt-mes">'.esc_html__('Opera', 'xstore-core').'</span></span></th>                
                            </tr>
                            <tr>
                                <td>TTF/OTF</td>
                                <td>9.0*</td>
                                <td>4.0</td>
                                <td>3.5</td>
                                <td>3.1</td>
                                <td>10.0</td>
                            </tr>
                            <tr>
                                <td>WOFF</td>
                                <td>9.0</td>
                                <td>5.0</td>
                                <td>3.6</td>
                                <td>5.1</td>
                                <td>11.1</td>
                            </tr>
                            <tr>
                                <td>WOFF2</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>36.0</td>
                                <td>35.0*</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>26.0</td>
                            </tr>
                            <!-- <tr>
                                <td>SVG</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>4.0</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>3.2</td>
                                <td>9.0</td>
                            </tr> -->
                            <tr>
                                <td>EOT</td>
                                <td>6.0</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="et-message et-info">' . esc_html__( 'Please, make sure that you upload font formats that are supported by all the browsers.', 'xstore-core' ) . '</p>
                </div>
            ';
			
			echo $style . $out;
		}
        
        private function get_browser_icons() {
            return array(
                'ie' => '<svg version="1.1" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M22.8928814,5.3646307c2.2560349-5.7619367-2.1258793-5.3585453-2.1258793-5.3585453
	c-2.8126583,0-6.3277903,2.5200396-6.3277903,2.5200396s-4.1936054-1.111402-8.3872108,1.3837142
	C1.4826994,6.7760372,1.633163,11.8659639,1.633163,11.8659639c3.7163658-5.2366967,8.8884468-7.362577,8.8884468-7.362577
	v0.3461595c-7.633966,5.1360798-9.4413786,12.6500435-9.893693,14.182375S0.4765299,24,3.6907341,24
	s6.4782553-2.5477333,6.4782553-2.5477333s0.7024727,0.1486187,2.6114264,0.1486187
	c8.0364332,0,9.9435415-6.9684172,9.9435415-6.9684172h-7.1318035c0,0-0.5003157,2.274497-3.0618944,2.274497
	c-3.5160551,0-3.3148203-3.6092873-3.3148203-3.6092873h13.6091366c0.6544704-9.2899914-7.5822735-10.6238585-7.5822735-10.6238585
	s2.9105072-2.0252626,5.4231625-2.0252626c3.983139,0,2.1000328,4.5914564,2.1000328,4.5914564L22.8928814,5.3646307z
	 M9.7711372,21.3488827c0,0-4.9043846,2.9289684-7.1068807,0.900013C1.485469,20.2180939,3.4018068,17.344511,3.4018068,17.344511
	S5.0227551,20.3048649,9.7711372,21.3488827z M15.6696911,10.1370134H9.1960506c0,0-0.0821552-3.1043558,3.3175898-3.1043558
	C15.7998476,7.0326576,15.6696911,10.1370134,15.6696911,10.1370134z"/>
</svg>',
                'opera' => '<svg version="1.1" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M17.0958138,22.86586c-0.208744,0.0122776-0.4197197,0.0178604-0.6306973,0.0178604
	c-6.0066986,0-10.8837214-4.8770218-10.8837214-10.8837204S10.4584179,1.1162791,16.4651165,1.1162791
	c0.2109756,0,0.4219532,0.0055815,0.6306973,0.0178604C15.5486517,0.4063257,13.8217678,0,12,0C5.3771162,0,0,5.3771162,0,12
	s5.3771162,12,12,12C13.8217678,24,15.5486507,23.5936737,17.0958138,22.86586z M13.4210243,3.3064184
	C14.37321,2.972651,15.397954,2.7906976,16.4651165,2.7906976c1.5884647,0,3.0831642,0.402977,4.388092,1.1118138
	C22.8066978,6.0379534,24,8.8811159,24,12s-1.1933022,5.9620457-3.1467915,8.0974884
	c-1.3049297,0.7088375-2.7996273,1.1118145-4.388092,1.1118145c-1.0671635,0-2.0919065-0.1819534-3.0440931-0.5157223
	C16.9250221,19.3607445,19.417675,15.9694881,19.417675,12S16.925024,4.639256,13.4210243,3.3064184z"/>
</svg>',
                'firefox' => '<svg version="1.1" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M0.835,12.2309999c-0.082,1.6719999,0.235,3.3400002,0.929,4.8620005
	c0.9289999,2.1079998,2.473,3.8780003,4.4229999,5.0749989c0.7950001,0.5429993,1.6630001,0.9650002,2.5779996,1.25
	c0.1199999,0.0429993,0.2440004,0.0869999,0.3690004,0.1310005c-0.0380001-0.0139999-0.0769997-0.0340004-0.1149998-0.0480003
	C10.1020002,23.8299999,11.2340002,24,12.3640003,24c4.0100002,0,5.3330002-1.5459995,5.4519997-1.6970005
	c0.1959991-0.1790009,0.3600006-0.3969994,0.4740009-0.6439991c0.0769997-0.0340004,0.1529999-0.0680008,0.2350006-0.1009998
	l0.0480003-0.0240002l0.0909996-0.0429993c0.6040001-0.2859993,1.1730003-0.6490002,1.6919994-1.0709991
	c0.7810001-0.5669994,1.3369999-1.3910007,1.5760002-2.3320007c0.1439991-0.3439999,0.1480007-0.7269993,0.0189991-1.0760002
	c0.0429993-0.0680008,0.0820007-0.1350002,0.1299992-0.2089996c0.8630009-1.401,1.3509998-3.0009995,1.4179993-4.6479998v-0.1350002
	c0-0.3540001-0.0289993-0.7030001-0.0909996-1.0480003v-0.007c-0.0340004-0.2180004-0.0680008-0.3439999-0.0680008-0.3439999
	s-0.0860004,0.0970001-0.2250004,0.2860003c-0.0429993-0.5190001-0.1340008-1.0279999-0.2779999-1.5310001
	c-0.177-0.6260004-0.4069996-1.2309999-0.6949997-1.8129997c-0.1819992-0.388-0.3929996-0.756-0.6369991-1.105
	c-0.0860004-0.131-0.177-0.2620001-0.2679996-0.382c-0.4220009-0.698-0.9109993-1.1290002-1.4710007-1.9390001
	c-0.3640003-0.6210001-0.618-1.3039999-0.7380009-2.016c-0.1529999,0.431-0.2730007,0.8729999-0.3549995,1.323
	c-0.5799999-0.5910001-1.0779991-1.0080001-1.3850002-1.2949998C15.8050003,0.732,15.9779997,0,15.9779997,0
	S13.184,3.1559999,14.3920002,6.4419999C14.809,7.5570002,15.533,8.5310001,16.4710007,9.2480001
	c1.1690006,0.9790001,2.434,1.7449999,3.1000004,3.7130003c-0.5370007-1.0319996-1.3460007-1.8990002-2.3390007-2.4960003
	c0.2970009,0.7130003,0.4510002,1.4829998,0.4459991,2.2539997c0,2.9560003-2.3769999,5.3560009-5.2989998,5.3509998
	c-0.3979998,0-0.79-0.0440006-1.1730003-0.1359997c-0.4549999-0.0869999-0.8970003-0.2380009-1.3129997-0.4510002
	c-0.618-0.3780003-1.1499996-0.8770008-1.5720005-1.4680004L8.3119993,15.999999l0.0959997,0.0339994
	c0.2209997,0.0779991,0.4399996,0.1350002,0.6709995,0.1790009c0.8959999,0.1940002,1.835,0.0830002,2.6639996-0.3190002
	c0.8380003-0.4709997,1.3409996-0.8190002,1.7539997-0.6779995h0.0089998
	c0.4029999,0.1309996,0.7189999-0.2670002,0.4309998-0.6779995c-0.4980001-0.6490002-1.3120003-0.9700003-2.118-0.8240004
	C10.9809971,13.835,10.2139969,14.441,9.1159973,13.8549995C9.0449972,13.816,8.9769974,13.7769995,8.909997,13.7339993
	c-0.0769997-0.0430002,0.2349997,0.0629997,0.1619997,0.0139999c-0.2390003-0.1210003-0.4689999-0.2620001-0.6899996-0.4169998
	c-0.0139999-0.0150003,0.1680002,0.0539999,0.1479998,0.0389996c-0.283-0.1940002-0.5270004-0.4460001-0.7179999-0.7360001
	c-0.1960001-0.3590002-0.2160001-0.7950001-0.0479999-1.1680002c0.0999999-0.184,0.2589998-0.3339996,0.4460001-0.4219999
	c0.1440001,0.0719995,0.2299995,0.1260004,0.2299995,0.1260004s-0.0620003-0.1210003-0.1000004-0.184
	c0.0139999-0.0050001,0.0240002,0,0.0380001-0.0089998c0.125,0.0539999,0.3979998,0.1940002,0.5459995,0.2810001
	c0.1000004,0.0539999,0.1820002,0.1309996,0.2489996,0.2270002c0,0,0.0480003-0.0240002,0.0139999-0.1309996
	c-0.0530005-0.1309996-0.1389999-0.2419996-0.2589998-0.3190002h0.0089998c0.1099997,0.0579996,0.2159996,0.1260004,0.316,0.1990004
	c0.0909996-0.2130003,0.1339998-0.4460001,0.125-0.6780005c0.0089998-0.1260004-0.0089998-0.2559996-0.0530005-0.3780003
	c-0.0389996-0.0780001,0.0229998-0.1070004,0.0909996-0.0240002c-0.0089998-0.0629997-0.0340004-0.1210003-0.0570002-0.1789999
	V9.9689999c0,0,0.0389996-0.0539999,0.0570002-0.0719995c0.0480003-0.0489998,0.1000004-0.092,0.1630001-0.1309996
	C9.923995,9.5480003,10.2879944,9.3590012,10.666995,9.203001c0.3070002-0.1350002,0.5600004-0.2379999,0.6129999-0.2720003
	c0.0769997-0.0489998,0.1479998-0.1070004,0.2159996-0.1700001c0.2539997-0.2180004,0.4309998-0.5229998,0.4890003-0.8579998
	c0.0050001-0.0430002,0.0089998-0.0869999,0.0139999-0.1360002V7.6950002c-0.0430002-0.1700001-0.3299999-0.296-1.8400002-0.441
	C9.6260004,7.1669998,9.1990004,6.7649999,9.0790005,6.2309999V6.2270002
	c0.2869997-0.7610002,0.8039999-1.4099998,1.4759998-1.8559999C10.5939999,4.3370004,10.4020004,4.3800001,10.441,4.3470001
	C10.5710001,4.2839999,10.6999998,4.2260003,10.8339996,4.177c0.0679998-0.0289998-0.2869997-0.164-0.6040001-0.131
	c-0.1920004,0.0089998-0.3830004,0.0580001-0.5600004,0.1350002C9.7469988,4.118,9.9669991,4.0310001,9.9139996,4.0310001
	C9.5100002,4.1090002,9.1230001,4.2589998,8.7679996,4.4679999c0-0.039,0.0050001-0.072,0.0240002-0.1069999
	C8.5089998,4.4829998,8.2639999,4.677,8.073,4.9190001c0.0050001-0.0430002,0.0089998-0.0869999,0.0089998-0.131
	C7.9519997,4.8850002,7.8329997,4.9970002,7.7319999,5.1220002L7.7280002,5.1269999
	C6.8940001,4.802,5.9879999,4.7249999,5.1120005,4.8999996L5.1030002,4.8940001H5.112
	C4.9299998,4.744,4.7719998,4.5690002,4.6469998,4.3660002L4.638,4.3709998L4.618,4.362
	C4.5609999,4.2750001,4.5029998,4.178,4.441,4.0710001C4.3979998,3.993,4.355,3.9070001,4.3109999,3.819
	c0-0.0050001-0.0050001-0.0090001-0.0089998-0.0090001c-0.0190001,0-0.0289998,0.0829999-0.0430002,0.063V3.8670001
	c-0.1529999-0.402-0.2249999-0.8329999-0.211-1.27L4.04,2.6029999C3.796,2.773,3.609,3.02,3.5079999,3.306
	c-0.043,0.1010001-0.0769999,0.1600001-0.105,0.2179999V3.5C3.408,3.4460001,3.431,3.3399999,3.4259999,3.3499999
	s-0.0090001,0.0139999-0.0139999,0.02C3.3410001,3.4530001,3.273,3.549,3.2249999,3.651
	C3.1819999,3.743,3.1429999,3.8399999,3.115,3.938c-0.0050001,0.0150001,0-0.0150001,0-0.049s0.0050001-0.0970001,0-0.0829999
	L3.0999999,3.8399999C2.779,4.5619998,2.5780001,5.3340001,2.507,6.1240001c-0.02,0.1349998-0.029,0.27-0.0239999,0.402v0.0089998
	c-0.23,0.2519999-0.431,0.533-0.608,0.829c-0.579,0.9880004-1.011,2.0599999-1.284,3.1799998
	C0.783,10.118,1.012,9.7109995,1.276,9.3269997C0.763,10.6400003,0.5,12.0410004,0.5,13.4569998
	C0.586,13.04,0.701,12.6330004,0.835,12.2309999z"/>
</svg>',
                'chrome' => '<svg version="1.1" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M12,0C8.0740004,0,4.5981565,1.894547,2.4101562,4.8105469l4.3417969,4.3417969C7.7679529,7.2833443,9.724,6,12,6
	c1.3419991,0,2.5683594,0.4552193,3.5683594,1.1992188h7.2324219l-5.4316406,2.171875C17.7601414,10.1680937,18,11.0530005,18,12
	c0,1.9720001-0.9646416,3.7077808-2.4316406,4.8007812h0.03125l-4.7597656,7.140625C11.2208443,23.9784069,11.6079998,24,12,24
	c6.6280003,0,12-5.3719997,12-12S18.6280003,0,12,0z M2.40625,4.8125C0.9022501,6.8175001,0,9.3000002,0,12
	c0,6.2290001,4.7473125,11.3464527,10.8203115,11.9394531l2.0078135-6.0234375C12.5541248,17.9540157,12.283,18,12,18
	c-3.3129997,0-6-2.6870003-6-6L2.40625,4.8125z M12,8c-2.2091389,0-4,1.7908611-4,4s1.7908611,4,4,4s4-1.7908611,4-4
	S14.2091389,8,12,8z"/>
</svg>',
                'safari' => '<svg version="1.1" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
<path d="M12,0C5.3787642,0,0,5.3787642,0,12s5.3787642,12,12,12s12-5.3787651,12-12S18.6212349,0,12,0z M12,1.0398579
	c6.0592556,0,10.9601421,4.900887,10.9601421,10.9601421S18.0592556,22.9601421,12,22.9601421S1.0398579,18.0592556,1.0398579,12
	c0-0.1893511,0.004759-0.377635,0.0142168-0.5646105C1.3472675,5.6391625,6.1300969,1.0398579,12,1.0398579z M12,2.1213505
	c-5.4472952,0-9.8786497,4.4313545-9.8786497,9.8786497S6.5527048,21.8786488,12,21.8786488S21.8786488,17.4472961,21.8786488,12
	S17.4472961,2.1213505,12,2.1213505z M12,3.6811373c0.2875204,0,0.5199289,0.232408,0.5199289,0.5199292v0.5199285
	c0,0.2875214-0.2324085,0.5199289-0.5199289,0.5199289s-0.5199289-0.2324076-0.5199289-0.5199289V4.2010665
	C11.4800711,3.9135454,11.7124796,3.6811373,12,3.6811373z M14.9824829,4.2751966
	c0.0675173-0.000536,0.1360502,0.0123081,0.2020817,0.0396042c0.2651634,0.109705,0.390995,0.4136767,0.2812901,0.6793604
	L15.266819,5.4744859c-0.0826693,0.2001729-0.2759933,0.3208938-0.4803247,0.3208938
	c-0.0665512,0-0.1340446-0.0125675-0.1990356-0.0396042c-0.2651634-0.1102252-0.390995-0.414196-0.2812891-0.6793599
	l0.1990347-0.4803252C14.5874825,4.3972178,14.7799301,4.2768054,14.9824829,4.2751966z M9.0124397,4.2772279
	c0.2025042,0.0010967,0.3946095,0.1220207,0.4772787,0.3208933l0.2000504,0.4793096
	c0.1107454,0.2651639-0.01511,0.5691357-0.2802744,0.6793609C9.3445034,5.7843475,9.2759953,5.79741,9.209444,5.79741
	c-0.2038126,0-0.3966408-0.1207204-0.4793091-0.3208933L8.5300837,4.9972076
	C8.4193392,4.7320437,8.5451937,4.428072,8.810358,4.3178473C8.8765182,4.2901607,8.9449387,4.2768617,9.0124397,4.2772279z
	 M18.2391472,5.7608528l-4.6793604,7.798934l-7.798934,4.6793604l4.6793604-7.798934L18.2391472,5.7608528z M6.4798169,5.9700432
	c0.1331019,0,0.2669759,0.0501814,0.3686218,0.1513076l0.3676062,0.3676057
	C7.4193373,6.6922493,7.4198322,7.0213718,7.2170601,7.225184C7.1156735,7.32657,6.9815407,7.3775072,6.8484387,7.3775072
	c-0.1331015,0-0.2662196-0.0514569-0.3676062-0.1523232L6.1132269,6.8575783
	C5.909934,6.6548057,5.9094386,6.3261786,6.1122112,6.1223655C6.2138572,6.0207205,6.3467155,5.9700432,6.4798169,5.9700432z
	 M19.198782,8.4742317c0.2026749,0.0008049,0.3952332,0.121006,0.4782944,0.3198786
	c0.1107445,0.2651634-0.0140953,0.5696306-0.2792587,0.6803761l-0.4803257,0.201066
	c-0.0655117,0.0275555-0.1329784,0.0396042-0.2000504,0.0396042c-0.2032928,0-0.3971367-0.1192102-0.4803257-0.3188629
	c-0.1107445-0.2656841,0.0140953-0.5696316,0.2792587-0.6803761l0.4793091-0.201066
	C19.0618458,8.4871655,19.1312237,8.4739637,19.198782,8.4742317z M4.792079,8.4965725
	c0.0674934-0.0004959,0.1361809,0.0123081,0.2020822,0.0396042l0.4803247,0.1990356
	c0.2651639,0.109705,0.3909945,0.414196,0.2812896,0.6793594C5.6731067,9.6147451,5.4792628,9.735466,5.2754507,9.735466
	c-0.0665512,0-0.1340442-0.0125675-0.1990352-0.0396042L4.5960903,9.4968262
	c-0.2651634-0.109705-0.390995-0.414196-0.2812896-0.6793594C4.3970795,8.6182032,4.5895991,8.4980593,4.792079,8.4965725z
	 M12,10.9601421c-0.5342216-0.0000486-0.9815664,0.4047127-1.0347805,0.9362783
	C10.9618053,11.9308405,10.9601107,11.9654112,10.9601421,12c0,0.5742979,0.46556,1.0398579,1.0398579,1.0398579
	S13.0398579,12.5742979,13.0398579,12S12.5742979,10.9601421,12,10.9601421z M4.2010665,11.4800711h0.5199285
	c0.2875214,0,0.5199289,0.2324085,0.5199289,0.5199289s-0.2324076,0.5199289-0.5199289,0.5199289H4.2010665
	c-0.2875211,0-0.5199292-0.2324085-0.5199292-0.5199289S3.9135454,11.4800711,4.2010665,11.4800711z M19.2790051,11.4800711
	h0.519928c0.2875214,0,0.5199299,0.2324085,0.5199299,0.5199289s-0.2324085,0.5199289-0.5199299,0.5199289h-0.519928
	c-0.2875214,0-0.5199299-0.2324085-0.5199299-0.5199289S18.9914837,11.4800711,19.2790051,11.4800711z M18.7215023,14.264534
	c0.067543-0.0004959,0.1359215,0.0123081,0.2020817,0.0396042l0.4803257,0.1990356
	c0.2651634,0.109705,0.3909931,0.414196,0.2812901,0.6793594c-0.0826702,0.2001734-0.2765141,0.3208942-0.4803257,0.3208942
	c-0.0665493,0-0.1340446-0.0125675-0.1990356-0.0396042l-0.4803238-0.1990356
	c-0.2651653-0.109705-0.390995-0.414196-0.2812901-0.6793594C18.3265018,14.3861647,18.518877,14.2660208,18.7215023,14.264534z
	 M5.2845898,14.2848434c0.2026014,0.0008783,0.3952355,0.1199903,0.4782944,0.3188629
	c0.1107445,0.2651644-0.0140948,0.5696316-0.2792587,0.6803761l-0.4793096,0.201066
	c-0.0655107,0.0275564-0.1339955,0.0396042-0.2010665,0.0396042c-0.2032919,0-0.3971362-0.1192093-0.4803247-0.3188629
	c-0.110745-0.2651634,0.0140948-0.5696306,0.2792587-0.6803761l0.4803243-0.201066
	C5.1485395,14.2968922,5.2170563,14.2845516,5.2845898,14.2848434z M17.1515617,16.6224937
	c0.1331024,0,0.2659607,0.0511951,0.3676052,0.1523228l0.3676071,0.3676052
	c0.2032909,0.2027721,0.2037868,0.5314007,0.0010147,0.7352123c-0.1013851,0.1013851-0.2355194,0.1523228-0.3686218,0.1523228
	s-0.2662201-0.0504417-0.3676052-0.1513081l-0.3676071-0.3676052c-0.2032909-0.2032909-0.2037868-0.5324135-0.0010147-0.736227
	C16.8845863,16.6731701,17.0184593,16.6224937,17.1515617,16.6224937z M14.793602,18.202589
	c0.2026262,0.0010967,0.3939857,0.1220207,0.476263,0.3208942l0.2000513,0.4793091
	c0.1107445,0.2651634-0.01511,0.5691357-0.2802744,0.6793613c-0.064991,0.0275555-0.1324844,0.0406189-0.1990356,0.0406189
	c-0.2038116,0-0.3976555-0.1207218-0.4803247-0.3208942l-0.2000504-0.4793091
	c-0.1107454-0.2651634,0.01511-0.5691338,0.2802744-0.6793594C14.6566668,18.2155228,14.7260609,18.2022247,14.793602,18.202589z
	 M9.2104597,18.2046204c0.0675173-0.000536,0.1360512,0.0123081,0.2020817,0.0396042
	c0.2651634,0.1102257,0.390995,0.414196,0.2812891,0.6793594l-0.1990347,0.4803257
	c-0.0826693,0.2001724-0.2759933,0.3208942-0.4803247,0.3208942c-0.0665512,0-0.1340446-0.0125694-0.1990356-0.0396042
	c-0.2651634-0.109705-0.390995-0.4136791-0.2812901-0.6793613l0.1990356-0.4803238
	C8.8154602,18.3266411,9.0079069,18.2062283,9.2104597,18.2046204z M12,18.7590752
	c0.2875204,0,0.5199289,0.2324085,0.5199289,0.5199299v0.519928c0,0.2875214-0.2324085,0.5199299-0.5199289,0.5199299
	s-0.5199289-0.2324085-0.5199289-0.5199299v-0.519928C11.4800711,18.9914837,11.7124796,18.7590752,12,18.7590752z"/>
</svg>');
        }
		
		// ! Upload file
		private function upload_action() {
			
			// ! Return if name file
			if ( ! isset( $this->file_data['et-fonts'] ) || empty( $this->file_data['et-fonts'] ) ) {
				$this->errors[] = esc_html__( 'Empty Font file field', 'xstore-core' );
				
				return;
			}
			
			// ! Require file
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			
			// ! Set Valid file formats
			$valid_formats = array( 'eot', 'woff2', 'woff', 'ttf', 'otf' );
			
			$file = $this->file_data['et-fonts'];
			
			// ! Get file extension
			$extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
			
			// ! Check file extension
			if ( ! in_array( strtolower( $extension ), $valid_formats ) ) {
				$this->errors[] = esc_html__( 'Wrong file extension "use only: eot, woff2, woff, ttf, otf"', 'xstore-core' );
				
				return;
			}
			
			// ! Check size 5mb limit
			if ( $file['size'] > ( 1048576 * 7 ) ) {
				$this->errors[] = esc_html__( 'File size more then 7MB', 'xstore-core' );
				
				return;
			}
			
			if ( $file['name'] ) {
				
				// ! Set overrides
				$overrides = array(
					'test_form' => false,
					'test_type' => false,
				);
				
				// ! Set font user data
				$user             = wp_get_current_user();
				$by               = array();
				$by['user_email'] = $user->user_email;
				$by['user_login'] = $user->user_login;
				$by['roles']      = array();
				foreach ( $user->roles as $value ) {
					$by['roles'][] = $value;
				}
				
				$font_file = array(
					'name'      => $file['name'],
					'type'      => $file['type'],
					'size'      => $file['size'],
					'extension' => $extension,
					'time'      => current_time( 'mysql' ),
				);
				
				// ! Change upload dir
				add_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );
				
				$status = wp_handle_upload( $file, $overrides );
				
				// ! Set upload dir to default
				remove_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );
				
				if ( $status && ! isset( $status['error'] ) ) {
					$font_file['url']   = $status['url'];
					$this->gafq_files[] = $font_file;
					$this->successes[]  = esc_html__( 'File was successfully uploaded.', 'xstore-core' );
					
					// ! Update fonts
					$fonts = get_option( 'etheme-fonts', false );
                    if ( !is_array($fonts) )
                        $fonts = array();
					$font  = array();
					
					$font['id']   = mt_rand( 1000000, 9999999 );
					$font['name'] = str_replace( '.' . $extension, '', $file['name'] );
					$font['file'] = $font_file;
					$font['user'] = $by;
					$fonts[]      = $font;
					update_option( 'etheme-fonts', $fonts );
					
				} else {
					//$this->errors[] = $status['error'];
				}
			}
			
			return;
		}
		
		// ! Upload dir filter function
		public function etheme_upload_dir( $dir ) {
			$time   = current_time( 'mysql' );
			$y      = substr( $time, 0, 4 );
			$m      = substr( $time, 5, 2 );
			$subdir = "/$y/$m";
			
			return array(
				       'path'   => $dir['basedir'] . '/custom-fonts' . $subdir,
				       'url'    => $dir['baseurl'] . '/custom-fonts' . $subdir,
				       'subdir' => '/custom-fonts' . $subdir,
			       ) + $dir;
		}
		
		// **********************************************************************//
		// ! Check file exists by url
		// **********************************************************************//
		public function custom_font_exists( $url ) {
			$upload_dir = wp_upload_dir();
			$upload_dir = $upload_dir['basedir'] . '/custom-fonts';
			$url = explode( '/custom-fonts', $url );
			
			return file_exists( $upload_dir . $url[1] );
		}
		
		// Get formated file size
		public function file_size( $bytes ) {
			if ( $bytes >= 1073741824 ) {
				$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
			} elseif ( $bytes >= 1048576 ) {
				$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
			} elseif ( $bytes >= 1024 ) {
				$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
			} elseif ( $bytes > 1 ) {
				$bytes = $bytes . ' bytes';
			} elseif ( $bytes == 1 ) {
				$bytes = $bytes . ' byte';
			} else {
				$bytes = '0 bytes';
			}
			
			return $bytes;
		}
		
		// ! Enqueue Function
		
		public function enqueue() {
			wp_enqueue_style( 'etheme-custom-fonts-css', ET_CORE_URL . 'app/models/fonts-uploader/style.css' );
			wp_enqueue_script( 'etheme-custom-fonts-js', ET_CORE_URL . 'app/models/fonts-uploader/script.min.js', array(
				'jquery'
			) );
            wp_enqueue_style('etheme_font-awesome');
		}
		
		public function et_ajax_fonts_export() {
			check_ajax_referer('etheme-fonts-actions', 'security');

			if (!current_user_can( 'manage_options' )){
				wp_send_json_error('Unauthorized access');
			}

			$fonts = get_option( 'etheme-fonts', false );
			foreach ( $fonts as $key => $value ) {
				$fonts[ $key ]['user']['user_login'] = 'imported';
				$fonts[ $key ]['user']['user_email'] = 'imported';
				$fonts[ $key ]['user']['roles']      = 'imported';
			}
			wp_send_json( $fonts );
		}
		
	} // class
	
	$custom_fonts = new Etheme_Custom_Fonts();
	$custom_fonts->actions();
} // if
