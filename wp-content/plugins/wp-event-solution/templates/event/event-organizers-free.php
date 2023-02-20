<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

$event_options  = get_option("etn_event_options");

?>
<div class="etn-widget etn-event-organizers">
    <h4 class="etn-widget-title etn-title">
        <?php 
        $event_organizers_title = apply_filters( 'etn_event_organizers_title', esc_html__("Organizers", "eventin") );
        echo esc_html( $event_organizers_title );
         ?> 
    </h4>
    <?php
    $term_details = get_term_by('slug',  $etn_organizer_events, 'etn_speaker_category');
    
    if( !empty($term_details) ){
        $organizer_term_id = $term_details->term_id;

        $data = Helper::post_data_query('etn-speaker', -1 , 'DESC' , [$organizer_term_id] , 'etn_speaker_category' );

        if (isset( $data ) && !empty( $data )) {
            foreach ( $data as $value ) { 
                $social = get_post_meta( $value->ID , 'etn_speaker_socials', true);
                $email = get_post_meta( $value->ID , 'etn_speaker_website_email', true);
                $etn_speaker_company_logo = get_post_meta( $value->ID , 'etn_speaker_company_logo', true);
                $logo = wp_get_attachment_image_src($etn_speaker_company_logo, 'full');
                ?>
                <div class="etn-organaizer-item">
                    <?php if (isset($logo[0])) { ?>
                        <div class="etn-organizer-logo">
                            <?php echo wp_get_attachment_image($etn_speaker_company_logo, 'full'); ?>
                        </div>
                    <?php } ?>
                    <h4 class="etn-organizer-name">
                        <?php echo esc_html( get_the_title( $value->ID ) ); ?>
                    </h4>

                    <?php if ($email) { ?>
                        <div class="etn-organizer-email">
                            <span class="etn-label-name"><?php echo esc_html__('Email :', "eventin"); ?></span>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </div>
                    <?php } ?>
                    <?php if (is_array( $social ) && !empty( $social )) { ?>
                        <div class="etn-social">
                            <span class="etn-label-name"><?php echo esc_html__('Social :', "eventin"); ?></span>
                                <?php foreach ($social as $social_value) {  ?>
                                    <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social_value['icon']); ?>

                                    <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>"><i class="<?php echo esc_attr($social_value["icon"]); ?>" rel="noopener"></i></a>
                                <?php  } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php  }
        }
    }
    ?>
</div>