<?php
/**
 * Template: Special Layout 1.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$html = null;

if ( 1 === $i ) {
	$class = $class . ' selected';
}

$html .= '<div class="' . esc_attr( $grid ) . ' ' . esc_attr( $class ) . '" data-id="' . absint( $mID ) . '">';
$html .= '<div class="single-team-item image-wrapper" data-id="' . absint( $mID ) . '">' . Fns::htmlKses( $imgHtml, 'image' ) . '</div>';
$html .= '</div>';

Fns::print_html( $html );
