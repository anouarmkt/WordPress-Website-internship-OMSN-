<?php
/**
 * Exit if accessed directly.
 */
defined( 'ABSPATH' ) or die;

/**
 * WPZOOM Instagram Widget Display class
 *
 * @package WPZOOM_Instagram_Widget
 */
class Wpzoom_Instagram_Widget_Display {
	/**
	 * @var Wpzoom_Instagram_Widget_Display The reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * @var Wpzoom_Instagram_Widget_API
	 */
	protected $api;

	/**
	 * Is this the pro version?
	 */
	private $is_pro = false;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Wpzoom_Instagram_Widget_Display The *Singleton* instance.
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Runs some intialization functions.
	 *
	 * @return void
	 */
	public function init() {
		// add_image_size( 'wpzoom-instagram-profile-photo-size', 128, 128, true );

		$this->is_pro = apply_filters( 'wpz-insta_is-pro', false );

		add_shortcode( 'instagram', array( $this, 'get_shortcode_output' ) );
	}

	/**
	 * Returns the markup for the feed with the given ID.
	 *
	 * @param  int    $feed_id The ID of the feed to return the markup for.
	 * @return string          The markup for the given feed.
	 */
	public function get_feed_output( int $feed_id ) {
		if ( $feed_id > -1 ) {
			$feed = get_post( $feed_id, OBJECT, 'display' );

			if ( null !== $feed && $feed instanceof WP_Post ) {
				$user_id = intval( get_post_meta( $feed_id, '_wpz-insta_user-id', true ) );
				$feed_settings = array();

				foreach( WPZOOM_Instagram_Widget_Settings::$feed_settings as $setting_name => $setting_args ) {
					$feed_settings[ $setting_name ] = WPZOOM_Instagram_Widget_Settings::get_feed_setting_value( $feed_id, $setting_name );
				}

				$feed_settings['feed-id'] = $feed_id;
				$feed_settings['user-id'] = $user_id;

				return $this->feed_content( $feed_settings );
			}
		}

        if ( current_user_can( 'edit_theme_options' ) ) {
			return is_admin() ? sprintf(
				'<p class="error" style="color:red"><strong>%s</strong></p>',
				esc_html__( 'There was a problem displaying the selected feed. Please check the configuration...', 'instagram-widget-by-wpzoom' )
			) : '';
    	}
	}

	/**
	 * Returns the markup for the feed shortcode.
	 *
	 * @param  array  $atts    The attributes on the shortcode.
	 * @param  string $content The content (if any) in the shortcode.
	 * @param  string $tag     The shortcode tag.
	 * @return string
	 */
	public function get_shortcode_output( array $atts, string $content, string $tag ) {
		if ( ! empty( $atts ) && is_array( $atts ) && array_key_exists( 'feed', $atts ) ) {
			$feed_id = intval( $atts['feed'] );

			if ( $feed_id > -1 ) {
				return sprintf(
					"<style type=\"text/css\">%s</style>\n%s",
					$this->output_styles( $feed_id, false ),
					$this->get_feed_output( $feed_id )
				);
			}
		}

		return is_admin() ? sprintf(
			'<p class="error" style="color:red"><strong>%s</strong></p>',
			esc_html__( 'There was a problem displaying the selected feed. Please check the configuration...', 'instagram-widget-by-wpzoom' )
		) : '';
	}

