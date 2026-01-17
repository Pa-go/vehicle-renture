<?php
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
    <link rel="stylesheet" href="style.css">
    <style>
        .wrapper { max-width: 900px; margin: 0 auto 40px; }
        .box { background: #fff; padding: 25px; border-radius: 10px; margin-bottom: 40px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h2 { color: #001F3F; margin-bottom: 10px; }
        label { font-weight: bold; display: block; margin: 12px 0 5px; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 15px; }
        button { background: #001F3F; color: white; border: none; padding: 12px; width: 100%; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #003366; }
        .success { background: #d4edda; color: #155724; padding: 14px; margin-bottom: 20px; border-radius: 6px; text-align: center; }
    </style>
</head>
<body>

<!-- Temporary Message Box -->
<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include 'header.php'; ?>

<div id="mainContent" class="main-content">
<div class="wrapper">

    <!-- CONTACT FORM -->
    <div class="box">
        <h2>Contact Us</h2>
        <?php if ($contact_submitted): ?>
            <div class="success">📩 Your contact request was submitted successfully!</div>
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
            <button type="submit" name="contact_submit">Send Message</button>
        </form>
    </div>

    <!-- FEEDBACK FORM -->
    <div class="box">
        <h2>Feedback</h2>
        <?php if ($feedback_submitted): ?>
            <div class="success">⭐ Thank you for your feedback!</div>
        <?php endif; ?>
        <form method="POST">
            <label>Your Name</label>
            <input type="text" name="fb_name" required>
            <label>Rating</label>
            <select name="fb_rating" required>
                <option disabled selected value="">Choose Rating</option>
                <option>⭐⭐⭐⭐⭐ Excellent</option>
                <option>⭐⭐⭐⭐ Good</option>
                <option>⭐⭐⭐ Average</option>
                <option>⭐⭐ Poor</option>
                <option>⭐ Very Poor</option>
            </select>
            <label>Your Feedback</label>
            <textarea name="fb_message" rows="4" required></textarea>
            <button type="submit" name="feedback_submit">Submit Feedback</button>
        </form>
    </div>

</div>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
