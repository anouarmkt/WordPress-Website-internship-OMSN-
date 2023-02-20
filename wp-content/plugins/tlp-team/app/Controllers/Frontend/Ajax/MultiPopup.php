<?php
/**
 * Multi Popup Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend\Ajax;

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Multi Popup Ajax Class.
 */
class MultiPopup {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlp_multi_popup_single', [ $this, 'response' ] );
		add_action( 'wp_ajax_nopriv_tlp_multi_popup_single', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$html    = $htmlCInfo = null;
		$success = false;
		$error   = true;

		if ( isset( $_REQUEST['id'] ) && $post_id = absint( $_REQUEST['id'] ) ) {
			global $post;
			$post = get_post( absint( $_REQUEST['id'] ) );

			if ( $post && $post->post_type == rttlp_team()->post_type ) {
				setup_postdata( $post );
				$settings                 = get_option( rttlp_team()->options['settings'] );
				$fields                   = isset( $settings['detail_page_fields'] ) ? $settings['detail_page_fields'] : [];
				$sLink                    = get_post_meta(
					$post->ID,
					'social',
					true
				);
				$tlp_skill                = unserialize( get_post_meta( $post->ID, 'skill', true ) );
				$name                     = $post->post_title;
				$tlp_member_content       = apply_filters( 'the_content', get_the_content() );
				$email                    = get_post_meta( $post->ID, 'email', true );
				$web_url                  = get_post_meta( $post->ID, 'web_url', true );
				$telephone                = get_post_meta( $post->ID, 'telephone', true );
				$mobile                   = get_post_meta( $post->ID, 'mobile', true );
				$fax                      = get_post_meta( $post->ID, 'fax', true );
				$location                 = get_post_meta( $post->ID, 'location', true );
				$experience_year          = get_post_meta( $post->ID, 'experience_year', true );
				$short_bio                = get_post_meta( $post->ID, 'short_bio', true );
				$designation              = strip_tags(
					get_the_term_list(
						$post->ID,
						rttlp_team()->taxonomies['designation'],
						null,
						', '
					)
				);
				$tag_line                 = get_post_meta( $post->ID, 'ttp_tag_line', true );
				$qualifications           = get_post_meta( $post->ID, 'ttp_qualifications', true );
				$professional_memberships = get_post_meta( $post->ID, 'ttp_professional_memberships', true );
				$area_of_expertise        = get_post_meta( $post->ID, 'ttp_area_of_expertise', true );
				$html                    .= "<div class='rt-container tlp-team rt-team-container'>";
				$html                    .= "<div class='rt-row tlp-detail'>";
				$html                    .= "<div class='rt-col-lg-5 rt-col-md-5 rt-col-sm-6 rt-col-xs-12 team-images'>";
				$html                    .= Fns::memberDetailGallery( $post->ID );
				$html                    .= '</div>';
				$html                    .= "<div class='rt-col-lg-7 rt-col-md-7 rt-col-sm-6 rt-col-xs-12'>";

				if ( in_array( 'name', $fields ) ) {
					$html .= '<h3 class="member-name">' . esc_html( $name ) . '</h3>';
				}

				$html .= Fns::get_formatted_designation( $designation, $fields, $experience_year );

				if ( $tag_line && in_array( 'ttp_tag_line', $fields ) ) {
					$html .= '<div class="tlp-tag-line">' . Fns::htmlKses( $tag_line, 'basic' ) . '</div>';
				}

				if ( $tlp_member_content && in_array( 'content', $fields ) ) {
					$html .= '<div class="tlp-member-detail">' . Fns::htmlKses( $tlp_member_content, 'basic' ) . '</div>';
				}

				if ( $qualifications && in_array( 'ttp_qualifications', $fields ) ) {
					$html .= '<div class="rt-extra-curriculum"><strong>' . esc_html__(
						'Qualifications : ',
						'tlp-team'
					) . '</strong>' . Fns::htmlKses( $qualifications, 'basic' ) . '</div>';
				}

				if ( $professional_memberships && in_array( 'ttp_professional_memberships', $fields ) ) {
					$html .= '<div class="rt-extra-curriculum"><strong>' . esc_html__(
						'Professional Memberships : ',
						'tlp-team'
					) . '</strong>' . Fns::htmlKses( $professional_memberships, 'basic' ) . '</div>';
				}

				if ( $area_of_expertise && in_array( 'ttp_area_of_expertise', $fields ) ) {
					$html .= '<div class="rt-extra-curriculum"><strong>' . esc_html__(
						'Area of Expertise : ',
						'tlp-team'
					) . '</strong>' . Fns::htmlKses( $area_of_expertise, 'basic' ) . '</div>';
				}

				$html .= Fns::get_formatted_short_bio( $short_bio, $fields );
				$html .= Fns::get_formatted_contact_info(
					[
						'email'     => $email,
						'telephone' => $telephone,
						'mobile'    => $mobile,
						'fax'       => $fax,
						'location'  => $location,
						'web_url'   => $web_url,
					],
					$fields
				);
				$html .= Fns::get_formatted_skill( $tlp_skill, $fields );
				$html .= Fns::get_formatted_social_link( $sLink, $fields );

				if ( in_array( 'author_post', $fields ) ) {
					$html .= Fns::memberDetailPosts( $post->ID );
				}

				$html   .= '</div>';
				$html   .= '</div>'; // End row
				$html   .= '</div>'; // End tlp-container
				$html   .= '</div>';
				$html   .= '<script>tlpSingleTeamScript();</script>';
				$success = true;

				wp_reset_postdata();
			} else {
				$html .= '<p>' . esc_html__( 'Selected is is not for team member', 'tlp-team' ) . '</p>';
				$error = true;
			}
		} else {
			$html .= '<p>' . esc_html__( 'No item id found', 'tlp-team' ) . '</p>';
			$error = true;
		}

		wp_send_json(
			[
				'data'    => $html,
				'error'   => $error,
				'success' => $success,
			]
		);
		die();
	}
}
