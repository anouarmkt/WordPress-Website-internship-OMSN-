<?php

namespace Etn\Base;

defined( 'ABSPATH' ) || exit;
/**
 * Cpt Abstract Class.
 * Cpt Abstract class for custom post type of Builders.
 *
 * @since 1.0.0
 */
abstract class Taxonomy {

    /**
     * __construct function
     * @since 1.0.0
     */
    public function __construct() {
        // get custom post type name
        $name = $this->get_name();
        $cpt  = $this->get_cpt();
        // get custom post optios data
        $args = $this->taxonomy();

        // register custom post type
        add_action( 'init', function () use ( $name, $cpt, $args ) {
            register_taxonomy( $name, $cpt, $args );
        } );

       
        
    }

    public abstract function taxonomy();
}
