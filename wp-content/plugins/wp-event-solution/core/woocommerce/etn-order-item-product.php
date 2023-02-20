<?php

defined('ABSPATH') || exit;

if ( !class_exists( 'WC_Product' ) || !class_exists('WC_Order_Item_Product')) {
    return;
}

if( class_exists( 'WooCommerce' )  ) {
    add_filter( 'woocommerce_product_class', 'etn_woo_product_class', 25, 3 );
    add_filter( 'woocommerce_get_order_item_classname', 'etn_woocommerce_get_order_item_classname', 20, 3 );

    add_filter( 'woocommerce_product_type_query', 'etn_woo_product_type', 12, 2 );
    add_filter( 'woocommerce_checkout_create_order_line_item_object', 'etn_woocommerce_checkout_create_order_line_item_object', 20, 4 );
}

/**
 * enable etn as wc product
 */
class Etn_Woo_Product extends WC_Product {
    protected $post_type = 'etn';

    public function get_type() {
        return 'etn';
    }

    public function __construct( $product = 0 ) {
        $this->supports[] = 'ajax_add_to_cart';
        parent::__construct( $product );
    }
    // maybe overwrite other functions from WC_Product
}

/**
 * set event id as product id for order item 
 */
class Etn_WC_Order_Item_Product extends WC_Order_Item_Product {
    public function set_product_id( $value ) {
        if ( $value > 0 && 'etn' !== get_post_type( absint( $value ) ) ) {
            $this->error( 'order_item_product_invalid_product_id', __( 'Invalid product ID', 'eventin' ) );
        }
        $this->set_prop( 'product_id', absint( $value ) );
    }
}

/**
 * load class name after etn is registered as woo product
 *
 * @param [type] $class_name
 * @param [type] $product_type
 * @param [type] $product_id
 * @return string
 */
function etn_woo_product_class( $class_name, $product_type, $product_id ) {
    if ($product_type == 'etn') {
        $class_name = 'Etn_Woo_Product';
    }
 
    return $class_name; 
}

/**
 * load the new class if the item is our custom post
 *
 * @param [type] $class_name
 * @param [type] $item_type
 * @param [type] $id
 * @return string
 */
function etn_woocommerce_get_order_item_classname($class_name, $item_type, $id) {
    global $wpdb;
    $is_etn = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = '_etn' AND order_item_id = {$id}");

    if ('yes' === $is_etn) { 
        $class_name = 'Etn_WC_Order_Item_Product';
    }

    return $class_name;
}

/**
 * check product type is etn
 *
 * @param [type] $false
 * @param [type] $product_id
 * @return string | bool
 */
function etn_woo_product_type($false, $product_id) { 
    if ($false === false) { // don't know why, but this is how woo does it
        global $post;
        
        // maybe redo it someday?!
        if ( !empty($post) && is_object($post) ) { // post is set
            if ($post->post_type == 'etn' && $post->ID == $product_id) {
                return 'etn';
            } else {
                $product = get_post( $product_id );
                if ( is_object($product) && !is_wp_error($product) ) { // post not set but it's a etn
                    if ($product->post_type == 'etn') return 'etn';
                }
            }
        } else if( wp_doing_ajax() ) { // has post set (useful when adding using ajax)
            $product_post = get_post( $product_id );
            if ($product_post->post_type == 'etn') return 'etn';
        } else { 
            $product = get_post( $product_id );
            if ( is_object($product) && !is_wp_error($product) ) { // post not set but it's a etn
                if ($product->post_type == 'etn') return 'etn';
            }
        }
    }

    return false;
}

/**
 * if order is for etn then return instance of etn as order item
 *
 * @param [type] $item
 * @param [type] $cart_item_key
 * @param [type] $values
 * @param [type] $order
 * @return void
 */
function etn_woocommerce_checkout_create_order_line_item_object($item, $cart_item_key, $values, $order) {
    $product = $values['data'];

    if ($product->get_type() == 'etn') {
        return new Etn_WC_Order_Item_Product();
    }

    return $item;
}
?>