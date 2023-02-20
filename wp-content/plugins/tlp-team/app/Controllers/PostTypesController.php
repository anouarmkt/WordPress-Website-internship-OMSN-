<?php
/**
 * Custom Post Type Register Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Custom Post Type Register Class.
 */
class PostTypesController {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Post Type Slug.
	 *
	 * @var string
	 */
	private $post_type_slug;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$settings = get_option( rttlp_team()->options['settings'] );

		$this->post_type_slug = isset( $settings['slug'] ) ? ( $settings['slug'] ? sanitize_title_with_dashes( $settings['slug'] ) : 'team' ) : 'team';

		$this->post_types()->taxonomies();
	}

	/**
	 * Post Type Definition.
	 *
	 * @return Object
	 */
	protected function post_types() {
		if ( empty( $this->post_type_slug ) ) {
			return $this;
		}

		$post_types = $this->post_type_args();

		if ( empty( $post_types ) ) {
			return $this;
		}

		foreach ( $post_types as $post_type => $args ) {
			\register_post_type( $post_type, $args );
		}

		return $this;
	}

	/**
	 * Taxonomy Definition.
	 *
	 * @return void
	 */
	protected function taxonomies() {
		$taxonomies = $this->taxonomy_args();

		if ( empty( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $taxonomy => $args ) {
			\register_taxonomy( rttlp_team()->taxonomies[ $taxonomy ], [ rttlp_team()->post_type ], $args );
		}
	}

	/**
	 * Post Type Arguments.
	 *
	 * @return array
	 */
	private function post_type_args() {
		$args = [];

		/**
		 * Post Type: Team.
		 */
		$args[ rttlp_team()->post_type ] = [
			'label'               => esc_html__( 'Team', 'tlp-team' ),
			'description'         => esc_html__( 'Member', 'tlp-team' ),
			'supports'            => [ 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'rewrite'             => [ 'slug' => $this->post_type_slug ],
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 23,
			'menu_icon'           => rttlp_team()->assets_url() . 'images/team.png',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		];

		$args[ rttlp_team()->post_type ]['labels'] = [
			'name'               => esc_html__( 'Team', 'tlp-team' ),
			'singular_name'      => esc_html__( 'Member', 'tlp-team' ),
			'menu_name'          => esc_html__( 'Team', 'tlp-team' ),
			'name_admin_bar'     => esc_html__( 'Member', 'tlp-team' ),
			'parent_item_colon'  => esc_html__( 'Parent Member:', 'tlp-team' ),
			'all_items'          => esc_html__( 'All Members', 'tlp-team' ),
			'add_new_item'       => esc_html__( 'Add New Member', 'tlp-team' ),
			'add_new'            => esc_html__( 'Add Member', 'tlp-team' ),
			'new_item'           => esc_html__( 'New Member', 'tlp-team' ),
			'edit_item'          => esc_html__( 'Edit Member', 'tlp-team' ),
			'update_item'        => esc_html__( 'Update Member', 'tlp-team' ),
			'view_item'          => esc_html__( 'View Member', 'tlp-team' ),
			'search_items'       => esc_html__( 'Search Member', 'tlp-team' ),
			'not_found'          => esc_html__( 'Not found', 'tlp-team' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', 'tlp-team' ),
		];

		$settings = get_option( rttlp_team()->options['settings'] );

		if ( isset( $settings['detail_allow_comments'] ) && $settings['detail_allow_comments'] ) {
			$args[ rttlp_team()->post_type ]['supports'][] = 'comments';
		}

		/**
		 * Post Type: Shortcodes.
		 */
		$args[ rttlp_team()->shortCodePT ] = [
			'label'               => esc_html__( 'Shortcode', 'tlp-team' ),
			'description'         => esc_html__( 'Team Shortcode generator', 'tlp-team' ),
			'supports'            => [ 'title' ],
			'public'              => false,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . rttlp_team()->post_type,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		];

		$args[ rttlp_team()->shortCodePT ]['labels'] = [
			'all_items'          => esc_html__( 'Shortcode Generator', 'tlp-team' ),
			'menu_name'          => esc_html__( 'Shortcode', 'tlp-team' ),
			'singular_name'      => esc_html__( 'Shortcode', 'tlp-team' ),
			'edit_item'          => esc_html__( 'Edit Shortcode', 'tlp-team' ),
			'new_item'           => esc_html__( 'New Shortcode', 'tlp-team' ),
			'view_item'          => esc_html__( 'View Shortcode', 'tlp-team' ),
			'search_items'       => esc_html__( 'Shortcode Locations', 'tlp-team' ),
			'not_found'          => esc_html__( 'No Shortcode found.', 'tlp-team' ),
			'not_found_in_trash' => esc_html__( 'No Shortcode found in trash.', 'tlp-team' ),
		];

		return $args;
	}

	/**
	 * Taxonomy Arguments.
	 *
	 * @return array
	 */
	private function taxonomy_args() {
		$args = [];

		/**
		 * Taxonomy: Designation.
		 */
		$args['designation'] = [
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		];

		$args['designation']['labels'] = [
			'name'                       => esc_html__( 'Designations', 'tlp-team' ),
			'singular_name'              => esc_html__( 'Designation', 'tlp-team' ),
			'menu_name'                  => esc_html__( 'Designations', 'tlp-team' ),
			'all_items'                  => esc_html__( 'All Designation', 'tlp-team' ),
			'parent_item'                => esc_html__( 'Parent Designation', 'tlp-team' ),
			'parent_item_colon'          => esc_html__( 'Parent Designation:', 'tlp-team' ),
			'new_item_name'              => esc_html__( 'New Designation Name', 'tlp-team' ),
			'add_new_item'               => esc_html__( 'Add New Designation', 'tlp-team' ),
			'edit_item'                  => esc_html__( 'Edit Designation', 'tlp-team' ),
			'update_item'                => esc_html__( 'Update Designation', 'tlp-team' ),
			'view_item'                  => esc_html__( 'View Designation', 'tlp-team' ),
			'separate_items_with_commas' => esc_html__( 'Separate Designations with commas', 'tlp-team' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Designations', 'tlp-team' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'tlp-team' ),
			'popular_items'              => esc_html__( 'Popular Designations', 'tlp-team' ),
			'search_items'               => esc_html__( 'Search Designations', 'tlp-team' ),
			'not_found'                  => esc_html__( 'Not Found', 'tlp-team' ),
		];

		/**
		 * Taxonomy: Department.
		 */
		$args['department'] = [
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		];

		$args['department']['labels'] = [
			'name'                       => esc_html__( 'Department', 'tlp-team' ),
			'singular_name'              => esc_html__( 'Department', 'tlp-team' ),
			'menu_name'                  => esc_html__( 'Departments', 'tlp-team' ),
			'all_items'                  => esc_html__( 'All Department', 'tlp-team' ),
			'parent_item'                => esc_html__( 'Parent Department', 'tlp-team' ),
			'parent_item_colon'          => esc_html__( 'Parent Department', 'tlp-team' ),
			'new_item_name'              => esc_html__( 'New Department Name', 'tlp-team' ),
			'add_new_item'               => esc_html__( 'Add New Department', 'tlp-team' ),
			'edit_item'                  => esc_html__( 'Edit Department', 'tlp-team' ),
			'update_item'                => esc_html__( 'Update Department', 'tlp-team' ),
			'view_item'                  => esc_html__( 'View Department', 'tlp-team' ),
			'separate_items_with_commas' => esc_html__( 'Separate Skills with commas', 'tlp-team' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Skills', 'tlp-team' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'tlp-team' ),
			'popular_items'              => esc_html__( 'Popular Departments', 'tlp-team' ),
			'search_items'               => esc_html__( 'Search Departments', 'tlp-team' ),
			'not_found'                  => esc_html__( 'Not Found', 'tlp-team' ),
		];

		return $args;
	}
}
