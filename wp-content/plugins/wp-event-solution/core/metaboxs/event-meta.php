<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;
class Event_meta extends Event_manager_metabox {

	public $report_box_id = '';
	public $event_fields  = [];
	public $cpt_id        = 'etn';
	public $text_domain   = 'eventin';

	public function register_meta_boxes() {
		$metabox_array = array(
					'etn_event_settings'    => array(
					'label'     => esc_html__( 'Event Settings', 'eventin' ),
					'callback'  => 'display_callback',
					'cpt_id'    => $this->cpt_id,
					'display_type' => 'tab',
					),
					'etn_report'=> array(
					'label'     => esc_html__( 'Order Report', 'eventin' ),
					'callback'  => 'etn_report_callback',
					'cpt_id'    => $this->cpt_id,
					'display_type' => 'list',
					),
		);

		$is_child_post  = wp_get_post_parent_id( get_the_ID() ) ? true : false;
		// add new meta box.
		$all_boxes      = apply_filters( 'etn/metaboxs/etn_metaboxs', $metabox_array );
		$all_boxes      = $this->filter_meta_boxes_for_parent_child( $all_boxes, $is_child_post );

			foreach( $all_boxes as $box_id => $metabox ){
				$instance       = !empty( $metabox["instance"] ) ? $metabox["instance"] : $this;
				add_meta_box(
					$box_id,
					esc_html__( $metabox['label'], 'eventin' ),
					[ $instance, $metabox['callback'] ],
					$metabox['cpt_id']
				);
		}
	}

