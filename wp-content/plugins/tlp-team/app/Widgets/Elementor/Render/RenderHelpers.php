<?php
/**
 * Elementor Render Helpers Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Render;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Render Helpers Class.
 */
class RenderHelpers {

	/**
	 * Registers required scripts.
	 *
	 * @param array $scripts Scripts to register.
	 * @return void
	 */
	public static function registerScripts( $scripts ) {
		$iso    = false;
		$caro   = false;
		$pagi   = false;
		$scroll = false;
		$script = [];
		$style  = [];
		array_push( $script, 'jquery' );

		foreach ( $scripts as $sc => $value ) {
			if ( ! empty( $sc ) ) {
				if ( 'isIsotope' === $sc ) {
					$iso = $value;
				}

				if ( 'isCarousel' === $sc ) {
					$caro = $value;
				}

				if ( 'hasPagination' === $sc ) {
					$pagi = $value;
				}

				if ( 'hasModal' === $sc ) {
					$scroll = $value;
				}
			}
		}

		if ( count( $scripts ) ) {
			/**
			 * Styles.
			 */
			if ( $caro ) {
				array_push( $style, 'tlp-swiper' );
			}

			// if ( rttlp_team()->has_pro() ) {
			// array_push( $style, 'tlp-scrollbar' );
			// }

			if ( $pagi ) {
				// array_push( $style, 'rt-pagination' );
			}

			array_push( $style, 'tlp-fontawsome' );
			// array_push( $style, 'tlp-el-team-css' );

			/**
			 * Scripts.
			 */
			if ( $iso ) {
				array_push( $script, 'tlp-isotope-js' );
			}

			if ( $caro ) {
				array_push( $script, 'tlp-swiper' );
			}

			if ( rttlp_team()->has_pro() ) {
				// array_push( $script, 'tlp-scrollbar' );
				array_push( $script, 'rt-tooltip' );
			}

			if ( $pagi ) {
				array_push( $script, 'rt-pagination' );
			}

			if ( $scroll ) {
				array_push( $script, 'rt-scrollbox' );
			}

			// array_push( $script, 'tlp-actual-height-js' );
			array_push( $script, 'tlp-image-load-js' );
			array_push( $script, 'tlp-el-team-js' );

			wp_enqueue_style( $style );
			wp_enqueue_script( $script );

			$nonce   = wp_create_nonce( Fns::nonceText() );
			$ajaxurl = '';

			if ( in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) ) ) {
				$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else {
				$ajaxurl .= admin_url( 'admin-ajax.php' );
			}

			wp_localize_script(
				'tlp-el-team-js',
				'ttp',
				[
					'ajaxurl' => $ajaxurl,
					'nonceID' => Fns::nonceID(),
					'nonce'   => $nonce,
					'is_pro'  => rttlp_team()->has_pro() ? 'true' : 'false',
					'lan'     => Options::lan(),
				]
			);

			$html = null;

			if ( rttlp_team()->has_pro() ) {
				$html .= '<div class="md-el-modal tlp-md-effect" id="tlp-modal">
					<div class="md-el-content">
						<div class="tlp-md-el-content-holder"></div>
						<div class="md-cls-btn">
							<button class="md-close"><i class="fa fa-times" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>';
				$html .= "<div class='md-el-overlay'></div>";
			}

			Fns::print_html( $html );
		}

	}

	/**
	 * Builds an array with field values.
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $meta Field values.
	 * @return array
	 */
	public static function metaBuilder( $prefix, array $meta ) {
		$cImageSize         = ! empty( $meta[ $prefix . 'image_custom_dimension' ] ) ? $meta[ $prefix . 'image_custom_dimension' ] : [];
		$cImageSize['crop'] = ! empty( $meta[ $prefix . 'image_crop' ] ) ? $meta[ $prefix . 'image_crop' ] : '';

		return [
			'layout'             => ! empty( $meta[ $prefix . 'layout' ] ) ? esc_attr( $meta[ $prefix . 'layout' ] ) : 'layout1',
			'dCols'              => ! empty( $meta[ $prefix . 'cols' ] ) ? absint( $meta[ $prefix . 'cols' ] ) : 0,
			'tCols'              => ! empty( $meta[ $prefix . 'cols_tablet' ] ) ? absint( $meta[ $prefix . 'cols_tablet' ] ) : 2,
			'mCols'              => ! empty( $meta[ $prefix . 'cols_mobile' ] ) ? absint( $meta[ $prefix . 'cols_mobile' ] ) : 1,
			'postIn'             => ! empty( $meta[ $prefix . 'include_posts' ] ) && is_array( $meta[ $prefix . 'include_posts' ] ) ? $meta[ $prefix . 'include_posts' ] : [],
			'postNotIn'          => ! empty( $meta[ $prefix . 'exclude_posts' ] ) && is_array( $meta[ $prefix . 'exclude_posts' ] ) ? $meta[ $prefix . 'exclude_posts' ] : [],
			'limit'              => ( ( empty( $meta[ $prefix . 'posts_limit' ] ) || $meta[ $prefix . 'posts_limit' ] === '-1' ) ? 10000000 : absint( $meta[ $prefix . 'posts_limit' ] ) ),
			'pagination'         => ! empty( $meta[ $prefix . 'show_pagination' ] ) ? true : false,
			'postsPerPage'       => ! empty( $meta[ $prefix . 'pagination_per_page' ] ) ? absint( $meta[ $prefix . 'pagination_per_page' ] ) : '',
			'order_by'           => ! empty( $meta[ $prefix . 'posts_order_by' ] ) ? esc_html( $meta[ $prefix . 'posts_order_by' ] ) : 'date',
			'order'              => ! empty( $meta[ $prefix . 'posts_order' ] ) ? esc_html( $meta[ $prefix . 'posts_order' ] ) : 'DESC',
			'department_ids'     => ! empty( $meta[ $prefix . 'filter_department' ] ) && is_array( $meta[ $prefix . 'filter_department' ] ) ? $meta[ $prefix . 'filter_department' ] : [],
			'designation_ids'    => ! empty( $meta[ $prefix . 'filter_designation' ] ) && is_array( $meta[ $prefix . 'filter_designation' ] ) ? $meta[ $prefix . 'filter_designation' ] : [],
			'link'               => ! empty( $meta[ $prefix . 'detail_page_link' ] ) ? true : false,
			'fImg'               => ! empty( $meta[ $prefix . 'show_featured_image' ] ) ? false : true,
			'fImgSize'           => ! empty( $meta[ $prefix . 'image' ] ) ? esc_html( $meta[ $prefix . 'image' ] ) : 'medium',
			'customImgSize'      => ! empty( $cImageSize ) && is_array( $cImageSize ) ? $cImageSize : [],
			'character_limit'    => ! empty( $meta[ $prefix . 'content_limit' ] ) ? absint( $meta[ $prefix . 'content_limit' ] ) : 0,
			'after_short_desc'   => ! empty( $meta[ $prefix . 'after_content' ] ) ? esc_html( $meta[ $prefix . 'after_content' ] ) : '',
			'visibility'         => ! empty( self::contentVisibility( $prefix, $meta ) ) ? self::contentVisibility( $prefix, $meta ) : [ 'name', 'designation', 'short_bio', 'social' ],
			'social_profiles'    => ! empty( $meta[ $prefix . 'team_social_media' ] ) && is_array( $meta[ $prefix . 'team_social_media' ] ) ? $meta[ $prefix . 'team_social_media' ] : [],
			'posts_loading_type' => ! empty( self::paginationType( $prefix, $meta ) ) ? self::paginationType( $prefix, $meta ) : 'pagination',
			'load_more_text'     => ! empty( $meta[ $prefix . 'load_more_text' ] ) ? esc_html( $meta[ $prefix . 'load_more_text' ] ) : esc_html__( 'Load More', 'tlp-team' ),
			'relation'           => ! empty( $meta[ $prefix . 'tax_relation' ] ) ? esc_html( $meta[ $prefix . 'tax_relation' ] ) : 'AND',
			'iCol'               => ! empty( $meta[ $prefix . 'image_cols' ] ) ? absint( $meta[ $prefix . 'image_cols' ] ) : 4,
			'gridType'           => ! empty( $meta[ $prefix . 'grid_style' ] ) ? esc_html( $meta[ $prefix . 'grid_style' ] ) : 'even',
			'linkType'           => ! empty( $meta[ $prefix . 'link_type' ] ) ? esc_html( $meta[ $prefix . 'link_type' ] ) : 'external_link',
			'popupType'          => ! empty( $meta[ $prefix . 'popup_type' ] ) ? esc_html( $meta[ $prefix . 'popup_type' ] ) : 'single',
			'target'             => ! empty( $meta[ $prefix . 'link_target' ] ) ? esc_attr( $meta[ $prefix . 'link_target' ] ) : '_self',
			'grayscale'          => ! empty( $meta[ $prefix . 'grayscale_image' ] ) ? true : false,
			'stripedRow'         => ! empty( $meta[ $prefix . 'table_style' ] ) ? true : false,
			'defaultImgId'       => ! empty( $meta[ $prefix . 'default_preview_image' ]['id'] ) ? absint( $meta[ $prefix . 'default_preview_image' ]['id'] ) : null,
			'filter_buttons'     => ! empty( $meta[ $prefix . 'filter_button' ] ) ? true : false,
			'filters'            => ! empty( self::selectedFilters( $prefix, $meta ) ) ? self::selectedFilters( $prefix, $meta ) : [],
			'taxFilter'          => ! empty( $meta[ $prefix . 'filter_taxonomy' ] ) ? esc_html( $meta[ $prefix . 'filter_taxonomy' ] ) : 'team_department',
			'action_term'        => ! empty( self::selectedTerm( $prefix, $meta ) ) ? self::selectedTerm( $prefix, $meta ) : 0,
			'isoFilterTaxonomy'  => ! empty( $meta[ $prefix . 'isotope_filter_taxonomy' ] ) ? esc_html( $meta[ $prefix . 'isotope_filter_taxonomy' ] ) : 'team_department',
			'filterType'         => ! empty( $meta[ $prefix . 'tax_filter_type' ] ) ? esc_html( $meta[ $prefix . 'tax_filter_type' ] ) : null,
			'hide_all_button'    => ! empty( $meta[ $prefix . 'show_all' ] ) ? false : true,
			'tItem'              => ! empty( self::selectedTerm( $prefix, $meta ) ) ? self::selectedTerm( $prefix, $meta ) : 0,
			'fShowAll'           => ! empty( $meta[ $prefix . 'iso_show_all' ] ) ? true : false,
			'animation'          => ! empty( $meta[ $prefix . 'image_hover_animation' ] ) ? esc_html( $meta[ $prefix . 'image_hover_animation' ] ) : 'zoom_in',
		];
	}

	/**
	 * Builds an array with field values.
	 *
	 * @param array $meta Field values.
	 * @return array
	 */
	public static function argBuilder( array $meta ) {
		if ( empty( $meta ) ) {
			return [];
		}

		$arg                = [];
		$arg['class']       = null;
		$arg['grid']        = null;
		$arg['anchorClass'] = null;

		$layout = $meta['layout'];
		$link   = $meta['link'];

		$isIsotope  = preg_match( '/isotope/', $layout );
		$isCarousel = preg_match( '/carousel/', $layout );

		$dCol = 0 === $meta['dCols'] ? self::defaultColumns( $layout ) : $meta['dCols'];
		$tCol = 0 === $meta['tCols'] ? 2 : $meta['tCols'];
		$mCol = 0 === $meta['mCols'] ? 1 : $meta['mCols'];

		$dCol = 5 === $dCol ? '24' : round( 12 / $dCol );
		$tCol = 5 === $dCol ? '24' : round( 12 / $tCol );
		$mCol = 5 === $dCol ? '24' : round( 12 / $mCol );

		if ( ! $isCarousel ) {
			$arg['grid']  = 'rt-col-md-' . $dCol . ' rt-col-sm-' . $tCol . ' rt-col-xs-' . $mCol . ' ';
			$arg['class'] = ' ' . $meta['gridType'] . '-grid-item ';
		}

		if ( ( 'layout2' === $layout ) || ( 'layout3' === $layout ) ) {
			$iCol                = $meta['iCol'] > 12 ? 4 : $meta['iCol'];
			$cCol                = 12 - $iCol;
			$arg['image_area']   = 'rt-col-sm-' . $iCol . ' rt-col-xs-12 ';
			$arg['content_area'] = 'rt-col-sm-' . $cCol . ' rt-col-xs-12 ';
		}

		$arg['class'] .= 'rt-grid-item';

		if ( ! $isIsotope ) {
			$arg['class'] .= ' rt-ready-animation animated fadeIn';
		}

		if ( $isIsotope ) {
			$arg['class'] .= ' isotope-item';
		}

		if ( $isCarousel ) {
			$arg['class'] .= ' swiper-slide';
		}

		if ( ! $link ) {
			$arg['link']        = false;
			$arg['anchorClass'] = ' disabled';
		} else {
			$arg['link'] = true;
		}

		if ( rttlp_team()->has_pro() && $link && 'popup' === $meta['linkType'] ) {
			if ( 'single' === $meta['popupType'] ) {
				$arg['anchorClass'] .= ' ttp-single-md-popup';
			} elseif ( 'multiple' === $meta['popupType'] ) {
				$arg['anchorClass'] .= ' ttp-multi-popup';
			} elseif ( 'smart' === $meta['popupType'] ) {
				$arg['anchorClass'] .= ' ttp-smart-popup';
			}
		}

		$arg['target'] = '_self';

		if ( $link && '_blank' === $meta['target'] ) {
			$arg['target'] = '_blank';
		}

		$arg['items']      = $meta['visibility'];
		$arg['el_socials'] = $meta['social_profiles'];

		return $arg;
	}

	/**
	 * Builds an array with meta values.
	 *
	 * @param array $arg Arg values.
	 * @param array $meta Meta values.
	 * @param int   $postID Post ID.
	 * @return array
	 */
	public static function loopArgBuilder( array $arg, array $meta, int $postID, bool $lazyLoad = false ) {
		if ( empty( $meta ) && empty( $arg ) && ! $postID ) {
			return [];
		}

		$arg['sLink'] = [];
		$isIsotope    = preg_match( '/isotope/', $meta['layout'] );

		$arg['mID']       = $postID;
		$arg['title']     = get_the_title();
		$cLink            = get_post_meta( $postID, 'ttp_custom_detail_url', true );
		$arg['pLink']     = ( 'external_link' === $meta['linkType'] ? ( ! empty( $cLink ) ? $cLink : get_permalink() ) : get_permalink() );
		$arg['email']     = get_post_meta( $postID, 'email', true );
		$arg['web_url']   = get_post_meta( $postID, 'web_url', true );
		$arg['telephone'] = get_post_meta( $postID, 'telephone', true );
		$arg['mobile']    = get_post_meta( $postID, 'mobile', true );
		$arg['fax']       = get_post_meta( $postID, 'fax', true );
		$arg['location']  = get_post_meta( $postID, 'location', true );
		$short_bio        = get_post_meta( $postID, 'short_bio', true );
		$social           = get_post_meta( $postID, 'social', true );
		$arg['soLink']    = $social ? $social : [];
		$skill            = get_post_meta( $postID, 'skill', true );
		$arg['tlp_skill'] = $skill ? maybe_unserialize( $skill ) : [];

		foreach ( $arg['soLink'] as $soc ) {
			if ( in_array( $soc['id'], $meta['social_profiles'], true ) ) {
				$arg['sLink'][] = $soc;
			}
		}

		if ( empty( $meta['social_profiles'] ) ) {
			$arg['sLink'] = $arg['soLink'];
		}

		$arg['imgHtml'] = ! $meta['fImg'] ? Fns::getFeatureImageHtml(
			$postID,
			$meta['fImgSize'],
			$meta['defaultImgId'],
			$meta['customImgSize'],
			$lazyLoad
		) : null;

		$arg['short_bio'] = Fns::get_ttp_short_description(
			$short_bio,
			$meta['character_limit'],
			$meta['after_short_desc']
		);

		$arg['designation'] = wp_strip_all_tags(
			get_the_term_list(
				$postID,
				rttlp_team()->taxonomies['designation'],
				null,
				', '
			)
		);

		$arg['tax_department'] = wp_strip_all_tags(
			get_the_term_list(
				$postID,
				rttlp_team()->taxonomies['department'],
				null,
				', '
			)
		);

		if ( ! $arg['imgHtml'] ) {
			$arg['content_area'] = 'rt-col-md-12';
		}

		$arg['isoFilter'] = '';

		if ( $isIsotope && $meta['isoFilterTaxonomy'] ) {
			$termAs    = wp_get_post_terms( $postID, $meta['isoFilterTaxonomy'], [ 'fields' => 'all' ] );
			$isoFilter = null;
			if ( ! empty( $termAs ) ) {
				foreach ( $termAs as $term ) {
					$isoFilter .= ' iso_' . $term->term_id;
				}
			}
			$arg['isoFilter'] = $isoFilter;
		}

		return $arg;
	}

	/**
	 * Render Filter buttons for Isotope View.
	 *
	 * @param array $meta Field values.
	 * @param int   $random Random number.
	 * @return string
	 */
	public static function renderIsotopeFilters( array $meta, int $random ) {
		$isIsotope = preg_match( '/isotope/', $meta['layout'] );

		if ( ! $isIsotope && ! $meta['isoFilterTaxonomy'] ) {
			return;
		}

		if ( ! rttlp_team()->has_pro() ) {
			$meta['fShowAll'] = true;
		}

		$terms          = Fns::rt_get_all_terms_by_taxonomy( $meta['isoFilterTaxonomy'] );
		$html           = null;
		$htmlButton     = null;
		$fSelectTrigger = false;

		if ( ! empty( $terms ) ) {
			$sltIds = [];

			if ( rttlp_team()->taxonomies['department'] === $meta['isoFilterTaxonomy'] ) {
				$sltIds = $meta['department_ids'];
			} elseif ( rttlp_team()->taxonomies['designation'] === $meta['isoFilterTaxonomy'] ) {
				$sltIds = $meta['designation_ids'];
			};

			foreach ( $terms as $id => $term ) {
				$fSelect = null;

				if ( $meta['tItem'] === $id ) {
					$fSelect        = 'class="selected"';
					$fSelectTrigger = true;
				}

				$btn = '<button data-filter=".iso_' . esc_attr( $id ) . '" ' . $fSelect . '>' . esc_html( $term ) . '</button>';

				if ( ! empty( $sltIds ) ) {
					$htmlButton .= in_array( esc_attr( $id ), $sltIds, true ) ? $btn : null;
				} else {
					$htmlButton .= $btn;
				}
			}
		}

		if ( $meta['fShowAll'] && $htmlButton ) {
			$fSelect = ( $fSelectTrigger ? null : 'class="selected"' );

			$htmlButton = '<button data-filter="*" ' . $fSelect . '>' . esc_html__(
				'Show all',
				'tlp-team'
			) . '</button>' . $htmlButton;
		}

		$html .= '<div id="iso-button-' . $random . '" class="ttp-isotope-buttons button-group filter-button-group">' . $htmlButton . '</div>';

		return $html;
	}

	/**
	 * Slider options.
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $meta Field values.
	 * @return string
	 */
	public static function sliderData( string $prefix, array $meta ) {
		$layout          = ! empty( $meta[ $prefix . 'layout' ] ) ? esc_attr( $meta[ $prefix . 'layout' ] ) : 'carousel-el-1';
		$dCols           = ! empty( $meta[ $prefix . 'cols' ] ) ? absint( $meta[ $prefix . 'cols' ] ) : 0;
		$tCols           = ! empty( $meta[ $prefix . 'cols_tablet' ] ) ? absint( $meta[ $prefix . 'cols_tablet' ] ) : 0;
		$mCols           = ! empty( $meta[ $prefix . 'cols_mobile' ] ) ? absint( $meta[ $prefix . 'cols_mobile' ] ) : 0;
		$dGroup          = ! empty( $meta[ $prefix . 'slide_groups' ] ) ? absint( $meta[ $prefix . 'slide_groups' ] ) : 1;
		$tGroup          = ! empty( $meta[ $prefix . 'slide_groups_mobile' ] ) ? absint( $meta[ $prefix . 'slide_groups_mobile' ] ) : 1;
		$mGroup          = ! empty( $meta[ $prefix . 'slide_groups_mobile' ] ) ? absint( $meta[ $prefix . 'slide_groups_mobile' ] ) : 1;
		$autoPlay        = ! empty( $meta[ $prefix . 'slide_autoplay' ] ) ? true : false;
		$stopOnHover     = ! empty( $meta[ $prefix . 'pause_hover' ] ) ? true : false;
		$nav             = ! empty( $meta[ $prefix . 'slider_nav' ] ) ? true : false;
		$dots            = ! empty( $meta[ $prefix . 'slider_pagi' ] ) ? true : false;
		$loop            = ! empty( $meta[ $prefix . 'slider_loop' ] ) ? true : false;
		$lazyLoad        = ! empty( $meta[ $prefix . 'slider_lazy_load' ] ) ? true : false;
		$autoHeight      = ! empty( $meta[ $prefix . 'slider_auto_height' ] ) ? true : false;
		$speed           = ! empty( $meta[ $prefix . 'slide_speed' ] ) ? absint( $meta[ $prefix . 'slide_speed' ] ) : 2000;
		$spaceBetween    = isset( $meta[ $prefix . 'space_between_slides' ]['size'] ) && strlen( $meta[ $prefix . 'space_between_slides' ]['size'] ) ? absint( $meta[ $prefix . 'space_between_slides' ]['size'] ) : 30;
		$autoPlayTimeOut = ! empty( $meta[ $prefix . 'autoplay_timeout' ] ) ? absint( $meta[ $prefix . 'autoplay_timeout' ] ) : 5000;
		$hasDots         = $dots ? ' has-dots' : ' no-dots';
		$hasDots        .= $nav ? ' has-nav' : ' no-nav';
		$navPosition     = ! empty( $meta[ $prefix . 'slider_nav_position' ] ) ? esc_attr( $meta[ $prefix . 'slider_nav_position' ] ) : 'top';

		$dCol = 0 === $dCols ? self::defaultColumns( $layout ) : $dCols;
		$tCol = 0 === $tCols ? 2 : $tCols;
		$mCol = 0 === $mCols ? 1 : $mCols;

		$sliderOptions = [
			'slidesPerView'  => (int) $dCol,
			'slidesPerGroup' => (int) $dGroup,
			'spaceBetween'   => (int) $spaceBetween,
			'speed'          => (int) absint( $speed ),
			'loop'           => (bool) $loop,
			'autoHeight'     => (bool) $autoHeight,
			'preloadImages'  => (bool) $lazyLoad ? false : true,
			'lazy'           => (bool) $lazyLoad ? true : false,
			'breakpoints'    => [
				0   => [
					'slidesPerView'  => (int) $mCol,
					'slidesPerGroup' => (int) $mGroup,
					'pagination'     => [
						'dynamicBullets' => (bool) true,
					],
				],
				767 => [
					'slidesPerView'  => (int) $tCol,
					'slidesPerGroup' => (int) $tGroup,
					'pagination'     => [
						'dynamicBullets' => (bool) false,
					],
				],
				991 => [
					'slidesPerView'  => (int) $dCol,
					'slidesPerGroup' => (int) $dGroup,
				],
			],
		];

		if ( 'carousel10' === $layout ) {
			$sliderOptions['breakpoints'] = [
				0   => [
					'slidesPerView' => (int) $mCol,
					'pagination'    => [
						'dynamicBullets' => (bool) true,
					],
				],
				767 => [
					'slidesPerView' => (int) ! empty( $tCol ) ? absint( $tCol ) : 3,
					'pagination'    => [
						'dynamicBullets' => (bool) false,
					],
				],
				991 => [
					'slidesPerView' => (int) ! empty( $dCol ) ? absint( $dCol ) : 5,
				],
			];
		}

		if ( $autoPlay ) {
			$sliderOptions['autoplay'] = [
				'delay'                => (int) absint( $autoPlayTimeOut ),
				'pauseOnMouseEnter'    => (bool) $stopOnHover,
				'disableOnInteraction' => (bool) false,
			];
		}

		$carouselClass = ( 'carousel10' !== $layout ? 'swiper rttm-carousel-slider rt-pos-s ' . $navPosition . '-nav' . $hasDots : 'swiper rttm-carousel-main rt-pos-s' );

		return [
			'data'  => wp_json_encode( $sliderOptions ),
			'class' => $carouselClass,
		];
	}

	/**
	 * Renders pagination
	 *
	 * @param object $wpQuery WP_Query object.
	 * @param array  $meta Meta values.
	 * @param int    $limit Post limit.
	 * @param int    $perPage Posts per page.
	 * @return string
	 */
	public static function renderPagination( $wpQuery, $meta, $limit, $perPage, $page_num = null ) {
		$htmlUtility = null;
		$html        = null;
		$isIsotope   = preg_match( '/isotope/', $meta['layout'] );
		$isGrid      = preg_match( '/layout/', $meta['layout'] );
		$postPp      = $wpQuery->query_vars['posts_per_page'];
		$page        = $wpQuery->query_vars['paged'];
		$foundPosts  = $wpQuery->found_posts;
		$morePosts   = $foundPosts - ( $postPp * $page );
		$totalPage   = $wpQuery->max_num_pages;
		$foundPost   = $wpQuery->found_posts;

		if ( $limit && ( empty( $wpQuery->query['tax_query'] ) ) ) {
			$foundPost = $wpQuery->found_posts;

			if ( $perPage && $foundPost > $perPage ) {
				$foundPost = $limit;
				$totalPage = ceil( $foundPost / $perPage );
			}
		}

		$morePosts  = $foundPost - ( $postPp * $page );
		$foundPosts = $foundPost;
		$totalPage  = absint( $totalPage );
		$morePosts  = absint( $morePosts );

		$hide = ( $wpQuery->max_num_pages < 2 ? ' rt-hidden-elm' : null );

		if ( 'pagination' === $meta['posts_loading_type'] && $isGrid && empty( $meta['filters'] ) ) {
			$htmlUtility .= Fns::custom_pagination(
				$totalPage,
				$postPp,
				$page_num
			);
		} elseif ( 'pagination_ajax' === $meta['posts_loading_type'] && ! $isIsotope ) {
			$htmlUtility .= '<div class="rt-page-numbers"></div>';
		} elseif ( 'load_more' === $meta['posts_loading_type'] ) {
			$htmlUtility .= '<div class="rt-loadmore-btn rt-loadmore-action rt-loadmore-style' . $hide . '">
								<span class="rt-loadmore-text">' . $meta['load_more_text'] . '</span>
								<div class="rt-loadmore-loading rt-ball-scale-multiple rt-2x">
									<div></div>
									<div></div>
									<div></div>
								</div>
							</div>';
		} elseif ( 'load_on_scroll' === $meta['posts_loading_type'] ) {
			$htmlUtility .= '<div class="rt-infinite-action">
								<div class="rt-infinite-loading la-fire la-2x">
									<div></div>
									<div></div>
									<div></div>
								</div>
							</div>';
		}

		if ( $htmlUtility ) {
			$html .= '<div class="rt-pagination-wrap" data-total-pages="' . absint( $totalPage ) . '" data-posts-per-page="' . absint( $postPp ) . '" data-type="' . esc_attr( $meta['posts_loading_type'] ) . '">' . $htmlUtility . '</div>';
		}

		return $html;
	}

	/**
	 * Pagination JSON data.
	 *
	 * @param boolean $pagination If Pagination enabled.
	 * @param string  $type Pagination type.
	 * @param string  $prefix Prefix.
	 * @param array   $metas Data set.
	 * @return void
	 */
	public static function elPaginationData( $prefix, $metas ) {
		$elData = self::metaBuilder( $prefix, $metas );
		return ' data-rttm-pagination=\'' . wp_json_encode( $elData ) . '\'';
	}

	/**
	 * Setting up content visibility
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $settings Elementor settings.
	 * @return array
	 */
	public static function contentVisibility( $prefix, $settings ) {
		$visibility = [];

		if ( ! empty( $settings[ $prefix . 'team_name' ] ) ) {
			$visibility[] = 'name';
		}

		if ( ! empty( $settings[ $prefix . 'team_designation' ] ) ) {
			$visibility[] = 'designation';
		}

		if ( ! empty( $settings[ $prefix . 'team_department' ] ) ) {
			$visibility[] = 'tax_department';
		}

		if ( ! empty( $settings[ $prefix . 'team_short_bio' ] ) ) {
			$visibility[] = 'short_bio';
		}

		if ( ! empty( $settings[ $prefix . 'team_content' ] ) ) {
			$visibility[] = 'content';
		}

		if ( ! empty( $settings[ $prefix . 'team_email' ] ) ) {
			$visibility[] = 'email';
		}

		if ( ! empty( $settings[ $prefix . 'team_website' ] ) ) {
			$visibility[] = 'web_url';
		}

		if ( ! empty( $settings[ $prefix . 'team_phone' ] ) ) {
			$visibility[] = 'telephone';
		}

		if ( ! empty( $settings[ $prefix . 'team_mobile' ] ) ) {
			$visibility[] = 'mobile';
		}

		if ( ! empty( $settings[ $prefix . 'team_fax' ] ) ) {
			$visibility[] = 'fax';
		}

		if ( ! empty( $settings[ $prefix . 'team_location' ] ) ) {
			$visibility[] = 'location';
		}

		if ( ! empty( $settings[ $prefix . 'show_social_media' ] ) ) {
			$visibility[] = 'social';
		}

		if ( ! empty( $settings[ $prefix . 'team_skills' ] ) ) {
			$visibility[] = 'skill';
		}

		return array_map( 'sanitize_text_field', $visibility );
	}

	/**
	 * Setting up Selected filters
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $settings Elementor settings.
	 * @return array
	 */
	public static function selectedFilters( $prefix, $settings ) {
		$selected = [];

		if ( ! empty( $settings[ $prefix . 'tax_filter' ] ) ) {
			$selected[] = '_taxonomy_filter';
		}

		if ( ! empty( $settings[ $prefix . 'tax_order_by' ] ) ) {
			$selected[] = '_order_by';
		}

		if ( ! empty( $settings[ $prefix . 'tax_order' ] ) ) {
			$selected[] = '_sort_order';
		}

		if ( ! empty( $settings[ $prefix . 'tax_search' ] ) ) {
			$selected[] = '_search';
		}

		return array_map( 'sanitize_text_field', $selected );
	}

	/**
	 * Setting up pagination type
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $settings Elementor settings.
	 * @return string
	 */
	public static function paginationType( $prefix, $settings ) {
		$selected = 0;

		if ( ! empty( $settings[ $prefix . 'pagination_type' ] ) ) {
			$selected = $settings[ $prefix . 'pagination_type' ];
		}

		if ( ! empty( $settings[ $prefix . 'pagination_type_filter' ] ) ) {
			$selected = $settings[ $prefix . 'pagination_type_filter' ];
		}

		return esc_html( $selected );
	}

	/**
	 * Setting up Selected term
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $settings Elementor settings.
	 * @return int
	 */
	public static function selectedTerm( $prefix, $settings ) {
		$selected = 0;

		if ( ! empty( $settings[ $prefix . 'team_designation_default' ] ) ) {
			$selected = $settings[ $prefix . 'team_designation_default' ];
		}

		if ( ! empty( $settings[ $prefix . 'team_department_default' ] ) ) {
			$selected = $settings[ $prefix . 'team_department_default' ];
		}

		return absint( $selected );
	}

	/**
	 * Default layout columns.
	 *
	 * @param int $layout Layout.
	 *
	 * @return int
	 */
	public static function defaultColumns( $layout ) {
		$columns = 4;

		switch ( $layout ) {
			case 'layout2':
				$columns = 2;
				break;

			case 'layout-el-6':
				$columns = 2;
				break;

			case 'layout-el-10':
				$columns = 3;
				break;

			case 'carousel5':
				$columns = 3;
				break;

			case 'carousel10':
				$columns = 5;
				break;

			default:
				$columns = 4;
				break;
		}

		return $columns;
	}
}
