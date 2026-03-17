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
            plate_number as distance, 
            status as availability, 
            vehicle_image as image,
            brand,
            model,
            fuel_type,
            color,
            v_condition,
            vehicle_condition,
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
    padding-top: 130px;
    background: #E6F0F4;
    font-family: 'Poppins', sans-serif;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    padding: 30px;
}

/* CARD - MODERN PINTEREST STYLE */
.card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 10px 25px rgba(0, 31, 63, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0, 31, 63, 0.25);
}

/* IMAGE */
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

/* BADGE */
.badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #FFFFFF;
    background: #001F3F;
}

/* BODY */
.card-body {
    padding: 18px;
    display: flex;
    flex-direction: column;
}

/* TITLE */
.card-body h4 {
    margin: 0;
    font-size: 18px;
    color: #001F3F;
}

/* SUBTEXT */
.card-body small {
    color: #193857;
    margin-top: 4px;
}

/* PRICE */
.price-tag {
    font-size: 22px;
    font-weight: bold;
    color: #001F3F;
    margin: 12px 0;
}

/* BUTTONS */
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

/* DETAILS BUTTON */
.btn-details {
    background: transparent;
    border: 1px solid #001F3F;
    color: #001F3F;
}

.btn-details:hover {
    background: #001F3F;
    color: #FFFFFF;
}

/* BOOK BUTTON */
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

/* PAGE TITLE */
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

/* FILTER */
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

/* POPUP */
.popup {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,31,63,0.7);
    justify-content: center;
    align-items: center;
}

/* POPUP CONTENT */
.popup-content {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 20px;
    width: 750px;
    max-width: 95%;
    border: 1px solid rgba(255,255,255,0.3);
}

/* POPUP TEXT */
.popup-details h2 {
    color: #001F3F;
}

.popup-details h3 {
    color: #FFD700 !important;
}

/* CONTACT BUTTON */
.contact-btn {
    background: #FFD700;
    border: none;
    padding: 12px;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
    color: #001F3F;
}

.contact-btn:hover {
    background: #e6c200;
}

/* EMPTY */
.no-vehicles {
    text-align: center;
    padding: 60px;
    color: #193857;
}
</style>
</head>

<body>

<div class="container">
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="page-title">
        <h1>Hello Tenant 👋</h1>
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
<script src="../assets/js/script.js"></script>

<!-- POPUP -->
<div id="vehiclePopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div class="popup-body">

            <div class="popup-img">
                <span id="popupBadge" class="badge"></span>
                <img id="popupImage" src="" onerror="this.src='https://placehold.co/300x200?text=No+Image'">
            </div>

            <div class="popup-details">
                <h2 id="popupName"></h2>
                <h3 id="popupPrice" style="color: #1e2a4a; margin-top: 0;"></h3>

                <div class="info-grid">
                    <div class="info-item"><b>Brand:</b> <span id="popupBrand"></span></div>
                    <div class="info-item"><b>Model:</b> <span id="popupModel"></span></div>
                    <div class="info-item"><b>Type:</b> <span id="popupType"></span></div>
                    <div class="info-item"><b>Fuel:</b> <span id="popupFuel"></span></div>
                    <div class="info-item"><b>Color:</b> <span id="popupColor"></span></div>
                    <div class="info-item"><b>Condition:</b> <span id="popupCondition"></span></div>
                    <div class="info-item"><b>Plate No:</b> <span id="popupPlate"></span></div>
                    <div class="info-item"><b>Discount:</b> <span id="popupDiscount"></span></div>
                </div>

                <p style="margin: 8px 0; font-size: 13px;"><b>Description:</b> <span id="popupDesc"></span></p>
                <p style="margin: 8px 0; font-size: 13px;"><b>Features:</b> <span id="popupFeatures"></span></p>

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

                <button id="popupBookBtn" class="contact-btn">Book Now</button>
            </div>

        </div>
    </div>
