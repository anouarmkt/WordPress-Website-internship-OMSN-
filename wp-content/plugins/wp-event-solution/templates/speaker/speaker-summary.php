<?php
defined( 'ABSPATH' ) || exit;

$etn_speaker_summary            = get_post_meta(get_the_id(), 'etn_speaker_summery', true);
?>
<p class="etn-speaker-desc"> 
    <?php echo \Etn\Utils\Helper::render( $etn_speaker_summary ); ?>
</p>
