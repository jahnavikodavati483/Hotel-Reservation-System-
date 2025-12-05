<?php
session_start();
include "config.php";

if (!isset($_POST['booking_id']) || !isset($_POST['reason'])) {
    die("Invalid Request");
}

$booking_id = intval($_POST['booking_id']);
$reason = $conn->real_escape_string($_POST['reason']);
$extra  = isset($_POST['extra']) ? $conn->real_escape_string($_POST['extra']) : "";

// Fetch booking for email info
$bq = $conn->query("
    SELECT b.*, h.hotel_name, r.room_type 
    FROM bookings b
    LEFT JOIN hotels h ON b.hotel_id = h.id
    LEFT JOIN rooms r ON b.room_id = r.room_id
    WHERE b.id = $booking_id
");
$b = $bq->fetch_assoc();

$email = $b['email'];
$hotel = $b['hotel_name'];
$room  = $b['room_type'];
$checkin = $b['check_in'];
$checkout = $b['check_out'];
$price = $b['room_price'];

// Update table
$conn->query("
    UPDATE bookings SET
        status='CANCELLED',
        cancelled_reason='$reason',
        cancelled_extra='$extra',
        cancelled_at=NOW(),
        refund_status='PROCESSING'
    WHERE id=$booking_id
");

// Email Start
require "includes/src/PHPMailer.php";
require "includes/src/SMTP.php";
require "includes/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "jahnavikodavati483@gmail.com";
    $mail->Password = "bprd yynf audy gkyr";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("YOUR_EMAIL@gmail.com", "RoyalStay");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Booking Cancelled – Refund Processing";

    $mail->Body = "
        <h2 style='color:#d62828;'>Your Booking Has Been Cancelled</h2>
        <p>Your refund request is now <b>PROCESSING</b>.</p>
        <p>Refund will be completed within <b>24 hours</b>.</p>
        <br>
        <b>Booking Details:</b><br>
        Hotel: $hotel <br>
        Room: $room <br>
        Check-in: $checkin <br>
        Check-out: $checkout <br>
        Amount Paid: ₹$price <br><br>

        <b>Reason:</b> $reason <br>
        <b>Additional Info:</b> $extra <br><br>

        <p>Thank you for choosing RoyalStay.</p>
    ";

    $mail->send();

} catch (Exception $e) {
    // ignore errors
}

header("Location: my_bookings.php?cancelled=1");
exit;
?>
