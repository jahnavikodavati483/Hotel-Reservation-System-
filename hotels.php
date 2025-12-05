<?php
session_start();
include "config.php";

// GET CITY ID
$city_id = isset($_GET['city']) ? intval($_GET['city']) : 0;

// SORT VALUE
$sort = $_GET['sort'] ?? "popular";

// -------------------------------------
// FETCH CITY NAME
// -------------------------------------
$city_name = "";
$cityQuery = $conn->query("SELECT city_name FROM cities WHERE id=$city_id");
if ($cityQuery->num_rows) {
    $city_name = $cityQuery->fetch_assoc()['city_name'];
}

// -------------------------------------
// FETCH HOTELS (AUTO DISPLAY NEW HOTELS)
// -------------------------------------

$sql = "SELECT * FROM hotels WHERE city_id = $city_id";

// APPLY SORT
if ($sort == "price_low"){
    $sql .= " ORDER BY price ASC";
}
else if ($sort == "price_high"){
    $sql .= " ORDER BY price DESC";
}
else if ($sort == "rating"){
    $sql .= " ORDER BY rating DESC";
}
else {
    $sql .= " ORDER BY id ASC";  // Popular
}

$hotels = $conn->query($sql);

// --------------------------------------------
// AUTO-ASSIGN RANDOM IMAGE IF IMAGE IS EMPTY
// --------------------------------------------
$randomImages = [];
for ($i = 1; $i <= 30; $i++){
    $randomImages[] = "room$i.jpg";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hotels in <?= htmlspecialchars($city_name) ?> - RoyalStay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body{
        background:#eef2f7;
        font-family:'Segoe UI', sans-serif;
    }
    .navbar-top{
        background:white;
        padding:12px 40px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom:1px solid #ddd;
    }
    .admin-btn{
        background:#0056d2;
        padding:7px 20px;
        border-radius:30px;
        color:white !important;
        text-decoration:none;
        margin-right:15px;
        font-weight:600;
    }
    .logout-btn{
        background:#e63946;
        padding:6px 15px;
        border-radius:8px;
        color:white !important;
        text-decoration:none;
    }
    .page-container{
        width:90%; max-width:1100px; margin:auto;
    }
    .title-box{
        background:white;
        padding:22px;
        border-radius:16px;
        margin-top:25px;
        box-shadow:0 4px 15px rgba(0,0,0,0.1);
    }
    .sort-btn{
        background:#f2f4fa;
        padding:6px 14px;
        border-radius:10px;
        border:1px solid #d5d9e2;
        text-decoration:none;
        color:#333;
        transition:0.2s;
        font-size:14px;
    }
    .sort-btn.active{
        background:#0066ff;
        color:white;
    }
    .hotel-card{
        background:white;
        display:flex;
        gap:20px;
        padding:18px;
        border-radius:16px;
        margin-top:20px;
        box-shadow:0 4px 18px rgba(0,0,0,0.09);
    }
    .hotel-img{
        width:240px; height:160px; object-fit:cover; border-radius:12px;
    }
    .feature{
        background:#eef3ff;
        padding:5px 12px;
        border-radius:20px;
        font-size:12px;
        margin-right:5px;
    }
    .price{
        color:#0066ff;
        font-size:20px;
        font-weight:bold;
    }
    .rating-badge{
        background:#0066ff;
        color:white;
        padding:6px 14px;
        font-size:15px;
        border-radius:14px;
    }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-top">
    <h3 style="color:#0066ff; font-weight:700;">RoyalStay</h3>

    <div style="display:flex; align-items:center;">
        <?php if(isset($_SESSION['user'])){ ?>

            <span class="fw-bold me-3">Hi, <?= htmlspecialchars($_SESSION['user']) ?></span>

            <?php if(isset($_SESSION['role']) && $_SESSION['role']=="admin"){ ?>
                <a href="/admin_dashboard.php" class="admin-btn">Admin</a>
            <?php } ?>

            <a href="/user/logout.php" class="logout-btn">Logout</a>

        <?php } else { ?>
            <a href="/user/signin.php" class="btn btn-primary">Login</a>
        <?php } ?>
    </div>
</div>

<!-- MAIN PAGE -->
<div class="page-container">

    <div class="title-box">
        <h2 style="font-weight:700;">Hotels in 
            <span style="color:black"><?= htmlspecialchars($city_name) ?></span>
        </h2>

        <p style="color:#666;">Discover curated premium rooms, verified ratings & comfortable stays.</p>
    </div>

    <!-- SORT BUTTONS -->
    <div style="margin-top:10px;">
        <b>Sort:</b>
        <a href="?city=<?= $city_id ?>&sort=popular" class="sort-btn <?= $sort=='popular'?'active':'' ?>">⭐ Popular</a>
        <a href="?city=<?= $city_id ?>&sort=price_low" class="sort-btn <?= $sort=='price_low'?'active':'' ?>">₹ Low → High</a>
        <a href="?city=<?= $city_id ?>&sort=price_high" class="sort-btn <?= $sort=='price_high'?'active':'' ?>">₹ High → Low</a>
        <a href="?city=<?= $city_id ?>&sort=rating" class="sort-btn <?= $sort=='rating'?'active':'' ?>">🌟 Rating</a>
    </div>

    <!-- HOTEL LOOP -->
    <?php while($h = $hotels->fetch_assoc()): ?>

        <?php
        // Assign random image if missing
        $final_image = $h['image'];
        if (!$final_image || trim($final_image)==""){
            $final_image = $randomImages[array_rand($randomImages)];
        }
        ?>

        <div class="hotel-card">
            <img src="images/<?= htmlspecialchars($final_image) ?>" class="hotel-img">

            <div style="flex:1;">
                <h4><?= htmlspecialchars($h['hotel_name']) ?></h4>
                <p>📍 Prime Location, <?= htmlspecialchars($city_name) ?></p>
                <p><?= htmlspecialchars($h['description']) ?></p>

                <div>
                    <span class="feature">Free Wi-Fi</span>
                    <span class="feature">Breakfast</span>
                    <span class="feature">A/C</span>
                </div>

                <p class="price mt-2">₹<?= number_format($h['price']) ?> / night</p>
            </div>

            <div style="text-align:right;">
                <span class="rating-badge">⭐ <?= htmlspecialchars($h['rating']) ?></span><br><br>
                <a href="hotel_details.php?id=<?= intval($h['id']) ?>" class="btn btn-primary">View</a>
            </div>
        </div>

    <?php endwhile; ?>

</div>

</body>
</html>
