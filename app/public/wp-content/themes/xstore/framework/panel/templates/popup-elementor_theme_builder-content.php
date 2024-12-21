<?php
/**
 * Template "Elementor theme builder" for 8theme dashboard.
 *
 * @since 9.1.12
 * @version 1.0.0
 */

?>
<p class="et-message et-info">
    <?php
    // translators: %1$s: Elementor plugin link, %2$s: PRO Elements plugin link.
    $pro_elements_message = _x( '<strong>Important!</strong> For this functionality you need to install %1$s or its free alternative, %2$s plugin. We cannot install them automatically. Please install one of these plugins to proceed then.', 'admin', 'xstore' );
    echo wp_kses_post(
        sprintf(
            $pro_elements_message,
            '<a href="https://elementor.com/pro/" target="_blank" rel="nofollow">Elementor Pro</a>',
            '<a href="https://proelements.github.io/proelements.org/" target="_blank" rel="nofollow">PRO Elements</a>'
        )
    );
    ?>
</p>
