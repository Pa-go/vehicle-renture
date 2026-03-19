<?php
// 1. ABSOLUTE TOP: Session and Database handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../database/db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Preview | Renture</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
body {
    margin: 0;
    padding-top: 130px;
    font-family: "Poppins", sans-serif;
    background: #E6F0F4;
}

.main-content {
    max-width: 1100px;
    margin: auto;
    padding: 20px;
}

.booking-preview-container {
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(18px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 31, 63, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.booking-section {
    background: rgba(255, 255, 255, 0.6);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
}

.vehicle-preview-card {
    display: flex;
    gap: 20px;
    align-items: center;
}

#vehicleImage {
    width: 200px;
    height: 130px;
    object-fit: cover;
    border-radius: 12px;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.payment-method-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
    border: 2px solid transparent;
}

.payment-method-card.active {
    background: #001F3F !important;
    border-color: #FFD700;
    transform: translateY(-5px);
}

.payment-method-card.active .payment-name,
.payment-method-card.active .payment-icon {
    color: #FFFFFF !important;
}

.btn-primary {
    background: #ccc; /* Initial disabled color */
    color: #001F3F;
    padding: 12px 25px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: not-allowed;
    transition: 0.3s ease;
}

.btn-primary:disabled {
    opacity: 0.7;
}

.spec-item {
    background: #193857;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    margin-right: 5px;
}
</style>
</head>
<body>

<div id="mainContent" class="main-content">
<?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="booking-preview-container">
        <h2 class="booking-title">📋 Booking Preview</h2>
        
        <div class="booking-section">
            <h3>🚗 Selected Vehicle</h3>
            <div class="vehicle-preview-card" id="vehiclePreview">
                <div class="vehicle-preview-image">
                    <img id="vehicleImage" src="https://placehold.co/400x250?text=Loading..." alt="Vehicle Image">
                </div>
                <div class="vehicle-preview-details">
                    <p class="vehicle-preview-name" id="vehicleName" style="font-weight:bold; font-size:1.2rem;">Loading...</p>
                    <p class="vehicle-preview-price" id="vehiclePrice" style="color:#001F3F; font-weight:600;">₹0</p>
                    <div class="vehicle-specs" style="margin-top:10px;">
                        <span class="spec-item" id="vehicleBrand">Brand: -</span>
                        <span class="spec-item" id="vehicleModel">Model: -</span>
                        <span class="spec-item" id="vehicleType">Type: -</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-section">
            <h3>💰 Price Summary</h3>
            <div class="price-summary">
                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span>Vehicle Price:</span>
                    <span id="basePrice">₹0</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span>Tax (10%):</span>
                    <span id="taxAmount">₹0</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span>Service Fee:</span>
                    <span id="serviceFee">₹500</span>
                </div>
                <hr style="border:0; border-top:1px solid #ccc;">
                <div style="display:flex; justify-content:space-between; font-weight:bold; font-size:1.1rem; margin-top:10px;">
                    <span>Total Amount:</span>
                    <span id="totalAmount">₹0</span>
                </div>
            </div>
        </div>

        <div class="booking-section">
            <h3>💳 Select Payment Method</h3>
            <div class="payment-methods">
                <div class="payment-method-card" onclick="selectPaymentMethod(this, 'card')">
                    <div class="payment-icon" style="font-size:2rem;">💳</div>
                    <div class="payment-name">Credit/Debit Card</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod(this, 'upi')">
                    <div class="payment-icon" style="font-size:2rem;">📱</div>
                    <div class="payment-name">UPI</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod(this, 'cash')">
                    <div class="payment-icon" style="font-size:2rem;">💵</div>
                    <div class="payment-name">Cash</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod(this, 'wallet')">
                    <div class="payment-icon" style="font-size:2rem;">👛</div>
                    <div class="payment-name">Wallet</div>
                </div>
            </div>
        </div>

        <div class="booking-actions" style="display:flex; justify-content:space-between; margin-top:20px;">
            <button class="btn-secondary" onclick="window.history.back()" style="padding:12px 25px; border-radius:10px; border:none; background:#193857; color:white; cursor:pointer;">← Back</button>
            <button class="btn-primary" id="proceedToPaymentBtn" onclick="proceedToPayment()" disabled>Proceed to Payment</button>
        </div>
    </div>
</div>

<?php 
include __DIR__ . '/../pages/payment-modal.php'; 
include __DIR__ . '/../includes/footer.php'; 
?>

<script>
// 1. GLOBAL SCOPE DECLARATIONS
const urlParams = new URLSearchParams(window.location.search);
const vehicleId = urlParams.get('id');
const vehicleName = urlParams.get('name') || (vehicleId === '1001' ? 'Toyota Camry Hybrid (Sample)' : 'Unknown');
const vehiclePrice = parseFloat(urlParams.get('price')) || (vehicleId === '1001' ? 40000 : 0);
const vehicleBrand = urlParams.get('brand') || (vehicleId === '1001' ? 'Toyota' : 'N/A');
const vehicleModel = urlParams.get('model') || (vehicleId === '1001' ? 'Camry' : 'N/A');
const vehicleType = urlParams.get('type') || (vehicleId === '1001' ? 'Car' : 'Vehicle');
const vehicleImage = urlParams.get('image');

document.addEventListener('DOMContentLoaded', function() {
    const imgElement = document.getElementById("vehicleImage");
    
    if (vehicleImage && vehicleImage !== 'undefined' && vehicleImage !== 'null') {
        let decodedImg = decodeURIComponent(vehicleImage);
        imgElement.src = (decodedImg.startsWith('http') || decodedImg.startsWith('../')) ? decodedImg : '../' + decodedImg;
    } else if (vehicleId === '1001') {
        imgElement.src = 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=400';
    } else {
        imgElement.src = 'https://placehold.co/400x250?text=No+Image';
    }

    document.getElementById('vehicleName').textContent = vehicleName;
    document.getElementById('vehiclePrice').textContent = '₹' + vehiclePrice.toLocaleString();
    document.getElementById('basePrice').textContent = '₹' + vehiclePrice.toLocaleString();
    document.getElementById('vehicleBrand').textContent = 'Brand: ' + vehicleBrand;
    document.getElementById('vehicleModel').textContent = 'Model: ' + vehicleModel;
    document.getElementById('vehicleType').textContent = 'Type: ' + vehicleType;

    const tax = vehiclePrice * 0.10;
    const serviceFee = 500;
    const total = vehiclePrice + tax + serviceFee;

    document.getElementById('taxAmount').textContent = '₹' + tax.toLocaleString();
    document.getElementById('serviceFee').textContent = '₹' + serviceFee.toLocaleString();
    document.getElementById('totalAmount').innerHTML = '<strong>₹' + total.toLocaleString() + '</strong>';

    window.bookingTotal = total;
});

let selectedPaymentMethod = '';

function selectPaymentMethod(element, method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method-card').forEach(card => card.classList.remove('active'));
    element.classList.add('active');
    
    const proceedBtn = document.getElementById('proceedToPaymentBtn');
    if (proceedBtn) {
        proceedBtn.disabled = false;
        proceedBtn.style.opacity = "1";
        proceedBtn.style.cursor = "pointer";
        proceedBtn.style.background = "#FFD700"; // FINAL FIX: Changes to Yellow
    }
}

function proceedToPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    const methodMap = { 'card': 'Card', 'upi': 'UPI', 'cash': 'Cash', 'wallet': 'Card' };
    const modalDropdown = document.getElementById('modalPaymentMethod');
    if(modalDropdown) {
        modalDropdown.value = methodMap[selectedPaymentMethod];
        if (typeof updatePaymentFields === "function") updatePaymentFields();
    }

    const modalTotal = document.getElementById('modalTotal');
    if(modalTotal) {
        modalTotal.innerText = window.bookingTotal.toLocaleString();
    }

    const modal = document.getElementById('paymentModal');
    if(modal) modal.style.display = 'flex';
}
</script>
</body>
</html>