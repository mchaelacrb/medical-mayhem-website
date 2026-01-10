<?php
session_start();
require 'includes/db.php';

// Initialize error variable to display error message
$error_message = null;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Securely handle input values
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Fetch the user data based on the username
    $stmt = $pdo->prepare("SELECT id, email, password, is_verified FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Validate if the username exists
    if ($user) {
        // Check if the email is verified
        if ($user['is_verified'] == 0) {
            $error_message = "Your account is not verified. Please check your email.";
        } else {
            // Check if it's the admin user and validate the password
            if ($username === 'admin') {
                // Hardcoded admin password check (or use hashed password stored in DB)
                $admin_password = 'admin123'; // Replace with the actual admin password
                if ($password === $admin_password) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    header("Location: admin.php");
                    exit();
                } else {
                    $error_message = "Invalid admin password!";
                }
            } else {
                // For non-admin users, use the hashed password check
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit();
                } else {
                    $error_message = "Invalid username or password!";
                }
            }
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo.png">
    <title>MEDICAL MAYHEM</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Notification styles */
        .notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .notification.success {
            background-color: #4CAF50; /* Green */
        }

        .notification.error {
            background-color: #f44336; /* Red */
        }
    </style>

    <!-- Notification Section -->
    <?php if ($error_message): ?>
        <div class="notification error" id="notification">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</head>
<body>
    <div class="content">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo">
        </div>
        <div class="logincontent">
            <img src="assets/login box.png" alt="Login Box">
            <form method="POST" action="login.php" class="login-form">
                <!-- Login Form -->
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">LOG IN</button>
            </form>
        </div>
        <div class="signuptext">
            <p>New here?</p>
            <a href="signup.php">SIGN UP</a>
        </div>
    </div>

    <script>
        // Show the notification only if there is an error or success message
        const notification = document.getElementById("notification");
        if (notification) {
            setTimeout(function() {
                notification.style.opacity = "0"; // Fade out
                setTimeout(function() {
                    notification.style.display = "none"; // Hide after fade-out
                }, 1000); // Wait for fade-out to complete
            }, 5000); // Show for 5 seconds
        }
    </script>
</body>
</html>
