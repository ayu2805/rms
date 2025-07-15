<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$user = $db->query("SELECT first_name FROM Employees WHERE employee_id=".$_SESSION["user_id"])->fetch_assoc()['first_name'];
$role = "employee";
$rows = $db->query("SELECT * FROM Attendance WHERE employee_id=".$_SESSION["user_id"]." ORDER BY attendance_date DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>My Attendance</h3>
    <table class="table table-dark table-hover">
        <thead><tr><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r): ?>
            <tr><td><?=$r['attendance_date']?></td><td><?=$r['status']?></td></tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>