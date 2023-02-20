<?php
/**
* Common Markup for attendee form
*/

if(empty( $key ) ) {
	$key = 0;
}
if(empty( $i ) ) {
	$i = 0;
}
if ( empty($attendee_update )) {
	$attendee_form_name   = 'attendee_name[]';
	$attendee_form_email  = 'attendee_email[]';
	$attendee_form_phone  = 'attendee_phone[]';
}else{
	$attendee_form_name   = 'name';
	$attendee_form_email  = 'email';
	$attendee_form_phone  = 'phone';
}
?>
<div class="etn-name-field etn-group-field">
	<label for="attendee_name_<?php echo intval( $i ) ?>">
		<?php echo esc_html__( 'Name', "eventin" ); ?> <span class="etn-input-field-required">*</span>
	</label>
	<input required placeholder="<?php echo esc_html__('Enter attendee full name', 'eventin'); ?>" class="attr-form-control" id="ticket_<?php echo intval( $key ) ?>_attendee_name_<?php echo intval( $i ) ?>"
	name="<?php esc_attr_e( $attendee_form_name );?>"  type="text"
	value="<?php echo ! empty( $attendee_name ) ? esc_html( $attendee_name ) : "" ;?>"/>
	<div class="etn-error ticket_<?php echo intval( $key ) ?>_attendee_name_<?php echo intval( $i ) ?>"></div>
</div>
<?php

if ( $include_email ) {
	?>
	<div class="etn-email-field etn-group-field">
		<label for="attendee_email_<?php echo intval( $i ) ?>">
			<?php echo esc_html__( 'Email', "eventin" ); ?><span class="etn-input-field-required"> *</span>
		</label>
		<input required placeholder="<?php echo esc_html__('Enter email address', 'eventin'); ?>" class="attr-form-control"
		id="ticket_<?php echo intval( $key ) ?>_attendee_email_<?php echo intval( $i ) ?>" name="<?php esc_attr_e( $attendee_form_email );?>" type="email" value="<?php echo ! empty( $attendee_email ) ? esc_html( $attendee_email ) : "" ; ?>"/>
		<div class="etn-error ticket_<?php echo intval( $key ) ?>_attendee_email_<?php echo intval( $i ) ?>"></div>
	</div>
	<?php
}

if ( $include_phone ) {
	?>
	<div class="etn-phone-field etn-group-field">
		<label for="attendee_phone_<?php echo intval( $i ) ?>">
			<?php echo esc_html__( 'Phone', "eventin" ); ?><span class="etn-input-field-required">*</span>
		</label>
		<input required placeholder="<?php echo esc_html__('Enter phone number', 'eventin'); ?>" class="attr-form-control" maxlength="15" id="ticket_<?php echo intval( $key ) ?>_attendee_phone_<?php echo intval( $i ) ?>"
		name="<?php esc_attr_e( $attendee_form_phone );?>" type="tel"
		value="<?php echo ! empty( $attendee_phone ) ? esc_html( $attendee_phone ) : "" ;?>"/>
		<div class="etn-error ticket_<?php echo intval( $key ) ?>_attendee_phone_<?php echo intval( $i ) ?>"></div>
	</div>
	<?php
}
