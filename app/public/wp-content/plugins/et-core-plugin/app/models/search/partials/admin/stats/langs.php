<?php

if (!defined('ABSPATH')) {
    exit;
}


if ($vars['multilingual']['is-multilingual']): ?>
    <div class="et-search-stats-analytics-langs">
        <select class="et-search-stats-analytics-select" data-type="lang">
            <?php foreach ($vars['multilingual']['langs'] as $lang => $label): ?>
                <option value="<?php echo esc_html($lang); ?>" <?php selected($vars['multilingual']['current-lang'], $lang, true); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>
