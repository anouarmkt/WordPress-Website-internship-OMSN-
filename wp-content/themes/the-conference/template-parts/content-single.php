<?php
/**
 * Template part for displaying post content in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package The Conference
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php 
        /**
         * Entry Content
         * 
         * @hooked the_conference_entry_content - 15
         * @hooked the_conference_entry_footer  - 20
         */
        do_action( 'the_conference_post_entry_content' );  
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
