<?php
/**
 * Model: Fields.
 *
 * @package RT_Team
 */

namespace RT\Team\Models;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Model: Fields.
 */
class Fields {
	private $type;
	private $name;
	private $value;
	private $default;
	private $label;
	private $id;
	private $class;
	private $holderClass;
	private $description;
	private $descriptionAdv;
	private $options;
	private $option;
	private $optionLabel;
	private $attr;
	private $multiple;
	private $alignment;
	private $placeholder;
	private $metaExist = false;
	private $blank;

	function __construct() {
	}

	private function setArgument( $key, $attr ) {
		$this->type     = isset( $attr['type'] ) ? ( $attr['type'] ? $attr['type'] : 'text' ) : 'text';
		$this->multiple = isset( $attr['multiple'] ) ? ( $attr['multiple'] ? $attr['multiple'] : false ) : false;
		$this->name     = ! empty( $key ) ? $key : null;
		$id             = ! empty( $attr['id'] ) ? $attr['id'] : null;
		$this->id       = ! empty( $id ) ? $id : $this->name;
		$this->default  = ! empty( $attr['default'] ) ? $attr['default'] : null;
		$this->value    = ! empty( $attr['value'] ) ? $attr['value'] : null;

		if ( ! $this->value ) {
			$post_id = get_the_ID();
			if ( ! Fns::meta_exist( $post_id, $this->name ) ) {
				$this->value = $this->default;
			} else {
				$this->metaExist = true;
				if ( $this->multiple ) {
					$this->value = get_post_meta( $post_id, $this->name );
				} else {
					$this->value = get_post_meta( $post_id, $this->name, true );
				}
			}
		}

		$this->label          = isset( $attr['label'] ) ? ( $attr['label'] ? $attr['label'] : null ) : null;
		$this->class          = isset( $attr['class'] ) ? ( $attr['class'] ? $attr['class'] : null ) : null;
		$this->holderClass    = isset( $attr['holderClass'] ) ? ( $attr['holderClass'] ? $attr['holderClass'] : null ) : null;
		$this->placeholder    = isset( $attr['placeholder'] ) ? ( $attr['placeholder'] ? $attr['placeholder'] : null ) : null;
		$this->description    = isset( $attr['description'] ) ? ( $attr['description'] ? $attr['description'] : null ) : null;
		$this->descriptionAdv = isset( $attr['description_adv'] ) ? ( $attr['description_adv'] ? $attr['description_adv'] : null ) : null;
		$this->options        = isset( $attr['options'] ) ? ( $attr['options'] ? $attr['options'] : [] ) : [];
		$this->option         = isset( $attr['option'] ) ? ( $attr['option'] ? $attr['option'] : null ) : null;
		$this->optionLabel    = isset( $attr['optionLabel'] ) ? ( $attr['optionLabel'] ? $attr['optionLabel'] : null ) : null;
		$this->attr           = isset( $attr['attr'] ) ? ( $attr['attr'] ? $attr['attr'] : null ) : null;
		$this->alignment      = isset( $attr['alignment'] ) ? ( $attr['alignment'] ? $attr['alignment'] : null ) : null;
		$this->blank          = ! empty( $attr['blank'] ) ? $attr['blank'] : null;
	}

