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

.custom-file-upload {
    display: inline-block;
    padding: 10px 18px;
    background-color: #1e2a78;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.custom-file-upload:hover {
    background-color: #16205a;
}
#image {
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

.upload-btn:hover {
    background-color: #16205a;
}
</style>
</head>

<body>

<div class="container">

  <div class="header">
    <h1>Hello Lender 👋</h1>
    <p>Here are the available vehicles listed on Renture.</p>
  </div>

  <!-- Add Vehicle -->
  <div class="add-box">
    <h3>Add Your Vehicle</h3>
    <input type="text" id="name" placeholder="Vehicle Name">
    
    <!-- Vehicle Type Added -->
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

  <!-- Filter Section -->
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

const vehicles = [
  {
    name:"Honda Civic",
    type:"Car",
    price:2000,
    distance:1.2,
    availability:true,
    image:"data:image/jpeg;base64,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"
  },
  {
    name:"BMW X5",
    type:"Car",
    price:2500,
    distance:5.1,
    availability:true,
    image:"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnTcv-iF16V7kzSU4XzDx4eOew41akzT7H7A&s"
  },
  {
    name:"Yamaha R15",
    type:"Bike",
    price:1000,
    distance:2.5,
    availability:true,
    image:"data:image/jpeg;base64,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"
  }
];

function renderVehicles(){
  const selectedType = document.getElementById("filterType").value;
  const grid = document.getElementById("vehicleGrid");
  grid.innerHTML = "";

  const filteredVehicles = vehicles.filter(vehicle => {
    if(selectedType === "All") return true;
    return vehicle.type === selectedType;
  });

  filteredVehicles.forEach((vehicle, index)=>{
    grid.innerHTML+=`
      <div class="card">
        <div class="card-img">
          <img src="${vehicle.image}">
          <div class="badge ${vehicle.availability ? 'available' : 'unavailable'}">
            ${vehicle.availability ? 'Available' : 'Unavailable'}
          </div>
        </div>
        <div class="card-body">
          <h4>${vehicle.name}</h4>
          <div style="font-size:13px;color:#888;margin-bottom:6px;">
            ${vehicle.type}
          </div>
          <div class="price">₹ ${vehicle.price.toLocaleString()}</div>
          <div class="distance">${vehicle.distance} km away</div>

          <div style="margin-top:12px; display:flex; gap:10px;">
  
  <button onclick="viewDetails(${index})"
    style="
      flex:1;
      padding:10px;
      background:#1e2a4a;
      color:white;
      border:none;
      border-radius:6px;
      cursor:pointer;
      font-weight:600;
    ">
    View Details
  </button>

  <button onclick="deleteVehicle(${index})"
    style="
      flex:1;
      padding:10px;
      background:#dc3545;
      color:white;
      border:none;
      border-radius:6px;
      cursor:pointer;
      font-weight:600;
    ">
    Delete
  </button>

</div>
            


        </div>
      </div>
    `;
  });

  if(filteredVehicles.length === 0){
    grid.innerHTML = "<p>No vehicles found.</p>";
  }
}
function addVehicle(){
  const name=document.getElementById("name").value.trim();
  const type=document.getElementById("type").value;
  const price=parseFloat(document.getElementById("price").value);
  const distance=parseFloat(document.getElementById("distance").value);
  const availability=document.getElementById("availability").value==="true";
  const imageFile=document.getElementById("image").files[0];

  if(!name || !type || !price || !distance || !imageFile){
    alert("Please fill all fields!");
    return;
  }

  const reader=new FileReader();
  reader.onload=function(e){
    vehicles.push({
      name,
      type,
      price,
      distance,
      availability,
      image:e.target.result
    });
    renderVehicles();
  };

  reader.readAsDataURL(imageFile);

  document.getElementById("name").value="";
  document.getElementById("price").value="";
  document.getElementById("distance").value="";
  document.getElementById("type").value="";
}
function viewDetails(index){
  const vehicle = vehicles[index];

  alert(
    "Vehicle Details\n\n" +
    "Name: " + vehicle.name + "\n" +
    "Type: " + vehicle.type + "\n" +
    "Price: ₹ " + vehicle.price.toLocaleString() + "\n" +
    "Distance: " + vehicle.distance + " km\n" +
    "Status: " + (vehicle.availability ? "Available" : "Unavailable")
  );
}

function deleteVehicle(index){
  if(confirm("Are you sure you want to delete this vehicle?")){
    vehicles.splice(index,1);
    renderVehicles();
  }
}
document.addEventListener("DOMContentLoaded",renderVehicles);

</script>

</body>
</html>