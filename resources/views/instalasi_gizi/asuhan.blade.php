<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Adime Gizi</title>
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
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: rgba(16, 185, 129, 0.1);
            --bg-main: #F1F5F9;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; }

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

        /* Header & Breadcrumb */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }
        .page-header p { font-size: 14px; color: var(--text-muted); margin-top: 4px; }

        /* Filter Card */
        .filter-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow); margin-bottom: 24px; overflow: hidden; }
        .filter-head { padding: 16px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; color: var(--text-main); }
        .filter-head-left svg { width: 18px; height: 18px; color: var(--primary); }
        .filter-body { padding: 24px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 16px; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 8px; }
        .filter-group label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px; }
        .filter-group label svg { width: 14px; height: 14px; }
        .filter-control { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; color: var(--text-main); outline: none; font-weight: 500; transition: border-color 0.2s; background: #FAFBFC; }
        .filter-control:focus { border-color: var(--primary); background: white; }
        
        .btn-filter { padding: 11px 24px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 700; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center; height: 42px; background: var(--primary); color: white; transition: background 0.2s, transform 0.1s; }
        .btn-filter:hover { background: var(--primary-dark); }
        .btn-filter:active { transform: scale(0.98); }

        .btn-reset { padding: 11px 20px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; border: 1.5px solid var(--border); display: inline-flex; align-items: center; gap: 8px; justify-content: center; height: 42px; background: white; color: var(--text-muted); transition: background 0.2s; }
        .btn-reset:hover { background: var(--border-light); color: var(--text-main); }

        /* Custom DataTable Styling */
        .dataTables_wrapper {
            padding: 20px 0;
        }
        table.dataTable {
            border-collapse: collapse !important;
            margin-top: 15px !important;
            margin-bottom: 15px !important;
            border-bottom: 1px solid var(--border) !important;
        }
        table.dataTable thead th {
            background: #FAFBFC !important;
            color: var(--text-muted) !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border-bottom: 2px solid var(--border) !important;
            padding: 12px 16px !important;
        }
        table.dataTable tbody td {
            padding: 12px 16px !important;
            border-bottom: 1px solid var(--border-light) !important;
            font-size: 13px !important;
            color: var(--text-main) !important;
        }
        table.dataTable tbody tr:hover td {
            background-color: rgba(16, 185, 129, 0.03) !important;
        }
        .dataTables_filter input {
            padding: 8px 12px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            outline: none !important;
            font-size: 13px !important;
            margin-left: 8px !important;
            background: #FAFBFC !important;
        }
        .dataTables_filter input:focus {
            border-color: var(--primary) !important;
            background: white !important;
        }
        .dataTables_length select {
            padding: 6px 10px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            outline: none !important;
            font-size: 13px !important;
            margin: 0 4px !important;
            background: #FAFBFC !important;
        }
        .dataTables_paginate .paginate_button {
            padding: 6px 12px !important;
            border-radius: var(--radius-sm) !important;
            margin: 0 2px !important;
            border: 1px solid var(--border) !important;
            background: white !important;
            color: var(--text-muted) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }
        .dataTables_paginate .paginate_button.current, 
        .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
        }
        .dataTables_paginate .paginate_button:hover {
            background: var(--border-light) !important;
            color: var(--text-main) !important;
        }
        .bed-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: var(--primary-light); color: var(--primary-dark); text-transform: uppercase; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); }

        /* Modal Layout */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(6px); z-index: 1000; display: none; align-items: flex-start; justify-content: center; padding: 40px 20px; overflow-y: auto; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; border-radius: var(--radius-lg); width: 100%; max-width: 900px; box-shadow: var(--shadow-lg); display: flex; flex-direction: column; overflow: hidden; margin-bottom: 40px; animation: modalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes modalSlideIn { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #FAFBFC; }
        .modal-head h2 { font-size: 18px; font-weight: 800; color: var(--text-main); }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color 0.2s; }
        .modal-close:hover { color: #EF4444; }

        .modal-body { padding: 24px; max-height: calc(85vh - 140px); overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        
        .modal-section { border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; background: #FCFDFE; }
        .modal-section-title { font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--primary-dark); letter-spacing: 0.05em; margin-bottom: 16px; border-bottom: 1.5px dashed var(--border); padding-bottom: 8px; }
        
        .modal-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .modal-field { display: flex; flex-direction: column; gap: 6px; }
        .modal-field.full { grid-column: span 2; }
        .modal-field .ml { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.03em; }
        
        .pm-input { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 14px; color: var(--text-main); outline: none; background: white; font-weight: 500; transition: border-color 0.2s; }
        .pm-input:focus { border-color: var(--primary); }
        .pm-input[readonly] { background: var(--border-light); cursor: not-allowed; color: var(--text-muted); }
        textarea.pm-input { resize: vertical; min-height: 80px; font-family: inherit; }

        .time-select-row { display: flex; gap: 8px; align-items: center; }
        .time-select { flex: 1; padding: 8px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; text-align: center; }

        .modal-foot { padding: 20px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: #FAFBFC; }

        /* Media Queries */
        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding: 84px 20px 20px; }
        }
        @media (max-width: 768px) {
            .modal-grid { grid-template-columns: 1fr; }
            .modal-field.full { grid-column: span 1; }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')
    
    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Adime Gizi</h1>
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
                <form method="GET" action="{{ route('gizi.asuhan') }}">
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
                        <a href="{{ route('gizi.asuhan') }}" class="btn-reset" style="height: 38px; padding: 0 20px; display: inline-flex; align-items: center;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset
                        </a>
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
                <div class="table-container" style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; padding: 20px;">
                    <table id="patientTable" class="display" style="width:100%">
                        <thead>
                            <tr style="background: #FAFBFC;">
                                <th>Kamar / Bed</th>
                                <th>Kelas</th>
                                <th>No. RM</th>
                                <th>No. Rawat</th>
                                 <th>Nama Pasien</th>
                                <th>Gender</th>
                                <th>Umur</th>
                                <th>ADIME Terakhir</th>
                                <th style="text-align: center; width: 220px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasienList as $p)
                                <tr style="border-bottom: 1px solid var(--border-light);">
                                    <td><span class="bed-badge" style="font-weight: 700;">{{ $p->kd_kamar }}</span></td>
                                    <td><span style="font-weight: 600; color: var(--text-muted);">{{ $p->kelas }}</span></td>
                                    <td style="font-weight: 600; color: var(--text-main);">{{ $p->no_rkm_medis }}</td>
                                    <td style="font-weight: 500; color: var(--text-muted);">{{ $p->no_rawat }}</td>
                                    <td style="font-weight: 700; color: var(--text-main);">{{ $p->nm_pasien }}</td>
                                    <td>{{ $p->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tgl_lahir)->age }} tahun</td>
                                    <td class="text-xs" style="white-space: nowrap;">{{ $p->tgl_adime_terakhir ? \Carbon\Carbon::parse($p->tgl_adime_terakhir)->format('d/m/Y H:i:s') : '—' }}</td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <button type="button" class="btn-filter" onclick="openInputAdimeModal('{{ $p->no_rawat }}')" style="padding: 6px 12px; font-size: 12px; height: auto; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Input ADIME
                                            </button>
                                            <button type="button" class="btn-reset" onclick="openRiwayatAdimeModal('{{ $p->no_rawat }}')" style="padding: 6px 12px; font-size: 12px; height: auto; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; border: 1.5px solid var(--primary); color: var(--primary);">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Riwayat
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>

    {{-- Modal Input Catatan ADIME Gizi --}}
    <div class="modal-overlay" id="adimeModal">
        <div class="modal-card">
            <div class="modal-head">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <h2>Data Catatan ADIME Gizi</h2>
                    <button type="button" class="btn-filter" onclick="openRiwayatFromAddModal()" style="background-color: #6366F1; font-size: 12px; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; height: auto;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Lihat Riwayat ADIME
                    </button>
                </div>
                <button type="button" class="modal-close" onclick="closeInputAdimeModal()">&times;</button>
            </div>
            <form id="formAsuhanGizi" style="display: flex; flex-direction: column; flex-grow: 1; overflow: hidden; margin: 0;">
                @csrf
                <input type="hidden" name="old_tanggal" id="ad-old-tanggal">
                <div class="modal-body">
                    {{-- Banner Notifikasi Salin Riwayat --}}
                    <div id="ad-copy-notice" style="display: none; align-items: center; justify-content: space-between; background: #ECFDF5; border: 1px solid #6EE7B7; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; color: #065F46; font-size: 13px; font-weight: 500;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            <span id="ad-copy-notice-text">Data ADIME berhasil disalin dari riwayat. Silakan sesuaikan jika diperlukan lalu simpan.</span>
                        </div>
                        <button type="button" onclick="document.getElementById('ad-copy-notice').style.display='none'" style="background:none; border:none; color:#065F46; font-size:18px; cursor:pointer; line-height: 1;">&times;</button>
                    </div>

                    {{-- Quick Action Salin dari Riwayat --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4338CA; font-weight: 500;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Ingin menggunakan catatan ADIME sebelumnya sebagai acuan?</span>
                        </div>
                        <button type="button" class="btn-filter" onclick="openRiwayatFromAddModal()" style="padding: 6px 14px; font-size: 12px; background: #4F46E5; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; height: auto;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                            Pilih dari Riwayat
                        </button>
                    </div>

                    {{-- Section Data Pasien --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Data Pasien</div>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <div class="ml">No. Rawat</div>
                                <input type="text" class="pm-input" name="no_rawat" id="ad-noRawat" readonly>
                            </div>
                            <div class="modal-field">
                                <div class="ml">No. Rekam Medis</div>
                                <input type="text" class="pm-input" name="no_rm" id="ad-noRm" readonly>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Nama Pasien</div>
                                <input type="text" class="pm-input" name="nama_pasien" id="ad-namaPasien" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Section Tanggal & Petugas --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Tanggal & Petugas</div>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <div class="ml">Tanggal</div>
                                <input type="date" class="pm-input" name="tanggal" id="ad-tanggal">
                            </div>
                            <div class="modal-field">
                                <div class="ml">Jam</div>
                                <input type="time" class="pm-input" name="jam" id="ad-jam" required>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Petugas (Dietisien/Nutrisionis)</div>
                                <select class="pm-input" id="ad-nip" disabled style="background-color: var(--border-light); cursor: not-allowed;">
                                    <option value="">Pilih Petugas...</option>
                                    @foreach($petugasList as $petugas)
                                        <option value="{{ $petugas->nik }}" {{ Session::get('user_id') == $petugas->nik ? 'selected' : '' }}>{{ $petugas->nama }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="nip" value="{{ Session::get('user_id') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Section Form ADIME --}}
                    <div class="modal-section">
                        <div class="modal-section-title">Asesmen & Diagnosis</div>
                        <div class="modal-grid">
                            <div class="modal-field full">
                                <div class="ml">Asesmen</div>
                                <textarea class="pm-input" name="asesmen" placeholder="IMT, Asupan, Alergi, Nyeri, dll..."></textarea>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Diagnosis</div>
                                <textarea class="pm-input" name="diagnosis" placeholder="Peningkatan kebutuhan energi, protein, dll..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Intervensi & Monitoring</div>
                        <div class="modal-grid">
                            <div class="modal-field full">
                                <div class="ml">Intervensi</div>
                                <textarea class="pm-input" name="intervensi" placeholder="Diet, jenis kalori, dsb..."></textarea>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Monitoring</div>
                                <textarea class="pm-input" name="monitoring" placeholder="Parameter monitoring gizi (Lab, klinis)..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Evaluasi & Instruksi</div>
                        <div class="modal-grid">
                            <div class="modal-field full">
                                <div class="ml">Evaluasi</div>
                                <textarea class="pm-input" name="evaluasi" placeholder="Hasil evaluasi gizi..."></textarea>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Instruksi</div>
                                <textarea class="pm-input" name="instruksi" placeholder="Instruksi tindak lanjut..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-foot" style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <button type="button" class="btn-reset" id="btn-delete-adime" onclick="deleteCurrentAdime()" style="display: none; background: #FEF2F2; color: #DC2626; border-color: #FECACA;" title="Hapus catatan ADIME ini (Hanya pembuat yang dapat menghapus)">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Catatan Ini
                        </button>
                    </div>
                    <div style="display: flex; gap: 10px; margin-left: auto;">
                        <button type="button" class="btn-reset" onclick="closeInputAdimeModal()">Batal</button>
                        <button type="button" class="btn-filter" id="btn-save-adime">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Catatan ADIME
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Riwayat ADIME Gizi --}}
    <div class="modal-overlay" id="riwayatAdimeModal" style="z-index: 1010;">
        <div class="modal-card" style="max-width: 900px;">
            <div class="modal-head">
                <h2>Riwayat Catatan ADIME Gizi Pasien</h2>
                <button class="modal-close" onclick="closeRiwayatAdimeModal()">&times;</button>
            </div>
            <div class="modal-body" id="riwayatAdimeBody" style="max-height: calc(85vh - 140px); overflow-y: auto;">
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    Loading riwayat ADIME...
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-reset" onclick="closeRiwayatAdimeModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var currentUserId = '{{ Session::get("user_id") }}';
        var pasienData = @json($pasienList->keyBy('no_rawat'));

        // Populate hour, minute, second options
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
        });

        function openInputAdimeModal(noRawat) {
            var p = pasienData[noRawat];
            if (!p) {
                alert('Data pasien tidak ditemukan.');
                return;
            }

            document.getElementById('formAsuhanGizi').reset();
            document.getElementById('ad-old-tanggal').value = '';
            $('#ad-copy-notice').hide();
            $('#btn-delete-adime').hide();

            // Populate patient data
            document.getElementById('ad-noRawat').value = p.no_rawat || '';
            document.getElementById('ad-noRm').value = p.no_rkm_medis || '';
            document.getElementById('ad-namaPasien').value = p.nm_pasien || '';

            // Populate current date & time
            var now = new Date();
            var year = now.getFullYear();
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var date = String(now.getDate()).padStart(2, '0');
            document.getElementById('ad-tanggal').value = year + '-' + month + '-' + date;

            var hour = String(now.getHours()).padStart(2, '0');
            var min = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('ad-jam').value = hour + ':' + min;

            document.getElementById('adimeModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function openRiwayatFromAddModal() {
            var noRawat = document.getElementById('ad-noRawat').value;
            if (!noRawat) {
                alert('Data pasien belum dimuat.');
                return;
            }
            openRiwayatAdimeModal(noRawat);
        }

        function closeInputAdimeModal() {
            document.getElementById('adimeModal').classList.remove('active');
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.classList.remove('modal-open');
            }
        }

        // Form Submission
        document.getElementById('btn-save-adime')?.addEventListener('click', async function() {
            var form = document.getElementById('formAsuhanGizi');

            var noRawat = document.getElementById('ad-noRawat').value;
            var tanggal = document.getElementById('ad-tanggal').value;
            var jam = document.getElementById('ad-jam').value;
            var nip = document.getElementById('ad-nip').value;

            if (!noRawat) { alert('No. Rawat wajib diisi.'); return; }
            if (!tanggal) { alert('Tanggal wajib diisi.'); return; }
            if (!jam) { alert('Jam wajib diisi.'); return; }
            if (!nip) { alert('Petugas wajib dipilih.'); return; }

            var btn = this;
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            try {
                var formData = new FormData(form);
                var response = await fetch('/instalasi-gizi/asuhan/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var result = await response.json();

                if (response.ok && result.success) {
                    alert(result.message || 'Catatan ADIME Gizi berhasil disimpan.');
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal menyimpan catatan ADIME.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server saat menyimpan.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Catatan ADIME';
            }
        });

        var activeRiwayatData = [];

        async function openRiwayatAdimeModal(noRawat) {
            var body = $('#riwayatAdimeBody');
            body.html(`
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    Loading riwayat ADIME...
                </div>
            `);

            document.getElementById('riwayatAdimeModal').classList.add('active');
            document.body.classList.add('modal-open');

            try {
                // We encode noRawat as it contains slashes
                var encodedNoRawat = encodeURIComponent(noRawat);
                var response = await fetch('/instalasi-gizi/asuhan/riwayat-pasien/' + encodedNoRawat);
                var result = await response.json();

                if (response.ok && result.success) {
                    var data = result.data;
                    activeRiwayatData = data;
                    body.empty();

                    if (data.length === 0) {
                        body.html(`
                            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada riwayat catatan ADIME Gizi untuk pasien ini.
                            </div>
                        `);
                        return;
                    }

                    data.forEach(function(item, idx) {
                        var isCreator = (item.nip && currentUserId && String(item.nip).trim() === String(currentUserId).trim());
                        
                        var editBtnHtml = isCreator ? `
                            <button type="button" class="btn-filter" onclick="editAdimeRecord(${idx})" style="padding: 4px 10px; font-size: 11px; height: auto; display: inline-flex; align-items: center; gap: 4px; border-radius: 4px; background-color: #3B82F6;" title="Edit catatan tanggal ini">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                        ` : '';

                        var deleteBtnHtml = isCreator ? `
                            <button type="button" class="btn-filter" onclick="deleteAdimeRecord(${idx})" style="padding: 4px 10px; font-size: 11px; height: auto; display: inline-flex; align-items: center; gap: 4px; border-radius: 4px; background-color: #EF4444;" title="Hapus catatan ADIME ini">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        ` : '';

                        var card = $(`
                            <div class="modal-section" style="margin-bottom: 20px;">
                                <div class="modal-section-title" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px dashed var(--border); padding-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span>Pemeriksaan Tanggal: ${fmtDateTime(item.tanggal)}</span>
                                        <button type="button" class="btn-filter" onclick="copyAdimeToInput(${idx})" style="padding: 4px 12px; font-size: 11px; height: auto; display: inline-flex; align-items: center; gap: 5px; border-radius: 4px; background-color: #10B981; font-weight: 700;" title="Gunakan dan salin data catatan ini sebagai inputan baru">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                            Gunakan Sebagai Inputan
                                        </button>
                                        ${editBtnHtml}
                                        ${deleteBtnHtml}
                                    </div>
                                    <span style="font-size: 11px; text-transform: none; color: var(--text-muted); font-weight: 600;">Petugas: ${item.nama_petugas || item.nip}</span>
                                </div>
                                <div class="modal-grid" style="grid-template-columns: 1fr; gap: 14px;">
                                    <div class="modal-field">
                                        <span class="ml">Asesmen</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.asesmen || '—'}</div>
                                    </div>
                                    <div class="modal-field">
                                        <span class="ml">Diagnosis</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.diagnosis || '—'}</div>
                                    </div>
                                    <div class="modal-field">
                                        <span class="ml">Intervensi</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.intervensi || '—'}</div>
                                    </div>
                                    <div class="modal-field">
                                        <span class="ml">Monitoring</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.monitoring || '—'}</div>
                                    </div>
                                    <div class="modal-field">
                                        <span class="ml">Evaluasi</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.evaluasi || '—'}</div>
                                    </div>
                                    <div class="modal-field">
                                        <span class="ml">Instruksi</span>
                                        <div class="mv" style="white-space: pre-line; background: #F8FAFC; padding: 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 13px;">${item.instruksi || '—'}</div>
                                    </div>
                                </div>
                            </div>
                        `);
                        body.append(card);
                    });
                } else {
                    body.html(`
                        <div style="text-align: center; padding: 40px; color: #EF4444; font-weight: 500;">
                            ${result.message || 'Gagal memuat data riwayat.'}
                        </div>
                    `);
                }
            } catch (err) {
                console.error(err);
                body.html(`
                    <div style="text-align: center; padding: 40px; color: #EF4444; font-weight: 500;">
                        Terjadi kesalahan jaringan atau server saat memuat riwayat.
                    </div>
                `);
            }
        }

        function editAdimeRecord(index) {
            var item = activeRiwayatData[index];
            if (!item) return;

            var isCreator = (item.nip && currentUserId && String(item.nip).trim() === String(currentUserId).trim());
            if (!isCreator) {
                alert('Anda hanya dapat mengedit catatan ADIME yang Anda buat sendiri. Untuk menggunakan data akun lain, silakan klik tombol "Gunakan Sebagai Inputan" untuk menyalin.');
                return;
            }

            // Close history modal
            closeRiwayatAdimeModal();

            // Populate form fields
            document.getElementById('formAsuhanGizi').reset();
            document.getElementById('ad-old-tanggal').value = item.tanggal || '';
            document.getElementById('ad-noRawat').value = item.no_rawat || '';

            // Tampilkan tombol hapus jika dibuat oleh user yang sedang login
            var isCreator = (item.nip && currentUserId && String(item.nip).trim() === String(currentUserId).trim());
            if (isCreator) {
                $('#btn-delete-adime').show();
            } else {
                $('#btn-delete-adime').hide();
            }

            // Get patient info from pasienData cache if available
            var p = pasienData[item.no_rawat];
            if (p) {
                document.getElementById('ad-noRm').value = p.no_rkm_medis || '';
                document.getElementById('ad-namaPasien').value = p.nm_pasien || '';
            }

            // Populate date and time
            if (item.tanggal) {
                var parts = item.tanggal.split(' ');
                if (parts[0]) {
                    document.getElementById('ad-tanggal').value = parts[0];
                }
                if (parts[1]) {
                    var timeParts = parts[1].split(':');
                    document.getElementById('ad-jam').value = (timeParts[0] || '00') + ':' + (timeParts[1] || '00');
                }
            }

            // Populate text fields
            document.getElementsByName('asesmen')[0].value = item.asesmen || '';
            document.getElementsByName('diagnosis')[0].value = item.diagnosis || '';
            document.getElementsByName('intervensi')[0].value = item.intervensi || '';
            document.getElementsByName('monitoring')[0].value = item.monitoring || '';
            document.getElementsByName('evaluasi')[0].value = item.evaluasi || '';
            document.getElementsByName('instruksi')[0].value = item.instruksi || '';

            // Open input modal
            document.getElementById('adimeModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function copyAdimeToInput(index) {
            var item = activeRiwayatData[index];
            if (!item) return;

            // Pastikan modal input terbuka dan terisi data pasien
            var curNoRawat = document.getElementById('ad-noRawat').value;
            if (!curNoRawat && item.no_rawat) {
                var p = pasienData[item.no_rawat];
                if (p) {
                    openInputAdimeModal(item.no_rawat);
                }
            }

            // Salin isian teks ADIME dari riwayat yang dipilih
            document.getElementsByName('asesmen')[0].value = item.asesmen || '';
            document.getElementsByName('diagnosis')[0].value = item.diagnosis || '';
            document.getElementsByName('intervensi')[0].value = item.intervensi || '';
            document.getElementsByName('monitoring')[0].value = item.monitoring || '';
            document.getElementsByName('evaluasi')[0].value = item.evaluasi || '';
            document.getElementsByName('instruksi')[0].value = item.instruksi || '';

            // Pastikan old_tanggal kosong agar disimpan sebagai DATA BARU
            document.getElementById('ad-old-tanggal').value = '';

            // Tutup modal riwayat
            closeRiwayatAdimeModal();

            // Tampilkan notifikasi keberhasilan di form input
            var notice = document.getElementById('ad-copy-notice');
            var noticeText = document.getElementById('ad-copy-notice-text');
            if (notice && noticeText) {
                noticeText.innerText = 'Data ADIME berhasil disalin dari riwayat tanggal ' + fmtDateTime(item.tanggal) + '. Silakan sesuaikan jika diperlukan lalu simpan.';
                notice.style.display = 'flex';
                notice.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function closeRiwayatAdimeModal() {
            document.getElementById('riwayatAdimeModal').classList.remove('active');
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.classList.remove('modal-open');
            }
        }

        async function deleteAdimeRecord(index) {
            var item = activeRiwayatData[index];
            if (!item) return;

            if (!currentUserId || String(item.nip).trim() !== String(currentUserId).trim()) {
                alert('Anda hanya dapat menghapus catatan ADIME yang Anda buat sendiri.');
                return;
            }

            if (!confirm('Apakah Anda yakin ingin menghapus catatan ADIME tanggal ' + fmtDateTime(item.tanggal) + '?\nData yang dihapus tidak dapat dikembalikan.')) {
                return;
            }

            try {
                var response = await fetch('/instalasi-gizi/asuhan/destroy', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        no_rawat: item.no_rawat,
                        tanggal: item.tanggal
                    })
                });

                var result = await response.json();

                if (response.ok && result.success) {
                    alert(result.message || 'Catatan ADIME Gizi berhasil dihapus.');
                    // Muat ulang daftar riwayat pasien
                    openRiwayatAdimeModal(item.no_rawat);
                } else {
                    alert(result.message || 'Gagal menghapus catatan ADIME.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server saat menghapus catatan.');
            }
        }

        async function deleteCurrentAdime() {
            var noRawat = document.getElementById('ad-noRawat').value;
            var oldTanggal = document.getElementById('ad-old-tanggal').value;

            if (!noRawat || !oldTanggal) {
                alert('Tidak ada catatan ADIME tersimpan yang dipilih untuk dihapus.');
                return;
            }

            if (!confirm('Apakah Anda yakin ingin menghapus catatan ADIME ini?\nTindakan ini tidak dapat dibatalkan.')) {
                return;
            }

            var btn = document.getElementById('btn-delete-adime');
            var origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerText = 'Menghapus...';

            try {
                var response = await fetch('/instalasi-gizi/asuhan/destroy', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        no_rawat: noRawat,
                        tanggal: oldTanggal
                    })
                });

                var result = await response.json();

                if (response.ok && result.success) {
                    alert(result.message || 'Catatan ADIME Gizi berhasil dihapus.');
                    closeInputAdimeModal();
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal menghapus catatan ADIME.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server saat menghapus catatan.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }

        function fmtDateTime(dt) {
            if (!dt) return '—';
            // Handles different database datetime formats cleanly
            var cleanDt = typeof dt === 'string' ? dt.replace(/-/g, '/') : dt;
            var d = new Date(cleanDt);
            if (isNaN(d.getTime())) return dt;
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            var hh = String(d.getHours()).padStart(2, '0');
            var min = String(d.getMinutes()).padStart(2, '0');
            return dd + '/' + mm + '/' + d.getFullYear() + ' ' + hh + ':' + min;
        }

        // Close on click outside
        document.getElementById('riwayatAdimeModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRiwayatAdimeModal();
        });
    </script>
</body>
</html>
