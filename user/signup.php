<?php  
include '../config.php';

$msg = "";

if (isset($_POST['register'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT email FROM customers WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $msg = "Email already registered!";
    } else {

        // Insert into customers table
        $stmt = $conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $pass);

        if ($stmt->execute()) {
            header("Location: signin.php?success=1");
            exit;
        } else {
            $msg = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Create Account - RoyalStay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600') center/cover no-repeat;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card-box{
    width: 400px;
    padding: 25px;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(6px);
    border-radius: 15px;
    color:white;
    box-shadow:0 8px 20px rgba(0,0,0,0.5);
}

.card-box h3{
    text-shadow:0 2px 10px rgba(0,0,0,0.7);
}

input{
    background: rgba(255,255,255,0.7) !important;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="card-box">
    <h3 class="text-center mb-4">Create Account</h3>

    <?php if(isset($_GET["success"])) echo "<div class='alert alert-success'>Account created! Please login.</div>"; ?>
    <?php if($msg != "") echo "<div class='alert alert-danger'>$msg</div>"; ?>

    <form method="post">

        <label>Full Name</label>
        <input type="text" class="form-control mb-3" name="name" required>

        <label>Email</label>
        <input type="email" class="form-control mb-3" name="email" required>

        <label>Password</label>
        <input type="password" class="form-control mb-4" name="password" required>

        <button class="btn btn-warning w-100 fw-bold" name="register">Create Account</button>
    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="signin.php" class="text-info fw-bold">Login</a>
    </p>
</div>

</body>
</html>
