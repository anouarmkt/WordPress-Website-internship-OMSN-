<?php
$text_domain = "eventin";

if ( is_array( $zoom_meeting ) ) {

    if ( $link_only == 'no' ) {
        $type = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->meeting_type_list();

        if ( isset( $zoom_meeting['start_time'] ) ) {
            $time_zone            = isset( $zoom_meeting['timezone'] ) ? $zoom_meeting['timezone'] : 'Asia/Dhaka';
            $converted_start_time = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->convert_meeting_date_time( $zoom_meeting['start_time'] );
        }
        ?>
        <div class="meeting-wrapper">
            <div class="meeting-row">
                <div class="meeting-info">
                    <h4 class="meeting-title">
                        <?php echo esc_html( $zoom_types[ $zoom_meeting['type'] ] ) . esc_html__( ' info', "eventin" ); ?>
                    </h4>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Meeting id', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['id'] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Topic', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['topic'] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Start time', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['start_time'] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Type', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $type[ absint( $zoom_meeting['type'] ) ] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Status', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['status'] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Duration', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['duration'] ) . esc_html__( " minutes", "eventin" ); ; ?> </p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Time zone', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <p><?php echo esc_html( $zoom_meeting['timezone'] ) ?></p>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                <p><?php echo esc_html__( 'Start url', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <a href="<?php echo esc_url( $zoom_meeting['start_url'] ) ?>"><?php echo esc_html__( 'Start url', "eventin" ) ?></a>
                </div>
            </div><!-- row end -->

            <div class="meeting-row">
                <div class="meeting-info">
                    <p><?php echo esc_html__( 'Join url', "eventin" ) ?></p>
                </div>
                <div class="meeting-info info-right">
                    <a href="<?php echo esc_url( $zoom_meeting['join_url'] ) ?>"><?php echo esc_html__( 'Join url', "eventin" ) ?></a>
                </div>
            </div><!-- row end -->
        </div><!-- mettin-wrapper end -->
        <?php
    }

    if ( $link_only == 'yes' ) {
        ?>
        <div class="meeting-wrapper">
            <div class="meeting-row">
                <div class="meeting-info">
                    <a href="<?php echo esc_url( $zoom_meeting['join_url'] ); ?>">
                        <?php
                        echo esc_html__( 'Join url', "eventin" );
                        ?>
                    </a>
                </div>
            </div><!-- row end -->
        </div>
        <?php
    }

} else {
    ?>
    <div class="meeting-wrapper">
        <div class="meeting-row">
            <div class="meeting-info">
                <h4 class="meeting-title"><?php echo esc_html__( 'No data found.', "eventin" ); ?></h4>
            </div>
        </div><!-- row end -->
    </div>
    <?php
}
