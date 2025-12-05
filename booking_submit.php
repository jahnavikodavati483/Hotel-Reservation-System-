<?php
session_start();
include "config.php";

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    die("Invalid Access.");
}

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Logged-in user ID & email
$user_id  = $_SESSION['user_id'];
$user_email = $_SESSION['username'];

// Store POST for payment.php
$_SESSION['pay'] = $_POST;

// INPUTS
$hotel_id   = intval($_POST['hotel_id']);
$room_id    = intval($_POST['room_id']);
$fullname   = $_POST['full_name'];
$email      = $_POST['email'];  // user typed
$phone      = $_POST['phone'];
$check_in   = $_POST['check_in'];
$check_out  = $_POST['check_out'];
$adults     = intval($_POST['adults']);
$children   = intval($_POST['children']);
$guests     = $adults + $children;

// Always replace email with login email
$email = $user_email;

// Get room price
$room = $conn->query("SELECT room_price FROM rooms WHERE room_id=$room_id")->fetch_assoc();

if (!$room) {
    die("Room not found.");
}
$price = $room['room_price'];

// ------------------------------
// FIXED INSERT QUERY
// ------------------------------
$stmt = $conn->prepare("
    INSERT INTO bookings 
    (user_id, hotel_id, room_id, full_name, email, phone, check_in, check_out, adults, children, guests, status, refund_status, room_price)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'CONFIRMED', 'NO REFUND', ?)
");

$stmt->bind_param("iiisssssiiis", 
    $user_id,
    $hotel_id,
    $room_id,
    $fullname,
    $email,
    $phone,
    $check_in,
    $check_out,
    $adults,
    $children,
    $guests,
    $price
);

$stmt->execute();

// Store last insert ID
$_SESSION['last_booking_id'] = $conn->insert_id;

// Redirect to payment
?>
<!DOCTYPE html>
<html>
<body>

<form id="go" action="payment.php" method="POST">
    <?php
    foreach ($_POST as $key => $value) {
        echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
    }
    ?>
</form>

<script>
document.getElementById("go").submit();
</script>

</body>
</html>
