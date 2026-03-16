<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Preview | Renture</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
body{
    margin:0;
    padding-top:130px;
    font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background:#E6F0F4;
}

.main-content{
    max-width:1100px;
    margin:auto;
    padding:20px;
}
.vehicle-preview-image img{
    width:180px;
    height:120px;
    object-fit:cover;
    border-radius:8px;
    background: #ccc; /* Adds a gray background if image takes a second to load */
}
</style>
</head>
<body>

<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<div id="mainContent" class="main-content">
<?php
session_start();
include __DIR__ . '/../includes/header.php';
?>
    <div class="booking-preview-container">
        <h2 class="booking-title">📋 Booking Preview</h2>
        
        <div class="booking-section">
            <h3>🚗 Selected Vehicle</h3>
            <div class="vehicle-preview-card" id="vehiclePreview">
                <div class="vehicle-preview-image">
                    <img id="vehicleImage" src="https://placehold.co/400x250?text=Loading..." alt="Vehicle Image">
                </div>
                <div class="vehicle-preview-details">
                    <p class="vehicle-preview-name" id="vehicleName">Toyota Camry Hybrid</p>
                    <p class="vehicle-preview-price" id="vehiclePrice">$40,000</p>
                    <div class="vehicle-specs">
                        <span class="spec-item" id="vehicleBrand">Brand: Toyota</span>
                        <span class="spec-item" id="vehicleModel">Model: Camry</span>
                        <span class="spec-item" id="vehicleType">Type: Car</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-section">
            <h3>💰 Price Summary</h3>
            <div class="price-summary">
                <div class="price-row">
                    <span>Vehicle Price:</span>
                    <span id="basePrice">$40,000</span>
                </div>
                <div class="price-row">
                    <span>Tax (10%):</span>
                    <span id="taxAmount">$4,000</span>
                </div>
                <div class="price-row">
                    <span>Service Fee:</span>
                    <span id="serviceFee">$500</span>
                </div>
                <div class="price-row total-row">
                    <span><strong>Total Amount:</strong></span>
                    <span id="totalAmount"><strong>$44,500</strong></span>
                </div>
            </div>
        </div>

        <div class="booking-section">
            <h3>💳 Select Payment Method</h3>
            <div class="payment-methods">
                <div class="payment-method-card" onclick="selectPaymentMethod('card')">
                    <div class="payment-icon">💳</div>
                    <div class="payment-name">Credit/Debit Card</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod('upi')">
                    <div class="payment-icon">📱</div>
                    <div class="payment-name">UPI</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod('cash')">
                    <div class="payment-icon">💵</div>
                    <div class="payment-name">Cash</div>
                </div>
                <div class="payment-method-card" onclick="selectPaymentMethod('wallet')">
                    <div class="payment-icon">👛</div>
                    <div class="payment-name">Wallet</div>
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <button class="btn-secondary" onclick="window.history.back()">← Back</button>
            <button class="btn-primary" id="proceedToPaymentBtn" onclick="proceedToPayment()" disabled>Proceed to Payment</button>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../pages/payment-modal.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
<script>
// Get vehicle details from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const vehicleName = urlParams.get('name') || 'Unnamed Vehicle';
const vehiclePrice = parseFloat(urlParams.get('price')) || 0;
const vehicleBrand = urlParams.get('brand') || 'N/A';
const vehicleModel = urlParams.get('model') || 'N/A';
const vehicleType = urlParams.get('type') || 'Vehicle';
const vehicleImage = urlParams.get('image');

// Update page with vehicle details
document.addEventListener('DOMContentLoaded', function() {
    
    // IMAGE FIX: Safely grab the image from the URL and apply it
    const imgElement = document.getElementById("vehicleImage");
    if (vehicleImage && vehicleImage !== 'undefined') {
        imgElement.src = decodeURIComponent(vehicleImage);
    } else {
        imgElement.src = 'https://placehold.co/400x250?text=No+Image+Available';
    }

    // In case the image link is broken, fall back to a missing image placeholder
    imgElement.onerror = function() {
        this.src = 'https://placehold.co/400x250?text=Image+Missing';
    };

    // Text Details
    document.getElementById('vehicleName').textContent = vehicleName;
    document.getElementById('vehiclePrice').textContent = '₹' + vehiclePrice.toLocaleString();
    document.getElementById('basePrice').textContent = '₹' + vehiclePrice.toLocaleString();
    document.getElementById('vehicleBrand').textContent = 'Brand: ' + vehicleBrand;
    document.getElementById('vehicleModel').textContent = 'Model: ' + vehicleModel;
    document.getElementById('vehicleType').textContent = 'Type: ' + vehicleType;

    // Calculate totals
    const tax = vehiclePrice * 0.10;
    const serviceFee = 500;
    const total = vehiclePrice + tax + serviceFee;

    document.getElementById('taxAmount').textContent = '₹' + tax.toLocaleString();
    document.getElementById('serviceFee').textContent = '₹' + serviceFee.toLocaleString();
    document.getElementById('totalAmount').innerHTML = '<strong>₹' + total.toLocaleString() + '</strong>';

    // Store total for payment modal
    window.bookingTotal = total;
});

let selectedPaymentMethod = '';

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    
    // Remove active class from all cards
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Add active class to selected card
    event.currentTarget.classList.add('active');
    
    // Enable proceed button
    document.getElementById('proceedToPaymentBtn').disabled = false;
}

function proceedToPayment() {
    if (!selectedPaymentMethod) {
        if (typeof showMessage === 'function') {
            showMessage('Please select a payment method');
        } else {
            alert('Please select a payment method');
        }
        return;
    }

    // Open payment modal with total amount first
    if (typeof openPaymentModal === 'function') {
        openPaymentModal(window.bookingTotal || 0);
        
        // Set payment method in modal after a short delay to ensure modal is open
        setTimeout(() => {
            const paymentMethodSelect = document.getElementById('paymentMethod');
            if (paymentMethodSelect) {
                const methodMap = {
                    'card': 'credit',
                    'upi': 'upi',
                    'cash': 'cash',
                    'wallet': 'wallet'
                };
                paymentMethodSelect.value = methodMap[selectedPaymentMethod] || selectedPaymentMethod;
                
                // Trigger change event to show/hide conditional fields
                const event = new Event('change');
                paymentMethodSelect.dispatchEvent(event);
            }
        }, 100);
    } else {
        alert('Payment modal not loaded');
    }
}
</script>
</body>
</html>