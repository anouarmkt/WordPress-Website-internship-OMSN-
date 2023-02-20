<?php

namespace Etn\Core\Attendee\Pages;

defined( 'ABSPATH' ) || exit;

class Attendee_Single_Page {

    use \Etn\Traits\Singleton;

    function __construct() {
        add_action( 'archive_template', [$this, 'attendee_archive_template'] );
    }
    
    function attendee_archive_template( $template ) {

        if ( is_post_type_archive( 'etn-attendee' ) ) {
            if ( file_exists( \Wpeventin::plugin_dir() . 'core/attendee/views/single/attendee-archive-page.php' ) ) {
                return \Wpeventin::plugin_dir() . 'core/attendee/views/single/attendee-archive-page.php';
            }
        }

        return $template;
    }

}
