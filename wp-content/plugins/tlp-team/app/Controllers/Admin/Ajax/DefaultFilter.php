<?php
/**
 * Default Filter Ajax Class.
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
 * Default Filter Ajax Class.
 */
class DefaultFilter {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_ttpDefaultFilterItem', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error = true;
		$data  = $msg = null;

		if ( Fns::verifyNonce() ) {
			$filter = isset( $_REQUEST['filter'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter'] ) ) : null;
			if ( ! empty( $filter ) ) {
				$error = false;
				$msg   = esc_html__( 'Success', 'tlp-team' );
				$data .= "<option value=''>" . __( 'Show All', 'tlp-team' ) . '</option>';
				$items = Fns::rt_get_all_terms_by_taxonomy( $filter );
				if ( ! empty( $items ) ) {
					foreach ( $items as $id => $item ) {
						$data .= "<option value='{$id}'>{$item}</option>";
					}
				}
			}
		} else {
			$msg = esc_html__( 'Your session is expired !!', 'tlp-team' );
		}

		$response = [
			'error' => $error,
			'msg'   => $msg,
			'data'  => $data,
		];

		wp_send_json( $response );
		die();
	}
}
