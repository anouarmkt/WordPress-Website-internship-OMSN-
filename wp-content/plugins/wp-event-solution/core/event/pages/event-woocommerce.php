<?php

namespace Etn\Core\Event\Pages;

defined( 'ABSPATH' ) || exit;

class Event_Woocommerce {

    function cart_template( $template ) {

        if ( class_exists( 'WooCommerce' ) ) {
            if ( is_page( 'cart' ) || is_cart() ) {
                return \Wpeventin::plugin_dir() . 'views/template/woocommerce/cart/cart.php';
            }
        }
        return $template;
    }

    public function after_cart_table() {
        require \Wpeventin::plugin_dir() . 'views/template/woocommerce/cart/cart-attendee.php';
    }

    public function order_processed( $order_id ) {

        $attendee = $_SESSION['attendee'];
        update_post_meta( $order_id, 'etn_es_wc_order_attendee_data', $attendee );

        $this->save_ticket( $order_id, $attendee );
        unset( $_SESSION['attendee'] );
    }

    public function order_attendee_details( $order ) {

        $attendee_data = get_post_meta( $order->get_id(), 'etn_es_wc_order_attendee_data', true );
        if ( !is_array( $attendee_data ) ) {
            return;
        }

        try {
            ?>
            <h2><?php echo esc_html__( 'Attendee', 'eventin' ); ?></h2>
            <div class='etn-es-single-page-flex-container etn-extra-attendee'>
            <?php
            if( is_array( $attendee_data ) ){

                foreach ( $attendee_data as $key => $item ) {

                    $_product = wc_get_product( $key );
                    ?>
                    <div class='etn-es-event-cart-attendee'>
                    <h3><?php echo esc_html( $_product->get_title() ); ?></h3>
                    <?php
                    if ( is_array( $item ) ) {
                        foreach ( $item as $k => $attendee ) {
                            ?>
                            <div><?php echo esc_html( $attendee['name'] ); ?> - <?php echo esc_html( $attendee['phone'] ); ?></div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                    <?php
                }
                //end foreach
            }
            ?>
            </div>
            <?php
        } catch ( Exception $e ) {
            return;
        }
    }

    public function save_ticket( $order_id = null, $data = [] ) {
        if ( $order_id == '' || is_null( $order_id ) ) {
            return;
        }

        $ticket = [
            'post_title'   => "#order-" . $order_id,
            'post_status'  => 'pending',
            'post_content' => serialize( $data ),
            'post_type'    => 'etn',
        ];
        wp_insert_post( $ticket );
    }

}
