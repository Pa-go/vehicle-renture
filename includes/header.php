<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : "";
?>

<style>
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
.header-spacer { 
    height: 110px; 
}

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
        <img src="/Renture1/assets/images/logo.png" style="height:70px;" alt="Logo">
        <div class="search-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="color:rgba(255,255,255,0.5); margin-right: 8px;">
                <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input id="searchInput" type="text" placeholder="Search vehicles...">
        </div>
    </div>
    <div class="right-nav">
        <?php if ($isLoggedIn): ?>
            <a class="login-btn" href="profile.php">HI, <?php echo strtoupper(htmlspecialchars(explode(' ', $userName)[0])); ?></a>
        <?php else: ?>
            <a class="login-btn" href="/Renture1/pages/log_reg.php">LOGIN</a>
        <?php endif; ?>
    </div>
</div>

<div class="menu-bar">
    <button id="hamburgerBtn" onclick="forceOpenSidebar(event)" style="background:none; border:none; cursor:pointer;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><rect y="4" width="22" height="2" fill="#1E293B"/><rect y="11" width="22" height="2" fill="#1E293B"/><rect y="18" width="22" height="2" fill="#1E293B"/></svg>
    </button>
</div>

<div id="mySidebar" class="sidebar">
    <div style="padding:20px; text-align:right;"><button onclick="closeSidebar()" style="background:none; border:none; color:#fff; font-size:30px; cursor:pointer;">&times;</button></div>
    <a href="home.php">Home</a>
    <a href="lender.php">Lender</a>
    <a href="tenant.php">Tenant</a>
    <a href="contact-feedback.php">Feedback</a>
    <?php if ($isLoggedIn): ?>
        <a href="/Renture1/pages/logout.php" class="yellow-link">Logout</a>
    <?php else: ?>
        <a href="/Renture1/pages/log_reg.php" class="yellow-link">Login / Register</a>
    <?php endif; ?>
</div>

<div class="header-spacer"></div>

<script>
function closeSidebar() { document.getElementById('mySidebar').classList.remove('open'); }
function forceOpenSidebar(e) { e.stopPropagation(); document.getElementById('mySidebar').classList.add('open'); }

document.addEventListener('click', function(e) {
    var side = document.getElementById('mySidebar');
    if (side.classList.contains('open') && !side.contains(e.target) && !document.getElementById('hamburgerBtn').contains(e.target)) {
        closeSidebar();
    }
});

// Live Search Logic
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase().trim();
    let cards = document.querySelectorAll('.card'); 
    let gridContainer = document.querySelector('.grid') || document.querySelector('.listings-container');

    cards.forEach(card => {
        if (card.classList.contains('add-vehicle-card')) return;

        let vehicleName = card.querySelector('h4') ? card.querySelector('h4').innerText.toLowerCase() : "";
        let vehicleInfo = card.querySelector('small') ? card.querySelector('small').innerText.toLowerCase() : "";
        
        let combinedText = vehicleName + " " + vehicleInfo;

        if (combinedText.includes(filter)) {
            card.style.display = ""; 
            card.style.opacity = "1";
        } else {
            card.style.display = "none"; 
        }
    });

    // Handle "No Results" message safely
    let visibleCards = Array.from(cards).filter(c => c.style.display !== "none" && !c.classList.contains('add-vehicle-card'));
    let noResultsMsg = document.getElementById('no-results-alert');
    
    if (visibleCards.length === 0 && filter !== "" && gridContainer) {
        if (!noResultsMsg) {
            const msg = document.createElement('div');
            msg.id = 'no-results-alert';
            msg.innerHTML = `<p style="text-align:center; padding:50px; color:#64748B; width:100%;">No vehicles match "${filter}"</p>`;
            gridContainer.appendChild(msg);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
});
</script>