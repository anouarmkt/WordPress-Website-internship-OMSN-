<?php
/**
 * Frontend Template Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Frontend Template Class.
 */
class Template {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'template_include', [ $this, 'template_loader' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_templatesctipt' ] );
	}

	public static function template_loader( $template ) {

		if ( is_single() && get_post_type() == rttlp_team()->post_type ) {
			$file      = 'single-team.php';
			$templates = [
				$file,
				'tlp-team/' . $file,
			];

			if ( ! $template = locate_template( $templates ) ) {
				$template = rttlp_team()->templates_path() . $file;
			}
		}
		return $template;
	}

	public function load_templatesctipt() {

		if ( get_post_type() == rttlp_team()->post_type || is_post_type_archive( rttlp_team()->post_type ) ) {
			wp_enqueue_style(
				[
					'tlp-fontawsome',
					'tlp-swiper',
					'rt-tpg-css',
				]
			);
			wp_enqueue_script(
				[
					'jquery',
					'rt-tooltip',
					'tlp-image-load-js',
					'tlp-swiper',
					'tlp-team-js',
				]
			);
			$nonce   = wp_create_nonce( Fns::nonceText() );
			$ajaxurl = '';
			if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
				$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else {
				$ajaxurl .= admin_url( 'admin-ajax.php' );
			}
			wp_localize_script(
				'tlp-team-js',
				'ttp',
				[
					'ajaxurl' => $ajaxurl,
					'nonceID' => Fns::nonceID(),
					'nonce'   => $nonce,
					'lan'     => Options::lan(),
				]
			);
		}
	}
}
