<?php
/**
 * Elementor Grid Widget Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Elements;

use RT\Team\Helpers\Fns;
use RT\Team\Abstracts\ElementorWidget;
use RT\Team\Widgets\Elementor\Sections\{
	Style,
	Layout,
	Settings
};

use RT\Team\Widgets\Elementor\Render\GridView;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Grid Widget Class.
 */
class GridLayout extends ElementorWidget {

	/**
	 * Class constructor.
	 *
	 * @param array $data default data.
	 * @param array $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->elBase = 'rttm-team-grid';
		$this->elName = esc_html__( 'Grid Layouts', 'tlp-team' );
		$this->elIcon = 'eicon-gallery-grid rttm-element';

		parent::__construct( $data, $args );
	}

	/**
	 * Script dependancies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		$scripts = [];

		if ( ! $this->isPreview() ) {
			return $scripts;
		}

		return [
			'tlp-image-load-js',
			'rt-pagination',
			'rt-tooltip',
			'rt-scrollbox',
			'tlp-el-team-js',
		];
	}

	/**
	 * Style dependancies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		$styles = [];

		if ( ! $this->isPreview() ) {
			return $styles;
		}

		$styles = [
			'rt-pagination',
			'tlp-fontawsome',
			'tlp-el-team-css',
		];

		return $styles;
	}

	/**
	 * Controls for layout tab
	 *
	 * @return object
	 */
	protected function layoutTab() {
		$sections = [
			'gridLayout',
			'columns',
			'query',
			'pagination',
			'image',
		];

		foreach ( $sections as $section ) {
			Layout::$section( $this );
		}

		return $this;
	}

	/**
	 * Controls for settings tab
	 *
	 * @return object
	 */
	protected function settingsTab() {
		$sections = [
			'ContentVisibility',
			'filter',
			'links',
			'contentLimit',
		];

		foreach ( $sections as $section ) {
			Settings::$section( $this );
		}

		return $this;
	}

	/**
	 * Controls for style tab
	 *
	 * @return object
	 */
	protected function styleTab() {
		$sections = apply_filters(
			$this->elPrefix . 'grid_style_section',
			[
				'colorScheme',
				'name',
				'designation',
				'department',
				'short_biography',
				'pagination',
				'imageStyle',
				'gutter',
			]
		);

		foreach ( $sections as $section ) {
			Style::$section( $this );
		}

		return $this;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		Fns::print_html( GridView::get_instance()->render( $this->elPrefix, $settings ) );

		$this->edit_mode_script();
	}
}
