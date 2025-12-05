<?php  
session_start();
include "config.php";

if (!isset($_GET['id'])) {
    die("Hotel not found");
}

$hotel_id = intval($_GET['id']);

// Fetch hotel
$hotel_sql = "SELECT * FROM hotels WHERE id = $hotel_id LIMIT 1";
$hotel = $conn->query($hotel_sql)->fetch_assoc();

if (!$hotel) { die("Hotel not found"); }

// Fetch rooms
$rooms_sql = "SELECT * FROM rooms WHERE hotel_id = $hotel_id LIMIT 3";
$rooms = $conn->query($rooms_sql);

// Fetch reviews
$reviews_sql = "SELECT * FROM reviews WHERE hotel_id = $hotel_id ORDER BY created_at DESC";
$reviews = $conn->query($reviews_sql);

// 30 images
$allImages = [
    "room1.jpg","room2.jpg","room3.jpg","room4.jpg","room5.jpg","room6.jpg","room7.jpg",
    "room8.jpg","room9.jpg","room10.jpg","room11.jpg","room12.jpg","room13.jpg","room14.jpg",
    "room15.jpg","room16.jpg","room17.jpg","room18.jpg","room19.jpg","room20.jpg","room21.jpg",
    "room22.jpg","room23.jpg","room24.jpg","room25.jpg","room26.jpg","room27.jpg",
    "room28.jpg","room29.jpg","room30.jpg"
];
shuffle($allImages);

// Gallery
$gallery = array_slice($allImages,0,4);

// Auto rooms
$autoRooms = [];
if ($rooms->num_rows == 0) {
    $roomTypes = ["Deluxe Room","Premium Suite","Family Room"];
    $descs = [
        "Comfortable modern furnishing with AC & WiFi.",
        "Luxury suite with minibar & premium interiors.",
        "Ideal for families with balcony view."
    ];
    $prices = [12000,18000,15000];

    for ($i=0; $i<3; $i++) {
        $autoRooms[] = [
            "room_type" => $roomTypes[$i],
            "room_description" => $descs[$i],
            "capacity" => ($i+2),
            "amenities" => "WiFi,AC,Breakfast",
            "room_price" => $prices[$i],
            "room_image" => $allImages[$i]
        ];
    }
}

