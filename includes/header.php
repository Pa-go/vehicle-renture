<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : "";
?>
<style>
/* 1. LAYERING FIX: Sidebar must be higher than everything else */
.header {
    display:flex; align-items:center; justify-content:space-between; color:#fff; padding:6px 12px; 
    position:fixed; top:0; left:0; width:100%; background:#001F3F; height:60px; z-index:2000;
}
.menu-bar {
    position:fixed; top:60px; width:100%; height:50px; background:#fff; 
    z-index:1500; /* Lowered so sidebar can go OVER it */
    display:flex; align-items:center; padding-left:12px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.sidebar {
    position: fixed !important;
    top: 0 !important;
    left: -220px; /* Hidden state */
    width: 220px !important;
    height: 100vh !important;
    background: #001F3F !important;
    z-index: 9999 !important; /* This puts it ABOVE the car image */
    transition: left 0.3s ease;
    display: block !important; /* Ensure it's not set to display:none */
}

.sidebar.open {
    left:0 !important;
}
.sidebar a { display:block; color:white; padding:15px 25px; text-decoration:none; border-bottom: 1px solid rgba(255,255,255,0.1); }
.sidebar a:hover { background: #193857; }

/* 2. Style for login/account button */
.login-btn { background:#FFD700; color:#001F3F; padding:8px 15px; border-radius:20px; text-decoration:none; font-weight:bold; }
</style>

<div class="header">
    <div class="left-section">
        <img id="siteLogo" src="../assets/images/logo.png" class="logo" alt="Renture logo" style="height:60px;">
        <div class="search-box" style="display:flex; align-items:center; background:#193857; border-radius:20px; padding:6px 12px;">
            <input id="searchInput" type="text" placeholder="Search..." style="border:none; background:transparent; color:#fff; outline:none;">
        </div>
    </div>

    <div class="right-section" style="display:flex; align-items:center; gap:12px;">
        <?php if ($isLoggedIn): ?>
            <a class="login-btn" href="profile.php">Hi, <?php echo htmlspecialchars(explode(' ', $userName)[0]); ?></a>
        <?php else: ?>
            <a class="login-btn" href="../pages/log_reg.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="menu-bar">
    <button type="button" onclick="forceOpenSidebar(event)" style="background:none; border:none; cursor:pointer; z-index: 6000;">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
        <rect y="4" width="24" height="2" rx="1" fill="#001F3F"/>
        <rect y="11" width="24" height="2" rx="1" fill="#001F3F"/>
        <rect y="18" width="24" height="2" rx="1" fill="#001F3F"/>
    </svg>
</button>
</div>

<div id="mySidebar" class="sidebar">
    <div style="text-align:right; padding:10px;"><button id="closeBtn" style="color:white; background:none; border:none; font-size:24px; cursor:pointer;">&times;</button></div>
    <a href="../home.php">Home</a>
    <a href="lender.php">Lender</a>
    <a href="tenant.php">Tenant</a>
    <a href="contact-feedback.php">Feedback</a>
    <?php if ($isLoggedIn): ?>
        <a href="../pages/logout.php" style="color:#FFD700;">Logout</a>
    <?php else: ?>
        <a href="../pages/log_reg.php">Login</a>
    <?php endif; ?>
</div>

<script>
function forceOpenSidebar(e) {
    e.preventDefault();
    e.stopPropagation(); // Stops other scripts from hearing this click
    
    var side = document.getElementById('mySidebar');
    side.style.left = "0px"; // Force position
    side.classList.add('open');
    console.log("FORCE OPEN EXECUTED");
}

// Ensure the sidebar CSS is forced
document.getElementById('mySidebar').style.zIndex = "9999";
</script>