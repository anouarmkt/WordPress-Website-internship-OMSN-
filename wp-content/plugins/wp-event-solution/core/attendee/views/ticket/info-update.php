<?php

use Etn\Utils\Helper;

$get_arr = filter_input_array( INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if( empty( $get_arr["attendee_id"] ) || empty( $get_arr["etn_info_edit_token"] ) ){
    Helper::show_attendee_pdf_invalid_data_page();
    exit;
}

if( !Helper::verify_attendee_edit_token( $get_arr["attendee_id"], $get_arr["etn_info_edit_token"] ) ){
    Helper::show_attendee_pdf_invalid_data_page();
    exit;
}

    $user_id            = is_numeric( $get_arr["attendee_id"] ) ? $get_arr["attendee_id"] : 0;
    $access_token       = $get_arr['etn_info_edit_token'];
    $attendee_data      = Helper::get_attendee_by_token( 'etn_info_edit_token', $access_token  );
    $attendee_name      = get_the_title( $user_id );
    $attendee_email     = get_post_meta( $user_id, "etn_email", true );
    $attendee_phone     = get_post_meta( $user_id, "etn_phone", true );
    $base_url           = home_url( );
    $attendee_cpt       = new \Etn\Core\Attendee\Cpt();
    $attendee_endpoint  = $attendee_cpt->get_name();
    $action_url         = $base_url . "/" . $attendee_endpoint;
    wp_head();
    $attendee_update    = true;
    ?>

    <div class="etn-es-events-page-container">
        <div class="etn-event-single-wrap">
            <div class="etn-container">
                <div class="etn-attendee-form">
                    <h3 class="attendee-title"><?php echo esc_html__( "Update Attendee Details", "eventin" ); ?></h3>
                    <hr>
                    <form action="<?php echo esc_url( $action_url );?>" method="post" class="attende_form">
                        <div class="etn-attendee-form-wrap">
														<?php
														// render template.
														if( file_exists( \Wpeventin::core_dir() . "attendee/views/ticket/part/ticket-form.php" ) ){
																include_once \Wpeventin::core_dir() . "attendee/views/ticket/part/ticket-form.php";
														}

                            $settings              = Helper::get_settings();
                            $attendee_extra_fields = isset($settings['attendee_extra_fields']) ? $settings['attendee_extra_fields'] : [];

                            if( is_array( $attendee_extra_fields ) && !empty( $attendee_extra_fields ) ) {
                                foreach( $attendee_extra_fields as $index => $attendee_extra_field ){
                                    $label_content = $attendee_extra_field['label'];
                                    if( !empty($label_content) && !empty($attendee_extra_field['type']) ){
                                        $name_from_label         = \Etn\Utils\Helper::generate_name_from_label( "etn_attendee_extra_field_", $label_content );
                                        $extra_field_saved_value = get_post_meta( $user_id, $name_from_label, true );
                                        $class_name_from_label   = \Etn\Utils\Helper::get_name_structure_from_label($label_content);
																				$etn_field_type = '';
																				$required_span  = '';
																				if ( !empty($attendee_extra_field['etn_field_type']) && $attendee_extra_field['etn_field_type'] == 'required'   ) {
																					$etn_field_type = 'required';
																					$required_span  = '<span class="etn-input-field-required">*</span>';
																				}
                                        ?>

                                        <div class="etn-<?php echo esc_attr( $class_name_from_label ); ?>-field etn-group-field">
                                            <label for="etn_attendee_extra_field_<?php echo esc_attr( $index ); ?>">
                                                <?php echo esc_html( $label_content ); echo  Helper::kses( $required_span ); ?>
                                            </label>
                                            <?php
                                                if( $attendee_extra_field['type'] == 'radio' ){
                                                    $radio_arr = isset( $attendee_extra_field['radio'] ) ? $attendee_extra_field['radio'] : [];
                                                    if( is_array($radio_arr) && !empty($radio_arr) ){
                                                        ?>
                                                        <div class="etn-radio-field-wrap">
                                                            <?php
                                                                foreach( $radio_arr as $radio_index => $radio_val ) {
                                                                    ?>
                                                                    <div class="etn-radio-field">
                                                                        <input type="radio" name="<?php echo esc_attr( $name_from_label ); ?>" value="<?php echo esc_attr( $radio_index ); ?>"
                                                                            class="attr-form-control1 etn-attendee-extra-fields1"
                                                                            id="etn_attendee_extra_field_<?php echo esc_attr( $index ); ?>_radio_<?php echo esc_attr( $radio_index ); ?>"
                                                                            <?php checked( $extra_field_saved_value, $radio_index, true ) ?>  data-etn_required="<?php esc_attr_e($etn_field_type);?>" />
                                                                        <label for="etn_attendee_extra_field_<?php echo esc_attr( $index ); ?>_radio_<?php echo esc_attr( $radio_index ); ?>"><?php echo esc_html( $radio_val ); ?></label>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            ?>
                                                        <div class="etn-error <?php echo esc_attr( 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index ); ?>"></div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else if( $attendee_extra_field['type'] == 'checkbox' ) {
                                                    $checkbox_arr = isset( $attendee_extra_field['checkbox'] ) ? $attendee_extra_field['checkbox'] : [];
                                                    if( is_array( $checkbox_arr ) && ! empty( $checkbox_arr ) ) {
                                                        $extra_field_saved_value_arr = [];
                                                        if ( !empty( $extra_field_saved_value ) ) {
                                                            $extra_field_saved_value_arr = maybe_unserialize( $extra_field_saved_value );
                                                        }
                                                        ?>
                                                        <div class="etn-checkbox-field-wrap">
                                                        <?php
                                                            foreach( $checkbox_arr as $checkbox_index => $checkbox_val ) {
                                                                $id = 'etn_attendee_extra_field_'.$key.'_attendee_'.$i.'_input_'.$index.'_checkbox_'.$checkbox_index.'';
                                                                ?>
                                                                    <div class="etn-checkbox-field">
                                                                        <input type="checkbox" name="<?php echo esc_attr( $name_from_label ); ?>[]" value="<?php echo esc_attr( $checkbox_index ); ?>"
                                                                        <?php checked( in_array( $checkbox_index, $extra_field_saved_value_arr ), true, true ) ?> class="etn-attendee-extra-fields" id="<?php esc_attr_e( $id );?>" data-etn_required="<?php esc_attr_e($etn_field_type);?>" />
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
                                                        name="<?php echo esc_attr( $name_from_label ); ?>"
                                                        value="<?php echo esc_attr( $extra_field_saved_value ); ?>"
                                                        class="attr-form-control etn-attendee-extra-fields"
                                                        id="etn_attendee_extra_field_<?php echo esc_attr( $index ); ?>"
                                                        placeholder="<?php echo !empty( $attendee_extra_field['place_holder'] ) ? esc_attr( $attendee_extra_field['place_holder'] ) : ''; ?>"
                                                        <?php echo ($attendee_extra_field['type'] == 'number') ? "pattern='\d+'" : '' ?> <?php esc_attr_e($etn_field_type);?> />
                                                    <?php
                                                }
                                            ?>
                                            <div class="etn-error etn_attendee_extra_field_<?php echo esc_attr( $index ); ?>"></div>
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

                        <?php wp_nonce_field( 'attendee_details_nonce', 'attendee_personal_data' );?>
                        <input type="hidden" name="etn_attendee_details_update_action" value="etn_attendee_details_update_action" required/>
                        <input type="hidden" name="etn_attendee_id" value="<?php echo esc_html( $user_id ); ?>" required/>
                        <input type="hidden" name="etn_info_edit_token" value="<?php echo esc_html( $access_token ); ?>" required/>
                        <input type="submit" name="submit" class="etn-btn etn-primary attendee_update_submit" value="<?php echo esc_html__( "Update", "eventin" ); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    wp_footer(  );
exit;