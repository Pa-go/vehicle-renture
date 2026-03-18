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
    font-family:"Poppins",sans-serif;
    background:#E6F0F4;
    padding-top:130px;
}

/* CONTAINER */
.container{
    max-width:1200px;
    margin:auto;
    padding:20px;
}

/* PAGE TITLE */
.page-title{
    text-align:center;
    padding:30px 20px 10px;
}

.page-title h1{
    color:#001F3F;
    font-size:30px;
}

.page-title p{
    color:#193857;
}

/* ADD BOX (GLASS STYLE) */
.add-box{
    background:rgba(255,255,255,0.2);
    backdrop-filter:blur(15px);
    padding:25px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 10px 25px rgba(0,31,63,0.15);
    margin-bottom:40px;
}

.add-box h3{
    margin-bottom:15px;
    color:#001F3F;
}

/* INPUTS */
.add-box input,
.add-box select{
    padding:12px;
    margin:8px 5px;
    border-radius:10px;
    border:1px solid rgba(0,31,63,0.2);
    background:rgba(255,255,255,0.6);
}

/* BUTTON */
.add-box button{
    padding:12px 20px;
    background:#FFD700;
    color:#001F3F;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.add-box button:hover{
    background:#e6c200;
}

/* FILTER */
.section-title{
    font-size:22px;
    margin-bottom:20px;
    color:#001F3F;
    border-bottom:2px solid rgba(0,31,63,0.2);
    padding-bottom:8px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:30px;
}

/* CARD */
.card{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(15px);
    border-radius:20px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 10px 25px rgba(0,31,63,0.15);
    transition:all 0.3s ease;
}

.card:hover{
    transform:translateY(-8px) scale(1.02);
    box-shadow:0 15px 35px rgba(0,31,63,0.25);
}

/* IMAGE */
.card-img{
    position:relative;
    height:220px;
    overflow:hidden;
}

.card-img img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:0.4s;
}

.card:hover img{
    transform:scale(1.1);
}

/* BADGE */
.badge{
    position:absolute;
    top:12px;
    right:12px;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    color:#FFFFFF;
    background:#001F3F;
}

.available{ background:#001F3F; }
.unavailable{ background:#193857; }

/* BODY */
.card-body{
    padding:18px;
}

.card-body h4{
    margin-bottom:8px;
    color:#001F3F;
}

/* TEXT */
.price{
    font-weight:700;
    font-size:18px;
    color:#001F3F;
}

.distance{
    color:#193857;
    font-size:14px;
}

/* BUTTON GROUP */
.card-body div button{
    border-radius:10px !important;
    transition:0.3s;
}

/* VIEW BUTTON */
.card-body button:first-child{
    background:#001F3F !important;
    color:#FFFFFF !important;
}

.card-body button:first-child:hover{
    background:#193857 !important;
}

/* DELETE BUTTON */
.card-body button:last-child{
    background:#FFD700 !important;
    color:#001F3F !important;
}

.card-body button:last-child:hover{
    background:#e6c200 !important;
}

/* ADD VEHICLE CARD */
.add-vehicle-card{
    border:2px dashed #001F3F;
    background:rgba(255,255,255,0.2);
    backdrop-filter:blur(10px);
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:380px;
    cursor:pointer;
    border-radius:20px;
    transition:0.3s;
}

.add-vehicle-card:hover{
    background:rgba(255,255,255,0.3);
    transform:scale(1.03);
}

.card-image-container{
    background:#001F3F;
    width:80px;
    height:80px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

.add-icon{
    font-size:40px;
    color:#FFD700;
}

.car-name{
    margin-top:10px;
    font-weight:600;
    color:#001F3F;
}

/* POPUP */
.popup-overlay{
    background:rgba(0,31,63,0.7);
}

/* POPUP CARD */
.popup-card{
    background:rgba(255,255,255,0.2);
    backdrop-filter:blur(20px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.3);
}

/* LEFT */
.popup-left{
    background:rgba(255,255,255,0.2);
}

/* TEXT */
.popup-right h2{
    color:#001F3F;
}

/* STEP BOX */
.step-box h4{
    color:#001F3F;
    border-bottom:1px solid rgba(0,31,63,0.2);
}

.step-box p{
    color:#193857;
}

/* ACTION BUTTONS */
.lender-btn{
    background:#FFD700;
    color:#001F3F;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.lender-btn:hover{
    background:#e6c200;
}
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
                    <div class="badge" style="background:${isAvailable ? '#001F3F' : '#193857'}">
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