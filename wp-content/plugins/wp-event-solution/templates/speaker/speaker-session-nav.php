<?php
defined( 'ABSPATH' ) || exit;
?>
<li>
    <a href="#" class="etn-tab-a <?php echo esc_attr(($key===0) ? 'etn-active' : ''); ?> " data-id="tab-<?php echo esc_attr($key); ?>">
        <span class="etn-date"><?php echo esc_html( $head_date ) ?></span>
        <span class="etn-day"><?php echo esc_html( $head_title ) ?></span>
    </a>     
</li>
