<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: user/signin.php");
    exit;
}

// FIXED ICON LIST (DO NOT CHANGE ORIGINAL ICONS)
$iconList = [
    "fa-solid fa-location-dot",   // For Bengaluru
    "fa-solid fa-city",           // For Chennai
    "fa-solid fa-earth-asia",     // For Delhi
    "fa-solid fa-map",            // For Goa
    "fa-solid fa-map-pin",        // For Hyderabad
    "fa-solid fa-house"           // For Mumbai
];

// GET CURRENT COUNT TO PICK NEXT ICON
$cityCount = $conn->query("SELECT COUNT(*) AS c FROM cities")->fetch_assoc()['c'];
$iconToAssign = $iconList[$cityCount % count($iconList)];

if (isset($_POST['add_city'])) {
    $city_name = trim($_POST['city_name']);

    $stmt = $conn->prepare("INSERT INTO cities (city_name, city_image) VALUES (?, ?)");
    $stmt->bind_param("ss", $city_name, $iconToAssign);
    $stmt->execute();

    header("Location: admin_cities.php?added=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<h3>Add New City</h3>

<form method="POST">
    <label class="form-label">City Name</label>
    <input type="text" name="city_name" class="form-control mb-3" required>

    <button class="btn btn-primary" name="add_city">Add City</button>
</form>

</body>
</html>
