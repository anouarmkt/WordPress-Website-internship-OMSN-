<?php 
defined( 'ABSPATH' ) || exit;

if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
?>
<div class="etn-single-speaker-wrapper">
	<div class="etn-row">
		<div class="etn-col-lg-5">
			<div class="etn-speaker-info">
				<?php 
				$speaker_avatar = apply_filters("etn/speakers/avatar", \Wpeventin::assets_url() . "images/avatar.jpg");
				$speaker_thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url() : $speaker_avatar;
				?>
				<div class="etn-speaker-thumb">
					<img src="<?php echo esc_url( $speaker_thumbnail ); ?>" height="150" width="150" alt="<?php the_title_attribute(); ?>"/>
				</div>
				<?php 

						/**
				* Speaker meta hook.
				*
				* @hooked etn_speaker_company_logo - 12
				*/
				do_action('etn_speaker_company_logo');
				

				/**
				* Speaker title before hook.
				*
				* @hooked speaker_title_before - 10
				*/
				do_action('etn_speaker_title_before');
				?>

				<h3 class="etn-title etn-speaker-name"> 
					<?php echo esc_html( apply_filters('etn_speaker_title', get_the_title()) ); ?> 
				</h3>

				<?php
				/**
				* Speaker title after hook.
				*
				* @hooked speaker_name - 12
				*/
				do_action('etn_speaker_title_after');

				/**
				* Speaker designation hook.
				*
				* @hooked speaker_designation - 13
				*/
				 do_action( "etn_speaker_designation" ); 

					/**
				* Speaker meta hook.
				*
				* @hooked speaker_meta - 12
				*/
				do_action('etn_speaker_meta');

		

					
				
				/**
				* Speaker summary hook.
				*
				* @hooked speaker_summary - 14
				*/
				 do_action( "etn_speaker_summary" ); 

				/**
				* Speaker social links.
				*
				* @hooked speaker_socials - 15
				*/
				 do_action( "etn_speaker_socials" ); 

				 ?>
			</div>
		</div>
		<div class="etn-col-lg-7">
			<div class="etn-schedule-wrap">
				<?php
					$orgs = \Etn\Utils\Helper::speaker_sessions( get_the_ID());

					if( is_array( $orgs ) && !empty( $orgs ) ) {

						foreach ( $orgs as $org ) {
							
							$etn_schedule_meta_value = maybe_unserialize( $org['meta_value'] );
							
							if( is_array( $etn_schedule_meta_value ) && !empty( $etn_schedule_meta_value ) ){
								foreach ($etn_schedule_meta_value as $single_meta) {
				
									$speaker_schedules = isset($single_meta["etn_shedule_speaker"]) && is_array($single_meta["etn_shedule_speaker"]) ? $single_meta["etn_shedule_speaker"]: [];
									if ( in_array( get_the_ID(), $speaker_schedules ) ) {

										/**
										* Speaker schedule details before.
										*
										* @hooked speaker_details_before - 16
										*/
										do_action( 'etn_speaker_details_before' );

										?>
										<div class="etn-single-schedule-item etn-row">
											<div class="etn-schedule-info etn-col-lg-4">
												<?php 

													/**
													* Speaker schedule time hook.
													*
													* @hooked schedule_time - 17
													*/
													do_action( 'etn_schedule_time' , $single_meta["etn_shedule_start_time"] , $single_meta["etn_shedule_end_time"] );

													/**
													* Speaker schedule location hook.
													*
													* @hooked schedule_locations - 18
													*/
													do_action( 'etn_schedule_locations' , $single_meta["etn_shedule_room"]  );
												?>
											</div>
											<div class="etn-schedule-content etn-col-lg-8">
												<?php

													/**
													* Speaker topic hook.
													*
													* @hooked speaker_topic - 19
													*/
													 do_action( 'etn_speaker_topic' , $single_meta["etn_schedule_topic"]  );

													/**
													* Speaker objective hook.
													*
													* @hooked speaker_objective - 20
													*/
													do_action( 'etn_speaker_objective' , $single_meta["etn_shedule_objective"]  );
												?>
											</div>
										</div>
										
										<?php
											/**
											* Speaker details after hook.
											*
											* @hooked speaker_details_after - 21
											*/
											do_action( 'etn_speaker_details_after' );
									}
								}
							}
							
						}
					}?>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
