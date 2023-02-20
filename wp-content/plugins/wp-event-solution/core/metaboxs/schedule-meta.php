<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Schedule_meta extends Event_manager_metabox {
    public $metabox_id      = 'etn_schedule_settings';
    public $schedule_fields = [];
    public $cpt_id          = 'etn-schedule';
    
    public function register_meta_boxes() {
        add_meta_box( 
            $this->metabox_id, 
            esc_html__( 'Schedule Information', 'eventin' ), 
            [$this, 'display_callback'], 
            $this->cpt_id 
        );
    }

    /**
     * Input fields array for speaker meta
     *
     * @return void
     */
    public function etn_schedule_meta_fields() {
        $this->schedule_fields = [
            'etn_schedule_title'  => [
                'label'    => esc_html__( 'Title', 'eventin' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Place schedule title', 'eventin' ),
                'priority' => 1,
                'attr'     => ['class' => 'etn-label-item'],
                'placeholder' => esc_html__( 'Title here', 'eventin' ),
                'required' => true,
                'group'    => 'etn-label-group',
            ],
            'etn_schedule_date'   => [
                'label'     => esc_html__( 'Date', 'eventin' ),
                'desc'      => esc_html__( 'Select schedule date', 'eventin' ),
                'type'      => 'date',
                'inline'    => false,
                'timestamp' => false,
                'priority'  => 1,
                'attr'      => [
                    'class' => 'etn-label-item etn-label-date etn-date',
                    'icon' => '<svg class="date-icon" width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.4004 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.40039 6.67188H15.0004" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.4 6.2002V13.0002C15.4 15.4002 14.2 17.0002 11.4 17.0002H5C2.2 17.0002 1 15.4002 1 13.0002V6.2002C1 3.8002 2.2 2.2002 5 2.2002H11.4C14.2 2.2002 15.4 3.8002 15.4 6.2002Z" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.19639 10.36H8.20357" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.23545 10.36H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.23545 12.7604H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    ',
                ],
                'placeholder' => esc_html__( 'Pick date', 'eventin' ),
                'required'  => true,
                'group'     => 'etn-label-group',
            ],
            'etn_schedule_day'    => [
                'label'    => esc_html__( 'Week Day', 'eventin' ),
                'type'     => 'select_single',
                'multiple' => false,
                'options'  => Helper::day_name(),
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Name of the day of week', 'eventin' ),
                'priority' => 1,
                'attr'     => ['class' => 'etn-label-item'],
                'required' => true,
                'group'     => 'etn-label-group',
            ],
            'etn_schedule_sorting' => [
                'label'    => esc_html__( 'Sorting order', 'eventin' ),
                'type'     => 'hidden',
                'default'  => '',
                'value'    => '',
                'desc'     => "",
                'priority' => 1,
                'attr'     => ['class' => 'etn-label-item'],
                'required' => true,
            ],
            'etn_schedule_topics' => [
                'label'    => esc_html__( 'Schedule List', 'eventin' ),
                'type'     => 'repeater',
                'default'  => '',
                'value'    => '',
                'options'  => [
    
                    'etn_schedule_topic'     => [
                        'label'    => esc_html__( 'Topic', 'eventin' ),
                        'type'     => 'text',
                        'default'  => '',
                        'value'    => '',
                        'desc'     => esc_html__( 'Place schedule topic', 'eventin' ),
                        'priority' => 1,
                        'attr'     => ['class' => ''],
                        'placeholder' => esc_html__( 'Topic here', 'eventin' ),
                        'required' => true,
                    ],
                    'etn_shedule_start_time' => [
                        'label'    => esc_html__( 'Start Time', 'eventin' ),
                        'type'     => 'time',
                        'default'  => '',
                        'value'    => '',
                        'desc'     => esc_html__( 'Select start time ', 'eventin' ),
                        'priority' => 1,
                        'placeholder' => esc_html__( 'Pick a time', 'eventin' ),
                        'attr'     => [
                            'class' => 'etn-label-date',
                            'icon' => '<svg class="date-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 9C17 13.416 13.416 17 9 17C4.584 17 1 13.416 1 9C1 4.584 4.584 1 9 1C13.416 1 17 4.584 17 9Z" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.9671 11.5442L9.48712 10.0642C9.05512 9.8082 8.70312 9.1922 8.70312 8.6882V5.4082" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
						    ',
                        ],
                        'required' => true,
                    ],
                    'etn_shedule_end_time'   => [
                        'label'    => esc_html__( 'End Time', 'eventin' ),
                        'type'     => 'time',
                        'default'  => '',
                        'value'    => '',
                        'desc'     => esc_html__( 'Select end time ', 'eventin' ),
                        'priority' => 1,
                        'placeholder' => esc_html__( 'Pick a time', 'eventin' ),
                        'attr'     => [
                            'class' => 'etn-label-date',
                            'icon' => '<svg class="date-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 9C17 13.416 13.416 17 9 17C4.584 17 1 13.416 1 9C1 4.584 4.584 1 9 1C13.416 1 17 4.584 17 9Z" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.9671 11.5442L9.48712 10.0642C9.05512 9.8082 8.70312 9.1922 8.70312 8.6882V5.4082" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
						    ',
                        ],
                        'required' => true,
                    ],
                    'etn_shedule_room'       => [
                        'label'    => esc_html__( 'Location', 'eventin' ),
                        'type'     => 'text',
                        'default'  => '',
                        'value'    => '',
                        'desc'     => esc_html__( 'Place location here ', 'eventin' ),
                        'priority' => 1,
                        'attr'     => ['class' => ''],
                        'placeholder' => esc_html__( 'Location', 'eventin' ),
                        'required' => true,
                    ],
                    'etn_shedule_speaker'    => [
                        'label'    => esc_html__( 'Speaker', 'eventin' ),
                        'type'     => 'select2',
                        'multiple' => true,
                        'default'  => '',
                        'value'    => '',
                        'options'  => Helper::get_speakers(),
                        'priority' => 1,
                        'desc'     => esc_html__( 'Select speaker ', 'eventin' ),
                        'required' => true,
                        'attr'     => ['class' => 'etn-event-speakers-section etn-label-top'],
                        'warning'       => esc_html__('Create New Speaker', 'eventin'),
                        'warning_url'   => admin_url( 'post-new.php?post_type=etn-speaker' )
                    ],
                    'etn_shedule_objective'  => [
                        'label'    => esc_html__( 'Details', 'eventin' ),
                        'type'     => 'textarea',
                        'default'  => '',
                        'value'    => '',
                        'desc'     => esc_html__( 'Place some details / overview of this slot / schedule.', 'eventin' ),
                        'attr'     => [
                            'class' => 'schedule etn-label-top',
                            'row'   => 30,
                            'col'   => 50,
                        ],
                        'settings' => [],
                        'priority' => 1,
                        'placeholder' => esc_html__( 'Write Text', 'eventin' ),
                        'required' => true,
                    ],
                ],
                'desc'     => '',
                'attr'     => ['class' => ''],
                'priority' => 1,
                'required' => true,
            ],
        ];

        

        return $this->schedule_fields;
    }

    public function banner_meta_field(){
        return [];
    }

}
