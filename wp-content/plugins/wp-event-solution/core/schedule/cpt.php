<?php

namespace Etn\Core\Schedule;

defined( 'ABSPATH' ) || exit;

class Cpt extends \Etn\Base\Cpt {

    /**
     * Undocumented function
     *
     * @return void
     */
    public function create_cpt(){

        if( !is_admin() ){
            return true;
         }
 
         if( current_user_can( 'manage_etn_schedule' )){
             return true;
         }
         
         return false;
    }

    // set custom post type name
    public function get_name() {
        return 'etn-schedule';
    }

    // set custom post type options data
    public function post_type() {
        $options = $this->user_modifiable_option();
        $labels  = [
            'name'                  => esc_html_x( 'Schedule', 'Post Type General Name', 'eventin' ),
            'singular_name'         => $options['etn_schedule_singular_name'],
            'menu_name'             => esc_html__( 'Schedule', 'eventin' ),
            'name_admin_bar'        => esc_html__( 'Schedule', 'eventin' ),
            'attributes'            => esc_html__( 'Schedule Attributes', 'eventin' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'eventin' ),
            'all_items'             => $options['etn_schedule_all'],
            'add_new_item'          => esc_html__( 'Add New Schedule', 'eventin' ),
            'add_new'               => esc_html__( 'Add New', 'eventin' ),
            'new_item'              => esc_html__( 'New Schedule', 'eventin' ),
            'edit_item'             => esc_html__( 'Edit Schedule', 'eventin' ),
            'update_item'           => esc_html__( 'Update Schedule', 'eventin' ),
            'view_item'             => esc_html__( 'View Schedule', 'eventin' ),
            'view_items'            => esc_html__( 'View Schedules', 'eventin' ),
            'search_items'          => esc_html__( 'Search Schedules', 'eventin' ),
            'not_found'             => esc_html__( 'Not found', 'eventin' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'eventin' ),
            'featured_image'        => esc_html__( 'Featured Image', 'eventin' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'eventin' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'eventin' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'eventin' ),
            'insert_into_item'      => esc_html__( 'Insert into Schedule', 'eventin' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Schedule', 'eventin' ),
            'items_list'            => esc_html__( 'Schedules list', 'eventin' ),
            'items_list_navigation' => esc_html__( 'Schedules list navigation', 'eventin' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'eventin' ),
        ];
        $rewrite = [
            'slug'       => apply_filters( 'etn_schedule_slug', $options['etn_schedule_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];
        $args = [
            'label'               => esc_html__( 'Schedules', 'eventin' ),
            'description'         => esc_html__( 'Schedule', 'eventin' ),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 1,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),
        ];

        if( current_user_can( 'manage_etn_schedule' ) ){
            $args['show_in_menu']        = 'etn-events-manager';
        }

        return $args;
    }

    // Operation custom post type
    public function flush_rewrites() {
        $name = $this->get_name();
        $args = $this->post_type();
        register_post_type( $name, $args );
        flush_rewrite_rules();
    }

    private function user_modifiable_option() {
        $settings_options = get_option( 'etn_schedule_options' );

        $options = [
            'etn_schedule_singular_name'       => esc_html__('Schedule', 'eventin'),
            'etn_schedule_all'                 => esc_html__('Schedules', 'eventin'),
            'etn_schedule_slug'                => 'etn-schedule',
            'etn_schedule_exclude_from_search' => true,
        ];

        if ( isset( $settings_options['etn_schedule_singular_name'] ) && $settings_options['etn_schedule_singular_name'] != '' ) {
            $options['etn_schedule_singular_name'] = $settings_options['etn_schedule_singular_name'];
        }

        if ( isset( $settings_options['etn_schedule_all'] ) && $settings_options['etn_schedule_all'] != '' ) {
            $options['etn_schedule_all'] = $settings_options['etn_schedule_all'];
        }

        if ( isset( $settings_options['etn_schedule_slug'] ) && $settings_options['etn_schedule_slug'] != '' ) {
            $options['etn_schedule_slug'] = $settings_options['etn_schedule_slug'];
        }

        if ( isset( $settings_options['etn_schedule_exclude_from_search'] ) && $settings_options['etn_schedule_exclude_from_search'] != '' ) {
            $options['etn_schedule_exclude_from_search'] = (bool) $settings_options['etn_schedule_exclude_from_search'];
        }

        return $options;
    }

}
