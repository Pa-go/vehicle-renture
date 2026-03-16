<?php
// 1. Database Connection & Session
include '../database/db_config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/log_reg.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch REAL data from Database
$sql = "SELECT 
            vehicle_name as name, 
            vehicle_type as type, 
            price as price, 
            plate_number as distance, 
            status as availability, 
            vehicle_image as image 
        FROM vehicles 
        WHERE owner_id = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $db_vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $db_vehicles = [];
}

$json_vehicles = json_encode($db_vehicles);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lender Dashboard | Renture</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
    background:#E6F0F4;
    padding-top: 130px; 
}

.container{
    max-width:1200px;
    margin:auto;
    padding:20px 20px;
}
.page-title h1{
    color:#1e2a4a;
    margin-bottom:10px;
}
.page-title p{
    color:#555;
    font-size:16px;
}
.page-title {
    text-align: center;
    padding: 30px 20px 10px;
}
.add-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    margin-bottom:40px;
}

.add-box h3{
    margin-bottom:20px;
    color:#1e2a4a;
}

.add-box input,
.add-box select{
    padding:12px;
    margin:8px 5px;
    border:1px solid #ddd;
    border-radius:8px;
    width:180px;
}

.add-box button{
    padding:12px 20px;
    background:#1e2a4a;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.section-title{
    font-size:22px;
    margin-bottom:20px;
    color:#1e2a4a;
    border-bottom:2px solid #ddd;
    padding-bottom:8px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:25px;
}

