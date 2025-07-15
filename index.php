<?php
session_start();
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin") header("Location: admin/dashboard.php");
    elseif ($_SESSION["role"] == "employee") header("Location: employee/dashboard.php");
    elseif ($_SESSION["role"] == "trainee") header("Location: trainee/dashboard.php");
    exit;
}
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Resource Management System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow" style="min-width: 350px;">
        <h3 class="mb-3 text-center">Login</h3>
        <?php if ($msg): ?>
            <div class="alert alert-danger"><?=$msg?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label>User Type</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                    <option value="trainee">Trainee</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Email/Username</label>
                <input type="text" name="username" class="form-control" required autocomplete="username">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>