<?php

namespace Etn\Core\Migration;

use Etn\Utils\Helper;

defined('ABSPATH') || exit;

class Migration {
    use \Etn\Traits\Singleton;

    /**
     * Main Function 
     *
     * @return void
     */
    public function init(){
        $this->migrate_event_price();   // event price migration done for achieving similar structure like woocommerce product price
        $this->migrate_for_single_price_to_multiple_prices_feature();   // migrations required for single ticket to multiple ticket variation feature
        
        $this->migrate_attendee_unique_id();    // migrations required to generate unique ticket it for each attendee

        if ( class_exists( 'WooCommerce' ) ) {
            add_action( 'init', [ $this, 'process_woo_stock_management' ], 10, 0 );   // migration for stock management
        }

        $this->migrate_stripe_payment_support(); 
    }

    /**
     * Generate Unique ID For Attendee Ticket
     *
     * @return void
     */
    public function migrate_attendee_unique_id() {
        $migration_done = !empty( get_option( "etn_attendee_unique_id_migration_done" ) ) ? true : false;
        
        if( !$migration_done ){

            $args          = [
                'post_type' => 'etn-attendee',
            ];
            $all_attendees = get_posts($args);
            foreach( $all_attendees as $attendee ){
                $attendee_id    = $attendee->ID;
                $ticket_id      = Helper::generate_unique_ticket_id_from_attendee_id( $attendee_id );
                update_post_meta( $attendee_id, 'etn_unique_ticket_id', $ticket_id );
            }

            update_option( "etn_attendee_unique_id_migration_done", true );
        }
    }

    /**
     * after woocommerce loaded then able to use woo functionality
     *
     * @return void
     */
    public function process_woo_stock_management() {
        $this->migrate_event_order_status();  
    }

