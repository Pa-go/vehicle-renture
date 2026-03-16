<?php
// 1. Database Connection
include '../database/db_config.php';
session_start();

// 2. Fetch ALL vehicles from the database for the Tenant to see
$sql = "SELECT 
            vehicle_name as name, 
            vehicle_type as type, 
            price as price, 
            plate_number as distance, 
            status as availability, 
            vehicle_image as image,
            brand,
            fuel_type,
            `condition` as v_condition,
            location,
            lender_name,
            lender_contact,
            lender_email
        FROM vehicles";

$result = $conn->query($sql);
$db_vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $db_vehicles[] = $row;
    }
}

// Convert to JSON for JavaScript
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
    /* Add padding to account for fixed header and menu bar */
    body {
        padding-top: 130px; 
        background: #f4f6f9;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        padding: 20px;
    }

    .card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column; 
        height: 100%; 
        border: 1px solid #eee;
    }

    .card-img {
        width: 100%;
        height: 200px;
        background: #f8f9fa;
        position: relative;
    }

    .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover; 
    }

    .badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 12px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        font-size: 12px;
        z-index: 10;
    }

    .card-body {
        padding: 15px;
        flex-grow: 1; 
        display: flex;
        flex-direction: column;
    }

    .card-body h4 { margin: 0 0 5px 0; text-transform: capitalize; }
    
    .price-tag { 
        font-size: 20px; 
        font-weight: bold; 
        color: #1e2a4a; 
        margin: 10px 0; 
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: auto; 
    }

    .btn {
        flex: 1;
        padding: 10px;
        border-radius: 6px;
        border: none;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-details { background: #f0f2f5; color: #333; }
    .btn-book { background: #1e2a4a; color: white; }
    .btn-book:disabled { background: #ccc; cursor: not-allowed; }
    
    .page-title {
        text-align: center;
        padding: 30px 20px 10px;
    }
    
    .page-title h1 {
        margin: 0;
        color: #1e2a4a;
        font-size: 28px;
    }
    
    .page-title p {
        color: #666;
        margin-top: 10px;
    }

    .filter-box {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
        align-items: center;
    }

    /* POPUP STYLES */
    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-content {
        background: white;
        width: 750px;
        border-radius: 15px;
        padding: 20px;
        position: relative;
    }

    /* CLOSE BUTTON STYLES */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 32px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        z-index: 10000;
        line-height: 1;
    }
    
    .close-btn:hover {
        color: red;
    }

    .popup-body {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        margin-top: 10px;
    }

    .popup-img {
        position: relative;
        width: 300px;
    }

    .popup-img img {
        width: 100%;
        border-radius: 10px;
    }

    .popup-details {
        flex: 1; 
        max-height: 450px; 
        overflow-y: auto; 
        padding-right: 15px;
    }

    .popup-details h2 {
        margin-top: 0;
        margin-bottom: 5px;
    }

    .contact-btn {
        background: #ffd400;
        border: none;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        text-align: center;
    }
    
    .contact-btn:hover {
        background: #e6c000;
    }
</style>
</head>

<body>

<div class="container">
    <?php include __DIR__ . '/../includes/header.php'; ?>

  <div class="page-title">
    <h1>Hello Tenant 👋</h1>
    <p>Choose from our wide selection of quality rental cars</p>
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

  <div class="grid" id="tenantVehicleGrid">
  </div>

</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
<script src="../assets/js/script.js"></script>

<div id="vehiclePopup" class="popup">
  <div class="popup-content">

    <span class="close-btn" onclick="closePopup()">&times;</span>

    <div class="popup-body">

      <div class="popup-img">
        <span id="popupBadge" class="badge"></span>
        <img id="popupImage" src="">
      </div>

      <div class="popup-details">
        <h2 id="popupName"></h2>
        <h3 id="popupPrice" style="color: #1e2a4a; margin-top: 0;"></h3>
        
        <p style="margin: 5px 0;"><b>Brand:</b> <span id="popupBrand"></span> | <b>Type:</b> <span id="popupType"></span></p>
        <p style="margin: 5px 0;"><b>Fuel:</b> <span id="popupFuel"></span> | <b>Condition:</b> <span id="popupCondition"></span></p>
        <p style="margin: 5px 0;"><b>Location:</b> <span id="popupLocation"></span></p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

        <h4 style="margin: 0 0 10px 0;">Lender Information</h4>
        <p style="margin: 5px 0;"><b>Name:</b> <span id="popupLenderName"></span></p>
        <p style="margin: 5px 0;"><b>Contact:</b> <span id="popupLenderContact"></span></p>
        <p style="margin: 5px 0;"><b>Email:</b> <span id="popupLenderEmail"></span></p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

        <h4 style="margin: 0 0 10px 0;">Booking Info</h4>
        <div style="display: flex; gap: 15px; margin-bottom: 10px;">
            <div>
                <label style="font-size: 13px; font-weight: bold;">Start Date:</label><br>
                <input type="date" id="bookStart" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;" onchange="calculateTotal()">
            </div>
            <div>
                <label style="font-size: 13px; font-weight: bold;">End Date:</label><br>
                <input type="date" id="bookEnd" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;" onchange="calculateTotal()">
            </div>
        </div>
        <p style="margin: 5px 0;"><b>Duration:</b> <span id="calcDuration">0</span> Days</p>
        <p style="margin: 5px 0; font-size: 18px;"><b>Total Price:</b> ₹<span id="calcTotal">0</span></p>

        <button id="popupContactBtn" class="contact-btn">Contact Owner</button>
      </div>

    </div>
  </div>
</div>

<script>
// 1. Combine Database and Mock Data
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;

const mockData = [
  {
    name: "Toyota Camry Hybrid",
    type: "Car",
    price: 40000,
    distance: "2.5 km away",
    availability: "Available",
    image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400",
    brand: "Toyota", fuel_type: "Hybrid", v_condition: "Used", location: "City Center", lender_name: "John Doe", lender_contact: "123-456-7890", lender_email: "john@example.com"
  },
  {
    name: "Suzuki Swift",
    type: "Car",
    price: 25000,
    distance: "1.2 km away",
    availability: "Available",
    image: "https://www.globalsuzuki.com/globalnews/2025/img/0528.jpg",
    brand: "Suzuki", fuel_type: "Petrol", v_condition: "Good", location: "North Side", lender_name: "Jane Smith", lender_contact: "987-654-3210", lender_email: "jane@example.com"
  },
  {
    name: "Kia K5",
    type: "Car",
    price: 55000,
    distance: "3.8 km away",
    availability: "Sold",
    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4L-2-OmGbn7Qcz9i-R1mTP1HbwQhUETUUyQ&s",
    brand: "Kia", fuel_type: "Petrol", v_condition: "New", location: "South Side", lender_name: "Alex Johnson", lender_contact: "555-123-4567", lender_email: "alex@example.com"
  }
];

// Merge data
const allVehicles = [...dbData, ...mockData];
let currentVehiclePrice = 0;

function renderTenantVehicles() {
    const typeFilter = document.getElementById("filterType").value;
    const statusFilter = document.getElementById("filterStatus").value;
    const grid = document.getElementById("tenantVehicleGrid");
    grid.innerHTML = "";

    const filtered = allVehicles.filter(v => {
        const typeMatch = (typeFilter === "All" || v.type === typeFilter);
        const statusMatch = (statusFilter === "All" || v.availability === "Available" || v.availability === "true" || v.availability == 1);
        return typeMatch && statusMatch;
    });

    filtered.forEach(vehicle => {
        let imgSrc = (vehicle.image && vehicle.image !== "null") 
            ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
            : 'https://placehold.co/400x250?text=No+Preview';

        const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
        const price = vehicle.price ? Number(vehicle.price).toLocaleString() : "---";

        // Create a safe JSON string for the onclick handler
        const vehicleJson = JSON.stringify(vehicle).replace(/'/g, "\\'").replace(/"/g, '&quot;');

        grid.innerHTML += `
            <div class="card">
                <div class="card-img">
                    <div class="badge" style="background: ${isAvailable ? '#28a745' : '#dc3545'}">
                        ${isAvailable ? 'Available' : 'Unavailable'}
                    </div>
                    <img src="${imgSrc}" onerror="this.src='https://placehold.co/400x250?text=Image+Missing'">
                </div>
                <div class="card-body">
                    <h4>${vehicle.name || 'Unnamed Vehicle'}</h4>
                    <small style="color: #888;">${vehicle.type || 'Vehicle'}</small>
                    <div class="price-tag">₹ ${price}</div>
                    <div class="btn-group">
                        <button class="btn btn-details" onclick="openPopup(${vehicleJson})">View Details</button>
                        <button class="btn btn-book" 
                                onclick="window.location.href='booking-preview.php?name=${encodeURIComponent(vehicle.name || '')}&price=${vehicle.price || 0}&type=${encodeURIComponent(vehicle.type || '')}'" 
                                ${isAvailable ? '' : 'disabled'}>
                            Book Now
                        </button>

                    </div>
                </div>
            </div>
        `;
    });
}

document.addEventListener("DOMContentLoaded", renderTenantVehicles);

function openPopup(vehicle) {
    document.getElementById("vehiclePopup").style.display = "flex";

    // Step 1: Vehicle Info
    document.getElementById("popupImage").src = vehicle.image || "";
    document.getElementById("popupName").innerText = vehicle.name || "Vehicle";
    document.getElementById("popupBrand").innerText = vehicle.brand || "N/A";
    document.getElementById("popupType").innerText = vehicle.type || "N/A";
    document.getElementById("popupFuel").innerText = vehicle.fuel_type || "N/A";
    document.getElementById("popupCondition").innerText = vehicle.v_condition || "N/A";
    document.getElementById("popupLocation").innerText = vehicle.location || vehicle.distance || "N/A";
    document.getElementById("popupPrice").innerText = "₹ " + vehicle.price + " / day";

    const isAvail = (vehicle.availability === "Available" || vehicle.availability == 1);
    const badge = document.getElementById("popupBadge");
    badge.innerText = isAvail ? "Available" : "Unavailable";
    badge.style.background = isAvail ? "#28a745" : "#dc3545";

    // Step 2: Lender Info
    document.getElementById("popupLenderName").innerText = vehicle.lender_name || "N/A";
    document.getElementById("popupLenderContact").innerText = vehicle.lender_contact || "N/A";
    document.getElementById("popupLenderEmail").innerText = vehicle.lender_email || "N/A";

    // Step 3: Reset Booking Calc
    currentVehiclePrice = vehicle.price || 0;
    document.getElementById("bookStart").value = "";
    document.getElementById("bookEnd").value = "";
    document.getElementById("calcDuration").innerText = "0";
    document.getElementById("calcTotal").innerText = "0";

    // NEW: Make the Contact Owner button functional (Works on PC and Mobile)
    const contactBtn = document.getElementById("popupContactBtn");
    contactBtn.onclick = function() {
        const phone = (vehicle.lender_contact && vehicle.lender_contact !== "N/A") ? vehicle.lender_contact : "Not provided";
        const email = (vehicle.lender_email && vehicle.lender_email !== "N/A") ? vehicle.lender_email : "Not provided";

        // 1. Always show an alert so you can see it working on your PC
        alert("👤 LENDER DETAILS\n\n📞 Phone: " + phone + "\n✉️ Email: " + email);

        // 2. Try to open the phone dialer (will work on mobile phones)
        if (phone !== "Not provided") {
            window.location.href = "tel:" + phone;
        }
    };
}

// THIS IS THE CLOSE POPUP FUNCTION
function closePopup() {
    document.getElementById("vehiclePopup").style.display = "none";
}

function calculateTotal() {
    const start = document.getElementById("bookStart").value;
    const end = document.getElementById("bookEnd").value;

    if (start && end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const timeDiff = endDate.getTime() - startDate.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (daysDiff > 0) {
            document.getElementById("calcDuration").innerText = daysDiff;
            document.getElementById("calcTotal").innerText = (daysDiff * currentVehiclePrice).toLocaleString();
        } else {
            document.getElementById("calcDuration").innerText = "0";
            document.getElementById("calcTotal").innerText = "0";
        }
    }
}
</script>

</body>
</html>