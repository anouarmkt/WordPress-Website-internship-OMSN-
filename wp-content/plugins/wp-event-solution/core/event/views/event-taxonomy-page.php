<?php

if (!defined('ABSPATH')) exit;

get_header();

global $post;

$post_slug = $post->post_name;

if ( !empty( $post_slug ) ) {

    if ( $post_slug == "etn-speaker-category" ) {
        $slug = "etn_speaker_category";
    }
		else if( ( class_exists('Wpeventin_Pro') ) && $post_slug == "etn_location" ){
			$slug = $post_slug ;
		}
    else if( $post_slug == "etn-tags" ){
        $slug = "etn_tags";
    }
    else{
        $slug = $post_slug ;
    }

    $terms = get_terms([
        'taxonomy'  => $slug,
        'hide_empty'=> false,
    ]);

    if ( !empty( $terms) ) {
        foreach ($terms as $key => $value) {
            ?>
            <li>
                <a href="<?php echo esc_url( get_term_link($value)  );?>">
                    <?php esc_html_e( $value->name )?>
                </a>
            </li>
            <?php
        }
    }
    ?>
    <?php
}

?>

<?php get_footer(); ?>