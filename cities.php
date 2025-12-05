<?php
// list cities
require_once 'config.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: user/signin.php");
$res = mysqli_query($conn,"SELECT * FROM cities ORDER BY city_name");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><title>Cities - RoyalStay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand">RoyalStay</a>
    <div class="ms-auto"><a href="home.php" class="btn btn-outline-primary btn-sm">Home</a></div>
  </div>
</nav>

<div class="container my-5">
  <h3>Choose city</h3>
  <div class="row g-3 mt-3">
    <?php while($c = mysqli_fetch_assoc($res)): ?>
      <div class="col-md-4">
         <div class="card p-3">
           <h5><?=htmlspecialchars($c['city_name'])?></h5>
           <a href="hotels.php?city_id=<?=$c['city_id']?>" class="btn btn-primary btn-sm mt-2">See Hotels</a>
         </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
