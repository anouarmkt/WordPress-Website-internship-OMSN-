<?php
/**
 * Eventin Template Hooks
 *
 * Action/filter hooks used for Eventin functions/templates.
 *
 * @package Eventin\Templates
 * @version 2.3.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * @see etn_before_single_event_content()
 */
add_action( "etn_event_content_before", "etn_before_single_event_content", 10 );

/**
 * @see etn_after_single_event_content()
 */
add_action( "etn_event_content_after", "etn_after_single_event_content", 10 );

/**
 * @see etn_single_event_template_select()
 */
add_action( "etn_single_event_template", "etn_single_event_template_select", 10 );

/**
 * @see etn_after_event_archive_content_show_footer()
 */
add_action( 'etn_after_event_archive_content', 'etn_after_event_archive_content_show_footer', 10 );

/**
 * @see etn_before_event_archive_content_show_thumbnail()
 */
add_action( 'etn_before_event_archive_content', 'etn_before_event_archive_content_show_thumbnail', 10 );

/**
 * @see etn_after_event_archive_title_show_excerpt()
 */
add_action( 'etn_after_event_archive_title', 'etn_after_event_archive_title_show_excerpt', 10 );

/**
 * @see etn_before_event_archive_title_show_location()
 */
add_action( 'etn_before_event_archive_title', 'etn_before_event_archive_title_show_location', 10 );

/**
 * @see etn_event_archive_pagination_post_per_page()
 */
add_action( 'etn_event_archive_pagination', 'etn_event_archive_pagination_links', 10 );

/**
 * @see etn_before_recurring_event_form_content()
 */
add_action( "etn_before_recurring_event_form_content", "etn_before_recurring_event_form_content", 10, 1 );

/**
 * @see etn_after_recurring_event_form_content()
 */
add_action( "etn_after_recurring_event_form_content", "etn_after_recurring_event_form_content", 10, 1 );

/**
 * @see etn_after_single_event_meta_ticket_form()
 */
add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_ticket_form", 10 );

/**
 * @see etn_after_single_event_meta_recurring_event_ticket_form()
 */
add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_meta_recurring_event_ticket_form", 9 );

/**
 * @see etn_after_single_event_meta_add_calendar()
 */
add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_add_to_calendar", 11, 1 );


$current_template = !empty( \Etn\Core\Settings\Settings::instance()->get_settings_option()['event_template'] ) ? \Etn\Core\Settings\Settings::instance()->get_settings_option()['event_template'] : "event-one";

if ( ( ETN_DEMO_SITE == true ) || ( ETN_DEMO_SITE === false && "event-one" === $current_template ) ) {

    /**
     * @see etn_before_single_event_details()
     */
    add_action( 'etn_before_single_event_details', 'etn_before_single_event_details' );

    /**
     * @see etn_before_single_event_container()
     */
    add_action( 'etn_before_single_event_container', 'etn_before_single_event_container' );

    /**
     * @see etn_before_single_event_content_wrap()
     */
    add_action( 'etn_before_single_event_content_wrap', 'etn_before_single_event_content_wrap' );

    /**
     * @see etn_after_single_event_content_schedule()
     */
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_schedule", 10 );

    /**
     * @see etn_after_single_event_content_faq()
     */
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_faq", 11 );

    /**
     * @see etn_before_single_event_content_title_show_meta()
     */
    add_action( "etn_before_single_event_content_title", "etn_before_single_event_content_title_show_meta", 10 );

    /**
     * @see etn_after_single_event_content_title()
     */
    add_action( "etn_after_single_event_content_title", "etn_after_single_event_content_title" );

    /**
     * @see etn_before_single_event_content_body()
     */
    add_action( "etn_before_single_event_content_body", "etn_before_single_event_content_body", 10 );

    /**
     * @see etn_after_single_event_content_body_show_meta()
     */
    add_action( "etn_after_single_event_content_body", "etn_after_single_event_content_body_show_meta", 10 );

    /**
     * @see etn_before_single_event_meta()
     */
    add_action( "etn_before_single_event_meta", "etn_before_single_event_meta" );

    /**
     * @see etn_single_event_meta_details()
     */
    add_action( "etn_single_event_meta", "etn_single_event_meta_details", 10 );

    /**
     * @see etn_after_single_event_meta_attendee_list()
     */
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_attendee_list", 11 );

    /**
     * @see etn_after_single_event_meta_organizers()
     */
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_organizers", 12 );

    /**
     * @see etn_after_single_event_container_related_events()
     */
    add_action( "etn_after_single_event_container", "etn_after_single_event_container_related_events", 10 );

    /**
     * @see etn_after_single_event_details()
     */
    add_action( "etn_after_single_event_details", "etn_after_single_event_details" );

}
