<?php

namespace Etn\Core\Metaboxs;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Metaboxs\Event_manager_repeater_metabox;
use Etn\Utils\Helper as Helper;

abstract class Repeater_Metaboxmanager_metabox extends Event_manager_repeater_metabox {

    public function display_callback( $post ) {

        foreach ( $this->default_fields() as $key => $item ):
            $this->get_markup( $item, $key );
        endforeach;
        wp_nonce_field( 'etn_Repeater_Metaboxdata', 'etn_Repeater_Metaboxn_fields' );
    }

    function save_meta_box_data( $post_id ) {
        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        if ( !Helper::is_secured( 'etn_Repeater_Metaboxn_fields', 'etn_Repeater_Metaboxdata', $post_id, $post_arr ) ) {
            return $post_id;
        }
        try {
            $this->update( $this->default_fields(), $post_arr );
        } catch ( Exception $e ) {
            $error = new WP_Error( $e->getCode(), $e->getMessage() );
        }
    }

    protected function update( $fields = null, $post ) {

        if ( !is_array( $fields ) || !count( $fields ) ) {
            throw new Exception( esc_html__( "meta data field not found", 'eventin' ) );
        }

        foreach ( $fields as $field_key => $field ) {
            if ( $field['type'] == 'radio' || $field['type'] == 'select2' ) {

                if ( isset( $post[$field_key] ) ) {
                    $upload_key = isset( $post[$field_key] ) ? $post[$field_key] : '';
                    $rv         = $upload_key;
                    update_post_meta( get_the_ID(), $field_key, $rv );
                } else {
                    update_post_meta( get_the_ID(), $field_key, '' );
                }
            } elseif ( $field['type'] == 'upload' ) {
                if ( isset( $post[$field_key] ) ) {
                    $upload_key = isset( $post[$field_key] ) ? sanitize_text_field( $post[$field_key] ) : '';
                    update_post_meta( get_the_ID(), $field_key, $upload_key );
                }
            } elseif ( $field['type'] == 'wp_editor' ) {
                if ( isset( $post[$field_key] ) ) {
                    $upload_key = isset( $post[$field_key] ) ? sanitize_textarea_field( $post[$field_key] ) : '';
                    update_post_meta( get_the_ID(), $field_key, $upload_key );
                }
            } elseif ( $field['type'] == 'social_reapeater' ) {
                if ( isset( $post[$field_key] ) ) {
                    $social_key = isset( $post[$field_key] ) ? $post[$field_key] : '';
                    if ( is_array( $social_key ) ) {
                        if ( count( $social_key ) == 1 ) {
                            if ( $social_key[0]['icon'] == '' ) {
                                update_post_meta( get_the_ID(), $field_key, "" );
                            } else {
                                update_post_meta( get_the_ID(), $field_key, $social_key );
                            }
                        } else {
                            update_post_meta( get_the_ID(), $field_key, $social_key );
                        }
                    }
                }
            } elseif ( $field['type'] == 'repeater' ) {
                if ( isset( $post[$field_key] ) ) {
                    $etn_rep_key = isset( $post[$field_key] ) ? $post[$field_key] : '';

                    if ( is_array( $etn_rep_key ) ) {
                        if ( count( $etn_rep_key ) == 1 ) {
                            if ( strlen( trim( implode( $etn_rep_key[0] ) ) ) == 0 ) {
                                update_post_meta( get_the_ID(), $field_key, "" );
                            } else {
                                update_post_meta( get_the_ID(), $field_key, $etn_rep_key );
                            }
                        } else {
                            update_post_meta( get_the_ID(), $field_key, $etn_rep_key );
                        }
                    }
                }
            } elseif ( $field['type'] == 'email' ) {

                if ( isset( $post[$field_key] ) ) {
                    $email_value = isset( $post[$field_key] ) ? sanitize_email( $post[$field_key] ) : '';
                    update_post_meta( get_the_ID(), $field_key, $email_value );
                }
            } else {

                if ( isset( $post[$field_key] ) ) {
                    $text_value = isset( $post[$field_key] ) ? sanitize_text_field( $post[$field_key] ) : '';
                    update_post_meta( get_the_ID(), $field_key, $text_value );
                }
            }
        }
    }

