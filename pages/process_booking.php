<?php
// process_booking.php
include '../database/db_config.php';
session_start();

// Prevent PHP errors from leaking into the JSON response
error_reporting(0);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
        exit;
    }

    $tenant_id = $_SESSION['user_id'];
    $vehicle_id = (int)$_POST['vehicle_id'];
    $amount = (float)$_POST['amount'];
    $method = htmlspecialchars($_POST['method'] ?? 'Card'); 

    // 2. Start Transaction (Ensures both queries succeed together)
    $conn->begin_transaction();

    try {
        // Action A: Insert into Bookings
        $query1 = "INSERT INTO bookings (tenant_id, vehicle_id, amount, payment_method, booking_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("iids", $tenant_id, $vehicle_id, $amount, $method);
        $stmt1->execute();

        // Action B: Update Vehicle Status to 'Sold'
        $query2 = "UPDATE vehicles SET status = 'Sold' WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $vehicle_id);
        $stmt2->execute();

        // If we reach here, both worked. Commit the changes!
        $conn->commit();
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        // If anything fails, undo everything (Rollback)
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>