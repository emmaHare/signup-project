<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $firstName = sanitize($_POST['first_name']);
    $lastName = sanitize($_POST['last_name']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $phone = sanitize($_POST['phone_number']);
    $birthday = $_POST['birthday'];

    // Optional address fields
    $street = $_POST['street'] ?? null;
    $houseNumber = $_POST['house_number'] ?? null;
    $zip = $_POST['zip'] ?? null;
    $flat = $_POST['flat'] ?? null;
    $staircase = $_POST['staircase'] ?? null;
    $city = $_POST['city'] ?? null;
    $country = $_POST['country'] ?? null;

    // Check password match
    if (!passwordsMatch($password, $confirmPassword)) {
        die("Passwords do not match. <a href='signup.php'>Go back</a>");
    }

    // Check for duplicate username or email
    if (isDuplicateUser($pdo, $username, $email)) {
        die("Username or email already taken. <a href='signup.php'>Go back</a>");
    }

    // Insert user and get ID
    $userId = insertUser($pdo, $username, $email, $password, $phone, $birthday, $firstName, $lastName);

    // Insert address if provided
    if (!empty($street) || !empty($houseNumber) || !empty($zip)) {
        insertAddress($pdo, $userId, $street, $houseNumber, $zip, $flat, $staircase, $city, $country);
    }

    echo "Signup successful! <a href='login.php'>Log in</a>";
} else {
    header("Location: signup.php");
    exit;
}
