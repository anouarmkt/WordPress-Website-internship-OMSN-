<?php
/**
 * Options helper class.
 *
 * @package RT_Team
 */

namespace RT\Team\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Options helper class.
 */
class Options {
	public static function lan() {
		return [
			'of' => esc_html__( 'of', 'tlp-team' ),
		];
	}

	public static function scColumns() {
		return [
			1 => esc_html__( '1 Column', 'tlp-team' ),
			2 => esc_html__( '2 Column', 'tlp-team' ),
			3 => esc_html__( '3 Column', 'tlp-team' ),
			4 => esc_html__( '4 Column', 'tlp-team' ),
			5 => esc_html__( '5 Column', 'tlp-team' ),
			6 => esc_html__( '6 Column', 'tlp-team' ),
		];
	}

	public static function scLayout() {
		$layouts = [
			'layout1'       => esc_html__( 'Layout 1', 'tlp-team' ),
			'layout2'       => esc_html__( 'Layout 2', 'tlp-team' ),
			'layout3'       => esc_html__( 'Layout 3', 'tlp-team' ),
			'layout4'       => esc_html__( 'Layout 4', 'tlp-team' ),
			'layout-el-4'   => esc_html__( 'Layout 4', 'tlp-team' ),
			'isotope-free'  => esc_html__( 'Isotope Layout', 'tlp-team' ),
			'carousel1'     => esc_html__( 'Carousel (Slider Layout)', 'tlp-team' ),
			'carousel-el-1' => esc_html__( 'Carousel (Slider Layout)', 'tlp-team' ),
		];

		return apply_filters( 'rttm_team_layouts', $layouts );
	}

	public static function paginationType() {
		$paginationType = [
			'pagination'      => esc_html__( 'Pagination', 'tlp-team' ),
			'pagination_ajax' => esc_html__( 'Ajax Number Pagination ( Only for Grid )', 'tlp-team' ),
			'load_more'       => esc_html__( 'Load more button (by ajax loading)', 'tlp-team' ),
			'load_on_scroll'  => esc_html__( 'Load more on scroll (by ajax loading)', 'tlp-team' ),
		];

		return apply_filters( 'tlp_pagination_type', $paginationType );
	}

	public static function teamMemberInfoField() {
		$default = [
			'short_bio'             => [
				'label'       => esc_html__( 'Short Bio', 'tlp-team' ),
				'type'        => 'textarea',
				'attr'        => 'rows="5"',
				'description' => esc_html__( 'Add some short bio', 'tlp-team' ),
			],
			'experience_year'       => [
				'label'       => esc_html__( 'Experience', 'tlp-team' ),
				'type'        => 'text',
				'is_pro'      => true,
				'description' => esc_html__( 'ex: 4 Years', 'tlp-team' ),
			],
			'email'                 => [
				'label' => esc_html__( 'Email', 'tlp-team' ),
				'type'  => 'email',
			],
			'telephone'             => [
				'label' => esc_html__( 'Telephone', 'tlp-team' ),
				'type'  => 'text',
			],
			'mobile'                => [
				'label' => esc_html__( 'Mobile', 'tlp-team' ),
				'type'  => 'text',
			],
			'fax'                   => [
				'label' => esc_html__( 'Fax', 'tlp-team' ),
				'type'  => 'text',
			],
			'web_url'               => [
				'label' => esc_html__( 'Personal Web URL', 'tlp-team' ),
				'type'  => 'url',
			],
			'location'              => [
				'label' => esc_html__( 'Location', 'tlp-team' ),
				'type'  => 'text',
			],
			'ttp_custom_detail_url' => [
				'label'       => esc_html__( 'Custom Detail URL', 'tlp-team' ),
				'type'        => 'url',
				'is_pro'      => true,
				'description' => esc_html__( 'Add your custom URl for detail profile', 'tlp-team' ),
			],
		];

		return apply_filters( 'rttm_member_info_fields', $default );
	}

