<!-- Mobile Header -->
<div class="mobile-header">
    <div class="logo-section" style="padding: 0; border: none; background: transparent;">
        <div class="logo-box" style="width: 32px; height: 32px; font-size: 14px;">K</div>
        <div class="logo-text" style="font-size: 16px;">KARSA ERM</div>
    </div>
    <button class="hamburger" id="mobile-toggle" style="background: none; border: none; cursor: pointer;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo-section" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="logo-box">K</div>
            <div class="logo-text">KARSA ERM</div>
        </div>
        <button id="desktop-toggle" class="desktop-only-btn"
            style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                </path>
            </svg>
        </button>
    </div>

    <div class="nav-section">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Dashboard
        </a>

        @if (hasPermission('gc.create') ||
                hasPermission('gc.view') ||
                hasPermission('ranap.create') ||
                hasPermission('ranap.view') ||
                hasPermission('ep.view'))
            <div class="nav-label" style="margin-top: 24px;">Rekam Medis</div>
        @endif

        @if (hasPermission('gc.create') || hasPermission('gc.view'))
            <div
                class="nav-dropdown {{ Route::is('general-consent') || Route::is('general-consent.index') ? 'active' : '' }}">
                <div class="nav-dropdown-toggle">
                    <div class="nav-dropdown-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        RM 01 General Consent
                    </div>
                    <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="nav-dropdown-content">
                    @if (hasPermission('gc.create'))
                        <a href="{{ route('general-consent') }}"
                            class="nav-dropdown-item {{ Route::is('general-consent') ? 'active' : '' }}">
                            Input General Consent
                        </a>
                    @endif
                    @if (hasPermission('gc.view'))
                        <a href="{{ route('general-consent.index') }}"
                            class="nav-dropdown-item {{ Route::is('general-consent.index') ? 'active' : '' }}">
                            Riwayat General Consent
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (hasPermission('ranap.create') || hasPermission('ranap.view'))
            <div class="nav-dropdown {{ Route::is('surat-persetujuan-rawat-inap.*') ? 'active' : '' }}">
                <div class="nav-dropdown-toggle">
                    <div class="nav-dropdown-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        RM 02 Surat Persetujuan Rawat Inap
                    </div>
                    <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="nav-dropdown-content">
                    @if (hasPermission('ranap.create'))
                        <a href="{{ route('surat-persetujuan-rawat-inap.index') }}"
                            class="nav-dropdown-item {{ Route::is('surat-persetujuan-rawat-inap.index') ? 'active' : '' }}">
                            Surat Persetujuan Rawat Inap
                        </a>
                    @endif
                    @if (hasPermission('ranap.view'))
                        <a href="{{ route('surat-persetujuan-rawat-inap.history') }}"
                            class="nav-dropdown-item {{ Route::is('surat-persetujuan-rawat-inap.history') ? 'active' : '' }}">
                            Riwayat Surat Persetujuan Rawat Inap
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (hasPermission('ep.create') || hasPermission('ep.view'))
            <div class="nav-dropdown {{ Route::is('edukasi-pasien.*') ? 'active' : '' }}">
                <div class="nav-dropdown-toggle">
                    <div class="nav-dropdown-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        RM 10 Edukasi Pasien
                    </div>
                    <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                <div class="nav-dropdown-content">
                    @if (hasPermission('ep.create'))
                        <a href="{{ route('edukasi-pasien') }}"
                            class="nav-dropdown-item {{ Route::is('edukasi-pasien') ? 'active' : '' }}">
                            Edukasi Pasien
                        </a>
                    @endif
                    @if (hasPermission('ep.view'))
                        <a href="{{ route('edukasi-pasien.history') }}"
                            class="nav-dropdown-item {{ Route::is('edukasi-pasien.history') ? 'active' : '' }}">
                            Riwayat Edukasi Pasien
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (hasPermission('lab.view'))
            <div class="nav-label" style="margin-top: 24px;">Laboratorium</div>

            <div class="nav-dropdown {{ Route::is('hasil-lab.*') ? 'active' : '' }}">
                <div class="nav-dropdown-toggle">
                    <div class="nav-dropdown-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                        Laboratorium
                    </div>
                    <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                <div class="nav-dropdown-content">
                    <a href="{{ route('hasil-lab.index') }}"
                        class="nav-dropdown-item {{ Route::is('hasil-lab.*') ? 'active' : '' }}">
                        Hasil Lab
                    </a>
                </div>
            </div>
        @endif

        @if (hasPermission('farmasi.create') || hasPermission('farmasi.view'))
            <div class="nav-label" style="margin-top: 24px;">Farmasi</div>

            @if (hasPermission('prospective-reviu.view'))
                <div
                    class="nav-dropdown {{ Route::is('prospective-reviu.*') || Route::is('prospective-reviu') ? 'active' : '' }}">
                    <div class="nav-dropdown-toggle">
                        <div class="nav-dropdown-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Prospective Reviu
                        </div>
                        <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    <div class="nav-dropdown-content">
                        @if (hasPermission('prospective-reviu.create'))
                            <a href="{{ route('prospective-reviu') }}"
                                class="nav-dropdown-item {{ Route::is('prospective-reviu') ? 'active' : '' }}">
                                Input Prospective Reviu
                            </a>
                        @endif
                        @if (hasPermission('prospective-reviu.view'))
                            <a href="{{ route('prospective-reviu.history-page') }}"
                                class="nav-dropdown-item {{ Route::is('prospective-reviu.history-page') ? 'active' : '' }}">
                                Riwayat Prospective Reviu
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @endif
        @if (hasPermission('role-permission.view'))
            <div class="nav-label" style="margin-top: 24px;">Pengaturan</div>

            <div class="nav-dropdown {{ Route::is('web-permissions.*') ? 'active' : '' }}">
                <div class="nav-dropdown-toggle">
                    <div class="nav-dropdown-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Role & Permission
                    </div>
                    <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                <div class="nav-dropdown-content">
                    <a href="{{ route('web-permissions.index') }}"
                        class="nav-dropdown-item {{ Route::is('web-permissions.*') ? 'active' : '' }}">
                        Master Permission
                    </a>
                    <a href="{{ route('web-user-roles.index') }}"
                        class="nav-dropdown-item {{ Route::is('web-user-roles.*') ? 'active' : '' }}">
                        User Roles
                    </a>
                    <a href="{{ route('web-role-permissions.index') }}"
                        class="nav-dropdown-item {{ Route::is('web-role-permissions.*') ? 'active' : '' }}">
                        Role Permissions
                    </a>
                </div>
            </div>
        @endif

        <div class="nav-label" style="margin-top: 24px;">Pedoman</div>

        <div class="nav-dropdown">
            <div class="nav-dropdown-toggle">
                <div class="nav-dropdown-label">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Pedoman Penggunaan
                </div>
                <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            <div class="nav-dropdown-content">
                <a href="{{ asset('pdf/Tutorial Penggunaan Aplikasi ERM General Consent.pdf') }}" target="_blank"
                    class="nav-dropdown-item">
                    General Consent
                </a>
                <a href="{{ asset('pdf/Tutorial Penggunaan Aplikasi Surat Persetujuan Rawat Inap.pdf') }}"
                    target="_blank" class="nav-dropdown-item">
                    Surat Persetujuan Rawat Inap
                </a>
                <a href="{{ asset('pdf/Manual_Book_KARSA_ERM_RM10.pdf') }}" target="_blank"
                    class="nav-dropdown-item">
                    RM 10
                </a>
                <a href="{{ asset('pdf/Tutorial Penggunaan Aplikasi ERM Hasil Lab.pdf') }}" target="_blank"
                    class="nav-dropdown-item">
                    Hasil Lab
                </a>
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>

