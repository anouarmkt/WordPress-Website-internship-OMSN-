<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package The Conference
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
    		while ( have_posts() ) : the_post();

    			get_template_part( 'template-parts/content', 'single' );

    		endwhile; // End of the loop.
    		
            /**
             * @hooked the_conference_navigation           - 15
             * @hooked the_conference_author               - 25
             * @hooked the_conference_related_posts        - 35
             * @hooked the_conference_comment              - 45
            */
            do_action( 'the_conference_after_post_content' );
        ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
