<?php
session_start();
include "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location:/user/signin.php");
    exit;
}

$id = $_POST['cust_id'];
$role = $_POST['role'];
$status = $_POST['status'];

$conn->query("UPDATE customers SET role='$role', status='$status' WHERE cust_id=$id");

header("Location: admin_users.php?updated=1");
exit;
?>
