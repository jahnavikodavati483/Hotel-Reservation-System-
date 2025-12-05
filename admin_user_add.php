<?php
session_start();
include "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:/user/signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add User - RoyalStay</title>

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

.error-text{
    color:#d00000;
    font-size:14px;
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
    <h2>Add User</h2>
    <a href="admin_users.php"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="form-container">

<form id="userForm" method="POST" action="admin_user_add_save.php">

    <!-- NAME -->
    <label class="fw-bold">Name</label>
    <div class="position-relative mb-2">
        <i class="bi bi-person-fill" style="position:absolute; left:14px; top:15px; font-size:20px; color:#0066ff;"></i>
        <input type="text" name="name" id="nameInput" class="form-control" style="padding-left:45px;">
    </div>
    <div id="nameErr" class="error-text mb-2"></div>

    <!-- EMAIL -->
    <label class="fw-bold">Email</label>
    <div class="position-relative mb-2">
        <i class="bi bi-envelope-fill" style="position:absolute; left:14px; top:15px; font-size:20px; color:#0066ff;"></i>
        <input type="email" name="email" id="emailInput" class="form-control" style="padding-left:45px;">
    </div>
    <div id="emailErr" class="error-text mb-2"></div>

    <!-- PASSWORD -->
    <label class="fw-bold">Password</label>
    <div class="position-relative mb-3">

        <i class="bi bi-shield-lock-fill"
            style="position:absolute; left:14px; top:15px; font-size:20px; color:#0066ff;"></i>

        <input type="password" name="password" id="passInput" class="form-control"
               style="padding-left:45px; padding-right:55px;">

        <i class="bi bi-eye-slash" id="togglePass"
            style="position:absolute; right:15px; top:15px; font-size:20px; cursor:pointer; color:#444;"></i>

    </div>
    <div id="passErr" class="error-text mb-3"></div>

    <button class="btn btn-create w-100">Create User</button>

</form>

</div>

<script>
// Toggle Password Visibility
document.getElementById("togglePass").onclick = function(){
    let p = document.getElementById("passInput");

    if(p.type === "password"){
        p.type = "text";
        this.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        p.type = "password";
        this.classList.replace("bi-eye", "bi-eye-slash");
    }
};

// Final VALIDATION + SWEETALERT + SUBMIT
document.getElementById("userForm").addEventListener("submit", function(e){

    e.preventDefault();

    let nameVal = document.getElementById("nameInput").value.trim();
    let emailVal = document.getElementById("emailInput").value.trim();
    let passVal = document.getElementById("passInput").value.trim();

    let valid = true;

    document.getElementById("nameErr").innerHTML =
        nameVal.length < 3 ? (valid=false, "Enter valid name") : "";

    document.getElementById("emailErr").innerHTML =
        (!emailVal.includes("@")) ? (valid=false, "Enter valid email") : "";

    document.getElementById("passErr").innerHTML =
        passVal.length < 6 ? (valid=false, "Password must be at least 6 characters") : "";

    if(!valid) return;

    Swal.fire({
        icon:"success",
        title:"User Created!",
        text:"Saving user...",
        confirmButtonColor:"#0066ff"
    }).then(() => {
        document.getElementById("userForm").submit();
    });
});
</script>

</body>
</html>
