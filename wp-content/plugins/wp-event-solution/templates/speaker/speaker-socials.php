<?php
defined( 'ABSPATH' ) || exit;

$etn_speaker_socials            = get_post_meta(get_the_id(), 'etn_speaker_socials', true);
?>
    <div class="etn-social">
        <?php if (is_array( $etn_speaker_socials ) ) : ?>
            <?php foreach ($etn_speaker_socials as $social) : ?>
                <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social['icon']); ?>
                <a href="<?php echo esc_url($social['etn_social_url']); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>"> <i class="<?php echo esc_attr($social['icon']); ?>"  rel="noopener"></i> </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>