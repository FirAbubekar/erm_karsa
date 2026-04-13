<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Dashboard</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --primary-light: rgba(16, 185, 129, 0.1);
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .logo-section {
            padding: 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
        }

        .logo-text {
            font-weight: 700;
            color: var(--text-main);
            font-size: 18px;
            letter-spacing: -0.5px;
        }

        .nav-section {
            padding: 24px 16px;
            flex-grow: 1;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 16px;
            margin-left: 8px;
            letter-spacing: 0.05em;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            background: var(--bg-main);
            color: var(--text-main);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 32px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-title p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: #E2E8F0;
            border-radius:  full;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            color: var(--text-muted);
        }

        .user-info span {
            font-size: 13px;
            font-weight: 600;
            display: block;
        }

        /* Dashboard Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.5;
        }

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
            padding: 10px;
            width: 100%;
            background: #FEF2F2;
            color: #EF4444;
            border: 1px solid #FEE2E2;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
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

        /* Responsiveness */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 280px;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            .mobile-header {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 64px;
                background: white;
                border-bottom: 1px solid var(--border);
                align-items: center;
                padding: 0 20px;
                z-index: 40;
                justify-content: space-between;
            }

            .hamburger {
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                background: var(--bg-main);
                border: none;
                color: var(--text-main);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 45;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .user-profile {
                width: 100%;
                justify-content: center;
            }
        }

        @media (min-width: 1025px) {
            .mobile-header {
                display: none;
            }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-title">
                <h1>Halo, {{ Session::get('user_id') }}!</h1>
                <p>Selamat datang kembali di panel Karsa ERM.</p>
            </div>

            <div class="user-profile">
                <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                <div class="user-info">
                    <span>{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </header>

        <div class="grid">
            <div class="card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Penyelesaian Ringkasan</h3>
                <p>Lengkapi ringkasan rekam medis pasien untuk memudahkan diagnosa selanjutnya.</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Antrian Terbaru</h3>
                <p>Lihat daftar antrian pasien hari ini secara real-time dan terorganisir.</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3>Statistik Kunjungan</h3>
                <p>Pantau grafik kunjungan pasien bulanan melalui sistem analitik kami.</p>
            </div>
        </div>
    </div>
    <script>
        // No custom scripts needed for sidebar as they are in partial
    </script>
</body>
</html>
