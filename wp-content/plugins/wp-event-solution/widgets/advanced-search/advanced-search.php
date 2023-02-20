<?php
/*
* @Plugin Version: 2.3.1
*/
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper as helper;

defined( 'ABSPATH' ) || exit;

class Etn_Advanced_Search extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-advanced-search';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Eventin Advanced Search', 'eventin' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    protected function get_total_posts() {
        return helper::get_eventin_search_data();
    }

    /**
     * Register the widget controls.
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
			'etn_event_searc_settings_section',
			[
				'label' => esc_html__( 'Settings', 'eventin' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
		'etn_event_input_filed_title',
			[
				'label'       => esc_html__( 'Input placeholder Text', 'eventin' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Find your next event', 'eventin' ),
				'placeholder' => esc_html__( 'Type your title here', 'eventin' ),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
        'etn_event_category_filed_title',
            [
                'label'       => esc_html__( 'Category Text', 'eventin' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Event Category', 'eventin' ),
                'placeholder' => esc_html__( 'Type your title here', 'eventin' ),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
        'etn_event_location_filed_title',
            [
                'label'       => esc_html__( 'Location Text', 'eventin' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Event Location', 'eventin' ),
                'placeholder' => esc_html__( 'Type your title here', 'eventin' ),
                'separator'   => 'before',
            ]
	    );

		$this->add_control(
			'etn_event_button_title',
			[
				'label'       => esc_html__( 'Button Text', 'eventin' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search Now', 'eventin' ),
				'placeholder' => esc_html__( 'Type your title here', 'eventin' ),
                'separator'   => 'before',
			]
		);

		$this->end_controls_section();

        // search style here - start
        // ==================================================
        // search wrap - start
        $this->start_controls_section(
            'search_style_section',
            [
                'label' => esc_html__( 'Search Box', 'eventin' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'search_background_style',
                    'label' => esc_html__( 'Background', 'eventin' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .etn_event_inline_form',
                ]
            );
            $this->add_responsive_control(
                'search_border_style',
                [
                    'label' => esc_html_x( 'Border Type', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__( 'None', 'eventin' ),
                        'solid' => esc_html_x( 'Solid', 'Border Control', 'eventin' ),
                        'double' => esc_html_x( 'Double', 'Border Control', 'eventin' ),
                        'dotted' => esc_html_x( 'Dotted', 'Border Control', 'eventin' ),
                        'dashed' => esc_html_x( 'Dashed', 'Border Control', 'eventin' ),
                        'groove' => esc_html_x( 'Groove', 'Border Control', 'eventin' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form' => 'border-style: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_border_width_style',
                [
                    'label' => esc_html_x( 'Border Width', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_border_style!' => '',
                    ],

                ]
            );
            $this->add_control(
                'search_border_color_style',
                [
                    'label' => esc_html_x( 'Border Color', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'search_border_style!' => '',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_border_radius_style',
                [
                    'label' => esc_html__( 'Border Radius', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_border_style!' => '',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'search_boxshadow_style',
                    'label' => esc_html__( 'Box Shadow', 'eventin' ),
                    'selector' => '{{WRAPPER}} .etn_event_inline_form',
                ]
            );
        $this->end_controls_section();
        // search wrap - end

        // search input group - start
        $this->start_controls_section(
            'search_input_group_style_section',
            [
                'label' => esc_html__( 'Input Group', 'eventin' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'search_input_group_padding',
                [
                    'label' =>esc_html__( 'Padding', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );
            $this->add_control(
                'search_input_group_margin',
                [
                    'label' =>esc_html__( 'Margin', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'search_input_group_border',
                [
                    'label' => esc_html_x( 'Border Type', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__( 'None', 'eventin' ),
                        'solid' => esc_html_x( 'Solid', 'Border Control', 'eventin' ),
                        'double' => esc_html_x( 'Double', 'Border Control', 'eventin' ),
                        'dotted' => esc_html_x( 'Dotted', 'Border Control', 'eventin' ),
                        'dashed' => esc_html_x( 'Dashed', 'Border Control', 'eventin' ),
                        'groove' => esc_html_x( 'Groove', 'Border Control', 'eventin' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'border-style: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_input_group_border_width',
                [
                    'label' => esc_html_x( 'Border Width', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_input_group_border!' => '',
                    ],

                ]
            );
            $this->add_control(
                'search_input_group_border_color',
                [
                    'label' => esc_html_x( 'Border Color', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'search_input_group_border!' => '',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_input_group_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_input_group_border!' => '',
                    ],
                ]
            );
        $this->end_controls_section();
        // search input group - end

        // search input icon - start
        $this->start_controls_section(
            'search_input_icon_style_section',
            [
                'label' => esc_html__( 'Input Icon', 'eventin' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_input_icon_typography',
                    'label' => esc_html__( 'Typography', 'eventin' ),
                    'selector' => '{{WRAPPER}} .etn_event_inline_form .input-group-text',
                ]
            );
            $this->add_control(
                'search_input_icon_color',
                [
                    'label' =>esc_html__( 'Color', 'eventin' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .input-group-text' => 'color: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();
        // search input icon - end

        // search button - start
        $this->start_controls_section(
            'search_button_style_section',
            [
                'label' => esc_html__( 'Search Button', 'eventin' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_button_typography',
                    'label' => esc_html__( 'Typography', 'eventin' ),
                    'selector' => '{{WRAPPER}} .etn_event_inline_form .etn-btn',
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'search_button_background',
                    'label' => esc_html__( 'Background', 'eventin' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .etn_event_inline_form .etn-btn',
                ]
            );

            $this->add_responsive_control(
                'search_button_border_style',
                [
                    'label' => esc_html_x( 'Border Type', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__( 'None', 'eventin' ),
                        'solid' => esc_html_x( 'Solid', 'Border Control', 'eventin' ),
                        'double' => esc_html_x( 'Double', 'Border Control', 'eventin' ),
                        'dotted' => esc_html_x( 'Dotted', 'Border Control', 'eventin' ),
                        'dashed' => esc_html_x( 'Dashed', 'Border Control', 'eventin' ),
                        'groove' => esc_html_x( 'Groove', 'Border Control', 'eventin' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .etn-btn' => 'border-style: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_button_border_width',
                [
                    'label' => esc_html_x( 'Border Width', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .etn-btn' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_button_border_style!' => '',
                    ],

                ]
            );
            $this->add_control(
                'search_button_border_color',
                [
                    'label' => esc_html_x( 'Border Color', 'Border Control', 'eventin' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .etn-btn' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'search_button_border_style!' => '',
                    ],
                ]
            );
            $this->add_responsive_control(
                'search_button_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'eventin' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .etn_event_inline_form .etn-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'search_button_border_style!' => '',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'search_button_boxshadow',
                    'label' => esc_html__( 'Box Shadow', 'eventin' ),
                    'selector' => '{{WRAPPER}} .etn_event_inline_form .etn-btn',
                ]
            );
        $this->end_controls_section();
    }

    protected function render() {
        $settings                    = $this->get_settings();
        extract($settings);
        ?>
        <div class="etn_search_<?php echo esc_attr( $this->get_id() ); ?> etn_search_wraper">

            <?php helper::get_event_search_form($etn_event_input_filed_title, $etn_event_category_filed_title, $etn_event_location_filed_title, $etn_event_button_title); ?>
            <p class="etn_search_bottom_area_text"><?php echo esc_html__( "Discover ". count($this->get_total_posts()) ." Upcoming and Expire "._n( "Event", "Events", count($this->get_total_posts()), 'eventin' )."", 'eventin' ); ?></p>
        </div>
        <?php
     
    }

    public function get_event_category() {
        return Helper::get_event_category();
    }


}
