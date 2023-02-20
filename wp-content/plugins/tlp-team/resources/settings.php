<?php
/**
 * Settings view.
 */

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="wrap">
	<h2><?php esc_html_e( 'TLP Team Settings', 'tlp-team' ); ?></h2>
	<div id="tlp-team-setting-wrapper">
		<div class="tlp-team-setting-title"><h3><?php esc_html_e( 'General settings', 'tlp-team' ); ?></h3></div>
		<div class="tlp-team-setting-container">
			<form id="tlp-team-settings">
				<?php
				wp_nonce_field( Fns::nonceText(), Fns::nonceID() );
				$html  = null;
				$html .= '<div id="settings-tabs" class="tlp-tabs rt-tab-container">';
				$html .= '<ul class="tab-nav rt-tab-nav">
								<li class="active"><a href="#general-settings"><i class="dashicons dashicons-admin-settings"></i>' . esc_html__( 'General Settings', 'tlp-team' ) . '</a></li>
								<li><a href="#detail-field-selection"><i class="dashicons dashicons-editor-table"></i>' . esc_html__( 'Detail page field selection', 'tlp-team' ) . '</a></li>
								' . apply_filters( 'rttm_license_tab', '' ) . '
							</ul>';

				$html .= '<div id="general-settings" class="rt-tab-content" style="display: block;">';
				$html .= Fns::rtFieldGenerator( Options::tlpTeamGeneralSettingFields() );
				$html .= '</div>';

				$html .= '<div id="detail-field-selection" class="rt-tab-content">';
				$html .= Fns::rtFieldGenerator( Options::tlpTeamDetailFieldSelection() );
				$html .= '</div>';
				$html .= apply_filters( 'rttm_license_tab_content', '' );
				$html .= '</div>';

				Fns::print_html( $html, true );
				?>
				<p class="rt-submit"><input type="submit" name="submit" id="tlpSaveButton" class="button button-primary rt-admin-btn" value="Save Changes"></p>

				<?php wp_nonce_field( Fns::nonceText(), Fns::nonceID() ); ?>
			</form>
			<div id="rt-response"></div>
		</div>
		<div class="tlp-team-setting-doc-wrap">
			<?php Fns::rt_plugin_team_sc_pro_information(); ?>
		</div>
	</div>

</div>
