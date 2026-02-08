<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renture</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Temporary Message Box -->
<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div id="mainContent" class="main-content">

    <!-- VIDEO BANNER -->
    <section class="video-banner">
        <div class="video-card">
            <video id="topVideo" src="../assets/video/Hailuo Video create a 3d video that has a c 446137454554259461.mp4" autoplay muted loop playsinline preload="metadata"></video>
            <div id="videoFallback" style="position:absolute;inset:0;display:none;align-items:center;justify-content:center;color:#fff;background:#000;" data-i18n="video.fallback">Video not available</div>
        </div>
    </section>

    <!-- VEHICLE LISTINGS -->
    <section class="vehicle-listings">
        <h2 class="listings-title" data-i18n="listings.title">The Most Searched Cars</h2>
        <div class="listings-container" id="listingsContainer">
            <div class="card">
                <div class="card-image-container">
                    <span class="badge">Great Price</span>
                    <span class="image-placeholder">[Car Image Placeholder]</span>
                </div>
                <div class="card-details">
                    <p class="car-name">Toyota Camry Hybrid</p>
                    <p class="price">$40,000</p>
                    <button class="view-details-btn">View Details</button>
                    <a href="/Renture1/pages/booking-preview.php?name=Toyota Camry Hybrid&price=40000&brand=Toyota&model=Camry&type=Car" class="pay-now-link">
                        <button class="view-details-btn" style="background: linear-gradient(135deg, #001F3F, #193857); color: white; margin-top: 8px; width: 100%;">Book Now</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-image-container">
                    <span class="badge">New Listing!</span>
                    <span class="image-placeholder">[Car Image Placeholder]</span>
                </div>
                <div class="card-details">
                    <p class="car-name">Honda Civic</p>
                    <p class="price">$25,000</p>
                    <button class="view-details-btn">View Details</button>
                    <a href="/Renture1/pages/booking-preview.php?name=Honda Civic&price=25000&brand=Honda&model=Civic&type=Car" class="pay-now-link">
                        <button class="view-details-btn" style="background: linear-gradient(135deg, #001F3F, #193857); color: white; margin-top: 8px; width: 100%;">Book Now</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-image-container">
                    <span class="badge">Electric Vehicle</span>
                    <span class="image-placeholder">[Car Image Placeholder]</span>
                </div>
                <div class="card-details">
                    <p class="car-name">Tesla Model 3</p>
                    <p class="price">$55,000</p>
                    <button class="view-details-btn">View Details</button>
                    <a href="/Renture1/pages/booking-preview.php?name=Tesla Model 3&price=55000&brand=Tesla&model=Model 3&type=Car" class="pay-now-link">
                        <button class="view-details-btn" style="background: linear-gradient(135deg, #001F3F, #193857); color: white; margin-top: 8px; width: 100%;">Book Now</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-image-container">
                    <span class="badge">Great Price</span>
                    <span class="image-placeholder">[Car Image Placeholder]</span>
                </div>
                <div class="card-details">
                    <p class="car-name">Ford F-150</p>
                    <p class="price">$35,000</p>
                    <button class="view-details-btn">View Details</button>
                    <a href="/Renture1/pages/booking-preview.php?name=Ford F-150&price=35000&brand=Ford&model=F-150&type=Truck" class="pay-now-link">
                        <button class="view-details-btn" style="background: linear-gradient(135deg, #001F3F, #193857); color: white; margin-top: 8px; width: 100%;">Book Now</button>
                    </a>
                </div>
            </div>
            
            <!-- Add New Vehicle Card -->
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
        </div>
    </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>
