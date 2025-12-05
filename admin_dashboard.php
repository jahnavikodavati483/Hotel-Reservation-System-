<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location:/user/signin.php");
    exit;
}

/* =========================
   FETCH BASIC COUNTS
========================= */
$total_users = $conn->query("SELECT COUNT(*) AS c FROM customers")->fetch_assoc()['c'];
$active_users = $conn->query("SELECT COUNT(*) AS c FROM customers WHERE status='ACTIVE'")->fetch_assoc()['c'];
$inactive_users = $conn->query("SELECT COUNT(*) AS c FROM customers WHERE status='INACTIVE'")->fetch_assoc()['c'];
$total_cities = $conn->query("SELECT COUNT(*) AS c FROM cities")->fetch_assoc()['c'];

/* =========================
   BOOKINGS PER DAY (LAST 7 DAYS)
========================= */
$day_labels = [];
$day_counts = [];

$q1 = $conn->query("
    SELECT DATE(created_at) AS d, COUNT(*) AS c
    FROM bookings
    WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY
    GROUP BY DATE(created_at)
    ORDER BY d ASC
");

while ($r = $q1->fetch_assoc()) {
    $day_labels[] = $r['d'];
    $day_counts[] = $r['c'];
}

/* =========================
   BOOKINGS PER HOUR (TODAY)
========================= */
$hour_labels = [];
$hour_counts = [];

$q2 = $conn->query("
    SELECT HOUR(created_at) AS h, COUNT(*) AS c
    FROM bookings
    WHERE DATE(created_at) = CURDATE()
    GROUP BY HOUR(created_at)
    ORDER BY h ASC
");

while ($r = $q2->fetch_assoc()) {
    $hour_labels[] = $r['h'] . ':00';
    $hour_counts[] = $r['c'];
}

/* =========================
   BOOKING STATUS PIE
========================= */
$booked = $conn->query("SELECT COUNT(*) AS c FROM bookings WHERE status='Booked'")->fetch_assoc()['c'];
$cancelled = $conn->query("SELECT COUNT(*) AS c FROM bookings WHERE status='Cancelled'")->fetch_assoc()['c'];

/* =========================
   CITY-WISE HOTEL COUNT
========================= */
$city_labels = [];
$city_hotel_counts = [];

$q3 = $conn->query("
    SELECT cities.city_name, COUNT(hotels.id) AS c
    FROM cities
    LEFT JOIN hotels ON hotels.city_id = cities.id
    GROUP BY cities.city_name
");

while ($r = $q3->fetch_assoc()) {
    $city_labels[] = $r['city_name'];
    $city_hotel_counts[] = $r['c'];
}

/* =========================
   RECENT HOTELS
========================= */
$recent_hotels = $conn->query("SELECT * FROM hotels ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - RoyalStay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    background: #f6f9ff;
    font-family: "Segoe UI";
}

/* Sidebar */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #0a4fff;
    position: fixed;
    left:0;
    top:0;
    padding-top:20px;
    color:white;
}
.sidebar h2 {
    text-align:center;
    font-weight:bold;
}
.sidebar a {
    display:flex;
    align-items:center;
    padding:14px 25px;
    color:white;
    text-decoration:none;
    font-size:18px;
}
.sidebar a:hover {
    background:#0057e7;
}
.sidebar a i {
    margin-right:12px;
}

/* main content */
.main {
    margin-left:260px;
    padding:35px;
}

/* cards */
.card-box {
    background:white;
    padding:25px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 3px 12px rgba(0,0,0,0.1);
}
.card-num {
    font-size:42px;
    font-weight:bold;
    color:#0057e7;
}
.card-title {
    font-size:17px;
    font-weight:600;
}

/* widgets */
.widget-box {
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 3px 12px rgba(0,0,0,0.07);
    height:350px;
}

.hotel-card {
    background:white;
    padding:12px;
    border-radius:14px;
    margin-bottom:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}
.hotel-card img {
    width:100%;
    height:110px;
    border-radius:10px;
    object-fit:cover;
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>RoyalStay</h2>
    <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i>Dashboard</a>
    <a href="admin_users.php"><i class="bi bi-people-fill"></i>Users</a>
    <a href="admin_cities.php"><i class="bi bi-geo-alt-fill"></i>Cities</a>
    <a href="admin_hotels.php"><i class="bi bi-building"></i>Hotels</a>
    <a href="/user/logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h1 class="mb-4">Analytics Dashboard</h1>

<!-- top cards -->
<div class="row g-4">
    <div class="col-md-3"><a href="admin_users.php" style="text-decoration:none;">
        <div class="card-box">
            <div class="card-num"><?= $total_users ?></div>
            <div class="card-title">Total Users</div>
        </div></a>
    </div>

    <div class="col-md-3"><a href="admin_users.php?filter=active" style="text-decoration:none;">
        <div class="card-box">
            <div class="card-num"><?= $active_users ?></div>
            <div class="card-title">Active Users</div>
        </div></a>
    </div>

    <div class="col-md-3"><a href="admin_users.php?filter=inactive" style="text-decoration:none;">
        <div class="card-box">
            <div class="card-num"><?= $inactive_users ?></div>
            <div class="card-title">Inactive Users</div>
        </div></a>
    </div>

    <div class="col-md-3"><a href="admin_cities.php" style="text-decoration:none;">
        <div class="card-box">
            <div class="card-num"><?= $total_cities ?></div>
            <div class="card-title">Total Cities</div>
        </div></a>
    </div>
</div>

<!-- charts row 1 -->
<div class="row mt-5 g-4">

    <div class="col-md-6">
        <div class="widget-box">
            <h5>Bookings Per Day (Last 7 Days)</h5>
            <canvas id="bookDay"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget-box">
            <h5>Bookings Per Hour (Today)</h5>
            <canvas id="bookHour"></canvas>
        </div>
    </div>

</div>

<!-- charts row 2 -->
<div class="row mt-4 g-4">

    <div class="col-md-6">
        <div class="widget-box">
            <h5>Booking Status</h5>
            <canvas id="bookPie"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget-box">
            <h5>City-wise Hotel Count</h5>
            <canvas id="cityHotels"></canvas>
        </div>
    </div>

</div>

<!-- recent hotels -->
<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="widget-box" style="height:auto;">
            <h5>Recently Added Hotels</h5>
            <?php while($h = $recent_hotels->fetch_assoc()): ?>
                <div class="hotel-card">
                    <img src="images/<?= $h['image'] ?>">
                    <h6 class="mt-2"><?= $h['hotel_name'] ?></h6>
                    <small>₹ <?= $h['price'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget-box" style="height:auto;">
            <h5>Activity Timeline</h5>
            <ul class="list-group mt-3">
                <li class="list-group-item">User Bookings updated</li>
                <li class="list-group-item">Hotels added recently</li>
                <li class="list-group-item">Cities updated</li>
                <li class="list-group-item">User activity logged</li>
            </ul>
        </div>
    </div>
</div>

</div>

<!-- ========== CHART JS ========== -->
<script>
/* Bookings Per Day */
new Chart(document.getElementById('bookDay'), {
    type:'line',
    data:{
        labels: <?= json_encode($day_labels) ?>,
        datasets:[{
            label:'Bookings',
            data: <?= json_encode($day_counts) ?>,
            borderColor:'#0057e7',
            backgroundColor:'rgba(0,87,231,0.15)',
            borderWidth:3,
            tension:0.4
        }]
    }
});

/* Bookings Per Hour */
new Chart(document.getElementById('bookHour'), {
    type:'bar',
    data:{
        labels: <?= json_encode($hour_labels) ?>,
        datasets:[{
            label:'Bookings',
            data: <?= json_encode($hour_counts) ?>,
            backgroundColor:'#4c8dfc'
        }]
    }
});

/* Booking Pie */
new Chart(document.getElementById('bookPie'), {
    type:'pie',
    data:{
        labels:['Booked','Cancelled'],
        datasets:[{
            data:[<?= $booked ?>, <?= $cancelled ?>],
            backgroundColor:['#0057e7','#ff4d4d']
        }]
    }
});

/* City-wise hotel count */
new Chart(document.getElementById('cityHotels'), {
    type:'bar',
    data:{
        labels: <?= json_encode($city_labels) ?>,
        datasets:[{
            label:'Hotels',
            data: <?= json_encode($city_hotel_counts) ?>,
            backgroundColor:'#0057e7'
        }]
    }
});
</script>

</body>
</html>
