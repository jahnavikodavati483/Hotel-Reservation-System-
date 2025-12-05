<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: user/signin.php");
    exit;
}

$city = $_POST['city'];
$name = $_POST['hotel_name'];
$price = $_POST['price'];
$rating = $_POST['rating'];
$desc = $_POST['description'];

// IMAGE UPLOAD
$imgName = time() . "_" . basename($_FILES['img']['name']);
$target = "images/" . $imgName;

move_uploaded_file($_FILES['img']['tmp_name'], $target);

// Insert database entry
$stmt = $conn->prepare("INSERT INTO hotels(city_id, hotel_name, price, rating, description, image) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isdsss", $city, $name, $price, $rating, $desc, $imgName);

if ($stmt->execute()) {
    echo "<script>
            alert('Hotel Added Successfully!');
            window.location.href='admin_hotels.php';
          </script>";
} else {
    echo "<script>
            alert('Error Adding Hotel');
            window.location.href='admin_hotel_add.php';
          </script>";
}
?>
