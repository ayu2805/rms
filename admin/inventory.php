<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") header("Location: ../index.php");
require_once "../config.php";
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == "add_raw") {
        $stmt = $db->prepare("INSERT INTO Raw_Items (ri_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $_POST['ri_name'], $_POST['quantity']);
        $stmt->execute();
    } elseif ($_POST['action'] == "edit_raw") {
        $stmt = $db->prepare("UPDATE Raw_Items SET ri_name=?, quantity=? WHERE ri_id=?");
        $stmt->bind_param("sii", $_POST['ri_name'], $_POST['quantity'], $_POST['ri_id']);
        $stmt->execute();
    } elseif ($_POST['action'] == "delete_raw") {
        $db->query("DELETE FROM Raw_Items WHERE ri_id=".(int)$_POST['ri_id']);
    } elseif ($_POST['action'] == "add_fp") {
        $stmt = $db->prepare("INSERT INTO Finishes_Products (fp_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $_POST['fp_name'], $_POST['quantity']);
        $stmt->execute();
    } elseif ($_POST['action'] == "edit_fp") {
        $stmt = $db->prepare("UPDATE Finishes_Products SET fp_name=?, quantity=? WHERE fp_id=?");
        $stmt->bind_param("sii", $_POST['fp_name'], $_POST['quantity'], $_POST['fp_id']);
        $stmt->execute();
    } elseif ($_POST['action'] == "delete_fp") {
        $db->query("DELETE FROM Finishes_Products WHERE fp_id=".(int)$_POST['fp_id']);
    }
    header("Location: inventory.php");
    exit;
}
$raw = $db->query("SELECT * FROM Raw_Items")->fetch_all(MYSQLI_ASSOC);
$fp = $db->query("SELECT * FROM Finishes_Products")->fetch_all(MYSQLI_ASSOC);
$stmt = $db->query("SELECT username FROM Admin WHERE admin_id=".$_SESSION["user_id"]);
$user = $stmt->fetch_assoc()['username'];
$role = "admin";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../includes/navbar.php"; ?>
<div class="container">
    <h3>Raw Items</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="add_raw">
        <div class="col"><input name="ri_name" class="form-control" placeholder="Raw Item Name" required></div>
        <div class="col"><input name="quantity" type="number" class="form-control" placeholder="Quantity" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Name</th><th>Qty</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($raw as $r): ?>
            <tr>
                <form method="post">
                <td><input name="ri_name" value="<?=htmlspecialchars($r['ri_name'])?>" class="form-control" required></td>
                <td><input name="quantity" value="<?=$r['quantity']?>" type="number" class="form-control" required></td>
                <td>
                    <input type="hidden" name="ri_id" value="<?=$r['ri_id']?>">
                    <button name="action" value="edit_raw" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete_raw" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                </td>
                </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <h3>Finished Products</h3>
    <form class="row g-2 mb-3" method="post">
        <input type="hidden" name="action" value="add_fp">
        <div class="col"><input name="fp_name" class="form-control" placeholder="Product Name" required></div>
        <div class="col"><input name="quantity" type="number" class="form-control" placeholder="Quantity" required></div>
        <div class="col-auto"><button class="btn btn-success" type="submit">Add</button></div>
    </form>
    <table class="table table-dark table-hover">
        <thead><tr><th>Name</th><th>Qty</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($fp as $f): ?>
            <tr>
                <form method="post">
                <td><input name="fp_name" value="<?=htmlspecialchars($f['fp_name'])?>" class="form-control" required></td>
                <td><input name="quantity" value="<?=$f['quantity']?>" type="number" class="form-control" required></td>
                <td>
                    <input type="hidden" name="fp_id" value="<?=$f['fp_id']?>">
                    <button name="action" value="edit_fp" class="btn btn-primary btn-sm">Save</button>
                    <button name="action" value="delete_fp" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                </td>
                </form>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>