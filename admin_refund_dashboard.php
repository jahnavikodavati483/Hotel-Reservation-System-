<?php
session_start();
include "config.php";

// Only admin
if ($_SESSION['username'] !== "jahnavikodavati483@gmail.com") {
    die("Access Denied");
}

// Approve refund
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("
        UPDATE bookings 
        SET refund_stage=3, refund_approved_at=NOW()
        WHERE id=$id
    ");
    header("Location: admin_refunds.php?msg=approved");
    exit;
}

// List refund requests
$refunds = $conn->query("
    SELECT * FROM bookings 
    WHERE status='CANCELLED' 
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Refunds – RoyalStay</title>
<style>
body{ font-family:'Segoe UI'; padding:20px;}
.card{
    padding:20px;
    border-radius:12px;
    background:#f8f9fa;
    margin-bottom:15px;
    border:1px solid #ddd;
}
.btn{
    padding:8px 15px;
    background:#0066ff;
    color:white;
    border-radius:8px;
    text-decoration:none;
}
</style>
</head>

<body>

<h1>Refund Requests</h1>

<?php while($r = $refunds->fetch_assoc()): ?>
<div class="card">
    <b>Booking ID:</b> <?= $r['id'] ?><br>
    <b>Status:</b> <?= $r['refund_stage']==1? "Waiting for admin approval":"Approved" ?><br><br>

    <?php if($r['refund_stage']==1): ?>
        <a href="admin_refunds.php?approve=<?= $r['id'] ?>" class="btn">Approve Refund</a>
    <?php else: ?>
        <span style="color:green;font-weight:bold;">Approved ✔</span>
    <?php endif; ?>
</div>
<?php endwhile; ?>

</body>
</html>
