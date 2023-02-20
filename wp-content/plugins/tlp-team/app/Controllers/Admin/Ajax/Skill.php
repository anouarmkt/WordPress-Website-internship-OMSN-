<?php
/**
 * Skill Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Skill Ajax Class.
 */
class Skill {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlpTeamSkillInput', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$count  = absint( $_REQUEST['id'] );
		$html   = null;
		$html  .= '<div class="tlp-field-holder skillHolder" id="sh-' . $count . '" >';
		$html  .= '<div class="tlp-label">';
		$html  .= '<select class="rt-select2" name="skill[' . $count . '][id]">';
		$skills = get_terms( rttlp_team()->taxonomies['skill'], 'orderby=name&hide_empty=0' );
		if ( ! empty( $skills ) ) {
			foreach ( $skills as $skill ) {
				$html .= "<option value='{$skill->name}'>{$skill->name}</option>";
			}
		}
		$html .= '</select>';
		$html .= '</div>';
		$html .= '<div class="tlp-field">';
		$html .= '<select name="skill[' . $count . '][percent]" class="tlpfield">';
		for ( $i = 0; $i <= 100; $i ++ ) {
			$html .= "<option value='$i'>$i</option>";
		}
		$html .= '</select> %';
		$html .= '<span data-id="' . $count . '" class="skRemove dashicons dashicons-trash"></span> <span class="dashicons dashicons-admin-settings"></span>';
		$html .= '</div>';
		$html .= '</div>';

		Fns::print_html( $html, true );
		die();
	}
}