    protected function get_markup( $item = null, $key = '' ) {

        if ( is_null( $item ) ) {
            return;
        }

        if ( isset( $item['type'] ) ) {

            switch ( $item['type'] ) {
            case "text":
                return $this->get_textinput( $item, $key );
                break;
            case "number":
                return $this->get_number_input( $item, $key );
                break;
            case "date":
                return $this->get_textinput( $item, $key );
                break;
            case "time":
                return $this->get_textinput( $item, $key );
                break;
            case "textarea":
                return $this->get_textarea( $item, $key );
                break;
            case "url":
                return $this->get_url_input( $item, $key );
                break;
            case "email":
                return $this->get_email_input( $item, $key );
                break;
            case "radio":
                return $this->get_radio_input( $item, $key );
                break;
            case "select2":
                return $this->get_select2( $item, $key );
                break;
            case "select_single":
                return $this->get_select_single( $item, $key );
                break;
            case "upload":
                return $this->get_upload( $item, $key );
                break;
            case "wp_editor":
                return $this->get_wp_editor( $item, $key );
                break;
            case "map":
                return $this->get_wp_map( $item, $key );
                break;
            case "social_reapeater":
                return $this->get_wp_social_repeater( $item, $key );
                break;
            case "repeater":
                return $this->get_wp_repeater( $item, $key );
                break;
            case "heading":
                return $this->get_heading( $item, $key );
                break;
            case "separator":
                return $this->get_separator( $item, $key );
                break;
            default:
                return;
            }
        }
        return;
    }

    public function get_wp_repeater( $item, $key ) {
        $value          = [];
        $class          = $key;
        $options_fields = $item['options'];
        $repeater_arr   = get_post_meta( get_the_ID(), $key, true );
        $count          = is_array( $repeater_arr ) ? count( $repeater_arr ) : 1;
        ?>
        <div class='etn-event-repeater-clearfix etn-repeater-item'>
            <h3 class='etn-title'><?php echo esc_html( $item['label'] ); ?></h3>
            <div class='etn-event-manager-repeater-fld <?php echo esc_attr($class); ?>'>
                <div class='ddd' data-repeater-list='<?php echo esc_attr($key); ?>'>
                    <?php
                    for ( $x = 0; $x < $count; $x++ ) {
                        $label_no = $x;
                        ?>
                        <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item" data-repeater-item>
                            <div class="form-group mb-3">
                            <div class="etn-event-shedule-collapsible">
                                <span class="event-title"><?php echo esc_html( $item['label'] . ' ' . ++$label_no ); ?></span>
                                <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
                            </div>
                            <div class="etn-event-repeater-collapsible-content" style="display: none">
                                <?php $i = $x;
                                    foreach ( $options_fields as $op_fld_key => $options_field ): 
                                        $nested_data = isset( $repeater_arr[$i] ) ? $repeater_arr[$i] : [];
                                        echo Helper::render( $this->get_repeater_markup( $options_field, $op_fld_key, $nested_data ) ); ?>
                                <?php endforeach;?>
                            </div>
                            </div>
                        </div>
                        <?php 
                    } 
                    ?>
                </div>
                <input data-repeater-create type='button' class='etn-btn attr-btn-primary mb-2 clearfix' value='<?php echo esc_html__("Add", "eventin");?>' />
            </div>
        </div>
        <?php
    }

