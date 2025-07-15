<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "add") {
        $dept = trim($_POST['department_name']);
        $post = trim($_POST['post']);
        $stmt = $db->prepare("INSERT INTO Posts_and_Departments (department_name, post) VALUES (?,?)");
        $stmt->bind_param("ss", $dept, $post);
        $stmt->execute();
    } elseif ($action == "edit") {
        $id = intval($_POST['pd_id']);
        $dept = trim($_POST['department_name']);
        $post = trim($_POST['post']);
        $stmt = $db->prepare("UPDATE Posts_and_Departments SET department_name=?, post=? WHERE pd_id=?");
        $stmt->bind_param("ssi", $dept, $post, $id);
        $stmt->execute();
    } elseif ($action == "delete") {
        $id = intval($_POST['pd_id']);
        $db->query("DELETE FROM Posts_and_Departments WHERE pd_id=$id");
    }
    header("Location: dept_post.php");
    exit;
}

$rows = $db->query("SELECT * FROM Posts_and_Departments ORDER BY department_name, post")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Departments & Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Departments & Posts</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="add">
        <div class="col"><input class="form-control" name="department_name" placeholder="Department" required></div>
        <div class="col"><input class="form-control" name="post" placeholder="Post/Title" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Department</th><th>Post</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <form method="post">
                <td><input type="text" name="department_name" value="<?=htmlspecialchars($r['department_name'])?>" class="form-control" required></td>
                <td><input type="text" name="post" value="<?=htmlspecialchars($r['post'])?>" class="form-control" required></td>
                <td>
                    <input type="hidden" name="pd_id" value="<?=$r['pd_id']?>">
                    <button name="action" value="edit" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this department/post?')">Delete</button>
                </td>
                </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>