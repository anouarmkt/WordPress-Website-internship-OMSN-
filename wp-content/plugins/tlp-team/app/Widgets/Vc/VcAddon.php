<?php
/**
 * VC Addon Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Vc;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * VC Addon Widget.
 */
class VcAddon {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', [ $this, 'add_vc_addOn' ] );
	}

	function add_vc_addOn() {
		if ( function_exists( 'vc_map' ) ) :
			$this->TplvcTeam();
		endif;
	}

	function scListA() {
		$sc            = [];
		$scQ           = get_posts(
			[
				'post_type'      => 'team-sc',
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			]
		);
		$sc['Default'] = '';
		if ( count( $scQ ) ) {
			foreach ( $scQ as $post ) {
				$sc[ $post->post_title ] = $post->ID;
			}
		}
		return $sc;
	}

	function TplvcTeam() {
		vc_map(
			[
				'name'              => 'TLP Team',
				'base'              => 'tlpteam',
				'class'             => '',
				'icon'              => 'tlp-vc-icon',
				'controls'          => 'full',
				'category'          => 'Content',
				'admin_enqueue_js'  => '',
				'admin_enqueue_css' => '',
				'params'            => [
					[
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Shortcode', 'tlp-team' ),
						'param_name'  => 'id',
						'value'       => $this->scListA(),
						'admin_label' => true,
						'description' => esc_html__( 'Shortcode list', 'tlp-team' ),
					],
				],
			]
		);
	}
}
