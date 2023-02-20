<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package The Conference
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<section class="error-404 not-found">
				<div class="page-content">
					<p class="error-text"><?php esc_html_e( 'The page you are looking for may have been moved, deleted, or possibly never existed.', 'the-conference' ); ?></p>
					<div class="error-num"><?php esc_html_e( '404', 'the-conference' ); ?></div>
					<a class="bttn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Take me to the home page', 'the-conference' ); ?></a>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section>
			<?php 
			    /**
	             * @see the_conference_latest_posts
	             */
	            do_action( 'the_conference_latest_posts' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->
    
    <?php
get_footer();
