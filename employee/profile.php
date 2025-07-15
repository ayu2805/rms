<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$row = $db->query("SELECT * FROM Employees WHERE employee_id=".$_SESSION["user_id"])->fetch_assoc();
$user = $row['first_name'];
$role = "employee";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Employee Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>My Information</h3>
    <table class="table table-dark">
        <?php foreach ($row as $k=>$v)
            if (!in_array($k, ['password','employee_id','pd_id']))
                echo "<tr><th>".ucwords(str_replace("_"," ",$k))."</th><td>".htmlspecialchars($v)."</td></tr>";
        ?>
    </table>
</div>
</body>
</html>