	/**
	 * Outputs the markup for the feed with the given ID.
	 *
	 * @param  int  $feed_id The ID of the feed to output.
	 * @param  bool $echo    Whether to output the feed or return it.
	 * @return void
	 */
	public function output_feed( int $feed_id, bool $echo = true ) {
		$output = sprintf(
			"<style type=\"text/css\">%s</style>\n%s",
			$this->output_styles( $feed_id, false ),
			$this->get_feed_output( $feed_id )
		);

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Outputs the markup for the preview of a feed configured with the given arguments.
	 *
	 * @param  array $args The arguments to define how to output the feed preview.
	 * @return void
	 */
	public function output_preview( array $args ) {
		printf(
			"<style type=\"text/css\">%s</style>\n%s",
			$this->output_preview_styles( $args, false ),
			$this->feed_content( $args, true )
		);
	}

	/**
	 * Returns the markup for the preview of a feed configured with the given arguments.
	 *
	 * @param  array  $args The arguments to define how to return the feed preview.
	 * @return string
	 */
	public function get_preview( array $args ) {
		return sprintf(
			"<style type=\"text/css\">%s</style>\n%s",
			$this->output_preview_styles( $args, false ),
			$this->feed_content( $args, true )
		);
	}

	/**
	 * Returns the markup for a feed configured with the given arguments.
	 *
	 * @param  array  $args The arguments to define how to return the feed content.
	 * @return string
	 */
	private function feed_content( array $args, bool $preview = false ) {
		$this->api = Wpzoom_Instagram_Widget_API::getInstance();
		$output = '';
		$user_id = isset( $args['user-id'] ) ? intval( $args['user-id'] ) : -1;

		if ( $user_id > 0 ) {
			$user = get_post( $user_id );

			if ( $user instanceof WP_Post ) {
				$show_user_name = isset( $args['show-account-username'] ) && boolval( $args['show-account-username'] );
				$user_name = get_the_title( $user );
				$user_name_display = sprintf( '@%s', $user_name );
				$user_link = 'https://www.instagram.com/' . $user_name;
				$show_user_nname = isset( $args['show-account-name'] ) && boolval( $args['show-account-name'] );
				$user_display_name = get_post_meta( $user_id, '_wpz-insta_user_name', true );
				$show_user_bio = isset( $args['show-account-bio'] ) && boolval( $args['show-account-bio'] );
				$user_bio = get_the_content( null, false, $user );
				$show_user_image = isset( $args['show-account-image'] ) && boolval( $args['show-account-image'] );
				$user_image = get_the_post_thumbnail_url( $user, 'thumbnail' ) ?: plugin_dir_url( __FILE__ ) . 'dist/images/backend/user-avatar.jpg';
				$user_account_token = get_post_meta( $user_id, '_wpz-insta_token', true ) ?: '-1';

				if ( '-1' !== $user_account_token ) {
					$attrs = '';
					$layout_names = array( 0 => 'grid', 1 => 'fullwidth', 2 => 'masonry', 3 => 'highlight' );
					$raw_layout = isset( $args['layout'] ) ? intval( $args['layout'] ) : 0;
					$layout_int = $this->is_pro ? $raw_layout : ( $raw_layout > 1 ? 0 : $raw_layout );
					$layout = isset( $layout_names[ $layout_int ] ) ? $layout_names[ $layout_int ] : 'grid';
					$new_posts_interval_number = isset( $args['check-new-posts-interval-number'] ) ? intval( $args['check-new-posts-interval-number'] ) : 1;
					$new_posts_interval_suffix = isset( $args['check-new-posts-interval-suffix'] ) ? intval( $args['check-new-posts-interval-suffix'] ) : 1;
					$enable_request_timeout = isset( $args['enable-request-timeout'] ) ? boolval( $args['enable-request-timeout'] ) : false;
					$amount = isset( $args['item-num'] ) ? intval( $args['item-num'] ) : 9;
					$lightbox = isset( $args['lightbox'] ) ? boolval( $args['lightbox'] ) : true;
					$show_view_on_insta_button = isset( $args['show-view-button' ] ) ? boolval( $args['show-view-button' ] ) : true;
					$show_load_more_button = ( ! $this->is_pro && $preview ) || ( $this->is_pro && isset( $args['show-load-more'] ) && boolval( $args['show-load-more'] ) );
					$image_size = isset( $args['image-size'] ) && in_array( $args['image-size'], array( 'thumbnail', 'low_resolution', 'standard_resolution' ) ) ? $args['image-size'] : 'low_resolution';
					$image_width = isset( $args['image-width'] ) ? intval( $args['image-width'] ) : 320;
					$hide_video_thumbs = isset( $args['hide-video-thumbs'] ) ? boolval( $args['hide-video-thumbs'] ) : true;

					if ( $lightbox ) {
						$attrs .= ' data-lightbox="1"';
					}

					$this->api->set_access_token( $user_account_token );

					if( isset( $args['feed-id'] ) ) {
						$this->api->set_feed_id( $args['feed-id'] );
					}

					$items  = $this->api->get_items( array( 'image-limit' => $amount, 'image-resolution' => $image_size, 'image-width' => $image_width, 'include-pagination' => true, 'bypass-transient' => $preview ) );
					$errors = $this->api->errors->get_error_messages();

					$output .= '<div class="zoom-instagram' . ( isset( $args['feed-id'] ) ? sprintf( ' feed-%d', intval( $args['feed-id'] ) ) : '' ) . sprintf( ' layout-%s', $layout ) . '">';

					if ( ! is_array( $items ) ) {
						return $this->get_errors( $errors );
					} else {
						if ( $show_user_image || $show_user_nname || $show_user_name || $show_user_bio ) {
							$output .= '<header class="zoom-instagram-widget__header">';

							if ( $show_user_image && ! empty( $user_image ) ) {
								$output .= '<div class="zoom-instagram-widget__header-column-left">';
								$output .= '<img src="' . esc_url( $user_image ) . '" alt="' . esc_attr( $user_name_display ) . '" width="70"/>';
								$output .= '</div>';
							}

							if ( $show_user_nname || $show_user_name || $show_user_bio ) {
								$output .= '<div class="zoom-instagram-widget__header-column-right">';

								if ( $show_user_nname ) {
									$output .= '<h5 class="zoom-instagram-widget__header-name">' . esc_html( $user_display_name ) . '</h5>';
								}

								if ( $show_user_name ) {
									$output .= '<p class="zoom-instagram-widget__header-user"><a href="' . esc_url( $user_link ) . '" target="_blank" rel="nofollow">' . esc_html( $user_name_display ) . '</a></p>';
								}

								if ( $show_user_bio ) {
									$output .= '<div class="zoom-instagram-widget__header-bio">' . esc_html( $user_bio ) . '</div>';
								}

								$output .= '</div>';
							}

							$output .= '</header>';
						}

						$output .= '<div class="zoom-instagram-widget__items-wrapper"><ul class="zoom-instagram-widget__items zoom-instagram-widget__items--no-js' . sprintf( ' layout-%s', $layout ) . '"' . $attrs . '>';
						$output .= self::items_html( $items['items'], $args );
						$output .= '</ul></div>';

						if ( $show_view_on_insta_button || ( $show_load_more_button && 'fullwidth' !== $layout ) ) {
							$output .= '<div class="zoom-instagram-widget__footer">';

							if ( $show_view_on_insta_button ) {
								$view_on_insta_label = isset( $args['view-button-text'] ) ? trim( $args['view-button-text'] ) : __( 'View on Instagram', 'instagram-widget-by-wpzoom' );
								$output .= '<a href="' . esc_url( $user_link ) . '" target="_blank" rel="noopener nofollow" class="wpz-button wpz-button-primary wpz-insta-view-on-insta-button">';
								$output .= '<span class="button-icon zoom-svg-instagram-stroke"></span> ';
								$output .= esc_html( $view_on_insta_label );
								$output .= '</a>';
							}

							if ( $show_load_more_button && 'fullwidth' !== $layout ) {
								$output .= '<form method="POST" autocomplete="off" class="wpzinsta-pro-load-more"' . ( ! $this->is_pro ? ' disabled' : '' ) . '>';
								$output .= wp_nonce_field( 'wpzinsta-pro-load-more', '_wpnonce', true, false );
								$output .= '<input type="hidden" name="feed_id" value="' . esc_attr( isset( $args['feed-id'] ) ? intval( $args['feed-id'] ) : -1 ) . '" />';
								$output .= '<input type="hidden" name="item_amount" value="' . esc_attr( $amount ) . '" />';
								$output .= '<input type="hidden" name="image_size" value="' . esc_attr( $image_size ) . '" />';
								$output .= '<input type="hidden" name="next" value="' . ( ! empty( $items ) && array_key_exists( 'paging', $items ) && is_object( $items['paging'] ) && property_exists( $items['paging'], 'next' ) ? esc_url( $items['paging']->next ) : '' ) . '" />';
								$output .= '<button type="submit">' . esc_html( ( isset( $args['load-more-text'] ) ? trim( $args['load-more-text'] ) : __( 'Load More', 'instagram-widget-by-wpzoom' ) ) . ( ! $this->is_pro ? __( ' [PRO only]', 'instagram-widget-by-wpzoom' ) : '' ) ) . '</button>';
								$output .= '</form>';
							}

							$output .= '</div>';
						}

						if ( $lightbox ) {
							$output .= '<div class="wpz-insta-lightbox-wrapper mfp-hide"><div class="swiper-container"><div class="swiper-wrapper">';
							$output .= self::lightbox_items_html( $items['items'], $user_id );
							$output .= '</div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div></div>';
						}
					}

					$output .= '</div>';

					return $output;
				}
			}
		}

		return sprintf(
			'<div class="zoom-instagram"><p class="select-a-feed">%s%s</p></div>',
			'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20 10.8H6.7l4.1-4.5-1.1-1.1-5.8 6.3 5.8 5.8 1.1-1.1-4-3.9H20z" fill="currentColor" stroke="currentColor" stroke-width="1.5"/></svg>',
			__( 'Please select an account in the panel to the left&hellip;', 'instagram-widget-by-wpzoom' )
		);
	}

	/**
	 * Returns the markup for the given feed items, configured with the given arguments.
	 *
	 * @param  array  $items The items to generate the markup for.
	 * @param  array  $args  The arguments to define how to return the feed items.
	 * @return string        The markup for the given feed items, empty string otherwise.
	 */
	public static function items_html( $items, $args ) {
		$output = '';

		if ( ! empty( $items ) && is_array( $items ) ) {
			$count = 0;
			$amount = isset( $args['item-num'] ) ? intval( $args['item-num'] ) : 9;
			$col_num = isset( $args['col-num'] ) && intval( $args['col-num'] ) !== 3 ? intval( $args['col-num'] ) : 3;
			$show_overlay = isset( $args['show-overlay'] ) ? boolval( $args['show-overlay'] ) : true;
            $show_insta_icon = isset( $args['hover-link'] ) ? boolval( $args['hover-link'] ) : true;
			$show_media_type_icons = isset( $args['show-media-type-icons'] ) ? boolval( $args['show-media-type-icons'] ) : true;
			$show_media_type_icons_on_hover = isset( $args['hover-media-type-icons'] ) ? boolval( $args['hover-media-type-icons'] ) : true;
			$hide_video_thumbs = isset( $args['hide-video-thumbs'] ) ? boolval( $args['hide-video-thumbs'] ) : true;
			$image_size = isset( $args['image-size'] ) && in_array( $args['image-size'], array( 'thumbnail', 'low_resolution', 'standard_resolution' ) ) ? $args['image-size'] : 'low_resolution';
			$small_class = $image_size <= 180 ? 'small' : '';
			$svg_icons = plugin_dir_url( __FILE__ ) . 'dist/images/frontend/wpzoom-instagram-icons.svg';

			foreach ( $items as $item ) {
				$inline_attrs  = '';
				$overwrite_src = false;
				$link          = isset( $item['link'] ) ? $item['link'] : '';
				$src           = isset( $item['image-url'] ) ? $item['image-url'] : '';
				$media_id      = isset( $item['image-id'] ) ? $item['image-id'] : '';
				$alt           = isset( $item['image-caption'] ) ? esc_attr( $item['image-caption'] ) : '';
				$likes         = isset( $item['likes_count'] ) ? intval( $item['likes_count'] ) : 0;
				$typ           = isset( $item['type'] ) ? strtolower( $item['type'] ) : 'image';
				$type          = in_array( $typ, array( 'video', 'carousel_album' ) ) ? $typ : false;
				$is_album      = 'carousel_album' == $type;
				$is_video      = 'video' == $type;
				$comments      = isset( $item['comments_count'] ) ? intval( $item['comments_count'] ) : 0;

				/*if ( $is_video && $hide_video_thumbs ) {
					continue;
				}*/

				if ( ! empty( $media_id ) && empty( $src ) ) {
					$inline_attrs  = 'data-media-id="' . esc_attr( $media_id ) . '"';
					$inline_attrs .= 'data-nonce="' . wp_create_nonce( WPZOOM_Instagram_Image_Uploader::get_nonce_action( $media_id ) ) . '"';
					$overwrite_src = true;
				}

				if (
					! empty( $media_id ) &&
					! empty( $src ) &&
					! file_exists( self::convert_url_to_path( $src ) )
				) {
					$inline_attrs  = 'data-media-id="' . esc_attr( $media_id ) . '"';
					$inline_attrs .= 'data-nonce="' . wp_create_nonce( WPZOOM_Instagram_Image_Uploader::get_nonce_action( $media_id ) ) . '"';
					$inline_attrs .= 'data-regenerate-thumbnails="1"';
					//$overwrite_src = true;
				}

				$inline_attrs .= 'data-media-type="' . esc_attr( $type ?: 'image' ) . '"';

				if ( $overwrite_src ) {
					$src = $item['original-image-url'];
				}

				$width = 100;
				$height = 100;
				if ( ! empty( $src ) ) {
					$local = self::attachment_url_to_path( $src );
					$image_size = @wp_getimagesize( false !== $local ? $local : $src );

					if ( false !== $image_size ) {
						$width = $image_size[0];
						$height = $image_size[1];
					}
				}

				$output .= '<li class="zoom-instagram-widget__item' . ( $show_media_type_icons_on_hover ? ' media-icons-hover' : '' ) . '" ' . $inline_attrs . '><div class="zoom-instagram-widget__item-inner-wrap">';

				$output .= sprintf( '<img src="%1$s" width="%3$d" height="%2$d" />', esc_url( $src ), esc_attr( $width ), esc_attr( $height ) );

				if ( $show_overlay ) {
					$output .= '<div class="hover-layout zoom-instagram-widget__overlay zoom-instagram-widget__black ' . $small_class . '">';

					if ( ( $show_media_type_icons || $show_media_type_icons_on_hover ) && ! empty( $type ) ) {
						$output .= '<svg class="svg-icon" shape-rendering="geometricPrecision"><use xlink:href="' . esc_url( $svg_icons ) . '#' . $type . '"></use></svg>';
					}

					if ( ! empty( $likes ) && ! empty( $comments ) ) {
						$output .= '<div class="hover-controls">
							<span class="dashicons dashicons-heart"></span>
							<span class="counter">' . self::format_number( $likes ) . '</span>
							<span class="dashicons dashicons-format-chat"></span>
							<span class="counter">' . self::format_number( $comments ) . '</span>
						</div>';
					}

                    if (! empty ( $show_insta_icon ) ) {
    					$output .= '<div class="zoom-instagram-icon-wrap"><a class="zoom-svg-instagram-stroke" href="' . $link . '" rel="noopener nofollow" target="_blank" title="' . $alt . '"></a></div>
    					<a class="zoom-instagram-link" data-src="' . $src . '" data-mfp-src="' . $media_id . '" href="' . $link . '" target="_blank" rel="noopener nofollow" title="' . $alt . '"></a>
    					</div>';
                    }
				} else {
					$output .= '<a class="zoom-instagram-link" data-src="' . $src . '" data-mfp-src="' . $media_id . '" href="' . $link . '" target="_blank" rel="noopener nofollow" title="' . $alt . '">';

					if ( ( $show_media_type_icons || $show_media_type_icons_on_hover ) && ! empty( $type ) ) {
						$output .= '<svg class="svg-icon" shape-rendering="geometricPrecision"><use xlink:href="' . esc_url( $svg_icons ) . '#' . $type . '"></use></svg>';
					}

					$output .= '</a>';
				}

				$output .= '</div></li>';

				if ( ++ $count === $amount ) {
					break;
				}
			}
		}

		return $output;
	}

	/**
	 * Returns the lightbox markup for the given feed items.
	 *
	 * @param  array  $items    The items to generate the markup for.
	 * @param  int    $user_id  The ID of the user to disaply in the user info area.
	 * @return string           The lightbox markup for the given feed items, empty string otherwise.
	 */
	public static function lightbox_items_html( $items, $user_id ) {
		$output = '';

		if ( ! empty( $items ) && is_array( $items ) ) {
			$user = get_post( $user_id );

			if ( $user instanceof WP_Post ) {
				$amount = count( $items );
				$count = 0;
				$user_name = get_the_title( $user );
				$user_name_display = sprintf( '@%s', $user_name );
				$user_image = get_the_post_thumbnail_url( $user, 'thumbnail' ) ?: plugin_dir_url( __FILE__ ) . 'dist/images/backend/user-avatar.jpg';

				foreach ( $items as $item ) {
					$count++;
					$link     = isset( $item['link'] ) ? $item['link'] : '';
					$src      = isset( $item['original-image-url'] ) ? $item['original-image-url'] : '';
					$media_id = isset( $item['image-id'] ) ? $item['image-id'] : '';
					$alt      = isset( $item['image-caption'] ) ? esc_attr( $item['image-caption'] ) : '';
					$typ      = isset( $item['type'] ) ? strtolower( $item['type'] ) : 'image';
					$type     = in_array( $typ, array( 'video', 'carousel_album' ) ) ? $typ : false;
					$is_album = 'carousel_album' == $type;
					$is_video = 'video' == $type;
					$children = $is_album && isset( $item['children'] ) && is_object( $item['children'] ) && isset( $item['children']->data ) ? $item['children']->data : false;

					$output .= '<div data-uid="' . $media_id . '" class="swiper-slide wpz-insta-lightbox-item"><div class="wpz-insta-lightbox"><div class="image-wrapper">';

					if ( $is_album && false !== $children ) {
						$output .= '<div class="swiper-container"><div class="swiper-wrapper wpz-insta-album-images">';

						foreach ( $children as $child ) {
							$child_type = property_exists( $child, 'media_type' ) && in_array( $child->media_type, array( 'VIDEO', 'CAROUSEL_ALBUM' ) ) ? strtolower( $child->media_type ) : 'image';
							$thumb = 'video' == $child_type && property_exists( $child, 'thumbnail_url' ) ? strtolower( $child->thumbnail_url ) : '';

							$output .= '<div class="swiper-slide wpz-insta-album-image" data-media-type="' . esc_attr( $child_type ) . '">';

							if ( 'video' == $child_type ) {
								$output .= '<video controls preload="metadata" poster="' . esc_attr( $thumb ) . '"><source src="' . esc_url( $child->media_url ) . '" type="video/mp4"/>' . esc_html( $alt ) . '</video>';
							} else {
								$output .= '<img class="wpzoom-swiper-image" src="' . esc_url( $child->media_url ) . '" alt="' . esc_attr( $alt ) . '"/>';
							}

							$output .= '</div>';
						}

						$output .= '</div><div class="swiper-pagination"></div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div>';
					} else {
						$output .= '<img class="wpzoom-swiper-image" src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '"/>';
					}

					$output .= '</div>
					<div class="details-wrapper">
					<div class="wpz-insta-header">
						<div class="wpz-insta-avatar">
							<img src="' . esc_url( $user_image ) . '" alt="' . esc_attr( $user_name_display ) . '" width="42" height="42"/>
						</div>
						<div class="wpz-insta-buttons">
							<div class="wpz-insta-username">
								<a rel="noopener" target="_blank" href="' . sprintf( 'https://instagram.com/%s', esc_attr( $user_name ) ) . '">' . esc_html( $user_name_display ) . '</a>
							</div>
							<div>&bull;</div>
							<div class="wpz-insta-follow">
								<a target="_blank" rel="noopener"
								href="' . sprintf( 'https://instagram.com/%s?ref=badge', esc_attr( $user_name ) ) . '">
									' . __( 'Follow', 'wpzoom-instagram-widget' ) . '
								</a>
							</div>
						</div>
					</div>';

					if ( ! empty( $item['image-caption'] ) ) {
						$output .= '<div class="wpz-insta-caption">' . self::filter_caption( $item['image-caption'] ) . '</div>';
					}

					if ( ! empty( $item['timestamp'] ) ) {
						$output .= '<div class="wpz-insta-date">' . sprintf( __( '%s ago' ), human_time_diff( strtotime( $item['timestamp'] ) ) ) . '</div>';
					}

					$output .= '<div class="view-post">
					<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener"><span class="dashicons dashicons-instagram"></span>' . __( 'View on Instagram', 'wpzoom-instagram-widget' ) . '</a>
					<span class="delimiter">|</span>
					<div class="wpz-insta-pagination">' . sprintf( '%d/%d', $count, $amount ) . '</div>
					</div></div></div></div>';
				}
			}
		}

		return $output;
	}

	/**
	 * Return errors if widget is misconfigured and current user can manage options (plugin settings).
	 *
	 * @return void
	 */
	protected function get_errors( $errors ) {
		$output = '';

		if ( current_user_can( 'edit_theme_options' ) ) {
			$output .= sprintf(
				'<p>%s <strong><a href="%s" target="_blank">%s</a></strong> %s</p>',
				__( 'Instagram Widget misconfigured or your Access Token <strong>expired</strong>. Please check', 'instagram-widget-by-wpzoom' ),
				admin_url( 'edit.php?post_type=wpz-insta_user' ),
				__( 'Instagram Settings Page', 'instagram-widget-by-wpzoom' ),
				__( 'and re-connect your account.', 'instagram-widget-by-wpzoom' )
			);

			if ( ! empty( $errors ) ) {
				$output .= '<ul>';

				foreach ( $errors as $error ) {
					$output .= '<li>' . esc_html( $error ) . '</li>';
				}

				$output .= '</ul>';
			}
		} else {
			$output .= '&#8230;';
		}

		return $output;
	}

	/**
	 * Returns the CSS markup for a feed configured with the given arguments.
	 *
	 * @param  array  $args The arguments to define how to return the feed CSS.
	 * @return string
	 */
	public function style_content( array $args ) {
		$output                 = '';
		$feed_id                = isset( $args['feed-id'] ) ? ".feed-" . $args['feed-id'] : "";
		$raw_layout             = isset( $args['layout'] ) ? intval( $args['layout'] ) : 0;
		$layout                 = $this->is_pro ? $raw_layout : ( $raw_layout > 1 ? 0 : $raw_layout );
		$col_num                = isset( $args['col-num'] ) && intval( $args['col-num'] ) !== 3 ? intval( $args['col-num'] ) : 3;
		$spacing_between        = isset( $args['spacing-between'] ) && intval( $args['spacing-between'] ) > -1 ? intval( $args['spacing-between'] ) : -1;
		$spacing_between_suffix = $this->get_suffix( isset( $args['spacing-between-suffix'] ) ? intval( $args['spacing-between-suffix'] ) : 0 );
		$button_bg              = isset( $args['view-button-bg-color'] ) ? $this->validate_color( $args['view-button-bg-color'] ) : '';
        $loadmore_bg            = isset( $args['load-more-color'] ) ? $this->validate_color( $args['load-more-color'] ) : '';
		$bg_color               = isset( $args['bg-color'] ) ? $this->validate_color( $args['bg-color'] ) : '';
		$border_radius          = isset( $args['border-radius'] ) ? ( intval( $args['border-radius'] ) ?: -1 ) : -1;
		$border_radius_suffix   = $this->get_suffix( isset( $args['border-radius-suffix'] ) ? intval( $args['border-radius-suffix'] ) : 0 );
		$spacing_around         = isset( $args['spacing-around'] ) ? ( intval( $args['spacing-around'] ) ?: -1 ) : -1;
		$spacing_around_suffix  = $this->get_suffix( isset( $args['spacing-around-suffix'] ) ? intval( $args['spacing-around-suffix'] ) : 0 );
		$font_size              = isset( $args['font-size'] ) ? ( intval( $args['font-size'] ) ?: -1 ) : -1;
		$font_size_suffix       = $this->get_suffix( isset( $args['font-size-suffix'] ) ? intval( $args['font-size-suffix'] ) : 0 );
		$image_width            = isset( $args['image-width'] ) ? ( intval( $args['image-width'] ) ?: 240 ) : 240;
		$image_width_suffix     = $this->get_suffix( isset( $args['image-width-suffix'] ) ? intval( $args['image-width-suffix'] ) : 0 );
		$hover_likes            = isset( $args['hover-likes'] ) ? boolval( $args['hover-likes'] ) : true;
		$hover_link             = isset( $args['hover-link'] ) ? boolval( $args['hover-link'] ) : true;
		$hover_caption          = isset( $args['hover-caption'] ) ? boolval( $args['hover-caption'] ) : false;
		$hover_username         = isset( $args['hover-username'] ) ? boolval( $args['hover-username'] ) : false;
		$hover_date             = isset( $args['hover-date'] ) ? boolval( $args['hover-date'] ) : false;
		$hover_text_color       = isset( $args['hover-text-color'] ) ? $this->validate_color( $args['hover-text-color'] ) : '';
		$hover_bg_color         = isset( $args['hover-bg-color'] ) ? $this->validate_color( $args['hover-bg-color'] ) : '';

		if ( $font_size > -1 || ! empty( $bg_color ) || $spacing_around > -1 ) {
			$output .= ".zoom-instagram" . $feed_id . " {\n";

			if ( $font_size > -1 ) {
				$output .= "\tfont-size: " . $font_size . $font_size_suffix . " !important;\n";
			}

			if ( ! empty( $bg_color ) ) {
				$output .= "\tbackground-color: " . $bg_color . " !important;\n";
			}

			if ( $spacing_around > -1 ) {
				$output .= "\tpadding: " . $spacing_around . $spacing_around_suffix . " !important;\n";
			}

			$output .= "}\n\n";
		}

		if ( 3 !== $col_num || $spacing_between > -1 ) {
			$output .= ".zoom-instagram" . $feed_id . " .zoom-instagram-widget__items {\n";

			if ( 3 !== $col_num ) {
				$output .= "\tgrid-template-columns: repeat(" . $col_num . ", 1fr) !important;\n";
			}

			if ( $spacing_between > -1 ) {
				$output .= "\tgap: " . $spacing_between . $spacing_between_suffix . " !important;\n";
			}

			$output .= "}\n\n";
		}

		if ( $image_width > -1 && 1 === $layout ) {
			$output .= ".zoom-instagram" . $feed_id . " .zoom-instagram-widget__items img {\n";
			$output .= "\twidth: " . $image_width . $image_width_suffix . " !important;";
			$output .= "}\n\n";
		}

		if ( $border_radius > -1 ) {
			$output .= ".zoom-instagram" . $feed_id . " .zoom-instagram-widget__item .zoom-instagram-widget__item-inner-wrap {\n\tborder-radius: " . $border_radius . $border_radius_suffix . " !important;\n}\n\n";
		}

		if ( '' != $button_bg ) {
			$output .= ".zoom-instagram" . $feed_id . " .wpz-insta-view-on-insta-button {\n\tbackground-color: " . $button_bg . " !important;\n}";
		}

        if ( '' != $loadmore_bg ) {
            $output .= ".zoom-instagram" . $feed_id . " .wpzinsta-pro-load-more button[type=submit] {\n\tbackground-color: " . $loadmore_bg . " !important;\n}";
        }

		return $output;
	}

	/**
	 * Outputs the CSS styles for the feed with the given ID.
	 *
	 * @param  int  $feed_id The ID of the feed to output the styles for.
	 * @param  bool $echo    Whether to output the styles (default) or return them.
	 * @return void
	 */
	public function output_styles( int $feed_id, bool $echo = true ) {
		$output = '';

		if ( $feed_id > -1 ) {
			$feed = get_post( $feed_id, OBJECT, 'display' );

			if ( null !== $feed && $feed instanceof WP_Post ) {
				$args = WPZOOM_Instagram_Widget_Settings::get_all_feed_settings_values( $feed_id );
				$args['feed-id'] = $feed_id;
				$output = $this->style_content( $args );
			}
		}

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Outputs the CSS styles for the preview of a feed configured with the given arguments.
	 *
	 * @param  array $args The arguments to define how to output the feed preview CSS.
	 * @param  bool  $echo Whether to output the preview (default) or return it.
	 * @return void
	 */
	public function output_preview_styles( array $args, bool $echo = true ) {
		$output = $this->style_content( $args );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Returns a suffix string (e.g. px, em, etc) from the given index.
	 *
	 * @param  int    $index The index to get the suffix value for.
	 * @return string        The suffix value as a string.
	 */
	public function get_suffix( int $index ) {
		return 2 === $index ? '%' : ( 1 === $index ? 'em' : 'px' );
	}

	/**
	 * Returns a validated color value.
	 *
	 * @param  string $color The raw color string to validate.
	 * @return string        The validated color string.
	 */
	function validate_color( string $color ) {
		return preg_match( '/^(\#[\da-f]{3}|\#[\da-f]{6}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/i', $color ) ? $color : '';
	}

	/**
	 * Formats a number for display.
	 *
	 * @param  int    $num The number to format.
	 * @return string      The formatted number in a string.
	 */
	public static function format_number( int $num ) {
		if ( $num < 10000 ) {
			return number_format( $num );
		}

		$units = array( '', 'k', 'm', 'b', 't' );
		for ( $i = 0; $num >= 1000; $i ++ ) {
			$num /= 1000;
		}

		return round( $num, 1 ) . $units[ $i ];
	}

	/**
	 * Convert $url to file path.
	 *
	 * @param  string          $url
	 * @return string|string[]
	 */
	public static function convert_url_to_path( string $url ) {
		return str_replace(
			wp_get_upload_dir()['baseurl'],
			wp_get_upload_dir()['basedir'],
			$url
		);
	}

	/**
	 * Convert attachment $url to file path.
	 *
	 * @param  string       $url
	 * @return string|false
	 */
	public static function attachment_url_to_path( string $url ) {
		$parsed_url = parse_url( $url );

		if ( empty( $parsed_url['path'] ) ) {
			return false;
		}

		$file = ABSPATH . ltrim( $parsed_url['path'], '/' );

		if ( file_exists( $file ) ) {
			return $file;
		}

		return false;
	}

	/**
	 * Sanitizes and prepares caption content for display.
	 * 
	 * @param  string $caption The raw caption text to filter.
	 * @return string          The filtered caption text.
	 */
	public static function filter_caption( string $caption = '' ) {
		if ( ! empty( $caption ) ) {
			$filters = array(
				'wp_kses_post',
				'autoembed',
				'wptexturize',
				'wpautop',
				'wp_filter_content_tags',
				'capital_P_dangit',
				'convert_chars',
				'convert_smilies',
				'force_balance_tags',
			);

			foreach ( $filters as $filter ) {
				$caption = apply_filters( $filter, $caption );
			}
		}

		return trim( $caption );
	}
}
