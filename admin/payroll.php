<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action']=='generate') {
        $empid = intval($_POST['employee_id']);
        $net_pay = floatval($_POST['net_pay']);
        $db->query("INSERT INTO Payroll_Records (employee_id, net_pay) VALUES ($empid, $net_pay)");
    } elseif ($_POST['action'] == 'delete') {
        $db->query("DELETE FROM Payroll_Records WHERE payroll_id=".(int)$_POST['payroll_id']);
    }
    header("Location: payroll.php");
    exit;
}
$emps = $db->query("SELECT employee_id, first_name, last_name FROM Employees");
$payrolls = $db->query("SELECT p.*, e.first_name, e.last_name FROM Payroll_Records p JOIN Employees e ON p.employee_id=e.employee_id ORDER BY p.payroll_id DESC")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Payroll Records</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="generate">
        <div class="col"><select name="employee_id" class="form-select">
            <?php foreach ($emps as $e) echo "<option value='{$e['employee_id']}'>{$e['first_name']} {$e['last_name']}</option>"; ?>
        </select></div>
        <div class="col"><input type="number" name="net_pay" class="form-control" step="0.01" placeholder="Net Pay" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Generate</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Employee</th><th>Net Pay</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($payrolls as $p): ?>
            <tr>
                <td><?=$p['first_name'].' '.$p['last_name']?></td>
                <td><?=$p['net_pay']?></td>
                <td>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="payroll_id" value="<?=$p['payroll_id']?>">
                        <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this payroll record?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>