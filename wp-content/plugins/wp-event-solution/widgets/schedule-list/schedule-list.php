<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

// Exit if accessed directly

/**
 * @since 1.1.0
 */
class Etn_Schedule_List extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-schedule-list';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Eventin Schedule List', 'eventin' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    protected function register_controls() {
        // Start of schedule section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'Schedule Settings', 'eventin' ),
            ]
        );
        $this->add_control(
            'schedule_style',
            [
                'label'   => esc_html__( 'Schedule Style', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'schedule-list-1',
                'options' => [
                    'schedule-list-1' => esc_html__( 'Style 1', 'eventin' ),
                ],
            ]
        );
        $this->add_control(
            'schedule_id',
            [
                'label'    => esc_html__( 'Schedule', 'eventin' ),
                'type'     => Controls_Manager::SELECT,
                'multiple' => true,
                'options'  => $this->get_schedules(),
            ]
        );

        $this->end_controls_section();
        // End of schedule section

        // Start of title section
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Topics Title Style', 'eventin' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_title_typography',
                'label'    => esc_html__( 'Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-title',
            ]
        );

        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__( 'Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_title_padding',
            [
                'label' => esc_html__( 'Margin', 'eventin' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'   => [
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of title section

        // Start of title section
        $this->start_controls_section(
            'details_style',
            [
                'label' => esc_html__( 'Topics Details Style', 'eventin' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_details_typography',
                'label'    => esc_html__( 'Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-details',
            ]
        );

        $this->add_control(
            'etn_details_color',
            [
                'label'     => esc_html__( 'Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-details' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_details_padding',
            [
                'label' => esc_html__( 'Margin', 'eventin' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'   => [
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of desc section

        // Start of title section
        $this->start_controls_section(
            'schedule_time_style',
            [
                'label' => esc_html__( 'Schedule Time Style', 'eventin' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_schedule_time_typography',
                'label'    => esc_html__( 'Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-time',
            ]
        );

        $this->add_control(
            'etn_schedule_time_color',
            [
                'label'     => esc_html__( 'Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-time' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'etn_schedule_time_bgcolor',
            [
                'label'     => esc_html__( 'Background Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-time' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_schedule_time_padding',
            [
                'label' => esc_html__( 'Padding', 'eventin' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default'   => [
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-slot-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of desc section

        // Start of title section
        $this->start_controls_section(
            'schedule_advance_style',
            [
                'label' => esc_html__( 'Advance Style', 'eventin' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => esc_html__( 'Margin', 'eventin' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
             
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper .schedule-listing.multi-schedule-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'schedule_advance_alignment',
            [
                'label' => esc_html__('Align', 'eventin'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'eventin'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'eventin'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'eventin'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .schedule-list-wrapper ' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();



    }

    protected function render() {
        $settings = $this->get_settings();
        $style    = $settings["schedule_style"];
        $etn_schedule_id   = $settings["schedule_id"];

        include \Wpeventin::plugin_dir() . "widgets/schedule-list/style/{$style}.php";
    }

    protected function get_schedules() {
        return Helper::get_schedules();
    }

}
