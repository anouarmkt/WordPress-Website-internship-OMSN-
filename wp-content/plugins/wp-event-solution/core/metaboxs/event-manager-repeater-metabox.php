<?php

namespace Etn\Core\Metaboxs;

defined( 'ABSPATH' ) || exit;

use Etn\Utils\Helper;

abstract class Event_manager_repeater_metabox {

		protected function get_repeater_markup( $item = null, $key = '', $data = [], $rep_key = ''  ) {
		
				if ( is_null( $item ) ) {
						return;
				}

				if ( isset( $item['type'] ) ) {
						switch ( $item['type'] ) {
						case "text":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "hidden":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "number":
								return $this->get_repeater_number_input( $item, $key, $data , $rep_key );
								break;
						case "date":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "email":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "time":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "url":
								return $this->get_repeater_text_input( $item, $key, $data );
								break;
						case "textarea":
								return $this->get_repeater_text_area( $item, $key, $data );
								break;
						case "select2":
								return $this->get_repeater_select2( $item, $key, $data );
								break;
						case "radio":
								return $this->get_repeater_radio( $item, $key, $data );
								break;
						case "upload":
								return $this->get_repeater_upload( $item, $key, $data );
								break;
						case "select_single":
								return $this->get_repeater_select_single( $item, $key );
								break;
								break;
						default:
								return;
						}

				}

				return;
		}


