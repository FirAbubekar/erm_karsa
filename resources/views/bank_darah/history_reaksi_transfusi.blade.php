<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Reaksi Transfusi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --sidebar-width: 280px;
            --primary: #DC2626;
            --primary-dark: #B91C1C;
            --primary-light: rgba(220,38,38,0.08);
            --primary-surface: #FEF2F2;
            --primary-gradient: linear-gradient(135deg, #DC2626, #B91C1C);
            --primary-glow: rgba(220,38,38,0.25);
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --success-light: #D1FAE5;
            --warning: #F59E0B;
            --warning-light: #FEF3C7;
            --danger: #EF4444;
            --danger-light: #FEE2E2;
            --info: #3B82F6;
            --info-light: #DBEAFE;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }
        body {
            background: var(--bg-main);
            background-image: radial-gradient(circle at 100% 0%, rgba(220,38,38,0.03) 0%, transparent 50%),
                              radial-gradient(circle at 0% 100%, rgba(59,130,246,0.03) 0%, transparent 50%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .sidebar { width: var(--sidebar-width); background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; box-shadow: 0 4px 12px var(--primary-glow); }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); transform: translateX(4px); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; box-shadow: inset 3px 0 0 var(--primary); }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; }

        .page-header {
            display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px;
            background: var(--card-bg); border-radius: var(--radius-xl); border: 1px solid var(--border);
            padding: 22px 28px; box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
        }
        .page-header::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--primary-gradient); border-radius: 0 4px 4px 0;
        }
        .page-header-left h1 { font-size: 24px; font-weight: 800; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
        .page-header-left h1 i { color: var(--primary); font-size: 22px; background: var(--primary-surface); padding: 8px; border-radius: 10px; }
        .page-header-left p { color: var(--text-muted); font-size: 14px; margin-top: 4px; margin-left: 46px; }
        .page-header-right { display: flex; gap: 10px; align-items: center; }
        .btn-primary-sm {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px;
            background: var(--primary-gradient); color: white; border: none; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 2px 8px var(--primary-glow);
        }
        .btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 4px 16px var(--primary-glow); }
        .user-badge {
            display: flex; align-items: center; gap: 10px; padding: 6px 16px 6px 6px;
            background: var(--bg-main); border: 1px solid var(--border); border-radius: 999px;
            box-shadow: var(--shadow-sm); transition: all 0.2s;
        }
        .user-badge:hover { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        .user-avatar {
            width: 30px; height: 30px; background: var(--primary-gradient);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px; color: white; box-shadow: 0 2px 8px var(--primary-glow);
        }
        .user-name { font-size: 12px; font-weight: 600; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
        .stat-card {
            background: var(--card-bg); border-radius: var(--radius-lg);
            border: 1px solid var(--border); padding: 20px 22px;
            box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 18px;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1); position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px;
            border-radius: 50%; opacity: 0.06; transform: translate(20px, -20px);
            background: var(--primary-gradient);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: transparent; }
        .stat-card:hover::after { opacity: 0.10; }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 20px; flex-shrink: 0;
            transition: all 0.3s; position: relative; z-index: 1;
        }
        .stat-card:hover .stat-icon { transform: scale(1.08); }
        .stat-icon.red { background: var(--primary-surface); color: var(--primary); box-shadow: 0 2px 8px var(--primary-glow); }
        .stat-icon.blue { background: #EFF6FF; color: var(--info); box-shadow: 0 2px 8px rgba(59,130,246,0.15); }
        .stat-icon.green { background: #ECFDF5; color: var(--success); box-shadow: 0 2px 8px rgba(16,185,129,0.15); }
        .stat-info h3 { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .stat-info p { font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 500; }

        .filter-card {
            background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border);
            box-shadow: var(--shadow-sm); margin-bottom: 24px; overflow: hidden; transition: box-shadow 0.3s;
        }
        .filter-card:hover { box-shadow: var(--shadow-md); }
        .filter-header {
            background: linear-gradient(135deg, #1E293B, #334155); padding: 14px 22px;
            display: flex; align-items: center; gap: 10px; color: white; font-weight: 600; font-size: 13px;
        }
        .filter-header i { color: rgba(255,255,255,0.8); }
        .filter-body { padding: 20px 22px; }
        .filter-row { display: flex; gap: 14px; flex-wrap: wrap; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .filter-group input, .filter-group select {
            padding: 8px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-family: inherit; background: var(--bg-main); color: var(--text-main);
            outline: none; min-width: 140px; transition: all 0.2s;
        }
        .filter-group input:focus {
            border-color: var(--primary); background: var(--card-bg);
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        .filter-group input:hover { border-color: var(--text-subtle); }
        .filter-group input[type="date"] { min-width: 150px; }
        .filter-actions { display: flex; gap: 8px; align-items: end; }
        .btn-filter {
            padding: 8px 20px; background: var(--primary-gradient); color: white; border: none;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 6px; transition: all 0.2s;
            box-shadow: 0 2px 8px var(--primary-glow);
        }
        .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 4px 16px var(--primary-glow); }
        .btn-filter:active { transform: translateY(0); }
        .btn-filter-reset {
            padding: 8px 16px; background: var(--bg-main); color: var(--text-muted);
            border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;
            font-weight: 500; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-filter-reset:hover { background: var(--card-bg); border-color: var(--text-subtle); color: var(--text-main); }

        .table-card {
            background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border);
            box-shadow: var(--shadow-sm); overflow: hidden; transition: box-shadow 0.3s;
        }
        .table-card:hover { box-shadow: var(--shadow-md); }
        .table-toolbar {
            padding: 16px 22px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
            background: linear-gradient(180deg, rgba(248,250,252,0.5) 0%, transparent 100%);
        }
        .table-toolbar-left { font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .table-toolbar-left i { color: var(--primary); }
        .table-toolbar-left span { color: var(--text-muted); font-weight: 400; font-size: 13px; }
        .table-search-box {
            display: flex; align-items: center; border: 1.5px solid var(--border);
            border-radius: 999px; overflow: hidden; background: var(--bg-main); transition: all 0.2s;
        }
        .table-search-box:focus-within { border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-light); background: var(--card-bg); }
        .table-search-box i { padding: 0 12px; color: var(--text-subtle); font-size: 13px; }
        .table-search-box input { border: none; padding: 8px 12px 8px 0; font-size: 13px; font-family: inherit; outline: none; width: 200px; background: transparent; }
        .table-wrap { overflow-x: auto; }
        table { width: 100% !important; border-collapse: collapse; font-size: 13px; margin: 0 !important; }
        thead { background: linear-gradient(180deg, #F8FAFC, #F1F5F9); }
        th {
            padding: 12px 16px !important; text-align: left !important; font-weight: 600 !important;
            font-size: 11px !important; text-transform: uppercase !important; color: var(--text-muted) !important;
            letter-spacing: 0.05em !important; border-bottom: 2px solid var(--border) !important;
            white-space: nowrap;
        }
        th i { margin-right: 4px; font-size: 10px; }
        td { padding: 13px 16px !important; border-bottom: 1px solid var(--border-light) !important; vertical-align: middle !important; }
        tbody tr { transition: background 0.15s; }
        tbody tr:nth-child(even) td { background: rgba(248,250,252,0.5); }
        tbody tr:hover td { background: var(--primary-surface) !important; }
        tbody tr:last-child td { border-bottom: none !important; }
        .badge-komponen {
            display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
            border-radius: 20px; font-size: 11px; font-weight: 600;
            background: var(--primary-surface); color: var(--primary);
        }
        .patient-cell strong { font-size: 13px; color: var(--text-main); }
        .patient-cell .sub-text { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .mono { font-family: 'SF Mono', 'Consolas', monospace; font-size: 12px; font-weight: 500; color: var(--text-muted); }
        .btn-download {
            display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px;
            background: var(--primary-gradient); color: white; border: none; border-radius: 6px;
            font-size: 11px; font-weight: 600; text-decoration: none; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 2px 6px var(--primary-glow);
        }
        .btn-download:hover { transform: translateY(-1px); box-shadow: 0 4px 12px var(--primary-glow); }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 44px; color: var(--border); margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; }
        .dataTables_wrapper .dataTables_filter { display: none; }
        .dataTables_wrapper .dataTables_length { display: none; }
        .dataTables_wrapper .dataTables_info { display: none; }
        .dataTables_wrapper .dataTables_paginate { display: none; }
        nav[role="navigation"] { padding: 16px 22px; border-top: 1px solid var(--border); background: var(--bg-main); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }
        nav[role="navigation"] .flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        nav[role="navigation"] .text-sm { font-size: 12px; color: var(--text-muted); }
        nav[role="navigation"] .relative.z-0 { display: flex; gap: 4px; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; justify-content: center; min-width: 34px;
            height: 34px; padding: 0 10px; border: 1px solid var(--border); border-radius: 8px;
            font-size: 12px; font-weight: 500; color: var(--text-main); background: var(--card-bg);
            text-decoration: none; transition: all 0.2s;
        }
        nav[role="navigation"] a:hover { background: var(--primary-surface); border-color: var(--primary); color: var(--primary); }
        nav[role="navigation"] span[aria-current="page"] {
            background: var(--primary-gradient) !important; color: white !important;
            border-color: transparent !important; box-shadow: 0 2px 8px var(--primary-glow);
        }
        nav[role="navigation"] .disabled span, nav[role="navigation"] .disabled a { opacity: 0.4; pointer-events: none; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-header { animation: fadeInUp 0.4s ease-out; }
        .stats-grid { animation: fadeInUp 0.4s ease-out 0.1s both; }
        .filter-card { animation: fadeInUp 0.4s ease-out 0.15s both; }
        .table-card { animation: fadeInUp 0.4s ease-out 0.2s both; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-row { flex-direction: column; }
            .page-header { flex-direction: column; gap: 12px; }
            .page-header-left p { margin-left: 0; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.98); border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px 12px; border-radius: var(--radius-sm); background: var(--bg-main); border: none; color: var(--text-main); font-size: 18px; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 45; }
            .sidebar-overlay.active { display: block; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="mobile-header">
        <button class="hamburger" onclick="document.querySelector('.sidebar').classList.toggle('active');document.querySelector('.sidebar-overlay').classList.toggle('active')">
            <i class="fas fa-bars"></i>
        </button>
        <span style="font-weight:700;font-size:16px;">Riwayat Reaksi Transfusi</span>
        <div class="user-avatar" style="cursor:default;">{{ substr((string) Session::get('user_id', 'U'), 0, 1) }}</div>
    </div>
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('active');document.querySelector('.sidebar-overlay').classList.remove('active')"></div>

    <div class="main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="fas fa-history"></i> Riwayat Reaksi Transfusi</h1>
                <p><i class="fas fa-database" style="font-size:12px;margin-right:4px;color:var(--text-subtle);"></i> Daftar penelusuran reaksi transfusi darah yang telah dicatat</p>
            </div>
            <div class="page-header-right">
                <a href="{{ route('bank-darah.reaksi') }}" class="btn-primary-sm">
                    <i class="fas fa-plus"></i> Form Reaksi Baru
                </a>
                <div class="user-badge">
                    <div class="user-avatar">{{ substr((string) Session::get('user_id', 'U'), 0, 1) }}</div>
                    <span class="user-name">{{ Session::get('user_id', 'User') }}</span>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalData, 0, ',', '.') }}</h3>
                    <p>Total Reaksi Transfusi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalBulanIni, 0, ',', '.') }}</h3>
                    <p>Bulan Ini</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($data->total(), 0, ',', '.') }}</h3>
                    <p>Data Ditampilkan</p>
                </div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-header"><i class="fas fa-sliders-h"></i> Filter Pencarian</div>
            <div class="filter-body">
                <form method="GET" action="{{ route('bank-darah.reaksi.history') }}" id="filterForm">
                    <div class="filter-row">
                        <div class="filter-group"><label><i class="fas fa-calendar-alt" style="margin-right:3px;"></i> Tgl. Awal</label><input type="date" name="tgl_awal" value="{{ request('tgl_awal', date('Y-m-d')) }}"></div>
                        <div class="filter-group"><label><i class="fas fa-calendar-check" style="margin-right:3px;"></i> Tgl. Akhir</label><input type="date" name="tgl_akhir" value="{{ request('tgl_akhir', date('Y-m-d')) }}"></div>
                        <div class="filter-group" style="flex:1;min-width:180px;">
                            <label><i class="fas fa-search" style="margin-right:3px;"></i> Cari</label>
                            <input type="text" name="search" placeholder="Nama, No. RM, atau No. Reaksi..." value="{{ request('search') }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('bank-darah.reaksi.history') }}" class="btn-filter-reset"><i class="fas fa-undo"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <i class="fas fa-table"></i>
                    Daftar Formulir Reaksi Transfusi
                    <span>({{ number_format($data->total(), 0, ',', '.') }} data)</span>
                </div>
                <div class="table-toolbar-right">
                    <div class="table-search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tableSearch" placeholder="Cari dalam tabel..." autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="table-wrap">
                <table id="historyTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> No. Reaksi</th>
                            <th><i class="fas fa-calendar"></i> Tgl. Transfusi</th>
                            <th><i class="fas fa-user"></i> Pasien</th>
                            <th><i class="fas fa-door-open"></i> Ruangan</th>
                            <th><i class="fas fa-flask"></i> Komponen</th>
                            <th><i class="fas fa-barcode"></i> No. Kantong</th>
                            <th><i class="fas fa-user-md"></i> Pelapor</th>
                            <th style="text-align:center;"><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td><span class="mono">{{ $item->no_reaksi }}</span></td>
                                <td style="font-size:12px;">{{ $item->tanggal_transfusi ? \Carbon\Carbon::parse($item->tanggal_transfusi)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <div class="patient-cell">
                                        <strong>{{ $item->nama_pasien }}</strong>
                                        <div class="sub-text">
                                            @if($item->no_rekam_medis)
                                                RM: {{ $item->no_rekam_medis }}
                                            @endif
                                            @if($item->no_rawat && $item->no_rekam_medis) &middot; @endif
                                            @if($item->no_rawat)
                                                Rawat: {{ $item->no_rawat }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;">{{ $item->ruangan ?? '-' }}</td>
                                <td><span class="badge-komponen"><i class="fas fa-tint"></i> {{ $item->jenis_komponen }}</span></td>
                                <td style="font-size:12px;">{{ $item->barcode ?? '-' }}</td>
                                <td style="font-size:12px;">{{ $item->petugas_pelapor ?? '-' }}</td>
                                <td style="text-align:center;">
                                    <a href="{{ route('bank-darah.reaksi.download', $item->id) }}" class="btn-download" target="_blank">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($data) === 0)
                <div class="empty-state" style="padding:60px 20px;">
                    <i class="fas fa-inbox"></i>
                    <p style="font-size:14px;color:var(--text-muted);">Belum ada data penelusuran reaksi transfusi.</p>
                </div>
            @endif
            @if(method_exists($data, 'links'))
                {{ $data->links() }}
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#historyTable').DataTable({
                pageLength: 20,
                lengthMenu: [10, 20, 50, 100],
                order: [[0, 'desc']],
                dom: 'rt<"bottom"lip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari dalam tabel...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    zeroRecords: 'Tidak ada data yang cocok',
                    emptyTable: 'Belum ada data',
                    paginate: { first: 'Pertama', previous: 'Sebelumnya', next: 'Selanjutnya', last: 'Terakhir' }
                }
            });
            $('#tableSearch').on('keyup', function () {
                table.search(this.value).draw();
            });
        });
    </script>
</body>
</html>
