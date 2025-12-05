<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: user/signin.php");
    exit;
}

$id = $_GET['id'];
$city = $conn->query("SELECT * FROM cities WHERE id=$id")->fetch_assoc();

if (isset($_POST['update_city'])) {
    $city_name = trim($_POST['city_name']);

    $stmt = $conn->prepare("UPDATE cities SET city_name=? WHERE id=?");
    $stmt->bind_param("si", $city_name, $id);
    $stmt->execute();

    header("Location: admin_cities.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3>Edit City</h3>

<form method="POST">
    <label class="form-label">City Name</label>
    <input type="text" name="city_name" value="<?= $city['city_name'] ?>" class="form-control mb-3" required>

    <button class="btn btn-primary" name="update_city">Update</button>
</form>

</body>
</html>
