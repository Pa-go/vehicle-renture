// Display a temporary message on the screen (replaces alert)
function showMessage(msg) {
    const messageBox = document.getElementById('messageBox');
    if (messageBox) {
        messageBox.textContent = msg;
        messageBox.style.display = 'block';
        // Remove the box after the animation (3s)
        setTimeout(() => {
            messageBox.style.display = 'none';
        }, 3000);
    }
}

console.log('[script.js] loaded');
window.addEventListener('error', function (ev) { console.error('[window error]', ev); });

document.addEventListener('DOMContentLoaded', function () {
    console.log('[script.js] DOMContentLoaded');
    
    // create a small on-page debug overlay so we can see logs without DevTools
    let debugOverlay = document.getElementById('debugOverlay');
    if (!debugOverlay) {
        debugOverlay = document.createElement('div');
        debugOverlay.id = 'debugOverlay';
        debugOverlay.style.cssText = 'position:fixed;right:10px;bottom:10px;z-index:9999;background:rgba(0,0,0,0.6);color:#fff;font-size:12px;padding:8px;max-width:320px;max-height:200px;overflow:auto;border-radius:6px;font-family:monospace;';
        document.body.appendChild(debugOverlay);
    }

    function pageLog(msg) {
        try {
            console.log(msg);
        } catch (e) {}
        if (!debugOverlay) return;
        const line = document.createElement('div');
        line.textContent = (new Date()).toLocaleTimeString() + ' - ' + msg;
        debugOverlay.appendChild(line);
        debugOverlay.scrollTop = debugOverlay.scrollHeight;
    }
    // --- Translations ---
    const translations = {
        EN: {
            'nav.home': 'Home',
            'nav.lender': 'Lender',
            'nav.tenant': 'Tenant',
            'nav.feedback': 'Feedback',
            'nav.login': 'Login',
            'btn.login': 'Login',
            'search.placeholder': 'Search...',
            'video.fallback': 'Video not available',
            'listings.title': 'The Most Searched Cars',
            'lang.en': 'English',
            'lang.hi': 'Hindi',
            'lang.mr': 'Marathi'
        },
        HI: {
            'nav.home': 'होम',
            'nav.lender': 'ऋणदाता',
            'nav.tenant': 'किरायेदार',
            'nav.feedback': 'फीडबैक',
            'nav.login': 'लॉगिन',
            'btn.login': 'लॉगिन',
            'search.placeholder': 'खोजें...',
            'video.fallback': 'वीडियो उपलब्ध नहीं है',
            'listings.title': 'सबसे खोजी जाने वाली कारें',
            'lang.en': 'अंग्रेजी',
            'lang.hi': 'हिन्दी',
            'lang.mr': 'मराठी'
        },
        MR: {
            'nav.home': 'होम',
            'nav.lender': 'कर्जदाता',
            'nav.tenant': 'भाडेकरू',
            'nav.feedback': 'अभिप्राय',
            'nav.login': 'लॉगिन',
            'btn.login': 'लॉगिन',
            'search.placeholder': 'शोधा...',
            'video.fallback': 'व्हिडिओ उपलब्ध नाही',
            'listings.title': 'सर्वाधिक शोधल्या गेलेल्या कार',
            'lang.en': 'इंग्रजी',
            'lang.hi': 'हिंदी',
            'lang.mr': 'मराठी'
        }
    };

    // Additional keys for login/register page
    const extraKeys = {
        EN: {
            'login.title': 'Login',
            'login.email': 'Email',
            'login.password': 'Password',
            'login.forgot': 'Forgot your password?',
            'btn.register': 'Register',
            'register.title': 'Create Account',
            'register.name': 'Name',
            'register.age': 'Age',
            'register.contact': 'Contact',
            'register.address': 'Address',
            'register.email': 'Email',
            'register.password': 'Password',
            'register.confirm': 'Confirm Password',
            'register.dob': 'DOB'
        },
        HI: {
            'login.title': 'लॉगिन',
            'login.email': 'ईमेल',
            'login.password': 'पासवर्ड',
            'login.forgot': 'क्या आपने पासवर्ड भूल गए?',
            'btn.register': 'रजिस्टर',
            'register.title': 'खाता बनाएं',
            'register.name': 'नाम',
            'register.age': 'उम्र',
            'register.contact': 'संपर्क',
            'register.address': 'पता',
            'register.email': 'ईमेल',
            'register.password': 'पासवर्ड',
            'register.confirm': 'पासवर्ड सत्यापित करें',
            'register.dob': 'जन्म तिथि'
        },
        MR: {
            'login.title': 'लॉगिन',
            'login.email': 'ईमेल',
            'login.password': 'पासवर्ड',
            'login.forgot': 'आपण आपला पासवर्ड विसरला आहे का?',
            'btn.register': 'नोंदणी करा',
            'register.title': 'खाते तयार करा',
            'register.name': 'नाव',
            'register.age': 'वय',
            'register.contact': 'संपर्क',
            'register.address': 'पत्ता',
            'register.email': 'ईमेल',
            'register.password': 'पासवर्ड',
            'register.confirm': 'पासवर्ड पुष्टी करा',
            'register.dob': 'जन्मतारीख'
        }
    };

    // Merge extraKeys into translations
    Object.keys(extraKeys).forEach(lang => {
        translations[lang] = Object.assign({}, translations[lang] || {}, extraKeys[lang]);
    });

    function applyTranslations(lang) {
        if (!lang) lang = 'EN';
        const map = translations[lang] || translations.EN;
        // text nodes
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (key && map[key]) el.textContent = map[key];
        });
        // placeholders
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (key && map[key]) el.setAttribute('placeholder', map[key]);
        });
        // persist
        try { localStorage.setItem('renture_lang', lang); } catch (e) {}
        pageLog('[i18n] applied: ' + lang);
    }
    // extra overlay/register translations
    // add overlay keys with defaults if missing
    const overlayDefaults = {
        'overlay.join': 'Join Renture!',
        'overlay.create': 'Create your account Now!!',
        'overlay.hello': 'Hello, Friend!',
        'overlay.details': 'Enter your personal details and start your journey with us'
    };
    Object.keys(overlayDefaults).forEach(k => {
        ['EN','HI','MR'].forEach(lang => {
            if (!translations[lang][k]) translations[lang][k] = overlayDefaults[k];
        });
    });
    // apply saved language on load
    try {
        const saved = localStorage.getItem('renture_lang') || 'EN';
        applyTranslations(saved);
    } catch (e) {
        applyTranslations('EN');
    }

    // Re-apply translations shortly after load in case some elements are added late
    try {
        const reapply = localStorage.getItem('renture_lang') || 'EN';
        setTimeout(() => applyTranslations(reapply), 120);
        setTimeout(() => applyTranslations(reapply), 600);
    } catch (e) {}
    
    const sidebar = document.getElementById('mySidebar');
    const main = document.getElementById('mainContent');
    const menuIcon = document.querySelector('.menu-icon');
    const menuToggleBtn = document.getElementById('menuToggle');
    const langIcon = document.getElementById('languageIcon');
    const langMenu = document.getElementById('langMenu');
    // Logo diagnostics: log load/error and show a text fallback if image missing
    const logoEl = document.getElementById('siteLogo');
    if (logoEl) {
        logoEl.addEventListener('load', function () {
            pageLog('[logo] loaded: ' + logoEl.getAttribute('src'));
        });
        logoEl.addEventListener('error', function () {
            pageLog('[logo] failed to load: ' + logoEl.getAttribute('src'));
            // hide broken image and insert text fallback
            try {
                logoEl.style.display = 'none';
                const fallback = document.createElement('span');
                fallback.id = 'logoFallback';
                fallback.textContent = 'Renture';
                fallback.style.color = '#fff';
                fallback.style.fontWeight = '700';
                fallback.style.marginLeft = '8px';
                logoEl.parentNode.insertBefore(fallback, logoEl.nextSibling);
            } catch (e) {
                pageLog('[logo] fallback insertion failed: ' + e.message);
            }
        });
    }
    
    // --- FIX: Sidebar Logic now uses classes correctly ---
    
    // Toggle sidebar open/close
    window.toggleSidebar = function () {
        if (!sidebar || !main || !menuToggleBtn) return;
        
        // Use classList.toggle to handle both opening and closing consistently
        const isOpen = sidebar.classList.toggle('open');
        menuToggleBtn.classList.toggle('open', isOpen);
        menuToggleBtn.setAttribute('aria-expanded', isOpen);

        // Shift the main content on desktop/large screens
        if (window.innerWidth >= 600) {
            main.classList.toggle('shift', isOpen);
        } else {
            // On mobile, the sidebar overlays, so no need to shift main content
            main.classList.remove('shift');
        }
        pageLog('[toggleSidebar] Sidebar is now ' + (isOpen ? 'open' : 'closed'));
    };

    // If a menu button exists, bind the click to toggleSidebar
    if (menuToggleBtn) {
        menuToggleBtn.setAttribute('aria-expanded', 'false');
        menuToggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            window.toggleSidebar();
        });
    }

    // Language menu toggle
    if (langIcon && langMenu) {
        langIcon.setAttribute('aria-expanded','false');
        langIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            const open = langMenu.style.display === 'block';
            if (open) {
                langMenu.style.display = 'none';
                langIcon.setAttribute('aria-expanded','false');
                pageLog('[lang] closed');
            } else {
                langMenu.style.display = 'block';
                langIcon.setAttribute('aria-expanded','true');
                pageLog('[lang] opened');
            }
        });

        // Add click handlers to language menu items
        const langMenuItems = langMenu.querySelectorAll('[data-lang]');
        langMenuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const langCode = this.getAttribute('data-lang');
                if (langCode) {
                    window.selectLang(langCode);
                }
            });
        });

        // Close language menu when clicking outside (but not on menu items)
        document.addEventListener('click', function (e) {
            if (langMenu && langMenu.style.display === 'block') {
                const clickedInsideMenu = langMenu.contains(e.target);
                const clickedOnIcon = langIcon.contains(e.target);
                // Only close if clicking outside both the menu and the icon
                if (!clickedInsideMenu && !clickedOnIcon) {
                    langMenu.style.display = 'none';
                    langIcon.setAttribute('aria-expanded','false');
                    pageLog('[lang] closed (outside click)');
                }
            }
        });
    }

    // FIX: Replaced alert() with showMessage() for compliance.
    window.selectLang = function (code) {
        if (!code) return;
        try {
            // store selection (optional) and update UI if needed
            localStorage.setItem('renture_lang', code);
        } catch (e) {
            // ignore storage errors
        }
        if (langMenu) langMenu.style.display = 'none';
        if (langIcon) langIcon.setAttribute('aria-expanded','false');
        applyTranslations(code);
        // show an alert and an on-page message for confirmation
        try { alert('Language switched to: ' + code); } catch (e) {}
        showMessage('Language switched to: ' + code);
        pageLog('[selectLang] Language changed to: ' + code);
    };

    // Login handler
    window.loginUser = function () {
        // If you have a login page, redirect to it. Otherwise show a message.
        const loginPage = 'log_reg.html';
        // Navigate to the login/registration page
        try {
            window.location.href = loginPage;
        } catch (e) {
            // Fallback: show message if navigation fails
            showMessage('Redirecting to Login Page...');
        }
    };

    // Make sidebar close when clicking outside (FIXED logic)
    document.addEventListener('click', function (e) {
        if (!sidebar || !sidebar.classList.contains('open')) return;
        
        const clickedInsideSidebar = sidebar.contains(e.target);
        const clickedMenuButton = menuToggleBtn && menuToggleBtn.contains(e.target);
        
        // If sidebar is open and click is neither inside the sidebar nor on the toggle button, close it.
        if (!clickedInsideSidebar && !clickedMenuButton) {
            window.toggleSidebar();
        }
    });

    // Close sidebar when clicking a nav link
    const sidebarLinks = sidebar ? Array.from(sidebar.querySelectorAll('a')) : [];
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Let the browser navigate, but close sidebar immediately for better UX
            setTimeout(window.toggleSidebar, 150);
        });
    });

    // Attempt to autoplay the top video and show fallback if it fails
    const topVideo = document.getElementById('topVideo');
    const videoFallback = document.getElementById('videoFallback');
    if (topVideo) {
        // try to play (some browsers block autoplay unless muted)
        topVideo.play().catch(() => {
            // show fallback overlay if play is rejected
            if (videoFallback) videoFallback.style.display = 'flex';
        });
        // if the video errors while loading, show fallback
        topVideo.addEventListener('error', function () {
            if (videoFallback) videoFallback.style.display = 'flex';
        });
    }

    // Search functionality for the listings
    const searchInput = document.getElementById('searchInput');
    const listingsContainer = document.getElementById('listingsContainer');
    
    function createNoResultsMessage() {
        let el = document.getElementById('noResultsMessage');
        if (!el) {
            el = document.createElement('p');
            el.id = 'noResultsMessage';
            el.style.cssText = 'color: #001F3F; font-size: 18px; margin-top: 20px;';
            el.textContent = 'No results found.';
            if (listingsContainer && listingsContainer.parentNode) listingsContainer.parentNode.appendChild(el);
        }
        return el;
    }

    function removeNoResultsMessage() {
        const el = document.getElementById('noResultsMessage');
        if (el && el.parentNode) el.parentNode.removeChild(el);
    }

    function filterListings(query) {
        if (!listingsContainer) return;
        const cards = Array.from(listingsContainer.querySelectorAll('.card'));
        const q = (query || '').trim().toLowerCase();
        let visible = 0;
        cards.forEach(card => {
            const nameEl = card.querySelector('.car-name');
            const name = nameEl ? nameEl.textContent.trim().toLowerCase() : '';
            const priceEl = card.querySelector('.price');
            const price = priceEl ? priceEl.textContent.trim().toLowerCase() : '';
            if (!q || name.includes(q) || price.includes(q)) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });
        if (visible === 0) {
            createNoResultsMessage();
        } else {
            removeNoResultsMessage();
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            filterListings(e.target.value);
        });
        // Optional: handle Enter key to keep focus or perform a final filter
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterListings(e.target.value);
            }
        });
    }
});