<?php

namespace Etn\Base;

defined( 'ABSPATH' ) || exit;

class Action {

    use \Etn\Traits\Singleton;

    private $key_form_settings;
    private $key_option_settings;
    private $key_form_count_views;
    private $post_type;

    private $fields;
    private $form_id;
    private $form_setting;
    private $title;
    private $response = [];

    public function __construct() {
        $this->key_option_settings = 'etn_event_options';
        $this->response            = [
            'saved'  => false,
            'status' => esc_html__( "Something went wrong.", 'eventin' ),
            'data'   => [],
        ];
    }

    public function store( $form_id, $form_data ) {

        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
 
        $this->sanitize( $form_data );
        $this->form_id = $form_id;

        if ( $this->form_id == -1 ) {
            $this->update_option_settings();
        }

        return $this->response;
    }

    public function sanitize( $form_setting ) {

        foreach ( $form_setting as $key => $value ) {
            $this->form_setting[sanitize_key( $key )] = $value ;
        }
    }

    public function update_option_settings() {
        $status = update_option( $this->key_option_settings, $this->form_setting );

        if ( $status ) {
            $this->response['saved']  = true;
            $this->response['status'] = esc_html__( 'Eventin Settings Updated', 'eventin' );
            $this->response['key']    = $this->key_option_settings;
            $this->response['data']   = $this->form_setting;
        }

				$tab_name = !empty( $this->form_setting['etn_tab']) ? $this->form_setting['etn_tab'] : 'tab1';
				$redirect = 'admin.php?page=etn-event-settings';

				return wp_safe_redirect( add_query_arg( 'etn_tab', $tab_name, $redirect ) );

    }

    public function get_fields() {
        // return Base::instance()->form->get_form_settings_fields();
    }

}
