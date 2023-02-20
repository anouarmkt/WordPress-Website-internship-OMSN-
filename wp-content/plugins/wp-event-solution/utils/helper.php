<?php

namespace Etn\Utils;

defined( 'ABSPATH' ) || exit;
/**
 * Global helper class.
 *
 * @since 1.0.0
 */
use DateTime;
class Helper {

    use \Etn\Traits\Singleton;
    private static $settings_key = 'etn_event_options';

    /**
     * Auto generate classname from path.
     */
    public static function make_classname( $dirname ) {
        $dirname    = pathinfo( $dirname, PATHINFO_FILENAME );
        $class_name = explode( '-', $dirname );
        $class_name = array_map( 'ucfirst', $class_name );
        $class_name = implode( '_', $class_name );
        return $class_name;
    }

    /**
     * Renders provided markup
     */
    public static function render( $content ) {
        return $content;
    }

    /**
     * Filters only accepted kses
     */
    public static function kses( $raw ) {
        $allowed_tags = [
            'a'                             => [
                'class'  => [],
                'href'   => [],
                'rel'    => [],
                'title'  => [],
                'target' => [],
            ],
            'input'                         => [
                'value'              => [],
                'type'               => [],
                'size'               => [],
                'name'               => [],
                'checked'            => [],
                'data-value'         => [],
                'data-default-color' => [],
                'placeholder'        => [],
                'id'                 => [],
                'class'              => [],
                'min'                => [],
                'step'               => [],
                'readonly'           => 'readonly',
            ],
            'button'                        => [
                'type'    => [],
                'name'    => [],
                'id'      => [],
                'class'   => [],
                'onclick' => [],
            ],
            'select'                        => [
                'value'       => [],
                'type'        => [],
                'size'        => [],
                'name'        => [],
                'placeholder' => [],
                'id'          => [],
                'class'       => [],
                'option'      => [
                    'value'   => [],
                    'checked' => [],
                ],
            ],
            'textarea'                      => [
                'value'       => [],
                'type'        => [],
                'size'        => [],
                'name'        => [],
                'rows'        => [],
                'cols'        => [],
                'placeholder' => [],
                'id'          => [],
                'class'       => [],
            ],
            'abbr'                          => [
                'title' => [],
            ],
            'b'                             => [],
            'blockquote'                    => [
                'cite' => [],
            ],
            'cite'                          => [
                'title' => [],
            ],
            'code'                          => [],
            'del'                           => [
                'datetime' => [],
                'title'    => [],
            ],
            'dd'                            => [],
            'div'                           => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'dl'                            => [],
            'dt'                            => [],
            'em'                            => [],
            'h1'                            => [
                'class' => [],
            ],
            'h2'                            => [
                'class' => [],
            ],
            'h3'                            => [
                'class' => [],
            ],
            'h4'                            => [
                'class' => [],
            ],
            'h5'                            => [
                'class' => [],
            ],
            'h6'                            => [
                'class' => [],
            ],
            'i'                             => [
                'class' => [],
            ],
            'img'                           => [
                'alt'    => [],
                'class'  => [],
                'height' => [],
                'src'    => [],
                'width'  => [],
            ],
            'li'                            => [
                'class' => [],
            ],
            'ol'                            => [
                'class' => [],
            ],
            'p'                             => [
                'class' => [],
            ],
            'q'                             => [
                'cite'  => [],
                'title' => [],
            ],
            'span'                          => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'iframe'                        => [
                'width'       => [],
                'height'      => [],
                'scrolling'   => [],
                'frameborder' => [],
                'allow'       => [],
                'src'         => [],
            ],
            'strike'                        => [],
            'br'                            => [],
            'strong'                        => [],
            'data-wow-duration'             => [],
            'data-wow-delay'                => [],
            'data-wallpaper-options'        => [],
            'data-stellar-background-ratio' => [],
            'ul'                            => [
                'class' => [],
            ],
            'label'                         => [
                'class'      => [],
                'for'        => [],
                'data-left'  => [],
                'data-right' => [],
            ],
            'form'                          => [
                'class'  => [],
                'id'     => [],
                'role'   => [],
                'action' => [],
                'method' => [],
            ],
        ];

        if ( function_exists( 'wp_kses' ) ) { // WP is here
            return wp_kses( $raw, $allowed_tags );
        } else {
            return $raw;
        }

    }

    /**
     * internal
     *
     * @param [type] $text
     * @return void
     */
    public static function kspan( $text ) {
        return str_replace( ['{', '}'], ['<span>', '</span>'], self::kses( $text ) );
    }

