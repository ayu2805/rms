<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "employee") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action']=='apply') {
    $stmt = $db->prepare("INSERT INTO Leaves (employee_id, start_date, end_date, reason) VALUES (?,?,?,?)");
    $stmt->bind_param("isss", $_SESSION["user_id"], $_POST['start_date'], $_POST['end_date'], $_POST['reason']);
    $stmt->execute();
    header("Location: leaves.php");
    exit;
}
$user = $db->query("SELECT first_name FROM Employees WHERE employee_id=".$_SESSION["user_id"])->fetch_assoc()['first_name'];
$role = "employee";
$rows = $db->query("SELECT * FROM Leaves WHERE employee_id=".$_SESSION["user_id"]." ORDER BY start_date DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Leaves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>My Leaves</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="apply">
        <div class="col"><input name="start_date" type="date" class="form-control" required></div>
        <div class="col"><input name="end_date" type="date" class="form-control" required></div>
        <div class="col"><input name="reason" class="form-control" placeholder="Reason" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Apply</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Start</th><th>End</th><th>Reason</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r): ?>
            <tr><td><?=$r['start_date']?></td><td><?=$r['end_date']?></td><td><?=htmlspecialchars($r['reason'])?></td><td><?=$r['status']?></td></tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>