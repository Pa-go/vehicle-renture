<?php
// process_booking.php
include '../database/db_config.php';
session_start();


// Prevent PHP errors from leaking into the JSON response
error_reporting(0);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    $tenant_id = $_SESSION['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $amount = $_POST['amount'];
    // FIXED: Changed 'paymentMethod' to 'method' to match your JS
    $method = $_POST['method']; 

    // 1. Insert into Bookings Table
    $query1 = "INSERT INTO bookings (tenant_id, vehicle_id, amount, payment_method) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("iids", $tenant_id, $vehicle_id, $amount, $method);

    if ($stmt1->execute()) {
        // 2. Update Vehicle Status to 'Sold'
        $query2 = "UPDATE vehicles SET status = 'Sold' WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $vehicle_id);
        $stmt2->execute();

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>