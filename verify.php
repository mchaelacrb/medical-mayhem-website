<?php
require 'includes/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);
        header("Location: login.php");
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
