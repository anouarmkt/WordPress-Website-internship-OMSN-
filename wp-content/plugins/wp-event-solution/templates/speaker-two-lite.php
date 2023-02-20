<?php

defined( 'ABSPATH' ) || exit;
    ?>
    <div class="etn-single-speaker-wrapper etn-speaker-detail2 ">
        <div class="etn-speaker-details-info-wrap">
            <div class="etn-row">
                <div class="etn-col-lg-4">
                    <div class="speaker-sidebar">
                        <?php 
                        $speaker_avatar = apply_filters("etn/speakers/avatar", \Wpeventin::assets_url() . "images/avatar.jpg");
                        ?>
                        <div class="etn-speaker-thumb">
                            <?php if(has_post_thumbnail()): ?>
                                <?php echo get_the_post_thumbnail(); ?>
                            <?php else: ?>
                                <img src="<?php echo esc_url( $speaker_avatar ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="speaker-title-info">
                            <?php 
                                /**
                                * Speaker meta hook.
                                *
                                * @hooked etn_speaker_company_logo - 12
                                */
                                do_action('etn_speaker_company_logo');
                            ?>
                            <h3 class="etn-title etn-speaker-name">
                                <?php echo esc_html( apply_filters('etn_speaker_title', get_the_title()) ); ?> 
                            </h3>
                            <?php
                                /**
                                * Speaker designation hook.
                                *
                                * @hooked speaker_three_designation - 10
                                */
                                do_action('etn_speaker_designation');

                                  /**
                                    * Speaker meta hook.
                                    *
                                    * @hooked speaker_meta - 12
                                    */
                                    do_action('etn_speaker_meta');
                           
                            ?>
                        </div>
                        
                        <div class="etn-speaker-info">
                            <?php
                                /**
                                * Speaker meta hook.
                                *
                                * @hooked speaker_three_meta - 11
                                */
                                do_action('etn_speaker_three_meta');
                                /**
                                * Speaker meta hook.
                                *
                                * @hooked speaker_three_social - 12
                                */
                                do_action('etn_speaker_socials');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="etn-col-lg-8">
                    <?php
                        /**
                        * Speaker summary hook.
                        *
                        * @hooked speaker_three_summary - 13
                        */
                        do_action('etn_speaker_summary');

                        /**
                        * Speaker summary hook.
                        *
                        * @hooked speaker_three_details_before - 15
                        */
                        do_action('etn_speaker_details_before');
                    ?>
                <!-- schedule list -->
                <div class="schedule-list-wrapper">
                    <?php
                        /**
                        * Speaker sessions title hook.
                        *
                        * @hooked speaker_sessions_title - 15
                        */
                        do_action('etn_speaker_two_lite_sessions_title');
                    ?>

                    
                    <div class="schedule-tab-wrapper etn-tab-wrapper">
                        <?php
						$orgs = \Etn\Utils\Helper::speaker_sessions( get_the_ID());

                        if (count( $orgs ) > 0) {
                            ?>
                            <ul class="etn-nav">
                                <?php
                                foreach ($orgs as $key=>$org) {
                                    $post_id                 = $org['post_id'];
                                    $etn_schedule_meta_value = unserialize( $org['meta_value'] );
                                    $schedule_date           = get_post_meta( $post_id, 'etn_schedule_date', true );
                                    $head_title = get_post_meta( $post_id, 'etn_schedule_title', true );
                                    $head_date  = date_i18n( get_option( "date_format" ), strtotime( $schedule_date ) );
                
                                    /**
                                     * Speaker schedule header hook.
                                     *
                                     * @hooked schedule_two_header - 18
                                     */
                                    do_action( 'etn_schedule_two_lite_header', $head_title, $head_date, $key );

                                }
                                ?>
                            </ul>
                            <div class="etn-tab-content clearfix">
                                <?php
                                foreach ($orgs as $key=>$org) {

                                    /**
                                    * Speaker sessions before hook.
                                    *
                                    * @hooked etn_speaker_four_sessions_details_before - 16
                                    */

                                    do_action( 'etn_speaker_four_sessions_details_before' );

                                    /**
                                    * Speaker sessions details hook.
                                    *
                                    * @hooked speaker_sessions_details - 17
                                    */

                                    do_action('etn_speaker_two_lite_sessions_details' , $org ,$key);

                                    
                                    /**
                                    * Inside Speaker sessions details action.
                                    * etn_schedule_four_header -18
                                    * @hooked schedule_four_header
                                    * etn_schedule_session_time - 19
                                    * @hooked schedule_session_time 
                                    * etn_schedule_session_title - 20
                                    * @hooked schedule_session_title 
                                    * etn_schedule_session_location - 21
                                    * @hooked schedule_session_location 
                                    */

                                    /**
                                    * Speaker sessions details after hook.
                                    *
                                    * etn_speaker_four_sessions_details_after  - 22
                                    * @hooked speaker_sessions_details_after
                                    */
                                    do_action( 'etn_speaker_details_after' );

                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- schedule list -->
    </div>
