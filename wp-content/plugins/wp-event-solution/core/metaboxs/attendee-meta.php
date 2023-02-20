<?php

namespace Etn\Core\Metaboxs;
use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Attendee_Meta extends Event_manager_metabox {

    public $metabox_id              = 'etn_attendee_meta';
    public $default_attendee_fields = [];
    public $cpt_id                  = 'etn-attendee';

    public function register_meta_boxes() {

        add_meta_box(
            $this->metabox_id,
            esc_html__( 'Attendee Details', 'eventin' ),
            [$this, 'display_callback'],
            $this->cpt_id
        );

    }

    public function etn_attendee_meta_fields() {

        $default_attendee_fields = array();
        $settings          = Helper::get_settings();
        $include_phone     = !empty( $settings["reg_require_phone"] ) ? true : false;
        $include_email     = !empty( $settings["reg_require_email"] ) ? true : false;
        // list of WooCommerce orders
        global $post;
        $order_id = get_post_meta($post->ID, 'etn_attendee_order_id',true);

        if( $include_email ){
            $default_attendee_fields['etn_email']   = [
                'label' => esc_html__( 'Email', 'eventin' ),
                'type'  => 'email',
                'value' => '',
                'desc'  => esc_html__( 'Enter Attendee Email Address', 'eventin' ),
                'attr'  => ['class' => 'etn-label-item'],
								'group'    => 'etn-label-group',
            ];
        }
        if( $include_phone ) {
            $default_attendee_fields['etn_phone']   = [
                'label' => esc_html__( 'Phone', 'eventin' ),
                'type'  => 'text',
                'value' => '',
                'desc'  => esc_html__( 'Enter Attendee Phone Number', 'eventin' ),
                'attr'  => ['class' => 'etn-label-item'],
                'group' => 'etn-label-group',
            ];
        }

        $default_attendee_fields['etn_attendeee_ticket_status'] = [
            'label'    => esc_html__( 'Ticket', 'eventin' ),
            'desc'     => esc_html__( 'Attendee ticket status', 'eventin' ),
            'type'     => 'select_single',
            'options'  => [
                'unused' => esc_html__( 'Unused', 'eventin' ),
                'used' => esc_html__( 'Used', 'eventin' ),
            ],
            'priority' => 1,
            'attr'     => ['class' => 'etn-label-item'],
						'group'    => 'etn-label-group',
        ];

        $default_attendee_fields['etn_status'] = [
            'label'    => esc_html__( 'Payment', 'eventin' ),
            'desc'     => esc_html__( 'Attendee payment status', 'eventin' ),
            'type'     => 'select_single',
            'options'  => [
                'success' => esc_html__( 'Success', 'eventin' ),
                'failed' => esc_html__( 'Failed', 'eventin' ),
            ],
            'priority' => 1,
            'attr'     => ['class' => 'etn-label-item'],
            'group'    => 'etn-label-group',
        ];
        if( !empty( $order_id ) ) {
					$button_url = get_edit_post_link( $order_id, 'eventin');
					$default_attendee_fields['etn_order_details'] = [
								'label'       => esc_html__( 'Order Details', 'eventin' ),
								'text'        => esc_html__( 'View', 'eventin' ),
								'url'         => $button_url,
								'desc'        => esc_html__( 'Order details here', 'eventin' ),
								'type'        => 'button',
								'attr'        => ['class' => 'etn-label-item'],
								'group'    => 'etn-label-group',
						];

        }else{
            $default_attendee_fields['etn_attendee_order_id'] = [
                'label'    => esc_html__( 'Order ID', 'eventin' ),
                'desc'     => esc_html__( 'WooCommerce Order ID', 'eventin' ),
                'type'     => 'select_single',
                'options'  => $this->get_order_items(),
                'priority' => 1,
                'attr'     => ['class' => 'etn-label-item'],
								'group'    => 'etn-label-group',
            ];
        }

        $this->default_attendee_fields = apply_filters( 'etn_attendee_fields', $default_attendee_fields );

        return $this->default_attendee_fields;
    }

    /**
     * get the order items for metabox select option
     * @since 1.1.0
     * @return void
     */
    public function get_order_items() {
        $orders = wc_get_orders(array(
            'limit' => -1
        ));

        $order_items = array(
            "" => ""
        );

        foreach( $orders as $order){
            if ( is_a( $order, 'WC_Order_Refund' ) ) {
                $order = wc_get_order( $order->get_parent_id() );
            }
            $order_items[ $order->get_id() ] = $order->get_id(). " - ".$order->get_billing_email();
        }

        return $order_items;
    }

}
