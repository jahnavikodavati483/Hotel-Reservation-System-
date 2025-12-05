<?php
// header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>RoyalStay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--accent:#0d6efd;--muted:#f7f9fb}
body{background:var(--muted);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial}
.navbar-brand{font-weight:700;color:var(--accent)}
.city-card{background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(13,110,253,0.06);padding:28px}
.hotel-card{background:#fff;border-radius:10px;border:1px solid #e9eef6;padding:16px;margin-bottom:18px;display:flex;gap:16px;align-items:center}
.hotel-thumb{width:160px;height:110px;object-fit:cover;border-radius:8px}
.checkout-box{background:#fff;border-radius:8px;padding:16px;border:1px solid #eef3fb}
.page-title{font-size:28px;font-weight:700;color:#0b3a66}
</style>
</head>
<body>
<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand ms-3" href="index.php">RoyalStay</a>
    <div>
      <?php if(isset($_SESSION['user_id'])): ?>
        <span class="me-3">Welcome, <strong><?php echo isset($_SESSION['user_name']) ? e($_SESSION['user_name']) : 'Guest'; ?></strong></span>
        <a class="btn btn-sm btn-danger" href="user/logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn-sm btn-outline-primary" href="user/signin.php">Login</a>
        <a class="btn btn-sm btn-primary ms-2" href="user/signup.php">Sign up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<main class="container my-4">
