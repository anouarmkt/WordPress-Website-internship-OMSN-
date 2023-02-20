<?php

defined( 'ABSPATH' ) || exit;

/**
*   Add etn_sold_tickets post meta if not available
*/
function etn_add_sold_tickets_post_meta($post_id, $post, $update) {
    if ($post->post_type == 'etn' && $post->post_status == 'publish' && empty(get_post_meta( $post_id, 'etn_sold_tickets' ))) {

        # And update the meta so it won't run again
        add_post_meta( $post_id, 'etn_sold_tickets', 0, true );
    }
}
add_action( 'wp_insert_post', 'etn_add_sold_tickets_post_meta', 10, 3 );


/**
*   update all WPML child events meta when meta key is updated
*/ 
add_action( 'added_post_meta', 'etn_update_event_meta', 10, 4 );
add_action( 'updated_post_meta', 'etn_update_event_meta', 10, 4 );
function etn_update_event_meta( $meta_id, $post_id, $meta_key, $meta_value )
{
    if ( 'etn_avaiilable_tickets' == $meta_key || 'etn_sold_tickets' == $meta_key ) {
         do_action( 'wpml_sync_custom_field', $post_id, 'etn_avaiilable_tickets');
         do_action( 'wpml_sync_custom_field', $post_id, 'etn_sold_tickets' );
    }
}


// if WPML is activated, change the event_id meta with the original translation's event_id callback 
function etn_wpml_alter_original_translation_event_id( $order_id ) { 
    if ( !$order_id ) {
        return;
    }

    global $sitepress;
    $order = wc_get_order( $order_id );
    foreach ( $order->get_items() as $item_id => $item ) {
        $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
        if( !empty( $event_id ) ){
            $trid = $sitepress->get_element_trid($event_id);
            $original_translation = $sitepress->get_original_element_id($event_id, 'post_etn');
            wc_update_order_item_meta( $item_id, 'event_id', $original_translation );
            wc_update_order_item_meta( $item_id, '_lang_event_id', $event_id );
            $item->set_name( get_the_title( $event_id) );
            $item->save();
        }
    }
    $order->save();
}; 
        
// if WPML is activated, change the event_id meta with the original translation's event_id
add_action( 'woocommerce_checkout_update_order_meta', 'etn_wpml_alter_original_translation_event_id', 9, 1 ); 

/**
 * Change add to cart message for wpml
 *
 * @param [type] $message
 * @param [type] $products
 * @return string
 */
function override_woo_add_to_cart_msg( $message, $products ) { 
    $product_id = array_keys( $products )[0];

    if ( get_post_type( $product_id ) == 'etn' && !empty( wp_get_post_parent_id( $product_id ) ) ) {
        $message = sprintf( esc_html__( '"%s" has been added to your cart.', 'eventin' ), get_the_title( $product_id ) ); 
    }

    return $message; 
}

add_filter( 'wc_add_to_cart_message_html', 'override_woo_add_to_cart_msg', 10, 2 );