<?php
session_start(); 
include "config.php";

// USER MUST LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: user/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch bookings for this user
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

/* NAVBAR */
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
    text-decoration:none !important;
}

.logout{
    background:#e63946;
    color:white !important;
}

/* PAGE TITLE */
.page-title{
    font-size:34px;
    font-weight:700;
    margin:35px 0 20px;
}

/* BOOKING CARD */
.booking-card{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 4px 18px rgba(0,0,0,0.15);
}

/* STATUS BADGE */
.status-badge{
    background:#007bff;
    color:white;
    padding:8px 15px;
    border-radius:10px;
    font-weight:bold;
}

/* BUTTONS — ORIGINAL STYLE */
.invoice-btn, .cancel-btn {
    text-decoration:none !important;
    display:inline-block;
    font-weight:bold;
    border-radius:10px;
    padding:10px 17px;
    transition:0.25s;
}

.invoice-btn { background:#d4a017; color:white; }
.invoice-btn:hover { background:#b48710; }

.cancel-btn { background:#e63946; color:white; }
.cancel-btn:hover { background:#c62828; }

/* REVIEW BUTTON */
.review-btn {
    background:#1a73e8;
    color:white !important;
    text-decoration:none !important;
    padding:10px 17px;
    border-radius:10px;
    font-weight:bold;
}
.review-btn:hover {
    background:#155fc8;
}

/* REFUND BUTTON */
.refund-btn {
    background:#4caf50 !important;
    color:white !important;
    text-decoration:none !important;
    padding:10px 17px;
    border-radius:10px;
    font-weight:bold;
}
.refund-btn:hover {
    background:#3e8e41 !important;
}

.action-row{
    display:flex;
    align-items:center;
    gap:12px;
    margin-top:15px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-top">
    <a href="index.php" class="brand">RoyalStay</a>

    <div>
        Welcome, <b><?php echo $_SESSION['user']; ?></b>

        <!-- FIXED LOGOUT PATH -->
        <a href="/user/logout.php" class="nav-btn logout ms-3">Logout</a>
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

    <h4><?= $b['hotel_name'] ?> — <?= $b['room_type'] ?></h4>
    <hr>

    <p><b>Check-In:</b> <?= $b['check_in'] ?></p>
    <p><b>Check-Out:</b> <?= $b['check_out'] ?></p>
    <p><b>Guests:</b> <?= $b['guests'] ?></p>
    <p><b>Status:</b> <?= $b['status'] ?></p>
    <p><b>Amount Paid:</b> ₹<?= number_format($b['room_price']) ?></p>

    <!-- BUTTONS -->
    <div class="action-row">
        
        <span class="status-badge"><?= $b['status'] ?></span>

        <!-- Always invoice -->
        <a href="download_invoice.php?id=<?= $b['id'] ?>" class="invoice-btn">Download Invoice</a>

        <!-- CANCEL -->
        <?php if ($b['status'] == "CONFIRMED"): ?>
            <a href="cancel_step1.php?id=<?= $b['id'] ?>" class="cancel-btn">Cancel Booking</a>
        <?php endif; ?>

        <!-- REVIEW -->
        <?php if ($b['status'] == "CONFIRMED"): ?>
            <a href="feedback.php?booking_id=<?= $b['id'] ?>&hotel_id=<?= $b['hotel_id'] ?>"
               class="review-btn">⭐ Add Review</a>
        <?php endif; ?>

        <!-- REFUND DETAILS -->
        <?php if ($b['status'] == "CANCELLED"): ?>
            <a href="refund_details.php?id=<?= $b['id'] ?>" class="refund-btn">Refund Details</a>
        <?php endif; ?>

    </div>

</div>
<?php endwhile; ?>

</div>

</body>
</html>