	public function Field( $key, $attr = [] ) {
		$this->setArgument( $key, $attr );
		$holderId = $this->name . '_holder';
		$html     = null;
		$html    .= '<div class="tlp-field-holder ' . esc_attr( $this->holderClass ) . '" id="' . esc_attr( $holderId ) . '">';

		if ( $this->label ) {
			$pro_label = ( isset( $attr['is_pro'] ) && $attr['is_pro'] ) && ! function_exists( 'rttmp' ) ? '<span class="rttm-pro rttm-tooltip">' . esc_html__( '[Pro]', 'tlp-team' ) . '<span class="rttm-tooltiptext">' . esc_html__( 'This is premium field', 'tlp-team' ) . '</span></span>' : '';
			$pro_label = apply_filters( 'rttm_pro_label', $pro_label );

			$html .= "<div class='tlp-label field-label'>";
			$html .= '<label for="' . esc_attr( $this->id ) . '">' . Fns::htmlKses( $this->label, 'basic' ) . ' ' . $pro_label . '</label>';
			$html .= '</div>';
		}

		$pro_class = ( isset( $attr['is_pro'] ) && $attr['is_pro'] ) && ! function_exists( 'rttmp' ) ? 'pro-field' : '';

		$html .= '<div class="tlp-field field ' . esc_attr( $pro_class ) . '">';

		if ( ( isset( $attr['is_pro'] ) && $attr['is_pro'] ) && ! function_exists( 'rttmp' ) ) {
			$html .= '<div class="pro-field-overlay"></div>';
		}

		switch ( $this->type ) {
			case 'text':
				$html .= $this->text();
				break;

			case 'url':
				$html .= $this->url();
				break;
			case 'email':
				$html .= $this->email();
				break;

			case 'number':
				$html .= $this->number();
				break;

			case 'select':
				$html .= $this->select();
				break;

			case 'textarea':
				$html .= $this->textArea();
				break;

			case 'checkbox':
				$html .= $this->checkbox();
				break;

			case 'switch':
				$html .= $this->switchField();
				break;

			case 'radio':
				$html .= $this->radioField();
				break;

			case 'radio-image':
				$html .= $this->radioImage();
				break;

			case 'multiple_options':
				$html .= $this->multipleOption( $this->options );
				break;
			case 'image':
				$html .= $this->image();
				break;

			case 'image_size':
				$html .= $this->imageSize();
				break;
			case 'style':
				$html .= $this->smartStyle();
				break;
		}

		if ( $this->description ) {
			$html .= '<p class="description">' . Fns::htmlKses( $this->description, 'basic' ) . '</p>';
		}

		if ( $this->descriptionAdv ) {
			$html .= '<p class="description">' . Fns::htmlKses( $this->descriptionAdv, 'advanced' ) . '</p>';
		}

		$html .= '</div>'; // field
		$html .= '</div>'; // field holder

		return $html;
	}

	private function text() {
		$h  = null;
		$h .= '<input
				type="text"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->id ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	private function image() {
		$h   = null;
		$h  .= "<div class='rt-image-holder'>";
		$h  .= '<input type="hidden" name="' . esc_attr( $this->name ) . '" value="' . absint( $this->value ) . '" id="' . esc_attr( $this->id ) . '" class="hidden-image-id" />';
		$img = null;
		$c   = 'hidden';

		if ( $id = absint( $this->value ) ) {
			$aImg = wp_get_attachment_image_src( $id, 'thumbnail' );
			$img  = '<img src="' . esc_url( $aImg[0] ) . '" >';
			$c    = null;
		}

		$h .= '<div class="rt-image-preview">' . Fns::htmlKses( $img, 'image' ) . '<span class="dashicons dashicons-plus-alt rtAddImage"></span><span class="dashicons dashicons-trash rtRemoveImage ' . esc_attr( $c ) . '"></span></div>';
		$h .= '</div>';

		return $h;
	}

	private function imageSize() {

		$width  = ( ! empty( $this->value['width'] ) ? $this->value['width'] : null );
		$height = ( ! empty( $this->value['height'] ) ? $this->value['height'] : null );
		$cropV  = ( ! empty( $this->value['crop'] ) ? $this->value['crop'] : 'soft' );

		$h = null;

		$h .= "<div class='multiple-field-rt-container rt-clear'>";
		$h .= "<div class='field-inner col-3'>";
		$h .= "<div class='field-inner-rt-container img-width'>";
		$h .= "<span class='label'>Width</span>";
		$h .= '<input type="number" class="small-text" name="' . esc_attr( $this->name ) . '[width]" value="' . absint( $width ) . '" />';
		$h .= '</div>';
		$h .= '</div>';

		$h .= "<div class='field-inner col-3'>";
		$h .= "<div class='field-inner-rt-container img-height'>";
		$h .= "<span class='label'>Height</span>";
		$h .= '<input type="number" class="small-text" name="' . esc_attr( $this->name ) . '[height]" value="' . absint( $height ) . '" />';
		$h .= '</div>';
		$h .= '</div>';

		$h .= "<div class='field-inner col-3'>";
		$h .= "<div class='field-inner-rt-container img-crop'>";
		$h .= "<span class='label'>Crop</span>";
		$h .= '<select name="' . esc_attr( $this->name ) . '[crop]" class="tlp-select">';

		$cropList = Options::imageCropType();

		foreach ( $cropList as $crop => $cropLabel ) {
			$cSl = ( $crop == $cropV ? 'selected' : null );
			$h  .= '<option value="' . esc_attr( $crop ) . '" ' . esc_attr( $cSl ) . '>' . esc_html( $cropLabel ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';
		$h .= '</div>';

		return $h;
	}

	private function url() {
		$h  = null;
		$h .= '<input
				type="url"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->id ) . '"
				value="' . esc_url( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	private function email() {
		$h  = null;
		$h .= '<input
				type="email"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->id ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	private function number() {
		$h  = null;
		$h .= '<input
				type="number"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->id ) . '"
				value="' . ( ! empty( $this->value ) ? absint( $this->value ) : null ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	private function select() {
		$h = null;
		if ( $this->multiple ) {
			$this->attr  = " style='display: none;'";
			$this->name  = $this->name . '[]';
			$this->attr  = $this->attr . " multiple='multiple'";
			$this->value = ( is_array( $this->value ) && ! empty( $this->value ) ? $this->value : [] );
		} else {
			$this->value = [ $this->value ];
		}

		$h .= '<select name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->id ) . '" class="' . esc_attr( $this->class ) . '" ' . Fns::htmlKses( $this->attr, 'basic' ) . '>';

		if ( $this->blank ) {
			$h .= '<option value="">' . esc_html( $this->blank ) . '</option>';
		}

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$slt = ( in_array( $key, $this->value ) ? 'selected' : null );
				$h  .= '<option ' . esc_attr( $slt ) . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
			}
		}

		$h .= '</select>';

		return $h;
	}

