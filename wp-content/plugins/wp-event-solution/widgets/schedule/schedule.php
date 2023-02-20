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
class Etn_Schedule extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-schedule';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Eventin schedule Tab', 'eventin' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-list';
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
                'label' => esc_html__( 'Schedule settings', 'eventin' ),
            ]
        );
        $this->add_control(
            'schedule_style',
            [
                'label'   => esc_html__( 'Schedule Style', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'schedule-1',
                'options' => [
                    'schedule-1' => esc_html__( 'Schedule 1', 'eventin' ),
                ],
            ]
        );
        $this->add_control(
            'schedule_id',
            [
                'label'    => esc_html__( 'Schedule', 'eventin' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => $this->get_schedules(),
            ]
        );
        $this->add_control(
            'etn_schedule_order',
            [
                'label'   => esc_html__( 'Schedule order', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__( 'Descending', 'eventin' ),
                    'ASC'  => esc_html__( 'Ascending', 'eventin' ),
                ],
            ]
        );
        $this->end_controls_section();
        // End of schedule section

        // Start of nav section
        $this->start_controls_section(
            'nav_style',
            [
                'label' => esc_html__( 'Nav style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'nav_align', [
                'label'     => esc_html__( 'Alignment', 'eventin' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [

                    'left'    => [
                        'title' => esc_html__( 'Left', 'eventin' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'eventin' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'eventin' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'eventin' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        //Responsive control end

        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_nav_typography',
                'label'    => esc_html__( 'Nav Title Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );

        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_nav_sub_typography',
                'label'    => esc_html__( 'Nav Sub Title Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a .etn-day',
            ]
        );

        //start of nav color tabs (normal and hover)
        $this->start_controls_tabs(
            'etn_nav_tabs'
        );

        //start of nav normal color tab
        $this->start_controls_tab(
            'etn_nav_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_nav_color',
            [
                'label'     => esc_html__( 'Nav Title Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-nav li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_nav_sub_color',
            [
                'label'     => esc_html__( 'Nav Subtitle color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a .etn-day' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_border',
                'label'    => esc_html__( 'Border', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );

        $this->end_controls_tab();
        //end of nav normal color tab

        //start of nav active color tab
        $this->start_controls_tab(
            'etn_nav_active_tab',
            [
                'label' => esc_html__( 'Active', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_nav_active_color',
            [
                'label'     => esc_html__( 'Nav active color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-nav li a.etn-active'                  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
                ],
            ]
        );
        $this->add_control(
            'etn_nav_sub_active_color',
            [
                'label'     => esc_html__( 'Nav Subtitle Active color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a.etn-active .etn-day' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_nav_angle_active_color',
            [
                'label'     => esc_html__( 'Nav Angle Active color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a:after' => 'border-color: {{VALUE}}  transparent transparent transparent;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_borderactive',
                'label'    => esc_html__( 'Border active', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-nav li a.etn-active',
            ]
        );
        $this->end_controls_tab();
        //end of nav hover color tab

        $this->end_controls_tabs();
        //end of nav color tabs (normal and hover)

        $this->end_controls_section();
        // End of nav section

        // Start of title section
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__( 'Title style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_title_typography',
                'label'    => esc_html__( 'Title Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-schedule-content .etn-title',
            ]
        );

        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__( 'Title color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-schedule-content .etn-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of title section

        // Start of title section
        $this->start_controls_section(
            'desc_style',
            [
                'label' => esc_html__( 'Description style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_desc_typography',
                'label'    => esc_html__( 'Desc Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-schedule-content p',
            ]
        );

        $this->add_control(
            'etn_desc_color',
            [
                'label'     => esc_html__( 'Desc color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-schedule-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of desc section

        // Start of title section
        $this->start_controls_section(
            'schedule_time_style',
            [
                'label' => esc_html__( 'Schedule time style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_schedule_time_typography',
                'label'    => esc_html__( 'Schedule Time Typography', 'eventin' ),
                'selector' => '{{WRAPPER}}  .etn-schedule-info span , {{WRAPPER}} .etn-schedule-info span i',
            ]
        );

        $this->add_control(
            'etn_schedule_time_color',
            [
                'label'     => esc_html__( 'Schedule Time Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-schedule-info span , {{WRAPPER}} .etn-schedule-info span i ' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_schedule_time__bgcolor',
            [
                'label'     => esc_html__( 'Schedule Time BG Color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-schedule-info span  , {{WRAPPER}} .etn-schedule-info span i ' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of desc section

    }

    protected function render() {
        $settings = $this->get_settings();
        $style    = !empty( $settings["schedule_style"] ) ? $settings["schedule_style"] : "schedule-1";
        $etn_schedule_order = !empty( $settings["etn_schedule_order"] ) ? $settings["etn_schedule_order"] : "";
        $etn_schedule_ids   = !empty( $settings["schedule_id"] ) ? $settings["schedule_id"] : '';
        $order              = isset($etn_schedule_order) ? $etn_schedule_order : 'ASC';

        include \Wpeventin::plugin_dir() . "widgets/schedule/style/{$style}.php";
    }

    protected function get_schedules() {
        return Helper::get_schedules();
    }

}
