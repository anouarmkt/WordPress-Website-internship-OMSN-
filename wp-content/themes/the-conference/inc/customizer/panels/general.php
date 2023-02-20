<?php
/**
 * General Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_general( $wp_customize ){
    
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 100,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'the-conference' ),
            'description' => __( 'Customize Header, Social, Sharing, SEO, Post/Page, Newsletter, Performance and Miscellaneous settings.', 'the-conference' ),
        ) 
    );
    
}
add_action( 'customize_register', 'the_conference_customize_register_general' );