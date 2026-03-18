<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Only set header for JSON responses when actually processing a POST request
    header('Content-Type: application/json');
    include '../database/db_config.php';

    $v_id = $_POST['vehicle_id'] ?? 0;
    $amount = $_POST['amount'] ?? 0;
    $user_id = $_SESSION['user_id'] ?? 1; // Default to 1 for demo if no session
    $method = $_POST['method'] ?? 'Card';

    // Update vehicle to Sold
    $sql_update = "UPDATE vehicles SET status = 'Sold' WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $v_id);
    
    // Insert booking record
    $sql_book = "INSERT INTO bookings (tenant_id, vehicle_id, amount, payment_method) VALUES (?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql_book);
    $stmt2->bind_param("iids", $user_id, $v_id, $amount, $method);

    if ($stmt->execute() && $stmt2->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit;
}
?>
