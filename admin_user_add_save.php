<?php
session_start();
include "config.php";

// Make sure mysqli doesn't throw uncaught exceptions
mysqli_report(MYSQLI_REPORT_OFF);

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location:/user/signin.php");
    exit;
}

// Read + sanitize input
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Basic server-side validation
if (strlen($name) < 3 || strlen($password) < 6 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // validation failed
    header("Location: admin_user_add.php?error=1");
    exit;
}

try {
    // 1) Check duplicate email (prepared)
    $stmt = $conn->prepare("SELECT cust_id FROM customers WHERE email = ?");
    if (!$stmt) {
        header("Location: admin_user_add.php?error=4");
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: admin_user_add.php?error=2");
        exit;
    }
    $stmt->close();

    // 2) Insert user (prepared + hash)
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $status = 'ACTIVE';

    $ins = $conn->prepare("INSERT INTO customers (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    if (!$ins) {
        header("Location: admin_user_add.php?error=4");
        exit;
    }
    $ins->bind_param("sssss", $name, $email, $hashed, $role, $status);

    if ($ins->execute()) {
        $ins->close();
        // success -> go to a success page that will show the SweetAlert and then redirect to dashboard
        header("Location: user_add_success.php");
        exit;
    } else {
        $ins->close();
        header("Location: admin_user_add.php?error=3");
        exit;
    }

} catch (Exception $e) {
    header("Location: admin_user_add.php?error=4");
    exit;
}
