<?php
/**
 * Template: Single team view.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

get_header();

global $post;
$settings     = get_option( rttlp_team()->options['settings'] );
$fields       = isset( $settings['detail_page_fields'] ) ? $settings['detail_page_fields'] : [ 'name', 'designation', 'short_bio', 'email', 'web_url', 'telephone', 'mobile', 'fax', 'location', 'social' ];
$page_wrapper = ! empty( $settings['detail_page_wrapper'] ) ? $settings['detail_page_wrapper'] : 'rt-container-fluid';
$iCol         = ! empty( $settings['detail_image_column'] ) ? absint( $settings['detail_image_column'] ) : 5;
$iCol         = $iCol > 12 ? 5 : $iCol;
$cCol         = 12 - $iCol;
$image_area   = "rt-col-sm-{$iCol} rt-col-xs-12 ";
$content_area = "rt-col-sm-{$cCol} rt-col-xs-12 ";

while ( have_posts() ) :
	the_post();

	$html            = $htmlCInfo = null;
	$email           = get_post_meta( $post->ID, 'email', true );
	$web_url         = get_post_meta( $post->ID, 'web_url', true );
	$telephone       = get_post_meta( $post->ID, 'telephone', true );
	$mobile          = get_post_meta( $post->ID, 'mobile', true );
	$location        = get_post_meta( $post->ID, 'location', true );
	$experience_year = get_post_meta( $post->ID, 'experience_year', true );
	$short_bio       = get_post_meta( $post->ID, 'short_bio', true );
	$socialLink      = get_post_meta( get_the_ID(), 'social', true );
	$tlpSkill        = get_post_meta( $post->ID, 'skill', true );
	$sLink           = $socialLink ? $socialLink : [];
	$tlp_skill       = $tlpSkill ? unserialize( $tlpSkill ) : [];
	$exp             = null;

	$designation = strip_tags(
		get_the_term_list(
			get_the_ID(),
			rttlp_team()->taxonomies['designation'],
			null,
			', '
		)
	);

	$tag_line                 = get_post_meta( $post->ID, 'ttp_tag_line', true );
	$qualifications           = get_post_meta( $post->ID, 'ttp_qualifications', true );
	$professional_memberships = get_post_meta( $post->ID, 'ttp_professional_memberships', true );
	$area_of_expertise        = get_post_meta( $post->ID, 'ttp_area_of_expertise', true );
	?>
	<div class="rt-team-container tlp-single-container <?php echo esc_attr( $page_wrapper ); ?>" data-layout="carousel1">
		<div class="rt-row">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'tlp-single-detail' ); ?>>
				<div class="<?php echo esc_attr( $image_area ); ?> tlp-member-feature-img">
					<div data-title="Loading ..." class="rt-content-loader ttp-pre-loader">
						<?php
						Fns::print_html( Fns::memberDetailGallery( get_the_ID() ) );
						?>
						<div class="rt-loading-overlay"></div>
						<div class="rt-loading rt-ball-clip-rotate"><div></div></div>
					</div>
				</div>

				<div class="<?php echo esc_attr( $content_area ); ?> tlp-member-description-container">
					<?php

					if ( $experience_year && in_array( 'experience_year', $fields ) ) {
						$exp = "<span class='experience'>(" . esc_html( $experience_year ) . ")</span>";
					}

					if ( in_array( 'name', $fields ) ) {
						$html .= "<h2 class='tlp-member-title'>" . get_the_title() . '</h2>';
					}

					if ( $designation && in_array( 'designation', $fields ) ) {
						$html .= '<div class="tlp-position">' . $designation . $exp . '</div>';
					}

					if ( $tag_line && in_array( 'ttp_tag_line', $fields ) ) {
						$html .= "<div class='tlp-tag-line'>" . wp_kses( $tag_line, Fns::allowedHtml() ) . "</div>";
					}

					if ( $short_bio && in_array( 'short_bio', $fields ) ) {
						$html .= "<div class='tlp-short-bio'>" . wp_kses( apply_filters( 'the_content', get_post_meta( $post->ID, 'short_bio', true ) ), Fns::allowedHtml() ) . '</div>';
					}

					if ( in_array( 'content', $fields ) ) {
						$html .= '<div class="tlp-member-detail">' . apply_filters( 'the_content', get_the_content() ) . '</div>';
					}

					if ( $qualifications && in_array( 'ttp_qualifications', $fields ) ) {
						$html .= "<div class='rt-extra-curriculum'><strong>" . esc_html__( 'Qualifications : ', 'tlp-team' ) . "</strong>" . wp_kses( $qualifications, Fns::allowedHtml() ) . "</div>";
					}

					if ( $professional_memberships && in_array( 'ttp_professional_memberships', $fields ) ) {
						$html .= "<div class='rt-extra-curriculum'><strong>" . esc_html__( 'Professional Memberships : ', 'tlp-team' ) . "</strong>" . wp_kses( $professional_memberships, Fns::allowedHtml() ) . "</div>";
					}

					if ( $area_of_expertise && in_array( 'ttp_area_of_expertise', $fields ) ) {
						$html .= "<div class='rt-extra-curriculum'><strong>" . esc_html__( 'Area of Expertise : ', 'tlp-team' ) . "</strong>" . wp_kses( $area_of_expertise, Fns::allowedHtml() ) . "</div>";
					}

					$html .= "<div class='tlp-team'>";

					if ( $email && in_array( 'email', $fields ) ) {
						$htmlCInfo .= '<li class="tlp-email"><i class="far fa-envelope"></i> <a href="mailto:' . esc_attr( $email ) . '"><span>' . esc_html( $email ) . '</span></a> </li>';
					}

					if ( $telephone && in_array( 'telephone', $fields ) ) {
						$htmlCInfo .= '<li class="tlp-phone"><i class="fa fa-phone"></i> <a href="tel:' . esc_attr( $telephone ) . '">' . esc_html( $telephone ) . '</a></li>';
					}

					if ( $mobile && in_array( 'mobile', $fields ) ) {
						$htmlCInfo .= "<li class='tlp-mobile'><i class='fa fa-mobile'></i> <span>" . esc_html( $mobile ) . "</span></li>";
					}

					if ( $location && in_array( 'location', $fields ) ) {
						$htmlCInfo .= "<li class='tlp-location'><i class='fa fa-map-marker'></i> <span>" . esc_html( $location ) . "</span> </li>";
					}

					if ( $web_url && in_array( 'web_url', $fields ) ) {
						$htmlCInfo .= '<li class="tlp-web-url"><i class="fa fa-globe"></i> <a href="' . esc_url( $web_url ) . '">' . esc_html( $web_url ) . '</a> </li>';
					}

					$html .= $htmlCInfo ? "<div class='contact-info'><ul>{$htmlCInfo}</ul></div>" : null;

					if ( is_array( $tlp_skill ) && ! empty( $tlp_skill ) && in_array( 'skill', $fields ) ) {
						$html .= '<div class="tlp-team-skill">';
						foreach ( $tlp_skill as $id => $skill ) {
							$html .= "<div class='skill_name'>" . esc_html( $skill['id'] ) . "</div><div class='skill-prog tlp-tooltip' title='" . esc_attr( $skill['percent'] ) . "%'><div class='fill' data-progress-animation='" . esc_attr( $skill['percent'] ) . "%'></div></div>";
						}
						$html .= '</div>';
					}

					if ( ! empty( $sLink ) && is_array( $sLink ) && in_array( 'social', $fields ) ) {
						$html .= '<div class="social-icons">';

						foreach ( $sLink as $id => $itemLink ) {
							$lURL = ! empty( $itemLink['url'] ) ? $itemLink['url'] : null;
							$lID  = ! empty( $itemLink['id'] ) ? esc_html( $itemLink['id'] ) : null;

							if ( $lID == 'envelope-o' ) {
								$lURL = 'mailto:' . $lURL;
							}

							$icon_class = '';

							switch ( $lID ) {
								case 'facebook':
									$icon_class = 'fab fa-facebook-f';
									break;
								case 'twitter':
									$icon_class = 'fab fa-twitter';
									break;
								case 'linkedin':
									$icon_class = 'fab fa-linkedin';
									break;
								case 'youtube':
									$icon_class = 'fab fa-youtube';
									break;
								case 'instagram':
									$icon_class = 'fab fa-instagram';
									break;
								case 'pinterest':
									$icon_class = 'fab fa-pinterest-p';
									break;
								case 'soundcloud':
									$icon_class = 'fab fa-soundcloud';
									break;
								case 'bandcamp':
									$icon_class = 'fab fa-bandcamp';
									break;
								case 'vimeo':
									$icon_class = 'fab fa-vimeo-v';
									break;
								case 'envelope-o':
									$icon_class = 'far fa-envelope';
									break;
								case 'globe':
									$icon_class = 'fas fa-globe';
									break;
								case 'xing':
									$icon_class = 'fab fa-xing';
									break;
							}

							if ( $lID != 'google-plus' && $icon_class ) {
								$html .= '<a href="' . esc_url( $lURL ) .'" title="' . esc_attr( $lID ) .'" target="_blank"><i class="' . esc_attr( $icon_class ) .'"></i></a>';
							}
						}

						$html .= '</div>';
					}

					if ( in_array( 'author_post', $fields ) ) {
						$html .= Fns::memberDetailPosts( $post->ID );
					}

					$html .= '</div>';

					Fns::print_html( $html, true );
					?>
				</div>
			</article>
		</div>
		<?php
		if ( ( isset( $settings['detail_allow_comments'] ) && $settings['detail_allow_comments'] ) && ( comments_open() || get_comments_number() ) ) :
			?>
			<div class="rt-team-comments-wrapper">
				<?php comments_template(); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
endwhile;

get_footer();
