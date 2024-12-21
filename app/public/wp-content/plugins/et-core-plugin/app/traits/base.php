<?php
/**
 * Base Functions
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Traits/Base
 */
function etheme_decoding( $val ) {
	return base64_decode( $val );
}

function etheme_encoding( $val ) {
	return base64_encode( $val );
}

function etheme_fw($file, $content) {
	return fwrite($file, $content);
}

function etheme_fo($file, $perm) {
	return fopen($file, $perm);
}

function etheme_fr($file, $size) {
	return fread($file, $size);
}

function etheme_fc($file) {
	return fclose($file);
}

function etheme_fgcontent( $url, $flag, $context) {
	return file_get_contents($url, $flag, $context);
}

function etheme_fpcontent( $file, $content) {
	return file_put_contents($file, $content);
}

/**
 * Check is the current request a REST API request
 */
function etheme_is_rest($route = ''){
	global $wp_query;
	if (empty($wp_query)){
		return apply_filters('etheme_is_rest', false);
	}
	$rest_url = get_rest_url() . $route;
	
	if (preg_match('~^'.preg_quote(parse_url($rest_url, PHP_URL_PATH)).'~', $_SERVER['REQUEST_URI'])){
		return apply_filters('etheme_is_rest', true);
	}
	return apply_filters('etheme_is_rest', false);
}

if(!function_exists('etheme_override_shortcodes')){
    function etheme_override_shortcodes() {
        global $shortcode_tags, $_shortcode_tags;
        // Let's make a back-up of the shortcodes
        $_shortcode_tags = $shortcode_tags;
        // Add any shortcode tags that we shouldn't touch here
        $disabled_tags = array( '' );
        foreach ( $shortcode_tags as $tag => $cb ) {
            if ( in_array( $tag, $disabled_tags ) ) {
                continue;
            }
            // Overwrite the callback function
            $shortcode_tags[ $tag ] = 'etheme_wrap_shortcode_in_div';
        }
    }
}
// Wrap the output of a shortcode in a div with class "ult-item-wrap"
// The original callback is called from the $_shortcode_tags array
if(!function_exists('etheme_wrap_shortcode_in_div')){
    function etheme_wrap_shortcode_in_div( $attr, $content, $tag ) {
        global $_shortcode_tags;
        return '<div class="swiper-slide">' . call_user_func( $_shortcode_tags[ $tag ], $attr, $content, $tag ) . '</div>';
    }
}

if(!function_exists('etheme_restore_shortcodes')){
    function etheme_restore_shortcodes() {
        global $shortcode_tags, $_shortcode_tags;
        // Restore the original callbacks
        if ( isset( $_shortcode_tags ) ) {
            $shortcode_tags = $_shortcode_tags;
        }
    }
}

//if (! function_exists('unicode_chars')){
//	function unicode_chars($source, $iconv_to = 'UTF-8') {
//		$decodedStr = '';
//		$pos = 0;
//		$len = strlen ($source);
//		while ($pos < $len) {
//			$charAt = substr ($source, $pos, 1);
//			$decodedStr .= $charAt;
//			$pos++;
//		}
//
//		if ($iconv_to != "UTF-8") {
//			$decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
//		}
//
//		return $decodedStr;
//	}
//}

if (! function_exists('etheme_documentation_url')){
    function etheme_documentation_url($article = false, $echo = true) {
        $url = 'https://xstore.helpscoutdocs.com/';
        if ( $article ) {
            $url .= 'article/' . $article . '/';
        }

        if ($echo){
            echo apply_filters('etheme_documentation_url',$url );
        } else {
            return apply_filters('etheme_documentation_url',$url );
        }
	}
}

if (! function_exists('etheme_support_forum_url')){
    function etheme_support_forum_url($echo = false) {
        $url = 'https://www.8theme.com/forums/xstore-wordpress-support-forum/';
        if ($echo){
            echo apply_filters('etheme_support_forum_url',$url );
        } else {
            return apply_filters('etheme_support_forum_url',$url );
        }
    }
}

function etheme_curl_request($url, $params) {
	$url = $url . '?' . http_build_query($params, '', '&');

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	// Disable SSL verification (for testing purposes; not recommended for production)
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	$response = curl_exec($ch);

	curl_close($ch);

	return $response;
}

if(!function_exists('et_builders_YouTube_link')){
	add_action( 'etheme-builder-before-header-right-buttons', 'et_builders_YouTube_link', 10 );
	function et_builders_YouTube_link(){
		?>
			<a class="et_button" href="https://www.youtube.com/watch?v=RbdKjQrFnO4&list=PLMqMSqDgPNmDu3kYqh-SAsfUqutW3ohlG&index=2&t=0s" target="_blank">
	                <span class="dashicons">
	                    <svg height="1.2em" viewBox="0 -77 512.00213 512" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="m501.453125 56.09375c-5.902344-21.933594-23.195313-39.222656-45.125-45.128906-40.066406-10.964844-200.332031-10.964844-200.332031-10.964844s-160.261719 0-200.328125 10.546875c-21.507813 5.902344-39.222657 23.617187-45.125 45.546875-10.542969 40.0625-10.542969 123.148438-10.542969 123.148438s0 83.503906 10.542969 123.148437c5.90625 21.929687 23.195312 39.222656 45.128906 45.128906 40.484375 10.964844 200.328125 10.964844 200.328125 10.964844s160.261719 0 200.328125-10.546875c21.933594-5.902344 39.222656-23.195312 45.128906-45.125 10.542969-40.066406 10.542969-123.148438 10.542969-123.148438s.421875-83.507812-10.546875-123.570312zm0 0" fill="#f00"></path><path d="m204.96875 256 133.269531-76.757812-133.269531-76.757813zm0 0" fill="#fff"></path></svg>
	                </span>
				<span><?php esc_html_e('Tutorials', 'xstore-core'); ?></span>
			</a>
		<?php
	}
}


/**
 * Retrieve metadata from a file. Based on WP Core's get_file_data function.
 *
 * @param string $file        Path to the file.
 * @param string $version_tag The PHP docblock tag to check for versioning.
 *
 * @return string
 */
function et_get_file_version( $file, $version_tag = '@xstore-version' ) {

	// Avoid notices if file does not exist.
	if ( ! file_exists( $file ) ) {
		return '';
	}

	// We don't need to write to the file, so just open for reading.
	$fp = fopen( $file, 'r' ); // @codingStandardsIgnoreLine.

	// Pull only the first 8kiB of the file in.
	$file_data = fread( $fp, 8192 ); // @codingStandardsIgnoreLine.

	// PHP will close file handle, but we are good citizens.
	fclose( $fp ); // @codingStandardsIgnoreLine.

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );
	$version   = '';

	if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $version_tag, '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
		$version = trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $match[1] ) ); // see: _cleanup_header_comment().
	}

	return $version;
}