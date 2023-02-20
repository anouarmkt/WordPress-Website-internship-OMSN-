<?php

namespace Etn\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * etn notice class.
 * Handles dynamically notices for lazy developers.
 *
 * @author etn team: alpha omega sigma
 * @since 1.0.0
 */

class Notice {

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_footer', [$this, 'enqueue_scripts'], 9999 );
        add_action( 'wp_ajax_etn_notices', [$this, 'dismiss'] );
    }

    /**
     * Dismiss Notice.
     *
     * @since 1.0.0
     * @return void
     */
    public function dismiss() {
        $post_arr       = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        if ( isset( $post_arr['id'] ) ) {
            $id = sanitize_key( $post_arr['id'] );
        } else {
            $id = '';
        }

        if ( isset( $post_arr['time'] ) ) {
            $time = sanitize_text_field( $post_arr['time'] );
        } else {
            $time = '';
        }

        if ( isset( $post_arr['meta'] ) ) {
            $meta = sanitize_meta( $post_arr['meta'] );
        } else {
            $meta = '';
        }

        // Valid inputs?
        if (!empty($id)) {

            if ('user' === $meta) {
                update_user_meta( get_current_user_id(), $id, true );
            } else {
                set_transient( $id, true, $time );
            }

            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Enqueue Scripts.
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        ?>
			<script>
                jQuery(document).ready(function ($) {
                    $( '.etn-notice.is-dismissible' ).on( 'click', '.notice-dismiss', function() {
                        _this 		= $( this ).parents( '.etn-active-notice' );
                        var id 	= _this.attr( 'id' ) || '';
                        var time 	= _this.attr( 'dismissible-time' ) || '';
                        var meta 	= _this.attr( 'dismissible-meta' ) || '';

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action 	: 'etn_notices',
                                id 		: id,
                                meta 	: meta,
                                time 	: time,
                            },
                        });
                    });
                });
			</script>
		<?php
    }

    /**
     * Show Notices
     *
     * @since 1.0.0
     * @return void
     */
    public static function push( $notice ) {

        $defaults = [
            'id'               => '',
            'type'             => 'info',
            'show_if'          => true,
            'message'          => '',
            'class'            => 'etn-active-notice',
            'dismissible'      => false,
            'btn'              => [],
            'dismissible-meta' => 'user',
            'dismissible-time' => WEEK_IN_SECONDS,
            'data'             => '',
        ];

        $notice = wp_parse_args( $notice, $defaults );

        $classes = ['etn-notice', 'notice'];

        $classes[] = $notice['class'];
        if ( isset( $notice['type'] ) ) {
            $classes[] = 'notice-' . $notice['type'];
        }

		// Is notice dismissible?
        if ( true === $notice['dismissible'] ) {
            $classes[] = 'is-dismissible';

            // Dismissable time.
            $notice['data'] = ' dismissible-time=' . esc_attr( $notice['dismissible-time'] ) . ' ';
        }

        // Notice ID.
        $notice_id    = 'etn-sites-notice-id-' . $notice['id'];
        $notice['id'] = $notice_id;

        if ( !isset( $notice['id'] ) ) {
            $notice_id    = 'etn-sites-notice-id-' . $notice['id'];
            $notice['id'] = $notice_id;
        } else {
            $notice_id = $notice['id'];
        }

        $notice['classes'] = implode( ' ', $classes );

        // User meta.
        $notice['data'] .= ' dismissible-meta=' . esc_attr( $notice['dismissible-meta'] ) . ' ';

        if ( 'user' === $notice['dismissible-meta'] ) {
            $expired = get_user_meta( get_current_user_id(), $notice_id, true );
        } elseif ( 'transient' === $notice['dismissible-meta'] ) {
            $expired = get_transient( $notice_id );
        }
        
        // Notice visible after transient expire.
        if ( isset( $notice['show_if'] ) ) {
            if ( true === $notice['show_if'] ) {
                // Is transient expired?
                if ( false === $expired || empty( $expired ) ) {
                    self::markup( $notice );
                }
            }
        } else {
            self::markup( $notice );
        }
    }


    /**
     * Markup Notice.
     *
     * @since 1.0.0
     * @param  array $notice Notice markup.
     * @return void
     */
    public static function markup( $notice = [] ) {
        ?>
		<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>" <?php echo Helper::render( $notice['data'] ); ?>>
			<p>
				<?php echo Helper::kses( $notice['message'] ); ?>
			</p>
			<?php if ( !empty( $notice['btn'] ) ): ?>
				<p>
					<a href="<?php echo esc_url( $notice['btn']['url'] ); ?>" class="button-primary"><?php echo esc_html( $notice['btn']['label'] ); ?></a>
				</p>
			<?php endif;?>
		</div>
		<?php
	}

}

new Notice();
