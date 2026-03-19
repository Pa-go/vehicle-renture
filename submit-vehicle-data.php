<?php
// 1. Database Connection
include './database/db_config.php'; 
session_start();

$target_dir = "uploads/";

// Create directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to add a vehicle.");
    }

    // --- 2. COLLECT & SANITIZE DATA ---
    $owner_id     = $_SESSION['user_id']; 
    $vehicle_name = htmlspecialchars(trim($_POST['vehicle_name']));
    $brand         = htmlspecialchars(trim($_POST['brand']));
    $model         = htmlspecialchars(trim($_POST['model']));
    $vehicle_type = $_POST['vehicle_type']; 
    $plate_number = htmlspecialchars(trim($_POST['plate_number']));
    $fuel_type    = $_POST['fuel_type'];
    $color         = htmlspecialchars(trim($_POST['color']));
    $price         = (double)$_POST['price'];
    $discount      = (double)$_POST['discount'];
    $description   = htmlspecialchars(trim($_POST['description']));
    $condition     = $_POST['condition'];
    $features      = htmlspecialchars(trim($_POST['features']));
    $status        = $_POST['status'];

    $final_price = $price - $discount;

    // --- 3. IMAGE LOGIC ---
    $uploadOk = 1;
    $db_path = "";

    if(!isset($_FILES["vehicle_image"]) || $_FILES["vehicle_image"]["error"] !== 0) {
        $uploadOk = 0;
        echo "Error: Please upload a valid image.";
    } else {
        $image_name = basename($_FILES["vehicle_image"]["name"]);
        $extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        // Use time() to ensure unique filenames
        $target_file = $target_dir . time() . "_" . bin2hex(random_bytes(4)) . "." . $extension; 
        
        $check = getimagesize($_FILES["vehicle_image"]["tmp_name"]);
        if($check === false) { 
            $uploadOk = 0; 
            echo "File is not an image."; 
        }
    }

    // --- 4. SAVE TO DATABASE ---
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $target_file)) {
            
            $db_path = $target_file; 

            $sql = "INSERT INTO vehicles 
                    (owner_id, vehicle_name, brand, model, vehicle_type, plate_number, fuel_type, color, price, discount, final_price, description, v_condition, features, status, vehicle_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            // i=int, s=string, d=double
            // Total 16 parameters: i (1), s (7), d (3), s (5)
            $types = "isssssssdddsssss";
            
            $stmt->bind_param($types, 
                $owner_id, $vehicle_name, $brand, $model, $vehicle_type, 
                $plate_number, $fuel_type, $color, $price, $discount, 
                $final_price, $description, $condition, $features, $status, $db_path
            );

            if ($stmt->execute()) {
                // Redirect back to lender dashboard with success message
                header("Location: pages/lender.php?success=vehicle_added"); 
                exit(); 
            } else {
                echo "<h1>Database Error: " . $stmt->error . "</h1>";
            }
        } else {
            echo "Error: The server could not save the uploaded image.";
        }
    }
}
?>