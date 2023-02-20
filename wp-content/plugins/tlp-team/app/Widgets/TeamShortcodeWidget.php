<?php
/**
 * Team Shortcode Widget.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets;

use WP_Widget;
use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Team Shortcode Widget.
 */
class TeamShortcodeWidget extends WP_Widget {

	function __construct() {

		$widget_ops = [
			'classname'   => 'widget_tlpTeam',
			'description' => esc_html__( 'Display the team member using TLP Team plugin.', 'tlp-team' ),
		];
		parent::__construct( 'widget_tlpTeam_sc', esc_html__( 'Team Widget (Shortcodes List)', 'tlp-team' ), $widget_ops );

	}

	function widget( $args, $instance ) {
		extract( $args );
		$id = ( ! empty( $instance['id'] ) ? absint( $instance['id'] ) : null );

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters(
				'widget_title',
				! empty( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'TLP Team', 'tlp-team' )
			) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! empty( $id ) ) {
			echo do_shortcode( '[tlpteam id="' . absint( $id ) . '" ]' );
		}

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	function form( $instance ) {

		$scList   = Fns::getTTPShortcodeList();
		$defaults = [
			'title' => esc_html__( 'TLP Team', 'tlp-team' ),
			'id'    => null,
		];
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"> <?php esc_html_e( 'Title:', 'tlp-team' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'Select team shortcode', 'tlp-team' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>">
				<option value="">Select one</option>
				<?php
				if ( ! empty( $scList ) ) {
					foreach ( $scList as $scId => $sc ) {
						$selected = ( $scId == $instance['id'] ? 'selected' : null );
						echo '<option value="' . absint( $scId ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $sc ) . '</option>';
					}
				}
				?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance          = [];
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['id']    = ( ! empty( $new_instance['id'] ) ) ? absint( $new_instance['id'] ) : '';

		return $instance;
	}


}
