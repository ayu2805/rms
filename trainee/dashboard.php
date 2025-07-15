<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "trainee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$row = $db->query("SELECT * FROM Trainees WHERE trainee_id=".$_SESSION["user_id"])->fetch_assoc();
$user = $row['first_name'];
$role = "trainee";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Trainee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h2 class="mb-4">Welcome, <?=$user?></h2>
    <a href="profile.php" class="btn btn-outline-light">My Info</a>
</div>
</body>
</html>