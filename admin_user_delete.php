<?php
session_start();
include "config.php";
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== "admin") {
    header("Location: signin.php");
    exit;
}

if (isset($_GET['cust_id'])) {
    $id = intval($_GET['cust_id']);
    // optional: you might want to soft-delete (update status) instead of delete
    $conn->query("DELETE FROM customers WHERE cust_id = $id");
}
header("Location: admin_users.php");
exit;
?>
