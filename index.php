<?php
session_start();
include "config.php";

// Fetch dynamically added cities (only cities with id > 6)
$newCities = $conn->query("SELECT * FROM cities WHERE id > 6 ORDER BY id ASC");

// Fixed icon cycle for new cities
$iconList = [
    "fa-solid fa-tree-city",
    "fa-solid fa-building",
    "fa-solid fa-mountain-city",
    "fa-solid fa-city",
    "fa-solid fa-map-location-dot"
];
?>
<!DOCTYPE html>
<html>
<head>
<title>RoyalStay – Find Hotels</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body {
    background: url('https://images.unsplash.com/photo-1590490360182-c31d21fd1271?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat fixed;
    font-family: 'Poppins', sans-serif;
}

/* Overlay */
.overlay {
    background: rgba(255, 255, 255, 0.55);
    min-height: 100vh;
    padding-bottom: 50px;
}

/* Navbar */
.navbar {
    background: rgba(255,255,255,0.9);
    box-shadow: 0px 3px 10px rgba(0,0,0,0.1);
}

.navbar-brand {
    font-size: 26px;
    font-weight: 700;
    color: #0056d2 !important;
    text-decoration:none !important;
}

/* Logout Button */
.logout-btn {
    background: #e63946;
    padding: 6px 15px;
    border-radius: 8px;
    color: white !important;
    text-decoration: none !important;
    border: none;
    margin-left: 15px;
}

/* Admin Button – PREMIUM */
.admin-btn {
    background: #0056d2;
    padding: 7px 20px;
    border-radius: 30px;
    color: white !important;
    text-decoration: none !important;
    font-weight: 600;
    margin-left: 15px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    transition: 0.25s ease;
}

.admin-btn:hover {
    background: #003c9e;
    box-shadow: 0 5px 12px rgba(0,0,0,0.25);
}

/* Title Section */
.header-title {
    font-size: 48px;
    font-weight: 700;
    color: #222;
    margin-top: 70px;
    text-align: center;
}

.header-sub {
    text-align: center;
    font-size: 18px;
    opacity: 0.8;
    margin-top: 10px;
}

/* City Cards */
.city-card {
    background: white;
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    border: 1px solid #eaeaea;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.08);
    transition: 0.25s ease;
}

.city-card:hover {
    transform: translateY(-7px);
    box-shadow: 0px 12px 25px rgba(0,0,0,0.15);
}

.city-icon {
    font-size: 42px;
    color: #0056d2;
    margin-bottom: 12px;
}

/* Button */
.city-btn {
    background: #0056d2;
    color: white;
    border-radius: 25px;
    border: none;
    padding: 8px 22px;
    font-weight: 600;
}

.city-btn:hover {
    background: #003d99;
}

</style>
</head>

<body>

<div class="overlay">

<!-- NAVBAR -->
<nav class="navbar p-3">
    <div class="container-fluid">

        <a class="navbar-brand" href="index.php">RoyalStay</a>

        <?php if(isset($_SESSION['user'])) { ?>
        <div style="display:flex; align-items:center;">

            <span class="fw-bold me-3">Hi, <?php echo $_SESSION['user']; ?></span>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
                <a href="/admin_dashboard.php" class="admin-btn">Admin</a>
            <?php } ?>

            <a href="/user/logout.php" class="logout-btn">Logout</a>

        </div>
        <?php } ?>

    </div>
</nav>

<!-- HEADINGS -->
<h1 class="header-title">Find Your Perfect Stay</h1>
<p class="header-sub">Choose a city and explore beautiful premium hotels</p>

<!-- FIXED 6 CITY GRID -->
<div class="container mt-5">
    <div class="row g-4 justify-content-center">

        <!-- Original 6 Cities remain unchanged -->
        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-location-dot city-icon"></i>
                <h5>Bengaluru</h5>
                <a href="hotels.php?city=1" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-city city-icon"></i>
                <h5>Chennai</h5>
                <a href="hotels.php?city=2" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-earth-asia city-icon"></i>
                <h5>Delhi</h5>
                <a href="hotels.php?city=3" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-map city-icon"></i>
                <h5>Goa</h5>
                <a href="hotels.php?city=4" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-map-pin city-icon"></i>
                <h5>Hyderabad</h5>
                <a href="hotels.php?city=5" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="fa-solid fa-house city-icon"></i>
                <h5>Mumbai</h5>
                <a href="hotels.php?city=6" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>

        <!-- NEW DYNAMIC CITIES -->
        <?php
        $i = 0;
        while($city = $newCities->fetch_assoc()):
            $icon = $iconList[$i % count($iconList)];
            $i++;
        ?>
        <div class="col-md-4 col-lg-3">
            <div class="city-card">
                <i class="<?php echo $icon; ?> city-icon"></i>
                <h5><?php echo $city['city_name']; ?></h5>
                <a href="hotels.php?city=<?php echo $city['id']; ?>" class="btn city-btn mt-2">Browse Hotels</a>
            </div>
        </div>
        <?php endwhile; ?>

    </div>
</div>

</div>
</body>
</html>
