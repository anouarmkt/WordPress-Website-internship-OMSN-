<?php
defined( 'ABSPATH' ) || exit;

use \Etn\Utils\Helper;

$etn_schedule_designation   = get_post_meta( get_the_id(), 'etn_schedule_designation', true );
$etn_schedule_company_logo  = get_post_meta( get_the_id(), 'etn_schedule_company_logo', true );
$etn_schedule_website_url   = get_post_meta( get_the_id(), 'etn_schedule_website_url', true );
$etn_schedule_website_email = get_post_meta( get_the_id(), 'etn_schedule_website_email', true );
$etn_schedule_socials       = get_post_meta( get_the_id(), 'etn_schedule_socials', true );
$logo                       = wp_get_attachment_image_src( $etn_schedule_company_logo );

?>
<div class="etn-es-single-schedule-content">
	<div class="etn-es-single-page-flex-container">
		<div class="etn-es-event-side-information">
			<?php if ( has_post_thumbnail() ): ?>
				<div class="etn-es-single-schedule-media">
					<img src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" alt=""/>
				</div>
			<?php endif;?>
		</div>
		<div class="etn-es-event-content-body">
			<div class="etn-es-schedule-header">
				<h2> <?php echo esc_html( get_the_title() ); ?> </h2>
			</div>
			<div class="meta">
				<?php if ( isset( $logo[0] ) ): ?>
					<img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php the_title_attribute(); ?>">
				<?php endif;?>
				<label> <?php echo esc_html__( "Designation ", 'eventin' ); ?> </label>
				<?php echo Helper::kses( $etn_schedule_designation ); ?>
				<label> <?php echo esc_html__( "Email ", 'eventin' ); ?> </label>
				<a href="mailto:<?php echo esc_attr( $etn_schedule_website_email ); ?>"><?php echo esc_html( $etn_schedule_website_email ); ?></a>
				<a href="<?php echo esc_url( $etn_schedule_website_url ); ?>"><?php echo esc_html__( "website ", 'eventin' ); ?> </a>
			</div>
			<hr />
			<div class="schedule-content">
				<?php the_content();?>
			</div>
			<div class="social">
				<?php if ( is_array( $etn_schedule_socials ) ): ?>
					<?php foreach ( $etn_schedule_socials as $social ): ?>
						<a href="#"> <i class="<?php echo esc_attr( $social['icon'] ); ?>"></i> </a>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>