// Auto reviews
$autoReviews = [];
if ($reviews->num_rows == 0) {
    $names = ["Ananya","Rohit","Meera","Kiran","Vikram"];
    $comments = [
        "Amazing stay, very clean rooms!",
        "Loved the ambience and service.",
        "Great value for money.",
        "Outstanding hospitality!",
        "Perfect for business & leisure."
    ];
    for ($i=0; $i<3; $i++) {
        $autoReviews[] = [
            "reviewer_name" => $names[array_rand($names)],
            "rating" => rand(4,5),
            "comment" => $comments[array_rand($comments)]
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?= $hotel['hotel_name'] ?></title>

<style>
body { font-family: 'Segoe UI'; margin:0; background:#eef2f7; }
.container { width:90%; margin:auto; }

/* --------------- NAVBAR FIXED ---------------- */
.navbar-top{
    background:white;
    padding:12px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #ddd;
}

.nav-right{
    display:flex;
    align-items:center;
    gap:18px;    /* ⭐ the fix */
}

.logout-btn{
    background:#e63946;
    padding:8px 18px;
    border-radius:8px;
    color:white !important;
    font-weight:600;
    text-decoration:none;
}

.admin-btn{
    background:#0056d2;
    padding:8px 20px;
    border-radius:30px;
    color:white !important;
    text-decoration:none;
    font-weight:600;
    box-shadow:0 3px 8px rgba(0,0,0,0.15);
    transition:0.3s;
}
.admin-btn:hover{ background:#003c9e; }

/* content */
.hotel-card {
    background:white; padding:20px 25px;
    border-radius:16px;
    margin-top:20px;
    box-shadow:0 4px 18px rgba(0,0,0,0.1);
}

.gallery-row { display:flex; gap:10px; margin-top:18px; }
.gallery-row img { width:24%; height:150px; border-radius:12px; object-fit:cover; }

.section-title { font-size:26px; font-weight:700; margin:30px 0 15px; }

.room-card {
    display:flex; background:white;
    padding:20px; border-radius:14px;
    margin-bottom:22px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.room-img { width:220px; height:160px; border-radius:12px; object-fit:cover; }

.book-btn {
    background:#0061ff; color:white;
    padding:10px 26px;
    border:none; border-radius:10px;
    text-decoration:none;
    align-self:center;
}

.amenity {
    background:#e9efff; padding:6px 10px;
    border-radius:10px;
    margin-right:5px; font-size:13px;
}

.map-box {
    border-radius:16px; margin-top:20px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.review-card {
    background:white; margin-bottom:15px;
    padding:15px; border-radius:14px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar-top">

    <h3 style="color:#0066ff;font-weight:700;">RoyalStay</h3>

    <div class="nav-right">

        <?php if(isset($_SESSION['user'])){ ?>

            <span class="fw-bold">Hi, <?= $_SESSION['user'] ?></span>

            <?php if($_SESSION['username']=="jahnavikodavati483@gmail.com"){ ?>
                <a href="/admin_dashboard.php" class="admin-btn">Admin</a>
            <?php } ?>

            <a href="/user/logout.php" class="logout-btn">Logout</a>

        <?php } else { ?>

            <a href="/user/signin.php" class="btn btn-primary">Login</a>

        <?php } ?>
    </div>
</div>

<div class="container">

    <!-- Title -->
    <div class="hotel-card">
        <h1><?= $hotel['hotel_name'] ?></h1>
        <p>📍 <?= $hotel['description'] ?></p>
        <p>⭐ <b><?= $hotel['rating'] ?></b></p>
    </div>

    <!-- Gallery -->
    <div class="gallery-row">
        <?php foreach($gallery as $g): ?>
            <img src="images/<?= $g ?>">
        <?php endforeach; ?>
    </div>

    <!-- Rooms -->
    <h2 class="section-title">Available Rooms</h2>

    <?php  
    if ($rooms->num_rows > 0):
        while($rm = $rooms->fetch_assoc()):
    ?>
        <div class="room-card">
            <img class="room-img" src="images/<?= $rm['room_image'] ?>">

            <div style="flex:1; margin-left:20px;">
                <h3><?= $rm['room_type'] ?></h3>
                <p><?= $rm['room_description'] ?></p>
                <p><b>👥 Capacity:</b> <?= $rm['capacity'] ?> guests</p>

                <?php foreach(explode(",", $rm['amenities']) as $am): ?>
                    <span class="amenity"><?= trim($am) ?></span>
                <?php endforeach; ?>

                <p style="color:#0061ff; font-size:18px; margin-top:10px;">
                    ₹<?= number_format($rm['room_price']) ?> / night
                </p>
            </div>

            <a href="bookings.php?hotel_id=<?= $hotel_id ?>&room_id=<?= $rm['room_id'] ?>" class="book-btn">Book</a>
        </div>

    <?php  
        endwhile;
    else:
        foreach($autoRooms as $rm):
    ?>
        <div class="room-card">
            <img class="room-img" src="images/<?= $rm['room_image'] ?>">

            <div style="flex:1; margin-left:20px;">
                <h3><?= $rm['room_type'] ?></h3>
                <p><?= $rm['room_description'] ?></p>
                <p><b>👥 Capacity:</b> <?= $rm['capacity'] ?> guests</p>

                <span class="amenity">WiFi</span>
                <span class="amenity">AC</span>
                <span class="amenity">Breakfast</span>

                <p style="color:#0061ff; font-size:18px; margin-top:10px;">
                    ₹<?= number_format($rm['room_price']) ?> / night
                </p>
            </div>

            <a href="bookings.php?hotel_id=<?= $hotel_id ?>&room_id=0" class="book-btn">Book</a>
        </div>
    <?php endforeach; endif; ?>

    <!-- Map -->
    <h2 class="section-title">Hotel Location</h2>
    <div class="map-box">
        <iframe width="100%" height="350"
        src="https://www.google.com/maps?q=<?= $hotel['lat'] ?>,<?= $hotel['lng'] ?>&z=15&output=embed"></iframe>
    </div>

    <!-- Reviews -->
    <h2 class="section-title">Guest Reviews</h2>

    <?php if ($reviews->num_rows > 0): ?>
        <?php while($rev = $reviews->fetch_assoc()): ?>
            <div class="review-card">
                <p><b><?= $rev['reviewer_name'] ?></b> — ⭐ <?= $rev['rating'] ?></p>
                <p><?= $rev['comment'] ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <?php foreach($autoReviews as $rev): ?>
            <div class="review-card">
                <p><b><?= $rev['reviewer_name'] ?></b> — ⭐ <?= $rev['rating'] ?></p>
                <p><?= $rev['comment'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
