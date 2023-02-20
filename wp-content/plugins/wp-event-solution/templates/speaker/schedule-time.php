<?php
defined( 'ABSPATH' ) || exit;

$event_options      = get_option( "etn_event_options" );
$event_time_format  = empty( $event_options["time_format"] ) ? '12' : $event_options["time_format"];
$start              = empty( $start ) ? '' : ( ( $event_time_format == '24' ) ? date_i18n( 'H:i', strtotime($start) ) : date_i18n( get_option( 'time_format' ), strtotime($start) ) );
$end                = empty( $end ) ? '' : ( ( $event_time_format == '24' ) ? date_i18n( 'H:i', strtotime($end) ) : date_i18n( get_option( 'time_format' ), strtotime($end) ) );
$dash_sign	        = ( !empty( $start ) || !empty( $end ) ) ? " - " : "";
if(!empty($start) || !empty( $end )){
    ?>
    <div class="etn-schedule-info">
        <span class="etn-schedule-time">
            <span class="etn-schedule-start-time"><?php  echo esc_html( $start ); ?></span>
            <span class="etn-schedule-time-divider"><?php  echo esc_html( $dash_sign ); ?></span>
            <span class="etn-schedule-end-time"><?php  echo esc_html( $end );  ?></span>
        </span>
    </div>
    <?php
}
?>