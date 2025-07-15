<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="row g-3">
        <div class="col-md-4"><a href="dept_post.php" class="btn btn-outline-light w-100">Departments & Posts</a></div>
        <div class="col-md-4"><a href="employees.php" class="btn btn-outline-light w-100">Employees</a></div>
        <div class="col-md-4"><a href="attendance.php" class="btn btn-outline-light w-100">Attendance</a></div>
        <div class="col-md-4"><a href="leaves.php" class="btn btn-outline-light w-100">Leaves</a></div>
        <div class="col-md-4"><a href="payroll.php" class="btn btn-outline-light w-100">Payroll</a></div>
        <div class="col-md-4"><a href="trainees.php" class="btn btn-outline-light w-100">Trainees</a></div>
        <div class="col-md-4"><a href="inventory.php" class="btn btn-outline-light w-100">Inventory</a></div>
        <div class="col-md-4"><a href="suppliers.php" class="btn btn-outline-light w-100">Suppliers</a></div>
    </div>
</div>
</body>
</html>