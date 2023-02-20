<?php 
defined( 'ABSPATH' ) || exit;
?>
<section class="woocommerce-order-details">
	<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Attendee details', 'eventin' ); ?></h2>
	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<thead>
			<tr>
				<th class="woocommerce-table__product-name attendee-name"><?php esc_html_e( 'Name', 'eventin' ); ?></th>
                <?php if ( $include_email ) { ?>
				<th class="woocommerce-table__product-table attendee-email"><?php esc_html_e( 'Email', 'eventin' ); ?></th>
                <?php } ?>
                <?php if ( $include_phone ) { ?>
                <th class="woocommerce-table__product-table attendee-phone"><?php esc_html_e( 'Phone', 'eventin' ); ?></th>
                <?php } ?>
                <th class="woocommerce-table__product-table attendee-ticket-status"><?php esc_html_e( 'Ticket Status', 'eventin' ); ?></th>
                <th class="woocommerce-table__product-table attendee-action"><?php esc_html_e( 'Action', 'eventin' ); ?></th>
			</tr>
		</thead>

		<tbody>
        <?php 
        foreach( $attendees as $attendee){
            $new_ticket_download_link = $ticket_download_link;
            $new_edit_information_link = $edit_information_link;
            $attendee_id           = $attendee->ID;
            $etn_email             = get_post_meta( $attendee_id, 'etn_email', true );
            $etn_phone             = get_post_meta( $attendee_id, 'etn_phone', true );
            $ticket_status         = get_post_meta( $attendee_id, 'etn_attendeee_ticket_status', true );

            $edit_token            = get_post_meta( $attendee_id, 'etn_info_edit_token', true );
            $new_ticket_download_link  .= urlencode($attendee_id) . "&etn_info_edit_token=" . urlencode ( $edit_token );
            $new_edit_information_link .= urlencode( $attendee_id ) . "&etn_info_edit_token=" . urlencode( $edit_token );
            ?>
            <tr>
                <td><?php echo esc_html( $attendee->post_title ); ?></td>
                <?php if ( $include_email ) { ?>
                    <td><?php echo esc_html( $etn_email ); ?></td>
                <?php } ?>
                <?php if ( $include_phone ) { ?>
                    <td><?php echo esc_html( $etn_phone ); ?></td>
                <?php } ?>
                <td><?php echo esc_html( $ticket_status ); ?></td>
                <td>
                    <div class=''>
                        <a class='' target='_blank' href='<?php echo esc_url( $new_edit_information_link ); ?>' rel='noopener'><?php echo esc_html__('Edit', 'eventin'); ?></a> | 
                        <a class='' target='_blank' href='<?php echo esc_url( $new_ticket_download_link ); ?>' rel='noopener'><?php echo esc_html__('Download Ticket', 'eventin'); ?></a>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>
		</tbody>
	</table>

</section>