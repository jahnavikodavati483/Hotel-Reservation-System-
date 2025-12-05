<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// After login, redirect user to cities page
header("Location: cities.php");
exit();
?>
