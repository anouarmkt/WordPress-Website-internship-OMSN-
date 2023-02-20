<?php
/**
 * Filter to modify functionality of RTC plugin.
 *
 * @package The Conference
 */

if( ! function_exists( 'the_conference_featured_page_widget_image_align_filter' ) ){
	/**
	 * Filter to set image postion
	 */    
	function the_conference_featured_page_widget_image_align_filter(){
		return 'left';
	}
}
add_filter( 'rrtc_featured_img_alignment', 'the_conference_featured_page_widget_image_align_filter' );

if( ! function_exists( 'the_conference_featured_page_widget_image_size_filter' ) ){
	/**
	 * Filter to set image size
	 */    
	function the_conference_featured_page_widget_image_size_filter(){
		return 'the-conference-featured-page';
	}
}
add_filter( 'rrtc_featured_img_size', 'the_conference_featured_page_widget_image_size_filter' );

if( ! function_exists( 'the_conference_icon_Text_widget_image_size_filter' ) ){
	/**
	 * Filter to set image size of icon text widget
	 */    
	function the_conference_icon_Text_widget_image_size_filter(){
		return 'the-conference-icon-text-image';
	}
}
add_filter( 'itw_icon_img_size', 'the_conference_icon_Text_widget_image_size_filter' );

if( ! function_exists( 'the_conference_team_member_image_size' ) ){
	/**
	 * Filter to define image size in team member section widget
	 */
	function the_conference_team_member_image_size(){
		return 'the-conference-speaker';
	}
}
add_filter( 'tmw_icon_img_size', 'the_conference_team_member_image_size' );

if( ! function_exists( 'the_conference_cta_btn_alignment_filter' ) ){
	/**
	 * Filter to add btn alignment of cta section widget
	 */    
	function the_conference_cta_btn_alignment_filter(){
		return 'centered';
	}
}
add_filter( 'rrtc_cta_btn_alignment', 'the_conference_cta_btn_alignment_filter' );

if( ! function_exists( 'the_conference_cta_bg_color_filter' ) ){
	/**
	 * Filter to add background color of CTA widget.
	 */    
	function the_conference_cta_bg_color_filter(){
		return '#57b9a8';
	}
}
add_filter( 'rrtc_cta_bg_color', 'the_conference_cta_bg_color_filter' );

if( ! function_exists( 'the_conference_testimonial_widget_image_size_filter' ) ){
	/**
	 * Filter to add image size of testimonial widget.
	 */    
	function the_conference_testimonial_widget_image_size_filter(){
		return 'the-conference-featured-page';
	}
}
add_filter( 'icon_img_size', 'the_conference_testimonial_widget_image_size_filter' );

if( ! function_exists( 'the_conference_portfolio_single_navigation' ) ) :
    /**
     * Filter to add navigation in portfolio single page
     */
    function the_conference_portfolio_single_navigation(){
        the_conference_navigation();
    }
endif; 
add_filter ( 'rrtc_portfolio_single_nav', 'the_conference_portfolio_single_navigation' );
