<?php
namespace Etn\Base;

class Api_Handler {

    public $prefix  = '';
    public $param   = '';
    public $args    = [];
    public $request = null;

    /**
     * constructor function for the class
     * 
     * @return void
     */
    public function __construct() {
        $this->config();
        $this->init();
    }

    /**
     * config can be override by child class
     * 
     * @return void
     */
    public function config() {

    }

    /**
     * rest api pattern buildup process
     * 
     * @return void
     */
    public function init() {
        add_action( 'rest_api_init', function () {
            register_rest_route( untrailingslashit( 'eventin/v1/' . $this->prefix ), '/(?P<action>\w+)/' . ltrim( $this->param, '/' ), [
                'methods'             => \WP_REST_Server::ALLMETHODS,
                'callback'            => [$this, 'callback'],
                'permission_callback' => '__return_true',
                // all permissions are implemented inside the callback action
            ] );
        } );
    }

    /**
     * callback function after api endpoint fired
     * 
     * @return void
     */
    public function callback( $request ) {
         $this->request = $request;
        if ( $this->request->get_params()['action'] == 'settings'  ) {
            $this->request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
        }

        $action_class = strtolower( $this->request->get_method() ) . '_' . $this->request['action'];

        if ( method_exists( $this, $action_class ) ) {
            return $this->{$action_class}();
        }

    }

}
