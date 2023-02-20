<?php
/**
 * Elementor Slider View Widget Class.
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
 * Elementor Slider View Widget Class.
 */
class SliderView {
	use \RT\Team\Traits\SingletonTrait;

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
		$masonryG      = ' ttp-even';
		$args          = [];
		$scID          = 'elementor';
		$lazyLoad      = false;

		$metas = RenderHelpers::metaBuilder( $prefix, $scMeta );
		$arg   = RenderHelpers::argBuilder( $metas );
		$args  = ( new QueryArgs() )->buildArgs( $metas, true );

		$layout    = $metas['layout'];
		$grayscale = $metas['grayscale'];
		$animation = $metas['animation'];
		$hasModal  = 'popup' === $metas['linkType'] ? true : false;

		if ( ! in_array( $layout, array_keys( Options::scLayout() ), true ) ) {
			$layout = 'carousel-el-1';
		}

		$dCol = 12;
		$tCol = 12;
		$mCol = 12;

		$containerAttr .= ' data-layout="' . esc_attr( $layout ) . '" data-desktop-col="' . absint( $dCol ) . '" data-tab-col="' . absint( $tCol ) . '" data-mobile-col="' . absint( $mCol ) . '"';

		$preLoader     = ' ttp-pre-loader';
		$preLoaderHtml = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>';

		$containerClass  = 'rt-team-container-' . $scID;
		$containerClass .= $grayscale ? ' rt-grayscale' : null;
		$containerClass .= ! empty( $animation ) ? ' rt-hover-' . esc_attr( $animation ) : null;

		$containerAttr .= ' data-sc-id="' . $scID . '"';

		$html .= '<div class="rt-elementor-container rt-pos-r ' . $containerClass . '" id="' . $layoutID . '" ' . $containerAttr . '>';

		$teamQuery = new WP_Query( $args );

		if ( $teamQuery->have_posts() ) {
			$html .= '<div data-title="' . esc_html__(
				'Loading ...',
				'tlp-team'
			) . '" class="rt-content-loader element-loading ' . $layout . $masonryG . $preLoader . '">';

			$sliderData = RenderHelpers::sliderData( $prefix, $scMeta );
			$rtlHtml    = is_rtl() ? 'dir="rtl"' : '';
			$nav        = ! empty( $scMeta[ $prefix . 'slider_nav' ] ) ? true : false;
			$dots       = ! empty( $scMeta[ $prefix . 'slider_pagi' ] ) ? true : false;
			$lazyLoad   = ! empty( $scMeta[ $prefix . 'slider_lazy_load' ] ) ? true : false;

			if ( 'carousel10' === $layout ) {
				$html .= $this->renderThumbSlider( $prefix, $teamQuery, $scMeta, $arg );
				$html .= "<div class='carousel-wrapper rt-pos-r'>";
			}

			$html .= '<div class="rt-carousel-holder slider-loading ' . esc_attr( $sliderData['class'] ) . '" data-options="' . esc_js( $sliderData['data'] ) . '" ' . esc_attr( $rtlHtml ) . '>';
			$html .= "<div class='swiper-wrapper'>";

			while ( $teamQuery->have_posts() ) {
				$teamQuery->the_post();

				$mID = get_the_ID();
				$arg = RenderHelpers::loopArgBuilder( $arg, $metas, $mID, $lazyLoad );

				$html .= Fns::render( 'layouts/' . $layout, $arg, true );
			}

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

			$html .= $preLoaderHtml;
			$html .= '</div>';

			wp_reset_postdata();
		} else {
			$html .= '<p>' . esc_html__( 'No team members found', 'tlp-team' ) . '</p>';
		}

		$html .= '</div>';

		$scriptGenerator                  = [];
		$scriptGenerator['layout']        = $layoutID;
		$scriptGenerator['rand']          = $rand;
		$scriptGenerator['isIsotope']     = false;
		$scriptGenerator['isCarousel']    = true;
		$scriptGenerator['hasPagination'] = false;
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
	 * Renders Thumb Slider.
	 *
	 * @param integer $prefix Meta prefix.
	 * @param object  $query WP Query Object.
	 * @param array   $meta Meta Value.
	 * @param array   $arg Arg.
	 * @return string
	 */
	public function renderThumbSlider( $prefix, $query, $meta, $arg ) {
		$html = '';

		$fImg          = ! empty( $meta[ $prefix . 'show_featured_image' ] ) ? false : true;
		$customImgSize = ! empty( $cImageSize ) && is_array( $cImageSize ) ? $cImageSize : [];
		$defaultImgId  = ! empty( $meta[ $prefix . 'default_preview_image' ]['id'] ) ? absint( $meta[ $prefix . 'default_preview_image' ]['id'] ) : null;
		$fImgSize      = ! empty( $meta[ $prefix . 'image' ] ) ? esc_html( $meta[ $prefix . 'image' ] ) : 'medium';

		$html     .= '<div class="ttp-carousel-thumb swiper slider-loading">';
			$html .= '<div class="swiper-wrapper">';

		while ( $query->have_posts() ) :
			$query->the_post();
			$iID          = get_the_ID();
			$arg['iID']   = $iID;
			$arg['pLink'] = get_permalink();
			$lazyLoad     = ! empty( $meta[ $prefix . 'slider_lazy_load' ] ) ? true : false;

			$arg['imgHtml'] = $fImg ? null : Fns::getFeatureImageHtml( $iID, $fImgSize, $defaultImgId, $customImgSize, $lazyLoad );

			$html .= Fns::render( 'layouts/carousel_thumb', $arg, true );

			endwhile;

			$html .= '</div>';
		$html     .= '</div>';

		return $html;
	}
}
