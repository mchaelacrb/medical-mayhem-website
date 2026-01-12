<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to view your orders.'); window.location.href = 'login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; //// Retrieve the logged-in user's ID

// Fetch only the logged-in user's orders
$stmt = $pdo->prepare("
    SELECT orders.id, orders.quantity, orders.created_at, orders.product_type, orders.status
    FROM orders
    WHERE orders.user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);

$orders = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT username, created_at, email, password FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$account = $stmt->fetch();
?>
<!-- hello -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo.png">
    <title>MEDICAL MAYHEM</title>
    <link rel="stylesheet" href="css/account.css">
    <style>
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

            <!-- Section 1 -->

            <section class="accsec1">               
                <div class="accsec1-content">
                    <h1>ACCOUNT INFORMATION</h1><br>
                    <p><strong>Username:</strong> <?= htmlspecialchars($account['username']) ?></p>
                    <p><strong>Account Created On:</strong> <?= htmlspecialchars($account['created_at']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($account['email']) ?></p>
                </div>
            </section>

            <!-- Section 2 -->

<section class="accsec2">
    <div class="accsec2-content">
        <h1>ORDERS</h1><br>
        <?php foreach ($orders as $order): ?>
            <div class="outer-order-div">
                <div class="order-details">
                    <h3><?= htmlspecialchars($order['product_type']) ?></h3>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                </div>
                <div class="order-buttons">
                    <!-- Order Received Button -->
                    <form method="post" action="update_order.php">
                   <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
    
                        <!-- Order Received Button -->
                        <button 
                          class="<?= ($order['status'] === 'Pending' || $order['status'] === 'Request for Cancel' ||
                              $order['status'] === 'Cancelled'  ) ? 'btn-disabled' : 'btn-received' ?>"
                        type="submit" 
                            name="action" 
                            value="received"
                            <?= ($order['status'] === 'Pending' || $order['status'] === 'Request for Cancel' || $order['status'] === 'Cancelled'
                            ) ? 'disabled' : '' ?>
                            >
                            Order Received
                        </button>
                    </form>

                    <form method="post" action="update_order.php">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                        
                        <!-- Cancel Order Button -->
                        <button 
                            class="<?= ($order['status'] === 'Shipped' || $order['status'] === 'Request for Cancel' ||
                              $order['status'] === 'Cancelled'||
                              $order['status'] === 'Completed') ? 'btn-disabled' : 'btn-cancel' ?>" 
                            type="submit" 
                            name="action" 
                            value="cancel"
                            <?= $order['status'] === 'Shipped' || $order['status'] === 'Request for Cancel' || $order['status'] === 'Cancelled'  || $order['status'] === 'Completed' ? 'disabled' : '' ?>
                        >
                            Cancel Order
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- Section 3 -->

<section class="accsec3">
    <a class="changepass-btn" href="changepass.php">CHANGE PASSWORD</a>
    <a class="exit-btn" href="logout.php">LOG OUT</a>
</section>

</body>
</html>
