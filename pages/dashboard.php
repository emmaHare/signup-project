<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Logout if logout button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
    header("Location: ../index.php");
    exit;
}

// Fetch user's first name
$stmt = $pdo->prepare("SELECT user_firstname FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        .logout-btn { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user['user_firstname']) ?>!</h2>
    <p>You are now logged in.</p>

    <form method="post">
        <button type="submit" name="logout">Log Out</button>
    </form>
</body>
</html>