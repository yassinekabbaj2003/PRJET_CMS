<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for AI generate settings
 *
 * @since   5.1.0
 * @version 1.0.0
 */

$global_admin_class = EthemeAdmin::get_instance();

$global_admin_class->init_vars();

$Etheme_AI = new Etheme_AI;
$settings_available = $Etheme_AI->is_settings_available();
//update_option('et_ai_models_settings', array(), false)
?>

<span class="et_close-popup et-button-cancel hide-popup et-button"><span class="dashicons dashicons-no" style="font-size: 22px;"></span></span>
<div class="popup-import-head with-bg">
    <p><?php esc_html_e( 'AI Settings', 'xstore-core' ); ?></p>
</div>
<div class="et_popup-import-content">
    <?php if ( $settings_available ) : ?>

        <?php $settings = $Etheme_AI->get_model_settings($_POST['model']); ?>
	    <?php $defaults = $Etheme_AI->get_model_defaults($_POST['model']); ?>

        <?php $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
            'temperature',
            esc_html__( 'Temperature', 'xstore-core' ),
            esc_html__('Controls randomness: Lowering results in less random completions. As the temperature approaches zero, the model will become deterministic and repetitive.', 'xstore-core'),
            0,
            1,
            $settings['temperature'],
            .01,
            '',
            array(),
		    $defaults['temperature'],
            true
        ); ?>

        <?php $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
            'max_tokens',
            esc_html__( 'Maximum length', 'xstore-core' ),
            esc_html__('The maximum number of tokens to "generate". Requests can use up to 2,048 or 4000 tokens shared between prompt and completion. The exact limit varies by model. (One token is roughly 4 characters for normal English text)', 'xstore-core'),
            1,
            4000,
            $settings['max_tokens'],
            10,
		    '',
		    array(),
		    $defaults['max_tokens'],
            true
        ); ?>

        <?php $global_admin_class->xstore_panel_settings_textarea_field( 'ai_settings',
            'stop_sequences',
            esc_html__( 'Stop sequences', 'xstore-core' ),
            esc_html__( 'Up to four sequences where the API will stop generating futher tokens. The returned text will not contain the stop sequence. Enter sequences with ";" separator.', 'xstore-core' ),
            $settings['stop_sequences'],
		    $defaults['stop_sequences'],
            true
        ); ?>

        <?php $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
            'top_p',
            esc_html__( 'Top P', 'xstore-core' ),
            esc_html__('Controls diversity via nucleus sampling: 0.5 means half of all likelihood-weighted options are considered.', 'xstore-core'),
            0,
            1,
            $settings['top_p'],
            .01,
		    '',
		    array(),
		    $defaults['top_p'],
            true
        ); ?>

        <?php $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
            'frequency_penalty',
            esc_html__( 'Frequency penalty', 'xstore-core' ),
            esc_html__('How much to penalize new tokens based on their existing frequency in the text so far. Decreases the model\'s likelihood to repeat the same line verbatim.', 'xstore-core'),
            0,
            2,
            $settings['frequency_penalty'],
            .01,
		    '',
		    array(),
		    $defaults['frequency_penalty'],
            true
        ); ?>

        <?php $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
            'presence_penalty',
            esc_html__( 'Presence penalty', 'xstore-core' ),
            esc_html__('How much to penalize new tokens based on whether they appear in the text so far. Increases the model\'s likelihood to talk about new topics.', 'xstore-core'),
            0,
            2,
            $settings['presence_penalty'],
            .01,
		    '',
		    array(),
		    $defaults['presence_penalty'],
            true
        ); ?>

        <?php
//            $global_admin_class->xstore_panel_settings_slider_field( 'ai_settings',
//            'best_of',
//            esc_html__( 'Best of (on question)', 'xstore-core' ),
//            esc_html__('Generates multiple completions server-side, and displays only the best. Streaming only works when set to 1. Since it acts as a multiplier on the number of competions, this parameters can eat into your token quota very quickly - use caution !', 'xstore-core'),
//            1,
//            20,
//            $settings['best_of'],
//            1);
        ?>

        <?php
//        $global_admin_class->xstore_panel_settings_switcher_field( 'ai_settings',
//            'show_probabilities',
//            esc_html__( 'Show probabilities (on question)', 'xstore-core' ),
//            esc_html__('Toggle token highlighting which indicates how likely a token was to be generated. Helps to debug a given generation, or see alternative options for a token.', 'xstore-core'),
//            $settings['show_probabilities'] );
        ?>
        <input type="hidden" name="nonce_etheme-ai-settings" value="<?php echo wp_create_nonce( 'etheme_ai-settings' ); ?>">
    <?php else: ?>
        <p><?php echo esc_html__( 'Can not get settings list', 'xstore-core' ); ?></p>
    <?php endif; ?>
</div>
<?php if ( $settings_available ) : ?>
        <div class="popup-import-footer">
           <span class="et-button et-button-green et-save-ai-config">
               <?php echo esc_html__( 'Save', 'xstore-core' ); ?>
           </span>
            <span class="et-button et-button-dark-grey et_rtd-button">
               <?php echo esc_html__( 'Restore default settings', 'xstore-core' ); ?>
           </span>
        </div>
<?php endif; ?>
