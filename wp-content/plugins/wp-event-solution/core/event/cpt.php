<?php

namespace Etn\Core\Event;

defined( 'ABSPATH' ) || exit;
/**
 * Cpt Class.
 * Cpt class for custom post type of Event.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */
class Cpt extends \Etn\Base\Cpt {

    // set custom post type name
    public function get_name() {
        return 'etn';
    }

    // set custom post type options data
    public function post_type() {
        $options = $this->user_modifiable_option();

        $labels  = [
            'name'                  => esc_html_x( 'Events', 'Post Type General Name', 'eventin' ),
            'singular_name'         => apply_filters( 'etn_event_singular_name', $options['wp_eventlty_singular_name'] ),
            'menu_name'             => esc_html__( 'Event', 'eventin' ),
            'name_admin_bar'        => esc_html__( 'Event', 'eventin' ),
            'archives'              => apply_filters( 'etn_event_archive', $options['etn_event_archive'] ),
            'attributes'            => esc_html__( 'Event Attributes', 'eventin' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'eventin' ),
            'all_items'             => apply_filters( 'etn_all_items', $options['etn_event_all'] ),
            'add_new_item'          => apply_filters( 'etn_add_new_item', 'Add New Event' ),
            'add_new'               => esc_html__( 'Add New', 'eventin' ),
            'new_item'              => esc_html__( 'New Event', 'eventin' ),
            'edit_item'             => esc_html__( 'Edit Event', 'eventin' ),
            'update_item'           => esc_html__( 'Update Event', 'eventin' ),
            'view_item'             => esc_html__( 'View Event', 'eventin' ),
            'view_items'            => esc_html__( 'View Events', 'eventin' ),
            'search_items'          => esc_html__( 'Search Events', 'eventin' ),
            'not_found'             => esc_html__( 'Not found', 'eventin' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'eventin' ),
            'featured_image'        => esc_html__( 'Featured Image', 'eventin' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'eventin' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'eventin' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'eventin' ),
            'insert_into_item'      => esc_html__( 'Insert into Event', 'eventin' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Event', 'eventin' ),
            'items_list'            => esc_html__( 'Events list', 'eventin' ),
            'items_list_navigation' => esc_html__( 'Events list navigation', 'eventin' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'eventin' ),
        ];
        $rewrite = [
            'slug'       => apply_filters( 'wp_eventlty_slug', $options['event_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => esc_html__( 'Events', 'eventin' ),
            'description'         => esc_html__( 'Event', 'eventin' ),
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'thumbnail','excerpt' ],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_admin_column'   => false,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => $options['etn_exclude_from_search'],
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'rest_base'           => $this->get_name(),

        ];

        if( current_user_can( 'manage_etn_event' ) ){
            $args['show_in_menu']        = 'etn-events-manager';
        }

        return $args;
    }

    private function user_modifiable_option() {
        $settings_options   = get_option( 'etn_event_options' );
        $options = [
            'wp_eventlty_singular_name' => esc_html__( 'Event', 'eventin' ),
            'etn_event_archive'         => esc_html__( 'Event Archive', 'eventin' ),
            'etn_event_all'             => esc_html__( 'Events', 'eventin' ),
            'event_slug'                => 'etn',
            'etn_exclude_from_search'   => false,
        ];

        if ( !empty( $settings_options['event_slug'] ) ) {
            $options['event_slug'] = str_replace( ' ', '_' , $settings_options['event_slug'] );
        }
        
        if ( !empty( $settings_options['etn_include_from_search'] ) && 'off' === $settings_options['etn_include_from_search'] ) {
            $options['etn_exclude_from_search'] = true;
        }

        return $options;
    }

}
