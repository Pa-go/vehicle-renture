<?php
include '../database/db_config.php';
session_start();

$user_id = $_SESSION['user_id'];

// Check if the JavaScript is trying to update a status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id']) && isset($_POST['new_status'])) {
    $v_id = $_POST['update_id'];
    $n_status = $_POST['new_status'];

    $update_sql = "UPDATE vehicles SET status = ? WHERE id = ? AND owner_id = ?";
    $upd_stmt = $conn->prepare($update_sql);
    $upd_stmt->bind_param("sii", $n_status, $v_id, $user_id);
    
    if ($upd_stmt->execute()) {
        echo "SUCCESS";
    } else {
        echo "ERROR";
    }
    exit(); // Stop the rest of the page from loading during this "talk"
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/log_reg.php");
    exit();
}

$sql = "SELECT 
            id,
            vehicle_name as name,
            brand,
            model,
            vehicle_type as type,
            plate_number as plate,
            fuel_type as fuel,
            color,
            price,
            discount,
            final_price,
            description,
            `condition`,
            features,
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
    padding-top:15px;
}

.container{
    max-width:1200px;
    margin:auto;
    padding:20px;
}

.page-title{
    text-align:center;
    padding:0px ;
}

.page-title h1{
    color:#001F3F;
    font-size:50px;
    font-weight:700;
}

.page-title p{
    color:#193857;
    font-size:14px;
}

.section-title{
    font-size:22px;
    margin-bottom:20px;
    color:#001F3F;
    border-bottom:2px solid rgba(0,31,63,0.2);
    padding-bottom:8px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:30px;
}

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

.badge{
    position:absolute;
    top:12px;
    right:12px;
    display:inline-block;
    width:fit-content;
    white-space:nowrap;
    padding:4px 10px;
    border-radius:8px;
    font-size:11px;
    font-weight:700;
    color:#FFFFFF;
    text-transform:uppercase;
    letter-spacing:0.5px;
    z-index:5;
    box-shadow:0 4px 8px rgba(0,0,0,0.2);
}

