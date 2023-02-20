<?php
/**
 * The Conference functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package The Conference
 */

$the_conference_theme_data = wp_get_theme();
if( ! defined( 'THE_CONFERENCE_THEME_VERSION' ) ) define ( 'THE_CONFERENCE_THEME_VERSION', $the_conference_theme_data->get( 'Version' ) );
if( ! defined( 'THE_CONFERENCE_THEME_NAME' ) ) define( 'THE_CONFERENCE_THEME_NAME', $the_conference_theme_data->get( 'Name' ) );

/**
 * Custom Functions.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Standalone Functions.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Template Functions.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom functions for selective refresh.
 */
require get_template_directory() . '/inc/partials.php';

/**
 * Custom Controls
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets/widgets.php';

/**
 * Metabox
 */
require get_template_directory() . '/inc/metabox/metabox.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/getting-started/getting-started.php';

/**
 * Add theme compatibility function for woocommerce if active
*/
if( the_conference_is_woocommerce_activated() ){
    require get_template_directory() . '/inc/woocommerce-functions.php';    
}

if( the_conference_is_rara_theme_companion_activated() ){
	/**
	 * Filters for RaraTheme Companion Plugin
	 */
	require get_template_directory() . '/inc/rrtc-filters.php';
}

if( the_conference_is_btnw_activated() ){
	/**
	 * Filters for BlossomThemes Email Newsletter Plugin
	 */
	require get_template_directory() . '/inc/btnw-filters.php';
}