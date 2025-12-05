<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location:/user/signin.php");
    exit;
}

$city_id     = $_POST['city'];
$hotel_name  = $_POST['hotel_name'];
$price       = $_POST['price'];
$rating      = $_POST['rating'];
$description = $_POST['description'];

$imageName = "";

if (!empty($_FILES['photo']['name'])) {
    $imageName = time() . "_" . $_FILES['photo']['name'];
    $target = "images/" . $imageName;
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);
}

$sql = "INSERT INTO hotels (city_id, hotel_name, price, rating, image, description)
        VALUES ('$city_id', '$hotel_name', '$price', '$rating', '$imageName', '$description')";

if ($conn->query($sql)) {
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
