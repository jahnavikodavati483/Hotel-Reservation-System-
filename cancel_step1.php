<?php
session_start();
include "config.php";

if (!isset($_GET['id'])) die("Invalid Request");
$booking_id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Cancel Booking – RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#eef2f7;
    font-family:'Poppins', sans-serif;
}

.cancel-box{
    width:65%;
    margin:50px auto;
    background:white;
    padding:45px;
    border-radius:25px;
    box-shadow:0 8px 30px rgba(0,0,0,0.18);
}

.header-title{
    font-size:36px;
    font-weight:700;
    text-align:center;
    color:#d62828;
}

.sub{
    text-align:center;
    font-size:17px;
    margin-bottom:20px;
}

.label-title{
    font-weight:600;
    font-size:18px;
    margin-top:20px;
}

select, textarea{
    width:100%;
    border-radius:12px;
    border:1px solid #ccc;
    padding:12px;
    margin-top:10px;
    font-size:15px;
}

.btn-cancel{
    width:100%;
    margin-top:35px;
    padding:14px;
    background:#d62828;
    color:white;
    border:none;
    font-size:19px;
    border-radius:12px;
    font-weight:bold;
}

.btn-cancel:hover{
    background:#b71d1d;
}

.info-box{
    background:#fff4f4;
    border-left:5px solid #d62828;
    padding:15px;
    margin:20px 0;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="cancel-box">

    <h2 class="header-title">Cancel Your Booking</h2>
    <p class="sub">We’re sorry to see you cancel. Please help us improve your experience ❤️</p>

    <div class="info-box">
        ⚠ <b>Note:</b> Refund will be processed within <b>24 hours</b> after cancellation.
    </div>

    <form action="cancel_booking.php" method="POST">

        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">

        <label class="label-title">Reason for Cancellation</label>
        <select name="reason" required>
            <option value="">Select a reason</option>
            <option value="Change of plans">Change of plans</option>
            <option value="Found a better option">Found a better option</option>
            <option value="Emergency situation">Emergency situation</option>
            <option value="Wrong booking details">Wrong booking details</option>
            <option value="Hotel too far or inconvenient">Hotel too far or inconvenient</option>
            <option value="Others">Others</option>
        </select>

        <label class="label-title">Additional Comments (Optional)</label>
        <textarea name="extra" rows="4" placeholder="Tell us more about your reason..."></textarea>

        <button class="btn-cancel">Confirm Cancellation</button>

    </form>
</div>

</body>
</html>
