<?php
/**
 * Filter Hook Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Hooks;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Filter Hook  Class.
 */
class FilterHooks {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class.
	 *
	 * @var string
	 */
	public $classes = '';

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$this->classes = ! rttlp_team()->has_pro() ? 'rttm-pro-field' : '';

		\add_filter( 'rttm_elementor_grid_layouts', [ $this, 'gridLayouts' ] );
		\add_filter( 'rttm_elementor_list_layouts', [ $this, 'listLayouts' ] );
		\add_filter( 'rttm_elementor_slider_layouts', [ $this, 'sliderLayouts' ] );
		\add_filter( 'rttm_elementor_isotope_layouts', [ $this, 'isotopeLayouts' ] );

		if ( rttlp_team()->has_pro() ) {
			return;
		}

		\add_filter( 'rttm_el_end_of_columns_section', [ $this, 'layoutControls' ] );
		\add_filter( 'rttm_el_after_slide_items', [ $this, 'slideItems' ] );
		\add_filter( 'rttm_el_query_tax_filter', [ $this, 'taxControls' ] );
		\add_filter( 'rttm_el_end_of_image_section', [ $this, 'imageControls' ] );
		\add_filter( 'rttm_el_after_show_pagination', [ $this, 'paginationControls' ] );
		\add_filter( 'rttm_el_after_show_isotope_pagination', [ $this, 'IsoPaginationControls' ] );
		\add_filter( 'rttm_el_end_of_links_section', [ $this, 'linksControls' ] );
		\add_filter( 'rttm_el_end_of_details_tab', [ $this, 'visibilityControls' ] );
		\add_filter( 'rttm_el_filter_section', [ $this, 'filterControls' ] );
		\add_filter( 'rttm_el_isotope_section', [ $this, 'isotopeControls' ] );
		\add_filter( 'rttm_el_color_scheme', [ $this, 'colorControls' ] );
		\add_filter( 'rttm_el_end_of_gutter_section', [ $this, 'gutterControls' ] );
		\add_filter( 'rttm_el_end_of_image_style_section', [ $this, 'imageStyleControls' ] );
		\add_filter( 'rttm_el_ajax_filter_style_section', [ $this, 'filterStyleControls' ] );
	}

	/**
	 * Grid Layouts
	 *
	 * @param array $layouts Layouts.
	 * @return array
	 */
	public function gridLayouts( $layouts ) {
		$status = ! rttlp_team()->has_pro();

		$layouts['layout-el-4'] = [
			'title'  => esc_html__( 'Layout 3', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout4.png',
			'is_pro' => $status,
		];

		$layouts['layout-el-6'] = [
			'title'  => esc_html__( 'Layout 4', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout6.png',
			'is_pro' => $status,
		];

		$layouts['layout7'] = [
			'title'  => esc_html__( 'Layout 5', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout7.png',
			'is_pro' => $status,
		];

		$layouts['layout-el-8'] = [
			'title'  => esc_html__( 'Layout 6', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout8.png',
			'is_pro' => $status,
		];

		$layouts['layout9'] = [
			'title'  => esc_html__( 'Layout 7', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout9.png',
			'is_pro' => $status,
		];

		$layouts['layout-el-10'] = [
			'title'  => esc_html__( 'Layout 8', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout10.png',
			'is_pro' => $status,
		];

		for ( $i = 9; $i < 14; $i++ ) {
			$layouts[ 'layout' . ( $i + 2 ) ] = [
				'title'  => esc_html( 'Layout ' ) . ( $i ),
				'url'    => rttlp_team()->assets_url() . 'images/layouts/layout' . ( $i + 2 ) . '.png',
				'is_pro' => $status,
			];
		}

		$layouts['special01'] = [
			'title'  => esc_html__( 'Special 01', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/special01.png',
			'is_pro' => $status,
		];

		return $layouts;
	}

	/**
	 * List Layouts
	 *
	 * @param array $layouts Layouts.
	 * @return array
	 */
	public function listLayouts( $layouts ) {
		$status = ! rttlp_team()->has_pro();

		$layouts['layout5'] = [
			'title'  => esc_html__( 'List 2', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/layout5.png',
			'is_pro' => $status,
		];

		return $layouts;
	}

	/**
	 * Slider Layouts
	 *
	 * @param array $layouts Layouts.
	 * @return array
	 */
	public function sliderLayouts( $layouts ) {
		$status = ! rttlp_team()->has_pro();

		$layouts['carousel-el-2'] = [
			'title'  => esc_html__( 'Carousel 2', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/carousel2.png',
			'is_pro' => $status,
		];

		for ( $i = 3; $i < 12; $i++ ) {
			$layouts[ "carousel{$i}" ] = [
				'title'  => esc_html( 'Carousel ' ) . $i,
				'url'    => rttlp_team()->assets_url() . "images/layouts/carousel{$i}.png",
				'is_pro' => $status,
			];
		}

		return $layouts;
	}

	/**
	 * Isotope Layouts
	 *
	 * @param array $layouts Layouts.
	 * @return array
	 */
	public function isotopeLayouts( $layouts ) {
		$status = ! rttlp_team()->has_pro();

		$layouts['isotope1'] = [
			'title'  => esc_html__( 'Isotope 2', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope1.png',
			'is_pro' => $status,
		];

		$layouts['isotope2'] = [
			'title'  => esc_html__( 'Isotope 3', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope2.png',
			'is_pro' => $status,
		];

		$layouts['isotope-el-3'] = [
			'title'  => esc_html__( 'Isotope 4', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope3.png',
			'is_pro' => $status,
		];

		$layouts['isotope4'] = [
			'title'  => esc_html__( 'Isotope 5', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope4.png',
			'is_pro' => $status,
		];

		$layouts['isotope5'] = [
			'title'  => esc_html__( 'Isotope 6', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope5.png',
			'is_pro' => $status,
		];

		$layouts['isotope-el-6'] = [
			'title'  => esc_html__( 'Isotope 7', 'tlp-team' ),
			'url'    => rttlp_team()->assets_url() . 'images/layouts/isotope6.png',
			'is_pro' => $status,
		];

		for ( $i = 7; $i < 11; $i++ ) {
			$layouts[ "isotope{$i}" ] = [
				'title'  => esc_html( 'Isotope ' ) . ( $i + 1 ),
				'url'    => rttlp_team()->assets_url() . "images/layouts/isotope{$i}.png",
				'is_pro' => $status,
			];
		}

		return $layouts;
	}

	/**
	 * Layout Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function layoutControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'grid_style_promo',
			'label'       => __( 'Grid Style', 'tlp-team' ),
			'options'     => [
				'even'    => __( 'Even', 'tlp-team' ),
				'masonry' => __( 'Masonry', 'tlp-team' ),
			],
			'default'     => 'masonry',
			'description' => __( 'Please select the grid style.', 'tlp-team' ),
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Slider items
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function slideItems( $obj ) {
		$obj->elControls[] = [
			'type'           => 'select2',
			'id'             => $obj->elPrefix . 'slide_groups_promo',
			'mode'           => 'responsive',
			'label'          => __( 'Number of Slides Per Groups', 'tlp-team' ),
			'description'    => __( 'Please select the number of slides per group.', 'tlp-team' ),
			'options'        => Options::scElColumns(),
			'default'        => '0',
			'tablet_default' => '0',
			'mobile_default' => '0',
			'label_block'    => true,
			'classes'        => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Category Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function taxControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'filter_department',
			'label'       => __( 'Filter By Departments', 'tlp-team' ),
			'options'     => Fns::getAllTermsByTaxonomyName( 'department' ),
			'description' => __( 'Select the departments you want to filter, Leave it blank for all departments.', 'tlp-team' ),
			'multiple'    => true,
			'label_block' => true,
			'separator'   => 'after',
		];

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'filter_designation_promo',
			'label'       => __( 'Filter By Designations', 'tlp-team' ),
			'options'     => Fns::getAllTermsByTaxonomyName( 'designation' ),
			'description' => __( 'Select the designations you want to filter, Leave it blank for all designations.', 'tlp-team' ),
			'multiple'    => true,
			'label_block' => true,
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Image Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function imageControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'grayscale_promo',
			'label'       => __( 'Grayscale Image', 'tlp-team' ),
			'description' => __( 'Image will be at grayscale.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'condition'   => [ $obj->elPrefix . 'show_featured_image' => [ 'yes' ] ],
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Pagination Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function paginationControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'pagination_type',
			'label'       => __( 'Pagination Type', 'tlp-team' ),
			'description' => __( 'Please choose the pagination type.<span class="elementor-pro-notice"><a target="_blank" href="//www.radiustheme.com/downloads/tlp-team-pro-for-wordpress/">Upgrade to PRO</a> to unlock Load More and Ajax Pagination.</span>', 'tlp-team' ),
			'options'     => [
				'pagination' => __( 'Number Pagination', 'tlp-team' ),
			],
			'default'     => 'pagination',
			'separator'   => 'before',
			'label_block' => true,
			'condition'   => [ $obj->elPrefix . 'show_pagination' => [ 'yes' ] ],
		];

		return $obj->elControls;
	}

	/**
	 * Pagination Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function isoPaginationControls( $obj ) {
		$obj->elControls[] = [
			'type'            => 'html',
			'id'              => $obj->elPrefix . 'ajax_pagination_note',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'show_pagination_promo',
			'label'       => __( 'Show Ajax Load More <br>Pagination?', 'tlp-team' ),
			'description' => __( 'Switch on to enable Ajax Load More pagination.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Link Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function linksControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'link_promo',
			'label'       => __( 'Detail Page Link Type', 'tlp-team' ),
			'description' => __( 'Please choose the detail page link type.', 'tlp-team' ),
			'options'     => [
				'popup' => __( 'Pop Up', 'tlp-team' ),
			],
			'default'     => 'popup',
			'label_block' => true,
			'condition'   => [ $obj->elPrefix . 'detail_page_link' => [ 'yes' ] ],
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Visibility Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function visibilityControls( $obj ) {
		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_skills_promo',
			'label'       => __( 'Show Team Member Skills?', 'tlp-team' ),
			'description' => __( 'Switch on to show team member skills.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Filter Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function filterControls( $obj ) {
		$obj->elControls[] = [
			'type'            => 'html',
			'id'              => $obj->elPrefix . 'filter_note',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'filter_promo',
			'label'       => __( 'Show Search Filter?', 'tlp-team' ),
			'description' => __( 'Switch on to show search filter.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Isotope Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function isotopeControls( $obj ) {
		$obj->elControls[] = [
			'type'            => 'html',
			'id'              => $obj->elPrefix . 'filter_note',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'filter_promo',
			'label'       => __( 'Isotope Taxonomy Filter', 'tlp-team' ),
			'description' => __( 'Please select the filter taxonomy source.', 'tlp-team' ),
			'options'     => Fns::rt_get_all_taxonomy_by_post_type(),
			'default'     => 'team_department',
			'label_block' => true,
			'classes'     => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Color Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function colorControls( $obj ) {
		$obj->elControls[] = [
			'type'      => 'color',
			'id'        => $obj->elPrefix . 'primary_color',
			'label'     => __( 'Primary Color', 'tlp-team' ),
			'selectors' => [
				'{{WRAPPER}}' => '--rttm-primary-color: {{VALUE}}',
			],
		];

		return $obj->elControls;
	}

	/**
	 * Gutter Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function gutterControls( $obj ) {
		$obj->elControls[] = [
			'type'            => 'html',
			'id'              => $obj->elPrefix . 'gutter_note',
			'raw'             => '',
			'content_classes' => 'elementor-panel-heading-title',
		];

		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'element_padding_promo',
			'label'      => __( 'Element/Box Gutter', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'classes'    => $this->classes,
		];

		return $obj->elControls;
	}

	/**
	 * Image Style Controls
	 *
	 * @param object $obj Variable.
	 * @return array
	 */
	public function imageStyleControls( $obj ) {
		$obj->elControls[] = [
			'mode'       => 'responsive',
			'type'       => 'dimensions',
			'id'         => $obj->elPrefix . 'image_alignment_promo',
			'label'      => __( 'Margin', 'tlp-team' ),
			'size_units' => [ 'px', '%', 'em' ],
			'classes'    => $this->classes,
		];

		return $obj->elControls;
	}
}
