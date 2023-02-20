<?php
/**
 * Helpers class.
 *
 * @package RT_Team
 */

namespace RT\Team\Helpers;

use RT\Team\Models\Fields;
use RT\Team\Models\ReSizer;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Helpers class.
 */
class Fns {

	/**
	 * Classes instatiation.
	 *
	 * @param array $classes Classes to init.
	 *
	 * @return void
	 */
	public static function instances( array $classes ) {
		if ( empty( $classes ) ) {
			return;
		}

		foreach ( $classes as $class ) {
			$class::get_instance();
		}
	}


	/**
	 * Nonce verification.
	 *
	 * @return boolean
	 */
	public static function verifyNonce() {
		$nonce     = isset( $_REQUEST[ self::nonceID() ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ self::nonceID() ] ) ) : null;
		$nonceText = self::nonceText();
		if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Nonce text.
	 *
	 * @return string
	 */
	public static function nonceText() {
		return 'tlp_team_nonce';
	}

	/**
	 * Nonce ID.
	 *
	 * @return string
	 */
	public static function nonceID() {
		return 'tlp_nonce';
	}

	/**
	 * Render.
	 *
	 * @param string  $view_name View name.
	 * @param array   $args View args.
	 * @param boolean $return View return.
	 *
	 * @return string|void
	 */
	public static function render( $view_name, $args = [], $return = false ) {
		$path = str_replace( '.', '/', $view_name );
		if ( $args ) {
			extract( $args );
		}

		$template = [
			"tlp-team/{$path}.php",
		];

		$pro_path = rttlp_team()->pro_templates_path() . $view_name . '.php';

		if ( locate_template( $template ) ) {
			$template_file = locate_template( $template );
		} elseif ( function_exists( 'rttmp' ) && file_exists( $pro_path ) ) {
			$template_file = $pro_path;
		} else {
			$template_file = rttlp_team()->templates_path() . $view_name . '.php';
		}

		if ( ! file_exists( $template_file ) ) {
			return;
		}

		if ( $return ) {
			ob_start();
			include $template_file;

			return ob_get_clean();
		} else {
			include $template_file;
		}
	}

	/**
	 * Render view.
	 *
	 * @param string  $view_name View name.
	 * @param array   $args View args.
	 * @param boolean $return View return.
	 *
	 * @return string|void
	 */
	public static function render_view( $view_name, $args = [], $return = false ) {
		$path           = str_replace( '.', '/', $view_name );
		$resources_path = rttlp_team()->plugin_path() . '/resources/' . $path . '.php';

		if ( ! file_exists( $resources_path ) ) {
			return new \WP_Error(
				'brock',
				sprintf(
					__( '%s file not found', 'tlp-team' ),
					esc_html( $resources_path )
				)
			);
		}

		if ( $args ) {
			extract( $args );
		}

		if ( $return ) {
			ob_start();
			include $resources_path;

			return ob_get_clean();
		}

		include $resources_path;
	}

	/**
	 * Field Generator.
	 *
	 * @param array $fields Fields.
	 *
	 * @return void|string
	 */
	public static function rtFieldGenerator( $fields = [] ) {
		$html = null;
		if ( is_array( $fields ) && ! empty( $fields ) ) {
			$tlpField = new Fields();
			foreach ( $fields as $fieldKey => $field ) {
				$html .= $tlpField->Field( $fieldKey, $field );
			}
		}

		return $html;
	}

	/**
	 * Checks if metadata exists.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $meta_key Meta key.
	 * @param string $type Post type.
	 *
	 * @return Boolean
	 */
	public static function meta_exist( $post_id, $meta_key, $type = 'post' ) {
		if ( ! $post_id ) {
			return false;
		}

		return metadata_exists( $type, $post_id, $meta_key );
	}

	public static function getScTeamMetaFields() {
		return array_merge(
			Options::get_sc_layout_settings_meta_fields(),
			Options::get_sc_query_filter_meta_fields(),
			Options::get_sc_field_selection_meta(),
			Options::get_sc_field_style_meta()
		);
	}

	public static function tlpAllMemberInfoFields() {
		$fields  = [];
		$fieldsA = Options::teamMemberInfoField();
		foreach ( $fieldsA as $field ) {
			if ( is_array( $field ) ) {
				$fields[] = $field['name'];
			}
		}

		return $fields;
	}


	public static function getMemberList() {
		$members = [];
		$memberQ = get_posts(
			[
				'post_type'      => rttlp_team()->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			]
		);
		if ( ! empty( $memberQ ) && is_array( $memberQ ) ) {
			foreach ( $memberQ as $member ) {
				$members[ $member->ID ] = $member->post_title;
			}
		}

		return $members;
	}

	public static function getTTPShortcodeList() {
		$scList = null;
		$scQ    = get_posts(
			[
				'post_type'      => rttlp_team()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			]
		);
		if ( ! empty( $scQ ) ) {
			foreach ( $scQ as $sc ) {
				$scList[ $sc->ID ] = $sc->post_title;
			}
		}

		return $scList;
	}

	/**
	 * @param $post_id
	 * @param $mates
	 * @param $request
	 * Update meta fields
	 */
	public static function updateMetaFields( $post_id, $mates, $request ) {
		if ( is_array( $mates ) && ! empty( $mates ) ) {
			foreach ( $mates as $metaKey => $field ) {
				$rValue = ! empty( $request[ $metaKey ] ) ? $request[ $metaKey ] : null;
				$value  = self::sanitize( $field, $rValue );
				if ( empty( $field['multiple'] ) ) {
					update_post_meta( $post_id, $metaKey, $value );
				} else {
					delete_post_meta( $post_id, $metaKey );
					if ( is_array( $value ) && ! empty( $value ) ) {
						foreach ( $value as $item ) {
							add_post_meta( $post_id, $metaKey, $item );
						}
					}
				}
			}
		}
	}

