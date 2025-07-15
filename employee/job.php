<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$row = $db->query("SELECT e.*, p.department_name, p.post FROM Employees e LEFT JOIN Posts_and_Departments p ON e.pd_id=p.pd_id WHERE e.employee_id=".$_SESSION["user_id"])->fetch_assoc();
$user = $row['first_name'];
$role = "employee";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Job & Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>My Job & Department</h3>
    <table class="table table-dark">
        <tr><th>Department</th><td><?=htmlspecialchars($row['department_name'])?></td></tr>
        <tr><th>Post</th><td><?=htmlspecialchars($row['post'])?></td></tr>
        <tr><th>Hire Date</th><td><?=$row['hire_date']?></td></tr>
    </table>
</div>
</body>
</html>