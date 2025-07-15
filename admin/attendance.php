<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $stmt = $db->prepare("INSERT INTO Attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_POST['employee_id'], $_POST['attendance_date'], $_POST['status']);
        $stmt->execute();
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $db->prepare("UPDATE Attendance SET status=? WHERE attendance_id=?");
        $stmt->bind_param("si", $_POST['status'], $_POST['attendance_id']);
        $stmt->execute();
    } elseif ($_POST['action'] == 'delete') {
        $db->query("DELETE FROM Attendance WHERE attendance_id=".(int)$_POST['attendance_id']);
    }
    header("Location: attendance.php");
    exit;
}
$emps = $db->query("SELECT employee_id, first_name, last_name FROM Employees");
$att = $db->query("SELECT a.*, e.first_name, e.last_name FROM Attendance a JOIN Employees e ON a.employee_id=e.employee_id ORDER BY a.attendance_date DESC, a.attendance_id DESC")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
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
    <h3>Attendance</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="add">
        <div class="col"><select name="employee_id" class="form-select">
            <?php foreach ($emps as $e) echo "<option value='{$e['employee_id']}'>{$e['first_name']} {$e['last_name']}</option>"; ?>
        </select></div>
        <div class="col"><input class="form-control" type="date" name="attendance_date" required></div>
        <div class="col"><select name="status" class="form-select">
            <option>Present</option><option>Absent</option><option>Half-day</option><option>Leave</option>
        </select></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Date</th><th>Employee</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($att as $a): ?>
            <tr>
            <form method="post">
                <td><?=$a['attendance_date']?></td>
                <td><?=$a['first_name']." ".$a['last_name']?></td>
                <td>
                    <input type="hidden" name="attendance_id" value="<?=$a['attendance_id']?>">
                    <select name="status" class="form-select">
                        <?php foreach(["Present","Absent","Half-day","Leave"] as $s) 
                            echo "<option".($a['status']==$s?' selected':'').">$s</option>";
                        ?>
                    </select>
                </td>
                <td>
                    <button name="action" value="edit" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this attendance record?')">Delete</button>
                </td>
            </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>