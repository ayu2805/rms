<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == "add") {
        $stmt = $db->prepare("INSERT INTO Suppliers (supplier_name, contact_person, contact_email, contact_phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['supplier_name'], $_POST['contact_person'], $_POST['contact_email'], $_POST['contact_phone'], $_POST['address']);
        $stmt->execute();
    } elseif ($_POST['action'] == "edit") {
        $stmt = $db->prepare("UPDATE Suppliers SET supplier_name=?, contact_person=?, contact_email=?, contact_phone=?, address=? WHERE supplier_id=?");
        $stmt->bind_param("sssssi", $_POST['supplier_name'], $_POST['contact_person'], $_POST['contact_email'], $_POST['contact_phone'], $_POST['address'], $_POST['supplier_id']);
        $stmt->execute();
    } elseif ($_POST['action'] == "delete") {
        $db->query("DELETE FROM Suppliers WHERE supplier_id=".(int)$_POST['supplier_id']);
    }
    header("Location: suppliers.php");
    exit;
}
$rows = $db->query("SELECT * FROM Suppliers ORDER BY supplier_name")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Suppliers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Suppliers</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="add">
        <div class="col"><input class="form-control" name="supplier_name" placeholder="Supplier Name" required></div>
        <div class="col"><input class="form-control" name="contact_person" placeholder="Contact Person"></div>
        <div class="col"><input class="form-control" name="contact_email" placeholder="Contact Email" type="email"></div>
        <div class="col"><input class="form-control" name="contact_phone" placeholder="Contact Phone"></div>
        <div class="col"><input class="form-control" name="address" placeholder="Address"></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Name</th><th>Contact</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <form method="post">
                <td><input name="supplier_name" value="<?=htmlspecialchars($r['supplier_name'])?>" class="form-control" required></td>
                <td><input name="contact_person" value="<?=htmlspecialchars($r['contact_person'])?>" class="form-control"></td>
                <td><input name="contact_email" value="<?=htmlspecialchars($r['contact_email'])?>" class="form-control"></td>
                <td><input name="contact_phone" value="<?=htmlspecialchars($r['contact_phone'])?>" class="form-control"></td>
                <td><input name="address" value="<?=htmlspecialchars($r['address'])?>" class="form-control"></td>
                <td>
                    <input type="hidden" name="supplier_id" value="<?=$r['supplier_id']?>">
                    <button name="action" value="edit" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this supplier?')">Delete</button>
                </td>
                </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>