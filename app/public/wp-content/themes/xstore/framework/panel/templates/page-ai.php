<h2 class="etheme-page-title etheme-page-title-type-2"><?php echo sprintf(esc_html__('%s OpenAI Assistant', 'xstore'), apply_filters('etheme_theme_label', 'XStore')); ?></h2>
<form>
	<p><?php echo esc_html__('We have integrated OpenAI into our theme to make it easier for you to write your website content. With OpenAI, you can generate text content in a matter of seconds, without the need to spend hours brainstorming or researching. Plus, take your SEO to the next level with features like meta description and keyword generation.', 'xstore'); ?></p>
	<p class="et-message et-info"><?php
		echo sprintf(esc_html__('To begin using OpenAI, please generate your API key by following this %1s', 'xstore'), '<a href="https://platform.openai.com/account/api-keys" target="_blank" rel="nofollow">'. esc_html__('link', 'xstore') . '</a>') . '.</br>';
		?></p>
	<p>
		<label for="open_ai"><?php echo esc_html__('OpenAI API Key...', 'xstore'); ?></label>
	</p>
	<p>
		<input id="open_ai" placeholder="<?php echo esc_attr('Enter your API key', 'xstore'); ?>" name="open_ai" type="text" value="<?php echo get_theme_mod('open_ai'); ?>">
        <input class="etheme-network-save et-button no-loader" data-network="open-ai" type="submit" value="<?php echo esc_attr('Save', 'xstore'); ?>">
	</p>
    <?php echo ( get_theme_mod('open_ai') ) ? et_ai_msg() : '';?>
	<p class="etheme-network-save-info info-success hidden">
		<?php esc_html_e('Saved', 'xstore');?>
	</p>
	<p class="etheme-network-save-info info-error hidden">
		<?php esc_html_e('Error while saving', 'xstore');?>
	</p>
</form>

<input type="hidden" name="nonce_update_network-settings" value="<?php echo wp_create_nonce( 'etheme_update_network-settings' ); ?>">