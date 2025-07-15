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
    <title>Trainee Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>My Information</h3>
    <table class="table table-dark">
        <?php foreach ($row as $k=>$v)
            if (!in_array($k, ['password','trainee_id','pd_id','trainer_employee_id']))
                echo "<tr><th>".ucwords(str_replace("_"," ",$k))."</th><td>".htmlspecialchars($v)."</td></tr>";
        ?>
    </table>
</div>
</body>
</html>