<?php
/**
 * Backward Compatibility with PRO plugin.
 *
 * @package RT/Team
 */

namespace RT\Team\Helpers;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Backward Compatibility with PRO plugin.
 */
class Deprecated {
	/**
	 * Post Type.
	 *
	 * @var string
	 */
	public $post_type;

	/**
	 * Taxonomies.
	 *
	 * @var array
	 */
	public $taxonomies;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Class Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->post_type  = rttlp_team()->post_type;
		$this->taxonomies = rttlp_team()->taxonomies;
		$this->options    = rttlp_team()->options;
	}

	public function render_view( $view_name, $args = [], $return = false ) {
		return Fns::render_view( $view_name, $args, $return );
	}

	public function rt_get_all_taxonomy_by_post_type() {
		return Fns::rt_get_all_taxonomy_by_post_type();
	}

	public function verifyNonce() {
		return Fns::verifyNonce();
	}

	public function rtFieldGenerator( $fields ) {
		return Fns::rtFieldGenerator( $fields );
	}

	public function rtTeamLicenceField() {
		return Options::rtTeamLicenceField();
	}

	public function getTTPShortCodeList() {
		return Fns::getTTPShortcodeList();
	}

	public function get_formatted_social_link( $sLink, $fields ) {
		return Fns::get_formatted_social_link( $sLink, $fields );
	}

	public function get_formatted_contact_info( $items = [], $fields ) {
		return Fns::get_formatted_contact_info( $items, $fields );
	}

	public function get_formatted_skill( $tlp_skill, $fields ) {
		return Fns::get_formatted_skill( $tlp_skill, $fields );
	}
}
