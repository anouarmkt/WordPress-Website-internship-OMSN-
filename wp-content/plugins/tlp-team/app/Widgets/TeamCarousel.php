<?php
/**
 * Team Carousel Widget.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets;

use WP_Widget;
use RT\Team\Helpers\Options;
use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Team Carousel Widget.
 */
class TeamCarousel extends WP_Widget {
	private $caroA = [];

	/**
	 * TLP TEAM widget setup
	 */
	function __construct() {
		$widget_ops = [
			'classname'   => 'widget_tlp_team_owl_carousel',
			'description' => esc_html__( 'Display the Team as carousel.', 'tlp-team' ),
		];
		parent::__construct( 'widget_tlp_team_owl_carousel', esc_html__( 'Team Widget (With Carousel)', 'tlp-team' ), $widget_ops );
	}

	/**
	 * Display the widgets on the screen.
	 */
	function widget( $args, $instance ) {

		$caroID = $args['widget_id'] . '-carousel';
		extract( $args );

		$title = ( isset( $instance['title'] ) ? $instance['title'] : 'TLP Team' );
		$total = ( isset( $instance['total'] ) ? (int) $instance['total'] : 8 );

		$cA          = [];
		$cA['items'] = ( isset( $instance['number'] ) ? (int) $instance['number'] : 4 );
		$cA['speed'] = ( isset( $instance['speed'] ) ? (int) $instance['speed'] : 1000 );

		foreach ( Options::swiperProperty() as $key => $value ) {
			$cA[ $key ] = ( isset( $instance[ $key ] ) && $instance[ $key ] ? 'true' : 'false' );
		}

		$autoPlay    = 'true' === $cA['autoplay'] ? true : false;
		$stopOnHover = 'true' === $cA['autoplayHoverPause'] ? true : false;
		$nav         = 'true' === $cA['nav'] ? true : false;
		$dots        = 'true' === $cA['dots'] ? true : false;
		$loop        = 'true' === $cA['loop'] ? true : false;
		$lazyLoad    = 'true' === $cA['lazyLoad'] ? true : false;
		$autoHeight  = 'true' === $cA['autoHeight'] ? true : false;
		$rtl         = 'true' === $cA['rtl'] ? true : false;
		$rtlHtml     = $rtl ? 'rtl' : 'ltr';
		$hasDots     = $dots ? ' has-dots' : ' no-dots';
		$hasDots    .= $nav ? ' has-nav' : ' no-nav';

		$sliderOptions = [
			'slidesPerView'  => (int) ! empty( $cA['items'] ) ? absint( $cA['items'] ) : 4,
			'slidesPerGroup' => (int) 1,
			'spaceBetween'   => (int) 0,
			'speed'          => (int) absint( $cA['speed'] ),
			'loop'           => (bool) $loop,
			'autoHeight'     => (bool) $autoHeight,
			'rtl'            => (bool) $rtl,
			'preloadImages'  => (bool) $lazyLoad ? false : true,
			'lazy'           => (bool) $lazyLoad ? true : false,
		];

		if ( $autoPlay ) {
			$sliderOptions['autoplay'] = [
				'delay'                => 5000,
				'pauseOnMouseEnter'    => (bool) $stopOnHover,
				'disableOnInteraction' => (bool) false,
			];
		}

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', esc_html( $title ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$args_q = [
			'post_type'      => rttlp_team()->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $total,
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$teamQuery = new \WP_Query( $args_q );
		$html      = '<div class="tlp-widget-holder rt-pos-r">';
		$html     .= '<div div id=' . esc_attr( $caroID ) . ' class="rt-carousel-holder swiper rttm-carousel-slider standard-nav rt-pos-s ' . esc_attr( $hasDots ) . '" data-options=' . wp_json_encode( $sliderOptions ) . ' dir="' . esc_attr( $rtlHtml ) . '">';
		$html     .= '<div class="swiper-wrapper">';

		if ( $teamQuery->have_posts() ) {
			while ( $teamQuery->have_posts() ) :
				$teamQuery->the_post();
				$mID      = get_the_id();
				$fImgSize = 'medium';

				$html .= "<div class='item swiper-slide'>";
				$html .= Fns::getFeatureImageHtml( $mID, $fImgSize );
				$html .= '</div>';
			endwhile;
			wp_reset_postdata();
		} else {
			$html .= '<p>' . esc_html__( 'No member found', 'tlp-team' ) . '</p>';
		}
		$html   .= '</div>';
		$navHtml = '<div class="swiper-nav"><div class="swiper-arrow swiper-button-next"><i class="fa fa-chevron-right"></i></div><div class="swiper-arrow swiper-button-prev"><i class="fa fa-chevron-left"></i></div></div>';

		$html .= $nav ? $navHtml : '';
		$html .= $dots ? '<div class="swiper-pagination rt-pos-s"></div>' : '';
		$html .= '</div>';
		$html .= '</div>';

		Fns::print_html( $html, true );

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		add_action( 'wp_footer', [ $this, 'register_scripts' ] );
		add_action( 'wp_footer', [ $this, 'low_footer_script' ], 100 );
	}

