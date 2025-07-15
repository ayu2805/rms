<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $db = get_db();
    if ($role == "admin") {
        $sql = "SELECT * FROM Admin WHERE username=?";
    } elseif ($role == "employee") {
        $sql = "SELECT * FROM Employees WHERE email=?";
    } elseif ($role == "trainee") {
        $sql = "SELECT * FROM Trainees WHERE email=?";
    } else {
        $_SESSION['msg'] = "Invalid role.";
        header("Location: index.php");
        exit;
    }
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION["role"] = $role;
            $_SESSION["user_id"] = $row[$role == "admin" ? "admin_id" : ($role == "employee" ? "employee_id" : "trainee_id")];
            header("Location: $role/dashboard.php");
            exit;
        }
    }
    $_SESSION['msg'] = "Invalid credentials.";
    header("Location: index.php");
}
?>