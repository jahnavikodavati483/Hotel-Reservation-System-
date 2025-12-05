<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: user/signin.php");
    exit;
}

$id = $_GET['id'];

$check = $conn->prepare("SELECT COUNT(*) AS c FROM hotels WHERE city_id=?");
$check->bind_param("i", $id);
$check->execute();
$count = $check->get_result()->fetch_assoc()['c'];

if ($count > 0) {
    header("Location: admin_cities.php?error=hotels_exist");
    exit;
}

$conn->query("DELETE FROM cities WHERE id=$id");

header("Location: admin_cities.php?deleted=1");
exit;
