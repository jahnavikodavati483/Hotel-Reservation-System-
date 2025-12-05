<?php
session_start();
include "config.php";

// Block non-admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: user/signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #eef2f7;
    font-family: 'Poppins', sans-serif;
    margin: 0;
}

/* UNIVERSAL ADMIN TOPBAR */
.admin-topbar {
    background: #0056d2;
    padding: 16px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

.admin-topbar h3 {
    color: white;
    margin: 0;
    font-weight: 700;
}

/* ONLY BACK + LOGOUT */
.admin-actions {
    display: flex;
    gap: 12px;
}

.btn-back {
    background: white;
    color: #0056d2;
    padding: 7px 16px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
}

.btn-logout {
    background: #e63946;
    color: white;
    padding: 7px 16px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="admin-topbar">
    <h3>RoyalStay Admin Panel</h3>

    <div class="admin-actions">
        <a href="admin_dashboard.php" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>

        <a href="user/logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="container mt-4">
