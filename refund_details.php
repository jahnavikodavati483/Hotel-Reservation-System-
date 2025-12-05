<?php
session_start();
include "config.php";

if (!isset($_GET['id'])) die("Invalid Request");
$booking_id = intval($_GET['id']);

$q = $conn->query("SELECT * FROM bookings WHERE id=$booking_id");
$b = $q->fetch_assoc();
if (!$b) die("Booking not found");

$amount = $b['room_price'];
$status = $b['refund_status'];  // PENDING / PROCESSING / COMPLETED

// Determine step based on refund status
$step = 1;
if ($status == "PROCESSING") $step = 2;
if ($status == "COMPLETED") $step = 3;
?>

<!DOCTYPE html>
<html>
<head>
<title>Refund Status – RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#eef2f7;
    font-family:'Poppins', sans-serif;
}

/* MAIN BOX */
.refund-box{
    width:70%;
    margin:50px auto;
    background:white;
    padding:45px;
    border-radius:25px;
    box-shadow:0 8px 30px rgba(0,0,0,0.15);
}

/* SUCCESS BANNER */
.success-banner{
    background:#e7ffe7;
    padding:18px;
    border-left:5px solid #1cab1c;
    border-radius:10px;
    margin-bottom:25px;
    font-size:18px;
    font-weight:600;
    color:#1a7f1a;
}

/* Heading */
.title{
    text-align:center;
    font-size:34px;
    font-weight:700;
    color:#0066ff;
}

/* Progress bar container */
.timeline{
    display:flex;
    justify-content:space-between;
    margin:40px 0 20px;
    position:relative;
}

.line{
    position:absolute;
    top:22px;
    left:50px;
    right:50px;
    height:4px;
    background:#d7d7d7;
    z-index:1;
}

.line-active{
    position:absolute;
    top:22px;
    left:50px;
    height:4px;
    background:#0066ff;
    z-index:2;
    width:0;
    transition:1.2s ease;
}

/* Circles */
.step{
    z-index:3;
    text-align:center;
    width:100px;
}

.circle{
    width:38px;
    height:38px;
    line-height:38px;
    border-radius:50%;
    margin:0 auto 10px;
    background:#d7d7d7;
    color:white;
    font-weight:bold;
}

.active-circle{
    background:#0066ff !important;
}

.completed-circle{
    background:#1cab1c !important;
}

/* Button */
.back-btn{
    margin-top:30px;
    display:block;
    width:100%;
    text-align:center;
    background:#0066ff;
    color:white;
    padding:12px;
    font-size:18px;
    border-radius:12px;
    font-weight:bold;
    text-decoration:none;
}

.back-btn:hover{
    background:#004bb3;
}
</style>

<script>
// animate line based on step
function animateLine(step){
    let line = document.getElementById("lineActive");
    if(step == 1) line.style.width = "0%";
    if(step == 2) line.style.width = "50%";
    if(step == 3) line.style.width = "100%";
}
</script>

</head>
<body onload="animateLine(<?= $step ?>)">

<div class="refund-box">

    <?php if ($status == "COMPLETED"): ?>
        <div class="success-banner">✔ Your refund is credited</div>
    <?php endif; ?>

    <h2 class="title">Refund Status</h2>
    <p class="text-center">Booking ID: <b><?= $booking_id ?></b></p>

    <!-- Timeline -->
    <div class="timeline">

        <div class="line"></div>
        <div class="line-active" id="lineActive"></div>

        <!-- Step 1 -->
        <div class="step">
            <div class="circle 
                <?= ($step >= 1 ? 'active-circle' : '') ?> 
                <?= ($step == 3 ? 'completed-circle' : '') ?>">
                <?= ($step == 3 ? '✔' : '1') ?>
            </div>
            <div>Initiated</div>
        </div>

        <!-- Step 2 -->
        <div class="step">
            <div class="circle 
                <?= ($step >= 2 ? 'active-circle' : '') ?> 
                <?= ($step == 3 ? 'completed-circle' : '') ?>">
                <?= ($step == 3 ? '✔' : '2') ?>
            </div>
            <div>Processing</div>
        </div>

        <!-- Step 3 -->
        <div class="step">
            <div class="circle 
                <?= ($step == 3 ? 'completed-circle' : '') ?>">
                <?= ($step == 3 ? '✔' : '3') ?>
            </div>
            <div>Refunded</div>
        </div>

    </div>

    <!-- Refund Amount -->
    <div class="text-center mt-4" style="font-size:22px; font-weight:600;">
        Total Refund:  
        <span style="color:#1cab1c;">₹<?= number_format($amount) ?></span>
    </div>

    <a href="my_bookings.php" class="back-btn">Back to My Bookings</a>

</div>
</body>
</html>
