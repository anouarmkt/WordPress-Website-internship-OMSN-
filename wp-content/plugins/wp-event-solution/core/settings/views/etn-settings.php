<?php

use Etn\Utils\Helper;

defined('ABSPATH') || exit;

$settings =  \Etn\Core\Settings\Settings::instance()->get_settings_option();

//pick a sample date to show on dropdown options
$sample_date  = strtotime( date('d') . " " . date('M') . " " .  date('Y') );
$date_formats = Helper::get_date_formats();
 
$sell_tickets                           =  (isset($settings['sell_tickets']) ? 'checked' : '');
$checked_exclude_from_search            =  isset( $settings['etn_include_from_search'] ) ? $settings['etn_include_from_search'] : 'on';
$checked_purchase_login_required        =  (isset($settings['etn_purchase_login_required']) ? 'checked' : '');
$attendee_registration                  =  (isset($settings['attendee_registration']) ? 'checked' : '');
$reg_require_phone                      =  (isset($settings['reg_require_phone']) ? 'checked' : '');
$reg_require_email                      =  (isset($settings['reg_require_email']) ? 'checked' : '');
$disable_ticket_email                   =  (isset($settings['disable_ticket_email']) ? 'checked' : '');
$checked_hide_date_from_details         =  (isset($settings['etn_hide_date_from_details']) ? 'checked' : '');
$checked_hide_time_from_details         =  (isset($settings['etn_hide_time_from_details']) ? 'checked' : '');
// $checked_expired_event                  =  (isset($settings['checked_expired_event']) ? 'checked' : '');
$checked_hide_location_from_details     =  (isset($settings['etn_hide_location_from_details']) ? 'checked' : '');
$checked_hide_seats_from_details        =  (isset($settings['etn_hide_seats_from_details']) ? 'checked' : '');
$checked_hide_attendee_count_from_details= (isset($settings['etn_hide_attendee_count_from_details']) ? 'checked' : '');
$checked_hide_organizers_from_details   =  (isset($settings['etn_hide_organizers_from_details']) ? 'checked' : '');
$checked_hide_schedule_from_details     =  (isset($settings['etn_hide_schedule_from_details']) ? 'checked' : '');
$checked_hide_address_from_details      =  (isset($settings['etn_hide_address_from_details']) ? 'checked' : '');
$selected_date_format                   =  isset( $settings['date_format'] ) ? $settings['date_format'] : "";
$selected_time_format                   =  !empty( $settings['time_format'] ) ? $settings['time_format'] : "";
$attendee_remove                        =  (isset($settings['attendee_remove']) ? $settings['attendee_remove'] : '');
$event_slug                             =  (isset($settings['event_slug']) ? $settings['event_slug'] : '');
$speaker_slug                           =  (isset($settings['speaker_slug']) ? $settings['speaker_slug'] : '');
$etn_price_label                        =  (isset($settings['etn_price_label']) ? $settings['etn_price_label'] : '');
$etn_zoom_api                           =  (isset($settings['etn_zoom_api']) ? 'checked' : '');
$zoom_api_key                           =  (isset($settings['zoom_api_key']) ? $settings['zoom_api_key'] : '');
$zoom_secret_key                        =  (isset($settings['zoom_secret_key']) ? $settings['zoom_secret_key'] : '');
$zoom_class                             =  ( $etn_zoom_api == 'checked' ) ?  'zoom_section' : 'zoom_section_hide';
$settings_arr                           =  apply_filters( 'eventin/settings/pro_settings', [] );
$is_registration_disabled_if_expired    =  isset( $settings['disable_registration_if_expired'] ) ? 'checked' : '';
$remainder_email_sending_day            =  isset( $settings['remainder_email_sending_day'] ) ? $settings['remainder_email_sending_day'] : '';
$selected_speaker_template              =  isset( $settings['speaker_template'] ) ? $settings['speaker_template'] : "";
$selected_event_template                =  isset( $settings['event_template'] ) ? $settings['event_template'] : "";
$selected_expiry_point                  =  isset( $settings['expiry_point'] ) ? $settings['expiry_point'] : "";
$events_per_page                        =  isset( $settings['events_per_page'] ) ? $settings['events_per_page'] : 10;
$selected_add_to_cart_redirect          =  isset( $settings['add_to_cart_redirect'] ) ? $settings['add_to_cart_redirect'] : "event";
$etn_sells_engine_woocommerce           = (isset($settings['etn_sells_engine_woocommerce']) ? 'checked' : '');
$hide_past_recurring_event_from_details = (isset($settings['hide_past_recurring_event_from_details']) ? 'checked' : '');

