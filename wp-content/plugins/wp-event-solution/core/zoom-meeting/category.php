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
class Category extends \Etn\Base\Taxonomy{
    use Singleton;
    // set custom post type name
    public function get_name(){
        return 'etn_zoom_meeting_category';
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
            'name'              => esc_html__('Category', 'eventin'),
            'singular_name'     => esc_html__('Category', 'eventin'),
            'search_items'      => esc_html__('Search Category', 'eventin'),
            'all_items'         => esc_html__('All Category', 'eventin'),
            'parent_item'       => esc_html__('Parent Category', 'eventin'),
            'parent_item_colon' => esc_html__('Parent Category:', 'eventin'),
            'edit_item'         => esc_html__('Edit Category', 'eventin'),
            'update_item'       => esc_html__('Update Category', 'eventin'),
            'add_new_item'      => esc_html__('Add New Category', 'eventin'),
            'new_item_name'     => esc_html__('New Category Name', 'eventin'),
            'menu_name'         => esc_html__('Category', 'eventin'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_menu'      => 'etn-events-manager',
            'query_var'         => true,
            'show_in_rest'        => true,

            'rewrite'           => array('slug' => 'etn-zoom-category'),
        );

        return $args;
    }
}
