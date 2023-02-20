<?php
/**
 * Scripts Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Scripts Class.
 */
class ScriptsController {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Styles.
	 *
	 * @var array
	 */
	private $styles = [];

	/**
	 * Scripts.
	 *
	 * @var array
	 */
	private $scripts = [];

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$this->get_assets();

		if ( empty( $this->styles ) ) {
			return;
		}

		if ( empty( $this->scripts ) ) {
			return;
		}

		$version    = rttlp_team()->version;
		$upload_dir = wp_upload_dir();
		$css_file   = $upload_dir['basedir'] . '/tlp-team/team-sc.css';

		foreach ( $this->styles as $style ) {
			wp_register_style( $style['handle'], $style['src'], '', $version );
		}

		foreach ( $this->scripts as $script ) {
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $version, $script['footer'] );
		}

		if ( file_exists( $css_file ) ) {
			$version = filemtime( $css_file );
			wp_register_style( 'rt-team-sc', set_url_scheme( $upload_dir['baseurl'] ) . '/tlp-team/team-sc.css', [ 'rt-team-css' ], $version );
		}

		wp_localize_script(
			'tlp-team-admin-js',
			'rttm',
			[
				'is_pro' => rttlp_team()->has_pro(),
			]
		);

		add_action( 'wp_enqueue_scripts', [ $this, 'tlp_script' ] );
	}

	/**
	 * Frontend scripts scripts.
	 *
	 * @return void
	 */
	public function tlp_script() {
		$settings = get_option( rttlp_team()->options['settings'] );
		$settings = isset( $settings['tlp_team_block_type'] ) ? esc_html( $settings['tlp_team_block_type'] ) : 'default';

		if ( in_array( $settings, [ 'default', 'shortcode' ], true ) || is_singular( 'team' ) ) {
			wp_enqueue_style( 'rt-team-css' );
			wp_enqueue_style( 'rt-team-sc' );
		}

		if ( did_action( 'elementor/loaded' ) && in_array( $settings, [ 'default', 'elementor' ], true ) && ! is_singular( 'team' ) ) {
			wp_enqueue_style( 'tlp-el-team-css' );
		}
	}

	/**
	 * Get all scripts.
	 *
	 * @return void
	 */
	private function get_assets() {
		$this->get_styles()->get_scripts();
	}

	/**
	 * Get styles.
	 *
	 * @return object
	 */
	private function get_styles() {
		$this->styles[] = [
			'handle' => 'tlp-fontawsome',
			'src'    => rttlp_team()->assets_url() . 'vendor/font-awesome/css/all.min.css',
		];

		$this->styles[] = [
			'handle' => 'rt-pagination',
			'src'    => rttlp_team()->assets_url() . 'vendor/pagination/pagination.css',
		];

		$this->styles[] = [
			'handle' => 'tlp-scrollbar',
			'src'    => rttlp_team()->assets_url() . 'vendor/scrollbar/jquery.mCustomScrollbar.min.css',
		];

		$this->styles[] = [
			'handle' => 'tlp-swiper',
			'src'    => rttlp_team()->assets_url() . 'vendor/swiper/swiper.min.css',
		];

		$this->styles[] = [
			'handle' => 'rt-team-css',
			'src'    => rttlp_team()->assets_url() . 'css/tlpteam.css',
		];

		$this->styles[] = [
			'handle' => 'tlp-el-team-css',
			'src'    => rttlp_team()->assets_url() . 'css/tlp-el-team.min.css',
		];

		/**
		 * Admin Styles.
		 */
		if ( is_admin() ) {
			$this->styles[] = [
				'handle' => 'tlp-team-admin-css',
				'src'    => rttlp_team()->assets_url() . 'css/settings.css',
			];

			$this->styles[] = [
				'handle' => 'select2',
				'src'    => rttlp_team()->assets_url() . 'vendor/select2/select2.min.css',
			];
		}

		return $this;
	}

	/**
	 * Get scripts.
	 *
	 * @return object
	 */
	private function get_scripts() {
		$this->scripts[] = [
			'handle' => 'tlp-scrollbar',
			'src'    => rttlp_team()->assets_url() . 'vendor/scrollbar/jquery.mCustomScrollbar.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$default_swiper_path   = rttlp_team()->assets_url() . 'vendor/swiper/swiper.min.js';
		$default_swiper_handle = 'tlp-swiper';

		if ( defined( 'ELEMENTOR_ASSETS_PATH' ) ) {
			$elementor_swiper_path = ELEMENTOR_ASSETS_PATH . 'lib/swiper/swiper.min.js';
			if ( file_exists( $elementor_swiper_path ) ) {
				$default_swiper_path   = ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper.min.js';
			}
		}

		$this->scripts[] = [
			'handle' => 'tlp-swiper',
			'src'    => $default_swiper_path,
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'tlp-image-load-js',
			'src'    => rttlp_team()->assets_url() . 'vendor/isotope/imagesloaded.pkgd.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'rt-pagination',
			'src'    => rttlp_team()->assets_url() . 'vendor/pagination/pagination.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'tlp-isotope-js',
			'src'    => rttlp_team()->assets_url() . 'vendor/isotope/isotope.pkgd.min.js',
			'deps'   => [ 'jquery', 'tlp-image-load-js' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'rt-tooltip',
			'src'    => rttlp_team()->assets_url() . 'js/rt-tooltip.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'tlp-actual-height-js',
			'src'    => rttlp_team()->assets_url() . 'vendor/actual-height/jquery.actual.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'rt-scrollbox',
			'src'    => rttlp_team()->assets_url() . 'vendor/scrollbar/jquery.scrollbar.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'tlp-team-js',
			'src'    => rttlp_team()->assets_url() . 'js/tlpteam.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$this->scripts[] = [
			'handle' => 'tlp-el-team-js',
			'src'    => rttlp_team()->assets_url() . 'js/tlp-el-team.min.js',
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		/**
		 * Admin Scripts.
		 */
		if ( is_admin() ) {
			$this->scripts[] = [
				'handle' => 'ace-code-highlighter-js',
				'src'    => rttlp_team()->assets_url() . 'vendor/ace/ace.js',
				'deps'   => null,
				'footer' => true,
			];
			$this->scripts[] = [
				'handle' => 'ace-mode-js',
				'src'    => rttlp_team()->assets_url() . 'vendor/ace/mode-css.js',
				'deps'   => [ 'ace-code-highlighter-js' ],
				'footer' => true,
			];
			$this->scripts[] = [
				'handle' => 'tlp-admin-taxonomy',
				'src'    => rttlp_team()->assets_url() . 'js/admin-taxonomy.js',
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];
			$this->scripts[] = [
				'handle' => 'select2',
				'src'    => rttlp_team()->assets_url() . 'vendor/select2/select2.min.js',
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];
			$this->scripts[] = [
				'handle' => 'tlp-team-admin-js',
				'src'    => rttlp_team()->assets_url() . 'js/settings.js',
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];
			$this->scripts[] = [
				'handle' => 'tlp-sc-preview',
				'src'    => rttlp_team()->assets_url() . 'js/sc-preview.js',
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];
		}

		return $this;
	}
}
