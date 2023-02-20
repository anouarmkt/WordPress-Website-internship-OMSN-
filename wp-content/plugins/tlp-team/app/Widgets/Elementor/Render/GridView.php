<?php
/**
 * Elementor Grid View Widget Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Render;

use WP_Query;
use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;
use RT\Team\Helpers\QueryArgs;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Grid View Widget Class.
 */
class GridView {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Layout check
	 *
	 * @var integer
	 */
	private $check = 0;

	/**
	 * Class init.
	 *
	 * @return void
	 */
	protected function init() {}

	/**
	 * Render frontend.
	 *
	 * @param string $prefix Field prefix.
	 * @param array  $scMeta Elementor settings.
	 * @return mixed
	 */
	public function render( $prefix, $scMeta ) {
		$rand          = absint( wp_rand() );
		$layoutID      = 'rt-team-container-' . $rand;
		$html          = null;
		$containerAttr = null;
		$masonryG      = null;
		$args          = [];
		$scID          = 'elementor';

		$metas = RenderHelpers::metaBuilder( $prefix, $scMeta );
		$arg   = RenderHelpers::argBuilder( $metas );
		$args  = ( new QueryArgs() )->buildArgs( $metas, false );

		$layout     = $metas['layout'];
		$gridType   = $metas['gridType'];
		$grayscale  = $metas['grayscale'];
		$animation  = $metas['animation'];
		$pagination = $metas['pagination'];
		$hasModal   = 'popup' === $metas['linkType'] ? true : false;

		if ( ! in_array( $layout, array_keys( Options::scLayout() ), true ) ) {
			$layout = 'layout1';
		}

		$isIsotope  = preg_match( '/isotope/', $layout );
		$isSpecial  = preg_match( '/special/', $layout );
		$isCarousel = 'popup' === $metas['linkType'] ? true : false;

		$dCol = 0 === $metas['dCols'] ? RenderHelpers::defaultColumns( $layout ) : $metas['dCols'];
		$tCol = 0 === $metas['tCols'] ? 2 : $metas['tCols'];
		$mCol = 0 === $metas['mCols'] ? 1 : $metas['mCols'];

		$containerAttr .= ' data-layout="' . esc_attr( $layout ) . '" data-desktop-col="' . absint( $dCol ) . '" data-tab-col="' . absint( $tCol ) . '" data-mobile-col="' . absint( $mCol ) . '"';

		if ( 'even' === $gridType ) {
			$masonryG = ' ttp-even';
		} elseif ( 'masonry' === $gridType ) {
			$masonryG = ' ttp-masonry';
		}

		$preLoader     = ' ttp-pre-loader';
		$preLoaderHtml = '<div class="rt-loading-overlay full-op"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';

		$containerClass  = 'rt-team-container-' . $scID;
		$containerClass .= $grayscale ? ' rt-grayscale' : null;
		$containerClass .= ! empty( $animation ) ? ' rt-hover-' . esc_attr( $animation ) : null;

		$containerAttr .= ' data-sc-id="' . $scID . '"';

		$html .= '<div class="rt-elementor-container rt-pos-r ' . $containerClass . '" id="' . $layoutID . '" ' . $containerAttr . RenderHelpers::elPaginationData( $prefix, $scMeta ) . '>';

		$teamQuery = new WP_Query( $args );

		if ( $teamQuery->have_posts() ) {
			$l5loop = 0;

			$html .= $this->renderGridFilter( $prefix . 'render_grid_filters', $metas, $args );

			$html .= '<div data-title="' . esc_html__(
				'Loading ...',
				'tlp-team'
			) . '" class="rt-row rt-content-loader element-loading ' . $layout . $masonryG . $preLoader . '">';

			if ( 'layout5' === $layout ) {
				$stripedRow = $metas['stripedRow'] ? ' table-striped' : '';
				$html      .= '<div class="rt-col-xs-12"><div class="table-responsive"><table class="table' . esc_attr( $stripedRow ) . '">';
			}

			if ( $isSpecial ) {
				$html .= "<div class='rt-el-special-wrapper'>";
				$html .= "<div class='rt-col-sm-4'><div class='rt-row' id='special-selected-wrapper'></div></div>";
				$html .= "<div class='rt-col-sm-8'>";
				$html .= "<div class='rt-row special-items-wrapper'>";
			}

			$i = 1;

			while ( $teamQuery->have_posts() ) {
				$teamQuery->the_post();

				if ( 'layout-el-6' === $layout ) {
					$arg['check'] = $this->check;
				}

				$mID      = get_the_ID();
				$arg      = RenderHelpers::loopArgBuilder( $arg, $metas, $mID );
				$arg['i'] = $i;

				$html .= Fns::render( 'layouts/' . $layout, $arg, true );

				$l5loop++;

				if ( 2 === $l5loop ) {
					$l5loop = 0;
					if ( 1 === $this->check ) {
						$this->check = 0;
					} else {
						$this->check = 1;
					}
				}

				$i++;
			}

			if ( $isSpecial ) {
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}

			if ( 'layout5' === $layout ) {
				$html .= '</table></div></div>';
			}

			if ( empty( $metas['filters'] ) ) {
				$html .= $preLoaderHtml;
			}

			$html .= '</div>';
			// $html .= $preLoaderHtml;

			if ( $pagination ) {
				$html .= RenderHelpers::renderPagination(
					$teamQuery,
					$metas,
					$scMeta[ $prefix . 'posts_limit' ],
					$scMeta[ $prefix . 'pagination_per_page' ],
					$scMeta[ $prefix . 'show_page_number' ],
				);
			}

			wp_reset_postdata();
		} else {
			$html .= '<p>' . esc_html__( 'No team members found', 'tlp-team' ) . '</p>';
		}

		$html .= '</div>';

		$isIsotope                        = ' ttp-masonry' === $masonryG ? true : $isIsotope;
		$scriptGenerator                  = [];
		$scriptGenerator['layout']        = $layoutID;
		$scriptGenerator['rand']          = $rand;
		$scriptGenerator['isIsotope']     = $isIsotope;
		$scriptGenerator['isCarousel']    = $isCarousel;
		$scriptGenerator['hasPagination'] = $pagination;
		$scriptGenerator['hasModal']      = $hasModal;

		add_action(
			'wp_footer',
			static function () use ( $scriptGenerator ) {
				RenderHelpers::registerScripts( $scriptGenerator );
			}
		);

		return $html;
	}

	/**
	 * Renders Grid Filter
	 *
	 * @param string $prefix Prefix.
	 * @param array  $meta Meta values.
	 * @param array  $args Args.
	 * @return string
	 */
	private function renderGridFilter( $prefix, $meta, $args ) {
		if ( ! rttlp_team()->has_pro() ) {
			return '';
		}

		return apply_filters( $prefix, $meta, $args );
	}
}
