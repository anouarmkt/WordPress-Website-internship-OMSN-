<?php
defined( 'ABSPATH' ) || exit;

$etn_speaker_company_logo       = get_post_meta(get_the_id(), 'etn_speaker_company_logo', true);
$logo                           = wp_get_attachment_image_src($etn_speaker_company_logo, 'large');

if ( !empty($logo) && $logo[0] != '') : ?>
    <div class="etn-speaker-logo">
        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php the_title_attribute(); ?>">
    </div>
<?php endif; 