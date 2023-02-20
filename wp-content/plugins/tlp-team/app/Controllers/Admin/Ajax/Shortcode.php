<?php
/**
 * Shortcode List Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Shortcode List Ajax Class.
 */
class Shortcode {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_teamShortcodeList', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$scQ = new \WP_Query(
			[
				'post_type'      => rttlp_team()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			]
		);
		if ( $scQ->have_posts() ) {
			?>
			<div class='mce-container mce-form'>
				<div class='mce-container-body'>
					<label class="mce-widget mce-label" style="padding: 20px;font-weight: bold;"
						for="scid"><?php _e( 'Select Shortcode', 'tlp-team' ); ?></label>
					<select name='id' id='scid' style='width: 150px;margin: 15px;'>
						<option value=''><?php _e( 'Default', 'tlp-team' ); ?></option>
						<?php
						while ( $scQ->have_posts() ) {
							$scQ->the_post();
							?>
							<option value='<?php get_the_ID(); ?>'><?php get_the_title(); ?></option>
							<?php
						}
						wp_reset_postdata();
						?>
					</select>
				</div>
			</div>
			<?php
		} else {
			?>
			<div><?php _e( 'No shortCode found.', 'tlp-team' ); ?></div>
			<?php
		}
		die();
	}
}
