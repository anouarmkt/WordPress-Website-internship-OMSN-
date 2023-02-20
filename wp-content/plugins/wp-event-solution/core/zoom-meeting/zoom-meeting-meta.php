<?php

namespace Etn\Core\Zoom_Meeting;

use Etn\Core\Metaboxs\Event_manager_metabox;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Zoom_Meeting_Meta extends Event_manager_metabox {
    public $metabox_id   = 'etn_zoom_meeting_settings';
    public $event_fields = [];
    public $cpt_id       = 'etn-zoom-meeting';

    public function register_meta_boxes() {
        add_meta_box( 
            $this->metabox_id, 
            esc_html__( 'Meeting Information', 'eventin' ), 
            [$this, 'display_callback'], 
            $this->cpt_id 
        );
    }

    /**
     * metabox data function
     *
     * @return void
     */
    public function etn_zoom_meeting_meta_fields() {
        global $post;
        $zoom_type  = get_post_meta( $post->ID, 'zoom_meeting_type', true );
        $zoom_types = Helper::zoom_types();

        if ( !empty( $zoom_type ) ) {
            if ( $zoom_type == '2' ) {
                unset( $zoom_types['5'] );
            } else { 
                unset( $zoom_types['2'] ); 
            }
        }

        if ( isset( $zoom_types['5']  ) ) {
            if ( !Helper::is_webinar_user() ) {
                unset( $zoom_types['5'] );
            }
        }

        // get host list
        $user_list = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->zoom_meeting_user_list();
        
        // get time zone
        $time_zone = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_timezone();

        $this->event_fields = [
            'zoom_meeting_type' => [
                'label'    => esc_html__( 'Meeting Type', 'eventin' ),
                'desc'     => esc_html__( 'Note: Webinar requires webinar plan enabled in your account', 'eventin' ),
                'type'     => 'select_single',
                'options'  => $zoom_types,
                'priority' => 1,
                'required' => true,
                'attr'     => ['class' => 'etn-label-item etn-zoom-meeting-type','tab'=>'general_settings'],
            ],
            'zoom_meeting_id'   => [
                'label'    => esc_html__( 'Meeting ID', 'eventin' ),
                'desc'     => esc_html__( 'Will be generated automatically after creation', 'eventin' ),
                'type'     => 'text',
                'value'    => "",
                'priority' => 1,
                'readonly' => true,
                'disabled' => true,
                'placeholder' => esc_html__( 'Enter meeting ID', 'eventin' ),
                'attr'     => ['class' => 'etn-label-item','tab'=>'general_settings'],
            ],
            'zoom_meeting_host' => [
                'label'    => esc_html__( 'Meeting Host', 'eventin' ),
                'desc'     => esc_html__( 'Select a host of meeting.(Required)', 'eventin' ),
                'type'     => 'select_single',
                'options'  => $user_list,
                'priority' => 1,
                'required' => true,
                'attr'     => ['class' => 'etn-label-item','tab'=>'general_settings'],
            ],
            'zoom_start_time'   => [
                'label'    => esc_html__( 'Start Date/Time', 'eventin' ),
                'desc'     => esc_html__( 'Select start date and time.(Required)', 'eventin' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'priority' => 1,
                'required' => true,
                'placeholder' => esc_html__( 'Pick a Date', 'eventin' ),
                'attr'     => [
                    'class' => 'etn-label-item etn-label-date',
                    'icon' => '<svg class="date-icon" width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11.4004 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M1.40039 6.67188H15.0004" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.4 6.2002V13.0002C15.4 15.4002 14.2 17.0002 11.4 17.0002H5C2.2 17.0002 1 15.4002 1 13.0002V6.2002C1 3.8002 2.2 2.2002 5 2.2002H11.4C14.2 2.2002 15.4 3.8002 15.4 6.2002Z" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.19639 10.36H8.20357" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.23545 10.36H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.23545 12.7604H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>',
                    'tab'=>'time_settings'
                ],
            ],
            'zoom_timezone'     => [
                'label'    => esc_html__( 'Time Zone', 'eventin' ),
                'desc'     => esc_html__( 'Select timezone for meeting .(Optional)', 'eventin' ),
                'type'     => 'select_single',
                'options'  => $time_zone,
                'priority' => 1,
                'required' => false,
                'attr'     => ['class' => 'etn-label-item','tab'=>'time_settings'],
            ],
            'zoom_duration'     => [
                'label'    => esc_html__( 'Duration', 'eventin' ),
                'desc'     => esc_html__( 'Meeting duration (minutes).(Optional)', 'eventin' ),
                'type'     => 'number',
                'default'  => '60',
                'value'    => '60',
                'priority' => 1,
                'required' => false,
                'min'      => 1,
                'placeholder' => esc_html__( '0', 'eventin' ),
                'attr'     => ['class' => 'etn-label-item','tab'=>'time_settings'],
            ],
            'zoom_password'     => [
                'label'    => esc_html__( 'Password', 'eventin' ),
                'desc'     => esc_html__( 'Max of 10 characters.( Leave blank for auto generate )', 'eventin' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'priority' => 1,
                'required' => false,
                'placeholder' => esc_html__( 'Enter password', 'eventin' ),
                'attr'     => ['class' => 'etn-label-item','tab'=>'general_settings'],
            ],

            // common for meeting and webinar
            'zoom_meeting_authentication' => [
                'label'        => esc_html__( 'Require authentication to join', 'eventin' ),
                'desc'         => esc_html__( 'Only authenticated users can join', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-authentication','tab'=>'options'],
            ],
            'zoom_host_video' => [
                'label'        => esc_html__( 'Host Video', 'eventin' ),
                'desc'         => esc_html__( 'Auto on/off host video', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-host-video','tab'=>'options'],
            ],
            'zoom_participant_panelists_video' => [
                'label'        => esc_html__( 'Participant/Panelists Video', 'eventin' ),
                'desc'         => esc_html__( 'Auto on/off participant video', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-participant-video','tab'=>'options'],
            ],
            'zoom_auto_recording' => [
                'label'    => esc_html__( 'Auto Recording', 'eventin' ),
                'desc'     => esc_html__( 'Automatically record meeting on', 'eventin' ),
                'type'     => 'select_single',
                'options'  => [ 
                    'none'  => esc_html__( 'None', 'eventin' ), 
                    'local' => esc_html__( 'Local', 'eventin' ), 
                    'cloud' => esc_html__( 'Cloud', 'eventin' ) 
                ],
                'priority' => 1,
                'required' => true,
                'attr'     => ['class' => 'etn-label-item etn-zoom-auto-recording','tab'=>'options'],
            ],
        ];

        $meeting_meta_fields = [
            'zoom_waiting_room' => [
                'label'        => esc_html__( 'Waiting Room', 'eventin' ),
                'desc'         => esc_html__( 'Only users admitted by the host can join the meeting', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-meeting-field etn-zoom-waiting-room','tab'=>'options']
            ],
            'zoom_join_before_host' => [
                'label'        => esc_html__( 'Join anytime', 'eventin' ),
                'desc'         => esc_html__( 'Allow participants to join anytime', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-meeting-field etn-zoom-join-before-host','tab'=>'options'],
            ],
            'zoom_mute_upon_entry' => [
                'label'        => esc_html__( 'Mute participants upon entry', 'eventin' ),
                'desc'         => esc_html__( 'Mutes participants when entering to the meeting', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-meeting-field etn-zoom-mute-entry','tab'=>'options'],
            ],
        ];

        $webinar_meta_fields = [
            'zoom_question_and_answer' => [
                'label'        => esc_html__( 'Question & Answer', 'eventin' ),
                'desc'         => esc_html__( 'Enable Question & Answer', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'default'      => 'on',
                'value'        => 'on',
                'attr'         => ['class' => 'etn-label-item etn-zoom-webinar-field', 'tab'=>'options'],
            ],
            'zoom_practice_session' => [
                'label'        => esc_html__( 'Practice Session', 'eventin' ),
                'desc'         => esc_html__( 'Enable Practice Session', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-webinar-field', 'tab'=>'options'],
            ],
            'zoom_hd_video' => [
                'label'        => esc_html__( 'HD video for Host/Panelists', 'eventin' ),
                'desc'         => esc_html__( 'Enable HD video for Host/Panelists', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-webinar-field', 'tab'=>'options'],
            ],
            'zoom_hd_video_for_attendees' => [
                'label'        => esc_html__( 'HD video for attendees', 'eventin' ),
                'desc'         => esc_html__( 'Enable HD video for attendees', "eventin" ),
                'type'         => 'checkbox',
                'left_choice'  => 'On',
                'right_choice' => 'Off',
                'attr'         => ['class' => 'etn-label-item etn-zoom-webinar-field', 'tab'=>'options'],
            ],
        ];

        if ( !empty( $zoom_type ) ) {
            if ( $zoom_type == '2' ) {
                $this->event_fields += $meeting_meta_fields;
            } else if ( $zoom_type == '5' ){ 
                $this->event_fields += $webinar_meta_fields;
            }
        } else {
            $this->event_fields = $this->event_fields + $meeting_meta_fields + $webinar_meta_fields;
        }
        
        $tab_items = $this->get_tab_pane( $this->event_fields );
        return [ 'fields' => $this->event_fields , 'tab_items' => $tab_items , 'display' => 'tab' ];
    }

    /**
     * Get tab pane array
     */

    public function get_tab_pane( $settings ){
        $tab_items = [
            [
                'name'=>esc_html__('General Settings','eventin'),
                'id'  => 'general_settings', 
                'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',   
            ],          
            [
                'name'=>esc_html__('Time settings','eventin'),
                'id'  => 'time_settings',
                'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
            ],
            [
                'name'=>esc_html__('Options','eventin'),
                'id'  => 'options',
                'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
            ]            
        ];

        return $tab_items;
    }

    public function banner_meta_field() {
        return [];
    }

    /**
     * Save metabox data and call api function
     */
    public function save_zoom_meta_data( $data, $postarr ) {
      
        if ( 'etn-zoom-meeting' == $data['post_type'] && is_array( $postarr ) && isset( $postarr['zoom_meeting_host'] ) ) {
            $request_data               = [];
            $meeting_id                 = get_post_meta( $postarr['ID'], 'zoom_meeting_id', true );
            $meeting_type               = !empty ( $postarr['zoom_meeting_type'] ) ? sanitize_text_field( $postarr['zoom_meeting_type'] ) : '2';

            if ( $meeting_type == '5' && !Helper::is_webinar_user() ) {
                return $data;
            }

            $request_data['type']       = $meeting_type;
            $request_data['topic']      = !empty( $postarr['post_title'] ) ? sanitize_text_field( $postarr['post_title'] ) : 'Zoom ' . Helper::zoom_types()[$meeting_type];
            $request_data['agenda']     = sanitize_text_field( $postarr['post_content'] );
            $request_data['start_time'] = sanitize_text_field( $postarr['zoom_start_time'] );
            $request_data['timezone']   = sanitize_text_field( $postarr['zoom_timezone'] );
            $request_data['duration']   = sanitize_text_field( $postarr['zoom_duration'] );
            $request_data['password']   = !empty($postarr['zoom_password'] ) ? sanitize_text_field( $postarr['zoom_password'] ) : $postarr['ID'];
            
            // on no
            $zoom_host_video                  = sanitize_text_field( $postarr['zoom_host_video'] );
            $zoom_participant_panelists_video = sanitize_text_field( $postarr['zoom_participant_panelists_video'] );
            $zoom_meeting_authentication      = sanitize_text_field( $postarr['zoom_meeting_authentication'] );
            $zoom_auto_recording              = sanitize_text_field( $postarr['zoom_auto_recording'] );

            $meeting_authentication = $host_video = $participant_video = $panelists_video = false;
            if ( $zoom_participant_panelists_video == 'on' ) {
                if ( $meeting_type == '2' ) {
                    $participant_video = true;
                } else if ( $meeting_type == '5' ) {
                    $panelists_video = true;
                }
            }

            $host_video                             = ( $zoom_host_video == 'on' ) ? true : false;
            $meeting_authentication                 = ( $zoom_meeting_authentication == 'on' ) ? true : false;
            $request_data['host_video']             = $host_video;
            $request_data['meeting_authentication'] = $meeting_authentication;
            $request_data['auto_recording']         = $zoom_auto_recording;

            // meeting extra
            if ( $meeting_type == '2' ) {
                $request_data['participant_video']  = $participant_video;

                $zoom_waiting_room                = sanitize_text_field( $postarr['zoom_waiting_room'] );
                $zoom_join_before_host            = sanitize_text_field( $postarr['zoom_join_before_host'] );
                $zoom_mute_upon_entry             = sanitize_text_field( $postarr['zoom_mute_upon_entry'] );

                $waiting_room       = ( $zoom_waiting_room == 'on' ) ? true : false;
                $join_before_host   = ( $zoom_join_before_host == 'on' ) ? true : false;
                $mute_upon_entry    = ( $zoom_mute_upon_entry == 'on' ) ? true : false;

                $request_data['waiting_room']       = $waiting_room;
                $request_data['join_before_host']   = $join_before_host;
                $request_data['mute_upon_entry']    = $mute_upon_entry;
            } else if ( $meeting_type == '5' ) { // webinar extra
                $request_data['panelists_video']    = $panelists_video;
                
                $zoom_question_and_answer         = sanitize_text_field( $postarr['zoom_question_and_answer'] );
                $zoom_practice_session            = sanitize_text_field( $postarr['zoom_practice_session'] );
                $zoom_hd_video                    = sanitize_text_field( $postarr['zoom_hd_video_for_attendees'] );
                $zoom_hd_video_for_attendees      = sanitize_text_field( $postarr['zoom_hd_video_for_attendees'] );
                    
                $hd_video               = ( $zoom_hd_video == 'on' ) ? true : false;
                $hd_video_for_attendees = ( $zoom_hd_video_for_attendees == 'on' ) ? true : false;
                $question_and_answer    = ( $zoom_question_and_answer == 'on' ) ? true : false;
                $practice_session       = ( $zoom_practice_session == 'on' ) ? true : false;
                
                $request_data['hd_video']               = $hd_video;
                $request_data['hd_video_for_attendees'] = $hd_video_for_attendees;
                $request_data['question_and_answer']    = $question_and_answer;
                $request_data['practice_session']       = $practice_session;
            }

            $meta_array                 = [];
            if ( empty( $meeting_id ) ) {
                $request_data['user_id'] = sanitize_text_field( $postarr['zoom_meeting_host'] );

                // create meeting
                $meeting_data = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->create_meeting( $request_data ) );
          
                if ( is_object( $meeting_data ) ) {
                    $zoom_status = '';
                    
                    if ( isset( $meeting_data->status ) ) {
                        $zoom_status = $meeting_data->status;
                    } else if ( isset( $meeting_data->occurrences ) ) {
                        $zoom_status = $meeting_data->occurrences->status;
                    }
                    
                    $meta_array = [
                        'zoom_join_url'        => $meeting_data->join_url,
                        'zoom_start_url'       => $meeting_data->start_url,
                        'zoom_meeting_id'      => $meeting_data->id,
                        'zoom_meeting_type'    => $meeting_data->type,
                        'zoom_meeting_status'  => $zoom_status,
                        'zoom_meeting_host_id' => $meeting_data->host_id,
                        'zoom_topic'           => $meeting_data->topic,
                        'zoom_agenda'          => isset( $meeting_data->agenda ) ? $meeting_data->agenda : '',
                        'zoom_start_time'      => $meeting_data->start_time,
                        'zoom_timezone'        => $meeting_data->timezone,
                        'zoom_duration'        => !empty( $meeting_data->duration ) ? $meeting_data->duration : 60,
                        'zoom_password'        => isset( $meeting_data->password ) ? $meeting_data->password : $postarr['ID'],
                    ];
                }

            } else {
                $request_data['meeting_id'] = $meeting_id;
                // update meeting
                $meeting_data               = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->create_meeting( $request_data ) );
                        
                $meta_array                 = [
                    'zoom_topic'      => $request_data['topic'],
                    'zoom_agenda'     => $request_data['agenda'],
                    'zoom_start_time' => $request_data['start_time'],
                    'zoom_timezone'   => $request_data['timezone'],
                    'zoom_duration'   => !empty( $request_data['duration'] ) ? $request_data['duration'] : 60,
                    'zoom_password'   => isset( $request_data['password'] ) ? $request_data['password'] : $postarr['ID'],
                ];
            }

            if ( is_array( $meta_array ) && count( $meta_array ) > 0 ) {
                foreach ( $meta_array as $key => $value ) {
                    update_post_meta( $postarr['ID'], $key, $value );
                }
            }

            if ( is_object( $meeting_data ) && !empty( $meeting_data->code ) ) {
                if ( $meeting_data->code === 429 ) {
                    $_SESSION['zoom_meeting_notice'] = $meeting_data->message;
                }
            }

        }

        return $data;
    }

    /**
     * call zoom meeting delete api when post is deleted
     *
     * @param [type] $post_id
     * @param [type] $post
     * @return void
     */
    public function delete_zoom_meeting( $post_id, $post ) {
        if ( 'etn-zoom-meeting' == $post->post_type ) {
            $meeting_id          = get_post_meta( $post_id, 'zoom_meeting_id', true );
            $meeting_type_number = get_post_meta( $post_id, 'zoom_meeting_type', true );
            
            if ( !empty( $meeting_id ) ) {
                $request_data['meeting_id'] = $meeting_id;

                $meeting_type = '';
                if ( $meeting_type_number == '2' ) {
                    $meeting_type = 'meetings';
                } else if ( $meeting_type_number == '5' ) {
                    $meeting_type = 'webinars';
                }
                
                // will optimize
                if ( !empty( $meeting_type ) ) {
                    if ( $meeting_type_number == '2' ) {
                        $delete_response = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->delete_meeting( $request_data, $meeting_type ) );
                    } else {
                        if ( $meeting_type_number == '5' && Helper::is_webinar_user() ) {
                            $delete_response = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->delete_meeting( $request_data, $meeting_type ) );
                        }
                    }
                    
                }
            }
        }
    }

    public function admin_notices() {

        if ( !empty( $_SESSION['zoom_meeting_notice'] ) ) {
            ?>
            <div class="alert alert-warning"><?php echo esc_html__( $_SESSION['zoom_meeting_notice'], 'eventin' ) ?> </div>
            <?php
            session_destroy();
        }

    }

    /**
     * Disable gutenberg for zoom meeting
     */
    public function disable_gutenberg( $is_enabled, $post_type ) {
        if ($post_type === 'etn-zoom-meeting') return false; 
        return $is_enabled;
    }

}
