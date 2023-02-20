<?php
/**
 * Elementor Custom Control: Image Selector Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Controls;

use Elementor\Base_Data_Control as Control;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Image Selector Class.
 */
class ImageSelector extends Control {

	/**
	 * Set control name.
	 *
	 * @var string
	 */
	public static $controlName = 'rttm-image-selector';

	/**
	 * Set control type.
	 *
	 * @return string
	 */
	public function get_type() {
		return self::$controlName;
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue() {
		\wp_enqueue_style( 'rttm-image-selector', rttlp_team()->assets_url() . 'css/image-selector.min.css', [], '1.0.0' );
	}

	/**
	 * Set default settings
	 *
	 * @return array
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'toggle'      => true,
			'options'     => [],
		];
	}

	/**
	 * Control field markup
	 *
	 * @return void
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{ value }}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description rttm-description">{{{ data.description }}}</div>
			<# } #>
			<div class="elementor-control-image-selector-wrapper">
				<# _.each( data.options, function( options, value ) { #>
				<div class="image-selector-inner{{ options.is_pro ? ' rttm-pro' : '' }}" title="{{ ! options.is_pro ? '' : 'Upgrade to PRO!' }}" data-tooltip="{{ ! options.is_pro ? options.title : 'Upgrade to PRO!' }}">
					<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
					<label class="elementor-image-selector-label tooltip-target{{ options.is_pro ? ' is-pro' : '' }}" for="<?php echo esc_attr( $control_uid ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
						<img src="{{ options.url }}" alt="{{ options.title }}">
						<span class="elementor-screen-only">{{{ options.title }}}</span>
					</label>
				</div>
				<# } ); #>
			</div>
		</div>
		<?php
	}
}