	private function textArea() {
		$h  = null;
		$h .= '<textarea
				class="' . esc_attr( $this->class ) . ' rt-textarea"
				id="' . esc_attr( $this->id ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				>' . wp_kses_post( $this->value ) . '</textarea>';

		return $h;
	}

	private function checkbox() {
		$h = null;

		if ( $this->multiple ) {
			$this->name  = $this->name . '[]';
			$this->value = ( is_array( $this->value ) && ! empty( $this->value ) ? $this->value : [] );
		}

		if ( $this->multiple ) {
			$h .= '<div class="checkbox-group ' . esc_attr( $this->alignment ) . '" id="' . esc_attr( $this->id ) . '">';

			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				foreach ( $this->options as $key => $value ) {
					$checked = ( in_array( $key, $this->value ) ? 'checked' : null );
					$h      .= '<label for="' . esc_attr( $this->id ) . '-' . esc_attr( $key ) . '"><input type="checkbox" id="' . esc_attr( $this->id ) . '-' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' name="' . esc_attr( $this->name ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</label>';
				}
			}

			$h .= '</div>';
		} else {
			$checked = ( $this->value ? 'checked' : null );
			$h      .= '<label><input type="checkbox" ' . esc_attr( $checked ) . ' id="' . esc_attr( $this->id ) . '" name="' . esc_attr( $this->name ) . '" value="1" />' . esc_html( $this->optionLabel ) . '</label>';
		}

		return $h;
	}

	private function switchField() {
		$h       = null;
		$checked = ( $this->value ? 'checked' : null );
		$h      .= '<label class="rttm-switch"><input type="checkbox" ' . esc_attr( $checked ) . ' id="' . esc_attr( $this->id ) . '" name="' . esc_attr( $this->name ) . '" value="1" /><span class="rttm-switch-slider round"></span></label>';

		return $h;
	}

	private function radioField() {
		$h  = null;
		$h .= '<div class="radio-group ' . esc_attr( $this->alignment ) . '" id="' . esc_attr( $this->id ) . '">';

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$checked = ( $key == $this->value ? 'checked' : null );
				$h      .= '<label for="' . esc_attr( $this->name ) . '-' . esc_attr( $key ) . '"><input type="radio" id="' . esc_attr( $this->id ) . '-' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' name="' . esc_attr( $this->name ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</label>';
			}
		}

		$h .= '</div>';

