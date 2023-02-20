<?php
/**
 * The Conference Demo Content
 *
 * @package The Conference
 */

if ( ! function_exists( 'the_conference_customizer_demo_content' ) ) :
	/**
     * Add demo content info
     */
	function the_conference_customizer_demo_content( $wp_customize ) {
		
	    $wp_customize->add_section( 'demo_content_section' , array(
			'title'       => __( 'Demo Content Import' , 'the-conference' ),
			'priority'    => 7,
			));
	        
	    $wp_customize->add_setting(
			'demo_content_instruction',
			array(
				'sanitize_callback' => 'wp_kses_post'
			)
		);

	    /* translators: 1: string, 2: url, 3: string */
	    $demo_content_description = sprintf( '%1$s<a class="documentation" href="%2$s" target="_blank">%3$s</a>', esc_html__( 'Conference comes with demo content import feature. You can import the demo content with just one click. For step-by-step video tutorial, ', 'the-conference' ), esc_url( 'https://rarathemes.com/blog/import-demo-content-rara-themes/' ), esc_html__( 'Click here', 'the-conference' ) );


		$wp_customize->add_control(
			new The_Conference_Note_Control( 
				$wp_customize,
				'demo_content_instruction',
				array(
					'section'		=> 'demo_content_section',
					'description'	=> $demo_content_description
				)
			)
		);
	    
		/* translators: 1: string, 2: preview url, 3: string */
		$theme_demo_content_desc = sprintf( '<span class="sticky_info_row"><label class="row-element">%1$s<a href="%2$s" target="_blank">%3$s</a></span><br/><br/>', esc_html__( 'Demo Link : ', 'the-conference' ), esc_url( __( 'https://rarathemes.com/previews/?theme=the-conference', 'the-conference' ) ), esc_html__( 'Click here.', 'the-conference' ) );

		if( ! class_exists( 'RDDI_init' ) ) {
			$theme_demo_content_desc .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Plugin required', 'the-conference' ) . ': </label><a href="' . esc_url( 'https://wordpress.org/plugins/rara-one-click-demo-import/' ) . '" target="_blank">' . __( 'Rara One Click Demo Import', 'the-conference' ) . '</a></span><br/>';
		}

		$theme_demo_content_desc .= '<span class="sticky_info_row download-link"><label class="row-element">' . __( 'Download Demo Content', 'the-conference' ) . ': </label><a href="' . esc_url( 'https://docs.rarathemes.com/docs/the-conference/theme-installation-activation/how-to-import-demo-content/' ) . '" target="_blank">' . __( 'Click here', 'the-conference' ) . '</a></span><br/>';

		$wp_customize->add_setting( 'theme_demo_content_info',array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			));

		// Demo content 
		$wp_customize->add_control( new The_Conference_Note_Control( $wp_customize ,'theme_demo_content_info',array(
			'section'     => 'demo_content_section',
			'description' => $theme_demo_content_desc
			)));

	}
endif;
add_action( 'customize_register', 'the_conference_customizer_demo_content' );