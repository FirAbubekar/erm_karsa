<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Rekomendasi Dokter</title>
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
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --warning: #F59E0B;
            --info: #3B82F6;
            --shadow-card: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-elevated: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -1px rgba(0,0,0,0.03);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        body { background: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-width); background: rgba(255,255,255,0.98); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; transition: all 0.15s; }
        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }
        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; width: 100%; background: var(--primary-surface); color: var(--primary); border: 1px solid #FEE2E2; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-logout:hover { background: #FEE2E2; }

        /* Main */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; min-height: 100vh; }

        /* Header */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .page-header-left h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.3px; display: flex; align-items: center; gap: 10px; }
        .page-header-left h1 i { color: var(--primary); font-size: 22px; }
        .page-header-left p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .page-header-right { display: flex; gap: 10px; align-items: center; }
        .btn-primary-sm { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--primary-gradient); color: white; border: none; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(220,38,38,0.2); }
        .btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,38,38,0.3); }
        .btn-outline-sm { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--card-bg); color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.15s; }
        .btn-outline-sm:hover { background: var(--bg-main); color: var(--text-main); }
        .user-badge { display: flex; align-items: center; gap: 8px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 999px; box-shadow: var(--shadow-card); }
        .user-avatar { width: 28px; height: 28px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 11px; color: white; }
        .user-name { font-size: 12px; font-weight: 500; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 18px 20px; box-shadow: var(--shadow-card); display: flex; align-items: center; gap: 16px; transition: all 0.2s; }
        .stat-card:hover { box-shadow: var(--shadow-elevated); }
        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .stat-icon.red { background: var(--primary-surface); color: var(--primary); }
        .stat-icon.green { background: #ECFDF5; color: var(--success); }
        .stat-icon.blue { background: #EFF6FF; color: var(--info); }
        .stat-info h3 { font-size: 22px; font-weight: 700; line-height: 1.2; }
        .stat-info p { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

        /* Filter Card */
        .filter-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-card); margin-bottom: 24px; overflow: hidden; }
        .filter-header { background: var(--primary-gradient); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
        .filter-header-left { display: flex; align-items: center; gap: 10px; }
        .filter-header-left i { color: rgba(255,255,255,0.85); font-size: 16px; }
        .filter-header-left span { color: white; font-weight: 600; font-size: 13px; }
        .filter-toggle { background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; font-size: 13px; }
        .filter-toggle:hover { background: rgba(255,255,255,0.3); }
        .filter-body { padding: 18px 20px; }
        .filter-row { display: flex; gap: 14px; flex-wrap: wrap; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; }
        .filter-group input, .filter-group select {
            padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; font-family: inherit; background: var(--card-bg); color: var(--text-main); outline: none; transition: border 0.15s; min-width: 140px;
        }
        .filter-group input:focus, .filter-group select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(220,38,38,0.08); }
        .filter-group input[type="date"] { min-width: 150px; }
        .filter-actions { display: flex; gap: 8px; align-items: end; padding-bottom: 1px; }
        .btn-filter { padding: 7px 18px; background: var(--primary-gradient); color: white; border: none; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s; display: flex; align-items: center; gap: 6px; }
        .btn-filter:hover { opacity: 0.9; }
        .btn-filter-reset { padding: 7px 14px; background: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; font-weight: 500; cursor: pointer; transition: all 0.15s; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .btn-filter-reset:hover { background: var(--border); color: var(--text-main); }

        /* Table Card */
        .table-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-card); overflow: hidden; }
        .table-toolbar { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .table-toolbar-left { font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .table-toolbar-left span { color: var(--text-muted); font-weight: 400; }
        .table-toolbar-right { display: flex; align-items: center; gap: 10px; }
        .table-search-box { display: flex; align-items: center; border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; }
        .table-search-box i { padding: 0 10px; color: var(--text-subtle); font-size: 13px; }
        .table-search-box input { border: none; padding: 7px 10px 7px 0; font-size: 12px; font-family: inherit; outline: none; width: 180px; background: transparent; }
        .table-search-box input:focus { width: 220px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100% !important; border-collapse: collapse; font-size: 13px; margin: 0 !important; }
        thead { background: var(--bg-main); }
        th { padding: 11px 14px !important; text-align: left !important; font-weight: 600 !important; font-size: 11px !important; text-transform: uppercase !important; color: var(--text-muted) !important; letter-spacing: 0.04em !important; border-bottom: 1px solid var(--border) !important; }
        td { padding: 12px 14px !important; border-bottom: 1px solid var(--border-light) !important; vertical-align: middle !important; }
        tr:last-child td { border-bottom: none !important; }
        tr:hover td { background: rgba(220,38,38,0.02); }
        .badge-gol { display: inline-block; padding: 2px 10px; border-radius: 999px; font-weight: 600; font-size: 11px; background: var(--primary-surface); color: var(--primary); }
        .badge-bentuk { display: inline-block; padding: 2px 10px; border-radius: 999px; font-weight: 500; font-size: 11px; background: #F8FAFC; color: var(--text-muted); border: 1px solid var(--border); }
        .patient-cell strong { font-size: 13px; }
        .patient-cell .sub-text { font-size: 11px; color: var(--text-muted); }
        .mono { font-family: 'SF Mono', 'Cascadia Code', 'Consolas', monospace; font-size: 12px; letter-spacing: -0.02em; }
        .btn-download { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .btn-download:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 2px 8px rgba(220,38,38,0.25); }
        .btn-download i { font-size: 12px; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 40px; color: var(--text-subtle); margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; }

        /* Pagination: override default */
        nav[role="navigation"] { padding: 14px 20px; border-top: 1px solid var(--border); }
        nav[role="navigation"] .flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        nav[role="navigation"] .text-sm { font-size: 12px; color: var(--text-muted); }
        nav[role="navigation"] .relative.z-0 { display: flex; gap: 3px; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; font-weight: 500; color: var(--text-main); background: var(--card-bg); text-decoration: none; transition: all 0.15s;
        }
        nav[role="navigation"] a:hover { background: var(--bg-main); border-color: var(--text-subtle); }
        nav[role="navigation"] span[aria-current="page"] { background: var(--primary-gradient); color: white; border-color: transparent; }
        nav[role="navigation"] .disabled span, nav[role="navigation"] .disabled a { opacity: 0.4; pointer-events: none; }

        /* DataTable overrides */
        .dataTables_wrapper .dataTables_filter { display: none; }
        .dataTables_wrapper .dataTables_length { display: none; }
        .dataTables_wrapper .dataTables_info { display: none; }
        .dataTables_wrapper .dataTables_paginate { display: none; }
        .dataTables_wrapper .dataTables_empty { display: none; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.2s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-row { flex-direction: column; }
            .filter-group input, .filter-group select { min-width: 100%; }
            .page-header { flex-direction: column; gap: 12px; }
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
        <span style="font-weight:700;font-size:16px;">Riwayat Rekomendasi</span>
        <div class="user-avatar" style="cursor:default;">{{ substr(Session::get('user_id'), 0, 1) }}</div>
    </div>
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('active');document.querySelector('.sidebar-overlay').classList.remove('active')"></div>

    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="fas fa-history"></i> Riwayat Rekomendasi Dokter</h1>
                <p>Daftar rekomendasi permintaan darah yang telah dibuat.</p>
            </div>
            <div class="page-header-right">
                <a href="{{ route('bank-darah.rekomendasi') }}" class="btn-primary-sm">
                    <i class="fas fa-plus"></i> Rekomendasi Baru
                </a>
                <div class="user-badge">
                    <div class="user-avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                    <span class="user-name">{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-file-medical"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalData, 0, ',', '.') }}</h3>
                    <p>Total Rekomendasi</p>
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

        <!-- Filter Card -->
        <div class="filter-card">
            <div class="filter-header">
                <div class="filter-header-left">
                    <i class="fas fa-filter"></i>
                    <span>Filter Pencarian</span>
                </div>
                <button class="filter-toggle" onclick="const b=document.querySelector('.filter-body');b.style.display=b.style.display==='none'?'block':'none';this.querySelector('i').classList.toggle('fa-chevron-up');this.querySelector('i').classList.toggle('fa-chevron-down')">
                    <i class="fas fa-chevron-up"></i>
                </button>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('bank-darah.history') }}" id="filterForm">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tgl_awal" value="{{ request('tgl_awal') }}">
                        </div>
                        <div class="filter-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}">
                        </div>
                        <div class="filter-group">
                            <label>Bentuk Darah</label>
                            <select name="bentuk_darah">
                                <option value="">Semua Bentuk</option>
                                @foreach($bentukDarahList as $bd)
                                    <option value="{{ $bd }}" {{ request('bentuk_darah') == $bd ? 'selected' : '' }}>{{ $bd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Golongan Darah</label>
                            <select name="gol_darah">
                                <option value="">Semua Golongan</option>
                                @foreach($golDarahList as $gd)
                                    <option value="{{ $gd }}" {{ request('gol_darah') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group" style="flex:1;min-width:160px;">
                            <label>Cari Pasien / No. RM</label>
                            <input type="text" name="search" placeholder="Nama, No. RM, atau No. Rekomendasi..." value="{{ request('search') }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('bank-darah.history') }}" class="btn-filter-reset"><i class="fas fa-undo"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <i class="fas fa-list" style="color:var(--primary);"></i>
                    Daftar Rekomendasi
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
                            <th>No. Rekomendasi</th>
                            <th>Tgl. Uji</th>
                            <th>Pasien</th>
                            <th>Bentuk Darah</th>
                            <th>Gol. Darah</th>
                            <th>Dokter</th>
                            <th>Tgl. Dibuat</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td><span class="mono">{{ $item->no_rekomendasi }}</span></td>
                                <td style="font-size:12px;">{{ $item->tgl_uji ? \Carbon\Carbon::parse($item->tgl_uji)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <div class="patient-cell">
                                        <strong>{{ $item->nama_pasien }}</strong>
                                        <div class="sub-text">
                                            @if($item->no_rkm_medis)
                                                RM: {{ $item->no_rkm_medis }}
                                            @endif
                                            @if($item->no_rawat && $item->no_rkm_medis) &middot; @endif
                                            @if($item->no_rawat)
                                                Rawat: {{ $item->no_rawat }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-bentuk">{{ $item->bentuk_darah }}</span></td>
                                <td><span class="badge-gol">{{ $item->gol_darah_permintaan }}</span></td>
                                <td style="font-size:12px;">{{ $item->nama_dokter }}</td>
                                <td style="font-size:12px;">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                                <td style="text-align:center;">
                                    <a href="{{ route('bank-darah.download', $item->id) }}" class="btn-download" target="_blank">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada data rekomendasi dokter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

            // Toggle mobile sidebar
            @if(session('sidebar_collapsed'))
                // noop
            @endif
        });
    </script>
</body>
</html>
