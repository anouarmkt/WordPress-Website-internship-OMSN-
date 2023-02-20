<?php
/**
 * Newsletter Settings
 *
 * @package The Conference
 */

function the_conference_customize_register_general_newsletter( $wp_customize ) {
    
    /** Newsletter Settings */
    $wp_customize->add_section(
        'newsletter_settings',
        array(
            'title'    => __( 'Newsletter Settings', 'the-conference' ),
            'priority' => 65,
            'panel'    => 'general_settings',
        )
    );
    
    if( the_conference_is_btnw_activated() ){
		
        /** Enable Newsletter Section */
        $wp_customize->add_setting( 
            'ed_newsletter', 
            array(
                'default'           => false,
                'sanitize_callback' => 'the_conference_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
    		new The_Conference_Toggle_Control( 
    			$wp_customize,
    			'ed_newsletter',
    			array(
    				'section'     => 'newsletter_settings',
    				'label'	      => __( 'Enable Newsletter Section', 'the-conference' ),
                    'description' => __( 'Enable to show Newsletter Section.', 'the-conference' ),
    			)
    		)
    	);

        /** Enable Gradient on Newsletter Section */
        $wp_customize->add_setting( 
            'ed_newsletter_gradient', 
            array(
                'default'           => true,
                'sanitize_callback' => 'the_conference_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new The_Conference_Toggle_Control( 
                $wp_customize,
                'ed_newsletter_gradient',
                array(
                    'section'         => 'newsletter_settings',
                    'label'           => __( 'Enable Gradient Color', 'the-conference' ),
                    'description'     => __( 'Enable to display gradient color.', 'the-conference' ),
                    'active_callback' => 'the_conference_newsletter_setting_ac'
                )
            )
        );
        
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'newsletter_shortcode',
            array(
                'section'         => 'newsletter_settings',
                'label'           => __( 'Newsletter Shortcode', 'the-conference' ),
                'description'     => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'the-conference' ),
                'active_callback' => 'the_conference_newsletter_setting_ac'
            )
        ); 
	} else {
		$wp_customize->add_setting(
			'newsletter_recommend',
			array(
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new The_Conference_Plugin_Recommend_Control(
				$wp_customize,
				'newsletter_recommend',
				array(
					'section'     => 'newsletter_settings',
					'label'       => __( 'Newsletter Shortcode', 'the-conference' ),
					'capability'  => 'install_plugins',
					'plugin_slug' => 'blossomthemes-email-newsletter',//This is the slug of recommended plugin.
                    /* translators: 1: strong tag start, 2: strong tag end */
					'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'the-conference' ), '<strong>', '</strong>' ),
				)
			)
		);
	}
       
}
add_action( 'customize_register', 'the_conference_customize_register_general_newsletter' );