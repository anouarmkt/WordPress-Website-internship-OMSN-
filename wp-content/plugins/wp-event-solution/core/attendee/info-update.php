<?php

namespace Etn\Core\Attendee;

use Etn\Utils\Helper;

defined( "ABSPATH" ) or die();

class InfoUpdate {

    use \Etn\Traits\Singleton;

    public function init() {
        add_action( 'init', [$this, 'attendee_info_update_process']);
        add_action( 'init', [$this, 'update_attendee_details']);
    }

    public function attendee_info_update_process() {
        if ( isset( $_GET['etn_action'] ) && sanitize_text_field( $_GET['etn_action'] ) === 'edit_information' ) {

            $settings        = Helper::get_settings();
            $include_phone   = !empty( $settings["reg_require_phone"] ) ? true : false;
            $include_email   = !empty( $settings["reg_require_email"] ) ? true : false;

            // render template
            if( file_exists( \Wpeventin::core_dir() . "attendee/views/ticket/info-update.php" ) ){
                include_once \Wpeventin::core_dir() . "attendee/views/ticket/info-update.php";
            }
        }
        return;
    }

    public function update_attendee_details() {

        if ( isset( $_POST["etn_attendee_details_update_action"] ) && $_POST["etn_attendee_details_update_action"] == "etn_attendee_details_update_action" ) {
            $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );  

            // render template
            if( file_exists( \Wpeventin::core_dir() . "attendee/views/ticket/update-attendee.php" ) ){
                include_once \Wpeventin::core_dir() . "attendee/views/ticket/update-attendee.php";
            }
        }
    }

}
