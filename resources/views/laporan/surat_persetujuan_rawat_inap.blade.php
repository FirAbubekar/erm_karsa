<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Laporan Surat Persetujuan Rawat Inap</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-width: 170px;
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: rgba(16, 185, 129, 0.1);
            --accent: #6366F1;
            --accent-light: rgba(99, 102, 241, 0.08);
            --accent-dark: #4F46E5;
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;

            /* Light palette */
            --surface: #F8FAFC;
            --glass: #FFFFFF;
            --glass-border: #E2E8F0;
            --glow-1: #3B82F6;
            --glow-2: #8B5CF6;
            --glow-3: #0891B2;
            --glow-4: #10B981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ── Main Layout ── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 0;
            max-width: calc(100vw - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Animated BG Mesh ── */
        .bg-mesh {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .bg-mesh .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.12;
            animation: orbFloat 20s ease-in-out infinite;
        }
        .bg-mesh .orb-1 {
            width: 600px; height: 600px;
            background: #93C5FD;
            top: -10%; right: -5%;
            animation-delay: 0s;
        }
        .bg-mesh .orb-2 {
            width: 500px; height: 500px;
            background: #C4B5FD;
            bottom: -15%; left: 10%;
            animation-delay: -7s;
        }
        .bg-mesh .orb-3 {
            width: 400px; height: 400px;
            background: #6EE7B7;
            top: 40%; right: 20%;
            animation-delay: -14s;
        }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -40px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(10px, 30px) scale(1.02); }
        }

        /* ── Page Container ── */
        .page-container {
            position: relative;
            z-index: 1;
            padding: 32px 36px 48px;
        }

        /* ── Header Strip ── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
        }
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }
        .breadcrumb-nav a,
        .breadcrumb-nav span {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumb-nav a:hover { color: var(--text-main); }
        .breadcrumb-nav .bc-active { color: var(--glow-3); font-weight: 600; }
        .page-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main);
            line-height: 1.2;
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 400;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 100px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .user-pill .u-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--glow-1), var(--glow-2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
            color: white;
        }
        .user-pill .u-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            flex-wrap: wrap;
        }
        .filter-bar .f-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-bar .f-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }
        .filter-bar .f-input {
            padding: 9px 14px;
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 13px;
            font-weight: 500;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .filter-bar .f-input:focus {
            border-color: var(--glow-1);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            background: white;
        }
        .btn-apply {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--glow-1), var(--glow-2));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 14px rgba(59,130,246,0.2);
            font-family: inherit;
            letter-spacing: 0.02em;
        }
        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.3);
        }
        .btn-clear {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            background: #F1F5F9;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-clear:hover {
            background: #E2E8F0;
            color: var(--text-main);
        }

        /* ── Stats Row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        .s-card {
            position: relative;
            padding: 22px 24px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .s-card:hover {
            border-color: #CBD5E1;
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }
        .s-card .s-glow {
            position: absolute;
            width: 120px; height: 120px;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.08;
            top: -30px; right: -30px;
        }
        .s-card:nth-child(1) .s-glow { background: var(--glow-1); }
        .s-card:nth-child(2) .s-glow { background: var(--glow-4); }
        .s-card:nth-child(3) .s-glow { background: var(--glow-2); }
        .s-card .s-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            position: relative;
        }
        .s-card .s-icon {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .s-card:nth-child(1) .s-icon { background: rgba(59,130,246,0.1); color: var(--glow-1); }
        .s-card:nth-child(2) .s-icon { background: rgba(16,185,129,0.1); color: var(--glow-4); }
        .s-card:nth-child(3) .s-icon { background: rgba(139,92,246,0.1); color: var(--glow-2); }
        .s-card .s-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .s-card:nth-child(1) .s-badge { background: rgba(59,130,246,0.08); color: var(--glow-1); }
        .s-card:nth-child(2) .s-badge { background: rgba(16,185,129,0.08); color: var(--glow-4); }
        .s-card:nth-child(3) .s-badge { background: rgba(139,92,246,0.08); color: var(--glow-2); }
        .s-card .s-value {
            font-size: 32px;
            font-weight: 900;
            color: var(--text-main);
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 4px;
            position: relative;
        }
        .s-card .s-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            position: relative;
        }

        /* ── Glass Panel ── */
        .g-panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 24px;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .g-panel:hover {
            border-color: #CBD5E1;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }
        .g-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-light);
        }
        .g-panel-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.3px;
        }
        .g-panel-header .g-sub {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
        }
        .g-panel-body {
            padding: 24px;
        }

        /* ── Chart Container ── */
        .chart-wrap {
            position: relative;
            height: 340px;
            width: 100%;
        }

        /* ── Two Column Grid ── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        /* ── Rank List ── */
        .rank-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .rank-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 14px;
            transition: all 0.25s;
        }
        .rank-item:hover {
            background: #F1F5F9;
            border-color: var(--border);
            transform: translateX(4px);
        }
        .rank-badge {
            width: 34px; height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            flex-shrink: 0;
        }
        .rank-badge.gold { background: #FEF3C7; color: #D97706; }
        .rank-badge.silver { background: #F1F5F9; color: #64748B; }
        .rank-badge.bronze { background: #FFEDD5; color: #C2410C; }
        .rank-badge.normal { background: #F1F5F9; color: #94A3B8; }
        .rank-info {
            flex: 1;
            min-width: 0;
        }
        .rank-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 6px;
        }
        .rank-bar-track {
            width: 100%;
            height: 5px;
            background: #E2E8F0;
            border-radius: 10px;
            overflow: hidden;
        }
        .rank-bar-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease;
        }
        .rank-bar-fill.c1 { background: linear-gradient(90deg, #FBBF24, #F59E0B); }
        .rank-bar-fill.c2 { background: linear-gradient(90deg, #94A3B8, #64748B); }
        .rank-bar-fill.c3 { background: linear-gradient(90deg, #F97316, #EA580C); }
        .rank-bar-fill.c4 { background: linear-gradient(90deg, var(--glow-1), var(--glow-2)); }
        .rank-count {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-main);
            white-space: nowrap;
            flex-shrink: 0;
        }
        .rank-count small {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            margin-left: 2px;
        }

        /* ── Horizontal Bar List ── */
        .hbar-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .hbar-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .hbar-label {
            width: 105px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-align: right;
            flex-shrink: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .hbar-track {
            flex: 1;
            height: 28px;
            background: #F1F5F9;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
        .hbar-fill {
            height: 100%;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding-left: 10px;
            min-width: 40px;
            transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hbar-fill span {
            font-size: 11px;
            font-weight: 700;
            color: white;
            white-space: nowrap;
        }
        .hbar-value {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            width: 50px;
            text-align: right;
            flex-shrink: 0;
        }

        /* ── Period Tag ── */
        .period-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: rgba(59,130,246,0.06);
            border: 1px solid rgba(59,130,246,0.12);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--glow-1);
        }

        .top-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            background: rgba(139,92,246,0.08);
            border: 1px solid rgba(139,92,246,0.12);
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            color: var(--glow-2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* ── Entrance Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-in {
            animation: fadeUp 0.6s ease forwards;
            opacity: 0;
        }
        .anim-d1 { animation-delay: 0.05s; }
        .anim-d2 { animation-delay: 0.1s; }
        .anim-d3 { animation-delay: 0.15s; }
        .anim-d4 { animation-delay: 0.2s; }
        .anim-d5 { animation-delay: 0.25s; }
        .anim-d6 { animation-delay: 0.3s; }
        .anim-d7 { animation-delay: 0.35s; }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .two-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 1024px) {
            .main-content { margin-left: 0; width: 100%; max-width: 100%; }
            .bg-mesh { left: 0; }
            .page-container { padding: 24px 20px 40px; }
            .stats-row { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; gap: 16px; }
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    <div class="main-content">

        <!-- Animated Background Mesh -->
        <div class="bg-mesh">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>

        <div class="page-container">

            <!-- ── Header ── -->
            <div class="page-header anim-in anim-d1">
                <div class="header-left">
                    <div class="breadcrumb-nav">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span>/</span>
                        <span>Laporan</span>
                        <span>/</span>
                        <span class="bc-active">Surat Persetujuan Rawat Inap</span>
                    </div>
                    <h1 class="page-title">Laporan Surat Persetujuan Rawat Inap</h1>
                    <p class="page-subtitle">Analitik & visualisasi data persetujuan rawat inap (SPRI)</p>
                </div>
                <div class="header-right">
                    <div class="user-pill">
                        <div class="u-avatar">{{ strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) }}</div>
                        <span class="u-name">{{ Session::get('user_name', 'Pengguna') }}</span>
                    </div>
                </div>
            </div>

            <!-- ── Filter Bar ── -->
            <form action="{{ route('laporan.surat-persetujuan-rawat-inap') }}" method="GET" class="filter-bar anim-in anim-d2">
                <div class="f-group">
                    <span class="f-label">Dari Tanggal</span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="f-input">
                </div>
                <div class="f-group">
                    <span class="f-label">Sampai Tanggal</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="f-input">
                </div>
                <button type="submit" class="btn-apply">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Tampilkan
                </button>
                <a href="{{ route('laporan.surat-persetujuan-rawat-inap') }}" class="btn-clear">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            </form>

            <!-- ── Stats Cards ── -->
            <div class="stats-row">
                <div class="s-card anim-in anim-d3">
                    <div class="s-glow"></div>
                    <div class="s-top">
                        <div class="s-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="s-badge">Periode</span>
                    </div>
                    <div class="s-value">{{ number_format($totalPeriod) }}</div>
                    <div class="s-label">Total Surat SPRI</div>
                </div>

                <div class="s-card anim-in anim-d4">
                    <div class="s-glow"></div>
                    <div class="s-top">
                        <div class="s-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="s-badge">Harian</span>
                    </div>
                    <div class="s-value">{{ $avgDaily }}</div>
                    <div class="s-label">Rata-rata per Hari</div>
                </div>

                <div class="s-card anim-in anim-d5">
                    <div class="s-glow"></div>
                    <div class="s-top">
                        <div class="s-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"/></svg>
                        </div>
                        <span class="s-badge">Peak</span>
                    </div>
                    <div class="s-value">{{ number_format($maxDaily) }}</div>
                    <div class="s-label">Tertinggi dalam 1 Hari</div>
                </div>
            </div>

            <!-- ── Trend Chart ── -->
            <div class="g-panel anim-in anim-d6">
                <div class="g-panel-header">
                    <h3>Tren Harian Surat Persetujuan Rawat Inap</h3>
                    <div class="period-tag">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </div>
                </div>
                <div class="g-panel-body">
                    <div class="chart-wrap">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ── Row 1: Top Users & Distribusi Kelas ── -->
            <div class="two-col">

                <!-- Top Petugas -->
                <div class="g-panel anim-in anim-d7" style="margin-bottom: 0;">
                    <div class="g-panel-header">
                        <h3>Top Petugas Pembuat SPRI</h3>
                        <span class="top-badge">Top 5</span>
                    </div>
                    <div class="g-panel-body">
                        <div class="rank-list">
                            @forelse ($topUsers as $rank => $user)
                                @php
                                    $percent = $totalPeriod > 0 ? round(($user->total_dibuat / $totalPeriod) * 100, 1) : 0;
                                    $badgeClass = match($rank) {
                                        0 => 'gold',
                                        1 => 'silver',
                                        2 => 'bronze',
                                        default => 'normal',
                                    };
                                    $barClass = match($rank) {
                                        0 => 'c1',
                                        1 => 'c2',
                                        2 => 'c3',
                                        default => 'c4',
                                    };
                                @endphp
                                <div class="rank-item">
                                    <div class="rank-badge {{ $badgeClass }}">#{{ $rank + 1 }}</div>
                                    <div class="rank-info">
                                        <div class="rank-name">{{ $user->nama_petugas }}</div>
                                        <div class="rank-bar-track">
                                            <div class="rank-bar-fill {{ $barClass }}" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                    <div class="rank-count">{{ number_format($user->total_dibuat) }}<small>surat</small></div>
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data petugas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Distribusi Kelas Rawat Inap -->
                <div class="g-panel anim-in anim-d7" style="margin-bottom: 0;">
                    <div class="g-panel-header">
                        <h3>Distribusi Kelas Rawat Inap</h3>
                        <span class="g-sub">Berdasarkan pilihan kelas kamar</span>
                    </div>
                    <div class="g-panel-body">
                        @php
                            $kelasMax = count($kelasChartData) > 0 ? max($kelasChartData) : 1;
                            $barColors = ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EC4899','#06B6D4','#6366F1','#64748B'];
                            $totalKelas = array_sum($kelasChartData);
                        @endphp
                        <div class="hbar-list">
                            @forelse ($kelasLabels as $idx => $label)
                                @php
                                    $val = $kelasChartData[$idx] ?? 0;
                                    $barPct = $kelasMax > 0 ? round(($val / $kelasMax) * 100) : 0;
                                    $color = $barColors[$idx % count($barColors)];
                                    $pctOfTotal = $totalKelas > 0 ? round(($val / $totalKelas) * 100, 1) : 0;
                                @endphp
                                <div class="hbar-item">
                                    <div class="hbar-label" title="{{ $label }}">{{ $label }}</div>
                                    <div class="hbar-track">
                                        <div class="hbar-fill" style="width: {{ $barPct }}%; background: {{ $color }};">
                                            <span>{{ $pctOfTotal }}%</span>
                                        </div>
                                    </div>
                                    <div class="hbar-value">{{ number_format($val) }}</div>
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data kelas rawat.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── Row 2: Distribusi Hubungan PJ & Cara Bayar ── -->
            <div class="two-col">

                <!-- Hubungan Penanggung Jawab -->
                <div class="g-panel anim-in anim-d7" style="margin-bottom: 0;">
                    <div class="g-panel-header">
                        <h3>Distribusi Hubungan Penanggung Jawab</h3>
                        <span class="g-sub">Hubungan dengan pasien</span>
                    </div>
                    <div class="g-panel-body">
                        @php
                            $hubunganMax = count($hubunganChartData) > 0 ? max($hubunganChartData) : 1;
                            $hubColors = ['#0891B2','#6366F1','#10B981','#F59E0B','#EC4899','#8B5CF6','#3B82F6','#64748B'];
                            $totalHub = array_sum($hubunganChartData);
                        @endphp
                        <div class="hbar-list">
                            @forelse ($hubunganLabels as $idx => $label)
                                @php
                                    $val = $hubunganChartData[$idx] ?? 0;
                                    $barPct = $hubunganMax > 0 ? round(($val / $hubunganMax) * 100) : 0;
                                    $color = $hubColors[$idx % count($hubColors)];
                                    $pctOfTotal = $totalHub > 0 ? round(($val / $totalHub) * 100, 1) : 0;
                                @endphp
                                <div class="hbar-item">
                                    <div class="hbar-label" title="{{ $label }}">{{ $label }}</div>
                                    <div class="hbar-track">
                                        <div class="hbar-fill" style="width: {{ $barPct }}%; background: {{ $color }};">
                                            <span>{{ $pctOfTotal }}%</span>
                                        </div>
                                    </div>
                                    <div class="hbar-value">{{ number_format($val) }}</div>
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data hubungan PJ.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Cara Bayar / Penjamin -->
                <div class="g-panel anim-in anim-d7" style="margin-bottom: 0;">
                    <div class="g-panel-header">
                        <h3>Distribusi Cara Bayar / Penjamin</h3>
                        <span class="g-sub">Metode pembayaran pasien</span>
                    </div>
                    <div class="g-panel-body">
                        @php
                            $bayarMax = count($bayarChartData) > 0 ? max($bayarChartData) : 1;
                            $bayarColors = ['#10B981','#3B82F6','#F59E0B','#8B5CF6','#EC4899','#64748B'];
                            $totalBayar = array_sum($bayarChartData);
                        @endphp
                        <div class="hbar-list">
                            @forelse ($bayarLabels as $idx => $label)
                                @php
                                    $val = $bayarChartData[$idx] ?? 0;
                                    $barPct = $bayarMax > 0 ? round(($val / $bayarMax) * 100) : 0;
                                    $color = $bayarColors[$idx % count($bayarColors)];
                                    $pctOfTotal = $totalBayar > 0 ? round(($val / $totalBayar) * 100, 1) : 0;
                                @endphp
                                <div class="hbar-item">
                                    <div class="hbar-label" title="{{ $label }}">{{ $label }}</div>
                                    <div class="hbar-track">
                                        <div class="hbar-fill" style="width: {{ $barPct }}%; background: {{ $color }};">
                                            <span>{{ $pctOfTotal }}%</span>
                                        </div>
                                    </div>
                                    <div class="hbar-value">{{ number_format($val) }}</div>
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data cara bayar.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ── Chart.js Script ── -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        const ctx = document.getElementById('trendChart').getContext('2d');

        // Gradient fill
        const grad = ctx.createLinearGradient(0, 0, 0, 320);
        grad.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        grad.addColorStop(0.5, 'rgba(139, 92, 246, 0.05)');
        grad.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah SPRI',
                    data: data,
                    borderColor: '#3B82F6',
                    borderWidth: 2.5,
                    backgroundColor: grad,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#2563EB',
                    pointHoverBorderColor: '#FFFFFF',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                layout: {
                    padding: { left: 4, right: 12, top: 8, bottom: 4 }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.92)',
                        titleFont: { family: 'Inter', size: 12, weight: '700' },
                        bodyFont: { family: 'Inter', size: 12, weight: '500' },
                        titleColor: '#FFFFFF',
                        bodyColor: '#CBD5E1',
                        padding: { x: 14, y: 10 },
                        cornerRadius: 10,
                        borderColor: 'rgba(0,0,0,0.08)',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            title: function(items) { return items[0].label; },
                            label: function(ctx) { return '  ' + ctx.parsed.y + ' Surat SPRI'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#F1F5F9', drawBorder: false },
                        ticks: {
                            font: { family: 'Inter', size: 10, weight: '500' },
                            color: '#94A3B8',
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9', drawBorder: false },
                        ticks: {
                            precision: 0,
                            font: { family: 'Inter', size: 10, weight: '500' },
                            color: '#94A3B8',
                            padding: 8
                        }
                    }
                }
            }
        });

        // Animate horizontal bars on load
        setTimeout(function() {
            document.querySelectorAll('.hbar-fill').forEach(function(bar) {
                bar.style.width = bar.style.width;
            });
        }, 400);

    });
    </script>

</body>
</html>
