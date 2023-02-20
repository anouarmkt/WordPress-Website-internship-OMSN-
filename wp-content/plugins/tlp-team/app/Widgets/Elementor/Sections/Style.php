<?php
/**
 * Elementor Style Class.
 *
 * This class contains all the controls for Style tab.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Sections;

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Style Class.
 */
class Style {

	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = \Elementor\Controls_Manager::TAB_STYLE;

	/**
	 * Color Scheme section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function colorScheme( $obj ) {
		$obj->startSection( 'color_scheme_section', esc_html__( 'Color Scheme', 'tlp-team' ), self::$tab );

		$obj->elControls = Fns::filter( $obj->elPrefix . 'color_scheme', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Name section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function name( $obj ) {
		$condition = [ $obj->elPrefix . 'team_name' => [ 'yes' ] ];

		$obj->startSection( 'name_section', esc_html__( 'Name', 'tlp-team' ), self::$tab, [], $condition );
		$obj->elHeading( $obj->elPrefix . 'name_typography_note', __( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'name_typography',
			'selector' => '{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row h3',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'name_alignment',
			'type'      => 'choose',
			'label'     => __( 'Alignment', 'tlp-team' ),
			'options'   => [
				'left'   => [
					'title' => __( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => __( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row h3' => 'text-align: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'name_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'name_color_tabs' );
		$obj->startTab( 'name_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay h3, {{WRAPPER}} .rt-elementor-container .layout14 .rt-grid-item .tlp-overlay h3, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row h3, {{WRAPPER}} .rt-elementor-container .carousel8 .rt-grid-item .tlp-overlay h3, {{WRAPPER}} .rt-elementor-container .carousel9 .single-team-area .tlp-overlay h3' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_top_color',
			'label'     => esc_html__( 'Top Title Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout-el-10', 'carousel5' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout-el-10 .tlp-overlay .tlp-title h3, {{WRAPPER}} .rt-elementor-container .carousel5 .tlp-overlay .tlp-title h3' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_top_bg_color',
			'label'     => esc_html__( 'Top Title Background Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout-el-10', 'carousel5' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout-el-10 .tlp-overlay .tlp-title h3, {{WRAPPER}} .rt-elementor-container .carousel5 .tlp-overlay .tlp-title h3' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_inner_bg_color',
			'label'     => esc_html__( 'Inner Background Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout12', 'carousel7', 'isotope-el-6' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout12 .single-team-area h3 .team-name, {{WRAPPER}} .rt-elementor-container .isotope-el-6 .single-team-area h3 .team-name' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'name_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3:hover, {{WRAPPER}} .rt-elementor-container h3:hover, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3:hover, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay h3:hover, {{WRAPPER}} .rt-elementor-container .layout14 .rt-grid-item .tlp-overlay h3:hover, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row h3:hover, {{WRAPPER}} .rt-elementor-container .carousel8 .rt-grid-item .tlp-overlay h3:hover, {{WRAPPER}} .rt-elementor-container .carousel9 .single-team-area .tlp-overlay h3:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3:hover, {{WRAPPER}} .rt-elementor-container h3:hover, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title h3:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_top_hover_color',
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout10', 'carousel5' ] ],
			'label'     => esc_html__( 'Top Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .tlp-title:hover h3' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_top_hover_bg_color',
			'label'     => esc_html__( 'Top Hover Background Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout10', 'carousel5' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .tlp-title:hover h3' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'name_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'label'    => esc_html__( 'Border', 'tlp-team' ),
			'id'       => $obj->elPrefix . 'name_border',
			'selector' => '{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'name_border_hover_color',
			'label'     => __( 'Hover Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'name_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .single-team-area h3:hover, {{WRAPPER}} .rt-elementor-container h3:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'name_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'name_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'name_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .single-team-area h3, {{WRAPPER}} .rt-elementor-container h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'name_top_padding',
			'label'      => esc_html__( 'Top Title Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'condition'  => [ $obj->elPrefix . 'layout' => [ 'layout10', 'carousel5' ] ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .tlp-title h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Designation section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function designation( $obj ) {
		$condition = [
			$obj->elPrefix . 'team_designation' => [ 'yes' ],
			// $obj->elPrefix . 'layout!'          => [ 'layout11' ],
		];

		$obj->startSection( 'designation_section', esc_html__( 'Designation', 'tlp-team' ), self::$tab, [], $condition );
		$obj->elHeading( $obj->elPrefix . 'designation_typography_note', esc_html__( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'designation_typography',
			'selector' => '{{WRAPPER}} .tlp-position, {{WRAPPER}} .layout2 .rttm-content-area .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout9 .single-team-area:hover .tlp-position, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row .tlp-position, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position, {{WRAPPER}} .rt-elementor-container .special01 .rt-el-special-wrapper .rt-row .tlp-position',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'designation_alignment',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'tlp-team' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-4 .single-team-area .overlay .overlay-element .tlp-content2 > *, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position, {{WRAPPER}} .rt-elementor-container .carousel9 .single-team-area .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-4 .single-team-area .overlay .overlay-element .tlp-content2>.tlp-position, {{WRAPPER}} .rt-elementor-container .isotope4 .caption-inner-content>.tlp-position, {{WRAPPER}} .rt-elementor-container .layout7 .caption-inner-content>.tlp-position' => 'text-align: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'designation_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'designation_color_tabs' );
		$obj->startTab( 'designation_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-position, {{WRAPPER}} .layout2 .rttm-content-area .tlp-position, {{WRAPPER}} .tlp-overlay1 .tlp-position, {{WRAPPER}} .rt-elementor-container .layout3 .tlp-content .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout12 .tlp-position span, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout14 .rt-grid-item .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row .tlp-position, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position, {{WRAPPER}} .rt-elementor-container .carousel8 .rt-grid-item .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .carousel9 .single-team-area .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .special01 .rt-el-special-wrapper .rt-row .tlp-position' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-position, {{WRAPPER}} .layout2 .rttm-content-area .tlp-position, {{WRAPPER}} .tlp-overlay1 .tlp-position, {{WRAPPER}} .rt-elementor-container .layout3 .tlp-content .tlp-position, {{WRAPPER}} .rt-elementor-container .layout12 .tlp-position span, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_inner_bg_color',
			'label'     => esc_html__( 'Inner Background Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'layout' => [ 'layout12', 'carousel7', 'isotope-el-6' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .layout12 .tlp-position span, {{WRAPPER}} .rt-elementor-container .isotope-el-6 .tlp-position span' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'designation_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-position:hover, {{WRAPPER}} .layout2 .rttm-content-area .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout3 .tlp-content .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout12 .tlp-position:hover span, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout14 .rt-grid-item .tlp-overlay .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .special01 .rt-special-wrapper .rt-row .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .carousel8 .rt-grid-item .tlp-overlay .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .carousel9 .single-team-area .tlp-overlay .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .special01 .rt-el-special-wrapper .rt-row .tlp-position:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-position:hover, {{WRAPPER}} .layout2 .rttm-content-area .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout3 .tlp-content .tlp-position:hover, {{WRAPPER}} .rt-elementor-container .layout12 .tlp-position:hover span, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'designation_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'designation_border',
			'label'    => esc_html__( 'Border', 'tlp-team' ),
			'selector' => '{{WRAPPER}} .tlp-position',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'designation_border_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'designation_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .tlp-position:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'designation_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'designation_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout9 .single-team-area:hover .tlp-position, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'designation_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tlp-position, {{WRAPPER}} .rt-elementor-container .layout-el-8 .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout13 .single-team-area .tlp-overlay .tlp-position, {{WRAPPER}} .rt-elementor-container .layout11 .single-team-area .tlp-title .tlp-position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Department section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function department( $obj ) {
		$condition = [
			$obj->elPrefix . 'team_department' => [ 'yes' ],
			$obj->elPrefix . 'layout!'         => [ 'layout5', 'layout9', 'layout10', 'layout11', 'layout12', 'layout13', 'layout14', 'layout15', 'carousel2', 'carousel4', 'carousel5', 'carousel6', 'carousel6', 'carousel7', 'carousel8', 'carousel9', 'carousel11', 'isotope1', 'isotope2', 'isotope-el-3', 'isotope4', 'isotope5', 'isotope-el-6', 'isotope7', 'isotope8', 'isotope9', 'isotope10', 'carousel3', 'carousel10' ],
		];

		$obj->startSection( 'department_section', esc_html__( 'Department', 'tlp-team' ), self::$tab, [], $condition );
		$obj->elHeading( $obj->elPrefix . 'department_typography_note', esc_html__( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'department_typography',
			'selector' => '{{WRAPPER}} .tlp-department',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'department_alignment',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'tlp-team' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tlp-department, {{WRAPPER}} .rt-elementor-container .layout-el-4 .single-team-area .overlay .overlay-element .tlp-content2>.tlp-department, {{WRAPPER}} .rt-elementor-container .isotope4 .caption-inner-content>.tlp-department, {{WRAPPER}} .rt-elementor-container .layout7 .caption-inner-content>.tlp-department' => 'text-align: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'department_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'department_color_tabs' );
		$obj->startTab( 'department_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'department_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-department' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'department_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-department' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'department_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'department_hover_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-department:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'department_hover_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .tlp-department:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'department_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'department_border',
			'selector' => '{{WRAPPER}} .tlp-department',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'department_border_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'department_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .tlp-department:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'department_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'department_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tlp-department' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'department_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tlp-department' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Contact section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function contact( $obj ) {
		$obj->elControls = Fns::filter( $obj->elPrefix . 'style_contact_section', $obj );

		return new static();
	}

	/**
	 * Skills section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function skills( $obj ) {
		$obj->elControls = Fns::filter( $obj->elPrefix . 'style_skills_section', $obj );

		return new static();
	}

	/**
	 * Social section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function social( $obj ) {
		$obj->elControls = Fns::filter( $obj->elPrefix . 'style_socials_section', $obj );

		return new static();
	}

	/**
	 * Short Biography section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function short_biography( $obj ) {
		$condition = [
			$obj->elPrefix . 'team_short_bio' => [ 'yes' ],
			$obj->elPrefix . 'layout!'        => [ 'layout5', 'layout-el-8', 'layout11', 'layout14', 'layout15', 'carousel3', 'carousel6', 'carousel8', 'carousel9', 'carousel11', 'isotope5', 'isotope7', 'isotope8', 'isotope9', 'isotope10' ],
		];

		$obj->startSection( 'short_biography_section', esc_html__( 'Short Biography', 'tlp-team' ), self::$tab, [], $condition );
		$obj->elHeading( $obj->elPrefix . 'short_biography_typography_note', esc_html__( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'short_biography_typography',
			'selector' => '{{WRAPPER}} .short-bio, {{WRAPPER}} .rt-elementor-container .layout9 .single-team-area .short-bio, {{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .short-bio, {{WRAPPER}} .rt-elementor-container .layout12 .single-team-area .short-bio',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'short_biography_alignment',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'tlp-team' ),
			'options'   => [
				'left'    => [
					'title' => esc_html__( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'  => [
					'title' => esc_html__( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'   => [
					'title' => esc_html__( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
				'justify' => [
					'title' => esc_html__( 'Justify', 'tlp-team' ),
					'icon'  => 'eicon-text-align-justify',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .short-bio, {{WRAPPER}} .rt-elementor-container .layout-el-4 .single-team-area .overlay .overlay-element .tlp-content2 > .short-bio, {{WRAPPER}} .rt-elementor-container .layout7 .tlp-team-item .short-bio, {{WRAPPER}} .rt-elementor-container .layout12 .single-team-area .short-bio, {{WRAPPER}} .rt-elementor-container .layout-el-4 .single-team-area .overlay .overlay-element .tlp-content2>.short-bio, {{WRAPPER}} .rt-elementor-container .isotope4 .caption-inner-content>.short-bio, {{WRAPPER}} .rt-elementor-container .layout7 .caption-inner-content>.short-bio' => 'text-align: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'short_biography_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'short_biography_color_tabs' );
		$obj->startTab( 'short_biography_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'short_biography_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .short-bio, {{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .short-bio' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'short_biography_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .short-bio' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'short_biography_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'short_biography_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .short-bio:hover, {{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .short-bio:hover, {{WRAPPER}} .rt-elementor-container .short-bio a:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'short_biography_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .short-bio:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'short_biography_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'short_biography_border',
			'selector' => '{{WRAPPER}} .short-bio',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'short_biography_border_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'short_biography_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .short-bio:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'short_biography_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'short_biography_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .short-bio, {{WRAPPER}} .rt-elementor-container .layout10 .tlp-overlay .short-bio, {{WRAPPER}} .rt-elementor-container .layout12 .single-team-area .short-bio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'short_biography_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .short-bio, {{WRAPPER}} .rt-elementor-container .layout12 .single-team-area .short-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Buttons section
	 *
	 * @param object $obj Reference object.
	 * @param string $conditions Condition.
	 * @return static
	 */
	public static function buttons( $obj ) {
		$conditions = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => $obj->elPrefix . 'slider_nav',
					'operator' => '==',
					'value'    => 'yes',
				],
				[
					'name'     => $obj->elPrefix . 'slider_pagi',
					'operator' => '==',
					'value'    => 'yes',
				],
			],
		];

		$arrow_condition = [ $obj->elPrefix . 'slider_nav' => [ 'yes' ] ];
		$dot_condition   = [ $obj->elPrefix . 'slider_pagi' => [ 'yes' ] ];

		$obj->startSection( 'buttons_section', esc_html__( 'Slider Buttons', 'tlp-team' ), self::$tab, $conditions );
		$obj->elHeading( $obj->elPrefix . 'buttons_typography_note', esc_html__( 'Arrow Size', 'tlp-team' ), 'null', [], $arrow_condition );

		$obj->elControls[] = [
			'type'      => 'slider',
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'arrow_size',
			'label'     => esc_html__( 'Arrow Size', 'tlp-team' ),
			'range'     => [
				'px' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 16,
			],
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow' => 'font-size: {{SIZE}}{{UNIT}}',
			],
			'condition' => $arrow_condition,
		];

		$obj->elControls[] = [
			'type'      => 'slider',
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'arrow_width',
			'label'     => esc_html__( 'Arrow Width', 'tlp-team' ),
			'range'     => [
				'px'  => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
				'em'  => [
					'min'  => 0.1,
					'max'  => 10,
					'step' => 0.1,
				],
				'rem' => [
					'min'  => 0.1,
					'max'  => 10,
					'step' => 0.1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow' => 'width: {{SIZE}}{{UNIT}}',
			],
			'condition' => $arrow_condition,
		];

		$obj->elControls[] = [
			'type'      => 'slider',
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'arrow_height',
			'label'     => esc_html__( 'Arrow Height', 'tlp-team' ),
			'range'     => [
				'px' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 30,
			],
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow' => 'height: {{SIZE}}{{UNIT}}',
			],
			'condition' => $arrow_condition,
		];

		$obj->elHeading( $obj->elPrefix . 'dot_size_note', esc_html__( 'Dot Size', 'tlp-team' ), 'before', [], $dot_condition );

		$obj->elControls[] = [
			'type'      => 'slider',
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'dot_width',
			'label'     => esc_html__( 'Dot Width', 'tlp-team' ),
			'range'     => [
				'px'  => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
				'em'  => [
					'min'  => 0.1,
					'max'  => 10,
					'step' => 0.1,
				],
				'rem' => [
					'min'  => 0.1,
					'max'  => 10,
					'step' => 0.1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .rt-carousel-holder.swiper .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
			],
			'condition' => $dot_condition,
		];

		$obj->elControls[] = [
			'type'      => 'slider',
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'dot_height',
			'label'     => esc_html__( 'Dot Height', 'tlp-team' ),
			'range'     => [
				'px' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .rt-carousel-holder.swiper .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
			],
			'condition' => $dot_condition,
		];

		$obj->elHeading( $obj->elPrefix . 'buttons_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'button_color_tabs' );
		$obj->startTab( 'button_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow' => 'color: {{VALUE}}',
			],
			'condition' => $arrow_condition,
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow, {{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'button_border',
			'selector' => '{{WRAPPER}} .rt-carousel-holder .swiper-arrow, {{WRAPPER}} .swiper-pagination-bullet',
		];

		$obj->endTab();
		$obj->startTab( 'button_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow:hover' => 'color: {{VALUE}}',
			],
			'condition' => $arrow_condition,
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow:hover, {{WRAPPER}} .rt-elementor-container .rt-carousel-holder .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_border_color',
			'label'     => esc_html__( 'Hover Border Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow:hover, .rt-elementor-container .rt-carousel-holder .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'button_active_color_tab', esc_html__( 'Active', 'tlp-team' ), [], $dot_condition );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_active_bg_color',
			'label'     => esc_html__( 'Active Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .rt-carousel-holder .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_active_border_color',
			'label'     => esc_html__( 'Active Border Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .rt-carousel-holder .swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'buttons_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_wrapper_padding',
			'label'      => esc_html__( 'Wrapper Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-carousel-holder.top-nav .swiper-nav, {{WRAPPER}} .rt-elementor-container .rt-carousel-holder .swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow, {{WRAPPER}} .swiper-pagination-bullet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-carousel-holder .swiper-arrow, {{WRAPPER}} .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Grid button section.
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function oldgridButtons( $obj ) {
		self::buttons( $obj, self::buttonConditions( $obj, 'grid' ) );

		return new static();
	}

	/**
	 * Slider button section.
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function sliderButtons( $obj ) {
		self::buttons( $obj, self::buttonConditions( $obj, 'slider' ) );

		return new static();
	}

	/**
	 * Button Controls Condition.
	 *
	 * @param object $obj Reference object.
	 * @param string $condition Condition.
	 * @return array
	 */
	private static function buttonConditions( $obj, $condition = null ) {
		$conditions = [];

		switch ( $condition ) {
			case 'grid':
				$conditions = [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => $obj->elPrefix . 'show_pagination',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => $obj->elPrefix . 'tax_filter',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				];
				break;

			case 'slider':
				$conditions = [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => $obj->elPrefix . 'slider_nav',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => $obj->elPrefix . 'slider_pagi',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				];
				break;

			case 'isotope':
				$conditions = [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => $obj->elPrefix . 'show_pagination',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => $obj->elPrefix . 'enable_isotope_button',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				];
				break;
		}

		return $conditions;
	}

	/**
	 * Image Sytle Section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function imageStyle( $obj ) {
		$condition = [ $obj->elPrefix . 'show_featured_image' => [ 'yes' ] ];

		$obj->startSection( 'image_style_section', esc_html__( 'Image Style', 'tlp-team' ), self::$tab, [], $condition );

		$obj->elControls[] = [
			'mode'      => 'group',
			'type'      => 'border',
			'id'        => $obj->elPrefix . 'image',
			'selector'  => '{{WRAPPER}} .single-team-area figure, {{WRAPPER}} .rt-elementor-container .layout5 .table figure, {{WRAPPER}} .rt-elementor-container .carousel10 .profile-img-wrap img',
			'separator' => 'after',
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'image_border_radius',
			'label'      => esc_html__( 'Border Radius', 'tlp-team' ),
			'size_units' => [ 'px', '%' ],
			'default'    => [
				'unit'     => '%',
				'isLinked' => true,
			],
			'separator'  => 'after',
			'selectors'  => [
				'{{WRAPPER}} .single-team-area figure, {{WRAPPER}} .rt-elementor-container .layout5 .table figure, {{WRAPPER}} .rt-elementor-container .carousel10 .profile-img-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_image_style_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Gutter Section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function gutter( $obj ) {
		$obj->startSection( 'gutter_section', esc_html__( 'Gutter', 'tlp-team' ), self::$tab );

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_gutter_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Pagination Style Section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function pagination( $obj ) {
		$condition = [
			$obj->elPrefix . 'show_pagination' => [ 'yes' ],
			// $obj->elPrefix . 'pagination_type!'        => [ 'load_more', 'load_on_scroll' ],
			// $obj->elPrefix . 'pagination_type_filter!' => [ 'load_more', 'load_on_scroll' ],
		];

		$activeCondition = [
			// $obj->elPrefix . 'pagination_type!'        => [ 'load_more', 'load_on_scroll' ],
			// $obj->elPrefix . 'pagination_type_filter!' => [ 'load_more', 'load_on_scroll' ],
		];

		$obj->startSection( 'buttons_section', esc_html__( 'Pagination Style', 'tlp-team' ), self::$tab, [], $condition );
		$obj->elHeading( $obj->elPrefix . 'buttons_typography_note', esc_html__( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'buttons_typography',
			'exclude'  => [ 'font_family', 'word_spacing', 'letter_spacing', 'text_transform', 'font_style', 'text_decoration' ],
			'selector' => '{{WRAPPER}} .rt-elementor-container .pagination span, {{WRAPPER}} .rt-elementor-container .pagination a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'buttons_alignment',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'tlp-team' ),
			'options'   => [
				'flex-start' => [
					'title' => esc_html__( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'     => [
					'title' => esc_html__( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'flex-end'   => [
					'title' => esc_html__( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap' => 'justify-content: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'buttons_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'button_color_tabs' );
		$obj->startTab( 'button_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_color',
			'label'     => esc_html__( 'Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_bg_color',
			'label'     => esc_html__( 'Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'button_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'button_active_color_tab', esc_html__( 'Active', 'tlp-team' ), [], $activeCondition );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_active_color',
			'label'     => esc_html__( 'Active Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > .active > span, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active > a' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_active_bg_color',
			'label'     => esc_html__( 'Active Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > .active > span, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active > a' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab( [], $activeCondition );
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'buttons_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'button_border',
			'selector' => '{{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .pagination > .active > span, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_hover_border_color',
			'label'     => __( 'Hover Border Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'button_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a:hover, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'button_active_border_color',
			'label'     => esc_html__( 'Active Border Color', 'tlp-team' ),
			'condition' => [
				$obj->elPrefix . 'button_border_border!' => [ '' ],
				// $obj->elPrefix . 'pagination_type!'        => [ 'load_more', 'load_on_scroll' ],
				// $obj->elPrefix . 'pagination_type_filter!' => [ 'load_more', 'load_on_scroll' ],
			],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li.active > span, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active > a' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'button_border_radius',
			'label'      => esc_html__( 'Border Radius', 'tlp-team' ),
			'size_units' => [ 'px', '%' ],
			'default'    => [
				'unit'     => 'px',
				'isLinked' => true,
			],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .pagination > li > span, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'buttons_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_wrapper_padding',
			'label'      => esc_html__( 'Wrapper Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .pagination, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > span, {{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'buttons_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .pagination > li > span, {{WRAPPER}} .rt-elementor-container .pagination > li > a, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li, {{WRAPPER}} .rt-elementor-container .rt-pagination-wrap .rt-loadmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Ajax Filter Style Section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function filterStyle( $obj ) {
		$obj->elControls = Fns::filter( $obj->elPrefix . 'ajax_filter_style_section', $obj );

		return new static();
	}

	/**
	 * Isotope Filter Style Section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function filterButtons( $obj ) {
		$obj->startSection(
			'filter_buttons_section',
			esc_html__( 'Filter Button', 'tlp-team' ),
			\Elementor\Controls_Manager::TAB_STYLE,
			[],
			[ $obj->elPrefix . 'enable_isotope_button' => [ 'yes' ] ]
		);

		$obj->elHeading( $obj->elPrefix . 'filter_buttons_typography_note', esc_html__( 'Typography', 'tlp-team' ) );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'typography',
			'id'       => $obj->elPrefix . 'filter_buttons_typography',
			'selector' => '{{WRAPPER}} .rt-elementor-container .button-group button',
		];

		$obj->elControls[] = [
			'mode'      => 'responsive',
			'id'        => $obj->elPrefix . 'filter_buttons_alignment',
			'type'      => 'choose',
			'label'     => esc_html__( 'Alignment', 'tlp-team' ),
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'tlp-team' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'tlp-team' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'tlp-team' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group' => 'text-align: {{VALUE}}',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'filter_buttons_colors_note', esc_html__( 'Colors', 'tlp-team' ), 'before' );

		$obj->startTabGroup( 'filter_button_color_tabs' );
		$obj->startTab( 'filter_button_color_tab', esc_html__( 'Normal', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_color',
			'label'     => esc_html__( 'Button Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group button' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_bg_color',
			'label'     => esc_html__( 'Button Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group button' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'filter_button_hover_color_tab', esc_html__( 'Hover', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_hover_color',
			'label'     => esc_html__( 'Hover Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group button:hover' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_hover_bg_color',
			'label'     => esc_html__( 'Hover Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group button:hover' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->startTab( 'filter_button_active_color_tab', esc_html__( 'Active', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_active_color',
			'label'     => esc_html__( 'Active Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group .selected' => 'color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_active_bg_color',
			'label'     => esc_html__( 'Active Background Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group .selected' => 'background-color: {{VALUE}}',
			],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elHeading( $obj->elPrefix . 'filter_buttons_border_note', esc_html__( 'Border', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'     => 'group',
			'type'     => 'border',
			'id'       => $obj->elPrefix . 'filter_button_border',
			'selector' => '{{WRAPPER}} .rt-elementor-container .button-group button',
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_hover_border_color',
			'label'     => esc_html__( 'Hover Border Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'filter_button_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group button:hover' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'filter_button_active_border_color',
			'label'     => esc_html__( 'Active Border Color', 'tlp-team' ),
			'condition' => [ $obj->elPrefix . 'filter_button_border_border!' => [ '' ] ],
			'selectors' => [
				'{{WRAPPER}} .rt-elementor-container .button-group .selected' => 'border-color: {{VALUE}}',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'filter_button_border_radius',
			'label'      => esc_html__( 'Border Radius', 'tlp-team' ),
			'size_units' => [ 'px', '%' ],
			'default'    => [
				'unit'     => 'px',
				'isLinked' => true,
			],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .button-group button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elHeading( $obj->elPrefix . 'filter_buttons_spacing_note', esc_html__( 'Spacing', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'filter_buttons_wrapper_padding',
			'label'      => esc_html__( 'Wrapper Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .button-group' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'filter_buttons_padding',
			'label'      => esc_html__( 'Padding', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .button-group button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'filter_buttons_margin',
			'label'      => esc_html__( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .rt-elementor-container .button-group button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		];

		$obj->endSection();

		return new static();
	}
}
