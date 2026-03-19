<?php
// 1. Session Guard - Absolute Top Line
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. AJAX POST Handler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (ob_get_length()) ob_clean(); 
    header('Content-Type: application/json');
    include '../database/db_config.php';

    $v_id = $_POST['vehicle_id'] ?? 0;
    $amount = $_POST['amount'] ?? 0;
    $user_id = $_SESSION['user_id'] ?? 1; 
    $method = $_POST['method'] ?? 'Card';

    // Step 1: Update vehicle status
    $sql_update = "UPDATE vehicles SET status = 'Sold' WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $v_id);
    
    // Step 2: Create booking record
    $sql_book = "INSERT INTO bookings (tenant_id, vehicle_id, amount, payment_method) VALUES (?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql_book);
    $stmt2->bind_param("iids", $user_id, $v_id, $amount, $method);

    if ($stmt->execute() && $stmt2->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    exit; 
}
?>

<div id="paymentModal" class="popup-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:10001; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:15px; width:400px; position:relative; font-family: 'Poppins', sans-serif;">
        <span onclick="document.getElementById('paymentModal').style.display='none'" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:24px;">&times;</span>
        
        <h3 style="color:#001F3F; margin-bottom:20px;">🔒 Secure Payment</h3>
        
        <div style="background:#f4f7f9; padding:15px; border-radius:10px; margin-bottom:20px;">
            <p style="margin:0; color:#555;">Total Amount:</p>
            <h2 style="margin:0; color:#001F3F;">₹<span id="modalTotal">0</span></h2>
        </div>

        <label style="display:block; margin-bottom:5px; font-weight:600;">Payment Method</label>
        <select id="modalPaymentMethod" onchange="updatePaymentFields()" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; margin-bottom:20px;">
            <option value="Card">Credit/Debit Card</option>
            <option value="UPI">UPI</option>
            <option value="Cash">Cash</option>
        </select>

        <div id="cardDetails" style="display:none; margin-bottom:15px;">
            <input type="text" placeholder="Card Number (16 digits)" maxlength="16" style="width:100%; padding:10px; margin-bottom:10px; border-radius:8px; border:1px solid #ccc; box-sizing: border-box;">
            <div style="display:flex; gap:10px;">
                <input type="text" placeholder="MM/YY" maxlength="5" style="flex:1; padding:10px; border-radius:8px; border:1px solid #ccc;">
                <input type="password" placeholder="CVV" maxlength="3" style="flex:1; padding:10px; border-radius:8px; border:1px solid #ccc;">
            </div>
        </div>

        <div id="upiDetails" style="display:none; margin-bottom:15px;">
            <input type="text" placeholder="Enter UPI ID (e.g., name@okaxis)" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <button id="modalPayBtn" onclick="confirmPayment()" style="width:100%; background:#FFD700; color:#001F3F; border:none; padding:12px; border-radius:10px; font-weight:bold; cursor:pointer; transition: 0.3s;">
            Confirm Payment
        </button>
    </div>
</div>

<script>
function updatePaymentFields() {
    const method = document.getElementById('modalPaymentMethod').value;
    const cardBox = document.getElementById('cardDetails');
    const upiBox = document.getElementById('upiDetails');

    cardBox.style.display = (method === 'Card') ? 'block' : 'none';
    upiBox.style.display = (method === 'UPI') ? 'block' : 'none';
}

function confirmPayment() {
    const v_id = urlParams.get('id'); 
    const finalAmount = window.bookingTotal;
    const method = document.getElementById('modalPaymentMethod').value;
    const btn = document.getElementById('modalPayBtn');

    if (!v_id) {
        alert("Vehicle selection error.");
        return;
    }

    btn.innerText = "Processing...";
    btn.disabled = true;

    const formData = new FormData();
    formData.append('vehicle_id', v_id);
    formData.append('amount', finalAmount);
    formData.append('method', method);

    // FIXED: Adjusted path to ensure it finds itself correctly during inclusion
    fetch('payment-modal.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert("✅ Booking Confirmed!");
            window.location.href = 'tenant.php';
        } else {
            alert("❌ Database Error: " + data.message);
            btn.innerText = "Confirm Payment";
            btn.disabled = false;
        }
    })
    .catch(err => {
        console.error("Payment Process Error:", err);
        alert("Failed to connect to the server.");
        btn.innerText = "Confirm Payment";
        btn.disabled = false;
    });
}
</script>