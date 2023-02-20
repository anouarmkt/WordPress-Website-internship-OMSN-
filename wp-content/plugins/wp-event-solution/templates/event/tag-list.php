<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;
?>
<div class="etn-event-tag-list">
    <?php
    global $post;
    $etn_terms = wp_get_post_terms($single_event_id, 'etn_tags');
    if ($etn_terms) {
        ?>
        <h4 class="etn-tags-title">
            <?php 
            $tag_title = apply_filters( 'etn_event_tag_list_title', esc_html__('Tags', "eventin") ); 
            echo esc_html( $tag_title );
            ?>
        </h4>
        <?php
        $output = array();

        if( is_array( $etn_terms ) ){
            foreach ($etn_terms as $term) {
                $term_link =  get_term_link($term->slug, 'etn_tags');
                $output[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
            }
        }
        echo Helper::kses( join(' ', $output) );
    }
    ?>
</div>