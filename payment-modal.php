<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal-overlay">
    <div class="payment-modal">
        <div class="payment-modal-header">
            <h2>💳 Payment</h2>
            <button class="payment-modal-close" onclick="closePaymentModal()">&times;</button>
        </div>
        
        <div class="payment-amount">
            <div class="payment-amount-label">Total Amount</div>
            <div class="payment-amount-value" id="paymentAmount">$0.00</div>
        </div>
        
        <form id="paymentForm" onsubmit="handlePayment(event)">
            <div class="payment-form-group">
                <label for="cardName">Cardholder Name</label>
                <input type="text" id="cardName" name="cardName" placeholder="John Doe" required>
            </div>
            
            <div class="payment-form-group">
                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
            </div>
            
            <div class="payment-form-row">
                <div class="payment-form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="payment-form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>
            
            <div class="payment-form-group">
                <label for="paymentMethod">Payment Method</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option value="">Select Payment Method</option>
                    <option value="credit">Credit Card</option>
                    <option value="debit">Debit Card</option>
                    <option value="upi">UPI</option>
                    <option value="cash">Cash</option>
                    <option value="wallet">Wallet</option>
                    <option value="netbanking">Net Banking</option>
                </select>
            </div>
            
            <!-- Conditional fields based on payment method -->
            <div id="upiFields" class="payment-conditional-fields" style="display: none;">
                <div class="payment-form-group">
                    <label for="upiId">UPI ID</label>
                    <input type="text" id="upiId" name="upiId" placeholder="yourname@upi">
                </div>
            </div>
            
            <div id="walletFields" class="payment-conditional-fields" style="display: none;">
                <div class="payment-form-group">
                    <label for="walletType">Wallet Type</label>
                    <select id="walletType" name="walletType">
                        <option value="">Select Wallet</option>
                        <option value="paytm">Paytm</option>
                        <option value="phonepe">PhonePe</option>
                        <option value="gpay">Google Pay</option>
                        <option value="amazonpay">Amazon Pay</option>
                    </select>
                </div>
            </div>
            
            <div id="cashFields" class="payment-conditional-fields" style="display: none;">
                <div class="payment-form-group">
                    <label>Cash Payment Note</label>
                    <p style="color: #666; font-size: 14px; margin: 0;">Payment will be collected upon vehicle delivery.</p>
                </div>
            </div>
            
            <div class="payment-form-group">
                <label for="billingAddress">Billing Address</label>
                <input type="text" id="billingAddress" name="billingAddress" placeholder="Street Address" required>
            </div>
            
            <div class="payment-form-row">
                <div class="payment-form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="City" required>
                </div>
                <div class="payment-form-group">
                    <label for="zipCode">ZIP Code</label>
                    <input type="text" id="zipCode" name="zipCode" placeholder="12345" required>
                </div>
            </div>
            
            <button type="submit" class="payment-submit-btn">Pay Now</button>
        </form>
    </div>
</div>

<script>
// Format card number input
document.addEventListener('DOMContentLoaded', function() {
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }
    
    // Format expiry date input
    const expiryDate = document.getElementById('expiryDate');
    if (expiryDate) {
        expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
    
    // Only allow numbers for CVV
    const cvv = document.getElementById('cvv');
    if (cvv) {
        cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
    
    // Show/hide conditional fields based on payment method
    const paymentMethod = document.getElementById('paymentMethod');
    if (paymentMethod) {
        paymentMethod.addEventListener('change', function() {
            const method = this.value;
            const upiFields = document.getElementById('upiFields');
            const walletFields = document.getElementById('walletFields');
            const cashFields = document.getElementById('cashFields');
            const cardFields = document.querySelectorAll('#cardName, #cardNumber, #expiryDate, #cvv');
            
            // Hide all conditional fields
            if (upiFields) upiFields.style.display = 'none';
            if (walletFields) walletFields.style.display = 'none';
            if (cashFields) cashFields.style.display = 'none';
            
            // Show relevant fields
            if (method === 'upi' && upiFields) {
                upiFields.style.display = 'block';
                cardFields.forEach(field => {
                    const group = field.closest('.payment-form-group');
                    if (group) group.style.display = 'none';
                });
            } else if (method === 'wallet' && walletFields) {
                walletFields.style.display = 'block';
                cardFields.forEach(field => {
                    const group = field.closest('.payment-form-group');
                    if (group) group.style.display = 'none';
                });
            } else if (method === 'cash' && cashFields) {
                cashFields.style.display = 'block';
                cardFields.forEach(field => {
                    const group = field.closest('.payment-form-group');
                    if (group) group.style.display = 'none';
                });
            } else {
                // Show card fields for credit/debit
                cardFields.forEach(field => {
                    const group = field.closest('.payment-form-group');
                    if (group) group.style.display = 'block';
                });
            }
        });
    }
});

// Open payment modal
function openPaymentModal(amount = 0) {
    const modal = document.getElementById('paymentModal');
    const amountElement = document.getElementById('paymentAmount');
    if (modal) {
        modal.classList.add('active');
        if (amountElement && amount > 0) {
            amountElement.textContent = '$' + amount.toFixed(2);
        }
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

// Close payment modal
function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
        // Reset form
        const form = document.getElementById('paymentForm');
        if (form) form.reset();
    }
}

// Handle payment form submission
function handlePayment(event) {
    event.preventDefault();
    
    // Get form data
    const formData = {
        cardName: document.getElementById('cardName').value,
        cardNumber: document.getElementById('cardNumber').value,
        expiryDate: document.getElementById('expiryDate').value,
        cvv: document.getElementById('cvv').value,
        paymentMethod: document.getElementById('paymentMethod').value,
        billingAddress: document.getElementById('billingAddress').value,
        city: document.getElementById('city').value,
        zipCode: document.getElementById('zipCode').value,
        amount: document.getElementById('paymentAmount').textContent
    };
    
    // Here you would typically send this data to your server
    console.log('Payment Data:', formData);
    
    // Show success message
    if (typeof showMessage === 'function') {
        showMessage('Payment processed successfully!');
    } else {
        alert('Payment processed successfully!');
    }
    
    // Close modal after a short delay
    setTimeout(() => {
        closePaymentModal();
    }, 1500);
    
    // In a real application, you would:
    // 1. Send formData to your server via AJAX/fetch
    // 2. Process the payment through a payment gateway
    // 3. Handle success/error responses
    // 4. Update the UI accordingly
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('paymentModal');
    if (modal && e.target === modal) {
        closePaymentModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('paymentModal');
        if (modal && modal.classList.contains('active')) {
            closePaymentModal();
        }
    }
});
</script>
