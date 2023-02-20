<?php
use Etn\Utils\Helper;
?>

<div class="etn-widget etn-ticket-widget">
    <?php
    if( $etn_left_tickets > 0 ) {
        ?>
        <h4 class="etn-widget-title etn-title etn-form-title">
            <?php
            $attendee_form_title = apply_filters( 'etn_event_purchase_form_title', esc_html__( "Registration Form", "eventin" ) );
            echo esc_html( $attendee_form_title );
            ?>
        </h4>
        <?php

        $settings            = Helper::get_settings();
        $attendee_reg_enable = !empty( $settings["attendee_registration"] ) ? true : false;

        if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/form-template.php' ) ) {
            $purchase_form_template = get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/form-template.php';
        } elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/form-template.php' ) ) {
            $purchase_form_template = get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/purchase-form/template/form-template.php';
        } else {
            $purchase_form_template = \Wpeventin::templates_dir() . "event/purchase-form/template/form-template.php";
        }

        $form_template = apply_filters( "etn/purchase_form_template", $purchase_form_template );

        if ( file_exists( $form_template ) ) {
            include $form_template;
        }

    } else {
        ?>
        <h6><?php echo esc_html__( 'No Tickets Available!!', "eventin" ); ?></h6>
        <?php
    }

    // show if this is a zoom event
    if ( isset( $is_zoom_event ) && "on" == $is_zoom_event ) {
        ?>
        <div class="etn-zoom-event-notice">
            <?php echo esc_html__( "[Note: This event will be held on zoom. Attendee will get zoom meeting URL through email]", "eventin" ); ?>
        </div>
        <?php
    }

    ?>
</div>