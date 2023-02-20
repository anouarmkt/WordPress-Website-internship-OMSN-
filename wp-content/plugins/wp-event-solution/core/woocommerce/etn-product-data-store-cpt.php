<?php

use Etn\Utils\Helper;

defined('ABSPATH') || exit;

if ( !class_exists( 'WC_Product_Data_Store_CPT' ) ) {
    return;
}

class Etn_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {

    /**
     * Method to read a product from the database.
     * @param WC_Product
     */
    public function read( &$product ) {
        
        $product->set_defaults();

        if ( !$product->get_id() || !( $post_object = get_post( $product->get_id() ) ) || !in_array( $post_object->post_type, ['etn', 'product'] ) ) {
            throw new Exception( esc_html__( 'Invalid product.', 'eventin' ) );
        }

        // $id = $product->get_id();

        $product->set_id( $post_object->ID );
        
        $product->set_props( [
            'product_id'        => $post_object->ID,
            'name'              => $post_object->post_title,
            'slug'              => $post_object->post_name,
            'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
            'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
            'status'            => $post_object->post_status,
            'description'       => $post_object->post_content,
            'short_description' => $post_object->post_excerpt,
            'parent_id'         => $post_object->post_parent,
            'menu_order'        => $post_object->menu_order,
            'reviews_allowed'   => 'open' === $post_object->comment_status,
        ] );

        $this->read_attributes( $product );
        $this->read_downloads( $product );
        $this->read_visibility( $product );
        $this->read_product_data( $product );
        $this->read_extra_data( $product );
        $product->set_object_read( true );
    }

    /**
     * Get the product type based on product ID.
     */
    public function get_product_type( $product_id ) {

        $post_type = get_post_type( $product_id );

        if ( 'product_variation' === $post_type ) {
            return 'variation';
        } elseif ( in_array( $post_type, ['etn', 'product'] ) ) {
            $terms = get_the_terms( $product_id, 'product_type' );
            return !empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'etn';
        } else {
            return false;
        }

    }

}

/**
 * overwrite woocommerce store and make our custom post as a product
 */
function etn_woocommerce_data_stores( $stores ) {
    $stores['product'] = 'Etn_Product_Data_Store_CPT';

    return $stores;
}

 //all hooks required to hook our event as woocommerce product
add_filter( 'woocommerce_data_stores', 'etn_woocommerce_data_stores' );


