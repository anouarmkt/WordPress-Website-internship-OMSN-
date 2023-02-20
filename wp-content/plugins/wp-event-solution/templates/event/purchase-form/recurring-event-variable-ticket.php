<?php
use Etn\Utils\Helper;

$etn_left_tickets       = !empty( $data['etn_left_tickets'] ) ? $data['etn_left_tickets'] : 0;
$etn_ticket_unlimited   = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
$etn_ticket_price       = isset( $data['etn_ticket_price'] ) ? $data['etn_ticket_price'] : '';
$ticket_qty             = get_post_meta( $single_event_id, "etn_sold_tickets", true );
$total_sold_ticket      = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
$is_zoom_event          = get_post_meta( $single_event_id, 'etn_zoom_event', true );
$event_options          = !empty( $data['event_options'] ) ? $data['event_options'] : [];
$event_title            = get_the_title( $single_event_id );
$separate               = (!empty($data['event_end_date'])) ? ' - ' : '';
$settings               = Helper::get_settings();
$attendee_reg_enable    = !empty( $settings["attendee_registration"] ) ? true : false;
$active_class           = ($i===0) ? 'active' : '';
$active_item            = ($i===0) ? 'style=display:block' : '';
$etn_min_ticket       = !empty(get_post_meta( $single_event_id, 'etn_min_ticket', true )) ? get_post_meta( $single_event_id, 'etn_min_ticket', true ) : 1;
$etn_max_ticket       = !empty(get_post_meta( $single_event_id, 'etn_max_ticket', true )) ? get_post_meta( $single_event_id, 'etn_max_ticket', true ) : $etn_left_tickets;
$etn_max_ticket       =  min($etn_left_tickets, $etn_max_ticket);
 
?>
<div class="etn-widget etn-recurring-widget <?php echo esc_attr($active_class); ?>">
    <div class="etn-row">
        <div class="etn-col-lg-12">
            <div class="recurring-content <?php echo esc_attr($active_class);?>">
                <div class="etn-recurring-header">
                    <div class="etn-left-datemeta">
                        <div class="etn-date-meta">
                            <?php
                            $start_date_int     = strtotime( str_replace( '/', '-', $data['event_start_date'] ) );
                            $end_date_int       = strtotime( str_replace( '/', '-', $data['event_end_date'] ) );

                            $start_date_d       = date_i18n( 'j', $start_date_int );
                            $end_date_d         = date_i18n( 'j', $end_date_int );
                            $start_date_m_y     = date_i18n( 'M, Y', $start_date_int );
                            $end_date_m_y       = date_i18n( 'M, Y', $end_date_int );

                            $start_date_d_m_y   = $start_date_d . ' ' . $start_date_m_y;
                            $end_date_d_m_y     = $end_date_d . ' ' . $end_date_m_y;
                            
                            $same_d_m_y         = ( $start_date_d_m_y == $end_date_d_m_y ) ? true : false; 
                            $same_m_y           = ( $start_date_m_y == $end_date_m_y ) ? true : false; 
                            ?>

                            <p class="etn-date-text">
                                <?php echo Helper::render($start_date_d_m_y); ?>
                            </p>

                            <?php 
                            if( !$same_d_m_y ){
                                
                                ?>
                                <p class="etn-date-to">
                                    <?php echo esc_html__('To', 'eventin')?>
                                </p>

                                <p class="etn-date-text">
                                    <?php echo Helper::render( $end_date_d_m_y ); ?>
                                </p>
                                <?php

                            }
                            ?>
                        </div>
                        <?php
                            // show if this is a zoom event
                            if ( isset( $is_zoom_event ) && "on" == $is_zoom_event ) {
                                ?>
                                <div class="etn-zoom-event-notice">
                                    <img src="<?php echo esc_url(\Wpeventin::assets_url() . "images/zoom.svg"); ?>" alt="<?php echo esc_attr__('Zoom', 'eventin') ?>">
                                    <?php echo esc_html__( "Zoom Event", "eventin" ); ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="etn-title-wrap">
                        <div class="etn-time-meta">
                            <?php
                            if ( !isset($event_options["etn_hide_time_from_details"]) ) {
                                $separate = (!empty($data['event_end_time'])) ? ' - ' : '';
                                ?>
                                <li>
                                    <i class="far fa-clock"></i>
                                    <?php echo esc_html($data['event_start_time'] . $separate . $data['event_end_time']); ?>
                                    <span class="etn-event-timezone">
                                        
                                        <?php
                                        if ( !empty( $data['event_timezone'] ) && !isset($event_options["etn_hide_timezone_from_details"]) ) {
                                            ?>
                                            (<?php echo esc_html( $data['event_timezone'] ); ?>)
                                            <?php
                                        }
                                        ?>
                                    </span>
                                </li>
                                <?php
                            }
                            ?>
                        </div>
                        <h4 class="etn-title etn-post-title etn-accordion-heading"> 
                            <a href="<?php  echo esc_url( get_permalink( $single_event_id ) ); ?>">
                                <?php  echo esc_html( $event_title  ); ?>
                            </a>
                        </h4>
                        <p>
                            <?php 
                            // echo esc_html(wp_trim_words(get_the_content($single_event_id), 10, ' ')); 
                            ?>
                        </p>
                    </div>
                    <div class="etn-thumb-wrap">
                        <?php echo get_the_post_thumbnail($single_event_id); ?>
                    </div>
                    <i class="etn-arrow fa fa-angle-down"></i>
                </div>
                <div class="etn-widget etn-variable-ticket-widget etn-form-wrap" <?php echo esc_attr($active_item ); ?>>
                    <div class="etn-row">
                        <div class="etn-col-lg-4">
                            <div class="etn-recurring-add-calendar">
                                <?php
                                    etn_after_single_event_meta_add_to_calendar($single_event_id);
                                ?>
                            </div>
                        </div>
                        <div class="etn-col-lg-8">
                            <?php
                            
                            $show_form_button = apply_filters("etn_form_submit_visibility", true, $single_event_id);

                            if( $event_left_ticket <= 0 ) {
                                ?>
                                <h4><?php echo esc_html__( 'All Tickets Sold!!', "eventin" ); ?></h4>
                                <?php
                            } else if ( $reg_deadline_expired ) {
                                ?>
                                <h4><?php echo esc_html__( 'Registration Deadline Expired!!', "eventin" ); ?></h4>
                                <?php
                            } else if( $show_form_button === false ){
                                 ?>
                                <h4><?php echo esc_html__( 'Registration Deadline Expired!!', "eventin" ); ?></h4>
                                <?php
                            } else {
                            
                                $settings            = Helper::get_settings();
                                $attendee_reg_enable = !empty( $settings["attendee_registration"] ) ? true : false;
                    
                                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/variable-ticket-form-template.php' ) ) {
                                    $purchase_form_template = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/variable-ticket-form-template.php';
                                } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/variable-ticket-form-template.php' ) ) {
                                    $purchase_form_template = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/variable-ticket-form-template.php';
                                } else {
                                    $purchase_form_template = \Wpeventin::templates_dir() . "event/purchase-form/template/variable-ticket-form-template.php";
                                }
                    
                                $form_template = apply_filters( "etn/purchase_form_template", $purchase_form_template );

                                if ( file_exists( $form_template ) ) {
                                    include $form_template;
                                }
                                
                            } 
                            ?>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
