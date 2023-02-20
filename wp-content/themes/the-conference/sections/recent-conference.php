<?php
/**
 * Recent Conference Section
 * 
 * @package The Conference
 */

if( is_active_sidebar( 'recent-conference' ) ){ ?>
	<section id="recent-the_conference_section" class="recent-the-conference-section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
		<div class="container">
	    	<?php dynamic_sidebar( 'recent-conference' ); ?>
	    </div>
	</section> <!-- .service-section -->
	<?php
}