<?php
/**
 * Social Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Social Ajax Class.
 */
class Social {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlpTeamSocialInput', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$count = absint( $_REQUEST['id'] );
		$html  = null;

		$html         .= '<div class="tlp-field-holder socialLink" id="slh-' . $count . '">';
			$html     .= '<div class="tlp-label">';
				$html .= '<select name="social[' . $count . '][id]">';
		foreach ( Options::socialLink() as $id => $name ) {
			$html .= "<option value='$id'>$name</option>";
		}
				$html .= '</select>';
			$html     .= '</div>';
			$html     .= '<div class="tlp-field">';
				$html .= '<input type="text" name="social[' . $count . '][url]" class="tlpfield" value="">';
				$html .= '<span data-id="' . $count . '" class="sRemove dashicons dashicons-trash"></span> <span class="dashicons dashicons-admin-settings"></span>';
			$html     .= '</div>';
		$html         .= '</div>';

		Fns::print_html( $html, true );

		die();
	}
}