    public function get_wp_repeaterpublic( $item, $key, $id ) {
        $value          = [];
        $class          = $key;
        $options_fields = $item['options'];
        $repeater_arr   = get_post_meta( $id, $key, true );
        $count          = is_array( $repeater_arr ) ? count( $repeater_arr ) : 1;
        ?>
        <div class='etn-event-repeater-clearfix'>
            <h3><?php echo esc_html( $item['label'] ); ?></h3>
            <div class='form-inline etn-event-repeater <?php echo esc_attr( $class ); ?>'>
                <div data-repeater-list='<?php echo esc_html( $key ); ?>'>
                <input data-repeater-create type='button' class='etn-btn attr-btn-primary mb-2 clearfix' value='<?php echo esc_html__("Add", "eventin"); ?>' />
                <?php
                for ( $x = 0; $x < $count; $x++ ) {
                    $label_no = $x; ?>
                    <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item">
                    <div class="form-group mb-3" data-repeater-item>

                        <div onclick="etn_essential_Repeater_Metaboxreapeater_collapse_public(this)" class="etn-event-repeater-collapsible">
                            <?php echo esc_html( $item['label'] . ' ' . ++$label_no ); ?>
                            <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
                        </div>

                        <div class="etn-event-repeater-collapsible-content">
                            <?php $i = $x;

                        foreach ( $options_fields as $op_fld_key => $options_field ): 
                            $nested_data = isset( $repeater_arr[$i] ) ? $repeater_arr[$i] : [];
                            echo Helper::render( $this->get_repeater_markup( $options_field, $op_fld_key, $nested_data ) ); 
                        endforeach;?>
                        </div>
                    </div>
                    </div>
                    <?php 
                } 
                ?>
            </div>
        </div>
        <?php
    }

    public function get_wp_repeaterpublicnull( $item, $key ) {

        $value          = [];
        $class          = $key;
        $options_fields = $item['options'];

        $count = 1;
        ?>
        <div class='etn-event-repeater-clearfix'>
            <h3><?php echo esc_html( $item['label'] ); ?></h3>
            <div class='form-inline etn-event-repeater <?php echo esc_attr( $class ); ?>'>
                <div data-repeater-list='<?php echo esc_html( $key ); ?>'>
                <input data-repeater-create type="button" class="etn-btn attr-btn-primary mb-2 clearfix" value="<?php echo esc_html__("Add", "eventin"); ?>" />
                <?php
                for ( $x = 0; $x < $count; $x++ ) {
                        $label_no = $x;?>
                    <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item">
                        <div class="form-group mb-3" data-repeater-item>
                        <div onclick="etn_essential_Repeater_Metaboxrepeater_collapse_publicnull(this)" class="etn-event-repeater-collapsible">
                            <?php echo esc_html( $item['label'] . ' ' . ++$label_no ); ?>
                            <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
                        </div>
                        <div class="etn-event-repeater-collapsible-content">
                            <?php $i = $x;
                                foreach ( $options_fields as $op_fld_key => $options_field ): 
                                    $nested_data = isset( $repeater_arr[$i] ) ? $repeater_arr[$i] : [];
                                    echo Helper::render( $this->get_repeater_markup( $options_field, $op_fld_key, $nested_data ) );?>
                            <?php endforeach;?>
                        </div>
                        </div>
                    </div>
                <?php }
                    ?>
                    <script>
                        function etn_essential_Repeater_Metaboxrepeater_collapse_publicnull(e) {

                            e.classList.toggle("etn-repeater-fld-active");
                            var content = e.nextElementSibling;
                            if (content.style.display === "block") {
                            content.style.display = "none";
                            } else {
                            content.style.display = "block";
                            }
                            jQuery('.etn_Repeater_Metaboxdate').datepicker({
                            dateFormat: "yy,MM,dd",
                            onSelect: function() {
                                jQuery(this).val();
                            }
                            });
                            jQuery('.etn_es_Repeater_Metaboxrepeater_select2').select2();
                            jQuery('.etn_es_Repeater_Metaboxrepeater_select2').select2();
                            if (jQuery(e).next().find('span.select2:eq(1)').length) {
                            jQuery(e).next().find('span.select2:eq(1)').hide();
                            }

                        }
                    </script>
                </div>
            </div>
      <?php
   }