    /**
     * migrate event order status by adding meta flag to check what was the last increment/decrement status
     *
     * @return void
     */
    public function migrate_event_order_status(){
        $migration_done = !empty( get_option( "etn_event_order_status_migration_done" ) ) ? true : false;
  
        if( !$migration_done && class_exists( 'WooCommerce' ) ){
            $all_order_posts = \Etn\Utils\Helper::get_order_posts();
       
            if( is_array($all_order_posts) && !empty($all_order_posts) ){
                foreach( $all_order_posts as $order_post ){
                    $order = wc_get_order( intval( $order_post['ID'] ) );
           
                    foreach ( $order->get_items() as $item_id => $item ){
                        $product_name     = $item->get_name();
                        $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
                        $product_quantity = (int) $item->get_quantity();
                        
                        if( !empty( $event_id ) ){
                            $event_object = get_post( $event_id );
                        } else {
                            $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
                        }
            
                        if ( !empty( $event_object ) ){
                            //this item is an event, proceed...
                            $decrease_states = [
                                'processing',
                                'on-hold',
                                'completed',
                                'on', // same as 'on-hold'
                            ];
                    
                            $increase_state = [
                                'pending',
                                'cancelled',
                            ];
                    
                            $no_action_state = [
                                'refunded',
                                'failed',
                            ];
                        
                            if ( !empty( $event_object->post_type ) && ('etn' == $event_object->post_type) ) {
                                $event_id = $event_object->ID;

                                $order_status = $order->get_status();
                            
                                // decrease event stock
                                if( in_array($order_status, $decrease_states) ){
                                    $decreased_stock = wc_get_order_item_meta( $item_id, '_etn_decreased_stock', true ); 
                                
                                    if( $decreased_stock != $product_quantity ){
                                        wc_delete_order_item_meta( $item_id, '_etn_increased_stock' ); 
                                        wc_add_order_item_meta( $item_id, '_etn_decreased_stock', $product_quantity, true);
                                    }
                                }

                                // increase event stock
                                if( in_array($order_status, $increase_state) ){
                                    $increased_stock = wc_get_order_item_meta( $item_id, '_etn_increased_stock', true );

                                    if( $increased_stock != $product_quantity ){
                                        wc_delete_order_item_meta( $item_id, '_etn_decreased_stock' ); 
                                        wc_add_order_item_meta( $item_id, '_etn_increased_stock', $product_quantity, true);
                                    }
                                }
                           
                                // complex case: refunded/failed handling
                                if( in_array($order_status, $no_action_state) ){
                                    
                                    $order_notes = wc_get_order_notes( array( 'order_id' => $order->get_id() ) );
                                    
                                    if( is_array($order_notes) && !empty($order_notes) ){
                                        krsort($order_notes);
                                      
                                        foreach( $order_notes as $order_note ){
                                            $matched = preg_match('/Order status changed from /i', $order_note->content);
                                          
                                            if( $matched ){
                                                $note_content = strtolower( rtrim( $order_note->content, '.' ) );
                                                $note_from_to = strstr($note_content, 'from');

                                                $old_order_status   = explode(' ', $note_from_to)[1];  
                                                $note_to            = strstr($note_from_to, 'to');
                                                $new_order_status   = explode(' ', $note_to)[1];

                                                // stock meta decrease/increase logic
                                                // decrease event stock
                                                $decreased_stock = wc_get_order_item_meta( $item_id, '_etn_decreased_stock', true ); 

                                                if( $decreased_stock != $product_quantity ){
                                                    if( in_array($new_order_status, $decrease_states) && !in_array($old_order_status, $decrease_states) ){
                                                        wc_delete_order_item_meta( $item_id, '_etn_increased_stock' ); 
                                                        wc_add_order_item_meta( $item_id, '_etn_decreased_stock', $product_quantity, true);
                                                    }
                                                }

                                                // increase event stock
                                                $increased_stock = wc_get_order_item_meta( $item_id, '_etn_increased_stock', true ); 
  
                                                if( $increased_stock != $product_quantity ){
                                                    if( in_array($new_order_status, $increase_state) && !in_array($old_order_status, $increase_state ) ){
                                                        wc_delete_order_item_meta( $item_id, '_etn_decreased_stock' ); 
                                                        wc_add_order_item_meta( $item_id, '_etn_increased_stock', $product_quantity, true);
                                                    }
                                                }
                                            }  
                                        }
                                    }
                                }  
                            }  
                        }
                    }
                }
            }

            update_option( "etn_event_order_status_migration_done", true );
        }
    }


    /**
     * migrate event price into Woocommerce product price
     *
     * @return void
     */
    public function migrate_event_price() {
        $migration_done = !empty( get_option( "etn_event_price_migration_done" ) ) ? true : false;
        
        if( !$migration_done ){
            $all_events = \Etn\Utils\Helper::get_events();
            if( is_array($all_events) && !empty($all_events) ){
                foreach( $all_events as $event_id => $event_title ){
                    $event_price = !empty(get_post_meta( $event_id, "etn_ticket_price", true )) ? get_post_meta( $event_id, "etn_ticket_price", true ) : 0;
                    update_post_meta( $event_id, "_price", $event_price );
                    update_post_meta( $event_id, "_regular_price", $event_price );
                    update_post_meta( $event_id, "_sale_price", $event_price );
                }
            }

            update_option( "etn_event_price_migration_done", true );
        }
    }