$event_template_array                   =  apply_filters('etn_event_templates', [
    'event-one' => esc_html__( 'Template One', 'eventin' ),
]);
$speaker_template_array                   = apply_filters('etn_speaker_templates', [
    'speaker-one' => esc_html__( 'Template One Free', 'eventin' ),
    'speaker-two-lite' => esc_html__( 'Template Two Free ', 'eventin' ),
]);

$redirect_after_cart_array  = [
    'event'     => esc_html__( 'Event', 'eventin' ),
    'cart'      => esc_html__( 'Cart', 'eventin' ),
    'checkout'  => esc_html__( 'Checkout', 'eventin' ),
];

$general_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 23.2 22" style="enable-background:new 0 0 23.2 22;" xml:space="preserve">
<style type="text/css">.st0{fill:#77797E;}</style><g><circle class="st0" cx="11.6" cy="11" r="4.2"/>
<path class="st0" d="M20.2,7.1c-0.5,0-0.9-0.1-1.1-0.4C19,6.4,19,6,19.3,5.5c0.4-0.7,0.5-1.5,0.3-2.3c-0.2-0.8-0.7-1.4-1.4-1.8
   l-1.8-1C15.7,0,15-0.1,14.3,0.1c-0.7,0.2-1.3,0.6-1.7,1.3l-0.1,0.2c-0.3,0.5-0.6,0.7-0.9,0.7c0,0,0,0,0,0c-0.3,0-0.6-0.3-0.9-0.7
   l-0.1-0.2c-0.4-0.6-1-1.1-1.7-1.3C8.2-0.1,7.4,0,6.8,0.4L5,1.4c-0.7,0.4-1.2,1-1.4,1.8C3.4,4,3.5,4.8,3.9,5.5
   C4.2,6,4.2,6.4,4.1,6.7C4,6.9,3.6,7.1,3,7.1c-1.7,0-3,1.4-3,3v1.9c0,1.7,1.4,3,3,3c0.5,0,0.9,0.1,1.1,0.4c0.1,0.2,0.1,0.7-0.2,1.1
   c-0.4,0.7-0.5,1.5-0.3,2.3c0.2,0.8,0.7,1.4,1.4,1.8l1.8,1c0.6,0.4,1.4,0.5,2.1,0.3c0.7-0.2,1.3-0.6,1.7-1.3l0.1-0.2
   c0.3-0.5,0.6-0.7,0.9-0.7c0,0,0,0,0,0c0.3,0,0.6,0.3,0.9,0.7l0.1,0.2c0.5,0.9,1.5,1.4,2.4,1.4c0.5,0,0.9-0.1,1.4-0.4l1.8-1
   c1.4-0.8,1.9-2.7,1.1-4.1C19,16,19,15.6,19.1,15.3c0.1-0.2,0.5-0.4,1.1-0.4c1.7,0,3-1.4,3-3v-1.9C23.2,8.4,21.8,7.1,20.2,7.1z
	M21.2,11.9c0,0.6-0.5,1-1,1c-1.3,0-2.3,0.5-2.8,1.4c-0.5,0.9-0.4,2,0.2,3.1c0.3,0.5,0.1,1.1-0.4,1.4l-1.8,1.1
   c-0.4,0.2-0.8,0.1-1-0.3l-0.1-0.2c-0.6-1.1-1.6-1.7-2.6-1.8c0,0,0,0,0,0c-1,0-2,0.6-2.6,1.7l-0.1,0.2c-0.1,0.2-0.3,0.3-0.5,0.4
   C8.2,20,8,20,7.8,19.9l-1.8-1c-0.2-0.1-0.4-0.3-0.5-0.6c-0.1-0.3,0-0.5,0.1-0.8c0.6-1.1,0.7-2.3,0.2-3.1c-0.5-0.9-1.5-1.4-2.8-1.4
   c-0.6,0-1-0.5-1-1v-1.9c0-0.6,0.5-1,1-1c1.3,0,2.3-0.5,2.8-1.4c0.5-0.9,0.4-2-0.2-3.1C5.5,4.3,5.4,4,5.5,3.8C5.6,3.5,5.8,3.3,6,3.2
   l1.8-1.1C8,2,8.2,2,8.4,2c0.2,0.1,0.4,0.2,0.5,0.4L9,2.6c0.6,1.1,1.6,1.7,2.6,1.7c0,0,0,0,0,0c1,0,2-0.6,2.6-1.7l0.1-0.2
   c0.1-0.2,0.3-0.3,0.5-0.4C15,2,15.2,2,15.4,2.1l1.8,1c0.2,0.1,0.4,0.3,0.5,0.6c0.1,0.3,0,0.5-0.1,0.8c-0.6,1.1-0.7,2.3-0.2,3.1
   c0.5,0.9,1.5,1.4,2.8,1.4c0.6,0,1,0.5,1,1V11.9z"/>
</g>
</svg>';
$event_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 20 23" style="enable-background:new 0 0 20 23;" xml:space="preserve">
<style type="text/css">.st0{fill:#77797E;}</style><g><path class="st0" d="M15,2.6V1c0-0.6-0.4-1-1-1s-1,0.4-1,1v1.5H7V1c0-0.6-0.4-1-1-1S5,0.4,5,1v1.6C1.8,2.9,0,5,0,8.5V17
   c0,3.8,2.2,6,6,6h8c3.8,0,6-2.2,6-6V8.5C20,5,18.2,2.9,15,2.6z M14,21H6c-2.7,0-4-1.3-4-4v-6.9h16V17C18,19.7,16.7,21,14,21z"/>
<path class="st0" d="M11,12.2L11,12.2c-0.7,0-1.3,0.6-1.3,1.2s0.6,1.2,1.3,1.2s1.2-0.6,1.2-1.2S11.7,12.2,11,12.2z"/>
<path class="st0" d="M6.3,12.2L6.3,12.2c-0.7,0-1.3,0.6-1.3,1.2s0.6,1.2,1.3,1.2s1.2-0.6,1.2-1.2S7,12.2,6.3,12.2z"/>
<path class="st0" d="M6.3,16.2L6.3,16.2c-0.7,0-1.3,0.6-1.3,1.2s0.6,1.2,1.3,1.2s1.2-0.6,1.2-1.2S7,16.2,6.3,16.2z"/>
</g></svg>';
$integrations_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
<style type="text/css">.st0{fill:#77797E;}</style><g><path class="st0" d="M17.1,0h-3.4c-1.9,0-2.9,1-2.9,2.9v3.4c0,1.9,1,2.9,2.9,2.9h3.4c1.9,0,2.9-1,2.9-2.9V2.9C20,1,19,0,17.1,0z
	M18,6.3c0,0.8-0.1,0.9-0.9,0.9h-3.4c-0.8,0-0.9-0.1-0.9-0.9V2.9c0-0.8,0.1-0.9,0.9-0.9h3.4C17.9,2,18,2.1,18,2.9V6.3z"/>
<path class="st0" d="M6.3,0H2.9C0.5,0,0,1.5,0,2.7v3.9c0,1.2,0.5,2.7,2.9,2.7h3.4c0,0,0,0,0,0c0.6,0,1.5-0.1,2.2-0.7
   c0.5-0.5,0.7-1.1,0.7-2V2.7C9.2,1.5,8.7,0,6.3,0z"/><path class="st0" d="M6.3,10.8H2.9c-1.9,0-2.9,1-2.9,2.9v3.4C0,19,1,20,2.9,20h3.4c1.9,0,2.9-1,2.9-2.9v-3.4
   C9.2,11.8,8.2,10.8,6.3,10.8z M7.2,17.1c0,0.8-0.1,0.9-0.9,0.9H2.9C2.1,18,2,17.9,2,17.1v-3.4c0-0.8,0.1-0.9,0.9-0.9h3.4
   c0.8,0,0.9,0.1,0.9,0.9V17.1z"/><path class="st0" d="M17.7,14.2h-1.6v-1.6c0-0.6-0.4-1-1-1s-1,0.4-1,1v1.6h-1.6c-0.6,0-1,0.4-1,1s0.4,1,1,1h1.6v1.6
   c0,0.6,0.4,1,1,1s1-0.4,1-1v-1.6h1.6c0.6,0,1-0.4,1-1S18.3,14.2,17.7,14.2z"/>
</g></svg>';

$settings_tabs = [
    "etn-general_options" => [
        "class"         => "nav-tab",
        "icon_class"    => "eventin-general_icon",
        "data_id"       => "tab1",
        "title"         => esc_html__( 'General Settings', 'eventin' ),
		"icon"			=> $general_icon,
        "content"       => \Wpeventin::plugin_dir() . "core/settings/views/parts/general-settings-view.php"
    ],  
    "etn-events_options" => [
        "class"         => "nav-tab",
        "icon_class"    => "eventin-details_icon",
        "data_id"       => "tab2",
        "title"         => esc_html__( 'Event Settings', 'eventin' ),
		"icon"			=> $event_icon,
        "content"       => \Wpeventin::plugin_dir() . "core/settings/views/parts/events-settings-view.php"
    ],
    "etn-user_data" => [
        "class"         => "etnshortcode-nav nav-tab",
        "icon_class"    => "eventin-user_icon",
        "data_id"       => "tab5",
        "title"         => esc_html__( 'Integrations', 'eventin' ),
		"icon"			=> $integrations_icon,
        "content"       => \Wpeventin::plugin_dir() . "core/settings/views/parts/zoom-settings-view.php"
    ],
];
	
	// header menu start.
	include_once( \Wpeventin::plugin_dir() . "templates/layout/header.php" );
	// header menu end.

?>
<div class="wrap etn-settings-dashboard">
	<div class="etn-settings-tab">
			<ul class="nav-tab-wrapper etn-tab">
					<?php
					$settings_tabs = apply_filters( "eventin/settings/tab_titles", $settings_tabs );
					$recent_tab = 'tab1';
					if( isset( $_GET['etn_tab'] ) ){
						$recent_tab = $_GET['etn_tab'];
					}
					foreach( $settings_tabs as $tab_id => $tab_attrs ) {
							?>
							<li>
								
								<a href="#<?php echo esc_attr( $tab_id ); ?>" class="<?php echo esc_attr( $tab_attrs["class"] ); ?>" id="<?php echo esc_attr( $tab_id ); ?>" data-id="<?php echo esc_attr( $tab_attrs["data_id"] );?>"> 
									<?php 
										echo Helper::render($tab_attrs["icon"]);
									?>
									<?php echo esc_html( $tab_attrs["title"] ); ?>
								</a>
							</li>
							<?php
					}
					?>
			</ul>

	</div>
	<div class="etn-admin-container stuffbox">
			<div class=" etn-admin-container--body">
					<form action="" method="post" class="attr-tab-content form-group etn-admin-input-text etn-settings-from" id="<?php esc_attr_e( $recent_tab );?>">
							<?php
							foreach( $settings_tabs as $tab_id => $tab_attrs ) {
									if( file_exists( $tab_attrs['content'] ) ) {
											include_once $tab_attrs['content'];
									}
							}
							?>
							<div class="etn_submit_wrap">
									<input type="hidden" name="etn_tab" class="etn_tab" value="<?php esc_html_e( $recent_tab );?>">
									<input type="hidden" name="etn_settings_page_action" value="save">
									<input type="submit" name="submit" id="submit" class="etn-btn etn-btn-primary etn_save_settings" value="<?php esc_html_e('Save Changes', 'eventin'); ?>">

									<?php wp_nonce_field('etn-settings-page', 'etn-settings-page'); ?>
							</div>
					</form>
			</div>
	</div>
</div>
