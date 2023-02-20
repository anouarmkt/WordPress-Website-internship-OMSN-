<?php

namespace Etn\Core\Schedule\Pages;

defined( 'ABSPATH' ) || exit;

class Schedule_single_post {
    
    use \Etn\Traits\Singleton;

    /**
     * Register single template
     */
    public function __construct() {
        add_action( 'single_template', [ $this, 'schedule' ] );
    }

    /**
     * Call  template
    */
    public function schedule( $single ) {

        global $post;

        if ( $post->post_type == 'etn-schedule' ) {
            if ( file_exists( \Wpeventin::plugin_dir() . 'core/schedule/views/single/schedule-single-page.php' ) ) {
                return \Wpeventin::plugin_dir() . 'core/schedule/views/single/schedule-single-page.php';
            }
        }

        return $single;
    }

}
