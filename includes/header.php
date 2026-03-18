<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : "";
?>

<style>
<<<<<<< HEAD
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

=======
/* Reset everything to zero so WE control the space */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* --- HEADERS --- */
.header-main {
    position: fixed; top: 0; left: 0; width: 100%; height: 65px;
    background: #1E293B; color: #F8FAFC;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 50px 0 15px; z-index: 2100;
}

.menu-bar {
    position: fixed; top: 65px; left: 0; width: 100%; height: 45px; 
    background: #ffffff; z-index: 2000;
    display: flex; align-items: center; padding-left: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

/* --- THE PRECISE GAP FIX --- */

/* This is the base spacer (Total header height) */
.header-spacer { 
    height: 110px; 
}

/* This creates a tiny, professional 10px gap instead of a huge space */
.main-content, .container, .card-section {
    margin-top: 10px !important; 
    padding-top: 0 !important;
}

/* UI Elements */
.search-box {
    display:flex; align-items:center; background:#0F172A; border: 1px solid rgba(255,255,255,0.1); 
    border-radius:20px; padding:6px 15px; width: 260px; margin-left: 15px;
}
.search-box input { border:none; background:transparent; color:#fff; outline:none; width: 100%; font-size: 13px; }
.login-btn { background:#FACC15; color:#1E293B; padding:8px 20px; border-radius:8px; text-decoration:none; font-weight:800; font-size: 12px; }

/* Sidebar */
.sidebar { position: fixed; top: 0; left: -250px; width: 250px; height: 100vh; background: #0F172A; z-index: 9999; transition: 0.3s; }
.sidebar.open { left: 0; }
.sidebar a { display: block; color: #fff; padding: 15px 25px; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.05); }
.sidebar .yellow-link { color: #FACC15; }
</style>

<div class="header-main">
    <div style="display:flex; align-items:center;">
        <img src="../assets/images/logo.png" style="height:35px;" alt="Logo">
        <div class="search-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="color:rgba(255,255,255,0.5); margin-right: 8px;">
                <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input id="searchInput" type="text" placeholder="Search...">
        </div>
    </div>
    <div class="right-nav">
>>>>>>> 30d53e410b624a5d8282c21b8c70e4f19463ffc8
        <?php if ($isLoggedIn): ?>
            <a class="login-btn" href="profile.php">HI, <?php echo strtoupper(htmlspecialchars(explode(' ', $userName)[0])); ?></a>
        <?php else: ?>
            <a class="login-btn" href="../pages/log_reg.php">LOGIN</a>
        <?php endif; ?>
    </div>
</div>

<div class="menu-bar">
<<<<<<< HEAD
    <button type="button" onclick="toggleSidebar()" style="background:none; border:none; cursor:pointer;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
            <rect y="4" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="11" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="18" width="24" height="2" rx="1" fill="#001F3F"/>
        </svg>
=======
    <button id="hamburgerBtn" onclick="forceOpenSidebar(event)" style="background:none; border:none; cursor:pointer;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><rect y="4" width="22" height="2" fill="#1E293B"/><rect y="11" width="22" height="2" fill="#1E293B"/><rect y="18" width="22" height="2" fill="#1E293B"/></svg>
>>>>>>> 30d53e410b624a5d8282c21b8c70e4f19463ffc8
    </button>
</div>

<div id="mySidebar" class="sidebar">
<<<<<<< HEAD
    <div style="text-align:right; padding:10px;">
        <button onclick="toggleSidebar()" style="color:white; background:none; border:none; font-size:30px; cursor:pointer;">&times;</button>
    </div>
=======
    <div style="padding:20px; text-align:right;"><button onclick="closeSidebar()" style="background:none; border:none; color:#fff; font-size:30px; cursor:pointer;">&times;</button></div>
>>>>>>> 30d53e410b624a5d8282c21b8c70e4f19463ffc8
    <a href="home.php">Home</a>
    <a href="lender.php">Lender</a>
    <a href="tenant.php">Tenant</a>
    <a href="contact-feedback.php">Feedback</a>
    <?php if ($isLoggedIn): ?>
        <a href="../pages/logout.php" class="yellow-link">Logout</a>
    <?php else: ?>
        <a href="../pages/log_reg.php" class="yellow-link">Login / Register</a>
    <?php endif; ?>
</div>

<<<<<<< HEAD
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
=======
<div class="header-spacer"></div>

<div class="main-content">
    </div>

<script>
function closeSidebar() { document.getElementById('mySidebar').classList.remove('open'); }
function forceOpenSidebar(e) { e.stopPropagation(); document.getElementById('mySidebar').classList.add('open'); }
document.addEventListener('click', function(e) {
    var side = document.getElementById('mySidebar');
    if (side.classList.contains('open') && !side.contains(e.target) && !document.getElementById('hamburgerBtn').contains(e.target)) {
        closeSidebar();
    }
});

// Simple search logic
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let cards = document.querySelectorAll('.car-card, .vehicle-card'); 
    cards.forEach(card => {
        let text = card.innerText.toLowerCase(); 
        card.style.display = text.includes(filter) ? "" : "none";
    });
});
>>>>>>> 30d53e410b624a5d8282c21b8c70e4f19463ffc8
</script>