	public static function socialLink() {
		$socialLinks = [
			'facebook'   => esc_html__( 'Facebook', 'tlp-team' ),
			'twitter'    => esc_html__( 'Twitter', 'tlp-team' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'tlp-team' ),
			'youtube'    => esc_html__( 'Youtube', 'tlp-team' ),
			'instagram'  => esc_html__( 'Instagram', 'tlp-team' ),
			'pinterest'  => esc_html__( 'Pinterest', 'tlp-team' ),
			'soundcloud' => esc_html__( 'Soundcloud', 'tlp-team' ),
			'bandcamp'   => esc_html__( 'Bandcamp', 'tlp-team' ),
			'envelope-o' => esc_html__( 'Email', 'tlp-team' ),
			'globe'      => esc_html__( 'Website', 'tlp-team' ),
			'xing'       => esc_html__( 'Xing', 'tlp-team' ),
		];

		return apply_filters( 'tlp_team_social_links', $socialLinks );
	}

	public static function tlpOverlayBg() {
		return [
			'0.1' => esc_html__( '10 %', 'tlp-team' ),
			'0.2' => esc_html__( '20 %', 'tlp-team' ),
			'0.3' => esc_html__( '30 %', 'tlp-team' ),
			'0.4' => esc_html__( '40 %', 'tlp-team' ),
			'0.5' => esc_html__( '50 %', 'tlp-team' ),
			'0.6' => esc_html__( '60 %', 'tlp-team' ),
			'0.7' => esc_html__( '70 %', 'tlp-team' ),
			'0.8' => esc_html__( '80 %', 'tlp-team' ),
			'0.9' => esc_html__( '90 %', 'tlp-team' ),
		];
	}

	public static function scAvailableFields() {

		$sc_avaiable_fiels = [
			'name'        => esc_html__( 'Name', 'tlp-team' ),
			'designation' => esc_html__( 'Designation', 'tlp-team' ),
			'short_bio'   => esc_html__( 'Short biography', 'tlp-team' ),
			'content'     => esc_html__( 'Content Details', 'tlp-team' ),
			'email'       => esc_html__( 'Email', 'tlp-team' ),
			'web_url'     => esc_html__( 'Web Url', 'tlp-team' ),
			'telephone'   => esc_html__( 'Telephone', 'tlp-team' ),
			'mobile'      => esc_html__( 'Mobile', 'tlp-team' ),
			'fax'         => esc_html__( 'Fax', 'tlp-team' ),
			'location'    => esc_html__( 'Location', 'tlp-team' ),
			'social'      => esc_html__( 'Social Link', 'tlp-team' ),
		];

		return apply_filters( 'rttm_sc_avaiable_fiels', $sc_avaiable_fiels );
	}

	public static function get_sc_field_selection_meta() {
		return [
			'ttp_selected_field' => [
				'label'       => esc_html__( 'Select the field', 'tlp-team' ),
				'alignment'   => 'vertical',
				'type'        => 'checkbox',
				'holderClass' => 'rttm-selected-field',
				'multiple'    => true,
				'default'     => array_keys( self::scAvailableFields() ),
				'options'     => self::scAvailableFields(),
				'description' => esc_html__( 'Check the field which you want to display. Note: Some field are not available for some layout', 'tlp-team' ),
			],
		];
	}

