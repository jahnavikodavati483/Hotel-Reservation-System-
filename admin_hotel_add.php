<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location:/user/signin.php");
    exit;
}

$cities = $conn->query("SELECT * FROM cities ORDER BY city_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Hotel - RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    background: linear-gradient(180deg,#eef4ff,#f9fbff);
    font-family:"Segoe UI";
}

.topbar{
    background:#0066ff;
    padding:18px 35px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.topbar a{
    background:white;
    padding:10px 20px;
    border-radius:8px;
    font-weight:600;
    color:#0066ff;
    text-decoration:none;
}

.form-container{
    width:550px;
    margin:70px auto;
    background:white;
    padding:40px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.form-control{
    height:52px;
    border-radius:12px;
    border:1.5px solid #cfd9ff;
    font-size:16px;
}

.form-control-textarea{
    border-radius:12px;
    border:1.5px solid #cfd9ff;
    padding:15px;
    font-size:16px;
}

.btn-create{
    background:#008f3c;
    color:white;
    height:50px;
    font-size:18px;
    border-radius:12px;
    font-weight:700;
}
</style>
</head>

<body>

<div class="topbar">
    <h2>Add Hotel</h2>
    <a href="admin_hotels.php"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="form-container">

<form id="hotelForm" method="POST" action="/admin_hotel_add_save.php" enctype="multipart/form-data">

    <label class="fw-bold">City</label>
    <select name="city" class="form-control mb-3" required>
        <option value="">Select City</option>
        <?php while($c = $cities->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['city_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label class="fw-bold">Hotel Name</label>
    <input type="text" name="hotel_name" class="form-control mb-3" required>

    <label class="fw-bold">Price Per Night</label>
    <input type="number" name="price" class="form-control mb-3" required>

    <label class="fw-bold">Rating (0–10)</label>
    <input type="number" name="rating" step="0.1" class="form-control mb-3" required>

    <label class="fw-bold">Description</label>
    <textarea name="description" rows="3" class="form-control-textarea mb-3" required></textarea>

    <label class="fw-bold">Upload Image</label>
    <input type="file" name="photo" class="form-control mb-4" required>

    <button class="btn btn-create w-100">Add Hotel</button>

</form>

</div>

<script>
document.getElementById("hotelForm").addEventListener("submit", function(e){
    e.preventDefault();

    Swal.fire({
        icon: "success",
        title: "Hotel Added!",
        text: "Saving hotel details...",
        confirmButtonColor: "#0066ff"
    }).then(() => {
        document.getElementById("hotelForm").submit();
    });
});
</script>

</body>
</html>
