<?php

namespace Etn\Core\Event;

use Etn\Utils\Helper;

defined( "ABSPATH" ) or die();

class Registration {

    use \Etn\Traits\Singleton;

    /**
     * Call all necessary hook
     */
    public function init() {
        add_action( 'init', [$this, 'registration_step_two'] );
    }

    /**
     * Store attendee report
     */
    public function registration_step_two() {

        if ( isset( $_POST['ticket_purchase_next_step'] ) && $_POST['ticket_purchase_next_step'] === "two" ) {
            $post_arr          = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
            $check             = wp_verify_nonce( $post_arr['ticket_purchase_next_step_two'], 'ticket_purchase_next_step_two' );
            $settings          = Helper::get_settings();
            $include_phone     = !empty( $settings["reg_require_phone"] ) ? true : false;
            $include_email     = !empty( $settings["reg_require_email"] ) ? true : false;
            $reg_form_template = \Wpeventin::core_dir() . "attendee/views/registration/attendee-details-form.php";

            
            // check if WPML is activated
            if( class_exists('SitePress') && function_exists('icl_object_id') ){
                global $sitepress;
                $event_id = $post_arr["event_id"];
                $trid = $sitepress->get_element_trid($event_id);
                $post_arr["event_id"] = $sitepress->get_original_element_id($event_id, 'post_etn');
                $post_arr["lang_event_id"] = $event_id;
            }
 
            if ( file_exists( $reg_form_template ) ) {
                // for compatibility with deposit plugin: check two variables are exist in request. if exist, so deposit is running
                $deposit_enabled      = ( isset( $post_arr['wc_deposit_option'] ) && $post_arr['wc_deposit_option'] === 'yes' ) ? 1 : 0;
                $deposit_payment_plan = isset( $post_arr['wc_deposit_payment_plan'] ) ? absint( $post_arr['wc_deposit_payment_plan'] )  : 0;

                include_once $reg_form_template;
            }
        }

        return false;
    }

}
