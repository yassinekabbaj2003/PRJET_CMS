<?php

$et_imported_data = get_option('et_imported_data', array());
$global_admin_class = EthemeAdmin::get_instance();

?>
<div class="et_panel-popup-inner with-scroll et_popup-content-remove">
    <?php // echo '<div class="image-block">'.$settings['logo'].'</div>' ?>
			<div class="steps-block-content">
                <h3><?php esc_html_e('Delete Content', 'xstore');?></h3>
                <p class="et-message et-error">
                    <?php esc_html_e('IMPORTANT: If you delete any content imported with our theme, it will be permanently removed, and there\'s no way to undo this action.', 'xstore') ?>
                </p>

                <form class="et_remove_content_form">
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-all" name="et_remove-all">
                        <label for="et_remove-all" class="check-all"><b><?php esc_html_e('Check all', 'xstore');?></b></label>
                        <label for="et_remove-all" class="hidden uncheck-all"><b><?php esc_html_e('Uncheck all', 'xstore');?></b></label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-page" name="et_remove-content" value="page">
                        <label for="et_remove-page">
                            <?php esc_html_e('Pages', 'xstore');?> (<?php echo isset($et_imported_data['page']) ? count($et_imported_data['page']) : '0';?>)
                        </label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-product" name="et_remove-content" value="product">
                        <label for="et_remove-product"><?php esc_html_e('Products', 'xstore');?> (<?php echo isset($et_imported_data['product']) ? count($et_imported_data['product']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-post" name="et_remove-content" value="post">
                        <label for="et_remove-post"><?php esc_html_e('Posts', 'xstore');?> (<?php echo isset($et_imported_data['post']) ? count($et_imported_data['post']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-projects" name="et_remove-content" value="project">
                        <label for="et_remove-projects"><?php esc_html_e('Projects', 'xstore');?> (<?php echo isset($et_imported_data['projects']) ? count($et_imported_data['project']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-staticblocks" name="et_remove-content" value="staticblocks">
                        <label for="et_remove-staticblocks"><?php esc_html_e('Static Blocks', 'xstore');?> (<?php echo isset($et_imported_data['staticblocks']) ? count($et_imported_data['staticblocks']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-etheme_slides" name="et_remove-content" value="etheme_slides">
                        <label for="et_remove-etheme_slides"><?php esc_html_e('Slides', 'xstore');?> (<?php echo isset($et_imported_data['etheme_slides']) ? count($et_imported_data['etheme_slides']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-wpcf7_contact_form" name="et_remove-content" value="wpcf7_contact_form">
                        <label for="et_remove-wpcf7_contact_form"><?php esc_html_e('Contact Form', 'xstore');?> (<?php echo isset($et_imported_data['wpcf7_contact_form']) ? count($et_imported_data['wpcf7_contact_form']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-mc4wp-form" name="et_remove-content"  value="mc4wp-form">
                        <label for="et_remove-mc4wp-form"><?php esc_html_e('Mailchimp', 'xstore');?> (<?php echo isset($et_imported_data['mc4wp-form']) ? count($et_imported_data['mc4wp-form']) : '0';?>)</label>
                    </span>
                    <?php if( isset($et_imported_data['vc_grid_item']) ) :?>
                        <span class="remove-content-block">
                            <input type="checkbox" id="et_remove-vc_grid_item" name="et_remove-content"  value="vc_grid_item">
                            <label for="et_remove-vc_grid_item"><?php esc_html_e('WPB Grid Item', 'xstore');?> (<?php echo isset($et_imported_data['vc_grid_item']) ? count($et_imported_data['vc_grid_item']) : '0';?>)</label>
                        </span>
                    <?php endif; ?>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-attachment" name="et_remove-content" value="attachment">
                        <label for="et_remove-attachment"><?php esc_html_e('Images', 'xstore');?> (<?php echo isset($et_imported_data['attachment']) ? count($et_imported_data['attachment']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-options" name="et_remove-content" value="options">
                        <label for="et_remove-options"><?php esc_html_e('Options', 'xstore');?></label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-widget" name="et_remove-content" value="widgets">
                        <label for="et_remove-widget"><?php esc_html_e('Widgets', 'xstore');?> (<?php echo isset($et_imported_data['widgets']) ? count($et_imported_data['widgets']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-widget_areas" name="et_remove-content" value="widget_areas">
                        <label for="et_remove-widget_areas"><?php esc_html_e('Sidebars', 'xstore');?> (<?php echo isset($et_imported_data['widget_areas']) ? count($et_imported_data['widget_areas']) : '0';?>)</label>
                    </span>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-menu" name="et_remove-content" value="menu">
                        <label for="et_remove-menu"><?php esc_html_e('Menus', 'xstore');?> (<?php echo isset($et_imported_data['menu']) ? count($et_imported_data['menu']) : '0';?>)</label>
                    </span>

                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-etheme_mega_menus" name="et_remove-content" value="etheme_mega_menus">
                        <label for="et_remove-etheme_mega_menus"><?php esc_html_e('Mega Menus', 'xstore');?> (<?php echo isset($et_imported_data['etheme_mega_menus']) ? count($et_imported_data['etheme_mega_menus']) : '0';?>)</label>
                    </span>

                    <?php

                        $builders_total = 0;

                        if (isset($et_imported_data['elementor_templates-footer'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-footer']);
                        }

                        if (isset($et_imported_data['elementor_templates-header'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-header']);
                        }

                        if (isset($et_imported_data['elementor_templates-product-archive'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-product-archive']);
                        }

                        if (isset($et_imported_data['elementor_templates-product'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-product']);
                        }

                        if (isset($et_imported_data['elementor_templates-archive'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-archive']);
                        }

                        if (isset($et_imported_data['elementor_templates-single-post'])){
                            $builders_total = $builders_total + count($et_imported_data['elementor_templates-single-post']);
                        }
                    ?>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-elementor_xstore-builders" name="et_remove-content" value="elementor_xstore-builders">
                        <label for="et_remove-elementor_xstore-builders"><?php esc_html_e('XStore Builders', 'xstore');?> (<?php echo esc_html($builders_total);?>)</label>
                    </span>

                    <?php
                        $terms_total = 0;
                        if (isset($et_imported_data['brand'])){
	                        $terms_total = $terms_total + count($et_imported_data['brand']);
                        }
                        if (isset($et_imported_data['product_cat'])){
	                        $terms_total = $terms_total + count($et_imported_data['product_cat']);
                        }
                    ?>
                    <span class="remove-content-block">
                        <input type="checkbox" id="et_remove-terms" name="et_remove-content" value="etheme_terms">
                        <label for="et_remove-terms"><?php esc_html_e('Terms', 'xstore');?> (<?php echo esc_html($terms_total);?>)</label>
                    </span>


<!--                    <span class="remove-content-block">-->
<!--                        <input type="checkbox" id="et_remove-elementor_templates-footer" name="et_remove-content" value="elementor_templates-footer">-->
<!--                        <label for="et_remove-elementor_templates-footer">--><?php //esc_html_e('Elementor Footers', 'xstore');?><!-- (--><?php //echo isset($et_imported_data['elementor_templates-footer']) ? count($et_imported_data['elementor_templates-footer']) : '0';?><!--)</label>-->
<!--                    </span>-->
<!---->
<!--                    <span class="remove-content-block">-->
<!--                        <input type="checkbox" id="et_remove-elementor_templates-header" name="et_remove-content" value="elementor_templates-header">-->
<!--                        <label for="et_remove-elementor_templates-header">--><?php //esc_html_e('Elementor Headers', 'xstore');?><!-- (--><?php //echo isset($et_imported_data['elementor_templates-header']) ? count($et_imported_data['elementor_templates-header']) : '0';?><!--)</label>-->
<!--                    </span>-->
<!---->
<!--                    <span class="remove-content-block">-->
<!--                        <input type="checkbox" id="et_remove-elementor_templates-product-archive" name="et_remove-content" value="elementor_templates-product-archive">-->
<!--                        <label for="et_remove-elementor_templates-product-archive">--><?php //esc_html_e('Elementor Product Archives', 'xstore');?><!-- (--><?php //echo isset($et_imported_data['elementor_templates-product-archive']) ? count($et_imported_data['elementor_templates-product-archive']) : '0';?><!--)</label>-->
<!--                    </span>-->
<!---->
<!--                    <span class="remove-content-block">-->
<!--                        <input type="checkbox" id="et_remove-elementor_templates-product" name="et_remove-content" value="elementor_templates-product">-->
<!--                        <label for="et_remove-elementor_templates-product">--><?php //esc_html_e('Elementor Single Products', 'xstore');?><!-- (--><?php //echo isset($et_imported_data['elementor_templates-product']) ? count($et_imported_data['elementor_templates-product']) : '0';?><!--)</label>-->
<!--                    </span>-->

                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'etheme_remove_content-nonce' ); ?>">
                </form>
			</div>
</div>
<p>
    <br/>
    <span class="et-button et-button-active full-width et_popup-remove-confirm text-center">
        <?php esc_html_e('Remove', 'xstore');?>
        <?php $global_admin_class->get_loader(true); ?>
    </span>
</p>
