<?php
/**
 * Preview Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Preview Ajax Class.
 */
class Preview {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlpTeamPreviewAjaxCall', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$msg   = $html = $scID = null;
		$error = true;

		if ( Fns::verifyNonce() ) {
			$error    = false;
			$rand     = mt_rand();
			$layoutID = 'rt-container-' . $rand;
			$lazyLoad = false;

			$layout = ( ! empty( $_REQUEST['layout'] ) ? sanitize_text_field( $_REQUEST['layout'] ) : 'layout1' );

			if ( ! in_array( $layout, array_keys( Options::scLayout() ) ) ) {
				$layout = 'layout1';
			}

			$isIsotope  = preg_match( '/isotope/', $layout );
			$isCarousel = preg_match( '/carousel/', $layout );
			$isGrid     = preg_match( '/layout/', $layout );
			$allCol     = ! empty( $_REQUEST['ttp_column'] ) && is_array( $_REQUEST['ttp_column'] ) ? array_map( 'sanitize_text_field', $_REQUEST['ttp_column'] ) : [];
			$dCol       = ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4;
			$tCol       = ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 2;
			$mCol       = ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1;

			if ( ! in_array( $dCol, array_keys( Options::scColumns() ) ) ) {
				$dCol = 3;
			}

			if ( ! in_array( $tCol, array_keys( Options::scColumns() ) ) ) {
				$tCol = 2;
			}

			if ( ! in_array( $dCol, array_keys( Options::scColumns() ) ) ) {
				$mCol = 1;
			}

			/* Argument create */
			$args              = [];
			$args['post_type'] = rttlp_team()->post_type;

			/* post__in */
			$post__in = isset( $_REQUEST['ttp_post__in'] ) && is_array( $_REQUEST['ttp_post__in'] ) ? array_map( 'absint', $_REQUEST['ttp_post__in'] ) : null;

			if ( $post__in ) {
				$args['post__in'] = $post__in;
			}

			/* post__not_in */
			$post__not_in = isset( $_REQUEST['ttp_post__not_in'] ) && is_array( $_REQUEST['ttp_post__not_in'] ) ? array_map( 'absint', $_REQUEST['ttp_post__not_in'] ) : null;

			if ( $post__not_in ) {
				$args['post__not_in'] = $post__not_in;
			}

			/* LIMIT */
			$limit                  = ( ( empty( $_REQUEST['ttp_limit'] ) || $_REQUEST['ttp_limit'] === '-1' ) ? 10000000 : absint( $_REQUEST['ttp_limit'] ) );
			$args['posts_per_page'] = $limit;
			$pagination             = ! empty( $_REQUEST['ttp_pagination'] );
			$posts_loading_type     = ! empty( $_REQUEST['ttp_pagination_type'] ) ? sanitize_text_field( $_REQUEST['ttp_pagination_type'] ) : 'pagination';

			if ( $pagination && ! $isCarousel ) {
				$posts_per_page = isset( $_REQUEST['ttp_posts_per_page'] ) ? absint( $_REQUEST['ttp_posts_per_page'] ) : $limit;
				if ( $posts_per_page > $limit ) {
					$posts_per_page = $limit;
				}
				// Set 'posts_per_page' parameter
				$args['posts_per_page'] = $posts_per_page;

				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				$offset        = $posts_per_page * ( (int) $paged - 1 );
				$args['paged'] = $paged;

				// Update posts_per_page
				if ( absint( $args['posts_per_page'] ) > $limit - $offset ) {
					$args['posts_per_page'] = $limit - $offset;
				}
			}

			if ( $isCarousel ) {
				$args['posts_per_page'] = $limit;
			}

			// Taxonomy
			$taxQ           = $taxFilterTerms = [];
			$department_ids = ! empty( $department_ids ) && is_array( $department_ids ) ? array_map( 'absint', $_REQUEST['ttp_departments'] ) : [];

			if ( ! empty( $department_ids ) ) {
				$taxFilterTerms = array_merge( $taxFilterTerms, $department_ids );
				$taxQ[]         = [
					'taxonomy' => rttlp_team()->taxonomies['department'],
					'field'    => 'term_id',
					'terms'    => $department_ids,
					'operator' => 'IN',
				];
			}

			$designation_ids = ! empty( $designation_ids ) && is_array( $designation_ids ) ? array_map( 'absint', $_REQUEST['ttp_designations'] ) : [];

			if ( ! empty( $designation_ids ) ) {
				$taxFilterTerms = array_merge( $taxFilterTerms, $designation_ids );
				$taxQ[]         = [
					'taxonomy' => rttlp_team()->taxonomies['designation'],
					'field'    => 'term_id',
					'terms'    => $designation_ids,
					'operator' => 'IN',
				];
			}

			if ( count( $taxQ ) >= 2 ) {
				$relation         = isset( $_REQUEST['ttp_taxonomy_relation'] ) ? sanitize_text_field( $_REQUEST['ttp_taxonomy_relation'] ) : 'AND';
				$taxQ['relation'] = $relation;
			}

			if ( ! empty( $taxQ ) ) {
				$args['tax_query'] = $taxQ;
			}

			// Order
			$order_by = isset( $_REQUEST['order_by'] ) ? sanitize_text_field( $_REQUEST['order_by'] ) : null;
			$order    = isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : null;

			if ( $order ) {
				$args['order'] = $order;
			}

			if ( $order_by ) {
				$args['orderby'] = $order_by;
			}

			// Validation
			$containerDataAttr  = null;
			$containerDataAttr .= " data-layout='{$layout}' data-desktop-col='{$dCol}'  data-tab-col='{$tCol}'  data-mobile-col='{$mCol}'";
			$dCol               = $dCol == 5 ? '24' : round( 12 / $dCol );
			$tCol               = $dCol == 5 ? '24' : round( 12 / $tCol );
			$mCol               = $dCol == 5 ? '24' : round( 12 / $mCol );

			if ( $isCarousel ) {
				$dCol = $tCol = $mCol = 12;
			}
			$arg         = [];
			$arg['grid'] = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";

			if ( ( $layout == 'layout2' ) || ( $layout == 'layout3' ) ) {
				$iCol                = ! empty( $_REQUEST['ttl_image_column'] ) ? absint( $_REQUEST['ttl_image_column'] ) : 4;
				$iCol                = $iCol > 12 ? 4 : $iCol;
				$cCol                = 12 - $iCol;
				$arg['image_area']   = "rt-col-sm-{$iCol} rt-col-xs-12 ";
				$arg['content_area'] = "rt-col-sm-{$cCol} rt-col-xs-12 ";
			}

			$gridType     = ! empty( $_REQUEST['grid_style'] ) ? sanitize_text_field( $_REQUEST['grid_style'] ) : 'even';
			$arg['class'] = null;

			if ( ! $isCarousel ) {
				$arg['class'] = $gridType . '-grid-item';
			}

			$arg['class'] .= ' rt-grid-item';

			$masonryG = null;

			if ( $gridType == 'even' ) {
				$masonryG = ' ttp-even';
			} elseif ( $gridType == 'masonry' && ! $isIsotope && ! $isCarousel ) {
				$masonryG = ' ttp-masonry';
			}

			$preLoader     = 'ttp-pre-loader';
			$preLoaderHtml = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';

			if ( $isIsotope ) {
				$arg['class'] .= ' isotope-item';
			}

			if ( $isCarousel ) {
				$arg['class'] .= ' swiper-slide';
			}

			$margin = ! empty( $_REQUEST['margin_option'] ) ? sanitize_text_field( $_REQUEST['margin_option'] ) : 'default';

			if ( $margin == 'no' ) {
				$arg['class'] .= ' no-margin';
			}

			$round_img = null;

			if ( ! empty( $_REQUEST['image_style'] ) && 'round' === sanitize_text_field( $_REQUEST['image_style'] ) ) {
				$arg['class'] .= $round_img = ' round-img';
			}

			$arg['anchorClass'] = null;
			$link               = ! empty( $_REQUEST['ttp_detail_page_link'] ) ? sanitize_text_field( $_REQUEST['ttp_detail_page_link'] ) : null;

			if ( ! $link ) {
				$arg['link']        = false;
				$arg['anchorClass'] = ' disabled';
			} else {
				$arg['link'] = true;
			}

			$linkType = ! empty( $_REQUEST['ttp_detail_page_link_type'] ) ? sanitize_text_field( $_REQUEST['ttp_detail_page_link_type'] ) : 'popup';

			if ( $link && $linkType == 'popup' ) {
				$popupType = ! empty( $_REQUEST['ttp_popup_type'] ) ? sanitize_text_field( $_REQUEST['ttp_popup_type'] ) : 'single';
				if ( $popupType == 'single' ) {
					$arg['anchorClass'] .= ' ttp-single-md-popup';
				} elseif ( $popupType == 'multiple' ) {
					$arg['anchorClass'] .= ' ttp-multi-popup';
				} elseif ( $popupType == 'smart' ) {
					$arg['anchorClass'] .= ' ttp-smart-popup';
				}
			}

			$arg['target'] = null;

			if ( $link && $linkType == 'new_page' ) {
				$arg['target'] = ! empty( $_REQUEST['ttp_link_target'] ) ? sanitize_text_field( $_REQUEST['ttp_link_target'] ) : '_self';
			}

			$parentClass      = ( ! empty( $_REQUEST['ttp_parent_class'] ) ? sanitize_text_field( $_REQUEST['ttp_parent_class'] ) : null );
			$grayscale        = ( ! empty( $_REQUEST['ttp_grayscale'] ) ? sanitize_text_field( $_REQUEST['ttp_grayscale'] ) : null );
			$fImg             = ! empty( $_REQUEST['ttp_image'] );
			$fImgSize         = ( isset( $_REQUEST['ttp_image_size'] ) ? sanitize_text_field( $_REQUEST['ttp_image_size'] ) : 'medium' );
			$character_limit  = ( isset( $_REQUEST['character_limit'] ) ? absint( $_REQUEST['character_limit'] ) : 0 );
			$after_short_desc = isset( $_REQUEST['ttp_after_short_desc_text'] ) ? sanitize_textarea_field( $_REQUEST['ttp_after_short_desc_text'] ) : '';
			$defaultImgId     = ! empty( $_REQUEST['default_preview_image'] ) ? absint( $_REQUEST['default_preview_image'] ) : null;
			$customImgSize    = ! empty( $_REQUEST['ttp_custom_image_size'] ) && is_array( $_REQUEST['ttp_custom_image_size'] ) ? array_map( 'sanitize_text_field', $_REQUEST['ttp_custom_image_size'] ) : [];

			$containerClass  = 'rt-team-container-' . $scID;
			$containerClass .= $parentClass ? ' ' . $parentClass : null;
			$containerClass .= $grayscale ? ' rt-grayscale' : null;
			$arg['items']    = ! empty( $_REQUEST['ttp_selected_field'] ) && is_array( $_REQUEST['ttp_selected_field'] ) ? array_map( 'sanitize_text_field', $_REQUEST['ttp_selected_field'] ) : [];
			$filters         = ! empty( $_REQUEST['ttp_filter'] ) && is_array( $_REQUEST['ttp_filter'] ) ? array_map( 'sanitize_text_field', $_REQUEST['ttp_filter'] ) : [];
			$taxFilter       = ! empty( $_REQUEST['ttp_filter_taxonomy'] ) ? sanitize_text_field( $_REQUEST['ttp_filter_taxonomy'] ) : null;
			$action_term     = ! empty( $_REQUEST['ttp_default_filter'] ) ? absint( $_REQUEST['ttp_default_filter'] ) : 0;

			$isoFilterTaxonomy = ! empty( $_REQUEST['ttp_isotope_filter_taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ttp_isotope_filter_taxonomy'] ) ) : null;