		public function get_repeater_number_input( $item, $key, $data , $index ) {
				$value = $data;
				$value = isset( $value[$key] ) ? $value[$key] : '';
				$class = $key;
				$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
				$step = isset( $item['step'] ) ? $item['step'] : "1";

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
				}
				?>
				<div class="<?php echo esc_attr( $class ); ?>">
						<div class="etn-label">
								<label for="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $item['label'] ); ?> </label>
								<div class="etn-desc"><?php echo esc_html( $item['desc'] ); ?>
								</div>
						</div>
						<div class="etn-meta">
								<input autocomplete="off" class="etn-form-control" type="<?php echo esc_attr( $item['type'] ); ?>" name="<?php echo esc_attr( $key ); ?>"
								 id="<?php echo esc_attr( $key ."_".$index ); ?>" value="<?php echo esc_html( $value ); ?>" min="0" step="<?php echo esc_html( $step ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>"/>
						</div>
				</div>
				<?php
		}

		public function get_repeater_select_single( $item, $key ) {
				$value = '';
				$class = $key;
				$input = '';
				$value = get_post_meta( get_the_ID(), $key, true );

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
				}

				if ( !isset( $item['options'] ) || !count( $item['options'] ) ) {
						$html = sprintf( '<div class="%s form-group">
				 <div class="etn-label"> <label for="%s"> %s </label></div>
				</div>', $class, $key, $item['label'] );
						echo Helper::kses( $html );
						return;
				} elseif ( isset( $item['options'] ) && count( $item['options'] ) ) {
						$options = $item['options'];
						$input .= sprintf( '<select  name="%s" class="etn_es_event_select2 %s">', $key, $key, $class );

						if ( is_array( $options ) && !empty( $options ) ) {

								foreach ( $options as $option_key => $option ) {

										if ( $option_key == $value ) {
												$input .= sprintf( ' <option selected value="%s"> %s </option>', $option_key, $option );
										} else {
												$input .= sprintf( ' <option value="%s"> %s </option>', $option_key, $option );
										}

								}

						}

						$input .= sprintf( '</select>' );
				}

				$html = sprintf( '
			<div class="%s">
				 <div class="etn-label">
						<label> %s  </label>
				 </div>
					%s
		 </div>', $class, $item['label'], $input );

				echo ( $html );
		}

		public function get_repeater_upload( $item, $key, $data ) {

				$class = $key;
				$value = null;

				if ( is_array( $data ) && count( $data ) ) {
						$value = isset( $data[$key] ) ? $data[$key] : '';
				}

				$image      = ' button">Upload image';
				$image_size = 'full';
				$display    = 'none';
				$multiple   = 0;

				if ( isset( $item['multiple'] ) && $item['multiple'] ) {
						$multiple = true;
				}

				if ( isset( $item['attr'] ) ) {

						if ( isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ) {
								$class = 'attr-form-control etn_event_meta_field ' . $class . ' ' . $item['attr']['class'];
						} else {
								$class = 'etn_event_meta_field attr-form-control';
						}

				}

				if ( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

						$image   = '"><img src="' . $image_attributes[0] . '" alt="" style="max-width:95%;display:block;" />';
						$display = 'inline-block';
				}

				?>
				<div class='<?php echo esc_attr( $class ); ?> form-group'>
						<label><?php echo esc_html( $item['label'] ); ?></label>
					 <a data-multiple="<?php echo esc_html( $multiple ); ?>" class="etn_event_upload_image_button<?php echo esc_html( $image ); ?></a>
									 <input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" />
						 <a href="#" class="essential_event_remove_image_button" style="display:inline-block;display:<?php echo esc_html( $display ); ?>"><?php echo esc_html__( 'Remove image', 'eventin' ); ?></a>
				</div>
				<?php
		}

		public function get_repeater_text_input( $item, $key, $data ) {
				
				$value = $data;
				$value = isset( $value[$key] ) ? $value[$key] : '';
				$class = $key;
				$icon  = '';
				$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
						$icon  = !empty( $item['attr']['icon'] )  ? $item['attr']['icon']  : '';
				}

				$hide_block = ( !empty( $item['type'] ) && $item['type'] =="hidden" ) ? "hide_item" : "";
				?>
				<div class="etn-label-item <?php echo esc_attr($hide_block) ?> <?php echo esc_attr($class)?>">
						<div class="etn-label">
								<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($item['label']); ?></label>
								<div class="etn-desc"> <?php echo esc_html(  $item['desc'] ); ?> </div>
						</div>
						<div class="etn-meta">
								<?php echo Helper::render( $icon ); ?>
								<input autocomplete="off" class="etn-form-control" type="<?php echo esc_attr( $item['type'] ); ?>" name="<?php echo esc_attr($key)?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
						</div>
				</div>
				<?php
		}

		public function get_repeater_text_area( $item, $key, $data ) {

				$value = $data;
				$value = isset( $value[$key] ) ? $value[$key] : '';
				$class = $key;
				$rows  = 14;
				$cols  = 50;
				$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';

				if ( isset( $item['attr'] ) ) {
						$rows  = isset( $item['attr']['row'] ) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
						$cols  = isset( $item['attr']['col'] ) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field etn-form-control' : 'etn_event_meta_field form-control';
				}

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
				}
				?>
				<div class="<?php echo esc_attr( $class ); ?> etn-label-item"> 
						<div class="etn-label">
								<label for="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $item['label'] ); ?> </label>
								<div class="etn-desc"> <?php echo esc_html( $item['desc'] ); ?>  </div>
						</div>
						<div class="etn-meta"> 
								<textarea id="<?php echo esc_attr( $key ); ?>" rows="<?php echo esc_attr( $rows ); ?>" cols="<?php echo esc_attr( $cols ); ?>" class="etn-form-control msg-control-box" name="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>"><?php echo Helper::render( $value )?></textarea>
						</div>
				</div>
				<?php
		}

		public function get_repeater_radio( $item, $key, $data ) {

				$value = $data;
				$value = isset( $value[$key] ) ? $value[$key] : '';
				$class = $key;
				$input = '';

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field attr-form-control' : 'etn_event_meta_field attr-form-control';
				}

				if ( !isset( $item['options'] ) || !count( $item['options'] ) ) {

						$html = sprintf( '<div class="attr-form-control %s">
			 <label for="%s"> %s </label>
			 </div>', $class, $key, $item['label'] );
						return $html;
				} elseif ( isset( $item['options'] ) && count( $item['options'] ) ) {

						$options = $item['options'];

						if ( is_array( $options ) ) {

								foreach ( $options as $option_key => $option ) {
										$checked = $option_key == $value ? 'checked' : '';
										$input .= sprintf( ' <input  %s type="%s" name="%s" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option );
								}

						}

				}

				$html = sprintf( '<div class="%s form-group"> <label> %s  </label> %s </div>', $class, $item['label'], $input );

				return $html;
		}

		public function get_repeater_select2( $item, $key, $data ) {
				$input    = '';
				$class    = $key;
				$value    = $data;
				$value    = isset( $value[$key] ) ? $value[$key] : '';
				$multiple = isset( $item['multiple'] ) ? 'multiple' : '';

				if ( isset( $item['attr'] ) ) {
						$class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field form-control';
				}
				?>
				<div class="<?php echo esc_attr( $class ); ?> etn-label-item"> 
						<div class="etn-label"> 
								<label> 
										<?php echo esc_html( $item['label'] ); ?>  
								</label>
								<div class="etn-desc">  
										<?php echo esc_html( $item['desc'] ); ?>  
								</div> 
						</div> 
						<?php 
						$options = $item['options'];
						?>
						<div class="etn-meta">
								<select <?php echo esc_html( $multiple ); ?> name="<?php echo esc_attr( $key );?>" class="etn_es_event_repeater_select2 etn-form-control">
										<?php
										if ( is_array( $options ) && !empty( $options ) ) {
												foreach ( $options as $option_key => $option ) {
														if ( $multiple ) {
																$etn_shedule_speaker_arr = isset( $data['etn_shedule_speaker'] ) && is_array( $data['etn_shedule_speaker'] ) ? $data['etn_shedule_speaker'] : [];
																$selected                = in_array( $option_key, $etn_shedule_speaker_arr ) ? "selected " : '';
																?>
																<option <?php echo esc_attr( $class ); ?> <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $option_key ); ?>"> <?php echo esc_html( $option );?> </option>
																<?php
														} else {
																if ( $option_key == $value ) {
																		?>
																		<option <?php echo esc_attr( $class ); ?> selected value="<?php echo esc_attr( $option_key ); ?>"> <?php echo esc_html( $option );?> </option>
																		<?php
																} else {
																		?>
																		<option <?php echo esc_attr( $class ); ?> value="<?php echo esc_attr( $option_key ); ?>"> <?php echo esc_html( $option );?> </option>
																		<?php
																}

														}
												}
										}

										?>
								</select>
								<?php 
								if(!empty($item['warning'])){
										$warning_text = $item['warning'];
										$warning_url  = !empty($item['warning_url']) ? $item['warning_url'] : '#';
										?>
										<span class="etn-input-select-warning">
												<a href="<?php echo esc_url( $warning_url ); ?>" target="_blank" ><?php echo esc_html( $warning_text );?></a>
										</span>
										<?php
								}
								?>
						</div>
				</div>
				<?php
		}

}
