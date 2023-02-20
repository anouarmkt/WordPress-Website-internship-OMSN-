<?php
defined( 'ABSPATH' ) || exit;

/********************
    Speaker one start
 ********************/


if ( !function_exists( 'speaker_designation' ) ) {
    /**
     * Designation
     */
    function speaker_designation() {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-designation.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-designation.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-designation.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-designation.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'speaker/speaker-designation.php';
            }
        }

    }

}

if ( !function_exists( 'speaker_summary' ) ) {
    /**
     * Summary
     */
    function speaker_summary() {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-summary.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-summary.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-summary.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-summary.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'speaker/speaker-summary.php';
            }
        }

    }

}

if ( !function_exists( 'speaker_socials' ) ) {
    /**
     * Socials
     */
    function speaker_socials() {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-socials.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-socials.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-socials.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-socials.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'speaker/speaker-socials.php';
            }
        }

    }
}

if ( !function_exists( 'schedule_time' ) ) {
    /**
     * Schedule time
     */
    function schedule_time( $start, $end ) {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-time.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-time.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-time.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-time.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/schedule-time.php';
            }
        }

    }

}

if ( !function_exists( 'schedule_locations' ) ) {
    /**
     * Schedule location
     */
    function schedule_locations( $etn_shedule_room ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-locations.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-locations.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-locations.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-locations.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/schedule-locations.php';
            }
        }

    }

}

if ( !function_exists( 'speaker_topic' ) ) {
    /**
     * Schedule topic
     */
    function speaker_topic( $topic ) {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-topic.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-topic.php';
            } elseif ( file_exists( get_template_directory(  ) . \Wpeventin::theme_templates_dir() . 'speaker/schedule-topic.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-topic.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/schedule-topic.php';
            }
        }

    }

}

if ( !function_exists( 'speaker_objective' ) ) {
    /**
     * Schedule objective
     */
    function speaker_objective( $objective ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ( ETN_SPEAKER_TEMPLATE_TWO_ID != get_the_ID(  ) && ETN_SPEAKER_TEMPLATE_THREE_ID != get_the_ID(  ) ) ) ){
            
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-objective.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-objective.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-objective.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-objective.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/speaker-objective.php';
            }
        }

    }

}

/* **********************
*  Speaker one end
************************/

/**********
 * Default hooks required for all templates
 **********/

if ( !function_exists( 'etn_single_speaker_template_select' ) ) {
    function etn_single_speaker_template_select() {
        $default_template_name = "speaker-one";
        $settings              = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $template_name         = !empty( $settings['speaker_template'] ) ? $settings['speaker_template'] : $default_template_name;
        if( ETN_DEMO_SITE === true ) {

            switch( get_the_ID() ){
                case ETN_SPEAKER_TEMPLATE_ONE_ID :
                    $single_template_path = \Wpeventin::templates_dir() . "speaker-one.php";
                    break;
                case ETN_SPEAKER_TEMPLATE_TWO_LITE_ID :
                    $single_template_path = \Wpeventin::templates_dir() . "speaker-two-lite.php";
                    break;
                case ETN_SPEAKER_TEMPLATE_TWO_ID :
                    $single_template_path = \Wpeventin_Pro::templates_dir() . "speaker-two.php";
                    break;
                case ETN_SPEAKER_TEMPLATE_THREE_ID :
                    $single_template_path = \Wpeventin_Pro::templates_dir() . "speaker-three.php";
                    break;
                default:
                    $single_template_path = \Etn\Utils\Helper::prepare_speaker_template_path( $default_template_name, $template_name );
                    break;
            }

            if ( file_exists( $single_template_path ) ) {
                include_once $single_template_path;
            }

        } else {
    
            //check if single page template is overriden from theme
            //if overriden, then the overriden template has higher priority
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php' ) ) {
                include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php' ) ) {
                include_once get_template_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php';
            } else {
    
                // check if multi-template settings exists
                $single_template_path = \Etn\Utils\Helper::prepare_speaker_template_path( $default_template_name, $template_name );
    
                if ( file_exists( $single_template_path ) ) {
                    include_once $single_template_path;
                }
    
            }
        }

    }

}

if ( !function_exists( 'etn_before_single_speaker_content' ) ) {
    /**
     * Speaker single page before
     */
    function etn_before_single_speaker_content() {
        $options       = get_option( 'etn_event_general_options' );
        $container_cls = isset( $options['single_post_container_width_cls'] ) ? $options['single_post_container_width_cls'] : '';
        ?>
        <div class="etn-speaker-page-container  <?php echo esc_attr( $container_cls ); ?>">
            <div class="etn-container">
        <?php
    }

}

if ( !function_exists( 'etn_after_single_speaker_content' ) ) {

    /**
     * Speaker single page after
     */
    function etn_after_single_speaker_content() {
        ?>
            </div>
        </div>
        <?php
    }

}

if ( !function_exists( 'speaker_main_content_before' ) ) {
    /**
     * Speaker main wrapper  before
     */
    function speaker_main_content_before() {
        return;
    }

}

if ( !function_exists( 'speaker_title_before' ) ) {
    /**
     * Speaker title  before
     */
    function speaker_title_before() {
        return;
    }

}

