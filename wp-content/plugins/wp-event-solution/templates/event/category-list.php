<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

$data                   = Helper::single_template_options( $single_event_id );
$etn_event_socials      = isset( $data['etn_event_socials']) ? $data['etn_event_socials'] : [];
?>
<div class="etn-event-meta">
    <div class="etn-event-category">
        <?php
        global $post;
        $etn_cat_terms = wp_get_post_terms( $single_event_id, 'etn_category');
        if ($etn_cat_terms) {
            $output = array();
            if( is_array( $etn_cat_terms ) ){
                foreach ($etn_cat_terms as $term) {
                    $term_link =  get_term_link($term->slug, 'etn_category');
                    $output[] = '<a  href="' . $term_link . '">' . $term->name . '</a>';
                }
            }
            echo "<span>" . Helper::kses(join(' ', $output)) . "</span>";
        }
        ?>
    </div>
    <div class="etn-event-social-wrap">
        <i class="fas fa-share-alt"></i>
        <div class="etn-social">
            <?php if (is_array($etn_event_socials)) : ?>
                <?php foreach ($etn_event_socials as $social) : ?>
                    <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social['icon']); ?>
                    <a href="<?php echo esc_url($social['etn_social_url']); ?>" target="_blank" rel="noopener" class="<?php echo esc_attr($etn_social_class); ?>"> <i class="<?php echo esc_attr($social['icon']); ?>"></i> </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>