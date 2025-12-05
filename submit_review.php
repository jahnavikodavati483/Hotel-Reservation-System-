<?php
session_start();
include "config.php"; // auto-switches between local & live

// Basic validation
if (!isset($_POST['booking_id'], $_POST['hotel_id'], $_POST['rating'])) {
    die("Invalid Request");
}

$booking_id = intval($_POST['booking_id']);
$hotel_id   = intval($_POST['hotel_id']);
$rating     = intval($_POST['rating']);
$comments   = isset($_POST['comments']) ? $conn->real_escape_string($_POST['comments']) : '';
$user_id    = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Insert review
$insert_sql = "
    INSERT INTO feedback (booking_id, user_id, hotel_id, rating, comments, created_at)
    VALUES ($booking_id, $user_id, $hotel_id, $rating, '$comments', NOW())
";
$conn->query($insert_sql);

// Update booking table only if review_submitted column exists
$colQ = $conn->query("SHOW COLUMNS FROM `bookings` LIKE 'review_submitted'");
if ($colQ && $colQ->num_rows > 0) {
    $conn->query("UPDATE bookings SET review_submitted = 1 WHERE id = $booking_id");
}

// Get city id
$city_id = 0;
$cityQ = $conn->query("SELECT city_id FROM hotels WHERE id = $hotel_id LIMIT 1");
if ($cityQ && $row = $cityQ->fetch_assoc()) {
    $city_id = intval($row['city_id']);
}

// *************** FIXED REDIRECT ***************
// Use relative redirect → works on localhost + InfinityFree
$redirect = "hotels.php?city=" . $city_id;
// ************************************************

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review Submitted – RoyalStay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{ background:#eef2f7; font-family:'Segoe UI',sans-serif; }
        .navbar-top{ background:white; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; 
                     box-shadow:0 2px 12px rgba(0,0,0,0.06); }
        .brand{ font-weight:700; color:#0066ff; text-decoration:none; font-size:20px; }
        .logout{ background:#e63946; color:white; padding:8px 14px; border-radius:8px; text-decoration:none; font-weight:700; }
    </style>
</head>
<body>

<nav class="navbar-top">
    <a href="index.php" class="brand">RoyalStay</a>
    <div>
        Hi, <b><?= isset($_SESSION['user']) ? htmlentities($_SESSION['user']) : 'Guest' ?></b>
        <a href="user/logout.php" class="logout ms-3">Logout</a>
    </div>
</nav>

<script>
Swal.fire({
    title: "Review Submitted!",
    text: "Thank you — your feedback has been recorded.",
    icon: "success",
    confirmButtonColor: "#0066ff",
    allowOutsideClick: false
}).then(function() {
    window.location.href = <?= json_encode($redirect) ?>;
});
</script>

</body>
</html>