    /**
     * retuns trimmed word
     */
    public static function trim_words( $text, $num_words ) {
        return wp_trim_words( $text, $num_words, '' );
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function img_meta( $id ) {
        $attachment = get_post( $id );

        if ( $attachment == null || $attachment->post_type != 'attachment' ) {
            return null;
        }

        return [
            'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption'     => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href'        => get_permalink( $attachment->ID ),
            'src'         => $attachment->guid,
            'title'       => $attachment->post_title,
        ];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function get_date_formats() {
        return [
            '0'  => 'Y-m-d',
            '1'  => 'n/j/Y',
            '2'  => 'm/d/Y',
            '3'  => 'j/n/Y',
            '4'  => 'd/m/Y',
            '5'  => 'n-j-Y',
            '6'  => 'm-d-Y',
            '7'  => 'j-n-Y',
            '8'  => 'd-m-Y',
            '9'  => 'Y.m.d',
            '10' => 'm.d.Y',
            '11' => 'd.m.Y',
            '11' => 'd M Y',
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $path
     * @return void
     */
    public static function safe_path( $path ) {
        $path = str_replace( ['//', '\\\\'], ['/', '\\'], $path );
        return str_replace( ['/', '\\'], DIRECTORY_SEPARATOR, $path );
    }

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
     * @param  array $array The multi-dimensional array.
     * @return array
     */
    public static function array_flatten( $array ) {

        if ( !is_array( $array ) ) {
            return false;
        }

        $result = [];

        foreach ( $array as $key => $value ) {

            if ( is_array( $value ) ) {
                $result = array_merge( $result, self::array_flatten( $value ) );
            } else {
                $result = array_merge( $result, [$key => $value] );
            }

        }

        return $result;
    }

    /**
     * Post query to get data for widget and shortcode
     */
    public static function post_data_query( $post_type, $count = null, $order = 'DESC', $term_arr = null, $taxonomy_slug = null,
                                               $post__in = null,                                        $post_not_in = null,                                        $tag__in = null,                                        $orderby_meta = null,
                                               $orderby = 'post_date',                                        $filter_with_status = null ) {

        $data = [];
        $args = [
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            'post_parent'      => '0',
            'suppress_filters' => false,
            'tax_query'        => [
                'relation' => 'AND',
            ],
        ];

        if ( $order != null ) {

            if ( $orderby_meta == null ) {
                $args['orderby'] = $orderby;
            } else {
                $args['meta_key'] = $orderby;
                $args['orderby']  = $orderby_meta;
            }

            $args['order'] = strtoupper( $order );
        }

        if ( $post_not_in != null ) {
            $args['post__not_in'] = $post_not_in;
        }

        if ( $count != null ) {
            $args['posts_per_page'] = $count;
        }

        if ( $post__in != null ) {
            $args['post__in'] = $post__in;
        }

// Elementor::If categories selected, add them to tax_query
        if ( is_array( $term_arr ) && !empty( $term_arr ) ) {
            $categories = [
                'taxonomy'         => $taxonomy_slug,
                'terms'            => $term_arr,
                'field'            => 'id',
                'include_children' => true,
                'operator'         => 'IN',
            ];
            array_push( $args['tax_query'], $categories );
        }

// Elementor::If tags selected, add them to tax_query
        if ( !empty( $tag__in ) && is_array( $tag__in ) ) {
            $tags = [
                'taxonomy'         => 'etn_tags',
                'terms'            => $tag__in,
                'field'            => 'id',
                'include_children' => true,
                'operator'         => 'IN',
            ];
            array_push( $args['tax_query'], $tags );
        }

// Elementor::If select upcoming  event , filter out the upcoming events
        if ( $post_type == "etn" ) {

            if ( $filter_with_status == 'upcoming' ) {

                $args['meta_query'] = [
                    [
                        'key'     => 'etn_start_date',
                        'value'   => date( 'Y-m-d' ),
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ],
                ];
            }

            if ( $filter_with_status == 'expire' ) {
                $args['meta_query'] = [
                    'relation' => 'OR',
                    [
                        'key'     => 'etn_end_date',
                        'value'   => date( 'Y-m-d' ),
                        'compare' => '<',
                        'type'    => 'DATE',

                    ],
                    [
                        'key'     => 'etn_end_date',
                        'value'   => date( 'Y-m-d' ),
                        'compare' => '=',
                        'type'    => 'DATE',
                    ],
                ];
            }

        }

        $data = get_posts( $args );

        return $data;
    }

    /**
     * Get zoom meeting data by meeting id
     *
     * @param [type] $meeting_id
     * @return void
     */
    public static function get_zoom_meetings( $meeting_id = null, $return_additional_data = false ) {
        $return_zoom_meetings = [];

        try {
            if ( empty( $meeting_id ) ) {
                $meetings = get_posts( [
                    'post_type'      => 'etn-zoom-meeting',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                ] );

                if ( $return_additional_data ) {
                    foreach ( $meetings as $meeting ) {
                        $post_id = $meeting->ID;
                        $return_zoom_meetings[ $post_id ] = [
                            'zoom_id'   => get_post_meta( $post_id, 'zoom_meeting_id', true ),
                            'zoom_type' => get_post_meta( $post_id, 'zoom_meeting_type', true ),
                        ];
                    }
                } else {
                    foreach ( $meetings as $meeting ) {
                        $return_zoom_meetings[ $meeting->ID ] = $meeting->post_title;
                    }
                }

                return $return_zoom_meetings;
            } else {
                // return single meeting
            }
        } catch ( \Exception $es ) {
            return [];
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function get_settings() {
        return get_option( "etn_event_options" );
    }

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param string $default
     * @return void
     */
    public static function get_option( $key, $default = '' ) {
        $all_settings = get_option( self::$settings_key );
        return ( isset( $all_settings[$key] ) && $all_settings[$key] != '' ) ? $all_settings[$key] : $default;
    }

    /**
     * get single data by meta
     */
    public static function get_single_data_by_meta( $post_type, $limit, $key, $value, $sign = "=" ) {
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => $limit,
            'meta_query'     => [
                [
                    'key'     => $key,
                    'value'   => $value,
                    'compare' => $sign,
                ],
            ],
        ];
        $query_result = get_posts( $args );
        return $query_result;
    }

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param string $value
     * @return void
     */
    public static function update_option( $key, $value = '' ) {
        $all_settings       = get_option( self::$settings_key );
        $all_settings[$key] = $value;
        update_option( self::$settings_key, $all_settings );
        return true;
    }

    /**
     * sanitizes given input
     *
     * @param string $data
     * @return void
     */
    public static function sanitize( string $data ) {
        return strip_tags(
            stripslashes(
                sanitize_text_field(
                    filter_input( INPUT_POST, $data )
                )
            )
        );
    }

    /**
     * returns list of all speaker
     * returns single speaker if speaker id is provuded
     */
    public static function get_speakers( $id = null ) {
        $return_organizers = [];
        try {

            if ( is_null( $id ) ) {
                $args = [
                    'post_type'        => 'etn-speaker',
                    'post_status'      => 'publish',
                    'posts_per_page'   => -1,
                    'suppress_filters' => false,
                ];
                $organizers = get_posts( $args );

                foreach ( $organizers as $value ) {
                    $return_organizers[$value->ID] = $value->post_title;
                }

                return $return_organizers;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * returns category of a speaker
     */
    public static function get_speakers_category( $id = null ) {
        $speaker_category = [];
        try {

            if ( is_null( $id ) ) {
                $terms = get_terms( [
                    'taxonomy'   => 'etn_speaker_category',
                    'hide_empty' => false,
                ] );

                foreach ( $terms as $speakers ) {
                    $speaker_category[$speakers->term_id] = $speakers->name;
                }

                return $speaker_category;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * returns category of an event
     *
     * @param [type] $id
     * @return void
     */
    public static function get_event_category( $id = null ) {
        $event_category = [];
        try {

            if ( is_null( $id ) ) {
                $terms = get_terms( [
                    'taxonomy'   => 'etn_category',
                    'hide_empty' => false,
                ] );

                foreach ( $terms as $event ) {
                    $event_category[$event->term_id] = $event->name;
                }

                return $event_category;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * returns tag of an event
     */
    public static function get_event_tag( $id = null ) {
        $event_tag = [];
        try {

            if ( is_null( $id ) ) {
                $terms = get_terms( [
                    'taxonomy'   => 'etn_tags',
                    'hide_empty' => false,
                ] );

                foreach ( $terms as $event ) {
                    $event_tag[$event->term_id] = $event->name;
                }

                return $event_tag;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function get_schedules( $id = null ) {
        $return_schedules = [];
        try {

            if ( is_null( $id ) ) {
                $args = [
                    'post_type'        => 'etn-schedule',
                    'post_status'      => 'publish',
                    'posts_per_page'   => -1,
                    'suppress_filters' => false,
                ];
                $schedules = get_posts( $args );

                foreach ( $schedules as $value ) {
                    $schedule_date                = get_post_meta( $value->ID, 'etn_schedule_date', true );
                    $return_schedules[$value->ID] = $value->post_title . " ($schedule_date)";
                }

                return $return_schedules;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function get_events( $id = null, $allow_child = false, $return_recurring_only = false ) {
        $return_events = [];
        try {

            if ( is_null( $id ) ) {
                $args = [
                    'post_type'      => 'etn',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                ];

                if ( !$allow_child ) {
                    $args['post_parent'] = 0;
                }

                $events = get_posts( $args );

                foreach ( $events as $value ) {

                    if( $return_recurring_only ){
                        $args = array(
                            'post_parent'   => $value->ID,
                            'post_type'     => 'etn',
                        );

                        $children = get_children( $args );

                        if ( empty($children) ) {
                            continue;
                        } 
                    }

                    $return_events[$value->ID] = $value->post_title;
                }

                return $return_events;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public static function get_users( $id = null ) {
        $return_organizers = ['' => esc_html__( 'select organizer', 'eventin' )];
        try {
            $blogusers = get_users(
                [
                    'order'    => 'DESC',
                    'role__in' => ['etn_organizer', 'administrator'],
                ]
            );

            foreach ( $blogusers as $user ) {
                $name                         = isset( $user->display_name ) ? $user->display_name : $user->user_nicename;
                $return_organizers[$user->ID] = $name . ' - ' . $user->user_email;
            }

            return $return_organizers;
        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * Undocumented function
     *
     * @param string $prefix
     * @return void
     */
    public static function etn_event_manager_fontawesome_icons( $prefix = 'fab' ) {
        $prefix       = apply_filters( 'etn_event_social_icons_prefix', $prefix );
        $social_icons = [
            "$prefix fa-facebook"           => esc_html__( 'facebook', 'eventin' ),
            "$prefix fa-facebook-f"         => esc_html__( 'facebook-f', 'eventin' ),
            "$prefix fa-facebook-messenger" => esc_html__( 'facebook-messenger', 'eventin' ),
            "$prefix fa-facebook-square"    => esc_html__( 'facebook-square', 'eventin' ),
            "$prefix fa-linkedin"           => esc_html__( 'linkedin', 'eventin' ),
            "$prefix fa-linkedin-in"        => esc_html__( 'linkedin-in', 'eventin' ),
            "$prefix fa-twitter"            => esc_html__( 'twitter', 'eventin' ),
            "$prefix fa-twitter-square"     => esc_html__( 'twitter-square', 'eventin' ),
            "$prefix fa-uber"               => esc_html__( 'uber', 'eventin' ),
            "$prefix fa-google"             => esc_html__( 'google', 'eventin' ),
            "$prefix fa-google-drive"       => esc_html__( 'google-drive', 'eventin' ),
            "$prefix fa-google-play"        => esc_html__( 'google-play', 'eventin' ),
            "$prefix fa-google-wallet"      => esc_html__( 'google-wallet', 'eventin' ),
            "$prefix fa-linkedin"           => esc_html__( 'linkedin', 'eventin' ),
            "$prefix fa-linkedin-in"        => esc_html__( 'linkedin-in', 'eventin' ),
            "$prefix fa-whatsapp"           => esc_html__( 'whatsapp', 'eventin' ),
            "$prefix fa-whatsapp-square"    => esc_html__( 'whatsapp-square', 'eventin' ),
            "$prefix fa-wordpress"          => esc_html__( 'wordpress', 'eventin' ),
            "$prefix fa-wordpress-simple"   => esc_html__( 'wordpress-simple', 'eventin' ),
            "$prefix fa-youtube"            => esc_html__( 'youtube', 'eventin' ),
            "$prefix fa-youtube-square"     => esc_html__( 'youtube-square', 'eventin' ),
            "$prefix fa-xbox"               => esc_html__( 'xbox', 'eventin' ),
            "$prefix fa-vk"                 => esc_html__( 'vk', 'eventin' ),
            "$prefix fa-vnv"                => esc_html__( 'vnv', 'eventin' ),
            "$prefix fa-instagram"          => esc_html__( 'instagram', 'eventin' ),
            "$prefix fa-reddit"             => esc_html__( 'reddit', 'eventin' ),
            "$prefix fa-reddit-alien"       => esc_html__( 'reddit-alien', 'eventin' ),
            "$prefix fa-reddit-square"      => esc_html__( 'reddit-square', 'eventin' ),
            "$prefix fa-pinterest"          => esc_html__( 'pinterest', 'eventin' ),
            "$prefix fa-pinterest-p"        => esc_html__( 'pinterest-p', 'eventin' ),
            "$prefix fa-pinterest-square"   => esc_html__( 'pinterest-square', 'eventin' ),
            "$prefix fa-tumblr"             => esc_html__( 'tumblr', 'eventin' ),
            "$prefix fa-tumblr-square"      => esc_html__( 'tumblr-square', 'eventin' ),
            "$prefix fa-flickr"             => esc_html__( 'flickr', 'eventin' ),
            "$prefix fa-meetup"             => esc_html__( 'meetup', 'eventin' ),
            "$prefix fa-share"              => esc_html__( 'share', 'eventin' ),
            "$prefix fa-vimeo-v"            => esc_html__( 'vimeo', 'eventin' ),
            "$prefix fa-weixin"             => esc_html__( 'Wechat', 'eventin' ),
        ];

        return apply_filters( 'etn_social_icons', $social_icons );
    }

    /**
     * returns all organizers list
     */
    public static function get_orgs() {
        $return_organizers = [];
        try {
            $terms = get_terms( [
                'taxonomy'   => 'etn_speaker_category',
                'orderby'    => 'count',
                'hide_empty' => false,
                'fields'     => 'all',
            ] );

            foreach ( $terms as $term ) {
                $return_organizers[$term->slug] = $term->name;
            }

            return $return_organizers;
        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * returns all categories of an event
     */
    public static function cate_with_link( $post_id = null, $category = '', $single = false ) {
        $terms         = get_the_terms( $post_id, $category );
        $category_name = '';

        if ( is_array( $terms ) ):

            foreach ( $terms as $tkey => $term ):
                $cat = $term->name;

                $category_name .= sprintf( "<span>%s</span> ", $cat );

                if ( $single ) {
                    break;
                }

                if ( $tkey == 1 ) {
                    break;
                }

            endforeach;
        endif;
        return $category_name;
    }

    /**
     * validation for nonce
     */
    public static function is_secured( $nonce_field, $action, $post_id = null, $post = [] ) {

        $nonce = !empty( $post[$nonce_field] ) ? sanitize_text_field( $post[$nonce_field] ) : '';

        if ( $nonce == '' ) {
            return false;
        }

        if ( null !== $post_id ) {

            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return false;
            }

            if ( wp_is_post_autosave( $post_id ) ) {
                return false;
            }

            if ( wp_is_post_revision( $post_id ) ) {
                return false;
            }

        }

        if ( !wp_verify_nonce( $nonce, $action ) ) {
            return false;
        }

        return true;
    }

    /**
     * Single page settings option
     */
    public static function single_template_options( $single_event_id ) {
        $data                     = [];
        $date_options             = Helper::get_date_formats();
        $text_domain              = 'eventin';
        $etn_start_date           = strtotime( get_post_meta( $single_event_id, 'etn_start_date', true ) );
        $etn_start_time           = strtotime( get_post_meta( $single_event_id, 'etn_start_time', true ) );
        $etn_end_date             = strtotime( get_post_meta( $single_event_id, 'etn_end_date', true ) );
        $etn_end_time             = strtotime( get_post_meta( $single_event_id, 'etn_end_time', true ) );
        $etn_event_location       = get_post_meta( $single_event_id, 'etn_event_location', true );
        $event_timezone           = get_post_meta( $single_event_id, 'event_timezone', true );
        $etn_event_tags           = get_post_meta( $single_event_id, 'etn_event_tags', true );
        $etn_event_description    = get_post_meta( $single_event_id, 'etn_event_description', true );
        $etn_event_schedule       = get_post_meta( $single_event_id, 'etn_event_schedule', true );
        $etn_online_event         = get_post_meta( $single_event_id, 'etn_online_event', true );
        $etn_es_event_feature     = get_post_meta( $single_event_id, 'etn_es_event_feature', true );
        $etn_event_banner         = get_post_meta( $single_event_id, 'etn_event_banner', true );
        $etn_event_banner_url     = wp_get_attachment_image_src( $etn_event_banner );
        $etn_organizer_banner     = get_post_meta( $single_event_id, 'etn_organizer_banner', true );
        $etn_organizer_banner_url = wp_get_attachment_image_src( $etn_organizer_banner );
        $etn_event_socials        = get_post_meta( $single_event_id, 'etn_event_socials', true );
        $etn_event_page           = get_post_meta( $single_event_id, 'etn_event_page', true );
        $etn_organizer_events     = get_post_meta( $single_event_id, 'etn_event_organizer', true );
        $etn_avaiilable_tickets   = get_post_meta( $single_event_id, 'etn_avaiilable_tickets', true );
        $etn_avaiilable_tickets   = isset( $etn_avaiilable_tickets ) ? ( intval( $etn_avaiilable_tickets ) ) : 0;
        $etn_ticket_unlimited     = get_post_meta( $single_event_id, 'etn_ticket_availability', true );

        $cart_product_id = get_post_meta( $single_event_id, 'link_wc_product', true ) ? esc_attr( get_post_meta( $single_event_id, 'link_wc_product', true ) ) : esc_attr( $single_event_id );

        $etn_sold_tickets = get_post_meta( $single_event_id, 'etn_sold_tickets', true );

        if ( !$etn_sold_tickets ) {
            $etn_sold_tickets = 0;
        }

        $etn_ticket_price  = get_post_meta( $single_event_id, 'etn_ticket_price', true );
        $etn_ticket_price  = isset( $etn_ticket_price ) ? ( floatval( $etn_ticket_price ) ) : 0;
        $etn_left_tickets  = $etn_avaiilable_tickets - $etn_sold_tickets;
        $event_options     = get_option( "etn_event_options" );
        $event_time_format = empty( $event_options["time_format"] ) ? '12' : $event_options["time_format"];
        $event_start_time  = empty( $etn_start_time ) ? '' : (  ( $event_time_format == "24" ) ? date_i18n( 'H:i', $etn_start_time ) : date_i18n( 'h:i a', $etn_start_time ) );
        $event_end_time    = empty( $etn_end_time ) ? '' : (  ( $event_time_format == "24" ) ? date_i18n( 'H:i', $etn_end_time ) : date_i18n( 'h:i a', $etn_end_time ) );
        $event_start_date  = ( isset( $event_options["date_format"] ) && $event_options["date_format"] !== '' ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_start_date ) : date_i18n( get_option( 'date_format' ), $etn_start_date );
        $event_end_date    = '';

        if ( $etn_end_date ) {
            $event_end_date = isset( $event_options["date_format"] ) && ( "" != $event_options["date_format"] ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_end_date ) : date_i18n( get_option( 'date_format' ), $etn_end_date );
        }

        $etn_deadline       = !empty(get_post_meta( $single_event_id, 'etn_registration_deadline', true )) ? strtotime( get_post_meta( $single_event_id, 'etn_registration_deadline', true ) ) : '';
        $etn_deadline_value = '';
        
        if ( $etn_deadline ) {
            $etn_deadline_value = isset( $event_options["date_format"] ) && $event_options["date_format"] !== '' ? date_i18n( $date_options[$event_options["date_format"]], $etn_deadline ) : date_i18n( get_option( 'date_format' ), $etn_deadline );
        }

        $category = self::cate_with_link( $single_event_id, 'etn_category' );

        $data['category']             = $category;
        $data['etn_event_schedule']   = $etn_event_schedule;
        $data['event_options']        = $event_options;
        $data['text_domain']          = $text_domain;
        $data['event_start_date']     = $event_start_date;
        $data['event_end_date']       = $event_end_date;
        $data['event_start_time']     = $event_start_time;
        $data['event_end_time']       = $event_end_time;
        $data['etn_deadline_value']   = $etn_deadline_value;
        $data['etn_event_location']   = $etn_event_location;
        $data['etn_left_tickets']     = $etn_left_tickets;
        $data['etn_organizer_events'] = $etn_organizer_events;
        $data['date_options']         = $date_options;
        $data['etn_event_socials']    = $etn_event_socials;
        $data['etn_ticket_price']     = $etn_ticket_price;
        $data['etn_ticket_unlimited'] = $etn_ticket_unlimited;
        $data['event_timezone']       = $event_timezone;
        return $data;
    }

    /**
     * Single page organizer
     */
    public static function single_template_organizer_free( $etn_organizer_events ) {

        if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-organizers-free.php' ) ) {
            require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-organizers-free.php';
        } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-organizers-free.php' ) ) {
            require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-organizers-free.php';
        } else {
            require_once \Wpeventin::templates_dir() . 'event/event-organizers-free.php';
        }

    }

    /**
     * Speaker sessions in single page
     */
    public static function speaker_sessions( $speaker_id ) {
        global $wpdb;
        $orgs = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = 'etn_schedule_topics' AND $wpdb->postmeta.meta_value LIKE '%\"$speaker_id\"%' ORDER BY post_id DESC", ARRAY_A );

        return $orgs;
    }

    /**
     * Remove attendee data when status failed
     */
    public static function remove_attendee_data() {
        global $wpdb;
        $query = $wpdb->query(
            "UPDATE $wpdb->posts posts
            INNER JOIN $wpdb->postmeta postmeta
            ON posts.ID = postmeta.post_id
            SET posts.post_status = 'etn-trashed-attendee'
            WHERE postmeta.meta_key = 'etn_status'
            AND postmeta.meta_value = 'failed'
            AND posts.post_type = 'etn-attendee'"
        );

        return $query;
    }

    /**
     * get  corn schedule days
     */
    public static function get_schedule_days() {
        // attendee_remove
        $event_options   = get_option( "etn_event_options" );
        $attendee_remove = isset( $event_options['attendee_remove'] ) && $event_options['attendee_remove'] !== "" ? $event_options['attendee_remove'] : 30;

        return 60 * 60 * 24 * $attendee_remove;
    }

    /**
     * Send email function
     */
    public static function send_email( $to, $subject, $mail_body, $from, $from_name ) {
        $body    = html_entity_decode( $mail_body );
        $headers = ['Content-Type: text/html; charset=UTF-8', 'From: ' . $from_name . ' <' . $from . '>'];
        $result  = wp_mail( $to, $subject, $body, $headers );

        return $result;
    }

    /**
     * Get all sales history of event
     */
    public static function get_tickets_by_event( $current_post_id, $report_sorting ) {
        global $wpdb;
        $response_data = [];
        $data          = [];

        $table_etn_events = ETN_EVENT_PURCHASE_HISTORY_TABLE;
        $data             = $wpdb->get_results( "SELECT * FROM $table_etn_events WHERE post_id = $current_post_id ORDER BY event_id $report_sorting" );

        if ( is_array( $data ) && count( $data ) > 0 ) {
            $total_sale_price = 0;

            $trans_history_meta_table = ETN_EVENT_PURCHASE_HISTORY_META_TABLE;

            foreach ( $data as &$single_sale ) {
                $total_sale_price += $single_sale->event_amount;
                $single_sale_meta = $wpdb->get_results( "SELECT * FROM $trans_history_meta_table WHERE event_id = $single_sale->event_id AND meta_key = '_etn_order_qty'" );
                $single_sale->{'single_sale_meta'}

                = $single_sale_meta[0]->meta_value;
            }

        }

        $response_data['all_sales']        = $data;
        $response_data['total_sale_price'] = isset( $total_sale_price ) ? $total_sale_price : 0;

        return $response_data;
    }

    /**
     * module for related events
     *
     * @param [type] $single_event_id
     * @return void
     */
    public static function related_events_widget( $single_event_id, $configs = [] ) {

        $etn_terms    = wp_get_post_terms( $single_event_id, 'etn_tags' );
        $etn_term_ids = [];

        if ( $etn_terms ) {

            foreach ( $etn_terms as $terms ) {
                array_push( $etn_term_ids, $terms->term_id );
            }

        }

        $event_options = get_option( "etn_event_options" );
        $date_options  = self::get_date_formats();
        $data          = self::post_data_query( 'etn', null, null, $etn_term_ids, "etn_tags", null, [$single_event_id], null, null, 'post_date', 'upcoming' );

        $column = "4";

        if ( !empty( $configs ) && !empty( $configs["column"] ) ) {
            $column = $configs["column"];
        }

        $title = ( is_array( $configs ) && !empty( $configs["title"] ) ) ? $configs["title"] : esc_html__( 'Related Events', 'eventin' );

        if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/related-events-free.php' ) ) {
            $template = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/related-events-free.php';
        } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/related-events-free.php' ) ) {
            $template = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/related-events-free.php';
        } elseif ( file_exists( \Wpeventin::templates_dir() . 'event/related-events-free.php' ) ) {
            $template = \Wpeventin::templates_dir() . 'event/related-events-free.php';
        }

        include $template;

    }

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public static function get_attendee_by_token( $key, $value ) {
        global $wpdb;
        $query_result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key='$key' AND meta_value='$value'" );
        return $query_result;
    }

    /**
     * Sorting Schedule repeater data
     */
    public static function sort_schedule_items( $post_id, $etn_rep_key ) {
        $new_order = sanitize_text_field( stripslashes( $_POST['etn_schedule_sorting'] ) );
        $order     = json_decode( $new_order, true );
        $order     = array_values( $order );

        if ( is_array( $order ) && !empty( $order ) ) {
            $schedules = $etn_rep_key;
            $new_arr   = [];
            $sort_arr  = [];

            foreach ( $order as $key => $value ) {
                $new_arr[$key]  = $schedules[$value];
                $sort_arr[$key] = $key;
            }

            $new_sort = json_encode( $sort_arr );
            update_post_meta( $post_id, 'etn_schedule_topics', $new_arr );
            update_post_meta( $post_id, 'etn_schedule_sorting', $new_sort );
        }

    }

    public static function generate_name_from_label( $prefix, $label ) {
        return $prefix . self::get_name_structure_from_label( $label );
    }

    public static function get_name_structure_from_label( $label ) {
        return strtolower( preg_replace( "/[^a-zA-Z0-9]/", "_", $label ) );
    }

    /**
     * Undocumented function
     *
     * @param [type] $default_template_name
     * @param [type] $template_name
     * @return void
     */
    public static function prepare_event_template_path( $default_template_name, $template_name ) {

        if ( "event-one" !== $template_name && class_exists( 'Etn_Pro\Bootstrap' ) ) {
            $single_template_path = \Wpeventin_Pro::templates_dir() . $template_name . ".php";
        } else {
            $single_template_path = \Wpeventin::templates_dir() . $default_template_name . ".php";
        }

        $single_template_path = apply_filters( "etn_event_content_template_path", $single_template_path );

        return $single_template_path;
    }

    /**
     * Undocumented function
     *
     * @param [type] $default_template_name
     * @param [type] $template_name
     * @return void
     */
    public static function prepare_speaker_template_path( $default_template_name, $template_name ) {
        $arr = [
            'speaker-one',
            'speaker-two-lite',
        ];

        if ( !in_array( $template_name, $arr ) && class_exists( 'Etn_Pro\Bootstrap' ) ) {
            $single_template_path = \Wpeventin_Pro::templates_dir() . $template_name . ".php";
        } else {
            $single_template_path = \Wpeventin::templates_dir() . $template_name . ".php";
        }

        $single_template_path = apply_filters( "etn_speaker_content_template_path", $single_template_path );

        return $single_template_path;
    }

    public static function get_attendee_by_woo_order( $order_id ) {
        $all_attendee = [];
        global $wpdb;
        $table_name = $wpdb->prefix . "postmeta";
        $sql        = "SELECT post_id FROM $table_name WHERE meta_key='etn_attendee_order_id' AND meta_value=$order_id";
        $results    = $wpdb->get_results( $sql );

        if ( is_array( $results ) && !empty( $results ) ) {

            foreach ( $results as $result ) {
                array_push( $all_attendee, $result->post_id );
            }

        }

        return $all_attendee;
    }

    public static function update_attendee_payment_status( $attendee_id, $order_status ) {
        $payment_success_status_array = [
            // 'pending',
            'processing',
            // 'on-hold',
            'completed',
            // 'cancelled',
            'refunded',
            // 'failed',
            'partial-payment',
            'stripe-pending',
        ];

        if ( in_array( $order_status, $payment_success_status_array ) ) {
            //payment complete, update payment status to success
            update_post_meta( $attendee_id, 'etn_status', 'success' );
        } else {
            //payment failed, update payment status to falied
            update_post_meta( $attendee_id, 'etn_status', 'failed' );
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $attendee_id
     * @param [type] $check_info_edit_token
     * @return void
     */
    public static function verify_attendee_edit_token( $attendee_id, $check_info_edit_token ) {

        if ( empty( $attendee_id ) || empty( $check_info_edit_token ) ) {
            return false;
        }

        $stored_edit_token = get_post_meta( $attendee_id, "etn_info_edit_token", true );

        if ( $stored_edit_token == $check_info_edit_token ) {
            return true;
        }

        return false;

    }

    /**
     * Show Invalid Data Page
     *
     * @return html
     */
    public static function show_attendee_pdf_invalid_data_page() {
        wp_head();
        ?>
        <div class="section-inner">
            <h3 class="entry-title">
                <?php echo esc_html__( "Invalid data. ", "eventin" ); ?>
            </h3>
            <div class="intro-text">
                <a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html__( "Return to homepage", "eventin" ); ?></a>
            </div>
        </div>
        <?php
        wp_footer();
    }

    /************************
     *advanced search
    *******************************/

    // get event data
    public static function get_eventin_search_data( $posts_per_page = -1 ) {
        $etn_event_location = "";

        if ( isset( $_GET['etn_event_location'] ) ) {
            $etn_event_location = $_GET['etn_event_location'];
        }

        $event_cat = "";

        if ( isset( $_GET['etn_categorys'] ) ) {
            $event_cat = $_GET['etn_categorys'];
        }

        $keyword = "";

        if ( isset( $_GET['s'] ) ) {
            $keyword = $_GET['s'];
        }

        $data_query_args = [
            'post_type'      => 'etn',
            'post_status'    => 'publish',
            's'              => $keyword,
            'posts_per_page' => isset( $posts_per_page ) ? $posts_per_page : -1,
        ];

        if ( !empty( $event_cat ) ) {
            $data_query_args['tax_query'] = [
                [
                    'taxonomy'         => 'etn_category',
                    'terms'            => [$event_cat],
                    'field'            => 'id',
                    'include_children' => true,
                    'operator'         => 'IN',
                ],
            ];
        }

        if ( !empty( $etn_event_location ) ) {
            $data_query_args['meta_query'] = [
                [
                    'key'     => 'etn_event_location',
                    'value'   => $etn_event_location,
                    'compare' => 'LIKE',
                ],
            ];
        }

        $query_data = get_posts( $data_query_args );
        return $query_data;
    }

    // get event location
    public static function get_event_location() {
        $location_args = [
            'post_type'   => ['etn'],
            'numberposts' => -1,
            'meta_query'  => [
                [
                    'key'     => 'etn_event_location',
                    'compare' => 'EXISTS',
                ],
                [
                    'key'     => 'etn_event_location',
                    'value'   => [''],
                    'compare' => 'NOT IN',
                ],
            ],
        ];
        $location_query_data = get_posts( $location_args );
        $location_data[]     = esc_html__( "Select Location", 'eventin' );

        if ( !empty( $location_query_data ) ) {

            foreach ( $location_query_data as $value ) {
                $location_data[get_post_meta( $value->ID, 'etn_event_location', true )] = get_post_meta( $value->ID, 'etn_event_location', true );
            }

        }

        return $location_data;
    }

    // get event search form
    public static function get_event_search_form( $etn_event_input_filed_title = "Find your next event", $etn_event_category_filed_title = "Event Category", $etn_event_location_filed_title = "Event Location", $etn_event_button_title = "Search Now" ) {

        $category_data = Helper::get_event_category();
        $location_data = [];
        $location_data = self::get_event_location();

        ?>
		<form method="GET" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="etn_event_inline_form">
			<div class="etn-event-search-wrapper etn-row">
				<div class="input-group etn-col-lg-3">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<i class="fas fa-search"></i>
						</span>
					</div>
					<input type="search" name="s" value="<?php echo get_search_query() ?>" placeholder="<?php echo esc_html__( $etn_event_input_filed_title, 'eventin' ) ?>" class="form-control">
				</div>
				<!-- // Search input filed -->
				<div class="input-group etn-col-lg-3">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<i class="fas fa-map-marker-alt"></i>
						</span>
					</div>
					<select name="etn_event_location" class="etn_event_select2 etn_event_select">
						<option value><?php echo esc_html__( $etn_event_location_filed_title, 'eventin' ) ?></option>
						<?php

                        if ( is_array( $location_data ) && !empty( $location_data ) ) {
                            $modify_array_data = array_shift( $location_data );

                            foreach ( $location_data as $value ) {
                                $select_value = "";

                                if ( isset( $_GET['etn_event_location'] ) ) {
                                    $select_value = $_GET['etn_event_location'];
                                }

                                ?>
                                            <option <?php

                                if ( !empty( $select_value ) && $select_value === $value ) {
                                    echo ' selected="selected"';
                                }

                                ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $value ); ?></option>
                                                <?php
                            }

                        }

                        ?>
					</select>
				</div>
				<!-- // location -->
				<div class="input-group etn-col-lg-3">
					<div class="input-group-prepend">
						<span class="input-group-text">
						<i class="fab fa-buffer"></i>
						</span>
					</div>
					<select name="etn_categorys" class="etn_event_select2 etn_event_select">
						<option value><?php echo esc_html__( $etn_event_category_filed_title, 'eventin' ) ?></option>
						<?php

                        if ( !empty( $category_data ) && is_array( $category_data ) ) {
                            $select_cat_value = '';

                            if ( isset( $_GET['etn_categorys'] ) ) {
                                $select_cat_value = $_GET['etn_categorys'];
                            }

                            foreach ( $category_data as $key => $value ) {
                                ?>
                                <option 
                                <?php

                                if ( !empty( $select_cat_value ) && $select_cat_value == $key ) {
                                    echo ' selected="selected"';
                                }

                                ?> 
                                value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                                <?php 
                            }

                        }

                        ?>
					</select>
				</div>

				<!-- // cat -->
                <div class="etn-col-lg-3 etn-text-right">
                    <input type="hidden" name="post_type" value="etn" />
                    <?php

                    if ( defined( 'ETN_PRO_FILES_LOADED' ) ) {
                        ?>
                        <button type="button" class="etn-btn etn-filter-icon"><i class="fas fa-sliders-h"></i></button>
                        <?php 
                    }

                    ?>
                    <button type="submit" class="etn-btn etn-btn-primary"><?php echo esc_html__( $etn_event_button_title, 'eventin' ) ?> </button>

                </div>
		    </div>
           <?php do_action( 'etn_advanced_search' );?>

		</form>
		<?php
    }

    // event normal search filter
    public static function event_etn_search_filter( $query ) {

        if (  ( isset( $_GET['post_type'] ) && $_GET['post_type'] === "etn" ) && $query->is_search && !is_admin() ) {

            $prev_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' -1 day' ) );
            $next_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' +1 day' ) );

            $week_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' +7 day' ) );

            $week_start = strtotime( "last monday" );
            $week_start = date( 'w', $week_start ) == date( 'w' ) ? $week_start + 7 * 86400 : $week_start;
            $weekend    = date( 'Y-m-d', strtotime( date( "Y-m-d", $week_start ) . " +6 days" ) );

            $month_start_date = date( 'Y-m-d', strtotime( date( 'Y-m' ) ) );
            $month_end_date   = date( 'Y-m-d', strtotime( date( "Y-m-t", strtotime( $month_start_date ) ) ) );

            $etn_event_location = "";

            if ( isset( $_GET['etn_event_location'] ) ) {
                $etn_event_location = $_GET['etn_event_location'];
            }

            $etn_event_date_range = "";

            if ( isset( $_GET['etn_event_date_range'] ) ) {
                $etn_event_date_range = $_GET['etn_event_date_range'];
            }

            $event_cat = "";

            if ( isset( $_GET['etn_categorys'] ) ) {
                $event_cat = $_GET['etn_categorys'];
            }

            $etn_event_will_happen = "";

            if ( isset( $_GET['etn_event_will_happen'] ) ) {
                $etn_event_will_happen = $_GET['etn_event_will_happen'];
            }

            $keyword = "";

            if ( isset( $_GET['s'] ) ) {
                $keyword = $_GET['s'];
            }

            $meta_location_query = [];

            if ( !empty( $etn_event_location ) ) {
                $meta_location_query = [
                    [
                        'key'     => 'etn_event_location',
                        'value'   => $etn_event_location,
                        'compare' => "EXSISTS",
                    ],
                ];
            }

            $meta_date_query = [];

            if ( !empty( $etn_event_date_range ) ) {

                if ( $etn_event_date_range === "today" ) {
                    $meta_date_query = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "tomorrow" ) {
                    $meta_date_query = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $next_date,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $next_date,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "yesterday" ) {
                    $meta_date_query = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $prev_date,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $prev_date,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-weekend" ) {
                    $meta_date_query = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $weekend,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $weekend,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-week" ) {
                    $meta_date_query = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_start_date',
                            'value'   => [$week_start, $weekend],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => [$week_start, $weekend],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-month" ) {
                    $meta_date_query = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_start_date',
                            'value'   => [$month_start_date, $month_end_date],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => [$month_start_date, $month_end_date],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "upcoming" ) {
                    $meta_date_query = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '>',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => '',
                            'compare' => '=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "expired" ) {
                    $meta_date_query = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '<',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => '',
                            'compare' => '=',
                        ],
                    ];
                }

            }

            $meta_event_happen_query = [];

            if ( !empty( $etn_event_will_happen ) ) {
                $meta_event_happen_query = [
                    [
                        'key'     => 'etn_zoom_event',
                        'value'   => $etn_event_will_happen,
                        'compare' => "EXSISTS",
                    ],
                ];
            }

            $meta_query = [
                'relation' => 'AND',
                [
                    $meta_location_query,
                    $meta_date_query,
                    $meta_event_happen_query,
                ],
            ];
            $query->set( 'meta_query', $meta_query );

            if ( !empty( $keyword ) ) {
                $query->set( 's', $keyword );
            }

            if ( !empty( $event_cat ) ) {
                $taxquery = [
                    [
                        'taxonomy' => 'etn_category',
                        'terms'    => [$event_cat],
                        'field'    => 'id',
                    ],
                ];
                $query->set( 'tax_query', $taxquery );
            }

            $query->set( 'post_type', ['etn'] );

            // Archive page event sort by event start date
            if ( is_archive() ) {
                $query->set( 'order', 'DESC' );
                $query->set( 'meta_key', 'etn_start_date' );
                $query->set( 'orderby', 'meta_value' );
            }

        }

        return $query;
    }

    // ajax event filter in event archive
    public static function etn_event_ajax_get_data() {
        
        $post_arr  = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        $prev_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' -1 day' ) );
        $next_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' +1 day' ) );

        $week_date = date( 'Y-m-d', strtotime( date( 'Y-m-d' ) . ' +7 day' ) );

        $week_start = strtotime( "last monday" );
        $week_start = date( 'w', $week_start ) == date( 'w' ) ? $week_start + 7 * 86400 : $week_start;
        $weekend    = date( 'Y-m-d', strtotime( date( "Y-m-d", $week_start ) . " +6 days" ) );

        $month_start_date = date( 'Y-m-d', strtotime( date( 'Y-m' ) ) );
        $month_end_date   = date( 'Y-m-d', strtotime( date( "Y-m-t", strtotime( $month_start_date ) ) ) );

        $keyword = "";

        if ( isset( $post_arr['s'] ) ) {
            $keyword = $post_arr['s'];
        }

        $event_cat = "";

        if ( isset( $post_arr['etn_categorys'] ) ) {
            $event_cat = $post_arr['etn_categorys'];
        }

        $etn_event_location = "";

        if ( isset( $post_arr['etn_event_location'] ) ) {
            $etn_event_location = $post_arr['etn_event_location'];
        }

        $etn_event_date_range = "";

        if ( isset( $post_arr['etn_event_date_range'] ) ) {
            $etn_event_date_range = $post_arr['etn_event_date_range'];
        }

        $etn_event_will_happen = "";

        if ( isset( $post_arr['etn_event_will_happen'] ) ) {
            $etn_event_will_happen = $post_arr['etn_event_will_happen'];
        }

        if ( isset( $post_arr['etn_event_location'] ) || isset( $post_arr['etn_categorys'] ) || isset( $post_arr['s'] ) || isset( $post_arr['etn_event_date_range'] ) || isset( $post_arr['etn_event_will_happen'] ) ) {
            $query_string = [
                'post_type'   => 'etn',
                'post_status' => 'publish',
            ];

            if ( isset( $post_arr['type'] ) ) {
                $id                           = $post_arr['id'];
                $query_string['post__not_in'] = explode( ',', $id );
            }

            if ( !empty( $keyword ) ) {
                $query_string['s'] = $keyword;
            }

            if ( !empty( $event_cat ) ) {
                $query_string['tax_query'] = [
                    [
                        'taxonomy' => 'etn_category',
                        'terms'    => [$event_cat],
                        'field'    => 'id',
                    ],
                ];
            }

            $meta_location_query_string = [];

            if ( !empty( $etn_event_location ) ) {
                $meta_location_query_string = [
                    'key'     => 'etn_event_location',
                    'value'   => $etn_event_location,
                    'compare' => 'EXSISTS',
                ];
            }

            $meta_event_happen_query = [];

            if ( !empty( $etn_event_will_happen ) ) {
                $meta_event_happen_query = [
                    'key'     => 'etn_zoom_event',
                    'value'   => $etn_event_will_happen,
                    'compare' => 'LIKE',
                ];
            }

            $meta_event_date_query_string = [];

            if ( !empty( $etn_event_date_range ) ) {

                if ( $etn_event_date_range === "today" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "tomorrow" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $next_date,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $next_date,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "yesterday" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $prev_date,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $prev_date,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-weekend" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'AND',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => $weekend,
                            'compare' => '>=',
                        ],
                        [
                            'key'     => 'etn_start_date',
                            'value'   => $weekend,
                            'compare' => '<=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-week" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_start_date',
                            'value'   => [$week_start, $weekend],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => [$week_start, $weekend],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "this-month" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_start_date',
                            'value'   => [$month_start_date, $month_end_date],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => [$month_start_date, $month_end_date],
                            'type'    => 'date',
                            'compare' => 'BETWEEN',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "upcoming" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '>',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => '',
                            'compare' => '=',
                        ],
                    ];
                } elseif ( $etn_event_date_range === "expired" ) {
                    $meta_event_date_query_string = [
                        'relation' => 'OR',
                        [
                            'key'     => 'etn_end_date',
                            'value'   => date( 'Y-m-d' ),
                            'compare' => '<',
                        ],
                        [
                            'key'     => 'etn_end_date',
                            'value'   => '',
                            'compare' => '=',
                        ],
                    ];
                }

            }

            $query_string['meta_query'] = [
                'relation' => 'AND',
                [
                    $meta_location_query_string,
                    $meta_event_date_query_string,
                    $meta_event_happen_query,
                ],
            ];
            $search  = new \WP_Query( $query_string );
            $newdata = '';
            $ids     = [];

            if ( $search->have_posts() ) {

                while ( $search->have_posts() ) {
                    $search->the_post();
                    ?>
                     <div class="etn-col-md-6 etn-col-lg-<?php echo esc_attr( apply_filters( 'etn_event_archive_column', '4' ) ); ?>">

                            <div class="etn-event-item">

                                <?php do_action( 'etn_before_event_archive_content', get_the_ID() );?>

                                <!-- content start-->
                                <div class="etn-event-content">

                                    <?php do_action( 'etn_before_event_archive_title', get_the_ID() );?>

                                    <h3 class="etn-title etn-event-title">
                                        <a href="<?php echo esc_url( get_the_permalink() ) ?>">
                                            <?php echo esc_html( get_the_title() ); ?>
                                        </a>
                                    </h3>

                                    <?php do_action( 'etn_after_event_archive_title', get_the_ID() );?>
                                </div>
                                <!-- content end-->

                                <?php do_action( 'etn_after_event_archive_content', get_the_ID() );?>

                            </div>
                        <!-- etn event item end-->
                        </div>

                    <?php
                }

            }

            wp_reset_postdata();

        }

        wp_die();
    }

    /**
     * Check If Zoom Event
     *
     * @since 2.4.1
     *
     * @return bool
     *
     * check if a provided event id is zoom event
     */
    public static function check_if_zoom_event( $event_id ) {
        $is_zoom_event   = get_post_meta( $event_id, 'etn_zoom_event', true );
        $zoom_meeting_id = get_post_meta( $event_id, 'etn_zoom_id', true );

        if ( isset( $is_zoom_event ) && "on" == $is_zoom_event && !empty( $zoom_meeting_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check If Zoom Details Email Sent Already
     *
     * @param [type] $order_id
     *
     * @since 2.4.1
     *
     * @return bool
     */
    public static function check_if_zoom_email_sent_for_order( $order_id ) {

        $is_email_sent = ( !empty( get_post_meta( $order_id, 'etn_zoom_email_sent', true ) ) && 'yes' === get_post_meta( $order_id, 'etn_zoom_email_sent', true ) ) ? true : false;
        return $is_email_sent;
    }

    /**
     * Send Email With Zoom Details
     *
     * @param [type] $order_id
     * @param [type] $order
     *
     * @since 2.4.1
     *
     * @return void
     */
    public static function send_email_with_zoom_meeting_details( $order_id, $order, $report_event_id = null, $gateway = 'woocommerce' ) {

        $zoom_event_count  = 0;
        $mail_body_content = '';

        if ( $gateway == 'woocommerce' ) {
            foreach ( $order->get_items() as $item_id => $item ) {
                // Get the product name
                $product_name = $item->get_name();
                $event_id     = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
    
                if ( !empty( $event_id ) ) {
                    $product_post = get_post( $event_id );
                } else {
                    $product_post = get_page_by_title( $product_name, OBJECT, 'etn' );
                }
    
                if (  ( empty( $report_event_id ) && !empty( $product_post ) ) || ( !empty( $product_post ) && !empty( $event_id ) && ( $report_event_id == $product_post->ID ) ) ) {
                    //this is an Eventin event
                    $event_id      = $product_post->ID;
                }
            }  
        } else {
            $event_id = $report_event_id;
        }
        

        $is_zoom_event = self::check_if_zoom_event( $event_id );

        if ( $is_zoom_event ) {
            $zoom_event_count++; //zoom event found in this order
            $mail_body_content .= self::zoom_mail_body_content($event_id, $mail_body_content);
        }

        // send email with zoom event details
        if ( $zoom_event_count > 0 ) {
            self::zoom_email_event_details($order_id, $mail_body_content);
        }

        return;
    }

    /**
     * prepare zoom email body
     *
     * @param [int] $event_id
     * @param [string] $mail_body_content
     *
     * @return string
     */
    public static function zoom_mail_body_content( $event_id = null, $mail_body_content = '') {
  
        $event_name        = get_the_title( $event_id );
        $event_link        = ( Helper::is_recurrence( $event_id ) ) ? get_the_permalink( wp_get_post_parent_id( $event_id ) ) : get_the_permalink( $event_id );
        $zoom_meeting_id   = get_post_meta( $event_id, 'etn_zoom_id', true );
        $zoom_meeting_url  = get_post_meta( $zoom_meeting_id, 'zoom_join_url', true );
        $meeting_password  = get_post_meta( $zoom_meeting_id, 'zoom_password', true );
        $date_options      = Helper::get_date_formats();
        $event_options     = get_option( "etn_event_options" );
        $etn_start_date    = strtotime( get_post_meta( $event_id, 'etn_start_date', true ) );
        $etn_start_time    = strtotime( get_post_meta( $event_id, 'etn_start_time', true ) );
        $etn_end_date      = strtotime( get_post_meta( $event_id, 'etn_end_date', true ) );
        $etn_end_time      = strtotime( get_post_meta( $event_id, 'etn_end_time', true ) );
        $event_time_format = empty( $event_options["time_format"] ) ? '12' : $event_options["time_format"];
        $event_start_date  = ( isset( $event_options["date_format"] ) && $event_options["date_format"] !== '' ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_start_date ) : date_i18n( get_option( 'date_format' ), $etn_start_date );
        $event_start_time  = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_start_time ) : date_i18n( get_option( 'time_format' ), $etn_start_time );
        $event_end_time    = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_end_time ) : date_i18n( get_option( 'time_format' ), $etn_end_time );
        $event_end_date    = '';

        if ( $etn_end_date ) {
            $event_end_date = isset( $event_options["date_format"] ) && ( "" != $event_options["date_format"] ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_end_date ) : date_i18n( get_option( 'date_format' ), $etn_end_date );
        }

        ob_start();
        ?>
        <div class="etn-invoice-zoom-event">
            <span class="etn-invoice-zoom-event-title">
                <?php echo esc_html( $event_name ) . esc_html__( " zoom meeting details : ", "eventin" ); ?>
            </span>
            <div class="etn-invoice-zoom-event-details">
                <?php
                if ( !empty( \Etn\Utils\Helper::get_option( 'invoice_include_event_details' ) ) ) {
                ?>
                    <div class="etn-invoice-email-event-meta">
                        <div>
                            <?php echo esc_html__( 'Event Page: ', 'eventin' ); ?>
                            <a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html__( 'Click here. ', 'eventin' ); ?></a>
                        </div>
                        <div><?php echo esc_html__( 'Start: ', 'eventin' ) . $event_start_date . " " . $event_start_time; ?></div>
                        <div><?php echo esc_html__( 'End: ', 'eventin' ) . $event_end_date . " " . $event_end_time; ?></div>
                    </div>
                <?php
                }
                ?>

                <div class="etn-zoom-meeting-url">
                    <span><?php echo esc_html__( 'Meeting URL: ', 'eventin' ); ?></span>
                    <a target="_blank" href="<?php echo esc_url( $zoom_meeting_url ); ?>">
                        <?php echo esc_html__( 'Click to join Zoom meeting', 'eventin' ); ?>
                    </a>
                </div>
                <?php

                if ( !empty( $meeting_password ) ) {
                    ?>
                    <div class="etn-zoom-meeting-password">
                        <span>
                            <?php echo esc_html__( 'Meeting Password: ', 'eventin' ) . $meeting_password; ?>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
        $zoom_details = ob_get_clean();
        $mail_body_content .= $zoom_details;
        
        return $mail_body_content;
    }

    /**
     * prepare zoom email details
     *
     * @param [int] $order_id
     * @param [string] $mail_body_content
     *
     * @return void
     */
    public static function zoom_email_event_details( $order_id = null, $mail_body_content = '') {
        ob_start();
        ?>
        <div>
            <?php echo esc_html__( "Your order no: {$order_id} includes Event(s) which will be hosted on Zoom. Zoom meeting details are as follows. ", 'eventin' ); ?>
        </div>
        <br><br>
        <?php
        $mail_body_header = ob_get_clean();
        $mail_body        = $mail_body_header . $mail_body_content;
        $subject          = esc_html__( 'Event zoom meeting details', "eventin" );
        $from             = self::get_settings()['admin_mail_address'];
        $from_name        = self::retrieve_mail_from_name();

        $to = !empty( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";

        self::send_email( $to, $subject, $mail_body, $from, $from_name );
        update_post_meta( $order_id, 'etn_zoom_email_sent', 'yes' );
    }

    /**
     * get_category id
     *
     * @param [type] $order_id
     * @param [type] $order
     *
     * @since 2.4.1
     *
     * @return void
     */
    public static function get_etn_taxonomy_ids( $taxonomy = 'etn_category', $shortcode_cat = "cat_id" ) {
        $taxonomy = $taxonomy;
        $args_cat = [
            'taxonomy'   => $taxonomy,
            'number'     => 50,
            'hide_empty' => 0,
        ];
        $cats = get_categories( $args_cat );
        ?>
        <select  data-cat = "<?php echo esc_attr( $shortcode_cat ); ?>" class="etn-shortcode-select etn-setting-input" multiple='multiple'>

                <?php foreach ( $cats as $item ): ?>
                <?php echo '<option value="' . esc_attr( $item->term_id ) . '">' . ( esc_html( $item->name ) ) . '</option>'; ?>
            <?php endforeach;?>
        </select>
        <?php
}

    /**
     * returns list of all speaker
     * returns single speaker if speaker id is provuded
     */
    public static function get_posts_ids( $post_type = 'etn-schedule', $shortcode_ids = "ids", $multiple = 'multiple', $query_args = [] ) {
        $args = [
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            'posts_per_page'   => -1,
            'post_parent'      => 0,
            'suppress_filters' => false,
        ];

        if ( !empty( $query_args ) ) {
            $args = $query_args;
        }
        
        $schedules = get_posts( $args );
        ?>
        <select  data-cat = "<?php echo esc_attr( $shortcode_ids ); ?>" class="etn-shortcode-select etn-setting-input" <?php echo esc_attr( $multiple ) ?>>
                <?php foreach ( $schedules as $item ): ?>
                    <?php if ( $post_type === 'etn-zoom-meeting' ) {
            $post_item_id = get_post_meta( $item->ID, 'zoom_meeting_id', true );
        } else {
            $post_item_id = $item->ID;
        }

        ?>
                <?php echo '<option  value="' . esc_attr( $post_item_id ) . '">' . ( esc_html( $item->post_title ) ) . '</option>'; ?>
            <?php endforeach;?>
        </select>
        <?php
}

    /**
     * returns modified posts_per_page for event archive page
     */
    public static function etn_event_archive_pagination_per_page( $query ) {
        if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'etn' ) ) {
            $settings        = \Etn\Core\Settings\Settings::instance()->get_settings_option();
            $events_per_page = !empty( $settings['events_per_page'] ) ? $settings['events_per_page'] : 10;
            $query->set( 'posts_per_page', $events_per_page );
            return $query;
        }

    }

    /**
     * Undocumented function
     *
     * @param [type] $attendee_id
     * @return void
     */
    public static function generate_unique_ticket_id_from_attendee_id( $attendee_id ) {
        $info_edit_token = get_post_meta( $attendee_id, 'etn_info_edit_token', true );
        $ticket_id       = substr( strtoupper( md5( $info_edit_token ) . $attendee_id ), -10 );
        return $ticket_id;
    }

    /**
     * shortcode builder option range
     */
    public static function get_option_range( $arr = [], $class = "" ) {
        ?>
        <select  class="etn-setting-input <?php echo esc_attr( $class ); ?>">
            <?php
$i = 0;
        foreach ( $arr as $key => $value ) {
            $i++;
            $selected = ( $i === 2 ) ? 'selected' : '';
            ?>
                <option value="<?php echo esc_html( $key ); ?>" <?php echo esc_attr( $selected ); ?>> <?php echo esc_html( $value ); ?> </option>
            <?php }

        ?>
        </select>
        <?php
return;
    }

    /**
     * shortcode builder hide empty
     */
    public static function get_show_hide( $key ) {
        $hide_empty = [
            "$key='yes'" => esc_html__( 'Yes', 'eventin' ),
            "$key='no'"  => esc_html__( 'No', 'eventin' ),
        ];
        return self::get_option_range( $hide_empty, '' );
    }

    /**
     * shortcode builder hide empty
     */
    public static function get_order( $key ) {
        $order = [
            "$key='ASC'"  => esc_html__( 'ASC', 'eventin' ),
            "$key='DESC'" => esc_html__( 'DESC', 'eventin' ),
        ];
        return self::get_option_range( $order, '' );
    }

    /**
     * shortcode builder hide empty
     */
    public static function get_event_status( $key ) {
        $event_status = [
            "$key=''"         => esc_html__( 'All', 'eventin' ),
            "$key='upcoming'" => esc_html__( 'Upcoming', 'eventin' ),
            "$key='expire'"   => esc_html__( 'Expire', 'eventin' ),
        ];
        return self::get_option_range( $event_status, '' );
    }

    /**
     * shortcode builder style
     */
    public static function get_option_style( $limit, $value_name, $option_name = "", $display_name = "" ) {
        ?>
        <select  class="etn-setting-input">
            <?php for ( $i = 1; $i <= $limit; $i++ ) {?>
                <option value="<?php echo esc_html( $value_name ); ?> ='<?php echo esc_html( $option_name . $i ); ?>'"> <?php echo esc_html( $display_name . $i, 'eventin' ); ?> </option>
            <?php }

        ?>
        </select>
        <?php
return;
    }

    /**
     * Check If Attendee Exists For A Specific Event Of A Specific Order
     *
     * @since 2.4.6
     *
     * @param [type] $order_id
     * @param [type] $id
     * @return void
     */
    public static function check_if_attendee_exists_for_ordered_event( $order_id ) {
        $args = [
            'post_type'   => 'etn-attendee',
            'post_status' => 'publish',
        ];
        $args['meta_query'] = [
            'relation' => "AND",
            [
                'key'     => 'etn_attendee_order_id',
                'value'   => $order_id,
                'compare' => '=',
            ],
        ];
        $data = get_posts( $args );

        return $data;
    }

    /**
     * Send Attendee Tickets Email For Specific Woocommerce Order
     *
     * @param [type] $order_id
     * @param [type] $report_event_id
     * @return void
     */
    public static function send_attendee_ticket_for_woo_order( $order_id, $report_event_id = null, $gateway = 'woocommerce' ) {

        if ( $gateway == 'woocommerce' ) {
            $order = wc_get_order( $order_id );

            foreach ( $order->get_items() as $item_id => $item ) {
                // Get the product name
                $product_name = $item->get_name();
                $product_id   = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
    
                if ( !empty( $product_id ) ) {
                    $event_object = get_post( $product_id );
                } else {
                    $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
                }
    
                if ( !empty( $event_object->post_type ) && ( 'etn' == $event_object->post_type ) && ( $event_object->ID == $report_event_id ) ) {
                    $event_id = $event_object->ID;

                    $update_key            = !is_null( $item->get_meta( 'etn_status_update_key', true ) ) ? $item->get_meta( 'etn_status_update_key', true ) : "";
                    // $etn_ticket_variations = !is_null( $item->get_meta( 'etn_ticket_variations', true ) ) ? $item->get_meta( 'etn_ticket_variations', true ) : [];
                    
                    self::mail_info_data( $event_id, $order_id, $product_name, $update_key );
                }
            }
        } else {
            $product_name = get_the_title( $report_event_id );
            $update_key   = get_post_meta( $order_id, 'etn_status_update_key', true );
            self::mail_info_data( $report_event_id, $order_id, $product_name, $update_key, $gateway );
        }
    }

    public static function mail_info_data( $event_id = null, $order_id = null, $product_name = '', $update_key = '', $gateway = 'woocommerce' ) {
        // update attendee status and send ticket to email
        $event_location        = !is_null( get_post_meta( $event_id, 'etn_event_location', true ) ) ? get_post_meta( $event_id, 'etn_event_location', true ) : "";
        $etn_ticket_price      = !is_null( get_post_meta( $event_id, 'etn_ticket_price', true ) ) ? get_post_meta( $event_id, 'etn_ticket_price', true ) : "";
        $etn_start_date        = !is_null( get_post_meta( $event_id, 'etn_start_date', true ) ) ? get_post_meta( $event_id, 'etn_start_date', true ) : "";
        $etn_end_date          = !is_null( get_post_meta( $event_id, 'etn_end_date', true ) ) ? get_post_meta( $event_id, 'etn_end_date', true ) : "";
        $etn_start_time        = !is_null( get_post_meta( $event_id, 'etn_start_time', true ) ) ? get_post_meta( $event_id, 'etn_start_time', true ) : "";
        $etn_end_time          = !is_null( get_post_meta( $event_id, 'etn_end_time', true ) ) ? get_post_meta( $event_id, 'etn_end_time', true ) : "";
        $purchaser_email       = !is_null( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";

        $pdf_data = [
            'order_id'         => $order_id,
            'event_name'       => $product_name,
            'update_key'       => $update_key,
            'user_email'       => $purchaser_email,
            'event_location'   => $event_location,
            'etn_ticket_price' => $etn_ticket_price,
            'etn_start_date'   => $etn_start_date,
            'etn_end_date'     => $etn_end_date,
            'etn_start_time'   => $etn_start_time,
            'etn_end_time'     => $etn_end_time,
        ];

        self::mail_attendee_report( $pdf_data, false, false, $gateway );
        // ========================== Attendee related works start ========================= //
    }

    /**
     * markup for attendee ticket send in mail
     */
    public static function generate_attendee_ticket_email_markup( $attendee_id ) {
        $attendee_name = get_the_title( $attendee_id );
        $ticket_name   = !empty( get_post_meta( $attendee_id, 'ticket_name', true ) ) ? get_post_meta( $attendee_id, 'ticket_name', true ) : ETN_DEFAULT_TICKET_NAME;
        $edit_token    = get_post_meta( $attendee_id, 'etn_info_edit_token', true );

        $base_url              = home_url();
        $attendee_cpt          = new \Etn\Core\Attendee\Cpt();
        $attendee_endpoint     = $attendee_cpt->get_name();
        $action_url            = $base_url . "/" . $attendee_endpoint;
        $ticket_download_link  = $action_url . "?etn_action=" . urlencode( 'download_ticket' ) . "&attendee_id=" . urlencode( $attendee_id ) . "&etn_info_edit_token=" . urlencode( $edit_token );
        $edit_information_link = $action_url . "?etn_action=" . urlencode( 'edit_information' ) . "&attendee_id=" . urlencode( $attendee_id ) . "&etn_info_edit_token=" . urlencode( $edit_token );

        ?>
        <div class="etn-attendee-details-button-parent">
            <div class="etn-attendee-details-name"><?php echo esc_html__( 'Ticket name: ', 'eventin' ) . esc_html( $ticket_name ); ?></div>
            <div class="etn-attendee-details-name"><?php echo esc_html__( 'Attendee: ', 'eventin' ) . esc_html( $attendee_name ); ?></div>
            <div class="etn-attendee-details-button-download">
                <a class="etn-btn etn-success download-details" target="_blank" href="<?php echo esc_url( $ticket_download_link ); ?>"><?php echo esc_html__( 'Download Ticket', 'eventin' ); ?></a>
                    |
                <a class="etn-btn etn-success edit-information" target="_blank" href="<?php echo esc_url( $edit_information_link ); ?>"><?php echo esc_html__( 'Edit Information', 'eventin' ); ?></a>
            </div>
        </div><br>
        <?php
}

    /**
     * update attendee status and send ticket to email
     */
    public static function mail_attendee_report( $pdf_data, $checkout = false, $update_payment_status = true, $gateway = 'woocommerce' ) {

        global $wpdb;

        if ( is_array( $pdf_data ) && !empty( $pdf_data['update_key'] ) ) {
            $prepare_guery           = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta where meta_key ='etn_status_update_token' and meta_value = '%s' ", $pdf_data['update_key'] );
            $current_event_attendees = $wpdb->get_col( $prepare_guery );
            $event_name              = $pdf_data['event_name'];

            if ( $gateway == 'woocommerce' ) {
                $order        = wc_get_order( $pdf_data['order_id'] );
                $order_status = $order->get_status();
            } else {
                $order_status = get_post_meta( $pdf_data['order_id'], 'etn_payment_status', true );
            }
        
            // update attendee payment status
            if ( $update_payment_status ) {
                $special_status = $order_status;
                if ( $gateway == 'stripe' ) {
                    $special_status = 'stripe-pending';
                }
                self::process_attendee_payment_status( $current_event_attendees, $pdf_data, $special_status );
            }

            if ( !$checkout ) {
                // don't attempt to send ticket email while creating an order
                // send attendee ticket email
                self::process_attendee_ticket_email( $current_event_attendees, $event_name, $pdf_data, $order_status );
            }
        }
    }

    public static function process_attendee_payment_status( $current_order_attendees, $pdf_data, $order_status ) {

        if ( is_array( $current_order_attendees ) && !empty( $current_order_attendees ) ) {

            foreach ( $current_order_attendees as $key => $value ) {
                $attendee_id = intval( $value );

                //update attendee status
                update_post_meta( $attendee_id, 'etn_attendee_order_id', $pdf_data['order_id'] );
                Helper::update_attendee_payment_status( $attendee_id, $order_status );
            }

        }

    }

    public static function process_attendee_ticket_email( $current_order_attendees, $event_name, $pdf_data, $order_status ) {

        ob_start();
        ?>
        <div>
            <?php echo esc_html__( "You have purchased ticket(s) for '{$event_name}'. Attendee ticket details are as follows. ", 'eventin' ); ?>
        </div>
        <br><br>
        <?php

        if ( is_array( $current_order_attendees ) && !empty( $current_order_attendees ) ) {

            foreach ( $current_order_attendees as $key => $value ) {
                $attendee_id = intval( $value );

                //generate email content markup
                Helper::generate_attendee_ticket_email_markup( $attendee_id );
            }

        }

        $mail_content = ob_get_clean();
        $mail_content = Helper::kses( $mail_content );

        $settings_options = Helper::get_settings();

        $disable_ticket_email = !empty( Helper::get_option( 'disable_ticket_email' ) ) ? true : false;

        // send email with attendee tickets
        if (  ( !$disable_ticket_email ) && is_array( $pdf_data ) && !empty( $settings_options['admin_mail_address'] ) && !empty( $pdf_data['user_email'] ) ) {
            $to        = $pdf_data['user_email'];
            $subject   = esc_html__( 'Event Ticket', "eventin" );
            $from      = $settings_options['admin_mail_address'];
            $from_name = self::retrieve_mail_from_name();

            $proceed_ticket_mail = true;

            $order_status = strtolower($order_status );
            
            // if checkout time and order_status is processing/completed then ticket mail will sent
            if ( !( $order_status == 'processing' || $order_status == 'completed' ) ) {
                $proceed_ticket_mail = false;
            }
            $proceed_ticket_mail = true;
            if ( $proceed_ticket_mail ) {
                Helper::send_email( $to, $subject, $mail_content, $from, $from_name );
            }

        }

    }

    /**
     * get decoded version of special character to show
     *
     * @return string
     */
    public static function retrieve_mail_from_name() {
        add_filter( 'wp_mail_from_name', function () {
            return html_entity_decode( get_bloginfo( "name" ), ENT_QUOTES );
        } );
    }

    public static function send_all_attendee_ticket_email_by_order( $order_id ) {

    }

    /**
     * Sanitize Recurring Event Slug Name
     *
     * @param [type] $post_slug
     * @param [type] $post_slug_postfix
     * @return void
     */
    public static function sanitize_recurring_event_slug( $post_slug, $post_slug_postfix ) {

        if ( strlen( $post_slug . '-' . $post_slug_postfix ) > 200 ) {
            if ( preg_match( '/^(.+)(\-[0-9]+)$/', $post_slug, $post_slug_parts ) ) {
                $post_slug_decoded = urldecode( $post_slug_parts[1] );
                $post_slug_suffix  = $post_slug_parts[2];
            } else {
                $post_slug_decoded = urldecode( $post_slug );
                $post_slug_suffix  = '';
            }

            $post_slug_maxlength = 200 - strlen( $post_slug_suffix . '-' . $post_slug_postfix );
            if ( $post_slug_parts[0] === $post_slug_decoded . $post_slug_suffix ) {
                $post_slug = substr( $post_slug_decoded, 0, $post_slug_maxlength );
            } else {
                $post_slug = utf8_uri_encode( $post_slug_decoded, $post_slug_maxlength );
            }

            $post_slug = rtrim( $post_slug, '-' ) . $post_slug_suffix;
        } else {
            $post_slug = rtrim( $post_slug . '-' ) . $post_slug_postfix;
        }

        return apply_filters( 'etn_sanitize_recurring_event_slug', $post_slug, $post_slug_postfix );
    }

    /**
     * Check if slug exists
     *
     * @param [type] $post_name
     * @return void
     */
    public static function the_slug_exists( $post_name ) {
        global $wpdb;
        if ( $wpdb->get_row( "SELECT post_name FROM $wpdb->posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A' ) ) {
            return true;
        } else {
            return false;
        }

    }

    /*
     * get all posts which are shop_order
     */
    public static function get_order_posts() {
        global $wpdb;
        $order_posts = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'shop_order' ORDER BY id DESC", ARRAY_A );

        return $order_posts;
    }

    /**
     * Checks If An Event Has Recurring Events
     *
     * @param [type] $single_event_id
     * @return void
     */
    public static function get_child_events( $single_event_id ) {
        //if post type is etn and post has this is as parent-id
        $args = [
            'post_parent' => $single_event_id,
            'post_type'   => 'etn',
        ];
        $children = get_children( $args );

        if ( !empty( $children ) ) {
            return $children;
        }

        return false;
    }

    /**
     * All data for recurrence details table
     */
    public static function get_all_data( $id ) {
        $events_data = [];

        $events = get_posts( [
            'post_parent'    => $id,
            'posts_per_page' => -1,
            'post_type'      => 'etn',
        ] );

        if ( !empty( $events ) ) {

            foreach ( $events as $key => $post ) {
                $freq                          = get_post_meta( $id, 'etn_event_recurrence', true );
                $recurr_type                   = !empty( $freq['recurrence_freq'] ) ? ucfirst( $freq['recurrence_freq'] ) : "";
                $events_data[$key]['ID']       = $post->ID;
                $events_data[$key]['name']     = $post->post_title;
                $events_data[$key]['location'] = get_post_meta( $post->ID, 'etn_event_location', true );

                $events_data[$key]['schedule'] = esc_html__( 'Date: ', 'eventin' ) . get_post_meta( $post->ID, 'etn_start_date', true ) . esc_html__( ' to ', 'eventin' ) . get_post_meta( $post->ID, 'etn_end_date', true ) .
                esc_html__( ' Time: ', 'eventin' ) . get_post_meta( $post->ID, 'etn_start_time', true ) . esc_html__( ' - ', 'eventin' ) . get_post_meta( $post->ID, 'etn_end_time', true );
            }

        }

        return $events_data;
    }

    public static function total_data( $id ) {
        $events = get_posts( [
            'post_parent'    => $id,
            'posts_per_page' => -1,
            'post_type'      => 'etn',
        ] );

        return count( $events );
    }

    /**
     * Check If An Event Is A Recurrence Child
     *
     * @param [type] $event_id
     * @return boolean
     */
    public static function is_recurrence( $event_id ) {

        if ( 'etn' == get_post_type( $event_id ) && '0' != wp_get_post_parent_id( $event_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Return day name
     *
     * @return void
     */
    public static function day_name() {
        return ['Sun' => 'Sun', 'Mon' => 'Mon', 'Tue' => 'Tue',
            'Wed'         => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat'];
    }

    /**
     * Create page
     *
     * @param string $title_of_the_page
     * @param string $content
     * @param [type] $parent_id
     * @return void
     */
    public static function create_page( $title_of_the_page, $content = '', $parent_id = NULL, $replace = '_' ) {

        $objPage = get_page_by_path( $title_of_the_page );

        if ( empty( $objPage ) ) {

            $page_id = wp_insert_post(
                [
                    'comment_status' => 'close',
                    'ping_status'    => 'close',
                    'post_author'    => 1,
                    'post_title'     => ucwords( str_replace( $replace, ' ', trim( $title_of_the_page ) ) ),
                    'post_name'      => $title_of_the_page,
                    'post_status'    => 'publish',
                    'post_content'   => $content,
                    'post_type'      => 'page',
                    'post_parent'    => $parent_id,
                ]
            );

        } else {
            $page_id = $objPage->ID;
        }

        return $page_id;
    }

    public static function send_attendee_ticket_email_on_order_status_change( $order_id ) {

        if ( !$order_id ) {
            return;
        }

        global $wpdb;

        $order = wc_get_order( $order_id );

        $userId = 0;

        if ( is_user_logged_in() ) {
            $userId = get_current_user_id();
        }

        foreach ( $order->get_items() as $item_id => $item ) {

            // Get the product name
            $product_name = $item->get_name();
            $event_id     = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";

            if ( !empty( $event_id ) ) {
                $event_object = get_post( $event_id );
            } else {
                $event_object = get_page_by_title( $product_name, OBJECT, 'etn' );
            }

            if ( !empty( $event_object->post_type ) && ( 'etn' == $event_object->post_type ) ) {

                $event_id = $event_object->ID;

                // ========================== Attendee related works start ========================= //
                $settings            = Helper::get_settings();
                $attendee_reg_enable = !empty( $settings["attendee_registration"] ) ? true : false;

                if ( $attendee_reg_enable ) {
                    // update attendee status and send ticket to email
                    $event_location        = !is_null( get_post_meta( $event_object->ID, 'etn_event_location', true ) ) ? get_post_meta( $event_object->ID, 'etn_event_location', true ) : "";
                    $ticket_price          = !is_null( get_post_meta( $event_object->ID, 'etn_ticket_price', true ) ) ? get_post_meta( $event_object->ID, 'etn_ticket_price', true ) : "";
                    $start_date            = !is_null( get_post_meta( $event_object->ID, 'etn_start_date', true ) ) ? get_post_meta( $event_object->ID, 'etn_start_date', true ) : "";
                    $end_date              = !is_null( get_post_meta( $event_object->ID, 'etn_end_date', true ) ) ? get_post_meta( $event_object->ID, 'etn_end_date', true ) : "";
                    $start_time            = !is_null( get_post_meta( $event_object->ID, 'etn_start_time', true ) ) ? get_post_meta( $event_object->ID, 'etn_start_time', true ) : "";
                    $end_time              = !is_null( get_post_meta( $event_object->ID, 'etn_end_time', true ) ) ? get_post_meta( $event_object->ID, 'etn_end_time', true ) : "";
                    $update_key            = !is_null( $item->get_meta( 'etn_status_update_key', true ) ) ? $item->get_meta( 'etn_status_update_key', true ) : "";
                    $purchaser_email       = !is_null( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";
                    $etn_ticket_variations = !is_null( $item->get_meta( 'etn_ticket_variations', true ) ) ? $item->get_meta( 'etn_ticket_variations', true ) : [];

                    $pdf_data = [
                        'order_id'         => $order_id,
                        'event_name'       => $product_name,
                        'update_key'       => $update_key,
                        'user_email'       => $purchaser_email,
                        'event_location'   => $event_location,
                        'etn_ticket_price' => $ticket_price,
                        'etn_start_date'   => $start_date,
                        'etn_end_date'     => $end_date,
                        'etn_start_time'   => $start_time,
                        'etn_end_time'     => $end_time,
                    ];

                    self::mail_attendee_report( $pdf_data, false, false );
                }

                // ========================== Attendee related works end ========================= //
            }

        }

    }

    /**
     * Input field escaping , sanitizing , validation
     *
     * @param array $request
     * @param array $input_fields
     *
     * @return array
     */
    public static function input_field_validation( $request, $input_fields ) {

        $response = [
            'status_code' => 1,
            'messages'    => [],
            'data'        => [],
        ];

        if ( !empty( $input_fields ) ) {
            $error_field = [];

            foreach ( $input_fields as $key => $value ) {

                if ( $value['required'] == true && empty( $request[$value['name']] ) ) {
                    $error_field[] = esc_html( ucfirst( str_replace( '_', ' ', $value['name'] ) ) . ' is empty', 'eventin' );
                }

            }

            if ( count( $error_field ) > 0 ) {
                $response = [
                    'status_code' => 0,
                    'messages'    => $error_field,
                ];
            } else {

                $input_data = [];

                foreach ( $input_fields as $key => $value ) {
                    $data                       = self::validate_param_data( $request, $value );
                    $input_data[$value['name']] = $data;
                }

                // pass sanitizing data
                $response = [
                    'status_code' => 1,
                    'messages'    => [],
                    'data'        => $input_data,
                ];
            }

        } else {
            $response = [
                'status_code' => 0,
                'messages'    => [
                    'error' => esc_html__( 'Input field is empty', 'eventin' ),
                ],
            ];
        }

        return $response;
    }

    /**
     * Sanitize and escaping data
     *
     * @param array $request
     * @param array $input_fields
     *
     * @return mixed
     */
    public static function validate_param_data( $request, $input_fields ) {
        $data = "";

        switch ( $input_fields['type'] ) {
        case "email":
            $data = sanitize_email( $request[$input_fields['name']] );
            break;
        case "text":
            $data = sanitize_text_field( $request[$input_fields['name']] );
            break;
        case "number":
            $data = absint( $request[$input_fields['name']] );
            break;
        default:
            break;
        }

        return $data;
    }

    /**
     * Get All Events By Month of A Year
     *
     * @param [type] $month
     * @param [type] $year
     * @param array $params
     * @return void
     */
    public static function get_events_by_date( $month, $year, $display, $endDate, $startTime ) {

        if ( empty( $month ) || empty( $year ) ) {
            return;
        }

        $date = $year . '-' . $month;
        global $wpdb;
        $sql           = "SELECT DISTINCT `post_id` FROM {$wpdb->prefix}postmeta WHERE (`meta_key`='etn_start_date' AND `meta_value` LIKE '%$date%') OR (`meta_key`='etn_end_date' AND `meta_value` LIKE '%$date%')";
        $all_event_ids = $wpdb->get_results( $sql );

        $all_events = [];

        foreach ( $all_event_ids as $single_event ) {
            $event_id  = $single_event->post_id;
            $event_cat = wp_get_post_terms( $event_id, 'etn_category' );
            $cat_names = wp_list_pluck( $event_cat, 'name' );
            $event     = new \stdClass;
            $etn_ticket_variations    = get_post_meta($event_id,"etn_ticket_variations", true); 

            $currency = '';

            if ( class_exists( 'woocommerce' ) &&  !empty($etn_ticket_variations ) ) {
                $currency = get_woocommerce_currency_symbol();
            } else

            if ( !class_exists( 'woocommerce' ) && !empty($etn_ticket_variations ) ) {
                $currency = '$';
            }
            $min_price='';
            $max_price='';
            $variation_price    = [];
            if(!empty($etn_ticket_variations) && is_array($etn_ticket_variations)){
                foreach($etn_ticket_variations as $index => $price){
                    $variation_price[$index] =  $price['etn_ticket_price'];
                } 
                $min_price = min($variation_price); 
                $max_price = max($variation_price);  
            } 

            if($min_price === $max_price){
                $price =  $currency . $min_price;
            }else {
                $price =  $currency . $min_price ."-". $currency . $max_price;
            }  
            if( !empty($display) ){
                $event->display         = $display;
            }
         

            $start_times = date_i18n(get_option( "time_format" ), strtotime(get_post_meta( $event_id, 'etn_start_time', true )));
            $end_times   = date_i18n(get_option( "time_format" ), strtotime(get_post_meta( $event_id, 'etn_end_time', true )));
            
            // time stamp
            $start_date = get_post_meta( $event_id, 'etn_start_date', true );
            $end_date   = get_post_meta( $event_id, 'etn_end_date', true ); 


            $start_time = !empty(date("H:i:s", strtotime(get_post_meta( $event_id, 'etn_start_time', true )))) ? 'T'. date("H:i:s", strtotime(get_post_meta( $event_id, 'etn_start_time', true ))) : '12:00 00';
            $end_time   = !empty(date("H:i:s", strtotime(get_post_meta( $event_id, 'etn_end_time', true )))) ? 'T'.date("H:i:s", strtotime(get_post_meta( $event_id, 'etn_end_time', true ))) : '12:00 00';
            if($start_date < $end_date){
                $start_date = $start_date;
                $end_date = $end_date;
            }else{ 
                $start_date = $start_date."".$start_time;
                $end_date = $end_date."".$end_time;
            } 

            $event->className   = "has-event";
            $event->start_time  = $start_times;
            $event->end_time    = $end_times;
            $event->id          = $event_id;
            $event->title       = get_the_title( $event_id );
            $event->date        = get_post_meta( $event_id, 'etn_start_date', true );
            if($startTime){
                $event->start       = $start_date;
            }
             if( $endDate ){
                $event->end         = $end_date; 
            }
            $event->price       = $price;
            $event->description = get_post_field( 'post_content', $event_id );
            $event->thumbnail   = get_the_post_thumbnail_url( $event_id );
            $event->category    = $cat_names;
            $event->location    = get_post_meta( $event_id, 'etn_event_location', true );
            $event->url         = get_permalink( $event_id );
            $event->backgroundColor = get_post_meta( $event_id, 'etn_event_calendar_bg', true );
            
            $event->borderColor = get_post_meta( $event_id, 'etn_event_calendar_bg', true );
            $event->textColor  = get_post_meta( $event_id, 'etn_event_calendar_text_color', true );
            $all_events[]       = $event;
        }

        return $all_events;
    }

    public static function generate_unique_slug_from_ticket_title( $event_id, $event_ticket_variation_title ) {
        $ticket_title = $event_ticket_variation_title == "" ? esc_html__( "Default", "eventin" ) : $event_ticket_variation_title;

        return $event_id . "-" . sanitize_title_with_dashes( $ticket_title ) . "-" . substr( md5( time() ), 0, 5 );
    }

    /**
     * returns list of all speaker
     * returns single speaker if speaker id is provuded
     */
    public static function get_attendee( $id = null ) {
        try {

            if ( is_null( $id ) ) {
                $args = [
                    'post_type'      => 'etn-attendee',
                    'posts_per_page' => -1,
                ];
                $attendees = get_posts( $args );
                return $attendees;
            } else {
                // return single speaker
                return get_post( $id );
            }

        } catch ( \Exception $es ) {
            return [];
        }

    }

    /**
     * calculate left ticket for individual variation ticket from total and sold quantity
     *
     * @param [array] $ticket_variation
     * @return void
     */
    public static function compute_left_tickets( $ticket_variation ) {
        $left_tickets = 0;

        if ( !empty( $ticket_variation ) ) {
            $avaiilable_tickets = !empty( $ticket_variation['etn_avaiilable_tickets'] ) ? absint( $ticket_variation['etn_avaiilable_tickets'] ) : 0;
            $sold_tickets       = !empty( $ticket_variation['etn_sold_tickets'] ) ? absint( $ticket_variation['etn_sold_tickets'] ) : 0;
            $left_tickets       = $avaiilable_tickets - $sold_tickets;
        }

        return $left_tickets;
    }

    /**
     * Ticket Form Widget For Single Events
     *
     * @param [type] $single_event_id
     * @param string $class
     * @return void
     */
    public static function woocommerce_ticket_widget( $single_event_id, $class = "" ) {
        $data        = self::single_template_options( $single_event_id );
        $ticket_list = get_post_meta( $single_event_id, 'etn_ticket_variations', true );
        $unique_id   = md5( md5( microtime() ) );

        if ( is_array( $ticket_list ) && !empty( $ticket_list ) ) {
            $event_options        = !empty( $data['event_options'] ) ? $data['event_options'] : [];
            $etn_ticket_unlimited = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
            $is_zoom_event        = get_post_meta( $single_event_id, 'etn_zoom_event', true );
            $event_title          = get_the_title( $single_event_id );
            $deadline             = $data['etn_deadline_value'];
            $reg_deadline_expired = false;

            $date_options         = \Etn\Utils\Helper::get_date_formats();
            $etn_date_format      = ( isset( $event_options["date_format"] ) && $event_options["date_format"] != "" ) ? $date_options[$event_options["date_format"]] : get_option( "date_format" );

            if( !empty( $deadline ) ){
                $datetime = \DateTime::createFromFormat($etn_date_format, $deadline);
                $deadline = $datetime->getTimestamp();
                if( strtotime("now") > $deadline ){
                    $reg_deadline_expired = true;
                }
            }

            $event_total_ticket = !empty( get_post_meta( $single_event_id, "etn_total_avaiilable_tickets", true ) ) ? absint( get_post_meta( $single_event_id, "etn_total_avaiilable_tickets", true ) ) : 0;
            $event_sold_ticket  = !empty( get_post_meta( $single_event_id, "etn_total_sold_tickets", true ) ) ? absint( get_post_meta( $single_event_id, "etn_total_sold_tickets", true ) ) : 0;
            $event_left_ticket  = $event_total_ticket - $event_sold_ticket;

            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/single-event-variable-ticket.php' ) ) {
                $purchase_form_widget = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/single-event-variable-ticket.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/single-event-variable-ticket.php' ) ) {
                $purchase_form_widget = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/single-event-variable-ticket.php';
            } else {
                $purchase_form_widget = \Wpeventin::templates_dir() . 'event/purchase-form/single-event-variable-ticket.php';
            }

        } else {

            $etn_left_tickets     = !empty( $data['etn_left_tickets'] ) ? $data['etn_left_tickets'] : 0;
            $etn_ticket_unlimited = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
            $etn_ticket_price     = isset( $data['etn_ticket_price'] ) ? $data['etn_ticket_price'] : '';
            $ticket_qty           = get_post_meta( $single_event_id, "etn_sold_tickets", true );
            $total_sold_ticket    = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
            $is_zoom_event        = get_post_meta( $single_event_id, 'etn_zoom_event', true );
            $event_options        = !empty( $data['event_options'] ) ? $data['event_options'] : [];
            $event_title          = get_the_title( $single_event_id );
            $etn_min_ticket       = !empty( get_post_meta( $single_event_id, 'etn_min_ticket', true ) ) ? get_post_meta( $single_event_id, 'etn_min_ticket', true ) : 1;
            $etn_max_ticket       = !empty( get_post_meta( $single_event_id, 'etn_max_ticket', true ) ) ? get_post_meta( $single_event_id, 'etn_max_ticket', true ) : $etn_left_tickets;
            $etn_max_ticket       = min( $etn_left_tickets, $etn_max_ticket );

            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/event-ticket.php' ) ) {
                $purchase_form_widget = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/event-ticket.php';
            } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/event-ticket.php' ) ) {
                $purchase_form_widget = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/event-ticket.php';
            } else {
                $purchase_form_widget = \Wpeventin::templates_dir() . 'event/purchase-form/event-ticket.php';
            }

        }

        include $purchase_form_widget;
    }

    /**
     * Ticket Form Widget For Recurring Events
     *
     * @param [type] $single_event_id
     * @param string $class
     * @return void
     */
    public static function woocommerce_recurring_events_ticket_widget( $parent_event_id, $recurring_event_ids, $class = "" ) {

        do_action( 'etn_before_recurring_event_form_content', $parent_event_id );

        if ( is_array( $recurring_event_ids ) && !empty( $recurring_event_ids ) ) {

            asort( $recurring_event_ids );
            $i = 0;

            foreach ( $recurring_event_ids as $key => $single_event_id ) {

                $data        = self::single_template_options( $single_event_id );
                $ticket_list = get_post_meta( $single_event_id, 'etn_ticket_variations', true );
                $unique_id   = md5( md5( microtime() ) );

                if ( is_array( $ticket_list ) && !empty( $ticket_list ) ) {
                    $event_options        = !empty( $data['event_options'] ) ? $data['event_options'] : [];
                    $etn_ticket_unlimited = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
                    $is_zoom_event        = get_post_meta( $single_event_id, 'etn_zoom_event', true );
                    $event_title          = get_the_title( $single_event_id );
                    $deadline             = $data['etn_deadline_value'];
                    $reg_deadline_expired = false;

                    $date_options               = \Etn\Utils\Helper::get_date_formats();
                    $etn_date_format   = ( isset( $event_options["date_format"] ) && $event_options["date_format"] != "" ) ? $date_options[$event_options["date_format"]] : get_option( "date_format" );

                    if( !empty( $deadline ) ){
                        $datetime = \DateTime::createFromFormat($etn_date_format, $deadline);
                        $deadline = $datetime->getTimestamp();
                        if( strtotime("now") > $deadline ){
                            $reg_deadline_expired = true;
                        }
                    }

                    $event_total_ticket = !empty( get_post_meta( $single_event_id, "etn_total_avaiilable_tickets", true ) ) ? absint( get_post_meta( $single_event_id, "etn_total_avaiilable_tickets", true ) ) : 0;
                    $event_sold_ticket  = !empty( get_post_meta( $single_event_id, "etn_total_sold_tickets", true ) ) ? absint( get_post_meta( $single_event_id, "etn_total_sold_tickets", true ) ) : 0;
                    $event_left_ticket  = $event_total_ticket - $event_sold_ticket;

                    if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-variable-ticket.php' ) ) {
                        $purchase_form_widget = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-variable-ticket.php';
                    } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-variable-ticket.php' ) ) {
                        $purchase_form_widget = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-variable-ticket.php';
                    } else {
                        $purchase_form_widget = \Wpeventin::templates_dir() . 'event/purchase-form/recurring-event-variable-ticket.php';
                    }

                } else {

                    $etn_left_tickets     = !empty( $data['etn_left_tickets'] ) ? $data['etn_left_tickets'] : 0;
                    $etn_ticket_unlimited = ( isset( $data['etn_ticket_unlimited'] ) && $data['etn_ticket_unlimited'] == "no" ) ? true : false;
                    $etn_ticket_price     = isset( $data['etn_ticket_price'] ) ? $data['etn_ticket_price'] : '';
                    $ticket_qty           = get_post_meta( $single_event_id, "etn_sold_tickets", true );
                    $total_sold_ticket    = isset( $ticket_qty ) ? intval( $ticket_qty ) : 0;
                    $is_zoom_event        = get_post_meta( $single_event_id, 'etn_zoom_event', true );
                    $event_options        = !empty( $data['event_options'] ) ? $data['event_options'] : [];
                    $event_title          = get_the_title( $single_event_id );
                    $etn_min_ticket       = !empty( get_post_meta( $single_event_id, 'etn_min_ticket', true ) ) ? get_post_meta( $single_event_id, 'etn_min_ticket', true ) : 1;
                    $etn_max_ticket       = !empty( get_post_meta( $single_event_id, 'etn_max_ticket', true ) ) ? get_post_meta( $single_event_id, 'etn_max_ticket', true ) : $etn_left_tickets;
                    $etn_max_ticket       = min( $etn_left_tickets, $etn_max_ticket );

                    if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-ticket.php' ) ) {
                        $purchase_form_widget = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-ticket.php';
                    } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-ticket.php' ) ) {
                        $purchase_form_widget = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/recurring-event-ticket.php';
                    } else {
                        $purchase_form_widget = \Wpeventin::templates_dir() . 'event/purchase-form/recurring-event-ticket.php';
                    }

                }

                if ( file_exists( $purchase_form_widget ) ) {
                    include $purchase_form_widget;
                }

                $i++;
            }

        }

        do_action( 'etn_after_recurring_event_form_content', $parent_event_id );

    }

    public static function convert_to_calender_date( $datetime ) {
        $time_string   = strtotime( $datetime );
        $date          = date_i18n( 'Ymd', $time_string );
        $time          = date( 'Hi', $time_string );
        $calendar_date = $date . "T" . $time . "00";
        return $calendar_date;
    }

    public static function content_to_html( $array_or_string ) {

        if ( is_string( $array_or_string ) ) {

            $array_or_string = sanitize_text_field( htmlentities( nl2br( $array_or_string ) ) );

        } elseif ( is_array( $array_or_string ) ) {

            foreach ( $array_or_string as $key => &$value ) {

                if ( is_array( $value ) ) {
                    $value = self::content_to_html( $value );
                } else {
                    $value = sanitize_text_field( htmlentities( nl2br( $value ) ) );
                }

            }

        }

        return $array_or_string;
    }

    public static function convert_to_calendar_title($post_title){
        return str_replace(' ', '+', $post_title);
    }

    /**
     * zoom types; meeting, webinar
     */
    public static function zoom_types() {
        $zoom_types = [ 
            '2' => esc_html__( 'Meeting', 'eventin' ), 
            '5' => esc_html__( 'Webinar', 'eventin' ) 
        ];

        return $zoom_types;
    }

    /**
     * extra zoom settings/options
     */
    public static function get_zoom_meta_settings( $type = 2, $settings_data = null, $meta_array = [] ) {

        if ( !empty( $settings_data ) ) {
            $meta_array += [
                'zoom_meeting_authentication'   => $settings_data->meeting_authentication,
                'zoom_host_video'               => $settings_data->host_video,
                'zoom_auto_recording'           => $settings_data->auto_recording,
            ];
            
            if ( $type == '2' ) {
                $meta_array             += [
                    'zoom_participant_panelists_video'    => $settings_data->participant_video,
                    'zoom_waiting_room'         => $settings_data->waiting_room,
                    'zoom_join_before_host'     => $settings_data->join_before_host,
                    'zoom_mute_upon_entry'      => $settings_data->mute_upon_entry,
                ];
            } elseif( $type == '5' ) {
                $meta_array             += [
                    'zoom_participant_panelists_video'          => $settings_data->panelists_video,
                    'zoom_question_and_answer'      => $settings_data->question_and_answer->enable,
                    'zoom_practice_session'         => $settings_data->practice_session,
                    'zoom_hd_video'                 => $settings_data->hd_video,
                    'zoom_hd_video_for_attendees'   => $settings_data->hd_video_for_attendees,
                ];
            }
        }

        return $meta_array;
    }


    /**
     * sync time delete zoom posts
     */
    public static function delete_zoom_posts( $post_ids = [] ) {
        if ( !empty( $post_ids ) ) {
            foreach ( $post_ids as $post_id => $zoom_id ) {
                wp_delete_post( $post_id );
            }
        }
    }

    /**
     * check webinar plan is enabled for the user
     */
    public static function is_webinar_user() {
        $webinar_enabled    = false;
        $user_settings_obj  = json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_user_settings() );

        if ( is_object( $user_settings_obj ) && isset( $user_settings_obj->feature ) ) {
            $webinar_enabled = $user_settings_obj->feature->webinar;
        }

        return $webinar_enabled;
    }

    /**
     * return settings set payment gateway like woocommerce, stripe etc.
     */
    public static function retrieve_payment_gateway() {
        $settings 		= \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $payment_type 	= isset( $settings['etn_sells_engine_stripe'] ) ? $settings['etn_sells_engine_stripe'] : 'woocommerce';

        return $payment_type;
    }

}
