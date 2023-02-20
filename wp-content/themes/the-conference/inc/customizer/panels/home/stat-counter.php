<?php
/**
 * Stat Counter Section
 *
 * @package The Conference
 */

function the_conference_customize_register_frontpage_stat_counter( $wp_customize ){

    /** Stat Counter Section */
    $wp_customize->add_section(
        'stat_counter_section',
        array(
            'title'    => __( 'Stat Counter Section', 'the-conference' ),
            'priority' => 25,
            'panel'    => 'frontpage_settings',
        )
    );

   /** Background Image */
    $wp_customize->add_setting(
        'stat_counter_bg_image',
        array(
            'default'           => get_template_directory_uri() . '/images/counter-bg.jpg',
            'sanitize_callback' => 'the_conference_sanitize_image',
        )
    );
    
    $wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'stat_counter_bg_image',
           array(
               'label'    => __( 'Background Image', 'the-conference' ),
               'section'  => 'stat_counter_section',
               'priority' => -1
           )
       )
    );

    $stat_counter_section = $wp_customize->get_section( 'sidebar-widgets-stat-counter' );

    if ( ! empty( $stat_counter_section ) ) {
        $stat_counter_section->panel = 'frontpage_settings';
        $stat_counter_section->priority = 25;
        $wp_customize->get_control( 'stat_counter_bg_image' )->section  = 'sidebar-widgets-stat-counter';
    }
}
add_action( 'customize_register', 'the_conference_customize_register_frontpage_stat_counter' );