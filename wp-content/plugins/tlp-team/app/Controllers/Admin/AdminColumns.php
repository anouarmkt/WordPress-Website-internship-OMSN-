<?php
/**
 * CPT Admin Columns Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Columns Class.
 */
class AdminColumns {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'manage_edit-team_columns', [ $this, 'arrange_team_columns' ] );
		add_action( 'manage_team_posts_custom_column', [ $this, 'manage_team_columns' ], 10, 2 );
		add_filter( 'manage_edit-team-sc_columns', [ $this, 'arrange_team_sc_columns' ] );
		add_action( 'manage_team-sc_posts_custom_column', [ $this, 'manage_team_sc_columns' ], 10, 2 );
		add_filter( 'manage_edit-team_sortable_columns', [ $this, 'team_column_sort' ] );
	}

	public function arrange_team_columns( $columns ) {
		$column_thumbnail = [ 'thumbnail' => esc_html__( 'Image', 'tlp-team' ) ];
		$column_email     = [ 'email' => esc_html__( 'Email', 'tlp-team' ) ];
		$column_location  = [ 'location' => esc_html__( 'Location', 'tlp-team' ) ];
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + $column_email + $column_location + array_slice( $columns, 1, null, true );
	}

	public function arrange_team_sc_columns( $columns ) {
		$shortcode = [ 'shortcode' => esc_html__( 'TLP Team Shortcode', 'tlp-team' ) ];
		return array_slice( $columns, 0, 2, true ) + $shortcode + array_slice( $columns, 1, null, true );
	}

	public function manage_team_columns( $column ) {

		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( get_the_ID(), [ 35, 35 ] );
				break;
			case 'designation':
				echo esc_html( get_post_meta( get_the_ID(), 'designation', true ) );
				break;
			case 'email':
				echo esc_html( get_post_meta( get_the_ID(), 'email', true ) );
				break;
			case 'location':
				echo esc_html( get_post_meta( get_the_ID(), 'location', true ) );
				break;
			default:
				break;
		}
	}

	public function manage_team_sc_columns( $column ) {
		switch ( $column ) {
			case 'shortcode':
				echo sprintf(
					'<input type="text" onfocus="this.select();" readonly="readonly" value="[tlpteam id=&quot;%s&quot; title=&quot;%s&quot;]" class="large-text code tlp-code-sc">',
					absint( get_the_ID() ),
					esc_html( get_the_title() )
				);
				break;
			default:
				break;
		}
	}

	function team_column_sort( $columns ) {
		$custom = [
			'designation' => 'designation',
			'email'       => 'email',
			'location'    => 'location',
		];
		return wp_parse_args( $custom, $columns );
	}
}
