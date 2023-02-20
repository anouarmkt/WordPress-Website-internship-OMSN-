<?php
defined( 'ABSPATH' ) || exit;

if( !empty( $etn_shedule_room ) ){
    ?>
    <span class="etn-schedule-location">
        <i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $etn_shedule_room ); ?>
    </span>
    <?php
}