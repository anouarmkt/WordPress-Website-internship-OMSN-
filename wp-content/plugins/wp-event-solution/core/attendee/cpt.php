<?php

namespace Etn\Core\Attendee;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Cpt Class.
 * Cpt class for custom post type of Speaker.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */

class Cpt extends \Etn\Base\Cpt {

    public function create_cpt(){

        if( !is_admin() ){
            return true;
        }

        if( current_user_can( 'manage_etn_attendee' )){
            return true;
        }
        
        return false;
    }

    /**
     * set custom post type name
     */ 
    public function get_name() {
        return "etn-attendee";
    }

     /**
     * set custom post type options data
     */
    public function post_type() {
        $options = $this->user_modifiable_option();

        $labels = [
            'name'                  => esc_html_x( 'Attendees', 'Post Type General Name', 'eventin' ),
            'singular_name'         => $options['etn_attendee_singular_name'],
            'menu_name'             => esc_html__( 'Attendee', 'eventin' ),
            'name_admin_bar'        => esc_html__( 'Attendee', 'eventin' ),
            'archives'              => $options['etn_attendee_archive'],
            'attributes'            => esc_html__( 'Attendee Attributes', 'eventin' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'eventin' ),
            'all_items'             => $options['etn_attendee_all'],
            'add_new_item'          => esc_html__( 'Add New Attendee', 'eventin' ),
            'add_new'               => esc_html__( 'Add New', 'eventin' ),
            'new_item'              => esc_html__( 'New Attendee', 'eventin' ),
            'edit_item'             => esc_html__( 'Edit Attendee', 'eventin' ),
            'update_item'           => esc_html__( 'Update Attendee', 'eventin' ),
            'view_item'             => esc_html__( 'View Attendee', 'eventin' ),
            'view_items'            => esc_html__( 'View Attendee', 'eventin' ),
            'search_items'          => esc_html__( 'Search Attendee', 'eventin' ),
            'not_found'             => esc_html__( 'Not found', 'eventin' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'eventin' ),
            'featured_image'        => esc_html__( 'Featured Image', 'eventin' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'eventin' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'eventin' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'eventin' ),
            'insert_into_item'      => esc_html__( 'Insert into Attendee', 'eventin' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Attendee', 'eventin' ),
            'items_list'            => esc_html__( 'Attendee list', 'eventin' ),
            'items_list_navigation' => esc_html__( 'Attendee list navigation', 'eventin' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'eventin' ),
        ];

        $rewrite = [
            'slug'       => apply_filters( 'attendee_slug', $options['attendee_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => esc_html__( 'Attendee', 'eventin' ),
            'description'         => esc_html__( 'Attendee', 'eventin' ),
            'labels'              => $labels,
            'supports'            => ['title'],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_admin_column'   => false,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),
            'capabilities' => array(
              'create_posts'    => 'do_not_allow', // Removes support for the "Add New" 
            ),
            'map_meta_cap' => true, // Allow edit / delete
        ];
        if( current_user_can( 'manage_etn_attendee' ) ){
            $args['show_in_menu']        = 'etn-events-manager';
        }

        return $args;
    }

    /**
     * Operation custom post type
     */ 
    public function flush_rewrites() {
        $name = $this->get_name();
        $args = $this->post_type();

        register_post_type( $name, $args );
        flush_rewrite_rules();
    }
    
    /**
     * Modifiable options is here
     */
    private function user_modifiable_option() {
        $etn_attendee_singular_name = Helper::get_option("etn_attendee_singular_name");
        $etn_attendee_archive       = Helper::get_option("etn_attendee_archive");
        $etn_attendee_all           = Helper::get_option("etn_attendee_all");
        $attendee_slug              = Helper::get_option("attendee_slug");

        $options = [
            'etn_attendee_singular_name'       => esc_html__( 'Attendee', 'eventin' ),
            'etn_attendee_archive'             => esc_html__( 'Attendee Archive', 'eventin' ),
            'etn_attendee_all'                 => esc_html__( 'Attendees', 'eventin' ),
            'attendee_slug'                    => 'etn-attendee',
            'etn_attendee_exclude_from_search' => true,
        ];

        if ( !empty( $etn_attendee_singular_name ) ) {
            $options['etn_attendee_singular_name'] = $etn_attendee_singular_name;
        }

        if ( !empty( $etn_attendee_archive ) ) {
            $options['etn_attendee_archive'] = $etn_attendee_archive;
        }

        if ( !empty($etn_attendee_all) ) {
            $options['etn_attendee_all'] = $etn_attendee_all;
        }

        if ( !empty( $attendee_slug ) ) {
            $options['attendee_slug'] = str_replace( ' ', '_', $attendee_slug );
        }

        return $options;
    }

}
