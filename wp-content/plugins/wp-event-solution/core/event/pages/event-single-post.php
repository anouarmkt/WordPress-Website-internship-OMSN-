<?php

namespace Etn\Core\Event\Pages;

defined( 'ABSPATH' ) || exit;

class Event_single_post {

    use \Etn\Traits\Singleton;

    function __construct() {
        add_action( 'single_template', [$this, 'event_single_page'] );
        add_filter( 'archive_template', [$this, 'event_archive_template'] );
    }

    function event_archive_template( $template ) {

        if ( is_post_type_archive( 'etn' ) ) {
            $default_file = \Wpeventin::plugin_dir() . 'core/event/views/event-archive-page.php';
            if ( file_exists( $default_file ) ) {
                $template = $default_file;
            }
        }

        return $template;
    }

    function event_single_page( $single ) {
        global $post;
        if ( $post->post_type == 'etn' &&  is_singular( 'etn' ) ) {
            $default_file = \Wpeventin::plugin_dir() . 'core/event/views/event-single-page.php';
            if ( file_exists( $default_file ) ) {
                $single = $default_file;
            }
        }
        return $single;
    }

}
