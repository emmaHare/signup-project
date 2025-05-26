<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $password = $_POST['password'];

    if (loginUser($pdo, $identifier, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials. <a href='login.php'>Try again</a>";
    }
} else {
    header("Location: login.php");
    exit;
}