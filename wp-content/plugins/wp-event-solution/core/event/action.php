<?php

namespace Etn\Core\Event;

defined( 'ABSPATH' ) || exit;
/**
 * Action Class.
 * for post insert, update and get data.
 *
 * @since 1.0.0
 */
class Action {

    use \Etn\Traits\Singleton;

    public $key_form_settings;
    private $post_type;

    private $fields;
    private $form_id;
    private $form_setting;
    private $title;
    private $response = [];

    /**
     * Public function __construct.
     * call function for all
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->post_type         = 'etn';
        $this->key_form_settings = 'etn_event_post_meta';

        $this->response = [
            'saved'  => false,
            'status' => esc_html__( "Something went wrong.", 'eventin' ),
            'data'   => [],
        ];
    }

    /**
     * Nav Menu Current Page
     *
     * @param [type] $atts
     * @param [type] $item
     * @param [type] $args
     * @return void
     */
    public function nav_menu_current_page( $atts, $item, $args ) {
        return $atts;
    }

    /**
     * Sanitize Input
     *
     * @param [type] $form_setting
     * @param [type] $fields
     * @return void
     */
    public function sanitize( $form_setting, $fields = null ) {

        if ( $fields == null ) {
            $fields = $this->fields;
        }

        foreach ( $form_setting as $key => $value ) {

            if ( isset( $fields[$key] ) ) {
                $this->form_setting[$key] = $value;
            }

        }

    }

}
