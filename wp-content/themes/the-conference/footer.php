<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package The Conference
 */
    
    /**
     * After Content
     * 
     * @hooked the_conference_content_end - 20
    */
    do_action( 'the_conference_before_footer' );
    
    /**
     * Footer
     * 
     * @hooked the_conference_footer_start  - 20
     * @hooked the_conference_footer_top    - 30
     * @hooked the_conference_footer_bottom - 40
     * @hooked the_conference_footer_end    - 50
    */
    do_action( 'the_conference_footer' );
    
    /**
     * After Footer
     * 
     * @hooked the_conference_page_end    - 20
    */
    do_action( 'the_conference_after_footer' );

    wp_footer(); ?>

</body>
</html>
