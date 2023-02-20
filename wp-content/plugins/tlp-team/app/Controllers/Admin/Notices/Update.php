<?php
/**
 * Update Notice Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Notices;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Update Notice Class.
 */
class Update {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_init', [ $this, 'notice' ] );
	}

	/**
	 * Update Notice.
	 *
	 * @return void|string
	 */
	public function notice() {

		$installed_version = get_option( rttlp_team()->options['installed_version'] );
		$migration_version = rttlp_team()->migration_version;

		if ( ! $installed_version ) {
			$installed_version = false;
		}

		if ( $installed_version && version_compare( $installed_version, $migration_version, '<=' ) ) {
			if ( get_option( 'rtteam_rtshortcodedismissable_3_0_0' ) != '1' ) {
				add_action(
					'admin_notices',
					function() {
						$class = 'notice notice-warning is-dismissible';
						$text  = esc_html__( 'Team', 'tlp-team' );
						$link  = esc_url(
							add_query_arg(
								[
									'tab'       => 'plugin-information',
									'plugin'    => 'tlp-team',
									'TB_iframe' => 'true',
									'width'     => '640',
									'height'    => '500',
								],
								admin_url( 'plugin-install.php' )
							)
						);

						$shortcode_url = admin_url( 'edit.php?post_type=team-sc' );

						printf(
							'<div class="%1$s" data-rtshortcodedismissable="rtteam_shortcode_notice" ><p><strong>Thanks for using this <a class="thickbox open-plugin-details-modal" href="%2$s">%3$s</a> plugin! we have major updated in this plugin. Those are using manual shortcode from old version, We strongly recommended to use <a href="%4$s">Shortcode Generator</a>. </strong> </p></div>',
							esc_attr( $class ),
							esc_url( $link ),
							esc_html( $text ),
							esc_url( $shortcode_url )
						);
					}
				);

				add_action(
					'admin_enqueue_scripts',
					function () {
						wp_enqueue_script( 'jquery' );
					}
				);

				add_action(
					'admin_footer',
					function () { ?>
						<script type="text/javascript">
							(function ($) {
									setTimeout(function () {
										$('div[data-rtshortcodedismissable] .notice-dismiss, div[data-rtshortcodedismissable] .button-dismiss')
											.on('click', function (e) {
												e.preventDefault();

												$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>',
													{
														'action': 'rtteam_shortcodedismiss_admin_notice',
														'nonce': <?php echo json_encode( wp_create_nonce( 'rtteam-rtshortcodedismissable' ) ); ?>
													},
													function(data, status){
														console.log( status )
													}
												);
												$(e.target).closest('.is-dismissible').remove();
											});
									}, 1000);
							})(jQuery);
						</script>
						<?php
					}
				);

				add_action(
					'wp_ajax_rtteam_shortcodedismiss_admin_notice',
					function () {
						check_ajax_referer( 'rtteam-rtshortcodedismissable', 'nonce' );

						update_option( 'rtteam_rtshortcodedismissable_3_0_0', '1' );
						wp_send_json_success(
							[
								'success' => true,
							]
						);
						wp_die();
					}
				);

			}
		}

		if ( get_option( 'rtteam_activation_redirect', false ) ) {
			delete_option( 'rtteam_activation_redirect' );
			wp_redirect( admin_url( 'edit.php?post_type=team&page=tlp_team_get_help' ) );
		}
	}

}
