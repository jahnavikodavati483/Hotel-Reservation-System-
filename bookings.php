<?php
session_start();
include "config.php";

if (!isset($_GET['hotel_id']) || !isset($_GET['room_id'])) {
    die("Invalid access.");
}

$hotel_id = intval($_GET['hotel_id']);
$room_id = intval($_GET['room_id']);

$hotel = $conn->query("SELECT * FROM hotels WHERE id=$hotel_id")->fetch_assoc();
$room  = $conn->query("SELECT * FROM rooms WHERE room_id=$room_id")->fetch_assoc();

// FIX: Prevent warnings if room does not exist
if (!$room) {
    $room = [
        'room_type' => "Room Not Found",
        'room_price' => 0
    ];
}

// RANDOM IMAGE
$imgs = [];
for ($i=1; $i<=30; $i++) $imgs[] = "room$i.jpg";
$selected = $imgs[array_rand($imgs)];
?>
<!DOCTYPE html>
<html>
<head>
<title>Book Your Stay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Segoe UI';
    background:#f2f6ff;
}

.navbar-top{
    background:white;
    padding:18px 45px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #dfe6f5;
}

.nav-right{display:flex;align-items:center;gap:20px;}
.logout-btn{
    background:#ff4b5c;padding:8px 20px;border-radius:8px;
    color:white;text-decoration:none;font-weight:600;
}
.admin-btn{
    background:#0056d2;padding:8px 22px;border-radius:30px;
    color:white;text-decoration:none;font-weight:600;
}
.container-custom{
    width:92%;margin:40px auto;display:flex;gap:40px;
}

.left-box{
    width:45%;
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 4px 15px rgba(0,0,0,0.12);
}

.left-box img{
    width:100%;
    border-radius:16px;
    height:380px;
    object-fit:cover;
}

.info-title{
    font-size:24px;
    font-weight:700;
    margin:20px 0 10px;
}

.info-card{
    background:#f8faff;
    padding:14px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,0.10);
}

.right-card{
    width:55%;
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 4px 20px rgba(0,0,0,0.15);
}

.header{
    background:#0057d9;color:white;padding:15px;
    text-align:center;border-radius:12px;font-size:23px;font-weight:bold;
}

.room-details{
    background:#eef4ff;padding:18px;border-radius:12px;margin-top:12px;
    font-size:17px;font-weight:600;
}

label{font-weight:600;margin-top:10px;}
input,select{
    width:100%;padding:12px;border-radius:10px;
    border:1px solid #bcccff;margin-bottom:12px;
}

.btn-main{
    width:100%;padding:14px;background:#0071e3;color:white;
    font-size:18px;border-radius:12px;font-weight:700;border:none;
}
.btn-main:hover{background:#005ec2;}
</style>

</head>
<body>

<!-- NAVBAR -->
<div class="navbar-top">
    <h3 style="color:#0066ff;font-weight:700;">RoyalStay</h3>

    <div class="nav-right">
        <?php if(isset($_SESSION['user'])){ ?>
            <span><b>Hi, <?= $_SESSION['user'] ?></b></span>
            <?php if($_SESSION['username']=="jahnavikodavati483@gmail.com"){ ?>
                <a href="/admin_dashboard.php" class="admin-btn">Admin</a>
            <?php } ?>
            <a href="/user/logout.php" class="logout-btn">Logout</a>
        <?php } ?>
    </div>
</div>


<!-- PAGE CONTENT -->
<div class="container-custom">

    <!-- LEFT BOX -->
    <div class="left-box">
        <img src="images/<?= $selected ?>">

        <div class="info-title">Why Book With Us?</div>

        <div class="info-card"><b>💰 Best Price Guarantee</b><br>We match the lowest price.</div>
        <div class="info-card"><b>🔐 Secure Payments</b><br>100% encrypted checkout.</div>
        <div class="info-card"><b>❌ Free Cancellation</b><br>Available for most rooms.</div>
        <div class="info-card"><b>📞 24/7 Customer Support</b><br>Always here for you.</div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-card">
        <div class="header">Royal HOTEL BOOKING</div>

        <div class="room-details">
            <?= $hotel['hotel_name'] ?><br>
            Room Type: <?= $room['room_type'] ?><br>
            Price: ₹<?= number_format($room['room_price']) ?> / night
        </div>

        <!-- FORM -->
        <form action="booking_submit.php" method="POST">
            <input type="hidden" name="hotel_id" value="<?= $hotel_id ?>">
            <input type="hidden" name="room_id" value="<?= $room_id ?>">

            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Check-In</label>
            <input type="date" name="check_in" required>

            <label>Check-Out</label>
            <input type="date" name="check_out" required>

            <label>Adults</label>
            <select name="adults">
                <option>1</option><option>2</option><option>3</option><option>4</option>
            </select>

            <label>Children</label>
            <select name="children">
                <option>0</option><option>1</option><option>2</option>
            </select>

            <button class="btn-main">Proceed to Payment</button>
        </form>
    </div>

</div>

</body>
</html>
