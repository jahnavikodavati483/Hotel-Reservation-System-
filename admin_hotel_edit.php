<?php
session_start();
include "config.php";

// Validate ID
$id = intval($_GET['id']);
$hotel = $conn->query("SELECT * FROM hotels WHERE id=$id")->fetch_assoc();

$cities = $conn->query("SELECT * FROM cities ORDER BY city_name ASC");

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $city = $_POST['city_id'];
    $name = $_POST['hotel_name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $desc = $_POST['description'];

    $conn->query("
        UPDATE hotels 
        SET city_id='$city', hotel_name='$name', price='$price', rating='$rating', description='$desc'
        WHERE id=$id
    ");

    header("Location: admin_hotels.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3>Edit Hotel</h3>

<form method="POST">

    <label>City</label>
    <select name="city_id" class="form-control">
        <?php while($c = $cities->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= $hotel['city_id']==$c['id']?'selected':'' ?>>
                <?= $c['city_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label class="mt-3">Hotel Name</label>
    <input type="text" name="hotel_name" class="form-control" value="<?= $hotel['hotel_name'] ?>">

    <label class="mt-3">Price</label>
    <input type="number" name="price" class="form-control" value="<?= $hotel['price'] ?>">

    <label class="mt-3">Rating</label>
    <input type="text" name="rating" class="form-control" value="<?= $hotel['rating'] ?>">

    <label class="mt-3">Description</label>
    <textarea name="description" class="form-control"><?= $hotel['description'] ?></textarea>

    <button class="btn btn-primary mt-3">Update Hotel</button>

</form>

</body>
</html>
