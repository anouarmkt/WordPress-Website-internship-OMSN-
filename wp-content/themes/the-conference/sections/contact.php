<?php
/**
 * Contact Section
 * 
 * @package The Conference
 */

if( is_active_sidebar( 'contact' ) ){ ?>
	<section id="contact_section" class="contact-form-section">
		<div class="container">
			<?php dynamic_sidebar( 'contact' ); ?>
		</div>
	</section> <!-- .contact-form-section -->
<?php
}