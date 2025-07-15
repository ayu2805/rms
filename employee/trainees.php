<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();
$user = $db->query("SELECT first_name FROM Employees WHERE employee_id=".$_SESSION["user_id"])->fetch_assoc()['first_name'];
$role = "employee";
$rows = $db->query("SELECT * FROM Trainees WHERE trainer_employee_id=".$_SESSION["user_id"]." ORDER BY start_date DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Assigned Trainees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Assigned Trainees</h3>
    <table class="table table-dark table-hover">
        <thead><tr><th>Name</th><th>Batch</th><th>Status</th><th>Start-End</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r): ?>
            <tr>
                <td><?=htmlspecialchars($r['first_name'].' '.$r['last_name'])?></td>
                <td><?=htmlspecialchars($r['batch_id'])?></td>
                <td><?=htmlspecialchars($r['completion_status'])?></td>
                <td><?=$r['start_date']?> - <?=$r['end_date']?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>