    /**
     * Migrations Required for Event Single Ticket to Variable Ticket Feature
     *
     * @return void
     */
    public function migrate_for_single_price_to_multiple_prices_feature(){
        $full_migration_done = !empty( get_option( "etn_event_price_to_prices_array_migration_done" ) ) ? true : false;
        
        if( !$full_migration_done ){

            $price_to_prices_array_migration_done = !empty( get_option( "price_to_prices_array_migration_done" ) ) ? true : false;;
            if( !$price_to_prices_array_migration_done ){
                $this->migrate_event_price_to_prices_array();
                update_option( "price_to_prices_array_migration_done", true );
            }

            $attendee_ticket_type_migration_done = !empty( get_option( "attendee_ticket_type_migration_done" ) ) ? true : false;;
            if( !$attendee_ticket_type_migration_done ){
                $this->migrate_attendee_ticket_type();
                update_option( "attendee_ticket_type_migration_done", true );
            }
            
            $woo_order_item_meta_migration_done = !empty( get_option( "woo_order_item_meta_migration_done" ) ) ? true : false;;
            if(class_exists( 'WooCommerce' ) && !$woo_order_item_meta_migration_done){
                $this->migrate_woo_order_item_meta();
                update_option( "woo_order_item_meta_migration_done", true );
            }

            $purchase_history_table_structure_migration_done = !empty( get_option( "purchase_history_table_structure_migration_done" ) ) ? true : false;;
            if( !$purchase_history_table_structure_migration_done ){
                $this->migrate_event_purchase_history_table_structure();
                update_option( "purchase_history_table_structure_migration_done", true );
            }

            $purchase_history_table_data_migration_done = !empty( get_option( "purchase_history_table_data_migration_done" ) ) ? true : false;;
            if(class_exists( 'WooCommerce' ) && !$purchase_history_table_data_migration_done){
                $this->migrate_event_purchase_history_table_data();
                update_option( "purchase_history_table_data_migration_done", true );
            }

            update_option( "etn_event_price_to_prices_array_migration_done", true );
        }
    }

    /**
     * Update Existing Event Ticket Price, Qty Format To Match The New Structure
     *
     * @return void
     */
    public function migrate_event_price_to_prices_array(){
        $all_events = \Etn\Utils\Helper::get_events(null, true);

        if( is_array($all_events) && !empty($all_events) ){
            foreach( $all_events as $event_id => $event_title ){
                $ticket_variations  = [];
                $event_old_price    = !empty(get_post_meta( $event_id, "etn_ticket_price", true )) ? get_post_meta( $event_id, "etn_ticket_price", true ) : 0;
                $event_old_qty      = !empty(get_post_meta( $event_id, "etn_avaiilable_tickets", true )) ? absint( get_post_meta( $event_id, "etn_avaiilable_tickets", true ) ) : 0;
                $event_sold_qty     = !empty(get_post_meta( $event_id, "etn_sold_tickets", true )) ? intval(get_post_meta( $event_id, "etn_sold_tickets", true )) : 0;

                if ( $event_old_qty == 999999999 ) {
                    $event_old_qty = 100000;
                }
                
                $etn_min_ticket     = !empty(get_post_meta( $event_id, 'etn_min_ticket', true )) ? absint( get_post_meta( $event_id, 'etn_min_ticket', true ) ) : 1;
                $etn_max_ticket     = !empty(get_post_meta( $event_id, 'etn_max_ticket', true )) ? absint( get_post_meta( $event_id, 'etn_max_ticket', true ) ) : $event_old_qty;

                $event_ticket_variation_title = ETN_DEFAULT_TICKET_NAME;
                $event_ticket_variation_slug  = Helper::generate_unique_slug_from_ticket_title( $event_id, $event_ticket_variation_title );
                
                $ticket_variations[] = [
                    'etn_ticket_price'          => $event_old_price,
                    'etn_avaiilable_tickets'    => $event_old_qty,
                    'etn_sold_tickets'          => $event_sold_qty,
                    'etn_min_ticket'            => $etn_min_ticket,
                    'etn_max_ticket'            => $etn_max_ticket,
                    'etn_ticket_name'           => $event_ticket_variation_title,
                    'etn_ticket_slug'           => $event_ticket_variation_slug,
                ];
                update_post_meta( $event_id, 'etn_ticket_variations', $ticket_variations );
                update_post_meta( $event_id, 'etn_total_avaiilable_tickets', $event_old_qty );
                update_post_meta( $event_id, 'etn_total_sold_tickets', $event_sold_qty );
            }
        }
    }

