<?php

require_once \Wpeventin::plugin_dir() . 'utils/tfpdf.php';
$pdf_file_name  = strtolower( str_replace( " ", "-", $attendee_name ) );
$pdf            = new \Etn\Utils\tFPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu','', 'DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',18);

do_action( 'etn\pdf\before_main_details', $attendee_id, $event_id, $pdf );

// html
$pdf->Cell( 80, 15, esc_html__( "Event Details:", "eventin" ), 0, 0, 'L', 0 );
$pdf->ln();
$pdf->SetFont('DejaVu','',14);
$pdf->Cell( 40, 8, esc_html__( "Event Name:", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $event_name, 0, 1, 'L', 0 );


$pdf->Cell( 40, 8, esc_html__( "Date :", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $date, 0, 1, 'L', 0 );
$pdf->Cell( 40, 8, esc_html__( "Time :", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $time, 0, 1, 'L', 0 );

$pdf->Cell( 40, 8, esc_html__( "Location:", "eventin" ), 0, 0, 'L', 0 );
$pdf->Multicell( 100, 8, $event_location, 0, 'L', 0 );
$pdf->Cell( 40, 8, esc_html__( "Ticket Type:", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $ticket_name, 0, 1, 'L', 0 );
$pdf->Cell( 40, 8, esc_html__( "Ticket Price:", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $ticket_price, 0, 1, 'L', 0 );


$pdf->ln();
$pdf->ln();
$pdf->Cell( 40, 8, '-----------------------------------------------------------------------------------------------------------------', 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, '', 0, 1, 'L', 0 );
$pdf->ln();
$pdf->ln();

$pdf->SetFont('DejaVu','',18);
$pdf->Cell( 80, 15, esc_html__( "Attendee Details:", "eventin" ), 0, 0, 'L', 0 );

$pdf->SetFont('DejaVu','',14);
$pdf->ln();
$pdf->Cell( 40, 8, esc_html__( "Name:", "eventin" ), 0, 0, 'L', 0 );
$pdf->Cell( 80, 8, $attendee_name, 0, 1, 'L', 0 );

if( $include_email  ){
    $pdf->Cell( 40, 8, esc_html__( "Email:", "eventin" ), 0, 0, 'L', 0 );
    $pdf->Cell( 80, 8, $attendee_email, 0, 1, 'L', 0 );
}
if( $include_phone  ){
    $pdf->Cell( 40, 8, esc_html__( "Phone:", "eventin" ), 0, 0, 'L', 0 );
    $pdf->Cell( 80, 8, $attendee_phone, 0, 1, 'L', 0 );
}

do_action( 'etn\pdf\after_main_details', $attendee_id, $event_id, $pdf );

$pdf->Output( "D", $pdf_file_name . ".pdf" );