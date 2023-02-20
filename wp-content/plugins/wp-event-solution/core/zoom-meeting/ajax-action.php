<?php

namespace Etn\Core\Zoom_Meeting;

use Etn\Traits\Singleton;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Ajax_Action {
    use Singleton;

    public $textdomain = 'eventin';
    
    public function init() {
        // check conection
        add_action( 'wp_ajax_zoom_connection', [$this, 'zoom_connection'] );
        // for users who are not logged in
        add_action( 'wp_ajax_nopriv_zoom_connection', [$this, 'zoom_connection'] );

        // create meeting
        add_action( 'wp_ajax_elementor_create_meeting', [$this, 'elementor_create_meeting'] );
        // for users who are not logged in
        add_action( 'wp_ajax_nopriv_elementor_create_meeting', [$this, 'elementor_create_meeting'] );

         // sync zoom meeting & webinars
         add_action( 'wp_ajax_sync_zoom_data', [$this, 'sync_zoom_data'] );
         // for users who are not logged in
         add_action( 'wp_ajax_nopriv_sync_zoom_data', [$this, 'sync_zoom_data'] );
    }

    /**
     * Reservation form submit check
     */
    public function zoom_connection() {
        $response = ['status_code' => 500, 'message' => ['something is wrong'], 'data' => []];
        $secured  = Helper::is_secured('zoom_nonce', 'zoom_connection_check_nonce', null, $_POST);

        if ( $secured == false && current_user_can( 'manage_options' ) == false ) {
            wp_send_json_error( $response );
        }

        //check for validation
        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

        // Check connection
        if ( is_array( $post_arr ) && count( $post_arr ) > 0 ) {
            $test_conn = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->zoom_api_conn_check() );
            
            if ( !empty( $test_conn ) ) {
                
                if ( !empty( $test_conn->error ) ) {
                    $message  = esc_html__( 'Please check your api connection.', "eventin" );
                    $response = ['status_code' => 125, 'message' => [esc_html__( $message , "eventin" )] ];
                    wp_send_json_error( $response );
                }
                if ( http_response_code() === 200 ) {
                    // remove cache
                    \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->remove_cache();
                    // get host list
                    \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->zoom_meeting_user_list();
                    // send response
                    if ( isset($test_conn->message) ) {
                        $message  = esc_html__( $test_conn->message , "eventin" );
                        $response = ['status_code' => $test_conn->code, 'message' => [esc_html__( $test_conn->message , "eventin" )] ];
                        wp_send_json_error( $response );
                    }
                    else {
                        $response = ['status_code' => 200, 'message' => [esc_html__( 'Api connection is successfull.' , "eventin" )] ];
                        wp_send_json_success( $response );
                    }
                } else {
                    wp_send_json( $test_conn );
                }

            }

        }

        exit;
    }

    /**
     * Elementor create or update meeting
     */
    public function elementor_create_meeting() {
        $response = [ 'status_code' => 500, 'message' => [ esc_html__("Something is wrong","eventin") ], 'data' => [] ];
        $secured  = Helper::is_secured('zoom_nonce', 'zoom_create_meeting_nonce', null, $_POST);

        if ( $secured == false && current_user_can( 'manage_options' ) == false ) {
            wp_send_json_error( $response );
        }

        // check for validation
        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

        if ( is_array( $post_arr ) && !empty( $post_arr ) ) {
            $meeting_type      = $post_arr['type'];

            if ( $meeting_type == '5' && !Helper::is_webinar_user() ) {
                wp_send_json_error( $response );
            }

            $title             = !empty( $post_arr['topic'] ) ? $post_arr['topic'] : 'Zoom ' . Helper::zoom_types()[$meeting_type];
            $post_arr['topic'] = $title;

            $data           = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->create_meeting( $post_arr ) );

            $meeting_id     = $post_arr['meeting_id'];
            if ( !empty( $meeting_id ) ) {
                $url_type = 'meetings';
                if ( $meeting_type == '5' ) {
                    $url_type = 'webinars';
                }
                $data   = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $meeting_id, $url_type ) );
            }
      
            $post_slug  = sanitize_title_with_dashes( $title, '', 'save' );
            $postslug   = sanitize_title( $post_slug );

            $agenda     = sanitize_textarea_field( $post_arr['agenda'] );

            if ( $data ) {
                // the array of arguments to be inserted with wp_insert_post
                $meta_array     = [];
                $zoom_post_id   = 0;
                if ( empty( $meeting_id ) ) {
                    $new_post = [
                        'post_title'     => $title,
                        'post_content'   => $agenda,
                        'post_status'    => 'publish',
                        'post_type'      => 'etn-zoom-meeting',
                        'comment_status' => 'closed',
                        'post_name'      => $postslug,
                    ];
                    $zoom_post_id        = wp_insert_post( $new_post );
                    
                    $meta_array           = [
                        'zoom_join_url'        => $data->join_url,
                        'zoom_start_url'       => $data->start_url,
                        'zoom_meeting_id'      => $data->id,
                        'zoom_meeting_type'    => $meeting_type,
                        'zoom_meeting_status'  => isset( $data->status ) ? $data->status : '',
                        'zoom_meeting_host'    => $data->host_id,
                        'zoom_meeting_host_id' => $data->host_id,
                    ];

                    $status_code = 201;
                    $created_updated_msg = esc_html__( ' is created successfully.', 'eventin' );
                } else { 
                    $post_id      = Helper::get_single_data_by_meta( 'etn-zoom-meeting' , 1 , 'zoom_meeting_id', $meeting_id );
                    if ( !empty( $post_id ) ) {
                        $zoom_post_id = $post_id[0]->ID ;

                        $update_post = [
                            'ID'            => $zoom_post_id,
                            'post_title'    => $title,
                            'post_content'  => $agenda,
                            'post_name'     => $postslug,
                        ];

                        wp_update_post( $update_post );

                        $status_code = 204;
                        $created_updated_msg = esc_html__( ' is updated successfully.', 'eventin' );
                    }
                }

                $new_password = isset( $data->password ) ? $data->password : $zoom_post_id;
                $meta_array             += [
                    'zoom_topic'           => $data->topic, // $title
                    'zoom_agenda'          => $agenda,
                    'zoom_start_time'      => $data->start_time,
                    'zoom_timezone'        => $data->timezone,
                    'zoom_duration'        => $data->duration,
                    'zoom_password'        => $new_password,
                ];
                
                $data->password = $new_password;

                if ( isset( $data->settings ) ) {
                    $settings_data = $data->settings;
                    $meta_array    = Helper::get_zoom_meta_settings( $meeting_type, $settings_data, $meta_array );
                }

                if ( is_array( $meta_array ) && count( $meta_array ) > 0 && $zoom_post_id !== 0 ) {
                    foreach ( $meta_array as $key => $value ) {
                        update_post_meta( $zoom_post_id , $key , $value );
                    }
                }

                $msg        = Helper::zoom_types()[$meeting_type] . $created_updated_msg;
                $response   = ['status_code' => $status_code, 'message' => [ $msg ], 'data' => $data];
                
            } else {
                $response = ['status_code' => 401, 'message' => ['Something is wrong.','eventin'], 'data' => []];
            }
            wp_send_json_success( $response );
        }
        exit;
    }

    /**
     * Sync server data
     */
    public function sync_zoom_data() {
        $response = [ 'status_code' => 500, 'message' => [ esc_html__("Something is wrong", "eventin") ], 'data' => [] ];
        $secured  = Helper::is_secured( 'sync_nonce', 'zoom_sync_nonce', null, $_POST );
        if ( $secured == false && current_user_can( 'manage_options' ) == false ) {
            wp_send_json_error( $response );
        }

        // check for validation
        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

        if ( is_array( $post_arr ) && !empty( $post_arr ) ) {
            $user_id = 'me';

            $is_webinar_user = Helper::is_webinar_user();
            $this->meeting_sync_process( $user_id, $post_arr, $is_webinar_user );

            if ( $is_webinar_user ) {
                $this->webinar_sync_process( $user_id, $post_arr );
            }
        }
        exit;
    }

    /**
     * meeting sync mechanism
     *
     * @param string $user_id
     * @param array $post_arr
     * @return void
     */
    public function meeting_sync_process( $user_id = 'me', $post_arr = [], $is_webinar_user = false ) {
        // meetings
        $sync_type = 'meetings';
        $data    = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meetings( $user_id, $post_arr, $sync_type ) );

        if ( isset( $data->meetings ) ) {
            $db_zoom_arr = Helper::get_zoom_meetings( null, true );
            
            $db_zoom_ids        = [];
            $db_post_zoom_ids   = [];
            foreach ( $db_zoom_arr as $post_id => $zoom_data ) {
                if ( empty( $zoom_data['zoom_type'] ) || empty( $zoom_data['zoom_id'] ) ) {
                    wp_delete_post( $post_id );
                } else {
                    if ( $zoom_data['zoom_type'] == '2' ) {
                        array_push( $db_zoom_ids, $zoom_data['zoom_id'] );
                        $db_post_zoom_ids[ $post_id ] = $zoom_data['zoom_id'];
                    }
                }
            }

            $server_meetings = $data->meetings;
            if ( !empty( $server_meetings ) ) {
                foreach ( $server_meetings as $index => $single_meeting ) {
                    $meeting_id      = $single_meeting->id;
                    $meeting_details = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $meeting_id, $sync_type ) );
                    
                    $title     = isset( $meeting_details->topic ) ? $meeting_details->topic : "eting";
                    $post_slug = sanitize_title_with_dashes( $title, '', 'save' );
                    $postslug  = sanitize_title( $post_slug );

                    $agenda     = isset( $meeting_details->agenda ) ? $meeting_details->agenda : '';
                    $meta_array = [];
                    // the array of arguments to be inserted with wp_insert_post
                    if ( !in_array( $meeting_id, $db_zoom_ids ) ) {
                        $new_post = [
                            'post_title'     => $title,
                            'post_content'   => $agenda,
                            'post_status'    => 'publish',
                            'post_type'      => 'etn-zoom-meeting',
                            'comment_status' => 'closed',
                            'post_name'      => $postslug,
                        ];

                        $meta_array = [
                            'zoom_join_url'        => $meeting_details->join_url, 
                            'zoom_start_url'       => $meeting_details->start_url,
                            'zoom_meeting_id'      => $meeting_details->id,
                            'zoom_meeting_type'    => $meeting_details->type,
                            'zoom_meeting_status'  => $meeting_details->status,
                            'zoom_meeting_host'    => $meeting_details->host_id,
                            'zoom_meeting_host_id' => $meeting_details->host_id,
                        ];
                        $zoom_post_id        = wp_insert_post( $new_post );
                    } else {
                        $zoom_post_id        =  array_search( $meeting_id, $db_post_zoom_ids );

                        $update_post = [
                            'ID'            => $zoom_post_id,
                            'post_title'    => $meeting_details->topic,
                            'post_content'  => $agenda,
                        ];
                        wp_update_post( $update_post );

                        unset( $db_post_zoom_ids[ $zoom_post_id ] );
                    }

                    $meta_array             += [
                        'zoom_topic'           => $title,
                        'zoom_agenda'          => $agenda,
                        'zoom_start_time'      => $meeting_details->start_time,
                        'zoom_timezone'        => $meeting_details->timezone,
                        'zoom_duration'        => $meeting_details->duration,
                        'zoom_password'        => isset ( $meeting_details->password ) ? $meeting_details->password : $zoom_post_id,
                    ];

                    if ( isset( $meeting_details->settings ) ) {
                        $settings_data = $meeting_details->settings;
                        $meta_array    = Helper::get_zoom_meta_settings( '2', $settings_data, $meta_array );
                    }

                    if ( is_array( $meta_array ) && count( $meta_array ) > 0 && $zoom_post_id !== 0 ) {
                        foreach ( $meta_array as $key => $value ) {
                            update_post_meta( $zoom_post_id , $key , $value );
                        }
                    }
                }
            } else {
                Helper::delete_zoom_posts( $db_post_zoom_ids );
            }

            if ( !empty( $db_post_zoom_ids ) ) {
                Helper::delete_zoom_posts( $db_post_zoom_ids );
            }

            $msg = esc_html__( 'Meetings are synced successfully.', 'eventin' );
            $response = ['status_code' => 201, 'message' => [ $msg ], 'data' => $data];

            if ( !$is_webinar_user ) {
                wp_send_json_success( $response );
            }
        } else {
            $response = ['status_code' => 401, 'message' => [ esc_html__( 'something is wrong.', 'eventin') ], 'data' => []];
            wp_send_json_error( $response );
        }
    }

    /**
     * webinar sync mechanism
     *
     * @param string $user_id
     * @param array $post_arr
     * @return void
     */
    public function webinar_sync_process( $user_id = 'me', $post_arr = [] ) {
        // webinars
        $sync_type = 'webinars';
        $data    = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meetings( $user_id, $post_arr, $sync_type ) );
        
        if ( isset( $data->webinars ) ) {
            $db_zoom_arr = Helper::get_zoom_meetings( null, true );
            
            $db_zoom_ids        = [];
            $db_post_zoom_ids   = [];
            foreach ( $db_zoom_arr as $post_id => $zoom_data ) {
                if ( empty( $zoom_data['zoom_type'] ) || empty( $zoom_data['zoom_id'] ) ) {
                    wp_delete_post( $post_id );
                } else {
                    if ( $zoom_data['zoom_type'] == '5' ) {
                        array_push( $db_zoom_ids, $zoom_data['zoom_id'] );
                        $db_post_zoom_ids[ $post_id ] = $zoom_data['zoom_id'];
                    }
                }
            }

            $server_webinars = $data->webinars;
            if ( !empty( $server_webinars ) ) {
                foreach ( $server_webinars as $index => $single_meeting ) {
                    $meeting_id      = $single_meeting->id;
                    $meeting_details = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $meeting_id, $sync_type ) );
    
                    $title     = isset( $meeting_details->topic ) ? $meeting_details->topic : "Zoom webinar";
                    $post_slug = sanitize_title_with_dashes( $title, '', 'save' );
                    $postslug  = sanitize_title( $post_slug );
                    $agenda    = isset( $meeting_details->agenda ) ? $meeting_details->agenda : '';
    
                    $meta_array = [];
                    // the array of arguments to be inserted with wp_insert_post
                    if ( !in_array( $meeting_id, $db_zoom_ids ) ) {
                        $new_post = [
                            'post_title'     => $title,
                            'post_content'   => $agenda,
                            'post_status'    => 'publish',
                            'post_type'      => 'etn-zoom-meeting',
                            'comment_status' => 'closed',
                            'post_name'      => $postslug,
                        ];
    
                        $meta_array = [
                            'zoom_join_url'        => $meeting_details->join_url, 
                            'zoom_start_url'       => $meeting_details->start_url,
                            'zoom_meeting_id'      => $meeting_details->id,
                            'zoom_meeting_type'    => $meeting_details->type,
                            'zoom_meeting_status'  => isset( $meeting_details->status ) ? $meeting_details->status : 'available',
                            'zoom_meeting_host'    => $meeting_details->host_id,
                            'zoom_meeting_host_id' => $meeting_details->host_id,
                        ];
                        $zoom_post_id        = wp_insert_post( $new_post );
                    } else {
                        $zoom_post_id        =  array_search( $meeting_id, $db_post_zoom_ids );

                        $update_post = [
                            'ID'            => $zoom_post_id,
                            'post_title'    => $meeting_details->topic,
                            'post_content'  => $agenda,
                        ];
                        wp_update_post( $update_post );
                        
                        unset( $db_post_zoom_ids[ $zoom_post_id ] );
                    }
    
                    $meta_array             += [
                        'zoom_topic'           => $title,
                        'zoom_agenda'          => $agenda,
                        'zoom_start_time'      => $meeting_details->start_time,
                        'zoom_timezone'        => $meeting_details->timezone,
                        'zoom_duration'        => $meeting_details->duration,
                        'zoom_password'        => isset ( $meeting_details->password ) ? $meeting_details->password : $zoom_post_id,
                    ];
    
                    if ( isset( $meeting_details->settings ) ) {
                        $settings_data = $meeting_details->settings;
                        $meta_array = Helper::get_zoom_meta_settings( '5', $settings_data, $meta_array );
                    }
    
                    if ( is_array( $meta_array ) && count( $meta_array ) > 0 && $zoom_post_id !== 0 ) {
                        foreach ( $meta_array as $key => $value ) {
                            update_post_meta( $zoom_post_id , $key , $value );
                        }
                    }
                }
            } else {
                Helper::delete_zoom_posts( $db_post_zoom_ids );
            }

            if ( !empty( $db_post_zoom_ids ) ) {
                Helper::delete_zoom_posts( $db_post_zoom_ids );
            }

            $msg = esc_html__( 'Meetings and Webinars are synced successfully.', 'eventin' );
            $response = ['status_code' => 201, 'message' => [ $msg ], 'data' => $data];
            wp_send_json_success( $response );
        } else {
            $response = ['status_code' => 401, 'message' => [ esc_html__( 'something is wrong.', 'eventin') ], 'data' => []];
            wp_send_json_error( $response );
        }
    }
}
