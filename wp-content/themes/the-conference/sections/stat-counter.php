<?php
/**
 * Stat Counter Section
 * 
 * @package The Conference
 */

$default_bg      =  get_template_directory_uri() . '/images/counter-bg.jpg';
$stat_counter_bg =  get_theme_mod( 'stat_counter_bg_image', $default_bg );
$style           = '';

if( $stat_counter_bg ){
	$style = ' style="background-image: url('. esc_url( $stat_counter_bg ) .'); background-repeat: no-repeat;"';
}

if( is_active_sidebar( 'stat-counter' ) ){ ?>
	<section id="stat-counter_section" class="counter-section"<?php echo $style ?>>
		<div class="container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
	    	<?php dynamic_sidebar( 'stat-counter' ); ?>
	    </div>
	</section> <!-- .testimonial-section -->
<?php
}