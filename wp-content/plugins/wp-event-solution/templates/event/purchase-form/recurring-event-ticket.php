<?php
use Etn\Utils\Helper;

$etn_left_tickets       = !empty( $data['etn_left_tickets'] ) ? $data['etn_left_tickets'] : 0;
$etn_ticket_unlimited   = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
$etn_ticket_price       = isset( $data['etn_ticket_price'] ) ? $data['etn_ticket_price'] : '';
$ticket_qty             = get_post_meta( $single_event_id, "etn_sold_tickets", true );
$total_sold_ticket      = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
$is_zoom_event          = get_post_meta( $single_event_id, 'etn_zoom_event', true );
$event_options          = !empty( $data['event_options'] ) ? $data['event_options'] : [];
$event_title            = get_the_title( $single_event_id );
$separate               = (!empty($data['event_end_date'])) ? ' - ' : '';
$settings               = Helper::get_settings();
$attendee_reg_enable    = !empty( $settings["attendee_registration"] ) ? true : false;
$active_class           = ($i===0) ? 'active' : '';
$active_item            = ($i===0) ? 'style=display:block' : '';
$etn_min_ticket       = !empty(get_post_meta( $single_event_id, 'etn_min_ticket', true )) ? get_post_meta( $single_event_id, 'etn_min_ticket', true ) : 1;
$etn_max_ticket       = !empty(get_post_meta( $single_event_id, 'etn_max_ticket', true )) ? get_post_meta( $single_event_id, 'etn_max_ticket', true ) : $etn_left_tickets;
$etn_max_ticket       =  min($etn_left_tickets, $etn_max_ticket);

?>