    /**
     * Update Existing Attendee Details For Variable Ticket
     *
     * @return void
     */
    public function migrate_attendee_ticket_type(){
        $all_attendees = Helper::get_attendee();

        if( is_array($all_attendees) && !empty($all_attendees) ){
            foreach( $all_attendees as $single_attendee ){
                $attendee_id            = $single_attendee->ID;
                $attendee_event_id      = get_post_meta( $attendee_id, 'etn_event_id', true );
                $attendee_event_prices  = get_post_meta( $attendee_event_id, 'etn_ticket_variations', true );
                
                if( isset( $attendee_event_prices[0] ) && is_array( $attendee_event_prices[0] ) && !empty( $attendee_event_prices[0] ) ) {
                    $attendee_ticket_name           = $attendee_event_prices[0]['etn_ticket_name'];
                    $attendee_ticket_unique_slug    = $attendee_event_prices[0]['etn_ticket_slug'];
                    update_post_meta( $attendee_id, 'ticket_name', $attendee_ticket_name );
                    update_post_meta( $attendee_id, 'ticket_slug', $attendee_ticket_unique_slug );
                }
            }
        }
    }

    /**
     * Update Existing Woo Order Details For Variable Ticket
     *
     * @return void
     */
    public function migrate_woo_order_item_meta(){
        // step 1, get all items from wp_woocommerce_order_items table
        // step 2, foreach item, get all eventin events using wp_woocommerce_order_itemmeta table
        // step 3, update order item meta, add ticket variation details meta
        global $wpdb;
        $all_woo_order_items    = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_order_items" );

        if(is_array($all_woo_order_items) && !empty($all_woo_order_items)){
            foreach( $all_woo_order_items as $single_order_item ){
                $order_item_id      = $single_order_item->order_item_id;
                $product_name       = $single_order_item->order_item_name;
                $event_id           = !empty( wc_get_order_item_meta( $order_item_id, 'event_id', true ) ) ? wc_get_order_item_meta( $order_item_id, 'event_id', true ) : "";
                $purchased_qty      = !empty( wc_get_order_item_meta( $order_item_id, '_qty', true ) ) ? wc_get_order_item_meta( $order_item_id, '_qty', true ) : 0;
            
                if( !empty( $event_id ) ){
                    $event_object = get_post( $event_id );
                } else{
                    $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
                }
    
                if ( !empty( $event_object->post_type ) && ('etn' == $event_object->post_type) ) {
                    // this is an eventin event, proceed....
    
                    $event_id               = $event_object->ID;
                    $event_ticket_variations= get_post_meta( $event_id, 'etn_ticket_variations', true );

                    if ( is_array( $event_ticket_variations ) && isset( $event_ticket_variations[0] ) ) {          
                        $default_ticket_title   = $event_ticket_variations[0]['etn_ticket_name']; //get the default ticket variation title
                        $default_ticket_slug    = $event_ticket_variations[0]['etn_ticket_slug']; //get the default ticket variation slug
        
                        $purchased_ticket_variation_details = [
                            [
                                'etn_ticket_name'   => $default_ticket_title,
                                'etn_ticket_slug'   => $default_ticket_slug,
                                'etn_ticket_qty'    => $purchased_qty,
                            ],
                        ];
                        wc_update_order_item_meta( $order_item_id, 'etn_ticket_variations',  $purchased_ticket_variation_details );
                    }
                   
                }
            }
        }
    }

