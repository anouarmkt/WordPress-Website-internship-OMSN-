<?php
/**
 * Elementor Shortcodes List Widget Class.
 *
 * This widget is deprecated and will be removed in some future version.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Elements;

use RT\Team\Abstracts\ElementorWidget;
use RT\Team\Widgets\Elementor\Sections\Layout;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Shortcodes List Widget Class.
 */
class Shortcodes extends ElementorWidget {

	/**
	 * Class constructor.
	 *
	 * @param array $data default data.
	 * @param array $args default arg.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->elBase = 'tlp-team';
		$this->elName = esc_html__( 'Shortcodes (Deprecated)', 'tlp-team' );
		$this->elIcon = 'eicon-shortcode rttm-element';

		parent::__construct( $data, $args );
	}

	/**
	 * Script dependancies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [];
	}

	/**
	 * Style dependancies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [];
	}

	/**
	 * Controls for settings tab
	 *
	 * @return object
	 */
	protected function settingsTab() {
		$sections = [
			'shortcodeList',
		];

		foreach ( $sections as $section ) {
			Layout::$section( $this );
		}

		return $this;
	}

	/**
	 * Controls for style tab
	 *
	 * @return object
	 */
	protected function styleTab() {
		return $this;
	}

	/**
	 * Controls for layout tab
	 *
	 * @return object
	 */
	protected function layoutTab() {
		return $this;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id       = $settings['short_code_id'];

		if ( $this->isPreview() ) {
			return;
		}

		if ( isset( $id ) && ! empty( $id ) && $id ) {
			echo do_shortcode( '[tlpteam id="' . absint( $id ) . '" ]' );
		} else {
			esc_html_e( 'Please select a shortcode from the list.', 'tlp-team' );
		}

		$this->edit_mode_script();
	}


	/**
	 * Elementor Edit mode need some extra js for isotop reinitialize
	 *
	 * @return mixed
	 */
	public function edit_mode_script() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script>
				initTlpTeam();
			</script>
			<?php
		}
	}
}
