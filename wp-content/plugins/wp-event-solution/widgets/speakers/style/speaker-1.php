<?php

use \Etn\Utils\Helper;

$speaker_id = $settings["speaker_id"];
$data['etn_speaker_designation'] = get_post_meta($speaker_id, 'etn_speaker_designation', true);
$data['social']          = get_post_meta($speaker_id, 'etn_speaker_socials', true);
$data['summery']  = get_post_meta($speaker_id, 'etn_speaker_summery', true);
$all_logo = get_post_meta($speaker_id, 'etn_speaker_company_logo', true);
$data['logo']     = isset($all_logo) ? wp_get_attachment_image_src($all_logo) : array();
$data['photo']    = has_post_thumbnail($speaker_id) ? get_the_post_thumbnail_url($speaker_id) : null;

if (is_array($data)) :
    $social = array_key_exists("social", $data) ? $data["social"] : [];
    ?>
    <div class="etn-speaker-wrapper">
        <div class="etn-speaker-item">
            <div class="etn-speaker-thumb">
                <?php if (!is_null($data["photo"])) : ?>
                    <?php echo get_the_post_thumbnail($speaker_id); ?>
                <?php endif; ?>
                <div class="etn-speakers-social">
                    <?php if (is_array($social) && !empty( $social )) { ?>
                        <?php foreach ($social as $social_value) {  ?>
                            <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>"><i class="<?php echo esc_attr($social_value["icon"]); ?>"></i></a>
                        <?php  } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="etn-speaker-content">
                <h3 class="etn-title etn-speaker-title"><a href="<?php echo esc_url(get_the_permalink($speaker_id)); ?>"> <?php echo esc_html(get_the_title($speaker_id)); ?></a> </h3>
                <p>
                    <?php echo Helper::kses($data['etn_speaker_designation']); ?>
                </p>
            </div>
        </div>
    </div>
<?php
endif;
