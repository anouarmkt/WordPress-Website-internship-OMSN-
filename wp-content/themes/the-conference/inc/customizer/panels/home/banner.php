<?php
/**
 * Banner Section
 *
 * @package The Conference
 */

function the_conference_customize_register_frontpage_banner( $wp_customize ) {
	
    $wp_customize->get_section( 'header_image' )->panel                    = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'the-conference' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'the_conference_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'the_conference_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'the_conference_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> 'static_banner',
			'sanitize_callback' => 'the_conference_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new The_Conference_Select_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'the-conference' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'the-conference' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'the-conference' ),
                    'static_banner'    => __( 'Static/Video CTA Banner', 'the-conference' ),
                ),
                'priority' => 5	
     		)            
		)
	);
    
    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Lepiza Announces New Design', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Event Title', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'the_conference_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector'        => '#banner_section .container h2.banner-title',
        'render_callback' => 'the_conference_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'October 10 & 11 - Berlin, Germany', 'the-conference' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Event description', 'the-conference' ),
            'section'         => 'header_image',
            'active_callback' => 'the_conference_banner_ac',
            'type'            => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector' => '#banner_section .static-banner .container .banner-desc',
        'render_callback' => 'the_conference_get_banner_sub_title',
    ) );
    
    /** Banner Label One */
    $wp_customize->add_setting(
        'banner_label_one',
        array(
            'default'           => __( 'VIEW SCHEDULE', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_label_one',
        array(
            'label'           => __( 'Banner Label One', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'the_conference_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_label_one', array(
        'selector' => '#banner_section .static-banner .container .btn-wrap a.btn-transparent',
        'render_callback' => 'the_conference_get_banner_btn_label_one',
    ) );

    /** Banner Link One */
    $wp_customize->add_setting(
        'banner_link_one',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_link_one',
        array(
            'label'           => __( 'Banner Link One', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'the_conference_banner_ac'
        )
    );

    /** Banner Label Two */
    $wp_customize->add_setting(
        'banner_label_two',
        array(
            'default'           => __( 'BUY TICKET NOW', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_label_two',
        array(
            'label'           => __( 'Banner Label Two', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'the_conference_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_label_two', array(
        'selector' => '#banner_section .static-banner .container .btn-wrap a.btn-filled',
        'render_callback' => 'the_conference_get_banner_btn_label_two',
    ) );
    
    /** Banner Link Two*/
    $wp_customize->add_setting(
        'banner_link_two',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_link_two',
        array(
            'label'           => __( 'Banner Link Two', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'the_conference_banner_ac'
        )
    );

    /** Enable Banner Timer */
    $wp_customize->add_setting(
        'ed_banner_event_timer',
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_banner_event_timer',
            array(
                'section'         => 'header_image',
                'label'           => __( 'Show Upcoming Event', 'the-conference' ),
                'description'     => __( 'Enable to show upcoming event timer.', 'the-conference' ),
                'active_callback' => 'the_conference_banner_ac'
            )
        )
    );

    /** Banner Timer */
    $wp_customize->add_setting(
        'banner_event_timer',
        array(
            'default'           => '2020-08-20', 
            'sanitize_callback' => 'the_conference_sanitize_date'
        )
    );
    
     $wp_customize->add_control(
        'banner_event_timer',
        array(
            'label'           => __( 'Event Date', 'the-conference' ),
            'description'     => __( 'Select upcoming event date.', 'the-conference' ),
            'section'         => 'header_image',
            'type'            => 'date',
            'active_callback' => 'the_conference_banner_ac'
        )            
    );

}
add_action( 'customize_register', 'the_conference_customize_register_frontpage_banner' );