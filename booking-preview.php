<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Preview | Renture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Temporary Message Box -->
<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include 'header.php'; ?>

<div id="mainContent" class="main-content">

    <div class="booking-preview-container">
        <h2 class="booking-title">📋 Booking Preview</h2>
        
        <!-- Selected Vehicle Details -->
        <div class="booking-section">
            <h3>🚗 Selected Vehicle</h3>
            <div class="vehicle-preview-card" id="vehiclePreview">
                <div class="vehicle-preview-image">
                    <span class="image-placeholder">[Vehicle Image]</span>
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

        <!-- Booking Summary -->
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

        <!-- Payment Method Selection -->
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

        <!-- Action Buttons -->
        <div class="booking-actions">
            <button class="btn-secondary" onclick="window.history.back()">← Back</button>
            <button class="btn-primary" id="proceedToPaymentBtn" onclick="proceedToPayment()" disabled>Proceed to Payment</button>
        </div>
    </div>

</div>

<?php include 'payment-modal.php'; ?>
<?php include 'footer.php'; ?>
<script src="script.js"></script>
<script>
// Get vehicle details from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const vehicleName = urlParams.get('name') || 'Toyota Camry Hybrid';
const vehiclePrice = parseFloat(urlParams.get('price')) || 40000;
const vehicleBrand = urlParams.get('brand') || 'Toyota';
const vehicleModel = urlParams.get('model') || 'Camry';
const vehicleType = urlParams.get('type') || 'Car';

// Update page with vehicle details
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('vehicleName').textContent = vehicleName;
    document.getElementById('vehiclePrice').textContent = '$' + vehiclePrice.toLocaleString();
    document.getElementById('basePrice').textContent = '$' + vehiclePrice.toLocaleString();
    document.getElementById('vehicleBrand').textContent = 'Brand: ' + vehicleBrand;
    document.getElementById('vehicleModel').textContent = 'Model: ' + vehicleModel;
    document.getElementById('vehicleType').textContent = 'Type: ' + vehicleType;

    // Calculate totals
    const tax = vehiclePrice * 0.10;
    const serviceFee = 500;
    const total = vehiclePrice + tax + serviceFee;

    document.getElementById('taxAmount').textContent = '$' + tax.toLocaleString();
    document.getElementById('serviceFee').textContent = '$' + serviceFee.toLocaleString();
    document.getElementById('totalAmount').innerHTML = '<strong>$' + total.toLocaleString() + '</strong>';

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
        openPaymentModal(window.bookingTotal || 44500);
        
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
