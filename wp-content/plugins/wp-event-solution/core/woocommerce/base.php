<?php

namespace Etn\Core\Woocommerce;

defined( 'ABSPATH' ) || exit;

class Base extends \Etn\Base\Common {

    use \Etn\Traits\Singleton;

    // $api veriable call for Cpt Class Instance
    public $product;

    // $api veriable call for Api Class Instance
    public $api;

    // set template type for template
    public $template_type = [];

    public function get_dir() {
        return dirname( __FILE__ );
    }

    public function init() {
        // call custom post type
        Hooks::instance()->Init();
    }
}
