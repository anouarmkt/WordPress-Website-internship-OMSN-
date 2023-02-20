<?php

/**
 * Custom CSS.
 *
 * @package RT_Team
 * @var  int $scID
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$css      = null;
$selector = '.rt-team-container.rt-team-container-' . $scID;

// Variables.
$scMeta         = get_post_meta( $scID );
$primaryColor   = ( isset( $scMeta['primary_color'][0] ) ? sanitize_text_field( $scMeta['primary_color'][0] ) : null );
$button         = ! empty( $scMeta['ttp_button_style'][0] ) ? unserialize( $scMeta['ttp_button_style'][0] ) : null;
$popupBg        = ! empty( $scMeta['ttp_popup_bg_color'][0] ) ? sanitize_text_field( $scMeta['ttp_popup_bg_color'][0] ) : null;
$popupTextColor = ! empty( $scMeta['ttp_popup_text_color'][0] ) ? sanitize_text_field( $scMeta['ttp_popup_text_color'][0] ) : null;
$name           = ! empty( $scMeta['name'][0] ) ? unserialize( $scMeta['name'][0] ) : null;
$designation    = ! empty( $scMeta['designation'][0] ) ? unserialize( $scMeta['designation'][0] ) : null;
$short_bio      = ! empty( $scMeta['short_bio'][0] ) ? unserialize( $scMeta['short_bio'][0] ) : null;
$email          = ! empty( $scMeta['email'][0] ) ? unserialize( $scMeta['email'][0] ) : null;
$web_url        = ! empty( $scMeta['web_url'][0] ) ? unserialize( $scMeta['web_url'][0] ) : null;
$telephone      = ! empty( $scMeta['telephone'][0] ) ? unserialize( $scMeta['telephone'][0] ) : null;
$mobile         = ! empty( $scMeta['mobile'][0] ) ? unserialize( $scMeta['mobile'][0] ) : null;
$fax            = ! empty( $scMeta['fax'][0] ) ? unserialize( $scMeta['fax'][0] ) : null;
$location       = ! empty( $scMeta['location'][0] ) ? unserialize( $scMeta['location'][0] ) : null;
$skill          = ! empty( $scMeta['skill'][0] ) ? unserialize( $scMeta['skill'][0] ) : null;
$social_icon    = ! empty( $scMeta['social'][0] ) ? unserialize( $scMeta['social'][0] ) : null;
$social_icon_bg = ! empty( $scMeta['social_icon_bg'][0] ) ? sanitize_text_field( $scMeta['social_icon_bg'][0] ) : null;
$mObg           = ! empty( $scMeta['overlay_rgba_bg'][0] ) ? unserialize( $scMeta['overlay_rgba_bg'][0] ) : null;
$itemP          = ! empty( $scMeta['overlay_padding'][0] ) ? intval( $scMeta['overlay_padding'][0] ) : null;
$gutter         = ! empty( $scMeta['ttp_gutter'][0] ) ? absint( $scMeta['ttp_gutter'][0] ) : null;


if ( $primaryColor ) {
	$css .= "$selector .single-team-area .overlay a.detail-popup, $selector .contact-info ul li i{";
	$css .= 'color:' . $primaryColor . ';';
	$css .= '}';

	$css .= "$selector .single-team-area .skill-prog .fill,.tlp-team $selector .tlp-content, .tlp-popup-wrap-{$scID} .tlp-tooltip + .tooltip > .tooltip-inner, .tlp-modal-{$scID} .tlp-tooltip + .tooltip > .tooltip-inner, .rt-modal-{$scID} .tlp-tooltip + .tooltip > .tooltip-inner,$selector .layout1 .tlp-content,$selector .layout11 .single-team-area .tlp-title,$selector .carousel7 .single-team-area .team-name,$selector .layout14 .rt-grid-item .tlp-overlay, $selector .carousel8 .rt-grid-item .tlp-overlay,$selector .isotope6 .single-team-area h3 .team-name,$selector .carousel8 .rt-grid-item .tlp-overlay .social-icons:before,$selector .layout14 .rt-grid-item .tlp-overlay .social-icons:before,$selector .skill-prog .fill,$selector .special-selected-top-wrap .ttp-label,#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header,$selector .layout6 .tlp-info-block, $selector .isotope-free .tlp-content, $selector .carousel9 .single-team-area .tlp-overlay{";
	$css .= 'background:' . $primaryColor . ' !important;';
	$css .= '}';

	$css .= "$selector .layout15 .single-team-area:before,$selector .isotope10 .single-team-area:before,$selector .carousel11 .single-team-area:before{";
	$css .= 'background:' . Fns::TLPhex2rgba( $primaryColor, 0.8 );
	$css .= '}';

	$css .= "#rt-smart-modal-container.loading.rt-modal-{$scID} .rt-spinner, $selector .tlp-team-skill .tooltip.top .tooltip-arrow, .tlp-popup-wrap-{$scID} .tlp-tooltip + .tooltip > .tooltip-arrow, .tlp-modal-{$scID} .tlp-tooltip + .tooltip > .tooltip-arrow, .rt-modal-{$scID} .tlp-tooltip + .tooltip > .tooltip-arrow {";
	$css .= 'border-top-color:' . $primaryColor . ';';
	$css .= '}';

	$css .= "$selector .layout6 .tlp-right-arrow:after{";
	$css .= 'border-color: transparent ' . $primaryColor . ';';
	$css .= '}';

	$css .= "$selector .layout6 .tlp-left-arrow:after{";
	$css .= 'border-color:' . $primaryColor . ' transparent transparent;';
	$css .= '}';

	$css .= "$selector .layout12 .single-team-area h3 .team-name,$selector .isotope6 .single-team-area h3 .team-name,$selector  .layout12 .single-team-area h3 .team-name,$selector .isotope6 .single-team-area h3 .team-name {";
	$css .= 'background:' . $primaryColor . ';';
	$css .= '}';

	$css .= ".tlp-popup-wrap-{$scID} .skill-prog .fill, .tlp-modal-{$scID} .skill-prog .fill{";
	$css .= 'background-color:' . $primaryColor . ';';
	$css .= '}';

	$css .= "$selector .special-selected-top-wrap .img:after{";
	$css .= 'background:' . Fns::TLPhex2rgba( $primaryColor, 0.2 );
	$css .= '}';

	$css .= "#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header a.rt-smart-nav-item{";
	$css .= '-webkit-text-stroke: 1px ' . Fns::TLPhex2rgba( $primaryColor ) . ';';
	$css .= '}';

	$css .= "#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header a.rt-smart-modal-close{";
	$css .= '-webkit-text-stroke: 6px ' . Fns::TLPhex2rgba( $primaryColor ) . ';';
	$css .= '}';
}

// Buttons.
if ( ! empty( $button ) ) {
	if ( ! empty( $button['bg'] ) ) {
		$css .= "$selector .rt-pagination-wrap .rt-loadmore-btn,$selector .rt-pagination-wrap .pagination > li > a, $selector .rt-pagination-wrap .pagination > li > span,$selector .ttp-isotope-buttons.button-group button,$selector .rt-pagination-wrap .rt-loadmore-btn,$selector .rt-carousel-holder .swiper-arrow,$selector .rt-carousel-holder.swiper .swiper-pagination-bullet,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,$selector .rt-pagination-wrap .paginationjs .paginationjs-pages li>a{";
		$css .= "background-color: {$button['bg']};";
		$css .= '}';

		$css .= "$selector .rt-carousel-holder .swiper-arrow{";
		$css .= "border-color: {$button['bg']};";
		$css .= '}';

		$css .= "$selector .rt-pagination-wrap .rt-infinite-action .rt-infinite-loading{";
		$css .= 'color: ' . Fns::TLPhex2rgba( $button['bg'], 0.5 );
		$css .= '}';
	}

	if ( ! empty( $button['hover_bg'] ) ) {
		$css .= "$selector .rt-pagination-wrap .rt-loadmore-btn:hover,$selector .rt-pagination-wrap .pagination > li > a:hover, $selector .rt-pagination-wrap .pagination > li > span:hover,$selector .rt-carousel-holder .swiper-arrow:hover,$selector .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,$selector .rt-carousel-holder.swiper .swiper-pagination-bullet:hover,$selector .ttp-isotope-buttons.button-group button:hover,$selector .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages li>a:hover{";
		$css .= "background-color: {$button['hover_bg']};";
		$css .= '}';

		$css .= "$selector .rt-carousel-holder .swiper-arrow:hover{";
		$css .= "border-color: {$button['hover_bg']};";
		$css .= '}';
	}

	if ( ! empty( $button['active_bg'] ) ) {
		$css .= "$selector .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active > a,$selector .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item.selected,$selector .ttp-isotope-buttons.button-group .selected,$selector .rt-carousel-holder .swiper-pagination-bullet.swiper-pagination-bullet-active,$selector .rt-pagination-wrap .pagination > .active > span{";
		$css .= "background-color: {$button['active_bg']};";
		$css .= '}';
	}

	if ( ! empty( $button['text'] ) ) {
		$css .= "$selector .rt-pagination-wrap .rt-loadmore-btn,$selector .rt-pagination-wrap .pagination > li > a, $selector .rt-pagination-wrap .pagination > li > span,$selector .ttp-isotope-buttons.button-group button,$selector .rt-carousel-holder .swiper-arrow i,$selector .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,$selector .rt-pagination-wrap .paginationjs .paginationjs-pages li>a{";
		$css .= "color: {$button['text']};";
		$css .= '}';
	}

	if ( ! empty( $button['hover_text'] ) ) {
		$css .= "$selector .rt-pagination-wrap .rt-loadmore-btn:hover,$selector .rt-pagination-wrap .pagination > li > a:hover, $selector .rt-pagination-wrap .pagination > li > span:hover,$selector .ttp-isotope-buttons.button-group button:hover,$selector .rt-carousel-holder .swiper-arrow:hover i,$selector .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,$selector .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages li>a:hover{";
		$css .= "color: {$button['hover_text']};";
		$css .= '}';
	}

	if ( ! empty( $button['border'] ) ) {
		$css .= "$selector .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action,$selector .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap{";
		$css .= "border-color: {$button['border']};";
		$css .= '}';
	}
}

// Gutter.
if ( $gutter ) {
	$css    .= "$selector [class*='rt-col-']:not(.paddingl0,.paddingr0 ) {";
	$css    .= "padding-left : {$gutter}px;";
	$css    .= "padding-right : {$gutter}px;";
	$bGutter = $gutter * 2;
	$css    .= "margin-bottom : {$bGutter}px;";
	$css    .= '}';

	$css .= "$selector .rt-row.special01 .rt-special-wrapper .rt-col-sm-4 [class*='rt-col-'] {";
	$css .= 'padding-left : 0;';
	$css .= 'padding-right : 0;';
	$css .= '}';

	$css .= "$selector .rt-row.special01 .rt-special-wrapper #special-selected-wrapper {";
	$css .= 'margin : 0;';
	$css .= '}';

	$css .= "$selector .rt-row.special01 .rt-special-wrapper #special-selected-wrapper .special-selected-top-wrap > div {";
	$css .= 'margin-bottom : 0;';
	$css .= '}';

	$css .= "$selector .rt-row.special01 .rt-special-wrapper #special-selected-wrapper .rt-col-sm-12 {";
	$css .= 'margin-bottom : 0;';
	$css .= '}';

	$css .= "$selector .rt-row{";
	$css .= "margin-left : -{$gutter}px;";
	$css .= "margin-right : -{$gutter}px;";
	$css .= '}';

	$css .= "$selector.rt-container-fluid,$selector.rt-container,$selector.rt-team-container, $selector .rt-content-loader:not(.carousel9, .carousel10, .carousel11 ) .owl-nav{";
	$css .= "padding-left : {$gutter}px;";
	$css .= "padding-right : {$gutter}px;";
	$css .= '}';
}

// Name.
if ( ! empty( $name ) ) {
	$namecCss  = null;
	$namecCss .= ! empty( $name['color'] ) ? 'color:' . $name['color'] . ';' : null;
	$namecCss .= ! empty( $name['align'] ) ? 'text-align:' . $name['align'] . ';' : null;
	$namecCss .= ! empty( $name['size'] ) ? 'font-size:' . $name['size'] . 'px;' : null;
	$namecCss .= ! empty( $name['weight'] ) ? 'font-weight:' . $name['weight'] . ';' : null;

	if ( $namecCss ) {
		$css .= "$selector h3,
                $selector .isotope1 .team-member h3,
                $selector h3 a,$selector .overlay h3 a,
                $selector .layout8 .tlp-overlay h3 a,
                $selector .layout9 .single-team-area h3 a,
                $selector .layout6 .tlp-info-block h3 a,
                $selector .carousel11 .single-team-area .ttp-member-title h3 a,
                $selector .layout10 .tlp-overlay .tlp-title h3 a,
                $selector .layout11 .single-team-area .ttp-member-title h3 a,
                $selector .layout12 .single-team-area h3 a,
                $selector .layout15 .single-team-area .ttp-member-title h3 a,
                $selector .isotope5 .tlp-overlay h3 a,
                $selector .isotope6 .single-team-area h3 a,
                $selector .isotope10 .single-team-area .ttp-member-title h3 a,
                $selector .single-team-area .tlp-content h3 a{ {$namecCss} }";
	}

	if ( ! empty( $name['hover_color'] ) ) {
		$css .= "$selector h3:hover,
                $selector h3 a:hover,
                $selector .layout8 .tlp-overlay h3 a:hover,
                $selector .layout9 .single-team-area h3 a:hover,
                $selector .layout6 .tlp-info-block h3 a:hover,
                $selector .carousel11 .single-team-area .ttp-member-title h3 a:hover,
                $selector .layout12 .single-team-area h3 a:hover,
                $selector .overlay h3 a:hover,
                $selector .layout10 .tlp-overlay .tlp-title h3 a:hover,
                $selector .layout11 .single-team-area .ttp-member-title h3 a:hover,
                $selector .layout14 .rt-grid-item .tlp-overlay h3 a:hover,
                $selector .layout15 .single-team-area .ttp-member-title h3 a:hover,
                $selector .isotope5 .tlp-overlay h3 a:hover,
                $selector .isotope6 .single-team-area h3 a:hover,
                $selector .isotope10 .single-team-area .ttp-member-title h3 a:hover,
                $selector .single-team-area .tlp-content h3 a:hover{ color: {$name['hover_color']}; }";
	}
}

// Designation.
if ( ! empty( $designation ) ) {
	$cCss  = null;
	$cCss .= ! empty( $designation['color'] ) ? 'color:' . $designation['color'] . ';' : null;
	$cCss .= ! empty( $designation['align'] ) ? 'text-align:' . $designation['align'] . ';' : null;
	$cCss .= ! empty( $designation['size'] ) ? 'font-size:' . $designation['size'] . 'px;' : null;
	$cCss .= ! empty( $designation['weight'] ) ? 'font-weight:' . $designation['weight'] . ';' : null;

	$css .= "$selector .tlp-position,$selector .isotope10 .single-team-area .ttp-member-title .tlp-position a,$selector .isotope1 .team-member .overlay .tlp-position,$selector .layout11 .single-team-area .ttp-member-title .tlp-position a,$selector .carousel11 .single-team-area .ttp-member-title .tlp-position a,$selector .layout15 .single-team-area .ttp-member-title .tlp-position a,$selector .tlp-position a,$selector .overlay .tlp-position,$selector .tlp-layout-isotope .overlay .tlp-position{ {$cCss} }";

	if ( ! empty( $designation['hover_color'] ) ) {
		$css .= "$selector .tlp-position:hover,$selector .isotope10 .single-team-area .ttp-member-title .tlp-position a:hover,$selector .layout11 .single-team-area .ttp-member-title .tlp-position a:hover,$selector .carousel11 .single-team-area .ttp-member-title .tlp-position a:hover,$selector .layout15 .single-team-area .ttp-member-title .tlp-position a:hover,$selector .tlp-position a:hover,$selector .overlay .tlp-position:hover,$selector .tlp-layout-isotope .overlay .tlp-position:hover{ color: {$designation['hover_color']}; }";
	}
}

// Short biography.
if ( ! empty( $short_bio ) ) {
	// $cCss  = null;
	$short_bio_cCss  = ! empty( $short_bio['color'] ) ? 'color:' . $short_bio['color'] . ';' : null;
	$short_bio_cCss .= ! empty( $short_bio['align'] ) ? 'text-align:' . $short_bio['align'] . ';' : null;
	$short_bio_cCss .= ! empty( $short_bio['size'] ) ? 'font-size:' . $short_bio['size'] . 'px;' : null;
	$short_bio_cCss .= ! empty( $short_bio['weight'] ) ? 'font-weight:' . $short_bio['weight'] . ';' : null;

	if ( $short_bio_cCss ) {
		$css .= "$selector .short-bio p,$selector .short-bio p a,$selector .overlay .short-bio p, $selector .overlay .short-bio p a{{$short_bio_cCss}}";
	}
}

// Email.
if ( ! empty( $email ) ) {
	// $cCss  = null;
	$emailcCss  = ! empty( $email['color'] ) ? 'color:' . $email['color'] . ';' : null;
	$emailcCss .= ! empty( $email['size'] ) ? 'font-size:' . $email['size'] . 'px;' : null;
	$emailcCss .= ! empty( $email['weight'] ) ? 'font-weight:' . $email['weight'] . ';' : null;

	if ( $emailcCss ) {
		$css .= "$selector .tlp-email, $selector .layout6 .contact-info i, $selector .tlp-email a{ {$emailcCss} }";
	}
}

// Web URL.
if ( ! empty( $web_url ) ) {
	// $cCss  = null;
	$web_urlcCss  = ! empty( $web_url['color'] ) ? 'color:' . $web_url['color'] . ';' : null;
	$web_urlcCss .= ! empty( $web_url['size'] ) ? 'font-size:' . $web_url['size'] . 'px;' : null;
	$web_urlcCss .= ! empty( $web_url['weight'] ) ? 'font-weight:' . $web_url['weight'] . ';' : null;

	if ( $web_urlcCss ) {
		$css .= "$selector .tlp-web-url a,$selector .tlp-url{{$web_urlcCss}}";
	}
}

// Telephone.
if ( ! empty( $telephone ) ) {
	// $cCss  = null;
	$telephonecss  = ! empty( $telephone['color'] ) ? 'color:' . $telephone['color'] . ';' : null;
	$telephonecss .= ! empty( $telephone['size'] ) ? 'font-size:' . $telephone['size'] . 'px;' : null;
	$telephonecss .= ! empty( $telephone['weight'] ) ? 'font-weight:' . $telephone['weight'] . ';' : null;

	if ( $telephonecss ) {
		$css .= "$selector .tlp-phone a,$selector .tlp-phone{{$telephonecss}}";
	}
}
// Mobile.
if ( ! empty( $mobile ) ) {
	// $cCss  = null;
	$mobilecCss  = ! empty( $mobile['color'] ) ? 'color:' . $mobile['color'] . ';' : null;
	$mobilecCss .= ! empty( $mobile['size'] ) ? 'font-size:' . $mobile['size'] . 'px;' : null;
	$mobilecCss .= ! empty( $mobile['weight'] ) ? 'font-weight:' . $mobile['weight'] . ';' : null;

	if ( $mobilecCss ) {
		$css .= "$selector .tlp-mobile{{$mobilecCss}}";
	}
}
// Fax.
if ( ! empty( $fax ) ) {
	// $cCss  = null;
	$faxcCss  = ! empty( $fax['color'] ) ? 'color:' . $fax['color'] . ';' : null;
	$faxcCss .= ! empty( $fax['size'] ) ? 'font-size:' . $fax['size'] . 'px;' : null;
	$faxcCss .= ! empty( $fax['weight'] ) ? 'font-weight:' . $fax['weight'] . ';' : null;

	if ( $faxcCss ) {
		$css .= "$selector .tlp-fax{{$faxcCss}}";
	}
}

// Location.
if ( ! empty( $location ) ) {
	// $cCss  = null;
	$locationcCss  = ! empty( $location['color'] ) ? 'color:' . $location['color'] . ';' : null;
	$locationcCss .= ! empty( $location['size'] ) ? 'font-size:' . $location['size'] . 'px;' : null;
	$locationcCss .= ! empty( $location['weight'] ) ? 'font-weight:' . $location['weight'] . ';' : null;

	if ( $locationcCss ) {
		$css .= "$selector .tlp-location{{$locationcCss}}";
	}
}

// Skill.
if ( ! empty( $skill ) ) {
	// $cCss  = null;
	$colorSkill = ! empty( $skill['color'] ) ? $skill['color'] : null;
	$skillcCss  = ! empty( $colorSkill ) ? 'color:' . $colorSkill . ';' : null;
	$skillcCss .= ! empty( $skill['align'] ) ? 'text-align:' . $skill['align'] . ';' : null;
	$skillcCss .= ! empty( $skill['size'] ) ? 'font-size:' . $skill['size'] . 'px;' : null;
	$skillcCss .= ! empty( $skill['weight'] ) ? 'font-weight:' . $skill['weight'] . ';' : null;

	if ( $skillcCss ) {
		$css .= "$selector .skill_name{{$skillcCss}}";
	}

	$css .= ".rt-modal-{$scID} .skill-prog .fill, .tlp-modal-{$scID} .skill-prog .fill, .tlp-popup-wrap-{$scID} .skill-prog .fill{background: {$colorSkill}}";

}

// Social Icon.
if ( ! empty( $social_icon ) ) {
	// $cCss  = null;
	$social_iconcCss  = ! empty( $social_icon['color'] ) ? 'color:' . $social_icon['color'] . ' !important;' : null;
	$social_iconcCss .= ! empty( $social_icon['size'] ) ? 'font-size:' . $social_icon['size'] . 'px;' : null;
	$social_iconcCss .= ! empty( $social_icon['weight'] ) ? 'font-weight:' . $social_icon['weight'] . ';' : null;

	if ( $social_iconcCss ) {
		$css .= "$selector .overlay .social-icons a,$selector .isotope8 .single-team-area .tlp-overlay .social-icons a,$selector .tlp-social,$selector .social-icons a{ {$social_iconcCss} }";
	}

	if ( ! empty( $social_icon['align'] ) ) {
		$css .= "$selector .social-icons,$selector .tlp-social, $selector .overlay .social-icons { text-align: {$social_icon['align']}; }";
	}
}

// Social Icon bg.
if ( $social_icon_bg ) {
	$css .= "$selector .social-icons a{background:{$social_icon_bg};}";
}

if ( ! empty( $popupTextColor ) ) {
	$css .= "#tlp-modal.tlp-modal-$scID .md-content{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .md-content .tlp-md-content-holder > .md-header h4, #tlp-modal.tlp-modal-$scID .md-content .tlp-md-content-holder > .md-header h3 {color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .md-content button.md-close{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .social-icons a{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .rt-team-container .contact-info ul li a{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .rt-team-container .contact-info ul li{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .rt-team-container .contact-info i{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .md-content .author-latest-post li a{color:{$popupTextColor};}";
	$css .= "#tlp-modal.tlp-modal-$scID .rt-team-container h3{color:{$popupTextColor};}";
}

// Overlay.
if ( ! empty( $mObg ) ) {
	if ( ! empty( $mObg['color'] ) && ! empty( $mObg['opacity'] ) ) {
		$css .= "$selector .single-team-area .overlay,$selector .single-team-area:hover .tlp-overlay,$selector .single-team-area:hover .tlp-overlay,$selector .layout7 figcaption:hover,$selector .isotope8 .tlp-overlay,$selector .layout13 .tlp-overlay,$selector .layout8 .tlp-overlay .tlp-title,$selector .isotope1 .rt-grid-item:hover .overlay,$selector .isotope4 figcaption:hover,$selector .layout11 .single-team-area .tlp-title,$selector .isotope5 .tlp-overlay .tlp-title {";
		$css .= 'background:' . Fns::TLPhex2rgba(
			$mObg['color'],
			( $mObg['opacity'] ? $mObg['opacity'] : .8 )
		) . ';';
		$css .= '}';
	}
}

// Overlay item padding.
if ( $itemP ) {
	$css .= "$selector .single-team-area .overlay .overlay-element,$selector .single-team-area:hover h3,$selector .isotope3 .single-team-area:hover h3,$selector .layout7 figcaption:hover,$selector .isotope4 figcaption:hover, $selector .layout9 .tlp-overlay{";
	$css .= 'padding-top:' . $itemP . '%; ';
	$css .= '}';
}

if ( $css ) {
	echo wp_strip_all_tags( $css );
}
