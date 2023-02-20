<?php
defined( 'ABSPATH' ) || die();

$etn_start_date   = get_post_meta( get_the_ID(), 'etn_start_date', true );
$event_start_date = !empty( $event_options["date_format"] ) ? date_i18n( $date_options[$event_options["date_format"]], strtotime( $etn_start_date ) ) : date_i18n( get_option( "date_format" ), strtotime( $etn_start_date ) );
?>
<div class="etn-event-footer">

    <?php do_action( 'etn_before_event_archive_footer_content' );?>

    <div class="etn-event-date">
        <i class="far fa-calendar-alt"></i>
        <?php echo esc_html( $event_start_date ); ?>
    </div>

    <?php do_action( 'etn_after_event_archive_footer_content' );?>

</div>