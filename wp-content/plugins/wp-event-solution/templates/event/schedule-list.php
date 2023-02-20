<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

$etn_event_schedule     = get_post_meta( $single_event_id, 'etn_event_schedule', true);

if ( is_array($etn_event_schedule) && !empty($etn_event_schedule) ) {
    $args = array(
        'post__in' => $etn_event_schedule,
        'orderby' => 'post_date',
        'order' => 'asc',
        'post_type' => 'etn-schedule',
        'post_status' => 'publish',
        'suppress_filters' => false,
    );

    $schedule_query = get_posts($args);
    ?>
    <!-- schedule tab start -->
    <div class="schedule-tab-wrapper etn-tab-wrapper">
        <ul class='etn-nav'>
            <?php
            $i = -1;
            if( is_array( $schedule_query ) ){
                foreach ($schedule_query as $post) :
                    $single_schedule_id = $post->ID;
                    $i++;
                    $schedule_meta = get_post_meta($single_schedule_id);
                    $schedule_date = !empty( $schedule_meta['etn_schedule_date'][0] ) ? date_i18n("d M", strtotime($schedule_meta['etn_schedule_date'][0])) : "";
                    $active_class = (($i == 0) ? 'etn-active' : ' ');
                    ?>
                    <li>
                        <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($i); ?>'>
                            <span class='etn-date'><?php echo esc_html($schedule_date); ?></span>
                            <span class=etn-day><?php echo esc_html($post->post_title); ?></span>
                        </a>
                    </li>
                    <?php 
                endforeach; 
            }
            ?>
        </ul>
        <div class='etn-tab-content clearfix etn-schedule-wrap'>
            <?php
            $j = -1;
            if( is_array( $schedule_query ) ){
                foreach ($schedule_query as $post) :
                    $single_schedule_id = $post->ID;
                    $j++;
                    $schedule_meta = get_post_meta($single_schedule_id);
                    $schedule_topics = unserialize($schedule_meta['etn_schedule_topics'][0]);
                    $schedule_date = !empty( $schedule_meta['etn_schedule_date'][0] ) ? date_i18n("d M", strtotime($schedule_meta['etn_schedule_date'][0]) ) : "";
                    $active_class = (($j == 0) ? 'tab-active' : ' ');
                    ?>
                    <!-- start repeatable item -->
                    <div class='etn-tab <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($j); ?>'>
                        <?php
                        $etn_tab_time_format = ( isset( $event_options["time_format"] ) && $event_options["time_format"] == '24' ) ? "H:i" : get_option( 'time_format' );
                        if( is_array( $schedule_topics ) && !empty($schedule_topics) ){
                            foreach ($schedule_topics as $topic) :
                                $etn_schedule_topic         = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
                                $etn_schedule_start_time    = !empty($topic['etn_shedule_start_time']) ? date_i18n($etn_tab_time_format, strtotime($topic['etn_shedule_start_time'])) : '';
                                $etn_schedule_end_time      = !empty($topic['etn_shedule_end_time']) ? date_i18n($etn_tab_time_format, strtotime($topic['etn_shedule_end_time'])) : '';
                                $etn_schedule_room          = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
                                $etn_schedule_objective     = (isset($topic['etn_shedule_objective']) ? $topic['etn_shedule_objective'] : '');
                                $etn_schedule_speaker       = (isset($topic['etn_shedule_speaker']) ? $topic['etn_shedule_speaker'] : []);
                                $dash_sign	        = ( !empty( $etn_schedule_start_time ) && !empty( $etn_schedule_end_time ) ) ? " - " : " ";
                                ?>
                                <div class='etn-single-schedule-item etn-row'>
                                    <div class='etn-schedule-info etn-col-sm-4'>
                                        <?php
                                        if(!empty($etn_schedule_start_time) || !empty( $etn_schedule_end_time )){
                                            ?>
                                            <span class='etn-schedule-time'>
                                                <?php echo esc_html($etn_schedule_start_time) . $dash_sign . esc_html($etn_schedule_end_time); ?>
                                            </span>
                                            <?php
                                        }
                                        if( !empty( $etn_schedule_room ) ){
                                            ?>
                                            <span class='etn-schedule-location'>
                                            <i class='fas fa-map-marker-alt'></i><?php echo esc_html($etn_schedule_room);?>
                                            </span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class='etn-schedule-content etn-col-sm-8'>
                                        <h4 class='etn-title'><?php echo esc_html($etn_schedule_topic); ?></h4>
                                        <p><?php echo Helper::render($etn_schedule_objective) ; ?></p>
                                        <div class='etn-schedule-content'>
                                            <div class='etn-schedule-speaker'>
                                                <?php
                                                $speaker_avatar = apply_filters("etn/speakers/avatar", \Wpeventin::assets_url() . "images/avatar.jpg");
                                                if (is_array($etn_schedule_speaker) && !empty($etn_schedule_speaker)) {
                                                    foreach ($etn_schedule_speaker as $key => $value) {
                                                        $etn_schedule_single_speaker = get_post($value);
                                                        $etn_speaker_permalink = get_post_permalink($value);
                                                        $speaker_title = $etn_schedule_single_speaker->post_title;
                                                        ?>
                                                        <div class='etn-schedule-single-speaker'>
                                                            <a href='<?php echo esc_url($etn_speaker_permalink); ?>'>
                                                                <?php if(has_post_thumbnail($value)): ?>
                                                                    <?php echo get_the_post_thumbnail($value); ?>
                                                                <?php else: ?>
                                                                <img src='<?php echo esc_url($speaker_avatar); ?>' alt='<?php echo esc_attr($speaker_title); ?>'>
                                                                <?php endif; ?>
                                                            </a>
                                                            <span class='etn-schedule-speaker-title'><?php echo esc_html($speaker_title); ?></span>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            endforeach; 
                        }
                        ?>
                    </div>
                    <!-- end repeatable item -->
                    <?php 
                endforeach;
            }
            wp_reset_postdata(); ?>
        </div>
    </div>
    <!-- schedule tab end -->
    <?php 
}