.card{
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card-img{
    position:relative;
    height:200px;
}

.card-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.badge{
    position:absolute;
    top:10px;
    right:10px;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    color:white;
}

.available{ background:#28a745; }
.unavailable{ background:#dc3545; }

.card-body{ padding:18px; }
.card-body h4{ margin-bottom:10px; color:#1e2a4a; }
.price{ font-weight:700; font-size:18px; margin-bottom:5px; }
.distance{ color:#666; font-size:14px; }

input[type="file"] { display: none; }
.upload-btn {
    background-color: #1e2a78;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.add-vehicle-link { text-decoration: none; }
.add-vehicle-card {
    border: 3px dashed #1e2a4a;
    background: #f8fbff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 380px;
    cursor: pointer;
}

.card-image-container {
    background: #e6f0f4;
    width: 80px; height: 80px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

.add-icon { font-size: 50px; color: #1e2a4a; font-weight: bold; }
.car-name { font-weight: 700; color: #1e2a4a; font-size: 20px; }

/* POPUP STYLING */
.popup-overlay{
    position:fixed;
    top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.popup-card{
    background:white;
    width:850px;
    max-width:95%;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 20px 45px rgba(0,0,0,0.25);
    display:flex;
}

.popup-left{
    width:40%;
    background:#f4f6f9;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px;
}

.popup-img{
    max-width:100%;
    max-height:100%;
    object-fit:contain; /* Keeps image from being cut or stretched */
    border-radius:10px;
}

.popup-right{
    width:60%;
    padding:25px;
    position:relative;
    max-height: 85vh;
    overflow-y: auto;
}

.popup-close{
    position:absolute;
    top:15px; right:20px;
    font-size:24px;
    cursor:pointer;
    color:#333;
}

.popup-right h2 { margin-bottom: 10px; color: #1e2a4a; }

.step-box { margin-bottom: 20px; }
.step-box h4 {
    color: #1e2a4a;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
    margin-bottom: 10px;
    font-size: 16px;
}

.step-box p { font-size: 14px; margin-bottom: 5px; color: #555; }
.step-box p b { color: #333; width: 140px; display: inline-block; }

/* ACTION BUTTONS: DARK BLUE BACKGROUND, WHITE FONT */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.lender-btn {
    background: #001F3F; /* Dark Blue */
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
}

.lender-btn:hover { background: #003366; }

</style>
</head>

<body>
<?php include '../includes/header.php'; ?>
<div class="container">

    <div class="page-title">
        <h1>Hello Lender 👋</h1>
        <p>Manage your vehicles and tenant rental requests.</p>
    </div>

    <div class="add-box">
        <h3>Quick Add</h3>
        <input type="text" id="name" placeholder="Vehicle Name">
        <select id="type">
            <option value="">Select Type</option>
            <option value="Car">Car</option>
            <option value="Bike">Bike</option>
        </select>
        <input type="number" id="price" placeholder="Price">
        <input type="number" id="distance" placeholder="Distance (km)">
        <select id="availability">
            <option value="true">Available</option>
            <option value="false">Unavailable</option>
        </select>
        <label for="image" class="upload-btn">Upload Image</label>
        <input type="file" id="image" accept="image/*">
        <button onclick="addVehicle()">Add Vehicle</button>
    </div>

    <div style="margin-bottom:20px;">
        <label style="font-weight:600;">Filter By: </label>
        <select id="filterType" onchange="renderVehicles()" style="padding:8px;border-radius:6px;">
            <option value="All">All Vehicles</option>
            <option value="Car">Car</option>
            <option value="Bike">Bike</option>
        </select>
    </div>

    <div class="section-title">Your Vehicles</div>
    <div class="grid" id="vehicleGrid"></div>

</div>

<?php include '../includes/footer.php'; ?>

<script>
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;

const mockData = [
  {
    name:"Toyota Camry Hybrid (Sample)",
    type:"Car", price:4000, distance:2.5, availability:true,
    image:"https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400",
    brand:"Toyota", fuel:"Hybrid", plate:"MH 01 AB 1234", location:"Mumbai", status:"Available",
    tenant_name:"Rahul Sharma", tenant_email:"rahul@example.com", tenant_phone:"+91 98765 43210", tenant_dl:"DL123456789",
    start_date:"2026-03-20", end_date:"2026-03-22", duration:"2 Days", total_price:"₹ 8,000"
  }
];

const vehicles = [...dbData, ...mockData];

function renderVehicles(){
    const selectedType = document.getElementById("filterType").value;
    const grid = document.getElementById("vehicleGrid");
    grid.innerHTML = "";

    const filteredVehicles = vehicles.filter(v => selectedType === "All" || v.type === selectedType);

    filteredVehicles.forEach((vehicle, index)=>{
        let imgSrc = (typeof vehicle.image === 'string' && vehicle.image.startsWith('uploads/')) 
                     ? '../' + vehicle.image : vehicle.image;

        let isAvailable = (vehicle.availability === true || vehicle.availability === "Available" || vehicle.availability == 1 || vehicle.availability === "true");

        grid.innerHTML += `
            <div class="card">
                <div class="card-img">
                    <img src="${imgSrc}" onerror="this.src='https://placehold.co/300x200?text=No+Image'">
                    <div class="badge ${isAvailable ? 'available' : 'unavailable'}">
                        ${isAvailable ? 'Available' : 'Unavailable'}
                    </div>
                </div>
                <div class="card-body">
                    <h4>${vehicle.name}</h4>
                    <div style="font-size:13px;color:#888;margin-bottom:6px;">${vehicle.type}</div>
                    <div class="price">₹ ${Number(vehicle.price).toLocaleString()}</div>
                    <div class="distance">${vehicle.distance} ${typeof vehicle.distance === 'number' ? 'km away' : ''}</div>
                    <div style="margin-top:12px; display:flex; gap:10px;">
                        <button onclick="viewDetails(${index})" style="flex:1; padding:10px; background:#1e2a4a; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600;">View Details</button>
                        <button onclick="deleteVehicle(${index})" style="flex:1; padding:10px; background:#dc3545; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600;">Delete</button>
                    </div>
                </div>
            </div>`;
    });

    grid.innerHTML += `
        <a href="../pages/form2.php" class="add-vehicle-link">
            <div class="card add-vehicle-card">
                <div class="card-image-container"><span class="add-icon">+</span></div>
                <p class="car-name">Add New Vehicle</p>
            </div>
        </a>`;
}

function viewDetails(index){
    const vehicle = vehicles[index];
    let imgSrc = (typeof vehicle.image === 'string' && vehicle.image.startsWith('uploads/')) ? '../' + vehicle.image : vehicle.image;

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name;

    // Step 1: Vehicle Info
    document.getElementById("p_type").innerText = vehicle.type || "N/A";
    document.getElementById("p_brand").innerText = vehicle.brand || "N/A";
    document.getElementById("p_fuel").innerText = vehicle.fuel || "N/A";
    document.getElementById("p_price").innerText = "₹ " + vehicle.price + " / day";
    document.getElementById("p_number").innerText = vehicle.plate || vehicle.distance || "N/A";
    document.getElementById("p_location").innerText = vehicle.location || "N/A";
    document.getElementById("p_status").innerText = vehicle.availability ? "Available" : "Unavailable";

    // Step 2: Tenant Info
    document.getElementById("p_tenant_name").innerText = vehicle.tenant_name || "N/A";
    document.getElementById("p_tenant_email").innerText = vehicle.tenant_email || "N/A";
    document.getElementById("p_tenant_phone").innerText = vehicle.tenant_phone || "N/A";
    document.getElementById("p_tenant_dl").innerText = vehicle.tenant_dl || "N/A";

    // Step 3: Booking Info
    document.getElementById("p_start").innerText = vehicle.start_date || "N/A";
    document.getElementById("p_end").innerText = vehicle.end_date || "N/A";
    document.getElementById("p_duration").innerText = vehicle.duration || "N/A";
    document.getElementById("p_total").innerText = vehicle.total_price || "N/A";

    document.getElementById("vehiclePopup").style.display = "flex";
}

function closePopup(){ document.getElementById("vehiclePopup").style.display="none"; }
function outsideClick(event){ if(event.target.id === "vehiclePopup") closePopup(); }

function deleteVehicle(index){
    if(confirm("Delete this vehicle?")){
        vehicles.splice(index,1);
        renderVehicles();
    }
}

function addVehicle() { alert("Please use the 'Add New Vehicle' card to open the form."); }

document.addEventListener("DOMContentLoaded",renderVehicles);
</script>

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
          <p><b>Vehicle Type:</b> <span id="p_type"></span></p>
          <p><b>Brand / Model:</b> <span id="p_brand"></span></p>
          <p><b>Fuel Type:</b> <span id="p_fuel"></span></p>
          <p><b>Price:</b> <span id="p_price"></span></p>
          <p><b>Vehicle Number:</b> <span id="p_number"></span></p>
          <p><b>Location:</b> <span id="p_location"></span></p>
          <p><b>Availability:</b> <span id="p_status"></span></p>
      </div>

      <div class="step-box">
          <h4>Tenant Information</h4>
          <p><b>Tenant Name:</b> <span id="p_tenant_name"></span></p>
          <p><b>Tenant Email:</b> <span id="p_tenant_email"></span></p>
          <p><b>Phone Number:</b> <span id="p_tenant_phone"></span></p>
          <p><b>Driving License:</b> <span id="p_tenant_dl"></span></p>
      </div>

      <div class="step-box">
          <h4>Booking Details</h4>
          <p><b>Start Date:</b> <span id="p_start"></span></p>
          <p><b>End Date:</b> <span id="p_end"></span></p>
          <p><b>Duration:</b> <span id="p_duration"></span></p>
          <p><b>Total Price:</b> <span id="p_total"></span></p>
      </div>

      <div class="step-box">
          <h4>Booking Status</h4>
          <p><b>Status:</b> <span style="color: #1e2a4a; font-weight: bold;">Pending</span></p>
      </div>

      <div class="step-box">
          
          <div class="action-buttons">
              <button class="lender-btn" onclick="alert('Booking Accepted')">Accept Booking</button>
              <button class="lender-btn" onclick="alert('Booking Rejected')">Reject Booking</button>
              <button class="lender-btn" onclick="alert('Opening Contact...')">Contact Tenant</button>
              <button class="lender-btn" onclick="alert('Marked as Completed')">Mark as Completed</button>
          </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>