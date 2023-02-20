<?php
/**
 * SEO Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_general_seo( $wp_customize ) {
    
    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'the-conference' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Toggle_Control( 
			$wp_customize,
			'ed_post_update_date',
			array(
				'section'     => 'seo_settings',
				'label'	      => __( 'Enable Last Update Post Date', 'the-conference' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'the-conference' ),
			)
		)
	);
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Toggle_Control( 
			$wp_customize,
			'ed_breadcrumb',
			array(
				'section'     => 'seo_settings',
				'label'	      => __( 'Enable Breadcrumb', 'the-conference' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'the-conference' ),
			)
		)
	);
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'the-conference' ),
        )
    );  
    /** SEO Settings Ends */
    
}
add_action( 'customize_register', 'the_conference_customize_register_general_seo' );