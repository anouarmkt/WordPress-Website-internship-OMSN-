<?php

namespace Etn\Core\Attendee;

use \Etn\Core\Attendee\Attendee_List;
use \Etn\Core\Attendee\Pages\Attendee_Single_Page;
use Etn\Utils\Helper;

defined( "ABSPATH" ) || exit;

class Hooks {
    use \Etn\Traits\Singleton;

    public $cpt;
    public $action;
    public $base;
    public $settings;
    public $actionPost_type = ['etn-attendee'];

    public function Init() {

        $settings        = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $attendee_module = ! empty( $settings['attendee_registration'] ) ? true : false;

        if( $attendee_module ) {
            $this->cpt      = new Cpt();
            $this->action   = new Action();
            $this->settings = new Settings( "etn", "1.0" );

            $this->add_metaboxes();
            $this->add_single_page_template();

            add_action( 'wp_ajax_change_ticket_status', [$this, 'change_ticket_status'] );
            add_action( 'wp_ajax_nopriv_change_ticket_status', [$this, 'change_ticket_status'] );

            // woo thank you page contains key in url so don't show attendee info here. this is for user purchased events
            if ( !isset( $_GET['key'] ) ) {
                add_action( 'woocommerce_order_details_after_order_table', [ $this, 'after_order_table_show_attendee_information' ], 9, 1 );
            }
        }

        // woocommerce my account > puchased events sidebar menu related hook
        add_action( 'init', [ $this, 'add_purchased_events_endpoint' ] );
        add_filter( 'query_vars', [ $this, 'purchased_events_query_vars' ], 0 );
        add_filter( 'woocommerce_account_menu_items', [ $this, 'add_purchased_events_link_my_account' ] );
        add_action( 'woocommerce_account_purchased-events_endpoint', [ $this, 'purchased_events_content' ] );
    }

    public function add_metaboxes() {

        // custom post meta
        $attendee_meta = new \Etn\Core\Metaboxs\Attendee_Meta();
        add_action( 'add_meta_boxes', [$attendee_meta, 'register_meta_boxes'] );
        add_action( 'save_post', [$attendee_meta, 'save_meta_box_data'] );

    }

    function add_single_page_template() {
        $page = new Attendee_Single_Page();
    }

    /**
     * update ticket status from attendee dashboard
     */
    public function change_ticket_status() {
        $status_code  = 0;
        $messages     = [];
        $content      = [];

        if ( wp_verify_nonce( sanitize_text_field( $_POST['security'] ), 'ticket_status_nonce_value' ) ) {
 
            if ( !current_user_can( 'manage_etn_attendee' ) ) {
                $messages[] = esc_html__( 'Update failed. Try again!', 'eventin' );
            } else {
                $attendee_id    = absint( $_POST['attendee_id'] );
                $ticket_status  = sanitize_text_field( $_POST['ticket_status'] );

                $update_status = update_post_meta( $attendee_id, 'etn_attendeee_ticket_status', $ticket_status );
                if ( $update_status ) {
                    $status_code    = 1;
                    $messages[]     = esc_html__( 'Status updated', 'eventin' );

                    $new_val = 'unused';
                    if ( $ticket_status == 'unused' ) {
                        $new_val = 'used';
                    }

                    $content['new_val']  = $new_val;
                    $content['new_text'] = ucfirst( $ticket_status );

                    $response = [
                        'status_code' => $status_code,
                        'messages'    => $messages,
                        'content'     => $content,
                    ];
                    wp_send_json_success( $response );
                    exit();
                }
            }
        } else {
            $messages[] = esc_html__( 'Update failed. Try again!', 'eventin' );
        }

        $response = [
            'status_code' => $status_code,
            'messages'    => $messages,
            'content'     => $content,
        ];
        wp_send_json_error( $response );
        exit;
    }

    /**
     * adding purchased-events endpoint
     */
    public function add_purchased_events_endpoint() {
        add_rewrite_endpoint( 'purchased-events', EP_ROOT | EP_PAGES );
    }

    /**
     * add extra item purchase-events
     *
     * @param [array] $vars
     * @return array
     */
    public function purchased_events_query_vars( $vars ) {
        $vars[] = 'purchased-events';

        return $vars;
    }

    /**
     * add extra item purchase events in sidebar menu
     * 
     * @param [array] $items
     * @return array
     */
    public function add_purchased_events_link_my_account( $items ) {
        $extra_item = [ 
            'purchased-events' => esc_html__( 'Purchased events', 'eventin-pro' )
        ];

        $split_1 = array_slice( $items, 0, 3 );
        $split_2 = array_slice( $items, 3, count( $items ) );

        $items = $split_1 + $extra_item + $split_2;
        return $items;
    }

