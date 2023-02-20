<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

$event_options  = get_option("etn_event_options");
$data           = Helper::single_template_options( $single_event_id );
?>
<div class="etn-event-meta-info etn-widget">
    <ul>
        <?php 
        // event date
        if(!isset($event_options["etn_hide_date_from_details"])){
            $separate = !empty($data['event_end_date']) ? ' - ' : '';
            ?>
            <li>
                <span> <?php echo esc_html__('Date : ', "eventin"); ?></span>
                <?php echo esc_html($data['event_start_date'] . $separate . $data['event_end_date']); ?>
            </li>
            <?php 
        } 
        ?>
        <?php
        // event time
        if ( !isset($event_options["etn_hide_time_from_details"]) && ( !empty( $data['event_start_time'] ) || !empty( $data['event_end_time'] ) )) {
            $separate = !empty($data['event_end_time']) ? ' - ' : '';
            ?>
            <li>
                <span><?php echo esc_html__('Time : ', "eventin"); ?></span>
                <?php echo esc_html($data['event_start_time'] . $separate . $data['event_end_time']); ?>
                <span class="etn-event-timezone">
                    <?php
                    if ( !empty( $data['event_timezone'] ) && !isset($event_options["etn_hide_timezone_from_details"]) ) {
                        ?>
                        (<?php echo esc_html( $data['event_timezone'] ); ?>)
                        <?php
                    }
                    ?>
                </span>
            </li>
            <?php
        }
        ?>
        <?php 
        if(!empty($data['etn_deadline_value'])){ 
            ?>
            <li>
                <span><?php echo esc_html__('Reg. Deadline : ', "eventin"); ?></span>
                <?php echo esc_html($data['etn_deadline_value']); ?>
            </li>
            <?php 
        } 
        ?>
        
        <?php
        if( !class_exists('Wpeventin_Pro') || get_post_meta($single_event_id, 'etn_event_location_type', true) != 'new_location' ) {
            if ( !isset($event_options["etn_hide_location_from_details"]) && !empty($data['etn_event_location'])) {
                ?>
                <li>
                    <span><?php echo esc_html__('Venue : ', "eventin") ?></span>
                    <?php echo esc_html($data['etn_event_location']);  ?>
                </li>
                <?php 
            }
        }
        ?>
    </ul>
    <?php
    ?>
</div> 