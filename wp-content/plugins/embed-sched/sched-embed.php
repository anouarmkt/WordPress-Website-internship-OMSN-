<?php
/*
Plugin Name:  Sched Event Management Software
Description:  Embed event content from sched.com into your WordPress site
Plugin URI:   https://github.com/schedorg/sched-embed
Version:      1.1.9
Author:       <a href="https://sched.com/">Sched.com</a>
Text Domain:  embed-sched
Domain Path:  /languages/
License:      GPL v2 or later

Copyright © 2013 Code for the People Ltd
Copyright © 2019 Sched LLC

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

defined( 'ABSPATH' ) or die();

if ( !class_exists( 'Sched_Embed_Plugin' ) ) {
class Sched_Embed_Plugin {

	/**
	 * Class constructor. Set up some actions, filters and shortcodes.
	 *
	 * @author John Blackbourn
	 * @return null
	 */
	private function __construct() {

		add_action( 'init',         array( $this, 'load_textdomain' ) );
		add_shortcode( 'sched',     array( $this, 'do_shortcode' ) );
		add_shortcode( 'sched.org', array( $this, 'do_shortcode' ) );
		add_shortcode( 'sched.com', array( $this, 'do_shortcode' ) );

	}

	/**
	 * Register our text domain.
	 *
	 * @author John Blackbourn
	 * @return null
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'embed-sched', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Shortcode callback function. Outputs the embed code.
	 *
	 * @author John Blackbourn
	 * @param  array  $atts    Array of attributes in the shortcode.
	 * @param  string $content Optional text content contained within the shortcode tags.
	 * @return string          The shortcode output.
	 */
	public function do_shortcode( $atts = null, $content = '' ) {

		$shortcode = new Sched_Embed_Shortcode( get_the_ID(), $atts, $content );
		$output    = $shortcode->get_output();

		if ( is_wp_error( $output ) ) {
			if ( current_user_can( 'edit_post', get_the_ID() ) )
				return sprintf( '<strong>%s</strong>', $output->get_error_message() );
			else
				return '';
		}

		return $output;

	}

	/**
	 * Singleton instantiator/getter.
	 *
	 * @author John Blackbourn
	 * @return Sched_Embed_Plugin The instance of our plugin object.
	 */
	public static function init() {
		static $instance = null;
		if ( null === $instance )
			$instance = new Sched_Embed_Plugin;
		return $instance;
	}

}
}

if ( !class_exists( 'Sched_Embed_Shortcode' ) ) {
class Sched_Embed_Shortcode {

	/**
	 * Class constructor. Processes the shortcode attributes.
	 *
	 * @author John Blackbourn
	 * @param  int    $post_id Current post ID.
	 * @param  array  $atts    Array of attributes in the shortcode.
	 * @param  string $content Optional text content contained within the shortcode tags.
	 * @return null
	 */
	function __construct( $post_id, $atts, $content = '' ) {

		$this->atts = shortcode_atts( array(
			'url'        => null,
			'width'      => null,
			'sidebar'    => true,
			'background' => null,
		), $atts );
		$this->post_id = $post_id;
		$this->content = $content;

		if ( $this->get_att( 'width' ) )
			$this->atts[ 'width' ] = absint( $this->get_att( 'width' ) );
	}

	/**
	 * Attribute getter.
	 *
	 * @author John Blackbourn
	 * @param  string $name Attribute name.
	 * @return string       Attribute value.
	 */
	function get_att( $name ) {
		if ( isset( $this->atts[$name] ) )
			return $this->atts[$name];
		return null;
	}

	/**
	 * Get the current post object.
	 *
	 * @author John Blackbourn
	 * @return StdClass|WP_Post Current post object.
	 */
	function get_post() {
		return get_post( $this->post_id );
	}

	/**
	 * Fetches the contents of the <title> tag from the embed URL. Cached for 24 hours.
	 *
	 * @author John Blackbourn
	 * @return string Embed URL page title. Falls back to the embed URL on failure.
	 */
	function fetch_title() {

		# http://core.trac.wordpress.org/ticket/15058
		$cache_key = $this->url;

		if ( $cache = get_site_transient( $cache_key ) )
			return $cache;

		$request = wp_remote_get( $this->url );
		$body    = wp_remote_retrieve_body( $request );

		if ( empty( $body ) )
			return $this->url;

		preg_match( '|<title>([^<]+)</title>|i', $body, $m );

		if ( !isset( $m[1] ) or empty( $m[1] ) )
			return $this->url;

		$title = trim( $m[1] );

		set_site_transient( $cache_key, $title, 60*60*24 );

		return $title;

	}

	/**
	 * Returns the output for the shortcode content.
	 *
	 * @author John Blackbourn
	 * @return WP_Error|string Shortcode output, or a WP_Error object on failure. Also enqueues the
	 *                         necessary JavaScript for the embed.
	 */
	function get_output() {
		
		if ( !$this->get_att( 'url' ) or ( false === strpos( $this->get_att( 'url' ), '.sched.com' ) ) ) {
			return new WP_Error( 'invalid_url', __( 'Embed Sched: Your shortcode should contain a sched.com URL.', 'embed-sched' ) );
		}
		
		if ( ! is_null( $this->get_att( 'width' ) ) and ( 990 < $this->get_att( 'width' ) || 500 > $this->get_att( 'width' ) ) ) {
			return new WP_Error( 'invalid_width', __( 'Embed Sched: If you specify a width, it should be between 500 and 990.', 'embed-sched' ) );
		}

		switch ( $this->get_att( 'view' ) ) {

			case 'schedule':
				$suffix = '/';
				break;

			case 'expanded':
				$suffix = '/list/descriptions';
				break;

			case 'grid':
				$suffix = '/grid';
				break;

			case 'venues':
				$suffix = '/venues';
				break;

			case 'attendees':
				$suffix = '/directory';
				break;

			case 'speakers':
				$suffix = '/directory/speakers';
				break;

			case 'sponsors':
				$suffix = '/directory/sponsors';
				break;

			case 'exhibitors':
				$suffix = '/directory/exhibitors';
				break;

			default:
				$suffix = false;
				break;

		}

		// Clean up the URL, just in case there's 
		// stuff in there we don't need.
		$url = esc_url_raw( $this->atts['url'] );
		$this->base_url = '//' . parse_url( $url, PHP_URL_HOST );

		if ( $suffix )
			$this->url = $this->base_url . $suffix;
		else
			$this->url = $url;

		if ( empty( $this->content ) )
			$this->content = esc_html( $this->fetch_title() );

		$atts = array();
		$attributes = '';

		if ( ! is_null( $this->get_att( 'width' ) ) )
			$atts['data-sched-width'] = $this->get_att( 'width' );

		if ( in_array( $this->get_att( 'sidebar' ), array( 'no', 'false', '0' ) ) )
			$atts['data-sched-sidebar'] = 'no';

		if ( in_array( $this->get_att( 'background' ), array( 'dark' ) ) )
			$atts['data-sched-bg'] = $this->get_att( 'background' );

		foreach ( $atts as $k => $v )
			$attributes .= sprintf( ' %s="%s"', $k, esc_attr( $v ) );

		wp_enqueue_script(
			'embed-sched',
			sprintf( '%s/js/embed.js', $this->base_url ),
			array(),
			null,
			true
		);

		return sprintf( '<a id="sched-embed" href="%s"%s>%s</a>',
			esc_url( $this->url ),
			$attributes,
			$this->content
		);

	}

}
}

Sched_Embed_Plugin::init();