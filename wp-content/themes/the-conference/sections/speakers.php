<?php
/**
 * Speakers Section
 * 
 * @package The Conference
 */

if( is_active_sidebar( 'speakers' ) ){ ?>
	<section id="speakers_section" class="speakers-section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
		<div class="container">
	    	<?php dynamic_sidebar( 'speakers' ); ?>
	    </div>
	</section> <!-- .service-section -->
	<?php
}