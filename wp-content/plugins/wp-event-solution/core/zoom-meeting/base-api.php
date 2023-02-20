<?php
namespace Etn\Core\Zoom_Meeting;

defined('ABSPATH') || exit;

include_once \Wpeventin::plugin_dir() .'core/zoom-meeting/jwt/jwt.php';
use Exception;
use Firebase\JWT\JWT;


abstract class Base_Api{
    /**
     * Zoom API KEY
     * @var
     */
    public $zoom_api_key;

    /**
     * Zoom API Secret
     * @var
     */
    public $zoom_api_secret;

    /**
     * Hold paramter
     *
     * @var string
     */
    public $param = '';

    /**
     * API endpoint base
     *
     * @var string
     */
    private $api_url = 'https://api.zoom.us/v2/';

    public function __construct(){
        $this->config();
    }

    /**
     * Hold configuration paramter
     *
     * @return void
     */
    public abstract function config();

    /**
     * Call api function
     *
     * @return void
     */
    public function init( $called_function , $data , $request ){
        $request_url = $this->api_url . $called_function ;

        try {
                $args = array( 'headers' => 
                    array(
                        'Authorization' => 'Bearer ' . $this->generate_token(),
                        'Content-Type'  => 'application/json'
                    ) );
                $response = [];    
                switch ( $request ) {
                case 'GET':
                    $args['body'] = ! empty( $data ) ? $data : [];
                    $response     = wp_remote_get( $request_url, $args );
                    break;
                case 'POST':
                    $args['body']   = ! empty( $data ) ? json_encode( $data ) : [];
                    $args['method'] = "POST";
                    $response       = wp_remote_post( $request_url, $args );
                    break;
                case 'PATCH':
                    $args['body']   = ! empty( $data ) ? json_encode( $data ) : [];
                    $args['method'] = "PATCH";
                    $response       = wp_remote_request( $request_url, $args );
                    break;
                case 'DELETE':
                    $args['body']   = ! empty( $data ) ? json_encode( $data ) : [];
                    $args['method'] = "DELETE";
                    $response       = wp_remote_request( $request_url, $args );
                    break;
                default:
                    break;
            }
            
            $response_body = wp_remote_retrieve_body( $response );
            
            if( ! $response_body ){
                return false;
            }
            return $response_body;

        } catch ( Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Generate token function
     *
     * @return void
     */
    public function generate_token()
    {
        $key    = $this->zoom_api_key;
        $secret = $this->zoom_api_secret;    
        $token = array(
            "iss" => $key,
            "exp" => time() + 3600 //60 seconds as suggested
        );
        return JWT::encode( $token , $secret );
    }
}
