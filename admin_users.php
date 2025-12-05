<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location:/user/signin.php");
    exit;
}

/* FILTER */
$filter = "";
if (isset($_GET['filter'])) {
    if ($_GET['filter'] == "active")  $filter = "WHERE status='ACTIVE'";
    if ($_GET['filter'] == "inactive") $filter = "WHERE status='INACTIVE'";
}

$query = "SELECT * FROM customers $filter ORDER BY cust_id ASC";
$users = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
<title>User Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    background:#f4f6f9;
    font-family:"Segoe UI";
}
.topbar{
    background:white;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #dcdcdc;
}
.heading{
    font-size:32px;
    font-weight:700;
    color:#222;
}
.btn-top{
    padding:8px 16px;
    border-radius:8px;
    font-weight:600;
    margin-left:10px;
    text-decoration:none;
}
.btn-dash{ background:#007bff; color:white; }
.btn-add{ background:#00b16a; color:white; }
.btn-logout{ background:#ff4b4b; color:white; }

.filter-box{
    margin:25px 0;
    text-align:center;
}
.filter-btn{
    padding:8px 20px;
    border-radius:8px;
    border:1px solid #005ad2;
    color:#005ad2;
    margin:0 5px;
    font-weight:600;
    text-decoration:none;
}
.filter-btn.active{
    background:#005ad2;
    color:white;
}

.table-box{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    width:90%;
    margin:auto;
}

.user-img{
    width:40px;
    height:40px;
    border-radius:50%;
    background:#d9e4ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    color:#0045c7;
    margin-right:10px;
}
.action-icon{
    font-size:20px;
    cursor:pointer;
    margin-left:10px;
}
.action-delete{ color:#ff4b4b; }
</style>
</head>

<body>

<!-- TOP BAR -->
<div class="topbar">
    <div class="heading">User Management</div>
    <div>
        <a href="admin_dashboard.php" class="btn-top btn-dash">Dashboard</a>
        <a href="admin_user_add.php" class="btn-top btn-add">+ Add User</a>
        <a href="/user/logout.php" class="btn-top btn-logout">Logout</a>
    </div>
</div>

<!-- FILTERS -->
<div class="filter-box">
    <a href="admin_users.php" class="filter-btn <?= !isset($_GET['filter']) ? 'active':'' ?>">All</a>
    <a href="admin_users.php?filter=active" class="filter-btn <?= (isset($_GET['filter']) && $_GET['filter']=='active') ? 'active':'' ?>">Active</a>
    <a href="admin_users.php?filter=inactive" class="filter-btn <?= (isset($_GET['filter']) && $_GET['filter']=='inactive') ? 'active':'' ?>">Inactive</a>
</div>

<!-- TABLE -->
<div class="table-box">
<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php 
    $sl = 1;
    while($u = $users->fetch_assoc()): 
    ?>
    <tr>
        <td><?= $sl++ ?></td>

        <!-- USER ICON + NAME -->
        <td>
            <div style="display:flex;align-items:center;">
                <div class="user-img">👤</div>
                <?= htmlspecialchars($u['name']) ?>
            </div>
        </td>

        <td><?= htmlspecialchars($u['email']) ?></td>

        <!-- FORM START -->
        <form method="POST" action="admin_user_update.php">
        <input type="hidden" name="cust_id" value="<?= $u['cust_id'] ?>">

        <!-- ROLE -->
        <td>
            <select name="role" class="form-select">
                <option value="user" <?= $u['role']=="user"?"selected":"" ?>>User</option>
                <option value="admin" <?= $u['role']=="admin"?"selected":"" ?>>Admin</option>
            </select>
        </td>

        <!-- STATUS -->
        <td>
            <select name="status" class="form-select">
                <option value="ACTIVE" <?= $u['status']=="ACTIVE"?"selected":"" ?>>Active</option>
                <option value="INACTIVE" <?= $u['status']=="INACTIVE"?"selected":"" ?>>Inactive</option>
            </select>
        </td>

        <!-- ACTION BUTTON -->
        <td>
            <button class="btn btn-primary btn-sm" type="submit">Update</button>

            <!-- DELETE ICON -->
            <span onclick="delUser(<?= $u['cust_id'] ?>)" class="action-icon action-delete">❌</span>
        </td>
        </form>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<script>
function delUser(id){
    Swal.fire({
        title: "Delete User?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        confirmButtonColor: "#ff4b4b"
    }).then((res)=>{
        if(res.isConfirmed){
            window.location = "admin_user_delete.php?cust_id=" + id;
        }
    });
}

/* SHOW SUCCESS MESSAGE AFTER UPDATE */
<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
Swal.fire({
    title: "Updated!",
    text: "User details updated successfully.",
    icon: "success",
    timer: 2000,
    showConfirmButton: false
});
<?php endif; ?>
</script>

</body>
</html>
