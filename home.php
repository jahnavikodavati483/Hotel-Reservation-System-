<?php
session_start();
include 'config.php';

// If user not logged in → go to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: user/signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>RoyalStay - Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .hotel-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.10);
            margin-bottom: 20px;
        }
        .hotel-img {
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }
        .price {
            font-size: 20px;
            font-weight: bold;
            color: green;
        }
        .navbar {
            background: white;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .nav-brand {
            font-size: 28px;
            font-weight: bold;
            color: #0066ff;
            text-decoration: none;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar d-flex justify-content-between">
    <a class="nav-brand">RoyalStay</a>

    <div>
        <span class="me-3 fw-bold text-primary">
            Welcome, <?= $_SESSION['user_name']; ?>
        </span>
        <a href="user/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">

        <!-- LEFT FILTER SECTION -->
        <div class="col-md-3">
            <div class="filter-box">
                <h5 class="fw-bold">Filter By</h5>
                <hr>

                <h6>Star Rating</h6>
                <button class="btn btn-outline-primary btn-sm mb-1">5 Stars</button>
                <button class="btn btn-outline-primary btn-sm mb-1">4 Stars</button>
                <button class="btn btn-outline-primary btn-sm mb-1">3 Stars</button>

                <hr>
                <h6>Price Range</h6>
                <input type="range" class="form-range">

                <hr>
                <h6>Popular Filters</h6>
                <div><input type="checkbox"> Free Parking</div>
                <div><input type="checkbox"> Breakfast Included</div>
                <div><input type="checkbox"> Luxury Hotels</div>
            </div>
        </div>

        <!-- RIGHT HOTEL LIST -->
        <div class="col-md-9">

            <h3 class="mb-3 fw-bold">Top Hotels For You</h3>

            <?php
            // 12 Real hotel images (direct internet)
            $photos = [
                "https://cf.bstatic.com/xdata/images/hotel/square600/506405507.webp?k=38365c23&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/367799088.webp?k=4d8fa4&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/279536449.webp?k=b73f14&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/247453590.webp?k=lad4ff&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/295759932.webp?k=9f3ce2&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/467234217.webp?k=24598a&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/403821369.webp?k=faa9b2&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/278852681.webp?k=cc0a97&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/284951833.webp?k=682ad7&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/285390719.webp?k=3a5e22&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/498912145.webp?k=aa901e&",
                "https://cf.bstatic.com/xdata/images/hotel/square600/340981273.webp?k=4ad51b&"
            ];

            // Hotels array
            $hotels = [
                ["ITC Grand Chola", "Chennai", 16500],
                ["The Leela Palace", "Chennai", 18800],
                ["Trident Hotel", "Chennai", 9200],
                ["Taj Coromandel", "Chennai", 21000],
                ["Hyatt Regency", "Chennai", 14000],
                ["Radisson Blu", "Chennai", 9000],
                ["The Park", "Bangalore", 7500],
                ["Oberoi Hotel", "Bangalore", 27000],
                ["Taj MG Road", "Bangalore", 15000],
                ["Novotel", "Hyderabad", 8500],
                ["ITC Kohenur", "Hyderabad", 19500],
                ["Marriott", "Hyderabad", 13000]
            ];

            for ($i = 0; $i < count($hotels); $i++):
            ?>
            <div class="hotel-card row">
                <div class="col-md-4">
                    <img src="<?= $photos[$i]; ?>" class="hotel-img">
                </div>

                <div class="col-md-6">
                    <h5 class="fw-bold"><?= $hotels[$i][0]; ?></h5>
                    <p>📍 <?= $hotels[$i][1]; ?>, India</p>
                    <p class="text-muted">Luxury room · Free WiFi · Free Parking · Breakfast Available</p>
                </div>

                <div class="col-md-2 text-end">
                    <p class="price">₹ <?= number_format($hotels[$i][2]); ?></p>
                    <a href="#" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
            <?php endfor; ?>
        </div>

    </div>
</div>

</body>
</html>
