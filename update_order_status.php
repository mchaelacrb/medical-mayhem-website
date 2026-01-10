<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $statuses = $_POST['status']; // Array of status updates: [order_id => status]

    try {
        // Start a transaction for batch update
        $pdo->beginTransaction();

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :order_id");

        // Loop through each order and update the status
        foreach ($statuses as $order_id => $status) {
            $stmt->execute([
                'status' => $status,
                'order_id' => $order_id
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect back to the admin page
        header("Location: admin.php");
        exit();
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        echo "Error updating order statuses: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
