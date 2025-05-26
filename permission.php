<?php
// Check if the user is logged in by verifying the presence of 'user_id' in the session.
// If not logged in, redirect to the login page.
// If logged in, redirect to the dashboard page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} else {
    header("Location: dashboard.php");
    exit;
} 


