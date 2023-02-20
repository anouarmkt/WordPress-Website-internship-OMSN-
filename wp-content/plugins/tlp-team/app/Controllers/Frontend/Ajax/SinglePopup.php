<?php
/**
 * Single Popup Ajax Class.
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
 * Single Popup Ajax Class.
 */
class SinglePopup {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlp_md_popup_single', [ $this, 'response' ] );
		add_action( 'wp_ajax_nopriv_tlp_md_popup_single', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$html  = $htmlCInfo = null;
		$error = true;

		if ( isset( $_REQUEST['id'] ) && $post_id = absint( $_REQUEST['id'] ) ) {
			global $post;
			$post = get_post( absint( $_REQUEST['id'] ) );

			if ( $post && $post->post_type == rttlp_team()->post_type ) {
				$error = false;
				setup_postdata( $post );

				$email                    = get_post_meta( $post->ID, 'email', true );
				$web_url                  = get_post_meta( $post->ID, 'web_url', true );
				$telephone                = get_post_meta( $post->ID, 'telephone', true );
				$mobile                   = get_post_meta( $post->ID, 'mobile', true );
				$fax                      = get_post_meta( $post->ID, 'fax', true );
				$location                 = get_post_meta( $post->ID, 'location', true );
				$short_bio                = get_post_meta( $post->ID, 'short_bio', true );
				$tlp_skill                = get_post_meta( $post->ID, 'skill', true );
				$tlp_skill                = $tlp_skill ? unserialize( $tlp_skill ) : [];
				$sLink                    = get_post_meta( $post->ID, 'social', true );
				$sLink                    = $sLink ? $sLink : [];
				$name                     = $post->post_title;
				$settings                 = get_option( rttlp_team()->options['settings'] );
				$fields                   = isset( $settings['detail_page_fields'] ) ? $settings['detail_page_fields'] : [];
				$designation              = strip_tags(
					get_the_term_list(
						$post->ID,
						rttlp_team()->taxonomies['designation'],
						null,
						', '
					)
				);
				$experience_year          = get_post_meta( $post->ID, 'experience_year', true );
				$tag_line                 = get_post_meta( $post->ID, 'ttp_tag_line', true );
				$qualifications           = get_post_meta( $post->ID, 'ttp_qualifications', true );
				$professional_memberships = get_post_meta( $post->ID, 'ttp_professional_memberships', true );
				$area_of_expertise        = get_post_meta( $post->ID, 'ttp_area_of_expertise', true );
				$html                    .= "<div class='md-header'>";
				$html                    .= '<h3>' . esc_html( $name ) . '</h3>';

				if ( $designation && in_array( 'designation', $fields ) ) {
					$exp = null;

					if ( $experience_year && in_array( 'experience_year', $fields ) ) {
						$exp = '<span class="experience">(' . esc_html( $experience_year ) . ')</span>';
					}

					$html .= '<h4>' . esc_html( $designation ) . Fns::htmlKses( $exp, 'basic' ) . '</h4>';
				}

				if ( $tag_line && in_array( 'ttp_tag_line', $fields ) ) {
					$html .= '<div class="tlp-tag-line">' . Fns::htmlKses( $tag_line, 'basic' ) . '</div>';
				}

				$html .= Fns::get_formatted_social_link( $sLink, $fields );
				$html .= '</div>';
				$html .= '<div class="tlp-md-content rt-team-container">';
				$html .= Fns::memberDetailGallery( $post->ID );
				$html .= Fns::get_formatted_short_bio( $short_bio, $fields );

				if ( $post->post_content && in_array( 'content', $fields ) ) {
					$html .= "<div class='tlp-md-member-details'>" . apply_filters(
						'the_content',
						get_the_content()
					) . '</div>';
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

				$html .= Fns::get_formatted_skill( $tlp_skill, $fields );

				$html .= "<div class='tlp-team'>";
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

				if ( in_array( 'author_post', $fields ) ) {
					$html .= Fns::memberDetailPosts( $post->ID );
				}

				$html .= '</div>';

				$html .= '</div>';
				$html .= '<script>mdPopUpSkillAnimation();</script>';
				wp_reset_postdata();
			} else {
				$html .= '<p>' . esc_html__( 'Selected is is not for team member', 'tlp-team' ) . '</p>';
			}
		} else {
			$html .= '<p>' . esc_html__( 'No item id found', 'tlp-team' ) . '</p>';
		}

		wp_send_json(
			[
				'data'  => wp_kses_post( $html ),
				'error' => $error,
			]
		);
		die();
	}
}