			if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter && $action_term ) {

				$args['tax_query'] = [
					[
						'taxonomy' => $taxFilter,
						'field'    => 'term_id',
						'terms'    => [ $action_term ],
					],
				];
			}

			$cssMeta = [
				'primary_color'      => ( isset( $_REQUEST['primary_color'] ) ? sanitize_hex_color( $_REQUEST['primary_color'] ) : null ),
				'ttp_button_style'   => ( isset( $_REQUEST['ttp_button_style'] ) ? array_map( 'sanitize_hex_color', $_REQUEST['ttp_button_style'] ) : null ),
				'ttp_popup_bg_color' => ( isset( $_REQUEST['ttp_popup_bg_color'] ) ? sanitize_hex_color( $_REQUEST['ttp_popup_bg_color'] ) : null ),
				'name'               => ( isset( $_REQUEST['name'] ) ? array_map( 'sanitize_text_field', $_REQUEST['name'] ) : null ),
				'designation'        => ( isset( $_REQUEST['designation'] ) ? array_map( 'sanitize_text_field', $_REQUEST['designation'] ) : null ),
				'short_bio'          => ( isset( $_REQUEST['short_bio'] ) ? array_map( 'sanitize_text_field', $_REQUEST['short_bio'] ) : null ),
				'email'              => ( isset( $_REQUEST['email'] ) ? array_map( 'sanitize_text_field', $_REQUEST['email'] ) : null ),
				'web_url'            => ( isset( $_REQUEST['web_url'] ) ? array_map( 'sanitize_text_field', $_REQUEST['web_url'] ) : null ),
				'telephone'          => ( isset( $_REQUEST['telephone'] ) ? array_map( 'sanitize_text_field', $_REQUEST['telephone'] ) : null ),
				'mobile'             => ( isset( $_REQUEST['mobile'] ) ? array_map( 'sanitize_text_field', $_REQUEST['mobile'] ) : null ),
				'fax'                => ( isset( $_REQUEST['fax'] ) ? array_map( 'sanitize_text_field', $_REQUEST['fax'] ) : null ),
				'location'           => ( isset( $_REQUEST['location'] ) ? array_map( 'sanitize_text_field', $_REQUEST['location'] ) : null ),
				'skill'              => ( isset( $_REQUEST['skill'] ) ? array_map( 'sanitize_text_field', $_REQUEST['skill'] ) : null ),
				'social'             => ( isset( $_REQUEST['social'] ) ? array_map( 'sanitize_text_field', $_REQUEST['social'] ) : null ),
				'social_icon_bg'     => ( isset( $_REQUEST['social_icon_bg'] ) ? sanitize_hex_color( $_REQUEST['social_icon_bg'] ) : null ),
				'overlay_rgba_bg'    => ( isset( $_REQUEST['overlay_rgba_bg'] ) ? array_map( 'sanitize_text_field', $_REQUEST['overlay_rgba_bg'] ) : null ),
				'ttp_gutter'         => ( isset( $_REQUEST['ttp_gutter'] ) ? absint( $_REQUEST['ttp_gutter'] ) : null ),
			];

			$teamQuery          = new \WP_Query( $args );
			$containerDataAttr .= " data-sc-id='{$scID}'";
			$html              .= Fns::layoutStyleGenerator( $layoutID, $cssMeta, $scID );
			$html              .= "<div class='rt-container-fluid rt-team-container {$containerClass}' id='{$layoutID}' {$containerDataAttr}'>";

			if ( $teamQuery->have_posts() ) {
				if ( ! empty( $filters ) && ( $isGrid ) ) {
					$html .= "<div class='rt-layout-filter-container rt-clear'><div class='rt-filter-wrap rt-clear'>";

					if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter ) {
						$filterType = ( ! empty( $_REQUEST['ttp_filter_type'] ) ? sanitize_text_field( $_REQUEST['ttp_filter_type'] ) : null );
						$terms      = Fns::rt_get_all_terms_by_taxonomy( $taxFilter );

						$allSelect      = ' selected';
						$isTermSelected = false;

						if ( $action_term && $taxFilter ) {
							$isTermSelected = true;
							$allSelect      = null;
						}
						$hide_all_button = ! empty( $_REQUEST['ttp_hide_all_button'] );

						if ( ! $filterType || $filterType == 'dropdown' ) {
							$html           .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-dropdown-wrap' data-taxonomy='{$taxFilter}'>";
							$termDefaultText = esc_html__( 'All', 'tlp-team' );
							$dataTerm        = 'all';
							$htmlButton      = '';
							$htmlButton     .= '<span class="term-dropdown rt-filter-dropdown">';

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									if ( $action_term == $id ) {
										$dataTerm = $id;
									}

									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											if ( $action_term == $id ) {
												$termDefaultText = $term;
												$dataTerm        = $id;
											} else {
												$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term}</span>";
											}
										}
									} else {
										if ( $action_term == $id ) {
											$termDefaultText = $term;
											$dataTerm        = $id;
										} else {
											$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='{$id}'>{$term}</span>";
										}
									}
								}
							}

							if ( $isTermSelected ) {
								$htmlButton .= "<span class='term-dropdown-item rt-filter-dropdown-item' data-term='all'>" . esc_html__(
									'All',
									'tlp-team'
								) . '</span>';
							}

							$htmlButton .= '</span>';

							$showAllhtml = '<span class="term-default rt-filter-dropdown-default" data-term="' . $dataTerm . '">
													<span class="rt-text">' . $termDefaultText . '</span>
													<i class="fa fa-angle-down rt-arrow-angle" aria-hidden="true"></i>
												</span>';

							$html .= $showAllhtml . $htmlButton;
							$html .= '</div>';
						} else {
							$html .= "<div class='rt-filter-item-wrap rt-tax-filter rt-filter-button-wrap' data-taxonomy='{$taxFilter}'>";

							if ( ! $hide_all_button ) {
								$html .= "<span class='term-button-item rt-filter-button-item {$allSelect}' data-term='all'>" . esc_html__(
									'All',
									'tlp-team'
								) . '</span>';
							}

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $id => $term ) {
									$termSelected = null;
									if ( $isTermSelected && $id == $action_term ) {
										$termSelected = ' selected';
									}
									if ( is_array( $taxFilterTerms ) && ! empty( $taxFilterTerms ) ) {
										if ( in_array( $id, $taxFilterTerms ) ) {
											$html .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term}</span>";
										}
									} else {
										$html .= "<span class='term-button-item rt-filter-button-item {$termSelected}' data-term='{$id}'>{$term}</span>";
									}
								}
							}

							$html .= '</div>';
						}
					}

					if ( in_array( '_sort_order', $filters ) ) {
						$action_order = ( ! empty( $args['order'] ) ? strtoupper( trim( $args['order'] ) ) : 'DESC' );
						$html        .= '<div class="rt-filter-item-wrap rt-sort-order-action">';
						$html        .= "<span class='rt-sort-order-action-arrow' data-sort-order='{$action_order}'>&nbsp;<span></span></span>";
						$html        .= '</div>';
					}

					if ( in_array( '_order_by', $filters ) ) {
						$orders               = Options::scOrderBy();
						$action_orderby       = ( ! empty( $args['orderby'] ) ? trim( $args['orderby'] ) : 'none' );
						$action_orderby_label = ( $action_orderby == 'none' ? esc_html__(
							'Sort By None',
							'tlp-team'
						) : $orders[ $action_orderby ] );

						if ( $action_orderby !== 'none' ) {
							$orders['none'] = esc_html__( 'Sort By None', 'tlp-team' );
						}

						$html .= '<div class="rt-filter-item-wrap rt-order-by-action rt-filter-dropdown-wrap">';
						$html .= "<span class='order-by-default rt-filter-dropdown-default' data-order-by='{$action_orderby}'>
												<span class='rt-text-order-by'>{$action_orderby_label}</span>
												<i class='fa fa-angle-down rt-arrow-angle' aria-hidden='true'></i>
											</span>";
						$html .= '<span class="order-by-dropdown rt-filter-dropdown">';

						foreach ( $orders as $orderKey => $order ) {
							$html .= '<span class="order-by-dropdown-item rt-filter-dropdown-item" data-order-by="' . $orderKey . '">' . $order . '</span>';
						}
						$html .= '</span>';
						$html .= '</div>';
					}

					if ( in_array( '_search', $filters ) ) {
						$html .= '<div class="rt-filter-item-wrap rt-search-filter-wrap">';
						$html .= "<input type='text' class='rt-search-input' placeholder='Search...'>";
						$html .= "<span class='rt-action'>&#128269;</span>";
						$html .= "<span class='rt-loading'></span>";
						$html .= '</div>';
					}

					$html .= '</div></div>';
				}

				$html .= "<div data-title='" . esc_html__(
					'Loading ...',
					'tlp-team'
				) . "' class='rt-row rt-content-loader {$layout}{$masonryG} {$preLoader}'>";

				if ( $isIsotope && $isoFilterTaxonomy ) {
					$terms          = Fns::rt_get_all_terms_by_taxonomy( $isoFilterTaxonomy );
					$htmlButton     = null;
					$fSelectTrigger = false;
					$tItem          = ! empty( $_REQUEST['ttp_isotope_selected_filter'] ) ? absint( $_REQUEST['ttp_isotope_selected_filter'] ) : null;

					if ( ! empty( $terms ) ) {
						$sltIds = [];

						if ( $isoFilterTaxonomy == rttlp_team()->taxonomies['department'] ) {
							$sltIds = $department_ids;
						} elseif ( $isoFilterTaxonomy == rttlp_team()->taxonomies['designation'] ) {
							$sltIds = $designation_ids;
						}

						foreach ( $terms as $id => $term ) {
							$fSelect = null;

							if ( $tItem == $id ) {
								$fSelect        = 'class="selected"';
								$fSelectTrigger = true;
							}

							$btn = "<button data-filter='.iso_{$id}' {$fSelect}>" . $term . '</button>';

							if ( ! empty( $sltIds ) ) {
								$htmlButton .= in_array( $id, $sltIds ) ? $btn : null;
							} else {
								$htmlButton .= $btn;
							}
						}
					}

					if ( empty( $_REQUEST['ttp_isotope_filter_show_all'] ) ) {
						$fSelect    = ( $fSelectTrigger ? null : 'class="selected"' );
						$htmlButton = "<button data-filter='*' {$fSelect}>" . esc_html__(
							'Show all',
							'tlp-team'
						) . '</button>' . $htmlButton;
					}

					$html .= '<div id="iso-button-' . $rand . '" class="ttp-isotope-buttons button-group filter-button-group">' . $htmlButton . '</div>';

					$html .= "<div class='tlp-team-isotope' id='iso-team-{$rand}'>";
				}
				if ( $isCarousel ) {

					$cOpt            = ! empty( $_REQUEST['ttp_carousel_options'] ) ? array_map( 'sanitize_text_field', $_REQUEST['ttp_carousel_options'] ) : [];
					$autoPlayTimeOut = ! empty( $_REQUEST['ttp_carousel_autoplay_timeout'] ) ? absint( $_REQUEST['ttp_carousel_autoplay_timeout'] ) : 5000;
					$speed           = ! empty( $_REQUEST['ttp_carousel_speed'] ) ? absint( $_REQUEST['ttp_carousel_speed'] ) : 2000;
					$autoPlay        = in_array( 'autoplay', $cOpt, true );
					$stopOnHover     = in_array( 'autoplayHoverPause', $cOpt, true );
					$nav             = in_array( 'nav', $cOpt, true );
					$dots            = in_array( 'dots', $cOpt, true );
					$loop            = in_array( 'loop', $cOpt, true );
					$lazyLoad        = in_array( 'lazy_load', $cOpt, true );
					$autoHeight      = in_array( 'auto_height', $cOpt, true );
					$rtl             = in_array( 'rtl', $cOpt, true );
					$rtlHtml         = $rtl ? 'rtl' : 'ltr';
					$hasDots         = $dots ? ' has-dots' : ' no-dots';
					$hasDots        .= $nav ? ' has-nav' : ' no-nav';
					$navPosition     = 'top';

					if ( 'carousel9' === $layout ) {
						$navPosition = 'bottom';
					}

					$carouselClass = ( $layout != 'carousel10' ? 'swiper rttm-carousel-slider rt-pos-s ' . $navPosition . '-nav' . $hasDots : 'swiper rttm-carousel-main rt-pos-s' );

					$sliderOptions = [
						'slidesPerView'  => (int) ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4,
						'slidesPerGroup' => 1,
						'spaceBetween'   => 0,
						'speed'          => $speed,
						'loop'           => $loop,
						'autoHeight'     => $autoHeight,
						'rtl'            => $rtl,
						'preloadImages'  => ! $lazyLoad,
						'lazy'           => $lazyLoad,
						'breakpoints'    => [
							0   => [
								'slidesPerView' => (int) ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1,
								'pagination'    => [
									'dynamicBullets' => true,
								],
							],
							767 => [
								'slidesPerView' => (int) ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 2,
								'pagination'    => [
									'dynamicBullets' => false,
								],
							],
							991 => [
								'slidesPerView' => (int) ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4,
							],
						],
					];

					if ( 'carousel10' === $layout ) {
						$sliderOptions['breakpoints'] = [
							0   => [
								'slidesPerView' => (int) ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1,
								'pagination'    => [
									'dynamicBullets' => true,
								],
							],
							767 => [
								'slidesPerView' => (int) ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 3,
								'pagination'    => [
									'dynamicBullets' => false,
								],
							],
							991 => [
								'slidesPerView' => (int) ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 5,
							],
						];
					}

					if ( $autoPlay ) {
						$sliderOptions['autoplay'] = [
							'delay'                => $autoPlayTimeOut,
							'pauseOnMouseEnter'    => $stopOnHover,
							'disableOnInteraction' => false,
						];
					}

					if ( 'carousel10' === $layout ) {
						$meta_value = [];

						$meta_value['ttp_carousel_options']  = $cOpt;
						$meta_value['ttp_image']             = $fImg;
						$meta_value['ttp_custom_image_size'] = $customImgSize;
						$meta_value['default_preview_image'] = $defaultImgId;
						$meta_value['ttp_image_size']        = $fImgSize;
						$meta_value['image_style']           = ! empty( $_REQUEST['image_style'] ) ? sanitize_text_field( $_REQUEST['image_style'] ) : null;

						$html .= $this->renderThumbSlider( $scID, $teamQuery, $meta_value, $arg );
						$html .= "<div class='carousel-wrapper rt-pos-r'>";
					}

					$html .= '<div class="rt-carousel-holder ' . esc_attr( $carouselClass ) . '" data-options=' . wp_json_encode( $sliderOptions ) . '  dir="' . esc_attr( $rtlHtml ) . '">';
					$html .= "<div class='swiper-wrapper'>";
				}
				$l5loop = 0;

				// layout 5 table
				if ( $layout == 'layout5' ) {
					$html .= "<table class='table table-striped table-responsive {$round_img}'>";
				}

				while ( $teamQuery->have_posts() ) :
					$teamQuery->the_post();

					if ( $layout == 'layout6' ) {
						$arg['check'] = $this->check;
					}
					/* Argument for single member */
					$mID                = get_the_ID();
					$arg['mID']         = $mID;
					$arg['title']       = get_the_title();
					$cLink              = get_post_meta( $mID, 'ttp_custom_detail_url', true );
					$arg['pLink']       = ( $cLink ? $cLink : get_permalink() );
					$arg['designation'] = strip_tags(
						get_the_term_list(
							$mID,
							rttlp_team()->taxonomies['designation'],
							null,
							', '
						)
					);
					$arg['email']       = get_post_meta( $mID, 'email', true );
					$arg['web_url']     = get_post_meta( $mID, 'web_url', true );
					$arg['telephone']   = get_post_meta( $mID, 'telephone', true );
					$arg['fax']         = get_post_meta( $mID, 'fax', true );
					$arg['mobile']      = get_post_meta( $mID, 'mobile', true );
					$arg['location']    = get_post_meta( $mID, 'location', true );
					$short_bio          = get_post_meta( $mID, 'short_bio', true );
					$arg['short_bio']   = Fns::get_ttp_short_description( $short_bio, $character_limit, $after_short_desc );
					$social             = get_post_meta( $mID, 'social', true );
					$arg['sLink']       = $social ? $social : [];
					$skill              = get_post_meta( $mID, 'skill', true );
					$arg['tlp_skill']   = $skill ? unserialize( $skill ) : [];
					$arg['imgHtml']     = ! $fImg ? Fns::getFeatureImageHtml( $mID, $fImgSize, $defaultImgId, $customImgSize, $lazyLoad ) : null;

					if ( $isIsotope && $isoFilterTaxonomy ) {
						$termAs    = wp_get_post_terms( $mID, $isoFilterTaxonomy, [ 'fields' => 'all' ] );
						$isoFilter = null;
						if ( ! empty( $termAs ) ) {
							foreach ( $termAs as $term ) {
								$isoFilter .= ' ' . 'iso_' . $term->term_id;
							}
						}
						$arg['isoFilter'] = $isoFilter;
					}

					$html .= Fns::render( 'layouts/' . $layout, $arg, true );
					$l5loop ++;

					if ( $l5loop == 2 ) {
						$l5loop = 0;
						if ( $this->check == 1 ) {
							$this->check = 0;
						} else {
							$this->check = 1;
						}
					}
				endwhile;
				if ( $layout == 'layout5' ) {
					$html .= '</table>';
				}

				if ( $isIsotope ) {
					$html .= '</div>'; // end of Isotope.
				}

				if ( $isCarousel ) {
					$html .= '</div>';

					$navHtml = '<div class="swiper-nav"><div class="swiper-arrow swiper-button-next"><i class="fa fa-chevron-right"></i></div><div class="swiper-arrow swiper-button-prev"><i class="fa fa-chevron-left"></i></div></div>';

					if ( 'carousel10' === $layout ) {
						$navHtml = '<div class="swiper-nav-main"><div class="swiper-arrow swiper-button-next"><i class="fa fa-chevron-right"></i></div><div class="swiper-arrow swiper-button-prev"><i class="fa fa-chevron-left"></i></div></div>';
					}

					$html .= $nav ? $navHtml : '';
					$html .= $dots ? '<div class="swiper-pagination rt-pos-s"></div>' : '';

					if ( 'carousel10' === $layout ) {
						$html .= '</div>';
					}

					$html .= '</div>'; // end of carousel.
				}

				$html .= $preLoaderHtml;
				$html .= '</div>'; // end row tlp-team

				if ( $pagination && ! $isCarousel ) {
					$htmlUtility = null;
					$hide        = ( $teamQuery->max_num_pages < 2 ? ' rt-hidden-elm' : null );

					if ( $posts_loading_type == 'pagination' && $isGrid && empty( $filters ) ) {
						$htmlUtility .= Fns::custom_pagination(
							$teamQuery->max_num_pages,
							$args['posts_per_page']
						);
					} elseif ( $posts_loading_type == 'pagination_ajax' && ! $isIsotope ) {
						$htmlUtility .= "<div class='rt-page-numbers'></div>";
					} elseif ( $posts_loading_type == 'load_more' ) {
						$htmlUtility .= "<div class='rt-loadmore-btn rt-loadmore-action rt-loadmore-style{$hide}'>
										<span class='rt-loadmore-text'>" . esc_html__( 'Load More', 'tlp-team' ) . "</span>
										<div class='rt-loadmore-loading rt-ball-scale-multiple rt-2x'><div></div><div></div><div></div></div>
									</div>";

					} elseif ( $posts_loading_type == 'load_on_scroll' ) {
						$htmlUtility .= "<div class='rt-infinite-action'>
											<div class='rt-infinite-loading la-fire la-2x'>
												<div></div>
												<div></div>
												<div></div>
											</div>
										</div>";
					}

					if ( $htmlUtility ) {
						$html .= '<div class="rt-pagination-wrap" data-total-pages="' . absint( $teamQuery->max_num_pages ) . '" data-posts-per-page="' . absint( $args['posts_per_page'] ) . '" data-type="' . esc_attr( $posts_loading_type ) . '">' . $htmlUtility . '</div>';
					}
				}

				wp_reset_postdata();
				// end row

			} else {
				$html .= '<p>' . esc_html__( 'No member found', 'tlp-team' ) . '</p>';
			}

			$html .= '</div>';// end container

			$scriptGenerator               = [];
			$scriptGenerator['layout']     = $layoutID;
			$scriptGenerator['rand']       = $rand;
			$scriptGenerator['isIsotope']  = $isIsotope;
			$scriptGenerator['isCarousel'] = $isCarousel;
			$this->scA[]                   = $scriptGenerator;

		} else {
			$html .= '<p>' . esc_html__( 'No Shortcode found', 'tlp-team' ) . '</p>';
		}

		wp_send_json(
			[
				'error' => $error,
				'msg'   => $msg,
				'data'  => $html,
			]
		);
	}

	public function renderThumbSlider( $scID, $query, $meta_value, $arg ) {
		$html = '';
		$cOpt = ! empty( $meta_value ['ttp_carousel_options'] ) ? $meta_value ['ttp_carousel_options'] : [];

		$fImg          = ! empty( $meta_value ['ttp_image'][0] ) ? true : false;
		$customImgSize = ! empty( $meta_value ['ttp_custom_image_size'][0] ) ? unserialize( $meta_value ['ttp_custom_image_size'][0] ) : [];
		$defaultImgId  = ! empty( $meta_value ['default_preview_image'][0] ) ? absint( $meta_value ['default_preview_image'][0] ) : null;
		$fImgSize      = isset( $meta_value ['ttp_image_size'][0] ) ? $meta_value ['ttp_image_size'][0] : 'medium';
		$round_img     = ! empty( $meta_value ['image_style'][0] ) && $meta_value ['image_style'][0] == 'round' ? esc_attr( ' round-img' ) : '';
		$rtl           = ( in_array( 'rtl', $cOpt ) ? 'dir="rtl"' : '' );

		$html .= "<div {$rtl} class='ttp-carousel-thumb swiper'>";
		$html .= '<div class="swiper-wrapper">';

		while ( $query->have_posts() ) :
			$query->the_post();
			$iID          = get_the_ID();
			$arg['iID']   = $iID;
			$arg['pLink'] = get_permalink();
			$arg['class'] = $round_img;
			$lazyLoad     = in_array( 'lazy_load', $cOpt ) ? true : false;

			$arg['imgHtml'] = $fImg ? null : Fns::getFeatureImageHtml( $iID, $fImgSize, $defaultImgId, $customImgSize, $lazyLoad );

			$html .= Fns::render( 'layouts/carousel_thumb', $arg, true );

		endwhile;

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
