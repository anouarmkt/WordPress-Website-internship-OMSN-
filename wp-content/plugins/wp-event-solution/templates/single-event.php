<?php
/**
 * The Template for displaying single event
 *
 * @see         
 * @package     Eventin\Templates
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
	<?php
		/**
		 * etn_event_content_before hook.
		 */
        do_action( "etn_event_content_before" );

        // it is for testing purpose , will remove after finish work

    ?>
    
    <?php while ( have_posts() ) : ?>
        
        <?php
        //show woocommerce notice
        if ( class_exists( 'WooCommerce' ) ) {
            wc_print_notices();
        }

        ?>
        
        <?php do_action( "etn_single_event_template", the_post() ); ?>

    <?php endwhile; // end of the loop. ?>

    <?php
		/**
		 * etn_event_content_after hook.
		 */
        do_action( "etn_event_content_after" );
    ?>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */