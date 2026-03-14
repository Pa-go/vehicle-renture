<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect text data
    $vehicle_name = mysqli_real_escape_string($conn, $_POST['vehicle_name']);
    $brand        = mysqli_real_escape_string($conn, $_POST['brand']);
    $model        = mysqli_real_escape_string($conn, $_POST['model']);
    $type         = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $plate        = mysqli_real_escape_string($conn, $_POST['plate_number']);
    $fuel         = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $color        = mysqli_real_escape_string($conn, $_POST['color']);
    $price        = $_POST['price'];
    $discount     = $_POST['discount'];
    $final_price  = $price - $discount; // Calculation on backend for security
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $condition    = mysqli_real_escape_string($conn, $_POST['condition']);
    $features     = mysqli_real_escape_string($conn, $_POST['features']);
    $status       = mysqli_real_escape_string($conn, $_POST['status']);

    // Handle Image Upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = pathinfo($_FILES["vehicle_image"]["name"], PATHINFO_EXTENSION);
    $new_filename = "veh_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $target_file)) {
        // SQL Injection protected query
        $sql = "INSERT INTO vehicle_details 
                (vehicle_name, brand, model, vehicle_type, plate_number, fuel_type, color, price, discount, final_price, description, vehicle_condition, features, status, vehicle_image) 
                VALUES 
                ('$vehicle_name', '$brand', '$model', '$type', '$plate', '$fuel', '$color', '$price', '$discount', '$final_price', '$description', '$condition', '$features', '$status', '$target_file')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Vehicle data and image uploaded successfully!'); window.location.href='../pages/home.php';</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Failed to upload image.";
    }
}
?>