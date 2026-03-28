<?php
// 1. SESSION & DATABASE
include '../database/db_config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/log_reg.php");
    exit();
}

if (isset($_GET['ajax_fetch'])) {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sync_sql = "SELECT id, vehicle_name as name, status as availability FROM vehicles";
    if (!empty($search)) {
        $sync_sql .= " WHERE vehicle_name LIKE '%$search%' OR brand LIKE '%$search%'";
    }
    $sync_result = $conn->query($sync_sql);
    $sync_data = [];
    while($row = $sync_result->fetch_assoc()) { $sync_data[] = $row; }
    echo json_encode($sync_data);
    exit();
}

$sql = "SELECT id, vehicle_name as name, vehicle_type as type, price, final_price, discount, 
                plate_number as plate, status as availability, vehicle_image as image,
                brand, model, fuel_type as fuel, color, v_condition as `condition`,
                description, features FROM vehicles";

$result = $conn->query($sql);
$db_vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { $db_vehicles[] = $row; }
}
$json_vehicles = json_encode($db_vehicles);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Cars Listing | Renture</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { padding-top: 5px; background: #E6F0F4; font-family: 'Poppins', sans-serif; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; padding: 30px; }
        .card { background: rgba(255,255,255,0.15); backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 10px 25px rgba(0,31,63,0.15); transition: all 0.3s ease; }
        .card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 15px 35px rgba(0,31,63,0.25); }
        .card-img { width: 100%; height: 220px; position: relative; overflow: hidden; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .card:hover .card-img img { transform: scale(1.1); }
        .badge { position: absolute; top: 12px; right: 12px; display: inline-block; width: fit-content; white-space: nowrap; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; color: #FFFFFF; text-transform: uppercase; z-index: 5; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .available { background: #28a745; }
        .unavailable { background: #dc3545; }
        .card-body { padding: 18px; display: flex; flex-direction: column; }
        .card-body h4 { margin: 0; font-size: 18px; color: #001F3F; }
        .price-tag { font-size: 22px; font-weight: bold; color: #001F3F; margin: 12px 0; }
        .btn-group { display: flex; gap: 10px; margin-top: auto; }
        .btn { flex: 1; padding: 10px; border-radius: 12px; font-weight: 600; cursor: pointer; border: none; transition: all 0.3s ease; }
        .btn-details { background: transparent; border: 1px solid #001F3F; color: #001F3F; }
        .btn-details:hover { background: #001F3F; color: #FFFFFF; }
        .btn-book { background: #FFD700; color: #001F3F; }
        .btn-book:hover { background: #e6c200; transform: scale(1.05); }

        .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,31,63,0.7); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .popup-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 20px; width: 850px; max-width: 95%; overflow: hidden; display: flex; box-shadow: 0 20px 45px rgba(0,31,63,0.3); }
        .popup-left { width: 40%; background: rgba(0,31,63,0.05); display: flex; align-items: center; justify-content: center; padding: 10px; }
        .popup-right { width: 60%; padding: 25px; position: relative; max-height: 85vh; overflow-y: auto; }
        .step-box { margin-bottom: 20px; }
        .step-box h4 { color: #001F3F; border-bottom: 1px solid rgba(0,31,63,0.2); padding-bottom: 5px; margin-bottom: 10px; }
        .step-box p { font-size: 14px; margin-bottom: 5px; color: #555; }
        .step-box p b { color: #333; width: 140px; display: inline-block; }
        .booking-btn { background: #FFD700; color: #001F3F; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .booking-btn:hover { background: #e6c200; }

        .btn-book:disabled {
            background: #cccccc !important;
            color: #666666;
            cursor: not-allowed;
            opacity: 0.6;
            transform: none !important;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="page-title" style="text-align:center; padding: 30px 20px 10px;">
        <h1 style="color:#001F3F; font-size:30px;">Hello Tenant</h1>
        <p style="color:#193857;">Choose from our wide selection of quality rental vehicles</p>
    </div>

    <div class="filter-box" style="display:flex; justify-content:center; gap:15px; margin:20px 0; align-items:center;">
        <label style="color:#001F3F; font-weight:500;">Filter By Type:</label>
        <select id="filterType" onchange="renderTenantVehicles()" style="padding:8px 12px; border-radius:10px; border:1px solid rgba(0,31,63,0.2); background:rgba(255,255,255,0.6);">
            <option value="All">All Vehicles</option>
            <option value="Car">Cars</option>
            <option value="Bike">Bikes</option>
        </select>
        <label>Availability:</label>
        <select id="filterStatus" onchange="renderTenantVehicles()" style="padding:8px 12px; border-radius:10px; border:1px solid rgba(0,31,63,0.2);">
            <option value="All">All Status</option>
            <option value="Available">Available Only</option>
        </select>
    </div>

    <div class="grid" id="tenantVehicleGrid"></div>
</div>

<div id="vehiclePopup" class="popup-overlay" onclick="outsideClick(event)">
  <div class="popup-card">
    <div class="popup-left">
        <img id="popupImage" style="max-width:100%; border-radius:10px;">
    </div>
    <div class="popup-right">
      <span onclick="closePopup()" style="position:absolute; top:15px; right:20px; font-size:28px; cursor:pointer; color:#001F3F; font-weight:bold;">×</span>
      <h2 id="popupName" style="color:#001F3F; margin-bottom:15px;"></h2>

      <div class="step-box">
          <h4>Vehicle Information</h4>
          <p><b>Brand:</b> <span id="p_brand"></span></p>
          <p><b>Model:</b> <span id="p_model"></span></p>
          <p><b>Type:</b> <span id="p_type"></span></p>
          <p><b>Plate Number:</b> <span id="p_plate"></span></p>
          <p><b>Fuel Type:</b> <span id="p_fuel"></span></p>
          <p><b>Color:</b> <span id="p_color"></span></p>
          <p><b>Condition:</b> <span id="p_condition"></span></p>
          <p><b>Features:</b> <span id="p_features"></span></p>
          <p><b>Description:</b> <span id="p_description"></span></p>
      </div>

      <div class="step-box">
          <h4>Pricing</h4>
          <p><b>Price / day:</b> <span id="p_price"></span></p>
          <p><b>Discount:</b> <span id="p_discount"></span></p>
          <p><b>Final Price:</b> <span id="p_final_price"></span></p>
      </div>

      <div class="step-box">
          <h4>Booking</h4>
          <p><b>Start Date:</b> <input type="date" id="bookStart" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>End Date:</b> <input type="date" id="bookEnd" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>Duration:</b> <span id="calcDuration">0</span> Days</p>
          <p><b>Total Price:</b> ₹<span id="calcTotal">0</span></p>
      </div>

      <button id="popupBookBtn" class="booking-btn" onclick="bookNow()">Book Now</button>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<script>
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;
const mockData = [
    {
        id: 1001,
        name: "Toyota Camry Hybrid",
        type: "Car",
        price: 4000,
        final_price: 4000,
        discount: 0,
        availability: "Available",
        brand: "Toyota",
        model: "Camry",
        fuel: "Hybrid",
        plate: "SAMPLE-01",
        color: "White",
        condition: "Excellent",
        description: "A premium hybrid sedan for smooth city drives.",
        features: "GPS, Bluetooth, Sunroof",
        image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400"
    }
];
const allVehicles = [...dbData, ...mockData];
let currentVehicle = null;

function renderTenantVehicles() {
    const typeFilter = document.getElementById("filterType").value;
    const statusFilter = document.getElementById("filterStatus").value;
    const grid = document.getElementById("tenantVehicleGrid");
    grid.innerHTML = "";

    const filtered = allVehicles.filter(v => {
        const typeMatch = (typeFilter === "All" || v.type === typeFilter);
        const statusMatch = (statusFilter === "All" || v.availability === "Available" || v.availability == 1);
        return typeMatch && statusMatch;
    });

    if (filtered.length === 0) {
        grid.innerHTML = "<p style='text-align:center; color:#001F3F; padding:40px;'>No vehicles found.</p>";
        return;
    }

    filtered.forEach((vehicle) => {
        let imgSrc = (vehicle.image && vehicle.image.startsWith('http')) ? vehicle.image : '../' + vehicle.image;
        const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);

        grid.innerHTML += `
            <div class="card">
                <div class="card-img">
                    <img src="${imgSrc}" onerror="this.src='https://placehold.co/400x250'">
                    <div class="badge ${isAvailable ? 'available' : 'unavailable'}">${isAvailable ? 'Available' : 'Unavailable'}</div>
                </div>
                <div class="card-body">
                    <h4>${vehicle.name}</h4>
                    <div class="price-tag">₹ ${Number(vehicle.final_price || vehicle.price).toLocaleString()}<small style="font-size:13px; font-weight:normal; color:#888;"> / day</small></div>
                    <div class="btn-group">
                        <button class="btn btn-details" onclick="viewDetails(${vehicle.id})">View Details</button>
                        <button class="btn btn-book" onclick="bookNowDirect(${vehicle.id})" ${isAvailable ? '' : 'disabled'}>Book Now</button>
                    </div>
                </div>
            </div>`;
    });
}

function viewDetails(id) {
    const vehicle = allVehicles.find(v => v.id == id);
    if (!vehicle) return;
    currentVehicle = vehicle;

    let imgSrc = (vehicle.image && vehicle.image.startsWith('http')) ? vehicle.image : '../' + vehicle.image;

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name || "N/A";
    document.getElementById("p_brand").innerText = vehicle.brand || "N/A";
    document.getElementById("p_model").innerText = vehicle.model || "N/A";
    document.getElementById("p_type").innerText = vehicle.type || "N/A";
    document.getElementById("p_plate").innerText = vehicle.plate || "N/A";
    document.getElementById("p_fuel").innerText = vehicle.fuel || "N/A";
    document.getElementById("p_color").innerText = vehicle.color || "N/A";
    document.getElementById("p_condition").innerText = vehicle.condition || "N/A";
    document.getElementById("p_features").innerText = vehicle.features || "N/A";
    document.getElementById("p_description").innerText = vehicle.description || "N/A";
    document.getElementById("p_price").innerText = "₹ " + Number(vehicle.price).toLocaleString();
    document.getElementById("p_discount").innerText = vehicle.discount ? "₹ " + Number(vehicle.discount).toLocaleString() : "None";
    document.getElementById("p_final_price").innerText = "₹ " + Number(vehicle.final_price || vehicle.price).toLocaleString();

    // ✅ Set today as minimum date for both inputs
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("bookStart").value = "";
    document.getElementById("bookEnd").value = "";
    document.getElementById("bookStart").min = today;
    document.getElementById("bookEnd").min = today;

    document.getElementById("calcDuration").innerText = "0";
    document.getElementById("calcTotal").innerText = "0";

    document.getElementById("vehiclePopup").style.display = "flex";

    const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
    const popupBtn = document.getElementById("popupBookBtn");

    if (!isAvailable) {
        popupBtn.disabled = true;
        popupBtn.innerText = "Currently Unavailable";
        popupBtn.style.background = "#ccc";
        popupBtn.style.cursor = "not-allowed";
    } else {
        popupBtn.disabled = false;
        popupBtn.innerText = "Book Now";
        popupBtn.style.background = "#FFD700";
        popupBtn.style.cursor = "pointer";
    }
}

function calculateTotal() {
    const startVal = document.getElementById("bookStart").value;
    const endVal = document.getElementById("bookEnd").value;
    const bookBtn = document.getElementById("popupBookBtn");
    
    if (startVal && endVal && currentVehicle) {
        const start = new Date(startVal);
        const end = new Date(endVal);
        const price = parseFloat(currentVehicle.final_price || currentVehicle.price);

        const diffInMs = end - start;
        let daysDiff = Math.ceil(diffInMs / (1000 * 3600 * 24));

        if (daysDiff < 0) {
            alert("End date cannot be before start date!");
            document.getElementById("bookEnd").value = "";
            daysDiff = 0;
        } else if (daysDiff === 0 && startVal === endVal) {
            daysDiff = 1;
        }

        if (price <= 0) {
            bookBtn.disabled = true;
            bookBtn.innerText = "Invalid Price";
            bookBtn.style.background = "#ccc";
            daysDiff = 0;
        } else if (daysDiff > 0) {
            bookBtn.disabled = false;
            bookBtn.innerText = "Book Now";
            bookBtn.style.background = "#FFD700";
        }

        document.getElementById("calcDuration").innerText = daysDiff;
        document.getElementById("calcTotal").innerText = (daysDiff * price).toLocaleString();
    }
}

function bookNowDirect(id) { 
    const duration = parseInt(document.getElementById("calcDuration").innerText);
    const start = document.getElementById("bookStart").value;
    const end = document.getElementById("bookEnd").value;
    const vehicle = allVehicles.find(v => v.id == id);
    
    if (!vehicle) return;

    if (duration <= 0 || !start || !end) {
        alert("Please select valid dates in the details popup first!");
        return;
    }

    const totalPrice = parseFloat(vehicle.final_price || vehicle.price) * duration;

    const params = new URLSearchParams({
        id: vehicle.id,
        name: vehicle.name,
        price: totalPrice,
        brand: vehicle.brand || 'N/A',
        model: vehicle.model || 'N/A',
        type: vehicle.type || 'Vehicle',
        image: vehicle.image || '',
        start: start,
        end: end
    });

    window.location.href = `booking-preview.php?${params.toString()}`; 
}

function bookNow() { 
    if (currentVehicle) bookNowDirect(currentVehicle.id); 
}

function closePopup() { document.getElementById("vehiclePopup").style.display = "none"; }
function outsideClick(e) { if (e.target.id === "vehiclePopup") closePopup(); }

// ✅ Set min date on page load as well
document.addEventListener("DOMContentLoaded", function() {
    renderTenantVehicles();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("bookStart").min = today;
    document.getElementById("bookEnd").min = today;
});
</script>
</body>
</html>