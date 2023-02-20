<?php
/**
 * Frontend Shortcode Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend;

use WP_Query;
use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;
use RT\Team\Helpers\QueryArgs;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Frontend Shortcode Class.
 */
class Shortcode {
	use \RT\Team\Traits\SingletonTrait;

	private $check = 0;
	private $scA   = [];
	private $scId;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_shortcode( 'tlpteam', [ $this, 'team_shortcode' ] );
	}

	function register_scripts() {
		$iso    = false;
		$script = [];
		$style  = [];
		array_push( $script, 'jquery' );
		foreach ( $this->scA as $sc ) {
			if ( isset( $sc ) && is_array( $sc ) ) {
				if ( $sc['isIsotope'] ) {
					$iso = true;
				}
			}
		}

		if ( count( $this->scA ) ) {
			if ( $iso ) {
				array_push( $script, 'tlp-isotope-js' );
			}

			array_push( $script, 'tlp-image-load-js' );
			array_push( $style, 'tlp-scrollbar' );
			array_push( $style, 'tlp-swiper' );
			array_push( $style, 'rt-pagination' );
			array_push( $script, 'rt-pagination' );
			array_push( $script, 'tlp-scrollbar' );
			array_push( $script, 'tlp-swiper' );
			array_push( $style, 'tlp-fontawsome' );
			array_push( $style, 'rt-team-css' );
			array_push( $script, 'rt-tooltip' );
			array_push( $script, 'tlp-actual-height-js' );
			array_push( $script, 'tlp-team-js' );

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
				'tlp-team-js',
				'ttp',
				[
					'ajaxurl' => esc_url( $ajaxurl ),
					'nonceID' => esc_attr( Fns::nonceID() ),
					'nonce'   => esc_attr( $nonce ),
					'lan'     => array_map( 'esc_attr', Options::lan() ),
				]
			);

			$html  = null;
			$html .= '<div class="md-modal tlp-md-effect" id="tlp-modal">
						<div class="md-content">
							<div class="tlp-md-content-holder">

							</div>
							<div class="md-cls-btn">
								<button class="md-close"><i class="fa fa-times" aria-hidden="true"></i></button>
							</div>
						</div>
					</div>';
			$html .= "<div class='md-overlay'></div>";

			Fns::print_html( $html );
		}

	}

	function team_shortcode( $atts ) {
		$rand     = mt_rand();
		$layoutID = 'rt-team-container-' . $rand;
		$html     = null;
		$arg      = [];
		$atts     = shortcode_atts(
			[
				'id' => null,
			],
			$atts,
			'tlpteam'
		);

		$scID = isset( $atts['id'] ) ? absint( $atts['id'] ) : null;

		if ( $scID && ! is_null( get_post( $scID ) ) ) {
			$this->scId = $scID;
			$scMeta     = $this->scMeta = get_post_meta( $scID );
			$buildMetas = $this->metas( $scMeta, $scID );
			$lazyLoad   = false;

			if ( $buildMetas ) {
				extract( $buildMetas );
			}

			if ( ! in_array( $layout, array_keys( Options::scLayout() ) ) ) {
				$layout = 'layout1';
			}

			$isIsotope  = preg_match( '/isotope/', $layout );
			$isCarousel = preg_match( '/carousel/', $layout );
			$isGrid     = preg_match( '/layout/', $layout );
			$isSpecial  = preg_match( '/special/', $layout );

			$dCol = ( ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4 );
			$tCol = ( ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 2 );
			$mCol = ( ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1 );

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
			$args = [];

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
			$arg['grid'] = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol} ";

			if ( ( $layout == 'layout2' ) || ( $layout == 'layout3' ) ) {
				$iCol                = $iCol > 12 ? 4 : $iCol;
				$cCol                = 12 - $iCol;
				$arg['image_area']   = "rt-col-sm-{$iCol} rt-col-xs-12 ";
				$arg['content_area'] = "rt-col-sm-{$cCol} rt-col-xs-12 ";
			}

			$arg['class'] = null;
			$masonryG     = null;

			if ( ! $isCarousel ) {
				$arg['class'] = $gridType . '-grid-item';
			}

			$arg['class'] .= ' rt-grid-item';

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

			if ( $margin == 'no' ) {
				$arg['class'] .= ' no-margin';
			}

			$arg['class']      .= $round_img;
			$arg['image_style'] = ( $scMeta['image_style'][0] );

			$arg['anchorClass'] = null;

			if ( ! $link ) {
				$arg['link']        = false;
				$arg['anchorClass'] = ' disabled';
			} else {
				$arg['link'] = true;
			}

			if ( $link && $linkType == 'popup' && function_exists( 'rttmp' ) ) {
				if ( $popupType == 'single' ) {
					$arg['anchorClass'] .= ' ttp-single-md-popup';
				} elseif ( $popupType == 'multiple' ) {
					$arg['anchorClass'] .= ' ttp-multi-popup';
				} elseif ( $popupType == 'smart' ) {
					$arg['anchorClass'] .= ' ttp-smart-popup';
				}
			}

			$arg['target'] = '_self';

			if ( $link && $linkType == 'new_page' ) {
				$arg['target'] = $target;
			}

			$containerClass  = 'rt-team-container-' . $scID;
			$containerClass .= $parentClass ? ' ' . $parentClass : null;
			$containerClass .= $grayscale ? ' rt-grayscale' : null;

			$arg['items'] = $visibility;

			$args = ( new QueryArgs() )->buildArgs( $buildMetas, $isCarousel );

			$teamQuery          = new WP_Query( $args );
			$containerDataAttr .= " data-sc-id='{$scID}'";
			$containerDataAttr .= " data-popup-bg='{$popupBg}'";
			$html              .= "<div class='rt-container-fluid rt-team-container rt-pos-r {$containerClass}' id='{$layoutID}' {$containerDataAttr}>";

			$taxFilterTerms = [];

			if ( is_array( $department_ids ) && ! empty( $department_ids ) ) {
				$taxFilterTerms = array_merge( $taxFilterTerms, $department_ids );
			}

			if ( ! empty( $designation_ids ) && is_array( $designation_ids ) ) {
				$taxFilterTerms = array_merge( $taxFilterTerms, $designation_ids );
			}

			if ( $teamQuery->have_posts() ) {
				if ( ! empty( $filters ) && ( $isGrid ) && ! $isSpecial ) {
					$html .= "<div class='rt-layout-filter-container rt-clear'><div class='rt-filter-wrap rt-clear'>";
					if ( in_array( '_taxonomy_filter', $filters ) && $taxFilter ) {
						$terms = Fns::rt_get_all_terms_by_taxonomy( $taxFilter );

						$allSelect      = ' selected';
						$isTermSelected = false;

						if ( $action_term && $taxFilter ) {
							$isTermSelected = true;
							$allSelect      = null;
						}

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

					if ( $fShowAll && $htmlButton ) {
						$fSelect = ( $fSelectTrigger ? null : 'class="selected"' );

						$htmlButton = "<button data-filter='*' {$fSelect}>" . esc_html__(
							'Show all',
							'tlp-team'
						) . '</button>' . $htmlButton;
					}

					$html .= '<div id="iso-button-' . $rand . '" class="ttp-isotope-buttons button-group filter-button-group">' . $htmlButton . '</div>';

					$html .= "<div class='tlp-team-isotope' id='iso-team-{$rand}'>";
				}

				if ( $isCarousel ) {
					$autoPlay    = ( in_array( 'autoplay', $cOpt, true ) ? true : false );
					$stopOnHover = ( in_array( 'autoplayHoverPause', $cOpt, true ) ? true : false );
					$nav         = ( in_array( 'nav', $cOpt, true ) ? true : false );
					$dots        = ( in_array( 'dots', $cOpt, true ) ? true : false );
					$loop        = ( in_array( 'loop', $cOpt, true ) ? true : false );
					$lazyLoad    = ( in_array( 'lazy_load', $cOpt, true ) ? true : false );
					$autoHeight  = ( in_array( 'auto_height', $cOpt, true ) ? 1 : false );
					$rtl         = ( in_array( 'rtl', $cOpt, true ) ? true : false );
					$rtlHtml     = $rtl ? 'rtl' : 'ltr';
					$hasDots     = $dots ? ' has-dots' : ' no-dots';
					$hasDots    .= $nav ? ' has-nav' : ' no-nav';
					$navPosition = 'top';

					if ( 'carousel9' === $layout ) {
						$navPosition = 'bottom';
					}

					$carouselClass = ( $layout != 'carousel10' ? 'swiper rttm-carousel-slider rt-pos-s ' . $navPosition . '-nav' . $hasDots : 'swiper rttm-carousel-main rt-pos-s' );

					$sliderOptions = [
						'slidesPerView'  => (int) ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4,
						'slidesPerGroup' => (int) 1,
						'spaceBetween'   => (int) 0,
						'speed'          => (int) absint( $speed ),
						'loop'           => (bool) $loop,
						'autoHeight'     => (bool) $autoHeight,
						'rtl'            => (bool) $rtl,
						'preloadImages'  => (bool) $lazyLoad ? false : true,
						'lazy'           => (bool) $lazyLoad ? true : false,
						'breakpoints'    => [
							0   => [
								'slidesPerView' => (int) ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1,
								'pagination'    => [
									'dynamicBullets' => (bool) true,
								],
							],
							767 => [
								'slidesPerView' => (int) ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 2,
								'pagination'    => [
									'dynamicBullets' => (bool) false,
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
									'dynamicBullets' => (bool) true,
								],
							],
							767 => [
								'slidesPerView' => (int) ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 3,
								'pagination'    => [
									'dynamicBullets' => (bool) false,
								],
							],
							991 => [
								'slidesPerView' => (int) ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 5,
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

					if ( 'carousel10' === $layout ) {
						$html .= $this->renderThumbSlider( $scID, $teamQuery, $scMeta, $arg );
						$html .= "<div class='carousel-wrapper rt-pos-r'>";
					}

					$html .= '<div class="rt-carousel-holder ' . esc_attr( $carouselClass ) . '" data-options=' . wp_json_encode( $sliderOptions ) . '  dir="' . esc_attr( $rtlHtml ) . '">';
					$html .= '<div class="swiper-wrapper">';
				}

				$l5loop = 0;

				// layout 5 table
				if ( $layout == 'layout5' ) {
					$html .= "<table class='table table-striped table-responsive {$round_img}'>";
				}

				if ( $isSpecial ) {
					$html .= "<div class='rt-special-wrapper'>";
					$html .= "<div class='rt-col-sm-4'><div class='rt-row' id='special-selected-wrapper'></div></div>";
					$html .= "<div class='rt-col-sm-8'>";
					$html .= "<div class='rt-row special-items-wrapper'>";
				}

				$i = 1;

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
					$arg['mobile']      = get_post_meta( $mID, 'mobile', true );
					$arg['fax']         = get_post_meta( $mID, 'fax', true );
					$arg['location']    = get_post_meta( $mID, 'location', true );
					$short_bio          = get_post_meta( $mID, 'short_bio', true );
					$arg['short_bio']   = Fns::get_ttp_short_description( $short_bio, $character_limit, $after_short_desc );
					$social             = get_post_meta( $mID, 'social', true );
					$arg['sLink']       = $social ? $social : [];
					$skill              = get_post_meta( $mID, 'skill', true );
					$arg['tlp_skill']   = $skill ? maybe_unserialize( $skill ) : [];
					$arg['imgHtml']     = ! $fImg ? Fns::getFeatureImageHtml( $mID, $fImgSize, $defaultImgId, $customImgSize, $lazyLoad ) : null;

					if ( ! $arg['imgHtml'] ) {
						$arg['content_area'] = 'rt-col-md-12';
					}

					$arg['i']         = $i;
					$arg['isoFilter'] = '';

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
					$l5loop++;

					if ( $l5loop == 2 ) {
						$l5loop = 0;
						if ( $this->check == 1 ) {
							$this->check = 0;
						} else {
							$this->check = 1;
						}
					}

					$i++;
				endwhile;

				if ( $isSpecial ) {
					$html .= '</div>'; // End row
					$html .= '</div>'; // End col
					$html .= '</div>'; // End rt-special-wrapper
				}

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
					$postPp      = $teamQuery->query_vars['posts_per_page'];
					$page        = $teamQuery->query_vars['paged'];
					$foundPosts  = $teamQuery->found_posts;
					$morePosts   = $foundPosts - ( $postPp * $page );
					$totalPage   = $teamQuery->max_num_pages;
					$foundPost   = $teamQuery->found_posts;

					if ( ! rttlp_team()->has_pro() ) {
						$posts_loading_type = 'pagination';
					}

					if ( $scMeta['ttp_limit'][0] ) {
						$range     = $scMeta['ttp_posts_per_page'][0];
						$foundPost = $teamQuery->found_posts;

						if ( $range && $foundPost > $range ) {
							$foundPost = $scMeta['ttp_limit'][0];
							$totalPage = ceil( $foundPost / $range );
						}
					}

					$morePosts  = $foundPost - ( $postPp * $page );
					$foundPosts = $foundPost;
					$totalPage  = absint( $totalPage );
					$morePosts  = absint( $morePosts );

					$hide = ( $teamQuery->max_num_pages < 2 ? ' rt-hidden-elm' : null );

					if ( $posts_loading_type == 'pagination' && $isGrid && empty( $filters ) ) {
						$htmlUtility .= Fns::custom_pagination(
							$totalPage,
							$postPp
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
						$html .= '<div class="rt-pagination-wrap" data-total-pages="' . absint( $totalPage ) . '" data-posts-per-page="' . absint( $postPp ) . '" data-type="' . esc_attr( $posts_loading_type ) . '">' . $htmlUtility . '</div>';
					}
				}

				wp_reset_postdata();
			} else {
				$html .= '<p>' . esc_html__( 'No member found', 'tlp-team' ) . '</p>';
			}

			$html                         .= '</div>'; // end container
			$isIsotope                     = $masonryG == ' ttp-masonry' ? true : $isIsotope;
			$scriptGenerator               = [];
			$scriptGenerator['layout']     = $layoutID;
			$scriptGenerator['rand']       = $rand;
			$scriptGenerator['scMeta']     = $scMeta;
			$scriptGenerator['isIsotope']  = $isIsotope;
			$scriptGenerator['isCarousel'] = $isCarousel;
			$this->scA[]                   = $scriptGenerator;
		} else {

		}

		add_action( 'wp_footer', [ $this, 'register_scripts' ] );

		return $html;
	}

	private function metas( array $meta ) {
		return [
			'layout'             => ! empty( $meta['layout'][0] ) ? esc_attr( $meta['layout'][0] ) : 'layout1',
			'allCol'             => ! empty( $meta['ttp_column'][0] ) ? unserialize( $meta['ttp_column'][0] ) : [],
			'popupBg'            => ! empty( $meta['ttp_popup_bg_color'][0] ) ? esc_attr( $meta['ttp_popup_bg_color'][0] ) : '',
			'postIn'             => ! empty( $meta['ttp_post__in'] ) && is_array( $meta['ttp_post__in'] ) ? $meta['ttp_post__in'] : [],
			'postNotIn'          => ! empty( $meta['ttp_post__not_in'] ) && is_array( $meta['ttp_post__not_in'] ) ? $meta['ttp_post__not_in'] : [],
			'limit'              => ( ( empty( $meta['ttp_limit'][0] ) || $meta['ttp_limit'][0] === '-1' ) ? 10000000 : absint( $meta['ttp_limit'][0] ) ),
			'pagination'         => ! empty( $meta['ttp_pagination'][0] ) ? true : false,
			'posts_loading_type' => ! empty( $meta['ttp_pagination_type'][0] ) ? $meta['ttp_pagination_type'][0] : 'pagination',
			'postsPerPage'       => isset( $meta['ttp_posts_per_page'][0] ) ? absint( $meta['ttp_posts_per_page'][0] ) : '',
			'order_by'           => isset( $meta['order_by'][0] ) ? $meta['order_by'][0] : null,
			'order'              => isset( $meta['order'][0] ) ? $meta['order'][0] : null,
			'department_ids'     => isset( $meta['ttp_departments'] ) ? $meta['ttp_departments'] : [],
			'designation_ids'    => isset( $meta['ttp_designations'] ) ? $meta['ttp_designations'] : [],
			'relation'           => isset( $meta['ttp_taxonomy_relation'][0] ) ? $meta['ttp_taxonomy_relation'][0] : 'AND',
			'iCol'               => ! empty( $meta['ttl_image_column'][0] ) ? absint( $meta['ttl_image_column'][0] ) : 4,
			'gridType'           => ! empty( $meta['grid_style'][0] ) ? $meta['grid_style'][0] : 'even',
			'margin'             => ! empty( $meta['margin_option'][0] ) ? $meta['margin_option'][0] : 'default',
			'round_img'          => ! empty( $meta['image_style'][0] ) && $meta['image_style'][0] == 'round' ? esc_attr( ' round-img' ) : '',
			'link'               => ! empty( $meta['ttp_detail_page_link'][0] ) ? $meta['ttp_detail_page_link'][0] : 0,
			'linkType'           => ! empty( $meta['ttp_detail_page_link_type'][0] ) ? $meta['ttp_detail_page_link_type'][0] : 'external_link',
			'popupType'          => ! empty( $meta['ttp_popup_type'][0] ) ? $meta['ttp_popup_type'][0] : 'single',
			'target'             => ! empty( $meta['ttp_link_target'][0] ) ? $meta['ttp_link_target'][0] : '_self',
			'parentClass'        => ! empty( $meta['ttp_parent_class'][0] ) ? trim( $meta['ttp_parent_class'][0] ) : null,
			'grayscale'          => ! empty( $meta['ttp_grayscale'][0] ) ? trim( $meta['ttp_grayscale'][0] ) : null,
			'fImg'               => ! empty( $meta['ttp_image'][0] ) ? true : false,
			'fImgSize'           => isset( $meta['ttp_image_size'][0] ) ? $meta['ttp_image_size'][0] : 'medium',
			'character_limit'    => isset( $meta['character_limit'][0] ) ? absint( $meta['character_limit'][0] ) : 0,
			'after_short_desc'   => isset( $meta['ttp_after_short_desc_text'][0] ) ? $meta['ttp_after_short_desc_text'][0] : '',
			'defaultImgId'       => ! empty( $meta['default_preview_image'][0] ) ? absint( $meta['default_preview_image'][0] ) : null,
			'customImgSize'      => ! empty( $meta['ttp_custom_image_size'][0] ) ? unserialize( $meta['ttp_custom_image_size'][0] ) : [],
			'visibility'         => ! empty( $meta['ttp_selected_field'] ) ? $meta['ttp_selected_field'] : [ 'name', 'designation', 'short_bio', 'social' ],
			'filters'            => ! empty( $meta['ttp_filter'] ) ? $meta['ttp_filter'] : [],
			'taxFilter'          => ! empty( $meta['ttp_filter_taxonomy'][0] ) ? $meta['ttp_filter_taxonomy'][0] : null,
			'action_term'        => ! empty( $meta['ttp_default_filter'][0] ) ? absint( $meta['ttp_default_filter'][0] ) : 0,
			'isoFilterTaxonomy'  => ! empty( $meta['ttp_isotope_filter_taxonomy'][0] ) ? $meta['ttp_isotope_filter_taxonomy'][0] : 'team_department',
			'filterType'         => ! empty( $meta['ttp_filter_type'][0] ) ? $meta['ttp_filter_type'][0] : null,
			'hide_all_button'    => empty( $meta['ttp_hide_all_button'][0] ) ? false : true,
			'tItem'              => ! empty( $meta['ttp_isotope_selected_filter'][0] ) ? absint( $meta['ttp_isotope_selected_filter'][0] ) : null,
			'fShowAll'           => empty( $meta['ttp_isotope_filter_show_all'][0] ) ? true : false,
			'cOpt'               => ! empty( $meta['ttp_carousel_options'] ) ? $meta['ttp_carousel_options'] : [],
			'autoPlayTimeOut'    => ! empty( $meta['ttp_carousel_autoplay_timeout'][0] ) ? $meta['ttp_carousel_autoplay_timeout'][0] : 5000,
			'speed'              => ! empty( $meta['ttp_carousel_speed'][0] ) ? $meta['ttp_carousel_speed'][0] : 2000,
		];
	}

	public function renderThumbSlider( $scID, $query, $meta_value, $arg ) {
		$html = '';
		$cOpt = ! empty( $meta_value['ttp_carousel_options'] ) ? $meta_value['ttp_carousel_options'] : [];

		$fImg          = ! empty( $meta_value['ttp_image'][0] ) ? true : false;
		$customImgSize = ! empty( $meta_value['ttp_custom_image_size'][0] ) ? unserialize( $meta_value['ttp_custom_image_size'][0] ) : [];
		$defaultImgId  = ! empty( $meta_value['default_preview_image'][0] ) ? absint( $meta_value['default_preview_image'][0] ) : null;
		$fImgSize      = isset( $meta_value['ttp_image_size'][0] ) ? $meta_value['ttp_image_size'][0] : 'medium';
		$round_img     = ! empty( $meta_value['image_style'][0] ) && $meta_value['image_style'][0] == 'round' ? esc_attr( ' round-img' ) : '';
		$rtl           = ( in_array( 'rtl', $cOpt ) ? 'dir="rtl"' : '' );

		$html     .= "<div {$rtl} class='ttp-carousel-thumb swiper'>";
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
		$html     .= '</div>';

		return $html;
	}
}
