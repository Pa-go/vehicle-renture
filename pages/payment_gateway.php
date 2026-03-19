<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout | Renture</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 50px; }
        .pay-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 450px; position: relative; }
        h2 { color: #1e2a4a; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0; }
        .summary { background: #eef2f3; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        .pay-btn { width: 100%; padding: 15px; background: #1e2a4a; color: white; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .pay-btn:hover { background: #ffd700; color: #1e2a4a; }
        
        #overlay { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: white; border-radius: 15px; flex-direction: column; align-items: center; justify-content: center; text-align: center; z-index: 10; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #1e2a4a; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<?php
    // Fetch data from URL parameters
    $v_id = $_GET['id'] ?? 0;
    $v_price = $_GET['price'] ?? 0;
?>

<div class="pay-container">
    <div id="overlay">
        <div class="spinner"></div>
        <p id="statusText" style="margin-top: 15px; font-weight: bold;">Contacting Bank...</p>
    </div>

    <h2>🔒 Secure Payment</h2>
    <div class="summary">
        <strong>Total Amount:</strong> <span style="float:right">₹ <?php echo number_format($v_price); ?></span>
    </div>

    <form id="demoPaymentForm">
        <input type="hidden" id="v_id" value="<?php echo $v_id; ?>">
        <input type="hidden" id="v_amount" value="<?php echo $v_price; ?>">

        <div class="form-group">
            <label>Select Payment Method</label>
            <select id="method" onchange="toggleFields()">
                <option value="Card">Credit/Debit Card</option>
                <option value="UPI">UPI</option>
                <option value="Cash">Cash on Delivery</option>
            </select>
        </div>

        <div id="cardSection">
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" id="cardNumber" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
            </div>
            <div style="display:flex; gap:10px">
                <div style="width:50%"><label>Expiry</label><input type="text" placeholder="MM/YY" maxlength="5"></div>
                <div style="width:50%"><label>CVV</label><input type="password" placeholder="***" maxlength="3"></div>
            </div>
        </div>

        <div id="upiSection" style="display:none">
            <div class="form-group">
                <label>UPI ID</label>
                <input type="text" placeholder="username@upi">
            </div>
        </div>

        <p style="font-size: 11px; color: #888; margin: 20px 0; line-height: 1.4;">
            * This is a sandbox environment. No actual money will be debited from your account.
        </p>
        
        <button type="button" class="pay-btn" id="payBtn" onclick="startDemoPayment()">
            Confirm & Pay ₹ <?php echo number_format($v_price); ?>
        </button>
    </form>
</div>

<script> 
// Card number formatting (adds space every 4 digits)
document.getElementById('cardNumber').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();
});

function toggleFields() {
    const method = document.getElementById('method').value;
    document.getElementById('cardSection').style.display = (method === 'Card') ? 'block' : 'none';
    document.getElementById('upiSection').style.display = (method === 'UPI') ? 'block' : 'none';
}
 
function startDemoPayment() {
    const method = document.getElementById('method').value;
    
    // Immediate Success for Cash on Delivery
    if(method === "Cash") {
        alert("Booking request received! Pay on arrival.");
        saveToDatabase();
        return;
    }

    document.getElementById("payBtn").innerText = "Processing...";
    document.getElementById("payBtn").disabled = true;
    const overlay = document.getElementById('overlay');
    const statusText = document.getElementById('statusText');
    overlay.style.display = 'flex';

    setTimeout(() => {
        statusText.innerText = "Verifying Transaction...";
        
        setTimeout(() => {
            // Using your 80% Success Logic
            const isSuccess = Math.random() > 0.2; 

            if (isSuccess) {
                statusText.innerHTML = "<span style='color:green; font-size:24px;'>✔</span><br>Payment Accepted!<br><small>Updating Records...</small>";
                saveToDatabase();
            } else {
                statusText.innerHTML = "<span style='color:red; font-size:24px;'>✖</span><br>Payment Rejected!<br><small>Bank server timed out.</small>";
                setTimeout(() => {
                    overlay.style.display = 'none';
                    document.getElementById("payBtn").innerText = "Retry Payment";
                    document.getElementById("payBtn").disabled = false;
                }, 2500);
            }
        }, 2000);
    }, 1500);
}

function saveToDatabase() {
    const formData = new FormData();
    formData.append('vehicle_id', document.getElementById('v_id').value);
    formData.append('amount', document.getElementById('v_amount').value);
    formData.append('method', document.getElementById('method').value);

    // Using your process_booking.php endpoint
    fetch('process_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = "tenant.php?status=success";
        } else {
            alert("Error: " + data.message);
            document.getElementById('overlay').style.display = 'none';
        }
    })
    .catch(err => {
        console.error("Save failed:", err);
        // Fallback for demo if process_booking doesn't exist yet
        alert("Demo Mode: Database update simulated.");
        window.location.href = "tenant.php";
    });
}
</script>
</body>
</html>