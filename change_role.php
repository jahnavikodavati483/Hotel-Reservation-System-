<?php
session_start();
include "config.php";

if (!isset($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'admin') {
    die("ACCESS DENIED");
}

if (!isset($_POST['cust_id']) || !isset($_POST['role'])) {
    die("Invalid request");
}

$id = intval($_POST['cust_id']);
$role = $_POST['role'] == 'admin' ? 'admin' : 'user';

$conn->query("UPDATE customers SET role='$role' WHERE cust_id=$id");

header("Location: admin_users.php");
exit();
?>