<style>
    /* Minimized Sidebar Styles */
    @media (max-width: 1024px) {
        .desktop-only-btn {
            display: none !important;
        }
    }

    body.sidebar-minimized .sidebar {
        width: 80px !important;
    }

    body.sidebar-minimized .main-content {
        margin-left: 80px !important;
        max-width: calc(100vw - 80px) !important;
    }

    body.sidebar-minimized .logo-text,
    body.sidebar-minimized .nav-label,
    body.sidebar-minimized .chevron,
    body.sidebar-minimized .nav-dropdown-content,
    body.sidebar-minimized .logo-box {
        display: none !important;
    }

    body.sidebar-minimized .logo-section {
        justify-content: center !important;
        padding: 24px 0 !important;
    }

    body.sidebar-minimized #desktop-toggle svg {
        transform: rotate(180deg);
    }

    body.sidebar-minimized .nav-item,
    body.sidebar-minimized .nav-dropdown-toggle,
    body.sidebar-minimized .btn-logout {
        padding: 12px !important;
        justify-content: center !important;
        font-size: 0 !important;
        gap: 0 !important;
    }

    body.sidebar-minimized .nav-dropdown-label {
        font-size: 0 !important;
        gap: 0 !important;
    }

    body.sidebar-minimized .nav-item svg,
    body.sidebar-minimized .nav-dropdown-label svg,
    body.sidebar-minimized .btn-logout svg {
        margin: 0 !important;
        width: 24px !important;
        height: 24px !important;
        min-width: 24px !important;
        flex-shrink: 0 !important;
    }

    /* Sidebar Scrolling Fix (Global) */
    .sidebar .nav-section {
        overflow-y: auto !important;
        min-height: 0 !important;
    }

    .sidebar .nav-section::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar .nav-section::-webkit-scrollbar-thumb {
        background-color: var(--border);
        border-radius: 4px;
    }

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

    /* Logout Button */
    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid var(--border);
        margin-top: auto;
    }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        width: 100%;
        background: #FEF2F2;
        color: #EF4444;
        border: 1px solid #FEE2E2;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .btn-logout:hover {
        background: #FEE2E2;
        border-color: #FECACA;
        transform: translateY(-1px);
    }

    .btn-logout svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
</style>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle = document.getElementById('mobile-toggle');
    const desktopToggle = document.getElementById('desktop-toggle');

    // Dropdown Logic
    const dropdowns = document.querySelectorAll('.nav-dropdown');

    // Desktop Minimize Logic
    const isMinimized = localStorage.getItem('sidebar_minimized') === 'true';
    if (isMinimized) {
        document.body.classList.add('sidebar-minimized');
        dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
    }

    if (desktopToggle) {
        desktopToggle.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-minimized');
            const minimizedState = document.body.classList.contains('sidebar-minimized');
            localStorage.setItem('sidebar_minimized', minimizedState);

            // Close dropdowns if minimizing
            if (minimizedState) {
                dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
            }
        });
    }

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    if (toggle) toggle.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    // Dropdown Logic
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
