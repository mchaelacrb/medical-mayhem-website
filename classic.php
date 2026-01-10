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
    $product_type = 'Classic'; // Hardcoded product type for this example
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
    <link rel="stylesheet" href="css/classic.css">

    <style>

.nav a {
    color: white;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    position: relative;
    display: inline-block;

}



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
    <a href="index.php" >HOME</a>
    <a href="frankenstein.php" >EXPANSION</a>
</div>
    </header>
    <!-- Customer Details Section -->
    <section class="userDetails">
  <div class="order-details">
    <div class="order-details-header">
        <img src="assets/logo.png">
    </div>
    <div class="order-info">
         <h2>Medical Mayhem</h2>
        <p style="color:darkgoldenrod;">Classic</p>
        <p>Price: 900.00</p>
        <div class="quantity-box">
        <button type="button" onclick="changeQty(-1)">−</button>

        <input 
            type="number"
            name="quantity"
            id="quantity"
            value="1"
            min="1"
            required
            oninput="calculateTotal()"
        >

        <button type="button" onclick="changeQty(1)">+</button>
        </div>
    </div>
   
        
  </div>
    
      
    </section>

    <!-- Order Form -->
    <form  class="form-border" method="POST" action="classic.php">
        <section class="classicsec2">
            <h1>ORDER DETAILS</h1>
            <input type="number" name="quantity" id="quantity" placeholder="Quantity" required min="1" oninput="calculateTotal()"><br>
            <p>Medical Mayhem Table Top Game<br>
            <span style="color: rgb(205, 203, 203);">Classic</span>
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
           
            <form method="POST" action="classic.php">
                
                 <span class="close">&times;</span>
                <input type="text" name="name" placeholder="Name" value="<?php echo $user_name; ?>" required><br>
                <input type="email" name="email" placeholder="Email" value="<?php echo $user_email; ?>" required><br>
                <input type="text" name="contact" placeholder="Contact" value="<?php echo $user_contact; ?>" required><br>
                <input type="text" name="address" placeholder="Address" value="<?php echo $user_address; ?>" required><br><br>
                <button type="submit" name="update_details">Update Details</button>
            </form>
        </div>
    </div>

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
       
        function changeQty(amount) {
            const qtyInput = document.getElementById("quantity");
            let current = parseInt(qtyInput.value) || 1;
            current += amount;

            if (current < 1) current = 1;

            qtyInput.value = current;
            calculateTotal(); // keeps your function working
        }


    </script>
</body>
</html>
