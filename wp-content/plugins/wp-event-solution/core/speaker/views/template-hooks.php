<?php
defined( 'ABSPATH' ) || exit;

/**
 * @see etn_before_single_speaker_content()
 */
add_action( "etn_speaker_content_before", "etn_before_single_speaker_content", 9 );

/**
 * @see etn_single_speaker_template_select()
 */
add_action( "etn_single_speaker_template", "etn_single_speaker_template_select", 9 );

/**
 * @see etn_after_single_speaker_content()
 */
add_action( "etn_speaker_content_after", "etn_after_single_speaker_content", 9 );

/**
 * @see etn_before_speaker_archive_content_show_thumbnail()
 */
add_action( 'etn_before_speaker_archive_content', 'etn_before_speaker_archive_content_show_thumbnail', 10 );

// Single page template hooks
$settings      = \Etn\Core\Settings\Settings::instance()->get_settings_option();
$template_name = isset( $settings['speaker_template'] ) && !is_null( $settings['speaker_template'] ) && !empty( $settings['speaker_template'] ) ? $settings['speaker_template'] : "speaker-one";

switch ( $template_name ) {

	case  ( ( ETN_DEMO_SITE === true ) || ( ETN_DEMO_SITE === false && 'speaker-one' === $template_name ) ) :
		style_one_lite_hook();
	case  ( ( ETN_DEMO_SITE === true ) || ( ETN_DEMO_SITE === false && 'speaker-two-lite' === $template_name ) ) :
		style_two_lite_hook();
		break;
	
}

function style_one_lite_hook() {

	//add all hooks for left block

    /**
     * @see speaker_title_before()
     */
    add_action( "etn_speaker_title_before", "speaker_title_before", 10 );

    /**
     * @see speaker_title_after()
     */
    add_action( "etn_speaker_title_after", "speaker_title_after", 12 );

    /**
     * @see speaker_designation()
     */
    add_action( "etn_speaker_designation", "speaker_designation", 13 );

    /**
     * @see speaker_summary()
     */
    add_action( "etn_speaker_summary", "speaker_summary", 14 );

    /**
     * @see speaker_socials()
     */
    add_action( "etn_speaker_socials", "speaker_socials", 15 );

	/**
	 * @see speaker_meta()
	 */
	add_action( "etn_speaker_meta", "speaker_meta", 15 );

	
	//add all hooks for left block

    /**
     * @see speaker_details_before()
     */
    add_action( "etn_speaker_details_before", "speaker_details_before", 16 );

    /**
     * @see schedule_time()
     */
    add_action( "etn_schedule_time", "schedule_time", 10, 2 );

    /**
     * @see schedule_locations()
     */
    add_action( "etn_schedule_locations", "schedule_locations", 18, 1 );

    /**
     * @see speaker_topic()
     */
    add_action( "etn_speaker_topic", "speaker_topic", 19, 1 );

    /**
     * @see speaker_objective()
     */
    add_action( "etn_speaker_objective", "speaker_objective", 20, 1 );

    /**
     * @see speaker_details_after()
     */
    add_action( "etn_speaker_details_after", "speaker_details_after", 21 );

}

function style_two_lite_hook(){
    	//add all hooks for left block

		/**
		 * @see speaker_company_logo()
		 */
		add_action("etn_speaker_company_logo", "speaker_company_logo" , 10 );


		/**
		 * @see speaker_title_before()
		 */
		add_action( "etn_speaker_title_before", "speaker_title_before", 10 );

		/**
		 * @see speaker_title_after()
		 */
		add_action( "etn_speaker_title_after", "speaker_title_after", 12 );

		/**
		 * @see speaker_designation()
		 */
		add_action( "etn_speaker_designation", "speaker_designation", 13 );

		/**
		 * @see speaker_summary()
		 */
		add_action( "etn_speaker_summary", "speaker_summary", 14 );

		/**
		 * @see speaker_meta()
		 */
		add_action( "etn_speaker_meta", "speaker_meta", 15 );
		/**
		 * @see speaker_socials()
		 */
		add_action( "etn_speaker_socials", "speaker_socials", 15 );


		/**
		 * @see speaker_two_sessions_title()
		 */
		add_action("etn_speaker_two_lite_sessions_title", "speaker_two_lite_sessions_title", 15 );

	
		/**
		 * @see speaker_two_sessions_details()
		 */
		add_action("etn_speaker_two_lite_sessions_details", "speaker_two_lite_sessions_details" , 17 , 2 );
		
		/**
		 * @see speaker_two_sessions_details_after()
		 */
		add_action("etn_speaker_details_after", "speaker_details_after" , 22  );


		/**
		 * @see speaker_objective()
		 */
		add_action( "etn_speaker_objective", "speaker_objective", 20, 1 );

		/**
		 * @see schedule_four_header()
		 */
		add_action("etn_schedule_two_lite_header", "schedule_two_lite_header" , 18 , 3 );


		/**
		 * @see schedule_session_time()
		 */
		add_action("etn_schedule_two_lite_session_time", "schedule_time" , 19 , 2 );

		/**
		 * @see schedule_two_lite_session_title()
		 */
		add_action("etn_schedule_two_lite_session_title", "speaker_topic", 20 );

		/**
		 * @see schedule_two_session_location()
		 */
		add_action( "etn_schedule_two_lite_location", "schedule_locations", 18, 1 );

		
	
}
