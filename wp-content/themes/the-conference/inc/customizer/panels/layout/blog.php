<?php
/**
 * Home Page Layout Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_layout_blog( $wp_customize ) {
    
    /** Blog Page Layout Settings */
    $wp_customize->add_section(
        'blog_layout',
        array(
            'title'    => __( 'Blog Page Layout', 'the-conference' ),
            'priority' => 40,
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'blog_page_layout', 
        array(
            'default'           => 'classic-view',
            'sanitize_callback' => 'the_conference_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Radio_Image_Control(
			$wp_customize,
			'blog_page_layout',
			array(
				'section'	  => 'blog_layout',
				'label'		  => __( 'Blog Page Layout', 'the-conference' ),
				'description' => __( 'Choose the blog page layout for your site.', 'the-conference' ),
				'choices'	  => array(
                    'classic-view' => get_template_directory_uri() . '/images/blog/classic.jpg',
                    'list-view'    => get_template_directory_uri() . '/images/blog/listing.jpg',
				)
			)
		)
	);
    
}
add_action( 'customize_register', 'the_conference_customize_register_layout_blog' );