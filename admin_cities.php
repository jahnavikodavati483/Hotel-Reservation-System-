<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: user/signin.php");
    exit;
}

// ADD CITY
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_city"])) {
    $city = trim($_POST["new_city"]);
    if ($city != "") {
        $stmt = $conn->prepare("INSERT INTO cities (city_name) VALUES (?)");
        $stmt->bind_param("s", $city);
        $stmt->execute();
        echo "<script>localStorage.setItem('cityAdded', '1');</script>";
        header("Location: admin_cities.php");
        exit;
    }
}

// EDIT CITY
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_city_id"])) {
    $id = $_POST["edit_city_id"];
    $name = trim($_POST["edit_city_name"]);

    $stmt = $conn->prepare("UPDATE cities SET city_name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();

    echo "<script>localStorage.setItem('cityEdited', '1');</script>";
    header("Location: admin_cities.php");
    exit;
}

// DELETE CITY
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM cities WHERE id=$id");

    echo "<script>localStorage.setItem('cityDeleted', '1');</script>";
    header("Location: admin_cities.php");
    exit;
}

$cities = $conn->query("SELECT * FROM cities ORDER BY city_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Cities - RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    background:#eef4ff;
    font-family:'Poppins', sans-serif;
}

.top-header{
    background:#0261ff;
    padding:15px 25px;
    color:white;
    font-size:22px;
    font-weight:600;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.top-header a{
    background:white;
    padding:8px 18px;
    border-radius:8px;
    font-weight:600;
    font-size:15px;
    text-decoration:none;
    color:#0261ff;
}
.top-header .logout{
    background:#ff4d4d;
    color:white;
}

.city-card{
    background:white;
    padding:20px;
    margin-bottom:18px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 20px rgba(0,0,0,0.1);
}

.city-left{
    display:flex;
    align-items:center;
    gap:18px;
    font-size:20px;
    font-weight:600;
}

.city-left i{
    font-size:34px;
    color:#0261ff;
    animation:pop 0.4s ease;
}

@keyframes pop{
    from{transform:scale(0.6); opacity:0;}
    to{transform:scale(1); opacity:1;}
}

#cityModal, #editModal{
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.45);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-box{
    width:420px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 8px 25px rgba(0,0,0,0.25);
}

.modal-box h4{
    text-align:center;
    margin-bottom:20px;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="top-header">
    RoyalStay Admin Panel

    <div>
        <a href="admin_dashboard.php"><i class="bi bi-arrow-left"></i> Back</a>
        <a href="user/logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Total Cities: <?= $cities->num_rows ?></h4>
        <button class="btn btn-success" onclick="openAdd()">+ Add City</button>
    </div>

    <!-- CITY LIST -->
    <?php
    $icons = ["bi-geo-alt","bi-building","bi-flag","bi-map","bi-geo-fill","bi-pin-map","bi-house","bi-brightness-high"];

    while($c = $cities->fetch_assoc()):
        $icon = $icons[array_rand($icons)];
    ?>
    <div class="city-card">

        <div class="city-left">
            <i class="bi <?= $icon ?>"></i>
            <?= $c['city_name'] ?>
        </div>

        <div>
            <button class="btn btn-warning" onclick="openEdit('<?= $c['id'] ?>','<?= $c['city_name'] ?>')">
                <i class="bi bi-pencil-square"></i> Edit
            </button>

            <button class="btn btn-danger" onclick="deleteCity(<?= $c['id'] ?>)">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>

    </div>
    <?php endwhile; ?>
</div>

<!-- ADD CITY POPUP -->
<div id="cityModal">
    <div class="modal-box">
        <h4>Add New City</h4>

        <form method="POST">
            <label>City Name</label>
            <input type="text" name="new_city" class="form-control mb-3" required>

            <button class="btn btn-success w-100">Add City</button>
        </form>

        <button class="btn btn-danger w-100 mt-2" onclick="closeAdd()">Cancel</button>
    </div>
</div>

<!-- EDIT CITY POPUP -->
<div id="editModal">
    <div class="modal-box">
        <h4>Edit City</h4>

        <form method="POST">
            <input type="hidden" name="edit_city_id" id="edit_id">

            <label>City Name</label>
            <input type="text" name="edit_city_name" id="edit_name" class="form-control mb-3" required>

            <button class="btn btn-primary w-100">Update City</button>
        </form>

        <button class="btn btn-danger w-100 mt-2" onclick="closeEdit()">Cancel</button>
    </div>
</div>

<script>
// ADD CITY popup
function openAdd(){
    document.getElementById("cityModal").style.display="flex";
}
function closeAdd(){
    document.getElementById("cityModal").style.display="none";
}

// EDIT CITY popup
function openEdit(id,name){
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("editModal").style.display="flex";
}
function closeEdit(){
    document.getElementById("editModal").style.display="none";
}

// DELETE with SweetAlert
function deleteCity(id){
    Swal.fire({
        icon:'warning',
        title:'Are you sure?',
        text:'This city will be removed!',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        cancelButtonColor:'#3085d6',
        confirmButtonText:'Yes, delete it'
    }).then((result)=>{
        if(result.isConfirmed){
            window.location = "admin_cities.php?delete="+id;
        }
    });
}

// SWEETALERT SUCCESS POPUPS
if(localStorage.getItem("cityAdded")){
    Swal.fire({ icon:'success', title:'City Added Successfully', timer:2000 });
    localStorage.removeItem("cityAdded");
}

if(localStorage.getItem("cityEdited")){
    Swal.fire({ icon:'success', title:'City Updated Successfully', timer:2000 });
    localStorage.removeItem("cityEdited");
}

if(localStorage.getItem("cityDeleted")){
    Swal.fire({ icon:'success', title:'City Deleted Successfully', timer:2000 });
    localStorage.removeItem("cityDeleted");
}
</script>

</body>
</html>
