<?php
session_start();
include "config.php";
require("fpdf/fpdf.php");

$id = intval($_GET['id']);

$q = $conn->query("
SELECT b.*, h.hotel_name, r.room_type, r.room_price
FROM bookings b
LEFT JOIN hotels h ON b.hotel_id=h.id
LEFT JOIN rooms r ON b.room_id=r.room_id
WHERE b.id=$id
");

$b = $q->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();

// USE PNG LOGO
$logo = "logo.png";

if (file_exists($logo)) {
    $logoWidth = 80;      // resize properly
    $pageWidth = 210;    
    $x = ($pageWidth - $logoWidth) / 2;  // center alignment

    $pdf->Image($logo, $x, 10, $logoWidth);
}

$pdf->Ln(55);

$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,10,'RoyalStay Booking Invoice',0,1,'C');

$pdf->Ln(10);
$pdf->SetFont('Arial','',13);

$pdf->Cell(0,10,"Booking ID: ".$b['id'],0,1);
$pdf->Cell(0,10,"Hotel: ".$b['hotel_name'],0,1);
$pdf->Cell(0,10,"Room: ".$b['room_type'],0,1);
$pdf->Cell(0,10,"Price: Rs ".$b['room_price'],0,1);
$pdf->Cell(0,10,"Check-in: ".$b['check_in'],0,1);
$pdf->Cell(0,10,"Check-out: ".$b['check_out'],0,1);

$pdf->Ln(5);
$pdf->Cell(0,10,"Status: ".$b['status'],0,1);
$pdf->Cell(0,10,"Refund: ".$b['refund_status'],0,1);

$pdf->Output();
?>
