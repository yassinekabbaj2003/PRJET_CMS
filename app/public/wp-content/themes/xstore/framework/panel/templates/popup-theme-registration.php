<?php

$domain = urldecode(et_get_current_domain());

$is_staging = et_check_domain_pattern($domain);
$is_connection = etheme_api_connection_check();

?>

<div class="et_popup-theme-registration et_panel-popup-inner with-scroll">
	<?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
    <div class="steps-block-content">
        <div class="popup-step step-1">
            <h3><?php echo esc_html__('Domain Usage Statement', 'xstore'); ?></h3>

            <p>
<!--				--><?php //echo 'When choosing how to use this <b>['.$domain.']</b> domain, it\'s essential to consider your specific objectives:'; ?>
	            <?php echo 'When choosing how to use this <b>['.$domain.']</b> domain, it\'s important to think about your specific goals:'; ?>

            </p>

            <!--                    <p>When choosing how to use this <strong>--><?php //echo $domain;?><!--</strong>, it's essential to consider your specific objectives:</p>-->


            <div class="domain-selectors">
                <div class="domain-selector<?php //echo (!$is_staging) ? " active": ""; ?>">
                    <h4>
                        <input type="radio" id="et_register_domain_type_live" name="domain_type" value="live" <?php //echo (!$is_staging) ? "checked": ""; ?>  />
                        <label for="et_register_domain_type_live"><?php echo esc_html__('Live Website 1(SITE)', 'xstore'); ?></label>
                    </h4>
                    <p><?php echo esc_html__('It\'s when your website is up and running for everyone to visit, just like a shop with its doors open.', 'xstore') ?></p>
                </div>
                <div class="domain-selector<?php //echo ($is_staging) ? " active": ""; ?>">
                    <h4>
                        <input type="radio" id="et_register_domain_type_staging" name="domain_type" value="staging" <?php //echo ($is_staging) ? "checked": ""; ?>  />
                        <label for="et_register_domain_type_staging"><?php echo esc_html__('Staging Website 2(SITES)', 'xstore'); ?></label>
                    </h4>
                    <p><?php echo esc_html__('This option enables you to experiment,  and troubleshoot issues without impacting your live website.', 'xstore'); ?></p>
                </div>
            </div>


			<?php if (!$is_connection) : ?>
                <div class="et-message et-error">
					<?php esc_html_e('We are unable to connect to the XStore API with the XStore theme. Please check your SSL certificate or white lists.', 'xstore'); ?>
                </div>
			<?php endif; ?>

			<?php if(!et_check_domain_pattern($domain)) : ?>
                <div class="et-message et-error staging-domain-error hidden">
                    <p>
                        <strong>
                            <?php echo esc_html__('Upon our review, it appears that the provided domain ', 'xstore'); ?>
                            <?php echo esc_html('['.$domain.']'); ?>
                            <?php echo esc_html__(' does not match our staging domain patterns.', 'xstore'); ?>
                        </strong>
                    </p>
                    <p><?php echo sprintf(
							esc_html__('So, for example, if you choose the pattern %s or %s, your staging site URL would look like this: %s OR %s', 'xstore'),
							'<strong>"dev."</strong>',
							'<strong>"test."</strong>',
							'<strong>dev.yourdomain.com</strong>',
							'<strong>test.yourdomain.com</strong>'
						); ?></p>
                    <p><?php echo sprintf(esc_html__('You can find the full list of our patterns and detailed instructions on how to register XStore for [staging websites/ live website] in
                            %sOur Documentation Guide%s', 'xstore'), '<a href="'.etheme_documentation_url('163-how-to-registerderegister-xstore-theme-livestaging-websites', false).'" target="_blank">', '</a>'); ?></p>
                </div>
			<?php else: ?>
                <div class="et-message et-error live-domain-error hidden">
                    <p>
                        <strong>
                            <?php echo esc_html__('Upon our review, it appears that the provided domain ', 'xstore'); ?>
                            <?php echo esc_html('['.$domain.']'); ?>
                            <?php echo esc_html__(' does not match our live domain patterns.', 'xstore'); ?>
                        </strong>
                    </p>
                    <p><?php echo sprintf(esc_html__('You can find the full list of our patterns and detailed instructions on how to register XStore for [staging websites/ live website] in
                            %sOur Documentation Guide%s', 'xstore'), '<a href="'.etheme_documentation_url('163-how-to-registerderegister-xstore-theme-livestaging-websites', false).'" target="_blank">', '</a>'); ?></p>
                </div>
			<?php endif; ?>

        </div>

        <div class="popup-step step-2 hidden">
            <h3><?php echo esc_html__('Important Registration Guidelines', 'xstore'); ?></h3>
            <p><?php echo esc_html__('Before proceeding with the registration of the XStore theme, please take a moment to review and agree to the following important guidelines:', 'xstore'); ?></p>
            <div class="section-block step-1">
                <label class="et-panel-option-switcher" for="deregister-domain-section-1">
                    <input type="checkbox" class="checkbox-steps" id="deregister-domain-section-1"/>
                    <span></span>
                </label>

                <p class="section-text">

					<?php echo sprintf(__('<b>License Policy:</b> When registering the XStore theme, it\'s crucial to understand %s ThemeForest\'s license policy %s. With a regular license, you can use XStore on 1 (ONE) domain. However, we offer activation for auto updates on 3 (THREE) domains: 2 (TWO) for staging (development) sites and 1 (ONE) for your live website.', 'xstore'),
						'<a href="https://themeforest.net/licenses/terms/regular" target="_blank">', '</a>'); ?>
<!--                    <br>-->
<!--                    <br>-->
<!--                    --><?php //echo sprintf(__('<b>Data Storage Consent:</b> By clicking "Register," I agree to let %s store my purchase code and user data for secure project management. If I need to check or remove active domains, I can do it on the %s website.', 'xstore'),
//						'<a href="https://8theme.com/" target="_blank" rel="nofollow">8theme.com</a>', '<a href="https://www.8theme.com/account/" target="_blank" rel="nofollow">8theme.com</a>'); ?>
                </p>
            </div>

            <div class="section-block step-2 invisible">
                <label class="et-panel-option-switcher" for="deregister-domain-section-2">
                    <input type="checkbox" class="checkbox-steps" id="deregister-domain-section-2"/>
                    <span></span>
                </label>
                <p class="section-text">
		            <?php echo sprintf(__('<b>Data Storage Consent:</b> By clicking "Register," I agree to let %s store my purchase code and user data for secure project management. If I need to check or remove active domains, I can do it on the %s website.', 'xstore'),
			            '<a href="https://8theme.com/" target="_blank" rel="nofollow">8theme.com</a>', '<a href="https://www.8theme.com/account/" target="_blank" rel="nofollow">8theme.com</a>'); ?>

                </p>
            </div>

<!--            <div class="section-block step-4 invisible">-->
<!--                <label class="et-panel-option-switcher" for="deregister-domain-section-4">-->
<!--                    <input type="checkbox" class="checkbox-steps" id="deregister-domain-section-4"/>-->
<!--                    <span></span>-->
<!--                </label>-->
<!--                <p class="section-text">-->
<!--					--><?php //echo __('<b>Manual Domain Verification:</b> Our support team reviews and verifies all domain requests to ensure security and accuracy, as well as to confirm that each license is used for one project ONLY.', 'xstore'); ?>
<!---->
<!--                </p>-->
<!--            </div>-->

            <div class="hidden registration-error"></div>
        </div>

        <div class="popup-step step-3 invisible hidden">
            <svg width="47" height="47" viewBox="0 0 47 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_1_591" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="47" height="47">
                    <path d="M23.5 0L23.1456 0.204878L18.0486 3.14791H11.75L8.60209 8.60209L3.14791 11.75V18.0486L0 23.5L3.14791 28.9514V35.25L8.60209 38.3979L11.75 43.8521H18.0486L23.5 47L28.9514 43.8521H35.25L38.3979 38.3979L43.8521 35.25V28.9514L47 23.5L43.8521 18.0486V11.75L38.3979 8.60209L35.25 3.14791H28.9514L23.5 0ZM23.5 1.63902L28.5721 4.56545H34.4305L37.3597 9.64032L42.4346 12.5695V18.4279L45.361 23.5L42.4346 28.5721V34.4305L37.3597 37.3597L34.4305 42.4346H28.5721L23.5 45.361L18.4279 42.4346H12.5695L9.64032 37.3597L4.56545 34.4305V28.5721L1.63902 23.5L4.56545 18.4279V12.5695L9.64032 9.64032L12.5695 4.56545H18.4279L23.5 1.63902ZM23.5 3.65457C23.1096 3.65457 22.7912 3.97296 22.7912 4.36334C22.7912 4.75371 23.1096 5.0721 23.5 5.0721C23.8904 5.0721 24.2088 4.75371 24.2088 4.36334C24.2088 3.97296 23.8904 3.65457 23.5 3.65457ZM13.9151 6.21831C13.796 6.22108 13.6797 6.2543 13.5773 6.31244C13.4139 6.40657 13.2949 6.56162 13.2451 6.74435C13.198 6.9243 13.2229 7.11811 13.317 7.28146C13.4112 7.4448 13.5662 7.56386 13.7489 7.61092C13.9289 7.66076 14.1227 7.63584 14.2861 7.54171C14.6238 7.34513 14.7401 6.91046 14.5435 6.57269C14.4162 6.34843 14.1753 6.21277 13.9151 6.21831ZM33.0351 6.22108C32.7942 6.23215 32.5755 6.36504 32.4537 6.57546C32.2571 6.91323 32.3762 7.34513 32.714 7.54171C33.0517 7.73828 33.4864 7.622 33.6802 7.28422C33.7771 7.12088 33.802 6.92707 33.7522 6.74435C33.7051 6.56162 33.5861 6.40657 33.4227 6.31244C33.3064 6.24599 33.1708 6.21277 33.0351 6.22108ZM23.5 6.48963C23.1096 6.48963 22.7912 6.80802 22.7912 7.1984C22.7912 7.58877 23.1096 7.90716 23.5 7.90716C23.8904 7.90716 24.2088 7.58877 24.2088 7.1984C24.2088 6.80802 23.8904 6.48963 23.5 6.48963ZM20.3216 6.80249C20.2746 6.80249 20.2275 6.80802 20.1804 6.81633C19.7984 6.89385 19.5492 7.26484 19.6239 7.64968C19.7015 8.03452 20.0752 8.2837 20.4573 8.20617C20.8421 8.12865 21.0913 7.75766 21.0166 7.37282C20.9501 7.04335 20.6594 6.80525 20.3216 6.80249ZM26.7005 6.80249C26.3544 6.79418 26.0527 7.03505 25.9862 7.37282C25.9087 7.75766 26.1579 8.12865 26.5427 8.20617C26.9275 8.2837 27.2985 8.03452 27.3761 7.64968C27.4536 7.26484 27.2044 6.89385 26.8196 6.81633C26.7808 6.80802 26.7393 6.80249 26.7005 6.80249ZM29.7543 7.72997C29.4608 7.72167 29.195 7.89609 29.0815 8.16464C29.0123 8.33907 29.0123 8.53564 29.0843 8.71006C29.1563 8.88171 29.2947 9.02015 29.4691 9.09213C29.8291 9.24164 30.2416 9.06998 30.3911 8.70729C30.5434 8.34737 30.3717 7.93208 30.009 7.78258C29.9287 7.74935 29.8429 7.72997 29.7543 7.72997ZM17.2651 7.72997C17.1709 7.72997 17.0796 7.74658 16.991 7.78534C16.6283 7.93485 16.4566 8.34737 16.6089 8.71006C16.7584 9.06998 17.1709 9.24164 17.5309 9.09213C17.7053 9.02292 17.8437 8.88448 17.9157 8.71006C17.9877 8.53564 17.9877 8.34184 17.9185 8.16741C17.8077 7.90439 17.5502 7.73274 17.2651 7.72997ZM14.4522 9.2361C14.3082 9.23333 14.167 9.27486 14.0507 9.35515C13.724 9.57387 13.6354 10.0113 13.8541 10.338C14.0701 10.6647 14.5103 10.7505 14.837 10.5346C14.9948 10.4294 15.1028 10.2688 15.1388 10.0833C15.1748 9.8978 15.136 9.70676 15.0308 9.54895C14.9035 9.35515 14.6847 9.23887 14.4522 9.2361ZM32.57 9.2361C32.3291 9.23056 32.1021 9.34961 31.9692 9.54895C31.7505 9.87565 31.8391 10.3159 32.163 10.5318C32.4897 10.7505 32.9271 10.6619 33.1459 10.338C33.3646 10.0113 33.276 9.57387 32.9493 9.35515C32.8385 9.2804 32.7056 9.23887 32.57 9.2361ZM11.9826 11.2627C11.7915 11.26 11.606 11.3347 11.4731 11.4731C11.1963 11.7472 11.1963 12.1985 11.4731 12.4726C11.7472 12.7495 12.1985 12.7495 12.4726 12.4726C12.7495 12.1985 12.7495 11.7472 12.4726 11.4731C12.3425 11.3402 12.1681 11.2683 11.9826 11.2627ZM35.0368 11.2627C34.8458 11.2627 34.6603 11.3375 34.5274 11.4731C34.2505 11.7472 34.2505 12.1985 34.5274 12.4726C34.8015 12.7495 35.2528 12.7495 35.5269 12.4726C35.8037 12.1985 35.8037 11.7472 35.5269 11.4731C35.3967 11.3402 35.2223 11.2655 35.0368 11.2627ZM40.0563 13.2229C39.9373 13.2257 39.821 13.2589 39.7158 13.317C39.378 13.5136 39.2617 13.9455 39.4583 14.2861C39.6549 14.6238 40.0868 14.7401 40.4245 14.5435C40.5879 14.4522 40.7069 14.2971 40.7568 14.1144C40.8066 13.9317 40.7817 13.7379 40.6876 13.5773C40.5574 13.353 40.3138 13.2174 40.0563 13.2229ZM6.89385 13.2229C6.65298 13.234 6.43426 13.3669 6.31244 13.5773C6.11587 13.9151 6.23492 14.3497 6.57269 14.5435C6.91046 14.7401 7.34513 14.6238 7.54171 14.2861C7.73551 13.9483 7.61923 13.5136 7.28146 13.3198C7.16517 13.2506 7.02951 13.2174 6.89385 13.2229ZM37.0634 13.7323C36.9195 13.7323 36.7783 13.7739 36.6592 13.8541C36.3353 14.0701 36.2467 14.5103 36.4654 14.837C36.6841 15.1609 37.1216 15.2495 37.4483 15.0308C37.7722 14.8121 37.8608 14.3746 37.6421 14.048C37.5147 13.8541 37.296 13.7351 37.0634 13.7323ZM9.95871 13.7323C9.71784 13.7296 9.49081 13.8486 9.35792 14.0507C9.1392 14.3746 9.22779 14.8149 9.55449 15.0308C9.87842 15.2495 10.3186 15.1609 10.5346 14.837C10.6398 14.682 10.6785 14.4882 10.6426 14.3054C10.6066 14.1199 10.4986 13.9566 10.3408 13.8541C10.2273 13.7766 10.0971 13.7351 9.95871 13.7323ZM30.8673 16.1189L21.6256 25.3605L17.3288 21.4429L14.3553 24.876L21.8305 31.5622L34.0706 19.3222L30.8673 16.1189ZM8.45258 16.5535C8.16188 16.5452 7.89609 16.7197 7.78534 16.9882C7.63307 17.3509 7.80472 17.7662 8.16741 17.9157C8.5301 18.0652 8.94263 17.8936 9.09213 17.5309C9.2444 17.1709 9.07275 16.7557 8.71006 16.6061C8.62977 16.5729 8.54118 16.5535 8.45258 16.5535ZM38.564 16.5535C38.4699 16.5508 38.3785 16.5702 38.2899 16.6061C37.9272 16.7557 37.7556 17.1709 37.9079 17.5309C38.0574 17.8936 38.4699 18.0652 38.8326 17.9157C39.1953 17.7662 39.3669 17.3509 39.2147 16.9882C39.1067 16.7252 38.8492 16.5535 38.564 16.5535ZM30.8673 18.1234L32.0661 19.3222L21.7752 29.6131L16.3431 24.7514L17.4506 23.4723L21.6727 27.3207L30.8673 18.1234ZM7.53063 19.6129C7.18456 19.6018 6.88278 19.8427 6.81633 20.1804C6.73881 20.5653 6.98798 20.939 7.37282 21.0166C7.75766 21.0913 8.12865 20.8421 8.20617 20.4573C8.24217 20.2746 8.20617 20.0835 8.10097 19.9257C7.99576 19.7707 7.83518 19.6599 7.64968 19.6239C7.61092 19.6156 7.56939 19.6129 7.53063 19.6129ZM39.4915 19.6129C39.4445 19.6101 39.3974 19.6156 39.3503 19.6239C39.1676 19.6599 39.0042 19.7679 38.899 19.9257C38.7966 20.0808 38.7578 20.2718 38.7938 20.4573C38.8298 20.6428 38.9378 20.8034 39.0956 20.9086C39.2506 21.0138 39.4417 21.0525 39.6272 21.0166C39.8127 20.9778 39.9733 20.8698 40.0785 20.7148C40.1837 20.557 40.2224 20.3659 40.1864 20.1804C40.12 19.851 39.8293 19.6129 39.4915 19.6129ZM4.36334 22.7912C3.97296 22.7912 3.65457 23.1096 3.65457 23.5C3.65457 23.8904 3.97296 24.2088 4.36334 24.2088C4.75371 24.2088 5.0721 23.8904 5.0721 23.5C5.0721 23.1096 4.75371 22.7912 4.36334 22.7912ZM7.1984 22.7912C6.80802 22.7912 6.48963 23.1096 6.48963 23.5C6.48963 23.8904 6.80802 24.2088 7.1984 24.2088C7.58877 24.2088 7.90716 23.8904 7.90716 23.5C7.90716 23.1096 7.58877 22.7912 7.1984 22.7912ZM39.8016 22.7912C39.4112 22.7912 39.0928 23.1096 39.0928 23.5C39.0928 23.8904 39.4112 24.2088 39.8016 24.2088C40.192 24.2088 40.5104 23.8904 40.5104 23.5C40.5104 23.1096 40.192 22.7912 39.8016 22.7912ZM42.6367 22.7912C42.2463 22.7912 41.9279 23.1096 41.9279 23.5C41.9279 23.8904 42.2463 24.2088 42.6367 24.2088C43.027 24.2088 43.3454 23.8904 43.3454 23.5C43.3454 23.1096 43.027 22.7912 42.6367 22.7912ZM39.5054 25.9696C39.1621 25.9613 38.8603 26.2022 38.7938 26.5427C38.7163 26.9248 38.9655 27.2985 39.3503 27.3761C39.7352 27.4508 40.1061 27.2016 40.1837 26.8196C40.2612 26.4347 40.012 26.061 39.6272 25.9834C39.5884 25.9779 39.5469 25.9724 39.5054 25.9696ZM7.51679 25.9724C7.46695 25.9724 7.41989 25.9751 7.37559 25.9834C7.19009 26.0222 7.02674 26.1302 6.92154 26.2852C6.8191 26.443 6.78034 26.6341 6.81633 26.8196C6.85232 27.0023 6.9603 27.1656 7.11811 27.2709C7.27315 27.3733 7.46418 27.4121 7.64968 27.3761C7.83518 27.3401 7.99576 27.2321 8.10097 27.0743C8.20617 26.9192 8.24494 26.7282 8.20617 26.5427C8.1425 26.2132 7.85179 25.9751 7.51679 25.9724ZM8.44704 29.0289C8.35014 29.0289 8.25601 29.0455 8.17018 29.0815C7.80749 29.2338 7.63584 29.6463 7.78534 30.009C7.93485 30.3717 8.35014 30.5434 8.71283 30.3911C9.07275 30.2416 9.2444 29.8291 9.0949 29.4664C8.98692 29.2033 8.72944 29.0317 8.44704 29.0289ZM38.5751 29.0289C38.2844 29.0234 38.0186 29.195 37.9079 29.4664C37.7556 29.8291 37.9272 30.2416 38.2899 30.3911C38.4644 30.4631 38.6582 30.4631 38.8326 30.3911C39.007 30.3191 39.1454 30.1807 39.2147 30.009C39.3669 29.6463 39.1953 29.231 38.8326 29.0815C38.7523 29.0483 38.6637 29.0317 38.5751 29.0289ZM37.0662 31.8474C36.8253 31.8418 36.5983 31.9609 36.4654 32.1602C36.2467 32.4869 36.3353 32.9271 36.6592 33.1459C36.817 33.2483 37.0081 33.2871 37.1936 33.2511C37.3791 33.2123 37.5396 33.1043 37.6449 32.9465C37.8608 32.6226 37.775 32.1824 37.4483 31.9637C37.3348 31.8889 37.2046 31.8474 37.0662 31.8474ZM9.95594 31.8474C9.81197 31.8446 9.67077 31.8861 9.55449 31.9664C9.22779 32.1851 9.1392 32.6226 9.35792 32.9493C9.57387 33.276 10.0141 33.3618 10.3408 33.1459C10.4986 33.0406 10.6066 32.8801 10.6426 32.6946C10.6785 32.5091 10.6398 32.318 10.5346 32.1602C10.4072 31.9664 10.1885 31.8501 9.95594 31.8474ZM6.91046 32.3596C6.79141 32.3623 6.67513 32.3956 6.57269 32.4537C6.23492 32.6503 6.11587 33.0822 6.31244 33.4227C6.40657 33.5833 6.56162 33.7023 6.74435 33.7522C6.9243 33.802 7.11811 33.7771 7.28146 33.6802C7.4448 33.5888 7.56386 33.4338 7.61092 33.2511C7.66076 33.0683 7.63584 32.8745 7.54171 32.714C7.41158 32.4897 7.16794 32.354 6.91046 32.3596ZM40.0369 32.3596C39.7961 32.3734 39.5773 32.5063 39.4583 32.714C39.2617 33.0545 39.378 33.4864 39.7158 33.683C39.8791 33.7771 40.0729 33.802 40.2557 33.7549C40.4384 33.7051 40.5934 33.5861 40.6876 33.4227C40.7817 33.2621 40.8066 33.0683 40.7568 32.8856C40.7069 32.7029 40.5879 32.5478 40.4245 32.4565C40.3083 32.3873 40.1726 32.354 40.0369 32.3596ZM11.9826 34.317C11.7915 34.3142 11.606 34.389 11.4731 34.5246C11.1963 34.8015 11.1963 35.25 11.4731 35.5269C11.7472 35.8037 12.1985 35.8037 12.4726 35.5269C12.7495 35.25 12.7495 34.8015 12.4726 34.5246C12.3425 34.3945 12.1681 34.3197 11.9826 34.317ZM35.0368 34.3197C34.8458 34.3142 34.6603 34.389 34.5246 34.5274C34.2478 34.8015 34.2478 35.2528 34.5246 35.5269C34.8015 35.8037 35.25 35.8037 35.5269 35.5269C35.8037 35.2528 35.8037 34.8015 35.5269 34.5274C35.3967 34.3945 35.2223 34.3225 35.0368 34.3197ZM32.5672 36.3436C32.4232 36.3436 32.282 36.3851 32.163 36.4654C31.8391 36.6814 31.7505 37.1216 31.9692 37.4455C32.1879 37.7722 32.6254 37.8608 32.952 37.6421C33.276 37.4234 33.3646 36.9859 33.1459 36.6592C33.0185 36.4654 32.7998 36.3464 32.5672 36.3436ZM14.4577 36.3464C14.2168 36.3408 13.9898 36.4599 13.8541 36.6592C13.6382 36.9859 13.7268 37.4234 14.0507 37.6421C14.3774 37.8608 14.8149 37.7722 15.0336 37.4455C15.2523 37.1216 15.1637 36.6814 14.8398 36.4654C14.7263 36.3879 14.5934 36.3464 14.4577 36.3464ZM17.2762 37.8525C16.9855 37.8442 16.7197 38.0186 16.6089 38.2872C16.4566 38.6499 16.6283 39.0652 16.991 39.2147C17.3537 39.3642 17.7662 39.1925 17.9185 38.8298C18.068 38.4699 17.8963 38.0546 17.5336 37.9051C17.4533 37.8719 17.3648 37.8525 17.2762 37.8525ZM29.746 37.8525C29.6491 37.8497 29.555 37.8691 29.4691 37.9051C29.1064 38.0546 28.9348 38.4699 29.0843 38.8298C29.2338 39.1925 29.6491 39.3642 30.0118 39.2147C30.3717 39.0652 30.5434 38.6499 30.3939 38.2872C30.2859 38.0269 30.0284 37.8525 29.746 37.8525ZM20.341 38.78C19.9949 38.7717 19.6932 39.0125 19.6239 39.3503C19.5879 39.5358 19.6267 39.7269 19.7319 39.8819C19.8371 40.0397 19.9977 40.1477 20.1832 40.1837C20.568 40.2612 20.939 40.012 21.0166 39.6272C21.0525 39.4417 21.0138 39.2506 20.9113 39.0956C20.8061 38.9378 20.6428 38.8298 20.4573 38.7938C20.4185 38.7855 20.3798 38.78 20.341 38.78ZM26.6839 38.78C26.6368 38.78 26.5898 38.7855 26.5427 38.7938C26.1579 38.8713 25.9087 39.2423 25.9862 39.6272C26.0637 40.012 26.4347 40.2612 26.8196 40.1837C27.2044 40.1061 27.4536 39.7352 27.3761 39.3503C27.3096 39.0209 27.0217 38.7828 26.6839 38.78ZM23.5 39.0928C23.1096 39.0928 22.7912 39.4112 22.7912 39.8016C22.7912 40.192 23.1096 40.5104 23.5 40.5104C23.8904 40.5104 24.2088 40.192 24.2088 39.8016C24.2088 39.4112 23.8904 39.0928 23.5 39.0928ZM33.049 39.3614C32.9327 39.3669 32.8164 39.3974 32.714 39.4583C32.3734 39.6549 32.2571 40.0868 32.4537 40.4245C32.6503 40.7651 33.0822 40.8814 33.4227 40.6876C33.5833 40.5934 33.7023 40.4384 33.7522 40.2557C33.802 40.0729 33.7771 39.8791 33.6802 39.7158C33.5528 39.4915 33.3092 39.3559 33.049 39.3614ZM13.8984 39.3669C13.6576 39.378 13.4389 39.5109 13.317 39.7185C13.2229 39.8819 13.198 40.0757 13.2451 40.2557C13.2949 40.4384 13.4139 40.5934 13.5773 40.6876C13.9151 40.8841 14.3497 40.7651 14.5435 40.4273C14.7401 40.0895 14.6238 39.6549 14.2861 39.4583C14.167 39.3918 14.0341 39.3586 13.8984 39.3669ZM23.5 41.9279C23.1096 41.9279 22.7912 42.2463 22.7912 42.6367C22.7912 43.027 23.1096 43.3454 23.5 43.3454C23.8904 43.3454 24.2088 43.027 24.2088 42.6367C24.2088 42.2463 23.8904 41.9279 23.5 41.9279Z" fill="var(--et_admin_dark2white-color, #222)"/>
                </mask>
                <g mask="url(#mask0_1_591)">
                    <circle cx="24" cy="22" r="11" fill="#489C33"/>
                    <path d="M41.5 23C41.5 33.327 33.3325 41.5 23.5 41.5C13.6675 41.5 5.5 33.327 5.5 23C5.5 12.673 13.6675 4.5 23.5 4.5C33.3325 4.5 41.5 12.673 41.5 23Z" stroke="var(--et_admin_dark2white-color, #222)" stroke-width="11"/>
                </g>
            </svg>
            <br/><br/>
            <h3><?php echo esc_html__('Theme Successfully Registered!', 'xstore'); ?></h3>
            <p>
				<?php echo esc_html__('Thank you for purchasing XStore – your success story begins here. We look forward to being part of your web development journey, and we\'re excited to offer you even more amazing experiences in the future.', 'xstore'); ?>
            </p>
            <p><?php echo esc_html__('Congratulations once again, and happy building!', 'xstore'); ?></p>
            <br/>
            <svg class="signature-svg" width="163" height="89" viewBox="0 0 468 258" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M107.5 140.5C102 133.833 100.2 114.5 137 90.5C148.5 83 171.1 79.4 147.5 103C140.667 110.5 127.229 127.204 125 133C122.5 139.5 122.5 150.5 131.5 150.5C138.7 150.5 142.5 137.833 143.5 131.5C144.5 125.167 143.3 107.3 140.5 84.5M161.5 82C161 80.5 163.9 75.1 179.5 65.5C195.1 55.9 233.333 33.5 250.5 23.5M197.5 33.5C196.667 28.6667 194.4 21.9 192 33.5C189 48 172.339 115.386 168 131.5C164.5 144.5 164.5 149 164.5 159.5M197.5 157C199.741 148.382 202.664 137.466 205.98 125.5M238.5 42C243.3 32.4 249.5 15.6667 252 8.50002C253.5 2.16669 253.5 -4.89998 241.5 17.5C232.282 34.7063 216.968 85.8591 205.98 125.5M205.98 125.5C209.32 117.667 217 101.5 221 99.5C225 97.5 222.667 106 221 110.5C217.667 121.667 215.1 141.1 231.5 129.5C252 115 262.5 107 267.5 94.5C272.5 82 259 86 252 96.5C245.712 105.932 237 145.5 279 122M289.5 92C290.833 94 292.7 102 289.5 118C289.3 119 303.775 95.0065 308.5 92C314 88.5 315.5 92.5 313.5 112C313 113 330.1 86.7 344.5 87.5C355 88.0833 349 106 346.5 112C344.333 117.833 345.4 125.3 367 108.5C383 97.3 397.667 78.8333 403 71C405.833 66.5 406.7 61.2 387.5 76C363.5 94.5 388 106 394 106.5C400 107 414.5 107.5 428 102M158.5 197C159 194.833 159 190.5 155 190.5C151 190.5 149.333 201.5 149 207C148.5 218.5 151.2 242.8 166 248C184.5 254.5 170.5 241 166 239.5C161.5 238 145.5 231 107.5 237.5C77.1 242.7 60.5 247.667 56 249.5C46.3333 251.833 24.3 256.5 13.5 256.5C-9.53674e-07 256.5 0.499999 252.5 3 248C5.5 243.5 12 228 68.5 208.5C125 189 181 170 238 162.5C295 155 428.5 150.5 457.5 153C480.7 155 449.5 157.5 431 158.5M172.5 234C178.667 230.667 190.6 222.1 189 214.5C187 205 178.5 218.5 177.5 222C176.5 225.5 168.5 257 199 239.5C201.167 238.167 205.9 234.9 207.5 232.5M207.5 232.5C204.3 220.1 220.5 215.5 229 215.5C234.5 215.5 241.731 220.5 234.135 229M207.5 232.5C209.5 237.459 216.9 244.26 230.5 232.5C231.953 231.244 233.163 230.088 234.135 229M207.5 232.5C209.09 233.667 216.643 234.6 234.135 229M234.135 229C241.923 226.167 256 222.6 250 231M255 217C256.167 223.167 260.7 233.4 269.5 225C278.3 216.6 282 211.5 281.5 212.5C281.667 220.167 284 233.4 292 225C302 214.5 321 206.5 321 222C321 237.5 320.5 234.5 323 236" stroke="var(--et_admin_dark2white-color, #1D1A34)" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
        <br/>

    </div>

</div>

<p>
<div class="mtips mtips-top">
                    <span class="et-button et-button-arrow full-width no-loader tooltip-trigger popup-domain-confirm <?php //echo ($is_connection) ? 'enabled' : '';?>">
                        <?php echo esc_html__('Next Step', 'xstore'); ?>
                        <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 32 32">
                            <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                            </g>
                        </svg>
                    </span>
    <span class="mt-mes"><?php echo esc_html__('Please, select the domain specific.', 'xstore'); ?></span>
</div>

<div class="mtips mtips-top">
                    <span class="et-button full-width no-loader tooltip-trigger popup-theme-register hidden">
                        <?php echo esc_html__('Register Theme', 'xstore'); ?>
                    </span>
    <span class="mt-mes"><?php echo esc_html__('Please confirm all steps and important guidelines before proceeding.', 'xstore'); ?></span>
</div>
<a href="<?php echo admin_url( 'admin.php?page=et-panel-demos' ); ?>" class="et-button et-button-arrow full-width no-loader tooltip-trigger popup-go-to-import hidden enabled"><?php echo esc_html__('Go To Import Demos', 'xstore'); ?>
    <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 32 32">
        <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
        </g>
    </svg></a>
</p>