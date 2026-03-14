<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout | Renture</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 50px; }
        .pay-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 450px; position: relative; }
        h2 { color: #1e2a4a; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .summary { background: #eef2f3; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .pay-btn { width: 100%; padding: 15px; background: #1e2a4a; color: white; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; font-weight: bold; }
        .pay-btn:hover { background: #ffd700; color: #1e2a4a; }
        
        /* Loading Overlay */
        #overlay { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: white; border-radius: 15px; flex-direction: column; align-items: center; justify-content: center; text-align: center; z-index: 10; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #1e2a4a; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<?php
    $v_id = $_GET['id'] ?? 0;
    $v_price = $_GET['price'] ?? 0;
?>

<div class="pay-container">
    <div id="overlay">
        <div class="spinner"></div>
        <p id="statusText" style="margin-top: 15px; font-weight: bold;">Contacting Bank...</p>
    </div>

    <h2>Secure Payment</h2>
    <div class="summary">
        <strong>Total Amount:</strong> <span style="float:right">₹ <?php echo number_format($v_price); ?></span>
    </div>

    <form id="demoPaymentForm">
        <input type="hidden" id="v_id" value="<?php echo $v_id; ?>">
        <input type="hidden" id="v_amount" value="<?php echo $v_price; ?>">

        <div class="form-group">
            <label>Payment Method</label>
            <select id="method" onchange="toggleFields()">
                <option value="Card">Credit/Debit Card</option>
                <option value="UPI">UPI</option>
                <option value="Cash">Cash on Delivery</option>
            </select>
        </div>

        <div id="cardSection">
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
            </div>
            <div style="display:flex; gap:10px">
                <input type="text" placeholder="MM/YY" style="width:50%">
                <input type="password" placeholder="CVV" style="width:50%">
            </div>
        </div>

        <div id="upiSection" style="display:none">
            <div class="form-group">
                <label>UPI ID</label>
                <input type="text" placeholder="username@upi">
            </div>
        </div>

        <p style="font-size: 12px; color: #777; margin: 20px 0;">* This is a secure demo environment for college project evaluation.</p>
        
        <button type="button" class="pay-btn" onclick="startDemoPayment()">Pay Now</button>
    </form>
</div>

<script>
function toggleFields() {
    const method = document.getElementById('method').value;
    document.getElementById('cardSection').style.display = (method === 'Card') ? 'block' : 'none';
    document.getElementById('upiSection').style.display = (method === 'UPI') ? 'block' : 'none';
}

function startDemoPayment() {
    const overlay = document.getElementById('overlay');
    const statusText = document.getElementById('statusText');
    overlay.style.display = 'flex';

    // Step 1: Simulate Bank Contact
    setTimeout(() => {
        statusText.innerText = "Verifying Details...";
        
        // Step 2: Randomly Accept or Reject (80% Success rate for demo)
        setTimeout(() => {
            const isSuccess = Math.random() > 0.2; // Demo Logic

            if (isSuccess) {
                statusText.innerHTML = "<span style='color:green'>✔ Payment Accepted!</span><br>Updating Database...";
                saveToDatabase();
            } else {
                statusText.innerHTML = "<span style='color:red'>✖ Payment Rejected!</span><br>Insufficient Funds.";
                setTimeout(() => overlay.style.display = 'none', 2000);
            }
        }, 2000);
    }, 1500);
}

function saveToDatabase() {
    const formData = new FormData();
    // Make sure these IDs (v_id, v_amount, method) exist in your HTML
    formData.append('vehicle_id', document.getElementById('v_id').value);
    formData.append('amount', document.getElementById('v_amount').value);
    formData.append('method', document.getElementById('method').value);

    fetch('process_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text()) // Get raw text first to catch PHP errors
    .then(text => {
        try {
            const data = JSON.parse(text);
            if(data.status === 'success') {
                alert("🎉 Booking Confirmed!");
                window.location.href = "tenant.php";
            } else {
                alert("Database Error: " + data.message);
            }
        } catch (err) {
            console.error("Server sent back HTML instead of JSON:", text);
            alert("Critical Server Error. Check console.");
        }
    })
    .catch(err => alert("Connection Error: " + err.message));
}
</script>
</body>
</html>