<?php do_action( 'etn_before_add_to_cart_form', $single_event_id); ?>


<form method="post" class="etn-event-form-parent etn-ticket-variation"  data-etn_uid="<?php echo esc_html($unique_id); ?>" data-decimal-number-points="<?php echo class_exists( 'WooCommerce' ) ? esc_attr( wc_get_price_decimals() ) : '2'; ?>">
    <input name="event_name" type="hidden" value="<?php echo esc_html( $event_title ); ?>" />
    <input name="specific_lang" type="hidden" value="<?php echo isset( $_GET['lang'] ) ? esc_html( $_GET['lang'] ) : ''; ?>" />
    <?php

    apply_filters( 'etn_pro/stripe/stripe_field', null );
		if( !class_exists('Wpeventin_Pro') ){
			?>
				<input type="hidden" name="sells_engine" value="woocommerce"/>
			<?php
		}

    if( $attendee_reg_enable ){
        ?>
        <?php  wp_nonce_field('ticket_purchase_next_step_two','ticket_purchase_next_step_two'); ?>
        <input name="ticket_purchase_next_step" type="hidden" value="two" />
        <input name="event_id" type="hidden" value="<?php echo intval($single_event_id); ?>" />
        <?php
    }else{
        ?>
        <input name="add-to-cart" type="hidden" value="<?php echo intval($single_event_id); ?>" />
        <?php
    }
    ?>

    <!-- Ticket Markup Starts Here -->
    <?php
    $ticket_variation           = get_post_meta($single_event_id,"etn_ticket_variations",true);
    $etn_ticket_availability    = get_post_meta($single_event_id,"etn_ticket_availability",true);


    if ( is_array($ticket_variation) && count($ticket_variation) > 0 ) {
        $cart_ticket = [];
        if ( class_exists('Woocommerce') && !is_admin()){
            global $woocommerce;
            $items = $woocommerce->cart->get_cart();

            foreach($items as $item => $values) {
                if ( !empty( $values['etn_ticket_variations']) ) {
                    $variations = $values['etn_ticket_variations'];
                    if ( !empty($variations) && !empty($variations[0]['etn_ticket_slug'])) {
                        if ( !empty($cart_ticket[$variations[0]['etn_ticket_slug']])) {
                            $cart_ticket[$variations[0]['etn_ticket_slug']] += $variations[0]['etn_ticket_qty'];
                        }else {
                            $cart_ticket[$variations[0]['etn_ticket_slug']] = $variations[0]['etn_ticket_qty'];
                        }
                    }
                }
            }
        }

        $number = !empty($i) ? $i : 0;

        ?>
        <div class="variations_<?php echo intval($number);?>">

            <input type="hidden" name="variation_picked_total_qty" value="0" class="variation_picked_total_qty" />
            <?php foreach ($ticket_variation as $key => $value) {
                $total_tickets  = absint( $value['etn_avaiilable_tickets'] );
                if ( $total_tickets > 0 ) {
                $etn_min_ticket = !empty( $value['etn_min_ticket'] ) ? absint( $value['etn_min_ticket'] ) : 0 ;
                $etn_max_ticket = !empty( $value['etn_max_ticket'] ) ? absint( $value['etn_max_ticket'] ) : 0 ;
                $sold_tickets   = absint( $value['etn_sold_tickets'] );

                $etn_cart_limit = 0;
                if (  !empty($cart_ticket) ) {
                    $etn_cart_limit = !empty( $cart_ticket[$value['etn_ticket_slug']] ) ? $cart_ticket[$value['etn_ticket_slug']] : 0;
                }

                $etn_current_stock = absint( $total_tickets - $sold_tickets );
                $stock_outClass = ($etn_current_stock === 0) ? 'stock_out' : '';
                ?>
                <div class="variation_<?php esc_attr_e($key)?>">

                    <div class="etn-single-ticket-item">
                        <h5 class="ticket-header">
                            <?php
                            esc_html_e($value['etn_ticket_name']);
                            if ( !isset($event_options["etn_hide_seats_from_details"]) ) {
                                if ($etn_current_stock > 0) {
                                    if ($etn_ticket_availability == 'on') {
                                    ?>
                                    <span class="seat-remaining-text">(<?php echo esc_html($etn_current_stock); echo esc_html__(' seats remaining', 'eventin'); ?>)</span>
                                    <?php } else { ?>
                                        <span class="seat-remaining-text">(<?php echo esc_html__(' Unlimited tickets', 'eventin'); ?>)</span>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="seat-remaining-text">(<?php echo esc_html__('All tickets have been sold out', 'eventin'); ?>)</span>
                                <?php
                                }
                            }
                            ?>
                        </h5>
                        <div class="etn-ticket-divider"></div>
                        <div class="etn-ticket-price-body <?php echo esc_attr($stock_outClass) ?>">
                            <div class="ticket-price-item etn-ticket-price">
                                <label><?php echo esc_html__("Ticket Price :","eventin");?></label>
                                <strong>
                                    <?php
                                        if(function_exists("get_woocommerce_currency_symbol")){
                                            echo esc_html(get_woocommerce_currency_symbol());
                                        }  esc_html_e($value['etn_ticket_price']);
                                    ?>
                                </strong>
                            </div>

                            <!-- Min , Max and stock quantity checking start -->
                            <div class="ticket-price-item etn-quantity">
                                <label><?php echo esc_html__("Quantity :","eventin");?></label>
                                <button type="button" class="qt-btn qt-sub" data-multi="-1" data-key="<?php echo intval($key)?>">-</button>

                                <input name="ticket_quantity[]" type="number" class="etn_ticket_variation ticket_<?php esc_attr_e($key)?>" value="0"
                                    data-price="<?php echo floatval($value['etn_ticket_price']);?>"
                                    data-etn_min_ticket="<?php echo absint( $etn_min_ticket ); ?>"
                                    data-etn_max_ticket="<?php echo absint( $etn_max_ticket ); ?>"
                                    data-etn_current_stock="<?php echo absint( $etn_current_stock ); ?>"
                                    data-stock_out="<?php echo esc_attr__("All ticket has has been sold","eventin") ?>"
                                    data-cart_ticket_limit="<?php echo esc_attr__("You have already added 5 tickets. You can't purchase more than $etn_max_ticket tickets","eventin") ?>" 
                                    data-stock_limit="<?php echo esc_attr__("Stock limit $etn_current_stock. You can purchase within $etn_current_stock.","eventin") ?>" 
                                    data-qty_message="<?php echo esc_attr__("Total Ticket quantity must be greater than or equal ","eventin") . $etn_min_ticket . esc_attr__(" and less than or equal ","eventin") . $etn_max_ticket ; ?>"
                                    data-etn_cart_limit="<?php echo absint( $etn_cart_limit ); ?>"
                                    data-etn_cart_limit_message="<?php echo esc_attr__("You have already added $etn_cart_limit, Which is greater than maximum quantity $etn_max_ticket . You can add maximum $etn_max_ticket tickets. ","eventin"); ?>" 
                                />

                                <button type="button" class="qt-btn qt-add" data-multi="1" data-key="<?php echo intval($key)?>">+</button>
                            </div>

                            <!-- Min , Max and stock quantity checking start -->

                            <div class="ticket-price-item etn-subtotal" data-subtotal="<?php esc_attr_e($value['etn_ticket_price']);?>">
                                <label><?php echo esc_html__("Sub Total :","eventin");?></label>
                                <strong>
                                    <?php
                                        if(function_exists("get_woocommerce_currency_symbol")){
                                        echo esc_html(get_woocommerce_currency_symbol( ));
                                        }
                                    ?>
                                    <span class="_sub_total_<?php echo absint( $key ); ?>">0</span>
                                </strong>
                            </div>
                        </div>


                        <input name="ticket_price[]" type="hidden" value="<?php echo floatval($value['etn_ticket_price']);?>"/>
                        <input name="ticket_name[]" type="hidden" value="<?php esc_html_e($value['etn_ticket_name']);?>"/>
                        <input name="ticket_slug[]" type="hidden" value="<?php esc_html_e($value['etn_ticket_slug']);?>"/>
                    </div>
                    <div class="show_message show_message_<?php echo intval($key);?> quantity-error-msg"></div>

                </div>
                <?php do_action( 'etn_before_add_to_cart_total_price', $single_event_id, $key, $value ); ?>
                <?php
                }
            }
            ?>

            <!-- Ticket Markup Ends Here -->
            <div class="etn-variable-total-price">
                <div id="etn_variable_ticket_form_price" class="etn_variable_ticket_form_price">
                    <div class="etn-total-quantity">
                        <label><?php echo esc_html__('Total Quantity', "eventin"); ?></label>
                        <strong class="variation_total_qty">0.00</strong>
                    </div>

                    <div class="etn-ticket-total-price">
                        <label><?php echo esc_html__('Total Price', "eventin"); ?></label>
                        <strong>
                            <?php
                                if(function_exists("get_woocommerce_currency_symbol")){
                                echo esc_html(get_woocommerce_currency_symbol());
                                }
                            ?>
                            <span class="variation_total_price">0.00</span>
                        </strong>
                    </div>
										<input type="hidden" name="etn_total_price" class="variation_total_price" value="0"/>
                    <input type="hidden" name="etn_total_qty" class="variation_total_qty" value="0"/>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <?php do_action( 'etn_before_add_to_cart_button', $single_event_id); ?>

    <?php

    if (!isset($event_options["etn_purchase_login_required"]) || (isset($event_options["etn_purchase_login_required"]) && is_user_logged_in())) {
        ?>
        <input name="submit" class="etn-btn etn-primary etn-add-to-cart-block disabled" type="submit" value="<?php $cart_button_text = apply_filters( 'etn_event_cart_button_text', esc_html__("Buy ticket", "eventin") ); echo esc_html( $cart_button_text ); ?>" />
        <?php
    } else {
        ?>
        <small>
        <?php echo esc_html__('Please', 'eventin'); ?> <a href="<?php echo wp_login_url( get_permalink( ) ); ?>"><?php echo esc_html__( "Login", "eventin" ); ?></a> <?php echo esc_html__(' to buy ticket!', "eventin"); ?>
        </small>
        <?php
    }
    ?>

    <?php do_action( 'etn_after_add_to_cart_button', $single_event_id); ?>
</form>

<?php do_action( 'etn_after_add_to_cart_form', $single_event_id); ?>