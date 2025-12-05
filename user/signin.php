<?php 
include '../config.php';
session_start();

$msg = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT cust_id, name, email, password, role, status 
                            FROM customers 
                            WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {

        $u = $res->fetch_assoc();
        $storedPass = $u['password'];

        // STATUS CHECK
        if (strtoupper($u['status']) === "INACTIVE") {
            $msg = "Your account is restricted by admin.";
        }
        else {
            $valid = false;

            // CASE 1 → CHECK HASHED PASSWORD
            if (password_verify($pass, $storedPass)) {
                $valid = true;
            }

            // CASE 2 → CHECK PLAIN TEXT PASSWORD (old users)
            if ($pass === $storedPass) {
                $valid = true;
            }

            if ($valid) {
                // login success
                $_SESSION['user_id']  = $u['cust_id'];
                $_SESSION['user']     = $u['name'];
                $_SESSION['username'] = $email;
                $_SESSION['role']     = $u['role'];

                header("Location: ../index.php");
                exit;
            } 
            else {
                $msg = "Incorrect Password!";
            }
        }

    } else {
        $msg = "Email not registered!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login - RoyalStay</title>
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
    width: 380px;
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
    <h3 class="text-center mb-4">Login to RoyalStay</h3>

    <?php if($msg != "") echo "<div class='alert alert-danger'>$msg</div>"; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" class="form-control mb-3" name="email" required>

        <label>Password</label>
        <input type="password" class="form-control mb-4" name="password" required>

        <button class="btn btn-primary w-100" name="login">Login</button>
    </form>

    <p class="text-center mt-3">
        New user? <a href="signup.php" class="text-warning fw-bold">Create account</a>
    </p>
</div>

</body>
</html>
