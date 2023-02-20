<?php
/**
 * Blog Section
 *
 * @package The Conference
 */

function the_conference_customize_register_frontpage_blog( $wp_customize ){

    /** Blog Section */
    $wp_customize->add_section(
        'blog_section',
        array(
            'title'    => __( 'Blog Section', 'the-conference' ),
            'priority' => 75,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Blog Options */
    $wp_customize->add_setting(
        'ed_blog_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new The_Conference_Toggle_Control(
            $wp_customize,
            'ed_blog_section',
            array(
                'label'       => __( 'Enable Blog Section', 'the-conference' ),
                'description' => __( 'Enable to show blog section.', 'the-conference' ),
                'section'     => 'blog_section',
            )            
        )
    );

    /** Blog title */
    $wp_customize->add_setting(
        'blog_section_title',
        array(
            'default'           => __( 'Recent Posts', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_title',
        array(
            'section'         => 'blog_section',
            'label'           => __( 'Blog Title', 'the-conference' ),
            'active_callback' => 'the_conference_blog_section_ac'
        )
    );

    /** Selective refresh for blog title. */
    $wp_customize->selective_refresh->add_partial( 'blog_section_title', array(
        'selector'            => '#blog_section .container h2.section-title',
        'render_callback'     => 'the_conference_blog_section_title_selective_refresh',
        'container_inclusive' => false,
        'fallback_refresh'    => true,
    ) );

    /** Blog description */
    $wp_customize->add_setting(
        'blog_section_subtitle',
        array(
            'default'           => __( 'See what other people are saying about us', 'the-conference' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_subtitle',
        array(
            'section'         => 'blog_section',
            'label'           => __( 'Blog Description', 'the-conference' ),
            'active_callback' => 'the_conference_blog_section_ac'
        )
    ); 

    /** Selective refresh for blog description. */
    $wp_customize->selective_refresh->add_partial( 'blog_section_subtitle', array(
        'selector'            => '#blog_section .container .section-desc',
        'render_callback'     => 'the_conference_blog_section_description_selective_refresh',
        'container_inclusive' => false,
        'fallback_refresh'    => true,
    ) );
    
    /** View All Label */
    $wp_customize->add_setting(
        'blog_view_all',
        array(
            'default'           => __( 'SEE ALL POSTS', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_view_all',
        array(
            'label'           => __( 'View All Label', 'the-conference' ),
            'section'         => 'blog_section',
            'type'            => 'text',
            'active_callback' => 'the_conference_blog_section_ac'
        )
    );
    
    /** Selective refresh for blog readmore. */
    $wp_customize->selective_refresh->add_partial( 'blog_view_all', array(
        'selector' => '#blog_section .container .btn-wrap a.btn-filled',
        'render_callback' => 'the_conference_blog_section_view_all_btn_selective_refresh',
    ) ); 
    
    /** Blog Section Ends */  
}
add_action( 'customize_register', 'the_conference_customize_register_frontpage_blog' );