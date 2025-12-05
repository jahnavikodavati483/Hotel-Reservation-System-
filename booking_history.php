<?php
require_once __DIR__ . '/header.php';
if (!isset($_SESSION['user_id'])) {
  echo "<div class='alert alert-warning'>Please login to see bookings.</div>";
  require_once __DIR__ . '/footer.php';
  exit;
}
$cust = (int)$_SESSION['user_id'];
$stmt = $conn->prepare("SELECT b.*, h.hotel_name FROM bookings b LEFT JOIN hotels h ON b.hotel_id=h.hotel_id WHERE b.cust_id = ? ORDER BY b.created_at DESC");
$stmt->bind_param('i', $cust);
$stmt->execute();
$res = $stmt->get_result();
$bookings = $res->fetch_all(MYSQLI_ASSOC);
?>
<h2 class="page-title">My Bookings</h2>
<?php if(empty($bookings)): ?>
  <div class="alert alert-light">No bookings yet.</div>
<?php else: ?>
  <?php foreach($bookings as $bk): ?>
    <div class="card mb-3 p-3">
      <div class="d-flex justify-content-between">
        <div>
          <strong><?php e($bk['hotel_name']) ?></strong><br>
          <small class="text-muted">Check-in: <?php e($bk['checkin']) ?> — Check-out: <?php e($bk['checkout']) ?></small>
        </div>
        <div style="text-align:right">
          <div>₹<?php e(number_format($bk['amount'],2)) ?></div>
          <small class="text-muted"><?php e($bk['created_at']) ?></small>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
