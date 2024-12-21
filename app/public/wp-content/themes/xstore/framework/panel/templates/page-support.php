<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Template "Support" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.1
 */
new YouTube();
if ( isset( $_POST['et_YouTube'] ) && count( $_POST['et_YouTube'] ) ) {
	$videos = $_POST['et_YouTube'];
} else {
	$videos = false;
}

$documentation = get_option('et_documentation_beacon', false);

?>
    <div class="etheme-support">
        <h2 class="etheme-page-title etheme-page-title-type-2"><?php esc_html_e('Tutorials & Support', 'xstore'); ?></h2>

        <h3 class="et-title"><?php esc_html_e( 'Video tutorials', 'xstore' ); ?></h3>
        <div class="etheme-videos-wrapper-new">
            <div class="etheme-videos xstore-panel-grid-wrapper loading">
				<?php
				if ( ! $videos ) {
					echo '<p class="et-message et-error" style="width: 100%; margin: 0 20px;">' .
					     esc_html__( 'Can not connect to youtube API to show video tutorials', 'xstore' ) .
					     '</p>';
				} else {
					$i = 0;
					foreach ( $videos as $key => $value ) {
						$i ++;
						?>
                            <div class="xstore-panel-grid-item etheme-video<?php echo ( $i <= 6 ) ? '' : ' hidden'; ?>">
                                <div class="xstore-panel-grid-item-content">
                                    <div class="xstore-panel-grid-item-image">
                                        <div id="player-<?php echo esc_attr($key); ?>"></div>
                                    </div>
                                    <div class="xstore-panel-grid-item-info">
                                        <span class="xstore-panel-grid-item-name">
                                             <?php echo esc_html($value['title']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php
						
					}
				}
				?>
            </div>
        </div>
		<?php if ( $videos ): ?>
            <div class="text-center">
                <br/>
                <a href="https://www.youtube.com/channel/UCiZY0AJRFoKhLrkCXomrfmA"
                   class="et-button no-loader more-videos last-button"
                   target="_blank"><?php esc_html_e( 'View more videos', 'xstore' ); ?></a>
            </div>
		<?php endif; ?>
        <br/>
        <br/>
        <br/>

        <h3><?php esc_html_e( 'Help and support', 'xstore' ); ?></h3>
        <p><?php esc_html_e( 'If you encounter any difficulties with our product we are ready to assist you via:', 'xstore' ); ?></p>
        <ul class="support-blocks">
            <li>
                <a href="https://t.me/etheme" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/telegram.png">
                    <span><?php esc_html_e( 'Telegram channel', 'xstore' ); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php etheme_support_forum_url(true); ?>" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/support-icon.png">
                    <span><?php esc_html_e( 'Support Forum', 'xstore' ); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/themeforest.jpg" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/envato-icon.png">
                    <span><?php esc_html_e( 'ThemeForest profile', 'xstore' ); ?></span>
                </a>
            </li>
        </ul>
        <div class="support-includes">
            <div class="includes">
                <p><?php esc_html_e( 'Item Support includes:', 'xstore' ); ?></p>
                <ul>
                    <li><?php esc_html_e( 'Answering technical questions', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Assistance with reported bugs and issues', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Help with bundled 3rd party plugins', 'xstore' ); ?></li>
                </ul>
            </div>
            <div class="excludes">
                <p><?php _e( 'Item Support <span class="red-color">DOES NOT</span> Include:', 'xstore' ); ?></p>
                <ul>
                    <li><?php esc_html_e( 'Customization services', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Installation services', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Support for non-bundled 3rd party plugins.', 'xstore' ); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <br/>
    <h3 class="et-title"><?php esc_html_e( 'XStore Documentation Beacon', 'xstore' ); ?></h3>

    <div>
        <p><?php esc_html_e('With this option, you can disable or enable the XStore assistant in the admin panel and customizer of the XStore theme.', 'xstore'); ?></p>
        <p>
            <label class="et-panel-option-switcher <?php if ( $documentation !== 'off' ) { ?> switched<?php } ?>" for="et_documentation">
                <input type="checkbox" id="et_documentation" name="et_documentation" <?php if ( $documentation !== 'off' ) { ?>checked<?php } ?>>
                <span></span>
            </label>
        </p>
    </div>
    <br/>
    <div class="xstore-panel-info-block">
        <svg width="23" height="29" viewBox="0 0 23 29" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0V29H22.3077V7.35457L21.994 7.00601L15.3017 0.313702L14.9531 0H0ZM2.23077 2.23077H13.3846V8.92308H20.0769V26.7692H2.23077V2.23077ZM15.6154 3.83413L18.4736 6.69231H15.6154V3.83413ZM5.57692 11.1538V13.3846H16.7308V11.1538H5.57692ZM5.57692 15.6154V17.8462H16.7308V15.6154H5.57692ZM5.57692 20.0769V22.3077H16.7308V20.0769H5.57692Z"/>
        </svg>
        <div>
            <h3><?php echo esc_html__('Documentation', 'xstore'); ?></h3>
            <p><?php echo esc_html__('In our theme documentation, you\'ll find answers to your questions and detailed instructions on how to use the theme.', 'xstore'); ?></p>
            <p><a href="<?php etheme_documentation_url() ?>" target="_blank"><span class="dashicons dashicons-external"></span> <?php echo esc_html__('Go to Documentation', 'xstore'); ?></a></p>
        </div>
    </div>
<?php if ( $videos ) : ?>
    <script>
        // This code loads the IFrame Player API code asynchronously.
        let tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        let firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // This function creates an <iframe> (and YouTube player) after the API code downloads.
        const players = [];

        function onYouTubeIframeAPIReady() {
			<?php foreach ($videos as $key => $value) : ?>
            var player;

            player = new YT.Player('player-<?php echo esc_attr( $key ); ?>', {
                height: '270',
                width: '480',
                videoId: '<?php echo esc_attr( $value['id'] ); ?>',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });

            players.push(player);
			<?php endforeach; ?>
        }

        // The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            let i = <?php echo esc_js( $i ); ?>;
            players.forEach(function (e, t) {
                if (t + 1 == i) {
                    setTimeout(removeLoading, 500);
                }
            });
        }

        // Remove loading class from etheme-videos DOM element
        function removeLoading(event) {
            jQuery('.etheme-videos').removeClass('loading');
        }

        // The API calls this function when the player's state changes. Stop played video
        function onPlayerStateChange(event) {
            players.forEach(function (e, t) {
                if (event.target != e && event.data == 1) {
                    e.pauseVideo();
                }
            });
        }
    </script>
<?php endif; ?>