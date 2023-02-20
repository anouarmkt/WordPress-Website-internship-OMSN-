<?php
/**
 * Black Friday Notice Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Notices;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Black Friday Notice Class.
 */
class BlackFriday {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$current      = time();
		$black_friday = mktime( 0, 0, 0, 11, 22, 2022 ) <= $current && $current <= mktime( 0, 0, 0, 12, 6, 2022 );

		if ( ! $black_friday ) {
			return;
		}

		add_action( 'admin_init', [ $this, 'bf_notice' ] );
	}

	/**
	 * Black Friday Notice.
	 *
	 * @return void|string
	 */
	public function bf_notice() {
		if ( get_option( 'rtteam_bf_2021' ) != '1' ) {
			if ( ! isset( $GLOBALS['rt_team_bf_2021_notice'] ) ) {
				$GLOBALS['rt_team_bf_2021_notice'] = 'rtteam_bf_2021';
				self::notice();
			}
		}
	}

	/**
	 * Render Notice
	 *
	 * @return void
	 */
	private static function notice() {

		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_script( 'jquery' );
			}
		);

		add_action(
			'admin_notices',
			function () {
				$plugin_name   = 'Team Pro';
				$download_link = rttlp_team()->pro_version_link();
				?>
				<div class="notice notice-info is-dismissible" data-rtteamdismissable="rtteam_bf_2021"
					style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
					<img alt="<?php echo esc_attr( $plugin_name ); ?>"
						src="<?php echo esc_url( rttlp_team()->assets_url() . 'images/tlpteam-notice.png' ); ?>" width="74px"
						height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
					<h3 style="margin:0;"><?php echo sprintf( '%s Black Friday Deal!!', esc_html( $plugin_name ) ); ?></h3>

					<p style="margin:0 0 2px;">
					<?php echo esc_html__( "Don't miss out on our biggest sale of the year! Get yours.", 'tlp-team' ); ?>
						<b><?php echo esc_html( $plugin_name ); ?> plan</b> with <b>UP TO 50% OFF</b>! Limited time offer expires on December 5.
					</p>

					<p style="margin:0;">
						<a class="button button-primary" href="<?php echo esc_url( $download_link ); ?>" target="_blank">Buy Now</a>
						<a class="button button-dismiss" href="#">Dismiss</a>
					</p>
				</div>
					<?php
			}
		);

		add_action(
			'admin_footer',
			function () {
				?>
				<script type="text/javascript">
					(function ($) {
						$(function () {
							setTimeout(function () {
								$('div[data-rtteamdismissable] .notice-dismiss, div[data-rtteamdismissable] .button-dismiss')
									.on('click', function (e) {
										e.preventDefault();
										$.post(ajaxurl, {
											'action': 'rtteam_dismiss_admin_notice',
											'nonce': <?php echo json_encode( wp_create_nonce( 'rtteam-dismissible-notice' ) ); ?>
										});
										$(e.target).closest('.is-dismissible').remove();
									});
							}, 1000);
						});
					})(jQuery);
				</script>
				<?php
			}
		);

		add_action(
			'wp_ajax_rtteam_dismiss_admin_notice',
			function () {
				check_ajax_referer( 'rtteam-dismissible-notice', 'nonce' );

				update_option( 'rtteam_bf_2021', '1' );
				wp_die();
			}
		);
	}
}
