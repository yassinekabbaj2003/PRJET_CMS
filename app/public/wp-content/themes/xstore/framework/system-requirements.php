<?php if ( ! defined( 'ETHEME_FW' ) ) {
	exit( 'No direct script access allowed' );
}

// **********************************************************************//
// ! System Requirements
// **********************************************************************//

class Etheme_System_Requirements {

    public static $instance = null;
	
	// ! Requirements
	public $requirements = array(         // ! Defaults
        'api_connection' => true,       // ! true
		'php'             => '7.4',     // ! 7.0
		'wp'              => '5.8.2',     // ! 3.9
		'ssl_version'     => '1.0',     // ! 1.0
		'wp_uploads'      => true,     // ! true
		'memory_limit'    => '128M',     // ! 128M
		'time_limit'      => array(
			'min' => 120,                 // ! 30
			'req' => 180                 // ! 60
		),
		'max_input_vars'  => array(
			'min' => 1000,                 // ! 1000
			'req' => 2000                 // ! 2000
		),
		'upload_filesize' => '10M',     // ! 10M
		'filesystem'      => 'direct', // ! direct
		'wp_remote_get'   => true,     // ! true
		'f_get_contents'  => true,     // ! true
		'gzip'            => true,     // ! true
		'DOMDocument'     => true,      // ! true
	);

    public $override_files = array();
    public $outdated_templates = array();
	
	// ! Let's think that all alright
	public $result = true;
	
	// ! Just leave it here
	function __construct() {
	}

