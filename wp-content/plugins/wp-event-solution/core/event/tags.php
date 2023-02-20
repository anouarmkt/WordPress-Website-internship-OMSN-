<?php

namespace Etn\Core\Event;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Tags extends \Etn\Base\Taxonomy {

    // set custom post type name
    public function get_name() {
        return 'etn_tags';
    }

    public function get_cpt() {
        return 'etn';
    }

    // Operation custom post type
    public function flush_rewrites() {
    }

    function taxonomy() {
        $labels = [
            'name'              => esc_html__( 'Tags', 'eventin' ),
            'singular_name'     => esc_html__( 'Tags', 'eventin' ),
            'search_items'      => esc_html__( 'Search Tags', 'eventin' ),
            'all_items'         => esc_html__( 'All Tags', 'eventin' ),
            'parent_item'       => esc_html__( 'Parent Tags', 'eventin' ),
            'parent_item_colon' => esc_html__( 'Parent Tags:', 'eventin' ),
            'edit_item'         => esc_html__( 'Edit Tags', 'eventin' ),
            'update_item'       => esc_html__( 'Update Tags', 'eventin' ),
            'add_new_item'      => esc_html__( 'Add New Tags', 'eventin' ),
            'new_item_name'     => esc_html__( 'New Tags Name', 'eventin' ),
            'menu_name'         => esc_html__( 'Tags', 'eventin' ),
        ];

        $args = [
            'hierarchical'      => true,
            'has_archive'       => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'show_in_rest'      => true,

            'rewrite'           => ['slug' => 'etn-tags'],

        ];

        return $args;
    }

    /**
     * Create page
     *
     * @param string $title_of_the_page
     * @param string $content
     * @param [type] $parent_id
     * @return void
     */
    public function create_page() {
        $page_id = Helper::create_page( 'etn-tags', '', null, "-" );

        return $page_id;

    }

    public function menu() {

        $parent = 'etn-events-manager';
        $name   = $this->get_name();
        $cpt    = $this->get_cpt();
        add_action( 'admin_menu', function () use ( $parent, $name, $cpt ) {

            if ( current_user_can( 'manage_etn_event' ) ) {
                add_submenu_page(
                    $parent,
                    esc_html__( 'Event Tags', 'eventin' ),
                    esc_html__( 'Event Tags', 'eventin' ),
                    'manage_categories',
                    'edit-tags.php?taxonomy=' . $name . '&post_type=' . $cpt,
                    false,
                    2
                );
            }

        } );

    }

}
