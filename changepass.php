<?php
session_start();
require 'includes/db.php'; // Include database connection

// Initialize error variable to display error message
$error_message = null;
$show_notification = false;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $error_message = "You must be logged in to change your password.";
    $show_notification = true;
} else {
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_SESSION['user_id']; // Get user ID from session

        // Ensure form fields are set to avoid undefined index warnings
        $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Check if new password and confirmation match
        if ($new_password !== $confirm_password) {
            $error_message = "New password and confirmation do not match.";
            $show_notification = true;
            
            
            unset($current_password, $new_password, $confirm_password);
        } else {
            try {
                // Fetch the user's current password hash from the database
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :user_id");
                $stmt->execute(['user_id' => $user_id]);
                $user = $stmt->fetch();

                // Verify the current password
                if (!password_verify($current_password, $user['password'])) {
                    $error_message = "Current password is incorrect.";
                    $show_notification = true;
                    
                    unset($current_password, $new_password, $confirm_password);
                } else {
                    // Hash the new password
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $update_stmt = $pdo->prepare("UPDATE users SET password = :new_password WHERE id = :user_id");
                    $update_stmt->execute([
                        'new_password' => $new_password_hash,
                        'user_id' => $user_id
                    ]);

                    // Success message
                    $error_message = "Password changed successfully!";
                    $show_notification = true;
                    
                    // Reset form values
                    unset($current_password, $new_password, $confirm_password);
                }
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
                $show_notification = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo.png">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/changepass.css">
    <style>
        /* Notification styles */
        .notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .notification.success {
            background-color: #4CAF50; /* Green */
        }

        .notification.error {
            background-color: #f44336; /* Red */
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="navigation">
        <div class="nav">
            <a href="index.php">HOME</a>
            <a href="frankenstein.php">EXPANSION</a>
        </div>
    </header>

    <!-- Notification Section -->
   <?php if ($error_message): ?>
        <div class="notification <?php echo ($error_message ? 'error' : ''); ?>" id="notification">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Password Change Section -->
    <section class="passsec1">
        <div class="passsec1-content">
            <h1>CHANGE PASSWORD</h1>

            <form method="POST" action="changepass.php" class="accdetails">
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password" 
                    placeholder="Current Password" 
                    value="<?php echo isset($current_password) ? htmlspecialchars($current_password) : ''; ?>"
                    required 
                >
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    placeholder="New Password" 
                    value="<?php echo isset($new_password) ? htmlspecialchars($new_password) : ''; ?>"
                    required 
                >
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirm New Password" 
                    value="<?php echo isset($confirm_password) ? htmlspecialchars($confirm_password) : ''; ?>"
                    required 
                >

                <div class="passsec2-content">
                    <a class="cancel-btn" href="account.php">CANCEL</a>
                    <button type="submit">SAVE</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Show the notification only if there is an error or success message
            const notification = document.getElementById("notification");
    if (notification) {
        setTimeout(function() {
            notification.style.display = "none"; // Hide after 5 seconds
        }, 5000);
    }

    </script>

</body>
</html>
