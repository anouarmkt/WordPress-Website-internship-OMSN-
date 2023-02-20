<?php
/**
 * About Section
 * 
 * @package The Conference
 */
if( is_active_sidebar( 'about' ) ){ ?>
<section id="about_section" class="about-section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">
	<div class="container">
    	<?php dynamic_sidebar( 'about' ); ?>
    </div>
</section><!-- .about-section -->
<?php
}