<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Permintaan Gizi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Select2 Premium Theme overrides */
        .select2-container--default .select2-selection--single {
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 6px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: var(--text-main) !important;
            background: #FAFBFC !important;
            transition: all 0.2s !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary) !important;
            background: white !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-lg) !important;
            overflow: hidden !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--border) !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-main) !important;
        }

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
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background-color: #ffffff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 700px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: modalSlide 0.3s ease-out;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
        @keyframes modalSlide {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            padding: 18px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .modal-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
        .modal-body {
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        /* Detail Groups */
        .detail-section {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: #FAFBFC;
            overflow: hidden;
        }
        .detail-section-title {
            padding: 10px 16px;
            background: #F1F5F9;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }
        .detail-grid {
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .detail-item.full-width {
            grid-column: span 2;
        }
        .detail-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .detail-val {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
        }
        .detail-val-highlight {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-dark);
        }
        .modal-footer {
            padding: 16px 24px;
            background: #F8FAFC;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }
        .btn-modal-close {
            padding: 8px 18px;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-modal-close:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
        }
        .btn-detail-modal:hover {
            background-color: #ECFDF5 !important;
            color: var(--primary-dark) !important;
        }
        body.modal-open {
            overflow: hidden !important;
            height: 100vh !important;
        }

        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        .filter-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px; box-shadow: var(--shadow-sm); overflow: hidden; }
        .filter-head { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 10px; }
        .filter-head-left svg { width: 20px; height: 20px; color: rgba(255,255,255,0.9); }
        .filter-head-left span { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.02em; }
        .filter-body { padding: 18px 20px 20px; }
        
        .filter-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px; }
        .filter-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .filter-group { display: flex; flex-direction: column; gap: 6px; width: 100%; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px; }
        .filter-group label svg { width: 14px; height: 14px; color: var(--primary); }
        .filter-group select,
        .filter-group input { padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit; background: #FAFBFC; transition: all 0.2s; width: 100%; height: 42px; }
        .filter-group select:focus,
        .filter-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); background: #fff; }
        
        .filter-buttons { display: flex; gap: 8px; justify-content: flex-start; }
        .btn-filter { padding: 9px 22px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px var(--primary-glow); height: 38px; }
        .btn-filter svg { width: 16px; height: 16px; }
        .btn-reset { padding: 9px 18px; background: transparent; color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; height: 38px; }
        .btn-reset:hover { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }
        .btn-reset svg { width: 14px; height: 14px; }

        .table-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-sm); }
        
        /* Badges styling */
        .badge-waktu { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-waktu.pagi { background: #FEF3C7; color: #D97706; }
        .badge-waktu.siang { background: #DBEAFE; color: #2563EB; }
        .badge-waktu.sore { background: #F3E8FF; color: #7C3AED; }

        .badge-diet { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #ECFDF5; color: #047857; border: 1.5px solid #A7F3D0; margin: 1px; }

        .bed-badge { background: #F8FAFC; border: 1px solid #E2E8F0; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #475569; }

        .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }

        /* Datatables Overrides */
        table.dataTable { border-collapse: collapse !important; border-spacing: 0 !important; width: 100% !important; margin: 15px 0 !important; }
        table.dataTable thead th { background: #FAFBFC !important; border-bottom: 1px solid var(--border) !important; color: var(--text-muted) !important; font-weight: 700 !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; padding: 14px 16px !important; text-align: left !important; }
        table.dataTable tbody td { border-bottom: 1px solid var(--border-light) !important; padding: 14px 16px !important; font-size: 13px !important; color: var(--text-main) !important; vertical-align: middle !important; }
        table.dataTable tbody tr:hover { background-color: #F8FAFC !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary) !important; border-color: var(--primary) !important; color: white !important; border-radius: var(--radius-sm) !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: var(--primary-dark) !important; border-color: var(--primary-dark) !important; color: white !important; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--border) !important; border-radius: var(--radius-sm) !important; padding: 6px 12px !important; outline: none !important; }
    </style>
</head>
<body>
    @include('partials.sidebar')

    {{-- Mobile header toggle --}}
    <div class="mobile-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="logo-box">G</div>
            <span class="logo-text">Gizi</span>
        </div>
        <button id="menu-toggle" style="background:none; border:none; color:var(--text-main); cursor:pointer;">
            <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
    <div class="sidebar-overlay" id="overlay"></div>

    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Riwayat Permintaan Gizi</h1>
                <p>Monitoring dan data rekapitulasi seluruh permintaan diet gizi pasien rawat inap.</p>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="filter-card">
            <div class="filter-head">
                <div class="filter-head-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"></path></svg>
                    <span>Filter Lanjutan Riwayat Permintaan</span>
                </div>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('gizi.permintaan.riwayat-semua') }}">
                    <div class="filter-row">
                        {{-- Periode Tanggal --}}
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg> Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $dateFrom }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg> Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $dateTo }}">
                        </div>
                    </div>

                    <div class="filter-grid-3" style="margin-bottom: 20px;">
                        {{-- Ruangan --}}
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg> Ruangan</label>
                            <select name="bangsal" id="select-bangsal" class="select2">
                                <option value="">Semua Ruangan...</option>
                                @foreach($bangsalList as $b)
                                    <option value="{{ $b->kd_bangsal }}" {{ request('bangsal') == $b->kd_bangsal ? 'selected' : '' }}>{{ $b->nm_bangsal }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Waktu --}}
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Waktu Makan</label>
                            <select name="waktu">
                                <option value="">Semua Waktu...</option>
                                <option value="pagi" {{ request('waktu') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="siang" {{ request('waktu') == 'siang' ? 'selected' : '' }}>Siang</option>
                                <option value="sore" {{ request('waktu') == 'sore' ? 'selected' : '' }}>Sore</option>
                            </select>
                        </div>

                        {{-- Bentuk Makanan --}}
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"></path></svg> Bentuk Makanan</label>
                            <select name="bentuk_makan">
                                <option value="">Semua Bentuk...</option>
                                @foreach($bentukMakanOptions as $bo)
                                    <option value="{{ $bo->kd_bentukmakan }}" {{ request('bentuk_makan') == $bo->kd_bentukmakan ? 'selected' : '' }}>{{ $bo->nama_bentukmakan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-group" style="margin-bottom: 20px;">
                        <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z"></path></svg> Pencarian Data</label>
                        <input type="text" name="search" placeholder="Cari Nama Pasien / No. RM / No. Rawat / Nama Petugas..." value="{{ request('search') }}">
                    </div>

                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('gizi.permintaan.riwayat-semua') }}" class="btn-reset">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Rekapitulasi Data --}}
        <div class="table-card">
            @if($permintaanList->isEmpty())
                <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                    <svg style="width: 48px; height: 48px; color: #CBD5E1; margin: 0 auto 12px; display: block;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg>
                    <div style="font-size: 16px; font-weight: 600; color: var(--text-main);">Tidak Ada Riwayat Permintaan</div>
                    <div style="font-size: 13px; margin-top: 4px;">Tidak ditemukan data permintaan diet gizi pada periode/filter ini.</div>
                </div>
            @else
                <table id="riwayatPermintaanTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Tgl Permintaan</th>
                            <th>No. RM / Rawat</th>
                            <th>Nama Pasien</th>
                            <th>Ruangan / Bed</th>
                            <th>Waktu</th>
                            <th>Bentuk & Diet</th>
                            <th>Alergi & Diagnosa Gizi</th>
                            <th>Keterangan</th>
                            <th>Ahli Gizi</th>
                            <th style="width: 80px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permintaanList as $p)
                            <tr>
                                <td style="font-weight: 600; white-space: nowrap;">
                                    {{ \Carbon\Carbon::parse($p['tglPermintaan'])->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">{{ $p['no_rekamedis'] }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);" class="font-mono">{{ $p['noRawat'] }}</div>
                                </td>
                                <td style="font-weight: 700; color: var(--primary-dark);">
                                    {{ $p['nama_pasien'] }}
                                </td>
                                <td>
                                    <span class="bed-badge" style="font-weight: 700;">{{ $p['kdBed'] }}</span>
                                    <div style="font-size: 12px; font-weight: 500; color: var(--text-main); margin-top: 4px;">{{ $p['nmRanap'] }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $p['kdKelas'] }}</div>
                                </td>
                                <td>
                                    <span class="badge-waktu {{ strtolower($p['waktu']) }}">
                                        {{ $p['waktu'] }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                        {{ $p['bentuk'] }}
                                    </div>
                                    @if(!empty($p['diets']))
                                        <div style="margin-top: 2px;">
                                            @foreach($p['diets'] as $d)
                                                <span class="badge-diet">{{ $d }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="font-size: 11px; color: var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="margin-bottom: 4px;">
                                        <span style="font-size: 11px; font-weight: 700; color: #DC2626; text-transform: uppercase;">Alergi:</span>
                                        <span style="font-size: 12px; color: var(--text-main);">{{ $p['alergi'] }}</span>
                                    </div>
                                    <div>
                                        <span style="font-size: 11px; font-weight: 700; color: var(--primary-dark); text-transform: uppercase;">Diagnosa:</span>
                                        <span style="font-size: 12px; color: var(--text-main);">{{ $p['diagnosaGizi'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="margin-bottom: 2px;">
                                        <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">Snack:</span>
                                        <span style="font-size: 12px;">{{ $p['keteranganSnack'] }}</span>
                                    </div>
                                    <div>
                                        <span style="font-size: 11px; font-weight: 600; color: var(--text-muted);">Diet:</span>
                                        <span style="font-size: 12px;">{{ $p['keteranganDiet'] }}</span>
                                    </div>
                                </td>
                                <td style="font-weight: 500; color: var(--text-muted); font-size: 12px;">
                                    {{ $p['nama_petugas'] }}
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <button class="btn-detail-modal" 
                                            data-detail="{{ json_encode($p) }}"
                                            style="background: #FAFBFC; border: 1.5px solid var(--border); cursor: pointer; color: var(--primary); padding: 7px 12px; border-radius: 8px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; font-family: inherit; font-size: 12px; font-weight: 600; gap: 4px;"
                                            title="Lihat Detail">
                                        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sidebar Mobile toggler
            $('#menu-toggle').click(function() {
                $('.sidebar').addClass('active');
                $('#overlay').addClass('active');
            });
            $('#overlay').click(function() {
                $('.sidebar').removeClass('active');
                $(this).removeClass('active');
            });

            // Initialize Select2
            $('#select-bangsal').select2({
                placeholder: 'Semua Ruangan...',
                allowClear: true,
                width: '100%'
            });

            // Initialize DataTable
            if ($('#riwayatPermintaanTable').length) {
                $('#riwayatPermintaanTable').DataTable({
                    responsive: true,
                    order: [[0, 'desc']], // Sort by Tanggal Permintaan descending
                    language: {
                        search: "Saring Hasil Table:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ permintaan",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 permintaan",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Data permintaan tidak ditemukan",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            }

            // Handle detail button clicks
            $(document).on('click', '.btn-detail-modal', function() {
                const data = $(this).data('detail');
                showDetailModal(data);
            });
        });

        function showDetailModal(data) {
            $('#det-nama-pasien').text(data.nama_pasien);
            $('#det-rm-rawat').html(`<strong>${data.no_rekamedis}</strong> <span class="text-muted" style="font-family: monospace;">(${data.noRawat})</span>`);
            $('#det-ruangan').text(data.nmRanap);
            $('#det-bed-kelas').html(`<span class="bed-badge" style="font-weight: 700;">${data.kdBed}</span> (Kelas: ${data.kdKelas})`);
            $('#det-waktu').html(`<span class="badge-waktu ${data.waktu.toLowerCase()}">${data.waktu}</span>`);
            $('#det-bentuk').text(data.bentuk);
            
            // Render diets as badges
            const dietsContainer = $('#det-diets');
            dietsContainer.empty();
            if (data.diets && data.diets.length > 0) {
                data.diets.forEach(d => {
                    dietsContainer.append(`<span class="badge-diet">${d}</span>`);
                });
            } else {
                dietsContainer.text('—');
            }

            $('#det-ket-snack').text(data.keteranganSnack || '—');
            $('#det-ket-diet').text(data.keteranganDiet || '—');
            $('#det-alergi').text(data.alergi || 'Tidak Ada');
            $('#det-diagnosa').text(data.diagnosaGizi || '—');
            $('#det-petugas').text(data.nama_petugas || '—');

            // Format date
            let tgl = data.tglPermintaan;
            try {
                const dateObj = new Date(tgl);
                const day = String(dateObj.getDate()).padStart(2, '0');
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const year = dateObj.getFullYear();
                const hours = String(dateObj.getHours()).padStart(2, '0');
                const minutes = String(dateObj.getMinutes()).padStart(2, '0');
                tgl = `${day}/${month}/${year} ${hours}:${minutes}`;
            } catch (e) {}
            $('#det-tanggal').text(tgl);

            $('#detailPermintaanModal').addClass('active');
            $('body').addClass('modal-open');
        }

        function closeDetailModal() {
            $('#detailPermintaanModal').removeClass('active');
            $('body').removeClass('modal-open');
        }

        // Close on escape key
        $(document).keydown(function(e) {
            if (e.keyCode === 27) {
                closeDetailModal();
            }
        });

        // Close on clicking outside modal content
        $(document).ready(function() {
            $('#detailPermintaanModal').click(function(e) {
                if ($(e.target).is('#detailPermintaanModal')) {
                    closeDetailModal();
                }
            });
        });
    </script>

    <!-- Detail Modal -->
    <div id="detailPermintaanModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Permintaan Diet Gizi</h3>
                <button class="modal-close" onclick="closeDetailModal()">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <!-- Pasien & Kamar -->
                <div class="detail-section">
                    <div class="detail-section-title">Informasi Pasien & Ruangan</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nama Pasien</span>
                            <span class="detail-val-highlight" id="det-nama-pasien"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">No. Rekam Medis / Rawat</span>
                            <span class="detail-val" id="det-rm-rawat"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ruangan Rawat Inap</span>
                            <span class="detail-val" id="det-ruangan"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Bed & Kelas</span>
                            <span class="detail-val" id="det-bed-kelas"></span>
                        </div>
                    </div>
                </div>

                <!-- Detail Permintaan Gizi -->
                <div class="detail-section">
                    <div class="detail-section-title">Detail Permintaan Diet Gizi</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Waktu Makan</span>
                            <span class="detail-val" id="det-waktu"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Bentuk Makanan</span>
                            <span class="detail-val" id="det-bentuk"></span>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label">Jenis Diet</span>
                            <div id="det-diets" style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px;"></div>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Keterangan Snack</span>
                            <span class="detail-val" id="det-ket-snack"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Keterangan Diet</span>
                            <span class="detail-val" id="det-ket-diet"></span>
                        </div>
                    </div>
                </div>

                <!-- Alergi & Diagnosa -->
                <div class="detail-section">
                    <div class="detail-section-title">Informasi Medis Gizi</div>
                    <div class="detail-grid">
                        <div class="detail-item full-width" style="margin-bottom: 8px;">
                            <span class="detail-label" style="color: #DC2626;">Alergi Makanan</span>
                            <span class="detail-val" id="det-alergi" style="color: #DC2626; font-weight: 600;"></span>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label" style="color: var(--primary-dark)">Diagnosa Gizi</span>
                            <span class="detail-val" id="det-diagnosa" style="font-weight: 500;"></span>
                        </div>
                    </div>
                </div>

                <!-- Administrasi -->
                <div class="detail-section">
                    <div class="detail-section-title">Petugas & Waktu Input</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Ahli Gizi (Dietisien)</span>
                            <span class="detail-val" id="det-petugas"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Permintaan</span>
                            <span class="detail-val" id="det-tanggal"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal-close" onclick="closeDetailModal()">Tutup Detail</button>
            </div>
        </div>
    </div>
</body>
</html>
