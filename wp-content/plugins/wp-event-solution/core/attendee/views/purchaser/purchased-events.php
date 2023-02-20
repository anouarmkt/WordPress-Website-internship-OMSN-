<?php
defined( 'ABSPATH' ) || exit;

$header_columns = [
	'order' 	 		=> esc_html__( 'Order', 'eventin-pro' ),
	'event' 	 		=> esc_html__( 'Event', 'eventin-pro' ),
	'event_start_date' 	=> esc_html__( 'Event Start Date', 'eventin-pro' ),
	'order_status' 		=> esc_html__( 'Order Status', 'eventin-pro' ),
	'action' 	 		=> esc_html__( 'Action', 'eventin-pro' ),
]
?>

<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
	<thead>
		<tr>
			<?php foreach ( $header_columns as $column_id => $column_name ) : ?>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ( $user_events as $order_id => $user_events ) {
			foreach ( $user_events as $event_id => $user_event ) {
				$user_event = (object) $user_event;
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $user_event->order_status ); ?> order">
					<?php foreach ( $header_columns as $column_id => $column_name ) : ?>
					<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php if ( 'order' === $column_id ) : ?>
							<a href="<?php echo esc_url( $user_event->order_url ); ?>">
								<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $user_event->order_id ); ?>
							</a>

						<?php elseif ( 'event' === $column_id ) : ?>
							<?php 
								echo '<a href="'.get_permalink( $user_event->event_id ).'" target="_blank">' . esc_html( $user_event->event_name ) . '</a>';
							?>

						<?php elseif ( 'event_start_date' === $column_id ) : ?>
							<?php echo esc_html( get_post_meta($user_event->event_id,'etn_start_date',true).' '.get_post_meta($user_event->event_id,'etn_start_time',true) ); ?>
									
						<?php elseif ( 'order_status' === $column_id ) : ?>
							<?php echo esc_html( ucfirst( $user_event->order_status ) ); ?>
					
						<?php elseif ( 'action' === $column_id ) : ?>
							<?php
								echo '<a href="' . esc_url( $user_event->order_url ) . '" target="_blank" class="woocommerce-button button ">' . esc_html__( 'View', 'eventin' ) . '</a>';
							?>
						<?php endif; ?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
