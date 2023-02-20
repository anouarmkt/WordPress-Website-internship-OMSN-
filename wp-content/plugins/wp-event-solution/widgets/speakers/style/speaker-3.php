<?php
use \Etn\Utils\Helper as Helper;
$data = Helper::post_data_query( 'etn-speaker' , $etn_speaker_count, $etn_speaker_order , $speakers_category , 'etn_speaker_category', null, null, null, $orderby_meta, $orderby );

if ( is_array( $data ) && !empty( $data ) ) { 
    ?>
    <div class='etn-row etn-speaker-wrapper'>
        <?php
        foreach( $data as $value ) {
            $etn_speaker_designation = get_post_meta( $value->ID , 'etn_speaker_designation', true);
            $social = get_post_meta( $value->ID , 'etn_speaker_socials', true);
            ?>
            <div class="etn-col-lg-<?php echo esc_attr($etn_speaker_col); ?> etn-col-md-6">
                <div class="etn-speaker-item style-3">
                    <div class="etn-speaker-thumb">
                        <?php 
                        if ( get_the_post_thumbnail_url( $value->ID ) ) { 
                            ?>
                            <a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" class="etn-img-link">
                                <?php echo get_the_post_thumbnail($value->ID); ?>
                            </a>
                            <?php 
                        } 
                        ?>
                    </div>
                    <div class="etn-speaker-content">
                        <h3 class="etn-title etn-speaker-title"><a href="<?php echo esc_url(get_the_permalink($value->ID)); ?>"> <?php echo esc_html(get_the_title( $value->ID )); ?></a> </h3>
                        <p>
                            <?php echo Helper::kses($etn_speaker_designation); ?>
                        </p>
                        <div class="etn-speakers-social">
                            <?php
                            if (is_array($social)  & !empty( $social )) {
                                ?>
                                <?php
                                foreach ($social as $social_value) {
                                    ?>
                                    <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>">
                                        <i class="<?php echo esc_attr($social_value["icon"]); ?>"></i>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php 
} else { 
    ?>
    <p class="etn-not-found-post"><?php echo esc_html__('No Post Found', 'eventin'); ?></p>
    <?php
}