</div>

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
        distance: "MH-01-AB-1234",
        availability: "Available",
        image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400",
        brand: "Toyota", model: "Camry", fuel_type: "Hybrid", color: "White",
        v_condition: "Used", description: "Well maintained hybrid car.", features: "AC, Music System, GPS"
    },
    {
        id: null,
        name: "Suzuki Swift",
        type: "Car",
        price: 25000,
        final_price: 25000,
        discount: 0,
        distance: "MH-02-CD-5678",
        availability: "Available",
        image: "https://www.globalsuzuki.com/globalnews/2025/img/0528.jpg",
        brand: "Suzuki", model: "Swift", fuel_type: "Petrol", color: "Red",
        v_condition: "Good", description: "Compact and fuel efficient.", features: "AC, Bluetooth"
    },
    {
        id: null,
        name: "Kia K5",
        type: "Car",
        price: 55000,
        final_price: 55000,
        discount: 0,
        distance: "MH-03-EF-9012",
        availability: "Sold",
        image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4L-2-OmGbn7Qcz9i-R1mTP1HbwQhUETUUyQ&s",
        brand: "Kia", model: "K5", fuel_type: "Petrol", color: "Black",
        v_condition: "New", description: "Luxury sedan.", features: "Sunroof, Leather Seats, AC"
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

    filtered.forEach(vehicle => {
        let imgSrc = (vehicle.image && vehicle.image !== "null" && vehicle.image !== null)
            ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
            : 'https://placehold.co/400x250?text=No+Preview';

        const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
        const displayPrice = vehicle.final_price || vehicle.price;
        const price = displayPrice ? Number(displayPrice).toLocaleString() : "---";
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
                    <small style="color: #888;">${vehicle.type || 'Vehicle'} ${vehicle.brand ? '· ' + vehicle.brand : ''}</small>
                    <div class="price-tag">₹ ${price} <small style="font-size:13px; font-weight:normal; color:#888;">/ day</small></div>
                    <div class="btn-group">
                        <button class="btn btn-details" onclick='openPopup(${JSON.stringify(vehicle).replace(/'/g, "&#39;")})'>View Details</button>
                        <button class="btn btn-book" 
                            onclick="window.location.href='booking-preview.php?name=${encodeURIComponent(vehicle.name || '')}&price=${vehicle.final_price || vehicle.price || 0}&type=${encodeURIComponent(vehicle.type || '')}'" 
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
    currentVehicle = vehicle;
    document.getElementById("vehiclePopup").style.display = "flex";

    const imgSrc = (vehicle.image && vehicle.image !== "null" && vehicle.image !== null)
        ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
        : 'https://placehold.co/300x200?text=No+Image';

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name || "Vehicle";
    document.getElementById("popupPrice").innerText = "₹ " + Number(vehicle.final_price || vehicle.price || 0).toLocaleString() + " / day";
    document.getElementById("popupBrand").innerText = vehicle.brand || "N/A";
    document.getElementById("popupModel").innerText = vehicle.model || "N/A";
    document.getElementById("popupType").innerText = vehicle.type || "N/A";
    document.getElementById("popupFuel").innerText = vehicle.fuel_type || "N/A";
    document.getElementById("popupColor").innerText = vehicle.color || "N/A";
    document.getElementById("popupCondition").innerText = vehicle.v_condition || vehicle.vehicle_condition || "N/A";
    document.getElementById("popupPlate").innerText = vehicle.distance || "N/A";
    document.getElementById("popupDiscount").innerText = vehicle.discount ? "₹ " + vehicle.discount : "None";
    document.getElementById("popupDesc").innerText = vehicle.description || "No description provided.";
    document.getElementById("popupFeatures").innerText = vehicle.features || "N/A";

    const isAvail = (vehicle.availability === "Available" || vehicle.availability == 1);
    const badge = document.getElementById("popupBadge");
    badge.innerText = isAvail ? "Available" : "Unavailable";
    badge.style.background = isAvail ? "#001F3F" : "#193857";

    // Reset booking fields
    document.getElementById("bookStart").value = "";
    document.getElementById("bookEnd").value = "";
    document.getElementById("calcDuration").innerText = "0";
    document.getElementById("calcTotal").innerText = "0";

    // Book Now button
    const bookBtn = document.getElementById("popupBookBtn");
    if (isAvail) {
        bookBtn.disabled = false;
        bookBtn.style.background = "#ffd400";
        bookBtn.style.cursor = "pointer";
        bookBtn.onclick = function() {
            window.location.href = 'booking-preview.php?name=' + encodeURIComponent(vehicle.name || '') 
                + '&price=' + (vehicle.final_price || vehicle.price || 0) 
                + '&type=' + encodeURIComponent(vehicle.type || '');
        };
    } else {
        bookBtn.disabled = true;
        bookBtn.style.background = "#ccc";
        bookBtn.style.cursor = "not-allowed";
        bookBtn.innerText = "Not Available";
    }
}

function closePopup() {
    document.getElementById("vehiclePopup").style.display = "none";
    currentVehicle = null;
}

// Close popup on background click
document.getElementById("vehiclePopup").addEventListener("click", function(e) {
    if (e.target === this) closePopup();
});

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
</script>

</body>
</html>