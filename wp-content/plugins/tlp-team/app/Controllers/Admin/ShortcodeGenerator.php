<?php
/**
 * Shortcode Generator Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Shortcode Generator Class.
 */
class ShortcodeGenerator {
	use \RT\Team\Traits\SingletonTrait;

	public $shortcode_tag = 'tlp_team_scg';

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_head', [ $this, 'admin_head' ] );
	}

	/**
	 * admin_head
	 * calls your functions into the correct filters
	 *
	 * @return void
	 */
	public function admin_head() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', [ $this, 'mce_external_plugins' ] );
			add_filter( 'mce_buttons', [ $this, 'mce_buttons' ] );
			echo '<style>';
				echo 'i.mce-i-tlp_team_scg{';
					echo "background: url('" . esc_url( rttlp_team()->assets_url() ) . "images/tlp-sch.png');";
				echo '}';
			echo 'i.tlp-vc-icon{';
					echo "background: url('" . esc_url( rttlp_team()->assets_url() ) . "images/tlp-vc.png');";
				echo '}';
			echo '</style>';
		}
	}

	/**
	 * mce_external_plugins
	 * Adds our tinymce plugin
	 *
	 * @param  array $plugin_array
	 * @return array
	 */
	public function mce_external_plugins( $plugin_array ) {
		$plugin_array[ $this->shortcode_tag ] = esc_url( rttlp_team()->assets_url() ) . 'js/mce-button.js';
		return $plugin_array;
	}

	/**
	 * mce_buttons
	 * Adds our tinymce button
	 *
	 * @param  array $buttons
	 * @return array
	 */
	public function mce_buttons( $buttons ) {
		array_push( $buttons, $this->shortcode_tag );
		return $buttons;
	}
}
