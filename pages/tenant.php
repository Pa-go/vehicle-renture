<?php
// 1. Database Connection
include '../database/db_config.php';
session_start();

// 2. Fetch ALL vehicles from the database for the Tenant to see
// We don't filter by owner_id here because a tenant sees everyone's cars
$sql = "SELECT 
            vehicle_name as name, 
            vehicle_type as type, 
            price as price, 
            plate_number as distance, 
            status as availability, 
            vehicle_image as image 
        FROM vehicles";

$result = $conn->query($sql);
$db_vehicles = [];
if ($result->num_rows > 0) {
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
    /* 3 cards per row, nicely spaced */
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
        flex-direction: column; /* Keeps content stacked */
        height: 100%; /* Makes all cards same height */
        border: 1px solid #eee;
    }

    /* Fixes the image stretching seen in your screenshot */
    .card-img {
        width: 100%;
        height: 200px;
        background: #f8f9fa;
        position: relative;
    }

    .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* This is the magic line that stops stretching */
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
    }

    .card-body {
        padding: 15px;
        flex-grow: 1; /* Pushes buttons to the bottom */
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
        margin-top: auto; /* Aligns buttons to bottom of card */
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
</style>
</head>

<body>

<div class="container">

  <div class="header">
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
    image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400"
  },
  {
    name: "Suzuki Swift",
    type: "Car",
    price: 25000,
    distance: "1.2 km away",
    availability: "Available",
    image: "https://www.globalsuzuki.com/globalnews/2025/img/0528.jpg"
  },
  {
    name: "Kia K5",
    type: "Car",
    price: 55000,
    distance: "3.8 km away",
    availability: "Sold",
    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4L-2-OmGbn7Qcz9i-R1mTP1HbwQhUETUUyQ&s"
  }
];

// Merge data
const allVehicles = [...dbData, ...mockData];

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
    // 1. Safety check for Image
    let imgSrc = (vehicle.image && vehicle.image !== "null") 
        ? (vehicle.image.startsWith('http') ? vehicle.image : '../' + vehicle.image)
        : 'https://placehold.co/400x250?text=No+Preview';

    // 2. Safety check for Availability
    const isAvailable = (vehicle.availability === "Available" || vehicle.availability == 1);
    const price = vehicle.price ? Number(vehicle.price).toLocaleString() : "---";

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
                    <button class="btn btn-details">View Details</button>
                    <button class="btn btn-book" 
                            onclick="openPaymentModal(${vehicle.price || 0}, '${vehicle.id}')" 
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
</script>

</body>
</html>