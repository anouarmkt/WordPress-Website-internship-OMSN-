<?php

defined( 'ABSPATH' ) || die();
?>
<p><?php echo apply_filters('etn_event_archive_content', wp_trim_words( get_the_excerpt(), 15 , '' )); ?></p>