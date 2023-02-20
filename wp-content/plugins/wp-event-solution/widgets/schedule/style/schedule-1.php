<?php
use \Etn\Utils\Helper as Helper;

$post_perpage = 3;
if ( did_action( 'eventin-pro/after_load'  ) ) {
    $post_perpage = 50;
}

$data = Helper::post_data_query( 'etn-schedule' , $post_perpage, $order, null , null ,  $etn_schedule_ids ,
null , null , "meta_value" , "etn_schedule_date"  );

if ( is_array( $data ) && !empty( $data ) ) {
    
$i = -1;
?>
<!-- schedule tab start -->
<div class="schedule-tab-wrapper etn-tab-wrapper">
    <ul class='etn-nav'>
        <?php
        $i = -1;
        foreach( $data as $value )  :
            $i++;
            $schedule_meta = get_post_meta($value->ID);
            $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
            $schedule_date = date_i18n("d M", $schedule_date);
            $active_class = (($i == 0) ? 'etn-active' : ' ');
            ?>
            <li>
                <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr( $value->ID ) . "-" . $i; ?>'>
                    <span class='etn-date'><?php echo esc_html($schedule_date); ?></span>
                    <span class=etn-day><?php echo esc_html( get_the_title( $value->ID ) ); ?></span>
                </a>
               
            </li>
        <?php endforeach; ?>
    </ul>
    <div class='etn-tab-content clearfix etn-schedule-wrap'>
        <?php
            $time_format = Helper::get_option("time_format");
            $time_format = !empty( $time_format ) ? $time_format : '12';
            $etn_sched_time_format = ( $time_format == '24') ? "H:i": get_option( 'time_format' ); 

        $j = -1;
        foreach ($data as $post) :
            $j++;
            $schedule_meta = get_post_meta($post->ID);
            $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
            $schedule_topics = unserialize($schedule_meta['etn_schedule_topics'][0]);
            $schedule_date = date_i18n("d M", $schedule_date);
            $active_class = (($j == 0) ? 'tab-active' : ' ');
            ?>
            <!-- start repeatable item -->
            <div class='etn-tab <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($post->ID) . "-" . $j; ?>'>
                <?php
                if( is_array( $schedule_topics ) && !empty( $schedule_topics )){
                    foreach ($schedule_topics as $topic) {
                        $etn_schedule_topic         = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
                        $etn_schedule_start_time    = !empty($topic['etn_shedule_start_time']) ? date_i18n($etn_sched_time_format, strtotime($topic['etn_shedule_start_time'])) : '';
                        $etn_schedule_end_time      = !empty($topic['etn_shedule_end_time']) ? date_i18n($etn_sched_time_format, strtotime($topic['etn_shedule_end_time'])) : '';
                        $etn_schedule_room          = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
                        $etn_schedule_objective     = (isset($topic['etn_shedule_objective']) ? $topic['etn_shedule_objective'] : '');
                        $etn_schedule_speaker       = (isset($topic['etn_shedule_speaker']) ? $topic['etn_shedule_speaker'] : []);
                        ?>
                        <div class='etn-single-schedule-item etn-row'>
                            <div class='etn-schedule-info etn-col-lg-3 etn-col-sm-3'>
                                <?php
                                if(!empty($etn_schedule_start_time) || !empty( $etn_schedule_end_time )){
                                    ?>
                                    <span> <?php echo esc_attr($etn_schedule_start_time); ?> - <?php echo esc_attr($etn_schedule_end_time); ?> </span>
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
                            <div class='etn-schedule-content etn-col-lg-6 etn-col-sm-6'>
                                <h4 class='etn-title'><?php echo esc_html($etn_schedule_topic); ?></h4>
                                <p><?php echo Helper::render($etn_schedule_objective); ?></p>
                            </div>
                            <div class='etn-col-lg-3 etn-col-sm-3'>
                                <div class='etn-schedule-right-content'>
                                        <div class='etn-schedule-single-speaker'>
                                            <div class='etn-schedule-speaker'>
                                            <?php
                                                $speaker_avatar = apply_filters("etn/speakers/avatar", \Wpeventin::assets_url() . "images/avatar.jpg");
                                                if ( is_array( $etn_schedule_speaker ) && !empty($etn_schedule_speaker) ) {
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
                                                                        <img src='<?php echo esc_url( $speaker_avatar);?>' alt='<?php echo esc_attr($speaker_title);?>'>
                                                                    <?php endif; ?>
                                                                </a>
                                                                <span class='etn-schedule-speaker-title'><?php echo esc_html($speaker_title);?></span>
                                                            </div>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                }
                ?>
            </div>
            <!-- end repeatable item -->
        <?php endforeach; ?>
    </div>
</div>
<!-- schedule tab end -->
<?php }else{ ?>
        <p class="etn-not-found-post"><?php echo esc_html__('No Post Found', 'eventin'); ?></p>
    <?php
    }