<?php
session_start();
require_once __DIR__ . '/../functions.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <link rel="stylesheet" href="signup.css">
    </head>
    <body>
        <form class="signup-form" action="signup-process.php" method="POST">
            <h2>Sign Up</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name=phone_number>
                </div>
                <div class="form-group">
                    <label for="birthday">Birthday</label>
                    <input type="date" name="birthday" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>

            <!--Address Modal Button-->
            <button type="button" class="add-address-btn" onclick="openModal()">+ Add Address</button>
            <!--Address Modal-->
            <div id="addressModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal()">&times;</span>
                    <h3>Address Details</h3>

                    <div class="form-group full">
                    <label for="street">Street</label>
                    <input type="text" name="street" placeholder="e.g., Main Street">
                </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="house_number">House Number</label>
                    <input type="text" name="house_number" placeholder="e.g., 42">
                </div>
                <div class="form-group">
                    <label for="zip">ZIP Code</label>
                    <input type="text" name="zip" placeholder="e.g., 12345">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="flat">Flat (optional)</label>
                    <input type="text" name="flat" placeholder="e.g., 3B">
                </div>
                <div class="form-group">
                    <label for="staircase">Staircase (optional)</label>
                    <input type="text" name="staircase" placeholder="e.g., A">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" placeholder="e.g., Vienna">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" placeholder="e.g., Austria">
                </div>
            </div>
            
            <button class="submit-btn" type="submit">Sign Up</button>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </form>
    </body>
</html>