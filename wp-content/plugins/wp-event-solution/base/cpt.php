<?php

namespace Etn\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Cpt Abstract Class.
 * Cpt Abstract class for custom post type of Builders.
 *
 * @since 1.0.0
 */
abstract class Cpt {

    /**
     * __construct function
     * @since 1.0.0
     */
    public function __construct() {

        $name = $this->get_name();
        $args = $this->post_type();

        // if( $this->create_cpt() ){
            add_action( 'init', 
                function () use ( $name, $args ) {
                    register_post_type( $name, $args );
                    flush_rewrite_rules();
                } 
            );
        // }
    }

    public abstract function get_name();
    public abstract function post_type();
}
