<?php
if( ! function_exists( 'the_conference_newsletter_bg_color' ) ) :
    function the_conference_newsletter_bg_color(){
        return '#dde9ed';
    }
endif;
add_filter( 'bt_newsletter_bg_color_setting', 'the_conference_newsletter_bg_color' );

if( ! function_exists( 'the_conference_add_inner_div' ) ) :
    function the_conference_add_inner_div(){
        return true;
    }
endif;
add_filter( 'bt_newsletter_shortcode_inner_wrap_display', 'the_conference_add_inner_div' );

if( ! function_exists( 'the_conference_start_inner_div' ) ) :
    function the_conference_start_inner_div(){
        echo '<div class="newsletter-wrap-inner">';
    }
endif;
add_action( 'bt_newsletter_shortcode_inner_wrap_start', 'the_conference_start_inner_div' );

if( ! function_exists( 'the_conference_end_inner_div' ) ) :
    function the_conference_end_inner_div(){
        echo '</div>';
    }
endif;
add_action( 'bt_newsletter_shortcode_inner_wrap_close', 'the_conference_end_inner_div' );

