<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Etn_Zoom extends Widget_Base {

    public $base;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_script( 'zoom-init', \Wpeventin::plugin_url() . 'widgets/zoom/assets/js/zoom.init.js', ['elementor-frontend'], \Wpeventin::version(), true );
        // locallize data
        $form_data                              = [];
        $form_data['ajax_url']                  = admin_url( 'admin-ajax.php' );
        $form_data['zoom_create_meeting_nonce'] = wp_create_nonce( 'zoom_create_meeting_nonce' );
        $form_data['zoom_type_meeting']         = esc_html__( 'Meeting', 'eventin' );
        $form_data['zoom_type_webinar']         = esc_html__( 'Webinar', 'eventin' );
        $form_data['zoom_type_change_alert']    = esc_html__( 'Sorry! As meeting/webinar is already created, so changing type is irrelevant. Please delete this block and create new type.', 'eventin' );
        wp_localize_script( 'zoom-init', 'zoom_js', $form_data );
    }

    public function get_script_depends() {
        return ['zoom-init'];
    }

    public function get_name() {
        return 'etn-zoom';
    }

    public function get_title() {
        return esc_html__( 'Eventin zoom', 'eventin' );
    }

    public function get_icon() {
        return 'eicon-video-camera';
    }

    public function get_categories() {
        return ['etn-event'];
    }

    protected function register_controls() {
        // get host list
        $user_list = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->zoom_meeting_user_list();
        // get time zone
        $time_zone              = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_timezone();
        $default_value_user     = is_array( $user_list ) && !empty( $user_list ) ? array_keys( $user_list )[0] : '';
        $default_value_timezone = is_array( $time_zone ) && !empty( $time_zone ) ? array_keys( $time_zone )[0] : '';

        $zoom_type_desc = '';
        $zoom_types     = $this->conditional_zoom_types();
        if ( count( $zoom_types ) == 2 ) {
            $zoom_type_desc = esc_html__( 'Note: Webinar requires webinar plan enabled in your account. At edit time don\'t change type as meeting/webinar id is already generated. If need to change type, remove and add new widget.', 'eventin' );
        }

        $this->start_controls_section(
            'meeting_section_content',
            [
                'label' => esc_html__( 'Content', 'eventin' ),
            ]
        );

        $this->add_control(
            'zoom_type',
            [
                'label'       => esc_html__( 'Meeting Type*', 'eventin' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $zoom_types,
                // 'label_block' => true,
                'default'     => '2',
                'description' => $zoom_type_desc,
            ]
        );

        $this->add_control(
            'zoom_style',
            [
                'label'   => esc_html__( 'Zoom Style', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'zoom-1',
                'options' => [
                    'zoom-1' => esc_html__( 'Style 1', 'eventin' ),
                    'zoom-2' => esc_html__( 'Style 2', 'eventin' ),
                    'zoom-3' => esc_html__( 'Style 3', 'eventin' ),
                    'zoom-4' => esc_html__( 'Style 4', 'eventin' ),

                ],
            ]
        );

        $this->add_control(
            'topic',
            [
                'label'       => esc_html__( 'Meeting Topic', 'eventin' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__( 'Write meeting topic a host of the meeting.(Optional)', 'eventin' ),
            ]
        );

        $this->add_control(
            'agenda',
            [
                'label'       => esc_html__( 'Meeting Agenda', 'eventin' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'description' => esc_html__( 'Details description of the meeting.(Optional)', 'eventin' ),
            ]
        );

        $this->add_control(
            'meeting_cache',
            [
                'label' => esc_html__( 'Meeting Data', 'eventin' ),
                'type'  => \Elementor\Controls_Manager::HIDDEN,
            ]
        );

        $this->add_control(
            'user_id',
            [
                'label'       => esc_html__( 'Meeting Hosts*', 'eventin' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $user_list,
                'label_block' => true,
                'default'     => $default_value_user,
                'description' => esc_html__( 'Select a host of the meeting.(Required)', 'eventin' ),
            ]
        );

        $this->add_control(
            'start_time',
            [
                'label'       => esc_html__( 'Start date/time*', 'eventin' ),
                'type'        => \Elementor\Controls_Manager::DATE_TIME,
                'default'     => date( 'y-m-d H:i' ),
                'description' => esc_html__( 'Select start date and time.(Required)', 'eventin' ),
            ]
        );

        $this->add_control(
            'timezone',
            [
                'label'       => esc_html__( 'Time zone', 'eventin' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $time_zone,
                'label_block' => true,
                'default'     => $default_value_timezone,
                'description' => esc_html__( 'Select timezone for meeting .(Optional)', 'eventin' ),
            ]
        );

        $this->add_control(
            'duration',
            [
                'label'       => esc_html__( 'Duration', 'eventin' ),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                // 'default'         => '',
                'description' => esc_html__( 'Meeting duration (minutes).(Optional)', 'eventin' ),
            ]
        );

        $this->add_control(
            'password',
            [
                'label'       => esc_html__( 'Password', 'eventin' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for auto generate )', 'eventin' ),
            ]
        );

		// common
        $this->add_control(
			'meeting_authentication',
			[
				'label'         => esc_html__( 'Require authentication to join?', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Only authenticated users can join', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
			]
		);

        $this->add_control(
			'host_video',
			[
				'label'         => esc_html__( 'Host Video', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Auto on/off host video', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
			]
		);

        $this->add_control(
			'participant_panelists_video',
			[
				'label'         => esc_html__( 'Participant/Panelists Video', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Auto on/off participant video', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
			]
		);

        $this->add_control(
            'auto_recording',
            [
                'label'       => esc_html__( 'Auto Recording', 'eventin' ),
                'type'        => Controls_Manager::SELECT2,
                'default'     => 'none',
                'options'     => [
                    'none'  => esc_html__( 'None', 'eventin' ), 
                    'local' => esc_html__( 'Local', 'eventin' ), 
                    'cloud' => esc_html__( 'Cloud', 'eventin' ), 
                ],
                // 'label_block' => true,
               
                'description' => esc_html__( 'Automatically record meeting on', 'eventin' ),
            ]
        );

        // meeting extra
        $this->add_control(
			'waiting_room',
			[
				'label'         => esc_html__( 'Waiting Room', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Only users admitted by the host can join the meeting', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
				'condition'       => [
                    'zoom_type' => '2', 
                ],
			]
		);

        $this->add_control(
			'join_before_host',
			[
				'label'         => esc_html__( 'Join anytime', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Allow participants to join anytime', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
                'condition'       => [
                    'zoom_type' => '2', 
                ],
			]
		);

        $this->add_control(
			'mute_upon_entry',
			[
				'label'         => esc_html__( 'Mute participants upon entry', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Mutes participants when entering to the meeting', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
                'condition'       => [
                    'zoom_type' => '2', 
                ],
			]
		);

        $this->add_control(
			'question_and_answer',
			[
				'label'         => esc_html__( 'Question & Answer', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Enable Question & Answer', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '1',
                'condition'       => [
                    'zoom_type' => '5', 
                ],
			]
		);

        $this->add_control(
			'practice_session',
			[
				'label'         => esc_html__( 'Practice Session', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Enable Practice Session', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
                'condition'       => [
                    'zoom_type' => '5', 
                ],
			]
		);

        $this->add_control(
			'hd_video',
			[
				'label'         => esc_html__( 'HD video for Host/Panelists', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Enable HD video for Host/Panelists', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
                'condition'       => [
                    'zoom_type' => '5', 
                ],
			]
		);

        $this->add_control(
			'hd_video_for_attendees',
			[
				'label'         => esc_html__( 'HD video for attendees', 'eventin' ),
				'type'          => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Enable HD video for attendees', 'eventin' ),
				'label_on'      => esc_html__( 'Yes', 'eventin' ),
				'label_off'     => esc_html__( 'No', 'eventin' ),
				'return_value'  => '1',
				'default'       => '0',
                'condition'       => [
                    'zoom_type' => '5', 
                ],
			]
		);

        $this->add_control(
            'create-meeting',
            [
                'type'        => \Elementor\Controls_Manager::BUTTON,
                'button_type' => 'success',
                'text'        => esc_html__( 'Create', 'eventin' ) . Helper::kses( '<span class="elementor-state-icon">
                    <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i></span>' ),
                'event'       => 'elementor:editor:create',
                'condition'   => ['meeting_cache' => ''],
            ]
        );

        $this->add_control(
            'update-meeting',
            [
                'type'        => \Elementor\Controls_Manager::BUTTON,
                'button_type' => 'success',
                'text'        => esc_html__( 'Update Meeting ', 'eventin' ) . Helper::kses( '<span class="elementor-state-icon">
                    <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i></span>' ),
                'event'       => 'elementor:editor:create',
                'condition'   => ['meeting_cache!' => ''],
            ]
        );
        

        $this->end_controls_section();

        // Start of title section
        $this->start_controls_section(
            'meeting_title_section',
            [
                'label' => esc_html__( 'Title Section', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        //control for title typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'meeting_title_typography',
                'label'    => esc_html__( 'Title Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .meeting-title',
            ]
        );

        //start of title color tabs (normal and hover)
        $this->start_controls_tabs(
            'etn_meeting_title_tabs'
        );

        //start of title normal color tab
        $this->start_controls_tab(
            'etn_meeting_title_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_meeting_title_color',
            [
                'label'     => esc_html__( 'Title color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meeting-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meeting-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

// end of title normal color tab

        //start of title hover color tab
        $this->start_controls_tab(
            'etn_meeting_title_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_meeting_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meeting-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meeting-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        //end of title hover color tab

        $this->end_controls_tabs();

//end of title color tabs (normal and hover)

        //start of title margin control
        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title margin', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .meeting-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //end of title margin control

        $this->end_controls_section();

// End of title section

        // Start of block section
        $this->start_controls_section(
            'meeting_block',
            [
                'label' => esc_html__( 'Meeting Section', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        //control for block typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'ent_meeting_blcok_typography',
                'label'    => esc_html__( 'Meeting block typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .meeting-info p',
            ]
        );

        //start of block color tabs (normal and hover)
        $this->start_controls_tabs(
            'etn_meeting_blcok_tabs'
        );

        //start of block normal color tab
        $this->start_controls_tab(
            'etn_meeting_blcok_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_meeting_blcok_color',
            [
                'label'     => esc_html__( 'Meeting block color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meeting-info p, .meeting-info a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meeting-info p, .meeting-info a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

// end of block normal color tab

        //start of block hover color tab
        $this->start_controls_tab(
            'etn_meeting_blcok_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_meeting_blcok_hover_color',
            [
                'label'     => esc_html__( 'Meeting block hover color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meeting-info p:hover, .meeting-info a:hover'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meeting-info p:hover,  .meeting-info a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        //end of block hover color tab

        $this->end_controls_tabs();

//end of block color tabs (normal and hover)

        //start of block margin control
        $this->add_responsive_control(
            'meeting_blcok_margin',
            [
                'label'      => esc_html__( 'Meeting block margin', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .meeting-info p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        //end of block margin control

        $this->end_controls_section();
        // End of block section

    }

    protected function render() {
        $settings      = $this->get_settings();
        $zoom_style    = $settings['zoom_style'];
        $zoom_types    = Helper::zoom_types();

        ?>
        <input type="hidden" value="<?php echo esc_attr( $settings['host_video'] ); ?>" class="control_host_video" />
        <input type="hidden" value="<?php echo esc_attr( $settings['participant_panelists_video'] ); ?>" class="control_participant_panelists_video" />
        <input type="hidden" value="<?php echo esc_attr( $settings['meeting_authentication'] ); ?>" class="control_meeting_authentication" />

        <input type="hidden" value="<?php echo esc_attr( $settings['waiting_room'] ); ?>" class="control_waiting_room" />
        <input type="hidden" value="<?php echo esc_attr( $settings['join_before_host'] ); ?>" class="control_join_before_host" />
        <input type="hidden" value="<?php echo esc_attr( $settings['mute_upon_entry'] ); ?>" class="control_mute_upon_entry" />

        <input type="hidden" value="<?php echo esc_attr( $settings['question_and_answer'] ); ?>" class="control_question_and_answer" />
        <input type="hidden" value="<?php echo esc_attr( $settings['practice_session'] ); ?>" class="control_practice_session" />
        <input type="hidden" value="<?php echo esc_attr( $settings['hd_video'] ); ?>" class="control_hd_video" />
        <input type="hidden" value="<?php echo esc_attr( $settings['hd_video_for_attendees'] ); ?>" class="control_hd_video_for_attendees" />
        <?php
        
        $template_file = \Wpeventin::plugin_dir() . "widgets/zoom/style/zoom-1.php";

        if ( file_exists( $template_file ) ) {
            include $template_file;
        }

    }

    /**
     * available zoom types
     *
     * @return array
     */
    public function conditional_zoom_types() {
        $zoom_types = Helper::zoom_types();

        if ( !Helper::is_webinar_user() ) {
            unset( $zoom_types['5'] );
        }

        return $zoom_types;        
    }

}