	public function admin_notices() {
        $hidden_notice = get_site_transient('etheme_hidden_templates_override_notices', '');
	    if ( $hidden_notice ) return;
        if ( isset( $_GET['et-hide-notice'] ) && isset( $_GET['_et_notice_nonce'] ) ) { // WPCS: input var ok, CSRF ok.
            if (wp_verify_nonce(sanitize_key(wp_unslash($_GET['_et_notice_nonce'])), 'etheme_hide_notices_nonce')) { // WPCS: input var ok, CSRF ok.
                set_site_transient('etheme_hidden_templates_override_notices', 'yes', WEEK_IN_SECONDS);
                return;
            }
        }
        if ( is_child_theme() ) {

            $theme_files_overrides = $this->get_theme_overrides();
            // $outdated_templates = $theme_files_overrides['outdated_templates'];
            $not_allowed_templates = array_filter($theme_files_overrides['templates'], function ($key) {
                return $key['not_allowed'];
            });

//            if ( count($outdated_templates) || count($not_allowed_templates)) {
            if ( count($not_allowed_templates)) {
                echo '<div class="et-message et-warning et-message--dismissible">
                    <p>' . sprintf(esc_html__('Some of the files in your child theme are not allowed to be modified. Please check and resolve any incompatibilities by going %1shere%2s.', 'xstore'),
                        '<a href="' . add_query_arg(array('page' => 'et-panel-system-requirements#templates-overrides'), admin_url('admin.php')) . '">',
                        '</a>') .
                    '<a class="et-message-dismiss notice-dismiss" href="'. esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'templates_overrides' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ) . '"><i class="et-admin-icon et-delete" role="button" aria-label="' . esc_html__('Dismiss this notice', 'xstore') . '" tabindex="0"></i><span class="screen-reader-text">'. esc_html__( 'Dismiss', 'xstore' ). '</span></a>'.
                    '</p>
                </div>';
            }
        }
    }
	
	// ! Return requirements
	public function get_requirements() {
		return $this->requirements;
	}
	
	// ! Return icon class, set result
	public function check( $type ) {
		if ( $type ) {
			return 'yes';
		} else {
			$this->result = false;
			
			return 'warning';
		}
		
		return $type;
	}
	
	// ! Return result. Note call it only after "html or system_test" functions!
	public function result() {
        $cache = get_transient('etheme_system_requirements_test_result', false);
        if ( $cache !== false )
            return $cache;
        set_transient('etheme_system_requirements_test_result', $this->result, WEEK_IN_SECONDS);
		return $this->result;
	}
	
	// ! Return system information
	public function get_system($force_check = false) {
		global $wp_version;
		if ( $force_check ) {
            delete_transient( 'etheme_system_information' );
            delete_transient( 'etheme_system_requirements_test_result' );
        }
		else {
            $etheme_system = get_transient('etheme_system_information', false);
            if ($etheme_system)
                return $etheme_system;
        }
		
		// $f_get_contents = str_replace( ' ', '_', 'file get contents' );
		ob_start();
        etheme_api_connection_notice();
        $api_connection_error = ob_get_clean();
		$system         = array(
		    'api_connection' => empty($api_connection_error),
			'php'             => PHP_VERSION,
			'wp'              => $wp_version,
			'curl_version'    => ( function_exists( 'curl_version' ) ) ? curl_version() : false,
			'ssl_version'     => 'undefined',
			'wp_uploads'      => wp_get_upload_dir(),
			'upload_filesize' => ini_get( 'upload_max_filesize' ),
			'memory_limit'    => ini_get( 'memory_limit' ),
			'time_limit'      => ini_get( 'max_execution_time' ),
			'max_input_vars'  => ini_get( 'max_input_vars' ),
			'filesystem'      => get_filesystem_method(),
			'wp_remote_get'   => function_exists( 'wp_remote_get' ),
			'f_get_contents'  => function_exists( 'file_get_contents' ),
			'gzip'            => is_callable( 'gzopen' ),
			'DOMDocument'     => class_exists('DOMDocument'),
		);

		if ($system['memory_limit'] == -1) {
			$system['memory_limit'] = WP_MEMORY_LIMIT;
		}
		
		if ( isset( $system['curl_version']['ssl_version'] ) ) {
			$system['ssl_version'] = $system['curl_version']['ssl_version'];
			$system['ssl_version'] = preg_replace( '/[^.0-9]/', '', $system['ssl_version'] );
		} else if ( extension_loaded( 'openssl' ) && defined( 'OPENSSL_VERSION_NUMBER' ) ) {
			$system['ssl_version'] = $this->get_openssl_version_number( true );
		}

        set_transient('etheme_system_information', $system, WEEK_IN_SECONDS);

		return $system;
	}
	
	// ! test system
	public function system_test($force_check = false) {
	    if ( !$force_check ) {
            $cache = get_transient('etheme_system_requirements_test_result', false);
            if ($cache !== false)
                return;
        }
		$system = $this->get_system($force_check);
        ( $system['api_connection'] === $this->requirements['api_connection'] ) ? $this->check( true ) : $this->check( false );
		( $system['filesystem'] === $this->requirements['filesystem'] ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['php'], $this->requirements['php'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['wp'], $this->requirements['wp'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( wp_convert_hr_to_bytes( $system['memory_limit'] ) >= wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? $this->check( true ) : $this->check( false );
		( $system['time_limit'] >= $this->requirements['time_limit']['min'] ) ? $this->check( true ) : $this->check( false );
		( $system['max_input_vars'] >= ( $this->requirements['max_input_vars']['min'] ) ) ? $this->check( true ) : $this->check( false );
		( wp_convert_hr_to_bytes( $system['upload_filesize'] ) >= wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? $this->check( true ) : $this->check( false );
		( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( $system['gzip'] == $this->requirements['gzip'] ) ? $this->check( true ) : $this->check( false );
		( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? $this->check( true ) : $this->check( false );
		( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? $this->check( true ) : $this->check( false );
        if ( is_child_theme() ) {
            $not_allowed_templates = array_filter($this->get_theme_overrides()['templates'], function ($key) {
                return $key['not_allowed'];
            });
            // break if child-theme contains files that are not allowed for modifications
            if (count($not_allowed_templates))
                $this->check( false );
        }
	}

    public function system_logs() {
        $system = $this->get_system();
        $logs = array();
        if ( $system['api_connection'] === $this->requirements['api_connection'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('API server connection: Requirement - %1s ---  System - %2s', 'xstore'), esc_html__('success', 'xstore'), esc_html__('error', 'xstore'))
            );
        }

        if ( $system['filesystem'] === $this->requirements['filesystem'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('WP File System: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['filesystem'], $system['filesystem'])
            );
        }

        if ( version_compare( $system['php'], $this->requirements['php'], '>=' ) ) {}
        else {
            $logs[] = array(
                'type' => 'error',
                'message' => sprintf(esc_html__('PHP version: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['php'], $system['php'] . (version_compare( $system['php'], $this->requirements['php'], '==' ) ? '(min)' : ''))
            );
        }

        if ( version_compare( $system['wp'], $this->requirements['wp'], '>=' ) ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('WordPress version: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['wp'], $system['wp'] . (version_compare( $system['wp'], $this->requirements['wp'], '==' ) ? '(min)' : ''))
            );
        }

        if ( wp_convert_hr_to_bytes( $system['memory_limit'] ) >= wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('Memory limit: Requirement - %1s ---  System - %2s', 'xstore'), wp_convert_hr_to_bytes($this->requirements['memory_limit']), wp_convert_hr_to_bytes($system['memory_limit']) . ( wp_convert_hr_to_bytes( $system['memory_limit'] ) === wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ? '(min)' : ''))
            );
        }

        if ( $system['time_limit'] >= $this->requirements['time_limit']['req'] ) {}
        else {
            $logs[] = array(
                'type' => ($system['time_limit'] < $this->requirements['time_limit']['min'] ? 'error' : 'warning'),
                'message' => sprintf(esc_html__('Max execution time: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['time_limit']['min'], $system['time_limit'] . ( (int) $system['time_limit'] === (int) $this->requirements['time_limit']['min'] ? '(min)' : ''))
            );
        }

        if ( $system['max_input_vars'] >= $this->requirements['max_input_vars']['req'] ) {}
        else {
            $logs[] = array(
                'type' => ($system['max_input_vars'] < $this->requirements['max_input_vars']['min'] ? 'error' : 'warning'),
                'message' => sprintf(esc_html__('Max input vars: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['max_input_vars']['req'], $system['max_input_vars'] . ( (int) $system['max_input_vars'] === (int) $this->requirements['max_input_vars']['min'] ? '(min)' : ''))
            );
        }

        if ( wp_convert_hr_to_bytes( $system['upload_filesize'] ) >= wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('Upload max filesize: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['upload_filesize'], $system['upload_filesize'] . ( (int) wp_convert_hr_to_bytes( $system['upload_filesize'] ) === (int) wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ? '(min)' : ''))
            );
        }

        if ( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('../Uploads folder: Requirement - %1s ---  System - %2s', 'xstore'), 'writable', 'unwritable')
            );
        }

        if ( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '>=' ) ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('OpenSSL version: Requirement - %1s ---  System - %2s', 'xstore'), $this->requirements['ssl_version'], $system['ssl_version'] . ( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '==' ) ? '(min)' : ''))
            );
        }

        if ( $system['gzip'] == $this->requirements['gzip'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('GZIP compression: Requirement - %1s ---  System - %2s', 'xstore'), 'enable', 'disable')
            );
        }

        if ( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('%1s: Requirement - %2s ---  System - %3s', 'xstore'), str_replace(' ', '_', 'file get contents') . '( )', 'enable', 'disable')
            );
        }

        if ( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) {}
        else {
            $logs[] = array(
                'type' => 'warning',
                'message' => sprintf(esc_html__('wp_remote_get( ): Requirement - %1s ---  System - %2s', 'xstore'), 'enable', 'disable')
            );
        }

        if ( is_child_theme() ) {
            $theme_files_overrides = $this->get_theme_overrides();
            $outdated_templates = $theme_files_overrides['outdated_templates'];
            $not_allowed_templates = array_filter($theme_files_overrides['templates'], function ($key) {
                return $key['not_allowed'];
            });
            if ( count($outdated_templates) ) {
                foreach ($outdated_templates as $outdated_template) {
                    $outdated_template_info = array_values(array_filter($theme_files_overrides['templates'], function ($key) use ($outdated_template) {
                        return $key['file'] == $outdated_template;
                    }))[0];
                    $logs[] = array(
                        'type' => 'warning',
                        'message' => sprintf(
                        /* Translators: %1$s: Template name, %2$s: Template version, %3$s: Core version. */
                            esc_html__( '%1$s version %2$s is out of date. The core version is %3$s', 'xstore' ),
                            '<code>' . esc_html( $outdated_template_info['file'] ) . '</code>',
                            '<strong style="var(--et_admin_red-color, #c62828)">' . ($outdated_template_info['version']?$outdated_template_info['version']:esc_html__('Undefined', 'xstore')) . '</strong>',
                            esc_html( $outdated_template_info['core_version'] )
                        ) . ' <a href="'.add_query_arg(array('page' => 'et-panel-system-requirements#templates-overrides'), admin_url('admin.php')).'">'.
                            esc_html__('View details', 'xstore').
                            '</a>'
                    );
                }
            }
            if ( count($not_allowed_templates) ) {
//                $child_theme_folder = str_replace( WP_CONTENT_DIR . '/themes/', '', get_stylesheet_directory() );

                foreach ($not_allowed_templates as $not_allowed_template) {
                    $file_type = ( strpos( $not_allowed_template['file'], '.php' ) !== false ) ? 'php' : 'js';

                    $logs[] = array(
                        'type' => 'error',
                        'message' => sprintf(
                        /* Translators: %1$s: Template name, %2$s: Notice type */
                            esc_html__( '%1s file is "%2s" to be modified in your child-theme folder', 'xstore' ),
                            '<code>' . esc_html( $not_allowed_template['file'] ) . '</code>',
                            ('<strong style="var(--et_admin_red-color, #c62828)">'.(($file_type=='js')?esc_html__('Prohibited', 'xstore'):esc_html__('Not allowed', 'xstore')).'</strong>')
                        ) . ' <a href="'.add_query_arg(array('page' => 'et-panel-system-requirements#deprecated-templates-overrides'), admin_url('admin.php')).'">'.
                            esc_html__('View details', 'xstore').
                            '</a>'
                    );
                }
            }
        }

        // sort by priority 'error', 'warning'
        uasort( $logs, function ( $item1, $item2 ) {
            return $item1['type'] <=> $item2['type'];
        } );

        return $logs;
    }
	
	// ! Show html result
	public function html() {
		$system = $this->get_system();
        $helper = '<span class="mtips"><span class="dashicons dashicons-editor-help"></span><span class="mt-mes">%s</span></span>';
		
		echo '<table class="system-requirements">';
		printf(
			'<thead><th class="requirement-headings environment">%1$s</th>
				<th>%2$s</th>
				<th>%3$s</th></thead>',
			esc_html__( 'Environment', 'xstore' ),
			esc_html__( 'Requirement', 'xstore' ),
			esc_html__( 'System', 'xstore' )
		);

        printf(
            '<tr class="api-connection %3$s">
					<td>' . esc_html__( 'API server connection:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s %4$s</td>
				</tr>',
            esc_html__('success', 'xstore'),
            ( $system['api_connection'] === $this->requirements['api_connection'] ) ? esc_html__('success', 'xstore') : esc_html__('error', 'xstore'),
            ( $system['api_connection'] === $this->requirements['api_connection'] ) ? $this->check( true ) : $this->check( false ),
            ( $system['api_connection'] === $this->requirements['api_connection'] ) ?
                '<span class="dashicons dashicons-'.$this->check( true ).'"></span>' :
                ('<span class="mtips mtips-lg mtips-left"><span class="dashicons dashicons-'.$this->check( false ).'"></span><span class="mt-mes">'.
                    esc_html__('We are unable to connect to the XStore API with the XStore theme. Please check your SSL certificate or white lists.', 'xstore').
                '</span>')
        );

		printf(
			'<tr class="wp-system %3$s">
					<td>' . esc_html__( 'WP File System:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			$this->requirements['filesystem'],
			$system['filesystem'],
			( $system['filesystem'] === $this->requirements['filesystem'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="php-version %3$s">
					<td>' . esc_html__( 'PHP version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['php'],
			$system['php'],
			( version_compare( $system['php'], $this->requirements['php'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['php'], $this->requirements['php'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="wp-version %3$s">
					<td>' . esc_html__( 'WordPress version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['wp'],
			$system['wp'],
			( version_compare( $system['wp'], $this->requirements['wp'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['wp'], $this->requirements['wp'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="memory-limit %3$s">
					<td>' . esc_html__( 'Memory limit:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['memory_limit'],
			$system['memory_limit'],
			( wp_convert_hr_to_bytes( $system['memory_limit'] ) >= wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? $this->check( true ) : $this->check( false ),
			( wp_convert_hr_to_bytes( $system['memory_limit'] ) === wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="execution-time %1$s %2$s">
					<td>' . esc_html__( 'Max execution time:', 'xstore' ) . '</td>
					<td>min (%3$s-%4$s)</td>
					<td>%5$s<span class="dashicons dashicons-%6$s %7$s"></td>
				</tr>',
			( $system['time_limit'] >= $this->requirements['time_limit']['req'] ) ? '' : 'warning',
			( (int) $system['time_limit'] === (int) $this->requirements['time_limit']['min'] ) ? 'min' : '',
			$this->requirements['time_limit']['min'],
			$this->requirements['time_limit']['req'],
			$system['time_limit'],
			( $system['time_limit'] >= $this->requirements['time_limit']['min'] ) ? $this->check( true ) : $this->check( false ),
			( $system['time_limit'] >= $this->requirements['time_limit']['req'] ) ? '' : 'dashicons-warning'
		);
		
		printf(
			'<tr class="input-vars %1$s %2$s">
					<td>' . esc_html__( 'Max input vars:', 'xstore' ) . '</td>
					<td>min (%3$s-%4$s)</td>
					<td>%5$s<span class="dashicons dashicons-%6$s %7$s"></span></td>
				</tr>',
			( $system['max_input_vars'] >= $this->requirements['max_input_vars']['req'] ) ? '' : 'warning',
			( (int) $system['max_input_vars'] === (int) $this->requirements['max_input_vars']['min'] ) ? 'min' : '',
			$this->requirements['max_input_vars']['min'],
			$this->requirements['max_input_vars']['req'],
			$system['max_input_vars'],
			( $system['max_input_vars'] >= ( $this->requirements['max_input_vars']['min'] ) ) ? $this->check( true ) : $this->check( false ),
			( $system['max_input_vars'] >= $this->requirements['max_input_vars']['req'] ) ? '' : 'dashicons-warning'
		);
		
		printf(
			'<tr class="filesize %3$s">
					<td>' . esc_html__( 'Upload max filesize:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['upload_filesize'],
			$system['upload_filesize'],
			( wp_convert_hr_to_bytes( $system['upload_filesize'] ) >= wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? $this->check( true ) : $this->check( false ),
			( (int) wp_convert_hr_to_bytes( $system['upload_filesize'] ) === (int) wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? 'min' : ''
		);

		printf(
			'<tr class="uploads-folder %3$s">
					<td>' . esc_html__( '../Uploads folder:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			'writable',
			( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? 'writable' : 'unwritable',
			( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="ssl-version %3$s">
					<td>' . esc_html__( 'OpenSSL version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['ssl_version'],
			$system['ssl_version'],
			( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="gzip-compression %3$s">
					<td>' . esc_html__( 'GZIP compression:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			'enable',
			( $system['gzip'] == $this->requirements['gzip'] ) ? 'enable' : 'disable',
			( $system['gzip'] == $this->requirements['gzip'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="function-f_get_contents %3$s">
					<td>' . str_replace( ' ', '_', 'file get contents' ) . '( ):</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			( $this->requirements['f_get_contents'] ) ? 'enable' : 'disable',
			( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? 'enable' : 'disable',
			( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="function-wp_remote_get %3$s">
					<td>wp_remote_get( ):</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			( $this->requirements['wp_remote_get'] ) ? 'enable' : 'disable',
			( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? 'enable' : 'disable',
			( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? $this->check( true ) : $this->check( false )
		);

		printf(
			'<tr class="class-DOMDocument %3$s">
					<td>DOMDocument</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			( $this->requirements['DOMDocument'] ) ? 'enable' : 'disable',
			( $system['DOMDocument'] == $this->requirements['DOMDocument'] ) ? 'enable' : 'disable',
			( $system['DOMDocument'] == $this->requirements['DOMDocument'] ) ? $this->check( true ) : $this->check( false )
		);
		echo '</table>';
	}

	public function wp_html() {
        $system = $this->get_system();
        echo '<table class="system-requirements">';

        $helper = '<span class="mtips"><span class="dashicons dashicons-editor-help"></span><span class="mt-mes">%s</span></span>';

        printf(
            '<tr class="wp-home-url">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'Home url:', 'xstore' ) . sprintf($helper, esc_attr__( 'The URL of your site\'s homepage.', 'xstore' )),
             esc_url_raw(home_url())
        );

        printf(
            '<tr class="wp-site-url">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'Site url:', 'xstore' ) . sprintf($helper, esc_attr__( 'The root URL of your site.', 'xstore' )),
            esc_url_raw(site_url())
        );

        printf(
            '<tr class="wp-content-path">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'WP Content Path:', 'xstore' ) . sprintf($helper, esc_attr__( 'System path of your wp-content directory.', 'xstore' )),
            (defined( 'WP_CONTENT_DIR' ) ? esc_html( WP_CONTENT_DIR ) : esc_html__( 'N/A', 'xstore' ))
        );

        printf(
            '<tr class="wp-root-path">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'WP Path:', 'xstore' ) . sprintf($helper, esc_attr__( 'System path of your WP root directory.', 'xstore' )),
            (defined( 'ABSPATH' ) ? esc_html( ABSPATH ) : esc_html__( 'N/A', 'xstore' ))
        );

        printf(
            '<tr class="wp-multisite">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'WP Multisite:', 'xstore' ) . sprintf($helper, esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'xstore' )),
            (is_multisite() ? esc_html__('Yes', 'xstore') : esc_html__('No', 'xstore'))
        );

        printf(
            '<tr class="wp-debug-mode">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'WP Debug Mode:', 'xstore' ) . sprintf($helper, esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'xstore' )),
            ((defined( 'WP_DEBUG' ) && WP_DEBUG) ? esc_html__('Yes', 'xstore') : esc_html__('No', 'xstore'))
        );

        printf(
            '<tr class="wp-language">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'Language:', 'xstore' ) . sprintf($helper, esc_attr__( 'The current language used by WordPress. Default = English.', 'xstore' )),
            esc_html( get_locale() )
        );

        printf(
            '<tr class="wp-theme-version">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
            esc_html__( 'Theme version:', 'xstore' ) . sprintf($helper, esc_attr__( 'The current version of theme used.', 'xstore' )),
            ETHEME_THEME_VERSION
        );

        if ( is_child_theme() ) {
            $theme   = wp_get_theme();
            $version = $theme->get( 'Version' );
            printf(
                '<tr class="wp-child-theme-version">
					<td>%1$s</td>
					<td>%2$s</td>
				</tr>',
                esc_html__('Child theme version:', 'xstore') . sprintf($helper, esc_attr__('The current version of child theme used.', 'xstore')),
                $version
            );
        }

        echo '</table>';
    }

    public function wp_active_plugins() {
        $active_plugins = (array) get_option( 'active_plugins', [] );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', [] ) ) );
        }

        if ( count($active_plugins) ) {
            $this->get_heading(esc_html__('Active plugins', 'xstore'));
            ?>
            <br/>
            <?php
        }

        echo '<table class="system-requirements">';

        printf(
            '<thead><th class="requirement-headings environment">%1$s</th>
				<th>%2$s</th>
				<th>%3$s</th></thead>',
            esc_html__( 'Name', 'xstore' ),
            esc_html__( 'Version', 'xstore' ),
            esc_html__( 'Author', 'xstore' )
        );

        $helper = '<span class="mtips mtips-lg"><span class="dashicons dashicons-editor-help"></span><span class="mt-mes">%s</span></span>';


        foreach ( $active_plugins as $plugin_file ) {

            $plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );

            if ( ! empty( $plugin_data['Name'] ) ) {

                // Link the plugin name to the plugin url if available.
                if ( ! empty( $plugin_data['PluginURI'] ) ) {
                    $plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage', 'xstore' ) . '">' . esc_html( $plugin_data['Name'] ) . '</a>';
                } else {
                    $plugin_name = esc_html( $plugin_data['Name'] );
                }
                ?>
                <tr>
                    <td>
                        <?php echo $plugin_name . sprintf($helper, $plugin_data['Description']); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>
                    <td>
                        <?php echo ' v.'.$plugin_data['Version']; ?>
                    </td>
                    <td>
                        <?php $author_name = preg_replace( '#<a.*?>([^>]*)</a>#i', '$1', $plugin_data['AuthorName'] ); ?>
                        <?php echo'<a href="' . esc_url( $plugin_data['AuthorURI'] ) . '" target="_blank">' . esc_html( $author_name ) . '</a>'; ?>
                    </td>
                </tr>
                <?php
            }
        }

        echo '</table>';

        echo '<br/>';
        echo '<br/>';
    }
	
	public function get_openssl_version_number( $patch_as_number = false, $openssl_version_number = null ) {
		if ( is_null( $openssl_version_number ) ) {
			$openssl_version_number = OPENSSL_VERSION_NUMBER;
		}
		$openssl_numeric_identifier = str_pad( (string) dechex( $openssl_version_number ), 8, '0', STR_PAD_LEFT );
		
		$openssl_version_parsed = array();
		$preg                   = '/(?<major>[[:xdigit:]])(?<minor>[[:xdigit:]][[:xdigit:]])(?<fix>[[:xdigit:]][[:xdigit:]])';
		$preg                   .= '(?<patch>[[:xdigit:]][[:xdigit:]])(?<type>[[:xdigit:]])/';
		preg_match_all( $preg, $openssl_numeric_identifier, $openssl_version_parsed );
		$openssl_version = false;
		if ( ! empty( $openssl_version_parsed ) ) {
			$alphabet        = array(
				1  => 'a',
				2  => 'b',
				3  => 'c',
				4  => 'd',
				5  => 'e',
				6  => 'f',
				7  => 'g',
				8  => 'h',
				9  => 'i',
				10 => 'j',
				11 => 'k',
				12 => 'l',
				13 => 'm',
				14 => 'n',
				15 => 'o',
				16 => 'p',
				17 => 'q',
				18 => 'r',
				19 => 's',
				20 => 't',
				21 => 'u',
				22 => 'v',
				23 => 'w',
				24 => 'x',
				25 => 'y',
				26 => 'z'
			);
			$openssl_version = intval( $openssl_version_parsed['major'][0] ) . '.';
			$openssl_version .= intval( $openssl_version_parsed['minor'][0] ) . '.';
			$openssl_version .= intval( $openssl_version_parsed['fix'][0] );
			$patchlevel_dec  = hexdec( $openssl_version_parsed['patch'][0] );
			if ( ! $patch_as_number && array_key_exists( $patchlevel_dec, $alphabet ) ) {
				$openssl_version .= $alphabet[ $patchlevel_dec ]; // ideal for text comparison
			} else {
				$openssl_version .= '.' . $patchlevel_dec; // ideal for version_compare
			}
		}
		
		return $openssl_version;
	}

    public function enqueue_panel_page_script() {
        wp_enqueue_script('etheme_panel_system_requirements.min');
    }
	// overrides files functions
    public function template_overrides () {
        $global_admin_class = EthemeAdmin::get_instance();
	    // if ( !is_child_theme() ) return; // this condition was already set in page template of panel
        $theme_files_overrides = $this->get_theme_overrides();
        $overrided_templates = array_filter($theme_files_overrides['templates'], function ($key) {
            return !$key['not_allowed'];
        });
        $outdated_templates = $theme_files_overrides['outdated_templates'];
        $not_allowed_templates = array_filter($theme_files_overrides['templates'], function ($key) {
            return $key['not_allowed'];
        });

        if ( !count($overrided_templates) && !count($not_allowed_templates) ) {
            $this->get_heading(esc_html__('Templates overrides', 'xstore'), '<a href="' . add_query_arg(array('page' => 'et-panel-system-requirements', 'et_clear_theme_templates_overrides_info' => 'true'), admin_url('admin.php')) . '" class="et-button">'.
                    $global_admin_class->get_loader(false, false).
                    '<span class="dashicons dashicons-image-rotate"></span> ' . esc_html__( 'Check again', 'xstore' ) .
                '</a>', 'templates-overrides'); ?>
            <p class="et-message et-info"><?php echo sprintf(esc_html__('Currently, you have not made any changes to your child theme, so everything is good.', 'xstore'), '<a href="https://www.8theme.com/forums/xstore-wordpress-support-forum/" rel="nofollow" target="_blank">', '</a>'); ?></p>
        <?php }
        else {
            $this->get_heading(esc_html__('Templates overrides', 'xstore'), '<a href="' . add_query_arg(array('page' => 'et-panel-system-requirements', 'et_clear_theme_templates_overrides_info' => 'true'), admin_url('admin.php')) . '" class="et-button">'.
                $global_admin_class->get_loader(false, false).
                '<span class="dashicons dashicons-image-rotate"></span> ' . esc_html__( 'Clear cache', 'xstore' ) .
                '</a>', 'templates-overrides');?>

            <p class="et-message et-info">
                <?php echo sprintf(esc_html__('This table contains a list of files that were copied and modified in your %1s theme.', 'xstore'),
                    str_replace( WP_CONTENT_DIR . '/themes/', '', get_stylesheet_directory() )); ?><br/>
                <?php echo sprintf(esc_html__('Please ensure that you %1sclear%2s the Templates system cache after updating the theme, as this may cause outdated files to remain.', 'xstore'),
                '<a href="'. add_query_arg(array('page' => 'et-panel-system-requirements', 'et_clear_theme_templates_overrides_info' => 'true'), admin_url('admin.php')). '">', '</a>'); ?>
            </p>
            <?php if ( count($outdated_templates) ) : ?>
                <p class="et-message et-warning">
                    <?php
                        echo esc_html__('We detected that some files version are out of date, please, follow the instructions below to keep your child-theme fully compatible with latest theme updates.', 'xstore');
                    ?>
                </p>
            <?php endif; ?>
            <br/>
        <?php }
        if ( count($overrided_templates) ) {
            echo '<table class="system-requirements">';

            printf(
                '<thead><th class="requirement-headings environment">%1$s</th>
				<th>%2$s</th>
				<th>%3$s</th></thead>',
                esc_html__( 'Name', 'xstore' ),
                esc_html__( 'Core version', 'xstore' ),
                esc_html__( 'Version', 'xstore' )
            );

            foreach ($overrided_templates as $overrided_template) {
                $warning = in_array($overrided_template['file'], $outdated_templates);
                ?>
                <tr class="<?php if ( $warning ) echo 'warning'; ?>">
                    <?php echo '<td>'.$overrided_template['file'] . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    <?php echo '<td>'.$overrided_template['core_version'] . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    <?php echo '<td>'.($overrided_template['version']?$overrided_template['version']:esc_html__('Undefined', 'xstore')) . ($warning ? '<span class="dashicons dashicons-warning"></span>' : '' ) . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </tr>
            <?php } ?>

                <tfoot>
                    <tr>
                        <td colspan="2">
                            <?php echo sprintf(esc_html__('Generated at: %s'), '<time datetime="'.$theme_files_overrides['generated_at'].'">'.$theme_files_overrides['generated_at'].'</time>'); ?>
                        </td>
                        <td>
                            <?php if ( count($outdated_templates) ) :
                                $this->enqueue_panel_page_script(); ?>
                                <div class="et_template-overrides-update-info mtips mtips-top" style="display: inline-block; cursor:pointer;"><span class="dashicons dashicons-warning"></span>
                                    <?php echo esc_html__('Learn how to update', 'xstore'); ?>
                                    <span class="mt-mes"><?php echo esc_html__('Click here to find the recommendations!', 'xstore'); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tfoot>

            <?php
            echo '</table>';
            echo '<br/>';
            echo '<br/>';
        }

        if ( count($not_allowed_templates) ) {
            $this->enqueue_panel_page_script();

            $this->get_heading(esc_html__('Deprecated templates overrides', 'xstore'), '<a href="' . add_query_arg(array('page' => 'et-panel-system-requirements', 'et_clear_theme_templates_overrides_info' => 'true'), admin_url('admin.php')) . '" class="et-button">'.
                $global_admin_class->get_loader(false, false).
                '<span class="dashicons dashicons-image-rotate"></span> ' . esc_html__( 'Clear cache', 'xstore' ) .
                '</a>', 'deprecated-templates-overrides'); ?>
            <p class="et-message et-warning">
                <?php echo sprintf(esc_html__('This table contains a list of files that %1sshould not%2s have been copied and modified in your %3s theme.', 'xstore'), '<strong><ins>', '</ins></strong>', str_replace( WP_CONTENT_DIR . '/themes/', '', get_stylesheet_directory() )); ?><br/>
                <?php echo esc_html__('Please follow the instructions next to each file and then delete them in order to ensure the best compatibility with theme updates and prevent any errors from occurring in the future.', 'xstore'); ?>
            </p>
            <br/>

            <?php
            echo '<table class="system-requirements">';

            printf(
                '<thead><th class="requirement-headings environment">%1$s</th>
				<th>%2$s</th></thead>',
                esc_html__( 'Name', 'xstore' ),
                esc_html__( 'Details', 'xstore' )
            );

            $child_theme_folder = str_replace( WP_CONTENT_DIR . '/themes/', '', get_stylesheet_directory() );

            foreach ($not_allowed_templates as $not_allowed_template) {
                $file_type = 'js';
                $not_allowed_file_notice = esc_html__('This file should not be modified in your child-theme as it can cause errors on the frontend and create further compatibility issues in the future.', 'xstore');

                if ( strpos( $not_allowed_template['file'], '.php' ) !== false ) {
                    $file_type = 'php';
                    $not_allowed_file_notice =
                        esc_html__('If you need to modify any functions located in this file, please ensure that the function meets the following requirements:', 'xstore').'<br/><ol>'.
                        '<li>'.sprintf(esc_html__('It is wrapped in the following condition: %1s', 'xstore'), '<code>if (!function_exists(\'name_of_function\')</code>') . '</li>' .
                        '<li>'.sprintf(esc_html__('The function has not been copied to your %1s before. ', 'xstore'), '<strong>'.$child_theme_folder.DIRECTORY_SEPARATOR.'functions.php</strong>').'</li></ol>'.
                        sprintf(esc_html__('If all requirements are met, copy the function with its name to your %1s and make your changes.', 'xstore'), '<strong>'.$child_theme_folder.DIRECTORY_SEPARATOR.'functions.php</strong>') . '<br/>'.
                        esc_html__('Note: Remember to keep the functions that you copy and modify in child-theme compatible after each theme update.', 'xstore');
                }
                ?>
                <tr class="warning">
                    <?php echo '<td>'.$not_allowed_template['file'] . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>

                    <?php
                        echo '<td><strong>'.(($file_type=='js')?esc_html__('Prohibited', 'xstore'):esc_html__('Not allowed', 'xstore')).'</strong>'.
                                '<span class="mtips mtips-lg mtips-left no-arrow"><span class="dashicons dashicons-editor-help"></span><span class="mt-mes">'.$not_allowed_file_notice.'</span></span>'.
                            '</td>';
                        ?>
                </tr>
                <?php
            }
                ?>
                <tfoot>
                <tr>
                    <td>
                        <?php echo sprintf(esc_html__('Generated at: %s'), '<time datetime="'.$theme_files_overrides['generated_at'].'">'.$theme_files_overrides['generated_at'].'</time>'); ?>
                    </td>
                    <td>
                        <div class="et_template-overrides-update-info mtips mtips-top" style="display: inline-block; cursor:pointer;"><span class="dashicons dashicons-warning"></span>
                            <?php echo esc_html__('Learn how to update', 'xstore'); ?>
                            <span class="mt-mes"><?php echo esc_html__('Click here to find the recommendations!', 'xstore'); ?></span>
                        </div>
                    </td>
                </tr>
                </tfoot>

            <?php
            echo '</table>';
            echo '<br/>';
            echo '<br/>';
        }
    }
    public function get_theme_overrides ()
    {
        $transient_key = 'xstore_templates_overrides';
        $cached_overrides = get_site_transient($transient_key, array());
        if ($cached_overrides) {
            return $cached_overrides;
        }

        $template_directory = get_template_directory();
        $child_template_directory = get_stylesheet_directory();

        // Root directory
        $this->check_overrides(
            $template_directory . '/',
            $child_template_directory . '/',
            $this->scan_root_template_files()
        );

        // theme templates
        $this->check_overrides(
            $template_directory . '/templates/',
            $child_template_directory . '/templates/',
            $this->scan_template_files($template_directory . '/templates/')
        );

        // woocommerce
        $this->check_overrides(
            $template_directory . '/woocommerce/',
            $child_template_directory . '/woocommerce/',
            $this->scan_template_files($template_directory . '/woocommerce/')
        );

        // some customers could rewrite files out of this folder
        $this->check_overrides(
            $template_directory . '/framework/',
            $child_template_directory . '/framework/',
            $this->scan_template_files($template_directory . '/framework/'),
                true
        );

        // some customers could rewrite files out of this folder too
        $this->check_overrides(
            $template_directory . '/js/',
            $child_template_directory . '/js/',
            $this->scan_template_files($template_directory . '/js/'),
                true
        );

        $generated_at = current_time( 'Y-m-d H:i:s P' ); // current_time( get_option( 'date_format' ).' ' . get_option( 'time_format' ) . ' P' )
        set_site_transient($transient_key,
            array(
                'templates' => $this->override_files,
                'outdated_templates' => $this->outdated_templates,
                'generated_at' => $generated_at
            ), WEEK_IN_SECONDS);

        return array(
            'templates' => $this->override_files,
            'outdated_templates' => $this->outdated_templates,
            'generated_at' => $generated_at
        );
    }

    /**
     * Scan the root template files.
     *
     * @return array
     */
    private function scan_root_template_files() {
        $result = array();
        // functions.php is technically an overridden file, however we don't want to report it in status listing.
        $exclude_files = array( 'functions.php' );

        $dir_iterator = new DirectoryIterator( get_template_directory() );
        foreach ( $dir_iterator as $file ) {
            if ( $file->getExtension() == 'php' && ! in_array( $file->getFilename(), $exclude_files, true ) ) {
                $result[] = $file->getFilename();
            }
        }

        return $result;
    }

    /**
     * Scan the template files.
     *
     * @param string $path Path to a template directory.
     *
     * @return array
     */
    private function scan_template_files( $path ) {
        $files  = @scandir( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
        $result = array();

        if ( ! empty( $files ) ) {
            foreach ( $files as $key => $value ) { // phpcs:ignore VariableAnalysis.CodeAnalysis
                if ( ! in_array( $value, array( '.', '..' ), true ) ) {
                    if ( is_dir( $path . DIRECTORY_SEPARATOR . $value ) ) {
                        $sub_files = self::scan_template_files( $path . DIRECTORY_SEPARATOR . $value );
                        foreach ( $sub_files as $sub_file ) {
                            $result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
                        }
                    } else {
                        if ( strpos( $value, '.php' ) !== false || strpos( $value, '.js' ) !== false ) {
                            $result[] = $value;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Checks the existence of a child file that overrides the parent.
     *
     * @param string $parent_dir The parent directory.
     * @param string $child_dir  The child directory.
     * @param array  $files      The files that need to be compared.
     *
     * @return void
     */
    private function check_overrides( $parent_dir, $child_dir, $files, $not_allowed_to_copy = false ) {
        foreach ( $files as $file ) {
            if ( file_exists( $child_dir . $file ) ) {
                $child_theme_file = $child_dir . $file;
            } else {
                $child_theme_file = false;
            }

            if ( ! empty( $child_theme_file ) ) {
                $core_file = $file;

                $core_version  = ( function_exists('et_get_file_version') ) ? et_get_file_version( $parent_dir . $core_file ) : '';
	            $child_version = ( function_exists('et_get_file_version') ) ? et_get_file_version( $child_theme_file ) : '';
                $file_name = str_replace( WP_CONTENT_DIR . '/themes/', '', $child_theme_file );

                if ( $core_version && ( empty( $child_version ) || version_compare( $child_version, $core_version, '<' ) ) ) {
                    $this->outdated_templates[] = $file_name;
                }
                $this->override_files[] = array(
                    'file'         => $file_name,
                    'version'      => $child_version,
                    'core_version' => $core_version,
                    'not_allowed' => $not_allowed_to_copy
                );
            }
        }
    }



    public function get_heading($text = '', $button = false, $id = false) {
        echo '<h2 class="etheme-page-title etheme-page-title-type-2"'.($id ? ' id="'.$id.'"' : '').'>' . $text . $button . '</h2>';
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.4.0
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}