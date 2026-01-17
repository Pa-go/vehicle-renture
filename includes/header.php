<!-- HEADER START -->
<div class="header">
    <div class="left-section">
        <img id="siteLogo" src="images/logo.png" class="logo" alt="Renture logo">
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

        <a id="loginBtn" class="login-btn" href="log_reg.html">Login</a>
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
    <a href="index.php" data-i18n="nav.home">Home</a>
    <a href="lender.php" data-i18n="nav.lender">Lender</a>
    <a href="tenant.php" data-i18n="nav.tenant">Tenant</a>
    <a href="contact-feedback.php" data-i18n="nav.feedback">Feedback</a>
    <a href="log_reg.php" data-i18n="nav.login">Login</a>
</div>
<!-- HEADER END -->
