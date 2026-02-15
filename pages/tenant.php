<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Cars Listing | Renture</title>

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

/* Header */
.header{
  text-align:center;
  margin-bottom:30px;
}

.header h1{
  font-size:36px;
  color:#1e2a4a;
}

.header p{
  color:#555;
  margin-top:10px;
}

/* Filter Section */
.filter-box{
  background:#fff;
  padding:20px;
  border-radius:12px;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
  margin-bottom:40px;
  display:flex;
  flex-wrap:wrap;
  gap:15px;
  align-items:center;
}

.filter-box select,
.filter-box input{
  padding:10px;
  border:1px solid #ddd;
  border-radius:8px;
}

/* Grid */
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
  gap:25px;
}

/* Card */
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

/* Badge LEFT */
.badge{
  position:absolute;
  top:10px;
  left:10px;
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
  margin-bottom:15px;
}

/* Buttons */
.btn-group{
  display:flex;
  gap:10px;
}

.btn{
  flex:1;
  padding:10px;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-weight:600;
}

.details{
  background:#1e2a4a;
  color:white;
}

.details:hover{
  background:#ffd700;
  color:#1e2a4a;
}

.book{
  background:#28a745;
  color:white;
}

.book:hover{
  background:#218838;
}
</style>
</head>

<body>

<div class="container">

  <!-- Header -->
  <div class="header">
    <h1>Hello Tenant 👋</h1>
    <p>Choose from our wide selection of quality rental cars</p>
  </div>

  <!-- Filters (UNCHANGED STYLE) -->
  <div class="filter-box">
    <label>Sort By:</label>
    <select>
      <option>Default</option>
      <option>Price: Low to High</option>
      <option>Price: High to Low</option>
    </select>

    <label>Filter By:</label>
    <select>
      <option>All Vehicles</option>
      <option>Available</option>
      <option>Unavailable</option>
    </select>

    <label>Price Range:</label>
    <input type="number" placeholder="Min Price">
    <input type="number" placeholder="Max Price">
  </div>

  <!-- Vehicles -->
  <div class="grid">

    <div class="card">
      <div class="card-img">
        <img src="data:image/jpeg;base64,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">
        <div class="badge available">✔ Available</div>
      </div>
      <div class="card-body">
        <h4>Toyota Camry Hybrid</h4>
        <div class="price">₹ 40,000</div>
        <div class="distance">2.5 km away</div>
        <div class="btn-group">
          <button class="btn details">View Details</button>
          <button class="btn book">Book Now</button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-img">
        <img src="https://www.globalsuzuki.com/globalnews/2025/img/0528.jpg">
        <div class="badge available">✔ Available</div>
      </div>
      <div class="card-body">
        <h4>Suzuki</h4>
        <div class="price">₹ 25,000</div>
        <div class="distance">1.2 km away</div>
        <div class="btn-group">
          <button class="btn details">View Details</button>
          <button class="btn book">Book Now</button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-img">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4L-2-OmGbn7Qcz9i-R1mTP1HbwQhUETUUyQ&s">
        <div class="badge unavailable">✖ Unavailable</div>
      </div>
      <div class="card-body">
        <h4>Kia k5</h4>
        <div class="price">₹ 55,000</div>
        <div class="distance">3.8 km away</div>
        <div class="btn-group">
          <button class="btn details">View Details</button>
          <button class="btn book" disabled style="background:#ccc;cursor:not-allowed;">
            Book Now
          </button>
        </div>
      </div>
    </div>

  </div>

</div>

</body>
</html>