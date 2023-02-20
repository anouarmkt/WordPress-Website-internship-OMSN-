<?php

defined('ABSPATH') || die();

?>
<div class="etn-speaker-thumb">
    <?php 
    if ( get_the_post_thumbnail_url( get_the_ID( ) ) ) { 
        ?>
        <?php do_action( "etn_before_speaker_archive_thumbnail" ); ?>

        <a href="<?php echo esc_url( get_the_permalink( get_the_ID( ) ) ); ?>" class="etn-img-link">
            <?php echo get_the_post_thumbnail(get_the_ID()); ?>
        </a>

        <?php do_action( "etn_after_speaker_archive_thumbnail" ); ?>

        <?php 
    } 
    ?>
    <div class="etn-speakers-social">
        <?php 
        $social = get_post_meta( get_the_ID( ) , 'etn_speaker_socials', true);
        if (is_array($social)  & !empty( $social )) { 
            ?>
            <?php foreach ($social as $social_value) {  ?>
                <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>">
                    <i class="<?php echo esc_attr($social_value["icon"]); ?>"></i>
                </a>
            <?php  } ?>
            <?php 
        } 
        ?>
    </div>
</div>