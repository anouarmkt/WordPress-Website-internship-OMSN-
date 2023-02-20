<?php
/**
 * Taxonomy order view.
 */

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

$taxonomy_objects = Fns::rt_get_all_taxonomy_by_post_type();
?>

<div class="wrap">
	<h2><?php esc_html_e( 'Taxonomy Ordering', 'tlp-team' ); ?></h2>
	<?php
	if ( ! function_exists( 'get_term_meta' ) ) {
		?>
		<div class="update-message notice inline notice-error notice-alt">
			<p><?php esc_html_e( 'Please update your WordPress to 4.4.0 or latest version to use taxonomy order functionality.', 'tlp-team' ); ?></p>
		</div>
		<?php
	}
	?>
	<div class="ttp-taxonomy-wrapper">
		<label><?php esc_html_e( 'Select Taxonomy', 'tlp-team' ); ?></label>
		<select class="tlp-select" id="ttp-taxonomy">
			<option value=""><?php esc_html_e( 'Select one taxonomy', 'tlp-team' ); ?></option>
			<?php
			if ( ! empty( $taxonomy_objects ) ) {
				foreach ( $taxonomy_objects as $key => $taxonomy ) {
					echo '<option value=' . esc_attr( $key ) . '>' . esc_html( $taxonomy ) . '</option>';
				}
			}
			?>
		</select>
	</div>
	<div class="ordering-wrapper">
		<div id="term-wrapper">
			<p><?php esc_html_e( 'No taxonomy selected', 'tlp-team' ); ?></p>
		</div>
	</div>
</div>