if ( !function_exists( 'speaker_details_before' ) ) {
    /**
     * Speaker details  before
     */
    function speaker_details_before() {
        return;
    }

}

if ( !function_exists( 'speaker_title_after' ) ) {
    /**
     * Speaker title  after
     */
    function speaker_title_after() {
        return;
    }

}

if ( !function_exists( 'speaker_details_after' ) ) {
    /**
     * Speaker details after
     */
    function speaker_details_after() {
        return;
    }

}

if ( !function_exists( 'speaker_main_content_after' ) ) {
    /**
     * Speaker main wrapper  after
     */
    function speaker_main_content_after() {
        return;
    }

}

if( !function_exists('etn_before_speaker_archive_content_show_thumbnail') ){

    function etn_before_speaker_archive_content_show_thumbnail( $single_event_id ){
        $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
        if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/archive/thumbnail-content.php' ) ) {
            include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/archive/thumbnail-content.php';
        } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/archive/thumbnail-content.php' ) ) {
            include get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/archive/thumbnail-content.php';
        } else {
            include \Wpeventin::templates_dir() . 'speaker/archive/thumbnail-content.php';
        }
    }
}

if ( !function_exists( 'schedule_two_lite_header' ) ) {
    /**
     * Speaker main wrapper  before
     */
    function schedule_two_lite_header( $head_title, $head_date, $key ) {
        
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_LITE_ID == get_the_ID(  ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-session-nav.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-session-nav.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-session-nav.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-session-nav.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/speaker-session-nav.php';
            }
        }

    }
}

if ( !function_exists( 'speaker_two_lite_sessions_title' ) ) {
    /**
     *  Speaker sessions details title.
     */
    function speaker_two_lite_sessions_title() {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_LITE_ID == get_the_ID(  ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-header.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-header.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-header.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/schedule-header.php';
            }else {

                require \Wpeventin::templates_dir() . 'speaker/schedule-header.php';
            }
        }

    }

}


if ( !function_exists( 'speaker_two_lite_sessions_details' ) ) {

    /**
     *  Speaker sessions details hook.
     */
    function speaker_two_lite_sessions_details( $org, $key ) {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_LITE_ID == get_the_ID(  ) ) ){
            if ( is_array( $org ) && count( $org ) > 0 ) {
                $post_id                 = $org['post_id'];
                $etn_schedule_meta_value = unserialize( $org['meta_value'] );
                $schedule_date           = get_post_meta( $post_id, 'etn_schedule_date', true );
                $active_class = ($key===0) ? 'tab-active' : '';
                ?>
                    <div class="etn-tab <?php echo esc_attr($active_class); ?>" data-id="tab-<?php echo esc_attr($key); ?>">
                        <?php
                        foreach ( $etn_schedule_meta_value as $single_meta ) {
    
                            if (  !empty( $single_meta['etn_shedule_speaker'] ) && is_array( $single_meta['etn_shedule_speaker'] ) && in_array( get_the_id(), $single_meta['etn_shedule_speaker'] ) ):
                                ?>
                                <div class="schedule-listing multi-schedule-list">

                                     <div class="schedule-slot-time">

                                                <?php
                
                                                /**
                                                 * Speaker schedule time hook.
                                                 *
                                                 * @hooked schedule_session_time - 19
                                                 */
                                                do_action( 'etn_schedule_two_lite_session_time', $single_meta["etn_shedule_start_time"], $single_meta["etn_shedule_end_time"] );
                
                                                ?>
                                        </div>
                                    <div class="schedule-slot-info">
                                        <?php
                                         /**
                                         * Speaker  session location
                                         *
                                         * @hooked schedule_four_session_location - 21
                                         */
                                        do_action( 'etn_schedule_two_lite_location', $single_meta["etn_shedule_room"] );


                                        /**
                                         * Speaker session title
                                         *
                                         * @hooked schedule_session_title - 20
                                         */
                                        do_action( 'etn_schedule_two_lite_session_title', $single_meta["etn_schedule_topic"] );
                                      
                                       /**
                                         * Speaker session description
                                         *
                                         * @hooked etn_shedule_objective - 20
                                         */
                                        do_action( 'etn_speaker_objective' , $single_meta["etn_shedule_objective"]  );

                                   
                                        ?>
                                    </div>
                                </div>
                            <?php 
                            endif;
                        }
                        ?>
                    </div>
                <?php
            }
        }
    }

}


if ( !function_exists( 'speaker_meta' ) ) {
    /**
     * Speaker meta
     */
    function speaker_meta() {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_ID == get_the_ID(  ) ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_LITE_ID == get_the_ID(  ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-meta.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-meta.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-meta.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-meta.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/speaker-meta.php';
            }
        }

    }

}

if ( !function_exists( 'speaker_company_logo' ) ) {
    /**
     * Speaker Company
     */
    function speaker_company_logo( $logo ) {
        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE === true && ETN_SPEAKER_TEMPLATE_TWO_ID == get_the_ID(  ) ) ){
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-company-logo.php' ) ) {
                require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-company-logo.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-company-logo.php' ) ) {
                require get_template_directory() . \Wpeventin::theme_templates_dir() . 'speaker/speaker-company-logo.php';
            } else {
                require \Wpeventin::templates_dir() . 'speaker/speaker-company-logo.php';
            }
        }

    }

}