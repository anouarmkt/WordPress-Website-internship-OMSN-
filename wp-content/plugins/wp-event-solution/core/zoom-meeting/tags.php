<?php

namespace Etn\Core\Zoom_Meeting;

use Etn\Traits\Singleton;

defined('ABSPATH') || exit;
/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Tags extends \Etn\Base\Taxonomy{

    use Singleton;
    
    // set custom post type name
    public function get_name(){
        return 'etn_zoom_meeting_tags';
    }

    public function get_cpt(){
        return 'etn-zoom-meeting';
    }

    // Operation custom post type
    public function flush_rewrites()
    {
    }

    function taxonomy(){
        $labels = array(
            'name'              => esc_html__('Tags', 'eventin'),
            'singular_name'     => esc_html__('Tags', 'eventin'),
            'search_items'      => esc_html__('Search Tags', 'eventin'),
            'all_items'         => esc_html__('All Tags', 'eventin'),
            'parent_item'       => esc_html__('Parent Tags', 'eventin'),
            'parent_item_colon' => esc_html__('Parent Tags:', 'eventin'),
            'edit_item'         => esc_html__('Edit Tags', 'eventin'),
            'update_item'       => esc_html__('Update Tags', 'eventin'),
            'add_new_item'      => esc_html__('Add New Tags', 'eventin'),
            'new_item_name'     => esc_html__('New Tags Name', 'eventin'),
            'menu_name'         => esc_html__('Tags', 'eventin'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'show_in_rest'        => true,

            'rewrite'           => array('slug' => 'etn-zoom-tags'),
        );

        return $args;
    }
}
