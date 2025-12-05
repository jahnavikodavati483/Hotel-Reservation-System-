<?php
session_start();
include "config.php";

$q = $conn->query("
    SELECT f.*, h.hotel_name, u.username 
    FROM feedback f
    LEFT JOIN hotels h ON f.hotel_id = h.id
    LEFT JOIN customers u ON f.user_id = u.id
    ORDER BY f.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>All Reviews – Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h2 class="mb-4">Customer Reviews</h2>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Hotel</th>
        <th>Rating</th>
        <th>Review</th>
        <th>Date</th>
    </tr>

<?php while($r = $q->fetch_assoc()): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['username'] ?></td>
    <td><?= $r['hotel_name'] ?></td>
    <td><?= $r['rating'] ?> ⭐</td>
    <td><?= $r['comments'] ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