	/**
	 * Input fields array for event meta
	 */
	public function etn_meta_fields() {
		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

		$default_fields = [
			
			'etn_event_schedule'        => [
				'label'         => esc_html__( 'Event Schedule', 'eventin' ),
				'desc'          => esc_html__( 'Select all schedules created for this event', 'eventin' ),
				'placeholder'   => esc_html__( 'Select Schedule', 'eventin' ),
				'type'          => 'select2',
				'options'       => Helper::get_schedules(),
				'priority'      => 1,
				'required'      => true,
				'attr'          => ['class' => 'etn-label-item etn-label-top' ,'tab' => 'general_settings'],
				'warning'       => esc_html__('Create Schedules', 'eventin'),
				'warning_url'   => admin_url( 'post-new.php?post_type=etn-schedule' )
			],
			'etn_event_organizer'       => [
				'label'    => esc_html__( 'Organizers', 'eventin' ),
				'desc'     => esc_html__( 'Select speaker category which will be used as organizer', 'eventin' ),
				'type'     => 'select_single',
				'options'  => Helper::get_orgs(),
				'priority' => 1,
				'required' => true,
				'attr'     => ['class' => 'etn-label-item etn-label-top' ,'tab' => 'general_settings'],
				'warning'       => esc_html__('Create Organizer Category', 'eventin'),
				'warning_url'   => admin_url( 'edit-tags.php?taxonomy=etn_speaker_category&post_type=etn-speaker' )
			]
		];

		if( !class_exists('Wpeventin_Pro') ) {
			$default_fields['etn_event_location']  = [
				'label'    => esc_html__( 'Event Location', 'eventin' ),
				'desc'     => esc_html__( 'Place event location', 'eventin' ),
				'type'     => 'text',
				'default'  => '',
				'placeholder' => esc_html__( 'Location', 'eventin' ),
				'value'    => '',
				'priority' => 1,
				'required' => true,
				'attr'     => ['class' => 'etn-label-item pro-dis-item' , 'tab' => 'locations'],
			];
		}

		$default_meta_fields = apply_filters( 'etn_event_meta_fields_after_default', $default_fields );

		$event_date_fields['etn_start_date'] = [
			'label'    => esc_html__( 'Event Start Date', 'eventin' ),
			'desc'     => esc_html__( 'Select event start date', 'eventin' ),
			'type'     => 'text',
			'default'  => '',
			'value'    => '',
			'priority' => 1,
			'placeholder' => esc_html__( 'Pick a Date', 'eventin' ),
			'required' => false,
			'attr'     => [
					'class' => 'etn-label-item etn-label-date etn-date' ,
					'tab' => 'date_time',
					'icon' => '<svg class="date-icon" width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M11.4004 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M1.40039 6.67188H15.0004" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M15.4 6.2002V13.0002C15.4 15.4002 14.2 17.0002 11.4 17.0002H5C2.2 17.0002 1 15.4002 1 13.0002V6.2002C1 3.8002 2.2 2.2002 5 2.2002H11.4C14.2 2.2002 15.4 3.8002 15.4 6.2002Z" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M8.19639 10.36H8.20357" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5.23545 10.36H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5.23545 12.7604H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>',
				],
			
		];
		$event_date_fields['etn_end_date'] = [
				'label'    => esc_html__( 'Event End Date', 'eventin' ),
				'type'     => 'text',
				'default'  => '',
				'value'    => '',
				'desc'     => esc_html__( 'Select end date', 'eventin' ),
				'priority' => 1,
				'placeholder' => esc_html__( 'Pick a Date', 'eventin' ),
				'required' => false,
				'attr'     => [
					'class' => 'etn-label-item etn-label-date etn-date' ,
					'tab' => 'date_time',
					'icon' => '<svg class="date-icon" width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M11.4004 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M1.40039 6.67188H15.0004" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M15.4 6.2002V13.0002C15.4 15.4002 14.2 17.0002 11.4 17.0002H5C2.2 17.0002 1 15.4002 1 13.0002V6.2002C1 3.8002 2.2 2.2002 5 2.2002H11.4C14.2 2.2002 15.4 3.8002 15.4 6.2002Z" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M8.19639 10.36H8.20357" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5.23545 10.36H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5.23545 12.7604H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>',
				],
		];
		
		$event_time_fields['event_timezone'] = [
			'label'    => esc_html__( 'Timezone', 'eventin' ),
			'type'     => 'timezone',
			'default'  => '',
			'desc'     => esc_html__( 'Event will be held on this time-zone', 'eventin' ),
			'value'    => '',
			'priority' => 1,
			'required' => false,
			'attr'     => ['class' => 'etn-label-item' ,'tab' => 'date_time'],
		];
		$event_time_fields['etn_start_time'] = [
				'label'    => esc_html__( 'Event Start Time', 'eventin' ),
				'desc'     => esc_html__( 'Select start time', 'eventin' ),
				'type'     => 'time',
				'default'  => '',
				'value'    => '',
				'priority' => 1,
				'placeholder' => esc_html__( 'Pick a time', 'eventin' ),
				'required' => false,
				'attr'     => [
							'class' => 'etn-label-item etn-label-date' ,
							'icon' => '<svg class="date-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M17 9C17 13.416 13.416 17 9 17C4.584 17 1 13.416 1 9C1 4.584 4.584 1 9 1C13.416 1 17 4.584 17 9Z" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M11.9671 11.5442L9.48712 10.0642C9.05512 9.8082 8.70312 9.1922 8.70312 8.6882V5.4082" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						',
				'tab' => 'date_time'],
		];
		$event_time_fields['etn_end_time'] = [
				'label'    => esc_html__( 'Event End Time', 'eventin' ),
				'type'     => 'time',
				'default'  => '',
				'desc'     => esc_html__( 'Select end time', 'eventin' ),
				'value'    => '',
				'priority' => 1,
				'placeholder' => esc_html__( 'Pick a time', 'eventin' ),
				'required' => false,
				'attr'     => [
							'class' => 'etn-label-item etn-label-date' ,
							'icon' => '<svg class="date-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M17 9C17 13.416 13.416 17 9 17C4.584 17 1 13.416 1 9C1 4.584 4.584 1 9 1C13.416 1 17 4.584 17 9Z" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M11.9671 11.5442L9.48712 10.0642C9.05512 9.8082 8.70312 9.1922 8.70312 8.6882V5.4082" stroke="#0D165E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>',
							'tab' => 'date_time',
						],
		];


		$time_meta_fields = apply_filters( 'etn_event_meta_fields_after_time', $event_time_fields );
 
		if( !empty( $settings['sell_tickets'] ) && class_exists('WooCommerce') ){
			$event_date_fields['etn_registration_deadline'] = [
					'label'    => esc_html__( 'Registration Deadline', 'eventin' ),
					'type'     => 'text',
					'default'  => '',
					'desc'     => esc_html__( 'Select event registration deadline', 'eventin' ),
					'value'    => '',
					'priority' => 1,
					'placeholder' => esc_html__( 'Pick a Date', 'eventin' ),
					'required' => false,
					'attr'     => [
						'class' => 'etn-label-item etn-label-date etn-date',
						'icon' => '<svg class="date-icon" width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.4004 1V3.4" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.40039 6.67188H15.0004" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.4 6.2002V13.0002C15.4 15.4002 14.2 17.0002 11.4 17.0002H5C2.2 17.0002 1 15.4002 1 13.0002V6.2002C1 3.8002 2.2 2.2002 5 2.2002H11.4C14.2 2.2002 15.4 3.8002 15.4 6.2002Z" stroke="#0D165E" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.19639 10.36H8.20357" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.23545 10.36H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.23545 12.7604H5.24264" stroke="#0D165E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>',
						'tab' => 'date_time'
					],
			];
		}
		$date_meta_fields = apply_filters( 'etn_event_meta_fields_after_date', $event_date_fields );

		$event_recurring_fields['recurring_enabled'] = [
			'label'        => esc_html__( 'Recurring Event', 'eventin' ),
			'desc'         => esc_html__( 'Set this event as a recurring event', "eventin" ),
			'type'         => 'checkbox',
			'left_choice'  => 'yes',
			'right_choice' => 'no',
			'attr'         => ['class' => 'etn-label-item etn-enable-recurring-event' ,'tab' => 'recurring'],
			'conditional'  => true,
			'condition-id' => 'etn_event_recurrence',
		];

		$event_recurring_fields['etn_event_recurrence'] = [
			'label'        => esc_html__( 'Set recurrence', 'eventin' ),
			'desc'         => esc_html__( 'Set condition for recurrences. Must select event start date and event end date. Otherwise this feature won\'t work', "eventin" ),
			'type'         => 'recurrence_block',
			'attr'         => ['class' => 'etn-label-item etn-recurrence-item set_recurrence_item' ,'tab' => 'recurring'],
		];

		$recurring_meta_fields = apply_filters( 'etn_event_meta_fields_after_recurring', $event_recurring_fields );

		$wc_meta_fields = [];
		if( !empty( $settings['sell_tickets'] ) && class_exists('WooCommerce') ){
			$event_wc_fields['_virtual'] = [
				'label'        => esc_html__( 'Virtual Product', 'eventin' ),
				'desc'         => esc_html__( 'Register event as WooCommerce virtual product and let WooCommerce handle its behvaiour.', "eventin" ),
				'type'     => 'select_single',
				'priority' => 1,
				'options'       => [
					''      => '',
					'yes'   =>'yes',
					'no'   =>'no',
				],
				'attr'     => ['class' => 'etn-label-item','tab' => 'general_settings'],
			];
			$wc_meta_fields = apply_filters( 'etn_event_meta_fields_after_wc', $event_wc_fields );
		}
		if( !empty( $settings['sell_tickets'] ) && class_exists('WooCommerce') && ( 'yes' == get_option( 'woocommerce_calc_taxes' ) )){
			$event_wc_fields['_tax_status'] = [
				'label'        => esc_html__( 'Tax Status', 'eventin' ),
				'desc'         => esc_html__( 'Set if you want to enable Woocommerce Tax on this event. First you need to enable tax calculation from Woocommerce. After that, if you turn on tax status, then Standard Tax Rates will be applied on this event', "eventin" ),
				'type'     => 'select_single',
				'options'       => [
					''      => '',
					'taxable'   =>'yes',
					'none'      =>'no',
				],
				'attr'     => ['class' => 'etn-label-item' ,'tab' => 'ticket'],
			];
			$wc_meta_fields = apply_filters( 'etn_event_meta_fields_after_wc', $event_wc_fields );
		}

        //***************************** Ticket Management *********************** */
        $event_ticket_fields = [];
        $event_ticket_fields['etn_ticket_availability'] = [
            'label'        => esc_html__( 'Limited Tickets', 'eventin' ),
            'desc'         => esc_html__( 'Enable limited ticket. Set ticket stock from ticket variation.', "eventin" ),
            'type'         => 'checkbox',
            'left_choice'  => 'limited',
            'right_choice' => 'unlimited',
            'attr'         => ['class' => 'etn-label-item etn-limit-event-ticket' ,'tab' => 'ticket_pricing'],
            'data'         => [
                'limit_info'=> esc_html__("If you choose limited ticket but do not set the ticket stock, then '100,000' will be used as default ticket stock count. You can update the ticket stock from ticket variation option below at any time.","eventin") ,
            ],
            'conditional'  => true,
            'condition-id' => 'etn_avaiilable_tickets',
        ];
        
        $event_ticket_fields['etn_ticket_variations'] = [
            'label'    => esc_html__( 'Ticket Variation', 'eventin' ),
            'type'     => 'repeater',
            'default'  => '',
            'value'    => '',
            'options'  => [
                'etn_ticket_name'   => [
                    'label'    => esc_html__( 'Ticket Name', 'eventin' ),
                    'type'     => 'text',
                    'default'  => '',
                    'value'    => '',
                    'desc'     => esc_html__( 'Set ticket name / label', 'eventin' ),
                    'placeholder' => esc_html__( 'Title here', 'eventin' ),
                    'priority' => 1,
                    'attr'     => [],
                    'required' => true,
                ],
                'etn_ticket_price'  => [
                    'label'    => esc_html__( 'Ticket Price', 'eventin' ),
                    'type'     => 'number',
                    'default'  => '',
                    'value'    => '',
                    'desc'     => esc_html__( 'Per ticket price', 'eventin' ),
                    'priority' => 1,
                    'step'     => 0.01,
                    'placeholder' => esc_html__( '0', 'eventin' ),
                    'required' => true,
                    'attr'     => ['class' => 'etn-label-item'],
                ],
                'etn_avaiilable_tickets' => [
                    'label'    => esc_html__( 'No. of Tickets', 'eventin' ),
                    'type'     => 'number',
                    'default'  => '',
                    'value'    => '',
                    'desc'     => esc_html__( 'Total no of ticket', 'eventin' ),
                    'priority' => 1,
                    'placeholder' => esc_html__( '100,000', 'eventin' ),
                    'required' => true,
                    'attr'     => ['class' => 'etn-label-item etn-ticket-stock-count'],
                ],
                'etn_sold_tickets' => [
                    'label'    => '',
                    'desc'    => '',
                    'type'     => 'hidden',
                    'default'  => '',
                    'value'    => '', //value of sold ticket of this ticket variation. 
                                    // This won't be updated from metabox. 
                                    // This will update automatically  only on stock status change from woo order
                    'attr'     => ['class' => 'etn-label-item'],
                ],
                'etn_ticket_slug' => [
                    'label'    => '',
                    'desc'     => '',
                    'type'     => 'hidden',
                    'default'  => '',
                    'value'    => '',// Unique slug for each ticket variations generated from variation title. 
                                    // Variation title may change but this slug will be generated only once and if existing slug found then never generate a new slug.
                                    // Variation stock will be calculated regarding this unique slug and will be used for storing as cart item meta, so generate only once
                    'attr'     => ['class' => 'etn-label-item'],
                ],
                
            ],
            'desc'     => '',
            'attr'     => ['class' => '' ,'tab' => 'ticket_pricing'],
            'priority' => 1,
            'required' => true,
        ];
        $ticket_meta_fields = apply_filters( 'etn_event_meta_fields_after_ticket', $event_ticket_fields );
        /******************************* Ticket Management *********************** */


		$zoom_meta_fields = [];
		if ( !empty( $settings['etn_zoom_api'] ) ) {
			$event_zoom_fields['etn_zoom_event'] = [
				'label'        => esc_html__( 'Zoom Event', "eventin" ),
				'desc'         => esc_html__( 'Enable if this event is a zoom event', "eventin" ),
				'type'         => 'checkbox',
				'left_choice'  => 'Yes',
				'right_choice' => 'no',
				'attr'         => ['class' => 'etn-label-item etn-zoom-event','tab'=>'zoom_settings'],
				'conditional'  => true,
				'condition-id' => 'etn_zoom_id',
			];

			$event_zoom_fields['etn_zoom_id'] = [
				'label'    => esc_html__( 'Select Meeting', "eventin" ),
				'desc'     => esc_html__( 'Choose zoom meeting for this event', "eventin" ),
				'type'     => 'select_single',
				'options'  => Helper::get_zoom_meetings(),
				'priority' => 1,
				'required' => true,
				'attr'     => ['class' => 'etn-label-item etn-zoom-id','tab'=>'zoom_settings'],
				'warning'       => esc_html__('Create Zoom Meeting', 'eventin'),
				'warning_url'   => admin_url( 'post-new.php?post_type=etn-zoom-meeting' )
			];

			$zoom_meta_fields = apply_filters( 'etn_event_meta_fields_after_zoom', $event_zoom_fields );
		}

		$event_social_fields['etn_event_socials'] = [
			'label'    => esc_html__( 'Social', 'eventin' ),
			'type'     => 'social_reapeater',
			'default'  => '',
			'value'    => '',
			'options'  => [
				'facebook' => [
					'label'      => esc_html__( 'Facebook', 'eventin' ),
					'icon_class' => '',
				],
				'twitter'  => [
					'label'      => esc_html__( 'Twitter', 'eventin' ),
					'icon_class' => '',
				],
			],
			'desc'     => esc_html__( 'Add multiple social icon', 'eventin' ),
			'attr'     => ['class' => '', 'tab' => 'general_settings'],
			'priority' => 1,
			'required' => true,
		];

		$is_child_post =  wp_get_post_parent_id( get_the_ID() ) ? true : false;

		//override and modify existing meta fields if needed
		$all_event_meta_fields  = array_merge($default_meta_fields, $time_meta_fields, $date_meta_fields, $recurring_meta_fields, $wc_meta_fields, $zoom_meta_fields, $ticket_meta_fields, $event_social_fields);
		$this->event_fields     = $this->filter_meta_fields_for_parent_child( $this->event_fields, $is_child_post );
		$crm_field              = apply_filters( 'etn_event_fields', $all_event_meta_fields);
		$banner_fields          = apply_filters( 'etn/banner_fields/etn_metaboxs', $all_event_meta_fields );
		$this->event_fields     = array_merge( $crm_field , $banner_fields );

		$tab_items = $this->get_tab_pane( $settings );

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
				'name'=>esc_html__('Venue/Location','eventin'),
				'id'  => 'locations', 
				'icon'=> '<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',   
			],
			[
				'name'=>esc_html__('Date & Time','eventin'),
				'id'  => 'date_time',
				'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
			],
			[
				'name'=>esc_html__('Recurring Events','eventin'),
				'id'  => 'recurring',
				'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
			],
			[
				'name'=>esc_html__('Ticket & Pricing','eventin'),
				'id'  => 'ticket_pricing',
				'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
			],
		];

		// push zoom tab
		if ( !empty( $settings['etn_zoom_api'] ) ) {
			$zoom_tab = [
				'name'=>esc_html__('Zoom Settings','eventin'),
				'id'  => 'zoom_settings',
				'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
			];

			array_push($tab_items,$zoom_tab);
		}

		// push zoom tab
		if ( class_exists( 'Wpeventin_Pro' ) ) {
			$pro_tabs = [
				[
					'name'=>esc_html__('Banner','eventin'),
					'id'  => 'banner', 
					'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>', 
				],
				[
					'name'=>esc_html__('FAQ','eventin'),
					'id'  => 'faq',
					'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',    
				],
				[
					'name'=>esc_html__('CRM','eventin'),
					'id'  => 'crm',
					'icon'=>'<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',  
				],
			];

			foreach ($pro_tabs as $key => $value) {
				array_push($tab_items,$value);
			}
			
		}

		return $tab_items;
	}

	/**
	 * Filter Meta Fields For Meta Boxes
	 *
	 * @param [type] $event_fields
	 * @param [type] $is_child_post
	 * @return void
	 */
	public function filter_meta_fields_for_parent_child( $event_fields, $is_child_post ){

		$allowed_child_post_fields = [
			'etn_start_date', 
			'etn_start_time', 
			'etn_end_date', 
			'etn_end_time', 
			'etn_registration_deadline',
			'event_timezone', 
			'etn_ticket_availability', 
			'etn_ticket_variations',
		];
		if( $is_child_post ){
			$new_array = array_intersect_key( $event_fields,  /* main array*/
												array_flip( $allowed_child_post_fields /* to be extracted */ )
						);
			return $new_array;
		}

		return $event_fields;

	}

	/**
	 * Filter Meta Boxes For Event
	 *
	 * @param [type] $event_fields
	 * @param [type] $is_child_post
	 * @return void
	 */
	public function filter_meta_boxes_for_parent_child( $event_boxes, $is_child_post ){

		$allowed_child_post_fields = [
			'etn_event_settings', 
			'etn_report', 
		];
		if( $is_child_post ){
			$new_array = array_intersect_key( $event_boxes,  /* main array*/
												array_flip( $allowed_child_post_fields /* to be extracted */ )
						);
			return $new_array;
		}

		return $event_boxes;

	}

	/**
	 * function etn_report_callback
	 * gets the current event id,
	 * gets all details of this event, calculates total sold quantity and price
	 * then finally generates report
	 */
	public function etn_report_callback() {
		$report_options    = get_option( "etn_event_report_etn_options" );
		$report_sorting    = isset( $report_options["event_list"] ) ? strtoupper( $report_options["event_list"] ) : "DESC";
		$ticket_qty        = get_post_meta( get_the_ID(), "etn_total_sold_tickets", true );
		$total_sold_ticket = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
		$data              = \Etn\Utils\Helper::get_tickets_by_event( get_the_ID(), $report_sorting );
		// get all tickets info
		$ticket_variations = get_post_meta( get_the_ID(), 'etn_ticket_variations', true );

		if ( is_array( $ticket_variations ) &&  count( $ticket_variations ) > 0 ) {
			// multi ticket feature
			?>
		   <div class="etn-order-report-wrap">
			   <h3 class="etn-sub-title"><?php echo esc_html__("Event Name:","eventin") . esc_html( get_the_title() ) ; ?></h3>
				<div class="etn-inner-wrap">
					<?php 
					foreach ($ticket_variations as $key => $value) {
						?>
						<div class="report-item">
							<div class="label">
								<strong><?php echo esc_html__("Ticket Name:","eventin") . $value['etn_ticket_name'] ;?></div></strong>
							<div class="label">
								<strong><?php echo esc_html__( "Total tickets sold:", "eventin" ); ?></strong> <?php echo intval( $value['etn_sold_tickets'] ); ?>
							</div>
							<div class="label">
								<strong><?php echo esc_html__( "Total price sold:", "eventin" ); ?></strong> 
								<?php 
								$total_price_sold = floatval( absint( $value['etn_sold_tickets'] ) * floatval( $value['etn_ticket_price'] ) ); 
								
								$formatted_total_price_sold = class_exists( 'WooCommerce' ) ? wc_format_decimal( $total_price_sold, wc_get_price_decimals() ) : number_format( $total_price_sold, 2 );
								echo esc_html( $formatted_total_price_sold );
								?>
							</div>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
			<?php

		} else {

			if ( isset( $data['all_sales'] ) && is_array( $data['all_sales'] ) && count( $data['all_sales'] ) > 0 ) {

				foreach ( $data['all_sales'] as $single_sale ) {
					?>
					<div>
						<div class="etn-report-row">
						<strong ><?php echo esc_html__( "invoice no.", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->invoice ); ?>
						<strong class="etn-report-cell"><?php echo esc_html__( "total qty:", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->single_sale_meta ); ?>
						<strong class="etn-report-cell"><?php echo esc_html__( "total amount:", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->event_amount ); ?>
						<strong class="etn-report-cell"><?php echo esc_html__( "email:", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->email ); ?>
						<strong class="etn-report-cell"><?php echo esc_html__( "status:", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->status ); ?>
						<strong class="etn-report-cell"><?php echo esc_html__( "payment type:", "eventin" ); ?></strong> <?php echo esc_html( $single_sale->payment_gateway ); ?>
						</div>
					</div>
					<hr>
					<?php
				}

			}

			?>
			<div>
				<strong><?php echo esc_html__( "Total tickets sold:", "eventin" ); ?></strong> <?php echo esc_html( $total_sold_ticket ); ?>
			</div>
			<div>
				<strong><?php echo esc_html__( "Total price sold:", "eventin" ); ?></strong> <?php echo isset( $data['total_sale_price'] ) ? esc_html( $data['total_sale_price'] ) : 0; ?>
			</div>
			<?php

		}
	}
}
