<?php
/**
 * CTA Section
 * 
 * @package The Conference
 */

$active_widget = the_conference_number_of_widgets_in_sidebar( 'cta' );

if( is_active_sidebar( 'cta' ) ){ ?>
	<section id="cta_section" class="cta-section">
		<div class="container<?php echo esc_attr( $active_widget ); ?>">
	    	<?php dynamic_sidebar( 'cta' ); ?>
	    </div>
	</section> <!-- .bg-cta-section -->
	<?php
}