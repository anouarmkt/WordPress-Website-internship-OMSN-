<?php

namespace Etn\Core\Speaker;

defined( 'ABSPATH' ) || exit;

class Settings {
    private $plugin_name;

    private $version;

    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }
}
