<?php
/**
 * The Conference Customizer Partials
 *
 * @package The Conference
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function the_conference_customize_partial_blogname() {
	$blog_name = get_bloginfo( 'name' );

    if ( $blog_name ){
        return esc_html( $blog_name );
    } else {
        return false;
    }
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function the_conference_customize_partial_blogdescription() {
	$blog_description = get_bloginfo( 'description' );

    if ( $blog_description ){
        return esc_html( $blog_description );
    } else {
        return false;
    }
}

if( ! function_exists( 'the_conference_header_custom_link_selective_refresh' ) ) :
/**
 * Header custom link 
*/
function the_conference_header_custom_link_selective_refresh(){
    $custom_link_label = get_theme_mod( 'custom_link_label', __( 'BUY TICKET', 'the-conference' ) );

    if ( $custom_link_label ){
        return esc_html( $custom_link_label );
    } else {
        return false;
    }
}
endif;

if( ! function_exists( 'the_conference_get_banner_title' ) ) :
/**
 * Banner Title
*/
function the_conference_get_banner_title(){
    $banner_title = get_theme_mod( 'banner_title', __( 'Lepiza Announces New Design', 'the-conference' ) );

    if ( $banner_title ){
        return esc_html( $banner_title );
    } else {
        return false;
    }
}
endif;

if( ! function_exists( 'the_conference_get_banner_sub_title' ) ) :
/**
 * Banner Sub Title
*/
function the_conference_get_banner_sub_title(){
    $banner_subtitle = get_theme_mod( 'banner_subtitle', __( 'October 10 & 11 - Berlin, Germany', 'the-conference' ) );

    if ( $banner_subtitle ){
        return wpautop( wp_kses_post( $banner_subtitle ) );
    } else {
        return false;
    }
}
endif;

if( ! function_exists( 'the_conference_get_banner_btn_label_one' ) ) :
/**
 * Banner Button one label
*/
function the_conference_get_banner_btn_label_one(){
    $banner_label_one = get_theme_mod( 'banner_label_one', __( 'VIEW SCHEDULE', 'the-conference' ) );

    if ( $banner_label_one ){
        return esc_html( $banner_label_one );
    } else {
        return false;
    }
}
endif;

if( ! function_exists( 'the_conference_get_banner_btn_label_two' ) ) :
/**
 * Banner Button two label
*/
function the_conference_get_banner_btn_label_two(){
    $banner_label_two = get_theme_mod( 'banner_label_two', __( 'BUY TICKET NOW', 'the-conference' ) );

    if ( $banner_label_two ){
        return esc_html( $banner_label_two );
    } else {
        return false;
    }
}
endif;

if( ! function_exists( 'the_conference_blog_section_title_selective_refresh' ) ) :
/**
 * Display blog section title
*/
function the_conference_blog_section_title_selective_refresh(){
    $blog_section_title = get_theme_mod( 'blog_section_title',  __( 'Recent Posts', 'the-conference' ) );

    if ( $blog_section_title ){
        return esc_html( $blog_section_title );
    } else {
        return false;
    }                                                              
}
endif;

if( ! function_exists( 'the_conference_blog_section_description_selective_refresh' ) ) :
/**
 * Display blog section description
*/
function the_conference_blog_section_description_selective_refresh(){
    $blog_section_subtitle = get_theme_mod( 'blog_section_subtitle',  __( 'See what other people are saying about us', 'the-conference' ) );

    if ( $blog_section_subtitle ){
        return wpautop( wp_kses_post( $blog_section_subtitle ) );
    } else {
        return false;
    }                                                              
}
endif;

if( ! function_exists( 'the_conference_blog_section_view_all_btn_selective_refresh' ) ) :
/**
 * Display blog section readmore
*/
function the_conference_blog_section_view_all_btn_selective_refresh(){
    $blog_view_all = get_theme_mod( 'blog_view_all',  __( 'SEE ALL POSTS', 'the-conference' ) );

    if ( $blog_view_all ){
        return esc_html( $blog_view_all );
    } else {
        return false;
    }                                                              
}
endif;

if( ! function_exists( 'the_conference_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function the_conference_get_related_title(){
    return get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'the-conference' ) );
}
endif;

if( ! function_exists( 'the_conference_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function the_conference_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'the-conference' );
        echo date_i18n( esc_html__( 'Y', 'the-conference' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'the-conference' );
    }
    echo '</span>'; 
}
endif;