<?php
session_start();
require 'includes/db.php';

// Check if the user is logged in and has the username 'admin'
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Fetch orders and related user details (email, address, contact)
$stmt = $pdo->query("SELECT orders.id, orders.product_type, orders.quantity, orders.created_at, orders.status, users.username
                     FROM orders
                     JOIN users ON orders.user_id = users.id");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo.png">
    <title>MEDICAL MAYHEM</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <!-- Header -->
    <header class="navigation">
        <!-- Add navigation content here -->
    </header>

   <!-- Section 1 -->
<section class="adminsec1">
    <div class="adminsec1-content">
        <h1>ADMIN</h1>
    </div>

    <div class="adminsec1-content2">
        <h1>ORDERS</h1>
      <form id="orderForm" method="POST" action="update_order_status.php">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Date Ordered</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td data-label="Order ID"><?php echo htmlspecialchars($order['id']); ?></td>
                    <td data-label="Username"><?php echo htmlspecialchars($order['username']); ?></td>
                    <td data-label="Product"><?php echo htmlspecialchars($order['product_type']); ?></td>
                    <td data-label="Quantity"><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td data-label="Date Ordered"><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td data-label="Status">
                        <select name="status[<?php echo htmlspecialchars($order['id']); ?>]">
                            <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

    </div>
</section>

    <!-- Section 2 -->
  <section class="adminsec2-btns">
    <a class="logout-btn" href="login.php?logout=true">LOG OUT</a>
    <!-- Save button outside the form -->
    <button type="button" class="save-btn" onclick="submitForm()">SAVE</button>
</section>
<script>
    // JavaScript function to submit the form
    function submitForm() {
        document.getElementById('orderForm').submit(); // Triggers form submission
    }
</script>
<style>
    /* General button styles */
    button {
        padding: 20px;
        width: 150px;
        background-color: #20C84D;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600; 
        color: black;
        border-radius: 15px;
        border: 2px solid black;
        transition: 1s ease;
    }

    button:hover {
        transform: scale(1.1);
        background-color: #20C84D;
    }


</style>
</body>
</html>
