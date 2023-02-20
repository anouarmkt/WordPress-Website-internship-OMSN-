<?php

namespace Etn\Core\Zoom_Meeting;

use Etn\Traits\Singleton;

defined('ABSPATH') || exit;

class Cpt extends \Etn\Base\Cpt{
    use Singleton;
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function create_cpt(){
        if( !is_admin() ){
            return true;
        }

        if( current_user_can( 'manage_etn_zoom' )){
            return true;
        }
        
        return false;
    }

    // set custom post type name
    public function get_name(){
        return 'etn-zoom-meeting';
    }
  
    // set custom post type options data
    public function post_type(){
        $options = $this->user_modifiable_option();
        $labels = array(
            'name'                  => esc_html_x('Zoom', 'Post Type General Name', 'eventin'),
            'singular_name'         => $options['etn_zoom_meeting_singular_name'],
            'menu_name'             => esc_html__('Add New Meeting', 'eventin'),
            'name_admin_bar'        => esc_html__('Zoom Meeting', 'eventin'),
            'attributes'            => esc_html__('Zoom Meeting Attributes', 'eventin'),
            'parent_item_colon'     => esc_html__('Parent Item:', 'eventin'),
            'all_items'             => $options['etn_zoom_meeting_all'],
            'add_new_item'          => esc_html__('Add New Zoom', 'eventin'),
            'add_new'               => esc_html__('Add New', 'eventin'),
            'new_item'              => esc_html__('New Zoom', 'eventin'),
            'edit_item'             => esc_html__('Edit Zoom', 'eventin'),
            'update_item'           => esc_html__('Update Zoom', 'eventin'),
            'view_item'             => esc_html__('View Zoom Meeting', 'eventin'),
            'view_items'            => esc_html__('View Zoom Meeting', 'eventin'),
            'search_items'          => esc_html__('Search Zoom Meeting', 'eventin'),
            'not_found'             => esc_html__('Not Found', 'eventin'),
            'not_found_in_trash'    => esc_html__('Not Found in Trash', 'eventin'),
            'featured_image'        => esc_html__('Featured Image', 'eventin'),
            'set_featured_image'    => esc_html__('Set Featured Image', 'eventin'),
            'remove_featured_image' => esc_html__('Remove Featured Image', 'eventin'),
            'use_featured_image'    => esc_html__('Use as Featured Image', 'eventin'),
            'insert_into_item'      => esc_html__('Insert into Zoom Meeting', 'eventin'),
            'uploaded_to_this_item' => esc_html__('Uploaded to This Zoom Meeting', 'eventin'),
            'items_list'            => esc_html__('Zoom Meeting List', 'eventin'),
            'items_list_navigation' => esc_html__('Zoom Meeting List navigation', 'eventin'),
            'filter_items_list'     => esc_html__('Filter Froms List', 'eventin'),
        );
        $rewrite = array(
            'slug'                  => apply_filters('etn_zoom_meeting_slug', $options['etn_zoom_meeting_slug']),
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => false,
        );
        $args = array(
            'label'                 => esc_html__('Zoom meeting', 'eventin'),
            'description'           => esc_html__('Zoom meeting', 'eventin'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'menu_icon'             => 'dashicons-text-page',
            'menu_position'         => 1,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'query_var'             => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => $this->get_name(),
        );

        if( current_user_can( 'manage_etn_zoom' ) ){
            $args['show_in_menu']          = 'etn-events-manager';
        }
        return $args;
    }

    // Operation custom post type
    public function flush_rewrites(){
        $name = $this->get_name();
        $args = $this->post_type();
        register_post_type($name, $args);
        flush_rewrite_rules();
    }

    private function user_modifiable_option(){
        $settings_options = get_option('etn_zoom_meeting_options');

        $options = [
            'etn_zoom_meeting_singular_name' => 'Zoom meeting',
            'etn_zoom_meeting_all'            => 'Zoom',
            'etn_zoom_meeting_slug'           => 'etn-zoom-meeting',
            'etn_zoom_meeting_exclude_from_search' => true
        ];

        if (isset($settings_options['etn_zoom_meeting_singular_name']) && $settings_options['etn_zoom_meeting_singular_name'] != '') {
            $options['etn_zoom_meeting_singular_name'] = $settings_options['etn_zoom_meeting_singular_name'];
        }
        if (isset($settings_options['etn_zoom_meeting_all']) && $settings_options['etn_zoom_meeting_all'] != '') {
            $options['etn_zoom_meeting_all'] = $settings_options['etn_zoom_meeting_all'];
        }
        if (isset($settings_options['etn_zoom_meeting_slug']) && $settings_options['etn_zoom_meeting_slug'] != '') {
            $options['etn_zoom_meeting_slug'] = $settings_options['etn_zoom_meeting_slug'];
        }
        if (isset($settings_options['etn_zoom_meeting_exclude_from_search']) && $settings_options['etn_zoom_meeting_exclude_from_search'] != '') {
            $options['etn_zoom_meeting_exclude_from_search'] = (bool) $settings_options['etn_zoom_meeting_exclude_from_search'];
        }

        return $options;
    }
}