	public static function get_sc_layout_settings_meta_fields() {
		$rttm_layout_options = [
			'layout_type'                   => [
				'type'    => 'radio-image',
				'label'   => esc_html__( 'Layout type', 'tlp-team' ),
				'id'      => 'rttm-layout-type',
				'options' => [
					[
						'name'  => esc_html__( 'Grid Layout', 'tlp-team' ),
						'value' => 'grid',
						'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/grid.png',
					],
					[
						'name'  => esc_html__( 'List Layout', 'tlp-team' ),
						'value' => 'list',
						'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/list.png',
					],
					[
						'name'  => esc_html__( 'Slider Layout', 'tlp-team' ),
						'value' => 'slider',
						'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/slider.png',
					],
					[
						'name'  => esc_html__( 'Isotope Layout', 'tlp-team' ),
						'value' => 'isotope',
						'img'   => TLP_TEAM_PLUGIN_URL . '/assets/images/isotope.png',
					],
				],
			],
			'layout'                        => [
				'type'        => 'radio-image',
				'label'       => esc_html__( 'Layout style', 'tlp-team' ),
				'description' => esc_html__( 'Click to the Layout name to see live demo', 'tlp-team' ),
				'id'          => 'rttm-style',
				'options'     => [],
			],
			'ttp_column'                    => [
				'type'    => 'multiple_options',
				'label'   => esc_html__( 'Column', 'tlp-team' ),
				'options' => [
					'desktop' => [
						'type'    => 'select',
						'class'   => 'tlp-select',
						'label'   => esc_html__( 'Desktop', 'tlp-team' ),
						'options' => self::scColumns(),
						'default' => 4,
					],
					'tab'     => [
						'type'    => 'select',
						'class'   => 'tlp-select',
						'label'   => esc_html__( 'Tab', 'tlp-team' ),
						'options' => self::scColumns(),
						'default' => 2,
					],
					'mobile'  => [
						'type'    => 'select',
						'class'   => 'tlp-select',
						'label'   => esc_html__( 'Mobile', 'tlp-team' ),
						'options' => self::scColumns(),
						'default' => 1,
					],
				],
			],
			'ttl_image_column'              => [
				'type'        => 'select',
				'label'       => esc_html__( 'Image column', 'tlp-team' ),
				'class'       => 'tlp-select',
				'holderClass' => 'ttp-hidden',
				'default'     => 4,
				'options'     => self::scColumns(),
				'description' => esc_html__( 'Content column will calculate automatically', 'tlp-team' ),
			],
			'ttp_carousel_speed'            => [
				'label'       => __( 'Speed', 'tlp-team' ),
				'holderClass' => 'ttp-hidden ttp-carousel-item',
				'type'        => 'number',
				'default'     => 250,
				'description' => esc_html__( 'Auto play Speed in milliseconds', 'tlp-team' ),
			],
			'ttp_carousel_options'          => [
				'label'       => esc_html__( 'Carousel Options', 'tlp-team' ),
				'holderClass' => 'ttp-hidden ttp-carousel-item',
				'type'        => 'checkbox',
				'multiple'    => true,
				'alignment'   => 'vertical',
				'options'     => self::owlProperty(),
				'default'     => [ 'autoplay', 'arrows', 'dots', 'responsive', 'infinite' ],
			],
			'ttp_carousel_autoplay_timeout' => [
				'label'       => esc_html__( 'Autoplay timeout', 'tlp-team' ),
				'holderClass' => 'ttp-hidden ttp-carousel-item ttp-carousel-auto-play-timeout',
				'type'        => 'number',
				'default'     => 5000,
				'description' => esc_html__( 'Autoplay interval timeout', 'tlp-team' ),
			],
			'ttp_filter'                    => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Filter', 'tlp-team' ),
				'holderClass' => 'sc-ttp-grid-filter ttp-hidden',
				'multiple'    => true,
				'is_pro'      => true,
				'alignment'   => 'vertical',
				'options'     => self::ttp_filter_list(),
			],
			'ttp_filter_taxonomy'           => [
				'type'        => 'select',
				'label'       => esc_html__( 'Taxonomy Filter', 'tlp-team' ),
				'holderClass' => 'sc-ttp-grid-filter sc-ttp-filter-item ttp-hidden',
				'class'       => 'tlp-select',
				'is_pro'      => true,
				'options'     => Fns::rt_get_all_taxonomy_by_post_type(),
			],
			'ttp_pagination'                => [
				'type'        => 'switch',
				'label'       => esc_html__( 'Pagination', 'tlp-team' ),
				'holderClass' => 'ttp-pagination-item pagination ttp-hidden',
				'optionLabel' => esc_html__( 'Enable', 'tlp-team' ),
				'option'      => 1,
			],
			'ttp_pagination_type'           => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Pagination type', 'tlp-team' ),
				'holderClass' => 'ttp-pagination-item ttp-hidden',
				'alignment'   => 'vertical',
				'is_pro'      => true,
				'default'     => 'pagination',
				'options'     => self::paginationType(),
			],
			'ttp_posts_per_page'            => [
				'type'        => 'number',
				'label'       => esc_html__( 'Display per page', 'tlp-team' ),
				'holderClass' => 'ttp-pagination-item ttp-hidden',
				'default'     => 5,
				'description' => esc_html__( 'If value of Limit setting is not blank (empty), this value should be smaller than Limit value.', 'tlp-team' ),
			],
			'ttp_image'                     => [
				'type'        => 'switch',
				'label'       => esc_html__( 'Feature Image Disable', 'tlp-team' ),
				'optionLabel' => esc_html__( 'Disable', 'tlp-team' ),
				'option'      => 1,
			],
			'image_style'                   => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Image style', 'tlp-team' ),
				'alignment'   => 'vertical',
				'description' => sprintf(
					'%s <br> <strong>%s</strong> %s',
					esc_html__( 'Select image style for layout.', 'tlp-team' ),
					esc_html__( 'Note:', 'tlp-team' ),
					esc_html__( "Some layouts don't support rounded style.", 'tlp-team' )
				),
				'default'     => 'normal',
				'options'     => self::scImgStyle(),
			],
			'ttp_image_size'                => [
				'type'        => 'select',
				'label'       => esc_html__( 'Image Size', 'tlp-team' ),
				'class'       => 'tlp-select',
				'holderClass' => 'ttp-feature-image-option ttp-hidden',
				'options'     => Fns::get_image_sizes(),
			],
			'ttp_custom_image_size'         => [
				'type'        => 'image_size',
				'label'       => esc_html__( 'Custom Image Size', 'tlp-team' ),
				'holderClass' => 'ttp-feature-image-option ttp-hidden',
			],
			'character_limit'               => [
				'type'        => 'number',
				'label'       => esc_html__( 'Short description limit', 'tlp-team' ),
				'description' => sprintf(
					"%s<br> <span style='color: red;'><strong>%s</strong> %s</span>",
					esc_html__( 'Short description limit only integer number is allowed, Leave it blank for full text.', 'tlp-team' ),
					esc_html__( 'Note:', 'tlp-team' ),
					esc_html__( 'HTML TAGS will not work if you use limit.', 'tlp-team' )
				),
			],
			'ttp_after_short_desc_text'     => [
				'type'        => 'text',
				'label'       => esc_html__( 'After Short Description', 'tlp-team' ),
				'description' => esc_html__( 'Add something after short description.', 'tlp-team' ),
			],
			'ttp_detail_page_link'          => [
				'type'        => 'switch',
				'label'       => esc_html__( 'Detail page link', 'tlp-team' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-team' ),
				'default'     => 1,
				'option'      => 1,
			],
		];

		return apply_filters( 'rttm_layout_options', $rttm_layout_options );
	}

	public static function owlProperty() {
		$owlProperty = [
			'loop'               => esc_html__( 'Loop', 'tlp-team' ),
			'autoplay'           => esc_html__( 'Auto Play', 'tlp-team' ),
			'autoplayHoverPause' => esc_html__( 'Pause on mouse hover', 'tlp-team' ),
			'nav'                => esc_html__( 'Nav Button', 'tlp-team' ),
			'dots'               => esc_html__( 'Pagination', 'tlp-team' ),
			'auto_height'        => esc_html__( 'Auto Height', 'tlp-team' ),
			'lazy_load'          => esc_html__( 'Lazy Load', 'tlp-team' ),
			'rtl'                => esc_html__( 'Right to left (RTL)', 'tlp-team' ),
		];

		return apply_filters( 'tlp_owl_property', $owlProperty );
	}

	public static function swiperProperty() {
		$swiperProperty = [
			'loop'               => esc_html__( 'Loop', 'tlp-team' ),
			'autoplay'           => esc_html__( 'Auto Play', 'tlp-team' ),
			'autoplayHoverPause' => esc_html__( 'Pause on mouse hover', 'tlp-team' ),
			'nav'                => esc_html__( 'Nav Button', 'tlp-team' ),
			'dots'               => esc_html__( 'Pagination', 'tlp-team' ),
			'autoHeight'         => esc_html__( 'Auto Height', 'tlp-team' ),
			'lazyLoad'           => esc_html__( 'Lazy Load', 'tlp-team' ),
			'rtl'                => esc_html__( 'Right to left (RTL)', 'tlp-team' ),
		];

		return apply_filters( 'tlp_swiper_property', $swiperProperty );
	}

	public static function ttp_filter_list() {
		return [
			'_taxonomy_filter' => esc_html__( 'Taxonomy filter', 'tlp-team' ),
			'_order_by'        => esc_html__( 'Order - Sort retrieved posts by parameter', 'tlp-team' ),
			'_sort_order'      => esc_html__( 'Sort Order - Designates the ascending or descending order of the "orderby" parameter', 'tlp-team' ),
			'_search'          => esc_html__( 'Search filter', 'tlp-team' ),
		];
	}

	public static function scImgStyle() {
		return [
			'normal' => esc_html__( 'Normal', 'tlp-team' ),
			'round'  => esc_html__( 'Round', 'tlp-team' ),
		];
	}

	public static function get_sc_query_filter_meta_fields() {

		return [
			'ttp_post__in'          => [
				'label'       => esc_html__( 'Include only', 'tlp-team' ),
				'type'        => 'select',
				'class'       => 'rttm-select2',
				'description' => esc_html__( 'Select the member you want to display', 'tlp-team' ),
				'multiple'    => true,
				'options'     => Fns::getMemberList(),
			],
			'ttp_post__not_in'      => [
				'label'       => esc_html__( 'Exclude', 'tlp-team' ),
				'type'        => 'select',
				'class'       => 'rttm-select2',
				'description' => esc_html__( 'Select the member you want to hide', 'tlp-team' ),
				'multiple'    => true,
				'options'     => Fns::getMemberList(),
			],
			'ttp_limit'             => [
				'label'       => esc_html__( 'Limit', 'tlp-team' ),
				'type'        => 'number',
				'description' => esc_html__( 'The number of posts to show. Set empty to show all found posts.', 'tlp-team' ),
			],
			'ttp_departments'       => [
				'label'       => esc_html__( 'Departments', 'tlp-team' ),
				'type'        => 'select',
				'class'       => 'rttm-select2',
				'multiple'    => true,
				'description' => esc_html__( 'Select the department you want to filter, Leave it blank for all department', 'tlp-team' ),
				'options'     => Fns::getAllTermsByTaxonomyName( 'department' ),
			],
			'ttp_designations'      => [
				'label'       => esc_html__( 'Designations', 'tlp-team' ),
				'type'        => 'select',
				'class'       => 'rttm-select2',
				'multiple'    => true,
				'is_pro'      => true,
				'description' => esc_html__( 'Select the designation you want to filter, Leave it blank for all designation', 'tlp-team' ),
				'options'     => Fns::getAllTermsByTaxonomyName( 'designation' ),
			],
			'ttp_taxonomy_relation' => [
				'label'       => esc_html__( 'Taxonomy relation', 'tlp-team' ),
				'type'        => 'select',
				'is_pro'      => true,
				'class'       => 'tlp-select',
				'description' => esc_html__( 'Select this option if you select more than one taxonomy like department , designation and skill', 'tlp-team' ),
				'options'     => self::scTaxonomyRelation(),
			],
			'order_by'              => [
				'label'   => esc_html__( 'Order By', 'tlp-team' ),
				'type'    => 'select',
				'class'   => 'tlp-select',
				'default' => 'title',
				'options' => self::scOrderBy(),
			],
			'order'                 => [
				'label'     => esc_html__( 'Order', 'tlp-team' ),
				'type'      => 'radio',
				'options'   => self::scOrder(),
				'default'   => 'ASC',
				'alignment' => 'vertical',
			],
		];
	}

	public static function get_sc_field_style_meta() {
		$style_fields = [
			'ttp_parent_class' => [
				'type'        => 'text',
				'label'       => 'Parent class',
				'class'       => 'medium-text',
				'description' => esc_html__( 'Parent class for adding custom css', 'tlp-team' ),
			],
			'primary_color'    => [
				'type'    => 'text',
				'label'   => esc_html__( 'Primary Color', 'tlp-team' ),
				'class'   => 'tlp-color',
				'default' => '#0367bf',
			],
			'ttp_button_style' => [
				'type'    => 'multiple_options',
				'label'   => esc_html__( 'Button color', 'tlp-team' ),
				'options' => [
					'bg'         => [
						'type'  => 'color',
						'label' => esc_html__( 'Background', 'tlp-team' ),
					],
					'hover_bg'   => [
						'type'  => 'color',
						'label' => esc_html__( 'Hover background', 'tlp-team' ),
					],
					'active_bg'  => [
						'type'  => 'color',
						'label' => esc_html__( 'Active background', 'tlp-team' ),
					],
					'text'       => [
						'type'  => 'color',
						'label' => esc_html__( 'Text', 'tlp-team' ),
					],
					'hover_text' => [
						'type'  => 'color',
						'label' => esc_html__( 'Hover text', 'tlp-team' ),
					],
					'border'     => [
						'type'  => 'color',
						'label' => esc_html__( 'Border', 'tlp-team' ),
					],
				],
			],
			'name'             => [
				'type'    => 'multiple_options',
				'label'   => esc_html__( 'Name', 'tlp-team' ),
				'options' => self::scStyleOptions(),
			],
			'designation'      => [
				'type'    => 'multiple_options',
				'label'   => esc_html__( 'Designation', 'tlp-team' ),
				'options' => self::scStyleOptions(),
			],
			'short_bio'        => [
				'type'    => 'multiple_options',
				'label'   => esc_html__( 'Short biography', 'tlp-team' ),
				'options' => self::scStyleOptions(),
			],
		];

		return apply_filters( 'rttm_style_fields', $style_fields );
	}

	public static function scTaxonomyRelation() {
		return [
			'OR'  => esc_html__( 'OR Relation', 'tlp-team' ),
			'AND' => esc_html__( 'AND Relation', 'tlp-team' ),
		];
	}

	public static function scOrderBy() {
		return [
			'menu_order' => esc_html__( 'Menu Order', 'tlp-team' ),
			'title'      => esc_html__( 'Name', 'tlp-team' ),
			'ID'         => esc_html__( 'ID', 'tlp-team' ),
			'date'       => esc_html__( 'Date', 'tlp-team' ),
			'rand'       => esc_html__( 'Random', 'tlp-team' ),
		];
	}

	public static function scElOrderBy() {
		return [
			'menu_order' => esc_html__( 'Default Order', 'tlp-team' ),
			'ID'         => esc_html__( 'ID', 'tlp-team' ),
			'date'       => esc_html__( 'Date', 'tlp-team' ),
			'rand'       => esc_html__( 'Random', 'tlp-team' ),
			'none'       => esc_html__( 'Sort By None', 'tlp-team' ),
		];
	}

	public static function scOrder() {
		return [
			'ASC'  => esc_html__( 'Ascending', 'tlp-team' ),
			'DESC' => esc_html__( 'Descending', 'tlp-team' ),
		];
	}


	public static function imageCropType() {
		return [
			'soft' => esc_html__( 'Soft Crop', 'tlp-team' ),
			'hard' => esc_html__( 'Hard Crop', 'tlp-team' ),
		];
	}

	public static function colorSizeAlignmentWeight() {
		return array_keys( self::scAvailableFields() );
	}

	public static function tlpTeamDetailFieldSelection() {

		$settings = get_option( rttlp_team()->options['settings'] );

		return [
			'detail_page_wrapper'   => [
				'type'    => 'select',
				'label'   => esc_html__( 'Content type', 'tlp-team' ),
				'class'   => 'tlp-select',
				'is_pro'  => true,
				'options' => self::pageWrapperList(),
				'value'   => ! empty( $settings['detail_page_wrapper'] ) ? $settings['detail_page_wrapper'] : 'rt-container',
			],
			'detail_image_column'   => [
				'type'        => 'select',
				'label'       => esc_html__( 'Image column', 'tlp-team' ),
				'class'       => 'tlp-select',
				'is_pro'      => true,
				'options'     => self::scColumns(),
				'value'       => ! empty( $settings['detail_image_column'] ) ? $settings['detail_image_column'] : 5,
				'description' => esc_html__( 'Content column will calculate automatically', 'tlp-team' ),
			],
			'detail_page_fields'    => [
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Field Selection', 'tlp-team' ),
				'description' => esc_html__( 'This will apply only single team page', 'tlp-team' ),
				'alignment'   => 'vertical',
				'multiple'    => true,
				'options'     => self::detailAvailableFields(),
				'value'       => ! empty( $settings['detail_page_fields'] ) ? $settings['detail_page_fields'] : [
					'name',
					'designation',
					'short_bio',
					'email',
					'web_url',
					'telephone',
					'mobile',
					'fax',
					'location',
					'social',
				],
			],
			'detail_allow_comments' => [
				'type'        => 'switch',
				'label'       => esc_html__( 'Comments', 'tlp-team' ),
				'is_pro'      => true,
				'description' => esc_html__( 'Allow comments to team member details page', 'tlp-team' ),
				'optionLabel' => esc_html__( 'Enable', 'tlp-team' ),
				'option'      => 1,
				'value'       => ! empty( $settings['detail_allow_comments'] ) ? 1 : false,
			],
		];
	}

	public static function rtTeamLicenceField() {
		$settings       = get_option( rttlp_team()->options['settings'] );
		$status         = ! empty( $settings['license_status'] ) && $settings['license_status'] === 'valid' ? true : false;
		$license_status = ! empty( $settings['license_key'] ) ? sprintf(
			"<span class='license-status'>%s</span>",
			$status ? "<input type='submit' class='button-secondary rt-team-licensing-btn danger' name='license_deactivate' value='Deactivate License'/>"
				: "<input type='submit' class='button-secondary rt-team-licensing-btn button-primary' name='license_activate' value='Activate License'/>"
		) : ' ';

		return [
			'license_key' => [
				'type'            => 'text',
				'name'            => 'license_key',
				'attr'            => 'style="min-width:300px;"',
				'label'           => 'Enter your license key',
				'description_adv' => $license_status,
				'id'              => 'license_key',
				'value'           => isset( $settings['license_key'] ) ? esc_attr( $settings['license_key'] ) : '',
			],
		];
	}

	public static function getAllSettingOptions() {
		$options = self::tlpTeamGeneralSettingFields() + self::tlpTeamDetailFieldSelection();
		return apply_filters( 'rttm_settings_all_options', $options );
	}

	public static function tlpTeamGeneralSettingFields() {

		$settings = get_option( rttlp_team()->options['settings'] );

		return [
			'slug'                => [
				'type'        => 'text',
				'label'       => esc_html__( 'Slug', 'tlp-team' ),
				'id'          => 'team-slug',
				'description' => esc_html__( 'Slug configuration', 'tlp-team' ),
				'attr'        => "style='width:100px;'",
				'value'       => ! empty( $settings['slug'] ) ? trim( $settings['slug'] ) : null,
			],

			'tlp_team_block_type' => [
				'type'        => 'select',
				'name'        => 'tlp_team_block_type',
				'label'       => esc_html__( 'Conditional Scripts Loading', 'tlp-team' ),
				'id'          => 'tlp-team-block-type',
				'options'     => [
					'default'   => esc_html__( 'Load All Scripts (Both Elementor and Shortcode)', 'tlp-team' ),
					'elementor' => esc_html__( 'Load Only Elementor Scripts', 'tlp-team' ),
					'shortcode' => esc_html__( 'Load Only Shortcodes Scripts', 'tlp-team' ),
				],
				'description' => esc_html__( 'Please choose the script loading condition. Our recommendation is to choose any one of Elementor or Shorcodes for faster page loads.', 'tlp-team' ),
				'value'       => isset( $settings['tlp_team_block_type'] ) ? $settings['tlp_team_block_type'] : 'default',
			],
		];
	}

	public static function detailAvailableFields() {
		$fields = self::scAvailableFields();

		return apply_filters( 'rttm_settings_avaiable_fields', $fields );
	}

	public static function scStyleOptions( $items = [ 'color', 'hover_color', 'size', 'weight', 'align' ] ) {
		$fields = [];
		if ( in_array( 'color', $items ) ) {
			$fields['color'] = [
				'type'     => 'color',
				'col_size' => 4,
				'label'    => esc_html__( 'Color', 'tlp-team' ),
			];
		}
		if ( in_array( 'hover_color', $items ) ) {
			$fields['hover_color'] = [
				'type'     => 'color',
				'col_size' => 4,
				'label'    => esc_html__( 'Hover color', 'tlp-team' ),
			];
		}
		if ( in_array( 'size', $items ) ) {
			$fields['size'] = [
				'type'     => 'select',
				'label'    => esc_html__( 'Font size', 'tlp-team' ),
				'col_size' => 4,
				'class'    => 'tlp-select',
				'blank'    => esc_html__( 'Default', 'tlp-team' ),
				'options'  => self::scFontSize(),
			];
		}
		if ( in_array( 'weight', $items ) ) {
			$fields['weight'] = [
				'type'     => 'select',
				'label'    => esc_html__( 'Weight', 'tlp-team' ),
				'col_size' => 4,
				'class'    => 'tlp-select',
				'blank'    => esc_html__( 'Default', 'tlp-team' ),
				'options'  => self::scTextWeight(),
			];
		}
		if ( in_array( 'align', $items ) ) {
			$fields['align'] = [
				'type'     => 'select',
				'label'    => esc_html__( 'Alignment', 'tlp-team' ),
				'col_size' => 4,
				'blank'    => esc_html__( 'Default', 'tlp-team' ),
				'class'    => 'tlp-select',
				'options'  => self::scAlignment(),
			];
		}

		return $fields;
	}

	public static function pageWrapperList() {
		return [
			'rt-container'       => esc_html__( 'Container', 'tlp-team' ),
			'rt-container-fluid' => esc_html__( 'Container fluid', 'tlp-team' ),
		];
	}

	public static function scFontSize() {
		$num = [];
		for ( $i = 10; $i <= 60; $i ++ ) {
			$num[ $i ] = $i . 'px';
		}

		return $num;
	}

	public static function scTextWeight() {
		return [
			'normal'  => esc_html__( 'Normal', 'tlp-team' ),
			'bold'    => esc_html__( 'Bold', 'tlp-team' ),
			'bolder'  => esc_html__( 'Bolder', 'tlp-team' ),
			'lighter' => esc_html__( 'Lighter', 'tlp-team' ),
			'inherit' => esc_html__( 'Inherit', 'tlp-team' ),
			'initial' => esc_html__( 'Initial', 'tlp-team' ),
			'unset'   => esc_html__( 'Unset', 'tlp-team' ),
			100       => esc_html__( '100', 'tlp-team' ),
			200       => esc_html__( '200', 'tlp-team' ),
			300       => esc_html__( '300', 'tlp-team' ),
			400       => esc_html__( '400', 'tlp-team' ),
			500       => esc_html__( '500', 'tlp-team' ),
			600       => esc_html__( '600', 'tlp-team' ),
			700       => esc_html__( '700', 'tlp-team' ),
			800       => esc_html__( '800', 'tlp-team' ),
			900       => esc_html__( '900', 'tlp-team' ),
		];
	}

	public static function scAlignment() {
		return [
			'left'    => esc_html__( 'Left', 'tlp-team' ),
			'right'   => esc_html__( 'Right', 'tlp-team' ),
			'center'  => esc_html__( 'Center', 'tlp-team' ),
			'justify' => esc_html__( 'Justify', 'tlp-team' ),
		];
	}

	public static function elGridLayouts() {
		return apply_filters(
			'rttm_elementor_grid_layouts',
			[
				'layout1' => [
					'title' => esc_html__( 'Layout 1', 'tlp-team' ),
					'url'   => rttlp_team()->assets_url() . 'images/layouts/layout1.png',
				],

				'layout3' => [
					'title' => esc_html__( 'Layout 2', 'tlp-team' ),
					'url'   => rttlp_team()->assets_url() . 'images/layouts/layout3.png',
				],
			]
		);
	}

	public static function elListLayouts() {
		return apply_filters(
			'rttm_elementor_list_layouts',
			[
				'layout2' => [
					'title' => esc_html__( 'List Layout 1', 'tlp-team' ),
					'url'   => rttlp_team()->assets_url() . 'images/layouts/layout2.png',
				],
			]
		);
	}

	public static function elSliderLayouts() {
		return apply_filters(
			'rttm_elementor_slider_layouts',
			[
				'carousel-el-1' => [
					'title' => esc_html__( 'Slider 1', 'tlp-team' ),
					'url'   => rttlp_team()->assets_url() . 'images/layouts/carousel1.png',
				],
			]
		);
	}

	public static function elIsotopeLayouts() {
		return apply_filters(
			'rttm_elementor_isotope_layouts',
			[
				'isotope-free' => [
					'title' => esc_html__( 'Isotope 1', 'tlp-team' ),
					'url'   => rttlp_team()->assets_url() . 'images/layouts/isotope2.png',
				],
			]
		);
	}

	public static function scElColumns() {
		return [
			0 => esc_html__( 'Default', 'tlp-team' ),
			1 => esc_html__( '1 Column', 'tlp-team' ),
			2 => esc_html__( '2 Columns', 'tlp-team' ),
			3 => esc_html__( '3 Columns', 'tlp-team' ),
			4 => esc_html__( '4 Columns', 'tlp-team' ),
			5 => esc_html__( '5 Columns', 'tlp-team' ),
			6 => esc_html__( '6 Columns', 'tlp-team' ),
		];
	}
}
