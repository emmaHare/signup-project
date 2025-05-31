<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

// Handle login if form submitted
handleLoginRequest($pdo);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        
        <form class="login-form" method="POST">
            <h2>Log In</h2>

            <div class="form-group">
                <label for="identifier">Username or Email</label>
                <input type="text" id="identifier" name="identifier" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Log In</button>

            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </form>
    
    </body>
</html>