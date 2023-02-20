<?php
/**
 * Gooogle Map Section
 * 
 * @package The Conference
 */
 
if( is_active_sidebar( 'gmap' ) ){ ?>
	<section id="map_section" class="map-section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
		<?php dynamic_sidebar( 'gmap' ); ?>
	</section> <!-- .map-section -->
<?php }