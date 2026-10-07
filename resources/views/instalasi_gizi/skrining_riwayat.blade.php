<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Asuhan Gizi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: rgba(16,185,129,0.10);
            --primary-glow: rgba(16,185,129,0.18);
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; }

        /* Sidebar Styling */
        .sidebar { width: 280px; background: rgba(255,255,255,0.98); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; }
        .nav-item:hover { background: #ECFDF5; color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }

        .main-content { margin-left: 280px; flex-grow: 1; padding: 30px; min-width: 0; width: calc(100% - 280px); transition: margin-left 0.25s ease; }

        /* Mobile Header */
        .mobile-header { display: none; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: white; border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 45; }
        .sidebar-overlay.active { display: block; }

        /* Media Queries */
        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding: 84px 20px 20px; }
        }

        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .header-actions { display: flex; gap: 10px; align-items: center; }
        .btn-new { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--primary); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; font-family: inherit; transition: all 0.2s; box-shadow: 0 4px 12px var(--primary-glow); }
        .btn-new:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
        .avatar { width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: white; }
        .user-info span { font-size: 13px; font-weight: 500; }

        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 20px 24px; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 24px; height: 24px; }
        .stat-icon.blue { background: rgba(16,185,129,0.12); color: var(--primary); }
        .stat-icon.green { background: rgba(16,185,129,0.12); color: #10b981; }
        .stat-icon.purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
        .stat-info .stat-value { font-size: 28px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
        .stat-info .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        .filter-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px; box-shadow: var(--shadow-sm); overflow: hidden; }
        .filter-head { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 10px; }
        .filter-head-left svg { width: 20px; height: 20px; color: rgba(255,255,255,0.9); }
        .filter-head-left span { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.02em; }
        .filter-head-badge { background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 20px; }
        .filter-body { padding: 18px 20px 20px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 14px; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; width: 100%; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px; }
        .filter-group label svg { width: 14px; height: 14px; color: var(--primary); }
        .filter-group select,
        .filter-group input { padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit; background: #FAFBFC; transition: all 0.2s; width: 100%; }
        .filter-group select:focus,
        .filter-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); background: #fff; }
        .filter-actions { display: flex; gap: 10px; align-items: center; padding-bottom: 1px; }
        .btn-filter { padding: 9px 22px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px var(--primary-glow); }
        .btn-filter svg { width: 16px; height: 16px; }
        .btn-reset { padding: 9px 18px; background: transparent; color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn-reset:hover { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }
        .btn-reset svg { width: 14px; height: 14px; }

        .table-wrap { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; }
        .table-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #F8FAFC; }
        th { padding: 13px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.04em; border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border-light); font-size: 13px; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(14,165,233,0.03); }
        .patient-cell { display: flex; align-items: center; gap: 12px; }
        .patient-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; flex-shrink: 0; }
        .patient-name { font-weight: 600; font-size: 13px; }
        .patient-rm { font-size: 11px; color: var(--text-muted); font-family: monospace; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #D1FAE5; color: #065F46; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-info { background: var(--primary-light); color: var(--primary); }
        .badge-muted { background: var(--bg-main); color: var(--text-muted); }
        .text-muted { color: var(--text-muted); }
        .text-xs { font-size: 12px; }
        .font-mono { font-family: monospace; font-size: 12px; }

        .pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .pagination-info { font-size: 13px; color: var(--text-muted); }
        .pagination-links { display: flex; gap: 4px; }
        .pagination-links a, .pagination-links span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 10px;
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500; color: var(--text-main); text-decoration: none;
            background: var(--card-bg); transition: all 0.15s;
        }
        .pagination-links a:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
        .pagination-links span.current { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
        .pagination-links span.disabled { opacity: 0.4; pointer-events: none; }

        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9999; display: none; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: #fff; border-radius: 20px; width: 850px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.25); animation: modalIn 0.25s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 1; border-radius: 20px 20px 0 0; }
        .modal-head h2 { font-size: 18px; font-weight: 700; }
        .modal-close { width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--bg-main); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 20px; }
        .modal-close:hover { background: var(--border); }
        .modal-body { padding: 24px; }
        .modal-section { margin-bottom: 20px; border: 1px solid var(--border); border-radius: 12px; padding: 18px; background: #FCFDFE; }
        .modal-section:last-child { margin-bottom: 0; }
        .modal-section-title { font-size: 13px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border-light); }
        .modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; }
        .modal-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px 24px; }
        .modal-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px 20px; }
        .modal-field .ml { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; }
        .modal-field .mv { font-size: 14px; margin-top: 2px; color: var(--text-main); line-height: 1.4; white-space: pre-wrap; font-weight: 500; }
        .modal-field.full { grid-column: 1 / -1; }

        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 84px 20px 20px; }
        }
        @media (max-width: 768px) {
            .modal-grid, .modal-grid-3, .modal-grid-4 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')
    
    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Riwayat Asuhan Gizi</h1>
                <p>Daftar riwayat pemeriksaan Asuhan Gizi pasien.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('gizi.skrining') }}" class="btn-new">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Asuhan Baru
                </a>
                <div class="user-profile">
                    <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                    <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $asuhan->total() }}</div>
                    <div class="stat-label">Total Dokumen</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $todayCount }}</div>
                    <div class="stat-label">Asuhan Hari Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ count($asuhan->items()) }}</div>
                    <div class="stat-label">Halaman Ini</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="filter-card">
            <div class="filter-head">
                <div class="filter-head-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter Pencarian</span>
                </div>
                <span class="filter-head-badge">{{ $asuhan->total() }} hasil</span>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('gizi.skrining.riwayat') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Tanggal Awal</label>
                            <input type="date" name="start_date" value="{{ $dateFrom }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $dateTo }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> No. Rawat</label>
                            <input type="text" name="no_rawat" placeholder="No. rawat / No. RM..." value="{{ request('no_rawat') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Nama Pasien</label>
                            <input type="text" name="nama_pasien" placeholder="Cari nama..." value="{{ request('nama_pasien') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Petugas</label>
                            <input type="text" name="petugas" placeholder="Nama / NIP petugas..." value="{{ request('petugas') }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>
                            <a href="{{ route('gizi.skrining.riwayat') }}" class="btn-reset">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th style="width:48px;">No</th>
                            <th>Pasien</th>
                            <th>No. Rawat</th>
                            <th>Ruangan</th>
                            <th>Tanggal & Jam</th>
                            <th>BB/TB (IMT)</th>
                            <th>Status Gizi</th>
                            <th>Diagnosis Gizi</th>
                            <th>Petugas</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asuhan as $item)
                            @php
                                $nmPasien = $item->nm_pasien ?? '—';
                                $noRM = $item->no_rkm_medis ?? '—';
                                $initial = strtoupper(substr($nmPasien, 0, 1));
                            @endphp
                            <tr>
                                <td style="color:var(--text-muted);font-weight:500;">{{ $loop->iteration + ($asuhan->currentPage() - 1) * $asuhan->perPage() }}</td>
                                <td>
                                    <div class="patient-cell">
                                        <div class="patient-avatar">{{ $initial }}</div>
                                        <div>
                                            <div class="patient-name">{{ $nmPasien }}</div>
                                            <div class="patient-rm">{{ $noRM }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="font-mono">{{ $item->no_rawat }}</span></td>
                                <td class="text-xs">{{ $item->ruangan ?: '—' }}</td>
                                <td class="text-xs" style="white-space:nowrap;">
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="text-xs">{{ $item->antropometri_bb }} kg / {{ $item->antropometri_tb }} cm ({{ $item->antropometri_imt }})</td>
                                <td><span class="badge badge-info">{{ $item->antropometri_statusgizi }}</span></td>
                                <td class="text-xs" style="max-width:180px;">
                                    <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:180px;" title="{{ $item->diagnosis }}">{{ $item->diagnosis ?: '—' }}</div>
                                </td>
                                <td class="text-xs">{{ $item->nama_petugas ?? $item->nip }}</td>
                                <td>
                                    <button class="btn" style="background: var(--primary-light); color: var(--primary); padding: 6px 14px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer;" onclick="openDetail('{{ $loop->index }}')">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                                    <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <div style="font-size:16px;font-weight:600;color:var(--text-main);">Belum Ada Data</div>
                                    <div style="font-size:13px;margin-top:4px;">Tidak ada asuhan gizi pada rentang tanggal tersebut.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($asuhan, 'lastPage') && $asuhan->lastPage() > 1)
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Menampilkan {{ $asuhan->firstItem() }}–{{ $asuhan->lastItem() }} dari {{ $asuhan->total() }} dokumen
                    </div>
                    <div class="pagination-links">
                        @if ($asuhan->onFirstPage())
                            <span class="disabled">&laquo;</span>
                        @else
                            <a href="{{ $asuhan->previousPageUrl() }}">&laquo;</a>
                        @endif

                        @php
                            $currentPage = $asuhan->currentPage();
                            $lastPage = $asuhan->lastPage();
                            $start = max($currentPage - 2, 1);
                            $end = min($currentPage + 2, $lastPage);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $asuhan->url(1) }}">1</a>
                            @if ($start > 2)
                                <span class="disabled">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $currentPage)
                                <span class="current">{{ $i }}</span>
                            @else
                                <a href="{{ $asuhan->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $lastPage)
                            @if ($end < $lastPage - 1)
                                <span class="disabled">...</span>
                            @endif
                            <a href="{{ $asuhan->url($lastPage) }}">{{ $lastPage }}</a>
                        @endif

                        @if ($asuhan->hasMorePages())
                            <a href="{{ $asuhan->nextPageUrl() }}">&raquo;</a>
                        @else
                            <span class="disabled">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal-overlay" id="detailModal">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Detail Pemeriksaan Asuhan Gizi</h2>
                <button class="modal-close" onclick="closeDetail()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                {{-- Demographics --}}
                <div class="modal-section">
                    <div class="modal-section-title">Informasi Pasien & Waktu</div>
                    <div class="modal-grid-4">
                        <div class="modal-field">
                            <div class="ml">Nama Pasien</div>
                            <div class="mv" id="d-nama-pasien">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">No. RM</div>
                            <div class="mv" id="d-no-rm">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">No. Rawat</div>
                            <div class="mv" id="d-no-rawat">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Gender & Umur</div>
                            <div class="mv" id="d-gender-umur">—</div>
                        </div>
                        <div class="modal-field span-2">
                            <div class="ml">Tanggal & Jam Catatan</div>
                            <div class="mv" id="d-tanggal">—</div>
                        </div>
                        <div class="modal-field span-2">
                            <div class="ml">Petugas Penanggung Jawab</div>
                            <div class="mv" id="d-petugas">—</div>
                        </div>
                    </div>
                </div>

                {{-- Antropometri --}}
                <div class="modal-section">
                    <div class="modal-section-title">Antropometri</div>
                    <div class="modal-grid-4">
                        <div class="modal-field">
                            <div class="ml">Berat Badan</div>
                            <div class="mv" id="d-bb">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Tinggi Badan</div>
                            <div class="mv" id="d-tb">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">IMT</div>
                            <div class="mv" id="d-imt">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Status Gizi</div>
                            <div class="mv" id="d-statusgizi">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">LiLa / TL / ULNA</div>
                            <div class="mv" id="d-lila-tl-ulna">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">BB Ideal</div>
                            <div class="mv" id="d-bbideal">—</div>
                        </div>
                        <div class="modal-field span-2">
                            <div class="ml">BB/U - TB/U - BB/TB - LiLa/U</div>
                            <div class="mv" id="d-sd-values">—</div>
                        </div>
                    </div>
                </div>

                {{-- Laboratorium & Klinik --}}
                <div class="modal-grid">
                    <div class="modal-section" style="margin-bottom: 0;">
                        <div class="modal-section-title">Biokimia (Laboratorium)</div>
                        <div class="modal-field">
                            <div class="mv" id="d-biokimia">—</div>
                        </div>
                    </div>
                    <div class="modal-section" style="margin-bottom: 0;">
                        <div class="modal-section-title">Fisik / Klinis</div>
                        <div class="modal-field">
                            <div class="mv" id="d-fisik">—</div>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Gizi & Personal --}}
                <div class="modal-section" style="margin-top: 20px;">
                    <div class="modal-section-title">Riwayat Gizi & Alergi Makanan</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">Alergi Makanan</div>
                            <div class="mv" id="d-alergi">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Pola Makan</div>
                            <div class="mv" id="d-polamakan">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Riwayat Personal</div>
                            <div class="mv" id="d-riwayat-personal">—</div>
                        </div>
                    </div>
                </div>

                {{-- Diagnosis, Intervensi, Monitoring --}}
                <div class="modal-section">
                    <div class="modal-section-title">Diagnosis, Intervensi, Monitoring & Evaluasi</div>
                    <div class="modal-grid">
                        <div class="modal-field full">
                            <div class="ml">Diagnosis Gizi</div>
                            <div class="mv" id="d-diagnosis">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Intervensi Gizi</div>
                            <div class="mv" id="d-intervensi">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Monitoring & Evaluasi</div>
                            <div class="mv" id="d-monitoring">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var asuhanData = @json($asuhan->items());

        function openDetail(index) {
            var item = asuhanData[index];
            if (!item) return;

            document.getElementById('d-nama-pasien').textContent = item.nm_pasien || '—';
            document.getElementById('d-no-rm').textContent = item.no_rkm_medis || '—';
            document.getElementById('d-no-rawat').textContent = item.no_rawat || '—';
            
            var age = '';
            if (item.tgl_lahir) {
                var birth = new Date(item.tgl_lahir);
                var now = new Date();
                var ageNum = now.getFullYear() - birth.getFullYear();
                var m = now.getMonth() - birth.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < birth.getDate())) {
                    ageNum--;
                }
                age = ageNum + ' tahun';
            }
            var gender = item.jk === 'L' ? 'Laki-laki' : (item.jk === 'P' ? 'Perempuan' : '—');
            document.getElementById('d-gender-umur').textContent = gender + (age ? ', ' + age : '');
            
            document.getElementById('d-tanggal').textContent = item.tanggal ? fmtDate(item.tanggal) : '—';
            document.getElementById('d-petugas').textContent = item.nama_petugas ? item.nama_petugas + ' (' + item.nip + ')' : item.nip || '—';
            
            document.getElementById('d-bb').textContent = (item.antropometri_bb || '—') + ' Kg';
            document.getElementById('d-tb').textContent = (item.antropometri_tb || '—') + ' Cm';
            document.getElementById('d-imt').textContent = (item.antropometri_imt || '—') + ' Kg/m²';
            document.getElementById('d-statusgizi').textContent = item.antropometri_statusgizi || '—';

            document.getElementById('d-lila-tl-ulna').textContent = [item.antropometri_lila, item.antropometri_tl, item.antropometri_ulna].map(v => v || '—').join(' / ');
            document.getElementById('d-bbideal').textContent = (item.antropometri_bbideal || '—') + ' Kg';
            document.getElementById('d-sd-values').textContent = [item.antropometri_bbperu, item.antropometri_tbperu, item.antropometri_bbpertb, item.antropometri_llaperu].map(v => v || '—').join(' / ');

            document.getElementById('d-biokimia').textContent = item.biokimia || '—';
            document.getElementById('d-fisik').textContent = item.fisik_klinis || '—';

            var alergi = [];
            ['telur', 'susu_sapi', 'kacang', 'gluten', 'udang', 'ikan', 'hazelnut'].forEach(function(k) {
                var v = item['alergi_' + k];
                if (v === 'Ya') alergi.push(k.replace('_sapi', ' sapi'));
            });
            document.getElementById('d-alergi').textContent = alergi.length ? alergi.join(', ') : 'Tidak ada alergi';

            document.getElementById('d-polamakan').textContent = item.pola_makan || '—';
            document.getElementById('d-riwayat-personal').textContent = item.riwayat_personal || '—';

            document.getElementById('d-diagnosis').textContent = item.diagnosis || '—';
            document.getElementById('d-intervensi').textContent = item.intervensi_gizi || '—';
            document.getElementById('d-monitoring').textContent = item.monitoring_evaluasi || '—';

            document.getElementById('detailModal').classList.add('active');
        }

        function fmtDate(dt) {
            if (!dt) return '—';
            var d = new Date(dt.replace(/-/g, '/'));
            if (isNaN(d.getTime())) return dt;
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            var hh = String(d.getHours()).padStart(2, '0');
            var min = String(d.getMinutes()).padStart(2, '0');
            var sec = String(d.getSeconds()).padStart(2, '0');
            return dd + '/' + mm + '/' + d.getFullYear() + ' ' + hh + ':' + min + ':' + sec;
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('active');
        }

        document.getElementById('detailModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeDetail();
        });
    </script>
</body>
</html>
