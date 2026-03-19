<?php
// 1. DATABASE & SESSION
include __DIR__ . '/../database/db_config.php';

// Fetch up to 3 real available vehicles from DB
$sql = "SELECT id, vehicle_name as name, price, vehicle_image as image, brand, model, vehicle_type as type, status as availability 
        FROM vehicles WHERE status = 'Available' LIMIT 3";
$result = $conn->query($sql);
$real_vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $real_vehicles[] = $row;
    }
}

// 2. YOUR MOCK DATA (Sample card)
$mock_vehicles = [
    [
        'id' => 1001,
        'name' => "Toyota Camry Hybrid (Sample)",
        'price' => 40000,
        'brand' => "Toyota",
        'model' => "Camry",
        'type' => "Car",
        'availability' => "Available",
        'image' => "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400"
    ]
];

$home_listings = array_merge($real_vehicles, $mock_vehicles);
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
            display: inline-block; width: fit-content;
            white-space: nowrap; padding: 4px 10px; border-radius: 8px;
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
        .btn-book:disabled { background: #ccc; cursor: not-allowed; }

        .listings-title { text-align: center; color: #001F3F; font-size: 30px; margin: 40px 0 10px; }
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
        <?php foreach($home_listings as $v): 
            $imgSrc = (isset($v['image']) && strpos($v['image'], 'http') === 0) 
                      ? $v['image'] 
                      : '../' . ($v['image'] ?? 'assets/images/placeholder.jpg');
            
            $isAvailable = ($v['availability'] === "Available" || $v['availability'] == 1);
            
            // Build the URL parameters once to ensure consistency
            $urlParams = "id=" . $v['id'] . 
                         "&name=" . urlencode($v['name']) . 
                         "&price=" . $v['price'] . 
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
                
                <div class="price-tag">₹ <?php echo number_format($v['price']); ?> <small style="font-size:13px; font-weight:normal; color:#888;">/ day</small></div>
                
                <div class="btn-group">
                    <a href="booking-preview.php?<?php echo $urlParams; ?>" class="btn btn-details">View Details</a>
                    
                    <a href="booking-preview.php?<?php echo $urlParams; ?>" class="btn btn-book <?php echo $isAvailable ? '' : 'disabled'; ?>" style="line-height: 2.2;">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
        
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>