<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Details | Renture</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
      body {
    background: #E6F0F4;
    font-family: "Poppins", sans-serif;
}

/* CONTAINER */
.form-container {
    max-width: 750px;
    margin: 40px auto;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(18px);
    padding: 30px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 10px 30px rgba(0,31,63,0.15);
}

/* TITLE */
.form-container h2 {
    text-align: center;
    color: #001F3F;
    margin-bottom: 20px;
}

/* GROUP */
.form-group {
    margin-bottom: 18px;
}

/* LABEL */
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #001F3F;
}

/* INPUTS */
input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid rgba(0,31,63,0.2);
    background: rgba(255,255,255,0.6);
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

/* FOCUS EFFECT */
input:focus,
textarea:focus,
select:focus {
    border-color: #FFD700;
    box-shadow: 0 0 8px rgba(255,215,0,0.4);
    background: rgba(255,255,255,0.9);
}

/* RADIO */
.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.radio-group label {
    font-weight: normal;
    color: #193857;
}

/* SUBMIT BUTTON */
.submit-button {
    background: #FFD700;
    color: #001F3F;
    padding: 14px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    width: 100%;
    margin-top: 10px;
    transition: all 0.3s ease;
}

.submit-button:hover {
    background: #e6c200;
    transform: scale(1.02);
}

/* FILE INPUT CUSTOM */
input[type="file"] {
    background: rgba(255,255,255,0.5);
    padding: 10px;
    cursor: pointer;
}

/* MESSAGE BOX */
#messageBox {
    background: #001F3F !important;
    color: #FFFFFF;
}

/* LINKS */
a {
    text-decoration: none;
} 
    </style>
</head>
<body>

<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include '../includes/header.php'; ?>

<div id="mainContent" class="main-content">

<div class="form-container">
    <h2>🚗 Vehicle Details</h2>
    <form action="../submit-vehicle-data.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="vehicle-name">1. Name of Vehicle:</label>
            <input type="text" id="vehicle-name" name="vehicle_name" required>
        </div>

        <div class="form-group">
            <label for="brand">2. Brand:</label>
            <input type="text" id="brand" name="brand" required>
        </div>

        <div class="form-group">
            <label for="model">3. Model:</label>
            <input type="text" id="model" name="model" required>
        </div>

        <div class="form-group">
            <label>4. Vehicle Type:</label>
            <div class="radio-group">
                <input type="radio" id="type-car" name="vehicle_type" value="Car" required>
                <label for="type-car">a. Car</label>

                <input type="radio" id="type-bike" name="vehicle_type" value="Bike">
                <label for="type-bike">b. Bike</label>
            </div>
        </div>

        <div class="form-group">
            <label for="plate-number">5. Plate Number:</label>
            <input type="text" id="plate-number" name="plate_number" required>
        </div>

        <div class="form-group">
            <label for="fuel-type">6. Fuel Type:</label>
            <select id="fuel-type" name="fuel_type" required>
                <option value="">-- Select Fuel Type --</option>
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
            </select>
        </div>

        <div class="form-group">
            <label for="color">7. Color:</label>
            <input type="text" id="color" name="color">
        </div>

        <div class="form-group">
            <label for="price">8. Price ($):</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="discount">9. Discount ($):</label>
            <input type="number" id="discount" name="discount" min="0" step="0.01" value="0">
        </div>

        <div class="form-group">
            <label for="final-price">10. Final Price ($) - Calculated:</label>
            <input type="number" id="final-price" name="final_price" readonly disabled placeholder="Calculated Final Price">
        </div>
        
        <div class="form-group">
            <label for="description">11. Vehicle Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="condition">12. Condition:</label>
            <select id="condition" name="condition" required>
                <option value="">-- Select Condition --</option>
                <option value="New">New</option>
                <option value="Used">Used</option>
                <option value="Certified Pre-Owned">Certified Pre-Owned</option>
            </select>
        </div>

        <div class="form-group">
            <label for="features">13. Features (e.g., Airbags, Bluetooth, GPS, ABS):</label>
            <textarea id="features" name="features" rows="2" placeholder="List key features separated by commas (e.g., Airbags, Bluetooth, ABS)"></textarea>
        </div>
        <div class="form-group">
            <label for="status">14. Status:</label>
            <select id="status" name="status" required>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
                <option value="Pending">Pending</option>
            </select>
        </div>

        <div class="form-group">
            <label for="vehicle-image">15. Upload Vehicle Image:</label>
            <input type="file" id="vehicle-image" name="vehicle_image" accept="image/*" required>
        </div>

        <button type="submit" class="submit-button">Submit Vehicle Details</button>
 
    </form>
</div>

</div>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>