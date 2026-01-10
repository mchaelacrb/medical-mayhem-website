<?php
require 'includes/db.php';
require 'vendor/autoload.php'; // Include PHPMailer

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Initialize variables for error and success messages
$error_message = null;
$success_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    function validatePasswords($password, $confirm_password) {
        return $password === $confirm_password;
    }

    if (!validatePasswords($password, $confirm_password)) {
        $error_message = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error_message = "Email is already registered!";
        } else {
            // Generate verification token
            $verification_token = bin2hex(random_bytes(16));

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_token) VALUES (:username, :email, :password, :verification_token)");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'verification_token' => $verification_token
            ]);

            // Send verification email
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration (example with Gmail SMTP server)
              $mail->isSMTP();
       
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';//'medicalmayhem.shop';                    // Your SMTP server (use the hostname from your hosting provider)
                $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
                $mail->Username   = 'curibamecha11@gmail.com';//'kyvpiqrl';              // SMTP username (email address)
                $mail->Password   = 'arecfwnfsxfudukm';//'medicalmayhem2024';                    // SMTP password (password for the email account)
                $mail->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
                $mail->Port       = '587';//587;                                      // TCP port for TLS/STARTTLS (usually 587)

               
                
                // Email content
                $mail->setFrom('curibamecha11@gmail.com', 'MEDICAL MAYHEM');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Verify Your Email Address';
                $mail->Body = "
                    <h1>Thank you for signing up, $username!</h1>
                    <p>Click the link below to verify your email address:</p>
                    <a href='http://localhost:3000/verify.php?token=$verification_token'>Verify Email</a> 

                ";

                $mail->send();

                // Success message
                $success_message = "Account created successfully! Please check your email for verification.";
                header("Location: thankyou-page.php"); // Redirect to a thank you page
                exit();
            } catch (Exception $e) {
                $error_message = "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
    <title>MEDICAL MAYHEM - Signup</title>
    <link rel="stylesheet" href="signup.css">

    <style>
        /* Notification styles */
        .notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f44336;
            font-family: Arial, Helvetica, sans-serif;
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
            background-color: #4CAF50; /* Green for success */
        }

        .notification.error {
            background-color: #f44336; /* Red for error */
        }
    </style>

    <!-- Notification Section -->
    <?php if ($error_message): ?>
        <div class="notification error" id="notification">
            <?php echo $error_message; ?>
        </div>
    <?php elseif ($success_message): ?>
        <div class="notification success" id="notification">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
</head>
<body>
    <div class="content">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo">
        </div>
        <div class="logincontent">
            <img src="assets/login box.png" alt="Signup Box">
            <!-- Signup Form -->
            <form method="POST" action="signup.php" class="login-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">SIGN UP</button>
            </form>
        </div>
        <div class="logintext">
            <p>Already have an account?</p>
            <a href="login.php">LOG IN</a>
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
