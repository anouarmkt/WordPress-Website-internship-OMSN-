<?php
    wp_head();
    
    $ticket_file_name = sanitize_title_with_dashes($attendee_name);
?>
<div class="etn-ticket-download-wrapper">
    <div class="etn-ticket-wrap" id="etn_attendee_details_to_print" >
      <div class="etn-ticket-wrapper">
            <div class="etn-ticket-main-wrapper">
                <div class="etn-ticket">
                    <?php  if(has_custom_logo()){ ?>
                          <div class="etn-ticket-logo-wrapper">
                             <?php 
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $image = wp_get_attachment_image_src( $custom_logo_id, 'Full' );
                            ?>
                             <img style="max-width: 100px; object-fit: cover"  src="<?php echo esc_url($image[0]); ?>" />

                            <div class="logo-shape">
                                <span class="logo-bar bar-one" ></span>
                                <span class="logo-bar bar-two" ></span>
                                <span class="logo-bar bar-three" ></span>
                            </div>
                        </div>
                    <?php    }  ?>
                  
                    <div class="etn-ticket-head">
                        <h3 class="etn-ticket-head-title"><?php echo esc_html( $event_name ) ?></h3>
                        <p class="etn-ticket-head-time"><?php echo esc_html( $date.' @ '. $time ) ?> </p>
                    </div>
                    <div class="etn-ticket-body">
                        <div class="etn-ticket-body-top">
                            <div class="etn-ticket-body-top-ul-wrapper">
                                <ul class="etn-ticket-body-top-ul">
                                    <?php do_action('etn_pro_ticket_id', $attendee_id, $event_id); ?>

                                    <?php
                                        if ( $ticket_name !== "") {
                                    ?>
                                        <li class="etn-ticket-body-top-li"><?php echo esc_html__( "TYPE :", "eventin" ); ?> <p><?php echo esc_html( $ticket_name ) ?></p></li>
                                    <?php }?>

                                    <?php
                                        if ( $event_location !== "") {
                                    ?>
                                    <li class="etn-ticket-body-top-li"><?php echo esc_html__( "VENUE :", "eventin" ); ?> <p><?php echo esc_html( $event_location ) ?></p></li>
                                    <?php }?>
                                    <?php
                                        if ( $ticket_price !== "") {
                                    ?>
                                    <li class="etn-ticket-body-top-li"><?php echo esc_html__( "PRICE :", "eventin" ); ?> 
                                    <p>
                                        <?php 
                                            if ( class_exists('WooCommerce') ) {
                                                $ticket_price = is_float( $ticket_price ) ? wc_format_decimal( $ticket_price, wc_get_price_decimals() ) : $ticket_price;

                                                $currency_symbol = get_woocommerce_currency_symbol();
                                                $currency_pos 	 = get_option( 'woocommerce_currency_pos' );
                                                if ( $currency_pos == 'left_space' ) {
                                                    $currency_symbol = $currency_symbol . ' ';
                                                } elseif ( $currency_pos == 'right_space' ) {
                                                    $currency_symbol = ' ' . $currency_symbol;
                                                }

                                                $print_left = ( strpos( $currency_pos, 'left' ) !== false ) ? true : false;
                                                echo ( $print_left ) ? $currency_symbol . esc_html( $ticket_price ):  esc_html( $ticket_price ) . $currency_symbol;
                                            } else {
                                                $ticket_price = is_float( $ticket_price ) ? number_format( $ticket_price, 2 ) : $ticket_price;
                                            }
                                        ?>
                                    </p>
                                    </li>
                                    <?php }?>
                                    <?php
                                        if ( $attendee_name !== "") {
                                    ?>
                                    <li class="etn-ticket-body-top-li"><?php echo esc_html__( "ATTENDEE :", "eventin" ); ?> <p><?php echo esc_html( $attendee_name ) ?></p></li>
                                    <?php }?>
                                    
                                    <?php
                                        if ( $include_phone  && $attendee_phone !== "") {
                                    ?>
                                            <li class="etn-ticket-body-top-li"><?php echo esc_html__( "PHONE :", "eventin" ); ?> <p><?php echo esc_html( $attendee_phone ) ?></p></li>
                                    <?php }?>
                                    <?php
                                        if ( $include_email && $attendee_email !== "" ) {
                                    ?>
                                            <li class="etn-ticket-body-top-li"><?php echo esc_html__( "EMAIL :", "eventin" ); ?> <p><?php echo esc_html( $attendee_email ) ?></p></li>
                                    <?php }?>
                                </ul>
                            
                        </div>
                        <!-- <div class="etn-ticket-body-bottom"></div> -->
                    </div>
                    <div class="etn-ticket-qr-code">
                    <?php
                        do_action('etn_pro_ticket_qr', $attendee_id, $event_id);
                    ?>
                    </div>
                </div>
                <!-- <div class="etn-ticket-action"></div> -->
            </div>
      </div>


        

    </div>
</div>
<div class="etn-download-ticket">
    <button class="etn-btn button etn-print-ticket-btn" id="etn_ticket_print_btn" onclick="etn_ticket_content_area('etn-ticket-download-wrapper');" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Print", "eventin" ); ?></button>
    
    <button class="etn-btn button etn-download-ticket-btn" id="etn_ticket_download_btn" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Download", "eventin" ); ?></button>
</div>

<?php wp_footer(); ?>