		return $h;
	}

	private function radioImage() {
		$h  = null;
		$h .= sprintf( "<div class='rttm-radio-image %s' id='%s'>", esc_attr( $this->alignment ), esc_attr( $this->id ) );

		$layout_group = [
			'grid'    => [
				'layout1',
				'layout3',
				'layout4',
				'layout6',
				'layout7',
				'layout8',
				'layout9',
				'layout10',
				'layout11',
				'layout12',
				'layout13',
				'layout14',
				'layout15',
				'special01',
			],
			'list'    => [
				'layout2',
				'layout5',
			],
			'slider'  => [
				'carousel1',
				'carousel2',
				'carousel3',
				'carousel4',
				'carousel5',
				'carousel6',
				'carousel7',
				'carousel8',
				'carousel9',
				'carousel10',
				'carousel11',
			],
			'isotope' => [
				'isotope1',
				'isotope2',
				'isotope3',
				'isotope4',
				'isotope5',
				'isotope6',
				'isotope7',
				'isotope8',
				'isotope9',
				'isotope10',
				'isotope-free',
			],
		];

		$selected_value = $this->value;

		if ( ! $selected_value ) {
			$layout = get_post_meta( get_the_ID(), 'layout', true );

			if ( $layout ) {
				foreach ( $layout_group as $key => $value ) {
					if ( in_array( $layout, $value ) ) {
						$selected_value = $key;
						break;
					}
				}
			} else {
				$selected_value = 'grid';
			}
		}

		// this is for layout preview, otherwise layout data not passing.
		if ( $this->name == 'layout' && $this->value ) {
			$this->options = [
				[
					'name'  => $this->value,
					'value' => $this->value,
					'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/layouts/' . $this->value . '.png',
				],
			];
		}

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$checked     = ( $value['value'] == $selected_value ? 'checked' : null );
				$is_pro      = ( isset( $value['is_pro'] ) && $value['is_pro'] && ! function_exists( 'rttmp' ) ? '<div class="rttm-ribbon"><span>' . esc_html__( 'Pro', 'tlp-team' ) . '</span></div>' : '' );
				$is_data_pro = ( isset( $value['is_pro'] ) && $value['is_pro'] && ! function_exists( 'rttmp' ) ? 'yes' : '' );
				$name        = isset( $value['name'] ) && $value['name'] ? esc_html( $value['name'] ) : '';
				$h          .= sprintf(
					'<label for="%1$s-%2$s">
						<input type="radio" id="%1$s-%2$s" %3$s name="%4$s" value="%2$s" data-pro="%7$s">
						<div class="rttm-radio-image-pro-wrap">
							<img src="%5$s" title="%8$s" alt="%2$s">
							%6$s
							<div class="rttm-checked"><span class="dashicons dashicons-yes"></span></div>
						</div>
					</label>',
					esc_attr( $this->id ),
					esc_attr( $value['value'] ),
					esc_attr( $checked ),
					esc_attr( $this->name ),
					esc_url( $value['img'] ),
					Fns::htmlKses( $is_pro, 'basic' ),
					esc_attr( $is_data_pro ),
					esc_attr( $name )
				);
			}
		}
		$h .= '</div>';
		return $h;
	}

	private function smartStyle() {
		$h       = null;
		$sColor  = ! empty( $this->value['color'] ) ? $this->value['color'] : null;
		$sSize   = ! empty( $this->value['size'] ) ? $this->value['size'] : null;
		$sWeight = ! empty( $this->value['weight'] ) ? $this->value['weight'] : null;
		$sAlign  = ! empty( $this->value['align'] ) ? $this->value['align'] : null;

		$h .= "<div class='multiple-field-rt-container clear'>";
		// color
		$h .= "<div class='field-inner col-4'>";
		$h .= "<div class='field-inner-rt-container size'>";
		$h .= "<span class='label'>Color</span>";
		$h .= '<input type="text" value="' . esc_attr( $sColor ) . '" class="tlp-color" name="' . esc_attr( $this->name ) . '[color]">';
		$h .= '</div>';
		$h .= '</div>';

		// Font size
		$h     .= "<div class='field-inner col-4'>";
		$h     .= "<div class='field-inner-rt-container size'>";
		$h     .= "<span class='label'>Font size</span>";
		$h     .= '<select name="' . esc_attr( $this->name ) . '[size]" class="tlp-select">';
		$fSizes = Options::scFontSize();
		$h     .= "<option value=''>Default</option>";

		foreach ( $fSizes as $size => $label ) {
			$sSlt = ( $size == $sSize ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $size ) . '" ' . esc_attr( $sSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Weight
		$h      .= "<div class='field-inner col-4'>";
		$h      .= "<div class='field-inner-rt-container weight'>";
		$h      .= "<span class='label'>Weight</span>";
		$h      .= '<select name="' . esc_attr( $this->name ) . '[weight]" class="tlp-select">';
		$h      .= "<option value=''>Default</option>";
		$weights = Options::scTextWeight();

		foreach ( $weights as $weight => $label ) {
			$wSlt = ( $weight == $sWeight ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $weight ) . '" ' . esc_attr( $wSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Alignment
		$h     .= "<div class='field-inner col-4'>";
		$h     .= "<div class='field-inner-rt-container alignment'>";
		$h     .= "<span class='label'>Alignment</span>";
		$h     .= '<select name="' . esc_attr( $this->name ) . '[align]" class="tlp-select">';
		$h     .= "<option value=''>Default</option>";
		$aligns = Options::scAlignment();

		foreach ( $aligns as $align => $label ) {
			$aSlt = ( $align == $sAlign ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $align ) . '" ' . esc_attr( $aSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';
		$h .= '</div>';

		return $h;
	}

	private function multipleOption( $fields = [] ) {
		$h  = null;
		$h .= "<div class='multiple-field-rt-container rt-clear'>";

		if ( ! empty( $fields ) && is_array( $fields ) ) {
			foreach ( $fields as $key => $field ) {
				$h .= $this->innerField( $key, $field );
			}
		}

		$h .= '</div>';

		return $h;
	}

	private function innerField( $key, $options = [] ) {
		$h        = null;
		$col_size = ! empty( $options['col_size'] ) ? $options['col_size'] : 3;
		$type     = ! empty( $options['type'] ) ? $options['type'] : 'color';
		$label    = ! empty( $options['label'] ) ? $options['label'] : null;
		$desc     = ! empty( $options['description'] ) ? $options['description'] : null;
		$val      = ! empty( $this->value[ $key ] ) ? $this->value[ $key ] : null;
		$class    = ! empty( $options['class'] ) ? trim( $options['class'] ) : null;
		$blank    = ! empty( $options['blank'] ) ? trim( $options['blank'] ) : null;
		$lists    = ! empty( $options['options'] ) ? $options['options'] : [];
		$default  = ! empty( $options['default'] ) ? $options['default'] : null;

		if ( ! $val ) {
			$val = $default;
		}

		switch ( $type ) {
			case 'color':
				$h .= '<div class="field-inner col-' . esc_attr( $col_size ) . '">';
				$h .= '<div class="field-inner-rt-container ' . esc_attr( $key ) . '">';
				$h .= ( $label ? '<span class="label">' . Fns::htmlKses( $label, 'basic' ) . '</span>' : null );
				$h .= '<input type="text" value="' . esc_attr( $val ) . '" class="tlp-color" name="' . esc_attr( $this->name ) . '[' . esc_attr( $key ) . ']">';
				$h .= ( $desc ? '<p>' . Fns::htmlKses( $desc, 'basic' ) . '</p>' : null );
				$h .= '</div>';
				$h .= '</div>';
				break;

			case 'select':
				$h .= '<div class="field-inner col-' . esc_attr( $col_size ) . '">';
				$h .= '<div class="field-inner-rt-container ' . esc_attr( $key ) . '">';
				$h .= ( $label ? '<span class="label">' . Fns::htmlKses( $label, 'basic' ) . '</span>' : null );

				$h .= '<select name="' . esc_attr( $this->name ) . '[' . $key . ']" id="' . esc_attr( $this->id ) . '_' . esc_attr( $key ) . '" class="' . esc_attr( $class ) . '">';

				if ( $blank ) {
					$h .= '<option value="">' . esc_html( $blank ) . '</option>';
				}

				if ( is_array( $lists ) && ! empty( $lists ) ) {
					foreach ( $lists as $lKey => $value ) {
						$slt = ( $lKey == $val ? 'selected' : null );
						$h  .= '<option ' . esc_attr( $slt ) . ' value="' . esc_attr( $lKey ) . '">' . esc_html( $value ) . '</option>';
					}
				}

				$h .= '</select>';
				$h .= ( $desc ? '<p>' . Fns::htmlKses( $desc, 'basic' ) . '</p>' : null );
				$h .= '</div>';
				$h .= '</div>';
				break;

			default:
				break;
		}

		return $h;
	}
}