    public function get_wp_social_repeater( $item, $key ) {
        $value        = '';
        $class        = $key;
        $social_items = $key;

        $dbvalue = get_post_meta( get_the_ID(), $key, true );

        require \Wpeventin::plugin_dir() . 'core/metaboxs/views/fields/icons.php';
        ?>
        <div class='etn-social-clearfix etn-label-social etn-label-item etn-label-top'>
        <div class='etn-label'>
            <label><?php echo esc_html( $item['label'] ); ?></label>
            <div class="etn-desc"><?php echo esc_html( $item['desc'] ); ?></div>
        </div>
        <?php

        if ( is_array( $dbvalue ) ) {
            echo sprintf( "<div class='form-inline etn-meta social-repeater %s'>
            <div class='etn-repeater-wrap' data-repeater-list='%s'>", $class, $social_items );

            foreach ( $dbvalue as $db_socail ) {
                ?>
                <div data-repeater-item>
                    <div class='etn-form-group mb-2'>
                    <i class='<?php echo esc_attr( $db_socail['icon'] );?> show-repeater-icon'></i>
                    <input  type='text' value='<?php echo esc_html($db_socail['icon']);?>' name='icon' class='etn-social-icon etn-form-control'  data-toggle='modal' data-target='#etn-event-es-social-modal'/> 
                    <input type='text' class='etn-form-control' value='<?php echo esc_html( $db_socail['etn_social_title'] );?>' name='etn_social_title' placeholder='<?php echo esc_html__("title", "eventin");?>' /> 
                    <input type='text' class='etn-form-control' value='<?php echo esc_html( $db_socail['etn_social_url'] );?>' name='etn_social_url' placeholder='<?php echo esc_html__( "url", "eventin" );?>' /> 
                    <button data-repeater-delete type='button' class='etn-btn btn-danger'>
                        <span class='dashicons dashicons-no-alt'></span>
                    </button>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
                <div class='add-social dd'>
                <input class='etn-btn attr-btn-primary' data-repeater-create type='button' value='<?php echo esc_html__("Add dfdf Social", "eventin"); ?>'/>
                </div>
            </div>
            <?php
        } else {
            echo sprintf( "<div class='form-inline etn-meta social-repeater %s'><div data-repeater-list='%s'>", $class, $social_items );
            ?>
            <div data-repeater-item> 
                <div class='etn-form-group mb-2'> 
                    <i class=''></i>
                    <input  type='text' name='icon' class='etn-social-icon etn-form-control'  data-toggle='modal' data-target='#etn-event-es-social-modal'/> 
                    <input type='text' class='etn-form-control' name='etn_social_title' placeholder='<?php echo esc_html__("Title here", "eventin");?>' /> 
                    <input type='text' class='etn-form-control' name='etn_social_url' placeholder='<?php echo esc_html__("Url here", "eventin");?>' />  
                    <button data-repeater-delete type='button' class='etn-btn btn-danger'>
                        <span class='dashicons dashicons-no-alt'></span>
                    </button>
                </div>
            </div>
            </div>
                <div class='add-social dd'>
                <input class='etn-btn attr-btn-primary' data-repeater-create type='button' value='<?php echo esc_html__("Add", "eventin");?>'/>
                </div>
            </div>
            <?php
        }
        ?>
      </div>
      <?php
   }

    public function get_separator( $item, $key ) {

        $class = $key;

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }
        ?>
        <div class='<?php echo esc_attr( $class ); ?>'>
            <hr/>
        </div>
        <?php
    }

    public function get_wp_map( $item, $key ) {
        $options = get_option( 'etn_Repeater_Metaboxgeneral_options' );
        $value   = '';
        $class   = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : ' etn_Repeater_Metaboxmeta_field';
        }

        require \Wpeventin::plugin_dir() . 'views/fields/map.php';
    }

    public function get_heading( $item, $key ) {

        if ( !isset( $item['label'] ) ) {
            return;
        }

        $class = $key;

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }

        $html = sprintf( '<div class="%s">
                <h3 for="%s"> %s  </h3>

                </div>', $class, $key, $item['label'] );

        echo Helper::kses( $html );
    }

