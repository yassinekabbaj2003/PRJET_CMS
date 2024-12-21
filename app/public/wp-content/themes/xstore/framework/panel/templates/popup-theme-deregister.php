<?php
$domain = urldecode(et_get_current_domain());
$is_connection = etheme_api_connection_check();
?>
<div class="et_popup-theme-deregister et_panel-popup-inner text-left">
    <?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
			<div class="steps-block-content">
				<div class="popup-step step-1">
					<h3><?php echo esc_html__('Step-by-step guide on how to deactivate the XStore:', 'xstore'); ?></h3>
					<p><?php echo sprintf(__('By following these steps, you\'ll be able to successfully deactivate the XStore theme from <b>[%s]</b> domain using the 8theme dashboard', 'xstore'), $domain); ?></p>
                    <br/>
                    <ol>
                        <?php
                            foreach (array(
                                __('<b>Access Your Account:</b> Go to the 8theme website and log in to your account. If you don\'t have an account, you\'ll need to create one using your email and purchase code.', 'xstore'),
                                sprintf(__('<b>Navigate to Dashboard:</b> Once logged in, locate and click on the "Dashboard" section. You can usually find this in the top menu bar. Here\'s the direct link: %s.', 'xstore'),
                                '<span class="mtips mtips-top mtips-lg mtips-img"><a href="https://www.8theme.com/account/" target="_blank">https://www.8theme.com/account/</a><span class="mt-mes"><img src="' . esc_url( ETHEME_BASE_URI.'framework/panel/images/account.png' ) . '" alt="8theme account"></span></span>'),
                                sprintf(__('<b>Enter Purchase Code & Deactivate:</b> In your 8theme account, find %s enter your XStore purchase code, and %s using the provided option.', 'xstore'),
                                    '<span class="mtips mtips-top mtips-lg mtips-img"><a href="https://www.8theme.com/account/#etheme_show_8t_licenses_anchor" target="_blank">"Themeforest Licenses"</a><span class="mt-mes"><img src="' . esc_url( ETHEME_BASE_URI.'framework/panel/images/themeforest-licenses.png' ) . '" alt="themeforest license"></span></span>',
                                    '<span class="mtips mtips-top mtips-lg mtips-img">"<a href="https://www.8theme.com/account/">'.esc_html__('Deactivate License', 'xstore').'</a>"<span class="mt-mes"><img src="' . esc_url( ETHEME_BASE_URI.'framework/panel/images/deactivate-license.png' ) . '" alt="deactivate license"></span></span>'),
                            ) as $step ) {
                                echo '<li>' . $step . '</li>';
                            }
                        ?>
                    </ol>

                    <br>
                    <p>
                   <?php
                   $url = '<a href="https://www.8theme.com/contact-us/" target="_blank">'.esc_html__('8theme support team','xstore').'</a>';
                   echo __('If you encounter any issues or have further questions, don\'t hesitate to reach out to the ', 'xstore') . $url . __(' for assistance.', 'xstore');

                   ?>
                    </p>

					<?php if (!$is_connection) : ?>
						<div class="et-message et-error">
							<?php esc_html_e('We are unable to connect to the XStore API with the XStore theme. Please check your SSL certificate or white lists.', 'xstore'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
</div>
<p><br/>
    <a href="https://www.8theme.com/account/" class="et-button full-width no-loader tooltip-trigger popup-go-to-import" target="_blank">Go To 8theme website</a>
</p>