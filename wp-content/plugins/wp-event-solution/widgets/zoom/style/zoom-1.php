<?php
$meeting     			= $settings['meeting_cache'];
$zoom_meeting_data   	= empty( $meeting ) ? [] : json_decode( $meeting );

if ( is_object( $zoom_meeting_data ) && isset( $zoom_meeting_data->id ) ){
	$zoom_data 			= (object) \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->meeting_info( $zoom_meeting_data->id );
	
	if ( isset( $zoom_data->id ) ) {
		if ( empty( $zoom_data->type ) ) {
			$zoom_data 	= json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $zoom_meeting_data->id ) );
		}

	$type 				= \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->meeting_type_list();
	if ( isset( $zoom_data->start_time ) ) {
		$time_zone 			  = isset( $zoom_data->timezone ) ? $zoom_data->timezone : 'Asia/Dhaka' ;
		$converted_start_time = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->convert_meeting_date_time( $zoom_data->start_time );
	}
    ?>
	<div class="meeting-wrapper etn-zoom-details <?php echo esc_attr($zoom_style); ?>">
		<div class="meeting-row meeting-header">
			<div class="meeting-info">
				<h4 class="meeting-title">
					<?php echo esc_html( $zoom_types[ $zoom_data->type ] ) . esc_html__( ' info', "eventin" ); ?>
				</h4>
			</div>
		</div><!-- row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Type', "eventin" ) ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $type[ absint( $zoom_data->type ) ]) ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Meeting ID', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->id ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Topic', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->topic ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<!-- <div class="meeting-row">
			<div class="meeting-info">
				<p><?php // echo esc_html__( 'Meeting Status', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php // echo esc_html( $zoom_data->status ); ?></p>
			</div>
		</div> -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Start Time', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $converted_start_time ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Duration', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->duration ) . esc_html__( " minutes", "eventin" ); ; ?> </p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Time Zone', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->timezone ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Start URL ( Host can start meeting using this url )', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<a class="etn-zoom-btn" href="<?php echo esc_url( $zoom_data->start_url );?>"><?php echo esc_html__( 'Start Url', 'eventin' ); ?></a>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Join URL ( Users can join meeting using this url )', 'eventin' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<a target="_blank" class="etn-zoom-btn" href="<?php echo esc_url( $zoom_data->join_url );?>"  rel="noopener"><?php echo esc_html__( 'Join Url', 'eventin' ); ?></a>
			</div>
		</div><!-- meeting row end -->
	</div>
<?php } } else { ?>
    <div class="meeting-wrapper">
        <div class="meeting-row">
            <div class="meeting-info">
                <p><?php echo esc_html__( 'Something is wrong.', 'eventin' ); ?></p>
            </div>
        </div>
    </div>
<?php
}

