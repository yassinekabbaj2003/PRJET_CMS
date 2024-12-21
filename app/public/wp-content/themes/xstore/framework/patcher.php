<?php
/**
 * Description
 *
 * @package    patcher.php
 * @since      9.1
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if (!defined('ETHEME_FW')) {
    exit('No direct script access allowed');
}

class Etheme_Patcher
{

    public static $instance = null;

    private $patcher_api_url = 'https://8theme.com/patcher/';

    private $patcher_token = null;

    private $patcher_transient_key = 'xstore_patches';

    private $patcher_test_mode = false;

    private $patcher_api_connected = true;

    private $writable_files = true;

    public function __construct()
    {
        add_action('wp_ajax_xstore_refresh_patches', array($this, 'refresh_patches'));
        add_action('wp_ajax_nopriv_xstore_refresh_patches', array($this, 'refresh_patches'));
        add_action('wp_ajax_xstore_apply_patch', array($this, 'apply_patch'));
        add_action('wp_ajax_nopriv_xstore_apply_patch', array($this, 'apply_patch'));
        add_action('wp_ajax_xstore_apply_patch_all', array($this, 'apply_all_patches'));
        add_action('wp_ajax_nopriv_xstore_apply_patch_all', array($this, 'apply_all_patches'));
        add_action('after_switch_theme', array($this, 'reset_patches_cache'));
        add_filter('pre_set_site_transient_update_themes', array($this, 'set_update_transient'));
        $this->patcher_test_mode = isset($_GET['xstore-patches-test-mode']);
        $activated_data = get_option( 'etheme_activated_data' );
        $this->patcher_token = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
    }

    public function refresh_patches()
    {
        check_ajax_referer('refresh-patches', 'security');
        $this->reset_available_patches();
        $this->reset_patches_cache();

        echo wp_json_encode(
            array(
                'success' => true,
            )
        );
        exit;
    }

    /**
     * Reset patches caches site transient
     * @return void
     */
    public function reset_patches_cache()
    {
        delete_site_transient($this->patcher_transient_key);
    }

    /**
     * Reset available patches caches site transient
     */
    public function reset_available_patches()
    {
        delete_site_transient($this->patcher_transient_key . '_available');
    }

    /**
     * Reset applied patches caches site transient
     */
    public function reset_applied_patches()
    {
        delete_site_transient($this->patcher_transient_key . '_done');
    }

    /**
     * Reset patches caches on theme update
     * @param $transient
     * @return mixed
     */
    public function set_update_transient($transient)
    {
        $this->reset_patches_cache();
        $this->reset_available_patches();
        if ( apply_filters($this->patcher_transient_key . '_done_reset_on_update_themes', true) )
            $this->reset_applied_patches();
        return $transient;
    }

    public function get_available_patches($theme_version) {
        $available_patches = get_site_transient($this->patcher_transient_key . '_available', array());
        if ( $available_patches )
            return $available_patches;
        $all_patches = $this->get_patches($theme_version);
        if ( !$all_patches )
            $all_patches = array();
        else {
            // if there are patches according to current version
            if ( array_key_exists($theme_version, $all_patches) )
                $all_patches = array_keys($all_patches[$theme_version]);
            else
                $all_patches = array();
        }
        $available_patches = array_diff($all_patches, $this->get_applied_patches());
        set_site_transient($this->patcher_transient_key . '_available', $available_patches, DAY_IN_SECONDS);
        return $available_patches;
    }

    public function get_applied_patches() {
        $applied_patches = get_site_transient($this->patcher_transient_key . '_done', array());
        $applied_patches = !$applied_patches ? array() : json_decode($applied_patches, true);
        return !empty($applied_patches) ? array_merge(...array_values($applied_patches)) : $applied_patches;
    }

    /**
     * Getter for available patches
     * @param $theme_version
     * @return array
     */
    public function get_patches($theme_version, $on_test = false)
    {
        $test_mode = $on_test || $this->patcher_test_mode;
        if ( !$test_mode ) {
            $cached_patches = get_site_transient($this->patcher_transient_key, array());
            if ($cached_patches) {
                return $cached_patches;
            }
        }

        $patches_list = array();
        $patches = wp_remote_get(
            add_query_arg(array(
                'action' => 'get',
                'min_version' => $theme_version
            ), $this->patcher_api_url));
        $code     = wp_remote_retrieve_response_code($patches);

        if ($code!=200){
            $this->patcher_api_connected = false;
            return array();
        }

        $patches = wp_remote_retrieve_body($patches);
        $patches = json_decode($patches, true);
        if ($patches['status'] == 'success') {
            $patches_list = $patches['data'];
        }

        $ready_patches = array();
        // sort patches according to upload date/time
        usort($patches_list, function ($a, $b) {
            return new DateTime($a['last_update']) <=> new DateTime($b['last_update']);
        });
        foreach ($patches_list as $key => $value) {
//            if ( version_compare( $value['version'], $theme_version, '<' ) ) continue;
	        if ( !$test_mode && in_array($value['status'], array('inactive', 'delete')) ) continue;
            $files = json_decode($value['file_path'], true);
            if (!array_key_exists($value['version'], $ready_patches))
                $ready_patches[$value['version']] = array();

            $ready_files = array();
            foreach ($files as $file_id => $file_path) {
                $project = $file_path['base'];
                if (!array_key_exists($project, $ready_files))
                    $ready_files[$project] = array();
                $ready_files[$project][$file_id] = $file_path['path'];
            }
            unset($value['file_path']);
            $patch_info = $value;
            $patch_info['files'] = $ready_files;
            $ready_patches[$value['version']][$patch_info['code']] = $patch_info;
        }
        // sort patches by theme versions
        ksort($ready_patches);
        set_site_transient($this->patcher_transient_key, $ready_patches, DAY_IN_SECONDS);

        return $ready_patches;
    }

    public function get_patcher_heading($button = false) {
        echo '<h2 class="etheme-page-title etheme-page-title-type-2">' . esc_html__('XStore Patcher', 'xstore') . $button . '</h2>';
    }
    /**
     * Patches html for XStore Control panel -> Patcher tab
     * @param $theme_version
     * @return void
     */
    public function get_patches_list($theme_version = '1.0.0')
    {
        $global_admin_class = EthemeAdmin::get_instance();
        $patches = $this->get_patches($theme_version);
        if ( !$this->patcher_api_connected ) {
            $this->get_patcher_heading();
            echo '<p class="et-message et-error">'.esc_html__('We are unable to connect to the XStore API with the XStore theme. Please check your SSL certificate or white lists.', 'xstore').'</p>';
            return;
        }
        if (!count($patches)) {
            $this->get_patcher_heading('<span class="et-button refresh-patches"
                      data-nonce="'.wp_create_nonce('refresh-patches').'">
                    '.$global_admin_class->get_loader(false, false).'
                    <span class="dashicons dashicons-image-rotate"></span>'. ' ' .
                    '<span>'.esc_html__('Check for updates', 'xstore').'</span>'.
                '</span>');
            echo '<p>' . sprintf(esc_html__('Currently, you have installed the most recent version. There are no updates or patches available at this time. In the unlikely event that you encounter any bugs, please reach out to our support team through the provided %slink%s and we will work to resolve the issue promptly.', 'xstore'), '<a href="'.etheme_support_forum_url().'" rel="nofollow" target="_blank">', '</a>') . '</p>';
            ?>
            <?php
            return;
        }
        $this->get_patcher_heading('<span class="et-button refresh-patches"
                  data-nonce="'.wp_create_nonce('refresh-patches').'">
                '.$global_admin_class->get_loader(false, false).'
                <span class="dashicons dashicons-image-rotate"></span>'.
                '<span>'.esc_html__('Refresh patches', 'xstore').'</span>'.
            '</span>');
        echo '<div class="etheme-div etheme-patcher-list etheme-table-style-2">';
        echo '<p>' .
            sprintf(esc_html__('Unleash the full potential of your website with The Parcher! With its unique ability to apply small fixes between XStore releases, you can now keep your site up-to-date and running smoothly. In the unlikely event that you encounter any bugs, please reach out to our support team through the provided %slink%s and we will work to resolve the issue promptly.', 'xstore'),
                '<a href="'.etheme_support_forum_url().'" rel="nofollow" target="_blank">', '</a>') .
            '</p>';
        echo '<p class="et-message et-info">' .
            esc_html__('We recommend that you make backups of your website before making any changes. After applying patches, please remember to clear your website caches and CDN in order to see the changes take effect.', 'xstore') .
            '</p>';
        $this->patches_info = $this->get_patches_info($patches);
        $limit = 15;
        $info_alert = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M12 0.024c-6.6 0-11.976 5.376-11.976 11.976s5.376 11.976 11.976 11.976 11.976-5.376 11.976-11.976-5.376-11.976-11.976-11.976zM12 22.056c-5.544 0-10.056-4.512-10.056-10.056s4.512-10.056 10.056-10.056 10.056 4.512 10.056 10.056-4.512 10.056-10.056 10.056zM12.24 4.656h-0.48c-0.48 0-0.84 0.264-0.84 0.624v8.808c0 0.336 0.36 0.624 0.84 0.624h0.48c0.48 0 0.84-0.264 0.84-0.624v-8.808c0-0.336-0.36-0.624-0.84-0.624zM12.24 16.248h-0.48c-0.456 0-0.84 0.384-0.84 0.84v1.44c0 0.456 0.384 0.84 0.84 0.84h0.48c0.456 0 0.84-0.384 0.84-0.84v-1.44c0-0.456-0.384-0.84-0.84-0.84z"></path></svg>';
        ob_start();
        ?>
        <div class="etheme-patcher-table-wrapper">
            <table>
                <thead>
                <tr>
                    <th class="patch-id">
                        <?php esc_html_e('Patch ID', 'xstore'); ?>
                    </th>
                    <th class="patch-description">
                        <?php esc_html_e('Description', 'xstore'); ?>
                    </th>
                    <th class="patch-date">
                        <?php esc_html_e('Data', 'xstore'); ?>
                    </th>
                    <th class="patch-action">
                        {{apply_all_patches_button}}
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $available_patches_count = 0;
                foreach ($this->patches_info as $patch_info) {
                    $description = html_entity_decode($patch_info['description']);
                    $description_tooltip = strlen($description) > 100;
                    $description_class = $description_tooltip ? ' mtips mtips-lg helping' : '';
                    if ( $description_tooltip ) {
                        $description_title         = substr($description,0,30) . '... (<span class="patch-more-details">' . esc_html__('More details', 'xstore') . '</span>)';
                        $description = $description_title . '<span class="mt-mes">' . $description . '</span>';
                    }
                    $classes = array();
                    if ( !$patch_info['active_status'] )
                        $classes[] = 'patch-test-mode';
                    if ( $limit < 1 )
                        $classes[] = 'hidden'; ?>
                    <tr class="<?php echo implode(' ', $classes); ?>">
                        <?php echo '<td class="patch-id">#' . $patch_info['patch_id'] . '</td>'; ?>
                        <?php echo '<td class="patch-description"><span class="patch-description-content'.$description_class.'">' . $description . '</span></td>'; ?>
                        <?php echo '<td class="patch-date"><span class="patch-date-info">' . $patch_info['last_update'][0] . '</span><span class="patch-time-info">' . $patch_info['last_update'][1] . '</span></td>'; ?>
                        <td class="patch-action">
                            <?php
                            if ($patch_info['applied']) {
                                echo '<span class="patch-unavailable success">' .
                                    '<svg width="1em" height="1em" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5 0C2.01911 0 0 2.01911 0 4.5C0 6.98089 2.01911 9 4.5 9C6.98089 9 9 6.98089 9 4.5C9 2.01911 6.98089 0 4.5 0ZM4.5 8.2666C2.41751 8.2666 0.7334 6.5825 0.7334 4.5C0.7334 2.41751 2.41751 0.7334 4.5 0.7334C6.5825 0.7334 8.2666 2.41751 8.2666 4.5C8.2666 6.5825 6.5825 8.2666 4.5 8.2666ZM6.80885 2.85211C6.70926 2.84306 6.6006 2.87928 6.52817 2.95171L3.85714 5.54125L2.47183 4.11972C2.3994 4.04728 2.2998 4.01107 2.19115 4.01107C2.0825 4.01107 1.9829 4.05634 1.92857 4.14688C1.86519 4.22837 1.82897 4.33702 1.83803 4.43662C1.84708 4.51811 1.8833 4.5996 1.94668 4.64487L3.58551 6.33803C3.65795 6.41046 3.74849 6.44668 3.84809 6.44668C3.93863 6.44668 4.02918 6.41046 4.10161 6.33803L7.02616 3.48592C7.09859 3.41348 7.13481 3.31388 7.13481 3.20523C7.13481 3.11469 7.09859 3.02414 7.03521 2.96982C6.98089 2.89738 6.8994 2.86117 6.80885 2.85211Z" fill="currentColor"/>
                                    </svg>' . esc_html__('Applied', 'xstore') . '</span>';
                            } elseif (count($patch_info['requirements'])) {
                                echo '<span class="patch-unavailable mtips mtips-lg mtips-left">' . $info_alert . esc_html__('Read details', 'xstore') .
                                    '<span class="mt-mes">' . implode("\n", $patch_info['requirements']) . '</span></span>';
                            }
                            else {
                                $files_from_previous_patches = array();
                                $all_patches = $this->patches_info;
                                $all_patches = array_reverse($all_patches);
                                $prev_patch_id = false;
                                $patch_same_files = array();
                                foreach ($all_patches as $patch_local_info) {
                                    // skip patches which were already applied
                                    if ($patch_local_info['applied']) continue;
                                    if ($patch_local_info['patch_id'] === $patch_info['patch_id']) break;

                                    // showing which patches should be applied first
                                    if (count(array_intersect($patch_local_info['files_inline'], $patch_info['files_inline'])))
                                        $patch_same_files[] = $patch_local_info['patch_id'];
                                }
                                // showing which patches should be applied first
                                if (!$this->patcher_test_mode && count($patch_same_files) && $prev_patch_id = $patch_same_files[array_key_first($patch_same_files)]) {
                                    echo
                                        (!$patch_info['active_status'] ? '<sub>'.esc_html__('Test mode', 'xstore').'</sub> ' : '') .
                                        '<span class="et-button et-button-grey2 mtips mtips-left required-patch no-transform no-loader no-hover" data-patch_id="' . $patch_info['patch_id'] . '" data-required_patch="' . $prev_patch_id . '">' .
                                        esc_html__('Apply', 'xstore') .
                                        '<span class="mt-mes">' . sprintf(esc_html__('Apply %s patch first', 'xstore'), '#' . $prev_patch_id) . ( !$patch_info['active_status'] ? '. ' . sprintf(esc_html__(' %s patch should have active status first', 'xstore'), '#' . $prev_patch_id) : '' ) . '</span></span>';
                                }
                                // check if current theme version is ok for patch's theme version
                                // could work without this condition also but better to check to prevent global changes in files
                                elseif ( !$this->patcher_test_mode && version_compare($patch_info['theme_version'], $theme_version, '>') ) {
                                    echo '<span class="et-button et-button-grey2 mtips mtips-left mtips-lg required-version no-transform no-loader no-hover" data-required_theme_version="' . $patch_info['theme_version'] . '">' .
                                        esc_html__('Apply', 'xstore') .
                                        '<span class="mt-mes">' . sprintf(__('This patch is available for XStore theme version %1s or later. Please update your %2s before proceeding.', 'xstore'), $patch_info['theme_version'], '<a href="https://www.youtube.com/watch?v=kPo0fiNY4to" rel="nofollow" target="_blank">'.esc_html__('XStore theme', 'xstore').'</a>') . '</span></span>';
                                }
                                else {
                                    // data-files will be shown in alert before applied -> These files will be overwritten on your server ...
                                    $files_info_attributes = array();
                                    foreach ($patch_info['files'] as $files_project => $patch_project_file) {
                                        if (!array_key_exists($files_project, $files_info_attributes))
                                            $files_info_attributes[$files_project] = array();

                                        $files_info_attributes[$files_project][] = $patch_project_file;
                                    }
                                    // strange patch with no files changed but let's better leave this condition here
                                    // could be useful as fix for some cases when patch was added for update but no files added for this patch
                                    if (!count($files_info_attributes))
                                        echo '<span class="patch-unavailable mtips mtips-left">' . $info_alert . esc_html__('Read details', 'xstore') .
                                            '<span class="mt-mes">' . esc_html__('This patch is currently unavailable. Please ignore it.', 'xstore') . '</span></span>';
                                    else {
                                        $available_patches_count++;
                                        echo (!$patch_info['active_status'] ? '<sub>'.esc_html__('Test mode', 'xstore').'</sub> ' : '') .
                                            '<button class="et-button et-button-green no-transform apply-patch et-button-arrow" data-patch_id="' . $patch_info['patch_id'] . '" data-theme_version="' . $patch_info['theme_version'] . '" data-files=\'' . json_encode($files_info_attributes) . '\'>' .
                                            '<span class="et-loader">
                                                    <svg class="loader-circular" viewBox="25 25 50 50">
                                                        <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                                                    </svg>
                                                </span>' .
                                            esc_html__('Apply', 'xstore') .
                                            '<svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 32 32">
                                                  <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                                    <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                                    <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                                                  </g>
                                                </svg>' .
                                            '</button>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $limit--;
                }
                ?>
                </tbody>
            </table>
        </div>

        <?php

        if ($limit < 1) { ?>
            <p class="text-center">
                <span class="et-button load-patches">
                    <span class="et-loader">
                        <svg class="loader-circular" viewBox="25 25 50 50">
                            <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2"
                                    stroke-miterlimit="10"></circle>
                        </svg>
                    </span>
                    <?php esc_html_e('Load more', 'xstore'); ?>
                </span>
            </p>
        <?php }
        $apply_all_button = esc_html__('Action', 'xstore');

        if ( $available_patches_count > 0 ) {
            $apply_all_button =
                '<button class="et-button apply-patch no-transform" data-patch_id="all" data-files="all" data-theme_version="' . $theme_version . '">' .
                '<span class="et-loader">
                                        <svg class="loader-circular" viewBox="25 25 50 50">
                                            <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                                        </svg>
                                    </span>' .
                esc_html__('Apply all patches', 'xstore') .
                '</button>';
        }

        echo '</div>'; // end .etheme-patcher-list
        echo str_replace('{{apply_all_patches_button}}', $apply_all_button, ob_get_clean());
    }

    /**
     * Generate information data for each patches set from args
     * @param $patches
     * @return array
     */
    public function get_patches_info($patches, $on_test = false)
    {
        $patch_index = array();
        $ready_patches = array();
        $test_mode = $on_test || $this->patcher_test_mode;
        $all_applied_patches_keys = $this->get_applied_patches();
        foreach ($patches as $patches_theme_version => $theme_version_patches_list) {
            $patch_index[$patches_theme_version] = 0;
            foreach ($theme_version_patches_list as $patch) {
                if ( !$test_mode && $patch['status'] == 'inactive') continue;
                // hide patches for the lower theme versions than current theme version, useful for cached patches
                if ( !$test_mode && version_compare($patches_theme_version, ETHEME_THEME_VERSION, '<')) continue;
                $patch_index[$patches_theme_version]++;
                $files = $patch['files'];
                $patch_id = $patch['code'];

                $patch_details = array(
                    'theme_version' => $patches_theme_version,
                    'patch_id' => $patch_id,
                    'patch_index' => $patch_index[$patches_theme_version],
                    'active_status' => $patch['status'] == 'active',
                    'files' => array(),
                    'files_inline' => array(),
                    'description' => $patch['description'],
                    'last_update' => explode(' ', $patch['last_update']),
                    'requirements' => array(),
                    'applied' => false
                );

                if (in_array($patch_id, $all_applied_patches_keys)) {
                    $patch_details['applied'] = true;
                }

                foreach ($files as $project_key => $project_files) {
                    $patch_details['files'][$project_key] = $project_files;
                    foreach ($project_files as $project_file_id => $project_file_path) {
                        $patch_details['files_inline'][$project_file_id] = $project_key . '/' . $project_file_path;
                    }
                }
                $patch_details['files_inline'] = array_unique($patch_details['files_inline']);


                if (isset($patch['requirements'])) {
                    foreach ($patch['requirements'] as $req_project_key => $project_requirements) {
                        if (isset($project_requirements['min_version'])) {
                            $project_name = false;
                            $project_version = false;
                            $project_type = 'plugin';
                            switch ($req_project_key) {
                                case 'theme':
                                    $project_name = 'XStore';
                                    $project_version = ETHEME_THEME_VERSION;
                                    $project_type = 'theme';
                                    break;
                                case 'et-core-plugin':
                                    if (defined('ET_CORE_VERSION')) {
                                        $project_name = 'XStore Core';
                                        $project_version = ET_CORE_VERSION;
                                    }
                                    break;
                                case 'xstore-advanced-sticky-header':
                                    if (defined('XStore_Advanced_Sticky_Header_Version')) {
                                        $project_name = 'XStore Advanced Sticky Header';
                                        $project_version = XStore_Advanced_Sticky_Header_Version;
                                    }
                                    break;
                            }
                            if ($project_name && $project_version) {
                                if (version_compare($project_version, $project_requirements['min_version'], '<')) {
                                    if ($project_type == 'theme')
                                        $patch_details['requirements'][$req_project_key] = sprintf(esc_html__('You are using %s version of %s theme but for this patch you need to have at least %s version', 'xstore'), $project_version, '<a href="' . admin_url('update-core.php') . '">' . $project_name . '</a>', $project_requirements['min_version']);
                                    else
                                        $patch_details['requirements'][$req_project_key] = sprintf(esc_html__('You are using %s version of %s plugin but for this patch you need to have at least %s version', 'xstore'), $project_version, '<a href="' . admin_url('update-core.php') . '">' . $project_name . '</a>', $project_requirements['min_version']);
                                }
                            }
                        }
                    }
                }

                array_unshift($ready_patches, $patch_details);
            }

        }
        return $ready_patches;
    }

    /**
     * Getter of patch file's content
     * @param $patch_id
     * @param $file_id
     * @return mixed|string
     */
    public function get_patch_file_from_server($patch_id, $file_id)
    {
	    add_filter( 'http_request_args', 'et_increase_http_request_timeout', 10, 2 );
        $url = add_query_arg(array(
            'action' => 'file-get',
            'code' => $patch_id,
            'token' => $this->patcher_token,
            'fid' => $file_id
        ), $this->patcher_api_url);

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array(
                'message' => $response->get_error_message(),
                'status' => 'error',
            );
        }

        if (!isset($response['body'])) {
            return array(
                'message' => $response['response']['code'] . ': ' . $response['response']['message'],
                'status' => 'error',
            );
        }

        $response = json_decode(html_entity_decode($response['body']), true);

        if (isset($response['status']) && $response['status'] == 'error' && isset($response['data'])) {
            return array(
                'message' => $response['data'],
                'status' => 'error',
            );
        }

        if (!isset($response['content'])) {
            return array(
                'message' => esc_html__('Error', 'xstore') . ': ' . sprintf(esc_html__('We would like to inform you that the file you have downloaded has no content. Please kindly inform us via our contact page %1s so that we can fix the issue immediately.', 'xstore'), etheme_contact_us_url()),
                'status' => 'error',
            );
        }

        return array(
            'content' => $response['content'],
            'status' => 'success',
        );
    }

    /**
     * Applier of the patch
     * @return void
     */
    public function apply_patch($patch_local_id = null, $patch_files = [], $multiple = false, $on_test = false)
    {
        $errors = array();
        if ( !current_user_can( 'manage_options' ) || ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), $this->patcher_transient_key . '_apply_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            $errors['server_errors'] = array();
            $errors['server_errors'][] = __( 'You are not allowed to complete this task due to invalid nonce validation.', 'xstore' );

            echo wp_json_encode(
                array(
                    'success' => false,
                    'server_errors_text' => esc_html__('Invalid nonce', 'xstore'),
                    'errors' => $errors
                )
            );
            exit;
        }
        $patch_id = isset($_POST['patch_id']) ? $_POST['patch_id'] : $patch_local_id;
        $files = isset($_POST['files']) ? $_POST['files'] : $patch_files;
        $test_mode = isset($_POST['test_mode']) ? $_POST['test_mode'] : $on_test;
        if (!$patch_id && !$multiple) {
            echo wp_json_encode(
                array(
                    'success' => false,
                    'errors_text' => __('This patch does not exist or was removed. Please try again later.', 'xstore'),
                    'errors' => $errors
                )
            );
            exit;
        }
        foreach ($files as $project => $project_files) {
            // in some cases it returns array in array
            $project_files = isset($project_files[0]) ? $project_files[0] : $project_files;
            foreach ($project_files as $project_files_key => $project_file_path) {
                $content = $this->get_patch_file_from_server($patch_id, $project_files_key);
                if ( $content['status'] != 'success' ) {
                    $this->writable_files = false;
                    if (!array_key_exists('server_errors', $errors))
                        $errors['server_errors'] = array();
                    $errors['server_errors'][] = $content['message'];
                    continue;
                }

                if (!$content['content']) {
                    $this->writable_files = false;
                    continue;
                }

                if (!$this->write_file($project, $project_file_path, $content['content'])) {
                    if (!array_key_exists($project, $errors))
                        $errors[$project] = array();
                    $errors[$project][] = $project_file_path;
                }
            }
        }
        if (count($errors) < 1 && !$test_mode) {
            $theme_version = $_POST['theme_version'];
            $applied_patches = get_site_transient('xstore_patches_done', array());
            $applied_patches = !$applied_patches ? array() : json_decode($applied_patches, true);
            if (!array_key_exists($theme_version, $applied_patches)) {
                $applied_patches[$theme_version] = array();
            }
            $applied_patches[$theme_version][] = $patch_id;
            set_site_transient('xstore_patches_done', json_encode($applied_patches));
            $this->reset_available_patches();
        }
        if ($multiple) {
            return $errors;
        } else {
            echo wp_json_encode(
                array(
                    'success' => count($errors) < 1,
                    'errors_text' => __('Some files were unable to be modified on your server. Please check your "System Status" page or try again later.', 'xstore'),
                    'server_errors_text' => __('There seems to be an issue with the server request. Please ensure that your theme has a valid purchase code.', 'xstore'),
                    'system_requirements_link' => add_query_arg(array('page' => 'et-panel-system-requirements'), admin_url('admin.php')),
                    'errors' => $errors
                )
            );
            exit;
        }
    }

    public function apply_all_patches()
    {
        if ( !current_user_can( 'manage_options' ) || ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), $this->patcher_transient_key . '_apply_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, it's used only for nonce verification
            $errors = array();
            $errors['server_errors'] = array();
            $errors['server_errors'][] = __( 'You are not allowed to complete this task due to invalid nonce validation.', 'xstore' );

            echo wp_json_encode(
                array(
                    'success' => false,
                    'server_errors_text' => esc_html__('Invalid nonce', 'xstore'),
                    'errors' => $errors
                )
            );
            exit;
        }
        $test_mode = $_POST['test_mode'];
        $patches = $this->get_patches($_POST['theme_version'], $test_mode);
        $patches_info = array_reverse($this->get_patches_info($patches, $test_mode));
        $success_patches = array();
        $broken_patches = array();
        $broken_patches_files = array();
        foreach ($patches_info as $patch_info) {
            // check if current theme version is ok for patch's theme version
            // could work without this condition also but better to check to prevent global changes in files
            if ( !$test_mode && version_compare($patch_info['theme_version'], $_POST['theme_version'], '>') )
                continue;
            // there are no sense to re-apply already applied patches
            if ($patch_info['applied'])
                continue;
            // don't apply patches with inactive status
            if (!$test_mode && !$patch_info['active_status'])
                continue;
            // if some files from previous patches were broken then prevent next patches action to rewrite same files
            if (count(array_intersect($broken_patches_files, $patch_info['files_inline']))) {
                continue;
            }

            $test = $this->apply_patch($patch_info['patch_id'], $patch_info['files'], true, $test_mode);

            if (count($test)) {
                $broken_patches[] = '#' . $patch_info['patch_id'];
                $broken_patches_files = array_unique(array_merge($broken_patches_files, $patch_info['files_inline']));
            }
            else {
                $success_patches[] = $patch_info['patch_id'];
            }
        }
        $this->reset_available_patches();
        echo wp_json_encode(
            array(
                'success' => count($broken_patches) < 1,
                'success_text' => __('Congratulations, all patches have been successfully applied.', 'xstore'),
                'success_patches' => $success_patches,
                'server_errors_text' => __('There is a problem with the request to get files\' content. Please ensure that your theme has a valid purchase code.', 'xstore'),
                'errors_text' => __('We apologize for the inconvenience, but it seems that some patches could not be applied. Please make sure to check your "System Status" page or try again later. Thank you.', 'xstore'),
                'system_requirements_link' => add_query_arg(array('page' => 'et-panel-system-requirements'), admin_url('admin.php')),
                'errors' => $broken_patches
            )
        );
        exit;
    }

    /**
     * Write file content on server with the content from patch's file
     * @param $project_type
     * @param $file_path
     * @param $content
     * @return bool
     */
    public function write_file($project_type, $file_path, $content)
    {
        global $wp_filesystem;

        if (function_exists('WP_Filesystem')) {
            WP_Filesystem();
        }

        $target = $project_type == 'xstore' ? get_template_directory() . wp_normalize_path('/' . $file_path) :
            WP_PLUGIN_DIR . wp_normalize_path('/' . $project_type . '/' . $file_path);

        // create folders if the ones didn't exist yet
        if (!$wp_filesystem->exists($target)) {
            $pos = strripos($target, '/');
            if (!wp_mkdir_p(substr($target, 0, $pos))) {
                return false;
            }
        }
        $status_write_file = $wp_filesystem->put_contents($target, $content);

        if (!$status_write_file) {
            $this->successful_write_files = false;
            return false;
        }

        return true;
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  9.1.1
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }

}

$patcher = new Etheme_Patcher();