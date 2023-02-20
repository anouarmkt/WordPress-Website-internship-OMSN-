<?php
/**
 * The Conference Theme Customizer
 *
 * @package The Conference
 */

/**
 * Requiring customizer panels & sections
*/
$the_conference_panels       = array( 'appearance', 'layout', 'home', 'general' );
$the_conference_sections     = array( 'info', 'demo-content', 'site', 'footer' );
$the_conference_sub_sections = array(
    'layout'     => array( 'blog', 'general' ),
    'home'       => array( 'banner', 'stat-counter', 'blog' ),
    'general'    => array( 'header', 'seo', 'post-page', 'newsletter' ),    
);

foreach( $the_conference_sections as $section ){
    require get_template_directory() . '/inc/customizer/sections/' . $section . '.php';
}

foreach( $the_conference_panels as $p ){
   require get_template_directory() . '/inc/customizer/panels/' . $p . '.php';
}

foreach( $the_conference_sub_sections as $k => $v ){
    foreach( $v as $w ){        
        require get_template_directory() . '/inc/customizer/panels/' . $k . '/' . $w . '.php';
    }
}

/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Active Callbacks
*/
require get_template_directory() . '/inc/customizer/active-callback.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function the_conference_customize_preview_js() {
	wp_enqueue_script( 'the-conference-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), THE_CONFERENCE_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'the_conference_customize_preview_js' );

function the_conference_customize_script(){
    wp_enqueue_style( 'the-conference-customize', get_template_directory_uri() . '/inc/css/customize.css', array(), THE_CONFERENCE_THEME_VERSION );
    wp_enqueue_script( 'the-conference-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), THE_CONFERENCE_THEME_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'the_conference_customize_script' );

/*
 * Notifications in customizer
 */
require get_template_directory() . '/inc/customizer-plugin-recommend/customizer-notice/class-customizer-notice.php';

require get_template_directory() . '/inc/customizer-plugin-recommend/plugin-install/class-plugin-install-helper.php';

require get_template_directory() . '/inc/customizer-plugin-recommend/plugin-install/class-plugin-recommend.php';

$config_customizer = array(
	'recommended_plugins' => array(
		//change the slug for respective plugin recomendation
        'raratheme-companion' => array(
			'recommended' => true,
			'description' => sprintf(
				/* translators: %s: plugin name */
				esc_html__( 'If you want to take full advantage of the features this theme has to offer, please install and activate %s plugin.', 'the-conference' ), '<strong>RaraTheme Companion</strong>'
			),
		),
	),
	'recommended_plugins_title' => esc_html__( 'Recommended Plugin', 'the-conference' ),
	'install_button_label'      => esc_html__( 'Install and Activate', 'the-conference' ),
	'activate_button_label'     => esc_html__( 'Activate', 'the-conference' ),
	'deactivate_button_label'   => esc_html__( 'Deactivate', 'the-conference' ),
);
The_Conference_Customizer_Notice::init( apply_filters( 'the_conference_customizer_notice_array', $config_customizer ) );