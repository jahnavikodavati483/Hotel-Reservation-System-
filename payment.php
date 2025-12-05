<?php
session_start();
include "config.php";

if (!isset($_SESSION['pay'])) {
    die("Invalid Access. Start booking again.");
}

$pay = $_SESSION['pay'];

if (!empty($_SESSION['username'])) {
    $pay['email'] = $_SESSION['username'];
}

$hotel_id  = $pay['hotel_id'];
$room_id   = $pay['room_id'];
$fullname  = $pay['full_name'];
$email     = $pay['email'];
$phone     = $pay['phone'];
$check_in  = $pay['check_in'];
$check_out = $pay['check_out'];
$adults    = $pay['adults'];
$children  = $pay['children'];
$guests    = $adults + $children;

$hotel = $conn->query("SELECT * FROM hotels WHERE id=$hotel_id")->fetch_assoc();
$room  = $conn->query("SELECT * FROM rooms WHERE room_id=$room_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>RoyalStay – Secure Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Segoe UI';
    background:#e9eef7;
}

/* NAVBAR */
.navbar-top{
    background:white;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #ddd;
}

.brand{
    font-size:28px;
    font-weight:700;
    color:#0056d2;
}

.admin-btn{
    background:#0056d2;
    padding:8px 20px;
    border-radius:30px;
    color:white !important;
    text-decoration:none;
    font-weight:600;
}

.logout-btn{
    background:#e63946;
    padding:8px 18px;
    border-radius:10px;
    color:white !important;
    text-decoration:none;
    font-weight:600;
}

/* MAIN CARD */
.wrapper{
    width:70%;
    margin:40px auto;
}

.main-card{
    background:white;
    padding:40px;
    border-radius:22px;
    box-shadow:0 12px 30px rgba(0,0,0,0.15);
}

/* SUMMARY SECTION */
.summary-box{
    background:#f4f7ff;
    padding:25px;
    border-radius:18px;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
}

.summary-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:12px;
    color:#003da5;
}

/* PAYMENT METHODS */
.pm-box{
    border:2px solid #d2d6e3;
    padding:14px;
    border-radius:14px;
    margin-top:12px;
    cursor:pointer;
    background:white;
    transition:0.25s;
}

.pm-box:hover{
    border-color:#003da5;
    background:#f0f5ff;
}

.pm-box input{
    margin-right:10px;
}

.hidden{
    display:none;
}

.method-details{
    background:#f7f9ff;
    padding:15px;
    border-radius:12px;
    margin-top:10px;
    border:1px solid #ccd4e1;
}

/* PAY BUTTON */
.btn-pay{
    width:100%;
    padding:16px;
    font-size:20px;
    background:#d4a017;
    color:white;
    border:none;
    border-radius:14px;
    font-weight:700;
    margin-top:20px;
}
.btn-pay:hover{
    background:#b48710;
}
</style>

<script>
function showMethod(id){
    document.getElementById('upi').classList.add('hidden');
    document.getElementById('card').classList.add('hidden');
    document.getElementById('bank').classList.add('hidden');

    document.getElementById(id).classList.remove('hidden');
}
</script>

</head>
<body>

<!-- NAVBAR -->
<div class="navbar-top">
    <div class="brand">RoyalStay</div>

    <div style="display:flex;gap:20px;align-items:center;">
        <b>Hi, <?= $_SESSION['user'] ?></b>

        <?php if($_SESSION['username']=="jahnavikodavati483@gmail.com"){ ?>
            <a href="/admin_dashboard.php" class="admin-btn">Admin</a>
        <?php } ?>

        <a href="/user/logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- MAIN WRAPPER -->
<div class="wrapper">

    <div class="main-card">
        <h2 style="font-weight:700;color:#003da5">Secure Payment</h2>
        <p style="opacity:0.7;margin-bottom:25px;">Complete your booking securely in a few steps</p>

        <!-- SUMMARY SECTION -->
        <div class="summary-box">
            <div class="summary-title">Booking Summary</div>
            <p><b>🏨 Hotel:</b> <?= $hotel['hotel_name'] ?></p>
            <p><b>🛏 Room:</b> <?= $room['room_type'] ?></p>
            <p><b>💵 Price:</b> ₹<?= number_format($room['room_price']) ?> / night</p>
            <hr>
            <p><b>📅 Check-In:</b> <?= $check_in ?></p>
            <p><b>📅 Check-Out:</b> <?= $check_out ?></p>
            <p><b>👤 Guests:</b> <?= $guests ?> (Adults <?= $adults ?>, Children <?= $children ?>)</p>
            <hr>
            <p><b>📧 Email:</b> <?= $email ?></p>
            <p><b>📞 Phone:</b> <?= $phone ?></p>
        </div>

        <h3 style="margin-top:35px;font-weight:700;">Choose Payment Method</h3>

        <!-- PAYMENT METHODS -->
        <div class="pm-box" onclick="showMethod('upi')">
            <input type="radio" name="method" checked> 💳 UPI (Google Pay / PhonePe)
        </div>
        <div id="upi" class="method-details">
            <label>Enter UPI ID</label>
            <input type="text" class="form-control" placeholder="yourname@okicici">
        </div>

        <div class="pm-box" onclick="showMethod('card')">
            <input type="radio" name="method"> 💳 Credit / Debit Card
        </div>
        <div id="card" class="method-details hidden">
            <label>Card Number</label>
            <input type="text" class="form-control" placeholder="XXXX XXXX XXXX XXXX">
            <label>Expiry Date</label>
            <input type="text" class="form-control" placeholder="MM/YY">
            <label>CVV</label>
            <input type="password" class="form-control" maxlength="3">
            <label>Name on Card</label>
            <input type="text" class="form-control">
        </div>

        <div class="pm-box" onclick="showMethod('bank')">
            <input type="radio" name="method"> 🏦 Net Banking
        </div>
        <div id="bank" class="method-details hidden">
            <label>Select Bank</label>
            <select class="form-control">
                <option>SBI</option>
                <option>HDFC</option>
                <option>ICICI</option>
                <option>Axis Bank</option>
                <option>Kotak</option>
            </select>
        </div>

        <form action="booking_success.php" method="POST">
            <button class="btn-pay">Confirm & Pay</button>
        </form>

    </div>

</div>

</body>
</html>
