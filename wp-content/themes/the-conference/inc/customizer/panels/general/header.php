<?php
/**
 * Header Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_general_header( $wp_customize ) {
    
    /** Header Settings */
    $wp_customize->add_section(
        'header_settings',
        array(
            'title'    => __( 'Header Settings', 'the-conference' ),
            'priority' => 20,
            'panel'    => 'general_settings',
        )
    );

    /** Custom Link label  */
    $wp_customize->add_setting(
        'custom_link_label',
        array(
            'default'           => __( 'BUY TICKET', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'custom_link_label',
        array(
            'label'           => __( 'Custom Link Label', 'the-conference' ),
            'description'     => __( 'Add custom link button label in header.', 'the-conference' ),
            'section'         => 'header_settings',
            'type'            => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'custom_link_label', array(
        'selector' => '.site-header .nav-btn a.btn.custom-link',
        'render_callback' => 'the_conference_header_custom_link_selective_refresh',
    ) );

    /** Custom Link */
    $wp_customize->add_setting(
        'custom_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'custom_link',
        array(
            'label'           => __( 'Custom link Url', 'the-conference' ),
            'description'     => __( 'Add custom link url in header.', 'the-conference' ),
            'section'         => 'header_settings',
            'type'            => 'url',
        )
    );
    
    /** Open Link in new tab */
    $wp_customize->add_setting( 
        'ed_custom_link_tab', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_custom_link_tab',
            array(
                'section'         => 'header_settings',
                'label'           => __( 'Open Link in New Tab', 'the-conference' ),
                'description'     => __( 'Enable to open link in new tab.', 'the-conference' ),
            )
        )
    );
    
    /** Header Settings Ends */
}
add_action( 'customize_register', 'the_conference_customize_register_general_header' );