<?php

namespace Etn\Core\Speaker\Pages;

defined( 'ABSPATH' ) || exit;

class Speaker_single_post {
    
    use \Etn\Traits\Singleton;

    function __construct() {
        add_action( 'single_template', [ $this, 'speaker_single_template' ] );
        add_filter( 'archive_template', [$this, 'speaker_archive_template'] );
    }

    function speaker_archive_template( $template ) {

        if ( is_post_type_archive( 'etn-speaker' ) ) {
            $default_file = \Wpeventin::plugin_dir() . 'core/speaker/views/single/speaker-archive-page.php';
            if ( file_exists( $default_file ) ) {
                $template = $default_file;
            }
        }

        return $template;
    }

    function speaker_single_template( $single ) {
        global $post;
        if ( $post->post_type == 'etn-speaker' ) {
            if ( file_exists( \Wpeventin::plugin_dir() . 'core/speaker/views/single/speaker-single-page.php' ) ) {
                return \Wpeventin::plugin_dir() . 'core/speaker/views/single/speaker-single-page.php';
            }
        }
        
        return $single;
    }

}
