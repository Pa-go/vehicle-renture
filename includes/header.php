<?php
$BASE_URL="/vehicle-renture";
?>
<!-- HEADER START -->
<style>
/* Inline fallback for header layout if main stylesheet fails to load */
.header{display:flex;align-items:center;justify-content:space-between;color:#fff;padding:6px 12px;position:fixed;top:0;left:0;width:100%;background:#001F3F;height:60px;z-index:2000}
.left-section{display:flex;align-items:center;gap:12px}
.logo{height:60px}
.search-box{display:flex;align-items:center;background:#193857;border-radius:20px;padding:6px 12px;height:38px}
.search-box input{border:none;background:transparent;color:#fff;outline:none}
.right-section{display:flex;align-items:center;gap:12px;margin-right:18px}
.menu-bar{position:fixed;top:60px;width:100%;height:50px;background:#fff;z-index:3000;display:flex;align-items:center;padding-left:12px}
.sidebar{height:100vh;width:220px;position:fixed;top:110px;left:-220px;background:#001F3F;color:#fff;transition:left .28s}
.sidebar.open{left:0}
.main-content{margin-top:110px;padding:16px}
</style>
<div class="header">
    <div class="left-section">
        <img id="siteLogo" src="/Renture1/assets/images/logo.png" class="logo" alt="Renture logo">
        <div class="search-box">
            <input id="searchInput" type="text" placeholder="Search..." data-i18n-placeholder="search.placeholder">
        </div>
    </div>

    <div class="right-section">
        <button id="languageIcon" class="icon" aria-haspopup="true" aria-expanded="false" aria-controls="langMenu" title="Change language">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
                      stroke="#FFFFFF" stroke-width="1.2" />
                <path d="M2 12h20M12 2c2.5 2.5 4 6 4 10s-1.5 7.5-4 10M12 2C9.5 4.5 8 8 8 12s1.5 7.5 4 10"
                      stroke="#FFFFFF" stroke-width="0.9" opacity="0.9"/>
            </svg>
        </button>

        <div id="langMenu">
            <div role="button" onclick="selectLang('EN')" data-lang="EN">English</div>
            <div role="button" onclick="selectLang('HI')" data-lang="HI">Hindi</div>
            <div role="button" onclick="selectLang('MR')" data-lang="MR">Marathi</div>
        </div>

        <a id="loginBtn" class="login-btn" href="/Renture1/pages/log_reg.php">Login</a>
    </div>
</div>

<!-- HAMBURGER MENU BAR -->
<div class="menu-bar">
    <button type="button" id="menuToggle" class="menu-icon" aria-label="Toggle menu">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
            <rect y="4" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="11" width="24" height="2" rx="1" fill="#001F3F"/>
            <rect y="18" width="24" height="2" rx="1" fill="#001F3F"/>
        </svg>
    </button>
</div>

<!-- SIDEBAR -->
<div id="mySidebar" class="sidebar">
    <a href="home.php">Home</a>
    <a href="lender.php">Lender</a>
    <a href="tenant.php">Tenant</a>
    <a href="contact-feedback.php">Feedback</a>
    <a href="/vehicle-renture/pages/login-register.php">Login</a>
</div>
<!-- HEADER END -->
