<?php
session_start();
include "config.php";

// Block non-admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: user/signin.php");
    exit;
}

// Fetch hotels + city name
$sql = "SELECT hotels.*, cities.city_name 
        FROM hotels
        JOIN cities ON hotels.city_id = cities.id
        ORDER BY hotels.id DESC";
$hotels = $conn->query($sql);

// Count hotels
$hotelCount = $conn->query("SELECT COUNT(*) AS c FROM hotels")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Hotels - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
.logout-button {
    text-decoration: none !important;
}

        body {
            background: #eef2f7;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-admin {
            background: #0056d2;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-admin h3 {
            color: white;
            font-weight: 700;
        }

        .back-btn {
            background: #ffffff;
            color: #0056d2;
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid #c9d7f2;
        }

        .logout-btn {
            background: #ff4d4d !important;
            color: white !important;
            padding: 6px 18px;
            border-radius: 8px;
        }

        .box-header {
            background: #d6e2ff;
            padding: 15px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            color: #003b99;
        }

        .hotel-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 22px;
            justify-content: space-between;
        }

        .hotel-img {
            width: 250px;
            height: 170px;
            object-fit: cover;
            border-radius: 12px;
        }

        .hotel-details {
            flex-grow: 1;
        }

        .hotel-city-tag {
            background: #d6eaff;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            color: #0056d2;
            font-weight: 600;
            display: inline-block;
        }

        .feature-tag {
            background: #f1f3f5;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            margin-right: 5px;
            display: inline-block;
        }

        .price-text {
            font-size: 20px;
            font-weight: 700;
            color: #0056d2;
        }

        /* BUTTONS RIGHT SIDE */
        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-edit {
            background: #007bff !important;
            color: white !important;
            font-weight: 600;
        }

        .btn-delete {
            background: #e63946 !important;
            color: white !important;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="navbar-admin">
        <h3>RoyalStay Admin Panel</h3>

        <div>
            <a href="admin_dashboard.php" class="back-btn">← Back</a>
            <a href="user/logout.php" class="btn btn-danger logout-button">Logout</a>

        </div>
    </div>

    <div class="container mt-4">

        <div class="box-header">
            Total Hotels: <?= $hotelCount ?>
        </div>

        <a href="admin_hotel_add.php" class="btn btn-success mt-3 mb-3">+ Add Hotel</a>

        <?php while ($h = $hotels->fetch_assoc()): ?>

            <div class="hotel-card">

                <!-- IMAGE -->
                <img src="images/<?= htmlspecialchars($h['image']) ?>"
                     class="hotel-img"
                     onerror="this.src='images/default.jpg'">

                <!-- DETAILS -->
                <div class="hotel-details">
                    <h4><?= htmlspecialchars($h['hotel_name']) ?></h4>

                    <span class="hotel-city-tag">
                        📍 Prime Location, <?= $h['city_name'] ?>
                    </span>

                    <p class="mt-2"><?= htmlspecialchars($h['description']) ?></p>

                    <div class="mt-1">
                        <span class="feature-tag">Free Wi-Fi</span>
                        <span class="feature-tag">Breakfast</span>
                        <span class="feature-tag">A/C</span>
                    </div>

                    <p class="price-text mt-2">₹<?= number_format($h['price']) ?> / night</p>
                </div>

                <!-- ACTION BUTTONS (RIGHT SIDE) -->
                <div class="actions">
                    <a href="admin_hotel_edit.php?id=<?= $h['id'] ?>" class="btn btn-edit">✏ Edit</a>
                    <a href="admin_hotel_delete.php?id=<?= $h['id'] ?>" class="btn btn-delete">🗑 Delete</a>
                </div>

            </div>

        <?php endwhile; ?>

    </div>

</body>
</html>
