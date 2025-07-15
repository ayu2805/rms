<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

function get_pd_options($db, $sel = null) {
    $opts = "";
    $pds = $db->query("SELECT pd_id, department_name, post FROM Posts_and_Departments ORDER BY department_name, post");
    while ($pd = $pds->fetch_assoc()) {
        $s = $sel==$pd['pd_id'] ? "selected" : "";
        $opts .= "<option value='{$pd['pd_id']}' $s>{$pd['department_name']} - {$pd['post']}</option>";
    }
    return $opts;
}
function get_emp_options($db, $sel = null) {
    $opts = "<option value=''>None</option>";
    $emps = $db->query("SELECT employee_id, first_name, last_name FROM Employees ORDER BY first_name");
    while ($e = $emps->fetch_assoc()) {
        $s = $sel==$e['employee_id'] ? "selected" : "";
        $opts .= "<option value='{$e['employee_id']}' $s>{$e['first_name']} {$e['last_name']}</option>";
    }
    return $opts;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == "add") {
        $stmt = $db->prepare("INSERT INTO Trainees (pd_id, first_name, last_name, gender, age, contact_address, email, password, batch_id, start_date, end_date, trainer_employee_id, completion_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("isssissssssss", $_POST['pd_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'], $_POST['age'], $_POST['contact_address'], $_POST['email'], $hash, $_POST['batch_id'], $_POST['start_date'], $_POST['end_date'], $_POST['trainer_employee_id'], $_POST['completion_status']);
        $stmt->execute();
    } elseif ($_POST['action'] == "edit") {
        $id = intval($_POST['trainee_id']);
        $sql = "UPDATE Trainees SET pd_id=?, first_name=?, last_name=?, gender=?, age=?, contact_address=?, email=?, batch_id=?, start_date=?, end_date=?, trainer_employee_id=?, completion_status=?";
        $params = [$_POST['pd_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'], $_POST['age'], $_POST['contact_address'], $_POST['email'], $_POST['batch_id'], $_POST['start_date'], $_POST['end_date'], $_POST['trainer_employee_id'], $_POST['completion_status']];
        if ($_POST['password']) {
            $sql .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE trainee_id=?";
        $params[] = $id;
        $types = "isssisssssssi" . ($_POST['password'] ? "s" : "") . "i";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
    } elseif ($_POST['action'] == "delete") {
        $db->query("DELETE FROM Trainees WHERE trainee_id=".(int)$_POST['trainee_id']);
    }
    header("Location: trainees.php");
    exit;
}

$rows = $db->query("SELECT * FROM Trainees ORDER BY trainee_id DESC")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Trainees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Trainees</h3>
    <form class="row g-2 mb-4" method="post">
        <input type="hidden" name="action" value="add">
        <div class="col"><?= "<select name='pd_id' class='form-select' required>".get_pd_options($db)."</select>" ?></div>
        <div class="col"><input class="form-control" name="first_name" placeholder="First Name" required></div>
        <div class="col"><input class="form-control" name="last_name" placeholder="Last Name" required></div>
        <div class="col"><select name="gender" class="form-select"><option>Male</option><option>Female</option><option>Other</option></select></div>
        <div class="col"><input class="form-control" name="age" placeholder="Age" type="number" min="16"></div>
        <div class="col"><input class="form-control" name="contact_address" placeholder="Address"></div>
        <div class="col"><input class="form-control" name="email" placeholder="Email" type="email" required></div>
        <div class="col"><input class="form-control" name="password" placeholder="Password" required></div>
        <div class="col"><input class="form-control" name="batch_id" placeholder="Batch ID" required></div>
        <div class="col"><input class="form-control" name="start_date" placeholder="Start Date" type="date" required></div>
        <div class="col"><input class="form-control" name="end_date" placeholder="End Date" type="date" required></div>
        <div class="col"><?= "<select name='trainer_employee_id' class='form-select'>".get_emp_options($db)."</select>" ?></div>
        <div class="col"><select name="completion_status" class="form-select"><option>Enrolled</option><option>In Progress</option><option>Completed</option><option>Dropped</option></select></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr>
            <th>#</th><th>Dept/Post</th><th>Name</th><th>Gender</th><th>Age</th><th>Address</th><th>Email</th><th>Batch</th><th>Start-End</th><th>Trainer</th><th>Status</th><th>Actions</th>
        </tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
            <form method="post">
                <td><?=$r['trainee_id']?><input type="hidden" name="trainee_id" value="<?=$r['trainee_id']?>"></td>
                <td><select name="pd_id" class="form-select"><?=get_pd_options($db, $r['pd_id'])?></select></td>
                <td><input name="first_name" value="<?=htmlspecialchars($r['first_name'])?>" class="form-control" required>
                    <input name="last_name" value="<?=htmlspecialchars($r['last_name'])?>" class="form-control" required></td>
                <td><select name="gender" class="form-select">
                    <option <?=($r['gender']=='Male'?'selected':'')?>>Male</option>
                    <option <?=($r['gender']=='Female'?'selected':'')?>>Female</option>
                    <option <?=($r['gender']=='Other'?'selected':'')?>>Other</option>
                </select></td>
                <td><input name="age" value="<?=htmlspecialchars($r['age'])?>" class="form-control" type="number"></td>
                <td><input name="contact_address" value="<?=htmlspecialchars($r['contact_address'])?>" class="form-control"></td>
                <td><input name="email" value="<?=htmlspecialchars($r['email'])?>" class="form-control" required></td>
                <td><input name="batch_id" value="<?=htmlspecialchars($r['batch_id'])?>" class="form-control" required></td>
                <td>
                    <input name="start_date" value="<?=$r['start_date']?>" class="form-control" type="date" required>
                    <input name="end_date" value="<?=$r['end_date']?>" class="form-control" type="date" required>
                </td>
                <td><select name="trainer_employee_id" class="form-select"><?=get_emp_options($db, $r['trainer_employee_id'])?></select></td>
                <td><select name="completion_status" class="form-select">
                    <option <?=($r['completion_status']=='Enrolled'?'selected':'')?>>Enrolled</option>
                    <option <?=($r['completion_status']=='In Progress'?'selected':'')?>>In Progress</option>
                    <option <?=($r['completion_status']=='Completed'?'selected':'')?>>Completed</option>
                    <option <?=($r['completion_status']=='Dropped'?'selected':'')?>>Dropped</option>
                </select></td>
                <td>
                    <input name="password" placeholder="New Password" class="form-control mb-1" type="password">
                    <button name="action" value="edit" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this trainee?')">Delete</button>
                </td>
            </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>