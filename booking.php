<?php
include "config.php";

// CHECK INPUT
if (!isset($_GET['hotel_id']) || !isset($_GET['room_id'])) {
    die("Invalid booking request");
}

$hotel_id = intval($_GET['hotel_id']);
$room_id  = intval($_GET['room_id']);
$user_id  = 1;  // Default user (because login is not implemented)

// FETCH HOTEL
$hotel = $conn->query("SELECT hotel_name FROM hotels WHERE id=$hotel_id")->fetch_assoc();
$room  = $conn->query("SELECT room_type, room_price FROM rooms WHERE room_id=$room_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Book Room</title>
<style>
body { font-family:'Segoe UI'; background:#eef2f7; margin:0; }
.container { width:55%; margin:40px auto; }
.card {
    background:white; padding:25px;
    border-radius:18px; box-shadow:0 4px 18px rgba(0,0,0,0.12);
}
input, select {
    width:100%; padding:12px; margin-top:8px;
    border:1px solid #ddd; border-radius:10px;
}
label { font-weight:600; }
.btn {
    background:#0061ff; color:white;
    padding:14px 20px; margin-top:15px;
    width:100%; border-radius:10px;
    border:none; cursor:pointer; font-size:16px;
}
.success {
    background:#d6ffe0; padding:15px;
    border-left:5px solid #00a53f;
    margin-bottom:20px;
    border-radius:10px;
}
</style>
</head>
<body>

<div class="container">
<div class="card">

<?php
// ------------------ PROCESS FORM ----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $check_in  = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests    = intval($_POST['guests']);

    $sql = "INSERT INTO bookings (user_id, hotel_id, room_id, check_in, check_out, guests, status, created_at)
            VALUES ($user_id, $hotel_id, $room_id, '$check_in', '$check_out', $guests, 'Confirmed', NOW())";

    if ($conn->query($sql)) {
        echo "<div class='success'><b>Booking Successful!</b> Your room is confirmed.</div>";
    } else {
        echo "<div class='success' style='background:#ffe0e0;border-color:#ff3d3d'>
                <b>Error:</b> Unable to complete booking.
              </div>";
    }
}
// -------------------------------------------------------
?>

<h2>Booking – <?= $hotel['hotel_name'] ?></h2>
<p><b>Room:</b> <?= $room['room_type'] ?></p>
<p><b>Price:</b> ₹<?= number_format($room['room_price']) ?> per night</p>
<hr><br>

<form method="POST">
    <label>Check-in Date</label>
    <input type="date" name="check_in" required>

    <label>Check-out Date</label>
    <input type="date" name="check_out" required>

    <label>Guests</label>
    <select name="guests" required>
        <option value="1">1 Guest</option>
        <option value="2">2 Guests</option>
        <option value="3">3 Guests</option>
        <option value="4">4 Guests</option>
    </select>

    <button class="btn">Confirm Booking</button>
</form>

</div>
</div>

</body>
</html>
