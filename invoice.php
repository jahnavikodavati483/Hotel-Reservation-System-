<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role     = $_SESSION['role'];
$name     = $_SESSION['user'];

// Fetch bookings
$q = $conn->query("
    SELECT b.*, h.hotel_name, r.room_type 
    FROM bookings b
    LEFT JOIN hotels h ON b.hotel_id = h.id
    LEFT JOIN rooms r ON b.room_id = r.room_id
    WHERE b.user_id = $user_id
    ORDER BY b.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>My Bookings – RoyalStay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#eef2f7;
    font-family:'Segoe UI',sans-serif;
}

.navbar-top{
    background:white;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 14px rgba(0,0,0,0.1);
}

.brand{
    font-size:28px;
    font-weight:700;
    color:#0066ff;
    text-decoration:none;
}

.nav-btn{
    padding:8px 18px;
    border-radius:10px;
    font-weight:bold;
    text-decoration:none;
}

.logout{
    background:#e63946;
    color:white;
}

.admin-btn{
    background:#0056d2;
    color:white;
}

.page-title{
    font-size:34px;
    font-weight:700;
    margin:35px 0 20px;
}

.booking-card{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 4px 18px rgba(0,0,0,0.15);
}

.cancel-btn{
    background:#e63946;
    color:white;
    padding:10px 20px;
    border:none;
    border-radius:10px;
    font-weight:bold;
}

.cancel-btn:hover{
    background:#c62828;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-top">
    <a href="index.php" class="brand">RoyalStay</a>

    <div>
        Welcome, <b><?= $name ?></b>

        <?php if ($role == "admin"): ?>
            <a href="admin_dashboard.php" class="nav-btn admin-btn ms-3">Admin Panel</a>
        <?php endif; ?>

        <a href="logout.php" class="nav-btn logout ms-3">Logout</a>
    </div>
</div>

<div class="container">

    <h2 class="page-title">My Bookings</h2>

    <?php if ($q->num_rows == 0): ?>
    <div class="booking-card text-center">
        <h4>No bookings yet.</h4>
        <a href="cities.php" class="btn btn-primary mt-3">Browse Hotels</a>
    </div>
    <?php endif; ?>

    <?php while($b = $q->fetch_assoc()): ?>
        <div class="booking-card">
            <h4><?= $b['hotel_name'] ?> – <?= $b['room_type'] ?></h4>
            <hr>

            <p><b>Check-In:</b> <?= $b['check_in'] ?></p>
            <p><b>Check-Out:</b> <?= $b['check_out'] ?></p>
            <p><b>Guests:</b> <?= $b['guests'] ?></p>
            <p><b>Status:</b> <?= $b['status'] ?></p>
            <p><b>Refund:</b> <?= $b['refund_status'] ?></p>
            <p><b>Price:</b> ₹<?= number_format($b['room_price']) ?></p>

            <a href="download_invoice.php?id=<?= $b['id'] ?>" class="btn btn-warning me-3">Download Invoice</a>

            <?php if ($b['status'] == "CONFIRMED"): ?>
                <a href="cancel_step1.php?id=<?= $b['id'] ?>" class="cancel-btn">Cancel Booking</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

</div>

</body>
</html>
