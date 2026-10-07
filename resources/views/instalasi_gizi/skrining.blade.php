<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Asuhan Gizi</title>
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
            --primary-light: rgba(16, 185, 129, 0.1);
            --primary-glow: rgba(16, 185, 129, 0.18);
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
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

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .page-header p { color: var(--text-muted); font-size: 13px; margin-top: 4px; }

        /* Filter Card */
        .filter-card { background: white; border-radius: var(--radius-md); border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 24px; overflow: hidden; }
        .filter-head { padding: 14px 20px; border-bottom: 1px solid var(--border-light); background: #FAFBFC; display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13px; color: var(--text-main); }
        .filter-head-left svg { width: 16px; height: 16px; color: var(--primary); }
        .filter-body { padding: 18px 20px; }
        .filter-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 200px; }
        .filter-group label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 5px; }
        .filter-group label svg { width: 12px; height: 12px; }
        .filter-control { padding: 9px 12px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; outline: none; background: white; width: 100%; font-weight: 500; }
        .filter-control:focus { border-color: var(--primary); }
        .btn-filter { padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s; height: 38px; }
        .btn-filter:hover { background: var(--primary-dark); }

        /* Custom DataTable Styling */
        .dataTables_wrapper { padding: 20px 0; }
        table.dataTable { border-collapse: collapse !important; margin-top: 15px !important; margin-bottom: 15px !important; border-bottom: 1px solid var(--border) !important; }
        table.dataTable thead th { background: #FAFBFC !important; color: var(--text-muted) !important; font-size: 11px !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border-bottom: 2px solid var(--border) !important; padding: 12px 16px !important; }
        table.dataTable tbody td { padding: 12px 16px !important; border-bottom: 1px solid var(--border-light) !important; font-size: 13px !important; color: var(--text-main) !important; }
        table.dataTable tbody tr:hover td { background-color: rgba(16, 185, 129, 0.03) !important; }
        .dataTables_filter input { padding: 8px 12px !important; border: 1.5px solid var(--border) !important; border-radius: var(--radius-sm) !important; outline: none !important; font-size: 13px !important; margin-left: 8px !important; background: #FAFBFC !important; }
        .dataTables_filter input:focus { border-color: var(--primary) !important; background: white !important; }
        .dataTables_length select { padding: 6px 10px !important; border: 1.5px solid var(--border) !important; border-radius: var(--radius-sm) !important; outline: none !important; font-size: 13px !important; margin: 0 4px !important; background: #FAFBFC !important; }
        .dataTables_paginate .paginate_button { padding: 6px 12px !important; border-radius: var(--radius-sm) !important; margin: 0 2px !important; border: 1px solid var(--border) !important; background: white !important; color: var(--text-muted) !important; font-size: 12px !important; font-weight: 600 !important; }
        .dataTables_paginate .paginate_button.current { background: var(--primary) !important; color: white !important; border-color: var(--primary) !important; }
        .bed-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: var(--primary-light); color: var(--primary-dark); text-transform: uppercase; }

        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); }

        /* Modal Layout */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(6px); z-index: 1000; display: none; align-items: flex-start; justify-content: center; padding: 40px 20px; overflow-y: auto; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; border-radius: var(--radius-lg); width: 100%; max-width: 1000px; box-shadow: var(--shadow-lg); display: flex; flex-direction: column; overflow: hidden; margin-bottom: 40px; animation: modalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes modalSlideIn { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #FAFBFC; }
        .modal-head h2 { font-size: 18px; font-weight: 800; color: var(--text-main); }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color 0.2s; }
        .modal-close:hover { color: #EF4444; }

        .modal-body { padding: 24px; max-height: calc(85vh - 140px); overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .modal-section { border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; background: #FCFDFE; }
        .modal-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--primary-dark); letter-spacing: 0.05em; margin-bottom: 16px; border-bottom: 1.5px dashed var(--border); padding-bottom: 8px; }
        
        .modal-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        .modal-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
        .modal-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .modal-field { display: flex; flex-direction: column; gap: 6px; }
        .modal-field.full { grid-column: span 4; }
        .modal-field.span-2 { grid-column: span 2; }
        .modal-field .ml { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.03em; }

        .pm-input { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; color: var(--text-main); outline: none; background: white; font-weight: 500; transition: border-color 0.2s; }
        .pm-input:focus { border-color: var(--primary); }
        .pm-input[readonly] { background: var(--border-light); cursor: not-allowed; color: var(--text-muted); }
        textarea.pm-input { resize: vertical; min-height: 80px; font-family: inherit; }

        .time-select-row { display: flex; gap: 8px; align-items: center; }
        .time-select { flex: 1; padding: 8px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; text-align: center; }

        .alergi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; padding: 6px 0; }
        .alergi-item { display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 500; border-bottom: 1px solid var(--border-light); padding-bottom: 6px; }
        .radio-group { display: flex; gap: 12px; }
        .radio-label { display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 13px; }

        .modal-foot { padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: #FAFBFC; }

        /* Media Queries */
        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding: 84px 20px 20px; }
        }
        @media (max-width: 768px) {
            .modal-grid-4, .modal-grid-3, .modal-grid-2 { grid-template-columns: 1fr; }
            .modal-field.span-2, .modal-field.full { grid-column: span 1; }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')
    
    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Asuhan Gizi</h1>
                <p>Asesmen awal, diagnosis, rencana intervensi, monitoring, dan evaluasi status gizi pasien rawat inap.</p>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="filter-card">
            <div class="filter-head">
                <div class="filter-head-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter Pasien Rawat Inap</span>
                </div>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('gizi.skrining') }}">
                    <div class="filter-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: flex-end;">
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Ruangan</label>
                            <select name="bangsal" id="select-bangsal" class="filter-control select2">
                                <option value="">Pilih Ruangan...</option>
                                @foreach($bangsalList as $b)
                                    <option value="{{ $b->kd_bangsal }}" {{ request('bangsal') == $b->kd_bangsal ? 'selected' : '' }}>{{ $b->nm_bangsal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari Pasien</label>
                            <input type="text" name="search" class="filter-control" placeholder="Nama / RM / No. Rawat..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="filter-buttons" style="display: flex; gap: 8px; margin-top: 16px; justify-content: flex-start;">
                        <button type="submit" class="btn-filter" style="height: 38px; padding: 0 20px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Inpatient List --}}
        @if(!$hasRoomFilter)
            <div class="empty-state">
                <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <div style="font-size:16px;font-weight:600;color:var(--text-main);">Silakan Pilih Ruangan</div>
                <div style="font-size:13px;margin-top:4px;max-width:420px;margin-left:auto;margin-right:auto;">Pilih ruangan rawat inap pada filter di atas, lalu klik <strong>Cari</strong> untuk menampilkan pasien yang aktif dirawat inap (belum pulang).</div>
            </div>
        @else
            @if($pasienList->isEmpty())
                <div class="empty-state">
                    <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div style="font-size:16px;font-weight:600;color:var(--text-main);">Pasien Tidak Ditemukan</div>
                    <div style="font-size:13px;margin-top:4px;">Tidak ada pasien aktif (belum pulang) yang sesuai di ruangan/filter pencarian ini.</div>
                </div>
            @else
                <div class="filter-card" style="padding: 20px;">
                    <table id="patientTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kamar / Bed</th>
                                <th>No. RM</th>
                                <th>No. Rawat</th>
                                <th>Nama Pasien</th>
                                <th>Gender</th>
                                <th>Umur</th>
                                <th>Asuhan Terakhir</th>
                                <th style="width: 120px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasienList as $p)
                                @php
                                    $birth = new \Carbon\Carbon($p->tgl_lahir);
                                    $age = $birth->diff(\Carbon\Carbon::now())->format('%y Th %m Bl');
                                    $gender = $p->jk == 'L' ? 'Laki-laki' : 'Perempuan';
                                @endphp
                                <tr>
                                    <td><span class="bed-badge">{{ $p->kd_kamar }}</span> <span style="font-size: 11px; color: var(--text-muted)">({{ $p->nm_bangsal }})</span></td>
                                    <td class="font-mono">{{ $p->no_rkm_medis }}</td>
                                    <td class="font-mono">{{ $p->no_rawat }}</td>
                                    <td style="font-weight: 600;">{{ $p->nm_pasien }}</td>
                                    <td>{{ $gender }}</td>
                                    <td>{{ $age }}</td>
                                    <td class="text-xs" style="white-space: nowrap;">
                                        @if($p->tgl_asuhan_terakhir)
                                            <span style="display: inline-flex; align-items: center; gap: 5px; color: var(--text-main); font-weight: 600;">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($p->tgl_asuhan_terakhir)->format('d/m/Y H:i:s') }}
                                            </span>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 11px;">Belum Diinput</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn-filter" style="font-size:12px; padding: 6px 12px; height: auto;" onclick="openInputAsuhanModal('{{ $p->no_rawat }}')">
                                            Asuhan Gizi
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>

    {{-- Input Asuhan Gizi Modal --}}
    <div class="modal-overlay" id="asuhanModal">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Form Input Asuhan Gizi</h2>
                <button class="modal-close" onclick="closeInputAsuhanModal()">&times;</button>
            </div>
            <form id="formAsuhanGizi">
                @csrf
                <div class="modal-body">
                    {{-- Banner Waktu Terakhir Asuhan --}}
                    <div id="as-info-terakhir" style="display: none; align-items: center; gap: 10px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #065F46; font-weight: 500;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Data Asuhan Gizi terakhir untuk pasien ini diinputkan pada: <strong id="as-info-terakhir-text">—</strong></span>
                    </div>

                    {{-- Informasi Pasien & Waktu --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Informasi Pasien & Registrasi</div>
                        <div class="modal-grid-4">
                            <div class="modal-field">
                                <span class="ml">No. Rawat</span>
                                <input type="text" id="as-noRawat" name="no_rawat" class="pm-input" readonly>
                            </div>
                            <div class="modal-field">
                                <span class="ml">No. RM</span>
                                <input type="text" id="as-noRm" class="pm-input" readonly>
                            </div>
                            <div class="modal-field span-2">
                                <span class="ml">Nama Pasien</span>
                                <input type="text" id="as-namaPasien" class="pm-input" readonly>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Gender</span>
                                <input type="text" id="as-gender" class="pm-input" readonly>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Tgl. Lahir</span>
                                <input type="text" id="as-tglLahir" class="pm-input" readonly>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Umur</span>
                                <input type="text" id="as-umur" class="pm-input" readonly>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Tanggal Catatan</span>
                                <input type="date" id="as-tanggal" name="tanggal" class="pm-input">
                            </div>
                            <div class="modal-field span-2">
                                <span class="ml">Diagnosa Awal</span>
                                <input type="text" id="as-diagnosa" class="pm-input" readonly>
                            </div>
                            <div class="modal-field span-2">
                                <span class="ml">Jam Catatan</span>
                                <div class="time-select-row">
                                    <select id="as-jam-h" class="pm-input time-select"></select> :
                                    <select id="as-jam-m" class="pm-input time-select"></select> :
                                    <select id="as-jam-s" class="pm-input time-select"></select>
                                    <input type="hidden" id="as-jam-full" name="jam">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Antropometri Section --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Antropometri</div>
                        <div class="modal-grid-4">
                            <div class="modal-field">
                                <span class="ml">Berat Badan (BB)</span>
                                <div class="time-select-row">
                                    <input type="text" id="as-bb" name="antropometri_bb" class="pm-input" placeholder="-">
                                    <span class="ml">Kg</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Tinggi Badan (TB)</span>
                                <div class="time-select-row">
                                    <input type="text" id="as-tb" name="antropometri_tb" class="pm-input" placeholder="-">
                                    <span class="ml">Cm</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">IMT</span>
                                <div class="time-select-row">
                                    <input type="text" id="as-imt" name="antropometri_imt" class="pm-input" placeholder="-">
                                    <span class="ml">Kg/m²</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Status Gizi</span>
                                <input type="text" id="as-statusgizi" name="antropometri_statusgizi" class="pm-input" placeholder="-">
                            </div>
                            <div class="modal-field">
                                <span class="ml">LILA</span>
                                <div class="time-select-row">
                                    <input type="text" name="antropometri_lila" class="pm-input" placeholder="-">
                                    <span class="ml">Cm</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Tinggi Lutut (TL)</span>
                                <div class="time-select-row">
                                    <input type="text" name="antropometri_tl" class="pm-input" placeholder="-">
                                    <span class="ml">Cm</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">ULNA</span>
                                <div class="time-select-row">
                                    <input type="text" name="antropometri_ulna" class="pm-input" placeholder="-">
                                    <span class="ml">Cm</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">BB Ideal</span>
                                <div class="time-select-row">
                                    <input type="text" name="antropometri_bbideal" class="pm-input" placeholder="-">
                                    <span class="ml">Kg</span>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="ml">BB/U (SD)</span>
                                <input type="text" name="antropometri_bbperu" class="pm-input" placeholder="-">
                            </div>
                            <div class="modal-field">
                                <span class="ml">TB/U (SD)</span>
                                <input type="text" name="antropometri_tbperu" class="pm-input" placeholder="-">
                            </div>
                            <div class="modal-field">
                                <span class="ml">BB/TB (SD)</span>
                                <input type="text" name="antropometri_bbpertb" class="pm-input" placeholder="-">
                            </div>
                            <div class="modal-field">
                                <span class="ml">LILA/U (SD)</span>
                                <input type="text" name="antropometri_llaperu" class="pm-input" placeholder="-">
                            </div>
                        </div>
                    </div>

                    {{-- Biokimia & Fisik/Klinis --}}
                    <div class="modal-grid-2">
                        <div class="modal-section" style="margin-bottom: 0;">
                            <div class="modal-section-title">Biokimia</div>
                            <div class="modal-field">
                                <textarea name="biokimia" class="pm-input" placeholder="Masukkan data laboratorium terkait gizi..."></textarea>
                            </div>
                        </div>
                        <div class="modal-section" style="margin-bottom: 0;">
                            <div class="modal-section-title">Fisik / Klinis</div>
                            <div class="modal-field">
                                <textarea name="fisik_klinis" class="pm-input" placeholder="Masukkan pemeriksaan fisik/klinis terkait gizi..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Gizi & Alergi --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Riwayat Gizi & Alergi Makanan</div>
                        <div class="alergi-grid">
                            <div class="alergi-item">
                                <span>Telur</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_telur" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_telur" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item">
                                <span>Udang</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_udang" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_udang" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item">
                                <span>Susu Sapi & Produk Olahannya</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_susu_sapi" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_susu_sapi" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item">
                                <span>Ikan</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_ikan" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_ikan" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item">
                                <span>Kacang Kedelai / Tanah</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_kacang" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_kacang" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item">
                                <span>Hazelnut / Almond</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_hazelnut" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_hazelnut" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                            <div class="alergi-item" style="grid-column: span 2;">
                                <span>Gluten / Gandum</span>
                                <div class="radio-group">
                                    <label class="radio-label"><input type="radio" name="alergi_gluten" value="Ya"> Ya</label>
                                    <label class="radio-label"><input type="radio" name="alergi_gluten" value="Tidak" checked> Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-grid-2" style="margin-top: 14px;">
                            <div class="modal-field">
                                <span class="ml">Pola Makan</span>
                                <input type="text" name="pola_makan" class="pm-input" placeholder="Masukkan pola makan pasien...">
                            </div>
                            <div class="modal-field">
                                <span class="ml">Riwayat Personal</span>
                                <input type="text" name="riwayat_personal" class="pm-input" placeholder="Masukkan riwayat personal...">
                            </div>
                        </div>
                    </div>

                    {{-- Diagnosis, Intervensi, Monitoring & Evaluasi --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Diagnosis, Intervensi, Monitoring & Evaluasi</div>
                        <div class="modal-grid-2" style="gap: 20px;">
                            <div class="modal-field">
                                <span class="ml">Diagnosis Gizi</span>
                                <textarea name="diagnosis" class="pm-input" placeholder="Masukkan diagnosis gizi..."></textarea>
                            </div>
                            <div class="modal-field">
                                <span class="ml">Intervensi Gizi</span>
                                <textarea name="intervensi_gizi" class="pm-input" placeholder="Masukkan rencana intervensi gizi..."></textarea>
                            </div>
                            <div class="modal-field span-2">
                                <span class="ml">Monitoring & Evaluasi</span>
                                <textarea name="monitoring_evaluasi" class="pm-input" placeholder="Masukkan rencana monitoring & evaluasi..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Petugas --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Petugas Penanggung Jawab</div>
                        <div class="modal-field">
                            <span class="ml">Dietisien / Nutrisionis</span>
                            <select id="as-nip" class="pm-input" disabled style="background-color: var(--border-light); cursor: not-allowed;">
                                <option value="">Pilih Petugas...</option>
                                @foreach($petugasList as $p)
                                    <option value="{{ $p->nik }}" {{ Session::get('user_id') == $p->nik ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="nip" value="{{ Session::get('user_id') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-secondary" style="border-radius:var(--radius-sm); padding: 10px 20px;" onclick="closeInputAsuhanModal()">Tutup</button>
                    <button type="button" id="btn-save-asuhan" class="btn-filter" style="height:auto;">Simpan Asuhan Gizi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var pasienData = @json($pasienList->keyBy('no_rawat'));

        $(document).ready(function() {
            $('#select-bangsal').select2({
                placeholder: 'Pilih Ruangan...',
                allowClear: true,
                width: '100%'
            });

            $('#patientTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari Pasien:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pasien",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 pasien",
                    infoFiltered: "(disaring dari _MAX_ total pasien)",
                    zeroRecords: "Pasien tidak ditemukan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Hour/Minute/Second dropdown population
            var hSelect = $('#as-jam-h');
            var mSelect = $('#as-jam-m');
            var sSelect = $('#as-jam-s');

            for (var i = 0; i < 24; i++) {
                var val = String(i).padStart(2, '0');
                hSelect.append(new Option(val, val));
            }
            for (var i = 0; i < 60; i++) {
                var val = String(i).padStart(2, '0');
                mSelect.append(new Option(val, val));
                sSelect.append(new Option(val, val));
            }

            // Auto calculate IMT when BB or TB changes
            $('#as-bb, #as-tb').on('input', function() {
                var bb = parseFloat($('#as-bb').val());
                var tb = parseFloat($('#as-tb').val());
                if (bb && tb) {
                    var imt = bb / ((tb / 100) * (tb / 100));
                    $('#as-imt').val(imt.toFixed(1));

                    // Auto suggest Status Gizi
                    var status = '';
                    if (imt < 18.5) {
                        status = 'Kurus';
                    } else if (imt >= 18.5 && imt < 25.0) {
                        status = 'Normal';
                    } else if (imt >= 25.0 && imt < 30.0) {
                        status = 'Gemuk';
                    } else {
                        status = 'Obesitas';
                    }
                    $('#as-statusgizi').val(status);
                }
            });
        });

        function openInputAsuhanModal(noRawat) {
            var p = pasienData[noRawat];
            if (!p) {
                alert('Data pasien tidak ditemukan.');
                return;
            }

            document.getElementById('formAsuhanGizi').reset();

            // Populate Waktu Terakhir Asuhan Gizi jika ada
            if (p.tgl_asuhan_terakhir) {
                var cleanDt = typeof p.tgl_asuhan_terakhir === 'string' ? p.tgl_asuhan_terakhir.replace(/-/g, '/') : p.tgl_asuhan_terakhir;
                var d = new Date(cleanDt);
                var formatted = !isNaN(d.getTime())
                    ? String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear() + ' ' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0') + ':' + String(d.getSeconds()).padStart(2, '0')
                    : p.tgl_asuhan_terakhir;
                document.getElementById('as-info-terakhir-text').textContent = formatted;
                document.getElementById('as-info-terakhir').style.display = 'flex';
            } else {
                document.getElementById('as-info-terakhir').style.display = 'none';
            }

            // Populate demographics
            document.getElementById('as-noRawat').value = p.no_rawat || '';
            document.getElementById('as-noRm').value = p.no_rkm_medis || '';
            document.getElementById('as-namaPasien').value = p.nm_pasien || '';
            document.getElementById('as-gender').value = p.jk == 'L' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('as-tglLahir').value = p.tgl_lahir || '';
            document.getElementById('as-diagnosa').value = p.diagnosa_awal || '—';

            // Calculate age
            if (p.tgl_lahir) {
                var birth = new Date(p.tgl_lahir);
                var now = new Date();
                var ageNum = now.getFullYear() - birth.getFullYear();
                var m = now.getMonth() - birth.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < birth.getDate())) {
                    ageNum--;
                }
                document.getElementById('as-umur').value = ageNum + ' Tahun';
            }

            // Populate current date & time
            var now = new Date();
            var year = now.getFullYear();
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var date = String(now.getDate()).padStart(2, '0');
            document.getElementById('as-tanggal').value = year + '-' + month + '-' + date;

            var hour = String(now.getHours()).padStart(2, '0');
            var min = String(now.getMinutes()).padStart(2, '0');
            var sec = String(now.getSeconds()).padStart(2, '0');
            
            $('#as-jam-h').val(hour);
            $('#as-jam-m').val(min);
            $('#as-jam-s').val(sec);

            document.getElementById('asuhanModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeInputAsuhanModal() {
            document.getElementById('asuhanModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Form Submission
        document.getElementById('btn-save-asuhan')?.addEventListener('click', async function() {
            var form = document.getElementById('formAsuhanGizi');

            // Construct jam-full string (HH:MM:SS)
            var h = document.getElementById('as-jam-h').value;
            var m = document.getElementById('as-jam-m').value;
            var s = document.getElementById('as-jam-s').value;
            document.getElementById('as-jam-full').value = h + ':' + m + ':' + s;

            var noRawat = document.getElementById('as-noRawat').value;
            var tanggal = document.getElementById('as-tanggal').value;
            var nip = document.getElementById('as-nip').value;

            if (!noRawat) { alert('No. Rawat wajib diisi.'); return; }
            if (!tanggal) { alert('Tanggal wajib diisi.'); return; }
            if (!nip) { alert('Petugas wajib dipilih.'); return; }

            var btn = this;
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            try {
                var formData = new FormData(form);
                var response = await fetch('/instalasi-gizi/skrining/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var result = await response.json();

                if (response.ok && result.success) {
                    alert(result.message || 'Catatan Asuhan Gizi berhasil disimpan.');
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal menyimpan catatan Asuhan Gizi.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server saat menyimpan.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Simpan Asuhan Gizi';
            }
        });
    </script>
</body>
</html>
