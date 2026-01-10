<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo "You need to log in first to place an order.";
    exit();
}

require 'includes/db.php';

// Fetch user details
$user_id = $_SESSION['user_id']; 
$user_email = $user_contact = $user_address = $user_name = null;

try {
    $stmt = $pdo->prepare("SELECT name, email, contact, address FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_name = $user['name'];
        $user_email = $user['email'];
        $user_contact = $user['contact'];
        $user_address = $user['address'];
    }
} catch (PDOException $e) {
    die("Error fetching user details: " . $e->getMessage());
}

// Notification messages
$success_message = null;
$error_message = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_details'])) {
    // Update user details
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, contact = :contact, address = :address WHERE id = :user_id");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'contact' => $contact,
            'address' => $address,
            'user_id' => $user_id
        ]);
        $success_message = "Your details have been updated!";
    } catch (PDOException $e) {
        $error_message = "Error updating details: " . $e->getMessage();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    // Fetch order data
    $user_id = $_SESSION['user_id']; // User ID from session
    $product_type = 'Frankenstein'; // Hardcoded product type for this example
    $name = $user_name;
    $quantity = $_POST['quantity'];
    $address = $user_address;
    $contact = $user_contact;
    $email = $user_contact;

    try {
        // Prepare the SQL query to insert order into the database
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_type, name, quantity, address, contact, email) 
                               VALUES (:user_id, :product_type, :name, :quantity, :address, :contact, :email)");
        
        // Execute the query with the form data
        $stmt->execute([
            'user_id' => $user_id,
            'product_type' => $product_type,
            'name' => $name,
            'quantity' => $quantity,
            'address' => $address,
            'contact' => $contact,
            'email' => $email
        ]);

        $success_message = "Your order has been placed successfully!";
       
    } catch (PDOException $e) {
        $error_message = "We encountered an issue with your order. Please ensure all your details are complete before submitting.";
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
    <link rel="stylesheet" href="css/expansion.css">

    <style>
    

    </style>
</head>
<body>
    <!-- Notification -->
    <?php if ($error_message): ?>
        <div class="notification error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="notification"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <header class="navigation">
        <div class="nav">
            <a href="index.php">HOME</a>
            <a href="frankenstein.php">EXPANSION</a>
        </div>
    </header>
    <!-- Customer Details Section -->
    <section class="userDetails">
        <h2>YOUR DETAILS</h2>
        <div class="read-only-details">
            <p><strong>Name:</strong> <?php echo $user_name ?: 'Not provided'; ?></p>
            <p><strong>Email:</strong> <?php echo $user_email ?: 'Not provided'; ?></p>
            <p><strong>Phone:</strong> <?php echo $user_contact ?: 'Not provided'; ?></p>
            <p><strong>Address:</strong> <?php echo $user_address ?: 'Not provided'; ?></p>
            <a href="javascript:void(0);" id="updateBtn" class="update-profile-link">Update Details</a>
        </div>
      
    </section>

    <!-- Order Form -->
    <form  class="form-border" method="POST" action="expansion.php">
        <section class="classicsec2">
            <h1>ORDER DETAILS</h1>
            <input type="number" name="quantity" id="quantity" placeholder="Quantity" required min="1" oninput="calculateTotal()"><br>
            <p>Medical Mayhem Table Top Game<br>
            <span style="color: rgb(205, 203, 203);">Frankenstein</span>
            </p>
        </section>

        <section class="read-only-details">
            
            <h1>PURCHASE DETAILS</h1> 
            <p id="shippingFee" style="display: none;">Shipping Fee: ₱50.00</p> 
            <p>Purchase Total: <span id="totalAmount">₱0.00</span>
           
        </section>

        <section class="classicsec4">
            <a class="cancel-btn" href="index.php">Cancel</a>
            <button type="submit" name="place_order">Confirm</button>
            
        </section>
    </form>

    <!-- Modal for Update Form -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            
            <form method="POST" action="expansion.php">
                <span class="close">&times;</span>
                <input type="text" name="name" placeholder="Name" value="<?php echo $user_name; ?>" required><br>
                <input type="email" name="email" placeholder="Email" value="<?php echo $user_email; ?>" required><br>
                <input type="text" name="contact" placeholder="Contact" value="<?php echo $user_contact; ?>" required><br>
                <input type="text" name="address" placeholder="Address" value="<?php echo $user_address; ?>" required><br><br>
                <button type="submit" name="update_details">Update Details</button>
            </form>
        </div>
    </div>
<style>


    /* Close button styles */
    .modal .close {
        font-size: 24px;
        color: #aaa;
        position: absolute;
        top: 70px;
        right: 30px;
        cursor: pointer;
        z-index: 10000;
    }

    .modal .close:hover {
        color: #000;
    }

    /* Optional: Button hover effect */
    button:hover {
        transform: scale(1.1);
    }
</style>
    <script>
        // Open the modal
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("updateBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal if user clicks anywhere outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // JavaScript function to calculate the total
        function calculateTotal() {
            const quantity = document.getElementById('quantity').value;
            const price = 850;
            const shippingFee = 50;
            const shippingFeeElement = document.getElementById('shippingFee');

            let total = (quantity >= 1 ? quantity * price : 0);

            if (quantity > 0) {
                shippingFeeElement.style.display = 'block';
                total += shippingFee;
            } else {
                shippingFeeElement.style.display = 'none';
            }

            document.getElementById('totalAmount').innerText = '₱' + total.toFixed(2);
        }
    </script>
</body>
</html>
