<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * The template for displaying header builter popup of connect_block element
 *
 * @since   1.0.0
 * @version 1.0.0
 */

$Etheme_Customize_Builder = new Etheme_Customize_header_Builder();

$elements     = array();
$all_elements = $Etheme_Customize_Builder->elements;

$popup_content = '';

if ( isset($_POST['elements']) && $_POST['elements'] && count( $_POST['elements'] ) ) {
    uasort( $_POST['elements'], function ( $item1, $item2 ) {
        return $item1['index'] <=> $item2['index'];
    });

    foreach ($_POST['elements'] as $key => $value) {
        $elements[esc_attr($key)] = $all_elements[esc_attr($key)];
    }

    foreach ($elements as $key => $value){
        $args = array(
            'class'    => 'et_in-popup',
            'id'       => $Etheme_Customize_Builder->generate_random( 5 ),
            'element'  => $key,
            'icon'     => $value['icon'],
            'title'    => $value['title'],
            'section'  => $value['section'],
            'parent'   => $value['parent'],
            'section2' => ( isset( $value['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-section="'.$value['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
        );

        $popup_content .= $Etheme_Customize_Builder->generate_html($args);
    }
}

?>
<div class="et_popup" data-id="<?php echo esc_attr($_POST['id']); ?>">
    <div class="et_actions-1">
        <span class="dashicons dashicons-move"></span>
        <span><?php esc_html_e( 'Connect block', 'xstore-core' ); ?></span>
        <span class="dashicons dashicons-no-alt et_close"></span>
    </div>
    <div class="et_inside-wrapper">
        <div class="et_inside-elements et_column" data-name="Drop here"><?php echo $popup_content; ?></div><!-- end inside block -->
        <div class="customize-control-kirki-toggle">
            <label class="block-setting" for="block_type">
                    <?php $checked = ( $_POST['type'] == 'vertical' ) ? 'checked' : ''; ?>
                    
                    <span class="customize-control-title"><?php esc_html_e( 'Vertical block', 'xstore-core' ); ?>
                        <span class="tooltip-wrapper">
                            <span class="tooltip-trigger" data-setting="block_type">
                                <span class="dashicons dashicons-editor-help"></span>
                            </span>
                            <span class="tooltip-content" data-setting="block_type">
                                <?php echo esc_html__('Enable this option to change the content direction of this connection block to display elements vertically.', 'xstore-core'); ?>
                            </span>
                        </span>
                    </span>
                    <input class="screen-reader-text" id="block_type" name="block_type" type="checkbox" value="" <?php echo $checked; ?> hidden="">
                    <span class="switch" data-text-on="<?php esc_attr_e( 'On', 'xstore-core' ); ?>" data-text-off="<?php esc_attr_e( 'Off', 'xstore-core' ); ?>"></span>
            </label>
         </div>
         <div class="customize-control-kirki-toggle">
            <label class="block-setting" for="block_separator">
                    <?php $checked = ( isset( $_POST['separator'] ) && $_POST['separator'] == 'true' ) ? 'checked' : ''; ?>
                    
                    <span class="customize-control-title"><?php esc_html_e( 'Separators', 'xstore-core' ); ?>
                        <span class="tooltip-wrapper">
                            <span class="tooltip-trigger" data-setting="block_separator">
                                <span class="dashicons dashicons-editor-help"></span>
                            </span>
                            <span class="tooltip-content" data-setting="block_separator">
                                <?php echo esc_html__('If you enable this option, you will be able to add attractive separators between your items.', 'xstore-core'); ?>
                            </span>
                        </span>
                    </span>
                    <input class="screen-reader-text" id="block_separator" name="block_separator" type="checkbox" value="" <?php echo $checked; ?> hidden="">
                    <span class="switch" data-text-on="<?php esc_attr_e( 'On', 'xstore-core' ); ?>" data-text-off="<?php esc_attr_e( 'Off', 'xstore-core' ); ?>"></span>
            </label>
         </div>
        <div class="block-setting customize-control-kirki-radio-buttonset flex align-items-center">
            <span class="customize-control-title"><?php esc_html_e( 'Alignment', 'xstore-core' ); ?>
                <span class="tooltip-wrapper">
                    <span class="tooltip-trigger" data-setting="block_alignment">
                        <span class="dashicons dashicons-editor-help"></span>
                    </span>
                    <span class="tooltip-content" data-setting="block_alignment">
                        <?php echo esc_html__( 'Using this option, you can choose an alignment value for the elements added inside this connection block.', 'xstore-core' ); ?>
                    </span>
                </span>
            </span>
            <div id="input_block_align" class="buttonset">
                <?php $checked = ( $_POST['align'] == 'start' ) ? 'checked' : ''; ?>

                <input class="switch-input screen-reader-text" type="radio" value="start" name="_customize-radio-block_align" id="block_alignstart" data-customize-setting-link="block_align" data-alt="" <?php echo $checked; ?>>

                <label for="block_alignstart" class="switch-label switch-label-off">
                    <span class="dashicons dashicons-editor-alignleft"></span>
                    <span class="image-clickable"></span>
                </label>
                
                <?php $checked = ( $_POST['align'] == 'center' ) ? 'checked' : ''; ?>
                <input class="switch-input screen-reader-text" type="radio" value="center" name="_customize-radio-block_align" id="block_aligncenter" data-customize-setting-link="block_align" data-alt="" <?php echo $checked; ?>>
                    <label for="block_aligncenter" class="switch-label switch-label-off">
                        <span class="dashicons dashicons-editor-aligncenter"></span>
                    <span class="image-clickable"></span>
                </label>
                
                <?php $checked = ( $_POST['align'] == 'end' ) ? 'checked' : ''; ?>
                <input class="switch-input screen-reader-text" type="radio" value="end" name="_customize-radio-block_align" id="block_alignend" data-customize-setting-link="block_align" data-alt="" <?php echo $checked; ?>>
                    <label for="block_alignend" class="switch-label switch-label-off">
                        <span class="dashicons dashicons-editor-alignright"></span>
                    <span class="image-clickable"></span>
                </label>        
            </div>
        </div><!-- end align element -->
        <div class="block-setting customize-control-kirki-slider flex align-items-center" id="et_popup-slider">
                <span class="customize-control-title"><?php esc_html_e( 'Spacing', 'xstore-core' ); ?>
                    <span class="tooltip-wrapper">
                        <span class="tooltip-trigger" data-setting="block_spacing">
                            <span class="dashicons dashicons-editor-help"></span>
                        </span>
                        <span class="tooltip-content" data-setting="block_spacing">
                            <?php echo esc_html__('This controls the spacing between the elements added inside this connection block.', 'xstore-core'); ?>
                        </span>
                    </span>
                </span>
                <div class="wrapper">
                    <?php $spacing = ( isset($_POST['spacing'] ) && ! empty( $_POST['spacing'] ) ) ? esc_attr($_POST['spacing']) : 0 ?>

                    <input type="range" min="0" max="100" step="1" value="<?php echo $spacing; ?>" data-customize-setting-link="top_header_height">
                    <span class="value">
                        <input type="text" value="<?php echo $spacing; ?>">
                        <span class="suffix"></span>
                    </span>
                </div>
         </div>
    </div> <!-- end inside wrapper block -->
</div><!-- end et_popup -->