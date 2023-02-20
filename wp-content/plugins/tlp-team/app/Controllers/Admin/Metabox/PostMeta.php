<?php
/**
 * Admin Post Metabox Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Metabox;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Post Metabox Class.
 */
class PostMeta {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'add_meta_boxes', [ $this, 'single_team_meta_boxes' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		$taxo = [
			'team_department',
			'team_designation',
		];

		foreach ( $taxo as $tx ) {
			add_action( 'created_' . $tx, [ &$this, 'save_team_department_meta_data' ], 10, 1 );
		}

		add_action( 'save_post', [ $this, 'save_team_meta_data' ], 10, 3 );
	}

	function save_team_department_meta_data( $term_id ) {
		$current_order = absint( get_term_meta( $term_id, 'team_department_order', true ) );
		$current_order = ( $current_order ? $current_order : 0 );
		update_term_meta( $term_id, '_rt_order', $current_order );
	}

	function admin_enqueue_scripts() {
		global $pagenow, $typenow;
		// validate page
		if ( ! in_array( $pagenow, [ 'post.php', 'post-new.php', 'edit.php' ] ) ) {
			return;
		}

		if ( $typenow != rttlp_team()->post_type ) {
			return;
		}

		// scripts
		wp_enqueue_script(
			[
				'jquery',
				'jquery-ui-sortable',
				'ace-code-highlighter-js',
				'ace-mode-js',
				'tlp-team-admin-js',
				'tlp-admin-taxonomy',
			]
		);

		// styles
		wp_enqueue_style( 'tlp-team-admin-css' );

		wp_localize_script(
			'tlp-team-admin-js',
			'ttp',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonceID' => Fns::nonceID(),
				'nonce'   => wp_create_nonce( Fns::nonceText() ),
			]
		);
	}

	function single_team_meta_boxes() {
		add_meta_box(
			'tlp_team_meta',
			__( 'Member Info', 'tlp-team' ),
			[ $this, 'tlp_team_meta' ],
			'team',
			'normal',
			'high'
		);

		add_meta_box(
			'tlp_team_meta_social',
			__( 'Member Social Link', 'tlp-team' ),
			[ $this, 'tlp_team_meta_social' ],
			'team',
			'normal',
			'high'
		);
	}

	function tlp_team_meta( $post ) {

		wp_nonce_field( Fns::nonceText(), Fns::nonceID() );
		$html  = null;
		$html .= '<div class="member-field-holder">';
		$html .= Fns::rtFieldGenerator( Options::teamMemberInfoField() );
		$html .= '</div>';

		Fns::print_html( $html, true );
	}

	function tlp_team_meta_social( $post ) { ?>
		<div class="member-field-holder">
			<div id="metaSocialHolder">
				<?php
				$s = ( get_post_meta(
					$post->ID,
					'social',
					true
				) ? get_post_meta( $post->ID, 'social', true ) : [] );

				if ( ! empty( $s ) ) {

					foreach ( $s as $count => $val ) {
						?>
						<div class="tlp-field-holder socialLink" id="slh-<?php echo absint( $count ); ?>">
							<div class="tlp-label">
								<select name="social[<?php echo absint( $count ); ?>][id]">
									<?php
									foreach ( Options::socialLink() as $id => $name ) {
										$select = ( $val['id'] == $id ) ? 'selected' : null;
										echo '<option value="' . esc_attr( $id ) . '" ' . esc_attr( $select ) . '>' . esc_html( $name ) . '</option>';
									}
									?>
								</select>
							</div>

							<div class="tlp-field">
								<input type="text" name="social[<?php echo absint( $count ); ?>][url]" class="tlpfield" value="<?php echo esc_url( $val['url'] ); ?>">
								<span data-id="<?php echo absint( $count ); ?>" class="sRemove dashicons dashicons-trash"></span>
								<span class="dashicons dashicons-admin-settings"></span>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<h2 class="social-h2">
				<span class="button button-primary" id="addNewSocial"><?php esc_html_e( 'Add new', 'tlp-team' ); ?></span>
			</h2>
		</div>
		<?php
	}

	function save_team_meta_data( $post_id, $post, $update ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! Fns::verifyNonce() ) {
			return $post_id;
		}

		if ( rttlp_team()->post_type != $post->post_type ) {
			return $post_id;
		}

		$mates   = Options::teamMemberInfoField();

		if ( is_array( $mates ) && ! empty( $mates ) ) {
			foreach ( $mates as $metaKey => $field ) {
				$value = ! empty( $_REQUEST[ $metaKey ] ) ? Fns::sanitize( $field, $_REQUEST[ $metaKey ] ) : null;

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

		if ( ! empty( $_REQUEST['skill'] ) ) {
			$sK = sanitize_text_field( serialize( array_filter( $_REQUEST['skill'] ) ) );
			update_post_meta( $post_id, 'skill', $sK );
		} else {
			delete_post_meta( $post_id, 'skill' );
		}

		if ( isset( $_REQUEST['social'] ) ) {
			$s = sanitize_text_field( serialize( array_filter( $_REQUEST['social'] ) ) );
			update_post_meta( $post_id, 'social', unserialize( $s ) );
		} else {
			update_post_meta( $post_id, 'social', '' );
		}

		delete_post_meta( $post->ID, 'tlp_team_gallery' );
		if ( isset( $_POST['tlp_team_gallery'] ) && ! empty( $_POST['tlp_team_gallery'] ) ) {
			$image_ids = array_unique( array_map( 'absint', $_POST['tlp_team_gallery'] ) );
			foreach ( $image_ids as $id ) {
				add_post_meta( $post->ID, 'tlp_team_gallery', $id );
			}
		}

	}

	function tlp_team_script() {
		global $post_type;
		if ( $post_type == rttlp_team()->post_type ) {

			wp_enqueue_style( 'tlp-team-admin-css' );
			wp_enqueue_script( 'tlp-team-admin-js' );

			$nonce = wp_create_nonce( Fns::nonceText() );
			wp_localize_script(
				'tlp-team-admin-js',
				'ttp',
				[
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonceID' => Fns::nonceID(),
					'nonce'   => $nonce,
				]
			);
		}
	}
}
