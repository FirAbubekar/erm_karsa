<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Transfer Pasien Antar Ruang</title>
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
        body { background: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif; }
        .sidebar { width: var(--sidebar-width); background: rgba(255,255,255,0.98); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; }
        .nav-item:hover { background: #ECFDF5; color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; }

        /* Header */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .header-actions { display: flex; gap: 10px; align-items: center; }
        .btn-new { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--primary); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; font-family: inherit; transition: all 0.2s; box-shadow: 0 4px 12px var(--primary-glow); }
        .btn-new:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 6px 16px var(--primary-glow); }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
        .avatar { width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: white; }
        .user-info span { font-size: 13px; font-weight: 500; }

        /* Stats */
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 20px 24px; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 16px; transition: all 0.2s; }
        .stat-card:hover { border-color: var(--primary); box-shadow: var(--shadow-md); }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 24px; height: 24px; }
        .stat-icon.blue { background: rgba(16,185,129,0.12); color: var(--primary); }
        .stat-icon.green { background: rgba(16,185,129,0.12); color: #10b981; }
        .stat-icon.purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
        .stat-info .stat-value { font-size: 28px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
        .stat-info .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        /* Filter card */
        .filter-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px; box-shadow: var(--shadow-sm); overflow: hidden; }
        .filter-head { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 10px; }
        .filter-head-left svg { width: 20px; height: 20px; color: rgba(255,255,255,0.9); }
        .filter-head-left span { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.02em; }
        .filter-head-badge { background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 20px; }
        .filter-body { padding: 18px 20px 20px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; align-items: end; }
        .filter-row + .filter-row { margin-top: 12px; padding-top: 14px; border-top: 1px dashed var(--border-light); }
        .filter-group { display: flex; flex-direction: column; gap: 5px; width: 100%; }
        .filter-group select,
        .filter-group input { width: 100%; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px; }
        .filter-group label svg { width: 14px; height: 14px; color: var(--primary); }
        .filter-group select,
        .filter-group input { padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit; background: #FAFBFC; transition: all 0.2s; }
        .filter-group select:hover,
        .filter-group input:hover { border-color: #cbd5e1; background: #fff; }
        .filter-group select:focus,
        .filter-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); background: #fff; }
        .filter-group input::placeholder { color: #94a3b8; }
        .filter-actions { display: flex; gap: 10px; align-items: center; padding-bottom: 1px; }
        .btn-filter { padding: 9px 22px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px var(--primary-glow); }
        .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px var(--primary-glow); }
        .btn-filter svg { width: 16px; height: 16px; }
        .btn-reset { padding: 9px 18px; background: transparent; color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn-reset:hover { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }
        .btn-reset svg { width: 14px; height: 14px; }

        /* Table */
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
        .badge-info { background: var(--primary-light); color: var(--primary); }
        .badge-muted { background: var(--bg-main); color: var(--text-muted); }
        .btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--card-bg); cursor: pointer; color: var(--text-muted); transition: all 0.15s; }
        .btn-icon:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
        .text-muted { color: var(--text-muted); }
        .text-xs { font-size: 12px; }
        .font-mono { font-family: monospace; font-size: 12px; }

        /* Pagination */
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

        /* Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9999; display: none; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: #fff; border-radius: 20px; width: 720px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px rgba(0,0,0,0.25); animation: modalIn 0.25s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 1; border-radius: 20px 20px 0 0; }
        .modal-head h2 { font-size: 18px; font-weight: 700; }
        .modal-close { width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--bg-main); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 20px; transition: all 0.15s; }
        .modal-close:hover { background: var(--border); }
        .modal-body { padding: 24px; }
        .modal-section { margin-bottom: 20px; }
        .modal-section:last-child { margin-bottom: 0; }
        .modal-section-title { font-size: 13px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border-light); }
        .modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; }
        .modal-field .ml { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; }
        .modal-field .mv { font-size: 14px; margin-top: 2px; color: var(--text-main); line-height: 1.4; }
        .modal-field.full { grid-column: 1 / -1; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; }
            .page-header { flex-direction: column; gap: 16px; }
            .stats-row { grid-template-columns: 1fr; }
            .filter-grid { flex-direction: column; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.98); border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px; border-radius: var(--radius-sm); background: var(--bg-main); border: none; color: var(--text-main); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 45; }
            .sidebar-overlay.active { display: block; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')
    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Riwayat Transfer Pasien</h1>
                <p>Daftar pasien rawat inap aktif yang telah menjalani transfer antar ruangan.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('transfer-pasien.index') }}" class="btn-new">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Transfer Baru
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
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalActive }}</div>
                    <div class="stat-label">Pasien Rawat Inap Aktif</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $todayTransfers }}</div>
                    <div class="stat-label">Transfer Hari Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalTransfers }}</div>
                    <div class="stat-label">Total Dokumen</div>
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
                <span class="filter-head-badge">{{ $transfers->total() }} hasil</span>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('transfer-pasien.riwayat') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Tanggal Awal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> No. Rawat</label>
                            <input type="text" name="no_rawat" placeholder="No. rawat / No. RM..." value="{{ request('no_rawat') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Nama Pasien</label>
                            <input type="text" name="nama_pasien" placeholder="Cari nama..." value="{{ request('nama_pasien') }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Asal Ruang</label>
                            <select name="asal_ruang">
                                <option value="">Semua Ruang</option>
                                @foreach($bangsalList as $nm)
                                    <option value="{{ $nm }}" {{ request('asal_ruang') == $nm ? 'selected' : '' }}>{{ $nm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Tujuan Ruang</label>
                            <select name="tujuan_ruang">
                                <option value="">Semua Ruang</option>
                                @foreach($bangsalList as $nm)
                                    <option value="{{ $nm }}" {{ request('tujuan_ruang') == $nm ? 'selected' : '' }}>{{ $nm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Status</label>
                            <select name="status_persetujuan">
                                <option value="">Semua Status</option>
                                <option value="Ya" {{ request('status_persetujuan') == 'Ya' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Tidak" {{ request('status_persetujuan') == 'Tidak' ? 'selected' : '' }}>Tidak Disetujui</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <a href="{{ route('transfer-pasien.riwayat') }}" class="btn-reset">
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
                            <th>Tgl. Transfer</th>
                            <th>Asal Ruang</th>
                            <th>Tujuan Ruang</th>
                            <th>Perawat</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $item)
                            @php
                                $nmPasien = $item->regPeriksa?->pasien?->nm_pasien ?? '—';
                                $noRM = $item->regPeriksa?->pasien?->no_rkm_medis ?? '—';
                                $initial = strtoupper(substr($nmPasien, 0, 1));
                            @endphp
                            <tr>
                                <td style="color:var(--text-muted);font-weight:500;">{{ $loop->iteration + ($transfers->currentPage() - 1) * $transfers->perPage() }}</td>
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
                                <td class="text-xs" style="white-space:nowrap;">
                                    {{ $item->tanggal_pindah ? \Carbon\Carbon::parse($item->tanggal_pindah)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>{{ $item->asal_ruang ?? $item->asalBangsal?->nm_bangsal ?? '—' }}</td>
                                <td>{{ $item->ruang_selanjutnya ?? $item->tujuanBangsal?->nm_bangsal ?? '—' }}</td>
                                <td class="text-xs">
                                    <div>Serah: <strong>{{ $item->perawatMenyerahkan?->nama ?? '—' }}</strong></div>
                                    <div style="margin-top:2px;">Terima: <strong>{{ $item->perawatMenerima?->nama ?? '—' }}</strong></div>
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px;">
                                        <button class="btn-icon" onclick="openDetail('{{ $loop->index }}')" title="Lihat detail">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <button class="btn-icon" style="color:#10b981;border-color:#D1FAE5;" onclick="openEdit('{{ $loop->index }}')" title="Edit keadaan setelah transfer">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
                                    <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <div style="font-size:16px;font-weight:600;color:var(--text-main);">Belum Ada Data</div>
                                    <div style="font-size:13px;margin-top:4px;">Tidak ada transfer pasien untuk pasien yang masih dirawat.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($transfers, 'lastPage') && $transfers->lastPage() > 1)
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Menampilkan {{ $transfers->firstItem() }}–{{ $transfers->lastItem() }} dari {{ $transfers->total() }} dokumen
                    </div>
                    <div class="pagination-links">
                        @if ($transfers->onFirstPage())
                            <span class="disabled">&laquo;</span>
                        @else
                            <a href="{{ $transfers->previousPageUrl() }}">&laquo;</a>
                        @endif
                        @for ($i = 1; $i <= $transfers->lastPage(); $i++)
                            @if ($i == $transfers->currentPage())
                                <span class="current">{{ $i }}</span>
                            @else
                                <a href="{{ $transfers->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor
                        @if ($transfers->hasMorePages())
                            <a href="{{ $transfers->nextPageUrl() }}">&raquo;</a>
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
                <h2>Detail Transfer Pasien</h2>
                <button class="modal-close" onclick="closeDetail()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                <div class="modal-section">
                    <div class="modal-section-title">Informasi Registrasi</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">No. Rawat</div>
                            <div class="mv" id="d-no-rawat">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Tanggal Masuk</div>
                            <div class="mv" id="d-tgl-masuk">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Tanggal Pindah</div>
                            <div class="mv" id="d-tgl-pindah">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Indikasi Pindah</div>
                            <div class="mv" id="d-indikasi">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Ruangan</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">Asal Ruang</div>
                            <div class="mv" id="d-asal">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Tujuan Ruang</div>
                            <div class="mv" id="d-tujuan">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Metode Pemindahan</div>
                            <div class="mv" id="d-metode">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Diagnosa & Prosedur</div>
                    <div class="modal-grid">
                        <div class="modal-field full">
                            <div class="ml">Diagnosa Utama</div>
                            <div class="mv" id="d-diagnosa-utama">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Diagnosa Sekunder</div>
                            <div class="mv" id="d-diagnosa-sekunder">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Prosedur</div>
                            <div class="mv" id="d-prosedur">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Obat Diberikan</div>
                            <div class="mv" id="d-obat">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Keadaan Sebelum Transfer</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">Keadaan Umum</div>
                            <div class="mv" id="d-keadaan-sebelum">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">TD / Nadi / RR / Suhu</div>
                            <div class="mv" id="d-vital-sebelum">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Keluhan Utama</div>
                            <div class="mv" id="d-keluhan-sebelum">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Keadaan Setelah Transfer</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">Keadaan Umum</div>
                            <div class="mv" id="d-keadaan-setelah">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">TD / Nadi / RR / Suhu</div>
                            <div class="mv" id="d-vital-setelah">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Keluhan Utama</div>
                            <div class="mv" id="d-keluhan-setelah">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Persetujuan & Petugas</div>
                    <div class="modal-grid">
                        <div class="modal-field">
                            <div class="ml">Persetujuan</div>
                            <div class="mv" id="d-persetujuan">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Nama Penyetuju</div>
                            <div class="mv" id="d-nama-pj">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Hubungan</div>
                            <div class="mv" id="d-hubungan-pj">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Perawat Menyerahkan</div>
                            <div class="mv" id="d-perawat-serah">—</div>
                        </div>
                        <div class="modal-field">
                            <div class="ml">Perawat Menerima</div>
                            <div class="mv" id="d-perawat-terima">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <div class="modal-section-title">Peralatan</div>
                    <div class="modal-grid">
                        <div class="modal-field full">
                            <div class="ml">Peralatan Menyertai</div>
                            <div class="mv" id="d-peralatan">—</div>
                        </div>
                        <div class="modal-field full">
                            <div class="ml">Keterangan</div>
                            <div class="mv" id="d-ket-peralatan">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Keadaan Setelah Transfer Modal --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal-card" style="width:560px;">
            <div class="modal-head">
                <h2>Edit Keadaan Setelah Transfer</h2>
                <button class="modal-close" onclick="closeEdit()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="no_rawat" id="edit-no-rawat">
                    <input type="hidden" name="tanggal_masuk" id="edit-tgl-masuk">

                    <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;" id="edit-patient-info">—</p>

                    {{-- Collapsible: Data Sebelumnya (readonly) --}}
                    <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;margin-bottom:18px;">
                        <div onclick="togglePrevData()" style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#F8FAFC;cursor:pointer;user-select:none;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <svg width="16" height="16" fill="none" stroke="var(--text-muted)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span style="font-size:13px;font-weight:600;color:var(--text-main);">Data Sebelumnya</span>
                            </div>
                            <svg id="prev-chevron" width="16" height="16" fill="none" stroke="var(--text-muted)" stroke-width="2.5" viewBox="0 0 24 24" style="transition:transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div id="prev-data-body" style="padding:14px;display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;border-top:1px solid var(--border-light);">
                            <div><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Keadaan Umum</span><br><span id="prev-keadaan" style="font-size:13px;">—</span></div>
                            <div><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">TD</span><br><span id="prev-td" style="font-size:13px;">—</span></div>
                            <div><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Nadi</span><br><span id="prev-nadi" style="font-size:13px;">—</span></div>
                            <div><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">RR</span><br><span id="prev-rr" style="font-size:13px;">—</span></div>
                            <div><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Suhu</span><br><span id="prev-suhu" style="font-size:13px;">—</span></div>
                            <div style="grid-column:1/-1;"><span style="font-size:10px;font-weight:600;color:var(--text-muted);text-transform:uppercase;">Keluhan Utama</span><br><span id="prev-keluhan" style="font-size:13px;">—</span></div>
                        </div>
                    </div>

                    {{-- Editable fields --}}
                    <div style="font-size:13px;font-weight:600;color:var(--text-main);margin-bottom:10px;">Input Baru</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="filter-group">
                            <label>Keadaan Umum</label>
                            <select name="keadaan_umum_setelah" id="edit-keadaan">
                                <option value="">-</option>
                                <option value="Compos Mentis">Compos Mentis</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Sopor">Sopor</option>
                                <option value="Koma">Koma</option>
                                <option value="Apatis">Apatis</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>TD (mmHg)</label>
                            <input type="text" name="td_setelah" id="edit-td" placeholder="120/80">
                        </div>
                        <div class="filter-group">
                            <label>Nadi (x/menit)</label>
                            <input type="text" name="nadi_setelah" id="edit-nadi" placeholder="80">
                        </div>
                        <div class="filter-group">
                            <label>RR (x/menit)</label>
                            <input type="text" name="rr_setelah" id="edit-rr" placeholder="20">
                        </div>
                        <div class="filter-group">
                            <label>Suhu (°C)</label>
                            <input type="text" name="suhu_setelah" id="edit-suhu" placeholder="36.5">
                        </div>
                        <div class="filter-group" style="grid-column:1/-1;">
                            <label>Keluhan Utama</label>
                            <textarea name="keluhan_setelah" id="edit-keluhan" rows="2" style="padding:9px 12px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:13px;font-family:inherit;width:100%;resize:vertical;" placeholder="Keluhan utama pasien setelah transfer"></textarea>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;justify-content:end;margin-top:18px;padding-top:14px;border-top:1px solid var(--border-light);">
                        <button type="button" class="btn-reset" onclick="closeEdit()">Batal</button>
                        <button type="submit" class="btn-filter" id="btn-save-edit">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            Simpan
                        </button>
                    </div>
                </form>
                <div id="edit-result" style="display:none;margin-top:16px;padding:12px 16px;border-radius:var(--radius-sm);font-size:14px;font-weight:500;"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var transferData = @json($transfers->items());

        function openDetail(index) {
            var item = transferData[index];
            if (!item) return;

            document.getElementById('d-no-rawat').textContent = item.no_rawat || '—';
            document.getElementById('d-tgl-masuk').textContent = item.tanggal_masuk ? fmt(item.tanggal_masuk) : '—';
            document.getElementById('d-tgl-pindah').textContent = item.tanggal_pindah ? fmt(item.tanggal_pindah) : '—';
            document.getElementById('d-indikasi').textContent = item.indikasi_pindah_ruang || '—';
            document.getElementById('d-asal').textContent = item.asal_ruang || item.asal_bangsal?.nm_bangsal || '—';
            document.getElementById('d-tujuan').textContent = item.ruang_selanjutnya || item.tujuan_bangsal?.nm_bangsal || '—';
            document.getElementById('d-metode').textContent = item.metode_pemindahan_pasien || '—';
            document.getElementById('d-diagnosa-utama').textContent = item.diagnosa_utama || '—';
            document.getElementById('d-diagnosa-sekunder').textContent = item.diagnosa_sekunder || '—';
            document.getElementById('d-prosedur').textContent = item.prosedur_yang_sudah_dilakukan || '—';
            document.getElementById('d-obat').textContent = item.obat_yang_telah_diberikan || '—';
            document.getElementById('d-keadaan-sebelum').textContent = item.keadaan_umum_sebelum_transfer || '—';
            document.getElementById('d-vital-sebelum').textContent = [item.td_sebelum_transfer || '—', item.nadi_sebelum_transfer || '—', item.rr_sebelum_transfer || '—', item.suhu_sebelum_transfer || '—'].join(' / ');
            document.getElementById('d-keluhan-sebelum').textContent = item.keluhan_utama_sebelum_transfer || '—';
            document.getElementById('d-keadaan-setelah').textContent = item.keadaan_umum_sesudah_transfer || '—';
            document.getElementById('d-vital-setelah').textContent = [item.td_sesudah_transfer || '—', item.nadi_sesudah_transfer || '—', item.rr_sesudah_transfer || '—', item.suhu_sesudah_transfer || '—'].join(' / ');
            document.getElementById('d-keluhan-setelah').textContent = item.keluhan_utama_sesudah_transfer || '—';
            document.getElementById('d-persetujuan').textContent = item.pasien_keluarga_menyetujui === 'Ya' ? 'Disetujui' : 'Tidak Disetujui';
            document.getElementById('d-nama-pj').textContent = item.nama_menyetujui || '—';
            document.getElementById('d-hubungan-pj').textContent = item.hubungan_menyetujui || '—';
            document.getElementById('d-perawat-serah').textContent = item.perawat_menyerahkan?.nama || '—';
            document.getElementById('d-perawat-terima').textContent = item.perawat_menerima?.nama || '—';
            document.getElementById('d-peralatan').textContent = item.peralatan_yang_menyertai || '—';
            document.getElementById('d-ket-peralatan').textContent = item.keterangan_peralatan_yang_menyertai || '—';

            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('active');
        }

        document.getElementById('detailModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeDetail();
        });

        function openEdit(index) {
            var item = transferData[index];
            if (!item) return;

            document.getElementById('edit-no-rawat').value = item.no_rawat;
            document.getElementById('edit-tgl-masuk').value = item.tanggal_masuk;
            document.getElementById('edit-patient-info').textContent =
                (item.reg_periksa?.pasien?.nm_pasien || '—') + ' (' + (item.reg_periksa?.pasien?.no_rkm_medis || '—') + ') | No. Rawat: ' + item.no_rawat;

            // Previous data (readonly)
            document.getElementById('prev-keadaan').textContent = item.keadaan_umum_sesudah_transfer || '—';
            document.getElementById('prev-td').textContent = item.td_sesudah_transfer || '—';
            document.getElementById('prev-nadi').textContent = item.nadi_sesudah_transfer || '—';
            document.getElementById('prev-rr').textContent = item.rr_sesudah_transfer || '—';
            document.getElementById('prev-suhu').textContent = item.suhu_sesudah_transfer || '—';
            document.getElementById('prev-keluhan').textContent = item.keluhan_utama_sesudah_transfer || '—';

            // Editable fields
            document.getElementById('edit-keadaan').value = item.keadaan_umum_sesudah_transfer || '';
            document.getElementById('edit-td').value = item.td_sesudah_transfer || '';
            document.getElementById('edit-nadi').value = item.nadi_sesudah_transfer || '';
            document.getElementById('edit-rr').value = item.rr_sesudah_transfer || '';
            document.getElementById('edit-suhu').value = item.suhu_sesudah_transfer || '';
            document.getElementById('edit-keluhan').value = item.keluhan_utama_sesudah_transfer || '';

            // Reset previous data section to expanded
            var prevBody = document.getElementById('prev-data-body');
            prevBody.style.display = 'grid';
            document.getElementById('prev-chevron').style.transform = 'rotate(0deg)';

            var resultDiv = document.getElementById('edit-result');
            resultDiv.style.display = 'none';

            document.getElementById('editModal').classList.add('active');
        }

        function togglePrevData() {
            var body = document.getElementById('prev-data-body');
            var ch = document.getElementById('prev-chevron');
            if (body.style.display === 'none') {
                body.style.display = 'grid';
                ch.style.transform = 'rotate(0deg)';
            } else {
                body.style.display = 'none';
                ch.style.transform = 'rotate(-90deg)';
            }
        }

        function closeEdit() {
            document.getElementById('editModal').classList.remove('active');
        }

        document.getElementById('editModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeEdit();
        });

        document.getElementById('editForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            var btn = document.getElementById('btn-save-edit');
            btn.disabled = true;
            btn.innerHTML = '<svg class="spin" style="width:16px;height:16px;animation:spin 1s linear infinite;display:inline-block;vertical-align:middle;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-dasharray="40" stroke-dashoffset="15"/></svg> Menyimpan...';

            var resultDiv = document.getElementById('edit-result');
            resultDiv.style.display = 'none';

            try {
                var formData = new FormData(this);
                var resp = await fetch('/transfer-pasien/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: formData
                });
                var json = await resp.json();
                if (resp.ok && json.success) {
                    resultDiv.style.display = 'block';
                    resultDiv.style.background = '#D1FAE5';
                    resultDiv.style.color = '#065F46';
                    resultDiv.textContent = json.message;
                    // Update local data
                    var idx = transferData.findIndex(function(d) { return d.no_rawat === formData.get('no_rawat') && d.tanggal_masuk === formData.get('tanggal_masuk'); });
                    if (idx !== -1) {
                        transferData[idx].keadaan_umum_sesudah_transfer = formData.get('keadaan_umum_setelah');
                        transferData[idx].td_sesudah_transfer = formData.get('td_setelah');
                        transferData[idx].nadi_sesudah_transfer = formData.get('nadi_setelah');
                        transferData[idx].rr_sesudah_transfer = formData.get('rr_setelah');
                        transferData[idx].suhu_sesudah_transfer = formData.get('suhu_setelah');
                        transferData[idx].keluhan_utama_sesudah_transfer = formData.get('keluhan_setelah');
                    }
                    setTimeout(function() { closeEdit(); }, 1500);
                } else {
                    resultDiv.style.display = 'block';
                    resultDiv.style.background = '#FEE2E2';
                    resultDiv.style.color = '#991B1B';
                    resultDiv.textContent = json.message || 'Gagal menyimpan.';
                }
            } catch (err) {
                resultDiv.style.display = 'block';
                resultDiv.style.background = '#FEE2E2';
                resultDiv.style.color = '#991B1B';
                resultDiv.textContent = 'Terjadi kesalahan jaringan.';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> Simpan';
            }
        });

        function fmt(d) {
            if (!d) return '—';
            var dt = new Date(d.replace(' ', 'T'));
            if (isNaN(dt)) return d;
            var pad = function (n) { return ('0' + n).slice(-2); };
            return pad(dt.getDate()) + '/' + pad(dt.getMonth()+1) + '/' + dt.getFullYear() + ' ' + pad(dt.getHours()) + ':' + pad(dt.getMinutes());
        }
    </script>
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</body>
</html>