.available{ background:#28a745; }
.unavailable{ background:#dc3545; }

.card-body{
    padding:18px;
}

.card-body h4{
    margin-bottom:8px;
    color:#001F3F;
}

.price{
    font-weight:700;
    font-size:18px;
    color:#001F3F;
}

.add-vehicle-link{ text-decoration:none; }

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

.popup-overlay{
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,31,63,0.7);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.popup-card{
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(20px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.3);
    width:850px;
    max-width:95%;
    overflow:hidden;
    box-shadow:0 20px 45px rgba(0,31,63,0.3);
    display:flex;
}

.popup-left{
    width:40%;
    background:rgba(0,31,63,0.05);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px;
}

.popup-img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    border-radius:10px;
}

.popup-right{
    width:60%;
    padding:25px;
    position:relative;
    max-height:85vh;
    overflow-y:auto;
}

.popup-close{
    position:absolute;
    top:15px; right:20px;
    font-size:28px;
    cursor:pointer;
    color:#001F3F;
    font-weight:bold;
    line-height:1;
}

.popup-right h2{
    color:#001F3F;
    margin-bottom:15px;
}

.step-box{
    margin-bottom:20px;
}

.step-box h4{
    color:#001F3F;
    border-bottom:1px solid rgba(0,31,63,0.2);
    padding-bottom:5px;
    margin-bottom:10px;
    font-size:16px;
}

.step-box p{
    font-size:14px;
    margin-bottom:5px;
    color:#555;
}

.step-box p b{
    color:#333;
    width:140px;
    display:inline-block;
}

.action-buttons{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:15px;
}

.lender-btn{
    background:#FFD700;
    color:#001F3F;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    font-size:13px;
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
        <h1>Hello Lender </h1>
        <p>Smart management for vehicles and rental bookings</p>
    </div>

    <div class="section-title">Your Vehicles</div>
    <div class="grid" id="vehicleGrid"></div>

</div>

<!-- POPUP -->
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
          <h4>Status</h4>
          <p><b>Availability:</b> <span id="p_status"></span></p>
      </div>

      <div class="step-box">
          <div class="action-buttons">
              <button class="lender-btn" onclick="updateBooking('accept')">Accept Booking</button>
              <button class="lender-btn" onclick="updateBooking('reject')">Reject Booking</button>
          </div>
      </div>

    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;

const mockData = [
  {
    name: "Toyota Camry Hybrid (Sample)",
    brand: "Toyota",
    model: "Camry",
    type: "Car",
    plate: "MH 01 AB 1234",
    fuel: "Hybrid",
    color: "White",
    price: 4000,
    discount: 0,
    final_price: 4000,
    description: "A well-maintained hybrid sedan perfect for city and highway driving.",
    condition: "Certified Pre-Owned",
    features: "Airbags, Bluetooth, GPS, ABS",
    availability: "Available",
    image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400"
  }
];

const vehicles = [...dbData, ...mockData];

let currentIndex = null;

function renderVehicles(){
    const grid = document.getElementById("vehicleGrid");
    grid.innerHTML = "";

    vehicles.forEach((vehicle, index)=>{
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
                    <div class="price">₹ ${Number(vehicle.final_price || vehicle.price).toLocaleString()}</div>
                    <div style="margin-top:12px; display:flex; gap:10px;">
                        <button onclick="viewDetails(${index})" style="flex:1; padding:10px; background:#001F3F; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;">View Details</button>
                        <button onclick="deleteVehicle(${index})" style="flex:1; padding:10px; background:#dc3545; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;">Delete</button>
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
    currentIndex = index;
    const vehicle = vehicles[index];
    let imgSrc = (typeof vehicle.image === 'string' && vehicle.image.startsWith('uploads/')) ? '../' + vehicle.image : vehicle.image;

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name || "N/A";

    document.getElementById("p_name").innerText        = vehicle.name        || "N/A";
    document.getElementById("p_brand").innerText       = vehicle.brand       || "N/A";
    document.getElementById("p_model").innerText       = vehicle.model       || "N/A";
    document.getElementById("p_type").innerText        = vehicle.type        || "N/A";
    document.getElementById("p_plate").innerText       = vehicle.plate       || "N/A";
    document.getElementById("p_fuel").innerText        = vehicle.fuel        || "N/A";
    document.getElementById("p_color").innerText       = vehicle.color       || "N/A";
    document.getElementById("p_condition").innerText   = vehicle.condition   || "N/A";
    document.getElementById("p_features").innerText    = vehicle.features    || "N/A";
    document.getElementById("p_description").innerText = vehicle.description || "N/A";

    document.getElementById("p_price").innerText       = vehicle.price       ? "₹ " + Number(vehicle.price).toLocaleString()       : "N/A";
    document.getElementById("p_discount").innerText    = vehicle.discount    ? "₹ " + Number(vehicle.discount).toLocaleString()    : "N/A";
    document.getElementById("p_final_price").innerText = vehicle.final_price ? "₹ " + Number(vehicle.final_price).toLocaleString() : "N/A";

    let isAvailable = (vehicle.availability === true || vehicle.availability === "Available" || vehicle.availability == 1 || vehicle.availability === "true");
    const statusEl = document.getElementById("p_status");
    statusEl.innerText = isAvailable ? "Available" : "Unavailable";
    statusEl.style.color = isAvailable ? "#28a745" : "#dc3545";
    statusEl.style.fontWeight = "bold";

    document.getElementById("vehiclePopup").style.display = "flex";
}

function updateBooking(action){
    if(currentIndex === null) return;

    const vehicle = vehicles[currentIndex];
    
    // Safety check for real DB ID
    if(!vehicle.id) {
        alert("Cannot update sample data. Please test with a real vehicle.");
        return;
    }

    const newStatus = (action === 'accept') ? "Available" : "Unavailable";

    const formData = new FormData();
    formData.append('update_id', vehicle.id);
    formData.append('new_status', newStatus);

    // Send to the SAME page (lender.php)
    fetch('lender.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data.trim() === "SUCCESS") {
            vehicle.availability = newStatus;
            renderVehicles();
            closePopup();
            alert("Vehicle status updated in Database!");
        } else {
            alert("Error: " + data);
        }
    });
}

function closePopup(){ document.getElementById("vehiclePopup").style.display = "none"; }
function outsideClick(event){ if(event.target.id === "vehiclePopup") closePopup(); }

function deleteVehicle(index){
    if(confirm("Delete this vehicle?")){
        vehicles.splice(index, 1);
        if(currentIndex === index) closePopup();
        currentIndex = null;
        renderVehicles();
    }
}

document.addEventListener("DOMContentLoaded", renderVehicles);
</script>

</body>
</html>