<!DOCTYPE html>
<html>
<head>
<title>User Created</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body{ background: linear-gradient(135deg,#dde7ff,#eef8ff); height:100vh; margin:0; }
</style>
</head>
<body>

<script>
// Show success popup then redirect to admin dashboard
Swal.fire({
    icon: "success",
    title: "User Created!",
    text: "The new user has been successfully added.",
    confirmButtonColor: "#0066ff",
    allowOutsideClick: false,
    allowEscapeKey: false
}).then(() => {
    // Redirect to admin dashboard
    window.location.href = "admin_dashboard.php";
});
</script>

</body>
</html>
