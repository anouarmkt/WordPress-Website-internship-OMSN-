<?php
/**
 * Template: Grid Layout 4.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$html    = null;
$content = null;

$html .= '<div class="' . esc_attr( $grid ) . ' ' . esc_attr( $class ) . '" data-id="' . absint( $mID ) . '">';

if ( $imgHtml ) {
	$html .= '<div class="single-team-area">';
	$html .= '<div class="single-team">';

	if ( $link ) {
		$html .= '<figure><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" href="' . esc_url( $pLink ) . '">' . Fns::htmlKses( $imgHtml, 'image' ) . '</a></figure>';
	} else {
		$html .= '<figure>' . Fns::htmlKses( $imgHtml, 'image' ) . '</figure>';
	}

	$html .= '<div class="overlay">';
	$html .= '<div class="overlay-element">';

	if ( in_array( 'name', $items, true ) && $title ) {
		if ( $link ) {
			$content .= '<h3><span class="team-name"><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $title ) . '" href="' . esc_url( $pLink ) . '">' . esc_html( $title ) . '</a></span></h3>';
		} else {
			$content .= '<h3><span class="team-name">' . esc_html( $title ) . '</span></h3>';
		}
	}

	if ( in_array( 'designation', $items, true ) && $designation ) {
		if ( $link ) {
			$content .= '<div class="tlp-position"><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $title ) . '" href="' . esc_url( $pLink ) . '">' . esc_html( $designation ) . '</a></div>';
		} else {
			$content .= '<div class="tlp-position">' . esc_html( $designation ) . '</div>';
		}
	}

	$content .= Fns::get_formatted_short_bio( $short_bio, $items );
	$html    .= $content ? '<div class="tlp-content2">' . $content . '</div>' : null;

	$html .= Fns::get_formatted_social_link( $sLink, $items );
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>'; // END single-team.
	$html .= '</div>'; // End single-team-area.
}

$html .= '</div>'; // END grid.

Fns::print_html( $html );