<div class="etn-widget etn-recurring-widget <?php echo esc_attr($active_class); ?>">
    <div class="etn-row">
        <div class="etn-col-lg-3">
            <div class="etn-left-datemeta">
                <div class="etn-date-meta">
                    <?php echo date_i18n('M, y', strtotime( str_replace('/', '-', $data['event_start_date'] ) )) ; ?>
                    <?php echo esc_attr($separate); ?>
                    <?php echo date_i18n('M, y', strtotime( str_replace('/', '-', $data['event_end_date'] ) )) ; ?>
                    <span>
                        <?php echo date_i18n('j', strtotime( str_replace('/', '-', $data['event_start_date'] ) )); ?>
                        <?php echo esc_attr($separate); ?>
                        <?php echo date_i18n('j', strtotime( str_replace('/', '-', $data['event_end_date'] ) )); ?>
                    </span>
                </div>
                <?php
                // show if this is a zoom event
                    if ( isset( $is_zoom_event ) && "on" == $is_zoom_event ) {
                        ?>
                        <div class="etn-zoom-event-notice">
                            <img src="<?php echo esc_url(\Wpeventin::assets_url() . "images/zoom.svg"); ?>" alt="<?php echo esc_attr__('Zoom', 'eventin') ?>">
                            <?php echo esc_html__( "Zoom Event", "eventin" ); ?>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    
        <div class="etn-col-lg-9">
            <div class="recurring-content <?php echo esc_attr($active_class);?>">
                <div class="etn-recurring-header">
                    <div class="etn-title-wrap">
                        <div class="etn-time-meta">
                            <?php
                            if ( !isset($event_options["etn_hide_time_from_details"]) ) {
                                $separate = (!empty($data['event_end_time'])) ? ' - ' : '';
                                ?>
                                <li>
                                    <i class="far fa-clock"></i>
                                    <?php echo esc_html($data['event_start_time'] . $separate . $data['event_end_time']); ?>
                                    <span class="etn-event-timezone">
                                        
                                        <?php
                                        if ( !empty( $data['event_timezone'] ) && !isset($event_options["etn_hide_timezone_from_details"]) ) {
                                            ?>
                                            (<?php echo esc_html( $data['event_timezone'] ); ?>)
                                            <?php
                                        }
                                        ?>
                                    </span>
                                </li>
                                <?php
                            }
                            ?>
                        </div>
                        <h4 class="etn-title etn-post-title etn-accordion-heading"> 
                            <a href="<?php  echo esc_url( get_permalink( $single_event_id ) ); ?>">
                                <?php  echo esc_html( $event_title  ); ?>
                            </a>
                        </h4>
                        <p>
                            <?php echo esc_html(wp_trim_words(get_the_content($single_event_id), 10, ' ')); ?>
                        </p>
                    </div>
                    <div class="etn-thumb-wrap">
                        <?php echo get_the_post_thumbnail($single_event_id); ?>
                    </div>
                    <i class="etn-arrow fa fa-angle-down"></i>
                </div>
                <div class="etn-form-wrap" <?php echo esc_attr($active_item ); ?>>
                    <?php 
                    if( $etn_left_tickets > 0 ) {  ?>
                        <form method="post" class="etn-event-form-parent">
                            <?php
                            if( $attendee_reg_enable ){
                                ?>
                                <?php  wp_nonce_field('ticket_purchase_next_step_two','ticket_purchase_next_step_two'); ?>
                                <input name="ticket_purchase_next_step" type="hidden" value="two" />
                                <input name="event_id" type="hidden" value="<?php echo intval($single_event_id); ?>" />
                                <input name="event_name" type="hidden" value="<?php echo esc_html($event_title); ?>" />
                                <?php
                            }else{
                                ?>
                                <input name="add-to-cart" type="hidden" value="<?php echo intval($single_event_id); ?>" />
                                <input name="event_name" type="hidden" value="<?php echo esc_html($event_title); ?>" />
                                <?php
                            }
                            ?>
                            <div class="etn-item-row">
                                <div class="etn-price-field">
                                    <label for="etn_product_price">
                                        <?php echo isset($event_options["etn_price_label"]) && ( "" != $event_options["etn_price_label"]) ? esc_html($event_options["etn_price_label"]) : esc_html__('Price', "eventin"); ?>
                                    </label>
                                    <input id="etn_product_price" class="attr-form-control etn-event-form-price etn_product_price" readonly name="price" type="text" value="<?php echo esc_attr($etn_ticket_price); ?>" min="1" />
                                </div>
                                <div class="etn-qty-field">
                                    <label for="etn_product_qty">
                                        <?php echo esc_html__('Quantity', "eventin"); ?>
                                    </label>
                                    <div class="etn-quantity">
                                        <input id="etn_product_qty" class="attr-form-control etn-event-form-qty etn_product_qty" name="quantity" type="number"  value="<?php echo esc_attr( $etn_min_ticket ); ?>" min="<?php echo esc_attr( $etn_min_ticket ); ?>" max="<?php echo esc_attr( $etn_max_ticket ); ?>" data-etn_min_ticket='<?php echo esc_attr( $etn_min_ticket ); ?>' data-etn_max_ticket='<?php echo esc_attr( $etn_max_ticket ); ?>' data-left_ticket="<?php echo esc_html($etn_left_tickets); ?>" data-invalid_qty_text="<?php echo esc_html__("Invalid Qty", "eventin");?>" />
                                    </div>
                                </div>
                            
                                <div class="etn-total-price">
                                    <label>
                                        <?php echo esc_html__('Total price', "eventin"); ?>
                                    </label>
                                    <div class="etn-t-price">
                                        <?php 
                                            if(function_exists("get_woocommerce_currency_symbol")){
                                                echo esc_html(get_woocommerce_currency_symbol()); 
                                            }
                                        ?>
                                        <span id="etn_form_price" class="etn_form_price">
                                            <?php echo esc_html($etn_ticket_price); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="etn-add-to-cart-btn">
                                <?php do_action( 'etn_before_add_to_cart_button'); ?>

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
                                    <?php do_action( 'etn_after_add_to_cart_button'); ?>
                                </div>
                            </div>
                        </form>
                        <!-- tikcet holder -->
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
                                    <?php echo esc_html( $total_sold_ticket ) . esc_html__(" attendees so far.", "eventin"); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <h6><?php echo esc_html__( 'No Tickets Available!!', "eventin" ); ?></h6>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

