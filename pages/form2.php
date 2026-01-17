<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Details | Renture</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #001F3F;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }
        .radio-group label {
            display: inline-block;
            font-weight: normal;
            margin-left: 5px;
        }
        .submit-button {
            background: linear-gradient(135deg, #001F3F, #193857);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }
        .submit-button:hover {
            background: linear-gradient(135deg, #193857, #001F3F);
        }
    </style>
</head>
<body>

<!-- Temporary Message Box -->
<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include 'header.php'; ?>

<div id="mainContent" class="main-content">

<div class="form-container">
    <h2>🚗 Vehicle Details</h2>
    <form action="/submit-vehicle-data" method="POST">

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

        <button type="submit" class="submit-button">Submit Vehicle Details</button>

    </form>
</div>

</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>