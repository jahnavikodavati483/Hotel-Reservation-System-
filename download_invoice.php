<?php
session_start();
include "config.php";
require("fpdf/fpdf.php");

if (!isset($_GET['id'])) { die("Invalid Request"); }

$id = intval($_GET['id']);

$q = $conn->query("
    SELECT b.*, h.hotel_name, r.room_type, r.room_price
    FROM bookings b
    LEFT JOIN hotels h ON b.hotel_id = h.id
    LEFT JOIN rooms r ON b.room_id = r.room_id
    WHERE b.id = $id
");

if ($q->num_rows == 0) { die("Booking Not Found"); }

$b = $q->fetch_assoc();

// Extract data
$invoice = "INV-" . $b['id'];
$hotel = $b['hotel_name'];
$room = $b['room_type'];
$checkin = $b['check_in'];
$checkout = $b['check_out'];
$guests = $b['guests'];
$price = $b['room_price'];
$name = $b['full_name'];
$email = $b['email'];
$phone = $b['phone'];

// ---------- AUTO QR GENERATION ----------
$qrText = "RoyalStay | Booking ID: $id | Hotel: $hotel | Check-in: $checkin";
$qrURL = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrText);

$qrPath = "fpdf/uploads/qr.png";
file_put_contents($qrPath, file_get_contents($qrURL));

// Signature + Stamp Paths
$logo = "fpdf/uploads/logo.png";
$stamp = "fpdf/uploads/stamp.png";
$sign1 = "fpdf/uploads/signature.png";
$sign2 = "fpdf/uploads/swetha.png";

// ---------- PDF BUILD ----------
$pdf = new FPDF();
$pdf->AddPage();

// HEADER
$pdf->SetFillColor(0, 102, 255);
$pdf->Rect(0, 0, 210, 30, "F");

if (file_exists($logo)) {
    $pdf->Image($logo, 10, 5, 20);
}

$pdf->SetTextColor(255,255,255);
$pdf->SetFont("Arial","B",20);
$pdf->Cell(0, 30, "RoyalStay Invoice", 0, 1, "C");

$pdf->SetTextColor(0,0,0);

// Booking Details
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,10,"Booking Details",0,1);

$pdf->SetFont("Arial","",12);
$pdf->Cell(50,7,"Invoice No:",0,0); $pdf->Cell(50,7,$invoice,0,1);
$pdf->Cell(50,7,"Hotel:",0,0); $pdf->Cell(50,7,$hotel,0,1);
$pdf->Cell(50,7,"Room Type:",0,0); $pdf->Cell(50,7,$room,0,1);
$pdf->Cell(50,7,"Check-in:",0,0); $pdf->Cell(50,7,$checkin,0,1);
$pdf->Cell(50,7,"Check-out:",0,0); $pdf->Cell(50,7,$checkout,0,1);
$pdf->Cell(50,7,"Guests:",0,0); $pdf->Cell(50,7,$guests,0,1);

// Customer
$pdf->Ln(2);
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,10,"Customer Details",0,1);

$pdf->SetFont("Arial","",12);
$pdf->Cell(50,7,"Name:",0,0); $pdf->Cell(50,7,$name,0,1);
$pdf->Cell(50,7,"Email:",0,0); $pdf->Cell(50,7,$email,0,1);
$pdf->Cell(50,7,"Phone:",0,0); $pdf->Cell(50,7,$phone,0,1);

// Payment
$pdf->Ln(2);
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,10,"Payment Summary",0,1);

$pdf->SetFillColor(255,249,196);
$pdf->SetFont("Arial","B",12);
$pdf->Cell(0,10,"Amount Paid:  Rs ".number_format($price),0,1,"L",true);

// QR Code
$pdf->Ln(3);
$pdf->SetFont("Arial","B",13);
$pdf->Cell(0,10,"Verification QR Code",0,1);

$pdf->Image($qrPath, 85, $pdf->GetY(), 40);

$pdf->Ln(45);

// Signatures
$pdf->SetFont("Arial","B",13);
$pdf->Cell(0,8,"Authorized Signatures",0,1,"C");

if (file_exists($sign1)) {
    $pdf->Image($sign1, 60, $pdf->GetY(), 35);
}

if (file_exists($sign2)) {
    $pdf->Image($sign2, 110, $pdf->GetY(), 25);
}

$pdf->Ln(28);

// Stamp
if (file_exists($stamp)) {
    $pdf->Image($stamp, 10, $pdf->GetY() - 25, 30);
}

// Terms
$pdf->Ln(10);
$pdf->SetFont("Arial","B",12);
$pdf->Cell(0,10,"Terms & Conditions",0,1);

$pdf->SetFont("Arial","",9);
$pdf->MultiCell(0,5,
"* Cancellation depends on hotel policies.\n".
"* Please show this invoice at check-in.\n".
"* For support: support@royalstay.com"
);

$pdf->Output();
?>
