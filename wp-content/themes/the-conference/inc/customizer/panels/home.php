<?php
/**
 * Front Page Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_frontpage( $wp_customize ) {
	
    /** Front Page Settings */
    $wp_customize->add_panel( 
        'frontpage_settings',
         array(
            'priority'    => 40,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Front Page Settings', 'the-conference' ),
            'description' => __( 'Static Home Page settings.', 'the-conference' ),
        ) 
    );    
      
}
add_action( 'customize_register', 'the_conference_customize_register_frontpage' );