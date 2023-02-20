<?php
/**
 * Template: Isotope Layout 1.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$html         = null;
$isoFilter    = isset( $isoFilter ) ? $isoFilter : '';
$wrapperClass = $grid . ' ' . $class . ' ' . $isoFilter;

$html .= '<div class="team-member ' . esc_attr( $wrapperClass ) . '" data-id="' . absint( $mID ) . '">';
$html .= '<figure>';

if ( $imgHtml ) {
	if ( $link ) {
		$html .= '<a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" href="' . esc_url( $pLink ) . '">' . Fns::htmlKses( $imgHtml, 'image' ) . '</a>';
	} else {
		$html .= Fns::htmlKses( $imgHtml, 'image' );
	}
}

$html .= '<div class="overlay">';
$html .= '<div class="overlay-element">';

if ( in_array( 'name', $items, true ) && $title ) {
	if ( $link ) {
		$html .= '<h3><span class="team-name"><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $title ) . '" href="' . esc_url( $pLink ) . '">' . esc_html( $title ) . '</a></span></h3>';
	} else {
		$html .= '<h3><span class="team-name">' . esc_html( $title ) . '</span></h3>';
	}
}

if ( in_array( 'designation', $items, true ) && $designation ) {
	if ( $link ) {
		$html .= '<div class="tlp-position"><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $title ) . '" href="' . esc_url( $pLink ) . '">' . esc_html( $designation ) . '</a></div>';
	} else {
		$html .= '<div class="tlp-position">' . esc_html( $designation ) . '</div>';
	}
}

$html .= Fns::get_formatted_short_bio( $short_bio, $items );
$html .= Fns::get_formatted_social_link( $sLink, $items );
$html .= '</div>';
$html .= '</div>';
$html .= '</figure>';
$html .= '</div>';

Fns::print_html( $html );
