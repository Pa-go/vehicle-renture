<?php
// 1. SESSION & DATABASE (STRICTLY AT THE TOP)
include '../database/db_config.php';
session_start();

// REDIRECT IF NOT LOGGED IN
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/log_reg.php");
    exit();
}

// 2. AJAX FETCH LOGIC (Background sync)
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

// 3. MAIN DATA FETCH
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
        /* YOUR ORIGINAL UI STYLES */
        body { padding-top: 5px; background: #E6F0F4; font-family: 'Poppins', sans-serif; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; padding: 30px; }
        .card { background: rgba(255,255,255,0.15); backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 10px 25px rgba(0,31,63,0.15); transition: all 0.3s ease; }
        .card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 15px 35px rgba(0,31,63,0.25); }
        .card-img { width: 100%; height: 220px; position: relative; overflow: hidden; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .card:hover .card-img img { transform: scale(1.1); }
        .badge { 
    position: absolute; 
    top: 12px; 
    right: 12px; 
    display: inline-block;
    width: fit-content;
    white-space: nowrap;
    padding: 4px 10px; 
    border-radius: 8px; 
    font-size: 11px; 
    font-weight: 700; 
    color: #FFFFFF; 
    text-transform: uppercase; 
    z-index: 5; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
}
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
        
        /* POPUP UI */
        .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,31,63,0.7); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .popup-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 20px; width: 850px; max-width: 95%; overflow: hidden; display: flex; box-shadow: 0 20px 45px rgba(0,31,63,0.3); }
        .popup-left { width: 40%; background: rgba(0,31,63,0.05); display: flex; align-items: center; justify-content: center; padding: 10px; }
        .popup-right { width: 60%; padding: 25px; position: relative; max-height: 85vh; overflow-y: auto; }
        .step-box { margin-bottom: 20px; }
        .step-box h4 { color: #001F3F; border-bottom: 1px solid rgba(0,31,63,0.2); padding-bottom: 5px; margin-bottom: 10px; }
        .booking-btn { background: #FFD700; color: #001F3F; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
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
    <div class="popup-left"><img id="popupImage" class="popup-img" style="max-width:100%; border-radius:10px;"></div>
    <div class="popup-right">
      <span class="popup-close" onclick="closePopup()" style="position:absolute; top:15px; right:20px; font-size:28px; cursor:pointer; color:#001F3F; font-weight:bold;">×</span>
      <h2 id="popupName" style="color:#001F3F; margin-bottom:15px;"></h2>
      <div class="step-box">
          <h4>Vehicle Information</h4>
          <p><b>Brand:</b> <span id="p_brand"></span></p>
          <p><b>Model:</b> <span id="p_model"></span></p>
          <p><b>Plate:</b> <span id="p_plate"></span></p>
          <p><b>Features:</b> <span id="p_features"></span></p>
      </div>
      <div class="step-box">
          <h4>Pricing</h4>
          <p><b>Final Price:</b> <span id="p_final_price"></span></p>
      </div>
      <div class="step-box">
          <h4>Booking</h4>
          <p><b>Start Date:</b> <input type="date" id="bookStart" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>End Date:</b> <input type="date" id="bookEnd" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>Total:</b> ₹<span id="calcTotal">0</span></p>
      </div>
      <button id="popupBookBtn" class="booking-btn" onclick="bookNow()">Book Now</button>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<script>
// YOUR ORIGINAL JAVASCRIPT LOGIC
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;
const mockData = [ { id: 1001, name: "Toyota Camry Hybrid", type: "Car", price: 4000, final_price: 4000, availability: "Available", brand: "Toyota", model: "Camry", image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400" } ];
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

    filtered.forEach((vehicle, index) => {
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
                    <div class="price-tag">₹ ${Number(vehicle.final_price || vehicle.price).toLocaleString()}</div>
                    <div class="btn-group">
                        <button class="btn btn-details" onclick="viewDetails(${index})">View Details</button>
                        <button class="btn btn-book" onclick="bookNowDirect(${vehicle.id})" ${isAvailable ? '' : 'disabled'}>Book Now</button>
                    </div>
                </div>
            </div>`;
    });
}

function viewDetails(index) {
    const vehicle = allVehicles[index]; // Note: index must match filtered list logic if using filters
    currentVehicle = vehicle;
    document.getElementById("popupImage").src = vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image;
    document.getElementById("popupName").innerText = vehicle.name;
    document.getElementById("p_brand").innerText = vehicle.brand;
    document.getElementById("p_model").innerText = vehicle.model;
    document.getElementById("p_final_price").innerText = "₹" + (vehicle.final_price || vehicle.price);
    document.getElementById("vehiclePopup").style.display = "flex";
}

function bookNowDirect(id) { window.location.href = `booking-preview.php?id=${id}`; }
function bookNow() { if(currentVehicle) bookNowDirect(currentVehicle.id); }
function closePopup() { document.getElementById("vehiclePopup").style.display = "none"; }
function outsideClick(e) { if(e.target.id === "vehiclePopup") closePopup(); }

document.addEventListener("DOMContentLoaded", renderTenantVehicles);
</script>
</body>
</html>