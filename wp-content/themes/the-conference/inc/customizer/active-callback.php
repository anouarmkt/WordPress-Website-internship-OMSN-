<?php
/**
 * Active Callback
 * 
 * @package The Conference
*/

/**
 * Active Callback for Banner Slider
*/
function the_conference_banner_ac( $control ){
    $banner         = $control->manager->get_setting( 'ed_banner_section' )->value();
    $ed_event_timer = $control->manager->get_setting( 'ed_banner_event_timer' )->value();

    $control_id  = $control->id;
    
    if ( $control_id == 'header_image' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'header_video' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'external_header_video' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_label_one' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_link_one' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_label_two' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_link_two' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'ed_banner_event_timer' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_event_timer' && $banner == 'static_banner' && $ed_event_timer ) return true;
    
    return false;
}

/**
 * Active Callback for Blog Section
*/
function the_conference_blog_section_ac( $control ){
    $show_blog = $control->manager->get_setting( 'ed_blog_section' )->value();
    $control_id = $control->id;
    $blog      = get_option( 'page_for_posts' );

    // Blog title, description, readmore controls
    if ( $control_id == 'blog_section_title' && $show_blog ) return true;
    if ( $control_id == 'blog_section_subtitle' && $show_blog ) return true;
    if ( $control_id == 'blog_view_all' && $show_blog && $blog ) return true;
    
    return false; 
}

/**
 * Active Callback for newsletter settings
 */
function the_conference_newsletter_setting_ac( $control ){
    $ed_newsletter = $control->manager->get_setting( 'ed_newsletter' )->value();
    $ed_gradient   = $control->manager->get_setting( 'ed_newsletter_gradient' )->value();
    $control_id    = $control->id;

    // FAQ view all label
    if ( $control_id == 'ed_newsletter_gradient' && $ed_newsletter ) return true;
    if ( $control_id == 'newsletter_shortcode' && $ed_newsletter ) return true;

    return false;
}