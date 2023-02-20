<?php

namespace Etn\Core\Speaker;

defined( 'ABSPATH' ) || exit;

/**
 * Cpt Class.
 * Cpt class for custom post type of Speaker.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */
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

        if( current_user_can( 'manage_etn_speaker' )){
            return true;
        }
        
        return false;
    }

    // set custom post type name
    public function get_name() {
        return 'etn-speaker';
    }

    // set custom post type options data
    public function post_type() {
        $options = $this->user_modifiable_option();
        $labels  = [
            'name'                  => esc_html_x( 'Speaker', 'Post Type General Name', 'eventin' ),
            'singular_name'         => $options['etn_speaker_singular_name'],
            'menu_name'             => esc_html__( 'Speaker', 'eventin' ),
            'name_admin_bar'        => esc_html__( 'Speaker', 'eventin' ),
            'archives'              => $options['etn_speaker_archive'],
            'attributes'            => esc_html__( 'Speaker Attributes', 'eventin' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'eventin' ),
            'all_items'             => $options['etn_speaker_all'],
            'add_new_item'          => esc_html__( 'Add New Speaker', 'eventin' ),
            'add_new'               => esc_html__( 'Add New', 'eventin' ),
            'new_item'              => esc_html__( 'New Speaker', 'eventin' ),
            'edit_item'             => esc_html__( 'Edit Speaker', 'eventin' ),
            'update_item'           => esc_html__( 'Update Speaker', 'eventin' ),
            'view_item'             => esc_html__( 'View Speaker', 'eventin' ),
            'view_items'            => esc_html__( 'View Speakera', 'eventin' ),
            'search_items'          => esc_html__( 'Search Speakers', 'eventin' ),
            'not_found'             => esc_html__( 'Not found', 'eventin' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'eventin' ),
            'featured_image'        => esc_html__( 'Featured Image', 'eventin' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'eventin' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'eventin' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'eventin' ),
            'insert_into_item'      => esc_html__( 'Insert into Speaker', 'eventin' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Speaker', 'eventin' ),
            'items_list'            => esc_html__( 'Speakers list', 'eventin' ),
            'items_list_navigation' => esc_html__( 'Speakers list navigation', 'eventin' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'eventin' ),
        ];
        $rewrite = [
            'slug'       => apply_filters( 'speaker_slug', $options['speaker_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];
        $args = [
            'label'               => esc_html__( 'Speakers', 'eventin' ),
            'description'         => esc_html__( 'Speaker', 'eventin' ),
            'labels'              => $labels,
            'supports'            => [ 'thumbnail' ],
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
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
        ];

        if( current_user_can( 'manage_etn_speaker' ) ){
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
        $settings_options = get_option( 'etn_event_options' );

        $options = [
            'etn_speaker_singular_name'       => esc_html__('Speaker', 'eventin' ),
            'etn_speaker_archive'             => esc_html__('Speaker Archive', 'eventin' ),
            'etn_speaker_all'                 => esc_html__('Speakers', 'eventin' ),
            'speaker_slug'                    => 'etn-speaker',
            'etn_speaker_exclude_from_search' => true,
        ];

        if ( isset( $settings_options['etn_speaker_singular_name'] ) && $settings_options['etn_speaker_singular_name'] != '' ) {
            $options['etn_speaker_singular_name'] = $settings_options['etn_speaker_singular_name'];
        }

        if ( isset( $settings_options['etn_speaker_archive'] ) && $settings_options['etn_speaker_archive'] != '' ) {
            $options['etn_speaker_archive'] = $settings_options['etn_speaker_archive'];
        }

        if ( isset( $settings_options['etn_speaker_all'] ) && $settings_options['etn_speaker_all'] != '' ) {
            $options['etn_speaker_all'] = $settings_options['etn_speaker_all'];
        }

        if ( isset( $settings_options['speaker_slug'] ) && $settings_options['speaker_slug'] != '' ) {
            $options['speaker_slug'] = str_replace( ' ', '_' , $settings_options['speaker_slug'] );
        }

        if ( isset( $settings_options['etn_speaker_exclude_from_search'] ) && $settings_options['etn_speaker_exclude_from_search'] != '' ) {
            $options['etn_speaker_exclude_from_search'] = (bool) $settings_options['etn_speaker_exclude_from_search'];
        }
        return $options;
    }

}
