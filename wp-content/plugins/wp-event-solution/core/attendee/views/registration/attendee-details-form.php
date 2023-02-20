<?php

use Etn\Utils\Helper;

if ( $check && !empty( $post_arr["variation_picked_total_qty"] ) && !empty( $post_arr["event_id"] ) ) {

	$total_qty = 0;
	if ( isset( $post_arr["variation_picked_total_qty"] ) ) {
		$total_qty = absint( $post_arr["variation_picked_total_qty"] );
	}

	if ( empty( $total_qty ) ) {
		return;
	}

    $attendee_info_update_key = md5( md5( "etn-access-token" . time() . $total_qty ) );
    wp_head();
    $add_to_cart_id = $post_arr["event_id"];
    ?>

	<div class="etn-es-events-page-container etn-attendee-registration-page etn-event-id-<?php echo esc_attr($add_to_cart_id); ?>">
		<div class="etn-event-single-wrap">
			<div class="etn-container">
				<div class="etn-attendee-form">
					<!-- Title -->
					<h3 class="attendee-title"><?php echo esc_html__( "Attendee Details for - ", "eventin" ) . esc_html( $post_arr["event_name"] ); ?></h3>
					<form action="" method="post" id="etn-event-attendee-data-form" class="attende_form">
						<?php wp_nonce_field( 'ticket_purchase_next_step_three', 'ticket_purchase_next_step_three' ); ?>
						<input type="hidden" name="ticket_purchase_next_step" value="three" />

						<!-- for compatibility with deposit plugin: check two variables are set in request. if set, deposit is running and pass them in reg form popup -->
						<?php if ( ! empty( $deposit_enabled ) ) { ?>
							<input type="hidden" name="wc_deposit_option" value="yes" />
						<?php } ?>

						<?php if ( ! empty( $deposit_payment_plan ) ) { ?>
							<input type="hidden" name="wc_deposit_payment_plan" value="<?php echo esc_attr( $deposit_payment_plan ); ?>" />
						<?php
							}
							$add_to_cart_id = $post_arr["event_id"];
							if ( isset( $post_arr["lang_event_id"] ) ) {
								$add_to_cart_id = $post_arr["lang_event_id"];
							}

							$specific_lang = '';
							if ( isset( $_GET['lang'] ) ) {
								$specific_lang = $_GET["lang"];
							}
						?>
						
						<input type="hidden" name="event_name" value="<?php echo esc_html( $post_arr["event_name"] ); ?>" />
						<input type="hidden" name="sells_engine" value="<?php echo esc_html( !empty($post_arr['sells_engine']) ? $post_arr['sells_engine'] : 'woocommerce'); ?>" />
						<input type="hidden" name="client_fname" value="<?php echo esc_html( !empty( $post_arr["client_fname"] ) ? $post_arr["client_fname"] : '' ); ?>" />
						<input type="hidden" name="client_lname" value="<?php echo esc_html( !empty( $post_arr["client_lname"] ) ? $post_arr["client_lname"] : '' ); ?>" />
						<input type="hidden" name="client_email" value="<?php echo esc_html( !empty( $post_arr["client_email"] ) ? $post_arr["client_email"] : '' ); ?>" />
						<input type="hidden" name="add-to-cart" value="<?php echo intval( $add_to_cart_id ); ?>" />
						<input type="hidden" name="specific_lang" value="<?php echo esc_html( $specific_lang ); ?>" />
						<input type="hidden" name="quantity" value="1" />
						<input type="hidden" name="attendee_info_update_key" value="<?php echo esc_html( $attendee_info_update_key ); ?>" />
						<input type="hidden" name="variation_picked_total_qty" value="<?php echo esc_attr( $total_qty ); ?>" />

						<?php
						if ( !empty( $post_arr["ticket_name"] ) &&  count( $post_arr["ticket_name"] ) > 0 ) {
							// ticket variation loop. 1st loop.
							foreach ( $post_arr["ticket_name"] as $key => $ticket_name ) {
								?>
								<div class="etn-ticket-single-variation-details">
									<?php if( !empty( $post_arr["ticket_quantity"] ) && (int) $post_arr[ 'ticket_quantity' ][ $key ] > 0 ) {?>
										<div class="etn-ticket-single-variation-title" data-ticket_name="<?php echo esc_attr($ticket_name); ?>" >
											<div class="etn-ticket-single-variation-title-wrap">
												<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
													<circle cx="17" cy="17" r="17" fill="#5D78FF" fill-opacity="0.2"/>
													<path d="M24.8476 12.6595C23.879 13.6281 22.3087 13.6281 21.3405 12.6595C20.3723 11.6909 20.3719 10.1206 21.3405 9.1524L19.6252 7.4375L7.4375 19.6252L9.1524 21.3401C10.121 20.3715 11.6913 20.3715 12.6599 21.3401C13.6285 22.3087 13.6285 23.879 12.6599 24.8472L14.3748 26.5625L26.5625 14.3748L24.8476 12.6595ZM16.9821 14.2713L16.1864 13.4757L16.9787 12.6834L17.7743 13.4791L16.9821 14.2713ZM18.573 15.8622L17.7773 15.0666L18.5696 14.2743L19.3652 15.0699L18.573 15.8622ZM20.1642 17.4535L19.3686 16.6578L20.1609 15.8656L20.9565 16.6612L20.1642 17.4535Z" fill="#5D78FF"/>
												</svg>
												<h3><?php echo esc_html( $ticket_name );?></h3>
											</div>

											<svg class="etn-arrow-icon" width="20" height="13" viewBox="0 0 20 13" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M2 11L10 3L18 11" stroke="black" stroke-width="3"/>
											</svg>
										</div>
									<?php
										}
										$ticket_quantity = !empty( $post_arr["ticket_quantity"] ) ? $post_arr["ticket_quantity"] : [];

										if ( !empty( $post_arr["ticket_quantity"] ) && count( $post_arr["ticket_quantity"] ) >0 ) {
											$radio_generated_indexes = $checkbox_generated_indexes = [];

											$variation_qty 	 = (int) $post_arr[ 'ticket_quantity' ][ $key ];
											// client purchase no of tickets . 2nd loop.
											for ( $i = 1; $i <= $variation_qty; $i++ ) {
												?>
												<div class="etn-attendee-form-wrap <?php echo esc_attr($ticket_name); ?>" data-ticket_name="<?php echo esc_attr($ticket_name); ?>" >
														<div class="etn-attendy-count">
															<h4><?php echo esc_html__( "Attendee - ", "eventin" ) . $i; ?></h4>
														</div>
														<input type="hidden" name="ticket_index[]" value="<?php esc_attr_e( $key ); ?>" />
														<?php
														// render template.
														if( file_exists( \Wpeventin::core_dir() . "attendee/views/ticket/part/ticket-form.php" ) ){
															include \Wpeventin::core_dir() . "attendee/views/ticket/part/ticket-form.php";
														}
														$attendee_extra_fields = isset($settings['attendee_extra_fields']) ? $settings['attendee_extra_fields'] : [];

														if ( is_array($attendee_extra_fields) && !empty($attendee_extra_fields) ){
															foreach( $attendee_extra_fields as $index => $attendee_extra_field ){
																$label_content  = $attendee_extra_field['label'];
																$etn_field_type = '';
																$required_span  = '';
																if ( !empty($attendee_extra_field['etn_field_type']) && $attendee_extra_field['etn_field_type'] == 'required'   ) {
																	$etn_field_type = 'required';
																	$required_span  = '<span class="etn-input-field-required">*</span>';
																}

																if( !empty($label_content) && !empty($attendee_extra_field['type']) ){
																	$name_from_label       = \Etn\Utils\Helper::generate_name_from_label( "etn_attendee_extra_field_" , $label_content);
																	$class_name_from_label = \Etn\Utils\Helper::get_name_structure_from_label($label_content);
																	?>

																	<div class="etn-<?php echo esc_attr( $class_name_from_label ); ?>-field etn-group-field">
																		<label for="etn_attendee_extra_field_<?php echo esc_attr( $key ) . "_attendee_" . intval( $i ) ?>">
																			<?php echo esc_html( $label_content );  echo  Helper::kses( $required_span ) ?>
																		</label>

																		<?php
																			if( $attendee_extra_field['type'] == 'radio' ) {
																				$radio_arr = isset( $attendee_extra_field['radio'] ) ? $attendee_extra_field['radio'] : [];

																				if( is_array($radio_arr) && !empty($radio_arr) ) {
																					$special_radio_index = $key .'_'. ( $i-1 );
																					if ( !in_array( $special_radio_index, $radio_generated_indexes ) ) {
																						$radio_generated_indexes[] = $special_radio_index;
																						?>
																						<input type="hidden" name="radio_track_index[]" value="<?php esc_attr_e( $special_radio_index );?>"/>
																						<?php
																					}
																					?>
																					<div class="etn-radio-field-wrap">
																					<?php
																					foreach( $radio_arr as $radio_index => $radio_val ) {
																						$id = 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index.'_radio_'.$radio_index.'';
																						?>
																						<div class="etn-radio-field">
																							<input type="radio" name="<?php echo esc_attr( $name_from_label ) . '_' . $key .'_'. ( $i-1 ); ?>[]" value="<?php echo esc_attr( $radio_index ); ?>"
																								class="etn-attendee-extra-fields" id="<?php esc_attr_e( $id );?>" data-etn_required="<?php esc_attr_e($etn_field_type);?>"/>
																							<label for="<?php esc_attr_e( $id );?>"><?php echo esc_html( $radio_val ); ?></label>
																						</div>
																						<?php
																					}
																					?>
																					<div class="etn-error <?php echo esc_attr( 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index ); ?>"></div>
																					</div>
																					<?php
																				}

																			} else if( $attendee_extra_field['type'] == 'checkbox' ){
																				$checkbox_arr = isset( $attendee_extra_field['checkbox'] ) ? $attendee_extra_field['checkbox'] : [];

																				if( is_array( $checkbox_arr ) && ! empty( $checkbox_arr ) ) {
																					$special_checkbox_index = $key .'_'. ( $i-1 );
																					if ( !in_array( $special_checkbox_index, $checkbox_generated_indexes ) ) {
																						$checkbox_generated_indexes[] = $special_checkbox_index;
																						?>
																						<input type="hidden" name="checkbox_track_index[]" value="<?php esc_attr_e( $special_checkbox_index );?>"/>
																						<?php
																					}
																					?>
																					<div class="etn-checkbox-field-wrap">
																					<?php
																						foreach( $checkbox_arr as $checkbox_index => $checkbox_val ) {
																							$id = 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index.'_checkbox_'.$checkbox_index.'';
																							?>
																								<div class="etn-checkbox-field">
																									<input type="checkbox" name="<?php echo esc_attr( $name_from_label ) . '_' . $key .'_'. ( $i-1 ); ?>[]" value="<?php echo esc_attr( $checkbox_index ); ?>"
																										class="etn-attendee-extra-fields" id="<?php esc_attr_e( $id );?>" data-etn_required="<?php esc_attr_e($etn_field_type);?>" />
																									<label for="<?php esc_attr_e( $id );?>"><?php echo esc_html( $checkbox_val );?></label>
																								</div>
																							<?php
																						}
																					?>
																					<div class="etn-error <?php echo esc_attr( 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index ); ?>"></div>
																					</div>
																					<?php
																				}
																			} else {
																				?>
																				<input type="<?php echo esc_html( $attendee_extra_field['type'] ); ?>"
																					name="<?php echo esc_attr( $name_from_label ); ?>[]"
																					class="attr-form-control etn-attendee-extra-fields"
																					id="etn_attendee_extra_field_<?php echo esc_attr( $index ) . "_attendee_" . intval( $i ) ?>"
																					placeholder="<?php echo !empty($attendee_extra_field['place_holder']) ? esc_attr( $attendee_extra_field['place_holder'] ) : ''; ?>"
																					<?php echo ($attendee_extra_field['type'] == 'number') ? "pattern='\d+'" : '' ; esc_attr_e( $etn_field_type )?> />
																				<?php
																			}
																		?>

																		<div class="etn-error etn_attendee_extra_field_<?php echo esc_attr( $index ) . "_attendee_" . intval( $i ) ?>"></div>
																	</div>
																	<?php
																	} else { ?>
																		<p class="error-text"><?php echo esc_html__( 'Please Select input type & label name from admin', 'eventin' ); ?></p>
																	<?php
																}

															}
														}
														?>
												</div>
												<?php
											}
										}

									?>
									<input type="hidden" name="ticket_quantity[]" value="<?php echo absint( $post_arr["ticket_quantity"][$key] ); ?>" />
									<input type="hidden" name="ticket_price[]" value="<?php echo Helper::render($post_arr["ticket_price"][$key]); ?>" />
									<input type="hidden" name="ticket_name[]" value="<?php echo esc_html( $ticket_name ); ?>" />
									<input type="hidden" name="ticket_slug[]" value="<?php echo esc_html( $post_arr["ticket_slug"][$key] ); ?>" />
								</div>
								<?php
							}
						}

						?>
						<div class="attendee-button-wrapper">
							<input type="hidden" name="etn_total_price" value="<?php echo esc_html( $post_arr["etn_total_price"] ); ?>" />
							<input type="hidden" name="etn_total_qty" value="<?php echo esc_html( $post_arr["etn_total_qty"] ); ?>" />
							<a href="<?php echo get_permalink(); ?>" class="etn-btn etn-btn-secondary attendee_goback"><?php echo esc_html__( "Go Back", "eventin" ); ?></a>
							<button type="submit" name="submit" class="etn-btn etn-primary attendee_submit"><?php echo esc_html__( "Confirm", "eventin" ); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	wp_footer();
	exit;
} else {
	wp_redirect( get_permalink() );
}

return;

