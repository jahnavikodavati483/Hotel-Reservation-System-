<?php
session_start();
include "config.php";

// Expect booking_id and hotel_id in GET
if (!isset($_GET['booking_id']) || !isset($_GET['hotel_id'])) {
    die("Invalid Request");
}
$booking_id = intval($_GET['booking_id']);
$hotel_id   = intval($_GET['hotel_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Write Review – RoyalStay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#eef2f7; font-family:'Segoe UI',sans-serif; }
        .review-box{ width:55%; margin:60px auto; background:white; padding:30px; border-radius:14px; box-shadow:0 6px 25px rgba(0,0,0,0.12); }
        .brand{ font-weight:700; color:#0066ff; font-size:22px; text-decoration:none; }
        .logout{ background:#e63946; color:white; padding:8px 14px; border-radius:8px; text-decoration:none; }
    </style>
</head>
<body>

<nav class="d-flex justify-content-between align-items-center p-3 bg-white" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <a href="index.php" class="brand">RoyalStay</a>
    <div>
        Hi, <b><?= htmlentities($_SESSION['user'] ?? 'Guest') ?></b>
        <a href="/user/logout.php" class="logout ms-3">Logout</a>
    </div>
</nav>

<div class="review-box">
    <h3 style="color:#0066ff; text-align:center; margin-bottom:8px;">Share Your Experience</h3>
    <p class="text-center text-muted">Tell others about your stay — it helps the community!</p>

    <form action="submit_review.php" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
        <input type="hidden" name="hotel_id" value="<?= $hotel_id ?>">

        <label class="form-label mt-3"><strong>Rating</strong></label>
        <select name="rating" class="form-control" required>
            <option value="">Select rating</option>
            <option value="5">5 — Excellent</option>
            <option value="4">4 — Very Good</option>
            <option value="3">3 — Good</option>
            <option value="2">2 — Poor</option>
            <option value="1">1 — Very Bad</option>
        </select>

        <label class="form-label mt-3"><strong>Your Review</strong></label>
        <textarea name="comments" class="form-control" rows="5" required placeholder="Write about your experience..."></textarea>

        <button type="submit" class="btn btn-primary w-100 mt-4" style="font-weight:700;">Submit Review</button>
    </form>
</div>

</body>
</html>
