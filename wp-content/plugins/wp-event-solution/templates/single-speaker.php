<?php
/**
 * The Template for displaying single speaker
 *
 * @see         
 * @package     Eventin\Templates
 * @version     2.3.2
 */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
		/**
		 * etn_before_single_speaker_content hook.
		 */
        do_action( "etn_speaker_content_before" );
    ?>
    
    <?php while ( have_posts() ) : ?>
        
        <?php
        
        //show woocommerce notice
        if ( class_exists( 'WooCommerce' ) ) {
            wc_print_notices();
        }

        /**
		 * etn_single_speaker_template_select hook.
		 */
        do_action( "etn_single_speaker_template", the_post() ); ?>

    <?php endwhile; // end of the loop. ?>

    <?php

		/**
		 * etn_after_single_speaker_content hook.
		 */
        do_action( "etn_speaker_content_after" );
    ?>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */