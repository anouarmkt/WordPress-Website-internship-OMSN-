<?php
/**
 * Post Page Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_general_post_page( $wp_customize ) {
    
    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'the-conference' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Toggle_Control( 
			$wp_customize,
			'ed_prefix_archive',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Prefix in Archive Page', 'the-conference' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'the-conference' ),
			)
		)
	);
    
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Toggle_Control( 
			$wp_customize,
			'ed_excerpt',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Enable Blog Excerpt', 'the-conference' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'the-conference' ),
			)
		)
	);
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 55,
            'sanitize_callback' => 'the_conference_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new The_Conference_Slider_Control( 
			$wp_customize,
			'excerpt_length',
			array(
				'section'	  => 'post_page_settings',
				'label'		  => __( 'Excerpt Length', 'the-conference' ),
				'description' => __( 'Automatically generated excerpt length (in words).', 'the-conference' ),
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 100,
					'step'	=> 5,
				)                 
			)
		)
	);
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'CONTINUE READING', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'the-conference' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-readmore',
        'render_callback' => 'the_conference_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new The_Conference_Note_Control( 
			$wp_customize,
			'post_note_text',
			array(
				'section'	  => 'post_page_settings',
                /* translators: 1: horizontal line tag */
                'description' => sprintf( __( '%s These options affect your individual posts.', 'the-conference' ), '<hr/>' ),
			)
		)
    );

    /** Hide Author Section */
    $wp_customize->add_setting( 
        'ed_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Author Section', 'the-conference' ),
                'description' => __( 'Enable to hide author section.', 'the-conference' ),
            )
        )
    );

    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Related Posts', 'the-conference' ),
                'description' => __( 'Enable to show related posts in single page.', 'the-conference' ),
            )
        )
    );

    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'Recommended Articles', 'the-conference' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Posts Section Title', 'the-conference' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.related-posts .title',
        'render_callback' => 'the_conference_get_related_title',
    ) );

    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'the_conference_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Comments', 'the-conference' ),
                'description' => __( 'Enable to show Comments in Single Post/Page.', 'the-conference' ),
            )
        )
    );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_category',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Category', 'the-conference' ),
                'description' => __( 'Enable to hide category.', 'the-conference' ),
            )
        )
    );
    
    /** Hide Post Author */
    $wp_customize->add_setting( 
        'ed_post_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_post_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Post Author', 'the-conference' ),
                'description' => __( 'Enable to hide post author.', 'the-conference' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_post_date',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Posted Date', 'the-conference' ),
                'description' => __( 'Enable to hide posted date.', 'the-conference' ),
            )
        )
    );

    /** Hide Comment Count */
    $wp_customize->add_setting( 
        'ed_post_comment_count', 
        array(
            'default'           => false,
            'sanitize_callback' => 'the_conference_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new The_Conference_Toggle_Control( 
            $wp_customize,
            'ed_post_comment_count',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Comment Count', 'the-conference' ),
                'description' => __( 'Enable to hide comment.', 'the-conference' ),
            )
        )
    );

    /** Posts(Blog) & Pages Settings Ends */
}
add_action( 'customize_register', 'the_conference_customize_register_general_post_page' );