<?php
/**
 * Template: Carousel Layout 1.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$html = null;

$html .= '<div class="layout3 ' . esc_attr( $grid ) . ' ' . esc_attr( $class ) . '" data-id="' . absint( $mID ) . '">';
$html .= '<div class="single-team-area">';

if ( $imgHtml ) {
	if ( $link ) {
		$html .= '<figure><a class="' . esc_attr( $anchorClass ) . '" data-id="' . absint( $mID ) . '" target="' . esc_attr( $target ) . '" href="' . esc_url( $pLink ) . '">' . Fns::htmlKses( $imgHtml, 'image' ) . '</a></figure>';
	} else {
		$html .= '<figure>' . Fns::htmlKses( $imgHtml, 'image' ) . '</figure>';
	}
}

$html .= '<div class="tlp-overlay1">';

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
$html .= '</div>';

Fns::print_html( $html );
