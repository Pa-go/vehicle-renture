<?php
session_start();
// 1. DATABASE & SESSION
include __DIR__ . '/../database/db_config.php';

$sql = "SELECT id, vehicle_name as name, price, vehicle_image as image, brand, model, 
               vehicle_type as type, status as availability, fuel_type as fuel, 
               plate_number as plate, color, v_condition as `condition`, 
               description, features, discount, final_price 
        FROM vehicles WHERE status = 'Available' LIMIT 3";
$result = $conn->query($sql);
$real_vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $real_vehicles[] = $row;
    }
}

$mock_vehicles = [
    [
        'id' => 1001,
        'name' => "Toyota Camry Hybrid (Sample)",
        'price' => 4000,
        'final_price' => 4000,
        'discount' => 0,
        'brand' => "Toyota",
        'model' => "Camry",
        'type' => "Car",
        'availability' => "Available",
        'fuel' => "Hybrid",
        'plate' => "SAMPLE-01",
        'color' => "White",
        'condition' => "Excellent",
        'description' => "A premium hybrid sedan for smooth city drives.",
        'features' => "GPS, Bluetooth, Sunroof",
        'image' => "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400"
    ]
];

$home_listings = array_merge($real_vehicles, $mock_vehicles);
$json_listings = json_encode($home_listings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renture | Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .listings-container {
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
            display: flex;
            flex-direction: column;
        }
        .card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,31,63,0.25); }
        .card-img { height: 220px; position: relative; overflow: hidden; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; }
        .badge {
            position: absolute; top: 12px; right: 12px;
            display: inline-block;
            width: fit-content;
            white-space: nowrap;
            padding: 4px 10px; border-radius: 8px;
            font-size: 11px; font-weight: 700; color: white;
            text-transform: uppercase; z-index: 5;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .available { background: #28a745; }
        .unavailable { background: #dc3545; }
        .card-body { padding: 18px; display: flex; flex-direction: column; flex-grow: 1; }
        .card-body h4 { margin: 0; font-size: 18px; color: #001F3F; }
        .card-body small { color: #193857; margin-top: 4px; display: block; }
        .price-tag { font-size: 22px; font-weight: bold; color: #001F3F; margin: 12px 0; }
        .btn-group { display: flex; gap: 10px; margin-top: auto; }
        .btn { flex: 1; padding: 10px; border-radius: 12px; font-weight: 600; cursor: pointer; border: none; transition: 0.3s; font-family: 'Poppins', sans-serif; text-decoration: none; text-align: center; font-size: 14px; }
        .btn-details { background: transparent; border: 1px solid #001F3F; color: #001F3F; line-height: 2.2; }
        .btn-details:hover { background: #001F3F; color: white; }
        .btn-book { background: #FFD700; color: #001F3F; }
        .btn-book:hover { background: #e6c200; }
        .btn-book.disabled { background: #ccc; cursor: not-allowed; pointer-events: none; }
        .listings-title { text-align: center; color: #001F3F; font-size: 30px; margin: 40px 0 10px; }

        /* POPUP STYLES */
        .popup-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,31,63,0.7); display: none;
            justify-content: center; align-items: center; z-index: 9999;
        }
        .popup-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(20px);
            border-radius: 20px; width: 850px; max-width: 95%;
            display: flex; overflow: hidden; box-shadow: 0 20px 45px rgba(0,31,63,0.3);
        }
        .popup-left { width: 40%; background: rgba(0,31,63,0.05); display: flex; align-items: center; justify-content: center; padding: 10px; }
        .popup-img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 10px; }
        .popup-right { width: 60%; padding: 25px; position: relative; max-height: 85vh; overflow-y: auto; }
        .popup-close { position: absolute; top: 15px; right: 20px; font-size: 28px; cursor: pointer; color: #001F3F; font-weight: bold; }
        .step-box { margin-bottom: 20px; }
        .step-box h4 { color: #001F3F; border-bottom: 1px solid rgba(0,31,63,0.2); padding-bottom: 5px; margin-bottom: 10px; font-size: 16px; }
        .step-box p { font-size: 14px; margin-bottom: 5px; color: #555; }
        .step-box p b { color: #333; width: 140px; display: inline-block; }
        .booking-btn { background: #FFD700; color: #001F3F; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .booking-btn:hover { background: #e6c200; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div id="mainContent" class="main-content">
    <section class="video-banner">
        <div class="video-card">
            <video id="topVideo" src="../assets/video/Hailuo Video create a 3d video that has a c 446137454554259461.mp4" autoplay muted loop playsinline preload="metadata" style="width: 100%; border-radius: 20px;"></video>
        </div>
    </section>

    <h2 class="listings-title">The Most Searched Cars</h2>

    <div class="listings-container">
        <?php foreach($home_listings as $index => $v): 
            $imgSrc = (isset($v['image']) && strpos($v['image'], 'http') === 0) 
                      ? $v['image'] 
                      : '../' . ($v['image'] ?? 'assets/images/placeholder.jpg');
            
            $isAvailable = ($v['availability'] === "Available" || $v['availability'] == 1);
            $displayPrice = $v['final_price'] ?? $v['price'];
            
            // Generate the URL for the booking preview
            $bookingUrl = "booking-preview.php?id=" . $v['id'] . 
                          "&name=" . urlencode($v['name']) . 
                          "&price=" . $displayPrice . 
                          "&brand=" . urlencode($v['brand'] ?? 'N/A') . 
                          "&model=" . urlencode($v['model'] ?? 'N/A') . 
                          "&type=" . urlencode($v['type'] ?? 'Car') . 
                          "&image=" . urlencode($imgSrc);
        ?>
        
        <div class="card">
            <div class="card-img">
                <img src="<?php echo $imgSrc; ?>" onerror="this.src='https://placehold.co/400x250?text=No+Preview'">
                <div class="badge <?php echo $isAvailable ? 'available' : 'unavailable'; ?>">
                    <?php echo $isAvailable ? 'Available' : 'Unavailable'; ?>
                </div>
            </div>
            <div class="card-body">
                <h4><?php echo $v['name']; ?></h4>
                <small><?php echo ($v['type'] ?? 'Vehicle') . ' · ' . ($v['brand'] ?? 'Sample'); ?></small>
                
                <div class="price-tag">
                    ₹ <?php echo number_format($displayPrice); ?>
                    <small style="font-size:13px; font-weight:normal; color:#888;">/ day</small>
                </div>
                
                <div class="btn-group">
                    <button onclick="viewDetails(<?php echo $index; ?>)" class="btn btn-details">View Details</button>
                    
                    <a href="javascript:void(0);" 
                       onclick="handleBookingRedirect('<?php echo $bookingUrl; ?>')"
                       class="btn btn-book <?php echo $isAvailable ? '' : 'disabled'; ?>" style="line-height: 2.2;">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

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
          <p><b>Start Date:</b> <input type="date" id="bookStart" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>End Date:</b> <input type="date" id="bookEnd" onchange="calculateTotal()" style="padding:5px; border-radius:6px; border:1px solid #ccc;"></p>
          <p><b>Duration:</b> <span id="calcDuration">0</span> Days</p>
          <p><b>Total Price:</b> ₹<span id="calcTotal">0</span></p>
      </div>
      <button id="popupBookBtn" class="booking-btn" onclick="bookNow()">Book Now</button>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
const allVehicles = <?php echo $json_listings; ?>;
// Check if user is logged in via PHP session
const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
let currentVehicle = null;

// Function for the card button
function handleBookingRedirect(url) {
    if (!isLoggedIn) {
        window.location.href = '../pages/log_reg.php';
    } else {
        window.location.href = url;
    }
}

function viewDetails(index) {
    const vehicle = allVehicles[index];
    currentVehicle = vehicle;

    let imgSrc = (vehicle.image && vehicle.image.startsWith('http')) 
                 ? vehicle.image 
                 : '../' + (vehicle.image || 'assets/images/placeholder.jpg');

    document.getElementById("popupImage").src = imgSrc;
    document.getElementById("popupName").innerText = vehicle.name || "N/A";
    document.getElementById("p_name").innerText = vehicle.name || "N/A";
    document.getElementById("p_brand").innerText = vehicle.brand || "N/A";
    document.getElementById("p_model").innerText = vehicle.model || "N/A";
    document.getElementById("p_type").innerText = vehicle.type || "N/A";
    document.getElementById("p_plate").innerText = vehicle.plate || "N/A";
    document.getElementById("p_fuel").innerText = vehicle.fuel || "N/A";
    document.getElementById("p_color").innerText = vehicle.color || "N/A";
    document.getElementById("p_condition").innerText = vehicle.condition || "N/A";
    document.getElementById("p_features").innerText = vehicle.features || "N/A";
    document.getElementById("p_description").innerText = vehicle.description || "N/A";

    document.getElementById("p_price").innerText = "₹ " + Number(vehicle.price).toLocaleString();
    document.getElementById("p_discount").innerText = vehicle.discount ? "₹ " + Number(vehicle.discount).toLocaleString() : "None";
    document.getElementById("p_final_price").innerText = "₹ " + Number(vehicle.final_price || vehicle.price).toLocaleString();

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
        const daysDiff = Math.ceil((new Date(end) - new Date(start)) / (1000 * 3600 * 24));
        if (daysDiff > 0) {
            const price = currentVehicle.final_price || currentVehicle.price;
            document.getElementById("calcDuration").innerText = daysDiff;
            document.getElementById("calcTotal").innerText = Number(daysDiff * price).toLocaleString();
        }
    }
}

// Function for the popup button
function bookNow() {
    if (!currentVehicle) return;

    if (!isLoggedIn) {
        window.location.href = '../pages/log_reg.php';
        return;
    }

    let imgSrc = (currentVehicle.image && currentVehicle.image.startsWith('http')) 
                 ? currentVehicle.image 
                 : '../' + (currentVehicle.image || 'assets/images/placeholder.jpg');

    const url = 'booking-preview.php?' + 
        'id=' + currentVehicle.id +
        '&name=' + encodeURIComponent(currentVehicle.name) +
        '&price=' + (currentVehicle.final_price || currentVehicle.price) +
        '&type=' + encodeURIComponent(currentVehicle.type) +
        '&brand=' + encodeURIComponent(currentVehicle.brand) +
        '&model=' + encodeURIComponent(currentVehicle.model) +
        '&image=' + encodeURIComponent(imgSrc);
    window.location.href = url;
}

function closePopup() { document.getElementById("vehiclePopup").style.display = "none"; }
function outsideClick(event) { if (event.target.id === "vehiclePopup") closePopup(); }
</script>

</body>
</html>