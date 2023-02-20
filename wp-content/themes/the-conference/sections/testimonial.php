<?php
/**
 * Testimonial Section
 * 
 * @package The Conference
 */

if( is_active_sidebar( 'testimonial' ) ){ ?>
	<section id="testimonial_section" class="testimonial-section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
		<div class="container">
			<div class="testimonial-wdgt-wrap">
		    	<?php dynamic_sidebar( 'testimonial' ); ?>
		    </div>
		</div>
	</section> <!-- .testimonial-section -->
<?php
}