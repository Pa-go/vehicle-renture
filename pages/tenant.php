<?php
include '../database/db_config.php';
session_start();

$sql = "SELECT 
            id,
            vehicle_name as name, 
            vehicle_type as type, 
            price as price,
            final_price,
            discount,
            plate_number as plate, 
            status as availability, 
            vehicle_image as image,
            brand,
            model,
            fuel_type as fuel,
            color,
            v_condition as `condition`,
            description,
            features
        FROM vehicles";

$result = $conn->query($sql);
$db_vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $db_vehicles[] = $row;
    }
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
body {
    padding-top: 5px;
    background: #E6F0F4;
    font-family: 'Poppins', sans-serif;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    padding: 30px;
}

.card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 10px 25px rgba(0,31,63,0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0,31,63,0.25);
}

.card-img {
    width: 100%;
    height: 220px;
    position: relative;
    overflow: hidden;
}

.card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.card:hover .card-img img {
    transform: scale(1.1);
}

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
    letter-spacing: 0.5px;
    z-index: 5;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.available { background: #28a745; }
.unavailable { background: #dc3545; }

.card-body {
    padding: 18px;
    display: flex;
    flex-direction: column;
}

.card-body h4 {
    margin: 0;
    font-size: 18px;
    color: #001F3F;
}

.card-body small {
    color: #193857;
    margin-top: 4px;
}

.price-tag {
    font-size: 22px;
    font-weight: bold;
    color: #001F3F;
    margin: 12px 0;
}

.btn-group {
    display: flex;
    gap: 10px;
    margin-top: auto;
}

.btn {
    flex: 1;
    padding: 10px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}

.btn-details {
    background: transparent;
    border: 1px solid #001F3F;
    color: #001F3F;
}

.btn-details:hover {
    background: #001F3F;
    color: #FFFFFF;
}

.btn-book {
    background: #FFD700;
    color: #001F3F;
}

.btn-book:hover {
    background: #e6c200;
    transform: scale(1.05);
}

.btn-book:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.page-title {
    text-align: center;
    padding: 30px 20px 10px;
}

.page-title h1 {
    margin: 0;
    color: #001F3F;
    font-size: 30px;
}

.page-title p {
    color: #193857;
    margin-top: 10px;
}

.filter-box {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 20px 0;
    align-items: center;
}

.filter-box label {
    color: #001F3F;
    font-weight: 500;
}

.filter-box select {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid rgba(0,31,63,0.2);
    background: rgba(255,255,255,0.6);
    cursor: pointer;
}

.no-vehicles {
    text-align: center;
    padding: 60px;
    color: #193857;
}

/* POPUP - LENDER STYLE */
.popup-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,31,63,0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.popup-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    width: 850px;
    max-width: 95%;
    overflow: hidden;
    box-shadow: 0 20px 45px rgba(0,31,63,0.3);
    display: flex;
}

.popup-left {
    width: 40%;
    background: rgba(0,31,63,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.popup-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 10px;
}

.popup-right {
    width: 60%;
    padding: 25px;
    position: relative;
    max-height: 85vh;
    overflow-y: auto;
}

.popup-close {
    position: absolute;
    top: 15px; right: 20px;
    font-size: 28px;
    cursor: pointer;
    color: #001F3F;
    font-weight: bold;
    line-height: 1;
}

.popup-right h2 {
    color: #001F3F;
    margin-bottom: 15px;
}

.step-box {
    margin-bottom: 20px;
}

.step-box h4 {
    color: #001F3F;
    border-bottom: 1px solid rgba(0,31,63,0.2);
    padding-bottom: 5px;
    margin-bottom: 10px;
    font-size: 16px;
}

.step-box p {
    font-size: 14px;
    margin-bottom: 5px;
    color: #555;
}

.step-box p b {
    color: #333;
    width: 140px;
    display: inline-block;
}

.booking-btn {
    background: #FFD700;
    color: #001F3F;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    width: 100%;
    margin-top: 10px;
    transition: 0.3s;
}

.booking-btn:hover { background: #e6c200; }
.booking-btn:disabled { background: #ccc; cursor: not-allowed; }
</style>
</head>

<body>

<div class="container">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="page-title">
        <h1>Hello Tenant </h1>
        <p>Choose from our wide selection of quality rental vehicles</p>
    </div>

    <div class="filter-box">
        <label>Filter By Type:</label>
        <select id="filterType" onchange="renderTenantVehicles()">
            <option value="All">All Vehicles</option>
            <option value="Car">Cars</option>
            <option value="Bike">Bikes</option>
        </select>

        <label>Availability:</label>
        <select id="filterStatus" onchange="renderTenantVehicles()">
            <option value="All">All Status</option>
            <option value="Available">Available Only</option>
        </select>
    </div>

    <div class="grid" id="tenantVehicleGrid"></div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<!-- POPUP - LENDER STYLE -->
<div id="vehiclePopup" class="popup-overlay" onclick="outsideClick(event)">
  <div class="popup-card">

    <div class="popup-left">
        <img id="popupImage" class="popup-img">
    </div>

    <div class="popup-right">
      <span class="popup-close" onclick="closePopup()">×</span>
      <h2 id="popupName"></h2>

      <div class="step-box">
          <h4>Vehicle Information</h4>
          <p><b>Vehicle Name:</b> <span id="p_name"></span></p>
          <p><b>Brand:</b> <span id="p_brand"></span></p>
          <p><b>Model:</b> <span id="p_model"></span></p>
          <p><b>Vehicle Type:</b> <span id="p_type"></span></p>
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
          <p>
              <b>Start Date:</b>
              <input type="date" id="bookStart" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;">
          </p>
          <p>
              <b>End Date:</b>
              <input type="date" id="bookEnd" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;">
          </p>
          <p><b>Duration:</b> <span id="calcDuration">0</span> Days</p>
          <p><b>Total Price:</b> ₹<span id="calcTotal">0</span></p>
      </div>

      <div class="step-box">
          <h4>Status</h4>
          <p><b>Availability:</b> <span id="p_status"></span></p>
      </div>

      <button id="popupBookBtn" class="booking-btn" onclick="bookNow()">Book Now</button>

    </div>
  </div>
</div>

<script src="../assets/js/script.js"></script>
<script>
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;

const mockData = [
    {
        id: null,
        name: "Toyota Camry Hybrid",
        type: "Car",
        price: 40000,
        final_price: 40000,
        discount: 0,
        plate: "MH-01-AB-1234",
        availability: "Available",
        image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400",
        brand: "Toyota", model: "Camry", fuel: "Hybrid", color: "White",
        condition: "Used", description: "Well maintained hybrid car.", features: "AC, Music System, GPS"
    },
    {
        id: null,
        name: "Suzuki Swift",
        type: "Car",
        price: 25000,
        final_price: 25000,
        discount: 0,
        plate: "MH-02-CD-5678",
        availability: "Available",
        image: "https://www.globalsuzuki.com/globalnews/2025/img/0528.jpg",
        brand: "Suzuki", model: "Swift", fuel: "Petrol", color: "Red",
        condition: "Good", description: "Compact and fuel efficient.", features: "AC, Bluetooth"
    },
    {
        id: null,
        name: "Kia K5",
        type: "Car",
        price: 55000,
        final_price: 55000,
        discount: 0,
        plate: "MH-03-EF-9012",
        availability: "Sold",
        image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4L-2-OmGbn7Qcz9i-R1mTP1HbwQhUETUUyQ&s",
        brand: "Kia", model: "K5", fuel: "Petrol", color: "Black",
        condition: "New", description: "Luxury sedan.", features: "Sunroof, Leather Seats, AC"
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
        grid.innerHTML = '<div class="no-vehicles">No vehicles found matching your filters.</div>';
        return;
    }

    filtered.forEach((vehicle, index) => {
        let imgSrc = (vehicle.image && vehicle.image !== "null" && vehicle.image !== null)
            ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
            : 'https://placehold.co/400x250?text=No+Preview';

        const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
        const displayPrice = vehicle.final_price || vehicle.price;
        const price = displayPrice ? Number(displayPrice).toLocaleString() : "---";

        grid.innerHTML += `
            <div class="card">
                <div class="card-img">
                    <img src="${imgSrc}" onerror="this.src='https://placehold.co/400x250?text=Image+Missing'">
                    <div class="badge ${isAvailable ? 'available' : 'unavailable'}">
                        ${isAvailable ? 'Available' : 'Unavailable'}
                    </div>
                </div>
                <div class="card-body">
                    <h4>${vehicle.name || 'Unnamed Vehicle'}</h4>
                    <small style="color:#888;">${vehicle.type || 'Vehicle'} ${vehicle.brand ? '· ' + vehicle.brand : ''}</small>
                    <div class="price-tag">₹ ${price} <small style="font-size:13px; font-weight:normal; color:#888;">/ day</small></div>
                    <div class="btn-group">
                        <button class="btn btn-details" onclick="viewDetails(${index})">View Details</button>
                        <button class="btn btn-book" 
                            onclick="window.location.href='booking-preview.php?name=${encodeURIComponent(vehicle.name || '')}&price=${vehicle.final_price || vehicle.price || 0}&type=${encodeURIComponent(vehicle.type || '')}'"
                            ${isAvailable ? '' : 'disabled'}>
                            Book Now
                        </button>
                    </div>
                </div>
            </div>`;
    });
}

function viewDetails(index) {
    const vehicle = allVehicles.filter(v => {
        const typeFilter = document.getElementById("filterType").value;
        const statusFilter = document.getElementById("filterStatus").value;
        const typeMatch = (typeFilter === "All" || v.type === typeFilter);
        const statusMatch = (statusFilter === "All" || v.availability === "Available" || v.availability == 1);
        return typeMatch && statusMatch;
    })[index];

    currentVehicle = vehicle;

    let imgSrc = (vehicle.image && vehicle.image !== "null" && vehicle.image !== null)
        ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
        : 'https://placehold.co/300x200?text=No+Image';

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name || "N/A";

    document.getElementById("p_name").innerText        = vehicle.name        || "N/A";
    document.getElementById("p_brand").innerText       = vehicle.brand       || "N/A";
    document.getElementById("p_model").innerText       = vehicle.model       || "N/A";
    document.getElementById("p_type").innerText        = vehicle.type        || "N/A";
    document.getElementById("p_plate").innerText       = vehicle.plate       || vehicle.distance || "N/A";
    document.getElementById("p_fuel").innerText        = vehicle.fuel        || vehicle.fuel_type || "N/A";
    document.getElementById("p_color").innerText       = vehicle.color       || "N/A";
    document.getElementById("p_condition").innerText   = vehicle.condition   || vehicle.v_condition || "N/A";
    document.getElementById("p_features").innerText    = vehicle.features    || "N/A";
    document.getElementById("p_description").innerText = vehicle.description || "N/A";

    document.getElementById("p_price").innerText       = vehicle.price       ? "₹ " + Number(vehicle.price).toLocaleString()       : "N/A";
    document.getElementById("p_discount").innerText    = vehicle.discount    ? "₹ " + Number(vehicle.discount).toLocaleString()    : "None";
    document.getElementById("p_final_price").innerText = vehicle.final_price ? "₹ " + Number(vehicle.final_price).toLocaleString() : "N/A";

    const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
    const statusEl = document.getElementById("p_status");
    statusEl.innerText = isAvailable ? "Available" : "Unavailable";
    statusEl.style.color = isAvailable ? "#28a745" : "#dc3545";
    statusEl.style.fontWeight = "bold";

    const bookBtn = document.getElementById("popupBookBtn");
    bookBtn.disabled = !isAvailable;
    bookBtn.style.background = isAvailable ? "#FFD700" : "#ccc";
    bookBtn.style.cursor = isAvailable ? "pointer" : "not-allowed";
    bookBtn.innerText = isAvailable ? "Book Now" : "Not Available";

    // Reset booking fields
    document.getElementById("bookStart").value = "";
    document.getElementById("bookEnd").value = "";
    document.getElementById("calcDuration").innerText = "0";
    document.getElementById("calcTotal").innerText = "0";

    document.getElementById("vehiclePopup").style.display = "flex";
}

function calculateTotal() {
    const start = document.getElementById("bookStart").value;
    const end = document.getElementById("bookEnd").value;

    if (start && end && currentVehicle) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const daysDiff = Math.ceil((endDate - startDate) / (1000 * 3600 * 24));

        if (daysDiff > 0) {
            const pricePerDay = currentVehicle.final_price || currentVehicle.price || 0;
            document.getElementById("calcDuration").innerText = daysDiff;
            document.getElementById("calcTotal").innerText = Number(daysDiff * pricePerDay).toLocaleString();
        } else {
            document.getElementById("calcDuration").innerText = "0";
            document.getElementById("calcTotal").innerText = "0";
        }
    }
}

function bookNow() {
    if (!currentVehicle) return;
    window.location.href = 'booking-preview.php?name=' + encodeURIComponent(currentVehicle.name || '')
        + '&price=' + (currentVehicle.final_price || currentVehicle.price || 0)
        + '&type=' + encodeURIComponent(currentVehicle.type || '');
}

function closePopup() { document.getElementById("vehiclePopup").style.display = "none"; currentVehicle = null; }
function outsideClick(event) { if (event.target.id === "vehiclePopup") closePopup(); }

document.addEventListener("DOMContentLoaded", renderTenantVehicles);
</script>
</body>
</html>