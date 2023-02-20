<?php

namespace Etn\Core\Woocommerce;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    public $action;
    public $base;

    public function Init() {

        // Handle actions on order status change
        add_action('woocommerce_order_status_changed', [$this, 'update_event_stock_on_order_status_update' ], 10, 3);
        add_action('woocommerce_order_status_changed', [$this, 'change_attendee_payment_status_on_order_status_update' ], 10, 3);
        add_action('woocommerce_order_status_changed', [$this, 'change_purchase_report_status_on_order_status_update' ], 10, 3);
        add_action('woocommerce_order_status_changed', [$this, 'email_zoom_event_details_on_order_status_update' ], 10, 3);
        add_action('woocommerce_order_status_changed', [$this, 'email_attendee_ticket_details_on_order_status_update' ], 10, 3);

        add_filter('woocommerce_hidden_order_itemmeta', [$this, 'hide_order_itemmeta_on_order_status_update'], 10, 1);

        // ====================== Attendee registration related hooks for woocommerce start ======================== //
        {
            // insert attendee data into database before add-to-cart
            if( (class_exists('Wpeventin_Pro') && \Wpeventin_Pro::version() !== '3.2.0' ) || !class_exists('Wpeventin_Pro') ) {
				add_action( 'woocommerce_add_to_cart', [$this, 'insert_attendee_before_add_to_cart'], 0 );
            } 
            // insert attendee data into cart item object
            add_filter( 'woocommerce_add_cart_item_data', [$this, 'add_cart_item_data'], 10, 3 );
            // Hide order item meta data (in thank you  and order page)
            add_filter( 'woocommerce_order_item_get_formatted_meta_data', [$this, 'hide_order_itemmeta'], 10, 2 );
            // save cart item data while checkout
            add_action( 'woocommerce_checkout_create_order_line_item', [$this, 'save_cart_item_data'], 10, 4 );
        }
        
        // ===================== Attendee registration related hooks for woocommerce end ========================== //


        // in cart page, compare cart item with stock
        add_action( 'woocommerce_before_cart', [ $this, 'before_cart_check_stock' ] );
        // before checkout, compare cart item with stock
        add_action( 'woocommerce_before_checkout_form', [ $this, 'before_checkout_check_stock' ], 9 );
        // before place order, compare cart item with stock
        add_action( 'woocommerce_after_checkout_validation', [ $this, 'before_submit_order_check_stock' ], 10, 2 );

        // before adding to cart, compare cart item with stock
        add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'validate_add_to_cart_item' ], 10, 5 );


        // add attendee id to order id at the time order is created for paypal payment
        add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'process_all_once_order_created' ], 10, 1 );

        // custom metabox for showing attendee list on woocommerce order page
        add_action( 'add_meta_boxes', [ $this, 'etn_order_page_metabox_init' ] );

        // add custom css to woocommerce order page table
        add_action('admin_head', [ $this, 'etn_order_page_table_css' ]);


        // ========================== Register and hook Eventin events as Woo product ================================ //
        add_filter( 'woocommerce_add_to_cart_redirect', [$this, 'etn_redirect_checkout_add_cart'] );

        // add_filter( 'woocommerce_product_get_price', [$this, 'etn_woocommerce_product_get_price'], 10, 2 );
        
        add_filter( 'woocommerce_cart_item_quantity', [$this, 'wc_cart_item_quantity'], 10, 3 );

        add_filter( 'woocommerce_cart_item_name', [$this, 'etn_cart_item_name'], 10, 3 );

        add_filter( 'woocommerce_cart_item_price', [$this, '_cart_item_price'], 10, 3 );

        add_action( 'woocommerce_thankyou', [$this, 'etn_checkout_callback'], 10, 1 );

        // Event Multi pricing

        // This hooks are responsible for ticket variations activities
        add_filter( 'woocommerce_add_cart_item_data',[ $this, 'etn_add_cart_item_data' ], 10, 2 );

        /**
         * Change sub-total price
         * For multi-variation ticket
         */
        add_action('woocommerce_before_calculate_totals', [ $this, 'etn_variation_ticket_total_price' ] , 90, 1);

        if ( class_exists( 'WC_Deposits' ) ) {
            add_action( 'woocommerce_cart_loaded_from_session', [ $this, 'etn_variation_ticket_total_price' ], 98, 1 );
        }

        /**
         * Set price from variation
         * For multi-variation ticket
         */
        add_filter('woocommerce_cart_item_price', [ $this, 'etn_total_variation_price' ] , 100, 3);
        add_action( 'woocommerce_order_item_meta_start', [$this, 'show_event_ticket_variation_details'], 10, 3 ); 
        add_action( 'woocommerce_after_order_itemmeta', [ $this, 'show_event_ticket_variation_details' ], 10, 3 );   

        /**
         * Coupon hooks
         * apply woocommerce coupon in Eventin
         * add select2 script in only coupon page for showing events in admin_footer
         */
        add_action( 'woocommerce_coupon_options_usage_restriction', [ $this, 'etn_woo_coupon_markup' ], 10);
        add_action( 'woocommerce_coupon_options_save', [ $this, 'etn_woo_coupon_save_options' ], 10, 2);
        add_action( 'admin_footer', [ $this, 'etn_woo_coupon_script' ], 10);
        add_filter( 'woocommerce_coupon_is_valid', [ $this, 'etn_woo_cart_coupon_validation' ], 10, 2 );
        add_action('woocommerce_checkout_order_processed', [ $this, 'etn_woo_cart_order_processed'], 10, 1);

    }

    /**
     * Include Additional Ticket Variation Data
     * 
     * @since 2.6.1
     *
     * @param [type] $item_id
     * @param [type] $item
     * @param [type] $order
     * @return void
     */
    public function show_event_ticket_variation_details($item_id,  $item,  $order){

        $product_name     = $item->get_name();
        $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
        $variation_details= !is_null( $item->get_meta( 'etn_ticket_variations', true ) ) ? $item->get_meta( 'etn_ticket_variations', true ) : [];
        if( !empty( $variation_details ) ){
            ?>
            <div class="etn-invoice-email-event-meta">
                <?php  echo esc_html__('Ticket Details', 'eventin'); ?>
            </div>
            <?php
            foreach( $variation_details as $single_variation ){
                ?>
                <div class="etn-invoice-email-event-meta">
                    <?php 
                    echo esc_html( $single_variation['etn_ticket_name'] . '*' . $single_variation['etn_ticket_qty'] );
                    ?>
                </div>
                <?php
            }
        }
    }

    /**
     * Adding cart meta
     */
    public function etn_add_cart_item_data( $cart_item_data, $product_id ) {

        if ( isset( $_POST ) && !empty( $product_id ) ) {

            $post_data  = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
            
            if ( !empty($post_data['ticket_name']) && !empty($post_data['ticket_quantity']) ) {
                $etn_ticket_variations = [];
                $total_price = 0.00;
                $total_qty   = 0;
                foreach ($post_data['ticket_quantity'] as $key => $value) {
                    if ( $value !=="0" ) {
                        $etn_ticket_variations[$key]['etn_ticket_slug'] = $post_data['ticket_slug'][$key];
                        $etn_ticket_variations[$key]['etn_ticket_name'] = $post_data['ticket_name'][$key];
                        $etn_ticket_variations[$key]['etn_ticket_qty']  = $value;
                        $total_price  += (int) $value * floatval( $post_data['ticket_price'][$key] );
                        $total_qty    += (int) $value;
                    }
                }

                $cart_item_data['etn_ticket_variations']         =  $etn_ticket_variations;
                $cart_item_data['_etn_variation_total_price']    =  $total_price ;
                $cart_item_data['_etn_variation_total_quantity'] =  $total_qty;

                $unique_cart_item_key = md5( microtime().rand() );
                $cart_item_data['unique_key'] = $unique_cart_item_key;
            }
        } 

        return $cart_item_data;
    }

    /**
     * Change price for cart item
     */
    public function etn_variation_ticket_total_price($cart_object){
        foreach ($cart_object->cart_contents as $key => $value) {
            if ( !empty($value['event_id']) &&  get_post_type($value['event_id']) == 'etn') {

                $event_total_price = !empty( $value['_etn_variation_total_price'] ) ? $value['_etn_variation_total_price'] : 0;
                
                $value['data']->set_price($event_total_price);
                $value['data']->set_regular_price($event_total_price);
                $value['data']->set_sale_price($event_total_price);
                $value['data']->set_sold_individually('yes');
                $value['data']->get_price();
            }
        }
    }

    /**
     * Set price for cart item
     */
    public function etn_total_variation_price($price, $cart_item , $cart_item_key) {

        if (!empty( $cart_item['_etn_variation_total_price'] ) ) {
            $price = wc_price($cart_item['_etn_variation_total_price']);
        }

        return $price;
    }

    /**
     * after successful checkout, some data are returned from woocommerce
     * we can use these data to update our own data storage / tables
     */
    public function etn_checkout_callback( $order_id ) {
        
        if ( !$order_id ) {
            return;
        }

        global $wpdb;

        $order = wc_get_order( $order_id );
        
        if ( $order->is_paid() ) {
            $paid = 'Paid';
        } else {
            $paid = 'Unpaid';
        }
        ?>
        <div class="etn-thankyou-page-order-details">
            <?php echo esc_html__( "Order ID: ", "eventin" ) . esc_html( $order_id ); ?> | <?php echo esc_html__("Order Status: ", "eventin") . esc_html( wc_get_order_status_name( $order->get_status() )); ?> | <?php echo esc_html__( "Order is Payment Status: ", "eventin" ) . esc_html( $paid ); ?>
        </div>
        <?php

        //checking for zoom event
        $this->show_zoom_events_details( $order );

        do_action("eventin/after_thankyou");

    }


    /**
     * check if any zoom meeting exists in order
     */
    public function show_zoom_events_details( $order ) {

        foreach ( $order->get_items() as $item_id => $item ) {
            // Get the product name
            $product_name     = $item->get_name();
            $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

            if( !empty( $event_id ) ){
                $product_post = get_post( $event_id );
            } else{
                $product_post = get_page_by_title( $product_name, OBJECT, 'etn' );
            }

            if ( !empty( $product_post ) ) {
                $post_id        = $product_post->ID;
                $is_zoom_event  = Helper::check_if_zoom_event($post_id);

                if( $is_zoom_event ){
                    ?>
                    <div class="etn-thankyou-page-order-details">
                    <?php echo esc_html__('NB. This order includes Events which will be hosted on Zoom. After successful payment, Zoom details will be sent through email', 'eventin');?>
                    </div>
                    <?php
                    break;
                }
            }
        }
    }


    /**
     * Return product quantity
     */
    public function wc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {

        // deactivate product quantity
        if ( is_cart() ) {
            if ( get_post_type( $cart_item['product_id'] ) == 'etn' ) {

                $product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity'] );
            }

        }

        return $product_quantity;
    }

    /**        
     * returns the price of the custom product
     * product is the custom post we are creating
     */
    public function etn_woocommerce_product_get_price( $price, $product ) {

        $product_id = $product->get_id();

        if ( get_post_type( $product_id ) == 'etn' ) {
            $price = get_post_meta( $product_id, 'etn_ticket_price', true );
            $price = isset( $price ) ? ( floatval( $price ) ) : 0;
        }
    
        return $price;
    }
    
    /**
     * @snippet Redirect to Checkout Upon Add to Cart - WooCommerce
     */
    public function etn_redirect_checkout_add_cart() {
        $add_to_cart_redirect       = empty( \Etn\Utils\Helper::get_option( 'add_to_cart_redirect' ) ) ? 'event' : \Etn\Utils\Helper::get_option( 'add_to_cart_redirect' );
        if( 'cart' == $add_to_cart_redirect ){
            return wc_get_cart_url();
        } elseif('checkout' == $add_to_cart_redirect){
            return wc_get_checkout_url();
        }
    }


    /**
     * Update Event Stock On Order Status Change
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    public function update_event_stock_on_order_status_update( $order_id, $old_order_status, $new_order_status ){
        
        $parent_id = wp_get_post_parent_id( $order_id );
        if ( ! empty( $parent_id ) ) {
            return;
        }

        $decrease_states = [
            'processing',
            'on-hold',
            'completed',
            'partial-payment',
        ];

        $increase_state = [
            'pending',
            'cancelled',
            'scheduled-payment',
            'pending-deposit',
        ];

        $no_action_state = [
            'refunded',
            'failed',
        ];

        global $wpdb;
        $order = wc_get_order( $order_id );

        foreach ( $order->get_items() as $item_id => $item ) {
            $product_name   = $item->get_name();
            $event_id       = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
            $event_object   = !empty( $event_id ) ? get_post( $event_id ) : get_page_by_title( $product_name, OBJECT, 'etn' );

            if ( !empty( $event_object ) ) {
                $event_id           = $event_object->ID;
                $ticket_variations  = !empty( get_post_meta( $event_id, "etn_ticket_variations", true ) ) ? get_post_meta( $event_id, "etn_ticket_variations", true ) : [];

                $item_variations                = !empty( $item->get_meta( "etn_ticket_variations", true ) ) ? $item->get_meta( "etn_ticket_variations", true ) : [];
                $variation_picked_total_qty     = !empty( $item->get_meta( "_etn_variation_total_quantity", true ) ) ? absint( $item->get_meta( "_etn_variation_total_quantity", true ) ) : 0;

                if ( !empty( $item_variations ) ) {
                    $decrease_time = $increase_time = false; 
                    $do_decrease   = $do_increase = false; // after calculation, will check whether it is possible to decrease

                    $product_quantity   = absint( $item->get_quantity() );
                    $decreased_stock    = wc_get_order_item_meta( $item_id, '_etn_decreased_stock', true );
                    $increased_stock    = wc_get_order_item_meta( $item_id, '_etn_increased_stock', true );

                    if ( $decreased_stock != $product_quantity ) {
                        if ( in_array( $new_order_status, $decrease_states ) && !in_array( $old_order_status, $decrease_states ) ) {
                            $decrease_time  = true; // decrease event stock
                        }
                    }
        
                    if ( $increased_stock != $product_quantity ) {
                        if ( in_array( $new_order_status, $increase_state ) && !in_array( $old_order_status, $increase_state ) ) {
                            $increase_time  = true; // increase event stock
                        }
                    }

                    if ( $decrease_time || $increase_time ) {
                        foreach ( $item_variations as $item_index => $item_variation ) {
                            $ticket_index = $this->search_array_by_value( $ticket_variations, $item_variation['etn_ticket_slug'] );
                            
                            if ( isset( $ticket_variations[ $ticket_index ] ) ) {
                                $variation_picked_qty   = absint( $item_variation[ 'etn_ticket_qty' ] );
                                $etn_sold_tickets       = absint( $ticket_variations[ $ticket_index ]['etn_sold_tickets'] );
                                $total_tickets          = absint( $ticket_variations[ $ticket_index ]['etn_avaiilable_tickets'] );

                                if ( $decrease_time ) {
                                    $updated_sold_tickets   = $etn_sold_tickets + $variation_picked_qty;
                                }
                                if ( $increase_time ) {
                                    $updated_sold_tickets   = $etn_sold_tickets - $variation_picked_qty;
                                }
                                
                                if ( $updated_sold_tickets <= $total_tickets && $updated_sold_tickets >= 0 ) {
                                    if ( $decrease_time ) {
                                        $do_decrease = true;
                                    }
                                    if ( $increase_time ) {
                                        $do_increase = true;
                                    }

                                    $ticket_variations[ $ticket_index ]['etn_sold_tickets'] = $updated_sold_tickets;
                                }
                            }
                        }
                    }

                    if ( $do_decrease || $do_increase ) {
                        $etn_total_sold_tickets = absint( get_post_meta( $event_id, "etn_total_sold_tickets", true ) );

                        if ( $do_decrease ) {
                            $updated_total_sold_tickets = $etn_total_sold_tickets + $variation_picked_total_qty;
                            $delete_meta_key    = '_etn_increased_stock';
                            $add_meta_key       = '_etn_decreased_stock';
                        }

                        if ( $do_increase ) {
                            $updated_total_sold_tickets = $etn_total_sold_tickets - $variation_picked_total_qty;
                            $delete_meta_key    = '_etn_decreased_stock';
                            $add_meta_key       = '_etn_increased_stock';
                        }

                        update_post_meta( $event_id, "etn_ticket_variations", $ticket_variations );
                        update_post_meta( $event_id, "etn_total_sold_tickets", $updated_total_sold_tickets );

                        wc_delete_order_item_meta( $item_id, $delete_meta_key );
                        wc_add_order_item_meta( $item_id, $add_meta_key, $product_quantity, true );
                    }
                }
            }

        }

        return;
    }


    /**
     * Send Zoom Event Details On Status CHange
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    public function email_zoom_event_details_on_order_status_update(  $order_id, $old_order_status, $new_order_status ) {
        $parent_id = wp_get_post_parent_id( $order_id );
        if ( ! empty( $parent_id ) ) {
            return;
        }

        $payment_success_status_array = [
            // 'pending', 'on-hold', 'cancelled','refunded', 'failed',
            'processing',
            'completed',
            'partial-payment',
        ];

        $zoom_email_sent = Helper::check_if_zoom_email_sent_for_order( $order_id );

        if( !$zoom_email_sent && in_array($new_order_status, $payment_success_status_array)){

            //email not sent yet and order order status is paid, so proceed..
            $order = wc_get_order( $order_id );
            Helper::send_email_with_zoom_meeting_details( $order_id, $order );
        }
    }

    /**
     * Send Attendee Ticket Details On Status CHange
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    public function email_attendee_ticket_details_on_order_status_update(  $order_id, $old_order_status, $new_order_status ) {
        $parent_id = wp_get_post_parent_id( $order_id );
        if ( ! empty( $parent_id ) ) {
            return;
        }

        $payment_success_status_array = [
            // 'pending', 'on-hold', 'cancelled','refunded', 'failed',
            'processing',
            'completed',
            'partial-payment',
        ];
 
        // Allow code execution only once
        if ( !get_post_meta( $order_id, 'etn_attendee_ticket_email_sent_on_order_placement', true )  && in_array($new_order_status, $payment_success_status_array)){ 

            // call function to send email
            Helper::send_attendee_ticket_email_on_order_status_change( $order_id );


            update_post_meta( $order_id, 'etn_attendee_ticket_email_sent_on_order_placement', true );
        }
    }

    /**
     * Change attendee payment status
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    public function change_attendee_payment_status_on_order_status_update(  $order_id, $old_order_status, $new_order_status ) {
        $parent_id = wp_get_post_parent_id( $order_id );
        if ( ! empty( $parent_id ) ) {
            return;
        }

        $order_attendees = Helper::get_attendee_by_woo_order( $order_id );
        if( is_array( $order_attendees ) && !empty( $order_attendees )){
            foreach($order_attendees as $attendee_id){
                Helper::update_attendee_payment_status($attendee_id, $new_order_status);
            }
        }
    }

    /**
     * Change Purchase Report Status On Order Status Change
     * 
     * @since 2.4.1
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    public function change_purchase_report_status_on_order_status_update( $order_id, $old_order_status, $new_order_status ) {
        $parent_id = wp_get_post_parent_id( $order_id );
        if ( ! empty( $parent_id ) ) {
            return;
        }

        $order_status_array = [
            'pending'               => "Pending",
            'processing'            => "Processing",
            'on-hold'               => "Hold",
            'completed'             => "Completed",
            'cancelled'             => "Cancelled",
            'refunded'              => "Refunded",
            'failed'                => "Failed",
            'partial-payment'       => "Completed", // "Partially Paid"
            'scheduled-payment'     => "Pending", // "Scheduled"
            'pending-deposit'       => "Pending", // "Pending Deposit Payment"
        ];

        global $wpdb;
        $order = wc_get_order( $order_id );
        foreach ( $order->get_items() as $item_id => $item ) {

            $product_name     = $item->get_name(); // Get the event name
            $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

            if( !empty( $event_id ) ){
                $event_object = get_post( $event_id );
            } else{
                $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
            }

            if ( !empty( $event_object ) ) {
                //this item is an event, proceed...

                //update purchase history status
                $event_id    = $event_object->ID;
                $status      = $order_status_array[$new_order_status];
                $table_name  = ETN_EVENT_PURCHASE_HISTORY_TABLE;
                $order_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE post_id = '$event_id' AND form_id = '$order_id'" );
                if ( $order_count > 0 ) {
                    $wpdb->query("UPDATE $table_name SET status = '$status' WHERE post_id = '$event_id' AND form_id = '$order_id'");
                }

            }
        }

        return;

    }

    /**
     * Display custom cart item meta data (in cart and checkout)
     */
    public function hide_order_itemmeta( $formatted_meta, $item ) {

        foreach ( $formatted_meta as $key => $meta ) {

            if ( isset( $meta->key ) && 'etn_status_update_key' == $meta->key ) {
                unset( $formatted_meta[$key] );
            }

            if ( isset( $meta->key ) && 'event_id' == $meta->key ) {
                unset( $formatted_meta[$key] );
            }

        }

        return $formatted_meta;
    }


    /**
     * Get event price
     */
    public function _cart_item_price( $price, $cart_item, $cart_item_key ) {
        return $price;
    }

    /**
     * Get event name
     */
    public function etn_cart_item_name( $product_title, $cart_item, $cart_item_key ) {

        $product = $cart_item['data'];
        $content_post = get_post( $product->get_id() );

        if ( get_post_type( $content_post ) == "etn" && ( is_cart() || is_checkout() ) && !empty( $content_post ) ) {
            $product_title = $product->get_title();

            if ( ( $cart_item['product_id'] != $cart_item['event_id'] ) && class_exists( 'SitePress' ) ) {
                $product_title = get_the_title( $cart_item['event_id'] );
            }

            $product_permalink = $product->is_visible() ? $product->get_permalink( $cart_item ) : '';

            if ( !$product_permalink ) {
                $new_product_title = $product_title . '&nbsp;';
            } else {
                $parent_id = wp_get_post_parent_id( $content_post );

                if ( !empty( $parent_id ) ) { // recur event
                    $product_permalink = get_permalink( $parent_id );
                } 

                if ( isset( $cart_item['specific_lang'] ) ) { // for wpml
                    $product_permalink .= '?lang=' . $cart_item['specific_lang'];
                }

                $new_product_title = sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $product_title );

                if ( !empty( $parent_id ) ) { // recur event
                    // $new_product_title = "<div class='etn_recur_hide_child'>" . $new_product_title . "<div>";
                }
            }

            $product_title = $new_product_title;

            if ( !empty( $cart_item['etn_ticket_variations'] ) && count($cart_item['etn_ticket_variations'])>0 ) {
                $variations = '<br>' . esc_html__('Ticket Details', 'eventin');
                foreach ( $cart_item['etn_ticket_variations'] as $key => $value) {
                    $variations .= "<div class=''>". $value['etn_ticket_name'] . "*" . $value['etn_ticket_qty']."<div>";
                }

                $product_title = $product_title . $variations ;
            }
        }

        return Helper::kses( $product_title );
    }

    /**
     * Post attendee data
     */
    public function insert_attendee_before_add_to_cart() {
        
        if ( isset( $_POST['ticket_purchase_next_step'] ) && $_POST['ticket_purchase_next_step'] === "three" ) {

            $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

            $check    = wp_verify_nonce( $post_arr['ticket_purchase_next_step_three'], 'ticket_purchase_next_step_three' );            

            if ( $check && !empty( $post_arr['attendee_info_update_key'] )
                && !empty( $post_arr["add-to-cart"] ) && !empty( $post_arr["quantity"] )
                && !empty( $post_arr["attendee_name"] ) ) {
                $access_token   = $post_arr['attendee_info_update_key'];
                $event_id       = $post_arr["add-to-cart"];
                $payment_token  = md5( 'etn-payment-token' . $access_token . time() . rand( 1, 9999 ) );
                $ticket_price   = get_post_meta( $event_id, "etn_ticket_price", true );

                // Variation Data

                // total variations
                $total_attendee = isset( $post_arr["variation_picked_total_qty"] ) ? $post_arr["variation_picked_total_qty"] : $post_arr["quantity"];

                // check if there's any attendee extra field set from Plugin Settings
                $settings              = Helper::get_settings();
                $attendee_extra_fields = isset($settings['attendee_extra_fields']) ? $settings['attendee_extra_fields'] : [];

                $extra_field_array = [];
                if( is_array( $attendee_extra_fields ) && !empty( $attendee_extra_fields )){

                    foreach( $attendee_extra_fields as $attendee_extra_field ){
                        $label_content = $attendee_extra_field['label'];

                        if( $label_content != '' ){
                            $name_from_label['label'] = $label_content;
                            $name_from_label['type']  = $attendee_extra_field['type'];
                            $name_from_label['name']  = Helper::generate_name_from_label("etn_attendee_extra_field_", $label_content);
                            array_push( $extra_field_array, $name_from_label );
                        }
                    }
                }

                $special_types = [
                    'radio',
                    'checkbox',
                ];
                
                // insert attendee custom post
                for ( $i = 0; $i < $total_attendee; $i++ ) {
                    $attendee_name  = !empty( $post_arr["attendee_name"][$i] ) ? $post_arr["attendee_name"][$i] : "";
                    $attendee_email = !empty( $post_arr["attendee_email"][$i] ) ? $post_arr["attendee_email"][$i] : "";
                    $attendee_phone = !empty( $post_arr["attendee_phone"][$i] ) ? $post_arr["attendee_phone"][$i] : "";

                    $post_id = wp_insert_post( [
                        'post_title'  => $attendee_name,
                        'post_type'   => 'etn-attendee',
                        'post_status' => 'publish',
                    ] );

                    if ( $post_id ) {
                        $info_edit_token = md5( 'etn-edit-token' . $post_id . $access_token . time() );
                        $ticket_index = $post_arr['ticket_index'][$i];
                        $data            = [
                            // passing variation start
                            'ticket_name'                   => $post_arr["ticket_name"][$ticket_index],
                            'ticket_slug'                   => $post_arr["ticket_slug"][$ticket_index],
                            'etn_ticket_price'              => (float) $post_arr["ticket_price"][$ticket_index],
                            // passing variation end

                            'etn_status_update_token'       => $access_token,
                            'etn_payment_token'             => $payment_token,
                            'etn_info_edit_token'           => $info_edit_token,
                            'etn_timestamp'                 => time(),
                            'etn_name'                      => $attendee_name,
                            'etn_email'                     => $attendee_email,
                            'etn_phone'                     => $attendee_phone,
                            'etn_status'                    => 'failed',
                            'etn_attendeee_ticket_status'   => 'unused',
                            'etn_event_id'                  => intval( $event_id ),
                            'etn_unique_ticket_id'          => Helper::generate_unique_ticket_id_from_attendee_id($post_id),
                        ];

                        // check and insert attendee extra field data from attendee form
                        if( is_array( $extra_field_array ) && !empty( $extra_field_array ) ){
                            foreach( $extra_field_array as $key => $value ){
                                $post_content   = '';
                                $field_name     = $value['name'];

                                if ( !in_array( $value['type'], $special_types ) ) {
                                    $post_content = $post_arr[$field_name][$i];
                                } else {
                                    if ( $value['type'] == 'checkbox') { // for checkbox
                                        $checkbox_index_now = $post_arr['checkbox_track_index'][$i];

                                        $checkbox_field_name = $field_name . '_' . $checkbox_index_now;
                                        if ( !empty( $post_arr[$checkbox_field_name] ) ) {
                                            $post_content = maybe_serialize( $post_arr[$checkbox_field_name] );
                                        }
                                    } else { // for radio
                                        $radio_index_now = $post_arr['radio_track_index'][$i];

                                        $radio_field_name = $field_name . '_' . $radio_index_now;
                                        if ( !empty( $post_arr[$radio_field_name] ) ) {
                                            $post_content    = $post_arr[$radio_field_name][0];
                                        }
                                    }
                                }

                                $data[$field_name] = $post_content;
                            }
                        }

                        foreach ( $data as $key => $value ) {
                            // insert post meta data of attendee
                            update_post_meta( $post_id, $key, $value );
                        }

                        // Write post content (triggers save_post).
                        wp_update_post( ['ID' => $post_id] );
                    }

                }

                unset( $_POST['ticket_purchase_next_step'] );
            } else {
                wp_redirect( get_permalink() );
            }

        }

    }

    /**
     * get attendee info update token
     *
     */
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ){

        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

        if( isset( $post_arr['add-to-cart'] ) ) {
            $event_id = intval( $post_arr['add-to-cart'] );

            if( get_post_type( $event_id ) == 'etn' ){
                $cart_item_data['event_id'] = $event_id;
                if ( isset ( $post_arr['specific_lang'] ) && ! empty( $post_arr['specific_lang'] ) ) {
                    $cart_item_data['specific_lang'] = $post_arr['specific_lang'];
                }
            }
        }
   
        if ( isset( $post_arr['attendee_info_update_key'] ) ) {
            $cart_item_data['etn_status_update_key'] = $post_arr['attendee_info_update_key'];
        }

        return $cart_item_data;
    }

     /**
     * Hide item specific meta so that they won't show in order update page
     *
     * @param [type] $item_hidden_metas
     * @return array
     */
    function hide_order_itemmeta_on_order_status_update( $item_hidden_metas ){
        $etn_extra_metas = [
            '_etn', 
            '_etn_increased_stock', '_etn_decreased_stock', 
            '_lang_event_id', 
            '_etn_variation_total_price', '_etn_variation_total_quantity'
        ];

        $item_hidden_metas = array_merge( $item_hidden_metas, $etn_extra_metas );

        return $item_hidden_metas;
    }

    /**
     * Get ticket info by slug
     */
    public function search_array_by_value($meta_data,$slug){

        $result_key = null;
        if ( count( $meta_data )> 0 ) {
            foreach ($meta_data as $key => $value) {
                if ( $value['etn_ticket_slug'] == $slug ) {
                    $result_key = $key;
                }
            }
        }
        
        return $result_key;
    }

    /**
     * validate cart quantity with stock so that user unable to add more item than stock quantity
     *
     * @param [bool] $passed
     * @param [int] $product_id
     * @param [int] $qty
     * @param string $variation_id
     * @param string $variations
     * @return void
     */
    public function validate_add_to_cart_item( $passed, $product_id, $qty, $variation_id = '', $variations= '' ) {

        if( !empty( $product_id ) && get_post_type( $product_id ) == 'etn' ) {
            $error_messages = $ticket_qty_errors = $cart_picked_data = [];

            $event_id   = $product_id;
            $event_name = get_the_title( $event_id );
            $ticket_variations = !empty( get_post_meta( $event_id, "etn_ticket_variations", true ) ) ? get_post_meta( $event_id, "etn_ticket_variations", true ) : [];
            
            if ( !WC()->cart->is_empty() ) {
                $cart_contents = WC()->cart->get_cart();

                foreach ( $cart_contents as $cart_item_key => $cart_data ) {
                    if( isset( $cart_data['etn_ticket_variations'] ) ) {
                        $picked_ticket_variations = $cart_data['etn_ticket_variations'];

                        foreach ( $picked_ticket_variations as $picked_index => $picked_ticket_variation ) {
                            $variation_picked_qty   = absint( $picked_ticket_variation['etn_ticket_qty'] );
                            $variation_picked_slug  = $picked_ticket_variation['etn_ticket_slug'];
                            
                            if ( !isset( $cart_picked_data[ $event_id ][ $variation_picked_slug ]['variation_total_picked_qty'] ) ) {
                                $cart_picked_data[ $event_id ][ $variation_picked_slug ]['variation_total_picked_qty'] = $variation_picked_qty;
                            } else {
                                $cart_picked_data[ $event_id ][ $variation_picked_slug ]['variation_total_picked_qty'] += $variation_picked_qty;
                            }
                        }
                    }
                } 
            }
        
            $post_contents = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
            if ( isset( $post_contents['ticket_quantity'] ) ) {
                $ticket_quantities = $post_contents['ticket_quantity'];

                foreach ( $ticket_quantities as $quantity_index => $variation_picked_qty ) {
                    if ( !empty( $variation_picked_qty ) && isset( $post_contents['ticket_slug'][ $quantity_index ] ) ) {
                        $ticket_index = $this->search_array_by_value( $ticket_variations, $post_contents['ticket_slug'][ $quantity_index ] );

                        if ( isset( $ticket_variations[ $ticket_index ] ) ) {
                            $error_cat = [];

                            $total_tickets      = absint( $ticket_variations[ $ticket_index ]['etn_avaiilable_tickets'] );
                            $etn_sold_tickets   = absint( $ticket_variations[ $ticket_index ]['etn_sold_tickets'] );
                            $remaining_ticket   = $total_tickets - $etn_sold_tickets;

                            $etn_min_ticket     = absint( $ticket_variations[ $ticket_index ]['etn_min_ticket'] );
                            $etn_max_ticket     = absint( $ticket_variations[ $ticket_index ]['etn_max_ticket'] );
                            $etn_max_ticket     = min( $remaining_ticket, $etn_max_ticket );

                            if ( !empty( $etn_min_ticket ) && $variation_picked_qty < $etn_min_ticket ) {
                                $error_cat['min_allowed'] = $etn_min_ticket;
                            }
                
                            if ( !empty( $etn_max_ticket ) && $variation_picked_qty > $etn_max_ticket ) {
                                $error_cat['max_allowed'] = $etn_max_ticket;
                            }
                
                            if ( ( $etn_sold_tickets + $variation_picked_qty ) > $total_tickets ) {
                                $error_cat['remaining_allowed'] = ( $total_tickets - $etn_sold_tickets );
                            }

                            $ticket_slug = $ticket_variations[ $ticket_index ]['etn_ticket_slug'];
                            if ( !WC()->cart->is_empty() && isset( $cart_picked_data[ $event_id ][ $ticket_slug ] ) ) {
                                $cart_picked_qty = $cart_picked_data[ $event_id ][ $ticket_slug ]['variation_total_picked_qty'];
                                $attempted_qty   = $cart_picked_qty + $variation_picked_qty;
                                
                                if ( ( $etn_sold_tickets + $attempted_qty ) > $total_tickets ) {
                                    $error_cat['total_picked_qty']        = $attempted_qty;
                                    $error_cat['total_remaining_allowed'] = ( $total_tickets - $etn_sold_tickets );
                                }
                            }

                            if ( !empty( $error_cat ) ) {
                                $error_cat['item_picked_qty'] = $variation_picked_qty;
                                $error_cat['event_name']      = $event_name;
                
                                $etn_ticket_name = $ticket_variations[ $ticket_index ]['etn_ticket_name'];
                                $ticket_qty_errors[ $event_id ][ $etn_ticket_name ] = $error_cat;
                            }
                        }

                    }
                }
            }

            if ( !empty( $ticket_qty_errors ) ) {
                foreach ( $ticket_qty_errors as $event_id => $ticket_cart_info ) {
                    foreach ( $ticket_cart_info as $ticket_name => $error_info ) {
                        $event_name         = $error_info['event_name'];
                        $quoted_ticket_name = ' "' . $ticket_name . '" ';
                        $quoted_event_name  = ' "' . $event_name . '"';

                        if ( isset( $error_info['min_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'minimum purchasable quantity is ', 'eventin' ) . $error_info['min_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }
                        if ( isset( $error_info['max_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'maximum purchasable quantity is ', 'eventin' ) . $error_info['max_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }
                        if ( isset( $error_info['remaining_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'available quantity is ', 'eventin' ) . $error_info['remaining_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }

                        if ( isset( $error_info['total_picked_qty'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'available quantity is ', 'eventin' ) . $error_info['total_remaining_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['total_picked_qty'] . esc_html__( ' ticket(s) through adding multiple times to the cart', 'eventin' ) ;
                        }
                    }
                }
            }

            $cart_item_quantities = WC()->cart->get_cart_item_quantities(); 
            if ( is_array( $cart_item_quantities ) && !empty( $cart_item_quantities ) ) {
                if ( array_key_exists( $product_id, $cart_item_quantities ) ) {
                    $already_qty        = absint( $cart_item_quantities[ $product_id ] );
                    $total_sold_ticket  = !empty( get_post_meta( $product_id, "etn_total_sold_tickets", true ) ) ? absint( get_post_meta( $product_id, "etn_total_sold_tickets", true ) ) : 0;
                    $available_ticket   = !empty( get_post_meta( $product_id, "etn_total_avaiilable_tickets", true ) ) ? absint( get_post_meta( $product_id, "etn_total_avaiilable_tickets", true ) ) : 0;
                    $remaining_ticket   = $available_ticket - $total_sold_ticket;

                    $attempted_cart_qty = $already_qty + $qty;
                    if ( $attempted_cart_qty > $remaining_ticket ) {
                        // $error_msg          = 'cart add time';
                        $error_msg          = esc_html__( 'You cannot add that amount to the cart — maximum purchasable quantity is ', 'eventin' ) . $remaining_ticket . esc_html__( '. You already have ', 'eventin' ) . $already_qty . esc_html__( ' in your cart.', 'eventin' );
                        $error_messages[]   = $error_msg;
                    }           
                }
            }
            
            if ( !empty( $error_messages ) ) {
                $passed = false;
                wc_clear_notices();

                $final_error_msg = implode( '<br>', $error_messages );
                wc_add_notice( $final_error_msg, 'error' );

                if(Helper::is_recurrence( $product_id )) {
                    $recurrence_parent_id = wp_get_post_parent_id( $product_id );
                    wp_safe_redirect( get_permalink( $recurrence_parent_id ) );
                } else {
                    wp_safe_redirect(get_permalink( $product_id ) );
                }

                exit();
            }
        }

        return $passed;
    }

    
    /**
     * before place order validate min/max qty with cart items
     *
     * @return array
     */
    public function validate_min_max_qty_before_order_submit() {
        $error_messages = [];

        if ( !WC()->cart->is_empty() ) {
            $events_data = $cart_picked_data = $ticket_qty_errors = [];

            $cart_contents = WC()->cart->get_cart();
            foreach ( $cart_contents as $cart_item_key => $cart_data ) {
                $event_id = isset( $cart_data['event_id'] ) ? absint( $cart_data['event_id'] ) : 0;

                if ( !empty( $event_id ) && get_post_type( $event_id ) == 'etn' ) {
                    if ( !isset( $events_data[ $event_id ] ) ) {
                        $variations                             = !empty( get_post_meta( $event_id, "etn_ticket_variations", true ) ) ? get_post_meta( $event_id, "etn_ticket_variations", true ) : [];
                        $events_data[ $event_id ]['variations'] = $variations;
                    }

                    $ticket_variations  = $events_data[ $event_id ]['variations'];
                    $item_variations    = isset( $cart_data["etn_ticket_variations"] ) ? $cart_data["etn_ticket_variations"] : [];
                    $event_name         = get_the_title( $event_id );

                    if ( !empty( $item_variations ) ) {
                        foreach ( $item_variations as $item_index => $item_variation ) {
                            $ticket_index = $this->search_array_by_value( $ticket_variations, $item_variation['etn_ticket_slug'] );

                            if ( isset( $ticket_variations[ $ticket_index ] ) ) {
                                $error_cat = [];
                                $variation_picked_qty = absint( $item_variation[ 'etn_ticket_qty' ] );
                                
                                if ( !isset( $cart_picked_data[ $event_id ][ $ticket_index ]['variation_total_picked_qty'] ) ) {
                                    $cart_picked_data[ $event_id ][ $ticket_index ]['variation_total_picked_qty'] = $variation_picked_qty;
                                } else {
                                    $cart_picked_data[ $event_id ][ $ticket_index ]['variation_total_picked_qty'] += $variation_picked_qty;
                                }

                                $total_tickets      = absint( $ticket_variations[ $ticket_index ]['etn_avaiilable_tickets'] );
                                $etn_sold_tickets   = absint( $ticket_variations[ $ticket_index ]['etn_sold_tickets'] );
                                $remaining_ticket   = $total_tickets - $etn_sold_tickets;

                                $etn_min_ticket     = absint( $ticket_variations[ $ticket_index ]['etn_min_ticket'] );
                                $etn_max_ticket     = absint( $ticket_variations[ $ticket_index ]['etn_max_ticket'] );
                                $etn_max_ticket     = min( $remaining_ticket, $etn_max_ticket );
                                
                                if ( !empty( $etn_min_ticket ) && $variation_picked_qty < $etn_min_ticket ) {
                                    $error_cat['min_allowed'] = $etn_min_ticket;
                                }
                    
                                if ( !empty( $etn_max_ticket ) && $variation_picked_qty > $etn_max_ticket ) {
                                    $error_cat['max_allowed'] = $etn_max_ticket;
                                }

                                if ( ( $etn_sold_tickets + $variation_picked_qty ) > $total_tickets ) {
                                    $error_cat['remaining_allowed'] = ( $total_tickets - $etn_sold_tickets );
                                }

                                $cart_picked_qty = $cart_picked_data[ $event_id ][ $ticket_index ]['variation_total_picked_qty'];
                                if ( ( $etn_sold_tickets + $cart_picked_qty ) > $total_tickets ) {
                                    $error_cat['total_picked_qty']        = $cart_picked_qty;
                                    $error_cat['total_remaining_allowed'] = ( $total_tickets - $etn_sold_tickets );
                                }
                    
                                if ( !empty( $error_cat ) ) {
                                    $error_cat['item_picked_qty'] = $variation_picked_qty;
                                    $error_cat['event_name']      = $event_name;
                                    
                                    $etn_ticket_name = $ticket_variations[ $ticket_index ]['etn_ticket_name'];
                                    $ticket_qty_errors[ $event_id ][ $etn_ticket_name ] = $error_cat;
                                }
                            }
                        }
                    }

                }
            }

            if ( !empty( $ticket_qty_errors ) ) {
                foreach ( $ticket_qty_errors as $event_id => $ticket_cart_info ) {
                    foreach ( $ticket_cart_info as $ticket_name => $error_info ) {
                        $event_name         = $error_info['event_name'];
                        $quoted_ticket_name = ' "' . $ticket_name . '" ';
                        $quoted_event_name  = ' "' . $event_name . '"';

                        if ( isset( $error_info['min_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'minimum purchasable quantity is ', 'eventin' ) . $error_info['min_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }
                        if ( isset( $error_info['max_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'maximum purchasable quantity is ', 'eventin' ) . $error_info['max_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }
                        if ( isset( $error_info['remaining_allowed'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'available quantity is ', 'eventin' ) . $error_info['remaining_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['item_picked_qty'] . esc_html__( ' ticket to the cart', 'eventin' );
                        }

                        if ( isset( $error_info['total_picked_qty'] ) ) {
                            $error_messages[] = esc_html__( 'Sorry, for', 'eventin' ) . $quoted_ticket_name . esc_html__( 'ticket of event', 'eventin' ) . $quoted_event_name . ', ' . 
                                esc_html__( 'available quantity is ', 'eventin' ) . $error_info['total_remaining_allowed'] . 
                                esc_html__( '. You attempted to add ', 'eventin' ) . $error_info['total_picked_qty'] . esc_html__( ' ticket(s) through adding multiple times to the cart', 'eventin' ) ;
                        }
                    }
                }
            }
        }

        return $error_messages;
    }

     /**
     * in cart page, compare cart item with stock
     */
    public function before_cart_check_stock() {
        $cart_stock_status  = $this->review_stock_with_cart_quantity();
        $error_messages     = $this->validate_min_max_qty_before_order_submit();

        $proceed_to_go_next = $cart_stock_status['proceed_to_go_next'];
        if( !$proceed_to_go_next ) {
            $product_name     = $cart_stock_status['product_name'];
            $remaining_ticket = $cart_stock_status['remaining_ticket'];

            // $error_msg          = 'before cart';
            $error_msg          = esc_html__( 'Sorry, we do not have enough "' , 'eventin' ) . $product_name . esc_html__( '" in stock to fulfill your order (', 'eventin' ) . $remaining_ticket . esc_html__( ' available). We apologise for any inconvenience caused.', 'eventin' );
            $error_messages[]   = $error_msg;
        }

        if ( !empty( $error_messages ) ) {
            wc_clear_notices();

            $final_error_msg = implode( '<br>', $error_messages );
            wc_print_notice( $final_error_msg, 'error' );
        }
    }

    /**
     * in checkout page, compare cart item with stock
     */
    public function before_checkout_check_stock() {
        $cart_stock_status  = $this->review_stock_with_cart_quantity();
        $error_messages     = $this->validate_min_max_qty_before_order_submit();

        $proceed_to_go_next = $cart_stock_status['proceed_to_go_next'];
        if( !$proceed_to_go_next ) {
            // $error_msg          = 'before checkout';
            $error_msg          = esc_html__( 'There are some issues with the items in your cart. Please go back to the cart page and resolve these issues before checking out.', 'eventin' );
            $error_messages[]   = $error_msg;
        }

        if ( !empty( $error_messages ) ) {
            wc_clear_notices();

            $final_error_msg = implode( '<br>', $error_messages );
            wc_print_notice( $final_error_msg, 'error' );

            $cart_url = wc_get_cart_url();
            ?>
            <a class="button wc-backward" href="<?php echo esc_url( $cart_url ); ?>"><?php echo esc_html__( 'Return to cart', 'eventin' ); ?></a>
            
            <?php
            die();
        }
    }

   /**
     * in checkout page, when click place order button: final chance to compare cart item with stock
     */
    public function before_submit_order_check_stock( $fields, $errors ) {
        $cart_stock_status  = $this->review_stock_with_cart_quantity();
        $error_messages     = $this->validate_min_max_qty_before_order_submit();

        $proceed_to_go_next = $cart_stock_status['proceed_to_go_next'];
        if( !$proceed_to_go_next ) {
            // $error_msg          = 'before place order';
            $error_msg          = esc_html__( 'There are some issues with the items in your cart. Please go back to the cart page and resolve these issues before checking out.', 'eventin' );
            $error_messages[]   = $error_msg;
        }
  
        if ( !empty( $error_messages ) ) {
            wc_clear_notices();

            $final_error_msg = implode( '<br>', $error_messages );
            $errors->add( 'validation', $final_error_msg );
        }
    }

    /**
     * compare cart item with stock. If greater than stock: notice user with error message
     */
    public function review_stock_with_cart_quantity() {
        $product_name       = ''; 
        $remaining_ticket   = 0;
        $proceed_to_go_next = true;

        $cart_item_quantities = WC()->cart->get_cart_item_quantities();
        if ( is_array( $cart_item_quantities ) && !empty( $cart_item_quantities ) ) {
            foreach( $cart_item_quantities as $product_id => $quantity ) {

                if ( get_post_type( $product_id ) == 'etn' ) {
                    $product_name = get_the_title( $product_id );

                    $total_sold_ticket = !empty( get_post_meta( $product_id, "etn_total_sold_tickets", true ) ) ? absint( get_post_meta( $product_id, "etn_total_sold_tickets", true ) ) : 0;
                    $available_ticket  = !empty( get_post_meta( $product_id, "etn_total_avaiilable_tickets", true ) ) ? absint( get_post_meta( $product_id, "etn_total_avaiilable_tickets", true ) ) : 0;
                    $remaining_ticket  = $available_ticket - $total_sold_ticket;

                    if ( $quantity > $remaining_ticket ) {
                        $proceed_to_go_next = false;
                        break;
                    }
                }
     
            }
        }
        
        $return_arr = [
            'proceed_to_go_next' => $proceed_to_go_next,
            'product_name'       => $product_name,
            'remaining_ticket'   => $remaining_ticket,
        ];

        return $return_arr;
    }

    /**
     * add attendee id to order id at the time order is created for paypal payment
     *
     * @param int $order_id
     * @return void
     */
    public function process_all_once_order_created( $order_id ) {

        if ( !$order_id ) {
            return;
        }

        global $wpdb;
    
        $order = wc_get_order( $order_id );

        if ( $order->is_paid() ) {
            $paid = 'Paid';
        } else {
            $paid = 'Unpaid';
        }

        // Allow code execution only once
        // if ( !get_post_meta( $order_id, '_etn_save_trans_record_done', true ) ) {

            $userId = 0;

            if ( is_user_logged_in() ) {
                $userId = get_current_user_id();
            }
            
            $payment_type = get_post_meta( $order_id, '_payment_method', true );
            $order_status = !empty( get_post_status( $order_id ) ) ? get_post_status( $order_id ) : '';

            if ( $payment_type == 'cod' ) {
                $etn_payment_method = 'offline_payment';
            } elseif ( $payment_type == 'bacs' ) {
                $etn_payment_method = 'bank_payment';
            } elseif ( $payment_type == 'cheque' ) {
                $etn_payment_method = 'check_payment';
            } elseif ( $payment_type == 'stripe' ) {
                $etn_payment_method = 'stripe_payment';
            } else {
                $etn_payment_method = 'online_payment';
            }

            if ( $order_status == 'wc-pending' ) {
                $status = 'Pending';
            } elseif ( $order_status == 'wc-processing' ) {
                $status = 'Processing';
            } elseif ( $order_status == 'wc-on-hold' ) {
                $status = 'Hold';
            } elseif ( $order_status == 'wc-completed' ) {
                $status = 'Completed';
            } elseif ( $order_status == 'wc-refunded' ) {
                $status = 'Refunded';
            } elseif ( $order_status == 'wc-failed' ) {
                $status = 'Failed';
            }  elseif ( $order_status == 'wc-partial-payment' ) {
                $status = 'Completed'; // 'Partially Paid'
            }  elseif ( $order_status == 'wc-scheduled-payment' ) {
                $status = 'Pending'; // 'Scheduled'
            }  elseif ( $order_status == 'wc-pending-deposit' ) {
                $status = 'Pending'; // 'Pending Deposit Payment'
            } else {
                $status = 'Pending';
            }

            foreach ( $order->get_items() as $item_id => $item ) {
                
                // Get the product name
                $product_name     = $item->get_name();
                $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
                $product_quantity = (int) $item->get_quantity();
                $product_total    = $item->get_total();

                if( !empty( $event_id ) ){
                    $event_object = get_post( $event_id );
                } else{
                    $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
                }

                if ( !empty( $event_object->post_type ) && ('etn' == $event_object->post_type) ) {

                    $event_id             = $event_object->ID;

                    $pledge_id = "";
                    $insert_post_id         = $event_id;
                    $insert_form_id         = $order_id;
                    $insert_invoice         = get_post_meta( $order_id, '_order_key', true );
                    $insert_event_amount    = !empty( $item->get_meta( '_etn_variation_total_price', true ) ) ? $item->get_meta( '_etn_variation_total_price', true ) : $product_total;
                    $insert_ticket_qty      = !empty( $item->get_meta( '_etn_variation_total_quantity', true ) ) ? $item->get_meta( '_etn_variation_total_quantity', true ) : $product_quantity;
                    $insert_user_id         = $userId;
                    $insert_email           = get_post_meta( $order_id, '_billing_email', true );
                    $insert_event_type      = "ticket";
                    $insert_payment_type    = 'woocommerce';

                    $etn_ticket_variations  = !is_null( $item->get_meta( 'etn_ticket_variations', true ) ) ? $item->get_meta( 'etn_ticket_variations', true ) : [];
                    $insert_ticket_variation=  serialize($etn_ticket_variations);

                    $insert_pledge_id       = $pledge_id;
                    $insert_payment_gateway = $etn_payment_method;
                    $insert_date_time       = date( "Y-m-d" );
                    $insert_status          = $status;
                    $inserted               = $wpdb->query( "INSERT INTO `". ETN_EVENT_PURCHASE_HISTORY_TABLE ."` (`post_id`, `form_id`, `invoice`, `event_amount`, `ticket_qty`, `ticket_variations`, `user_id`, `email`, `event_type`, `payment_type`, `pledge_id`, `payment_gateway`, `date_time`, `status`) VALUES ('$insert_post_id', '$insert_form_id', '$insert_invoice', '$insert_event_amount', '$insert_ticket_qty', '$insert_ticket_variation', '$insert_user_id', '$insert_email', '$insert_event_type', '$insert_payment_type', '$insert_pledge_id', '$insert_payment_gateway', '$insert_date_time', '$insert_status')" );
                    $id_insert              = $wpdb->insert_id;

                    if ( $inserted ) {
                        $metaKey                              = [];
                        $metaKey['_etn_first_name']           = get_post_meta( $order_id, '_billing_first_name', true );
                        $metaKey['_etn_last_name']            = get_post_meta( $order_id, '_billing_last_name', true );
                        $metaKey['_etn_email']                = get_post_meta( $order_id, '_billing_email', true );
                        $metaKey['_etn_post_id']              = $event_id;
                        $metaKey['_etn_order_key']            = '_etn_' . $id_insert;
                        $metaKey['_etn_order_shipping']       = get_post_meta( $order_id, '_order_shipping', true );
                        $metaKey['_etn_order_shipping_tax']   = get_post_meta( $order_id, '_order_shipping_tax', true );
                        $metaKey['_etn_order_qty']            = $product_quantity;
                        $metaKey['_etn_order_total']          = $product_total;
                        $metaKey['_etn_order_tax']            = get_post_meta( $order_id, '_order_tax', true );
                        $metaKey['_etn_addition_fees']        = 0;
                        $metaKey['_etn_addition_fees_amount'] = 0;
                        $metaKey['_etn_addition_fees_type']   = '';
                        $metaKey['_etn_country']              = get_post_meta( $order_id, '_billing_country', true );
                        $metaKey['_etn_currency']             = get_post_meta( $order_id, '_order_currency', true );
                        $metaKey['_etn_date_time']            = date( "Y-m-d H:i:s" );

                        foreach ( $metaKey as $k => $v ) {
                            $data               = [];
                            $data["event_id"]   = $id_insert;
                            $data["meta_key"]   = $k;
                            $data["meta_value"] = $v;
                            $wpdb->insert( ETN_EVENT_PURCHASE_HISTORY_META_TABLE, $data );
                        }
                    }

                    // ========================== Attendee related works start ========================= //
                    $settings               = Helper::get_settings();
                    $attendee_reg_enable    = !empty( $settings["attendee_registration"] ) ? true : false;
                    if( $attendee_reg_enable ){
                        // update attendee status and send ticket to email
                        $event_location   = !is_null( get_post_meta( $event_object->ID , 'etn_event_location', true ) ) ? get_post_meta( $event_object->ID , 'etn_event_location', true ) : "";
                        $etn_ticket_price = !is_null( get_post_meta( $event_object->ID , 'etn_ticket_price', true ) ) ? get_post_meta( $event_object->ID , 'etn_ticket_price', true ) : "";
                        $etn_start_date   = !is_null( get_post_meta( $event_object->ID , 'etn_start_date', true ) ) ? get_post_meta( $event_object->ID , 'etn_start_date', true ) : "";
                        $etn_end_date     = !is_null( get_post_meta( $event_object->ID , 'etn_end_date', true ) ) ? get_post_meta( $event_object->ID , 'etn_end_date', true ) : "";
                        $etn_start_time   = !is_null( get_post_meta( $event_object->ID , 'etn_start_time', true ) ) ? get_post_meta( $event_object->ID , 'etn_start_time', true ) : "";
                        $etn_end_time     = !is_null( get_post_meta( $event_object->ID , 'etn_end_time', true ) ) ? get_post_meta( $event_object->ID , 'etn_end_time', true ) : "";
                        $update_key       = !is_null( $item->get_meta( 'etn_status_update_key', true ) ) ? $item->get_meta( 'etn_status_update_key', true ) : "";
                        $insert_email     = !is_null( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";

                        $pdf_data = [
                            'order_id'          => $order_id,
                            'event_name'        => $product_name ,
                            'update_key'        => $update_key ,
                            'user_email'        => $insert_email ,
                            'event_location'    => $event_location ,
                            'etn_ticket_price'  => $etn_ticket_price,
                            'etn_start_date'    => $etn_start_date,
                            'etn_end_date'      => $etn_end_date,
                            'etn_start_time'    => $etn_start_time,
                            'etn_end_time'      => $etn_end_time
                        ];

                        Helper::mail_attendee_report( $pdf_data, true );
                    }
                    // ========================== Attendee related works end ========================= //
                }
            }

            update_post_meta( $order_id, '_etn_save_trans_record_done', true );
            $order->update_meta_data( '_etn_save_trans_record_done', true );
            $order->save();
        // }
    }

    // custom metabox for showing attendee list on woocommerce order page
    public function etn_order_page_metabox_init() {
        add_meta_box(
            'etn-shop-attendee-list',
            esc_html__('Eventin Attendee List', 'eventin'),
            [ $this, 'etn_order_page_metabox_callback'],
            'shop_order',
            'normal',
            'default'
        );
    }

    // custom metabox for showing attendee list on woocommerce order page - callback
    public function etn_order_page_metabox_callback( $order ) {

        $args = array(
           'post_type' => 'etn-attendee',
           'post_status' => 'publish',
           'meta_key' => 'etn_attendee_order_id',
           'meta_value' => $order->ID,
           'numberposts'   => -1                        
        );
        $attendees = get_posts($args);
        
        if( $attendees){
            echo "<div class='etn-table-view'>
                <div class='etn-column'>
                    <h4>".esc_html__('Attendee ID', 'eventin')."</h4>
                </div>
                <div class='etn-column'>
                    <h4>".esc_html__('Ticket ID', 'eventin')."</h4>
                </div>
                <div class='etn-column'>
                    <h4>".esc_html__('Attendee Name', 'eventin')."</h4>
                </div>
            ";

            foreach( $attendees as $attendee){
                echo "
                    <div class='etn-column'>".esc_html__($attendee->ID, 'eventin')."</div>
                    <div class='etn-column'>".esc_html__($attendee->etn_unique_ticket_id, 'eventin')."</div>
                    <div class='etn-column'><a href='".esc_attr(get_edit_post_link( $attendee->ID), 'eventin')."' target='_blank' rel='noopener'>".esc_html__($attendee->post_title, 'eventin')."</a></div>
                    ";
            }

        }else{
            echo esc_html__('No Attendee Found', 'eventin');
        }             
    }

    // custom css for shop_order page event table
    public function etn_order_page_table_css() {
        if( 'shop_order' == get_post_type() ){
            echo '<style>
                .etn-table-view{
                    display: grid;
                    grid-template-columns: 1fr 1fr 2fr;
                    grid-gap: 5px;
                }
                .etn-table-view h4{
                    margin: 5px 0;
                }
              </style>';
        }
    }

    /**
     * save cart item custom meta as order item_meta to show in thank you and order page
     */
    public function save_cart_item_data( $item, $cart_item_key, $values, $order ) {
        $updatable_metas = [
            'event_id',
            'etn_status_update_key',
            'etn_ticket_variations',
            '_etn_variation_total_price',
            '_etn_variation_total_quantity',
        ];

        foreach ( $updatable_metas as $index => $meta_key ) {
            if ( isset( $values[ $meta_key ] ) ) {
                $item->update_meta_data( $meta_key, $values[ $meta_key ] );
            }
        }
        
        // add a way to recognize custom post type in ordered items
        if ( $values['data']->get_type() == 'etn' ) {
            $item->update_meta_data( '_etn', 'yes' ); 
            return;
        }

    }

    /**
     * coupon page events multiple select dropdown markup
     */
    public function etn_woo_coupon_markup(){
        global $post;

        $value = get_post_meta( get_the_ID(), 'etn_event_ids', true );

        if( empty( $value ) ) $value = '';

        $all_events = get_posts(array(
            'posts_per_page'  => -1,
            'post_type' => 'etn'
        ));

        foreach( $all_events as $event){
            $options[$event->ID] = $event->post_title;
        }

        ?>
        <div class="options_group">
            <p class="form-field">
                <label for="etn_event_ids"><?php echo esc_html__( 'Select Eventin Events', 'eventin' ); ?></label>
                <select multiple="multiple" name="etn_event_ids[]" class="etn_coupon_event_select2" style="width: 50%;">
                    <?php
                    if ( !empty( $options ) ) {
                        foreach ( $options as $option_key => $option ) {
                            if ( is_array( $value ) && in_array( $option_key, $value ) ) {
                                ?>
                                <option selected value="<?php echo esc_attr( $option_key ); ?>"> <?php echo esc_html( $option ); ?> </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo esc_attr( $option_key ); ?>"> <?php echo esc_html( $option ); ?> </option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>
            </p>
        </div>
        <?php
    }

    /**
     * coupon after save hook for saving event id's as coupon product
     */
    public function etn_woo_coupon_save_options($post_id, $coupon){

        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );  

        if( isset( $post_arr['etn_event_ids'] ) ) {
            $event_ids = $post_arr['etn_event_ids'];
        }else{
            $event_ids = [];
        }

        $all_events = get_posts(array(
            'posts_per_page'  => -1,
            'post_type' => 'etn',
            'fields'    => 'ids'
        ));
        
        //get all coupon products
        $coupon_products = $coupon->get_product_ids();

        // findout which coupon products are eventin events and remove them from the coupon_products array
        $only_coupon_products = array_diff($coupon_products, $all_events);


        // update the coupon product meta 
        $coupon->update_meta_data( 'etn_event_ids', $event_ids);

        // add new event ids to the coupon products and save coupon data
        $coupon_with_events = array_merge($only_coupon_products, $event_ids);
        $coupon->set_product_ids($coupon_with_events);
        $coupon->save();
    }

    /**
     * select2 script for coupon page only
     */
    public function etn_woo_coupon_script(){
        // get screen
        $screen    = get_current_screen();

        if( "shop_coupon" == $screen->post_type ){
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('.etn_coupon_event_select2').select2();
                });
            </script>
        <?php

        }
    }

    public function etn_woo_coupon_count_adjustment( $coupon_code ) {
        global $woocommerce;
        $coupon =  new \WC_Coupon($coupon_code);
        $coupon_remaining_usage_limit = (int) ($coupon->usage_limit - $coupon->usage_count);
        $items = $woocommerce->cart->get_cart();
        $etn_coupon_quantity = 0;
        
        foreach($items as $item => $values) { 

            $_product =  wc_get_product( $values['data']->get_id()); 

            if ( 
                get_post_type( $values['product_id']  ) == 'etn' &&
                in_array( $values['product_id'], $coupon->get_product_ids()) &&
                !empty( $values['etn_ticket_variations'] ) && 
                count($values['etn_ticket_variations']) > 0
            ) {
                foreach ( $values['etn_ticket_variations'] as $key => $value) {
                    // $values['quantity'] += ($value['etn_ticket_qty'] - 1 );
                    $etn_coupon_quantity += $value['etn_ticket_qty'];
                    
                }
            }
        } 
        
        if( $coupon_remaining_usage_limit < $etn_coupon_quantity ){
            $woocommerce->cart->remove_coupon( $coupon_code );
            wc_add_notice ( esc_html__('Coupon usage limit has been reached.', 'eventin'), 'error');
            return;
        }else{
            $coupon->set_usage_count();
            $coupon->save();
        }
        
    }

    public function etn_woo_cart_coupon_validation($true, $coupon){
        // get the number of remaining coupon number
        $coupon_remaining_usage_limit = (int) ($coupon->get_usage_limit() - $coupon->get_usage_count() );

        $items = WC()->cart->get_cart();
        $existing_events = [];
        
        foreach($items as $item => $values) { 

            $_product =  wc_get_product( $values['data']->get_id()); 
            $_product_id = $_product->get_id();
            $coupon_session = "etn_coupon_count_{$coupon->get_code()}_{$_product_id}";

            // apply the validation to only etn products
            if ( 
                get_post_type( $values['product_id']  ) == 'etn' &&
                in_array( $values['product_id'], $coupon->get_product_ids()) &&
                !empty( $values['etn_ticket_variations'] ) && 
                count($values['etn_ticket_variations']) > 0
            ) {
                
                if( !in_array( $_product_id, $existing_events) ){
                        array_push( $existing_events, $_product_id);
                        $etn_coupon_quantity[$_product_id] = 0;
                }else{
                    $etn_coupon_quantity[$_product_id] = (int) WC()->session->get($coupon_session)['count'];
                }

                foreach ( $values['etn_ticket_variations'] as $key => $value) {
                    
                    $etn_coupon_quantity[$_product_id] += (int) $value['etn_ticket_qty'];

                    // if the number of available coupon count exceeds the number of variation tickets check, then through notice
                    if( $coupon_remaining_usage_limit < $etn_coupon_quantity[$_product_id] ){
                        WC()->cart->remove_coupon( $coupon->get_code() );
                        wc_add_notice( 
                            sprintf( 
                                __("'<strong>%s</strong>' Coupon usage limit has been reached for '<strong>%s</strong>'. ", 'eventin'),
                                $coupon->get_code(),
                                $_product->get_name()
                            ),
                            'error'
                        );
                        return false;
                    }else{
                        if( isset($coupon_session) ){
                            WC()->session->__unset( $coupon_session);
                        }
                        
                        WC()->session->set( $coupon_session, [
                            'coupon'    => $coupon->get_code(),
                            'product_id'=> $_product_id,
                            'count'     => $etn_coupon_quantity[$_product_id]
                        ]);
                    }
                }   
            }
        } 
        
        return $true; 
    }

    public function etn_woo_cart_order_processed( $order_id ){
        if (!empty(WC()->cart->applied_coupons)){
            $order = wc_get_order( $order_id );

            $coupons = WC()->cart->get_applied_coupons();
            $products = $order->get_items();
            $existing_events = [];

            foreach( $coupons as $coupon_code ){
                foreach( $order->get_items() as $item_id => $item ){

                    $product = $item->get_product();
                    $product_id = $product->get_id();

                    if( in_array( $product_id, $existing_events) ){
                            array_push( $existing_events, $product_id);
                            return;
                    }

                    $coupon = new \WC_Coupon($coupon_code);
                    $coupon_session = "etn_coupon_count_{$coupon->get_code()}_{$product_id}";
                    $session_usage_count = (int) WC()->session->get($coupon_session)['count'];
                    $usage_count = (int) ($coupon->get_usage_count() + $session_usage_count - 1 );
                    $coupon->set_usage_count( $usage_count );
                    $coupon->save();
                }
            }
        }
    }


}
