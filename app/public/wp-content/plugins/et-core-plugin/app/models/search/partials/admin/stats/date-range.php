<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="et-search-stats-analytics-date-range">
    <span><?php esc_html_e( 'Search stats.', 'xstore-core' ); ?></span>
    <select class="et-search-stats-analytics-select" data-type="date-from">
        <?php foreach ( $vars['date-range']['ranges'] as $range => $label ): ?>
            <option value="<?php echo esc_html( $range ); ?>" <?php selected( $vars['date-range']['current-range'], $range, true ); ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
    </select>
</div>
