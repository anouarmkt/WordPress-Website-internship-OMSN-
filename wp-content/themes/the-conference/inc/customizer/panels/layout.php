<?php
/**
 * Layout Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_layout( $wp_customize ) {
    
    /** Layout Settings */
    $wp_customize->add_panel( 
        'layout_settings',
         array(
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Layout Settings', 'the-conference' ),
            'description' => __( 'Change different page layout from here.', 'the-conference' ),
        ) 
    );
}
add_action( 'customize_register', 'the_conference_customize_register_layout' );