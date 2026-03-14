<?php
// 1. Database Connection
include './database/db_config.php'; 
session_start();

$target_dir = "uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to add a vehicle.");
    }

    // --- 2. COLLECT DATA ---
    $vehicle_name = $_POST['vehicle_name'];
    $brand        = $_POST['brand'];
    $model        = $_POST['model'];
    $vehicle_type = $_POST['vehicle_type']; 
    $plate_number = $_POST['plate_number'];
    $fuel_type    = $_POST['fuel_type'];
    $color        = $_POST['color'];
    $price        = $_POST['price'];
    $discount     = $_POST['discount'];
    $description  = $_POST['description'];
    $condition    = $_POST['condition'];
    $features     = $_POST['features'];
    $status       = $_POST['status'];
    $owner_id     = $_SESSION['user_id']; 

    $final_price = $price - $discount;

    // --- 3. IMAGE LOGIC ---
    $image_name = basename($_FILES["vehicle_image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name; 
    
    $uploadOk = 1;
    if(isset($_FILES["vehicle_image"])) {
        $check = getimagesize($_FILES["vehicle_image"]["tmp_name"]);
        if($check === false) { $uploadOk = 0; echo "File is not an image."; }
    }

    // --- 4. SAVE TO DATABASE ---
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $target_file)) {
            
            $db_path = $target_file; 

            // MATCHING DATABASE COLUMN NAMES EXACTLY
            $sql = "INSERT INTO vehicles 
                    (owner_id, vehicle_name, brand, model, vehicle_type, plate_number, fuel_type, color, price, discount, final_price, description, v_condition, features, status, vehicle_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            // i = integer, s = string, d = double (decimal)
            $stmt->bind_param("isssssssdddsssss", 
                $owner_id, $vehicle_name, $brand, $model, $vehicle_type, 
                $plate_number, $fuel_type, $color, $price, $discount, 
                $final_price, $description, $condition, $features, $status, $db_path
            );

            if ($stmt->execute()) {
                header("Location: ./pages/lender.php?success=vehicle_added"); 
                exit(); 
            } else {
                echo "<h1>SQL Error: " . $stmt->error . "</h1>";
            }
        } else {
            echo "Error: Could not move the file.";
        }
    }
}
?>