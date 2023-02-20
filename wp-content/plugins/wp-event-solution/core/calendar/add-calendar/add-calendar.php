<?php

namespace Etn\Core\Calendar\Add_Calendar;

defined( 'ABSPATH' ) || die();

use Etn\Utils\Helper;

class Add_Calendar {

    function etn_add_to_google_calender_link( $pid ) {
        $event       = get_post( $pid );
        $event_meta  = get_post_meta( $pid );
        $event_start = $event_meta['etn_start_date'][0] . ' ' . $event_meta['etn_start_time'][0];
        $event_end   = $event_meta['etn_end_date'][0] . ' ' . $event_meta['etn_end_time'][0];
        $location    = $event_meta['etn_event_location'][0];
        ?>
        <div class='etn-add-to-calender-title'>
            <h4 class="etn-widget-title etn-title"><?php echo esc_html__( 'Add To Calendar', 'eventin' ); ?></h4>
        </div>
        <ul id="etn_add_calender_links" class="etn-calender-list">
            <li>
                <a href ="https://calendar.google.com/calendar/r/eventedit?text=<?php echo esc_html( Helper::convert_to_calendar_title( $event->post_title ) ); ?>&dates=<?php echo esc_html( Helper::convert_to_calender_date( $event_start ) ); ?>/<?php echo esc_html( Helper::convert_to_calender_date( $event_end ) ); ?>&details=<?php echo esc_html( substr( Helper::content_to_html( $event->post_content ), 0, 1000 ) ); ?>&location=<?php echo esc_html( $location ); ?>&sf=true" rel="noopener noreferrer" target='_blank' class='etn-add-to-calender' rel="nofollow">
                    <div class="calender-icon">
                        <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.263 4.737H4.737v10.526h10.526V4.737z" fill="#fff"/><path d="M15.263 20 20 15.263h-4.737V20z" fill="#EA4335"/><path d="M20 4.737h-4.737v10.526H20V4.737z" fill="#FBBC04"/><path d="M15.263 15.263H4.737V20h10.526v-4.737z" fill="#34A853"/><path d="M0 15.263v3.158C0 19.295.705 20 1.579 20h3.158v-4.737H0z" fill="#188038"/><path d="M20 4.737V1.579C20 .708 19.295 0 18.421 0h-3.158v4.737H20z" fill="#1967D2"/><path d="M15.263 0H1.58C.705 0 0 .708 0 1.579v13.684h4.737V4.737h10.526V0z" fill="#4285F4"/><path d="M6.897 12.903c-.392-.266-.666-.655-.816-1.166l.913-.376c.084.315.23.56.434.734.206.173.456.26.748.26.297 0 .555-.092.77-.273a.868.868 0 0 0 .325-.695.86.86 0 0 0-.34-.703c-.226-.181-.51-.273-.85-.273h-.529v-.903h.474c.292 0 .537-.079.737-.237a.78.78 0 0 0 .3-.647c0-.245-.09-.44-.269-.584-.179-.148-.405-.219-.681-.219-.269 0-.482.071-.64.216a1.275 1.275 0 0 0-.345.526l-.902-.376c.12-.34.34-.64.66-.9.321-.26.735-.39 1.235-.39.368 0 .702.072.997.216.295.142.526.342.692.595.166.253.25.537.25.855 0 .321-.076.595-.232.819a1.625 1.625 0 0 1-.573.515v.056c.292.12.547.318.734.573.192.256.287.564.287.921 0 .358-.092.677-.274.958-.181.282-.434.5-.75.66-.318.161-.679.243-1.079.243a2.279 2.279 0 0 1-1.276-.405zm5.608-4.532-1.003.724-.5-.76 1.8-1.298h.69v6.121h-.985V8.371h-.002z" fill="#4285F4"/></svg>
                    </div>
                    <p class="calender-name">
                        <?php echo esc_attr( 'Google Calendar', 'eventin' );?>
                    </p>
                </a>
            </li>
            <li>
                <a href ="https://calendar.yahoo.com/?v=60&view=d&type=20&title=<?php echo esc_html( Helper::convert_to_calendar_title( $event->post_title ) ); ?>&st=<?php echo esc_html( Helper::convert_to_calender_date( $event_start ) ); ?>&et=<?php echo esc_html( Helper::convert_to_calender_date( $event_end ) ); ?>&desc=<?php echo esc_html( substr( Helper::content_to_html( $event->post_content ), 0, 1000 ) ); ?>&in_loc=<?php echo esc_html( $location ); ?>&uid=" rel="noopener noreferrer" target='_blank' class='etn-add-to-calender' rel="nofollow">
                    <div class="calender-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3.04348C0 1.36261 1.36261 0 3.04348 0H16.9565C18.6374 0 20 1.36261 20 3.04348V16.9565C20 18.6374 18.6374 20 16.9565 20H3.04348C1.36261 20 0 18.6374 0 16.9565V3.04348Z" fill="#6001CF"/>
                            <path d="M13.4517 4.93927C13.4912 4.84356 13.5849 4.78145 13.6884 4.78262H15.4134C15.5513 4.78184 15.6642 4.89317 15.665 5.03106C15.6654 5.06427 15.6588 5.09669 15.6463 5.12716L13.8201 9.61661C13.7818 9.71192 13.6896 9.77442 13.5873 9.77442H11.865C11.7263 9.77442 11.6135 9.66192 11.6135 9.52325C11.6135 9.49083 11.6197 9.4584 11.6318 9.42833L13.4517 4.93927Z" fill="white"/>
                            <path d="M9.22439 7.36567L7.89939 10.654L6.61657 7.3645C6.57868 7.26801 6.48611 7.20434 6.3822 7.20395H4.59939C4.46072 7.20395 4.34783 7.31645 4.34783 7.45512C4.34783 7.48755 4.35408 7.51997 4.36619 7.55005L6.76618 13.4407L6.1529 14.959C6.09822 15.0868 6.15759 15.2344 6.28532 15.2887C6.31697 15.3024 6.35134 15.309 6.38572 15.309H8.10798C8.21032 15.3094 8.30251 15.2473 8.34079 15.1524L11.4131 7.55512C11.4654 7.42661 11.4037 7.27973 11.2752 7.22739C11.2474 7.21606 11.2181 7.20981 11.1881 7.20903H9.45525C9.35407 7.20903 9.26228 7.27114 9.22439 7.36567Z" fill="white"/>
                            <path d="M13.3964 11.9343C13.3964 12.7038 12.7726 13.3276 12.0031 13.3276C11.2335 13.3276 10.6097 12.7038 10.6097 11.9343C10.6097 11.1648 11.2335 10.5409 12.0031 10.5409C12.7726 10.5409 13.3964 11.1648 13.3964 11.9343Z" fill="white"/>
                        </svg>
                    </div>
                    <p class="calender-name">
                        <?php echo esc_attr( 'Yahoo Calendar', 'eventin' );?>
                    </p>
                </a>
            </li>
            <li>
                <a href ="https://webapps.genprod.com/wa/cal/download-ics.php?date_end=<?php echo esc_html( Helper::convert_to_calender_date( $event_end ) ); ?>&date_start=<?php echo esc_html( Helper::convert_to_calender_date( $event_start ) ); ?>&summary=<?php echo esc_html( Helper::convert_to_calendar_title( $event->post_title ) ); ?>&location=<?php echo esc_html( $location ); ?>&description=<?php echo esc_html( substr( Helper::content_to_html( $event->post_content ), 0, 1000 ) ); ?>" rel="noopener noreferrer" target='_blank' class='etn-add-to-calender'>
                    <div class="calender-icon">

                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.95246 20C1.99037 20 0 18.0096 0 15.0475V5.66665C0 2.70455 1.99037 0.714183 4.95246 0.714183H15.0474C18.0095 0.714183 19.9999 2.70455 19.9999 5.66665V15.0471C19.9999 18.0092 18.0095 19.9996 15.0474 19.9996L4.95246 20Z" fill="#ECECEC"/>
                            <path d="M0 5.71436V5.66665C0 2.70455 1.99037 0.714183 4.95246 0.714183H15.0474C18.0095 0.714183 19.9999 2.70455 19.9999 5.66665V5.71436H0Z" fill="#F4413D"/>
                            <path d="M6.99096 11.7452H7.8112C8.73402 11.7452 9.36858 11.1786 9.36858 10.3926C9.36858 9.63076 8.79247 9.09361 7.84022 9.09361C6.96631 9.09361 6.34606 9.62122 6.2729 10.4021H5.62839C5.71109 9.26934 6.60012 8.50755 7.86965 8.50755C9.11015 8.50755 10.0425 9.26418 10.0425 10.3095C10.0425 11.1933 9.47116 11.8279 8.57299 11.9889V12.0084C9.65207 12.072 10.3455 12.7408 10.3455 13.7371C10.3455 14.9283 9.28111 15.778 7.90901 15.778C6.488 15.778 5.52621 14.9673 5.48208 13.8393H6.12658C6.18026 14.6254 6.88838 15.1919 7.90424 15.1919C8.92447 15.1919 9.66718 14.5912 9.66718 13.7562C9.66718 12.8577 8.97417 12.311 7.84102 12.311H6.99135C6.99096 12.3114 6.99096 11.7452 6.99096 11.7452Z" fill="#413D3D"/>
                            <path d="M13.1575 9.32759H13.138C13.0259 9.39598 11.8053 10.2798 11.2482 10.6265V9.91365C11.4582 9.78165 12.9523 8.72722 13.1475 8.60993H13.802V15.6657H13.1575V9.32759Z" fill="#413D3D"/>
                            <path d="M4.64293 4.28559C5.23472 4.28559 5.71446 3.80586 5.71446 3.21407C5.71446 2.62229 5.23472 2.14255 4.64293 2.14255C4.05115 2.14255 3.57141 2.62229 3.57141 3.21407C3.57141 3.80586 4.05115 4.28559 4.64293 4.28559Z" fill="#C53431"/>
                            <path d="M15.3572 4.28559C15.949 4.28559 16.4287 3.80586 16.4287 3.21407C16.4287 2.62229 15.949 2.14255 15.3572 2.14255C14.7654 2.14255 14.2856 2.62229 14.2856 3.21407C14.2856 3.80586 14.7654 4.28559 15.3572 4.28559Z" fill="#C53431"/>
                            <path d="M4.64227 3.57121C4.44506 3.57121 4.28523 3.41138 4.28523 3.21417V0.357042C4.28523 0.159834 4.44506 0 4.64227 0C4.83948 0 4.99931 0.159834 4.99931 0.357042V3.21417C4.99971 3.41138 4.83948 3.57121 4.64227 3.57121Z" fill="url(#paint0_linear_218_29)"/>
                            <path d="M15.3565 3.57121C15.1593 3.57121 14.9995 3.41138 14.9995 3.21417V0.357042C14.9995 0.159834 15.1593 0 15.3565 0C15.5537 0 15.7135 0.159834 15.7135 0.357042V3.21417C15.7135 3.41138 15.5533 3.57121 15.3565 3.57121Z" fill="url(#paint1_linear_218_29)"/>
                            <defs>
                            <linearGradient id="paint0_linear_218_29" x1="10.0018" y1="20.001" x2="10.0018" y2="-8.70276e-05" gradientUnits="userSpaceOnUse">
                            <stop stop-color="white"/>
                            <stop offset="1" stop-color="#DCDCDC"/>
                            </linearGradient>
                            <linearGradient id="paint1_linear_218_29" x1="15.3566" y1="3.5714" x2="15.3566" y2="-1.55397e-05" gradientUnits="userSpaceOnUse">
                            <stop stop-color="white"/>
                            <stop offset="1" stop-color="#DCDCDC"/>
                            </linearGradient>
                            </defs>
                        </svg>

                    </div>
                    <p class="calender-name">
                        <?php echo esc_attr( 'Apple Calendar', 'eventin' );?>
                    </p>
                </a>
            </li>
            <li>
                <a href ="https://outlook.live.com/calendar/0/deeplink/compose?rru=addevent&enddt=<?php echo date('Y-m-d', strtotime($event_meta['etn_end_date'][0])) ; ?>T<?php echo urlencode(date("H:i:s", strtotime($event_meta['etn_end_time'][0]))); ?>&startdt=<?php echo date('Y-m-d', strtotime($event_meta['etn_start_date'][0])); ?>T<?php echo urlencode(date( "H:i:s", strtotime($event_meta['etn_start_time'][0]) )); ?>&allday=false" rel="noopener noreferrer" target='_blank' class='etn-add-to-calender' rel="nofollow">
                    <div class="calender-icon">
                        <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.265 4.5H12v2.48h7v7.77h-7v.75h7.265c.405 0 .735-.33.735-.735v-9.53a.736.736 0 0 0-.735-.735zM0 17.75 11.5 20V0L0 2.25v15.5z" fill="#1976D2"/><path d="M13.375 12.87h-1.35v1.45h1.35v-1.45zM15.075 12.87h-1.35v1.45h1.35v-1.45zM16.775 12.87h-1.35v1.45h1.35v-1.45zM13.375 11.08h-1.35v1.45h1.35v-1.45zM15.075 11.08h-1.35v1.45h1.35v-1.45zM16.775 11.08h-1.35v1.45h1.35v-1.45zM18.475 11.08h-1.35v1.45h1.35v-1.45zM13.375 9.355h-1.35v1.45h1.35v-1.45zM15.075 9.355h-1.35v1.45h1.35v-1.45zM16.775 9.355h-1.35v1.45h1.35v-1.45zM18.475 9.355h-1.35v1.45h1.35v-1.45zM15.075 7.555h-1.35v1.45h1.35v-1.45zM16.775 7.555h-1.35v1.45h1.35v-1.45zM18.475 7.555h-1.35v1.45h1.35v-1.45z" fill="#1976D2"/><path d="M5.625 6.25C4.035 6.25 2.75 7.93 2.75 10c0 2.07 1.285 3.75 2.875 3.75S8.5 12.07 8.5 10c0-2.07-1.285-3.75-2.875-3.75zm-.125 6C4.67 12.25 4 11.245 4 10s.67-2.25 1.5-2.25S7 8.755 7 10s-.67 2.25-1.5 2.25z" fill="#fff"/></svg>
                    </div>
                    <p class="calender-name">
                        <?php echo esc_attr( 'Outlook Calendar', 'eventin' );?>
                    </p>
                </a>
            </li>
        </ul>
        <?php
    }
}