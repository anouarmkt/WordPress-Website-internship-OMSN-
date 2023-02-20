<?php
/**
 * WPZOOM Portfolio Template
 *
 * @since   1.0.5
 * @package WPZOOM_Portfolio
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main WPZOOM_Portfolio_Template Class.
 *
 * @since 1.0.5
 */
class WPZOOM_Portfolio_Template {

	/**
	 * This class instance.
	 *
	 * @var WPZOOM_Portfolio_Template
	 * @since 1.0.5
	 */
	private static $instance;

	/**
	 * Provides singleton instance.
	 *
	 * @since 1.0.5
	 * @return self instance
	 */
	public static function instance() {			

		if ( null === self::$instance ) {
			self::$instance = new WPZOOM_Portfolio_Template();
		}

		return self::$instance;
	}

	/**
	 * The Constructor.
	 */
	public function __construct() {

		add_filter( 'taxonomy_template', array( $this, 'include_taxonomy_template' ), 99 );

	}

	public function include_taxonomy_template( $template ) {

		$use_template = get_option( 'wpzoom-portfolio-settings' );

		if( '1' !== $use_template['wpzoom_portfolio_settings_use_template'] ) {
			return $template;
		}

		global $wp;
		
		$template_filename = 'taxonomy-portfolio.php';

		// Get the current term object. We will use get_queried_object
		$current_term = get_queried_object();

		// If the current term does not belong to advert post type, bail
		if ( $current_term->taxonomy !== 'portfolio' ) {
			return $template;
		}

		// Check if the template exists, if not bail
		$locate_template = locate_template( $template_filename );

		if ( !$locate_template )  {
			$locate_template = WPZOOM_PORTFOLIO_PATH . 'templates/' . $template_filename;
		}

		if ( !file_exists( $locate_template ) )  {
			return $template;
		}

		// We have reached this point, set our custom template
		$template = $locate_template;
		
		return $template;
	
	}

}

new WPZOOM_Portfolio_Template;