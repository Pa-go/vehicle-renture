<?php
session_start();

$contact_submitted = false;
$feedback_submitted = false;

if (isset($_POST['contact_submit'])) {
    $contact_name = htmlspecialchars($_POST['contact_name']);
    $contact_email = htmlspecialchars($_POST['contact_email']);
    $contact_phone = htmlspecialchars($_POST['contact_phone']);
    $contact_msg = htmlspecialchars($_POST['contact_msg']);
    $contact_submitted = true;
}

if (isset($_POST['feedback_submit'])) {
    $fb_name = htmlspecialchars($_POST['fb_name']);
    $fb_rating = htmlspecialchars($_POST['fb_rating']);
    $fb_message = htmlspecialchars($_POST['fb_message']);
    $feedback_submitted = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contact & Feedback | Renture</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

/* ===== Sticky Footer ===== */

html, body {
    height: 100%;
    margin: 0;
}

body {
    background: #F8FAFC;
    color: #475569;
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow-x: hidden;
}


/* ===== Decorative blobs ===== */

body::before {
    content: "";
    position: absolute;
    top: -10%;
    left: -10%;
    width: 40%;
    height: 40%;
    background: rgba(226,232,240,0.3);
    border-radius: 50%;
    z-index: -1;
    filter: blur(80px);
}

body::after {
    content: "";
    position: absolute;
    bottom: -10%;
    right: -10%;
    width: 40%;
    height: 40%;
    background: rgba(226,232,240,0.2);
    border-radius: 50%;
    z-index: -1;
    filter: blur(80px);
}


/* ===== Layout ===== */

.main-content {
    flex: 1 0 auto;
}

.page-wrapper {
    max-width: 700px;
    margin: 0 auto;
    padding: 90px 20px 60px;   /* GAP FROM HEADER */
}


/* ===== Box ===== */

.box {
    background: rgba(226, 232, 240, 0.75);
    padding: 30px 35px;
    border-radius: 12px;
    margin-bottom: 30px;
    border: 1px solid rgba(248, 250, 252, 0.6);
    box-shadow: 0 10px 25px rgba(30, 41, 59, 0.25);
    backdrop-filter: blur(10px);
}

.box h2 {
    color: #1E293B;
    margin-bottom: 20px;
    font-size: 24px;
    border-bottom: 1px solid #94A3B8;
    padding-bottom: 10px;
}


/* ===== Form ===== */

.box label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #1E293B;
}

.box input,
.box textarea,
.box select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid rgba(226,232,240,0.9);
    border-radius: 8px;
    margin-bottom: 20px;
    background: rgba(248,250,252,0.75);
}

.box button {
    background: #475569;
    color: #F8FAFC;
    border: none;
    padding: 14px;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
}

.box button:hover {
    background: #1E293B;
}


/* ===== Success ===== */

.success {
    background: #dcfce7;
    color: #166534;
    padding: 14px;
    margin-bottom: 20px;
    border-radius: 8px;
    text-align: center;
}


/* ===== Footer ===== */

footer {
    flex-shrink: 0;
}

</style>
</head>

<body>

<div id="messageBox"
style="display:none;position:fixed;top:10px;right:10px;background:#1E293B;color:#F8FAFC;padding:8px 12px;border-radius:6px;z-index:10000;">
</div>

<?php include '../includes/header.php'; ?>


<div class="main-content">
<div class="page-wrapper">


<div class="box">

<h2>📬 Contact Us</h2>

<?php if ($contact_submitted): ?>
<div class="success">📩 Your message has been sent successfully!</div>
<?php endif; ?>

<form method="POST">

<label>Your Name</label>
<input type="text" name="contact_name" required>

<label>Email Address</label>
<input type="email" name="contact_email" required>

<label>Phone Number</label>
<input type="text" name="contact_phone" required>

<label>Your Message</label>
<textarea name="contact_msg" rows="4" required></textarea>

<button type="submit" name="contact_submit">
Send Message
</button>

</form>

</div>



<div class="box">

<h2>⭐ Feedback</h2>

<?php if ($feedback_submitted): ?>
<div class="success">⭐ Thank you for your feedback!</div>
<?php endif; ?>

<form method="POST">

<label>Your Name</label>
<input type="text" name="fb_name" required>

<label>Rating</label>

<select name="fb_rating" required>
<option disabled selected></option>
<option>⭐⭐⭐⭐⭐ Excellent</option>
<option>⭐⭐⭐⭐ Good</option>
<option>⭐⭐⭐ Average</option>
<option>⭐⭐ Poor</option>
<option>⭐ Very Poor</option>
</select>

<label>Your Feedback</label>
<textarea name="fb_message" rows="4" required></textarea>

<button type="submit" name="feedback_submit">
Submit Feedback
</button>

</form>

</div>


</div>
</div>


<?php include '../includes/footer.php'; ?>

<script src="../assets/js/script.js"></script>

</body>
</html>