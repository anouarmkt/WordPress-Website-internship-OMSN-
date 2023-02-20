<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper;

use function PHPSTORM_META\type;

defined( 'ABSPATH' ) || exit;

class Etn_Event_Calendar extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-event-calendar';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Event Calendar', 'eventin' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-calendar';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    /**
     * Register the widget controls.
     * @access protected
     */
    protected function register_controls() {

        // Start of event section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'Eventin Event', 'eventin' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => esc_html__( 'Style', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__( 'Style 1', 'eventin' ),
                    'style-2' => esc_html__( 'Style 2', 'eventin' ),
                ],
            ]
        );
        $this->add_control(
            'etn_event_cat',
            [
                'label'    => esc_html__( 'Event Category', 'eventin' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_category(),
                'multiple' => true,
            ]
        );
        
        $this->add_control(
            'etn_event_count',
            [
                'label'   => esc_html__( 'Event count', 'eventin' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );

        $this->add_control(
			'show_desc',
			[
				'label' => esc_html__( 'Show Description?', 'eventin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'eventin' ),
				'label_off' => esc_html__( 'Hide', 'eventin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        $this->add_control(
            'calendar_show',
            [
                'label'   => esc_html__( 'Calendar Display', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left', 'eventin' ),
                    'full_width' => esc_html__( 'Full Width', 'eventin' ),
                    'right' => esc_html__( 'Right', 'eventin' ),
                ],
                'condition' => ['style' => 'style-1'],
            ]
        );

        $this->end_controls_section();

         // Title style section
         $this->start_controls_section(
            'title_section',
            [
                'label' => esc_html__( 'Title Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'ent_title_typography',
                'label'    => esc_html__( 'Title Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-content .etn-title',
            ]
        );

        // tab controls start
        $this->start_controls_tabs(
            'etn_title_tabs'
        );

        $this->start_controls_tab(
            'etn_title_normal_tab',
            [
                'label' =>esc_html__( 'Normal', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__( 'Title color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-content .etn-title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etn-event-content .etn-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_title_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item:hover .etn-event-content .etn-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etn-event-item:hover .etn-event-content .etn-title a'     => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title margin', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-content .etn-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // designation style section
        $this->start_controls_section(
            'desginnation_section',
            [
                'label' => esc_html__( 'Description Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_desc' => 'yes'],

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_designation_typography',
                'label'    => esc_html__( 'Description Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-content p',
            ]
        );

        $this->add_control(
            'etn_desc_color',
            [
                'label'     => esc_html__( 'Description color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_desc_margin',
            [
                'label'      => esc_html__( 'Description margin', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // location style section
        $this->start_controls_section(
            'location_style',
            [
                'label' => esc_html__( 'Location Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_location_typography',
                'label'    => esc_html__( 'Location Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-location',
            ]
        );

        $this->add_control(
            'etn_location_color',
            [
                'label'     => esc_html__( 'Location color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-location' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // location style section
        $this->start_controls_section(
            'thumb_style',
            [
                'label' => esc_html__( 'Thumb Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label'      => esc_html__( 'border radius', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-item .etn-event-thumb, {{WRAPPER}} .etn-event-item .etn-event-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render() {
        $settings           = $this->get_settings();
        $event_count        = isset($settings["etn_event_count"]) ? $settings["etn_event_count"] : 10;
        $event_cat          = (isset($settings["etn_event_cat"]) ? $settings["etn_event_cat"] : []);
        $calendar_show      = (isset($settings["calendar_show"]) ? $settings["calendar_show"] : 'left');
        $show_desc          = (isset($settings["show_desc"]) ? $settings["show_desc"] : 'no');
        $style              = (isset($settings["style"]) ? $settings["style"] : 'style-1');
        $catsIds            =  !empty($event_cat) ?  implode(",",$event_cat) : '';
        echo do_shortcode("[events_calendar style='$style' limit='$event_count' event_cat_ids='$catsIds' calendar_show=$calendar_show show_desc=$show_desc /]");
    }

    public function get_event_category() {
        return Helper::get_event_category();
    }
    
    public function get_event_tag() {
        return Helper::get_event_tag();
    }
}
 