    /**
     * add more columns to etn_events table 
     *
     * @return void
     */
    public function migrate_event_purchase_history_table_structure(){
        global $wpdb;
        $purchaseHistoryTableName = ETN_EVENT_PURCHASE_HISTORY_TABLE;

        $tableMigrationQuery = "ALTER TABLE `$purchaseHistoryTableName` ADD ticket_variations text DEFAULT '' AFTER `event_amount`, ADD ticket_qty INT DEFAULT 1 AFTER `event_amount`;";
        $wpdb->query( $tableMigrationQuery );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function migrate_event_purchase_history_table_data(){
        global $wpdb;
        $purchaseHistoryTableName   = ETN_EVENT_PURCHASE_HISTORY_TABLE;
        $allHistory                 = $wpdb->get_results( "SELECT * FROM {$purchaseHistoryTableName}" );
    
        if( !is_array( $allHistory ) || empty( $allHistory )){
            return;
        }

        $allOrderHistoryArray = [];
        foreach($allHistory as $singleHistory){
            $id         = $singleHistory->event_id;
            $eventId    = $singleHistory->post_id;
            $wooOrderId = $singleHistory->form_id;
            $amount     = $singleHistory->event_amount;

            if( !array_key_exists($wooOrderId, $allOrderHistoryArray) ){
                $allOrderHistoryArray[$wooOrderId] = [];
            }
            $singleArray = [
                'event_id'     => $id,
                'post_id'      => $eventId,
                'event_amount' => $amount,
                'ticket_qty'   =>  '',
            ];
            array_push($allOrderHistoryArray[$wooOrderId], $singleArray);
        }
    
        $allEventOrderIds = array_keys($allOrderHistoryArray);

        $secondStepArray = [];
        if( is_array( $allEventOrderIds ) && !empty( $allEventOrderIds )){
            foreach( $allEventOrderIds as $orderId ){
                
                $orderItemTableName = $wpdb->prefix . 'woocommerce_order_items';
                $allOrderItems      = $wpdb->get_results( "SELECT * FROM {$orderItemTableName} WHERE `order_id`={$orderId}" );
                
                if( is_array($allOrderItems) && !empty($allOrderItems)){
                    foreach($allOrderItems as $singleOrderItem){
                        $orderItemId        = $singleOrderItem->order_item_id;
                        $orderItemName      = $singleOrderItem->order_item_name;
                        $eventId            = !is_null( wc_get_order_item_meta(  $orderItemId,  'event_id', true ) ) ? wc_get_order_item_meta(  $orderItemId,  'event_id', true ) : "";
                        $productQuantity    = !is_null( wc_get_order_item_meta(  $orderItemId,  '_qty', true ) ) ? (int) wc_get_order_item_meta(  $orderItemId,  '_qty', true ) : 0;
                        $productTotal       = !is_null( wc_get_order_item_meta(  $orderItemId,  '_line_total', true ) ) ? wc_get_order_item_meta(  $orderItemId,  '_line_total', true ) : 0;
                    
                        if( !empty( $eventId ) ){
                            $eventObject = get_post( $eventId );
                        } else{
                            $eventObject = get_page_by_title( $orderItemName, OBJECT, 'etn' );
                        }
        
                        if ( !empty( $eventObject->post_type ) && ('etn' == $eventObject->post_type) ) {
        
                            $eventId    = $eventObject->ID;
        
                            // this order item is an eventin event, proceed...
                            if( !array_key_exists($orderId, $secondStepArray) ){
                                $secondStepArray[$orderId] = [];
                            }
        
                            $singleItemArray = [
                                'event_id'      => $eventId,
                                'amount'        => $productTotal,
                                'qty'           => $productQuantity,
                            ];
                            array_push($secondStepArray[$orderId], $singleItemArray);
                        }
                    }
                }
            }
        }
    
        if( is_array($secondStepArray) && !empty($secondStepArray)){
            foreach($secondStepArray as $wooUniqueOrderId => $wooOrderItems){
                if( is_array($wooOrderItems) && !empty($wooOrderItems) ){
                    foreach( $wooOrderItems as $key => $wooSingleOrderItem ){
        
                        $eventId            = $wooSingleOrderItem['event_id'];
                        $ticketQty          = $wooSingleOrderItem['qty'];
                        $ticketVariations   = get_post_meta( $eventId, 'etn_ticket_variations', true );

                        if ( is_array( $ticketVariations ) && isset( $ticketVariations[0] ) ) {
                            $defaultTicketTitle = $ticketVariations[0]['etn_ticket_name']; //get the default ticket variation title
                            $defaultTicketSlug  = $ticketVariations[0]['etn_ticket_slug']; //get the default ticket variation slug
                            $ticketVariationDetails = [
                                [
                                    'etn_ticket_name'   => $defaultTicketTitle,
                                    'etn_ticket_slug'   => $defaultTicketSlug,
                                    'etn_ticket_qty'    => $ticketQty,
                                ],
                            ];
                        }
    
                        $finalOrderId       = $wooUniqueOrderId;  //woo order id
                        $finalRowId         = $allOrderHistoryArray[$wooUniqueOrderId][$key]['event_id']; //row unique id
                        $finalEventId       = $allOrderHistoryArray[$wooUniqueOrderId][$key]['post_id'];  // event id
                        $finalQty           = $allOrderHistoryArray[$wooUniqueOrderId][$key]['ticket_qty'] = $ticketQty;
                        $finalTicketVariations = $allOrderHistoryArray[$wooUniqueOrderId][$key]['ticket_variations'] = serialize( $ticketVariationDetails );
    
                        $wpdb->query("UPDATE $purchaseHistoryTableName SET ticket_qty = '$finalQty', ticket_variations = '$finalTicketVariations' WHERE post_id = '$finalEventId' AND form_id = '$finalOrderId' AND event_id = '$finalRowId'");
                    }
                }
            }
        }
    }

    /**
     * migrate event purchase history table structure to support stripe payment gateway
     *
     * @return void
     */
    public function migrate_stripe_payment_support() {
        $purchase_history_table_stripe_support = !empty( get_option( "purchase_history_table_stripe_support" ) ) ? true : false;;
        if( !$purchase_history_table_stripe_support ){
            $this->migrate_event_purchase_history_table_stripe_support();
            update_option( "purchase_history_table_stripe_support", true );
        }
    }

    /**
     * update event purchase history table structure to support stripe payment gateway
     *
     * @return void
     */
    public function migrate_event_purchase_history_table_stripe_support(){
        // run table column migration for older version than 3.1.7
		if ( version_compare( get_option( 'etn_version' ), '3.1.7', '<' ) ) {

            global $wpdb;
            $purchaseHistoryTableName = ETN_EVENT_PURCHASE_HISTORY_TABLE;
    
			$migration_query = "ALTER TABLE `$purchaseHistoryTableName` MODIFY COLUMN `payment_type` ENUM('woocommerce', 'stripe') DEFAULT 'woocommerce';";
			$wpdb->query( $migration_query );
		}
    }
}

if( !function_exists('etn_speaker_schedule_title_migration') ){

    /**
     * Migration To Update Speaker And Schedule Title
     *
     * @return void
     */
    function etn_speaker_schedule_title_migration(){
        
        $all_speakers = get_posts( [
            'post_type' => 'etn-speaker',
        ] );
        $all_schedule = get_posts( [
            'post_type' => 'etn-schedule',
        ] );

        if( is_array($all_speakers) && !empty( $all_speakers )){

            // update speaker data
            foreach( $all_speakers as $speaker ){
                $speaker_id     = $speaker->ID;
                $speaker_content= get_post_meta( $speaker->ID, 'etn_speaker_summery', true );
                $speaker_title  = get_post_meta( $speaker_id, 'etn_speaker_title', true );
                $post_slug      = sanitize_title_with_dashes( $speaker_title, '', 'save' );
                $speaker_slug   = sanitize_title( $post_slug );
                $speaker_data   = array(
                    'ID'           => $speaker_id,
                    'post_name'    => $speaker_slug, // new title
                    'post_title'   => $speaker_title, // new title
                    'post_content' => $speaker_content,
                );
                wp_update_post( $speaker_data );
            }
        }

        if( is_array($all_schedule) && !empty( $all_schedule )){
            //update schedule data
            foreach( $all_schedule as $schedule ){
                $schedule_id    = $schedule->ID;
                $schedule_title = get_post_meta( $schedule_id, 'etn_schedule_title', true );
                $post_slug      = sanitize_title_with_dashes( $schedule_title, '', 'save' );
                $schedule_slug  = sanitize_title( $post_slug );
                $schedule_data  = array(
                    'ID'            => $schedule_id,
                    'post_title'    => $schedule_title, // new title
                    'post_name'     => $schedule_slug,
                );
                wp_update_post( $schedule_data );
            }
        }
    }
}
// etn_speaker_schedule_title_migration();
