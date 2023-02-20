<?php
/**
 * Profile Image Ajax Class.
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
 * Profile Image Ajax Class.
 */
class ProfileImage {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlp_team_profile_img_remove', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error   = true;
		$msg     = null;
		$id      = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
		$post_id = isset( $_REQUEST['post_ID'] ) ? absint( $_REQUEST['post_ID'] ) : 0;
		if ( $id && $post_id && Fns::verifyNonce() ) {
			if ( delete_post_meta( $post_id, 'tlp_team_gallery', $id ) ) {
				$error = false;
				$msg   = __( 'Successfully deleted', 'tlp-team' );
			} else {
				$msg = __( 'Error!!', 'tlp-team' );
			}
		}

		wp_send_json(
			[
				'error' => $error,
				'msg'   => $msg,
			]
		);
	}
}
