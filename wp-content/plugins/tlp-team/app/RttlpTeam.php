<?php
/**
 * Main initialization class.
 *
 * @package RT_Team
 */

require_once __DIR__ . './../vendor/autoload.php';

use RT\Team\Helpers as Helpers;
use RT\Team\Controllers as Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

if ( ! class_exists( RttlpTeam::class ) ) {
	/**
	 * Main initialization class.
	 */
	final class RttlpTeam {

		use RT\Team\Traits\SingletonTrait;

		/**
		 * Post Type.
		 *
		 * @var string
		 */
		public $post_type;

		/**
		 * Shortcode Post Type.
		 *
		 * @var string
		 */
		public $shortCodePT;

		/**
		 * Taxonomies.
		 *
		 * @var array
		 */
		public $taxonomies;

		/**
		 * Default settings.
		 *
		 * @var array
		 */
		public $default_settings;

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version;

		/**
		 * Migration version.
		 *
		 * @var string
		 */
		public $migration_version;

		/**
		 * Options
		 *
		 * @var array
		 */
		public $options;

		/**
		 * Plugin path.
		 *
		 * @var string
		 */
		public $plugin_path;

		/**
		 * Pro path.
		 *
		 * @var string
		 */
		public $pro_path;

		/**
		 * Class init.
		 *
		 * @return void
		 */
		protected function init() {
			// Defaults.
			$this->defaults();

			// Hooks.
			$this->init_hooks();
		}

		/**
		 * Defaults
		 *
		 * @return void
		 */
		private function defaults() {
			$this->post_type         = 'team';
			$this->shortCodePT       = 'team-sc';
			$this->version           = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : TLP_TEAM_VERSION;
			$this->migration_version = '3.0.3';

			$this->options = [
				'settings'          => 'tlp_team_settings',
				'version'           => TLP_TEAM_VERSION,
				'feature_img_size'  => 'team-thumb',
				'installed_version' => 'tlp_team_installed_version',
			];

			$this->taxonomies = [
				'department'  => $this->post_type . '_department',
				'designation' => $this->post_type . '_designation',
				'skill'       => $this->post_type . '_skill',
			];

			$this->default_settings = [
				'feature_img'         => [
					'width'  => 400,
					'height' => 400,
				],
				'slug'                => 'team',
				'tlp_team_block_type' => 'default',
				'detail_page_fields'  => [
					'name',
					'designation',
					'short_bio',
					'content',
					'experience_year',
					'email',
					'web_url',
					'telephone',
					'mobile',
					'location',
					'skill',
					'social',
				],
				'custom_css'          => null,
			];
		}

		/**
		 * Init Hooks.
		 *
		 * @return void
		 */
		private function init_hooks() {
			\add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], -1 );
			\add_action( 'init', [ $this, 'initialize' ], 0 );
		}

		/**
		 * Init Hooks.
		 *
		 * @return void
		 */
		public function initialize() {
			\do_action( 'rttm_loaded' );
			Helpers\Fns::instances( $this->controllers() );
		}

		/**
		 * Controllers.
		 *
		 * @return array
		 */
		public function controllers() {
			$controllers = [
				Controllers\AjaxController::class,
				Controllers\ScriptsController::class,
				Controllers\WidgetsController::class,
				Controllers\FrontendController::class,
				Controllers\PostTypesController::class,
				Controllers\GutenbergController::class,
			];

			if ( is_admin() ) {
				$controllers[] = Controllers\AdminController::class;
			}

			return $controllers;
		}

		/**
		 * Actions on Plugins Loaded.
		 *
		 * @return void
		 */
		public function on_plugins_loaded() {
			\do_action( 'rttm_loading' );
		}

		/**
		 * Plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME ) );
		}

		/**
		 * PRO plugin path
		 *
		 * @return string
		 */
		public function pro_plugin_path() {
			return untrailingslashit( plugin_dir_path( TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME ) ) . '-pro';
		}

		/**
		 * Template path
		 *
		 * @return string
		 */
		public function templates_path() {
			return apply_filters( 'rttlp_team_template_path', $this->plugin_path() . '/templates/' );
		}

		/**
		 * PRO Template path
		 *
		 * @return string
		 */
		public function pro_templates_path() {
			return apply_filters( 'rttlp_team_pro_template_path', $this->pro_plugin_path() . '/templates/' );
		}

		/**
		 * Checks if Pro version installed
		 *
		 * @return boolean
		 */
		public function has_pro() {
			return function_exists( 'rttmp' );
		}

		/**
		 * PRO Version URL.
		 *
		 * @return string
		 */
		public function pro_version_link() {
			return esc_url( 'https://www.radiustheme.com/downloads/tlp-team-pro-for-wordpress/' );
		}

		/**
		 * Documentation URL.
		 *
		 * @return string
		 */
		public function documentation_link() {
			return esc_url( 'https://www.radiustheme.com/docs/team/' );
		}

		/**
		 * Ticket URL.
		 *
		 * @return string
		 */
		public function ticket_link() {
			return 'https://www.radiustheme.com/ticket-support/';
		}

		/**
		 * Facebook URL.
		 *
		 * @return string
		 */
		public function fb_link() {
			return 'https://www.facebook.com/groups/234799147426640/';
		}

		/**
		 * Radius URL.
		 *
		 * @return string
		 */
		public function radius_link() {
			return 'https://www.radiustheme.com/';
		}

		/**
		 * Review URL.
		 *
		 * @return string
		 */
		public function review_link() {
			return 'https://wordpress.org/support/plugin/tlp-team/reviews/?filter=5#new-post';
		}

		/**
		 * Assets URL.
		 *
		 * @return string
		 */
		public function assets_url() {
			return esc_url( TLP_TEAM_PLUGIN_URL . '/assets/' );
		}
	}

	/**
	 * Returns RttlpTeam.
	 *
	 * @return RttlpTeam
	 */
	function rttlp_team() {
		return RttlpTeam::get_instance();
	}

	/**
	 * App Init.
	 */
	rttlp_team();
}
