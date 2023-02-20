<?php

namespace Etn\Core\Attendee;

defined( "ABSPATH" ) || exit;

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
        $this->post_type         = 'etn_attendee';
        $this->key_form_settings = 'etn_attendee_post_meta';

        $this->response = [
            'saved'  => false,
            'status' => esc_html__( "Something went wrong.", 'eventin' ),
            'data'   => [],
        ];
    }

    public function nav_menu_current_page( $atts, $item, $args ) {
        return $atts;
    }

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