	function register_scripts() {
		wp_enqueue_style(
			[
				'tlp-fontawsome',
				'tlp-swiper',
				'rt-team-css',
			]
		);

		wp_enqueue_script(
			[
				'tlp-swiper',
				'rt-tooltip',
				'tlp-actual-height-js',
				'tlp-team-js',
			]
		);

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
				'ajaxurl' => $ajaxurl,
				'nonceID' => Fns::nonceID(),
				'nonce'   => $nonce,
				'lan'     => array_map( 'esc_attr', Options::lan() ),
			]
		);
	}

	function low_footer_script() {
		?>
		<script>(function ($) {
				rtSliderInit($);
			})(jQuery)</script>
		<?php
	}

	function form( $instance ) {

		$defaults = [
			'number'    => 4,
			'total'     => 8,
			'speed'     => 5000,
			'auto_play' => 1,
		];
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'tlp-team' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				   value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;"/></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of member per slide:', 'tlp-team' ); ?></label>
			<input type="text" size="2" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
				   value="<?php echo esc_attr( $instance['number'] ); ?>"/></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'total' ) ); ?>"><?php esc_html_e( 'Total Number of member:', 'tlp-team' ); ?></label>
			<input type="text" size="2" id="<?php echo esc_attr( $this->get_field_id( 'total' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'total' ) ); ?>"
				   value="<?php echo esc_attr( $instance['total'] ); ?>"/></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"><?php esc_html_e( 'Slide Speed:', 'tlp-team' ); ?></label>
			<input type="text" size="4" id="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'speed' ) ); ?>"
				   value="<?php echo esc_attr( $instance['speed'] ); ?>"/></p>
		<?php

		$options = Options::swiperProperty();

		if ( ! empty( $options ) ) {
			echo '<p>';
			foreach ( $options as $key => $value ) {
				$checked = ( isset( $instance[ $key ] ) ? ( $instance[ $key ] ? 'checked' : null ) : null );
				echo '<input type="checkbox" ' . esc_attr( $checked ) . ' value="1" class="checkbox" id="' . esc_attr( $this->get_field_id( $key ) ) . '" name="' . esc_attr( $this->get_field_name( $key ) ) . '"><label for="' . esc_attr( $this->get_field_id( $key ) ) . '">' . esc_html( $value ) . '</label><br>';
			}

			echo '</p>';
		}
	}

	public function update( $new_instance, $old_instance ) {

		$instance           = [];
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? (int) ( $new_instance['number'] ) : '';
		$instance['total']  = ( ! empty( $new_instance['total'] ) ) ? (int) ( $new_instance['total'] ) : '';
		$instance['speed']  = ( ! empty( $new_instance['speed'] ) ) ? (int) ( $new_instance['speed'] ) : '';

		$options = Options::swiperProperty();

		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $value ) {
				$instance[ $key ] = ( ! empty( $new_instance[ $key ] ) ) ? (int) ( $new_instance[ $key ] ) : '';
			}
		}

		return $instance;
	}
}