    public function get_textinput( $item, $key ) {
        $value = '';
        $class = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        $value = get_post_meta( get_the_ID(), $key, true );

        if ( isset( $item['attr'] ) ) {

            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }

        $html = sprintf( '<div class="%s">
                <div class="etn-label"> <label for="%s"> %s : </label><div class="etn-desc">  %s  </div></div>
                <div class="etn-meta"> <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/></div>
                </div>', $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value );

        echo Helper::kses( $html );
    }

    public function get_number_input( $item, $key ) {

        $value = '';
        $class = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        $value = get_post_meta( get_the_ID(), $key, true );

        $step = isset( $item['step'] ) ? $item['step'] : "1";

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }

        $html = sprintf(
            '<div class="%s">
                <div class="etn-label"> <label for="%s"> %s : </label><div class="etn-desc">%s</div></div>
                <div class="etn-meta">
                    <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s" min="0" step="%s" />
                </div>
            </div>', $class, $key, $item['label'], $item['desc'], $item['type'], $key, $key, $value, $step );

        echo Helper::kses( $html );
    }

    public function get_email_input( $item, $key ) {

        $value = '';
        $class = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : ' etn_Repeater_Metaboxmeta_field';
        }

        $html = sprintf( '<div class="%s">
                <div class="etn-label"> <label for="%s"> %s : </label></div>
                <div class="etn-meta">
                <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/>
                </div></div>', $class, $key, $item['label'], $item['type'], $key, $key, $value );

        echo Helper::kses( $html );
    }

    public function get_radio_input( $item, $key ) {

        $value = '';
        $class = $key;
        $input = '';

        $value = get_post_meta( get_the_ID(), $key, true );

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : 'etn_Repeater_Metaboxmeta_field ';
        }

        if ( !isset( $item['options'] ) || !count( $item['options'] ) ) {
            $html = sprintf( '<div class=" %s">
                    <label for="%s"> %s : </label>

                    </div>', $class, $key, $item['label'] );

            echo Helper::kses( $html );
            return;
        } elseif ( isset( $item['options'] ) && count( $item['options'] ) ) {
            $options = $item['options'];

            foreach ( $options as $option_key => $option ) {
                $checked = $option_key == $value ? 'checked' : '';

                $input .= sprintf( ' <input  %s type="%s" name="%s" class="etn-form-control" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option );
            }

        }

        $html = sprintf( '<div class="%s form-group"> <label> %s  </label>
                %s
                </div>', $class, $item['label'], $input );

        echo Helper::kses( $html );
    }

    public function get_select2( $item, $key ) {
        $value = '';
        $class = $key;
        $input = '';
        $value = get_post_meta( get_the_ID(), $key, true );

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }

        if ( !isset( $item['options'] ) || !count( $item['options'] ) ) {
            $html = sprintf( '<div class="%s form-group">
                    <div class="etn-label"> <label for="%s"> %s : </label></div>
                    </div>', $class, $key, $item['label'] );
            echo Helper::kses( $html );
            return;
        } elseif ( isset( $item['options'] ) && count( $item['options'] ) ) {
            $options = $item['options'];
            $input .= sprintf( '<select multiple name="%s[]" class="etn_es_Repeater_Metaboxselect2 %s">', $key, $key, $class );

            foreach ( $options as $option_key => $option ) {

                if ( is_array( $value ) && in_array( $option_key, $value ) ) {
                    $input .= sprintf( ' <option %s value="%s"> %s </option>', 'selected', $option_key, $option );
                } else {
                    $input .= sprintf( ' <option value="%s"> %s </option>', $option_key, $option );
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

        echo Helper::kses( $html );
    }

    public function get_select_single( $item, $key ) {
        $value = '';
        $class = $key;
        $input = '';
        $value = get_post_meta( get_the_ID(), $key, true );

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field' : 'etn_Repeater_Metaboxmeta_field';
        }

        if ( !isset( $item['options'] ) || !count( $item['options'] ) ) {
            $html = sprintf( '<div class="%s form-group">
                    <div class="etn-label"> <label for="%s"> %s : </label></div>
                    </div>', $class, $key, $item['label'] );
            echo Helper::kses( $html );
            return;
        } elseif ( isset( $item['options'] ) && count( $item['options'] ) ) {
            $options = $item['options'];
            $input .= sprintf( '<select name="%s" class="etn_es_Repeater_Metaboxselect2 %s">', $key, $key, $class );

            foreach ( $options as $option_key => $option ) {

                if ( $option_key == $value ) {
                    $input .= sprintf( ' <option selected value="%s"> %s </option>', $option_key, $option );
                } else {
                    $input .= sprintf( ' <option value="%s"> %s </option>', $option_key, $option );
                }

            }

            $input .= sprintf( '</select>' );
        }

        $html = sprintf( '
                        <div class="%s">
                            <div class="etn-label">
                                <label> %s  </label>
                                <div class="etn-desc">%s</div>
                            </div>
                            %s
                        </div>', $class, $item['label'], $item['desc'], $input );

        echo ( $html );
    }

    public function get_url_input( $item, $key ) {
        $value = '';
        $class = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        if ( isset( $item['attr'] ) ) {
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : 'etn_Repeater_Metaboxmeta_field ';
        }

        $html = sprintf( '
                <div class="%s">
                    <div class="etn-label"> 
                        <label for="%s"> %s : </label>
                    </div>
                    <div class="etn-meta">
                        <input class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/>
                    </div>
                </div>', $class, $key, $item['label'], $item['type'], $key, $key, $value );

        echo Helper::kses( $html );
    }

    public function get_upload( $item, $key ) {

        $class = $key;
        $value = get_post_meta(get_the_ID(), $key, true);
        $image = ' button">Upload image';
        $image_size = 'full';
        $display = 'none';
        $multiple = 0;
  
        if (isset($item['multiple']) && $item['multiple']) {
           $multiple = true;
        }
  
        if (isset($item['attr'])) {
  
           if (isset($item['attr']['class']) && $item['attr']['class'] != '') {
              $class = ' etn_event_meta_field ' . $class . ' ' . $item['attr']['class'];
           } else {
              $class = ' etn_event_meta_field ';
           }
        }
  
        if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {
  
           $image = '"><img src="' . $image_attributes[0] . '" alt="" style="max-width:95%;display:block;" />';
           $display = 'inline-block';
        }
        ?>
        <div class='<?php echo esc_attr( $class ); ?>'>
        <div class="etn-label"> <label><?php echo esc_html(  $item['label'] ); ?></label></div>
          <div class="etn-meta">
            <a data-multiple="<?php echo esc_html( $multiple ); ?>" class="etn_event_upload_image_button<?php echo esc_html( $image ); ?>"></a>
                <input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" />
            <a href="#" class="essential_event_remove_image_button" style="display:inline-block;display:<?php echo esc_attr( $display ); ?>"><?php echo esc_html__('Remove image', 'eventin'); ?></a>
          </div>
        </div>
        <?php
    }

    public function get_textarea( $item, $key ) {

        $rows  = 14;
        $cols  = 50;
        $value = '';
        $class = $key;

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        if ( isset( $item['attr'] ) ) {
            $rows  = isset( $item['attr']['row'] ) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
            $cols  = isset( $item['attr']['col'] ) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : 'etn_Repeater_Metaboxmeta_field ';
        }
        ?>
        <div class="<?php echo esc_attr($class);?> form-group">
            <div class="etn-label">
                <label for="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html($item['label']);?> : </label>
            </div> 
            <div class="etn-meta">
                <textarea class="etn-form-control msg-control-box" id="<?php echo esc_attr($key); ?>" rows="<?php echo esc_attr($rows); ?>" cols="<?php echo esc_attr($cols); ?>" name="<?php echo esc_html($key); ?>"> <?php echo Helper::kses( $value );?> </textarea>
            </div> 
        </div>
        <?php
    }

    public function get_wp_editor( $item, $key ) {

        $rows  = 14;
        $cols  = 50;
        $value = '';
        $class = $key;

        if ( isset( $item['settings'] ) && is_array( $item['settings'] ) ) {
            $settings = $item['settings'];
        }

        if ( isset( $item['value'] ) ) {
            $value = get_post_meta( get_the_ID(), $key, true );
        }

        if ( isset( $item['attr'] ) ) {
            $rows  = isset( $item['attr']['row'] ) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
            $cols  = isset( $item['attr']['col'] ) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
            $class = isset( $item['attr']['class'] ) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_Repeater_Metaboxmeta_field ' : 'etn_Repeater_Metaboxmeta_field ';
        }
        ?>
        <div class='<?php echo esc_attr( $class ); ?>'>
            <?php
            wp_editor( $value, $key, $settings );
            ?>
        </div>
        <?php
    }

}
