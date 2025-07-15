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

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "add") {
        $stmt = $db->prepare("INSERT INTO Employees (pd_id, first_name, last_name, gender, age, contact_address, email, password, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("isssissss", $_POST['pd_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'], $_POST['age'], $_POST['contact_address'], $_POST['email'], $hash, $_POST['hire_date']);
        $stmt->execute();
    } elseif ($action == "edit") {
        $id = intval($_POST['employee_id']);
        $sql = "UPDATE Employees SET pd_id=?, first_name=?, last_name=?, gender=?, age=?, contact_address=?, email=?, hire_date=?";
        $params = [$_POST['pd_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'], $_POST['age'], $_POST['contact_address'], $_POST['email'], $_POST['hire_date']];
        if ($_POST['password']) {
            $sql .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE employee_id=?";
        $params[] = $id;
        $types = "isssiss" . ($_POST['password'] ? "s" : "") . "i";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
    } elseif ($action == "delete") {
        $id = intval($_POST['employee_id']);
        $db->query("DELETE FROM Employees WHERE employee_id=$id");
    }
    header("Location: employees.php");
    exit;
}

$rows = $db->query("SELECT * FROM Employees ORDER BY employee_id DESC")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Employees</h3>
    <form class="row g-2 mb-4" method="post">
        <input type="hidden" name="action" value="add">
        <div class="col"><?= "<select name='pd_id' class='form-select' required>".get_pd_options($db)."</select>" ?></div>
        <div class="col"><input class="form-control" name="first_name" placeholder="First Name" required></div>
        <div class="col"><input class="form-control" name="last_name" placeholder="Last Name" required></div>
        <div class="col"><select name="gender" class="form-select"><option>Male</option><option>Female</option><option>Other</option></select></div>
        <div class="col"><input class="form-control" name="age" placeholder="Age" type="number" min="18"></div>
        <div class="col"><input class="form-control" name="contact_address" placeholder="Address"></div>
        <div class="col"><input class="form-control" name="email" placeholder="Email" type="email" required></div>
        <div class="col"><input class="form-control" name="password" placeholder="Password" required></div>
        <div class="col"><input class="form-control" name="hire_date" placeholder="Hire Date" type="date" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr>
            <th>#</th><th>Dept/Post</th><th>Name</th><th>Gender</th><th>Age</th><th>Address</th><th>Email</th><th>Hire Date</th><th>Actions</th>
        </tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
            <form method="post">
                <td><?=$r['employee_id']?><input type="hidden" name="employee_id" value="<?=$r['employee_id']?>"></td>
                <td>
                    <select name="pd_id" class="form-select"><?=get_pd_options($db, $r['pd_id'])?></select>
                </td>
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
                <td><input name="hire_date" value="<?=$r['hire_date']?>" class="form-control" type="date" required></td>
                <td>
                    <input name="password" placeholder="New Password" class="form-control mb-1" type="password">
                    <button name="action" value="edit" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this employee?')">Delete</button>
                </td>
            </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>