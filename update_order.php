<?php
require 'includes/db.php';  // Make sure this path is correct
session_start();
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in.";
    exit();
}

// Check if action and order_id are set in the request
if (isset($_POST['action'], $_POST['order_id'])) {
    $action = $_POST['action'];
    $order_id = $_POST['order_id'];

    // Retrieve the order to verify it belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :order_id AND user_id = :user_id");
    $stmt->execute([
        'order_id' => $order_id,
        'user_id' => $_SESSION['user_id']
    ]);

    $order = $stmt->fetch();

    if (!$order) {
        echo "Order not found or does not belong to you.";
        exit();
    }

    if ($action === 'received') {
        // Delete the order from the database
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :order_id");
        $stmt->execute(['order_id' => $order_id]);
        $_SESSION['message'] = "Order marked as received and removed from the list.";
    } elseif ($action === 'cancel') {
        // Update the order status to 'Request for Cancel'
        $stmt = $pdo->prepare("UPDATE orders SET status = 'Request for Cancel' WHERE id = :order_id");
        $stmt->execute(['order_id' => $order_id]);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER']; // Replace with your Gmail address
        $mail->Password = $_ENV['SMTP_PASS']; // Replace with your Gmail password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        // Email content
        $mail->setFrom($_ENV['SMTP_USER'], 'Medical Mayhem'); // Replace with your sender email and name
        $mail->addAddress($_ENV['SMTP_USER']); // Replace with admin's email
        $mail->Subject = "Order Cancel Request";
        $mail->Body = "The user with ID {$_SESSION['user_id']} has requested to cancel order ID {$order_id}.";

        // Send email
        if ($mail->send()) {
            $_SESSION['message'] = "Cancel request submitted. Admin has been notified.";
        } else {
            $_SESSION['message'] = "Cancel request submitted, but there was an issue sending the email.";
        }
    } else {
        $_SESSION['message'] = "Invalid action.";
    }
} else {
    $_SESSION['message'] = "No action or order specified.";
}

// Redirect back to the account/orders page
header("Location: account.php");
exit();
?>
