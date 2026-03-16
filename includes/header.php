<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : "";
?>
<style>
/* 1. LAYOUT & LAYERING */
.header {
    display:flex; align-items:center; justify-content:space-between; color:#fff; padding:6px 12px; 
    position:fixed; top:0; left:0; width:100%; background:#001F3F; height:60px; z-index:2000;
}
.left-section, .right-section { display:flex; align-items:center; gap:12px; }
.logo { height:50px; }

.search-box {
    display:flex; align-items:center; background:#193857; border-radius:20px; padding:6px 12px; height:38px;
}
.search-box input { border:none; background:transparent; color:#fff; outline:none; }

.menu-bar {
    position:fixed; top:60px; width:100%; height:50px; background:#fff; 
    z-index:1500; display:flex; align-items:center; padding-left:12px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* 2. SIDEBAR STYLING */
.sidebar {
    position: fixed !important; top: 0 !important; left: -220px; 
    width: 220px !important; height: 100vh !important; background: #001F3F !important;
    z-index: 9999 !important; transition: left 0.3s ease; display: block !important;
}
.sidebar.open { left:0 !important; }
.sidebar a { display:block; color:white; padding:15px 25px; text-decoration:none; border-bottom: 1px solid rgba(255,255,255,0.1); }
.sidebar a:hover { background: #193857; }

/* 3. BUTTONS & UI */
.login-btn { background:#FFD700; color:#001F3F; padding:8px 15px; border-radius:20px; text-decoration:none; font-weight:bold; }
.icon { background:none; border:none; cursor:pointer; display:flex; align-items:center; padding: 5px; }

/* Language Menu Styling */
#langMenu {
    display: none; 
    position: absolute; 
    top: 55px; 
    right: 15px; 
    background: white; 
    color: #333; 
    border-radius: 8px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    z-index: 5000; 
    min-width: 140px;
    border: 1px solid #ddd;
}
#langMenu div { padding: 12px 20px; cursor: pointer; border-bottom: 1px solid #f0f0f0; }
#langMenu div:last-child { border-bottom: none; }
#langMenu div:hover { background: #f0f2f5; }
</style>

<div class="header">
    <div class="left-section">
        <img src="../assets/images/logo.png" class="logo" alt="Renture logo">
        <div class="search-box">
            <input id="searchInput" type="text" placeholder="Search...">
        </div>
    </div>

    <div class="right-section">
        <button id="languageIcon" class="icon" onclick="toggleLangMenu(event)">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" stroke="#FFFFFF" stroke-width="1.2" />
                <path d="M2 12h20M12 2c2.5 2.5 4 6 4 10s-1.5 7.5-4 10M12 2C9.5 4.5 8 8 8 12s1.5 7.5 4 10" stroke="#FFFFFF" stroke-width="0.9" opacity="0.9"/>
            </svg>
        </button>

        <div id="langMenu">
            <div onclick="selectLang('EN')">English</div>
            <div onclick="selectLang('HI')">Hindi</div>
            <div onclick="selectLang('MR')">Marathi</div>
        </div>

        <?php if ($isLoggedIn): ?>
            <a class="login-btn" href="profile.php">Hi, <?php echo htmlspecialchars(explode(' ', $userName)[0]); ?></a>
        <?php else: ?>
            <a class="login-btn" href="../pages/log_reg.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="menu-bar">
    <button type="button" onclick="toggleSidebar()" style="background:none; border:none; cursor:pointer;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
            <rect y="4" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="11" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="18" width="24" height="2" rx="1" fill="#001F3F"/>
        </svg>
    </button>
</div>

<div id="mySidebar" class="sidebar">
    <div style="text-align:right; padding:10px;">
        <button onclick="toggleSidebar()" style="color:white; background:none; border:none; font-size:30px; cursor:pointer;">&times;</button>
    </div>
    <a href="home.php">Home</a>
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
function toggleSidebar() {
    const sidebar = document.getElementById('mySidebar');
    sidebar.classList.toggle('open');
}

function toggleLangMenu(event) {
    if (event) event.stopPropagation();
    
    const menu = document.getElementById('langMenu');
    
    // Check if it's currently hidden
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        console.log("[lang] OPENING NOW");
    } else {
        menu.style.display = 'none';
        console.log("[lang] manual close");
    }
}

function selectLang(lang) {
    console.log("Selected Language:", lang);
    alert("Language changed to " + lang);
    document.getElementById('langMenu').style.display = 'none';
}

// SINGLE Unified click-outside handler
window.onclick = function(event) {
    const menu = document.getElementById('langMenu');
    const icon = document.getElementById('languageIcon');
    const sidebar = document.getElementById('mySidebar');

    // Close Lang Menu if clicking outside
    if (menu.style.display === 'block') {
        if (!icon.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = 'none';
            console.log("[lang] closed by clicking outside");
        }
    }
};
</script>