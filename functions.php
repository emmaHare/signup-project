<?php

// Sanitize Input 
function sanitize($data) {
    return trim(htmlspecialchars($data));
}

// Check passwords match
function passwordsMatch($password, $confirmPassword) {
    return $password === $confirmPassword;
}

// Check for duplicate username or email
function isDuplicateUser($pdo, $username, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_username = ? OR user_email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->rowCount() > 0;
}

// Insert User and get ID
function insertUser($pdo, $username, $email, $password, $phone, $birthday, $firstName, $lastName) {
    $hashedPassword = hash('sha512', $password);
    $stmt = $pdo->prepare("INSERT INTO users (user_username, user_email, user_password, user_phone, user_birthday, user_firstname, user_lastname) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword, $phone, $birthday, $firstName, $lastName]);
    return $pdo->lastInsertId();
}

// Insert address if provided
function insertAddress($pdo, $userId, $street, $houseNumber, $zip, $flat, $staircase, $city, $country) {
    $stmt = $pdo->prepare("INSERT INTO addresses (user_id, address_street, address_housenumber, address_zip, address_flat, address_staircase, address_city, address_country) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $street, $houseNumber, $zip, $flat, $staircase, $city, $country]);
} 

// User login 
function loginUser($pdo, $identifier, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_username = ? OR user_email = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['user_password'])) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['user_username'];
        $_SESSION['is_admin'] = $user['user_is_admin'];

        return true; // login success
    }

    return false; // login failed
}

// User Logout
function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];
    session_destroy();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

?>