    /**
     * view of purchased events page
     */
    public function purchased_events_content() {
        global $wpdb;

        $user_orders = get_posts( array(
            'numberposts'   => -1,
            'meta_key'      => '_customer_user',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'meta_value'    => get_current_user_id(),
            'post_type'     => wc_get_order_types(),
            'post_status'   => array_keys( wc_get_order_statuses() ), 
            // 'post_status'   => array('wc-completed'),
        ) );

        $user_events = [];
        foreach ($user_orders as $user_order) {
            $order_id       = $user_order->ID;
            $order          = wc_get_order( $order_id );
            $order_status   = $order->get_status();
            $date_created   = $order->get_date_created();
            $order_url      = $order->get_view_order_url();
            
            foreach ( $order->get_items() as $item_id => $item ) {
                $product_name  = $item->get_name();
                $event_id      = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

                if ( !empty( $event_id ) ) {
                    $user_events[ $order_id ][ $event_id ] = [
                        'event_id'     => $event_id,
                        'event_name'   => $product_name,
                        'order_status' => $order_status,
                        'order_id'     => $order_id,
                        'order_url'    => $order_url,
                    ];
                }
            }
        }

        if ( count( $user_events ) > 0 ) {
            include_once \Wpeventin::core_dir() . "attendee/views/purchaser/purchased-events.php";
        } else {
            echo esc_html__( 'No event has purchased yet!', 'eventin' );
        }
    }

    /**
     * show attendee information in woo order details
     *
     * @param [type] $order
     * @return void
     */
    public function after_order_table_show_attendee_information( $order ) { 
        foreach ( $order->get_items() as $item_id => $item ) {
            $event_id = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

            if ( !empty( $event_id ) ) {
                $args = array(
                    'post_type'     => 'etn-attendee',
                    'post_status'   => 'publish',
                    'meta_key'      => 'etn_attendee_order_id',
                    'meta_value'    => $order->get_id(),
                    'numberposts'   => -1
                );
                
                $attendees = get_posts($args);
                if( count( $attendees ) > 0 ) {
                    $settings        = Helper::get_settings();
                    $include_email   = !empty( $settings["reg_require_email"] ) ? true : false;
                    $include_phone   = !empty( $settings["reg_require_phone"] ) ? true : false;
    
                    $base_url               = home_url( );
                    $attendee_cpt           = new \Etn\Core\Attendee\Cpt();
                    $attendee_endpoint      = $attendee_cpt->get_name();
                    $action_url             = $base_url . "/" . $attendee_endpoint;
    
                    $ticket_download_link   = $action_url . "?etn_action=". urlencode('download_ticket') ."&attendee_id="; 
                    $edit_information_link  = $action_url . "?etn_action=" . urlencode( 'edit_information' ) . "&attendee_id=";
    
                    include_once \Wpeventin::core_dir() . "attendee/views/purchaser/attendee-details.php";
                }
            }
        }
    }


    /**
     * attendee add mechanism
     */
    public function add_attendee_data( $sells_engine = 'woocommerce' , $attendee_data = array() ) {
			if ( ! empty( $_POST['sells_engine'] ) && 'woocommerce' == $_POST['sells_engine'] ) {
				$ticket_purchase_next_step = ! empty( $_POST['ticket_purchase_next_step'] ) ? $_POST['ticket_purchase_next_step'] : '';
			}else{
				$ticket_purchase_next_step = isset( $attendee_data['ticket_purchase_next_step'] ) ? $attendee_data['ticket_purchase_next_step'] : '';
			}

			if ( isset( $ticket_purchase_next_step ) && $ticket_purchase_next_step === "three" ) {
					$post_arr =  $sells_engine !== 'stripe' ? filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING ) : $attendee_data;

					$check    = wp_verify_nonce( $post_arr['ticket_purchase_next_step_three'], 'ticket_purchase_next_step_three' );

					if ( $check && !empty( $post_arr['attendee_info_update_key'] )
							&& !empty( $post_arr["add-to-cart"] ) && !empty( $post_arr["quantity"] )
							&& !empty( $post_arr["attendee_name"] ) ) {
							$access_token   = $post_arr['attendee_info_update_key'];
							$event_id       = $post_arr["add-to-cart"];
							$payment_token  = md5( 'etn-payment-token' . $access_token . time() . rand( 1, 9999 ) );
							$ticket_price   = get_post_meta( $event_id, "etn_ticket_price", true );

							// Variation Data.

							// total variations.
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
							if ( 'stripe' == $sells_engine ) {
								return 'success';
							}
					} else {
							if ( 'stripe' == $sells_engine ) {
								return 'error';
							}
							wp_redirect( get_permalink() );
					}

			}else{
				if ( 'stripe' == $sells_engine ) {
					return 'error';
				}
			}
	}

}
