<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package The Conference
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Blog">
	<?php 
        /**
         * @hooked the_conference_post_thumbnail - 15
         * @hooked the_conference_entry_header   - 20 
        */
        do_action( 'the_conference_before_posts_entry_content' );
    
        /**
         * @hooked the_conference_entry_content - 15
         * @hooked the_conference_entry_footer  - 20
        */
        do_action( 'the_conference_posts_entry_content' );
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
