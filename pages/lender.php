<?php
// 1. Database Connection & Session
include '../database/db_config.php';
include '../includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/log_reg.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch REAL data from Database
// We use 'AS' to match the keys your existing JavaScript expects
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
$stmt->bind_param("i", $user_id);
$stmt->execute();
$db_vehicles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Convert PHP data to JSON for the JavaScript array
$json_vehicles = json_encode($db_vehicles);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lender Dashboard | Renture</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
    background:#E6F0F4;
}

.container{
    max-width:1200px;
    margin:auto;
    padding:40px 20px;
}

.header{
    text-align:center;
    margin-bottom:40px;
}

.header h1{
    font-size:36px;
    color:#1e2a4a;
}

.header p{
    margin-top:10px;
    color:#555;
    font-size:16px;
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

.add-box input[type="file"]{
    width:auto;
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

.add-box button:hover{
    background:#ffd700;
    color:#1e2a4a;
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

.available{
    background:#28a745;
}

.unavailable{
    background:#dc3545;
}

.card-body{
    padding:18px;
}

.card-body h4{
    margin-bottom:10px;
    color:#1e2a4a;
}

.price{
    font-weight:700;
    font-size:18px;
    margin-bottom:5px;
}

.distance{
    color:#666;
    font-size:14px;
}

@media(max-width:768px){
    .add-box input{
        width:100%;
        display:block;
    }
}
input[type="file"] {
    display: none;
}

.upload-btn {
    background-color: #1e2a78;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

#image {
    display: none;
}

.add-vehicle-link {
    text-decoration: none;
}

.add-vehicle-card {
    border: 3px dashed #1e2a4a;
    background: #f8fbff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 380px;
    cursor: pointer;
    transition: 0.3s;
}

.add-vehicle-card:hover {
    background: #fff;
    border-color: #ffd700;
}

.card-image-container {
    background: #e6f0f4;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.add-icon {
    font-size: 50px;
    color: #1e2a4a;
    font-weight: bold;
}

.car-name {
    font-weight: 700;
    color: #1e2a4a;
    font-size: 20px;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Hello Lender 👋</h1>
        <p>Here are the available vehicles listed on Renture.</p>
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

    <div class="section-title">Available Vehicles</div>
    <div class="grid" id="vehicleGrid"></div>

</div>

<script>
// 1. Get real data from PHP
const dbData = <?php echo $json_vehicles ?: '[]'; ?>;

// 2. Mock Data (Sample Cars)
const mockData = [
  {
    name:"Honda Civic (Sample)",
    type:"Car",
    price:2000,
    distance:1.2,
    availability:true,
    image:"https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=300"
  },
  {
    name:"BMW X5 (Sample)",
    type:"Car",
    price:2500,
    distance:5.1,
    availability:true,
    image:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnTcv-iF16V7kzSU4XzDx4eOew41akzT7H7A&s"
  },
  {
    name:"Yamaha R15 (Sample)",
    type:"Bike",
    price:1000,
    distance:2.5,
    availability:true,
    image:"https://images.unsplash.com/photo-1591637333184-19aa84b3e01f?auto=format&fit=crop&q=80&w=300"
  }
];

// 3. Combine both: DB data followed by Mock data
const vehicles = [...dbData, ...mockData];

function renderVehicles(){
    const selectedType = document.getElementById("filterType").value;
    const grid = document.getElementById("vehicleGrid");
    grid.innerHTML = "";

    const filteredVehicles = vehicles.filter(vehicle => {
        if(selectedType === "All") return true;
        return vehicle.type === selectedType;
    });

    filteredVehicles.forEach((vehicle, index)=>{
        // Determine if we need to look in the uploads folder (DB data) or use URL (Mock data)
        let imgSrc = (typeof vehicle.image === 'string' && vehicle.image.startsWith('uploads/')) 
                     ? '../' + vehicle.image 
                     : vehicle.image;

        // Determine availability (handles Boolean from Mock or String/Int from DB)
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
                    <div class="distance">${vehicle.distance} ${typeof vehicle.distance === 'number' || !isNaN(vehicle.distance) ? 'km away' : ''}</div>
                    <div style="margin-top:12px; display:flex; gap:10px;">
                        <button onclick="viewDetails(${index})" style="flex:1; padding:10px; background:#1e2a4a; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600;">View Details</button>
                        <button onclick="deleteVehicle(${index})" style="flex:1; padding:10px; background:#dc3545; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600;">Delete</button>
                    </div>
                </div>
            </div>
        `;
    });

    // 4. ALWAYS ADD THE "ADD NEW VEHICLE" CARD AT THE VERY END
    grid.innerHTML += `
        <a href="/Renture1/pages/form2.php" class="add-vehicle-link">
            <div class="card add-vehicle-card">
                <div class="card-image-container">
                    <span class="add-icon">+</span>
                </div>
                <div class="card-details">
                    <p class="car-name">Add New Vehicle</p>
                </div>
            </div>
        </a>
    `;
}

function addVehicle(){
    const name=document.getElementById("name").value.trim();
    const type=document.getElementById("type").value;
    const price=parseFloat(document.getElementById("price").value);
    const distance=document.getElementById("distance").value;
    const availability=document.getElementById("availability").value==="true";
    const imageFile=document.getElementById("image").files[0];

    if(!name || !type || !price || !distance || !imageFile){
        alert("Please fill all fields!");
        return;
    }

    const reader=new FileReader();
    reader.onload=function(e){
        vehicles.unshift({ name, type, price, distance, availability, image:e.target.result });
        renderVehicles();
    };
    reader.readAsDataURL(imageFile);
}

function viewDetails(index){
    const vehicle = vehicles[index];
    alert("Vehicle: " + vehicle.name + "\nPrice: ₹" + vehicle.price);
}

function deleteVehicle(index){
    if(confirm("Delete this vehicle?")){
        vehicles.splice(index,1);
        renderVehicles();
    }
}

document.addEventListener("DOMContentLoaded",renderVehicles);
</script>

</body>
</html>