<?php
/**
 * Elementor Addons Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend;

use Elementor\Plugin as Elementor;
use RT\Team\Widgets\Elementor as Widgets;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Addons Class.
 */
class ElementorAddons {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/widgets/register', [ $this, 'registerWidgets' ] );
		}

		add_action( 'elementor/controls/register', [ $this, 'registerControls' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'addCategory' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editorScript' ] );
	}

	/**
	 * Registers Elementor Widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function registerWidgets( $widgets_manager ) {
		$widgets = apply_filters(
			'rttm_elementor_widgets',
			[
				Widgets\Elements\GridLayout::class,
				Widgets\Elements\ListLayout::class,
				Widgets\Elements\SliderLayout::class,
				Widgets\Elements\IsotopeLayout::class,
				Widgets\Elements\Shortcodes::class,
			]
		);

		foreach ( $widgets as $widget ) {
			$widgets_manager->register( new $widget() );
		}
	}

	/**
	 * RT category.
	 *
	 * @param object $elements_manager Elements Manager.
	 * @return void
	 */
	public function addCategory( $elements_manager ) {
		$categories['rttm-elementor-widgets'] = [
			'title' => __( 'Team Member Showcase', 'tlp-team' ),
			'icon'  => 'fa fa-plug',
		];

		$el_categories = $elements_manager->get_categories();
		$categories    = array_merge(
			array_slice( $el_categories, 0, 1 ),
			$categories,
			array_slice( $el_categories, 1 )
		);

		$set_categories = function( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	/**
	 * Registers Custom controls.
	 *
	 * @param object $controls_manager Controls Manager.
	 * @return void
	 */
	public function registerControls( $controls_manager ) {
		$controls = apply_filters(
			'rttm_elementor_custom_controls',
			[
				Widgets\Controls\ImageSelector::class,
			]
		);

		foreach ( $controls as $control ) {
			$controls_manager->register( new $control() );
		}
	}

	/**
	 * Elementor editor scripts
	 *
	 * @return void
	 */
	public function editorScript() {
		wp_enqueue_script( 'rttm-el-editor-scripts', rttlp_team()->assets_url() . 'js/elementor-editor.js', [ 'jquery' ], '1.0.0', true );
		wp_enqueue_style( 'rttm-el-editor-style', rttlp_team()->assets_url() . 'css/elementor-editor.min.css', [], '1.0.0' );
	}
}
