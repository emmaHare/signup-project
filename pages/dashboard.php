<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Handle POST requests (logout and address CRUD)
handleDashboardPost($pdo);

// Fetch user, addresses, and login history
$user = getUserById($pdo, $_SESSION['user_id']);
$addresses = getUserAddresses($pdo, $_SESSION['user_id']);
$loginHistory = getLoginHistory($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        .logout-btn { margin-top: 20px; }
        table { margin-top: 20px; border-collapse: collapse; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px 12px; text-align: left; }
        button { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user['user_firstname']) ?>!</h2>
    <p>You are now logged in.</p>

    <form method="post">
        <button type="submit" name="logout">Log Out</button>
    </form>

    <!-- Toggle Buttons -->
    <button id="toggleAddressBtn">View / Edit My Addresses</button>
    <button id="toggleLoginHistoryBtn">View My Login History</button>

    <!-- Address Section -->
    <div id="addressSection" style="display: none; margin-top: 20px;">
        <button id="closeAddressSection" style="float: right;">X</button>
        <h3>Your Addresses</h3>

        <?php foreach ($addresses as $row): ?>
            <form method="post" style="margin-bottom: 10px;">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['address_id']) ?>">

                <input type="text" name="street" value="<?= htmlspecialchars($row['address_street']) ?>" placeholder="Street" required>
                <input type="text" name="house_number" value="<?= htmlspecialchars($row['address_housenumber']) ?>" placeholder="House Number">
                <input type="text" name="staircase" value="<?= htmlspecialchars($row['address_staircase']) ?>" placeholder="Staircase">
                <input type="text" name="flat" value="<?= htmlspecialchars($row['address_flat']) ?>" placeholder="Flat">
                <input type="text" name="zip" value="<?= htmlspecialchars($row['address_zip']) ?>" placeholder="ZIP" required>
                <input type="text" name="city" value="<?= htmlspecialchars($row['address_city']) ?>" placeholder="City" required>
                <input type="text" name="country" value="<?= htmlspecialchars($row['address_country']) ?>" placeholder="Country" required>

                <button type="submit">Update</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Delete this address?')">Delete</button>
            </form>
        <?php endforeach; ?>

        <h4>Add New Address</h4>
        <form method="post">
            <input type="hidden" name="action" value="add">

            <input type="text" name="street" placeholder="Street" required>
            <input type="text" name="house_number" placeholder="House Number">
            <input type="text" name="staircase" placeholder="Staircase">
            <input type="text" name="flat" placeholder="Flat">
            <input type="text" name="zip" placeholder="ZIP" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="country" placeholder="Country" required>

            <button type="submit">Add</button>
        </form>
    </div>

    <!-- Login History Section -->
    <div id="loginHistorySection" style="display: none; margin-top: 40px;">
        <button id="closeLoginHistorySection" style="float: right;">X</button>
        <h3>Login History</h3>
        <table>
            <thead>
                <tr>
                    <th>Login Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loginHistory as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['login_time']) ?></td>
                        <td><?= htmlspecialchars($entry['login_ip_address']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../functions.js"></script>
</body>
</html>