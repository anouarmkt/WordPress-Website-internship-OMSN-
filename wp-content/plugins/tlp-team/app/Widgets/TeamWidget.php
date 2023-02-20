<?php
/**
 * Team Widget.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets;

use WP_Widget;
use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Team Widget.
 */
class TeamWidget extends WP_Widget {

	/**
	 * TLP TEAM widget setup
	 */
	function __construct() {
		$widget_ops = [
			'classname'   => 'widget_tlpTeam',
			'description' => esc_html__( 'Display the Team.', 'tlp-team' ),
		];
		parent::__construct( 'widget_tlpTeam', esc_html__( 'Team Widget', 'tlp-team' ), $widget_ops );
	}

	/**
	 * Display the widgets on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$member = ( isset( $instance['member'] ) ? ( $instance['member'] ? (int) $instance['member'] : 2 ) : 2 );

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', esc_html( $instance['title'] ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$args = [
			'post_type'      => 'team',
			'post_status'    => 'publish',
			'posts_per_page' => $member,
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$teamQuery = new \WP_Query( $args );
		$html      = null;
		$settings  = get_option( rttlp_team()->options['settings'] );

		$fName        = in_array( 'name', $settings['detail_page_fields'], true );
		$fDesignation = in_array( 'designation', $settings['detail_page_fields'], true );
		$fShort_bio   = in_array( 'short_bio', $settings['detail_page_fields'], true );
		$fSocial      = in_array( 'social', $settings['detail_page_fields'], true );
		$html        .= "<div class='tlp-teamul tlp-row tlp-team'>";

		if ( $teamQuery->have_posts() ) {
			while ( $teamQuery->have_posts() ) :
				$teamQuery->the_post();

				if ( has_post_thumbnail() ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), rttlp_team()->options['feature_img_size'] );
					$img   = $image[0];
				} else {
					$img = rttlp_team()->assets_url() . 'images/demo.jpg';
				}

				$bio         = get_post_meta( get_the_ID(), 'short_bio', true );
				$designation = strip_tags(
					get_the_term_list(
						get_the_ID(),
						rttlp_team()->taxonomies['designation'],
						null,
						', '
					)
				);

				$html .= "<div class='tlp-member rt-col-4'>
						<div class='tlp-thum'><img src='" . esc_url( $img ) . "' /></div>
						<div class='widget_des'>";

				if ( $fName && get_the_title() ) {
					$html .= "<h2 class='name'><a href='" . get_the_permalink() . "'>" . get_the_title() . '</a></h2>';
				}

				$html .= '<div class="widget-short-desc">';

				if ( $designation && $fDesignation ) {
					$html .= '<h4 class="designation">' . esc_html( $designation ) . '</h4>';
				}

				if ( $bio && $fShort_bio ) {
					$html .= '<div class="short-bio">' . Fns::htmlKses( $bio, 'basic' ) . '</div>';
				}

				$html .= '</div>';
				$html .= '</div>';

				$sLink = get_post_meta( get_the_ID(), 'social', true );

				if ( ! empty( $sLink ) && is_array( $sLink ) && $fSocial ) {
					$html .= '<ul class="tpl-social">';
					foreach ( $sLink as $id => $link ) {
						$html .= '<li><a class="fa fa-' . esc_attr( $link['id'] ) . '" href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['id'] ) . '" target="_blank"></a></li>';
					}
					$html .= '</ul>';
				}

				$html .= '</div>';
			endwhile;

			wp_reset_postdata();
		} else {
			$html .= '<p>' . __( 'No member found', 'tlp-team' ) . '</p>';
		}

		$html .= '</div>';

		Fns::print_html( $html );

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	function form( $instance ) {

		$defaults = [
			'title'  => '',
			'member' => 4,
		];

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'tlp-team' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'member' ) ); ?>"><?php esc_html_e( 'Number of member to show:', 'tlp-team' ); ?></label>
			<input type="text" size="2" id="<?php echo esc_attr( $this->get_field_id( 'member' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'member' ) ); ?>"
				value="<?php echo esc_attr( $instance['member'] ); ?>"/>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance           = [];
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['member'] = ( ! empty( $new_instance['member'] ) ) ? (int) ( $new_instance['member'] ) : '';

		return $instance;
	}
}
