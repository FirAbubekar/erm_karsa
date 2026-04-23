<!-- Mobile Header -->
<div class="mobile-header">
    <div class="logo-section" style="padding: 0; border: none; background: transparent;">
        <div class="logo-box" style="width: 32px; height: 32px; font-size: 14px;">K</div>
        <div class="logo-text" style="font-size: 16px;">KARSA ERM</div>
    </div>
    <button class="hamburger" id="mobile-toggle" style="background: none; border: none; cursor: pointer;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
    </button>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo-section">
        <div class="logo-box">K</div>
        <div class="logo-text">KARSA ERM</div>
    </div>

    <div class="nav-section">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        
        <div class="nav-label" style="margin-top: 24px;">Rekam Medis</div>
        
        <!-- Dropdown Menu -->
        <div class="nav-dropdown {{ (Route::is('general-consent') || Route::is('general-consent.index')) ? 'active' : '' }}">
            <div class="nav-dropdown-toggle">
                <div class="nav-dropdown-label">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    RM 01 General Consent
                </div>
                <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="nav-dropdown-content">
                <a href="{{ route('general-consent') }}" class="nav-dropdown-item {{ Route::is('general-consent') ? 'active' : '' }}">
                    Input General Consent
                </a>
                <a href="{{ route('general-consent.index') }}" class="nav-dropdown-item {{ Route::is('general-consent.index') ? 'active' : '' }}">
                    Riwayat General Consent
                </a>
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</div>

<style>
    /* Dropdown Styles */
    .nav-dropdown {
        margin-bottom: 4px;
    }

    .nav-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 12px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
    }

    .nav-dropdown-toggle:hover {
        background: var(--bg-main);
        color: var(--text-main);
    }

    .nav-dropdown.active .nav-dropdown-toggle {
        color: var(--text-main);
    }

    .nav-dropdown-label {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .nav-dropdown-label svg {
        width: 20px;
        height: 20px;
    }

    .chevron {
        width: 16px;
        height: 16px;
        transition: transform 0.3s ease;
    }

    .nav-dropdown.active .chevron {
        transform: rotate(180deg);
    }

    .nav-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        padding-left: 36px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .nav-dropdown.active .nav-dropdown-content {
        max-height: 200px;
        margin-top: 4px;
        margin-bottom: 8px;
    }

    .nav-dropdown-item {
        display: block;
        padding: 10px 16px;
        border-radius: 10px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .nav-dropdown-item:hover {
        background: var(--bg-main);
        color: var(--text-main);
    }

    .nav-dropdown-item.active {
        color: var(--primary);
        font-weight: 600;
        background: var(--primary-light);
    }
</style>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle = document.getElementById('mobile-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    if (toggle) toggle.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    // Dropdown Logic
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    dropdowns.forEach(dropdown => {
        const toggleBtn = dropdown.querySelector('.nav-dropdown-toggle');
        toggleBtn.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });
    });

    // Close sidebar when clicking nav items on mobile
    const navItems = document.querySelectorAll('.nav-item, .nav-dropdown-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 1024) {
                toggleSidebar();
            }
        });
    });
</script>