	/**
	 * Sanitize field value
	 *
	 * @param array $field
	 * @param null  $value
	 *
	 * @return array|null
	 * @internal param $value
	 */
	public static function sanitize( $field = [], $value = null ) {
		$newValue = null;
		if ( is_array( $field ) ) {
			$type = ( ! empty( $field['type'] ) ? esc_attr( $field['type'] ) : 'text' );
			if ( empty( $field['multiple'] ) ) {
				if ( $type == 'text' || $type == 'number' || $type == 'select' || $type == 'checkbox' || $type == 'radio' ) {
					$newValue = sanitize_text_field( $value );
				} elseif ( $type == 'email' ) {
					$newValue = sanitize_email( $value );
				} elseif ( $type == 'url' ) {
					$newValue = esc_url( $value );
				} elseif ( $type == 'slug' ) {
					$newValue = sanitize_title_with_dashes( $value );
				} elseif ( $type == 'textarea' ) {
					$newValue = wp_kses_post( $value );
				} elseif ( $type == 'custom_css' ) {
					$newValue = esc_textarea( $value );
				} elseif ( $type == 'colorpicker' ) {
					$newValue = self::sanitize_hex_color( $value );
				} elseif ( $type == 'image_size' ) {
					$newValue = [];
					foreach ( $value as $k => $v ) {
						if ( $k == 'width' || $k == 'height' ) {
							$newValue[ $k ] = absint( $v );
						} else {
							$newValue[ $k ] = esc_attr( $v );
						}
					}
				} elseif ( $type == 'style' || $type == 'multiple_options' ) {
					$newValue = [];
					foreach ( $value as $k => $v ) {
						$nV = null;
						if ( $k == 'color' ) {
							$nV = self::sanitize_hex_color( $v );
						} else {
							$nV = self::sanitize( [ 'type' => 'text' ], $v );
						}
						if ( $nV ) {
							$newValue[ $k ] = $nV;
						}
					}
					if ( empty( $newValue ) ) {
						$newValue = null;
					}
				} else {
					$newValue = sanitize_text_field( $value );
				}
			} else {
				$newValue = [];
				if ( ! empty( $value ) ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $key => $val ) {
							if ( $type == 'style' && $key == 0 ) {
								if ( function_exists( 'sanitize_hex_color' ) ) {
									$newValue = sanitize_hex_color( $val );
								} else {
									$newValue[] = self::sanitize_hex_color( $val );
								}
							} else {
								$newValue[] = sanitize_text_field( $val );
							}
						}
					} else {
						$newValue[] = sanitize_text_field( $value );
					}
				}
			}
		}

		return $newValue;
	}

	public static function sanitize_hex_color( $color ) {
		if ( function_exists( 'sanitize_hex_color' ) ) {
			return sanitize_hex_color( $color );
		} else {
			if ( '' === $color ) {
				return '';
			}

			// 3 or 6 hex digits, or the empty string.
			if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
				return $color;
			}
		}
	}

	public static function custom_pagination( $pages = '', $range = 4, $page_num = null ) {
		$html      = null;
		$showitems = ( $range * 2 ) + 1;
		global $paged;
		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}
		if ( empty( $paged ) ) {
			$paged = 1;
		}

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {

			$html .= '<div class="tlp-pagination"><ul class="pagination">';

			if ( $page_num ) {
				$html .= '<li class="disabled hidden-xs"><span><span aria-hidden="true">Page ' . $paged . ' of ' . $pages . '</span></span></li>';
			}

			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				$html .= "<li><a href='" . get_pagenum_link( 1 ) . "' aria-label='First'>&laquo;<span class='hidden-xs'> First</span></a></li>";
			}

			if ( $paged > 1 && $showitems < $pages ) {
				$html .= "<li><a href='" . get_pagenum_link( $paged - 1 ) . "' aria-label='Previous'>&lsaquo;<span class='hidden-xs'> Previous</span></a></li>";
			}

			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					$html .= ( $paged == $i ) ? '<li class="active"><span>' . $i . '</span></li>' : "<li><a href='" . get_pagenum_link( $i ) . "'>" . $i . '</a></li>';
				}
			}

			if ( $paged < $pages && $showitems < $pages ) {
				$html .= '<li><a href="' . get_pagenum_link( $paged + 1 ) . "\"  aria-label='Next'><span class='hidden-xs'>Next </span>&rsaquo;</a></li>";
			}

			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				$html .= "<li><a href='" . get_pagenum_link( $pages ) . "' aria-label='Last'><span class='hidden-xs'>Last </span>&raquo;</a></li>";
			}

			$html .= '</ul>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function memberDetailGallery( $post_id = null ) {
		if ( ! $post_id ) {
			return;
		}
		$html     = null;
		$settings = get_option( rttlp_team()->options['settings'] );
		$fields   = isset( $settings['detail_page_fields'] ) ? $settings['detail_page_fields'] : [];

		$image_ids = get_post_meta( $post_id, 'tlp_team_gallery' );
		if ( ! empty( $image_ids ) && is_array( $image_ids ) ) {
			$fID = get_post_thumbnail_id( $post_id );
			if ( $fID && ! in_array( 'remove_feature_image', $fields ) ) {
				array_unshift( $image_ids, $fID );
			}

			$sliderOption = self::swiper_options();

			$html .= "<div id='team-member-profile-gallery' class='rt-carousel-holder swiper rttm-carousel-slider rt-pos-s' data-options='" . wp_json_encode( $sliderOption ) . "'>";
			$html .= '<div class="swiper-wrapper">';
			foreach ( $image_ids as $id ) {
				$img_alt  = trim( wp_strip_all_tags( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) );
				$alt_tag  = ! empty( $img_alt ) ? $img_alt : get_the_title( $post_id );
				$full_url = wp_get_attachment_image_src( $id, 'large' );
				if ( isset( $full_url[0] ) ) {
					$html .= '<div class="swiper-slide">';
					$html .= '<div class="profile-img-wrapper">';
					$html .= "<img class='profile-img' src='{$full_url[0]}' alt='" . $alt_tag . "'>";
					$html .= '</div>';
					$html .= '</div>';
				}
			}
			$html .= '</div>';
			$html .= '<div class="swiper-arrow swiper-button-next"><i class="fa fa-chevron-right"></i></div>';
			$html .= '<div class="swiper-arrow swiper-button-prev"><i class="fa fa-chevron-left"></i></div>';
			$html .= '<div class="swiper-pagination"></div>';
			$html .= '</div>';
		} else {
			if ( has_post_thumbnail( $post_id ) ) {
				$html .= get_the_post_thumbnail( $post_id, 'large' );
			}
		}

		return $html;
	}

	public static function memberDetailPosts( $post_id = null ) {
		if ( ! $post_id ) {
			return;
		}
		$output   = null;
		$authorId = get_post_field( 'post_author', $post_id );
		if ( $authorId ) {
			$authors_posts = get_posts(
				[
					'author'         => $authorId,
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 5,
				]
			);
		}
		if ( is_array( $authors_posts ) && ! empty( $authors_posts ) ) {
			$output .= "<div class='rt-team-latest-post-wrap'>";
			$output .= '<h3>' . esc_html__( 'Latest post(s)', 'tlp-team' ) . '</h3>';
			$output .= '<ul class="author-latest-post">';
			foreach ( $authors_posts as $authors_post ) {
				$output .= '<li><a href="' . get_permalink( $authors_post->ID ) . '">' . apply_filters(
					'the_title',
					$authors_post->post_title,
					$authors_post->ID
				) . '</a></li>';
			}
			$output .= '</ul>';
			$output .= '</div>';
		}

		return $output;
	}

	public static function rt_get_all_taxonomy_by_post_type() {
		$taxonomies = [];
		$taxObj     = get_object_taxonomies( rttlp_team()->post_type, 'objects' );
		if ( is_array( $taxObj ) && ! empty( $taxObj ) ) {
			foreach ( $taxObj as $tKey => $taxonomy ) {
				if ( $tKey == rttlp_team()->taxonomies['skill'] ) {
					continue;
				}
				$taxonomies[ $tKey ] = $taxonomy->label;
			}
		}

		return $taxonomies;
	}

	public static function rt_get_all_terms_by_taxonomy( $taxonomy = null ) {
		$terms = [];
		if ( $taxonomy ) {
			$temp_terms = get_terms(
				[
					'taxonomy'   => $taxonomy,
					'hide_empty' => 0,
				]
			);
			if ( is_array( $temp_terms ) && ! empty( $temp_terms ) && empty( $temp_terms['errors'] ) ) {
				foreach ( $temp_terms as $term ) {
					$order = get_term_meta( $term->term_id, '_rt_order', true );
					if ( $order === '' ) {
						update_term_meta( $term->term_id, '_rt_order', 0 );
					}
				}
				$termObjs = get_terms(
					[
						'taxonomy'   => $taxonomy,
						'orderby'    => 'meta_value_num',
						'meta_key'   => '_rt_order',
						'order'      => 'ASC',
						'hide_empty' => false,
					]
				);

				foreach ( $termObjs as $term ) {
					$terms[ $term->term_id ] = $term->name;
				}
			}
		}

		return $terms;
	}

	/* Convert hexdec color string to rgb(a) string */
	public static function TLPhex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = [ $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] ];
		} elseif ( strlen( $color ) == 3 ) {
			$hex = [ $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] ];
		} else {
			return $default;
		}

		// Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return rgb(a) color string
		return $output;
	}

	/**
	 * @return array
	 * Image size
	 */
	public static function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = [];
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'large' ] ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = [
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				];
			}
		}

		$imgSize = [];
		foreach ( $sizes as $key => $img ) {
			$imgSize[ $key ] = ucfirst( $key ) . " ({$img['width']}*{$img['height']})";
		}
		$imgSize['ttp_custom'] = esc_html__( 'Custom image size', 'tlp-team' );

		return $imgSize;
	}

	public static function getAllTermsByTaxonomyName( $taxonomy ) {

		$terms = [];
		if ( $taxonomy ) {
			$termList = get_terms( [ rttlp_team()->taxonomies[ $taxonomy ] ], [ 'hide_empty' => 0 ] );
			if ( is_array( $termList ) && ! empty( $termList ) && empty( $termList['errors'] ) ) {
				foreach ( $termList as $term ) {
					$terms[ $term->term_id ] = $term->name;
				}
			}
		}

		return $terms;
	}

	public static function getFeatureImageSrc( $post_id, $fImgSize = 'medium', $defaultImgId = null, $customImgSize = [] ) {

		$imgSrc = null;
		$cSize  = false;
		if ( $fImgSize == 'ttp_custom' ) {
			$fImgSize = 'full';
			$cSize    = true;
		}

		if ( $aID = get_post_thumbnail_id( $post_id ) ) {
			$image  = wp_get_attachment_image_src( $aID, $fImgSize );
			$imgSrc = $image[0];
		}
		if ( ! $imgSrc && $defaultImgId ) {
			$image  = wp_get_attachment_image_src( $defaultImgId, $fImgSize );
			$imgSrc = $image[0];
		}
		if ( $imgSrc && $cSize ) {
			$w = ( ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null );
			$h = ( ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null );
			$c = ( ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true );
			if ( $w && $h ) {
				$imgSrc = self::rtImageReSize( $imgSrc, $w, $h, $c );
			}
		}
		if ( ! $imgSrc ) {
			$imgSrc = rttlp_team()->assets_url() . 'images/demo.jpg';
		}

		return $imgSrc;
	}

	/**
	 * @param        $post_id
	 * @param string  $fImgSize
	 * @param null    $defaultImgId
	 * @param array   $customImgSize
	 *
	 * @return string|null
	 */
	public static function getFeatureImageHtml( $post_id, $fImgSize = 'medium', $defaultImgId = null, $customImgSize = [], $lazy = false ) {

		$imgHtml = $imgSrc = $attachment_id = null;
		$cSize   = false;
		if ( $fImgSize == 'ttp_custom' ) {
			$fImgSize = 'full';
			$cSize    = true;
		}
		$aID        = get_post_thumbnail_id( $post_id );
		$post_title = get_the_title( $post_id );
		$img_alt    = trim( wp_strip_all_tags( get_post_meta( $aID, '_wp_attachment_image_alt', true ) ) );
		$alt_tag    = ! empty( $img_alt ) ? $img_alt : trim( wp_strip_all_tags( $post_title ) );
		$lazy_class = $lazy ? ' swiper-lazy' : '';
		$attr       = [
			'class' => 'img-responsive rt-team-img' . $lazy_class,
			'alt'   => $alt_tag,
		];

		if ( $aID ) {
			$imgHtml       = wp_get_attachment_image( $aID, $fImgSize, false, $attr );
			$attachment_id = $aID;
		}

		if ( ! $imgHtml && $defaultImgId ) {
			$imgHtml       = wp_get_attachment_image( $defaultImgId, $fImgSize, false, $attr );
			$attachment_id = $defaultImgId;
		}

		if ( $imgHtml && $cSize ) {
			preg_match( '@src="([^"]+)"@', $imgHtml, $match );
			$imgSrc = array_pop( $match );
			$w      = ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null;
			$h      = ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null;
			$c      = ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true;

			if ( $w && $h ) {
				$image = self::rtImageReSize( $imgSrc, $w, $h, $c, false );

				if ( ! empty( $image ) ) {
					if ( $lazy ) {
						list( $src, $width, $height ) = $image;

						$hwstring         = image_hwstring( $width, $height );
						$attachment       = get_post( $attachment_id );
						$attr             = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $fImgSize );
						$attr['data-src'] = $src;
						$attr             = array_map( 'esc_attr', $attr );
						$imgHtml          = rtrim( "<img $hwstring" );
						foreach ( $attr as $name => $value ) {
							$imgHtml .= " $name=" . '"' . $value . '"';
						}
						$imgHtml .= ' />';
					} else {
						list( $src, $width, $height ) = $image;

						$hwstring    = image_hwstring( $width, $height );
						$attachment  = get_post( $attachment_id );
						$attr        = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $fImgSize );
						$attr['src'] = $src;
						$attr        = array_map( 'esc_attr', $attr );
						$imgHtml     = rtrim( "<img $hwstring" );
						foreach ( $attr as $name => $value ) {
							$imgHtml .= " $name=" . '"' . $value . '"';
						}
						$imgHtml .= ' />';
					}
				}
			}
		}

		if ( ! $imgHtml ) {
			$hwstring      = image_hwstring( 160, 160 );
			$attr          = isset( $attr['src'] ) ? apply_filters( 'wp_get_attachment_image_attributes', $attr, false, $fImgSize ) : [];
			$attr['class'] = 'default-img';
			$attr['src']   = esc_url( rttlp_team()->assets_url() . 'images/demo.jpg' );
			$attr['alt']   = esc_html__( 'Default Image', 'tlp-team' );
			$imgHtml       = rtrim( "<img $hwstring" );
			foreach ( $attr as $name => $value ) {
				$imgHtml .= " $name=" . '"' . $value . '"';
			}
			$imgHtml .= ' />';
		}

		if ( $lazy ) {
			$imgHtml = $imgHtml . '<div class="swiper-lazy-preloader swiper-lazy-preloader"></div>';
		}

		return $imgHtml;
	}

	/**
	 * Call the Image resize model for resize function
	 *
	 * @param            $url
	 * @param null       $width
	 * @param null       $height
	 * @param null       $crop
	 * @param bool|true  $single
	 * @param bool|false $upscale
	 *
	 * @return array|bool|string
	 * @throws Exception
	 * @throws TTPException
	 */
	public static function rtImageReSize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		$rtResize = new ReSizer();

		return $rtResize->process( $url, $width, $height, $crop, $single, $upscale );
	}


	// public static function get_ttp_short_description( $short_bio, $character_limit = null, $after_desc = null ) {
	// return $character_limit && strlen( $short_bio ) > $character_limit ? substr(
	// strip_tags( $short_bio ),
	// 0,
	// $character_limit
	// ) . $after_desc : $short_bio; // apply_filters( 'the_content', $short_bio )
	// }

	public static function get_ttp_short_description( $short_bio, $character_limit, $after_desc ) {
		if ( empty( $character_limit ) ) {
			return $short_bio;
		}

		$character_limit ++;

		$text = '';

		if ( mb_strlen( $short_bio ) > $character_limit ) {
			$subex   = mb_substr( wp_strip_all_tags( $short_bio ), 0, $character_limit );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

			if ( $excut < 0 ) {
				$text .= mb_substr( $subex, 0, $excut );
			} else {
				$text .= $subex;
			}
		} else {
			$text .= $short_bio;
		}

		$text = $text . $after_desc;

		return $text;
	}

	public static function get_formatted_contact_info( $items, $fields ) {
		$contact_info = null;
		if ( ! empty( $items['email'] ) && in_array( 'email', $fields, true ) ) {
			$contact_info .= '<li class="tlp-email"><i class="far fa-envelope"></i><a href="mailto:' . esc_attr( $items['email'] ) . '"><span class="tlp-email">' . esc_html( $items['email'] ) . '</span></a></li>';
		}
		if ( ! empty( $items['telephone'] ) && in_array( 'telephone', $fields, true ) ) {
			$contact_info .= '<li class="tlp-phone"><i class="fa fa-phone-alt"></i><a href="tel:' . esc_attr( $items['telephone'] ) . '" class="tlp-phone">' . esc_html( $items['telephone'] ) . '</a></li>';
		}
		if ( $items['mobile'] && in_array( 'mobile', $fields, true ) ) {
			$contact_info .= "<li class='tlp-mobile'><i class='fa fa-mobile'></i> <a href='tel:" . esc_attr( $items['mobile'] ) . "'>" . esc_html( $items['mobile'] ) . '</a></li>';
		}
		if ( $items['fax'] && in_array( 'fax', $fields, true ) ) {
			$contact_info .= "<li class='tlp-fax'><i class='fa fa-fax'></i> <a href='fax:" . esc_attr( $items['fax'] ) . "'><span> " . esc_html( $items['fax'] ) . '</span></a></li>';
		}
		if ( ! empty( $items['location'] ) && in_array( 'location', $fields, true ) ) {
			$contact_info .= '<li class="tlp-location"><i class="fa fa-map-marker"></i><span class="tlp-location">' . esc_html( $items['location'] ) . '</span></li>';
		}
		if ( ! empty( $items['web_url'] ) && in_array( 'web_url', $fields, true ) ) {
			$contact_info .= '<li class="tlp-website"><a target="_blank" href="' . esc_url( $items['web_url'] ) . '"><i class="fa fa-globe"></i><span class="tlp-url">' . esc_url( $items['web_url'] ) . '</span></a></li>';
		}

		return $contact_info ? '<div class="contact-info"><ul>' . $contact_info . '</ul></div>' : null;
	}

	public static function get_formatted_designation( $designation, $fields, $experience_year = null ) {
		$html = $exp = null;
		if ( $experience_year && in_array( 'experience_year', $fields ) ) {
			$exp = "<span class='experience'>({$experience_year})</span>";
		}
		if ( in_array( 'designation', $fields ) && $designation ) {
			$html .= '<div class="tlp-position">' . $designation . $exp . '</div>';
		}

		return $html;
	}

	public static function get_formatted_short_bio( $short_bio, $fields ) {
		$html = null;

		if ( $short_bio && in_array( 'short_bio', $fields, true ) ) {
			$html .= '<div class="short-bio">' . apply_filters( 'the_content', self::htmlKses( $short_bio, 'basic' ) ) . '</div>';
		}

		return $html;
	}

	public static function get_formatted_skill( $tlp_skill, $fields ) {
		if ( ! rttlp_team()->has_pro() ) {
			return;
		}

		$html = null;

		if ( is_array( $tlp_skill ) && ! empty( $tlp_skill ) && in_array( 'skill', $fields, true ) ) {
			$html .= '<div class="tlp-team-skill">';

			foreach ( $tlp_skill as $id => $skill ) {
				if ( ! isset( $skill['id'] ) ) {
					continue;
				}

				$html .= '<div class="skill_name"> ' . esc_html( $skill['id'] ) . ' </div><div class="skill-prog tlp-tooltip" title="' . absint( $skill['percent'] ) . '%"><div class="fill" data-progress-animation="' . absint( $skill['percent'] ) . '%"></div></div>';
			}

			$html .= '</div>';
		}

		return $html;
	}

	public static function get_formatted_social_link( $sLink, $fields ) {
		$html = null;

		if ( ! empty( $sLink ) && is_array( $sLink ) && in_array( 'social', $fields, true ) ) {
			$html .= '<div class="social-icons">';

			foreach ( $sLink as $id => $itemLink ) {
				$lURL = ! empty( $itemLink['url'] ) ? esc_url( $itemLink['url'] ) : null;
				$lID  = ! empty( $itemLink['id'] ) ? esc_html( $itemLink['id'] ) : null;

				if ( 'envelope-o' === $lID ) {
					$lURL = ! empty( $itemLink['url'] ) ? $itemLink['url'] : null;
					$lURL = 'mailto:' . esc_attr( $lURL );
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

				if ( 'google-plus' !== $lID && $icon_class ) {
					$html .= '<a href="' . esc_url( $lURL ) . '" title="' . esc_attr( $lID ) . '" target="_blank"><i class="' . esc_attr( $icon_class ) . '"></i></a>';
				}
			}
			$html .= '</div>';
		}

		return $html;
	}

	public static function layoutStyleGenerator( $layoutID, $scMeta, $scID = null ) {
		$css  = null;
		$css .= '<style>';
		// Variable
		if ( $scID ) {
			$primaryColor   = ( isset( $scMeta['primary_color'][0] ) ? $scMeta['primary_color'][0] : null );
			$button         = ! empty( $scMeta['ttp_button_style'][0] ) ? unserialize( $scMeta['ttp_button_style'][0] ) : null;
			$popupBg        = ! empty( $scMeta['ttp_popup_bg_color'][0] ) ? $scMeta['ttp_popup_bg_color'][0] : null;
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
			$social_icon_bg = ! empty( $scMeta['social_icon_bg'][0] ) ? $scMeta['social_icon_bg'][0] : null;
			$mObg           = ! empty( $scMeta['overlay_rgba_bg'][0] ) ? unserialize( $scMeta['overlay_rgba_bg'][0] ) : null;
			$itemP          = ! empty( $scMeta['overlay_padding'][0] ) ? intval( $scMeta['overlay_padding'][0] ) : null;
			$gutter         = ! empty( $scMeta['ttp_gutter'][0] ) ? absint( $scMeta['ttp_gutter'][0] ) : null;
		} else {
			$primaryColor   = ! empty( $scMeta['primary_color'] ) ? $scMeta['primary_color'] : null;
			$button         = ! empty( $scMeta['ttp_button_style'] ) ? $scMeta['ttp_button_style'] : null;
			$popupBg        = ! empty( $scMeta['ttp_popup_bg_color'] ) ? $scMeta['ttp_popup_bg_color'] : null;
			$name           = ! empty( $scMeta['name'] ) ? $scMeta['name'] : null;
			$designation    = ! empty( $scMeta['designation'] ) ? $scMeta['designation'] : null;
			$short_bio      = ! empty( $scMeta['short_bio'] ) ? $scMeta['short_bio'] : null;
			$email          = ! empty( $scMeta['email'] ) ? $scMeta['email'] : null;
			$web_url        = ! empty( $scMeta['web_url'] ) ? $scMeta['web_url'] : null;
			$telephone      = ! empty( $scMeta['telephone'] ) ? $scMeta['telephone'] : null;
			$mobile         = ! empty( $scMeta['mobile'] ) ? $scMeta['mobile'] : null;
			$fax            = ! empty( $scMeta['fax'] ) ? $scMeta['fax'] : null;
			$location       = ! empty( $scMeta['location'] ) ? $scMeta['location'] : null;
			$skill          = ! empty( $scMeta['skill'] ) ? $scMeta['skill'] : null;
			$social_icon    = ! empty( $scMeta['social'] ) ? $scMeta['social'] : null;
			$social_icon_bg = ! empty( $scMeta['social_icon_bg'] ) ? $scMeta['social_icon_bg'] : null;
			$mObg           = ! empty( $scMeta['overlay_rgba_bg'] ) ? $scMeta['overlay_rgba_bg'] : null;
			$itemP          = ! empty( $scMeta['overlay_padding'] ) ? intval( $scMeta['overlay_padding'] ) : null;
			$gutter         = ! empty( $scMeta['ttp_gutter'] ) ? absint( $scMeta['ttp_gutter'] ) : null;
		}
		if ( $primaryColor ) {
			$css .= "#{$layoutID} .single-team-area .overlay a.detail-popup,
					#{$layoutID} .contact-info ul li i{";
			$css .= 'color:' . $primaryColor . ';';
			$css .= '}';
			$css .= "#{$layoutID} .single-team-area .skill-prog .fill,.tlp-team #{$layoutID} .tlp-content,
					.tlp-tooltip + .tooltip > .tooltip-inner,
					#{$layoutID} .layout1 .tlp-content,
					#{$layoutID} .layout11 .single-team-area .tlp-title,
					#{$layoutID} .carousel7 .single-team-area .team-name,
					#{$layoutID} .layout14 .rt-grid-item .tlp-overlay,
					#{$layoutID} .carousel8 .rt-grid-item .tlp-overlay,
					#{$layoutID} .isotope6 .single-team-area h3 .team-name,
					#{$layoutID} .carousel8 .rt-grid-item .tlp-overlay .social-icons:before,
					#{$layoutID} .layout14 .rt-grid-item .tlp-overlay .social-icons:before,
					#{$layoutID} .skill-prog .fill,
					#{$layoutID} .special-selected-top-wrap .ttp-label,
					#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header,
					#{$layoutID} .layout6 .tlp-info-block, #{$layoutID} .carousel9 .single-team-area .tlp-overlay{";
			$css .= 'background:' . $primaryColor . ';';
			$css .= '}';
			$css .= "#{$layoutID} .layout15 .single-team-area:before,
						#{$layoutID} .isotope10 .single-team-area:before,
						#{$layoutID} .carousel11 .single-team-area:before{";
			$css .= 'background:' . self::TLPhex2rgba( $primaryColor, 0.8 );
			$css .= '}';
			$css .= "#rt-smart-modal-container.loading.rt-modal-{$scID} .rt-spinner,
						.tlp-team-skill .tooltip.top .tooltip-arrow{";
			$css .= 'border-top-color:' . $primaryColor . ';';
			$css .= '}';
			$css .= "#{$layoutID} .layout6 .tlp-right-arrow:after{";
			$css .= 'border-color: transparent ' . $primaryColor . ';';
			$css .= '}';
			$css .= "#{$layoutID} .layout6 .tlp-left-arrow:after{";
			$css .= 'border-color:' . $primaryColor . ' transparent transparent;';
			$css .= '}';
			$css .= "#{$layoutID} .layout12 .single-team-area h3 .team-name,
					#{$layoutID} .isotope6 .single-team-area h3 .team-name,
					.rt-team-container .layout12 .single-team-area h3 .team-name,
					.rt-team-container .isotope6 .single-team-area h3 .team-name {";
			$css .= 'background:' . $primaryColor . ';';
			$css .= '}';
			$css .= "#{$layoutID} .special-selected-top-wrap .img:after{";
			$css .= 'background:' . self::TLPhex2rgba( $primaryColor, 0.2 );
			$css .= '}';

			$css .= "#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header a.rt-smart-nav-item{";
			$css .= '-webkit-text-stroke: 1px ' . self::TLPhex2rgba( $primaryColor ) . ';';
			$css .= '}';
			$css .= "#rt-smart-modal-container.rt-modal-{$scID} .rt-smart-modal-header a.rt-smart-modal-close{";
			$css .= '-webkit-text-stroke: 6px ' . self::TLPhex2rgba( $primaryColor ) . ';';
			$css .= '}';
		}

		/* button */
		if ( ! empty( $button ) ) {
			if ( ! empty( $button['bg'] ) ) {
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-loadmore-btn,
						#{$layoutID} .rt-pagination-wrap .pagination > li > a,
						#{$layoutID} .rt-pagination-wrap .pagination > li > span,
						#{$layoutID} .ttp-isotope-buttons.button-group button,
						#{$layoutID} .rt-pagination-wrap .rt-loadmore-btn,
						#{$layoutID} .rt-carousel-holder .swiper-arrow,
						#{$layoutID} .rt-team-container .rt-carousel-holder.swiper .swiper-pagination-bullet,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,
						#{$layoutID} .rt-pagination-wrap .paginationjs .paginationjs-pages li>a{";
				$css .= "background-color: {$button['bg']};";
				$css .= '}';
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-infinite-action .rt-infinite-loading{";
				$css .= 'color: ' . self::TLPhex2rgba( $button['bg'], 0.5 );
				$css .= '}';
			}
			if ( ! empty( $button['hover_bg'] ) ) {
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-loadmore-btn:hover,
						#{$layoutID} .rt-pagination-wrap .pagination > li > a:hover,
						#{$layoutID} .rt-pagination-wrap .pagination > li > span:hover,
						#{$layoutID} .rt-carousel-holder .swiper-arrow:hover,
						#{$layoutID} .rt-team-container .rt-carousel-holder.swiper .swiper-pagination-bullet:hover,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,
						#{$layoutID} .ttp-isotope-buttons.button-group button:hover,
						#{$layoutID} .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages li>a:hover{";
				$css .= "background-color: {$button['hover_bg']};";
				$css .= '}';
			}
			if ( ! empty( $button['active_bg'] ) ) {
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages ul li.active > a,
				#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item.selected,
				#{$layoutID} .ttp-isotope-buttons.button-group .selected,
				#{$layoutID} .rt-carousel-holder .swiper-pagination-bullet.swiper-pagination-bullet-active,
				#{$layoutID} .rt-pagination-wrap .pagination > .active > span{";
				$css .= "background-color: {$button['active_bg']};";
				$css .= '}';
			}
			if ( ! empty( $button['text'] ) ) {
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-loadmore-btn,
						#{$layoutID} .rt-pagination-wrap .pagination > li > a,
						#{$layoutID} .rt-pagination-wrap .pagination > li > span,
						#{$layoutID} .ttp-isotope-buttons.button-group button,
						#{$layoutID} .rt-carousel-holder .swiper-arrow i,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item,
						#{$layoutID} .rt-pagination-wrap .paginationjs .paginationjs-pages li>a{";
				$css .= "color: {$button['text']};";
				$css .= '}';
			}
			if ( ! empty( $button['hover_text'] ) ) {
				$css .= "#{$layoutID} .rt-pagination-wrap .rt-loadmore-btn:hover,
						#{$layoutID} .rt-pagination-wrap .pagination > li > a:hover,
						#{$layoutID} .rt-pagination-wrap .pagination > li > span:hover,
						#{$layoutID} .ttp-isotope-buttons.button-group button:hover,
						#{$layoutID} .rt-carousel-holder .swiper-arrow:hover i,
						#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item:hover,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap .rt-filter-dropdown .rt-filter-dropdown-item:hover,
						#{$layoutID} .rt-pagination-wrap .rt-page-numbers .paginationjs .paginationjs-pages li>a:hover{";
				$css .= "color: {$button['hover_text']};";
				$css .= '}';
			}
			if ( ! empty( $button['border'] ) ) {
				$css .= "#{$layoutID} .rt-filter-item-wrap.rt-filter-button-wrap span.rt-filter-button-item,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-sort-order-action,
						#{$layoutID} .rt-layout-filter-container .rt-filter-wrap .rt-filter-item-wrap.rt-filter-dropdown-wrap{";
				$css .= "border-color: {$button['border']};";
				$css .= '}';
			}
		}

		/* gutter */
		if ( $gutter ) {
			$css    .= "#{$layoutID} [class*='rt-col-'] {";
			$css    .= "padding-left : {$gutter}px;";
			$css    .= "padding-right : {$gutter}px;";
			$bGutter = $gutter * 2;
			$css    .= "margin-bottom : {$bGutter}px;";
			$css    .= '}';
			$css    .= "#{$layoutID} .rt-row.special01 .rt-special-wrapper .rt-col-sm-4 [class*='rt-col-'] {";
			$css    .= 'padding-left : 0;';
			$css    .= 'padding-right : 0;';
			$css    .= '}';
			$css    .= "#{$layoutID} .rt-row.special01 .rt-special-wrapper #special-selected-wrapper {";
			$css    .= 'margin : 0;';
			$css    .= '}';
			$css    .= "#{$layoutID} .rt-row.special01 .rt-special-wrapper #special-selected-wrapper .special-selected-top-wrap > div {";
			$css    .= 'margin-bottom : 0;';
			$css    .= '}';
			$css    .= "#{$layoutID} .rt-row.special01 .rt-special-wrapper #special-selected-wrapper .rt-col-sm-12 {";
			$css    .= 'margin-bottom : 0;';
			$css    .= '}';
			$css    .= "#{$layoutID} .rt-row{";
			$css    .= "margin-left : -{$gutter}px;";
			$css    .= "margin-right : -{$gutter}px;";
			$css    .= '}';
			$css    .= "#{$layoutID}.rt-container-fluid,#{$layoutID}.rt-container,#{$layoutID}.rt-team-container{";
			$css    .= "padding-left : {$gutter}px;";
			$css    .= "padding-right : {$gutter}px;";
			$css    .= '}';
		}

		/* popup background color */
		if ( $popupBg ) {
			// $id = str_replace("rt-team-container-", "", $layoutID);
			$css .= "#tlp-popup-wrap.tlp-popup-wrap-{$scID} .tlp-popup-navigation-wrap,
					#tlp-modal.tlp-modal-{$scID} .md-content,
					#tlp-modal.tlp-modal-{$scID} .md-content > .tlp-md-content-holder .tlp-md-content{";
			$css .= 'background-color:' . $popupBg . ';';
			$css .= '}';
		}

		// Name
		if ( ! empty( $name ) ) {
			$cCss  = null;
			$cCss .= ! empty( $name['color'] ) ? 'color:' . $name['color'] . ';' : null;
			$cCss .= ! empty( $name['align'] ) ? 'text-align:' . $name['align'] . ';' : null;
			$cCss .= ! empty( $name['size'] ) ? 'font-size:' . $name['size'] . 'px;' : null;
			$cCss .= ! empty( $name['weight'] ) ? 'font-weight:' . $name['weight'] . ';' : null;
			if ( $cCss ) {
				$css .= "#{$layoutID} h3,
						#{$layoutID} h3 a,
						#{$layoutID} .overlay h3 a,
						#{$layoutID} .single-team-area .tlp-content h3 a{ {$cCss} }";
			}
			if ( ! empty( $name['hover_color'] ) ) {
				$css .= "#{$layoutID} h3:hover,
						#{$layoutID} h3 a:hover,
						#{$layoutID} .overlay h3 a:hover,
						#{$layoutID} .single-team-area .tlp-content h3 a:hover{ color: {$name['hover_color']}; }";
			}
		}
		// Designation
		if ( ! empty( $designation ) ) {
			$cCss  = null;
			$cCss .= ! empty( $designation['color'] ) ? 'color:' . $designation['color'] . ';' : null;
			$cCss .= ! empty( $designation['align'] ) ? 'text-align:' . $designation['align'] . ';' : null;
			$cCss .= ! empty( $designation['size'] ) ? 'font-size:' . $designation['size'] . 'px;' : null;
			$cCss .= ! empty( $designation['weight'] ) ? 'font-weight:' . $designation['weight'] . ';' : null;

			$css .= "#{$layoutID} .tlp-position,
					#{$layoutID} .tlp-position a,
					#{$layoutID} .overlay .tlp-position,
					#{$layoutID} .tlp-layout-isotope .overlay .tlp-position{ {$cCss} }";

			if ( ! empty( $designation['hover_color'] ) ) {
				$css .= "#{$layoutID} .tlp-position:hover,
						#{$layoutID} .tlp-position a:hover,
						#{$layoutID} .overlay .tlp-position:hover,
						#{$layoutID} .tlp-layout-isotope .overlay .tlp-position:hover{ color: {$designation['hover_color']}; }";
			}
		}

		// Short biography
		if ( ! empty( $short_bio ) ) {
			$cCss  = null;
			$cCss .= ! empty( $short_bio['color'] ) ? 'color:' . $short_bio['color'] . ';' : null;
			$cCss .= ! empty( $short_bio['align'] ) ? 'text-align:' . $short_bio['align'] . ';' : null;
			$cCss .= ! empty( $short_bio['size'] ) ? 'font-size:' . $short_bio['size'] . 'px;' : null;
			$cCss .= ! empty( $short_bio['weight'] ) ? 'font-weight:' . $short_bio['weight'] . ';' : null;
			$css  .= "#{$layoutID} .short-bio p,#{$layoutID} .short-bio p a,
					#{$layoutID} .overlay .short-bio p, #{$layoutID} .overlay .short-bio p a{{$cCss}}";
		}

		// Email
		if ( ! empty( $email ) ) {
			$cCss  = null;
			$cCss .= ! empty( $email['color'] ) ? 'color:' . $email['color'] . ';' : null;
			$cCss .= ! empty( $email['size'] ) ? 'font-size:' . $email['size'] . 'px;' : null;
			$cCss .= ! empty( $email['weight'] ) ? 'font-weight:' . $email['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-email, #{$layoutID} a .tlp-email{ {$cCss} }";
		}

		// Web URL
		if ( ! empty( $web_url ) ) {
			$cCss  = null;
			$cCss .= ! empty( $web_url['color'] ) ? 'color:' . $web_url['color'] . ';' : null;
			$cCss .= ! empty( $web_url['size'] ) ? 'font-size:' . $web_url['size'] . 'px;' : null;
			$cCss .= ! empty( $web_url['weight'] ) ? 'font-weight:' . $web_url['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-url{{$cCss}}";
		}

		// Telephone
		if ( ! empty( $telephone ) ) {
			$cCss  = null;
			$cCss .= ! empty( $telephone['color'] ) ? 'color:' . $telephone['color'] . ';' : null;
			$cCss .= ! empty( $telephone['size'] ) ? 'font-size:' . $telephone['size'] . 'px;' : null;
			$cCss .= ! empty( $telephone['weight'] ) ? 'font-weight:' . $telephone['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-phone{{$cCss}}";
		}
		// mobile
		if ( ! empty( $mobile ) ) {
			$cCss  = null;
			$cCss .= ! empty( $mobile['color'] ) ? 'color:' . $mobile['color'] . ';' : null;
			$cCss .= ! empty( $mobile['size'] ) ? 'font-size:' . $mobile['size'] . 'px;' : null;
			$cCss .= ! empty( $mobile['weight'] ) ? 'font-weight:' . $mobile['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-mobile{{$cCss}}";
		}
		// Fax
		if ( ! empty( $fax ) ) {
			$cCss  = null;
			$cCss .= ! empty( $fax['color'] ) ? 'color:' . $fax['color'] . ';' : null;
			$cCss .= ! empty( $fax['size'] ) ? 'font-size:' . $fax['size'] . 'px;' : null;
			$cCss .= ! empty( $fax['weight'] ) ? 'font-weight:' . $fax['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-fax{{$cCss}}";
		}

		// Location
		if ( ! empty( $location ) ) {
			$cCss  = null;
			$cCss .= ! empty( $location['color'] ) ? 'color:' . $location['color'] . ';' : null;
			$cCss .= ! empty( $location['size'] ) ? 'font-size:' . $location['size'] . 'px;' : null;
			$cCss .= ! empty( $location['weight'] ) ? 'font-weight:' . $location['weight'] . ';' : null;
			$css  .= "#{$layoutID} .tlp-location{{$cCss}}";
		}

		// Skill
		if ( ! empty( $skill ) ) {
			$cCss  = null;
			$cCss .= ! empty( $skill['color'] ) ? 'color:' . $skill['color'] . ';' : null;
			$cCss .= ! empty( $skill['align'] ) ? 'text-align:' . $skill['align'] . ';' : null;
			$cCss .= ! empty( $skill['size'] ) ? 'font-size:' . $skill['size'] . 'px;' : null;
			$cCss .= ! empty( $skill['weight'] ) ? 'font-weight:' . $skill['weight'] . ';' : null;
			$css  .= "#{$layoutID} .skill_name{{$cCss}}";
		}

		// Social Icon
		if ( ! empty( $social_icon ) ) {
			$cCss  = null;
			$cCss .= ! empty( $social_icon['color'] ) ? 'color:' . $social_icon['color'] . ';' : null;
			$cCss .= ! empty( $social_icon['size'] ) ? 'font-size:' . $social_icon['size'] . 'px;' : null;
			$cCss .= ! empty( $social_icon['weight'] ) ? 'font-weight:' . $social_icon['weight'] . ';' : null;

			$css .= "#{$layoutID} .overlay .social-icons a,
					#{$layoutID} .tlp-social,
					#{$layoutID} .social-icons a{ {$cCss} }";
			if ( ! empty( $social_icon['align'] ) ) {
				$css .= "#{$layoutID} .social-icons,#{$layoutID} .tlp-social, #{$layoutID} .overlay .social-icons { text-align: {$social_icon['align']}; }";
			}
		}

		// Social Icon bg
		if ( $social_icon_bg ) {
			$css .= "#{$layoutID} .social-icons a{background:{$social_icon_bg};}";
		}

		// Overlay
		if ( ! empty( $mObg ) ) {
			if ( ! empty( $mObg['color'] ) && ! empty( $mObg['opacity'] ) ) {
				$css .= "#{$layoutID} .single-team-area .overlay,
						#{$layoutID} .single-team-area:hover .tlp-overlay,
						#{$layoutID} .single-team-area:hover .tlp-overlay,
						#{$layoutID} .layout7 figcaption:hover,
						#{$layoutID} .isotope8 .tlp-overlay,
						#{$layoutID} .layout13 .tlp-overlay,
						#{$layoutID} .layout8 .tlp-overlay .tlp-title,
						#{$layoutID} .isotope1 .rt-grid-item:hover .overlay,
						#{$layoutID} .isotope4 figcaption:hover,
						#{$layoutID} .layout11 .single-team-area .tlp-title,
						#{$layoutID} .isotope5 .tlp-overlay .tlp-title {";
				$css .= 'background:' . self::TLPhex2rgba(
					$mObg['color'],
					( $mObg['opacity'] ? $mObg['opacity'] : .8 )
				) . ';';
				$css .= '}';
			}
		}

		// Overlay item padding
		if ( $itemP ) {
			$css .= "#{$layoutID} .single-team-area .overlay .overlay-element,
					#{$layoutID} .single-team-area:hover h3,
					#{$layoutID} .isotope3 .single-team-area:hover h3,
					#{$layoutID} .layout7 figcaption:hover,
					#{$layoutID} .isotope4 figcaption:hover,
					#{$layoutID} .layout9 .tlp-overlay{";
			$css .= 'padding-top:' . $itemP . '%; ';
			$css .= '}';
		}
		$css .= '</style>';

		return $css;
	}

	public static function generatorShortcodeCss( $scID ) {
		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$upload_dir     = wp_upload_dir();
		$upload_basedir = $upload_dir['basedir'];
		$cssFile        = $upload_basedir . '/tlp-team/team-sc.css';
		if ( $css = self::render( 'sc-css', compact( 'scID' ), true ) ) {
			$css = sprintf( '/*sc-%2$d-start*/%1$s/*sc-%2$d-end*/', $css, $scID );
			if ( file_exists( $cssFile ) && ( $oldCss = $wp_filesystem->get_contents( $cssFile ) ) ) {
				if ( strpos( $oldCss, '/*sc-' . $scID . '-start' ) !== false ) {
					$oldCss = preg_replace( '/\/\*sc-' . $scID . '-start[\s\S]+?sc-' . $scID . '-end\*\//', '', $oldCss );
					$oldCss = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", '', $oldCss );
				}
				$css = $oldCss . $css;
			} elseif ( ! file_exists( $cssFile ) ) {
				$upload_basedir_trailingslashit = trailingslashit( $upload_basedir );
				$wp_filesystem->mkdir( $upload_basedir_trailingslashit . 'tlp-team' );
			}
			if ( ! $wp_filesystem->put_contents( $cssFile, $css ) ) {
				error_log( print_r( 'Team: Error Generated css file ', true ) );
			}
		}
	}

	/**
	 * Generate Shortcode css
	 *
	 * @param integer $scID
	 *
	 * @return void
	 */
	public static function removeGeneratorShortcodeCss( $scID ) {
		$upload_dir     = wp_upload_dir();
		$upload_basedir = $upload_dir['basedir'];
		$cssFile        = $upload_basedir . '/tlp-team/team-sc.css';
		if ( file_exists( $cssFile ) && ( $oldCss = file_get_contents( $cssFile ) ) && strpos( $oldCss, '/*sc-' . $scID . '-start' ) !== false ) {
			$css = preg_replace( '/\/\*sc-' . $scID . '-start[\s\S]+?sc-' . $scID . '-end\*\//', '', $oldCss );
			$css = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", '', $css );
			file_put_contents( $cssFile, $css );
		}
	}

	public static function rt_plugin_team_sc_pro_information() {
		?>
		<div class="rt-document-box">
			<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
			<div class="rt-box-content">
				<h3 class="rt-box-title">Documentation</h3>
				<p>Get started by spending some time with the documentation we included step by step process with
					screenshots with video.</p>
				<a href="<?php echo esc_url( rttlp_team()->documentation_link() ); ?>" target="_blank" class="rt-admin-btn">Documentation</a>
			</div>
		</div>
		<div class="rt-document-box">
			<div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
			<div class="rt-box-content">
				<h3 class="rt-box-title">Need Help?</h3>
				<p>Stuck with something? Please create a
					<a href="<?php echo esc_url( rttlp_team()->ticket_link() ); ?>">ticket here</a> or post on <a href="<?php echo esc_url( rttlp_team()->fb_link() ); ?>">facebook group</a>. For emergency
					case join our <a href="<?php echo esc_url( rttlp_team()->radius_link() ); ?>">live chat</a>.</p>
				<a href="<?php echo esc_url( rttlp_team()->ticket_link() ); ?>" target="_blank" class="rt-admin-btn">Get
					Support</a>
			</div>
		</div>
		<?php
	}

	public static function swiper_options() {
		$options = [
			'speed'         => (int) 1000,
			'slidesPerView' => (int) 1,
			'loop'          => (bool) true,
			'autoplay'      => [
				'delay'                => (int) 7000,
				'pauseOnMouseEnter'    => (bool) true,
				'disableOnInteraction' => (bool) false,
			],
		];

		return $options;
	}

	/**
	 * Register Elementor widget controls.
	 *
	 * Adds different control fields into the widget settings.
	 *
	 * @param array  $fields Control fields to add.
	 * @param object $obj Object in which controls are adding.
	 *
	 * @return void
	 *
	 * @access public
	 */
	public static function addElControls( $fields, $obj ) {
		foreach ( $fields as $field ) {
			if ( ! empty( $field['type'] ) ) {
				$field['type'] = self::elFields( $field['type'] );
			}

			if ( isset( $field['mode'] ) && 'section_start' === $field['mode'] ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$obj->start_controls_section( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'section_end' === $field['mode'] ) {
				$obj->end_controls_section();
			} elseif ( isset( $field['mode'] ) && 'tabs_start' === $field['mode'] ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$obj->start_controls_tabs( $id );
			} elseif ( isset( $field['mode'] ) && 'tabs_end' === $field['mode'] ) {
				$obj->end_controls_tabs();
			} elseif ( isset( $field['mode'] ) && 'tab_start' === $field['mode'] ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$obj->start_controls_tab( $id, $field );
			} elseif ( isset( $field['mode'] ) && 'tab_end' === $field['mode'] ) {
				$obj->end_controls_tab();
			} elseif ( isset( $field['mode'] ) && 'group' === $field['mode'] ) {
				$type          = $field['type'];
				$field['name'] = $field['id'];
				unset( $field['mode'] );
				unset( $field['type'] );
				unset( $field['id'] );
				$obj->add_group_control( $type, $field );
			} elseif ( isset( $field['mode'] ) && 'responsive' === $field['mode'] ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$obj->add_responsive_control( $id, $field );
			} else {
				$id = $field['id'];
				unset( $field['id'] );
				$obj->add_control( $id, $field );
			}
		}
	}

	/**
	 * Elementor Fields.
	 *
	 * @param string $type Control type.
	 *
	 * @return object
	 */
	private static function elFields( $type ) {
		$controls = \Elementor\Controls_Manager::class;

		switch ( $type ) {
			case 'text':
				$type = $controls::TEXT;
				break;

			case 'html':
				$type = $controls::RAW_HTML;
				break;

			case 'select':
				$type = $controls::SELECT;
				break;

			case 'select2':
				$type = $controls::SELECT2;
				break;

			case 'number':
				$type = $controls::NUMBER;
				break;

			case 'image-dimensions':
				$type = $controls::IMAGE_DIMENSIONS;
				break;

			case 'dimensions':
				$type = $controls::DIMENSIONS;
				break;

			case 'media':
				$type = $controls::MEDIA;
				break;

			case 'switch':
				$type = $controls::SWITCHER;
				break;

			case 'color':
				$type = $controls::COLOR;
				break;

			case 'choose':
				$type = $controls::CHOOSE;
				break;

			case 'slider':
				$type = $controls::SLIDER;
				break;

			case 'typography':
				$type = \Elementor\Group_Control_Typography::get_type();
				break;

			case 'border':
				$type = \Elementor\Group_Control_Border::get_type();
				break;
		}

		return $type;
	}

	/**
	 * Adding filter hook.
	 *
	 * @param string $filterName Filter hook name.
	 * @param string $var Variable to apply.
	 * @param object $obj Reference object.
	 *
	 * @return mixed
	 */
	public static function filter( $filterName, $obj ) {
		return array_merge( apply_filters( $filterName, $obj ) );
	}

	/**
	 * Prints HTMl.
	 *
	 * @param string $html HTML.
	 * @param bool   $allHtml All HTML.
	 *
	 * @return mixed
	 */
	public static function print_html( $html, $allHtml = false ) {
		if ( $allHtml ) {
			echo stripslashes_deep( $html );
		} else {
			echo wp_kses_post( stripslashes_deep( $html ) );
		}
	}

	/**
	 * Allowed HTML for wp_kses.
	 *
	 * @param string $level Tag level.
	 *
	 * @return mixed
	 */
	public static function allowedHtml( $level = 'basic' ) {
		$allowed_html = [];

		switch ( $level ) {
			case 'basic':
				$allowed_html = [
					'b'      => [
						'class' => [],
						'id'    => [],
					],
					'i'      => [
						'class' => [],
						'id'    => [],
					],
					'u'      => [
						'class' => [],
						'id'    => [],
					],
					'br'     => [
						'class' => [],
						'id'    => [],
					],
					'em'     => [
						'class' => [],
						'id'    => [],
					],
					'span'   => [
						'class' => [],
						'id'    => [],
					],
					'strong' => [
						'class' => [],
						'id'    => [],
					],
					'hr'     => [
						'class' => [],
						'id'    => [],
					],
					'a'      => [
						'href'   => [],
						'title'  => [],
						'class'  => [],
						'id'     => [],
						'target' => [],
					],
				];
				break;

			case 'advanced':
				$allowed_html = [
					'b'      => [
						'class' => [],
						'id'    => [],
					],
					'i'      => [
						'class' => [],
						'id'    => [],
					],
					'u'      => [
						'class' => [],
						'id'    => [],
					],
					'br'     => [
						'class' => [],
						'id'    => [],
					],
					'em'     => [
						'class' => [],
						'id'    => [],
					],
					'span'   => [
						'class' => [],
						'id'    => [],
					],
					'strong' => [
						'class' => [],
						'id'    => [],
					],
					'hr'     => [
						'class' => [],
						'id'    => [],
					],
					'a'      => [
						'href'   => [],
						'title'  => [],
						'class'  => [],
						'id'     => [],
						'target' => [],
					],
					'input'  => [
						'type'   => [],
						'name'   => [],
						'class'  => [],
						'value'  => [],
					],
				];
				break;

			case 'image':
				$allowed_html = [
					'img' => [
						'src'      => [],
						'data-src' => [],
						'alt'      => [],
						'height'   => [],
						'width'    => [],
						'class'    => [],
						'id'       => [],
						'style'    => [],
						'srcset'   => [],
						'loading'  => [],
						'sizes'    => [],
					],
					'div' => [
						'class' => [],
					],
				];
				break;

			case 'anchor':
				$allowed_html = [
					'a' => [
						'href'  => [],
						'title' => [],
						'class' => [],
						'id'    => [],
						'style' => [],
					],
				];
				break;

			default:
				// code...
				break;
		}

		return $allowed_html;
	}

	/**
	 * Definition for wp_kses.
	 *
	 * @param string $string String to check.
	 * @param string $level Tag level.
	 *
	 * @return mixed
	 */
	public static function htmlKses( $string, $level ) {
		if ( empty( $string ) ) {
			return;
		}

		return wp_kses( $string, self::allowedHtml( $level ) );
	}
}
