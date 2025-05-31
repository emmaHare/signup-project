
<?php

// ----------------------
// General Utilities
// ----------------------
function sanitize($data) {
    return trim(htmlspecialchars($data));
}

function passwordsMatch($password, $confirmPassword) {
    return $password === $confirmPassword;
}

function getUserById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ----------------------
// User Authentication
// ----------------------
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
}

function loginUser($pdo, $identifier, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_username = ? OR user_email = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['user_password'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['user_username'];
        $_SESSION['is_admin'] = $user['user_is_admin'];

        logLogin($pdo, $user['user_id']);
        return true;
    }

    return false;
}

function handleLoginRequest($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identifier = sanitize($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if (loginUser($pdo, $identifier, $password)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid login credentials. <a href='login.php'>Try again</a>";
        }
    }
}

// ----------------------
// Signup
// ----------------------
function isDuplicateUser($pdo, $username, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_username = ? OR user_email = ?");
    $stmt->execute([$username, $email]);
    return $stmt->rowCount() > 0;
}

function insertUser($pdo, $username, $email, $password, $phone, $birthday, $firstName, $lastName) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (user_username, user_email, user_password, user_phone, user_birthday, user_firstname, user_lastname)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword, $phone, $birthday, $firstName, $lastName]);
    return $pdo->lastInsertId();
}

function handleSignupRequest($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = sanitize($_POST['first_name'] ?? '');
        $lastName = sanitize($_POST['last_name'] ?? '');
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $phone = sanitize($_POST['phone_number'] ?? '');
        $birthday = $_POST['birthday'] ?? '';

        $street = $_POST['street'] ?? null;
        $houseNumber = $_POST['house_number'] ?? null;
        $zip = $_POST['zip'] ?? null;
        $flat = $_POST['flat'] ?? null;
        $staircase = $_POST['staircase'] ?? null;
        $city = $_POST['city'] ?? null;
        $country = $_POST['country'] ?? null;

        if (!passwordsMatch($password, $confirmPassword)) {
            echo "Passwords do not match. <a href='signup.php'>Go back</a>";
            return;
        }

        if (isDuplicateUser($pdo, $username, $email)) {
            echo "Username or email already taken. <a href='signup.php'>Go back</a>";
            return;
        }

        $userId = insertUser($pdo, $username, $email, $password, $phone, $birthday, $firstName, $lastName);

        if (!empty($street) || !empty($houseNumber) || !empty($zip)) {
            insertAddress($pdo, $userId, $street, $houseNumber, $zip, $flat, $staircase, $city, $country);
        }

        echo "Signup successful! <a href='login.php'>Log in</a>";
    }
}

// ----------------------
// Logging
// ----------------------
function logLogin($pdo, $userId) {
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    // Convert ::1 to 127.0.0.1
    if ($ipAddress === '::1') {
        $ipAddress = '127.0.0.1';
    }

    $stmt = $pdo->prepare("INSERT INTO logins (user_id, login_time, login_ip_address)
                           VALUES (?, NOW(), ?)");
    $stmt->execute([$userId, $ipAddress]);
}

function getLoginHistory($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT login_time, login_ip_address FROM logins WHERE user_id = ? ORDER BY login_time DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ----------------------
// Address Management
// ----------------------
function insertAddress($pdo, $userId, $street, $houseNumber, $staircase, $flat, $zip, $city, $country) {
    $stmt = $pdo->prepare("INSERT INTO addresses 
        (user_id, address_street, address_housenumber, address_staircase, address_flat, address_zip, address_city, address_country) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $street, $houseNumber, $staircase, $flat, $zip, $city, $country]);
}

function addAddress($pdo, $user_id, $street, $houseNumber, $staircase, $flat, $zip, $city, $country) {
    if (!empty($street) && !empty($city)) {
        $stmt = $pdo->prepare("INSERT INTO addresses 
            (user_id, address_street, address_housenumber, address_staircase, address_flat, address_zip, address_city, address_country) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $street, $houseNumber, $staircase, $flat, $zip, $city, $country]);
    }
}

function updateAddress($pdo, $user_id, $id, $street, $houseNumber, $staircase, $flat, $zip, $city, $country) {
    $stmt = $pdo->prepare("UPDATE addresses SET 
        address_street = ?, 
        address_housenumber = ?, 
        address_staircase = ?, 
        address_flat = ?, 
        address_zip = ?, 
        address_city = ?, 
        address_country = ? 
        WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$street, $houseNumber, $staircase, $flat, $zip, $city, $country, $id, $user_id]);
}

function deleteAddress($pdo, $user_id, $id) {
    $stmt = $pdo->prepare("DELETE FROM addresses WHERE address_id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
}

function getUserAddresses($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Update handleDashboardPost to handle all fields
function handleDashboardPost($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['logout'])) {
            logoutUser();
            header("Location: ../index.php");
            exit;
        }

        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $user_id = $_SESSION['user_id'];

            // Sanitize all address input fields (add your sanitize calls as needed)
            $street = $_POST['street'] ?? '';
            $houseNumber = $_POST['house_number'] ?? '';
            $staircase = $_POST['staircase'] ?? '';
            $flat = $_POST['flat'] ?? '';
            $zip = $_POST['zip'] ?? '';
            $city = $_POST['city'] ?? '';
            $country = $_POST['country'] ?? '';

            if ($action === 'add') {
                addAddress($pdo, $user_id, $street, $houseNumber, $staircase, $flat, $zip, $city, $country);
            } elseif ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                updateAddress($pdo, $user_id, $id, $street, $houseNumber, $staircase, $flat, $zip, $city, $country);
            } elseif ($action === 'delete') {
                $id = $_POST['id'] ?? 0;
                deleteAddress($pdo, $user_id, $id);
            }
        }
    }
}
?>