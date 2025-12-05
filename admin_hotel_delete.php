<?php
include "config.php";
$id = intval($_GET['id']);
$conn->query("DELETE FROM hotels WHERE id=$id");
header("Location: admin_hotels.php?deleted=1");
?>
