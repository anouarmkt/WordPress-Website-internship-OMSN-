<?php
/**
 * Appearance Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_appearance( $wp_customize ) {
    
    $wp_customize->add_panel( 
        'appearance_settings', 
        array(
            'title'       => __( 'Appearance Settings', 'the-conference' ),
            'priority'    => 25,
            'capability'  => 'edit_theme_options',
            'description' => __( 'Change color and body background.', 'the-conference' ),
        ) 
    );
    
    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel                          = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority                       = 30;
    $wp_customize->get_section( 'background_image' )->panel                = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority             = 35;
    $wp_customize->get_section( 'background_image' )->title                = __( 'Background', 'the-conference' );
    $wp_customize->get_control( 'background_color' )->description          = __( 'Background color of the theme.', 'the-conference' );  
}
add_action( 'customize_register', 'the_conference_customize_register_appearance' );