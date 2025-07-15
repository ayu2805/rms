<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
        $stmt = $db->prepare("UPDATE Leaves SET status=?, approved_by_employee_id=? WHERE leave_id=?");
        $stmt->bind_param("sii", $_POST['status'], $_POST['approved_by_employee_id'], $_POST['leave_id']);
        $stmt->execute();
    }
    header("Location: leaves.php");
    exit;
}
$leaves = $db->query("SELECT l.*, e.first_name, e.last_name FROM Leaves l JOIN Employees e ON l.employee_id=e.employee_id ORDER BY l.status, l.start_date DESC")->fetch_all(MYSQLI_ASSOC);
$emps = $db->query("SELECT employee_id, first_name, last_name FROM Employees");
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
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
    <h3>Leaves (Employee Leave Requests)</h3>
    <table class="table table-dark table-hover">
        <thead><tr>
            <th>Employee</th><th>Start</th><th>End</th><th>Reason</th><th>Status</th><th>Approved By</th><th>Actions</th>
        </tr></thead>
        <tbody>
        <?php foreach ($leaves as $l): ?>
            <tr>
                <form method="post">
                <td><?=$l['first_name'].' '.$l['last_name']?></td>
                <td><?=$l['start_date']?></td>
                <td><?=$l['end_date']?></td>
                <td><?=htmlspecialchars($l['reason'])?></td>
                <td>
                    <input type="hidden" name="leave_id" value="<?=$l['leave_id']?>">
                    <select name="status" class="form-select">
                        <?php foreach(['Pending','Approved','Denied'] as $s)
                            echo "<option".($l['status']==$s?" selected":"").">$s</option>";
                        ?>
                    </select>
                </td>
                <td>
                    <select name="approved_by_employee_id" class="form-select">
                        <option value="">None</option>
                        <?php foreach ($emps as $e)
                            echo "<option value='{$e['employee_id']}' ".($l['approved_by_employee_id']==$e['employee_id']?'selected':'').">{$e['first_name']} {$e['last_name']}</option>";
                        ?>
                    </select>
                </td>
                <td>
                    <button name="action" value="update" class="btn btn-primary btn-sm">Update</button>
                </td>
                </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>