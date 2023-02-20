<?php
defined( 'ABSPATH' ) || exit;

use \Etn\Utils\Helper;
$etn_speaker_designation        = get_post_meta(get_the_id(), 'etn_speaker_designation', true);
?>
    <p class="etn-speaker-designation"><?php echo Helper::kses( $etn_speaker_designation ); ?></p>	