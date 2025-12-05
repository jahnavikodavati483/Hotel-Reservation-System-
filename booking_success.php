<?php
session_start();
include "config.php";

if (!isset($_SESSION['last_booking_id'])) {
    die("Invalid Access.");
}

$booking_id = $_SESSION['last_booking_id'];

// Fetch booking details
$bk = $conn->query("
    SELECT b.*, h.hotel_name, r.room_type, r.room_price
    FROM bookings b
    LEFT JOIN hotels h ON b.hotel_id = h.id
    LEFT JOIN rooms r ON b.room_id = r.room_id
    WHERE b.id = $booking_id
")->fetch_assoc();

$hotel  = $bk['hotel_name'];
$room   = $bk['room_type'];
$price  = $bk['room_price'];
$in     = $bk['check_in'];
$out    = $bk['check_out'];
$guests = $bk['guests'];
$email  = $bk['email'];

/* ======================= EMAIL ============================ */
require "includes/src/PHPMailer.php";
require "includes/src/SMTP.php";
require "includes/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;

    // YOUR EMAIL (must match Gmail account)
    $mail->Username   = "jahnavikodavati483@gmail.com";
    $mail->Password   = "bprd yynf audy gkyr"; // Gmail App Password

    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    // Correct FROM address
    $mail->setFrom("jahnavikodavati483@gmail.com", "RoyalStay");

    // Send to customer
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Booking Confirmed – RoyalStay";

    $mail->Body = "
        <h2 style='color:#0066ff;'>Booking Confirmed!</h2>
        <p>Your booking at <b>$hotel</b> is successfully confirmed.</p><br>

        <b>Room:</b> $room <br>
        <b>Check-in:</b> $in <br>
        <b>Check-out:</b> $out <br>
        <b>Guests:</b> $guests <br>
        <b>Amount:</b> ₹$price <br><br>

        <p>Thank you for choosing <b>RoyalStay</b>!</p>
    ";

    $mail->send();

} catch (Exception $e) {
    // email error ignored
}
/* =========================================================== */

?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Successful – RoyalStay</title>

<style>
body{
    background:#eef2f7;
    font-family:'Segoe UI';
}
.container{
    width:55%;
    margin:90px auto;
    background:white;
    padding:40px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 4px 25px rgba(0,0,0,0.15);
}
h1{
    font-size:35px;
    color:#0066ff;
}
.btn{
    background:#d4a017;
    padding:15px 25px;
    border-radius:12px;
    color:white;
    text-decoration:none;
    font-weight:bold;
    margin:10px;
}
.btn:hover{
    background:#b48710;
}
.danger-btn{
    background:#e63946 !important;
}
</style>

</head>
<body>

<div class="container">

    <h1>🎉 Booking Confirmed!</h1>

    <p>Your stay at <b><?= $hotel ?></b> has been successfully booked.</p>

    <p><b>Check-in:</b> <?= $in ?></p>
    <p><b>Check-out:</b> <?= $out ?></p>
    <p><b>Guests:</b> <?= $guests ?></p>
    <p><b>Amount:</b> ₹<?= number_format($price) ?></p>

    <br>

    <a href="my_bookings.php" class="btn">View My Bookings</a>
    <a href="index.php" class="btn" style="background:#4caf50;">Return Home</a>
    <a href="cancel_step1.php?id=<?= $booking_id ?>" class="btn danger-btn">Cancel Booking</a>

</div>

</body>
</html>
