<div class="etn-single-page-ticket-count-text-holder">
    <?php
        if ( !isset($event_options["etn_hide_seats_from_details"]) ) {
            ?>
            <div class="etn-form-ticket-text">
                <?php
                if( $etn_ticket_unlimited ){
                    echo esc_html__( "This event offers unlimited tickets", "eventin" );
                }else {
                    echo esc_html($etn_left_tickets) . esc_html__(' seats remaining', "eventin");
                }
                ?>
            </div>
            <?php
        } 
        if( !isset($event_options["etn_hide_attendee_count_from_details"]) ){
            ?>
            <div class="etn-form-ticket-text">
                <?php echo esc_html( $total_sold_ticket ) . esc_html__(" Attendees so far.", "eventin"); ?>
            </div>
            <?php
        }
    ?>
</div>

<?php do_action( 'etn_before_add_to_cart_form', $single_event_id); ?>

<form method="post" class="etn-event-form-parent" data-etn_uid="<?php echo esc_html($unique_id); ?>">
    <input name="event_name" type="hidden" value="<?php echo esc_html($event_title); ?>" />
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
    
    <div class="etn-row etn-item-row">
        <div class="etn-qty-field etn-col-lg-6">
            <label for="etn_product_qty">
                <?php echo esc_html__('Quantity', "eventin"); ?>
            </label>
            <input id="etn_product_qty" class="attr-form-control etn-event-form-qty etn_product_qty" name="quantity" type="number" value="<?php echo esc_attr( $etn_min_ticket ); ?>"
             min="<?php echo esc_attr( $etn_min_ticket ); ?>" max="<?php echo esc_attr( $etn_max_ticket ); ?>" data-etn_min_ticket='<?php echo esc_attr( $etn_min_ticket ); ?>'
             data-etn_max_ticket='<?php echo esc_attr( $etn_max_ticket ); ?>' 
             data-left_ticket="<?php echo esc_html($etn_left_tickets); ?>"
            />
        </div>
        <div class="etn-price-field etn-col-lg-6">
            <label for="etn_product_price">
                <?php echo isset($event_options["etn_price_label"]) && ( "" != $event_options["etn_price_label"]) ? esc_html($event_options["etn_price_label"]) : esc_html__('Price', "eventin"); ?>
            </label>
            <input id="etn_product_price" class="attr-form-control etn-event-form-price etn_product_price" readonly name="price" type="number" value="<?php echo esc_attr($etn_ticket_price); ?>" min="1" />
        </div>
    </div>

    <?php do_action( 'etn_before_add_to_cart_total_price', $single_event_id); ?>

    <div class="etn-total-price">
        <?php echo esc_html__('Total price', "eventin"); ?>
        <?php 
            if(function_exists("get_woocommerce_currency_symbol")){
                echo esc_html(get_woocommerce_currency_symbol()); 
            }
            ?>
        <span id="etn_form_price" class="etn_form_price">
            <?php echo esc_html($etn_ticket_price); ?>
        </span>
    </div>
    
    <?php do_action( 'etn_before_add_to_cart_button', $single_event_id); ?>

    <?php
    $show_form_button = apply_filters("etn_form_submit_visibility", true, $single_event_id);

    if ($show_form_button === false) {
        ?>
        <small><?php echo esc_html__('Event already expired!', "eventin"); ?></small>
        <?php
    } else {
        if (!isset($event_options["etn_purchase_login_required"]) || (isset($event_options["etn_purchase_login_required"]) && is_user_logged_in())) {
            ?>
            <input name="submit" class="etn-btn etn-primary etn-add-to-cart-block" type="submit" value="<?php $cart_button_text = apply_filters( 'etn_event_cart_button_text', esc_html__("Add to cart", "eventin") ); echo esc_html( $cart_button_text ); ?>" />
            <?php
        } else {
            ?>
            <small>
            <?php echo esc_html__('Please', 'eventin'); ?> <a href="<?php echo wp_login_url( get_permalink( ) ); ?>"><?php echo esc_html__( "Login", "eventin" ); ?></a> <?php echo esc_html__(' to buy ticket!', "eventin"); ?>
            </small>
            <?php
        }
    }
    ?>
    
    <?php do_action( 'etn_after_add_to_cart_button', $single_event_id); ?>
</form>

<?php do_action( 'etn_after_add_to_cart_form', $single_event_id); ?>